<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === MUSCLE  FUNCTIONS ===
 * ...Extending Wordpress...
 *
**/

// cannot be called directly
if (!function_exists('add_action')) {exit;}

// ----------------------------
// === muscle.php Structure ===
// ----------------------------
// = Metabox Overrides =
//
// = Muscle Theme Options =
// - MISC -
// - Scripts -
// - Extras -
// - Thumbnails -
// - Reading -
// - Excerpt -
// - Read More -
// - Writing -
// - RSS -
// - Admin -
//
// = Integrations =
// - WooCommerce -
// - Open Graph Protocol Framework -
// - Hybrid Hook -
// - Foundation -
// - Theme My Login -

// other integration notes:
// - AJAX Load More (/templates/ajax-load-more/)
// - Theme Test Drive (various)
// - WP Subtitle (in skeleton.php)
// - WP PageNavi (in skeleton.php)


// =========================
// --- MetaBox Overrides ---
// =========================
// applies the various PerPost Theme Options
// 1.8.0: metabox interface moved to admin.php

// Get PerPost Display Overrides
// -----------------------------
// 1.8.0: rename from muscle_get_overrides and now for displays only
if (!function_exists('muscle_get_display_overrides')) {
 function muscle_get_display_overrides($vresource) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemedisplay;

	// 1.8.0: removed options tab as only needed for admin display
	// 1.8.0: moved content filters to a separate function
	// 1.8.0: removed perpoststyles from overrides, now retrieved separately

	// TODO: archive overrides via custom post types/panel?
	if (is_numeric($vresource)) {$vthemedisplay = get_post_meta($vresource,'_displayoverrides',true);}
	else {$vthemedisplay = array();}

	// 1.8.5: added wrapper, headernav, footernav, breadcrumb, pagenavi
	// TODO: sitelogo, sitetitle, sitedesc?
	$vdisplaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);

	// 1.8.0: convert old display overrides to single meta array
	if ( (is_numeric($vresource)) && ( ($vthemedisplay == '') || (!is_array($vthemedisplay)) ) ) {
		$vthemedisplay = array();
		foreach ($vdisplaykeys as $vdisplaykey) {
			// 1.9.8: fix from vpostid to vresource variable
			$vthemedisplay[$vdisplaykey] = get_post_meta($vresource,'_hide'.$vdisplaykey,true);
			if ( (!$vthemedisplay[$vdisplaykey]) || ($vthemedisplay[$vdisplaykey] == '') ) {$voverride[$vdisplaykey] = '0';}
		}
		delete_post_meta($vresource,'_displayoverrides');
		add_post_meta($vresource,'_displayoverrides',$vthemedisplay,true);
	}
	else {
		// fix for any empty values to avoid undefined index warnings
		foreach ($vdisplaykeys as $vdisplaykey) {
			if (!isset($vthemedisplay[$vdisplaykey])) {$vthemedisplay[$vdisplaykey] = '0';}
		}
	}

	// TODO: change this filter name to muscle_display_overrides
	$vthemedisplay = skeleton_apply_filters('muscle_perpage_overrides',$vthemedisplay);

	if (THEMEDEBUG) {echo '<!-- Display Overrides: '; print_r($vthemedisplay); echo ' -->';}

	return $vthemedisplay;
 }
}

// Get PerPost Templating Overrides
// --------------------------------
// 1.9.5: added separate function for templating overrides
if (!function_exists('muscle_get_templating_overrides')) {
 function muscle_get_templating_overrides($vresource) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemeoverride;

	// TODO: archive overrides via custom post type?
	if (is_numeric($vresource)) {$vthemeoverride = get_post_meta($vresource,'_templatingoverrides',true);}
	else {$vthemeoverride = array();}

	// note: output override keys (not implemented)
	// $voverridekeys = array(
	//	'wrapper'. 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
	//	'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
	//	'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	// );

	$voverridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);

	// fix for any empty values to avoid undefined index warnings
	foreach ($voverridekeys as $voverridekey) {
		if (!isset($vthemeoverride[$voverridekey])) {$vthemeoverride[$voverridekey] = '';}
	}

	// check thumbnail size force off option
	// 1.9.8: fix to undefined vpostid variable
	if (get_post_meta($vresource,'_thumbnailsize',true) == 'off') {$vthemeoverride['image'] == 'off';}

	// TODO: add filter example to filters.php
	$vthemeoverride = skeleton_apply_filters('muscle_templating_overrides',$vthemeoverride);

	if (THEMEDEBUG) {echo '<!-- Templating Overrides: '; print_r($vthemeoverride); echo ' -->';}

	return $vthemeoverride;
 }
}


// Output PerPost Override Styles
// ------------------------------
if (!is_admin()) {
	if (!function_exists('muscle_perpage_override_styles')) {
	 if ($vthemesettings['themecssmode'] == 'footer') {add_action('wp_footer','muscle_perpage_override_styles');}
	 else {add_action('wp_head','muscle_perpage_override_styles');}
	 function muscle_perpage_override_styles() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

		global $vthemedisplay;

		// 1.8.5: set global value via muscle_get_display_overrides
		// 1.9.5: removed post check as global already set
		// 1.9.5: just set short name for vthemedisplay global
		$voverride = $vthemedisplay; $vstyles = '';

		// Full Width Container Override
		// -----------------------------
		// 1.8.5: added full width container option (no wrap margins)
		// 1.9.8: fix to override key from fullwidth to wrapper
		if ($voverride['wrapper'] == '1') {$vstyles .= '#wrap.container {width: 100% !important;}'.PHP_EOL;}

		// Main Theme Areas
		// ----------------
		if ($voverride['header'] == '1') {$vstyles .= "#header {display:none !important;}".PHP_EOL;}
		if ($voverride['footer'] == '1') {$vstyles .= "#footer {display:none !important;}".PHP_EOL;}

		// Navigation
		// ----------
		if ($voverride['navigation'] == '1') {$vstyles .= "#navigation {display:none !important;}".PHP_EOL;}
		if ($voverride['secondarynav'] == '1') {$vstyles .= "#secondarymenu {display:none !important;}".PHP_EOL;}
		if ($voverride['headernav'] == '1') {$vstyles .= "#header #headermenu {display:none !important;}".PHP_EOL;}
		if ($voverride['footernav'] == '1') {$vstyles .= "#footer #footermenu {display:none !important;}".PHP_EOL;}

		// Sidebars
		// --------
		// 1.8.0: Sidebar hides removed here as handled by templating
		// 1.9.5: Changed back this is really for actually hiding not for templating
		// 1.9.5: apply individual sidebar hide conditional filters here
		$vhidesidebar = skeleton_apply_filters('skeleton_sidebar_hide',false);
		$vhidesubsidebar = skeleton_apply_filters('skeleton_subsidebar_hide',false);
		if ( ($vhidesidebar) || ($voverride['sidebar'] == '1') ) {$vstyles .= "#sidebar {display:none !important;}".PHP_EOL;}
		if ( ($vhidesubsidebar) || ($voverride['subsidebar'] == '1') ) {$vstyles .= "#subsidebar {display:none !important;}".PHP_EOL;}

		// Widget Areas
		// ------------
		if ($voverride['headerwidgets'] == '1') {$vstyles .= "#header-widget-area {display:none !important;}".PHP_EOL;}
		if ($voverride['footerwidgets'] == '1') {$vstyles .= "#sidebar-footer {display:none !important;}".PHP_EOL;}
		// 1.9.5: re-added individual footer display overrides
		if ($voverride['footer1'] == '1') {$vstyles .= "#footer-widget-area-1 {display:none !important;}".PHP_EOL;}
		if ($voverride['footer2'] == '1') {$vstyles .= "#footer-widget-area-2 {display:none !important;}".PHP_EOL;}
		if ($voverride['footer3'] == '1') {$vstyles .= "#footer-widget-area-3 {display:none !important;}".PHP_EOL;}
		if ($voverride['footer4'] == '1') {$vstyles .= "#footer-widget-area-4 {display:none !important;}".PHP_EOL;}

		// Content Areas
		// -------------
		// 1.9.5: separated display and templating override for thumbnail
		if ($voverride['image']) {$vstyles .= "div.thumbnail img {display:none !important;}";}
		if ($voverride['breadcrumb'] == '1') {$vstyles .= "#content #breadcrumb {display:none !important;}".PHP_EOL;}
		if ($voverride['title'] == '1') {$vstyles .= "#content .entry-title {display:none !important;}".PHP_EOL;}
		if ($voverride['subtitle'] == '1') {$vstyles .= "#content .entry-subtitle {display:none !important;}".PHP_EOL;}
		if ($voverride['metatop'] == '1') {$vstyles .= "#content .entry-meta {display:none !important;}".PHP_EOL;}
		if ($voverride['metabottom'] == '1') {$vstyles .= "#content .entry-utility {display:none !important;}".PHP_EOL;}
		if ($voverride['authorbio'] == '1') {$vstyles .= "#content .entry-author {display:none !important;}".PHP_EOL;}
		if ($voverride['pagenavi'] == '1') {$vstyles .= "#content #nav-below {display:none !important;}".PHP_EOL;}

		// PerPost Styles
		// --------------
		// 1.9.5: moved singular post check to here
		// TODO: get archive overrides from custom post type?
		if (is_singular()) {
			global $post; $vpostid = $post->ID;
			$vperpoststyles = get_post_meta($vpostid,'_perpoststyles',true);
		} else {$vperpoststyles = '';}

		if ( ($vperpoststyles) && ($vperpoststyles != '') ) {$vstyles .= $vperpoststyles.PHP_EOL;}

		if ($vstyles != '') {echo '<style>'.$vstyles.'</style>';}
	}
 }
}

// PerPost Thumbnail Size Filter
// -----------------------------
add_filter('skeleton_post_thumbnail_size','muscle_thumbnail_size_perpost');
if (!function_exists('muscle_thumbnail_size_perpost')) {
 function muscle_thumbnail_size_perpost($vsize) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $post; if ( (!isset($post)) || (!is_object($post)) ) {return $vsize;}
	$vthumbsize = get_post_meta($post->ID,'_thumbnailsize',true);
	// TODO: maybe double check thumbnail size still available before using it?
	// $vthumbsizes = array_merge(array('small','medium','large'),get_intermediate_image_sizes());
	if ( ($vthumbsize) && ($vthumbsize != '') ) {return $vthumbsize;}
	return $vsize;
 }
}

// Get Content Filter Overrides
// ----------------------------
// 1.8.0: new function to just get filter overrides
if (!function_exists('muscle_get_content_filter_overrides')) {
 function muscle_get_content_filter_overrides($vpostid) {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.9.5: fix to remove filters metakey (previously _disablefilters)
	$vremovefilters = get_post_meta($vpostid,'_removefilters',true);

	// 1.8.0: maybe convert to single filter meta array
	if ( ($vremovefilters == '') || (!is_array($vremovefilters)) ) {
		$vremovefilters = array();
		$vfilters = array('wpautop','wptexturize','convertsmilies','convertchars');
		foreach ($vfilters as $vfilter) {
			$vremovefilters[$vfilter] = get_post_meta($vpostid, '_disable'.$vfilter, true);
			delete_post_meta($vpostid,'_disable'.$vfilter);
		}
		delete_post_meta($vpostid,'_removefilters');
		add_post_meta($vpostid,'_removefilters',$vremovefilters,true);
	}

	// 1.8.0: added this conditional filter
	$vremovefilters = skeleton_apply_filters('muscle_content_filter_overrides',$vremovefilters);
	return $vremovefilters;
 }
}


// maybe Remove Content Filters
// ----------------------------
// wpautop, wptexturize, convert_smilies, convert_chars
if (!function_exists('muscle_remove_content_filters')) {
 function muscle_remove_content_filters($vcontent) {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $post; if ( (!isset($post)) || (!is_object($post)) ) {return $vcontent;}
	$vpostid = $post->ID; $vremove = muscle_get_content_filter_overrides($vpostid);

	if ( (isset($vremove['wpautop'])) && ($vremove['wpautop'] == '1') ) {remove_filter('the_content','wpautop');}
	if ( (isset($vremove['wptexturize'])) && ($vremove['wptexturize'] == '1') ) {remove_filter('the_content','wptexturize');}
	if ( (isset($vremove['convertsmilies'])) && ($vremove['convertsmilies'] == '1') ) {remove_filter('the_content','convert_smilies');}
	if ( (isset($vremove['convertchars'])) && ($vremove['convertchars'] == '1') ) {remove_filter('the_content','convert_chars');}

	return $vcontent;
 }
 // run this filter before others to maybe remove the filters
 add_filter('the_content','muscle_remove_content_filters',9);
}


// ------------
// === MISC ===
// ------------

// maybe Change default Gravatar
// -----------------------------
// eg. /wp-content/child-theme/images/avatar.png
if (!function_exists('muscle_default_gravatar')) {
 add_filter('avatar_defaults', 'muscle_default_gravatar');
 function muscle_default_gravatar($vdefaults) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs;
	if ($vthemesettings['gravatarurl'] != '') {
		$vavatar = $vthemesettings['gravatarurl'];
		$vdefaults[$vavatar] = 'avatar';
	}
	else {
		$vavatar = skeleton_file_hierarchy('url','gravatar.png',$vthemedirs['img']);
		if ($vavatar) {$vdefaults[$vavatar] = 'avatar';}
	}
	// TODO: get the image size and pass to skeleton_comments_avatar_size filter?
	return $vdefaults;
 }
}

