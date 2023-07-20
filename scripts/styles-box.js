/* === BioShip Styles Box Script === */
// 2.2.0: moved to separate file from muscle.php

// 2.2.0: check element before applying attributes
jQuery(document).ready(function() {
	if (document.getElementById('wp-admin-bar-theme-styles')) {
		stylelink = document.getElementById('wp-admin-bar-theme-styles').firstElementChild;
		stylelink.setAttribute('onclick', 'return bioship_toggle_theme_style_box();');
		stylelink.href = 'javacript:void(0);';
	}
});

/* --- toggle theme style box --- */
// 2.2.0: maybe trigger code editor initialization
function bioship_toggle_theme_style_box() {
	if (document.getElementById('theme-styles-box').style.display == 'none') {
		if (typeof bioship_style_editor_init == 'function') {bioship_style_editor_init();}
		document.getElementById('theme-styles-box').style.display = '';
		position = document.getElementById('theme-styles-position').value;
		bioship_shift_style_editor(position);
	} else {document.getElementById('theme-styles-box').style.display = 'none';}
	return false;
}

/* --- change style type --- */
// 2.2.0: show/hide textarea container instead of textarea
// 2.2.0: maybe initialize code editor for second textarea
function bioship_toggle_theme_style_type(type) {
	if (type == 'theme') {
		document.getElementById('theme-styles-type-theme').checked = '1';
		displaya = ''; displayb = 'none';
	}
	if (type == 'post') {
		document.getElementById('theme-styles-type-post').checked = '1';
		displaya = 'none'; displayb = '';
	}
	if (typeof bioship_style_editor_init == 'function') {bioship_style_editor_init();}
	document.getElementById('theme-styles-label').style.display = displaya;
	document.getElementById('theme-styles-label-post').style.display = displayb;
	document.getElementById('theme-styles-input').style.display = displaya;
	document.getElementById('theme-styles-input-post').style.display = displayb;
	document.getElementById('theme-styles-modified-label').style.display = displaya;
	document.getElementById('theme-styles-modified-post-label').style.display = displayb;
}

/* --- shift style editor position --- */
// 2.2.0: maybe trigger code editor refresh
function bioship_shift_style_editor(position) {
	adminmenu = document.getElementById('adminmenuwrap');
	editorbox = document.getElementById('theme-styles-box');
	editorbox.className = position;
	document.getElementById('theme-styles-position').value = position;
	labelsdiv = document.getElementById('theme-styles-labels');
	inputsdiv = document.getElementById('theme-styles-inputs');
	savediv = document.getElementById('theme-styles-save');
	themetextarea = document.getElementById('theme-styles-textarea');
	posttextarea = document.getElementById('theme-styles-textarea-post');
	if ( (position == 'top') || (position == 'bottom') ) {
		editorbox.style.width = '';
		editorbox.style.height = document.getElementById('theme-styles-height').value;
		inputwidth = editorbox.offsetWidth - labelsdiv.offsetWidth - savediv.offsetWidth - 150;
		if (adminmenu) {
			adminmenuwidth = parseInt(adminmenu.offsetWidth);
			inputwidth = inputwidth - adminmenuwidth;
			document.getElementById('theme-styles-wrapper').style.marginLeft = adminmenuwidth+'px';
		}
		inputheight = parseInt(document.getElementById('theme-styles-height').value);
		inputsdiv.style.width = themetextarea.style.width = inputwidth+'px';
		inputsdiv.style.height = themetextarea.style.height = inputheight+'px';
		if (posttextarea) {
			posttextarea.style.height = inputheight+'px';
			posttextarea.style.width = inputwidth+'px';
		}
	}
	if ( (position == 'left') || (position == 'right') ) {
		editorbox.style.height = '';
		editorbox.style.width = document.getElementById('theme-styles-width').value;
		if (adminmenu) {
			if (position == 'left') {adminmenuwidth = parseInt(adminmenu.offsetWidth);}
			else {adminmenuwidth = 0;}
			document.getElementById('theme-styles-wrapper').style.marginLeft = adminmenuwidth+'px';
		}
		inputwidth = parseInt(document.getElementById('theme-styles-width').value);
		inputheight = editorbox.offsetHeight - labelsdiv.offsetHeight - savediv.offsetHeight - 150;
		inputsdiv.style.width = themetextarea.style.width = inputwidth+'px';
		inputsdiv.style.height = themetextarea.style.height = inputheight+'px';
		if (posttextarea) {
			posttextarea.style.width = inputwidth+'px';
			posttextarea.style.height = inputheight+'px';
		}
	}
	if (typeof bioship_style_editor_refresh == 'function') {bioship_style_editor_refresh();}
}

