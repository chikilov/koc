<?php
class Con_AccountBasic extends MY_Controller {

	function __construct(){
		parent::__construct();
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $searchParam = "", $searchValue = "" )
	{
		$data = array( 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view( 'admin/view_AccountBasic', $data );
	}

	public function basicInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult = $this->dbPlay->requestBasicInfoByPid( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult = $this->dbPlay->requestBasicInfoById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult = $this->dbPlay->requestBasicInfoByName( $searchValue )->result_array();
		}

		if ( empty($arrayResult) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = $arrayResult[0];
			$pid = $arrayResult["pid"];
			$arrayResult["last_pve"] = $this->dbRank->requestLastPVEStage( $pid )->result_array();
			$arrayResult["last_pvp"] = $this->dbRank->requestLastPVPStage( $pid )->result_array();
			$arrayResult["last_pvb"] = $this->dbRank->requestLastPVBStage( $pid )->result_array();
			$arrayResult["last_survival"] = $this->dbRank->requestLastSURVIVALStage( $pid )->result_array();
			$arrayResult["remain_item"] = $this->dbPlay->requestAdminItem( $pid )->result_array();
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function mailInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $decoded["pid"];
		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = $this->dbMail->adminMailList( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$pid = null;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function collectionInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $decoded["pid"];
		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = $this->dbPlay->adminCollectionList( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$pid = null;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function achieveInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $decoded["pid"];
		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult["attendinfo"] = $this->dbPlay->adminAttendInfo( $pid )->result_array();
			$arrayResult["dailyinfo"] = $this->dbPlay->adminDailyAchieveInfo( $pid )->result_array();
			$arrayResult["achieveinfo"] = $this->dbPlay->adminAchieveInfo( $pid )->result_array();
			$arrayResult["researchinfo"] = $this->dbPlay->adminResearchAchieveInfo( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$pid = null;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function operInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $decoded["pid"];
		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult["operinfo"] = $this->dbPlay->adminOperInfo( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$pid = null;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function teamInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $decoded["pid"];
		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult["teaminfo"] = $this->dbPlay->adminTeamInfo( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$pid = null;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}
}
?>

