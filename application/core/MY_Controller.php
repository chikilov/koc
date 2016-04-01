<?php
class MY_Controller extends CI_Controller {
	//const for version
	const VERSION_FOR_ANDROID = '10300';

	//const for updatefile
	const DOWNLOAD_URL = 'static/StreamingAssets/';
	const NOTICE_URL = 'index.php/pages/notice/listnotice/view';

	//const for encrypt
	const INIT_ENCRYPTION_SERVER_PUBLICKEY = <<<EOF
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC//TPz1LpVo1/o/EHOO6VF3V5h
uW6s7XPEOw2PRR1gJ/RAGQmRvRXtFvyziPxA+PK/cLfZPqJZ78ju4t6PpHg35vCI
N2iZRkYz31M+39tEz0UPi8pvIDnu2zCZcKxYp+KxuB6Q9ULaVzE8SvScTFxKnmUP
s+3fYUwb9tGS0ZEUdwIDAQAB
-----END PUBLIC KEY-----
EOF;
	const INIT_ENCRYPTION_SERVER_PRIVATEKEY = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC//TPz1LpVo1/o/EHOO6VF3V5huW6s7XPEOw2PRR1gJ/RAGQmR
vRXtFvyziPxA+PK/cLfZPqJZ78ju4t6PpHg35vCIN2iZRkYz31M+39tEz0UPi8pv
IDnu2zCZcKxYp+KxuB6Q9ULaVzE8SvScTFxKnmUPs+3fYUwb9tGS0ZEUdwIDAQAB
AoGAJgWMK2bevCUN0oNauPAhDBQKJApoO8EO4zbauZvzdF6VRbhvp7gtxnV9+ERR
yMBEutsk3dZEqtENFxpA/2uQDxJBrgWiHnJdRgYyCDvbTxdSK+6m0wqNMHqjTKcg
8gUPoZBh6o1CGeO1Ivu0P1u56iPZILSgqXCtx7ofOGzpE1kCQQDmgKKJjjBM+JbD
u4rg/lR0QzsopmtpKeqJ56a2jO2XhNYtiZKEAqAabAxklgi6C53Lmash/n/Sus2h
QYGq/bo7AkEA1TnyMqIsxCHgp2BtccPt/vMDBMj5SxaBaV9eaZ3Y41LRxxEKXz/j
puzbx4k4CD2VsQ+WjlF63j+4BKzLWCHu9QJBALbVsheKQaXMSUpYqIBvPG48JeDI
oMp6BR/+L679ejiFlZ3pUd6Edpp+uhXPLHzmWfP1oFkGlj2BDzqpy0DmoaECQE2E
vYqu7vt3HYd8RQ3BH3s6ufQ5ZHhp/C5k35wiKMGsUFvA1DaAsbD0F4rZVieYzB0M
/71J3hOCstHwSBUIcSkCQA+88O3hqbpgzqtds45wVZ9Mo5FdbUoWKUuvyLNQBfAp
wTAtMVZ7ieX9t5cS9MLgi73HgK0vwGt+LVrPul9+cYk=
-----END RSA PRIVATE KEY-----
EOF;

	//const for naver api
	const CP_ID	= 'AD_1003737';
	const TEST_IAP_KEY = 'ctXmVCPZm8';
	const PROD_IAP_KEY = 'K6Pb3rHwae';

	//const for tstore api
	const TSTORE_APPID = 'OA00683672';

	//const for common response
	const STATUS_NO_MATCHING_PARAMETER = '0001';
	const MESSAGE_NO_MATCHING_PARAMETER = 'NG_ERROR_COMMON';
	const STATUS_SERVER_OFFLINE = '0002';
	const MESSAGE_SERVER_OFFLINE = 'NG_ERROR_SERVER_OFFLINE';
	const STATUS_API_OK = STATUS_API_OK;
	const MESSAGE_API_OK = 'NG_SUCCESS';
	const STATUS_LOGIN_DUP = '0300';
	const MESSAGE_LOGIN_DUP = 'NG_ERROR_LOGIN_DUP';
	const STATUS_INSERT_ROW = '0500';
	const MESSAGE_INSERT_ROW = 'NG_ERROR_INSERT_ROW';

	//const for each error response
	const STATUS_CREATE_DUPLICATE_ID = '0011';
	const MESSAGE_CREATE_DUPLICATE_ID = "NG_ERROR_CREATE_DUPLICATE_ID";
	const STATUS_CREATE_ID = "0012";
	const MESSAGE_CREATE_ID = "NG_ERROR_CREATE_ID";
	const STATUS_RESTRICT_MAC = "0013";
	const MESSAGE_RESTRICT_MAC = "NG_ERROR_RESTRICT_MAC";

//	const STATUS_RENAME = "0021";
//	const MESSAGE_RENAME = "NG_ERROR_RENAME";
	const STATUS_DUPLICATE_UPDATE_ID = "0022";
	const MESSAGE_DUPLICATE_UPDATE_ID = "NG_ERROR_DUPLICATE_UPDATE_ID";

	const STATUS_LACK_CASH = "0031";
	const MESSAGE_LACK_CASH = "NG_ERROR_LACK_CASH";
	const STATUS_EXPAND_SLOT = "0032";
	const MESSAGE_EXPAND_SLOT = "NG_ERROR_EXPAND_SLOT";
	const STATUS_EXPAND_NO_PLAYER = "0033";
	const MESSAGE_EXPAND_NO_PLAYER = "NG_ERROR_EXPAND_NO_PLAYER";

	const STATUS_DUPLICATE_LOGIN_ID = "0041";
	const MESSAGE_DUPLICATE_LOGIN_ID = "NG_ERROR_DUPLICATE_LOGIN_ID";
	const STATUS_INFO_ID = "0042";
	const MESSAGE_INFO_ID = "NG_ERROR_INFO_ID";
	const STATUS_REJECT_ID = "0043";
	const MESSAGE_REJECT_ID = "NG_ERROR_REJECT_ID";

	const STATUS_REQUEST_MAIL = "0051";
	const MESSAGE_REQUEST_MAIL = "NG_ERROR_REQUEST_MAIL";

	const STATUS_RECEIVE_ITEM = "0061";
	const MESSAGE_RECEIVE_ITEM = "NG_ERROR_RECEIVE_ITEM";
	const STATUS_ADD_MAIL = "0062";
	const MESSAGE_ADD_MAIL = "NG_ERROR_ADD_MAIL";

	const STATUS_NO_PLAYER = "0071";
	const MESSAGE_NO_PLAYER = "NG_ERROR_NO_PLAYER";
	const STATUS_SERVER_BUSY = "0072";
	const MESSAGE_SERVER_BUSY = "NG_ERROR_SERVER_BUSY";

	const STATUS_DEPLOY_CHARACTER_INFO = "0081";
	const MESSAGE_DEPLOY_CHARACTER_INFO = "NG_ERROR_DEPLOY_CHARACTER_INFO";
	const STATUS_DEPLOY_DUPLICATE_CHARACTER = "0082";
	const MESSAGE_DEPLOY_DUPLICATE_CHARACTER = "NG_ERROR_DEPLOY_DUPLICATE_CHARACTER";
	const STATUS_NO_CHARACTER_FOR_SLOT = "0083";
	const MESSAGE_NO_CHARACTER_FOR_SLOT = "NG_ERROR_NO_CHARACTER_FOR_SLOT";

	const STATUS_APPID = "0091";
	const MESSAGE_APPID = "NG_ERROR_APPID";

	const STATUS_EQUIP_ERROR_ITEM = "0101";
	const MESSAGE_EQUIP_ERROR_ITEM = "NG_ERROR_EQUIP_ERROR_ITEM";
	const STATUS_EQUIP_DELETE_ITEM = "0102";
	const MESSAGE_EQUIP_DELETE_ITEM = "NG_ERROR_EQUIP_DELETE_ITEM";
	const STATUS_EQUIP_ITEM = "0103";
	const MESSAGE_EQUIP_ITEM = "NG_ERROR_EQUIP_ITEM";
	const STATUS_EQUIP_CLASS_ERROR_ITEM = "0104";
	const MESSAGE_EQUIP_CLASS_ERROR_ITEM = "NG_ERROR_EQUIP_CLASS_ERROR_ITEM";
	const STATUS_EQUIP_SLOT_ERROR_ITEM = "0105";
	const MESSAGE_EQUIP_SLOT_ERROR_ITEM = "NG_ERROR_EQUIP_SLOT_ERROR_ITEM";
	const STATUS_EQUIP_TYPE_ERROR_ITEM = "0106";
	const MESSAGE_EQUIP_TYPE_ERROR_ITEM = "NG_ERROR_EQUIP_TYPE_ERROR_ITEM";

	const STATUS_BUYITEM_ERROR_ITEM = "0111";
	const MESSAGE_BUYITEM_ERROR_ITEM = "NG_ERROR_BUYITEM_ERROR_ITEM";
	const STATUS_BUYITEM_ERROR_METHOD = "0112";
	const MESSAGE_BUYITEM_ERROR_METHOD = "NG_ERROR_BUYITEM_ERROR_METHOD";
	const STATUS_BUYITEM_ITEM_GIVE = "0113";
	const MESSAGE_BUYITEM_ITEM_GIVE = "NG_ERROR_BUYITEM_ITEM_GIVE";

	const STATUS_PVE_LACK_GOLD = "0121";
	const MESSAGE_PVE_LACK_GOLD = "NG_ERROR_PVE_LACK_GOLD";
	const STATUS_PVE_LACK_ENERGY = "0122";
	const MESSAGE_PVE_LACK_ENERGY = "NG_ERROR_PVE_LACK_ENERGY";

	const STATUS_PVE_REWARD_FAIL = "0131";
	const MESSAGE_PVE_REWARD_FAIL = "NG_ERROR_PVE_REWARD_FAIL";
	const STATUS_PVE_REWARD_ALREADY = "0132";
	const MESSAGE_PVE_REWARD_ALREADY = "NG_ERROR_PVE_REWARD_ALREADY";
	const STATUS_PVE_REWARD_HACK = "0133";
	const MESSAGE_PVE_REWARD_HACK = "NG_ERROR_PVE_REWARD_FAIL";

