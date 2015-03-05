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
			var Smenu = 1;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventpresent/requestArticleList",
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
				if ( $("#evt_id").val() == "" || $("#evt_id").val() == null )
				{
					alert("아이디");
					$("#evt_id").focus();
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

			$(".layerClose").click(function () {
				$(".layerPop").hide();
			});

			$("#btnConf1").click(function () {
				if ( confirm("적용 하시겠습니까?") )
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/eventpresent/requestPresentEventInsert",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"evt_id\":\"" + $("#evt_id").val() + "\", \"evt_category\":\"" + $("#evt_category > option:selected").val() + "\",\"evt_target\":\"" + $("#evt_target > option:selected").val() + "\",\"evt_value\":\"" + $("#evt_value").val() + "\",\"evt_reason\":\"" + $("#evt_reason").val() + "\"\}"},
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

								$("#evt_reason").val("");
								$("#creatAlert1").hide();
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
		});

		function selarray( mCategory, mArticle_type, mId, mGrade, mText )
		{
			this.category = mCategory;
			this.article_type = mArticle_type;
			this.id = mId;
			this.grade = mGrade;
			this.text = mText;
		}

		function eventarray( mIdx, mReason, mArticle_type, mEvt_category, mEvt_target, mEvt_value, mReg_date )
		{
			this.idx = mIdx;
			this.reason = mReason;
			this.article_type = mArticle_type;
			this.evt_category = mEvt_category;
			this.evt_target = mEvt_target;
			this.evt_value = mEvt_value;
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
					strEventInfo += "<td class=\"alignC\"><a href=\"javascript:showTargets(" + evtArray[i].idx + ");\" class=\"btn_basic\">보기</a></td>";
					strEventInfo += "<td>" + evtArray[i].evt_target + "(" + evtArray[i].evt_category + ") - " + evtArray[i].evt_value + "</td>";
					strEventInfo += "<td>" + evtArray[i].reason + "</td>";
				}
				$("#eventTbody").html(strEventInfo);
				$(".paging").children("ul").children("li").removeClass("on");
				$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
				$("#eventTbody > tr").css("cursor", "pointer");
			}
			else
			{
				strEventInfo = "<tr><td style=\"text-align:center;\" colspan=\"4\">등록된 이벤트가 없습니다.</td></tr>"
				$("#eventTbody").html(strEventInfo);
			}
		}

		function getEventList()
		{
			evtArray = new Array();
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventpresent/requestPresentEventList",
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
								evtArray.push( new eventarray(obj.arrResult[i].idx, obj.arrResult[i].evt_reason, obj.arrResult[i].article_type, obj.arrResult[i].evt_category, obj.arrResult[i].evt_target, obj.arrResult[i].evt_value, obj.arrResult[i].reg_date ) );
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

		function showTargets( idx )
		{
			$(".layerPop").hide();
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventpresent/requestPresentEventSubList",
				type:"POST",
				dataType:"json",
				async:false,
				data: {"data":"\{\"evt_id\":\"" + idx + "\"\}"},
				success:function(data)
				{
					var obj = eval(data);
					var strHtml = "";
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						if ( obj.arrResult.length > 0 )
						{
							for ( var i = 0; i < obj.arrResult.length; i++ )
							{
								strHtml += "<tr><td>" + obj.arrResult[i].pid + "</td></tr>";
							}
							$("#targetTbody").html(strHtml);
							$("#showTargets").show();
						}
						else
						{
							alert("데이터가 없습니다.");
						}
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
		<div class="section">
			<h2>이벤트관리 - 지급 관리</h2>
			<!-- 보상 -->
			<!--<div class="container basicData">
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="" />
						</colgroup>
						<tbody>
							<tr>
								<th>사유</th>
								<td><input type="text" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>-->
			<!--// 보상 -->
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>대상 (캐릭터명 or ID 선택입력, 쉼표 or 엔터로 구분)</h3>
					<span style="float: right;margin-right: 10px;">총 0명</span>
				</div>
				<textarea style="margin-left:10px;width:98%;height:50px;" id="evt_id" name="evt_id"></textarea>
				<!--<a href="#" class="btn_action bicBtn">확인</a>-->
			</div>
			<!--//container-->
			<!-- 보상 -->
			<div class="container basicData">
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="" />
							<col width="10%" />
							<col width="" />
						</colgroup>
						<tbody>
							<tr>
								<th>보상</th>
								<td>
									<select name="evt_category" id="evt_category">
									</select>
									<select name="evt_target" id="evt_target">
									</select>
								</td>
								<th>수량</th>
								<td><input type="text" name="evt_value" id="evt_value" style="width:50px" value="" /> <!--<a href="#" class="btn_action">추가</a>--></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--// 보상 -->
			<!--container-->
			<!--<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col width="50px"/>
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2"></th>
								<th class="bb2">종류</th>
								<th class="bb2">상세</th>
								<th class="bb2">개수</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignC">1</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">3</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">4</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">5</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>-->
			<!--//container-->
			<div class="btnArea alignL" style="padding-top:0; padding-left:0;text-align:center;">
				<a href="javascript:void(0);" id="btnInsert" class="btn_action">지급</a>
			</div>

			<!--container-->
			<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col width="200px" />
							<col width="100px"/>
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">지급일</th>
								<th class="bb2">대상</th>
								<th class="bb2">보상</th>
								<th class="bb2">사유</th>
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
				<a href="이전" class="prev"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="다음" class="next"></a>
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
	<div class="layerPop" style="display:none; top:300px; left:50%; margin-left:-110px" id="showTargets">
		<div class="container">
			<div class="board_list" style="width:220px;max-height: 300px;overflow-y: auto;">
				<table>
					<colgroup>
						<col width="200px" />
					</colgroup>
					<thead>
						<tr>
							<th class="bb2">아이디</th>
						</tr>
					</thead>
					<tbody id="targetTbody">
					</tbody>
				</table>
			</div>
		</div>
		<div class="btnArea alignC">
			<a href="javascript:void(0);" class="btn_basic sm layerClose">닫기</a>
		</div>
	</div>
</div>
</body>
</html>
