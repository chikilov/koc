<?php
class Con_EventPresent extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/Model_Admin', "dbAdmin");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $searchParam = "", $searchValue = "" )
	{
		$data = array( 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view( 'admin/view_EventPresent', $data );
	}

	public function requestArticleList()
	{
		$arrayResult = $this->dbRef->requestAdminArticleList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestPresentEventInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$evt_id = $decoded["evt_id"];
		$evt_category = $decoded["evt_category"];
		$evt_target = $decoded["evt_target"];
		$evt_value = $decoded["evt_value"];
		$evt_reason = $decoded["evt_reason"];

		$arrEvtId = explode(",", $evt_id);
		$totCnt = count($arrEvtId);
		$arrEvtId = array_unique($arrEvtId, SORT_LOCALE_STRING);
		$result["countmsg"] = "총 ".$totCnt."건 중 중복 ".($totCnt - count($arrEvtId))."건";

		$idx = $this->dbAdmin->requestPresentEventInsert( $evt_category, $evt_target, $evt_value, $evt_reason );
		$this->dbAdmin->requestPresentEventSubInsert( $idx, $arrEvtId );
		foreach( $arrEvtId as $row )
		{
			//정상아이디 체크 로직 필요
			$this->dbMail->sendMail( $row, MY_Controller::SENDER_GM, MY_Controller::ACCESS_EVENT_REWARD_TITLE, $evt_target, $evt_value, MY_Controller::NORMAL_EXPIRE_TERM );
		}

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $result, null );
	}

	public function requestPresentEventList()
	{
		$arrayResult = $this->dbAdmin->requestPresentEventList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestPresentEventSubList()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$evt_id = $decoded["evt_id"];

		$arrayResult = $this->dbAdmin->requestPresentEventSubList( $evt_id )->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}
}
?>

