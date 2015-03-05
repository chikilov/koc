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
		var strInventoryInfo = "";
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
						url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/operatorInfo",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							strInventoryInfo = "";
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
									inventoryArray.push( new inventoryarray( obj.inventoryInfo[i].idx, obj.inventoryInfo[i].itemname, obj.inventoryInfo[i].is_equip, obj.inventoryInfo[i].reg_date, obj.inventoryInfo[i].expire ) );
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
									var tempj;
									for ( var i = 0; i < itemobj.length && i < pageSize; i++ )
									{
										strInventoryInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
										strInventoryInfo += "<td class=\"alignC\">" + itemobj[i].idx + "</td>";
										strInventoryInfo += "<td>" + itemobj[i].itemname + "</td>";
										if ( itemobj[i].is_equip == "1" )
										{
											strInventoryInfo += "<td>Y</td>";
										}
										else
										{
											strInventoryInfo += "<td>N</td>";
										}
										if ( itemobj[i].reg_date == null )
										{
											strInventoryInfo += "<td>-<br />";
										}
										else
										{
											strInventoryInfo += "<td>" + itemobj[i].reg_date + "<br />";
										}
										if ( itemobj[i].ext_date == null )
										{
											strInventoryInfo += "( - )</td>";
										}
										else
										{
											strInventoryInfo += itemobj[i].ext_date + "</td>";
										}
										if ( itemobj[i].expire == null )
										{
											strInventoryInfo += "<td><span>-</span><span style=\"display:none;\"><input type=\"text\" name=\"expDate\" style=\"width:65px;\" class=\"inputTit datepicker\" value=\"<?php echo date("Y-m-d")?>\" />&nbsp;<select name=\"expHour\">";
											for ( var j = 0; j < 24; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(11, 13).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select>:<select name=\"expMin\">";
											for ( var j = 0; j < 60; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(14, 16).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select>:<select name=\"expSec\">";
											for ( var j = 0; j < 60; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(17, 19).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select></span></td>";
										}
										else
										{
											strInventoryInfo += "<td><span>" + itemobj[i].expire + "</span><span style=\"display:none;\"><input type=\"text\" name=\"expDate\" class=\"inputTit datepicker\" style=\"width:65px;\" value=\"" + itemobj[i].expire.substring(0, 10) + "\" />&nbsp;<select name=\"expHour\">";
											for ( var j = 0; j < 24; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(11, 13).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select>:<select name=\"expMin\">";
											for ( var j = 0; j < 60; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(14, 16).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select>:<select name=\"expSec\">";
											for ( var j = 0; j < 60; j++ )
											{
												tempj = "0" + j.toString();
												if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(17, 19).toString() )
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
												else
												{
													strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
												}
											}
											strInventoryInfo += "</select></span></td>";
										}
										strInventoryInfo += "<td>?</td>";
										strInventoryInfo += "<td>?</td>";
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

			$(document).on("click", "input[name=\"charSel\"]", function () {
				if ( $(this).prop("checked") )
				{
					$("input[name=\"expDate\"]").eq($(this).index("input[name=\"charSel\"]")).parent().parent().children("span").eq(0).css("display", "none");
					$("input[name=\"expDate\"]").eq($(this).index("input[name=\"charSel\"]")).parent().parent().children("span").eq(1).css("display", "block");
				}
				else
				{
					$("input[name=\"expDate\"]").eq($(this).index("input[name=\"charSel\"]")).parent().parent().children("span").eq(1).css("display", "none");
					$("input[name=\"expDate\"]").eq($(this).index("input[name=\"charSel\"]")).parent().parent().children("span").eq(0).css("display", "block");
				}
			});

			$("#btn_edit, #btn_delt").click(function () {
				if ( $("#searchValue").val() == "" )
				{
					alert("검색 내용을 입력해주세요.");
					$("searchValue").focus();
				}
				else
				{
					if ( $(this).is( $("#btn_delt") ) )
					{
						$("#gubun").val("D");
					}
					else
					{
						$("#gubun").val("E");
					}
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
				var ajaxUrl;
				var strConfirm;
				var strFinal;
				var strError
				if ( $("#gubun").val() == "D" )
				{
					ajaxUrl = "<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/operatorDelete";
					strConfirm = "삭제할 경우 메인설정 정보도 모두 삭제됩니다.\n그래도 실행하시겠습니까?";
					strFinal = "삭제 되었습니다.";
					strError = "삭제할 데이터를 먼저 선택하세요.";
				}
				else
				{
					ajaxUrl = "<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/operatorUpdate";
					isConfirmed = true;
					strFinal = "수정 되었습니다.";
					strError = "수정할 데이터를 먼저 선택하세요.";
				}
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
					if ( $("#gubun").val() == "D" )
					{
						isConfirmed = false;
					}
					isSuccess = true;
					theLoop:
					for ( var i = 0; i < arrayCheckedIndex.length; i++ )
					{
						if ( $("#gubun").val() == "D" )
						{
							if ( isConfirmed )
							{
								$.ajax({
									url:ajaxUrl,
									type:"POST",
									async:false,
									dataType:"json",
									data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"iid\"\:\"" + $("#itemTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"expire\":\"" + $("input[name=\"expDate\"]").eq(arrayCheckedIndex[i]).val() + " " + $("select[name=\"expHour\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expMin\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expSec\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + "\"\}"},
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
								if ( confirm( strConfirm ) )
								{
									isConfirmed = true;
									$.ajax({
										url:ajaxUrl,
										type:"POST",
										async:false,
										dataType:"json",
										data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"iid\"\:\"" + $("#itemTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"expire\":\"" + $("input[name=\"expDate\"]").eq(arrayCheckedIndex[i]).val() + " " + $("select[name=\"expHour\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expMin\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expSec\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + "\"\}"},
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
						else
						{
							$.ajax({
								url:ajaxUrl,
								type:"POST",
								dataType:"json",
								async:false,
								data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"iid\"\:\"" + $("#itemTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"expire\":\"" + $("input[name=\"expDate\"]").eq(arrayCheckedIndex[i]).val() + " " + $("select[name=\"expHour\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expMin\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + ":" + $("select[name=\"expSec\"]").eq(arrayCheckedIndex[i]).children("option:selected").val() + "\"\}"},
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
					}
					if ( isSuccess )
					{
						alert(strFinal);
					}
				}
				else
				{
					alert(strError);
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
			strInventoryInfo = ""
			for ( var i = ( ( page - 1 ) * pageSize ); i < itemobj.length && i < ( page * pageSize ); i++ )
			{
				strInventoryInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
				strInventoryInfo += "<td class=\"alignC\">" + itemobj[i].idx + "</td>";
				strInventoryInfo += "<td>" + itemobj[i].itemname + "</td>";
				if ( itemobj[i].is_equip == "1" )
				{
					strInventoryInfo += "<td>Y</td>";
				}
				else
				{
					strInventoryInfo += "<td>N</td>";
				}
				if ( itemobj[i].reg_date == null )
				{
					strInventoryInfo += "<td>-<br />";
				}
				else
				{
					strInventoryInfo += "<td>" + itemobj[i].reg_date + "<br />";
				}
				if ( itemobj[i].ext_date == null )
				{
					strInventoryInfo += "( - )</td>";
				}
				else
				{
					strInventoryInfo += itemobj[i].ext_date + "</td>";
				}
				if ( itemobj[i].expire == null )
				{
					strInventoryInfo += "<td><span>-</span><span style=\"display:none;\"><input type=\"text\" name=\"expDate\" style=\"width:65px;\" class=\"inputTit datepicker\" value=\"<?php echo date("Y-m-d")?>\" />&nbsp;<select name=\"expHour\">";
					for ( var j = 0; j < 24; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(11, 13).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select>:<select name=\"expMin\">";
					for ( var j = 0; j < 60; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(14, 16).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select>:<select name=\"expSec\">";
					for ( var j = 0; j < 60; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(17, 19).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select></span></td>";
				}
				else
				{
					strInventoryInfo += "<td><span>" + itemobj[i].expire + "</span><span style=\"display:none;\"><input type=\"text\" name=\"expDate\" style=\"width:65px;\" class=\"inputTit datepicker\" value=\"" + itemobj[i].expire.substring(0, 10) + "\" />&nbsp;<select name=\"expHour\">";
					for ( var j = 0; j < 24; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(11, 13).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select>:<select name=\"expMin\">";
					for ( var j = 0; j < 60; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(14, 16).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select>:<select name=\"expSec\">";
					for ( var j = 0; j < 60; j++ )
					{
						tempj = "0" + j.toString();
						if ( tempj.substring(tempj.length - 2) == itemobj[i].expire.substring(17, 19).toString() )
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\" selected=\"true\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
						else
						{
							strInventoryInfo += "<option value=\"" + tempj.substring(tempj.length - 2) + "\">" + tempj.substring(tempj.length - 2) + "</option>";
						}
					}
					strInventoryInfo += "</select></span></td>";
				}
				strInventoryInfo += "<td>?</td>";
				strInventoryInfo += "<td>?</td>";
			}
			$("#itemTbody").html(strInventoryInfo);
			$(".paging").children("ul").children("li").removeClass("on");
			$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
			curPage = page;
		}

		function inventoryarray( mIdx, mItemname, mIs_equip, mReg_date, mExpire )
		{
			this.idx = mIdx;
			this.itemname = mItemname;
			this.is_equip = mIs_equip;
			this.reg_date = mReg_date;
			this.expire = mExpire;
		}
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<input type="hidden" name="pid" id="pid" value="" />
	<input type="hidden" name="gubun" id="gubun" value="" />
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
							<col width="50" />
							<col width="100" />
							<col  width="50" />
							<col width="150" />
							<col width="" />
							<col width="100" />
							<col width="100" />
						</colgroup>
						<thead>
							<tr>
								<th class="alignC bb2"></th>
								<th class="alignC bb2">ID</th>
								<th class="alignC bb2">이름</th>
								<th class="alignC bb2">메인</th>
								<th class="alignC bb2">구매(갱신)일자</th>
								<th class="alignC bb2">만료일자</th>
								<th class="alignC bb2">옵션1</th>
								<th class="alignC bb2">옵션2</th>
							</tr>
						</thead>
						<tbody id="itemTbody">
							<tr>
								<td colspan="8" class="alignC">데이터가 없습니다.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging" style="display:none;">
				<a href="javascript:void(0);" class="prev"></a>
				<ul>
					<li><strong>1</strong></li>
				</ul>
				<a href="javascript:void(0);" class="next"></a>
			</div>
			<!-- //Paging -->
			<!-- btnArea -->
			<div id="btn_init" class="btnArea alignR">
				<a href="javascript:void(0);" id="btn_edit" class="btn_action">수정</a>
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
