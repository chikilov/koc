<?php
class Model_Ref extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_SEL = $this->load->database("koc_ref_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_ref_ins", TRUE);

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

	public function requestUpdateFile( $device )
	{
		$query = "select version, is_bundle, category, concat(device, '/', filename) as filename ";
		$query .= "from koc_ref.".MY_Controller::TBL_DATAFILES." force index (idx_datafiles_device) where ";
		$query .= "device = 'COMMON' or device = '".$device."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestProductList( $pid, $storeType, $storeVersion, $country_code )
	{
		$query = "select a.id, a.alignment_index, a.category, a.retry_id, a.is_retry, a.type, a.value, ";
		$query .= "max(if(d.is_valid, if(now() between d.start_date and d.end_date, d.evt_paytype, null), null)) as evt_paytype, ";
		$query .= "ifnull( max(if(d.is_valid, if(now() between d.start_date and d.end_date, d.evt_value, null), null)), 0) as evt_value, ";
		$query .= "b.payment_unit, b.payment_type, b.payment_value, ";
		$query .= "a.bonus, a.expiration_time, a.target, a.enable, a.version, a.duration, a.".$storeType." as iapcode ";
		$query .= "from koc_ref.".MY_Controller::TBL_PRODUCT." as a inner join koc_ref.".MY_Controller::TBL_PRODUCTPRICE." as b on a.id = b.product_id ";
		$query .= "left outer join ( select product_id from koc_ref.product_price where country_code = 'KR' ) as c on b.product_id = c.product_id ";
		$query .= "left outer join koc_admin.event_discount as d on a.id = d.evt_target ";
		$query .= "where ( a.version = 0 or a.version >= ".$storeVersion." ) and a.enable = 1 and a.expiration_time >= now() ";
		if ( $storeType == "ios" )
		{
			$query .= "and target = 'SELF' ";
		}
		if ( $country_code == "" || $country_code == null )
		{
			$query .= "and b.is_default = 1 ";
		}
		else
		{
			$query .= "and ( b.country_code = '".$country_code."' or c.product_id is null ) ";
		}

		$query .= "group by a.id, a.category, a.retry_id, a.is_retry, a.type, a.value, b.payment_unit, b.payment_type, b.payment_value, ";
		$query .= "a.bonus, a.expiration_time, a.target, a.enable, a.version, a.ios, a.android ";
		$query .= "order by category asc, id asc, ";
		if ( $country_code == "" || $country_code == null )
		{
			$query .= "b.is_default desc ";
		}
		else
		{
			$query .= "b.is_default asc ";
		}

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getAttendReward( $pid, $attendCount )
	{
		$query = "select a.day, a.type, a.value, b.".MY_Controller::COMMON_LANGUAGE_COLUMN." from koc_ref.".MY_Controller::TBL_DAILYREWARD." as a ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as b on concat('NG_ARTICLE_', a.type) = b.id ";
		$query .= "where a.day = if( '".$attendCount."' % ".MY_Controller::MAX_ATTEND." = 0, ".MY_Controller::MAX_ATTEND.", ";
		$query .= "'".$attendCount."' % ".MY_Controller::MAX_ATTEND." ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getExtraAttendReward( $pid, $attendCount )
	{
		$query = "select a.day, a.type, a.value, b.".MY_Controller::COMMON_LANGUAGE_COLUMN." from koc_ref.".MY_Controller::TBL_EVENTATTEND." as a ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as b on concat('NG_ARTICLE_', a.type) = b.id ";
		$query .= "where a.day = '".$attendCount."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestExtraAttendList( $pid )
	{
		$query = "select day, type, value from koc_ref.".MY_Controller::TBL_EVENTATTEND." where day > 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function productVerify( $pid, $storeType, $product, $country_code )
	{
		$query = "select a.product_type, a.category, a.type, a.value as attach_value, c.payment_unit, c.payment_type, c.payment_value, ";
		$query .= "a.bonus, b.article_type, b.article_value, a.vip_exp, a.".$storeType." as iapcode ";
		$query .= "from koc_ref.".MY_Controller::TBL_PRODUCT." as a inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.type = b.article_id ";
		$query .= "inner join koc_ref.product_price as c on a.id = c.product_id ";
		$query .= "where a.enable = 1 and a.id = '".$product."' ";
		if ( $country_code == "" || $country_code == null )
		{
			$query .= "and c.is_default = 1 ";
		}
		else
		{
			$query .= "and c.country_code = '".$country_code."' ";
		}

		$query .= "limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeCharacterListPick( $pid, $cid )
	{
		$query = "select b.id from koc_ref.".MY_Controller::TBL_GATCHA." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_REFCHARACTER." as b on a.reference = b.id ";
		$query .= "where a.id = '".$cid."' ";
		$query .= "order by rand() * a.probability desc limit 1";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeInventoryListPick( $pid, $iid )
	{
		$query = "select b.id from koc_ref.".MY_Controller::TBL_GATCHA." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ITEM." as b on a.reference = b.id ";
		$query .= "where a.id = '".$iid."' ";
		$query .= "order by rand() * a.probability desc limit 1";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeRewardListPick( $pid, $rid )
	{
		$query = "select a.id, a.pattern, a.seq, a.reward_type, a.reward_value as attach_value, c.article_type, c.article_value ";
		$query .= "from koc_ref.".MY_Controller::TBL_REWARD." as a inner join ( select id, pattern, seq, reward_type, reward_value ";
		$query .= "from koc_ref.".MY_Controller::TBL_REWARD." where id = '".$rid."' ";
		$query .= "order by probability * rand() desc limit 1 ) as b on a.id = b.id and a.pattern = b.pattern ";
		$query .= "inner join koc_ref.article as c on a.reward_type = c.article_id ";
		$query .= "order by a.reward_probability * rand() desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeValuePick( $pid, $rid )
	{
		$query = "select id, pattern, ( min(min_prob) + ceil( rand() * (max(max_prob) - min(min_prob)) ) ) as rand_prob ";
		$query .= "from ( select d.id, d.pattern, d.seq, d.reward_type, d.reward_value, d.reward_probability, (@min_prob) as min_prob, ";
		$query .= "(@min_prob:= @min_prob + d.reward_probability) as max_prob from koc_ref.".MY_Controller::TBL_REWARD." as d ";
		$query .= "inner join ( select id, ( min(min_prob) + ceil( rand() * (max(max_prob) - min(min_prob)) ) ) as rand_prob from koc_ref.".MY_Controller::TBL_REWARD." ";
		$query .= "where id = '".$rid."' ) as e on d.id = e.id and e.rand_prob between d.min_prob and d.max_prob, (select @min_prob:= 0) as f ";
		$query .= "where d.id = '".$rid."' ) as g ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeRewardPick( $pid, $rid, $rpattern, $rvalue )
	{
		$query = "select id, pattern, seq, reward_type, reward_value as attach_value, d.article_type, d.article_value ";
		$query .= "from ( select a.id, a.pattern, a.seq, a.reward_type, a.reward_value, a.reward_probability, (@rowsum) + 1 as min_prob, ";
		$query .= "(@rowsum := @rowsum + a.reward_probability) as max_prob from koc_ref.".MY_Controller::TBL_REWARD." as a , (select @rowsum := 0) as b ";
		$query .= "where a.id = '".$rid."' and a.pattern = '".$rpattern."' order by a.seq ) as c ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as d on c.reward_type = d.article_id ";
		$query .= "where ".$rvalue." between min_prob and max_prob ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeRewardListPickWithException( $pid, $rid, $rpattern, $rseq )
	{
		$query = "select a.seq, a.reward_type, a.reward_value as attach_value, b.article_type, b.article_value from koc_ref.".MY_Controller::TBL_REWARD." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.reward_type = b.article_id ";
		$query .= "where a.id = '".$rid."' and a.pattern = '".$rpattern."' and a.seq != '".$rseq."' ";
		$query .= "order by a.reward_probability * rand() desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function randomizeRewardValuePickWithException( $pid, $rid, $rpattern, $rseq )
	{
		$query = "select h.seq, h.reward_type, h.reward_value as attach_value, i.article_type, i.article_value ";
		$query .= "from ( select floor(1 + rand() * sum(reward_probability) ) as rand_prob ";
		$query .= "from ( select a.id, a.pattern, a.seq, a.reward_type, a.reward_value, ";
		$query .= "if( a.seq = b.seq, a.reward_probability + ";
		$query .= "(select reward_probability from koc_ref.".MY_Controller::TBL_REWARD." where id = '".$rid."' and pattern = '".$rpattern."' and seq = '".$rseq."'), ";
		$query .= "a.reward_probability ) as reward_probability from reward as a left outer join ( ";
		$query .= "select seq from koc_ref.".MY_Controller::TBL_REWARD." where id = '".$rid."' and pattern = '".$rpattern."' and seq != '".$rseq."' ";
		$query .= "order by reward_probability desc limit 1 ";
		$query .= ") as b on a.seq = b.seq where a.id = '".$rid."' and a.pattern = '".$rpattern."' and a.seq != '".$rseq."' ) as c ) as d inner join ";
		$query .= "( select e.id, e.pattern, e.seq, e.reward_type, e.reward_value, (@rowsum) + 1 as min_prob, (@rowsum := @rowsum + ";
		$query .= "if( e.seq = f.seq, e.reward_probability + ";
		$query .= "(select reward_probability from koc_ref.".MY_Controller::TBL_REWARD." where id = '".$rid."' and pattern = '".$rpattern."' and seq = '".$rseq."'), ";
		$query .= "e.reward_probability ) ) as max_prob from koc_ref.".MY_Controller::TBL_REWARD." as e ";
		$query .= "left outer join ( select seq from koc_ref.".MY_Controller::TBL_REWARD." ";
		$query .= "where id = '".$rid."' and pattern = '".$rpattern."' and seq != '".$rseq."' order by reward_probability desc limit 1 ";
		$query .= ") as f on e.seq = f.seq, (select @rowsum := 0) as g where e.id = '".$rid."' and e.pattern = '".$rpattern."' and e.seq != '".$rseq."' ";
		$query .= "order by e.seq ) as h on d.rand_prob between h.min_prob and h.max_prob ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as i on h.reward_type = i.article_id ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getRewardIdFromStage( $pid, $stageid )
	{
		$query = "select reward, platform_points from koc_ref.".MY_Controller::TBL_STAGE." where id = '".$stageid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getExpSecond( $pid, $grade, $exp_time_rate )
	{
		$query = "select floor( time * ".( 1 - $exp_time_rate )." ) as time from koc_ref.".MY_Controller::TBL_EXPREF." where grade = '".$grade."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPriceProduct( $pid, $arrid )
	{
		$query = "select payment_type, sum(payment) as payment from koc_ref.".MY_Controller::TBL_PRODUCT." ";
		$query .= "where id in ('".join("', '", $arrid)."') group by payment_type ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestUpgradeInfo( $pid, $targetIdx, $sourceIdx )
	{
		$query = "select a.step, a.probability, a.payment_type, a.payment, a.incentive, a.reference, ";
		$query .= "b.up_incentive, c.weapon, c.backpack, c.skill_0, c.skill_1, c.skill_2 ";
		$query .= "from koc_ref.".MY_Controller::TBL_UPGRADE." as a inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b ";
		$query .= "on (a.grade, a.step) = (b.grade, b.up_grade) ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on a.m_grade = c.grade where category = 'CHARACTER' ";
		$query .= "and b.idx = '".$targetIdx."' and c.idx = '".$sourceIdx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestUpgradeItemInfo( $pid, $itemType, $sourceIdx )
	{
		$query = "select sum(exp) as exp, payment_type, sum(payment_value) as payment_value ";
		$query .= "from koc_ref.".MY_Controller::TBL_UPGRADEITEM." as a inner join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b ";
		$query .= "on a.grade = b.grade and a.current_step = b.up_grade ";
		$query .= "where catergory = '".$itemType."' and b.idx in ('".join( "','", $sourceIdx )."') group by payment_type ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestAchieveType( $pid, $aid )
	{
		$query = "select id, repeate from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." ";
		$query .= "where id in ('".join("', '", $aid)."') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestAchieveRewardSum( $pid, $aid )
	{
		$query = "select reward_type, sum(reward_value) as attach_value, article_type, article_value from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.reward_type = b.article_id ";
		$query .= "where id in ('".join("', '", $aid)."') and reward_type is not null and reward_type != '' and article_type = 'ASST' group by article_value ";
		$query .= "union select reward_type, reward_value as attach_value, article_type, article_value from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as c ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as d on c.reward_type = d.article_id ";
		$query .= "where id in ('".join("', '", $aid)."') and reward_type is not null and reward_type != '' and article_type != 'ASST' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getGatchaInfo( $pid, $cid )
	{
		$query = "select category from koc_ref.".MY_Controller::TBL_GATCHA." where id = '".$cid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestExpExp( $pid, $grade )
	{
		$query = "select exp from koc_ref.".MY_Controller::TBL_EXPREF." where grade = '".$grade."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLevelInfo( $pid, $exp )
	{
		$query = "select level from koc_ref.".MY_Controller::TBL_LEVINFO." where exp > ".$exp." order by level asc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getNameForId( $pid, $type, $id )
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

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getRankRewardMaxRtype( $pid, $rewardtype )
	{
		$query = "select max(rank_max) as rank from koc_ref.".MY_Controller::TBL_RANKREWARD." where unit = 'R' and category = '".$rewardtype."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestRankRewardInfo( $pid, $rank, $tot, $rewardtype, $rewardUnit )
	{
		$query = "select a.reward_type, a.reward_value, b.article_type from koc_ref.".MY_Controller::TBL_RANKREWARD." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.reward_type = b.article_id ";
		$query .= "where a.category = '".$rewardtype."' and a.unit = '".$rewardUnit."' and case when '".$rewardUnit."' = 'P' then ";
		$query .= "ceil(".$rank."/".$tot."*100) else ".$rank." end between a.rank_min and a.rank_max ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestExpInfo( $pid, $itemType, $grade, $exp )
	{
		$query = "select current_step as level, reference from koc_ref.".MY_Controller::TBL_ITEMLEVINFO." ";
		$query .= "where catergory = '".$itemType."' and grade = '".$grade."' and '".$exp."' between min_exp and max_exp ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestSupplies( $pid )
	{
		$query = "select a.type, b.article_type, a.value from koc_ref.".MY_Controller::TBL_SUPPLIES." as a inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b ";
		$query .= "on a.type = b.article_id where a.valid = 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestRetryInfo( $pid, $stageid )
	{
		$query = "select retry_type, retry_value from koc_ref.".MY_Controller::TBL_STAGE." where id = '".$stageid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestVipReward( $pid, $prev_level, $vip_level )
	{
		$query = "select reward_div, reward_type, reward_value from koc_ref.".MY_Controller::TBL_VIPREWARD." ";
		$query .= "where vip_lev > ".$prev_level." and vip_lev <= ".$vip_level." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestDailyVipReward( $pid, $vip_level )
	{
		$query = "select reward_div, reward_type, reward_value from koc_ref.".MY_Controller::TBL_VIPREWARD." ";
		$query .= "where vip_lev = ".$vip_level." and reward_div = 'DAILY' and not exists ( select pid from koc_play.".MY_Controller::TBL_PLAYERVIP." ";
		$query .= "where pid = '".$pid."' and left(vipreward_datetime, 10) = left(now(), 10) ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestStageReward( $pid, $stageid )
	{
		$query = "select max_exp, max_gold from koc_ref.".MY_Controller::TBL_STAGE." where id = '".$stageid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMaterialCheck( $pid, $targetId, $sourceId )
	{
		$query = "select e_target from koc_ref.".MY_Controller::TBL_REFCHARACTER." where id = '".$targetId."' and e_material = '".$sourceId."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPackageList( $pid, $product_id )
	{
		$query = "select type, value from koc_ref.package_list where id = '".$product_id."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

//for ADMIN
	public function requestAdminProductList()
	{
		$query = "select a.category, a.id, b.".MY_Controller::COMMON_LANGUAGE_COLUMN." ";
		$query .= "from koc_ref.".MY_Controller::TBL_PRODUCT." as a inner join koc_ref.".MY_Controller::TBL_TEXT." as b on concat('PRODUCT_NAME_', a.id) = b.id ";
		$query .= "where a.category != 'CASH' order by a.category asc, a.id asc ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestAdminArticleList()
	{
		$query = "select a.article_type, case when a.article_type = 'ASST' then '자원' when a.article_type = 'BCPC' then '백팩' ";
		$query .= "when a.article_type = 'BTIK' then '백팩뽑기권' when a.article_type = 'CHAR' then '캐릭터' ";
		$query .= "when a.article_type = 'CTIK' then '캐릭터뽑기권' when a.article_type = 'OPRT' then '오퍼레이터' ";
		$query .= "when a.article_type = 'PILT' then '파일럿' when a.article_type = 'SKIL' then '스킬' ";
		$query .= "when a.article_type = 'STIK' then '스킬뽑기권' when a.article_type = 'WEPN' then '무기' ";
		$query .= "when a.article_type = 'WTIK' then '무기뽑기권' end as category, ";
		$query .= "a.article_id, if(a.article_id = 'CASH_POINTS', '구매수정', ";
		$query .= "if( a.article_id = 'EVENT_POINTS', '이벤트수정', b.".MY_Controller::COMMON_LANGUAGE_COLUMN.")) as ".MY_Controller::COMMON_LANGUAGE_COLUMN.", ";
		$query .= "if(a.article_type = 'CHAR', e.grade, d.grade) as grade ";
		$query .= "from koc_ref.".MY_Controller::TBL_ARTICLE." as a left outer join koc_ref.".MY_Controller::TBL_TEXT." as b ";
		$query .= "on concat('NG_ARTICLE_', a.article_id) = b.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on a.article_value = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_REFCHARACTER." as e on a.article_value = e.id ";
		$query .= "where a.article_type in ('ASST', 'BCPC', 'BTIK', 'CHAR', 'CTIK', 'OPRT', 'PILT', 'SKIL', 'STIK', 'WEPN', 'WTIK') ";
		$query .= "and a.article_id != 'EXP_POINTS' order by a.article_type asc ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestGatcha( $pid, $id )
	{
		$query = "select a.id, a.idx, ifnull(c.grade, 0) + ifnull(d.grade, 0) as grade, a.refid ";
		$query .= "from koc_ref.".MY_Controller::TBL_GATCHA_SIM." as a inner join koc_ref.numbers as b on a.probability >= b.number ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_REFCHARACTER." as c on a.refid = c.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on a.refid = d.id ";
		$query .= "where a.id = '".$id."' and a.probability > 0 ";
		$query .= "order by rand() desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestGatchaEvent( $pid, $id )
	{
		$query = "select a.id, a.idx, ifnull(c.grade, 0) + ifnull(d.grade, 0) as grade, a.refid ";
		$query .= "from koc_ref.".MY_Controller::TBL_GATCHA_EVENT_SIM." as a inner join koc_ref.numbers as b on a.probability >= b.number ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_REFCHARACTER." as c on a.refid = c.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on a.refid = d.id ";
		$query .= "where a.id = '".$id."' and a.probability > 0 ";
		$query .= "order by rand() desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestGatchaUpdateProbability( $pid, $id, $refid )
	{
		$query = "update koc_ref.".MY_Controller::TBL_GATCHA_SIM." set probability = probability - 1 ";
		$query .= "where id = '".$id."' and refid = '".$refid."' and probability > 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestGatchaEventUpdateProbability( $pid, $id, $refid )
	{
		$query = "update koc_ref.".MY_Controller::TBL_GATCHA_EVENT_SIM." set probability = probability - 1 ";
		$query .= "where id = '".$id."' and refid = '".$refid."' and probability > 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function insertGatcha( $pid, $id )
	{
		$query = "insert into koc_ref.".MY_Controller::TBL_GATCHA_SIM." ( id, refid, probability ) ";
		$query .= "select id, reference, probability from koc_ref.".MY_Controller::TBL_GATCHA." where id = '".$id."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function insertGatchaEvent( $pid, $id )
	{
		$query = "insert into koc_ref.".MY_Controller::TBL_GATCHA_EVENT_SIM." ( id, refid, probability ) ";
		$query .= "select id, reference, probability from koc_ref.".MY_Controller::TBL_GATCHA_EVENT." where id = '".$id."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestGetGatcha( $id, $grade, $refid, $pid )
	{
		$query = "insert into koc_ref.".MY_Controller::TBL_GATCHA_RESULT." ( id, grade, refid, pid, result_date ) ";
		$query .= "values ( '".$id."', '".$grade."', '".$refid."', '".$pid."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestGetGatchaEvent( $id, $grade, $refid, $pid )
	{
		$query = "insert into koc_ref.".MY_Controller::TBL_GATCHA_EVENT_RESULT." ( id, grade, refid, pid, result_date ) ";
		$query .= "values ( '".$id."', '".$grade."', '".$refid."', '".$pid."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestGatchaProbabilityList()
	{
		$query = "select a.id, c.kr, round(b.probability / a.totsum * 100, 3) as prob from ( select id, sum(probability) as totsum ";
		$query .= "from koc_ref.".MY_Controller::TBL_GATCHA." group by id ) as a ";
		$query .= "inner join ( select id, reference, probability from koc_ref.".MY_Controller::TBL_GATCHA." ) as b on a.id = b.id ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_TEXT." as c on concat( 'NG_ARTICLE_', b.reference ) = c.id ";

		return $this->DB_SEL->query($query);
	}

	//테스트용
	public function requestGatchaInfo( $id )
	{
		$query = "select id, reference, probability from koc_ref.".MY_Controller::TBL_GATCHA." where id = '".$id."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestInitGatcha( $id )
	{
		$query = "insert into koc_ref.".MY_Controller::TBL_GATCHA_SIM." (id, refid) select id, refid from gatcha where id = '".$id."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestRewardTest1()
	{
		$query = "select id, pattern, ( min(min_prob) + ceil( rand() * (max(max_prob) - min(min_prob)) ) ) as rand_prob from ( select d.id, d.pattern, d.seq, d.reward_type, d.reward_value, d.reward_probability, (@min_prob) as min_prob, (@min_prob:= @min_prob + d.reward_probability) as max_prob from koc_ref.reward as d inner join ( select id, ( min(min_prob) + ceil( rand() * (max(max_prob) - min(min_prob)) ) ) as rand_prob from koc_ref.reward where id = 'RWPVE05215' ) as e on d.id = e.id and e.rand_prob between d.min_prob and d.max_prob, (select @min_prob:= 0) as f where d.id = 'RWPVE05215' ) as g";

		return $this->DB_SEL->query($query);
	}

	public function requestRewardTest2( $rid, $rpattern, $rvalue )
	{
		$query = "select id, pattern, seq, reward_type, reward_value as attach_value, d.article_type, d.article_value,reward_probability from ( select a.id, a.pattern, a.seq, a.reward_type, a.reward_value, a.reward_probability, (@rowsum) + 1 as min_prob, (@rowsum := @rowsum + a.reward_probability) as max_prob from koc_ref.reward as a , (select @rowsum := 0) as b  where a.id = '".$rid."' and a.pattern = '".$rpattern."' order by a.seq ) as c inner join koc_ref.article as d on c.reward_type = d.article_id  where ".$rvalue." between min_prob and max_prob";

		return $this->DB_SEL->query($query);
	}

	public function rewardTestInsert( $rid, $rpattern, $rseq )
	{
		$query = "insert into koc_ref.reward_test select * from reward where id = '".$rid."' and pattern = '".$rpattern."' and seq = '".$rseq."' ";

		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}
}
?>
