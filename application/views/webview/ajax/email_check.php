<?php
	header("Content-Type: text/plain");
	header("Content-Type: text/html; charset=utf8");

	$email = $_REQUEST['email'];

	//$connect = mysqli_connect('172.18.45.247','root','dudrud0612@') or die("Error " . mysqli_error($connect)); ;
	include_once "../include/db_connect.php";

	$query = "SELECT * FROM koc_account.account WHERE id = '".$email."'" or die("Error in the consult.." . mysqli_error($connect));

	$result =  $connect->query($query);
	//$result = mysql_query("SELECT * FROM koc_play.account WHERE email = 'wanzang@naver.com'");
	$i=0;

	while($row = mysqli_fetch_array($result)) {
		 $i++;
	}

	if($i>0){
		echo "success";
		//$sql = "insert koc_play.account set	token = '".$token."'" ;
	}else{
		echo "fail";

	//mysql_query($sql);
	}


?>
