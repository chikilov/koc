<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$(".loginBtn").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/login/chkAuth",
					type:"POST",
					dataType:"json",
					data:{ "admin_id" : $("#admin_id").val(),"admin_pw" : $("#admin_pw").val() },
					success:function(data)
					{
						var obj = eval(data);
						obj.url = "<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/view";
						if( obj.result == "OK" )
						{
							document.location.replace(obj.url);
						}
						else
						{
							alert( obj.message );
							$("#admin_pw").val("");
						}
					},
					error:function( request, status, error )
					{
			        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			       	}
				});
			});
		});
	</script>
</head>
<body>
<!-- contents -->
<div id="contents" class="contents">
	<div class="section">
		<h2>관리자 로그인</h2>
		<div class="con loginCon">
			<div class="loginArea">
				<div class="container">
					<div class="fLBox" >

					</div>
					<div class="fRBox">
						<table>
							<colgroup>
								<col width="80" />
								<col width="" />
							</colgroup>
							<tbody>
								<tr>
									<th>아이디</th>
									<td><input type="text" id="admin_id" name="admin_id" title="E-mail Address" style="width:93%" /></td>
								</tr>
								<tr>
									<th>비밀번호</th>
									<td><input type="password" id="admin_pw" name="admin_pw" title="Passwords" style="width:93%" /></td>
								</tr>
							</tbody>
						</table>
						<!--<p>
							<label><input type="checkbox" class="checkbox" /> 아이디 저장</label>
							<label><input type="checkbox" class="checkbox" /> 패스워드 저장</label>
						</p>-->
						<div class="btnArea">
							<a href="javascript:void(0);" class="loginBtn">Login</a> <!--<a href="#" class="joinBtn">회원가입</a>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
