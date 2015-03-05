<?php
class Con_RankThisweek extends MY_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->model('admin/Model_Admin', "dbModel");
	}

	function index( )
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $subnavi = 0, $searchParam = "", $searchValue = "" )
	{
		$data = array( 'subnavi' => $subnavi, 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view('admin/view_RankThisweek_'.$subnavi, $data );
	}
}
?>

