<?php 
class Con_Member extends MY_Controller {

	function __construct(){
		parent::__construct();		
	}
	
	function index()
	{
		$this->load->view('error/403_Forbidden');
	}
	
	public function view()
	{
		$this->load->view('admin/view_Member');
	}
	
	public function requestHeart()
	{
		$kakao_id = $_REQUEST["kakaoId"];
		
		$arrayData = $this->dbModel->requestHeart( $kakao_id )->result_array();

		//레코드 수가 1 이상이면 에러
		if (count($arrayData) > 1)
		{
			$arrayData = "To Many Rows";
		}
		else if (count($arrayData) < 1)
		{
			$arrayData = "No Rows";
		}
		else
		{
			$this->calcurate_heart( $kakao_id, $arrayData );
		}
		
		$arrayData = $this->dbModel->requestHeart( $kakao_id )->result_array();
		$arrayData = $arrayData[0];
		$currentTime = $this->dbModel->getCurrentTime()->result_array();
		$currentTime = strtotime((string)$currentTime[0]["cur_date_time"]);
		$lastHeartTime = $arrayData["last_hearttime"];
		$lastHeartTime = strtotime((string)$lastHeartTime);
		$diffInSec = $currentTime - $lastHeartTime;
		if ($arrayData["current_heart"] < 5)
		{
			$arrayData["last_hearttime"] = MY_Controller::HEART_REFILL_SECONDS - ($diffInSec % MY_Controller::HEART_REFILL_SECONDS);
		}
		$arrayData["last_hearttime"] = (string)$arrayData["last_hearttime"];
		
		echo $this->json_encode2( $arrayData );
	}

	public function getData()
	{
		$kakaoId = $_REQUEST['kakaoId'];
		$accountInfo = $this->admModel->requestAccountInfo( $kakaoId )->result_array();
		$accountInfo = $accountInfo[0];
		//레벨 계산
		$nextLv = 1;
		$totalExp = intval($accountInfo["exp"]);
		$remainExp = $totalExp;
		$lvExp = 407;
		while ($remainExp > $lvExp)
		{
			$remainExp -= $lvExp;
			$nextLv += 1;
			$lvExp = round(pow($nextLv, floatval(3.4)) + 400, 0, PHP_ROUND_HALF_UP);
		}
		$accountInfo["level"] = (string)$nextLv;
		$tmpLog = $this->admModel->requestLogDate( $kakaoId )->result_array();
		$accountInfo["logDate"] = $tmpLog[0]["logCnt"];
		$accountInfo["min_req"] = $tmpLog[0]["min_req"];
		$accountInfo["max_req"] = $tmpLog[0]["max_req"];
		$accountInfo["diff_req"] = $tmpLog[0]["diff_req"];

		$retentionInfo = $this->admModel->requestItemCoinState( $kakaoId )->result_array();
		$retentionInfo = $retentionInfo[0];
		
		//하트 다시 계산
		$currentData = $this->dbModel->requestHeart( $kakaoId )->result_array();
		$this->calcurate_heart( $kakaoId, $currentData );
		$currentData = $this->dbModel->requestHeart( $kakaoId )->result_array();
		$retentionInfo = array_merge($retentionInfo, $currentData[0]);
		
		$stopProcessHistory = $this->admModel->requestStatusLog( $kakaoId )->result_array();
		
		$itemRetentionInfo = $this->admModel->requestItemRetentionInfo( $kakaoId )->result_array();
		
		$itemRetentionInfoHistory = $this->admModel->requestItemRetentionInfoHistory( $kakaoId )->result_array();

		$moneyInfoDataArray = $this->admModel->requestMoneyInfoDataArray( $kakaoId )->result_array();
		
		$moneyInfoDataTable = $this->admModel->requestMoneyInfoDataTable( $kakaoId )->result_array();
		$moneyInfoDataTable = $moneyInfoDataTable[0];
		
		$fenceData = $this->admModel->requestFenceData( $kakaoId )->result_array();
		
		$plantData = $this->admModel->requestPlantData( $kakaoId )->result_array();
		
		$heartDataArray = $this->admModel->requestHeartLog( $kakaoId )->result_array();
		
		$resignData = $this->admModel->requestResignLog( $kakaoId )->result_array();
		
		$killMonsterData = $this->admModel->requestKillMonster( $kakaoId )->result_array();

		echo $this->json_encode2( array( "accountInfo"=>$accountInfo, "retentionInfo"=>$retentionInfo, "stopProcessHistory"=>$stopProcessHistory, "itemRetentionInfo"=>$itemRetentionInfo, "itemRetentionInfoHistory"=>$itemRetentionInfoHistory, "moneyInfoDataArray"=>$moneyInfoDataArray, "moneyInfoDataTable"=>$moneyInfoDataTable, "fenceData"=>$fenceData, "plantData"=>$plantData, "heartDataArray"=>$heartDataArray, "resignData"=>$resignData, "killMonsterData"=>$killMonsterData ) );
	}

	public function userStopProcess()
	{
		$kakaoId = $_REQUEST['kakaoId'];
		$userStopType = $_REQUEST['userStopType'];
		$userStopAdminMemo = $_REQUEST['userStopAdminMemo'];
		$managerID = $_REQUEST['managerID'];
		
		$this->admModel->onStartTransaction();
		$data = $this->admModel->onStopUserLog( $kakaoId, $userStopType, $userStopAdminMemo, $managerID );
		$data = $this->admModel->onStopUserAccount( $kakaoId, $userStopType );
		$this->admModel->onCompleteTransaction();
		
		$stopProcessHistory = $this->admModel->requestStatusLog( $kakaoId )->result_array();
		echo $this->json_encode2( array( "stopProcessHistory"=>$stopProcessHistory ) );
	}
}
?>


