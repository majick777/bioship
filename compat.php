<?php

// ===========================
// ===== BioShip Compat ======
// = Backwards Compatibility =
// ===========================

// --- no direct load ---
if (!defined('ABSPATH')) {exit;}

// ----------------------------
// === compat.php Structure ===
// ----------------------------
// - Pre WP 3.4 Compatibility Fix
// === Actions ===
// - Set Old Skeleton Hooks
// - Remove Matching Old Actions
// - Convert Old Action/Filter Prefixes
// === Functions ===
// - Old Function Wrappers
// ----------------------------

// Development TODOs
// -----------------
// - back compat for skeleton_header_html_extras, skeleton_footer_html_extras

// 1.8.5: added this file so name changed functions/filters should end up here
// to keep pluggable functions and filters still overrideable and working later...
// 2.0.1: started converting everything to bioship_ prefix...
// 2.0.2: converted all other functions to bioship_ prefix
// 2.0.5: check and remove matching bioship removed skeleton actions


// ----------------------------
// Pre WP 3.4 Compatibility Fix
// ----------------------------
global $wp_version;
if (!function_exists('wp_get_theme') && version_compare($wp_version, '3.4', '<')) {
	function wp_get_theme($theme) {
		$themedir = get_stylesheet_directory($theme);
		$getthemedata = 'get_'.'theme_'.'data'; // just to pass Theme Check warning
		if (!function_exists($getthemedata)) {include_once(ABSPATH.WPINC.'/theme.php');}
		$theme = $getthemedata($themedir);
		return $theme;
	}
}


// ---------------
// === Actions ===
// ---------------

// ----------------------
// Set Old Skeleton Hooks
// ----------------------
$s = array();
$s['skeleton_before_container'] = array();
$s['skeleton_container_open']['skeleton_main_wrapper_open'] = 5;
$s['skeleton_before_header']['skeleton_top_banner'] = 2;
// $s['skeleton_before_header']['* Content Sidebar *'] = 5;
$s['skeleton_before_header']['skeleton_secondary_menu'] = 8;
$s['skeleton_header']['skeleton_header_open'] = 0;
$s['skeleton_header']['skeleton_header_nav'] = 2;
$s['skeleton_header']['skeleton_header_logo'] = 4;
$s['skeleton_header']['skeleton_header_widgets'] = 6;
$s['skeleton_before_header_widgets'] = array();
$s['skeleton_after_header_widgets'] = array();
$s['skeleton_header']['skeleton_header_extras'] = 8;
$s['skeleton_header']['skeleton_header_close'] = 10;
$s['skeleton_after_header']['skeleton_header_banner'] = 5;
$s['skeleton_before_navbar'] = array();
$s['skeleton_navbar']['skeleton_main_menu_open'] = 0;
$s['skeleton_navbar']['skeleton_sidebar_mobile_button'] = 2;
$s['skeleton_navbar']['skeleton_main_menu_mobile_button'] = 4;
$s['skeleton_navbar']['skeleton_subsidebar_mobile_button'] = 6;
$s['skeleton_navbar']['skeleton_main_menu'] = 8;
$s['skeleton_navbar']['skeleton_main_menu_close'] = 10;
$s['skeleton_after_navbar']['skeleton_clear_div'] = 0;
$s['skeleton_after_navbar']['skeleton_navbar_banner'] = 4;
$s['skeleton_after_navbar']['skeleton_clear_div'] = 8;
$s['skeleton_before_sidebar']['skeleton_sidebar_open'] = 5;
$s['skeleton_after_sidebar']['skeleton_sidebar_close'] = 5;
$s['skeleton_before_subsidebar']['skeleton_subsidebar_open'] = 5;
$s['skeleton_after_subsidebar']['skeleton_subsidebar_close'] = 5;
// $s['skeleton_before_content']['* Content Sidebar *'] = 5;
$s['skeleton_before_content']['skeleton_content_open'] = 10;
$s['skeleton_front_page_top']['skeleton_front_page_content'] = 5;
$s['skeleton_home_page_top']['skeleton_home_page_content'] = 5;
$s['skeleton_before_loop']['skeleton_breadcrumbs'] = 5;
$s['skeleton_before_archive'] = array();
$s['skeleton_before_author']['skeleton_author_bio_top'] = 5;
$s['skeleton_before_category'] = array();
$s['skeleton_before_taxonomy'] = array();
$s['skeleton_before_tags'] = array();
$s['skeleton_before_author'] = array();
$s['skeleton_before_date'] = array();
$s['skeleton_before_entry'] = array();
$s['skeleton_attachment_media_handler']['skeleton_media_handler'] = 5;
$s['skeleton_entry_header']['skeleton_entry_header_open'] = 0;
$s['skeleton_entry_header']['skeleton_entry_header_title'] = 2;
$s['skeleton_entry_header']['skeleton_entry_header_subtitle'] = 4;
$s['skeleton_entry_header']['skeleton_entry_header_meta'] = 6;
$s['skeleton_entry_header']['skeleton_entry_header_close'] = 10;
$s['skeleton_before_thumbnail'] = array();
$s['skeleton_thumbnail']['skeleton_echo_thumbnail'] = 5;
$s['skeleton_after_thumbnail'] = array();
$s['skeleton_before_excerpt'] = array();
$s['skeleton_the_excerpt']['skeleton_echo_the_excerpt'] = 5;
$s['skeleton_after_excerpt'] = array();
$s['skeleton_entry_footer']['skeleton_entry_footer_open'] = 0;
$s['skeleton_entry_footer']['skeleton_entry_footer_meta'] = 6;
$s['skeleton_entry_footer']['skeleton_entry_footer_close'] = 10;
$s['skeleton_author_bio_top'] = array();
$s['skeleton_before_author_bio'] = array();
$s['skeleton_after_author_bio'] = array();
$s['skeleton_author_bio_bottom'] = array();
$s['skeleton_before_singular']['skeleton_breadcrumbs'] = 5;
$s['skeleton_before_the_content'] = array();
$s['skeleton_the_content']['skeleton_echo_the_content'] = '';
$s['skeleton_after_the_content'] = array();
$s['skeleton_page_navi']['skeleton_page_navigation'] = 5;
$s['skeleton_after_entry'] = array();
$s['skeleton_after_author']['skeleton_author_bio_bottom'] = 0;
$s['skeleton_after_date'] = array();
$s['skeleton_after_tags'] = array();
$s['skeleton_after_taxonomy'] = array();
$s['skeleton_after_category'] = array();
$s['skeleton_after_archive'] = array();
$s['skeleton_after_loop'] = array();
$s['skeleton_after_content']['skeleton_content_close'] = 0;
$s['skeleton_after_content']['skeleton_clear_div'] = 2;
// $s['skeleton_after_content']['* Content Sidebar *'] = 5;
$s['skeleton_after_singular'] = array();
$s['skeleton_before_comments'] = array();
$s['skeleton_after_comments'] = array();
$s['skeleton_before_footer']['skeleton_footer_banner'] = 5;
$s['skeleton_footer']['skeleton_footer_open'] = 0;
$s['skeleton_footer']['skeleton_footer_extras'] = 2;
$s['skeleton_footer']['skeleton_footer_widgets'] = 4;
$s['skeleton_before_footer_widgets'] = array();
$s['skeleton_after_footer_widgets'] = array();
$s['skeleton_footer']['skeleton_footer_nav'] = 6;
$s['skeleton_footer']['skeleton_footer_credits'] = 8;
$s['skeleton_footer']['skeleton_footer_close'] = 10;
$s['skeleton_after_footer']['skeleton_bottom_banner'] = 5;
$s['skeleton_container_close']['skeleton_main_wrapper_close'] = 5;
$s['skeleton_after_container'] = array();

// 2.0.5: add dummy skeleton actions for testing removal of later
foreach ($s as $hook => $functions) {
	if (count($functions) > 0) {
		foreach ($functions as $function => $priority) {
			$priority = apply_filters($function.'_position', $priority);
			$s[$hook][$function] = $priority;
			add_action($hook, $function, $priority);
		}
	}
}
global $vthemehooks; $vthemehooks['skeleton'] = $s; unset($s);

