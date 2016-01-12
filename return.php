<?php
	function getCurl($fUrl,$fMethod,$fParam) {
		$sUrl = $fUrl.(($fParam && strtolower($fMethod)=="get") ? "?$fParam": "");
		$sMethod = (strtolower($fMethod)=="get") ? "0" : "1" ;
		$sParam = (strtolower($fMethod)=="get") ? "" : $fParam ;

		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL,"$sUrl"); //접속할 URL 주소
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 인증서 체크같은데 true 시 안되는 경우가 많다.
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// default 값이 true 이기때문에 이부분을 조심 (https 접속시에 필요)
		curl_setopt ($ch, CURLOPT_SSLVERSION,3); // SSL 버젼 (https 접속시에 필요)
		curl_setopt ($ch, CURLOPT_HEADER, 0); // 헤더 출력 여부
		curl_setopt ($ch, CURLOPT_POST, $sMethod); // Post Get 접속 여부
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "$fParam"); // Post 값  Get 방식처럼적는다.
		curl_setopt ($ch, CURLOPT_TIMEOUT, 30); // TimeOut 값
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); // 결과값을 받을것인지
		$result = curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}
	//$_GET["code"] = "4/NveuJOzzHz-3yT_I2AyPtu74r_agvZDWVXyvIlt6BIk.UqfC6Tu_rkcWgrKXntQAax0WofSTlwI";
	if ($_GET["code"]) {
		$sParam = "";
		$sParam .= "code=".$_GET["code"];
		$sParam .= "&client_id=861544031598-ejukh7ao7elqfv3bm9vlqqkq1vl8tca1.apps.googleusercontent.com";
		$sParam .= "&client_secret=1wO8w4Js0FyVD-Gv3S4lJsm2";
		$sParam .= "&redirect_uri=http://m.koccommon.tntgame.co.kr/return.php";
		$sParam .= "&grant_type=authorization_code"; // authorization_code 는 OAuth 2 에서는 고정값입니다.
		$rResult = getCurl("https://accounts.google.com/o/oauth2/token","post","$sParam");
		echo "<br />token 생성<br />$rResult<hr>";
	}
?>