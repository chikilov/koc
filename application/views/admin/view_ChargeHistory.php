<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var assetArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;
		$(document).ready(function () {
			var Lmenu = 1;
			var Smenu = 1;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$("#btn_search").click(function () {
				if ( $("#searchValue").val() == "" || $("#searchValue").val() == null )
				{
					alert("검색 내용을 입력해주세요.");
					$("#searchValue").focus();
				}
				else if ( $("#start_date").val() == "" || $("#start_date").val() == null || $("#end_date").val() == "" || $("#end_date").val() == null )
				{
					alert("검색 내용을 입력해주세요.");
					$("start_date").focus();
				}
				else
				{
					assetArray = new Array();
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/chargehistory/requestAssetLogList",
						type:"POST",
						dataType:"json",
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\",\"start_date\"\:\"" + $("#start_date").val() + "\",\"end_date\"\:\"" + $("#end_date").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								for ( var i = 0; i < obj.arrResult.length; i++ )
								{
									assetArray.push( new assetarray(obj.arrResult[i].reg_datetime, obj.arrResult[i].usetype, obj.arrResult[i].<?php echo MY_Controller::COMMON_LANGUAGE_COLUMN; ?>, obj.arrResult[i].asset_value, obj.arrResult[i].description) );
								}
								obj = obj.arrResult[0];
								$("#pid").val(obj.pid);
								$("#strId").html( obj.id + "(" + obj.pid + ")" );
								$("#strName").html( obj.name );
								$("#strUuid").html( obj.uuid );
								totPage = ( Math.ceil( assetArray.length / pageSize ) < 1 ? 1 : Math.ceil( assetArray.length / pageSize ) );
								assetPage(1);
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
				}
			});
		});

		function assetarray( mReg_datetime, mUsetype, mAsset_type, mAsset_value, mDescription )
		{
			var strDetails = "";
			this.reg_datetime = mReg_datetime;
			this.usetype = mUsetype;
			this.asset_type = mAsset_type;
			this.asset_value = mAsset_value;
			this.description = mDescription;
		}

		function assetPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:assetPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:assetPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:assetPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:assetPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);
			var strAssetInfo = ""
			if ( assetArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < assetArray.length && i < ( page * pageSize ); i++ )
				{
					strAssetInfo += "<tr>";
					strAssetInfo += "<td class=\"alignC\">" + assetArray[i].reg_datetime + "</td>";
					strAssetInfo += "<td>" + assetArray[i].usetype + "</td>";
					strAssetInfo += "<td>" + assetArray[i].asset_type + "</td>";
					strAssetInfo += "<td>" + assetArray[i].asset_value + "</td>";
					strAssetInfo += "<td>" + assetArray[i].description;
					if ( assetArray[i].details )
					{
						strAssetInfo += " - " + assetArray[i].details + "</td>";
					}
					else
					{
						strAssetInfo += "</td>";
					}
					strAssetInfo += "</tr>";
				}
				$("#assetTbody").html(strAssetInfo);
				$(".paging").children("ul").children("li").removeClass("on");
				$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
			}
			else
			{
				strAssetInfo = "<tr><td style=\"text-align:center;\" colspan=\"5\">등록된 정보가 없습니다.</td></tr>"
				$("#assetTbody").html(strAssetInfo);
			}
		}
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
			<h2>결제관리 - 게임 거래내역</h2>
			<?php include_once APPPATH."views/include/searchinfo_payment.php"; ?>
			<?php include_once APPPATH."views/include/accountinfo_payment.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col width="15%" />
							<col width="10%" />
							<col width="15%" />
							<col width="10%" />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">날짜</th>
								<th class="bb2">소모/획득</th>
								<th class="bb2">재화</th>
								<th class="bb2">금액</th>
								<th class="bb2">상세</th>
							</tr>
						</thead>
						<tbody id="assetTbody">
							<tr>
								<td class="alignC" colspan="5">검색을 해주세요</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="#" class="prev" title="이전"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="#" class="next" title="다음"></a>
			</div>
			<!-- //Paging -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
