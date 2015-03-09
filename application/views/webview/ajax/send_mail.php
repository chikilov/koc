<?php
	header("Content-Type: text/plain");
	header("Content-Type: text/html; charset=utf-8");
	 
	$email = $_REQUEST['email'];
		
	include_once "../include/db_connect.php";						
	require_once "../PHPMailer/PHPMailerAutoload.php";
	
	
	// function token_rand($a)
	// {		
		// $token = str_pad(mt_rand(0,9999999999),10,'1');
// 		
		// $query = "SELECT * FROM koc_play.account WHERE token = '".$token."'" or die("Error in the consult.." . mysqli_error($connect));
		// $result =  $connect->query($query);
		// $i =0;
// 		
		// while($row = mysqli_fetch_array($result)) {
		// $i++;
		// }		
	    // if($i>0)
		// token_rand($token);
		// else 
	    // return $token;
	// }
	
	
			
	while($row = mysqli_fetch_array($result)) {
		$pid = $row['pid'];
		$name = $row['name'];
	}
	
	$token = str_pad(mt_rand(0,9999999999),10,'1');
	
	$query = "SELECT * FROM koc_account.account WHERE id = '".$email."'" or die("Error in the consult.." . mysqli_error($connect));
	$result =  $connect->query($query);
	
	while($row = mysqli_fetch_array($result)) {
		$pid = $row['pid'];
		$name = $row['name'];
	}	
	
	
	
	$query = "UPDATE koc_account.account SET token=".$token." WHERE pid = ".$pid or die("Error in the consult.." . mysqli_error($connect));
	$result =  $connect->query($query);
		
	date_default_timezone_set('asia/seoul');
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 2;
	
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	
	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';
	
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "koc_help@anticgame.co.kr";
	
	//Password to use for SMTP authentication
	$mail->Password = "oliver888";
	
	//Set who the message is to be sent from
	$mail->setFrom('koc_help@anticgame.co.kr', 'Anticgame');
	
	//Set an alternative reply-to address
	$mail->addReplyTo('koc_help@anticgame.co.kr', 'Anticgame');
	
	//Set who the message is to be sent to
	$mail->addAddress($email, $email);
	
	//Set the subject line
	$mail->Subject = '[우주의기사] 비밀번호 변경 안내입니다.';
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML("안녕하세요. ".$name."님<br>
					새로운 비밀번호로 변경하시려면 아래의 링크를 눌러주세요. 이 메일은 24시간 동안만 유효합니다.. 
					<br><br> 
					<a href='http://".$_SERVER['SERVER_NAME']."/koc10100/application/views/webview/passwd_change_pass_input.php?token=$token&id=$pid' target='_blank'>
					우주의 기사 패스워드 변경하러 가기</a>
					<br><br>				
					해당 메일은 발신전용 입니다. 본인이 요청하지 않으셨거나 다른 문의사항이 있으시면, koc_help@anticgame.co.kr로 문의 바랍니다.<br>
					감사합니다.
					");
	
	//Replace the plain text body with one created manually
	$mail->AltBody = 'Thank you. please click this URL.';
	
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	//send the message, check for errors
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "Message sent!";
	}
?>