// ---------------------------
// Remove Matching Old Actions
// ---------------------------
if (!function_exists('bioship_compat_hooks')) {

 // 2.0.5: check for user-removed skeleton_ actions
 add_action('init', 'bioship_compat_hooks', 99);

 function bioship_compat_hooks() {
	global $vthemehooks;
	foreach ($vthemehooks['skeleton'] as $hook => $functions) {
		if (count($functions) > 0) {
			foreach ($functions as $function => $priority) {
				if (has_action($hook, $function) == $priority) {
					// --- just remove the dummy skeleton action ---
					remove_action($hook, $function, $priority);
				} else {
					// --- remove any matching theme prefixed action ---
					$thishook = str_replace('skeleton_', THEMEPREFIX.'_', $hook);
					$thisfunction = str_replace('skeleton_', THEMEPREFIX.'_', $function);
					$thispriority = apply_filters($thisfunction, $priority);
					if (THEMEDEBUG) {
						bioship_debug("Removing Function ".$function." from Hook ".$thishook." with Priority ".$thispriority);
					}
					remove_action($thishook, $thisfunction, $thispriority);
				}
			}
		}
	}
 }
}

// ----------------------------------
// Convert Old Action/Filter Prefixes
// ----------------------------------
// 2.0.2: convert from skeleton to bioship prefix
if (!function_exists('bioship_compat_actions')) {

 // add_action('after_setup_theme', 'bioship_compat_actions', 99);
 add_action('wp', 'bioship_compat_actions', 99);

 function bioship_compat_actions() {

	global $wp_filter, $vthemehooks;

	// 2.0.5: loop through defined skeleton hook array
	foreach ($vthemehooks['skeleton'] as $oldhook => $functions) {
		$oldprefix = 'skeleton_';
		if (isset($wp_filter[$oldhook])) {
			if (strpos($oldhook, $oldprefix) === 0) {
				if (THEMEDEBUG) {bioship_debug("Transferring Hook for Theme Compat", $oldhook);}
				$newhook = THEMEPREFIX.'_'.substr($oldhook, strlen($oldprefix), strlen($oldhook));
				// 2.0.8: add extra check before property_exists check
				if (!isset($wp_filter[$newhook])  || !property_exists($wp_filter[$newhook], 'callbacks')) {
					$wp_filter[$newhook] = $wp_filter[$oldhook];
					unset($wp_filter[$oldhook]);
				} else {
					if (THEMEDEBUG) {bioship_debug("Old Skeleton Actions for Hook ".$oldhook, $wp_filter[$oldhook]);}
					$callbacks = $wp_filter[$newhook]->callbacks;
					if (property_exists($wp_filter[$oldhook], 'callbacks')) {
						$oldcallbacks = $wp_filter[$oldhook]->callbacks;
						foreach ($oldcallbacks as $priority => $functions) {
							// 2.0.9: comment out old line (undefined callback)
							// $callbacks[$priority] = $callback;
							foreach ($functions as $key => $func) {
								// if (THEMEDEBUG) {echo "<!-- ".$newhook." - ".$func['function']." - ".$priority." - ".$func['accepted_args']." -->";}
								add_action($newhook, $func['function'], $priority, $func['accepted_args']);
							}
						}
					}
					// $wp_filter[$newhook]->callbacks = $callbacks;
					unset($wp_filter[$oldhook]);
					if (THEMEDEBUG) {bioship_debug("New BioShip Actions for Hook ".$newhook, $wp_filter[$newhook]);}
				}
			}
		}
	}
 }
}

// -----------------
// === Functions ===
// -----------------
// 2.0.2 to 2.0.5: backwards compatability for new bioship_ functions prefix

// functions.php
// -------------
if (function_exists('skeleton_timer_start')) {function bioship_timer_start() {return skeleton_timer_start();} }
if (function_exists('skeleton_timer_time')) {function bioship_timer_time() {return skeleton_timer_time();} }
if (function_exists('skeleton_write_to_file')) {function bioship_write_to_file($a,$b) {return skeleton_write_to_file($a,$b);} }
if (function_exists('skeleton_write_debug_file')) {function bioship_write_debug_file($a,$b) {return skeleton_write_debug_file($a,$b);} }
if (function_exists('skeleton_apply_filters')) {function bioship_apply_filters($a,$b,$c) {return skeleton_apply_filters($a,$b,$c);} }
if (function_exists('skeleton_fix_serialized')) {function bioship_fix_serialized($a) {return skeleton_fix_serialized($a);} }
if (function_exists('skeleton_fix_str_length')) {function bioship_fix_str_length($a) {return skeleton_fix_str_length($a);} }
if (function_exists('skeleton_get_option')) {function bioship_get_option($a) {return skeleton_get_option($a);} }
if (function_exists('skeleton_get_theme_settings')) {function bioship_get_theme_settings($a,$b=false) {return skeleton_get_theme_settings($a,$b);} }
if (function_exists('skeleton_forced_settings_restored')) {function bioship_forced_settings_restored() {return skeleton_forced_settings_restored();} }
if (function_exists('skeleton_file_settings_restored')) {function bioship_file_settings_restored() {return skeleton_file_settings_restored();} }
if (function_exists('skeleton_backup_settings_restored')) {function bioship_backup_settings_restored() {return skeleton_backup_settings_restored();} }
if (function_exists('skeleton_file_hierarchy')) {function bioship_file_hierarchy($a,$b,$c=array()) {return skeleton_file_hierarchy($a,$b,$c);} }
if (function_exists('skeleton_add_action')) {function bioship_add_action($a,$b,$c) {return skeleton_add_action($a,$b,$c);} }
if (function_exists('skeleton_get_post_types')) {function bioship_get_post_types($a=null) {return skeleton_get_post_types($a=null);} }
if (function_exists('skeleton_word_to_number')) {function bioship_word_to_number($a) {return skeleton_word_to_number($a);} }
if (function_exists('skeleton_number_to_word')) {function bioship_number_to_word($a) {return skeleton_number_to_word($a);} }
if (function_exists('skeleton_themedrive_determine_theme')) {function bioship_themedrive_determine_theme() {return skeleton_themedrive_determine_theme();} }
if (function_exists('skeleton_trace')) {function bioship_trace($a=null,$b=null,$c=null,$d=null) {return skeleton_trace($a,$b,$c,$d);} }
if (function_exists('skeleton_customizer_convert_posted')) {function bioship_customizer_convert_posted($a,$b) {return skeleton_customizer_convert_posted($a,$b);} }
if (function_exists('skeleton_titan_redirect_bypass')) {function bioship_titan_redirect_bypass($a, $b) {return skeleton_titan_redirect_bypass($a, $b);} }
if (function_exists('skeleton_add_theme_info_page')) {function bioship_add_theme_info_page() {return skeleton_add_theme_info_page();} }
if (function_exists('skeleton_titan_theme_options')) {function bioship_titan_theme_options($a) {return skeleton_titan_theme_options($a);} }
if (function_exists('skeleton_titan_create_options')) {function bioship_titan_create_options() {return skeleton_titan_create_options();} }
if (function_exists('skeleton_remove_woocommerce_theme_notice')) {function bioship_remove_woocommerce_theme_notice() {return skeleton_remove_woocommerce_theme_notice();} }
if (function_exists('skeleton_options_customize_loader')) {function bioship_customizer_loader($wp_customize) {return skeleton_options_customize_loader($wp_customize);} }
if (function_exists('skeleton_options_customize_preview_loader')) {function bioship_customizer_preview_loader() {return skeleton_options_customize_preview_loader();} }
if (function_exists('skeleton_customizer_loading_icon')) {function bioship_customizer_loading_icon() {return skeleton_customizer_loading_icon();} }
if (function_exists('skeleton_hybrid_core_setup')) {function bioship_hybrid_core_setup() {return skeleton_hybrid_core_setup();} }
if (function_exists('skeleton_load_hybrid_media')) {function bioship_load_hybrid_media() {return skeleton_load_hybrid_media();} }
if (function_exists('skeleton_meta_charset')) {function bioship_meta_charset() {return skeleton_meta_charset();} }
if (function_exists('skeleton_pingback_link')) {function bioship_pingback_link() {return skeleton_pingback_link();} }
if (function_exists('skeleton_hybrid_attr_header')) {function bioship_hybrid_attr_header($a) {return skeleton_hybrid_attr_header($a);} }
if (function_exists('skeleton_hybrid_attr_site_description')) {function bioship_hybrid_attr_site_description($a) {return skeleton_hybrid_attr_site_description($a);} }
if (function_exists('skeleton_hybrid_attr_content')) {function bioship_hybrid_attr_content($a) {return skeleton_hybrid_attr_content($a);} }
if (function_exists('skeleton_hybrid_attr_footer')) {function bioship_hybrid_attr_footer($a) {return skeleton_hybrid_attr_footer($a);} }
if (function_exists('skeleton_theme_update_checker')) {function bioship_theme_update_checker() {return skeleton_theme_update_checker();} }
if (function_exists('skeleton_avoid_deletion')) {function bioship_avoid_deletion($a) {return skeleton_avoid_deletion($a);} }

