<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === MUSCLE  FUNCTIONS ===
 * ...Extending WordPress...
 *
**/

if (!function_exists('add_action')) {exit;}

// ============================
// === muscle.php Structure ===
// ============================
//
// === Metabox Overrides ===
// --- Get PerPost Display Overrides
// --- Get PerPost Layout Overrides
// --- Output PerPost Override Styles
// --- PerPost Thumbnail Size Filter
// --- Get Content Filter Overrides
// --- Maybe Remove Content Filters
//
// === Muscle Settings ===
// --- MISC
// ---- Default Gravatar
// ---- Classic Text Widget
// ---- Discreet Text Widget
// ---- Video Background
// --- Scripts
// --- Extras
// --- Thumbnails
// --- Reading
// --- Excerpts
// --- Read More
// --- Writing
// --- RSS
// --- Admin
//
// === Integrations ===
// --- WooCommerce
// --- Open Graph Protocol Framework
// --- Hybrid Hook
// --- Foundation
// --- Theme My Login
// --- Theme Switching

// --- Integration Notes ---
// - AJAX Load More (see /templates/ajax-load-more/)
// - Theme Test Drive (throughout theme)
// - WP Subtitle (see skeleton.php)
// - WP PageNavi (see skeleton.php)


// -------------------------
// === MetaBox Overrides ===
// -------------------------
// (to apply various PerPost Theme Options)
// 1.8.0: metabox interface moved to admin.php

// -----------------------------
// Get PerPost Display Overrides
// -----------------------------
// 1.8.0: rename from muscle_get_overrides and now for displays only
// 1.8.0: removed options tab as only needed for admin display
// 1.8.0: moved content filters to a separate function
// 1.8.0: removed perpoststyles from overrides, now retrieved separately
if (!function_exists('bioship_muscle_get_display_overrides')) {
 function bioship_muscle_get_display_overrides($resource) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.1.1: set empty array instead of global
	$display = array();

	// TODO: add meta archive overrides via custom post type ?
	// if (!is_singular()) {}

	// --- check resource ---
	if (is_numeric($resource)) {

		// --- get display overrides ---
		// 2.0.8: use prefixed post meta key
		$display = get_post_meta($resource, '_'.THEMEPREFIX.'_display_overrides', true);

		// --- maybe convert old metakey values ---
		// 2.0.8: to convert to prefixed metakey
		if (!$display) {
			$oldpostmeta = get_post_meta($resource, '_displayoverrides', true);
			if ($oldpostmeta && is_array($oldpostmeta)) {
				$display = $oldpostmeta;
				delete_post_meta($resource, '_displayoverrides');
				update_post_meta($resource, '_'.THEMEPREFIX.'_display_overrides', $display);
			}
		}
	}

	// --- maybe convert old dispay override values ---
	// 1.8.0: convert old display overrides to single meta array
	// 2.1.1: [deprecated] conversion of (very) old single metakey values

	// --- fix for empty values to avoid undefined index warnings ---
	// 1.8.5: added wrapper, headernav, footernav, breadcrumb, pagenavi
	$displaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);
	foreach ($displaykeys as $displaykey) {
		if (!isset($display[$displaykey])) {$display[$displaykey] = '';}
	}

	// --- filter and return display overrides ---
	// 2.0.9: changed this filter name from muscle_perpage_overrides
	// 2.1.1: added post ID as second filter argument
	$display = bioship_apply_filters('muscle_display_overrides', $display, $resource);
	bioship_debug("Display Overrides", $display);
	return $display;
 }
}

// --------------------------------
// Get PerPost Templating Overrides
// --------------------------------
// 1.9.5: separated for templating overrides
if (!function_exists('bioship_muscle_get_templating_overrides')) {
 function bioship_muscle_get_templating_overrides($resource) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.1.1: set empty array instead of using global
	$overrides = array();

	// TODO: get meta archive overrides via custom post type?
	// if (!is_singular()) {}

	// --- get post ID if not supplied ---
	if (!$resource || !is_numeric($resource)) {
		global $post; if (is_object($post)) {$resource = $post->ID;} else {return $overrides;}
	}

	// --- get templating overrides ---
	// 2.0.8: use prefixed post meta key
	$overrides = get_post_meta($resource, '_'.THEMEPREFIX.'_templating_overrides', true);

	// --- maybe convert old metakey values
	// 2.0.8: to convert to prefixed metakey
	if (!$overrides) {
		$oldpostmeta = get_post_meta($resource, '_templatingoverrides', true);
		if ($oldpostmeta && is_array($oldpostmeta)) {
			$overrides = $oldpostmeta;
			delete_post_meta($resource, '_templatingoverrides');
			update_post_meta($resource, '_'.THEMEPREFIX.'_templating_overrides', $overrides);
		}
	}

	// --- set override keys ---
	$overridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);

	// --- fix for empty values to avoid undefined index warnings ---
	foreach ($overridekeys as $overridekey) {
		if (!isset($overrides[$overridekey])) {$overrides[$overridekey] = '';}
	}

	// --- check for thumbnail size force off option ---
	// 1.9.8: fix to undefined vpostid variable
	// 2.0.8: check prefixed post meta value for thumbnail size
	$thumbnailsize = get_post_meta($resource, '_'.THEMEPREFIX.'_thumbnailsize', true);

	// --- maybe convert unprefixed meta key ---
	// 2.0.8: maybe convert old thumbnail size meta key
	// 2.1.1: moved here from thumbnail size filter
	if (!$thumbnailsize) {
		$oldpostmeta = get_post_meta($resource, '_thumbnailsize', true);
		if ($oldpostmeta) {
			$thumbnailsize = $oldpostmeta;
			delete_post_meta($resource, '_thumbnailsize');
			update_post_meta($resource, '_'.THEMEPREFIX.'_thumbnailsize', $thumbsize);
		}
	}

	// --- maybe set thumbnail override ---
	if ($thumbnailsize && ($thumbnailsize == 'off')) {$overrides['image'] = 'off';}

	// --- filter and return templating overrides ---
	// 2.1.1: added post ID as second filter argument
	$overrides = bioship_apply_filters('muscle_templating_overrides', $overrides, $resource);
	bioship_debug("Templating Overrides", $overrides);
	return $overrides;
 }
}

// ------------------------------
// Output PerPost Override Styles
// ------------------------------
if (!function_exists('bioship_muscle_perpage_override_styles')) {

 if ($vthemesettings['themecssmode'] == 'footer') {
 	add_action('wp_footer', 'bioship_muscle_perpage_override_styles');
 } else {add_action('wp_head', 'bioship_muscle_perpage_override_styles');}

 function bioship_muscle_perpage_override_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- for frontend styles only ---
	// 2.0.9: check admin context internally
	if (is_admin()) {return;}

	// --- get theme display overrides ---
	// 1.8.5: set global value via muscle_get_display_overrides
	// 1.9.5: removed post check as global already set
	// 1.9.5: just set short name for vthemedisplay global
	global $vthemedisplay; $hide = $vthemedisplay;

	// PerPost Styles
	// --------------
	// 1.9.5: moved singular post check to here
	// 2.1.1: moved above override styles (for resource setting)
	$resource = $customstyles = '';
	if (is_singular()) {

		// --- get perpost style overrides ---
		global $post; $resource = $post->ID;
		// 2.0.8: use prefixed post meta key
		$customstyles = get_post_meta($resource, '_'.THEMEPREFIX.'_perpoststyles', true);

		// --- maybe convert old post meta key ---
		// 2.0.8: to add prefixed metakey
		if (!$customstyles) {
			$oldpostmeta = get_post_meta($resource, '_perpoststyles', true);
			if ($oldpostmeta) {
				$customstyles = $oldpostmeta;
				delete_post_meta($resource, '_perpoststyles');
				update_post_meta($resource, '_'.THEMEPREFIX.'_perpoststyles', $customstyles);
			}
		}

		// --- filter perpost styles ---
		// 2.1.1: added missing perpost style filter
		$customstyles = bioship_apply_filters('muscle_custom_styles', $customstyles, $resource);
		bioship_debug("Custom Styles", $customstyles);

	} elseif (is_archive()) {

		// TODO: add meta archive style overrides via custom post type?
		// 2.1.1: added filter for archive custom styles
		// $customstyles = get_post_meta('?????', '_'.THEMEPREFIX'._contextstyles', true);
		$customstyles = ''; //  TEMP
		$resource = $context = 'archive'; // TEMP
		$customstyles = bioship_apply_filters('muscle_custom_styles', $customstyles, $resource);
	}

	// --- add styles for display overrides ----
	// 2.1.1: check for overrides to prevent undefined index warnings
	$styles = '';
	if ($hide) {

		// Full Width Container Override
		// -----------------------------
		// 1.8.5: added full width container option (no wrap margins)
		// 1.9.8: fix to override key from fullwidth to wrapper
		if ($hide['wrapper'] == '1') {$styles .= '#wrap.container {width: 100% !important;}'.PHP_EOL;}

		// Main Theme Areas
		// ----------------
		if ($hide['header'] == '1') {$styles .= "#header {display:none !important;}".PHP_EOL;}
		if ($hide['footer'] == '1') {$styles .= "#footer {display:none !important;}".PHP_EOL;}

		// Navigation
		// ----------
		if ($hide['navigation'] == '1') {$styles .= "#navigation {display:none !important;}".PHP_EOL;}
		if ($hide['secondarynav'] == '1') {$styles .= "#secondarymenu {display:none !important;}".PHP_EOL;}
		if ($hide['headernav'] == '1') {$styles .= "#header #headermenu {display:none !important;}".PHP_EOL;}
		if ($hide['footernav'] == '1') {$styles .= "#footer #footermenu {display:none !important;}".PHP_EOL;}

		// Sidebars
		// --------
		// 1.8.0: Sidebar hides removed here as handled by templating
		// 1.9.5: Changed back this is really for actually hiding not for templating
		// 1.9.5: apply individual sidebar hide conditional filters here
		$hidesidebar = bioship_apply_filters('skeleton_sidebar_hide', false);
		$hidesubsidebar = bioship_apply_filters('skeleton_subsidebar_hide', false);
		if ($hidesidebar || ($hide['sidebar'] == '1') ) {$styles .= "#sidebar {display:none !important;}".PHP_EOL;}
		if ($hidesubsidebar || ($hide['subsidebar'] == '1') ) {$styles .= "#subsidebar {display:none !important;}".PHP_EOL;}

		// Widget Areas
		// ------------
		// 1.9.5: re-added individual footer display overrides (for completeness)
		if ($hide['headerwidgets'] == '1') {$styles .= "#header-widget-area {display:none !important;}".PHP_EOL;}
		if ($hide['footerwidgets'] == '1') {$styles .= "#sidebar-footer {display:none !important;}".PHP_EOL;}
		if ($hide['footer1'] == '1') {$styles .= "#footer-widget-area-1 {display:none !important;}".PHP_EOL;}
		if ($hide['footer2'] == '1') {$styles .= "#footer-widget-area-2 {display:none !important;}".PHP_EOL;}
		if ($hide['footer3'] == '1') {$styles .= "#footer-widget-area-3 {display:none !important;}".PHP_EOL;}
		if ($hide['footer4'] == '1') {$styles .= "#footer-widget-area-4 {display:none !important;}".PHP_EOL;}

		// Content Areas
		// -------------
		// 1.9.5: separated display and templating override for thumbnail
		// 2.0.0: fix to breadcrumb trail targeting (was #breadcrumb)
		if ($hide['image']) {$styles .= "div.thumbnail img {display:none !important;}";}
		if ($hide['breadcrumb'] == '1') {$styles .= "#content .breadcrumb-trail {display:none !important;}".PHP_EOL;}
		if ($hide['title'] == '1') {$styles .= "#content .entry-title {display:none !important;}".PHP_EOL;}
		if ($hide['subtitle'] == '1') {$styles .= "#content .entry-subtitle {display:none !important;}".PHP_EOL;}
		if ($hide['metatop'] == '1') {$styles .= "#content .entry-meta {display:none !important;}".PHP_EOL;}
		if ($hide['metabottom'] == '1') {$styles .= "#content .entry-utility {display:none !important;}".PHP_EOL;}
		if ($hide['authorbio'] == '1') {$styles .= "#content .entry-author {display:none !important;}".PHP_EOL;}
		if ($hide['pagenavi'] == '1') {$styles .= "#content #nav-below {display:none !important;}".PHP_EOL;}

	}

	// --- filter override styles ---
	// 2.1.1: added missing style override filter
	$styles = bioship_apply_filters('muscle_override_styles', $styles, $resource);

	// --- combine perpost overrides with custom styles ---
	if ($customstyles && ($customstyles != '')) {$styles .= $customstyles.PHP_EOL;}

	// --- output styles ---
	if ($styles != '') {
		echo '<style>'.$styles.'</style>'; // phpcs:ignore WordPress.Security.OutputNotEscaped
	}
 }
}

// -----------------------------
// PerPost Thumbnail Size Filter
// -----------------------------
// 2.0.1: moved add_filter internally
if (!function_exists('bioship_muscle_thumbnail_size_perpost')) {

 // 2.1.1: added second argument for postid
 add_filter('skeleton_post_thumbnail_size', 'bioship_muscle_thumbnail_size_perpost', 10, 2);

 function bioship_muscle_thumbnail_size_perpost($size, $postid) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- get post ID if not supplied ---
	if (!$postid) {global $post; if (is_object($post)) {$postid = $post->ID;} else {return $size;} }

	// --- get thumbnail size override ---
	// 2.0.8: use prefixed post meta key
	$thumbsize = get_post_meta($postid, '_'.THEMEPREFIX.'_thumbnailsize', true);

	// TODO: maybe double check thumbnail size is still available before using it?
	// $thumbsizes = array_merge(array('small', 'medium', 'large'), get_intermediate_image_sizes());
	if ($thumbsize && ($thumbsize != '')) {return $thumbsize;}
	return $size;
 }
}

// ----------------------------
// Get Content Filter Overrides
// ----------------------------
// 1.8.0: separated to just get filter overrides
if (!function_exists('bioship_muscle_get_content_filter_overrides')) {
 function bioship_muscle_get_content_filter_overrides($postid) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- get content filter overrides ---
	// 1.9.5: fix to remove filters metakey (previously _disablefilters)
	// 2.0.8: use prefixed post meta key
	$removefilters = get_post_meta($postid, '_'.THEMEPREFIX.'_removefilters', true);

	// 2.0.8: maybe convert old post meta key
	if (!$removefilters) {
		$oldpostmeta = get_post_meta($postid, '_removefilters', true);
		if ($oldpostmeta) {
			$removefilters = $oldpostmeta;
			delete_post_meta($postid, '_removefilters');
			update_post_meta($postid, '_'.THEMEPREFIX.'_removefilters', $removefilters);
		}
	}

	// --- filter and return ---
	// 1.8.0: added this conditional filter
	// 2.1.1: added postid argument to filter
	$removefilters = bioship_apply_filters('muscle_content_filter_overrides', $removefilters, $postid);
	return $removefilters;
 }
}

