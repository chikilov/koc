<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8" />
<title>이메일 입력받기</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />
<!-- Add jQuery library -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

 </head>
 <body style="background-color: #000000">
 
   <div id="wrap" style="width:360px; height:355px; margin:auto; background-image:url(images/bg_01.jpg)">

     <div id="header" style="height:53px; margin:auto; background-color:#101010; " align="center"><img src="images/koc_logo.png" width="100" height="53"></div>
     <div id="main" style="height:302px;">
        <div id="top_main" style="height:65px;margin: 20px">
	       <div id="header_text" style="text-align:center; font-size:0.9em; color:#ffffff;padding:20px;">비밀번호 변경 및 찾을 아이디(이메일)를 입력 후<br>인증하기 버튼을 클릭해 주시기 바랍니다.
	       </div>        
		</div>
         
       
       <div style="text-align:center;"> <input type="text" id="email" style="text-align:center;font-size:1.1em; height:40px;width:250px;:center; color:#111111;border-radius: 5px 5px 5px 5px;" placeholder="이메일을 입력하세요"></div> 
       
       <div id="blank_02" style="height:34px;"></div>      
       
       <div id="send_btn" style="height:46px; " align="center"><img id ="send_img"src="images/send.gif">     
       </div>            
     </div>


      <div id="footer">
      </div>

   </div>

 

 </body>

<script>
$(document).ready(function(){
	
});

$("#send_img").click(function(){
		$("#header_text").text("잠시만요");		
		var email = $("#email");
		
		var regEmail = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
       	
       	
       	if( !email.val()){
            $("#header_text").text('이메일주소를 입력 해 주세요');
            email.focus();
            return false;
        } 
        else if(!regEmail.test($.trim(email.val()))) {
                $("#header_text").text('이메일 주소가 유효하지 않습니다');
                email.focus();
                return false;
            }
        
        else{        					
		$.ajax({								  
		   url: 'ajax/email_check.php',
		   type: 'POST',					   
		   data: {"email":$.trim(email.val())},
		   dataType: 'html',
		   success: function(data){
		   	if(data=="success"){
		   		$("#header_text").html("입력하신 이메일로 비밀번호 변경 인증 메일이 발송 중 입니다..<br>");
		   		$("#send_img").hide();
		   		$.ajax({								  
				   url: 'ajax/send_mail.php',
				   type: 'POST',					   
				   data: {"email":email.val()},
				   dataType: 'html',
				   success: function(data){
						$("#header_text").append("메일 발송이 완료 되었습니다..");										   					   					   
				   }								   
				});		   		
		   	}		   				   	 	
		   	else
		   		$("#header_text").html("잘못된 아이디(이메일)를 입력하셨습니다.<br>다시 확인 후 이용해 주시기 바랍니다.");
		   }								   
		});
		}		
	});
</script>

</html>									