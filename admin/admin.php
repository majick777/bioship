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

if (!function_exists('add_action')) {exit;}

// ==========================
// === admin.php Sections ===
// ==========================
// - WShadow Theme Update Checker
// - Load Freemius SDK
// - Theme Settings Transfers
// - Modify Theme Admin Menus/Submenus
// - Theme Admin Page Menu Header
// - Theme Info Page Section
// - Check for Theme Updates Output
// - One-Click Child Theme Install
// - Build Selective Resources
// - Theme Tools (Backup/Restore/Export/Import)
// - Activation / Deactivation Actions
// - Editor Screen Theme Options MetaBox
// - Required/Recommend Plugins (via TGMPA)
// ==========================

// Note: this file is included via setup near top of functions.php
// 1.5.0: moved admin specific functions here from main functions.php
// 1.8.5: moved admin.php from theme root to /admin/ subdirectory
// 2.0.5: moved update checker here and added Freemius loading

// Script Debugging
// ----------------
// 2.0.5: use development scripts/styles if theme debugging in admin
if (THEMEDEBUG && !defined('SCRIPT_DEBUG')) {define('SCRIPT_DEBUG', true);}

// ----------------------------
// WShadow Theme Update Checker
// ----------------------------
// 2.0.5: moved from functions.php, hook to admin_init, remove is_admin check
if (!function_exists('bioship_theme_update_checker')) {

 // 2.0.7: moved add action internally for consistency
 add_action('admin_init', 'bioship_theme_update_checker');

 function bioship_theme_update_checker() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 1.8.0: use file hierarchy for theme update checker
	// 2.0.8: use constant to check for WordPress.org version
	if (THEMEWPORG) {
		// 1.8.0: default sidebar ads to off for WordPress.Org guideline compliance
		// 1.8.5: switched this to single option array value
		if (!is_array(get_option('bioship_sidebar_options'))) {
			// 2.0.2: added first installed version record
			$vsidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => 'checked',
				'installdate' => date('Y-m-d'), 'installversion' => THEMEVERSION);
			add_option('bioship_sidebar_options', $vsidebaroptions);
		}

		// 1.8.5: maybe disable directory clearing to keep installed theme bundles?
		// ref: http://wordpress.stackexchange.com/a/228798/76440
		// TODO: test this out for desired results
		// add_filter('bioship_package_options', 'bioship_avoid_deletion', 999);
		if (!function_exists('bioship_avoid_deletion')) {
		 function bioship_avoid_deletion($voptions) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			$vhookextra = $voptions['hook_extra'];
			if ( ($vhookextra['type'] == 'theme') && ($vhookextra['action'] == 'update') ) {
				if ($vhookextra['theme'] == 'bioship') {
					$voptions['clear_destination'] = false;
					$voptions['abort_if_destination_exists'] = false;
				}
			}
			return $voptions;
		 }
		}
	} else {
		$vthemeupdater = bioship_file_hierarchy('file', 'theme-update-checker.php', array('includes'));
		require_once($vthemeupdater);
		// 1.5.0: use custom theme update server location
		$vjsoninfourl = THEMEHOMEURL.'/download/?action=get_metadata&slug=bioship';
		// $vjsoninfourl = THEMEHOMEURL.'/download/version.json'; // for 1.4.5 to 1.5.0 only
		$vupdatechecker = new ThemeUpdateChecker('bioship', $vjsoninfourl);
		// 1.8.5: add default sidebar option values
		// 2.0.5: added first installed version record
		if (!is_array(get_option('bioship_sidebar_options'))) {
			$vsidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => '',
				'installdate' => date('Y-m-d'), 'installversion' => THEMEVERSION);
			add_option('bioship_sidebar_options', $vsidebaroptions);
		}
	}
 }
}

// ---------------------------
// Theme Settings for Freemius
// ---------------------------
// 2.0.5: added Freemius to theme
if (!function_exists('bioship_admin_freemius')) {
 function bioship_admin_freemius() {
    global $wordquestplugins, $bioship_freemius; $vpremium = false;
    if ($wordquestplugins[THEMEPREFIX]['plan'] == 'premium') {$vpremium = true;}
    $vwporg = $wordquestplugins[THEMEPREFIX]['wporg'];
    $vhasplans = $wordquestplugins[THEMEPREFIX]['hasplans'];

	if (isset($_REQUEST['page'])) {
		// external redirect for Support Forum
		if ($_REQUEST['page'] == THEMEPREFIX.'-wp-support-forum') {
			if (!function_exists('wp_redirect')) {include(ABSPATH.WPINC.'/pluggable.php');}
			wp_redirect(THEMESUPPORT.'/quest/quest-category/theme-support/'); exit;
		}

		// 2.0.7: external redirect for Documentation
		if ($_REQUEST['page'] == THEMEPREFIX.'-documentation') {
			if (!function_exists('wp_redirect')) {include(ABSPATH.WPINC.'/pluggable.php');}
			wp_redirect(THEMEHOMEURL.'/documentation/'); exit;
		}
	}


    if (!isset($bioship_freemius)) {

		require_once(dirname(dirname(__FILE__)).'/freemius/start.php');

		// set different menu depending on framework
		$vmenu = array('contact' =>	$vpremium);

		if (THEMETITAN) {
			$vmenu['slug'] = 'bioship-options';
			$vmenu['first-path'] = 'admin.php?page=bioship-options&welcome=true';
		} elseif (THEMEOPT) {
			$vmenu['slug'] = 'theme-options';
			$vmenu['first-path'] = 'admin.php?page=options-framework&welcome=true';
		} else {
			$vmenu['slug'] = 'theme-tools';
			$vmenu['parent'] = array('slug' => 'themes.php');
			$vmenu['first-path'] = 'themes.php?page=theme-info&welcome=true';
		}

		$bioship_settings = array(
            'id'                	=> '816',
            'slug'              	=> 'bioship',
            'type'					=> 'theme',
            'public_key'        	=> 'pk_15fe43ecd7c5580cbcbb3484cfa9f',
            'is_premium'        	=> $vpremium,
            'has_premium_version' 	=> false,
            'has_addons'        	=> false,
            'has_paid_plans'    	=> $vhasplans,
            'is_org_compliant'  	=> $vwporg,
            'menu' 					=> $vmenu
        );
    	$bioship_freemius = fs_dynamic_init($bioship_settings);
    }
    return $bioship_freemius;
 }
}

// load Freemius SDK for Theme
// ---------------------------
// 2.0.5: added Freemius loading - not working yet :-/
if (file_exists(dirname(dirname(__FILE__)).'/freemius/start.php')) {

 	// initialize Freemius object
 	// --------------------------
	$bioship_freemius = bioship_admin_freemius();
	// signal that SDK was initiated
	bioship_do_action('bioship_loaded');

	// customize Freemius Connect Message
	// ----------------------------------
	if (!function_exists('bioship_admin_freemius_connect')) {
	 // 'Never miss an important update - opt-in to our security and feature updates notifications, and non-sensitive diagnostic tracking with %4$s.'
	 $bioship_freemius->add_filter('connect_message', 'bioship_admin_freemius_connect', WP_FS__DEFAULT_PRIORITY, 6);
	 function bioship_admin_freemius_connect($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link) {
		return sprintf(
			__fs('hey-x').'<br>'.
	 		__('Show your appreciation for %1$s by helping us improve it! Opt in to diagnostic tracking and receive security and feature notifications.', 'bioship'),
			$user_first_name, '<b>'.$plugin_title.'</b>', '<b>'.$user_login.'</b>', $site_link, $freemius_link
		);
	 }
	}

	// customize Connect on Update Message
	// -----------------------------------
	if (!function_exists('bioship_admin_freemius_connect_update')) {
	 //	'Please help us improve %1$s! If you opt-in, some data about your usage of %1$s will be sent to %4$s. If you skip this, that\'s okay! %1$s will still work just fine.'
	 $bioship_freemius->add_filter('connect_message_on_update', 'bioship_admin_freemius_connect_update', WP_FS__DEFAULT_PRIORITY, 6);
	 function bioship_admin_freemius_connect_update($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link) {
	 	// 2.0.5: keep update message the same for now
		return bioship_admin_freemius_connect($message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link);
	 }
	}

	// license Change Updater
	// ----------------------
	add_action('fs_after_license_change_bioship', 'bioship_admin_license_change', 10, 2);
	if (!function_exists('bioship_admin_license_change')) {
	 function bioship_admin_license_change($vchange, $vplan) {
	 	global $bioship_freemius;
	 	// none, upgraded, downgraded, changed, cancelled, expired, trial_started, trial_expired
	 	if ($vchange != 'none') {
	 		// 2.0.7: use new prefixed current user function
			$current_user =  bioship_get_current_user();
		 	$vupdateurl = add_query_arg('email', $current_user->user_email, THEMESUPPORT);
		 	$vupdateurl = add_query_arg('planchange', $vchange, $vupdateurl);
		 	$vupdateurl = add_query_arg('plan', $vplan, $vupdateurl);
		 	echo '<iframe src="'.$vupdateurl.'" style="display:none;"></iframe>';
		}
	 }
	}

}


// --------------------
// Admin Notice Helpers
// --------------------

// Admin Notices Output
// --------------------
// 2.0.2: added admin notice output helper
$vadminmessages = array();
if (!function_exists('bioship_admin_notices')) {
 function bioship_admin_notices() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	echo "<!-- Theme Admin Notices -->";
	global $vadminmessages;
	if (count($vadminmessages) > 0) {
		foreach ($vadminmessages as $vmessage) {
			echo "<div class='update message' style='padding:3px 10px;margin:0 0 10px 0;'>".$vmessage."</div>";
		}
	}
 }
}

// Admin Notices Enqueue
// ---------------------
// 2.0.2: added admin notice enqueue helper
if (!function_exists('bioship_admin_notices_enqueue')) {
 function bioship_admin_notices_enqueue() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (!has_action('admin_notice', 'bioship_admin_notices')) {
		add_action('admin_notice', 'bioship_admin_notices');
	}
	if (!has_action('theme_admin_notice', 'bioship_admin_notices')) {
		add_action('theme_admin_notice', 'bioship_admin_notices');
	}
 }
}

// Theme Debug: Echo All Theme Option Values
// -----------------------------------------
if (isset($_REQUEST['themedump'])) {
 if ( ($_REQUEST['themedump'] == 'themeoptions') || ($_REQUEST['themedump'] == 'options')
   || ($_REQUEST['themedump'] == 'backupoptions') || ($_REQUEST['themedump'] == 'backup') ) {
	add_action('init', 'bioship_admin_echo_setting_values');
	if (!function_exists('bioship_admin_echo_setting_values')) {
	 function bioship_admin_echo_setting_values() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (current_user_can('edit_theme_options')) {
			global $vtheme, $vthemename, $vthemesettings;
			$vtitankey = preg_replace('/\W/','-',strtolower($vthemename)).'_options';
			$vofkey = preg_replace('/\W/','_',strtolower($vthemename));
			if ( ($_REQUEST['themedump'] == 'backupoptions') || ($_REQUEST['themedump'] == 'backup') ) {
				echo "<!-- Auto Backup: (".THEMEKEY."_backup)"; print_r(bioship_get_option(THEMEKEY.'_backup')); echo PHP_EOL.' -->'.PHP_EOL;
				echo "<!-- User Backup: (".THEMEKEY."_user_backup)"; print_r(bioship_get_option(THEMEKEY.'_user_backup')); echo PHP_EOL.' -->'.PHP_EOL;
				// return;
			}

			echo "<!-- Theme Object: "; print_r($vtheme); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Titan Framework Settings (".$vtitankey."): "; print_r(maybe_unserialize(bioship_get_option($vtitankey))); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Options Framework Settings (".$vofkey."): "; print_r(bioship_get_option($vofkey)); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Theme Settings (".THEMEKEY."): "; print_r($vthemesettings); echo PHP_EOL.' -->'.PHP_EOL;
			exit;
		}
	 }
	}
 }
}

// Force Update Theme Settings (Save)
// ---------------------------------
add_action('update_option', 'bioship_admin_theme_settings_save', 11, 3);
if (!function_exists('bioship_admin_theme_settings_save')) {
 function bioship_admin_theme_settings_save($voption, $voldsettings, $vnewsettings) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	if ($voption != THEMEKEY) {return;}
 	if ( (defined('THEMEUPDATED')) && (THEMEUPDATED) ) {return;}
 	define('THEMEUPDATED',true); // to do this once only for actual updates

	if ( ($vnewsettings) && (!empty($vnewsettings)) && ($vnewsettings != '') ) {
		// write a manual settings file of the serialized data
		// ob_start(); print_r($vnewsettings); $vsaveddata = ob_get_contents(); ob_end_clean();
		bioship_write_debug_file($voption.'.txt', $vnewsettings);
		set_transient('force_update_'.THEMEKEY, $vnewsettings, 120);
	}
 }
}

// ------------------------
// Theme Settings Transfers
// ------------------------

// Transfer Framework Settings
// ---------------------------
// 1.9.5: rewritten and expanded transfer function
if (isset($_REQUEST['transfersettings'])) {add_action('init', 'bioship_admin_framework_settings_transfer');}
if (!function_exists('bioship_admin_framework_settings_transfer')) {
 function bioship_admin_framework_settings_transfer() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (!current_user_can('edit_theme_options')) {return;}

	global $vthemestyledir, $vthemetemplatedir;

	// check transfer existing options triggers
	if ($_REQUEST['transfersettings'] == 'totitan') {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vtransferfrom = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferfrom = str_replace('-', '_', $vtransferfrom);
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vtransferto = preg_replace("/\W/", "-", strtolower(trim($_REQUEST['totheme'])));
			$vtransferto = str_replace('_', '-', $vtransferto); $vtransferto .= '_options';
		} elseif (THEMETITAN) {$vtransferto = THEMEKEY;}

		if ( ($vtransferfrom) && ($vtransferto) ) {
			global $vthemeoptions;

			$voptionvalues = get_option($vtransferfrom);
			if (!$voptionvalues) {
				// try to fallback to retrieving serialized settings from a file
				// if the database is acting up (this code from when it has happened)
				// used by copying the value from database to text in the debug directory
				$vstylefile = $vthemestyledir.'debug'.DIRSEP.$vtransferfrom.'.txt';
				$vtemplatefile = $vthemestyledir.'debug'.DIRSEP.$vtransferfrom.'.txt';
				if (file_exists($vstylefile)) {$vsettingsfile = $vstylefile;}
				elseif (file_exists($vtemplatefile)) {$vsettingsfile = $vtemplatefile;}
				else {
					$vmessage = __('Transfer Failed! Could not retrieve existing settings.','bioship');
					global $vadminmessages; $vadminmessages[] = $vmessage;
					bioship_admin_notices_enqueue(); return;
				}

				$vfilecontents = trim(bioship_file_get_contents($vsettingsfile));
				// echo $vfilecontents.PHP_EOL; // debug point
				$voptionvalues = unserialize($vfilecontents);
				if (!$voptionvalues) {
				    $vrepaired = bioship_fix_serialized($vfilecontents);
    				$voptionvalues = unserialize($vrepaired);
    				if (!$voptionvalues) {
    					echo __('Error! Could not unserialize settings from file!','bioship'); exit;
    				}
				}
			} else {
				if (is_string($voptionvalues)) {$voptionvalues = trim($voptionvalues);}
				if (is_serialized($voptionvalues)) {$voptionvalues = unserialize($voptionvalues);}
			}

			if ( ($voptionvalues != '') && (is_array($voptionvalues)) ) {
				foreach ($vthemeoptions as $voption => $voptionvalue) {
					$voptionkey = $voptionvalue['id'];
					// map missing defaults
					if (!isset($voptionvalues[$voptionkey])) {
						if (isset($voptionvalues['std'])) {$voptionvalues[$voptionkey] = $voptionvalue['std'];}
					}
					// fix to multicheck arrays
					if ( ($voptionvalue['type'] == 'multicheck') && (is_array($voptionvalues[$voptionkey])) ) {
						$voptionarray = array(); $vi = 0;
						foreach ($voptionvalues[$voptionkey] as $vkey => $vvalue) {$voptionarray[$vi] = $vkey;}
						$voptionvalues[$voptionkey] = $voptionarray;
					}
					// fix to serialize all subarray values
					if (is_array($voptionvalues[$voptionkey])) {
						$voptionvalues[$voptionkey] = serialize($voptionvalues[$voptionkey]);
					}

					// TODO: fix for font values transfers
					// TODO: fix multi checkbox value transfers
					// TODO: ? fix for changing image URLs to attachment IDs ?
					// ...would need to be manually inserted as new attachments?

				}
				delete_option($vtransferto); add_option($vtransferto, serialize($voptionvalues));
				// write settings to file just in case
				bioship_write_debug_file($vtransferto.'.txt', serialize($voptionvalues));
				// echo serialize($voptionvalues); exit; // for manual output

				$vmessage = __('Transferred Existing Theme Settings to Titan Framework.','bioship');
				global $vadminmessages; $vadminmessages[] = $vmessage;
				bioship_admin_notices_enqueue();
			}
		}
	}

	if ($_REQUEST['transfersettings'] == 'tooptions') {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vtransferfrom = preg_replace("/\W/", "-", strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferfrom = str_replace('_', '-', $vtransferfrom); $vtransferfrom .= '_options';
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vtransferto = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferto = str_replace('-', '_', $vtransferto);
		} elseif (THEMEOPT) {$vtransferto = THEMEKEY;}

		// TODO: Titan Framework settings to Options Framework settings Transfer here?
	}

 }
}

// Manually Copy Theme Settings
// ----------------------------
// 1.9.5: made this to copy to/from any theme settings
// WARNING: will overwrite the existing Theme Settings for a theme
// usage: ?copysettings=yes&copyfrom=source-theme-slug&copyto=destination-theme-slug

if ( (isset($_REQUEST['copysettings'])) && ($_REQUEST['copysettings'] == 'yes') ) {
	add_action('init', 'bioship_admin_copy_theme_settings');
}

if (!function_exists('bioship_admin_copy_theme_settings')) {
 function bioship_admin_copy_theme_settings() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	if (!current_user_can('edit_theme_options')) {return;}

 	$vcopyto = false; $vcopyfrom = false;
 	if (THEMEOPT) {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vcopyfrom = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['fromtheme'])));
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vcopyto = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['fromtheme'])));
		}
 	} else {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vcopyfrom = preg_replace("/\W/", "-", strtolower(trim($_REQUEST['fromtheme'])));
			$vcopyfrom = str_replace('_', '-', $vcopyfrom); $vcopyfrom .= '_options';
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vcopyto = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['totheme'])));
			$vcopyto = str_replace('_', '-', $vtransferto); $vcopyto .= '_options';
		}
 	}

 	if ( ($vcopyto) && ($vcopyfrom) ) {
 		$vfromsettings = get_option($vcopyfrom);
 		$vtosettings = get_option($vcopyto);

 		// TODO: backup existing settings?
 		// TODO: also copy over parent widgets/menus ?
 		// 2.0.5: removed numerous separate message functions

 		if ($vfromsettings) {
 			$vcopysettings = update_option($vcopyto,$vfromsettings);
			if ($vcopysettings) {
				$vcopyfrom = trim(strtolower($_REQUEST['fromtheme'])); $vcopyto = trim(strtolower($_REQUEST['totheme']));
	 		 	$vmessage = __('Theme Settings have been copied from ','bioship').$vcopyfrom.' '.__('to','bioship').' '.$vcopyto;
			} else {
				$vcopyto = trim(strtolower($_REQUEST['totheme']));
	 		 	$vmessage = __('Theme Settings failed to copy to ','bioship').$vcopyto;
			}
 		} else {
			$vcopyfrom = trim(strtolower($_REQUEST['fromtheme']));
 		 	$vmessage = __('Copy Theme Settings failed! Could not retrieve settings for ','bioship').$vcopyfrom;
 		}
		global $vadminmessages; $vadminmessages[] = $vmessage;
		bioship_admin_notices_enqueue();
 	}

 }
}


// ==========================
// === Modify Admin Menus ===
// ==========================
// lots of nice hacky fixes here...

// Theme Options Page Redirection
// ------------------------------
// 1.9.5: moved here from Titan-specific function
if (!function_exists('bioship_admin_theme_options_page_redirect')) {
 function bioship_admin_theme_options_page_redirect($vupdated='') {
	// 1.8.5: use add_query_arg
	$voptionsurl = admin_url('admin.php');
	// 1.9.5: handle Titan or Options Framework or no framework
	if (THEMETITAN) {$voptionsurl = add_query_arg('page', 'bioship-options', $voptionsurl);} // $vthemename.'-options'
	elseif (THEMEOPT) {$voptionsurl = add_query_arg('page', 'options-framework', $voptionsurl);}
	else {$voptionsurl = add_query_arg('page', 'theme-tools', $voptionsurl);}

	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
		$voptionsurl = add_query_arg('theme', $_REQUEST['theme'], $voptionsurl);
	}
	if ($vupdated != '') {$voptionsurl = add_query_arg('updated', $vupdated, $voptionsurl);}
	wp_redirect($voptionsurl); exit;
 }
}

// Change the 'Theme Options' Framework Admin Menu
// -----------------------------------------------
// (for Options Framework only)
if (!function_exists('bioship_admin_options_default_submenu')) {
 add_filter('optionsframework_menu', 'bioship_admin_options_default_submenu', 0);
 function bioship_admin_options_default_submenu($vmenu) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// note this filter is priority 0 so added filters applied later
	// can be further modified (see Child Theme filters.php)
	if (!THEMECHILD) {
		// only say 'bioship' if using framework as the active parent theme
	    $vmenu['page_title'] = __('BioShip Options','bioship');
		$vmenu['menu_title'] = __('BioShip Options','bioship');
	}
	return $vmenu;
 }
}

// Add Appearance Submenu item
// ---------------------------
// creates Appearance -> Theme Options submenu item for Titan
if (THEMETITAN) {
	add_action('admin_menu', 'bioship_admin_theme_options_submenu');
	if (!function_exists('bioship_admin_theme_options_submenu')) {
	 function bioship_admin_theme_options_submenu() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 2.0.7: added missing translation wrappers
	 	add_theme_page(__('Theme Options','bioship'), __('Theme Options','bioship'), 'edit_theme_options', 'theme-options', 'bioship_admin_theme_options_submenu_dummy');
	 	function bioship_admin_theme_options_submenu_dummy() {} // dummy menu item function
	 }
	}
	// trigger redirect to actual admin theme options page
	// 2.0.5: make redirect available for admin.php also
	if ( (strstr($_SERVER['REQUEST_URI'],'/themes.php')) || (strstr($_SERVER['REQUEST_URI'],'/admin.php')) ) {
	 	if ( (isset($_REQUEST['page'])) && ($_REQUEST['page'] == 'theme-options') ) {
	 		add_action('init', 'bioship_admin_theme_options_page_redirect');
	 	}
	}
}

