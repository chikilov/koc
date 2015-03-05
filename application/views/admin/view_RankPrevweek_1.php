<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 3;
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
		<?php include_once  APPPATH."views/include/subnavi_rankprev.php"; ?>
			<!-- 검색영역 -->
			<div class="searchArea">
				<table class="line1Tb">
					<colgroup>
						<col width="50" />
						<col width="" />
					</colgroup>
					<tbody>
						<tr>
							<th>
								기간
							</th>
							<td>
								<select name="" id="">
									<option value="">0000-00-00</option>
									 <option value="">0000-00-00</option>
								</select>
								<a href="#" class="btn_basic">조회</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--// 검색영역 -->
			<!--container-->
			<div class="container">
				<!-- board_list -->
				<div class="board_list">
					<div class="boardBar">
						<div class="listSearch"><a href="#" class="btn_action">엑셀파일로 다운</a></div>
					</div>
					<table>
						<colgroup>
							<col width="50" />
							<col />
							<col />
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">순위</th>
								<th class="bb2">캐릭터명</th>
								<th class="bb2">ID</th>
								<th class="bb2">점수</th>
								<th class="bb2">승패</th>
								<th class="bb2">최고연승</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignC">1</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">2</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">3</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">4</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">5</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">6</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">7</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">8</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">9</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
							</tr>
							<tr>
								<td class="alignC">10</td>
								<td>Aaaa</td>
								<td>Aaaa</td>
								<td>12000</td>
								<td>100/10</td>
								<td>12</td>
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