// Discreet Text Widget
// --------------------
// most super useful widget, especially when used with shortcodes
// (so that if the shortcode returns empty the widget is not displayed)
// ref: https://wordpress.org/plugins/hackadelic-discreet-text-widget/
// 1.8.5: removed option, always on by default
if (!function_exists('muscle_discreet_text_widget')) {
 add_action('widgets_init', 'muscle_discreet_text_widget');
 function muscle_discreet_text_widget() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	// 1.9.8: added class check (for no conflict with content sidebars plugin)
	if (!class_exists('DiscreetTextWidget')) {
		class DiscreetTextWidget extends WP_Widget_Text {
			function __construct() {
				$vwidgetops = array('classname' => 'discreet_text_widget', 'description' => __('Arbitrary text or HTML, only shown if not empty.','bioship'));
				$vcontrolops = array('width' => 400, 'height' => 350);
				// 1.9.8: fix to deprecated class construction method
				call_user_func(array(get_parent_class(get_parent_class($this)), '__construct'), 'discrete_text', __('Discreet Text','csidebars'), $vwidgetops, $vcontrolops);
				// parent::__construct('discrete_text', __('Discreet Text','bioship'), $vwidgetops, $vcontrolops);
				// $this->WP_Widget('discrete_text', __('Discreet Text','bioship'), $vwidgetops, $vcontrolops);
			}
			function widget($vargs,$vinstance) {
				// 1.9.8: removed usage of extract here
				// extract($vargs, EXTR_SKIP);
				$vtext = skeleton_apply_filters('widget_text', $vinstance['text']);
				if (empty($vtext)) {return;}

				echo $vargs['before_widget'];
				$vtitle = skeleton_apply_filters('widget_title', empty($vinstance['title']) ? '' : $vinstance['title']);
				if (!empty($vtitle)) {echo $vargs['before_title'].$vtitle.$vargs['after_title'];}
				echo '<div class="textwidget">';
				echo $vinstance['filter'] ? wpautop($vtext) : $vtext;
				echo '</div>';
				echo $vargs['after_widget'];
			}
		}
		return register_widget("DiscreetTextWidget");
	}
 }
}

// Fullscreen Video Background!
// ----------------------------
// What is this doing here? A client-abandoned feature. It works tho! :-)
// Currently works for YouTube videos only (TODO: could add Vimeo etc...)
// TODO: Could maybe add to Theme Options? Currently loaded via filters only...
// (see filters.php example): muscle_videobackground_type,
// muscle_videobackground_id, muscle_videobackground_delay
$vvideobackground = '';
if (isset($vthemesettings['videobackground'])) {$vvideobackground = $vthemesettings['videobackground'];}
if (skeleton_apply_filters('muscle_videobackground_type',$vvideobackground) == 'youtube') {
	// 1.9.8: fix to function_exists check (missing argument)
	if (!function_exists('muscle_video_background')) {
	 add_action('skeleton_before_navbar','muscle_video_background');
	 function muscle_video_background() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		$vvideoid = skeleton_apply_filters('muscle_videobackground_id',$vthemesettings['videobackgroundid']);
		$vvideodelay = (int)skeleton_apply_filters('muscle_videobackground_delay',$vthemesettings['videobackgrounddelay']);
		if (!is_numeric($vvideodelay)) {$vvideodelay = 1000;}
		$vmaybe = array(); preg_match( "/[a-zA-Z0-9]+//", $vyoutubevideoid, $vmaybe);
		if ( ($vyoutubevideoid != '') && ($vyoutubevideoid == $vmaybe[0]) ) {
			echo '<div id="backgroundvideowrapper">';
			echo '<input type="hidden" id="videobackgroundoid" value="'.$vvideoid.'">';
			echo '<input type="hidden" id="videobackgrounddelay" value="'.$vvideodelay.'">';
			echo '<div id="backgroundvideo"></div></div>';
		}
	 }
	}
}


// ---------------
// === Scripts ===
// ---------------

// Internet Explorer Support Scripts
// ---------------------------------
// - Selectivizr (CSS3, for IE6 to IE8 inclusive)
// - HTML5 Shiv (HTML5, for less than IE9)
// - Supersleight (transparent PNGs, for IE6 and below)
// - Flexibiliity (flexbox support for IE8+9)
// 1.5.0: moved to skeleton_internet_explorer_scripts hook
// TODO: could be enqueued first and wrapped instead?
if (!function_exists('muscle_internet_explorer_scripts')) {
 add_action('wp_head','muscle_internet_explorer_scripts');
 function muscle_internet_explorer_scripts() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust;
	$viesupports = $vthemesettings['iesupports'];
	if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// Selectivizr CSS3
	// ----------------
	if ( (isset($viesupports['selectivizr'])) &&  ($viesupports['selectivizr'] == '1') ) {
		$vselectivizr = skeleton_file_hierarchy('both','selectivizr.min.js',$vthemedirs['js']);
		if (is_array($vselectivizr)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vselectivizr['file']));}
			echo '<!--[if (gte IE 6)&(lte IE 8)]><script type="text/javascript" src="'.$vselectivizr['url'].'?ver='.$vjscachebust.'"></script><![endif]-->';
		}
	}

	// HTML5 Shiv
	// ----------
	if ( (isset($viesupports['html5shiv'])) && ($viesupports['html5shiv'] == '1') ) {
		$vhtml5 = skeleton_file_hierarchy('both','html5.js',$vthemedirs['js']);
		if (is_array($vhtml5)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vhtml5['file']));}
			echo '<!--[if lt IE 9]><script src="'.$vhtml5['url'].'"></script><![endif]-->';
		}
	}

	// Supersleight
	// ------------
	if ( (isset($viesupports['supersleight'])) && ($viesupports['supersleight'] == '1') ) {
		$vsupersleight = skeleton_file_hierarchy('both','supersleight.js',$vthemedirs['js']);
		if (is_array($vsupersleight)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vsupersleight['file']));}
			echo '<!--[if lte IE 6]><script src="'.$vsupersleight['url'].'"></script><![endif]-->';
		}
	}

	// IE8 DOM
	// -------
	// 1.8.5: added IE8 DOM polyfill
	if ( (isset($viesupports['ie8'])) && ($viesupports['ie8'] == '1') ) {
		$vie8 = skeleton_file_hierarchy('both','ie8.js',$vthemedirs['js']);
		if (is_array($vie8)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vie8['file']));}
			echo '<!--[if IE 8]><script src="'.$vie8['url'].'"></script><![endif]-->';
		}
	}

	// Flexibility
	// -----------
	// 1.8.0: added flexbox polyfill
	if ( (isset($viesupports['flexibility'])) && ($viesupports['flexibility'] == '1') ) {
		$vflexibility = skeleton_file_hierarchy('both','flexibility.js',$vthemedirs['js']);
		if (is_array($vflexibility)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vflexibility['file']));}
			echo '<!--[if (IE 8)|(IE 9)]><script src="'.$vflexibility['url'].'"></script><![endif]-->';
		}
	}

 }
}

// PrefixFree
// ----------
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_load_prefixfree')) {
 add_action('wp_enqueue_scripts','muscle_load_prefixfree');
 function muscle_load_prefixfree() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vjscachebust, $vthemedirs; $vloadprefixfree = 0;
	if (isset($vthemesettings['prefixfree'])) {$vloadprefixfree = $vthemesettings['prefixfree'];}
	$vloadprefixfree = skeleton_apply_filters('muscle_load_prefixfree',$vloadprefixfree);
	if (!$vloadprefixfree) {return;}

	$vprefixfree = skeleton_file_hierarchy('both','prefixfree.js',$vthemedirs['js']);
	if (is_array($vprefixfree)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vprefixfree['file']));}
		wp_enqueue_script('prefixfree',$vprefixfree['url'],array(),$vjscachebust,true);

		// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (a "WTF" bug)
		// ref: http://stackoverflow.com/questions/25694456/google-fonts-giving-no-access-control-allow-origin-header-is-present-on-the-r
		// ref: http://wordpress.stackexchange.com/questions/176077/add-attribute-to-link-tag-thats-generated-through-wp-register-style
		// ref: https://github.com/LeaVerou/prefixfree/pull/39
		add_filter('style_loader_tag','muscle_fonts_noprefix_attribute', 10, 2 );
		if (!function_exists('muscle_fonte_no_prefix_attribute')) {
		 function muscle_fonts_noprefix_attribute($vlink, $vhandle) {
		 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			$vlinka = $vlink;
			// note: Google fonts style handles are 'heading-font-'x or 'custom-font-'x
			if ( (strstr($vhandle,'heading-font-')) || (strstr($vhandle,'custom-font-')) ) {
				$vlink = str_replace( '/>', 'data-noprefix />', $vlink);
			}
			else {
				// ...and a basic check for if the link is external to the site
				// as this problem could occur for other external sheets like this
				$vsitehost = $_SERVER['HTTP_HOST'];
				if ( ( (!stristr($vlink,$vsitehost)) && (stristr($vlink,'http://')) )
				  || ( (!stristr($vlink,$vsitehost)) && (stristr($vlink,'https://')) ) ) {
					$vlink = str_replace( '/>', 'data-noprefix />', $vlink);
				}
			}
			// if ($vlinka != $vlink) {echo '***'.$vlink.'---'.$vhandle.'***';} // debug point
			return $vlink;
		 }
		}
	}
 }
}

// NWWatcher Selector Javascript
// -----------------------------
// TODO: check themeoptions internally to allow filtering
if ($vthemesettings['nwwatcher'] == '1') {
	if (!function_exists('muscle_load_nwwatcher')) {
	 add_action('wp_enqueue_scripts','muscle_load_nwwatcher');
	 function muscle_load_nwwatcher() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $vthemedirs, $vjscachebust;

		$vnwwatcher = skeleton_file_hierarchy('both','nwwatcher.js',$vthemedirs['js']);
		if (is_array($vnwwatcher)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vnwwatcher['file']));}
			wp_enqueue_script('nwwatcher',$vnwwatcher['url'],array(),$vjscachebust,true);
		}
	 }
	}

	// NWEvents Event Manager (NWWatcher dependent)
	// --------------------------------------------
	if ($vthemesettings['nwevents'] == '1') {
		if (!function_exists('muscle_load_nwevents')) {
		 add_action('wp_enqueue_scripts','muscle_load_nwevents');
		 function muscle_load_nwevents() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
			global $vthemesettings, $vthemedirs, $vjscachebust;

			$vnwevents = skeleton_file_hierarchy('both','nwevents.js',$vthemedirs['js']);
			if (is_array($vnwevents)) {
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vprefixfree['file']));}
				wp_enqueue_script('nwevents',$vnwevents,array('nwwatcher'),$vjscachebust,true);
			}
		 }
		}
	}
}

// Media Queries Support
// ---------------------
// note: apparently for these, the 'best place is in the footer' - so not enqueued
// TODO: check themeoptions internally to allow filtering
if ($vthemesettings['mediaqueries'] != 'off') {
	if (!function_exists('muscle_media_queries_script')) {

	 add_action('wp_footer','muscle_media_queries_script');
	 function muscle_media_queries_script() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $vthemedirs, $vjscachebust;

		if ($vthemesettings['mediaqueries'] == 'respond') {
			$vrespond = skeleton_file_hierarchy('both','respond.min.js',$vthemedirs['js']);
			if (is_array($vrespond)) {
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vrespond['file']));}
				echo '<script type="text/javascript" src="'.$vrespond['url'].'?ver='.$vjscachebust.'"></script>';
			}
		}
		if ($vthemesettings['mediaqueries'] == 'mediaqueries') {
			$vmediaqueries = skeleton_file_hierarchy('both','css3-mediaqueries.js',$vthemedirs['js']);
			if (is_array($vmediaqueries)) {
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmediaqueries['file']));}
				echo '<script type="text/javascript" src="'.$vmediaqueries['url'].'?ver='.$vjscachebust.'"></script>';
			}
		}
	 }
	}
}

// Load FastClick
// --------------
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_load_fastclick')) {
 add_action('wp_enqueue_scripts','muscle_load_fastclick');
 function muscle_load_fastclick() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadfastclick = 0;
	if (isset($vthemesettings['loadfastclick'])) {$vloadfastclick = $vthemesettings['loadfastclick'];}
	$vloadfastclick = skeleton_apply_filters('muscle_load_fastclick',$vloadfastclick);
	if (!$vloadfastclick) {return;}

	// 1.8.5: adding missing filemtime cachebusting option
	$vfastclick = skeleton_file_hierarchy('both','fastclick.js',$vthemedirs['js']);
	if (is_array($vfastclick)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vfastclick['file']));}
		wp_enqueue_script('fastclick',$vfastclick['url'],array('jquery'),$vjscachebust,true);
	}
 }
}

