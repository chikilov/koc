<?php
class Index extends CI_Controller {

	function __construct(){
		parent::__construct();
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Seoul');
	}
	
	function index()
	{
		$this->load->view('error/403_Forbidden');
	}
}
?>