// Skull Functions
// ---------------
if (function_exists('skeleton_register_nav_menus')) {function bioship_register_nav_menus() {return skeleton_register_nav_menus();} }
if (function_exists('skeleton_register_sidebar')) {function bioship_register_sidebar($a,$b,$c='') {return skeleton_register_sidebar($a,$b,$c);} }
if (function_exists('skeleton_widget_page_message')) {function bioship_widget_page_message() {return skeleton_widget_page_message();} }
if (function_exists('skeleton_widgets_init_active')) {function bioship_widgets_init_active() {return skeleton_widgets_init_active();} }
if (function_exists('skeleton_widgets_init_inactive')) {function bioship_widgets_init_inactive() {return skeleton_widgets_init_inactive();} }
if (function_exists('skeleton_widgets_init')) {function bioship_widgets_init($a=true) {return skeleton_widgets_init($a);} }
if (function_exists('skeleton_load_layout')) {function bioship_set_layout() {return skeleton_load_layout();} }
if (function_exists('skeleton_set_page_context')) {function bioship_set_page_context() {return skeleton_set_page_context();} }
if (function_exists('skeleton_set_max_width')) {function bioship_set_max_width() {return skeleton_set_max_width();} }
if (function_exists('skeleton_set_grid_columns')) {function bioship_set_grid_columns() {return skeleton_set_grid_columns();} }
if (function_exists('skeleton_set_sidebar_layout')) {function bioship_set_sidebar_layout() {return skeleton_set_sidebar_layout();} }
if (function_exists('skeleton_set_sidebar')) {function bioship_set_sidebar($a) {return skeleton_set_sidebar($a);} }
if (function_exists('skeleton_set_sidebar_columns')) {function bioship_set_sidebar_columns() {return skeleton_set_sidebar_columns();} }
if (function_exists('skeleton_set_subsidebar_columns')) {function bioship_set_subsidebar_columns() {return skeleton_set_subsidebar_columns();} }
if (function_exists('skeleton_set_content_width')) {function bioship_set_content_width() {return skeleton_set_content_width();} }
if (function_exists('skeleton_get_content_width')) {function bioship_get_content_width() {return skeleton_get_content_width();} }
if (function_exists('skeleton_content_width')) {function bioship_content_width() {return skeleton_content_width();} }
if (function_exists('skeleton_get_content_padding_width')) {function bioship_get_content_padding_width($a,$b=false) {return skeleton_get_content_padding_width($a,$b=false);} }

if (function_exists('skeleton_title_separator')) {function bioship_title_separator($a) {return skeleton_title_separator($a);} }
if (function_exists('skeleton_render_title_tag_filtered')) {function bioship_render_title_tag_filtered() {return skeleton_render_title_tag_filtered();} }
if (function_exists('skeleton_wp_render_title_tag')) {function bioship_wp_render_title_tag($a) {return skeleton_wp_render_title_tag($a);} }
if (function_exists('skeleton_wp_title_tag')) {function bioship_wp_title_tag() {return skeleton_wp_title_tag();} }
if (function_exists('skeleton_wp_title')) {function bioship_wp_title($a,$b) {return skeleton_wp_title($a,$b);} }

if (function_exists('skeleton_get_header')) {function bioship_get_header($a=false) {return skeleton_get_header($a);} }
if (function_exists('skeleton_get_footer')) {function bioship_get_footer($a=false) {return skeleton_get_footer($a);} }
if (function_exists('skeleton_sidebar_check_template')) {function bioship_sidebar_template_check($a,$b) {return skeleton_sidebar_check_template($a,$b);} }
if (function_exists('skeleton_get_sidebar')) {function bioship_get_sidebar($a) {return skeleton_get_sidebar($a);} }
if (function_exists('skeleton_get_loop')) {function bioship_get_loop($a=false) {return skeleton_get_loop($a);} }
if (function_exists('skeleton_get_loop_title')) {function bioship_get_loop_title() {return skeleton_get_loop_title();} }
if (function_exists('skeleton_get_loop_description')) {function bioship_get_loop_description() {return skeleton_get_loop_description();} }
if (function_exists('skeleton_locate_template')) {function bioship_locate_template($a,$b=false,$c=true) {return skeleton_locate_template($a,$b,$c);} }
if (function_exists('skeleton_comments_template')) {function bioship_comments_template($a) {return skeleton_comments_template($a);} }
if (function_exists('skeleton_archive_template_hierarchy')) {function bioship_archive_template_hierarchy($a) {return skeleton_archive_template_hierarchy($a);} }
if (function_exists('skeleton_content_template_hierarchy')) {function bioship_content_template_hierarchy($a) {return skeleton_content_template_hierarchy($a);} }
if (function_exists('skeleton_get_author_avatar')) {function bioship_get_author_avatar() {return skeleton_get_author_avatar();} }
if (function_exists('skeleton_get_author_by_post')) {function bioship_get_author_by_post($a) {return skeleton_get_author_by_post($a);} }
if (function_exists('skeleton_get_author_display')) {function bioship_get_author_display($a) {return skeleton_get_author_display($a);} }
if (function_exists('skeleton_get_author_display_by_post')) {function bioship_get_author_display_by_post($a) {return skeleton_get_author_display_by_post($a);} }
if (function_exists('skeleton_get_entry_meta')) {function bioship_get_entry_meta($a,$b,$c) {return skeleton_get_entry_meta($a,$b,$c);} }
if (function_exists('skeleton_count_footer_widgets')) {function bioship_count_footer_widgets() {return skeleton_count_footer_widgets();} }

if (function_exists('skeleton_setup')) {function bioship_setup() {return skeleton_setup();} }
if (function_exists('skeleton_add_dynamic_editor_styles')) {function bioship_add_dynamic_editor_styles($a) {return skeleton_add_dynamic_editor_styles($a);} }
if (function_exists('skeleton_scripts')) {function bioship_scripts() {return skeleton_scripts();} }
if (function_exists('skeleton_jquery_fallback')) {function bioship_jquery_fallback($a,$b) {return skeleton_jquery_fallback($a,$b);} }
if (function_exists('skeleton_meta_generator')) {function bioship_meta_generator() {return skeleton_meta_generator();} }
if (function_exists('skeleton_mobile_meta')) {function bioship_mobile_meta() {return skeleton_mobile_meta();} }
if (function_exists('skeleton_icons')) {function bioship_site_icons() {return skeleton_icons();} }
if (function_exists('skeleton_apple_icon_sizes')) {function bioship_apple_icon_sizes($a) {return skeleton_apple_icon_sizes($a);} }
if (function_exists('skeleton_apple_startup_images')) {function bioship_apple_startup_images($a) {return skeleton_apple_startup_images($a);} }
if (function_exists('skeleton_csshero_script_dir')) {function bioship_csshero_script_dir() {return skeleton_csshero_script_dir();} }
if (function_exists('skeleton_csshero_script_url')) {function bioship_csshero_script_url($a,$b,$c) {return skeleton_csshero_script_url($a,$b,$c);} }
if (function_exists('skeleton_get_theme_includes')) {function bioship_get_theme_includes() {return skeleton_get_theme_includes();} }
if (function_exists('skeleton_check_theme_includes')) {function bioship_check_theme_includes() {return skeleton_check_theme_includes();} }
if (function_exists('skeleton_check_theme_templates')) {function bioship_check_theme_templates() {return skeleton_check_theme_templates();} }
if (function_exists('skeleton_admin_template_dropdown')) {function bioship_admin_template_dropdown() {return skeleton_admin_template_dropdown();} }

