<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 7;
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
			<h2>권한 관리 - 아이디 관리</h2>
			<!--container-->
			<div class="container">
				<!-- board_list -->
				<div class="board_list">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">ID</th>
								<th class="bb2">이름</th>
								<th class="bb2">소속</th>
								<th class="bb2">E-mail</th>
								<th class="bb2">권한</th>
								<th class="bb2">조작</th>
							</tr>
						</thead>
						<tbody>
							<tr class="alignC">
								<td>Admin1</td>
								<td>홍길동</td>
								<td>사업부</td>
								<td><a href="mailto:aaa@antic.com">aaa@antic.com</a></td>
								<td>Admin</td>
								<td>
									<a href="#" class="dbBtn">수정</a>
									<a href="#" class="dbBtn normalColor">삭제</a>
								</td>
							</tr>
							<tr class="alignC">
								<td>Admin1</td>
								<td>홍길동</td>
								<td>사업부</td>
								<td><a href="mailto:aaa@antic.com">aaa@antic.com</a></td>
								<td>Manager</td>
								<td>
									<a href="#" class="dbBtn">수정</a>
									<a href="#" class="dbBtn normalColor">삭제</a>
								</td>
							</tr>
							<tr class="alignC">
								<td>Admin1</td>
								<td>홍길동</td>
								<td>사업부</td>
								<td><a href="mailto:aaa@antic.com">aaa@antic.com</a></td>
								<td>GM</td>
								<td>
									<a href="#" class="dbBtn">수정</a>
									<a href="#" class="dbBtn normalColor">삭제</a>
								</td>
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
			<!-- btnArea -->
			<div class="btnArea alignR">
				<a href="<?php echo URLBASE; ?>index.php/pages/admin/adminmanage/write" class="btn_action">등록</a>
			</div>
			<!--// btnArea -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
