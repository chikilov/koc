<?php
class Con_NoticeManage extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/Model_Admin', "dbAdmin");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $subnavi = 0 )
	{
		$data = array( "subnavi" => $subnavi );
		$this->load->view( 'admin/view_NoticeManage_'.$subnavi, $data );
	}

	public function form( $subnavi = 0, $idx = 0 )
	{
		$data = array( "subnavi" => $subnavi, "idx" => $idx );
		$this->load->view( 'admin/view_NoticeManage_form_'.$subnavi, $data );
	}

	public function del( $subnavi, $idx )
	{
		if ( $subnavi == 0 )
		{
			$tblName = "notice_list";
		}
		else if ( $subnavi == 1 )
		{
			$tblName = "event_list";
		}
		else if ( $subnavi == 2 )
		{
			$tblName = "banner_list";
		}

		if ( $this->dbAdmin->delCommon( $tblName, $idx ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function uploadImage( $uploadtype )
	{
		if ( $uploadtype == 0 )
		{
			$path = "notice/content";
			$field = "upload_image";
		}
		else if ( $uploadtype == 1 )
		{
			$path = "notice/thumbnail";
			$field = "thumbnail";
		}
		else if ( $uploadtype == 2 )
		{
			$path = "event/content";
			$field = "upload_image";
		}
		else if ( $uploadtype == 3 )
		{
			$path = "event/thumbnail";
			$field = "thumbnail";
		}
		else if ( $uploadtype == 4 )
		{
			$path = "banner/image";
			$field = "banner_url";
		}

		if ( $path != "" && $path != null )
		{
			$config["upload_path"] = "static/upload/".$path."/";
			$config["allowed_types"] = "gif|jpg|png";
			$config["max_size"]	= "0";
			$config["max_width"] = "0";
			$config["max_height"] = "0";

			$this->load->library("upload", $config);
			$this->upload->initialize($config);
			$this->upload->display_errors();

	   		if ( $this->upload->do_upload($field) )
	        {
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = $this->upload->data();
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestNoticeListInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$notice_title = $decoded["notice_title"];
		$thumbnail = $decoded["thumbnail"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$notice_target = $decoded["notice_target"];
		$content_type = $decoded["content_type"];
		$content_text = $decoded["content_text"];
		$content_image = $decoded["content_image"];
		$content_link = $decoded["content_link"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestNoticeListInsert( $notice_title, $thumbnail, $start_date, $end_date, $notice_target, $content_type, $content_text, $content_image, $content_link );

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function requestNoticeListUpdate()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$notice_title = $decoded["notice_title"];
		$thumbnail = $decoded["thumbnail"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$notice_target = $decoded["notice_target"];
		$content_type = $decoded["content_type"];
		$content_text = $decoded["content_text"];
		$content_image = $decoded["content_image"];
		$content_link = $decoded["content_link"];
		$idx = $decoded["idx"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestNoticeListUpdate( $idx, $notice_title, $thumbnail, $start_date, $end_date, $notice_target, $content_type, $content_text, $content_image, $content_link );

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function requestNoticeList()
	{
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestNoticeList()->result_array();

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestNoticeListModi()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$idx = $decoded["idx"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestNoticeListModi( $idx )->result_array();

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestNoticeListOrderChange()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		foreach ( $decoded as $row )
		{
			$this->dbAdmin->updateNoticeListOrder( $row["idx"], $row["order_no"] );
		}

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = null;

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

		public function requestEventListInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$event_title = $decoded["event_title"];
		$thumbnail = $decoded["thumbnail"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$event_target = $decoded["event_target"];
		$content_type = $decoded["content_type"];
		$content_text = $decoded["content_text"];
		$content_image = $decoded["content_image"];
		$content_link = $decoded["content_link"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestEventListInsert( $event_title, $thumbnail, $start_date, $end_date, $event_target, $content_type, $content_text, $content_image, $content_link );

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function requestEventListUpdate()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$event_title = $decoded["event_title"];
		$thumbnail = $decoded["thumbnail"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$event_target = $decoded["event_target"];
		$content_type = $decoded["content_type"];
		$content_text = $decoded["content_text"];
		$content_image = $decoded["content_image"];
		$content_link = $decoded["content_link"];
		$idx = $decoded["idx"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestEventListUpdate( $idx, $event_title, $thumbnail, $start_date, $end_date, $event_target, $content_type, $content_text, $content_image, $content_link );

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}

	public function requestEventList()
	{
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestEventList()->result_array();

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestEventListModi()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$idx = $decoded["idx"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestEventListModi( $idx )->result_array();

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestEventListOrderChange()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		foreach ( $decoded as $row )
		{
			$this->dbAdmin->updateEventListOrder( $row["idx"], $row["order_no"] );
		}

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = null;

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestBannerList()
	{
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestBannerList()->result_array();

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestBannerListInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );
		$banner_url = $decoded["banner_url"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$banner_target = $decoded["banner_target"];
		$banner_link = $decoded["banner_link"];

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult = $this->dbAdmin->requestBannerListInsert( $start_date, $end_date, $banner_target, $banner_url, $banner_link );

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST ["data"] );
	}
}
?>

