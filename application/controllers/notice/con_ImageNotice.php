<?php
class Con_ImageNotice extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("admin/Model_Admin", "dbAdmin");
	}

	function index()
	{
		$this->load->view("error/403_Forbidden");
	}

	public function view( $idx, $banner_target )
	{
		if ( $banner_target == "" || $banner_target == null )
		{
			$banner_target = "ALL";
		}

		$arrayData = $this->dbAdmin->requestImageBanner( $idx, $banner_target )->result_array();
		if ( empty( $arrayData ) )
		{
			echo "<script type=\"text/javascript\">alert(\"잘못된 경로입니다.\");</script>";
			return;
		}
		else
		{
			$arrayData[0]["banner_url"] = URLBASE."/static/upload/banner/image/".$arrayData[0]["banner_url"];
			$this->load->view( "notice/view_imageNotice", array( "arrayData" => $arrayData[0] ) );
		}
	}

	public function preview()
	{
		$this->load->view( 'notice/view_imageNotice_preview' );
	}
}
?>