// ----------------------------
// maybe Remove Content Filters
// ----------------------------
if (!function_exists('bioship_muscle_remove_content_filters')) {

 // note: runs early to maybe remove the filters
 add_filter('the_content', 'bioship_muscle_remove_content_filters', 9);

 function bioship_muscle_remove_content_filters($content) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- get content filters to remove ---
	global $post;
	if (!isset($post) || !is_object($post)) {return $content;}
	$postid = $post->ID;
	$remove = bioship_muscle_get_content_filter_overrides($postid);

	// --- loop to remove content filters ---
	// 2.0.5: loop through possible filter array
	// TODO: check and match possible change in filter priority ?
	$filters = array('wpautop', 'wptexturize', 'convert_smilies', 'convert_chars');
	foreach ($filters as $filter) {
		if (isset($remove[$filter]) && ($remove[$filter] == '1')) {
			remove_filter('the_content', $filter);
		}
	}

	return $content;
 }
}


// ------------
// === MISC ===
// ------------

// -----------------------------
// maybe Change default Gravatar
// -----------------------------
// eg. /wp-content/child-theme/images/avatar.png
if (!function_exists('bioship_muscle_default_gravatar')) {
 add_filter('avatar_defaults', 'bioship_muscle_default_gravatar');
 function bioship_muscle_default_gravatar($defaults) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs;
	if ($vthemesettings['gravatarurl'] != '') {
		$avatar = $vthemesettings['gravatarurl'];
		$defaults[$avatar] = 'avatar';
	} else {
		$avatar = bioship_file_hierarchy('url', 'gravatar.png', $vthemedirs['image']);
		if ($avatar) {$defaults[$avatar] = 'avatar';}
	}
	// TODO: cache default avatar image size and pass to skeleton_comments_avatar_size filter?
	return $defaults;
 }
}

// -------------------
// Classic Text Widget
// -------------------
// 2.0.8: copied from WP 4.7.5 for Discreet Text Widget basis
// (since WP 4.8 changes WP_Widget_Text class and breaks DiscreetTextWidget)
// 2.0.9: no classic text widget for WordPress.org version :-(
// (widgets classes need to be in plugins not themes in repository)
if (!class_exists('WP_Widget_Classic_Text') && !THEMEWPORG) {
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
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.OutputNotEscaped
		if (!empty($title)) {echo $args['before_title'].$title.$args['after_title'];}
		echo '<div class="textwidget">';
			if (!empty($instance['filter'])) {$text = wpautop($text);}
			echo $text; // // phpcs:ignore WordPress.Security.OutputNotEscapedShortEcho
		echo '</div>';
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.OutputNotEscaped
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
		echo '<p><label for="'.$this->get_field_id('title').'">'.esc_attr(__('Title:','bioship')).'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" /></p>';
		echo '<p><label for="'.$this->get_field_id('text').'">'.esc_attr(__('Content:','bioship')).'</label>';
		echo '<textarea class="widefat" rows="16" cols="20" id="'.$this->get_field_id('text').'" name="'.$this->get_field_name('text').'">'.esc_textarea($instance['text']).'</textarea></p>';
		echo '<p><input id="'.$this->get_field_id('filter').'" name="'.$this->get_field_name('filter').'" type="checkbox"'.checked($filter).' />&nbsp;';
		echo '<label for="'.$this->get_field_id('filter').'">'.esc_attr(__('Automatically add paragraphs','bioship')).'</label></p>';
	}
 }
}

// --------------------
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
	if (!class_exists('DiscreetTextWidget') && !THEMEWPORG) {
	 // 2.0.8: extend classic text widget class
	 class DiscreetTextWidget extends WP_Widget_Classic_Text {
		function __construct() {
			$widgetops = array('classname' => 'discreet_text_widget', 'description' => __('Arbitrary text or HTML, only shown if not empty.','bioship'));
			$controlops = array('width' => 400, 'height' => 350);
			// 1.9.8: fix to deprecated class construction method
			// 2.0.7: fix to incorrect text domain (csidebars)
			call_user_func(array(get_parent_class(get_parent_class($this)), '__construct'), 'discrete_text', __('Discreet Text','bioship'), $widgetops, $controlops);
		}
		function widget($args, $instance) {
			// 1.9.8: removed usage of extract here
			$text = bioship_apply_filters('widget_text', $instance['text']);
			if (empty($text)) {return;}

			echo $args['before_widget']; // phpcs:ignore WordPress.Security.OutputNotEscaped
				$title = bioship_apply_filters('widget_title', $instance['title']);
				if (!empty($title)) {
					echo $args['before_title'].esc_attr($title).$args['after_title']; // phpcs:ignore WordPress.Security.OutputNotEscaped
				}
				echo '<div class="textwidget">';
					if ($instance['filter']) {$text = wpautop($text);}
					echo $text; // phpcs:ignore WordPress.Security.OutputNotEscapedShortEcho
				echo '</div>';
			echo $args['after_widget']; // phpcs:ignore WordPress.Security.OutputNotEscaped
		}
	 }
	 return register_widget("DiscreetTextWidget");
	}
 }
}

// ----------------------------
// Fullscreen Video Background!
// ----------------------------
// What is this doing here? A client-abandoned feature. It works tho! :-)
// Currently works for YouTube videos only (TODO: could add Vimeo etc...)
// TODO: allow for HTML5 video uploaded to media library ?
// Ref: https://wordpress.stackexchange.com/a/325790/76440
// TODO: maybe add video background to Theme Options? currently via filters only
// (see filters.php example): muscle_videobackground_type,
// muscle_videobackground_id, muscle_videobackground_delay

// 1.9.8: fix to function_exists check (missing argument)
// 2.0.1: check themesettings internally to allow better filtering
if (!function_exists('bioship_muscle_video_background')) {

 add_action('bioship_before_navbar', 'bioship_muscle_video_background');

 function bioship_muscle_video_background() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;

	// --- filter video background loading ---
	if (isset($vthemesettings['videobackground'])) {$load = $vthemesettings['videobackground'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_videobackground_type', $load);

	if ($load == 'youtube') {

		// --- get video ID and delay ---
		$videoid = $videodelay = '';
		if (isset($vthemesettings['videobackgroundid'])) {$videoid = $vthemesettings['videobackgroundid'];}
		$videoid = bioship_apply_filters('muscle_videobackground_id', $videoid);
		if (isset($vthemesettings['videobackgrounddelay'])) {$videodelay = $vthemesettings['videobackgrounddelay'];}
		$videodelay = (int)bioship_apply_filters('muscle_videobackground_delay', $videodelay);
		$videodelay = absint($videodelay);
		if (!is_numeric($videodelay) || ($videodelay < 0)) {$videodelay = 1000;}

		// --- output ID and delay values ---
		$maybe = array(); preg_match( "/[a-zA-Z0-9]+//", $videoid, $maybe);
		if ( ($videoid != '') && ($videoid == $maybe[0]) ) {
			echo '<div id="backgroundvideowrapper">';
			echo '<input type="hidden" id="videobackgroundoid" value="'.esc_attr($videoid).'">';
			echo '<input type="hidden" id="videobackgrounddelay" value="'.esc_attr($videodelay).'">';
			echo '<div id="backgroundvideo"></div></div>';
		}
	}
 }
}


// ---------------
// === Scripts ===
// ---------------

// ---------------------------------
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
	$iesupports = $vthemesettings['iesupports'];

	// --- check for file modified cachebusting ---
	// 2.0.9: fix for undefined variable warning
	if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$filemtime = true;} else {$filemtime = false;}

	// Selectivizr CSS3
	// ----------------
	if (isset($iesupports['selectivizr']) &&  ($iesupports['selectivizr'] == '1')) {$load = true;} else {$load = false;}
	// 2.0.1: added loading filter
	$load = bioship_apply_filters('muscle_load_selectivizr', true);
	if ($load) {
		$selectivizr = bioship_file_hierarchy('both', 'selectivizr.min.js', $vthemedirs['script']);
		if (is_array($selectivizr)) {
			// 2.1.1: fix to cachebusting conditions
			if ($filemtime) {$cachebust = date('ymdHi', filemtime($selectivizr['file']));} else {$cachebust = $vjscachebust;}
			echo '<!--[if (gte IE 6)&(lte IE 8)]><script src="'.esc_url($selectivizr['url']).'?ver='.esc_attr($cachebust).'"></script><![endif]-->';
		}
	}

	// HTML5 Shiv
	// ----------
	if (isset($iesupports['html5shiv']) && ($iesupports['html5shiv'] == '1')) {$load = true;} else {$load = false;}
	// 2.0.1: added loading filter
	$load = bioship_apply_filters('muscle_load_html5shiv', true);
	if ($load) {
		$html5 = bioship_file_hierarchy('both', 'html5.js', $vthemedirs['script']);
		if (is_array($html5)) {
			// 2.1.1: fix to cachebusting conditions
			if ($filemtime) {$cachebust = date('ymdHi', filemtime($html5['file']));} else {$cachebust = $vjscachebust;}
			echo '<!--[if lt IE 9]><script src="'.esc_url($html5['url']).'?ver='.esc_attr($cachebust).'"></script><![endif]-->';
		}
	}

	// Supersleight
	// ------------
	if (isset($iesupports['supersleight']) && ($iesupports['supersleight'] == '1')) {$load = true;} else {$load = false;}
	// 2.0.1: added loading filter
	$load = bioship_apply_filters('muscle_load_supersleight', true);
	if ($load) {
		$supersleight = bioship_file_hierarchy('both', 'supersleight.js', $vthemedirs['script']);
		if (is_array($supersleight)) {
			// 2.1.1: fix to cachebusting conditions
			if ($filemtime) {$cachebust = date('ymdHi', filemtime($supersleight['file']));} else {$cachebust = $vjscachebust;}
			echo '<!--[if lte IE 6]><script src="'.esc_url($supersleight['url']).'?ver='.esc_attr($cachebust).'"></script><![endif]-->';
		}
	}

	// IE8 DOM
	// -------
	// 1.8.5: added IE8 DOM polyfill
	if (isset($iesupports['ie8']) && ($iesupports['ie8'] == '1')) {$load = true;} else {$load = false;}
	// 2.0.1: added loading filter
	$load = bioship_apply_filters('muscle_load_ie8dom', true);
	if ($load) {
		$ie8 = bioship_file_hierarchy('both', 'ie8.js', $vthemedirs['script']);
		if (is_array($ie8)) {
			// 2.1.1: fix to cachebusting conditions
			if ($filemtime) {$cachebust = date('ymdHi', filemtime($ie8['file']));} else {$cachebust = $vjscachebust;}
			echo '<!--[if IE 8]><script src="'.esc_url($ie8['url']).'?ver='.esc_attr($cachebust).'"></script><![endif]-->';
		}
	}

	// Flexibility
	// -----------
	// 1.8.0: added flexbox polyfill
	if (isset($iesupports['flexibility']) && ($iesupports['flexibility'] == '1')) {$load = true;} else {$load = false;}
	// 2.0.1: added loading filter
	$load = bioship_apply_filters('muscle_load_flexibility', true);
	if ($load) {
		$flexibility = bioship_file_hierarchy('both', 'flexibility.js', $vthemedirs['script']);
		if (is_array($flexibility)) {
			if ($filemtime) {$cachebust = date('ymdHi', filemtime($flexibility['file']));} else {$cachebust = $vjscachebust;}
			echo '<!--[if (IE 8)|(IE 9)]><script src="'.esc_url($flexibility['url']).'?ver='.esc_attr($cachebust).'"></script><![endif]-->';
		}
	}

 }
}

// ----------
// PrefixFree
// ----------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_prefixfree')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_prefixfree');

 function bioship_muscle_load_prefixfree() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vjscachebust, $vthemedirs;

	// ---check load ---
	if (isset($vthemesettings['prefixfree'])) {$load = $vthemesettings['prefixfree'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_prefixfree', $load);
	if (!$load) {return;}

	// --- load PrefixFree ---
	$prefixfree = bioship_file_hierarchy('both', 'prefixfree.js', $vthemedirs['script']);
	if (is_array($prefixfree)) {

		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($prefixfree['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('prefixfree', $prefixfree['url'], array(), $cachebust, true);

		// --- prefixfree attribute to script tags ---
		// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (a "WTF" bug!)
		// ref: http://stackoverflow.com/questions/25694456/google-fonts-giving-no-access-control-allow-origin-header-is-present-on-the-r
		// ref: http://wordpress.stackexchange.com/questions/176077/add-attribute-to-link-tag-thats-generated-through-wp-register-style
		// ref: https://github.com/LeaVerou/prefixfree/pull/39
		if (!function_exists('muscle_fonte_no_prefix_attribute')) {

		 add_filter('style_loader_tag', 'bioship_muscle_fonts_noprefix_attribute', 10, 2);

		 function bioship_muscle_fonts_noprefix_attribute($tag, $handle) {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			$original = $tag;

			// note: Google fonts style handles are 'heading-font-'x or 'custom-font-'x
			// 2.0.9: stricter checking for handle at start of string
			if ( (strpos($handle, 'heading-font-') === 0)  || (strpos($handle, 'custom-font-') === 0) ) {
				$tag = str_replace('/>', 'data-noprefix />', $tag);
			} else {
				// ...and a basic check for if the link is external to the site
				// as this problem could occur for other external sheets like this
				if (!stristr($tag, $_SERVER['HTTP_HOST'])) {
					// 2.0.9: use stricter checking for http at start of string
					// 2.1.2: fix this check at this is a full tag not just an URL
					$pos = strpos($tag, 'href=');
					if ( (substr($tag, $pos + 6, strlen('http://'))  == 'http://')
						|| (substr($tag, $pos + 6, strlen('https://')) == 'https://')
						|| (substr($tag, $pos + 6, strlen('//')) == '//') ) {
						$tag = str_replace('/>', 'data-noprefix />', $tag);
					}
				}
			}
			if ($original != $tag) {bioship_debug("No PrefixFree for Style", $handle);}
			return $tag;
		 }
		}
	}
 }
}

// -----------------------------
// NWWatcher Selector Javascript
// -----------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_nwwatcher')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_nwwatcher');

 function bioship_muscle_load_nwwatcher() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['nwwatcher'])) {$load = $vthemesettings['nwwatcher'];} else {$load = true;}
	$load = bioship_apply_filters('muscle_load_nwwatcher', $load);
	if (!$load) {return;}

	// --- load NW Watcher ---
	$nwwatcher = bioship_file_hierarchy('both', 'nwwatcher.js', $vthemedirs['script']);
	if (is_array($nwwatcher)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($nwwatcher['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('nwwatcher', $nwwatcher['url'], array(), $cachebust, true);
	}
 }
}