// Skeleton Functions
// ------------------
if (function_exists('skeleton_main_wrapper_open')) {function bioship_skeleton_main_wrapper_open() {return skeleton_main_wrapper_open();} }
if (function_exists('skeleton_main_wrapper_close')) {function bioship_skeleton_main_wrapper_close() {return skeleton_main_wrapper_close();} }
if (function_exists('skeleton_echo_clear_div')) {function bioship_skeleton_echo_clear_div() {return skeleton_echo_clear_div();} }
if (function_exists('skeleton_header_open')) {function bioship_skeleton_header_open() {return skeleton_header_open();} }
if (function_exists('skeleton_header_close')) {function bioship_skeleton_header_close() {return skeleton_header_close();} }
if (function_exists('skeleton_header_nav')) {function bioship_skeleton_header_nav() {return skeleton_header_nav();} }
if (function_exists('skeleton_header_logo')) {function bioship_skeleton_header_logo() {return skeleton_header_logo();} }
if (function_exists('skeleton_header_widgets')) {function bioship_skeleton_header_widgets() {return skeleton_header_widgets();} }
if (function_exists('skeleton_header_extras')) {function bioship_skeleton_header_extras() {return skeleton_header_extras();} }
if (function_exists('skeleton_main_menu_open')) {function bioship_skeleton_main_menu_open() {return skeleton_main_menu_open();} }
if (function_exists('skeleton_main_menu')) {function bioship_skeleton_main_menu() {return skeleton_main_menu();} }
if (function_exists('skeleton_main_menu_close')) {function bioship_skeleton_main_menu_close() {return skeleton_main_menu_close();} }
if (function_exists('skeleton_main_menu_mobile_button')) {function bioship_skeleton_main_menu_button() {return skeleton_main_menu_mobile_button();} }
if (function_exists('skeleton_secondary_menu')) {function bioship_skeleton_secondary_menu() {return skeleton_secondary_menu();} }
if (function_exists('skeleton_banner_abstract')) {function bioship_skeleton_banner_abstract($a) {return skeleton_banner_abstract($a);} }
if (function_exists('skeleton_top_banner')) {function bioship_skeleton_banner_top() {return skeleton_top_banner();} }
if (function_exists('skeleton_header_banner')) {function bioship_skeleton_banner_header() {return skeleton_header_banner();} }
if (function_exists('skeleton_navbar_banner')) {function bioship_skeleton_banner_navbar() {return skeleton_navbar_banner();} }
if (function_exists('skeleton_footer_banner')) {function bioship_skeleton_banner_footer() {return skeleton_footer_banner();} }
if (function_exists('skeleton_bottom_banner')) {function bioship_skeleton_banner_bottom() {return skeleton_bottom_banner();} }
if (function_exists('skeleton_add_widget_classes')) {function bioship_skeleton_add_widget_classes($a) {return skeleton_add_widget_classes($a);} }
if (function_exists('skeleton_mobile_sidebar_button_swap')) {function bioship_mobile_sidebar_button_swap() {return skeleton_mobile_sidebar_button_swap();} }
if (function_exists('skeleton_mobile_subsidebar_button_swap')) {function bioship_mobile_subsidebar_button_swap() {return skeleton_mobile_subsidebar_button_swap();} }
if (function_exists('skeleton_sidebar_position_class')) {function bioship_skeleton_sidebar_position_class($v) {return skeleton_sidebar_position_class($v);} }
if (function_exists('skeleton_sidebar_mobile_button')) {function bioship_skeleton_sidebar_button() {return skeleton_sidebar_mobile_button();} }
if (function_exists('skeleton_sidebar_open')) {function bioship_skeleton_sidebar_open() {return skeleton_sidebar_open();} }
if (function_exists('skeleton_sidebar_close')) {function bioship_skeleton_sidebar_close() {return skeleton_sidebar_close();} }
if (function_exists('skeleton_subsidebar_position_class')) {function bioship_skeleton_subsidebar_position_class($v) {return skeleton_subsidebar_position_class($v);} }
if (function_exists('skeleton_subsidebar_mobile_button')) {function bioship_skeleton_subsidebar_button() {return skeleton_subsidebar_mobile_button();} }
if (function_exists('skeleton_subsidebar_open')) {function bioship_skeleton_subsidebar_open() {return skeleton_subsidebar_open();} }
if (function_exists('skeleton_subsidebar_close')) {function bioship_skeleton_subsidebar_close() {return skeleton_subsidebar_close();} }
if (function_exists('skeleton_woocommerce_page_wrapper_open')) {function bioship_skeleton_woocommerce_wrapper_open() {return skeleton_woocommerce_page_wrapper_open();} }
if (function_exists('skeleton_woocommerce_page_wrapper_close')) {function bioship_skeleton_woocommerce_wrapper_close() {return skeleton_woocommerce_page_wrapper_close();} }
if (function_exists('skeleton_content_open')) {function bioship_skeleton_content_open() {return skeleton_content_open();} }
if (function_exists('skeleton_content_wrap_close')) {function skeleton_content_close() {return skeleton_content_wrap_close();} }
if (function_exists('skeleton_content_close')) {function bioship_skeleton_content_close() {return skeleton_content_close();} }
if (function_exists('skeleton_home_page_content')) {function bioship_skeleton_home_page_content() {return skeleton_home_page_content();} }
if (function_exists('skeleton_echo_the_excerpt')) {function bioship_skeleton_echo_the_excerpt() {return skeleton_echo_the_excerpt();} }
if (function_exists('skeleton_echo_the_content')) {function bioship_skeleton_echo_the_content() {return skeleton_echo_the_content();} }
if (function_exists('skeleton_attachment_media_handler')) {function bioship_skeleton_media_handler() {return skeleton_attachment_media_handler();} }
if (function_exists('skeleton_entry_header_open')) {function bioship_skeleton_entry_header_open() {return skeleton_entry_header_open();} }
if (function_exists('skeleton_entry_header_close')) {function bioship_skeleton_entry_header_close() {return skeleton_entry_header_close();} }
if (function_exists('skeleton_entry_header_title')) {function bioship_skeleton_entry_header_title() {return skeleton_entry_header_title();} }
if (function_exists('skeleton_entry_header_subtitle')) {function bioship_skeleton_entry_header_subtitle() {return skeleton_entry_header_subtitle();} }
if (function_exists('skeleton_entry_header_meta')) {function bioship_skeleton_entry_header_meta() {return skeleton_entry_header_meta();} }
if (function_exists('skeleton_entry_footer_open')) {function bioship_skeleton_entry_footer_open() {return skeleton_entry_footer_open();} }
if (function_exists('skeleton_entry_footer_close')) {function bioship_skeleton_entry_footer_close() {return skeleton_entry_footer_close();} }
if (function_exists('skeleton_entry_footer_meta')) {function bioship_skeleton_entry_footer_meta() {return skeleton_entry_footer_meta();} }
if (function_exists('skeleton_echo_thumbnail')) {function bioship_skeleton_echo_thumbnail() {return skeleton_echo_thumbnail();} }
if (function_exists('skeleton_get_thumbnail')) {function bioship_skeleton_get_thumbnail($a,$b,$c,$d='') {return skeleton_get_thumbnail($a,$b,$c,$d='');} }
if (function_exists('skeleton_thumbnailer')) {function bioship_skeleton_thumbnailer($a,$b,$c,$d='') {return skeleton_thumbnailer($a,$b,$c,$d='');} }
if (function_exists('skeleton_echo_author_bio')) {function bioship_skeleton_echo_author_bio($a) {return skeleton_echo_author_bio($a);} }
if (function_exists('skeleton_echo_author_bio_top')) {function bioship_skeleton_echo_author_bio_top() {return skeleton_echo_author_bio_top();} }
if (function_exists('skeleton_echo_author_bio_bottom')) {function bioship_skeleton_echo_author_bio_bottom() {return skeleton_echo_author_bio_bottom();} }
if (function_exists('skeleton_author_bio_box')) {function bioship_skeleton_author_bio_box($a,$b,$c) {return skeleton_author_bio_box($a,$b,$c);} }
if (function_exists('skeleton_about_author_title')) {function bioship_skeleton_about_author_title() {return skeleton_about_author_title();} }
if (function_exists('skeleton_author_posts_link')) {function bioship_skeleton_author_posts_link($a) {return skeleton_author_posts_link($a);} }
if (function_exists('skeleton_echo_comments')) {function bioship_skeleton_echo_comments() {return skeleton_echo_comments();} }
if (function_exists('skeleton_comments')) {function bioship_skeleton_comments($a,$b,$c) {return skeleton_comments($a,$b,$c);} }
if (function_exists('skeleton_comments_popup_script')) {function bioship_skeleton_comments_popup_script() {return skeleton_comments_popup_script();} }
if (function_exists('skeleton_breadcrumbs')) {function bioship_skeleton_breadcrumbs() {return skeleton_breadcrumbs();} }
if (function_exists('skeleton_check_breadcrumbs')) {function bioship_skeleton_check_breadcrumbs() {return skeleton_check_breadcrumbs();} }
if (function_exists('skeleton_page_navigation')) {function bioship_skeleton_page_navigation() {return skeleton_page_navigation();} }
if (function_exists('skeleton_paged_navi')) {function bioship_skeleton_paged_navi() {return skeleton_paged_navi();} }
if (function_exists('skeleton_footer')) {function bioship_skeleton_footer() {return skeleton_footer();} }
if (function_exists('skeleton_footer_wrap_open')) {function skeleton_footer_open() {return skeleton_footer_wrap_open();} }
if (function_exists('skeleton_footer_open')) {function bioship_skeleton_footer_open() {return skeleton_footer_open();} }
if (function_exists('skeleton_footer_wrap_close')) {function skeleton_footer_close() {return skeleton_footer_wrap_close();} }
if (function_exists('skeleton_footer_close')) {function bioship_skeleton_footer_close() {return skeleton_footer_close();} }
if (function_exists('skeleton_footer_extras')) {function bioship_skeleton_footer_extras() {return skeleton_footer_extras();} }
if (function_exists('skeleton_footer_widgets')) {function bioship_skeleton_footer_widgets() {return skeleton_footer_widgets();} }
if (function_exists('skeleton_footer_nav')) {function bioship_skeleton_footer_nav() {return skeleton_footer_nav();} }
if (function_exists('skeleton_footer_credits')) {function bioship_skeleton_footer_credits() {return skeleton_footer_credits();} }
if (function_exists('skeleton_credit_link')) {function bioship_skeleton_credit_link() {return skeleton_credit_link();} }

