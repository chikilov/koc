<?php
class Model_Rank extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_SEL = $this->load->database("koc_rank_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_rank_ins", TRUE);

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

	public function requestUpdatePVERank( $pid, $stageid, $duration, $grade )
	{
		$query = "insert into koc_rank.".MY_Controller::TBL_PVE." ( pid, stageid, grade, min_duration, rank_datetime ) ";
		$query .= "select '".$pid."', '".$stageid."', '".$grade."', '".$duration."', now() from dual ";
		$query .= "where not exists ( select pid from koc_rank.".MY_Controller::TBL_PVE." where pid = '".$pid."' and stageid = '".$stageid."' ";
		$query .= "and ( ifnull(if(min_duration = 0, 99999, min_duration), 99999) <= '".$duration."' and ifnull(if(grade = 0, 3, grade), 3) >= '".$grade."' ) ) ";
		$query .= "on duplicate key update min_duration = '".$duration."', grade = '".$grade."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestUpdatePVBRank( $pid, $stageid, $score )
	{
		$query = "insert into koc_rank.".MY_Controller::TBL_PVBSTORE." ( pid, weekseq, name, affiliate_name, prof_img, stageid, rank, score, rank_datetime ) ";
		$query .= "select '".$pid."', ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end, ";
		$query .= "( select name from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_prof, prof_img, '') as prof_img from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "'".$stageid."', 0, '".$score."', now() from dual ";
		$query .= "on duplicate key update score = score + ".$score.", ";
		$query .= "affiliate_name = ( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestUpdateSURVIVALRank( $pid, $score )
	{
		$query = "insert into koc_rank.".MY_Controller::TBL_SURVIVALSTORE." ( pid, weekseq, name, affiliate_name, prof_img, rank, score, rank_datetime ) ";
		$query .= "select '".$pid."', ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end, ";
		$query .= "( select name from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_prof, prof_img, '') as prof_img from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "0, '".$score."', now() from dual ";
		$query .= "on duplicate key update score = score + ".$score.", ";
		$query .= "affiliate_name = ( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestUpdatePVPRank( $pid, $score )
	{
		$query = "insert into koc_rank.".MY_Controller::TBL_PVPSTORE." ( pid, dateseq, name, affiliate_name, prof_img, rank, score, rank_datetime ) ";
		$query .= "select '".$pid."', date_format(now(), '%Y%m%d'), ";
		$query .= "( select name from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "( select if(show_prof, prof_img, '') as prof_img from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ), ";
		$query .= "0, '".$score."', now() from dual ";
		$query .= "on duplicate key update score = score + ".$score.", ";
		$query .= "affiliate_name = ( select if(show_name, affiliate_name, '') from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestPVPScore( $pid )
	{
		$query = "select rank, score from koc_rank.".MY_Controller::TBL_PVPSTORE." where pid = '".$pid."' ";
		$query .= "and dateseq = date_format(now(), '%Y%m%d') ";
		/* 20160314 랭킹 일일로 변경
		$query .= "and weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) ";
		*/
		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestPVERank( $pid )
	{
		$query = "select stageid, grade, min_duration from koc_rank.".MY_Controller::TBL_PVE." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestEnemyForPVP( $pid, $downNum, $score )
	{
		$query = "select pid from koc_rank.".MY_Controller::TBL_PVP." where pid != '".$pid."' ";
		$query .= "and score between ".($score - 100 + $downNum)." ";
		$query .= "and ".($score + 100 + $downNum)." order by rand() desc limit 1";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPVPRankCount( $pid )
	{
		$query = "select pid from koc_rank.".MY_Controller::TBL_PVP." where pid != '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestRankingInfoPVP( $pid, $page )
	{
		$query = "select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, c.refid, a.rank, a.score from koc_rank.".MY_Controller::TBL_PVP." as a ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid and b.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx where ";
		$query .= "a.dateseq = date_format(now(), '%Y%m%d') order by a.rank asc ";
		$query .= "limit ".$page.", ".MY_Controller::COMMON_RANKING_PAGE_SIZE." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMyRankingInfoPVP( $pid )
	{
		$query = "select rank, score, rank_score from ( select ifnull(a.rank, 0) as rank, ifnull(a.score, 0) as score, ifnull(b.score, 0) as rank_score ";
		$query .= "from koc_rank.".MY_Controller::TBL_PVPSTORE." as a left outer join koc_rank.".MY_Controller::TBL_PVP." as b ";
		$query .= "on a.pid = b.pid and a.dateseq = b.dateseq ";
		$query .= "where a.pid = '".$pid."' and a.dateseq = date_format(now(), '%Y%m%d') union select 0, 0, 0 from dual ) as a order by rank desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestFriendRankingInfoPVP( $pid )
	{
		$query = "select pid, name, refid, rank, score, prof_img ";
		$query .= "from ( select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, d.refid, a.rank, a.score from koc_rank.".MY_Controller::TBL_PVP." as a ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERFRIEND." as b on (a.pid = b.fid or a.pid = b.pid) ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid and c.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as d on c.memb_0 = d.idx ";
		$query .= "where b.pid = '".$pid."' and b.friend_status = 1 and a.dateseq = date_format(now(), '%Y%m%d') group by a.pid, a.name, a.rank, a.score ) as a order by rank asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestRankingInfoPVB( $pid, $page )
	{
		$query = "select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, c.refid, a.rank, a.score ";
		$query .= "from koc_rank.".MY_Controller::TBL_PVB." as a force index (idx_pvb_weekseq, idx_pvb_rank) ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid and b.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx where ";
		$query .= "a.weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) order by a.rank asc ";
		$query .= "limit ".$page.", ".MY_Controller::COMMON_RANKING_PAGE_SIZE." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMyRankingInfoPVB( $pid )
	{
		$query = "select rank, score, rank_score from ( select ifnull(a.rank, 0) as rank, ifnull(a.score, 0) as score, ifnull(b.score, 0) as rank_score ";
		$query .= "from koc_rank.".MY_Controller::TBL_PVBSTORE." as a left outer join koc_rank.".MY_Controller::TBL_PVB." as b ";
		$query .= "on a.pid = b.pid and a.weekseq = b.weekseq ";
		$query .= "where a.pid = '".$pid."' and a.weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." ";
		$query .= "then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) union select 0, 0, 0 from dual ) as a order by rank desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestFriendRankingInfoPVB( $pid )
	{
		$query = "select pid, name, refid, rank, score, prof_img ";
		$query .= "from ( select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, d.refid, a.rank, a.score from koc_rank.".MY_Controller::TBL_PVB." as a ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERFRIEND." as b on (a.pid = b.fid or a.pid = b.pid) ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid and c.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as d on c.memb_0 = d.idx ";
		$query .= "where b.pid = '".$pid."' and b.friend_status = 1 and a.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) group by a.pid, a.name, a.rank, a.score ) as a order by rank asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestRankingInfoSURVIVAL( $pid, $page )
	{
		$query = "select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, c.refid, a.rank, a.score ";
		$query .= "from koc_rank.".MY_Controller::TBL_SURVIVAL." as a inner join ";
		$query .= "koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid and b.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx where ";
		$query .= "a.weekseq = ( case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) order by a.rank asc ";
		$query .= "limit ".$page.", ".MY_Controller::COMMON_RANKING_PAGE_SIZE." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMyRankingInfoSURVIVAL( $pid )
	{
		$query = "select rank, score, rank_score from ( select ifnull(a.rank, 0) as rank, ifnull(a.score, 0) as score, ifnull(b.score, 0) as rank_score ";
		$query .= "from koc_rank.".MY_Controller::TBL_SURVIVALSTORE." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVAL." as b ";
		$query .= "on a.pid = b.pid and a.weekseq = b.weekseq ";
		$query .= "where a.pid = '".$pid."' and a.weekseq = ( case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." ";
		$query .= "then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) union select 0, 0, 0 from dual ) as a order by rank desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestFriendRankingInfoSURVIVAL( $pid )
	{
		$query = "select pid, name, refid, rank, score, prof_img ";
		$query .= "from ( select a.pid, concat(ifnull(a.name, '-'), if(a.affiliate_name is null or a.affiliate_name = '', '', concat('(', a.affiliate_name, ')'))) as name, ";
		$query .= "a.prof_img, d.refid, a.rank, a.score ";
		$query .= "from koc_rank.".MY_Controller::TBL_SURVIVAL." as a ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERFRIEND." as b on (a.pid = b.fid or a.pid = b.pid) ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid and c.team_seq = 0 ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as d on c.memb_0 = d.idx ";
		$query .= "where b.pid = '".$pid."' and b.friend_status = 1 and a.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) group by a.pid, a.name, a.rank, a.score ) as a order by rank asc";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastWeekRankInfo( $pid, $rewardtype )
	{
		$query = "select max(if(pid = '".$pid."', rank, null)) as rank, max(if(pid = '".$pid."', is_reward, null)) as is_reward, ";
		$query .= "count(pid) as tot ";
		if ( $rewardtype == "pvp" )
		{
			$query .= "from koc_rank.".$rewardtype."_lastday where dateseq = date_format(date_add(now(), interval -1 day), '%Y%m%d') ";
		}
		else if ( $rewardtype == "pvb" )
		{
			$query .= "from koc_rank.".$rewardtype."_lastweek where weekseq = ( ";
			$query .= "case when dayofweek(date_add(now(), interval -7 day)) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
			$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(date_add(now(), interval -7 day), 2) end ) ";
		}
		else if ( $rewardtype == "survival" )
		{
			$query .= "from koc_rank.".$rewardtype."_lastweek where weekseq = ( ";
			$query .= "case when dayofweek(date_add(now(), interval -7 day)) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
			$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(date_add(now(), interval -7 day), 2) end ) ";
		}

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateSendResult( $pid, $rewardtype, $result )
	{
		$query = "update koc_rank.".$rewardtype."_lastweek set is_reward = ".$result.", reward_datetime = now() where pid = '".$pid."' and weekseq = ( ";
		if ( $rewardtype == "pvp" )
		{
			$query = "update koc_rank.".$rewardtype."_lastday set is_reward = ".$result.", reward_datetime = now() where pid = '".$pid."' and dateseq = date_format(date_add(now(), interval -1 day), '%Y%m%d') ";
		}
		else if ( $rewardtype == "pvb" )
		{
			$query .= "case when dayofweek(date_add(now(), interval -7 day)) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
			$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(date_add(now(), interval -7 day), 2) end ) ";
		}
		else if ( $rewardtype == "survival" )
		{
			$query .= "case when dayofweek(date_add(now(), interval -7 day)) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
			$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(date_add(now(), interval -7 day), 2) end ) ";
		}

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLastPVEStage( $pid )
	{
		$query = "select concat(c.kr, '(', if(b.diff = 0, '일반', if(b.diff = 1, '하드', '헬')), ')') as stage, substring(max(b.id), 5, 2) as scene ";
		$query .= "from koc_rank.".MY_Controller::TBL_PVE." as a inner join koc_ref.".MY_Controller::TBL_STAGE." as b on a.stageid = b.id ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_TEXT." as c on concat('EPISODE_', upper(b.episode), '_NAME') = c.id ";
		$query .= "where a.pid = '".$pid."' ";
		$query .= "group by b.episode, b.diff ";
		$query .= "order by b.id asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVEStageByPid( $pid )
	{
		$query = "select a.pid, concat(d.kr, '(', if(c.diff = 0, '일반', if(c.diff = 1, '하드', '헬')), ')') as stage, substring(max(c.id), 5, 2) as scene ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVE." as b on a.pid = b.pid ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_STAGE." as c on b.stageid = c.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat('EPISODE_', upper(c.episode), '_NAME') = d.id ";
		$query .= "where a.pid = '".$pid."' ";
		$query .= "group by c.episode, c.diff ";
		$query .= "order by c.id asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVEStageById( $id )
	{
		$query = "select a.pid, concat(d.kr, '(', if(c.diff = 0, '일반', if(c.diff = 1, '하드', '헬')), ')') as stage, substring(max(c.id), 5, 2) as scene ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVE." as b on a.pid = b.pid ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_STAGE." as c on b.stageid = c.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat('EPISODE_', upper(c.episode), '_NAME') = d.id ";
		$query .= "where a.id = '".$id."' ";
		$query .= "group by c.episode, c.diff ";
		$query .= "order by c.id asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVEStageByName( $name )
	{
		$query = "select a.pid, concat(d.kr, '(', if(c.diff = 0, '일반', if(c.diff = 1, '하드', '헬')), ')') as stage, substring(max(c.id), 5, 2) as scene ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVE." as b on a.pid = b.pid ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_STAGE." as c on b.stageid = c.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat('EPISODE_', upper(c.episode), '_NAME') = d.id ";
		$query .= "where a.name = '".$name."' ";
		$query .= "group by c.episode, c.diff ";
		$query .= "order by c.id asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVPStage( $pid )
	{
		$query = "select rank, score from koc_rank.".MY_Controller::TBL_PVPSTORE." where pid = '".$pid."' and weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVPStageByPid( $pid )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVPStageById( $id )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVPStageByName( $name )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVPLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVP." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVBStage( $pid )
	{
		$query = "select rank, score from koc_rank.".MY_Controller::TBL_PVBSTORE." where pid = '".$pid."' and weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end )";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVBStageByPid( $pid )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVBStageById( $id )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastPVBStageByName( $name )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(count(c.idx), '-') as match_count, ";
		$query .= "ifnull(sum(if( c.is_clear = 1, 1, 0 )), '-') as win_count, ";
		$query .= "ifnull(sum(if( c.is_clear = -1, 1, 0 )), '-') as lose_count, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_PVBLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_PVB." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastSURVIVALStage( $pid )
	{
		$query = "select rank, score from koc_rank.".MY_Controller::TBL_SURVIVALSTORE." where pid = '".$pid."' and weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end )";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastSURVIVALStageByPid( $pid )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.pid = '".$pid."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastSURVIVALStageById( $id )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.id = '".$id."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLastSURVIVALStageByName( $name )
	{
		$query = "select 'curSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALSTORE." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) union ";
		$query .= "select 'lastSeason' as season, ifnull(b.rank, '-') as rank, ifnull(b.score, '-') as score ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_rank.".MY_Controller::TBL_SURVIVALLASTWEEK." as b on a.pid = b.pid ";
		$query .= "inner join koc_record.".MY_Controller::TBL_SURVIVAL." as c on b.pid = c.pid and b.weekseq = c.weekseq ";
		$query .= "where a.name = '".$name."' and b.weekseq = ( ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -14 day), 2) else yearweek(now(), 2) end ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function resetPlayerPVE( $pid )
	{
		$query = "delete from koc_rank.".MY_Controller::TBL_PVE." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerPVB( $pid )
	{
		$query = "delete from koc_rank.".MY_Controller::TBL_PVB." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerSURVIVAL( $pid )
	{
		$query = "delete from koc_rank.".MY_Controller::TBL_PVP." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerPVP( $pid )
	{
		$query = "delete from koc_rank.".MY_Controller::TBL_PVP." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}
}
?>
