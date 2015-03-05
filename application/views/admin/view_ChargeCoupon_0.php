<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var selArray = new Array();
		var couponArray = new Array();
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;
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

			//상품 추가 삭제 버튼 기능 구현
			var curCount = (( $(".addUiBtn").parent().attr("rowspan") ) ? parseInt($(".addUiBtn").parent().attr("rowspan")) : 1);
			$(document).on("click", ".addUiBtn", function () {
				curCount++;
				var lastThCount = $(".addUiBtn").parent().parent().parent().children(":last-child").children("th").length - 1;
				$(".addUiBtn").parent().attr("rowspan", curCount);
 				$(".addUiBtn").parent().parent().parent().append("<tr><th>" + $(".addUiBtn").parent().parent().parent().children(":last-child").children("th").eq(lastThCount).html().replace(new RegExp(curCount - 1,'gi'), curCount) + "</th><td colspan=\"2\">" + $(".addUiBtn").parent().siblings("td").html() + "</td></tr>");
 				$("select[name=\"reward_type\"]").eq(curCount - 1).html("<option value=\"\"> = 선택 = </option>");
			});

			$(document).on("click", ".delUiBtn", function () {
				if ( curCount > 1 )
				{
					curCount--;
					$(".delUiBtn").parent().attr("rowspan", curCount);
					$(".delUiBtn").parent().parent().parent().children(":last-child").remove();
				}
				else
				{
					alert("더 이상 삭제 할 수 없습니다.");
				}
			});

			$("#coupon_type").change(function () {
				if ( $(this).val() == "S" )
				{
					$("#coupon_count").val("0");
					$("#staticfield").show();
					$("#randomcount").hide();
				}
				else if ( $(this).val() == "R" )
				{
					$("#static_code").val("");
					$("#staticfield").hide();
					$("#randomcount").show();
				}
				else
				{
					$("#static_code").val("");
					$("#staticfield").hide();
					$("#staticfield").hide();
				}
			});

			$("#btnInsert").click(function () {
				if ( $("#group_name").val() == "" || $("#group_name").val() == null )
				{
					alert("쿠폰명");
					$("#group_name").focus();
					return;
				}
				if ( $("#start_date").val() == "" || $("#start_date").val() == null )
				{
					alert("시작일");
					$("#start_date").focus();
					return;
				}
				if ( $("#end_date").val() == "" || $("#end_date").val() == null )
				{
					alert("종료일");
					$("#end_date").focus();
					return;
				}
				if ( $("#coupon_count").val() == "" || $("#coupon_count").val() == null )
				{
					alert("쿠폰개수");
					$("#coupon_count").focus();
					return;
				}
				if ( $("#coupon_type").val() == "" || $("#coupon_type").val() == null )
				{
					alert("쿠폰타입");
					$("#coupon_type").focus();
					return;
				}
				else
				{
					if ( $("#coupon_type").val() == "S" && ( $("#static_code").val() == "" || $("#static_code").val() == null ) )
					{
						alert("고정코드");
						$("#static_code").focus()
						return;
					}
				}
				var result = true;
				var thisIndex = 0;
				var arrRewardType = new Array();
				var arrRewardValue = new Array();
				$("select[name=\"reward_type\"]").each(function () {
					if ( result && ( $(this).val() == "" || $(this).val() == null ) )
					{
						result = false;
						thisIndex = $(this).parent().parent().index() - 2;
						return;
					}
					else
					{
						arrRewardType.push( $(this).val() );
					}
				});

				if (!result)
				{
					alert("대상상품");
					$("select[name=\"reward_type\"]").eq(thisIndex).focus();
					return;
				}

				result = true;
				thisIndex = 0;
				$("input[name=\"reward_value\"]").each(function () {
					if ( result && ( $(this).val() == "" || $(this).val() == null ) )
					{
						result = false;
						thisIndex = $(this).parent().parent().index() - 2;
						return;
					}
					else
					{
						arrRewardValue.push( $(this).val() );
					}
				});

				if (!result)
				{
					alert("아이템 수량");
					$("input[name=\"reward_value\"]").eq(thisIndex).focus();
					return;
				}

				var strReward = "";
				for ( var i = 0; i < arrRewardType.length; i++ )
				{
					strReward += "\{\"reward_type\":\"" + arrRewardType[i] + "\", \"reward_value\":\"" + arrRewardValue[i] + "\"\}, ";
				}

				if ( strReward != "" )
				{
					strReward = strReward.substr(0, strReward.length - 2);
				}

				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/requestCouponInsert",
					type:"POST",
					dataType:"json",
					async:false,
					data: {"data":"\{\"group_name\":\"" + $("#group_name").val() + "\", \"coupon_type\":\"" + $("#coupon_type").val() + "\", \"coupon_count\":\"" + $("#coupon_count").val() + "\",\"start_date\":\"" + $("#start_date").val() + "\",\"end_date\":\"" + $("#end_date").val() + "\",\"static_code\":\"" + $("#static_code").val() + "\", \"reward_array\":\[" + strReward + "\]\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							alert("쿠폰이 생성되었습니다.");
							window.open("<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/requestCouponListView/" + obj.arrResult.group_id, "_blank", "");
							$("#group_name").val("");
							$("#coupon_type").val("");
							$("#coupon_count").val("");
							$("#start_date").val("");
							$("#end_date").val("");
							$("#static_code").val("");
							addedCount = curCount;
							for ( var j = 1; j < addedCount; j++ )
							{
								$(".delUiBtn").click();
							}
							$("input[name=\"reward_value\"]").val("");
							getArticleList();
						}
						else
						{
							alert(obj.resultText);
						}
					},
					error:function( request, status, error )
					{
			        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			       	}
				});
			});
			getArticleList();

			$(document).on("change", "select[name=\"reward_category\"]", function () {
				var thisIndex = $(this).parent().parent().index() - 2;
				var filteredArr = new Array();
				filteredArr = $.grep(selArray, function( obj, i ) {
					return ( obj.article_type == $("select[name=\"reward_category\"]").eq(thisIndex).children("option:selected").val() );
				});
				var strTargetHtml = "<option value=\"\"> = 선택 = </option>";
				for ( var i = 0; i < filteredArr.length; i++ )
				{
					if ( filteredArr[i].grade == null )
					{
						strTargetHtml += "<option value=\"" + filteredArr[i].id + "\">" + filteredArr[i].text + "</option>";
					}
					else
					{
						strTargetHtml += "<option value=\"" + filteredArr[i].id + "\">★" + filteredArr[i].grade + " " + filteredArr[i].text + "</option>";
					}
				}
				$("select[name=\"reward_type\"]").eq(thisIndex).html(strTargetHtml);
			});

			getCouponList();
			couponPage(1);
		});

		function getCouponList()
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/requestCouponList",
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						if ( obj.arrResult.length > 0 )
						{
							for ( var i = 0; i < obj.arrResult.length; i++ )
							{
								couponArray.push( new couponarray( obj.arrResult[i].group_id, obj.arrResult[i].reg_datetime, obj.arrResult[i].coupon_count, obj.arrResult[i].static_code, obj.arrResult[i].use_count, obj.arrResult[i].start_date, obj.arrResult[i].end_date, obj.arrResult[i].group_name, obj.arrResult[i].coupon_type, obj.arrResult[i].reward_type.replace(/ /gi, "").split(","), obj.arrResult[i].reward_value.replace(/ /gi, "").split(","), obj.arrResult[i].is_valid ) );
							}
						}
						totPage = ( Math.ceil( couponArray.length / pageSize ) < 1 ? 1 : Math.ceil( couponArray.length / pageSize ) );
					}
					else
					{
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
		}

		function getArticleList()
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/eventpresent/requestArticleList",
				type:"POST",
				dataType:"json",
				async:false,
				data: null,
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						var objCategory = new Array();
						var objArticleType = new Array();
						for ( var i = 0; i < obj.arrResult.length; i++ )
						{
							objCategory.push(obj.arrResult[i].category);
							objArticleType.push(obj.arrResult[i].article_type);
						}
						objCategory = $.unique(objCategory);
						objArticleType = $.unique(objArticleType);

						var strTargetHtml = "<option value=\"\"> = 선택 = </option>";
						for ( var i = 0; i < objCategory.length; i++ )
						{
							strTargetHtml += "<option value=\"" + objArticleType[i] + "\">" + objCategory[i] + "</option>";
						}
						$("select[name=\"reward_category\"]").html(strTargetHtml);

						strTargetHtml = "";
						for ( var i = 0; i < obj.arrResult.length; i++ )
						{
							selArray.push( new selarray(obj.arrResult[i].category, obj.arrResult[i].article_type, obj.arrResult[i].article_id, obj.arrResult[i].grade, obj.arrResult[i].<?php echo MY_Controller::COMMON_LANGUAGE_COLUMN ?>) );
						}
						$("select[name=\"reward_type\"]").html("<option value=\"\"> = 선택 = </option>");
					}
					else
					{
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
		}

		function couponarray( mGroup_id, mReg_date, mCoupon_count, mStatic_code, mUse_count, mStart_date, mEnd_date, mGroup_name, mCoupon_type, mReward_type, mReward_value, mIs_valid )
		{
			this.group_id = mGroup_id;
			this.reg_date = mReg_date;
			this.coupon_count = mCoupon_count;
			this.static_code = mStatic_code;
			this.use_count = mUse_count;
			this.start_date = mStart_date;
			this.end_date = mEnd_date;
			this.group_name = mGroup_name;
			this.coupon_type = mCoupon_type;
			this.reward_type = mReward_type;
			this.reward_value = mReward_value;
			this.is_valid = mIs_valid;
		}

		function selarray( mCategory, mArticle_type, mId, mGrade, mText )
		{
			this.category = mCategory;
			this.article_type = mArticle_type;
			this.id = mId;
			this.grade = mGrade;
			this.text = mText;
		}

		function couponPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:couponPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:couponPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:couponPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:couponPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);

			var strCouponInfo = ""
			if ( couponArray.length > 0 )
			{
				for ( var i = ( ( page - 1 ) * pageSize ); i < couponArray.length && i < ( page * pageSize ); i++ )
				{
					strCouponInfo += "<tr>";
					strCouponInfo += "<td class=\"alignC\">" + couponArray[i].reg_date + "</td>";
					if ( couponArray[i].coupon_type == "R" )
					{
						strCouponInfo += "<td class=\"alignC\">" + couponArray[i].coupon_count + " / " + couponArray[i].use_count + "</td>";
					}
					else
					{
						strCouponInfo += "<td class=\"alignC\">" + couponArray[i].static_code + " / " + couponArray[i].use_count + "</td>";
					}
					strCouponInfo += "<td class=\"alignC\">" + couponArray[i].start_date + " ~ " + couponArray[i].end_date + "</td>";
					strCouponInfo += "<td><a href=\"javascript:window.open('<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/requestCouponListView/" + couponArray[i].group_id + "', '_blank', '');\">" + couponArray[i].group_name + "</a></td><td>";
					for ( var j = 0; j < couponArray[i].reward_type.length; j++ )
					{
						strCouponInfo += couponArray[i].reward_type[j] + " : " + couponArray[i].reward_value[j];
						if ( j < couponArray[i].reward_type.length - 1 )
						{
							 strCouponInfo += ",<br />";
						}
					}
					strCouponInfo += "</td><td class=\"alignC\"><a href=\"javascript:couponStatusChange('" + couponArray[i].group_id + "', " + couponArray[i].is_valid + ");\" class=\"btn_basic\">";

					if ( couponArray[i].is_valid == true )
					{
						strCouponInfo += "중지";
					}
					else
					{
						strCouponInfo += "발행";
					}
					strCouponInfo += "</a></td>";
					strCouponInfo += "</tr>";
				}
				$("#couponTbody").html(strCouponInfo);
				$(".paging").children("ul").children("li").removeClass("on");
				$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
				curPage = page;
			}
			else
			{
				strCouponInfo = "<tr><td style=\"text-align:center;\" colspan=\"4\">등록된 쿠폰이 없습니다.</td></tr>"
				$("#couponTbody").html(strCouponInfo);
			}
		}

		function couponStatusChange( group_id, curStatus )
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/chargecoupon/requestCouponStatusChange",
				type:"POST",
				dataType:"json",
				async:false,
				data: {"data":"\{\"group_id\":\"" + group_id + "\", \"is_valid\":\"" + Math.abs(curStatus - 1) + "\"\}"},
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						alert("변경 되었습니다.");
						couponArray = new Array();
						getCouponList();
						couponPage(1);
					}
					else
					{
						alert("cc");
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
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
		<?php include_once  APPPATH."views/include/subnavi_coupon.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="15%" />
							<col width="10%" />
							<col width="" />
						</colgroup>
						<tbody>
							<tr>
								<th>쿠폰명</th>
								<td colspan="3">
									<input type="text" id="group_name" name="group_name" value="" /> &nbsp;&nbsp;
									<strong>유효기간</strong>&nbsp;
									<input type="text" class="inputTit datepicker" id="start_date" name="start_date" value="" style="width:100px;" />
									<input type="text" class="inputTit datepicker lastDate" id="end_date" name="end_date" value="" style="width:100px" />&nbsp;&nbsp;
									<!--<label><strong>영구</strong>&nbsp;
									<input type="checkbox" /></label>-->
								</td>
							</tr>
							<tr>
								<th>쿠폰타입</th>
								<td colspan="3">
									<select id="coupon_type" name="coupon_type">
										<option value=""> = 선택 = </option>
										<option value="R">랜덤코드</option>
										<option value="S">고정코드</option>
									</select>
									<span id="staticfield" style="display:none;"><input type="text" id="static_code" name="static_code" /></span>
									<span id="randomcount" style="display:none;"><input type="text" id="coupon_count" name="coupon_count" value="" />개</span>
								</td>
							</tr>
							<tr>
								<th>상품&nbsp;<a href="javascript:void(0);" class="btn_action smBtn addUiBtn">추가</a>&nbsp;<a href="javascript:void(0);" class="btn_basic smBtn delUiBtn">삭제</a></th>
								<th>상품1</th>
								<td colspan="2">
									<select name="reward_category" style="width:100px">
									</select>&nbsp;
									<select name="reward_type" style="width:100px">
									</select>&nbsp;
									<strong>아이템 수량</strong>&nbsp;
									<input type="text" name="reward_value" style="width:50px"/> 개
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="btnArea alignL" style="padding-top:0; padding-left:0; margin-top:5px;text-align:center;">
					<a href="javascript:void(0);" id="btnInsert" class="btn_action">생성</a>
				</div>
			</div>
			<!--//container-->
			<br />
			<!--container-->
			<div class="container">
				<h3>쿠폰 리스트</h3>
				<!-- board_list -->
				<div class="board_list">
					<div class="boardBar">
						<div class="listSearch"><a href="#" class="btn_action">엑셀파일로 출력</a></div>
					</div>
					<table>
						<colgroup>
							<col width="140" />
							<col width="200" />
							<col width="200" />
							<col />
							<col width="240" />
							<col width="80" />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2">생성일</th>
								<th class="bb2">발행/사용개수</th>
								<th class="bb2">유효기간</th>
								<th class="bb2">내용</th>
								<th class="bb2">상품</th>
								<th class="bb2">상태</th>
							</tr>
						</thead>
						<tbody id="couponTbody">
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
