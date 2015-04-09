<?php
class Model_Login extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_LOGIN = $this->load->database("koc_play_login", TRUE);

		$this->DB_LOGIN->trans_strict(FALSE);

		$this->DB_LOGIN->query("SET NAMES utf8");
	}

	public function __destruct() {
		$this->DB_LOGIN->close();
	}

	public function onStartTransaction()
	{
		$this->DB_LOGIN->trans_start();
	}

	public function onCompleteTransaction()
    {
        $this->DB_LOGIN->trans_complete();
    }

	public function onBeginTransaction()
	{
		$this->DB_LOGIN->trans_begin();
	}

	public function onRollbackTransaction()
	{
		$this->DB_LOGIN->trans_rollback();
	}

	public function onEndTransaction( $result )
	{
		if ($this->DB_LOGIN->trans_status() === FALSE || $result === FALSE)
		{
		    $this->DB_LOGIN->trans_rollback();
		}
		else
		{
		    $this->DB_LOGIN->trans_commit();
		}
	}

	public function requestDupId( $id )
	{
		$query = "select pid from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." where id = '".$id."'";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestJoin( $id, $password, $macaddr )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ( id, password, macaddr, reg_date ) values (";
		$query .= " '".$id."', password( '".$password."' ), '".$macaddr."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->insert_id();
	}

	public function requestGuestJoin( $macaddr, $uuid )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ( macaddr, uuid, reg_date ) values (";
		$query .= " '".$macaddr."', '".$uuid."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->insert_id();
	}

	public function requestDupAffiliateId( $affiliateType, $affiliateId )
	{
		$query = "select pid, name, limit_type, limit_start, limit_end, 0 as helpcount ";
		$query .= "from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "where affiliate_id = '".$affiliateId."' and affiliate_type = '".$affiliateType."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}

	public function requestAffiliateJoin( $macaddr, $uuid, $affiliateType, $affiliateId, $affiliateName, $affiliateEmail, $affiliateProfImg )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ( ";
		$query .= "macaddr, uuid, affiliate_type, affiliate_id, affiliate_name, email, prof_Img, reg_date ) ";
		$query .= "values ( '".$macaddr."', '".$uuid."', '".$affiliateType."', '".$affiliateId."', '".$affiliateName."', ";
		$query .= "'".$affiliateEmail."', '".$affiliateProfImg."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->insert_id();
	}

	public function updateAffiliateNameAccount( $pid, $affiliateName, $affiliateEmail, $affiliateProfImg )
	{
		$query = "update ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "set affiliate_name = '".$affiliateName."', email = '".$affiliateEmail."', prof_img = '".$affiliateProfImg."' ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestAffiliateAccount( $pid )
	{
		$query = "select affiliate_name, email, prof_img from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}

	public function requestLogin( $id, $password )
	{
		$query = "select pid, limit_type, limit_start, limit_end, 0 as helpcount ";
		$query .= "from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "where id = '".$id."' and password = password( '".$password."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}

	public function requestDupMacaddr( $macaddr )
	{
		$query = "select pid, limit_type, limit_start, limit_end, 0 as helpcount ";
		$query .= "from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "where macaddr = '".$macaddr."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}

	public function requestCheckMac( $macaddr )
	{
		$query = "select macaddr from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_RESTRICTMACADDR." ";
		$query .= "where macaddr = '".$macaddr."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function checkDup( $cursession )
	{
		$query = "select cursession from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_CURSESSION." where cursession = '".$cursession."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function updateSession( $pid, $cursession )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_CURSESSION." values ('".$pid."', '".$cursession."') ";
		$query .= "on duplicate key update cursession = '".$cursession."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestSessionCheck( $keyId, $cursession )
	{
		$query = "select pid from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_CURSESSION." where pid = '".$keyId."' and cursession = '".$cursession."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $keyId, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestCheckDupPushKey( $pid, $pushkey )
	{
		$query = "select pid from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_PUSHKEY." ";
		$query .= "where registration_id = '".$pushkey."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestDelDupPushKey( $pid, $pushkey )
	{
		$query = "delete from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_PUSHKEY." ";
		$query .= "where registration_id = '".$pushkey."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestRegPushKey( $pid, $platform, $pushkey )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT_PUSHKEY." ";
		$query .= "values ('".$pid."', '".$platform."', '".$pushkey."') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}
}
?>
