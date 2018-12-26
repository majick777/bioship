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
if (!function_exists('bioship_muscle_get_display_overrides')) {
 function bioship_muscle_get_display_overrides($vresource) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemedisplay;

	// 1.8.0: removed options tab as only needed for admin display
	// 1.8.0: moved content filters to a separate function
	// 1.8.0: removed perpoststyles from overrides, now retrieved separately

	// TODO: meta archive overrides via custom post type?
	if (is_numeric($vresource)) {
		// 2.0.8: use prefixed post meta key
		$vthemedisplay = get_post_meta($vresource, '_'.THEMEPREFIX.'_display_overrides', true);
		if (!$vthemedisplay) {
			// 2.0.8: maybe convert old meta key to prefixed meta key
			$voldpostmeta = get_post_meta($vresource, '_displayoverrides', true);
			if ( ($voldpostmeta) && (is_array($voldpostmeta)) ) {
				$vthemedisplay = $voldpostmeta; delete_post_meta($vresource, '_displayoverrides');
				update_post_meta($vresource, '_'.THEMEPREFIX.'_display_overrides', $vthemedisplay);
			}
		}
	} else {$vthemedisplay = array();}

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
			$vthemedisplay[$vdisplaykey] = get_post_meta($vresource, '_hide'.$vdisplaykey, true);
			if ( (!$vthemedisplay[$vdisplaykey]) || ($vthemedisplay[$vdisplaykey] == '') ) {$voverride[$vdisplaykey] = '0';}
		}
		delete_post_meta($vresource, '_'.THEMEPREFIX.'_display_overrides');
		add_post_meta($vresource, '_'.THEMEPREFIX.'_display_overrides', $vthemedisplay, true);
	} else {
		// fix for any empty values to avoid undefined index warnings
		foreach ($vdisplaykeys as $vdisplaykey) {
			if (!isset($vthemedisplay[$vdisplaykey])) {$vthemedisplay[$vdisplaykey] = '0';}
		}
	}

	// 2.0.9: changed this filter name from muscle_perpage_overrides
	$vthemedisplay = bioship_apply_filters('muscle_display_overrides', $vthemedisplay);

	if (THEMEDEBUG) {echo '<!-- Display Overrides: '; print_r($vthemedisplay); echo ' -->';}

	return $vthemedisplay;
 }
}

// get PerPost Templating Overrides
// --------------------------------
// 1.9.5: separated for templating overrides
if (!function_exists('bioship_muscle_get_templating_overrides')) {
 function bioship_muscle_get_templating_overrides($vresource) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemeoverride;

	// TODO: meta archive overrides via custom post type?
	if (is_numeric($vresource)) {
		// 2.0.8: use prefixed post meta key
		$vthemeoverride = get_post_meta($vresource, '_'.THEMEPREFIX.'_templating_overrides', true);
		if (!$vthemeoverride) {
			// 2.0.8: maybe convert old meta key to prefixed meta key
			$voldpostmeta = get_post_meta($vresource, '_templatingoverrides', true);
			if ( ($voldpostmeta) && (is_array($voldpostmeta)) ) {
				$vthemeoverride = $voldpostmeta; delete_post_meta($vresource, '_templatingoverrides');
				update_post_meta($vresource, '_'.THEMEPREFIX.'_templating_overrides', $vthemeoverride);
			}
		}
	} else {$vthemeoverride = array();}

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
	// 2.0.8: check prefixed post meta value for thumbnail size
	if ( (get_post_meta($vresource, '_thumbnailsize', true) == 'off')
	  || (get_post_meta($vresource, '_'.THEMEPREFIX.'_thumbnail_size', true) == 'off') )  {
		$vthemeoverride['image'] == 'off';
	}

	$vthemeoverride = bioship_apply_filters('muscle_templating_overrides', $vthemeoverride);
	bioship_debug("Templating Overrides", $vthemeoverride);
	return $vthemeoverride;
 }
}


// Output PerPost Override Styles
// ------------------------------
if (!function_exists('bioship_muscle_perpage_override_styles')) {

 if ($vthemesettings['themecssmode'] == 'footer') {
 	add_action('wp_footer', 'bioship_muscle_perpage_override_styles');
 } else {add_action('wp_head', 'bioship_muscle_perpage_override_styles');}

 function bioship_muscle_perpage_override_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 2.0.9: check admin context internally
	if (is_admin()) {return;}

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
	$vhidesidebar = bioship_apply_filters('skeleton_sidebar_hide', false);
	$vhidesubsidebar = bioship_apply_filters('skeleton_subsidebar_hide', false);
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
	// 2.0.0: fix to breadcrumb trail targeting (was #breadcrumb)
	if ($voverride['breadcrumb'] == '1') {$vstyles .= "#content .breadcrumb-trail {display:none !important;}".PHP_EOL;}
	if ($voverride['title'] == '1') {$vstyles .= "#content .entry-title {display:none !important;}".PHP_EOL;}
	if ($voverride['subtitle'] == '1') {$vstyles .= "#content .entry-subtitle {display:none !important;}".PHP_EOL;}
	if ($voverride['metatop'] == '1') {$vstyles .= "#content .entry-meta {display:none !important;}".PHP_EOL;}
	if ($voverride['metabottom'] == '1') {$vstyles .= "#content .entry-utility {display:none !important;}".PHP_EOL;}
	if ($voverride['authorbio'] == '1') {$vstyles .= "#content .entry-author {display:none !important;}".PHP_EOL;}
	if ($voverride['pagenavi'] == '1') {$vstyles .= "#content #nav-below {display:none !important;}".PHP_EOL;}

	// PerPost Styles
	// --------------
	// 1.9.5: moved singular post check to here
	$vperpoststyles = '';
	if (is_singular()) {
		global $post; $vpostid = $post->ID;
		// 2.0.8: use prefixed post meta key
		$vperpoststyles = get_post_meta($vpostid, '_'.THEMEPREFIX.'_perpoststyles', true);
		// 2.0.8: maybe convert old post meta key
		if (!$vperpoststyles) {
			$voldpostmeta = get_post_meta($vpostid, '_perpoststyles', true);
			if ($voldpostmeta) {
				$vperpoststyles = $voldpostmeta; delete_post_meta($vpostid, '_perpoststyles');
				update_post_meta($vpostid, '_'.THEMEPREFIX.'_perpoststyles', $vperpoststyles);
			}
		}
	} elseif (is_archive()) {
		// TODO: meta archive overrides via custom post type?

	}

	if ( ($vperpoststyles) && ($vperpoststyles != '') ) {$vstyles .= $vperpoststyles.PHP_EOL;}

	if ($vstyles != '') {echo '<style>'.$vstyles.'</style>';}
 }
}

// PerPost Thumbnail Size Filter
// -----------------------------
// 2.0.1: moved add_filter internally
if (!function_exists('bioship_muscle_thumbnail_size_perpost')) {
 add_filter('skeleton_post_thumbnail_size','bioship_muscle_thumbnail_size_perpost');
 function bioship_muscle_thumbnail_size_perpost($vsize) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $post;
	if ( (!isset($post)) || (!is_object($post)) ) {return $vsize;}
	$vpostid = $post->ID;

	// 2.0.8: use prefixed post meta key
	$vthumbsize = get_post_meta($vpostid, '_'.THEMEPREFIX.'_thumbnailsize', true);
	if (!$vthumbsize) {
		$voldpostmeta = get_post_meta($vpostid, '_thumbnailsize', true);
		if ($voldpostmeta) {
			$vthumbsize = $voldpostmeta; delete_post_meta($vpostid, '_thumbnailsize');
			update_post_meta($vpostid, '_'.THEMEPREFIX.'_thumbnailsize', $vthumbsize);
		}
	}

	// TODO: maybe double check thumbnail size is still available before using it?
	// $vthumbsizes = array_merge(array('small', 'medium', 'large'), get_intermediate_image_sizes());
	if ( ($vthumbsize) && ($vthumbsize != '') ) {return $vthumbsize;}
	return $vsize;
 }
}

// Get Content Filter Overrides
// ----------------------------
// 1.8.0: separated to just get filter overrides
if (!function_exists('bioship_muscle_get_content_filter_overrides')) {
 function bioship_muscle_get_content_filter_overrides($vpostid) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.9.5: fix to remove filters metakey (previously _disablefilters)
	// 2.0.8: use prefixed post meta key
	$vremovefilters = get_post_meta($vpostid, '_'.THEMEPREFIX.'_removefilters', true);

	if (!$vremovefilters) {
	 	// 2.0.8: maybe convert old post meta key
		$voldpostmeta = get_post_meta($vpostid, '_removefilters', true);
		if ($voldpostmeta) {
			$vremovefilters = $voldpostmeta; delete_post_meta($vpostid, '_removefilters');
			update_post_meta($vpostid, '_'.THEMEPREFIX.'_removefilters', $vremovefilters);
		}
	}

	// 1.8.0: maybe convert to single filter meta array
	if ( ($vremovefilters == '') || (!is_array($vremovefilters)) ) {
		$vremovefilters = array();
		$vfilters = array('wpautop', 'wptexturize', 'convertsmilies', 'convertchars');
		foreach ($vfilters as $vfilter) {
			$vremovefilters[$vfilter] = get_post_meta($vpostid, '_disable'.$vfilter, true);
			delete_post_meta($vpostid, '_disable'.$vfilter);
		}
		delete_post_meta($vpostid, '_bioship_removefilters');
		add_post_meta($vpostid, '_bioship_removefilters', $vremovefilters, true);
	}

	// 1.8.0: added this conditional filter
	$vremovefilters = bioship_apply_filters('muscle_content_filter_overrides', $vremovefilters);
	return $vremovefilters;
 }
}


// maybe Remove Content Filters
// ----------------------------
if (!function_exists('bioship_muscle_remove_content_filters')) {

 // run this filter before others to maybe remove the filters
 add_filter('the_content', 'bioship_muscle_remove_content_filters', 9);

 function bioship_muscle_remove_content_filters($vcontent) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $post; if ( (!isset($post)) || (!is_object($post)) ) {return $vcontent;}
	$vpostid = $post->ID; $vremove = bioship_muscle_get_content_filter_overrides($vpostid);

	// 2.0.5: loop through possible filter array
	$vfilters = array('wpautop', 'wptexturize', 'convert_smilies', 'convert_chars');
	foreach ($vfilters as $vfilter) {
		if ( (isset($vremove[$vfilter])) && ($vremove[$vfilter] == '1') ) {
			remove_filter('the_content', $vfilter);
		}
	}

	return $vcontent;
 }
}


// ------------
// === MISC ===
// ------------

// maybe Change default Gravatar
// -----------------------------
// eg. /wp-content/child-theme/images/avatar.png
if (!function_exists('bioship_muscle_default_gravatar')) {
 add_filter('avatar_defaults', 'bioship_muscle_default_gravatar');
 function bioship_muscle_default_gravatar($vdefaults) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs;
	if ($vthemesettings['gravatarurl'] != '') {
		$vavatar = $vthemesettings['gravatarurl'];
		$vdefaults[$vavatar] = 'avatar';
	} else {
		$vavatar = bioship_file_hierarchy('url', 'gravatar.png', $vthemedirs['image']);
		if ($vavatar) {$vdefaults[$vavatar] = 'avatar';}
	}
	// TODO: cache default avatar image size and pass to skeleton_comments_avatar_size filter?
	return $vdefaults;
 }
}

