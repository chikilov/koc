<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 8;
			var Smenu = 0;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/gatchamanage/gatchaInfo",
				type:"POST",
				dataType:"json",
				data: {"data":"\"\"}"},
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						var wholeArray = obj.arrResult;
						var filteredArr = new Array();
						filteredArr = $.grep(wholeArray, function( obj, i ) {
							return ( obj.id == wholeArray[0].id );
						});
						console.log(filteredArr.length);

						var prevId = "";
						var strHtml = "";
						var k = 0;
						for ( var i = 0; i < wholeArray.length; i++ )
						{
							if ( prevId != wholeArray[i].id )
							{
								prevId = wholeArray[i].id;
								filteredArr = new Array();
								filteredArr = $.grep(wholeArray, function( obj, i ) {
									return ( obj.id == prevId );
								});

								strHtml += "<tr class=\"alignC\"><td rowspan=\"" + filteredArr.length + "\">" + wholeArray[i].id + "</td><td>" + wholeArray[i].kr + "</td><td>" + wholeArray[i].prob + "</td></tr>";
							}
							else
							{
								strHtml += "<tr class=\"alignC\"><td>" + wholeArray[i].kr + "</td><td>" + wholeArray[i].prob + "</td></tr>";
							}
						}
						$("#gatchaTbody").html(strHtml);
					}
					else
					{
						alert(obj.resultMsg);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
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
			<h2>가차 관리 - 가차 정보</h2>
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
								<th class="bb2">가차ID</th>
								<th class="bb2">이름</th>
								<th class="bb2">확률</th>
							</tr>
						</thead>
						<tbody id="gatchaTbody">
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
