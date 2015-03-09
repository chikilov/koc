<!DOCTYPE html>
<html lang="ko">
<head>
	<title>우주의기사 - 공지사항</title>
	<?php include_once APPPATH."views/include/metainfo_front.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$("h2").html($("#notice_title", opener.document).val());

			if ( $("#content_type", opener.document).val() == "text" )
			{
				$("ul.imgBox > li").html($("#content_text", opener.document).val());
				$("ul.imgBox > li").css("word-break", "break-all");
			}
			else
			{
				$("ul.imgBox > li").html("<img src=\"/koc/static/upload/notice/content/" + $("#content_image", opener.document).val() + "\" />");
				$("ul.imgBox > li > img").css("width", $("ul.imgBox > li").css("width"));
			}
		});
	</script>
</head>
<body>
<!-- wrap -->
<a name="top"></a>
<div class="popWrap">
	<!--<div class="header">
		<h1>공지사항</h1>
		<a href="#" class="winClose">X</a>
	</div>-->
	<div class="contents">
		<h2 class="notititle"></h2>
		<ul class="imgBox">
			<li></li>
		</ul>
	</div>
	<div class="footer">
		<a href="#top"><button class="closeBtn">맨위로</button></a>
	</div>
</div>
</body>
</html>