// Add the Advanced Options (Customizer) Submenu item
// --------------------------------------------------
// 1.9.9: add extra menu item for split Customizer options
if ( (!THEMETITAN) && (!THEMEOPT) ) {
	add_action('admin_menu', 'bioship_admin_theme_options_advanced');
	if (!function_exists('bioship_admin_theme_options_advanced')) {
	 function bioship_admin_theme_options_advanced() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 2.0.7: added missing apply_filters function prefix
		$vsplitoptions = bioship_apply_filters('options_customizer_split_options', true);
		if ($vsplitoptions) {
			// 2.0.7: change to add_theme_page and add missing translation wrappers
		 	add_theme_page(__('Customize Advanced','bioship'), __('Advanced Options','bioship'), 'edit_theme_options', 'customize.php?options=advanced');
		}
	 }
	}
}

// Hack the Theme Options Submenu Position to Top
// ----------------------------------------------
// (Appearance submenu for Options and Titan Framework)
if (!function_exists('bioship_admin_theme_options_position')) {
 add_action('admin_head', 'bioship_admin_theme_options_position');
 function bioship_admin_theme_options_position() {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $menu;
	if (THEMEDEBUG) {echo "<!-- Admin Menu: "; print_r($menu); echo " -->";}

	// 2.0.0: fix for undefined variable warning (dospliceb)
	global $submenu; $vi = 0; $vdosplice = false; $vdospliceb = false;
	if (isset($submenu['themes.php'])) {

		foreach ($submenu['themes.php'] as $submenukey => $vvalues) {
			// 1.8.0: do same for Titan theme options submenu page
			// 1.8.5: allow for theme test drive link overrides as well
			if ( ($vvalues[2] == 'options-framework') || (strstr($vvalues[2],'page=options-framework'))
			  || ($vvalues[2] == 'theme-options') || (strstr($vvalues[2],'page=theme-options')) ) {
				unset($submenu['themes.php'][$submenukey]);
				$vnewposition = bioship_apply_filters('muscle_theme_options_position', '1');
				if (isset($submenu['themes.php'][$vnewposition])) {
					// in trouble, need to insert and shift the array
					$vdosplice = true; $vj = 0; $vthemesettingsvalues = $vvalues;
					foreach ($submenu['themes.php'] as $vkey => $vvalue) {
						if ($vkey == $vnewposition) {$vposition = $vj;}
						$vj++;
					}
				} else {
					// just re-insert at the new position
					$submenu['themes.php'][$vnewposition] = $vvalues;
					$submenuthemes = $submenu['themes.php'];
					ksort($submenuthemes); $submenu['themes.php'] = $submenuthemes;
				}
			}
			// 1.8.5: get the themes.php submenu position
			if ($vvalues[2] == 'themes.php') {$vthemesposition = $submenukey; $vthemessubmenu = $vvalues;}

			// 1.8.0: no longer remove the Customize option (fixed)
			// 1.8.5: maybe rename Customize to Live Preview
			if ($vvalues[1] == 'customize') {
				// 2.0.7: fix for undefined index for no framework
				$vcustomizerposition = $submenukey;
				if ( (THEMETITAN) || (THEMEOPT) ) {
					$submenu['themes.php'][$submenukey][0] = __('Live Preview','bioship');
				}
			}

			// 1.9.9: shift the advanced options (customizer) item position
			if (strstr($vvalues[2],'?options=advanced')) {
				unset($submenu['themes.php'][$submenukey]);
				$vadvposition = $vcustomizerposition + 1;
				// 2.0.7: fix undefined variable for no framework
				if ( (isset($vnewposition)) && (isset($submenu['themes.php'][$vnewposition])) ) {
					$vdospliceb = true; $vk = 0; $vadvancedvalues = $vvalues;
					foreach ($submenu['themes.php'] as $vkey => $vvalue) {
						if ($vkey == $vadvposition) {$vadvancedposition = $vk;}
						$vk++;
					}
				} else {
					$submenu['themes.php'][$vcustomizerposition+1] = $vvalues;
					$submenuthemes = $submenu['themes.php'];
					ksort($submenuthemes); $submenu['themes.php'] = $submenuthemes;
				}
			}

			$vlastposition = $submenukey;
			$vi++;
		}

		if ($vdosplice) {
			// shift the $submenu array maintaining keys
			$submenuthemes = $submenu['themes.php']; $newthemesb = array();
			$submenuthemesa = array_slice($submenuthemes, 0, $vposition, true);
			$submenuthemesb = array_slice($submenuthemes, $vposition, count($submenuthemes), true);
			foreach ($submenuthemesb as $key => $value) {$newthemesb[$key+1] = $value;}
			$submenuthemesa[$vnewposition] = $vthemesettingsvalues;
			$submenuthemes = $submenuthemesa + $newthemesb;
			$submenu['themes.php'] = $submenuthemes;
		}

		// 1.9.9: repeat for advanced options item
		if ($vdospliceb) {
			// shift the $submenu array maintaining keys
			$submenuthemes = $submenu['themes.php']; $newthemesb = array();
			$submenuthemesa = array_slice($submenuthemes, 0, $vadvancedposition, true);
			$submenuthemesb = array_slice($submenuthemes, $vadvancedposition, count($submenuthemes), true);
			foreach ($submenuthemesb as $key => $value) {$newthemesb[$key+1] = $value;}
			$submenuthemesa[$vadvposition] = $vadvancedvalues;
			$submenuthemes = $submenuthemesa + $newthemesb;
			$submenu['themes.php'] = $submenuthemes;
		}

		// 1.8.5: shift the themes submenu item
		if ($vthemesposition != '') {
			unset($submenu['themes.php'][$vthemesposition]);
			$submenu['themes.php'][$submenukey+1] = $vthemessubmenu;
		}

		if (THEMEDEBUG) {
			echo "<!-- SubMenu: "; print_r($submenu); echo " -->";
			echo "<!-- themes.php position: ".$vthemesposition." -->";
			echo "<!-- last position: ".$vi." -->";
		}

	}
 }
}

// Hack Theme Options submenu URLs for Theme Test Driving
// ------------------------------------------------------
if (!function_exists('bioship_admin_themetestdrive_options')) {
 function bioship_admin_themetestdrive_options() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$vtdtheme = $_REQUEST['theme'];
	// 1.8.5: bug out if not test driving via querystring
	if ($vtdtheme == '') {return;}

	global $vthemename, $menu, $submenu;

	foreach ($submenu['themes.php'] as $submenukey => $values) {
		// if (THEMEDEBUG) {echo "<!-- ".$submenukey." : "; print_r($values); echo " -->";} // debug point
		// hack Options Framework submenu URL
		if ($submenu['themes.php'][$submenukey][2] == 'options-framework') {
			$submenu['themes.php'][$submenukey][2] = 'themes.php?page=options-framework&theme='.$vtdtheme;
			break;
		}
		// hack Titan Theme Options submenu URL
		if ($submenu['themes.php'][$submenukey][2] == 'theme-options') {
			$submenu['themes.php'][$submenukey][2] = 'themes.php?page=theme-options&theme='.$vtdtheme;
			break;
		}
	}

	// 1.8.0: Hack Titan Framework Admin URL
	$vmenukey = 'bioship-options'; // $vmenukey = $vthemename.'-options';
	foreach ($menu as $vpriority => $vvalues) {
		// if (THEMEDEBUG) {echo "<!-- ".$vpriority." : "; print_r($vvalues); echo " -->";} // debug point
		// 1.8.5: fix to Titan Theme options admin menu URL link
		if ($vvalues[2] == $vmenukey) {$menu[$vpriority][2] = 'admin.php?page='.$vmenukey.'&theme='.$vtdtheme; break;}
	}

	// debug points
	if (THEMEDEBUG) {
		echo "<!-- Admin Menu: "; print_r($menu); echo " -->";
		echo "<!-- Admin SubMenu: "; print_r($submenu); echo " -->";
	}

 }
}

// Add Theme Documentation Submenu Link
// ------------------------------------
// 2.0.7: add documentation link to admin menu
if (!function_exists('bioship_admin_documentation_menu')) {
 add_action('admin_menu', 'bioship_admin_documentation_menu', 12);
 function bioship_admin_documentation_menu() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	add_theme_page(__('Documentation','bioship'), __('Documentation','bioship'), 'manage_options', 'bioship-documentation');
 }
}

// maybe shift Documentation Submenu to Theme Menu
// -----------------------------------------------
// 2.0.7: added this shift (for Titan framework menu)
if (!function_exists('bioship_admin_documentation_shift')) {
 add_action('admin_head', 'bioship_admin_documentation_shift', 9);
 function bioship_admin_documentation_shift() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $submenu;
	if (THEMETITAN) {
		if ( (array_key_exists('bioship-options', $submenu)) && (array_key_exists('themes.php', $submenu)) ) {
			foreach ($submenu['bioship-options'] as $vkey => $vvalues) {$vlastkey = $vkey + 1;}
			foreach ($submenu['themes.php'] as $vkey => $vvalues) {
				if ($vvalues[2] == 'bioship-documentation') {
					$submenu['bioship-options'][$vlastkey] = $vvalues;
					unset($submenu['themes.php'][$vkey]);
				}
			}
		}
	}
 }
}

// Add Theme Options to the Admin bar menu
// ---------------------------------------
// 1.8.5: moved here from muscle.php, option changed to filter
if (!function_exists('bioship_admin_adminbar_theme_options')) {

 // 2.0.5: check filter inside function for consistency
 add_action('wp_before_admin_bar_render', 'bioship_admin_adminbar_theme_options');

 function bioship_admin_adminbar_theme_options() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_admin_bar, $vthemename, $vthemedirs;

	$vadminbar = bioship_apply_filters('admin_adminbar_theme_options', true);
	if (!$vadminbar) {return;}

	// 1.8.0: link to customize.php if no theme options page exists
	$vthemelink = add_query_arg('return', urlencode(wp_unslash($_SERVER['REQUEST_URI'])), admin_url('customize.php'));

	if ( ( (!THEMETITAN) && (THEMEOPT) )
	  || ( (THEMETITAN) && (class_exists('TitanFramework')) ) ) {
		// 1.8.5: use add_query_arg here
		$vthemelink = admin_url('themes.php');
		if ( (!THEMETITAN) && (THEMEOPT) ) {$vthemepage = 'options-framework';}
		else {$vthemepage = 'bioship-options';} // $vthemename.'-options';
		$vthemelink = add_query_arg('page', $vthemepage, $vthemelink);
	}

	// 1.8.5: maybe append the Theme Test Drive querystring
	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
		$vthemelink = add_query_arg('theme', $_REQUEST['theme'], $vthemelink);
	}

	// 1.5.0: Add an Icon next to the Theme Options menu item
	// ref: http://wordpress.stackexchange.com/questions/172939/how-do-i-add-an-icon-to-a-new-admin-bar-item
	// default is set to \f115 Dashicon (an eye in a screen) in skin.php
	// and can be overridden using admin_adminbar_menu_icon filter
	$vicon = bioship_file_hierarchy('url', 'theme-icon.png', $vthemedirs['img']);
	$vicon = bioship_apply_filters('admin_adminbar_theme_options_icon', $vicon);
	if ($vicon) {
		$viconspan = '<span class="theme-options-icon" style="
			float:left; width:22px !important; height:22px !important;
			margin-left: 5px !important; margin-top: 5px !important;
			background-image:url(\''.$vthemesettingsicon.'\');"></span>';
	} else {$viconspan = '<span class="ab-icon"></span>';}

	$vtitle = __('Theme Options','bioship');
	$vtitle = bioship_apply_filters('admin_adminbar_theme_options_title', $vtitle);
	$vmenu = array('id' => 'theme-options', 'title' => $viconspan.$vtitle, 'href' => $vthemelink);
	$wp_admin_bar->add_menu($vmenu);
 }
}

// Replace Howdy in Admin bar
// --------------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('bioship_admin_adminbar_replace_howdy')) {
 add_filter('admin_bar_menu', 'bioship_admin_adminbar_replace_howdy', 25);
 function bioship_admin_adminbar_replace_howdy($wp_admin_bar) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// 1.9.8: replaced deprecated function get_currentuserinfo();
	// 2.0.7: use new prefixed current user function
	$current_user = bioship_get_current_user();
	$vusername = $current_user->user_login;
	$vmyaccount = $wp_admin_bar->get_node('my-account');
	// 1.5.5: fixed translation for Theme Check
	$vnewtitle = __('Logged in as', 'bioship').' '.$vusername;
	$vnewtitle = bioship_apply_filters('admin_adminbar_howdy_title', $vnewtitle);
	$wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $vnewtitle));
 }
}

// Remove Admin Footer
// -------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('bioship_admin_remove_admin_footer')) {
 add_filter('admin_footer_text', 'bioship_admin_remove_admin_footer');
 function bioship_admin_remove_admin_footer() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	return bioship_apply_filters('muscle_admin_footer_text', '');
 }
}

// ====================================
// === Theme Admin Page Menu Header ===
// ====================================

// Add Top Menu section to Admin Page (for Titan Framework)
if ( (isset($_REQUEST['page'])) && (function_exists('add_action')) ) {

	if ($_REQUEST['page'] == 'bioship-options') {
		add_action('tf_admin_page_before_'.$vthemename, 'bioship_admin_theme_options_page');

		// note: other possible sections of the Titan Framework admin page
		// add_action('tf_admin_page_table_start_'.$vthemename,'');
		// add_action('tf_admin_page_end_'.$vthemename,'');
		// add_action('tf_admin_page_after_'.$vthemename,'');
	}

	// Add Top Menu section to Admin Notices (for Options Framework)
	if ($_REQUEST['page'] == 'options-framework') {
		add_action('all_admin_notices', 'bioship_admin_theme_options_page', 99);
	}
}

// Enqueue Thickbox or Update Nag
// ------------------------------
// 2.0.5: maybe show updates available for admin pages
add_action('admin_notices', 'bioship_admin_theme_updates_echo');

if (isset($_REQUEST['page'])) {
	if ( ($_REQUEST['page'] == 'bioship-options')
	  || ($_REQUEST['page'] == 'options-framework')
	  || ($_REQUEST['page'] == 'theme-info') ) {
		add_action('admin_enqueue_scripts', 'bioship_admin_add_thickbox');
		// 2.0.5: remove theme updates available notice for theme options page
		remove_action('admin_notices', 'bioship_admin_theme_updates_echo');
	}
}

