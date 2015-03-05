<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 1;
			var Smenu = 0;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$("#subnavi").change(function () {
				var strUrl = "<?php echo URLBASE; ?>index.php/pages/admin/chargemanage/view/" + $(this).val();
				if ( $("#searchParam").val() != '' && $("#searchParam").val() != null )
				{
					strUrl = strUrl + "/" + $("#searchParam").val();
					if ( $("#searchValue").val() != '' && $("#searchValue").val() != null )
					{
						strUrl = strUrl + "/" + $("#searchValue").val();
					}
				}
				window.location.href = strUrl;
			});
		});
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
			<h2>결제관리 - 충전 내역</h2>
			<?php include_once APPPATH."views/include/searchinfo_payment.php"; ?>
			<?php include_once APPPATH."views/include/accountinfo_payment.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">결제일</th>
								<th class="bb2">애플결제 시스템시간</th>
								<th class="bb2">TRANSACTION ID</th>
								<th class="bb2">앱센터 ID</th>
								<th class="bb2">구매금액</th>
								<th class="bb2">구매수정</th>
								<th class="bb2">보유수정</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>123123123</td>
								<td>Abcabc</td>
								<td>3000</td>
								<td>10</td>
								<td>570</td>
							</tr>
							<tr>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>123123123</td>
								<td>Abcabc</td>
								<td>3000</td>
								<td>10</td>
								<td>570</td>
							</tr>
							<tr>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>123123123</td>
								<td>Abcabc</td>
								<td>3000</td>
								<td>10</td>
								<td>570</td>
							</tr>
							<tr>
								<td>2014-10-27 02:30:00</td>
								<td>2014-10-27 02:30:00</td>
								<td>123123123</td>
								<td>Abcabc</td>
								<td>3000</td>
								<td>10</td>
								<td>570</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
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
</div>
</body>
</html>
