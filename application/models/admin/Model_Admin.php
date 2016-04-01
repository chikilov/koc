<?php
class Model_Admin extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_SEL = $this->load->database("koc_admin_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_admin_ins", TRUE);
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
	public function onEndTransaction()
	{
		if ($this->DB_INS->trans_status() === FALSE)
		{
			$this->DB_INS->trans_rollback();
		}
		else
		{
			$this->DB_INS->trans_commit();
		}
	}

//for ADMIN
	public function requestLogin( $admin_id, $admin_pw )
	{
		$query = "select admin_id from koc_admin.admin_master where admin_id = '".$admin_id."' and admin_pw = '".$admin_pw."' ";

		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestDisEventList()
	{
		$query = "select idx, start_date, end_date, evt_category, b.".MY_Controller::COMMON_LANGUAGE_COLUMN." as evt_target, ";
		$query .= "evt_paytype, evt_value, is_valid, reg_date ";
		$query .= "from koc_admin.event_discount as a left outer join koc_ref.text as b on concat( 'PRODUCT_NAME_', a.evt_target ) = b.id ";

		return $this->DB_SEL->query($query);
	}

	public function requestDisEventInsert( $start_date, $end_date, $evt_category, $evt_target, $evt_paytype, $evt_value, $evt_reason )
	{
		$query = "insert into koc_admin.event_discount ";
		$query .= "( start_date, end_date, evt_category, evt_target, evt_paytype, evt_value, evt_reason, is_valid, reg_date, reg_id ) values ";
		$query .= "('".$start_date."', '".$end_date."', '".$evt_category."', '".$evt_target."', '".$evt_paytype."', '".$evt_value."', '".$evt_reason."', ";
		$query .= "1, now(), '".$this->session->userdata('userId_session')."') ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestDisEventStop( $idx )
	{
		$query = "update koc_admin.event_discount set is_valid = 0, upd_date = now(), upd_id = '".$this->session->userdata('userId_session')."' ";
		$query .= "where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAccEventList()
	{
		$query = "select a.idx, a.start_date, a.end_date, case when a.evt_category = 'ASST' then '자원' when a.evt_category = 'BCPC' then '백팩' ";
		$query .= "when a.evt_category = 'BTIK' then '백팩뽑기권' when a.evt_category = 'CHAR' then '캐릭터' ";
		$query .= "when a.evt_category = 'CTIK' then '캐릭터뽑기권' when a.evt_category = 'OPRT' then '오퍼레이터' ";
		$query .= "when a.evt_category = 'PILT' then '파일럿' when a.evt_category = 'SKIL' then '스킬' ";
		$query .= "when a.evt_category = 'STIK' then '스킬뽑기권' when a.evt_category = 'WEPN' then '무기' ";
		$query .= "when a.evt_category = 'WTIK' then '무기뽑기권' end as evt_category, ";
		$query .= "concat( if(a.evt_category = 'CHAR' or a.evt_category = 'WEPN' or a.evt_category = 'SKIL' or a.evt_category = 'BCPC', ";
		$query .= "concat('★', if(a.evt_category = 'CHAR', d.grade, c.grade)), ''), b.".MY_Controller::COMMON_LANGUAGE_COLUMN." ) as evt_target, ";
		$query .= "a.evt_value, a.is_valid, a.reg_date ";
		$query .= "from koc_admin.event_access as a left outer join koc_ref.text as b on concat( 'NG_ARTICLE_', a.evt_target ) = b.id ";
		$query .= "left outer join koc_ref.item as c on a.evt_target = c.id ";
		$query .= "left outer join koc_ref.ref_character as d on a.evt_target = d.id ";

		return $this->DB_SEL->query($query);
	}

	public function requestAccEventInsert( $start_date, $end_date, $evt_category, $evt_target, $evt_value, $evt_reason )
	{
		$query = "insert into koc_admin.event_access ";
		$query .= "( start_date, end_date, evt_category, evt_target, evt_value, evt_reason, is_valid, reg_date, reg_id ) values ";
		$query .= "('".$start_date."', '".$end_date."', '".$evt_category."', '".$evt_target."', '".$evt_value."', '".$evt_reason."', ";
		$query .= "1, now(), '') ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAccEventStop( $idx )
	{
		$query = "update koc_admin.event_access set is_valid = 0, upd_date = now(), upd_id = '".$this->session->userdata('userId_session')."' ";
		$query .= "where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestPresentEventInsert( $evt_category, $evt_target, $evt_value, $evt_reason )
	{
		$query = "insert into koc_admin.event_present ( evt_category, evt_target, evt_value, evt_reason, reg_date, reg_id ) values ";
		$query .= "( '".$evt_category."', '".$evt_target."', '".$evt_value."', '".$evt_reason."', now(), '".$this->session->userdata('userId_session')."' ) ";

		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestPresentEventSubInsert( $idx, $arrEvtId )
	{
		$query = "insert into koc_admin.event_present_sub ( idx, pid, send_date ) ";
		$query .= "select '".$idx."', '".implode( "', now() union select '".$idx."', '", $arrEvtId )."', now() ";

		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestPresentEventList()
	{
		$query = "select a.idx, case when a.evt_category = 'ASST' then '자원' when a.evt_category = 'BCPC' then '백팩' ";
		$query .= "when a.evt_category = 'BTIK' then '백팩뽑기권' when a.evt_category = 'CHAR' then '캐릭터' ";
		$query .= "when a.evt_category = 'CTIK' then '캐릭터뽑기권' when a.evt_category = 'OPRT' then '오퍼레이터' ";
		$query .= "when a.evt_category = 'PILT' then '파일럿' when a.evt_category = 'SKIL' then '스킬' ";
		$query .= "when a.evt_category = 'STIK' then '스킬뽑기권' when a.evt_category = 'WEPN' then '무기' ";
		$query .= "when a.evt_category = 'WTIK' then '무기뽑기권' end as evt_category, ";
		$query .= "concat( if(a.evt_category = 'CHAR' or a.evt_category = 'WEPN' or a.evt_category = 'SKIL' or a.evt_category = 'BCPC', ";
		$query .= "concat('★', if(a.evt_category = 'CHAR', d.grade, c.grade)), ''), b.".MY_Controller::COMMON_LANGUAGE_COLUMN." ) as evt_target, ";
		$query .= "a.evt_value, a.evt_reason, a.reg_date ";
		$query .= "from koc_admin.event_present as a left outer join koc_ref.text as b on concat( 'NG_ARTICLE_', a.evt_target ) = b.id ";
		$query .= "left outer join koc_ref.item as c on a.evt_target = c.id ";
		$query .= "left outer join koc_ref.ref_character as d on a.evt_target = d.id ";

		return $this->DB_SEL->query($query);
	}

	public function requestPresentEventSubList( $evt_id )
	{
		$query = "select pid from koc_admin.event_present_sub where idx = '".$evt_id."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestNoticeList()
	{
		$query = "select idx, order_no, notice_title, start_date, end_date from koc_admin.notice_list where is_valid = 1 order by order_no asc ";

		return $this->DB_SEL->query($query);
	}

	public function requestNoticeViewList()
	{
		$query = "select idx, notice_title, content_type, content_text, content_image, content_link ";
		$query .= "from koc_admin.notice_list where is_valid = 1 and now() between start_date and end_date ";
		$query .= "order by order_no asc ";

		return $this->DB_SEL->query($query);
	}

	public function requestNoticeViewDet( $idx )
	{
		$query = "select a.idx, a.notice_title, a.thumbnail, a.start_date, a.end_date, a.notice_target, a.content_type, ";
		$query .= "a.content_text, a.content_image, a.content_link, ";
		$query .= "concat(sum(if(b.order_no <= a.order_no, 1, 0)), ' / ', count(b.idx)) as rec_position, ";
		$query .= "max(if(b.order_no < a.order_no, b.idx, null)) as prev_idx, min(if(b.order_no > a.order_no, b.idx, null)) as next_idx ";
		$query .= "from koc_admin.notice_list as a, ";
		$query .= "( select idx, order_no from koc_admin.notice_list where is_valid = 1 and now() between start_date and end_date order by order_no asc ) as b ";
		$query .= "where a.idx = '".$idx."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestNoticeListInsert(
		$notice_title, $thumbnail, $start_date, $end_date, $notice_target, $content_type, $content_text, $content_image, $content_link
	)
	{
		$query = "insert into koc_admin.notice_list ";
		$query .= "( order_no, notice_title, thumbnail, start_date, end_date, notice_target, content_type, content_text, ";
		$query .= "content_image, content_link, reg_datetime, reg_id, is_valid ) ";
		$query .= "select ifnull(max(order_no), 0) + 1, '".$notice_title."', '".$thumbnail."', '".$start_date."', '".$end_date."', ";
		$query .= "'".$notice_target."', '".$content_type."', '".$content_text."', '".$content_image."', '".$content_link."', ";
		$query .= "now(), '".$this->session->userdata('userId_session')."', 1 ";
		$query .= "from koc_admin.notice_list ";

		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestNoticeListUpdate(
		$idx, $notice_title, $thumbnail, $start_date, $end_date, $notice_target, $content_type, $content_text, $content_image, $content_link
	)
	{
		$query = "update koc_admin.notice_list set notice_title = '".$notice_title."', thumbnail = '".$thumbnail."', start_date = '".$start_date."', ";
		$query .= "end_date = '".$end_date."', notice_target = '".$notice_target."', content_type = '".$content_type."', content_text = '".$content_text."', ";
		$query .= "content_image = '".$content_image."', content_link = '".$content_link."' where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateNoticeListOrder( $idx, $order_no )
	{
		$query = "update koc_admin.notice_list set order_no = '".$order_no."' where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestNoticeListModi( $idx )
	{
		$query = "select idx, notice_title, thumbnail, start_date, end_date, notice_target, content_type, content_text, content_image, content_link ";
		$query .= "from koc_admin.notice_list where idx = '".$idx."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestEventList()
	{
		$query = "select idx, order_no, event_title, start_date, end_date from koc_admin.event_list where is_valid = 1 order by order_no asc ";

		return $this->DB_SEL->query($query);
	}

	public function requestEventViewList()
	{
		$query = "select idx, thumbnail, content_type, content_text, content_image, content_link ";
		$query .= "from koc_admin.event_list where is_valid = 1 and now() between start_date and end_date ";
		$query .= "order by order_no asc ";

		return $this->DB_SEL->query($query);
	}

	public function requestEventViewDet( $idx )
	{
		$query = "select a.idx, a.event_title, a.thumbnail, a.start_date, a.end_date, a.event_target, a.content_type, ";
		$query .= "a.content_text, a.content_image, a.content_link, ";
		$query .= "concat(sum(if(b.order_no <= a.order_no, 1, 0)), ' / ', count(b.idx)) as rec_position, ";
		$query .= "max(if(b.order_no < a.order_no, b.idx, null)) as prev_idx, min(if(b.order_no > a.order_no, b.idx, null)) as next_idx ";
		$query .= "from koc_admin.event_list as a, ";
		$query .= "( select idx, order_no from koc_admin.event_list where is_valid = 1 and now() between start_date and end_date order by order_no asc ) as b ";
		$query .= "where a.idx = '".$idx."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestEventListInsert(
		$event_title, $thumbnail, $start_date, $end_date, $event_target, $content_type, $content_text, $content_image, $content_link
	)
	{
		$query = "insert into koc_admin.event_list ";
		$query .= "( order_no, event_title, thumbnail, start_date, end_date, event_target, content_type, content_text, content_image, ";
		$query .= "content_link, reg_datetime, reg_id, is_valid ) ";
		$query .= "select ifnull(max(order_no), 0) + 1, '".$event_title."', '".$thumbnail."', '".$start_date."', '".$end_date."', ";
		$query .= "'".$event_target."', '".$content_type."', '".$content_text."', '".$content_image."', '".$content_link."', ";
		$query .= "now(), '".$this->session->userdata('userId_session')."', 1 ";
		$query .= "from koc_admin.event_list ";

		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestEventListUpdate(
		$idx, $event_title, $thumbnail, $start_date, $end_date, $event_target, $content_type, $content_text, $content_image, $content_link
	)
	{
		$query = "update koc_admin.event_list set event_title = '".$event_title."', thumbnail = '".$thumbnail."', start_date = '".$start_date."', ";
		$query .= "end_date = '".$end_date."', event_target = '".$event_target."', content_type = '".$content_type."', content_text = '".$content_text."', ";
		$query .= "content_image = '".$content_image."', content_link = '".$content_link."' where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateEventListOrder( $idx, $order_no )
	{
		$query = "update koc_admin.event_list set order_no = '".$order_no."' where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEventListModi( $idx )
	{
		$query = "select idx, event_title, thumbnail, start_date, end_date, event_target, content_type, content_text, content_image, content_link ";
		$query .= "from koc_admin.event_list where idx = '".$idx."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestBannerList()
	{
		$query = "select idx, reg_datetime, start_date, end_date, banner_url, banner_link from koc_admin.banner_list where is_valid = 1 ";

		return $this->DB_SEL->query($query);
	}

	public function requestBannerListInsert( $start_date, $end_date, $banner_target, $banner_url, $banner_link )
	{
		$query = "insert into koc_admin.banner_list ( start_date, end_date, banner_target, banner_url, banner_link, reg_datetime, reg_id, is_valid ) values ";
		$query .= "( '".$start_date."', '".$end_date."', '".$banner_target."', '".$banner_url."', '".$banner_link."', ";
		$query .= "now(), '".$this->session->userdata('userId_session')."', 1 ) ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function delCommon( $tblName, $idx )
	{
		$query = "update ".$tblName." set is_valid = 0 where idx = '".$idx."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAssetLogList( $searchParam, $searchValue, $start_date, $end_date )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.reg_datetime, b.usetype, ";
		$query .= "c.".MY_Controller::COMMON_LANGUAGE_COLUMN.", b.asset_value, ";
		$query .= "b.description from koc_play.player_basic as a ";
		$query .= "inner join koc_play.player_asset_logging as b on a.pid = b.pid ";
		$query .= "left outer join koc_ref.text as c on concat('NG_ARTICLE_', b.asset_type) = c.id ";
		$query .= "where a.".$searchParam." = '".$searchValue."' and b.reg_datetime >= '".$start_date." 00:00:00' ";
		$query .= "and b.reg_datetime < concat(date_add('".$end_date."', interval 1 day), ' 00:00:00') order by b.reg_datetime desc ";

		return $this->DB_SEL->query($query);
	}

	public function getNameForId( $type, $id )
	{
		if ( $type == "SKILL" || $type == "TECHNIQUE" )
		{
			$id = "NG_ARTICLE_".$id;
		}
		else if ( $type == "PVESTAGE" )
		{
			$id = "NG_STAGE_TITLE_".$id;
		}
		else if ( $type == "PVBSTAGE" )
		{
			$id = "NG_BOSS_NAME_".$id;
		}
		else if ( $type == "PRODUCT" )
		{
			$id = "PRODUCT_NAME_".$id;
		}
		else if ( $type == "ITEM" )
		{
			$id = "NG_ARTICLE_".$id;
		}
		else if ( $type == "CHAR" )
		{
			$id = "NG_ARTICLE_".$id;
		}
		else if ( $type == "ACHIEVEMENT" )
		{
			$id = "ACHIEVEMENT_NAME_".$id;
		}
		else if ( $type == "ASST" )
		{
			$id = "NG_ARTICLE_".$id;
		}

		$query = "select ".MY_Controller::COMMON_LANGUAGE_COLUMN." from koc_ref.".MY_Controller::TBL_TEXT." where id = '".$id."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestBuyIAPList( $searchParam, $searchValue, $start_date, $end_date, $platform )
	{
		$query = "select b.id, concat(b.name, '(', b.affiliate_name, ')') as name, b.uuid, ";
		$query .= "a.pid, a.sid, c.".MY_Controller::COMMON_LANGUAGE_COLUMN." as product_id, a.buy_date, a.storetype, ";
		$query .= "a.expire_date, a.payment_unit, a.payment_type, a.payment_value, ";
		$query .= "a.is_provision, a.is_refund, a.paymentSeq, a.approvedPaymentNo, a.naverId, a.paymentTime, a.curcash ";
		$query .= "from koc_play.player_iap as a left outer join koc_play.player_basic as b on a.pid = b.pid ";
		$query .= "left outer join koc_ref.text as c on concat('PRODUCT_NAME_', a.product_id) = c.id ";
		$query .= "where b.".$searchParam." = '".$searchValue."' ";
		$query .= "and a.buy_date >= '".$start_date." 00:00:00' and a.buy_date < concat(date_add('".$end_date."', interval 1 day), ' 00:00:00') ";
		$query .= "and a.storetype = '".$platform."' ";

		return $this->DB_SEL->query($query);
	}

//for API
	public function requestValidDisEventList( $category, $productid )
	{
		$query = "select evt_paytype, evt_value from koc_admin.event_discount where is_valid = 1 and ";
		$query .= "evt_category = '".$category."' and evt_target = '".$productid."' and now() between start_date and end_date ";

		return $this->DB_SEL->query($query);
	}

	public function requestValidAccEventList( )
	{
		$query = "select idx, evt_target, evt_value from koc_admin.event_access where is_valid = 1 ";
		$query .= "and now() between start_date and end_date ";

		return $this->DB_SEL->query($query);
	}

	public function requestAccessEventApply( $pid, $eid )
	{
		$query = "insert into koc_admin.event_access_log ( pid, eid, reg_date ) values ( '".$pid."', '".$eid."', now() ) ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestImageBanner( $idx, $banner_target )
	{
		$query = "select banner_url, banner_link from koc_admin.banner_list where idx = '".$idx."' and now() between start_date and end_date ";
		$query .= "and is_valid = 1 and ( banner_target = 'ALL' or banner_target = '".$banner_target."' ) ";

		return $this->DB_SEL->query($query);
	}

	public function requestImageBannerList( $banner_target )
	{
		$query = "select idx from koc_admin.banner_list where now() between start_date and end_date ";
		$query .= "and is_valid = 1 and ( banner_target = 'ALL' or banner_target = '".$banner_target."' ) order by idx desc ";

		return $this->DB_SEL->query($query);
	}

	public function requestGatchaEventStatus()
	{
		$query = "select idx from koc_admin.gacha_manage where cast(left(now(), 10) as date) between start_date and end_date ";
		$query .= "and hour(now()) between start_time and end_time ";

		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}
}
?>
