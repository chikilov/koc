<?php
class Con_Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('security');
		$userId = $this->session->userdata('userId_session');
		if( $userId != null )
		{
			redirect('index.php/pages/admin/member/view', "refresh");
		}
		
		//$this->load->model('admin/Model_Admin', "dbModel");
	}
	
	function index()
	{
		$this->load->view('error/403_Forbidden');
	}
	
	public function view()
	{
		$this->load->view('admin/view_Login');
	}
	
	public function chkAuth()
	{
		$input_id = $_REQUEST['input_id'];
		$input_pw = $_REQUEST['input_pw'];
		
		$login_id = $input_id;
		$input_id = do_hash($input_id);
		$input_pw = do_hash($input_pw);

		$ret = 1;
		
		if( $ret > 0 )
		{
			$result = "OK";
			$message = "PASS";
			$url = "../../../pages/admin/member/view";
			$this->session->set_userdata( 'userId_session', $login_id );
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