// Muscle Functions
// ----------------
if (function_exists('muscle_get_display_overrides')) {function bioship_muscle_get_display_overrides($a) {return muscle_get_display_overrides($a);} }
if (function_exists('muscle_get_templating_overrides')) {function bioship_muscle_get_templating_overrides($a) {return muscle_get_templating_overrides($a);} }
if (function_exists('muscle_perpage_override_styles')) {function bioship_muscle_perpage_override_styles() {return muscle_perpage_override_styles();} }
if (function_exists('muscle_thumbnail_size_perpost')) {function bioship_muscle_thumbnail_size_perpost($a,$b=null) {return muscle_thumbnail_size_perpost($a,$b);} }
if (function_exists('muscle_get_content_filter_overrides')) {function bioship_muscle_get_content_filter_overrides($a) {return muscle_get_content_filter_overrides($a);} }
if (function_exists('muscle_remove_content_filters')) {function bioship_muscle_remove_content_filters($a) {return muscle_remove_content_filters($a);} }
if (function_exists('muscle_default_gravatar')) {function bioship_muscle_default_gravatar($a) {return muscle_default_gravatar($a);} }
if (function_exists('muscle_discreet_text_widget')) {function bioship_muscle_discreet_text_widget() {return muscle_discreet_text_widget();} }
if (function_exists('muscle_video_background')) {function bioship_muscle_video_background() {return muscle_video_background();} }
if (function_exists('muscle_internet_explorer_scripts')) {function bioship_muscle_internet_explorer_scripts() {return muscle_internet_explorer_scripts();} }
if (function_exists('muscle_load_prefixfree')) {function bioship_muscle_load_prefixfree() {return muscle_load_prefixfree();} }
if (function_exists('muscle_fonts_noprefix_attribute')) {function bioship_muscle_fonts_noprefix_attribute($a,$b) {return muscle_fonts_noprefix_attribute($a,$b);} }
if (function_exists('muscle_load_nwwatcher')) {function bioship_muscle_load_nwwatcher() {return muscle_load_nwwatcher();} }
if (function_exists('muscle_load_nwevents')) {function bioship_muscle_load_nwevents() {return muscle_load_nwevents();} }
if (function_exists('muscle_media_queries_script')) {function bioship_muscle_media_queries_script() {return muscle_media_queries_script();} }
if (function_exists('muscle_load_fastclick')) {function bioship_muscle_load_fastclick() {return muscle_load_fastclick();} }
if (function_exists('muscle_load_mousewheel')) {function bioship_muscle_load_mousewheel() {return muscle_load_mousewheel();} }
if (function_exists('muscle_load_csssupports')) {function bioship_muscle_load_csssupports() {return muscle_load_csssupports();} }
if (function_exists('muscle_match_media_script')) {function bioship_muscle_match_media_script() {return muscle_match_media_script();} }
if (function_exists('muscle_load_modernizr')) {function bioship_muscle_load_modernizr() {return muscle_load_modernizr();} }
if (function_exists('muscle_smooth_scrolling')) {function bioship_muscle_smooth_scrolling() {return muscle_smooth_scrolling();} }
if (function_exists('muscle_load_matchheight')) {function bioship_muscle_load_matchheight() {return muscle_load_matchheight();} }
if (function_exists('muscle_run_matchheight')) {function bioship_muscle_run_matchheight() {return muscle_run_matchheight();} }
if (function_exists('muscle_load_stickykit')) {function bioship_muscle_load_stickykit() {return muscle_load_stickykit();} }
if (function_exists('muscle_echo_sticky_elements')) {function bioship_muscle_echo_sticky_elements() {return muscle_echo_sticky_elements();} }
if (function_exists('muscle_load_fitvids')) {function bioship_muscle_load_fitvids() {return muscle_load_fitvids();} }
if (function_exists('muscle_echo_fitvids_elements')) {function bioship_muscle_fitvids_elements() {return muscle_echo_fitvids_elements();} }
if (function_exists('muscle_load_scrolltofixed')) {function bioship_muscle_load_scrolltofixed() {return muscle_load_scrolltofixed();} }
if (function_exists('muscle_logo_resize')) {function bioship_muscle_logo_resize() {return muscle_logo_resize();} }
if (function_exists('muscle_jpeg_quality')) {function bioship_muscle_jpeg_quality() {return muscle_jpeg_quality();} }
if (function_exists('muscle_thumbnail_size_custom')) {function bioship_muscle_thumbnail_size_custom($a) {return muscle_thumbnail_size_custom($a);} }
if (function_exists('muscle_fading_thumbnails')) {function bioship_muscle_fading_thumbnails($a,$b) {return muscle_fading_thumbnails($a,$b);} }
if (function_exists('muscle_fading_thumbnail_script')) {function bioship_muscle_fading_thumbnail_script() {return muscle_fading_thumbnail_script();} }
if (function_exists('muscle_select_home_categories')) {function bioship_muscle_select_home_categories($a) {return muscle_select_home_categories($a);} }
if (function_exists('muscle_search_results_per_page')) {function bioship_muscle_search_results_per_page($a) {return muscle_search_results_per_page($a);} }
if (function_exists('muscle_searchable_cpts')) {function bioship_muscle_searchable_cpts($a) {return muscle_searchable_cpts($a);} }
if (function_exists('muscle_jetpack_scroll_setup')) {function bioship_muscle_jetpack_scroll_setup() {return muscle_jetpack_scroll_setup();} }
if (function_exists('muscle_infinite_scroll_loop')) {function bioship_muscle_infinite_scroll_loop() {return muscle_infinite_scroll_loop();} }
if (function_exists('muscle_excerpts_with_shortcodes')) {function bioship_muscle_excerpts_with_shortcodes($a) {return muscle_excerpts_with_shortcodes($a);} }
if (function_exists('skeleton_excerpt_length')) {function muscle_excerpt_length($a) {return skeleton_excerpt_length($a);} }
if (function_exists('muscle_excerpt_length')) {function bioship_muscle_excerpt_length($a) {return muscle_excerpt_length($a);} }
if (function_exists('skeleton_continue_reading_link')) {function muscle_continue_reading_link() {return skeleton_continue_reading_link();} }
if (function_exists('muscle_continue_reading_link')) {function bioship_muscle_continue_reading_link() {return muscle_continue_reading_link();} }
if (function_exists('skeleton_auto_excerpt_more')) {function muscle_auto_excerpt_more($a) {return skeleton_auto_excerpt_more($a);} }
if (function_exists('muscle_auto_excerpt_more')) {function bioship_muscle_auto_excerpt_more($a) {return muscle_auto_excerpt_more($a);} }
if (function_exists('muscle_remove_more_jump_link')) {function bioship_muscle_remove_more_jump_link($a) {return muscle_remove_more_jump_link($a);} }
if (function_exists('muscle_limit_post_revisions')) {function bioship_muscle_limit_post_revisions() {return muscle_limit_post_revisions();} }
if (function_exists('muscle_wp_subtitle_custom_support')) {function bioship_muscle_wp_subtitle_custom_support() {return muscle_wp_subtitle_custom_support();} }
if (function_exists('muscle_delay_feed_publish')) {function bioship_muscle_delay_feed_publish($a) {return muscle_delay_feed_publish($a);} }
if (function_exists('muscle_custom_feed_request')) {function bioship_muscle_custom_feed_request($a) {return muscle_custom_feed_request($a);} }
if (function_exists('muscle_rss_page_feed_full_content')) {function bioship_muscle_rss_page_feed_full_content($a) {return muscle_rss_page_feed_full_content($a);} }
if (function_exists('muscle_page_rss_excerpt_option')) {function bioship_muscle_page_rss_excerpt_option($a) {return muscle_page_rss_excerpt_option($a);} }
if (function_exists('muscle_rss_page_excerpt')) {function bioship_muscle_rss_page_excerpt($a) {return muscle_rss_page_excerpt($a);} }
if (function_exists('muscle_add_bioship_dashboard_feed_widget')) {function bioship_muscle_add_bioship_dashboard_feed_widget() {return muscle_add_bioship_dashboard_feed_widget();} }
if (function_exists('muscle_bioship_dashboard_feed_widget')) {function bioship_muscle_bioship_dashboard_feed_widget($a=true,$b=false) {return muscle_bioship_dashboard_feed_widget($a=true,$b=false);} }
if (function_exists('muscle_process_rss_feed')) {function bioship_muscle_process_rss_feed($a,$b) {return muscle_process_rss_feed($a,$b);} }
if (function_exists('muscle_admin_post_thumbnail_column')) {function bioship_muscle_admin_post_thumbnail_column($a) {return muscle_admin_post_thumbnail_column($a);} }
if (function_exists('muscle_display_post_thumbnail_column')) {function bioship_muscle_display_post_thumbnail_column($a,$b) {return muscle_display_post_thumbnail_column($a,$b);} }
if (function_exists('muscle_all_options_link')) {function bioship_muscle_all_options_link() {return muscle_all_options_link();} }
if (function_exists('muscle_remove_update_notice')) {function bioship_muscle_remove_update_notice() {return muscle_remove_update_notice();} }
if (function_exists('muscle_stop_new_user_notifications')) {function bioship_muscle_stop_new_user_notifications() {return muscle_stop_new_user_notifications();} }
if (function_exists('muscle_disable_self_pings')) {function bioship_muscle_disable_self_pings($a) {return muscle_disable_self_pings($a);} }
if (function_exists('muscle_cleaner_adminbar')) {function bioship_muscle_cleaner_adminbar() {return muscle_cleaner_adminbar();} }
if (function_exists('muscle_right_now_content_table_end')) {function bioship_muscle_right_now_content_table_end() {return muscle_right_now_content_table_end();} }
if (function_exists('muscle_login_headerurl')) {function bioship_muscle_login_headerurl($a) {return muscle_login_headerurl($a);} }
if (function_exists('muscle_login_headertitle')) {function bioship_muscle_login_headertitle($a) {return muscle_login_headertitle($a);} }
if (function_exists('muscle_login_styles')) {function bioship_muscle_login_styles() {return muscle_login_styles();} }
if (function_exists('muscle_login_body_hack')) {function bioship_muscle_login_body_hack($a) {return muscle_login_body_hack($a);} }
if (function_exists('muscle_login_body_filter_hack')) {function bioship_muscle_login_body_filter_hack($a,$b) {return muscle_login_body_filter_hack($a,$b);} }
if (function_exists('muscle_close_login_wrapper')) {function bioship_muscle_close_login_wrapper() {return muscle_close_login_wrapper();} }
if (function_exists('muscle_woocommerce_template_path')) {function bioship_muscle_woocommerce_template_path($a) {return muscle_woocommerce_template_path($a);} }
if (function_exists('muscle_woocommerce_template')) {function bioship_muscle_woocommerce_template($a,$b,$c,$d,$e) {return muscle_woocommerce_template($a,$b,$c,$d,$e);} }
if (function_exists('muscle_woocommerce_template_part')) {function bioship_muscle_woocommerce_template_part($a,$b,$c) {return muscle_woocommerce_template_part($a,$b,$c);} }
if (function_exists('muscle_open_graph_default_image')) {function bioship_muscle_open_graph_default_image($a) {return muscle_open_graph_default_image($a);} }
if (function_exists('muscle_open_graph_override_image_fields')) {function bioship_muscle_open_graph_override_image_fields($a) {return muscle_open_graph_override_image_fields($a);} }
if (function_exists('muscle_disallow_hook_php')) {function bioship_muscle_disallow_hook_php($a) {return muscle_disallow_hook_php($a);} }
if (function_exists('muscle_hybrid_get_hooks')) {function bioship_muscle_hybrid_get_hooks() {return muscle_hybrid_get_hooks();} }
if (function_exists('muscle_hybrid_hook_prefix')) {function bioship_muscle_hybrid_hook_prefix() {return muscle_hybrid_hook_prefix();} }
if (function_exists('muscle_load_foundation')) {function bioship_muscle_load_foundation() {return muscle_load_foundation();} }
if (function_exists('muscle_foundation_init')) {function bioship_muscle_foundation_init() {return muscle_foundation_init();} }
if (function_exists('muscle_tml_template_paths')) {function bioship_muscle_tml_template_paths($a) {return muscle_tml_template_paths($a);} }
if (function_exists('muscle_login_button_url')) {function bioship_muscle_login_button_url($a) {return muscle_login_button_url($a);} }
if (function_exists('muscle_register_button_url')) {function bioship_muscle_register_button_url($a) {return muscle_register_button_url($a);} }
if (function_exists('muscle_profile_button_url')) {function bioship_muscle_profile_button_url($a) {return muscle_profile_button_url($a);} }
if (function_exists('muscle_register_form_image')) {function bioship_muscle_register_form_image() {return muscle_register_form_image();} }
if (function_exists('muscle_login_form_image')) {function bioship_muscle_login_form_image() {return muscle_login_form_image();} }
if (function_exists('muscle_theme_switch_admin_fix')) {function bioship_muscle_theme_switch_admin_fix() {return muscle_theme_switch_admin_fix();} }
if (function_exists('muscle_admin_ajax_stylesheet')) {function bioship_muscle_admin_ajax_stylesheet() {return muscle_admin_ajax_stylesheet();} }
if (function_exists('muscle_admin_ajax_template')) {function bioship_muscle_admin_ajax_template() {return muscle_admin_ajax_template();} }

