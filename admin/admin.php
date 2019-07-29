<?php

// =====================
// === BioShip Admin ===
// =====================

// --- no direct load ---
if (!defined('ABSPATH')) {exit;}

// ---------------------------
// === admin.php Structure ===
// ---------------------------
// == Admin Includes ==
// - Editor Metabox (metabox.php)
// -- Editor Metabox
// -- Quicksaves
// - Theme Tools (tools.php)
// -- Child Theme Install
// -- Clone Child Theme
// -- Theme Settings Transfers
// -- Backup / Restore
// -- Import / Export
// == Theme Debug Output ==
// == Admin Notice Helpers ==
// - Admin Notices Enqueue
// - Admin Notices Output
// == File System Helpers ==
// - Check WP Filesystem Credentials
// - Get Directory Filelist Recursively
// - Get SubDirectories List Recursively
// == Theme Update Checker ==
// - Load Theme Update Checker
// - Generate Updates Available Output
// - Output Theme Updates Available
// - Add Theme Update Notice
// == Freemius ==
// - Theme Settings for Freemius
// - Load Freemius for Themes
// - Customize Connect Message
// - Customize Update Connect Message
// - License Change Updater
// == Admin Scripts ==
// - Enqueue Media Scripts
// - Enqueue Thickbox
// - Add Thickbox Style and Script
// - Sticky Widgets on Widgets Page
// == Dashboard Widget ==
// - Load Dashboard Feed
// - Dashboard Feed Widget
// - Process RSS Feed
// == Admin Thumbnails ==
// - Check Admin Current Screen
// - Admin Thumbnail Columns
// - Admin Thumbnail Display Callback
// - Admin Thumbnail Column Width

// === Theme Admin Menus ===
// - Theme Tools Submenu
// - Theme Options Page Redirect
// - Change Options Framework Admin Menu
// - Add Appearance Submenu Item
// - Add Customize Advanced Submenu Item
// - Hack Theme Options Submenu to Top
// - Hack Theme Options Submenu URLs
// - Add Theme Documentation Submenu Item
// - maybe Shift Docs to Theme Menu
// - Set Support Link to New Window
// - Set Documentation Link to New Window
// - Remove Admin Footer
//
// === Theme Admin Page ===
// - Add Top Menu to Theme Admin Page
// - Theme Admin Top Menu with Filter Tabs
// - Admin Theme Options Wrap Close
// - Admin Theme Options Page Scripts
// - Admin Theme Options Page Styles
// - Floating Sidebar Output
// - AJAX QuickSave CSS
// - AJAX Refresh Titan Nonce
// - AJAX Session Timeout Alert
// === Theme Info Page ===
// - Theme Info Section
// - Welcome Message
// - Documentation Box
//
// == Build Selective Resources ==
// - Build Selective CSS and JS
// == Activation / Deactivation Actions ==
// * For Parent Theme
// * For Child Theme
// == Theme Plugins ==
// - Install the Titan Framework Plugin
// - Load TGM Plugin Activation
// - Recommended Plugins List
// ---------------------------

// Development TODOs
// -----------------
// * retest activation/deactivation functionality
// ? keep revisions of Theme Settings changes ?
// - add MarkDown parser for readme.txt Upgrade Notice checking
// - add a CSS quicksave menu item (dropdown box?) to admin navbar
// -- (with a "leave this page without saving" catch block?)
// - add Titan installation info and link to Theme Tools page

// 1.5.0: moved admin specific functions here from main functions.php
// 1.8.5: moved admin.php from theme root to /admin/ subdirectory
// 2.0.5: moved update checker here and added Freemius loading
// 2.1.1: separated Theme Tools to separate tools.php file
// 2.1.4: separated Editor Metabox to separate metabox.php file

// ------------------------------
// === Include Editor Metabox ===
// ------------------------------
// 2.1.4: moved to separate file
include(dirname(__FILE__).'/metabox.php');

// ---------------------------
// === Include Theme Tools ===
// ---------------------------
// (Backup / Restore / Import / Export)
// 2.1.1: moved Theme Tools to a separate file
$tools = bioship_file_hierarchy('file', 'tools.php', $vthemedirs['admin']);
if ($tools) {include($tools); define('THEMETOOLS', true);}


// --------------------------
// === Theme Debug Output ===
// --------------------------

// ----------------
// Script Debugging
// ----------------
// 2.0.5: use development scripts/styles if theme debugging in admin area
// TODO: check this is hooked early enough to work properly ?
if (THEMEDEBUG && !defined('SCRIPT_DEBUG')) {define('SCRIPT_DEBUG', true);}

// ---------------------------------------
// Debug Output of All Theme Option Values
// ---------------------------------------
if (!function_exists('bioship_admin_echo_setting_values')) {

 // 2.0.9: moved add action internally for consistency
 add_action('init', 'bioship_admin_echo_setting_values');

 function bioship_admin_echo_setting_values() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check for theme dump trigger ---
	// 2.0.9: check action trigger internally for consistency
	// 2.1.1: optimized trigger values check
	$valid = array('themeoptions', 'options', 'backupoptions', 'backup');
	if (!isset($_REQUEST['themedump']) || !in_array($_REQUEST['themedump'], $valid)) {return;}
	if (!current_user_can('edit_theme_options')) {return;}

	// --- set theme values and keys ---
	global $vtheme, $vthemename, $vthemesettings;
	$titankey = preg_replace('/\W/', '-', strtolower($vthemename)).'_options';
	$ofkey = preg_replace('/\W/', '_', strtolower($vthemename));

	// --- maybe output theme settings backup debug ---
	if ( ($_REQUEST['themedump'] == 'backupoptions') || ($_REQUEST['themedump'] == 'backup') ) {
		bioship_debug("Auto Backup (".THEMEKEY."_backup)", bioship_get_option(THEMEKEY.'_backup'));
		bioship_debug("User Backup (".THEMEKEY."_user_backup)", bioship_get_option(THEMEKEY.'_user_backup'));
	}

	// --- output theme settings debug ---
	bioship_debug("Theme Object", $vtheme);
	bioship_debug("Titan Framework Settings (".$titankey.")", maybe_unserialize(bioship_get_option($titankey)));
	bioship_debug("Options Framework Settings (".$ofkey.")", bioship_get_option($ofkey));
	bioship_debug("Theme Settings (".THEMEKEY.")", $vthemesettings);
	exit;
 }
}


// ----------------------------
// === Admin Notice Helpers ===
// ----------------------------

// ---------------------
// Admin Notices Enqueue
// ---------------------
// 2.0.2: added admin notice enqueue helper
if (!function_exists('bioship_admin_notices_enqueue')) {
 function bioship_admin_notices_enqueue() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// --- add admin notice actions ---
	if (!has_action('admin_notice', 'bioship_admin_notices')) {
		add_action('admin_notice', 'bioship_admin_notices');
	}
	if (!has_action('theme_admin_notice', 'bioship_admin_notices')) {
		add_action('theme_admin_notice', 'bioship_admin_notices');
	}
 }
}

// --------------------
// Admin Notices Output
// --------------------
// 2.0.2: added admin notice output helper
global $vthemeadminmessages; $vthemeadminmessages = array();
if (!function_exists('bioship_admin_notices')) {
 function bioship_admin_notices() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// --- admin notices output ---
	global $vthemeadminmessages;
	if (count($vthemeadminmessages) > 0) {
		foreach ($vthemeadminmessages as $message) {
			echo "<div class='update message' style='padding:3px 10px;margin:0 0 10px 0;'>".esc_attr($message)."</div>";
		}
	}
 }
}


// ---------------------------
// === File System Helpers ===
// ---------------------------

// -------------------------------
// Check WP Filesystem Credentials
// -------------------------------
// 1.8.0: for Child Theme creation to pass Theme Check
// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/
// ref: http://www.webdesignerdepot.com/2012/08/wordpress-filesystem-api-the-right-way-to-operate-with-local-files/
if (!function_exists('bioship_admin_check_filesystem_credentials')) {
 function bioship_admin_check_filesystem_credentials($url, $method, $context, $extrafields) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $wp_filesystem;
	if (empty($wp_filesystem)) {require_once(ABSPATH.'/wp-admin/includes/file.php'); WP_Filesystem();}
	$credentials = request_filesystem_credentials($url, $method, false, $context, $extrafields);
	if ($credentials === false) {return false;}
	if (!WP_Filesystem($credentials)) {request_filesystem_credentials($url, $method, true, $context, $extrafields); return false;}
	return true;
 }
}

// ----------------------------------
// Get Directory Filelist Recursively
// ----------------------------------
if (!function_exists('bioship_admin_get_directory_files')) {
 function bioship_admin_get_directory_files($dir, $recursive = true, $basedir = '') {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
	if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
	if ($basedir == '') {$basedir = realpath($dir).DIRSEP;}

	$files = scandir($dir);
	foreach ($files as $key => $value){
		if ( ($value != '.') && ($value != '..') ) {
			$path = realpath($dir.DIRSEP.$value);
			if (is_dir($path)) { // do not combine with the next line or
				if ($recursive) { // non-recursive file list includes subdirs
					$subdirresults = bioship_admin_get_directory_files($path, $recursive, $basedir);
					$results = array_merge($results, $subdirresults);
					unset($subdirresults);
				}
			} else { // strip basedir and add to subarray to separate list
				$subresults[] = str_replace($basedir, '', $path);
			}
		}
	}
	// merge the subarray to give list of files first, then subdirectory files
	if (count($subresults) > 0) {
		$results = array_merge($subresults, $results); unset($subresults);
	}
	return $results;
 }
}

// ---------------------------------
// Get SubDirectory List Recursively
// ---------------------------------
if (!function_exists('bioship_admin_get_directory_subdirs')) {
 function bioship_admin_get_directory_subdirs($dir, $recursive = true, $basedir = '') {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
	if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
	if ($basedir == '') {$basedir = realpath($dir).DIRSEP;}

	$files = scandir($dir);
	foreach ($files as $key => $value){
		if ( ($value != '.') && ($value != '..') ) {
			$path = realpath($dir.DIRSEP.$value);
			if (is_dir($path)) {
				$results[] = str_replace($basedir,'',$path);
				if ($recursive) {
					$subdirresults = bioship_admin_get_directory_subdirs($path, $recursive, $basedir);
					$results = array_merge($results, $subdirresults);
				}
			}
		}
	}
	return $results;
 }
}


// ----------------------------
// === Theme Update Checker ===
// ----------------------------

// -------------------------
// Load Theme Update Checker
// -------------------------
// [for non-WordPress.org version only]
// 2.0.5: moved from functions.php, hook to admin_init, remove is_admin check
if (!function_exists('bioship_theme_update_checker')) {

 // 2.0.7: moved add action internally for consistency
 add_action('admin_init', 'bioship_theme_update_checker');

 function bioship_theme_update_checker() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 1.8.0: use file hierarchy for theme update checker
	// 2.0.8: use constant to check for WordPress.org version
	if (THEMEWPORG) {

		// --- add sidebar options ---
		// 1.8.0: default sidebar ads to off for WordPress.Org guideline compliance
		// 1.8.5: switched this to single option array value
		if (!is_array(get_option('bioship_sidebar_options'))) {
			// 2.0.2: added first installed version record
			$sidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => 'checked',
				'installdate' => date('Y-m-d'), 'installversion' => THEMEVERSION);
			add_option('bioship_sidebar_options', $sidebaroptions);
		}

		// TODO: test this out for desired results
		// 1.8.5: maybe disable directory clearing to keep installed theme bundles ?
		// ref: http://wordpress.stackexchange.com/a/228798/76440
		// add_filter('bioship_package_options', 'bioship_avoid_deletion', 999);
		// if (!function_exists('bioship_avoid_deletion')) {
		//  function bioship_avoid_deletion($options) {
		//	 if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		//	 $hookextra = $options['hook_extra'];
		//	 if ( ($hookextra['type'] == 'theme') && ($hookextra['action'] == 'update') ) {
		//		if ($hookextra['theme'] == 'bioship') {
		//			$options['clear_destination'] = false;
		//			$options['abort_if_destination_exists'] = false;
		//		}
		//	 }
		//	 return $options;
		//  }
		// }

	} else {

		// --- load theme update checker ---
		// 1.5.0: use custom theme update server location
		$themeupdater = bioship_file_hierarchy('file', 'theme-update-checker.php', array('includes'));
		require_once($themeupdater);
		$jsoninfourl = THEMEHOMEURL.'/download/?action=get_metadata&slug=bioship';
		$updatechecker = new ThemeUpdateChecker('bioship', $jsoninfourl);

		// --- add sidebar options ---
		// 1.8.5: add default sidebar option values
		// 2.0.5: added first installed version record
		if (!is_array(get_option('bioship_sidebar_options'))) {
			$sidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => '',
				'installdate' => date('Y-m-d'), 'installversion' => THEMEVERSION);
			add_option('bioship_sidebar_options', $sidebaroptions);
		}
	}
 }
}

// ---------------------------------
// Generate Updates Available Output
// ---------------------------------
if (!function_exists('bioship_admin_theme_updates_available')) {
 function bioship_admin_theme_updates_available() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_version, $vthemename;
	$themedisplayname = THEMEDISPLAYNAME;
	// 2.0.5: use existing THEMESLUG constant
	$updatehtml = ''; $themeslug = THEMESLUG;

	// --- check user capability ---
	// TODO: recheck this code behaviour on multisite / non-multisite?
	if ( (!is_multisite() && !current_user_can('update_themes'))
		 || (is_multisite() && !current_user_can('manage_network_themes')) ) {
		// 2.0.5: add hidden capability section for admin notices
		// 2.0.9: removed parent theme output text
		// 2.0.9: change no update capability string
		$updatehtml .= '<!-- NO THEME UPDATE CAPABILITY -->';
		return $updatehtml;
	}

	// --- check theme updates transient ---
	// note: created from modified WP function get_theme_update_available
	$updatestransient = get_site_transient('update_themes');
	// 2.0.7: fix for possible empty transient theme update response
	// 2.0.9: object check for possible empty transient theme update response
	if (!is_object($updatestransient) || !property_exists($updatestransient, 'response')) {return $updatehtml;}
	$updates = $updatestransient->response;
	bioship_debug("Updates Transient", $updatestransient);

	// --- check for Child Theme update ---
	if (THEMECHILD) {
		if (isset($updates[$themeslug])) {
			// Special: allow for a user specified Child Theme update location
			// ie. by calling a new Theme Updater instance in their Child Theme
			$update = $updates[$themeslug];
			$url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($themeslug)), 'upgrade-theme_'.$themeslug);
			$message = __("Warning! Updating this Child Theme will lose any file customizations you have made! 'Cancel' to stop, 'OK' to update.",'bioship');
			$onclick = 'onclick="if (confirm(\''.esc_js($message).'\)) {return true;} return false;"';
			if (!empty($update['package'])) {
				$updatemessage = esc_attr(__( 'New Child Theme available.','bioship'));
				$updatemessage .= '<br><a href="'.esc_url($update['url']).'" title="'.esc_attr($themedisplayname).'" target="_blank" onclick="'.$onclick.'">'.esc_attr(__('Update to','bioship')).' '.$update['new_version'].'</a>';
				$updatehtml .= '<!-- THEME UPDATE AVAILABLE -->';
			}
		}

		// change the info so the parent theme updates are displayed next
		$theme = wp_get_theme($themeslug);
		$parenttheme = wp_get_theme(get_template($theme['Template']));
		$themeslug = $parenttheme['Stylesheet'];
		$themedisplayname = $parenttheme['Stylesheet'];
		$themeversion = $parenttheme['Version'];

		// 2.0.9: removed parent theme (framework) output text from here
		// $updatehtml .= esc_attr(__('Parent Theme','bioship')).':<br>';
		// $updatehtml .= esc_attr(__('BioShip Framework','bioship')).' v'.$themeversion.'<br>';
	}

	// --- output in either case ---
	// (ie. for child theme parent or base framework)
	if (isset($updates[$themeslug])) {
		$update = $updates[$themeslug];
		// 2.0.5: recompare versions (as if theme is updated manually transient will be old!)
		bioship_debug("Current Framework Version", $parenttheme['Version']);
		bioship_debug("Update Version", $update['new_version']);

		if (version_compare($parenttheme['Version'], $update['new_version'], '<')) { // '>
			$url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($themeslug)), 'upgrade-theme_'.$themeslug);
			$message = __("Updating this Theme Framework will lose any file customizations not in your Child Theme. 'Cancel' to stop, 'OK' to update.",'bioship');
			$onclick = 'onclick="if (confirm(\''.esc_js($message).'\')) {return true;} return false;"';
			if (!empty($update['package'])) {
				$updatemessage = esc_attr(__('New Framework version available!','bioship'));
				$updatemessage .= '<br><a href="'.esc_url($update['url']).'" title="'.esc_attr($themedisplayname).'" target="_blank">'.esc_attr(__('Update to','bioship')).' '.esc_attr($update['new_version']).'</a>';
				$updatehtml .= '<!-- THEME UPDATE AVAILABLE -->';
			}

			// --- check for theme Upgrade Notices ---
			// 2.0.9: check new theme version readme.txt for Upgrade Notices
			// if (THEMEWPORG) {$url = 'http://themes.svn.wordpress.org/bioship/'.$update['new_version'].'/readme.txt';}
			// else {$url = 'http://bioship.space/download/packages/readme.txt';}

			// $readme = wp_remote_get($url);
			// if (!is_wp_error($readme) && stristr($readme['body'], 'Upgrade Notice')) {
				// $markdown = bioship_file_hierarchy('file', 'markdown.php', $vthemedirs['includes']);
				// $parsereadme = bioship_file_hierarchy('file', 'parse-readme.php', $vthemedirs['includes']);
				// if ($markdown && $parsereadme) {include($markdown); include($parsereadme);}
				// $parser = new Automattic_Readme;
				// $data = $parser->parse_readme_contents($readme['body']);
				// if (isset($data['upgrade_notice'])) {
					// TODO: test for Upgrade Notice section
					// and if found, add to update HTML display
				// }
			// }
		}
	}

	return $updatehtml;
 }
}

