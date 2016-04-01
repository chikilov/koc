<?php
	if ( array_key_exists( 'CI_ENV', $_SERVER ) )
	{
		define('ENVIRONMENT', $_SERVER['CI_ENV']);
		define('SERVERGROUP', $_SERVER['SERVERGROUP']);
		define('LOGROOT', $_SERVER['LOGROOT']);
	}
	else
	{
		define('ENVIRONMENT', 'development');
		define('SERVERGROUP', '1');
		define('LOGROOT', '/tmp/');
	}

	if ( ENVIRONMENT == 'production' )
	{
		$serverName = "live ".SERVERGROUP;
		$urlbase = "koc";
	}
	else if ( ENVIRONMENT == 'staging' )
	{
		$serverName = "stage ".SERVERGROUP;
		$urlbase = "koc";
	}
	else
	{
		$serverName = "dev";
		$urlbase = "koc";
	}
	echo $_SERVER['SERVER_NAME']." ( ".$serverName." )<br />";
	$strurl = "/".$urlbase."/index.php/request/api/requestLogin/";
?>
<script type="text/javascript">
	var arrSampleData = [
		"{\"id\":\"chikilov\", \"password\":\"1212\", \"name\":\"최민\", \"email\":\"cm@neogramgames.com\"}",
		"{\"macaddr\":\"00:00:00:00:00:00\", \"uuid\":\"123qweasdzxc\", \"package\":\"com.neogram.editor\"}",
		"{\"pid\":\"13645\", \"idx\":\"4\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"attach_type\":\"FRIENDSHIP_POINTS\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"category\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"id\":\"chikilov\", \"password\":\"dudrud\", \"package\":\"com.neogram.editor\"}",
		"{\"device\":\"ANDROID\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"appid\":\"13645\", \"store_type\":\"1\", \"store_version\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cid\":\"137\", \"slotseq\":\"skill_1\", \"iid\":\"636\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"product\":\"NGP_PK_0001\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"product\":\"NGP_CR_0206\", \"sid\":\"13645\", \"cursession\":\"forAdmin\"}",
//		"{\"pid\":\"13645\", \"team_seq\":\"1\"}",
		"{\"pid\":\"13645\", \"teamInfo\":{\"00\":\"223\", \"01\":\"224\", \"02\":\"225\", \"10\":\"224\", \"11\":\"225\", \"12\":\"223\", \"20\":\"225\", \"21\":\"223\", \"22\":\"224\"}, \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"stageid\":\"100109\", \"friendid\":\"\", \"instant_item1\":\"NGP_BF_00001\", \"instant_item2\":\"NGP_BF_00002\", \"instant_item3\":\"NGP_BF_00003\", \"instant_item4\":\"NGP_BF_00004\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"stageid\":\"100109\", \"logid\":\"1\", \"cid_0\":\"239\", \"cid_1\":\"240\", \"cid_2\":\"241\", \"exp_0\":\"13\", \"exp_1\":\"17\", \"exp_2\":\"23\", \"lev_0\":\"2\", \"lev_1\":\"3\", \"lev_2\":\"5\", \"basic_reward_type\":\"GAME_POINTS\", \"basic_reward_value\":\"10\", \"duration\":\"83\", \"is_clear\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"rid\":\"RWPVE01009\", \"rpattern\":\"5\", \"rseq\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"stageid\":\"400101\", \"use_cash\":\"0\", \"level\":\"1\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"score\":\"12123\", \"is_clear\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"use_cash\":\"0\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"logid\":\"1\", \"score\":\"12123\", \"is_clear\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"targetIdx\":\"240\", \"sourceIdx\":\"255\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"targetIdx\":\"240\", \"sourceIdx\":\"255\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"arrayAchieve\":[{\"aid\":\"AADCU01001\", \"astatus\":\"1\"}, {\"aid\":\"AADE01001\", \"astatus\":\"3\"}], \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"aid\":[\"AADCU01001\", \"AADE01001\"], \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"sell_type\":\"CHARACTER\", \"sell_idx\":\"351\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\",\"grade\":\"3\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\",\"grade\":\"3\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\",\"exp_idx\":\"1\",\"cid\":\"392\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"use_cash\":\"0\", \"instant_item1\":\"NGP_BF_00201\", \"instant_item2\":\"NGP_BF_00202\", \"instant_item3\":\"NGP_BF_00301\", \"instant_item4\":\"NGP_BF_00302\", \"tactinfo\":{\"00\":\"2\", \"01\":\"1\", \"02\":\"0\", \"10\":\"0\", \"11\":\"0\", \"12\":\"0\", \"20\":\"0\", \"21\":\"0\", \"22\":\"0\"}, \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"logid\":\"1\",\"is_clear\":\"-1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"page\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"page\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"page\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"searchval\":\"neogram\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"status\":\"0\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\",\"fid\":\"13645\",\"status\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"fid\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"slotseq\":\"pilot_0\", \"iid\":\"2014\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"tactinfo\":{\"00\":\"2\", \"01\":\"1\", \"02\":\"0\", \"10\":\"0\", \"11\":\"0\", \"12\":\"0\", \"20\":\"0\", \"21\":\"0\", \"22\":\"0\"}, \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"slotseq\":\"pilot_0\", \"iid\":\"2014\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13670\", \"slotseq\":\"00\", \"iid\":\"2219\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"rewardtype\":\"pvb\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"exp_group_idx\":\"120\",\"exp_idx\":\"6\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"slottype\":\"cha\",\"incquantity\":\"5\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"name\":\"미닝\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"show_prof\":\"0\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"77\", \"targetIdx\":\"16643\", \"sourceIdx\":\"16657\", \"targetId\":\"RD0300014\", \"sourceId\":\"RD1200000\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"10\", \"itemType\":\"weapon\", \"targetIdx\":\"6252\", \"sourceIdx\":\"6919\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"24\", \"camount\":\"10\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"136\", \"itemType\":\"WEAPON\", \"targetIdx\":\"1148527\", \"sourceIdx\":\"1148529\", \"grade\":\"5\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"136\", \"itemType\":\"WEAPON\", \"sourceIdx\":\"1148656\", \"grade\":\"6\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"102870\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"3\", \"cid\":\"354077\", \"gtype_0\":\"0\", \"gtype_1\":\"1\", \"gtype_2\":\"0\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"3\", \"cid\":\"354077\", \"gtype_0\":\"0\", \"gtype_1\":\"1\", \"gtype_2\":\"0\", \"cursession\":\"forAdmin\"}",
		//테스트용
		"{\"pid\":\"13645\",\"cid\":\"RS0100042\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"iid\":\"WCC1120001\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\",\"characters\":[{\"cid\":\"111\"},{\"cid\":\"222\"}], \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"13645\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"1\", \"cursession\":\"forAdmin\"}",
		"{\"prefix\":\"WEAPONTEST_\",\"maxSeq\":\"108\", \"cursession\":\"forAdmin\"}",
		"{\"pid\":\"10\",\"sid\":\"0\",\"title\":\"NG_MESSAGE_MAIL_REWARD_EV\",\"attach_type\":\"GAC060006\",\"attach_value\":\"1\",\"expire_date\":\"72\",\"cursession\":\"forAdmin\"}"
	];

	function apiTypeChg() {
		var actiondisp = document.getElementById("ActionURL");
		var dataSam = document.getElementById("dataSample");

		actiondisp.innerText = "http://<?=$_SERVER['HTTP_HOST']?>/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
		dataSam.innerText = arrSampleData[frmTest.apitype.selectedIndex];
		document.getElementById("samData").value = arrSampleData[frmTest.apitype.selectedIndex];
<?php
	if ( $urlbase == "" )
	{
?>
		frmTest.action = "/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
	else
	{
?>
		frmTest.action = "/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
?>
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
<?php
	if ( $urlbase == "" )
	{
?>
		frmTest.action = "/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
	else
	{
?>
		frmTest.action = "/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
?>
		document.getElementById("dataSample").innerText = arrSampleData[frmTest.apitype.selectedIndex];
<?php
	if ( $urlbase == "" )
	{
?>
		document.getElementById("ActionURL").innerText = "http://<?=$_SERVER['HTTP_HOST']?>/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
	else
	{
?>
		document.getElementById("ActionURL").innerText = "http://<?=$_SERVER['HTTP_HOST']?>/<?=$urlbase?>/index.php/request/api/" + frmTest.apitype.value + "/";
<?php
	}
?>
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
		<option value="requestUpgradeItem">requestUpgradeItem</option>
		<option value="requestLoggingRetryStepForPVE">requestLoggingRetryStepForPVE</option>
		<option value="requestSynthesizeItem">requestSynthesizeItem</option>
		<option value="requestSynthesizeItemRetry">requestSynthesizeItemRetry</option>
		<option value="requestInitDelayForPVP">requestInitDelayForPVP</option>
		<option value="requestResetGearSlot">requestResetGearSlot</option>
		<option value="requestSynthesizeGear">requestSynthesizeGear</option>
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
