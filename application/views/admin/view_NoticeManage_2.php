<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var banArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;

		$(document).ready(function () {
			var Lmenu = 2;
			var Smenu = 2;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			getBannerList();

			$("#btnInsert").click(function () {
				if ( $("#banner_url").val() == "" || $("#banner_url").val() == null )
				{
					alert("이미지");
					$("#banner_url").focus();
					return;
				}

				if ( $("#banner_link").val() == "" || $("#banner_link").val() == null )
				{
					alert("링크");
					$("#banner_link").focus();
					return;
				}

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

				if ( $("#banner_target").val() == "" || $("#banner_target").val() == null )
				{
					alert("대상");
					$("#banner_target").focus();
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

				if ( $("#banner_url").val() == "" || $("#banner_url").val() == null )
				{
					alert("내용타입");
					$("#banner_url").focus();
					return;
				}

				if ( $("#banner_link").val() == "" || $("#banner_link").val() == null )
				{
					alert("내용타입");
					$("#banner_link").focus();
					return;
				}

				var data = new FormData();
				$.each($('#banner_url')[0].files, function(i, file) {
		            data.append("banner_url", file);
		        });

				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/uploadImage/4",
					processData: false,
					contentType: false,
					dataType:"json",
					type:"POST",
					async:false,
					data: data,
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							insertBanner( obj.arrResult.file_name );
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
			});
		});

		function insertBanner( file_name )
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestBannerListInsert",
				type:"POST",
				dataType:"json",
				async:false,
				data: {"data":"\{\"banner_url\":\"" + file_name + "\", \"start_date\":\"" + $("#start_date").val() + " " + $("#start_hour > option:selected").val() + ":" + $("#start_min > option:selected").val() + ":00\",\"end_date\":\"" + $("#end_date").val() + " " + $("#end_hour > option:selected").val() + ":" + $("#end_min > option:selected").val() + ":00\",\"banner_target\":\"" + $("#banner_target").val() + "\",\"banner_link\":\"" + $("#banner_link").val() + "\"\}"},
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						alert("등록되었습니다.");
						$("#start_date").val("");
						$("#start_hour").val("00");
						$("#start_min").val("00");
						$("#end_date").val("");
						$("#end_hour").val("00");
						$("#end_min").val("00");
						$("#banner_target").val("ALL");
						$("#banner_link").val("");
						$("#banner_url").val("");
						getBannerList();
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

		function banarray( mIdx, mReg_datetime, mStart_date, mEnd_date, mBanner_url, mBanner_link )
		{
			this.idx = mIdx;
			this.reg_datetime = mReg_datetime;
			this.start_date = mStart_date;
			this.end_date = mEnd_date;
			this.banner_url = mBanner_url;
			this.banner_link = mBanner_link;
		}

		function bannerListPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:bannerListPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:bannerListPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:bannerListPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:bannerListPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$("#paging").html(strPaging);
			var strBannerInfo = ""
			if ( banArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < banArray.length && i < ( page * pageSize ); i++ )
				{
					strBannerInfo += "<tr>";
					strBannerInfo += "<td class=\"alignC\">" + banArray[i].idx + "</td>";
					strBannerInfo += "<td>" + banArray[i].reg_datetime + "</td>";
					strBannerInfo += "<td>" + banArray[i].start_date + "</td>";
					strBannerInfo += "<td>" + banArray[i].end_date + "</td>";
					strBannerInfo += "<td>" + banArray[i].banner_link + "</td>";
					strBannerInfo += "<td class=\"alignC\"><!--<a href=\"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/form/0/" + banArray[i].idx + "\" class=\"btn_basic\">수정</a>&nbsp;&nbsp;-->";
					strBannerInfo += "<a href=\"javascript:delBannerList('" + banArray[i].idx + "');\" class=\"btn_basic\">삭제</a></td>";
					strBannerInfo += "</tr>";
				}
				$("#bannerTbody").html(strBannerInfo);
				$("#paging").children("ul").children("li").removeClass("on");
				$("#paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
			}
			else
			{
				strBannerInfo = "<tr><td style=\"text-align:center;\" colspan=\"6\">등록된 이미지 배너가 없습니다.</td></tr>"
				$("#bannerTbody").html(strBannerInfo);
			}
		}

		function delBannerList( idx )
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/del/2/" + idx,
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						alert("삭제되었습니다.");
						getBannerList();
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

		function getBannerList()
		{
			banArray = new Array();
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestBannerList",
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						for ( var i = 0; i < obj.arrResult.length; i++ )
						{
							banArray.push( new banarray(obj.arrResult[i].idx, obj.arrResult[i].reg_datetime, obj.arrResult[i].start_date, obj.arrResult[i].end_date, obj.arrResult[i].banner_url, obj.arrResult[i].banner_link) );
						}
						totPage = ( Math.ceil( banArray.length / pageSize ) < 1 ? 1 : Math.ceil( banArray.length / pageSize ) );
						bannerListPage(1);
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
			<h2>공지관리 - 이미지 배너</h2>
			<!-- 이미지 공지 -->
			<div class="container">
				<div class="titArea">
					<h3>등록</h3>
				</div>
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="" />
							<col width="10%" />
							<col width="40%" />
						</colgroup>
						<tbody>
							<tr>
								<th>시작일</th>
								<td>
									<input type="text" class="inputTit datepicker" name="start_date" id="start_date" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="start_hour" id="start_hour">
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
										<select name="start_min" id="start_min">
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
								<th>대상</th>
								<td>
									<select name="banner_target" id="banner_target">
										<option value="ALL">전체</option>
										<option value="AND">안드로이드</option>
										<option value="IOS">아이폰</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>종료일</th>
								<td colspan="3">
									<input type="text" class="inputTit datepicker" name="end_date" id="end_date" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="end_hour" id="end_hour">
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
								<th>이미지</th>
								<td colspan="3">
									<input type="file" name="banner_url" id="banner_url" class="inputTit" />
								</td>
							</tr>
							<tr>
								<th>링크</th>
								<td colspan="3">
									<input type="text" name="banner_link" id="banner_link" class="inputTit" style="width:97%;" />
								</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: center;">
									<div class="btnArea">
										<a href="javascript:void(0);" id="btnInsert" class="btn_action">설정</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--// 이미지 공지 -->

			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>리스트</h3>
				</div>
				<div class="board_list">
					<table>
						<colgroup>
							<col width="50px"/>
							<col width="140px" />
							<col width="140px" />
							<col width="140px" />
							<col />
							<col width="120px" />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2"></th>
								<th class="bb2">등록일</th>
								<th class="bb2">시작일</th>
								<th class="bb2">종료일</th>
								<th class="bb2">내용</th>
								<th class="bb2"></th>
							</tr>
						</thead>
						<tbody id="bannerTbody">
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging" id="paging">
				<a href="이전" class="prev"></a>
				<ul>
					<li><strong>1</strong></li>
				</ul>
				<a href="다음" class="next"></a>
				<div class="btnPosR"><a href="#" class="btn_action" onclick="$('#creatAlert1').show(); return false"><span>취소</span></a></div>
			</div>
			<!-- //Paging -->
			<!--공지 리스트-->
			<!-- <div class="container">
				<div class="titArea">
					<h3>공지 리스트</h3>
				</div>
				<div class="board_list">
					<table>
						<colgroup>
							<col width="50px"/>
							<col />
							<col />
							<col />
							<col />
							<col width="100px" />
						</colgroup>
						<thead>
							<tr>
								<th></th>
								<th>등록일</th>
								<th>시작일</th>
								<th>종료일</th>
								<th>내용</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>지급이벤트</td>
								<td class="alignC"><a href="#" class="btn_basic">미리보기</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>푸쉬알림</td>
								<td class="alignC"><a href="#" class="btn_basic">자세히</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>푸쉬알림</td>
								<td class="alignC"><a href="#" class="btn_basic">자세히</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>푸쉬알림</td>
								<td class="alignC"><a href="#" class="btn_basic">자세히</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>푸쉬알림</td>
								<td class="alignC"><a href="#" class="btn_basic">자세히</a></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div> -->
			<!--//공지 리스트 -->
			<!-- Paging -->
			<!-- <div class="paging">
				<a href="이전" class="prev"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="다음" class="next"></a>
				<div class="btnPosR"><a href="다음" class="btn_action"><span>취소</span></a></div>
			</div> -->
			<!-- //Paging -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
	<div class="alertPop" style="display:none; top:700px; left:50%; margin-left:-50px" id="creatAlert1">
		<p>할로윈 기념! 5시까지 호박로봇 증정!</p>
	</div>
</div>
</body>
</html>
