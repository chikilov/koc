<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		var mailobj;
		var pageSize = 8;
		var pagingGroupSize = 10;
		var totPage;
		$(document).ready(function () {
			var Lmenu = 0;
			var Smenu = 0;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			//초기화를 위한 기본 태그 저장
			var mailListTbody = $("#mailListTbody").html();
			var mailListPaging = $(".paging").html();
			var collectionDiv = $("#collectionDiv").html();
			var achieveDiv = $("#achieveDiv").html();
			var operatorDiv = $("#operatorDiv").html();
			var teamDiv = $("#teamDiv").html();

			$("#btn_search").click(function () {
				if ( $("#searchValue").val() == "" )
				{
					alert("검색 내용을 입력해주세요.");
					$("searchValue").focus();
				}
				else
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/basicInfo",
						type:"POST",
						dataType:"json",
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								obj = obj.arrResult;
								$("#pid").val(obj.pid);
								$("#strId").html( obj.id + "(" + obj.pid + ")" );
								$("#strName").html( obj.name );
								$("#strUuid").html( obj.uuid );
								$("#strRegDate").html( obj.reg_date );
								$("#strLoginDatetime").html( obj.login_datetime );
								if ( obj.limit_type != null )
								{
									$("#strLimitInfo").html( getLimitType(obj.limit_type) + "(" + obj.limit_start + " ~ " + obj.limit_end + ")" );
								}
								else
								{
									$("#strLimitInfo").html( "정상" );
								}
								$("#strVipLevExp").html( obj.vip_level + "(" + obj.vip_exp + ")" );
								$("#strCurPve").html( "" );
								if ( obj.last_pve.length > 0 )
								{
									for ( var i = 0; i < obj.last_pve.length; i++)
									{
										if ( i != 0 )
										{
											$("#strCurPve").html( $("#strCurPve").html() + "<br />" );
										}

										$("#strCurPve").html( $("#strCurPve").html() + obj.last_pve[i].stage + ' - ' + obj.last_pve[i].scene );
									}
								}
								else
								{
									$("#strCurPve").html( "-" );
								}

								if ( obj.last_survival.length > 0 )
								{
									$("#strCurSur").html( obj.last_survival[0].rank + " 위 / " + obj.last_survival[0].score + " 점" );
								}
								else
								{
									$("#strCurSur").html( "- 위 / - 점" );
								}
								if ( obj.last_pvp.length > 0 )
								{
									$("#strCurPvp").html( obj.last_pvp[0].rank + " 위 / " + obj.last_pvp[0].score + " 점" );
								}
								else
								{
									$("#strCurPvp").html( "- 위 / - 점" );
								}
								if ( obj.last_pvb.length > 0 )
								{
									$("#strCurPvb").html( obj.last_pvb[0].rank + " 위 / " + obj.last_pvb[0].score + " 점" );
								}
								else
								{
									$("#strCurPvb").html( "- 위 / - 점" );
								}
								if ( obj.remain_item.length > 0 )
								{
									$("#strEnergy_points").html( obj.remain_item[0].energy_points );
									$("#strPvp_points").html( obj.remain_item[0].pvp_points );
									$("#strPvb_points").html( obj.remain_item[0].pvb_points );
									$("#strSurvival_points").html( obj.remain_item[0].survival_points );
									$("#strGame_points").html( obj.remain_item[0].game_points );
									$("#strCash_points").html( obj.remain_item[0].cash_points );
									$("#strEvent_points").html( obj.remain_item[0].event_points );
									$("#strFriendship_points").html( obj.remain_item[0].friendship_points );
								}
								$("#mailList").css("padding-bottom", obj.last_pve.length * 9);
								$("#mailListTbody").html(mailListTbody);
								$(".paging").html(mailListPaging);
								$("#collectionDiv").html(collectionDiv);
								$("#achieveDiv").html(achieveDiv);
								$("#operatorDiv").html(operatorDiv);
								$("#teamDiv").html(teamDiv);
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

			$("#btn_mailLoad").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/mailInfo",
					type:"POST",
					dataType:"json",
					data: {"data":"\{\"pid\"\:\"" + $("#pid").val()+"\"\}"},
					success:function(data)
					{
						mailobj = eval(data);
						var mailArray = Array();
						if ( mailobj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							obj = mailobj.arrResult;
							for ( var i = 0; i < obj.length; i++ )
							{
								mailArray.push( new mailarray( obj[i].idx, obj[i].sid, obj[i].title, obj[i].attach_type, obj[i].attach_value, obj[i].send_date, obj[i].expire_date, obj[i].article_type, obj[i].article_value, obj[i].is_receive ) );
							}
							mailobj = mailArray;

							totPage = ( Math.ceil( obj.length / pageSize ) < 1 ? 1 : Math.ceil( obj.length / pageSize ) );

							var strPaging = "<a href=\"javascript:mailPage(1);\" class=\"prev\" title=\"이전\"></a>";
							strPaging += "<ul>";
							for ( var i = 0; i < totPage && i < pagingGroupSize; i++ )
							{
								if ( i == 0 )
								{
									strPaging += "<li class=\"on\"><a href=\"javascript:mailPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></strong></li>";
								}
								else
								{
									strPaging += "<li><a href=\"javascript:mailPage(" + ( i + 1 ) + ");\">" + ( i + 1 ) + "</a></li>";
								}
							}
							strPaging += "</ul>";
							if ( totPage > pagingGroupSize )
							{
								strPaging += "<a href=\"javascript:mailPage(11);\" class=\"next\" title=\"다음\"></a>";
							}
							$(".paging").html(strPaging);

							if ( obj.length > 0 )
							{
								var strMail = ""
								for ( var i = 0; i < obj.length && i < pageSize; i++ )
								{
									strMail += "<tr><td>" + obj[i].send_date + "</td><td>" + obj[i].sid + "</td><td>" + obj[i].expire_date + "</td><td>" + obj[i].article_value + "(" + obj[i].attach_value + ")</td><td>" + obj[i].is_receive + "</td></tr>";
								}
								$("#mailListTbody").html(strMail);
							}
						}
						else
						{
							alert(mailobj.resultMsg);
						}
					},
					error:function( request, status, error )
					{
			        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			       	}
				});
			});

			$("#btn_collectionLoad").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/collectionInfo",
					type:"POST",
					dataType:"json",
					data: {"data":"\{\"pid\"\:\"" + $("#pid").val()+"\"\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							obj = obj.arrResult;
							for ( var i = 0; i < obj.length; i++ )
							{
								$("#td_" + obj[i].voc).html( obj[i].ucnt + " / " + obj[i].tcnt );
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
			});

			$("#btn_achieveLoad").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/achieveInfo",
					type:"POST",
					dataType:"json",
					data: {"data":"\{\"pid\"\:\"" + $("#pid").val()+"\"\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							obj = obj.arrResult;

							if ( obj.attendinfo.length > 0 )
							{
								$("#td_attendinfo").html( obj.attendinfo[0].attend_count + " ( " + obj.attendinfo[0].attend_date + " )" );
							}
							if ( obj.dailyinfo.length > 0 )
							{
								$("#td_dailyinfo").html( obj.dailyinfo[0].ucnt + " / " + obj.dailyinfo[0].tcnt );
							}
							if ( obj.achieveinfo.length > 0 )
							{
								$("#td_achieveinfo").html( obj.achieveinfo[0].ucnt + " / " + obj.achieveinfo[0].tcnt );
							}
							if ( obj.researchinfo.length > 0 )
							{
								$("#td_researchinfo").html( obj.researchinfo[0].ucnt + " / " + obj.researchinfo[0].tcnt );
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
			});

			$("#btn_operLoad").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/operInfo",
					type:"POST",
					dataType:"json",
					data: {"data":"\{\"pid\"\:\"" + $("#pid").val()+"\"\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							obj = obj.arrResult;

							var strOperInfoText = "";
							if ( obj.operinfo.length > 0 )
							{
								for ( var i = 0; i < obj.operinfo.length; i++ )
								{
									strOperInfoText += "<tr><td>" + obj.operinfo[i].kr + "</td><td>" + obj.operinfo[i].expire + "</td></tr>";
								}
							}
							else
							{
								strOperInfoText += "<tr><td colspan=\"2\" style=\"text-align: center;\">데이터가 없습니다.</td></tr>";
							}
							$("#operInfoTbody").html( strOperInfoText );
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

			$("#btn_teamLoad").click(function () {
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/accountbasic/teamInfo",
					type:"POST",
					dataType:"json",
					data: {"data":"\{\"pid\"\:\"" + $("#pid").val()+"\"\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							obj = obj.arrResult;

							for ( var i = 0; i < obj.teaminfo.length; i++ )
							{
								$("#teamname_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].ref_0 + " ( " + obj.teaminfo[i].memb_0 + " )");
								$("#teamname_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].ref_1 + " ( " + obj.teaminfo[i].memb_1 + " )");
								$("#teamname_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].ref_2 + " ( " + obj.teaminfo[i].memb_2 + " )");
								$("#teampilot_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].pi_0 + " ( " + obj.teaminfo[i].pilot_0 + " )");
								$("#teampilot_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].pi_1 + " ( " + obj.teaminfo[i].pilot_1 + " )");
								$("#teampilot_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].pi_2 + " ( " + obj.teaminfo[i].pilot_2 + " )");
								$("#teamgrade_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].grd_0);
								$("#teamgrade_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].grd_1);
								$("#teamgrade_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].grd_2);
								$("#teamlevel_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].level_0);
								$("#teamlevel_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].level_1);
								$("#teamlevel_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].level_2);
								$("#teamupgrade_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].upgrade_0);
								$("#teamupgrade_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].upgrade_1);
								$("#teamupgrade_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].upgrade_2);
								$("#teamweapon_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].weapon_0 + " ( " + obj.teaminfo[i].wep_0 + " )");
								$("#teamweapon_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].weapon_1 + " ( " + obj.teaminfo[i].wep_1 + " )");
								$("#teamweapon_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].weapon_2 + " ( " + obj.teaminfo[i].wep_2 + " )");
								$("#teambackpack_" + obj.teaminfo[i].team_seq + "0").html(obj.teaminfo[i].backpack_0 + " ( " + obj.teaminfo[i].bp_0 + " )");
								$("#teambackpack_" + obj.teaminfo[i].team_seq + "1").html(obj.teaminfo[i].backpack_1 + " ( " + obj.teaminfo[i].bp_1 + " )");
								$("#teambackpack_" + obj.teaminfo[i].team_seq + "2").html(obj.teaminfo[i].backpack_2 + " ( " + obj.teaminfo[i].bp_2 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "00").html(obj.teaminfo[i].skill_00 + " ( " + obj.teaminfo[i].skl_00 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "01").html(obj.teaminfo[i].skill_01 + " ( " + obj.teaminfo[i].skl_01 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "02").html(obj.teaminfo[i].skill_02 + " ( " + obj.teaminfo[i].skl_02 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "10").html(obj.teaminfo[i].skill_10 + " ( " + obj.teaminfo[i].skl_10 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "11").html(obj.teaminfo[i].skill_11 + " ( " + obj.teaminfo[i].skl_11 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "12").html(obj.teaminfo[i].skill_12 + " ( " + obj.teaminfo[i].skl_12 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "20").html(obj.teaminfo[i].skill_20 + " ( " + obj.teaminfo[i].skl_20 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "21").html(obj.teaminfo[i].skill_21 + " ( " + obj.teaminfo[i].skl_21 + " )");
								$("#teamskill_" + obj.teaminfo[i].team_seq + "22").html(obj.teaminfo[i].skill_22 + " ( " + obj.teaminfo[i].skl_22 + " )");

								onChangeText( $("#teampilot_" + obj.teaminfo[i].team_seq + "0") );
								onChangeText( $("#teampilot_" + obj.teaminfo[i].team_seq + "1") );
								onChangeText( $("#teampilot_" + obj.teaminfo[i].team_seq + "2") );
								onChangeText( $("#teamweapon_" + obj.teaminfo[i].team_seq + "0") );
								onChangeText( $("#teamweapon_" + obj.teaminfo[i].team_seq + "1") );
								onChangeText( $("#teamweapon_" + obj.teaminfo[i].team_seq + "2") );
								onChangeText( $("#teambackpack_" + obj.teaminfo[i].team_seq + "0") );
								onChangeText( $("#teambackpack_" + obj.teaminfo[i].team_seq + "1") );
								onChangeText( $("#teambackpack_" + obj.teaminfo[i].team_seq + "2") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "00") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "01") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "02") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "10") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "11") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "12") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "20") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "21") );
								onChangeText( $("#teamskill_" + obj.teaminfo[i].team_seq + "22") );
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
			});
		});

		function mailPage(page)
		{
			var prev = parseInt( ( page - 1 ) / pagingGroupSize ) * pagingGroupSize + 1;
			var next = (
				( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1 > totPage ) ?
					totPage + 1 : ( ( parseInt( ( page - 1 ) / pagingGroupSize ) + 1 ) * pagingGroupSize + 1
			);
			var strPaging = "<a href=\"javascript:mailPage(" + ( ( ( prev - 1 ) < 1 ) ? 1 : ( prev - 1 ) ) + ");\" class=\"prev\" title=\"이전\"></a>";
			strPaging += "<ul>";
			for ( var i = prev; i < next; i++ )
			{
				if ( i == page )
				{
					strPaging += "<li class=\"on\"><a href=\"javascript:mailPage(" + i + ");\">" + i + "</a></strong></li>";
				}
				else
				{
					strPaging += "<li><a href=\"javascript:mailPage(" + i + ");\">" + i + "</a></li>";
				}
			}
			strPaging += "</ul>";
			if ( next > totPage )
			{
				strPaging += "<a href=\"javascript:mailPage(" + totPage + ");\" class=\"next\" title=\"다음\"></a>";
			}
			else
			{
				strPaging += "<a href=\"javascript:mailPage(" + next + ");\" class=\"next\" title=\"다음\"></a>";
			}
			$(".paging").html(strPaging);

			var strMail = ""
			for ( var i = ( ( page - 1 ) * pageSize + 1 ); i < mailobj.length && i < ( ( page + 1 ) * pageSize + 1 ); i++ )
			{
				strMail += "<tr><td>" + mailobj[i - 1].send_date + "</td><td>" + mailobj[i - 1].sid + "</td><td>" + mailobj[i - 1].expire_date + "</td>";
				strMail += "<td>" + mailobj[i - 1].article_value + "(" + mailobj[i - 1].attach_value + ")</td><td>" + mailobj[i - 1].is_receive + "</td></tr>";
			}
			$("#mailListTbody").html(strMail);
			$("#mail_paging").children("ul").children("li").removeClass("on");
			$("#mail_paging").children("ul").children("li").eq( ( page % 10 ) - 1 ).addClass("on");
		}

		function onChangeText( obj )
		{
			if ( obj.html() != "-" && obj.html() != "- ( - )" )
			{
				obj.removeClass("alignC");
			}
			else
			{
				obj.removeClass("alignC");
				obj.addClass("alignC");
			}
		}

		function mailarray( mIdx, mSid, mTitle, mAttach_type, mAttach_value, mSend_date, mExpire_date, mArticle_type, mArticle_value, mIs_receive )
		{
			this.idx = mIdx;
			this.sid = mSid;
			this.title = mTitle;
			this.attach_type = mAttach_type;
			this.attach_value = mAttach_value;
			this.send_date = mSend_date;
			this.expire_date = mExpire_date;
			this.article_type = mArticle_type;
			this.article_value = mArticle_value;
			this.is_receive = mIs_receive;
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
		<div class="section">
			<h2>계정관리 - 기본정보</h2>
			<?php include_once APPPATH."views/include/searchinfo_account.php"; ?>
			<?php include_once APPPATH."views/include/accountinfo_account.php"; ?>
			<!--container-->
			<div class="container">
				<div class="fLBox" style="width:35%">
					<div class="container">
						<h3>계정 기본정보</h3>
						<div class="board_view_st alignL">
							<table>
								<colgroup>
									<col width="40%" />
									<col width="" />
								</colgroup>
								<tbody>
									<tr>
										<th>생성일</th>
										<td id="strRegDate">-</td>
									</tr>
									<tr>
										<th>최근접속</th>
										<td id="strLoginDatetime">-</td>
									</tr>
									<tr>
										<th>계정상태</th>
										<td id="strLimitInfo">-</td>
									</tr>
									<tr>
										<th>VIP</th>
										<td id="strVipLevExp">-</td>
									</tr>
									<tr>
										<th>현재 스테이지</th>
										<td id="strCurPve">-</td>
									</tr>
									<tr>
										<th>현재 생존모드</th>
										<td id="strCurSur">-</td>
									</tr>
									<tr>
										<th>PvP랭킹</th>
										<td id="strCurPvp">-</td>
									</tr>
									<tr>
										<th>PvB랭킹</th>
										<td id="strCurPvb">-</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="container" style="padding-top:13px;">
						<h3>남은 참여횟수</h3>
						<div class="board_view_st alignC">
							<table>
								<colgroup>
									<col width="" />
									<col width="" />
									<col width="" />
									<col width="" />
								</colgroup>
								<tbody>
									<tr>
										<th>에너지</th>
										<th>1vs1대전</th>
										<th>보스대전</th>
										<th>생존</th>
									</tr>
									<tr>
										<td id="strEnergy_points">-/-</td>
										<td id="strPvp_points">-/-</td>
										<td id="strPvb_points">-/-</td>
										<td id="strSurvival_points">-/-</td>
									</tr>
									<tr>
										<th>골드</th>
										<th>구매 수정</th>
										<th>이벤트 수정</th>
										<th>우정포인트</th>
									</tr>
									<tr>
										<td id="strGame_points">-<!--1000000--></td>
										<td id="strCash_points">-<!--1000000--></td>
										<td id="strEvent_points">-<!--50000--></td>
										<td id="strFriendship_points">-<!--1000--></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="fRBox" style="width:63%">
					<div class="container">
						<div class="pagingTitle">
							<h3>우편함</h3>
							<div class="pagingArea">
								<a href="javascript:void(0);" id="btn_mailLoad"><img src="<?php echo URLBASE; ?>static/images/reload.png" style="padding-left:12px; width:12px; height:13px;" /></a>
								<!--paging-->
								<div class="paging" id="mail_paging"></div>
								<!-- //Paging -->
							</div>
						</div>
						<!--boardTabArea-->
						<div class="boardTabArea" id="mailList">
							<!-- board_list -->
							<div class="board_list2 tabItem" style="display:block;">
								<table>
									<colgroup>
										<col />
										<col />
										<col />
										<col />
										<col />
									</colgroup>
									<thead>
										<tr>
											<th>날짜</th>
											<th>발신</th>
											<th>유효기간</th>
											<th>첨부아이템</th>
											<th>수령여부</th>
										</tr>
									</thead>
									<tbody id="mailListTbody">
										<tr>
											<td colspan="5" style="text-align: center;">데이터가 없습니다.</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- board_list -->
						</div>
						<!--//boardTabArea-->
					</div>
					<div class="container col3Section">
						<div class="fLBox">
							<h3>도감<a href="javascript:void(0);" id="btn_collectionLoad"><img src="<?php echo URLBASE; ?>static/images/reload.png" style="padding-left:12px; width:12px; height:13px;" /></a></h3>
							<div id="collectionDiv" class="board_view_st alignL">
								<table>
									<colgroup>
										<col width="40%" />
										<col width="" />
									</colgroup>
									<tbody>
										<tr>
											<th>인파이터</th>
											<td id="td_INFIGHTER">-/-</td>
										</tr>
										<tr>
											<th>디펜더</th>
											<td id="td_DEFENDER">-/-</td>
										</tr>
										<tr>
											<th>슈터</th>
											<td id="td_SHOOTER">-/-</td>
										</tr>
										<tr>
											<th>라이센스</th>
											<td id="td_LICENSE">-/-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="fCBox">
							<h3>업적<a href="javascript:void(0);" id="btn_achieveLoad"><img src="<?php echo URLBASE; ?>static/images/reload.png" style="padding-left:12px; width:12px; height:13px;" /></a></h3>
							<div id="achieveDiv" class="board_view_st alignL">
								<table>
									<colgroup>
										<col width="40%" />
										<col width="" />
									</colgroup>
									<tbody>
										<tr>
											<th>출석</th>
											<td id="td_attendinfo">-/-</td>
										</tr>
										<tr>
											<th>일일</th>
											<td id="td_dailyinfo">-/-</td>
										</tr>
										<tr>
											<th>상시</th>
											<td id="td_achieveinfo">-/-</td>
										</tr>
										<tr>
											<th>기체연구</th>
											<td id="td_researchinfo">-/-</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="fRBox">
							<h3>오퍼레이터<a href="javascript:void(0);" id="btn_operLoad"><img src="<?php echo URLBASE; ?>static/images/reload.png" style="padding-left:12px; width:12px; height:13px;" /></a></h3>
							<!--boardTabArea-->
							<div class="boardTabAreaScroll">
								<div id="operatorDiv" class="board_view_st alignL tabItem" style="display:block;">
									<table>
										<colgroup>
											<col width="40%" />
											<col width="" />
										</colgroup>
										<thead>
											<tr>
												<th style="text-align: center;">이름</th>
												<th style="text-align: center;">고용만료일</td>
											</tr>
										</thead>
										<tbody id="operInfoTbody">
											<tr>
												<td colspan="2" style="text-align: center;">데이터가 없습니다.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!--//boardTabArea-->
						</div>
					</div>
				</div>
			</div>
			<!--//container-->
			<!-- tabArea -->
			<div class="tabArea">
				<h3>팀 정보<a href="javascript:void(0);" id="btn_teamLoad"><img src="<?php echo URLBASE; ?>static/images/reload.png" style="padding-left:12px; width:12px; height:13px;" /></a></h3>
				<div class="tabMenu">
					<ul class="tabList">
						<li class="on"><a href="javascript:void(0);">A팀</a></li>
						<li><a href="javascript:void(0);">B팀</a></li>
						<li><a href="javascript:void(0);">C팀</a></li>
					</ul>
				</div>
				<ul class="tabConArea">
					<li class="tabCon" style="display:block;">
						<div id="teamDiv" class="board_list">
							<table>
								<colgroup>
									<col width="20%" />
									<col width="5%" />
									<col width="5%" />
									<col width="5%" />
									<col width="20%" />
									<col width="20%" />
									<col width="" />
								</colgroup>
								<thead>
									<tr>
										<th rowspan="2" class="alignC bb2">기체명</th>
										<th rowspan="2" class="alignC bb2">등급</th>
										<th rowspan="2" class="alignC bb2">레벨</th>
										<th rowspan="2" class="alignC bb2">강화</th>
										<th class="alignC bb1">무기</th>
										<th class="alignC bb1">백팩</th>
										<th class="alignC bb1">파일럿</th>
									</tr>
									<tr>
										<th class="alignC bb2">스킬1</th>
										<th class="alignC bb2">스킬2</th>
										<th class="alignC bb2">스킬3</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_00">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_00">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_00">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_00">-</td>
										<td class="alignC" id="teamweapon_00">-</td>
										<td class="alignC" id="teambackpack_00">-</td>
										<td class="alignC" id="teampilot_00">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_000">-</td>
										<td class="alignC" id="teamskill_001">-</td>
										<td class="alignC" id="teamskill_002">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_01">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_01">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_01">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_01">-</td>
										<td class="alignC" id="teamweapon_01">-</td>
										<td class="alignC" id="teambackpack_01">-</td>
										<td class="alignC" id="teampilot_01">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_010">-</td>
										<td class="alignC" id="teamskill_011">-</td>
										<td class="alignC" id="teamskill_012">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_02">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_02">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_02">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_02">-</td>
										<td class="alignC" id="teamweapon_02">-</td>
										<td class="alignC" id="teambackpack_02">-</td>
										<td class="alignC" id="teampilot_02">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_020">-</td>
										<td class="alignC" id="teamskill_021">-</td>
										<td class="alignC" id="teamskill_022">-</td>
									</tr>
								</tbody>
							</table>
						</div>
					</li>
					<li class="tabCon">
						<div class="board_list">
							<table>
								<colgroup>
									<col width="20%" />
									<col width="5%" />
									<col width="5%" />
									<col width="5%" />
									<col width="20%" />
									<col width="20%" />
									<col width="" />
								</colgroup>
								<thead>
									<tr>
										<th rowspan="2" class="alignC bb2">기체명</th>
										<th rowspan="2" class="alignC bb2">등급</th>
										<th rowspan="2" class="alignC bb2">레벨</th>
										<th rowspan="2" class="alignC bb2">강화</th>
										<th class="alignC bb1">무기</th>
										<th class="alignC bb1">백팩</th>
										<th class="alignC bb1">파일럿</th>
									</tr>
									<tr>
										<th class="alignC bb2">스킬1</th>
										<th class="alignC bb2">스킬2</th>
										<th class="alignC bb2">스킬3</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_10">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_10">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_10">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_10">-</td>
										<td class="alignC" id="teamweapon_10">-</td>
										<td class="alignC" id="teambackpack_10">-</td>
										<td class="alignC" id="teampilot_10">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_100">-</td>
										<td class="alignC" id="teamskill_101">-</td>
										<td class="alignC" id="teamskill_102">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_11">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_11">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_11">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_11">-</td>
										<td class="alignC" id="teamweapon_11">-</td>
										<td class="alignC" id="teambackpack_11">-</td>
										<td class="alignC" id="teampilot_11">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_110">-</td>
										<td class="alignC" id="teamskill_111">-</td>
										<td class="alignC" id="teamskill_112">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_12">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_12">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_12">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_12">-</td>
										<td class="alignC" id="teamweapon_12">-</td>
										<td class="alignC" id="teambackpack_12">-</td>
										<td class="alignC" id="teampilot_12">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_120">-</td>
										<td class="alignC" id="teamskill_121">-</td>
										<td class="alignC" id="teamskill_122">-</td>
									</tr>
								</tbody>
							</table>
						</div>
					</li>
					<li class="tabCon">
						<div class="board_list">
							<table>
								<colgroup>
									<col width="20%" />
									<col width="5%" />
									<col width="5%" />
									<col width="5%" />
									<col width="20%" />
									<col width="20%" />
									<col width="" />
								</colgroup>
								<thead>
									<tr>
										<th rowspan="2" class="alignC bb2">기체명</th>
										<th rowspan="2" class="alignC bb2">등급</th>
										<th rowspan="2" class="alignC bb2">레벨</th>
										<th rowspan="2" class="alignC bb2">강화</th>
										<th class="alignC bb1">무기</th>
										<th class="alignC bb1">백팩</th>
										<th class="alignC bb1">파일럿</th>
									</tr>
									<tr>
										<th class="alignC bb2">스킬1</th>
										<th class="alignC bb2">스킬2</th>
										<th class="alignC bb2">스킬3</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_20">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_20">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_20">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_20">-</td>
										<td class="alignC" id="teamweapon_20">-</td>
										<td class="alignC" id="teambackpack_20">-</td>
										<td class="alignC" id="teampilot_20">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_200">-</td>
										<td class="alignC" id="teamskill_201">-</td>
										<td class="alignC" id="teamskill_202">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_21">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_21">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_21">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_21">-</td>
										<td class="alignC" id="teamweapon_21">-</td>
										<td class="alignC" id="teambackpack_21">-</td>
										<td class="alignC" id="teampilot_21">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_210">-</td>
										<td class="alignC" id="teamskill_211">-</td>
										<td class="alignC" id="teamskill_212">-</td>
									</tr>
									<tr>
										<th rowspan="2" class="alignC" id="teamname_22">-</th>
										<td rowspan="2" class="alignC" id="teamgrade_22">-</td>
										<td rowspan="2" class="alignC" id="teamlevel_22">-</td>
										<td rowspan="2" class="alignC" id="teamupgrade_22">-</td>
										<td class="alignC" id="teamweapon_22">-</td>
										<td class="alignC" id="teambackpack_22">-</td>
										<td class="alignC" id="teampilot_22">-</td>
									</tr>
									<tr>
										<td class="alignC" id="teamskill_220">-</td>
										<td class="alignC" id="teamskill_221">-</td>
										<td class="alignC" id="teamskill_222">-</td>
									</tr>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</div>
			<!--//tabArea -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
