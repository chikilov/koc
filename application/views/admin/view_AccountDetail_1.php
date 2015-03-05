<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var itemobj;
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var curPage = 1;
		$(document).ready(function () {
			var Lmenu = 0;
			var Smenu = 1;
			var subNav = <?php echo $subnavi; ?>;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			if ( $(".subNav") )
			{
				$(".subNav > li").eq(subNav).addClass("on");
			}
			$("#btn_cust").css("display", "none");

			$("#btn_search").click(function () {
				if ( $("#searchValue").val() == "" )
				{
					alert("검색 내용을 입력해주세요.");
					$("searchValue").focus();
				}
				else
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/inventoryInfo",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							var strInventoryInfo = "";
							var obj = eval(data);
							var inventoryArray = new Array();
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								obj = obj.arrResult;
								$("#pid").val(obj.inventoryInfo[0].pid);
								$("#strId").html( obj.inventoryInfo[0].id + "(" + obj.inventoryInfo[0].pid + ")" );
								$("#strName").html( obj.inventoryInfo[0].name );
								$("#strUuid").html( obj.inventoryInfo[0].uuid );
								for ( var i = 0; i < obj.inventoryInfo.length; i++ )
								{
									inventoryArray.push( new inventoryarray( obj.inventoryInfo[i].idx, obj.inventoryInfo[i].category, obj.inventoryInfo[i].itemname, obj.inventoryInfo[i].grade, obj.inventoryInfo[i].equip ) );
								}

								itemobj = inventoryArray;
								totPage = ( Math.ceil( itemobj.length / pageSize ) < 1 ? 1 : Math.ceil( itemobj.length / pageSize ) );

								var strPaging = "<a href=\"javascript:inventoryPage(1);\" class=\"prev\" title=\"이전\"></a>";
								strPaging += "<ul>";
								for ( var i = 0; i < totPage && i < pagingGroupSize; i++ )
								{
									if ( i == 0 )
									{
										strPaging += "<li><strong><a href=\"javascript:inventoryPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></strong></li>";
									}
									else
									{
										strPaging += "<li><a href=\"javascript:inventoryPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></li>";
									}
								}
								strPaging += "</ul>";
								if ( totPage > pagingGroupSize )
								{
									strPaging += "<a href=\"javascript:inventoryPage(11);\" class=\"next\" title=\"다음\"></a>";
								}
								$(".paging").html(strPaging);

								if ( itemobj.length > 0 )
								{
									for ( var i = 0; i < itemobj.length && i < pageSize; i++ )
									{
										strInventoryInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
										strInventoryInfo += "<td class=\"alignC\">" + itemobj[i].idx + "</td>";
										strInventoryInfo += "<td>" + itemobj[i].category + "</td>";
										strInventoryInfo += "<td>" + itemobj[i].itemname + "</td>";
										strInventoryInfo += "<td>" + itemobj[i].grade + "</td>";
										if ( itemobj[i].equip == null )
										{
											strInventoryInfo += "<td>-</td>";
										}
										else
										{
											strInventoryInfo += "<td>" + itemobj[i].equip + "</td>";
										}
									}
									$("#itemTbody").html(strInventoryInfo);
								}
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

			$("#btn_delt").click(function () {
				if ( $("#searchValue").val() == "" )
				{
					alert("검색 내용을 입력해주세요.");
					$("searchValue").focus();
				}
				else
				{
					$("input[name=\"charSel\"]").attr("disabled", false);
					$("#btn_cust").css("display", "block");
					$("#btn_init").css("display", "none");
				}
			});

			$("#btn_cncl").click(function () {
				$("#btn_cust").css("display", "none");
				$("#btn_init").css("display", "block");
				$("#btn_search").click();
				inventoryPage(curPage);
			});

			$("#btn_conf").click(function () {
				var arrayCheckedIndex = new Array();
				for ( var i = 0; i < $("input[name=\"charSel\"]").length; i++ )
				{
					if ( $("input[name=\"charSel\"]").eq(i).prop("checked") )
					{
						arrayCheckedIndex.push(i);
					}
				}

				if ( arrayCheckedIndex.length > 0 )
				{
					isConfirmed = false;
					isSuccess = true;
					theLoop:
					for ( var i = 0; i < arrayCheckedIndex.length; i++ )
					{
						if ( isConfirmed )
						{
							$.ajax({
								url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/inventoryDelete",
								type:"POST",
								dataType:"json",
								data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"iid\"\:\"" + $("#itemTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\"\}"},
								success:function(data)
								{
									var obj = eval(data);
									if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
									{
										isSuccess = isSuccess && true;
										$("#btn_cust").css("display", "none");
										$("#btn_init").css("display", "block");
										$("#btn_search").click();
										inventoryPage(curPage);
									}
									else
									{
										isSuccess = false;
										alert(obj.resultMsg);
									}
								},
								error:function( request, status, error )
								{
									isSuccess = false;
						        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
						       	}
							});
						}
						else
						{
							if ( confirm( "삭제할 경우 착용된 캐릭터 정보도 모두 삭제됩니다.\n그래도 실행하시겠습니까?" ) )
							{
								isConfirmed = true;
								$.ajax({
									url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/inventoryDelete",
									type:"POST",
									dataType:"json",
									data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"iid\"\:\"" + $("#itemTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\"\}"},
									success:function(data)
									{
										var obj = eval(data);
										if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
										{
											isSuccess = isSuccess && true;
											$("#btn_cust").css("display", "none");
											$("#btn_init").css("display", "block");
											$("#btn_search").click();
											inventoryPage(curPage);
										}
										else
										{
											isSuccess = false;
											alert(obj.resultMsg);
										}
									},
									error:function( request, status, error )
									{
										isSuccess = false;
							        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
							       	}
								});
							}
							else
							{
								isSuccess = false;
								$("#btn_search").click();
								charPage(curPage);
								alert("취소 되었습니다.");
								break theLoop;
							}
						}
					}
					if ( isSuccess )
					{
						alert("삭제 되었습니다.");
					}
				}
				else
				{
					alert("삭제할 데이터를 먼저 선택하세요.");
				}
			});
		});

		function inventoryPage(page)
		{
			$("#btn_cust").css("display", "none");
			$("#btn_init").css("display", "block");
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:inventoryPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:inventoryPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:inventoryPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:inventoryPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);
			var strInventoryInfo = ""
			for ( var i = ( ( page - 1 ) * pageSize ); i < itemobj.length && i < ( page * pageSize ); i++ )
			{
				strInventoryInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
				strInventoryInfo += "<td class=\"alignC\">" + itemobj[i].idx + "</td>";
				strInventoryInfo += "<td>" + itemobj[i].category + "</td>";
				strInventoryInfo += "<td>" + itemobj[i].itemname + "</td>";
				strInventoryInfo += "<td>" + itemobj[i].grade + "</td>";
				if ( itemobj[i].equip == null )
				{
					strInventoryInfo += "<td>-</td>";
				}
				else
				{
					strInventoryInfo += "<td>" + itemobj[i].equip + "</td>";
				}
			}
			$("#itemTbody").html(strInventoryInfo);
			$(".paging").children("ul").children("li").removeClass("on");
			$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
			curPage = page;
		}

		function inventoryarray( mIdx, mCategory, mItemname, mGrade, mEquip )
		{
			this.idx = mIdx;
			this.category = mCategory;
			this.itemname = mItemname;
			this.grade = mGrade;
			this.equip = mEquip;
		}
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<input type="hidden" name="pid" id="pid" value="" />
	<?php include_once  APPPATH."views/include/header.php"; ?>
	<!-- contents -->
	<div id="contents" class="contents">
		<!--section-->
			<?php include_once APPPATH."views/include/subnavi_account.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_list">
					<table>
						<colgroup>
							<col width="50" />
							<col width="" />
							<col width="" />
							<col width="" />
							<col width="" />
							<col width="" />
						</colgroup>
						<thead>
							<tr>
								<th class="alignC bb2"></th>
								<th class="alignC bb2">ID</th>
								<th class="alignC bb2">분류</th>
								<th class="alignC bb2">이름</th>
								<th class="alignC bb2">등급</th>
								<th class="alignC bb2">장착</th>
							</tr>
						</thead>
						<tbody id="itemTbody">
							<tr>
								<td colspan="6" class="alignC">데이터가 없습니다.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="javascript:void(0);" class="prev"></a>
				<ul>
					<li><strong>1</strong></li>
				</ul>
				<a href="javascript:void(0);" class="next"></a>
			</div>
			<!-- //Paging -->
			<!-- btnArea -->
			<div id="btn_init" class="btnArea alignR">
				<a href="javascript:void(0);" id="btn_delt" class="btn_basic">삭제</a>
			</div>
			<div id="btn_cust" class="btnArea alignR">
				<a href="javascript:void(0);" id="btn_conf" class="btn_action">확인</a>
				<a href="javascript:void(0);" id="btn_cncl" class="btn_basic">취소</a>
			</div>
			<!--// btnArea -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
