<?php 
class Con_Mornitoring extends MY_Controller {

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
		$this->load->view('admin/view_Mornitoring');
	}
	
	public function onEggMoneySearch()
	{
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];
		$egg_search = $_REQUEST["egg_search"];
		
		if ( $egg_search == "get" )
		{
			$arrayData = $this->admModel->requestCurrentEggTop( $start_date, $end_date )->result_array();
		}
		else if ( $egg_search == "day" )
		{
			$arrayData = $this->admModel->requestCurrentEggDay( $start_date, $end_date )->result_array();
		}
		
		echo $this->json_encode2( array( "eggMoneySearch"=>$arrayData ) );
	}
	
	public function onStageSearch()
	{
		$start_date = $_REQUEST["start_date"];
		$gamemode = $_REQUEST["gamemode"];
		
		//$arrayData = $this->admModel->requestStageSearch( $start_date, $gamemode )->result_array();
		$arrayData2 = $this->admModel->requestStageSearch2( $start_date, $gamemode )->result_array();
		
		//echo $this->json_encode2( array( "stageSearch"=>$arrayData, "stageSearch2"=>$arrayData2 ) );
		echo $this->json_encode2( array( "stageSearch2"=>$arrayData2 ) );
	}
	
	public function onStageScore()
	{
		$gamemode = $_REQUEST["gamemode"];
		
		$arrayData = $this->admModel->requestStageScore( $gamemode )->result_array();
		
		echo $this->json_encode2( array( "stageScore"=>$arrayData ) );
	}
	
	public function onLevelSearch()
	{
		$start_date = $_REQUEST["start_date"];
		
		$arrayData = $this->admModel->requestLevelSearch( $start_date )->result_array();
		echo $this->json_encode2( array( "levelSearch"=>$arrayData ) );
	}
	
	public function onFenceSearch()
	{
		$start_date = $_REQUEST["start_date"];
		
		$arrayData = $this->admModel->requestFenceSearch( $start_date )->result_array();
		$arrayData2 = $this->admModel->requestFenceSearch2( $start_date )->result_array();
		$arrayData3 = $this->admModel->requestFenceSearch3( $start_date )->result_array();
		
		foreach($arrayData as $key=>$value)
		{
			foreach($value as $kkey=>$vvalue)
			{
				if ($kkey == "grade_string")
				{
					$arrayData[$key]["yesterday"] = (string)(intval($arrayData[$key]["current"]) - intval($arrayData2[$key]["yesterday"]));
					$arrayData[$key]["beforeweek"] = (string)(intval($arrayData[$key]["current"]) - intval($arrayData3[$key]["beforeweek"]));
				}
			}
		}

		echo $this->json_encode2( array( "fenceSearch"=>$arrayData ) );
	}
	
	public function onRankInfoSearch()
	{
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];
		$kakao_id = $_REQUEST["kakao_id"];
		
		$arrayData = $this->admModel->requestRankInfoSearch( $start_date, $end_date, $kakao_id )->result_array();
		echo $this->json_encode2( array( "rankInfoSearch"=>$arrayData ) );
	}
	
	public function onAccessByTimeSearch()
	{
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];
		
		$arrayData = $this->admModel->requestAccessByTimeSearch( $start_date, $end_date )->result_array();
		echo $this->json_encode2( array( "accessByTimeSearch"=>$arrayData ) );
	}
	
	public function onRedirectExcelFile_egg()
	{
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];
		$egg_search = $_REQUEST["egg_search"];
		
		if ( $egg_search == "get" )
		{
			$arrayData = $this->admModel->requestCurrentEggTop( $start_date, $end_date )->result_array();
			
			$this->load->library( 'ExcelControll', TRUE );

			$this->excelcontroll->onInitExcelInstance();
	 		
			$this->excelcontroll->onSetProperties("HERO", "HERO", "HERO", "HERO", "HERO", "Category");
	 		
			$this->excelcontroll->onSelectSheet(0);
	
			$cellCount = 1;
			$this->excelcontroll->onSetCellData( "A".$cellCount, "기간" );
			$this->excelcontroll->onSetCellData( "B".$cellCount, $start_date." ~ ".$end_date);
	
			$cellCount = 3;
			$this->excelcontroll->onSetCellData( "A".$cellCount, "순위" );
			$this->excelcontroll->onSetCellData( "B".$cellCount, "KAKAO ID" );
			$this->excelcontroll->onSetCellData( "C".$cellCount, "에그 (게임머니)" );
			
			for( $index = 0 ; $index < count($arrayData) ; $index ++ )
			{
				$cellCount++;
				$this->excelcontroll->onSetCellData( "A".$cellCount, $arrayData[$index]["rank"]  ); // ?쒕쾲
				$this->excelcontroll->onSetCellData( "B".$cellCount, " ".$arrayData[$index]["kakao_id"] ); 
				$this->excelcontroll->onSetCellData( "C".$cellCount, " ".$arrayData[$index]["egg"] );
			}
	 		
			$this->excelcontroll->onRenameSheet('모니터링_에그(게임머니) Top 500_보유량');
			
			$this->excelcontroll->onSelectSheet(0);
		}
		else if ( $egg_search == "day" )
		{
			$arrayData = $this->admModel->requestCurrentEggDay( $start_date, $end_date )->result_array();
			
			$this->load->library( 'ExcelControll', TRUE );

			$this->excelcontroll->onInitExcelInstance();
	 		
			$this->excelcontroll->onSetProperties("HERO", "HERO", "HERO", "HERO", "HERO", "Category");
	 		
			$this->excelcontroll->onSelectSheet(0);
	
			$cellCount = 1;
			$this->excelcontroll->onSetCellData( "A".$cellCount, "기간" );
			$this->excelcontroll->onSetCellData( "B".$cellCount, $start_date." ~ ".$end_date);
	
			$cellCount = 3;
			$this->excelcontroll->onSetCellData( "A".$cellCount, "순위" );
			$this->excelcontroll->onSetCellData( "B".$cellCount, "KAKAO ID" );
			$this->excelcontroll->onSetCellData( "C".$cellCount, "에그 (게임머니)" );
				
			for( $index = 0 ; $index < count($arrayData) ; $index ++ )
			{
				$cellCount++;
				$this->excelcontroll->onSetCellData( "A".$cellCount, $arrayData[$index]["rank"]  ); // ?쒕쾲
				$this->excelcontroll->onSetCellData( "B".$cellCount, " ".$arrayData[$index]["kakao_id"] ); 
				$this->excelcontroll->onSetCellData( "C".$cellCount, " ".$arrayData[$index]["egg"] );
			}
	 		
			$this->excelcontroll->onRenameSheet('모니터링_에그(게임머니) Top 500_일일 획득량');
			
			$this->excelcontroll->onSelectSheet(0);
		}
		
		if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'testing' ) 
		{
			$this->excelcontroll->onSaveFile("/var/www/html/Hero/excelTemp/mornitoring_egg.xls");
		} 
		else if ( ENVIRONMENT == 'development' ) 
		{
			$this->excelcontroll->onSaveFile("/var/www/html/Hero/excelTemp/mornitoring_egg.xls");
		}
		
		echo $this->json_encode2(array( "fileName"=>"/excelTemp/mornitoring_egg.xls" ));
	}
}
?>


