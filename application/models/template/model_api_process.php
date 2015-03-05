<?php
class Model_Api_Process extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_API = $this->load->database("default", TRUE);
		$this->DB_API->query("SET NAMES utf8");
	}

	public function getTemplate( $idx )
	{
		$query = "SELECT MESSAGE_ID,SENDER_KAKAO_ID,RECEIVER_KAKAO_ID,MESSAGE_TYPE,MESSAGE_VALUE,MESSAGE_TEXT,IS_RECEIVE,SEND_DATE";
		$query .= " FROM PLAYER_MESSAGE_INFO ";
		$query .= " WHERE MESSAGE_ID = ".$idx;

		$this->logw->sysLogWrite( LOG_NOTICE, $query );
		return $this->DB_API->query($query);
	}
}
?>
