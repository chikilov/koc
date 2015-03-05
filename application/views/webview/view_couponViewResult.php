<!DOCTYPE html>
<html>
<head>
	<title>우주의기사 - 쿠폰입력</title>
	<?php include_once APPPATH."views/include/metainfo_front.php"; ?>
	<style type="text/css">
		body { background:#000; color:#fff; font: 24px/24px Helvetica, Arial, sans-serif;}
		#container { width:100%; background:#000 url('<?php echo URLBASE; ?>static/images/CouponCode_error_640.png')no-repeat center top; padding-top:1px;}
		.article { max-width:480px; overflow:hidden; margin:60px auto 0; text-align:center;}
		.article .requestTxt { margin-bottom:55px; color:#cffdff;}
		.article .inputBox input { font-size:30px; font-family:verdana; margin-bottom:36px; height:30px; width:367px; border: 0;}
		.article .submitBox { margin-bottom:60px;}
		.article .completeTxt { padding-top:3px; margin-bottom:20px}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			if ( $("#pid").val() == "" || $("#pid").val() == null )
			{
				window.location = "<?php echo URLBASE; ?>index.php/pages/webview/couponview/result";
			}

			$("#btnConfirm").click(function () {
				if ( $("#coupon_id").val() == "" || $("#coupon_id").val() == null )
				{
					alert("쿠폰 번호를 입력해주세요.");
					$("#coupon_id").focus();
				}
				else
				{
					$("#frmCoupon").submit();
				}
			});
		});
	</script>
</head>
<body>
	<div id="container">
		<div class="article">
<?php
	if ( $result == "SUCCESS" )
	{
		echo "<p class=\"requestTxt\">쿠폰 입력이 완료되었습니다.<br />게임 내 우편함을 확인해 주세요</p>";
	}
	else if ( $result == "DUPLICATE" )
	{
		echo "<p class=\"requestTxt\">이미 사용한 쿠폰 번호 입니다.<br />쿠폰번호를 다시 확인해 주세요.</p>";
	}
	else if ( $result == "NONE" )
	{
		echo "<p class=\"requestTxt\">잘못된 쿠폰 번호 입니다.<br />쿠폰번호를 다시 확인해 주세요.</p>";
	}
?>
			<!--<p class="requestTxt">쿠폰 번호 혹은 이벤트 코드를 입력해 주세요</p>-->
			<input type="hidden" id="pid" name="pid" value="<?php echo $pid ?>" />
			<div class="submitBox"><a href="<?php echo URLBASE; ?>index.php/pages/webview/couponview/input/<?php echo $pid; ?>"><img src="<?php echo URLBASE; ?>static/images/Confirm_640.png" vspace="16" border="0"></a></div>
		</div>
	</div>
</body>
</html>