// Add Thickbox Script and Styles
// ------------------------------
// 2.0.5: enqueue for documentation boxes
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

	if (version_compare($wp_version,'3.8', '<')) {$vnagclass = 'updated';} else {$vnagclass = 'update-nag';} // '>

	// 1.8.5: improve extend 'layer' behaviour
	echo "<script language='javascript' type='text/javascript'>
	if (document.getElementById('layertab')) {document.getElementById('prevlayer').value = document.getElementById('layertab').value;}
	if (document.getElementById('".$vthemename."_layertab')) {
		document.getElementById('prevlayer').value = document.getElementById('".$vthemename."_layertab').value;
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
		if (document.getElementById('".$vthemename."_layertab')) {
			prevlayer = document.getElementById('".$vthemename."_layertab').value;
			document.getElementById('".$vthemename."_layertab').value = 'extend';
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
			if (document.getElementById('".$vthemename."_layertab')) {
				document.getElementById('".$vthemename."_layertab').value = 'alloptions';
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
			if (document.getElementById('".$vthemename."_layertab')) {
				document.getElementById('".$vthemename."_layertab').value = optionid;
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
	$vactionurl = admin_url('admin.php');
	if (THEMETITAN) {$vactionurl = add_query_arg('page', 'bioship-options', $vactionurl);} // $vthemename.'-options';
	else {$vactionurl = add_query_arg('page', 'options-framework', $vactionurl);}

	// maybe File System Credentials Form
	// ----------------------------------
	// 1.8.0: checks permissions for creating Child Theme
	// 1.9.5: check permission for creating clones also
	if ( (isset($_REQUEST['newchildname'])) || (isset($_REQUEST['newclonename'])) ) {
		// 2.0.5: use actionurl for url already calculated
		$vmethod = ''; $vcontext = false;
		$vextrafields = array('newchildname'); // not sure if really needed
		$vfilesystemcheck = bioship_admin_check_filesystem_credentials($vactionurl, $vmethod, $vcontext, $vextrafields);
	}

	// WordQuest Floating Sidebar
	// --------------------------
	// 1.8.5: call sidebar here directly (floats right)
	bioship_admin_floating_sidebar();

	// CSS QuickSave Form/Frame
	// ------------------------
	$vadminajax = admin_url('admin-ajax.php');
	echo '<div id="quicksavecsswrapper" style="display:none;">';
	echo '<form id="quicksavecssform" action="'.$vadminajax.'" method="post" target="quicksavecssframe">';
	echo '<input type="hidden" name="action" value="quicksave_css_theme_settings">';
	echo '<input type="hidden" name="theme" value="'.$vthemename.'">';
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
		$vthemelogo = bioship_file_hierarchy('url', 'theme-logo.png', $vthemedirs['img']);
		if ($vthemelogo) {echo '<td><img src="'.$vthemelogo.'"></td>';}
		echo '<td width="10"></td>';

		// Theme Name and Version
		// ----------------------
		echo '<td><table id="themedisplayname" cellpadding="0" cellspacing="0"><tr height="40">';
		echo '<td style="vertical-align:middle;"><h2 style="margin:5px 0;">'.THEMEDISPLAYNAME.'</h2>';
		echo '</td><td width="10"></td><td><h3 style="margin:5px 0;">v';
		// 2.0.5: fix to maybe display Child Theme Version constant
		if (THEMECHILD) {echo THEMECHILDVERSION;} else {echo THEMEVERSION;}
		echo '</h3></td></tr>';
		echo '<tr height="40"><td colspan="3" align="center" style="vertical-align:middle;">';

		// Small Theme Links
		// -----------------
		// TODO: Docs could be a popup link to /bioship/admin/docs.php?
		echo '<font style="font-size:11pt;"><a href="'.THEMEHOMEURL.'/news/" class="frameworklink" title="BioShip Theme Framework News" target=_blank>'.__('News','bioship').'</a>';
		echo ' | <a href="'.THEMEHOMEURL.'/documentation/" class="frameworklink" title="BioShip Theme Framework Documentation" target=_blank>'.__('Docs','bioship').'</a>';
		// echo ' | <a href="'.THEMEHOMEURL.'/faq/" class="frameworklink" title="BioShip Theme Framework Frequently Asked Questions" target=_blank>'.__('FAQ','bioship').'</a>';
		echo ' | <a href="'.THEMEHOMEURL.'/development/" class="frameworklink" title="BioShip Theme Framework Development" target=_blank>'.__('Dev','bioship').'</a>';
		// echo ' | <a href="'.THEMEHOMEURL.'/extensions/" class="frameworklink" title="BioShip Theme Framework Extensions" target=_blank>'.__('Extend','bioship').'</a>';
		echo '</font></center></td></tr></table>';

		echo '</td><td width="10"></td>';
		echo '<td align="center">';

		// One-Click Child Theme Install/Clone Forms
		// -----------------------------------------
		// 1.9.5: handle calls to new child clone here too
		$vnewchild = false; $vnewclone = false;
		if ( (isset($_REQUEST['newchildname'])) || (isset($_REQUEST['newclonename'])) ) {
			// 1.8.5: check wp nonce form field
			if (isset($_REQUEST['newchildname'])) {check_admin_referer('bioship_child_install');}
			if (isset($_REQUEST['newclonename'])) {check_admin_referer('bioship_child_clone');}
			// 1.8.0: install only if WP Filesystem credentials checked out
			if ($vfilesystemcheck) {
				if (isset($_REQUEST['newchildname'])) {
					$vmessage = bioship_admin_do_install_child();
					if (strstr($vmessage,'<!-- SUCCESS -->')) {$vnewchild = true;}
				}
				elseif (isset($_REQUEST['newclonename'])) {
					$vmessage = bioship_admin_do_install_clone();
					if (strstr($vmessage,'<!-- SUCCESS -->')) {$vnewclone = true;}
				}
			} else {$vmessage = __('Check your file system owner/group permissions!','bioship');}
			if ($vmessage != '') {echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;">'.$vmessage.'</div><br>';}
		}

		// 1.9.5: added 'Clone' to new child name for existing child themes
		if (!is_child_theme()) {$vnewchildname = "BioShip Child";}
		else {$vnewclonename = THEMEDISPLAYNAME.' Clone';}

		if ( (!is_child_theme()) && (!$vnewchild) ) {

			if ( (isset($_REQUEST['newchildname'])) && ($_REQUEST['newchildname'] != '') ) {
				$vnewchildname = $_REQUEST['newchildname'];
			}

			// Child Theme Creation Form
			// -------------------------
			echo '<form action="'.$vactionurl.'" method="post">';
			// 1.8.5: added nonce field to form
			wp_nonce_field('bioship_child_install');
			// 1.9.5: in case of theme driving a theme?
			if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
				echo '<input type="hidden" name="theme" value="'.$_REQUEST['theme'].'">';
			}
			echo '<table><tr><td align="left">';
			echo '<font style="font-size:11pt;line-height:22px;"><b>'.__('Create Child Theme','bioship').':</b></font></td>';
			echo '<td width="10"></td><td><input type="text" name="newchildname" style="font-size:11pt; width:120px;" value="'.$vnewchildname.'"></td>';
			echo '<td width="10"></td><td><input type="submit" class="button-primary" title="'.__('Note: alphanumeric and spaces only.','bioship').'" value="'.__('Create','bioship').'"></td>';
			echo '</td></tr></table></form>';
		}
		elseif ( (is_child_theme()) && (!$vnewclone) ) {

			if ( (isset($_REQUEST['newclonename'])) && ($_REQUEST['newclonename'] != '') ) {
				$vnewclonename = $_REQUEST['newclonename'];
			}

			// Clone Child Theme Form
			// ----------------------
			// 1.9.5: added child theme clone form
			echo '<form action="'.$vactionurl.'" method="post">';
			wp_nonce_field('bioship_child_clone');
			// 2.0.5: use existing THEMESLUG constant here
			echo '<input type="hidden" name="clonetheme" value="'.THEMESLUG.'">';
			echo '<table><tr><td align="left">';
			echo '<font style="font-size:11pt;line-height:22px;"><b>'.__('Clone Child Theme to','bioship').':</b></font></td>';
			echo '<td width="10"></td><td><input type="text" name="newclonename" style="font-size:11pt; width:120px;" value="'.$vnewclonename.'"></td>';
			echo '<td width="10"></td><td><input type="submit" class="button-primary" title="'.__('Note: alphanumeric and spaces only.','bioship').'" value="'.__('Clone','bioship').'"></td>';
			echo '</td></tr></table></form>';
		}

		// Theme Options Filter Buttons
		// ----------------------------
		echo '<table style="float:left; margin-top:5px;"><tr>';
		// Theme Home (extend) button
		echo '<td><div id="extendoptions" class="filterbutton" onclick="showhideextend();">';
		echo '<a href="javascript:void(0);">'.__('Info','bioship').'</a>';
		echo '</div></td>';
		// Skin filter button
		echo '<td width="10"></td><td><div id="skinoptions" class="filterbutton activefilter" onclick="switchlayers(\'skin\');">';
		echo '<a href="javascript:void(0);">'.__('Skin','bioship').'</a>';
		echo '</div></td>';
		// Muscle filter button
		echo '<td width="10"></td><td><div id="muscleoptions" class="filterbutton" onclick="switchlayers(\'muscle\');">';
		echo '<a href="javascript:void(0);">'.__('Muscle','bioship').'</a>';
		echo '</div></td>';
		// Skeleton filter button
		echo '<td width="10"></td><td><div id="skeletonoptions" class="filterbutton" onclick="switchlayers(\'skeleton\');">';
		echo '<a href="javascript:void(0);">'.__('Skeleton','bioship').'</a>';
		echo '</div></td>';
		// ALL options button
		echo '<td width="10"></td><td><div id="alloptions" class="filterbutton" onclick="showalllayers();">';
		echo '<a href="javascript:void(0);">'.__('ALL','bioship').'</a>';
		echo '</div></td>';
		echo '</tr></table>';

	echo '</td></tr></table></div><br>';

	// maybe output Theme Updates available
	// ------------------------------------
	// 1.9.6: separate line for theme update alert
	$vthemeupdates = bioship_admin_theme_updates_available();
	if ($vthemeupdates != '') {
		$vthemeupdates = str_replace('<br>', ' ', $vthemeupdates);
		echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">'.$vthemeupdates.'</div></font><br>';
	}

	// Theme Admin Notices
	// -------------------
	// 1.9.5: added this theme action since main admin_notices are now boxed
	// 2.0.7: fix to action name typo (is singular not plural)
	ob_start(); bioship_do_action('theme_admin_notice');
	$vthemenotices = ob_get_contents(); ob_end_clean();
	if ($vthemenotices != '') {
		echo '<div style="float:none; clear:both;"></div><br>';
		echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;">'.$vthemenotices.'</div>';
	}

	// Theme Info Section
	// ------------------
	echo '<div id="extendwrapper" class="wrap"';
	if ($vthemesettings['layertab'] != '') {echo 'style="display:none;"';}
	echo '>';
		bioship_admin_theme_info_section();
	echo '<p>&nbsp;</p><br></div>';
	echo '<div style="float:none; clear:both;"></div>';

	// Option/Layer Tab Javascript to Admin Footer
	// -------------------------------------------
	add_action('admin_footer', 'bioship_admin_theme_options_scripts');

	// 1.8.0: fixed nesting problem causing javascript error
	// 1.8.0: detect and switch to tab for Titan framework also
	// 1.8.5: simplified defaults and improved options switching
	if (!function_exists('bioship_admin_theme_options_scripts')) {
	 function bioship_admin_theme_options_scripts() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemename;

		// Switch Layer Display
		// --------------------
		$vdefaulttab = 'skin';
		// 2.0.5: change default tab for welcome message
		if ( (isset($_REQUEST['welcome'])) && ($_REQUEST['welcome'] == 'true') ) {$vdefaulttab = 'extend';}

		echo "<script language='javascript' type='text/javascript'>
		function optionstabsdisplay() {
			var layertab; var optionstab;
			if (document.getElementById('layertab')) {layertab = document.getElementById('layertab').value;} else {
				if (document.getElementById('".$vthemename."_layertab')) {layertab = document.getElementById('".$vthemename."_layertab').value;}
			}

			if (layertab == '') {layertab = '".$vdefaulttab."';}
			if (layertab == 'alloptions') {showalllayers();}
			else {
				if (layertab == 'extend') {switchlayers('skin'); showhideextend();}
				else {switchlayers(layertab);}
			}

			var optionstab = '';
			if (document.getElementById('optionstab')) {optionstab = document.getElementById('optionstab').value;} else {
				if (document.getElementById('".$vthemename."_optionstab')) {optionstab = document.getElementById('".$vthemename."_optionstab').value;}
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
		$vtableclasses = '#optionsframework-wrap,#optionsframework-metabox,#optionsframework,.nav-tab-wrapper';
		// $vtableclasses .= ',.group,.options-container';
		// for Titan Framework
		if (THEMETITAN) {$vtableclasses = '.titan-framework-panel-wrap,.nav-tab-wrapper,.options-container,.form-table';}

		// make room for righthand sidebar...
		// TODO: hide sidebar if screen width is too small to handle it?
		echo "function resizeoptionstables() {
			if (document.getElementById('floatdiv')) {
				var wpbodywidth = jQuery('#wpbody').width(); var newwidth = wpbodywidth - 270;
				if (newwidth < 640) {
					document.getElementById('floatdiv').style.display = 'none';
					jQuery('".$vtableclasses."').css('width','100%');
				} else {
					document.getElementById('floatdiv').style.display = 'block';
					jQuery('".$vtableclasses."').css('width',newwidth+'px');
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
				document.getElementById('".$vthemename."_optionstab').value = optionstab.toLowerCase();
				console.log(document.getElementById('".$vthemename."_optionstab').value);
			});";
		}

		// Dynamic CSS QuickSave Button
		// ----------------------------
		// 1.8.5: added CSS quicksave button
		if (THEMETITAN) {echo PHP_EOL."var dynamiccssareaid = '".$vthemename."_dynamiccustomcss';".PHP_EOL;}
		else {echo PHP_EOL."var dynamiccssareaid = 'dynamiccustomcss';".PHP_EOL;}
		echo "
			quicksavebutton = document.createElement('a');
			quicksavebutton.setAttribute('class','button button-primary');
			quicksavebutton.setAttribute('style','margin-left:-80px; float:right;');
			quicksavebutton.innerHTML = 'Save CSS';
			quicksavebutton.href = 'javascript:void(0);';
			quicksavebutton.id = 'quicksavebutton';

			quicksavesaved = document.createElement('div');
			quicksavesaved.id = 'quicksavesaved';
			quicksavesaved.innerHTML = 'CSS Saved!';

			function quicksavedshow() {
				quicksaved = document.getElementById('quicksavesaved');
				quicksaved.style.display = 'block';
				setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
				/* alert('Saved!'); */
			}
		".PHP_EOL;
		// if (document.getElementById('dynamiccustomcss')) {jQuery('#dynamiccustomcss').parent.addClass('nolabel');}

		// Call Document Ready Functions
		// -----------------------------
		// 1.8.0: call tab display and resize on page load
		// 1.8.5: run these function after document ready
		echo "jQuery(document).ready(function($) {

			/* insert CSS quicksave button */
			csstextarea = document.getElementById(dynamiccssareaid);
			textareaparent = csstextarea.parentNode;
			textareaparent.insertBefore(quicksavebutton,csstextarea);
			textareaparent.insertBefore(quicksavesaved,csstextarea);
			jQuery('#quicksavebutton').click(function() {
				newcss = document.getElementById(dynamiccssareaid).value;
				jQuery('#quicksavecss').val(newcss);
				jQuery('#quicksavecssform').submit();
			});

			optionstabsdisplay(); resizeoptionstables(); jQuery('#floatdiv').fadeIn();
			";

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
			// FIXME: check that stick_in_parent function exists?
			// echo "if (typeof stick_in_parent === 'function') {";
			echo "jQuery('#floatdiv').stick_in_parent({offset_top:100});";
			// echo "}";

		echo "});</script>";
	 }
	}

	// Closing Div For Theme Options Center
	// ------------------------------------
	// 1.8.5: closes #themeoptionswrap div
	add_action('tf_admin_page_after_'.$vthemename, 'bioship_admin_theme_options_close'); // Titan
	add_action('optionsframework_after', 'bioship_admin_theme_options_close'); // Options Framework
	if (!function_exists('bioship_admin_theme_options_close')) {
	 function bioship_admin_theme_options_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo '</div>';
		bioship_html_comment('/#themeoptionswrap');
	 }
	}
 }
}

// -------------------------
// Theme Options Page Styles
// -------------------------
if (isset($_REQUEST['page'])) {
	if ( ($_REQUEST['page'] == 'options-framework')
	  || ($_REQUEST['page'] == 'bioship-options')
	  || ($_REQUEST['page'] == $vthemename.'-options') ) {

		if (function_exists('add_action')) {
			add_action('admin_head', 'bioship_admin_theme_options_styles');
		}

		if (!function_exists('bioship_admin_theme_options_styles')) {
		 function bioship_admin_theme_options_styles() {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		 	global $vthemename;
		 	// 1.8.5: improved button tab colour scheme
		 	// TODO: separate admin page CSS out to an admin-styles.css file?
		 	// (this would disallow filtering however :-/ )
			$vstyles = "#wpcontent {padding-left:0px;} #wpbody {padding-left:20px; background-color:#D0D0EE;}
			    #wpbody-content {background-color: #EEEEEE; padding-left: 20px;}
				.wrap {margin-right:0px; margin-left:0px;} .wrap select {min-width:150px;}
				#themeoptionswrap {float:left;} #floatdiv {display:none; float:right;}
				#themedisplayname {background-color: #E0E0FF; border: 1px solid #77AAEE; padding: 0 10px;}
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
				#dynamiccustomcss, #".$vthemename."_dynamiccustomcss {width: 86% !important; height: 400px;}
				#dynamicadmincss, #".$vthemename."_dynamicadmincss {width: 86% !important; height: 250px;}

				#postmetatop, #postmetabottom, #".$vthemename."_postmetatop, #".$vthemename."postmetabottom,
				#pagemetatop, #pagemetabottom, #".$vthemename."_pagemetatop, #".$vthemename."pagemetabottom {height:30px;}
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
			".PHP_EOL;
			$vstyles = bioship_apply_filters('options_themepage_styles', $vstyles);
			echo "<style>".$vstyles."</style>";
		 }
		}
	}
}

// -----------------------
// Floating Sidebar Output
// -----------------------
// Donations / Testimonials / Sidebar Ads / Footer
if (!function_exists('bioship_admin_floating_sidebar')) {
 function bioship_admin_floating_sidebar() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// Include WordQuest Sidebar
	// -------------------------
	$vsidebar = bioship_file_hierarchy('file', 'wordquest.php', array('includes'));
	// 1.8.5: change from wordquest_admin_load to match new helper version (1.6.0)
	if ($vsidebar) {include_once($vsidebar); wqhelper_admin_loader();}

	if (function_exists('wqhelper_sidebar_floatbox')) {

		// Filter Sidebar Save Button
		// --------------------------
		add_filter('wordquest_sidebar_save_button', 'bioship_admin_sidebar_save_button');
		if (!function_exists('bioship_admin_sidebar_save_button')) {
		 function bioship_admin_sidebar_save_button($vbutton) {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
			if (THEMETITAN) {
				$vsubmitfunction = "jQuery('.options-container form button[name=\"action\"]').each(function() {
					if (jQuery(this).hasClass('button-primary')) {jQuery(this).trigger('click');}
				});";
			} // for Titan Framework
			else {$vsubmitfunction =  "jQuery('#optionsframework form').submit();";} // for Options Framework

			// 1.8.0: replace the sidebar save button
			// 1.8.5: add onlick event instead of replacing the button
			// 1.9.5: replace the button agasin to remove the inline sidebar save onlick event
			$vbutton = "<table><tr>";
			$vbutton .= "<td align='center'><input id='sidebarsavebutton' type='button' class='button-primary' value='Save Settings'></td>";
			$vbutton .= "<td width='30'></td>";
			$vbutton .= "<td><div style='line-height:1em;'><font style='font-size:8pt;'><a href='javascript:void(0);' style='text-decoration:none;' onclick='doshowhidediv(\"sidebarsettings\");hidesidebarsaved();'>Sidebar<br>Options</a></font></div></td>";
			$vbutton .= "</tr></table>";
			$vbutton .= "<script>jQuery('#sidebarsavebutton').click(function() {".$vsubmitfunction."});</script>";
			return $vbutton;
		 }
		}

		// Load Floating Sidebar
		// ---------------------
		$vargs = array('bioship','yes');
		// 1.8.5: removed float script for theme in favour of using stickykit
		// $vfloatmenuscript = wqhelper_sidebar_floatmenuscript();
		// echo $vfloatmenuscript;
		// 1.8.5: change from wordquest_sidebar_floatbox to match new helper version (1.6.0)
		wqhelper_sidebar_floatbox($vargs);

	}

 }
}

// QuickSave CSS
// -------------
// 1.8.5: added CSS quicksave
add_action('wp_ajax_quicksave_css_theme_settings', 'bioship_admin_quicksave_css');
if (!function_exists('bioship_admin_quicksave_css')) {
 function bioship_admin_quicksave_css() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (current_user_can('edit_theme_options')) {
		$vthemename = $_POST['theme'];
		$vchecknonce = check_admin_referer('quicksave_css_'.$vthemename);
		if ($vchecknonce) {
			global $vthemesettings;
			$vthemesettings['dynamiccustomcss'] = stripslashes($_POST['quicksavecss']);
			if (THEMETITAN) {$vthemesettings = serialize($vthemesettings);}
			update_option(THEMEKEY,$vthemesettings);
		} else {$verror = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}
	} else {$verror = __('Failed. Looks like you may need to login again!','bioship');}

	if ($verror) {echo "<script>alert('".$verror."');</script>";}
	else {echo "<script>parent.quicksavedshow();</script>";}
	exit;
 }
}

// TODO: add a CSS quicksave menu item (dropdown?) to navbar
// - with "leave this page without saving" catch?


// =======================
// === Theme Info Page ===
// =======================

// Theme Info Page
// ---------------
// standalone page for WordPress.org version (no Theme Framework page)
if (!function_exists('bioship_admin_theme_info_page')) {
 function bioship_admin_theme_info_page() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// TODO: improve admin page title display..?
	echo "<h3>".__('BioShip Theme Info','bioship')."</h3><br>";

	// !TODO! add Titan installation info and install link?

	// Include WordQuest Sidebar
	// -------------------------
	$vsidebar = bioship_file_hierarchy('file', 'wordquest.php', array('includes'));
	// 1.8.5: change from wordquest_admin_load for new helper version (1.6.0+)
	if ($vsidebar) {include_once($vsidebar); wqhelper_admin_loader();}

	// Load Theme Info Section
	// -----------------------
	echo '<div class="wrap">';
		bioship_admin_theme_info_section();
	echo '<p></p><br></div><div style="float:none; clear:both;"></div>';

 }
}

// Theme Info Section
// ------------------
// 1.8.0: separate function for theme home info tab
if (!function_exists('bioship_admin_theme_info_section')) {
 function bioship_admin_theme_info_section() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemedirs;

	$vwelcome = false;
	if ( (isset($_REQUEST['welcome'])) && ($_REQUEST['welcome'] == 'true') ) {$vwelcome = true;}

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
	$vboxid = 'adminnotices'; $vboxtitle = __('Admin Notices','bioship');
	echo '<div id="'.$vboxid.'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span id="'.$vboxid.'-arrow">&#9662;</span> '.$vboxtitle.'</h3>';
	echo '<div class="inside" id="'.$vboxid.'-inside" style="display:none;">';
	echo '<h2></h2>';
		// Admin Notices reinsert themselves magically here after the <h2> tag inside a <div class="wrap">
		// Note: TGM Plugin Activations Notice still disappears from here when dismissed :-(
		// FIXME =dismiss_admin_notices should have no effect here (and only here)
	echo '</div></div>';

	// Welcome / Documentation - Wide
	// ------------------------------
	// 2.0.5: add combined welcome and documentation box
	$vboxid = 'documentation';
	if ($vwelcome) {$vboxtitle = __('Welcome!','bioship');}
	else {$vboxtitle = __('Documentation','bioship');}
	echo '<div id="'.$vboxid.'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');">';
	echo '<span id="'.$vboxid.'-arrow">';
	if ($vwelcome) {echo '&#9656;';} else {echo '&#9662;';}
	echo '</span> '.$vboxtitle.'</h3>';
	echo '<div class="inside" id="'.$vboxid.'-inside"';
	if (!$vwelcome) {echo ' style="display:none;"';}
	echo '><center>';
		bioship_admin_documentation_box($vwelcome);
	echo '</div></div>';

	// Theme Tools - Wide (collapsed)
	// ------------------------------
	$vboxid = 'themetools'; $vboxtitle = __('Theme Settings Tools','bioship');
	echo '<div id="'.$vboxid.'" class="postbox" style="max-width:680px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span id="'.$vboxid.'-arrow">&#9662;</span> '.$vboxtitle.'</h3>';
	echo '<div class="inside" id="'.$vboxid.'-inside" style="display:none;"><center>';
		bioship_admin_theme_tools_forms();
	echo '</div></div>';

	// Left Column - Links / Extensions
	// --------------------------------
	echo '<div id="extendcolumn">';

		// BioShip Theme Links
		// -------------------
		$vboxid = 'bioshiplinks'; $vboxtitle = __('Theme Links','bioship');
		echo '<div id="'.$vboxid.'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
		echo '<div class="inside" id="'.$vboxid.'-inside">';
			$vbioshiplogo = bioship_file_hierarchy('url', 'bioship.png', $vthemedirs['img']);
			echo '<table><tr><td style="vertical-align:top;"><a href="'.THEMESUPPORT.'" class="themelink" target=_blank><img src="'.$vbioshiplogo.'" border="0"></a></td>';
			echo '<td width="20"></td>';
			echo '<td><a href="'.THEMEHOMEURL.'/documentation/" class="themelink" target=_blank>'.__('BioShip Documentation','bioship').'</a><br>';
			// TODO: maybe add a dropdown list of documentation subpages?
			// Support Forum Links
			echo '<a href="'.THEMESUPPORT.'/quest/quest-category/bioship-support/" class="themelink" target=_blank>'.__('Support Solutions','bioship').'</a><br>';
			echo '<a href="'.THEMESUPPORT.'/quest/quest-category/bioship-features/" class="themelink" target=_blank>'.__('Features and Feedback','bioship').'</a><br>';
			// Development
			echo '<a href="http://github.com/majick777/bioship/" class="themelink" target=_blank>'.__('Development via GitHub','bioship').'</a><br><br>';
			// Extensions
			echo '<a href="'.THEMEHOMEURL.'/extensions/" class="themelink" target=_blank>'.__('BioShip Extensions','bioship').'</a><br>';
			// Content Sidebars / AutoSave Net / ... FreeStyler?
			echo '&rarr; <a href="'.THEMESUPPORT.'/plugins/content-sidebars/" class="themelink" target=_blank>Content Sidebars Plugin</a><br>';
			echo '&rarr; <a href="'.THEMESUPPORT.'/plugins/autosave-net/" class="themelink" target=_blank>AutoSave Net Plugin</a>';
			// echo '<a href="'.THEMESUPPORT.'/plugins/freestyler/" class="themelink" target=_blank>FreeStyler</a>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// WordQuest Plugins
		// -----------------
		$vboxid = 'wordquestplugins'; $vboxtitle = __('WordQuest Alliance','bioship');
		echo '<div id="'.$vboxid.'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
		echo '<div class="inside" id="'.$vboxid.'-inside">';
			$vwordquestlogo = bioship_file_hierarchy('url', 'wordquest.png', $vthemedirs['img']);
			echo '<table><tr><td><a href="http://wordquest.org" target=_blank><img src="'.$vwordquestlogo.'" border="0"></a></td><td width="20"></td><td>';
			if (isset($GLOBALS['admin_page_hooks']['wordquest'])) {
				echo '<a href="'.admin_url('admin.php').'?page=wordquest" class="themelink">'.__('Plugin Panel','bioship').'</a><br>';
			}
			echo '<a href="http://wordquest.org/register/" class="themelink" target=_blank>'.__('Join the Alliance','bioship').'</a><br>';
			echo '<a href="http://wordquest.org/login/" class="themelink" target=_blank>'.__('Members Login','bioship').'</a><br>';
			echo '<a href="http://wordquest.org/solutions/" class="themelink" target=_blank>'.__('Solutions Forum','bioship').'</a><br>';
			echo '<a href="http://wordquest.org/plugins/" class="themelink" target=_blank>'.__('WordQuest Plugins','bioship').'</a><br>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// Recommended Plugins
		// -------------------
		// 1.8.5: added recommended box display
		$vrecommended = bioship_file_hierarchy('file', 'recommended.php', array('includes'));
		if ($vrecommended) {
			include($vrecommended);
			$vrecommend = bioship_admin_get_recommended();
			if ($vrecommend) {
				$vboxid = 'recommended'; $vboxtitle = __('Recommended','bioship');
				echo '<div id="'.$vboxid.'" class="postbox">';
				echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
				echo '<div class="inside" id="'.$vboxid.'-inside">';
					echo $vrecommend;
				echo '</div></div>';
			}
		}

	echo '</div>'; // close extend column

	// Right Column - Dashboard Feed Widgets
	// -------------------------------------
	echo '<div id="feedcolumn">';

		// 1.8.0: allow turning feeds off if being problematic
		$vfeeds = true;
		if (isset($_REQUEST['loadfeeds'])) {
			$vloadfeeds = $_REQUEST['loadfeeds'];
			if ( ($vloadfeeds == 'on') || ($vloadfeeds == '1') ) {delete_option('bioship_admin_feed_display');}
			if ( ($vloadfeeds == 'off') || ($vloadfeeds == '0') ) {update_option('bioship_admin_feed_display','off');}
			if ( ($vloadfeeds == 'no') || ($vloadfeeds == '2') ) {$vfeeds = false;}
		}
		if (get_option('bioship_admin_feed_display') == 'off') {$vfeeds = false;}

		// BioShip Feed
		// ------------
		$vboxid = 'bioshipfeed'; $vboxtitle = __('BioShip News','bioship');
		if ( ($vfeeds) && (function_exists('muscle_bioship_dashboard_feed_widget')) ) {
			echo '<div id="'.$vboxid.'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
			echo '<div class="inside" id="'.$vboxid.'-inside">';
				echo "<!-- start BioShip News Feed -->";
				muscle_bioship_dashboard_feed_widget(false,false);
				echo "<!-- end BioShip News Feed -->";
			echo '</div></div>';
		}

		// WordQuest Feed
		// --------------
		$vboxid = 'wordquestfeed'; $boxtitle = __('WordQuest News','bioship');
		if ( ($vfeeds) && (function_exists('wqhelper_dashboard_feed_widget')) ) {
			echo '<div id="'.$vboxid.'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$boxtitle.'</span></h2>';
			echo '<div class="inside" id="'.$vboxid.'-inside">';
				echo "<!-- start Wordquest News Feed -->";
				wqhelper_dashboard_feed_widget();
				echo "<!-- end Wordquest News Feed -->";
			echo '</div></div>';
		}

		// PluginReview Feed
		// -----------------
		$vboxid = 'pluginreviewfeed'; $boxtitle = __('Plugin Reviews','bioship');
		if ( ($vfeeds) && (function_exists('wqhelper_pluginreview_feed_widget')) ) {
			echo '<div id="'.$vboxid.'" class="postbox">';
			echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$boxtitle.'</span></h2>';
			echo '<div class="inside" id="'.$vboxid.'-inside">';
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

// Welcome Message
// ---------------
if (!function_exists('bioship_admin_welcome_message')) {
 function bioship_admin_welcome_message() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}



 }
}

