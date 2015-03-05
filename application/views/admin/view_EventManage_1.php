<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var selArray = new Array();
		var evtArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;
		$(document).ready(function () {
			var Lmenu = 5;
			var Smenu = 0;
			var subNav = <?php echo $subnavi; ?>;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			if ( $(".subNav") )
			{
				$(".subNav > li").eq(subNav).addClass("on");
			}

			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventmanage/requestArticleList",
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						var objCategory = new Array();
						var objArticleType = new Array();
						for ( var i = 0; i < obj.arrResult.length; i++ )
						{
							objCategory.push(obj.arrResult[i].category);
							objArticleType.push(obj.arrResult[i].article_type);
						}
						objCategory = unique(objCategory);
						objArticleType = unique(objArticleType);

						var strTargetHtml = "<option value=\"\"> = 선택 = </option>";
						for ( var i = 0; i < objCategory.length; i++ )
						{
							strTargetHtml += "<option value=\"" + objArticleType[i] + "\">" + objCategory[i] + "</option>";
						}
						$("#evt_category").html(strTargetHtml);

						strTargetHtml = "";
						for ( var i = 0; i < obj.arrResult.length; i++ )
						{
							selArray.push( new selarray(obj.arrResult[i].category, obj.arrResult[i].article_type, obj.arrResult[i].article_id, obj.arrResult[i].grade, obj.arrResult[i].<?php echo MY_Controller::COMMON_LANGUAGE_COLUMN ?>) );
						}
						$("#evt_target").html("<option value=\"\"> = 선택 = </option>");
					}
					else
					{
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});

			getEventList();
			eventPage(1);

			$(document).on("change", "#evt_category", function () {
				var filteredArr = new Array();
				filteredArr = $.grep(selArray, function( obj, i ) {
					return ( obj.article_type == $("#evt_category > option:selected").val() );
				});
				var strTargetHtml = "<option value=\"\"> = 선택 = </option>";
				for ( var i = 0; i < filteredArr.length; i++ )
				{
					if ( filteredArr[i].grade == null )
					{
						strTargetHtml += "<option value=\"" + filteredArr[i].id + "\">" + filteredArr[i].text + "</option>";
					}
					else
					{
						strTargetHtml += "<option value=\"" + filteredArr[i].id + "\">★" + filteredArr[i].grade + " " + filteredArr[i].text + "</option>";
					}
				}
				$("#evt_target").html(strTargetHtml);
			});

			$("#btnInsert").click(function () {
				if ( $("#start_date").val() == "" || $("#start_date").val() == null )
				{
					alert("시작일");
					$("#start_date").focus();
					return;
				}
				if ( $("#start_hour").val() == "" || $("#start_hour").val() == null )
				{
					alert("시작시간");
					$("#start_hour").focus();
					return;
				}
				if ( $("#start_min").val() == "" || $("#start_min").val() == null )
				{
					alert("시작시간");
					$("#start_min").focus();
					return;
				}
				if ( $("#end_date").val() == "" || $("#end_date").val() == null )
				{
					alert("종료일");
					$("#end_date").focus();
					return;
				}
				if ( $("#end_hour").val() == "" || $("#end_hour").val() == null )
				{
					alert("종료시간");
					$("#end_hour").focus();
					return;
				}
				if ( $("#end_min").val() == "" || $("#end_min").val() == null )
				{
					alert("종료시간");
					$("#end_min").focus();
					return;
				}
				if ( $("#evt_category").val() == "" || $("#evt_category").val() == null )
				{
					alert("카테고리");
					$("#evt_category").focus();
					return;
				}
				if ( $("#evt_target").val() == "" || $("#evt_target").val() == null )
				{
					alert("대상상품");
					$("#evt_target").focus();
					return;
				}
				if ( $("#evt_value").val() == "" || $("#evt_value").val() == null )
				{
					alert("개수");
					$("#evt_value").focus();
					return;
				}
				$("#creatAlert1").show();
			});

			$(".alertClose").click(function () {
				$("#evt_reason").val("");
				$(".alertPop").hide();
			});

			$("#btnConf1").click(function () {
				if ( confirm("적용 하시겠습니까?") )
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/eventmanage/requestAccEventInsert",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"start_date\":\"" + $("#start_date").val() + " " + $("#start_hour > option:selected").val() + ":" + $("#start_min > option:selected").val() + ":00\",\"end_date\":\"" + $("#end_date").val() + " " + $("#end_hour > option:selected").val() + ":" + $("#end_min > option:selected").val() + ":00\",\"evt_category\":\"" + $("#evt_category > option:selected").val() + "\",\"evt_target\":\"" + $("#evt_target > option:selected").val() + "\",\"evt_value\":\"" + $("#evt_value").val() + "\",\"evt_reason\":\"" + $("#evt_reason").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								alert("등록되었습니다.");
								$("#start_date").val("");
								$("#start_hour > option:selected").prop("selected", false);
								$("#start_min > option:selected").prop("selected", false);
								$("#end_date").val("");
								$("#end_hour > option:selected").prop("selected", false);
								$("#end_min > option:selected").prop("selected", false);
								$("#evt_category > option:selected").prop("selected", false)
								$("#evt_target > option:selected").prop("selected", false)
								$("#evt_value").val("");
								$("#evt_reason").val("");

								getEventList();
								eventPage(1);
							}
							else
							{
								alert(obj.resultText);
							}
						},
						error:function( request, status, error )
						{
				        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				       	}
					});
				}
				else
				{
					$("#evt_reason").val("");
					$("#creatAlert1").hide();
				}
			});

			$(document).on("click", "a[name=btnStop]", function () {
				if ( confirm("해당 이벤트를 바로 중지 하시겠습니까?") )
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/eventmanage/requestAccEventStop",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"idx\":\"" + $(this).attr("id") + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								getEventList();
								eventPage(1);
							}
							else
							{
								alert(obj.resultText);
							}
						},
						error:function( request, status, error )
						{
				        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				       	}
					});
				}
			});

			$(document).on("click", "#eventTbody > tr", function () {
				var startday = $(this).children("td").eq(1).html().split(" ");
				var endday = $(this).children("td").eq(2).html().split(" ");
				var starthour = startday[1].split(":");
				var endhour = endday[1].split(":");

				$("#start_date").val(startday[0]);
				$("#end_date").val(endday[0]);
				$("#start_hour").val(starthour[0]);
				$("#start_min").val(starthour[1]);
				$("#end_hour").val(endhour[0]);
				$("#end_min").val(endhour[1]);
			});
		});

		function selarray( mCategory, mArticle_type, mId, mGrade, mText )
		{
			this.category = mCategory;
			this.article_type = mArticle_type;
			this.id = mId;
			this.grade = mGrade;
			this.text = mText;
		}

		function eventarray( mIdx, mStart_date, mEnd_date, mArticle_type, mEvt_category, mEvt_target, mEvt_value, mIs_valid, mReg_date )
		{
			this.idx = mIdx;
			this.start_date = mStart_date;
			this.end_date = mEnd_date;
			this.article_type = mArticle_type;
			this.evt_category = mEvt_category;
			this.evt_target = mEvt_target;
			this.evt_value = mEvt_value;
			this.is_valid = mIs_valid;
			this.reg_date = mReg_date;
		}

		function eventPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:eventPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:eventPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:eventPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:eventPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);
			var strEventInfo = ""
			if ( evtArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < evtArray.length && i < ( page * pageSize ); i++ )
				{
					strEventInfo += "<tr>";
					strEventInfo += "<td class=\"alignC\">" + evtArray[i].reg_date + "</td>";
					strEventInfo += "<td>" + evtArray[i].start_date + "</td>";
					strEventInfo += "<td>" + evtArray[i].end_date + "</td>";
					strEventInfo += "<td>" + evtArray[i].evt_target + "(" + evtArray[i].evt_category + ") - " + evtArray[i].evt_value + "</td>";
					var thisday, startday, endday, diffstart, diffend, tempstart, tempend, tempstartday, tempendday, tempstarthour, tempendhour;
					if ( evtArray[i].is_valid == 1 )
					{
						thisday = new Date("<?php echo date("Y") ?>", "<?php echo (date("m") - 1) ?>", "<?php echo date("d") ?>", "<?php echo date("H") ?>", "<?php echo date("i") ?>", "<?php echo date("s") ?>");
						tempstart = evtArray[i].start_date.split(" ");
						tempend = evtArray[i].end_date.split(" ");

						tempstartday = tempstart[0].split("-");
						tempstarthour = tempstart[1].split(":");
						tempendday = tempend[0].split("-");
						tempendhour = tempend[1].split(":");

						startday = new Date( tempstartday[0], tempstartday[1] - 1, tempstartday[2], tempstarthour[0], tempstarthour[1], tempstarthour[2] );
						endday = new Date( tempendday[0], tempendday[1] - 1, tempendday[2], tempendhour[0], tempendhour[1], tempendhour[2] );

						diffstart = new Date(thisday.getTime() - startday.getTime());
						diffend = new Date(endday.getTime() - thisday.getTime());

						if ( diffstart < 0 && diffend > 0 )
						{
							strEventInfo += "<td style=\"text-align:center;\"><a style=\"cursor:pointer;\" id=\"" + evtArray[i].idx;
							strEventInfo += "\" name=\"btnStop\" class=\"btn_basic\">대기중</a></td></tr>";
						}
						else if ( diffstart >= 0 && diffend < 0 )
						{
							strEventInfo += "<td style=\"text-align:center;\"><a href=\"javascript:void(0);\" id=\"" + evtArray[i].idx;
							strEventInfo += "\" class=\"btn_basic\">종료</a></td></tr>";
						}
						else
						{
							strEventInfo += "<td style=\"text-align:center;\"><a style=\"cursor:pointer;\" id=\"" + evtArray[i].idx;
							strEventInfo += "\" name=\"btnStop\" class=\"btn_action\">중지</a></td></tr>";
						}
					}
					else
					{
						strEventInfo += "<td style=\"text-align:center;\"><a class=\"btn_basic\">미사용</a></td></tr>";
					}
				}
				$("#eventTbody").html(strEventInfo);
				$(".paging").children("ul").children("li").removeClass("on");
				$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
				$("#eventTbody > tr").css("cursor", "pointer");
			}
			else
			{
				strEventInfo = "<tr><td style=\"text-align:center;\" colspan=\"5\">등록된 이벤트가 없습니다.</td></tr>"
				$("#eventTbody").html(strEventInfo);
			}
		}

		function getEventList()
		{
			evtArray = new Array();
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventmanage/requestAccEventList",
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						if ( obj.arrResult.length > 0 )
						{
							for ( var i = 0; i < obj.arrResult.length; i++ )
							{
								evtArray.push( new eventarray(obj.arrResult[i].idx, obj.arrResult[i].start_date, obj.arrResult[i].end_date, obj.arrResult[i].article_type, obj.arrResult[i].evt_category, obj.arrResult[i].evt_target, obj.arrResult[i].evt_value, obj.arrResult[i].is_valid, obj.arrResult[i].reg_date ) );
							}
						}
						totPage = ( Math.ceil( evtArray.length / pageSize ) < 1 ? 1 : Math.ceil( evtArray.length / pageSize ) );
					}
					else
					{
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
		}
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<?php include_once  APPPATH."views/include/header.php"; ?>
	<!-- contents -->
	<div id="contents" class="contents">
		<!--section-->
		<?php include_once  APPPATH."views/include/subnavi_event.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="9%" />
							<col width="" />
							<col width="9%" />
							<col width="" />
							<col width="9%" />
							<col width="" />
						</colgroup>
						<tbody>
							<tr>
								<th>시작일</th>
								<td colspan="5">
									<input type="text" class="inputTit datepicker" id="start_date" name="start_date" style="width:100px" value="" />&nbsp;&nbsp;
									<span class="timePicker">
										<select id="start_hour" id="start_hour">
											<option value="">= 선택 =</option>