/* --- change box width or height --- */
// note: value set / saved is actually textarea width/height
// 2.2.0: maybe trigger code editor refresh
function bioship_resize_style_editor(widthheight, plusminus) {
	widthstep = heightstep = 30;
	position = document.getElementById('theme-styles-position');
	editorbox = document.getElementById('theme-styles-box');
	inputsdiv = document.getElementById('theme-styles-inputs');
	themetextarea = document.getElementById('theme-styles-textarea');
	posttextarea = document.getElementById('theme-styles-textarea-post');
	if (widthheight == 'width') {
		inputwidth = parseInt(document.getElementById('theme-styles-width').value);
		if (plusminus == 'plus') {newwidth = inputwidth + widthstep;}
		if (plusminus == 'minus') {
			newwidth = inputwidth - widthstep;
			if (newwidth < 200) {newwidth = 200;}
		}
		document.getElementById('theme-styles-width').value = newwidth;
		inputsdiv.style.width = themetextarea.style.width = newwidth+'px';
		if (posttextarea) {posttextarea.style.width = newwidth+'px';}
		editorbox.style.width = (newwidth+20)+'px';
	}
	if (widthheight == 'height') {
		inputheight = parseInt(document.getElementById('theme-styles-height').value);
		if (plusminus == 'plus') {newheight = inputheight + heightstep;}
		if (plusminus == 'minus') {
			newheight = inputheight - heightstep;
			if (newheight < 150) {newheight = 150;}
		}
		document.getElementById('theme-styles-height').value = newheight;
		inputsdiv.style.height = themetextarea.style.height = newheight+'px';
		if (posttextarea) {posttextarea.style.height = newheight+'px';}
		if (position == 'top') {editorbox.style.height = (newheight+80)+'px';}
		if (position == 'bottom') {editorbox.style.height = (newheight+40)+'px';}
	}
	if (typeof bioship_style_editor_refresh == 'function') {bioship_style_editor_refresh();}
}

/* --- check for modified styles --- */
function bioship_check_textarea(type) {
	id = 'theme-styles-textarea'; mid = 'theme-styles-modified';
	if (type == 'post') {id += '-post'; mid += '-post';}
	original = document.getElementById(id+'-original').value;
	console.log('Original ('+id+'-original): '+original);
	if (document.getElementById('theme-styles-type-post') && (document.getElementById('theme-styles-type-post').checked == '1')) {
		if (jQuery('#theme-styles-input-post .CodeMirror').length) {
			styles = jQuery('#theme-styles-input-post .CodeMirror')[0].CodeMirror.getValue();
		} else {styles = document.getElementById(id).value;}
	} else {
		if (jQuery('#theme-styles-input .CodeMirror').length) {
			styles = jQuery('#theme-styles-input .CodeMirror')[0].CodeMirror.getValue();
		} else {styles = document.getElementById(id).value;}
	}
	console.log('New: '+styles);
	if (styles == original) {
		document.getElementById(id+'-modified').style.display = 'none';
		document.getElementById(mid).style.display = 'none';
		window.onbeforeunload = null;
	} else {
		document.getElementById(id+'-modified').style.display = '';
		document.getElementById(mid).style.display = '';
		window.onbeforeunload = function() {return true;};
	}
}

/* --- show styles saved message --- */
// 2.2.0: fix to post modified id (-post-modified not -modified-post)
function bioship_quicksave_styles(type) {
	var quicksaveid;
	id = 'theme-styles-textarea-modified'; mid = 'theme-styles-modified';
	if (type == 'theme') {quicksaveid = 'quicksavesaved';}
	if (type == 'admin') {quicksaveid = 'adminquicksavesaved';}
	if (type == 'post') {quicksaveid = 'postquicksavesaved'; id = 'theme-styles-textarea-post-modified'; mid += '-post';}
	document.getElementById(id).style.display = 'none';
	document.getElementById(mid).style.display = 'none';
	document.getElementById(quicksaveid).style.display = '';
	setTimeout(function() {
		if (typeof jQuery == 'function') {jQuery('#'+quicksaveid).fadeOut(5000);}
		else {document.getElementById(quicksaveid).style.display = 'none';}
	}, 5000);
	window.onbeforeunload = null;
}

/* Style Editor Init */
function bioship_style_editor_init() {
	setTimeout(function() {
		if (document.getElementById('theme-styles-type-post') && (document.getElementById('theme-styles-type-post').checked == '1')) {
			if (jQuery('#theme-styles-input-post .CodeMirror').length) {bioship_style_editor_refresh();}
			else {
				wp.codeEditor.initialize(jQuery('#theme-styles-textarea-post'), freestyler_style_settings);
				jQuery('#theme-styles-textarea-post-original').val(jQuery('#theme-styles-input-post .CodeMirror')[0].CodeMirror.getValue());
				jQuery('#theme-styles-input-post .CodeMirror .CodeMirror-code').keyup(function() {bioship_check_textarea('post');});
			}
		} else {
			if (jQuery('#theme-styles-input .CodeMirror').length) {bioship_style_editor_refresh();}
			else {
				wp.codeEditor.initialize(jQuery('#theme-styles-textarea'), freestyler_style_settings);
				jQuery('#theme-styles-textarea-original').val(jQuery('#theme-styles-input .CodeMirror')[0].CodeMirror.getValue());
				jQuery('#theme-styles-input .CodeMirror .CodeMirror-code').keyup(function() {bioship_check_textarea('');});
			}
		}
	}, 500);
}



/* Style Editor Refresh */
function bioship_style_editor_refresh() {
	if (document.getElementById('theme-styles-type-post') && (document.getElementById('theme-styles-type-post').checked == '1')) {
		if (jQuery('#theme-styles-input-post .CodeMirror').length) {jQuery('#theme-styles-input-post .CodeMirror')[0].CodeMirror.refresh();}
	} else {
		if (jQuery('#theme-styles-input .CodeMirror').length) {jQuery('#theme-styles-input .CodeMirror')[0].CodeMirror.refresh();}
	}
}
