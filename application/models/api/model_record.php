<?php
class Model_Record extends MY_Model {

	public function __construct()
	{
		parent::__construct();
//20141210rep	$this->DB_API = $this->load->database("koc_record", TRUE);
		$this->DB_SEL = $this->load->database("koc_record_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_record_ins", TRUE);

		/**
		 * 기본적으로 CodeIgniter 는 트랜잭션을 완벽모드(Strict Mode)로 실행합니다.
		 * 완벽모드가 활성화된 상태에서는 여러그룹의 트랜잭션을 실행했을때 단하나라도 실패하게되면 전체는 롤백됩니다.
		 * 만약 완벽모드가 비활성화라면, 여러그룹의 트랜잭션을 실행했을때
		 * 각각의 그룹은 독립적으로 실행되기때문에 각 그룹내에서만 성공여부에따라서 커밋,롤백 하게 됩니다.
		 * 즉 그룹간에는 서로 영향이 없습니다.
		 */
//20141210rep	$this->DB_API->trans_strict(FALSE);
		$this->DB_SEL->trans_strict(FALSE);
		$this->DB_INS->trans_strict(FALSE);

//20141210rep	$this->DB_API->query("SET NAMES utf8");
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
//20141210rep	$this->DB_API->trans_start();
		$this->DB_INS->trans_start();
	}

	/**
	 * 트랜젝션 종료
	 *start/complete 함수사이에 원하는 수 만큼의 쿼리를 실행하면 전체의 성공여부에따라 함수들이 알아서 커밋 혹은 롤백을 수행합니다.
	*/
	public function onCompleteTransaction()
    {
//20141210rep	$this->DB_API->trans_complete();
		$this->DB_INS->trans_complete();
    }

	/************************* 트랜젝션 수동 처리용********************/
	/*
	 * 트랜젝션에 대한 처리를 수동으로 처리할때 사용하는 함수
	*/
	public function onBeginTransaction()
	{
//20141210rep	$this->DB_API->trans_begin();
		$this->DB_INS->trans_begin();
	}

	/*
	 * 예외 상황 발생시 처리용
	*/
	public function onRollbackTransaction()
	{
//20141210rep	$this->DB_API->trans_rollback();
		$this->DB_INS->trans_rollback();
	}

	/*
	 * 로직이 끝나는 시점에서 호출해서 쿼리 수행에 문제가 있었는지 판단하여 롤벡 또는 커밋 시켜주는 함수.
	*/
	public function onEndTransaction( $result )
	{
//20141210rep	if ($this->DB_API->trans_status() === FALSE || $result === FALSE)
//20141210rep	{
//20141210rep		$this->DB_API->trans_rollback();
//20141210rep	}
//20141210rep	else
//20141210rep	{
//20141210rep		$this->DB_API->trans_commit();
//20141210rep	}
		if ($this->DB_INS->trans_status() === FALSE || $result === FALSE)
		{
		    $this->DB_INS->trans_rollback();
		}
		else
		{
		    $this->DB_INS->trans_commit();
		}
	}