// Documentation Box
// -----------------
// 2.0.5: added documentation link box
if (!function_exists('bioship_admin_documentation_box')) {
 function bioship_admin_documentation_box($vwelcome) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemetemplateurl;

	// Load Docs
	include(dirname(__FILE__).'/docs.php');

	// Welcome Message
	if ($vwelcome) {
		// 2.0.7: use new prefixed current user function
		$current_user = bioship_get_current_user();
		$vfirstname = $current_user->user_firstname;
		if ($vfirstname != '') {$vfirstname = ', '.$vfirstname;}
		echo '<p align="left">'.__('Welcome aboard','bioship').$vfirstname.'! ';
		echo __('And thanks for choosing to pilot BioShip...','bioship').'</p>';
	}

	// QuickStart Section
	echo '<div id="quickstart"';
	if (!$vwelcome) {echo ' style="display:none;"';}
	echo '><h4>'.__('QuickStart Guide','bioship').'</h4>';
		echo bioship_docs_quickstart(false);
	echo '</div>';

	// QuickStart Script
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
	}
	</script>";

	// QuickStart Links
	$vqslinks = '<div id="showquickstart"';
	if ($vwelcome) {$vqslinks .= ' style="display:none;"';}
	$vqslinks .= '><a href="javascript:void(0);" onclick="showquickstart();">';
	$vqslinks .= __('Show','bioship').' '.__('QuickStart Guide','bioship').'</a></div>';
	$vqslinks .= '<div id="hidequickstart"';
	if (!$vwelcome) {$vqslinks .= ' style="display:none;"';}
	$vqslinks .= '><a href="javascript:void(0);" onclick="hidequickstart();">';
	$vqslinks .= __('Hide','bioship').' '.__('QuickStart Guide','bioship').'</a></div>';
	$vqslinks .= '<br>';

	// Documentation Index
	$vdocindex = bioship_docs_index(false);
	$vdocindex = str_replace('h3>', 'h4>', $vdocindex);
	$vdocindex = str_replace('<!-- START -->', '<center><table><tr><td align="left">'.$vqslinks, $vdocindex);
	$vdocindex = str_replace('<!-- SPLIT -->', '</td><td width="50"></td><td align="left" style="vertical-align:top;">', $vdocindex);
	$vdocindex = str_replace('<!-- END -->', '</td></tr></table></center>', $vdocindex);
	$vdocindex = str_replace('a href="docs.php?page=', 'a class="doc-thickbox" href="#', $vdocindex);
	echo $vdocindex;

	// Documentation Thickbox Script
	$vdocsurl = $vthemetemplateurl.'admin/docs.php';
	echo "<script>jQuery(document).ready(function() {
	    jQuery('.doc-thickbox').click(function() {
	    	width = jQuery(window).width() * 0.9;
	    	height = jQuery(window).height() * 0.8;
	    	thishref = jQuery(this).attr('href').replace('#','');
	        tb_show('', '".$vdocsurl."?page='+thishref+'&TB_iframe=true&width='+width+'&height='+height);
	        return false;
	    });
	});</script>";
 }
}


// ===================================
// Check for Theme Updates HTML Output
// ===================================

// Echo Theme Updates Available
// ----------------------------
// 2.0.5: for admin_notice section
if (!function_exists('bioship_admin_theme_updates_echo')) {
 function bioship_admin_theme_updates_echo() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	$vthemeupdates = bioship_admin_theme_updates_available();
	// only show updates if there is user capability for it
	if ( ($vthemeupdates != '') && (!strstr($vthemeupdates, '<!-- NO CAPABILITY -->')) ) {
		global $wp_version;
		if (version_compare($wp_version,'3.8', '<')) {$vnagclass = 'updated';} else {$vnagclass = 'update-nag';} // '>
		$vthemeupdates = str_replace('<br>', ' ', $vthemeupdates);
		echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">';
		echo $vthemeupdates.'</div></font><br>';
	}
 }
}

// Generate Theme Updates Available
// --------------------------------
if (!function_exists('bioship_admin_theme_updates_available')) {
 function bioship_admin_theme_updates_available() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_version, $vthemename;
	$vthemedisplayname = THEMEDISPLAYNAME;
	// 2.0.5: use existing THEMESLUG constant
	$vupdatehtml = ''; $vthemeslug = THEMESLUG;

	// check user capability just in case
	if (!current_user_can('update_themes')) {
		if (is_child_theme()) {
			$vtheme = wp_get_theme(get_stylesheet($vthemeslug));
			$vparenttheme = wp_get_theme(get_template($vtheme['Template']));
			$vparentversion = $vparenttheme['Version'];
			$vupdatehtml = __('Parent Theme','bioship').':<br>';
			$vupdatehtml .= __('BioShip Framework','bioship').' v'.$vparentversion.'<br>';
		}
		// else {$vupdatehtml = __('Theme Framework','bioship').' v'.$vthemeversion.'<br>';}
		// 2.0.5: add hidden capability section for admin notices
		$vupdatehtml .= '<!-- NO CAPABILITY -->';
		return $vupdatehtml;
	}

	// note: created from modified WP function get_theme_update_available
	$vupdatestransient = get_site_transient('update_themes');
	// 2.0.7: fix for possible empty transient theme update response
	if (!property_exists($vupdatestransient, 'response')) {return $vupdatehtml;}
	$vupdates = $vupdatestransient->response;
	if (THEMEDEBUG) {echo "<!-- Updates Transient: "; print_r($vupdatestransient); echo " -->";}

	if (is_child_theme()) {
		if (isset($vupdates[$vthemeslug])) {
			// special: allow for a user specified Child Theme update location
			// ie. by calling a new Theme Updater instance in their Child Theme
			$vupdate = $vupdates[$vthemeslug];
			$vupdate_url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($vthemeslug)), 'upgrade-theme_'.$vthemeslug);
			$vupdate_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Warning! Updating this Child Theme will lose any file customizations you have made! 'Cancel' to stop, 'OK' to update.",'bioship') ) . '\') ) {return true;} return false;"';
			if (!empty($vupdate['package'])) {
				// if ( (!is_multisite()) || ( (is_multisite()) && (current_user_can('manage_network_themes')) ) ) {
					$vupdatehtml .= sprintf(__( 'New Child Theme available.<br><a href="%2$s" title="%3$s" target=_blank>View v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship' ),
					$vthemedisplayname, esc_url($vupdate['url']), esc_attr($vthemedisplayname), $vupdate['new_version'], $vupdate_url, $vupdate_onclick );
				// }
			}
		}

		// change the info so the parent theme updates are displayed next
		$vtheme = wp_get_theme($vthemeslug);
		$vparenttheme = wp_get_theme(get_template($vtheme['Template']));
		$vthemeslug = $vparenttheme['Stylesheet'];
		$vthemedisplayname = $vparenttheme['Stylesheet'];
		$vthemeversion = $vparenttheme['Version'];
		$vupdatehtml .= __('Parent Theme','bioship').':<br>';
		$vupdatehtml .= __('BioShip Framework','bioship').' v'.$vthemeversion.'<br>';

		if (THEMEDEBUG) {
			echo "<!-- Theme Slug: ".$vthemeslug." - Name: ".$vthemedisplayname." - Version: ".$vthemeversion." -->";
		}
	}

	// output in either case (child theme parent or base framework)
	if (isset($vupdates[$vthemeslug])) {
		$vupdate = $vupdates[$vthemeslug];
		// 2.0.5: recompare versions (as if theme is updated manually transient will be old!)
		if (THEMEDEBUG) {
			echo "<!-- Current Framework Version: ".$vparenttheme['Version'];
			echo " - Update Version: ".$vupdate['new_version']." -->";
		}
		if (version_compare($vparenttheme['Version'], $vupdate['new_version'], '<')) { // '>
			$vupdate_url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($vthemeslug)), 'upgrade-theme_'.$vthemeslug);
			$vupdate_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Updating this Theme Framework will lose any file customizations not in your Child Theme. 'Cancel' to stop, 'OK' to update.",'bioship') ) . '\') ) {return true;} return false;"';
			if (!empty($vupdate['package'])) {
				// if ( (!is_multisite()) || ( (is_multisite()) && (current_user_can('manage_network_themes')) ) ) {
					$vupdatehtml .= sprintf(__('New Framework version available!<br><a href="%2$s" title="%3$s" target=_blank>v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship'),
					$vthemedisplayname, esc_url($vupdate['url']), esc_attr($vthemedisplayname), $vupdate['new_version'], $vupdate_url, $vupdate_onclick );
				// }
			}
		}
	}

	return $vupdatehtml;
 }
}

// =============================
// One-Click Child Theme Install
// =============================

if (!function_exists('bioship_admin_do_install_child')) {
 function bioship_admin_do_install_child() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $wp_filesystem; // load WP Filesystem for correct writing permissions
	global $vthemetemplatedir, $vthemestyledir; $vmessage = '';

	// match new Child Name, allowing for spaces
	$vnewchildname = $_REQUEST['newchildname'];
	if ($vnewchildname == '') {return __('Error: Child Theme Name cannot be empty.','bioship');}
	if (!preg_match('/^[0-9a-z ]+$/i', $vnewchildname)) {return __('Error. Letters, numbers and spaces only please!','bioship');}
	$vnewchildslug = preg_replace("/\W/", "-", strtolower($vnewchildname));

	// 1.8.0: use directory separator, added debug dir
	$vthemesdir = get_theme_root().DIRSEP;
	$vchilddir = $vthemesdir.$vnewchildslug.DIRSEP;
	$vchildimagedir = $vchilddir.'images'.DIRSEP;
	$vchildcssdir = $vchilddir.'styles'.DIRSEP;
	$vchildjsdir = $vchilddir.'javascripts'.DIRSEP;
	$vchilddebugdir = $vchilddir.'debug'.DIRSEP;

	// keeping child theme files to a minimum here...
	// 1.8.0: also copy core-styles.css also to avoid using WP Filesystem for it later
	// 1.9.0: no longer copy hooks.php or template.php by default, do copy debug/.htaccess
	$vchildfiles = array('style.css','core-styles.css','functions.php','filters.php','screenshot.jpg','theme-logo.png','.htaccess');

	// Create Child Theme directory
	// ----------------------------
	if (is_dir($vchilddir)) {
		// Do NOT Continue! We must avoid overwriting an existing Child Theme!
		$vmessage = __('Aborted! Child Theme directory of that name already exists!','bioship').'<br>';
		$vmessage .= __('Remove or rename the existing directory and try again.','bioship').'<br>';
		return $vmessage;
	}
	else {
		// 1.8.0: fix for correct directory permissions
		// ...the old way
		// clearstatcache(); $vpermissions = fileperms(get_template_directory()));
		// umask(0000); @mkdir($vchilddir,$vpermissions); @mkdir($vchildimagedir,$vpermissions);
		// @mkdir($vchildcssdir,$vpermissions); @mkdir($vchildjsdir,$vpermissions); @mkdir($vchilddebugdir,$vpermissions);
		// ...the easier way
		// wp_mkdir_p($vchilddir); wp_mkdir_p($vchildimagedir); wp_mkdir_p($vchildcssdir);
		// wp_mkdir_p($vchildjsdir); wp_mkdir_p($vchilddebugdir);

		// the WP Filesystem way
		$wp_filesystem->mkdir($vchilddir);
		if (!is_dir($vchilddir)) {
			$vmessage = __('Aborted: Could not create Child Theme directory.','bioship').'<br>';
			$vmessage .= __('Check your permissions or do a','bioship').' <a href="http://bioship.space/documentation/" target=_blank>'.__('manual install','bioship').'</a>.';
			return $vmessage;
		}

		$wp_filesystem->mkdir($vchildimagedir);
		$wp_filesystem->mkdir($vchildcssdir);
		$wp_filesystem->mkdir($vchildjsdir);
		$wp_filesystem->mkdir($vchilddebugdir);
	}

	// Copy Child Theme files
	// ----------------------
	$vmissingfiles = array();
	foreach ($vchildfiles as $vchildfile) {

		// 1.8.5: change 'child-source' directory to 'child'
		// 1.9.0: no longer copy hooks.php by default, but copy .htaccess
		// 1.9.5: fix to new child theme file destination directories
		// exceptions: core-styles.css in css folder, .htaccess is in debug dir
		if ($vchildfile == 'core-styles.css') {$vchildsource = $vthemetemplatedir.'styles'.DIRSEP; $vchilddest = $vchildcssdir;}
		elseif ($vchildfile == '.htaccess') {$vchildsource = $vthemetemplatedir.'debug'.DIRSEP; $vchilddest = $vchilddebugdir;}
		else {$vchildsource = $vthemetemplatedir.'child'.DIRSEP; $vchilddest = $vchilddir;}
		// echo $vchildsource.$vchildfile.PHP_EOL; // debug point

		if (file_exists($vchildsource.$vchildfile)) {
			// 1.8.0: read files using WP Filesystem
			$vfilecontents = $wp_filesystem->get_contents($vchildsource.$vchildfile);

			// replace the default Child Theme name with the New one in style.css
			if ($vchildfile == 'style.css') {
				$vfilecontents = str_replace('Theme Name: BioShip Child', 'Theme Name: '.$vnewchildname, $vfilecontents);
				// 1.9.5: match the child theme version to the parent version on creation
				$vfilecontents = str_replace('1.0.0', THEMEVERSION, $vfilecontents);
			}

			// 1.8.0: write the file using WP_Filesystem
			$wp_filesystem->put_contents($vchilddest.$vchildfile, $vfilecontents, FS_CHMOD_FILE);

		}
		else {$vmissingfiles[] = $vchildfile;}
	}

	// 1.8.5: change 'child-source' directory to 'child'
	// 2.0.7: fix to changed variable typo (vmissing)
	if (count($vmissingfiles) > 0) {
		$vmessage .= __('Error: Child Theme source files missing','bioship').':<br>';
		foreach ($vmissingfiles as $vmissingfile) {
			// 1.9.5: display correct paths for .htaccess and core-styles.css here too
			if ($vmissingfile == '.htaccess') {$vmessage .= '/bioship/debug/'.$vmissingfile.'<br>';}
			elseif ($vmissingfile == 'core-styles.css') {$vmessage .= '/bioship/styles/'.$vmissingfile.'<br>';}
			else {$vmessage .= '/bioship/child/'.$vmissingfile.'<br>';}
		}
	}

	// Copy existing Parent Theme options to new Child Theme
	// -----------------------------------------------------
	$vsettingsmessage = '';

	// 1.8.0: do check for Titan Framework
	// 1.9.5: fix to parent settings framework logic
	if (THEMEOPT) {$vparentsettings = get_option('bioship'); $vchildoptionsslug = str_replace('-', '_', $vnewchildslug);}
	else {$vparentsettings = get_option('bioship_options'); $vchildoptionsslug = $vnewchildslug.'_options';}

	$vexistingsettings = get_option($vchildoptionsslug);
	if (!$vexistingsettings) {
		delete_option($vchildoptionsslug);
		if ( (!$vparentsettings) || ($vparentsettings == '') ) {
			// 1.9.5: set to default theme options
			$vdefaultsettings = bioship_titan_theme_options(array());
			add_option($vchildoptionsslug,$vdefaultsettings);
			$vsettingsmessage .= __('No Parent Theme settings! Child Theme set to default settings.','bioship').'<br>';
			$vsettingsmessage .= __('See documentation to manually transfer settings between themes.','bioship').'<br>';
		}
		else {
			add_option($vchildoptionsslug,$vparentsettings);
			$vsettingsmessage .= __('Parent Theme settings transferred to new Child Theme.','bioship').'<br>';
		}
	} else {$vmessage .= __('Child Theme settings exist, Parent Theme settings not transferred.','bioship').'<br>';}

	// 1.5.0: copy parent widgets and menus to child theme 'backups'
	// in preparation for activation, including menu locations
	$vsidebarswidgets = get_option('sidebars_widgets');
	$vnavmenus = get_option('nav_menu_options');
	$vmenulocations = get_theme_mod('nav_menu_locations');
	if (!get_option($vchildoptionsslug.'_widgets_backup')) {
		delete_option($vchildoptionsslug.'_widgets_backup');
		add_option($vchildoptionsslug.'_widgets_backup', $vsidebarswidgets);
	}
	if (!get_option($vchildoptionsslug.'_menus_backup')) {
		delete_option($vchildoptionsslug.'_menus_backup');
		add_option($vchildoptionsslug.'_menus_backup', $vnavmenus);
	}
	if (!get_option($vchildoptionsslug.'_menu_locations_backup')) {
		delete_option($vchildoptionsslug.'_menu_locations_backup');
		add_option($vchildoptionsslug.'_menu_locations_backup', $vmenulocations);
	}

	// Set Child Creation Output Message
	// ---------------------------------
	// 1.8.0: added translation strings to messages
	if ($vmessage != '') {$vcreationresult = '('.__('with errors','bioship').')';}
	else {$vcreationresult = __('successfully','bioship').'<!-- SUCCESS -->';}

	$vmessage .= $vsettingsmessage;
	$vmessage .= __('New Child Theme','bioship').' "'.$vnewchildname.'" '.__('created','bioship').' '.$vcreationresult.'.<br>';
	$vmessage .= __('Base Directory','bioship').': '.ABSPATH.'<br>';
	$vmessage .= __('Theme Subdirectory','bioship').': '.str_replace(ABSPATH, '', $vchilddir).'<br>';
	$vmessage .= __('Activate it on your','bioship').' <a href="'.admin_url('themes.php').'">'.__('Themes Page','bioship').'</a>.';

	// One-Click Activation for New Child Theme
	// ----------------------------------------
	$vwpnonce = wp_create_nonce('switch-theme_'.$vnewchildslug);
	$vactivatelink = 'themes.php?action=activate&stylesheet='.$vnewchildslug.'&_wpnonce='.$vwpnonce;
	$vmessage .= '... '.__('or just','bioship').' <a href="'.$vactivatelink.'">'.__('click here to activate it','bioship').'</a>.';
	if (function_exists('themedrive_determine_theme')) {
		// 1.8.0: link for Titan or Options Framework
		// CHECKME:
		if (THEMETITAN) {$vchildthemeoptions = 'admin.php?page=bioship_options&theme='.$vnewchildslug;}
		else {$vchildthemeoptions = 'themes.php?page=options-framework&theme='.$vnewchildslug;}
		$vmessage .= '<br>('.__('or','bioship').' <a href="'.$vchildthemeoptions.'">'.__('Theme Test Drive options without activating','bioship').'</a>.)';
	}

	return $vmessage;
 }
}

