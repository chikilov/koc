<!DOCTYPE html>
<html>
<head>
	<title>우주의기사 - 쿠폰입력</title>
	<?php include_once APPPATH."views/include/metainfo_front.php"; ?>
	<style type="text/css">
		body { background:#000; color:#fff; font: 24px/24px Helvetica, Arial, sans-serif;}
		#container { width:100%; background:#000 url('<?php echo URLBASE; ?>static/images/CouponCode_640.png')no-repeat center top; padding-top:1px;}
		.article { max-width:480px; overflow:hidden; margin:42px auto 0; text-align:center;}
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
			<!-- 글씨가 디자인되어 있어서 이미지를 넣어 놨음..이미지 필요 없으면 주석처럼 시스템 택스트 사용 가능 -->
			<form id="frmCoupon" name="frmCoupon" method="post" action="<?php echo URLBASE; ?>index.php/pages/webview/couponview/result">
			<p class="requestTxt">쿠폰 번호 혹은 이벤트 코드를 입력해 주세요</p>
			<!--<p class="requestTxt"><img src="<?php echo URLBASE; ?>static/images/coupon_error_txt.png" alt="" /></p>-->
			<div class="inputBox"><input type="text" id="coupon_id" name="coupon_id" value="" /><input type="hidden" id="pid" name="pid" value="<?php echo $pid ?>" /></div>
			<div class="submitBox"><a href="javascript:void(0);"><img src="<?php echo URLBASE; ?>static/images/Confirm_640.png" id="btnConfirm" vspace="16" border="0"></a></div>
		</div>
	</div>
</body>
</html>
