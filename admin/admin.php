<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === BioShip Admin Functions ===
 *
**/

// Development TODOs
// -----------------
// - move select resource build triggers internally
// - add MarkDown parser for readme.txt Upgrade Notice checking
// - add a CSS quicksave menu item (dropdown box?) to admin navbar
// -- (with a "leave this page without saving" catch block?)
// - add Titan installation info and link to Theme Tools page
// - TODO: retest activation/deactivation functionality

if (!function_exists('add_action')) {exit;}

// ==========================
// === admin.php Sections ===
// ==========================
// --- Debugging Output ---
// --- Admin Notice Helpers ---
// --- File System Helpers ---
// --- Load Theme Tools (tools.php) ---
// ---- Child Theme Install ---
// ---- Clone Child Theme ---
// ---- Theme Settings Transfers ---
// ---- Backup / Restore ---
// ---- Import / Export ---
// --- Load Theme Update Checker ---
// --- Load Freemius ---

// --- Modify Theme Admin Menus ---
// --- Theme Admin Page Menu Header ---
// --- Theme Info Page Section ---
// --- Check for Theme Updates ---
// --- Build Selective Resources ---
// --? Activation / Deactivation Actions ---
// --- Editor Screen Theme Options MetaBox ---
// --- Required/Recommend Plugins (via TGMPA) ---
// ==========================

// 1.5.0: moved admin specific functions here from main functions.php
// 1.8.5: moved admin.php from theme root to /admin/ subdirectory
// 2.0.5: moved update checker here and added Freemius loading
// 2.1.1: separated Theme Tools to separate tools.php file


// ------------------------
// === Debugging Output ===
// ------------------------

// ----------------
// Script Debugging
// ----------------
// 2.0.5: use development scripts/styles if theme debugging in admin area
// TODO: check this is hooked early enough to work properly
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

// --------------------------------------
// Get Directory File List with Recursion
// --------------------------------------
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

// -------------------------------
// Get Directory SubDirs Recursion
// -------------------------------
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


// ------------------------
// === Load Theme Tools ===
// ------------------------
// (Backup / Restore / Import / Export)
// 2.1.1: moved Theme Tools to a separate file
$tools = bioship_file_hierarchy('file', 'tools.php', $vthemedirs['admin']);
if ($tools) {include($tools); define('THEMETOOLS', true);}


// ---------------------------------
// === Load Theme Update Checker ===
// ---------------------------------
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

// ---------------------------
// === Check Theme Updates ===
// ---------------------------

// ----------------------------
// Echo Theme Updates Available
// ----------------------------
// 2.0.5: for admin_notice section
if (!function_exists('bioship_admin_theme_updates_echo')) {
 function bioship_admin_theme_updates_echo() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$themeupdates = bioship_admin_theme_updates_available();
	// only show updates if there is user capability for it
	$nocapstring = '<!-- NO THEME UPDATE CAPABILITY -->';

	if ( ($themeupdates != '') && !strstr($themeupdates, $nocapstring) ) {
		// 2.0.9: only show in admin notices section if an update is actually available
		$updatestring = '<!-- THEME UPDATE AVAILABLE -->';
		if (strstr($themeupdates, $updatestring)) {
			global $wp_version;
			if (version_compare($wp_version,'3.8', '<')) {$nagclass = 'updated';} else {$nagclass = 'update-nag';} // '>
			$themeupdates = str_replace('<br>', ' ', $themeupdates);
			echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">';
			echo $themeupdates.'</div></font><br>';
		}
	}
 }
}

// ---------------------------------------
// Generate Theme Updates Available Output
// ---------------------------------------
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
				$updatemessage = __( 'New Child Theme available.<br><a href="%2$s" title="%3$s" target=_blank>View v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship');
				$updatehtml .= sprintf($updatemessage, $themedisplayname, esc_url($update['url']), esc_attr($themedisplayname), $update['new_version'], $url, $onclick);
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
		// $updatehtml .= __('Parent Theme','bioship').':<br>';
		// $updatehtml .= __('BioShip Framework','bioship').' v'.$themeversion.'<br>';
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
				$updatemessage = __('New Framework version available!<br><a href="%2$s" title="%3$s" target=_blank>v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship');
				$updatehtml .= sprintf($updatemessage, $themedisplayname, esc_url($update['url']), esc_attr($themedisplayname), $update['new_version'], $url, $onclick );
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
		$current_user =  bioship_get_current_user();
		$updateurl = add_query_arg('email', $current_user->user_email, THEMESUPPORT);
		$updateurl = add_query_arg('planchange', $change, $updateurl);
		$updateurl = add_query_arg('plan', $plan, $updateurl);
		echo '<iframe src="'.$updateurl.'" style="display:none;"></iframe>';
	}
 }
}


// --------------------------------
// === Modify Theme Admin Menus ===
// --------------------------------
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

// ------------------------------
// Theme Options Page Redirection
// ------------------------------
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

// -----------------------------------------------
// Change the 'Theme Options' Framework Admin Menu
// -----------------------------------------------
// (Options Framework only)
if (!function_exists('bioship_admin_options_default_submenu')) {

 // note: this filter is priority 0 so added filters are applied later
 add_filter('optionsframework_menu', 'bioship_admin_options_default_submenu', 0);

 function bioship_admin_options_default_submenu($menu) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// --- Options Framework only ---
	if (!THEMEOPT) {return;}

	// --- only change if using as the active parent theme ---
	if (!THEMECHILD) {
		$menu['page_title'] = __('BioShip Options','bioship');
		$menu['menu_title'] = __('BioShip Options','bioship');
	}
	return $menu;
 }
}

// ---------------------------
// Add Appearance Submenu item
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

// --------------------------------------------------
// Add the Advanced Options (Customizer) Submenu item
// --------------------------------------------------
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

// ----------------------------------------------
// Hack the Theme Options Submenu Position to Top
// ----------------------------------------------
// (Appearance submenu for Options and Titan Framework)
if (!function_exists('bioship_admin_theme_options_position')) {

 add_action('admin_head', 'bioship_admin_theme_options_position');

 function bioship_admin_theme_options_position() {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 2.1.2: bug out if wordpress.org version
	if (THEMEWPORG) {return;}

	global $menu, $submenu;
	if (THEMEDEBUG) {echo "<!-- Admin Menu: "; print_r($menu); echo " -->";}

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
					$submenu['themes.php'][$submenukey][0] = __('Live Preview','bioship');
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
			echo "<!-- SubMenu: "; print_r($submenu); echo " -->";
			echo "<!-- themes.php position: ".esc_attr($themesposition)." -->";
			echo "<!-- last position: ".esc_attr($i)." -->";
		}

	}
 }
}

// -------------------------------------
// maybe Hack Theme Options submenu URLs
// -------------------------------------
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
// Add Theme Documentation Submenu Link
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

// -----------------------------------------------
// maybe shift Documentation Submenu to Theme Menu
// -----------------------------------------------
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

// --------------------------
// Support Link to New Window
// --------------------------
// 2.0.9: added this jquery link target tweak
if (!function_exists('bioship_support_link_external')) {
 function bioship_support_link_external() {
	echo "<script>jQuery(document).ready(function() {
		jQuery('#".esc_attr(THEMEPREFIX)."-support-link').parent().attr('target','_blank');
	});</script>";
 }
}

// --------------------------------
// Documentation Link to New Window
// --------------------------------
// 2.0.9: added this jquery link target tweak
if (!function_exists('bioship_documentation_link_external')) {
 function bioship_documentation_link_external() {
	echo "<script>jQuery(document).ready(function() {
		jQuery('#".esc_attr(THEMEPREFIX)."-doc-link').parent().attr('target','_blank');
	});</script>";
 }
}