// Load Mousewheel
// ---------------
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_load_mousewheel')) {
 add_action('wp_enqueue_scripts','muscle_load_mousewheel');
 function muscle_load_mousewheel() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadmouswheel = 0;
	if (isset($vthemesettings['loadmousewheel'])) {$vloadmouswheel = $vthemesettings['loadmousewheel'];}
	$vloadfastclick = skeleton_apply_filters('muscle_load_mousewheel',$vloadmouswheel);
	if (!$vloadmouswheel) {return;}

	// 1.9.0: fix to file hierarchy call (both not url)
	$vmousewheel = skeleton_file_hierarchy('both','mousewheel.js',$vthemedirs['js']);
	if (is_array($vmousewheel)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmousewheel['file']));}
		wp_enqueue_script('mousewheel',$vmousewheel['url'],array('jquery'),$vjscachebust,true);
	}
 }
}

// Load CSS.Supports
// -----------------
// TODO: check themeoptions internally to allow filtering
if ($vthemesettings['loadcsssupports'] == '1')  {
	if (!function_exists('muscle_load_csssupports')) {
	 add_action('wp_enqueue_scripts','muscle_load_csssupports');
	 function muscle_load_csssupports() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $vthemedirs, $vjscachebust;

		$vcsssupports = skeleton_file_hierarchy('url','CSS.supports.js',$vthemedirs['js']);
		if (is_array($vcsssupports)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vcsssupports['file']));}
			wp_enqueue_script('csssupports',$vcsssupports,array(),$vjscachebust,true);
		}
	 }
	}
}

// MatchMedia.js
// -------------
// TODO: check themeoptions internally to allow filtering?
if ($vthemesettings['loadmatchmedia'] == '1') {
	if (!function_exists('muscle_match_media_script')) {
	 add_action('wp_enqueue_scripts','muscle_match_media_script');
	 function muscle_match_media_script() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $vthemetoptions, $vthemedirs, $vjscachebust;

		// 1.9.5: fixed to file hierarchy call
		$vmatchmedia = skeleton_file_hierarchy('both','matchMedia.js',$vthemedirs['js']);
		if (is_array($vmatchmedia)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmatchmedia['file']));}
			wp_enqueue_script('matchmedia',$vmatchmedia['url'],array('jquery'),$vjscachebust,true);

			// 1.9.5: fixed to file hierarchy call
			$vmatchmedialistener = skeleton_file_hierarchy('both','matchMedia.addListener.js',$vthemedirs['js']);
			if (is_array($vmatchmedialistener)) {
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmatchmedialistener['file']));}
				wp_enqueue_script('matchmedialistener',$vmatchmedialistener['url'],array('jquery','matchmedia'),$vjscachebust,true);
			}
		}
	 }
	}
}

// Load Modernizr
// --------------
// TODO: check themeoptions internally to allow filtering
if ( ($vthemesettings['loadmodernizr'] != '') && ($vthemesettings['loadmodernizr'] != 'off') ) {
	add_action('wp_enqueue_scripts','muscle_load_modernizr');
}
if (!function_exists('muscle_load_modernizr')) {
 function muscle_load_modernizr() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vjscachebust;

	if ($vthemesettings['modernizer'] == 'production') {
		// with fallback to development version
		$vmodernizr = skeleton_file_hierarchy('both','modernizr.js',array('includes/foundation5/js/vendor','javascripts','js','assets/js'));
	}
	elseif ($vthemesettings['modernizer'] == 'development') {
		// with fallback to production version
		$vmodernizr = skeleton_file_hierarchy('both','modernizr.js',array('javascripts','includes/foundation5/js/vendor','js','assets/js'));
	}
	if (is_array($vmodernizr)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmodernizr['file']));}
		wp_enqueue_script('modernizr',$vmodernizr['url'],array('jquery'),$vjscachebust,true);
	}
 }
}

// --------------
// === Extras ===
// --------------

// Load Smooth Scrolling
// ---------------------
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_smooth_scrolling')) {
 add_action('wp_footer','muscle_smooth_scrolling');
 function muscle_smooth_scrolling() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vsmoothscrolling = 0;
	if (isset($vthemesettings['smoothscrolling'])) {$vsmoothscrolling = $vthemesettings['smoothscrolling'];}
	$vsmoothscrolling = skeleton_apply_filters('muscle_smooth_scrolling',$vsmoothscrolling);
	if (!$vsmoothscrolling) {return;}

	// adds a hidden input that is picked up by init.js
	echo "<input type='hidden' id='smoothscrolling' name='smoothscrolling' value='yes'>";
 }
}

// Load jQuery matchHeight
// -----------------------
// 1.9.9: added this for content grid (and other) usage
if (!function_exists('muscle_load_matchheight')) {
 add_action('wp_enqueue_scripts','muscle_load_matchheight');
 function muscle_load_matchheight() {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadmatchheight = 0;
	if (isset($vthemesettings['loadmatchheight'])) {$vloadmatchheight = $vthemesettings['loadmatchheight'];}
	$vloadmatchheight = skeleton_apply_filters('muscle_load_matchheight',$vloadmatchheight);
	if (!$vloadmatchheight) {return;}

	$vmatchheight = skeleton_file_hierarchy('both','jquery.matchHeight.js',$vthemedirs['js']);
	if (is_array($vmatchheight)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vmatchheight['file']));}
		wp_enqueue_script('matchheight',$vmatchheight['url'],array('jquery'),$vjscachebust,true);

		// add run trigger to footer
		add_action('wp_footer','muscle_run_matchheight');
		if (!function_exists('muscle_run_matchheight')) {
		 function muscle_run_matchheight() {
		 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
			echo "<input type='hidden' id='matchheight' name='matchheight' value='yes'>";
		 }
		}
	}
 }
}

// Load Sticky Kit
// ---------------
// 1.5.0: Added Sticky Kit Loading
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_load_stickykit')) {
 add_action('wp_enqueue_scripts','muscle_load_stickykit');
 function muscle_load_stickykit() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	// 1.8.5: seems to cause customizer some troubles
	// 1.9.9: add pagenow check for same reason
	global $pagenow;
	if ( ($pagenow == 'customizer.php') || (is_customize_preview()) ) {return;}

	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadstickykit = 0;
	if (isset($vthemesettings['loadstickykit'])) {$vloadstickykit = $vthemesettings['loadstickykit'];}
	// 1.9.9: fix to incorrect filter name typo
	$vloadstickykit = skeleton_apply_filters('muscle_load_stickykit',$vloadstickykit);
	if (!$vloadstickykit) {return;}

	$vstickykit = skeleton_file_hierarchy('both','jquery.sticky-kit.min.js',$vthemedirs['js']);
	if (is_array($vstickykit)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vstickykit['file']));}
		wp_enqueue_script('stickykit',$vstickykit['url'],array('jquery'),$vjscachebust,true);
	}

	if ($vthemesettings['stickyelements'] != '') {
		add_action('wp_footer','muscle_echo_sticky_elements');
		if (!function_exists('muscle_echo_sticky_elements')) {
			function muscle_echo_sticky_elements() {
				if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
				global $vthemesettings;
				$vstickyelements = skeleton_apply_filters('muscle_sticky_elements',$vthemesettings['stickyelements']);
				if (strstr($vstickyelements,',')) {
					$vstickyelementsarray = explode(',',$vstickyelements); $vi = 0;
					foreach ($vstickyelementsarray as $vstickyelementvalue) {
						$vstickyelementsarray[$vi] = trim($vstickyelementvalue); $vi++;
					}
					$vstickyelements = implode(',',$vstickyelementsarray);
				}
				else {$vstickyelements = trim($vstickyelements);}
				echo "<input type='hidden' id='stickyelements' name='stickyelements' value='".$vstickyelements."'>";
			}
		}
	}
 }
}

// Load FitVids
// ------------
// 1.8.5: check themeoptions internally to allow filtering

if (!function_exists('muscle_load_fitvids')) {
 add_action('wp_enqueue_scripts','muscle_load_fitvids');
 function muscle_load_fitvids() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadfitvids = 0;
	if (isset($vthemesettings['loadfitvids'])) {$vloadfitvids = $vthemesettings['loadfitvids'];}
	$vloadfitvids = skeleton_apply_filters('muscle_load_fitvids',$vloadfitvids);
	if (!$vloadfitvids) {return;}

	$vfitvids = skeleton_file_hierarchy('both','jquery.fitvids.js',$vthemedirs['js']);
	if (is_array($vfitvids)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vfitvids['file']));}
		wp_enqueue_script('fitvids',$vfitvids['url'],array('jquery'),$vjscachebust,true);
	}

	if ($vthemesettings['fitvidselements'] != '') {
		if (!function_exists('fitvids_elements')) {
		 add_action('wp_footer','muscle_echo_fitvids_elements');
		 function muscle_echo_fitvids_elements() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

			// 1.5.0: handle initializing for multiple page elements
			global $vthemesettings;
			$vfitvidselements = skeleton_apply_filters('muscle_fitvids_elements',$vthemesettings['fitvidselements']);
			if (strstr($vfitvidselements,',')) {
				$vfitvidsarray = explode(',',$vfitvidselements); $vi = 0;
				foreach ($vfitvidsarray as $vfitvidsvalue) {$vfitvidsarray[$vi] = trim($vfitvidsvalue); $vi++;}
				$vfitvidselements = implode(',',$vfitvidsarray);
			}
			else {$vfitvidselements = trim($vfitvidselements);}
			echo "<input type='hidden' id='fitvidselements' name='fitvidselements' value='".$vfitvidselements."'>";
		 }
		}
	}
 }
}

// Load ScrollToFixed
// ------------------
// 1.5.0: added Scroll To Fixed library
// 1.8.5: check themeoptions internally to allow filtering
if (!function_exists('muscle_load_scrolltofixed')) {
 add_action('wp_enqueue_scripts','muscle_load_scrolltofixed');
 function muscle_load_scrolltofixed() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vloadscrolltofixed = 0;
	if (isset($vthemesettings['loadscrolltofixed'])) {$vloadscrolltofixed = $vthemesettings['loadscrolltofixed'];}
	$vloadscrolltofixed = skeleton_apply_filters('muscle_load_scrolltofixed',$vloadscrolltofixed);
	if (!$vloadscrolltofixed) {return;}

	$vscrolltofixed = skeleton_file_hierarchy('both','jquery-scrolltofixed.min.js',$vthemedirs['js']);
	if (is_array($vscrolltofixed)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi',filemtime($vscrolltofixed['file']));}
		wp_enqueue_script('scrolltofixed',$vscrolltofixed['url'],array('jquery'),$vjscachebust,true);
	}
 }
}

// Logo Resize Switch
// ------------------
// 1.8.5: added this input switch for init.js
if (!function_exists('muscle_logo_resize')) {
 add_action('wp_footer','muscle_logo_resize');
 function muscle_logo_resize() {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings; $vlogoresize = 0;
    if (isset($vthemesettings['logoresize'])) {$vlogoresize = $vthemesettings['logoresize'];}
    $vlogoresize = skeleton_apply_filters('muscle_logo_resize',$vlogoresize);
    if (!$vlogoresize) {return;}
    echo '<input type="hidden" id="logoresize" name="logoresize" value="yes">';
 }
}


// ------------------
// === Thumbnails ===
// ------------------

// Allow thumbnail size override on upload for CPTs
// ------------------------------------------------
// (note: post type support for the CPT must be active via theme options)
// each filter must be explicity set, eg. muscle_custom_post_type_thumbsize_video
// Ref: http://wordpress.stackexchange.com/questions/6103/change-set-post-thumbnail-size-according-to-post-type-admin-page

if (!function_exists('muscle_thumbnail_size_custom')) {
 add_filter('intermediate_image_sizes_advanced','muscle_thumbnail_size_custom', 10);
 function muscle_thumbnail_size_custom($vsizes) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// rather funny way of doing it but seems to work fine
	// as this is for the admin post/page editing screen
	// TODO: check if this works for new posts though?
	$vposttype = get_post_type($_REQUEST['post_id']);

	// get default thumbnail size options (as in theme setup)
	global $vthemesettings;
	$vthumbnailwidth = skeleton_apply_filters('skeleton_thumbnail_width',250);
	$vthumbnailheight = skeleton_apply_filters('skeleton_thumbnail_height',250);
	$vcrop = get_option('thumbnail_crop');
	$vthumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ($vthumbnailcrop == 'nocrop') {$vcrop = false;}
	if ($vthumbnailcrop == 'auto') {$vcrop = true;}
	if (strstr($vthumbnailcrop,'-')) {$vcrop = explode('-',$vthumbnailcrop);}
	$vthumbsize = array($vthumbnailwidth, $vthumbnailheight, $vcrop);

	// now check for a custom filter for this post type
	if (has_filter('muscle_custom_post_type_thumbsize_'.$vposttype)) {
		$vnewthumbsize = skeleton_apply_filters('muscle_custom_post_type_thumbsize_'.$vposttype,$vthumbsize);
		if ( (is_numeric($vnewthumbsize[0])) && (is_numeric($vnewthumbsize[1])) ) {
			$vthumbsize = $vnewthumbsize;
		}
	}
	// set it explicitly whether default or changed
	$vsizes['post-thumbnail'] = array('width' => $vthumbsize[0], 'height' => $vthumbsize[1], 'crop' => $vthumbsize[2]);
    return $vsizes;
 }
}