// Classic Text Widget
// -------------------
// 2.0.8: copied from WP 4.7.5 for Discreet Text Widget basis
// (since WP 4.8 changes WP_Widget_Text class and breaks DiscreetTextWidget)
// 2.0.9: no classic text widget for WordPress.org version :-(
// (widgets classes need to be in plugins not themes in repository)
if ( (!class_exists('WP_Widget_Classic_Text')) && !THEMEWPORG) {
 class WP_Widget_Classic_Text extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_text',
			'description' => __('Arbitrary text or HTML.','bioship'),
			'customize_selective_refresh' => true,
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct('text', __('Text','bioship'), $widget_ops, $control_ops);
	}

	public function widget($args, $instance ){
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$widget_text = !empty($instance['text']) ? $instance['text'] : '';
		$text = apply_filters('widget_text', $widget_text, $instance, $this);
		echo $args['before_widget'];
		if (!empty($title)) {echo $args['before_title'].$title.$args['after_title'];}
		echo '<div class="textwidget">';
		echo !empty($instance['filter']) ? wpautop($text) : $text;
		echo '</div>';
		echo $args['after_widget'];
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		if (current_user_can('unfiltered_html')) {$instance['text'] = $new_instance['text'];}
		else {$instance['text'] = wp_kses_post($new_instance['text']);}
		$instance['filter'] = !empty($new_instance['filter']);
		return $instance;
	}

	public function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '', 'text' => '' ));
		$filter = isset($instance['filter']) ? $instance['filter'] : 0;
		$title = sanitize_text_field($instance['title']);
		echo '<p><label for="'.$this->get_field_id('title').'">'.__('Title:','bioship').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" /></p>';
		echo '<p><label for="'.$this->get_field_id('text').'">'.__('Content:','bioship').'</label>';
		echo '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id('text').'" name="'.$this->get_field_name('text').'">'.esc_textarea($instance['text']).'</textarea></p>';
		echo '<p><input id="'.$this->get_field_id('filter').'" name="'.$this->get_field_name('filter').'" type="checkbox"'.checked($filter).' />&nbsp;';
		echo '<label for="'.$this->get_field_id('filter').'">'.__('Automatically add paragraphs','bioship').'</label></p>';
	}
 }
}

// Discreet Text Widget
// --------------------
// most super useful widget, especially when used with shortcodes
// (so that if the shortcode returns empty the widget is not displayed)
// ref: https://wordpress.org/plugins/hackadelic-discreet-text-widget/
// 1.8.5: removed option, always on by default
if (!function_exists('bioship_muscle_discreet_text_widget')) {
 add_action('widgets_init', 'bioship_muscle_discreet_text_widget');
 function bioship_muscle_discreet_text_widget() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 1.9.8: added class check (for no conflict with content sidebars plugin)
	// 2.0.9: no discreet text widget for WordPress.org version
	// (widgets classes need to be in plugins not themes in repository)
	if ( (!class_exists('DiscreetTextWidget')) && !THEMEWPORG) {
		// 2.0.8: extend classic text widget class
		class DiscreetTextWidget extends WP_Widget_Classic_Text {
			function __construct() {
				$vwidgetops = array('classname' => 'discreet_text_widget', 'description' => __('Arbitrary text or HTML, only shown if not empty.','bioship'));
				$vcontrolops = array('width' => 400, 'height' => 350);
				// 1.9.8: fix to deprecated class construction method
				// 2.0.7: fix to incorrect text domain (csidebars)
				call_user_func(array(get_parent_class(get_parent_class($this)), '__construct'), 'discrete_text', __('Discreet Text','bioship'), $vwidgetops, $vcontrolops);
				// parent::__construct('discrete_text', __('Discreet Text','bioship'), $vwidgetops, $vcontrolops);
				// $this->WP_Widget('discrete_text', __('Discreet Text','bioship'), $vwidgetops, $vcontrolops);
			}
			function widget($vargs, $vinstance) {
				// 1.9.8: removed usage of extract here
				// extract($vargs, EXTR_SKIP);
				$vtext = bioship_apply_filters('widget_text', $vinstance['text']);
				if (empty($vtext)) {return;}

				echo $vargs['before_widget'];
				$vtitle = bioship_apply_filters('widget_title', $vinstance['title']);
				if (!empty($vtitle)) {echo $vargs['before_title'].$vtitle.$vargs['after_title'];}
				echo '<div class="textwidget">';
				if ($vinstance['filter']) {echo wpautop($vtext);} else {echo $vtext;}
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
// TODO: maybe add video background to Theme Options? currently via filters
// (see filters.php example): muscle_videobackground_type,
// muscle_videobackground_id, muscle_videobackground_delay

// 1.9.8: fix to function_exists check (missing argument)
// 2.0.1: check themesettings internally to allow better filtering
if (!function_exists('bioship_muscle_video_background')) {
 add_action('bioship_before_navbar', 'bioship_muscle_video_background');
 function bioship_muscle_video_background() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings; $vload = false;
	if (isset($vthemesettings['videobackground'])) {$vload = $vthemesettings['videobackground'];}
	$vload = bioship_apply_filters('muscle_videobackground_type', $vload);

	if ($vload == 'youtube') {
		$vvideoid = ''; $vvideodelay = '';
		if (isset($vthemesettings['videobackgroundid'])) {$vvideoid = $vthemesettings['videobackgroundid'];}
		$vvideoid = bioship_apply_filters('muscle_videobackground_id', $vvideoid);
		if (isset($vthemesettings['videobackgrounddelay'])) {$vvideodelay = $vthemesettings['videobackgrounddelay'];}
		$vvideodelay = (int)bioship_apply_filters('muscle_videobackground_delay', $vvideodelay);
		$vvideodelay = absint($vvideodelay);

		if ( (!is_numeric($vvideodelay)) || ($vvideodelay < 0) ) {$vvideodelay = 1000;}
		$vmaybe = array(); preg_match( "/[a-zA-Z0-9]+//", $vvideoid, $vmaybe);
		if ( ($vvideoid != '') && ($vvideoid == $vmaybe[0]) ) {
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
// 1.8.0: added flexibility (flexbox polyfill)
// 2.0.1: added individual loading filters
if (!function_exists('bioship_muscle_internet_explorer_scripts')) {
 add_action('wp_head', 'bioship_muscle_internet_explorer_scripts');
 function bioship_muscle_internet_explorer_scripts() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust;
	$viesupports = $vthemesettings['iesupports'];
	// 2.0.9: fix for undefined variable warning
	$vfilemtime = false;
	if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// Selectivizr CSS3
	// ----------------
	if ( (isset($viesupports['selectivizr'])) &&  ($viesupports['selectivizr'] == '1') ) {
		// 2.0.1: added loading filter
		$vload = bioship_apply_filters('muscle_load_selectivizr', true);
		if ($vload) {
			$vselectivizr = bioship_file_hierarchy('both', 'selectivizr.min.js', $vthemedirs['script']);
			if (is_array($vselectivizr)) {
				if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vselectivizr['file']));}
				echo '<!--[if (gte IE 6)&(lte IE 8)]><script type="text/javascript" src="'.$vselectivizr['url'].'?ver='.$vjscachebust.'"></script><![endif]-->';
			}
		}
	}

	// HTML5 Shiv
	// ----------
	if ( (isset($viesupports['html5shiv'])) && ($viesupports['html5shiv'] == '1') ) {
		// 2.0.1: added loading filter
		$vload = bioship_apply_filters('muscle_load_html5shiv', true);
		if ($vload) {
			$vhtml5 = bioship_file_hierarchy('both', 'html5.js', $vthemedirs['script']);
			if (is_array($vhtml5)) {
				if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vhtml5['file']));}
				echo '<!--[if lt IE 9]><script src="'.$vhtml5['url'].'"></script><![endif]-->';
			}
		}
	}

	// Supersleight
	// ------------
	if ( (isset($viesupports['supersleight'])) && ($viesupports['supersleight'] == '1') ) {
		// 2.0.1: added loading filter
		$vload = bioship_apply_filters('muscle_load_supersleight', true);
		if ($vload) {
			$vsupersleight = bioship_file_hierarchy('both', 'supersleight.js', $vthemedirs['script']);
			if (is_array($vsupersleight)) {
				if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vsupersleight['file']));}
				echo '<!--[if lte IE 6]><script src="'.$vsupersleight['url'].'"></script><![endif]-->';
			}
		}
	}

	// IE8 DOM
	// -------
	// 1.8.5: added IE8 DOM polyfill
	if ( (isset($viesupports['ie8'])) && ($viesupports['ie8'] == '1') ) {
		// 2.0.1: added loading filter
		$vload = bioship_apply_filters('muscle_load_ie8dom', true);
		if ($vload) {
			$vie8 = bioship_file_hierarchy('both', 'ie8.js', $vthemedirs['script']);
			if (is_array($vie8)) {
				if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vie8['file']));}
				echo '<!--[if IE 8]><script src="'.$vie8['url'].'"></script><![endif]-->';
			}
		}
	}

	// Flexibility
	// -----------
	// 1.8.0: added flexbox polyfill
	if ( (isset($viesupports['flexibility'])) && ($viesupports['flexibility'] == '1') ) {
		// 2.0.1: added loading filter
		$vload = bioship_apply_filters('muscle_load_flexibility', true);
		if ($vload) {
			$vflexibility = bioship_file_hierarchy('both', 'flexibility.js', $vthemedirs['script']);
			if (is_array($vflexibility)) {
				if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vflexibility['file']));}
				echo '<!--[if (IE 8)|(IE 9)]><script src="'.$vflexibility['url'].'"></script><![endif]-->';
			}
		}
	}

 }
}

// PrefixFree
// ----------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_prefixfree')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_prefixfree');
 function bioship_muscle_load_prefixfree() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vjscachebust, $vthemedirs; $vload = false;
	if (isset($vthemesettings['prefixfree'])) {$vload = $vthemesettings['prefixfree'];}
	$vload = bioship_apply_filters('muscle_load_prefixfree', $vload);
	if (!$vload) {return;}

	$vprefixfree = bioship_file_hierarchy('both', 'prefixfree.js', $vthemedirs['script']);
	if (is_array($vprefixfree)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vprefixfree['file']));}
		wp_enqueue_script('prefixfree',$vprefixfree['url'], array(), $vjscachebust, true);

		// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (a "WTF" bug!)
		// ref: http://stackoverflow.com/questions/25694456/google-fonts-giving-no-access-control-allow-origin-header-is-present-on-the-r
		// ref: http://wordpress.stackexchange.com/questions/176077/add-attribute-to-link-tag-thats-generated-through-wp-register-style
		// ref: https://github.com/LeaVerou/prefixfree/pull/39
		add_filter('style_loader_tag','bioship_muscle_fonts_noprefix_attribute', 10, 2);
		if (!function_exists('muscle_fonte_no_prefix_attribute')) {
		 function bioship_muscle_fonts_noprefix_attribute($vlink, $vhandle) {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			$vlinka = $vlink;
			// note: Google fonts style handles are 'heading-font-'x or 'custom-font-'x
			// 2.0.9: stricter checking for handle at start of string
			if ( (strpos($vhandle, 'heading-font-') === 0)  || (strpos($vhandle, 'custom-font-') === 0) ) {
				$vlink = str_replace( '/>', 'data-noprefix />', $vlink);
			} else {
				// ...and a basic check for if the link is external to the site
				// as this problem could occur for other external sheets like this
				$vsitehost = $_SERVER['HTTP_HOST'];
				if (!stristr($vlink, $vsitehost)) {
					// 2.0.9: use stricter checking for http at start of string
					if ( (stripos($vlink, 'http://') === 0) || (stripos($vlink, 'https://') === 0) ) {
						$vlink = str_replace('/>', 'data-noprefix />', $vlink);
					}
				}
			}
			if ($vlinka != $vlink) {bioship_debug("No PrefixFree for Style", $vhandle);}
			return $vlink;
		 }
		}
	}
 }
}

// NWWatcher Selector Javascript
// -----------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_nwwatcher')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_nwwatcher');
 function bioship_muscle_load_nwwatcher() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['nwwatcher'])) {$vload = $vthemesettings['nwwatcher'];}
	$vload = bioship_apply_filters('muscle_load_nwwatcher', $vload);
	if (!$vload) {return;}

	$vnwwatcher = bioship_file_hierarchy('both', 'nwwatcher.js', $vthemedirs['script']);
	if (is_array($vnwwatcher)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vnwwatcher['file']));}
		wp_enqueue_script('nwwatcher', $vnwwatcher['url'], array(), $vjscachebust, true);
	}
 }
}

