<?php
class Con_GameInfo extends MY_Controller {

	function __construct(){
		parent::__construct();		
	}
	
	function index()
	{
		$this->load->view("error/403_Forbidden");
	}
	
	public function view()
	{
		$this->load->view("admin/view_GameInfo");
	}
	
	public function getStageInfo()
	{
		$kakao_id = $_REQUEST["kakaoId"];
		$gamemode = $_REQUEST["gamemode"];

		$maxStageInfo = $this->admModel->requestMaxStage( $kakao_id, $gamemode )->result_array();
		$maxStageInfo = $maxStageInfo[0];

		$clearStageInfoArray = $this->admModel->requestClearStageInfo( $kakao_id, $gamemode )->result_array();

		echo $this->json_encode2( array( "maxStageInfo"=>$maxStageInfo, "clearStageInfoArray"=>$clearStageInfoArray ) );
	}

	public function getPlantInfo()
	{
		$kakao_id = $_REQUEST["kakaoId"];
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];

		$maxPlantInfo = $this->admModel->requestMaxPlantInfo( $kakao_id )->result_array();
		$maxPlantInfo = $maxPlantInfo[0];
		
		$harvestInfoArray = $this->admModel->requestHarvestInfoArray( $kakao_id, $start_date, $end_date )->result_array();

		echo $this->json_encode2( array( "maxPlantInfo"=>$maxPlantInfo, "harvestInfoArray"=>$harvestInfoArray ) );
	}
	
	public function onSearchDailyLog()
	{
		$kakao_id = $_REQUEST["kakaoId"];
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];
		
		$dailyLogArray = $this->admModel->requestEveryLog( $kakao_id, $start_date, $end_date )->result_array();
		echo $this->json_encode2( array( "dailyLogArray"=>$dailyLogArray ) );
	}
}
?>