// Fun with Fading Thumbnails
// --------------------------
// add_filter('the_posts','muscle_fading_thumbnails',10,2);
if (!function_exists('muscle_fading_thumbnails')) {
 function muscle_fading_thumbnails($posts,$query) {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	if (!is_archive()) {return $posts;}
	$cptslug = 'post'; $dosomethingcool = false;
	$posttypes = skeleton_get_post_types($query);
	if ( (is_array($posttypes)) && (in_array($cptslug,$posttypes)) ) {$dosomethingcool = true;}
	elseif ($cptslug == $posttypes) {$dosomethingcool = true;}

	if ($dosomethingcool) {
	    global $fadingthumbnails; $fadingthumbnails = $cptslug;
	    if (!had_action('wp_footer','muscle_fading_thumbnail_script')) {
	    	add_action('wp_footer','muscle_fading_thumbnail_script');
	    }
	}

	if (!function_exists('muscle_fading_thumbnail_script')) {
	 function muscle_fading_thumbnail_script() {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $fadingthumbnails;
		echo "<script>var thumbnailclass = 'img.thumbtype-".$fadingthumbnails."';
		function fadeoutthumbnails() {jQuery(thumbnailclass).fadeOut(3000,fadeinthumbnails);}
		function fadeinthumbnails() {jQuery(thumbnailclass).fadeIn(3000,fadeoutthumbnails);}
		jQuery(document).ready(function() {fadeoutthumbnails();});
	 	</script>";
	 }
	}

	return $posts;
 }
}

// ---------------
// === Reading ===
// ---------------

// Include/Exclude Categories from Home (Blog) Page
// ------------------------------------------------
if ($vthemesettings['homecategorymode'] != 'all') {
	if ( ($vthemesettings['homecategorymode'] == 'include') || ($vthemesettings['homecategorymode'] == 'exclude') || ($vthemesettings['homecategorymode'] == 'includeexclude') ) {

		// Selected Categories
		// -------------------
		if (!function_exists('muscle_select_home_categories')) {
		 add_filter('pre_get_posts','muscle_select_home_categories');
		 function muscle_select_home_categories($query) {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			if ($query->is_home()) {
				global $vthemesettings;

				// $vhomecategories = $vthemesettings['homecategories'];
				$vincludecategories = $vthemesettings['homeincludecategories'];
				$vexcludecategories = $vthemesettings['homeexcludecategories'];

				$vcategories = get_categories();

				foreach ($vcategories as $vcategory) {
					$vcatid = $vcategory->cat_ID;
					if ( ($vthemesettings['homecategorymode'] == 'include')
					  || ($vthemesettings['homecategorymode'] == 'includeexclude') ) {
						if (isset($vincludecategories[$vcatid])) {
							$vselected[] = $vcatid;
						}
					}

					if ( ($vthemesettings['homecategorymode'] == 'exclude')
					  || ($vthemesettings['homecategorymode'] == 'includeexclude') ) {
						if (isset($vexcludecategories[$vcatid])) {
							$vselected[] = '-'.$vcatid;
						}
					}
				}

				if (count($vselected) > 0) {
					$vcatstring = implode(' ',$vselected);
					$query->set('cat',$vcatstring);
				}
			}
			return $query;
		 }
	 	}
	}
}


// Number of Search Results per Page
// ---------------------------------
if (is_numeric($vthemesettings['searchresults'])) {
	add_action('pre_get_posts', 'muscle_search_results_per_page');
	if (!function_exists('muscle_search_results_per_page')) {
	 function muscle_search_results_per_page($vquery) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings, $wp_the_query;
		$vsearchresults = $vthemesettings['searchresults'];
		if ( (!is_admin()) && ($vquery === $wp_the_query) && ($vquery->is_search()) ) {
			$vquery->set('posts_per_page', $vsearchresults);
		}
		return $vquery;
	 }
	}
}

// Make Custom Post Types Searchable
// ---------------------------------
$vsearchablecpts = false;
if (is_array($vthemesettings['searchablecpts'])) {
	foreach ($vthemesettings['searchablecpts'] as $vcpt) {if ($vcpt == '1') {$vsearchablecpts = true;} }
}
if ($vsearchablecpts) {
	if (!function_exists('muscle_searchable_cpts')) {
	 function muscle_searchable_cpts($query) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings;
		foreach ($vthemesettings['searchablecpts'] as $vcpt) {
			if ($vcpt == '1') {$vsearchablecpts[] = $vcpt;}
		}
		if ($query->is_search) {$query->set('post_type', $vsearchablecpts);}
		return $query;
	 }
	 if (is_search()) {add_filter('the_search_query','muscle_searchable_cpts');}
	}
}

// Jetpack Infinite Scroll Support
// -------------------------------
// Jetpack Infinite Scroll info: http://jetpack.me/support/infinite-scroll/
// also could use AJAX Load More: https://wordpress.org/plugins/ajax-load-more/
// Loading Span selector: span.infinite-loader (default image: /images/infinite-loader.gif)
// Load More Button selector: div.infinite-handler (for click only, not scroll)
if ( ($vthemesettings['infinitescroll'] == 'scroll') || ($vthemesettings['infinitescroll'] == 'click') ) {
	if (!function_exists('muscle_jetpack_scroll_setup')) {
	 function muscle_jetpack_scroll_setup() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;
		$vtype = $vthemesettings['infinitescroll'];
		$vfootersidebars = $vthemesettings['footersidebars'];
		if ($vfootersidebars > 0) {$vfooterwidgets[0] = 'footer-widget-area-1';}
		if ($vfootersidebars > 1) {$vfooterwidgets[1] = 'footer-widget-area-2';}
		if ($vfootersidebars > 2) {$vfooterwidgets[2] = 'footer-widget-area-3';}
		if ($vfootersidebars > 3) {$vfooterwidgets[3] = 'footer-widget-area-4';}

		$vsettings = array(
			'type' => $vtype,
			'container' => 'content',
			'footer' => 'footer',
			'footer_widgets', $vfooterwidgets,
			'wrapper' => 'infinite-wrap',
			'render' => 'muscle_infinite_scroll_loop'
		);

		// 1.8.0: added override filters
		$vpostsperpage = skeleton_apply_filters('skeleton_infinite_scroll_numposts','');
		if (is_numeric($vpostsperpage)) {$vsettings['posts_per_page'] = $vpostsperpage;}
		$vsettings = skeleton_apply_filters('skeleton_infinite_scroll_settings',$vsettings);

		add_theme_support('infinite-scroll', $vsettings);
	 }
	}
	if (!function_exists('muscle_infinite_scroll_loop')) {
	 function muscle_infinite_scroll_loop() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// TODO: update this to match AJAX Load More Loop Template?
		while (have_posts()) {
			the_post();
			// 1.5.0: fix: always use hybrid content hierarchy
			hybrid_get_content_template();
		}
	 }
	 add_action('after_setup_theme', 'muscle_jetpack_scroll_setup');
	}
}


// -----------------------------
// === Excerpt and Read More ===
// -----------------------------

// Add Excerpt Support to Pages
// ----------------------------
// 1.8.0: add page excerpt support option
if ( (isset($vthemesettings['pageexcerpts'])) && ($vthemesettings['pageexcerpts'] == '1') ) {add_post_type_support('page', 'excerpt');}

// Enable Shortcodes in Excerpts
// -----------------------------
if ($vthemesettings['excerptshortcodes'] == '1') {
	// 1.9.8: very much "doing it wrong"! - replaced these filters...
	//	add_filter('the_excerpt','do_shortcode');
	//	add_filter('get_the_excerpt','do_shortcode');
	if (has_filter('get_the_excerpt','wp_trim_excerpt')) {
		remove_filter('get_the_excerpt','wp_trim_excerpt');
		add_filter('get_the_excerpt','muscle_excerpts_with_shortcodes');
	}
}

// Excerpts with Shortcodes
// ------------------------
// 1.9.8: copy of wp_trim_excerpt but with shortcodes kept
// note: formatting is still stripped but shortcode text remains
if (!function_exists('muscle_excerpts_with_shortcodes')) {
 function muscle_excerpts_with_shortcodes($text) {
	// for use in shortcodes to provide alternative output
	global $doingexcerpt; $doingexcerpt = true;

	$text = get_the_content('');
	// $text = strip_shortcodes( $text ); // modification
	$text = apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = apply_filters( 'excerpt_length', 55 );
	$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	$doingexcerpt = false; return $text;
 }
}

// User Defined Excerpt Length
// ---------------------------
// 1.8.5: move checks to inside filter
if (!function_exists('muscle_excerpt_length')) {
	add_filter('excerpt_length','muscle_excerpt_length');
	// old pseudonym
	if (!function_exists('skeleton_excerpt_length')) {
		function skeleton_excerpt_length($vlength) {return muscle_excerpt_length($vlength);}
	}
	function muscle_excerpt_length($vlength) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings;

		// 1.8.5: alternative feed excerpt length
		if (is_feed()) {
			if ( (isset($vthemesettings['rssexcerptlength'])) && ($vthemeoption['rssexcerptlength'] != '') ) {
				$vrssexcerptlength = abs(intval($vthemesettings['rssexcerptlength']));
				if ($vrssexcerptlength == 0) {return PHP_INT_MAX;}
				elseif ($vrssexcerptlength > 0) {return $vrssexcerptlength;}
			}
		}

		// 1.8.5: simplified and improved code here
		if ( (isset($vthemesettings['excerptlength'])) && ($vthemesettings['excerptlength'] != '') ) {
			$vexcerptlength = abs(intval($vthemesettings['excerptlength']));
			if ($vexcerptlength == 0) {return PHP_INT_MAX;}
			elseif ($vexcerptlength > 0) {return $vexcerptlength;}
		}

		return $vlength;
	}
}

// Read More Link
// --------------
// Default = 'Continue reading <span class="meta-nav">&rarr;</span>';
if ($vthemesettings['readmoreanchor'] != '') {
	if (!function_exists('skeleton_continue_reading_link')) {
	 function skeleton_continue_reading_link() {return muscle_continue_reading_link();}
	}
	if (!function_exists('muscle_continue_reading_link')) {
	 function muscle_continue_reading_link() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;
		return ' <a href="'.get_permalink().'">'.$vthemesettings['readmoreanchor'].'</a>';
	 }
	}
}

// Read More Before and After
// --------------------------
// Default = ' &hellip;';
if ($vthemesettings['readmorebefore'] != '') {
	if (!function_exists('skeleton_auto_excerpt_more')) {
	 function skeleton_auto_excerpt_more($vmore) {return muscle_auto_excerpt_more($vmore);}
	}
	if (!function_exists('muscle_auto_excerpt_more')) {
	 add_filter('excerpt_more','muscle_auto_excerpt_more');
	 function muscle_auto_excerpt_more($vmore) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings;
		if (function_exists('muscle_continue_reading_link')) {
			return '<div class="readmore">'.$vthemesettings['readmorebefore'].muscle_continue_reading_link().'</div>';
		}
		elseif (function_exists('skeleton_continue_reading_link')) {
			return '<div class="readmore">'.$vthemesettings['readmorebefore'].skeleton_continue_reading_link().'</div>';
		}
		else {
			$default = ' <a href="'.get_permalink().'">Continue reading <span class="meta-nav">&rarr;</span></a>';
			return '<div class="readmore">'.$vthemesettings['readmorebefore'].'</div>';
		}
	 }
	}
}

// Remove More 'Jump' Link
// -----------------------
// TODO: make this a theme option?
if (!function_exists( 'muscle_remove_more_jump_link')) {
 add_filter('the_content_more_link', 'muscle_remove_more_jump_link');
 function muscle_remove_more_jump_link($vlink) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$voffset = strpos($vlink, '#more-');
	if ($voffset) {$vend = strpos($link, '"',$voffset);}
	if ($vend) {$vlink = substr_replace($link,'',$voffset,$vend-$voffset);}
	return $vlink;
 }
}

// ---------------
// === Writing ===
// ---------------

// Limit Post Revisions
// --------------------
// 1.8.0: (deprecate) moved to separate AutoSave Net plugin
// 1.5.5: fixed: use filter over constant method
// if ( (is_numeric($vthemesettings['postrevisions'])) && ($vthemesettings['postrevisions'] > 0) ) {
//	if (!function_exists('muscle_limit_post_revisions')) {
//		function muscle_limit_post_revisions() {
//			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
//			global $vthemesettings;
//			// if (!defined('WP_POST_REVISIONS')) {define('WP_POST_REVISIONS', $vthemesettings['postrevisions']);}
// 			return $vthemesettings['postrevisions'];
//		}
//		add_filter('wp_revisions_to_keep','muscle_limit_post_revisions');
//	}
// }

