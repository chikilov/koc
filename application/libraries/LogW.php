<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * How to use :
 * 		$this->load->library( 'LogW', TRUE );
 * 		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "requestData : ".$_POST["data"] );
 */
class LogW {

	private $dirPrefix;

	public function __construct()
	{
		if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'staging' )
		{
			$this->dirPrefix = 'c:\\tmp\\';
		}
		else
		{
			$this->dirPrefix = '/tmp/';
		}
	}

	public function sysLogWrite( $m_LogLevel, $pid, $m_LogMessage, $status = '0200' )
	{
		if ( mb_substr( $_SERVER['REQUEST_URI'], mb_strlen( $_SERVER['REQUEST_URI'] ) - 1, 1 ) == '/' )
	    {
		    $called_name = mb_substr( $_SERVER['REQUEST_URI'], 0, mb_strrpos( $_SERVER['REQUEST_URI'], '/' ) );
	    }
	    else
	    {
    	    $called_name = $_SERVER['REQUEST_URI'];
		}
        $called_name = mb_substr( $called_name, ( mb_strlen( $called_name ) - mb_strrpos( $called_name, '/' ) - 1) * -1 );
        if ( $called_name == "" || $called_name == null )
        {
            $called_name = $_SERVER['REQUEST_URI'];
        }

		if ( is_dir( $this->dirPrefix.date( 'Ymd' ) ) == false )
		{
		    mkdir( $this->dirPrefix.date( 'Ymd' ), 0777, true );
		}
		$logFileName = $this->dirPrefix.date( 'Ymd' ).'\\logfile_'.date( 'Ymd' ).'_'.$pid.'.log';

		if ( file_exists( $logFileName ) == false )
		{
			fopen( $logFileName, 'w' );
		}

		error_log( '\n', 3, $logFileName );
		error_log( date( 'Y-m-d H:i:s' ).' : '.$called_name.' : '.$m_LogMessage, 3, $logFileName);
		error_log( '\n', 3, $logFileName );

		if ( $status != STATUS_API_OK )
		{
			$this->onSysErrLogWriteDb( $pid, $status, $called_name, $m_LogMessage );
		}
	}

    public function onSysErrLogWriteDb( $pid, $status, $called_name, $logcontent )
    {
		$CI =& get_instance();
		$CI->load->model('api/Model_Log', 'dbLog');
     	$CI->dbLog->requestErrLog( $pid, $status, $_POST['data'], $called_name, $logcontent );
    }

	public function admLogWrite( $m_LogLevel, $m_LogMessage )
	{
		if ( ENVIRONMENT != 'production' )
		{
			$logFileName = $this->dirPrefix.'admlogfile_'.date( 'Ymd' ).'.log';

			if ( file_exists( $logFileName ) == false )
			{
				fopen($logFileName, 'w');
			}

			error_log( '\n', 3, $logFileName );
			error_log( date( 'Y-m-d H:i:s' ).' : '.$m_LogMessage, 3, $logFileName );
			error_log( '\n', 3, $logFileName );
		}
	}
}

?>
