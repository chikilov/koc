<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var charobj;
		var pageSize = 15;
		var pagingGroupSize = 10;
		var totPage;
		var expobj = new Array();
		var curPage = 1;
		var isSuccess;
		var isConfirmed;
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
						url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/characterInfo",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							var strCharInfo = "";
							var obj = eval(data);
							var charArray = new Array();
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								obj = obj.arrResult;
								$("#pid").val(obj.charinfo[0].pid);
								$("#strId").html( obj.charinfo[0].id + "(" + obj.charinfo[0].pid + ")" );
								$("#strName").html( obj.charinfo[0].name );
								$("#strUuid").html( obj.charinfo[0].uuid );
								for ( var i = 0; i < obj.charinfo.length; i++ )
								{
									charArray.push( new chararray( obj.charinfo[i].idx, obj.charinfo[i].charname, obj.charinfo[i].grade, obj.charinfo[i].level, obj.charinfo[i].up_grade, obj.charinfo[i].weapon_name, obj.charinfo[i].weapon, obj.charinfo[i].backpack_name, obj.charinfo[i].backpack, obj.charinfo[i].skill_0_name, obj.charinfo[i].skill_0, obj.charinfo[i].skill_1_name, obj.charinfo[i].skill_1, obj.charinfo[i].skill_2_name, obj.charinfo[i].skill_2, obj.charinfo[i].team, obj.charinfo[i].exp_group_idx, obj.charinfo[i].exp_idx, obj.charinfo[i].exp_time ) );
								}

								for ( var i = 0; i < obj.expinfo.length; i++ )
								{
									expobj.push( new exparray( obj.expinfo[i].level, obj.expinfo[i].exp ) );
								}

								charobj = charArray;
								totPage = ( Math.ceil( charobj.length / pageSize ) < 1 ? 1 : Math.ceil( charobj.length / pageSize ) );

								var strPaging = "<a href=\"javascript:charPage(1);\" class=\"prev\" title=\"이전\"></a>";
								strPaging += "<ul>";
								for ( var i = 0; i < totPage && i < pagingGroupSize; i++ )
								{
									if ( i == 0 )
									{
										strPaging += "<li><strong><a href=\"javascript:charPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></strong></li>";
									}
									else
									{
										strPaging += "<li><a href=\"javascript:charPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></li>";
									}
								}
								strPaging += "</ul>";
								if ( totPage > pagingGroupSize )
								{
									strPaging += "<a href=\"javascript:charPage(11);\" class=\"next\" title=\"다음\"></a>";
								}
								$(".paging").html(strPaging);

								if ( charobj.length > 0 )
								{
									for ( var i = 0; i < charobj.length && i < pageSize; i++ )
									{
										strCharInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
										strCharInfo += "<td class=\"alignC\">" + charobj[i].idx + "</td>";
										strCharInfo += "<td>" + charobj[i].charname + "</td>";
										strCharInfo += "<td>" + charobj[i].grade + "</td>";
										strCharInfo += "<td><span>" + charobj[i].level + "</span><input type=\"text\" name=\"charLev\" style=\"width:20px; display:none;\" value=\"" + charobj[i].level + "\" /></td>";
										strCharInfo += "<td><span>" + charobj[i].up_grade + "</span><input type=\"text\" name=\"charUg\" style=\"width:20px; display:none;\" value=\"" + charobj[i].up_grade + "\" /></td>";
										strCharInfo += "<td>" + charobj[i].weapon_name + " ( " + charobj[i].weapon + " )</td>";
										strCharInfo += "<td>" + charobj[i].backpack_name + " ( " + charobj[i].backpack + " )</td>";
										strCharInfo += "<td>" + charobj[i].skill_0_name + " ( " + charobj[i].skill_0 + " )</td>";
										strCharInfo += "<td>" + charobj[i].skill_1_name + " ( " + charobj[i].skill_1 + " )</td>";
										strCharInfo += "<td>" + charobj[i].skill_2_name + " ( " + charobj[i].skill_2 + " )</td>";
										if ( charobj[i].team != null && charobj[i].team != "" && charobj[i].team != "-" )
										{
											strCharInfo += "<td>" + charArray[i].team + "</td></tr>";
										}
										else
										{
											if ( charobj[i].exp_time != null && charobj[i].exp_time != "" && charobj[i].exp_time != "-" )
											{
												strCharInfo += "<td>" + charobj[i].exp_group_idx + "-" + charobj[i].exp_idx + " ( " + charobj[i].exp_time + " )</td></tr>";
											}
											else
											{
												strCharInfo += "<td>" + charobj[i].team + "</td></tr>";
											}
										}
									}
									$("#charTbody").html(strCharInfo);
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
					$("input[name=\"charLev\"]").eq($(this).index("input[name=\"charSel\"]")).parent().children("span").css("display", "none");
					$("input[name=\"charLev\"]").eq($(this).index("input[name=\"charSel\"]")).css("display", "block");
					$("input[name=\"charUg\"]").eq($(this).index("input[name=\"charSel\"]")).parent().children("span").css("display", "none");
					$("input[name=\"charUg\"]").eq($(this).index("input[name=\"charSel\"]")).css("display", "block");
				}
				else
				{
					$("input[name=\"charLev\"]").eq($(this).index("input[name=\"charSel\"]")).parent().children("span").css("display", "block");
					$("input[name=\"charLev\"]").eq($(this).index("input[name=\"charSel\"]")).css("display", "none");
					$("input[name=\"charUg\"]").eq($(this).index("input[name=\"charSel\"]")).parent().children("span").css("display", "block");
					$("input[name=\"charUg\"]").eq($(this).index("input[name=\"charSel\"]")).css("display", "none");
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
				charPage(curPage);
			});

			$("#btn_conf").click(function () {
				var ajaxUrl;
				var strConfirm;
				var strFinal;
				var strError;
				if ( $("#gubun").val() == "D" )
				{
					ajaxUrl = "<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/characterDelete";
					strConfirm = "삭제할 경우 착용된 아이템도 모두 삭제됩니다.\n그래도 실행하시겠습니까?";
					strFinal = "삭제 되었습니다.";
					strError = "삭제할 데이터를 먼저 선택하세요.";
				}
				else
				{
					ajaxUrl = "<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/characterLevelChange";
					strConfirm = "레벨이 변동 될 경우 경험치가 해당 레벨의 최소값으로 변경됩니다.\n그래도 실행하시겠습니까?";
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
					isConfirmed = false;
					isSuccess = true;
					theLoop:
					for ( var i = 0; i < arrayCheckedIndex.length; i++ )
					{
						if ( $("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).parent().children("span").text() != $("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() || $("#gubun").val() == "D" )
						{
							if ( isConfirmed )
							{
								$.ajax({
									url:ajaxUrl,
									type:"POST",
									dataType:"json",
									data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"cid\"\:\"" + $("#charTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"clev\":\"" + $("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() + "\",\"cexp\":\"" + expobj[$("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() - 1].exp + "\",\"cug\":\"" + $("input[name=\"charUg\"]").eq(arrayCheckedIndex[i]).val() + "\"\}"},
									success:function(data)
									{
										var obj = eval(data);
										if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
										{
											isSuccess = isSuccess && true;
											$("#btn_cust").css("display", "none");
											$("#btn_init").css("display", "block");
											$("#btn_search").click();
											charPage(curPage);
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
										dataType:"json",
										data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"cid\"\:\"" + $("#charTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"clev\":\"" + $("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() + "\",\"cexp\":\"" + expobj[$("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() - 1].exp + "\",\"cug\":\"" + $("input[name=\"charUg\"]").eq(arrayCheckedIndex[i]).val() + "\"\}"},
										success:function(data)
										{
											var obj = eval(data);
											if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
											{
												isSuccess = isSuccess && true;
												$("#btn_cust").css("display", "none");
												$("#btn_init").css("display", "block");
												$("#btn_search").click();
												charPage(curPage);
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
								data: {"data":"\{\"pid\"\:\"" + $("#pid").val() + "\",\"cid\"\:\"" + $("#charTbody > tr").eq(arrayCheckedIndex[i]).children("td").eq(1).html() + "\",\"clev\":\"" + $("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() + "\",\"cexp\":\"" + expobj[$("input[name=\"charLev\"]").eq(arrayCheckedIndex[i]).val() - 1].exp + "\",\"cug\":\"" + $("input[name=\"charUg\"]").eq(arrayCheckedIndex[i]).val() + "\"\}"},
								success:function(data)
								{
									var obj = eval(data);
									if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
									{
										isSuccess = isSuccess && true;
										$("#btn_cust").css("display", "none");
										$("#btn_init").css("display", "block");
										$("#btn_search").click();
										charPage(curPage);
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

		function charPage(page)
		{
			$("#btn_cust").css("display", "none");
			$("#btn_init").css("display", "block");
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:charPage(" + prev + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li><strong><a href=\"javascript:charPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:charPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next <= totPage )
			{
				strPaging += "<a href=\"javascript:charPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);
			var strCharInfo = ""
			for ( var i = ( ( page - 1 ) * pageSize ); i < charobj.length && i < ( page * pageSize ); i++ )
			{
				strCharInfo += "<tr><td class=\"alignC\"><input type=\"checkbox\" name=\"charSel\" disabled=\"true\" /></td>";
				strCharInfo += "<td class=\"alignC\">" + charobj[i].idx + "</td>";
				strCharInfo += "<td>" + charobj[i].charname + "</td>";
				strCharInfo += "<td>" + charobj[i].grade + "</td>";
				strCharInfo += "<td><span>" + charobj[i].level + "</span><input type=\"text\" name=\"charLev\" style=\"width:20px; display:none;\" value=\"" + charobj[i].level + "\" /></td>";
				strCharInfo += "<td><span>" + charobj[i].up_grade + "</span><input type=\"text\" name=\"charUg\" style=\"width:20px; display:none;\" value=\"" + charobj[i].up_grade + "\" /></td>";
				strCharInfo += "<td>" + charobj[i].weapon_name + " ( " + charobj[i].weapon + " )</td>";
				strCharInfo += "<td>" + charobj[i].backpack_name + " ( " + charobj[i].backpack + " )</td>";
				strCharInfo += "<td>" + charobj[i].skill_0_name + " ( " + charobj[i].skill_0 + " )</td>";
				strCharInfo += "<td>" + charobj[i].skill_1_name + " ( " + charobj[i].skill_1 + " )</td>";
				strCharInfo += "<td>" + charobj[i].skill_2_name + " ( " + charobj[i].skill_2 + " )</td>";
				if ( charobj[i].team != null && charobj[i].team != "" && charobj[i].team != "-" )
				{
					strCharInfo += "<td>" + charobj[i].team + "</td></tr>";
				}
				else
				{
					if ( charobj[i].exp_time != null && charobj[i].exp_time != "" && charobj[i].exp_time != "-" )
					{
						strCharInfo += "<td>" + charobj[i].exp_group_idx + "-" + charobj[i].exp_idx + " ( " + charobj[i].exp_time + " )</td></tr>";
					}
					else
					{
						strCharInfo += "<td>" + charobj[i].team + "</td></tr>";
					}
				}
			}
			$("#charTbody").html(strCharInfo);
			$(".paging").children("ul").children("li").removeClass("on");
			$(".paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
			curPage = page;
		}

		function chararray( mIdx, mCharname, mGrade, mLevel, mUp_grade, mWeapon_name, mWeapon, mBackpack_name, mBackpack, mSkill_0_name, mSkill_0, mSkill_1_name, mSkill_1, mSkill_2_name, mSkill_2, mTeam, mExp_group_idx, mExp_idx, mExp_time )
		{
			this.idx = mIdx;
			this.charname = mCharname;
			this.grade = mGrade;
			this.level = mLevel;
			this.up_grade = mUp_grade;
			this.weapon_name = mWeapon_name;
			this.weapon = mWeapon;
			this.backpack_name = mBackpack_name;
			this.backpack = mBackpack;
			this.skill_0_name = mSkill_0_name;
			this.skill_0 = mSkill_0;
			this.skill_1_name = mSkill_1_name;
			this.skill_1 = mSkill_1;
			this.skill_2_name = mSkill_2_name;
			this.skill_2 = mSkill_2;
			this.team = mTeam;
			this.exp_group_idx = mExp_group_idx;
			this.exp_idx = mExp_idx;
			this.exp_time = mExp_time;
		}

		function exparray( mLevel, mExp )
		{
			this.level = mLevel;
			this.exp = mExp;
		}
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<input type="hidden" name="pid" id="pid" value="" />
	<input type="hidden" name="gubun" id="gubun" value="" />
	<?php include_once APPPATH."views/include/header.php"; ?>
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
							<col width="" />
							<col width="50" />
							<col width="50" />
							<col width="50" />
							<col width="140" />
							<col width="140" />
							<col width="140" />
							<col width="140" />
							<col width="140" />
							<col width="50" />
						</colgroup>
						<thead>
							<tr>
								<th class="alignC bb2"></th>
								<th class="alignC bb2">ID</th>
								<th class="alignC bb2">기체명</th>
								<th class="alignC bb2">등급</th>
								<th class="alignC bb2">레벨</th>
								<th class="alignC bb2">강화</th>
								<th class="alignC bb2">무기</th>
								<th class="alignC bb2">백팩</th>
								<th class="alignC bb2">스킬1</th>
								<th class="alignC bb2">스킬2</th>
								<th class="alignC bb2">스킬3</th>
								<th class="alignC bb2">팀</th>
							</tr>
						</thead>
						<tbody id="charTbody">
							<tr>
								<td colspan="12" class="alignC">데이터가 없습니다.</td>
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
					<li class="on"><strong>1</strong></li>
				</ul>
				<a href="javascript:void(0);" class="next"></a>
			</div>
			<!-- //Paging -->
			<!-- btnArea -->
			<div id="btn_init" class="btnArea alignR">
				<a href="#" id="btn_edit" class="btn_action">수정</a>
				<a href="#" id="btn_delt" class="btn_basic">삭제</a>
			</div>
			<div id="btn_cust" class="btnArea alignR">
				<a href="#" id="btn_conf" class="btn_action">확인</a>
				<a href="#" id="btn_cncl" class="btn_basic">취소</a>
			</div>
			<!--// btnArea -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
