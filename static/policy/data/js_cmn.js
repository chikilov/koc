//<![CDATA[
//document.domain = 'pupugame.com';

// 폼 체크 함수
function IsEmpty( _data ) {
	if ( _data.length < 1 )	{
		return true;
	}
	return false;
}

// input 다음 칸으로 focus 넘기기. 칸수 , this.id , 다음 id값
function nextBlank(N, Obj, nextID) {
	if(document.getElementById(Obj).value.length == N) {
		document.getElementById(nextID).focus();
	}
}


//이미지 사이즈 조절 폭이 614px보다 크면 614px로 자동 줄이기
//이미지 사이즈 조절 폭이 614px보다 크면 614px로 자동 줄이기

function content_img_resize() {
	var contentBox=document.getElementById("contentArea");
	var contentImg=contentBox.getElementsByTagName("IMG");
	var noChk	= "arrow-down-";

	for(var i=0; i<contentImg.length; i++) {
		var img = new Image();
		img.src = contentBox.getElementsByTagName("IMG")[i].src;
		if(img.src.indexOf(noChk) < 0){
			$(contentBox.getElementsByTagName("IMG")[i]).css({
				width:"100%"
			});	
		}	
	}
}


/*모바일 전환 상태 확인*/
var mql = window.matchMedia("(orientation: portrait)");

// 리스너에 등록할 수도 있다.
mql.addListener(function (m) {
    if (m.matches) {
		content_img_resize_device_width();  
    }
    else {
		content_img_resize_device_width();
    }
});
/*모바일 전환 상태 확인*/

function content_img_resize_device_width() {
	var contentBox=document.getElementById("contentArea");
	var contentImg=contentBox.getElementsByTagName("IMG");
	var noChk	= "arrow-down-";
	
	var width	= (window.innerWidth > 0) ? window.innerWidth : screen.width;	
	var height	= (window.innerHeight > 0) ? window.innerHeight : screen.height;	
	
	for(var i=0; i<contentImg.length; i++) {
		var img = new Image();
		var img_width	= "95%";
		var img_height	= "auto"; 
		img.src = contentBox.getElementsByTagName("IMG")[i].src;

		if(width > height){
			if(img.height > img.width){
				var img_width	= "auto";
				var img_height	= height+"px"; 	
			}
		}

		//alert(img.width+"__"+img.height);
		if(img.src.indexOf(noChk) < 0){
			$(contentBox.getElementsByTagName("IMG")[i]).css({
				width	:	img_width,
				height	:	img_height
			});	
		}	
	}
}

function content_img_resize_chk(obj) {

	var contentBox=document.getElementById(obj);
	var windowWidth = $(window).width();
	
	if(obj == 'contentArea'){
		var contentImg=contentBox.getElementsByTagName("IMG");
		if(contentImg != null){
			for(var i=0; i<contentImg.length; i++) {
				var img = new Image();
				img.src = contentBox.getElementsByTagName("IMG")[i].src;
				if(img.width>windowWidth) {
					$(contentBox.getElementsByTagName("IMG")[i]).css({
						width:"100%"
						/*"width":"100%",
						"max-width":"750px",
						"height":windowWidth*3/4+"px",
						"max-height":"560px"*/
					});		
				}
					
			}
		}
	}
	else{
		var contentIframe=contentBox.getElementsByTagName("IFRAME");
	 
		for(var i=0; i<contentIframe.length; i++) {
			$(contentBox.getElementsByTagName("IFRAME")[i]).css({
				"width":"100%",
				"max-width":"750px",
				"height":windowWidth*3/4+"px",
				"max-height":"560px"
			});		
				
		}
		
		var contentEmbed=contentBox.getElementsByTagName("EMBED");
	 
		for(var i=0; i<contentEmbed.length; i++) {
			$(contentBox.getElementsByTagName("EMBED")[i]).css({
				"width":"100%",
				"max-width":"750px",
				"height":windowWidth*3/4+"px",
				"max-height":"560px"
			});		
				
		}
	}
}