// NWEvents Event Manager (NWWatcher dependent)
// --------------------------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('muscle_load_nwevents')) {
 add_action('wp_enqueue_scripts','bioship_muscle_load_nwevents');
 function bioship_muscle_load_nwevents() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['nwevents'])) {$vload = $vthemesettings['nwevents'];}
	$vload = bioship_apply_filters('muscle_load_nwevents', $vload);
	if (!$vload) {return;}

	$vnwevents = bioship_file_hierarchy('both', 'nwevents.js', $vthemedirs['script']);
	if (is_array($vnwevents)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vprefixfree['file']));}
		wp_enqueue_script('nwevents', $vnwevents, array('nwwatcher'), $vjscachebust, true);
	}
 }
}

// Media Queries Support
// ---------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_media_queries_script')) {
 // note enqueue exception: apparently for these the "best place is in the footer"
 add_action('wp_footer', 'bioship_muscle_media_queries_script');
 function bioship_muscle_media_queries_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = 'off';
	if (isset($vthemesettings['mediaqueries'])) {$vload = $vthemesettings['mediaqueries'];}
	$vload = bioship_apply_filters('muscle_load_mediaqueries', $vload);
	// 2.0.2: fix to simplified load variable typo
	if ( (!$vload) || ($vload == 'off') ) {return;}

	if ($vthemesettings['mediaqueries'] == 'respond') {
		$vrespond = bioship_file_hierarchy('both', 'respond.min.js', $vthemedirs['script']);
		if (is_array($vrespond)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vrespond['file']));}
			echo '<script type="text/javascript" src="'.$vrespond['url'].'?ver='.$vjscachebust.'"></script>';
		}
	}
	if ($vthemesettings['mediaqueries'] == 'mediaqueries') {
		$vmediaqueries = bioship_file_hierarchy('both', 'css3-mediaqueries.js', $vthemedirs['script']);
		if (is_array($vmediaqueries)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmediaqueries['file']));}
			echo '<script type="text/javascript" src="'.$vmediaqueries['url'].'?ver='.$vjscachebust.'"></script>';
		}
	}
 }
}

// Load FastClick
// --------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_fastclick')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick');
 function bioship_muscle_load_fastclick() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadfastclick'])) {$vload = $vthemesettings['loadfastclick'];}
	$vload = bioship_apply_filters('muscle_load_fastclick', $vload);
	if (!$vload) {return;}

	// 1.8.5: adding missing filemtime cachebusting option
	$vfastclick = bioship_file_hierarchy('both', 'fastclick.js', $vthemedirs['script']);
	if (is_array($vfastclick)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vfastclick['file']));}
		wp_enqueue_script('fastclick', $vfastclick['url'], array('jquery'), $vjscachebust,true);
	}
 }
}

// Load Mousewheel
// ---------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_mousewheel')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_mousewheel');
 function bioship_muscle_load_mousewheel() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadmousewheel'])) {$vload = $vthemesettings['loadmousewheel'];}
	// 2.0.1: fix to reused code typo in filter variable
	$vload = bioship_apply_filters('muscle_load_mousewheel', $vload);
	if (!$vload) {return;}

	// 1.9.0: fix to file hierarchy call (both not url)
	$vmousewheel = bioship_file_hierarchy('both', 'mousewheel.js', $vthemedirs['script']);
	if (is_array($vmousewheel)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmousewheel['file']));}
		wp_enqueue_script('mousewheel', $vmousewheel['url'], array('jquery'), $vjscachebust, true);
	}
 }
}

// Load CSS.Supports
// -----------------
// 2.0.1: check themeoptions internally to allow filtering
if (!function_exists('bioship_muscle_load_csssupports')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_csssupports');
 function bioship_muscle_load_csssupports() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;

	if (isset($vthemesettings['loadcsssupports'])) {$vload = $vthemesettings['loadcsssupports'];}
	$vload = bioship_apply_filters('muscle_load_csssupports', $vload);
	if (!$vload) {return;}

	$vcsssupports = bioship_file_hierarchy('url', 'CSS.supports.js', $vthemedirs['script']);
	if (is_array($vcsssupports)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vcsssupports['file']));}
		wp_enqueue_script('csssupports', $vcsssupports, array(), $vjscachebust, true);
	}
 }
}

// MatchMedia.js
// -------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_match_media_script')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_match_media_script');
 function bioship_muscle_match_media_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 2.0.1: fix to old themeoptions global typo
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadmatchmedia'])) {$vloadmatchmedia = $vthemesettings['loadmatchmedia'];}
	$vload = bioship_apply_filters('muscle_load_matchmedia', $vload);
	if (!$vload) {return;}

	// 1.9.5: fixed to file hierarchy call
	$vmatchmedia = bioship_file_hierarchy('both', 'matchMedia.js', $vthemedirs['script']);
	if (is_array($vmatchmedia)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmatchmedia['file']));}
		wp_enqueue_script('matchmedia', $vmatchmedia['url'], array('jquery'), $vjscachebust, true);

		// 1.9.5: fixed to file hierarchy call
		$vmatchmedialistener = bioship_file_hierarchy('both', 'matchMedia.addListener.js', $vthemedirs['script']);
		if (is_array($vmatchmedialistener)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmatchmedialistener['file']));}
			wp_enqueue_script('matchmedialistener', $vmatchmedialistener['url'], array('jquery','matchmedia'), $vjscachebust,true);
		}
	}
 }
}

// Load Modernizr
// --------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_modernizr')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr');
 function bioship_muscle_load_modernizr() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars, $vjscachebust; $vload = 'off';

	if (isset($vthemesettings['load'])) {$vload = $vthemesettings['loadmodernizr'];}
	$vload = bioship_apply_filters('muscle_load_modernizr', $vload);
	// 2.0.2: fix to simplified variable typo
	if ( (!$vload) || ($vload == 'off') ) {return;}

	// 2.0.1: use filtered value here also
	if ($vload == 'production') {
		// (with fallback to development version)
		$vmodernizr = bioship_file_hierarchy('both', 'modernizr.js', array('includes/foundation5/js/vendor','javascripts','js','assets/js'));
	} elseif ($vload == 'development') {
		// (with fallback to production version)
		$vmodernizr = bioship_file_hierarchy('both', 'modernizr.js', array('javascripts','includes/foundation5/js/vendor','js','assets/js'));
	}
	if (is_array($vmodernizr)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmodernizr['file']));}
		wp_enqueue_script('modernizr', $vmodernizr['url'], array('jquery'), $vjscachebust, true);
		// 2.0.9: add javascript variable to automatically initialize modernizr
		$vthemevars[] = "var loadmodernizr = 'yes'; ";
	}
 }
}

// --------------
// === Extras ===
// --------------

// Load Smooth Scrolling
// ---------------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_smooth_scrolling')) {
 add_action('wp_footer','bioship_muscle_smooth_scrolling');
 function bioship_muscle_smooth_scrolling() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars; $vload = false;
	if (isset($vthemesettings['smoothscrolling'])) {$vload = $vthemesettings['smoothscrolling'];}
	$vload = bioship_apply_filters('muscle_smooth_scrolling', $vload);
	if (!$vload) {return;}

	// adds run trigger to footer (detected by bioship-init.js)
	// 2.0.9: use theme load variables instead of input field
	$vthemevars[] = "var smoothscrolling = 'yes'; ";
 }
}

// Load jQuery matchHeight
// -----------------------
// 1.9.9: added this for content grid (and other) usage
if (!function_exists('bioship_muscle_load_matchheight')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_matchheight');
 function bioship_muscle_load_matchheight() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadmatchheight'])) {$vload = $vthemesettings['loadmatchheight'];}
	$vload = bioship_apply_filters('muscle_load_matchheight', $vload);
	if (!$vload) {return;}

	$vmatchheight = bioship_file_hierarchy('both', 'jquery.matchHeight.js', $vthemedirs['script']);
	if (is_array($vmatchheight)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vmatchheight['file']));}
		wp_enqueue_script('matchheight', $vmatchheight['url'], array('jquery'), $vjscachebust, true);

		// add run trigger to footer (detected by bioship-init.js)
		// 2.0.9: use theme load variables instead of input field
		$vthemevars[] = "var loadmatchheights = 'yes'; ";
	}
 }
}

// Load Sticky Kit
// ---------------
// 1.5.0: Added Sticky Kit Loading
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_stickykit')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_stickykit');
 function bioship_muscle_load_stickykit() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 1.8.5: seems to cause customizer some troubles
	// 1.9.9: add pagenow check also for same reason
	global $pagenow;
	if ( ($pagenow == 'customizer.php') || (is_customize_preview()) ) {return;}

	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadstickykit'])) {$vload = $vthemesettings['loadstickykit'];}
	// 1.9.9: fix to incorrect filter name typo
	$vload = bioship_apply_filters('muscle_load_stickykit', $vload);
	if (!$vload) {return;}

	$vstickykit = bioship_file_hierarchy('both', 'jquery.sticky-kit.min.js', $vthemedirs['script']);
	if (is_array($vstickykit)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vstickykit['file']));}
		wp_enqueue_script('stickykit', $vstickykit['url'], array('jquery'), $vjscachebust, true);

		// 2.0.9: set stickykit elements array variable instead of input field
		$vstickyelements = bioship_apply_filters('muscle_sticky_elements', $vthemesettings['stickyelements']);
		if (!$vstickyelements) {return;}
		if (is_string($vstickyelements)) {
			if ($vstickyelements == '') {return;}
			elseif (strstr($vstickyelements, ',')) {$vstickyelements = explode(',', $vfitvidselements);}
			else {$vstickyelements[0] = $vstickyelements;
		}
		if ( (!is_array($vstickyelements)) || (count($vstickyelements) < 1) ) {return;}
		$vscriptvar = "var stickyelements = new Array(); ";
		foreach ($vstickyelements as $vi => $velement) {
			$vscriptvar .= "stickyelements[".$vi."] = '".trim($velement)."'; ";
		}
		$vthemevars[] = $vscriptvar;
	}
 }
}

// Load FitVids
// ------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_fitvids')) {
 add_action('wp_enqueue_scripts','bioship_muscle_load_fitvids');
 function bioship_muscle_load_fitvids() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadfitvids'])) {$vload = $vthemesettings['loadfitvids'];}
	$vload = bioship_apply_filters('muscle_load_fitvids', $vload);
	if (!$vload) {return;}

	$vfitvids = bioship_file_hierarchy('both', 'jquery.fitvids.js', $vthemedirs['script']);
	if (is_array($vfitvids)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vfitvids['file']));}
		wp_enqueue_script('fitvids', $vfitvids['url'], array('jquery'), $vjscachebust, true);

		// 2.0.9: set fitvids elements array variable instead of input field
		$vfitvidselements = bioship_apply_filters('muscle_fitvids_elements', $vthemesettings['fitvidselements']);
		if (!$vfitvidselements) {return;}
		if (is_string($vfitvidselements)) {
			if ($vfitvidselements == '') {return;}
			elseif (strstr($vfitvidselements, ',')) {$vfitvidselements = explode(',', $vfitvidselements);}
			else {$vfitvidselements[0] = $vfitvidselements;
		}
		if ( (!is_array($vfitvidselements)) || (count($vfitvidselements) < 1) ) {return;}
		$vscriptvar = "var fitvidselements = new Array(); ";
		foreach ($vfitvidselements as $vi => $velement) {
			$vscriptvar .= "fitvidselements[".$vi."] = '".trim($velement)."'; ";
		}
		$vthemevars[] = $vscriptvar;
	}
 }
}

// Load ScrollToFixed
// ------------------
// 1.5.0: added Scroll To Fixed library
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_scrolltofixed')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_scrolltofixed');
 function bioship_muscle_load_scrolltofixed() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust; $vload = false;
	if (isset($vthemesettings['loadscrolltofixed'])) {$vload = $vthemesettings['loadscrolltofixed'];}
	$vload = bioship_apply_filters('muscle_load_scrolltofixed', $vload);
	if (!$vload) {return;}

	$vscrolltofixed = bioship_file_hierarchy('both', 'jquery-scrolltofixed.min.js', $vthemedirs['script']);
	if (is_array($vscrolltofixed)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vjscachebust = date('ymdHi', filemtime($vscrolltofixed['file']));}
		wp_enqueue_script('scrolltofixed', $vscrolltofixed['url'], array('jquery'), $vjscachebust, true);
	}
 }
}

