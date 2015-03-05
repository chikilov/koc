<?php
class Con_Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('security');
//		$this->load->model("api/Model_Mail", "dbMail");
//		$this->load->model("api/Model_Play", "dbPlay");
//		$this->load->model("api/Model_Rank", "dbRank");
//		$this->load->model("api/Model_Record", "dbRecord");
//		$this->load->model("api/Model_Ref", "dbRef");
		$this->load->model('admin/Model_Admin', "dbAdmin");

		if ( ENVIRONMENT == 'production' )
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			define("URLBASE", "/koc920/");
		}
		else if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'staging' )
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			define("URLBASE", "/koc/");
		}
		//$this->load->model('admin/Model_Admin', "dbModel");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view()
	{
		$userId = $this->session->userdata('userId_session');
		if( $userId != null )
		{
			redirect( URLBASE."index.php/pages/admin/accountbasic/view", "refresh" );
		}
		else
		{
			$this->load->view('admin/view_Login');
		}
	}

	public function logout()
	{
		$this->session->set_userdata( 'userId_session', null );
		$this->session->set_userdata( 'currentUrl_session', 'index.php/pages/admin/login/view' );
		redirect('index.php/pages/admin/login/view', "refresh");
	}

	public function chkAuth()
	{
		$admin_id = $_REQUEST['admin_id'];
		$admin_pw = $_REQUEST['admin_pw'];

		$ret = (bool)$this->dbAdmin->requestLogin( $admin_id, $admin_pw );

		if( $ret > 0 )
		{
			$result = "OK";
			$message = "PASS";
			$url = "../../../pages/admin/member/view";
			$this->session->set_userdata( 'userId_session', $admin_id );
			$this->session->set_userdata( 'currentUrl_session', 'index.php/pages/admin/login/view' );
		}
		else
		{
			$result = "FAIL";
			$message = "No Matching ID & PW";
			$url = "";
		}

		$retArray = array( "result"=>$result, "message"=>$message, "url"=>$url );
		echo json_encode($retArray);
	}

}
?>

