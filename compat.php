<?php

// -----------------------------
// Theme Backwards Compatibility
// -----------------------------

// 1.8.5: added this file so name changed functions/filters should end up here
// to keep our pluggable functions and filters overrideable...
// name changes shall be kept to a minimum! (see the mess it makes..!)
// ...on the other hand see how few are actually here after all these versions :-)
// 2.0.2: started converting to bioship_ prefix...


// =======
// Actions
// =======

// Convert Old Action/Filter Prefixes
// ----------------------------------
add_action('after_setup_theme', 'bioship_compat_actions', 99);
add_action('wp', 'bioship_compat_actions', 99);

function bioship_compat_actions() {

	global $wp_filter, $vthemehooks;

	// list of old skeleton_ prefixed action hooks
	$vactions = array(
		'skeleton_before_container','skeleton_container_open','skeleton_before_header',
		'skeleton_header','skeleton_before_header_widgets','skeleton_after_header_widgets',
		'skeleton_after_header','skeleton_before_navbar','skeleton_navbar','skeleton_after_navbar',
		'skeleton_before_sidebar','skeleton_after_sidebar','skeleton_before_subsidebar',
		'skeleton_after_subsidebar','skeleton_before_content','skeleton_front_page_top',
		'skeleton_home_page_top','skeleton_before_loop','skeleton_before_archive',
		'skeleton_before_category','skeleton_before_taxonomy','skeleton_before_tags',
		'skeleton_before_author','skeleton_before_date','skeleton_before_entry',
		'skeleton_attachment_media_handler','skeleton_entry_header','skeleton_thumbnail',
		'skeleton_before_thumbnail','skeleton_after_thumbnail','skeleton_before_excerpt',
		'skeleton_the_excerpt','skeleton_after_excerpt','skeleton_entry_footer',
		'skeleton_before_singular','skeleton_author_bio_top','skeleton_before_author_bio',
		'skeleton_after_author_bio','skeleton_before_the_content','skeleton_after_the_content',
		'skeleton_author_bio_bottom','skeleton_after_singular','skeleton_before_comments',
		'skeleton_after_comments','skeleton_after_entry','skeleton_page_navi','skeleton_after_date',
		'skeleton_after_author','skeleton_after_tags','skeleton_after_taxonomy',
		'skeleton_after_category','skeleton_after_archive','skeleton_after_loop',
		'skeleton_after_content','skeleton_before_footer','skeleton_footer',
		'skeleton_before_footer_widgets','skeleton_after_footer_widgets','skeleton_after_footer',
		'skeleton_container_close','skeleton_after_container'
	);

	foreach ($vactions as $vaction) {
		if (isset($wp_filter[$vaction])) {
			$vprefix = 'skeleton_';
			if (strpos($vaction,$vprefix) === 0) {
				$vnewaction = 'bioship_'.substr($vaction,strlen($vprefix),strlen($vaction));
				$wp_filter[$vnewaction] = $wp_filter[$vaction]; unset($wp_filter[$vaction]);
			}
		}
	}

	return;

	// TODO: filter names?
	$vfilters = array(

	);

	foreach ($vactions as $vaction) {
		if (isset($wp_filter[$vaction])) {
			$vprefixes = array('skeleton_','muscle_','admin_');
			foreach ($vprefixes as $vprefix) {
				if (strpos($vaction,$vprefix) === 0) {
					if (THEMEDEBUG) {echo "<!-- Old Filter (".$vaction."): "; print_r($wp_filter[$vaction]); echo " -->";}
					$vnewaction = 'bioship_'.substr($vaction,strlen($vprefix),strlen($vaction));
					$wp_filter[$vnewaction] = $wp_filter[$vaction]; unset($wp_filter[$vaction]);
					if (THEMEDEBUG) {echo "<!-- New Filter (".$vnewaction."): "; print_r($wp_filter[$vnewaction]); echo " -->";}
				}
			}
		}
	}

}


// =========
// Functions
// =========