	const STATUS_RETRY_LACK_CASH = "0141";
	const MESSAGE_RETRY_LACK_CASH = "NG_ERROR_RETRY_LACK_CASH";
	const STATUS_RETRY_FAIL = "0142";
	const MESSAGE_RETRY_FAIL = "NG_ERROR_RETRY_FAIL";
	const STATUS_PVE_RETRY_STAGE = "0143";
	const MESSAGE_PVE_RETRY_STAGE = "NG_ERROR_PVE_RETRY_STAGE";
	const STATUS_RETRY_ALREADY = "0144";
	const MESSAGE_RETRY_ALREADY = "NG_ERROR_PVE_RETRY_STAGE_ALREADY";

	const STATUS_PVB_START_FAIL = "0151";
	const MESSAGE_PVB_START_FAIL = "NG_ERROR_PVB_START_FAIL";
	const STATUS_PVB_LACK_GOLD = "0152";
	const MESSAGE_PVB_LACK_GOLD = "NG_ERROR_PVB_LACK_GOLD";
	const STATUS_PVB_LACK_CASH = "0153";
	const MESSAGE_PVB_LACK_CASH = "NG_ERROR_PVB_LACK_CASH";
	const STATUS_PVB_LACK_PVBPOINT = "0154";
	const MESSAGE_PVB_LACK_PVBPOINT = "NG_ERROR_PVB_LACK_PVBPOINT";

	const STATUS_PVB_SAVEINFO = "0161";
	const MESSAGE_PVB_SAVEINFO = "NG_ERROR_PVB_SAVEINFO";

	const STATUS_SUR_START_FAIL = "0171";
	const MESSAGE_SUR_START_FAIL = "NG_ERROR_SUR_START_FAIL";
	const STATUS_SUR_LACK_GOLD = "0172";
	const MESSAGE_SUR_LACK_GOLD = "NG_ERROR_SUR_LACK_GOLD";
	const STATUS_SUR_LACK_CASH = "0173";
	const MESSAGE_SUR_LACK_CASH = "NG_ERROR_SUR_LACK_CASH";
	const STATUS_SUR_LACK_PVBPOINT = "0174";
	const MESSAGE_SUR_LACK_PVBPOINT = "NG_ERROR_SUR_LACK_PVBPOINT";

	const STATUS_SUR_SAVEINFO = "0181";
	const MESSAGE_SUR_SAVEINFO = "NG_ERROR_SUR_SAVEINFO";

	const STATUS_LOAD_RIVAL = "0191";
	const MESSAGE_LOAD_RIVAL = "NG_ERROR_LOAD_RIVAL";
	const STATUS_PVP_START = "0192";
	const MESSAGE_PVP_START = "NG_ERROR_PVP_START";
	const STATUS_PVP_LACK_GOLD = "0193";
	const MESSAGE_PVP_LACK_GOLD = "NG_ERROR_PVP_LACK_GOLD";
	const STATUS_PVP_LACK_CASH = "0194";
	const MESSAGE_PVP_LACK_CASH = "NG_ERROR_PVP_LACK_CASH";
	const STATUS_PVP_LACK_PVBPOINT = "0195";
	const MESSAGE_PVP_LACK_PVBPOINT = "NG_ERROR_PVP_LACK_PVBPOINT";

	const STATUS_PVP_SAVEINFO = "0201";
	const MESSAGE_PVP_SAVEINFO = "NG_ERROR_PVP_SAVEINFO";

	const STATUS_UPGRADE_NON_CHARACTER = "0211";
	const MESSAGE_UPGRADE_NON_CHARACTER = "NG_ERROR_UPGRADE_NON_CHARACTER";
	const STATUS_UPGRADE_LOAD_COST = "0212";
	const MESSAGE_UPGRADE_LOAD_COST = "NG_ERROR_UPGRADE_LOAD_COST";
	const STATUS_UPGRADE_RACK_COST = "0213";
	const MESSAGE_UPGRADE_RACK_COST = "NG_ERROR_UPGRADE_RACK_COST";
	const STATUS_UPGRADE_RESULT_CHARACTER = "0214";
	const MESSAGE_UPGRADE_RESULT_CHARACTER = "NG_ERROR_UPGRADE_RESULT_CHARACTER";

	const STATUS_SYNTHESIZE_NON_CHARACTER = "0221";
	const MESSAGE_SYNTHESIZE_NON_CHARACTER = "NG_ERROR_SYNTHESIZE_NON_CHARACTER";
	const STATUS_SYNTHESIZE_ERROR_GARADE = "0222";
	const MESSAGE_SYNTHESIZE_ERROR_GARADE = "NG_ERROR_SYNTHESIZE_ERROR_GARADE";
	const STATUS_SYNTHESIZE_FAIL = "0223";
	const MESSAGE_SYNTHESIZE_FAIL = "NG_ERROR_SYNTHESIZE_FAIL";

	const STATUS_ACHIEVE_UPDATE = "0231";
	const MESSAGE_ACHIEVE_UPDATE = "NG_ERROR_ACHIEVE_UPDATE";

	const STATUS_ACHIEVE_REWARD_GIVED = "0241";
	const MESSAGE_ACHIEVE_REWARD_GIVED = "NG_ERROR_ACHIEVE_REWARD_GIVED";
	const STATUS_ACHIEVE_REWARD_EMPTY = "0242";
	const MESSAGE_ACHIEVE_REWARD_EMPTY = "NG_ERROR_ACHIEVE_REWARD_EMPTY";
	const STATUS_ACHIEVE_REWARD_GIVE = "0243";
	const MESSAGE_ACHIEVE_REWARD_GIVE = "NG_ERROR_ACHIEVE_REWARD_GIVE";
	const STATUS_ACHIEVE_REWARD_DISCORD = "0244";
	const MESSAGE_ACHIEVE_REWARD_DISCORD = "NG_ERROR_ACHIEVE_REWARD_DISCORD";

	const STATUS_SELL_PRICE = "0251";
	const MESSAGE_SELL_PRICE = "NG_ERROR_SELL_PRICE";

	const STATUS_EXPLORATE_CHARACTER = "0261";
	const MESSAGE_EXPLORATE_CHARACTER = "NG_ERROR_EXPLORATE_CHARACTER";
	const STATUS_EXPLORATE_START = "0262";
	const MESSAGE_EXPLORATE_START = "NG_ERROR_EXPLORATE_START";

	const STATUS_EXPLORATE_COMPLETE = "0271";
	const MESSAGE_EXPLORATE_COMPLETE = "NG_ERROR_EXPLORATE_COMPLETE";

	const STATUS_EXPLORATE_REWARD_EMPTY = "0281";
	const MESSAGE_EXPLORATE_REWARD_EMPTY = "NG_ERROR_EXPLORATE_REWARD_EMPTY";
	const STATUS_EXPLORATE_REWARD_GIVE = "0282";
	const MESSAGE_EXPLORATE_REWARD_GIVE = "NG_ERROR_EXPLORATE_REWARD_GIVE";

	const STATUS_EXPLORATE_NON_COMPLTE = "0291";
	const MESSAGE_EXPLORATE_NON_COMPLTE = "NG_ERROR_EXPLORATE_NON_COMPLTE";

	const STATUS_FRIEND_NOBODY = "0301";
	const MESSAGE_FRIEND_NOBODY = "NG_ERROR_FRIEND_NOBODY";

	const STATUS_FRIEND_ADD = "0311";
	const MESSAGE_FRIEND_ADD = "NG_ERROR_FRIEND_ADD";
	const STATUS_FRIEND_REQUEST = "0312";
	const MESSAGE_FRIEND_REQUEST = "NG_ERROR_FRIEND_REQUEST";

	const STATUS_FRIEND_ADD_OVER = "0321";
	const MESSAGE_FRIEND_ADD_OVER = "NG_ERROR_FRIEND_ADD_OVER";
	const STATUS_FRIEND_REQUEST_OVER = "0322";
	const MESSAGE_FRIEND_REQUEST_OVER = "NG_ERROR_FRIEND_REQUEST_OVER";

	const STATUS_FRIEND_DEL_FRIEND_OVER = "0331";
	const MESSAGE_FRIEND_DEL_FRIEND_OVER = "NG_ERROR_FRIEND_DEL_FRIEND_OVER";
	const STATUS_FRIEND_DEL_PROCESS = "0332";
	const MESSAGE_FRIEND_DEL_PROCESS = "NG_ERROR_FRIEND_DEL_PROCESS";

	const STATUS_FRIENDSHIP_LIMIT = "0341";
	const MESSAGE_FRIENDSHIP_LIMIT = "NG_ERROR_FRIENDSHIP_LIMIT";
	const STATUS_FRIENDSHIP_SEND = "0342";
	const MESSAGE_FRIENDSHIP_SEND = "NG_ERROR_FRIENDSHIP_SEND";

	const STATUS_PILOT_DUPLICATE = "0351";
	const MESSAGE_PILOT_DUPLICATE = "NG_ERROR_PILOT_DUPLICATE";

	const STATUS_RANK_EMPTY = "0361";
	const MESSAGE_RANK_EMPTY = "NG_ERROR_RANK_EMPTY";
	const STATUS_REWARD_GIVED = "0362";
	const MESSAGE_REWARD_GIVED = "NG_ERROR_REWARD_GIVED";
	const STATUS_REWARD_GIVE = "0363";
	const MESSAGE_REWARD_GIVE = "NG_ERROR_REWARD_GIVE";

	const STATUS_JOIN_GUEST = "0371";
	const MESSAGE_JOIN_GUEST = "NG_ERROR_JOIN_GUEST";

	const STATUS_ABNORMALPRODUCT = "0381";
	const MESSAGE_ABNORMALPRODUCT = "NG_ERROR_ABNORMALPRODUCT";
	const STATUS_PROVIDE_PRODUCT = "0382";
	const MESSAGE_PROVIDE_PRODUCT = "NG_ERROR_PROVIDE_PRODUCT";
	const STATUS_RECEIPT_INFO = "0383";
	const MESSAGE_RECEIPT_INFO = "NG_ERROR_RECEIPT_INFO";
	const STATUS_CONSUME = "0384";
	const MESSAGE_CONSUME = "NG_ERROR_CONSUME";
	const STATUS_RECEIPT_INFO_DISCORD = "0385";
	const MESSAGE_RECEIPT_INFO_DISCORD = "NG_ERROR_RECEIPT_INFO_DISCORD";
	const STATUS_DUPLICATE_PURCHASE = "0386";
	const MESSAGE_DUPLICATE_PURCHASE = "NG_ERROR_DUPLICATE_PURCHASE";

