<script>
function loading( onOff )
{
	if( onOff == "ON" )
	{
    	document.getElementById("layerMask").style.display = 'block';
    	document.getElementById("aniLoding").style.display = 'block';
    }
    else
    {
    	document.getElementById("layerMask").style.display = 'none';
    	document.getElementById("aniLoding").style.display = 'none';    
    }    
}
</script>

<!-- loding start -->
<div class="layerMask" id="layerMask"></div>
<div class="aniLoding" id="aniLoding"><img src="/template/static/images/lodingING.gif" alt="로딩중"><br>잠시만 기다려 주세요.</div>
<!-- loding end -->    