// Skin Functions
// --------------
if (function_exists('skeleton_grid_url_reference')) {function bioship_grid_url_reference() {return skeleton_grid_url_reference();} }
if (function_exists('skeleton_grid_dynamic_css')) {function bioship_grid_dynamic_css($a=false) {return skeleton_grid_dynamic_css($a);} }
if (function_exists('skeleton_grid_dynamic_css_inline')) {function bioship_grid_dynamic_css_inline() {return skeleton_grid_dynamic_css_inline();} }
if (function_exists('skeleton_deregister_styles')) {function bioship_skin_deregister_styles() {return skeleton_deregister_styles();} }
if (function_exists('skin_enqueue_styles')) {function bioship_skin_enqueue_styles() {return skin_enqueue_styles();} }
if (function_exists('skeleton_deregister_styles')) {function bioship_deregister_styles() {return skeleton_deregister_styles();} }
if (function_exists('skin_enqueue_admin_styles')) {function bioship_skin_enqueue_admin_styles() {return skin_enqueue_admin_styles();} }
if (function_exists('skin_typography_loader')) {function bioship_skin_typography_loader() {return skin_typography_loader();} }
if (function_exists('skin_dynamic_css')) {function bioship_skin_dynamic_css() {return skin_dynamic_css();} }
if (function_exists('skin_dynamic_css_inline')) {function bioship_skin_dynamic_css_inline() {return skin_dynamic_css_inline();} }
if (function_exists('skeleton_grid_url_reference')) {function bioship_grid_url_reference() {return skeleton_grid_url_reference();} }
if (function_exists('skeleton_grid_dynamic_css')) {function bioship_grid_dynamic_css($a=false) {return skeleton_grid_dynamic_css($a);} }
if (function_exists('skeleton_grid_dynamic_css_inline')) {function bioship_grid_dynamic_css_inline() {return skeleton_grid_dynamic_css_inline();} }
if (function_exists('skin_dynamic_admin_css')) {function bioship_skin_dynamic_admin_css($a=false) {return skin_dynamic_admin_css($a);} }
if (function_exists('skin_dynamic_admin_css_inline')) {function bioship_skin_dynamic_admin_css_inline() {return skin_dynamic_admin_css_inline();} }
if (function_exists('skin_dynamic_login_css_inline')) {function bioship_skin_dynamic_login_css_inline() {return skin_dynamic_login_css_inline();} }

