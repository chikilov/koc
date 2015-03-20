<?php
	if ( array_key_exists( "SERVER_ADDR", $_SERVER ) )
	{
		$serverAddrKeyName = "SERVER_ADDR";
	}
	else if ( array_key_exists( "LOCAL_ADDR", $_SERVER ) )
	{
		$serverAddrKeyName = "LOCAL_ADDR";
	}
	if ( $_SERVER[$serverAddrKeyName] == "175.119.227.180" )
	{
		$serverAddr = "175.119.227.180";
		$serverName = "api_1-1";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "211.110.6.124" )
	{
		$serverAddr = "211.110.6.124";
		$serverName = "api_1-2";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.7.75" )
	{
		$serverAddr = "1.234.7.75";
		$serverName = "api_1-3";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "211.110.154.227" )
	{
		$serverAddr = "211.110.154.227";
		$serverName = "api_1-4";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.45.250" )
	{
		$serverAddr = "1.234.45.250";
		$serverName = "api_2-1";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "175.126.103.73" )
	{
		$serverAddr = "175.126.103.73";
		$serverName = "api_2-2";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.89.167" )
	{
		$serverAddr = "1.234.89.167";
		$serverName = "api_2-3";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.6.60" )
	{
		$serverAddr = "1.234.6.60";
		$serverName = "api_2-4";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.89.245" )
	{
		$serverAddr = "1.234.89.245";
		$serverName = "api_3-1";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "211.110.154.217" )
	{
		$serverAddr = "211.110.154.217";
		$serverName = "api_3-2";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.69.161" )
	{
		$serverAddr = "1.234.69.161";
		$serverName = "api_3-3";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "1.234.69.83" )
	{
		$serverAddr = "1.234.69.83";
		$serverName = "api_3-4";
		$urlbase = "koc10100";
	}
	else if ( $_SERVER[$serverAddrKeyName] == "101.79.109.239" )
	{
		$serverAddr = "101.79.109.239";
		$serverName = "dev";
		$urlbase = "koc";
	}
	echo $serverAddr." ( ".$serverName." )<br />";
	$strurl = "/".$urlbase."/index.php/request/api/requestLogin/";
?>
<script type="text/javascript">
	var arrSampleData = [
		"{\"id\":\"chikilov\", \"password\":\"1212\", \"name\":\"최민\", \"email\":\"cm@neogramgames.com\"}",
		"{\"macaddr\":\"00:00:00:00:00:00\", \"uuid\":\"123qweasdzxc\", \"package\":\"com.neogram.editor\"}",
		"{\"pid\":\"13645\", \"idx\":\"4\"}",
		"{\"pid\":\"13645\", \"attach_type\":\"FRIENDSHIP_POINTS\"}",
		"{\"pid\":\"13645\", \"category\":\"1\"}",
		"{\"id\":\"chikilov\", \"password\":\"dudrud\", \"package\":\"com.neogram.editor\"}",
		"{\"device\":\"ANDROID\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\", \"appid\":\"13645\", \"store_type\":\"1\", \"store_version\":\"1\"}",
		"{\"pid\":\"13645\", \"cid\":\"137\", \"slotseq\":\"skill_1\", \"iid\":\"636\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\", \"product\":\"NGP_PK_0001\"}",
		"{\"pid\":\"13645\", \"product\":\"NGP_CR_0206\", \"sid\":\"13645\"}",
//		"{\"pid\":\"13645\", \"team_seq\":\"1\"}",
		"{\"pid\":\"13645\", \"teamInfo\":{\"00\":\"223\", \"01\":\"224\", \"02\":\"225\", \"10\":\"224\", \"11\":\"225\", \"12\":\"223\", \"20\":\"225\", \"21\":\"223\", \"22\":\"224\"}}",
		"{\"pid\":\"13645\", \"stageid\":\"100109\", \"friendid\":\"\", \"instant_item1\":\"NGP_BF_00001\", \"instant_item2\":\"NGP_BF_00002\", \"instant_item3\":\"NGP_BF_00003\", \"instant_item4\":\"NGP_BF_00004\"}",
		"{\"pid\":\"13645\", \"stageid\":\"100109\", \"logid\":\"1\", \"cid_0\":\"239\", \"cid_1\":\"240\", \"cid_2\":\"241\", \"exp_0\":\"13\", \"exp_1\":\"17\", \"exp_2\":\"23\", \"lev_0\":\"2\", \"lev_1\":\"3\", \"lev_2\":\"5\", \"basic_reward_type\":\"GAME_POINTS\", \"basic_reward_value\":\"10\", \"duration\":\"83\", \"is_clear\":\"1\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"rid\":\"RWPVE01009\", \"rpattern\":\"5\", \"rseq\":\"1\"}",
		"{\"pid\":\"13645\", \"stageid\":\"400101\", \"use_cash\":\"0\", \"level\":\"1\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"score\":\"12123\", \"is_clear\":\"1\"}",
		"{\"pid\":\"13645\", \"use_cash\":\"0\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"score\":\"12123\", \"is_clear\":\"1\"}",
		"{\"pid\":\"13645\", \"targetIdx\":\"240\", \"sourceIdx\":\"255\"}",
		"{\"pid\":\"13645\", \"targetIdx\":\"240\", \"sourceIdx\":\"255\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\", \"arrayAchieve\":[{\"aid\":\"AADCU01001\", \"astatus\":\"1\"}, {\"aid\":\"AADE01001\", \"astatus\":\"3\"}]}",
		"{\"pid\":\"13645\", \"aid\":[\"AADCU01001\", \"AADE01001\"]}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"1\", \"sell_type\":\"CHARACTER\", \"sell_idx\":\"351\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\",\"grade\":\"3\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\",\"grade\":\"3\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\", \"use_cash\":\"0\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\", \"tactinfo\":{\"00\":\"2\", \"01\":\"1\", \"02\":\"0\", \"10\":\"0\", \"11\":\"0\", \"12\":\"0\", \"20\":\"0\", \"21\":\"0\", \"22\":\"0\"}}",
		"{\"pid\":\"13645\",\"logid\":\"1\",\"is_clear\":\"-1\"}",
		"{\"pid\":\"13645\",\"page\":\"1\"}",
		"{\"pid\":\"13645\",\"page\":\"1\"}",
		"{\"pid\":\"13645\",\"page\":\"1\"}",
		"{\"pid\":\"13645\",\"searchval\":\"neogram\"}",
		"{\"pid\":\"13645\",\"status\":\"0\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\"}",
		"{\"pid\":\"1\",\"fid\":\"13645\",\"status\":\"1\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\"}",
		"{\"pid\":\"1\"}",
		"{\"pid\":\"1\", \"slotseq\":\"pilot_0\", \"iid\":\"2014\"}",
		"{\"pid\":\"1\", \"tactinfo\":{\"00\":\"2\", \"01\":\"1\", \"02\":\"0\", \"10\":\"0\", \"11\":\"0\", \"12\":\"0\", \"20\":\"0\", \"21\":\"0\", \"22\":\"0\"}}",
		"{\"pid\":\"1\", \"slotseq\":\"pilot_0\", \"iid\":\"2014\"}",
		"{\"pid\":\"13670\", \"slotseq\":\"00\", \"iid\":\"2219\"}",
		"{\"pid\":\"13645\", \"rewardtype\":\"pvb\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"120\",\"exp_idx\":\"6\"}",
		"{\"pid\":\"13645\",\"slottype\":\"cha\",\"incquantity\":\"5\"}",
		"{\"pid\":\"13645\",\"name\":\"미닝\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"13645\", \"show_prof\":\"0\"}",
		"{\"pid\":\"77\", \"targetIdx\":\"16643\", \"sourceIdx\":\"16657\", \"targetId\":\"RD0300014\", \"sourceId\":\"RD1200000\"}",
		//테스트용
		"{\"pid\":\"13645\",\"cid\":\"RS0100042\"}",
		"{\"pid\":\"13645\",\"iid\":\"WCC1120001\"}",
		"{\"pid\":\"13645\",\"characters\":[{\"cid\":\"111\"},{\"cid\":\"222\"}]}",
		"{\"pid\":\"13645\"}",
		"{\"pid\":\"1\"}",
		"{\"prefix\":\"WEAPONTEST_\",\"maxSeq\":\"108\"}",
		"{\"pid\":\"10\",\"sid\":\"0\",\"title\":\"NG_MESSAGE_MAIL_REWARD_EV\",\"attach_type\":\"GAC060006\",\"attach_value\":\"1\",\"expire_date\":\"72\",\"cursession\":\"forAdmin\"}"
	];

	function apiTypeChg() {
		var actiondisp = document.getElementById("ActionURL");
		var dataSam = document.getElementById("dataSample");

		actiondisp.innerText = "http://<?=$_SERVER['HTTP_HOST']?>/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
		dataSam.innerText = arrSampleData[frmTest.apitype.selectedIndex];
		document.getElementById("samData").value = arrSampleData[frmTest.apitype.selectedIndex];
		frmTest.action = "/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
	}

	function sendact() {
		var jsonString = JSON.parse(document.getElementById("samData").value);
		if ( jsonString.pid )
		{
			document.getElementById("pid").value = jsonString.pid;
		}
		else
		{
			document.getElementById("pid").value = "";
		}
		frmTest.submit();
	}

	window.onload = function () {
		document.getElementById("samData").value = arrSampleData[frmTest.apitype.selectedIndex];
		frmTest.action = "/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
		document.getElementById("dataSample").innerText = arrSampleData[frmTest.apitype.selectedIndex];
		document.getElementById("ActionURL").innerText = "http://<?=$_SERVER['HTTP_HOST']?>/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";

	}
</script>
모든 데이터는 data 라는 이름의 파라미터에 json 형태의 value 로 전달 하시면 됩니다.<br /><br />

Action주소 : <br />
<span id="ActionURL">
</span><br /><br />
샘플데이터 : <br />
<span id="dataSample">
</span><br />
<form name="frmTest" method="post" action="">
	<select name="apitype" onchange="javascript:apiTypeChg();">
		<option value="requestJoin">requestJoin</option>
		<option value="requestGuestLogin">requestGuestLogin</option>
		<option value="requestMailReceipt">requestMailReceipt</option>
		<option value="requestMailReceiptAll">requestMailReceiptAll</option>
		<option value="requestMailList">requestMailList</option>
		<option value="requestLogin">requestLogin</option>
		<option value="requestUpdateFile">requestUpdateFile</option>
		<option value="requestPlayer">requestPlayer</option>
		<option value="requestProductList">requestProductList</option>
		<option value="requestEquipToChar">requestEquipToChar</option>
		<option value="requestAttendEvent">requestAttendEvent</option>
		<option value="requestBuyProduct">requestBuyProduct</option>
		<option value="requestBuyIap">requestBuyIap</option>
		<option value="requestDeployCharacters">requestDeployCharacters</option>
		<option value="requestLoggingStartStepForPVE">requestLoggingStartStepForPVE</option>
		<option value="requestLoggingRewardStepForPVE">requestLoggingRewardStepForPVE</option>
		<option value="requestLoggingRetryRewardStepForPVE">requestLoggingRetryRewardStepForPVE</option>
		<option value="requestLoggingStartStepForPVB">requestLoggingStartStepForPVB</option>
		<option value="requestLoggingResultStepForPVB">requestLoggingResultStepForPVB</option>
		<option value="requestLoggingStartStepForSURVIVAL">requestLoggingStartStepForSURVIVAL</option>
		<option value="requestLoggingResultStepForSURVIVAL">requestLoggingResultStepForSURVIVAL</option>
		<option value="requestUpgradeCharacter">requestUpgradeCharacter</option>
		<option value="requestSynthesize">requestSynthesize</option>
		<option value="requestAchieveList">requestAchieveList</option>
		<option value="requestAchieveStatusUpdate">requestAchieveStatusUpdate</option>
		<option value="requestAchieveReward">requestAchieveReward</option>
		<option value="requestPVERank">requestPVERank</option>
		<option value="requestSellProduct">requestSellProduct</option>
		<option value="requestExplorationList">requestExplorationList</option>
		<option value="requestExplorationStart">requestExplorationStart</option>
		<option value="requestExplorationResult">requestExplorationResult</option>
		<option value="requestExplorationReset">requestExplorationReset</option>
		<option value="requestExplorationGroupResult">requestExplorationGroupResult</option>
		<option value="requestCollectionList">requestCollectionList</option>
		<option value="requestLoggingStartStepForPVP">requestLoggingStartStepForPVP</option>
		<option value="requestLoggingResultStepForPVP">requestLoggingResultStepForPVP</option>
		<option value="requestRankingInfoPVP">requestRankingInfoPVP</option>
		<option value="requestRankingInfoPVB">requestRankingInfoPVB</option>
		<option value="requestRankingInfoSURVIVAL">requestRankingInfoSURVIVAL</option>
		<option value="requestRecomFriendList">requestRecomFriendList</option>
		<option value="requestMyFriendList">requestMyFriendList</option>
		<option value="requestPVEFriendList">requestPVEFriendList</option>
		<option value="requestAddFriend">requestAddFriend</option>
		<option value="requestReplyAddFriend">requestReplyAddFriend</option>
		<option value="requestDelFriend">requestDelFriend</option>
		<option value="requestFriendshipPoint">requestFriendshipPoint</option>
		<option value="requestOtherPlayer">requestOtherPlayer</option>
		<option value="requestEquipToPlayer">requestEquipToPlayer</option>
		<option value="requestUpdateTactic">requestUpdateTactic</option>
		<option value="requestChangeOperator">requestChangeOperator</option>
		<option value="requestChangePilot">requestChangePilot</option>
		<option value="requestWeeklyReward">requestWeeklyReward</option>
		<option value="requestExplorationReward">requestExplorationReward</option>
		<option value="requestUpdateIncresement">requestUpdateIncresement</option>
		<option value="requestUpdateName">requestUpdateName</option>
		<option value="requestExtraAttendEvent">requestExtraAttendEvent</option>
		<option value="requestExtraAttendList">requestExtraAttendList</option>
		<option value="requestMailCount">requestMailCount</option>
		<option value="requestUpdateShowProfile">requestUpdateShowProfile</option>
		<option value="requestEvolution">requestEvolution</option>
		<!-- 테스트용 -->
		<option value="requestCharacterProvisioning">requestCharacterProvisioning</option>
		<option value="requestItemProvisioning">requestItemProvisioning</option>
		<option value="requestMaxCharacter">requestMaxCharacter</option>
		<option value="requestMaxCharacterAll">requestMaxCharacterAll</option>
		<option value="requestInit">requestInit</option>
		<option value="requestTestLoop">requestTestLoop</option>
		<option value="requestSendMail">requestSendMail</option>
	</select><br /><br />
	<textarea name="data" id="samData" style="width:600px;height:300px;">
	</textarea><br /><br />
	<input type="hidden" name="pid" id="pid" value="" />
	<input type="button" name="sendbtn" value="제출" onclick="sendact();" />
</form>