// --------------------------------------------
// NWEvents Event Manager (NWWatcher dependent)
// --------------------------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('muscle_load_nwevents')) {

 add_action('wp_enqueue_scripts','bioship_muscle_load_nwevents');

 function bioship_muscle_load_nwevents() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['nwevents'])) {$load = $vthemesettings['nwevents'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_nwevents', $load);
	if (!$load) {return;}

	// --- load NW Events ---
	$nwevents = bioship_file_hierarchy('both', 'nwevents.js', $vthemedirs['script']);
	if (is_array($nwevents)) {
		// 2.1.1: fix to cachebusting conditions and mismatched filename (prefixfree)
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($nwevents['file']));}
		else {$cachebust = $vjscachebust;}
		// 2.1.1: fix to missing URL key
		wp_enqueue_script('nwevents', $nwevents['url'], array('nwwatcher'), $cachebust, true);
	}
 }
}

// ---------------------
// Media Queries Support
// ---------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_media_queries_script')) {

 // note enqueue exception: apparently for these the "best place is in the footer"
 add_action('wp_footer', 'bioship_muscle_media_queries_script');

 function bioship_muscle_media_queries_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['mediaqueries'])) {$load = $vthemesettings['mediaqueries'];} else {$load = 'off';}
	$load = bioship_apply_filters('muscle_load_mediaqueries', $load);
	// 2.0.2: fix to simplified load variable typo
	if (!$load || ($load == 'off')) {return;}

	// --- load Respond or Media Queries --
	if ($vthemesettings['mediaqueries'] == 'respond') {
		$respond = bioship_file_hierarchy('both', 'respond.min.js', $vthemedirs['script']);
		if (is_array($respond)) {
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($respond['file']));}
			else {$cachebust = $vjscachebust;}
			echo '<script src="'.esc_url($respond['url']).'?ver='.esc_attr($cachebust).'"></script>';
		}
	} elseif ($vthemesettings['mediaqueries'] == 'mediaqueries') {
		$mediaqueries = bioship_file_hierarchy('both', 'css3-mediaqueries.js', $vthemedirs['script']);
		if (is_array($mediaqueries)) {
			// 2.1.1: fix to cachebusting conditions
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($mediaqueries['file']));}
			else {$cachebust = $vjscachebust;}
			echo '<script src="'.esc_url($mediaqueries['url']).'?ver='.esc_attr($cachebust).'"></script>';
		}
	}
 }
}

// --------------
// Load FastClick
// --------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_fastclick')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick');

 function bioship_muscle_load_fastclick() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadfastclick'])) {$load = $vthemesettings['loadfastclick'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_fastclick', $load);
	if (!$load) {return;}

	// --- load FastClick ---
	// 1.8.5: adding missing filemtime cachebusting option
	$fastclick = bioship_file_hierarchy('both', 'fastclick.js', $vthemedirs['script']);
	if (is_array($fastclick)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($fastclick['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('fastclick', $fastclick['url'], array('jquery'), $cachebust, true);
	}
 }
}

// ---------------
// Load Mousewheel
// ---------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_mousewheel')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_mousewheel');

 function bioship_muscle_load_mousewheel() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadmousewheel'])) {$load = $vthemesettings['loadmousewheel'];} else {$load = false;}
	// 2.0.1: fix to reused code typo in filter variable
	$load = bioship_apply_filters('muscle_load_mousewheel', $load);
	if (!$load) {return;}

	// --- load MouseWheel ---
	// 1.9.0: fix to file hierarchy call (both not url)
	$mousewheel = bioship_file_hierarchy('both', 'mousewheel.js', $vthemedirs['script']);
	if (is_array($mousewheel)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($mousewheel['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('mousewheel', $mousewheel['url'], array('jquery'), $cachebust, true);
	}
 }
}

// -----------------
// Load CSS.Supports
// -----------------
// 2.0.1: check themeoptions internally to allow filtering
if (!function_exists('bioship_muscle_load_csssupports')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_csssupports');

 function bioship_muscle_load_csssupports() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadcsssupports'])) {$load = $vthemesettings['loadcsssupports'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_csssupports', $load);
	if (!$load) {return;}

	// --- load CSS Supports ---
	$csssupports = bioship_file_hierarchy('url', 'CSS.supports.js', $vthemedirs['script']);
	if (is_array($csssupports)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($csssupports['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('csssupports', $csssupports, array(), $cachebust, true);
	}
 }
}

// -------------
// MatchMedia.js
// -------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_match_media_script')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_match_media_script');

 function bioship_muscle_match_media_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 2.0.1: fix to old themeoptions global typo
	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadmatchmedia'])) {$load = $vthemesettings['loadmatchmedia'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_matchmedia', $load);
	if (!$load) {return;}

	// --- load MatchMedia ---
	// 1.9.5: fixed to file hierarchy call
	$matchmedia = bioship_file_hierarchy('both', 'matchMedia.js', $vthemedirs['script']);
	if (is_array($matchmedia)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($matchmedia['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('matchmedia', $matchmedia['url'], array('jquery'), $cachebust, true);

		// 1.9.5: fixed to file hierarchy call
		$matchmedialistener = bioship_file_hierarchy('both', 'matchMedia.addListener.js', $vthemedirs['script']);
		if (is_array($matchmedialistener)) {
			// 2.1.1: fix to cachebusting conditions
			if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($matchmedialistener['file']));}
			else {$cachebust = $vjscachebust;}
			wp_enqueue_script('matchmedialistener', $matchmedialistener['url'], array('jquery','matchmedia'), $cachebust,true);
		}
	}
 }
}

// --------------
// Load Modernizr
// --------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_modernizr')) {
 add_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr');
 function bioship_muscle_load_modernizr() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemevars, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['load'])) {$load = $vthemesettings['loadmodernizr'];} else {$load = 'off';}
	$load = bioship_apply_filters('muscle_load_modernizr', $load);
	// 2.0.2: fix to simplified variable typo
	if (!$load || ($load == 'off')) {return;}

	// --- load Modernizr ---
	// 2.0.1: use filtered value here also
	// TODO: allow for alternative includes/scripts directories ?
	if ($load == 'production') {
		// (with fallback to development version)
		$modernizr = bioship_file_hierarchy('both', 'modernizr.js', array('includes/foundation5/js/vendor', 'javascripts', 'js', 'assets/js'));
	} elseif ($load == 'development') {
		// (with fallback to production version)
		$modernizr = bioship_file_hierarchy('both', 'modernizr.js', array('javascripts','includes/foundation5/js/vendor', 'js', 'assets/js'));
	}
	if (is_array($modernizr)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($modernizr['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('modernizr', $modernizr['url'], array('jquery'), $cachebust, true);
		// 2.0.9: add javascript variable to global to auto-initialize modernizr
		$vthemevars[] = "var loadmodernizr = 'yes'; ";
	}
 }
}


// --------------
// === Extras ===
// --------------

// ---------------------
// Load Smooth Scrolling
// ---------------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_smooth_scrolling')) {

 add_action('wp_footer','bioship_muscle_smooth_scrolling');

 function bioship_muscle_smooth_scrolling() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars;

	// --- check load ---
	if (isset($vthemesettings['smoothscrolling'])) {$load = $vthemesettings['smoothscrolling'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_smooth_scrolling', $load);
	if (!$load) {return;}

	// --- add run trigger to footer ---
	// (detected by bioship-init.js)
	// 2.0.9: use theme load variables instead of input field
	$vthemevars[] = "var smoothscrolling = 'yes'; ";
 }
}

// -----------------------
// Load jQuery matchHeight
// -----------------------
// 1.9.9: added this for content grid (and other) usage
if (!function_exists('bioship_muscle_load_matchheight')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_matchheight');

 function bioship_muscle_load_matchheight() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadmatchheight'])) {$load = $vthemesettings['loadmatchheight'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_matchheight', $load);
	if (!$load) {return;}

	// --- load MatchHeight ---
	$matchheight = bioship_file_hierarchy('both', 'jquery.matchHeight.js', $vthemedirs['script']);
	if (is_array($matchheight)) {
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($matchheight['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('matchheight', $matchheight['url'], array('jquery'), $cachebust, true);

		// --- add run trigger to footer ---
		// (detected by bioship-init.js)
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

	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadstickykit'])) {$load = $vthemesettings['loadstickykit'];} else {$load = false;}
	// 1.9.9: fix to incorrect filter name typo
	$load = bioship_apply_filters('muscle_load_stickykit', $load);
	if (!$load) {return;}

	// --- load Sticky Kit ---
	$stickykit = bioship_file_hierarchy('both', 'jquery.sticky-kit.min.js', $vthemedirs['script']);
	if (is_array($stickykit)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($stickykit['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('stickykit', $stickykit['url'], array('jquery'), $cachebust, true);

		// --- set Sticky Kit elements ---
		// 2.0.9: set stickykit elements array variable instead of input field
		// 2.1.1: fix to themesettings variable typo
		$stickyelements = bioship_apply_filters('muscle_sticky_elements', $vthemesettings['stickyelements']);
		if (!$stickyelements) {return;}
		if (is_string($stickyelements)) {
			if ($stickyelements == '') {return;}
			elseif (strstr($stickyelements, ',')) {$stickyelements = explode(',', $stickyelements);}
			else {$stickyelements[0] = $stickyelements;
		}
		if (!is_array($stickyelements) || (count($stickyelements) < 1)) {return;}
		$scriptvar = "var stickyelements = new Array(); ";
		foreach ($stickyelements as $i => $element) {
			$scriptvar .= "stickyelements[".$i."] = '".trim($element)."'; ";
		}
		$vthemevars[] = $scriptvar;
	}
 }
}

// ------------
// Load FitVids
// ------------
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_fitvids')) {

 add_action('wp_enqueue_scripts','bioship_muscle_load_fitvids');

 function bioship_muscle_load_fitvids() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadfitvids'])) {$load = $vthemesettings['loadfitvids'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_fitvids', $load);
	if (!$load) {return;}

	// --- load FitVids ---
	$fitvids = bioship_file_hierarchy('both', 'jquery.fitvids.js', $vthemedirs['script']);
	if (is_array($fitvids)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($fitvids['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('fitvids', $fitvids['url'], array('jquery'), $cachebust, true);

		// --- set FitVids elements ---
		// 2.0.9: set fitvids elements array variable instead of input field
		$fitvidselements = bioship_apply_filters('muscle_fitvids_elements', $vthemesettings['fitvidselements']);
		if (!$fitvidselements) {return;}
		if (is_string($fitvidselements)) {
			if ($fitvidselements == '') {return;}
			elseif (strstr($fitvidselements, ',')) {$fitvidselements = explode(',', $fitvidselements);}
			else {$fitvidselements[0] = $fitvidselements;
		}
		if (!is_array($fitvidselements) || (count($fitvidselements) < 1)) {return;}
		$scriptvar = "var fitvidselements = new Array(); ";
		foreach ($fitvidselements as $i => $element) {
			$scriptvar .= "fitvidselements[".$i."] = '".trim($element)."'; ";
		}
		$vthemevars[] = $scriptvar;
	}
 }
}

// ------------------
// Load ScrollToFixed
// ------------------
// 1.5.0: added Scroll To Fixed library
// 1.8.5: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_load_scrolltofixed')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_scrolltofixed');

 function bioship_muscle_load_scrolltofixed() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemedirs, $vjscachebust;

	// --- check load ---
	if (isset($vthemesettings['loadscrolltofixed'])) {$load = $vthemesettings['loadscrolltofixed'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_scrolltofixed', $load);
	if (!$load) {return;}

	// --- load ScrollToFixed ---
	$scrolltofixed = bioship_file_hierarchy('both', 'jquery-scrolltofixed.min.js', $vthemedirs['script']);
	if (is_array($scrolltofixed)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$cachebust = date('ymdHi', filemtime($scrolltofixed['file']));}
		else {$cachebust = $vjscachebust;}
		wp_enqueue_script('scrolltofixed', $scrolltofixed['url'], array('jquery'), $cachebust, true);
	}
 }
}

// ------------------
// Logo Resize Switch
// ------------------
// 1.8.5: added this input switch for init.js
if (!function_exists('bioship_muscle_logo_resize')) {

 add_action('wp_footer', 'bioship_muscle_logo_resize');

 function bioship_muscle_logo_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemevars;

    // --- check load ---
    if (isset($vthemesettings['logoresize'])) {$load = $vthemesettings['logoresize'];} else {$load = false;}
    $load = bioship_apply_filters('muscle_logo_resize', $load);
    if (!$load) {return;}

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme load variables instead of input field
    $vthemevars[] = "var logoresize = 'yes'; ";
 }
}

// -----------------------
// Site Text Resize Switch
// -----------------------
// 2.0.9: expermental feature for bioship-init.js
if (!function_exists('bioship_muscle_site_text_resize')) {

 add_action('wp_footer', 'bioship_muscle_site_text_resize');

 function bioship_muscle_site_text_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemelayout, $vthemevars;

    // --- check load ---
    if (isset($vthemesettings['sitetextresize'])) {$load = $vthemesettings['sitetextresize'];} else {$load = false;}
    $load = bioship_apply_filters('muscle_site_text_resize', $load);
    if (!$load) {return;}

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
    $vthemevars[] = "var sitetextresize = 'yes'; ";
 }
}

// --------------------
// Header Resize Switch
// --------------------
// 2.0.9: added prototype feature for bioship-init.js
if (!function_exists('bioship_muscle_header_resize')) {

 add_action('wp_footer', 'bioship_muscle_header_resize');

 function bioship_muscle_header_resize() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    global $vthemesettings, $vthemelayout, $vthemevars;

    // --- check load ---
    if (isset($vthemesettings['headerresize'])) {$load = $vthemesettings['headerresize'];} else {$load = false;}
    $load = bioship_apply_filters('muscle_header_resize', $load);
    if (!$load) {return;}

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
	$vthemevars[] = "var headerresize = 'yes'; ";}
 }
}

// -------------------------------
// Output Script Loading Variables
// -------------------------------
// 2.0.9: added for outputting script load variables (for bioship-init.js)
add_action('wp_footer', 'bioship_muscle_output_script_vars', 11);
if (!function_exists('bioship_muscle_output_script_vars')) {
 function bioship_muscle_output_script_vars() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemevars, $vthemelayout;

 	// --- set and filter script variables ---
 	// 2.1.1: always output maxwidth at minimum
	$vthemevars[] = "var maxwidth = '".$vthemelayout['maxwidth']."'; ";
	// 2.1.1: add filter for script variables before output
	$scriptvars = bioship_apply_filters('muscle_script_vars', $vthemevars);
 	if (!is_array($scriptvars) || (count($scriptvars) < 1) ) {return;}

 	// --- output theme script variables ---
 	echo "<script>";
 	 	foreach ($scriptvars as $scriptvar) {
 	 		echo $scriptvar.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped
 	 	}
 	echo "</script>";
 }
}


