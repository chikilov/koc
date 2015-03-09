<!DOCTYPE html>
<html lang="ko">
<head>
	<title>우주의기사 - 공지사항</title>
    <?php include_once APPPATH."views/include/metainfo_front.php"; ?>
</head>
<body>
<!-- wrap -->
<div class="popWrap">
	<!--<div class="header">
		<h1>공지사항</h1>
		<a href="#" class="winClose">X</a>
	</div>-->
	<div class="contents">
		<a href="<?php echo $arrayData["banner_link"]?>"><img src="<?php echo $arrayData["banner_url"]?>" style="width:100%;" /></a>
	</div>
	<!--<div class="footer">
		<label for="closeToday" class="closeToday"><input type="checkbox" id="closeToday" /> <span>오늘은 그만보기</span></label>
		<button class="closeBtn">닫기 X </button>
	</div>-->
</div>
</body>
</html>
