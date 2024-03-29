<?php
class Con_ApiProcess extends MY_Controller {

	function __construct(){
		parent::__construct();
	}

	function index()
	{
		$this->load->view( 'error/403_Forbidden' );
	}

	public function requestJoin()
	{
		$id = $this->decoded['id'];
		$password = $this->decoded['password'];
		$macaddr = $this->decoded['macaddr'];

		//회원가입
		if ( $id && $password && $macaddr )
		{
			//아이디 중복 체크
			if ( $this->dbLogin->requestDupId( $id ) > 0 )
			{
				$resultCode = MY_Controller::STATUS_CREATE_DUPLICATE_ID;
				$resultText = MY_Controller::MESSAGE_CREATE_DUPLICATE_ID;
				$arrayResult = null;
			}
			else if ( $this->dbLogin->requestCheckMac( $macaddr ) )
			{
				$resultCode = MY_Controller::STATUS_RESTRICT_MAC;
				$resultText = MY_Controller::MESSAGE_RESTRICT_MAC;
				$arrayResult = null;
			}
			else
			{
				//회원 가입처리
				$arrayResult['pid'] = $this->dbLogin->requestJoin( $id, $password, $macaddr );
				if ( count($arrayResult) < 1 )
				{
					$resultCode = MY_Controller::STATUS_CREATE_ID;
					$resultText = MY_Controller::MESSAGE_CREATE_ID;
					$arrayResult = null;
					$result = (bool)0;
				}
				else
				{
					$result = (bool)$arrayResult['pid'];
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $arrayResult['pid'], $_POST['data'] );
	}
/*
	public function requestGuestLogin()
	{
		$macaddr = $this->decoded['macaddr'];
		$uuid = $this->decoded['uuid'];
		$package = $this->decoded['package'];
		$pushkey = $this->decoded['pushkey'];
		if ( array_key_exists('platform', $this->decoded) )
		{
			$platform = $this->decoded['platform'];
		}
		else
		{
			$platform = 'GCM';
		}

		//회원가입
		if ( $macaddr )
		{
			//맥어드레스 중복 체크
			$arrayResult = $this->dbLogin->requestDupMacaddr( $macaddr )->result_array();
			if ( empty( $arrayResult ) )
			{
				if ( $this->dbLogin->requestCheckMac( $macaddr ) )
				{
					$resultCode = MY_Controller::STATUS_RESTRICT_MAC;
					$resultText = MY_Controller::MESSAGE_RESTRICT_MAC;
					$arrayResult = null;
				}
				else
				{
					//회원 가입처리
					$arrayResult['pid'] = $this->dbLogin->requestGuestJoin( $macaddr, $uuid );
					$checkCount = 1;
					while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
					{
						$cursession = $this->generateRandomString(16); // 16자리 난수 생성 (영대문자+숫자)
						$checkCount = $this->dbLogin->checkDup( $cursession ); // 중복체크
					}

					if ( count($arrayResult) < 1 )
					{
						$resultCode = MY_Controller::STATUS_JOIN_GUEST;
						$resultText = MY_Controller::MESSAGE_JOIN_GUEST;
						$arrayResult = null;
						$result = (bool)0;
					}
					else
					{
						$arrayResult['cursession'] = $cursession;
						$result = (bool)$arrayResult['pid'];

						$this->dbLogin->updateSession( $arrayResult['pid'], $cursession );
						$this->onSysLogWriteDb( $arrayResult['pid'], '로그인 성공' );
						if ( $pushkey != '' && $pushkey != null )
						{
							if ( !(bool)$this->dbLogin->requestCheckDupPushKey( $arrayResult['pid'], $pushkey ) )
							{
								$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
							}
							else
							{
								$this->dbLogin->requestDelDupPushKey( $arrayResult['pid'], $pushkey );
								$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
							}
						}
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
					}
				}
			}
			else
			{
				$arrayResult = $arrayResult[0];
				if ( $arrayResult['limit_type'] == 'R' )
				{
					$this->onSysLogWriteDb( $arrayResult['pid'], '탈퇴 유저 로그인 시도' );
					$resultCode = MY_Controller::STATUS_REJECT_ID;
					$resultText = MY_Controller::MESSAGE_REJECT_ID;
				}
				else
				{
					$checkCount = 1;
					while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
					{
						$cursession = $this->generateRandomString(16); // 16자리 난수 생성 (영대문자+숫자)
						$checkCount = $this->dbLogin->checkDup( $cursession ); // 중복체크
					}

					$this->dbLogin->updateSession( $arrayResult['pid'], $cursession );
					$arrayResult['cursession'] = $cursession;
					$this->onSysLogWriteDb( $arrayResult['pid'], '로그인 성공' );
					if ( $pushkey != '' && $pushkey != null )
					{
						if ( !(bool)$this->dbLogin->requestCheckDupPushKey( $arrayResult['pid'], $pushkey ) )
						{
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
						else
						{
							$this->dbLogin->requestDelDupPushKey( $arrayResult['pid'], $pushkey );
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
					}
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $arrayResult['pid'], $_POST['data'] );
	}
*/
	public function requestAffiliateLogin()
	{
		$macaddr = $this->decoded['macaddr'];
		$uuid = $this->decoded['uuid'];
		$package = $this->decoded['package'];
		$affiliateType = $this->decoded['affiliatetype'];
		$affiliateId = $this->decoded['affiliateid'];
		$affiliateName = $this->decoded['affiliatename'];
		$affiliateEmail = $this->decoded['affiliateemail'];
		$affiliateProfImg = $this->decoded['affiliateprofimg'];
		$pushkey = $this->decoded['pushkey'];
		$country = $this->decoded['country'];
		if ( array_key_exists('platform', $this->decoded) )
		{
			$platform = $this->decoded['platform'];
		}
		else
		{
			$platform = 'GCM';
		}

		//회원가입
		if ( $affiliateType && $affiliateId )
		{
			//맥어드레스 중복 체크
			$arrayResult = $this->dbLogin->requestDupAffiliateId( $affiliateType, $affiliateId )->result_array();
			if ( empty( $arrayResult ) )
			{
				if ( $this->dbLogin->requestCheckMac( $macaddr ) )
				{
					$resultCode = MY_Controller::STATUS_RESTRICT_MAC;
					$resultText = MY_Controller::MESSAGE_RESTRICT_MAC;
					$arrayResult = null;
				}
				else
				{
					//회원 가입처리
					$arrayResult['pid'] = $this->dbLogin->requestAffiliateJoin( $macaddr, $uuid, $affiliateType, $affiliateId, $affiliateName, $affiliateEmail, $affiliateProfImg, $country );
					if ( count($arrayResult) < 1 )
					{
						$resultCode = MY_Controller::STATUS_JOIN_PARTNERSHIP;
						$resultText = MY_Controller::MESSAGE_JOIN_PARTNERSHIP;
						$arrayResult = null;
						$result = (bool)0;
					}
					else
					{
						$result = (bool)$arrayResult['pid'];
					}

					$checkCount = 1;
					while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
					{
						$cursession = $this->generateRandomString(16); // 16자리 난수 생성 (영대문자+숫자)
						$checkCount = $this->dbLogin->checkDup( $cursession ); // 중복체크
					}

					$this->dbLogin->updateSession( $arrayResult['pid'], $cursession );
					$arrayResult['cursession'] = $cursession;
					$this->onSysLogWriteDb( $arrayResult['pid'], '로그인 성공' );
					$this->dbLog->updateCountry( $arrayResult['pid'], $country );
					$this->dbPlay->updateCountry( $arrayResult['pid'], $country );
					if ( $pushkey != '' && $pushkey != null )
					{
						if ( !(bool)$this->dbLogin->requestCheckDupPushKey( $arrayResult['pid'], $pushkey ) )
						{
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
						else
						{
							$this->dbLogin->requestDelDupPushKey( $arrayResult['pid'], $pushkey );
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
					}
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
			else
			{
				$arrayResult = $arrayResult[0];
				if ( $arrayResult['limit_type'] == 'R' )
				{
					$this->onSysLogWriteDb( $arrayResult['pid'], '탈퇴 유저 로그인 시도' );
					$resultCode = MY_Controller::STATUS_REJECT_ID;
					$resultText = MY_Controller::MESSAGE_REJECT_ID;
				}
				else
				{
					$checkCount = 1;
					while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
					{
						$cursession = $this->generateRandomString(16); // 16자리 난수 생성 (영대문자+숫자)
						$checkCount = $this->dbLogin->checkDup( $cursession ); // 중복체크
					}

					$this->dbLogin->updateSession( $arrayResult['pid'], $cursession );
					$arrayResult['cursession'] = $cursession;
					$this->dbLogin->updateAffiliateNameAccount( $arrayResult['pid'], $affiliateName, $affiliateEmail, $affiliateProfImg );

					$this->onSysLogWriteDb( $arrayResult['pid'], '로그인 성공' );
					if ( $pushkey != '' && $pushkey != null )
					{
						if ( !(bool)$this->dbLogin->requestCheckDupPushKey( $arrayResult['pid'], $pushkey ) )
						{
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
						else
						{
							$this->dbLogin->requestDelDupPushKey( $arrayResult['pid'], $pushkey );
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
					}
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $arrayResult['pid'], $_POST['data'] );
	}

	public function requestUpdateName()
	{
		$pid = $this->decoded['pid'];
		$name = $this->decoded['name'];

		//회원가입
		if( $pid && $name )
		{
			if ( $this->dbPlay->requestNameDupCount( $pid, $name ) > 0 )
			{
				$resultCode = MY_Controller::STATUS_DUPLICATE_UPDATE_ID;
				$resultText = MY_Controller::MESSAGE_DUPLICATE_UPDATE_ID;
				$arrayResult = null;
			}
			else
			{
				$this->dbPlay->requestUpdateNamePlay( $pid, $name );
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $arrayResult['pid'], $_POST['data'] );
	}

	public function requestUpdateIncresement()
	{
		$pid = $this->decoded['pid'];
		$slottype = $this->decoded['slottype'];

		if( $pid && $slottype )
		{
			$this->dbPlay->onBeginTransaction();
			// 지불 처리
			if ( $slottype == 'cha' )
			{
				$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, MY_Controller::COMMON_SLOT_PAYMENT_TYPE, MY_Controller::COMMON_CHASLOT_PAYMENT_VALUE, '캐릭터 슬롯 확장' );
			}
			else if ( $slottype == 'wea' )
			{
				$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, MY_Controller::COMMON_SLOT_PAYMENT_TYPE, MY_Controller::COMMON_INVSLOT_PAYMENT_VALUE, '아이템 슬롯 확장' );
			}
			else if ( $slottype == 'ger' )
			{
				$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, MY_Controller::COMMON_SLOT_PAYMENT_TYPE, MY_Controller::COMMON_GERSLOT_PAYMENT_VALUE, '기어 슬롯 확장' );
			}
			if ( $result )
			{
				$result = $result & (bool)$this->dbPlay->requestUpdateIncresement( $pid, $slottype, 5 );
				$this->dbPlay->onEndTransaction( $result );
				if ( $result )
				{
					$tmpArray = $this->dbPlay->requestPlayerIns($pid)->result_array()[0];
					if (empty($tmpArray))
					{
						$resultCode = MY_Controller::STATUS_EXPAND_NO_PLAYER;
						$resultText = MY_Controller::MESSAGE_EXPAND_NO_PLAYER;
						$arrayResult = null;
					}
					else
					{
						$arrayResult['max_cha'] = MY_Controller::MAX_CHAR_CAPACITY;
						$arrayResult['inc_cha'] = $tmpArray['inc_cha'];
						$arrayResult['max_wea'] = MY_Controller::MAX_WEPN_CAPACITY;
						$arrayResult['inc_wea'] = $tmpArray['inc_wea'];
						$arrayResult['max_ger'] = MY_Controller::MAX_GEAR_CAPACITY;
						$arrayResult['inc_ger'] = $tmpArray['inc_ger'];
						$arrayResult['max_exp'] = MY_Controller::MAX_EXPLORATION;
						$arrayResult['inc_exp'] = $tmpArray['inc_exp'];
						$arrayResult['max_eng'] = MY_Controller::MAX_ENERGY_POINTS;
						$arrayResult['inc_eng'] = $tmpArray['inc_eng'];
						$arrayResult['max_fri'] = MY_Controller::MAX_FRIENDS;
						$arrayResult['inc_fri'] = $tmpArray['inc_fri'];
						$arrayResult['max_pvp'] = MY_Controller::MAX_MODES_PVP;
						$arrayResult['inc_pvp'] = $tmpArray['inc_pvp'];
						$arrayResult['max_pvb'] = MY_Controller::MAX_MODES_PVB;
						$arrayResult['inc_pvb'] = $tmpArray['inc_pvb'];
						$arrayResult['max_survival'] = MY_Controller::MAX_MODES_SURVIVAL;
						$arrayResult['inc_survival'] = $tmpArray['inc_survival'];
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_EXPAND_SLOT;
					$resultText = MY_Controller::MESSAGE_EXPAND_SLOT;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_LACK_CASH;
				$resultText = MY_Controller::MESSAGE_LACK_CASH;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}
/*
	public function requestLogin()
	{
		$id = $this->decoded['id'];
		$password = $this->decoded['password'];
		$package = $this->decoded['package'];
		$pushkey = $this->decoded['pushkey'];
		if ( array_key_exists('platform', $this->decoded) )
		{
			$platform = $this->decoded['platform'];
		}
		else
		{
			$platform = 'GCM';
		}

		if( $id && $password )
		{
			//로그인 처리
			$arrayResult = $this->dbLogin->requestLogin( $id, $password )->result_array();
			if ( count($arrayResult) > 1 )
			{
				$resultCode = MY_Controller::STATUS_DUPLICATE_LOGIN_ID;
				$resultText = MY_Controller::MESSAGE_DUPLICATE_LOGIN_ID;
				$arrayResult = null;
			}
			else if ( count($arrayResult) < 1 )
			{
				$resultCode = MY_Controller::STATUS_INFO_ID;
				$resultText = MY_Controller::MESSAGE_INFO_ID;
				$arrayResult = null;
			}
			else
			{
				$arrayResult = $arrayResult[0];
				if ( $arrayResult['limit_type'] == 'R' )
				{
					$this->onSysLogWriteDb( $arrayResult['pid'], '탈퇴 유저 로그인 시도' );
					$resultCode = MY_Controller::STATUS_REJECT_ID;
					$resultText = MY_Controller::MESSAGE_REJECT_ID;
				}
				else
				{
					$checkCount = 1;
					while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
					{
						$cursession = $this->generateRandomString(16); // 16자리 난수 생성 (영대문자+숫자)
						$checkCount = $this->dbLogin->checkDup( $cursession ); // 중복체크
					}

					$this->dbLogin->updateSession( $arrayResult['pid'], $cursession );
					$arrayResult['cursession'] = $cursession;
					$this->dbPlay->onBeginTransaction();
					$result = (bool)$this->dbPlay->updateLoginTimeForMe( $arrayResult['pid'] );
					$this->dbPlay->updateLoginTimeForFriend( $arrayResult['pid'] );
					$this->dbPlay->onEndTransaction( $result );
					$this->dbLogin->onEndTransaction( $result );

					$this->onSysLogWriteDb( $arrayResult['pid'], '로그인 성공' );
					if ( $pushkey != '' && $pushkey != null )
					{
						if ( !(bool)$this->dbLogin->requestCheckDupPushKey( $arrayResult['pid'], $pushkey ) )
						{
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
						else
						{
							$this->dbLogin->requestDelDupPushKey( $arrayResult['pid'], $pushkey );
							$this->dbLogin->requestRegPushKey( $arrayResult['pid'], $platform, $pushkey );
						}
					}
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $arrayResult['pid'], $_POST['data'] );
	}
*/
	public function requestMailList()
	{
		$pid = $this->decoded['pid'];
//		$category = $this->decoded['category'];

		if ( $pid )
		{
			//접속 이벤트 참여 (메일목록에 추가)
			$this->load->model('admin/Model_Admin', 'dbAdmin');
			$arrayEvent = $this->dbAdmin->requestValidAccEventList()->result_array();
			foreach( $arrayEvent as $row )
			{
				if ( $this->dbAdmin->requestAccessEventApply( $pid, $row['idx'] ) == 1 )
				{
					$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::ACCESS_EVENT_REWARD_TITLE, $row['evt_target'], $row['evt_value'], MY_Controller::NORMAL_EXPIRE_TERM );
				}
			}

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult['list'] = $this->dbMail->mailList( $pid/*, $category */)->result_array();

			if (empty($arrayResult))
			{
				$resultCode = MY_Controller::STATUS_REQUEST_MAIL;
				$resultText = MY_Controller::MESSAGE_REQUEST_MAIL;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMailReceipt()
	{
		$pid = $this->decoded['pid'];
		$idx = $this->decoded['idx'];

		if( $pid && $idx )
		{
			$this->dbMail->onBeginTransaction();
			$this->dbPlay->onBeginTransaction();
			$arrayProduct = $this->dbMail->getMailInfo( $pid, $idx )->result_array();
			if ( count($arrayProduct) > 0 )
			{
				$result = (bool)$this->dbMail->mailReceipt( $pid, $idx );
				//공통처리
				$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '메일 수령('.$idx.')' );
				$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
				unset( $arrayResult['rewardobject'] );
				if ( $arrayResult )
				{
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
				else
				{
					$resultCode = MY_Controller::STATUS_RECEIVE_ITEM;
					$resultText = MY_Controller::MESSAGE_RECEIVE_ITEM;
					$result = (bool)0;
				}
				$arrayResult['idx'] = (string)$idx;
			}
			else
			{
				$resultCode = MY_Controller::STATUS_ADD_MAIL;
				$resultText = MY_Controller::MESSAGE_ADD_MAIL;
				$arrayResult = null;
				$result = (bool)0;
			}
			$this->dbPlay->onEndTransaction( $result );
			$this->dbMail->onEndTransaction( $result );
			$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMailReceiptAll()
	{
		$pid = $this->decoded['pid'];
		$attach_type = $this->decoded['attach_type'];
		$idx = $this->decoded['idx'];

		if( $pid && $attach_type )
		{
			$this->dbMail->onBeginTransaction();
			$this->dbPlay->onBeginTransaction();
			$sumValue = $this->dbMail->mailValueSummary( $pid, $attach_type, $idx )->result_array();
			if ( empty($sumValue) )
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
			else
			{
				$this->calcurateEnergy( $pid );
				$result = (bool)$this->dbMail->mailReceiptAll( $pid, $attach_type, $idx );
				if ( $result )
				{
					$sumValue = $sumValue[0]['attach_value'];
					$logProc = join(',', $idx);
					$result2 = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, $attach_type, $sumValue, '메일 수령('.$logProc.')' );
					$result = $result & $result2;

					if ( $result )
					{
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$arrayResult['addedType'] = $attach_type;
						$arrayResult['addedPoints'] = $sumValue;

						$arrayResult['procIdx'] = $idx;
					}
					else
					{
						$resultCode = MY_Controller::STATUS_NO_DATA;
						$resultText = MY_Controller::MESSAGE_NO_DATA;
						$arrayResult = null;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_NO_DATA;
					$resultText = MY_Controller::MESSAGE_NO_DATA;
					$arrayResult = null;
				}
				$this->dbPlay->onEndTransaction( $result );
				$this->dbMail->onEndTransaction( $result );
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMailCount()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult['mailcount'] = (string)$this->dbMail->mailCount( $pid );
			$arrayResult['friendcount'] = (string)$this->dbPlay->friendCount( $pid );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUpdateShowProfile()
	{
		$pid = $this->decoded['pid'];
		$show_prof = $this->decoded['show_prof'];
		$show_name = $this->decoded['show_name'];

		if ( $pid )
		{
			$this->dbPlay->requestUpdateShowProfile( $pid, $show_prof, $show_name );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestPlayer()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$tmpResult = 1;
			if ( !($this->dbPlay->requestFirstLogin( $pid )) )
			{
				$this->dbPlay->requestJoinStep2( $pid );
				$this->dbPlay->insertItem( $pid );

				$arraySupply = $this->dbRef->requestSupplies( $pid )->result_array();
				$result = (bool)1;
				$charCount = 0;
				$itemCount = 0;
				$equipCount = 0;

				foreach( $arraySupply as $row )
				{
					if ( $row['article_type'] == 'ASST' )
					{
						$result = $result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, $row['type'], $row['value'], '최초 회원가입 지급' );
					}
					else if ( $row['article_type'] == 'CHAR' )
					{
						for ( $i = 0; $i < $row['value']; $i++ )
						{
							$arrayCharacter[$charCount] = $this->dbPlay->characterProvision( $pid, $row['type'] );
							$charCount = $charCount + 1;
						}
						$this->dbPlay->collectionProvision( $pid, $row['type'] );
					}
					else if ( $row['article_type'] == 'WEPN' || $row['article_type'] == 'BCPC' || $row['article_type'] == 'SKIL' )
					{
						for ( $i = 0; $i < $row['value']; $i++ )
						{
							$arrayInventory[$itemCount] = $this->dbPlay->inventoryProvision( $pid, $row['type'] );
							$itemCount = $itemCount + 1;
						}
					}
					else if ( $row['article_type'] == 'OPRT' )
					{
						for ( $i = 0; $i < $row['value']; $i++ )
						{
							$arrayEquipment[$equipCount] = $this->dbPlay->inventoryProvision( $pid, $row['type'] );
							$equipCount = $equipCount + 1;
						}
					}
				}
				$result = $result & $this->dbPlay->newPlayerTeam( $pid, $arrayCharacter[0], $arrayCharacter[1], null );
				$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[0], 'weapon', $arrayInventory[0] );
				$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[1], 'weapon', $arrayInventory[1] );
				$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[0], 'backpack', $arrayInventory[3] );
				$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[1], 'backpack', $arrayInventory[4] );
				$tmpResult = 1;
			}

			if ( $tmpResult )
			{
				$arrayResult['pid'] = (string)$pid;
				$accountResult = $this->dbLogin->requestAffiliateAccount( $pid )->result_array();
				$helpArray = $this->dbPlay->requestHelpCount( $pid )->result_array();
				$arrayResult['helpcount'] = $helpArray[0]['helpcount'];
				$this->dbPlay->updateLoginTimeForMe( $pid, $accountResult[0]['affiliate_name'], $accountResult[0]['prof_img'] );
				if ( $helpArray[0]['show_name'] )
				{
					$affiliateName = $accountResult[0]['affiliate_name'];
				}
				else
				{
					$affiliateName = '';
				}
				if ( $helpArray[0]['show_prof'] )
				{
					$this->dbPlay->updateAffiliateFriendInfo( $pid, $affiliateName, $accountResult[0]['prof_img'] );
				}
				else
				{
					$this->dbPlay->updateAffiliateFriendInfo( $pid, $affiliateName, '' );
				}
				$this->dbPlay->updateAffiliateNamePlay( $pid, $affiliateName, $accountResult[0]['prof_img'] );

				//valid user check
				$tmpArray = $this->dbPlay->requestPlayerIns($pid)->result_array();
				if (empty($tmpArray))
				{
					$resultCode = MY_Controller::STATUS_NO_PLAYER;
					$resultText = MY_Controller::MESSAGE_NO_PLAYER;
					$arrayResult = null;
				}
				else
				{
					$tmpArray = $tmpArray[0];
					//접속 이벤트 참여
					$this->load->model('admin/Model_Admin', 'dbAdmin');
					$arrayEvent = $this->dbAdmin->requestValidAccEventList()->result_array();
					foreach( $arrayEvent as $row )
					{
						if ( $this->dbAdmin->requestAccessEventApply( $pid, $row['idx'] ) == 1 )
						{
							$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::ACCESS_EVENT_REWARD_TITLE, $row['evt_target'], $row['evt_value'], MY_Controller::NORMAL_EXPIRE_TERM );
						}
					}

					$arrayResult['name'] = $tmpArray['name'];
					$arrayResult['show_prof'] = $tmpArray['show_prof'];
					$arrayResult['show_name'] = $tmpArray['show_name'];
					$arrayResult['prof_img'] = $tmpArray['prof_img'];
					$arrayResult['vip_level'] = $tmpArray['vip_level'];
					$arrayResult['vip_exp'] = $tmpArray['vip_exp'];
					$arrayResult['player_level'] = $tmpArray['player_level'];
					$arrayResult['player_exp'] = $tmpArray['player_exp'];
					$arrayResult['max_cha'] = MY_Controller::MAX_CHAR_CAPACITY;
					$arrayResult['inc_cha'] = $tmpArray['inc_cha'];
					$arrayResult['max_wea'] = MY_Controller::MAX_WEPN_CAPACITY;
					$arrayResult['inc_wea'] = $tmpArray['inc_wea'];
					$arrayResult['max_ger'] = MY_Controller::MAX_GEAR_CAPACITY;
					$arrayResult['inc_ger'] = $tmpArray['inc_ger'];
					$arrayResult['max_exp'] = MY_Controller::MAX_EXPLORATION;
					$arrayResult['inc_exp'] = $tmpArray['inc_exp'];
					$arrayResult['max_eng'] = MY_Controller::MAX_ENERGY_POINTS;
					$arrayResult['inc_eng'] = $tmpArray['inc_eng'];
					$arrayResult['max_fri'] = MY_Controller::MAX_FRIENDS;
					$arrayResult['inc_fri'] = $tmpArray['inc_fri'];
					$arrayResult['max_pvp'] = MY_Controller::MAX_MODES_PVP;
					$arrayResult['inc_pvp'] = $tmpArray['inc_pvp'];
					$arrayResult['max_pvb'] = MY_Controller::MAX_MODES_PVB;
					$arrayResult['inc_pvb'] = $tmpArray['inc_pvb'];
					$arrayResult['max_survival'] = MY_Controller::MAX_MODES_SURVIVAL;
					$arrayResult['inc_survival'] = $tmpArray['inc_survival'];

					$arrayResult['op'] = $tmpArray['operator'];

					$arrayResult['team'] = $this->dbPlay->requestTeam( $pid )->result_array();
					$arrayResult['inventory'] = $this->dbPlay->requestInventory( $pid )->result_array();
					$arrayResult['character'] = $this->dbPlay->requestCharacters( $pid )->result_array();

					$this->calcurateEnergy( $pid );
					$tmpArray = $this->dbPlay->requestItem( $pid )->result_array()[0];
					$arrayResult['remain_item'] = $tmpArray;

					$playerReward = $this->dbRef->requestDailyPlayerReward( $pid, $arrayResult['player_level'] )->result_array();
					/* vip 시스템 임시제거
					$vipReward = $this->dbRef->requestDailyVipReward( $pid, $arrayResult['vip_level'] )->result_array();

					if ( !( empty($vipReward) ) )
					{
						foreach( $vipReward as $row )
						{
							if ( $row['reward_div'] == 'DAILY' )
							{
								$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
								$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
							}
						}
						$arrayResult['vip_reward'] = 1;
					}
					else
					{
						$arrayResult['vip_reward'] = 0;
					}
					*/
					// 매일매일 패키지 지급 처리
					$arrayResult['packinfo']['everydaypack'] = $this->dbPlay->requestEverydayPackageList( $pid )->result_array();
					if ( !empty( $arrayResult['packinfo']['everydaypack'] ) )
					{
						foreach( $arrayResult['packinfo']['everydaypack'] as $row )
						{
							if ( $row['is_reward'] < 1 )
							{
								$mail_id = $this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
								$this->dbPlay->requestLoggingEveryDayPackPayment( $pid, $mail_id, $row['idx'], $row['paymentSeq'], $row['product_id'], $row['expire_date'], $row['reward_type'], $row['reward_value'] );
							}
						}
					}

					// 첫구매 체크 ( 상품 카테고리가 CASH 인 경우만 체크, 매일매일 패키지(카테고리 : SUBSCRIBE)와 개척자 패키지(카테고리 : LIMITED) 제외 )
					/*
					if ( MY_Controller::VALID_FIRST_BUY_EVENT )
					{
						$arrayResult['packinfo']['is_first'] = $this->dbPlay->requestFirstBuyCheck( $pid )->result_array()[0]['is_first'];
					}
					else
					{
						$arrayResult['packinfo']['is_first'] = 0;
					}
					*/
					// 월간 패키지 첫구매 체크
					$arrayResult['packinfo']['is_first'] = intval(!(bool)$this->dbPlay->requestPackageBuyHistory( $pid, '', MY_Controller::PRODUCTTYPE_PACKAGE_MONTHLY ));
					$arrayResult['packinfo']['daily'] = $this->dbPlay->requestDailyPackageList( $pid )->result_array();

					// 개척자 패키지
					$arrayResult['packinfo']['limited'] = $this->dbPlay->requestLimitedPackageList( $pid )->result_array();
					$arrayResult['packinfo']['free'] = $this->dbPlay->requestFreeGatcha( $pid )->result_array();

					$arrayRstime = $this->dbPlay->requestBeforeRequestData( $pid )->result_array();
					if ( empty($arrayRstime) )
					{
						$rstime = '1900-01-01 00:00:00';
					}
					else
					{
						$rstime = $arrayRstime[0]['rstime'];
					}
					$arrayResult['rstime'] = $rstime;

					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_SERVER_BUSY;
				$resultText = MY_Controller::MESSAGE_SERVER_BUSY;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestDeployCharacters()
	{
		$pid = $this->decoded['pid'];
		$teamInfo = $this->decoded['teamInfo'];
		//배열 빈값 제거
		$teamInfo = array_filter($teamInfo, 'is_numeric');

		if ( $pid )
		{
			$checkTeamInfo = array_filter($teamInfo);
			// 캐릭터 idx 검사 (본인의 캐릭터가 맞는지 확인)
			if ( $this->dbPlay->requestCharactersExists( $pid, array_unique($checkTeamInfo, SORT_LOCALE_STRING) ) != count(array_unique($checkTeamInfo, SORT_LOCALE_STRING)) )
			{
				$resultCode = MY_Controller::STATUS_DEPLOY_CHARACTER_INFO;
				$resultText = MY_Controller::MESSAGE_DEPLOY_CHARACTER_INFO;
				$arrayResult = null;
			}
			else if ( $teamInfo['00'] == 0 || $teamInfo['10'] == 0 || $teamInfo['20'] == 0 )
			{
				$resultCode = MY_Controller::STATUS_NO_CHARACTER_FOR_SLOT;
				$resultText = MY_Controller::MESSAGE_NO_CHARACTER_FOR_SLOT;
				$arrayResult = null;
			}
			else
			{
				if ( ( $teamInfo['00'] == $teamInfo['01'] && $teamInfo['00'] > 0 && $teamInfo['01'] > 0 )
					|| ( $teamInfo['00'] == $teamInfo['02'] && $teamInfo['00'] > 0 && $teamInfo['02'] > 0 )
					|| ( $teamInfo['01'] == $teamInfo['02'] && $teamInfo['01'] > 0 && $teamInfo['02'] > 0 )
					|| ( $teamInfo['10'] == $teamInfo['11'] && $teamInfo['10'] > 0 && $teamInfo['11'] > 0 )
					|| ( $teamInfo['10'] == $teamInfo['12'] && $teamInfo['10'] > 0 && $teamInfo['12'] > 0 )
					|| ( $teamInfo['11'] == $teamInfo['12'] && $teamInfo['11'] > 0 && $teamInfo['12'] > 0 )
					|| ( $teamInfo['20'] == $teamInfo['21'] && $teamInfo['20'] > 0 && $teamInfo['21'] > 0 )
					|| ( $teamInfo['20'] == $teamInfo['22'] && $teamInfo['20'] > 0 && $teamInfo['22'] > 0 )
					|| ( $teamInfo['21'] == $teamInfo['22'] && $teamInfo['21'] > 0 && $teamInfo['22'] > 0 ) )
				{
					$resultCode = MY_Controller::STATUS_DEPLOY_DUPLICATE_CHARACTER;
					$resultText = MY_Controller::MESSAGE_DEPLOY_DUPLICATE_CHARACTER;
					$arrayResult = null;
				}
				else
				{
					// $teamInfo는 배열크기 및 인덱스 고정
					// Array ( [00] => ??? [01] => ??? [02] => ??? [10] => ??? [11] => ??? [12] => ??? [20] => ??? [21] => ??? [22] => ??? )
					$this->dbPlay->requestDeployCharacters( $pid, $teamInfo );
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = null;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUpdateTactic()
	{
		$pid = $this->decoded['pid'];
		$tactinfo = $this->decoded['tactinfo'];

		if ( $pid )
		{
			// $tactinfo는 배열크기 및 인덱스 고정
			// Array ( [00] => ??? [01] => ??? [02] => ??? [10] => ??? [11] => ??? [12] => ??? [20] => ??? [21] => ??? [22] => ??? )
			$this->dbPlay->requestUpdateTact( $pid, $tactinfo );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestProductList()
	{
		$pid = $this->decoded['pid'];
		$appId = $this->decoded['appid'];
		$storeType = strtolower( $this->decoded['storetype'] );
		$storeVersion = $this->decoded['store_version'];
		if ( array_key_exists( 'country_code', $this->decoded ) )
		{
			$country_code = $this->decoded['country_code'];
		}
		else
		{
			$country_code = '';
		}

		if ( $appId && $storeType && $storeVersion )
		{
			if ( $storeType != 'editor' && $storeType != 'ios' && $storeType != 'android' && $storeType != 'naver' && $storeType != 'tstore' && $storeType != 'google' )
			{
				$storeType = 'editor';
			}
			if ( $appId )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult['lst'] = $this->dbRef->requestProductList( $pid, $storeType, $storeVersion, $country_code )->result_array();
			}
			else
			{
				$resultCode = MY_Controller::STATUS_APPID;
				$resultText = MY_Controller::MESSAGE_APPID;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestEquipToChar()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['cid'];
		$slotseq = $this->decoded['slotSeq']; //weapon, backpack, skill_0, skill_1, skill_2, gear_0, gear_1, gear_2, gear_3, gear_4, gear_5
		$iid = $this->decoded['iid'];

		if ( $pid && $cid && $slotseq && $iid )
		{
			$deliid = $this->dbPlay->requestEquipAvailableCheck( $pid, $cid, $slotseq, $iid )->result_array();
			// 현재 아이템을 사용할 수 없는 경우
			if ( count($deliid) == 0 )
			{
				$result = (bool)0;
				$resultCode = MY_Controller::STATUS_EQUIP_ERROR_ITEM;
				$resultText = MY_Controller::MESSAGE_EQUIP_ERROR_ITEM;
				$arrayResult = null;
			}
			else
			{
				// 기어 타입번호 체크
				if ( strpos($slotseq, 'gear') !== false )
				{
					$gseq = (int)(substr($slotseq, strlen($slotseq) - 1, 1) / 2);
				}
				else
				{
					$gseq = 0;
				}

				$cVocation = $this->dbPlay->requestCharacterClassCheck( $pid, $cid, $gseq )->result_array();
				$iVocation = $this->dbPlay->requestItemClassCheck( $pid, $iid )->result_array();

				if ( empty( $cVocation ) || empty( $iVocation ) )
				{
					$result = (bool)0;
					$resultCode = MY_Controller::STATUS_EQUIP_CLASS_ERROR_ITEM;
					$resultText = MY_Controller::MESSAGE_EQUIP_CLASS_ERROR_ITEM;
					$arrayResult = null;
				}
				else
				{
					$iVocation[0]['vocation'] = ( $iVocation[0]['vocation'] == '' ? 'DEFENDER|INFIGHTER|SHOOTER' : $iVocation[0]['vocation'] );
					if ( strpos( $iVocation[0]['vocation'], '|' ) )
					{
						$iVoc = explode( '|', $iVocation[0]['vocation'] );
					}
					else
					{
						$iVoc = array( $iVocation[0]['vocation'] );
					}

					if ( in_array( $cVocation[0]['vocation'], $iVoc ) )
					{
						$result = (bool)1;
						// 기어 타입 체크
						if ( strpos($slotseq, 'gear') !== false )
						{
							if (strtolower($cVocation[0]['gtype_'.$gseq]) != strtolower($iVocation[0]['type']))
							{
								$result = (bool)0;
								$resultCode = MY_Controller::STATUS_EQUIP_TYPE_ERROR_ITEM;
								$resultText = MY_Controller::MESSAGE_EQUIP_TYPE_ERROR_ITEM;
								$arrayResult = null;
							}

							if ( $iVocation[0]['parts'] != json_decode(MY_Controller::ARRAY_GEAR_PARTS_SLOTSEQ, true)[(int)(substr($slotseq, strlen($slotseq) - 1, 1))] )
							{
								$result = (bool)0;
								$resultCode = MY_Controller::STATUS_EQUIP_SLOT_ERROR_ITEM;
								$resultText = MY_Controller::MESSAGE_EQUIP_SLOT_ERROR_ITEM;
								$arrayResult = null;
							}
						}

						if ( $result )
						{
							$this->dbPlay->onBeginTransaction();
							// 착용중인 아이템이 있는경우 삭제
							if ( $deliid[0][$slotseq] && $deliid[0][$slotseq] != $iid )
							{
								$result = (bool)$this->dbPlay->deleteInventory( $pid, $deliid[0][$slotseq] );
							}

							if ( $result )
							{
								$result = (bool)$this->dbPlay->requestEquipToChar( $pid, $cid, $slotseq, $iid );
								if ( $result )
								{
									$resultCode = MY_Controller::STATUS_API_OK;
									$resultText = MY_Controller::MESSAGE_API_OK;
									$arrayResult = null;
								}
								else
								{
									$resultCode = MY_Controller::STATUS_EQUIP_ITEM;
									$resultText = MY_Controller::MESSAGE_EQUIP_ITEM;
									$arrayResult = null;
								}
							}
							$this->dbPlay->onEndTransaction( $result );
						}
					}
					else
					{
						$result = (bool)0;
						$resultCode = MY_Controller::STATUS_EQUIP_CLASS_ERROR_ITEM;
						$resultText = MY_Controller::MESSAGE_EQUIP_CLASS_ERROR_ITEM;
						$arrayResult = null;
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUnequipToChar()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['cid'];
		$slotseq = $this->decoded['slotSeq'];
		$iid = $this->decoded['iid'];

		if ( $pid && $cid && $slotseq && $iid )
		{
			$this->dbPlay->onBeginTransaction();
			$arrResult = $this->dbPlay->requestUnequipAvailableCheck( $pid, $cid, $slotseq, $iid )->result_array();
			// 현재 아이템을 사용할 수 없는 경우
			if ( count($arrResult) == 0 )
			{
				$result = (bool)0;
				$resultCode = MY_Controller::STATUS_EQUIP_ERROR_ITEM;
				$resultText = MY_Controller::MESSAGE_EQUIP_ERROR_ITEM;
				$arrayResult = null;
			}
			else
			{
				$result = (bool)$this->dbPlay->requestUnequipToChar( $pid, $cid, $slotseq, $iid );
				if ( $result )
				{
					$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, MY_Controller::UNEQUIP_ASSET_TYPE, json_decode(MY_Controller::UNEQUIP_ASSET_VALUE, true)[$arrResult[0]['grade']], '장비 탈착('.$iid.')' );
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				}
				else
				{
					$resultCode = MY_Controller::STATUS_EQUIP_ITEM;
					$resultText = MY_Controller::MESSAGE_EQUIP_ITEM;
					$arrayResult = null;
				}
			}
			$this->dbPlay->onEndTransaction( $result );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestAttendEvent()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			$result = $this->dbPlay->requestAttendEvent( $pid );

			if ( $result )
			{
				$attendCount = $this->dbPlay->requestLastAttend( $pid )->result_array()[0]['attend_count'];
				$rewardInfo = $this->dbRef->getAttendReward( $pid, $attendCount )->result_array()[0];
				$result2 = (bool)$this->dbMail->sendMail(
					$pid, MY_Controller::SENDER_GM, MY_Controller::DAILY_REWARD_TITLE, $rewardInfo['type'], $rewardInfo['value'], MY_Controller::NORMAL_EXPIRE_TERM
				);
				$result = $result & $result2;
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult['day'] = $rewardInfo['day'];
				$arrayResult['maxday'] = MY_Controller::MAX_ATTEND;

				$this->onSysLogWriteDb( $pid, '출석이벤트\n보상아이템 : '.$rewardInfo[MY_Controller::COMMON_LANGUAGE_COLUMN].' '.$rewardInfo['value'].'개 우편발송' );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
			$this->dbPlay->onEndTransaction( $result );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExtraAttendEvent()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			if ( date('Ymd') > '20150215' && date('Ymd') > '20150223' )
			{
				$result = (bool)0;
			}
			else
			{
				$result = $this->dbPlay->requestExtraAttendEvent( $pid );
			}

			if ( $result )
			{
				$attendCount = $this->dbPlay->requestLastExtraAttend( $pid )->result_array()[0]['attend_count'];
				if ( $attendCount > MY_Controller::MAX_EXTRAATTEND )
				{
					$result = false;
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult['day'] = '-1';
					$arrayResult['maxday'] = MY_Controller::MAX_EXTRAATTEND;
				}
				else
				{
					$rewardInfo = $this->dbRef->getExtraAttendReward( $pid, $attendCount )->result_array()[0];
					$result2 = (bool)$this->dbMail->sendMail(
						$pid, MY_Controller::SENDER_GM, MY_Controller::EVENTREWARD_SEND_TITLE,
						$rewardInfo['type'], $rewardInfo['value'], MY_Controller::NORMAL_EXPIRE_TERM
					);
					$result = $result & $result2;
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult['day'] = $rewardInfo['day'];
					$arrayResult['maxday'] = MY_Controller::MAX_EXTRAATTEND;

					$this->onSysLogWriteDb( $pid, '연속출석이벤트\n보상아이템 : '.$rewardInfo[MY_Controller::COMMON_LANGUAGE_COLUMN].' '.$rewardInfo['value'].'개 우편발송' );
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
			$this->dbPlay->onEndTransaction( $result );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExtraAttendList()
	{
		$pid = $this->decoded['pid'];

		if ( $pid && date('Ymd') > '20150215' && date('Ymd') < '20150223' )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult['list'] = $this->dbRef->requestExtraAttendList( $pid )->result_array();
			$arrayResult['day'] = $this->dbPlay->requestLastExtraAttend( $pid )->result_array()[0]['attend_count'];
			$arrayResult['start'] = '2015-02-16';
			$arrayResult['end'] = '2015-02-22';
			$arrayResult['image'] = 'koc_daily_event_new_year';
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
			$arrayResult = null;
		}
		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestBuyProduct()
	{
		$pid = $this->decoded['pid'];
		$product = $this->decoded['product'];
		if ( array_key_exists( 'country_code', $this->decoded ) )
		{
			$country_code = $this->decoded['country_code'];
		}
		else
		{
			$country_code = '';
		}
		$storeType = strtolower( $this->decoded['storetype'] );

		if ( $pid && $storeType && $product )
		{
			if ( $storeType != 'editor' && $storeType != 'ios' && $storeType != 'android' && $storeType != 'naver' && $storeType != 'tstore' )
			{
				$storeType = 'editor';
			}
			$arrayProduct = $this->dbRef->productVerify( $pid, $storeType, $product, $country_code )->result_array();
			if ( count($arrayProduct) < 1 )
			{
				$resultCode = MY_Controller::STATUS_BUYITEM_ERROR_ITEM;
				$resultText = MY_Controller::MESSAGE_BUYITEM_ERROR_ITEM;
				$arrayResult = null;
			}
			else
			{
				$this->load->model('admin/Model_Admin', 'dbAdmin');
				$arrDisInfo = $this->dbAdmin->requestValidDisEventList($arrayProduct[0]['category'], $product)->result_array();
				$this->dbPlay->onBeginTransaction();

				if ( !empty( $arrDisInfo ) )
				{
					if ( $arrDisInfo[0]['evt_paytype'] == 'DIS' )
					{
						$arrayProduct[0]['payment'] = floor( $arrPayment[0]['payment'] * (100 - $arrDisInfo[0]['evt_value']) / 100 );
					}
					else
					{
						$arrayProduct[0]['payment_type'] = $arrDisInfo[0]['evt_paytype'];
						$arrayProduct[0]['payment'] = $arrDisInfo[0]['evt_value'];
					}
				}

				if ( $arrayProduct[0]['termmin'] != null )
				{
					$arrayLast = $this->dbPlay->requestLastFreeGatcha( $pid, $product )->result_array();

					if ( empty($arrayLast) )
					{
						if ( $arrayProduct[0]['termcount'] != null )
						{
							$group_count = 1;
						}
						else
						{
							$group_count = null;
						}
						$this->dbPlay->requestLoggingFreePack( $pid, $product, $group_count );
						$arrayProduct[0]['payment_type'] = null;
						$arrayProduct[0]['payment'] = 0;
						$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '무료뽑기('.$product.')' );
						$result = boolval(true);
					}
					else
					{
						$exec_datetime = new DateTime( $arrayLast[0]['exec_datetime'] );
						$cur_datetime = new DateTime( $arrayLast[0]['cur_datetime'] );
						$diff = $exec_datetime->diff($cur_datetime);
						$diff_sec = $diff->days * 24 * 60 * 60;
						$diff_sec += $diff->h * 60 * 60;
						$diff_sec += $diff->i * 60;
						$diff_sec += $diff->s;

						if ( $arrayProduct[0]['termcount'] != null )
						{
							if ( $cur_datetime->format('Y-m-d') != $exec_datetime->format('Y-m-d') )
							{
								$arrayLast[0]['group_count'] = 0;
							}

							if ( $arrayLast[0]['group_count'] >= $arrayProduct[0]['termcount'] )
							{
								$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrayProduct[0]['payment_type'], $arrayProduct[0]['payment'], '상품구매('.$product.')' );
								if ( $result )
								{
									$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '상품구매('.$product.')' );
								}
								else
								{
									$resultCode = MY_Controller::STATUS_BUYITEM_ERROR_METHOD;
									$resultText = MY_Controller::MESSAGE_BUYITEM_ERROR_METHOD;
									$arrayResult = null;
								}
							}
							else
							{
								if ( $diff_sec > $arrayProduct[0]['termmin'] * $arrayLast[0]['group_count'] * 60 )
								{
									$this->dbPlay->requestLoggingFreePack( $pid, $product, ( $arrayLast[0]['group_count'] != null ? $arrayLast[0]['group_count'] + 1 : 1 ) );
									$arrayProduct[0]['payment_type'] = null;
									$arrayProduct[0]['payment'] = 0;
									$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '무료뽑기('.$product.')' );
									$result = boolval(true);
								}
								else
								{
									$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrayProduct[0]['payment_type'], $arrayProduct[0]['payment'], '상품구매('.$product.')' );
									if ( $result )
									{
										$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '상품구매('.$product.')' );
									}
									else
									{
										$resultCode = MY_Controller::STATUS_BUYITEM_ERROR_METHOD;
										$resultText = MY_Controller::MESSAGE_BUYITEM_ERROR_METHOD;
										$arrayResult = null;
									}
								}
							}
						}
						else
						{
							if ( $diff_sec > $arrayProduct[0]['termmin'] * 60 )
							{
								$this->dbPlay->requestLoggingFreePack( $pid, $product, null );
								$arrayProduct[0]['payment_type'] = null;
								$arrayProduct[0]['payment'] = 0;
								$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '상품구매('.$product.')' );
								$result = boolval(true);
							}
							else
							{
								$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrayProduct[0]['payment_type'], $arrayProduct[0]['payment'], '상품구매('.$product.')' );
								if ( $result )
								{
									$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '상품구매('.$product.')' );
								}
								else
								{
									$resultCode = MY_Controller::STATUS_BUYITEM_ERROR_METHOD;
									$resultText = MY_Controller::MESSAGE_BUYITEM_ERROR_METHOD;
									$arrayResult = null;
								}
							}
						}
					}
				}
				else
				{
					$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrayProduct[0]['payment_type'], $arrayProduct[0]['payment'], '상품구매('.$product.')' );
					if ( $result )
					{
						$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '상품구매('.$product.')' );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_BUYITEM_ERROR_METHOD;
						$resultText = MY_Controller::MESSAGE_BUYITEM_ERROR_METHOD;
						$arrayResult = null;
					}
				}

				if ( $arrayResult == null )
				{
					$resultCode = MY_Controller::STATUS_BUYITEM_ITEM_GIVE;
					$resultText = MY_Controller::MESSAGE_BUYITEM_ITEM_GIVE;
					$result = (bool)0;
				}
				else
				{
					//공통처리
					$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
					unset( $arrayResult['rewardobject'] );
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $arrayProduct[0]['payment'] );
				}

				$this->dbPlay->onEndTransaction( $result );
				if ( $arrayResult != null )
				{
					$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				}
			}

			$arrayResult['free'] = $this->dbPlay->requestFreeGatcha( $pid )->result_array();
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestBuyIap()
	{
		$pid = $this->decoded['pid'];
		$product = $this->decoded['product'];
		$price = $this->decoded['price'];
		$sid = $this->decoded['sid'];
		$storeType = strtolower( $this->decoded['storetype'] );
		$paymentSeq = $this->decoded['receipt'];
		if ( array_key_exists( 'signdata', $this->decoded ) )
		{
			$signdata = $this->decoded['signdata'];
		}
		if ( array_key_exists( 'productId', $this->decoded ) )
		{
			$productId = $this->decoded['productId'];
		}
		$is_consume = $this->decoded['consume'];
		if ( array_key_exists( 'country_code', $this->decoded ) )
		{
			$country_code = $this->decoded['country_code'];
		}
		else
		{
			$country_code = '';
		}
		$country_code = 'KRW';

		if ( $pid && $storeType && $product && $sid )
		{
			if ( $storeType != 'editor' && $storeType != 'ios' && $storeType != 'google' && $storeType != 'naver' && $storeType != 'tstore' )
			{
				$storeType = 'editor';
			}

			$arrayProduct = $this->dbRef->iapVerify( $pid, $storeType, $product )->result_array();
			if ( count($arrayProduct) < 1 )
			{
				$resultCode = MY_Controller::STATUS_ABNORMALPRODUCT;
				$resultText = MY_Controller::MESSAGE_ABNORMALPRODUCT;
				$arrayResult = null;
			}
			else
			{
				$product_status = true;
				if (
					$arrayProduct[0]['product_type'] == MY_Controller::PRODUCTTYPE_PACKAGE_FOREVER
					|| $arrayProduct[0]['product_type'] == MY_Controller::PRODUCTTYPE_PACKAGE_ENDPOINT
					|| $arrayProduct[0]['product_type'] == MY_Controller::PRODUCTTYPE_PACKAGE_MONTHLY
					|| $arrayProduct[0]['product_type'] == MY_Controller::PRODUCTTYPE_PACKAGE_DAILY
				)
				{
					$product_status = !(bool)$this->dbPlay->requestPackageBuyHistory( $pid, $product, $arrayProduct[0]['product_type'] );
				}

				if ( $product_status )
				{
					if ( $storeType == 'editor' )
					{
						// 지급 처리
						if ( $pid != $sid )
						{
							if ( $arrayProduct[0]['attach_value'] > 0 )
							{
								$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, $arrayProduct[0]['type'], $arrayProduct[0]['attach_value'], false );
							}
							if ( $arrayProduct[0]['bonus'] > 0 )
							{
								$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, 'EVENT_POINTS', $arrayProduct[0]['bonus'], false );
							}
							/*
							if ( array_key_exists( 'vip_exp', $arrayProduct[0] ) )
							{
								if ( $arrayProduct[0]['vip_exp'] > 0 )
								{
									$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct[0]['vip_exp'] )->result_array();
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
														$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
														$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
													}
												}
											}
										}
										$arrayResult['vipinfo'] = $vipInfo[0];
									}
									else
									{
										$arrayResult['vipinfo'] = null;
									}
								}
							}
							else
							{
								$arrayResult['vipinfo'] = null;
							}
							*/
							$arrayResult['vipinfo'] = null;
						}
						else
						{
							$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $sid, '상품구매('.$product.')' );
						}
						//패키지 추가지급 코드
						$arrayPackageList = $this->dbRef->requestPackageList( $pid, $product )->result_array();
						if ( !empty($arrayPackageList) )
						{
							foreach( $arrayPackageList as $row )
							{
								$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['type'], $row['value'], false );
							}
						}

						//지급 성공
						$is_provision = 1;
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $price );

						$receiptPaymentSeq = '';
						$receiptApprovedPaymentNo = '';
						$receiptNaverId = '';
						$receiptPaymentTime = '';
						$reasonCode = MY_Controller::REASONCODE_IAP_NORMAL;
					}
					else if ( $storeType == 'naver' )
					{
						if ( $this->dbPlay->requestBuyIapExists( $pid, $storeType, $paymentSeq ) )
						{
							$arrayResult = null;
							//지급 오류
							$is_provision = 0;
							$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
							$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
							$receiptPaymentSeq = '';
							$receiptApprovedPaymentNo = '';
							$receiptNaverId = '';
							$receiptPaymentTime = '';
							$reasonCode = MY_Controller::REASONCODE_RECEIPT_ALREADY_PROVISION;
						}
						else
						{
							$this->load->library( 'HmacManager', TRUE );
							$nonce = mt_rand();

							$url = 'http://apis.naver.com/'.MY_Controller::CP_ID.'/appStore/receiptV2.json?nonce='.$nonce.'&paymentSeq='.$paymentSeq;

							$encrypted_url = $this->hmacmanager->getEncryptUrl($url);

							//네이버 api 호출
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $encrypted_url);
							if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'exam' )
							{
								curl_setopt($ch, CURLOPT_HTTPHEADER, array('IAP_KEY:'.MY_Controller::PROD_IAP_KEY));
							}
							else
							{
								curl_setopt($ch, CURLOPT_HTTPHEADER, array('IAP_KEY:'.MY_Controller::TEST_IAP_KEY));
							}
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$arrResponse = json_decode( curl_exec( $ch ), true );

							if ( empty( $arrResponse ) )
							{
								$arrayResult = null;
								//지급 오류
								$is_provision = 0;
								$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
								$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

								$receiptPaymentSeq = '';
								$receiptApprovedPaymentNo = '';
								$receiptNaverId = '';
								$receiptPaymentTime = '';
								$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
							}
							else
							{
								if ( $arrResponse['code'] != 0 )
								{
									$arrayResult = null;
									//지급 오류
									$is_provision = 0;
									$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
									$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

									$receiptPaymentSeq = '';
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = '';
									$receiptPaymentTime = '';
									$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
								}
								else
								{
									$receipt = $arrResponse['result']['receipt'];
									$extra = json_decode($receipt['extra'], true);

									if ( $extra['sid'] = $sid && $extra['product'] == $product && $extra['iapcode'] == $arrayProduct[0]['iapcode'] )
									{
										if ( $is_consume )
										{
											// 지급 처리
											if ( $pid != $sid )
											{
												if ( $arrayProduct[0]['attach_value'] > 0 )
												{
													$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, $arrayProduct[0]['type'], $arrayProduct[0]['attach_value'], false );
												}
												if ( $arrayProduct[0]['bonus'] > 0 )
												{
													$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, 'EVENT_POINTS', $arrayProduct[0]['bonus'], false );
												}
												/*
												if ( array_key_exists( 'vip_exp', $arrayProduct[0] ) )
												{
													if ( $arrayProduct[0]['vip_exp'] > 0 )
													{
														$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct[0]['vip_exp'] )->result_array();
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
																			$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
																			$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
																		}
																	}
																}
															}
															$arrayResult['vipinfo'] = $vipInfo[0];
														}
														else
														{
															$arrayResult['vipinfo'] = null;
														}
													}
												}
												else
												{
													$arrayResult['vipinfo'] = null;
												}
												*/
												$arrayResult['vipinfo'] = null;
											}
											else
											{
												$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $sid, '상품구매('.$product.')' );
											}

											//패키지 추가지급 코드
											$arrayPackageList = $this->dbRef->requestPackageList( $pid, $product )->result_array();
											if ( !empty($arrayPackageList) )
											{
												foreach( $arrayPackageList as $row )
												{
													$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['type'], $row['value'], false );
												}
											}

											//지급 성공
											$is_provision = 1;
											$resultCode = MY_Controller::STATUS_API_OK;
											$resultText = MY_Controller::MESSAGE_API_OK;
											$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $price );
											$reasonCode = MY_Controller::REASONCODE_IAP_NORMAL;
										}
										else
										{
											$arrayResult = null;
											//지급 오류
											$is_provision = 0;
											$resultCode = MY_Controller::STATUS_CONSUME;
											$resultText = MY_Controller::MESSAGE_CONSUME;
											$reasonCode = MY_Controller::REASONCODE_CONSUME_FAILED;
										}
									}
									else
									{
										$arrayResult = null;
										//지급 오류
										$is_provision = 0;
										$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
										$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
										$reasonCode = MY_Controller::REASONCODE_DOESNT_MATCH_RECEIPT;
									}
									$receiptPaymentSeq = $receipt['paymentSeq'];
									$receiptApprovedPaymentNo = $receipt['approvedPaymentNo'];
									$receiptNaverId = $receipt['naverId'];
									$receiptPaymentTime = date('Y-m-d H:i:s', $receipt['paymentTime'] / 1000);
								}
							}
						}
					}
					else if ( $storeType == 'google' )
					{
						if ( $this->dbPlay->requestBuyIapExists( $pid, $storeType, $paymentSeq ) )
						{
							$arrayResult = null;
							//지급 오류
							$is_provision = 0;
							$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
							$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
							$receiptPaymentSeq = '';
							$receiptApprovedPaymentNo = '';
							$receiptNaverId = '';
							$receiptPaymentTime = '';
							$reasonCode = MY_Controller::REASONCODE_RECEIPT_ALREADY_PROVISION;
						}
						else
						{
							if ( ENVIRONMENT == 'production' )
							{
								$kurl = 'http://m.koccommon.tntgame.co.kr/refresh.php';
								$sFileName = 'http://m.koccommon.tntgame.co.kr/token.htm';
							}
							else
							{
								$kurl = 'http://'.$_SERVER['HTTP_HOST'].'/koc/refresh.php';
								$sFileName = 'http://'.$_SERVER['HTTP_HOST'].'/koc/token.htm';
							}
							$kch = curl_init();
							curl_setopt($kch, CURLOPT_URL, $kurl);
							curl_setopt($kch, CURLOPT_RETURNTRANSFER, 1);
							curl_exec( $kch );

							$sResult = json_decode(file_get_contents($sFileName),true);
							$url = 'https://www.googleapis.com/androidpublisher/v1.1/applications/com.ENP.robotheroes.global.googleplay/inapp/'.$arrayProduct[0]['iapcode'].'/purchases/'.$paymentSeq;
							$url .= '?access_token='.$sResult['access_token'];

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_SSLVERSION,3); // SSL 버젼 (https 접속시에 필요)
							$arrResponse = json_decode( curl_exec( $ch ), true );

							if ( empty( $arrResponse ) )
							{
								$arrayResult = null;
								//지급 오류
								$is_provision = 0;
								$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
								$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

								$receiptPaymentSeq = '';
								$receiptApprovedPaymentNo = '';
								$receiptNaverId = '';
								$receiptPaymentTime = '';
								$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
							}
							else
							{
								if ($arrResponse == false || $arrResponse == '' || empty( $arrResponse ) )
								{
									$arrayResult = null;
									//지급 오류
									$is_provision = 0;
									$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
									$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

									$receiptPaymentSeq = '';
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = '';
									$receiptPaymentTime = '';
									$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
								}
								else
								{
									if ( array_key_exists('developerPayload', $arrResponse) )
									{
										$extra   = json_decode( $arrResponse['developerPayload'], true );

										if ( $extra['sid'] == $sid && $extra['product'] == $product && $extra['iapcode'] == $arrayProduct[0]['iapcode'] )
										{
											if ( $is_consume )
											{
												// 지급 처리
												if ( $pid != $sid )
												{
													if ( $arrayProduct[0]['attach_value'] > 0 )
													{
														$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, $arrayProduct[0]['type'], $arrayProduct[0]['attach_value'], false );
													}
													if ( $arrayProduct[0]['bonus'] > 0 )
													{
														$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, 'EVENT_POINTS', $arrayProduct[0]['bonus'], false );
													}
													/*
													if ( array_key_exists( 'vip_exp', $arrayProduct[0] ) )
													{
														if ( $arrayProduct[0]['vip_exp'] > 0 )
														{
															$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct[0]['vip_exp'] )->result_array();
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
																				$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
																				$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
																			}
																		}
																	}
																}
																$arrayResult['vipinfo'] = $vipInfo[0];
															}
															else
															{
																$arrayResult['vipinfo'] = null;
															}
														}
													}
													else
													{
														$arrayResult['vipinfo'] = null;
													}
													*/
													$arrayResult['vipinfo'] = null;
												}
												else
												{
													$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $sid, '상품구매('.$product.')' );
												}
												//패키지 추가지급 코드
												$arrayPackageList = $this->dbRef->requestPackageList( $pid, $product )->result_array();
												if ( !empty($arrayPackageList) )
												{
													foreach( $arrayPackageList as $row )
													{
														$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['type'], $row['value'], false );
													}
												}

												//지급 성공
												$is_provision = 1;
												$resultCode = MY_Controller::STATUS_API_OK;
												$resultText = MY_Controller::MESSAGE_API_OK;
												$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $price );

												$receiptPaymentSeq = '';
												$receiptApprovedPaymentNo = '';
												$receiptNaverId = '';
												$receiptPaymentTime = '';
												$reasonCode = MY_Controller::REASONCODE_IAP_NORMAL;
											}
											else
											{
												$arrayResult = null;
												//지급 오류
												$is_provision = 0;
												$resultCode = MY_Controller::STATUS_CONSUME;
												$resultText = MY_Controller::MESSAGE_CONSUME;
												$reasonCode = MY_Controller::REASONCODE_CONSUME_FAILED;
											}
										}
										else
										{
											$arrayResult = null;
											//지급 오류
											$is_provision = 0;
											$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
											$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
											$reasonCode = MY_Controller::REASONCODE_DOESNT_MATCH_RECEIPT;
										}
									}
									else
									{
										$arrayResult = null;
										//지급 오류
										$is_provision = 0;
										$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
										$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
										$reasonCode = MY_Controller::REASONCODE_DOESNT_MATCH_RECEIPT;
									}
									$receiptPaymentSeq = $paymentSeq;
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = '';
									if ( array_key_exists('purchaseTime', $arrResponse) )
									{
										$receiptPaymentTime = date('Y-m-d H:i:s', $arrResponse['purchaseTime'] / 1000);
									}
									else
									{
										$receiptPaymentTime = null;
									}
								}
							}
						}
					}
					else if ( $storeType == 'tstore' )
					{
						if ( $this->dbPlay->requestBuyIapExists( $pid, $storeType, $paymentSeq ) )
						{
							$arrayResult = null;
							//지급 오류
							$is_provision = 0;
							$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
							$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
							$receiptPaymentSeq = '';
							$receiptApprovedPaymentNo = '';
							$receiptNaverId = '';
							$receiptPaymentTime = '';
							$reasonCode = MY_Controller::REASONCODE_RECEIPT_ALREADY_PROVISION;
						}
						else
						{
							if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'exam' )
							{
								$url = 'https://iap.tstore.co.kr/digitalsignconfirm.iap';
							}
							else
							{
								$url = 'https://iapdev.tstore.co.kr/digitalsignconfirm.iap';
							}
							//네이버 api 호출  TSTORE_APPID

							$sendData = array('txid' => $paymentSeq, 'appid' => MY_Controller::TSTORE_APPID, 'signdata' => $signdata );
							$sendData_string = json_encode( $sendData, JSON_UNESCAPED_UNICODE);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_SSLVERSION,3); // SSL 버젼 (https 접속시에 필요)
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData_string);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen($sendData_string)));

							$arrResponse = json_decode( curl_exec( $ch ), true );

							if ( empty( $arrResponse ) )
							{
								$arrayResult = null;
								//지급 오류
								$is_provision = 0;
								$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
								$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

								$receiptPaymentSeq = '';
								$receiptApprovedPaymentNo = '';
								$receiptNaverId = '';
								$receiptPaymentTime = '';
								$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
							}
							else
							{
								if ( $arrResponse['status'] != 0 || $arrResponse['detail'] != '0000' )
								{
									$arrayResult = null;
									//지급 오류
									$is_provision = 0;
									$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
									$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

									$receiptPaymentSeq = '';
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = '';
									$receiptPaymentTime = '';
									$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
								}
								else
								{
									$bpInfo = urldecode( $arrResponse['product'][0]['bp_info'] );
									$extra   = json_decode( $bpInfo, true );

									if ( $extra['sid'] = $sid && $extra['product'] == $product && $extra['iapcode'] == $arrayProduct[0]['iapcode'] )
									{
										if ( $is_consume )
										{
											// 지급 처리
											if ( $pid != $sid )
											{
												if ( $arrayProduct[0]['attach_value'] > 0 )
												{
													$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, $arrayProduct[0]['type'], $arrayProduct[0]['attach_value'], false );
												}
												if ( $arrayProduct[0]['bonus'] > 0 )
												{
													$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, 'EVENT_POINTS', $arrayProduct[0]['bonus'], false );
												}
												/*
												if ( array_key_exists( 'vip_exp', $arrayProduct[0] ) )
												{
													if ( $arrayProduct[0]['vip_exp'] > 0 )
													{
														$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct[0]['vip_exp'] )->result_array();
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
																			$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
																			$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
																		}
																	}
																}
															}
															$arrayResult['vipinfo'] = $vipInfo[0];
														}
														else
														{
															$arrayResult['vipinfo'] = null;
														}
													}
												}
												else
												{
													$arrayResult['vipinfo'] = null;
												}
												*/
												$arrayResult['vipinfo'] = null;
											}
											else
											{
												$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $sid, '상품구매('.$product.')' );
											}

											//패키지 추가지급 코드
											$arrayPackageList = $this->dbRef->requestPackageList( $pid, $product )->result_array();
											if ( !empty($arrayPackageList) )
											{
												foreach( $arrayPackageList as $row )
												{
													$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['type'], $row['value'], false );
												}
											}

											//지급 성공
											$is_provision = 1;
											$resultCode = MY_Controller::STATUS_API_OK;
											$resultText = MY_Controller::MESSAGE_API_OK;
											$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $price );

											$receiptPaymentSeq = '';
											$receiptApprovedPaymentNo = '';
											$receiptNaverId = '';
											$receiptPaymentTime = '';
											$reasonCode = MY_Controller::REASONCODE_IAP_NORMAL;
										}
										else
										{
											$arrayResult = null;
											//지급 오류
											$is_provision = 1;
											$resultCode = MY_Controller::STATUS_CONSUME;
											$resultText = MY_Controller::MESSAGE_CONSUME;
											$reasonCode = MY_Controller::REASONCODE_CONSUME_FAILED;
										}
									}
									else
									{
										$arrayResult = null;
										//지급 오류
										$is_provision = 0;
										$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
										$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
										$reasonCode = MY_Controller::REASONCODE_DOESNT_MATCH_RECEIPT;
									}
									$receiptPaymentSeq = $paymentSeq;
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = $arrResponse['product'][0]['tid'];
									$receiptPaymentTime = DateTime::createFromFormat('YmdHis', $arrResponse['product'][0]['log_time']);
									$receiptPaymentTime = $receiptPaymentTime->format('Y-m-d H:i:s');
								}
							}
						}
					}
					else if ( $storeType == 'ios' )
					{
						$paymentSeqParse = preg_replace( '/\n/s', '', base64_decode( $paymentSeq ) );
						$paymentSeqParse = preg_replace( '/\s/s', '', $paymentSeqParse );
						$paymentSeqParse = preg_replace( '/;/s', ',', $paymentSeqParse );
						$paymentSeqParse = preg_replace( '/,}/s', '}', $paymentSeqParse );
						$paymentSeqParse = preg_replace( '/"="/s', '":"', $paymentSeqParse );
						$paymentSeqParse = json_decode( $paymentSeqParse, true );

						$verify_host = '';
						if ( $paymentSeqParse['environment'] == 'Sandbox' )
						{
							$verify_host = 'https://sandbox.itunes.apple.com/verifyReceipt';
						}
						else
						{
							$verify_host = 'https://buy.itunes.apple.com/verifyReceipt';
						}
						/*
						if ( ENVIRONMENT == 'production' || ENVIRONMENT == 'exam' )
						{
							$verify_host = 'https://buy.itunes.apple.com/verifyReceipt';
						}
						else
						{
							$verify_host = 'https://sandbox.itunes.apple.com/verifyReceipt';
						}
						*/

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $verify_host);
				        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				        curl_setopt($ch, CURLOPT_POST, true);
				        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array( 'receipt-data' => $paymentSeq )));
				        $arrResponse = json_decode( curl_exec( $ch ), true );

						if ( $arrResponse['status'] != 0 )
						{
							$arrayResult = null;
							//지급 오류
							$is_provision = 0;
							$resultCode = MY_Controller::STATUS_RECEIPT_INFO;
							$resultText = MY_Controller::MESSAGE_RECEIPT_INFO;

							$receiptPaymentSeq = '';
							$receiptApprovedPaymentNo = '';
							$receiptNaverId = $arrResponse['status'];
							$receiptPaymentTime = '';
							$reasonCode = MY_Controller::REASONCODE_CANT_GET_RECEIPT;
						}
						else
						{
							if ( $arrResponse['receipt']['product_id'] == $arrayProduct[0]['iapcode'] )
							{
								if ( $is_consume )
								{
									// 지급 처리
									if ( $pid != $sid )
									{
										if ( $arrayProduct[0]['attach_value'] > 0 )
										{
											$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, $arrayProduct[0]['type'], $arrayProduct[0]['attach_value'], false );
										}
										if ( $arrayProduct[0]['bonus'] > 0 )
										{
											$this->dbMail->sendMail( $sid, $pid, MY_Controller::PACKAGE_SEND_TITLE, 'EVENT_POINTS', $arrayProduct[0]['bonus'], false );
										}
										/*
										if ( array_key_exists( 'vip_exp', $arrayProduct[0] ) )
										{
											if ( $arrayProduct[0]['vip_exp'] > 0 )
											{
												$vipInfo = $this->dbPlay->requestVipInfo( $pid, $arrayProduct[0]['vip_exp'] )->result_array();
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
																	$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
																	$this->dbPlay->updateVipRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
																}
															}
														}
													}
													$arrayResult['vipinfo'] = $vipInfo[0];
												}
												else
												{
													$arrayResult['vipinfo'] = null;
												}
											}
										}
										else
										{
											$arrayResult['vipinfo'] = null;
										}
										*/
										$arrayResult['vipinfo'] = null;
									}
									else
									{
										$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $sid, '상품구매('.$product.')' );
									}

									//패키지 추가지급 코드
									$arrayPackageList = $this->dbRef->requestPackageList( $pid, $product )->result_array();
									if ( !empty($arrayPackageList) )
									{
										foreach( $arrayPackageList as $row )
										{
											$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::PACKAGE_SEND_TITLE, $row['type'], $row['value'], false );
										}
									}

									//지급 성공
									$is_provision = 1;
									$resultCode = MY_Controller::STATUS_API_OK;
									$resultText = MY_Controller::MESSAGE_API_OK;
									$arrayResult['payment_info'] = array( 'payment_type' => $arrayProduct[0]['payment_type'], 'payment_value' => $price );

									$receiptPaymentSeq = '';
									$receiptApprovedPaymentNo = '';
									$receiptNaverId = '';
									$receiptPaymentTime = '';
									$reasonCode = MY_Controller::REASONCODE_IAP_NORMAL;
								}
								else
								{
									$arrayResult = null;
									//지급 오류
									$is_provision = 1;
									$resultCode = MY_Controller::STATUS_CONSUME;
									$resultText = MY_Controller::MESSAGE_CONSUME;
									$reasonCode = MY_Controller::REASONCODE_CONSUME_FAILED;
								}
							}
							else
							{
								$arrayResult = null;
								//지급 오류
								$is_provision = 0;
								$resultCode = MY_Controller::STATUS_RECEIPT_INFO_DISCORD;
								$resultText = MY_Controller::MESSAGE_RECEIPT_INFO_DISCORD;
								$reasonCode = MY_Controller::REASONCODE_DOESNT_MATCH_RECEIPT;
							}
							$receiptPaymentSeq = $paymentSeq;
							$receiptApprovedPaymentNo = '';
							$receiptNaverId = $arrResponse['receipt']['transaction_id'];
							$receiptPaymentTime = substr($arrResponse['receipt']['purchase_date'], 0, 19);
						}
					}
					if ( $arrayResult != null )
					{
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$curcash = $this->dbPlay->requestItem( $sid )->result_array()[0];
					}
					else
					{
						$curcash['cash_points'] = 0;
						$curcash['event_points'] = 0;
					}
				}
				else
				{
					$is_provision = 0;
					$resultCode = MY_Controller::STATUS_DUPLICATE_PURCHASE;
					$resultText = MY_Controller::MESSAGE_DUPLICATE_PURCHASE;
					$arrayResult = null;
					$receiptPaymentSeq = '';
					$receiptApprovedPaymentNo = '';
					$receiptNaverId = '';
					$receiptPaymentTime = '';
					$reasonCode = MY_Controller::REASONCODE_PACKAGE_STATUS;
					$curcash['cash_points'] = 0;
					$curcash['event_points'] = 0;
				}
				//공통처리
				$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
				unset( $arrayResult['rewardobject'] );
				$this->dbPlay->requestLogIap( $is_provision, $pid, $sid, $storeType, $product, $country_code, $arrayProduct[0]['payment_type'], $price, $receiptPaymentSeq, $receiptApprovedPaymentNo, $receiptNaverId, $receiptPaymentTime, $curcash['cash_points'].','.$curcash['event_points'], $reasonCode );
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingStartStepForPVE()
	{
		$pid = $this->decoded['pid'];
		$stageid = $this->decoded['stageid'];
		$fid = $this->decoded['friendid'];
		$instant_item[0] = $this->decoded['instant_item1'];
		$instant_item[1] = $this->decoded['instant_item2'];
		$instant_item[2] = $this->decoded['instant_item3'];
		$instant_item[3] = $this->decoded['instant_item4'];
		if ( $instant_item[0] )
		{
			$instant_itemName[] = $instant_item[0];
		}
		if ( $instant_item[1] )
		{
			$instant_itemName[] = $instant_item[1];
		}
		if ( $instant_item[2] )
		{
			$instant_itemName[] = $instant_item[2];
		}
		if ( $instant_item[3] )
		{
			$instant_itemName[] = $instant_item[3];
		}

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			$this->dbRecord->onBeginTransaction();
			$result = $this->dbRecord->requestLoggingStartPVE( $pid, $stageid, $fid, $instant_item[0], $instant_item[1], $instant_item[2], $instant_item[3] );
			$result2 = $result;
			$usePoint = $this->dbRef->requestPriceProduct( $pid, $instant_item )->result_array();

			if ( !(empty($usePoint)) )
			{
				foreach( $usePoint as $row)
				{
					$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $row['payment_type'], $row['payment'], 'PVE아이템 사용('.implode(',', $instant_itemName).')' );
				}
			}
			else
			{
				$result = (bool)1;
			}

			if ( $result )
			{
				$this->calcurateEnergy( $pid );
				$arrEng = $this->dbRef->requestStageEnergy( $pid, $stageid )->result_array();
				if ( empty($arrEng) )
				{
					$result = (bool)0;
				}
				else
				{
					$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'ENERGY_POINTS', $arrEng[0]['energy'], 'PVE출격' );
					if ( $fid )
					{
						//양쪽 10포인트씩 주기
						$this->dbMail->sendMail(
							$pid, MY_Controller::SENDER_GM, MY_Controller::FRIENDHELP_REWARD_TITLE, 'FRIENDSHIP_POINTS',
							MY_Controller::FRIEND_HELP_BASIC_POINT, MY_Controller::NORMAL_EXPIRE_TERM
						);
						$this->dbMail->sendMail(
							$fid, MY_Controller::SENDER_GM, MY_Controller::FRIENDHELP_REWARD_TITLE, 'FRIENDSHIP_POINTS',
							MY_Controller::FRIEND_HELP_BASIC_POINT, MY_Controller::NORMAL_EXPIRE_TERM
						);
						$result = (bool)$result & (bool)$this->dbPlay->updateFriendHelpTime( $pid, $fid );

						$friendInfo = $this->dbPlay->requestFriendCharInfo( $pid, $fid )->result_array()[0];
						if ( $friendInfo['refid'] == null )
						{
							$friendInfo['refid'] = 'RS0300942';
							$friendInfo['level'] = '1';
							$friendInfo['up_grade'] = '0';
						}
						$result = (bool)1;
					}
					else
					{
						$friendInfo['fid'] = null;
					}
				}

				if ( $result )
				{
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = array(
						'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0], 'friend_info' => $friendInfo
					);

					// 로그 문자열 만들기 시작
					if ( $fid == '' || $fid == null )
					{
						$fid == '없음';
					}
					$logString = '행성전 시작 > '.$stageid;

					$itemLog = '';
					foreach( $instant_item as $row )
					{
						if ( $row != null && $row != '' && $row != '0' )
						{
							if ( !empty($arrItem) )
							{
								if ( $itemLog != '' )
								{
									$itemLog .= ', ';
								}
								$itemLog .= $row;
							}
						}
					}

					if ( $itemLog != '' )
					{
						$logString .= $itemLog.' 사용';
					}

					if ( $fid )
					{
						$logString .= '\n잔여 에너지 '.$arrayResult['remain_item']['energy_points'].'\n데려간 친구 '.$fid;
					}
					else
					{
						$logString .= '\n잔여 에너지 '.$arrayResult['remain_item']['energy_points'];
					}
					// 로그 문자열 만들기 끝

					$this->onSysLogWriteDb( $pid, $logString );
				}
				else
				{
					$resultCode = MY_Controller::STATUS_PVE_LACK_ENERGY;
					$resultText = MY_Controller::MESSAGE_PVE_LACK_ENERGY;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PVE_LACK_GOLD;
				$resultText = MY_Controller::MESSAGE_PVE_LACK_GOLD;
				$arrayResult = null;
			}
			$this->dbRecord->onEndTransaction( $result );
			$this->dbPlay->onEndTransaction( $result );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingRewardStepForPVE()
	{
		$pid = $this->decoded['pid'];
		$stageid = $this->decoded['stageid'];
		$logid = $this->decoded['logid'];
		$cidArray[0]['idx'] = $this->decoded['cid_0'];
		$cidArray[1]['idx'] = $this->decoded['cid_1'];
		$cidArray[2]['idx'] = $this->decoded['cid_2'];
		$cidArray[0]['refid'] = (array_key_exists( 'refid_0', $this->decoded ) ? $this->decoded['refid_0'] : null);
		$cidArray[1]['refid'] = (array_key_exists( 'refid_1', $this->decoded ) ? $this->decoded['refid_1'] : null);
		$cidArray[2]['refid'] = (array_key_exists( 'refid_2', $this->decoded ) ? $this->decoded['refid_2'] : null);
		$cidArray[0]['exp'] = $this->decoded['exp_0'];
		$cidArray[1]['exp'] = $this->decoded['exp_1'];
		$cidArray[2]['exp'] = $this->decoded['exp_2'];
		$cidArray[0]['lev'] = $this->decoded['lev_0'];
		$cidArray[1]['lev'] = $this->decoded['lev_1'];
		$cidArray[2]['lev'] = $this->decoded['lev_2'];
		$cidArray[0]['up_grade'] = (array_key_exists( 'up_grade_0', $this->decoded ) ? $this->decoded['up_grade_0'] : null);
		$cidArray[1]['up_grade'] = (array_key_exists( 'up_grade_1', $this->decoded ) ? $this->decoded['up_grade_1'] : null);
		$cidArray[2]['up_grade'] = (array_key_exists( 'up_grade_2', $this->decoded ) ? $this->decoded['up_grade_2'] : null);
		$basic_reward_type = $this->decoded['basic_reward_type'];
		$basic_reward_value = $this->decoded['basic_reward_value'];
		$duration = (array_key_exists( 'duration', $this->decoded ) ? $this->decoded['duration'] : null);
		$grade = $this->decoded['grade'];
		$is_clear = $this->decoded['is_clear'];
		if ( $is_clear < 0 )
		{
			$is_clear = 0;
		}

		$stageInfo = $this->dbRef->getRewardIdFromStage( $pid, $stageid )->result_array()[0];
		$rid1 = $stageInfo['reward_1'];
		$rid2 = $stageInfo['reward_2'];
		$rid3 = $stageInfo['reward_3'];
		$platformPoints = $stageInfo['platform_points'];
		if ( $cidArray[0]['idx'] > 0 )
		{
			$charInfo[0]['valid'] = 1;
			$charInfo[0]['obj'] = $this->dbPlay->requestCharacter( $pid, $cidArray[0]['idx'] )->result_array()[0];
		}
		else
		{
			$charInfo[0]['obj'] = null;
		}
		if ( $cidArray[1]['idx'] > 0 )
		{
			$charInfo[1]['obj'] = $this->dbPlay->requestCharacter( $pid, $cidArray[1]['idx'] )->result_array()[0];
		}
		else
		{
			$charInfo[1]['obj'] = null;
		}
		if ( $cidArray[2]['idx'] > 0 )
		{
			$charInfo[2]['obj'] = $this->dbPlay->requestCharacter( $pid, $cidArray[2]['idx'] )->result_array()[0];
		}
		else
		{
			$charInfo[2]['obj'] = null;
		}

		if ( $pid )
		{
			$stageReward = $this->dbRef->requestStageReward( $pid, $stageid )->result_array();
			if ( !empty($stageReward) )
			{
				$arrayRewardInfo = $this->dbRecord->requestLogRewardInfo( $pid, $logid )->result_array();
				if ( !empty($arrayRewardInfo) )
				{
					$instant_item[] = ($arrayRewardInfo[0]['instant_item1'] != null ? $arrayRewardInfo[0]['instant_item1'] : null);
					$instant_item[] = ($arrayRewardInfo[0]['instant_item2'] != null ? $arrayRewardInfo[0]['instant_item2'] : null);
					$instant_item[] = ($arrayRewardInfo[0]['instant_item3'] != null ? $arrayRewardInfo[0]['instant_item3'] : null);
					$instant_item[] = ($arrayRewardInfo[0]['instant_item4'] != null ? $arrayRewardInfo[0]['instant_item4'] : null);
				}
				else
				{
					$arrayRewardInfo = array();
				}

				if ( $basic_reward_type != MY_Controller::COMMON_PVE_REWARD_TYPE || $basic_reward_value > $stageReward[0]['max_gold'] )
				{
					$basic_reward_type = MY_Controller::COMMON_PVE_REWARD_TYPE;
					$basic_reward_value = intval($stageReward[0]['max_gold']);
				}

				if ( array_key_exists('exp', $charInfo[0]['obj']) )
				{
					if ( ($charInfo[0]['obj']['exp'] - $cidArray[0]['exp']) > $stageReward[0]['max_exp'] )
					{
						$cidArray[0]['exp'] = $charInfo[0]['obj']['exp'] + $stageReward[0]['max_exp'];
					}
				}
				else if ( array_key_exists('exp', $charInfo[1]['obj']) )
				{
					if ( ($charInfo[1]['obj']['exp'] - $cidArray[1]['exp']) > $stageReward[0]['max_exp'] )
					{
						$cidArray[1]['exp'] = $charInfo[1]['obj']['exp'] + $stageReward[0]['max_exp'];
					}
				}
				else if ( array_key_exists('exp', $charInfo[2]['obj']) )
				{
					if ( ($charInfo[2]['obj']['exp'] - $cidArray[2]['exp']) > $stageReward[0]['max_exp'] )
					{
						$cidArray[2]['exp'] = $charInfo[2]['obj']['exp'] + $stageReward[0]['max_exp'];
					}
				}

				if ( !( empty( $arrayRewardInfo ) ) )
				{
					$arrayProduct = $this->dbRef->randomizeValuePick( $pid, $rid1, '1' )->result_array()[0];
					$arrayProduct = array_merge($arrayProduct, $this->dbRef->randomizeValuePick( $pid, $rid2, '2' )->result_array()[0]);
					$arrayProduct = array_merge($arrayProduct, $this->dbRef->randomizeValuePick( $pid, $rid3, '3' )->result_array()[0]);

					$this->dbRecord->onBeginTransaction();
					$this->dbRank->onBeginTransaction();
					$this->dbPlay->onBeginTransaction();

					//기본보상 추가 (포인트)
					$arrayBasicProduct = array( array( 'article_type' => 'ASST', 'article_value' => $basic_reward_type, 'attach_value' => intval($basic_reward_value) ) );
					//2배 아이템 적용(골드)
					if ( in_array(MY_Controller::PRODUCT_ID_GOLD_BONUS, $instant_item) )
					{
						$arrayBasicProduct[] = $arrayBasicProduct[0];
					}

					//기본보상 추가 (기체 경험치)
					$txtCarray = '';
					$mlRewardList = array();
					foreach ($cidArray as $cRow)
					{
						//2배 아이템 적용(경험치)
						if ( in_array(MY_Controller::PRODUCT_ID_EXP_BONUS, $instant_item) )
						{
							$cRow['exp'] += $cRow['exp'];
						}

						if ( $cRow['idx'] != 0 )
						{
							$this->dbPlay->updateCharacter( $pid, $cRow['idx'], $cRow['lev'], $cRow['exp'] );
							$this->dbPlay->updateCollection( $pid, $cRow['refid'], $cRow['lev'] );
							$is_reward = $this->dbPlay->requestCollection( $pid, $cRow['refid'] )->result_array();
							if ( !empty( $is_reward ) )
							{
								if ( $cRow['lev'] > 29 && !($is_reward[0]['is_reward']) )
								{
									if ( $this->dbPlay->requestCollectionReward( $pid, $cRow['refid'] ) )
									{
										$this->dbMail->sendMail(
											$pid, MY_Controller::SENDER_GM, MY_Controller::CHARMAXLEV_REWARD_TITLE, MY_Controller::CHARMAXLEV_REWARD_TYPE,
											MY_Controller::CHARMAXLEV_REWARD_VALUE, MY_Controller::NORMAL_EXPIRE_TERM
										);
										$mlRewardList[] = array('refid' => $cRow['refid']);
									}
								}
							}
						}
					}
					//기본보상 추가 (플레이어 경험치)
					$player_exp = $stageInfo['reward_pexp'];
					$playerInfo = $this->dbPlay->requestPlayerInfo( $pid, $player_exp )->result_array();
					if ( !( empty( $playerInfo ) ) )
					{
						$this->dbPlay->requestUpdatePlayerInfo( $pid, $playerInfo[0]['player_level'], $playerInfo[0]['player_exp'] );
						$playerInfo = $playerInfo[0];
						if ( $playerInfo['prev_level'] < $playerInfo['player_level'] )
						{
							$playerReward = $this->dbRef->requestPlayerReward( $pid, $playerInfo['prev_level'], $playerInfo['player_level'] )->result_array();
							if ( !( empty($playerReward) ) )
							{
								foreach( $playerReward as $row )
								{
									if ( $row['reward_div'] == 'PERM' )
									{
										$this->dbPlay->updatePlayerBasic( $pid, $row['reward_type'], $row['reward_value'] );
									}
									else
									{
										$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
										$this->dbPlay->updatePlayerRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
									}
								}
							}
						}
						unset($playerInfo["prev_level"]);
					}
					else
					{
						$playerInfo = null;
					}

					//랜덤보상 지급
					if ( $is_clear )
					{
						$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, 'achieve_points', $platformPoints, 'PVE보상' );
						$this->commonUserResourceProvisioning( $arrayBasicProduct, $pid, $pid, 'PVE보상 수령' );
						$randomProduct[] = array( 'reward_type' => $arrayProduct['reward_1_type'], 'attach_value' => $arrayProduct['attach_1_value'], 'article_type' => $arrayProduct['article_1_type'], 'article_value' => $arrayProduct['article_1_value'] );
						if ( $grade > 1 )
						{
							$randomProduct[] = array( 'reward_type' => $arrayProduct['reward_2_type'], 'attach_value' => $arrayProduct['attach_2_value'], 'article_type' => $arrayProduct['article_2_type'], 'article_value' => $arrayProduct['article_2_value'] );
						}
						if ( $grade > 2 )
						{
							$randomProduct[] = array( 'reward_type' => $arrayProduct['reward_3_type'], 'attach_value' => $arrayProduct['attach_3_value'], 'article_type' => $arrayProduct['article_3_type'], 'article_value' => $arrayProduct['article_3_value'] );
						}
						$addProduct = null;
						foreach ( $randomProduct as $key => $row )
						{
							//2배 아이템 적용(골드)
							if ( in_array(MY_Controller::PRODUCT_ID_GOLD_BONUS, $instant_item) && $row['article_type'] == MY_Controller::COMMON_PVE_REWARD_TYPE )
							{
								$addProduct[] = $row;
							}

							//2배 아이템 적용(아이템)
							if ( in_array(MY_Controller::PRODUCT_ID_ITEM_BONUS, $instant_item) && ( in_array( $row["article_type"], json_decode(MY_Controller::ITEM_TYPE, true ) ) || $row["article_type"] == 'CHAR' ) )
							{
								$addProduct[] = $row;
							}
						}

						if ( count($addProduct) > 0 )
						{
							$randomProduct = array_merge($randomProduct, $addProduct);
						}

						$arrayResult = $this->commonUserResourceProvisioning( $randomProduct, $pid, $pid, 'PVE보상 수령' );
						$arrayResult = $this->commonRewardObject( $pid, $arrayResult, $playerInfo, $randomProduct );

						$arrayResult['basicreward'] = array( array( 'article_value' => 'achieve_points', 'attach_value' => intval($platformPoints) ) );
						$arrayResult['basicreward'][] = array( 'article_value' => $basic_reward_type, 'attach_value' => intval($basic_reward_value) );

						if ( in_array(MY_Controller::PRODUCT_ID_GOLD_BONUS, $instant_item) && $basic_reward_type == MY_Controller::COMMON_PVE_REWARD_TYPE )
						{
							$arrayResult['basicreward'][] = array( 'article_value' => $basic_reward_type, 'attach_value' => intval($basic_reward_value) );
						}

						$txtRandReward = '';
						foreach( $arrayResult['rewardobject'] as $key => $val )
						{
							if ( $key > 0 )
							{
								$txtRandReward .= '; ';
							}
							// 로그 추가 완료
							if ( array_key_exists('idx', $val) )
							{
								$idx = $val['idx'];
								$txtRandReward .= $val['reward_type'].', '.$val['attach_value'].', '.$val['idx'];
							}
							else
							{
								$idx = null;
								if ( $val['article_type'] == 'CHAR' )
								{
									$txtRandReward .= $val['reward_type'];
								}
								else if ( $val['article_type'] == 'SKIL' )
								{
									$txtRandReward .= $val['reward_type'];
								}
								else
								{
									$txtRandReward .= $val['reward_type'];
								}
								$txtRandReward .= ', '.$val['attach_value'];
							}
						}

						$result = (bool)$this->dbRecord->updateLoggingRewardStepForPVE( $pid, $logid, $cidArray, $basic_reward_type, $basic_reward_value, $arrayProduct, $arrayResult['rewardobject'], $duration, $grade, $is_clear );
						unset($arrayResult['rewardobject']);
						// 랭킹정보 추가 완료
						$this->dbRank->requestUpdatePVERank( $pid, $stageid, $duration, $grade );
					}
					else
					{
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$result = (bool)$this->dbRecord->updateLoggingRewardStepForPVE( $pid, $logid, $cidArray, $basic_reward_type, $basic_reward_value, null, null, $duration, null, $is_clear );
						$txtRandReward = '없음';
					}

					$this->dbPlay->onEndTransaction( $result );
					$this->dbRank->onEndTransaction( $result );
					$this->dbRecord->onEndTransaction( $result );

					if ( $arrayResult & $result )
					{
						$this->calcurateEnergy( $pid );
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$arrayResult['mlRewardList'] = $mlRewardList;
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;

						// 로그 문자열 만들기 시작
						$logString = '행성전 종료\n';
						$logString .= '클리어 여부 : ';
						if ( $is_clear )
						{
							$logString .= ' 성공\n';
						}
						else
						{
							$logString .= ' 실패\n';
						}
						$logString .= '소요시간 : '.$duration.'초\n';
						$logString .= '기본보상 : '.$basic_reward_type;
						$logString .= ' => '.$basic_reward_value.'\n';
						$logString .= '기본경험치 : ';
						$txtCarray = '';
						foreach ($cidArray as $cRow)
						{
							if ( $txtCarray != '' )
							{
								$txtCarray .= ', ';
							}
							$txtCarray .= $cRow['idx'].'번 기체 : 레벨 => '.$cRow['lev'].' : 경험치 => '.$cRow['exp'];
						}

						$logString .= '진행팀 정보 : [ \n\t아이디 => '.$pid.'\n';
						if ( $cidArray[0]['refid'] != null && $cidArray[0]['refid'] != '' && $cidArray[0]['refid'] != '0' )
						{
							$logString .= '\t출격기체1 => +'.$cidArray[0]['up_grade'].' '.$cidArray[0]['refid'].'('.$cidArray[0]['idx'].'),';
							$logString .= 'lv.'.$cidArray[0]['lev'].'\n';
						}
						if ( $cidArray[1]['refid'] != null && $cidArray[1]['refid'] != '' && $cidArray[1]['refid'] != '0' )
						{
							$logString .= '\t출격기체2 => +'.$cidArray[1]['up_grade'].' '.$cidArray[1]['refid'].'('.$cidArray[1]['idx'].'),';
							$logString .= 'lv.'.$cidArray[1]['lev'].'\n';
						}
						if ( $cidArray[2]['refid'] != null && $cidArray[2]['refid'] != '' && $cidArray[2]['refid'] != '0' )
						{
							$logString .= '\t출격기체2 => +'.$cidArray[2]['up_grade'].' '.$cidArray[2]['refid'].'('.$cidArray[2]['idx'].'),';
							$logString .= 'lv.'.$cidArray[2]['lev'].'\n';
						}
						$logString .= ' ] ';

						$logString .= $txtCarray.'\n';
						$logString .= '선택보상 : '.$txtRandReward;
						// 로그 문자열 만들기 끝

						$this->onSysLogWriteDb( $pid, $logString );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_PVE_REWARD_FAIL;
						$resultText = MY_Controller::MESSAGE_PVE_REWARD_FAIL;
						$arrayResult = null;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_PVE_REWARD_ALREADY;
					$resultText = MY_Controller::MESSAGE_PVE_REWARD_ALREADY;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PVE_REWARD_FAIL;
				$resultText = MY_Controller::MESSAGE_PVE_REWARD_FAIL;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingStartStepForPVB()
	{
		$pid = $this->decoded['pid'];
		$stageid = $this->decoded['stageid'];
		$use_cash = $this->decoded['use_cash'];
		$level = $this->decoded['level'];
		$instant_item[0] = $this->decoded['instant_item1'];
		$instant_item[1] = $this->decoded['instant_item2'];
		$instant_item[2] = $this->decoded['instant_item3'];
		$instant_item[3] = $this->decoded['instant_item4'];
		if ( $instant_item[0] )
		{
			$instant_itemName[] = $instant_item[0];
		}
		if ( $instant_item[1] )
		{
			$instant_itemName[] = $instant_item[1];
		}
		if ( $instant_item[2] )
		{
			$instant_itemName[] = $instant_item[2];
		}
		if ( $instant_item[3] )
		{
			$instant_itemName[] = $instant_item[3];
		}

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			$this->dbRecord->onBeginTransaction();
			$result = $this->dbRecord->requestLoggingStartPVB( $pid, $stageid, $level, $use_cash, $instant_item[0], $instant_item[1], $instant_item[2], $instant_item[3] );
			if ( $result )
			{
				$result2 = $result;

				$usePoint = $this->dbRef->requestPriceProduct( $pid, $instant_item )->result_array();

				if ( !(empty($usePoint)) )
				{
					foreach( $usePoint as $row)
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $row['payment_type'], $row['payment'], 'PVB아이템 사용('.implode(',', $instant_itemName).')' );
					}
				}
				else
				{
					$result = (bool)1;
				}

				if ( $result )
				{
					$this->calcurateEnergy( $pid );
					if ( $use_cash )
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'CASH_POINTS', MY_Controller::ADDITIONAL_MODE_PVB_PRICE, 'PVB 에너지 구매' );
						if ( $result )
						{
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult = array( 'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_PVB_LACK_CASH;
							$resultText = MY_Controller::MESSAGE_PVB_LACK_CASH;
							$arrayResult = null;
						}
					}
					else
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'PVB_POINTS', 1, 'PVB출격' );
						if ( $result )
						{
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult = array( 'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_PVB_LACK_PVBPOINT;
							$resultText = MY_Controller::MESSAGE_PVB_LACK_PVBPOINT;
							$arrayResult = null;
						}
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_PVB_LACK_GOLD;
					$resultText = MY_Controller::MESSAGE_PVB_LACK_GOLD;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PVB_START_FAIL;
				$resultText = MY_Controller::MESSAGE_PVB_START_FAIL;
				$arrayResult = null;
			}
			$this->dbRecord->onEndTransaction( $result );
			$this->dbPlay->onEndTransaction( $result );

			if ( $result )
			{
				// 로그 문자열 만들기 시작
				$logString = '보스대전 시작\n';
				$logString .= $stageid.' 레벨 => '.$level.'\n';

				$logString .= '진행팀 정보 : [ \n\t아이디 => '.$pid.'\n';
				if ( $this->decoded['op'] != null && $this->decoded['op'] != '' && $this->decoded['op'] != '0' )
				{
					$logString .= '\t오퍼레이터 => '.$this->decoded['op'].'\n';
				}
				if ( $this->decoded['pilot_0'] != null && $this->decoded['pilot_0'] != '' && $this->decoded['pilot_0'] != '0' )
				{
					$logString .= '\t파일럿1 => '.$this->decoded['pilot_0'].'\n';
				}
				if ( $this->decoded['pilot_1'] != null && $this->decoded['pilot_1'] != '' && $this->decoded['pilot_1'] != '0' )
				{
					$logString .= '\t파일럿2 => '.$this->decoded['pilot_1'].'\n';
				}
				if ( $this->decoded['pilot_2'] != null && $this->decoded['pilot_2'] != '' && $this->decoded['pilot_2'] != '0' )
				{
					$logString .= '\t파일럿3 => '.$this->decoded['pilot_2'].'\n';
				}

				if ( $this->decoded['refid_0'] != null && $this->decoded['refid_0'] != '' && $this->decoded['refid_0'] != '0' )
				{
					$logString .= '\t출격기체1 => +'.$this->decoded['up_grade_0'].' '.$this->decoded['refid_0'].'('.$this->decoded['cid_0'].'),';
					$logString .= 'lv.'.$this->decoded['lev_0'].'\n';
				}
				if ( $this->decoded['refid_1'] != null && $this->decoded['refid_1'] != '' && $this->decoded['refid_1'] != '0' )
				{
					$logString .= '\t출격기체2 => +'.$this->decoded['up_grade_1'].' '.$this->decoded['refid_1'].'('.$this->decoded['cid_1'].'), ';
					$logString .= 'lv.'.$this->decoded['lev_1'].'\n';
				}
				if ( $this->decoded['refid_2'] != null && $this->decoded['refid_2'] != '' && $this->decoded['refid_2'] != '0' )
				{
					$logString .= '\t출격기체3 => +'.$this->decoded['up_grade_2'].' '.$this->decoded['refid_2'].'('.$this->decoded['cid_2'].'), ';
					$logString .= 'lv.'.$this->decoded['lev_2'].'\n';
				}
				$logString .= ' ] ';

				$itemLog = '';
				foreach( $instant_item as $row )
				{
					if ( $row != null && $row != '' && $row != '0' )
					{
						if ( !empty($arrItem) )
						{
							if ( $itemLog != '' )
							{
								$itemLog .= ', ';
							}
							$itemLog .= $row;
						}
					}
				}

				$logString .= $itemLog;
				if ( $itemLog != '' )
				{
					$logString .= ' 사용';
				}
				$logString .= '\n캐시 사용여부 : ';
				if ( $use_cash )
				{
					$logString .= MY_Controller::ADDITIONAL_MODE_PVB_PRICE.' 사용';
				}
				else
				{
					$logString .= '미사용';
				}
				$logString .= '\nPVB_에너지 잔여량 '.$arrayResult['remain_item']['pvb_points'];
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingResultStepForPVB()
	{
		$pid = $this->decoded['pid'];
		$logid = $this->decoded['logid'];
		$score = $this->decoded['score'];
		$level = $this->decoded['level'];
		$stageid = $this->decoded['stageid'];
		$rstageid = substr($stageid, 0, strlen($stageid) - strlen($level)).$level;
		$stageReward = $this->dbRef->requestStageReward( $pid, $rstageid )->result_array();
		$is_clear = $this->decoded['is_clear'];
		if ( $is_clear < 0 )
		{
			$is_clear = 0;
		}

		if( $pid && $logid && !empty($stageReward) )
		{
			if ( $score > $stageReward[0]['max_exp'] )
			{
				$bossInfo = $this->dbRecord->requestBossInfo( $pid )->result_array();
				$score = $stageReward[0]['max_exp'] - $bossInfo[0]['off_hp'];
				if ( $is_clear == 0 )
				{
					$score = $score - 1;
				}
			}

			// 로그 추가 완료
			$result = $this->dbRecord->updateLoggingResultStepForPVB( $pid, $logid, $score, $is_clear );

			// 랭킹정보 추가
			$this->dbRank->requestUpdatePVBRank( $pid, $stageid, $score );
			if ( $result )
			{
				$this->calcurateEnergy( $pid );
				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$arrayResult['score'] = $score;
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				// 로그 문자열 만들기 시작
				$logString = '보스대전 종료\n';
				if ( $is_clear == 1 )
				{
					$logString .= '승리\n';
				}
				else
				{
					$logString .= '패배\n';
				}
				$logString .= '획득점수 : '.$score;
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PVB_SAVEINFO;
				$resultText = MY_Controller::MESSAGE_PVB_SAVEINFO;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingStartStepForSURVIVAL()
	{
		$pid = $this->decoded['pid'];
		$use_cash = $this->decoded['use_cash'];
		$instant_item[0] = $this->decoded['instant_item1'];
		$instant_item[1] = $this->decoded['instant_item2'];
		$instant_item[2] = $this->decoded['instant_item3'];
		$instant_item[3] = $this->decoded['instant_item4'];
		if ( $instant_item[0] )
		{
			$instant_itemName[] = $instant_item[0];
		}
		if ( $instant_item[1] )
		{
			$instant_itemName[] = $instant_item[1];
		}
		if ( $instant_item[2] )
		{
			$instant_itemName[] = $instant_item[2];
		}
		if ( $instant_item[3] )
		{
			$instant_itemName[] = $instant_item[3];
		}

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			$this->dbRecord->onBeginTransaction();
			$result = $this->dbRecord->requestLoggingStartSURVIVAL( $pid, $use_cash, $instant_item[0], $instant_item[1], $instant_item[2], $instant_item[3] );

			if ( $result )
			{
				$result2 = $result;

				$usePoint = $this->dbRef->requestPriceProduct( $pid, $instant_item )->result_array();

				if ( !(empty($usePoint)) )
				{
					foreach( $usePoint as $row)
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $row['payment_type'], $row['payment'], 'SURVIVAL아이템 사용('.implode(',', $instant_itemName).')' );
					}
				}
				else
				{
					$result = (bool)1;
				}

				if ( $result )
				{
					$this->calcurateEnergy( $pid );
					if ( $use_cash )
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'CASH_POINTS', MY_Controller::ADDITIONAL_MODE_SURVIVAL_PRICE, 'SURVIVAL 에너지 구매' );
						if ( $result )
						{
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult = array( 'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_SUR_LACK_CASH;
							$resultText = MY_Controller::MESSAGE_SUR_LACK_CASH;
							$arrayResult = null;
						}
					}
					else
					{
						$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'SURVIVAL_POINTS', 1, 'SURVIVAL 출격' );
						if ( $result )
						{
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult = array( 'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_SUR_LACK_PVBPOINT;
							$resultText = MY_Controller::MESSAGE_SUR_LACK_PVBPOINT;
							$arrayResult = null;
						}
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_SUR_LACK_GOLD;
					$resultText = MY_Controller::MESSAGE_SUR_LACK_GOLD;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_SUR_START_FAIL;
				$resultText = MY_Controller::MESSAGE_SUR_START_FAIL;
				$arrayResult = null;
			}

			$this->dbRecord->onEndTransaction( $result );
			$this->dbPlay->onEndTransaction( $result );
			if ( $result )
			{
				// 로그 문자열 만들기 시작
				$logString = '생존 시작\n';

				$logString .= '진행팀 정보 : \n\t아이디 => '.$pid.'\n';
				if ( $this->decoded['op'] != null && $this->decoded['op'] != '' && $this->decoded['op'] != '0' )
				{
					$logString .= '\t오퍼레이터 => '.$this->decoded['op'].'\n';
				}
				if ( $this->decoded['pilot_0'] != null && $this->decoded['pilot_0'] != '' && $this->decoded['pilot_0'] != '0' )
				{
					$logString .= '\t파일럿1 => '.$this->decoded['pilot_0'].'\n';
				}
				if ( $this->decoded['pilot_1'] != null && $this->decoded['pilot_1'] != '' && $this->decoded['pilot_1'] != '0' )
				{
					$logString .= '\t파일럿2 => '.$this->decoded['pilot_1'].'\n';
				}
				if ( $this->decoded['pilot_2'] != null && $this->decoded['pilot_2'] != '' && $this->decoded['pilot_2'] != '0' )
				{
					$logString .= '\t파일럿3 => '.$this->decoded['pilot_2'].'\n';
				}

				if ( $this->decoded['refid_0'] != null && $this->decoded['refid_0'] != '' && $this->decoded['refid_0'] != '0' )
				{
					$logString .= '\t출격기체1 => +'.$this->decoded['up_grade_0'].' '.$this->decoded['refid_0'].'('.$this->decoded['cid_0'].'),';
					$logString .= 'lv.'.$this->decoded['lev_0'].'\n';
				}
				if ( $this->decoded['refid_1'] != null && $this->decoded['refid_1'] != '' && $this->decoded['refid_1'] != '0' )
				{
					$logString .= '\t출격기체2 => +'.$this->decoded['up_grade_1'].' '.$this->decoded['refid_1'].'('.$this->decoded['cid_1'].'), ';
					$logString .= 'lv.'.$this->decoded['lev_1'].'\n';
				}
				if ( $this->decoded['refid_2'] != null && $this->decoded['refid_2'] != '' && $this->decoded['refid_2'] != '0' )
				{
					$logString .= '\t출격기체3 => +'.$this->decoded['up_grade_2'].' '.$this->decoded['refid_2'].'('.$this->decoded['cid_2'].'), ';
					$logString .= 'lv.'.$this->decoded['lev_2'].'\n';
				}

				$itemLog = '';
				foreach( $instant_item as $row )
				{
					if ( $row != null && $row != '' && $row != '0' )
					{
						if ( !empty($arrItem) )
						{
							if ( $itemLog != '' )
							{
								$itemLog .= ', ';
							}
							$itemLog .= $row;
						}
					}
				}

				$logString .= $itemLog;
				if ( $itemLog != '' )
				{
					$logString .= ' 사용';
				}
				$logString .= '\n캐시 사용여부 : ';
				if ( $use_cash )
				{
					$logString .= MY_Controller::ADDITIONAL_MODE_SURVIVAL_PRICE.' 사용';
				}
				else
				{
					$logString .= '미사용';
				}
				$logString .= '\nSURVIVAL_에너지 잔여량 '.$arrayResult['remain_item']['survival_points'];
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingResultStepForSURVIVAL()
	{
		$pid = $this->decoded['pid'];
		$logid = $this->decoded['logid'];
		$score = $this->decoded['score'];
		$game_points = $this->decoded['game_points'];
		$round = $this->decoded['round'];

		if( $pid && $logid )
		{
			// 로그 추가 완료
			$result = $this->dbRecord->updateLoggingResultStepForSURVIVAL( $pid, $logid, $score, $round );

			// 랭킹정보 추가
			$this->dbRank->requestUpdateSURVIVALRank( $pid, $score );
			if ( $result )
			{
				$this->calcurateEnergy( $pid );
				$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, MY_Controller::COMMON_SURVIVAL_REWARD_TYPE, $game_points, 'SURVIVAL보상' );
				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				// 로그 문자열 만들기 시작
				$logString = '생존 종료\n';
				$logString .= '획득점수 : '.$score;
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_SUR_SAVEINFO;
				$resultText = MY_Controller::MESSAGE_SUR_SAVEINFO;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestInitDelayForPVP()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$this->dbPlay->onBeginTransaction();
			$result = ( $this->dbPlay->requestInitDelayForPVP( $pid ) == 1 ? true : false );
			$result = $result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, MY_Controller::PVP_DELAY_INIT_ASSET_TYPE, MY_Controller::PVP_DELAY_INIT_ASSET_VALUE, 'PVP 쿨타임 초기화' );
			if ( $result )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = array( 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
			} else {
				$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
				$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
				$arrayResult = null;
			}
			$this->dbPlay->onEndTransaction( $result );
		} else {
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingStartStepForPVP()
	{
		$pid = $this->decoded['pid'];
		$use_cash = $this->decoded['use_cash'];
		$instant_item[0] = $this->decoded['instant_item1'];
		$instant_item[1] = $this->decoded['instant_item2'];
		$instant_item[2] = $this->decoded['instant_item3'];
		$instant_item[3] = $this->decoded['instant_item4'];
		$tactInfo = $this->decoded['tactinfo'];
		if ( $instant_item[0] )
		{
			$instant_itemName[] = $instant_item[0];
		}
		if ( $instant_item[1] )
		{
			$instant_itemName[] = $instant_item[1];
		}
		if ( $instant_item[2] )
		{
			$instant_itemName[] = $instant_item[2];
		}
		if ( $instant_item[3] )
		{
			$instant_itemName[] = $instant_item[3];
		}

		if ( $pid )
		{
			/* 랭킹점수 기준의 매칭 방식 시작 */
			//화요일 12시 이전이면
			if ( date('H') < 13 )
			{
				$enemy_info = $this->dbPlay->requestEnemyForPVP( $pid )->result_array();
			}
			else
			{
				$player_score = $this->dbRank->requestPVPScore( $pid )->result_array();
				if ( empty($player_score) )
				{
					$player_score = 0;
					$player_rank = 0;
				}

				$player_rank = $player_score[0]['rank'];
				$player_score = $player_score[0]['score'];
				$limit_low = floor($player_score / MY_Controller::PVP_SCORE_DEVIDE_CONST) * MY_Controller::PVP_SCORE_DEVIDE_CONST;
				if ( $limit_low >= MY_Controller::PVP_SCORE_LAST_GROUP )
				{
					$limit_high = 1000000;
				}
				else
				{
					$limit_high = $limit_low + MY_Controller::PVP_SCORE_DEVIDE_CONST - 1;
				}
				$enemies = $this->dbPlay->requestEnemyForPVPWithRangeCount( $pid, $limit_low, $limit_high );
				if ( $enemies < 5 )
				{
					if ( $this->dbPlay->requestEnemyForPVPCount( $pid ) < 10 )
					{
						$enemy_info = $this->dbPlay->requestEnemyForPVP( $pid )->result_array();
					}
					else if ( $player_rank < 5 )
					{
						$rank_high = 10;
						$rank_low = 1;
						$enemy_info = $this->dbPlay->requestEnemyForPVPWithRank( $pid, $rank_low, $rank_high )->result_array();
					}
					else
					{
						$rank_high = $player_rank + 5;
						$rank_low = $player_rank - 5;
						$enemy_info = $this->dbPlay->requestEnemyForPVPWithRank( $pid, $rank_low, $rank_high )->result_array();
					}
				}
				else
				{
					$enemy_info = $this->dbPlay->requestEnemyForPVPWithRange( $pid, $limit_low, $limit_high )->result_array();
				}
			}

			/* 랭킹점수 기준의 매칭 방식 끝 */
			//$enemy_info = $this->dbPlay->requestEnemyForPVP( $pid )->result_array();
			if ( empty($enemy_info) )
			{
				$resultCode = MY_Controller::STATUS_LOAD_RIVAL;
				$resultText = MY_Controller::MESSAGE_LOAD_RIVAL;
				$arrayResult = null;
			}
			else
			{
				if ( $enemy_info[0]['pid'] == '' || $enemy_info[0]['pid'] == null )
				{
					$resultCode = MY_Controller::STATUS_LOAD_RIVAL;
					$resultText = MY_Controller::MESSAGE_LOAD_RIVAL;
					$arrayResult = null;
				}
				else
				{
					$enemyPid = $enemy_info[0]['pid'];
					if ( $enemy_info[0]['show_prof'] )
					{
						$enemyProfImg = $enemy_info[0]['prof_img'];
					}
					else
					{
						$enemyProfImg = '';
					}
					$enemy_info = array_merge($enemy_info, $this->dbPlay->requestPVPEquipment( $enemyPid )->result_array()[0]);
					unset($enemy_info[0]);

					$enemy_info['pid'] = $enemyPid;
					$enemy_info['prof_img'] = $enemyProfImg;
					$enemy_info['charInfo'] = $this->dbPlay->requestPVPTeam( $enemyPid )->result_array();
					if ( !(empty($enemy_info['charInfo'][0])) )
					{
						$enemy_info['tact_0'] = $enemy_info['charInfo'][0]['tact'];
					}
					if ( !(empty($enemy_info['charInfo'][1])) )
					{
						$enemy_info['tact_1'] = $enemy_info['charInfo'][1]['tact'];
					}
					if ( !(empty($enemy_info['charInfo'][2])) )
					{
						$enemy_info['tact_2'] = $enemy_info['charInfo'][2]['tact'];
					}
					unset($enemy_info['charInfo'][0]['tact']);
					unset($enemy_info['charInfo'][1]['tact']);
					unset($enemy_info['charInfo'][2]['tact']);

					$this->dbPlay->onBeginTransaction();
					$this->dbRecord->onBeginTransaction();
					$this->dbPlay->requestUpdateTact( $pid, $tactInfo );
					$result = $this->dbRecord->requestLoggingStartPVP( $pid, $enemyPid, $use_cash, $instant_item[0], $instant_item[1], $instant_item[2], $instant_item[3] );
					if ( $result )
					{
						$result2 = $result;

						$usePoint = $this->dbRef->requestPriceProduct( $pid, $instant_item )->result_array();

						if ( !(empty($usePoint)) )
						{
							foreach( $usePoint as $row )
							{
								//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
								//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
								//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
								//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
								//$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $row['payment_type'], $row['payment'], 'PVP아이템 사용('.implode(',', $instant_itemName).')' );
								$result = (bool)1;
							}
						}
						else
						{
							$result = (bool)1;
						}

						if ( $result )
						{
							//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
							//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
							//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
							//임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시임시
							//$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'PVP_POINTS', 1, 'PVP 출격 에너지 사용' );
							$result = (bool)1;
							if ( $result )
							{
								$resultCode = MY_Controller::STATUS_API_OK;
								$resultText = MY_Controller::MESSAGE_API_OK;
								$arrayResult = array( 'logid' => (string)$result2, 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0], 'enemy_info' => $enemy_info );
							}
							else
							{
								$resultCode = MY_Controller::STATUS_PVP_LACK_PVBPOINT;
								$resultText = MY_Controller::MESSAGE_PVP_LACK_PVBPOINT;
								$arrayResult = null;
							}
						}
						else
						{
							$resultCode = MY_Controller::STATUS_PVP_LACK_GOLD;
							$resultText = MY_Controller::MESSAGE_PVP_LACK_GOLD;
							$arrayResult = null;
						}
					}
					else
					{
						$resultCode = MY_Controller::STATUS_PVP_START;
						$resultText = MY_Controller::MESSAGE_PVP_START;
						$arrayResult = null;
					}

					$this->dbRecord->onEndTransaction( $result );
					$this->dbPlay->onEndTransaction( $result );

					if ( $result )
					{
						// 로그 문자열 만들기 시작
						$logString = '1:1대전 시작\n';
						$logString .= '진행팀 정보 : \n\t아이디 => '.$pid.'\n';
						if ( array_key_exists('op', $this->decoded) )
						{
							if ( $this->decoded['op'] != null && $this->decoded['op'] != '' && $this->decoded['op'] != '0' )
							{
								$logString .= '\t오퍼레이터 => '.$this->decoded['op'].'\n';
							}
						}
						if ( array_key_exists('pilot_0', $this->decoded) )
						{
							if ( $this->decoded['pilot_0'] != null && $this->decoded['pilot_0'] != '' && $this->decoded['pilot_0'] != '0' )
							{
								$logString .= '\t파일럿1 => '.$this->decoded['pilot_0'].'\n';
							}
						}
						if ( array_key_exists('pilot_1', $this->decoded) )
						{
							if ( $this->decoded['pilot_1'] != null && $this->decoded['pilot_1'] != '' && $this->decoded['pilot_1'] != '0' )
							{
								$logString .= '\t파일럿2 => '.$this->decoded['pilot_1'].'\n';
							}
						}
						if ( array_key_exists('pilot_2', $this->decoded) )
						{
							if ( $this->decoded['pilot_2'] != null && $this->decoded['pilot_2'] != '' && $this->decoded['pilot_2'] != '0' )
							{
								$logString .= '\t파일럿3 => '.$this->decoded['pilot_2'].'\n';
							}
						}
						if ( array_key_exists('refid_0', $this->decoded) )
						{
							if ( $this->decoded['refid_0'] != null && $this->decoded['refid_0'] != '' && $this->decoded['refid_0'] != '0' )
							{
								$logString .= '\t출격기체1 => +'.$this->decoded['up_grade_0'].' '.$this->decoded['refid_0'].'('.$this->decoded['cid_0'].'),';
								$logString .= 'lv.'.$this->decoded['lev_0'].', 전략'.$this->decoded['tact_0'].'\n';
							}
						}
						if ( array_key_exists('refid_1', $this->decoded) )
						{
							if ( $this->decoded['refid_1'] != null && $this->decoded['refid_1'] != '' && $this->decoded['refid_1'] != '0' )
							{
								$logString .= '\t출격기체2 => +'.$this->decoded['up_grade_1'].' '.$this->decoded['refid_1'].'('.$this->decoded['cid_1'].'), ';
								$logString .= 'lv.'.$this->decoded['lev_1'].', 전략'.$this->decoded['tact_1'].'\n';
							}
						}
						if ( array_key_exists('refid_2', $this->decoded) )
						{
							if ( $this->decoded['refid_2'] != null && $this->decoded['refid_2'] != '' && $this->decoded['refid_2'] != '0' )
							{
								$logString .= '\t출격기체3 => +'.$this->decoded['up_grade_2'].' '.$this->decoded['refid_2'].'('.$this->decoded['cid_2'].'), ';
								$logString .= 'lv.'.$this->decoded['lev_2'].', 전략'.$this->decoded['tact_2'].'\n';
							}
						}

						$logString .= '상대팀 정보 : \n\t아이디 => '.$enemy_info['pid'].'\n';
						if ( $enemy_info['op'] != null && $enemy_info['op'] != '' && $enemy_info['op'] != '0' )
						{
							$logString .= '\t오퍼레이터 => '.$enemy_info['op'].'\n';
						}
						if ( $enemy_info['pilot_0'] != null && $enemy_info['pilot_0'] != '' && $enemy_info['pilot_0'] != '0' )
						{
							$logString .= '\t파일럿1 => '.$enemy_info['pilot_0'].'\n';
						}
						if ( $enemy_info['pilot_1'] != null && $enemy_info['pilot_1'] != '' && $enemy_info['pilot_1'] != '0' )
						{
							$logString .= '\t파일럿2 => '.$enemy_info['pilot_1'].'\n';
						}
						if ( $enemy_info['pilot_2'] != null && $enemy_info['pilot_2'] != '' && $enemy_info['pilot_2'] != '0' )
						{
							$logString .= '\t파일럿3 => '.$enemy_info['pilot_2'].'\n';
						}
						if ( count( $enemy_info['charInfo'] ) >= 1 )
						{
							if ( $enemy_info['charInfo'][0]['refid'] != null && $enemy_info['charInfo'][0]['refid'] != '' && $enemy_info['charInfo'][0]['refid'] != '0' )
							{
								$logString .= '\t출격기체1 => +'.$enemy_info['charInfo'][0]['up_grade'].' '.$enemy_info['charInfo'][0]['refid'].'('.$enemy_info['charInfo'][0]['idx'].'),';
								$logString .= 'lv.'.$enemy_info['charInfo'][0]['level'].', 전략'.$enemy_info['tact_0'].'\n';
							}
						}
						if ( count( $enemy_info['charInfo'] ) >= 2 )
						{
							if ( $enemy_info['charInfo'][1]['refid'] != null && $enemy_info['charInfo'][1]['refid'] != '' && $enemy_info['charInfo'][1]['refid'] != '0' )
							{
								$logString .= '\t출격기체2 => +'.$enemy_info['charInfo'][1]['up_grade'].' '.$enemy_info['charInfo'][1]['refid'].'('.$enemy_info['charInfo'][1]['idx'].'), ';
								$logString .= 'lv.'.$enemy_info['charInfo'][1]['level'].', 전략'.$enemy_info['tact_1'].'\n';
							}
						}
						if ( count( $enemy_info['charInfo'] ) >= 3 )
						{
							if ( $enemy_info['charInfo'][2]['refid'] != null && $enemy_info['charInfo'][2]['refid'] != '' && $enemy_info['charInfo'][2]['refid'] != '0' )
							{
								$logString .= '\t출격기체3 => +'.$enemy_info['charInfo'][2]['up_grade'].' '.$enemy_info['charInfo'][2]['refid'].'('.$enemy_info['charInfo'][2]['idx'].'), ';
								$logString .= 'lv.'.$enemy_info['charInfo'][2]['level'].', 전략'.$enemy_info['tact_2'].'\n';
							}
						}

						$itemLog = '';
						foreach( $instant_item as $row )
						{
							if ( $row != null && $row != '' && $row != '0' )
							{
								if ( !empty($arrItem) )
								{
									if ( $itemLog != '' )
									{
										$itemLog .= ', ';
									}
									$itemLog .= $row;
								}
							}
						}

						if ( $itemLog != '' )
						{
							$logString .= $itemLog.' 사용\n';
						}
						$logString .= '캐시 사용여부 : ';
						if ( $use_cash )
						{
							$logString .= MY_Controller::ADDITIONAL_MODE_PVP_PRICE.' 사용';
						}
						else
						{
							$logString .= '미사용';
						}
						$logString .= '\nPVP_에너지 잔여량 '.$arrayResult['remain_item']['pvp_points'];
						// 로그 문자열 만들기 끝
						$this->onSysLogWriteDb( $pid, $logString );
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestLoggingResultStepForPVP()
	{
		$pid = $this->decoded['pid'];
		$logid = $this->decoded['logid'];
		$is_clear = $this->decoded['is_clear'];
		$duration = $this->decoded['duration'];
		$remain_home = $this->decoded['remain_home'];
		$killed_away = $this->decoded['killed_away'];
		$pvp = $this->decoded['pvp'];
		if ( $duration == null || $duration == '' )
		{
			$duration = 0;
		}
		if ( $duration > 180)
		{
			$duration = 180;
		}
		if ( $duration < 0 )
		{
			$duration = 0;
		}

		if ( $remain_home == null || $remain_home == '' )
		{
			$remain_home = 0;
		}

		if ( $killed_away == null || $killed_away == '' )
		{
			$killed_away = 0;
		}

		if ( $pvp != null && $pvp != '' )
		{
			$pvp = 1 + floatval($pvp);
		}
		else
		{
			$pvp = 1;
		}

		if( $pid && $logid )
		{
			if ( $is_clear == 1 )
			{
				if ( $remain_home > 3 )
				{
					$remain_home = 3;
				}
				$score = floor( ( MY_Controller::PVP_POINT_BASIC_WIN + ( $remain_home * MY_Controller::PVP_POINT_USER_MULTIPLE ) - floor($duration / MY_Controller::PVP_POINT_TIME_DENOMINATOR ) ) * $pvp );
				$strLogText = '승리';
			}
			else
			{
				if ( $killed_away > 3 )
				{
					$killed_away = 3;
				}
				$score = floor( ( MY_Controller::PVP_POINT_BASIC_LOSE + ( $killed_away * MY_Controller::PVP_POINT_USER_MULTIPLE ) + floor($duration / MY_Controller::PVP_POINT_TIME_DENOMINATOR) ) * $pvp );
				$strLogText = '패배';
			}
			// 로그 추가 완료
			if ( (bool)$this->dbRecord->requestLoggingResultStepForPVP( $pid, $logid ) )
			{
				$result = $this->dbRecord->updateLoggingResultStepForPVP( $pid, $logid, $score, $is_clear );
			}
			else
			{
				$result = (bool)0;
			}
			if ( $result )
			{
				// 랭킹정보 추가
				$result = $result & (bool)$this->dbRank->requestUpdatePVPRank( $pid, $score );
			}

			if ( $result )
			{
				$this->calcurateEnergy( $pid );
				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$arrayResult['score'] = $this->dbRank->requestPVPScore( $pid )->result_array()[0]['score'];
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;

				// 로그 문자열 만들기 시작
				$logString = '1:1대전 종료\n';
				$logString .= '결과 : '.$strLogText.'\n';
				$logString .= '획득점수 : '.$score.'\n';
				$logString .= '현재점수 : '.$arrayResult['score'].'\n';
				$logResult['series_info'] = $this->dbRecord->requestSeriesInfo( $pid )->result_array()[0];
				$logString .= '연승횟수 : '.$logResult['series_info']['serial_win'].'\n';

				$this->onSysLogWriteDb( $pid, $logString );
				// 로그 문자열 만들기 끝
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PVP_SAVEINFO;
				$resultText = MY_Controller::MESSAGE_PVP_SAVEINFO;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUpgradeCharacter()
	{
		$pid = $this->decoded['pid'];
		$targetIdx = $this->decoded['targetIdx'];
		$sourceIdx = $this->decoded['sourceIdx'];
		$arrChar = array( $targetIdx, $sourceIdx );

		if( $pid && $targetIdx && $sourceIdx )
		{
			if ( !($this->dbPlay->requestCharacterExists( $pid, $targetIdx ) && $this->dbPlay->requestCharactersExistForSales( $pid, $sourceIdx )) )
			{
				$resultCode = MY_Controller::STATUS_UPGRADE_NON_CHARACTER;
				$resultText = MY_Controller::MESSAGE_UPGRADE_NON_CHARACTER;
				$arrayResult = null;
			}
			else
			{
				//강화 비용처리
				$arrPayment = $this->dbRef->requestUpgradeInfo( $pid, $targetIdx, $sourceIdx )->result_array();
				if ( !empty( $arrPayment ) )
				{
					$this->load->model('admin/Model_Admin', 'dbAdmin');
					$arrDisInfo = $this->dbAdmin->requestValidDisEventList( MY_Controller::UPGRADE_DIS_EVENT_PRODUCT, MY_Controller::UPGRADE_DIS_EVENT_PRODUCT )->result_array();

					$this->dbPlay->onBeginTransaction();
					if ( empty( $arrDisInfo ) )
					{
						$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrPayment[0]['payment_type'], $arrPayment[0]['payment'], '캐릭터 강화' );
					}
					else
					{
						if ( $arrDisInfo[0]['evt_paytype'] == 'DIS' )
						{
							$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrPayment[0]['payment_type'], floor( $arrPayment[0]['payment'] * (100 - $arrDisInfo[0]['evt_value']) / 100 ), '캐릭터 강화' );
						}
						else
						{
							$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrDisInfo[0]['evt_paytype'], $arrDisInfo[0]['evt_value'], '캐릭터 강화' );
						}
					}

					if ( $result )
					{
						if ( doubleval($arrPayment[0]['probability']) + doubleval($arrPayment[0]['up_incentive']) >= doubleval(rand()) / doubleval(getrandmax()) )
						{
							$reference = $arrPayment[0]['reference'];
							$up_incentive = 0;
							$is_upgrade = (bool)1;
						}
						else
						{
							$up_incentive = $arrPayment[0]['incentive'];
							$reference = null;
							$is_upgrade = (bool)0;
						}
						$this->dbPlay->requestUpdateUpgradeInfo( $pid, $targetIdx, $arrPayment[0]['step'] + intval($is_upgrade), $reference, $up_incentive );
						if ( $arrPayment[0]['weapon'] != null && $arrPayment[0]['weapon'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['weapon'] );
						}
						if ( $arrPayment[0]['backpack'] != null && $arrPayment[0]['backpack'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['backpack'] );
						}
						if ( $arrPayment[0]['skill_0'] != null && $arrPayment[0]['skill_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['skill_0'] );
						}
						if ( $arrPayment[0]['skill_1'] != null && $arrPayment[0]['skill_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['skill_1'] );
						}
						if ( $arrPayment[0]['skill_2'] != null && $arrPayment[0]['skill_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['skill_2'] );
						}
						if ( $arrPayment[0]['gear_0'] != null && $arrPayment[0]['gear_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_0'] );
						}
						if ( $arrPayment[0]['gear_1'] != null && $arrPayment[0]['gear_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_1'] );
						}
						if ( $arrPayment[0]['gear_2'] != null && $arrPayment[0]['gear_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_2'] );
						}
						if ( $arrPayment[0]['gear_3'] != null && $arrPayment[0]['gear_3'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_3'] );
						}
						if ( $arrPayment[0]['gear_4'] != null && $arrPayment[0]['gear_4'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_4'] );
						}
						if ( $arrPayment[0]['gear_5'] != null && $arrPayment[0]['gear_5'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $arrPayment[0]['gear_5'] );
						}
						$result = (bool)$result & (bool)$this->dbPlay->deletePlayerCharacter( $pid, $sourceIdx );

						$this->calcurateEnergy( $pid );
						$this->dbPlay->onEndTransaction( $result );

						if ( $result )
						{
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult = array( 'charInfo' => $this->dbPlay->requestCharacterResult( $pid, $targetIdx )->result_array()[0], 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
							$arrayResult['charInfo']['is_upgrade'] = (string)intval($is_upgrade);
							$this->onSysLogWriteDb( $pid, '강화, 강화메인기체 '.$targetIdx.', 소멸한 재료기체 '.$sourceIdx.', 확률 '.(doubleval($arrPayment[0]['probability']) + doubleval($arrPayment[0]['up_incentive'])).', 소모골드'.$arrPayment[0]['payment_type'].', '.$arrPayment[0]['payment'].', 성공여부 '.$is_upgrade.', 강화레벨변화 수치 '.$arrPayment[0]['step'].'=>'.($arrPayment[0]['step'] + $is_upgrade) );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_UPGRADE_RESULT_CHARACTER;
							$resultText = MY_Controller::MESSAGE_UPGRADE_RESULT_CHARACTER;
							$arrayResult = null;
						}
					}
					else
					{
						$resultCode = MY_Controller::STATUS_UPGRADE_RACK_COST;
						$resultText = MY_Controller::MESSAGE_UPGRADE_RACK_COST;
						$arrayResult = null;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_UPGRADE_LOAD_COST;
					$resultText = MY_Controller::MESSAGE_UPGRADE_LOAD_COST;
					$arrayResult = null;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUpgradeItem()
	{
		$pid = $this->decoded['pid'];
		$category = $this->decoded['category'];
		$itemType = $this->decoded['itemType'];
		$targetIdx = $this->decoded['targetIdx'];
		$sourceIdx = $this->decoded['sourceIdx'];

		if( $pid && $category && $itemType && $targetIdx && $sourceIdx )
		{
			if ( $this->dbPlay->requestItemExists( $pid, $targetIdx ) && ( count( $sourceIdx ) == $this->dbPlay->requestItemExistsForSales( $pid, $category, $sourceIdx ) ) == false )
			{
				$resultCode = MY_Controller::STATUS_UPGRADE_NON_ITEM;
				$resultText = MY_Controller::MESSAGE_UPGRADE_NON_ITEM;
				$arrayResult = null;
			}
			else
			{
				//강화 비용처리
				$arrPayment = $this->dbRef->requestUpgradeItemInfo( $pid, $category, $sourceIdx )->result_array();
				if ( empty( $arrPayment ) == false )
				{
					$this->load->model('admin/Model_Admin', 'dbAdmin');
					$arrDisInfo = $this->dbAdmin->requestValidDisEventList( MY_Controller::UPGRADE_DIS_EVENT_PRODUCT, MY_Controller::UPGRADE_DIS_EVENT_ITEM_PRODUCT )->result_array();

					if ( empty( $arrDisInfo ) )
					{
						$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrPayment[0]['payment_type'], $arrPayment[0]['payment_value'], '아이템 강화' );
					}
					else
					{
						if ( $arrDisInfo[0]['evt_paytype'] == 'DIS' )
						{
							$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrPayment[0]['payment_type'], floor( $arrPayment[0]['payment_value'] * (100 - $arrDisInfo[0]['evt_value']) / 100 ), '아이템 강화' );
						}
						else
						{
							$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $arrDisInfo[0]['evt_paytype'], $arrDisInfo[0]['evt_value'], '아이템 강화' );
						}
					}

					if ( $result )
					{
						$targetItem = $this->dbPlay->requestItemLevExp( $pid, $targetIdx )->result_array();
						if ( empty( $targetItem ) )
						{
							$resultCode = MY_Controller::STATUS_UPGRADE_NON_ITEM;
							$resultText = MY_Controller::MESSAGE_UPGRADE_NON_ITEM;
							$arrayResult = null;
						}
						else
						{
							if ( $this->dbPlay->deleteInventoryArray( $pid, $sourceIdx ) )
							{
								$resultCode = MY_Controller::STATUS_API_OK;
								$resultText = MY_Controller::MESSAGE_API_OK;
								$exp = $arrPayment[0]['exp'] + $targetItem[0]['up_exp'];
								$resultArray = $this->dbRef->requestExpInfo( $pid, $category, $itemType, $targetItem[0]['grade'], $exp )->result_array();
								$this->dbPlay->updateItem( $pid, $targetIdx, $resultArray[0]['level'], $exp, $resultArray[0]['reference'] );
								$arrayResult = array( 'itemInfo' => $this->dbPlay->requestItemResult( $pid, $targetIdx )->result_array()[0], 'remain_item' => $this->dbPlay->requestItem( $pid )->result_array()[0] );
								$this->onSysLogWriteDb( $pid, '강화, 강화메인아이템 '.$targetIdx.', 소멸한 재료아이템 '.join( ',', $sourceIdx ).', 소모골드'.$arrPayment[0]['payment_type'].', '.$arrPayment[0]['payment_value'].', 강화경험치변화 수치 '.$targetItem[0]['up_grade'].':'.$targetItem[0]['up_exp'].' => '.$resultArray[0]['level'].':'.$exp );
							}
							else
							{
								$resultCode = MY_Controller::STATUS_UPGRADE_RESULT_ITEM;
								$resultText = MY_Controller::MESSAGE_UPGRADE_RESULT_ITEM;
								$arrayResult = null;
							}
						}
					}
					else
					{
						$resultCode = MY_Controller::STATUS_UPGRADE_ITEM_RACK_COST;
						$resultText = MY_Controller::MESSAGE_UPGRADE_ITEM_RACK_COST;
						$arrayResult = null;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_UPGRADE_ITEM_LOAD_COST;
					$resultText = MY_Controller::MESSAGE_UPGRADE_ITEM_LOAD_COST;
					$arrayResult = null;
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestSynthesize()
	{
		$pid = $this->decoded['pid'];
		$targetIdx = $this->decoded['targetIdx'];
		$sourceIdx = $this->decoded['sourceIdx'];
		$arrChar = array( $targetIdx, $sourceIdx );

		if( $pid && $targetIdx && $sourceIdx )
		{
			$arrayResult = $this->dbPlay->requestCharactersSynthesize( $pid, array_unique($arrChar, SORT_LOCALE_STRING) )->result_array();
			$bTeamInfo = $this->dbPlay->requestTeam( $pid )->result_array();
			foreach( $bTeamInfo as $row )
			{
				if ( $row['team_seq'] == 0 )
				{
					$aTeamInfo['00'] = $row['memb_0'];
					$aTeamInfo['01'] = $row['memb_1'];
					$aTeamInfo['02'] = $row['memb_2'];
				}
				else if ( $row['team_seq'] == 1 )
				{
					$aTeamInfo['10'] = $row['memb_0'];
					$aTeamInfo['11'] = $row['memb_1'];
					$aTeamInfo['12'] = $row['memb_2'];
				}
				else if ( $row['team_seq'] == 2 )
				{
					$aTeamInfo['20'] = $row['memb_0'];
					$aTeamInfo['21'] = $row['memb_1'];
					$aTeamInfo['22'] = $row['memb_2'];
				}
			}

			if ( count($arrayResult) != count(array_unique($arrChar, SORT_LOCALE_STRING)) )
			{
				$resultCode = MY_Controller::STATUS_SYNTHESIZE_NON_CHARACTER;
				$resultText = MY_Controller::MESSAGE_SYNTHESIZE_NON_CHARACTER;
				$arrayResult = null;
			}
			else
			{
				if ( $arrayResult[0]['grade'] != $arrayResult[1]['grade'] )
				{
					$resultCode = MY_Controller::STATUS_SYNTHESIZE_ERROR_GARADE;
					$resultText = MY_Controller::MESSAGE_SYNTHESIZE_ERROR_GARADE;
					$arrayResult = null;
				}
				else
				{
					$gatchaValue = json_decode(MY_Controller::GATCHA_BY_GRADE, true)[$arrayResult[0]['grade'] - 1];
					// 합성은 비용 없음 추후 생길 경우 아래 주석 해제
					//$gatchaPayment_type = json_decode(MY_Controller::GATCHA_BY_GRADE_PAYMENT_TYPE, true)[$arrayResult[0]['grade']];
					//$gatchaPayment = MY_Controller::GATCHA_BY_GRADE_PAYMENT_TYPE;
					$gatchaPayment = '0';
					$gatchaPayment_type = '0';

					$this->dbPlay->onBeginTransaction();

					// 합성은 비용 없음 추후 생길 경우 아래 주석 해제
					//$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $gatchaPayment_type, $gatchaPayment );
					$arrayProduct = $this->dbRef->requestArticleInfo($gatchaValue)->result_array();

					//공통처리
					$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '캐릭터 합성('.$targetIdx.', '.$sourceIdx.')' );
					$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
					unset( $arrayResult['rewardobject'] );

					// 팀정보 업데이트
					$is_change = false;
					foreach( $aTeamInfo as $key => $val )
					{
						if ( $val == $targetIdx )
						{
							$aTeamInfo[$key] = $arrayResult['character'][0]['idx'];
							$is_change = true;
						}
						else if ( $val == $sourceIdx )
						{
							$aTeamInfo[$key] = 0;
							$is_change = true;
						}
						else if ( $val == null )
						{
							$aTeamInfo[$key] = 0;
						}
					}
					for ( $i = 0; $i < 3; $i++ )
					{
						if ( $aTeamInfo[$i.'0'] == 0 )
						{
							$aTeamInfo[$i.'0'] = $arrayResult['character'][0]['idx'];
							if ( $aTeamInfo[$i.'1'] == $arrayResult['character'][0]['idx'] )
							{
								$aTeamInfo[$i.'1'] = 0;
							}
							else if ( $aTeamInfo[$i.'2'] == ['character'][0]['idx'] )
							{
								$aTeamInfo[$i.'2'] = 0;
							}
						}
					}

					if ( $is_change )
					{
						$result = $result & (bool)$this->dbPlay->requestDeployCharacters( $pid, $aTeamInfo );
					}

					$arrChar = $this->dbPlay->requestSynthesizeCharInfo( $pid, $sourceIdx, $targetIdx )->result_array();
					foreach ( $arrChar as $row )
					{
						if ( $row['weapon'] != null && $row['weapon'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['weapon'] );
						}
						if ( $row['backpack'] != null && $row['backpack'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['backpack'] );
						}
						if ( $row['skill_0'] != null && $row['skill_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_0'] );
						}
						if ( $row['skill_1'] != null && $row['skill_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_1'] );
						}
						if ( $row['skill_2'] != null && $row['skill_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_2'] );
						}
						if ( $row['gear_0'] != null && $row['gear_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_0'] );
						}
						if ( $row['gear_1'] != null && $row['gear_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_1'] );
						}
						if ( $row['gear_2'] != null && $row['gear_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_2'] );
						}
						if ( $row['gear_3'] != null && $row['gear_3'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_3'] );
						}
						if ( $row['gear_4'] != null && $row['gear_4'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_4'] );
						}
						if ( $row['gear_5'] != null && $row['gear_5'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_5'] );
						}
					}

					$result = (bool)$this->dbPlay->deletePlayerCharacter( $pid, $sourceIdx );
					$result = (bool)$result & (bool)$this->dbPlay->deletePlayerCharacter( $pid, $targetIdx );

					$this->dbPlay->onEndTransaction( $result );

					if ( $result )
					{
						$this->calcurateEnergy( $pid );
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$arrayResult['team'] = $this->dbPlay->requestTeam( $pid )->result_array();
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$this->onSysLogWriteDb( $pid, '합성, 재료기체 '.$targetIdx.', '.$sourceIdx.', 소모비용 '.$gatchaPayment_type.', '.$gatchaPayment.', 획득기체 '.$arrayResult['character'][0]['idx'].', '.$arrayResult['character'][0]['refid'] );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_SYNTHESIZE_FAIL;
						$resultText = MY_Controller::MESSAGE_SYNTHESIZE_FAIL;
						$arrayResult = null;
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestSynthesizeItem()
	{
		$pid = $this->decoded['pid'];
		$itemType = $this->decoded['itemType'];
		$grade = $this->decoded['grade'];
		$targetIdx = $this->decoded['targetIdx'];
		$sourceIdx = $this->decoded['sourceIdx'];
		$arrItem = array( $targetIdx, $sourceIdx );

		if( $pid && $targetIdx && $sourceIdx )
		{
			$arrayResult = $this->dbPlay->requestItemsSynthesize( $pid, array_unique($arrItem, SORT_LOCALE_STRING), $itemType , json_decode( MY_Controller::ITEM_MAXLEV_BY_GRADE, true)[$grade] )->result_array();

			if ( count($arrayResult) != count(array_unique($arrItem, SORT_LOCALE_STRING)) )
			{
				$resultCode = MY_Controller::STATUS_SYNTHESIZE_NON_ITEM;
				$resultText = MY_Controller::MESSAGE_SYNTHESIZE_NON_ITEM;
				$arrayResult = null;
			}
			else
			{
				if ( $arrayResult[0]['grade'] != $arrayResult[1]['grade'] )
				{
					$resultCode = MY_Controller::STATUS_SYNTHESIZE_ERROR_ITEM_GARADE;
					$resultText = MY_Controller::MESSAGE_SYNTHESIZE_ERROR_ITEM_GARADE;
					$arrayResult = null;
				}
				else
				{
					if ( strtolower( $itemType ) == 'weapon' )
					{
						$gatchaValue = json_decode(MY_Controller::GATCHA_BY_WEPN_GRADE, true)[$grade - 1];
					}
					else if ( strtolower( $itemType ) == 'backpack' )
					{
						$gatchaValue = json_decode(MY_Controller::GATCHA_BY_BCPC_GRADE, true)[$grade - 1];
					}
					else if ( strtolower( $itemType ) == 'technique' )
					{
						$gatchaValue = json_decode(MY_Controller::GATCHA_BY_SKIL_GRADE, true)[$grade - 1];
					}

					// 합성은 비용 없음 추후 생길 경우 아래 주석 해제 - 비용이 발생하여 주석 위치 변경
					$gatchaPayment = json_decode(MY_Controller::GATCHA_BY_ITEM_GRADE_PAYMENT, true)[$arrayResult[0]['grade']];
					$gatchaPayment_type = MY_Controller::GATCHA_BY_ITEM_GRADE_PAYMENT_TYPE;

					$this->dbPlay->onBeginTransaction();

					// 합성은 비용 없음 추후 생길 경우 아래 주석 해제 - 비용이 발생하여 주석 제거
					$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $gatchaPayment_type, $gatchaPayment, '아이템 합성하기' );
					if ( $result )
					{
						$arrayProduct = $this->dbRef->requestArticleInfo($gatchaValue)->result_array();

						//공통처리
						$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '아이템 합성('.$targetIdx.', '.$sourceIdx.')' );
						$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
						unset( $arrayResult['rewardobject'] );
						$result = (bool)$this->dbPlay->deletePlayerItem( $pid, $sourceIdx );
						$result = (bool)$result & (bool)$this->dbPlay->deletePlayerItem( $pid, $targetIdx );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_SYNTHESIZE_ITEM_RACK_COST;
						$resultText = MY_Controller::MESSAGE_SYNTHESIZE_ITEM_RACK_COST;
						$arrayResult = null;
					}
					$this->dbPlay->onEndTransaction( $result );
					if ( $result )
					{
						$this->calcurateEnergy( $pid );
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$this->onSysLogWriteDb( $pid, '합성, 재료아이템 '.$targetIdx.', '.$sourceIdx.', 소모비용 '.$gatchaPayment_type.', '.$gatchaPayment.', 획득아이템 '.$arrayResult['inventory'][0]['idx'].', '.$arrayResult['inventory'][0]['refid'] );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_SYNTHESIZE_ITEM_FAIL;
						$resultText = MY_Controller::MESSAGE_SYNTHESIZE_ITEM_FAIL;
						$arrayResult = null;
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestSynthesizeItemRetry()
	{
		$pid = $this->decoded['pid'];
		$itemType = $this->decoded['itemType'];
		$grade = $this->decoded['grade'];
		$sourceIdx = $this->decoded['sourceIdx'];

		if( $pid && $itemType && $grade && $sourceIdx )
		{
			if ( strtolower( $itemType ) == 'weapon' )
			{
				$gatchaValue = json_decode(MY_Controller::GATCHA_BY_WEPN_GRADE, true)[$grade - 2];
			}
			else if ( strtolower( $itemType ) == 'backpack' )
			{
				$gatchaValue = json_decode(MY_Controller::GATCHA_BY_BCPC_GRADE, true)[$grade - 2];
			}
			else if ( strtolower( $itemType ) == 'technique' )
			{
				$gatchaValue = json_decode(MY_Controller::GATCHA_BY_SKIL_GRADE, true)[$grade - 2];
			}

			$gatchaPayment = '200';
			$gatchaPayment_type = 'CASH_POINTS';

			$this->dbPlay->onBeginTransaction();

			unset($arrayResult);
			// 합성은 비용 없음 추후 생길 경우 아래 주석 해제
			$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $gatchaPayment_type, $gatchaPayment, '아이템 진화 다시하기' );
			$arrayProduct = $this->dbRef->requestArticleInfo($gatchaValue)->result_array();
			//공통처리
			$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '아이템 재합성('.$sourceIdx.')' );
			$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
			unset( $arrayResult['rewardobject'] );
			$result = (bool)$this->dbPlay->deletePlayerItem( $pid, $sourceIdx );
			$this->dbPlay->onEndTransaction( $result );
			if ( $result )
			{
				$this->calcurateEnergy( $pid );
				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$this->onSysLogWriteDb( $pid, '합성 다시하기, 재료아이템 '.$sourceIdx.', 소모비용 '.$gatchaPayment_type.', '.$gatchaPayment.', 획득아이템 '.$arrayResult['inventory'][0]['idx'].', '.$arrayResult['inventory'][0]['refid'] );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_SYNTHESIZE_FAIL;
				$resultText = MY_Controller::MESSAGE_SYNTHESIZE_FAIL;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestEvolution()
	{
		$pid = $this->decoded['pid'];
		$targetIdx = $this->decoded['targetIdx'];
		$sourceIdx = $this->decoded['sourceIdx'];
		$targetId = $this->decoded['targetId'];
		$sourceId = $this->decoded['sourceId'];
		$arrChar = array( $targetIdx, $sourceIdx );

		if( $pid && $targetIdx && $sourceIdx )
		{
			$arrayResult = $this->dbPlay->requestCharactersEvolution( $pid, array_unique($arrChar, SORT_LOCALE_STRING) )->result_array();
			$bTeamInfo = $this->dbPlay->requestTeam( $pid )->result_array();
			foreach( $bTeamInfo as $row )
			{
				if ( $row['team_seq'] == 0 )
				{
					$aTeamInfo['00'] = $row['memb_0'];
					$aTeamInfo['01'] = $row['memb_1'];
					$aTeamInfo['02'] = $row['memb_2'];
				}
				else if ( $row['team_seq'] == 1 )
				{
					$aTeamInfo['10'] = $row['memb_0'];
					$aTeamInfo['11'] = $row['memb_1'];
					$aTeamInfo['12'] = $row['memb_2'];
				}
				else if ( $row['team_seq'] == 2 )
				{
					$aTeamInfo['20'] = $row['memb_0'];
					$aTeamInfo['21'] = $row['memb_1'];
					$aTeamInfo['22'] = $row['memb_2'];
				}
			}

			if ( count($arrayResult) != count(array_unique($arrChar, SORT_LOCALE_STRING)) )
			{
				$resultCode = MY_Controller::STATUS_EVOLUTION_LEVEL;
				$resultText = MY_Controller::MESSAGE_EVOLUTION_LEVEL;
				$arrayResult = null;
			}
			else
			{
				$arrayEvolutionInfo = $this->dbRef->requestMaterialCheck( $pid, $targetId, $sourceId )->result_array();
				if ( empty($arrayEvolutionInfo) )
				{
					$resultCode = MY_Controller::STATUS_EVOLUTION_INFO;
					$resultText = MY_Controller::MESSAGE_EVOLUTION_INFO;
					$arrayResult = null;
				}
				else
				{
					unset($arrayResult);
					$this->dbPlay->onBeginTransaction();
					if ( array_key_exists('e_target', $arrayEvolutionInfo[0] ) )
					{
						$arrayProduct = $this->dbRef->requestArticleInfo($arrayEvolutionInfo[0]['e_target'])->result_array();
						//공통처리
						$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '캐릭터 진화('.$sourceIdx.', '.$targetIdx.')' );
						$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
						unset( $arrayResult['rewardobject'] );

						// 팀정보 업데이트
						$is_change = false;
						foreach( $aTeamInfo as $key => $val )
						{
							if ( $val == $targetIdx )
							{
								$aTeamInfo[$key] = $arrayResult['character'][0]['idx'];
								$is_change = true;
							}
							else if ( $val == $sourceIdx )
							{
								$aTeamInfo[$key] = 0;
								$is_change = true;
							}
							else if ( $val == null )
							{
								$aTeamInfo[$key] = 0;
							}
						}
						for ( $i = 0; $i < 3; $i++ )
						{
							if ( $aTeamInfo[$i.'0'] == 0 )
							{
								$aTeamInfo[$i.'0'] = $arrayResult['character'][0]['idx'];
								if ( $aTeamInfo[$i.'1'] == $arrayResult['character'][0]['idx'] )
								{
									$aTeamInfo[$i.'1'] = 0;
								}
								else if ( $aTeamInfo[$i.'2'] == $arrayResult['character'][0]['idx'] )
								{
									$aTeamInfo[$i.'2'] = 0;
								}
							}
						}

						if ( $is_change )
						{
							$result = $result & (bool)$this->dbPlay->requestDeployCharacters( $pid, $aTeamInfo );
						}

						$arrChar = $this->dbPlay->requestSynthesizeCharInfo( $pid, $sourceIdx, $targetIdx )->result_array();
						foreach ( $arrChar as $row )
						{
							if ( $row['weapon'] != null && $row['weapon'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['weapon'] );
							}
							if ( $row['backpack'] != null && $row['backpack'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['backpack'] );
							}
							if ( $row['skill_0'] != null && $row['skill_0'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['skill_0'] );
							}
							if ( $row['skill_1'] != null && $row['skill_1'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['skill_1'] );
							}
							if ( $row['skill_2'] != null && $row['skill_2'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['skill_2'] );
							}
							if ( $row['gear_0'] != null && $row['gear_0'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_0'] );
							}
							if ( $row['gear_1'] != null && $row['gear_1'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_1'] );
							}
							if ( $row['gear_2'] != null && $row['gear_2'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_2'] );
							}
							if ( $row['gear_3'] != null && $row['gear_3'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_3'] );
							}
							if ( $row['gear_4'] != null && $row['gear_4'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_4'] );
							}
							if ( $row['gear_5'] != null && $row['gear_5'] != '' )
							{
								$this->dbPlay->deletePlayerItem( $pid, $row['gear_5'] );
							}
						}

						$result = (bool)$this->dbPlay->deletePlayerCharacter( $pid, $sourceIdx );
						$result = (bool)$result & (bool)$this->dbPlay->deletePlayerCharacter( $pid, $targetIdx );
					}
					else
					{
						$result = (bool)0;
					}
					$this->dbPlay->onEndTransaction( $result );
					if ( $result )
					{
						$this->calcurateEnergy( $pid );
						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$arrayResult['team'] = $this->dbPlay->requestTeam( $pid )->result_array();
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$this->onSysLogWriteDb( $pid, '진화, 재료기체 '.$targetIdx.', '.$sourceIdx.', 획득기체 '.$arrayResult['character'][0]['idx'].', '.$arrayResult['character'][0]['refid'] );
					}
					else
					{
						$resultCode = MY_Controller::STATUS_SYNTHESIZE_FAIL;
						$resultText = MY_Controller::MESSAGE_SYNTHESIZE_FAIL;
						$arrayResult = null;
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_EVOLUTION_FAIL;
			$resultText = MY_Controller::MESSAGE_EVOLUTION_FAIL;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestAchieveList()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrayResult['arrayDailyAchieve'] = $this->dbPlay->requestDailyAchieveList( $pid )->result_array();

			if ( count($arrayResult['arrayDailyAchieve']) == 0 )
			{
				$this->dbPlay->requestDailyAchieveInsert( $pid );
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				unset($arrayResult);
				$arrayResult['arrayDailyAchieve'] = $this->dbPlay->requestDailyAchieveList( $pid )->result_array();
				$arrayResult['arrayAchieve'] = $this->dbPlay->requestAchieveList( $pid )->result_array();
			}
			else
			{
				$arrayResult['arrayAchieve'] = $this->dbPlay->requestAchieveList( $pid )->result_array();
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestAchieveStatusUpdate()
	{
		$pid = $this->decoded['pid'];
		$arrayAchieve = $this->decoded['arrayAchieve'];

		if( $pid && $arrayAchieve )
		{
			foreach ( $arrayAchieve as $achieve )
			{
				$this->dbPlay->requestAchieveInsert( $pid, $achieve['aid'] );
				$this->dbPlay->requestAchieveStatusUpdate( $pid, $achieve['aid'], $achieve['astatus'] );
				$this->onSysLogWriteDb( $pid, '업적상태 변경, '.$achieve['aid'].', 변경상태, '.$achieve['astatus'] );
			}

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestAchieveReward()
	{
		$pid = $this->decoded['pid'];
		$aid = $this->decoded['aid'];
		$aid = array_unique($aid, SORT_LOCALE_STRING);

		if( $pid && $aid )
		{
			$arrayAchieve = $this->dbRef->requestAchieveType( $pid, $aid )->result_array();
			$prevRewardCount = (int)0;
			$txtDailyList = '';
			$txtPermList = '';

			foreach ( $arrayAchieve as $row )
			{
				if ( $row['repeate'] == 'DAILY' )
				{
					$prevRewardCount = $prevRewardCount + (int)$this->dbPlay->requestDailyAchieveRewardCount( $pid, $row['id'] );
					$txtDailyList = $txtDailyList.$row['id'].', ';
					$arrDailyList[] = $row['id'];
					$arrPermList = array();
					//$this->dbPlay->requestInsertNextArchieve( $pid, $row['id'] );
				}
				else
				{
					$prevRewardCount = $prevRewardCount + (int)$this->dbPlay->requestAchieveRewardCount( $pid, $row['id'] );
					$txtPermList = $txtPermList.$row['id'].', ';
					$arrPermList[] = $row['id'];
					$arrDailyList = array();
					//$this->dbPlay->requestInsertNextArchieve( $pid, $row['id'] );
				}

				foreach( $aid as $row )
				{
					$this->dbPlay->requestAchieveInsert( $pid, $row );
				}

				//20150115 다음 업적이 중복으로 들어가는 오류가 있어서 해당부분 제거 (위에 $this->dbPlay->requestInsertNextArchieve( $pid, $row['id'] ); 부분) 수정
			}

			if ( $prevRewardCount > 0 )
			{
				$resultCode = MY_Controller::STATUS_ACHIEVE_REWARD_GIVED;
				$resultText = MY_Controller::MESSAGE_ACHIEVE_REWARD_GIVED;
				$arrayResult = null;
			}
			else
			{
				//$this->dbPlay->onBeginTransaction();
				$result = $this->dbPlay->requestAchieveReward( $pid, $aid );
				if ( $result == count($aid) )
				{
					$result = (bool)$result;
					$arrayPoints = $this->dbRef->requestAchieveRewardSum( $pid, $aid )->result_array();
					if ( empty($arrayPoints) )
					{
						$resultCode = MY_Controller::STATUS_ACHIEVE_REWARD_EMPTY;
						$resultText = MY_Controller::MESSAGE_ACHIEVE_REWARD_EMPTY;
						$arrayResult = null;
						$result = (bool)0;
					}
					else
					{
						//기본보상 추가 (플레이어 경험치)
						$player_exp = 0;
						foreach ( $arrayPoints as $row )
						{
							$player_exp += $row['reward_exp'];
						}
						$playerInfo = $this->dbPlay->requestPlayerInfo( $pid, $player_exp )->result_array();
						if ( !( empty( $playerInfo ) ) )
						{
							$this->dbPlay->requestUpdatePlayerInfo( $pid, $playerInfo[0]['player_level'], $playerInfo[0]['player_exp'] );
							$playerInfo = $playerInfo[0];
							if ( $playerInfo['prev_level'] < $playerInfo['player_level'] )
							{
								$playerReward = $this->dbRef->requestPlayerReward( $pid, $playerInfo['prev_level'], $playerInfo['player_level'] )->result_array();
								if ( !( empty($playerReward) ) )
								{
									foreach( $playerReward as $row )
									{
										if ( $row['reward_div'] == 'PERM' )
										{
											$this->dbPlay->updatePlayerBasic( $pid, $row['reward_type'], $row['reward_value'] );
										}
										else
										{
											$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::VIPREWARD_SEND_TITLE, $row['reward_type'], $row['reward_value'], false );
											$this->dbPlay->updatePlayerRewardDate( $pid, $row['reward_type'], $row['reward_value'] );
										}
									}
								}
							}
							unset($playerInfo["prev_level"]);
						}
						else
						{
							$playerInfo = null;
						}
						$this->calcurateEnergy( $pid );
						if ( $result )
						{
							if ( !empty( $arrDailyList ) )
							{
								$result = (bool)$result & (bool)$this->dbPlay->requestAchieveNewInsert( $pid, $arrDailyList );
							}
							if ( !empty( $arrPermList ) )
							{
								$result = (bool)$result & (bool)$this->dbPlay->requestNextAchieveInsert( $pid, $arrPermList );
							}
							$resultCode = MY_Controller::STATUS_API_OK;
							$resultText = MY_Controller::MESSAGE_API_OK;
							$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
							$arrayResult['arrayDailyAchieve'] = $this->dbPlay->requestDailyAchieveList( $pid )->result_array();
							$arrayResult['arrayAchieve'] = $this->dbPlay->requestAchieveList( $pid )->result_array();
							$txtReward = '';
							foreach($arrayPoints as $row)
							{
								$txtReward = $txtReward.$row['article_value'].':'.$row['attach_value'].',';
							}
							$this->onSysLogWriteDb( $pid, '업적완료, 일일 목록, '.$txtDailyList.', 상시목록, '.$txtPermList.', 획득보상, '.$txtReward );
						}
						else
						{
							$resultCode = MY_Controller::STATUS_ACHIEVE_REWARD_GIVE;
							$resultText = MY_Controller::MESSAGE_ACHIEVE_REWARD_GIVE;
							$arrayResult = null;
							$result = (bool)0;
						}
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_ACHIEVE_REWARD_DISCORD;
					$resultText = MY_Controller::MESSAGE_ACHIEVE_REWARD_DISCORD;
					$arrayResult = null;
					$result = (bool)0;
				}
				//$this->dbPlay->onEndTransaction( $result );
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestPVERank()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrayResult['pveInfo'] = $this->dbRank->requestPVERank( $pid )->result_array();
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_INFO_PVE_RANK;
			$resultText = MY_Controller::MESSAGE_INFO_PVE_RANK;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestSellProduct()
	{
		$pid = $this->decoded['pid'];
		$sellType = $this->decoded['sell_type'];
		$sellIdx = $this->decoded['sell_idx'];
		if ( !is_array($sellIdx) )
		{
			$sellIdx = array($sellIdx);
		}
		$sellIdx = array_filter(array_filter(array_unique($sellIdx), 'is_numeric'));

		if( $pid && $sellType && $sellIdx )
		{
			$this->dbPlay->onBeginTransaction();
			if ( $sellType == 'CHARACTER' )
			{
				$sellInfo = $this->dbPlay->requestCharacterGradeLev( $pid, $sellIdx )->result_array();
				if ( !empty($sellInfo) && count($sellInfo) == count($sellIdx) )
				{
					$sellPoint = 0;
					$result = true;
					$txtSellInfo = '';
					foreach( $sellInfo as $row )
					{
						if ( $row['weapon'] != null && $row['weapon'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['weapon'] );
						}
						if ( $row['backpack'] != null && $row['backpack'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['backpack'] );
						}
						if ( $row['skill_0'] != null && $row['skill_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_0'] );
						}
						if ( $row['skill_1'] != null && $row['skill_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_1'] );
						}
						if ( $row['skill_2'] != null && $row['skill_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['skill_2'] );
						}
						if ( $row['gear_0'] != null && $row['gear_0'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_0'] );
						}
						if ( $row['gear_1'] != null && $row['gear_1'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_1'] );
						}
						if ( $row['gear_2'] != null && $row['gear_2'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_2'] );
						}
						if ( $row['gear_3'] != null && $row['gear_3'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_3'] );
						}
						if ( $row['gear_4'] != null && $row['gear_4'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_4'] );
						}
						if ( $row['gear_5'] != null && $row['gear_5'] != '' )
						{
							$this->dbPlay->deletePlayerItem( $pid, $row['gear_5'] );
						}

						$sellPoint += json_decode(MY_Controller::GAMEPOINTS_PER_CHARACTER_GRADE, true)[$row['grade']];
						$result = $result & (bool)$this->dbPlay->deletePlayerCharacter( $pid, $row['idx'] );
						$txtSellInfo .= '코드'.$row['refid'].', 등급:'.$row['grade'].', 레벨:'.$row['level'].', 고유키:'.$row['idx'].'\n';
					}
				}
				else
				{
					$sellPoint = 0;
					$txtSellInfo = '-';
					$result = (bool)0;
				}
			}
			else if ( $sellType == 'ITEM' || $sellType == 'OPERATOR' || $sellType == 'PILOT' || $sellType == 'GEAR' )
			{
				$sellInfo = $this->dbPlay->requestItemGrade( $pid, $sellIdx )->result_array();
				if ( !empty($sellInfo) && count($sellInfo) == count($sellIdx) )
				{
					$sellPoint = 0;
					$result = true;
					$txtSellInfo = '';
					foreach( $sellInfo as $row )
					{
						$sellPoint += json_decode(MY_Controller::GAMEPOINTS_PER_ITEM_GRADE, true)[$row['grade']];
						$result = $result & (bool)$this->dbPlay->deletePlayerItem( $pid, $row['idx'] );
						$txtSellInfo .= '코드'.$row['refid'].', 등급:'.$row['grade'].', 고유키:'.$row['idx'].'\n';
					}
				}
				else
				{
					$sellPoint = 0;
					$txtSellInfo = '-';
					$result = (bool)0;
				}
			}
			else
			{
				$sellPoint = 0;
				$txtSellInfo = '-';
				$result = (bool)0;
			}

			$result = (bool)$result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, 'GAME_POINTS', $sellPoint, '아이템 판매' );
			$this->dbPlay->onEndTransaction( $result );
			$this->calcurateEnergy( $pid );
			if ( $result )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$this->onSysLogWriteDb( $pid, '아이템 판매, '.$txtSellInfo.', 획득자원, GAME_POINTS, '.$sellPoint );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_SELL_PRICE;
				$resultText = MY_Controller::MESSAGE_SELL_PRICE;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExplorationList()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$result = $this->dbRecord->requestCheckExploration( $pid )->result_array();
			if ( empty($result) )
			{
				$this->dbRecord->onBeginTransaction();
				$result = $this->dbRecord->requestCreateExpGroup( $pid );
				$result2 = $this->dbRecord->requestCreateExpList( $pid, $result );
				$result2 = (bool)$result & (bool)$result2;
				$this->dbRecord->onEndTransaction( $result2 );
			}
			else
			{
				$result = $result[0]['exp_group_idx'];
			}
			$arrayResult['expl'] = $this->dbRecord->requestExplorationList( $pid, $result )->result_array();
			$arrayResult['exp_group_idx'] = $arrayResult['expl'][0]['exp_group_idx'];
			$arrayResult['rn'] = $arrayResult['expl'][0]['rn'];
			foreach( $arrayResult['expl'] as $key => $row )
			{
				unset($arrayResult['expl'][$key]['exp_group_idx']);
				unset($arrayResult['expl'][$key]['rn']);
			}

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExplorationStart()
	{
		$pid = $this->decoded['pid'];
		$exp_group_idx = $this->decoded['exp_group_idx'];
		$exp_idx = $this->decoded['exp_idx'];
		$cid = $this->decoded['cid'];
		$refid = $this->decoded['refid'];
		$up_grade = $this->decoded['up_grade'];
		$lev = $this->decoded['lev'];
		$esi = floatval( $this->decoded['esi'] );
		$cgrade = $this->decoded['cgrade'];
		$grade = $this->decoded['grade'];
		$exp_time_rate = floatval( json_decode(MY_Controller::EXP_TIME_FOR_CHAR, true)[$cgrade] );

		if( $pid && $exp_group_idx && $exp_idx && $cid )
		{
			//캐릭터 탐색 가능 여부 체크
			$result = (bool)$this->dbPlay->requestCheckCharacterCanExp( $pid, $cid );
			$result = $result & !( (bool)$this->dbPlay->requestCheckExpCanExp( $pid, $exp_group_idx, $exp_idx ) );
			if ( $result )
			{
				$exp_time = $this->dbRef->getExpSecond( $pid, $grade, $esi + $exp_time_rate )->result_array()[0]['time'];
				$this->dbRecord->onBeginTransaction();
				$this->dbPlay->onBeginTransaction();

				$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, 'GAME_POINTS', MY_Controller::EXP_COST_BASIC_MULTIPLE * $grade, '탐색 시작' );
				$this->calcurateEnergy( $pid );
				if ( $result )
				{
					$result = (bool)$this->dbRecord->requestExplorationStart( $pid, $cid, $exp_group_idx, $exp_idx, $exp_time );
					$result = $result & (bool)$this->dbPlay->requestCharacterExploration( $pid, $cid, $exp_group_idx, $exp_idx, $exp_time );
				}

				if ( $result )
				{
					$arrayResult['exp_second'] = $exp_time;
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					// 로그 문자열 만들기 시작
					$logString = '탐색 시작\n';
					$logString .= '탐색 그룹 => '.$exp_group_idx.', 탐색 행성 => '.$exp_idx.'\n';
					$logString .= '탐색 등급 => '.$grade.', 탐색 비용 => '.MY_Controller::EXP_COST_BASIC_MULTIPLE * $grade.'\n';
					$logString .= '진행팀 정보 : \n\t아이디 => '.$pid.'\n';
					if ( array_key_exists('op', $this->decoded) )
					{
						if ( $this->decoded['op'] != null && $this->decoded['op'] != '' && $this->decoded['op'] != '0' )
						{
							$logString .= '\t오퍼레이터 => '.$this->decoded['op'].'\n';
						}
					}

					if ( $refid != null && $refid != '' && $refid != '0' )
					{
						$logString .= '\t출격기체 => +'.$up_grade.' '.$refid.'('.$cid.'),';
						$logString .= 'lv.'.$lev.'\n';
					}

					$logString .= '탐색 소요 시간 => '.$exp_time.'초';
					// 로그 문자열 만들기 끝
					$this->onSysLogWriteDb( $pid, $logString );
				}
				else
				{
					$resultCode = MY_Controller::STATUS_EXPLORATE_START;
					$resultText = MY_Controller::MESSAGE_EXPLORATE_START;
					$arrayResult = null;
				}
				$this->dbPlay->onEndTransaction( $result );
				$this->dbRecord->onEndTransaction( $result );
				if ( $result )
				{
					$arrayResult['exp_time'] = $this->dbPlay->requestExplorationTime( $pid, $cid )->result_array()[0]['exp_time'];
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_EXPLORATE_CHARACTER;
				$resultText = MY_Controller::MESSAGE_EXPLORATE_CHARACTER;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExplorationResult()
	{
		$pid = $this->decoded['pid'];
		$exp_group_idx = $this->decoded['exp_group_idx'];
		$exp_idx = $this->decoded['exp_idx'];
		$grade = $this->decoded['grade'];
		$cid = $this->decoded['cid'];
		$crefid = $this->decoded['crefid'];

		$rid = $this->dbRecord->getRewardIdFromExp( $pid, $exp_group_idx, $exp_idx )->result_array();

		if( $pid && $exp_group_idx && $exp_idx && $cid && $crefid )
		{
			if ( empty($rid) )
			{
				$resultCode = MY_Controller::STATUS_EXPLORATE_COMPLETE;
				$resultText = MY_Controller::MESSAGE_EXPLORATE_COMPLETE;
				$arrayResult = null;
			}
			else
			{
				$is_enemy = $rid[0]['is_enemy'];
				$rid = $rid[0]['reward'];

				$arrayResult['rewardobject'] = $this->dbRef->randomizeValuePick( $pid, $rid, '1' )->result_array()[0];
				$this->dbPlay->onBeginTransaction();
				$this->dbRecord->onBeginTransaction();
				$this->dbMail->onBeginTransaction();

				$result = (bool)$this->dbRecord->updateExplorationResult( $pid, $exp_group_idx, $exp_idx, $arrayResult['rewardobject']['article_1_value'], $arrayResult['rewardobject']['attach_1_value'] );
				$cexp = $this->dbRef->requestExpExp( $pid, $grade )->result_array()[0]['exp'];
				$bexp = $this->dbPlay->requestCharacterExp( $pid, $cid )->result_array()[0]['exp'];

				//추가보상이 경험치일 경우
				if ( $arrayResult['rewardobject']['article_1_value'] == 'EXP_POINTS' )
				{
					$cexp = $cexp + $bexp + $arrayResult['rewardobject']['attach_1_value'];
				}
				else
				{
					$cexp = $cexp + $bexp;
					$rewardInfo = $arrayResult['rewardobject'];

					$result = (bool)$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::EXPLORATION_REWARD_TITLE, $arrayResult['rewardobject']['article_1_value'], $arrayResult['rewardobject']['attach_1_value'], MY_Controller::NORMAL_EXPIRE_TERM );
				}
				if ( $cexp > MY_Controller::CHAR_EXP_FOR_MAXLEV )
				{
					$cexp = MY_Controller::CHAR_EXP_FOR_MAXLEV;
				}
				$clev = $this->dbRef->requestLevelInfo( $pid, $cexp )->result_array()[0]['level'];

				$result = $result & (bool)$this->dbPlay->updateCharacter( $pid, $cid, $clev, $cexp );
				$this->dbPlay->updateCollection( $pid, $crefid, $clev );
				$this->dbRecord->updateExplorationReward( $pid, $exp_group_idx, $exp_idx );
				$is_reward = $this->dbPlay->requestCollection( $pid, $crefid )->result_array();
				$mlRewardList = array();
				if ( !empty( $is_reward ) )
				{
					if ( $clev > 29 && !($is_reward[0]['is_reward']) )
					{
						if ( $this->dbPlay->requestCollectionReward( $pid, $crefid ) )
						{
							$this->dbMail->sendMail(
								$pid, MY_Controller::SENDER_GM, MY_Controller::CHARMAXLEV_REWARD_TITLE, MY_Controller::CHARMAXLEV_REWARD_TYPE,
								MY_Controller::CHARMAXLEV_REWARD_VALUE, MY_Controller::NORMAL_EXPIRE_TERM
							);
							$mlRewardList[] = array('refid' => $crefid);
						}
					}
				}
				$result = $result & (bool)$this->dbPlay->requestCharacterExplorationInitialize( $pid, $cid );

				$this->dbMail->onEndTransaction( $result );
				$this->dbRecord->onEndTransaction( $result );
				$this->dbPlay->onEndTransaction( $result );
				// 로그 문자열 만들기 시작
				$logString = '탐색 종료\n';
				$logString .= '탐색 그룹 => '.$exp_group_idx.', 탐색 행성 => '.$exp_idx.'\n';
				if ( $is_enemy )
				{
					$logString .= '적발견 => 성공\n';
				}
				else
				{
					$logString .= '적발견 => 실패\n';
				}
				$tmpReward['article_value'] = $arrayResult['rewardobject']['article_1_value'];
				$tmpReward['attach_value'] = $arrayResult['rewardobject']['attach_1_value'];
				if ( $arrayResult['rewardobject']['article_1_type'] == 'CHAR' )
				{
					$txtRandReward = $arrayResult['rewardobject']['reward_1_type'];
				}
				else if ( $arrayResult['rewardobject']['article_1_type'] == 'SKIL' )
				{
					$txtRandReward = $arrayResult['rewardobject']['reward_1_type'];
				}
				else if ( $arrayResult['rewardobject']['article_1_type'] == 'ASST' )
				{
					$txtRandReward = $arrayResult['rewardobject']['reward_1_type'];
				}
				else
				{
					$txtRandReward = $arrayResult['rewardobject']['reward_1_type'];
				}
				$txtRandReward .= ' : '.$arrayResult['rewardobject']['attach_1_value'];
				$logString .= '탐색 보상 => '.$txtRandReward.'\n';
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
				unset($arrayResult);
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult['char_info'] = $this->dbPlay->requestCharacter( $pid, $cid )->result_array()[0];
				$arrayResult['reward_type'] = $tmpReward['article_value'];
				$arrayResult['reward_value'] = $tmpReward['attach_value'];
				$arrayResult['is_enemy'] = $is_enemy;
				$arrayResult['mlRewardList'] = $mlRewardList;
				unset($arrayResult['char_info']['refid']);
				unset($arrayResult['char_info']['weapon']);
				unset($arrayResult['char_info']['backpack']);
				unset($arrayResult['char_info']['skill_0']);
				unset($arrayResult['char_info']['skill_1']);
				unset($arrayResult['char_info']['skill_2']);
				unset($arrayResult['char_info']['up_grade']);
				unset($arrayResult['char_info']['up_refid']);
				unset($arrayResult['char_info']['up_incentive']);
				unset($arrayResult['char_info']['exp_target']);
				unset($arrayResult['char_info']['exp_time']);
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExplorationReset()
	{
		$pid = $this->decoded['pid'];
		$exp_group_idx = $this->decoded['exp_group_idx'];
		$exp_idx = $this->decoded['exp_idx'];
		$cid = $this->decoded['cid'];

		if( $pid && $exp_group_idx && $exp_idx && $cid )
		{
			$this->dbPlay->onBeginTransaction();
			$this->dbRecord->onBeginTransaction();

			$result = (bool)$this->dbRecord->updateExplorationReset( $pid, $exp_group_idx, $exp_idx );

			$result = $result & (bool)$this->dbPlay->requestCharacterExplorationInitialize( $pid, $cid );
			$this->dbPlay->onEndTransaction( $result );
			$this->dbRecord->onEndTransaction( $result );

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestExplorationGroupResult()
	{
		$pid = $this->decoded['pid'];
		$exp_group_idx = $this->decoded['exp_group_idx'];

		if( $pid && $exp_group_idx )
		{
			$result = $this->dbRecord->requestExpGroupCheck( $pid, $exp_group_idx )->result_array()[0]['cnt'];

			if ( $result == 3 )
			{
				$this->dbPlay->onBeginTransaction();
				$this->dbRecord->onBeginTransaction();

				$this->dbRecord->updateExpGroupResult( $pid, $exp_group_idx, MY_Controller::COMMON_EXP_REWARD_TYPE, MY_Controller::COMMON_EXP_REWARD_VALUE );
				$this->dbPlay->requestInitCharExpInfo( $pid );
				$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, MY_Controller::COMMON_EXP_REWARD_TYPE, MY_Controller::COMMON_EXP_REWARD_VALUE, '탐색 그룹 보상 지급' );
				$result2 = $this->dbRecord->requestCreateExpGroup( $pid );
				$result3 = $this->dbRecord->requestCreateExpList( $pid, $result2 );
				$result = (bool)$result & (bool)$result2 & (bool)$result3;
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$this->dbPlay->onEndTransaction( $result );
				$this->dbRecord->onEndTransaction( $result );

				$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
				$arrayResult['expl'] = $this->dbRecord->requestExplorationList( $pid, $result2 )->result_array();
				$arrayResult['exp_group_idx'] = $arrayResult['expl'][0]['exp_group_idx'];
				$arrayResult['rn'] = $arrayResult['expl'][0]['rn'];
				foreach( $arrayResult['expl'] as $key => $row )
				{
					unset($arrayResult['expl'][$key]['exp_group_idx']);
					unset($arrayResult['expl'][$key]['rn']);
				}

				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				// 로그 문자열 만들기 시작
				$logString = '탐색 그룹보상\n';
				$logString .= '탐색 그룹 => '.$exp_group_idx.'\n';
				$logString .= '탐색 그룹보상 => '.MY_Controller::COMMON_EXP_REWARD_TYPE.MY_Controller::COMMON_EXP_REWARD_VALUE.'';
				// 로그 문자열 만들기 끝
				$this->onSysLogWriteDb( $pid, $logString );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_EXPLORATE_NON_COMPLTE;
				$resultText = MY_Controller::MESSAGE_EXPLORATE_NON_COMPLTE;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestCollectionList()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrayResult['collection'] = $this->dbPlay->requestCollectionList( $pid )->result_array();

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestRankingInfoPVP()
	{
		$pid = $this->decoded['pid'];
		$page = $this->decoded['page'];

		if( $pid && $page )
		{
			$page = ($page - 1) * MY_Controller::COMMON_RANKING_PAGE_SIZE;
			$arrayResult['ranking_info'] = $this->dbRank->requestRankingInfoPVP( $pid, $page )->result_array();
			$arrayResult['my_info'] = $this->dbRank->requestMyRankingInfoPVP( $pid )->result_array()[0];

			$friendInfo = $this->dbRank->requestFriendRankingInfoPVP( $pid )->result_array();
			if ( count($friendInfo) > 0 )
			{
				$arrayResult['friend_info'] = $friendInfo;
			}
			else
			{
				$arrayResult['friend_info'] = array();
			}
			$arrayResult['series_info'] = $this->dbRecord->requestSeriesInfo( $pid )->result_array()[0];

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestRankingInfoPVB()
	{
		$pid = $this->decoded['pid'];
		$page = $this->decoded['page'];

		if( $pid && $page )
		{
			$page = ($page - 1) * MY_Controller::COMMON_RANKING_PAGE_SIZE;
			$arrayResult['ranking_info'] = $this->dbRank->requestRankingInfoPVB( $pid, $page )->result_array();
			$arrayResult['my_info'] = $this->dbRank->requestMyRankingInfoPVB( $pid )->result_array()[0];
			$friendInfo = $this->dbRank->requestFriendRankingInfoPVB( $pid )->result_array();
			if ( count($friendInfo) > 0 )
			{
				$arrayResult['friend_info'] = $friendInfo;
			}
			else
			{
				$arrayResult['friend_info'] = array();
			}
			$bossInfo = $this->dbRecord->requestBossInfo( $pid )->result_array();

			if ( empty($bossInfo) )
			{
				$stageid = ($this->dbRecord->requestBossWeekSeq()->result_array()[0]['weekseq'] % 5);
				if ( $stageid == 0 )
				{
					$stageid = 5;
				}
				$arrayResult['boss_info'] = array( 'stageid' => '400'.(string)($stageid).'00', 'level' => '1', 'off_hp' => '0' );
			}
			else
			{
				$stageid = substr( $bossInfo[0]['stageid'], 0, strlen($bossInfo[0]['stageid']) - strlen($bossInfo[0]['level'])).$bossInfo[0]['level'];
				$rewardInfo = $this->dbRef->requestStageReward( $pid, $stageid )->result_array();
				if ( !empty($rewardInfo) )
				{
					if ( array_key_exists('max_exp', $rewardInfo[0]) )
					{
						if ( $rewardInfo[0]['max_exp'] <= $bossInfo[0]['off_hp'] )
						{
							$bossInfo[0]['off_hp'] = $rewardInfo[0]['max_exp'] - 1;
						}
					}
				}
				$arrayResult['boss_info'] = $bossInfo[0];
			}

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestRankingInfoSURVIVAL()
	{
		$pid = $this->decoded['pid'];
		$page = $this->decoded['page'];

		if( $pid && $page )
		{
			$page = ($page - 1) * MY_Controller::COMMON_RANKING_PAGE_SIZE;
			$arrayResult['ranking_info'] = $this->dbRank->requestRankingInfoSURVIVAL( $pid, $page )->result_array();
			$arrayResult['my_info'] = $this->dbRank->requestMyRankingInfoSURVIVAL( $pid )->result_array()[0];
			$friendInfo = $this->dbRank->requestFriendRankingInfoSURVIVAL( $pid )->result_array();
			if ( count($friendInfo) > 0 )
			{
				$arrayResult['friend_info'] = $friendInfo;
			}
			else
			{
				$arrayResult['friend_info'] = array();
			}

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMyFriendList()
	{
		$pid = $this->decoded['pid'];
		$status = $this->decoded['status'];

		if ( $pid )
		{
			$arrayResult['friendlist'] = $this->dbPlay->requestMyFriendList( $pid, $status )->result_array();
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestOtherPlayer()
	{
		$pid = $this->decoded['pid'];
		$oid = $this->decoded['oid'];

		if( $pid && $oid )
		{
			//valid user check
			$tmpArray = $this->dbPlay->requestPlayerSel( $oid )->result_array()[0];
			if (empty($tmpArray))
			{
				$resultCode = MY_Controller::STATUS_FRIEND_NOBODY;
				$resultText = MY_Controller::MESSAGE_FRIEND_NOBODY;
				$arrayResult = null;
			}
			else
			{
				$arrayResult['pid'] = (string)$oid;
				$arrayResult['name'] = $tmpArray['name'];
				$arrayResult['prof_img'] = $tmpArray['prof_img'];
				$arrayResult['vip_level'] = $tmpArray['vip_level'];
				$arrayResult['vip_exp'] = $tmpArray['vip_exp'];

				$arrayResult['op'] = $tmpArray['operator'];

				$arrayResult['team'] = $this->dbPlay->requestTeam( $oid )->result_array();
				$arrayResult['inventory'] = $this->dbPlay->requestInventory( $oid )->result_array();
				$arrayResult['character'] = $this->dbPlay->requestCharacters( $oid )->result_array();

				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestPVEFriendList()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrayResult['friendlist'] = $this->dbPlay->requestPVEFriendList( $pid )->result_array();
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestRecomFriendList()
	{
		$pid = $this->decoded['pid'];
		$searchVal = $this->decoded['searchval'];

		if ( $pid )
		{
			$arrayResult['recomlist'] = $this->dbPlay->requestRecomFriendList( $pid, $searchVal )->result_array();
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestAddFriend()
	{
		$pid = $this->decoded['pid'];
		$fid = $this->decoded['fid'];

		if( $pid && $fid )
		{
			$friendCount = $this->dbPlay->requestFriendCount( $pid )->result_array()[0];
			$inc_friend = $this->dbPlay->requestIncFriendCount( $pid )->result_array()[0]['inc_fri'];
			if ( $friendCount['fcount'] < MY_Controller::MAX_FRIENDS + $inc_friend )
			{
				$result = (bool)$this->dbPlay->requestAddFriend( $pid, $fid, MY_Controller::FRIEND_STATUS_REQUEST );
				if ( $result )
				{
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = null;
					$this->onSysLogWriteDb( $pid, $fid.'친구 요청' );
				}
				else
				{
					$resultCode = MY_Controller::STATUS_FRIEND_REQUEST;
					$resultText = MY_Controller::MESSAGE_FRIEND_REQUEST;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_FRIEND_ADD;
				$resultText = MY_Controller::MESSAGE_FRIEND_ADD;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestReplyAddFriend()
	{
		$pid = $this->decoded['pid'];
		$fid = $this->decoded['fid'];
		$status = $this->decoded['status'];

		if( $pid && $fid && $status )
		{
			$this->dbPlay->onBeginTransaction();
			$friendCount = $this->dbPlay->requestFriendCount( $pid )->result_array()[0];
			$inc_friend = $this->dbPlay->requestIncFriendCount( $pid )->result_array()[0]['inc_fri'];
			if ( $status == '1' )
			{
				if ( $friendCount['fcount'] < MY_Controller::MAX_FRIENDS + $inc_friend )
				{
					$ffriendCount = $this->dbPlay->requestFriendCount( $fid )->result_array()[0];
					$inc_ffriend = $this->dbPlay->requestIncFriendCount( $fid )->result_array()[0]['inc_fri'];
					if ( (int)$ffriendCount['fcount'] < (int)MY_Controller::MAX_FRIENDS + (int)$inc_ffriend )
					{
						$result = $this->dbPlay->requestReplyAddFriend( $pid, $fid, $status );
						$result2 = $this->dbPlay->requestAddFriend( $pid, $fid, $status );
						$result2 += $this->dbPlay->requestReplyAddFriend( $fid, $pid, $status );
						$result = (bool)($result + $result2);
					}
					else
					{
						$result = (bool)0;
						$resultCode = MY_Controller::STATUS_FRIEND_REQUEST_OVER;
						$resultText = MY_Controller::MESSAGE_FRIEND_REQUEST_OVER;
						$arrayResult = null;
					}
				}
				else
				{
					$result = (bool)0;
					$resultCode = MY_Controller::STATUS_FRIEND_ADD_OVER;
					$resultText = MY_Controller::MESSAGE_FRIEND_ADD_OVER;
					$arrayResult = null;
				}
			}
			else
			{
				$result = $this->dbPlay->requestReplyAddFriend( $pid, $fid, $status );
			}
			$this->dbPlay->onEndTransaction( $result );

			if ( $result )
			{
				if ( $status == '1' )
				{
					$friendCount['fcount'] = $friendCount['fcount'] + 1;
				}
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
				$this->onSysLogWriteDb( $pid, $fid.'친구 추가, 현재 친구 수 '.$friendCount['fcount'].'명' );
			}
			else
			{
				$resultCode = MY_Controller::STATUS_FRIEND_REQUEST;
				$resultText = MY_Controller::MESSAGE_FRIEND_REQUEST;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestDelFriend()
	{
		$pid = $this->decoded['pid'];
		$fid = $this->decoded['fid'];

		if( $pid && $fid )
		{
			$friendCount = $this->dbPlay->requestFriendCount( $pid )->result_array()[0];
			if ( $friendCount['dcount'] < MY_Controller::MAX_DEL_FRIEND )
			{
				$this->dbPlay->onBeginTransaction();
				$result = (bool)$this->dbPlay->requestDelFriend( $pid, $fid, $pid );
				if ( $result )
				{
					$result = $result & (bool)$this->dbPlay->requestDelFriend( $fid, $pid, $pid );
				}
				$this->dbPlay->onEndTransaction( $result );

				if ( $result )
				{
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = null;
					$this->onSysLogWriteDb( $pid, $fid.'친구 삭제, 남은 친구 수 '.($friendCount['fcount'] - 1).'명, 남은 삭제 수 '.($friendCount['dcount'] + 1).'회' );
				}
				else
				{
					$resultCode = MY_Controller::STATUS_FRIEND_DEL_PROCESS;
					$resultText = MY_Controller::MESSAGE_FRIEND_DEL_PROCESS;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_FRIEND_DEL_FRIEND_OVER;
				$resultText = MY_Controller::MESSAGE_FRIEND_DEL_FRIEND_OVER;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestFriendshipPoint()
	{
		$pid = $this->decoded['pid'];
		$fid = $this->decoded['fid'];

		if( $pid && $fid )
		{
			$last_present_time = $this->dbPlay->requestFriendshipPointTime( $pid, $fid )->result_array()[0]['last_present_time'];
			if ( $last_present_time < 0 )
			{
				$this->dbPlay->onBeginTransaction();
				$this->dbMail->onBeginTransaction();
				$result = (bool)$this->dbMail->sendMail( $fid, $pid, MY_Controller::FRIENDSHIP_SEND_TITLE, MY_Controller::FRIENDSHIP_ARTICLE_ID, MY_Controller::FRIENDSHIP_SEND_BASIC_VALUE, MY_Controller::NORMAL_EXPIRE_TERM );
				$result = $result & (bool)$this->dbPlay->requestFriendshipPoint( $pid, $fid );
				$this->dbMail->onEndTransaction( $result );
				$this->dbPlay->onEndTransaction( $result );
				if ( $result )
				{
					$this->onSysLogWriteDb( $pid, $fid.'우정 선물' );
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = null;
				}
				else
				{
					$resultCode = MY_Controller::STATUS_FRIENDSHIP_SEND;
					$resultText = MY_Controller::MESSAGE_FRIENDSHIP_SEND;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_FRIENDSHIP_LIMIT;
				$resultText = MY_Controller::MESSAGE_FRIENDSHIP_LIMIT;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestFriendshipPointAll()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrResult = $this->dbPlay->requestFriendshipPointAll( $pid )->result_array();
			if ( empty($arrResult) )
			{
				$resultCode = MY_Controller::STATUS_FRIENDSHIP_LIMIT;
				$resultText = MY_Controller::STATUS_FRIENDSHIP_LIMIT;
				$arrayResult = null;
			}
			else
			{
				foreach( $arrResult as $idx => $row )
				{
					$result = (bool)$this->dbMail->sendMail( $row['fid'], $pid, MY_Controller::FRIENDSHIP_SEND_TITLE, MY_Controller::FRIENDSHIP_ARTICLE_ID, MY_Controller::FRIENDSHIP_SEND_BASIC_VALUE, MY_Controller::NORMAL_EXPIRE_TERM );
					if ( $result )
					{
						$this->dbPlay->requestFriendshipPoint( $pid, $row['fid'] );
						$this->onSysLogWriteDb( $pid, $row['fid'].'우정 선물' );
					}
					$arrResult[$idx]['res'] = intval($result);
				}
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult['flist'] = $arrResult;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestChangeOperator()
	{
		$pid = $this->decoded['pid'];
		$iid = $this->decoded['iid'];

		if ( $pid )
		{
			$this->dbPlay->requestChangeOperator( $pid, $iid );

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestChangePilot()
	{
		$pid = $this->decoded['pid'];
		$slotseq = $this->decoded['slotseq'];
		$teamSeq = substr($slotseq, 0, 1);
		$iid = $this->decoded['iid'];

		if( $pid && $slotseq )
		{
			if ( !( $this->dbPlay->requestCheckTeamPilot( $pid, $teamSeq, $iid ) ) )
			{
				$this->dbPlay->requestChangePilot( $pid, $slotseq, $iid );
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
			}
			else
			{
				$resultCode = MY_Controller::STATUS_PILOT_DUPLICATE;
				$resultText = MY_Controller::MESSAGE_PILOT_DUPLICATE;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestWeeklyReward()
	{
		$pid = $this->decoded['pid'];
		$rewardtype = $this->decoded['rewardtype'];

		if( $pid && $rewardtype )
		{
			$lastRankInfo = $this->dbRank->requestLastWeekRankInfo( $pid, $rewardtype )->result_array();
			if ( $rewardtype == 'pvp' )
			{
				$sendmailtitle = MY_Controller::PVPRANKING_SEND_TITLE;
			}
			else if ( $rewardtype == 'pvb' )
			{
				$sendmailtitle = MY_Controller::PVBRANKING_SEND_TITLE;
			}
			else if ( $rewardtype == 'survival' )
			{
				$sendmailtitle = MY_Controller::SURRANKING_SEND_TITLE;
			}
			if ( empty($lastRankInfo) )
			{
				$resultCode = MY_Controller::STATUS_RANK_EMPTY;
				$resultText = MY_Controller::MESSAGE_RANK_EMPTY;
				$arrayResult = null;
			}
			else
			{
				if ( $lastRankInfo[0]['rank'] == null )
				{
					$resultCode = MY_Controller::STATUS_RANK_EMPTY;
					$resultText = MY_Controller::MESSAGE_RANK_EMPTY;
					$arrayResult = null;
				}
				else if ( $lastRankInfo[0]['is_reward'] )
				{
					$resultCode = MY_Controller::STATUS_REWARD_GIVED;
					$resultText = MY_Controller::MESSAGE_REWARD_GIVED;
					$arrayResult = null;
				}
				else
				{
					if ( $lastRankInfo[0]['rank'] <= $this->dbRef->getRankRewardMaxRtype( $pid, $rewardtype )->result_array()[0]['rank'] )
					{
						$rewardUnit = 'R';
					}
					else
					{
						$rewardUnit = 'P';
					}
					$rewardInfo = $this->dbRef->requestRankRewardInfo( $pid, $lastRankInfo[0]['rank'], $lastRankInfo[0]['tot'], $rewardtype, $rewardUnit )->result_array();

					$result = (bool)1;
					foreach ( $rewardInfo as $row )
					{
						if ( $row['reward_value'] > 0 )
						{
							if ( $row['article_type'] != 'ASST' )
							{
								for ( $i = 0; $i < $row['reward_value']; $i++ )
								{
									$result = $result & (bool)$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, $sendmailtitle, $row['reward_type'], 1, MY_Controller::NORMAL_EXPIRE_TERM );
								}
							}
							else
							{
								$result = $result & (bool)$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, $sendmailtitle, $row['reward_type'], $row['reward_value'], MY_Controller::NORMAL_EXPIRE_TERM );
							}
						}
					}

					$this->dbRank->updateSendResult( $pid, $rewardtype, $result );

					if ( $result )
					{
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
						$arrayResult = null;
					}
					else
					{
						$resultCode = MY_Controller::STATUS_REWARD_GIVE;
						$resultText = MY_Controller::MESSAGE_REWARD_GIVE;
						$arrayResult = null;
					}
				}
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestRetirePlayer()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			if ( $this->dbPlay->requestRetirePlayer( $pid ) )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
				$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestEnBonus()
	{
		$pid = $this->decoded['pid'];
		$rstime = $this->decoded['rstime'];
		$rltime = $this->decoded['rltime'];

		$bftime = $this->dbPlay->requestBeforeRequestData( $pid )->result_array();
		if ( empty($bftime) )
		{
			$bftime[0]['rstime'] = '1900-01-01 00:00:00';
			$bftime[0]['rltime'] = '1900-01-01 00:00:00';
		}

		$bftime = $bftime[0];
		foreach ( $bftime as $key => $val )
		{
			$bftime[$key] = new DateTime($val);
		}
		$rsdatetime = new DateTime($rstime);
		$rldatetime = new DateTime($rltime);
		if ( in_array( $rldatetime->format('H'), json_decode(MY_Controller::ENBONUS_A, true) ) )
		{
			$rType = 'A';
			$reqstart = new DateTime( $rldatetime->format('Y-m-d '.json_decode(MY_Controller::ENBONUS_A, true)[0].':00:00') );
		}
		else if ( in_array( $rldatetime->format('H'), json_decode(MY_Controller::ENBONUS_B, true) ) )
		{
			$rType = 'B';
			$reqstart = new DateTime( $rldatetime->format('Y-m-d '.json_decode(MY_Controller::ENBONUS_B, true)[0].':00:00') );
		}
		else
		{
			$rType = null;
		}

		if ( $rType == null )
		{
			$resultCode = MY_Controller::STATUS_ENERGY_NOT_BONUS_TIME;
			$resultText = MY_Controller::MESSAGE_ENERGY_NOT_BONUS_TIME;
		}
		else
		{
			$rsdiff = ($rsdatetime->getTimestamp() - ( $rldatetime->getTimestamp() - $reqstart->getTimestamp() )) - $bftime['rstime']->getTimestamp();
			$rldiff = ($rldatetime->getTimestamp() - ( $rldatetime->getTimestamp() - $reqstart->getTimestamp() )) - $bftime['rltime']->getTimestamp();
			if ( $rType == 'A' )
			{
				$timeDiff = MY_Controller::ENBONUS_TERM_A;
			}
			else if ( $rType == 'B' )
			{
				$timeDiff = MY_Controller::ENBONUS_TERM_B;
			}

			if ( $rsdiff < $timeDiff || $rldiff < $timeDiff )
			{
				$resultCode = MY_Controller::STATUS_ENERGY_BONUS_TIME_ERROR;
				$resultText = MY_Controller::MESSAGE_ENERGY_BONUS_TIME_ERROR;
			}
			else
			{
				$result = (bool)$this->dbPlay->requestEnBonus( $pid, ($rsdatetime->add( $rldatetime->diff($reqstart) )->format('Y-m-d H:i:s')), $reqstart->format('Y-m-d H:i:s') );
				$result = $result & (bool)$this->updatePoint( $pid, MY_Controller::COMMON_SAVE_CODE, MY_Controller::ENERGY_BONUS_TYPE, MY_Controller::ENERGY_BONUS_VALUE, MY_Controller::ENERGY_BONUS_LOG_TEXT );

				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
			}
		}

		$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
		$arrayRstime = $this->dbPlay->requestBeforeRequestData( $pid )->result_array();
		if ( empty($arrayRstime) )
		{
			$rstime = '1900-01-01 00:00:00';
		}
		else
		{
			$rstime = $arrayRstime[0]['rstime'];
		}
		$arrayResult['rstime'] = $rstime;

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestResetGearSlot()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['cid'];
		$gtype_0 = $this->decoded['gtype_0'];
		$gtype_1 = $this->decoded['gtype_1'];
		$gtype_2 = $this->decoded['gtype_2'];

		$price[MY_Controller::GEAR_RESET_BASIC_TYPE] = MY_Controller::GEAR_RESET_BASIC_VALUE;
		$price[MY_Controller::GEAR_RESET_LOCK_TYPE] = ( intval($gtype_0) + intval($gtype_1) + intval($gtype_2) ) * MY_Controller::GEAR_RESET_LOCK_VALUE;

		if ( $pid )
		{
			$result = (bool)1;
			foreach( $price as $key => $val )
			{
				$result = $result & ( $val > 0 ? (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $key, $val, '기어 슬롯 리셋비용' ) : (bool)1 );
			}

			if ( $result )
			{
				$gearInfo = $this->dbPlay->requestCharacter( $pid, $cid )->result_array()[0];
				$gearInfo = array( $gearInfo['gear_0'], $gearInfo['gear_1'], $gearInfo['gear_2'], $gearInfo['gear_3'], $gearInfo['gear_4'], $gearInfo['gear_5'] );
				foreach ( $gearInfo as $key => $val )
				{
					if ( $val != null && $this->decoded['gtype_'.(string)(int)(substr($key, strlen($key) - 1, 1) / 2)] == 0 )
					{
						$result = $result & (bool)$this->dbPlay->deleteInventory( $pid, $val );
					}
				}

				if ( $this->dbPlay->requestResetGearSlot( $pid, $cid, $gtype_0, $gtype_1, $gtype_2 ) )
				{
					$arrayResult['objectarray'] = $this->dbPlay->requestCharacterIns( $pid, $cid )->result_array()[0];
					$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
				}
				else
				{
					$resultCode = MY_Controller::STATUS_RESET_NO_CHARACTER;
					$resultText = MY_Controller::MESSAGE_RESET_NO_CHARACTER;
					$arrayResult = null;
				}
			}
			else
			{
				$resultCode = MY_Controller::STATUS_RESET_LACK_CASH;
				$resultText = MY_Controller::MESSAGE_RESET_LACK_CASH;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestSynthesizeGear()
	{
		$pid = $this->decoded['pid'];
		$type = $this->decoded['type'];
		$parts = $this->decoded['parts'];
		$grade = $this->decoded['grade'];
		$iid = $this->decoded['iid'];
		$iid = array_unique($iid);

		if ( $pid )
		{
			if ( count($iid) == 10 )
			{
				$this->dbPlay->onBeginTransaction();

				if ( $this->dbPlay->deleteInventoryArray( $pid, $iid ) == 10 )
				{
					// 합성은 비용 없음 추후 생길 경우 아래 주석 해제 - 비용이 발생하여 주석 제거
					$gatchaPayment_type = MY_Controller::GATCHA_BY_ITEM_GRADE_PAYMENT_TYPE;
					$gatchaPayment = json_decode(MY_Controller::GATCHA_BY_ITEM_GRADE_PAYMENT, true)[$grade];
					$result = (bool)$this->updatePoint( $pid, MY_Controller::COMMON_USE_CODE, $gatchaPayment_type, $gatchaPayment, '기어 합성하기' );

					if ( $result )
					{
						$gatchaValue = json_decode(MY_Controller::GATCHA_BY_GEAR_GRADE, true)[$type][$parts][$grade];
						$arrayProduct = $this->dbRef->requestArticleInfo($gatchaValue)->result_array();
						$iidString = '';
						foreach ( $iid as $val ) {
							$iidString .= ( $iidString == '' ? $val : ', '.$val );
						}

						//공통처리
						$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '기어 합성('.$iidString.')' );
						$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
						unset( $arrayResult['rewardobject'] );

						$arrayResult['remain_item'] = $this->dbPlay->requestItem( $pid )->result_array()[0];
						$resultCode = MY_Controller::STATUS_API_OK;
						$resultText = MY_Controller::MESSAGE_API_OK;
					}
					else
					{
						$resultCode = MY_Controller::STATUS_EQUIP_ITEM;
						$resultText = MY_Controller::MESSAGE_EQUIP_ITEM;
						$arrayResult = null;
					}
				}
				else
				{
					$resultCode = MY_Controller::STATUS_EQUIP_ITEM;
					$resultText = MY_Controller::MESSAGE_EQUIP_ITEM;
					$arrayResult = null;
					$result = false;
				}
				$this->dbPlay->onEndTransaction( $result );
			}
			else
			{
				$arrayResult = null;
				$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
				$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}
	//테스트용

	public function requestCharacterProvisioning()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['cid'];

		if ( $pid )
		{
			$arrayProduct[] = array( 'attach_value' => 1, 'article_type' => 'CHAR', 'article_value' => $cid );
			//공통처리
			$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '캐릭터 강제배포' );
			$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
			unset( $arrayResult['rewardobject'] );

			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestItemProvisioning()
	{
		$pid = $this->decoded['pid'];
		$iid = $this->decoded['iid'];

		if ( $pid )
		{
			$arrayProduct = $this->dbRef->requestArticleInfo( $iid )->result_array();
			//공통처리
			$arrayResult = $this->commonUserResourceProvisioning( $arrayProduct, $pid, $pid, '캐릭터 강제배포' );
			$arrayResult = $this->commonRewardObject( $pid, $arrayResult, null, $arrayProduct );
			unset( $arrayResult['rewardobject'] );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMaxCharacter()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['characters'];

		if ( $pid )
		{
			foreach ($cid as $idx => $row)
			{
				$arrayResult[$idx]['cid'] = $row['cid'];
				$arrayResult[$idx]['result'] = $this->dbPlay->requestMaxCharacter( $pid, $row['cid'] );
			}
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestMaxCharacterAll()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$arrayResult['result'] = $this->dbPlay->requestMaxCharacterAll($pid);
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestInit()
	{
		$pid = $this->decoded['pid'];

		if ( $pid )
		{
			$result = $this->dbPlay->resetPlayerPoint( $pid );
			$result = $result & $this->dbPlay->resetPlayerBasic( $pid );
			$result = $result & $this->dbPlay->resetPlayerAttend( $pid );
			$result = $result & $this->dbPlay->resetPlayerCharacter( $pid );
			$result = $result & $this->dbPlay->resetPlayerCollection( $pid );
			$result = $result & $this->dbPlay->resetPlayerEquipment( $pid );
			$result = $result & $this->dbPlay->resetPlayerAchieve( $pid );
			$result = $result & $this->dbPlay->resetPlayerInventory( $pid );
			$result = $result & $this->dbPlay->resetPlayerTeam( $pid );
			$result = $result & $this->dbRecord->resetPlayerPVE( $pid );
			$result = $result & $this->dbRecord->resetPlayerPVB( $pid );
			$result = $result & $this->dbRecord->resetPlayerSURVIVAL( $pid );
			$result = $result & $this->dbRecord->resetPlayerPVP( $pid );
//			$result = $result & $this->dbRank->resetPlayerPVE( $pid );
			$result = $result & $this->dbRank->resetPlayerPVB( $pid );
			$result = $result & $this->dbRank->resetPlayerSURVIVAL( $pid );
			$result = $result & $this->dbRank->resetPlayerPVP( $pid );
			$result = $result & $this->dbRecord->resetPlayerExploration( $pid );

			$arrayInventory[0] = $this->dbPlay->inventoryProvision( $pid, 'WCC1120001' );
			$result = $result & (bool)$arrayInventory[0];
			$arrayInventory[1] = $this->dbPlay->inventoryProvision( $pid, 'WCC1320001' );
			$result = $result & (bool)$arrayInventory[1];
			$arrayInventory[2] = $this->dbPlay->inventoryProvision( $pid, 'WCC1250001' );
			$result = $result & (bool)$arrayInventory[2];
			$arrayInventory[3] = $this->dbPlay->inventoryProvision( $pid, 'OP01000002' );
			$result = $result & (bool)$arrayInventory[3];
			$arrayCharacter[0] = $this->dbPlay->characterProvision( $pid, 'RS0100042' );
			$result = $result & (bool)$arrayCharacter[0];
			$arrayCharacter[1] = $this->dbPlay->characterProvision( $pid, 'RI0000042' );
			$result = $result & (bool)$arrayCharacter[1];
			$arrayCharacter[2] = $this->dbPlay->characterProvision( $pid, 'RD0000000' );
			$result = $result & (bool)$arrayCharacter[2];
			$result = $result & $this->dbPlay->collectionProvision( $pid, 'RS0100042' );
			$result = $result & $this->dbPlay->collectionProvision( $pid, 'RI0000042' );
			$result = $result & $this->dbPlay->collectionProvision( $pid, 'RD0000000' );
			$result = $result & $this->dbPlay->newPlayerTeam( $pid, $arrayCharacter[0], $arrayCharacter[1], null );
			$result = $result & $this->dbPlay->updateEquipment( $pid, 'operator', $arrayInventory[3] );
			$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[0], 'weapon', $arrayInventory[0] );
			$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[1], 'weapon', $arrayInventory[1] );
			//$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[2], 'weapon', $arrayInventory[2] );

//			$this->dbRank->insertPVP( $pid );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUpdateFile()
	{
		$device = $this->decoded['device'];
		$lang = $this->decoded['lang'];
		if ( $device == 'ANDROID' )
		{
			$device = 'AND';
		}
		else if ( $device == 'IOS' )
		{
			$device = 'IOS';
		}

		if( $device )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult['download_url'] = 'http://'.$_SERVER['HTTP_HOST'].URLBASE.MY_Controller::DOWNLOAD_URL;
			$arrayResult['notice_url'] = 'http://'.$_SERVER['HTTP_HOST'].URLBASE.MY_Controller::NOTICE_URL;

			$this->load->model('admin/Model_Admin', 'dbAdmin');
			$banResult = $this->dbAdmin->requestImageBannerList( $device )->result_array();

			foreach ( $banResult as $row )
			{
				if ( $device == 'AND' )
				{
					$arrayResult['event_url'][] = 'http://'.$_SERVER['HTTP_HOST'].URLBASE.'index.php/pages/notice/imagenotice/view/'.$row['idx'].'/AND';
				}
				else if ( $device == 'IOS' )
				{
					$arrayResult['event_url'][] = 'http://'.$_SERVER['HTTP_HOST'].URLBASE.'index.php/pages/notice/imagenotice/view/'.$row['idx'].'/IOS';
				}
			}
			$arrayResult['policy_url'] = 'http://'.$_SERVER['HTTP_HOST'].URLBASE.'/static/policy/policy_'.$lang.'.html';
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, '0', $_POST['data'] );
	}

	public function requestLevelChange()
	{
		$pid = $this->decoded['pid'];
		$cid = $this->decoded['cid'];
		$clev = $this->decoded['clev'];
		$cexp = $this->decoded['cexp'];

		if( $pid && $cid && $clev && $cexp )
		{
			$this->dbPlay->requestLevelChange( $pid, $cid, $clev, $cexp );
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	//삭제예정
	public function requestEquipToPlayer()
	{
		$pid = $this->decoded['pid'];
		$slotseq = $this->decoded['slotSeq'];
		$iid = $this->decoded['iid'];

		if ( $pid && $slotseq && $iid )
		{
			$this->dbPlay->onBeginTransaction();
			$deliid = $this->dbPlay->requestEquipDelCheck( $pid, $iid )->result_array();

			// 현재 아이템을 사용할 수 없는 경우
			if ( count($deliid) == 0 )
			{
				$result = (bool)0;
				$resultCode = MY_Controller::STATUS_DUPLICATION_DATA;
				$resultText = MY_Controller::MESSAGE_DUPLICATION_DATA;
				$arrayResult = null;
			}
			else
			{
				$unSlotSeq = $this->dbPlay->requestGetSlotSeq( $pid, $iid )->result_array();
				if ( count($unSlotSeq) > 0 )
				{
					$this->dbPlay->requestUnEquipToPlayer( $pid, $unSlotSeq[0]['slotseq'] );
				}
				$result = (bool)$this->dbPlay->requestEquipToPlayer( $pid, $slotseq, $iid );

				if ( $result )
				{
					$resultCode = MY_Controller::STATUS_API_OK;
					$resultText = MY_Controller::MESSAGE_API_OK;
					$arrayResult = null;
				}
				else
				{
					$resultCode = MY_Controller::STATUS_NO_DATA;
					$resultText = MY_Controller::MESSAGE_NO_DATA;
					$arrayResult = null;
				}
			}
			$this->dbPlay->onEndTransaction( $result );
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestUnEquipToPlayer()
	{
		$pid = $this->decoded['pid'];
		$iid = $this->decoded['iid'];

		if ( $pid && $iid )
		{
			$unSlotSeq = $this->dbPlay->requestGetSlotSeq( $pid, $iid )->result_array()[0]['slotseq'];
			$this->dbPlay->requestUnEquipToPlayer( $pid, $unSlotSeq );

			if ( $result )
			{
				$resultCode = MY_Controller::STATUS_API_OK;
				$resultText = MY_Controller::MESSAGE_API_OK;
				$arrayResult = null;
			}
			else
			{
				$resultCode = MY_Controller::STATUS_NO_DATA;
				$resultText = MY_Controller::MESSAGE_NO_DATA;
				$arrayResult = null;
			}
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_MATCHING_PARAMETER;
			$resultText = MY_Controller::MESSAGE_NO_MATCHING_PARAMETER;
			$arrayResult = null;
		}

		echo $this->API_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $pid, $_POST['data'] );
	}

	public function requestTestLoop()
	{
		$prefix = $this->decoded['prefix'];
		$maxSeq = $this->decoded['maxSeq'];

		for ( $seqi = 230000; $seqi <= $maxSeq + 230000; $seqi++ )
		{
			$id = $prefix;
			for ( $j = 0; $j < strlen($maxSeq) - strlen($seqi); $j++ )
			{
				$id .= '0';
			}
			$id = $id.$seqi;
			$password = '1111';
			$macaddr = 'A'.$seqi;

			//아이디 중복 체크
			if ($this->dbPlay->requestDupId( $id ) > 0)
			{
				$resultCode = MY_Controller::STATUS_DUPLICATION_DATA;
				$resultText = 'requestJoin-1'.MY_Controller::MESSAGE_DUPLICATION_DATA;
				$arrayResult = null;
				echo '중복아이디 발생 => '.$id.'\n<br />';
			}
			else
			{
				//회원 가입처리
				$this->dbPlay->onBeginTransaction();
				$arrayResult['pid'] = $this->dbLogin->requestJoin( $id, $password, $macaddr );
				if ( count($arrayResult) < 1 )
				{
					$resultCode = MY_Controller::STATUS_UPDATE_NOTHING;
					$resultText = 'requestJoin-2'.MY_Controller::MESSAGE_UPDATE_NOTHING;
					$arrayResult = null;
					$result = (bool)0;
				}
				else
				{
					$result = (bool)$arrayResult['pid'];
					$this->dbPlay->requestJoinStep2($arrayResult['pid']);
					$this->dbPlay->insertItem( $arrayResult['pid'] );

					$arraySupply = $this->dbRef->requestSupplies( $arrayResult['pid'] )->result_array();
					$result = (bool)1;
					$charCount = 0;
					$itemCount = 0;
					$equipCount = 0;
					foreach( $arraySupply as $row )
					{
						if ( $row['article_type'] == 'ASST' )
						{
							$result = $result & (bool)$this->updatePoint( $arrayResult['pid'], MY_Controller::COMMON_SAVE_CODE, $row['type'], $row['value'], '' );
						}
						else if ( $row['article_type'] == 'CHAR' )
						{
							for ( $i = 0; $i < $row['value']; $i++ )
							{
								$arrayCharacter[$charCount] = $this->dbPlay->characterProvision( $arrayResult['pid'], $row['type'] );
								$charCount = $charCount + 1;
							}
							$this->dbPlay->collectionProvision( $arrayResult['pid'], $row['type'] );
						}
						else if ( $row['article_type'] == 'WEPN' || $row['article_type'] == 'BCPC' || $row['article_type'] == 'SKIL' || $row['article_type'] == 'GEAR' )
						{
							for ( $i = 0; $i < $row['value']; $i++ )
							{
								$arrayInventory[$itemCount] = $this->dbPlay->inventoryProvision( $arrayResult['pid'], $row['type'] );
								$itemCount = $itemCount + 1;
							}
						}
						else if ( $row['article_type'] == 'OPRT' )
						{
							for ( $i = 0; $i < $row['value']; $i++ )
							{
								$arrayEquipment[$equipCount] = $this->dbPlay->inventoryProvision( $arrayResult['pid'], $row['type'] );
								$equipCount = $equipCount + 1;
							}
						}
					}
					$result = $result & $this->dbPlay->newPlayerTeam( $arrayResult['pid'], $arrayCharacter[0], $arrayCharacter[1], null );
//					$result = $result & $this->dbPlay->insertEquipment( $arrayResult['pid'], $arrayEquipment[0] );
					$result = $result & $this->dbPlay->itemToChar( $arrayResult['pid'], $arrayCharacter[0], 'weapon', $arrayInventory[0] );
					$result = $result & $this->dbPlay->itemToChar( $arrayResult['pid'], $arrayCharacter[1], 'weapon', $arrayInventory[1] );
					$result = $result & $this->dbPlay->itemToChar( $arrayResult['pid'], $arrayCharacter[0], 'backpack', $arrayInventory[3] );
					$result = $result & $this->dbPlay->itemToChar( $arrayResult['pid'], $arrayCharacter[1], 'backpack', $arrayInventory[4] );
				}
				$this->dbPlay->onEndTransaction( $result );
			}
		}
	}

	public function requestTestLoopItem()
	{
		$arrayItem = Array( '', 'WCC1260001', 'WCC2260001', 'WCC3260001', 'WCC4260011', 'WCC5260011', 'WCC6260011',
								'WCC1320001', 'WCC2320001', 'WCC3320011', 'WCC4320011', 'WCC5320021', 'WCC6320021',
								'WCC1300001', 'WCC2300001', 'WCC3300011', 'WCC4300031', 'WCC5300021', 'WCC6300021',
								'WCC1050001', 'WCC2050001', 'WCC3050021', 'WCC4050021', 'WCC5050011', 'WCC6050011',
								'WCC1190001', 'WCC2190001', 'WCC3190011', 'WCC4190021', 'WCC5190031', 'WCC6190031',
								'WCC1130001', 'WCC2130001', 'WCC3130011', 'WCC4130011', 'WCC5130022', 'WCC6130022',
								'WCC1110001', 'WCC2110001', 'WCC3110011', 'WCC4110031', 'WCC5110041', 'WCC6110051',
								'WCC1220001', 'WCC2220001', 'WCC3220002', 'WCC4220002', 'WCC5220003', 'WCC6220003',
								'WCC1170001', 'WCC2170001', 'WCC3170002', 'WCC4170002', 'WCC5170003', 'WCC6170003',
								'WCC1240001', 'WCC2240001', 'WCC3240002', 'WCC4240002', 'WCC5240003', 'WCC6240003',
								'WCC1140001', 'WCC2140021', 'WCC3140031', 'WCC4140051', 'WCC5140061', 'WCC6140071',
								'WCC1100001', 'WCC2100001', 'WCC3100002', 'WCC4100002', 'WCC5100003', 'WCC6100003',
								'WCC1180001', 'WCC2180001', 'WCC3180002', 'WCC4180002', 'WCC5180003', 'WCC6180003',
								'WCC1210001', 'WCC2210001', 'WCC3210002', 'WCC4210002', 'WCC5210003', 'WCC6210003',
								'WCC1230001', 'WCC2230001', 'WCC3230002', 'WCC4230002', 'WCC5230003', 'WCC6230003',
								'WCC1190001', 'WCC2190001', 'WCC3190011', 'WCC4190021', 'WCC5190031', 'WCC6190031',
								'WCC1160001', 'WCC2160001', 'WCC3160011', 'WCC4160011', 'WCC5160022', 'WCC6160022',
								'WCC1200001', 'WCC2200001', 'WCC3200011', 'WCC4200031', 'WCC5200041', 'WCC6200051'
					);

		$this->dbPlay->onBeginTransaction();
		$result = (bool)1;
		for ( $seqi = 1; $seqi <= 108; $seqi++ )
		{
			$id = 'WEAPONTEST2_';
			for ( $j = 0; $j < 3 - strlen($seqi); $j++ )
			{
				$id .= '0';
			}
			$id = $id.$seqi;

			$pid = $this->dbPlay->requestPid($id)->result_array()[0]['pid'];
			$arrayCharacter[0] = $this->dbPlay->requestGetCharFirst( $pid )->result_array()[0]['memb_0'];
			$arrayInventory[0] = $this->dbPlay->inventoryProvision( $pid, $arrayItem[$seqi] );
			$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[0], 'weapon', $arrayInventory[0] );
		}

		$this->dbPlay->onEndTransaction( $result );
	}

	public function requestTestLoopSkill()
	{
		$arrayItem = Array( '', 'SCC?300001', 'SCC?300002', 'SCC?300003', 'SCC?300004', 'SCC?300005', 'SCC?300006',
							'SCC?300007', 'SCC?300008', 'SCC?300009', 'SCC?300010', 'SCC?300011', 'SCC?300012',
							'SCC?310001', 'SCC?310002', 'SCC?310003', 'SCC?310004', 'SCC?310005', 'SCC?310006',
							'SCC?310007', 'SCC?310008', 'SCC?310009', 'SCC?310010', 'SCC?320001', 'SCC?320002',
							'SCC?310011', 'SCC?310012', 'SCC?310013', 'SCC?400100', 'SCC?400200', 'SCC?400300',
							'SCC?400400', 'SCC?400500', 'SCC?400600', 'SCC?400700', 'SCC?400900', 'SCC?401000'
					);

		$this->dbPlay->onBeginTransaction();
		$result = (bool)1;
		$idxi = 1;
		for ( $seqi = 1; $seqi <= 36; $seqi++ )
		{
			if ( substr($arrayItem[$seqi], 4, 1) == '3' )
			{
				$loopCnt = 6;
			}
			else if ( substr($arrayItem[$seqi], 4, 1) == '4' )
			{
				$loopCnt = 3;
			}
			else
			{
				$loopCnt = 0;
			}

			for ( $seqj = 6; $seqj > (6 - $loopCnt); $seqj-- )
			{
				$itemid =  str_replace('?', $seqj, $arrayItem[$seqi]);
				$id = 'SKILLTEST_';
				for ( $j = 0; $j < 3 - strlen($idxi); $j++ )
				{
					$id .= '0';
				}
				$id = $id.$idxi;
				$idxi = $idxi + 1;
				$pid = $this->dbPlay->requestPid($id)->result_array()[0]['pid'];
				$arrayCharacter[0] = $this->dbPlay->requestGetCharFirst( $pid )->result_array()[0]['memb_0'];
				$arrayInventory[0] = $this->dbPlay->inventoryProvision( $pid, $itemid );
				$result = $result & $this->dbPlay->itemToChar( $pid, $arrayCharacter[0], 'skill_0', $arrayInventory[0] );
			}
		}

		$this->dbPlay->onEndTransaction( $result );
	}

	public function requestSendMail()
	{
		$this->decoded = json_decode ( stripslashes ( $_POST['data'] ), TRUE );
		$pid = $this->decoded['pid'];
		$sid = $this->decoded['sid'];
		$title = $this->decoded['title'];
		$attach_type = $this->decoded['attach_type'];
		$attach_value = $this->decoded['attach_value'];
		$expire_date = $this->decoded['expire_date'];

		if ( $this->dbMail->sendMail( $pid, $sid, $title, $attach_type, $attach_value, $expire_date ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
			$arrayResult = null;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_INSERT_ROW;
			$resultText = MY_Controller::MESSAGE_INSERT_ROW;
			$arrayResult = null;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST['data'] );
	}
}
?>
