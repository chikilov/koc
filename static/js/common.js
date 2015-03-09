function getLimitType( limit_type ){
	var limit_text = [0, 1, 2];
	return limit_text[limit_type];
}

/*
* datepicker UI
* selector :: $( ".datepicker" )
*/
function datepickerUi(){
	$( ".datepicker" ).datepicker({
		showOn: "button",
		buttonImage: window.location.toString().substr(0, window.location.toString().indexOf("index")) + "/static/images/calendar.png",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		width:'30px'
	});
}

/*
* Tab UI
* selector :: $( ".tabArea" )
*/
function tabUi (){
	$('.tabArea').each(function(i){
		var $tabConArea = $(this).children('.tabConArea').children('li'),
		$tabMenu = $(this).children('.tabMenu'),
		$menuUl = $tabMenu.children('ul'),
		$menuLi = $menuUl.children('li');
		$menuLi.click(function(i){
			var idx = $(this).index();
			$menuLi.removeClass('on');
			$menuLi.eq(idx).addClass('on');
			$tabConArea.hide();
			$tabConArea.eq(idx).show();
			return false;
		});
	});
}

function unique(array) {
    return $.grep(array, function(el, index) {
        return index == $.inArray(el, array);
    });
}

$(document).ready(function(){
	/*
	* fn 실행
	*/
	if($( ".datepicker" )){	datepickerUi();	};
	if($('.tabArea')){ tabUi();};

	$('.layerPop').find('.close').click(function(){
		$(this).parent().hide();
	});
});

$(document).ajaxComplete(function () {
	if($( ".datepicker" )){	datepickerUi();	};
})