// ------------------------------
// Output Theme Updates Available
// ------------------------------
// 2.0.5: for admin_notice section
if (!function_exists('bioship_admin_theme_updates_echo')) {
 function bioship_admin_theme_updates_echo() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$themeupdates = bioship_admin_theme_updates_available();
	// --- only show updates if there is user capability for it ---
	$nocapstring = '<!-- NO THEME UPDATE CAPABILITY -->';

	if ( ($themeupdates != '') && !strstr($themeupdates, $nocapstring) ) {
		// 2.0.9: only show in admin notices section if an update is actually available
		$updatestring = '<!-- THEME UPDATE AVAILABLE -->';
		if (strstr($themeupdates, $updatestring)) {
			global $wp_version;
			if (version_compare($wp_version,'3.8', '<')) {$nagclass = 'updated';} else {$nagclass = 'update-nag';} // '>
			$themeupdates = str_replace('<br>', ' ', $themeupdates);
			echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">';
			echo $themeupdates.'</div></font><br>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		}
	}
 }
}

// -----------------------
// Add Theme Update Notice
// -----------------------
// 2.0.5: maybe show updates available for admin pages
// 2.0.5: remove theme updates available notice from theme options pages
// 2.1.4: invert logic and separate from thickbox enqueue check
$optionspages = array('bioship-options', 'options-framework', 'theme-info');
if (!isset($_REQUEST['page']) || !in_array($_REQUEST['page'], $optionspages)) {
	add_action('admin_notices', 'bioship_admin_theme_updates_echo');
}


// ---------------------
// === Load Freemius ===
// ---------------------

// ---------------------------
// Theme Settings for Freemius
// ---------------------------
// 2.0.5: added Freemius to theme
// 2.0.9: moved page redirects to admin redirect section
if (!function_exists('bioship_admin_freemius')) {
 function bioship_admin_freemius() {
    if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

    global $wordquestplugins, $bioship_freemius;

    // --- get settings values ---
    $args = $wordquestplugins[THEMEPREFIX];
    $wporg = $args['wporg']; $hasplans = $args['hasplans'];
    if (isset($args['plan']) && ($args['plan'] == 'premium')) {$premium = true;} else {$premium = false;}
    $menu = array('contact' => $premium);

    if (!isset($bioship_freemius)) {

		// --- find Freemius loader ---
		// 2.0.9: use file hierarchy to locate Freemius
		$freemius = bioship_file_hierarchy('file', 'start.php', array('freemius'));
		if (!$freemius) {return false;} else {require_once($freemius);}

		// --- set different menu depending on framework ---
		if (THEMETITAN) {
			$menu['slug'] = 'bioship-options';
			$menu['first-path'] = 'admin.php?page=bioship-options&welcome=true';
		} elseif (THEMEOPT) {
			$menu['slug'] = 'theme-options';
			$menu['first-path'] = 'admin.php?page=options-framework&welcome=true';
		} else {
			$menu['slug'] = 'theme-tools';
			$menu['parent'] = array('slug' => 'themes.php');
			// 2.1.1: fix to mismatching page slug
			$menu['first-path'] = 'themes.php?page=theme-tools&welcome=true';
		}

		// --- set Freemius settings args ---
		$bioship_settings = array(
            'id'                	=> '816',
            'slug'              	=> 'bioship',
            'type'					=> 'theme',
            'public_key'        	=> 'pk_15fe43ecd7c5580cbcbb3484cfa9f',
            'is_premium'        	=> $premium,
            'has_premium_version' 	=> false,
            'has_addons'        	=> false,
            'has_paid_plans'    	=> $hasplans,
            'is_org_compliant'  	=> $wporg,
            'menu' 					=> $menu,
        );

        // --- initialize Freemius ---
    	$bioship_freemius = fs_dynamic_init($bioship_settings);

		// --- do Fremius loaded action ---
		// 2.0.9: signal to the SDK was initiated here
		bioship_do_action('bioship_loaded');
    }
    return $bioship_freemius;
 }
}

// ----------------------------
// Load Freemius SDK for Themes
// ----------------------------
// 2.0.9: initialize Freemius if PHP 5.4+
// 2.1.1: otherwise set to false to avoid undefined index warning
if (version_compare(PHP_VERSION, '5.4.0') >= 0) {$bioship_freemius = bioship_admin_freemius();} else {$bioship_freemius = false;}

// ----------------------------------
// Customize Freemius Connect Message
// ----------------------------------
if (!function_exists('bioship_admin_freemius_connect')) {
 // 'Never miss an important update - opt-in to our security and feature updates notifications, and non-sensitive diagnostic tracking with %4$s.'
 // 2.0.9: check object and method before adding filter
 if ($bioship_freemius && is_object($bioship_freemius) && method_exists($bioship_freemius, 'add_filter')) {
	 $bioship_freemius->add_filter('connect_message', 'bioship_admin_freemius_connect', WP_FS__DEFAULT_PRIORITY, 6);
 }
 function bioship_admin_freemius_connect($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link) {
	return sprintf(
		__fs('hey-x').'<br>'.
		__('Show your appreciation for %1$s by helping us improve it! Opt in to diagnostic tracking and receive security and feature notifications.', 'bioship'),
		$user_first_name, '<b>'.$plugin_title.'</b>', '<b>'.$user_login.'</b>', $site_link, $freemius_link
	);
 }
}

// -----------------------------------
// Customize Connect on Update Message
// -----------------------------------
//	'Please help us improve %1$s! If you opt-in, some data about your usage of %1$s will be sent to %4$s. If you skip this, that\'s okay! %1$s will still work just fine.'
if (!function_exists('bioship_admin_freemius_connect_update')) {
 // 2.0.9: check object and method before adding filter
 if ($bioship_freemius && is_object($bioship_freemius) && method_exists($bioship_freemius, 'add_filter')) {
	$bioship_freemius->add_filter('connect_message_on_update', 'bioship_admin_freemius_connect_update', WP_FS__DEFAULT_PRIORITY, 6);
 }
 function bioship_admin_freemius_connect_update($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link) {
	// 2.0.5: keep update message the same for now
	return bioship_admin_freemius_connect($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link);
 }
}

// ----------------------
// License Change Updater
// ----------------------
add_action('fs_after_license_change_bioship', 'bioship_admin_license_change', 10, 2);
if (!function_exists('bioship_admin_license_change')) {
 function bioship_admin_license_change($change, $plan) {
	global $bioship_freemius;
	// none, upgraded, downgraded, changed, cancelled, expired, trial_started, trial_expired
	if ($change != 'none') {
		// 2.0.7: use new prefixed current user function
		$current_user = bioship_get_current_user();
		$updateurl = add_query_arg('email', $current_user->user_email, THEMESUPPORT);
		$updateurl = add_query_arg('planchange', $change, $updateurl);
		$updateurl = add_query_arg('plan', $plan, $updateurl);
		echo '<iframe src="'.esc_url($updateurl).'" style="display:none;"></iframe>';
	}
 }
}

// ---------------------
// === Admin Scripts ===
// ---------------------

// ---------------------
// Enqueue Media Scripts
// ---------------------
// 2.0.9: ensure media scripts are enqueued
if (!function_exists('bioship_enqueue_media_files')) {

 add_action('admin_enqueue_scripts', 'bioship_enqueue_media_files');

 function bioship_enqueue_media_files() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if (!THEMETITAN) {return;}
	wp_enqueue_script('media-models');
	wp_enqueue_script('media-views');
	wp_enqueue_media();
 }
}

// ----------------
// Enqueue Thickbox
// ----------------
// 2.1.1: streamline thickbox enqueue check
$valid = array('bioship-options', 'options-framework', 'theme-info');
if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], $valid)) {
	add_action('admin_enqueue_scripts', 'bioship_admin_add_thickbox');
	// 2.0.5: remove theme updates available notice from theme options pages
	remove_action('admin_notices', 'bioship_admin_theme_updates_echo');
}

// ------------------------------
// Add Thickbox Script and Styles
// ------------------------------
// 2.0.5: enqueue thickbox for documentation box loading
if (!function_exists('bioship_admin_add_thickbox')) {
 function bioship_admin_add_thickbox() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	add_thickbox();
 }
}

// ------------------------------
// Sticky Widgets on Widgets Page
// ------------------------------
// ref: https://github.com/srikat/admin-sticky-widget-areas
// 2.1.3: moved here from stylesheet usage
if (!function_exists('bioship_admin_sticky_widget_areas')) {

 add_action('admin_footer', 'bioship_admin_sticky_widget_areas');

 function bioship_admin_sticky_widget_areas() {
	global $pagenow;
	if ($pagenow == 'widgets.php') {
		echo "<style>@media only screen and (min-width: 481px) {
		.widget-liquid-right {position: -webkit-sticky; position: sticky; top: 42px;} }</style>";
	}
 }
}


// ------------------------
// === Dashboard Widget ===
// ------------------------

// -------------------
// Load Dashboard Feed
// -------------------
// 2.1.4: moved from muscle.php
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
				wp_add_dashboard_widget('bioship', esc_attr(__('BioShip News','bioship')), 'bioship_muscle_bioship_dashboard_feed_widget');
			}
		}
	}
}

// ---------------------
// Dashboard Feed Widget
// ---------------------
// 1.9.5: added displayupdates argument
// 2.0.0: added displaylinks argument
// 2.1.4: moved from muscle.php
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
	if ($displayupdates) {echo admin_theme_updates_available();} // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho

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
	if ($feed != '') {echo $feed;} // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	else {echo esc_attr(__('Feed currently unavailable.','bioship')); delete_transient('bioship_feed');}
	echo "</div>";

 }
}

// ----------------
// Process RSS Feed
// ----------------
// 2.1.4: moved from muscle.php
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

// -------------------------------------
// Include CPTs in Dashboard 'Right Now'
// -------------------------------------
// 2.0.1: check themesettings internally to allow filtering
// 2.1.4: moved from muscle.php and reprefixed
if (!function_exists('bioship_admin_right_now_content_table_end')) {

 add_action('right_now_content_table_end', 'bioship_admin_right_now_content_table_end');

 function bioship_admin_right_now_content_table_end() {
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


// ------------------------
// === Admin Thumbnails ===
// ------------------------

// --------------------------
// Check Admin Current Screen
// --------------------------
add_action('current_screen', 'bioship_admin_current_screen_check');
function bioship_admin_current_screen_check() {
	$screen = get_current_screen(); $screenid = $screen->id;
	add_filter('manage_'.$screen->id.'_columns', 'bioship_admin_thumbnail_column', 5);
}

// -----------------------
// Admin Thumbnail Columns
// -----------------------
// 2.1.4: fix to admin thumbnail columns for multiple CPTs
// (was bioship_muscle_admin_post_thumbnail_column)
if (!function_exists('bioship_admin_thumbnail_column')) {

 function bioship_admin_thumbnail_column($cols){
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;

	// --- filter admin thumbnail column setting ---
	$thumbcols = $vthemesettings['adminthumbnailcols'];
	$thumbcols = bioship_apply_filters('admin_thumbnail_columns', $thumbcols);
	if (!$thumbcols || !is_array($thumbcols) || (count($thumbcols) == 0)) {return $cols;}
	bioship_debug("Admin Thumbnail Columns", $thumbcols);

	// --- get current screen ---
	// $screen = get_current_screen(); $screenid = $screen->id;

	// --- loop new multicheck setting ---
	foreach ($thumbcols as $cpt => $value) {
		if ( ($value == '1') && post_type_supports($cpt, 'thumbnail') ) {
			add_action('manage_'.$cpt.'_posts_custom_column', 'bioship_admin_display_thumbnail_column', 5, 2);
			add_action('admin_footer', 'bioship_admin_thumbnail_column_width');
			$cols['post_thumb'] = esc_attr(__('Thumbnail','bioship'));
		}
	}

	return $cols;
 }
}

// --------------------------------
// Admin Thumbnail Display Callback
// --------------------------------
// 2.1.4: changed function name for multiple CPT support
// (was bioship_muscle_display_post_thumbnail_column)
if (!function_exists('bioship_admin_display_thumbnail_column')) {
 function bioship_admin_display_thumbnail_column($col, $postid) {
 	bioship_debug("Admin Column", $col.' - '.$postid);
	if ($col == 'post_thumb') {
		// 2.1.4: maybe auto-regenerate thumbnail (if admin-thumb size is missing)
		bioship_regenerate_thumbnails($postid, 'admin-thumb');
		echo the_post_thumbnail('admin-thumb'); // phpcs:ignore WordPress.Security.OutputNotEscaped
	}
 }
}

// ----------------------------
// Admin Thumbnail Column Width
// ----------------------------
// 2.1.4: style width of thumbnail column (allow for filtered image size)
if (!function_exists('bioship_admin_thumbnail_column_width')) {
 function bioship_admin_thumbnail_column_width() {
	global $_wp_additional_image_sizes;
	foreach ($_wp_additional_image_sizes as $size => $data) {
		if ($size == 'admin-thumb') {$width = $data['width'] + 10;}
	}
	if ($width < 75) {$width = 75;}
	echo "<style>#post_thumb, .post_thumb {width:".esc_attr($width)."px;}</style>";
 }
}


// -------------------------
// === Theme Admin Menus ===
// -------------------------
// ...lots of nice hacky fixes here...

// -------------------
// Theme Tools Submenu
// -------------------
// for WordPress.org version theme tools menu (without Titan Framework)
// add only Theme Info page for access to info and backup/restore/export/import tools etc.
// 2.0.9: moved here from functions.php
if (!function_exists('bioship_add_theme_info_page')) {
 add_action('admin_menu','bioship_add_theme_info_page');
 function bioship_add_theme_info_page() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 1.9.5: change menu name to theme tools
	// 2.0.5: added missing translation wrappers
	// 2.0.9: only for Wordpress.org version without Titan or Options Framework
	if (!THEMEWPORG || THEMETITAN) {return;}
	add_theme_page(__('Theme Tools','bioship'), __('Theme Tools','bioship'), 'edit_theme_options', 'theme-info', 'bioship_admin_theme_info_page');
 }
}

// ---------------------------
// Theme Options Page Redirect
// ---------------------------
// 1.9.5: moved here from old Titan-specific function
// 2.1.1: check redirect trigger internally
if (!function_exists('bioship_admin_theme_options_page_redirect')) {

 add_action('admin_init', 'bioship_admin_theme_options_page_redirect');

 function bioship_admin_theme_options_page_redirect($updated = '') {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// ---- check conditions for page redirection ---
	// 2.0.5: make this redirect available for admin.php also
	if (!strstr($_SERVER['REQUEST_URI'], '/themes.php') && !strstr($_SERVER['REQUEST_URI'], '/admin.php')) {return;}
 	if (!isset($_REQUEST['page']) || ($_REQUEST['page'] != 'theme-options')) {return;}

 	// --- set theme options page url ---
	// 1.8.5: use add_query_arg here
	// 1.9.5: handle Titan or Options Framework or no framework
	// 2.0.9: use THEMEPREFIX constant for Titan Framework menu item
	$optionsurl = admin_url('admin.php');
	if (THEMETITAN) {$optionsurl = add_query_arg('page', THEMEPREFIX.'-options', $optionsurl);}
	elseif (THEMEOPT) {$optionsurl = add_query_arg('page', 'options-framework', $optionsurl);}
	else {$optionsurl = add_query_arg('page', 'theme-tools', $optionsurl);}

	// --- add optional arguments ---
	if (isset($_REQUEST['theme']) && ($_REQUEST['theme'] != '')) {
		$optionsurl = add_query_arg('theme', $_REQUEST['theme'], $optionsurl);
	}
	if ($updated != '') {$optionsurl = add_query_arg('updated', $updated, $optionsurl);}

	// --- redirect and exit ---
	wp_redirect($optionsurl); exit;
 }
}

// -----------------------------------
// Change Options Framework Admin Menu
// -----------------------------------
// [Options Framework only]
if (!function_exists('bioship_admin_options_default_submenu')) {

 // note: this filter is priority 0 so added filters are applied later
 add_filter('optionsframework_menu', 'bioship_admin_options_default_submenu', 0);

 function bioship_admin_options_default_submenu($menu) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- Options Framework only ---
	if (!THEMEOPT) {return;}

	// --- only change if using as the active parent theme ---
	if (!THEMECHILD) {
		$menu['page_title'] = esc_attr(__('BioShip Options','bioship'));
		$menu['menu_title'] = esc_attr(__('BioShip Options','bioship'));
	}
	return $menu;
 }
}

