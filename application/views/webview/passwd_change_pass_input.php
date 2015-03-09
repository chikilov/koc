
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>패스워드 입력하기</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />

<!-- Add jQuery library -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body style="background-color:#000000">

<div id="wrap" style="width:360px; height:355px; margin:auto; background-image:url(images/bg_02.jpg)">

 <div id="header" style="height:53px; margin:auto; background-color:#101010; " align="center"><img src="images/koc_logo.png" width="100" height="53"></div>
 <div id="main" style="height:302px;">
    <div id="top_main" style="height:65px;margin: 20px">
       <div id="header_text" style="text-align:center; font-size:0.9em; color:#ffffff;padding-top:30px;">변경 할 비밀번호를 입력해 주세요
       </div>        
	</div>
     
          
   <div style="text-align:center; margin-bottom:10px"> <input type="password" id="pwd" style="text-align:center;font-size:1em; height:30px;width:250px;:center; color:#111111;border-radius:5px 5px 5px 5px;" placeholder="변경 할 비밀번호 입력"></div>
   <div style="text-align:center;"> <input type="password" id="pwd1" style="text-align:center;font-size:1em; height:30px;width:250px;:center; color:#111111;border-radius:5px 5px 5px 5px;" placeholder="비밀번호 확인"></div> 
   
   <div id="blank_02" style="height:34px;"></div>      
   
   <div id="send_btn" style="height:46px; " align="center">
   	<span><img id ="send_img" src="images/btn_ok.gif"></span>
   	<span><img onclick="javascript:var win = window.open('','_self');win.close();return false;" src="images/btn_cancel.gif"></span>     
   </div>            
 </div>




										
</body>

<?php
	$pid = $_REQUEST['id'];
	$token = $_REQUEST['token'];
?>

<script>
$(document).ready(function(){
	
});

$("#send_img").click(function(){
	
		var pwd = $("#pwd").val();
		var pwd1 = $("#pwd1").val();
		
		var pid = '<?=$pid?>';
		var token = '<?=$token?>';
		
		if(!pwd)
			{
				$("#header_text").text("패스워드를 입력하세요.");
				$("#pwd").focus();
			}
		else if(!pwd1){
				$("#header_text").text("비밀번호를 잘못입력하였습니다.");
				$("#pwd1").focus();		
		}	
		
		else if( pwd != pwd1 ) 
			{	//	비밀번호 와 비밀번호 확인이 다르다면 ...
				$("#header_text").text("비밀번호를 다르게 입력하였습니다.");
				$("#pwd").focus();
			}					
		else
			{				
			$.ajax({								  
			   url: 'ajax/pass_change.php',
			   type: 'POST',					   
			   data: {"pid":pid, "token":token,"pwd":pwd},
			   dataType: 'html',
			   success: function(data){
			   	if(data=="success"){
			   		$("#header_text").text("패스워드가 변경되었습니다..");
			   		$("#send_img").hide();			   		
			   	}		   				   	 	
			   	else
			   		$("#header_text").html("잘못된 정보를 가지고 있습니다.<br> 패스워드 찾기를 새로 진행하세요.");
				}								   
			});		
			}
	});
	
	
</script>

</html>									