// WP Subtitle Custom Post Type Support
// ------------------------------------
add_action('init', 'muscle_wp_subtitle_custom_support');
if (!function_exists('muscle_wp_subtitle_custom_support')) {
 function muscle_wp_subtitle_custom_support() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	if (function_exists('get_the_subtitle')) {
		global $vthemesettings;
		$vcptsubtitles = $vthemesettings['subtitlecpts'];
		foreach ($vcptsubtitles as $vcpt => $vvalue) {
			if ($vvalue) {
				if ( ($vcpt != 'post') && ($vcpt != 'page') ) {
					add_post_type_support($vcpt,'wps_subtitle');
				}
			}
			else {
				if ($vcpt == 'post') {remove_post_type_support('post','wps_subtitle');}
				if ($vcpt == 'page') {remove_post_type_support('page','wps_subtitle');}
			}
		}
	}
 }
}


// -----------------
// === RSS Feeds ===
// -----------------

// Automatic Feed Links
// --------------------
if ($vthemesettings['autofeedlinks'] == '1') {add_theme_support('automatic-feed-links');}
else {remove_theme_support('automatic-feed-links');}

// RSS Publish Delay
// -----------------
if ( (is_numeric($vthemesettings['rsspublishdelay'])) && ($vthemesettings['rsspublishdelay'] > 0) ) {
	if (!function_exists('muscle_delay_feed_publish')) {
	 add_filter('posts_where', 'muscle_delay_feed_publish');
	 function muscle_delay_feed_publish($where) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $wpdb, $vthemesettings;
		if (is_feed()) {
			$now = gmdate('Y-m-d H:i:s');
			$wait = $vthemesettings['rsspublishdelay'];
			// ref: http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
			$device = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
			// add SQL-sytax to default $where
			$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
		}
		return $where;
	 }
	}
}

// Define Post Types in RSS Feed
// -----------------------------
$vcptsinfeed = false;
if (is_array($vthemesettings['cptsinfeed'])) {
	if (THEMEDEBUG) {echo "<!-- RSS Feed Post Types: "; print_r($vthemesettings['cptsinfeed']); echo " -->";}
	if (count($vthemesettings['cptsinfeed']) > 0) {
 		foreach ($vthemesettings['cptsinfeed'] as $vcpt => $vvalue) {if ($vvalue == '1') {$vcptsinfeed = true;} }
 	}
}
if ($vcptsinfeed) {
	if (!function_exists('muscle_custom_feed_request')) {
	 function muscle_custom_feed_request($vars) {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		if (!is_feed()) {return $vars;}
		global $vthemesettings; $vcptsinfeed = array();
		if ( (isset($vars['feed'])) && (!isset($vars['post_type'])) ) {
			foreach ($vthemesettings['cptsinfeed'] as $vcpt => $vvalue) {
				if ($vvalue == '1') {$vcptsinfeed[] = $vcpt;}
			}
			if (THEMEDEBUG) {echo "<!-- RSS Feed Post Types: "; print_r($vcptsinfeed); echo " -->";}
			// TODO: recheck this function
			// $vars['post_type'] = $vcptsinfeed;
		}
		return $vars;
	 }
	 add_filter('request','muscle_custom_feed_request');
	}
}

// Full Content RSS Feed for Pages
// -------------------------------
// 1.8.5: added this option
// ref: http://wordpress.stackexchange.com/a/227455/76440

if ( (isset($vthemesettings['pagecontentfeeds'])) && ($vthemesettings['pagecontentfeeds'] == '1') ) {

	add_action('pre_get_posts', 'rss_page_feed_full_content');
	if (!function_exists('muscle_rss_page_feed_full_content')) {
 	 function rss_page_feed_full_content($vquery) {
 	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// Check if it feed request and for single page
		if ($vquery->is_main_query() && $vquery->is_feed() && $vquery>is_page()) {
			// set the post type to page
			$q->set('post_type', array('page'));
			// allow for page comments feed via ?withcomments=1
			if ( (isset($_GET['withcomments'])) && ($_GET['withcomments'] == '1') ) {return;}
			// set the comment feed to false
			$q->is_comment_feed = false;
		}
	 }
	}

	add_filter('pre_option_rss_use_excerpt', 'page_rss_excerpt_option');
    if (!function_exists('muscle_page_rss_excerpt_option')) {
	 function musclepage_rss_excerpt_option($voption) {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// force full content output for pages
		if (is_page()) {return '0';}
		return $voption;
	 }
	}

	// TODO: test strip_shortcode result on multiple installs
	// add_filter('the_excerpt_rss','muscle_rss_page_excerpt');
	// if (!function_exists('muscle_rss_page_excerpt')) {
	// function muscle_rss_page_excerpt($excerpt) {
	//	  if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	//    if (is_page()) {
	//        global $post; $text = $post->post_content;
	//        // removed this line otherwise got blank
	//        // $text = strip_shortcodes( $text );
	//        $text = skeleton_apply_filters( 'the_content', $text );
	//        $text = str_replace(']]>', ']]&gt;', $text);
	//        $excerpt_length = skeleton_apply_filters( 'excerpt_length', 55 );
	//        $excerpt_more = skeleton_apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	//        $excerpt = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	//    }
	//    return $excerpt;
	//  }
	// }

}


// Load the Dashboard Feed
// -----------------------
if (is_admin()) {
	if (!function_exists('muscle_add_bioship_dashboard_feed_widget')) {
		function muscle_add_bioship_dashboard_feed_widget() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
			global $wp_meta_boxes, $current_user;
			if ( (current_user_can('manage_options')) || (current_user_can('update_themes')) ) {
				// 1.9.9: fix to undefined index warning
				$vfeedloaded = false;
				foreach (array_keys($wp_meta_boxes['dashboard']['normal']['core']) as $vname) {
					if ($vname == 'bioship') {$vfeedloaded = true;}
				}
				if (!$vfeedloaded) {wp_add_dashboard_widget('bioship','BioShip News','muscle_bioship_dashboard_feed_widget');}
			}
		}

		$vrequesturi = $_SERVER['REQUEST_URI'];
		if ( (preg_match('|index.php|i', $vrequesturi))
		  || (substr($vrequesturi,-(strlen('/wp-admin/'))) == '/wp-admin/')
		  || (substr($vrequesturi,-(strlen('/wp-admin/network'))) == '/wp-admin/network/') ) {
			add_action('wp_dashboard_setup', 'muscle_add_bioship_dashboard_feed_widget');
		}
	}
}

// -----------------------------
// BioShip Dashboard Feed Widget
// -----------------------------
// 1.9.5: added displayupdates argument
if (!function_exists('muscle_bioship_dashboard_feed_widget')) {
 function muscle_bioship_dashboard_feed_widget($vdisplayupdates=true) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

	// Display Updates Available
	// -------------------------
	if (!function_exists('admin_theme_updates_available')) {
	  	$vadmin = skeleton_file_hierarchy('file','admin.php'); include_once($vadmin);
	}
	if ($vdisplayupdates) {echo admin_theme_updates_available();}

	// Load the News Feed
	// ------------------
	$vbaseurl = "http://bioship.space"; // theme news home
	$vrssurl = $vbaseurl."/feed/";
	// 1.8.0: set transient for daily feed update only
	$vfeed = trim(get_transient('bioship_feed'));
	if ( (!$vfeed) || ($vfeed == '') ) {
		echo "<!-- Fetching Feed -->";
		$vrssfeed = fetch_feed($vrssurl);
		$vfeeditems = 5;
		$vfeed = muscle_process_rss_feed($vrssfeed,$vfeeditems);
		if ($vfeed != '') {set_transient('bioship_feed',$vfeed,(24*60*60));}
	}
	if ($vfeed != '') {
		// 1.8.0: set link hover class
		echo "<style>.themefeedlink {text-decoration:none;} .themefeedlink:hover {text-decoration:underline;}</style>";
		echo "<div id='musclenewsdisplay'><b>Latest News</b><br>".$vfeed;
		// 1.8.5: fix to typo on close div ruining admin page
		echo "<div align='right'>&rarr;<a href='".$vbaseurl."/category/news/' class='themefeedlink' target=_blank> More News...</a></div></div>";
	} else {echo "Feed currently unavailable.<br>"; delete_transient('bioship_feed');}
 }
}

// Process RSS Feed
// ----------------
if (!function_exists('muscle_process_rss_feed')) {
 function muscle_process_rss_feed($vrss,$vfeeditems) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.0: fix to undefined index warning
	$vprocessed = '';

	if (is_wp_error($vrss)) {return '';}

	$vmaxitems = $vrss->get_item_quantity($vfeeditems);
	$vrssitems = $vrss->get_items(0,$vmaxitems);

	if (count($vrssitems) > 0) {
		$vprocessed = "<ul style='list-style:none;'>";
		foreach ($vrssitems as $vitem ) {
			$vprocessed .= "<li>&rarr; <a href='".esc_url($vitem->get_permalink())."' target='_blank' ";
			$vprocessed .= "title='Posted ".$vitem->get_date('j F Y | g:i a')."' class='themefeedlink'>";
			$vprocessed .= esc_html($vitem->get_title())."</a></li>";
		}
		$vprocessed .= "</ul>";
	}
	return $vprocessed;
 }
}


// -------------
// === Admin ===
// -------------


// Add Post Thumbnail Column to Post/Page list
// -------------------------------------------
// FIXME: do not think this function is working, but not a priority
if ( (isset($vthemesettings['adminthumbnailcolumn'])) && ($vthemesettings['adminthumbnailcolumn'] == '1') ) {
	if (!function_exists('muscle_admin_post_thumbnail_column')) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

		// TODO: Add a filter for thumbnail column use with other CPTs?
		// ...which would allow for post or page selection also...

		add_filter('manage_posts_columns','muscle_admin_post_thumbnail_column',5);
		// add_filter('manage_pages_columns','muscle_admin_post_thumbnail_column',5);

		function muscle_admin_post_thumbnail_column($cols){
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			$cols['post_thumb'] = __('Thumbnail','bioship'); return $cols;
		}

		add_action('manage_posts_custom_column','muscle_display_post_thumbnail_column',5,2);
		// TODO: check featured image support for pages here
		// add_action('manage_pages_custom_column','muscle_display_post_thumbnail_column',5,2);

		if (!function_exists('muscle_display_post_thumbnail_column')) {
		 function muscle_display_post_thumbnail_column($col,$id) {
			switch($col) {
				case 'post_thumb':
					echo the_post_thumbnail('admin-list-thumb');
			}
		 }
		}
	}
}

// Add "All Options" Page to Settings Menu
// ---------------------------------------
if ($vthemesettings['alloptionspage'] == '1') {
	if (!function_exists('muscle_all_options_link')) {
	 function muscle_all_options_link() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		add_options_page(__('All Options','bioship'), __('All Options','bioship'), 'manage_options', 'options.php');
	 }
	 add_action('admin_menu', 'muscle_all_options_link', 0);
	}
}

// Remove Update Notice
// --------------------
if ($vthemesettings['removeupdatenotice'] == '1') {
	if (!function_exists('muscle_remove_update_notice')) {
	 add_action('init','muscle_remove_update_notice');
	 function muscle_remove_update_notice() {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
	 	// 1.9.8: replaced deprecated function get_currentuserinfo
		global $current_user; wp_get_current_user();
		if (!current_user_can('update_plugins')) {
			add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
			add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
		}
	 }
	}
}

// Stop New User Notifications
// ---------------------------
if ($vthemesettings['disablenotifications'] == '1') {
	if (!function_exists('muscle_stop_new_user_notifications')) {
		add_action('phpmailer_init', 'muscle_stop_new_user_notifications');
		function muscle_stop_new_user_notifications() {
		 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

			global $phpmailer;
			if (is_multisite()) {
				$subject = 'New User Registration: ';
				if (substr($phpmailer->Subject,0,strlen($subject)) == $subject) {
					$phpmailer = new PHPMailer(true);
				}
			}
			else {
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$subject = array(
					sprintf(__('[%s] New User Registration'), $blogname),
					sprintf(__('[%s] Password Lost/Changed'), $blogname)
				);
				if (in_array($phpmailer->Subject,$subject)) {
					$phpmailer = new PHPMailer(true);
				}
			}
		}
	}
}

// Disable Self Pings
// ------------------
if ($vthemesettings['disableselfpings'] == '1') {
	if (!function_exists('muscle_disable_self_pings')) {
	 add_action('pre_ping','muscle_disable_self_pings');
	 // TODO: remove unneeded reference here?
	 function muscle_disable_self_pings(&$vlinks) {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	 	// 1.5.5: fix to use home_url for theme check
	 	$vhome = home_url(); // $vhome = get_option('home');
		foreach ($vlinks as $vi => $vlink) {if (0 === strpos($vlink,$vhome)) {unset($vlinks[$vi]);} }
	 }
	}
}

