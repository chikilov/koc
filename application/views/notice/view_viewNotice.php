<!DOCTYPE html>
<html lang="ko">
<head>
	<title>우주의기사 - 공지사항</title>
	<?php include_once APPPATH."views/include/metainfo_front.php"; ?>
</head>
<body>
<!-- wrap -->
<a name="top"></a>
<div class="popWrap">
	<div class="contents">
		<h2 style="padding: 10px 0 0 15px; height: 30px; font-size: 18px;"><?php echo $contentArray["notice_title"]; ?></h2>
		<ul class="imgBox">
			<li style="padding: 10px 0 10px 15px;"><?php echo $contentArray["content_text"]; ?></li>
		</ul>
	</div>
	<div class="footer">
		<a href="<?php echo URLBASE; ?>index.php/pages/notice/listnotice/view"><button style="margin-left: 7px;padding: 1px 4px;font-family: Arial,'돋움';font-size: 12px;border-radius: 3px;border: 1px solid #333;background: #fff;">목록</button></a>
		<span style="margin-left: 42%;">
<?php
	if ( $contentArray["prev_idx"] != null )
	{
		echo "<a href=\"".URLBASE."index.php/pages/notice/listnotice/noticeview/".$contentArray["prev_idx"]."\"><button style=\"padding: 1px 4px;font-family: Arial,'돋움';font-size: 12px;border-radius: 3px;border: 1px solid #333;background: #fff;\">&lt;</button></a>\n";
	}

	echo $contentArray["rec_position"]."\n";

	if ( $contentArray["next_idx"] != null )
	{
		echo "<a href=\"".URLBASE."index.php/pages/notice/listnotice/noticeview/".$contentArray["next_idx"]."\"><button style=\"padding: 1px 4px;font-family: Arial,'돋움';font-size: 12px;border-radius: 3px;border: 1px solid #333;background: #fff;\">&gt;</button></a>\n";
	}
?>
		</span>
		<a href="#top"><button class="closeBtn">맨위로</button></a>
	</div>
</div>
</body>
</html>
