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
			<!-- container -->
				<div class="container">
					<h3>생성할 ID 정보</h3>
					<!-- board_write -->
					<div class="board_list">
						<table>
							<colgroup>
								<col width="150" />
								<col width="" />
							</colgroup>
							<tbody>
								<tr>
									<th>아이디</th>
									<td colspan="3"><input type="text" style="width:18%;" /></td>
								</tr>
								<tr>
									<th>암호입력</th>
									<td colspan="3">
										<input type="password" style="width:18%;" />
									</td>
								</tr>
								<tr>
									<th>암호확인</th>
									<td colspan="3">
										<input type="password" style="width:18%;" />
									</td>
								</tr>
								<tr>
									<th>이름</th>
									<td colspan="3"><input type="text" style="width:18%;" /></td>
								</tr>
								<tr>
									<th>소속</th>
									<td colspan="3"><input type="text" style="width:18%;" /></td>
								</tr>
								<tr>
									<th>이메일</th>
									<td colspan="3"><input type="text" style="width:18%;" /></td>
								</tr>
								<!--<tr>
									<th>그룹설정</th>
									<td colspan="3">
										<select name="" id="" style="width:20%">
											<option value="Admin"></option>
											<option value="Manager"></option>
											<option value="GM"></option>
											<option value="Partner"></option>
										</select>
									</td>
								</tr>-->
							</tbody>
						</table>
					</div>
					<!--// board_write -->
					<div class="btnArea alignC">
						<a href="#" class="btn_action mr5">생성</a>
						<a href="<?php echo URLBASE; ?>index.php/pages/admin/adminmanage/view" class="btn_basic">취소</a>
					</div>
				</div>
				<!--// container -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
