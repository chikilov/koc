<?php
class Con_ListNotice extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("admin/Model_Admin", "dbAdmin");
	}

	function index()
	{
		$this->load->view("error/403_Forbidden");
	}

	public function view()
	{
		$notiArray = $this->dbAdmin->requestNoticeViewList()->result_array();
		$evtArray = $this->dbAdmin->requestEventViewList()->result_array();
		$this->load->view( "notice/view_listNotice", array( "noticeArray" => $notiArray, "eventArray" => $evtArray ) );
	}

	public function noticeview( $idx )
	{
		$contentArray = $this->dbAdmin->requestNoticeViewDet( $idx )->result_array();
		$this->load->view( "notice/view_viewNotice", array( "contentArray" => $contentArray[0] ) );
	}

	public function eventview( $idx )
	{
		$contentArray = $this->dbAdmin->requestEventViewDet( $idx )->result_array();
		$this->load->view( "notice/view_viewEvent", array( "contentArray" => $contentArray[0] ) );
	}

	public function preview()
	{
		$this->load->view( 'notice/view_viewNotice_preview' );
	}
}
?>

