<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var notiArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;

		$(document).ready(function () {
			var Lmenu = 2;
			var Smenu = 0;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			getNoticeList();

			$("#btnOrder").click(function () {
				$("#noticeTbody > tr").each(function () {
					$(this).children("td").eq(0).html("<input type=\"text\" style=\"width:60px;\" name=\"order_no\" value=\"" + $(this).children("td").eq(0).html() + "\" />");
					$(this).children("td").eq(0).html($(this).children("td").eq(0).html() + "<input type=\"hidden\" name=\"idx\" value=\"" + $(this).children("td").eq(0).attr("id") + "\" />");
				});

				$(this).hide();
				$("#btnOrderConf").show();
			});

			$("#btnOrderConf").click(function () {
				var strParam = "[";
				for ( var i = 0; i < $("input[name=\"order_no\"]").length; i++ )
				{
					strParam += "{\"idx\":\"" + $("input[name=\"idx\"]").eq(i).val() + "\", \"order_no\":\"" + $("input[name=\"order_no\"]").eq(i).val() + "\"}";
					if ( i != ( $("input[name=\"order_no\"]").length - 1 ) )
					{
						strParam += ", ";
					}
				}
				strParam += "]";

				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestNoticeListOrderChange",
					type:"POST",
					dataType:"json",
					async:false,
					data: {"data":strParam},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							$("#btnOrder").show();
							$("#btnOrderConf").hide();
							getNoticeList();
							noticeListPage(1);
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

		function notiarray( mIdx, mOrder_no, mNotice_title, mStart_date, mEnd_date )
		{
			this.idx = mIdx;
			this.order_no = mOrder_no;
			this.notice_title = mNotice_title;
			this.start_date = mStart_date;
			this.end_date = mEnd_date;
		}

		function noticeListPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:noticeListPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:noticeListPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:noticeListPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:noticeListPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$("#paging").html(strPaging);
			var strNotiInfo = ""
			if ( notiArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < notiArray.length && i < ( page * pageSize ); i++ )
				{
					strNotiInfo += "<tr>";
					strNotiInfo += "<td class=\"alignC\" id=\"" + notiArray[i].idx + "\">" + notiArray[i].order_no + "</td>";
					strNotiInfo += "<td>" + notiArray[i].start_date + "</td>";
					strNotiInfo += "<td>" + notiArray[i].end_date + "</td>";
					strNotiInfo += "<td>" + notiArray[i].notice_title + "</td>";
					strNotiInfo += "<td class=\"alignC\"><a href=\"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/form/0/" + notiArray[i].idx + "\" class=\"btn_basic\">수정</a>&nbsp;&nbsp;";
					strNotiInfo += "<a href=\"javascript:delNoticeList('" + notiArray[i].idx + "');\" class=\"btn_basic\">삭제</a></td>";
					strNotiInfo += "</tr>";
				}
				$("#noticeTbody").html(strNotiInfo);
				$("#paging").children("ul").children("li").removeClass("on");
				$("#paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
			}
			else
			{
				strNotiInfo = "<tr><td style=\"text-align:center;\" colspan=\"5\">등록된 공지사항이 없습니다.</td></tr>"
				$("#noticeTbody").html(strNotiInfo);
			}
		}

		function delNoticeList( idx )
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/del/0/" + idx,
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
						getNoticeList();
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

		function getNoticeList()
		{
			notiArray = new Array();
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestNoticeList",
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
							notiArray.push( new notiarray(obj.arrResult[i].idx, obj.arrResult[i].order_no, obj.arrResult[i].notice_title, obj.arrResult[i].start_date, obj.arrResult[i].end_date) );
						}
						totPage = ( Math.ceil( notiArray.length / pageSize ) < 1 ? 1 : Math.ceil( notiArray.length / pageSize ) );
						noticeListPage(1);
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
			<h2>공지관리 - 리스트 공지</h2>
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>공지 리스트</h3>
				</div>
				<div class="board_list">
					<table>
						<colgroup>
							<col width="80px"/>
							<col width="140px" />
							<col width="140px" />
							<col />
							<col width="120px" />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">순서</th>
								<th class="bb2">시작일</th>
								<th class="bb2">종료일</th>
								<th class="bb2">제목</th>
								<th class="bb2">관리</th>
							</tr>
						</thead>
						<tbody id="noticeTbody">
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging" id="paging">
				<a href="이전" class="prev"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="다음" class="next"></a>
			</div>
			<div class="paging">
				<div class="btnPosR"><a href="<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/form/0" class="btn_action"><span>등록</span></a>&nbsp;<a href="javascript:void(0);" id="btnOrder" class="btn_action"><span>순서변경</span></a><a style="display:none;" href="javascript:void(0);" id="btnOrderConf" class="btn_action"><span>확인</span></a></div>
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
</div>
</body>
</html>
