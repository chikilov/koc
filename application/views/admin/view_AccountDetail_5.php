<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 0;
			var Smenu = 1;
			var subNav = <?php echo $subnavi; ?>;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			if ( $(".subNav") )
			{
				$(".subNav > li").eq(subNav).addClass("on");
			}
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
			<?php include_once APPPATH."views/include/subnavi_account.php"; ?>
			<!--container-->
			<div class="container">
				<div class="fLBox" style="width:49%;">
					<h3>요약정보</h3>
					<div class="container">
						<div class="board_view_st alignC">
							<table>
								<colgroup>
									<col width="" />
									<col width="" />
								</colgroup>
								<tbody>
									<tr>
										<th>친구 수</th>
										<td>12</td>
									</tr>
									<tr>
										<th>초대수</th>
										<td>12</td>
									</tr>
									<tr>
										<th>우정 포인트</th>
										<td>12</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="fRBox" style="width:49%;">
					<h3>친구목록</h3>
					<div class="container">
						<div class="board_list">
							<table>
								<colgroup>
									<col width="" />
									<col width="" />
									<col width="" />
								</colgroup>
								<thead>
									<tr>
										<th class="alignC bb2">ID</th>
										<th class="alignC bb2">캐릭터명</th>
										<th class="alignC bb2">수락일</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
									<tr>
										<td>00123</td>
										<td>레아</td>
										<td>1023-12-12 12:12:12</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
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
			</div>
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