// ---------------------------------------
// Add Theme Options to the Admin bar menu
// ---------------------------------------
// 1.8.5: moved here from muscle.php, option changed to filter
if (!function_exists('bioship_admin_adminbar_theme_options')) {

 // 2.0.5: check filter inside function for consistency
 add_action('wp_before_admin_bar_render', 'bioship_admin_adminbar_theme_options');

 function bioship_admin_adminbar_theme_options() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_admin_bar, $vthemename, $vthemedirs;

	// 2.1.2: check admin permissions
	if (!current_user_can('manage_options') && !current_user_can('edit_theme_options')) {return;}

	// --- filter adding of theme options link ---
	$adminbar = bioship_apply_filters('admin_adminbar_theme_options', true);
	if (!$adminbar) {return;}

	// --- set theme options link ---
	if (THEMEOPT || THEMETITAN || class_exists('TitanFramework')) {
		// 1.8.5: use add_query_arg here
		$themelink = admin_url('themes.php');
		if (THEMEOPT) {$themepage = 'options-framework';} else {$themepage = 'bioship-options';}
		$themelink = add_query_arg('page', $themepage, $themelink);
	} else {
		// 1.8.0: link to customize.php if no theme options page exists
		$themelink = admin_url('customize.php');
		$themelink = add_query_arg('return', urlencode(wp_unslash($_SERVER['REQUEST_URI'])), $themelink);
	}

	// --- theme test drive compatibility ---
	// 1.8.5: maybe append the Theme Test Drive querystring
	if (isset($_REQUEST['theme']) && ($_REQUEST['theme'] != '')) {
		$themelink = add_query_arg('theme', $_REQUEST['theme'], $themelink);
	}

	// --- add theme options link icon ---
	// 1.5.0: Add an Icon next to the Theme Options menu item
	// ref: http://wordpress.stackexchange.com/questions/172939/how-do-i-add-an-icon-to-a-new-admin-bar-item
	// default is set to \f115 Dashicon (an eye in a screen) in skin.php
	// and can be overridden using admin_adminbar_menu_icon filter
	$icon = bioship_file_hierarchy('url', 'theme-icon.png', $vthemedirs['image']);
	$icon = bioship_apply_filters('admin_adminbar_theme_options_icon', $icon);
	if ($icon) {
		// 2.0.9: fix for variable name (vthemesettingsicon)
		$iconspan = '<span class="theme-options-icon" style="
			float:left; width:22px !important; height:22px !important;
			margin-left: 5px !important; margin-top: 5px !important;
			background-image:url(\''.$icon.'\');"></span>';
	} else {$iconspan = '<span class="ab-icon"></span>';}

	// --- add admin bar link and title ---
	$title = __('Theme Options','bioship');
	$title = bioship_apply_filters('admin_adminbar_theme_options_title', $title);
	$menu = array('id' => 'theme-options', 'title' => $iconspan.$title, 'href' => $themelink);
	$wp_admin_bar->add_menu($menu);
 }
}

// --------------------------------
// Replace the Welcome in Admin bar
// --------------------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('bioship_admin_adminbar_replace_howdy')) {

 add_filter('admin_bar_menu', 'bioship_admin_adminbar_replace_howdy', 25);

 function bioship_admin_adminbar_replace_howdy($wp_admin_bar) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 2.1.1: filter whether to replace welcome message
	$replacehowdy = bioship_apply_filters('admin_adminbar_replace_howdy', true);
	if (!$replacehowdy) {return;}

	// 1.9.8: replaced deprecated function get_currentuserinfo();
	// 2.0.7: use new prefixed current user function
	$current_user = bioship_get_current_user();
	$username = $current_user->user_login;
	$myaccount = $wp_admin_bar->get_node('my-account');

	// --- filter the new node title ---
	// 1.5.5: fixed translation for Theme Check
	$newtitle = __('Logged in as', 'bioship').' '.$username;
	$newtitle = bioship_apply_filters('admin_adminbar_howdy_title', $newtitle);

	$wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $newtitle));
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


// ------------------------------------
// === Theme Admin Page Menu Header ===
// ------------------------------------

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

// -----------------------------------------
// Add Theme Update Display to Admin Notices
// -----------------------------------------
// 2.0.5: maybe show updates available for admin pages
add_action('admin_notices', 'bioship_admin_theme_updates_echo');

// ----------------
// Enqueue Thickbox
// ----------------
// 2.1.1: streamline thickbox enqueue check
$valid = array('bioship-options', 'options-framework', 'theme-info');
if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], $valid)) {
	add_action('admin_enqueue_scripts', 'bioship_admin_add_thickbox');
	// 2.0.5: remove theme updates available notice for theme options page
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

// -----------------------------------
// Theme Options Menu with Filter Tabs
// -----------------------------------
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
		if ($themelogo) {echo '<td><img src="'.esc_url($themelogo).'"></td>';}
		echo '<td width="10"></td>';

		// Theme Name and Version
		// ----------------------
		echo '<td><table id="themedisplayname" cellpadding="0" cellspacing="0"><tr height="40">';
		echo '<td style="vertical-align:middle;"><h2 style="margin:5px 0;">'.esc_attr(THEMEDISPLAYNAME).'</h2>';
		echo '</td><td width="10"></td><td>';
		// 2.0.5: fix to maybe display Child Theme Version constant
		if (THEMECHILD) {echo '<h3 style="margin:5px 0;">v'.esc_attr(THEMECHILDVERSION).'</h3>';}
		else {echo 'v'.esc_attr(THEMEVERSION);}
		echo '</td></tr>';

		// Small Theme Links
		// -----------------
		// TODO: Docs link could be a popup link to /bioship/admin/docs.php ?
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
		echo '</font></center></td></tr></table>';

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
			} else {$message = __('Check your file system owner/group permissions!','bioship');}
			if ($message != '') {echo '<div class="'.esc_attr($nagclass).'" style="padding:3px 10px;margin:0 0 10px 0;">'.esc_attr($message).'</div><br>';}
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
			echo '<font style="font-size:11pt;line-height:22px;"><b>'.__('Clone Child Theme to','bioship').':</b></font></td>';
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
	echo '<div id="extendwrapper" class="wrap"'.$hide.'>';
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
			var wpbodywidth = jQuery('#wpbody').width(); var newwidth = wpbodywidth - 270;
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
	$savecss = __('Save CSS', 'bioship'); $csssaved = __('CSS Saved!','bioship');
	echo "
		quicksavebutton = document.createElement('a');
		quicksavebutton.setAttribute('class','button button-primary');
		quicksavebutton.setAttribute('style','margin-left:-80px; float:right;');
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
		resizeoptionstables();
		jQuery('#floatdiv').fadeIn();
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
		// TODO: javascript check that stick_in_parent function exists?
		// echo "if (typeof stick_in_parent === 'function') {";
		echo "jQuery('#floatdiv').stick_in_parent({offset_top:100});";
		// echo "}";

	echo "});</script>";
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


// -------------------------
// Theme Options Page Styles
// -------------------------
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
		#themeoptionswrap {float:left;} #floatdiv {display:none; float:right;}
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
	echo "<style>".$styles."</style>";
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
		$button .= "<td align='center'><input id='sidebarsavebutton' type='button' class='button-primary' value='".__('Save Settings','bioship')."'></td>";
		$button .= "<td width='30'></td>";
		$button .= "<td><div style='line-height:1em;'><font style='font-size:8pt;'><a href='javascript:void(0);' style='text-decoration:none;' onclick='doshowhidediv(\"sidebarsettings\");hidesidebarsaved();'>";
		$button .= __('Sidebar','bioship')."<br>".__('Options','bioship')."</a></font></div></td>";
		$button .= "</tr></table>";
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
	wqhelper_sidebar_floatbox($args);
 }
}

