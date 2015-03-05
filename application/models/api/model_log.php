<?php
class Model_Log extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_INS = $this->load->database("koc_play_log", TRUE);

		$this->DB_INS->trans_strict(FALSE);

		$this->DB_INS->query("SET NAMES utf8");
	}

	public function __destruct() {
		$this->DB_INS->close();
	}

	public function onStartTransaction()
	{
		$this->DB_INS->trans_start();
	}

	public function onCompleteTransaction()
    {
        $this->DB_INS->trans_complete();
    }

	public function onBeginTransaction()
	{
		$this->DB_INS->trans_begin();
	}

	public function onRollbackTransaction()
	{
		$this->DB_INS->trans_rollback();
	}

	public function onEndTransaction( $result )
	{
		if ($this->DB_INS->trans_status() === FALSE || $result === FALSE)
		{
		    $this->DB_INS->trans_rollback();
		}
		else
		{
		    $this->DB_INS->trans_commit();
		}
	}

	public function requestLog( $pid, $logtype, $logcontent )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERLOG." ( pid, logtype, logcontent, log_datetime ) values ";
		$query .= "( '".$pid."', '".$logtype."', '".$logcontent."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}
}
?>
