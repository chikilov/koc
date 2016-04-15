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
/*
	public function requestGuestJoin( $macaddr, $uuid )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ( macaddr, uuid, reg_date ) values (";
		$query .= " '".$macaddr."', '".$uuid."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->insert_id();
	}
*/
	public function requestDupAffiliateId( $affiliateType, $affiliateId )
	{
		$query = "select pid, name, limit_type, limit_start, limit_end, 0 as helpcount ";
		$query .= "from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "where affiliate_id = '".$affiliateId."' and affiliate_type = '".$affiliateType."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}

	public function requestAffiliateJoin( $macaddr, $uuid, $affiliateType, $affiliateId, $affiliateName, $affiliateEmail, $affiliateProfImg, $country )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ( ";
		$query .= "macaddr, uuid, country, affiliate_type, affiliate_id, affiliate_name, email, prof_Img, reg_date ) ";
		$query .= "values ( '".$macaddr."', '".$uuid."', '".$country."', '".$affiliateType."', '".$affiliateId."', '".$affiliateName."', ";
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
/*
	public function requestDupMacaddr( $macaddr )
	{
		$query = "select pid, limit_type, limit_start, limit_end, 0 as helpcount ";
		$query .= "from ".$this->DB_LOGIN->database.".".MY_Controller::TBL_ACCOUNT." ";
		$query .= "where macaddr = '".$macaddr."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_LOGIN->query($query);
	}
*/
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

	public function couponVerify1( $coupon_id, $pid )
	{
		$query = "select a.group_id, a.coupon_type, c.reward_type, c.reward_value, b.coupon_user_id, if( a.group_id in ";
		$query .= "( select group_id from ".$this->DB_LOGIN->database.".coupon_detail_info where coupon_user_id = '".$pid."' group by group_id ), 'DUPLICATE', '') as result ";
		$query .= "from ".$this->DB_LOGIN->database.".coupon_basic_info as a left outer join ".$this->DB_LOGIN->database.".coupon_detail_info as b on a.group_id = b.group_id ";
		$query .= "left outer join ".$this->DB_LOGIN->database.".coupon_reward_info as c on a.group_id = c.group_id ";
		$query .= "where a.static_code = '".$coupon_id."' ";
		$query .= "and a.is_valid = 1 and now() between a.start_date and a.end_date limit 1 ";

		return $this->DB_LOGIN->query($query);
	}

	public function couponVerify2( $coupon_id, $pid )
	{
		$query = "select a.group_id, a.coupon_type, c.reward_type, c.reward_value, b.coupon_user_id, if( a.group_id in ";
		$query .= "( select group_id from ".$this->DB_LOGIN->database.".coupon_detail_info where coupon_user_id = '".$pid."' group by group_id ), 'DUPLICATE', '') as result ";
		$query .= "from ".$this->DB_LOGIN->database.".coupon_basic_info as a left outer join ".$this->DB_LOGIN->database.".coupon_detail_info as b on a.group_id = b.group_id ";
		$query .= "left outer join ".$this->DB_LOGIN->database.".coupon_reward_info as c on a.group_id = c.group_id ";
		$query .= "where b.coupon_id = '".$coupon_id."' ";
		$query .= "and a.is_valid = 1 and now() between a.start_date and a.end_date limit 1 ";

		return $this->DB_LOGIN->query($query);
	}

	public function requestCouponStatusUpdate( $pid, $group_id, $coupon_type, $coupon_id )
	{
		if ( $coupon_type == "R" )
		{
			$query = "update ".$this->DB_LOGIN->database.".coupon_detail_info set server_type = '".SERVERGROUP."', coupon_user_id = '".$pid."', coupon_use_datetime = now() where coupon_id = '".$coupon_id."' ";
		}
		else if ( $coupon_type == "S" )
		{
			$query = "insert into ".$this->DB_LOGIN->database.".coupon_detail_info ( group_id, coupon_id, server_type, coupon_user_id, coupon_use_datetime ) values ";
			$query .= "( '".$group_id."', '".SERVERGROUP."', '".$coupon_id."', '".$pid."', now() ) ";
		}

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}
/*
	public function checkDup( $coupon_id )
	{
		$query = "select coupon_id from ".$this->DB_LOGIN->database.".coupon_detail_info where coupon_id = '".$coupon_id."'";

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function insertCoupon( $group_id, $coupon_id )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".coupon_detail_info ( group_id, coupon_id, coupon_user_id ) values ( '".$group_id."', '".$coupon_id."', 0 ) ";

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function insertCouponReward( $group_id, $reward_type, $reward_value )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".coupon_reward_info ( group_id, reward_type, reward_value ) values ";
		$query .= "( '".$group_id."', '".$reward_type."', '".$reward_value."' )";

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function requestCouponList()
	{
		$query = "select d.group_id, d.reg_datetime, d.coupon_count, d.static_code, d.coupon_type, d.is_valid, ";
		$query .= "count(e.coupon_id) as use_count, ";
		$query .= "left(d.start_date, 10) as start_date, left(d.end_date, 10) as end_date, d.group_name, ";
		$query .= "d.reward_type, d.reward_value from ( ";
		$query .= "select a.group_id, a.reg_datetime, a.coupon_count, a.static_code, a.coupon_type, cast(a.is_valid as unsigned) as is_valid, ";
		$query .= "left(a.start_date, 10) as start_date, left(a.end_date, 10) as end_date, a.group_name, ";
		$query .= "group_concat(c.".MY_Controller::COMMON_LANGUAGE_COLUMN.") as reward_type, group_concat(b.reward_value) as reward_value ";
		$query .= "from ".$this->DB_LOGIN->database.".coupon_basic_info as a ";
		$query .= "left outer join ".$this->DB_LOGIN->database.".coupon_reward_info as b on a.group_id = b.group_id ";
		$query .= "left outer join koc_ref.text as c on concat('NG_ARTICLE_', b.reward_type) = c.id ";
		$query .= "group by a.group_id, a.reg_datetime, a.coupon_count, a.static_code, a.coupon_type, a.is_valid, a.start_date, a.end_date, a.group_name ";
		$query .= ") as d left outer join (select group_id, coupon_id from ".$this->DB_LOGIN->database.".coupon_detail_info ";
		$query .= "where coupon_user_id > 0 and coupon_user_id is not null ) as e on d.group_id = e.group_id ";
		$query .= "group by d.group_id ";

		return $this->DB_LOGIN->query($query);
	}

	public function requestCouponListView( $group_id )
	{
		$query = "select coupon_id from ".$this->DB_LOGIN->database.".coupon_detail_info where group_id = '".$group_id."' ";

		return $this->DB_LOGIN->query($query);
	}

	public function requestCouponStatusChange( $group_id, $is_valid )
	{
		$query = "update ".$this->DB_LOGIN->database.".coupon_basic_info set is_valid = ".$is_valid." where group_id = '".$group_id."' ";

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->affected_rows();
	}

	public function insertCouponBasic( $group_name, $coupon_type, $coupon_count, $use_start_date, $use_end_date, $static_code )
	{
		$query = "insert into ".$this->DB_LOGIN->database.".coupon_basic_info ";
		$query .= "( group_name, coupon_type, coupon_count, static_code, start_date, end_date, is_valid, reg_datetime, reg_id ) ";
		$query .= "values ( '".$group_name."', '".$coupon_type."', '".$coupon_count."', '".$static_code."', '".$use_start_date."', '".$use_end_date."', ";
		$query .= "1, now(), '".$this->session->userdata('userId_session')."' ) ";

		$this->DB_LOGIN->query($query);
		return $this->DB_LOGIN->insert_id();
	}

	public function requestCouponSearch( $coupon_id, $searchParam, $searchValue )
	{
		$query = "select b.reg_datetime, b.start_date, b.end_date, if(a.coupon_use_datetime is null, false, true) as is_use, a.coupon_use_datetime, ";
		$query .= "a.coupon_user_id, a.coupon_id, b.group_name, group_concat(d.kr) as reward_type, group_concat(c.reward_value) as reward_value ";
		$query .= "from ".$this->DB_LOGIN->database.".coupon_detail_info as a inner join ".$this->DB_LOGIN->database.".coupon_basic_info as b on a.group_id = b.group_id ";
		$query .= "left outer join ".$this->DB_LOGIN->database.".coupon_reward_info as c on b.group_id = c.group_id ";
		$query .= "left outer join koc_ref.text as d on concat('NG_ARTICLE_', c.reward_type) = d.id ";
		$query .= "where 1 = 1 ";
		if ( $coupon_id )
		{
			$query .= "and a.coupon_id = '".$coupon_id."' ";
		}
		if ( $searchParam && $searchValue )
		{
			if ( $searchParam == "pid" )
			{
				$query .= "and a.coupon_user_id = '".$searchValue."' ";
			}
			else if ( $searchParam == "id" )
			{
				$query .= "and a.coupon_user_id = ( select pid from koc_play.player_basic where id = '".$searchValue."' ) ";
			}
			else
			{
				$query .= "and a.coupon_user_id = ( select pid from koc_play.player_basic where ( name = '".$searchValue."' or affiliate_name = '".$searchValue."' ) ) ";
			}
		}
		$query .= "group by b.reg_datetime, b.start_date, b.end_date, a.coupon_use_datetime, a.coupon_user_id, a.coupon_id, b.group_name ";

		return $this->DB_LOGIN->query($query);
	}
*/
}
?>