// Clone Child Theme
// -----------------
// 1.9.5: added child theme cloning function
if (!function_exists('bioship_admin_do_install_clone')) {
 function bioship_admin_do_install_clone() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $wp_filesystem;

	if (!isset($_REQUEST['clonetheme'])) {return __('Error: Source Child Theme not specified.','bioship');}
	elseif ($_REQUEST['clonetheme'] == '') {return __('Error: Source Child Theme not specified.','bioship');}
	else {$vclonetheme = $_REQUEST['clonetheme'];}

	if (THEMEOPT) {$vclonesettings = get_option($vclonetheme);}
	else {$vclonesettings = get_option($vclonetheme.'_options');}
	if (!$vclonesettings) {return __('Error: Source Child Theme Settings are empty!','bioship');}

	$vtheme = wp_get_theme(get_stylesheet($vclonetheme));
	if ( (!isset($vtheme['Template'])) || ($vtheme['Template'] != 'bioship') ) {
		return __('Cloning Aborted! Child Theme parent must be BioShip!','bioship');
	}

	$vnewclonename = $_REQUEST['newclonename'];
	if ($vnewclonename == '') {return __('Error: New Child Theme Name cannot be empty.','bioship');}
	if (!preg_match('/^[0-9a-z ]+$/i', $vnewclonename)) {return __('Error. Letters, numbers and spaces only please!','bioship');}
	$vnewcloneslug = preg_replace("/\W/", "-",strtolower($vnewclonename));
	if (get_option($vnewcloneslug)) {return __('Aborted! Theme Settings already exist for this name!','bioship');}

	$vthemesdir = get_theme_root().DIRSEP;
	$vchilddir = $vthemesdir.$vclonetheme.DIRSEP;
	$vclonedir = $vthemesdir.$vnewcloneslug.DIRSEP;

	if (!is_dir($vchilddir)) {return __('Aborted! Source Child Theme directory does not exist!','bioship');}
	if (is_dir($vclonedir)) {
		// Do NOT Continue! We must avoid overwriting an existing Child Theme!
		$vmessage = __('Aborted! Child Theme directory of that name already exists!','bioship').'<br>';
		$vmessage .= __('Remove or rename the existing directory and try again.','bioship').'<br>';
		return $vmessage;
	}

	// get and copy all the child theme files to the clone directory recursively...
	$vchildfiles = bioship_admin_get_directory_files($vchilddir,true);
	foreach ($vchildfiles as $vchildfile) {
		$vsourcefile = $vchilddir.$vchildfile; $vdestfile = $vclonedir.$vchildfile;
		echo "<!-- Copying: ".$vsourcefile." to ".$vdestfile." -->".PHP_EOL;
		if (!is_dir(dirname($vdestfile))) {$wp_filesystem->mkdir(dirname($vdestfile));}
		$vfilecontents = $wp_filesystem->get_contents($vsourcefile);
		if (substr($vsourcefile,-9,9) == 'style.css') {
			$vfilecontents = str_replace('Theme Name: '.THEMEDISPLAYNAME,'Theme Name: '.$vnewclonename,$vfilecontents);
		}
		$wp_filesystem->put_contents($vdestfile,$vfilecontents,FS_CHMOD_FILE);
	}

	// oh why not, copy any empty subdirectories too
	$vsubdirs = bioship_admin_get_directory_subdirs($vchilddir,true);
	echo "<!-- Creating Subdirs: "; print_r($vsubdirs); echo " -->";
	foreach ($vsubdirs as $vsubdir) {
		$vdestdir = $vclonedir.$vsubdir;
		if (!is_dir($vdestdir)) {$wp_filesystem->mkdir($vdestdir);}
	}

	// add a clone stamp file
	global $current_user; $current_user = wp_get_current_user();
	$vfilecontents = 'Cloned from existing Child Theme: '.THEMEDISPLAYNAME.' ('.$vclonetheme.')'.PHP_EOL;
	$vfilecontents .= 'on '.date('d/m/Y').' (timestamp: '.time().') by '.$current_user->user_login.PHP_EOL;
	$vfilecontents .= 'Serialized Settings at Clone time after this line ------'.PHP_EOL;
	if (is_serialized($vclonesettings)) {$vfilecontents .= $vclonesettings;} else {$vfilecontents .= serialize($vclonesettings);}
	$vdestfile = $vclonedir.'clonestamp.txt';
	$wp_filesystem->put_contents($vdestfile, $vfilecontents, FS_CHMOD_FILE);

	// copy child theme settings
	if (THEMEOPT) {$vnewclonekey = $vnewcloneslug;} else {$vnewclonekey = $vnewcloneslug.'_options';}
	delete_option($vnewclonekey); add_option($vnewclonekey, $vclonesettings);

	// copy all widget / sidebar settings (from active or backups)
	if ($vclonetheme == get_stylesheet()) {
		$vsidebarswidgets = get_option('sidebars_widgets');
		$vnavmenus = get_option('nav_menu_options');
		$vmenulocations = get_theme_mod('nav_menu_locations');
	} else {
		$vsidebarswidgets = get_option($vclonetheme.'_widgets_backup');
		$vnavmenus = get_option($vclonetheme.'_menus_backup');
		$vmenulocations = get_option($vclonetheme.'_menu_locations_backup');
	}

	// assume that if the new clone settings were empty / deleted
	// copying over these is safe / wanted as well
	delete_option($vnewcloneslug.'_widgets_backup');
	add_option($vnewcloneslug.'_widgets_backup',$vsidebarswidgets);
	delete_option($vnewcloneslug.'_menus_backup');
	add_option($vnewcloneslug.'_menus_backup',$vnavmenus);
	delete_option($vnewcloneslug.'_menu_locations_backup');
	add_option($vnewcloneslug.'_menu_locations_backup',$vmenulocations);

	// set Clone Creation Output Message
	$vmessage = __('New Child Theme','bioship').' "'.$vnewclonename.'" '.__('cloned successfully.','bioship').'<br>';
	$vmessage .= __('Base Directory','bioship').': '.ABSPATH.'<br>';
	$vmessage .= __('Theme Subdirectory','bioship').': '.str_replace(ABSPATH,'',$vclonedir).'<br>';
	$vmessage .= __('Activate it on your','bioship').' <a href="'.admin_url('themes.php').'">'.__('Themes Page','bioship').'</a>.';

	// One-Click Activation for New Cloned Theme
	$vwpnonce = wp_create_nonce('switch-theme_'.$vnewcloneslug);
	$vactivatelink = 'themes.php?action=activate&stylesheet='.$vnewcloneslug.'&_wpnonce='.$vwpnonce;
	$vmessage .= '... '.__('or just','bioship').' <a href="'.$vactivatelink.'">'.__('click here to activate it','bioship').'</a>.';
	if (function_exists('themedrive_determine_theme')) {
		if (THEMETITAN) {$vclonethemeoptions = 'admin.php?page=bioship-options&theme='.$vnewcloneslug;}
		else {$vclonethemeoptions = 'themes.php?page=options-framework&theme='.$vnewcloneslug;}
		$vmessage .= '<br>('.__('or','bioship').' <a href="'.$vclonethemeoptions.'">'.__('Theme Test Drive options without activating','bioship').'</a>.)';
	}
	$vmessage .= "<!-- SUCCESS -->"; // result indicator

	return $vmessage;

 }
}

// Check WP Filesystem Credentials
// -------------------------------
// 1.8.0: for Child Theme creation to pass Theme Check
// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/
// ref: http://www.webdesignerdepot.com/2012/08/wordpress-filesystem-api-the-right-way-to-operate-with-local-files/
if (!function_exists('bioship_admin_check_filesystem_credentials')) {
 function bioship_admin_check_filesystem_credentials($vurl, $vmethod, $vcontext, $vextrafields) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $wp_filesystem;
	if (empty($wp_filesystem)) {require_once(ABSPATH.'/wp-admin/includes/file.php'); WP_Filesystem();}
	$vcredentials = request_filesystem_credentials($vurl, $vmethod, false, $vcontext, $vextrafields);
	if ($vcredentials === false) {return false;}
	if (!WP_Filesystem($vcredentials)) {request_filesystem_credentials($vurl, $vmethod, true, $vcontext, $vextrafields); return false;}
	return true;
 }
}

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

// =========================
// Build Selective Resources
// =========================

// Trigger Build Selective CSS and JS
// ----------------------------------
// this method is for Options Framework
if ( (isset($_GET['page'])) && (isset($_GET['settings-updated'])) ) {
	if ( ($_GET['page'] == 'options-framework') && ($_GET['settings-updated'] == 'true') ) {
		add_action('admin_notices', 'bioship_admin_build_selective_resources');
	}
}
// 1.8.0: need to trigger differently for Titan save
if ( (isset($_GET['page'])) && (isset($_GET['message'])) ) {
	if ( ($_GET['page'] == $vthemename.'-options') && ($_GET['message'] == 'saved') ) {
		add_action('admin_notices', 'bioship_admin_build_selective_resources');
	}
}
// 1.8.0: ...also need to trigger this after a Customizer save...
add_action('customize_save_after', 'bioship_admin_build_selective_resources');


// Build Selective CSS and JS
// --------------------------
if (!function_exists('bioship_admin_build_selective_resources')) {
 function bioship_admin_build_selective_resources() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemename, $vthemesettings, $vthemedirs;

	// Maybe Combine CSS Core On Save
	// ------------------------------
	if ($vthemesettings['combinecsscore']) {

		// reset.css or normalize.css
		$vresetoption = $vthemesettings['cssreset'];
		if ($vresetoption == 'normalize') {
			$vcssfile = bioship_file_hierarchy('file', 'normalize.css', $vthemedirs['css']);
			if ($vcssfile) {$vcssreset = bioship_file_get_contents($vcssfile);}
			else {echo "<b>".__('Warning','bioship')."</b>: ".__('CSS Combine could not find','bioship')." <i>normalize.css</i><br>";}
		}
		if ($vresetoption == 'reset') {
			$vcssfile = bioship_file_hierarchy('file', 'reset.css', $vthemedirs['css']);
			if ($vcssfile) {$vcssreset = bioship_file_get_contents($vcssfile);}
			else {echo "<b>".__('Warning','bioship')."</b>: ".__('CSS Combine could not find','bioship')." <i>reset.css</i><br>";}
		}

		// formalize.css
		if ($vthemesettings['loadformalize']) {
			$vcssfile = bioship_file_hierarchy('file', 'formalize.css', $vthemedirs['css']);
			if ($vcssfile) {$vformalize = bioship_file_get_contents($vcssfile);}
			else {echo "<b>".__('Warning','bioship')."</b>: ".__('CSS Combine could not find','bioship')." <i>formalize.css</i><br>";}
		}

		// mobile.css (previously misnamed layout.css)
		$vcssfile = bioship_file_hierarchy('file', 'mobile.css', $vthemedirs['css']);
		if ($vcssfile) {$vlayout = bioship_file_get_contents($vcssfile);}
		else {echo "<b>".__('Warning','bioship')."</b>: ".__('CSS Combine could not find','bioship')." <i>mobile.css</i><br>";}

		// 1.5.0: these stylesheets are deprecated by grid.php
		// skeleton-960.css, skeleton-1120.css, skeleton-1200.css

		// skeleton.css (note: this must be *last*, or CSS breaks!?)
		$vcssfile = bioship_file_hierarchy('file', 'skeleton.css', $vthemedirs['css']);
		if ($vcssfile) {$vskeleton = bioship_file_get_contents($vcssfile);}
		else {echo "<b>".__('Warning','bioship')."</b>: ".__('CSS Combine could not find','bioship')." <i>skeleton.css</i><br>";}

		// ...style.css (intentionally not added as breaks stylesheet!)
		// ...custom.css (intentionally kept separate here also)

		$vcsscontents = $vcssreset.PHP_EOL.PHP_EOL.$vformalize.PHP_EOL.PHP_EOL;
		$vcsscontents .= $vlayoutwidth.PHP_EOL.PHP_EOL.$vlayout.PHP_EOL.PHP_EOL;
		$vcsscontents .= $vskeleton.PHP_EOL.PHP_EOL.$vstyle.PHP_EOL.PHP_EOL;
		$vdatetime = date('H:i:s d/m/Y');
		$vcsscontents .= '/* Last Updated: '.$vdatetime.' */';

		// note: combined core CSS file written directly...
		// (no need for WP_Filesystem as the file already exists, but used anyway to pass Theme Check)
		// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/#comment-10820
		// 1.8.0: fix to use directory separator in file path
		$vcssfile = get_stylesheet_directory($vthemename).DIRSEP.'css'.DIRSEP.'core-styles.css';
		if (file_exists($vcssfile)) {bioship_write_to_file($vcssfile, $vcsscontents);}
	}

	// Build Selective Foundation Javascripts on Save
	// -----------------------------------------------
	// TODO: build selectives for Foundation 6, this currently only works for Foundation 5
	// $vfoundation = $vthemesettings['foundationversion'];
	$vfoundation = 'foundation5'; // currently available for Foundation 5 only

	if ($vthemesettings['loadfoundation'] == 'selective') {
		$vmessage = '';
		$vjsfile = bioship_file_hierarchy('file', 'foundation.js', array('javascripts','includes/'.$vfoundation.'/js/foundation'));
		$vfoundationjs = bioship_file_get_contents($vjsfile);
		$vselected = $vthemesettings['foundationselect'];

		foreach ($vselected as $vkey => $vvalue) {
			if ($vvalue == '1') {
				$vfilename = 'foundation.'.$vkey.'.js';
				$vfoundationsourcedir = 'includes/'.$vfoundation.'/js/foundation';
				$vjsfile = bioship_file_hierarchy('file', $vfilename, array($vfoundationsourcedir));
				if ($vjsfile) {
					$vjsdata = bioship_file_get_contents($vjsfile);
					// 2.0.7: doubly ensure new line consistency for Theme Check
					$vjsdata = str_replace("\r\n", "\n", $vjsdata);
					$vfoundationjs .= $vjsdata;
					// 1.5.5: fix, use specific matching EOL to pass Theme Check
					$vfoundationjs .= "\n"."\n";
				}
				else {$vmessage .= "<b>".__('Warning','bioship')."</b>: ".__('Foundation JS Combine could not find','bioship')." <i>".$vfilename."</i><br>";}
			}
		}
		// 1.8.0: added admin warning for missing resources
		if ($vmessage != '') {
			global $vadminmessages; $vadminmessages[] = $vmessage;
			bioship_admin_notices_enqueue();
		}

		if (strlen($vfoundation) > 0) {
			// 1.8.0: write to theme javascripts directory, not foundation/js and fix directory separator
			// also, this is written to template directory so as not to overwrite a child version
			// (as such it does not need to use WP Filesystem as the file already exists)
			// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/#comment-10820
			$vselectedjs = get_template_directory($vthemename).DIRSEP.'javascripts'.DIRSEP.'foundation.selected.js';
			bioship_write_to_file($vselectedjs, $vfoundationjs);
		}
	}

 }
}


// ===================
// === Theme Tools ===
// ===================

// Backup/Restore/Import/Export Theme Settings
// Note: No Tracers added to theme tools functions (seems pointless.)
// 1.9.5: changed usage of 'options' to 'settings'

// note: some possible manual querystring usage
// ?backup_theme_settings=yes or admin-ajax.php?action=backup_theme_settings
// ?restore_theme_settings=yes (manual usage deprecated - as requires nonce check)
// ?export_theme_settings=yes or admin-ajax.php?action=export_theme_settings
// ?import_theme_settings=yes (manual usage deprecated - as requires nonce check)
// ?revert_theme_settings=yes (revert to pre-import - manual deprecated requires nonce)

// -----------------
// Theme Tools Forms
// -----------------
// for backup / restore / export / import / revert
// note: AJAX action for backup / export, post refresh for restore / import / revert
if (!function_exists('bioship_admin_theme_tools_forms')) {
 function bioship_admin_theme_tools_forms() {

	global $vthemesettings, $vthemename;

	// Theme Tools Javascript
	// ----------------------
	$vconfirmrestore = __('Are you sure you want to Restore the Theme Settings Backup?','bioship');
	$vconfirmimport = __('Are you sure you want to Import these Theme Settings?','bioship');
	$vconfirmrevert = __('Are you sure you want to Revert to Theme Settings prior to Import?','bioship');

	$vadminajax = admin_url('admin-ajax.php');
	$vactionurl = add_query_arg('page', $_REQUEST['page'], admin_url('admin.php'));
	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
		$vactionurl = add_query_arg('theme', $_REQUEST['theme'], $vactionurl);
	}

	echo "<script>
	function confirmrestore() {var agree = '".$vconfirmrestore."'; if (confirm(agree)) {return true;} return false;}
	function confirmimport() {
		if (document.getElementById('textareaimport').checked == '1') {
			if (document.getElementById('importtextarea').value == '') {return false;} }
		var agree = '".$vconfirmimport."'; if (confirm(agree)) {return true;} return false;
	}
	function confirmrevert() {var agree = '".$vconfirmrevert."'; if (confirm(agree)) {return true;} return false;}
	function backupthemesettings() {document.getElementById('themetoolsframe').src = '".$vadminajax."?action=backup_theme_settings';}
	function exportthemesettings() {
		if (document.getElementById('exportjson').checked == '1') {exportformat = 'json';}
		if (document.getElementById('exportserial').checked == '1') {exportformat = 'ser';}
		/* if (document.getElementById('exportxml').checked == '1') {exportformat = 'xml';} */
		document.getElementById('themetoolsframe').src = '".$vadminajax."?action=export_theme_settings&format='+exportformat;
	}
	function switchexportformat(format) {
		if (format == 'json') {document.getElementById('exportjson').checked = '1';}
		if (format == 'xml') {document.getElementById('exportxml').checked = '1';}
	}
	function switchimportmethod(importmethod) {
		if (importmethod == 'fileupload') {
			document.getElementById('fileuploadimport').checked = '1';
			document.getElementById('importtextareas').style.display = 'none';
			document.getElementById('importfileselect').style.display = '';
		}
		if (importmethod == 'textarea') {
			document.getElementById('textareaimport').checked = '1';
			document.getElementById('importfileselect').style.display = 'none';
			document.getElementById('importtextareas').style.display = '';
		}
	}
	</script>";

	// Theme Tools Form Interface
	// --------------------------
	echo "<table><tr><td style='vertical-align:middle;'>";
		echo "<input type='button' class='button-primary' value='".__('Backup','bioship')."' onclick='backupthemesettings();'>";
	echo "</td><td width='75'></td><td style='vertical-align:middle;'>";
		echo "<form action='".$vactionurl."' method='post'><input type='hidden' name='restore_theme_settings' value='yes'>";
		wp_nonce_field('restore_theme_settings_'.$vthemename);
		echo "<input type='submit' class='button-primary' value='".__('Restore','bioship')."' onclick='return confirmrestore();'></form>";
	echo "</td><td width='75'></td><td style='vertical-align:middle;'>";
		echo "<span id='exportform-arrow'>&#9662;</span>";
		echo "<input type='button' class='button-secondary' value='".__('Export','bioship')."' onclick='togglethemebox(\"exportform\");'>";
	echo "</td><td width='75'></td><td style='vertical-align:middle;'>";
		echo "<span id='importform-arrow'>&#9662;</span>";
		echo "<input type='button' class='button-secondary' value='".__('Import','bioship')."' onclick='togglethemebox(\"importform\");'>";
	if (isset($vthemesettings['importtime'])) {
		if ($vthemesettings['importtime'] != '') {
			echo "</td><td width='75'></td><td>";
			echo "<form action='".$vactionurl."' target='themetoolsframe' method='post'><input type='hidden' name='revert_theme_settings' value='yes'>";
			wp_nonce_field('revert_theme_settings_'.$vthemename);
			echo "<input type='submit' value='Revert' onclick='return confirmrevert();'></form>";
		}
	}
	echo "</center></td></tr>";

	// Backup Form
	// -----------
	// TODO: multiple/regular auto-backup options? (unique backups only)
	// TODO: set maximum number of theme settings backups to keep (revisions?)

	// Restore Form
	// ------------
	// TODO: display multiple theme option backups?
	// TODO: view backup data / delete backup options

	// Export Form
	// -----------
	echo "<tr><td colspan='7' align='center'><div id='exportform-inside' style='display:none;'>";
		echo "<center><form><table><tr height='25'><td> </td></tr>";
		// wp_nonce_field('export_theme_settings_'.$vthemename);
		echo "<tr><td><b>".__('Export Format','bioship').":</b></td><td width='20'></td>";
		echo "<td width='80' align='right'><input type='radio' id='exportserial' name='exportformat' value='ser'> <b>Serial</b></td><td width='40'></td>";
		echo "<td width='80' align='right'><input type='radio' id='exportjson' name='exportformat' value='json'> <b>JSON</b></td><td width='40'></td>";
		// echo "<td width='80' align='right'><input type='radio' id='exportxml' name='exportformat' value='xml' checked> <b>XML</b></td><td width='40'></td>";
		echo "<td><input type='button' class='button-primary' value='".__('Export','bioship')."' onclick='exportthemesettings();'></td>";
		echo "</tr></table></form>";
	echo "</div></td></tr>";

	// Import Form
	// -----------
	echo "<tr><td colspan='7' align='center'><div id='importform-inside' style='display:none;'>";

		// start import form
		// 2.0.7: remove form target='themetoolsframe'
		echo "<form action='".$vactionurl."' enctype='multipart/form-data' method='post'>";
		echo "<input type='hidden' name='import_theme_settings' value='yes'>";
		wp_nonce_field('import_theme_settings_'.$vthemename);
		// for import debugging switch passthrough
		if (THEMEDEBUG) {echo "<input type='hidden' name='themedebug' value='2'>";}
		echo "<table><tr height='25'><td> </td></tr><tr>";

		// select import method
		echo "<td style='vertical-align:top; line-height:12px;'><b>".__('Import Method','bioship').":<b><br><br>";
		echo "<input type='radio' id='fileuploadimport' name='importmethod' value='fileupload' onchange='switchimportmethod(\"fileupload\")' checked> <a href='javascript:void(0);' onclick='switchimportmethod(\"fileupload\");' style='text-decoration:none;'>".__('File Upload','bioship')."</a><br><br>";
		echo "<input type='radio' id='textareaimport' name='importmethod' value='textarea' onchange='switchimportmethod(\"textarea\");'> <a href='javascript:void(0);' onclick='switchimportmethod(\"textarea\");' style='text-decoration:none;'>".__('Text Area','bioship')."</a></td>";
		echo "<td width='20'></td>";

		// textarea import fields
		echo "<td align='center' style='vertical-align:middle;'><div id='importtextareas' style='display:none;'>";
		echo "(".__('XML, JSON or Serialized','bioship')."<br>".__('are auto-recognized.','bioship').")<br>";
		echo "<textarea name='importtextarea' id='importtextarea' style='width:300px; height:80px;'></textarea>";
		echo "</div></td>";

		// file upload import field
		echo "<td align='center' style='vertical-align:middle;'><div id='importfileselect' style='width:300px;'>";
		echo __('Select Theme Options file to Import','bioship').":<br><br>";
		echo "<input type='file' name='importthemeoptions' size='30'></div></td>";

		// import submit button
		echo "</td><td width='20'></td>";
		echo "<td style='vertical-align:bottom;'><input type='submit' class='button-primary' value='".__('Import','bioship')."' onclick='return confirmimport();'></td></tr></table></form>";

	echo "</div></td></tr></table>";

	// Theme Tools Iframe
	// ------------------
	echo "<iframe style='display:none;' src='javascript:void(0);' name='themetoolsframe' id='themetoolsframe'></iframe>";
 }
}

// -------------------------------
// Backup / Restore Theme Settings
// -------------------------------
// Backup via URL querystring or Theme Tools UI
// Restore via Theme Tools UI (requires nonce)

// Backup Theme Options
// --------------------
if (!function_exists('bioship_admin_backup_theme_settings')) {
 function bioship_admin_backup_theme_settings() {
	$vcurrentsettings = maybe_unserialize(get_option(THEMEKEY));
	$vcurrentsettings['backuptime'] = time();
	$vbackupkey = THEMEKEY.'_user_backup';
	delete_option($vbackupkey); add_option($vbackupkey, $vcurrentsettings);
 }
}

// Backup Triggers
// ---------------
add_action('wp_ajax_backup_theme_settings', 'bioship_admin_do_backup_theme_settings');
if ( (isset($_REQUEST['backup_theme_settingss'])) && ($_REQUEST['backup_theme_setttings'] == 'yes') ) {
	// 2.0.7: merged repetitive trigger function and use add_action
	add_action('init', 'bioship_admin_do_backup_theme_settings');
}
if (!function_exists('bioship_admin_do_backup_theme_settings')) {
 function bioship_admin_do_backup_theme_settings() {
 	if (current_user_can('edit_theme_options')) {
		bioship_admin_backup_theme_settings();
		$vmessage = __('Current Theme Settings User Backup has been Created!','bioship');
		if (defined('DOING_AJAX') && DOING_AJAX) {
			echo "<script>alert('".$vmessage."');</script>"; exit;
		} else {
			global $vadminmessages; $vadminmessages[] = $vmessage;
			bioship_admin_notices_enqueue();
		}
	}
 }
}

