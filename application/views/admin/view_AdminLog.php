<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 7;
			var Smenu = 1;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
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
			<h2>권한관리 - 로그조회</h2>
			<!-- 검색영역 -->
			<div class="searchArea">
				<table class="line1Tb">
					<colgroup>
						<col width="300" />
						<col width="50" />
						<col width="" />
						<col width="80" />
					</colgroup>
					<tbody>
						<tr>
							<td>
								<select name="" id="">
									<option value="">ID</option>
									<option value="">IP</option>
								</select>
								<input type="text" class="inputTit" title="" value="기사짱" >
							</td>
							<th><label for="kakaoId">기간</label></th>
							<td>
								<input type="text" class="inputTit datepicker" title="" style="width:100px;" />
								<input type="text" class="inputTit datepicker lastDate" title="" style="width:100px" />
							</td>
							<td class="searchBtn alignL"><a href="#" class="btn_basic">조회</a></td>
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
						<div class="listSearch"><a href="#" class="btn_action">엑셀파일로 출력</a></div>
					</div>
					<table>
						<colgroup>
							<col width="50" />
							<col />
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
								<th class="bb2">번호</th>
								<th class="bb2">시간</th>
								<th class="bb2">ID</th>
								<th class="bb2">메뉴</th>
								<th class="bb2">대상(닉네임)</th>
								<th class="bb2">행동</th>
								<th class="bb2">아이템</th>
								<th class="bb2">타입</th>
								<th class="bb2">IP</th>
							</tr>
						</thead>
						<tbody>
							<tr class="alignC">
								<td>1</td>
								<td>2015-00-00 00:00:00</td>
								<td>Neo</td>
								<td>회원정보(기본정보)</td>
								<td>으라차</td>
								<td>조회</td>
								<td></td>
								<td></td>
								<td>100.00.000.00</td>
							</tr>
							<tr class="alignC">
								<td>1</td>
								<td>2015-00-00 00:00:00</td>
								<td>Neo</td>
								<td>회원정보(기본정보)</td>
								<td>으라차</td>
								<td>추가</td>
								<td>가이런</td>
								<td>기제</td>
								<td>100.00.000.00</td>
							</tr>
							<tr class="alignC">
								<td>1</td>
								<td>2015-00-00 00:00:00</td>
								<td>Neo</td>
								<td>회원정보(기본정보)</td>
								<td>으라차</td>
								<td>공지</td>
								<td>10수정</td>
								<td>무료캐시</td>
								<td>100.00.000.00</td>
							</tr>
							<tr class="alignC">
								<td>1</td>
								<td>2015-00-00 00:00:00</td>
								<td>Neo</td>
								<td>회원정보(기본정보)</td>
								<td>으라차</td>
								<td>조회</td>
								<td></td>
								<td></td>
								<td>100.00.000.00</td>
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
