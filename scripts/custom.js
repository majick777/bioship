
var menuslugs = new Array();
jQuery('#menu-main-menu li a').each(function() {
	menuslug = jQuery(this).html().toLowerCase().replace('!', '');
	menuid = menuslug+'-menu-button';
	jQuery(this).attr('id', menuid);
	menuslugs[menuslugs.length] = menuslug;

	jQuery(this).on('click', function() {
		menuslug = jQuery(this).html().toLowerCase().replace('!', '');
		for (i in menuslugs) {
			if (menuslugs[i] == menuslug) {$('#'+menuslugs[i]+'-menu-button').parent().addClass('current-menu-item');}
			else {$('#'+menuslugs[i]+'-menu-button').parent().removeClass('current-menu-item');}
			if (menuslugs[i] != menuslug) {$('#'+menuslugs[i]+'-tab-content').fadeOut(250);}
		}
		for (i in menuslugs) {
			if (menuslugs[i] == menuslug) {
				jQuery('#'+menuslug+'-tab-content').fadeIn(500);
			}
		}

	});
});