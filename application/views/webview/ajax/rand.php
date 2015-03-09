<?php
	header("Content-Type: text/plain");
	header("Content-Type: text/html; charset=utf-8");
	
			
	include_once "../include/db_connect.php";						
	require_once "../PHPMailer/PHPMailerAutoload.php";
	
	function token_rand()
	{		
		$token = str_pad(mt_rand(0,9999999999),10,'1');
		
		$query = "SELECT * FROM koc_account.account WHERE token = '".$token."'" or die("Error in the consult.." . mysqli_error($connect));
		$result =  $connect->query($query);
		$i =0;
		
		while($row = mysqli_fetch_array($result)) {
		$i++;
		}		
	    if($i>0)
		token_rand($token);
		else 
	    return $token;
	}			
	
	$token = token_rand();	
	
	echo $token;
?>

 

