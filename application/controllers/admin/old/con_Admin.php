<?php
class Con_Admin extends MY_Controller {

	function __construct(){
		parent::__construct();		
		//$this->load->model("admin/Model_Admin", "dbModel");
		$config["upload_path"] = "static/images/upload/event_banner/";
		$config["allowed_types"] = "gif|jpg|png";
		$config["max_size"]	= "0";
		$config["max_width"] = "0";
		$config["max_height"] = "0";
		
		$this->load->library("upload", $config);
		$this->upload->display_errors();
	}
	
	function index()
	{
		$this->load->view("error/403_Forbidden");
	}
	
	public function view()
	{	
		$curMenu = (isset($_GET["curMenu"]) && $_GET["curMenu"]) ? $_GET["curMenu"] : 1;
		$this->load->view("admin/view_Admin", array( "curMenu"=>$curMenu ));
	}
	
	public function onCheckAll()
	{
		$arrayData = $this->admModel->requestAllUser()->result_array();
		echo $this->json_encode2( array( "arrayData"=>$arrayData ) );
	}
	
	public function onAdminProcess()
	{
		$kakao_id = explode(",", str_replace(" ", "", $_REQUEST["kakaoId"]));
		$method = $_REQUEST["method"];
		$selectItem = $_REQUEST["selectItem"];
		$count = $_REQUEST["count"];
		$reason = $_REQUEST["reason"];
		$message = $_REQUEST["message"];

		$processCount = 0;
		$processFailCount = 0;

		foreach($kakao_id as $single_id)
		{
			$identity = $this->admModel->insertAdminLog( $single_id, $method, $selectItem, $count, $reason, $message, $this->session->userdata( "userId_session" ) );

			$this->dbModel->onStartTransaction();
			if ($method == "s")
			{
				//set
				if ($selectItem == MY_Controller::ITEM_ID_MONEY)
				{
					$arrayData = $this->admModel->requestEgg( $single_id )->result_array();
					$arrayData = $arrayData[0];
					
					if (intval($arrayData["egg"]) > $count)
					{
						$method = "m";
						$count = intval($arrayData["egg"]) - $count;
					}
					else if (intval($arrayData["egg"]) < $count)
					{
						$method = "p";
						$count = $count - intval($arrayData["egg"]);
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_REALMONEY)
				{
					$arrayData = $this->admModel->requestEgg( $single_id )->result_array();
					$arrayData = $arrayData[0];
					
					if (intval($arrayData["realegg"]) > $count)
					{
						$method = "m";
						$count = intval($arrayData["realegg"]) - $count;
					}
					else if (intval($arrayData["realegg"]) < $count)
					{
						$method = "p";
						$count = $count - intval($arrayData["realegg"]);
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_HEART)
				{
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$this->calcurate_heart( $single_id, $arrayData );
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$arrayData = $arrayData[0];
					
					if (intval($arrayData["current_heart"]) > $count)
					{
						$method = "m";
						$count = intval($arrayData["current_heart"]) - $count;
					}
					else if (intval($arrayData["current_heart"]) < $count)
					{
						$method = "p";
						$count = $count - intval($arrayData["current_heart"]);
					}
				}
				else
				{
					$arrayData = $this->admModel->requestItem( $single_id, $selectItem )->result_array();
					$arrayData = $arrayData[0];
					
					if (intval($arrayData["item_value"]) > $count)
					{
						$method = "m";
						$count = intval($arrayData["item_value"]) - $count;
					}
					else if (intval($arrayData["item_value"]) < $count)
					{
						$method = "p";
						$count = $count - intval($arrayData["item_value"]);
					}
				}
			}
			
			if ($method == "p")
			{
				//add
				if ($selectItem == MY_Controller::ITEM_ID_MONEY)
				{
					$this->dbModel->insertEggLog( $single_id, MY_Controller::ITEM_ID_MONEY, $count, 0, MY_Controller::EGG_SAVE_TYPE, $this->session->userdata( "userId_session" ), $reason, $message );
					$arrayData = $this->dbModel->updateEgg( $single_id, MY_Controller::EGG_SAVE_TYPE, $count, 0 );
					if ($arrayData == 1)
					{
						$processCount = $processCount + 1;
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_REALMONEY)
				{
					$this->dbModel->insertEggLog( $single_id, MY_Controller::ITEM_ID_MONEY, 0, $count, MY_Controller::EGG_SAVE_TYPE, $this->session->userdata( "userId_session" ), $reason, $message );
					$arrayData = $this->dbModel->updateEgg( $single_id, MY_Controller::EGG_SAVE_TYPE, 0, $count );
					if ($arrayData == 1)
					{
						$processCount = $processCount + 1;
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_HEART)
				{
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$this->calcurate_heart( $single_id, $arrayData );
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$arrayData = $arrayData[0];

					if (intval($arrayData["current_heart"]) + intval($count) > MY_Controller::MAX_HEART_SIZE)
					{
						$count = MY_Controller::MAX_HEART_SIZE - intval($arrayData["current_heart"]);
					}
					if ($count > 0)
					{
						$this->dbModel->insertHeartLog( $single_id, $count, $this->session->userdata( "userId_session" ), $reason, $message );
						$arrayData = $this->dbModel->addHeart( $single_id, $count );
						if ($arrayData == 1)
						{
							$processCount = $processCount + 1;
						}
						else
						{
							$processFailCount = $processFailCount + 1;
						}
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else
				{
					$this->dbModel->insertBuyLog( $single_id, $selectItem, $count, 0, $this->session->userdata( "userId_session" ), $reason, $message );
					$this->dbModel->updateItem_3( $kakao_id, $item_id, $item_value );
				}
			}
			else if ($method == "m")
			{
				//remove
				if ($selectItem == MY_Controller::ITEM_ID_MONEY)
				{
					$this->dbModel->insertEggLog( $single_id, MY_Controller::ITEM_ID_MONEY, $count, 0, MY_Controller::EGG_USE_TYPE, $this->session->userdata( "userId_session" ), $reason, $message );
					$arrayData = $this->dbModel->updateEgg( $single_id, MY_Controller::EGG_USE_TYPE, $count, 0 );
					
					if ($arrayData == 1)
					{
						$processCount = $processCount + 1;
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_REALMONEY)
				{
					$this->dbModel->insertEggLog( $single_id, MY_Controller::ITEM_ID_MONEY, 0, $count, MY_Controller::EGG_USE_TYPE, $this->session->userdata( "userId_session" ), $reason, $message );
					$arrayData = $this->dbModel->updateEgg( $single_id, MY_Controller::EGG_USE_TYPE, 0, $count );
					
					if ($arrayData == 1)
					{
						$processCount = $processCount + 1;
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else if ($selectItem == MY_Controller::ITEM_ID_HEART)
				{
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$this->calcurate_heart( $single_id, $arrayData );
					$arrayData = $this->dbModel->requestHeart( $single_id )->result_array();
					$arrayData = $arrayData[0];

					if (intval($arrayData["current_heart"]) - intval($count) < 0)
					{
						$count = intval($arrayData["current_heart"]);
					}
					if ($count > 0)
					{
						$this->dbModel->heart_uselog_2( $single_id, $arrayData["current_heart"], $count, -1, -1, $reason, $message );
						$arrayData = $this->dbModel->useHeart_2( $single_id, $count );
						if ($arrayData == 1)
						{
							$processCount = $processCount + 1;
						}
						else
						{
							$processFailCount = $processFailCount + 1;
						}
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
				else
				{
					$this->dbModel->useItemLog( $single_id, $selectItem, $count, $this->session->userdata( "userId_session" ), $reason, $message );
					$arrayData = $this->dbModel->updateItem_2( $single_id, $selectItem, $count );
					if ($arrayData == 1)
					{
						$processCount = $processCount + 1;
					}
					else
					{
						$processFailCount = $processFailCount + 1;
					}
				}
			}
			else
			{
				$processFailCount = $processFailCount + 1;
			}
			$this->dbModel->updateAfterValue( $single_id, $selectItem, $identity );
			$this->dbModel->onCompleteTransaction();
		}
		
		if ($processCount == 0)
		{
			$processCountText = "no";
		}
		else
		{
			$processCountText = $processCount." count";
		}
		
		if ($processFailCount == 0)
		{
			$processFailCountText = "no";
		}
		else
		{
			$processFailCountText = $processFailCount." count";
		}
		$processMessage = $processFailCountText." process fail. ".$processCountText." process success";

		echo $this->json_encode2( array( "processCount"=>$processCount, "processFailCount"=>$processFailCount, "processMessage"=>$processMessage ) );
	}
	
	public function onAdminProcessHistory()
	{
		$search_type = $_REQUEST["search_type"];
		
		if ($search_type == "id")
		{
			$idType = $_REQUEST["idType"];
			$id = $_REQUEST["id"];
			$method = $_REQUEST["method"];
			
			$arrayData = $this->admModel->requestAdminLogById( $idType, $id, $method )->result_array();
		}
		else if ($search_type == "date")
		{
			$start_date = $_REQUEST["start_date"];
			$end_date = $_REQUEST["end_date"];
			
			$arrayData = $this->admModel->requestAdminLogByDate( $start_date, $end_date )->result_array();
		}

		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventBannerList()
	{
		$arrayData = $this->admModel->requestEventBannerList()->result_array();
		
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventBannerInsert()
	{
		$ban_title = $_REQUEST["ban_title"];

		$event_start = $_REQUEST["event_start"];
		$event_start_hour = $_REQUEST["event_start_hour"];
		$event_start_min = $_REQUEST["event_start_min"];
		$event_start = $event_start." ".$event_start_hour.":".$event_start_min.":00";

		$event_end = $_REQUEST["event_end"];
		$event_end_hour = $_REQUEST["event_end_hour"];
		$event_end_min = $_REQUEST["event_end_min"];
		$event_end = $event_end." ".$event_end_hour.":".$event_end_min.":00";

		$ban_start = $_REQUEST["ban_start"];
		$ban_start_hour = $_REQUEST["ban_start_hour"];
		$ban_start_min = $_REQUEST["ban_start_min"];
		$ban_start = $ban_start." ".$ban_start_hour.":".$ban_start_min.":00";

		$ban_end = $_REQUEST["ban_end"];
		$ban_end_hour = $_REQUEST["ban_end_hour"];
		$ban_end_min = $_REQUEST["ban_end_min"];
		$ban_end = $ban_end." ".$ban_end_hour.":".$ban_end_min.":00";

		$event_condition = $_REQUEST["event_condition"];
		$event_reward = $_REQUEST["event_reward"];
		$event_content = $_REQUEST["event_content"];

        if ($this->upload->do_upload("banner_image"))
        {
			$arrayData = $this->upload->data();

			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/")));

			$this->admModel->insertEventBanner( $ban_title, $event_start, $event_end, $ban_start, $ban_end, $arrayData["full_path"], $event_condition, $event_reward, $event_content, $this->session->userdata( "userId_session" ) );
			
		 	echo "<script type='text/javascript'>";
			echo "alert('등록 되었습니다.');";
			echo "window.location.href = '/Hero/index.php/pages/admin/admin/view?curMenu=4';";
			echo "</script>";
        }
        else
        {
        	$this->load->helper('url');
		 	echo "<script type='text/javascript'>";
			echo "alert('등록에 실패하였습니다.');";
			echo "window.location.href = '/Hero/index.php/pages/admin/admin/view?curMenu=4';";
			echo "</script>";
        }
	}
	
	public function onEventBannerBeShowChange()
	{
		$idx = $_REQUEST["idx"];
		$be_show = $_REQUEST["be_show"];
		
		$arrayData = $this->admModel->updateEventBannerBeShowChange( $idx, $be_show, $this->session->userdata( "userId_session" ) );
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventBannerBeDelChange()
	{
		$idx = $_REQUEST["idx"];
		
		$arrayData = $this->admModel->updateEventBannerBeDelChange( $idx, $this->session->userdata( "userId_session" ) );
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventBannerGetDetail()
	{
		$idx = $_REQUEST["idx"];
		
		$arrayData = $this->admModel->requestEventBannerGetDetail( $idx )->result_array();
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventBannerUpdate()
	{
		$ban_title = $_REQUEST["ban_title"];

		$event_start = $_REQUEST["event_start"];
		$event_start_hour = $_REQUEST["event_start_hour"];
		$event_start_min = $_REQUEST["event_start_min"];
		$event_start = $event_start." ".$event_start_hour.":".$event_start_min.":00";

		$event_end = $_REQUEST["event_end"];
		$event_end_hour = $_REQUEST["event_end_hour"];
		$event_end_min = $_REQUEST["event_end_min"];
		$event_end = $event_end." ".$event_end_hour.":".$event_end_min.":00";

		$ban_start = $_REQUEST["ban_start"];
		$ban_start_hour = $_REQUEST["ban_start_hour"];
		$ban_start_min = $_REQUEST["ban_start_min"];
		$ban_start = $ban_start." ".$ban_start_hour.":".$ban_start_min.":00";

		$ban_end = $_REQUEST["ban_end"];
		$ban_end_hour = $_REQUEST["ban_end_hour"];
		$ban_end_min = $_REQUEST["ban_end_min"];
		$ban_end = $ban_end." ".$ban_end_hour.":".$ban_end_min.":00";

		$event_condition = $_REQUEST["event_condition"];
		$event_reward = $_REQUEST["event_reward"];
		$event_content = $_REQUEST["event_content"];
		$img_url = $_REQUEST["img_url"];
		$idx = $_REQUEST["idx"];

        if ($this->upload->do_upload("banner_image"))
        {
			$arrayData = $this->upload->data();

			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/") - 1));
			$arrayData["full_path"] = substr($arrayData["full_path"], -(strlen($arrayData["full_path"]) - strpos($arrayData["full_path"], "/")));

			$this->admModel->updateEventBanner( $idx, $ban_title, $event_start, $event_end, $ban_start, $ban_end, $arrayData["full_path"], $event_condition, $event_reward, $event_content, $this->session->userdata( "userId_session" ) );
        }
        else
        {
        	$this->admModel->updateEventBanner( $idx, $ban_title, $event_start, $event_end, $ban_start, $ban_end, $img_url, $event_condition, $event_reward, $event_content, $this->session->userdata( "userId_session" ) );
        }

		echo "<script type='text/javascript'>";
		echo "alert('수정 되었습니다.');";
		echo "window.location.href = '/Hero/index.php/pages/admin/admin/view?curMenu=4';";
		echo "</script>";
	}
	
	public function onRequestEventList()
	{
		$arrayData = $this->admModel->requestEventList( )->result_array();
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventInsert()
	{
		$event_name = $_REQUEST["event_name"];
		$apply_count = $_REQUEST["apply_count"];
		
		$event_start = $_REQUEST["e_start"];
		$event_start_hour = $_REQUEST["e_start_hour"];
		$event_start_min = $_REQUEST["e_start_min"];
		$event_start = $event_start." ".$event_start_hour.":".$event_start_min.":00";

		$event_end = $_REQUEST["e_end"];
		$event_end_hour = $_REQUEST["e_end_hour"];
		$event_end_min = $_REQUEST["e_end_min"];
		$event_end = $event_end." ".$event_end_hour.":".$event_end_min.":00";
		
		$event_content = $_REQUEST["e_content"];
		$adm_message = $_REQUEST["adm_message"];

		$event_type = $_REQUEST["event_type"];
		$event_condition = $_REQUEST["e_condition"];
		$condition_value = $_REQUEST["condition_value"];
		$event_reward = $_REQUEST["e_reward"];
		$reward_value = $_REQUEST["reward_value"];
		
		$event_id = $this->admModel->insertEventBasic( $event_name, $apply_count, $event_content, $adm_message, $event_start, $event_end, $this->session->userdata( "userId_session" ) );

		foreach($event_type as $key=>$value)
		{
			$this->admModel->insertEventDetail( $event_id, $event_type[$key], $event_condition[$key], $condition_value[$key], $event_reward[$key], $reward_value[$key] );
		}
		
		echo "<script type='text/javascript'>";
		echo "alert('등록 되었습니다.');";
		echo "window.location.href = '/Hero/index.php/pages/admin/admin/view?curMenu=3';";
		echo "</script>";
	}
	
	public function onEventGetDetail()
	{
		$event_id = $_REQUEST["idx"];
		
		$arrayData = $this->admModel->requestEventGetDetail( $event_id )->result_array();
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onEventUpdate()
	{
		$event_id = $_REQUEST["event_id"];
		$event_name = $_REQUEST["event_name"];
		$apply_count = $_REQUEST["apply_count"];
		
		$event_start = $_REQUEST["e_start"];
		$event_start_hour = $_REQUEST["e_start_hour"];
		$event_start_min = $_REQUEST["e_start_min"];
		$event_start = $event_start." ".$event_start_hour.":".$event_start_min.":00";

		$event_end = $_REQUEST["e_end"];
		$event_end_hour = $_REQUEST["e_end_hour"];
		$event_end_min = $_REQUEST["e_end_min"];
		$event_end = $event_end." ".$event_end_hour.":".$event_end_min.":00";
		
		$event_content = $_REQUEST["e_content"];
		$adm_message = $_REQUEST["adm_message"];

		$event_type = $_REQUEST["event_type"];
		$event_condition = $_REQUEST["e_condition"];
		$condition_value = $_REQUEST["condition_value"];
		$event_reward = $_REQUEST["e_reward"];
		$reward_value = $_REQUEST["reward_value"];
		
		$this->admModel->updateEventBasic( $event_id, $event_name, $apply_count, $event_content, $adm_message, $event_start, $event_end, $this->session->userdata( "userId_session" ) );

		$this->admModel->deleteEventDetail( $event_id );
		foreach($event_type as $key=>$value)
		{
			$this->admModel->insertEventDetail( $event_id, $event_type[$key], $event_condition[$key], $condition_value[$key], $event_reward[$key], $reward_value[$key] );
		}
		
		echo "<script type='text/javascript'>";
		echo "alert('수정 되었습니다.');";
		echo "window.location.href = '/Hero/index.php/pages/admin/admin/view?curMenu=3';";
		echo "</script>";
	}
	
	public function onChangeEventStatus()
	{
		$event_id = $_REQUEST["idx"];
		$is_open = $_REQUEST["is_open"];
		
		$arrayData["row"] = $this->admModel->updateEventStatus( $event_id, $is_open );
		echo $this->json_encode2( array( "arrayData"=>$arrayData ));
	}
	
	public function onMakeCoupon()
	{
		/*
		$group_id = "1"; //쿠폰 그룹아이디
		$basicInfo = $this->admModel->getCouponBasicInfo( $group_id )->result_array(); //쿠폰 수량을 가져옴
		for ( $i = 0; $i < $basicInfo[0]["coupon_count"]; $i++ ) //수량만큼 쿠폰을 생성하기 위해 루프
		{
			$checkCount = 1;
			while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
			{
				$coupon_id = $this->generateRandomString(16); // 16자리 쿠폰 생성 (영대문자+숫자)
				$checkCount = $this->admModel->checkDup( $coupon_id ); // 중복체크
			}
			$this->admModel->insertCoupon( $group_id, $coupon_id ); // 최종결과 coupon_detail_info에 인서트
		}
		*/
	}
}
?>

