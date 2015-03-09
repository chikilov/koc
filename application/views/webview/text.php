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
				
	var email = $("#email");
	        					
	$.ajax({								  
			   url: 'ajax/rand.php',
			   type: 'POST',					   
			   data: {"email":email.val()},
			   dataType: 'html',
			   success: function(data){
					$("#header_text").text(data);										   					   					   
			   }								   
			});
	}		
});
</script>

</html>									