<?php
class Model_Mail extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_SEL = $this->load->database("koc_mail_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_mail_ins", TRUE);

		/**
		 * 기본적으로 CodeIgniter 는 트랜잭션을 완벽모드(Strict Mode)로 실행합니다.
		 * 완벽모드가 활성화된 상태에서는 여러그룹의 트랜잭션을 실행했을때 단하나라도 실패하게되면 전체는 롤백됩니다.
		 * 만약 완벽모드가 비활성화라면, 여러그룹의 트랜잭션을 실행했을때
		 * 각각의 그룹은 독립적으로 실행되기때문에 각 그룹내에서만 성공여부에따라서 커밋,롤백 하게 됩니다.
		 * 즉 그룹간에는 서로 영향이 없습니다.
		 */
		$this->DB_SEL->trans_strict(FALSE);
		$this->DB_INS->trans_strict(FALSE);

		$this->DB_SEL->query("SET NAMES utf8");
		$this->DB_INS->query("SET NAMES utf8");
	}

	public function __destruct() {
		$this->DB_SEL->close();
		$this->DB_INS->close();
	}

	/************************* 트랜젝션 자동 처리용********************/
	/************************* 주의 : 트랜젝션 자동 처리용 함수와 수동 처리용 함수에 대해 섞어서 사용하면 안됨.********************/
	/**
	 * 트랜젝션 시작
	 */
	public function onStartTransaction()
	{
		$this->DB_INS->trans_start();
	}

	/**
	 * 트랜젝션 종료
	 *start/complete 함수사이에 원하는 수 만큼의 쿼리를 실행하면 전체의 성공여부에따라 함수들이 알아서 커밋 혹은 롤백을 수행합니다.
	*/
	public function onCompleteTransaction()
    {
		$this->DB_INS->trans_complete();
    }

	/************************* 트랜젝션 수동 처리용********************/
	/*
	 * 트랜젝션에 대한 처리를 수동으로 처리할때 사용하는 함수
	*/
	public function onBeginTransaction()
	{
		$this->DB_INS->trans_begin();
	}

	/*
	 * 예외 상황 발생시 처리용
	*/
	public function onRollbackTransaction()
	{
		$this->DB_INS->trans_rollback();
	}

	/*
	 * 로직이 끝나는 시점에서 호출해서 쿼리 수행에 문제가 있었는지 판단하여 롤벡 또는 커밋 시켜주는 함수.
	*/
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

	public function mailReceipt( $pid, $idx )
	{
		$query = "update koc_mail.".MY_Controller::TBL_MAIL." set is_receive = 1, receive_date = now() ";
		$query .= "where idx = '".$idx."' and pid = '".$pid."' and is_receive = 0 and (expire_date >= now() or expire_date is null) ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function mailReceiptAll( $pid, $attach_type, $idx )
	{
		$query = "update koc_mail.".MY_Controller::TBL_MAIL." set is_receive = 1, receive_date = now() ";
		$query .= "where idx in ('".join("', '", $idx)."') and pid = '".$pid."' and attach_type = '".$attach_type."' ";
		$query .= "and is_receive = 0 and (expire_date >= now() or expire_date is null) ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function mailList( $pid )//, $category )
	{
		$query = "select a.idx, a.sid, a.title, if(b.article_value = 'ENERGY_POINTS', 1, if(b.article_value = 'ENERGY_POINTS', 2, if(b.article_type in ('CTIK', 'WTIK', 'BTIK', 'STIK'), 3, 4))) as category, ";
		$query .= "a.attach_type, a.attach_value, a.send_date, a.expire_date, b.article_type, b.article_value, ";
		$query .= "if( a.sid = 0, '', concat( ifnull( c.name, '익명' ), '(', ifnull( c.affiliate_name, '익명' ), ')' )) as sname ";
		$query .= "from koc_mail.".MY_Controller::TBL_MAIL." as a inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.attach_type = b.article_id ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERBASIC." as c on a.sid = c.pid ";
		$query .= "where a.pid = '".$pid."' and a.is_receive = 0 and (a.expire_date >= now() or a.expire_date is null) ";
/*
		if ( $category == "1" )
		{
			$query .= "and b.article_value = 'ENERGY_POINTS' ";
		}
		else if ( $category == "2" )
		{
			$query .= "and b.article_value = 'FRIENDSHIP_POINTS' ";
		}
		else if ( $category == "3" )
		{
			$query .= "and b.article_type in ('CTIK', 'WTIK', 'BTIK', 'STIK') ";
		}
		else if ( $category == "4" )
		{
			$query .= "and b.article_value not in ('ENERGY_POINTS', 'FRIENDSHIP_POINTS') ";
			$query .= "and b.article_type not in ('CTIK', 'WTIK', 'BTIK', 'STIK') ";
		}
*/
		$query .= "order by ifnull(a.expire_date, '9999-12-31 23:59:59') asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminMailList( $pid )
	{
		$query = "select idx, sid, title, attach_type, attach_value, send_date, expire_date, b.article_type, b.article_value, is_receive, expire_date ";
		$query .= "from koc_mail.".MY_Controller::TBL_MAIL." as a inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.attach_type = b.article_id ";
		$query .= "where pid = '".$pid."' order by send_date desc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function mailCount( $pid )
	{
		$query = "select idx from koc_mail.".MY_Controller::TBL_MAIL." where pid = '".$pid."' and is_receive = 0 and (expire_date >= now() or expire_date is null) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function sendMail( $pid, $sid, $title, $type, $value, $expire_term )
	{
		$query = "insert into koc_mail.".MY_Controller::TBL_MAIL." ";
		$query .= "( pid, sid, title, attach_type, attach_value, is_receive, send_date, receive_date, expire_date ) values ( ";
		$query .= "'".$pid."', '".$sid."', '".$title."', '".$type."', '".$value."', 0, now(), null, ";
		if ( $expire_term )
		{
			$query .= "date_add(now(), interval '".$expire_term."' hour) ";
		}
		else
		{
			$query .= "null ";
		}
		$query .= ") ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function getMailInfo( $pid, $idx )
	{
		$query = "select attach_value, article_type, article_value ";
		$query .= "from koc_mail.".MY_Controller::TBL_MAIL." as a inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.attach_type = b.article_id ";
		$query .= "where idx = '".$idx."' and pid = '".$pid."' and is_receive = 0 and (expire_date >= now() or expire_date is null) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function mailValueSummary( $pid, $attach_type, $idx )
	{
		$query = "select sum(attach_value) as attach_value ";
		$query .= "from koc_mail.".MY_Controller::TBL_MAIL." where idx in ('".join("', '", $idx)."') and pid = '".$pid."' and attach_type = '".$attach_type."' ";
		$query .= "and is_receive = 0 and (expire_date >= now() or expire_date is null) group by attach_type ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function mailListReceiptAll( $pid, $attach_type, $idx )
	{
		$query = "select idx ";
		$query .= "from koc_mail.".MY_Controller::TBL_MAIL." where idx in ('".join("', '", $idx)."') and pid = '".$pid."' and attach_type = '".$attach_type."' ";
		$query .= "and is_receive = 0 and (expire_date >= now() or expire_date is null) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}
}
?>
