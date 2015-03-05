<?php
class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		if ( ENVIRONMENT == 'production' )
		{
		}
		else if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'staging' )
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
		}

		date_default_timezone_set('Asia/Seoul');
		$this->load->library( 'LogW', TRUE );
	}
}
?>
