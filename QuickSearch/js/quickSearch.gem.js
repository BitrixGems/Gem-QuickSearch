function ShowAjaxWaitAnimation(selector){
    var elem = $(selector).get(0);
    if(!elem) {
	return;
    }
    jsAjaxUtil.ShowLocalWaitWindow(elem.id, elem, true);
}

function HideAjaxWaitAnimation(selector){
    var elem = $(selector).get(0);
    if(!elem) {
	return;
    }
    jsAjaxUtil.CloseLocalWaitWindow(elem.id, elem);
}

function BitrixGem_quickSearch_toggleSwitch( elem ){
	if( $('.bitrixgems_quickSearch').length > 0 ){
		$('.bitrixgems_quickSearch').remove();
	}else{
		var offset = $(elem).offset();
		$('body').after(
			'<div class="bx-core-window bx-core-dialog bitrixgems_quickSearch" style="left: '+500+'px !important; top: '+(offset.top+30)+'px !important ;">\
				<form method="get" action="" id="quick-search-form">\
				<div class="dialog-center"><div class="bitrixgems_quickSearch_inner bx-core-dialog-content"><div class="bx-core-dialog-head"><div class="bx-core-dialog-head-content head-block">\
					Поиск: <input type="text" name="bitrixgems_quickSearch_search" class="bitrixgems_quickSearch_search"><input type="submit" class="bitrixgems_quickSearch_search_search" value="Найти!"/>\
				</div></div>\
				<div class=" bitrixgems_quickSearch_result bx-core-dialog-content"></div>\
				</div></div>\
				<div class="dialog-head"><div class="l"><div class="r"><div class="c"><span>Быстрый поиск</span></div></div></div></div>\
				<div class="dialog-head-icons"><a class="bx-icon-close" title="Закрыть" onclick="$(\'.bitrixgems_quickSearch\').remove();"></a></div>\
				<div class="dialog-foot"><div class="l"><div class="r"><div class="c"><img height="1" border="0" width="90%" style="position: absolute; top: 0pt; left: 0pt;" src="/bitrix/js/main/core/images/line.png"></div></div></div></div>\
				</form>\
			</div>'
		);
		$('#quick-search-form').submit(function(e){
			e.preventDefault();
			e.stopPropagation();
			var queryString = $('.bitrixgems_quickSearch_search').val();
			if( $.trim( queryString ) == '' ) return;
			$('.bitrixgems_quickSearch_result').hide();
			ShowAjaxWaitAnimation('.bitrixgems_quickSearch');
			$('.bitrixgems_quickSearch_result')
			    .load(
				'/bitrix/admin/bitrixgems_simpleresponder.php?gem=QuickSearch&AJAXREQUEST=Y&queryString=' + encodeURI(queryString),
				function(){
				    HideAjaxWaitAnimation('.bitrixgems_quickSearch');
				    $(".bitrixgems_quickSearch_result").show()
				}
			    );
		})

	}

}