// Restore Theme Settings
// ----------------------
if (!function_exists('bioship_admin_restore_theme_settings')) {
 function bioship_admin_restore_theme_settings() {
 	// 1.8.5: fix to incorrect restoretime application
 	$vcurrentsettings = maybe_unserialize(get_option(THEMEKEY));
	$vbackupkey = THEMEKEY.'_user_backup';
	$vbackupsettings = maybe_unserialize(get_option($vbackupkey));
	$vbackupsettings['restoretime'] = time();

	// switch not delete, so as to backs up 'current' options (if not empty)
	// 1.9.5: define constant to not trigger force update filter
	// 2.0.7: fix by deleting the force update transient and removing filter
	delete_transient('force_update_'.THEMEKEY);
	remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);
	delete_option(THEMEKEY); add_option(THEMEKEY, $vbackupsettings);

	if ( ($vcurrentsettings != '') && (is_array($vcurrentsettings)) ) {
		$vcurrentsettings['backuptime'] = time();
		delete_option($vbackupkey); add_option($vbackupkey, $vcurrentsettings);
	}
	// 1.9.5: update global to continue
	global $vthemesettings;	$vthemesettings = $vbackupsettings;
 }
}

// Restore Trigger
// ---------------
// 1.8.5: added nonce check
if ( (isset($_POST['restore_theme_settings'])) && ($_POST['restore_theme_settings'] == 'yes') ) {
	if (current_user_can('edit_theme_options')) {
		global $vthemename;
		check_admin_referer('restore_theme_settings_'.$vthemename);
		bioship_admin_restore_theme_settings();
		$vmessage = __('Theme Settings Backup Restored! (You can switch back by using this method again.)','bioship');
		global $vadminmessages; $vadminmessages[] = $vmessage;
		bioship_admin_notices_enqueue();
	}
}


// ----------------------------
// Export/Import Theme Settings
// ----------------------------
// 1.5.0: added export/import/revert triggers
// 1.8.0: changed prefix from muscle to bioship_admin, and restorepreimport to revert

// Export Action Triggers
// ----------------------
add_action('wp_ajax_export_theme_settings', 'bioship_admin_export_theme_settings');
if ( (isset($_REQUEST['export_theme_settings'])) && ($_REQUEST['export_theme_settings'] == 'yes') ) {
	add_action('init', 'bioship_admin_export_theme_settings');
}

// Import/Revert Action Triggers
// -----------------------------
if ( (isset($_POST['import_theme_settings'])) && ($_POST['import_theme_settings'] == 'yes') ) {
	add_action('init', 'bioship_admin_import_theme_settings');
}
if ( (isset($_POST['revert_theme_settings'])) && ($_POST['revert_theme_settings'] == 'yes') ) {
	add_action('init', 'bioship_admin_revert_theme_settings');
}

// Export Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_export_theme_options
// 2.0.7: added serialized format export option
if (!function_exists('bioship_admin_export_theme_settings')) {
 function bioship_admin_export_theme_settings() {
	if (!current_user_can('edit_theme_options')) {return;}

	global $vthemename, $vthemesettings, $vthemestyledir;
	// check_admin_referer('export_theme_settings_'.$vthemename);

	// add the export time to the array
	$vthemesettings['exporttime'] = time();
	// print_r($vthemesettings);

	$vformat = '';
	if (isset($_REQUEST['format'])) {$vformat = $_REQUEST['format'];}
	if ($vformat == '') {$vformat = 'json';}

	// set the filename
	$vdate = date('Y-m-d--H:i:s',time());
	$vfilename = $vthemename.'_options--'.$vdate.'.'.$vformat;

	if ($vformat == 'json') {
		// convert array to JSON data
		$vexport = json_encode($vthemesettings);
		$vcontenttype = 'text/json';
	} elseif ($vformat == 'ser') {
		// convert to serialized string
		$vexport = serialize($vthemesettings);
		$vcontenttype = 'text/plain';
	} elseif ($vformat == 'xml') {
		// create an XML document
		$vxml = new SimpleXMLElement('<themeoptions/>');
		bioship_admin_array_to_xml($vxml, $vthemesettings);
		$vexport = $vxml->asXML();
		$vcontenttype = 'text/xml';
		// print_r($vexport); exit;

		// also add line breaks to make it readable?
		// FIXME: this is *not* working any more :-(
		// $vdom = new DOMDocument();
		// $vdom->formatOutput = true;
		// $vdom->loadXML($vexport);
		// $vexport = $vdom->saveXML();
		// print_r($vexport); exit;

		// for export debugging
		// $vnewthemeoptions = bioship_admin_xml_to_array($vexportxml);
		// print_r($vnewthemeoptions);
		// $vdiff = array_diff($vnewthemeoptions,$vthemesettings);
		// print_r($vdiff);
	}

	// save generated export file
	$vexportfile = $vthemestyledir.'debug'.DIRSEP.$vfilename;
	bioship_write_to_file($vexportfile, $vexport);

	// output the XML (force download)
	header('Content-disposition: attachment; filename="'.$vfilename.'"');
	header('Content-type: '.$vcontenttype);
	echo $vexport; exit;
 }
}

// Import Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_import_theme_options
if (!function_exists('bioship_admin_import_theme_settings')) {
 function bioship_admin_import_theme_settings() {
	if (!current_user_can('edit_theme_options')) {return;}

	global $vthemename, $vthemesettings;
	check_admin_referer('import_theme_settings_'.$vthemename);
	bioship_admin_notices_enqueue();

	if ($_POST['importmethod'] == 'textarea') {
		// import from textarea
		$vimportdata = stripslashes(trim($_POST['importtextarea']));
		// if (THEMEDEBUG) {echo "<!--|||".$vimportdata."|||-->";}
		if ( (substr($vimportdata,0,1) == '<') && (substr($vimportdata,-1,1) == '>') ) {$vformat = 'xml';}
		elseif ( (substr($vimportdata,0,1) == '{') && (substr($vimportdata,-1,1) == '}') ) {$vformat = 'json';}
		elseif (is_serialized($vimportdata)) {$vformat = 'serial';}
		if (THEMEDEBUG) {echo "<!-- Import Data Type: ".$vformat." -->";}

		if ($vformat == 'json') {
			// JSON validator ref: http://stackoverflow.com/a/15198925/5240159
			// convert JSON to an array
			$vnewthemeoptions = json_decode($vimportdata, true);
		} elseif ($vformat == 'xml') {
			// convert the XML to an array
			$vnewthemeoptions = bioship_admin_xml_to_array($vimportdata);
		} elseif ($vformat == 'serial') {
			// 2.0.7: unserialize serialized data
			$vnewthemeoptions = unserialize($vimportdata);
		} else {
			// format not recognized error
			$vmessage = __('Failed: format not recognized. Please upload valid XML, JSON or Serialized data.','bioship');
		}
	}
	elseif ($_POST['importmethod'] == 'fileupload') {
		// import from file upload
		$vverifyupload = bioship_admin_verify_file_upload('importthemeoptions');
		if (is_wp_error($vverifyupload)) {
			$vmessage = __('Upload Error','bioship').": ".$vverifyupload->get_error_message."');</script>";
		} else {
			$vformat = strtolower($vverifyupload['type']);
			$vdata = $vverifyupload['data'];
			if (THEMEDEBUG) {echo "<!-- Uploaded File Type: ".$vformat." -->";}
			if ($vformat == 'json') {$vnewthemeoptions = json_decode($vdata, true);}
			elseif ($vformat == 'xml') {$vnewthemeoptions = bioship_admin_xml_to_array($vdata);}
			elseif (is_serialized($vdata)) {$vnewthemeoptions = unserialize($vdata);}
		}
	}

	if (THEMEDEBUG) {echo "<-- Uploaded Theme Options: "; print_r($vnewthemeoptions); echo " -->";}

	if ( ($vnewthemeoptions) && (is_array($vnewthemeoptions)) ) {

		// 2.0.7: fix by deleting the force update transient and removing filter
		delete_transient('force_update_'.THEMEKEY);
		remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

		// add the import time to the array
		$vnewthemeoptions['importedtime'] = time();

		// backup the existing theme options
		$vbackupkey = THEMEKEY.'_import_backup';
		delete_option($vbackupkey); add_option($vbackupkey, $vthemesettings);

		// 1.8.5: allow selective import, only override new values found
		$vchanged = false;
		foreach ($vnewthemeoptions as $voptionkey => $voptionvalue) {
			if ( (!is_array($voptionvalue)) || (!isset($vthemesettings[$voptionkey])) ) {
				$vthemesettings[$voptionkey] = $voptionvalue; $vchanged = true;
			} elseif (is_array($voptionvalue)) {
				foreach ($voptionvalue as $vsuboptionkey => $vsuboptionvalue) {
					$vthemesettings[$voptionkey][$vsuboptionkey] = $vsuboptionvalue;
					if (!$vchanged) {$vchanged = true;}
				}
			}
		}

		if ($vchanged) {
			// change to the newly imported theme options
			delete_option(THEMEKEY); add_option(THEMEKEY, $vthemesettings);
			$vmessage = __('Theme Settings have been Imported successfully!','bioship');
		} else {$vmessage = __('No changed Theme Settings detected in import!','bioship');}

	} else {$vmessage = __('Could not convert import data to Theme Settings array.','bioship');}

	// 1.9.5: add theme admin message
	global $vadminmessages; $vadminmessages[] = $vmessage;
	// echo "<script>alert('".$vmessage."');</script>"; exit;

	if (THEMEDEBUG) {
		echo "<!-- New Theme Options: "; print_r(get_option(THEMEKEY)); echo " -->";
		ob_start(); print_r($vnewthemeoptions); echo PHP_EOL; print_r($vthemesettings);
		$vdebugdata = ob_get_contents(); ob_end_clean();
		bioship_write_debug_file('file-upload-import.txt', $vdebugdata);
	}

 }
}

// Revert to pre-Import Backup
// ---------------------------
// 1.8.0: renamed from muscle_restore_preimport_theme_options
if (!function_exists('function bioship_admin_revert_theme_settings')) {
 function bioship_admin_revert_theme_settings() {
 	if (!current_user_can('edit_theme_options')) {return;}

	global $vthemename, $vthemesettings;
	check_admin_referer('revert_theme_settings_'.$vthemename);

	// switch the preimport backup and existing options
	$vbackupkey = THEMEKEY.'_import_backup';
	$vbackupoptions = get_option($vbackupkey);

	// 2.0.7: fix by deleting the force update transient and removing filter
	delete_transient('force_update_'.THEMEKEY);
	remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

	if ( (!empty($backupoptions)) && (is_array($vbackupoptions)) ) {
		delete_option(THEMEKEY); add_option(THEMEKEY, $vbackupoptions);
		delete_option($vbackupkey); add_option($vbackupkey, $vthemesettings);
		$vmessage = __('Pre-Import Theme Settings have been reverted.','bioship')."<br>";
		$vmessage .= __('(You can switch back to the Imported Settings by using this method again.)','bioship');
	} else {
		$vmessage = __('Revert Failed! Pre-Import Theme Settings are empty or corrupt!','bioship');
	}

	// 2.0.5: enqueue admin notice message
	global $vadminmessages; $vadminmessages[] = $vmessage;
	bioship_admin_notices_enqueue();
 }
}

// Array to XML Function (for Export)
// ----------------------------------
// ref: http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
// answer used: http://stackoverflow.com/a/19987539/5240159
if (!function_exists('bioship_admin_array_to_xml')) {
 function bioship_admin_array_to_xml(SimpleXMLElement $vobject, array $vdata) {
    foreach ($vdata as $vkey => $vvalue) {
        if (is_array($vvalue)) {
            $vnewobject = $vobject->addChild($vkey);
            bioship_admin_array_to_xml($vnewobject, $vvalue);
        }
        else {
        	// added: htmlspecialchars
            $vobject->addChild($vkey, htmlspecialchars($vvalue));
        }
    }
 }
}

// XML to Array Function (for Import)
// ----------------------------------
// ref: http://stackoverflow.com/questions/6578832/how-to-convert-xml-into-array-in-php
if (!function_exists('bioship_admin_xml_to_array')) {
 function bioship_admin_xml_to_array($vxml) {
	$vthemesettings = json_decode(json_encode((array)simplexml_load_string($vxml)),1);
	// do htmlspecialchars_decode 2 levels deep
	// 1.8.5: okay, make that 3 levels deep then
	foreach ($vthemesettings as $vkey => $vvalue) {
		if (!is_array($vvalue)) {
			$vthemesettings[$vkey] = htmlspecialchars_decode($vvalue);
		} elseif ($vvalue == array()) {
			// no non-set array values thanks
			$vthemesettings[$vkey] = '';
		} else {
			foreach ($vvalue as $vsubkey => $vsubvalue) {
				if (!is_array($vsubvalue)) {$vthemesettings[$vkey][$vsubkey] = htmlspecialchars_decode($vsubvalue);}
				elseif (vsubvalue == array()) {$vthemesettings[$vkey][$vsubkey] = '';}
				else {
					foreach ($vsubvalue as $vsubsubkey => $vsubsubvalue) {
						if (!is_array($vsubsubvalue)) {$vthemesettings[$vkey][$vsubkey][$vsubsubkey] = htmlspecialchars_decode($vsubsubvalue);}
						elseif ($vsubsubvalue == array()) {$vthemesettings[$vkey][$vsubkey][$vsubsubkey] = '';}
						// else {print_r($vsubsubvalue);} // debug point
					}
				}
			}
		}
	}
	return $vthemesettings;
 }
}

// Verify Uploaded File
// --------------------
// 1.8.5: added this upload check handler
// ref: http://php.net/manual/en/features.file-upload.php
if (!function_exists('bioship_admin_verify_file_upload')) {
 function bioship_admin_verify_file_upload($vinputkey) {
	try {

		// Undefined | Multiple Files | $_FILES Corruption Attack
		// If this request falls under any of them, treat it invalid.
		if ( (!isset($_FILES[$vinputkey]['error'])) || (is_array($_FILES[$vinputkey]['error'])) ) {
			throw new RuntimeException(__('Invalid parameters.','bioship'));
		}

		// Check $_FILES[$vinputkey]['error'] value.
		switch ($_FILES[$vinputkey]['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException(__('No file sent.','bioship'));
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException(__('Exceeded filesize limit.','bioship'));
			default:
				throw new RuntimeException(__('Unknown errors.','bioship'));
		}

		// You should also check filesize here.
		if ($_FILES[$vinputkey]['size'] > 1000000) {
			throw new RuntimeException(__('Exceeded filesize limit.','bioship'));
		} elseif ($_FILES[$vinputkey]['size'] === 0) {
			throw new RuntimeException(__('File is empty.','bioship'));
		}

		// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		// Check MIME Type by yourself.
		if (class_exists('finfo')) {
			// 2.0.8: fix for serialized extension validation
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $extension = array_search(
				$finfo->file($_FILES[$vinputkey]['tmp_name']),
				array('xml' => 'text/xml', 'json' => 'text/json', 'ser' => 'text/plain'),
				true
			)) {
				echo "<!-- "; print_r($finfo); echo " -->";
				// echo "<!-- ".$finfo->file[$_FILES[$vinputkey]['tmp_name'])." -->";
				throw new RuntimeException(__('Invalid file format.','bioship'));
			}
		} else {
			if (isset($_FILES[$vinputkey]['mime'])) {
				echo "<!-- File Mime Type: ".$_FILES[$vinputkey]['mime']." -->";
			}
			$vpathinfo = pathinfo($_FILES[$vinputkey]['name']);
			echo "<!-- File Path Info: "; print_r($vpathinfo); echo "-->";
			$vextension = $vpathinfo['extension'];
			$vvalid = array('json', 'xml', 'ser');
			if (!in_array($vextension, $vvalid)) {
				throw new RuntimeException(__('Invalid file extension.','bioship'));
			}
		}

		// You should name it uniquely.
		// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
		// On this example, obtain safe unique name from its binary data.
		// if (!move_uploaded_file(
		//	$_FILES[$vinputkey]['tmp_name'],
		//	sprintf('./uploads/%s.%s',
		//		sha1_file($_FILES[$vinputkey]['tmp_name']),
		//		$extension
		//	)
		// )) {
		// 	throw new RuntimeException(__('Failed to move uploaded file.','bioship'));
		// }

		$vfile['type'] = $vextension;
		$vfile['data'] = bioship_file_get_contents($_FILES[$vinputkey]['tmp_name']);
		return $vfile;

	} catch (RuntimeException $e) {
		$error = $e->getMessage();
		echo "<!-- ERROR: ".$error." -->";
		return new WP_Error('failed', $error);
	}
 }
}


// =================================
// === Activation / Deactivation ===
// =================================

// Save/Restore Widgets/Menus on Deactivation/Activation
// -----------------------------------------------------
// for Parent Theme as Child Theme has different code
// CHECKME: retest activation/deactivation functionality

if (!THEMECHILD) {

	$vsaverestorewidgets = bioship_apply_filters('skeleton_theme_widget_backups', true);

	if ($vsaverestorewidgets) {

		// Backup on Deactivation
		// ----------------------
		if (!function_exists('bioship_admin_theme_deactivation')) {
		 add_action('switch_theme', 'bioship_admin_theme_deactivation');
		 function bioship_admin_theme_deactivation($vnewthemename) {
			$vsidebarswidgets = get_option('sidebars_widgets');
			delete_option('bioship_widgets_backup');
			add_option('bioship_widgets_backup', $vsidebarswidgets);

			$vmenusettings = get_option('nav_menu_options');
			delete_option('bioship_menus_backup');
			add_option('bioship_menus_backup', $vmenusettings);

			// not needed: theme mods, as they are theme specific
			// (hmmmm just how the sidebars/menus should be!)
		 }
		}

		// Restore on Activation
		// ---------------------
		if (!function_exists('bioship_admin_theme_activation')) {
		 add_action('after_switch_theme', 'bioship_admin_theme_activation');
		 function bioship_admin_theme_activation() {

			$vsidebarswidgets = get_option('sidebars_widgets');
			$vmenusettings = get_option('nav_menu_options');
			$vbioshipwidgets = get_option('bioship_widgets_backup');
			$vbioshipmenus = get_option('bioship_menus_backup');
			// note: no need to restore theme mods as already theme specific

			// If there are backed up widgets/menus, restore them now
			// ..also be nice and backup the deactivated themes widgets/menus
			// (even though note these cannot be automatically restored)
			if ($vbioshipwidgets != '') {
				update_option('sidebars_widgets', $vbioshipwidgets);
				delete_option('old_theme_widgets_backup');
				// 2.0.8: fix to variable typo (vsidebarwidgets)
				add_option('old_theme_widgets_backup', $vsidebarswidgets);
			}
			if ($vbioshipmenus != '') {
				update_option('nav_menu_options', $vbioshipmenus);
				delete_option('old_theme_menus_backup');
				add_option('old_theme_menus_backup', $vmenusettings);
			}

			// Redirect to Theme Options page on activation
			// 2.0.5: redirect to welcome page section with admin_url
			global $pagenow;
			if ( (is_admin()) && (isset($_GET['activated'])) && ($pagenow == 'themes.php') ) {
				// 1.8.0: Titan Framework Conversion
				// 2.0.5: allow for no Titan or Options Framework
				if ( (!THEMETITAN) && (THEMEOPT) ) {wp_redirect(admin_url('themes.php').'?page=options-framework&welcome=true'); exit;}
				elseif ( (THEMETITAN) && (class_exists('TitanFramework')) ) {wp_redirect(admin_url('admin.php').'?page=bioship-options&welcome=true'); exit;}
				// 2.0.8: wp.org redirection of theme activation not allowed :-/
				// else {wp_redirect(admin_url('themes.php').'?page=theme-info&welcome=true'); exit;}
			}
		 }
		}
	}
}

if (THEMECHILD) {

	// Save/Restore Child Theme Widgets/Menus on Deactivation/Activation
	// -----------------------------------------------------------------
	// 1.8.0: moved here from child theme functions.php (cleaner)
	// (note: maintain function_exists wrappers for back compat)
	$vsaverestorechildwidgets = bioship_apply_filters('skeleton_childtheme_widget_backups', true);

	if ($vsaverestorechildwidgets) {

		// get Child Theme Slug
		// --------------------
		if (!function_exists('bioship_admin_get_child_theme_slug')) {
		 function bioship_admin_get_child_theme_slug() {
			$vthetheme = wp_get_theme();
			if ( (!THEMETITAN) && (THEMEOPT) ) {$vchildthemeslug = preg_replace("/\W/", "_", strtolower($vthetheme['Name']));}
			else {$vchildthemeslug = preg_replace("/\W/", "-", strtolower($vthetheme['Name']));}
			return $vchildthemeslug;
		 }
		}

		// Switch Theme Hook (on Deactivation)
		// -----------------------------------
		add_action('switch_theme', 'bioship_admin_child_theme_deactivation');
		if (!function_exists('bioship_admin_child_theme_deactivation')) {
		 function bioship_admin_child_theme_deactivation($vnewthemename) {

			// Backup Child Theme Widgets and Menus
			// ------------------------------------
			$vchildthemeslug = bioship_admin_get_child_theme_slug();
			$vsidebarswidgets = get_option('sidebars_widgets');
			delete_option($vchildthemeslug.'_widgets_backup');
			add_option($vchildthemeslug.'_widgets_backup', $vsidebarswidgets);
			$vmenusettings = get_option('nav_menu_options');
			delete_option($vchildthemeslug.'_menus_backup');
			add_option($vchildthemeslug.'_menus_backup', $vmenusettings);
		 }
		}

		// After Switch Theme Hook (on Activation)
		// ---------------------------------------
		add_action('after_switch_theme', 'bioship_admin_child_theme_activation');
		if (!function_exists('bioship_admin_child_theme_activation')) {
		 function bioship_admin_child_theme_activation() {

			// Restore Child Theme Widgets and Menus
			// -------------------------------------
			$vchildthemeslug = bioship_admin_get_child_theme_slug();
			$vbackupwidgets = get_option($vchildthemeslug.'_widgets_backup');
			$vbackupmenus = get_option($vchildthemeslug.'_menus_backup');
			if ($vbackupwidgets != '') {update_option('sidebars_widgets',$vbackupwidgets);}
			if ($vbackupmenus != '') {update_option('nav_menu_options',$vbackupmenus);}

			// Also transfer the menu locations from the backup
			$vmenulocations = get_theme_mod('nav_menu_locations');
			if (!is_array($vmenulocations)) {
				$vmenulocations = get_option($vchildthemeslug.'_menu_locations_backup');
				if (is_array($vmenulocations)) {set_theme_mod('nav_menu_locations',$vmenulocations);}
			}

			// Redirect to Theme Options page on theme activation
			// --------------------------------------------------
			// 1.8.0: redirects to theme options / info page
			// 2.0.5: redirect to theme options / customizer with admin_url
			global $pagenow;
			if ( (is_admin()) && (isset($_GET['activated'])) && ($pagenow == 'themes.php') ) {
				if ( (!THEMETITAN) && (THEMEOPT) ) {wp_redirect(admin_url('admin.php').'?page=options-framework'); exit;}
				elseif ( (THEMETITAN) && (class_exists('TitanFramework')) ) {wp_redirect(admin_url('admin.php').'?page=bioship-options'); exit;}
				else {wp_redirect(admin_url('customize.php')); exit;}
			}
		 }
		}
	}
}