// ---------------------------
// Add Appearance Submenu Item
// ---------------------------
// creates Appearance -> Theme Options submenu item (Titan Framework only)
if (!function_exists('bioship_admin_theme_options_submenu')) {

 add_action('admin_menu', 'bioship_admin_theme_options_submenu');

 function bioship_admin_theme_options_submenu() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- only for Titan Framrwork ---
 	if (!THEMETITAN) {return;}

	// 2.0.7: added missing translation wrappers
	// 2.0.9: use plain dummy function instead of declaring a mew one
	add_theme_page(__('Theme Options','bioship'), __('Theme Options','bioship'), 'edit_theme_options', 'theme-options', 'bioship_dummy_function');
 }
}

// -----------------------------------
// Add Customize Advanced Submenu Item
// -----------------------------------
// 1.9.9: add extra menu item for split Customizer options
if (!function_exists('bioship_admin_theme_options_advanced')) {

 // 2.0.9: move add action internally for consistency
 add_action('admin_menu', 'bioship_admin_theme_options_advanced');

 function bioship_admin_theme_options_advanced() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 2.1.1: check WordPress.Org condition internally
	if (!THEMEWPORG) {return;}

	// 2.0.7: added missing apply_filters function prefix
	$splitoptions = bioship_apply_filters('options_customizer_split_options', true);
	if ($splitoptions) {
		// 2.0.7: change to add_theme_page and add missing translation wrappers
		add_theme_page(__('Customize Advanced','bioship'), __('Advanced Options','bioship'), 'edit_theme_options', 'customize.php?options=advanced');
	}
 }
}

// ---------------------------------
// Hack Theme Options Submenu to Top
// ---------------------------------
// (Appearance submenu for Options and Titan Framework)
if (!function_exists('bioship_admin_theme_options_position')) {

 add_action('admin_head', 'bioship_admin_theme_options_position');

 function bioship_admin_theme_options_position() {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 2.1.2: bug out if wordpress.org version
	if (THEMEWPORG) {return;}

	global $menu, $submenu;
	if (THEMEDEBUG) {bioship_debug("Admin Menu", $menu);}

	// 2.0.0: fix for undefined variable warning (dospliceb)
	$i = 0; $dosplice = $dospliceb = false;
	if (isset($submenu['themes.php'])) {

		foreach ($submenu['themes.php'] as $submenukey => $values) {

			$pageslug = $values[2];

			// --- get themes.php submenu position ---
			// 1.8.5: added this shifting
			if ($pageslug == 'themes.php') {$themesposition = $submenukey; $themessubmenu = $values;}

			// --- get theme options submenu page menu position ---
			// 1.8.0: do same for Titan theme options submenu page
			// 1.8.5: allow for theme test drive link overrides as well
			if ( ($pageslug == 'options-framework') || (strstr($pageslug, 'page=options-framework'))
			  || ($pageslug == 'theme-options') || (strstr($pageslug, 'page=theme-options')) ) {
				unset($submenu['themes.php'][$submenukey]);
				$newposition = bioship_apply_filters('muscle_theme_options_position', '1');
				if (isset($submenu['themes.php'][$newposition])) {
					// --- need to shift the array to insert ---
					$dosplice = true; $j = 0; $themesettingsvalues = $values;
					foreach ($submenu['themes.php'] as $key => $value) {
						if ($key == $newposition) {$position = $j;}
						$j++;
					}
				} else {
					// --- just set to re-insert at the new position ---
					$submenu['themes.php'][$newposition] = $values;
					$submenuthemes = $submenu['themes.php'];
					ksort($submenuthemes);
					$submenu['themes.php'] = $submenuthemes;
				}
			}

			// --- maybe rename Customizer link ---
			// 1.8.0: no longer remove the Customize option (fixed)
			// 1.8.5: maybe rename Customize to Live Preview
			if ($values[1] == 'customize') {
				// 2.0.7: fix for undefined index for no framework
				$customizerposition = $submenukey;
				if (THEMETITAN || THEMEOPT) {
					$submenu['themes.php'][$submenukey][0] = esc_attr(__('Live Preview','bioship'));
				}
			}

			// --- get advanced customizer menu position ---
			// 1.9.9: shift the advanced options (customizer) item position
			if (strstr($pageslug, '?options=advanced')) {
				unset($submenu['themes.php'][$submenukey]);
				$advposition = $customizerposition + 1;
				// 2.0.7: fix undefined variable for no framework
				if (isset($newposition) && isset($submenu['themes.php'][$newposition])) {
					$dospliceb = true; $k = 0; $advancedvalues = $values;
					foreach ($submenu['themes.php'] as $key => $value) {
						if ($key == $advposition) {$advancedposition = $k;}
						$k++;
					}
				} else {
					$submenu['themes.php'][($customizerposition+1)] = $values;
					$submenuthemes = $submenu['themes.php'];
					ksort($submenuthemes);
					$submenu['themes.php'] = $submenuthemes;
				}
			}

			$lastposition = $submenukey; $i++;
		}

		// --- shift
		if ($dosplice) {
			// --- shift the $submenu array, maintaining keys ---
			$submenuthemes = $submenu['themes.php']; $newthemesa = array();
			$submenuthemesa = array_slice($submenuthemes, 0, $position, true);
			$submenuthemesb = array_slice($submenuthemes, $position, count($submenuthemes), true);
			foreach ($submenuthemesb as $key => $value) {$newthemesa[($key+1)] = $value;}
			$submenuthemesa[$newposition] = $themesettingsvalues;
			$submenuthemes = $submenuthemesa + $newthemesa;
			$submenu['themes.php'] = $submenuthemes;
		}

		// --- shift Customizer advanced menu ite ---
		// 1.9.9: repeat for advanced options item
		if ($dospliceb) {
			// --- shift the $submenu array, maintaining keys ---
			$submenuthemes = $submenu['themes.php']; $newthemesb = array();
			$submenuthemesa = array_slice($submenuthemes, 0, $advancedposition, true);
			$submenuthemesb = array_slice($submenuthemes, $advancedposition, count($submenuthemes), true);
			foreach ($submenuthemesb as $key => $value) {$newthemesb[($key+1)] = $value;}
			$submenuthemesa[$advposition] = $advancedvalues;
			$submenuthemes = $submenuthemesa + $newthemesb;
			$submenu['themes.php'] = $submenuthemes;
		}

		// 1.8.5: shift the themes submenu item
		if ($themesposition != '') {
			unset($submenu['themes.php'][$themesposition]);
			$submenu['themes.php'][$submenukey+1] = $themessubmenu;
		}

		if (THEMEDEBUG) {
			bioship_debug("SubMenu", $submenu);
			bioship_debug("themes.php position", $themesposition);
			bioship_debug("last position", $i);
		}

	}
 }
}

// -------------------------------
// Hack Theme Options Submenu URLs
// -------------------------------
// (for Theme Test Drive compatibility)
if (!function_exists('bioship_admin_themetestdrive_options')) {
 function bioship_admin_themetestdrive_options() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 1.8.5: bug out if not test driving via querystring
	// 2.0.9: added check to prevent undefined index warning
	if (!isset($_REQUEST['theme']) || (trim($_REQUEST['theme']) == '')) {return;}

	global $menu, $submenu;
	$tdtheme = trim($_REQUEST['theme']);

	// --- append theme to submenu URL ---
	foreach ($submenu['themes.php'] as $submenukey => $values) {
		// --- hack Options Framework submenu URL ---
		if ($submenu['themes.php'][$submenukey][2] == 'options-framework') {
			$submenu['themes.php'][$submenukey][2] = 'themes.php?page=options-framework&theme='.$tdtheme;
			break;
		}
		// --- hack Titan Theme Options submenu URL ---
		if ($submenu['themes.php'][$submenukey][2] == 'theme-options') {
			$submenu['themes.php'][$submenukey][2] = 'themes.php?page=theme-options&theme='.$tdtheme;
			break;
		}
	}

	// --- append theme to options top menu URL ---
	// 1.8.0: hack Titan Framework Admin URL
	$optionsmenukey = THEMEPREFIX.'-options';
	foreach ($menu as $priority => $values) {
		// 1.8.5: fix to Titan Theme options admin menu URL link
		if ($values[2] == $optionsmenukey) {
			$menu[$priority][2] = 'admin.php?page='.$optionsmenukey.'&theme='.$tdtheme; break;
		}
	}

	// debug points
	// 2.0.9: use simpler debug function
	bioship_debug("Admin Menu", $menu);
	bioship_debug("Admin SubMenu", $submenu);

 }
}

// ------------------------------------
// Add Theme Documentation Submenu Item
// ------------------------------------
// 2.0.7: add documentation link to admin menu
if (!function_exists('bioship_admin_documentation_menu')) {

 add_action('admin_menu', 'bioship_admin_documentation_menu', 12);

 function bioship_admin_documentation_menu() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// 2.0.9: do not add documentation submenu item for wordpress.org version
 	if (THEMEWPORG) {return;}

	// --- add documentation submenu link ---
 	// 2.0.9: fix to documentation link and added dummy function
	add_theme_page(__('Documentation','bioship'), __('Documentation','bioship'), 'edit_theme_options', 'bioship-documentation', 'bioship_dummy_function');
 }
}

// ------------------------------
// maybe Shift Docs to Theme Menu
// ------------------------------
// 2.0.7: added this shift (for Titan framework menu)
if (!function_exists('bioship_admin_documentation_shift')) {

 add_action('admin_head', 'bioship_admin_documentation_shift', 9);

 function bioship_admin_documentation_shift() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- only for Titan Framework ---
 	// 2.0.9: no need to check this for Wordpress.org version
	if (THEMEWPORG || !THEMETITAN) {return;}

	global $submenu;
	if ( (array_key_exists('bioship-options', $submenu))
	  && (array_key_exists('themes.php', $submenu)) ) {
		foreach ($submenu[THEMEPREFIX.'-options'] as $key => $values) {
			// 2.0.9: wrap support link in div tags for jquery targeting to new window
			if ($values[2] == THEMEPREFIX.'-options-wp-support-forum') {
				$values[0] = "<div id='".THEMEPREFIX."-support-link'>".$values[0]."</div>";
				add_action('admin_footer', 'bioship_support_link_external');
			}
			$lastkey = $key + 1;
		}
		foreach ($submenu['themes.php'] as $key => $values) {
			if ($values[2] == THEMEPREFIX.'-documentation') {
				// 2.0.9: wrap doc link in div tags for jquery targeting (to new window)
				$values[0] = "<div id='".THEMEPREFIX."-doc-link'>".$values[0]."</div>";
				add_action('admin_footer', 'bioship_documentation_link_external');

				// 2.0.9: fix to link URL when changing to separate menu
				$values[2] = 'admin.php?page='.THEMEPREFIX.'-documentation';
				$submenu[THEMEPREFIX.'-options'][$lastkey] = $values;
				unset($submenu['themes.php'][$key]);
			}
		}
	}
 }
}

// ------------------------------
// Set Support Link to New Window
// ------------------------------
// 2.0.9: added this jquery link target tweak
if (!function_exists('bioship_support_link_external')) {
 function bioship_support_link_external() {
	echo "<script>jQuery(document).ready(function() {
		jQuery('#".esc_js(THEMEPREFIX)."-support-link').parent().attr('target','_blank');
	});</script>";
 }
}

// ------------------------------------
// Set Documentation Link to New Window
// ------------------------------------
// 2.0.9: added this jquery link target tweak
if (!function_exists('bioship_documentation_link_external')) {
 function bioship_documentation_link_external() {
	echo "<script>jQuery(document).ready(function() {
		jQuery('#".esc_js(THEMEPREFIX)."-doc-link').parent().attr('target','_blank');
	});</script>";
 }
}

// -------------------
// Remove Admin Footer
// -------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('bioship_admin_remove_admin_footer')) {

 add_filter('admin_footer_text', 'bioship_admin_remove_admin_footer');

 function bioship_admin_remove_admin_footer($footertext) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$footertext = bioship_apply_filters('muscle_admin_footer_text', '');
	return $footertext;
 }
}


// ------------------------
// === Theme Admin Page ===
// ------------------------

// --------------------------------
// Add Top Menu to Theme Admin Page
// --------------------------------
if (isset($_REQUEST['page']) && function_exists('add_action')) {

	// --- Add Top Menu section to Admin Page [Titan Framework] ---
	if ($_REQUEST['page'] == 'bioship-options') {
		add_action('tf_admin_page_before_'.$vthemename, 'bioship_admin_theme_options_page');

		// note: other possible sections of the Titan Framework admin page
		// add_action('tf_admin_page_table_start_'.$vthemename, '');
		// add_action('tf_admin_page_end_'.$vthemename, '');
		// add_action('tf_admin_page_after_'.$vthemename, '');
	}

	// --- Add Top Menu section to Admin Notices [Options Framework] ---
	if ($_REQUEST['page'] == 'options-framework') {
		add_action('all_admin_notices', 'bioship_admin_theme_options_page', 99);
	}

	// Page Redirects
	// --------------

	// --- External Redirect for Support Forum ---
	// 2.0.9: moved here from inside Freemius init function
	// 2.0.9: fixed menu link prefix (by adding -options)
	// 2.1.1: removed unneeded /quest/ base prefix from URL
	if ($_REQUEST['page'] == THEMEPREFIX.'-options-wp-support-forum') {
		if (!function_exists('wp_redirect')) {include(ABSPATH.WPINC.'/pluggable.php');}
		wp_redirect(THEMESUPPORT.'/quest-category/theme-support/'); exit;
	}

	// --- External Redirect for Documentation ---
	// 2.0.7: added this redirection link
	// 2.0.9: moved here from inside Freemius init function
	if ($_REQUEST['page'] == THEMEPREFIX.'-documentation') {
		if (!function_exists('wp_redirect')) {include(ABSPATH.WPINC.'/pluggable.php');}
		wp_redirect(THEMEHOMEURL.'/documentation/'); exit;
	}

}