// ------------------
// === Thumbnails ===
// ------------------

// -------------------
// JPEG Quality Filter
// -------------------
// 2.0.5: added a jpeg quality filter
if (!function_exists('bioship_muscle_jpeg_quality')) {

 add_filter('jpeg_quality', 'bioship_muscle_jpeg_quality', 10, 2);

 // note: context = image_resize or edit_image
 function bioship_muscle_jpeg_quality($quality, $context) {
	global $vthemesettings;
	if (isset($vthemesettings['jpegquality'])) {
		$newquality = $vthemesettings['jpegquality'];
		if ( ($newquality != '') && ($newquality != '0') ) {
			$newquality = absint($qual);
			if ( ($newquality > 0) && ($newquality < 101) ) {$quality = $newquality;}
		}
	}
	return $quality;
 }
}

// ------------------------------------------------
// Allow Thumbnail Size override on upload for CPTs
// ------------------------------------------------
// (note: post type support for the CPT must be active via theme options)
// each filter must be explicity set, ie. muscle_post_type_thumbsize_{size}
// ref: http://wordpress.stackexchange.com/questions/6103/change-set-post-thumbnail-size-according-to-post-type-admin-page

if (!function_exists('bioship_muscle_thumbnail_size_custom')) {

 add_filter('intermediate_image_sizes_advanced', 'bioship_muscle_thumbnail_size_custom', 10);

 function bioship_muscle_thumbnail_size_custom($sizes) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- get post ID and type ---
	// rather funny way of doing it but seems to work fine
	// as this is for the admin post/page editing screen
	if (isset($_REQUEST['post_id'])) {
		$postid = absint($_REQUEST['post_id']);
		$posttype = get_post_type($postid);
	} else {
		// CHECKME: what to do for new (draft) posts (ie. with no saved post ID yet)?
		return;
	}

	// --- get default thumbnail size options ---
	// (as in theme setup)
	global $vthemesettings;
	$thumbnailwidth = bioship_apply_filters('skeleton_thumbnail_width', 250);
	$thumbnailheight = bioship_apply_filters('skeleton_thumbnail_height', 250);

	// --- get cropping options ---
	$crop = get_option('thumbnail_crop');
	$thumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ($thumbnailcrop == 'nocrop') {$crop = false;}
	if ($thumbnailcrop == 'auto') {$crop = true;}
	if (strstr($thumbnailcrop, '-')) {$crop = explode('-', $thumbnailcrop);}
	$thumbsize = array($thumbnailwidth, $thumbnailheight, $crop);

	// --- filter for this post type ---
	$newthumbsize = bioship_apply_filters('muscle_post_type_thumbsize_'.$posttype, $thumbsize);
	if ($thumbsize != $newthumbsize) {
		if ( (is_numeric($newthumbsize[0])) && (is_numeric($newthumbsize[1])) ) {
			$thumbsize = $newthumbsize;
		}
	}

	// --- set explicitly whether default or changed ---
	$sizes['post-thumbnail'] = array('width' => $thumbsize[0], 'height' => $thumbsize[1], 'crop' => $thumbsize[2]);
    return $sizes;
 }
}

// --------------------------
// Fun with Fading Thumbnails
// --------------------------
// TODO: retest and add this feature to theme options?
if (!function_exists('bioship_muscle_fading_thumbnails')) {
 // add_filter('the_posts', 'bioship_muscle_fading_thumbnails', 10, 2);
 function bioship_muscle_fading_thumbnails($posts, $query) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	if (!is_archive()) {return $posts;}

 	// TODO: add fading thumbnails loading filter here

	$cptslug = 'post'; $fadingthumbs = false;
	$posttypes = bioship_get_post_types($query);
	if (is_array($posttypes) && in_array($cptslug, $posttypes)) {$fadingthumbs = true;}
	elseif (!is_array($posttypes) && ($cptslug == $posttypes)) {$fadingthumbs = true;}

	if ($fadingthumbs) {
	    global $vthemelayout; $vthemelayout['fadingthumbnails'] = $cptslug;
	    // 2.1.1: fix to function name type had_action
	    if (!has_action('wp_footer', 'bioship_muscle_fading_thumbnail_script')) {
	    	add_action('wp_footer', 'bioship_muscle_fading_thumbnail_script');
	    }
	}

	if (!function_exists('bioship_muscle_fading_thumbnail_script')) {
	 function bioship_muscle_fading_thumbnail_script() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemelayout;
		$fadingthumbs = $vthemelayout['fadingthumbnails'];

		// TODO: allow for multiple CPTs/classes?
		echo "<script>var thumbnailclass = 'img.thumbtype-".esc_attr($fadingthumbs)."';
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

// ------------------------------------------------
// Include/Exclude Categories from Home (Blog) Page
// ------------------------------------------------
if (!function_exists('bioship_muscle_select_home_categories')) {

 add_filter('pre_get_posts', 'bioship_muscle_select_home_categories');

 function bioship_muscle_select_home_categories($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	if ($query->is_home()) {

		global $vthemesettings; $mode = false;
		if (isset($vthemesettings['homecategorymode'])) {$mode = $vthemesettings['homecategorymode'];}
		$mode = bioship_apply_filters('muscle_home_category_mode', $mode);
		if (!$mode || ($mode == 'all')) {return;}
		$valid = array('include', 'exclude', 'includeexclude');
		if (!in_array($mode, $valid)) {return;}

		// 2.0.0: added category mode/include/exclude filters
		$includecategories = bioship_apply_filters('muscle_home_include_categories', $vthemesettings['homeincludecategories']);
		$excludecategories = bioship_apply_filters('muscle_home_exclude_categories', $vthemesettings['homeexcludecategories']);

		// 2.0.1: revamped include / exclude logic
		$categories = get_categories(); $selected = array();
		$included = $excluded = false;
		if (is_array($includecategories)) {$included = true;}
		if (is_array($excludecategories)) {$excluded = true;}

		foreach ($categories as $category) {
			$catid = $category->cat_ID;
			if ($included && ( ($mode == 'include') || ($mode == 'includeexclude') ) ) {
				if (isset($includecategories[$catid])) {$selected[] = $catid;}
			}
			if ($excluded && ( ($mode == 'exclude') || ($mode == 'includeexclude') ) ) {
				if (isset($excludecategories[$catid])) {$selected[] = '-'.$catid;}
			}
		}

		if (count($selected) > 0) {
			$catstring = implode(' ', $selected);
			$query->set('cat', $catstring);
		}
	}
	return $query;
 }
}

// ---------------------------------
// Number of Search Results per Page
// ---------------------------------
// 2.0.1: filter themesettings internally
if (!function_exists('bioship_muscle_search_results_per_page')) {

 // 2.1.1: moved add_action internally for consistency
 add_action('pre_get_posts', 'bioship_muscle_search_results_per_page');

 function bioship_muscle_search_results_per_page($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings, $wp_the_query;

	// 2.0.0: added muscle_search_results filter
	$searchresults = bioship_apply_filters('muscle_search_results', $vthemesettings['searchresults']);
	$searchresults = absint($searchresults);
	if (is_numeric($searchresults)) {
		if (!is_admin() && ($query === $wp_the_query) && ($query->is_search())) {
			$query->set('posts_per_page', $searchresults);
		}
	}
	return $query;
 }
}

// ---------------------------------
// Make Custom Post Types Searchable
// ---------------------------------
// 2.0.1: filter themesettings internally
if (!function_exists('bioship_muscle_searchable_cpts')) {

 add_filter('the_search_query', 'bioship_muscle_searchable_cpts');

 function bioship_muscle_searchable_cpts($query) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;

	// 2.1.1: moved trigger check internally
	if (!is_search()) {return;}

	// --- get searchable CPTs ---
	$searchablecpts = false;
	if (isset($vthemesettings['searchablecpts'])) {$searchablecpts = $vthemesettings['searchablecpts'];}
	$searchablecpts = bioship_apply_filters('muscle_searchable_cpts', $searchablecpts);

	// --- set searchable CPTs ---
	// 2.0.1: fix to search logic array here
	if (is_array($searchablecpts) && (count($vsearchablecpts) > 0)) {
		$cpts = array();
		foreach ($searchablecpts as $cpt => $value) {
			if ($value == '1') {$cpts[] = $cpt;}
		}
		if ($query->is_search) {$query->set('post_type', $cpts);}
	}
	return $query;
 }
}

// -------------------------------
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
	global $vthemesettings;

	// --- check load ---
	if (isset($vthemesettings['infinitescroll'])) {$load = $vthemesettings['infinitescroll'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_infinitescroll', $load);
	if ( ($load != 'scroll') && ($load != 'click') ) {return;}

	// --- get footer widget areas ---
	$footersidebars = $vthemesettings['footersidebars'];
	if ($footersidebars > 0) {$footerwidgets[0] = 'footer-widget-area-1';}
	if ($footersidebars > 1) {$footerwidgets[1] = 'footer-widget-area-2';}
	if ($footersidebars > 2) {$footerwidgets[2] = 'footer-widget-area-3';}
	if ($footersidebars > 3) {$footerwidgets[3] = 'footer-widget-area-4';}

	// --- set scroll settings ---
	$settings = array(
		'type' => $load,
		'container' => 'content',
		'footer' => 'footer',
		'footer_widgets', $footerwidgets,
		'wrapper' => 'infinite-wrap',
		'render' => 'muscle_infinite_scroll_loop'
	);

	// --- filter settings ---
	// 1.8.0: added override filters
	$postsperpage = bioship_apply_filters('skeleton_infinite_scroll_numposts', '');
	if (is_numeric($postsperpage)) {$settings['posts_per_page'] = $postsperpage;}
	$settings = bioship_apply_filters('skeleton_infinite_scroll_settings', $settings);

	// --- add theme support ---
	add_theme_support('infinite-scroll', $settings);

	// TODO: use file hierarchy for infinite loader gif
	// ... and then remove this style from style.css
	// span.infinite-loader {url('../images/infinite-loader.gif');}

	// --- loop render callback ---
	// 2.0.1: moved this inside loader function
	// TODO: maybe update Infinite Scroll to use AJAX Load More Template ?
	if (!function_exists('bioship_muscle_infinite_scroll_loop')) {
	 function bioship_muscle_infinite_scroll_loop() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- simple post loop ---
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

// ----------------------------
// Add Excerpt Support to Pages
// ----------------------------
// 1.8.0: add page excerpt support option
if (isset($vthemesettings['pageexcerpts']) && ($vthemesettings['pageexcerpts'] == '1')) {
	add_post_type_support('page', 'excerpt');
}

// -----------------------------
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

// ------------------------
// Excerpts with Shortcodes
// ------------------------
// 1.9.8: copy of wp_trim_excerpt but with shortcodes kept
// note: formatting is still stripped but shortcode text remains
if (!function_exists('bioship_muscle_excerpts_with_shortcodes')) {
 function bioship_muscle_excerpts_with_shortcodes($text) {
	// for use in shortcodes to provide alternative output
	global $doingexcerpt; $doingexcerpt = true;

	$text = get_the_content('');
	// $text = strip_shortcodes($text); // modification
	$text = bioship_apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = bioship_apply_filters( 'excerpt_length', 55 );
	$excerpt_more = bioship_apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
	$text = wp_trim_words($text, $excerpt_length, $excerpt_more);
	$doingexcerpt = false; return $text;
 }
}

// ---------------------
// Filter Excerpt Length
// ---------------------
// 1.8.5: move checks to inside filter
if (!function_exists('bioship_muscle_excerpt_length')) {

	add_filter('excerpt_length', 'bioship_muscle_excerpt_length');

	// 2.0.5: move old pseudonym to compat.php
	function bioship_muscle_excerpt_length($length) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings;

		// --- RSS feed excerpt length ---
		// 1.8.5: added alternative feed excerpt length
		// 2.0.9: fix to old themeoption variable
		if (is_feed()) {
			if (isset($vthemesettings['rssexcerptlength']) && ($vthemesettings['rssexcerptlength'] != '')) {
				// 2.1.1: added missing rss excerpt length filter
				$length = bioship_apply_filters('muscle_excerpt_length_rss', $vthemesettings['rssexcerptlength']);
				$length = abs(intval($length));
				if ( ($length == 0) || ($length < 0) ) {$length = PHP_INT_MAX;}
				return $length;
			}
		}

		// --- standard excerpt length ---
		// 1.8.5: simplified and improved code here
		if (isset($vthemesettings['excerptlength']) && ($vthemesettings['excerptlength'] != '') ) {
			// 2.1.1: added missing excerpt length filter
			$length = bioship_apply_filters('muscle_excerpt_length', $vthemesettings['excerptlength']);
			$length = abs(intval($length));
			if ( ($length == 0) || ($length < 0) ) {$length = PHP_INT_MAX;}
		}

		return $length;
	}
}

// --------------
// Read More Link
// --------------
// note: Default = 'Continue reading <span class="meta-nav">&rarr;</span>';
if ($vthemesettings['readmoreanchor'] != '') {
	// 2.0.5: move old pseudonym to compat.php
	if (!function_exists('bioship_muscle_continue_reading_link')) {
	 function bioship_muscle_continue_reading_link() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;

		// --- create and filter read more link ---
		// 2.0.0: added muscle_read_more_anchor filter
		$readmoreanchor = bioship_apply_filters('muscle_read_more_anchor', $vthemesettings['readmoreanchor']);
		$readmore = ' <a href="'.get_permalink().'">'.$readmoreanchor.'</a>';
		// 2.1.1: added filter muscle_read_more_link filter
		$readmore = bioship_apply_filters('muscle_read_more_link', $readmore);
		return $readmore;
	 }
	}
}

// -----------------
// Read More Wrapper
// -----------------
// Default = ' &hellip;';
// 2.0.5: removed outside settings check so filtered
// 2.0.5: move old pseudonym to compat.php
if (!function_exists('bioship_muscle_auto_excerpt_more')) {

 add_filter('excerpt_more', 'bioship_muscle_auto_excerpt_more');

 function bioship_muscle_auto_excerpt_more($more) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;
	// 2.0.0: added muscle_read_more_before filter
	$readmorebefore = bioship_apply_filters('muscle_read_more_filter', $vthemesettings['readmorebefore']);

	// 2.1.1: fix to actually use filtered value
	if (function_exists('bioship_muscle_continue_reading_link')) {
		$readmore = '<div class="readmore">'.$readmorebefore.bioship_muscle_continue_reading_link().'</div>';
	} else {
		$default = ' <a href="'.get_permalink().'">'.__('Continue reading','bioship').' <span class="meta-nav">&rarr;</span></a>';
		// 2.0.9: added default continue reading link back into readmore div
		$readmore = '<div class="readmore">'.$readmorebefore.$default.'</div>';
	}
	return $readmore;
 }
}

// -----------------------
// Remove More 'Jump' Link
// -----------------------
// TODO: maybe add a theme option for removing jump link?
if (!function_exists( 'bioship_muscle_remove_more_jump_link')) {

 add_filter('the_content_more_link', 'bioship_muscle_remove_more_jump_link');

 function bioship_muscle_remove_more_jump_link($link) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- remove the hashed suffix ---
	$offset = strpos($link, '#more-');
	// 2.0.9: fix to link variable typo
	if ($offset) {$end = strpos($link, '"', $offset);}
	if ($end) {$link = substr_replace($link, '', $offset, ($end - $offset));}
	return $link;
 }
}