// Options Functions
// -----------------
if (function_exists('optionsframework_to_titan')) {function bioship_optionsframework_to_titan() {return optionsframework_to_titan();} }
if (function_exists('custom_titan_websafefonts')) {function bioship_titan_websafe_fonts($a) {return custom_titan_websafefonts($a);} }
if (function_exists('custom_titan_googlefonts')) {function bioship_titan_google_fonts($a) {return custom_titan_googlefonts($a);} }
if (function_exists('optionsframework_option_name')) {function bioship_optionsframework_option_name() {return optionsframework_option_name();} }
if (function_exists('optionsframework_resource_url_fix')) {function bioship_optionsframework_resource_url_fix() {return optionsframework_resource_url_fix();} }
if (function_exists('options_enqueue_stickykit')) {function bioship_options_enqueue_stickykit() {return options_enqueue_stickykit();} }
if (function_exists('options_web_font_stacks')) {function bioship_options_web_font_stacks($a) {return options_web_font_stacks($a);} }
if (function_exists('options_title_fonts')) {function bioship_options_title_fonts() {return options_title_fonts();} }
if (function_exists('options_title_font_display')) {function bioship_options_title_font_display() {return options_title_font_display();} }
if (function_exists('options_body_font_display')) {function bioship_options_body_font_display() {return options_body_font_display();} }
if (function_exists('optionsframework_options')) {function bioship_options($a=true) {return optionsframework_options($a=true);} }

// Admin Functions
// ---------------
if (function_exists('admin_echo_setting_values')) {function bioship_admin_echo_setting_values() {return admin_echo_setting_values();} }
if (function_exists('admin_theme_settings_save')) {function bioship_admin_theme_settings_save($a,$b,$c) {return admin_theme_settings_save($a,$b,$c);} }
if (function_exists('admin_framework_settings_transfer')) {function bioship_admin_framework_settings_transfer() {return admin_framework_settings_transfer();} }
if (function_exists('admin_copy_theme_settings')) {function bioship_admin_copy_theme_settings() {return admin_copy_theme_settings();} }
if (function_exists('admin_copy_theme_success')) {function bioship_admin_copy_theme_success() {return admin_copy_theme_success();} }
if (function_exists('admin_copy_theme_failed_source')) {function bioship_admin_copy_theme_failed_source() {return admin_copy_theme_failed_source();} }
if (function_exists('admin_copy_theme_failed_destination')) {function bioship_admin_copy_theme_failed_destination() {return admin_copy_theme_failed_destination();} }
if (function_exists('admin_theme_options_submenu')) {function bioship_admin_theme_options_submenu() {return admin_theme_options_submenu();} }
if (function_exists('admin_options_default_submenu')) {function bioship_admin_options_default_submenu($a) {return admin_options_default_submenu($a);} }
if (function_exists('admin_theme_options_page_redirect')) {function bioship_admin_theme_options_page_redirect($a='') {return admin_theme_options_page_redirect($a);} }
if (function_exists('admin_theme_options_advanced')) {function bioship_admin_theme_options_advanced() {return admin_theme_options_advanced();} }
if (function_exists('admin_theme_options_position')) {function bioship_admin_theme_options_position() {return admin_theme_options_position();} }
if (function_exists('admin_themetestdrive_options')) {function bioship_admin_themetestdrive_options() {return admin_themetestdrive_options();} }
if (function_exists('admin_adminbar_theme_options')) {function bioship_admin_adminbar_theme_options() {return admin_adminbar_theme_options();} }
if (function_exists('admin_adminbar_replace_howdy')) {function bioship_admin_adminbar_replace_howdy($a) {return admin_adminbar_replace_howdy($a);} }
if (function_exists('admin_remove_admin_footer')) {function bioship_admin_remove_admin_footer() {return bioship_admin_remove_admin_footer();} }

if (function_exists('admin_theme_options_menu')) {function bioship_admin_theme_options_page() {return admin_theme_options_menu();} }
if (function_exists('options_switch_layer_options_tabs')) {function bioship_admin_theme_options_scripts() {return options_switch_layer_options_tabs();} }
if (function_exists('options_closing_div')) {function bioship_admin_theme_options_close() {return options_closing_div();} }
if (function_exists('options_admin_page_styles')) {function bioship_admin_theme_options_styles() {return options_admin_page_styles();} }
if (function_exists('options_floating_sidebar')) {function bioship_admin_floating_sidebar() {return options_floating_sidebar();} }
if (function_exists('admin_sidebar_save_button')) {function bioship_admin_sidebar_save_button($a) {return admin_sidebar_save_button($a);} }
if (function_exists('admin_check_filesystem_credentials')) {function bioship_admin_check_filesystem_credentials($a,$b,$c,$d) {return admin_check_filesystem_credentials($a,$b,$c,$d);} }
if (function_exists('admin_quicksave_css')) {function bioship_admin_quicksave_css() {return admin_quicksave_css();} }
if (function_exists('admin_theme_info_page')) {function bioship_admin_theme_info_page() {return admin_theme_info_page();} }
if (function_exists('admin_theme_info_section')) {function bioship_admin_theme_info_section() {return admin_theme_info_section();} }
if (function_exists('admin_do_install_child')) {function bioship_admin_do_install_child() {return admin_do_install_child();} }
if (function_exists('admin_theme_updates_available')) {function bioship_admin_theme_updates_available() {return admin_theme_updates_available();} }
if (function_exists('admin_do_install_clone')) {function bioship_admin_do_install_clone() {return admin_do_install_clone();} }
if (function_exists('admin_get_directory_files')) {function bioship_admin_get_directory_files($a,$b=true,$c='') {return admin_get_directory_files($a,$b,$c);} }
if (function_exists('admin_get_directory_subdirs')) {function bioship_admin_get_directory_subdirs($a,$b=true,$c='') {return bioship_admin_get_directory_subdirs($a,$b,$c);} }
if (function_exists('admin_build_selective_resources')) {function bioship_admin_build_selective_resources() {return bioship_admin_build_selective_resources();} }
if (function_exists('admin_theme_tools_forms')) {function bioship_admin_theme_tools_forms() {return admin_theme_tools_forms();} }

if (function_exists('admin_backup_theme_settings')) {function bioship_admin_backup_theme_settings() {return admin_backup_theme_settings();} }
if (function_exists('admin_do_backup_theme_settings')) {function bioship_admin_do_backup_theme_settings() {return admin_do_backup_theme_settings();} }
if (function_exists('admin_restore_theme_settings')) {function bioship_admin_restore_theme_settings() {return admin_restore_theme_settings();} }
if (function_exists('admin_export_theme_settings')) {function bioship_admin_export_theme_settings() {return admin_export_theme_settings();} }
if (function_exists('admin_import_theme_settings')) {function bioship_admin_import_theme_settings() {return admin_import_theme_settings();} }
if (function_exists('admin_revert_theme_settings')) {function bioship_admin_revert_theme_settings() {return admin_revert_theme_settings();} }
if (function_exists('admin_array_to_xml')) {function bioship_admin_array_to_xml(SimpleXMLElement $a, array $b) {return bioship_admin_array_to_xml($a,$b);} }
if (function_exists('admin_xml_to_array')) {function bioship_admin_xml_to_array($a) {return admin_xml_to_array($a);} }
if (function_exists('admin_verify_file_upload')) {function bioship_admin_verify_file_upload($a) {return admin_verify_file_upload($a);} }

if (function_exists('admin_theme_activation')) {function bioship_admin_theme_activation() {return admin_theme_activation();} }
if (function_exists('admin_theme_deactivation')) {function bioship_admin_theme_deactivation($a) {return admin_theme_deactivation($a);} }
if (function_exists('admin_child_theme_activation')) {function bioship_admin_child_theme_activation() {return admin_child_theme_activation();} }
if (function_exists('admin_child_theme_deactivation')) {function bioship_admin_child_theme_deactivation($a) {return admin_child_theme_deactivation($a);} }
if (function_exists('admin_get_child_theme_slug')) {function bioship_admin_get_child_theme_slug() {return admin_get_child_theme_slug();} }

if (function_exists('admin_add_theme_metabox')) {function bioship_admin_add_theme_metabox() {return admin_add_theme_metabox();} }
if (function_exists('admin_theme_metabox')) {function bioship_admin_theme_metabox() {return admin_theme_metabox();} }
if (function_exists('admin_update_metabox_options')) {function bioship_admin_update_metabox_options() {return admin_update_metabox_options();} }
if (function_exists('admin_quicksave_perpost_css')) {function bioship_admin_quicksave_perpost_css() {return admin_quicksave_perpost_css();} }
if (function_exists('admin_quicksave_perpost_css_form')) {function bioship_admin_quicksave_perpost_css_form() {return admin_quicksave_perpost_css_form();} }
if (function_exists('admin_install_titan_framework')) {function bioship_admin_install_titan_framework() {return admin_install_titan_framework();} }
if (function_exists('admin_tgm_titan_framework_check')) {function bioship_admin_tgm_titan_framework_check() {return admin_tgm_titan_framework_check();} }
if (function_exists('admin_register_plugins')) {function bioship_register_plugins() {return admin_register_plugins();} }

