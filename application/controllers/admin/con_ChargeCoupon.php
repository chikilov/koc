<?php
class Con_ChargeCoupon extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/Model_Admin', "dbAdmin");
	}

	function index()
	{
		$this->load->view('error/403_Forbidden');
	}

	public function view( $subnavi = 0, $searchParam = "", $searchValue = "" )
	{
		$data = array( 'subnavi' => $subnavi, 'searchParam' => $searchParam, 'searchValue' => $searchValue );
		$this->load->view( 'admin/view_ChargeCoupon_'.$subnavi, $data );
	}

	public function requestCouponInsert()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$group_name = $decoded["group_name"];
		$coupon_type = $decoded["coupon_type"];
		$coupon_count = $decoded["coupon_count"];
		$start_date = $decoded["start_date"];
		$end_date = $decoded["end_date"];
		$static_code = $decoded["static_code"];
		$reward_array = $decoded["reward_array"];

		$group_id = $this->dbAdmin->insertCouponBasic( $group_name, $coupon_type, $coupon_count, $start_date, $end_date, $static_code ); //쿠폰 그룹아이디
		foreach( $reward_array as $row )
		{
			$this->dbAdmin->insertCouponReward( $group_id, $row["reward_type"], $row["reward_value"] ); //보상 입력
		}

		if ( $coupon_type == "R" )
		{
			for ( $i = 0; $i < $coupon_count; $i++ ) //수량만큼 쿠폰을 생성하기 위해 루프
			{
				$checkCount = 1;
				while ( $checkCount >= 1 ) //중복카운트가 0일때 까지 돌림
				{
					$coupon_id = $this->generateRandomString(16); // 16자리 쿠폰 생성 (영대문자+숫자)
					$checkCount = $this->dbAdmin->checkDup( $coupon_id ); // 중복체크
				}
				$this->dbAdmin->insertCoupon( $group_id, $coupon_id ); // 최종결과 coupon_detail_info에 인서트
			}
		}

		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		$arrayResult["group_id"] = $group_id;

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, $_POST["data"] );
	}

	public function requestCouponList()
	{
		$arrayResult = $this->dbAdmin->requestCouponList()->result_array();
		$resultCode = MY_Controller::STATUS_API_OK;
		$resultText = MY_Controller::MESSAGE_API_OK;
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrayResult, null );
	}

	public function requestCouponStatusChange()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$group_id = $decoded["group_id"];
		$is_valid = $decoded["is_valid"];

		if ( $this->dbAdmin->requestCouponStatusChange( $group_id, $is_valid ) )
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, null, null );
	}

	public function requestCouponListView( $group_id )
	{
		$arrayResult = $this->dbAdmin->requestCouponListView( $group_id )->result_array();

		echo "<table style=\"border: 1px solid\">";
		foreach( $arrayResult as $key => $row )
		{
			if ( $key > 0 )
			{
				echo "<tr style=\"border-top:1px solid\"><td>".$row["coupon_id"]."</td></tr>";
			}
			else
			{
				echo "<tr><td>".$row["coupon_id"]."</td></tr>";
			}
		}
		echo "</table>";
	}

	public function searchCoupon()
	{
		$decoded = json_decode ( stripslashes ( $_POST ["data"] ), TRUE );
		$this->logw->admLogWrite( LOG_NOTICE, "requestData : ".$_POST ["data"] );

		$coupon_id = $decoded["coupon_id"];
		$searchParam = $decoded["searchParam"];
		$searchValue = $decoded["searchValue"];

		$arrResult = $this->dbAdmin->requestCouponSearch( $coupon_id, $searchParam, $searchValue )->result_array();

		if ( empty($arrResult) )
		{
			$resultCode = MY_Controller::STATUS_NO_DATA;
			$resultText = MY_Controller::MESSAGE_NO_DATA;
		}
		else
		{
			$resultCode = MY_Controller::STATUS_API_OK;
			$resultText = MY_Controller::MESSAGE_API_OK;
		}

		echo $this->ADM_RETURN_MESSAGE( $resultCode, $resultText, $arrResult, null );
	}
}
?>