// ---------------
// === Writing ===
// ---------------

// --------------------
// Limit Post Revisions
// --------------------
// 1.8.0: [deprecated] moved to separate AutoSave Net plugin

// ------------------------------------
// WP Subtitle Custom Post Type Support
// ------------------------------------
if (!function_exists('bioship_muscle_wp_subtitle_custom_support')) {

 // 2.1.1: moved add_action internally for consistency
 add_action('init', 'bioship_muscle_wp_subtitle_custom_support');

 function bioship_muscle_wp_subtitle_custom_support() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	// --- WP Subtitle plugin check ---
	if (!function_exists('get_the_subtitle')) {return;}

	// -- add/remove CPT subtitle support ---
	$cptsubtitles = $vthemesettings['subtitlecpts'];
	// 2.0.8: fix for possible empty subtitle setting
	if (is_array($cptsubtitles)) {
		foreach ($cptsubtitles as $cpt => $value) {
			if ($value) {
				if ( ($cpt != 'post') && ($cpt != 'page') ) {add_post_type_support($cpt, 'wps_subtitle');}
			} else {
				if ($cpt == 'post') {remove_post_type_support('post', 'wps_subtitle');}
				if ($cpt == 'page') {remove_post_type_support('page', 'wps_subtitle');}
			}
		}
	}
 }
}


// -----------------
// === RSS Feeds ===
// -----------------

// --------------------
// Automatic Feed Links
// --------------------
// 2.0.5: added missing setting filter
if ( (isset($vthemesettings['autofeedlinks'])) && ($vthemesettings['autofeedlinks'] == '1') ) {
	$autofeedlinks = true;
} else {$autofeedlinks = false;}
$autofeedlinks = bioship_apply_filters('muscle_automatic_feed_links', $autofeedlinks);
if ($autofeedlinks) {add_theme_support('automatic-feed-links');}
else {remove_theme_support('automatic-feed-links');}

// -----------------
// RSS Publish Delay
// -----------------
// 2.0.5: check setting internally to allow filtering
if (!function_exists('bioship_muscle_delay_feed_publish')) {

 add_filter('posts_where', 'bioship_muscle_delay_feed_publish');

 function bioship_muscle_delay_feed_publish($where) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $wpdb, $vthemesettings;

	// --- check conditions ---
	// 2.0.5: added missing setting filter
	// 2.1.0: still allow filtering if not set (wp.org version)
	// 2.1.1: fix to delay bypass conditions
	if (!is_feed()) {return $where;}
	if (!isset($vthemesettings['rsspublishdelay'])) {$delay = false;}
	else {$delay = $vthemesettings['rsspublishdelay'];}
	$delay = bioship_apply_filters('muscle_rss_feed_publish_delay', $delay);
	if ( !$delay || !is_numeric($delay) || ($delay == 0)) {return $where;}

	$now = gmdate('Y-m-d H:i:s');
	// ref: http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
	$units = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
	$units = bioship_apply_filters('muscle_rss_feed_delay_units', $units);
	// add SQL-sytax to default $where
	$where .= " AND TIMESTAMPDIFF(".$units.", $wpdb->posts.post_date_gmt, '".$now."') > ".$delay." ";

	return $where;
 }
}

// --------------------------
// Set Post Types in RSS Feed
// --------------------------
// 2.0.5: check settings internally to allow filtering
// 2.0.5: simplified logic for this filter function
if (!function_exists('bioship_muscle_custom_feed_request')) {

 add_filter('request', 'bioship_muscle_custom_feed_request');

 function bioship_muscle_custom_feed_request($vars) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;

	if (!is_feed()) {return $vars;}
	// 2.1.0: set to false if setting is not present (WordPress.Org version)
	if (!isset($vthemesettings['cptsinfeed'])) {$cptsinfeed = false;}
	else {$cptsinfeed = $vthemesettings['cptsinfeed'];}
	$cptsinfeed = bioship_apply_filters('muscle_rss_feed_post_types', $cptsinfeed);
	bioship_debug("Feed CPTs", $cptsinfeed);

	if ($cptsinfeed && is_array($cptsinfeed)) {
		if (isset($vars['feed']) && !isset($vars['post_type'])) {
			// TODO: recheck whether this is still working as desired
			$vars['post_type'] = $cptsinfeed;
		}
	}
	return $vars;
 }
}

// -------------------------------
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

	// --- check conditions ---
	// 2.1.0: default to false if setting is not present (WordPress.Org version)
	if (!isset($vthemesettings['pagecontentfeeds'])) {$pagefeeds = false;}
	else {$pagefeeds = $vthemesettings['pagecontentfeeds'];}
	$pagefeeds = bioship_apply_filters('muscle_rss_full_page_feeds', $pagefeeds);
	if (!$pagefeeds) {return $query;}

	// --- check for single page feed request ---
	if ($query->is_main_query() && $query->is_feed() && $query->is_page()) {
		// set the post type to page
		$query->set('post_type', array('page'));
		// allow for page comments feed via ?withcomments=1
		if (isset($_GET['withcomments']) && ($_GET['withcomments'] == '1')) {return;}
		// set the comment feed to false
		$query->is_comment_feed = false;
	}

	// --- debug feed query ---
	if ($query->is_feed()) {bioship_debug("Feed Query", $query);}
 }
}

// --------------------------------
// RSS Full Page Feed Option Filter
// --------------------------------
// 2.0.0: fix to typo in funcname
if (!function_exists('bioship_muscle_page_rss_excerpt_option')) {

 // 2.0.5: move add_filter inside for consistency
 add_filter('pre_option_rss_use_excerpt', 'bioship_muscle_page_rss_excerpt_option');

 function bioship_muscle_page_rss_excerpt_option($option) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;

	// --- check setting ---
	$pagefeeds = bioship_apply_filters('muscle_rss_full_page_feeds', $vthemesettings['pagecontentfeeds']);
	if (!$pagefeeds) {return $option;}

	// --- force full content output for pages ---
	if (is_page()) {return '0';}
	return $option;
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


// -----------------------
// Load the Dashboard Feed
// -----------------------
if (!function_exists('bioship_muscle_add_bioship_dashboard_feed_widget')) {

	$requesturi = $_SERVER['REQUEST_URI'];
	// 2.0.1: fix for network string match typo
	if ( (preg_match('|index.php|i', $requesturi))
	  || (substr($requesturi, -(strlen('/wp-admin/'))) == '/wp-admin/')
	  || (substr($requesturi, -(strlen('/wp-admin/network/'))) == '/wp-admin/network/') ) {
		add_action('wp_dashboard_setup', 'bioship_muscle_add_bioship_dashboard_feed_widget');
	}

	function bioship_muscle_add_bioship_dashboard_feed_widget() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: do not add the dashboard feed for WordPress.Org version
		if (THEMEWPORG) {return;}

		// --- check permissions ---
		if ( (current_user_can('manage_options')) || (current_user_can('update_themes'))
		  || (current_user_can('edit_theme_options')) ) {

			// --- load dashboard feed widget ---
			// 1.9.9: fix to undefined index warning
			global $wp_meta_boxes; $feedloaded = false;
			foreach (array_keys($wp_meta_boxes['dashboard']['normal']['core']) as $name) {
				if ($name == 'bioship') {$feedloaded = true;}
			}
			if (!$feedloaded) {
				wp_add_dashboard_widget('bioship', __('BioShip News','bioship'), 'bioship_muscle_bioship_dashboard_feed_widget');
			}
		}
	}
}

// -----------------------------
// BioShip Dashboard Feed Widget
// -----------------------------
// TODO: move this to admin.php
// 1.9.5: added displayupdates argument
// 2.0.0: added displaylinks argument
if (!function_exists('bioship_muscle_bioship_dashboard_feed_widget')) {
 function bioship_muscle_bioship_dashboard_feed_widget($displayupdates=true, $displaylinks=false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemedirs;

	// Display Updates Available
	// -------------------------
	if (!function_exists('admin_theme_updates_available')) {
		// 2.0.0: fix to file hierarchy search dir
		$admin = bioship_file_hierarchy('file', 'admin.php', $vthemedirs['admin']);
		include_once($admin);
	}
	if ($displayupdates) {
		echo admin_theme_updates_available(); // phpcs:ignore WordPress.Security.OutputNotEscaped
	}

	// Load the Update News Feed
	// -------------------------
	$baseurl = THEMEHOMEURL;
	$rssurl = $baseurl."/feed/";

	// --- maybe get feed transient ---
	// 1.8.0: set transient for daily feed update only
	// 2.0.0: clear feed transient for debugging
	delete_transient('bioship_feed');
	if (THEMEDEBUG) {$feed = ''; delete_transient('bioship_feed');}
	else {$feed = trim(get_transient('bioship_feed'));}

	// --- fetch feed ---
	if (!$feed || ($feed == '')) {
		$rssfeed = fetch_feed($rssurl); $feeditems = 4;
		$feed = bioship_muscle_process_rss_feed($rssfeed, $feeditems);
		if ($feed != '') {set_transient('bioship_feed', $feed, (24*60*60));}
	}

	// --- feed link styles ---
	// 1.8.0: set link hover class
	echo "<style>.themefeedlink {text-decoration:none;} .themefeedlink:hover {text-decoration:underline;}</style>";

	// --- maybe display links ---
	// 2.0.0: add documentation, development and extensions links
	if ($displaylinks) {
		echo "<center><b><a href='".esc_url(THEMEHOMEURL.'/documentation/')."' class='themefeedlink' target=_blank>".esc_attr(__('Documentation','bioship'))."</a></b> | ";
		echo "<b><a href='".esc_url(THEMEHOMEURL.'/development/')."' class='themefeedlink' target=_blank>".esc_attr(__('Development','bioship'))."</a></b> | ";
		echo "<b><a href='".esc_url(THEMEHOMEURL.'/extensions/')."' class='themefeedlink' target=_blank>".esc_attr(__('Extensions','bioship'))."</a></b></center><br>";
	}

	// --- output feed ---
	// 1.8.5: fix to typo on close div ruining admin page
	// 2.0.0: re-arrange display output and styles
	echo "<div id='bioshipfeed'>";
	echo "<div style='float:right;'>&rarr;<a href='".esc_url($baseurl.'/category/news/')."' class='themefeedlink' target=_blank> ".esc_attr(__('More','bioship'))."...</a></div>";
	if ($feed != '') {echo $feed;} else {echo esc_attr(__('Feed currently unavailable.','bioship')); delete_transient('bioship_feed');}
	echo "</div>";

 }
}

// ----------------
// Process RSS Feed
// ----------------
if (!function_exists('bioship_muscle_process_rss_feed')) {
 function bioship_muscle_process_rss_feed($rss, $feeditems) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- bug out on error ---
	if (is_wp_error($rss)) {return '';}

	// --- get RSS items ---
	$maxitems = $rss->get_item_quantity($feeditems);
	$rssitems = $rss->get_items(0, $maxitems);

	// --- create item list ---
	// 1.8.0: fix to undefined index warning
	$processed = '';
	if (count($rssitems) > 0) {
		$processed = "<ul style='list-style:none; margin:0px; text-align:left;'>";
		foreach ($rssitems as $item ) {
			$processed .= "<li>&rarr; <a href='".esc_url($item->get_permalink())."' target='_blank' ";
			$processed .= "title='Posted ".$item->get_date('j F Y | g:i a')."' class='themefeedlink'>";
			$processed .= esc_html($item->get_title())."</a></li>";
		}
		$processed .= "</ul>";
	}
	return $processed;
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

 // add_filter('manage_posts_columns', 'bioship_muscle_admin_post_thumbnail_column', 5);

 function bioship_muscle_admin_post_thumbnail_column($cols){
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// TODO: Add a filter for thumbnail column use with other CPTs?
	// ...which would allow for post or page selection also...
	$thumbcols = $vthemesettings['adminthumbnailcolumn'];
	$thumbcols = bioship_apply_filters('muscle_post_thumbnail_column', $thumbcols);
	if (!$thumbcols) {return $cols;}

	add_action('manage_posts_custom_column', 'bioship_muscle_display_post_thumbnail_column', 5, 2);
	// TODO: check featured image support for pages here ?
	add_action('manage_pages_custom_column', 'bioship_muscle_display_post_thumbnail_column', 5, 2);

	$cols['post_thumb'] = __('Thumbnail','bioship');
	return $cols;
 }
}

// -------------------------------
// Post Thumbnail Display Callback
// -------------------------------
if (!function_exists('bioship_muscle_display_post_thumbnail_column')) {
 function bioship_muscle_display_post_thumbnail_column($col, $id) {
	if ($col == 'post_thumb') {
		echo the_post_thumbnail('admin-list-thumb'); // phpcs:ignore WordPress.Security.OutputNotEscaped
	}
 }
}

// ---------------------------------------
// Add "All Options" Page to Settings Menu
// ---------------------------------------
if (!function_exists('bioship_muscle_all_options_link')) {

 // 2.0.1: moved filter option internally
 add_action('admin_menu', 'bioship_muscle_all_options_link', 11);

 function bioship_muscle_all_options_link() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $submenu;

	// 2.1.1: do not add All Options link for WordPress.Org version
	if (THEMEWPORG) {return;}

	// --- check setting ---
	if (isset($vthemesettings['alloptionspage'])) {$addlink = $vthemesettings['alloptionspage'];} else {$addlink = false;}
	$addlink = bioship_apply_filters('muscle_all_options_page', $addlink);

	if ($addlink == '1') {
		// 2.0.7: changed to use add_theme_page instead of add_options_page
		add_theme_page(__('All Options','bioship'), __('All Options','bioship'), 'manage_options', 'options.php');

		// 2.0.7: then shift from themes to settings menu
		// 2.1.0: check submenu key exists before looping
		if (isset($submenu['options-general.php'])) {
			foreach ($submenu['options-general.php'] as $key => $values) {$lastkey = $key + 1;}
		}
		if (isset($submenu['themes.php'])) {
			foreach ($submenu['themes.php'] as $key => $values) {
				if ($values[2] == 'options.php') {
					$submenu['options-general.php'][$lastkey] = $values;
					unset($submenu['themes.php'][$key]);
				}
			}
		}
	}
 }
}