//가이드 전용. 이미지 사이즈 조절 폭이 694px보다 크면 694px로 자동 줄이기
function guide_img_resize() {
	var contentBox=document.getElementById("contentArea");
	var contentImg=contentBox.getElementsByTagName("IMG");
 
	for(var i=0; i<contentImg.length; i++) {
		var img = new Image();
		img.src = contentBox.getElementsByTagName("IMG")[i].src;
	 
		if(img.width>694) {
			contentBox.getElementsByTagName("IMG")[i].width=694;
		}
	}
}



function popup_openchk(url, name, option) {
	var objPopup = window.open(url, name, option); 
	
	if (objPopup == null) {
		alert("팝업이 차단 되어 있습니다.\n브라우저 설정에서 팝업 설정을 차단 해제 하거나 허용 사이트에 푸푸게임을 추가해 주시기 바랍니다."); 
		return false;
	} else {
		return true;
	}
}

function getCookie(Name) { 
  var search = Name + "="; 
  if (document.cookie.length > 0) {                    // if there are any cookies 
    offset = document.cookie.indexOf(search); 
    if (offset != -1){                                              // if cookie exists 
        offset += search.length;                            // set index of beginning of value 
        end = document.cookie.indexOf(";", offset);  // set index of end of cookie value 
        if (end == -1) 
          end = document.cookie.length; 
        return unescape(document.cookie.substring(offset, end)); 
    } 
  } 
} 

// 로그인 창 스크립트
function CheckLogin() {
	var f = document.login_form;

	if ( f.userid.value == "" ) {
		alert( "아이디를 입력해 주십시오." );
		f.userid.focus();
		return;
	}
	if ( f.password.value == "" ) {
		alert( "비밀번호를 입력해 주십시오." );
		f.password.focus();
		return;
	}

	f.submit();
}


// 메인 공지 탭
function main_noticetab(tabnum){
	var i;
	var d = new Array(4);
	var tm = document.getElementById("tabmenu").getElementsByTagName("a");
	for(i=0; i<=3; i++){
		
  d[i] = document.getElementById("tabcontent"+i);
  d[i].style.display = "none";
  tm[i].className = "";
	};
	
  
  switch(tabnum){
   case 0:
    d[0].style.display = "";
	tm[0].className = "on";
    break;
   case 1:
    d[1].style.display = "";
	tm[1].className = "on";
    break;
	
	case 2:
    d[2].style.display = "";
	tm[2].className = "on";
    break;

		case 3:
    d[3].style.display = "";
	tm[3].className = "on";
    break;

  };
};



//트위터 게시하기
function twitter(msg,url) {
	msg = "[푸푸게임] " + msg;
	
	var href = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(msg) + "&url=" + encodeURIComponent(url);
	var objPopup = window.open(href, 'twitter', 'width=680,height=450'); 
	
	if (objPopup == null) {
		alert("팝업이 차단 되어 있습니다.\n브라우저 설정에서 팝업 설정을 차단 해제 하거나 허용 사이트에 푸푸게임을 추가해 주시기 바랍니다."); 
		return false;
	} else {
		objPopup.focus();
		return true;
	}
}


//페이스북 게시하기
function facebook(msg,url) {	
	msg = "[푸푸게임] " + msg;
	
	var href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent(url) + "&t=" + encodeURIComponent(msg);
	var objPopup = window.open(href, 'facebook', 'width=680,height=450'); 

	if (objPopup == null) {
		alert("팝업이 차단 되어 있습니다.\n브라우저 설정에서 팝업 설정을 차단 해제 하거나 허용 사이트에 푸푸게임을 추가해 주시기 바랍니다."); 
		return false;
	} else {
		objPopup.focus();
		return true;
	}
}
 
//미투데이 게시하기
function Me2Day(msg,url) {
	var tag = " 웹게임 푸푸게임 미녀 섹시"
	msg = "[푸푸게임] " + msg;
	
	var href = "http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent(msg) + " " + encodeURIComponent(url) + "&new_post[tags]=" + encodeURIComponent(tag);
	var objPopup = window.open(href, 'me2Day', ''); 

	if (objPopup == null) {
		alert("팝업이 차단 되어 있습니다.\n브라우저 설정에서 팝업 설정을 차단 해제 하거나 허용 사이트에 푸푸게임을 추가해 주시기 바랍니다."); 
		return false;
	} else {
		objPopup.focus();
		return true;
	}
}
 
