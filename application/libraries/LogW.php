<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * How to use :
 * 		$this->load->library( 'ExcelControll', TRUE );
 * 		$this->excelcontroll->onInitExcelInstance( "templates/template1.xls" );
 */
class LogW {

	public function sysLogWrite( $m_LogLevel, $pid, $m_LogMessage )
	{
		if ( mb_substr($_SERVER['REQUEST_URI'], mb_strlen($_SERVER['REQUEST_URI']) - 1, 1) == "/" )
	    {
		    $called_name = mb_substr($_SERVER['REQUEST_URI'], 0, mb_strrpos($_SERVER['REQUEST_URI'], "/"));
	    }
	    else
	    {
    	    $called_name = $_SERVER['REQUEST_URI'];
		}
        $called_name = mb_substr($called_name, -(mb_strlen($called_name) - mb_strrpos($called_name, "/") - 1));
        if ( $called_name == "" || $called_name == null )
        {
            $called_name = $_SERVER['REQUEST_URI'];
        }

		if ( ENVIRONMENT == 'production' )
		{
			// syslog($m_LogLevel, $m_LogMessage);
			if (!is_dir("c:\\tmp\\".date("Ymd"))) {
			    mkdir("c:\\tmp\\".date("Ymd"), 0777, true);
			}
			$logFileName = "c:\\tmp\\".date("Ymd")."\\logfile_".date("Ymd")."_".$pid.".log";

			if (file_exists($logFileName)) {
			}
			else
			{
				fopen($logFileName, "w");
			}

			error_log( "\n", 3, $logFileName);
			error_log( date("Y-m-d H:i:s")." : ".$called_name." : ".$m_LogMessage, 3, $logFileName);
			error_log( "\n", 3, $logFileName);
		}
		else if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'testing' )
		{
			// syslog($m_LogLevel, $m_LogMessage);
			if (!is_dir("/tmp/".date("Ymd"))) {
			    mkdir("/tmp/".date("Ymd"), 0777, true);
			}
			$logFileName = "/tmp/".date("Ymd")."/logfile_".date("Ymd")."_".$pid.".log";

			if (file_exists($logFileName)) {
			}
			else
			{
				fopen($logFileName, "w");
			}

			error_log( "\n", 3, $logFileName);
			error_log( date("Y-m-d H:i:s")." : ".$called_name." : ".$m_LogMessage, 3, $logFileName);
			error_log( "\n", 3, $logFileName);
		}
		else if ( ENVIRONMENT == 'staging' )
		{
			// syslog($m_LogLevel, $m_LogMessage);
			if (!is_dir("c:\\tmp\\".date("Ymd"))) {
			    mkdir("c:\\tmp\\".date("Ymd"), 0777, true);
			}
			$logFileName = "c:\\tmp\\".date("Ymd")."\\logfile_".date("Ymd")."_".$pid.".log";

			if (file_exists($logFileName)) {
			}
			else
			{
				fopen($logFileName, "w");
			}

			error_log( "\n", 3, $logFileName);
			error_log( date("Y-m-d H:i:s")." : ".$called_name." : ".$m_LogMessage, 3, $logFileName);
			error_log( "\n", 3, $logFileName);
		}
	}

	public function admLogWrite( $m_LogLevel, $m_LogMessage )
	{
		if ( ENVIRONMENT == 'production' )
		{
			// Do Not Process
		}
		else if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'testing' )
		{
			// syslog($m_LogLevel, $m_LogMessage);
			$logFileName = "/tmp/admlogfile_".date("Ymd").".log";

			if (file_exists($logFileName)) {
			}
			else
			{
				fopen($logFileName, "w");
			}

			error_log( "\n", 3, $logFileName);
			error_log( date("Y-m-d H:i:s")." : ".$m_LogMessage, 3, $logFileName);
			error_log( "\n", 3, $logFileName);
		}
		else if ( ENVIRONMENT == 'staging' )
		{
			// syslog($m_LogLevel, $m_LogMessage);
			$logFileName = "c:\\tmp\\admlogfile_".date("Ymd").".log";

			if (file_exists($logFileName)) {
			}
			else
			{
				fopen($logFileName, "w");
			}

			error_log( "\n", 3, $logFileName);
			error_log( date("Y-m-d H:i:s")." : ".$m_LogMessage, 3, $logFileName);
			error_log( "\n", 3, $logFileName);
		}
	}
}

?>