// --------------------
// Remove Update Notice
// --------------------
if (!function_exists('bioship_muscle_remove_update_notice')) {

 add_action('init', 'bioship_muscle_remove_update_notice', 1);

 function bioship_muscle_remove_update_notice() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	// --- check setting ---
	if (isset($vthemesettings['removeupdatenotice'])) {$remove = $vthemesettings['removeupdatenotice'];} else {$remove = false;}
	$remove = bioship_apply_filters('muscle_remove_update_notice', $remove);
	if ($remove != '1') {return;}

	// --- permission checks ---
	// 2.1.1: changed from update_plugins to update_core
	if (!current_user_can('update_core')) {
		// 2.0.1: simplify to remove version check action here
		remove_action('init', 'wp_version_check');
		add_filter('pre_option_update_core', '__return_null');
	}
 }
}

// ---------------------------
// Stop New User Notifications
// ---------------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_stop_new_user_notifications')) {

 add_action('phpmailer_init', 'bioship_muscle_stop_new_user_notifications');

 function bioship_muscle_stop_new_user_notifications() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	if (isset($vthemesettings['disablenotifications'])) {$disable = $vthemesettings['disablenotifications'];} else {$disable = false;}
	$disable = bioship_apply_filters('muscle_stop_new_user_notifications', $disable);
	if ($disable != '1') {return;}

	// note: handling translation wrappers in subject line is not working,
	// so this feature may not work with multi-language translations
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
		// 2.1.0: need to remove translation wrappers
		$subject = array(
			sprintf('[%s] New User Registration', $blogname),
			sprintf('[%s] Password Lost/Changed', $blogname)
		);
		if (in_array($phpmailer->Subject, $subject)) {$phpmailer = new PHPMailer(true);}
	}
 }
}

// ------------------
// Disable Self Pings
// ------------------
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_disable_self_pings')) {

 add_action('pre_ping','bioship_muscle_disable_self_pings');

 // 2.0.0: remove unneeded pass by reference in argument
 function bioship_muscle_disable_self_pings($links) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;

	// --- check settings ---
	if (isset($vthemesettings['disableselfpings'])) {$disable = $vthemesettings['disableselfpings'];} else {$disable = false;}
	$disable = bioship_apply_filters('muscle_disable_self_pings', $disable);
	if ($disable != '1') {return;}

	// --- remove ping if contains home URL ---
	// 1.5.5: fix to use home_url for theme check
	$home = home_url();
	foreach ($links as $i => $link) {if (0 === strpos($link, $home)) {unset($links[$i]);} }
 }
}

// -----------------
// Cleaner Admin Bar
// -----------------
// (removes WP links)
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_cleaner_adminbar')) {

 add_action('wp_before_admin_bar_render', 'bioship_muscle_cleaner_adminbar');

 function bioship_muscle_cleaner_adminbar() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	// --- check settings ---
	if (isset($vthemesettings['cleaneradminbar'])) {$clean = $vthemesettings['cleaneradminbar'];} else {$clean = false;}
	$clean = bioship_apply_filters('muscle_cleaner_admin_bar', $clean);
	if ($clean != '1') {return;}

	// --- set items to remove ---
	global $wp_admin_bar;
	// 1.8.0: added array filter for altering adminbar link removal
	$removeitems = array('wp-logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback');
	$removeitems = bioship_apply_filters('admin_adminbar_remove_items', $removeitems);

	// --- remove admin bar items ---
	if (count($removeitems) > 0) {
		foreach ($removeitems as $removeitem) {$wp_admin_bar->remove_menu($removeitem);}
	}
 }
}

// -----------------------------------------
// Include CPTs in the Dashboard 'Right Now'
// -----------------------------------------
// TODO: move to admin.php ?
// 2.0.1: check themesettings internally to allow filtering
if (!function_exists('bioship_muscle_right_now_content_table_end')) {

 add_action('right_now_content_table_end','bioship_muscle_right_now_content_table_end');

 function bioship_muscle_right_now_content_table_end() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	// --- check settings ---
	if (isset($vthemesettings['cptsrightnow'])) {$modify = $vthemesettings['cptsrightnow'];} else {$modify = false;}
	$modify = bioship_apply_filters('muscle_cpts_right_now', $modify);
	if ($modify != '1') {return;}

	// --- custom post type list ---
	$args = array('public' => true, '_builtin' => false);
	$output = 'object'; $operator = 'and';
	$posttypes = get_post_types($args, $output, $operator);
	foreach($posttypes as $posttype) {
		$num_posts = wp_count_posts($posttype->name);
		$num = number_format_i18n($num_posts->publish);
		// 2.0.7: added missing text domain
		$singular = $posttype->labels->singular_name;
		$label = $posttype->labels->name;
		$postcount = intval($num_posts->publish);
		// 2.1.0: changed this as cannot translate variables
		// $text = _n($singular, $label, $postcount, 'bioship');
		if ($postcount == 1) {$text = $postcount.' '.$singular;}
		else {$text = $postcount.' '.$label;}
		if (current_user_can('edit_posts')) {
			$num = "<a href='edit.php?post_type=".esc_attr($posttype->name)."'>".esc_attr($num)."</a>";
			$text = "<a href='edit.php?post_type=".esc_attr($posttype->name)."'>".esc_attr($text)."</a>";
		}
		echo '<tr><td class="first num b b-'.esc_attr($posttype->name).'">'.esc_attr($num).'</td>';
		echo '<td class="text t '.esc_attr($posttype->name).'">'.esc_attr($text).'</td></tr>';
	}

	// --- tag terms list ---
	$taxonomies = get_taxonomies($args, $output, $operator);
	foreach ($taxonomies as $taxonomy) {
		$num_terms  = wp_count_terms($taxonomy->name);
		$num = number_format_i18n($num_terms);
		// 2.0.7: added missing text domain
		$singular = $taxonomy->labels->singular_name;
		$label = $taxonomy->labels->name;
		$termcount = intval($num_terms);
		// 2.1.0: changed as cannot translate variables
		// $text = _n($singular, $label, $termcount, 'bioship');
		if ($termcount == 1) {$text = $termcount.' '.$singular;}
		else {$text = $termcount.' '.$label;}
		if (current_user_can('manage_categories')) {
			$num = "<a href='edit-tags.php?taxonomy=".esc_attr($taxonomy->name)."'>".esc_attr($num)."</a>";
			$text = "<a href='edit-tags.php?taxonomy=".esc_attr($taxonomy->name)."'>".esc_attr($text)."</a>";
		}
		echo '<tr><td class="first b b-'.sec_attr($taxonomy->name).'">'.esc_attr($num).'</td>';
		echo '<td class="t '.esc_attr($taxonomy->name).'">'.esc_attr($text).'</td></tr>';
	}
 }
}

// ----------------
// Login Header URL
// ----------------
if (!function_exists('bioship_muscle_login_headerurl')) {

 add_filter('login_headerurl', 'bioship_muscle_login_headerurl' );

 function bioship_muscle_login_headerurl($url) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.0.9: added missing theme-specific filtering
	// 2.1.1: added missing bioship_ function prefix
	$url = bioship_apply_filters('muscle_login_header_title', site_url('/'));
	return $url;
 }
}

// ------------------
// Login Header Title
// ------------------
if (!function_exists('bioship_muscle_login_headertitle')) {

 add_filter('login_headertitle', 'bioship_muscle_login_headertitle');

 function bioship_muscle_login_headertitle($title) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.0.9: added missing theme-specific filtering
	// 2.1.1: added missing bioship_ function prefix
	$title = bioship_apply_filters('muscle_login_header_title', get_bloginfo('name'));
	return $title;
 }
}

// ---------------
// Login Page Logo
// ---------------
// (adds a #loginwrapper element to help styling)
// 1.8.5: moved actual login styling to skin.php
// 1.8.5: much fun with login wrapper hacks!
if (!function_exists('bioship_muscle_login_styles')) {

 add_action('login_head', 'bioship_muscle_login_styles');

 function bioship_muscle_login_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- add loginwrapper class placeholder ---
	if (!function_exists('bioship_muscle_login_body_hack')) {
	 add_filter('login_body_class', 'bioship_muscle_login_body_hack', 999);
	 function bioship_muscle_login_body_hack($classes) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$classes[] = 'LOGINWRAPPER';
		add_filter('attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2);
		return $classes;
	 }
	}

	// --- replace loginwrapper class with div ---
	if (!function_exists('bioship_muscle_login_body_filter_hack')) {
	 function bioship_muscle_login_body_filter_hack($safetext, $text) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$replace = '"><div id="loginwrapper'; // "
		$safetext = str_replace('LOGINWRAPPER', $replace, $safetext);
		remove_filter('attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2);
		return $safetext;
	 }
	}

	// --- close loginwrapper div ---
	if (!function_exists('bioship_muscle_close_login_wrapper')) {
	 add_action('login_footer', 'bioship_muscle_close_login_wrapper');
	 function bioship_muscle_close_login_wrapper() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skin_dynamic_login_css_inline();
		echo "</div>".PHP_EOL;
		bioship_html_comment('/#loginwrapper');
	 }
	}
 }
}


// --------------------
// === Integrations ===
// --------------------

// ========
// CSS Hero
// ========

// ---------------------------------
// Adjust CSS Hero Declarations Path
// ---------------------------------
// also allows for the file to be in the parent theme directory
// (this is a bit hacky, hopefully a real filter is available for this in future!)
// 1.8.5: allow moving of csshero.js from theme root to javascript dirs
// 2.1.1: moved integration function here from skull.php
if (isset($_GET['csshero_action']) && ($_GET['csshero_action'] == 'edit_page')) {
	// 1.9.5: added filter to optionally disable this path adjustment
	$csshero = bioship_apply_filters('skeleton_adjust_css_hero_script_dir', true);
	if ($csshero) {

		// TODO: maybe prefix function name with bioship_muscle_
		if (!function_exists('bioship_csshero_script_dir')) {

		 // 2.1.1: moved add_action internally for consistency
		 add_action('wp_loaded', 'bioship_csshero_script_dir', 0);

		 function bioship_csshero_script_dir() {

			add_filter('stylesheet_directory_uri', 'bioship_csshero_script_url', 10, 3);

			// 2.1.1: added missing function_exists wrapper
			if (!function_exists('bioship_csshero_script_url')) {
			 function bioship_csshero_script_url($stylesheet_dir_url, $stylesheet, $theme_root_url) {
				if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
				global $vthemedirs;
				$csshero = bioship_file_hierarchy('url', 'csshero.js', $vthemedirs['script']);
				if ($csshero) {$stylesheet_dir_url = dirname($csshero);}
				remove_filter('stylesheet_directory_url', 'skeleton_css_hero_script_url', 10, 3);
				return $stylesheet_dir_url;
			 }
			}
		 }
		}
	}
}

// ===========
// WooCommerce
// ===========

// ------------------------------
// WooCommerce Template Directory
// ------------------------------
// Changes directory for Woocommerce templates (for both child and parent theme directories)
// intended so you could use:  /theme/theme-name/templates/woocommerce/
// instead of the default: /theme/theme-name/woocommerce/
// (as a better way of organizing 3rd party templates)
// WARNING: use one directory OR the other, it is NOT a hierarchy so you cannot use both!

// --------------------------------
// WooCommerce Template Path Filter
// --------------------------------
if (class_exists('WC_Template_Loader')) {

	add_filter('woocommerce_template_path', 'bioship_muscle_woocommerce_template_path');

	if (!function_exists('bioship_muscle_woocommerce_template_path')) {
	 function bioship_muscle_woocommerce_template_path($path) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// 1.9.5: added this filter to allow further change
		// override woocommerce/ to (filtered) templates/woocommerce/
		$newpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce/');

		global $vthemetemplatedir, $vthemestyledir;
		if (is_dir($vthemetemplatedir.$newpath) || is_dir($vthemestyledir.$newpath)) {
			// 1.9.5: only if new template directory exists do we apply other template filters
			add_filter('wc_get_template', 'bioship_muscle_woocommerce_template', 10, 5);
			add_filter('wc_get_template_part', 'bioship_muscle_woocommerce_template_part', 10, 3);
			return $newpath;
		}
		else {return $path;}
	 }
	}
}

// ---------------------------------------------
// Woocommerce Template subdirectories Templates
// ---------------------------------------------
// 2.1.1: removed unneeded function_exists check
if (!function_exists('bioship_muscle_woocommerce_template')) {
	function bioship_muscle_woocommerce_template($located, $template_name, $args, $template_path, $default_path) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// --- find the new template via file hierarchy ---
		// looking in templates/woocommerce/ then woocommerce/
		// 1.9.5: apply the template directory filter and search that only
		// 2.1.1: removed unnecessary trailing slash from default path
		$newpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce');
		$newtemplate = bioship_file_hierarchy('file', $template_name, array($newpath));

		// write debug info (kept here as useful for finding templates)
		// ob_start();
		// echo "new template: "; print_r($newtemplate); echo PHP_EOL;
		// echo "located: "; print_r($located); echo PHP_EOL;
		// echo "template_name: "; print_r($template_name); echo PHP_EOL;
		// $data = ob_get_contents(); ob_end_clean();
		// bioship_write_debug_file('woo-templates.txt', $data);

		// return the new template location if found
		if ($newtemplate) {return $newtemplate;}

		return $located;
	}
}

// --------------------------
// Woocommerce Template Parts
// --------------------------
// 2.1.1: removed unneeded function_exists check
// eg. single-product-content.php and anything retrieved by wc_get_template_part
if (!function_exists('bioship_muscle_woocommerce_template_part')) {
	function bioship_muscle_woocommerce_template_part($template, $slug, $name) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// 1.9.5: apply the template directory filter and search that only
		// 2.1.1: removed unnecessary trailing slash from default path
		$newpath = bioship_apply_filters('skeleton_woocommerce_template_directory', 'templates/woocommerce');
		// get slug-name template via file hierarchy
		$newtemplate = bioship_file_hierarchy('file', $slug.'-'.$name.'.php', array($newpath));
		// include a fallback to slug based template
		$slugtemplate = bioship_file_hierarchy('file', $slug.'.php', array($newpath));

		// write debug info (kept here as useful for finding templates)
		// ob_start();
		// echo "name template (".$name."): "; print_r($newtemplate); echo PHP_EOL;
		// echo "slug template (".$slug."): "; print_r($slugtemplate); echo PHP_EOL;
		// $data = ob_get_contents(); ob_end_clean();
		// bioship_write_debug_file('woo-template-parts.txt', $data);

		// maybe return the altered template location
		if ($newtemplate) {return $newtemplate;}
		if ($slugtemplate) {return $slugtemplate;}

		return $template;
	}
}


