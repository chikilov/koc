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

if ( $_SERVER["DOCUMENT_ROOT"] == "/var/www/html" )
{
	$sFileName = dirname(__FILE__)."/token.htm";
}
else
{
	$sFileName = dirname(__FILE__)."\\"."token.htm";
}

$sResult = json_decode(file_get_contents($sFileName),true);
$refresh_token = addslashes($sResult["refresh_token"]);
$regdate = $sResult["regdate"];

$sResult = "";
if ((time()-$regdate)>1800) {
	$sParam = "";
	$sParam .= "refresh_token=".$refresh_token;
	$sParam .= "&client_id=861544031598-ejukh7ao7elqfv3bm9vlqqkq1vl8tca1.apps.googleusercontent.com";
	$sParam .= "&client_secret=1wO8w4Js0FyVD-Gv3S4lJsm2";
	$sParam .= "&grant_type=refresh_token";
	$rResult = getCurl("https://accounts.google.com/o/oauth2/token","post","$sParam");

	$sResult = json_decode($rResult,true);
	$sResult["regdate"] = "".time()."";
	$sResult["refresh_token"] = $refresh_token;

	$rResult = json_encode($sResult);
	$fp=fopen($sFileName,'w');
	fwrite($fp,$rResult);
	fclose($fp);

	echo $rResult;
}
else
{
	echo file_get_contents($sFileName);
}
?>