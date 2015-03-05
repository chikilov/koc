<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 1;
			var Smenu = 2;
			var subNav = <?php echo $subnavi; ?>;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			if ( $(".subNav") )
			{
				$(".subNav > li").eq(subNav).addClass("on");
			}
			$("#subnavi").change(function () {
				var strUrl = "<?php echo URLBASE; ?>index.php/pages/admin/chargemanage/view/" + $(this).val();
				if ( $("#searchParam").val() != "" && $("#searchParam").val() != null )
				{
					strUrl = strUrl + "/" + $("#searchParam").val();
					if ( $("#searchValue").val() != "" && $("#searchValue").val() != null )
					{
						strUrl = strUrl + "/" + $("#searchValue").val();
					}
				}
				window.location.href = strUrl;
			});

			$("#btnSearch").click(function () {
				if ( ( $("#coupon_id").val() == null || $("#coupon_id").val() == "" ) && (
					$("#searchParam").val() == "" || $("#searchParam").val() == null || $("#searchValue").val() == "" || $("#searchValue").val() == null
					)
				)
				{
					alert("쿠폰아이디나 검색내용을 입력하세요");
					$("#coupon_id").focus();
				}
				else
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/searchCoupon",
						type:"POST",
						dataType:"json",
						data: {"data":"\{\"coupon_id\":\"" + $("#coupon_id").val() + "\", \"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								obj = obj.arrResult;
								var strHtml = "";
								for ( var i = 0; i < obj.length; i++ )
								{
									strHtml += "<tr><td>" + obj[i].reg_datetime + "</td>";
									strHtml += "<td>" + obj[i].start_date + " ~ " + obj[i].end_date + "</td>";
									if ( obj[i].is_use == 1 )
									{
										strHtml += "<td class=\"alignC\">사용</td>";
									}
									else
									{
										strHtml += "<td class=\"alignC\">미사용</td>";
									}
									if ( obj[i].coupon_use_datetime == null )
									{
										strHtml += "<td class=\"alignC\"> - </td>";
									}
									else
									{
										strHtml += "<td class=\"alignC\">" + obj[i].coupon_use_datetime + "</td>";
									}
									if ( obj[i].coupon_user_id == 0 )
									{
										strHtml += "<td class=\"alignC\"> - </td>";
									}
									else
									{
										strHtml += "<td class=\"alignC\">" + obj[i].coupon_user_id + "</td>";
									}
									strHtml += "<td>" + obj[i].group_name + "</td><td>";
									var reward_type = obj[i].reward_type.replace(/ /gi, "").split(",");
									var reward_value = obj[i].reward_value.replace(/ /gi, "").split(",");
									for ( var j = 0; j < reward_type.length; j++ )
									{
										strHtml += reward_type[j] + " : " + reward_value[j];
										if ( j < reward_type.length - 1 )
										{
											strHtml += ", <br />";
										}
									}
								}

								$("#couponTbody").html(strHtml);
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
	</script>
</head>
<body>
<!-- wrap -->
<?php include_once  APPPATH."views/include/header.php"; ?>
	<!-- contents -->
	<div id="contents" class="contents">
		<!--section-->
		<?php include_once  APPPATH."views/include/subnavi_coupon.php"; ?>
			<div class="searchArea">
				<table class="line1Tb">
					<colgroup>
						<col width="100" />
						<col width="200" />
						<col width="50" />
						<col width="" />
						<col width="80" />
					</colgroup>
					<tbody>
						<tr>
							<th><label for="">쿠폰번호</label></th>
							<td>
								<input type="text" class="inputTit" id="coupon_id" name="coupon_id" value="" >
							</td>
							<th><label for="">사용자</label></th>
							<td>
								<select name="searchParam" id="searchParam">
									<option value="pid"<?php if ( $searchParam == "pid" ) echo " selected=\"true\""; ?>>PID</option>
									<option value="id"<?php if ( $searchParam == "id" ) echo " selected=\"true\""; ?>>이메일</option>
									<option value="nm"<?php if ( $searchParam == "nm" ) echo " selected=\"true\""; ?>>닉네임</option>
								</select>
								<input type="text" class="inputTit" title="" name="searchValue" id="searchValue" value="<?php if ( $searchValue != "" && $searchValue != null ) echo $searchValue; ?>" />
							</td>
							<td class="searchBtn alignL"><a href="javascript:void(0);" id="btnSearch" class="btn_basic">조회</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--// 검색영역 -->
			<!--container-->
			<div class="container">
				<h3>결과</h3>
				<!-- board_list -->
				<div class="board_list">
					<table>
						<colgroup>
							<col />
							<col />
							<col width="100"/>
							<col />
							<col />
							<col />
							<col />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">생성일</th>
								<th class="bb2">유효기간</th>
								<th class="bb2">사용여부</th>
								<th class="bb2">사용일</th>
								<th class="bb2">사용자</th>
								<th class="bb2">내용</th>
								<th class="bb2">상품</th>
							</tr>
						</thead>
						<tbody id="couponTbody">
							<tr>
								<td colspan="7" class="alignC">검색이 필요합니다.</td>
							</tr>
							<!--<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>
							<tr>
								<td>1231-12-12 12:12:12</td>
								<td>1234-12-12</td>
								<td>사용</td>
								<td>1231-12-12 12:12:12</td>
								<td>기사짱</td>
								<td>초보자 쿠폰</td>
								<td>수정30, 2성뽑기권 1개</td>
							</tr>-->
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="#" class="prev" title="이전"></a>
				<ul>
					<li><strong>1</strong></li>
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