// Logo Resize Switch
// ------------------
// 1.8.5: added this input switch for init.js
if (!function_exists('bioship_muscle_logo_resize')) {
 add_action('wp_footer', 'bioship_muscle_logo_resize');
 function bioship_muscle_logo_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemevars; $vload = false;
    if (isset($vthemesettings['logoresize'])) {$vload = $vthemesettings['logoresize'];}
    $vload = bioship_apply_filters('muscle_logo_resize', $vload);
    if (!$vload) {return;}

    // add run trigger to footer (detected by bioship-init.js)
    // 2.0.9: use theme load variables instead of input field
    $vthemevars[] = "var logoresize = 'yes'; ";
 }
}

// Site Text Resize Switch
// -----------------------
// 2.0.9: expermental feature for bioship-init.js
if (!function_exists('bioship_muscle_site_text_resize')) {
 add_action('wp_footer', 'bioship_muscle_site_text_resize');
 function bioship_muscle_site_text_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemelayout, $vthemevars; $vload = false;
    if (isset($vthemesettings['sitetextresize'])) {$vload = $vthemesettings['sitetextresize'];}
    $vload = bioship_apply_filters('muscle_site_text_resize', $vload);
    if (!$vload) {return;}

    // add run trigger to footer (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
    $vthemevars[] = "var sitetextresize = 'yes'; ";
 }
}

// Header Resize Switch
// --------------------
// 2.0.9: experimental feature for bioship-init.js
if (!function_exists('bioship_muscle_header_resize')) {
 add_action('wp_footer', 'bioship_muscle_header_resize');
 function bioship_muscle_header_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemelayout, $vthemevars; $vload = false;
    if (isset($vthemesettings['headerresize'])) {$vload = $vthemesettings['headerresize'];}
    $vload = bioship_apply_filters('muscle_header_resize', $vload);
    if (!$vload) {return;}

    // add run trigger to footer (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
	$vthemevars[] = "var headerresize = 'yes'; ";}
 }
}

// Output Script Loading Variables
// -------------------------------
// 2.0.9: added for outputting script load variables (for bioship-init.js)
add_action('wp_footer', 'bioship_muscle_output_script_vars', 11);
if (!function_exists('bioship_muscle_output_script_vars')) {
 function bioship_muscle_output_script_vars() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemevars, $vthemelayout;
 	if ( (!is_array($vthemevars)) || (count($vthemevars) < 1) ) {return;}
 	echo "<script>";
	echo "var maxwidth = '".$vthemelayout['maxwidth']."'; ";
 	foreach ($vthemevars as $vscriptvar) {echo $vscriptvar.PHP_EOL;}
 	echo "</script>";
 }
}


// ------------------
// === Thumbnails ===
// ------------------

// JPEG Quality Filter
// -------------------
// 2.0.5: added a jpeg quality filter
if (!function_exists('bioship_muscle_jpeg_quality')) {
 add_filter('jpeg_quality', 'bioship_muscle_jpeg_quality', 10, 2);
 function bioship_muscle_jpeg_quality($vquality, $vcontext) {
	global $vthemesettings;
	if (isset($vthemesettings['jpegquality'])) {
		$vqual = $vthemesettings['jpegquality'];
		if ( ($vqual != '') && ($vqual != '0') ) {
			$vqual = absint($vqual);
			if ( ($vqual > 0) && ($vqual < 101) ) {$vquality = $vqual;}
		}
	}
	return $vquality;
 }
}

// Allow Thumbnail Size override on upload for CPTs
// ------------------------------------------------
// (note: post type support for the CPT must be active via theme options)
// each filter must be explicity set, ie. muscle_post_type_thumbsize_{size}
// ref: http://wordpress.stackexchange.com/questions/6103/change-set-post-thumbnail-size-according-to-post-type-admin-page