	const STATUS_INFO_PVE_RANK = "0391";
	const MESSAGE_INFO_PVE_RANK = "NG_ERROR_INFO_PVE_RANK";

	const STATUS_JOIN_PARTNERSHIP = "0401";
	const MESSAGE_JOIN_PARTNERSHIP = "NG_ERROR_JOIN_PARTNERSHIP";

	const STATUS_EVOLUTION_LEVEL = "0411";
	const MESSAGE_EVOLUTION_LEVEL = "NG_ERROR_EVOLUTION_LEVEL";
	const STATUS_EVOLUTION_INFO = "0412";
	const MESSAGE_EVOLUTION_INFO = "NG_ERROR_EVOLUTION_INFO";
	const STATUS_EVOLUTION_FAIL = "0413";
	const MESSAGE_EVOLUTION_FAIL = "NG_ERROR_EVOLUTION_FAIL";

	const STATUS_UPGRADE_NON_ITEM = '0421';
	const MESSAGE_UPGRADE_NON_ITEM = 'NG_ERROR_UPGRADE_NON_ITEM';
	const STATUS_UPGRADE_ITEM_LOAD_COST = '0422';
	const MESSAGE_UPGRADE_ITEM_LOAD_COST = 'NG_ERROR_UPGRADE_LOAD_COST';
	const STATUS_UPGRADE_ITEM_RACK_COST = '0423';
	const MESSAGE_UPGRADE_ITEM_RACK_COST = 'NG_ERROR_UPGRADE_RACK_COST';
	const STATUS_UPGRADE_RESULT_ITEM = '0424';
	const MESSAGE_UPGRADE_RESULT_ITEM = 'NG_ERROR_UPGRADE_RESULT_ITEM';

	const STATUS_SYNTHESIZE_NON_ITEM = '0431';
	const MESSAGE_SYNTHESIZE_NON_ITEM = 'NG_ERROR_SYNTHESIZE_NON_ITEM';
	const STATUS_SYNTHESIZE_ERROR_ITEM_GARADE = '0432';
	const MESSAGE_SYNTHESIZE_ERROR_ITEM_GARADE = 'NG_ERROR_SYNTHESIZE_ERROR_ITEM_GARADE';
	const STATUS_SYNTHESIZE_ITEM_RACK_COST = '0433';
	const MESSAGE_SYNTHESIZE_ITEM_RACK_COST = 'NG_ERROR_SYNTHESIS_RACK_COST';
	const STATUS_SYNTHESIZE_ITEM_FAIL = '0434';
	const MESSAGE_SYNTHESIZE_ITEM_FAIL = 'NG_ERROR_SYNTHESIZE_FAIL';

	const STATUS_ENERGY_BONUS_TIME_ERROR = '0441';
	const MESSAGE_ENERGY_BONUS_TIME_ERROR = 'NG_ERROR_ENERGY_BONUS_TIME_ERROR';
	const STATUS_ENERGY_NOT_BONUS_TIME = '0442';
	const MESSAGE_ENERGY_NOT_BONUS_TIME = 'NG_ERROR_ENERGY_NOT_BONUS_TIME';

	const STATUS_RESET_LACK_CASH = "0451";
	const MESSAGE_RESET_LACK_CASH = "NG_ERROR_RESET_LACK_CASH";
	const STATUS_RESET_NO_CHARACTER = "0452";
	const MESSAGE_RESET_NO_CHARACTER = "NG_ERROR_RESET_NO_CHARACTER";

	const STATUS_BY_CODE = "010";

	const STATUS_NO_DATA = "0000";
	const MESSAGE_NO_DATA = "데이터가 없음";

	const STATUS_UPDATE_NOTHING = "300";
	const MESSAGE_UPDATE_NOTHING = "데이터 처리 오류";

	const STATUS_INTERFACE_NOTHING = "400";
	const MESSAGE_INTERFACE_NOTHING = "해당 Interface가 존재하지 않음";

	const STATUS_DUPLICATION_DATA = "500";
	const MESSAGE_DUPLICATION_DATA = "중복 데이터 존재함";

	//const for table name
		//table in koc_play database
	const TBL_ACCOUNT = "account";
	const TBL_RESTRICTMACADDR = "restrict_mac";
	const TBL_ACCOUNT_PUSHKEY = "account_pushkey";
	const TBL_ACCOUNT_CURSESSION = "account_cursession";

	const TBL_PLAYERBASIC = "player_basic";
	const TBL_PACKAGE_LOG = "package_log";
	const TBL_PLAYEREQUIPMENT = "player_equipment";
	const TBL_PLAYERINVENTORY = "player_inventory";
	const TBL_PLAYERCHARACTER = "player_character";
	const TBL_PLAYERITEM = "player_item";
	const TBL_PLAYERACHIEVE = "player_achieve";
	const TBL_PLAYERTEAM = "player_team";
	const TBL_PLAYERATTEND = "player_attend";
	const TBL_PLAYERCOLLECTION = "player_collection";
	const TBL_PLAYERFRIEND = "player_friend";
	const TBL_PLAYERLOG = "player_log";
	const TBL_PLAYERERRLOG = "player_errlog";
	const TBL_PLAYERASSETLOG = "player_asset_logging";
	const TBL_PLAYERVIP = "player_vip";
	const TBL_PLAYERLEV = "player_level";
	const TBL_PLAYEREXTRAATTEND = "player_extraattend";
	const TBL_PLAYERIAP = "player_iap";
	const TBL_PLAYERENREQ = "player_enreq";

		//table in koc_ref database
	const TBL_DATAFILES = "datafiles";
	const TBL_PRODUCT = "product";
	const TBL_PRODUCTPRICE = "product_price";
	const TBL_DAILYREWARD = "daily_reward";
	const TBL_GATCHA = "gatcha";
	const TBL_GATCHA_SIM = "gatcha_sim";
	const TBL_GATCHA_RESULT = "gatcha_result";
	const TBL_GATCHA_EVENT = "gatcha_event";
	const TBL_GATCHA_EVENT_SIM = "gatcha_event_sim";
	const TBL_GATCHA_EVENT_RESULT = "gatcha_event_result";
	const TBL_REFCHARACTER = "ref_character";
	const TBL_ITEM = "item";
	const TBL_REWARD = "reward";
	const TBL_STAGE = "stage";
	const TBL_ACHIEVEMENTS = "achievements";
	const TBL_UPGRADEITEM = 'upgradeItem';
	const TBL_UPGRADE = "upgrade";
	const TBL_EXPREF = "exploration_ref_sect";
	const TBL_LEVINFO = "levinfo";
	const TBL_ITEMLEVINFO = 'itemlevinfo';
	const TBL_ARTICLE = "article";
	const TBL_TEXT = "text";
	const TBL_RANKREWARD = "rank_reward";
	const TBL_VIP = "vip";
	const TBL_SUPPLIES = "supplies";
	const TBL_PLAYERLEVINFO = "player_levinfo";
	const TBL_PLAYERREWARD = "player_reward";
	const TBL_VIPLEVINFO = "vip_levinfo";
	const TBL_VIPREWARD = "vip_reward";
	const TBL_EVENTATTEND = "event_attend";
	const TBL_ITEMUPREF = "item_upgrade_reference";

		//table in koc_mail database
	const TBL_MAIL = "mail";

		//table in koc_record, koc_rank
	const TBL_PVE = "pve";
	const TBL_PVB = "pvb";
	const TBL_SURVIVAL = "survival";
	const TBL_PVP = "pvp";
	const TBL_PVBSTORE = "pvb_store";
	const TBL_SURVIVALSTORE = "survival_store";
	const TBL_PVPSTORE = "pvp_store";
	const TBL_EXPGRP = "exploration_group";
	const TBL_EXP = "exploration";
	const TBL_PVPLASTWEEK = "pvp_lastweek";
	const TBL_PVBLASTWEEK = "pvb_lastweek";
	const TBL_SURVIVALLASTWEEK = "survival_lastweek";

	//const for mail
	const SENDER_GM	= 0;
	const NORMAL_EXPIRE_TERM = 72;											// 기본 메일 만료시간 (72시간)
	const DAILY_REWARD_TITLE = "NG_MESSAGE_MAIL_REWARD_AT";					// 출석보상 기본 메세지 내용
//	const FRIENDSHIP_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_FR";				// 우정포인트선물 기본 메세지 내용
	const FRIENDSHIP_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_FRIENDHELP";		// 오류로 임시 변경
	const ACHIEVEMENT_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_MI";				// 업적보상 기본 메세지 내용
	const EXPLORATION_REWARD_TITLE = "NG_MESSAGE_MAIL_REWARD_EX";			// 탐색 보상 기본 타이틀
	const PVBRANKING_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_PVB";				// PVB랭킹보상 기본 메세지 내용
	const SURRANKING_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_SUR";				// SURVIVAL랭킹보상 기본 메세지 내용
	const PVPRANKING_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_PVP";				// PVP랭킹보상 기본 메세지 내용
	const EVENTREWARD_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_EV";				// 이벤트 보상 기본 메세지 내용
	const VIPREWARD_SEND_TITLE = "NG_MESSAGE_MAIL_VIP";						// VIP보상 기본 메세지 내용
	const ACCESS_EVENT_REWARD_TITLE = "NG_MESSAGE_MAIL_REWARD_EV";			// 접속이벤트 기본 메세지 내용
	const CHARMAXLEV_REWARD_TITLE = "NG_MESSAGE_MAIL_REWARD_MAXLEVEL";		// 캐릭터레벨달성 기본 메세지 내용
	const FRIENDHELP_REWARD_TITLE = "NG_MESSAGE_MAIL_REWARD_FRIENDHELP";	// 친구도움 기본 메세지 내용
	const PACKAGE_SEND_TITLE = "NG_MESSAGE_MAIL_BUY_ITEM";					// 패키지구매 추가지급 기본 메세지 내용
	const COUPON_SEND_TITLE = "NG_MESSAGE_MAIL_REWARD_COUPON";				// 쿠폰 입력 보상 기본 메세지 내용
	const FRIENDSHIP_ARTICLE_ID = "FRIENDSHIP_POINTS";						// 우정포인트아티클 아이디
	const FRIENDSHIP_SEND_BASIC_VALUE = 10;									// 우정포인트선물 기본 수량

	//const for player
	const MAX_CHAR_CAPACITY					= 50;	// 기본 캐릭터 보유량
	const MAX_WEPN_CAPACITY					= 60;	// 기본 무기 보유량
	const MAX_GEAR_CAPACITY					= 150;	// 기본 기어 보유량