// Cleaner Admin Bar (remove WP links)
// -----------------------------------
if ($vthemesettings['cleaneradminbar'] == '1') {
	if (!function_exists('muscle_cleaner_adminbar')) {
	 add_action('wp_before_admin_bar_render','muscle_cleaner_adminbar');
	 function muscle_cleaner_adminbar() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		global $wp_admin_bar;

		// 1.8.0: added array filter for altering adminbar link removal
		$vremoveitems = array('wp-logo','about','wporg','documentation','support-forums','feedback');
		$vremoveitems = skeleton_apply_filters('admin_adminbar_remove_items',$vremoveitems);

		if (count($vremoveitems) > 0) {
			foreach ($vremoveitems as $vremoveitem) {$wp_admin_bar->remove_menu($vremoveitem);}
		}
	 }
	}
}

// Include CPTs in the Dashboard 'Right Now'
// -----------------------------------------
if ($vthemesettings['cptsrightnow'] == '1') {
	if (!function_exists('muscle_right_now_content_table_end')) {
	 function muscle_right_now_content_table_end() {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

		$args = array('public' => true,'_builtin' => false);
		$output = 'object';
		$operator = 'and';
		$post_types = get_post_types( $args , $output , $operator );
		foreach($post_types as $post_type) {
			$num_posts = wp_count_posts($post_type->name);
			$num = number_format_i18n($num_posts->publish);
			$text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
			if (current_user_can('edit_posts')) {
				$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
				$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
			}
			echo '<tr><td class="first num b b-' . $post_type->name . '">' . $num . '</td>';
			echo '<td class="text t ' . $post_type->name . '">' . $text . '</td></tr>';
		}
		$taxonomies = get_taxonomies( $args , $output , $operator );
		foreach ($taxonomies as $taxonomy) {
			$num_terms  = wp_count_terms($taxonomy->name);
			$num = number_format_i18n($num_terms);
			$text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ));
			if (current_user_can('manage_categories')) {
				$num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
				$text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
			}
			echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
			echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
		}
	 }
	 add_action('right_now_content_table_end','muscle_right_now_content_table_end');
	}
}

// Login Header URL
// ----------------
if (!function_exists('muscle_login_headerurl')) {
 add_filter('login_headerurl', 'muscle_login_headerurl' );
 function muscle_login_headerurl($vurl) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$vurl = site_url('/'); return $vurl;
 }
}

// Login Header Title
// ------------------
if (!function_exists('muscle_login_headertitle')) {
 add_filter('login_headertitle', 'muscle_login_headertitle');
 function muscle_login_headertitle($vtitle) {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$title = get_bloginfo('name'); return $vtitle;
 }
}


// Login Page Logo
// ---------------
if (!function_exists('muscle_login_styles')) {
 add_action('login_head', 'muscle_login_styles');
 function muscle_login_styles() {
	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

	// 1.8.5: fun with login wrapper hacks
	add_filter('login_body_class','muscle_login_body_hack',999);
	if (!function_exists('muscle_login_body_hack')) {
	 function muscle_login_body_hack($vclasses) {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$vclasses[] = 'LOGINWRAPPER';
		add_filter('attribute_escape', 'muscle_login_body_filter_hack',999,2);
		return $vclasses;
	 }
	}
	if (!function_exists('muscle_login_body_filter_hack')) {
	 function muscle_login_body_filter_hack($safe_text, $text) {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$replace = '"><div id="loginwrapper'; // "
		$safe_text = str_replace('LOGINWRAPPER',$replace,$safe_text);
		remove_filter('attribute_escape', 'muscle_login_body_filter_hack',999,2);
		return $safe_text;
	 }
	}
	add_action('login_footer','muscle_close_login_wrapper');
	if (!function_exists('muscle_close_login_wrapper')) {
	 function muscle_close_login_wrapper() {
	 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		skin_dynamic_login_css_inline();
		echo "</div><!-- /#loginwrapper -->";
	 }
	}
	// 1.8.5: moved actual styling to skin.php
 }
}



// ====================
// --- Integrations ---
// ====================

// -----------
// WooCommerce
// -----------

// WooCommerce Template Directory
// ------------------------------
// Changes hierarchy for Woocommerce templates (for both child and parent theme directories)
// intended so you could use:  /theme/theme-name/templates/woocommerce/
// instead of the default: /theme/theme-name/woocommerce/
// (as a better way of organizing 3rd party templates)

// WARNING: use one directory OR the other, it is not a hierarchy so you cannot use both!
// TODO: maybe could be a hierarchy by using template_includes filter?

// WooCommerce Template Path Filter
// --------------------------------
if (class_exists('WC_Template_Loader')) {
	add_filter('woocommerce_template_path','muscle_woocommerce_template_path');
	if (!function_exists('muscle_woocommerce_template_path')) {
		function muscle_woocommerce_template_path($vpath) {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			// 1.9.5: added this filter to allow further change
			// override woocommerce/ to (filtered) templates/woocommerce/
			$vnewpath = skeleton_apply_filters('skeleton_woocommerce_template_directory','templates/woocommerce/');
			global $vthemetemplatedir, $vthemestyledir;
			if ( (is_dir($vthemetemplatedir.$vnewpath)) || (is_dir($vthemestyledir.$vnewpath)) ) {
				// 1.9.5: only if new template directory exists do we apply other template filters
				add_filter('wc_get_template','muscle_woocommerce_template',10,5);
				add_filter('wc_get_template_part','muscle_woocommerce_template_part',10,3);
				return $vnewpath;
			}
			else {return $vpath;}
		}
	}
}

// /= Woocommerce Template subdirectories Templates =/
// ---------------------------------------------------
if (function_exists('wc_get_template')) {
	if (!function_exists('muscle_woocommerce_template')) {
		function muscle_woocommerce_template($located, $template_name, $args, $template_path, $default_path) {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// find the new template via file hierarchy
			// looking in templates/woocommerce/ then woocommerce/
			// 1.9.5: apply the template directory filter and search that only
			$vnewpath = skeleton_apply_filters('skeleton_woocommerce_template_directory','templates/woocommerce/');
			$vnewtemplate = skeleton_file_hierarchy('file',$template_name,array($vnewpath));

			// write debug info (kept here as useful for finding templates)
			// ob_start();
			// echo "new template: "; print_r($vnewtemplate); echo PHP_EOL;
			// echo "located: "; print_r($located); echo PHP_EOL;
			// echo "template_name: "; print_r($template_name); echo PHP_EOL;
			// $vdata = ob_get_contents(); ob_end_clean();
			// skeleton_write_debug_file('woo-templates.txt',$vdata);

			// return the new template location if found
			if ($vnewtemplate) {return $vnewtemplate;}

			return $located;
		}
	}
}

// Woocommerce Template Parts
// --------------------------
// eg. single-product-content.php and anything retrieved by wc_get_template_part
if (function_exists('wc_get_template_part')) {
 	if (!function_exists('muscle_woocommerce_template_part')) {
		function muscle_woocommerce_template_part($vtemplate,$vslug,$vname) {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// 1.9.5: apply the template directory filter and search that only
			$vnewpath = skeleton_apply_filters('skeleton_woocommerce_template_directory','templates/woocommerce/');
			// get slug-name template via file hierarchy
			$vnewtemplate = skeleton_file_hierarchy('file',$vslug.'-'.$vname.'.php',array($vnewpath));
			// include a fallback to slug based template
			$vslugtemplate = skeleton_file_hierarchy('file',$vslug.'.php',array($vnewpath));

			// write debug info (kept here as useful for finding templates)
			// ob_start();
			// echo "name template (".$vname."): "; print_r($vnewtemplate); echo PHP_EOL;
			// echo "slug template (".$vslug."): "; print_r($vslugtemplate); echo PHP_EOL;
			// $vdata = ob_get_contents(); ob_end_clean();
			// skeleton_write_debug_file('woo-template-parts.txt',$vdata)l

			// maybe return the altered template location
			if ($vnewtemplate) {return $vnewtemplate;}
			if ($vslugtemplate) {return $vslugtemplate;}

			return $vtemplate;
		}
	}
}


// -----------------------------
// Open Graph Framework Protocol
// -----------------------------
// ..yah down wid OGP? yeah u know me..
// Ref: http://www.itthinx.com/plugins/open-graph-protocol/

// 1.5.0: Set Open Graph Protocol Default Image
// --------------------------------------------
// requires Open Graph Protocol plugin to be installed and active
// note: if using Jetpack see filter: jetpack_open_graph_image_default

add_filter('open_graph_protocol_metas','muscle_open_graph_default_image');
if (!function_exists('muscle_open_graph_default_image')) {
 function muscle_open_graph_default_image($vmetas) {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemename, $vthemesettings, $vthemedirs;

	// allow for open graph image override filter
	// see next function for in-built custom field override
	// you can add more conditional overrides via filters.php
	$vimage = array();
	if (isset($vmetas['og:image:width'])) {$vimage[0] = $vmetas['og:image:width'];}
	if (isset($vmetas['og:image:height'])) {$vimage[1] = $vmetas['og:image:height'];}
	if (isset($vmetas['og:image'])) {$vimage[2] = $vmetas['og:image'];}
	$vimage = skeleton_apply_filters('muscle_open_graph_override_image',$vimage);

	// if we now have an image and it is a different URL
	if (isset($vimage[2])) {
		if ($vimage[2] != $vmetas['og:image']) {
			// allow override to turn this meta off completely
			if ($vimage[2] == 'off') {return array();}
			// image has been changed/updated, check for new width and height
			if ( (isset($vimage[0])) && (isset($vimage[1])) ) {
				$vmetas['og:image:width'] = $vimage[0];
				$vmetas['og:image:height'] = $vimage[1];
				$vmetas['og:image'] = $vimage[2];
			}
			else {
				// otherwise, use getimagesize (slower)
				if (!ini_get('allow_url_fopen')) {
					$vfilepath = ABSPATH.parse_url($vurl, PHP_URL_PATH);
					if (file_exists($vfilepath)) {$vurltofilepath = true;}
				}
				if ( (ini_get('allow_url_fopen')) || ($vurltofilepath) ) {
					if ($vurltofilepath) {$vimagesize = getimagesize($vfilepath);}
					else {$vimagesize = getimagesize($vimage[2]);}
					if ($vimagesize) {
						$vmetas['og:image:width'] = $vimagesize[0];
						$vmetas['og:image:height'] = $vimagesize[1];
						$vmetas['og:image'] = $vimage[2];
					}
				}
			}
		}
		else {
			// same URL, maybe a change in size though
			// as it is an override, just do that
			$vmetas['og:image:width'] = $vimage[0];
			$vmetas['og:image:height'] = $vimage[1];
		}
	}

	// default (fallback) open graph image option
	if (!isset($vmetas['og:image'])) {
		// maybe pick the largest size if set to precomposed apple touch icons
		// 1.9.6: removed this code as even 192 does not meet minimum of 200
		// if ($vthemesettings['ogdefaultimage'] == 'appletouchicon') {
		//	$vsizes = array('192','180','152','144','120','114','75','72');
		//	$vfound = false;
		//	foreach ($vsizes as $vsize) {
		//		if (!$vfound) {
		//			$vcheckurl = skeleton_file_hierarchy('url','touch-icon-'.$vsize.'x'.$vsize.'-precomposed.png',$vthemedirs['img']);
		//			if ($vcheckurl) {$vurl = $vcheckurl; $vfound = true;}
		//		}
		//	}
		// }
		// else {
			// otherwise, set the url based on the theme options => suboption
			$vkey = $vthemesettings['ogdefaultimage'];
			if ($vkey == '') {$vkey = 'header_logo';}
			// 1.9.5: fix for uploaded default image
			$vurl = $vthemesettings[$vkey];
			if (THEMEDEBUG) {echo "<!-- Open Graph Default Image URL: ".$vthemesettings[$vkey]." -->";}
		// }

		// allow for default image filter
		$vurl = skeleton_apply_filters('muscle_open_graph_default_image_url',$vurl);

		if ($vurl != '') {
			// best to cache image size like in skin.php header logo for getimagesize
			// ...but again need to check for allow_url_fopen to do that
			if (!ini_get('allow_url_fopen')) {
				// try to convert the url to filepath instead
				$vfilepath = ABSPATH.parse_url($vurl, PHP_URL_PATH);
				if (file_exists($vfilepath)) {$vurltofilepath = true;}
				// echo "**".$vfilepath."**"; // debug point
			}
			if ( (ini_get('allow_url_fopen')) || ($vurltofilepath) ) {
				$vimagesize = get_option($vthemename.'_ogdefaultimage');
				if (strstr($vimagesize,':')) {
					$vimagesize = explode(':',$vimagesize);
					if ($vimagesize[2] != $vurl) {
						if ($vurltofilepath) {$vimagesize = getimagesize($vfilepath);}
						else {$vimagesize = getimagesize($vurl);}
						if ($vimagesize) {
							$vimagedata = $vimagesize[0].':'.$vimagesize[1].':'.$vurl;
							if (!update_option($vthemename.'_ogdefaultimage',$vimagedata)) {
								add_option($vthemename.'_ogdefaultimage');
							}
						}
					}
				}
				else {
					if ($vurltofilepath) {$vimagesize = getimagesize($vfilepath);}
					else {$vimagesize = getimagesize($vurl);}
					if ($vimagesize) {
						$vimagedata = $vimagesize[0].':'.$vimagesize[1].':'.$vurl;
						if (!update_option($vthemename.'_ogdefaultimage',$vimagedata)) {
							add_option($vthemename.'_ogdefaultimage');
						}
					}
				}
				if ($vimagesize) {
					$vmetas['og:image'] = $vurl;
					$vmetas['og:image:width'] = $vimagesize[0];
					$vmetas['og:image:height'] = $vimagesize[1];
				}
			}
			else {
				// no allow_fopen_url and filepath failed :-(
				// rely on a matching explicit width/height set via filter
				$vimagesize = skeleton_apply_filters('muscle_open_graph_default_image_size',array());
				if ( (isset($vimagesize[0])) && (isset($vimagesize[1])) ) {
					$vmetas['og:image'] = $vurl;
					$vmetas['og:image:width'] = $imagesize[0];
					$vmetas['og:image:height'] = $imagesize[1];
				}
			}
		}
	}
	// 1.9.6: fix some mismatching WP to FB locales
	// ...there may be a number more of these?
	// http://www.roseindia.net/tutorials/i18n/locales-list.shtml
	// https://www.facebook.com/translations/FacebookLocales.xml
	if ($vmetas['og:locale'] == 'en_AU') {$vmetas['og:locale'] = 'en_GB';}
	if ($vmetas['og:locale'] == 'ja') {$vmetas['og:locale'] = 'ja_JP';}
	if ($vmetas['og:locale'] == 'iw_IL') {$vmetas['og:locale'] = 'he_IL';}

	if (THEMEDEBUG) {echo "<!-- Open Graph Meta: "; print_r($vmetas); echo " -->";}
	return $vmetas;
 }
}