if (!function_exists('bioship_muscle_thumbnail_size_custom')) {
 add_filter('intermediate_image_sizes_advanced', 'bioship_muscle_thumbnail_size_custom', 10);
 function bioship_muscle_thumbnail_size_custom($vsizes) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// rather funny way of doing it but seems to work fine
	// as this is for the admin post/page editing screen
	if (isset($_REQUEST['post_id'])) {
		$vpostid = $_REQUEST['post_id'];
		$vposttype = get_post_type($vpostid);
	} else {
		// CHECKME: what to do for new (draft) posts (ie. with no saved post ID yet)?
		return;
	}

	// get default thumbnail size options (as in theme setup)
	global $vthemesettings;
	$vthumbnailwidth = bioship_apply_filters('skeleton_thumbnail_width', 250);
	$vthumbnailheight = bioship_apply_filters('skeleton_thumbnail_height', 250);

	// get croppping options
	$vcrop = get_option('thumbnail_crop');
	$vthumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ($vthumbnailcrop == 'nocrop') {$vcrop = false;}
	if ($vthumbnailcrop == 'auto') {$vcrop = true;}
	if (strstr($vthumbnailcrop,'-')) {$vcrop = explode('-', $vthumbnailcrop);}
	$vthumbsize = array($vthumbnailwidth, $vthumbnailheight, $vcrop);

	// now check for a custom filter for this post type
	$vnewthumbsize = bioship_apply_filters('muscle_post_type_thumbsize_'.$vposttype, $vthumbsize);
	if ($vthumbsize != $newthumbsize) {
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
// TODO: add this feature to theme options?
if (!function_exists('bioship_muscle_fading_thumbnails')) {
 // add_filter('the_posts', 'bioship_muscle_fading_thumbnails', 10, 2);
 function bioship_muscle_fading_thumbnails($posts, $query) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	if (!is_archive()) {return $posts;}

 	// TODO: add fading thumbnails loading filter here

	$vcptslug = 'post'; $vfadingthumbs = false;
	$vposttypes = bioship_get_post_types($query);
	if ( (is_array($vposttypes)) && (in_array($vcptslug, $vposttypes)) ) {$vfadingthumbs = true;}
	elseif ($vcptslug == $vposttypes) {$vfadingthumbs = true;}

	if ($vfadingthumbs) {
	    global $vthemelayout; $vthemelayout['fadingthumbnails'] = $vcptslug;
	    if (!had_action('wp_footer', 'bioship_muscle_fading_thumbnail_script')) {
	    	add_action('wp_footer', 'bioship_muscle_fading_thumbnail_script');
	    }
	}

	if (!function_exists('bioship_muscle_fading_thumbnail_script')) {
	 function bioship_muscle_fading_thumbnail_script() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemelayout; $vfadingthumbs = $vlayout['fadingthumbnails'];
		// TODO: allow for multiple CPTs/classes?
		echo "<script>var thumbnailclass = 'img.thumbtype-".$vfadingthumbs."';
		function fadeoutthumbnails() {jQuery(thumbnailclass).fadeOut(3000, fadeinthumbnails);}
		function fadeinthumbnails() {jQuery(thumbnailclass).fadeIn(3000, fadeoutthumbnails);}
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
if (!function_exists('bioship_muscle_select_home_categories')) {
 add_filter('pre_get_posts', 'bioship_muscle_select_home_categories');
 function bioship_muscle_select_home_categories($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	if ($query->is_home()) {
		global $vthemesettings; $vmode = false;
		if (isset($vthemesettings['homecategorymode'])) {$vmode = $vthemesettings['homecategorymode'];}
		$vmode = bioship_apply_filters('muscle_home_category_mode', $vmode);
		if ( (!$vmode) || ($vmode == 'all') ) {return;}
		if ( ($vmode != 'include') && ($vmode != 'exclude') && ($vmode != 'includeexclude') ) {return;}

		// 2.0.0: added category mode/include/exclude filters
		$vincludecategories = bioship_apply_filters('muscle_home_include_categories', $vthemesettings['homeincludecategories']);
		$vexcludecategories = bioship_apply_filters('muscle_home_exclude_categories', $vthemesettings['homeexcludecategories']);

		// 2.0.1: revamped include / exclude logic
		$vcategories = get_categories(); $vselected = array();
		$vincluded = false; $vexcluded = false;
		if (is_array($vincludecategories)) {$vincluded = true;}
		if (is_array($vexcludecategories)) {$excluded = true;}

		foreach ($vcategories as $vcategory) {
			$vcatid = $vcategory->cat_ID;
			if ( ($vincluded) && ( ($vmode == 'include') || ($vmode == 'includeexclude') ) ) {
				if (isset($vincludecategories[$vcatid])) {$vselected[] = $vcatid;}
			}
			if ( ($vexcluded) && ( ($vmode == 'exclude') || ($vmode == 'includeexclude') ) ) {
				if (isset($vexcludecategories[$vcatid])) {$vselected[] = '-'.$vcatid;}
			}
		}

		if (count($vselected) > 0) {
			$vcatstring = implode(' ', $vselected);
			$query->set('cat', $vcatstring);
		}
	}
	return $query;
 }
}

// Number of Search Results per Page
// ---------------------------------
// 2.0.1: filter themesettings internally
add_action('pre_get_posts', 'bioship_muscle_search_results_per_page');
if (!function_exists('bioship_muscle_search_results_per_page')) {
 function bioship_muscle_search_results_per_page($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings, $wp_the_query;
	// 2.0.0: added muscle_search_results filter
	$vsearchresults = bioship_apply_filters('muscle_search_results', $vthemesettings['searchresults']);
	$vsearchresults = absint($vsearchresults);
	if (is_numeric($vsearchresults)) {
		if ( (!is_admin()) && ($query === $wp_the_query) && ($query->is_search()) ) {
			$query->set('posts_per_page', $vsearchresults);
		}
	}
	return $query;
 }
}

// Make Custom Post Types Searchable
// ---------------------------------
// 2.0.1: filter themesettings internally
if (!function_exists('bioship_muscle_searchable_cpts')) {
 if (is_search()) {add_filter('the_search_query','bioship_muscle_searchable_cpts');}
 function bioship_muscle_searchable_cpts($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings; $vsearchablecpts = false;
	if (isset($vthemesettings['searchablecpts'])) {$vsearchablecpts = $vthemesettings['searchablecpts'];}
	$vsearchablecpts = bioship_apply_filters('muscle_searchable_cpts', $vsearchablecpts);

	// 2.0.1: fix to search logic array here
	if ( (is_array($vsearchablecpts)) && (count($vsearchablecpts) > 0) ) {
		$vcpts = array();
		foreach ($vsearchablecpts as $vcpt => $vvalue) {
			if ($vvalue == '1') {$vcpts[] = $vcpt;}
		}
		if ($query->is_search) {$query->set('post_type', $vcpts);}
	}
	return $query;
 }
}

// Jetpack Infinite Scroll Support
// -------------------------------
// Jetpack Infinite Scroll info: http://jetpack.me/support/infinite-scroll/
// also could use AJAX Load More: https://wordpress.org/plugins/ajax-load-more/
// Loading Span selector: span.infinite-loader (default image: /images/infinite-loader.gif)
// Load More Button selector: div.infinite-handler (for click only, not scroll)

if (!function_exists('bioship_muscle_jetpack_scroll_setup')) {
 add_action('after_setup_theme', 'bioship_muscle_jetpack_scroll_setup');
 function bioship_muscle_jetpack_scroll_setup() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings; $vload = false;
	if (isset($vthemesettings['infinitescroll'])) {$vload = $vthemesettings['infinitescroll'];}
	$vload = bioship_apply_filters('muscle_load_infinitescroll', $vload);
	if ( ($vload != 'scroll') && ($vload != 'click') ) {return;}

	$vfootersidebars = $vthemesettings['footersidebars'];
	if ($vfootersidebars > 0) {$vfooterwidgets[0] = 'footer-widget-area-1';}
	if ($vfootersidebars > 1) {$vfooterwidgets[1] = 'footer-widget-area-2';}
	if ($vfootersidebars > 2) {$vfooterwidgets[2] = 'footer-widget-area-3';}
	if ($vfootersidebars > 3) {$vfooterwidgets[3] = 'footer-widget-area-4';}

	$vsettings = array(
		'type' => $vload,
		'container' => 'content',
		'footer' => 'footer',
		'footer_widgets', $vfooterwidgets,
		'wrapper' => 'infinite-wrap',
		'render' => 'muscle_infinite_scroll_loop'
	);

	// 1.8.0: added override filters
	$vpostsperpage = bioship_apply_filters('skeleton_infinite_scroll_numposts', '');
	if (is_numeric($vpostsperpage)) {$vsettings['posts_per_page'] = $vpostsperpage;}
	$vsettings = bioship_apply_filters('skeleton_infinite_scroll_settings', $vsettings);

	add_theme_support('infinite-scroll', $vsettings);

	// 2.0.1: moved this inside loader
	if (!function_exists('bioship_muscle_infinite_scroll_loop')) {
	 function bioship_muscle_infinite_scroll_loop() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// TODO: maybe update Infinite Scroll to use/match AJAX Load More Template?
		// 1.5.0: fix: always use hybrid content hierarchy
		while (have_posts()) {
			the_post();
			hybrid_get_content_template();
		}
	 }
	}

 }
}



// -----------------------------
// === Excerpt and Read More ===
// -----------------------------

// Add Excerpt Support to Pages
// ----------------------------
// 1.8.0: add page excerpt support option
if ( (isset($vthemesettings['pageexcerpts'])) && ($vthemesettings['pageexcerpts'] == '1') ) {
	add_post_type_support('page', 'excerpt');
}

// Enable Shortcodes in Excerpts
// -----------------------------
if ($vthemesettings['excerptshortcodes'] == '1') {
	// 1.9.8: very much "doing it wrong"! - replaced these filters...
	//	add_filter('the_excerpt', 'do_shortcode');
	//	add_filter('get_the_excerpt', 'do_shortcode');
	if (has_filter('get_the_excerpt', 'wp_trim_excerpt')) {
		remove_filter('get_the_excerpt', 'wp_trim_excerpt');
		add_filter('get_the_excerpt', 'bioship_muscle_excerpts_with_shortcodes');
	}
}

// Excerpts with Shortcodes
// ------------------------
// 1.9.8: copy of wp_trim_excerpt but with shortcodes kept
// note: formatting is still stripped but shortcode text remains
if (!function_exists('bioship_muscle_excerpts_with_shortcodes')) {
 function bioship_muscle_excerpts_with_shortcodes($text) {
	// for use in shortcodes to provide alternative output
	global $doingexcerpt; $doingexcerpt = true;

	$text = get_the_content('');
	// $text = strip_shortcodes( $text ); // modification
	$text = bioship_apply_filters( 'the_content', $text );
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = bioship_apply_filters( 'excerpt_length', 55 );
	$excerpt_more = bioship_apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	$doingexcerpt = false; return $text;
 }
}

// User Defined Excerpt Length
// ---------------------------
// 1.8.5: move checks to inside filter
if (!function_exists('bioship_muscle_excerpt_length')) {
	add_filter('excerpt_length','bioship_muscle_excerpt_length');
	// 2.0.5: move old pseudonym to compat.php
	function bioship_muscle_excerpt_length($vlength) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings;

		// 1.8.5: added alternative feed excerpt length
		// 2.0.9: fix to old themeoption variable
		if (is_feed()) {
			if ( (isset($vthemesettings['rssexcerptlength'])) && ($vthemesettings['rssexcerptlength'] != '') ) {
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
	// 2.0.5: move old pseudonym to compat.php
	if (!function_exists('bioship_muscle_continue_reading_link')) {
	 function bioship_muscle_continue_reading_link() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;
		// 2.0.0: added muscle_read_more_anchor filter
		$vreadmoreanchor = bioship_apply_filters('muscle_read_more_anchor', $vthemesettings['readmoreanchor']);
		return ' <a href="'.get_permalink().'">'.$vreadmoreanchor.'</a>';
	 }
	}
}

// Read More Before and After
// --------------------------
// Default = ' &hellip;';
// 2.0.5: removed outside settings check so filtered
// 2.0.5: move old pseudonym to compat.php
if (!function_exists('bioship_muscle_auto_excerpt_more')) {
 add_filter('excerpt_more', 'bioship_muscle_auto_excerpt_more');
 function bioship_muscle_auto_excerpt_more($vmore) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;
	// 2.0.0: added muscle_read_more_before filter
	$vreadmorebefore = bioship_apply_filters('muscle_read_more_filter', $vthemesettings['readmorebefore']);

	if (function_exists('bioship_muscle_continue_reading_link')) {
		return '<div class="readmore">'.$vthemesettings['readmorebefore'].bioship_muscle_continue_reading_link().'</div>';
	} else {
		$vdefault = ' <a href="'.get_permalink().'">'.__('Continue reading','bioship').' <span class="meta-nav">&rarr;</span></a>';
		// 2.0.9: added default continue reading link back into readmore div
		return '<div class="readmore">'.$vthemesettings['readmorebefore'].$vdefault.'</div>';
	}
 }
}

// Remove More 'Jump' Link
// -----------------------
// TODO: maybe add a theme option for removing jump link?
if (!function_exists( 'bioship_muscle_remove_more_jump_link')) {
 add_filter('the_content_more_link', 'bioship_muscle_remove_more_jump_link');
 function bioship_muscle_remove_more_jump_link($vlink) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$voffset = strpos($vlink, '#more-');
	// 2.0.9: fix to link variable typo
	if ($voffset) {$vend = strpos($vlink, '"', $voffset);}
	if ($vend) {$vlink = substr_replace($vlink, '', $voffset, ($vend-$voffset));}
	return $vlink;
 }
}

// ---------------
// === Writing ===
// ---------------

// Limit Post Revisions
// --------------------
// 1.8.0: [deprecated] moved to separate AutoSave Net plugin

// WP Subtitle Custom Post Type Support
// ------------------------------------
add_action('init','bioship_muscle_wp_subtitle_custom_support');
if (!function_exists('bioship_muscle_wp_subtitle_custom_support')) {
 function bioship_muscle_wp_subtitle_custom_support() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (!function_exists('get_the_subtitle')) {return;}
	global $vthemesettings;
	$vcptsubtitles = $vthemesettings['subtitlecpts'];
	// 2.0.8: fix for possible empty subtitle setting
	if (is_array($vcptsubtitles)) {
		foreach ($vcptsubtitles as $vcpt => $vvalue) {
			if ($vvalue) {
				if ( ($vcpt != 'post') && ($vcpt != 'page') ) {add_post_type_support($vcpt, 'wps_subtitle');}
			} else {
				if ($vcpt == 'post') {remove_post_type_support('post', 'wps_subtitle');}
				if ($vcpt == 'page') {remove_post_type_support('page', 'wps_subtitle');}
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
// 2.0.5: added missing setting filter
$vautofeedlinks = false;
if ( (isset($vthemesettings['autofeedlinks'])) && ($vthemesettings['autofeedlinks'] == '1') ) {
	$vautofeedlinks = true;
}
$vautofeedlinks = bioship_apply_filters('muscle_automatic_feed_links', $vautofeedlinks);
if ($vautofeedlinks) {add_theme_support('automatic-feed-links');}
else {remove_theme_support('automatic-feed-links');}

// RSS Publish Delay
// -----------------
// 2.0.5: check setting internally to allow filtering
if (!function_exists('bioship_muscle_delay_feed_publish')) {

 add_filter('posts_where', 'bioship_muscle_delay_feed_publish');

 function bioship_muscle_delay_feed_publish($where) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $wpdb, $vthemesettings;

	// 2.0.5: added missing setting filter
	if (!is_feed()) {return $where;}

	// 2.1.0: still allow filtering if not set (wp.org version)
	if (!isset($vthemesettings['rsspublishdelay'])) {$vdelay = false;}
	else {$vdelay = $vthemesettings['rsspublishdelay'];}
	$vdelay = bioship_apply_filters('muscle_rss_feed_publish_delay', $vdelay);
	if ( (!is_numeric($vdelay)) || ($vdelay < 1) ) {return $where;}

	$vnow = gmdate('Y-m-d H:i:s');
	// ref: http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
	$vunits = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
	$vunits = bioship_apply_filters('muscle_rss_feed_delay_units', $vunits);
	// add SQL-sytax to default $where
	$where .= " AND TIMESTAMPDIFF(".$vunits.", $wpdb->posts.post_date_gmt, '".$vnow."') > ".$vdelay." ";

	return $where;
 }
}

// Define Post Types in RSS Feed
// -----------------------------
// 2.0.5: check settings internally to allow filtering
// 2.0.5: simplified logic for this filter function
if (!function_exists('bioship_muscle_custom_feed_request')) {

 add_filter('request', 'bioship_muscle_custom_feed_request');

 function bioship_muscle_custom_feed_request($vars) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;
	if (!is_feed()) {return $vars;}

	// 2.1.0: set to false if setting is not present (wp.org version)
	if (!isset($vthemesettings['cptsinfeed'])) {$vcptsinfeed = false;}
	else {$vcptsinfeed = $vthemesettings['cptsinfeed'];}

	$vcptsinfeed = bioship_apply_filters('muscle_rss_feed_post_types', $vcptsinfeed);
	if (THEMEDEBUG) {echo "<!-- Feed CPTs: "; print_r($vthemesettings['cptsinfeed']); echo " -->";}

	if (is_array($vcptsinfeed)) {
		if ( (isset($vars['feed'])) && (!isset($vars['post_type'])) ) {
			// ? CHECKME: recheck whether this is still working as desired
			$vars['post_type'] = $vcptsinfeed;
		}
	}
	return $vars;
 }
}

// Full Content RSS Feed for Pages
// -------------------------------
// ref: http://wordpress.stackexchange.com/a/227455/76440
// 1.8.5: added this option
// 2.0.0: fix to query object typo
// 2.0.1: fix to match function_exists check
// 2.0.5: check setting internally to allow filtering
if (!function_exists('bioship_muscle_rss_page_feed_full_content')) {

 // 2.0.5: move add_action inside for consistency
 add_action('pre_get_posts', 'bioship_muscle_rss_page_feed_full_content');

 function bioship_muscle_rss_page_feed_full_content($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;

	// 2.1.0: default to false if setting is not present (wp.org version)
	if (!isset($vthemesettings['pagecontentfeeds'])) {$vpagefeeds = false;}
	else {$vpagefeeds = $vthemesettings['pagecontentfeeds'];}

	$vpagefeeds = bioship_apply_filters('muscle_rss_full_page_feeds', $vpagefeeds);
	if (!$vpagefeeds) {return $query;}

	// check feed request and for single page only
	if ($query->is_main_query() && $query->is_feed() && $query->is_page()) {
		// set the post type to page
		$query->set('post_type', array('page'));
		// allow for page comments feed via ?withcomments=1
		if ( (isset($_GET['withcomments'])) && ($_GET['withcomments'] == '1') ) {return;}
		// set the comment feed to false
		$query->is_comment_feed = false;
	}

	if (THEMEDEBUG && $query->is_feed()) {
		echo "<!-- Feed Query: "; print_r($query); echo " -->";
	}
 }
}

// RSS Full Page Feed Option Filter
// --------------------------------
// 2.0.0: fix to typo in funcname
if (!function_exists('bioship_muscle_page_rss_excerpt_option')) {

 // 2.0.5: move add_filter inside for consistency
 add_filter('pre_option_rss_use_excerpt', 'bioship_muscle_page_rss_excerpt_option');

 function bioship_muscle_page_rss_excerpt_option($voption) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;
	$vpagefeeds = bioship_apply_filters('muscle_rss_full_page_feeds', $vthemesettings['pagecontentfeeds']);
	if (!$vpagefeeds) {return $voption;}

	// force full content output for pages
	if (is_page()) {return '0';}
	return $voption;
 }
}

// TODO: test strip_shortcode result for excerpt_rss (on multiple installs?)
// (this code causing some troubles)
// add_filter('the_excerpt_rss','bioship_muscle_rss_page_excerpt');
// if (!function_exists('bioship_muscle_rss_page_excerpt')) {
// function bioship_muscle_rss_page_excerpt($excerpt) {
//	  if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
//    if (is_page()) {
//        global $post; $text = $post->post_content;
//        // removed this line otherwise got blank
//        // $text = strip_shortcodes( $text );
//        $text = bioship_apply_filters( 'the_content', $text );
//        $text = str_replace(']]>', ']]&gt;', $text);
//        $excerpt_length = bioship_apply_filters( 'excerpt_length', 55 );
//        $excerpt_more = bioship_apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
//        $excerpt = wp_trim_words( $text, $excerpt_length, $excerpt_more );
//    }
//    return $excerpt;
//  }
// }


// Load the Dashboard Feed
// -----------------------
if (!function_exists('bioship_muscle_add_bioship_dashboard_feed_widget')) {

	$vrequesturi = $_SERVER['REQUEST_URI'];
	// 2.0.1: fix for network string match typo
	if ( (preg_match('|index.php|i', $vrequesturi))
	  || (substr($vrequesturi, -(strlen('/wp-admin/'))) == '/wp-admin/')
	  || (substr($vrequesturi, -(strlen('/wp-admin/network/'))) == '/wp-admin/network/') ) {
		add_action('wp_dashboard_setup', 'bioship_muscle_add_bioship_dashboard_feed_widget');
	}

	function bioship_muscle_add_bioship_dashboard_feed_widget() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 2.0.9: do not add the dashboard feed for WordPress.org version
		if (THEMEWPORG) {return;}
		if ( (current_user_can('manage_options')) || (current_user_can('update_themes'))
		  || (current_user_can('edit_theme_options')) ) {
			// 1.9.9: fix to undefined index warning
			global $wp_meta_boxes; $vfeedloaded = false;
			foreach (array_keys($wp_meta_boxes['dashboard']['normal']['core']) as $vname) {
				if ($vname == 'bioship') {$vfeedloaded = true;}
			}
			if (!$vfeedloaded) {
				wp_add_dashboard_widget('bioship', __('BioShip News','bioship'), 'bioship_muscle_bioship_dashboard_feed_widget');
			}
		}
	}
}

// -----------------------------
// BioShip Dashboard Feed Widget
// -----------------------------
// 1.9.5: added displayupdates argument
// 2.0.0: added displaylinks argument
if (!function_exists('bioship_muscle_bioship_dashboard_feed_widget')) {
 function bioship_muscle_bioship_dashboard_feed_widget($vdisplayupdates=true, $vdisplaylinks=false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemedirs;

	// Display Updates Available
	// -------------------------
	if (!function_exists('admin_theme_updates_available')) {
		// 2.0.0: fix to file hierarchy search dir
		$vadmin = bioship_file_hierarchy('file', 'admin.php', $vthemedirs['admin']);
		include_once($vadmin);
	}
	if ($vdisplayupdates) {echo admin_theme_updates_available();}

	// Load the Update News Feed
	// -------------------------
	$vbaseurl = THEMEHOMEURL;
	$vrssurl = $vbaseurl."/feed/";

	// 1.8.0: set transient for daily feed update only
	// 2.0.0: clear feed transient for debugging
	delete_transient('bioship_feed');
	if (THEMEDEBUG) {$vfeed = ''; delete_transient('bioship_feed');}
	else {$vfeed = trim(get_transient('bioship_feed'));}

	if ( (!$vfeed) || ($vfeed == '') ) {
		$vrssfeed = fetch_feed($vrssurl); $vfeeditems = 4;
		$vfeed = bioship_muscle_process_rss_feed($vrssfeed, $vfeeditems);
		if ($vfeed != '') {set_transient('bioship_feed', $vfeed, (24*60*60));}
	}

	// 1.8.0: set link hover class
	echo "<style>.themefeedlink {text-decoration:none;} .themefeedlink:hover {text-decoration:underline;}</style>";

	// 2.0.0: add documentation, development and extensions links
	if ($vdisplaylinks) {
		echo "<center><b><a href='".THEMEHOMEURL."/documentation/' class='themefeedlink' target=_blank>".__('Documentation','bioship')."</a></b> | ";
		echo "<b><a href='".THEMEHOMEURL."/development/' class='themefeedlink' target=_blank>".__('Development','bioship')."</a></b> | ";
		echo "<b><a href='".THEMEHOMEURL."/extensions/' class='themefeedlink' target=_blank>".__('Extensions','bioship')."</a></b></center><br>";
	}

	// 1.8.5: fix to typo on close div ruining admin page
	// 2.0.0: re-arrange display output and styles
	echo "<div id='bioshipfeed'>";
	echo "<div style='float:right;'>&rarr;<a href='".$vbaseurl."/category/news/' class='themefeedlink' target=_blank> ".__('More','bioship')."...</a></div>";
	if ($vfeed != '') {echo $vfeed;} else {echo __('Feed currently unavailable.','bioship'); delete_transient('bioship_feed');}
	echo "</div>";

 }
}

// Process RSS Feed
// ----------------
if (!function_exists('bioship_muscle_process_rss_feed')) {
 function bioship_muscle_process_rss_feed($vrss,$vfeeditems) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.0: fix to undefined index warning
	$vprocessed = ''; if (is_wp_error($vrss)) {return '';}

	$vmaxitems = $vrss->get_item_quantity($vfeeditems);
	$vrssitems = $vrss->get_items(0, $vmaxitems);

	if (count($vrssitems) > 0) {
		$vprocessed = "<ul style='list-style:none;margin:0;text-align:left;'>";
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
// TODO: fix unworking admin thumbnail column display
// 2.0.5: check setting internally to allow filtering
if (!function_exists('bioship_muscle_admin_post_thumbnail_column')) {
 add_filter('manage_posts_columns', 'bioship_muscle_admin_post_thumbnail_column', 5);
 // add_filter('manage_pages_columns','bioship_muscle_admin_post_thumbnail_column',5);
 function bioship_muscle_admin_post_thumbnail_column($vcols){
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// TODO: Add a filter for thumbnail column use with other CPTs?
	// ...which would allow for post or page selection also...
	$vthumbcols = $vthemesettings['adminthumbnailcolumn'];
	$vthumbcols = bioship_apply_filters('muscle_post_thumbnail_column', $vthumbcols);
	if (!$vthumbcols) {return $vcols;}

	add_action('manage_posts_custom_column', 'bioship_muscle_display_post_thumbnail_column', 5, 2);
	// TODO: check featured image support for pages here
	// add_action('manage_pages_custom_column', 'bioship_muscle_display_post_thumbnail_column', 5, 2);

	$vcols['post_thumb'] = __('Thumbnail','bioship'); return $vcols;
 }
}

// Post Thumbnail Display Callback
// -------------------------------
if (!function_exists('bioship_muscle_display_post_thumbnail_column')) {
 function bioship_muscle_display_post_thumbnail_column($vcol, $vid) {
	if ($vcol == 'post_thumb') {echo the_post_thumbnail('admin-list-thumb');}
 }
}

// Add "All Options" Page to Settings Menu
// ---------------------------------------
if (!function_exists('bioship_muscle_all_options_link')) {

 // 2.0.1: moved filter option internally
 add_action('admin_menu', 'bioship_muscle_all_options_link', 11);

 function bioship_muscle_all_options_link() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $submenu; $vaddlink = false;
	if (isset($vthemesettings['alloptionspage'])) {$vaddlink = $vthemesettings['alloptionspage'];}
	$vaddlink = bioship_apply_filters('muscle_all_options_page', $vaddlink);
	if ($vaddlink == '1') {
		// 2.0.7: changed to use add_theme_page instead of add_options_page
		// add_options_page(__('All Options','bioship'), __('All Options','bioship'), 'manage_options', 'options.php');
		add_theme_page(__('All Options','bioship'), __('All Options','bioship'), 'manage_options', 'options.php');

		// 2.0.7: then shift from themes to settings menu
		// 2.1.0: check submenu key exists before looping
		if (isset($submenu['options-general.php'])) {
			foreach ($submenu['options-general.php'] as $vkey => $vvalues) {$vlastkey = $vkey + 1;}
		}
		if (isset($submenu['themes.php'])) {
			foreach ($submenu['themes.php'] as $vkey => $vvalues) {
				if ($vvalues[2] == 'options.php') {
					$submenu['options-general.php'][$vlastkey] = $vvalues;
					unset($submenu['themes.php'][$vkey]);
				}
			}
		}
	}
 }
}


// Remove Update Notice
// --------------------
if (!function_exists('bioship_muscle_remove_update_notice')) {
 add_action('init', 'bioship_muscle_remove_update_notice', 1);
 function bioship_muscle_remove_update_notice() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vremove = false;
	if (isset($vthemesettings['removeupdatenotice'])) {$vremove = $vthemesettings['removeupdatenotice'];}
	$vremove = bioship_apply_filters('muscle_remove_update_notice', $vremove);
	if ($vremove != '1') {return;}

	if (!current_user_can('update_plugins')) {
		// 2.0.1: simplify to remove version check action here
		remove_action('init', 'wp_version_check');
		add_filter('pre_option_update_core', '__return_null');
	}
 }
}

// Stop New User Notifications
// ---------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_stop_new_user_notifications')) {
 add_action('phpmailer_init', 'bioship_muscle_stop_new_user_notifications');
 function bioship_muscle_stop_new_user_notifications() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vdisable = false;
	if (isset($vthemesettings['disablenotifications'])) {$vdisable = $vthemesettings['disablenotifications'];}
	$vdisable = bioship_apply_filters('muscle_stop_new_user_notifications', $vdisable);
	if ($vdisable != '1') {return;}

	global $phpmailer;
	if (is_multisite()) {
		// 2.0.7: added missing translation wrapper
		// 2.0.9: duh, removed translation wrapper
		$subject = 'New User Registration';
		if ($phpmailer->Subject == $subject) {$phpmailer = new PHPMailer(true);}
	} else {
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		// 2.0.7: added missing text domain
		// 2.0.9: duh, removed text domain again
		$subject = array(
			sprintf(__('[%s] New User Registration'), $blogname),
			sprintf(__('[%s] Password Lost/Changed'), $blogname)
		);
		if (in_array($phpmailer->Subject, $subject)) {$phpmailer = new PHPMailer(true);}
	}
 }
}