// =============================
// Open Graph Protocol Framework
// =============================
// ...yah down wid OGP? yeah u know me...
// Ref: http://www.itthinx.com/plugins/open-graph-protocol/

// -------------------------------------
// Set Open Graph Protocol Default Image
// -------------------------------------
// requires Open Graph Protocol plugin to be installed and active
// note: if using Jetpack see filter: jetpack_open_graph_image_default
// 1.5.0: added default image meta
if (!function_exists('bioship_muscle_open_graph_default_image')) {

 // 2.0.5: move filter inside for consistency
 add_filter('open_graph_protocol_metas', 'bioship_muscle_open_graph_default_image');

 function bioship_muscle_open_graph_default_image($metas) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemename, $vthemesettings, $vthemedirs;

	// --- allow for open graph image override filter ---
	// (see next func for in-built custom field override)
	$image = array();
	if (isset($metas['og:image:width'])) {$image[0] = $metas['og:image:width'];}
	if (isset($metas['og:image:height'])) {$image[1] = $metas['og:image:height'];}
	if (isset($metas['og:image'])) {$image[2] = $metas['og:image'];}
	$image = bioship_apply_filters('muscle_open_graph_override_image', $image);

	// --- if we now have an image and it is a different URL ---
	if (isset($image[2])) {
		if ($image[2] != $metas['og:image']) {
			// --- allow override to turn this meta off completely ---
			if ($image[2] == 'off') {return array();}

			// --- if image changed/updated, check for new width and height ---
			if (isset($image[0]) && isset($image[1])) {
				$metas['og:image:width'] = $image[0];
				$metas['og:image:height'] = $image[1];
				$metas['og:image'] = $image[2];
			} else {
				// --- otherwise, use getimagesize (slower) ---
				// 2.0.9: set missing default value
				// 2.1.1: try filepath conversion in any case as file system is faster
				$filepath = ABSPATH.parse_url($url, PHP_URL_PATH);
				if (file_exists($filepath)) {$urltofilepath = true;} else {$urltofilepath = false;}

				if ($urltofilepath || ini_get('allow_url_fopen')) {
					if ($urltofilepath) {$imagesize = getimagesize($filepath);}
					else {$imagesize = getimagesize($image[2]);}
					if ($imagesize) {
						$metas['og:image:width'] = $imagesize[0];
						$metas['og:image:height'] = $imagesize[1];
						$metas['og:image'] = $image[2];
					}
				}
			}
		} else {
			// same URL, maybe a change in size though
			// as it is an override, just do that
			$metas['og:image:width'] = $image[0];
			$metas['og:image:height'] = $image[1];
		}
	}

	// --- default (fallback) open graph image option ---
	if (!isset($metas['og:image'])) {

		// 1.9.6: removed this code as even 192 does not meet OG minimum of 200
		// maybe pick the largest size if set to precomposed apple touch icons
		// if ($vthemesettings['ogdefaultimage'] == 'appletouchicon') {
		//	$sizes = array('192','180','152','144','120','114','75','72');
		//	$found = false;
		//	foreach ($sizes as $size) {
		//		if (!$found) {
		//			$checkurl = bioship_file_hierarchy('url', 'touch-icon-'.$size.'x'.$size.'-precomposed.png', $vthemedirs['image']);
		//			if ($checkurl) {vurl = $checkurl; $found = true;}
		//		}
		//	}
		// }
		// else {
			// --- set the URL via theme settings suboption ---
			$key = $vthemesettings['ogdefaultimage'];
			if ($key == '') {$key = 'header_logo';}
			// 1.9.5: fix for uploaded default image
			// 2.0.8: added new open graph image off option
			if ($key == 'none') {$url = '';}
			elseif ($key == 'site_icon') {$url = get_site_icon_url();}
			else {$url = $vthemesettings[$key];}
		// }

		// --- allow for default open graph image filter ---
		bioship_debug("Open Graph Default Image URL", $url);
		$url = bioship_apply_filters('muscle_open_graph_default_image_url', $url);
		bioship_debug("Filtered OpenGraph URL", $url);

		if ($url != '') {
			// best to cache image size like in skin.php header logo for getimagesize
			// ...but again need to check for allow_url_fopen to do that

			// --- try to convert URL to filepath ---
			// 2.1.1: try filepath conversion in any case as file system is faster
			$filepath = ABSPATH.parse_url($url, PHP_URL_PATH);
			if (file_exists($filepath)) {$urltofilepath = true;} else {$urltofilepath = false;}

			if ($urltofilepath || ini_get('allow_url_fopen')) {
				$imagesize = get_option($vthemename.'_ogdefaultimage');
				if (strstr($imagesize,':')) {
					$imagesize = explode(':', $imagesize);
					if ($imagesize[2] != $url) {
						if ($urltofilepath) {$imagesize = getimagesize($filepath);}
						else {$imagesize = getimagesize($url);}
						if ($imagesize) {
							$imagedata = $imagesize[0].':'.$imagesize[1].':'.$url;
							// 2.0.5: remove unnecessary add_option fallback
							update_option($vthemename.'_ogdefaultimage', $imagedata);
						}
					}
				} else {
					if ($urltofilepath) {$imagesize = getimagesize($filepath);}
					else {$imagesize = getimagesize($url);}
					if ($imagesize) {
						$imagedata = $imagesize[0].':'.$imagesize[1].':'.$url;
						// 2.0.5: remove unnecessary add_option fallback
						update_option($vthemename.'_ogdefaultimage', $imagedata);
					}
				}
				// --- set image meta ---
				if ($imagesize) {
					$metas['og:image'] = $url;
					$metas['og:image:width'] = $imagesize[0];
					$metas['og:image:height'] = $imagesize[1];
				}

			} else {
				// no allow_fopen_url and filepath failed :-(
				// rely on a matching explicit width/height set via filter
				$imagesize = bioship_apply_filters('muscle_open_graph_default_image_size', array());
				if (isset($imagesize[0]) && isset($imagesize[1])) {
					$metas['og:image'] = $url;
					$metas['og:image:width'] = $imagesize[0];
					$metas['og:image:height'] = $imagesize[1];
				}
			}
		}
	}
	// 1.9.6: fix some mismatching WP to FB locales
	// ...there may be a number more of these?
	// http://www.roseindia.net/tutorials/i18n/locales-list.shtml
	// https://www.facebook.com/translations/FacebookLocales.xml
	if ($metas['og:locale'] == 'en_AU') {$metas['og:locale'] = 'en_GB';}
	if ($metas['og:locale'] == 'ja') {$metas['og:locale'] = 'ja_JP';}
	if ($metas['og:locale'] == 'iw_IL') {$metas['og:locale'] = 'he_IL';}

	bioship_debug("Open Graph Meta", $metas);
	return $metas;
 }
}

// --------------------------------------------------
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

 function bioship_muscle_open_graph_override_image_fields($image) {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.1.2: added check for singular but not-404 page
	if (is_singular() && !is_404()) {

		// --- override existing open graph image meta with post custom field meta ---
		// (better to set width and height field values but not totally necessary)
		global $post; $postid = $post->ID;
		$ogimage[0] = get_post_meta($postid, 'opengraphimagewidth', true);
		$ogimage[1] = get_post_meta($postid, 'opengraphimageheight', true);
		$ogimage[2] = get_post_meta($postid, 'opengraphimageurl', true);

		// --- allow image removal for this page ---
		// (by setting opengraphimageurl value to 'off'
		if ($ogimage[2] == 'off') {return array();}

		// --- require the URL to be there ---
		if ($ogimage[2] != '') {return $ogimage;}

	}
	// else {
		// TODO: handle archive page overrides via archive CPT ?
	// }

	return $image;
 }
}

// ===========
// Hybrid Hook
// ===========
// (does not require Hybrid Core to be loaded)
// (note: Hybrid Hook Widgets is available also)
// 2.0.1: filter Hybrid Hook loading here
if (isset($vthemesettings['hybridhook'])) {$loadhybridhook = $vthemesettings['hybridhook'];} else {$loadhybridhook = false;}
$loadhybridhook = bioship_apply_filters('muscle_load_hybrid_hook', $loadhybridhook);

if ($loadhybridhook == '1') {

	// --- check Hybrid Hook directory ---
	// 1.8.0: changed hybrid hook location to /includes/ subfolder
	// 2.1.1: check alternative includes directories
	$hybridhookdirs = array();
	if (count($vthemedirs['includes']) > 0) {
		foreach ($vthemedirs['includes'] as $dir) {
			if (is_dir($dir.DIRSEP.'hybrid-hook')) {$hybridhookdirs[] = $dir.DIRSEP.'hybrid-hook';}
		}
	}

	// --- load Hybrid Hook ---
	$hybridhook = bioship_file_hierarchy('file', 'hybrid-hook.php', $hybridhookdirs);
	if ($hybridhook) {
		include($hybridhook);
		if (THEMEDEBUG) {echo "<!-- Hybrid Hook Loaded -->".PHP_EOL;}

		// --- load setup now ---
		// (as we have missed the plugins_loaded hook)
		hybrid_hook_setup();

		// --- disallow PHP execution ---
		// 1.8.5: added this filter (as e-v-a-l commented out for Theme Check)
		// (HTML / Shortcode / Widget methods are better anyway)
		add_filter('hybrid_hook_allow_php', 'bioship_muscle_disallow_hook_php', 5);
		if (!function_exists('bioship_muscle_disallow_hook_php')) {
			function bioship_muscle_disallow_hook_php($v) {
				if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
				return false;
			}
		}

		// --- load theme layout hooks ---
		add_filter('hybrid_hooks', 'bioship_muscle_hybrid_get_hooks');
		if (!function_exists('bioship_muscle_hybrid_get_hooks')) {
		 function bioship_muscle_hybrid_get_hooks() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

			// 1.9.0: hooks now loaded by default in functions.php
			global $vthemehooks;

			if (THEMEDEBUG) {echo "<!-- Hybrid Hooks: ".esc_attr(print_r($vthemehooks['hybrid'],true))." -->";}

			// 1.9.0: handle admin metabox defaults
			if (is_admin()) {

				// 1.8.5: default the hybrid hook metaboxes to closed
				// ref: https://surniaulula.com/2013/05/29/collapse-close-wordpress-metaboxes/
				$userid = get_current_user_id();
				$optionkey = 'closedpostboxes_'.'appearance_page_'.'hybrid-hook-settings';

				if (isset($_REQUEST['metaboxes']) && ($_REQUEST['metaboxes'] == 'reset')) {
					delete_user_option($optionkey, $userid);
				} else {$closedboxes = get_user_option($optionkey, $userid);}

				// create an empty array if get_user_option() had nothing to return
				if (!is_array($closedboxes)) {
					$closedboxes = array();
					// 2.1.1: fix to use vthemehooks subkey
					foreach ($vthemehooks['hybrid'] as $hook) {$closedboxes[] = 'hybrid-hook-'.$hook;}
					update_user_option($userid, $optionkey, $closedboxes, true);
				}

				if (THEMEDEBUG) {echo "<!-- Closed Boxes: ".esc_attr(print_r($closedboxes,true))." -->";}
			}

			return $vthemehooks['hybrid'];
		 }
		}

		// --- filter Hybrid Hook theme prefix ---
		// hook into the theme filter (for modified Hybrid Hook plugin)
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


// ==========
// Foundation
// ==========
// ref: http://foundation.zurb.com/docs
if (!function_exists('bioship_muscle_load_foundation')) {

 add_action('wp_enqueue_scripts', 'bioship_muscle_load_foundation');

 function bioship_muscle_load_foundation() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemevars, $vcsscachebust, $vjscachebust;

	// --- check load ---
	// 2.0.9: filter Foundation loading internally
	if (isset($vthemesettings['loadfoundation'])) {$load = $vthemesettings['loadfoundation'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_foundation', $load);
	if (!$load || ($load == 'off')) {return;}

	// --- check Foundation version ---
	// 1.8.0: check Foundation 5 or 6 directory to use for loading
	// TODO: check for alternative includes directory
	if (isset($vthemesettings['foundationversion'])) {$foundation = 'includes/'.$vthemesettings['foundationversion'];}
	else {$foundation = 'includes/foundation5';} // backwards compatibility default

	// --- force auto-load of modernizr and fastclick for Foundation 5 ---
	if (strstr($foundation, '5')) {
		if (!has_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr')) {add_action('wp_enqueue_scripts', 'bioship_muscle_load_modernizr');}
		if (!has_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick')) {add_action('wp_enqueue_scripts', 'bioship_muscle_load_fastclick');}
		$deps = array('jquery', 'fastclick', 'modernizr');
	} else {$deps = array('jquery');}

	// Foundation Stylesheet
	// ---------------------
	// http://foundation.zurb.com/docs/css.html
	if ($vthemesettings['foundationcss']) {
		if ($vthemesettings['loadfoundation'] == 'essentials') {
			$stylesheet = bioship_file_hierarchy('both', 'foundation.essentials.min.css', array($foundation.'/css','css'));
		} else {
			$stylesheet = bioship_file_hierarchy('both', 'foundation.min.css', array($foundation.'/css','css'));
		}
		if (is_array($stylesheet)) {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$cachebust = date('ymdHi', filemtime($stylesheet['file']));
			} else {$cachebust = $vcsscachebust;}
			wp_register_style('foundation', $stylesheet['url'], array(), $cachebust);
			wp_enqueue_style('foundation');
		}
	}

	// Full or Partial Foundation Javascript
	// -------------------------------------
	// http://foundation.zurb.com/docs/javascript.html

	// --- get Foundation script ---
	// TODO: add check for alternative script directories
	if ($vthemesettings['loadfoundation'] == 'full') {
		$script = bioship_file_hierarchy('both', 'foundation.min.js', array($foundation.'/js', 'javascripts'));
	}
	if ($vthemesettings['loadfoundation'] == 'essentials') {
		$script = bioship_file_hierarchy('both', 'foundation.essentials.js', array($foundation.'/js', 'javascripts'));
	} elseif ($vthemesettings['loadfoundation'] == 'selective') {
		$script = bioship_file_hierarchy('both', 'foundation.selected.js', array('javascripts', $foundation.'/js'));
		// 1.8.0: note, selective javascript is currently only working for Foundation 5, so just in case, fallback to min.js
		if (!is_array($script)) {$script = bioship_file_hierarchy('both', 'foundation.min.js', array('javascripts', $foundation.'/js'));}
	}

	// --- enqueue Foundation script ---
	if (is_array($script)) {
		// 2.1.1: fix to cachebusting conditions
		if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
			$vjscachebust = date('ymdHi', filemtime($script['file']));
		} else {$cachebust = $vjscachebust;}
		wp_enqueue_script('foundation', $script['url'], $deps, $cachebust, true);

		// --- initialize via script variable ---
		// 2.0.9: use script load variable instead of input
		$vthemevars[] = "var loadfoundation = 'yes'; ";}
	}
 }
}

// ==============
// Theme My Login
// ==============
// 2.0.1: filter Theme My Login template loading
// 2.0.9: check loading filter internally
if (!function_exists('muscle_load_theme_my_login_filters')) {

 add_action('plugins_loaded', 'muscle_load_theme_my_login_filters');

 function muscle_load_theme_my_login_filters() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemesettings;

 	// --- check load ---
	if (isset($vthemesettings['tmltemplates'])) {$load = $vthemesettings['tmltemplates'];} else {$load = false;}
	$load = bioship_apply_filters('muscle_load_tml_templates', $load);
	if (!$load || ($load != '1')) {return;}

	// --- add Theme My Login filters ---
	// 2.0.9: add Theme My Login filters here
	add_filter('tml_template_paths', 'bioship_muscle_tml_template_paths');
	add_filter('login_button_url', 'bioship_muscle_login_button_url');
	add_filter('register_button_url', 'bioship_muscle_register_button_url');
 	add_filter('profile_button_url', 'bioship_muscle_profile_button_url');
	add_filter('register_form_image', 'bioship_muscle_register_form_image');
	add_filter('login_form_image', 'bioship_muscle_login_form_image');
 }
}

