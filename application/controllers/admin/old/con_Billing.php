<?php 
	class Con_Billing extends MY_Controller {

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
		$this->load->view('admin/view_Billing');
	}
	
	public function getBillingInfo()
	{
		$searchWhere = $_REQUEST["searchWhere"];
		$kakao_id = $_REQUEST["kakaoId"];
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];

		$billingInfoArray = $this->admModel->requestBillingInfoArray( $searchWhere, $kakao_id, $start_date, $end_date )->result_array();

		echo $this->json_encode2( array( "billingInfoArray"=>$billingInfoArray ) );
	}
	
	public function onRedirectExcelFile()
	{
		$searchWhere = $_REQUEST["searchWhere"];
		$kakao_id = $_REQUEST["kakaoId"];
		$start_date = $_REQUEST["start_date"];
		$end_date = $_REQUEST["end_date"];

		$billingInfoArray = $this->admModel->requestBillingInfoArray( $searchWhere, $kakao_id, $start_date, $end_date )->result_array();
		
		$this->load->library( 'ExcelControll', TRUE );

		$this->excelcontroll->onInitExcelInstance();
 		
		$this->excelcontroll->onSetProperties("HERO", "HERO", "HERO", "HERO", "HERO", "Category");
 		
		$this->excelcontroll->onSelectSheet(0);

		$cellCount = 1;
		$this->excelcontroll->onSetCellData( "A".$cellCount, "기간" );
		$this->excelcontroll->onSetCellData( "B".$cellCount, $start_date." ~ ".$end_date);
		$this->excelcontroll->onSetCellData( "C".$cellCount, "kakaoId" );
		$this->excelcontroll->onSetCellData( "D".$cellCount, " ".$kakao_id." " );

		$cellCount = 3;
		$this->excelcontroll->onSetCellData( "A".$cellCount, "순번" );
		$this->excelcontroll->onSetCellData( "B".$cellCount, "결제코드" );
		$this->excelcontroll->onSetCellData( "C".$cellCount, "ID" );
		$this->excelcontroll->onSetCellData( "D".$cellCount, "Order ID" );
		$this->excelcontroll->onSetCellData( "E".$cellCount, "결제 일자" );
		$this->excelcontroll->onSetCellData( "F".$cellCount, "마켓종류" );
		$this->excelcontroll->onSetCellData( "G".$cellCount, "금액" );
		$this->excelcontroll->onSetCellData( "H".$cellCount, "게임종류" );
		$this->excelcontroll->onSetCellData( "I".$cellCount, "상품" );
		$this->excelcontroll->onSetCellData( "J".$cellCount, "서버지급여부" );
		$this->excelcontroll->onSetCellData( "K".$cellCount, "취소여부" );
			
		for( $index = 0 ; $index < count($billingInfoArray) ; $index ++ )
		{
			$cellCount++;
			$this->excelcontroll->onSetCellData( "A".$cellCount, $index+1 ); // ?쒕쾲
			$this->excelcontroll->onSetCellData( "B".$cellCount, $billingInfoArray[$index]["billing_code"] ); 
			$this->excelcontroll->onSetCellData( "C".$cellCount, " ".$billingInfoArray[$index]["kakao_id"] );
			$this->excelcontroll->onSetCellData( "D".$cellCount, " ".$billingInfoArray[$index]["order_id"] );
			$this->excelcontroll->onSetCellData( "E".$cellCount, $billingInfoArray[$index]["billing_datetime"] );
			$this->excelcontroll->onSetCellData( "F".$cellCount, $billingInfoArray[$index]["platform"] );
			$this->excelcontroll->onSetCellData( "G".$cellCount, " ".$billingInfoArray[$index]["price"] );
			$this->excelcontroll->onSetCellData( "H".$cellCount, $billingInfoArray[$index]["game_type"] );
			$this->excelcontroll->onSetCellData( "I".$cellCount, $billingInfoArray[$index]["billing_type"] );
			$this->excelcontroll->onSetCellData( "J".$cellCount, $billingInfoArray[$index]["is_receive"] );
			$this->excelcontroll->onSetCellData( "K".$cellCount, $billingInfoArray[$index]["is_cancel"] );
		}
 		
		$this->excelcontroll->onRenameSheet('빌링 정보 조회');
		
		$this->excelcontroll->onSelectSheet(0);
		
		if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'testing' ) 
		{
			$this->excelcontroll->onSaveFile("/var/www/html/Hero/excelTemp/billingInfoArray.xls");
		} 
		else if ( ENVIRONMENT == 'development' ) 
		{
			$this->excelcontroll->onSaveFile("/var/www/html/Hero/excelTemp/billingInfoArray.xls");
		}
		
		echo $this->json_encode2(array( "fileName"=>"/excelTemp/billingInfoArray.xls" ));
	}
}
?>


