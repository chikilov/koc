<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 0;
			var Smenu = 2;
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
			<h2>계정관리 - 제제관리</h2>
			<?php include APPPATH."views/include/searchinfo_account.php"; ?>
			<?php include APPPATH."views/include/accountinfo_account.php"; ?>
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