// Disable Self Pings
// ------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_disable_self_pings')) {
 add_action('pre_ping','bioship_muscle_disable_self_pings');
 // 2.0.0: remove unneeded pass by reference in argument
 function bioship_muscle_disable_self_pings($vlinks) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings; $vdisable = false;
	if (isset($vthemesettings['disableselfpings'])) {$vdisable = $vthemesettings['disableselfpings'];}
	$vdisable = bioship_apply_filters('muscle_disable_self_pings', $vdisable);
	if ($vdisable != '1') {return;}

	// 1.5.5: fix to use home_url for theme check
	$vhome = home_url(); // $vhome = get_option('home');
	foreach ($vlinks as $vi => $vlink) {if (0 === strpos($vlink, $vhome)) {unset($vlinks[$vi]);} }
 }
}

// Cleaner Admin Bar (remove WP links)
// -----------------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_cleaner_adminbar')) {
 add_action('wp_before_admin_bar_render', 'bioship_muscle_cleaner_adminbar');
 function bioship_muscle_cleaner_adminbar() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vclean = false;
	if (isset($vthemesettings['cleaneradminbar'])) {$vclean = $vthemesettings['cleaneradminbar'];}
	$vclean = bioship_apply_filters('muscle_cleaner_admin_bar', $vclean);
	if ($vclean != '1') {return;}

	global $wp_admin_bar;
	// 1.8.0: added array filter for altering adminbar link removal
	$vremoveitems = array('wp-logo','about','wporg','documentation','support-forums','feedback');
	$vremoveitems = bioship_apply_filters('admin_adminbar_remove_items', $vremoveitems);

	if (count($vremoveitems) > 0) {
		foreach ($vremoveitems as $vremoveitem) {$wp_admin_bar->remove_menu($vremoveitem);}
	}
 }
}

