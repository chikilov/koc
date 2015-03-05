<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
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

			$("#btn_search").click(function () {
				if ( $("#searchValue").val() == "" )
				{
					alert("검색 내용을 입력해주세요.");
					$("searchValue").focus();
				}
				else
				{
					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/playInfo",
						type:"POST",
						dataType:"json",
						async:false,
						data: {"data":"\{\"searchParam\"\:\"" + $("#searchParam").val() + "\",\"searchValue\"\:\"" + $("#searchValue").val() + "\"\}"},
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								obj = obj.arrResult;
								//pve 정보 셋팅
								if ( obj.last_pve.length > 0 )
								{
									var strLastPveHtml = "";
									for ( var i = 0; i < obj.last_pve.length; i++ )
									{
										strLastPveHtml += "<tr><td>" + obj.last_pve[i].stage + "</td><td>" + obj.last_pve[i].scene + "</td></tr>";
									}
								}
								else
								{
									strLastPveHtml = "<tr><td>-</td><td>-</td></tr>";
								}
								$("#last_pve").html(strLastPveHtml);

								//pvp 정보 셋팅
								if ( obj.last_pvp.length > 0 )
								{
									var strLastPvpHtml = "";
									for ( var i = 0; i < obj.last_pvp.length; i++ )
									{
										if ( obj.last_pvp[i].season == "curSeason" )
										{
											strLastPvpHtml += "<tr id=\"" + obj.last_pvp[i].season + "_pvp\"><td>" + obj.last_pvp[i].rank + "</td><td>-</td><td>" + obj.last_pvp[i].match_count + "</td><td>" + obj.last_pvp[i].win_count + "승 " + obj.last_pvp[i].lose_count + "패</td><td>" + obj.last_pvp[i].score + "</td></tr>";
										}
										else
										{
											strLastPvpHtml += "<tr id=\"" + obj.last_pvp[i].season + "_pvp\" style=\"display:none;\"><td>" + obj.last_pvp[i].rank + "</td><td>-</td><td>" + obj.last_pvp[i].match_count + "</td><td>" + obj.last_pvp[i].win_count + "승 " + obj.last_pvp[i].lose_count + "패</td><td>" + obj.last_pvp[i].score + "</td></tr>";
										}
									}
								}
								else
								{
									strLastPvpHtml = "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
								}
								$("#last_pvp").html(strLastPvpHtml);
								if ( !( $("#curSeason_pvp")[0] ) )
								{
									$("#last_pvp").html("<tr id=\"curSeason_pvp\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>" + $("#last_pvp").html());
								}
								if ( !( $("#lastSeason_pvp")[0] ) )
								{
									$("#last_pvp").html($("#last_pvp").html() + "<tr id=\"lastSeason_pvp\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
								}

								//pvb 정보 셋팅
								if ( obj.last_pvb.length > 0 )
								{
									var strLastPvbHtml = "";
									for ( var i = 0; i < obj.last_pvb.length; i++ )
									{
										if ( obj.last_pvb[i].season == "curSeason" )
										{
											strLastPvbHtml += "<tr id=\"" + obj.last_pvb[i].season + "_pvb\"><td>" + obj.last_pvb[i].rank + "</td><td>-</td><td>" + obj.last_pvb[i].match_count + "</td><td>" + obj.last_pvb[i].win_count + "</td><td>" + obj.last_pvb[i].score + "</td></tr>";
										}
										else
										{
											strLastPvbHtml += "<tr id=\"" + obj.last_pvb[i].season + "_pvb\" style=\"display:none;\"><td>" + obj.last_pvb[i].rank + "</td><td>-</td><td>" + obj.last_pvb[i].match_count + "</td><td>" + obj.last_pvb[i].win_count + "</td><td>" + obj.last_pvb[i].score + "</td></tr>";
										}
									}
								}
								else
								{
									strLastPvbHtml = "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
								}
								$("#last_pvb").html(strLastPvbHtml);
								if ( !( $("#curSeason_pvb")[0] ) )
								{
									$("#last_pvb").html("<tr id=\"curSeason_pvb\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>" + $("#last_pvb").html());
								}
								if ( !( $("#lastSeason_pvb")[0] ) )
								{
									$("#last_pvb").html($("#last_pvb").html() + "<tr id=\"lastSeason_pvb\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
								}

								//survival 정보 셋팅
								if ( obj.last_survival.length > 0 )
								{
									var strLastSurvivalHtml = "";
									for ( var i = 0; i < obj.last_survival.length; i++ )
									{
										if ( obj.last_survival[i].season == "curSeason" )
										{
											strLastSurvivalHtml += "<tr id=\"" + obj.last_survival[i].season + "_survival\"><td>" + obj.last_survival[i].rank + "</td><td>-</td><td></td><td></td><td>" + obj.last_survival[i].score + "</td></tr>";
										}
										else
										{
											strLastSurvivalHtml += "<tr id=\"" + obj.last_survival[i].season + "_survival\" style=\"display:none;\"><td>" + obj.last_survival[i].rank + "</td><td>-</td><td></td><td></td><td>" + obj.last_survival[i].score + "</td></tr>";
										}
									}
								}
								else
								{
									strLastSurvivalHtml = "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
								}
								$("#last_survival").html(strLastSurvivalHtml);
								if ( !( $("#curSeason_survival")[0] ) )
								{
									$("#last_survival").html("<tr id=\"curSeason_survival\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>" + $("#last_survival").html());
								}
								if ( !( $("#lastSeason_survival")[0] ) )
								{
									$("#last_survival").html($("#last_survival").html() + "<tr id=\"lastSeason_survival\"><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
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

			$("#pvpTogle").change(function () {
				$("#last_pvp > tr").toggle();
			});

			$("#pvbTogle").change(function () {
				$("#last_pvb > tr").toggle();
			});

			$("#survivalTogle").change(function () {
				$("#last_survival > tr").toggle();
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
			<?php include_once APPPATH."views/include/subnavi_account.php"; ?>
			<!--container-->
			<div class="container" style="width:60%">
				<h3>행성전</h3>
				<div class="board_view_st alignC">
					<table>
						<colgroup>
							<col width="" />
							<col width="" />
						</colgroup>
						<thead>
							<tr>
								<th>현재 스테이지</th>
								<th>총 진행 횟수</th>
							</tr>
						</thead>
						<tbody id="last_pve">
							<tr>
								<td>-</td>
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>1vs1 대전</h3>
					<select class="floatR" id="pvpTogle">
						<option value="">현재시즌</option>
						<option value="">지난시즌</option>
					</select>
				</div>
				<div class="board_view_st alignC">
					<table>
						<colgroup>
							<col width="20%" />
							<col width="20%" />
							<col width="20%" />
							<col width="20%" />
							<col width="20%" />
						</colgroup>
						<thead>
							<tr>
								<th>현재랭킹</th>
								<th>최고랭킹</th>
								<th>참가 횟수</th>
								<th>승패</th>
								<th>점수</th>
							</tr>
						</thead>
						<tbody id="last_pvp">
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>보스 대전</h3>
					<select class="floatR" id="pvbTogle">
						<option value="">현재시즌</option>
						<option value="">지난시즌</option>
					</select>
				</div>
				<div class="board_view_st alignC">
					<table>
						<colgroup>
							<col width="" />
							<col width="" />
							<col width="" />
							<col width="" />
							<col width="" />
						</colgroup>
						<thead>
							<tr>
								<th>현재랭킹</th>
								<th>최고랭킹</th>
								<th>참가 횟수</th>
								<th>보스 퇴치 횟수</th>
								<th>점수</th>
							</tr>
						</thead>
						<tbody id="last_pvb">
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>생존 모드</h3>
					<select class="floatR" id="survivalTogle">
						<option value="">현재시즌</option>
						<option value="">지난시즌</option>
					</select>
				</div>
				<div class="board_view_st alignC">
					<table>
						<colgroup>
							<col width="20%" />
							<col width="20%" />
							<col width="20%" />
							<col width="" />
							<col width="" />
						</colgroup>
						<thead>
							<tr>
								<th>현재랭킹</th>
								<th>최고랭킹</th>
								<th>참가 횟수</th>
								<th>최고 웨이브</th>
								<th>점수</th>
							</tr>
						</thead>
						<tbody id="last_survival">
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<div class="titArea">
					<h3>탐색</h3>
					<a href="#" class="btnArea btn_action" style="height: 17px; padding: 3px 3px 0 3px;" onclick="$('#detailPopup1').show()">자세히</a>
				</div>
				<div class="board_view_st alignC">
					<table>
						<colgroup>
							<col width="" />
							<col width="" />
							<col width="" />
							<col width="" />
						</colgroup>
						<thead>
							<tr>
								<th>탐색 중 기체</th>
								<th>총 탐색 횟수</th>
								<th>맵 클리어수</th>
								<th>적 퇴치 수</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- 탐색 팝업창 -->
				<div class="layerPop" id="detailPopup1" style="display:none; width:450px; bottom:30px; left:50%; margin-right:-150px;">
					<div class="container">
						<div class="board_view_st alignC">
							<table>
								<colgroup>
									<col width="100" />
									<col width="100" />
									<col width="200" />
								</colgroup>
								<thead>
									<tr>
										<th>탐색기체</th>
										<th>탐색지역</th>
										<th>남은시간</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
									<tr>
										<td>-</td>
										<td>-</td>
										<td>-</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<a href="#" class="close">X</a>
				</div>
				<!--// 탐색 팝업창 -->

			</div>
			<!--//container-->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->

</div>

</body>
</html>