// 1.5.0: Add Custom Field Override for the Open Graph image
// ---------------------------------------------------------
// requires the Open Graph Protocol plugin to be installed and active
// by default the plugin only sets the featured image if there is one
// this lets you add custom image fields on a post/page screen and have them used:
// opengraphimageurl (required), opengraphimagewidth, opengraphimageheight

if (!function_exists('muscle_open_graph_override_image_fields')) {
 add_filter('muscle_open_graph_override_image','muscle_open_graph_override_image_fields',0);
 function muscle_open_graph_override_image_fields($vimage) {
  	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// override existing open graph image meta with post custom field meta
	// better to set width and height field values but not totally necessary
	global $post; $vpostid = $post->ID;
	$vogimage[0] = get_post_meta($vpostid,'opengraphimagewidth',true);
	$vogimage[1] = get_post_meta($vpostid,'opengraphimageheight',true);
	$vogimage[2] = get_post_meta($vpostid,'opengraphimageurl',true);
	// to remove the image for this page, can set opengraphimageurl value to 'off'
	if ($vogimage[2] == 'off') {return array();}
	// the URL on the other hand needs to be there, or we just return
	if ($vogimage[2] != '') {return $vogimage;}
	return $vimage;
 }
}

// -----------
// Hybrid Hook
// -----------
// (does not require Hybrid Core to be loaded)
// (note: Hybrid Hook Widgets is available also)
if ( (isset($vthemesettings['hybridhook'])) && ($vthemesettings['hybridhook'] == '1') ) {
	// 1.8.0: changed hybrid hook location to /includes/ subfolder
	$vhybridhook = skeleton_file_hierarchy('file','hybrid-hook.php',array('includes/hybrid-hook'));
	if ($vhybridhook) {
		include($vhybridhook);
		if (THEMEDEBUG) {echo "<!-- Hybrid Hook Loaded -->".PHP_EOL;}
		// load it now as we have missed the plugins_loaded hook
		hybrid_hook_setup();

		// 1.8.5: dissallow hybrid hook PHP execution via filter (as e-v-a-l commented out for Theme Check)
		// (HTML / Shortcode / Widget methods are better anyway)
		add_filter('hybrid_hook_allow_php','muscle_disallow_hook_php',5);
		if (!function_exists('muscle_disallow_hook_php')) {function muscle_disallow_hook_php($v) {return false;} }

		// Load the theme layout hooks
		add_filter('hybrid_hooks','muscle_hybrid_get_hooks');
		if (!function_exists('muscle_hybrid_get_hooks')) {
		 function muscle_hybrid_get_hooks() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

			// 1.9.0: hooks now loaded by default in functions.php
			global $vthemehooks;

			if (THEMEDEBUG) {echo "<!-- Hybrid Hooks: "; print_r($vthemehooks['hybrid']); echo "-->";}

			// 1.9.0: handle admin metabox defaults
			if (is_admin()) {
				// 1.8.5: default the hybrid hook metaboxes to closed
				// ref: https://surniaulula.com/2013/05/29/collapse-close-wordpress-metaboxes/
				$vuserid = get_current_user_id();
				$voptionkey = 'closedpostboxes_'.'appearance_page_'.'hybrid-hook-settings';

				if ( (isset($_REQUEST['metaboxes'])) && ($_REQUEST['metaboxes'] == 'reset') ) {
					delete_user_option($voptionkey, $vuserid);
				} else {$vclosedboxes = get_user_option($voptionkey, $vuserid);}

				// create an empty array if get_user_option() had nothing to return
				if (!is_array($vclosedboxes)) {$vclosedboxes = array();
					foreach ($vthemehybridhooks as $vhook) {$vclosedboxes[] = 'hybrid-hook-'.$vhook;}
					update_user_option($vuserid, $voptionkey, $vclosedboxes, true);
				}

				if (THEMEDEBUG) {echo "<!-- Closed Boxes: "; print_r($vclosedboxes); echo " -->";}
			}

			return $vthemehooks['hybrid'];
		 }
		}

		// hook into the new theme filter (for modified Hybrid Hook plugin)
		add_filter('hybrid_hook_theme_prefix','muscle_hybrid_hook_prefix');
		if (!function_exists('muscle_hybrid_hook_prefix')) {
		 function muscle_hybrid_hook_prefix() {
		 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		 	return 'skeleton';
		 }
		}
	}
}


// ------------------
// === Foundation ===
// ------------------
// ref: http://foundation.zurb.com/docs

// Load Foundation
// ---------------
if ( ($vthemesettings['loadfoundation'] != '') && ($vthemesettings['loadfoundation'] != 'off') ) {
	if (!function_exists('muscle_load_foundation')) {
	 add_action('wp_enqueue_scripts','muscle_load_foundation');
	 function muscle_load_foundation() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vcsscachebust, $vjscachebust;

		// 1.8.0: check Foundation 5 or 6 directory to use for loading
		if (isset($vthemesettings['foundationversion'])) {$vfoundation = 'includes/'.$vthemesettings['foundationversion'];}
		else {$vfoundation = 'includes/foundation5';} // backwards compatibility default

		// force auto-load of modernizr and fastclick for Foundation 5
		if (strstr($vfoundation,'5')) {
			if (!has_action('wp_enqueue_scripts','muscle_load_modernizr')) {add_action('wp_enqueue_scripts','muscle_load_modernizr');}
			if (!has_action('wp_enqueue_scripts','muscle_load_fastclick')) {add_action('wp_enqueue_scripts','muscle_load_fastclick');}
		}

		// Foundation Stylesheet
		// ---------------------
		// http://foundation.zurb.com/docs/css.html
		if ($vthemesettings['foundationcss']) {
			if ($vthemesettings['loadfoundation'] == 'essentials') {
				$vfoundationstylesheet = skeleton_file_hierarchy('both','foundation.essentials.min.css',array($vfoundation.'/css','css'));
			} else {
				$vfoundationstylesheet = skeleton_file_hierarchy('both','foundation.min.css',array($vfoundation.'/css','css'));
			}
			if (is_array($vfoundationstylesheet)) {
				if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
					$vcsscachebust = date('ymdHi',filemtime($vfoundationstylesheet['file']));
				}
				wp_register_style('foundation', $vfoundationstylesheet['url'], array(), $vcsscachebust);
				wp_enqueue_style('foundation');
			}
		}

		// Full or Partial Foundation Javascript
		// -------------------------------------
		// http://foundation.zurb.com/docs/javascript.html
		if ($vthemesettings['loadfoundation'] == 'full') {
			$vfoundation = skeleton_file_hierarchy('both','foundation.min.js',array($vfoundation.'/js','javascripts'));
		}
		if ($vthemesettings['loadfoundation'] == 'essentials') {
			$vfoundation = skeleton_file_hierarchy('both','foundation.essentials.js',array($vfoundation.'/js','javascripts'));
		}
		elseif ($vthemesettings['loadfoundation'] == 'selective') {
			$vfoundation = skeleton_file_hierarchy('both','foundation.selected.js',array('javascripts',$vfoundation.'/js'));
			// 1.8.0: note, selective javascript is currently only working for Foundation 5, so just in case, fallback to min.js
			if (!is_array($vfoundation)) {$vfoundation = skeleton_file_hierarchy('both','foundation.min.js',array('javascripts',$vfoundation.'/js'));}
		}
		if (is_array($vfoundation)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
				$vjscachebust = date('ymdHi',filemtime($vfoundation['file']));
			}
			wp_enqueue_script('foundation',$vfoundation['url'],array('jquery','modernizr'),$vjscachebust,true);
		}
	 }
	}

	// Initialize Foundation JavaScript
	// --------------------------------
	if (!function_exists('muscle_foundation_init')) {
	 add_action('wp_footer','muscle_foundation_init');
	 function muscle_foundation_init() {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
		// echo "<script>$(document).foundation();</script>";
		// or better, to avoid conflicts: echo "<script>jQuery(document).foundation();</script>";
		// or even better, we add a hidden input to detect and initialize via init.js
		echo "<input type='hidden' id='foundation' name='foundation' value='load'>";
	 }
	}
}

// ----------------------
// === Theme My Login ===
// ----------------------

if ( ($vthemesettings['tmltemplates'] != '') && ($vthemesettings['tmltemplates'] != '0') ) {

	// Improve TML Template Hierarchy
	// ------------------------------
	if (!function_exists('muscle_tml_template_paths')) {
	 add_filter('tml_template_paths','muscle_tml_template_paths');
	 function muscle_tml_template_paths($vpaths) {
		if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// 1.8.5: use existing globals
		global $vthemestyledir, $vthemetemplatedir;
		$vnewpaths = array(
			$vthemestyledir.'templates/theme-my-login',
			$vthemestyledir.'theme-my-login',
			$vthemestyledir,
			$vthemetemplatedir.'templates/theme-my-login',
			$vthemetemplatedir.'theme-my-login',
			$vthemetemplatedir,
			WP_PLUGIN_DIR.'/theme-my-login/templates'
		);
		if (THEMEDEBUG) {echo "<!-- New TML Paths: "; print_r($vnewpaths); echo " -->";}
		return $vnewpaths;
	 }
	}

	// Login Button URL Filter
	// -----------------------
	if ($vthemesettings['loginbuttonurl'] != '') {
		if (!function_exists('muscle_login_button_url')) {
		 add_filter('login_button_url','muscle_login_button_url');
		 function muscle_login_button_url($vbuttonurl) {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		 	global $vthemesettings; return $vthemesettings['loginbuttonurl'];
		 }
		}
	}

	// Register Button URL Filter
	// --------------------------
	if ($vthemesettings['registerbuttonurl'] != '') {
		if (!function_exists('muscle_register_button_url')) {
			add_filter('register_button_url','muscle_register_button_url');
			function muscle_register_button_url($vbuttonurl) {
				if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
				global $vthemesettings; return $vthemesettings['registerbuttonurl'];
			}
		}
	}

	// Profile Button URL Filter
	// -------------------------
	if ($vthemesettings['profilebuttonurl'] != '') {
		if (!function_exists('muscle_profile_button_url')) {
		 add_filter('profile_button_url','muscle_profile_button_url');
		 function muscle_profile_button_url($vbuttonurl) {
		 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			global $vthemesettings; return $vthemesettings['profilebuttonurl'];
		 }
		}
	}

	// Register Form Logo Image
	// ------------------------
	if ($vthemesettings['registerformimage'] == '1') {
		if (!function_exists('muscle_register_form_image')) {
		 add_filter('register_form_image','muscle_register_form_image');
		 function muscle_register_form_image() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
			global $vthemesettings;
			if ($vthemesettings['loginlogo'] == 'custom') {$vimage = $vthemesettings['header_logo'];}
			if ($vthemesettings['loginlogo'] == 'upload') {$vimage = $vthemesettings['loginlogourl'];}
			return $vimage;
		 }
		}
	}

	// Register Form Logo Image
	// ------------------------
	if ($vthemesettings['loginformimage'] == '1') {
		if (!function_exists('muscle_login_form_image')) {
		 add_filter('login_form_image','muscle_login_form_image');
		 function muscle_login_form_image() {
			if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}
			global $vthemesettings;
			if ($vthemesettings['loginlogo'] == 'none') {$vimage = 'javascript:void(0);';}
			if ($vthemesettings['loginlogo'] == 'custom') {$vimage = $vthemesettings['header_logo'];}
			if ($vthemesettings['loginlogo'] == 'upload') {$vimage = $vthemesettings['loginlogourl'];}
			return $vimage;
		 }
		}
	}

}