// Include CPTs in the Dashboard 'Right Now'
// -----------------------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_right_now_content_table_end')) {
 add_action('right_now_content_table_end','bioship_muscle_right_now_content_table_end');
 function bioship_muscle_right_now_content_table_end() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vmodify = false;
	if (isset($vthemesettings['cptsrightnow'])) {$vmodify = $vthemesettings['cptsrightnow'];}
	$vmodify = bioship_apply_filters('muscle_cpts_right_now', $vmodify);
	if ($vmodify != '1') {return;}

	$args = array('public' => true,'_builtin' => false);
	$output = 'object'; $operator = 'and';
	$post_types = get_post_types($args, $output, $operator);
	foreach($post_types as $post_type) {
		$num_posts = wp_count_posts($post_type->name);
		$num = number_format_i18n($num_posts->publish);
		// 2.0.7: added missing text domain
		$singular = $post_type->labels->singular_name;
		$label = $post_type->labels->name;
		$postcount = intval($num_posts->publish);
		$text = _n($singular, $label, $postcount, 'bioship');
		if (current_user_can('edit_posts')) {
			$num = "<a href='edit.php?post_type=$post_type->name'>".$num."</a>";
			$text = "<a href='edit.php?post_type=$post_type->name'>".$text."</a>";
		}
		echo '<tr><td class="first num b b-'.$post_type->name.'">'.$num.'</td>';
		echo '<td class="text t '.$post_type->name.'">'.$text.'</td></tr>';
	}

	$taxonomies = get_taxonomies( $args , $output , $operator );
	foreach ($taxonomies as $taxonomy) {
		$num_terms  = wp_count_terms($taxonomy->name);
		$num = number_format_i18n($num_terms);
		// 2.0.7: added missing text domain
		$singular = $taxonomy->labels->singular_name;
		$label = $taxonomy->labels->name;
		$termcount = intval($num_terms);
		$text = _n($singular, $label, $termcount, 'bioship');
		if (current_user_can('manage_categories')) {
			$num = "<a href='edit-tags.php?taxonomy=".$taxonomy->name."'>".$num."</a>";
			$text = "<a href='edit-tags.php?taxonomy=".$taxonomy->name."'>".$text."</a>";
		}
		echo '<tr><td class="first b b-'.$taxonomy->name.'">'.$num.'</td>';
		echo '<td class="t '.$taxonomy->name.'">'.$text.'</td></tr>';
	}
 }
}

// Login Header URL
// ----------------
if (!function_exists('bioship_muscle_login_headerurl')) {
 add_filter('login_headerurl', 'bioship_muscle_login_headerurl' );
 function bioship_muscle_login_headerurl($vurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// 2.0.9: adding missing theme-specific filtering
	$vurl = apply_filters('muscle_login_header_title', site_url('/'));
	return $vurl;
 }
}

// Login Header Title
// ------------------
if (!function_exists('bioship_muscle_login_headertitle')) {
 add_filter('login_headertitle', 'bioship_muscle_login_headertitle');
 function bioship_muscle_login_headertitle($vtitle) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// 2.0.9: adding missing theme-specific filtering
	$vtitle = apply_filters('muscle_login_header_title', get_bloginfo('name'));
	return $vtitle;
 }
}

// Login Page Logo
// ---------------
// (adds a #loginwrapper element to help styling)
// 1.8.5: fun with login wrapper hacks!
if (!function_exists('bioship_muscle_login_styles')) {
 add_action('login_head', 'bioship_muscle_login_styles');
 function bioship_muscle_login_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	add_filter('login_body_class', 'bioship_muscle_login_body_hack', 999);
	if (!function_exists('bioship_muscle_login_body_hack')) {
	 function bioship_muscle_login_body_hack($vclasses) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$vclasses[] = 'LOGINWRAPPER';
		add_filter('attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2);
		return $vclasses;
	 }
	}
	if (!function_exists('bioship_muscle_login_body_filter_hack')) {
	 function bioship_muscle_login_body_filter_hack($safe_text, $text) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$replace = '"><div id="loginwrapper'; // "
		$safe_text = str_replace('LOGINWRAPPER', $replace, $safe_text);
		remove_filter('attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2);
		return $safe_text;
	 }
	}
	add_action('login_footer', 'bioship_muscle_close_login_wrapper');
	if (!function_exists('bioship_muscle_close_login_wrapper')) {
	 function bioship_muscle_close_login_wrapper() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skin_dynamic_login_css_inline();
		echo "</div><!-- /#loginwrapper -->";
	 }
	}
	// 1.8.5: moved actual login styling to skin.php
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
// Changes directory for Woocommerce templates (for both child and parent theme directories)
// intended so you could use:  /theme/theme-name/templates/woocommerce/
// instead of the default: /theme/theme-name/woocommerce/
// (as a better way of organizing 3rd party templates)
// WARNING: use one directory OR the other, it is not a hierarchy so you cannot use both!


// WooCommerce Template Path Filter
// --------------------------------
if (class_exists('WC_Template_Loader')) {
	add_filter('woocommerce_template_path','bioship_muscle_woocommerce_template_path');
	if (!function_exists('bioship_muscle_woocommerce_template_path')) {
	 function bioship_muscle_woocommerce_template_path($vpath) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// 1.9.5: added this filter to allow further change
		// override woocommerce/ to (filtered) templates/woocommerce/
		$vnewpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce/');
		global $vthemetemplatedir, $vthemestyledir;
		if ( (is_dir($vthemetemplatedir.$vnewpath)) || (is_dir($vthemestyledir.$vnewpath)) ) {
			// 1.9.5: only if new template directory exists do we apply other template filters
			add_filter('wc_get_template', 'bioship_muscle_woocommerce_template', 10, 5);
			add_filter('wc_get_template_part', 'bioship_muscle_woocommerce_template_part', 10, 3);
			return $vnewpath;
		}
		else {return $vpath;}
	 }
	}
}

// /= Woocommerce Template subdirectories Templates =/
// ---------------------------------------------------
if (function_exists('wc_get_template')) {
	if (!function_exists('bioship_muscle_woocommerce_template')) {
		function bioship_muscle_woocommerce_template($located, $template_name, $args, $template_path, $default_path) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// find the new template via file hierarchy
			// looking in templates/woocommerce/ then woocommerce/
			// 1.9.5: apply the template directory filter and search that only
			$vnewpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce/');
			$vnewtemplate = bioship_file_hierarchy('file', $template_name, array($vnewpath));

			// write debug info (kept here as useful for finding templates)
			// ob_start();
			// echo "new template: "; print_r($vnewtemplate); echo PHP_EOL;
			// echo "located: "; print_r($located); echo PHP_EOL;
			// echo "template_name: "; print_r($template_name); echo PHP_EOL;
			// $vdata = ob_get_contents(); ob_end_clean();
			// bioship_write_debug_file('woo-templates.txt',$vdata);

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
 	if (!function_exists('bioship_muscle_woocommerce_template_part')) {
		function bioship_muscle_woocommerce_template_part($vtemplate,$vslug,$vname) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// 1.9.5: apply the template directory filter and search that only
			$vnewpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce/');
			// get slug-name template via file hierarchy
			$vnewtemplate = bioship_file_hierarchy('file', $vslug.'-'.$vname.'.php', array($vnewpath));
			// include a fallback to slug based template
			$vslugtemplate = bioship_file_hierarchy('file', $vslug.'.php', array($vnewpath));

			// write debug info (kept here as useful for finding templates)
			// ob_start();
			// echo "name template (".$vname."): "; print_r($vnewtemplate); echo PHP_EOL;
			// echo "slug template (".$vslug."): "; print_r($vslugtemplate); echo PHP_EOL;
			// $vdata = ob_get_contents(); ob_end_clean();
			// bioship_write_debug_file('woo-template-parts.txt',$vdata)l

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

// Set Open Graph Protocol Default Image
// -------------------------------------
// 1.5.0: added default image meta
// requires Open Graph Protocol plugin to be installed and active
// note: if using Jetpack see filter: jetpack_open_graph_image_default

if (!function_exists('bioship_muscle_open_graph_default_image')) {

 // 2.0.5: move filter inside for consistency
 add_filter('open_graph_protocol_metas', 'bioship_muscle_open_graph_default_image');

 function bioship_muscle_open_graph_default_image($vmetas) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemename, $vthemesettings, $vthemedirs;

	// allow for open graph image override filter
	// see next func for in-built custom field override
	// you can add more conditional overrides via filters.php
	$vimage = array();
	if (isset($vmetas['og:image:width'])) {$vimage[0] = $vmetas['og:image:width'];}
	if (isset($vmetas['og:image:height'])) {$vimage[1] = $vmetas['og:image:height'];}
	if (isset($vmetas['og:image'])) {$vimage[2] = $vmetas['og:image'];}
	$vimage = bioship_apply_filters('muscle_open_graph_override_image', $vimage);

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
			} else {
				// 2.0.9: set missing default value
				$vurltofilepath = false;
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
		} else {
			// same URL, maybe a change in size though
			// as it is an override, just do that
			$vmetas['og:image:width'] = $vimage[0];
			$vmetas['og:image:height'] = $vimage[1];
		}
	}

	// default (fallback) open graph image option
	if (!isset($vmetas['og:image'])) {

		// 1.9.6: removed this code as even 192 does not meet OG minimum of 200
		// maybe pick the largest size if set to precomposed apple touch icons
		// if ($vthemesettings['ogdefaultimage'] == 'appletouchicon') {
		//	$vsizes = array('192','180','152','144','120','114','75','72');
		//	$vfound = false;
		//	foreach ($vsizes as $vsize) {
		//		if (!$vfound) {
		//			$vcheckurl = bioship_file_hierarchy('url','touch-icon-'.$vsize.'x'.$vsize.'-precomposed.png',$vthemedirs['image']);
		//			if ($vcheckurl) {$vurl = $vcheckurl; $vfound = true;}
		//		}
		//	}
		// }
		// else {
			// set the url based on the theme options => suboption
			$vkey = $vthemesettings['ogdefaultimage'];
			if ($vkey == '') {$vkey = 'header_logo';}
			// 1.9.5: fix for uploaded default image
			// 2.0.8: added new open graph image off option
			if ($vkey == 'none') {$vurl = '';}
			elseif ($vkey == 'site_icon') {$vurl = get_site_icon_url();}
			else {$vurl = $vthemesettings[$vkey];}
		// }

		if (THEMEDEBUG) {echo "<!-- Open Graph Default Image URL: ".$vurl." -->";}
		// allow for default open graph image filter
		$vurl = bioship_apply_filters('muscle_open_graph_default_image_url', $vurl);
		if (THEMEDEBUG) {echo "<!-- Filtered OpenGraph URL: ".$vurl." -->";}

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
							// 2.0.5: remove unnecessary add_option fallback
							update_option($vthemename.'_ogdefaultimage', $vimagedata);
						}
					}
				}
				else {
					if ($vurltofilepath) {$vimagesize = getimagesize($vfilepath);}
					else {$vimagesize = getimagesize($vurl);}
					if ($vimagesize) {
						$vimagedata = $vimagesize[0].':'.$vimagesize[1].':'.$vurl;
						// 2.0.5: remove unnecessary add_option fallback
						update_option($vthemename.'_ogdefaultimage', $vimagedata);
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
				$vimagesize = bioship_apply_filters('muscle_open_graph_default_image_size',array());
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

// Add Custom Field Override for the Open Graph image
// --------------------------------------------------
// 1.5.0: added this opengraph override
// requires the Open Graph Protocol plugin to be installed and active
// by default the plugin only sets the featured image if there is one
// this lets you add custom image fields on a post/page screen and have them used:
// opengraphimageurl (required), opengraphimagewidth, opengraphimageheight

if (!function_exists('bioship_muscle_open_graph_override_image_fields')) {

 // 2.0.5: moved inside for consistency
 add_filter('muscle_open_graph_override_image', 'bioship_muscle_open_graph_override_image_fields', 0);

 function bioship_muscle_open_graph_override_image_fields($vimage) {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// override existing open graph image meta with post custom field meta
	// better to set width and height field values but not totally necessary
	global $post; $vpostid = $post->ID;
	$vogimage[0] = get_post_meta($vpostid, 'opengraphimagewidth', true);
	$vogimage[1] = get_post_meta($vpostid, 'opengraphimageheight', true);
	$vogimage[2] = get_post_meta($vpostid, 'opengraphimageurl', true);
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
// 2.0.1: filter Hybrid Hook loading here
$vloadhybridhook = false;
if (isset($vthemesettings['hybridhook'])) {$vloadhybridhook = $vthemesettings['hybridhook'];}
$vloadhybridhook = bioship_apply_filters('muscle_load_hybrid_hook', $vloadhybridhook);

if ($vloadhybridhook == '1') {
	// 1.8.0: changed hybrid hook location to /includes/ subfolder
	$vhybridhook = bioship_file_hierarchy('file', 'hybrid-hook.php', array('includes/hybrid-hook'));
	if ($vhybridhook) {
		include($vhybridhook);
		if (THEMEDEBUG) {echo "<!-- Hybrid Hook Loaded -->".PHP_EOL;}
		// load it now as we have missed the plugins_loaded hook
		hybrid_hook_setup();

		// 1.8.5: dissallow hybrid hook PHP execution via filter (as e-v-a-l commented out for Theme Check)
		// (HTML / Shortcode / Widget methods are better anyway)
		add_filter('hybrid_hook_allow_php', 'bioship_muscle_disallow_hook_php', 5);
		if (!function_exists('bioship_muscle_disallow_hook_php')) {
			function bioship_muscle_disallow_hook_php($v) {return false;}
		}

		// Load the theme layout hooks
		add_filter('hybrid_hooks', 'bioship_muscle_hybrid_get_hooks');
		if (!function_exists('bioship_muscle_hybrid_get_hooks')) {
		 function bioship_muscle_hybrid_get_hooks() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

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
		add_filter('hybrid_hook_theme_prefix', 'bioship_muscle_hybrid_hook_prefix');
		if (!function_exists('bioship_muscle_hybrid_hook_prefix')) {
		 function bioship_muscle_hybrid_hook_prefix() {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		 	// 2.0.5: change to bioship prefix to match new action names
		 	return 'bioship';
		 }
		}
	}
}


// ------------------
// === Foundation ===
// ------------------
// ref: http://foundation.zurb.com/docs
if (!function_exists('bioship_muscle_load_foundation')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_foundation');

 function bioship_muscle_load_foundation() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemevars, $vcsscachebust, $vjscachebust;

	// 2.0.9: filter Foundation loading internally
	$vloadfoundation = false;
	if (isset($vthemesettings['loadfoundation'])) {$vloadfoundation = $vthemesettings['loadfoundation'];}
	$vloadfoundation = bioship_apply_filters('muscle_load_foundation', $vloadfoundation);
	if ( (!$vloadfoundation) || ($vloadfoundation == 'off') ) {return;}

	// 1.8.0: check Foundation 5 or 6 directory to use for loading
	if (isset($vthemesettings['foundationversion'])) {$vfoundation = 'includes/'.$vthemesettings['foundationversion'];}
	else {$vfoundation = 'includes/foundation5';} // backwards compatibility default

	// force auto-load of modernizr and fastclick for Foundation 5
	if (strstr($vfoundation,'5')) {
		if (!has_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr')) {add_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr');}
		if (!has_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick')) {add_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick');}
		$vdeps = array('jquery','fastclick','modernizr');
	} else {$vdeps = array('jquery');}

	// Foundation Stylesheet
	// ---------------------
	// http://foundation.zurb.com/docs/css.html
	if ($vthemesettings['foundationcss']) {
		if ($vthemesettings['loadfoundation'] == 'essentials') {
			$vfoundationstylesheet = bioship_file_hierarchy('both', 'foundation.essentials.min.css', array($vfoundation.'/css','css'));
		} else {
			$vfoundationstylesheet = bioship_file_hierarchy('both', 'foundation.min.css', array($vfoundation.'/css','css'));
		}
		if (is_array($vfoundationstylesheet)) {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$vcsscachebust = date('ymdHi', filemtime($vfoundationstylesheet['file']));
			}
			wp_register_style('foundation', $vfoundationstylesheet['url'], array(), $vcsscachebust);
			wp_enqueue_style('foundation');
		}
	}

	// Full or Partial Foundation Javascript
	// -------------------------------------
	// http://foundation.zurb.com/docs/javascript.html
	if ($vthemesettings['loadfoundation'] == 'full') {
		$vfoundation = bioship_file_hierarchy('both', 'foundation.min.js', array($vfoundation.'/js','javascripts'));
	}
	if ($vthemesettings['loadfoundation'] == 'essentials') {
		$vfoundation = bioship_file_hierarchy('both', 'foundation.essentials.js', array($vfoundation.'/js','javascripts'));
	}
	elseif ($vthemesettings['loadfoundation'] == 'selective') {
		$vfoundation = bioship_file_hierarchy('both', 'foundation.selected.js', array('javascripts', $vfoundation.'/js'));
		// 1.8.0: note, selective javascript is currently only working for Foundation 5, so just in case, fallback to min.js
		if (!is_array($vfoundation)) {$vfoundation = bioship_file_hierarchy('both', 'foundation.min.js', array('javascripts',$vfoundation.'/js'));}
	}
	if (is_array($vfoundation)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
			$vjscachebust = date('ymdHi', filemtime($vfoundation['file']));
		}
		wp_enqueue_script('foundation', $vfoundation['url'], $vdeps, $vjscachebust, true);
		// 2.0.9: use script load variable instead of input
		$vthemevars[] = "var loadfoundation = 'yes'; ";}
	}
 }
}

