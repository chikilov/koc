<?php
class Model_Play extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->DB_SEL = $this->load->database("koc_play_sel", TRUE);
		$this->DB_INS = $this->load->database("koc_play_ins", TRUE);

		$this->DB_SEL->trans_strict(FALSE);
		$this->DB_INS->trans_strict(FALSE);

		$this->DB_SEL->query("SET NAMES utf8");
		$this->DB_INS->query("SET NAMES utf8");
	}

	public function __destruct() {
		$this->DB_SEL->close();
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

	public function requestFirstLogin( $pid )
	{
		$query = "select pid from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestJoinStep2( $pid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERBASIC." ( pid, name, affiliate_name, show_prof, prof_img, vip_level, vip_exp, ";
		$query .= "inc_cha, inc_wea, inc_bck, inc_skl, inc_exp, inc_eng, inc_fri, inc_pvp, inc_pvb, inc_survival ) ";
		$query .= "select '".$pid."', null, null, 1, null, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestHelpCount( $pid )
	{
		$query = "select a.show_prof, count(b.result_datetime) as helpcount from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a ";
		$query .= "left outer join koc_record.".MY_Controller::TBL_PVE." as b on a.pid = b.friendid ";
		$query .= "and (b.result_datetime between a.login_datetime and now() or b.result_datetime is null) ";
		$query .= "where a.pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestUpdateNamePlay( $pid, $name )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set name = '".$name."' where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateLoginTimeForMe( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set login_datetime = now() ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateLoginTimeForFriend( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set login_datetime = now() ";
		$query .= "where fid = '".$pid."' and friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateAffiliateFriendInfo( $pid, $affiliateName, $affiliateProfImg )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set login_datetime = now(), faffiliate_name = '".$affiliateName."',  ";
		$query .= "fprof_img = '".$affiliateProfImg."' where fid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestPlayerSel( $pid )
	{
		$query = "select name, show_prof, if(show_prof, prof_img, '') as prof_img, vip_level, vip_exp, inc_cha, inc_wea, inc_bck, inc_skl, inc_exp, inc_eng, inc_fri, ";
		$query .= "inc_pvp, inc_pvb, inc_survival, operator, show_prof ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPlayerIns( $pid )
	{
		$query = "select name, show_prof, if(show_prof, prof_img, '') as prof_img, vip_level, vip_exp, inc_cha, inc_wea, inc_bck, inc_skl, inc_exp, inc_eng, inc_fri, ";
		$query .= "inc_pvp, inc_pvb, inc_survival, operator, show_prof ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestUpdateShowProfile( $pid, $show_prof )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set show_prof = ".$show_prof." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestPVPEquipment( $pid )
	{
		$query = "select concat(ifnull(a.name, ''), '(', ifnull(a.affiliate_name, ''), ')') as name, c.refid as op, d.refid as pilot_0, e.refid as pilot_1, f.refid as pilot_2 ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a ";
		$query .= "inner join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c on a.operator = c.idx ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as d on b.pilot_0 = d.idx ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as e on b.pilot_1 = e.idx ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as f on b.pilot_2 = f.idx ";
		$query .= "where a.pid = '".$pid."' and b.team_seq = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestInventory( $pid )
	{
		$query = "select idx, refid, expire ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." ";
		$query .= "where pid = '".$pid."' and is_del = 0 and (expire > now() or expire is null) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacters( $pid )
	{
		$query = "select idx, refid, level, exp, weapon, backpack, skill_0, skill_1, skill_2, ";
		$query .= "up_grade, up_refid, up_incentive, exp_group_idx, exp_idx, exp_time ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacter( $pid, $cid )
	{
		$query = "select idx, refid, level, exp, weapon, backpack, skill_0, skill_1, skill_2, ";
		$query .= "up_grade, up_refid, up_incentive, exp_group_idx, exp_idx, exp_time ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestItem( $pid )
	{
		$query = "select energy_points, pvb_points, pvp_points, survival_points, game_points, cash_points, ifnull(event_points, 0) as event_points, ";
		$query .= "friendship_points, energy_uptime, pvb_uptime, pvp_uptime, survival_uptime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERITEM." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestItemWithTime( $pid )
	{
		$query = "select energy_points, pvb_points, pvp_points, survival_points, ";
		$query .= "energy_uptime, pvb_uptime, pvp_uptime, survival_uptime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERITEM." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestTeam( $pid )
	{
		$query = "select team_seq, ifnull(memb_0, 0) as memb_0, ifnull(memb_1, 0) as memb_1, ifnull(memb_2, 0) as memb_2, ";
		$query .= "tact_0, tact_1, tact_2, pilot_0, pilot_1, pilot_2 ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERTEAM." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPVPTeam( $pid )
	{
		$query = "select a.idx, a.refid, a.level, a.exp, a.up_grade, a.up_refid, ";
		$query .= "max(if(a.weapon = b.idx, b.refid, null)) as weapon, max(if(a.backpack = b.idx, b.refid, null)) as backpack, ";
		$query .= "max(if(a.skill_0 = b.idx, b.refid, null)) as skill_0, max(if(a.skill_1 = b.idx, b.refid, null)) as skill_1, ";
		$query .= "max(if(a.skill_2 = b.idx, b.refid, null)) as skill_2, c.tact from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b ";
		$query .= "on a.pid = b.pid and ( a.weapon = b.idx or a.backpack = b.idx or a.skill_0 = b.idx or a.skill_1 = b.idx or a.skill_2 = b.idx ) ";
		$query .= "and b.is_del = 0 and cast(ifnull(b.expire, '9999-12-31 23:59:59') as datetime) > now() ";
		$query .= "inner join ( select memb_0 as idx, tact_0 as tact from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = 0 union ";
		$query .= "select memb_1, tact_1 from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = 0 union ";
		$query .= "select memb_2, tact_2 from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = 0 ) as c on a.idx = c.idx ";
		$query .= "group by a.idx, a.refid, a.level, a.exp, a.up_grade, a.up_refid ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestTeamExistCheck( $pid, $teamSeq )
	{
		$query = "select team_seq from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = '".$teamSeq."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}
	public function requestUpdateTact( $pid, $tactInfo )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERTEAM." set ";
		$query .= "tact_0 = case ";
		if ( array_key_exists("00", $tactInfo) )
		{
			$query .= "when team_seq = 0 then '".$tactInfo["00"]."' ";
		}
		if ( array_key_exists("10", $tactInfo) )
		{
			$query .= "when team_seq = 1 then '".$tactInfo["10"]."' ";
		}
		if ( array_key_exists("20", $tactInfo) )
		{
			$query .= "when team_seq = 2 then '".$tactInfo["20"]."' ";
		}
		$query .= "end, tact_1 = case ";
		if ( array_key_exists("01", $tactInfo) )
		{
			$query .= "when team_seq = 0 then '".$tactInfo["01"]."' ";
		}
		if ( array_key_exists("11", $tactInfo) )
		{
			$query .= "when team_seq = 1 then '".$tactInfo["11"]."' ";
		}
		if ( array_key_exists("21", $tactInfo) )
		{
			$query .= "when team_seq = 2 then '".$tactInfo["21"]."' ";
		}
		$query .= "end, tact_2 = case ";
		if ( array_key_exists("02", $tactInfo) )
		{
			$query .= "when team_seq = 0 then '".$tactInfo["02"]."' ";
		}
		if ( array_key_exists("12", $tactInfo) )
		{
			$query .= "when team_seq = 1 then '".$tactInfo["12"]."' ";
		}
		if ( array_key_exists("22", $tactInfo) )
		{
			$query .= "when team_seq = 2 then '".$tactInfo["22"]."' ";
		}
		$query .= "end where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestChangePilot( $pid, $slotseq, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERTEAM." set ";
		if ( $iid == 0 )
		{
			$query .= "pilot_".substr($slotseq, 1, 1)." = null ";
		}
		else
		{
			$query .= "pilot_".substr($slotseq, 1, 1)." = '".$iid."' ";
		}
		$query .= "where pid = '".$pid."' and team_seq = '".substr($slotseq, 0, 1)."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestDeployCharacters( $pid, $teamInfo )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERTEAM." set ";
		$query .= "memb_0 = case ";
		if ( array_key_exists("00", $teamInfo) )
		{
			if ( $teamInfo["00"] == 0 )
			{
				$query .= "when team_seq = 0 then null ";
			}
			else
			{
				$query .= "when team_seq = 0 then '".$teamInfo["00"]."' ";
			}
		}
		if ( array_key_exists("10", $teamInfo) )
		{
			if ( $teamInfo["10"] == 0 )
			{
				$query .= "when team_seq = 1 then null ";
			}
			else
			{
				$query .= "when team_seq = 1 then '".$teamInfo["10"]."' ";
			}
		}
		if ( array_key_exists("20", $teamInfo) )
		{
			if ( $teamInfo["20"] == 0 )
			{
				$query .= "when team_seq = 2 then null ";
			}
			else
			{
				$query .= "when team_seq = 2 then '".$teamInfo["20"]."' ";
			}
		}
		$query .= "end, memb_1 = case ";
		if ( array_key_exists("01", $teamInfo) )
		{
			if ( $teamInfo["01"] == 0 )
			{
				$query .= "when team_seq = 0 then null ";
			}
			else
			{
				$query .= "when team_seq = 0 then '".$teamInfo["01"]."' ";
			}
		}
		if ( array_key_exists("11", $teamInfo) )
		{
			if ( $teamInfo["11"] == 0 )
			{
				$query .= "when team_seq = 1 then null ";
			}
			else
			{
				$query .= "when team_seq = 1 then '".$teamInfo["11"]."' ";
			}
		}
		if ( array_key_exists("21", $teamInfo) )
		{
			if ( $teamInfo["21"] == 0 )
			{
				$query .= "when team_seq = 2 then null ";
			}
			else
			{
				$query .= "when team_seq = 2 then '".$teamInfo["21"]."' ";
			}
		}
		$query .= "end, memb_2 = case ";
		if ( array_key_exists("02", $teamInfo) )
		{
			if ( $teamInfo["02"] == 0 )
			{
				$query .= "when team_seq = 0 then null ";
			}
			else
			{
				$query .= "when team_seq = 0 then '".$teamInfo["02"]."' ";
			}
		}
		if ( array_key_exists("12", $teamInfo) )
		{
			if ( $teamInfo["12"] == 0 )
			{
				$query .= "when team_seq = 1 then null ";
			}
			else
			{
				$query .= "when team_seq = 1 then '".$teamInfo["12"]."' ";
			}
		}
		if ( array_key_exists("22", $teamInfo) )
		{
			if ( $teamInfo["22"] == 0 )
			{
				$query .= "when team_seq = 2 then null ";
			}
			else
			{
				$query .= "when team_seq = 2 then '".$teamInfo["22"]."' ";
			}
		}
		$query .= "end where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCharactersExists( $pid, $arrIdx )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and idx in ('".join("', '", $arrIdx)."') and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestCharacterExists( $pid, $cid )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestCharactersExistForSales( $pid, $cid )
	{
/* 튜닝 및 간단히 확인된 쿼리이나 확신이 서지 않음
		$query = "select a.idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid ";
		$query .= "where a.pid = '".$pid."' and a.idx = '".$cid."' ";
		$query .= "and a.is_del = 0 and (a.exp_idx is null or a.exp_idx < 1) ";
		$query .= "and a.idx != ifnull(b.memb_0, 0) and a.idx != ifnull(b.memb_1, 0) and a.idx != ifnull(b.memb_2, 0) ";
		$query .= "group by a.idx, a.grade, a.level, a.refid, a.weapon, a.backpack, a.skill_0, a.skill_1, a.skill_2 having count(a.idx) >= 3 ";
*/
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx = '".$cid."' ";
		$query .= "and is_del = 0 and (exp_idx is null or exp_idx < 1) ";
		$query .= "and idx not in ( select ifnull(memb_0, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_1, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_2, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestItemExists( $pid, $arrIdx )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."' and idx in ('".join("', '", $arrIdx)."') ";
		$query .= "and is_del = 0 and expire > now() ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestItemExistsWithRef( $pid, $refid, $type )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." ";
		$query .= "where pid = '".$pid."' and refid = '".$refid."' and is_del = 0 and expire > now() ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );

		if ( $type == "count" )
		{
			$this->DB_SEL->query($query);
			return $this->DB_SEL->affected_rows();
		}
		else
		{
			return $this->DB_SEL->query($query);
		}
	}

	public function requestItemRefWithIdx( $pid, $idx )
	{
		$query = "select refid from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."' and idx = '".$idx."' ";
		$query .= "and is_del = 0 and expire > now() ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacterGradeLev( $pid, $idx )
	{
/* 튜닝 및 간단히 확인된 쿼리이나 확신이 서지 않음
		$query = "select a.idx, a.grade, a.level, a.refid, a.weapon, a.backpack, a.skill_0, a.skill_1, a.skill_2 from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid ";
		$query .= "where a.pid = '".$pid."' and a.idx = '".$idx."' ";
		$query .= "and a.is_del = 0 and (a.exp_idx is null or a.exp_idx < 1) ";
		$query .= "and a.idx != ifnull(b.memb_0, 0) and a.idx != ifnull(b.memb_1, 0) and a.idx != ifnull(b.memb_2, 0) ";
		$query .= "group by a.idx, a.grade, a.level, a.refid, a.weapon, a.backpack, a.skill_0, a.skill_1, a.skill_2 having count(a.idx) >= 3 ";
*/
		$query = "select idx, grade, level, refid, weapon, backpack, skill_0, skill_1, skill_2 from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx = '".$idx."' ";
		$query .= "and is_del = 0 and (exp_idx is null or exp_idx < 1) ";
		$query .= "and idx not in ( select ifnull(memb_0, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_1, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_2, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestItemGrade( $pid, $idx )
	{
		$query = "select idx, grade, refid from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."' and idx = '".$idx."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestEquipToChar( $pid, $cid, $slotseq, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		$query .= $slotseq." = '".$iid."' ";
		$query .= "where pid = '".$pid."' and idx = '".$cid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEquipAvailableCheck( $pid, $cid, $slotseq, $iid )
	{
		$query = "select idx, ".$slotseq." from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where not exists( select idx ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and '".$iid."' ";
		if ( strcasecmp($slotseq, "WEAPON") == 0 )
		{
			$query .= "= weapon ";
		}
		else if ( strcasecmp($slotseq, "BACKPACK") == 0 )
		{
			$query .= "= backpack ";
		}
		else
		{
			$query .= "in ( skill_0, skill_1, skill_2 )";
		}
		$query .= ") and idx = '".$cid."' and ";
		$query .= "exists(select idx from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where idx = '".$iid."' and is_del = 0)";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestEquipDelCheck( $pid, $iid )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where idx = '".$iid."' and pid = '".$pid."' ";
		$query .= "and is_del = 0 and ( expire >= now() or expire is null ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function deleteInventory( $pid, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERINVENTORY." set is_del = 1 where pid = '".$pid."' and idx = '".$iid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAttendEvent( $pid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERATTEND." ";
		$query .= "select pid, attend_date, attend_count, attend_datetime from ( select '".$pid."' as pid, cast(now() as date) as attend_date, ";
		$query .= "ifnull(max(attend_count), 0) + 1 as attend_count, now() as attend_datetime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERATTEND." where pid = '".$pid."' ) as a where not exists( ";
		$query .= "select pid from koc_play.".MY_Controller::TBL_PLAYERATTEND." where pid = '".$pid."' and attend_date = cast(now() as date) ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestExtraAttendEvent( $pid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYEREXTRAATTEND." ";
		$query .= "select pid, attend_date, attend_count, attend_datetime from ( select '".$pid."' as pid, cast(now() as date) as attend_date, ";
		$query .= "ifnull(max(attend_count), 0) + 1 as attend_count, now() as attend_datetime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYEREXTRAATTEND." where pid = '".$pid."' ) as a where not exists( ";
		$query .= "select pid from koc_play.".MY_Controller::TBL_PLAYEREXTRAATTEND." where pid = '".$pid."' and attend_date = cast(now() as date) ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLastAttend( $pid )
	{
		$query = "select ifnull(max(attend_count), 1) as attend_count from koc_play.".MY_Controller::TBL_PLAYERATTEND." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestLastExtraAttend( $pid )
	{
		$query = "select ifnull(max(attend_count), 1) as attend_count from koc_play.".MY_Controller::TBL_PLAYEREXTRAATTEND." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function characterProvision( $pid, $cid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ( pid, refid, grade, level, exp, up_grade, up_incentive, is_del, reg_date) ";
		$query .= "select '".$pid."' as pid, '".$cid."' as refid, grade as grade, '1' as level, '0' as exp, '0' as up_grade, '0.00' as up_incentive, ";
		$query .= "0 as is_del, now() as reg_date ";
		$query .= "from koc_ref.".MY_Controller::TBL_REFCHARACTER." where id = '".$cid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function collectionProvision( $pid, $cid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." ( pid, cid, clevel ) ";
		$query .= "select '".$pid."' as pid, '".$cid."' as cid, '1' as clevel from dual ";
		$query .= "where not exists (select cid from koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." where pid = '".$pid."' and cid = '".$cid."')";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function inventoryProvision( $pid, $iid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERINVENTORY." ( pid, refid, grade, expire, is_del, reg_date) ";
		$query .= "select '".$pid."' as pid, '".$iid."' as refid, grade, ";
		if ( $iid == "OP01000008" )
		{
			$query .= "'2015-02-28 23:59:59' as expire, 0 as is_del, now() as reg_date ";
		}
		else
		{
			$query .= "if(duration = '0', null, date_add(now(), interval duration hour)) as expire, 0 as is_del, now() as reg_date ";
		}
		$query .= "from koc_ref.".MY_Controller::TBL_ITEM." where id = '".$iid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestInventoryExpire( $pid, $iid )
	{
		$query = "select expire from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."' and idx = '".$iid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function paymentCharge( $pid, $payment_type, $payment, $inc_eng, $inc_pvb, $inc_pvp, $inc_survival )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		if ( strtoupper($payment_type) == "ENERGY_POINTS" )
		{
			$query .= "ENERGY_UPTIME = if( ".$payment_type." - ".$payment." < ".( MY_Controller::MAX_ENERGY_POINTS + $inc_eng ).", ";
			$query .= "if( energy_uptime is null, now(), energy_uptime), null), ";
		}
		else if ( strtoupper($payment_type) == "PVB_POINTS" )
		{
			$query .= "PVB_UPTIME = if( ".$payment_type." - ".$payment." < ".( MY_Controller::MAX_MODES_PVB + $inc_pvb ).", ";
			$query .= "if( pvb_uptime is null, now(), pvb_uptime), null), ";
		}
		else if ( strtoupper($payment_type) == "PVP_POINTS" )
		{
			$query .= "PVP_UPTIME = if( ".$payment_type." - ".$payment." < ".( MY_Controller::MAX_MODES_PVP + $inc_pvp ).", ";
			$query .= "if( pvp_uptime is null, now(), pvp_uptime), null), ";
		}
		else if ( strtoupper($payment_type) == "SURVIVAL_POINTS" )
		{
			$query .= "SURVIVAL_UPTIME = if( ".$payment_type." - ".$payment." < ".( MY_Controller::MAX_MODES_SURVIVAL + $inc_survival ).", ";
			$query .= "if( survival_uptime is null, now(), survival_uptime), null), ";
		}

		$query .= "".$payment_type." = ".$payment_type." - ".$payment." ";
		$query .= "where pid = '".$pid."' and ".$payment_type." >= ".$payment." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updatePlayerPoint( $pid, $point_type, $point_value, $inc_eng, $inc_pvb, $inc_pvp, $inc_survival )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		if ( strtoupper($point_type) == "ENERGY_POINTS" )
		{
			$query .= "ENERGY_UPTIME = if( ".$point_type." + ".$point_value." < ".( MY_Controller::MAX_ENERGY_POINTS + $inc_eng ).", ";
			$query .= "if( energy_uptime is null, now(), ENERGY_UPTIME), null), ";
		}
		else if ( strtoupper($point_type) == "PVB_POINTS" )
		{
			$query .= "PVB_UPTIME = if( ".$point_type." + ".$point_value." < ".( MY_Controller::MAX_MODES_PVB + $inc_pvb ).", ";
			$query .= "if( energy_uptime is null, now(), PVB_UPTIME), null), ";
		}
		else if ( strtoupper($point_type) == "PVP_POINTS" )
		{
			$query .= "PVP_UPTIME = if( ".$point_type." + ".$point_value." < ".( MY_Controller::MAX_MODES_PVP + $inc_pvp ).", ";
			$query .= "if( energy_uptime is null, now(), PVP_UPTIME), null), ";
		}
		else if ( strtoupper($point_type) == "SURVIVAL_POINTS" )
		{
			$query .= "SURVIVAL_UPTIME = if( ".$point_type." + ".$point_value." < ".( MY_Controller::MAX_MODES_SURVIVAL + $inc_survival ).", ";
			$query .= "if( energy_uptime is null, now(), SURVIVAL_UPTIME), null), ";
		}
		$query .= "".$point_type." = ifnull( ".$point_type.", 0 ) + ".$point_value." ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function deletePlayerCharacter( $pid, $idx )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		$query .= "is_del = 1 where pid = '".$pid."' and idx = '".$idx."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function deleteCharacterTeam( $pid, $cid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERTEAM." set memb_0 = if( memb_0 = ".$cid.", null, memb_0 ), ";
		$query .= "memb_1 = if( memb_1 = ".$cid.", null, memb_1 ), memb_2 = if( memb_2 = ".$cid.", null, memb_2 ) ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function deletePlayerItem( $pid, $idx )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERINVENTORY." set ";
		$query .= "is_del = 1 where pid = '".$pid."' and idx = '".$idx."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateCharacter( $pid, $cid, $clev, $cexp )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		$query .= "level = '".$clev."', ";
		$query .= "exp = ".$cexp." ";
		$query .= "where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateCollection( $pid, $cid, $clev )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." set ";
		$query .= "clevel = if( '".$clev."' > clevel, '".$clev."', clevel ) ";
		$query .= "where cid = '".$cid."' and pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCollection( $pid, $crefid )
	{
		$query = "select is_reward from koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." where pid = '".$pid."' and cid = '".$crefid."' limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestCollectionReward( $pid, $crefid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." set is_reward = 1 where pid = '".$pid."' and cid = '".$crefid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function revisionItemTime( $pid, $inc_eng, $inc_pvb, $inc_pvp, $inc_survival )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." ";
		$query .= "set energy_uptime = if( energy_points < ".( MY_Controller::MAX_ENERGY_POINTS + $inc_eng ).", ";
		$query .= "if( energy_uptime is null, now(), energy_uptime), null ), ";
		$query .= "pvb_uptime = if( pvb_points < ".( MY_Controller::MAX_MODES_PVB + $inc_pvb ).", ";
		$query .= "if( pvb_uptime is null, now(), pvb_uptime), null ), ";
		$query .= "pvp_uptime = if( pvp_points < ".( MY_Controller::MAX_MODES_PVP + $inc_pvp ).", ";
		$query .= "if( pvp_uptime is null, now(), pvp_uptime), null ), ";
		$query .= "survival_uptime = if( survival_points < ".( MY_Controller::MAX_MODES_SURVIVAL + $inc_survival ).", ";
		$query .= "if( survival_uptime is null, now(), survival_uptime), null ) ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function getCurrentTimeUTC()
	{
		$query = "select utc_timestamp() as curTime from dual ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function getCurrentTime()
	{
		$query = "select now() as curTime from dual ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateEnergyTime( $pid, $curDataArray )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		$query .= "energy_points = '".$curDataArray["energy_points"]."', ";
		$query .= "pvb_points = '".$curDataArray["pvb_points"]."', ";
		$query .= "pvp_points = '".$curDataArray["pvp_points"]."', ";
		$query .= "survival_points = '".$curDataArray["survival_points"]."'";
		if ( $curDataArray["energy_uptime"] < 0 )
		{
			$query .= ", energy_uptime = null";
		}
		else
		{
			$query .= ", energy_uptime = date_add(now(), interval -".$curDataArray["energy_uptime"]." second)";
		}
		if ( $curDataArray["pvb_uptime"] < 0 )
		{
			$query .= ", pvb_uptime = null";
		}
		else
		{
			$query .= ", pvb_uptime = date_add(now(), interval -".$curDataArray["pvb_uptime"]." second)";
		}
		if ( $curDataArray["pvp_uptime"] < 0 )
		{
			$query .= ", pvp_uptime = null";
		}
		else
		{
			$query .= ", pvp_uptime = date_add(now(), interval -".$curDataArray["pvp_uptime"]." second)";
		}
		if ( $curDataArray["survival_uptime"] < 0 )
		{
			$query .= ", survival_uptime = null";
		}
		else
		{
			$query .= ", survival_uptime = date_add(now(), interval -".$curDataArray["survival_uptime"]." second)";
		}
		$query .= " where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateTime( $pid, $upCol, $uptime )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		if ( $uptime )
		{
			$query .= "".$upCol." = date_add(now(), interval -".$uptime." second) ";
		}
		else
		{
			$query .= "".$upCol." = null ";
		}
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updatePointTime( $pid, $upCol, $upval, $uptime )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		$query .= "".$upCol."_POINTS = '".$upval."', ";
		if ( $uptime )
		{
			$query .= "".$upCol."_UPTIME = date_add(now(), interval -".$uptime." second) ";
		}
		else
		{
			$query .= "".$upCol."_UPTIME = null ";
		}
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestUpdateUpgradeInfo( $pid, $idx, $up_grade, $up_refid, $up_incentive )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		if ( $up_incentive == 0 )
		{
			$query .= "up_incentive = 0";
		}
		else
		{
			$query .= "up_incentive = up_incentive + '".$up_incentive."'";
		}
		if ( $up_grade )
		{
			$query .= ", up_grade = '".$up_grade."'";
		}
		if ( $up_refid )
		{
			$query .= ", up_refid = '".$up_refid."'";
		}
		$query .= " where pid = '".$pid."' and idx = '".$idx."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCharacterResult( $pid, $idx )
	{
		$query = "select up_grade, up_refid, up_incentive from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx = '".$idx."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function IsEmptySpace( $pid, $IsEmptySpace, $count )
	{
		if ( $IsEmptySpace == "CHARACTER" )
		{
			$query = "select a.inc_cha + ".MY_Controller::MAX_CHAR_CAPACITY." >= ifnull( count(b.idx), 0 ) + ".$count." as is_empty ";
			$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a ";
			$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b on a.pid = b.pid and b.is_del = 0 ";
			$query .= "where a.pid = '".$pid."' group by a.inc_cha, a.pid ";
		}
		else if ( $IsEmptySpace == "WEAPON" )
		{
			$query = "select a.inc_wea + ".MY_Controller::MAX_WEPN_CAPACITY." >= ifnull( sum(if( d.idx is null, 1, 0 )), 0 ) + ".$count." as is_empty ";
			$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join ( select c.pid, c.idx ";
			$query .= "from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c ";
			$query .= "inner join koc_ref.".MY_Controller::TBL_ITEM." as d on c.refid = d.id where d.category IN ( 'WEAPON', 'BACKPACK', 'TECHNIQUE' ) ";
			$query .= "and c.is_del = 0 and c.pid = '".$pid."' ";
			$query .= ") as b on a.pid = b.pid left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as d ";
			$query .= "on a.pid = d.pid and ( b.idx = d.weapon or b.idx = d.backpack or b.idx = d.skill_0 or b.idx = d.skill_1 or b.idx = d.skill_2 ) ";
			$query .= "where a.pid = '".$pid."' group by a.pid ";
		}
		else
		{
			$query = "select 1 as is_empty from dual ";
		}

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharactersSynthesize( $pid, $arrIdx )
	{
/* 튜닝 및 간단히 확인된 쿼리이나 확신이 서지 않음
		$query = "select a.idx, a.grade from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid ";
		$query .= "where a.pid = '".$pid."' and a.idx in ('".join("', '", $arrIdx)."') ";
		$query .= "and a.up_grade = ".MY_Controller::MAX_UPGRADE." and a.level = ".MY_Controller::MAX_LEVEL." and a.is_del = 0 and (a.exp_idx is null or a.exp_idx < 1) ";
		$query .= "and a.idx != ifnull(b.memb_0, 0) and a.idx != ifnull(b.memb_1, 0) and a.idx != ifnull(b.memb_2, 0) ";
		$query .= "group by a.idx, a.grade, a.level, a.refid, a.weapon, a.backpack, a.skill_0, a.skill_1, a.skill_2 having count(a.idx) >= 3 ";
*/
		$query = "select idx, grade from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx in ('".join("', '", $arrIdx)."') ";
		$query .= "and up_grade = ".MY_Controller::MAX_UPGRADE." and level = ".MY_Controller::MAX_LEVEL." and is_del = 0 and (exp_idx is null or exp_idx < 1) ";
		$query .= "and idx not in ( select ifnull(memb_0, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_1, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_2, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharactersEvolution( $pid, $arrIdx )
	{
/* 튜닝 및 간단히 확인된 쿼리이나 확신이 서지 않음
		$query = "select a.idx, a.grade from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.pid = b.pid ";
		$query .= "where a.pid = '".$pid."' and a.idx in ('".join("', '", $arrIdx)."') ";
		$query .= "and a.up_grade = ".MY_Controller::MAX_UPGRADE." and a.level = ".MY_Controller::MAX_LEVEL." and a.is_del = 0 and (a.exp_idx is null or a.exp_idx < 1) ";
		$query .= "and a.idx != ifnull(b.memb_0, 0) and a.idx != ifnull(b.memb_1, 0) and a.idx != ifnull(b.memb_2, 0) ";
		$query .= "group by a.idx, a.grade, a.level, a.refid, a.weapon, a.backpack, a.skill_0, a.skill_1, a.skill_2 having count(a.idx) >= 3 ";
*/
		$query = "select idx, grade from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and idx in ('".join("', '", $arrIdx)."') ";
		$query .= "and up_grade = ".MY_Controller::MAX_UPGRADE." and level = ".MY_Controller::MAX_LEVEL." and is_del = 0 and (exp_idx is null or exp_idx < 1) ";
		$query .= "and idx not in ( select ifnull(memb_0, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_1, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' union ";
		$query .= "select ifnull(memb_2, 0) from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestDailyAchieveInsert( $pid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ";
		$query .= "(pid, aid, groupid, astatus, agoal, is_reward, start_datetime, reward_datetime) ";
		$query .= "select '".$pid."', id, groupid, 0, point, 0, now(), null from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." ";
		$query .= "where repeate = '".MY_Controller::ACHIEVE_REPEATE_FOR_DAILY."' and ( required_1 is null or required_1 = '' ) ";
		$query .= "and not exists (select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b ";
		$query .= "on a.aid = b.id where a.pid = '".$pid."' and b.repeate = '".MY_Controller::ACHIEVE_REPEATE_FOR_DAILY."' ";
		$query .= "and date_format(a.start_datetime, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')) order by rand() desc limit 5 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestDailyAchieveList( $pid )
	{
		$query = "select aid, astatus, agoal, is_reward ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id ";
		$query .= "where pid = '".$pid."' and b.repeate = '".MY_Controller::ACHIEVE_REPEATE_FOR_DAILY."' ";
		$query .= "and date_format(start_datetime, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestAchieveList( $pid )
	{
		$query = "select aid, astatus, agoal, is_reward ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id ";
		$query .= "where pid = '".$pid."' and b.repeate != '".MY_Controller::ACHIEVE_REPEATE_FOR_DAILY."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestAchieveInsert( $pid, $aid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ";
		$query .= "(pid, aid, groupid, astatus, agoal, is_reward, start_datetime, reward_datetime) ";
		$query .= "select '".$pid."', id, groupid, 0, point, 0, now(), null from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." ";
		$query .= "where id = '".$aid."' and not exists( select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ";
		$query .= "where pid = '".$pid."' and aid = '".$aid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAchieveNewInsert( $pid, $aid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERACHIEVE." (pid, aid, groupid, astatus, agoal, is_reward, start_datetime, reward_datetime) ";
		$query .= "select distinct '".$pid."', a.id, a.groupid, 0, a.point, 0, now(), null from koc_ref.achievements as a ";
		$query .= "left outer join ( select aid, start_datetime from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." where pid = '".$pid."' ) as b on a.id = b.aid ";
		$query .= "where a.required_1 in ('".join("', '", $aid)."') and not exists ( ";
		$query .= "select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ";
		$query .= "where aid in ( select id from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." where required_1 in ('".join("', '", $aid)."') ) ";
		$query .= "and pid = '".$pid."' and left(now(), 10) = left(start_datetime, 10) ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAchieveStatusUpdate( $pid, $aid, $astatus )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b ";
		$query .= "on a.aid = b.id set astatus = '".$astatus."' ";
		$query .= "where a.pid = '".$pid."' and a.aid = '".$aid."' ";
		$query .= "and date_format(a.start_datetime, '%Y-%m-%d') = ";
		$query .= "if(b.repeate = 'DAILY', date_format(now(), '%Y-%m-%d'), date_format(start_datetime, '%Y-%m-%d')) and a.is_reward = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAchieveReward( $pid, $aid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id ";
//		$query .= "set is_reward = 1, reward_datetime = now() ";
//		$query .= "where pid = '".$pid."' and aid in ('".join("', '", $aid)."') and astatus >= agoal and is_reward = 0 ";
// 배포시 주의 !!!
		$query .= "set is_reward = 1, reward_datetime = now(), astatus = agoal ";
		$query .= "where pid = '".$pid."' and aid in ('".join("', '", $aid)."') and is_reward = 0 ";
		$query .= "and case when b.repeate = '".MY_Controller::ACHIEVE_REPEATE_FOR_DAILY."' then date_format(start_datetime, '%Y%m%d') ";
		$query .= "else date_format(now(), '%Y%m%d') end = date_format(now(), '%Y%m%d') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestNextAchieveInsert( $pid, $aid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERACHIEVE." (pid, aid, groupid, astatus, agoal, is_reward, start_datetime, reward_datetime) ";
		$query .= "select distinct '".$pid."', a.id, a.groupid, 0, a.point, 0, now(), null from koc_ref.achievements as a ";
		$query .= "left outer join ( select aid, start_datetime from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." where pid = '".$pid."' ) as b on a.id = b.aid ";
		$query .= "where a.required_1 in ('".join("', '", $aid)."') and not exists ( ";
		$query .= "select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ";
		$query .= "where aid in ( select id from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." where required_1 in ('".join("', '", $aid)."') ) ";
		$query .= "and pid = '".$pid."' ) and case when a.repeate = 'DAILY' then date_format(b.start_datetime, '%Y%m%d') ";
		$query .= "else date_format(now(), '%Y%m%d') end = date_format(now(), '%Y%m%d') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestDailyAchieveRewardCount( $pid, $aid )
	{
		$query = "select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." where ";
		$query .= "pid = '".$pid."' and aid = '".$aid."' ";
		$query .= "and date_format(start_datetime, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') and is_reward = 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestInsertNextArchieve( $pid, $aid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERACHIEVE." ( pid, aid, groupid, astatus, agoal, is_reward, start_datetime, reward_datetime ) ";
		$query .= "select '".$pid."', id, groupid, 0, point, 0, now(), null from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." where required_1 = '".$aid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestAchieveRewardCount( $pid, $aid )
	{
		$query = "select aid from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." where ";
		$query .= "pid = '".$pid."' and aid = '".$aid."' ";
		$query .= "and is_reward = 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCheckCharacterCanExp( $pid, $cid )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 and exp_time is null ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestCheckExpCanExp( $pid, $exp_group_idx, $exp_idx )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and exp_group_idx = '".$exp_group_idx."' and exp_idx = '".$exp_idx."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestExplorationTime( $pid, $cid )
	{
		$query = "select exp_time from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacterExploration( $pid, $cid, $exp_group_idx, $exp_idx, $exp_time )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		$query .= "exp_group_idx = '".$exp_group_idx."', ";
		$query .= "exp_idx = '".$exp_idx."', ";
		$query .= "exp_time = (select date_add(now(), interval ".$exp_time." second )) ";
		$query .= "where idx = '".$cid."' and pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCharacterExplorationInitialize( $pid, $cid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ";
		$query .= "exp_group_idx = null, ";
		$query .= "exp_idx = null, ";
		$query .= "exp_time = null ";
		$query .= "where idx = '".$cid."' and pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestCharacterExp( $pid, $cid )
	{
		$query = "select exp from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCollectionList( $pid )
	{
		$query = "select cid, clevel from koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestInitCharExpInfo( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set exp_group_idx = null, exp_idx = null, exp_time = null where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestRecomFriendList( $pid, $searchVal )
	{
		$query = "select f.pid, f.name, if(f.show_prof, f.prof_img, '') as prof_img, f.refid, f.inc_fri, f.friendCount from ( ";
		$query .= "select a.pid, a.show_prof, a.prof_img, concat(a.name, '(', ifnull(a.affiliate_name, '-'), ')') as name, d.refid, a.inc_fri, count(e.fid) as friendCount ";
		$query .= "from koc_play.player_basic as a left outer join ( ";
		$query .= "select fid from koc_play.player_friend where pid = '".$pid."' and friend_status < 2 ) as b on a.pid = b.fid ";
		$query .= "left outer join koc_play.player_team as c on a.pid = c.pid and c.team_seq = 0 ";
		$query .= "left outer join koc_play.player_character as d on c.memb_0 = d.idx ";
		$query .= "left outer join koc_play.player_friend as e on a.pid = e.pid and e.friend_status = 1 ";
		$query .= "where b.fid is null and a.name is not null and a.affiliate_name is not null and a.pid != '".$pid."' ";
		if ( $searchVal )
		{
			$query .= "and ( a.name = '".$searchVal."' or a.affiliate_name = '".$searchVal."' ) ";
		}
		$query .= "group by a.pid, a.name, a.affiliate_name, d.refid, a.inc_fri ) as f where f.friendCount < ".MY_Controller::MAX_FRIENDS." + f.inc_fri ";
		$query .= "order by rand() desc ";
		$query .= "limit ".MY_Controller::RECOMMENDED_FRIEND_LIST_COUNT." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMyFriendList( $pid, $status )
	{
		if ( $status == 1 )
		{
			$query = "select a.fid, concat(a.fname, '(', a.faffiliate_name, ')') as fname, a.fprof_img, a.login_datetime, ";
			$query .= "ifnull(a.last_present_time, '1900-01-01 00:00:00') as last_present_time, c.refid ";
			$query .= "from koc_play.".MY_Controller::TBL_PLAYERFRIEND." as a ";
			$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.fid = b.pid and b.team_seq = 0 ";
			$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx ";
			$query .= "where a.pid = '".$pid."' and a.friend_status = ".$status." ";
		}
		else
		{
			$query = "select a.pid as fid, concat(b.name, '(', b.affiliate_name, ')') as fname, a.fprof_img, b.login_datetime, ";
			$query .= "ifnull(last_present_time, '1900-01-01 00:00:00') as last_present_time, d.refid ";
			$query .= "from koc_play.".MY_Controller::TBL_PLAYERFRIEND." as a inner join koc_play.".MY_Controller::TBL_PLAYERBASIC." as b on a.pid = b.pid ";
			$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid and c.team_seq = 0 ";
			$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as d on c.memb_0 = d.idx ";
			$query .= "where a.fid = '".$pid."' and a.friend_status = ".$status." ";
		}

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestEquipmentFriend( $pid )
	{
		$query = "select max(if(a.operator = b.idx, b.refid, null)) as op ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.operator = b.idx ";
		$query .= "where a.pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestFriendCharInfo( $pid, $fid )
	{
		$query = "select a.fid, a.fname as name, a.fprof_img, ";
		$query .= "case when c.is_del = 0 then c.refid else null end as refid, c.level, c.up_grade, c.up_refid, ";
		$query .= "max(if(c.weapon = d.idx, d.refid, null)) as weapon, max(if(c.backpack = d.idx, d.refid, null)) as backpack, ";
		$query .= "max(if(c.skill_0 = d.idx, d.refid, null)) as skill_0, max(if(c.skill_1 = d.idx, d.refid, null)) as skill_1, ";
		$query .= "max(if(c.skill_2 = d.idx, d.refid, null)) as skill_2 from koc_play.".MY_Controller::TBL_PLAYERFRIEND." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.fid = b.pid and b.team_seq = 0 ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx and c.is_del = 0 ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as d on a.fid = d.pid ";
		$query .= "and ( c.weapon = d.idx or c.backpack = d.idx or c.skill_0 = d.idx or c.skill_1 = d.idx or c.skill_2 = d.idx ) ";
		$query .= "and d.is_del = 0 and cast(ifnull(d.expire, '9999-12-31 23:59:59') as datetime) > now() ";
		$query .= "where a.pid = '".$pid."' and a.fid = '".$fid."' and a.friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";
		$query .= "group by a.fid, a.login_datetime, c.refid, c.level, c.up_grade, c.up_refid ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestMyFriendCharInfo( $pid, $fid )
	{
		$query = "select max(if(b.memb_0 = c.idx, 'memb_0', if(b.memb_1 = c.idx, 'memb_1', if(b.memb_2 = c.idx, 'memb_2', null)))) as slot, ";
		$query .= "a.fid, concat(a.fname, '(', a.faffiliate_name, ')') as fname, case when c.is_del = 0 then c.refid else null end as refid, ";
		$query .= "c.level, c.up_grade, c.up_refid, ";
		$query .= "max(if(c.weapon = d.idx, d.refid, null)) as weapon, max(if(c.backpack = d.idx, d.refid, null)) as backpack, ";
		$query .= "max(if(c.skill_0 = d.idx, d.refid, null)) as skill_0, max(if(c.skill_1 = d.idx, d.refid, null)) as skill_1, ";
		$query .= "max(if(c.skill_2 = d.idx, d.refid, null)) as skill_2, ";
		$query .= "max(if(b.memb_0 = c.idx, tact_0, if(b.memb_1 = c.idx, tact_1, if(b.memb_2 = c.idx, tact_2, null)))) as tact, ";
		$query .= "max(if(b.memb_0 = c.idx, pilot_0, if(b.memb_1 = c.idx, pilot_1, if(b.memb_2 = c.idx, pilot_2, null)))) as pilot ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERFRIEND." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.fid = b.pid and b.team_seq = 0 ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on ( b.memb_0 = c.idx or b.memb_1 = c.idx or b.memb_2 = c.idx ) and c.is_del = 0 ";
		$query .= "left outer join (select pid, idx, refid from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." ";
		$query .= "where is_del = 0 and cast(ifnull(expire, '9999-12-31 23:59:59') as datetime) > now() ) as d ";
		$query .= "on a.fid = d.pid and ( c.weapon = d.idx or c.backpack = d.idx or c.skill_0 = d.idx or c.skill_1 = d.idx or c.skill_2 = d.idx ) ";
		$query .= "where a.pid = '".$pid."' and a.fid = '".$fid."' and a.friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";
		$query .= "group by a.fid, a.fname, a.affiliate_name, a.login_datetime, c.refid, c.level, c.up_grade, c.up_refid order by slot asc ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPVEFriendList( $pid )
	{
		$query = "select a.fid, a.fname, ifnull(a.last_help_time, '1900-01-01 00:00:00') as last_help_time, ";
		$query .= "a.login_datetime, ifnull(a.last_present_time, '1900-01-01 00:00:00') as last_present_time, ";
		$query .= "a.fprof_img, c.refid, c.level, c.up_grade, c.up_refid ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERFRIEND." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as b on a.fid = b.pid and b.team_seq = 0 ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on b.memb_0 = c.idx ";
		$query .= "where a.pid = '".$pid."' and a.friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestAddFriend( $pid, $fid, $status )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERFRIEND." (pid, fid, fname, faffiliate_name, fprof_img, friend_status, last_present_time, login_datetime) ";
		$query .= "select '".$pid."' as pid, pid as fid, name as fname, affiliate_name as faffiliate_name, if(show_prof, prof_img, '') as fprof_img, ".$status." as friend_status, ";
		$query .= "null as last_present_time, login_datetime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$fid."' and not exists ( ";
		$query .= "select fid from koc_play.".MY_Controller::TBL_PLAYERFRIEND." where fid = '".$fid."' and pid = '".$pid."' ";
		$query .= "and friend_status in (".MY_Controller::FRIEND_STATUS_REQUEST.", ".MY_Controller::FRIEND_STATUS_ACCEPTED.") )";
		$query .= "on duplicate key update friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestReplyAddFriend( $pid, $fid, $status )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set friend_status = ".$status." ";
		$query .= "where pid = '".$fid."' and fid = '".$pid."' and friend_status = ".MY_Controller::FRIEND_STATUS_REQUEST." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestDelFriend( $pid, $fid, $did )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." ";
		$query .= "set friend_status = ".MY_Controller::FRIEND_STATUS_DELETED.", delete_datetime = now(), did = '".$did."' ";
		$query .= "where pid = '".$pid."' and fid = '".$fid."' and friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestFriendCount( $pid )
	{
		$query = "select ifnull(sum(if(friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED.", 1, 0)), 0) as fcount, ";
		$query .= "ifnull(sum(if(friend_status = ".MY_Controller::FRIEND_STATUS_DELETED." and did = '".$pid."' ";
		$query .= "and delete_datetime between concat(date_format(now(), '%Y-%m-%d'), ' 00:00:00') ";
		$query .= "and concat(date_format(now(), '%Y-%m-%d'), ' 23:59:59'), 1, 0)), 0) as dcount from koc_play.".MY_Controller::TBL_PLAYERFRIEND." ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestIncFriendCount( $pid )
	{
		$query = "select inc_fri from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestFriendshipPoint( $pid, $fid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set last_present_time = now() ";
		$query .= "where pid = '".$pid."' and fid = '".$fid."' and friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestFriendshipPointTime( $pid, $fid )
	{
		$query = "select (".MY_Controller::FRIEND_PRESENT_TIME." - TIME_TO_SEC(TIMEDIFF(ifnull(last_present_time, '1900-01-01 00:00:00'), now()))) ";
		$query .= "as last_present_time from koc_play.".MY_Controller::TBL_PLAYERFRIEND." ";
		$query .= "where pid = '".$pid."' and fid = '".$fid."' and friend_status = ".MY_Controller::FRIEND_STATUS_ACCEPTED." ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateFriendHelpTime( $pid, $fid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set last_help_time = now() where pid = '".$pid."' and fid = '".$fid."' ";
		$query .= "and ifnull(last_help_time, '1900-01-01 00:00:00') < date_add(now(), interval -12 hour) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLog( $pid, $logtype, $logcontent )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERLOG." ( pid, logtype, logcontent, log_datetime ) values ";
		$query .= "( '".$pid."', '".$logtype."', '".$logcontent."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestChangeOperator( $pid, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set ";
		if ( $iid == 0 )
		{
			$query .= "operator = null ";
		}
		else
		{
			$query .= "operator = '".$iid."' ";
		}
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEnemyForInitPVP( $pid )
	{
		$query = "select pid from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid != '".$pid."' order by rand() limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCheckTeamPilot( $pid, $teamSeq, $iid )
	{
		$query = "select team_seq from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = '".$teamSeq."' ";
		$query .= "and ( pilot_0 = '".$iid."' or pilot_1 = '".$iid."' or pilot_2 = '".$iid."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	public function requestUnequipItemFromChar( $pid, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set weapon = if( weapon = '".$iid."', null, weapon ), ";
		$query .= "backpack = if( backpack = '".$iid."', null, backpack ), ";
		$query .= "skill_0 = if( skill_0 = '".$iid."', null, skill_0 ), ";
		$query .= "skill_1 = if( skill_1 = '".$iid."', null, skill_1 ), ";
		$query .= "skill_2 = if( skill_2 = '".$iid."', null, skill_2 ) where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestUnequipPilotFromTeam( $pid, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERTEAM." set pilot_0 = if( pilot_0 = '".$iid."', null, pilot_0 ), ";
		$query .= "pilot_1 = if( pilot_1 = '".$iid."', null, pilot_1 ), ";
		$query .= "pilot_2 = if( pilot_2 = '".$iid."', null, pilot_2 ) where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestUnequipOperatorFromPlayer( $pid, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set operator = if( operator = '".$iid."', null, operator ) where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestUpdateOperatorExpire( $pid, $iid, $expire )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERINVENTORY." set expire = cast('".$expire."' as datetime) where pid = '".$pid."' and idx = '".$iid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestNameDupCount( $name )
	{
		$query = "select pid from koc_play.".MY_Controller::TBL_PLAYERBASIC." where name = '".$name."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, "0", "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

//삭제 예정
	public function requestEquipment( $pid )
	{
		$query = "select operator as op, pilot_0, pilot_1, pilot_2 ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestGetSlotSeq( $pid, $iid )
	{
		$query = "select if(operator = '".$iid."', 'operator', if(pilot_0 = '".$iid."', 'pilot_0', ";
		$query .= "if(pilot_1 = '".$iid."', 'pilot_1', if(pilot_2 = '".$iid."', 'pilot_2', null)))) as slotseq ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." where pid = '".$pid."' and ";
		$query .= "(operator = '".$iid."' or pilot_0 = '".$iid."' or pilot_1 = '".$iid."' or pilot_2 = '".$iid."') ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestUnEquipToPlayer( $pid, $slotseq )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." set ".$slotseq." = null where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEquipToPlayer( $pid, $slotseq, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." set ".$slotseq." = '".$iid."' where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestExtendItemExpire( $pid, $idx, $article_id )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as a inner join koc_ref.".MY_Controller::TBL_ITEM." as b on a.refid = b.id ";
		$query .= "set a.expire = date_add( a.expire, interval b.duration hour ), ext_date = now() ";
		$query .= "where a.idx = '".$idx."' and a.pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEnemyForPVP( $pid )
	{
		$query = "select pid, show_prof, prof_img from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid != '".$pid."' ";
/*개발 테스트용 소스 시작*/
		if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'staging' )
		{
			if ( $pid == "4" )
			{
				$query .= "and pid = '59' ";
			}
			else if ( $pid == "59" )
			{
				$query .= "and pid = '4' ";
			}
			if ( $pid == "93" )
			{
				$query .= "and pid = '96' ";
			}
			else if ( $pid == "96" )
			{
				$query .= "and pid = '93' ";
			}
		}
/*개발 테스트용 소스 끝*/
//		$query .= "and score between ".($score - 100 + $downNum)." ";
//		$query .= "and ".($score + 100 + $downNum)." order by rand() desc limit 1";
		$query .= "order by rand() desc limit 1 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function updateAffiliateNamePlay( $pid, $affiliateName, $affiliateProfImg )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set affiliate_name = '".$affiliateName."', prof_img = '".$affiliateProfImg."' where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateAffiliateNameFriend( $pid, $affiliateName )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERFRIEND." set faffiliate_name = '".$affiliateName."' where fid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestRetirePlayer( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_ACCOUNT." set limit_type = 'R', retire_date = now() where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function friendCount( $pid )
	{
		$query = "select pid from koc_play.".MY_Controller::TBL_PLAYERFRIEND." where fid = '".$pid."' and friend_status = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_SEL->query($query);
		return $this->DB_SEL->affected_rows();
	}

	//for Admin
	public function requestBasicInfoByPid( $searchValue )
	{
		$query = "select a.pid, a.id, a.email, a.affiliate_id, a.affiliate_type, concat(a.name, '(', a.affiliate_name, ')') as name, ";
		$query .= "a.uuid, a.reg_date, b.login_datetime, b.vip_level, b.vip_exp, ";
		$query .= "if(now() between a.limit_start and a.limit_end, a.limit_type, null) as limit_type, a.limit_start, a.limit_end ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a inner join koc_play.".MY_Controller::TBL_PLAYERBASIC." as b on a.pid = b.pid ";
		$query .= "where a.pid = '".$searchValue."' ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestBasicInfoById( $searchValue )
	{
		$query = "select a.pid, a.id, a.email, a.affiliate_id, a.affiliate_type, concat(a.name, '(', a.affiliate_name, ')') as name, ";
		$query .= "a.uuid, a.reg_date, b.login_datetime, b.vip_level, b.vip_exp, ";
		$query .= "if(now() between a.limit_start and a.limit_end, a.limit_type, null) as limit_type, a.limit_start, a.limit_end ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a inner join koc_play.".MY_Controller::TBL_PLAYERBASIC." as b on a.pid = b.pid ";
		$query .= "where a.id = '".$searchValue."' ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestBasicInfoByName( $searchValue )
	{
		$query = "select a.pid, a.id, a.email, a.affiliate_id, a.affiliate_type, concat(a.name, '(', a.affiliate_name, ')') as name, ";
		$query .= "a.uuid, a.reg_date, b.login_datetime, b.vip_level, b.vip_exp, ";
		$query .= "if(now() between a.limit_start and a.limit_end, a.limit_type, null) as limit_type, a.limit_start, a.limit_end ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a inner join koc_play.".MY_Controller::TBL_PLAYERBASIC." as b on a.pid = b.pid ";
		$query .= "where ( a.name = '".$searchValue."' or a.affiliate_name = '".$searchValue."' ) ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestAdminItem( $pid )
	{
		$query = "select energy_points, pvb_points, pvp_points, survival_points, game_points, ";
		$query .= "ifnull(cash_points, 0) as cash_points, ifnull(event_points, 0) as event_points, ";
		$query .= "friendship_points, energy_uptime, pvb_uptime, pvp_uptime, survival_uptime ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERITEM." ";
		$query .= "where pid = '".$pid."'";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminCollectionList( $pid )
	{
		$query = "select a.voc, ifnull(b.ucnt, 0) as ucnt, ifnull(a.tcnt, 0) as tcnt from ( ";
		$query .= "select if(category = 'LICENSE', category, vocation) as voc, count(id) as tcnt from koc_ref.".MY_Controller::TBL_REFCHARACTER." ";
		$query .= "where category != 'ENEMY' group by voc ) as a left outer join ( ";
		$query .= "select if(d.category = 'LICENSE', d.category, d.vocation) as voc, count(d.id) as ucnt from koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." as c ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_REFCHARACTER." as d on c.cid = d.id ";
		$query .= "where pid = '".$pid."' group by voc ) as b on a.voc = b.voc ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminAttendInfo( $pid )
	{
		$query = "select attend_count, attend_date from koc_play.".MY_Controller::TBL_PLAYERATTEND." where pid = '".$pid."' order by attend_date desc limit 1";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminDailyAchieveInfo( $pid )
	{
		$query = "select count(aid) as ucnt, 5 as tcnt from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id ";
		$query .= "where pid = '".$pid."' and b.repeate = 'DAILY' and date_format(start_datetime, '%Y%m%d') = date_format(now(), '%Y%m%d') ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminAchieveInfo( $pid )
	{
		$query = "select ucnt, tcnt from ( select count(aid) as ucnt from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id where pid = '".$pid."' and b.repeate = 'GENERAL' ";
		$query .= ") as c, ( select count(id) as tcnt from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." where repeate = 'GENERAL' ) as d ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminResearchAchieveInfo( $pid )
	{
		$query = "select ucnt, tcnt from ( select count(aid) as ucnt from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." as a ";
		$query .= "inner join koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." as b on a.aid = b.id where pid = '".$pid."' and b.repeate = 'RESEARCH' ";
		$query .= ") as c, ( select count(id) as tcnt from koc_ref.".MY_Controller::TBL_ACHIEVEMENTS." where repeate = 'RESEARCH' ) as d ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminOperInfo( $pid )
	{
		$query = "select c.kr, ifnull(a.expire, '9999-12-31 23:59:59') as expire from koc_play.player_inventory as a inner join koc_ref.item as b on a.refid = b.id ";
		$query .= "inner join koc_ref.text as c on concat('NG_ARTICLE_', b.id) = c.id ";
		$query .= "where b.category = 'OPERATOR' and a.pid = '".$pid."' order by expire asc ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function adminTeamInfo( $pid )
	{
		$query = "select team_seq, ifnull(max(if(a.memb_0 = b.idx, d.kr, null)), '-') as ref_0, ifnull(a.memb_0, '-') as memb_0, ";
		$query .= "ifnull(max(if(a.memb_1 = b.idx, d.kr, null)), '-') as ref_1, ifnull(a.memb_1, '-') as memb_1, ";
		$query .= "ifnull(max(if(a.memb_2 = b.idx, d.kr, null)), '-') as ref_2, ifnull(a.memb_2, '-') as memb_2, ";
		$query .= "ifnull(max(if(a.pilot_0 = c.idx, e.kr, null)), '-') as pilot_0, ifnull(a.pilot_0, '-') as pi_0, ";
		$query .= "ifnull(max(if(a.pilot_1 = c.idx, e.kr, null)), '-') as pilot_1, ifnull(a.pilot_1, '-') as pi_1, ";
		$query .= "ifnull(max(if(a.pilot_2 = c.idx, e.kr, null)), '-') as pilot_2, ifnull(a.pilot_2, '-') as pi_2, ";
		$query .= "ifnull(max(if(b.idx = a.memb_0, b.grade, null)), '-') as grd_0, ";
		$query .= "ifnull(max(if(b.idx = a.memb_1, b.grade, null)), '-') as grd_1, ";
		$query .= "ifnull(max(if(b.idx = a.memb_2, b.grade, null)), '-') as grd_2, ";
		$query .= "ifnull(max(if(b.idx = a.memb_0, b.level, null)), '-') as level_0, ";
		$query .= "ifnull(max(if(b.idx = a.memb_1, b.level, null)), '-') as level_1, ";
		$query .= "ifnull(max(if(b.idx = a.memb_2, b.level, null)), '-') as level_2, ";
		$query .= "ifnull(max(if(b.idx = a.memb_0, b.up_grade, null)), '-') as upgrade_0, ";
		$query .= "ifnull(max(if(b.idx = a.memb_1, b.up_grade, null)), '-') as upgrade_1, ";
		$query .= "ifnull(max(if(b.idx = a.memb_2, b.up_grade, null)), '-') as upgrade_2, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_0, e.kr, null)), '-') as weapon_0, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_0, c.idx, null)), '-') as wep_0, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_1, e.kr, null)), '-') as weapon_1, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_1, c.idx, null)), '-') as wep_1, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_2, e.kr, null)), '-') as weapon_2, ";
		$query .= "ifnull(max(if(c.idx = b.weapon and b.idx = a.memb_2, c.idx, null)), '-') as wep_2, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_0, e.kr, null)), '-') as backpack_0, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_0, c.idx, null)), '-') as bp_0, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_1, e.kr, null)), '-') as backpack_1, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_1, c.idx, null)), '-') as bp_1, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_2, e.kr, null)), '-') as backpack_2, ";
		$query .= "ifnull(max(if(c.idx = b.backpack and b.idx = a.memb_2, c.idx, null)), '-') as bp_2, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_0, f.kr, null)), '-') as skill_00, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_0, c.idx, null)), '-') as skl_00, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_0, f.kr, null)), '-') as skill_01, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_0, c.idx, null)), '-') as skl_01, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_0, f.kr, null)), '-') as skill_02, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_0, c.idx, null)), '-') as skl_02, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_1, f.kr, null)), '-') as skill_10, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_1, c.idx, null)), '-') as skl_10, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_1, f.kr, null)), '-') as skill_11, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_1, c.idx, null)), '-') as skl_11, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_1, f.kr, null)), '-') as skill_12, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_1, c.idx, null)), '-') as skl_12, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_2, f.kr, null)), '-') as skill_20, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0 and b.idx = a.memb_2, c.idx, null)), '-') as skl_20, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_2, f.kr, null)), '-') as skill_21, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1 and b.idx = a.memb_2, c.idx, null)), '-') as skl_21, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_2, f.kr, null)), '-') as skill_22, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2 and b.idx = a.memb_2, c.idx, null)), '-') as skl_22 ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERTEAM." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b on b.pid = '".$pid."' ";
		$query .= "and (a.memb_0 = b.idx or a.memb_1 = b.idx or a.memb_2 = b.idx) ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c on c.pid = '".$pid."' ";
		$query .= "and (a.pilot_0 = c.idx or a.pilot_1 = c.idx or a.pilot_2 = c.idx ";
		$query .= "or b.weapon = c.idx or b.backpack = c.idx or b.skill_0 = c.idx or b.skill_1 = c.idx or b.skill_2 = c.idx) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat('NG_ARTICLE_', b.refid) = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat('NG_ARTICLE_', c.refid) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat('NG_ARTICLE_', c.refid) = f.id ";
		$query .= "where a.pid = '".$pid."' group by a.team_seq ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestInventoryInfoByPid( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, ";
		$query .= "if( d.category = 'WEAPON', '무기', if( d.category = 'BACKPACK', '백팩', '스킬') ) as category, ";
		$query .= "e.kr as itemname, d.grade, if( c.idx = max(c.idx), concat(f.kr, ' ( ', c.idx, ' )'), '') as equip ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.weapon or b.idx = c.backpack or b.idx = c.skill_0 or b.idx = c.skill_1 or b.idx = c.skill_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id or concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "where a.pid = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category in ('WEAPON', 'BACKPACK', 'TECHNIQUE') group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestInventoryInfoById( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, ";
		$query .= "if( d.category = 'WEAPON', '무기', if( d.category = 'BACKPACK', '백팩', '스킬') ) as category, ";
		$query .= "e.kr as itemname, d.grade, if( c.idx = max(c.idx), concat(f.kr, ' ( ', c.idx, ' )'), '') as equip ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.weapon or b.idx = c.backpack or b.idx = c.skill_0 or b.idx = c.skill_1 or b.idx = c.skill_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id or concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "where a.id = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category in ('WEAPON', 'BACKPACK', 'TECHNIQUE') group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestInventoryInfoByName( $name )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, ";
		$query .= "if( d.category = 'WEAPON', '무기', if( d.category = 'BACKPACK', '백팩', '스킬') ) as category, ";
		$query .= "e.kr as itemname, d.grade, if( c.idx = max(c.idx), concat(f.kr, ' ( ', c.idx, ' )'), '') as equip ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.weapon or b.idx = c.backpack or b.idx = c.skill_0 or b.idx = c.skill_1 or b.idx = c.skill_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id or concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "where ( a.name = '".$name."' or a.affiliate_name = '".$name."' ) and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category in ('WEAPON', 'BACKPACK', 'TECHNIQUE') group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPilotInfoByPid( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "concat( if( c.team_seq = 0, 'A', if( c.team_seq = 1, 'B', if( c.team_seq = 2, 'C', '' ) ) ), ";
		$query .= "if( b.idx = c.pilot_0, '1', if( b.idx = c.pilot_1, '2', if( b.idx = c.pilot_2, '3', '' ) ) ) ) as board, ";
		$query .= "b.reg_date from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.pilot_0 or b.idx = c.pilot_1 or b.idx = c.pilot_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where a.pid = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'PILOT' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPilotInfoById( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "concat( if( c.team_seq = 0, 'A', if( c.team_seq = 1, 'B', if( c.team_seq = 2, 'C', '' ) ) ), ";
		$query .= "if( b.idx = c.pilot_0, '1', if( b.idx = c.pilot_1, '2', if( b.idx = c.pilot_2, '3', '' ) ) ) ) as board, ";
		$query .= "b.reg_date from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.pilot_0 or b.idx = c.pilot_1 or b.idx = c.pilot_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where a.id = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'PILOT' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestPilotInfoByName( $name )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "concat( if( c.team_seq = 0, 'A', if( c.team_seq = 1, 'B', if( c.team_seq = 2, 'C', '' ) ) ), ";
		$query .= "if( b.idx = c.pilot_0, '1', if( b.idx = c.pilot_1, '2', if( b.idx = c.pilot_2, '3', '' ) ) ) ) as board, ";
		$query .= "b.reg_date from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as c on a.pid = c.pid ";
		$query .= "and ( b.idx = c.pilot_0 or b.idx = c.pilot_1 or b.idx = c.pilot_2 ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where ( a.name = '".$name."' or a.affiliate_name = '".$name."' ) and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'PILOT' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestOperatorInfoByPid( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "if(c.operator = b.idx, 1, 0) as is_equip, b.reg_date, b.ext_date, b.expire ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERBASIC." as c on a.pid = c.pid and b.idx = c.operator ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where a.pid = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'OPERATOR' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestOperatorInfoById( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "if(c.operator = b.idx, 1, 0) as is_equip, b.reg_date, b.ext_date, b.expire ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERBASIC." as c on a.pid = c.pid and b.idx = c.operator ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where a.id = '".$id."' and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'OPERATOR' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestOperatorInfoByName( $name )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, e.kr as itemname, ";
		$query .= "if(c.operator = b.idx, 1, 0) as is_equip, b.reg_date, b.ext_date, b.expire ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERBASIC." as c on a.pid = c.pid and b.idx = c.operator ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_ITEM." as d on b.refid = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', b.refid ) = e.id ";
		$query .= "where ( a.name = '".$name."' or a.affiliate_name = '".$name."' ) and b.is_del = 0 and ( b.expire is null or b.expire > now() ) ";
		$query .= "and d.category = 'OPERATOR' group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacterInfoByPid( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, d.kr as charname, b.refid, b.grade, b.level, b.up_grade, ";
		$query .= "ifnull(max(if(c.idx = b.weapon, c.idx, null)), '-') as weapon, ifnull(max(if(c.idx = b.weapon, e.kr, null)), '-') as weapon_name, ";
		$query .= "ifnull(max(if(c.idx = b.backpack, c.idx, null)), '-') as backpack, ifnull(max(if(c.idx = b.backpack, e.kr, null)), '-') as backpack_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0, c.idx, null)), '-') as skill_0, ifnull(max(if(c.idx = b.skill_0, f.kr, null)), '-') as skill_0_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1, c.idx, null)), '-') as skill_1, ifnull(max(if(c.idx = b.skill_1, f.kr, null)), '-') as skill_1_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2, c.idx, null)), '-') as skill_2, ifnull(max(if(c.idx = b.skill_2, f.kr, null)), '-') as skill_2_name, ";
		$query .= "if( g.team_seq = 0, 'A', if( g.team_seq = 1, 'B', if( g.team_seq = 2, 'C', if( exp_idx is not null, '탐색', '-' ) ) ) ) as team, ";
		$query .= "exp_group_idx, exp_idx, exp_time ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c on a.pid = c.pid ";
		$query .= "and ( b.weapon = c.idx or b.backpack = c.idx or b.skill_0 = c.idx or b.skill_1 = c.idx or b.skill_2 = c.idx ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat( 'NG_ARTICLE_', b.refid ) = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', c.refid ) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as g on a.pid = g.pid ";
		$query .= "and ( b.idx = g.memb_0 or b.idx = g.memb_1 or b.idx = g.memb_2 ) ";
		$query .= "where a.pid = '".$id."' and b.is_del = 0 ";
		$query .= "group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacterInfoById( $id )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, d.kr as charname, b.refid, b.grade, b.level, b.up_grade, ";
		$query .= "ifnull(max(if(c.idx = b.weapon, c.idx, null)), '-') as weapon, ifnull(max(if(c.idx = b.weapon, e.kr, null)), '-') as weapon_name, ";
		$query .= "ifnull(max(if(c.idx = b.backpack, c.idx, null)), '-') as backpack, ifnull(max(if(c.idx = b.backpack, e.kr, null)), '-') as backpack_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0, c.idx, null)), '-') as skill_0, ifnull(max(if(c.idx = b.skill_0, f.kr, null)), '-') as skill_0_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1, c.idx, null)), '-') as skill_1, ifnull(max(if(c.idx = b.skill_1, f.kr, null)), '-') as skill_1_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2, c.idx, null)), '-') as skill_2, ifnull(max(if(c.idx = b.skill_2, f.kr, null)), '-') as skill_2_name, ";
		$query .= "if( g.team_seq = 0, 'A', if( g.team_seq = 1, 'B', if( g.team_seq = 2, 'C', if( exp_idx is not null, '탐색', '-' ) ) ) ) as team, ";
		$query .= "exp_group_idx, exp_idx, exp_time ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c on a.pid = c.pid ";
		$query .= "and ( b.weapon = c.idx or b.backpack = c.idx or b.skill_0 = c.idx or b.skill_1 = c.idx or b.skill_2 = c.idx ) ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as d on concat( 'NG_ARTICLE_', b.refid ) = d.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as e on concat( 'NG_ARTICLE_', c.refid ) = e.id ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_TEXT." as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as g on a.pid = g.pid ";
		$query .= "and ( b.idx = g.memb_0 or b.idx = g.memb_1 or b.idx = g.memb_2 ) ";
		$query .= "where a.id = '".$id."' and b.is_del = 0 ";
		$query .= "group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestCharacterInfoByName( $name )
	{
		$query = "select a.id, a.pid, concat(a.name, '(', a.affiliate_name, ')') as name, a.uuid, b.idx, d.kr as charname, b.refid, b.grade, b.level, b.up_grade, ";
		$query .= "ifnull(max(if(c.idx = b.weapon, c.idx, null)), '-') as weapon, ifnull(max(if(c.idx = b.weapon, e.kr, null)), '-') as weapon_name, ";
		$query .= "ifnull(max(if(c.idx = b.backpack, c.idx, null)), '-') as backpack, ifnull(max(if(c.idx = b.backpack, e.kr, null)), '-') as backpack_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_0, c.idx, null)), '-') as skill_0, ifnull(max(if(c.idx = b.skill_0, f.kr, null)), '-') as skill_0_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_1, c.idx, null)), '-') as skill_1, ifnull(max(if(c.idx = b.skill_1, f.kr, null)), '-') as skill_1_name, ";
		$query .= "ifnull(max(if(c.idx = b.skill_2, c.idx, null)), '-') as skill_2, ifnull(max(if(c.idx = b.skill_2, f.kr, null)), '-') as skill_2_name, ";
		$query .= "if( g.team_seq = 0, 'A', if( g.team_seq = 1, 'B', if( g.team_seq = 2, 'C', if( exp_idx is not null, '탐색', '-' ) ) ) ) as team, ";
		$query .= "exp_group_idx, exp_idx, exp_time ";
		$query .= "from koc_play.".MY_Controller::TBL_ACCOUNT." as a ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERCHARACTER." as b on a.pid = b.pid ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERINVENTORY." as c on a.pid = c.pid ";
		$query .= "and ( b.weapon = c.idx or b.backpack = c.idx or b.skill_0 = c.idx or b.skill_1 = c.idx or b.skill_2 = c.idx ) ";
		$query .= "left outer join koc_ref.text as d on concat( 'NG_ARTICLE_', b.refid ) = d.id ";
		$query .= "left outer join koc_ref.text as e on concat( 'NG_ARTICLE_', c.refid ) = e.id ";
		$query .= "left outer join koc_ref.text as f on concat( 'NG_ARTICLE_', c.refid ) = f.id ";
		$query .= "left outer join koc_play.".MY_Controller::TBL_PLAYERTEAM." as g on a.pid = g.pid ";
		$query .= "and ( b.idx = g.memb_0 or b.idx = g.memb_1 or b.idx = g.memb_2 ) ";
		$query .= "where ( a.name = '".$name."' or a.affiliate_name = '".$name."' ) and b.is_del = 0 ";
		$query .= "group by a.id, a.pid, a.name, a.affiliate_name, a.uuid, b.idx ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLevelUgrChange( $pid, $cid, $clev, $cexp, $cug )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set level = ".$clev.", exp = ".$cexp.", up_grade = ".$cug." ";
		$query .= "where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->admLogWrite( LOG_NOTICE, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();

	}

	public function requestUpdateIncresement( $pid, $slottype, $incquantity )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set ";
		if ( $slottype == "cha" )
		{
			$query .= "inc_".$slottype." = if( inc_".$slottype." + ".$incquantity." > ".MY_Controller::MAX_INC_LIMIT_CHAR.", ";
		}
		else if ( $slottype == "wea" )
		{
			$query .= "inc_".$slottype." = if( inc_".$slottype." + ".$incquantity." > ".MY_Controller::MAX_INC_LIMIT_ITEM.", ";
		}
		$query .= "inc_".$slottype.", inc_".$slottype." + ".$incquantity." ) ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestSynthesizeCharInfo( $pid, $sourceIdx, $targetIdx )
	{
		$query = "select weapon, backpack, skill_0, skill_1, skill_2 from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." ";
		$query .= "where pid = '".$pid."' and  idx in ('".$sourceIdx."', '".$targetIdx."') and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestVipInfo( $pid, $vip_exp )
	{
		$query = "select ifnull(a.vip_level, 0) as prev_level, ifnull(max(b.vip_lev), 0) as vip_level, ( ifnull(a.vip_exp, 0) + ".$vip_exp." ) as vip_exp ";
		$query .= "from koc_play.".MY_Controller::TBL_PLAYERBASIC." as a ";
		$query .= "left outer join koc_ref.".MY_Controller::TBL_VIPLEVINFO." as b on a.vip_exp + ".$vip_exp." >= b.exp ";
		$query .= "where a.pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestUpdateVipInfo( $pid, $vip_level, $vip_exp )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set vip_level = '".$vip_level."', vip_exp = '".$vip_exp."' ";
		$query .= "where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateVipRewardDate( $pid, $reward_type, $reward_value )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERVIP." ( pid, vipreward_datetime, reward_type, reward_value ) values ";
		$query .= "( '".$pid."', now(), '".$reward_type."', '".$reward_value."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updatePlayerBasic( $pid, $reward_type, $reward_value )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set ".$reward_type." = '".$reward_value."' where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestKey( $pid )
	{
		$query = "select public_key, private_key from koc_play.".MY_Controller::TBL_PLAYERBASIC." where pid = '".$pid."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLogIap( $is_provision, $pid, $sid, $storeType, $product_id, $payment_unit, $payment_type, $payment_value, $paymentSeq, $approvedPaymentNo, $naverId, $paymentTime, $curcash )
	{
		$query = "insert into koc_play.player_iap ( pid, sid, storetype, product_id, payment_unit, payment_type, payment_value, ";
		$query .= "buy_date, expire_date, is_refund, is_provision, paymentSeq, approvedPaymentNo, naverId, paymentTime, curcash ) ";
		$query .= "select '".$pid."', '".$sid."', '".$storeType."', '".$product_id."', '".$payment_unit."', '".$payment_type."', '".$payment_value."', ";
		$query .= "now(), date_add( now(), interval ( duration - 1 ) day ), 0, ".$is_provision.", ";
		$query .= "'".$paymentSeq."', '".$approvedPaymentNo."', '".$naverId."', ";
		if ( $paymentTime == "" || $paymentTime == null )
		{
			$query .= "null, ";
		}
		else
		{
			$query .= "'".$paymentTime."', ";
		}
		$query .= "'".$curcash."' ";
		$query .= "from koc_ref.".MY_Controller::TBL_PRODUCT." where id = '".$product_id."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->insert_id();
	}

	public function requestBuyIapExists( $pid, $storeType, $paymentSeq )
	{
		$query = "select idx from koc_play.player_iap where pid = '".$pid."' and storetype = '".$storeType."' and paymentSeq = '".$paymentSeq."' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestEverydayPackageList( $pid )
	{
		$query = "select a.idx, a.product_id, a.paymentSeq, a.expire_date, b.type as reward_type, b.value as reward_value, count(c.paymentSeq) as is_reward ";
		$query .= "from koc_play.player_iap as a inner join koc_ref.".MY_Controller::TBL_PRODUCT." as b ";
		$query .= "on a.product_id = b.id left outer join koc_play.player_packagereward_log as c ";
		$query .= "on a.idx = c.order_idx and left(now(), 10) = left(c.reward_datetime, 10) ";
		$query .= "where a.expire_date >= left( now(), 10 ) and a.product_id = '".MY_Controller::EVERYDAY_PROVISION_PRODUCT_ID."' ";
		$query .= "and a.is_provision = 1 and a.is_refund = 0 and a.sid = '".$pid."' ";
		$query .= "group by a.idx, a.product_id, a.paymentSeq, a.expire_date, b.type, b.value ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLimitedPackageList( $pid )
	{
		$query = "select a.product_id ";
		$query .= "from koc_play.player_iap as a inner join koc_ref.".MY_Controller::TBL_PRODUCT." as b on a.product_id = b.id ";
		$query .= "where a.product_id in ('".MY_Controller::LIMITED_PROVISION_PRODUCT_ID."', '".MY_Controller::HAPPYNEWYEAR_PROVISION_PRODUCT_ID."') ";
		$query .= "and a.is_provision = 1 and a.is_refund = 0 and a.sid = '".$pid."' ";
		$query .= "group by a.idx, a.product_id, a.paymentSeq, a.expire_date, b.type, b.value ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_SEL->query($query);
	}

	public function requestLoggingEveryDayPackPayment( $pid, $mail_id, $idx, $paymentSeq, $product_id, $expire_date, $reward_type, $reward_value )
	{
		$query = "insert into koc_play.player_packagereward_log ";
		$query .= "( pid, mail_id, order_idx, paymentSeq, product_id, reward_type, reward_value, expire_date, reward_datetime ) ";
		$query .= "values ( '".$pid."', '".$mail_id."', '".$idx."', '".$paymentSeq."', '".$product_id."', ";
		$query .= "'".$reward_type."', '".$reward_value."', '".$expire_date."', now() ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestFirstBuyCheck( $pid )
	{
		$query = "select case when count(idx) > 0 then false else true end as is_first ";
		$query .= "from koc_play.player_iap as a inner join koc_ref.product as b on a.product_id = b.id ";
		$query .= "where pid = '".$pid."' and buy_date between if( year(now()) = 2015 and ( month(now()) = 1 or month(now()) = 2 ), ";
		$query .= "cast('2015-01-01 00:00:00' as datetime), cast(concat('2015-', right(concat('0', month(now())), 2), '-01 00:00:00') as datetime) ) ";
		$query .= "and if( year(now()) = 2015 and ( month(now()) = 1 or month(now()) = 2 ), cast('2015-02-28 23:59:59' as datetime), ";
		$query .= "cast(concat('2015-', right(concat('0', month(now())), 2), '-', right(concat('0', last_day(now())), 2) ,' 23:59:59') as datetime) ";
		$query .= ") and b.category = 'CASH' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function requestLoggingUseAssets( $pid, $use_gubun, $point_type, $point_value, $loggingText )
	{
		$query = "insert into koc_play.player_asset_logging ( pid, usetype, asset_type, asset_value, description, reg_datetime ) ";
		$query .= "values ( '".$pid."', '".$use_gubun."', '".$point_type."', '".$point_value."', '".$loggingText."', now() )";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		return $this->DB_INS->query($query);
	}

	public function inventoryExistCheck( $pid )
	{
		$query = "select idx from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."' and refid = 'OP01000008' ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	//테스트용
	public function resetPlayerPoint( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERITEM." set ";
		$query .= "energy_points = ".MY_Controller::MAX_ENERGY_POINTS.", ";
		$query .= "pvb_points = ".MY_Controller::MAX_MODES_PVB.", ";
		$query .= "pvp_points = ".MY_Controller::MAX_MODES_PVP.", ";
		$query .= "survival_points = ".MY_Controller::MAX_MODES_SURVIVAL.", ";
		$query .= "game_points = 0, ";
		$query .= "cash_points = 0, ";
		$query .= "friendship_points = 0 ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerBasic( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERBASIC." set vip_level = 0, vip_exp = 0, ";
		$query .= "inc_cha = 0, inc_wea = 0, inc_bck = 0, inc_skl = 0, inc_exp = 0, inc_eng = 0, inc_fri = 0, inc_pvp = 0, inc_pvb = 0, inc_survival = 0 ";
		$query .= "where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerAttend( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERATTEND." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerCharacter( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERCHARACTER." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerCollection( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERCOLLECTION." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerEquipment( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." set operator = null, pilot_0 = null, pilot_1 = null, pilot_2 = null where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerAchieve( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERACHIEVE." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerInventory( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERINVENTORY." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function resetPlayerTeam( $pid )
	{
		$query = "delete from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function newPlayerTeam( $pid, $chr1, $chr2, $chr3 )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERTEAM." ( pid, team_seq, memb_0, memb_1, memb_2, tact_0, tact_1, tact_2 ) select ";
		$query .= "'".$pid."', ";
		$query .= "'0', ";
		$query .= "'".$chr1."', ";
		$query .= "'".$chr2."', ";
		if ( $chr3 == null )
		{
			$query .= "null, 0, 0, 0 from dual union all select ";
		}
		else
		{
			$query .= "'".$chr3."', 0, 0, 0 from dual union all select ";
		}
		$query .= "'".$pid."', ";
		$query .= "'1', ";
		$query .= "'".$chr1."', ";
		$query .= "null, ";
		$query .= "null, 0, 0, 0 from dual union all select ";
		$query .= "'".$pid."', ";
		$query .= "'2', ";
		$query .= "'".$chr1."', ";
		$query .= "null, ";
		$query .= "null, 0, 0, 0 from dual ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function insertEquipment( $pid, $oper )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." ( pid, operator ) values ( '".$pid."', '".$oper."' ) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function insertItem( $pid )
	{
		$query = "insert into koc_play.".MY_Controller::TBL_PLAYERITEM." ( pid, energy_points, pvb_points, pvp_points, survival_points, game_points, cash_points, event_points, friendship_points ) values ('".$pid."', ".MY_Controller::MAX_ENERGY_POINTS.", ".MY_Controller::MAX_MODES_PVB.", ".MY_Controller::MAX_MODES_PVP.", ".MY_Controller::MAX_MODES_SURVIVAL.", 0, 0, 0, 0) ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function updateEquipment( $pid, $slotseq, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYEREQUIPMENT." set ".$slotseq." = '".$iid."' where pid = '".$pid."'";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function itemToChar( $pid, $cid, $slotseq, $iid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set ".$slotseq." = '".$iid."' where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestMaxCharacter( $pid, $cid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set up_grade = 5, level = 30 where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestMaxCharacterAll( $pid )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set up_grade = 5, level = 30 where pid = '".$pid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestLevelChange( $pid, $cid, $clev, $cexp )
	{
		$query = "update koc_play.".MY_Controller::TBL_PLAYERCHARACTER." set level = ".$clev.", exp = ".$cexp." where pid = '".$pid."' and idx = '".$cid."' and is_del = 0 ";

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "sql : ".$query );
		$this->DB_INS->query($query);
		return $this->DB_INS->affected_rows();
	}

	public function requestPid( $id )
	{
		$query = "select pid from koc_play.".MY_Controller::TBL_ACCOUNT." where id = '".$id."' ";

		return $this->DB_SEL->query($query);
	}

	public function requestGetCharFirst( $pid )
	{
		$query = "select memb_0 from koc_play.".MY_Controller::TBL_PLAYERTEAM." where pid = '".$pid."' and team_seq = 0 ";

		return $this->DB_SEL->query($query);
	}
	//테스트용
}
?>
