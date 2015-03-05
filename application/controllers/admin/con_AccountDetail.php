<?php
class Con_AccountDetail extends MY_Controller {

	function __construct(){
		parent::__construct();
	}

	function index( )
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $subnavi = 0, $searchParam = "", $searchValue = "" )
	{
		$data = array( 'subnavi' => $subnavi, 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view('admin/view_AccountDetail_'.$subnavi, $data );
	}

	public function characterInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult["charinfo"] = $this->dbPlay->requestCharacterInfoByPid( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult["charinfo"] = $this->dbPlay->requestCharacterInfoById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult["charinfo"] = $this->dbPlay->requestCharacterInfoByName( $searchValue )->result_array();
		}

		$arrayResult["expinfo"] = $this->dbRef->requestExpInfo()->result_array();

		if ( empty($arrayResult["charinfo"]) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$pid = $arrayResult["charinfo"][0]["pid"];
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function characterLevelChange()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$cid = $this->NG_DECRYPT( $decoded[ "cid" ] );
		$clev = $this->NG_DECRYPT( $decoded[ "clev" ] );
		$cexp = $this->NG_DECRYPT( $decoded[ "cexp" ] );
		$cug = $this->NG_DECRYPT( $decoded[ "cug" ] );

		if( $pid && $cid )
		{
			$this->dbPlay->requestLevelUgrChange( $pid, $cid, $clev, $cexp, $cug );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}


		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function characterDelete()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$cid = $this->NG_DECRYPT( $decoded[ "cid" ] );
		$sellInfo = $this->dbPlay->requestCharacterGradeLev( $cid )->result_array()[0];

		if ( count($sellInfo) > 0 )
		{
			if ( $sellInfo["weapon"] != null && $sellInfo["weapon"] != "" )
			{
				$this->dbPlay->deletePlayerItem( $sellInfo["weapon"] );
			}
			if ( $sellInfo["backpack"] != null && $sellInfo["backpack"] != "" )
			{
				$this->dbPlay->deletePlayerItem( $sellInfo["backpack"] );
			}
			if ( $sellInfo["skill_0"] != null && $sellInfo["skill_0"] != "" )
			{
				$this->dbPlay->deletePlayerItem( $sellInfo["skill_0"] );
			}
			if ( $sellInfo["skill_1"] != null && $sellInfo["skill_1"] != "" )
			{
				$this->dbPlay->deletePlayerItem( $sellInfo["skill_1"] );
			}
			if ( $sellInfo["skill_2"] != null && $sellInfo["skill_2"] != "" )
			{
				$this->dbPlay->deletePlayerItem( $sellInfo["skill_2"] );
			}
			$this->dbPlay->deleteCharacterTeam( $pid, $cid );
			$result = (bool)$this->dbPlay->deletePlayerCharacter( $cid );

			if ( $result )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function inventoryInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestInventoryInfoByPid( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestInventoryInfoById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestInventoryInfoByName( $searchValue )->result_array();
		}

		if ( empty($arrayResult["inventoryInfo"]) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$pid = $arrayResult["inventoryInfo"][0]["pid"];
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function inventoryDelete()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$iid = $this->NG_DECRYPT( $decoded[ "iid" ] );

		$this->dbPlay->requestUnequipItemFromChar( $pid, $iid );
		$result = (bool)$this->dbPlay->deletePlayerItem( $iid );

		if ( $result )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function pilotInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestPilotInfoByPid( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestPilotInfoById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestPilotInfoByName( $searchValue )->result_array();
		}

		if ( empty($arrayResult["inventoryInfo"]) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$pid = $arrayResult["inventoryInfo"][0]["pid"];
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function pilotDelete()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$iid = $this->NG_DECRYPT( $decoded[ "iid" ] );

		$this->dbPlay->requestUnequipPilotFromTeam( $pid, $iid );
		$result = (bool)$this->dbPlay->deletePlayerItem( $iid );

		if ( $result )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function operatorInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestOperatorInfoByPid( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestOperatorInfoById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult["inventoryInfo"] = $this->dbPlay->requestOperatorInfoByName( $searchValue )->result_array();
		}

		if ( empty($arrayResult["inventoryInfo"]) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$pid = $arrayResult["inventoryInfo"][0]["pid"];
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function operatorDelete()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$iid = $this->NG_DECRYPT( $decoded[ "iid" ] );

		$this->dbPlay->requestUnequipOperatorFromPlayer( $pid, $iid );
		$result = (bool)$this->dbPlay->deletePlayerItem( $iid );

		if ( $result )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function operatorUpdate()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$pid = $this->NG_DECRYPT( $decoded[ "pid" ] );
		$iid = $this->NG_DECRYPT( $decoded[ "iid" ] );
		$expire = $this->NG_DECRYPT( $decoded[ "expire" ] );

		$result = (bool)$this->dbPlay->requestUpdateOperatorExpire( $pid, $iid, $expire );

		if ( $result )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function playInfo()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		if ( $searchParam == "pid" )
		{
			$arrayResult["last_pve"] = $this->dbRank->requestLastPVEStageByPid( $searchValue )->result_array();
			$arrayResult["last_pvp"] = $this->dbRank->requestLastPVPStageByPid( $searchValue )->result_array();
			$arrayResult["last_pvb"] = $this->dbRank->requestLastPVBStageByPid( $searchValue )->result_array();
			$arrayResult["last_survival"] = $this->dbRank->requestLastSURVIVALStageByPid( $searchValue )->result_array();
//			$arrayResult["last_exploration"] = $this->dbRank->requestLastPVEStageById( $searchValue )->result_array();
		}
		else if ( $searchParam == "id" )
		{
			$arrayResult["last_pve"] = $this->dbRank->requestLastPVEStageById( $searchValue )->result_array();
			$arrayResult["last_pvp"] = $this->dbRank->requestLastPVPStageById( $searchValue )->result_array();
			$arrayResult["last_pvb"] = $this->dbRank->requestLastPVBStageById( $searchValue )->result_array();
			$arrayResult["last_survival"] = $this->dbRank->requestLastSURVIVALStageById( $searchValue )->result_array();
//			$arrayResult["last_exploration"] = $this->dbRank->requestLastPVEStageById( $searchValue )->result_array();
		}
		else
		{
			$arrayResult["last_pve"] = $this->dbRank->requestLastPVEStageByName( $searchValue )->result_array();
			$arrayResult["last_pvp"] = $this->dbRank->requestLastPVPStageByName( $searchValue )->result_array();
			$arrayResult["last_pvb"] = $this->dbRank->requestLastPVBStageByName( $searchValue )->result_array();
			$arrayResult["last_survival"] = $this->dbRank->requestLastSURVIVALStageByName( $searchValue )->result_array();
//			$arrayResult["last_exploration"] = $this->dbRank->requestLastPVEStageByName( $searchValue )->result_array();
		}

		if ( empty($arrayResult["last_pve"]) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$pid = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$pid = $arrayResult["last_pve"][0]["pid"];
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}
}
?>

