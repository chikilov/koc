<?php
class Con_ChargeManage extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/Model_Admin', "dbAdmin");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $searchParam = "", $searchValue = "", $platform = "" )
	{
		$data = array( 'searchParam' => $searchParam, 'searchValue' => $searchValue, 'platform' => $platform );
		$this->load->view( 'admin/view_ChargeManage', $data );
	}

	public function requestBuyIAPList()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$platform = $decoded["platform"];

		$arrayResult = $this->dbAdmin->requestBuyIAPList( $searchParam, $searchValue, $start_date, $end_date, $platform )->result_array();

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
			$pid = $arrayResult[0]["pid"];
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}
}
?>

