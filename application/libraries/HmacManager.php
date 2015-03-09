<?php
/*
 * HMAC library for NHN API Gateway
 * 2010.09.06

 * Copyright 2010 NHN Corp. All rights Reserved.
 * NHN PROPRIETARY. Use is subject to license terms.
 */

define("MSGPAD", "msgpad=");
define("MD", "&md=");
define("QUESTION", "?");
define("AMPERCENT", "&");
define("MAX_MESSAGESIZE", 255);
define("MAX_KEY_BYTE", 1000);

class HmacManager {
	private function getMessage($url, $cur_time) {
		$msg_url = substr($url, 0, MAX_MESSAGESIZE);

		return $msg_url.$cur_time;
	}

	private function getMessageDigest($message, $key) {
		return urlencode(base64_encode(hash_hmac("sha1", $message, $key, true)));
	}

	private function getTimeStamp() {
		//$today  = mktime (date("H"),date("i"),date("s"),date("m") , date("d"), date("Y"))."000";
		list($microtime,$timestamp) = explode(' ',microtime());
		$time = $timestamp.substr($microtime,2,3);

		return $time;
	}

	private function makeEncrytUrl($url, $key) {
		$cur_time = $this->getTimeStamp();
		$message = $this->getMessage($url, $cur_time);
		$md = $this->getMessageDigest($message, $key);

		return MSGPAD.$cur_time.MD.$md;
	}

	private function parseProperties($properties) {
		$trimedProperties = preg_replace("/\s+/","",$properties);
		$propertiesValue = preg_split("[=]", $trimedProperties);

		return $propertiesValue[1];
	}

	public function getEncryptUrl($url) {
		if (!file_exists("./static/NHNAPIGatewayKey.properties"))
			return;

		$fp = fopen("./static/NHNAPIGatewayKey.properties", "r");
		$fbuf = fgets($fp, MAX_KEY_BYTE);
		fclose($fp);

		$key = $this->parseProperties($fbuf);

		$pos = strpos($url, QUESTION);

		if ($pos == false) {
			return $url.QUESTION.$this->makeEncrytUrl($url, $key);
		} else {
			return $url.AMPERCENT.$this->makeEncrytUrl($url, $key);
		}

	}
}
?>