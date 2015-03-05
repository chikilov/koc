<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 4;
			var Smenu = 0;
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
			<h2>로그조회 - 게임이용 로그</h2>
			<!-- 검색영역 -->
			<div class="searchArea">
				<table class="line2Tb">
					<colgroup>
						<col width="" />
						<col width="" />
						<col width="" />
						<col width="500" />
					</colgroup>
					<tbody>
						<tr>
							<td colspan="2">
								<select name="" id="">
									<option value="">계정명</option>
									<option value="">캐릭터명</option>
									<option value="">계정ID</option>
									<option value="">UID</option>
								</select>
								<input type="text" class="inputTit" title="" value="기사짱" >
							</td>
							<th><label for="kakaoId">기간</label></th>
							<td>
								<input type="text" class="inputTit datepicker" title="" style="width:100px;" />
								<input type="text" class="inputTit datepicker lastDate" title="" style="width:100px" />
							</td>
							<td class="alignR"><a href="#" class="btn_basic">조회</a></td>
						</tr>
						<tr class="optionSelect">
							<td class="alignL" colspan="5"><strong>[추가옵션]</strong></td>
						</tr>
						<tr>
							<th>행동선택</th>
							<td class="alignL">
								<select name="" id="">
									<option value="">전체</option>
									<option value="">로그인/아웃</option>
									<option value="">강화</option>
									<option value="">행성전</option>
								</select>
							</td>
							<th>아이템 ID</th>
							<td colspan="2">
								<input type="text" class="inputTit" title="" style="width:100px;" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--// 검색영역 -->
			<!--container-->
			<div class="container">
				<!-- board_list -->
				<div class="board_list vTStyle">
					<div class="boardBar">
						<div class="listSearch"><a href="#" class="btn_action">엑셀파일로 다운</a></div>
					</div>
					<table>
						<colgroup>
							<col  width="12%"/>
							<col width="8%"/>
							<col width="8%"/>
							<col width="8%"/>
							<col />
							<col />
							<col />
							<col width="12%"/>
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">날짜</th>
								<th class="bb2">ID</th>
								<th class="bb2">캐릭터명</th>
								<th class="bb2">행동</th>
								<th class="bb2">내용1</th>
								<th class="bb2">내용2</th>
								<th class="bb2">획득</th>
								<th class="bb2">소멸</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>로그아웃</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>강화성공</td>
								<td>
									3성 파르지팔 1>2로 강화성공
								</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>강화실패</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>판매</td>
								<td>
									3성 파르지팔 1>2로 강화성공
								</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>구매</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>뽑기</td>
								<td>3성 파르지팔 1>2로 강화성공</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>행성전시작</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>1vs1 시작</td>
								<td>친구 aaa와 A팀으로 어스 1-3 시작</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>로그아웃</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>강화성공</td>
								<td>
									3성 파르지팔 1>2로 강화성공
								</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>로그아웃</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>강화성공</td>
								<td>
									3성 파르지팔 1>2로 강화성공
								</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>로그아웃</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>강화성공</td>
								<td>
									3성 파르지팔 1>2로 강화성공
								</td>
								<td></td>
								<td></td>
								<td>
									<p>2성 기체1</p>
									<p>2성 기체2</p>
									<p>1000 골드</p>
								</td>
							</tr>
							<tr>
								<td class="alignC">2015-00-00 00:00:00</td>
								<td>sdkjdfjl3123</td>
								<td>기사짱</td>
								<td>로그아웃</td>
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