	const MAX_TEAM							= 3;	// 기본 팀 수
	const MAX_CHARACTER_SLOT				= 3;	// 기본 캐릭터슬롯 수
	const MAX_EXPLORATION					= 5;	// 기본 탐색 회수
	const MAX_FRIENDS						= 50;	// 기본 친구 수
	const MAX_DEL_FRIEND					= 10;	// 일일 친구삭제 수
	const MAX_UPGRADE						= 5;	// 기본 최대 강화수치
	const MAX_LEVEL							= 30;	// 기본 최대 강화수치
	const MAX_INC_LIMIT_CHAR				= 100;	// 최대 캐릭터 증가 슬롯
	const MAX_INC_LIMIT_ITEM				= 90;	// 최대 아이템 증가 슬롯
	const MAX_INC_LIMIT_GEAR				= 100;	// 최대 아이템 증가 슬롯

	const MAX_ENERGY_POINTS					= 50;	// 기본 행성전 에너지
	const MAX_MODES_PVB						= 10;	// 기본 PVB 에너지
	const MAX_MODES_PVP						= 10;	// 기본 PVP 에너지
	const MAX_MODES_DEFENCE					= 10;	// 기본 DEFENCE 에너지
	const MAX_MODES_SURVIVAL				= 10;	// 기본 생존 에너지
	const ADDITIONAL_MODE_PVP_PRICE			= 5;	// 추가 PVP 이용시 캐시 가격
	const ADDITIONAL_MODE_PVB_PRICE			= 5;	// 추가 PVB 이용시 캐시 가격
	const ADDITIONAL_MODE_SURVIVAL_PRICE	= 5;	// 추가 SURVIVAL 이용시 캐시 가격

	const ENERGY_RECHARGE_INTERVAL			= 600;	// 에너지(행성전) 충전 시간
	const MODE_PVB_RECHARGE_INTERVAL		= 1800;	// 에너지(보스) 충전 시간
	const MODE_PVP_RECHARGE_INTERVAL		= 30;	// 에너지(대전) 충전 시간
	const MODE_DEFENSE_RECHARGE_INTERVAL	= 1800;	// 에너지(디펜스) 충전 시간
	const MODE_SURVIVAL_RECHARGE_INTERVAL	= 1800;	// 에너지(생존) 충전 시간
	const PVP_DELAY_INIT_ASSET_TYPE			= 'CASH_POINTS';
	const PVP_DELAY_INIT_ASSET_VALUE		= 50;

	const MAX_ATTEND						= 20;	// 최대 출석 일 수
	const MAX_EXTRAATTEND					= 7;	// 최대 이벤트출석 일 수

	//const for product
	const RANDOMIZE_CATEGORY				= "[\"SPECIAL\", \"GATCHA\"]";
	const RANDOMIZE_TYPE					= "[\"SINGLE\", \"PACKAGE\", \"CHARACTER\", \"WEAPON\", \"BACKPACK\", \"TECHNIQUE\"]";
	const CHARACTER_TYPE					= "[\"SINGLE\", \"PACKAGE\", \"CHARACTER\"]";
	const INVENTORY_TYPE					= "[\"WEAPON\", \"BACKPACK\", \"TECHNIQUE\"]";
	const ITEM_TYPE							= '["WTIK", "BTIK", "STIK", "WEPN", "BCPC", "SKIL"]';

	const GAMEPOINTS_PER_CHARACTER_GRADE	= '[0,100,200,400,600,800,1200]';
	const GAMEPOINTS_PER_ITEM_GRADE			= '[0,100,200,400,600,800,1200]';

	const PRODUCTTYPE_NORMAL				= 'NM';
	const PRODUCTTYPE_PACKAGE_FOREVER		= 'PF';
	const PRODUCTTYPE_PACKAGE_ENDPOINT		= 'PE';
	const PRODUCTTYPE_PACKAGE_MONTHLY		= 'PM';
	const PRODUCTTYPE_PACKAGE_DAILY			= 'PD';

	//const for points
	const LIMIT_ENERGY_POINTS				= 999;	// 최대 행성전 에너지 제한
	const LIMIT_MODES_PVP					= 999;	// 최대 PVP 에너지 제한
	const LIMIT_MODES_PVB					= 999;	// 최대 PVB 에너지 제한
	const LIMIT_MODES_DEFENCE				= 999;	// 최대 DEFENCE 에너지 제한
	const LIMIT_MODES_SURVIVAL				= 999;	// 최대 생존 에너지 제한
	const LIMIT_GAME_POINTS					= 100000000;	// 최대 골드 제한
	const LIMIT_CASH_POINTS					= 99999;	// 최대 캐시포인트 제한
	const LIMIT_FRIENDSHIP_POINTS			= 1000;	// 최대 우정포인트 제한

	const COMMON_USE_CODE					= 'use';
	const COMMON_SAVE_CODE					= 'save';

	//const for pve
	const DEFENCE_UP_ITEM_PRICE				= 400;
	const ATTACK_UP_ITEM_PRICE				= 400;
	const AUTO_SKILL_ITEM_PRICE				= 600;
	const FRIEND_TIME_EXTENT_ITEM_PRICE		= 600;
	const PRODUCT_ID_GOLD_BONUS				= 'NGP_BT_00001';
	const PRODUCT_ID_EXP_BONUS				= 'NGP_BT_00002';
	const PRODUCT_ID_ITEM_BONUS				= 'NGP_BT_00003';

	//const for ranking
	const PVP_YEARWEEK_STANDARD				= 3;	//pvp는 매주 화요일 갱신
	const PVB_YEARWEEK_STANDARD				= 4;	//pvb는 매주 수요일 갱신
	const SURVIVAL_YEARWEEK_STANDARD		= 5;	//survival은 매주 목요일 갱신
	const MAX_LEVEL_PVB						= 20;	//PVB 최대레벨
	const PVP_SCORE_DEVIDE_CONST			= 2000; //pvp 그룹 기준 상수
	const PVP_SCORE_LAST_GROUP				= 18000; // pvp 그룹 마지막 그룹시작점수

	//const for synthesize
	const GATCHA_BY_GRADE					= '["GAC030000", "GAC030001", "GAC030002", "GAC030003", "GAC030004"]';	//합성에 사용되는 가챠정보
	const GATCHA_BY_WEPN_GRADE				= '["GAW070000", "GAW070001", "GAW070002", "GAW070003", "GAW070004"]';	//무기 합성에 사용되는 가챠정보
	const GATCHA_BY_BCPC_GRADE				= '["GAG070000", "GAG070001", "GAG070002", "GAG070003", "GAG070004"]';	//백팩 합성에 사용되는 가챠정보
	const GATCHA_BY_SKIL_GRADE				= '["GAT070000", "GAT070001", "GAT070002", "GAT070003", "GAT070004"]';	//스킬 합성에 사용되는 가챠정보
	const GATCHA_BY_GEAR_GRADE				= '{"KRYPT":{"HD":["MGKR01001","MGKR02001","MGKR03001","MGKR04001","MGKR05001","MGKR06001"],"BD":["MGKR01002","MGKR02002","MGKR03002","MGKR04002","MGKR05002","MGKR06002"],"RA":["MGKR01003","MGKR02003","MGKR03003","MGKR04003","MGKR05003","MGKR06003"],"LA":["MGKR01004","MGKR02004","MGKR03004","MGKR04004","MGKR05004","MGKR06004"],"RL":["MGKR01005","MGKR02005","MGKR03005","MGKR04005","MGKR05005","MGKR06005"],"LL":["MGKR01006","MGKR02006","MGKR03006","MGKR04006","MGKR05006","MGKR06006"]},"KAIRON":{"HD":["MGKA01001","MGKA02001","MGKA03001","MGKA04001","MGKA05001","MGKA06001"],"BD":["MGKA01002","MGKA02002","MGKA03002","MGKA04002","MGKA05002","MGKA06002"],"RA":["MGKA01003","MGKA02003","MGKA03003","MGKA04003","MGKA05003","MGKA06003"],"LA":["MGKA01004","MGKA02004","MGKA03004","MGKA04004","MGKA05004","MGKA06004"],"RL":["MGKA01005","MGKA02005","MGKA03005","MGKA04005","MGKA05005","MGKA06005"],"LL":["MGKA01006","MGKA02006","MGKA03006","MGKA04006","MGKA05006","MGKA06006"]},"PADANIUM":{"HD":["MGPA01001","MGPA02001","MGPA03001","MGPA04001","MGPA05001","MGPA06001"],"BD":["MGPA01002","MGPA02002","MGPA03002","MGPA04002","MGPA05002","MGPA06002"],"RA":["MGPA01003","MGPA02003","MGPA03003","MGPA04003","MGPA05003","MGPA06003"],"LA":["MGPA01004","MGPA02004","MGPA03004","MGPA04004","MGPA05004","MGPA06004"],"RL":["MGPA01005","MGPA02005","MGPA03005","MGPA04005","MGPA05005","MGPA06005"],"LL":["MGPA01006","MGPA02006","MGPA03006","MGPA04006","MGPA05006","MGPA06006"]},"PRODIA":{"HD":["MGPR01001","MGPR02001","MGPR03001","MGPR04001","MGPR05001","MGPR06001"],"BD":["MGPR01002","MGPR02002","MGPR03002","MGPR04002","MGPR05002","MGPR06002"],"RA":["MGPR01003","MGPR02003","MGPR03003","MGPR04003","MGPR05003","MGPR06003"],"LA":["MGPR01004","MGPR02004","MGPR03004","MGPR04004","MGPR05004","MGPR06004"],"RL":["MGPR01005","MGPR02005","MGPR03005","MGPR04005","MGPR05005","MGPR06005"],"LL":["MGPR01006","MGPR02006","MGPR03006","MGPR04006","MGPR05006","MGPR06006"]},"TASTEN":{"HD":["MGTA01001","MGTA02001","MGTA03001","MGTA04001","MGTA05001","MGTA06001"],"BD":["MGTA01002","MGTA02002","MGTA03002","MGTA04002","MGTA05002","MGTA06002"],"RA":["MGTA01003","MGTA02003","MGTA03003","MGTA04003","MGTA05003","MGTA06003"],"LA":["MGTA01004","MGTA02004","MGTA03004","MGTA04004","MGTA05004","MGTA06004"],"RL":["MGTA01005","MGTA02005","MGTA03005","MGTA04005","MGTA05005","MGTA06005"],"LL":["MGTA01006","MGTA02006","MGTA03006","MGTA04006","MGTA05006","MGTA06006"]}}';	//스킬 합성에 사용되는 가챠정보