// Customizer Functions
// --------------------
if (function_exists('options_customize_register_controls')) {function bioship_customizer_register_controls($a) {return options_customize_register_controls($a);} }
if (function_exists('options_customize_load_control_options')) {function bioship_customizer_load_control_options($a) {return options_customize_load_control_options($a);} }
if (function_exists('options_customize_register_info_control')) {function bioship_customizer_register_info_control() {return options_customize_register_info_control();} }

if (function_exists('options_customize_text_script')) {function bioship_customizer_text_script() {return options_customize_text_script();} }
if (function_exists('options_customizer_info_script')) {function bioship_customizer_info_script() {return options_customizer_info_script();} }
if (function_exists('options_customize_save_serialized')) {function bioship_customizer_save_serialized() {return options_customize_save_serialized();} }
if (function_exists('options_customize_preview')) {function bioship_customizer_preview() {return options_customize_preview();} }
if (function_exists('options_customizer_i10n')) {function bioship_customizer_i10n() {return options_customizer_i10n();} }


// Older Functions
// ---------------
// 1.9.8: removed this one as spitting some weird constant warning?
// if (function_exists(muscle_get_overrides)) {function muscle_get_display_overrides() {return muscle_get_overrides();} }
// else {function muscle_get_overrides() {return muscle_get_display_overrides();} }
if (function_exists('muscle_adminbar_theme_options')) {function admin_adminbar_theme_options() {muscle_adminbar_theme_options();} }
else {function muscle_adminbar_theme_options() {admin_adminbar_theme_options();} }
if (function_exists('muscle_adminbar_replace_howdy')) {function admin_adminbar_replace_howdy() {muscle_adminbar_replace_howdy();} }
else {function muscle_adminbar_replace_howdy() {admin_adminbar_replace_howdy();} }
if (function_exists('muscle_remove_admin_footer')) {function admin_remove_admin_footer() {muscle_remove_admin_footer();} }
else {function muscle_remove_admin_footer() {admin_remove_admin_footer();} }


// Skeleton Options
// ----------------
// [deprecated] transitional from SMPL Skeleton Theme options,
// will remain here for old option name cross-references...
if (!function_exists('skeleton_options')) {
 function skeleton_options($name, $default) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;

	// Note: SMPL Skeleton Option Names (from its customizer.php)
	// layout, content_width, sidebar_width, page_layout
	// logotype (URL), header_typography, tagline_typography
	// body_bg_color, background_image (URL), body_typography
	// h1_typography, h2_typography, h3_typography, h4_typography, h5_typography
	// link_color, link_hover_color

	// deprecated: heading_font, body_font, body_text_color
	// deprecated: primary_color, secondary_color
	// now filters: header_extras, footer_extras

	// Fix to some duplicate option name crackliness
	if ($name == 'logotype') {$name = 'header_logo';}
	if ($name == 'sidebar_position') {$name = 'page_layout';}
	if ($name == 'layout_width') {$name = 'layout';}

	$value = $vthemesettings[$name];
	if ($value == '') {return $default;}
	return (skeleton_apply_filters('skeleton_options_'.$name, $value));
 }
}


// Old Function/Filters
// --------------------
add_filter('skeleton_sidebar_position','bioship_compat_sidebar_position');
function bioship_compat_sidebar_position($v) {return bioship_apply_filters('skeleton_sidebar_position_class',$v);}
add_filter('skeleton_subsidebar_position','bioship_compat_subsidebar_position');
function bioship_compat_subsidebar_position($v) {return bioship_apply_filters('skeleton_subsidebar_position_class',$v);}

// Old Filters
// -----------
add_filter('options_theme_options','bioship_compat_theme_options');
function bioship_compat_theme_options($v) {return bioship_apply_filters('options_themeoptions',$v);}
add_filter('admin_adminbar_menu_icon','bioship_compat_adminbar_menu_icon');
function bioship_compat_adminbar_menu_icon($v) {return bioship_apply_filters('options_adminbar_menu_icon',$v);}
add_filter('admin_adminbar_theme_options_title','bioship_compat_adminbar_theme_options_title');
function bioship_compat_adminbar_theme_options_title($v) {return bioship_apply_filters('muscle_adminbar_theme_options_title',$v);}
add_filter('admin_adminbar_theme_options_icon','bioship_compat_adminbar_theme_options_icon');
function bioship_compat_adminbar_theme_options_icon($v) {return bioship_apply_filters('muscle_adminbar_theme_options_icon',$v);}
add_filter('admin_adminbar_howdy_title','bioship_compat_adminbar_howdy_title');
function bioship_compat_adminbar_howdy_title($v) {return bioship_apply_filters('muscle_adminbar_howdy_title',$v);}
add_filter('admin_adminbar_remove_items','bioship_compat_adminbar_remove_items');
function bioship_compat_adminbar_remove_items($v) {return bioship_apply_filters('muscle_adminbar_remove_items',$v);}
add_filter('admin_admin_footer_text','bioship_compat_admin_footer_text');
function bioship_compat_admin_footer_text($v) {return bioship_apply_filters('muscle_admin_footer_text',$v);}
add_filter('skeleton_generator_meta','bioship_compat_generator_meta');
function bioship_compat_generator_meta($v) {return bioship_apply_filters('muscle_generator_meta',$v);}
// 2.0.9: fix to inconsistent filter name
add_filter('optionsframework_menu','bioship_compat_options_framework_menu', 1);
function bioship_compat_options_framework_menu($v) {return bioship_apply_filters('options_framework_menu',$v);}

// [deprecated] note: old Skeleton functions with mismatched name formats
// if (!function_exists('smpl_recommended_plugins')) {function smpl_recommended_plugins() {return;} }
// if (!function_exists('st_remove_wpautop')) {function st_remove_wpautop($v) {return $v;} }
// if (!function_exists('remove_more_jump_link')) {function remove_more_jump_link($v) {return $v;} }

// Re-Prefixed Filters
// -------------------
// 2.0.9: core theme filters skeleton_ prefix replaced with theme_
// Resource Directories
add_filter('skeleton_theme_tracer','bioship_compat_skeleton_core_dirs',1);
function bioship_compat_skeleton_core_dirs($v) {return bioship_apply_filters('theme_core_dirs',$v);}
add_filter('skeleton_admin_dirs','bioship_compat_skeleton_admin_dirs',1);
function bioship_compat_skeleton_admin_dirs($v) {return bioship_apply_filters('theme_admin_dirs',$v);}
add_filter('skeleton_css_dirs','bioship_compat_skeleton_css_dirs',1);
function bioship_compat_skeleton_css_dirs($v) {return bioship_apply_filters('theme_style_dirs',$v);}
add_filter('skeleton_js_dirs','bioship_compat_skeleton_js_dirs',1);
function bioship_compat_skeleton_js_dirs($v) {return bioship_apply_filters('theme_script_dirs',$v);}
add_filter('skeleton_img_dirs','bioship_compat_skeleton_img_dirs',1);
function bioship_compat_skeleton_img_dirs($v) {return bioship_apply_filters('theme_image_dirs',$v);}
// Debug Directories
add_filter('skeleton_debug','bioship_compat_skeleton_debug',1);
function bioship_compat_skeleton_debug($v) {return bioship_apply_filters('theme_debug',$v);}
add_filter('skeleton_debug_dirpath','bioship_compat_skeleton_debug_dirpath',1);
function bioship_compat_skeleton_debug_dirpath($v) {return bioship_apply_filters('theme_debug_dirpath',$v);}
add_filter('skeleton_debug_dirpath','bioship_compat_skeleton_debug_filename',1);
function bioship_compat_skeleton_debug_filename($v) {return bioship_apply_filters('theme_debug_filename',$v);}
add_filter('skeleton_theme_tracer','bioship_compat_skeleton_theme_tracer',1);
function bioship_compat_skeleton_theme_tracer($v) {return bioship_apply_filters('theme_tracer',$v);}

// 2.0.9: changed this filter name to muscle_display_overrides
add_filter('muscle_perpage_overrides','bioship_compat_muscle_display_overrides',1);
function bioship_compat_muscle_display_overrides($v) {return bioship_apply_filters('muscle_display_overrides',$v);}