// -------------------------------------
// Theme Admin Top Menu with Filter Tabs
// -------------------------------------
if (!function_exists('bioship_admin_theme_options_page')) {
 function bioship_admin_theme_options_page() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_version, $vthemesettings, $vthemename, $vthemedirs;

	// --- set nag class ---
	if (version_compare($wp_version,'3.8', '<')) {$nagclass = 'updated';} else {$nagclass = 'update-nag';} // '>

	// --- admin page javascript ---
	// 1.8.5: improved extend 'layer' behaviour
	echo "<script>
	if (document.getElementById('layertab')) {document.getElementById('prevlayer').value = document.getElementById('layertab').value;}
	if (document.getElementById('".esc_js($vthemename)."_layertab')) {
		document.getElementById('prevlayer').value = document.getElementById('".esc_js($vthemename)."_layertab').value;
	}
	function showhideextend() {
		if (document.getElementById('extendwrapper').style.display == 'none') {showextend();}
		else {prevlayer = document.getElementById('prevlayer').value;
			if (prevlayer == 'alloptions') {showalllayers();} else {switchlayers(prevlayer);}
		}
	}
	function showextend() {
		jQuery(function(\$) {
			\$('#alloptions').removeClass('activefilter');
			\$('#skeletonoptions').removeClass('activefilter');
			\$('#muscleoptions').removeClass('activefilter');
			\$('#skinoptions').removeClass('activefilter');
			\$('#extendoptions').addClass('activefilter');
			\$('#extendwrapper').fadeIn();
		});
		if (document.getElementById('layertab')) {
			prevlayer = document.getElementById('layertab').value;
			document.getElementById('layertab').value = 'extend';
		}
		if (document.getElementById('".esc_js($vthemename)."_layertab')) {
			prevlayer = document.getElementById('".esc_js($vthemename)."_layertab').value;
			document.getElementById('".esc_js($vthemename)."_layertab').value = 'extend';
		}
	}

	function showalllayers() {
		document.getElementById('prevlayer').value = 'alloptions';
		jQuery(function(\$) {
			\$('#extendwrapper').fadeOut();
			\$('#extendoptions').removeClass('activefilter');
			\$('.skeleton').css('display',''); showhidetabs('skeleton','show');
			\$('.muscle').css('display',''); showhidetabs('muscle','show');
			\$('.skin').css('display',''); showhidetabs('skin','show');
			\$('#alloptions').addClass('activefilter');
			\$('#skeletonoptions').removeClass('activefilter');
			\$('#muscleoptions').removeClass('activefilter');
			\$('#skinoptions').removeClass('activefilter');
			\$('.nav-tab').css('font-size','15px');
			if (document.getElementById('layertab')) {document.getElementById('layertab').value = 'alloptions';}
			if (document.getElementById('".esc_js($vthemename)."_layertab')) {
				document.getElementById('".esc_js($vthemename)."_layertab').value = 'alloptions';
			}
		});
	}
	function switchlayers(optionid) {
		document.getElementById('prevlayer').value = optionid;
		jQuery(function(\$) {
			\$('#extendwrapper').fadeOut();
			\$('#extendoptions').removeClass('activefilter');
			\$('#alloptions').removeClass('activefilter');
			\$('.skeleton').css('display','none'); \$('#skeletonoptions').removeClass('activefilter');
			\$('.muscle').css('display','none'); \$('#muscleoptions').removeClass('activefilter');
			\$('.skin').css('display','none'); \$('#skinoptions').removeClass('activefilter');
			showhidetabs('skeleton','hide'); showhidetabs('muscle','hide'); showhidetabs('skin','hide');
			if (optionid == 'skeleton') {showhidetabs('skeleton','show'); \$('.skeleton').css('display',''); \$('#skeletonoptions').addClass('activefilter');}
			if (optionid == 'muscle') {showhidetabs('muscle','show'); \$('.muscle').css('display',''); \$('#muscleoptions').addClass('activefilter');}
			if (optionid == 'skin') {showhidetabs('skin','show'); \$('.skin').css('display',''); \$('#skinoptions').addClass('activefilter');}
			\$('.nav-tab').css('font-size','20px');
			if (document.getElementById('layertab')) {document.getElementById('layertab').value = optionid;}
			if (document.getElementById('".esc_js($vthemename)."_layertab')) {
				document.getElementById('".esc_js($vthemename)."_layertab').value = optionid;
			}
		});
	}
	function showhidetabs(optionid,showhide) {
		jQuery(function(\$) {
			tabref = optionid+'-tab';
			if (showhide == 'show') {\$('.'+tabref).fadeIn();}
			if (showhide == 'hide') {\$('.'+tabref).fadeOut();}
		});
	}
	</script>";

	// set Admin Action URL
	// --------------------
	// 1.8.0: form action URL for Options or Titan Framework
	// 1.9.5: use add_query_arg for query arguments
	// 2.1.1: use THEMEPREFIX for Titan admin page slug
	$actionurl = admin_url('admin.php');
	if (THEMETITAN) {$actionurl = add_query_arg('page', THEMEPREFIX.'-options', $actionurl);}
	else {$actionurl = add_query_arg('page', 'options-framework', $actionurl);}

	// maybe File System Credentials Form
	// ----------------------------------
	// 1.8.0: checks permissions for creating Child Theme
	// 1.9.5: check permission for creating clones also
	if (isset($_REQUEST['newchildname']) || isset($_REQUEST['newclonename'])) {
		// 2.0.5: use actionurl for url already calculated
		$method = ''; $context = false;
		$extrafields = array('newchildname'); // not sure if really needed
		$filesystemcheck = bioship_admin_check_filesystem_credentials($actionurl, $method, $context, $extrafields);
	}

	// WordQuest Floating Sidebar
	// --------------------------
	// 1.8.5: call sidebar here directly (floats right)
	// TODO: maybe use StickyKit sidebar instead ?
	bioship_admin_floating_sidebar();

	// CSS QuickSave Form/Frame
	// ------------------------
	$adminajax = admin_url('admin-ajax.php');
	echo '<div id="quicksavecsswrapper" style="display:none;">';
	echo '<form id="quicksavecssform" action="'.esc_url($adminajax).'" method="post" target="quicksavecssframe">';
	echo '<input type="hidden" name="action" value="quicksave_css_theme_settings">';
	echo '<input type="hidden" name="theme" value="'.esc_attr($vthemename).'">';
	echo '<input type="hidden" id="quicksavecss" name="quicksavecss" value="">';
	wp_nonce_field('quicksave_css_'.$vthemename);
	echo '</form></div>';
	echo '<iframe name="quicksavecssframe" id="quicksavecssframe" style="display:none;" src="javascript:void(0);"></iframe>';

	// Opening Div for Theme Options
	// -----------------------------
	// 1.8.5: added center wrap for better floats
	bioship_html_comment('#themeoptionswrap');
	echo '<div id="themeoptionswrap">';
	// 1.8.5: hidden input for extend layer switching
	echo "<input type='hidden' name='prevlayer' id='prevlayer' value=''>";

	// Theme Options Page Header
	// -------------------------
	echo '<br><div id="themeoptionsheader"><table><tr>';

		// Theme Logo
		// ----------
		$themelogo = bioship_file_hierarchy('url', 'theme-logo.png', $vthemedirs['image']);
		if ($themelogo) {
			// 2.1.4: add vertical style align to cell
			echo '<td style="vertical-align:top;"><img src="'.esc_url($themelogo).'"></td>';
		}
		echo '<td width="10"></td>';

		// Theme Name and Version
		// ----------------------
		echo '<td style="vertical=align:top;">';

			echo '<table id="themedisplayname" cellpadding="0" cellspacing="0"><tr height="40">';

				echo '<td style="vertical-align:middle;">';
					echo '<h2 style="margin:5px 0;">'.esc_attr(THEMEDISPLAYNAME).'</h2>';
				echo '</td><td width="10"></td><td>';
				// 2.0.5: fix to maybe display Child Theme Version constant
				if (THEMECHILD) {echo '<h3 style="margin:5px 0;">v'.esc_attr(THEMECHILDVERSION).'</h3>';}
				else {echo 'v'.esc_attr(THEMEVERSION);}
				echo '</td></tr>';

				// Small Theme Links
				// -----------------
				// TODO: Docs link could be a thickbox popup link to /bioship/admin/docs.php ?
				echo '<tr height="40"><td colspan="3" align="center" style="vertical-align:middle;">';
				// 2.1.0: maybe output parent theme framework version
				if (THEMECHILD) {
					// 2.1.2: use existing constant for parent theme version
					echo esc_attr(__('Parent Theme','bioship')).': ';
					echo esc_attr(__('BioShip Framework','bioship'));
					echo ' v'.esc_attr(THEMEVERSION)."<br>";
				}
				echo '<font style="font-size:11pt;"><a href="'.esc_url(THEMEHOMEURL.'/news/').'" class="frameworklink" title="BioShip Theme Framework News" target=_blank>'.esc_attr(__('News','bioship')).'</a>';
				echo ' | <a href="'.esc_url(THEMEHOMEURL.'/documentation/').'" class="frameworklink" title="BioShip Theme Framework Documentation" target=_blank>'.esc_attr(__('Docs','bioship')).'</a>';
				// echo ' | <a href="'.esc_url(THEMEHOMEURL.'/faq/').'" class="frameworklink" title="BioShip Theme Framework Frequently Asked Questions" target=_blank>'.esc_attr(__('FAQ','bioship')).'</a>';
				echo ' | <a href="'.esc_url(THEMEHOMEURL.'/development/').'" class="frameworklink" title="BioShip Theme Framework Development" target=_blank>'.esc_attr(__('Dev','bioship')).'</a>';
				// echo ' | <a href="'.esc_url(THEMEHOMEURL.'/extensions/').'" class="frameworklink" title="BioShip Theme Framework Extensions" target=_blank>'.esc_attr(__('Extend','bioship')).'</a>';
				echo '</font></center></td>';

			echo '</tr></table>';

		echo '</td><td width="10"></td>';
		echo '<td align="center">';

		// One-Click Child Theme Install/Clone Forms
		// -----------------------------------------
		// 1.9.5: handle calls to new child clone here too
		$newchild = $newclone = false;
		if (isset($_REQUEST['newchildname']) || isset($_REQUEST['newclonename'])) {
			// --- check nonce for clone / install ---
			// 1.8.5: check wp nonce form field
			if (isset($_REQUEST['newchildname'])) {check_admin_referer('bioship_child_install');}
			if (isset($_REQUEST['newclonename'])) {check_admin_referer('bioship_child_clone');}

			// --- do child theme clone / install  ---
			// 1.8.0: install only if WP Filesystem credentials checked out
			if ($filesystemcheck) {
				if (isset($_REQUEST['newchildname'])) {
					$message = bioship_admin_do_install_child();
					if (strstr($message, '<!-- SUCCESS -->')) {$newchild = true;}
				} elseif (isset($_REQUEST['newclonename'])) {
					$message = bioship_admin_do_install_clone();
					if (strstr($message, '<!-- SUCCESS -->')) {$newclone = true;}
				}
			} else {$message = esc_attr(__('Check your file system owner/group permissions!','bioship'));}
			if ($message != '') {
				// 2.1.4: escape HTML internally in functions rather than here
				// 2.1.4: added max-width to prevent message size overflow
				echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;max-width:580px;">';
					echo $message; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo '</div><br>';
			}
		}

		// --- set default new child theme theme ---
		// 1.9.5: added 'Clone' to new child name for existing child themes
		// 2.1.1: use THEMECHILD constant instead of is_child_theme()
		if (!THEMECHILD) {$newchildname = "BioShip Child";} else {$newclonename = THEMEDISPLAYNAME.' Clone';}

		// 2.1.1: use THEMECHILD constant instead of is_child_theme()
		if (!THEMECHILD && !$newchild) {

			if (isset($_REQUEST['newchildname']) && ($_REQUEST['newchildname'] != '')) {
				$newchildname = $_REQUEST['newchildname'];
			}

			// Child Theme Creation Form
			// -------------------------
			echo '<form action="'.esc_url($actionurl).'" method="post">';
			// 1.8.5: added nonce field to form
			wp_nonce_field('bioship_child_install');
			// 1.9.5: in case of theme driving a theme?
			if (isset($_REQUEST['theme']) && ($_REQUEST['theme'] != '')) {
				echo '<input type="hidden" name="theme" value="'.esc_attr($_REQUEST['theme']).'">';
			}
			echo '<table><tr><td align="left">';
			echo '<font style="font-size:11pt;line-height:22px;"><b>'.esc_attr(__('Create Child Theme','bioship')).':</b></font></td>';
			echo '<td width="10"></td><td><input type="text" name="newchildname" style="font-size:11pt; width:120px;" value="'.esc_attr($newchildname).'"></td>';
			echo '<td width="10"></td><td><input type="submit" class="button-primary" title="'.esc_attr(__('Note: alphanumeric and spaces only.','bioship')).'" value="'.esc_attr(__('Create','bioship')).'"></td>';
			echo '</td></tr></table></form>';

		} elseif (THEMECHILD && !$newclone) {

			if (isset($_REQUEST['newclonename']) && ($_REQUEST['newclonename'] != '')) {
				$newclonename = $_REQUEST['newclonename'];
			}

			// Clone Child Theme Form
			// ----------------------
			// 1.9.5: added child theme clone form
			echo '<form action="'.esc_url($actionurl).'" method="post">';
			wp_nonce_field('bioship_child_clone');
			// 2.0.5: use existing THEMESLUG constant here
			echo '<input type="hidden" name="clonetheme" value="'.esc_attr(THEMESLUG).'">';
			echo '<table><tr><td align="left">';
			echo '<font style="font-size:11pt;line-height:22px;"><b>'.esc_attr(__('Clone Child Theme to','bioship')).':</b></font></td>';
			echo '<td width="10"></td><td><input type="text" name="newclonename" style="font-size:11pt; width:120px;" value="'.esc_attr($newclonename).'"></td>';
			echo '<td width="10"></td><td><input type="submit" class="button-primary" title="'.esc_attr(__('Note: alphanumeric and spaces only.','bioship')).'" value="'.esc_attr(__('Clone','bioship')).'"></td>';
			echo '</td></tr></table></form>';
		}

		// Theme Options Filter Buttons
		// ----------------------------
		echo '<table style="float:left; margin-top:5px;"><tr>';
		// Theme Home (extend) button
		echo '<td><div id="extendoptions" class="filterbutton" onclick="showhideextend();">';
		echo '<a href="javascript:void(0);">'.esc_attr(__('Info','bioship')).'</a>';
		echo '</div></td>';
		// Skin filter button
		echo '<td width="10"></td><td><div id="skinoptions" class="filterbutton activefilter" onclick="switchlayers(\'skin\');">';
		echo '<a href="javascript:void(0);">'.esc_attr(__('Skin','bioship')).'</a>';
		echo '</div></td>';
		// Muscle filter button
		echo '<td width="10"></td><td><div id="muscleoptions" class="filterbutton" onclick="switchlayers(\'muscle\');">';
		echo '<a href="javascript:void(0);">'.esc_attr(__('Muscle','bioship')).'</a>';
		echo '</div></td>';
		// Skeleton filter button
		echo '<td width="10"></td><td><div id="skeletonoptions" class="filterbutton" onclick="switchlayers(\'skeleton\');">';
		echo '<a href="javascript:void(0);">'.esc_attr(__('Skeleton','bioship')).'</a>';
		echo '</div></td>';
		// ALL options button
		echo '<td width="10"></td><td><div id="alloptions" class="filterbutton" onclick="showalllayers();">';
		echo '<a href="javascript:void(0);">'.esc_attr(__('ALL','bioship')).'</a>';
		echo '</div></td>';
		echo '</tr></table>';

	echo '</td></tr></table></div><br>';

	// maybe output Theme Updates available
	// ------------------------------------
	// 1.9.6: separate line for theme update alert
	$themeupdates = bioship_admin_theme_updates_available();
	if ($themeupdates != '') {
		$themeupdates = str_replace('<br>', ' ', $themeupdates);
		echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">'.$themeupdates.'</div></font><br>';
	}

	// Theme Admin Notices
	// -------------------
	// 1.9.5: added this theme action since main admin_notices are now boxed
	// 2.0.7: fix to action name typo (is singular not plural)
	ob_start(); bioship_do_action('theme_admin_notice');
	$themenotices = ob_get_contents(); ob_end_clean();
	if ($themenotices != '') {
		echo '<div style="float:none; clear:both;"></div><br>';
		echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;">'.$themenotices.'</div>';
	}

	// Theme Info Section
	// ------------------
	if ($vthemesettings['layertab'] != '') {$hide = ' style="display:none;"';} else {$hide = '';}
	echo '<div id="extendwrapper" class="wrap"'.$hide.'>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		bioship_admin_theme_info_section();
	echo '<p>&nbsp;</p><br></div>';
	echo '<div style="float:none; clear:both;"></div>';

	// Option/Layer Tab Javascript to Admin Footer
	// -------------------------------------------
	add_action('admin_footer', 'bioship_admin_theme_options_scripts');


	// Closing Div For Theme Options Center
	// ------------------------------------
	// 1.8.5: closes #themeoptionswrap div
	// --- Titan Framework ---
	add_action('tf_admin_page_after_'.$vthemename, 'bioship_admin_theme_options_close');
	// --- Options Framework ---
	add_action('optionsframework_after', 'bioship_admin_theme_options_close');

 }
}