	//const for character reach maxlevel
	const CHARMAXLEV_REWARD_TYPE			= 'EVENT_POINTS';
	const CHARMAXLEV_REWARD_VALUE			= 10;

	const CHAR_EXP_FOR_MAXLEV				= 209700;

	//const for exploration
	const ARRAY_GRADE_COUNT					= '[{"count":"20","arrPlanet":[{"grade":"1","pcount":"2"},{"grade":"2","pcount":"6"},{"grade":"3","pcount":"6"},{"grade":"4","pcount":"4"},{"grade":"5","pcount":"2"}]},{"count":"21","arrPlanet":[{"grade":"1","pcount":"2"},{"grade":"2","pcount":"6"},{"grade":"3","pcount":"7"},{"grade":"4","pcount":"4"},{"grade":"5","pcount":"2"}]},{"count":"22","arrPlanet":[{"grade":"1","pcount":"2"},{"grade":"2","pcount":"7"},{"grade":"3","pcount":"7"},{"grade":"4","pcount":"4"},{"grade":"5","pcount":"2"}]},{"count":"23","arrPlanet":[{"grade":"1","pcount":"2"},{"grade":"2","pcount":"7"},{"grade":"3","pcount":"7"},{"grade":"4","pcount":"5"},{"grade":"5","pcount":"2"}]},{"count":"24","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"7"},{"grade":"3","pcount":"7"},{"grade":"4","pcount":"5"},{"grade":"5","pcount":"2"}]},{"count":"25","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"7"},{"grade":"3","pcount":"7"},{"grade":"4","pcount":"5"},{"grade":"5","pcount":"3"}]},{"count":"26","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"7"},{"grade":"3","pcount":"8"},{"grade":"4","pcount":"5"},{"grade":"5","pcount":"3"}]},{"count":"27","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"8"},{"grade":"3","pcount":"8"},{"grade":"4","pcount":"5"},{"grade":"5","pcount":"3"}]},{"count":"28","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"8"},{"grade":"3","pcount":"8"},{"grade":"4","pcount":"6"},{"grade":"5","pcount":"3"}]},{"count":"29","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"8"},{"grade":"3","pcount":"9"},{"grade":"4","pcount":"6"},{"grade":"5","pcount":"3"}]},{"count":"30","arrPlanet":[{"grade":"1","pcount":"3"},{"grade":"2","pcount":"9"},{"grade":"3","pcount":"9"},{"grade":"4","pcount":"6"},{"grade":"5","pcount":"3"}]}]';	// 탐색 행성 수 별 등급별 행성 수
	const MIN_EXP_PLANET_COUNT				= 20;	// 탐색 행성 최소 수량
	const MAX_EXP_PLANET_COUNT				= 30;	// 탐색 행성 최대 수량

	const EXP_TIME_FOR_CHAR					= '["0.00","0.00","0.04","0.08","0.12","0.16","0.20"]';	// 기체 등급별 탐색시간 비율

	const EXP_COST_BASIC_MULTIPLE			= 200;	// 탐색 비용 고정 배수 (등급 * 고정배수 = 탐색비용)
	const COMMON_EXP_REWARD_TYPE			= 'EVENT_POINTS';
	const COMMON_EXP_REWARD_VALUE			= 5;
	const COMMON_PVE_REWARD_TYPE			= 'GAME_POINTS';
	const COMMON_SURVIVAL_REWARD_TYPE		= 'GAME_POINTS';

	const COMMON_RANKING_PAGE_SIZE			= '200';
	const RECOMMENDED_FRIEND_LIST_COUNT		= '10';
	const FRIEND_STATUS_REQUEST				= '0';
	const FRIEND_STATUS_ACCEPTED			= '1';
	const FRIEND_STATUS_REJECTED			= '2';
	const FRIEND_STATUS_DELETED				= '3';
	const FRIEND_HELP_BASIC_POINT			= 10;
	const FRIEND_PRESENT_TIME				= 86400;
	const COMMON_LANGUAGE_COLUMN			= 'kr';

	//const for pvp
	const PVP_POINT_USER_MULTIPLE			= 2;
	const PVP_POINT_BASIC_WIN				= 50;
	const PVP_POINT_BASIC_LOSE				= 0;
	const PVP_POINT_TIME_DENOMINATOR		= 10;

	const COMMON_SLOT_PAYMENT_TYPE			= "CASH_POINTS";
	const COMMON_CHASLOT_PAYMENT_VALUE		= 30;
	const COMMON_INVSLOT_PAYMENT_VALUE		= 20;
	const COMMON_GERSLOT_PAYMENT_VALUE		= 20;

	//const for event
	const UPGRADE_DIS_EVENT_PRODUCT			= "upgrade";
	const UPGRADE_DIS_EVENT_CHAR_PRODUCT	= 'char';
	const UPGRADE_DIS_EVENT_ITEM_PRODUCT	= 'item';

	//const for achieve
	const ACHIEVE_REPEATE_FOR_DAILY			= "DAILY";
	const ACHIEVE_REPEATE_FOR_GENERAL		= "GENERAL";

	//const for package product
	const EVERYDAY_PROVISION_PRODUCT_ID		= "NGP_CM_0100";
	const LIMITED_PROVISION_PRODUCT_ID		= "NGP_LP_0001";
	const HAPPYNEWYEAR_PROVISION_PRODUCT_ID	= "NGP_LP_0002";

	//const for iap
	const REASONCODE_IAP_NORMAL = "00";
	const REASONCODE_RECEIPT_ALREADY_PROVISION = "01";
	const REASONCODE_CANT_GET_RECEIPT = "02";
	const REASONCODE_CONSUME_FAILED = "03";
	const REASONCODE_DOESNT_MATCH_RECEIPT = "04";
	const REASONCODE_PACKAGE_STATUS = "05";

	const ITEM_MAXLEV_BY_GRADE = '["0","10","10","10","10","10","10"]';
	const ITEM_MAX_LEV = '10';
	const GATCHA_BY_ITEM_GRADE_PAYMENT_TYPE = 'GAME_POINTS';
	const GATCHA_BY_ITEM_GRADE_PAYMENT = '["0","1000","2000","4000","7000","45000"]';
	const UNEQUIP_ASSET_TYPE = 'GAME_POINTS';
	const UNEQUIP_ASSET_VALUE = '["0","1000","3000","4000","7000","9000","18000"]';

	//const for energy bonus
	const ENBONUS_A = '[12, 13]';
	const ENBONUS_B = '[20, 21]';
	const ENBONUS_TERM_A = '50400';
	const ENBONUS_TERM_B = '28800';
	const ENERGY_BONUS_TYPE = 'ENERGY_POINTS';
	const ENERGY_BONUS_VALUE = '20';
	const ENERGY_BONUS_LOG_TEXT = '지정시간 이벤트 에너지 지급';

	//const for gearslot
	const ARRAY_GEAR_TYPE = '[{"type":"KRYPT", "probability":"1"}, {"type":"PRODIA", "probability":"1"}, {"type":"KAIRON", "probability":"1"}, {"type":"TASTEN", "probability":"1"}, {"type":"PADANIUM", "probability":"1"}]';
	const GEAR_RESET_BASIC_TYPE = 'GAME_POINTS';
	const GEAR_RESET_BASIC_VALUE = 7500;
	const GEAR_RESET_LOCK_TYPE = 'CASH_POINTS';
	const GEAR_RESET_LOCK_VALUE = 25;
	const ARRAY_GEAR_PARTS_SLOTSEQ = '["HD", "BD", "RA", "LA", "RL", "LL"]';

	public $public_key = MY_Controller::INIT_ENCRYPTION_SERVER_PUBLICKEY;
	public $private_key = MY_Controller::INIT_ENCRYPTION_SERVER_PRIVATEKEY;
	public $decoded;

	function __construct(){
		parent::__construct();
		$this->load->library( 'LogW', TRUE );

		define('DEFAULTKEY', 'dnflahen20djrspdhrmfoa20djreoqkr');
		if ( ENVIRONMENT == 'production' )
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			define('URLBASE', '/koc'.MY_Controller::VERSION_FOR_ANDROID.'/');
		}
		else if ( ENVIRONMENT == 'development' || ENVIRONMENT == 'staging' )
		{
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			define('URLBASE', '/koc/');
		}
		if ( array_key_exists( "data", $_POST ) )
		{
			$dataString = $this->NG_DECRYPT( $_POST["data"] );
			$this->decoded = json_decode( stripslashes( $dataString ), TRUE );
			if ( array_key_exists("pid", $this->decoded) == false )
			{
				$this->decoded["pid"] = '0';
			}
			$this->logw->sysLogWrite( LOG_NOTICE, $this->decoded["pid"], "requestData : ".$_POST["data"] );
		}

		//db init
		$this->load->model("api/Model_Mail", "dbMail");
		$this->load->model("api/Model_Play", "dbPlay");
		$this->load->model("api/Model_Rank", "dbRank");
		$this->load->model("api/Model_Record", "dbRecord");
		$this->load->model("api/Model_Ref", "dbRef");
		$this->load->model("api/Model_Login", "dbLogin");
		$this->load->model("api/Model_Log", "dbLog");

		date_default_timezone_set('Etc/UTC');

		$uriAddress = $_SERVER['REQUEST_URI'];

		if ( strpos($uriAddress, "/pages/admin/") !== false )
		{
			$this->load->library('session');
			$userId = $this->session->userdata('userId_session');

			if ( $userId == null )
			{
				$this->load->helper('url');

    			redirect( "/index.php/pages/admin/login/view", "refresh");
			}
		}

