<?php
class Model_Template extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_API = $this->load->database("default", TRUE);
		$this->DB_API->query("SET NAMES utf8");
	}

	public function getTemplate( $idx )
	{
		$query = "SELECt title, description, created ";
		$query .= " FROM topic ";
		$query .= " WHERE id = ".$idx;
		
		$this->logw->sysLogWrite( LOG_NOTICE, $query );
		return $this->DB_API->query($query);
	}
}
?>