// -------------------------------
// TML Improved Template Hierarchy
// -------------------------------
if (!function_exists('muscle_tml_template_paths')) {
 function bioship_muscle_tml_template_paths($paths) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.5: use existing globals
	global $vthemestyledir, $vthemetemplatedir;
	$templatepaths = array(
		$vthemestyledir.'templates/theme-my-login',
		$vthemestyledir.'theme-my-login',
		$vthemestyledir,
		$vthemetemplatedir.'templates/theme-my-login',
		$vthemetemplatedir.'theme-my-login',
		$vthemetemplatedir,
		WP_PLUGIN_DIR.'/theme-my-login/templates'
	);
	bioship_debug("New ThemeMyLogin Paths", $newpaths);
	return $templatepaths;
 }
}

// ---------------------------
// TML Login Button URL Filter
// ---------------------------
if (!function_exists('bioship_muscle_login_button_url')) {
 function bioship_muscle_login_button_url($buttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['loginbuttonurl'] != '') {$buttonurl = $vthemesettings['loginbuttonurl'];}
	return $buttonurl;
 }
}

// ------------------------------
// TML Register Button URL Filter
// ------------------------------
if (!function_exists('bioship_muscle_register_button_url')) {
 function bioship_muscle_register_button_url($buttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['registerbuttonurl'] != '') {$buttonurl = $vthemesettings['registerbuttonurl'];}
	return $buttonurl;
 }
}

// -----------------------------
// TML Profile Button URL Filter
// -----------------------------
if (!function_exists('bioship_muscle_profile_button_url')) {
 function bioship_muscle_profile_button_url($buttonurl) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemesettings;
	if ($vthemesettings['profilebuttonurl'] != '') {$buttonurl = $vthemesettings['profilebuttonurl'];}
	return $buttonurl;
 }
}

// ----------------------------
// TML Register Form Logo Image
// ----------------------------
if (!function_exists('bioship_muscle_register_form_image')) {
 function bioship_muscle_register_form_image($image) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;
	if ($vthemesettings['registerformimage'] == '1') {
		if ($vthemesettings['loginlogo'] == 'custom') {$image = $vthemesettings['header_logo'];}
		if ($vthemesettings['loginlogo'] == 'upload') {$image = $vthemesettings['loginlogourl'];}
	}
	return $image;
 }
}

// -------------------------
// TML Login Form Logo Image
// -------------------------
// 2.1.1: added missing image argument definition
if (!function_exists('bioship_muscle_login_form_image')) {
 function bioship_muscle_login_form_image($image) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings;

	if ($vthemesettings['loginformimage'] == '1') {
		if ($vthemesettings['loginlogo'] == 'custom') {$image = $vthemesettings['header_logo'];}
		if ($vthemesettings['loginlogo'] == 'upload') {$image = $vthemesettings['loginlogourl'];}
	}
	return $image;
 }
}


// ----------------------
// Theme Switch Admin Fix
// ----------------------
// TODO: retest this Theme Switching functionality

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
if (!function_exists('bioship_muscle_theme_switch_admin_fix')) {

 // 2.1.1: move add_action internally for consistency
 add_action('init', 'bioship_muscle_theme_switch_admin_fix');

 function bioship_muscle_theme_switch_admin_fix() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check for a valid active plugin ---
	$activeplugins = maybe_unserialize(get_option('active_plugins'));
	if (!is_array($activeplugins)) {return;}
	$multiplethemes = $themetestdrive = false;
	if (in_array('jonradio-multiple-themes/jonradio-multiple-themes.php', $activeplugins)) {
		// 2.1.1: add extra check to ensure plugin is actually loaded
		if (function_exists('jr_mt_template')) {$multiplethemes = true;}
	}
	if (in_array('theme-test-drive/themedrive.php', $activeplugins)) {
		// 2.1.1: add extra check to ensure plugin is actually loaded
		if (function_exists('themedrive_get_template')) {$themetestdrive = true;}
	}
	if (!$multiplethemes && !$themetestdrive) {return;} // bug out

	// --- multiple themes option: 'site', 'sticky' or 'both' ---
	if (defined('MT_METHOD')) {$method = MT_METHOD;} else {$method = 'site';}
	$parameter = 'theme'; // multiple theme switch querystring parameter name

	// --- user data save settings ---
	$datamethod = 'both'; // how to save user data: 'cookie', 'usermeta' or 'both'
	$datakey = 'theme_switch_data'; // cookie and user meta key name
	$expires = 24*60*60; // length of time for cookies and transients

	// --- maybe include pluggable.php for accessing user ---
	// 2.1.1: fix to incorrect variable name (userdata)
	if ( ($datamethod != 'cookie') && !function_exists('is_user_logged_in')) {
		require(ABSPATH.WPINC.'/pluggable.php');
	}

	// --- maybe reset cookie and URL data by user request ---
	if (isset($_GET['resetthemes']) && ($_GET['resetthemes'] == '1')) {
		if ($debug) {echo "<!-- THEME SWITCH DATA RESET -->";}
		if ($themetestdrive) {setCookie($themecookie, '', -300);}
		delete_option('theme_switch_request_urls'); return;
	}

	// --- maybe set debug switch ---
	$debug = false;
	if (isset($_GET['debug']) && ($_GET['debug'] == '1')) {$debug = true;}
	elseif (defined('THEMEDEBUG')) {$debug = THEMEDEBUG;}

	// --- improve theme test drive to use options filters like multiple themes ---
	// (theme test drive by default only filters via get_stylesheet and get_template)
	if ($themetestdrive) {
		$parameter = 'theme';
		remove_filter('template', 'themedrive_get_template'); remove_filter('stylesheet', 'themedrive_get_stylesheet');
		add_filter('pre_option_stylesheet', 'themedrive_get_stylesheet'); add_filter('pre_option_template', 'themedrive_get_template');
	}

	// --- maybe load stored alternative theme for AJAX/admin calls ---
	if (is_admin()) {

		// --- let WordPress handle customize previews ---
		if (is_customize_preview()) {return;}

		// --- get pagenow to check for admin-post.php as well ---
		global $pagenow;

		if ( (defined('DOING_AJAX') && DOING_AJAX) || ($pagenow == 'admin-post.php') ) {

			// set the referer path for URL matching
			$referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

			// set some globals for the AJAX theme options
			global $ajax_stylesheet, $ajax_template;

			// --- check for temporary Theme Test Drive cookie data ---
			if ($themetestdrive || ($multiplethemes && ($method != 'site'))) {
				if ($datamethod != 'usermeta') {
					if (isset($_COOKIE[$datakey]) && ($_COOKIE[$datakey] != '')) {
						$cookiedata = explode(',', $_COOKIE[$datakey]);
						// attempt to match referer data with stored transient request
						foreach ($cookiedata as $transientkey) {
							$transientdata = get_transient($transientkey);
							if ($transientdata) {
								$data = explode(':', $transientdata);
								if ($data[0] == $referer) {
									$ajax_stylesheet = $data[1]; $ajax_template = $data[2];
									$transientdebug = $transientdata; $matchedurlpath = true;
								}
							}
						}
					}
					if (($datamethod != 'cookie') && is_user_logged_in()) {
						// 2.0.1: allow for fallback for older installs
						$current_user = bioship_get_current_user();
						$usermetadata = get_user_meta($current_user->ID, $datakey, true);
						if (is_array($usermetadata)) {
							// --- attempt to match referer data with stored transient request ---
							foreach ($usermetadata as $transientkey) {
								$transientdata = get_transient($transientkey);
								if ($transientdata) {
									$data = explode(':', $transientdata);
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
			elseif ($multiplethemes && ($method != 'sticky')) {
				// --- check the request URL list to handle sitewide cases ---
				if (!$matchedurlpath) { // but not if we already have a match
					$requesturls = get_option('theme_switch_request_urls');
					if (is_array($requesturls)) {
						if (is_array($requesturls) && array_key_exists($referer, $requesturls)) {
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
				add_filter('pre_option_stylesheet', 'bioship_muscle_admin_ajax_stylesheet');
				add_filter('pre_option_template', 'bioship_muscle_admin_ajax_template');

				// 2.1.1: added function_exists wrappers for consistency
				if (!function_exists('bioship_muscle_admin_ajax_stylesheet')) {
				 function bioship_muscle_admin_ajax_stylesheet() {global $ajax_stylesheet; return $ajax_stylesheet;}
			  	}
			  	if (!function_exists('bioship_muscle_admin_ajax_template')) {
				 function bioship_muscle_admin_ajax_template() {global $ajax_template; return $ajax_template;}
				}
			}

			// --- maybe output debug info for AJAX/admin test frame ---
			if ($debug) {
				echo "<!-- COOKIE DATA: ".esc_attr($_COOKIE[$themecookie])." -->";
				echo "<!-- TRANSIENT DATA: ".esc_attr($transientdebug)." -->";
				echo "<!-- REFERER: ".esc_url($referer)." -->";
				echo "<!-- STORED URLS: ".esc_attr(print_r($requesturls,true))." -->";
				if ($matchedurlpath) {echo "<!-- URL MATCH FOUND -->";} else {echo "<!-- NO URL MATCH FOUND -->";}
				echo "<!-- AJAX Stylesheet: ".esc_attr(get_option('stylesheet'))." -->";
				echo "<!-- AJAX Template: ".esc_attr(get_option('template'))." -->";
			}

			return; // done for admin requests so bug out here
		}
	}

	// --- store public request URLs where an alternate theme is active ---
	// (note: multiple themes does not load in admin, but theme test drive does)
	if ($themetestdrive || (!is_admin() && $multiplethemes)) {

		// --- get current theme (possibly overriden) setting ---
		$themestylesheet = get_option('stylesheet'); $themetemplate = get_option('template');

		// --- remove filters, get default theme setting, re-add filters ---
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

		// --- set/get request URL values (URL path only) ---
		$requesturl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$requesturls = get_option('theme_switch_request_urls');

		// --- store the request data ---
		if ($themetestdrive || ($multiplethemes && ($method != 'site'))) {
			if (isset($_REQUEST[$parameter]) && ($_REQUEST[$parameter] != '') ) {
				if ($datamethod != 'usermeta') {
					 // --- check existing cookie data ---
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
				if (($datamethod != 'cookie') && is_user_logged_in()) {
					// --- check existing usermeta data ---
					// 2.0.1: allow for fallback for older installs
					// 2.0.7: use new prefixed current user function
					$current_user = bioship_get_current_user();
					$usermetadata = get_user_meta($current_user->ID, $datakey, true);
					if (is_array($usermetadata)) {
						$existingmatch = false;
						$i = 0;
						// --- remove expired transient IDs from usermeta ---
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

				// --- set the transient with matching cookie/usermeta data ---
				if (!$existingmatch) { // avoid duplicates
					 // --- set the new transient ---
					 $transientkey = $datakey.'_'.uniqid();
					 $transientdata = $transientdebug = $requesturl.':'.$themestylesheet.':'.$themetemplate;
					 set_transient($transientkey, $transientdata, $expires);

					 // --- add transient to cookie for matching later ---
					 if ($datamethod != 'usermeta') {
						 $cookiedata[] = $transientkey; $cookiedatastring = implode(',', $cookiedata);
						 setCookie($themecookie, $cookiedatastring, time()+$expires);
					 }
					 // --- add transient to usermeta for matching later ---
					 if ($datamethod != 'cookie') {
					 	$usermetadata[] = $transientkey; update_user_meta($current_user->ID, $datakey, $usermetadata);
					 }
				}

				// --- maybe output debug info ---
				if ($debug) {
					echo "<!-- COOKIE DATA: "; print_r($cookiedata); echo " -->";
					if ($datamethod != 'cookie') {echo "<!-- USERMETA DATA: "; print_r($usermetadata); echo " -->";}
					echo "<!-- TRANSIENT DATA: ".$transientdebug." -->";
				}
			}

		} elseif ($multiplethemes && ($method != 'sticky')) {
			// --- save/remove the requested URL path in the list ---
			if ( ($stylesheet == $themestylesheet) && ($template == $themetemplate) ) {
				// maybe remove this request from the stored URL list
				if (is_array($requesturls) && array_key_exists($requesturl, $requesturls)) {
					unset($requesturls[$requesturl]);
					if (count($requesturls) === 0) {delete_option('theme_switch_request_urls');}
					else {update_option('theme_switch_request_urls', $requesturls);}
				}
			} else {
				// --- add this request URL to the stored list ---
				$requesturls[$requesturl]['stylesheet'] = $themestylesheet;
				$requesturls[$requesturl]['template'] = $themetemplate;
				update_option('theme_switch_request_urls', $requesturls);
			}

			// --- maybe output debug info ---
			if (!is_admin() && $debug) {
				echo "<!-- REQUEST URL: ".esc_url($requesturl)." -->";
				echo "<!-- STORED URLS: ".esc_attr(print_r($requesturls))." -->";
			}
		}

		// --- maybe output hidden ajax debugging frames ---
		if (!is_admin() && $debug) {
			echo "<iframe src='".esc_url(admin_url('admin-ajax.php'))."?debug=1' style='display:none;'></iframe>";
			echo "<iframe src='".esc_url(admin_url('admin-post.php'))."?debug=1' style='display:none;'></iframe>";
		}
	}
 }
}