// ----------------------
// === Theme My Login ===
// ----------------------

// 2.0.1: filter Theme My Login template loading
// 2.0.9: check loading filter internally
if (!function_exists('muscle_load_theme_my_login_filters')) {

 add_action('plugins_loaded', 'muscle_load_theme_my_login_filters');

 function muscle_load_theme_my_login_filters() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	global $vthemesettings;	$vtmltemplates = false;
	if (isset($vthemesettings['tmltemplates'])) {$vtmltemplates = $vthemesettings['tmltemplates'];}
	$vtmltemplates = bioship_apply_filters('muscle_load_tml_templates',$vtmltemplates);
	if ( (!$vtmltemplates) || ($vtmltemplates != '1') ) {return;}

	// 2.0.9: add Theme My Login filters here
	add_filter('tml_template_paths', 'bioship_muscle_tml_template_paths');
	add_filter('login_button_url', 'bioship_muscle_login_button_url');
	add_filter('register_button_url', 'bioship_muscle_register_button_url');
 	add_filter('profile_button_url', 'bioship_muscle_profile_button_url');
	add_filter('register_form_image', 'bioship_muscle_register_form_image');
	add_filter('login_form_image', 'bioship_muscle_login_form_image');
 }
}



// Improve TML Template Hierarchy
// ------------------------------
if (!function_exists('muscle_tml_template_paths')) {
 function bioship_muscle_tml_template_paths($vpaths) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.5: use existing globals
	global $vthemestyledir, $vthemetemplatedir;
	$vtemplatepaths = array(
		$vthemestyledir.'templates/theme-my-login',
		$vthemestyledir.'theme-my-login',
		$vthemestyledir,
		$vthemetemplatedir.'templates/theme-my-login',
		$vthemetemplatedir.'theme-my-login',
		$vthemetemplatedir,
		WP_PLUGIN_DIR.'/theme-my-login/templates'
	);
	// $vtemplatepaths = array_merge($vtemplatepaths, $vpaths);
	bioship_debug("New ThemeMyLogin Paths", $vnewpaths);
	return $vtemplatepaths;
 }
}

// Login Button URL Filter
// -----------------------
if (!function_exists('bioship_muscle_login_button_url')) {
 function bioship_muscle_login_button_url($vbuttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['loginbuttonurl'] != '') {$vbuttonurl = $vthemesettings['loginbuttonurl'];}
	return $vbuttonurl;
 }
}

// Register Button URL Filter
// --------------------------
if (!function_exists('bioship_muscle_register_button_url')) {
 function bioship_muscle_register_button_url($vbuttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['registerbuttonurl'] != '') {$vbuttonurl = $vthemesettings['registerbuttonurl'];}
	return $vbuttonurl;
 }
}

// Profile Button URL Filter
// -------------------------
if (!function_exists('bioship_muscle_profile_button_url')) {
 function bioship_muscle_profile_button_url($vbuttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['profilebuttonurl'] != '') {$vbuttonurl = $vthemesettings['profilebuttonurl'];}
	return $vbuttonurl;
 }
}

// Register Form Logo Image
// ------------------------
if (!function_exists('bioship_muscle_register_form_image')) {
 function bioship_muscle_register_form_image($vimage) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vimage = '';
	if ($vthemesettings['registerformimage'] == '1') {
		if ($vthemesettings['loginlogo'] == 'custom') {$vimage = $vthemesettings['header_logo'];}
		if ($vthemesettings['loginlogo'] == 'upload') {$vimage = $vthemesettings['loginlogourl'];}
	}
	return $vimage;
 }
}

// Login Form Logo Image
// ---------------------
if (!function_exists('bioship_muscle_login_form_image')) {
 function bioship_muscle_login_form_image() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings; $vimage = '';
	if ($vthemesettings['loginformimage'] == '1') {
		if ($vthemesettings['loginlogo'] == 'custom') {$vimage = $vthemesettings['header_logo'];}
		if ($vthemesettings['loginlogo'] == 'upload') {$vimage = $vthemesettings['loginlogourl'];}
	}
	return $vimage;
 }
}


// ----------------------
// Theme Switch Admin Fix
// ----------------------
// TODO: retest Theme Switching functionality

// for Theme Test Drive and JonRadio Multiple Themes...
// ref: http://wordpress.stackexchange.com/q/227532/76440

// *** IMPORTANT USAGE NOTE *** only works *HERE* for BioShip Parent and Child Theme switching
// if you want the same theme switching functionality to work with other themes as well,
// you will need to simply put a copy of this function in /wp-content/mu-plugins/ folder.
// and that is because THIS file is loaded BY this theme, so therefore the fix will not be
// loaded for other themes - unless it is loaded at an earlier time, ie. mu-plugins or plugins

// note: currently for JonRadio Multiple Themes, select-theme.php is NOT loaded for admin
// (this means the advanced setting 'AJAX All' currently has no effect anyway...)
// loading select-theme.php will automatically set the active theme via cookie storage,
// BUT this will not work for using admin-ajax.php or admin-post.php when visiting multiple
// pages on the same site at once where a different theme may be active for different pages!

// note: if loading via mu-plugins or a plugin, this action hook must change to 'plugins_loaded'
// 2.0.5: disable all this by default until retesting
add_action('init', 'bioship_muscle_theme_switch_admin_fix');
if (!function_exists('bioship_muscle_theme_switch_admin_fix')) {
 function bioship_muscle_theme_switch_admin_fix() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

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
						// 2.0.1: allow for fallback for older installs
						$current_user = bioship_get_current_user();
						$usermetadata = get_user_meta($current_user->ID, $datakey, true);
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
				add_filter('pre_option_stylesheet','bioship_muscle_admin_ajax_stylesheet');
				add_filter('pre_option_template','bioship_muscle_admin_ajax_template');

				function bioship_muscle_admin_ajax_stylesheet() {global $ajax_stylesheet; return $ajax_stylesheet;}
				function bioship_muscle_admin_ajax_template() {global $ajax_template; return $ajax_template;}
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
									set_transient($transientkey, $transientdata, $expires);
									$existingmatch = true;
								}
							} else {unset($cookiedata[$i]);} // remove expired
							$i++;
						}
					}
				}
				if ( ($datamethod != 'cookie') && (is_user_logged_in()) ) {
					// check existing usermeta data
					// 2.0.1: allow for fallback for older installs
					// 2.0.7: use new prefixed current user function
					$current_user = bioship_get_current_user();
					$usermetadata = get_user_meta($current_user->ID, $datakey, true);
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
									set_transient($transientkey, $transientdata, $expires);
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
						 $cookiedata[] = $transientkey; $cookiedatastring = implode(',', $cookiedata);
						 setCookie($themecookie, $cookiedatastring, time()+$expires);
					 }
					 // add transient to usermeta for matching later
					 if ($datamethod != 'cookie') {
					 	$usermetadata[] = $transientkey; update_user_meta($current_user->ID, $datakey, $usermetadata);
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