// ------------------------------
// Admin Theme Options Wrap Close
// ------------------------------
// 2.1.1: moved function outside admin_theme_options_page
if (!function_exists('bioship_admin_theme_options_close')) {
 function bioship_admin_theme_options_close() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	echo '</div>';
	bioship_html_comment('/#themeoptionswrap');
	echo PHP_EOL;
 }
}

// ---------------------------
// Admin Theme Options Scripts
// ---------------------------
// 1.8.0: fixed nesting problem causing javascript error
// 1.8.0: detect and switch to tab for Titan framework also
// 1.8.5: simplified defaults and improved options switching
// 2.1.1: moved function outside admin_theme_options_page
if (!function_exists('bioship_admin_theme_options_scripts')) {
 function bioship_admin_theme_options_scripts() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemename;

	// Switch Layer Display
	// --------------------
	$defaulttab = 'skin';
	// 2.0.5: change default tab for welcome message
	if (isset($_REQUEST['welcome']) && ($_REQUEST['welcome'] == 'true')) {$defaulttab = 'extend';}

	echo "<script>
	function optionstabsdisplay() {
		var layertab; var optionstab;
		if (document.getElementById('layertab')) {layertab = document.getElementById('layertab').value;} else {
			if (document.getElementById('".esc_js($vthemename)."_layertab')) {layertab = document.getElementById('".esc_js($vthemename)."_layertab').value;}
		}

		if (layertab == '') {layertab = '".esc_attr($defaulttab)."';}
		if (layertab == 'alloptions') {showalllayers();}
		else {
			if (layertab == 'extend') {switchlayers('skin'); showhideextend();}
			else {switchlayers(layertab);}
		}

		var optionstab = '';
		if (document.getElementById('optionstab')) {optionstab = document.getElementById('optionstab').value;} else {
			if (document.getElementById('".esc_js($vthemename)."_optionstab')) {optionstab = document.getElementById('".esc_js($vthemename)."_optionstab').value;}
		}
		if (optionstab == '') {optionstab = 'options-group-1-tab';}

		if (layertab == 'skeleton') {showhidetabs('skeleton','hide');}
		if (layertab == 'muscle') {showhidetabs('muscle','hide');}
		if (layertab == 'skin') {showhidetabs('skin','hide');}

		if (document.getElementById(optionstab)) {document.getElementById(optionstab).style.display = '';}
		else {console.log('#'.optionstab+' tab not found!');}
	}";

	// Resize Options Form Table Widths
	// --------------------------------
	// for Options Framework
	$tableclasses = '#optionsframework-wrap,#optionsframework-metabox,#optionsframework,.nav-tab-wrapper';
	// $tableclasses .= ',.group,.options-container';
	// for Titan Framework
	if (THEMETITAN) {$tableclasses = '.titan-framework-panel-wrap,.nav-tab-wrapper,.options-container,.form-table';}

	// --- make room for righthand sidebar ---
	// TODO: maybe hide WordQuest sidebar if screen width is too small to handle it?
	echo "function resizeoptionstables() {
		if (document.getElementById('floatdiv')) {
			var wpbodywidth = jQuery('#wpbody').width();
			var newwidth = wpbodywidth - 280;
			if (newwidth < 640) {
				document.getElementById('floatdiv').style.display = 'none';
				jQuery('".$tableclasses."').css('width','100%');
			} else {
				document.getElementById('floatdiv').style.display = 'block';
				jQuery('".$tableclasses."').css('width',newwidth+'px');
			}
		} else {jQuery('#extendwrapper').css({'float':'none','margin-top':'100px'});}
	}"; // ">


	// Titan Tab Click Function
	// ------------------------
	// 1.8.5: tab click function modified from options-custom.js
	if (THEMETITAN) {echo "
		jQuery('.nav-tab-wrapper a').click(function(e) {
			e.preventDefault();
			jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active');
			jQuery(this).addClass('nav-tab-active').blur();
			var selected = jQuery(this).attr('href');
			jQuery('.group').hide(); jQuery(selected).show();
			optionstab = jQuery(this).attr('title');
			document.getElementById('".esc_js($vthemename)."_optionstab').value = optionstab.toLowerCase();
			/* console.log(document.getElementById('".esc_js($vthemename)."_optionstab').value); */
		});";
	}

	// Dynamic CSS QuickSave Button
	// ----------------------------
	// 1.8.5: added CSS quicksave button
	if (THEMETITAN) {echo PHP_EOL."var dynamiccssareaid = '".esc_js($vthemename)."_dynamiccustomcss';".PHP_EOL;}
	else {echo PHP_EOL."var dynamiccssareaid = 'dynamiccustomcss';".PHP_EOL;}
	// 2.1.2: added missing translation wrappers to save button/message
	$savecss = esc_attr(__('Save CSS', 'bioship'));
	$csssaved = esc_attr(__('CSS Saved!','bioship'));
	echo "
		quicksavebutton = document.createElement('a');
		quicksavebutton.setAttribute('class', 'button button-primary');
		quicksavebutton.setAttribute('style', 'margin-left:-80px; float:right;');
		quicksavebutton.innerHTML = '".esc_attr($savecss)."';
		quicksavebutton.href = 'javascript:void(0);';
		quicksavebutton.id = 'quicksavebutton';

		quicksavesaved = document.createElement('div');
		quicksavesaved.id = 'quicksavesaved';
		quicksavesaved.innerHTML = '".esc_attr($csssaved)."';

		function quicksavedshow() {
			quicksaved = document.getElementById('quicksavesaved');
			quicksaved.style.display = 'block';
			setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
		}
	".PHP_EOL;
	// if (document.getElementById('dynamiccustomcss')) {jQuery('#dynamiccustomcss').parent.addClass('nolabel');}

	// Call Document Ready Functions
	// -----------------------------
	// 1.8.0: call tab display and resize on page load
	// 1.8.5: run these function after document ready
	echo "jQuery(document).ready(function($) {

		optionstabsdisplay();
		jQuery('#floatdiv').fadeIn();
		resizeoptionstables();
		";

		// CSS Quicksave Button
		// --------------------
		echo "csstextarea = document.getElementById(dynamiccssareaid);
		textareaparent = csstextarea.parentNode;
		textareaparent.insertBefore(quicksavebutton, csstextarea);
		textareaparent.insertBefore(quicksavesaved, csstextarea);
		jQuery('#quicksavebutton').click(function() {
			newcss = document.getElementById(dynamiccssareaid).value;
			jQuery('#quicksavecss').val(newcss);
			jQuery('#quicksavecssform').submit();
		});";

		// Resize Table Width on Window Resize
		// -----------------------------------
		// once using delay (debounce)
		// ref: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed
		echo "var resizedelay = (function() {
		  var resizetimer = 0; return function(callback, ms) {clearTimeout (resizetimer);
		  resizetimer = setTimeout(callback, ms);};	})();
		jQuery(window).resize(function() {
			resizedelay(function(){resizeoptionstables();}, 750);
			jQuery(document.body).trigger('sticky_kit:recalc');
		});";

		// Load Sticky Kit for Sidebar Floatbox
		// ------------------------------------
		// 1.8.5: use sticky kit instead of floating script
		// TODO: javascript check that stick_in_parent function exists ?
		// echo "if (typeof stick_in_parent === 'function') {";
			echo "jQuery('#floatdiv').stick_in_parent({offset_top:100});";
		// echo "}";

	echo "});</script>";
 }
}

