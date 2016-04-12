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
		$this->dirPrefix = LOGROOT;
		define('DEFAULTKEY', 'dnflahen20djrspdhrmfoa20djreoqkr');
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
			$old = umask(0);
			mkdir( $this->dirPrefix.date( 'Ymd' ), 0777, true );
			umask($old);
		}
		$logFileName = $this->dirPrefix.date( 'Ymd' ).'/logfile_'.date( 'Ymd' ).'_'.$pid.'.log';

		if ( file_exists( $logFileName ) == false )
		{
			fopen( $logFileName, 'w' );
			$old = umask(0);
			chmod($logFileName, 0777);
			umask($old);
		}

		error_log( date( 'Y-m-d H:i:s' ).' : '.$called_name.' : '.$m_LogMessage.PHP_EOL, 3, $logFileName);

		if ( $status != STATUS_API_OK )
		{
			$this->onSysErrLogWriteDb( $pid, $status, $called_name, $m_LogMessage );
		}
	}

    public function onSysErrLogWriteDb( $pid, $status, $called_name, $logcontent )
    {
		$CI =& get_instance();
		$CI->load->model('api/Model_Log', 'dbLog');
		if ( array_key_exists( "data", $_POST ) )
		{
			$dataString = $this->NG_DECRYPT( $_POST["data"] );
		}
		else
		{
			$dataString = 'no data';
		}
     	$CI->dbLog->requestErrLog( $pid, $status, $dataString, $called_name, $logcontent );
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

			error_log( date( 'Y-m-d H:i:s' ).' : '.$m_LogMessage.PHP_EOL, 3, $logFileName );
		}
	}

	public function NG_ENCRYPT( $string, $key = NULL )
	{
		$is_enc = true;
	    if ( array_key_exists( "HTTP_REFERER", $_SERVER ) )
		{
			if ( strpos( $_SERVER["HTTP_REFERER"], "/pages/admin/" ) || strpos($_SERVER["HTTP_REFERER"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists( "REQUEST_URI", $_SERVER ) )
		{
			if ( strpos( $_SERVER["REQUEST_URI"], "/pages/admin/" ) || strpos($_SERVER["REQUEST_URI"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists("HTTP_USER_AGENT", $_SERVER ) )
		{
			if ( $_SERVER["HTTP_USER_AGENT"] == "RPT-HTTPClient/0.3-3E" || $_SERVER["HTTP_USER_AGENT"] == "Apache-HttpClient/4.2.6 (java 1.5)" || $_SERVER["HTTP_USER_AGENT"] == "curl/7.43.0")
			{
				$is_enc = false;
			}
		}

		if ( array_key_exists("HTTP_POSTMAN_TOKEN", $_SERVER) )
		{
			$is_enc = false;
		}

		if ( $is_enc )
		{
			$key = $key == NULL ? DEFAULTKEY : $key;
			return base64_encode(openssl_encrypt($string, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
		}
		else
		{
			return $string;
		}
    }

    public function NG_DECRYPT( $encrypted_string, $key = NULL )
    {
		$is_enc = true;
	    if ( array_key_exists( "HTTP_REFERER", $_SERVER ) )
		{
			if ( strpos( $_SERVER["HTTP_REFERER"], "/pages/admin/" ) || strpos($_SERVER["HTTP_REFERER"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists( "REQUEST_URI", $_SERVER ) )
		{
			if ( strpos( $_SERVER["REQUEST_URI"], "/pages/admin/" ) || strpos($_SERVER["REQUEST_URI"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists("HTTP_USER_AGENT", $_SERVER ) )
		{
			if ( $_SERVER["HTTP_USER_AGENT"] == "RPT-HTTPClient/0.3-3E" || $_SERVER["HTTP_USER_AGENT"] == "Apache-HttpClient/4.2.6 (java 1.5)" || $_SERVER["HTTP_USER_AGENT"] == "curl/7.43.0" )
			{
				$is_enc = false;
			}
		}

		if ( array_key_exists("HTTP_POSTMAN_TOKEN", $_SERVER) )
		{
			$is_enc = false;
		}

		if ( $is_enc )
		{
			$key = $key == NULL ? DEFAULTKEY : $key;
			return openssl_decrypt(base64_decode($encrypted_string), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
	    }
	    else
	    {
		    return $encrypted_string;
	    }
    }
}

?>
