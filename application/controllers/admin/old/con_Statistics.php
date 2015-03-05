<?php 
class Con_Statistics extends MY_Controller {

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
		$this->load->view('admin/view_Statistics');
	}
}
?>


