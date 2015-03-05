<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var buyiapArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;
		$(document).ready(function () {
			var Lmenu = 1;
			var Smenu = 0;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$("#subnavi").change(function () {
				var strUrl = "<?php echo URLBASE; ?>index.php/pages/admin/chargemanage/view/" + $(this).val();
				if ( $("#searchParam").val() != '' && $("#searchParam").val() != null )
				{
					strUrl = strUrl + "/" + $("#searchParam").val();
					if ( $("#searchValue").val() != '' && $("#searchValue").val() != null )
					{
						strUrl = strUrl + "/" + $("#searchValue").val();
					}
				}
				window.location.href = strUrl;
			});

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
					buyiapArray = new Array();
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/chargemanage/requestBuyIAPList",
						type:"POST",
						dataType:"json",
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\",\"start_date\"\:\"" + $("#start_date").val() + "\",\"end_date\"\:\"" + $("#end_date").val() + "\",\"platform\":\"" + $("#platform").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								for ( var i = 0; i < obj.arrResult.length; i++ )
								{
									buyiapArray.push( new buyiaparray(obj.arrResult[i].id, obj.arrResult[i].name, obj.arrResult[i].uuid, obj.arrResult[i].pid, obj.arrResult[i].sid, obj.arrResult[i].product_id, obj.arrResult[i].buy_date, obj.arrResult[i].storetype, obj.arrResult[i].expire_date, obj.arrResult[i].payment_unit, obj.arrResult[i].payment_type, obj.arrResult[i].payment_value, obj.arrResult[i].is_provision, obj.arrResult[i].is_refund, obj.arrResult[i].paymentSeq, obj.arrResult[i].approvedPaymentNo, obj.arrResult[i].naverId, obj.arrResult[i].paymentTime, obj.arrResult[i].curcash) );
								}
								obj = obj.arrResult[0];
								$("#pid").val(obj.pid);
								$("#strId").html( obj.id + "(" + obj.pid + ")" );
								$("#strName").html( obj.name );
								$("#strUuid").html( obj.uuid );
								totPage = ( Math.ceil( buyiapArray.length / pageSize ) < 1 ? 1 : Math.ceil( buyiapArray.length / pageSize ) );
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

		function buyiaparray( mId, mName, mUuid, mPid, mSid, mProduct_id, mBuy_date, mStoretype, mExpire_date, mPayment_unit, mPayment_type, mPayment_value, mIs_provision, mIs_refund, mPaymentSeq, mApprovedPaymentNo, mNaverId, mPaymentTime, mCurcash )
		{
			this.id = mId;
			this.name = mName;
			this.uuid = mUuid;
			this.pid = mPid;
			this.sid = mSid;
			this.product_id = mProduct_id;
			this.buy_date = mBuy_date;
			this.storetype = mStoretype;
			this.expire_date = mExpire_date;
			this.payment_unit = mPayment_unit;
			this.payment_type = mPayment_type;
			this.payment_value = mPayment_value;
			this.is_provision = mIs_provision;
			this.is_refund = mIs_refund;
			this.paymentSeq = mPaymentSeq;
			this.approvedPaymentNo = mApprovedPaymentNo;
			this.naverId = mNaverId;
			this.paymentTime = mPaymentTime;
			this.curcash = mCurcash;
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
			var strbuyiapInfo = ""
			if ( buyiapArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < buyiapArray.length && i < ( page * pageSize ); i++ )
				{
					strbuyiapInfo += "<tr>";
					strbuyiapInfo += "<td class=\"alignC\">" + buyiapArray[i].buy_date + "</td>";
					strbuyiapInfo += "<td>" + buyiapArray[i].paymentTime + "</td>";
					strbuyiapInfo += "<td>" + buyiapArray[i].paymentSeq + "</td>";
					strbuyiapInfo += "<td>" + buyiapArray[i].naverId + "</td>";
					strbuyiapInfo += "<td>(" + buyiapArray[i].payment_unit + ") " + buyiapArray[i].payment_value + "</td>";
					strbuyiapInfo += "<td>" + buyiapArray[i].product_id + "</td>";
					if ( buyiapArray[i].is_provision == 1 )
					{
						strbuyiapInfo += "<td>지급완료</td>";
					}
					else
					{
						strbuyiapInfo += "<td>지급오류</td>";
					}
					if ( buyiapArray[i].is_refund == 1 )
					{
						strbuyiapInfo += "<td>환불완료</td>";
					}
					else
					{
						strbuyiapInfo += "<td>환불가능</td>";
					}
					strbuyiapInfo += "<td>" + buyiapArray[i].sid + " => " + buyiapArray[i].curcash + "</td>";
					strbuyiapInfo += "</tr>";
				}
				$("#buyiapTbody").html(strbuyiapInfo);
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
			<h2>결제관리 - 충전 내역</h2>
			<?php include_once APPPATH."views/include/searchinfo_payment.php"; ?>
			<?php include_once APPPATH."views/include/accountinfo_payment.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col width="160" />
							<col width="160" />
							<col width="120" />
							<col width="120" />
							<col width="120" />
							<col width="180" />
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">결제일</th>
								<th class="bb2">플랫폼시간</th>
								<th class="bb2">플랫폼결제코드</th>
								<th class="bb2">플랫폼아이디</th>
								<th class="bb2">구매금액</th>
								<th class="bb2">구매상품</th>
								<th class="bb2">지급여부</th>
								<th class="bb2">취소여부</th>
								<th class="bb2">보유수정</th>
							</tr>
						</thead>
						<tbody id="buyiapTbody">
							<tr>
								<td class="alignC" colspan="9">검색을 해주세요.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="이전" class="prev"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="다음" class="next"></a>
			</div>
			<!-- //Paging -->
			<!--container-->
			<!--<div class="container">
				<div class="titArea">
					<h3>결재 관리 (ORDER ID , ID , 수정)</h3>
					<div class="btnArea">
						<a href="#" class="btn_action">누락 결재 지급</a>
						<a href="#" class="btn_basic">결제 취소</a>
					</div>
				</div>
				<div class="textareaBox" style="height:100px">
					123123123,abcabc,10
				</div>
			</div>-->
			<!--//container-->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
