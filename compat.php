<?php

// -----------------------------
// Theme Backwards Compatibility
// -----------------------------

// 1.8.5: name changed functions/filters should end up here

// to keep our pluggable functions and filters overrideable...
// name changes shall be kept to a minimum! see the mess it makes..!
// ...on the other hand see how few are actually here after all these versions :-)


// Functions
// ---------

// 1.9.8: removed this as spitting weird constant error
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

add_filter('skeleton_sidebar_position','themecompat_sidebar_position');
function themecompat_sidebar_position($vclasses) {return apply_filters('skeleton_sidebar_position_class',$vclasses);}
add_filter('skeleton_subsidebar_position','themecompat_subsidebar_position');
function themecompat_subsidebar_position($vclasses) {return apply_filters('skeleton_subsidebar_position_class',$vclasses);}


// Filters
// -------

add_filter('options_theme_options','themecompat_theme_options');
function themecompat_theme_options($voptions) {return apply_filters('options_themeoptions',$voptions);}

add_filter('admin_adminbar_menu_icon','themecompat_adminbar_menu_icon');
function themecompat_adminbar_menu_icon($vicon) {return apply_filters('options_adminbar_menu_icon',$vicon);}

add_filter('admin_adminbar_theme_options_title','themecompat_adminbar_theme_options_title');
function themecompat_adminbar_theme_options_title($vtitle) {return apply_filters('muscle_adminbar_theme_options_title',$vtitle);}

add_filter('admin_adminbar_theme_options_icon','themecompat_adminbar_theme_options_icon');
function themecompat_adminbar_theme_options_icon($vurl) {return apply_filters('muscle_adminbar_theme_options_icon',$vurl);}

add_filter('admin_adminbar_howdy_title','themecompat_adminbar_howdy_title');
function themecompat_adminbar_howdy_title($vtitle) {return apply_filters('muscle_adminbar_howdy_title',$vtitle);}

add_filter('admin_adminbar_remove_items','themecompat_adminbar_remove_items');
function themecompat_adminbar_remove_items($vitems) {return apply_filters('muscle_adminbar_remove_items',$vitems);}

add_filter('admin_admin_footer_text','themecompat_admin_footer_text');
function themecompat_admin_footer_text($vtext) {return apply_filters('muscle_admin_footer_text',$vtext);}

add_filter('skeleton_generator_meta','themecompat_generator_meta');
function themecompat_generator_meta($vtext) {return apply_filters('muscle_generator_meta',$vtext);}


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