		if ( array_key_exists( "data", $_POST ) )
		{
			$requestData = json_decode ( stripslashes ( $_POST["data"] ), TRUE );
			if ( $requestData != "" )
			{
				if ( array_key_exists( "pid", $requestData ) )
				{
					$keyId = $requestData["pid"];
					if ( $keyId != "" )
					{
						if ( array_key_exists( "cursession", $requestData ) )
						{
							$cursession = $requestData["cursession"];
							if ( !($this->dbLogin->requestSessionCheck( $keyId, $cursession )) && $cursession != "forAdmin" )
							{
								$resultCode = MY_Controller::STATUS_LOGIN_DUP;
								$resultText = MY_Controller::MESSAGE_LOGIN_DUP;
								$arrayResult = null;

								echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $keyId, $_POST["data"] );
								exit(0);
							}
						}
						else
						{
							$resultCode = MY_Controller::STATUS_LOGIN_DUP;
							$resultText = MY_Controller::MESSAGE_LOGIN_DUP;
							$arrayResult = null;

							echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $keyId, $_POST["data"] );
							exit(0);
						}

						//$arrKey = $this->dbPlay->requestKey( $keyId )->result_array();
						//$public_key = $arrKey[0]["public_key"];
						//$private_key = $arrKey[0]["private_key"];
						$public_key = MY_Controller::INIT_ENCRYPTION_SERVER_PUBLICKEY;
						$private_key = MY_Controller::INIT_ENCRYPTION_SERVER_PRIVATEKEY;
					}
					else
					{
						$public_key = MY_Controller::INIT_ENCRYPTION_SERVER_PUBLICKEY;
						$private_key = MY_Controller::INIT_ENCRYPTION_SERVER_PRIVATEKEY;
					}
				}
				else
				{
					$public_key = MY_Controller::INIT_ENCRYPTION_SERVER_PUBLICKEY;
					$private_key = MY_Controller::INIT_ENCRYPTION_SERVER_PRIVATEKEY;
				}
			}
		}
	}

	function NG_ENCRYPT( $string, $key = NULL )
	{
		$is_enc = true;
	    if ( array_key_exists( "HTTP_REFERER", $_SERVER ) )
		{
			if ( strpos( $_SERVER["HTTP_REFERER"], "/pages/admin/" ) || strpos($_SERVER["HTTP_REFERER"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists( "REQUEST_URI", $_SERVER ) )
		{
			if ( strpos( $_SERVER["REQUEST_URI"], "/pages/admin/" ) || strpos($_SERVER["REQUEST_URI"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists("HTTP_USER_AGENT", $_SERVER ) )
		{
			if ( $_SERVER["HTTP_USER_AGENT"] == "RPT-HTTPClient/0.3-3E" || $_SERVER["HTTP_USER_AGENT"] == "Apache-HttpClient/4.2.6 (java 1.5)" || $_SERVER["HTTP_USER_AGENT"] == "curl/7.43.0")
			{
				$is_enc = false;
			}
		}

		if ( array_key_exists("HTTP_POSTMAN_TOKEN", $_SERVER) )
		{
			$is_enc = false;
		}

		if ( $is_enc )
		{
			$key = $key == NULL ? DEFAULTKEY : $key;
			return base64_encode(openssl_encrypt($string, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)));
		}
		else
		{
			return $string;
		}
    }

    function NG_DECRYPT( $encrypted_string, $key = NULL )
    {
		$is_enc = true;
	    if ( array_key_exists( "HTTP_REFERER", $_SERVER ) )
		{
			if ( strpos( $_SERVER["HTTP_REFERER"], "/pages/admin/" ) || strpos($_SERVER["HTTP_REFERER"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists( "REQUEST_URI", $_SERVER ) )
		{
			if ( strpos( $_SERVER["REQUEST_URI"], "/pages/admin/" ) || strpos($_SERVER["REQUEST_URI"], "apiTest.php") )
			{
				$is_enc = false;
			}
		}
		if ( array_key_exists("HTTP_USER_AGENT", $_SERVER ) )
		{
			if ( $_SERVER["HTTP_USER_AGENT"] == "RPT-HTTPClient/0.3-3E" || $_SERVER["HTTP_USER_AGENT"] == "Apache-HttpClient/4.2.6 (java 1.5)" || $_SERVER["HTTP_USER_AGENT"] == "curl/7.43.0" )
			{
				$is_enc = false;
			}
		}

		if ( array_key_exists("HTTP_POSTMAN_TOKEN", $_SERVER) )
		{
			$is_enc = false;
		}

		if ( $is_enc )
		{
			$key = $key == NULL ? DEFAULTKEY : $key;
			return openssl_decrypt(base64_decode($encrypted_string), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
	    }
	    else
	    {
		    return $encrypted_string;
	    }
    }

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	function API_RETURN_MESSAGE( $status, $message, $arrayResult, $pid, $reqData )
	{
		if ( $pid == "" || $pid == null )
		{
			$pid = "0";
		}
		$cur_date = $this->dbPlay->getCurrentTimeUTC()->result_array();
		if ( empty( $cur_date ) )
		{
			$cur_date = date("Y-m-d H:i:s");
		}
		else
		{
			$cur_date = $cur_date[0]["curTime"];
		}
		$this->benchmark->mark('total_execution_time_endbf');
		$strReturn = json_encode( array( 'resultCd'=>$status, 'resultMsg'=>$message, 'loadingTime'=>$this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_endbf'), 'memusage'=>(double)((int)((((double)memory_get_usage(true) - (double)MEMUSECHK) / (double)1024 / (double)1024) * 1000) / 1000), 'cur_date' => $cur_date, 'arrResult'=>$arrayResult ), JSON_UNESCAPED_UNICODE);

		$this->logw->sysLogWrite( LOG_NOTICE, $pid, "responseData : ".$strReturn, $status );

		$strReturn = $this->NG_ENCRYPT( $strReturn );

		return $strReturn;
	}

	function ADM_RETURN_MESSAGE( $status, $message, $arrayResult, $reqData )
	{
		$userId = "";//$this->session->userdata('userId_session');
		$this->logw->admLogWrite( LOG_NOTICE, "reponseData : ".json_encode( array( 'resultCd'=>$status, 'resultMsg'=>$message, 'admin_id'=>$userId, 'arrResult'=>$arrayResult ), JSON_UNESCAPED_UNICODE) );

		//return json_encode( array( 'resultCd'=>$status, 'resultMsg'=>$message, 'admin_id'=>$userId, 'loadingTime'=>$this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_endbf'), 'memusage'=>(string)(((double)memory_get_usage(true) - (double)MEMUSECHK) / (double)1024 / (double)1024), 'arrResult'=>$arrayResult ), JSON_UNESCAPED_UNICODE);
		return json_encode( array( 'resultCd'=>$status, 'resultMsg'=>$message, 'admin_id'=>$userId, 'arrResult'=>$arrayResult ), JSON_UNESCAPED_UNICODE);
	}

	function onSysLogWriteDb( $pid, $logcontent )
    {
	    if ( mb_substr($_SERVER['REQUEST_URI'], mb_strlen($_SERVER['REQUEST_URI']) - 1, 1) == "/" )
	    {
		    $called_name = mb_substr($_SERVER['REQUEST_URI'], 0, mb_strrpos($_SERVER['REQUEST_URI'], "/"));
	    }
	    else
	    {
    	    $called_name = $_SERVER['REQUEST_URI'];
		}
        $called_name = mb_substr($called_name, -(mb_strlen($called_name) - mb_strrpos($called_name, "/") - 1));
        if ( $called_name == "" || $called_name == null )
        {
            $called_name = $_SERVER['REQUEST_URI'];
        }
     	$this->dbLog->requestLog( $pid, $called_name, $logcontent );
    }

    // RSA 공개키를 사용하여 문자열을 암호화한다.
	// 암호화할 때는 비밀번호가 필요하지 않다.
	// 오류가 발생할 경우 false를 반환한다.

	function rsa_encrypt( $plaintext )
	{
	    // 공개키를 사용하여 암호화한다.
	    $pubkey_decoded = openssl_pkey_get_public( $this->public_key );
	    if ($pubkey_decoded === false) return false;

		// Encrypt the data in small chunks and then combine and send it.
		$chunkSize = ceil(openssl_pkey_get_details($pubkey_decoded)["bits"] / 8) - 11;

	    $ciphertext = false;
		while ($plaintext)
		{
		    $chunk = substr($plaintext, 0, $chunkSize);
		    $plaintext = substr($plaintext, $chunkSize);
		    $encrypted = '';
		    if (!openssl_public_encrypt($chunk, $encrypted, $pubkey_decoded))
		    {
		        die('Failed to encrypt data');
		    }
		    $ciphertext .= $encrypted;
		}
		openssl_free_key($pubkey_decoded);

	    // 암호문을 base64로 인코딩하여 반환한다.

	    return base64_encode($ciphertext);
	}

	function rsa_encrypt_array( $plaintext )
	{
	    // 공개키를 사용하여 암호화한다.
	    $pubkey_decoded = openssl_pkey_get_public( $this->public_key );
	    if ($pubkey_decoded === false) return false;

		// Encrypt the data in small chunks and then combine and send it.
		$chunkSize = ceil(openssl_pkey_get_details($pubkey_decoded)["bits"] / 8) - 11;

	    $ciphertext = false;
		while ($plaintext)
		{
		    $chunk = substr($plaintext, 0, $chunkSize);
		    $plaintext = substr($plaintext, $chunkSize);
		    $encrypted = '';
		    if (!openssl_public_encrypt($chunk, $encrypted, $pubkey_decoded))
		    {
		        die('Failed to encrypt data');
		    }
		    $ciphertext .= "|".base64_encode($encrypted);
		}
		openssl_free_key($pubkey_decoded);

	    // 암호문을 base64로 인코딩하여 반환한다.

	    return substr($ciphertext, 1, strlen($ciphertext) - 1);
	}

	function rsa_decrypt($ciphertext)
	{
	    // 암호문을 base64로 디코딩한다.
	    $ciphertext = base64_decode($ciphertext, true);
	    if ($ciphertext === false) return false;

	    // 개인키를 사용하여 복호화한다.
	    $privkey_decoded = openssl_pkey_get_private($this->private_key);
	    if ($privkey_decoded === false) return false;

		// Decrypt the data in the small chunks
		$chunkSize = ceil(openssl_pkey_get_details($privkey_decoded)["bits"] / 8);

	    $plaintext = false;
	    while ($ciphertext)
		{
		    $chunk = substr($ciphertext, 0, $chunkSize);
		    $ciphertext = substr($ciphertext, $chunkSize);
		    $decrypted = '';
		    if (!openssl_private_decrypt($chunk, $decrypted, $privkey_decoded))
		    {
		        die('Failed to decrypt data');
		    }
		    $plaintext .= $decrypted;
		}
		openssl_free_key($privkey_decoded);

	    if ($plaintext === false) return false;

	    // 이상이 없는 경우 평문을 반환한다.
	    return $plaintext;
	}

	function rsa_decrypt_array($ciphertext)
	{
	    // 암호문을 base64로 디코딩한다.
	    $arrciphertext = explode("|", $ciphertext);
	    $plaintext = false;
	    foreach($arrciphertext as $row)
	    {
		    $rowciphertext = base64_decode($row, true);
		    if ($rowciphertext === false) return false;

		    // 개인키를 사용하여 복호화한다.
		    $privkey_decoded = openssl_pkey_get_private($this->private_key);
		    if ($privkey_decoded === false) return false;

			// Decrypt the data in the small chunks
			$chunkSize = ceil(openssl_pkey_get_details($privkey_decoded)["bits"] / 8);

		    $rowplaintext = false;
		    $decrypted = '';
		    if (!openssl_private_decrypt($rowciphertext, $decrypted, $privkey_decoded))
		    {
		        die('Failed to decrypt data');
		    }
		    $rowplaintext = $decrypted;
			openssl_free_key($privkey_decoded);

		    if ($rowplaintext === false) return false;
		    $plaintext .= $rowplaintext;
		}

	    // 이상이 없는 경우 평문을 반환한다.
	    return $plaintext;
	}

    //공통 : 재화 보유액과 비교한 후 사용, 또는 적립
    function updatePoint( $pid, $use_gubun, $point_type, $point_value, $loggingText )
    {
	    $incInfo = $this->dbPlay->requestPlayerIns( $pid )->result_array()[0];

    	if ( strcmp($use_gubun, "save") == 0 )
    	{
	    	$this->dbPlay->updatePlayerPoint( $pid, $point_type, $point_value, $incInfo["inc_eng"], $incInfo["inc_pvb"], $incInfo["inc_pvp"], $incInfo["inc_survival"] );
			$this->dbLog->requestLoggingUseAssets( $pid, $use_gubun, $point_type, $point_value, $loggingText );
		    return true;
		}
		else if ( strcmp($use_gubun, "use") == 0 )
		{
			if ( $point_type == "CASH_POINTS" )
			{
				$remain_item = $this->dbPlay->requestItem( $pid )->result_array()[0];
				if ( $remain_item["cash_points"] >= $point_value )
				{
					$result = (bool)$this->dbPlay->paymentCharge( $pid, $point_type, $point_value, $incInfo["inc_eng"], $incInfo["inc_pvb"], $incInfo["inc_pvp"], $incInfo["inc_survival"] );
					$this->dbLog->requestLoggingUseAssets( $pid, $use_gubun, $point_type, $point_value, $loggingText );
				}
				else
				{
					if ( $remain_item["cash_points"] > 0 )
					{
						$result = (bool)$this->dbPlay->paymentCharge( $pid, $point_type, $remain_item["cash_points"], $incInfo["inc_eng"], $incInfo["inc_pvb"], $incInfo["inc_pvp"], $incInfo["inc_survival"] );
						$this->dbLog->requestLoggingUseAssets( $pid, $use_gubun, $point_type, $remain_item["cash_points"], $loggingText );
					}
					else
					{
						$result = (bool)1;
					}
					$result = $result & (bool)$this->dbPlay->paymentCharge( $pid, "EVENT_POINTS", $point_value - $remain_item["cash_points"], $incInfo["inc_eng"], $incInfo["inc_pvb"], $incInfo["inc_pvp"], $incInfo["inc_survival"] );
					$this->dbLog->requestLoggingUseAssets( $pid, $use_gubun, "EVENT_POINTS", $point_value - $remain_item["cash_points"], $loggingText );
				}
			}
			else
			{
				$result = (bool)$this->dbPlay->paymentCharge( $pid, $point_type, $point_value, $incInfo["inc_eng"], $incInfo["inc_pvb"], $incInfo["inc_pvp"], $incInfo["inc_survival"] );
				$this->dbLog->requestLoggingUseAssets( $pid, $use_gubun, $point_type, $point_value, $loggingText );
			}
		    return $result;
		}
		else
		{
			return false;
		}
    }

    function calcurateEnergy( $pid )
    {
    	if ( $pid )
    	{
	    	$incInfo = $this->dbPlay->requestPlayerIns( $pid )->result_array()[0];
    		// 현재 디비값 불러오기
    			//시간값 보정(혹시 있을지도 모를 오류를 대비하여 보정
    		$curDataArray = $this->dbPlay->requestItemWithTime( $pid )->result_array()[0];

		    //에너지가 MY_Controller::MAX_ENERGY_POINTS(10)개 이상이면 충전이 진행되지 않음
			//다음 에너지 충전이 존재하지 않음 null
			$currentTime = $this->dbPlay->getCurrentTime()->result_array();
			$currentTime = strtotime($currentTime[0]["curTime"]);

			if ( intval($curDataArray["energy_points"]) >= ( intval(MY_Controller::MAX_ENERGY_POINTS) + intval($incInfo["inc_eng"]) ) )
			{
				$curDataArray["energy_uptime"] = -1;
			}
			else
			{
				$lastEnergyTime = $curDataArray["energy_uptime"];
				$lastEnergyTime = strtotime((string)$lastEnergyTime);
				$diffInSec = intval($currentTime) - intval($lastEnergyTime);

				//지나간 시간만큼 하트를 더함 (MY_Controller::HEART_REFILL_SECONDS(1200초))
				$curDataArray["energy_points"] = intval($curDataArray["energy_points"]) + intval($diffInSec / MY_Controller::ENERGY_RECHARGE_INTERVAL);

				if ( intval($curDataArray["energy_points"]) >= ( intval(MY_Controller::MAX_ENERGY_POINTS) + intval($incInfo["inc_eng"]) ) )
				{
					$curDataArray["energy_points"] = intval(MY_Controller::MAX_ENERGY_POINTS) + intval($incInfo["inc_eng"]);
					$curDataArray["energy_uptime"] = -1;
				}
				else
				{
					$curDataArray["energy_uptime"] = intval($diffInSec) % intval(MY_Controller::ENERGY_RECHARGE_INTERVAL);
				}
			}

		    //에너지가 MY_Controller::MAX_MODES_PVB(10)개 이상이면 충전이 진행되지 않음
			//다음 에너지 충전이 존재하지 않음 null
			if ( $curDataArray["pvb_points"] >= ( MY_Controller::MAX_MODES_PVB + $incInfo["inc_pvb"] ) )
			{
				$curDataArray["pvb_uptime"] = -1;
			}
			else
			{
				$lastEnergyTime = $curDataArray["pvb_uptime"];
				$lastEnergyTime = strtotime((string)$lastEnergyTime);
				$diffInSec = (int)$currentTime - (int)$lastEnergyTime;

				//지나간 시간만큼 하트를 더함 (MY_Controller::HEART_REFILL_SECONDS(1200초))
				$curDataArray["pvb_points"] = intval($curDataArray["pvb_points"]) + floor($diffInSec / MY_Controller::MODE_PVB_RECHARGE_INTERVAL);

				if ( intval($curDataArray["pvb_points"]) >= ( intval(MY_Controller::MAX_MODES_PVB) + intval($incInfo["inc_pvb"]) ) )
				{
					$curDataArray["pvb_points"] = ( intval(MY_Controller::MAX_MODES_PVB) + intval($incInfo["inc_pvb"]) );
					$curDataArray["pvb_uptime"] = -1;
				}
				else
				{
					$curDataArray["pvb_uptime"] = intval($diffInSec) % intval(MY_Controller::MODE_PVB_RECHARGE_INTERVAL);
				}
			}

			//에너지가 MY_Controller::MAX_MODES_SURVIVAL(10)개 이상이면 충전이 진행되지 않음
			//다음 에너지 충전이 존재하지 않음 null
			if ( $curDataArray["survival_points"] >= ( MY_Controller::MAX_MODES_SURVIVAL + $incInfo["inc_survival"] ) )
			{
				$curDataArray["survival_uptime"] = -1;
			}
			else
			{
				$lastEnergyTime = $curDataArray["survival_uptime"];
				$lastEnergyTime = strtotime((string)$lastEnergyTime);
				$diffInSec = (int)$currentTime - (int)$lastEnergyTime;

				//지나간 시간만큼 하트를 더함 (MY_Controller::HEART_REFILL_SECONDS(1200초))
				$curDataArray["survival_points"] = intval($curDataArray["survival_points"]) + floor($diffInSec / MY_Controller::MODE_SURVIVAL_RECHARGE_INTERVAL);

				if ( intval($curDataArray["survival_points"]) >= ( intval(MY_Controller::MAX_MODES_SURVIVAL) + intval($incInfo["inc_survival"]) ) )
				{
					$curDataArray["survival_points"] = ( intval(MY_Controller::MAX_MODES_SURVIVAL) + intval($incInfo["inc_survival"]) );
					$curDataArray["survival_uptime"] = -1;
				}
				else
				{
					$curDataArray["survival_uptime"] = intval($diffInSec) % intval(MY_Controller::MODE_SURVIVAL_RECHARGE_INTERVAL);
				}
			}
			$this->dbPlay->updateEnergyTime( $pid, $curDataArray );
		}
		else
		{
			$curDataArray = (bool)0;
		}

		return $curDataArray;
    }

    function commonUserResourceProvisioning( $arrayProd, $pid, $sid, $loggingText )
    {
		// 인벤토리 내에 빈공간 체크
		$charCount = $this->dbPlay->IsEmptySpace( $sid, "CHARACTER" )->result_array()[0]["is_empty"];
		$wepnCount = $this->dbPlay->IsEmptySpace( $sid, "WEAPON" )->result_array()[0]["is_empty"];
		$gearCount = $this->dbPlay->IsEmptySpace( $sid, "GEAR" )->result_array()[0]["is_empty"];

		//빈공간이 존재할 경우(추가되는 아이템의 수량 이상으로)
		foreach ( $arrayProd as $key => $arrayProduct )
		{
		    if ( $arrayProduct['article_type'] == 'ASST' )
			{
				$this->calcurateEnergy( $sid );
				$result = (bool)$this->updatePoint( $sid, MY_Controller::COMMON_SAVE_CODE, $arrayProduct['article_value'], $arrayProduct['attach_value'], $loggingText );
				if ( array_key_exists('bonus', $arrayProduct) )
				{
					if ( $arrayProduct['bonus'] > 0 )
					{
						if ( $arrayProduct['article_value'] == 'CASH_POINTS' )
						{
							$result = $result & (bool)$this->updatePoint( $sid, MY_Controller::COMMON_SAVE_CODE, 'EVENT_POINTS', $arrayProduct['bonus'], $loggingText );
						}
						else
						{
							$result = $result & (bool)$this->updatePoint( $sid, MY_Controller::COMMON_SAVE_CODE, $arrayProduct['article_value'], $arrayProduct['bonus'], $loggingText );
						}
					}
				}
				$arrayResult['objectarray'][] = array( 'article_value' => $arrayProduct['article_value'], 'attach_value' => $arrayProduct['attach_value'] );
			}
			// 캐릭터 가챠인 경우 도감, 캐릭터 정보 업데이트
			else
			{
				//for ( $i = 0; $i < $arrayProduct['attach_value'] )
				if ( $arrayProduct['article_type'] == 'CTIK' )
				{
					$result = (bool)1;
					if ( $charCount > 0 )
					{
						for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
						{
							$refid = $this->requestGatcha( $sid, $arrayProduct['article_value'] );
							// 캐릭터 정보 업데이트
							$idx = $this->dbPlay->characterProvision( $sid, $refid );
							$arrayResult['objectarray'][] = array_merge( $this->dbPlay->requestCharacterIns( $pid, $idx )->result_array()[0], array( 'type' => 'CHARACTER', 'value' => $refid ) );
							$result = $result & (bool)$idx;

							// 도감 업데이트
							$this->dbPlay->collectionProvision( $sid, $refid );
						}

						$charCount = $charCount - 1;
					}
					else
					{
						$arrayResult['objectarray'][] = array( 'idx' => 0 );
					}
				}
				// 아이템 가챠인 경우 인벤토리 정보 업데이트
				else if ( $arrayProduct['article_type'] == 'BTIK' || $arrayProduct['article_type'] == 'STIK' || $arrayProduct['article_type'] == 'WTIK' )
				{
					$result = (bool)1;
					if ( $wepnCount > 0 )
					{
						for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
						{
							$refid = $this->requestGatcha( $sid, $arrayProduct['article_value'] );
							// 인벤토리 정보 업데이트
							$idx = $this->dbPlay->inventoryProvision( $sid, $refid );
							$arrayResult['objectarray'][] = array( 'type' => 'ITEM', 'idx' => $idx, 'value' => $refid );
							$result = $result & (bool)$idx;
						}

						$wepnCount = $wepnCount - 1;
					}
					else
					{
						$arrayResult['objectarray'][] = array( 'idx' => 0 );
					}
				}
				else if ( $arrayProduct['article_type'] == 'CHAR' )
				{
					$result = (bool)1;
					if ( $charCount > 0 )
					{
						for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
						{
							// 캐릭터 정보 업데이트
							$idx = $this->dbPlay->characterProvision( $sid, $arrayProduct['article_value'] );
							$arrayResult['objectarray'][] = array_merge($this->dbPlay->requestCharacterIns( $pid, $idx )->result_array()[0], array( 'type' => 'CHARACTER', 'idx' => $idx, 'value' => $arrayProduct['article_value'] ) );
							$result = $result & (bool)$idx;

							// 도감 업데이트
							$this->dbPlay->collectionProvision( $sid, $arrayProduct['article_value'] );
						}

						$charCount = $charCount - 1;
					}
					else
					{
						$arrayResult['objectarray'][] = array( 'idx' => 0 );
					}
				}
				else if ( $arrayProduct['article_type'] == 'PILT' )
				{
					$result = (bool)1;

					for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
					{
						// 인벤토리 정보 업데이트
						$idx = $this->dbPlay->inventoryProvision( $sid, $arrayProduct['article_value'] );
						$arrayResult['objectarray'][] = array( 'type' => 'ITEM', 'idx' => $idx, 'value' => $arrayProduct['article_value'], 'expire' => $this->dbPlay->requestInventoryExpire( $sid, $idx )->result_array()[0]['expire'] );
						$result = $result & (bool)$idx;
					}
				}
				else if ( $arrayProduct['article_type'] == 'BCPC' || $arrayProduct['article_type'] == 'SKIL' || $arrayProduct['article_type'] == 'WEPN' )
				{
					$result = (bool)1;
					if ( $wepnCount > 0 )
					{
						for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
						{
							// 인벤토리 정보 업데이트
							$idx = $this->dbPlay->inventoryProvision( $sid, $arrayProduct['article_value'] );
							$arrayResult['objectarray'][] = array( 'type' => 'ITEM', 'idx' => $idx, 'value' => $arrayProduct['article_value'], 'expire' => $this->dbPlay->requestInventoryExpire( $sid, $idx )->result_array()[0]['expire'] );
							$result = $result & (bool)$idx;
						}

						$wepnCount = $wepnCount - 1;
					}
					else
					{
						$arrayResult['objectarray'][] = array( 'idx' => 0 );
					}
				}
				else if ( $arrayProduct['article_type'] == 'OPRT' )
				{
					$result = (bool)1;
					//오퍼레이터의 경우 중복체크하여 지급
					if ( !( (bool)$this->dbPlay->requestItemExistsWithRef( $sid, $arrayProduct['article_value'], 'count' ) ) )
					{
						for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
						{
							// 인벤토리 정보 업데이트
							$idx = $this->dbPlay->inventoryProvision( $sid, $arrayProduct['article_value'] );
							$arrayResult['objectarray'][] = array( 'type' => 'ITEM', 'idx' => $idx, 'value' => $arrayProduct['article_value'], 'expire' => $this->dbPlay->requestInventoryExpire( $sid, $idx )->result_array()[0]['expire'] );
							$result = $result & (bool)$idx;
						}
					}
					else
					{
						$arrayResult['objectarray'][] = array( 'idx' => 0 );
					}
				}
				else if ( $arrayProduct['article_type'] == 'EXTD' )
				{
					for ( $i = 0; $i < $arrayProduct['attach_value']; $i++ )
					{
						$idx = $this->dbPlay->requestItemExistsWithRef( $sid, $arrayProduct['article_value'], 'value' )->result_array();
						if ( !empty($idx) )
						{
							$idx = $idx[0]['idx'];
							$result = (bool)$this->dbPlay->requestExtendItemExpire( $sid, $idx, $arrayProduct['article_value'] );
							if ( $result )
							{
								$arrayResult['objectarray'][] = array( 'type' => 'ITEM', 'idx' => $idx, 'value' => $arrayProduct['article_value'], 'expire' => $this->dbPlay->requestInventoryExpire( $sid, $idx )->result_array()[0]['expire'] );
							}
							else
							{
								$arrayResult = array( 'idx' => 0 );
							}
						}
						else
						{
							$arrayResult = array( 'idx' => 0 );
						}
					}
				}
				else
				{
					$arrayResult = array( 'idx' => 0 );
				}
			}
			/*
			if ( array_key_exists( 'vip_exp', $arrayProduct ) )
			{
				if ( $arrayProduct['vip_exp'] > 0 )
				{
					$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct['vip_exp'] )->result_array();
					if ( !( empty( $vipInfo ) ) )
					{
						$this->dbPlay->requestUpdateVipInfo( $pid, $vipInfo[0]['vip_level'], $vipInfo[0]['vip_exp'] );
						if ( $vipInfo[0]['prev_level'] != $vipInfo[0]['vip_level'] )
						{
							$vipReward = $this->dbRef->requestVipReward( $pid, $vipInfo[0]['prev_level'], $vipInfo[0]['vip_level'] )->result_array();
							if ( !( empty($vipReward) ) )
							{
								foreach( $vipReward as $row )
								{
									if ( $row['reward_div'] == 'PERM' )
									{
										$this->dbPlay->updatePlayerBasic( $pid, $row['reward_type'], $row['reward_value'] );
									}
									else
									{
										$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], MY_Controller::NORMAL_EXPIRE_TERM );
										$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
									}
								}
								$incInfo = $this->dbPlay->requestPlayerIns( $pid )->result_array()[0];
								// 현재 디비값 불러오기
								// vip 레벨업으로 인한 에너지 추가 처리
								$this->dbPlay->revisionItemTime( $pid, $incInfo['inc_eng'], $incInfo['inc_pvb'], $incInfo['inc_pvp'], $incInfo['inc_survival'] );
							}
						}
						$arrayResult['vipinfo'] = $vipInfo[0];
					}
					else
					{
						$arrayResult['vipinfo'] = null;
					}
				}

				if ( !$result )
				{
					$arrayResult = null;
				}
			}
			*/
		}

		return $arrayResult;
    }

	function requestGatcha( $pid, $id )
	{
		$this->load->model('admin/Model_Admin', "dbAdmin");
		if ( $this->dbAdmin->requestGatchaEventStatus() )
		{
			$arrayGatcha = $this->dbRef->requestGatchaEvent( $pid, $id )->result_array();
			if ( empty($arrayGatcha) )
			{
				$this->dbRef->insertGatchaEvent( $pid, $id );
				$arrayGatcha = $this->dbRef->requestGatchaEvent( $pid, $id )->result_array();
			}
			$this->dbRef->requestGatchaEventUpdateProbability( $pid, $arrayGatcha[0]["id"], $arrayGatcha[0]["refid"] );

			$this->dbRef->requestGetGatchaEvent( $id, $arrayGatcha[0]["grade"], $arrayGatcha[0]["refid"], $pid );
			return $arrayGatcha[0]["refid"];
		}
		else
		{
			$arrayGatcha = $this->dbRef->requestGatcha( $pid, $id )->result_array();
			if ( empty($arrayGatcha) )
			{
				$this->dbRef->insertGatcha( $pid, $id );
				$arrayGatcha = $this->dbRef->requestGatcha( $pid, $id )->result_array();
			}
			$this->dbRef->requestGatchaUpdateProbability( $pid, $arrayGatcha[0]["id"], $arrayGatcha[0]["refid"] );

			$this->dbRef->requestGetGatcha( $id, $arrayGatcha[0]["grade"], $arrayGatcha[0]["refid"], $pid );
			return $arrayGatcha[0]["refid"];
		}
	}

	function generateRandomString( $length )
	{
		$charactersAtFirstBit = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	    	if ($i == 0)
	    	{
		        $randomString .= $charactersAtFirstBit[rand(0, strlen($charactersAtFirstBit) - 1)];
	    	}
	    	else
	    	{
		        $randomString .= $characters[rand(0, strlen($characters) - 1)];
		    }
	    }
	    return $randomString;
	}
}
?>