	public function requestLoggingStartPVE( $pid, $stageid, $friendid, $instant_item1, $instant_item2, $instant_item3, $instant_item4 )
	{
		$query = "insert into koc_record.".MY_Controller::TBL_PVE." ( pid, stageid, friendid, instant_item1, instant_item2, instant_item3, instant_item4, start_datetime ) values ( ";
		$query .= "'".$pid."', ";
		$query .= "'".$stageid."', ";
		if ( strcmp($friendid, "") == 0 )
		{
			$query .= "null, ";
		}
		else
		{
			$query .= "'".$friendid."', ";
		}
		$query .= "'".$instant_item1."', ";
		$query .= "'".$instant_item2."', ";
		$query .= "'".$instant_item3."', ";
		$query .= "'".$instant_item4."', ";
		$query .= "now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function updateLoggingRewardStepForPVE( $pid, $logid, $cidArray, $basic_reward_type, $basic_reward_value, $random_reward_id, $random_reward_pattern, $random_reward_seq, $random_reward_type, $random_reward_value, $random_reward_idx, $duration, $is_clear )
	{
		$query = "update koc_record.".MY_Controller::TBL_PVE." set ";
		$query .= "character_0 = '".$cidArray[0]["idx"]."', ";
		$query .= "character_1 = '".$cidArray[1]["idx"]."', ";
		$query .= "character_2 = '".$cidArray[2]["idx"]."', ";
		$query .= "lev_0 = '".$cidArray[0]["lev"]."', ";
		$query .= "lev_1 = '".$cidArray[1]["lev"]."', ";
		$query .= "lev_2 = '".$cidArray[2]["lev"]."', ";
		$query .= "exp_0 = '".$cidArray[0]["exp"]."', ";
		$query .= "exp_1 = '".$cidArray[1]["exp"]."', ";
		$query .= "exp_2 = '".$cidArray[2]["exp"]."', ";
		$query .= "basic_reward_type = '".$basic_reward_type."', ";
		$query .= "basic_reward_value = '".$basic_reward_value."', ";
		$query .= "random_reward_id = '".$random_reward_id."', ";
		$query .= "random_reward_pattern = '".$random_reward_pattern."', ";
		$query .= "random_reward_seq = '".$random_reward_seq."', ";
		$query .= "random_reward_type = '".$random_reward_type."', ";
		$query .= "random_reward_value = '".$random_reward_value."', ";
		if ( $random_reward_idx )
		{
			$query .= "random_reward_idx = '".$random_reward_idx."', ";
		}
		$query .= "duration = '".$duration."', ";
		$query .= "is_clear = ".$is_clear.", ";
		$query .= "result_datetime = now() ";
		$query .= "where pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateLoggingRetryRewardStepForPVE( $pid, $logid, $additional_reward_seq, $additional_reward_type, $additional_reward_value, $additional_reward_idx )
	{
		$query = "update koc_record.".MY_Controller::TBL_PVE." set ";
		$query .= "additional_reward_seq = '".$additional_reward_seq."', ";
		$query .= "additional_reward_type = '".$additional_reward_type."', ";
		$query .= "additional_reward_value = '".$additional_reward_value."', ";
		if ( $additional_reward_idx )
		{
			$query .= "additional_reward_idx = '".$additional_reward_idx."', ";
		}
		$query .= "additional_datetime = now() ";
		$query .= "where pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerPVE( $pid )
	{
		$query = "delete from koc_record.".MY_Controller::TBL_PVE." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLoggingStartPVB( $pid, $stageid, $level, $use_cash, $instant_item1, $instant_item2, $instant_item3, $instant_item4 )
	{
		$query = "insert into koc_record.".MY_Controller::TBL_PVB." ";
		$query .= "( pid, weekseq, stageid, level, use_cash, instant_item1, instant_item2, instant_item3, instant_item4, start_datetime ) values ( ";
		$query .= "'".$pid."', ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end, ";
		$query .= "'".$stageid."', ";
		$query .= "'".$level."', ";
		$query .= "".$use_cash.", ";
		$query .= "'".$instant_item1."', ";
		$query .= "'".$instant_item2."', ";
		$query .= "'".$instant_item3."', ";
		$query .= "'".$instant_item4."', ";
		$query .= "now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function updateLoggingResultStepForPVB( $pid, $logid, $score, $is_clear )
	{
		$query = "update koc_record.".MY_Controller::TBL_PVB." set ";
		$query .= "score = '".$score."', ";
		$query .= "is_clear = ".$is_clear.", ";
		$query .= "result_datetime = now() ";
		$query .= "where pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerPVB( $pid )
	{
		$query = "delete from koc_record.".MY_Controller::TBL_PVB." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLoggingStartSURVIVAL( $pid, $use_cash, $instant_item1, $instant_item2, $instant_item3, $instant_item4 )
	{
		$query = "insert into koc_record.".MY_Controller::TBL_SURVIVAL." ";
		$query .= "( pid, weekseq, use_cash, instant_item1, instant_item2, instant_item3, instant_item4, start_datetime ) values ( ";
		$query .= "'".$pid."', ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::SURVIVAL_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end, ";
		$query .= "".$use_cash.", ";
		$query .= "'".$instant_item1."', ";
		$query .= "'".$instant_item2."', ";
		$query .= "'".$instant_item3."', ";
		$query .= "'".$instant_item4."', ";
		$query .= "now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function updateLoggingResultStepForSURVIVAL( $pid, $logid, $score, $round )
	{
		$query = "update koc_record.".MY_Controller::TBL_SURVIVAL." set ";
		$query .= "score = '".$score."', ";
		$query .= "round = '".$round."', ";
		$query .= "result_datetime = now() ";
		$query .= "where pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid,"sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCheckExploration( $pid )
	{
		$query = "select max(exp_group_idx) as exp_group_idx from koc_record.".MY_Controller::TBL_EXPGRP." where pid = '".$pid."' and is_clear = 0 group by pid ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCreateExpGroup( $pid )
	{
		$query = "insert into koc_record.".MY_Controller::TBL_EXPGRP." ( pid, rn, is_clear, regist_datetime ) ";
		$query .= "select '".$pid."', floor(rand() * 9999), 0, now() from dual ";
		$query .= "where not exists ( select exp_group_idx from koc_record.".MY_Controller::TBL_EXPGRP." where pid = '".$pid."' and is_clear = 0 ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestCreateExpList( $pid, $group_idx )
	{
		$arrayGrade = json_decode(MY_Controller::ARRAY_GRADE_COUNT, true);
		$query = "select floor(".MY_Controller::MIN_EXP_PLANET_COUNT." + ";
		$query .= "rand() * ".(MY_Controller::MAX_EXP_PLANET_COUNT - MY_Controller::MIN_EXP_PLANET_COUNT).") as expcount";
		$expCount = $this->DB_INS->query($query)->result_array()[0]["expcount"];
		$idx = null;
		foreach( $arrayGrade as $key => $row )
		{
			$idx = array_search($expCount, $arrayGrade[$key]);
			if ( $idx )
			{
				break;
			}
		}

		$subtablequery = "";
		for ( $i = 1; $i <= $expCount; $i++ )
		{
			$subtablequery .= "select ".$i." ";
			if ( $i == 1 )
			{
				$subtablequery .= "as idx ";
			}
			if ( $i < $expCount )
			{
				$subtablequery .= "union all ";
			}
		}

		$query = "insert into koc_record.".MY_Controller::TBL_EXP." ";
		$query .= "( pid, exp_group_idx, exp_idx, is_enemy, grade, exp_ori_sec, exp_experience, exp_status, exp_cost, ";
		$query .= "reward_id ) ";
		$query .= "select '".$pid."', '".$group_idx."', (@row := @row + 1) as idx, if(e.idx is null, 0, 1) as is_enemy, b.grade, c.time, ";
		$query .= "c.exp, 0, b.grade * ".MY_Controller::EXP_COST_BASIC_MULTIPLE.", ";
		$query .= "if(e.idx is null, concat('RWEXP0', b.grade, '001'), concat('RWEXC0', b.grade, '001')) from ( ";
		$query .= "select idx, case ";
		$sumSubKey = 0;
		$prevSumSubKey = 0;
		for ( $subkey = 0; $subkey < count($arrayGrade[$key]["arrPlanet"]); $subkey++ )
		{
			if ( $subkey == 0 )
			{
				$query .= "when idx between 1 and ".($arrayGrade[$key]["arrPlanet"][$subkey]["pcount"] + $sumSubKey)." ";
				$query .= "then '".$arrayGrade[$key]["arrPlanet"][$subkey]["grade"]."' ";
			}
			else if ( $subkey == end(array_keys($arrayGrade[$key]["arrPlanet"])) )
			{
				$query .= "when idx between ".$sumSubKey." and ".$expCount." ";
				$query .= "then '".$arrayGrade[$key]["arrPlanet"][$subkey]["grade"]."' ";
			}
			else
			{
				$query .= "when idx between ".($sumSubKey + 1)." and ".($arrayGrade[$key]["arrPlanet"][$subkey]["pcount"] + $sumSubKey)." ";
				$query .= "then '".$arrayGrade[$key]["arrPlanet"][$subkey]["grade"]."' ";
			}
			$sumSubKey = $sumSubKey + $arrayGrade[$key]["arrPlanet"][$subkey]["pcount"];
		}
		$query .= "end as grade, rand() as rand_seq from ( ".$subtablequery.") ";
		$query .= "as a ) as b inner join koc_ref.".MY_Controller::TBL_EXPREF." as c on b.grade = c.grade left outer join ( ";
		$query .= "select idx from ( ".$subtablequery.") as d order by rand() limit 3 ) as e on b.idx = e.idx, (select @row := 0) as f ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestExplorationList( $pid, $result )
	{
		$query = "select a.exp_group_idx, b.rn, a.exp_idx, a.grade, ifnull(a.exp_second, 0) as exp_second, a.exp_experience, a.exp_cost, a.exp_ori_sec, ";
		$query .= "a.reward_type, a.reward_value, a.reward_datetime, cast(if(a.reward_type is null, -1, a.is_enemy) as signed) as is_enemy, c.exp_time ";
		$query .= "from koc_record.".MY_Controller::TBL_EXP." as a ";
		$query .= "inner join koc_record.".MY_Controller::TBL_EXPGRP." as b on a.exp_group_idx = b.exp_group_idx ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on a.exp_group_idx = c.exp_group_idx and a.exp_idx = c.exp_idx ";
		$query .= "where a.pid = '".$pid."' and a.exp_group_idx = '".$result."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestExplorationStart( $pid, $cid, $exp_group_idx, $exp_idx, $exp_time )
	{
		$query = "update koc_ref.".MY_Controller::TBL_EXPREF." as a inner join koc_record.".MY_Controller::TBL_EXP." as b on a.grade = b.grade ";
		$query .= "set b.cid = '".$cid."', b.exp_second = ".$exp_time.", b.start_datetime = now() ";
		$query .= "where b.pid = '".$pid."' and b.exp_group_idx = '".$exp_group_idx."' and b.exp_idx = '".$exp_idx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function getRewardIdFromExp( $pid, $exp_group_idx, $exp_idx )
	{
		$query = "select reward_id as reward, cast(is_enemy as signed) as is_enemy from koc_record.".MY_Controller::TBL_EXP." where exp_group_idx = '".$exp_group_idx."' ";
		$query .= "and exp_idx = '".$exp_idx."' and exists ( select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where ";
		$query .= "pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' and exp_idx = '".$exp_idx."' and exp_time <= now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateExplorationResult( $pid, $exp_group_idx, $exp_idx, $reward_type, $reward_value )
	{
		$query = "update koc_record.".MY_Controller::TBL_EXP." set ";
		$query .= "reward_type = '".$reward_type."', ";
		$query .= "reward_value = '".$reward_value."' ";
		$query .= "where pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' and exp_idx = '".$exp_idx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateExplorationReward( $pid, $exp_group_idx, $exp_idx )
	{
		$query = "update koc_record.".MY_Controller::TBL_EXP." set ";
		$query .= "reward_datetime = now() ";
		$query .= "where pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' and exp_idx = '".$exp_idx."' and reward_datetime is null ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestExplorationReward( $pid, $exp_group_idx, $exp_idx )
	{
		$query = "select a.reward_type, a.reward_value as attach_value, b.article_value from koc_record.".MY_Controller::TBL_EXP." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ARTICLE." as b on a.reward_type = b.article_id ";
		$query .= "where a.pid = '".$pid."' and a.exp_group_idx = '".$exp_group_idx."' and a.exp_idx = '".$exp_idx."' and a.reward_datetime is null ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function updateExplorationReset( $pid, $exp_group_idx, $exp_idx )
	{
		$query = "update koc_record.".MY_Controller::TBL_EXP." set ";
		$query .= "exp_second = null, ";
		$query .= "start_datetime = null ";
		$query .= "where pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' and exp_idx = '".$exp_idx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLogRewardInfo( $pid, $logid )
	{
		$query = "select idx from koc_record.".MY_Controller::TBL_PVE." where ";
		$query .= "random_reward_type is null and random_reward_value is null and random_reward_idx is null and pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestLogRetryRewardInfo( $pid, $logid )
	{
		$query = "select idx from koc_record.".MY_Controller::TBL_PVE." where ";
		$query .= "additional_reward_type is null and additional_reward_value is null and additional_reward_idx is null and pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function resetPlayerSURVIVAL( $pid )
	{
		$query = "delete from koc_record.".MY_Controller::TBL_PVP." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestExpGroupCheck( $pid, $exp_group_idx )
	{
		$query = "select count(exp_idx) as cnt from koc_record.".MY_Controller::TBL_EXP." where pid = '".$pid."' ";
		$query .= "and exp_group_idx = '".$exp_group_idx."' and is_enemy = 1 and reward_datetime < now() ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateExpGroupResult( $pid, $exp_group_idx, $reward_type, $reward_value )
	{
		$query = "update koc_record.".MY_Controller::TBL_EXPGRP." set reward_type = '".$reward_type."', reward_value = '".$reward_value."', ";
		$query .= "is_clear = 1, clear_datetime = now() ";
		$query .= "where pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLoggingStartPVP( $pid, $enemyPid, $use_cash, $instant_item1, $instant_item2, $instant_item3, $instant_item4 )
	{
		$query = "insert into koc_record.".MY_Controller::TBL_PVP." ";
		$query .= "( pid, weekseq, enemy_id, use_cash, instant_item1, instant_item2, instant_item3, instant_item4, start_datetime ) values ( ";
		$query .= "'".$pid."', ";
		$query .= "case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end, ";
		$query .= "'".$enemyPid."', ";
		$query .= "".$use_cash.", ";
		$query .= "'".$instant_item1."', ";
		$query .= "'".$instant_item2."', ";
		$query .= "'".$instant_item3."', ";
		$query .= "'".$instant_item4."', ";
		$query .= "now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}


	public function updateLoggingResultStepForPVP( $pid, $logid, $score, $is_clear )
	{
		$query = "update koc_record.".MY_Controller::TBL_PVP." set ";
		$query .= "score = ".$score.", ";
		$query .= "is_clear = '".$is_clear."', ";
		$query .= "result_datetime = now() ";
		$query .= "where pid = '".$pid."' and idx = '".$logid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestSeriesInfo( $pid )
	{
		$query = "select count(idx) as win_count, ifnull(sum( if( result_datetime > ";
		$query .= "( select ifnull(max( result_datetime ), '1900-01-01') from koc_record.".MY_Controller::TBL_PVP." where is_clear = -1 and pid = '".$pid."' ";
		$query .= "and weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) ), 1, 0 ) ), 0) as serial_win from koc_record.".MY_Controller::TBL_PVP." where pid = '".$pid."' and weekseq = ";
		$query .= "( case when dayofweek(now()) < ".MY_Controller::PVP_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) ";
		$query .= "and is_clear = 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestBossInfo( $pid )
	{
		$query = "select a.stageid, if(if(a.is_clear = 1, a.level + 1, a.level) > ".MY_Controller::MAX_LEVEL_PVB.", ".MY_Controller::MAX_LEVEL_PVB.", ";
		$query .= "if(a.is_clear = 1, a.level + 1, a.level)) as level, if(a.is_clear = 1, 0, ifnull(b.score, 0)) as off_hp ";
		$query .= "from koc_record.".MY_Controller::TBL_PVB." as a left outer join ( select level, sum(score) as score from koc_record.".MY_Controller::TBL_PVB." ";
		$query .= "where pid = '".$pid."' and weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) and idx > ( ";
		$query .= "select max(idx) from koc_record.".MY_Controller::TBL_PVB." where pid = '".$pid."' and is_clear = 1 and ";
		$query .= "weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then yearweek(date_add(now(), interval -7 day), 2) ";
		$query .= "else yearweek(now(), 2) end ) ) group by level ) as b on a.level = b.level ";
		$query .= "where a.pid = '".$pid."' and a.weekseq = ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) order by a.result_datetime desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestBossWeekSeq( )
	{
		$query = "select ( case when dayofweek(now()) < ".MY_Controller::PVB_YEARWEEK_STANDARD." then ";
		$query .= "yearweek(date_add(now(), interval -7 day), 2) else yearweek(now(), 2) end ) as weekseq ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function resetPlayerPVP( $pid )
	{
		$query = "delete from koc_record.".MY_Controller::TBL_PVP." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerExploration( $pid )
	{
		$query = "delete from koc_record.".MY_Controller::TBL_EXPGRP." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);

		$query = "delete from koc_record.".MY_Controller::TBL_EXP." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}
}
?>