// -------------------------------
// Admin Theme Options Page Styles
// -------------------------------
if (!function_exists('bioship_admin_theme_options_styles')) {

 // 2.0.9: move add action internally for consistency
 add_action('admin_head', 'bioship_admin_theme_options_styles');

 function bioship_admin_theme_options_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check for admin page ---
	// 2.0.9: check trigger internally for consistency
	// 2.1.1: set global vthemename earlier to prevent warning
	// 2.1.1: streamline valid page triggers check
	global $vthemename;
	$valid = array('options-framework', 'bioship-options', $vthemename.'-options');
	if (!isset($_REQUEST['page']) || !in_array($_REQUEST['page'], $valid)) {return;}

	// --- set admin page styles ---
	// 1.8.5: improved button tab colour scheme
	$styles = "#wpcontent {padding-left:0px;} #wpbody {padding-left:20px; background-color:#D0D0EE;}
		#wpbody-content {background-color: #EEEEEE; padding-left: 20px;}
		.wrap {margin-right:0px; margin-left:0px;} .wrap select {min-width:150px;}
		#themeoptionswrap {float:left;} #floatdiv {display:none;}
		#floatdiv-wrapper {position:absolute; right:0; height:100%;}
		#themedisplayname {background-color: #E0E0FF; border: 1px solid #77AAEE; padding: 0 10px; min-width:150px;}
		.filterbutton {font-size:12pt; font-weight:bold; padding:10px 10px; background-color:#E0EEEE; border: 1px solid #CCC;}
		.filterbutton:hover {background-color: #DDDDEE;}
		.filterbutton a, .frameworklink {text-decoration:none;}
		.frameworklink:hover {text-decoration:underline;}
		.activefilter {background-color:#D0D0EE; font-weight:bold; border: 1px solid #77AAEE;}
		.option .explain {line-height: 2em;}
		.wrap #optionsframework .section .explain {font-size: 11pt; line-height: 28px;}
		.section {font-size: 11pt;} .section-info {line-height:20px;}
		.section-info .heading {font-size: 11pt; font-weight:bold; color: #666; margin: 15px 0 0 0;}
		.metabox-holder .group h3 {font-size: 12pt;}
		.themelink {text-decoration:none;} .themelink:hover {text-decoration:underline;}
		#quickstart p {text-align:left; text-indent:1.5em;}

		.options-container {padding-top: 1px; background-color: #D0D0EE;}
		.titan-framework-panel-wrap .form-table {margin: 15px 0 15px 0;}
		.titan-framework-panel-wrap .form-table tr {background: #F3F9FF !important;}
		.titan-framework-panel-wrap .form-table th {width:auto !important;}
		#extendwrapper .postbox, #optionsframework .group {background: #F3F9FF;}
		#floatdiv .stuffbox {background: #F3F9FF !important;}

		.titan-framework-panel-wrap .form-table tr.tf-heading th {background: #E0EEEE !important;}
		#optionsframework .group h3 {background-color:#E0EEEE !important;}
		#floatdiv .stuffbox h3 {background: #E0EEEE !important; margin: 0; padding: 10px 0;}

		#optionsframework .section .controls.nolabel {width: 86% !important;}
		.section .control textarea, .tf-textarea textarea {font-size: 12pt; font-family: Consolas, 'Lucida Console', Monaco, FreeMono, monospace;}
		#dynamiccustomcss, #".esc_attr($vthemename)."_dynamiccustomcss {width: 86% !important; height: 400px;}
		#dynamicadmincss, #".esc_attr($vthemename)."_dynamicadmincss {width: 86% !important; height: 250px;}

		#postmetatop, #postmetabottom, #".esc_attr($vthemename)."_postmetatop, #".esc_attr($vthemename)."postmetabottom,
		#pagemetatop, #pagemetabottom, #".esc_attr($vthemename)."_pagemetatop, #".esc_attr($vthemename)."pagemetabottom {height:30px;}
		#extendcolumn, #feedcolumn {float:left;} #extendcolumn {margin-right:20px;}
		#extendcolumn .postbox {width:300px;} #feedcolumn .postbox {width:350px;}

		.postbox h2, .postbox h3 {font-size: 16px; margin-top: 0; background-color: #E0E0EE; padding: 5px;}
		.menu-block {float:left; display:block; width:100%;}
		.options-container input[type='checkbox'], tf-checkbox input[type='checkbox'] {font-size:12pt;}
		.options-container select, tf-select select, .options-container input[type='text'], tf-text input[type='text']
			{font-size:12pt; padding:2px 10px;}

		.titan-framework-panel-wrap p.submit {padding: 0; border-bottom: 0;}
		.options-container p.submit button.button-primary, #optionsframework-submit .button-primary {float: right; margin-right:20px;}
		.options-container p.submit button.button-secondary, #optionsframework-submit .reset-button {float: left; margin-left:20px;}
		.options-container p.submit {width: 100%;}
		.nav-tab-active {background-color: #CCD;}
		.nav-tab {background-color: #EAEAFA;} .nav-tab:hover {background-color: #DDE;}

		#themeoptionsheader, #extendwrapper {float:left;}
		#optionsframework {max-width:100% !important;} /* O.F. */
		.titan-framework-panel-wrap {float:left;} .options-container .tf-font iframe {height:75px;} /* Titan */
		#quicksavesaved {display:none; float:right; margin-left:-50px; margin-top:50px; padding:3px 6px; max-width:80px;
			font-size:10pt; color: #333; font-weight:bold; 	background-color: lightYellow; border: 1px solid #E6DB55;}
		#setting-error-tgmpa button.notice-dismiss {display:none !important;} /* TGM fix */

		#exportform-arrow, #importform-arrow {font-size:24px; font-weight:bold; line-height:24px;}
		#themetools-inside, #adminnotices-inside {padding-bottom: 0;}
	";

	// 2.0.9: fix to hide added Freemius tabs (account, contact us, support forum, upgrade)
	$styles .= "#themeoptionswrap .fs-tab {visibility: hidden; height: 0; margin-top: -12px;}".PHP_EOL;

	$styles = bioship_apply_filters('options_themepage_styles', $styles);
	echo "<style>".$styles."</style>"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
 }
}

// -----------------------
// Floating Sidebar Output
// -----------------------
// Donations / Testimonials / Sidebar Ads / Footer
if (!function_exists('bioship_admin_floating_sidebar')) {
 function bioship_admin_floating_sidebar() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// Include WordQuest Helper
	// ------------------------
	// 1.8.5: change from wordquest_admin_load to match new helper version (1.6.0)
	$wordquest = bioship_file_hierarchy('file', 'wordquest.php', array('includes'));
	if ($wordquest) {include_once($wordquest); wqhelper_admin_loader();}
	if (!function_exists('wqhelper_sidebar_floatbox')) {return;}

	// Filter Sidebar Save Button
	// --------------------------
	if (!function_exists('bioship_admin_sidebar_save_button')) {

	 add_filter('wordquest_sidebar_save_button', 'bioship_admin_sidebar_save_button');

	 function bioship_admin_sidebar_save_button($button) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// jQuery click and submit functions
		if (THEMETITAN) {
			// --- Titan Framework ---
			$submitfunction = "jQuery('.options-container form button[name=\"action\"]').each(function() {
				if (jQuery(this).hasClass('button-primary')) {jQuery(this).trigger('click');}
			});";
		} elseif (THEMEOPT) {
			// --- Options Framework ---
			$submitfunction =  "jQuery('#optionsframework form').submit();";
		}

		// --- theme options sidebar save button ---
		// 1.8.0: replace the sidebar save button
		// 1.8.5: add onlick event instead of replacing the button
		// 1.9.5: replace the button agasin to remove the inline sidebar save onlick event
		// 2.0.9: added missing translation wrappers
		$button = "<table><tr>";
		$button .= "<td align='center'><input id='sidebarsavebutton' type='button' class='button-primary' value='".esc_attr(__('Save Settings','bioship'))."'></td>";
		$button .= "<td width='30'></td><td>";
			$button .= "<div style='line-height:1em;'><font style='font-size:8pt;'><a href='javascript:void(0);' style='text-decoration:none;' onclick='doshowhidediv(\"sidebarsettings\");hidesidebarsaved();'>";
			$button .= esc_attr(__('Sidebar','bioship'))."<br>";
			$button .= esc_attr(__('Options','bioship'))."</a></font></div>";
		$button .= "</td></tr></table>";
		$button .= "<script>jQuery('#sidebarsavebutton').click(function() {".$submitfunction."});</script>";
		return $button;
	 }
	}

	// Load Floating Sidebar
	// ---------------------
	// 1.8.5: removed float script for theme in favour of using stickykit
	// $floatmenuscript = wqhelper_sidebar_floatmenuscript();
	// echo $floatmenuscript;
	// 1.8.5: change from wordquest_sidebar_floatbox to match new helper version (1.6.0)
	$args = array('bioship','yes');
	echo "<div id='floatdiv-wrapper'>";
		wqhelper_sidebar_floatbox($args);
	echo "</div>";
 }
}

// ------------------
// AJAX QuickSave CSS
// ------------------
// 1.8.5: added CSS quicksave
if (!function_exists('bioship_admin_quicksave_css')) {

 // 2.0.9: moved add action internally for consistency
 add_action('wp_ajax_quicksave_css_theme_settings', 'bioship_admin_quicksave_css');

 function bioship_admin_quicksave_css() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if (current_user_can('edit_theme_options')) {
		$vthemename = $_POST['theme'];
		// 2.0.9: use wp_verify_nonce not check_admin_referer here
		$checknonce = wp_verify_nonce($_REQUEST['_wpnonce'], 'quicksave_css_'.$vthemename);
		if ($checknonce) {
			global $vthemesettings;
			$vthemesettings['dynamiccustomcss'] = stripslashes($_POST['quicksavecss']);
			if (THEMETITAN) {$vthemesettings = serialize($vthemesettings);}
			update_option(THEMEKEY, $vthemesettings);
		} else {$error = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}
	} else {$error = __('Failed. Looks like you may need to login again!','bioship');}

	if ($error) {echo "<script>alert('".esc_js($error)."');</script>";}
	else {
		// 2.1.2: moved from parent quicksave function
		echo "<script>parent.document.getElementById('quicksavesaved').style.display = 'block';
		setTimeout(function() {parent.jQuery('#quicksavesaved').fadeOut(5000,function(){});}, 5000);</script>";
	}
	exit;
 }
}

// ------------------------
// AJAX Refresh Titan Nonce
// ------------------------
// 2.0.9: AJAX action to auto-refresh Titan Framework nonce
// 2.1.1: moved add_action internally for consistency
if (!function_exists('bioship_theme_options_refresh_titan_nonce')) {

 add_action('wp_ajax_bioship_theme_options_refresh_titan_nonce', 'bioship_theme_options_refresh_titan_nonce');

 function bioship_theme_options_refresh_titan_nonce() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 2.1.0: fix to incorrect nonce key here
	// 2.1.1: use THEMEPREFIX contant here
	// note: this comes from the admin menu item ID, NOT the theme options key!
	$noncekey = THEMEPREFIX.'-options';
	$titannonce = wp_create_nonce($noncekey);
	echo "<script>parent.document.getElementById('titan-framework_nonce').value = '".$titannonce."';
	console.log('Titan Admin Page Nonce Refreshed: ".$titannonce."');</script>"; exit;
 }
}

// --------------------------
// AJAX Session Timeout Alert
// --------------------------
// 2.1.0: add javascript popup alert for session timeout
// 2.1.1: moved add_action internally for consistency
// 2.1.3: fix to mismatching add action and function name
if (!function_exists('bioship_theme_options_timeout_alert')) {

 // 2.1.1: fixed wp_ajax_no_priv_ prefix typo to wp_ajax_nopriv_
 add_action('wp_ajax_nopriv_bioship_theme_options_refresh_titan_nonce', 'bioship_theme_options_timeout_alert');

 function bioship_theme_options_timeout_alert() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// TODO: maybe trigger interstitial login popup thickbox instead ?
 	$message = __('Oops! Your session has timed out.','bioship')."\n";
 	$message .= __('Do not attempt to save changes - login again in another window first.','bioship');
	echo "<script>alert('".esc_js($message)."');</script>"; exit;
 }
}


// -----------------------
// === Theme Info Page ===
// -----------------------

// ---------------
// Theme Info Page
// ---------------
// standalone page for WordPress.org version (no Theme Framework page)
if (!function_exists('bioship_admin_theme_info_page')) {
 function bioship_admin_theme_info_page() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- theme info page heading ---
	// TODO: improve Theme Tools admin page title display
	echo "<h3>".esc_attr(__('BioShip Theme Info','bioship'))."</h3><br>";

	// TODO: add Titan installation info and link to Theme Tools page

	// Include WordQuest Helper
	// ------------------------
	// 1.8.5: change from wordquest_admin_load for new helper version (1.6.0+)
	$wordquest = bioship_file_hierarchy('file', 'wordquest.php', array('includes'));
	if ($wordquest) {include_once($wordquest); wqhelper_admin_loader();}

	// Load Theme Info Section
	// -----------------------
	echo '<div class="wrap">';
		bioship_admin_theme_info_section();
	echo '<p></p><br></div><div style="float:none; clear:both;"></div>';

 }
}

// ------------------
// Theme Info Section
// ------------------
// 1.8.0: separate function for theme home info tab
if (!function_exists('bioship_admin_theme_info_section')) {
 function bioship_admin_theme_info_section() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemedirs;

	$welcome = false;
	if (isset($_REQUEST['welcome']) && ($_REQUEST['welcome'] == 'true')) {$welcome = true;}

	// Toggle Box Javascript
	// ---------------------
	// 2.0.0: maybe switch arrow display for box toggle
	echo "<script>function togglethemebox(divid) {
		var arrowid = divid+'-arrow'; var divid = divid+'-inside';
		if (document.getElementById(divid).style.display == '') {
			document.getElementById(divid).style.display = 'none';
			if (document.getElementById(arrowid)) {document.getElementById(arrowid).innerHTML = '&#9662;';}
		} else {
			document.getElementById(divid).style.display = '';
			if (document.getElementById(arrowid)) {document.getElementById(arrowid).innerHTML = '&#9656;';}
		}
	}</script>";

	// Admin Notices - Wide (collapsed)
	// --------------------------------
	// TODO: maybe use wqhelper admin notices boxer here ?
	$boxid = 'adminnotices'; $boxtitle = __('Admin Notices','bioship');
	echo '<div id="'.esc_attr($boxid).'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.esc_attr($boxid).'\');"><span id="'.esc_attr($boxid).'-arrow">&#9662;</span> '.esc_attr($boxtitle).'</h3>';
	echo '<div class="inside" id="'.esc_attr($boxid).'-inside" style="display:none;">';
	echo '<h2></h2>';
		// Admin Notices reinsert themselves magically here after the <h2> tag inside a <div class="wrap">
		// Note: TGM Plugin Activations Notice still disappears from here when dismissed :-(
		// TODO: =dismiss_admin_notices should have no effect here (and only here)
	echo '</div></div>';

	// Welcome / Documentation - Wide
	// ------------------------------
	// 2.0.5: add combined welcome and documentation box
	$boxid = 'documentation';
	if ($welcome) {$boxtitle = __('Welcome!','bioship');}
	else {$boxtitle = __('Documentation','bioship');}
	echo '<div id="'.esc_attr($boxid).'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');">';
	echo '<span id="'.esc_attr($boxid).'-arrow">';
	if ($welcome) {echo '&#9656;';} else {echo '&#9662;';}
	echo '</span> '.esc_attr($boxtitle).'</h3>';
	if (!$welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	echo '<div class="inside" id="'.esc_attr($boxid).'-inside"'.$hide.'><center>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		bioship_admin_documentation_box($welcome);
	echo '</center></div></div>';

	// Theme Tools - Wide (collapsed)
	// ------------------------------
	$boxid = 'themetools'; $boxtitle = __('Theme Settings Tools','bioship');
	echo '<div id="'.esc_attr($boxid).'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span id="'.esc_attr($boxid).'-arrow">&#9662;</span> '.esc_attr($boxtitle).'</h3>';
	echo '<div class="inside" id="'.esc_attr($boxid).'-inside" style="display:none;"><center>';
		bioship_admin_theme_tools_forms();
	echo '</center></div></div>';

	// Left Column - Links / Extensions
	// --------------------------------
	echo '<div id="extendcolumn">';

		// BioShip Theme Links
		// -------------------
		$boxid = 'bioshiplinks'; $boxtitle = __('Theme Links','bioship');
		echo '<div id="'.esc_attr($boxid).'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
		echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
			$bioshiplogo = bioship_file_hierarchy('url', 'bioship.png', $vthemedirs['image']);
			echo '<table><tr><td style="vertical-align:top;"><a href="'.esc_url(THEMESUPPORT).'" class="themelink" target=_blank><img src="'.esc_url($bioshiplogo).'" border="0"></a></td>';
			echo '<td width="20"></td>';
			echo '<td><a href="'.esc_url(THEMEHOMEURL.'/documentation/').'" class="themelink" target=_blank>'.esc_attr(__('BioShip Documentation','bioship')).'</a><br>';
			// TODO: maybe add a dropdown list of documentation subpages?
			// --- Support Forum Links ---
			// 2.1.1: fix to support forum link URLs
			echo '<a href="'.esc_url(THEMESUPPORT.'/quest-category/bioship-support/').'" class="themelink" target=_blank>'.esc_attr(__('Support Solutions','bioship')).'</a><br>';
			echo '<a href="'.esc_url(THEMESUPPORT.'/quest-category/bioship-features/').'" class="themelink" target=_blank>'.esc_attr(__('Features and Feedback','bioship')).'</a><br>';
			// --- Development ---
			echo '<a href="http://github.com/majick777/bioship/" class="themelink" target=_blank>'.esc_attr(__('Development via GitHub','bioship')).'</a><br><br>';
			// --- Extensions ---
			echo '<a href="'.esc_url(THEMEHOMEURL.'/extensions/').'" class="themelink" target=_blank>'.esc_attr(__('BioShip Extensions','bioship')).'</a><br>';
			// --- Content Sidebars / AutoSave Net / ... FreeStyler? ---
			echo '&rarr; <a href="'.esc_url(THEMESUPPORT.'/plugins/content-sidebars/').'" class="themelink" target=_blank>Content Sidebars Plugin</a><br>';
			// echo '&rarr; <a href="'.esc_url(THEMESUPPORT.'/plugins/autosave-net/').'" class="themelink" target=_blank>AutoSave Net Plugin</a>';
			// echo '<a href="'.esc_url(THEMESUPPORT.'/plugins/freestyler/').'" class="themelink" target=_blank>FreeStyler Plugin</a>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// WordQuest Plugins
		// -----------------
		$boxid = 'wordquestplugins'; $boxtitle = __('WordQuest Alliance','bioship');
		echo '<div id="'.esc_attr($boxid).'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
		echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
			$wordquestlogo = bioship_file_hierarchy('url', 'wordquest.png', $vthemedirs['image']);
			echo '<table><tr><td><a href="http://wordquest.org" target=_blank><img src="'.esc_url($wordquestlogo).'" border="0"></a></td><td width="20"></td><td>';
			if (isset($GLOBALS['admin_page_hooks']['wordquest'])) {
				// 2.1.2: use add_query_arg here
				$wqpanel = add_query_arg('page', 'wordquest', admin_url('admin.php'));
				echo '<a href="'.$wqpanel.'" class="themelink">'.esc_attr(__('Plugin Panel','bioship')).'</a><br>';
			}
			echo '<a href="http://wordquest.org/register/" class="themelink" target=_blank>'.esc_attr(__('Join the Alliance','bioship')).'</a><br>';
			echo '<a href="http://wordquest.org/login/" class="themelink" target=_blank>'.esc_attr(__('Members Login','bioship')).'</a><br>';
			echo '<a href="http://wordquest.org/solutions/" class="themelink" target=_blank>'.esc_attr(__('Solutions Forum','bioship')).'</a><br>';
			echo '<a href="http://wordquest.org/plugins/" class="themelink" target=_blank>'.esc_attr(__('WordQuest Plugins','bioship')).'</a><br>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// Recommended Plugins
		// -------------------
		// 1.8.5: added recommended box display
		$recommended = bioship_file_hierarchy('file', 'recommended.php', array('includes'));
		if ($recommended) {
			include($recommended);
			$recommend = bioship_admin_get_recommended();
			if ($recommend) {
				$boxid = 'recommended'; $boxtitle = __('Recommended','bioship');
				echo '<div id="'.esc_attr($boxid).'" class="postbox">';
				echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
				echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
					echo $recommend;
				echo '</div></div>';
			}
		}

	echo '</div>'; // close extend column

	// Right Column - Dashboard Feed Widgets
	// -------------------------------------
	echo '<div id="feedcolumn">';

		// 1.8.0: allow turning feeds off if being problematic
		$feeds = true;
		if (isset($_REQUEST['loadfeeds'])) {
			$loadfeeds = $_REQUEST['loadfeeds'];
			if ( ($loadfeeds == 'on') || ($loadfeeds == '1') ) {delete_option('bioship_admin_feed_display');}
			if ( ($loadfeeds == 'off') || ($loadfeeds == '0') ) {update_option('bioship_admin_feed_display','off');}
			if ( ($loadfeeds == 'no') || ($loadfeeds == '2') ) {$feeds = false;}
		}
		if (get_option('bioship_admin_feed_display') == 'off') {$feeds = false;}

		// BioShip Feed
		// ------------
		// TODO: move muscle_bioship_dashboard_feed_widget to admin.php
		$boxid = 'bioshipfeed'; $boxtitle = __('BioShip News','bioship');
		if ($feeds && function_exists('muscle_bioship_dashboard_feed_widget')) {
			echo '<div id="'.esc_attr($boxid).'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
			echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
				echo "<!-- start BioShip News Feed -->";
					muscle_bioship_dashboard_feed_widget(false, false);
				echo "<!-- end BioShip News Feed -->";
			echo '</div></div>';
		}

		// WordQuest Feed
		// --------------
		$boxid = 'wordquestfeed'; $boxtitle = __('WordQuest News','bioship');
		if ($feeds && function_exists('wqhelper_dashboard_feed_widget')) {
			echo '<div id="'.esc_attr($boxid).'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_attr($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
			echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
				echo "<!-- start Wordquest News Feed -->";
					wqhelper_dashboard_feed_widget();
				echo "<!-- end Wordquest News Feed -->";
			echo '</div></div>';
		}

		// PluginReview Feed
		// -----------------
		$boxid = 'pluginreviewfeed'; $boxtitle = __('Plugin Reviews','bioship');
		if ($feeds && function_exists('wqhelper_pluginreview_feed_widget')) {
			echo '<div id="'.esc_attr($boxid).'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_attr($boxtitle).'</span></h2>';
			echo '<div class="inside" id="'.esc_attr($boxid).'-inside">';
				echo "<!-- start PluginReview News Feed -->";
					wqhelper_pluginreview_feed_widget();
				echo "<!-- end PluginReview News Feed -->";
			echo '</div></div>';
		}

		// maybe enqueue Feed Loader Javascript
		// ------------------------------------
		if (!has_action('admin_footer', 'wqhelper_dashboard_feed_javascript')) {
			add_action('admin_footer', 'wqhelper_dashboard_feed_javascript');
		}

	echo '</div>'; // close feed column

 }
}

// ---------------
// Welcome Message
// ---------------
// 2.1.2: added separate (filterable) theme welcome message
if (!function_exists('bioship_admin_welcome_message')) {
 function bioship_admin_welcome_message() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- set welcome message ---
	$current_user = bioship_get_current_user();
	$firstname = $current_user->user_firstname;
	if ($firstname != '') {$firstname = ', '.$firstname;}
	$message = '<p align="left">'.esc_attr(__('Welcome aboard','bioship')).' '.esc_attr($firstname).'! ';
	$message = esc_attr(__('And thanks for choosing to pilot BioShip...','bioship')).'</p>';

	// --- filter and return ---
	$message = bioship_apply_filters('admin_welcome_message', $message);
	return $message;
 }
}

