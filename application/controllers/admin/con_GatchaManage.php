<?php
class Con_GatchaManage extends MY_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('admin/Model_Admin', "dbModel");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view()
	{
		$this->load->view( 'admin/view_GatchaManage' );
	}

	public function gatchaInfo()
	{
		$this->load->model('api/Model_Ref', "dbRef");
		$arrayResult = $this->dbRef->requestGatchaProbabilityList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}
}
?>