<?php
	for ( $i = 0; $i < 24; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 시
										<select id="start_min" id="start_min">
											<option value="">= 선택 =</option>
<?php
	for ( $i = 0; $i < 60; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 분
									</span>
								</td>
							</tr>
							<tr>
								<th>종료일</th>
								<td colspan="5">
									<input type="text" class="inputTit datepicker" id="end_date" name="end_date" style="width:100px" value="" />&nbsp;&nbsp;
									<span class="timePicker">
										<select name="end_hour" id="end_hour">
											<option value="">= 선택 =</option>
<?php
	for ( $i = 0; $i < 24; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 시
										<select name="end_min" id="end_min">
											<option value="">= 선택 =</option>
<?php
	for ( $i = 0; $i < 60; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 분
									</span>
								</td>
							</tr>
							<tr>
								<th>카테고리</th>
								<td>
									<select name="evt_category" id="evt_category">
									</select>
								</td>
								<th>대상상품</th>
								<td>
									<select name="evt_target" id="evt_target">
									</select>
								</td>
								<th>개수</th>
								<td><input type="text" name="evt_value" id="evt_value" style="width:50px" value="" />개</td>
							</tr>
							<tr>
								<td colspan="6" style="text-align: center;"><a href="javascript:void(0);" id="btnInsert" class="btn_action">적용</a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<h3>이벤트 리스트</h3>
				<!-- board_list -->
				<div class="board_list">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col width="80" />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">등록일</th>
								<th class="bb2">시작일</th>
								<th class="bb2">종료일</th>
								<th class="bb2">내용</th>
								<th class="bb2">상태</th>
							</tr>
						</thead>
						<tbody id="eventTbody">
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="#" class="prev" title="이전"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="#" class="next" title="다음"></a>
			</div>
			<!-- //Paging -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->

	<div class="alertPop" style="display:none; top:300px; left:50%; margin-left:-50px" id="creatAlert1">
		<p>적용 사유를 적으세요.</p>
		<input type="text" id="evt_reason" name="evt_reason" value="" />
		<div class="btnArea alignC">
			<a href="javascript:void(0);" id="btnConf1" class="btn_action sm addUiBtn">확인</a>
			<a href="javascript:void(0);" class="btn_basic sm alertClose">취소</a>
		</div>
	</div>
</div>
</body>
</html>
