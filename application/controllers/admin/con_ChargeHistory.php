<?php
class Con_ChargeHistory extends MY_Controller {

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
		$this->load->view( 'admin/view_ChargeHistory', $data );
	}

	public function requestAssetLogList()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];

		$arrayResult = $this->dbAdmin->requestAssetLogList( $searchParam, $searchValue, $start_date, $end_date )->result_array();

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

	public function getProductName()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$product_id = $decoded["product_id"];

		$arrayResult = $this->dbAdmin->getNameForId( "PRODUCT", $product_id )->result_array();

		if ( empty($arrayResult) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}
}
?>