// -----------------
// Documentation Box
// -----------------
// 2.0.5: added documentation link box
if (!function_exists('bioship_admin_documentation_box')) {
 function bioship_admin_documentation_box($welcome) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemetemplateurl;

	// Load Documentation
	// ------------------
	include(dirname(__FILE__).'/docs.php');

	// Welcome Message
	// ---------------
	if ($welcome) {
		// 2.0.7: use new prefixed current user function
		// 2.1.1: move welcome message to separate function
		echo bioship_admin_welcome_message();
	}

	// QuickStart Section
	// ------------------
	if (!$welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	echo '<div id="quickstart"'.$hide.'>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<h4>'.esc_attr(__('QuickStart Guide','bioship')).'</h4>';
		echo bioship_docs_quickstart(false);
	echo '</div>';

	// QuickStart Scripts
	// ------------------
	echo "<script>
	function showquickstart() {
		document.getElementById('quickstart').style.display = '';
		document.getElementById('showquickstart').style.display = 'none';
		document.getElementById('hidequickstart').style.display = '';
	}
	function hidequickstart() {
		document.getElementById('quickstart').style.display = 'none';
		document.getElementById('hidequickstart').style.display = 'none';
		document.getElementById('showquickstart').style.display = '';
	}</script>";

	// QuickStart Links
	// ----------------
	if ($welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	$quickstart = '<div id="showquickstart"'.$hide.'>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	$quickstart .= '<a href="javascript:void(0);" onclick="showquickstart();">';
	$quickstart .= esc_attr(__('Show','bioship')).' '.esc_attr(__('QuickStart Guide','bioship')).'</a></div>';
	if (!$welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	$quickstart .= '<div id="hidequickstart"'.$hide.'>'; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	$quickstart .= '<a href="javascript:void(0);" onclick="hidequickstart();">';
	$quickstart .= esc_attr(__('Hide','bioship')).' '.esc_attr(__('QuickStart Guide','bioship')).'</a></div>';
	$quickstart .= '<br>';

	// Documentation Index
	// -------------------
	$docindex = bioship_docs_index(false);
	$docindex = str_replace('h3>', 'h4>', $docindex);
	$docindex = str_replace('<!-- START -->', '<center><table><tr><td align="left">'.$quickstart, $docindex);
	$docindex = str_replace('<!-- SPLIT -->', '</td><td width="50"></td><td align="left" style="vertical-align:top;">', $docindex);
	$docindex = str_replace('<!-- END -->', '</td></tr></table></center>', $docindex);

	// 2.1.4: fix to documentation thickbox links
	global $vthemedoctitles;
	foreach ($vthemedoctitles as $key => $title) {
		$docindex = str_replace('a href="/documentation/'.$key.'/"', 'a class="doc-thickbox" href="#'.$key.'"', $docindex);
	}
	echo $docindex; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho

	// Documentation Thickbox Script
	// -----------------------------
	$docsurl = esc_url($vthemetemplateurl.'admin/docs.php');
	echo "<script>jQuery(document).ready(function() {
	    jQuery('.doc-thickbox').click(function() {
	    	width = jQuery(window).width() * 0.9;
	    	height = jQuery(window).height() * 0.8;
	    	thishref = jQuery(this).attr('href').replace('#','');
	        tb_show('', '".esc_js($docsurl)."?page='+thishref+'&TB_iframe=true&width='+width+'&height='+height);
	        return false;
	    });
	});</script>";
 }
}


// ---------------------------------
// === Build Selective Resources ===
// ---------------------------------

