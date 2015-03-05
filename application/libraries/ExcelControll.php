<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * How to use : 
 * 		$this->load->library( 'ExcelControll', TRUE );
 * 		$this->excelcontroll->onInitExcelInstance( "templates/template1.xls" );
 */
class ExcelControll {
	
	private $objPHPExcel;	
			
	public function __construct( $b_debug = TRUE )
	{
		$this->onInit( $b_debug );
	}	
	
	private function onInit( $b_debug )
	{
		if( $b_debug == TRUE )
		{
			error_reporting( E_ALL );
			ini_set( 'display_errors', TRUE );
			ini_set( 'display_startup_errors', TRUE );
		}
		
		date_default_timezone_set( 'Asia/Seoul' );
		
		/** Include PHPExcel */
		require_once 'Classes/PHPExcel.php';
		
		//** PHPExcel_IOFactory */
		require_once 'Classes/PHPExcel/IOFactory.php';
	}
	
	/**
	 * have not excelFileName -> Create new PHPExcel ojbect
	 * have excelFileName -> Excel File Load
	 */
	public function onInitExcelInstance( $excelFileName = "" )
	{
		if( $excelFileName === "" )
		{
			// Create new PHPExcel object
			$this->objPHPExcel = new PHPExcel();
		}
		else {
			$this->onLoadExcel( $excelFileName );
		}
	}
	
	/**
	 * This Function is Set Properties in Excel Document
	 */
	public function onSetProperties( $creator = "", $lastModifiedBy = "", $title = "", $subject = "", $description = "", $category = "" )
	{
		$this->objPHPExcel->getProperties()->setCreator( $creator )
									 ->setLastModifiedBy( $lastModifiedBy )
									 ->setTitle( $title )
									 ->setSubject( $subject )
									 ->setDescription( $description )
									 ->setCategory( $category );	
	}
	
	/**
	 * How to use : onMergeCells('A1:D1');
	 */
	public function onMergeCells($mergeCellRange)
	{
		$this->objPHPExcel->getActiveSheet()->mergeCells($mergeCellRange);
	}
	
	/**
	 * How to use : onInsertImage( 'imageName', 'imageDesc', 'templates/pieChart.png', 'G2' )'
	 */
	public function onInsertImage( $imageName, $imageDesc, $imagePath, $coordinates )
	{
		$objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName($imageName);
        $objDrawing->setDescription($imageDesc);
        $objDrawing->setPath($imagePath);

        $objDrawing->setCoordinates($coordinates);
        $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
	}
	
	/**
	 * This Function is Set Data in cell of specific sheets 
	 */
	public function onSetCellData( $cellNumber, $data )
	{
		$this->objPHPExcel->getActiveSheet()->setcellValue( $cellNumber, $data );
	}
	
	/**
	 * This Function is rename sheetTitle of specific sheets
	 */
	public function onRenameSheet( $sheetTitle )
	{
		$this->objPHPExcel->getActiveSheet()->setTitle( $sheetTitle );
	}
	
	/**
	 * new Sheet Create
	 */
	public function onCreateSheet( $sheetNumber )
	{
		$this->objPHPExcel->createSheet( $sheetNumber );
		$this->objPHPExcel->setActiveSheetIndex( $sheetNumber );
	}
	
	/**
	 * Set active sheet index to the first sheet, so Excel opens this as the first sheet
	 */
	public function onSelectSheet( $sheetNumber )
	{
		$this->objPHPExcel->setActiveSheetIndex( $sheetNumber );
	}
	
	/**
	 * This function is before of specific row to insert row 
	 */
	public function onInsertRowBefore( $rowNumber, $rowCount )
	{
		$this->objPHPExcel->getActiveSheet()->insertNewRowBefore( $rowNumber, $rowCount );
	}	
	
	/**
	 * This function is specific row to remove
	 */
	public function onRemoveRow( $specificRow, $removeRowCount )
	{
		$this->objPHPExcel->getActiveSheet()->removeRow( $specificRow,$removeRowCount );
	}
	
	/**
	 * Redirect output to a client’s web browser (Excel5)
	 */
	public function onRedirectFile( $filename = "test.xls")
	{
		header( 'Content-Type: application/vnd.ms-excel' );
		header( 'Content-Disposition: attachment;filename="'.$filename.'"' );
		header( 'Cache-Control: max-age=0' );
		// If you're serving to IE 9, then the following may be needed
		header( 'Cache-Control: max-age=1' );
		
		// If you're serving to IE over SSL, then the following may be needed
		header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
		header ( 'Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT' ); // always modified
		header ( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
		header ( 'Pragma: public' ); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter( $this->objPHPExcel, 'Excel5' );
		$objWriter->save( 'php://output' );
	}
	
	/**
	 * Excel File Save
	 */
	public function onSaveFile( $saveFileFullPath )
	{
		syslog(LOG_NOTICE, $saveFileFullPath);
		try{
		$objWriter = PHPExcel_IOFactory::createWriter( $this->objPHPExcel, 'Excel5' );
		$objWriter->save( $saveFileFullPath );
		}catch(exception $ex)
		{
			syslog(LOG_NOTICE, $ex->getMessage() );
		}
	}
	
	/**
	 * This Function is load Excel Template Document. 
	 */
	private function onLoadExcel( $excelFileName )
	{
		if ( ! file_exists( $excelFileName ) )
		{
			echo $excelFileName." File is not exists!";
			exit;
		}
		$objReader = PHPExcel_IOFactory::createReader( 'Excel5' );
		$this->objPHPExcel = $objReader->load( $excelFileName );
	}
}

?>