// -------------
// QuickSave CSS
// -------------
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
if (!function_exists('bioship_theme_options_timeout_alert')) {

 // 2.1.1: fixed wp_ajax_no_priv_ prefix typo to wp_ajax_nopriv_
 add_action('wp_ajax_nopriv_bioship_theme_options_refresh_titan_nonce', 'bioship_theme_options_timeout_alert');

 function bioship_theme_options_logged_out_alert() {
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
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$boxid.'\');"><span id="'.esc_attr($boxid).'-arrow">&#9662;</span> '.esc_attr($boxtitle).'</h3>';
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
	echo '</span> '.$boxtitle.'</h3>';
	if (!$welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	echo '<div class="inside" id="'.esc_attr($boxid).'-inside"'.esc_attr($hide).'><center>';
		bioship_admin_documentation_box($welcome);
	echo '</center></div></div>';

	// Theme Tools - Wide (collapsed)
	// ------------------------------
	$boxid = 'themetools'; $boxtitle = __('Theme Settings Tools','bioship');
	echo '<div id="'.esc_attr($boxid).'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span id="'.esc_attr($boxid).'-arrow">&#9662;</span> '.esc_attr($boxtitle).'</h3>';
	echo '<div class="inside" id="'.$boxid.'-inside" style="display:none;"><center>';
		bioship_admin_theme_tools_forms();
	echo '</center></div></div>';

	// Left Column - Links / Extensions
	// --------------------------------
	echo '<div id="extendcolumn">';

		// BioShip Theme Links
		// -------------------
		$boxid = 'bioshiplinks'; $boxtitle = __('Theme Links','bioship');
		echo '<div id="'.esc_attr($boxid).'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.esc_js($boxid).'\');"><span>'.esc_js($boxtitle).'</span></h2>';
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
	echo '<div id="quickstart"'.$hide.'>';
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
	$quickstart = '<div id="showquickstart"'.$hide.'>';
	$quickstart .= '<a href="javascript:void(0);" onclick="showquickstart();">';
	$quickstart .= esc_attr(__('Show','bioship')).' '.esc_attr(__('QuickStart Guide','bioship')).'</a></div>';
	if (!$welcome) {$hide = ' style="display:none;"';} else {$hide = '';}
	$quickstart .= '<div id="hidequickstart"'.$hide.'>';
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
	$docindex = str_replace('a href="docs.php?page=', 'a class="doc-thickbox" href="#', $docindex);
	echo $docindex;

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

// ----------------------------------
// Trigger Build Selective CSS and JS
// ----------------------------------
// TODO: move select resource build triggers internally

// --- Options Framework ---
if ( (isset($_GET['page'])) && (isset($_GET['settings-updated'])) ) {
	if ( ($_GET['page'] == 'options-framework') && ($_GET['settings-updated'] == 'true') ) {
		add_action('admin_notices', 'bioship_admin_build_selective_resources');
	}
}
// --- Titan Framework ---
// 1.8.0: need to trigger differently for Titan save
if ( (isset($_GET['page'])) && (isset($_GET['message'])) ) {
	if ( ($_GET['page'] == $vthemename.'-options') && ($_GET['message'] == 'saved') ) {
		add_action('admin_notices', 'bioship_admin_build_selective_resources');
	}
}
// --- Customizer ---
// 1.8.0: ...also need to trigger this after a Customizer save...
add_action('customize_save_after', 'bioship_admin_build_selective_resources');


// --------------------------
// Build Selective CSS and JS
// --------------------------
if (!function_exists('bioship_admin_build_selective_resources')) {
 function bioship_admin_build_selective_resources() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemename, $vthemesettings, $vthemedirs;

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


// -----------------------------
// === Editor Screen Metabox ===
// -----------------------------
// to override custom values (via muscle_get_display_overrides in muscle.php)

// Perpage Override Metabox
// ------------------------
// Meta Key Notes
// 2.1.1: keys now use _THEMEPREFIX_ instead of just _
// _display_overrides (array)		- header, footer, navigation, secondarynav,
// 									  sidebar, subsidebar, headerwidgets, footerwidgets,
//			 						  image, title, subtitle, metatop, metabottom, authorbio
// _templating_overrides (array)	- TODO: add missing values
// _removefilters (array)			- wpautop, wptexturize, convertsmilies, convertchars
// _thumbnailsize (single key)		- stores override
// _perpoststyles (single key)		- stores style additions

// -----------------------
// Add the Perpage Metabox
// -----------------------
// 1.8.0: renamed from muscle_add_metabox
// 2.0.5: move add_action inside for consistency
if (!function_exists('bioship_admin_add_theme_metabox')) {

 add_action('admin_init', 'bioship_admin_add_theme_metabox');

 function bioship_admin_add_theme_metabox() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get custom post types ----
	// TODO: add multicheck option for Theme Options Metabox on CPTs
	$cpts = array('post', 'page');
	$args = array('public' => true, '_builtin' => false);
	$cptlist = get_post_types($args, 'names', 'and');
	$cpts = array_merge($cpts, $cptlist);
	// 2.0.5: add filter for post types metabox
	$cpts = bioship_apply_filters('admin_theme_metabox_post_types', $cpts);

	// --- metabox position ---
	// 2.1.1: added filter for metabox priority position
	$priority = bioship_apply_filters('admin_theme_metabox_priority', 'high');

	// --- add metaboxes ---
	foreach ($cpts as $cpt) {
		add_meta_box('theme_metabox', __('Theme Display Overrides','bioship'), 'bioship_admin_theme_metabox', $cpt, 'side', $priority);
	}
 }
}

// ---------------------
// PerPage Theme Metabox
// ---------------------
// 1.8.0: renamed from muscle_theme_metabox
// 2.0.0: added missing translation wrappers
if (!function_exists('bioship_admin_theme_metabox')) {
 function bioship_admin_theme_metabox() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;

	// --- get post data ---
	// 2.1.1: handle new post (no post ID)
	global $post;
	if (is_object($post)) {$postid = $post->ID; $posttype = $post->post_type;}
	else {
		$postid = ''; $posttype = 'post';
		if (isset($_REQUEST['post_type'])) {$posttype = $_REQUEST['post_type'];}
	}

	// --- get current override values ---
	$display = bioship_muscle_get_display_overrides($postid);
	$override = bioship_muscle_get_templating_overrides($postid);
	$removefilters = bioship_muscle_get_content_filter_overrides($postid);

	if (THEMEDEBUG) {
		echo "<!-- Post ID: ".esc_attr($postid)." -->";
		echo "<!-- Display Overrides: ".esc_attr(print_r($display,true))." -->";
		echo "<!-- Templating Overrides: ".esc_attr(print_r($override,true))." -->";
		echo "<!-- Filter Overrides: ".esc_attr(print_r($removefilters,true))." -->";
	}

	// --- option tab script ---
	echo "<script>
	function clickthemeoptions(themeoption) {
		if (document.getElementById('themetabclicked').value == 'mouseover') {var mouseover = true;}
		if ( (document.getElementById('theme'+themeoption).style.display == 'none') || (mouseover == true) ) {
			document.getElementById('themeoptionstab').value = themeoption;
			document.getElementById('themetabclicked').value = 'clicked';
			showthemeoptions(themeoption);
		} else {
			document.getElementById('themeoptionstab').value = '';
			document.getElementById('themetabclicked').value = '';
			hidethemeoptions(themeoption);
		}
	}
	function maybeshowthemeoptions(themeoption) {
		if (document.getElementById('themetabclicked').value == 'clicked') {return;}
		document.getElementById('themetabclicked').value = 'mouseover';
		showthemeoptions(themeoption);
	}
	function showthemeoptions(themeoption) {
		document.getElementById('themelayout').style.display = 'none';
		document.getElementById('themesidebar').style.display = 'none';
		document.getElementById('themecontent').style.display = 'none';
		document.getElementById('themestyles').style.display = 'none';
		/* document.getElementById('themefilters').style.display = 'none'; */
		document.getElementById('theme'+themeoption).style.display = '';
		document.getElementById('themelayoutbutton').style.backgroundColor = '#EEE';
		document.getElementById('themesidebarbutton').style.backgroundColor = '#EEE';
		document.getElementById('themecontentbutton').style.backgroundColor = '#EEE';
		document.getElementById('themestylesbutton').style.backgroundColor = '#EEE';
		/* document.getElementById('themefiltersbutton').style.backgroundColor = '#EEE'; */
		document.getElementById('theme'+themeoption+'button').style.backgroundColor = '#DDD';
	}
	function hidethemeoptions(themeoption) {
		document.getElementById('theme'+themeoption).style.display = 'none';
		document.getElementById('theme'+themeoption+'button').style.backgroundColor = '#EEE';
	}
	function checkcustomtemplates() {
		selectelement = document.getElementById('_sidebartemplate');
		template = selectelement.options[selectelement.selectedIndex].value;
		if (template == 'custom') {document.getElementById('sidebarcustom').style.display = '';}
		else {document.getElementById('sidebarcustom').style.display = 'none';}
		selectelement = document.getElementById('_subsidebartemplate');
		subtemplate = selectelement.options[selectelement.selectedIndex].value;
		if (subtemplate == 'custom') {document.getElementById('subsidebarcustom').style.display = '';}
		else {document.getElementById('subsidebarcustom').style.display = 'none';}
		if ( (template == 'custom') || (subtemplate == 'custom') ) {
			document.getElementById('customtemplatelabel').style.display = '';
		} else {document.getElementById('customtemplatelabel').style.display = 'none';}
	}</script>";


	// Button Tabs
	// -----------
	// 1.9.5: merged filters with content tab and add separate sidebar tab
	// 1.9.5: changed _hide prefix to _display_ prefix for form option names

	// --- get current options tab ---
	// 1.8.0: use separate tab value so only for metabox itself
	// 2.1.1: use prefixed post meta key for theme options tab
	$settingstab = ''; // empty default
	if ($postid != '') {$tab = get_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', true);}
	if ($tab) {$settingstab = $tab;}

	// --- tab button styles ---
	// 2.1.1: added a tag text decoration style
	echo "<style>.themeoptionbutton {background-color:#E0E0EF; padding:5px; border-radius:5px;}
	.themeoptionbutton a {text-decoration:none;}</style>";

	// --- theme options tab buttons ---
	// 2.1.0: removed filters tab cell/button remnant
	// TODO: maybe convert a tags to input buttons ?
	echo "<center><table><tr>";

		// --- content tab button ---
		if ($settingstab == 'content') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td><div id='themecontentbutton' class='themeoptionbutton'".$bgcolor.">";
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"content\");' onclick='clickthemeoptions(\"content\");'>";
			echo esc_attr(__('Content','bioship'))."</a>";
		echo "</div></td>";

		// --- sidebar tab button ---
		if ($settingstab == 'sidebar') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td width='10'></td><td><div id='themesidebarbutton' class='themeoptionbutton'".$bgcolor.">";
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"sidebar\");' onclick='clickthemeoptions(\"sidebar\");'>";
			echo esc_attr(__('Sidebars','bioship'))."</a>";
		echo "</div></td>";

		// --- layout tab button ---
		if ($settingstab == 'layout') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td width='10'></td><td><div id='themelayoutbutton' class='themeoptionbutton' class='themeoptionbutton'".$bgcolor.">";
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"layout\");' onclick='clickthemeoptions(\"layout\");'>";
			echo esc_attr(__('Layout','bioship'))."</a>";
		echo "</div></td>";

		// --- styles tab button ---
		if ($settingstab == 'styles') {echo " style='background-color:#DDD;'";}
		echo "<td width='10'></td><td><div id='themestylesbutton' class='themeoptionbutton'".$bgcolor.">";
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"styles\");' onclick='clickthemeoptions(\"styles\");'>";
			echo esc_attr(__('Styles','bioship'))."</a>";
		echo "</div></td>";

	echo "</tr></table>";


	// Content Override Tab
	// --------------------
	if ($settingstab != 'content') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themecontent'".$hide.">";
	echo "<table cellpadding='0' cellspacing='0'>";

		// Thumbnail Size Override
		// -----------------------

		// --- setup available thumbnail sizes ---
		if ($posttype == 'page') {$thumbdisplay = esc_attr(__('Featured Image','bioship')); $thumbdefault = $vthemesettings['pagethumbsize'];}
		else {$thumbdisplay = esc_attr(__('Thumbnail','bioship')); $thumbdefault = $vthemesettings['postthumbsize'];}
		$thumbarray = array(
			'thumbnail' => esc_attr(__('Thumbnail','bioship')).' ('.get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h').')',
			'medium' => esc_attr(__('Medium','bioship')).' ('.get_option('medium_size_w').' x '.get_option('medium_size_h').')',
			'large' => esc_attr(__('Large','bioship')).' ('.get_option('large_size_w').' x '.get_option('large_size_h').')',
			'full' => esc_attr(__('Full Size','bioship')).' ('.__('original','bioship').')'
		);

		// --- get additional image sizes ---
		global $_wp_additional_image_sizes;
		$image_sizes = get_intermediate_image_sizes();
		$oldsizenames = array('squared150', 'squared250', 'video43', 'video169');
		foreach ($image_sizes as $size_name) {
			if ( ($size_name != 'thumbnail') && ($size_name != 'medium') && ($size_name != 'large') ) {
				// 1.9.8: fix to sporadic undefined index warning (huh? size names should match?)
				if (isset($_wp_additional_image_sizes[$size_name])) {
					// 2.0.5: no longer output old size names as options
					if (!in_array($size_name, $oldsizenames)) {
						$thumbarray[$size_name] = $size_name.' ('.$_wp_additional_image_sizes[$size_name]['width'].' x '.$_wp_additional_image_sizes[$size_name]['height'].')';
					}
				}
			}
		}

		// --- get thumbnail size override ---
		// 1.8.0: keep individual meta key for this
		// 2.1.1: added theme prefix to thumbnail size metakey
		$thumbnailsize = '';
		if ($postid != '') {$thumbnailsize = get_post_meta($postid, '_'.THEMEPREFIX.'_thumbnailsize', true);}

		// --- maybe convert old thumbnail size names ---
		// 2.0.5: maybe convert to prefixed names and update meta
		$newthumbsize = false;
		if ($thumbnailsize == 'squared150') {$newthumbsize = 'bioship-150s';}
		elseif ($thumbnailsize == 'squared250') {$newthumbsize = 'bioship-250s';}
		elseif ($thumbnailsize == 'video43') {$newthumbsize = 'bioship-4-3';}
		elseif ($thumbnailsize == 'video169') {$newthumbsize = 'bioship-16-9';}
		elseif ($thumbnailsize == 'opengraph') {$newthumbsize = 'bioship-opengraph';}
		if ($newthumbsize) {
			update_post_meta($postid, '_'.THEMEPREFIX.'_thumbnailsize', $newthumbsize);
			$thumbnailsize = $newthumbsize;
		}

		// --- thumbnail size override selector ---
		// 2.0.7: fix to text domin typo (bioship.)
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td colspan='3' align='center'>";
			echo "<b>".$thumbdisplay." ".esc_attr(__('Size','bioship'))."</b> (".esc_attr(__('default','bioship'))." ".esc_attr($thumbdefault).")<br>";
			echo "<select name='_thumbnailsize' id='_thumbnailsize' style='font-size:9pt;'>";
				if ($thumbnailsize == '') {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value=''".$selected.">".esc_attr(__('Theme Settings Default','bioship'))."</option>";
				// 2.1.1: fix to missing option value for no thumbnail
				if ($thumbnailsize == 'off') {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='off'".$selected.">".esc_attr(__('No Thumbail Output','bioship'))."</option>";
				foreach ($thumbarray as $key => $value) {
					if ($thumbnailsize == $key) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".$key."'".$selected.">".esc_attr($value)."</option>";
				}
			echo "</select>";
		echo "</td></tr>";

		// --- hide thumbnail ---
		// 2.1.1.: added missing id for checkbox field
		echo "<tr><td>".__('Hide','bioship')." ".$thumbdisplay."</td><td width='10'></td><td align='center'>";
			if ($display['image'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_image' id='_display_image' value='1'".$checked.">";
		echo "</td></tr>";

		// Content Display Overrides
		// -------------------------

		// --- content override headings ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Content Display','bioship'))."</b></td><td width='10'></td>";
		echo "<td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- content title ---
		echo "<tr><td>".esc_attr(__('Title','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['title'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_title' id='_display_title' value='1'".$checked.">";
		echo "</td></tr>";

		// ---- content subtitle ---
		echo "<tr><td>".esc_attr(__('Subtitle','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['subtitle'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_subtitle' id='_display_subtitle' value='1'".$checked.">";
		echo "</td></tr>";

		// --- content meta top ---
		echo "<tr><td>".esc_attr(__('Top Meta','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['metatop'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_metatop' id='_display_metatop' value='1'".$checked.">";
		echo "</td></tr>";

		// --- content meta bottom ---
		echo "<tr><td>".esc_attr(__('Bottom Meta','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['metabottom'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_metabottom' id='_display_metabottom' value='1'".$checked.">";
		echo "</td></tr>";

		// --- author bio box ---
		echo "<tr><td>".__('Author Bio','bioship')."</td><td width='10'></td><td align='center'>";
			if ($display['authorbio'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_authorbio' id='_display_authorbio' value='1'".$checked.">";
		echo "</td></tr>";


		// Filter Overrides
		// ----------------
		// 1.9.5: merged to content tab from separate filters tab

		// --- content filters heading ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Content Filter','bioship'))."</b></td><td></td>";
		echo "<td align='center'>".esc_attr(__('Disable','bioship'))."</td></tr>";

		// --- wpautop filter ---
		echo "<tr><td>wpautop</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['wpautop']) && ($removefilters['wpautop'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_wpautop' id='_wpautop' value='1'".$checked.">";
		echo "</td></tr>";

		// --- wptexturize filter ---
		echo "<tr><td>wptexturize</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['wptexturize']) && ($removefilters['wptexturize'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_wptexturize' id='_wptexturize' value='1'".$checked.">";
		echo "</td></tr>";

		// --- convert_smilies filter ---
		echo "<tr><td>convert_smilies</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['convertsmilies']) && ($removefilters['convertsmilies'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_convertsmilies' id='_convertsmilies' value='1'".$checked.">";
		echo "</td></tr>";

		// --- convert_chars filter ---
		echo "<tr><td>convert_chars</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['convertchars']) && ($removefilters['convertchars'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_convertchars' id='_convertchars' value='1'".$checked.">";
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-content'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"content\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close content tab ---
	echo "</table></div>";


	// Sidebar Overrides
	// -----------------
	// TODO: add display of total column width ?
	// 1.9.5: separate tab for sidebar overrides
	if ($settingstab != 'sidebar') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themesidebar'".$hide.">";
	echo "<table cellpadding='0' cellspacing='0'>";

		// -- set column options ---
		$subsidebarcolumns = array(
			'' => __('Default','bioship'),
			'one' => ' '.__('1','bioship').' ', 'two' => ' '.__('2','bioship').' ',
			'three' => ' '.__('3','bioship').' ', 'four' => ' '.__('4','bioship').' ',
			'five' => ' '.__('5','bioship').' ', 'six' => ' '.__('6','bioship').' ',
			'seven' => ' '.__('7','bioship').' ', 'eight' => ' '.__('8','bioship').' ',
		);
		$sidebarcolumns = array_merge($subsidebarcolumns, array(
			'nine'	=> ' '.__('9','bioship').' ', 'ten' => __('10','bioship').' ',
			'eleven' => __('11','bioship').' ', 'twelve' => __('12','bioship').' ',
		) );
		$contentcolumns = array_merge($sidebarcolumns, array(
			'thirteen' => __('13','bioship').' ', 'fourteen' => __('14','bioship').' ',
			'fifteen' => __('15','bioship').' ', 'sixteen' => __('16','bioship').' ',
			'seventeen' => __('17','bioship').' ', 'eighteen' => __('18','bioship').' ',
			'nineteen' => __('19','bioship').' ', 'twenty' => __('20','bioship').' ',
			'twentyone' => __('21','bioship').' ', 'twentytwo' => __('22','bioship').' ',
			'twentythree' => __('23','bioship').' ', 'twentyfour' => __('24','bioship').' '
		) );

		// --- content columns ---
		echo "<tr><td colspan='5' align='center'>";
			echo "<table><tr><td>".esc_attr(__('Content Columns','bioship'))."</td>";
				echo "<td width='10'></td><td>";
				echo "<select name='_contentcolumns' id='_contentcolumns'>";
				foreach ($contentcolumns as $width => $label) {
					if ($override['contentcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".$width."'".$selected.">".esc_attr($label)."</option>";
				}
				echo "</select>";
			echo "</td></tr></table>";
		echo "</td></tr>";

		// --- column headings ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td></td><td></td><td align='center'><b>".esc_attr(__('Sidebar','bioship'))."</b></td><td></td>";
		echo "<td align='center'><b>".esc_attr(__('SubSidebar','bioship'))."</b></td></tr>";
		echo "<tr><td align='right'>".esc_attr(__('Columns','bioship'))."</td><td width='5'></td>";

		// --- sidebar columns ---
		echo "<td>";
			echo "<select name='_sidebarcolumns' id='_sidebarcolumns' style='width:100%;font-size:9pt;'>";
			foreach ($sidebarcolumns as $width => $label) {
				if ($override['sidebarcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".$width."'".$selected.">".esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td><td width='5'></td>";

		// --- subsidebar columns ---
		echo "<td>";
			echo "<select name='_subsidebarcolumns' id='_subsidebarcolumns' style='width:100%;font-size:9pt;'>";
			foreach ($subsidebarcolumns as $width => $label) {
				if ($override['subsidebarcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".$width."'".$selected.">".esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td></tr>";

		// Sidebar Templates
		// -----------------
		$sidebartemplates = array( '' => __('Default','bioship'), 'off' => __('None','bioship'), 'blank' => __('Blank','bioship'), 'primary' => __('Primary','bioship') );
		$subsidebartemplates = array( '' => __('Default','bioship'), 'off' => __('None','bioship'), 'subblank' => __('Blank','bioship'), 'subsidiary' => __('Subsidiary','bioship') );

		// TODO: use the new sidebar template search function here ? (bioship_get_sidebar_templates_info in skull.php) ?
		$templates = array( 'page' => __('Page','bioship'), 'post' => __('Post','bioship'),
			'front' => __('Front','bioship'), 'home' => __('Home','bioship'), 'archive' => __('Archive','bioship'),
			'category' => __('Category','bioship'), 'taxonomy' => __('Taxonomy','bioship'),
			'tag' => __('Tag','bioship'), 'author' => __('Author','bioship'), 'date' => __('Date','bioship'),
			'search' => __('Search','bioship'), 'notfound' => __('NotFound','bioship') );
		$sidebartemplates = array_merge($sidebartemplates, $templates);
		foreach ($templates as $key => $label) {$subsidebartemplates['sub'.$key] = $label;}
		$sidebartemplates['custom'] = $subsidebartemplates['custom'] = __('Custom','bioship');

		// --- sidebar template headings ---
		// 2.1.1: added missing translation wrappers
		echo "<tr><td style='vertical=align:top;' align='right'>";
			echo __('Template','bioship')."<br>";
			if ( ($override['sidebartemplate'] != 'custom') && ($override['subsidebartemplate'] != 'custom') ) {$hide = "display:none;";} else {$hide = '';}
			echo "<div id='customtemplatelabel' style='margin-top:10px;".$hide."'>".esc_attr(__('Slug','bioship')).":</div>";
		echo "</td><td width='5'></td>";

		// --- sidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo "<td style='vertical-align:top;'>";
			echo "<select name='_sidebartemplate' id='_sidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
				foreach ($sidebartemplates as $template => $label) {
					if ($override['sidebartemplate'] == $template) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".esc_attr($template)."'".$selected.">".esc_attr($label)."</option>";
				}
			echo "</select><br>";

			// --- custom sidebar template ---
			if ($override['sidebartemplate'] != 'custom') {$hide = " style='display:none;'";} else {$hide = '';}
			echo "<div id='sidebarcustom'".$hide.">";
				// 2.1.1: added missing id attribute for input
				echo "<input type='text' name='_sidebarcustom' id='_sidebarcustom' style='width:80px;font-size:9pt;' value='".esc_attr($override['sidebarcustom'])."'>";
			echo "</div>";
		echo "</td><td width='5'></td>";

		// --- subsidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo "<td style='vertical-align:top;'>";
			echo "<select name='_subsidebartemplate' id='_subsidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
				foreach ($subsidebartemplates as $template => $label) {
					if ($override['subsidebartemplate'] == $template) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".esc_attr($template)."'".$selected.">".esc_attr($label)."</option>";
				}
			echo "</select><br>";

			// --- custom subsidebar template ---
			if ($override['subsidebartemplate'] != 'custom') {$hide = " style='display:none;'";} else {$hide = '';}
			echo "<div id='subsidebarcustom'".$hide.">";
				// 2.1.1: added missing id attribute for input
				echo "<input type='text' name='_subsidebarcustom' id='_subsidebarcustom' style='width:80px;font-size:9pt;' value='".esc_attr($override['subsidebarcustom'])."'>";
			echo "</div>";
		echo "</td></tr>";

		// --- main sidebar position ---
		$sidebarpositions = array( '' => __('Default','bioship'), 'left' => __('Left','bioship'), 'right' => __('Right','bioship') );
		echo "<tr><td align='right'>";
			// 2.1.1: added missing translation wrapper
			echo esc_attr(__('Position','bioship'));
		echo "</td><td width='5'></td><td>";
			echo "<select name='_sidebarposition' id='_sidebarposition' style='width:100%;font-size:9pt;'>";
			foreach ($sidebarpositions as $position => $label) {
				if ($override['sidebarposition'] == $position) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".esc_attr($position)."'".$selected.">".esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td><td width='5'></td>";

		// --- subsidebar position ---
		$subsidebarpositions = array( '' => __('Default','bioship'), 'opposite' => __('Opposite','bioship'),
			'internal' => __('Internal','bioship'), 'external' => __('External','bioship') );
		echo "<td>";
			// 2.1.1: added missing id field for subsidebar position
			echo "<select name='_subsidebarposition' id='_subsidebarposition' style='width:100%;font-size:9pt;'>";
			foreach ($subsidebarpositions as $position => $label) {
				if ($override['subsidebarposition'] == $position) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".esc_attr($position)."'".$selected.">".esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td></tr>";
		echo "</table>";

		// --- sidebar display headings ---
		echo "<table cellpadding='0' cellspacing='0'><tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Sidebar Display','bioship'))."</b></td>";
		echo "<td></td><td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- main sidebar hide ---
		echo "<tr><td>".esc_attr(__('Main Sidebar','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['sidebar'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_sidebar' id='_display_sidebar' value='1'".$checked.">";
		echo "</td></tr>";

		// --- subsidebar hide ---
		echo "<tr><td>".esc_attr(__('SubSidebar','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['subsidebar'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_subsidebar' id='_display_subsidebar' value='1'".$checked.">";
		echo "</td></tr>";

		// --- header widgets hide ---
		echo "<tr><td>".esc_attr(__('Header Widgets','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['headerwidgets'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_headerwidgets' id='_display_headerwidgets' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer widgets hide ---
		echo "<tr><td>".esc_attr(__('Footer Widgets','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footerwidgets'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footerwidgets' id='_display_footerwidgets' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer widget area 1 ---
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 1</td><td width='10'></td><td align='center'>";
			if ($display['footer1'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer1' id='_display_footer1' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer widget area 2 ---
		echo "<tr><td>".__('Footer Area','bioship')." 2</td><td width='10'></td><td align='center'>";
			if ($display['footer2'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer2' id='_display_footer2' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer widget area 3 ---
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 3</td><td width='10'></td><td align='center'>";
			if ($display['footer3'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer3' id='_display_footer3' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer widget area 4 ----
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 4</td><td width='10'></td><td align='center'>";
			if ($display['footer4'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer4' id='_display_footer4' value='1'".$checked.">";
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-sidebar'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"sidebar\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close sidebar overrides tab ---
	echo "</table></div>";


	// Layout Overrides
	// ----------------
	if ($settingstab != 'layout') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themelayout'".$hide.">";
	echo "<table cellpadding='0' cellspacing='0'>";

		// --- layout overrides heading ---
		echo "<tr><td colspan='3' align='center'>";
			echo "<b>".esc_attr(__('Layout Display Overrides','bioship'))."</b>";
		echo "</td></tr>";

		// --- no wrap margins (full width) ---
		// 1.8.5: added full width container option (no wrap margins)
		echo "<tr><td>".esc_attr(__('No Wrap Margins','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['wrapper'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_wrapper' id='_display_wrapper' value='1'".$checked.">";
		echo "</td></tr>";

		// --- hide header ---
		echo "<tr><td>".esc_attr(__('Hide Header','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['header'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_header' id='_display_header' value='1'".$checked.">";
		echo "</td></tr>";

		// --- hide footer ---
		echo "<tr><td>".esc_attr(__('Hide Footer','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footer'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer' id='_display_footer' value='1'".$checked.">";
		echo "</td></tr>";

		// TODO: general layout displays?
		// Header Logo / Title Text / Description / Extras
		// Footer Extras / Site Credits

		// --- navigation display headings ---
		// 1.9.8: fix to headernav and footernav keys
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Navigation Display','bioship'))."<b></td>";
		echo "<td></td><td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- main navigation menu ---
		echo "<tr><td>".esc_attr(__('Main Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['navigation'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_navigation' id='_display_navigation' value='1'".$checked.">";
		echo "</td></tr>";

		// --- secondary navigation ---
		echo "<tr><td>".esc_attr(__('Secondary Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['secondarynav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_secondarynav' id='_display_secondarynav' value='1'".$checked.">";
		echo "</td></tr>";

		// --- header navigation menu ---
		echo "<tr><td>".esc_attr(__('Header Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['headernav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_headernav' id='_display_headernav' value='1'".$checked.">";
		echo "</td></tr>";

		// --- footer navigation menu ---
		echo "<tr><td>".esc_attr(__('Footer Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footernav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footernav' id='_display_footernav' value='1'".$checked.">";
		echo "</td></tr>";

		// --- breadcrumbs ---
		echo "<tr><td>".esc_attr(__('Breadcrumbs','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['breadcrumb'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_breadcrumb' id='_display_breadcrumb' value='1'".$checked.">";
		echo "</td></tr>";

		// --- page navi ---
		echo "<tr><td>".esc_attr(__('Post/Page Navi','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['pagenavi'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_pagenavi' id='_display_pagenavi' value='1'".$checked.">";
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-layout'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"layout\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close layout override tab ---
	echo "</table></div>";


	// Style Overrides
	// ---------------
	// 1.8.0: javascript to expand/collapse style box
	// 2.1.1: added marginTop to help prevent editor overlay
	echo "<script>function expandpostcss() {
		document.getElementById('expandpostcss').style.display = 'none';
		document.getElementById('collapsepostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '600px';
		document.getElementById('perpoststyles').style.height = '300px';
		perpoststylebox = document.getElementById('perpoststylebox');
		perpoststylebox.style.width = '620px';
		perpoststylebox.style.marginTop = '40px';
		perpoststylebox.style.marginLeft = '-375px';
		perpoststylebox.style.paddingLeft = '20px';
		perpoststylebox.style.paddingTop = '20px';
		perpoststylebox.style.paddingBottom = '15px';
		perpoststylebox.style.borderLeft = '1px solid #CCC';
	}
	function collapsepostcss() {
		document.getElementById('collapsepostcss').style.display = 'none';
		document.getElementById('expandpostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '100%';
		document.getElementById('perpoststyles').style.height = '200px';
		perpoststylebox = document.getElementById('perpoststylebox');
		perpoststylebox.style.width = '100%';
		perpoststylebox.style.marginTop = '0px';
		perpoststylebox.style.marginLeft = '0px';
		perpoststylebox.style.paddingLeft = '0px';
		perpoststylebox.style.paddingTop = '0px';
		perpoststylebox.style.paddingBottom = '0px';
		perpoststylebox.style.borderLeft = '0';
	}</script>";

	// 2.1.1: added .quicksavesettings class
	echo "<style>#quicksavedcss, .quicksavesettings {display:none; padding:3px 6px; max-width:80px; ";
	echo "font-size:10pt; color: #333; font-weight:bold; background-color: lightYellow; border: 1px solid #E6DB55;}</style>";

	// --- get per post styles ---
	// 1.8.0: keep individual meta key for this
	// 2.1.1: added theme prefix to post metakey
	$perpoststyles = '';
	if ($postid != '') {$perpoststyles = get_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', true);}

	// --- per post styles tab ---
	if ($settingstab != 'styles') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themestyles'".$hide.">";
	echo "<table cellpadding='0' cellspacing='0' style='width:100%;overflow:visible;'>";

		// --- style textarea ---
		echo "<tr><td colspan='2' align='center'><b>".esc_attr(__('Post Specific CSS Style Rules','bioship'))."</b></td></tr>";
		echo "<tr><td><div id='expandpostcss' style='float:left; margin-left:10px;'><a href='javascript:void(0);' onclick='expandpostcss();' style='text-decoration:none;'>&larr; ".__('Expand','bioship')."</a></div>";
		echo "<div id='collapsepostcss' style='float:right; margin-right:20px; display:none;'><a href='javascript:void(0);' onclick='collapsepostcss();' style='text-decoration:none;'>".__('Collapse','bioship')." &rarr;</a></div></tr>";
		echo "<tr><td colspan='2'><div id='perpoststylebox' style='background:#FFF;'>";
		echo "<textarea rows='5' cols'30' name='_perpoststyles' id='perpoststyles' style='width:100%;height:200px;'>";
			echo $perpoststyles;
		echo "</textarea></div></td></tr>";

		// --- quicksave CSS button ---
		echo "<tr><td align='center'><div id='quicksavedcss'>".esc_attr(__('CSS Saved!','bioship'))."</div></td>";
		echo "<td align='right'><input type='button' onclick='quicksavecss();' value='".esc_attr(__('QuickSave CSS','bioship'))."' class='button-secondary'></td></tr>";

	// --- close style override tab ---
	echo "</table></div>";

	// --- end tabs output ---
	echo "</center>";

	// --- theme options current tab saver ---
	echo "<input type='hidden' id='themeoptionstab' name='_themeoptionstab' value='".esc_attr($settingstab)."'>";
	echo "<input type='hidden' id='themetabclicked' name='_themetabclicked' value=''>";

	// --- enqueue quicksave forms ---
	// 1.9.5: added quicksave perpost CSS form to footer
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_css_form');
	// 2.0.0: added quicksave perpost settings form to footer (prototype)
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_settings_form');
	// 2.1.1: added quicksave cyclic nonce refresher
	add_action('admin_footer', 'bioship_admin_quicksave_nonce_refresher');

 }
}

// ---------------------
// Update Metabox Values
// ---------------------
add_action('publish_post', 'bioship_admin_update_metabox_options');
add_action('save_post', 'bioship_admin_update_metabox_options');

// 1.8.0: renamed from muscle_update_metabox_options
if (!function_exists('bioship_admin_update_metabox_options')) {
 function bioship_admin_update_metabox_options() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check post values ---
	// 1.9.8: return if post is empty
	global $post; if (!is_object($post)) {return;}
	$postid = $post->ID;

	// --- check for autosave ---
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return;}

	// --- check user capabilities ---
	// 1.8.0: cleaner save logic here
	if (!current_user_can('edit_posts') || !current_user_can('edit_post', $postid)) {return $postid;}

	// --- save display overrides --
	// 1.8.0: grouped display overrides to array
	// 1.8.5: added headernav, footernav, breadcrumbs, pagenavi
	$display = array(); $postdata = false;
	$displaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);
	// 1.9.5: changed _hide prefix to _display_
	foreach ($displaykeys as $key) {
		if (!isset($_POST['_display_'.$key])) {$display[$key] = '';}
		else {
			if ($_POST['_display_'.$key] == '1') {$display[$key] = '1'; $postdata = true;}
			else {$display[$key] = '';}
		}
	}
	// 1.9.9: check and save only if new post data
	// 2.1.1: use prefixed metakey for saving
	// 2.1.1: set unique argument to true here
	delete_post_meta($postid, '_'.THEMEPREFIX.'_display_overrides');
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_display_overrides', $display, true);}

	// --- save layout overrides ---
	// 1.9.5: added override keys
	$override = array(); $postdata = false;
	$overridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);
	foreach ($overridekeys as $key) {
		if (!isset($_POST['_'.$key])) {$override[$key] = '';}
		else {$override[$key] = $_POST['_'.$key]; $postdata = true;}
	}
	delete_post_meta($postid, '_'.THEMEPREFIX.'_templating_overrides');
	// 1.9.9: check and save if new post data
	// 2.1.1: use prefixed metakey for saving
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_templating_overrides', $override);}

	// --- save filter overrides ---
	// 1.8.0: grouped filters to array
	// 2.0.0: better checkbox save logic
	$removefilters = array(); $postdata = false;
	$filters = array('wpautop', 'wptexturize', 'convertsmilies', 'convertchars');
	foreach ($filters as $filter) {
		if (!isset($_POST['_'.$filter])) {$removefilters[$filter] = '';}
		else {
			if ($_POST['_'.$filter] == '1') {$removefilters[$filter] = '1'; $postdata = true;}
			else {$removefilters[$filter] = '';}
		}
	}
	delete_post_meta($postid, '_'.THEMEPREFIX.'_removefilters');
	// 1.9.9: check and save if new filters
	// 2.0.0: save if post data found
	// 2.1.1: use prefixed metakey for saving
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_removefilters', $removefilters, true);}

	// --- save individual options ---
	// 1.8.0: save individual key values
	$optionkeys = array('_perpoststyles', '_thumbnailsize', '_themeoptionstab');
	foreach ($optionkeys as $option) {
		// 1.9.9: make sure option value is actually set (as metabox may be removed)
		if (isset($_POST[$option])) {
			$optionvalue = $_POST[$option];
			if ($option == '_perpoststyles') {$optionvalue = stripslashes($optionvalue);}
			// 2.1.1: use prefixed metakey for saving
			$option = str_replace('_', '_'.THEMEPREFIX.'_', $option);
			delete_post_meta($postid, $option);
			// 1.9.5: to make cleaner, do not save empty values
			if (trim($optionvalue) != '') {add_post_meta($postid, $option, $optionvalue, true);}
			$options[$option] = $optionvalue;
		}
	}

	// --- manual debug of per post options ---
	// $metasavedebug = false;
	$metasavedebug = true;
	if ($metasavedebug) {
		$debuginfo = PHP_EOL." Saved Post ".$postid." at ".date('j/m/d H:i:s', time()).PHP_EOL;
		$debuginfo .= "--- Override ---".PHP_EOL; foreach ($override as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		$debuginfo .= "--- Display ---".PHP_EOL; foreach ($display as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		$debuginfo .= "--- Filters ---".PHP_EOL; foreach ($removefilters as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		// 2.1.2: fix for possible undefined variable warning
		if (isset($options)) {$debuginfo .= "--- Options ---".PHP_EOL; foreach ($options as $key => $value) {$debuginfo .= $key.': '.print_r($value,true).PHP_EOL;} }
		// $debuginfo .= "--- Posted ---".PHP_EOL; foreach ($posted as $key => $value) {$debuginfo .= $key.': '.print_r($value,true).PHP_EOL;}
		bioship_write_debug_file('perpost-debug-'.$postid.'.txt', $debuginfo);
	}
 }
}

// --------------------------
// QuickSave PerPost CSS Form
// --------------------------
// 1.9.5: added this CSS quicksave form
if (!function_exists('bioship_admin_quicksave_perpost_css_form')) {
 function bioship_admin_quicksave_perpost_css_form() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- quicksave CSS scripts ---
	// 2.1.2: moved quicksave show fadeout to AJAX action
	echo "<script>function quicksavecss() {
		oldcss = document.getElementById('pageloadperpoststyles').value;
		newcss = document.getElementById('perpoststyles').value;
		if (oldcss == newcss) {return false;}
		document.getElementById('newperpoststyles').value = newcss;
		document.getElementById('quicksave-css-form').submit();
	}</script>";

	// --- get perpost styles ---
	global $post; $postid = $post->ID;
	// 2.0.8: use prefixed post meta key
	// 2.1.1: do not convert old values here
	$perpoststyles = get_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', true);

	// --- perpost styles form ---
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	$adminajax = admin_url('admin-ajax.php');
	echo "<form action='".esc_url($adminajax)."' method='post' id='quicksave-css-form' target='quicksave-css-frame'>";
	$nonce = wp_create_nonce('quicksave-perpost-css-'.$postid);
	echo "<input type='hidden' name='_wpnonce' id='quicksave-css-nonce' value='".$nonce."'>";
	echo "<input type='hidden' name='action' value='quicksave_perpost_css'>";
	echo "<input type='hidden' name='postid' value='".$postid."'>";
	echo "<input type='hidden' name='pageloadperpoststyles' id='pageloadperpoststyles' value='".$perpoststyles."'>";
	echo "<input type='hidden' name='newperpoststyles' id='newperpoststyles' value=''></form>";

	// --- perpost styles saving iframe ---
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-css-frame' id='quicksave-css-frame'></iframe>";
 }
}

// ---------------------
// QuickSave PerPost CSS
// ---------------------
// 1.9.5: added this CSS quicksave
if (!function_exists('bioship_admin_quicksave_perpost_css')) {

 add_action('wp_ajax_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css');

 function bioship_admin_quicksave_perpost_css() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get edit post ID ---
	if (!isset($_POST['postid']) || !isset($_POST['newperpoststyles'])) {exit;}
	$postid = $_POST['postid'];
	if (!is_numeric($postid)) {exit;}

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if (is_user_logged_in()) {

		// --- check edit permissions ---
		if (current_user_can('edit_posts') && current_user_can('edit_post', $postid)) {

			// --- check nonce ---
			// 2.0.0: use wp_verify_nonce instead of check_admin_referer for error message output
			$checknonce = false;
			if (isset($_POST['_wpnonce'])) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce($nonce, 'quicksave-perpost-css-'.$postid);
			}

			// --- update perpost styles ---
			if ($checknonce) {
				$newstyles = stripslashes($_POST['newperpoststyles']);
				// 2.1.1: use prefixed perpost styles metakey
				update_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', $newstyles);
			} else {$error = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}

			// --- update current tab to styles ---
			update_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', 'styles');

		} else {$error = __('Failed! You do not have permission to edit this post.','bioship');}
	} else {$error = __('Failed. Looks like you may need to login again!','bioship');}

	// --- script output and exit ---
	if (isset($error)) {echo "<script>alert('".esc_js($error)."');</script>";}
	else {echo "<script>parent.quicksavedshow();</script>";}
	exit;
 }
}

// -------------------------------
// QuickSave PerPost Settings Form
// -------------------------------
// 2.0.0: dummy form copy to save all metabox theme option overrides (prototype)
if (!function_exists('bioship_admin_quicksave_perpost_settings_form')) {
 function bioship_admin_quicksave_perpost_settings_form() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- set perpost metabox settings keys ---
	$checkboxkeys = array(
		'display_wrapper', 'display_header', 'display_footer', 'display_navigation', 'display_secondarynav', 'display_headernav', 'display_footernav',
		'display_sidebar', 'display_subsidebar', 'display_headerwidgets', 'display_footerwidgets', 'display_footer1', 'display_footer2', 'display_footer3', 'display_footer4',
		'display_image', 'display_breadcrumb', 'display_title', 'display_subtitle', 'display_metatop', 'display_metabottom', 'display_authorbio', 'display_pagenavi',
		'wpautop', 'wptexturize', 'convertsmilies', 'convertchars' // filter keys
	);
	$selectkeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'thumbnailsize' // *
	);
	$textkeys = array('sidebarcustom', 'subsidebarcustom');

	// --- convert settings inputs to javascript arrays ---
	$settingskeys = '';
	foreach ($checkboxkeys as $i => $key) {$settingskeys .= "checkboxkeys[".$i."] = '".$key."'; "; $i++;}
	$settingskeys .= PHP_EOL;
	foreach ($selectkeys as $j => $key) {$settingskeys .= "selectkeys[".$j."] = '".$key."'; "; $j++;}
	$settingskeys .= PHP_EOL;
	foreach ($textkeys as $k => $key) {$settingskeys .= "textkeys[".$k."] = '".$key."'; "; $k++;}
	$settingskeys .= PHP_EOL;

	// --- output settings save script ---
	// 2.1.1: added tab for displaying message and saving current tab
	echo "<script>function quicksavesettings(tab) {
		checkboxkeys = new Array(); selectkeys = new Array(); textkeys = new Array(); ";

		// --- output settings keys ---
		echo PHP_EOL.$settingskeys.PHP_EOL;

		// --- copy settings to quicksave form ---
		echo "
		for (i in checkboxkeys) {
			if (document.getElementById('_'+checkboxkeys[i])) {
				if (document.getElementById('_'+checkboxkeys[i]).checked) {
					document.getElementById('__'+checkboxkeys[i]).value = '1';
				} else {document.getElementById('__'+checkboxkeys[i]).value = '';}
			} else {console.log('Warning! Missing Checkbox Setting Key: _'+checkboxkeys[i]);}
		}
		for (i in selectkeys) {
			if (document.getElementById('_'+selectkeys[i])) {
				selectelement = document.getElementById('_'+selectkeys[i]);
				selectedvalue = selectelement.options[selectelement.selectedIndex].value;
				document.getElementById('__'+selectkeys[i]).value = selectedvalue;
			} else {console.log('Warning! Missing Select Setting Key: _'+selectkeys[i]);}
		}
		for (i in textkeys) {
			if (document.getElementById('_'+textkeys[i])) {
				document.getElementById('__'+textkeys[i]).value = document.getElementById('_'+textkeys[i]).value;
			} else {console.log('Warning! Missing Text Setting Key: _'+textkeys[i]);}
		}";

		// --- submit quicksave form ---
		echo "
		document.getElementById('quicksave-options-tab').value = tab;
		document.getElementById('quicksave-settings-form').submit();
	}
	function quicksavedsettings(tab) {
		quicksaved = document.getElementById('quicksavesettings-'+tab); quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
	}</script>";

	// --- quicksave settings form ---
	// 2.1.1: added buttonid and tab fields
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	global $post; $postid = $post->ID;
	$adminajax = admin_url('admin-ajax.php');
	echo "<form action='".esc_url($adminajax)."' method='post' id='quicksave-settings-form' target='quicksave-settings-frame'>";
	$nonce = wp_create_nonce('quicksave-perpost-settings-'.$postid);
	echo "<input type='hidden' name='_wpnonce' id='quicksave-settings-nonce' value='".$nonce."'>";
	echo "<input type='hidden' name='action' value='quicksave_perpost_settings'>";
	echo "<input type='hidden' name='tab' id='quicksave-options-tab' value=''>";
	echo "<input type='hidden' name='postid' value='".$postid."'>";
	foreach ($checkboxkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	foreach ($selectkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	foreach ($textkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	echo "</form>";

	// --- quicksave settings iframe ---
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-settings-frame' id='quicksave-settings-frame'></iframe>";
 }
}

// --------------------------
// QuickSave PerPost Settings
// --------------------------
// 2.0.0: save theme overrides via AJAX trigger (prototype)
if (!function_exists('bioship_admin_update_metabox_settings')) {

 add_action('wp_ajax_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings');

 function bioship_admin_update_metabox_settings() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check trigger conditions
 	if (!isset($_REQUEST['postid'])) {exit;}
 	$postid = $_REQUEST['postid'];
 	if (!is_numeric($postid)) {exit;}
 	$error = false;

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if (is_user_logged_in()) {

		// --- check permissions ---
		if (current_user_can('edit_posts') && current_user_can('edit_post', $postid)) {

			// --- check nonce ---
			$checknonce = false;
			if (isset($_POST['_wpnonce'])) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce($nonce, 'quicksave-perpost-settings-'.$postid);
			}
			if ($checknonce) {
				global $post; $post = get_post($postid);
				bioship_admin_update_metabox_options();
			} else {$error = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}

			// --- update current options tab
			// 2.1.1: added saving of current tab
			$tab = $_POST['tab'];
			update_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', $tab);

		} else {$error = __('Failed! You do not have permission to edit this post.','bioship');}
	} else {$error = __('Failed! Looks like you may need to login again!','bioship');}

	// --- output script and exit ---
	// 2.1.1: added tab argument for saved message display
	if ($error) {echo "<script>alert('".esc_js($error)."');</script>";}
	else {echo "<script>parent.quicksavedsettings('".esc_attr($tab)."');</script>";}
	exit;
 }
}

// --------------------------------
// QuickSave Cyclic Nonce Refresher
// --------------------------------
if (!function_exists('bioship_admin_quicksave_nonce_refresher')) {
 function bioship_admin_quicksave_nonce_refresher() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// --- output cyclic nonce refresh script ---
 	global $post; $postid = $post->ID;
	$adminajax = admin_url('admin-ajax.php');
	echo "<script>jQuery(document).ready(function() {
		setTimeout(function() {
			document.getElementById('quicksave-doing-refresh').value = 'yes';
			document.getElementById('quicksave-refresh-iframe').src = '".esc_url($adminajax)."?action=quicksave_update_nonces&postid=".esc_js($postid)."';
		}, 300000);
	});</script>";

	// --- hidden doing refresh input ---
	// 2.1.1: added input to prevent possible multiple alerts
	echo "<input type='hidden' id='quicksave-doing-refresh' value=''>";

	// --- quicksave nonce refresh iframe ---
	echo "<iframe src='javascript:void(0);' id='quicksave-refresh-iframe' style='display:none;'></iframe>";
 }
}

// --------------------------
// Update MetaBox Nonces AJAX
// --------------------------
if (!function_exists('bioship_admin_update_quicksave_nonces')) {

 add_action('wp_ajax_quicksave_update_nonces', 'bioship_admin_update_quicksave_nonces');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_update_nonces', 'bioship_admin_update_quicksave_nonces');

 function bioship_admin_update_quicksave_nonces() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get post ID ---
	$postid = $_REQUEST['postid'];
 	if (!isset($_REQUEST['postid'])) {exit;}
 	$postid = $_REQUEST['postid'];
 	if (!is_numeric($postid)) {exit;}

	// --- session timeout message ---
	// 2.1.1: added alert message to inform of session timeout
	// TODO: trigger showing of popup interim login thickbox ?
	if (!is_user_logged_in()) {
		$message = __('Your session has timed out. Please Login again to continue editing.','bioship');
		echo "<script>alert('".esc_js($message)."');</script>";
		exit;
	}

	// --- check edit permissions ---
	if (!current_user_can('edit_posts') || !current_user_can('edit_post', $postid)) {exit;}

	// --- create new nonces ---
	$settingsnonce = wp_create_nonce('quicksave-perpost-settings-'.$postid);
	$cssnonce = wp_create_nonce('quicksave-perpost-css-'.$postid);

	// --- send new nonces back to parent window ---
	// 2.1.1: reset doing nonce refresh flag on refresh
	echo "<script>parent.document.getElementById('quicksave-doing-refresh').value = '';
	parent.document.getElementById('quicksave-settings-nonce').value = '".esc_js($settingsnonce)."';
	parent.document.getElementById('quicksave-css-nonce').value = '".esc_js($cssnonce)."';</script>";
	exit;
 }
}


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


// ---------------------------
// === Recommended Plugins ===
// ---------------------------

// 'Required' Plugins
// ------------------
// - Titan Framework (better admin theme options interface)
// -- for WordPress.Org installs (not explicitly 'required')

// Recommended Plugins (Theme Supported)
// -------------------------------------
// TODO: retest need for Widget Saver ?
// TODO: force use of specific TML version ?
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
// - RefleXedit				(testing)
// - PDF Replicator			(testing)
// - PDF Shuttle			(testing)
// - WP Infinity Responder	(updating)
// - Visitor Vortex			(updating)
// - WarpPress Builder		(development)
// - FreeStyler				(development)

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
			$message = '<h3>'.__('BioShip Theme Framework Recommended Plugins','bioship').'</h3><br>';
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
			$message = '<h3>'.__('BioShip Theme Framework','bioship');
			$message .= ' - '.__('Recommended Plugins','bioship').'</h3><br>';
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

