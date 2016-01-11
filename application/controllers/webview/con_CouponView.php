<?php
class Con_CouponView extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('api/Model_Login', "dbLogin");
	}

	function index()
	{
		$this->load->view("error/403_Forbidden");
	}

	public function input( $pid )
	{
		if ( !( is_numeric( $pid ) ) )
		{
			$pid = "";
		}
		$this->load->view( "webview/view_couponViewInput", array( "pid" => $pid ) );
	}

	public function result()
	{
		if ( array_key_exists( "coupon_id", $_POST ) && array_key_exists( "pid", $_POST ) )
		{
			$coupon_id = $_POST["coupon_id"];
			$pid = $_POST["pid"];
			$couponInfo1 = $this->dbLogin->couponVerify1( $coupon_id, $pid )->result_array();
			$couponInfo2 = $this->dbLogin->couponVerify2( $coupon_id, $pid )->result_array();
			// 쿠폰 정보가 올바르지 않은 경우
			if ( empty( $couponInfo1 ) && empty( $couponInfo2 ) )
			{
				$result = "NONE";
			}
			else
			{
				if ( empty( $couponInfo1 ) )
				{
					$couponInfo = $couponInfo2;
				}
				else
				{
					$couponInfo = $couponInfo1;
				}
				// 이미 사용된 쿠폰인 경우
				if ( $couponInfo[0]["coupon_type"] == 'R' &&  $couponInfo[0]["coupon_user_id"] > 0 )
				{
					$result = "DUPLICATE";
				}
				else
				{
					//해당 그룹내 쿠폰 이미 사용일 경우
					if ( $couponInfo[0]["result"] == "DUPLICATE" )
					{
						$result = "DUPLICATE";
					}
					else
					{
						foreach( $couponInfo as $row )
						{
							$this->dbMail->sendMail( $pid, MY_Controller::SENDER_GM, MY_Controller::COUPON_SEND_TITLE, $row["reward_type"], $row["reward_value"], MY_Controller::NORMAL_EXPIRE_TERM );
						}
						$this->dbLogin->requestCouponStatusUpdate( $pid, $couponInfo[0]["group_id"], $couponInfo[0]["coupon_type"], $coupon_id );
						$result = "SUCCESS";
					}
				}
			}
		}
		else
		{
			$coupon_id = "";
			$result = "NONE";
		}

		if ( $result == "" )
		{
			$result = "NONE";
		}
		$this->load->view( "webview/view_couponViewResult", array( "pid" => $pid, "result" => $result ) );
	}
}
?>