// TODO: Fix/Deprecate? theme mods and menu locations transfer?
//	if (get_option('bioship_transfer_widgets_menus') == 'yes') {
//		$vtransferwidgets = get_option('bioship_widgets_backup');
//		update_option('sidebars_widgets', $vtransferwidgets);
//
//		$vtransfermenus = get_option('bioship_menus_backup');
//		update_option('nav_menu_options', $vtransfermenus);
//
//		$vthememods = get_option('bioship_mods_backup');
//		if (count($vthememods) > 0) {
//			foreach ($vthememods as $vthememod => $vvalue) {
//				set_theme_mod($vthememod,$vvalue);
//			}
//		}
//		// $vmenulocations = get_option('bioship_menu_locations_backup');
//		// set_theme_mod('nav_menu_locations', $vmenulocations);
//
//		update_option('bioship_transfer_widgets_menus','done');
//	}


// =============================
// === Editor Screen Metabox ===
// =============================
// (override value retrieval via muscle_get_display_overrides in muscle.php)

// Perpage Override Metabox
// ------------------------
// Meta Key Notes
// _displayoverrides (array) 	- header, footer, navigation, secondarynav,
// 								  sidebar, subsidebar, headerwidgets, footerwidgets,
//			 					  image, title, subtitle, metatop, metabottom, authorbio
// _filteroverrides (array) 	- wpautop, wptexturize, convertsmilies, convertchars
// _thumbnailsize (single key) 	- stores override
// _perpoststyles (single key) 	- stores style additions

// Add the Perpage Metabox
// -----------------------
// 1.8.0: renamed from muscle_add_metabox
// 2.0.5: move add_action inside for consistency
if (!function_exists('bioship_admin_add_theme_metabox')) {
 add_action('admin_init', 'bioship_admin_add_theme_metabox');
 function bioship_admin_add_theme_metabox() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	$vcpts = array('post', 'page');
	$vargs = array('public'=>true, '_builtin' => false);
	$vcptlist = get_post_types($vargs, 'names', 'and');
	$vcpts = array_merge($vcpts, $vcptlist);
	// 2.0.5: add filter for post types metabox
	$vcpts = bioship_apply_filters('admin_theme_metabox_post_types', $vcpts);
	foreach ($vcpts as $vcpt) {
		add_meta_box('theme_metabox', __('Theme Display Overrides','bioship'), 'bioship_admin_theme_metabox', $vcpt, 'side', 'high');
	}
 }
}

