<?php
class Model_Log extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_LOG = $this->load->database("koc_play_log", TRUE);

		$this->DB_LOG->trans_strict(FALSE);

		$this->DB_LOG->query("SET NAMES utf8");

		$this->load->library( 'cimongo/Cimongo', TRUE );
	}

	public function __destruct() {
		$this->DB_LOG->close();
	}

	public function onStartTransaction()
	{
		$this->DB_LOG->trans_start();
	}

	public function onCompleteTransaction()
    {
        $this->DB_LOG->trans_complete();
    }

	public function onBeginTransaction()
	{
		$this->DB_LOG->trans_begin();
	}

	public function onRollbackTransaction()
	{
		$this->DB_LOG->trans_rollback();
	}

	public function onEndTransaction( $result )
	{
		if ($this->DB_LOG->trans_status() === FALSE || $result === FALSE)
		{
		    $this->DB_LOG->trans_rollback();
		}
		else
		{
		    $this->DB_LOG->trans_commit();
		}
	}

	public function requestLog( $pid, $logtype, $logcontent )
	{
		$query = "insert into ".$this->DB_LOG->database.".".MY_Controller::TBL_PLAYERLOG.SERVERGROUP." ( pid, logtype, logcontent, log_datetime ) values ";
		$query .= "( '".$pid."', '".$logtype."', '".$logcontent."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOG->query($query);
		return $this->DB_LOG->affected_rows();
	}

	public function requestLoggingUseAssets( $pid, $use_gubun, $point_type, $point_value, $loggingText )
	{
		$query = "insert into ".$this->DB_LOG->database.".".MY_Controller::TBL_PLAYERASSETLOG.SERVERGROUP." ";
		$query .= "( pid, usetype, asset_type, asset_value, description, reg_datetime ) ";
		$query .= "values ( '".$pid."', '".$use_gubun."', '".$point_type."', '".$point_value."', '".$loggingText."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_LOG->query($query);
	}

	public function requestErrLog( $pid, $status, $request, $logtype, $logcontent )
	{
		if ( ENVIRONMENT != "product" )
		{
			$this->cimongo->insert(
				MY_Controller::TBL_PLAYERERRLOG.SERVERGROUP,
				array(
					"pid" => $pid,
					"status" => $status,
					"request" => $request,
					"logtype" => $logtype,
					"logcontent" => $logcontent,
					"log_datetime" => new MongoDate()
				)
			);
		}

		$query = "insert into ".$this->DB_LOG->database.".".MY_Controller::TBL_PLAYERERRLOG.SERVERGROUP." ( pid, status, logtype, request, logcontent, log_date ) values ";
		$query .= "( '".$pid."', '".$status."', '".$logtype."', '".$request."', '".$logcontent."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOG->query($query);
		return $this->DB_LOG->affected_rows();
	}
}
?>