// ----------------------
// Theme Switch Admin Fix
// ----------------------
// for Theme Test Drive and JonRadio Multiple Themes...
// ref: http://wordpress.stackexchange.com/q/227532/76440

// *** IMPORTANT USAGE NOTE *** only works *HERE* for BioShip Parent and Child Theme switching
// if you want the same theme switching functionality to work with other themes as well,
// you will need to simply put a copy of this functions in /wp-content/mu-plugins/ folder.
// and that is because THIS file is loaded BY this theme, so therefore the fix will not be
// loaded for other themes - unless it is loaded at an earlier time, ie. mu-plugins or plugins

// note: currently for JonRadio Multiple Themes, select-theme.php is NOT loaded for admin
// (this means the advanced setting 'AJAX All' currently has no effect anyway...)
// loading select-theme.php will automatically set the active theme via cookie storage,
// BUT this will not work for using admin-ajax.php or admin-post.php when visiting multiple
// pages on the same site at once where a different theme may be active for different pages!

// note: if loading via mu-plugins or a plugin, this action hook must change to 'plugins_loaded'
add_action('init','muscle_theme_switch_admin_fix');
if (!function_exists('muscle_theme_switch_admin_fix')) {
 function muscle_theme_switch_admin_fix() {
 	if (THEMETRACE) {skeleton_trace('F',__FUNCTION__,__FILE__);}

	// check for a valid active plugin
	$activeplugins = maybe_unserialize(get_option('active_plugins'));
	if (!is_array($activeplugins)) {return;}
	$multiplethemes = false; $themetestdrive = false;
	if (in_array('jonradio-multiple-themes/jonradio-multiple-themes.php',$activeplugins)) {$multiplethemes = true;}
	if (in_array('theme-test-drive/themedrive.php',$activeplugins)) {$themetestdrive = true;}
	if ( (!$multiplethemes) && (!$themetestdrive) ) {return;} // nothing to do

	// multiple themes option: 'site', 'sticky' or 'both'
	if (defined('MT_METHOD')) {$method = MT_METHOD;} else {$method = 'site';}
	$parameter = 'theme'; // multiple theme switch querystring parameter name

	// user data save settings
	$datamethod = 'both'; // how to save user data: 'cookie', 'usermeta' or 'both'
	$datakey = 'theme_switch_data'; // cookie and user meta key name
	$expires = 24*60*60; // length of time for cookies and transients

	// maybe include pluggable.php for accessing user
	if ( ($userdata != 'cookie') && (!function_exists('is_user_logged_in')) ) {require(ABSPATH.WPINC.'/pluggable.php');}

	// maybe reset cookie and URL data by user request
	if ( (isset($_GET['resetthemes'])) && ($_GET['resetthemes'] == '1') ) {
		if ($debug) {echo "<!-- THEME SWITCH DATA RESET -->";}
		if ($themetestdrive) {setCookie($themecookie,'',-300);}
		delete_option('theme_switch_request_urls'); return;
	}

	// maybe set debug switch
	$debug = false;
	if ( (isset($_GET['debug'])) && ($_GET['debug'] == '1') ) {$debug = true;}
	elseif (defined('THEMEDEBUG')) {$debug = THEMEDEBUG;}

	// theme test drive by default only filters via get_stylesheet and get_template
	// improve theme test drive to use options filters like multiple themes instead
	if ($themetestdrive) {
		$parameter = 'theme'; // set f
		remove_filter('template', 'themedrive_get_template'); remove_filter('stylesheet', 'themedrive_get_stylesheet');
		add_filter('pre_option_stylesheet', 'themedrive_get_stylesheet'); add_filter('pre_option_template', 'themedrive_get_template');
	}

	// maybe load stored alternative theme for AJAX/admin calls
	if (is_admin()) {

		// let wordpress handle customize previews
		if (is_customize_preview()) {return;}

		// get pagenow to check for admin-post.php as well
		global $pagenow;

		if ( ( (defined('DOING_AJAX')) && (DOING_AJAX) ) || ($pagenow == 'admin-post.php') ) {

			// set the referer path for URL matching
			$referer = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_PATH);

			// set some globals for the AJAX theme options
			global $ajax_stylesheet, $ajax_template;

			// check for temporary Theme Test Drive cookie data
			if ( ($themetestdrive) || ( ($multiplethemes) && ($method != 'site') ) ) {
				if ($datamethod != 'usermeta') {
					if ( (isset($_COOKIE[$datakey])) && ($_COOKIE[$datakey] != '') ) {
						$cookiedata = explode(',',$_COOKIE[$datakey]);
						// attempt to match referer data with stored transient request
						foreach ($cookiedata as $transientkey) {
							$transientdata = get_transient($transientkey);
							if ($transientdata) {
								$data = explode(':',$transientdata);
								if ($data[0] == $referer) {
									$ajax_stylesheet = $data[1]; $ajax_template = $data[2];
									$transientdebug = $transientdata; $matchedurlpath = true;
								}
							}
						}
					}
					if ( ($datamethod != 'cookie') && (is_user_logged_in()) ) {
						global $current_user; wp_get_current_user();
						$usermetadata = get_user_meta($current_user->ID,$datakey,true);
						if (is_array($usermetadata)) {
							// attempt to match referer data with stored transient request
							foreach ($usermetadata as $transientkey) {
								$transientdata = get_transient($transientkey);
								if ($transientdata) {
									$data = explode(':',$transientdata);
									if ($data[0] == $referer) {
										$ajax_stylesheet = $data[1]; $ajax_template = $data[2];
										$transientdebug = $transientdata; $matchedurlpath = true;
									}
								}
							}
						}
					}
				}
			}
			elseif ( ($multiplethemes) && ($method != 'sticky') ) {
				// check the request URL list to handle sitewide cases
				if (!$matchedurlpath) { // but not if we already have a match
					$requesturls = get_option('theme_switch_request_urls');
					if (is_array($requesturls)) {
						if ( (is_array($requesturls)) && (array_key_exists($referer,$requesturls)) ) {
							$matchedurlpath = true;
							$ajax_stylesheet = $requesturls[$referer]['stylesheet'];
							$ajax_template = $requesturls[$referer]['template'];
						}
					}
				}
			}

			if ($matchedurlpath) {
				// add theme option filters for admin-ajax (and admin-post)
				// so any admin actions defined by the theme are finally loaded!
				add_filter('pre_option_stylesheet','admin_ajax_stylesheet');
				add_filter('pre_option_template','admin_ajax_template');

				function admin_ajax_stylesheet() {global $ajax_stylesheet; return $ajax_stylesheet;}
				function admin_ajax_template() {global $ajax_template; return $ajax_template;}
			}

			// maybe output debug info for AJAX/admin test frame
			if ($debug) {
				echo "<!-- COOKIE DATA: ".$_COOKIE[$themecookie]." -->";
				echo "<!-- TRANSIENT DATA: ".$transientdebug." -->";
				echo "<!-- REFERER: ".$referer." -->";
				echo "<!-- STORED URLS: "; print_r($requesturls); echo " -->";
				if ($matchedurlpath) {echo "<!-- URL MATCH FOUND -->";} else {echo "<!-- NO URL MATCH FOUND -->";}
				echo "<!-- AJAX Stylesheet: ".get_option('stylesheet')." -->";
				echo "<!-- AJAX Template: ".get_option('template')." -->";
			}

			return; // done for admin requests so bug out here
		}
	}

	// store public request URLs where an alternate theme is active
	// (note: multiple themes does not load in admin, but theme test drive does)
	if ( ($themetestdrive) || ( (!is_admin()) && ($multiplethemes) ) ) {

		// get current theme (possibly overriden) setting
		$themestylesheet = get_option('stylesheet'); $themetemplate = get_option('template');

		// remove filters, get default theme setting, re-add filters
		if ($multiplethemes) {
			remove_filter('pre_option_stylesheet', 'jr_mt_stylesheet'); remove_filter('pre_option_template', 'jr_mt_template');
			$stylesheet = get_option('stylesheet'); $template = get_option('template');
			add_filter('pre_option_stylesheet', 'jr_mt_stylesheet'); add_filter('pre_option_template', 'jr_mt_template');
		}
		if ($themetestdrive) {
			// note: default theme test drive filters are changed earlier on
			remove_filter('pre_option_stylesheet', 'themedrive_get_stylesheet'); remove_filter('pre_option_template', 'themedrive_get_template');
			$stylesheet = get_stylesheet(); $template = get_template();
			add_filter('pre_option_stylesheet', 'themedrive_get_stylesheet'); add_filter('pre_option_template', 'themedrive_get_template');
		}

		// set/get request URL values (URL path only)
		$requesturl = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
		$requesturls = get_option('theme_switch_request_urls');

		// store the request data
		if ( ($themetestdrive) || ( ($multiplethemes) && ($method != 'site') ) ) {
			if ( (isset($_REQUEST[$parameter])) && ($_REQUEST[$parameter] != '') ) {
				if ($datamethod != 'usermeta') {
					 // check existing cookie data
					 $cookiedata = array();
					 if ( (isset($_COOKIE[$datakey])) && ($_COOKIE[$datakey] != '') ) {
						$existingmatch = false;
						$i = 0; $cookiedata = explode(',',$_COOKIE[$datakey]);
						foreach ($cookiedata as $transientkey) {
							$transientdata = get_transient($transientkey);
							if ($transientdata) {
								$data = explode(':',$transientdata);
								if ($data[0] == $requesturl) {
									// update existing transient data
									$transientdata = $transientdebug = $requesturl.':'.$themestylesheet.':'.$themetemplate;
									set_transient($transientkey,$transientdata,$expires);
									$existingmatch = true;
								}
							} else {unset($cookiedata[$i]);} // remove expired
							$i++;
						}
					}
				}
				if ( ($datamethod != 'cookie') && (is_user_logged_in()) ) {
					// check existing usermeta data
					global $current_user; wp_get_current_user();
					$usermetadata = get_user_meta($current_user->ID,$datakey,true);
					if (is_array($usermetadata)) {
						$existingmatch = false;
						$i = 0;
						// remove expired transient IDs from usermeta
						foreach ($usermetadata as $transientkey) {
							$transientdata = get_transient($transientkey);
							if ($transientdata) {
								$data = explode(':',$transientdata);
								if ($data[0] == $requesturl) {
									// update existing transient data
									$transientdata = $transientdebug = $requesturl.':'.$themestylesheet.':'.$themetemplate;
									set_transient($transientkey,$transientdata,$expires);
									$existingmatch = true;
								}
							} else {unset($usermetadata[$i]);} // remove expired
							$i++;
						}
					} else {$usermetadata = array();}
				}
				// set the transient with matching cookie/usermeta data
				if (!$existingmatch) { // avoid duplicates
					 // set the new transient
					 $transientkey = $datakey.'_'.uniqid();
					 $transientdata = $transientdebug = $requesturl.':'.$themestylesheet.':'.$themetemplate;
					 set_transient($transientkey, $transientdata, $expires);

					 // add transient to cookie for matching later
					 if ($datamethod != 'usermeta') {
						 $cookiedata[] = $transientkey; $cookiedatastring = implode(',',$cookiedata);
						 setCookie($themecookie, $cookiedatastring, time()+$expires);
					 }
					 // add transient to usermeta for matching later
					 if ($datamethod != 'cookie') {
					 	$usermetadata[] = $transientkey; update_user_meta($current_user->ID,$datakey,$usermetadata);
					 }
				}
				// maybe output debug info
				if ($debug) {
					echo "<!-- COOKIE DATA: "; print_r($cookiedata); echo " -->";
					if ($datamethod != 'cookie') {echo "<!-- USERMETA DATA: "; print_r($usermetadata); echo " -->";}
					echo "<!-- TRANSIENT DATA: ".$transientdebug." -->";
				}
			}
		}
		elseif ( ($multiplethemes) && ($method != 'sticky') ) {
			// save/remove the requested URL path in the list
			if ( ($stylesheet == $themestylesheet) && ($template == $themetemplate) ) {
				// maybe remove this request from the stored URL list
				if ( (is_array($requesturls)) && (array_key_exists($requesturl,$requesturls)) ) {
					unset($requesturls[$requesturl]);
					if (count($requesturls) === 0) {delete_option('theme_switch_request_urls');}
					else {update_option('theme_switch_request_urls',$requesturls);}
				}
			}
			else {
				// add this request URL to the stored list
				$requesturls[$requesturl]['stylesheet'] = $themestylesheet;
				$requesturls[$requesturl]['template'] = $themetemplate;
				update_option('theme_switch_request_urls',$requesturls);
			}
			// maybe output debug info
			if ( (!is_admin()) && ($debug) ) {
				echo "<!-- REQUEST URL: ".$requesturl." -->";
				echo "<!-- STORED URLS: "; print_r($requesturls); echo " -->";
			}
		}

		// maybe output hidden ajax debugging frames
		if ( (!is_admin()) && ($debug) ) {
			echo "<iframe src='".admin_url('admin-ajax.php')."?debug=1' style='display:none;'></iframe>";
			echo "<iframe src='".admin_url('admin-post.php')."?debug=1' style='display:none;'></iframe>";
		}
	}
 }
}

?>