// PerPage Metabox Checkboxes
// --------------------------
// 1.8.0: renamed from muscle_theme_metabox
// 2.0.0: added missing translation wrappers
if (!function_exists('bioship_admin_theme_metabox')) {
 function bioship_admin_theme_metabox() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $post, $vthemesettings;
	$vpostid = $post->ID; $vposttype = $post->post_type;

	// setup available thumbnail sizes
	if ($vposttype == 'page') {$vthumbdisplay = __('Featured Image','bioship'); $vthumbdefault = $vthemesettings['pagethumbsize'];}
	else {$vthumbdisplay = __('Thumbnail','bioship'); $vthumbdefault = $vthemesettings['postthumbsize'];}

	$vthumbarray = array(
		'thumbnail' => __('Thumbnail','bioship').' ('.get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h').')',
		'medium' => __('Medium','bioship').' ('.get_option('medium_size_w').' x '.get_option('medium_size_h').')',
		'large' => __('Large','bioship').' ('.get_option('large_size_w').' x '.get_option('large_size_h').')',
		'full' => __('Full Size','bioship').' ('.__('original','bioship').')'
	);
	global $_wp_additional_image_sizes;
	$image_sizes = get_intermediate_image_sizes();
	$voldsizenames = array('squared150', 'squared250', 'video43', 'video169');
	foreach ($image_sizes as $size_name) {
		if ( ($size_name != 'thumbnail') && ($size_name != 'medium') && ($size_name != 'large') ) {
			// 1.9.8: fix to sporadic undefined index warning (huh? size names should match?)
			if (isset($_wp_additional_image_sizes[$size_name])) {
				// 2.0.5: no longer output old size names as options
				if (!in_array($size_name, $voldsizenames)) {
					$vthumbarray[$size_name] = $size_name.' ('.$_wp_additional_image_sizes[$size_name]['width'].' x '.$_wp_additional_image_sizes[$size_name]['height'].')';
				}
			}
		}
	}

	echo "<script language='javascript' type='text/javascript'>
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
		selectelement = document.getElementById('sidebartemplate');
		template = selectelement.options[selectelement.selectedIndex].value;
		if (template == 'custom') {document.getElementById('sidebarcustom').style.display = '';}
		else {document.getElementById('sidebarcustom').style.display = 'none';}
		selectelement = document.getElementById('subsidebartemplate');
		subtemplate = selectelement.options[selectelement.selectedIndex].value;
		if (subtemplate == 'custom') {document.getElementById('subsidebarcustom').style.display = '';}
		else {document.getElementById('subsidebarcustom').style.display = 'none';}
		if ( (template == 'custom') || (subtemplate == 'custom') ) {
			document.getElementById('customtemplatelabel').style.display = '';
		} else {document.getElementById('customtemplatelabel').style.display = 'none';}
	}</script>";

	// 1.9.5: changed _hide prefix to _display_ prefix for form option names

	// Button Tabs
	// -----------
	// 1.8.0: use separate tab value so only for metabox itself
	// 1.9.5: merge filters with content tab and add separate sidebar tab
	$vthemesettingstab = get_post_meta($vpostid, '_themeoptionstab', true);
	echo "<style>.themeoptionbutton {background-color:#E0E0EF; padding:5px; border-radius:5px;}</style>";
	echo "<center><table><tr>";
	echo "<td><div id='themelayoutbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'layout') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"layout\");' onclick='clickthemeoptions(\"layout\");'>".__('Layout','bioship')."</a></div></td>";
	echo "<td width='10'></td><td><div id='themesidebarbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'sidebar') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"sidebar\");' onclick='clickthemeoptions(\"sidebar\");'>".__('Sidebars','bioship')."</a></div></td>";
	echo "<td width='10'></td><td><div id='themecontentbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'content') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"content\");' onclick='clickthemeoptions(\"content\");'>".__('Content','bioship')."</a></div></td>";
	echo "<td width='10'></td><td><div id='themestylesbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'styles') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"styles\");' onclick='clickthemeoptions(\"styles\");'>".__('Styles','bioship')."</a></div></td>";
	echo "<td width='10'></td><td><div id='themefiltersbutton' class='themeoptionbutton'";
	// if ($vthemesettingstab == 'filters') {echo " style='background-color:#DDD;'";}
	// echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"filters\");' onclick='clickthemeoptions(\"filters\");'>".__('Filters','bioship')."</a></div></td>";
	echo "</tr></table>";

	// Layout Overrides
	// ----------------
	$vdisplay = bioship_muscle_get_display_overrides($vpostid);
	$voverride = bioship_muscle_get_templating_overrides($vpostid);
	$vremovefilters = bioship_muscle_get_content_filter_overrides($vpostid);
	echo "<!-- Display Overrides: "; print_r($vdisplay); echo " -->"; // debug point
	echo "<!-- Templating Overrides: "; print_r($voverride); echo " -->"; // debug point
	echo "<!-- Filter Overrides: "; print_r($vremovefilters); echo " -->"; // debug point

	echo "<div id='themelayout'";
	if ($vthemesettingstab != 'layout') {echo " style='display:none;'";}
	echo "><table cellpadding='0' cellspacing='0'>";
	echo "<tr><td colspan='3' align='center'><b>".__('Layout Display Overrides','bioship')."</b></td></tr>";
	// 1.8.5: added full width container option (no wrap margins)
	echo "<tr><td>".__('No Wrap Margins','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_wrapper' id='_display_wrapper' value='1'";
		if ($vdisplay['wrapper']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Hide Header','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_header' id='_display_header' value='1'";
		if ($vdisplay['header']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Hide Footer','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footer' id='_display_footer' value='1'";
		if ($vdisplay['footer']) {echo " checked";}  echo "></td></tr>";

	// TODO: general layout displays?
	// Header Logo / Title Text / Description / Extras
	// Footer Extras / Site Credits

	// 1.9.8: fix to headernav and footernav keys
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>".__('Navigation Display','bioship')."<b></td><td></td><td align='center'>".__('Hide','bioship')."</td></tr>";
	echo "<tr><td>".__('Main Nav Menu','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_navigation' id='_display_navigation' value='1'";
		if ($vdisplay['navigation']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Secondary Nav Menu','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_secondarynav' id='_display_secondarynav' value='1'";
		if ($vdisplay['secondarynav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Header Nav Menu','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_headernav' id='_display_headernav' value='1'";
		if ($vdisplay['headernav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Nav Menu','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footernav' id='_display_footernav' value='1'";
		if ($vdisplay['footernav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Breadcrumbs','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_breadcrumb' id='_display_breadcrumb' value='1'";
		if ($vdisplay['breadcrumb']) {echo " checked";}  echo "></td></td></tr>";
	echo "<tr><td>".__('Post/Page Navi','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_pagenavi' id='_display_pagenavi' value='1'";
		if ($vdisplay['pagenavi']) {echo " checked";}
	echo "></td></tr>";

	echo "</table></div>";

	// Sidebar Overrides
	// -----------------
	// 1.9.5: separate tab for sidebar overrides
	echo "<div id='themesidebar'";
	if ($vthemesettingstab != 'sidebar') {echo " style='display:none;'";}
	echo "><table cellpadding='0' cellspacing='0'>";

	$vsubsidebarcolumns = array(
		'' => __('Default','bioship'),
		'one' => ' '.__('1','bioship').' ', 'two' => ' '.__('2','bioship').' ',
		'three' => ' '.__('3','bioship').' ', 'four' => ' '.__('4','bioship').' ',
		'five' => ' '.__('5','bioship').' ', 'six' => ' '.__('6','bioship').' ',
		'seven' => ' '.__('7','bioship').' ', 'eight' => ' '.__('8','bioship').' ',
	);
	$vsidebarcolumns = array_merge($vsubsidebarcolumns,array(
		'nine'	=> ' '.__('9','bioship').' ', 'ten' => __('10','bioship').' ',
		'eleven' => __('11','bioship').' ', 'twelve' => __('12','bioship').' ',
	) );
	$vcontentcolumns = array_merge($vsidebarcolumns,array(
		'thirteen' => __('13','bioship').' ', 'fourteen' => __('14','bioship').' ',
		'fifteen' => __('15','bioship').' ', 'sixteen' => __('16','bioship').' ',
		'seventeen' => __('17','bioship').' ', 'eighteen' => __('18','bioship').' ',
		'nineteen' => __('19','bioship').' ', 'twenty' => __('20','bioship').' ',
		'twentyone' => __('21','bioship').' ', 'twentytwo' => __('22','bioship').' ',
		'twentythree' => __('23','bioship').' ', 'twentyfour' => __('24','bioship').' '
	) );
	echo "<tr><td colspan='5' align='center'><table><tr><td>".__('Content Columns','bioship')."</td><td width='10'></td>";
	echo "<td><select name='_contentcolumns' id='_contentcolumns'>";
		foreach ($vcontentcolumns as $vwidth => $vlabel) {echo "<option value='".$vwidth."'";
		if ($voverride['contentcolumns'] == $vwidth) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td></tr></table></td></tr>";
	echo "<tr height='10'><td> </td></tr>";

	echo "<tr><td></td><td></td><td align='center'><b>".__('Sidebar','bioship')."</b></td>";
	echo "<td></td><td align='center'><b>".__('SubSidebar','bioship')."</b></td></tr>";
	echo "<tr><td align='right'>".__('Columns','bioship')."</td><td width='5'></td>";
	echo "<td><select name='_sidebarcolumns' id='_sidebarcolumns' style='width:100%;font-size:9pt;'>";
		foreach ($vsidebarcolumns as $vwidth => $vlabel) {echo "<option value='".$vwidth."'";
		if ($voverride['sidebarcolumns'] == $vwidth) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td><td width='5'></td>";
	echo "<td><select name='_subsidebarcolumns' id='_subsidebarcolumns' style='width:100%;font-size:9pt;'>";
		foreach ($vsubsidebarcolumns as $vwidth => $vlabel) {echo "<option value='".$vwidth."'";
		if ($voverride['subsidebarcolumns'] == $vwidth) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td></tr>";

	// Sidebar Templates
	$vsidebartemplates = array( '' => __('Default','bioship'), 'off' => __('None','bioship'), 'blank' => __('Blank','bioship'), 'primary' => __('Primary','bioship') );
	$vsubsidebartemplates = array( '' => __('Default','bioship'), 'off' => __('None','bioship'), 'subblank' => __('Blank','bioship'), 'subsidiary' => __('Subsidiary','bioship') );
	$vtemplates = array( 'page' => __('Page','bioship'), 'post' => __('Post','bioship'),
		'front' => __('Front','bioship'), 'home' => __('Home','bioship'), 'archive' => __('Archive','bioship'),
		'category' => __('Category','bioship'), 'taxonomy' => __('Taxonomy','bioship'),
		'tag' => __('Tag','bioship'), 'author' => __('Author','bioship'), 'date' => __('Date','bioship'),
		'search' => __('Search','bioship'), 'notfound' => __('NotFound','bioship') );
	$vsidebartemplates = array_merge($vsidebartemplates,$vtemplates);
	foreach ($vtemplates as $vvalue => $vlabel) {$vsubsidebartemplates['sub'.$vvalue] = $vlabel;}
	$vsidebartemplates['custom'] = 'CUSTOM'; $vsubsidebartemplates['custom'] = 'CUSTOM';

	echo "<tr><td style='vertical=align:top;' align='right'>Template<br>";
	echo "<div id='customtemplatelabel' style='margin-top:10px;";
		if ( ($voverride['sidebartemplate'] != 'custom') && ($voverride['subsidebartemplate'] != 'custom') ) {echo "display:none;";}
	echo "'>Slug:</div></td><td width='5'></td>";
	echo "<td style='vertical-align:top;'><select id='sidebartemplate' name='_sidebartemplate' id='_sidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
		foreach ($vsidebartemplates as $vtemplate => $vlabel) {echo "<option value='".$vtemplate."'";
		if ($voverride['sidebartemplate'] == $vtemplate) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select><br><div id='sidebarcustom'";
		if ($voverride['sidebartemplate'] != 'custom') {echo " style='display:none;'";}
	echo "><input type='text' name='_sidebarcustom' style='width:100%;font-size:9pt;' value='".$voverride['sidebarcustom']."'></div>";
	echo "</td><td width='5'></td>";
	echo "<td style='vertical-align:top;'><select id='subsidebartemplate' name='_subsidebartemplate' id='_subsidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
		foreach ($vsubsidebartemplates as $vtemplate => $vlabel) {echo "<option value='".$vtemplate."'";
		if ($voverride['subsidebartemplate'] == $vtemplate) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select><br><div id='subsidebarcustom'";
		if ($voverride['subsidebartemplate'] != 'custom') {echo " style='display:none;'";}
	echo "><input type='text' name='_subsidebarcustom' style='width:100%;font-size:9pt;' value='".$voverride['subsidebarcustom']."'></div>";
	echo "</td></tr>";

	// Sidebar Position
	$vsidebarpositions = array( '' => __('Default','bioship'), 'left' => __('Left','bioship'), 'right' => __('Right','bioship') );
	$vsubsidebarpositions = array( '' => __('Default','bioship'), 'opposite' => __('Opposite','bioship'),
		'internal' => __('Internal','bioship'), 'external' => __('External','bioship') );
	echo "<tr><td align='right'>Position</td><td width='5'></td>";
	echo "<td><select name='_sidebarposition' id='_sidebarposition' style='width:100%;font-size:9pt;'>";
		foreach ($vsidebarpositions as $vposition => $vlabel) {echo "<option value='".$vposition."'";
		if ($voverride['sidebarposition'] == $vposition) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td><td width='5'></td>";
	echo "<td><select name='_subsidebarposition' style='width:100%;font-size:9pt;'>";
		foreach ($vsubsidebarpositions as $vposition => $vlabel) {echo "<option value='".$vposition."'";
		if ($voverride['subsidebarposition'] == $vposition) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td></tr>";
	echo "</table>";

	echo "<table cellpadding='0' cellspacing='0'><tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>".__('Sidebar Display','bioship')."</b></td><td></td><td align='center'>".__('Hide','bioship')."</td></tr>";
	echo "<tr><td>".__('Main Sidebar','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_sidebar' id='_display_sidebar' value='1'";
		if ($vdisplay['sidebar']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('SubSidebar','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_subsidebar' id='_display_subsidebar' value='1'";
		if ($vdisplay['subsidebar']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Header Widgets','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_headerwidgets' id='_display_headerwidgets' value='1'";
		if ($vdisplay['headerwidgets']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Widgets','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footerwidgets' id='_display_footerwidgets' value='1'";
		if ($vdisplay['footerwidgets']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Area','bioship')." 1</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footer1' id='_display_footer1' value='1'";
		if ($vdisplay['footer1']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Area','bioship')." 2</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footer2' id='_display_footer2' value='1'";
		if ($vdisplay['footer2']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Area','bioship')." 3</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footer3' id='_display_footer3' value='1'";
		if ($vdisplay['footer3']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>".__('Footer Area','bioship')." 4</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_footer4' id='display_footer4' value='1'";
		if ($vdisplay['footer4']) {echo " checked";}  echo "></td></tr>";
	echo "</table></div>";

	// Content Overrides
	// -----------------
	// 1.8.0: keep individual meta key for this
	$vthumbnailsize = get_post_meta($vpostid, '_thumbnailsize', true);
	// 2.0.5: convert old size names to prefixed ones and update
	$vnewthumbsize = false;
	if ($vthumbnailsize == 'squared150') {$vnewthumbsize = 'bioship-150s';}
	elseif ($vthumbnailsize == 'squared250') {$vnewthumbsize = 'bioship-250s';}
	elseif ($vthumbnailsize == 'video43') {$vnewthumbsize = 'bioship-4-3';}
	elseif ($vthumbnailsize == 'video169') {$vnewthumbsize = 'bioship-16-9';}
	elseif ($vthumbnailsize == 'opengraph') {$vnewthumbsize = 'bioship-opengraph';}
	if ($vnewthumbsize) {
		update_post_meta($vpostid, '_thumbnailsize', $vnewthumbsize);
		$vthumbnailsize = $vnewthumbsize;
	}

	echo "<div id='themecontent'";
	if ($vthemesettingstab != 'content') {echo " style='display:none;'";}
	echo "><table cellpadding='0' cellspacing='0'>";

	// Thumbnail Size Override
	// 2.0.7: fix to text domin typo (bioship.)
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td colspan='3' align='center'><b>".$vthumbdisplay." ".__('Size','bioship')."</b> (".__('default','bioship')." ".$vthumbdefault.")<br>";
	echo "<select name='_thumbnailsize' id='_thumbnailsize' style='font-size:9pt;'>";
	echo "<option value=''";
	if ($vthumbnailsize == '') {echo " selected='selected'";}
	echo ">".__('Theme Settings Default','bioship')."</option>";
	echo "<option value=''";
	if ($vthumbnailsize == 'off') {echo " selected='selected'";}
	echo ">".__('No Thumbail Output','bioship')."</option>";
	foreach ($vthumbarray as $vkey => $vvalue) {
		echo "<option value='".$vkey."'";
		if ($vthumbnailsize == $vkey) {echo " selected='selected'";}
		echo ">".$vvalue."</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><td>".__('Hide','bioship')." ".$vthumbdisplay."</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_image' value='1'";
		if ($vdisplay['image']) {echo " checked";}
	echo "></td></tr>";

	// Content Overrides
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>".__('Content Display','bioship')."</b></td><td width='10'></td>";
	echo "<td align='center'>".__('Hide','bioship')."</td>";
	echo "<tr><td>".__('Title','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_title' id='_display_title' value='1'";
		if ($vdisplay['title']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>".__('Subtitle','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_subtitle' id='_display_subtitle' value='1'";
		if ($vdisplay['subtitle']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>".__('Top Meta','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_metatop' id='_display_metatop' value='1'";
		if ($vdisplay['metatop']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>".__('Bottom Meta','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_metabottom' id='_display_metabottom' value='1'";
		if ($vdisplay['metabottom']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>".__('Author Bio','bioship')."</td><td width='10'></td>";
	echo "<td align='center'><input type='checkbox' name='_display_authorbio' id='_display_authorbio' value='1'";
		if ($vdisplay['authorbio']) {echo " checked";}
	echo "></td></tr>";

	// Filter Overrides
	// 1.9.5: merge to content from separate filters tab
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>".__('Content Filter','bioship')."</b></td><td></td><td align='center'>".__('Disable','bioship')."</td></tr>";
	echo "<tr><td>wpautop</td><td width='10'></td><td align='center'><input type='checkbox' name='_wpautop' id='_wpautop' value='1'";
		if ( (isset($vremovefilters['wpautop'])) && ($vremovefilters['wpautop'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>wptexturize</td><td width='10'></td><td align='center'><input type='checkbox' name='_wptexturize' id='_wptexturize' value='1'";
		if ( (isset($vremovefilters['wptexturize'])) && ($vremovefilters['wptexturize'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>convert_smilies</td><td width='10'></td><td align='center'><input type='checkbox' name='_convertsmilies' id='_convertsmilies' value='1'";
		if ( (isset($vremovefilters['convertsmilies'])) && ($vremovefilters['convertsmilies'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>convert_chars</td><td width='10'></td><td align='center'><input type='checkbox' name='_convertchars' id='_convertchars' value='1'";
		if ( (isset($vremovefilters['convertchars'])) && ($vremovefilters['convertchars'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "</table></div>";

	// Style Overrides
	// ---------------
	// 1.8.0: javascript to expand/collapse style box
	echo "<script>function expandpostcss() {
		document.getElementById('expandpostcss').style.display = 'none';
		document.getElementById('collapsepostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '600px';
		document.getElementById('perpoststyles').style.height = '300px';
		document.getElementById('perpoststylebox').style.width = '620px';
		document.getElementById('perpoststylebox').style.marginLeft = '-375px';
		document.getElementById('perpoststylebox').style.paddingLeft = '20px';
		document.getElementById('perpoststylebox').style.paddingTop = '20px';
		document.getElementById('perpoststylebox').style.paddingBottom = '15px';
		document.getElementById('perpoststylebox').style.borderLeft = '1px solid #CCC';
	}
	function collapsepostcss() {
		document.getElementById('collapsepostcss').style.display = 'none';
		document.getElementById('expandpostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '100%';
		document.getElementById('perpoststyles').style.height = '200px';
		document.getElementById('perpoststylebox').style.width = '100%';
		document.getElementById('perpoststylebox').style.marginLeft = '0px';
		document.getElementById('perpoststylebox').style.paddingLeft = '0px';
		document.getElementById('perpoststylebox').style.paddingTop = '0px';
		document.getElementById('perpoststylebox').style.paddingBottom = '0px';
		document.getElementById('perpoststylebox').style.borderLeft = '0';
	}</script>";

	echo "<style>#quicksavesaved {display:none; padding:3px 6px; max-width:80px; ";
	echo "font-size:10pt; color: #333; font-weight:bold; background-color: lightYellow; border: 1px solid #E6DB55;}</style>";

	// 1.8.0: keep individual meta key for this
	$vperpoststyles = get_post_meta($vpostid, '_perpoststyles', true);
	echo "<div id='themestyles'";
	if ($vthemesettingstab != 'styles') {echo " style='display:none;'";}
	echo "><table  cellpadding='0' cellspacing='0' style='width:100%;overflow:visible;'>";
	echo "<tr><td colspan='2' align='center'><b>".__('Post Specific CSS Style Rules','bioship')."</b></td></tr>";
	echo "<tr><td><div id='expandpostcss' style='float:left; margin-left:10px;'><a href='javascript:void(0);' onclick='expandpostcss();' style='text-decoration:none;'>&larr; ".__('Expand','bioship')."</a></div>";
	echo "<div id='collapsepostcss' style='float:right; margin-right:20px; display:none;'><a href='javascript:void(0);' onclick='collapsepostcss();' style='text-decoration:none;'>".__('Collapse','bioship')." &rarr;</a></div></tr>";
	echo "<tr><td colspan='2'><div id='perpoststylebox' style='background:#FFF;'>";
	echo "<textarea rows='5' cols'30' name='_perpoststyles' id='perpoststyles' style='width:100%;height:200px;'>";
	echo $vperpoststyles."</textarea></div></td></tr>";
	echo "<tr><td align='center'><div id='quicksavesaved'>".__('CSS Saved!','bioship')."</div></td>";
	echo "<td align='right'><input type='button' onclick='quicksavecss();' value='".__('QuickSave CSS','bioship')."' class='button-secondary'></td></tr>";
	echo "</table></div>";

	// theme options current tab saver
	$vthemesettingstab = get_post_meta($vpostid,'_themeoptionstab',true);
	echo "<input type='hidden' id='themeoptionstab' name='_themeoptionstab' value='".$vthemesettingstab."'>";
	echo "<input type='hidden' id='themetabclicked' name='_themetabclicked' value=''>";

	echo "</center>";

	// 1.9.5: add quicksave perpost CSS form to footer
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_css_form');
	// 2.0.0: add quicksave perpost settings form to footer (prototype)
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_settings_form');

 }
}

// Update Metabox Hook
// -------------------
add_action('publish_post', 'bioship_admin_update_metabox_options');
add_action('save_post', 'bioship_admin_update_metabox_options');

// Update Metabox Values
// ---------------------
// 1.8.0: renamed from muscle_update_metabox_options
if (!function_exists('bioship_admin_update_metabox_options')) {
 function bioship_admin_update_metabox_options() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// 1.9.8: return if post is empty
	global $post; if (!is_object($post)) {return;}
	$vpostid = $post->ID;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return $vpostid;}

	// 1.8.0: cleaner save logic here
	if ( (!current_user_can('edit_posts')) || (!current_user_can('edit_post',$vpostid)) ) {return $vpostid;}

	// 1.8.0: grouped display overrides to array
	// 1.8.5: added headernav, footernav, breadcrumbs, pagenavi
	$vdisplay = array(); $vpostdata = false;
	$vdisplaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);

	// 1.9.5: changed _hide prefix to _display_
	foreach ($vdisplaykeys as $vkey) {
		if (isset($_POST['_display_'.$vkey])) {
			if ($_POST['_display_'.$vkey] == '1') {$vdisplay[$vkey] = '1'; $vpostdata = true;}
			else {$vdisplay[$vkey] = '';}
		} else {$vdisplay[$vkey] = '';}
	}
	delete_post_meta($vpostid,'_displayoverrides');
	// 1.9.9: check and save if new post data
	if ($vpostdata) {add_post_meta($vpostid,'_displayoverrides',$vdisplay);}

	// 1.9.5: added override keys
	$voverride = array(); $vpostdata = false;
	$voverridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);
	foreach ($voverridekeys as $vkey) {
		if (isset($_POST['_'.$vkey])) {$voverride[$vkey] = $_POST['_'.$vkey]; $vpostdata = true;}
		else {$voverride[$vkey] = '';}
	}
	delete_post_meta($vpostid,'_templatingoverrides');
	// 1.9.9: check and save if new post data
	if ($vpostdata) {add_post_meta($vpostid,'_templatingoverrides',$voverride);}

	// 1.8.0: grouped filters to array
	// 2.0.0: better checkbox save logic
	$vremovefilters = array(); $vpostdata = false;
	$vfilters = array('wpautop', 'wptexturize', 'convertsmilies', 'convertchars');
	foreach ($vfilters as $vfilter) {
		if (isset($_POST['_'.$vfilter])) {
			if ($_POST['_'.$vfilter] == '1') {$vremovefilters[$vfilter] = '1'; $vpostdata = true;}
			else {$vremovefilters[$vfilter] = '';}
		} else {$vremovefilters[$vfilter] = '';}
	}
	delete_post_meta($vpostid,'_removefilters');
	// 1.9.9: check and save if new filters
	// 2.0.0: save if post data found
	if ($vpostdata) {add_post_meta($vpostid, '_removefilters', $vremovefilters, true);}

	// 1.8.0: save individual key values
	$voptionkeys = array('_perpoststyles', '_thumbnailsize', '_themeoptionstab');
	foreach ($voptionkeys as $voption) {
		// 1.9.9: make sure option value is set as metaxbox may be removed
		if (isset($_POST[$voption])) {
			$voptionvalue = $_POST[$voption]; $voptions[$voption] = $voptionvalue;
			if ($voption == '_perpoststyles') {$voptionvalue = stripslashes($voptionvalue);}
			delete_post_meta($vpostid, $voption);
			// 1.9.5: make cleaner, do not save empty values
			if ($voptionvalue != '') {add_post_meta($vpostid, $voption, $voptionvalue, true);}
		}
	}

	// for manually writing a post options debug file on save
	$vmetasavedebug = false; // $vmetasavedebug = true;
	if ($vmetasavedebug) {
		$vdebuginfo = "Override".PHP_EOL; foreach ($voverride as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Display".PHP_EOL; foreach ($vdisplay as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Filters".PHP_EOL; foreach ($vremovefilters as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Options".PHP_EOL; foreach ($voptions as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		bioship_write_debug_file('perpost-debug-'.$vpostid.'.txt', $vdebuginfo);
	}
 }
}

// QuickSave PerPost CSS Form
// --------------------------
// 1.9.5: added this CSS quicksave form
if (!function_exists('bioship_admin_quicksave_perpost_css_form')) {
 function bioship_admin_quicksave_perpost_css_form() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	echo "<script>function quicksavecss() {
		oldcss = document.getElementById('pageloadperpoststyles').value;
		newcss = document.getElementById('perpoststyles').value;
		if (oldcss == newcss) {return false;}
		document.getElementById('newperpoststyles').value = newcss;
		document.getElementById('quicksave-css-form').submit();
	}
	function quicksavedshow() {
		quicksaved = document.getElementById('quicksavesaved'); quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
	}</script>";

	global $post; $vpostid = $post->ID;
	// 2.0.8: use prefixed post meta key
	$vperpoststyles = get_post_meta($vpostid, '_'.THEMEPREFIX.'_perpost_styles', true);
	if (!$vperpoststyles) {
		// 2.0.8: maybe convert old post meta key
		$voldpostmeta = get_post_meta($vpostid, '_perpoststyles', true);
		if ($voldpostmeta) {
			$vperpoststyles = $voldpostmeta; delete_post_meta($vpostid, '_perpoststyles');
			update_post_meta($vpostid, '_'.THEMEPREFIX.'_perpost_styles');
		}
	}

	$vadminajax = admin_url('admin-ajax.php');
	echo "<form action='".$vadminajax."' method='post' id='quicksave-css-form' target='quicksave-css-frame'>";
	wp_nonce_field('quicksave_perpost_css_'.$vpostid);
	echo "<input type='hidden' name='action' value='quicksave_perpost_css'>";
	echo "<input type='hidden' name='postid' value='".$vpostid."'>";
	echo "<input type='hidden' name='pageloadperpoststyles' id='pageloadperpoststyles' value='".$vperpoststyles."'>";
	echo "<input type='hidden' name='newperpoststyles' id='newperpoststyles' value=''></form>";
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-css-frame' id='quicksave-css-frame'></iframe>";

 }
}

// QuickSave PerPost CSS
// ---------------------
// 1.9.5: added this CSS quicksave
add_action('wp_ajax_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css');
if (!function_exists('bioship_admin_quicksave_perpost_css')) {
 function bioship_admin_quicksave_perpost_css() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if ( (!isset($_POST['postid'])) || (!isset($_POST['newperpoststyles'])) ) {exit;}
	$vpostid = $_POST['postid']; if (!is_numeric($vpostid)) {exit;}
	$verror = false;

  	if ( (current_user_can('edit_posts')) && (current_user_can('edit_post',$vpostid)) ) {
  		// 2.0.0: use wp_verify_nonce instead of check_admin_referer for error message output
  		$vchecknonce = false;
  		if (isset($_REQUEST['_wp_nonce'])) {
  			$vnonce = $_REQUEST['_wp_nonce'];
  			$vchecknonce = wp_verify_nonce($vnonce, 'quicksave_perpost_css_'.$vpostid);
  		}
	  	if ($vchecknonce) {
		  	$vnewstyles = stripslashes($_POST['newperpoststyles']);
		  	$vupdatestyles = update_post_meta($vpostid, '_perpoststyles', $vnewstyles);
		} else {$verror = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}
	} else {$verror = __('Failed. Looks like you may need to login again!','bioship');}

	if ($verror) {echo "<script>alert('".$verror."');</script>";}
	else {echo "<script>parent.quicksavedshow();</script>";}
	exit;
 }
}

// QuickSave PerPost Settings Form
// -------------------------------
// 2.0.0: dummy form copy to save metabox overrides (prototype)
if (!function_exists('bioship_admin_quicksave_perpost_settings_form')) {
 function bioship_admin_quicksave_perpost_settings_form() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// metabox settings keys
	$vcheckboxkeys = array(
		'display_wrapper', 'display_header', 'display_footer', 'display_navigation', 'display_secondarynav', 'display_headernav', 'display_footernav',
		'display_sidebar', 'display_subsidebar', 'display_headerwidgets', 'display_footerwidgets', 'display_footer1', 'display_footer2', 'display_footer3', 'display_footer4',
		'display_image', 'display_breadcrumb', 'display_title', 'display_subtitle', 'display_metatop', 'display_metabottom', 'display_authorbio', 'display_pagenavi',
		'wpautop', 'wptexturize', 'convertsmilies', 'convertchars' // filter keys
	);
	$vselectkeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'thumbnailsize' // *
	);
	$vtextkeys = array('sidebarcustom', 'subsidebarcustom');

	$vi = 0; $vj = 0; $vk = 0;
	echo "<script>function quicksavethemesettings() {
		checkboxkeys = new Array(); selectkeys = new Array(); otherkeys = new Array();";
		foreach ($vcheckboxkeys as $vkey) {echo "checkboxkeys[".$vi."] = '".$vkey."'; "; $vi++;}
		foreach ($vselectkeys as $vkey) {echo "selectkeys[".$vj."] = '".$vkey."'; "; $vj++;}
		foreach ($vtextkeys as $vkey) {echo "textkeys[".$vk."] = '".$vkey."'; "; $vk++;}
		echo "
		for (i in checkboxkeys) {
			if (document.getElementById('_display_'+checkboxkeys[i]).checked) {
				document.getElementById('__display_'+checkboxkeys[i]).value = '1';
			} else {document.getElementById('_display_'+checkboxkeys[i]).value = '';}
		}
		for (i in selectkeys) {
			selectelement = document.getElementById('_'+selectkeys[i]);
			selectedvalue = selectelement.options[selectelement.selectedIndex].value;
			doceument.getElementById('__'+selectkeys[i]) = selectedvalue;
		}
		for (i in textkeys) {
			document.getElementById('__'+textkeys[i]).value = document.getElementById('_'+textkeys[i]).value;
		}
		document.getElementById('quicksave-settings-form').submit();
	}
	function quicksaveddisplay() {
		quicksaved = document.getElementById('quicksavesettingssaved'); quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
	}</script>";

	global $post; $vpostid = $post->ID;
	$vadminajax = admin_url('admin-ajax.php');
	echo "<form action='".$vadminajax."' method='post' id='quicksave-settings-form' target='quicksave-settings-frame'>";
	wp_nonce_field('quicksave_perpost_settings_'.$vpostid);
	echo "<input type='hidden' name='action' value='quicksave_perpost_settings'>";
	echo "<input type='hidden' name='postid' value='".$vpostid."'>";
	foreach ($vcheckboxkeys as $vkey) {echo "<input type='hidden' name='_".$vkey."' id='__".$vkey."' value=''>";}
	foreach ($vselectkeys as $vkey) {echo "<input type='hidden' name='_".$vkey."' id='__".$vkey."' value=''>";}
	foreach ($vtextkeys as $vkey) {echo "<input type='hidden' name='_".$vkey."' id='__".$vkey."' value=''>";}
	echo "</form>";
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-setting-frame' id='quicksave-settings-frame'></iframe>";
 }
}


// QuickSave PerPost Settings
// --------------------------
// 2.0.0: save theme overrides via AJAX trigger (prototype)
if (!function_exists('bioship_admin_update_metabox_settings')) {
 add_action('wp_ajax_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings');
 function bioship_admin_update_metabox_settings() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	if (!isset($_REQUEST['postid'])) {exit;}
 	$vpostid = $_REQUEST[$vpostid]; if (!is_numeric($vpostid)) {exit;}
 	$verror = false;

	if ( (current_user_can('edit_posts')) && (current_user_can('edit_post',$vpostid)) ) {
  		$vchecknonce = false;
  		if (isset($_REQUEST['_wp_nonce'])) {
  			$vnonce = $_REQUEST['_wp_nonce'];
  			$vchecknonce = wp_verify_nonce($vnonce, 'quicksave_perpost_settings_'.$vpostid);
  		}
	  	if ($vchecknonce) {
		 	global $post; $post = get_post($vpostid);
		 	bioship_admin_update_metabox_options();
		} else {$verror = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}
	} else {$verror = __('Failed. Looks like you may need to login again!','bioship');}

	if ($verror) {echo "<script>alert('".$verror."');</script>";}
	else {echo "<script>parent.quicksaveddisplay();</script>";}
	exit;
 }
}

// ===============
// === PLUGINS ===
// ===============

// To Install the Titan Framework Plugin
// -------------------------------------
// 1.8.5: moved here from customizer.php
// this method creates a standalone callable installation link for
// adding Titan Framework to the WordPress.Org version of theme
// (currently done via TGMPA using Titan Checker, rather than here)
if (isset($_REQUEST['admin_install_titan_framework'])) {
	if ($_REQUEST['admin_install_titan_framework'] == 'yes') {
		add_action('init', 'bioship_admin_install_titan_framework');
	}
}

// Note: Otto's Theme Plugin Dependency Class
// ref: http://ottopress.com/2012/themeplugin-dependencies/
if (!function_exists('bioship_admin_install_titan_framework')) {
 function bioship_admin_install_titan_framework() {
	if (current_user_can('install_plugins')) {
		// TODO: maybe extracting from a bundled zip could work here..?
		// eg: http://meta.wordpress.stackexchange.com/questions/4172/script-that-downloads-installs-and-activates-wordpress-plugins?cb=1

		// proceed with Titan Framework installation...
		$vtitanslug = 'titan-framework';
		$vinstallurl = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin='.$vtitanslug), 'install-plugin_'.$vtitanslug);
		wp_redirect($vinstallurl); exit;
	}
 }
}

// --------------------------
// Load TGM Plugin Activation
// --------------------------
if (!function_exists('tgmpa')) {
	$vtgmpa = bioship_file_hierarchy('file', 'class-tgm-plugin-activation.php', array('includes'));
	if ($vtgmpa) {require_once($vtgmpa);}
}

// ---------------------------
// === Recommended Plugins ===
// ---------------------------

// 'Required' Plugins
// ------------------
// - Titan Framework
// -- for WP.Org installs

// Recommended Plugins (Theme Supported)
// -------------------------------------
// - AJAX Load More
// - Open Graph Protocol Framework
// - Theme My Login
// ? Theme Test Drive
// - Widget Saver
// - WP PageNavi
// - WP Subtitle

// WordQuest Plugins
// -----------------
// (could handle these differently?)
// - AutoSave Net 	  (released)
// - Content Sidebars (released)
// - FreeStyler

// - PDF Replicator
// - PDF Shuttle
// - Visitor Vortex
// - WarpPress Builder
// - WP AutoMedic
// - WP BugBot
// - WP Email Images
// - WP Infinity Responder


if (function_exists('tgmpa')) {

	// TESTME: this is done separately via Titan Checker now, but this calls
	// TGMPA separately as well as a new instance - which may or may not be desirable?

	// 1.9.8: recommend Titan Framework plugin for Wordpress.org installs
	add_filter('tgm_plugins_array', 'bioship_admin_tgm_titan_framework_check');
	if (!function_exists('bioship_admin_tgm_titan_framework_check')) {
	 function bioship_admin_tgm_titan_framework_check($vplugins) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		$vthemeupdater = bioship_file_hierarchy('file', 'theme-update-checker.php', array('includes'));
		if (!$vthemeupdater) {
			$vplugins[] = array(
				'name' 		=> 'Titan Framework',
				'slug' 		=> 'titan-framework',
				'required' 	=> false
			);
		}
		return $vplugins;
	 }
	}

	// TGMPA seems to need at least WP 3.7...
	if (!version_compare($wp_version,'3.7','<')) { //'>

		// TGMPA Theme Options Page - Notice Display Workaround
		// to use the plugin recommendation notice on the theme options page
		// whether it has already been dismissed by the user or not :-)

		if (isset($_REQUEST['page'])) {
			// 1.8.0: allow for Titan Framework admin page URL
			if ( ($_REQUEST['page'] == 'options-framework')
			  || ($_REQUEST['page'] == $vthemename.'-options')
			  || ($_REQUEST['page'] == $vthemename.'_options') ) {
				// hook in before init as this is when TGM loads
				add_action('plugins_loaded', 'bioship_admin_tgm_notice_shift');
				// make the notice undismissable on theme page only via config filter
				add_filter('tgm_config_array', 'bioship_admin_tgm_dismiss_notice_off');
			}
		}

		if (!function_exists('bioship_admin_tgm_notice_shift')) {
		 function bioship_admin_tgm_notice_shift() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
			$vid = 'bioship-tgmpa'; // TGM instance id
			if (get_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid, true)) {
				add_user_meta(get_current_user_id(), 'tgmpa_temp_notice_'.$vid, 1);
				delete_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid);
				add_action('all_admin_notices', 'bioship_admin_tgm_notice_unshift', 100);
			}
		 }
		}

		if (!function_exists('bioship_admin_tgm_notice_unshift')) {
		 function bioship_admin_tgm_notice_unshift() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
			$vid = 'bioship-tgmpa'; // TGM instance id
			delete_user_meta(get_current_user_id(), 'tgmpa_temp_'.$vid);
			add_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid, 1);
		 }
		}

		if (!function_exists('bioship_admin_tgm_dismiss_notice_off')) {
		 function bioship_admin_tgm_dismiss_notice_off($vconfig) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			// filter the theme page message
			$vthememessage = '<h3>'.__('BioShip Theme Framework Recommended Plugins','bioship').'</h3><br>';
			$vthememessage = bioship_apply_filters('tgm_theme_page_message',$vthememessage);
			$vconfig['dismissable'] = false;
			$vconfig['dismiss_msg'] = $vthememessage;
			return $vconfig;
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

			// Note: Originally recommended by SPML Skeleton Theme:
			// Simple Shortcodes (smpl-shortcodes)

			$vplugins = array(

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

				// array(
				//	'name'      		=> 'Theme Test Drive',
				//	'slug'      		=> 'theme-test-drive',
				//	'required'  		=> false,
				//	'force_activation'  => true
				// ),

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
				// 1.9.8: remove as yet unreleased plugin
				// 2.0.5: re-add this released plugin
				array(
					'name'      		=> 'AutoSave Net',
					'slug'      		=> 'autosave-net',
					'required'  		=> false,
					'source'			=> 'http://wordquest.org/downloads/packages/autosave-net.zip',
					'external_url' 		=> 'http://wordquest.org/plugins/autosave-net/'
				),

				array(
					'name'      		=> 'Content Sidebars',
					'slug'      		=> 'content-sidebars',
					'required'  		=> false,
					'source'			=> 'http://wordquest.org/downloads/packages/content-sidebars.zip',
					'external_url' 		=> 'http://wordquest.org/plugins/content-sidebars/'
				),

			);

			// Filter the TGMPA plugins
			$vplugins = bioship_apply_filters('tgm_plugins_array', $vplugins);

			/*
			 * TGMPA: Array of configuration settings. Amend each line as needed.
			 *
			 */

			// filter the TGM page message
			$vtgmpagemessage = '<h3>'.__('BioShip Theme Framework','bioship');
			$vtgmpagemessage .= ' - '.__('Recommended Plugins','bioship').'</h3><br>';
			$vtgmpagemessage = bioship_apply_filters('tgm_plugin_page_message', $vtgmpagemessage);

			// filter the bundle path
			$vbundlespath = get_template_directory().'/includes/plugins/';
			$vbundlespath = bioship_apply_filters('tgm_plugin_bundles_path', $vbundlespath);

			// note: id (instance) set to bioship-tgmpa to prevent conflicts

			$vconfig = array(
				'id'           => 'bioship-tgmpa',         // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => $vbundlespath,           // Default absolute path to bundled plugins.
				'menu'         => 'tgmpa-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                    // Automatically activate plugins after installation or not.
				'message'      => $vtgmpagemessage,        // Message to output right before the plugins table.

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

			// Filter the TGMPA config
			$vconfig = bioship_apply_filters('tgm_config_array', $vconfig);

			// Load TGM Plugin Activation!
			// ---------------------------
			tgmpa($vplugins, $vconfig);

		 }
		}
	}
}

?>