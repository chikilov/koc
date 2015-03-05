<?php
class Con_EventManage extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/Model_Admin', "dbAdmin");
	}

	function index( )
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $subnavi = 0, $searchParam = "", $searchValue = "" )
	{
		$data = array( 'subnavi' => $subnavi, 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view('admin/view_EventManage_'.$subnavi, $data );
	}

	public function requestProductList()
	{
		$arrayResult = $this->dbRef->requestAdminProductList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestDisEventList()
	{
		$arrayResult = $this->dbAdmin->requestDisEventList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestDisEventInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$evt_category = $decoded["evt_category"];
		$evt_target = $decoded["evt_target"];
		$evt_paytype = $decoded["evt_paytype"];
		$evt_value = $decoded["evt_value"];
		$evt_reason = $decoded["evt_reason"];

		if ( $this->dbAdmin->requestDisEventInsert( $start_date, $end_date, $evt_category, $evt_target, $evt_paytype, $evt_value, $evt_reason ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, null, null );
	}

	public function requestDisEventStop()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$idx = $decoded["idx"];
		if ( $this->dbAdmin->requestDisEventStop( $idx ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, null, null );
	}

	public function requestArticleList()
	{
		$arrayResult = $this->dbRef->requestAdminArticleList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestAccEventList()
	{
		$arrayResult = $this->dbAdmin->requestAccEventList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestAccEventInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$evt_category = $decoded["evt_category"];
		$evt_target = $decoded["evt_target"];
		$evt_value = $decoded["evt_value"];
		$evt_reason = $decoded["evt_reason"];

		if ( $this->dbAdmin->requestAccEventInsert( $start_date, $end_date, $evt_category, $evt_target, $evt_value, $evt_reason ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, null, null );
	}

	public function requestAccEventStop()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$idx = $decoded["idx"];
		if ( $this->dbAdmin->requestAccEventStop( $idx ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, null, null );
	}
}
?>