// --------------------------
// Build Selective CSS and JS
// --------------------------
// 2.1.4: move trigger conditions internally
if (!function_exists('bioship_admin_build_selective_resources')) {

 add_action('admin_notices', 'bioship_admin_build_selective_resources');
 // 1.8.0: ...also need to trigger this after a Customizer save...
 add_action('customize_save_after', 'bioship_admin_build_selective_resources');

 function bioship_admin_build_selective_resources() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemename, $vthemesettings, $vthemedirs;

	// --- check build trigger conditions ---
	// 2.1.4: move trigger conditions internally
	$currentaction = current_action(); $dobuild = false;
	if ($currentaction == 'customize_save_after') {$dobuild = true;}
	elseif ($currentaction == 'admin_notices') {

		// --- Options Framework ---
		if (isset($_GET['page']) && isset($_GET['settings-updated'])) {
			if ( ($_GET['page'] == 'options-framework') && ($_GET['settings-updated'] == 'true') ) {
				$dobuild = true;
			}
		}
		// --- Titan Framework ---
		// 1.8.0: need to trigger differently for Titan save
		if (isset($_GET['page']) && isset($_GET['message'])) {
			if ( ($_GET['page'] == $vthemename.'-options') && ($_GET['message'] == 'saved') ) {
				$dobuild = true;
			}
		}
	}
	if (!$dobuild) {return;}

	// Maybe Combine CSS Core On Save
	// ------------------------------
	if ($vthemesettings['combinecsscore']) {

		// --- reset.css or normalize.css ---
		$resetoption = $vthemesettings['cssreset'];
		if ($resetoption == 'normalize') {
			$cssfile = bioship_file_hierarchy('file', 'normalize.css', $vthemedirs['style']);
			if ($cssfile) {$cssreset = bioship_file_get_contents($cssfile);}
			else {echo "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('CSS Combine could not find','bioship'))." <i>normalize.css</i><br>";}
		}
		if ($resetoption == 'reset') {
			$cssfile = bioship_file_hierarchy('file', 'reset.css', $vthemedirs['style']);
			if ($cssfile) {$cssreset = bioship_file_get_contents($cssfile);}
			else {echo "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('CSS Combine could not find','bioship'))." <i>reset.css</i><br>";}
		}

		// --- formalize.css ---
		if ($vthemesettings['loadformalize']) {
			$cssfile = bioship_file_hierarchy('file', 'formalize.css', $vthemedirs['style']);
			if ($cssfile) {$formalize = bioship_file_get_contents($cssfile);}
			else {echo "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('CSS Combine could not find','bioship'))." <i>formalize.css</i><br>";}
		}

		// --- mobile.css ---
		// (previously misnamed layout.css in skeleton theme)
		$cssfile = bioship_file_hierarchy('file', 'mobile.css', $vthemedirs['style']);
		if ($cssfile) {$mobile = bioship_file_get_contents($cssfile);}
		else {echo "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('CSS Combine could not find','bioship'))." <i>mobile.css</i><br>";}

		// 1.5.0: [Deprecated] these fixed stylesheet widths are deprecated by grid.php
		// skeleton-960.css, skeleton-1120.css, skeleton-1200.css

		// --- skeleton.css ---
		// (note: this must be last or CSS breaks)
		$cssfile = bioship_file_hierarchy('file', 'skeleton.css', $vthemedirs['style']);
		if ($cssfile) {$skeleton = bioship_file_get_contents($cssfile);}
		else {echo "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('CSS Combine could not find','bioship'))." <i>skeleton.css</i><br>";}

		// note: style.css and custom.css are intentionally not added here as it breaks combined stylesheet!

		// --- combine the CSS file contents ---
		$csscontents = $cssreset.PHP_EOL.PHP_EOL;
		$csscontents .= $formalize.PHP_EOL.PHP_EOL;
		$csscontents .= $mobile.PHP_EOL.PHP_EOL;
		$csscontents .= $skeleton.PHP_EOL.PHP_EOL;
		$datetime = date('H:i:s d/m/Y');
		$csscontents .= '/* Last Updated: '.esc_attr($datetime).' */';

		// --- write combined core CSS file directly ---
		// (no need for WP_Filesystem as the file already exists, but used anyway to pass Theme Check)
		// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/#comment-10820
		// 1.8.0: fix to use directory separator in file path

		// --- write selected styles to file ---
		// 2.1.1: check alternate style directory paths
		$stylepath = get_stylesheet_directory($vthemename).DIRSEP;
		foreach ($vthemedirs['style'] as $dir) {
			if (is_dir($stylepath.DIRSEP.$dir) && file_exists($stylepath.DIRSEP.$dir.DIRSEP.'core-styles.css')) {
				$stylefile .= $stylepath.DIRSEP.$dir.DIRSEP.'core-styles.css'; break;
			}
		}
		if (isset($stylefile)) {bioship_write_to_file($stylefile, $csscontents);}
	}

	// ----------------------------------------------
	// Build Selective Foundation Javascripts on Save
	// -----------------------------------------------
	// TODO: build selectives for Foundation 6, this currently only works for Foundation 5
	// $foundation = $vthemesettings['foundationversion'];
	$foundation = 'foundation5'; // currently available for Foundation 5 only

	if ($vthemesettings['loadfoundation'] == 'selective') {

		$jsfile = bioship_file_hierarchy('file', 'foundation.js', array('javascripts','includes/'.$foundation.'/js/foundation'));
		$foundationjs = bioship_file_get_contents($jsfile);
		$selected = $vthemesettings['foundationselect'];

		$message = '';
		foreach ($selected as $key => $value) {
			if ($value == '1') {
				$filename = 'foundation.'.$key.'.js';
				$foundationsourcedir = 'includes/'.$foundation.'/js/foundation';
				$jsfile = bioship_file_hierarchy('file', $filename, array($foundationsourcedir));
				if ($jsfile) {
					$jsdata = bioship_file_get_contents($jsfile);
					// 2.0.7: doubly ensure new line consistency for Theme Check
					$jsdata = str_replace("\r\n", "\n", $jsdata);
					$foundationjs .= $jsdata;
					// 1.5.5: fix, use specific matching EOL to pass Theme Check
					$foundationjs .= "\n"."\n";
				} else {
					$message .= "<b>".esc_attr(__('Warning','bioship'))."</b>: ".esc_attr(__('Foundation JS Combine could not find','bioship'))." <i>".esc_attr($filename)."</i><br>";
				}
			}
		}

		// --- missing resources message ---
		// 1.8.0: added admin warning for missing resources
		if ($message != '') {
			global $vadminmessages; $vadminmessages[] = $message;
			bioship_admin_notices_enqueue();
		}

		// --- write to file ---
		if (strlen($foundation) > 0) {
			// 1.8.0: write to theme javascripts directory, not foundation/js and fix directory separator
			// also, this is written to template directory so as not to overwrite a child version
			// (as such it does not need to use WP Filesystem as the file already exists)
			// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/#comment-10820
			$scriptpath = get_template_directory($vthemename).DIRSEP;

			// 2.1.1: check alternate script directory paths
			foreach ($vthemedirs['script'] as $dir) {
				if (is_dir($scriptpath.DIRSEP.$dir) && file_exists($scriptpath.DIRSEP.$dir.DIRSEP.'foundation.selected.js')) {
				  	$scriptfile = $scriptpath.DIRSEP.$dir.DIRSEP.'foundation.selected.js'; break;
				  }
			}
			if (isset($scriptfile)) {bioship_write_to_file($scriptfile, $foundationjs);}
		}
	}

 }
}


// =================================
// === Activation / Deactivation ===
// =================================

// -----------------------------------------------------
// Save/Restore Widgets/Menus on Deactivation/Activation
// -----------------------------------------------------

// TODO: retest activation/deactivation functionality
// (as this was improved in WP Core at some point)

// TEST: if switch_theme/after_switch_theme is triggered when using WP CLI
// (as may not be in admin area and these are loaded via admin.php only,
// so this may need to move out of admin.php if it is still needed)

// ----------------
// For Parent Theme
// ----------------
if (!THEMECHILD) {

	$saverestorewidgets = bioship_apply_filters('skeleton_theme_widget_backups', true);

	if ($saverestorewidgets) {

		// Backup on Deactivation
		// ----------------------
		if (!function_exists('bioship_admin_theme_deactivation')) {

		 add_action('switch_theme', 'bioship_admin_theme_deactivation');

		 function bioship_admin_theme_deactivation($vnewthemename) {

			$sidebarswidgets = get_option('sidebars_widgets');
			update_option('bioship_widgets_backup', $sidebarswidgets);

			$menusettings = get_option('nav_menu_options');
			update_option('bioship_menus_backup', $menusettings);

			// not needed: theme mods, as they are theme specific
			// (hmmmm maybe just how the sidebars/menus should be!)
		 }
		}

		// Restore on Activation
		// ---------------------
		if (!function_exists('bioship_admin_theme_activation')) {

		 add_action('after_switch_theme', 'bioship_admin_theme_activation');

		 function bioship_admin_theme_activation() {

			// 2.1.1: use THEMEPREFIX constant instead of hardcoding
			$sidebarswidgets = get_option('sidebars_widgets');
			$menusettings = get_option('nav_menu_options');
			$bioshipwidgets = get_option(THEMEPREFIX.'_widgets_backup');
			$bioshipmenus = get_option(THEMEPREFIX.'_menus_backup');
			// note: no need to restore theme mods as already theme specific

			// If there are backed up widgets/menus, restore them now
			// ..also be nice and backup the deactivated themes widgets/menus
			// (even though note these cannot be automatically restored)
			if ($bioshipwidgets != '') {
				update_option('sidebars_widgets', $bioshipwidgets);
				delete_option('old_theme_widgets_backup');
				// 2.0.8: fix to variable typo (vsidebarwidgets)
				add_option('old_theme_widgets_backup', $sidebarswidgets);
			}
			if ($bioshipmenus != '') {
				update_option('nav_menu_options', $bioshipmenus);
				delete_option('old_theme_menus_backup');
				add_option('old_theme_menus_backup', $menusettings);
			}

			// --- redirect to Theme Options page on activation ---
			// 2.0.5: redirect to welcome page section with admin_url
			global $pagenow;
			if (is_admin() && isset($_GET['activated']) && ($pagenow == 'themes.php')) {
				// 1.8.0: Titan Framework Conversion
				// 2.0.5: allow for no Titan or Options Framework
				if (!THEMETITAN && THEMEOPT) {wp_redirect(admin_url('themes.php').'?page=options-framework&welcome=true'); exit;}
				elseif (THEMETITAN && class_exists('TitanFramework')) {wp_redirect(admin_url('admin.php').'?page=bioship-options&welcome=true'); exit;}
				// 2.0.8: WordPress.Org versions only - redirection on theme activation not allowed :-/
				// else {wp_redirect(admin_url('themes.php').'?page=theme-info&welcome=true'); exit;}
			}
		 }
		}
	}
}

// ---------------
// For Child Theme
// ---------------
if (THEMECHILD) {

	// Save/Restore Child Theme Widgets/Menus on Deactivation/Activation
	// -----------------------------------------------------------------
	// 1.8.0: moved here from child theme functions.php (cleaner)
	// (note: maintain function_exists wrappers for back compat)
	// TODO: possibly change this filter name ?
	$saverestorechildwidgets = bioship_apply_filters('skeleton_childtheme_widget_backups', true);

	if ($saverestorechildwidgets) {

		// get Child Theme Slug
		// --------------------
		if (!function_exists('bioship_admin_get_child_theme_slug')) {
		 function bioship_admin_get_child_theme_slug() {
			$theme = wp_get_theme();
			if (!THEMETITAN && THEMEOPT) {$childthemeslug = preg_replace("/\W/", "_", strtolower($theme['Name']));}
			else {$childthemeslug = preg_replace("/\W/", "-", strtolower($theme['Name']));}
			return $childthemeslug;
		 }
		}

		// Switch Theme Hook (on Deactivation)
		// -----------------------------------
		// 2.1.1: moved add_action internally for consistency
		if (!function_exists('bioship_admin_child_theme_deactivation')) {

		 add_action('switch_theme', 'bioship_admin_child_theme_deactivation');

		 function bioship_admin_child_theme_deactivation($vnewthemename) {

			// Backup Child Theme Widgets and Menus
			// ------------------------------------
			$childthemeslug = bioship_admin_get_child_theme_slug();
			$sidebarswidgets = get_option('sidebars_widgets');
			delete_option($childthemeslug.'_widgets_backup');
			add_option($childthemeslug.'_widgets_backup', $sidebarswidgets);
			$menusettings = get_option('nav_menu_options');
			delete_option($childthemeslug.'_menus_backup');
			add_option($childthemeslug.'_menus_backup', $menusettings);
		 }
		}

		// After Switch Theme Hook (on Activation)
		// ---------------------------------------
		// 2.1.1: moved add_action internally for consistency
		if (!function_exists('bioship_admin_child_theme_activation')) {

		 add_action('after_switch_theme', 'bioship_admin_child_theme_activation');

		 function bioship_admin_child_theme_activation() {

			// Restore Child Theme Widgets and Menus
			// -------------------------------------
			$childthemeslug = bioship_admin_get_child_theme_slug();
			$backupwidgets = get_option($childthemeslug.'_widgets_backup');
			$backupmenus = get_option($childthemeslug.'_menus_backup');
			if ($backupwidgets != '') {update_option('sidebars_widgets', $backupwidgets);}
			if ($backupmenus != '') {update_option('nav_menu_options', $backupmenus);}

			// Also transfer the menu locations from the backup
			$menulocations = get_theme_mod('nav_menu_locations');
			if (!is_array($menulocations)) {
				$menulocations = get_option($childthemeslug.'_menu_locations_backup');
				if (is_array($menulocations)) {set_theme_mod('nav_menu_locations', $menulocations);}
			}

			// Redirect to Theme Options page on theme activation
			// --------------------------------------------------
			// 1.8.0: redirects to theme options / info page
			// 2.0.5: redirect to theme options / customizer with admin_url
			global $pagenow;
			if (is_admin() && isset($_GET['activated']) && ($pagenow == 'themes.php')) {
				if (!THEMETITAN && THEMEOPT) {$url = admin_url('admin.php').'?page=options-framework';}
				elseif (THEMETITAN && class_exists('TitanFramework')) {$url = admin_url('admin.php').'?page=bioship-options';}
				else {$url = admin_url('customize.php');}
				wp_redirect($url); exit;
			}
		 }
		}
	}
}

// TODO: fix or deprecate? automatic theme mods and menu locations transfer ?
//	if (get_option('bioship_transfer_widgets_menus') == 'yes') {
//		$transferwidgets = get_option('bioship_widgets_backup');
//		update_option('sidebars_widgets', $vtransferwidgets);
//
//		$transfermenus = get_option('bioship_menus_backup');
//		update_option('nav_menu_options', $transfermenus);
//
//		$thememods = get_option('bioship_mods_backup');
//		if (count($thememods) > 0) {
//			foreach ($thememods as $thememod => $value) {
//				set_theme_mod($thememod, $value);
//			}
//		}
//		// $menulocations = get_option('bioship_menu_locations_backup');
//		// set_theme_mod('nav_menu_locations', $menulocations);
//
//		update_option('bioship_transfer_widgets_menus','done');
//	}


// ---------------------
// === Theme Plugins ===
// ---------------------

// ----------------------------------
// Install the Titan Framework Plugin
// ----------------------------------
// this method creates a standalone callable installation link for
// adding Titan Framework to the WordPress.Org version of theme
// (currently done via TGMPA using Titan Checker, rather than here)
// 1.8.5: moved here from customizer.php
// 2.1.1: moved check trigger internally for consistency
// Note: Otto's Theme Plugin Dependency Class
// ref: http://ottopress.com/2012/themeplugin-dependencies/
if (!function_exists('bioship_admin_install_titan_framework')) {

 add_action('init', 'bioship_admin_install_titan_framework');

 function bioship_admin_install_titan_framework() {

	// --- check trigger conditions ---
	if (!isset($_REQUEST['admin_install_titan_framework'])) {return;}
	if ($_REQUEST['admin_install_titan_framework'] != 'yes') {return;}

	// --- check permissions ---
	if (!current_user_can('install_plugins')) {return;}

	// IDEA: maybe extracting from a bundled zip could work here..?
	// eg: http://meta.wordpress.stackexchange.com/questions/4172/script-that-downloads-installs-and-activates-wordpress-plugins?cb=1

	// --- install Titan Framework ---
	// 2.1.1: use add_query_arg for install URL
	$titanslug = 'titan-framework';
	$installurl = add_query_arg('action', 'install-plugin', self_admin_url('update.php'));
	$installurl = add_query_arg('plugin', $titanslug, $installurl);
	$installurl = wp_nonce_url($installurl, 'install-plugin_'.$titanslug);
	wp_redirect($installurl); exit;
 }
}

// --------------------------
// Load TGM Plugin Activation
// --------------------------
if (!function_exists('tgmpa')) {
	$tgmpa = bioship_file_hierarchy('file', 'class-tgm-plugin-activation.php', array('includes'));
	if ($tgmpa) {require_once($tgmpa);}
}

// ------------------------
// Recommended Plugins List
// ------------------------

// 'Required' Plugins
// ------------------
// - Titan Framework (better admin theme options interface)
// -- for WordPress.Org installs (not explicitly 'required')

// Recommended Plugins (Theme Supported)
// -------------------------------------
// TODO: retest the need for Widget Saver ?
// TODO: maybe force use of specific TML version ?
// - AJAX Load More
// - Open Graph Protocol Framework
// - Theme My Login
// - Theme Test Drive
// - Widget Saver
// - WP Subtitle
// - WP PageNavi

// WordQuest Plugins
// -----------------
// - AutoSave Net		(released)
// - Content Sidebars	(released)
// - Guten Free Options (released)
// - WP AutoMedic		(released)

// Upcoming WordQuest Plugins
// --------------------------
// - ForceField				(pending)
// - Visitor Vortex			(pending)
// - RefleXedit				(testing)
// - PDF Replicator			(testing)
// - PDF Shuttle			(testing)
// - WP Infinity Responder	(updating)


// ----------------------------------
// Load TGMPA for Recommended Plugins
// ----------------------------------
if (function_exists('tgmpa')) {

	// TESTME: this is done separately via Titan Checker now, but this calls
	// TGMPA separately as well as a new instance - which may or may not be desirable?

	// 1.9.8: recommend Titan Framework plugin for Wordpress.org installs
	add_filter('tgm_plugins_array', 'bioship_admin_tgm_titan_framework_check');

	if (!function_exists('bioship_admin_tgm_titan_framework_check')) {
	 function bioship_admin_tgm_titan_framework_check($plugins) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	 	// 2.0.9: only for WordPress.org version and when Titan Framework is not present
	 	if (!THEMEWPORG || THEMETITAN || class_exists('TitanFramework')) {return $plugins;}

	 	// 2.0.9: fix (override) Titan Framework checker 'required' setting to false
	 	$foundtitan = false;
	 	foreach ($plugins as $i => $plugin) {
	 		if ($plugin['slug'] == 'titan-framework') {
	 			$plugin['required'] = false;
	 			$plugins[$i] = $plugin;
	 			$foundtitan = true;
	 		}
	 	}
	 	if (!$foundtitan) {
			$plugins[] = array(
				'name' 		=> 'Titan Framework',
				'slug' 		=> 'titan-framework',
				'required' 	=> false
			);
		}
		return $plugins;
	 }
	}

	// TGMPA seems to need at least WP 3.7...
	if (!version_compare($wp_version,'3.7','<')) { //'>

		// TGMPA Theme Options Page - Notice Display Workaround
		// to use the plugin recommendation notice on the theme options page
		// - whether it has already been dismissed by the user or not! :-)

		if (isset($_REQUEST['page'])) {
			// 1.8.0: allow for Titan Framework admin page URL
			// 2.0.9: use THEMESLUG constance instead of vthemename
			if ( ($_REQUEST['page'] == 'options-framework')
			  || ($_REQUEST['page'] == THEMESLUG.'-options')
			  || ($_REQUEST['page'] == THEMESLUG.'_options') ) {
				// hook in before init as this is when TGM loads
				add_action('plugins_loaded', 'bioship_admin_tgm_notice_shift');
				// make the notice undismissable on theme page only via config filter
				add_filter('tgm_config_array', 'bioship_admin_tgm_dismiss_notice_off');
			}
		}

		// Notice Shift
		// ------------
		if (!function_exists('bioship_admin_tgm_notice_shift')) {
		 function bioship_admin_tgm_notice_shift() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

			// 2.1.1: use THEMEPREFIX constant instead of hardcoded
			$id = THEMESLUG.'-tgmpa'; // TGM instance id
			$currentuserid = get_current_user_id();
			if (get_user_meta($currentuserid, 'tgmpa_dismissed_notice_'.$id, true)) {
				add_user_meta($currentuserid, 'tgmpa_temp_notice_'.$id, 1);
				delete_user_meta($currentuserid, 'tgmpa_dismissed_notice_'.$id);
				add_action('all_admin_notices', 'bioship_admin_tgm_notice_unshift', 100);
			}
		 }
		}

		// Notice Unshift
		// --------------
		if (!function_exists('bioship_admin_tgm_notice_unshift')) {
		 function bioship_admin_tgm_notice_unshift() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

			$id = THEMESLUG.'-tgmpa'; // TGM instance id
			$currentuserid = get_current_user_id();
			// 2.0.9: fix to match temporary notice key
			delete_user_meta($currentuserid, 'tgmpa_temp_notice_'.$id);
			add_user_meta($currentuserid, 'tgmpa_dismissed_notice_'.$id, 1);
		 }
		}

		// Notice Off
		// ----------
		if (!function_exists('bioship_admin_tgm_dismiss_notice_off')) {
		 function bioship_admin_tgm_dismiss_notice_off($config) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// --- filter the theme page message ---
			$message = '<h3>'.esc_attr(__('BioShip Theme Framework Recommended Plugins','bioship')).'</h3><br>';
			$message = bioship_apply_filters('tgm_theme_page_message', $message);

			// --- change the existing config ---
			$config['dismissable'] = false;
			$config['dismiss_msg'] = $message;
			return $config;
		 }
		}


		// Register Plugins using TGMPA
		// ----------------------------
		// 1.8.0: renamed from muscle_register_plugins
		add_action('tgmpa_register', 'bioship_admin_register_plugins');

		// Create an array of plugins and config
		// -------------------------------------
		// Note: see includes/tgm-examples.php for more detailed plugin examples
		// Ref: http://tgmpluginactivation.com/configuration/

		if (!function_exists('bioship_admin_register_plugins')) {
		 function bioship_admin_register_plugins() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

			/*
			 * TGMPA: Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */

			// note: Originally recommended by SPML Skeleton Theme: Simple Shortcodes (smpl-shortcodes)

			$plugins = array(

				array(
					'name'      		=> 'AJAX Load More',
					'slug'      		=> 'ajax-load-more',
					'required'  		=> false,
				),

				array(
					'name'				=> 'Better WordPress Minify',
					'slug'				=> 'bwp-minify',
					'required' 			=> false,
				),

				array(
					'name'      		=> 'Open Graph Protocol Framework',
					'slug'      		=> 'open-graph-protocol-framework',
					'required'  		=> false,
				),

				array(
					'name'      		=> 'Theme My Login',
					'slug'      		=> 'theme-my-login',
					'required'  		=> false
				),

				array(
					'name'      		=> 'Theme Test Drive',
					'slug'      		=> 'theme-test-drive',
					'required'  		=> false,
				),

				array(
					'name'      		=> 'Widget Saver',
					'slug'      		=> 'widget-saver',
					'required'  		=> false,
				),

				array(
					'name'      		=> 'WP Subtitle',
					'slug'      		=> 'wp-subtitle',
					'required'  		=> false,
				),

				array(
					'name'      		=> 'WP Pagenavi',
					'slug'      		=> 'wp-pagenavi',
					'required'  		=> false,
				),

				// WordQuest (Theme) Plugins
				// -------------------------
				// 1.9.8: removed unreleased plugins
				// 2.0.5: re-added released plugins
				// 2.0.9: added WP AutoMedic release
				// 2.1.1: added Guten Free Options plugin
				// 2.1.1: added wq flag for source handling

				// --- AutoSave Net ---
				array(
					'name'      		=> 'AutoSave Net',
					'slug'      		=> 'autosave-net',
					'required'  		=> false,
					'wq'				=> true,
				),

				// --- Content Sidebars ---
				array(
					'name'      		=> 'Content Sidebars',
					'slug'      		=> 'content-sidebars',
					'required'  		=> false,
					'wq'				=> true,
				),

				// --- Guten Free Options ---
				array(
					'name'				=> 'Guten Free Options',
					'slug'				=> 'guten-free-options',
					'required'			=> false,
					'wq'				=> true,
				),

				// --- WP AutoMedic ---
				array(
					'name'				=> 'WP AutoMedic',
					'slug'				=> 'wp-automedic',
					'required'			=> false,
					'wq'				=> true,
				),

			);

			// 2.1.1: change plugin source info if not WordPress.Org version
			if (!THEMEWPORG) {
				$wqurl = 'http://wordquest.org';
				foreach ($plugins as $i => $plugin) {
					if (isset($plugin['wq']) && $plugin['wq']) {
						$plugins[$i]['source'] = $wqurl.'/downloads/packages/'.$plugin['slug'].'.zip';
						$plugins[$i]['external_url'] = $wqurl.'/plugins/'.$plugin['slug'].'/';
					}
				}
			}


			// --- filter the plugins array ---
			$plugins = bioship_apply_filters('tgm_plugins_array', $plugins);

			/*
			 * TGMPA: Array of configuration settings. Amend each line as needed.
			 *
			 */

			// --- filter the TGM plugins page message ---
			$message = '<h3>'.esc_attr(__('BioShip Theme Framework','bioship'));
			$message .= ' - '.esc_attr(__('Recommended Plugins','bioship')).'</h3><br>';
			$message = bioship_apply_filters('tgm_plugin_page_message', $message);

			// --- filter the bundle path ---
			// 2.0.9: changed from /includes/plugins/ for possible future usage
			$bundlespath = get_template_directory().'/includes/bundled/';
			$bundlespath = bioship_apply_filters('tgm_plugin_bundles_path', $bundlespath);

			// note: id (instance) set to bioship-tgmpa to prevent conflicts
			$config = array(
				'id'           => THEMESLUG.'-tgmpa',		// Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => $bundlespath,				// Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins',	// Menu slug.
				'parent_slug'  => 'themes.php',				// Parent menu slug.
				'capability'   => 'edit_theme_options',		// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,						// Show admin notices or not.
				'dismissable'  => true,						// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',						// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,						// Automatically activate plugins after installation or not.
				'message'      => $message,        			// Message to output right before the plugins table.

				'strings'      => array(
					'page_title'                      => __( 'Install Recommended Plugins', 'bioship' ),
					'menu_title'                      => __( 'Theme Plugins', 'bioship' ),
					'installing'                      => __( 'Installing Plugin: %s', 'bioship' ), // %s = plugin name.
					'oops'                            => __( 'Something went wrong with the plugin API.', 'bioship' ),
					'notice_can_install_required'     => _n_noop(
						'This theme requires the following plugin: %1$s.',
						'This theme requires the following plugins: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_can_install_recommended'  => _n_noop(
						'This theme recommends the following plugin: %1$s.',
						'This theme recommends the following plugins: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_cannot_install'           => _n_noop(
						'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_ask_to_update'            => _n_noop(
						'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_ask_to_update_maybe'      => _n_noop(
						'There is a plugin update available for: %1$s.',
						'There are updates available for the following plugins: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_cannot_update'            => _n_noop(
						'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_can_activate_required'    => _n_noop(
						'Warning! The following required plugin is currently inactive: %1$s.',
						'Warning! The following required plugins are currently inactive: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_can_activate_recommended' => _n_noop(
						'The following recommended plugin is currently inactive: %1$s.',
						'The following recommended plugins are currently inactive: %1$s.',
						'bioship'
					), // %1$s = plugin name(s).
					'notice_cannot_activate'          => _n_noop(
						'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
						'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
						'bioship'
					), // %1$s = plugin name(s).
					'install_link'                    => _n_noop('Begin installing plugin',	'Begin installing plugins', 'bioship') ,
					'update_link' 					  => _n_noop('Begin updating plugin', 'Begin updating plugins', 'bioship' ),
					'activate_link'                   => _n_noop('Begin activating plugin',	'Begin activating plugins',	'bioship' ),
					'return'                          => __( 'Return to Recommended Plugins Installer', 'bioship' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'bioship' ),
					'activated_successfully'          => __( 'The following plugin was activated successfully:', 'bioship' ),
					'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'bioship' ),  // %1$s = plugin name(s).
					'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'bioship' ),  // %1$s = plugin name(s).
					'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'bioship' ), // %s = dashboard link.
					'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'bioship' ),
					'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				),

			);

			// --- filter the TGMPA config ---
			$config = bioship_apply_filters('tgm_config_array', $config);

			// --- load TGM Plugin Activation ---
			tgmpa($plugins, $config);

		 }
		}
	}
}