// Skin
// ----
if (function_exists('skin_enqueue_styles')) {function bioship_skin_enqueue_styles() {skin_enqueue_styles();} }
if (function_exists('skin_enqueue_admin_styles')) {function bioship_skin_enqueue_admin_styles() {skin_enqueue_admin_styles();} }
if (function_exists('skin_typography_loader')) {function bioship_skin_typography_loader() {skin_typography_loader();} }
if (function_exists('skin_dynamic_css')) {function bioship_skin_dynamic_css() {skin_dynamic_css();} }
if (function_exists('skin_dynamic_admin_css')) {function bioship_skin_dynamic_admin_css($vflag) {skin_dynamic_admin_css($vflag);} }
if (function_exists('skin_dynamic_css_inline')) {function bioship_skin_dynamic_css_inline() {skin_dynamic_css_inline();} }
if (function_exists('skin_dynamic_admin_css_inline')) {function bioship_skin_dynamic_admin_css_inline() {skin_dynamic_admin_css_inline();} }
if (function_exists('skin_enqueue_admin_styles')) {function bioship_skin_enqueue_admin_styles() {skin_enqueue_admin_styles();} }
if (function_exists('skin_css_replace_values')) {function bioship_skin_css_replace_values($css) {skin_css_replace_values($css);} }
if (function_exists('skin_get_image_size')) {function bioship_skin_get_image_size($a,$b) {skin_get_image_size($a,$b);} }





// 1.9.8: removed this one as spitting a weird constant warning?
// if (function_exists(muscle_get_overrides)) {function muscle_get_display_overrides() {return muscle_get_overrides();} }
// else {function muscle_get_overrides() {return muscle_get_display_overrides();} }

if (function_exists('muscle_adminbar_theme_options')) {function admin_adminbar_theme_options() {muscle_adminbar_theme_options();} }
else {function muscle_adminbar_theme_options() {admin_adminbar_theme_options();} }
if (function_exists('muscle_adminbar_replace_howdy')) {function admin_adminbar_replace_howdy() {muscle_adminbar_replace_howdy();} }
else {function muscle_adminbar_replace_howdy() {admin_adminbar_replace_howdy();} }

if (function_exists('muscle_remove_admin_footer')) {function admin_remove_admin_footer() {muscle_remove_admin_footer();} }
else {function muscle_remove_admin_footer() {admin_remove_admin_footer();} }


// Function/Filters
// ----------------

add_filter('skeleton_sidebar_position','bioship_compat_sidebar_position');
function bioship_compat_sidebar_position($vclasses) {return skeleton_apply_filters('skeleton_sidebar_position_class',$vclasses);}
add_filter('skeleton_subsidebar_position','bioship_compat_subsidebar_position');
function bioship_compat_subsidebar_position($vclasses) {return skeleton_apply_filters('skeleton_subsidebar_position_class',$vclasses);}


// Filters
// -------

add_filter('options_theme_options','bioship_compat_theme_options');
function bioship_compat_theme_options($voptions) {return skeleton_apply_filters('options_themeoptions',$voptions);}

add_filter('admin_adminbar_menu_icon','bioship_compat_adminbar_menu_icon');
function bioship_compat_adminbar_menu_icon($vicon) {return skeleton_apply_filters('options_adminbar_menu_icon',$vicon);}

add_filter('admin_adminbar_theme_options_title','bioship_compat_adminbar_theme_options_title');
function bioship_compat_adminbar_theme_options_title($vtitle) {return skeleton_apply_filters('muscle_adminbar_theme_options_title',$vtitle);}

add_filter('admin_adminbar_theme_options_icon','bioship_compat_adminbar_theme_options_icon');
function bioship_compat_adminbar_theme_options_icon($vurl) {return skeleton_apply_filters('muscle_adminbar_theme_options_icon',$vurl);}

add_filter('admin_adminbar_howdy_title','bioship_compat_adminbar_howdy_title');
function bioship_compat_adminbar_howdy_title($vtitle) {return skeleton_apply_filters('muscle_adminbar_howdy_title',$vtitle);}

add_filter('admin_adminbar_remove_items','bioship_compat_adminbar_remove_items');
function bioship_compat_adminbar_remove_items($vitems) {return skeleton_apply_filters('muscle_adminbar_remove_items',$vitems);}

add_filter('admin_admin_footer_text','bioship_compat_admin_footer_text');
function bioship_compat_admin_footer_text($vtext) {return skeleton_apply_filters('muscle_admin_footer_text',$vtext);}

add_filter('skeleton_generator_meta','bioship_compat_generator_meta');
function bioship_compat_generator_meta($vtext) {return skeleton_apply_filters('muscle_generator_meta',$vtext);}


// Pre WP 3.4 Compatibilty Fix
// ---------------------------
global $wp_version;
if (!function_exists('wp_get_theme')) {
	if (version_compare($wp_version,'3.4','<')) {
		function wp_get_theme($vtheme) {
			$vthemedir = get_stylesheet_directory($vtheme);
			$getthemedata = 'get_'.'theme_'.'data'; // to pass Theme Check
			if (!function_exists($getthemedata)) {include_once(ABSPATH.WPINC.'/theme.php');}
			$vtheme = $getthemedata($vthemedir);
			return $vtheme;
		}
	}
}

?>