//다음 요즘 게시하기
function YozmDaum(link,prefix,parameter) {
	var href = "http://yozm.daum.net/api/popup/prePost?link=" + encodeURIComponent(link) + "&prefix=" + encodeURIComponent(prefix) + "&parameter=" + encodeURIComponent(parameter);
	var objPopup = window.open(href, 'yozmSend', 'width=466, height=356'); 
 
	if (objPopup == null) {
		alert("팝업이 차단 되어 있습니다.\n브라우저 설정에서 팝업 설정을 차단 해제 하거나 허용 사이트에 푸푸게임을 추가해 주시기 바랍니다."); 
		return false;
	} else {
		objPopup.focus();
		return true;
	}
}

function main_banner_call() {
	$(function() {
		if(imgCnt > 1){
			var direction = 'left';
		} else {
			var direction = 'none';
		}
		$("#main_banner").jQBanner({
			nWidth:90,							
			nHeight:90,						
			nCount:imgCnt,								
			isActType:direction,				
			nOrderNo:1,									
			isStartAct:"Y",
			isStartDelay:"N",
			nDelay:3000,
			isBtnType:"img"
			}
		);
	});
}

function getParameterByName( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}   

var consigneeCode = getParameterByName("pupu_layerpop");


function pupu_show_layerpop(msg,_width,_height,_top)
{

	var _width = "350";
	var _height = "200";
	
	document.getElementById("win_reg").style.display="";
	document.getElementById("cover_reg").style.width=document.getElementById("pupu_body").scrollWidth+"px"; 
	document.getElementById("cover_reg").style.height = $(document).height()+"px"; 
	document.getElementById("cover_reg").style.display=""; 
	bb=true;
	if(consigneeCode == null || consigneeCode === ""){
		consigneeCode = "pupugame";
	}
	var obj = document.getElementById('iframe_select_server_reg');
	
	if ( obj ) obj.src = '/api_mobile/alarm_window.html?msg='+msg;
	if ( obj ) obj.width = _width+"px";
	if ( obj ) obj.height = _height+"px";
	
	
	var topScroll = 0;

	if (document.documentElement && document.documentElement.scrollWidth) {
		xScroll = document.documentElement.scrollWidth;
		yScroll = document.documentElement.scrollHeight;
	} else if (document.body.scrollWidth) {
		xScroll =  document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	}
	
	topScroll = (yScroll / 2) - 50;
	document.getElementById("win_reg").style.top = topScroll+"px"; 	
	document.getElementById("win_reg").focus();
	
	//if ( _top ) document.getElementById("win_reg").style.top = _top+"px"; 
	
}



function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}


function goLocation(url) {
	location.href=url;
}


function PUPU_main_div()
{
	try {
		// Layer popup
			isObj = '<div id="cover_reg" style="position:absolute; top:0px; left:0px; min-height:450px; display:none; background:#000000;-moz-opacity:0.7; opacity: 0.7;filter:alpha(opacity=70); z-index: 1200;"></div>'
						+ '<div style="display:none; position:absolute;z-index:199999;background:transparent;margin-left:-170px; left: 50%; top: 0px; min-width: 300px; min-height: 300px;top:150px;" id="win_reg" >'
						+ '<iframe id="iframe_select_server_reg" name="iframe_select_server_reg" src="" width="100%" height="100%" style="margin:0px;" allowTransparency="true" frameborder="0" scrolling="no"></iframe>'
						+ '</div>';
		
		document.getElementsByTagName('body')[0].id = 'pupu_body';
		document.getElementById('pupu_global_gnb').innerHTML = isObj;

	}catch(e){}
}
  

// api.pupugame.com 용 Google Analytics.
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37389769-2', 'pupugame.com');
  ga('send', 'pageview');

  
  
//]]>