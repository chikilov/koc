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
		<h2>공지사항</h2>
		<ul class="txtBox">
<?php
	//content_type, content_text, content_image, content_link
	if ( empty( $noticeArray ) )
	{
		echo "<li>등록된 공지가 없습니다.</li>\n";
	}
	else
	{
		foreach( $noticeArray as $row )
		{
			if ( $row["content_type"] == "link" )
			{
				echo "<li><a href=\"".$row["content_link"]."\">".$row["notice_title"]."</a></li>\n";
			}
			else
			{
				echo "<li><a href=\"".URLBASE."index.php/pages/notice/listnotice/noticeview/".$row["idx"]."\">".$row["notice_title"]."</a></li>\n";
			}
		}
	}
?>
		</ul>
	</div>
	<div class="contents">
		<h2>이벤트</h2>
		<ul class="imgBox">
<?php
	if ( empty( $eventArray ) )
	{
		echo "<li>등록된 이벤트가 없습니다.</li>\n";
	}
	else
	{
		foreach( $eventArray as $row )
		{
			if ( $row["content_type"] == "link" )
			{
				echo "<li><a href=\"".$row["content_link"]."\"><img src=\"".URLBASE."static/upload/event/thumbnail/".$row["thumbnail"]."\" style=\"width:100%\" /></a></li>\n";
			}
			else
			{
				echo "<li><a href=\"".URLBASE."index.php/pages/notice/listnotice/eventview/".$row["idx"]."\"><img src=\"".URLBASE."static/upload/event/thumbnail/".$row["thumbnail"]."\" style=\"width:100%\" /></a></li>\n";
			}
		}
	}
?>
		</ul>
		<ul class="imgBox">
			<li><a href="http://cafe.naver.com/tntgame2"><img src="<?php echo URLBASE ?>static/upload/notice/cafe_join.jpg" style="width:100%" /></a></li>
		</ul>
	</div>
	<div class="footer">
		<a href="#top"><button class="closeBtn">맨위로</button></a>
	</div>
</div>
</body>
</html>
