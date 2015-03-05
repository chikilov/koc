<?php
class Con_LogGame extends MY_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('admin/Model_Admin', "dbModel");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $searchParam = "", $searchValue = "" )
	{
		$data = array( 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view( 'admin/view_LogGame', $data );
	}
}
?>

