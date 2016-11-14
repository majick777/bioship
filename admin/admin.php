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

// Note: this file is included via setup near top of functions.php
// 1.5.0: moved admin specific functions here from main functions.php
// 1.8.5: moved admin.php from theme root to /admin/ subdirectory

if (!function_exists('add_action')) {exit;}

// ==========================
// === admin.php Sections ===
// ==========================
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

// TODO: Theme Settings CPT for Revisions (with visual 'editor' off)


// Theme Debug: Echo All Theme Option Values
// -----------------------------------------
if (isset($_REQUEST['themedump'])) {
 if ( ($_REQUEST['themedump'] == 'themeoptions') || ($_REQUEST['themedump'] == 'options')
   || ($_REQUEST['themedump'] == 'backupoptions') || ($_REQUEST['themedump'] == 'backup') ) {
	add_action('init','admin_echo_setting_values');
	if (!function_exists('admin_echo_setting_values')) {
	 function admin_echo_setting_values() {
		if (current_user_can('edit_theme_options')) {
			global $vtheme, $vthemename, $vthemesettings;
			$vtitankey = preg_replace('/\W/','-',strtolower($vthemename)).'_options';
			$vofkey = preg_replace('/\W/','_',strtolower($vthemename));
			if ( ($_REQUEST['themedump'] == 'backupoptions') || ($_REQUEST['themedump'] == 'backup') ) {
				echo "<!-- Auto Backup: (".THEMEKEY."_backup)"; print_r(skeleton_get_option(THEMEKEY.'_backup')); echo PHP_EOL.' -->'.PHP_EOL;
				echo "<!-- User Backup: (".THEMEKEY."_user_backup)"; print_r(skeleton_get_option(THEMEKEY.'_user_backup')); echo PHP_EOL.' -->'.PHP_EOL;
				// return;
			}

			echo "<!-- Theme Object: "; print_r($vtheme); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Titan Framework Settings (".$vtitankey."): "; print_r(maybe_unserialize(skeleton_get_option($vtitankey))); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Options Framework Settings (".$vofkey."): "; print_r(skeleton_get_option($vofkey)); echo PHP_EOL.' -->'.PHP_EOL;
			echo "<!-- Theme Settings (".THEMEKEY."): "; print_r($vthemesettings); echo PHP_EOL.' -->'.PHP_EOL;
			exit;
		}
	 }
	}
 }
}

// Force Update Theme Settings (Save)
// ---------------------------------
add_action('update_option', 'admin_theme_settings_save',11,3);
if (!function_exists('admin_theme_settings_save')) {
 function admin_theme_settings_save($voption,$voldsettings,$vnewsettings) {
 	if ($voption != THEMEKEY) {return;}
 	if ( (defined('THEMEUPDATED')) && (THEMEUPDATED) ) {return;}
 	define('THEMEUPDATED',true); // to do this once only for actual updates

	if ( ($vnewsettings) && (!empty($vnewsettings)) && ($vnewsettings != '') ) {
		// write a manual settings file of the serialized data
		// ob_start(); print_r($vnewsettings); $vsaveddata = ob_get_contents(); ob_end_clean();
		skeleton_write_debug_file($voption.'.txt',$vnewsettings);
		set_transient('force_update_'.THEMEKEY,$vnewsettings,120);
	}

	// TODO: write to theme settings revisions

 }
}

// Transfer Framework Settings
// ---------------------------
// 1.9.5: rewritten and expanded transfer function
if (isset($_REQUEST['transfersettings'])) {add_action('init','admin_framework_settings_transfer');}
if (!function_exists('admin_framework_settings_transfer')) {
 function admin_framework_settings_transfer() {
	if (!current_user_can('edit_theme_options')) {return;}

	global $vthemestyledir, $vthemetemplatedir;

	// check transfer existing options triggers
	if ($_REQUEST['transfersettings'] == 'totitan') {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vtransferfrom = preg_replace("/\W/","_",strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferfrom = str_replace('-','_',$vtransferfrom);
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vtransferto = preg_replace("/\W/","-",strtolower(trim($_REQUEST['totheme'])));
			$vtransferto = str_replace('_','-',$vtransferto); $vtransferto .= '_options';
		} elseif (THEMETITAN) {$vtransferto = THEMEKEY;}

		if ( ($vtransferfrom) && ($vtransferto) ) {
			global $vthemeoptions;

			$voptionvalues = get_option($vtransferfrom);
			if (!$voptionvalues) {
				// try to fallback to retrieving serialized settings from a file
				// if the database is acting up (this code from when it has happened)
				// used by copying the value from database to text in the debug directory
				$vstylefile = $vthemestyledir.'debug/'.$vtransferfrom.'.txt';
				$vtemplatefile = $vthemestyledir.'debug/'.$vtransferfrom.'.txt';
				if (file_exists($vstylefile)) {$vsettingsfile = $vstylefile;}
				elseif (file_exists($vtemplatefile)) {$vsettingsfile = $vtemplatefile;}
				else {
					add_action('theme_admin_notices','admin_options_to_titan_transfer_failed');
					add_action('admin_notices','admin_options_to_titan_transfer_failed');
					if (!function_exists('admin_options_to_titan_transfer_failed')) {function admin_options_to_titan_transfer_notice() {
						echo "<br><div class='update message'>".__('Transfer Failed! Could not retrieve existing settings.','bioship')."</div>";
					} }
					return;
				}

				$vfilecontents = trim(file_get_contents($vsettingsfile));
				// echo $vfilecontents.PHP_EOL; // debug point
				$voptionvalues = unserialize($vfilecontents);
				if (!$voptionvalues) {
				    $vrepaired = skeleton_fix_serialized($vfilecontents);
    				$voptionvalues = unserialize($vrepaired);
    				if (!$voptionvalues) {echo "Error! Could not unserialize settings from file!"; exit;}
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
				delete_option($vtransferto); add_option($vtransferto,serialize($voptionvalues));
				// write settings to file just in case
				skeleton_write_debug_file($vtransferto.'.txt',serialize($voptionvalues));
				// echo serialize($voptionvalues); exit; // for manual output

				add_action('theme_admin_notices','admin_options_to_titan_transfer_notice');
				add_action('admin_notices','admin_options_to_titan_transfer_notice');
				if (!function_exists('admin_options_to_titan_transfer_notice')) {function admin_options_to_titan_transfer_notice() {
					echo "<br><div class='update message'>".__('Transferred Existing Theme Options to Titan Framework Options.','bioship')."</div>";
				} }
			}
		}
	}

	if ($_REQUEST['transfersettings'] == 'tooptions') {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vtransferfrom = preg_replace("/\W/","-",strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferfrom = str_replace('_','-',$vtransferfrom); $vtransferfrom .= '_options';
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vtransferto = preg_replace("/\W/","_",strtolower(trim($_REQUEST['fromtheme'])));
			$vtransferto = str_replace('-','_',$vtransferto);

		} elseif (THEMEOPT) {$vtransferto = THEMEKEY;}

		// TODO: Titan Framework settings to Options Framework settings Transfer here

	}

 }
}

// Manually Copy Theme Settings
// ----------------------------
// 1.9.5: made this to copy to/from any theme settings
// WARNING: will overwrite the existing Theme Settings for a theme
// usage: ?copysettings=yes&copyfrom=source-theme-slug&copyto=destination-theme-slug

if ( (isset($_REQUEST['copysettings'])) && ($_REQUEST['copysettings'] == 'yes') ) {
	add_action('init','admin_copy_theme_settings');
}

if (!function_exists('admin_copy_theme_settings')) {
 function admin_copy_theme_settings() {
 	if (!current_user_can('edit_theme_options')) {return;}

 	$vcopyto = false; $vcopyfrom = false;
 	if (THEMEOPT) {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vcopyfrom = preg_replace("/\W/","_",strtolower(trim($_REQUEST['fromtheme'])));
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vcopyto = preg_replace("/\W/","_",strtolower(trim($_REQUEST['fromtheme'])));
		}
 	}
 	else {
		if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
			$vcopyfrom = preg_replace("/\W/","-",strtolower(trim($_REQUEST['fromtheme'])));
			$vcopyfrom = str_replace('_','-',$vcopyfrom); $vcopyfrom .= '_options';
		}
		if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
			$vcopyto = preg_replace("/\W/","_",strtolower(trim($_REQUEST['totheme'])));
			$vcopyto = str_replace('_','-',$vtransferto); $vcopyto .= '_options';
		}
 	}

 	if ( ($vcopyto) && ($vcopyfrom) ) {
 		$vfromsettings = get_option($vcopyfrom);
 		$vtosettings = get_option($vcopyto);

 		// TODO: backup existing settings?
 		// TODO: also copy over parent widgets/menus ?

 		if (!function_exists('admin_copy_theme_success')) {function admin_copy_theme_success() {
			$vcopyfrom = trim(strtolower($_REQUEST['fromtheme'])); $vcopyto = trim(strtolower($_REQUEST['totheme']));
 		 	echo "<br><div class='update message'>".__('Theme Settings have been copied from ','bioship').$vcopyfrom.__(' to ','bioship').$vcopyto."</div>";
 		} }
 		if (!function_exists('admin_copy_theme_failed_source')) {function admin_copy_theme_failed_source() {
			$vcopyfrom = trim(strtolower($_REQUEST['fromtheme']));
 		 	echo "<br><div class='update message'>".__('Copy Theme Settings failed! Could not retrieve settings for ','bioship').$vcopyfrom."</div>";
 		} }
 		if (!function_exists('admin_copy_theme_failed_destination')) {function admin_copy_theme_failed_destination() {
			$vcopyto = trim(strtolower($_REQUEST['totheme']));
 		 	echo "<br><div class='update message'>".__('Theme Settings failed to copy to ','bioship').$vcopyto."</div>";
 		} }

 		if ($vfromsettings) {
 			$vcopysettings = update_option($vcopyto,$vfromsettings);
			if ($vcopysettings) {
				add_action('admin_notices','admin_copy_theme_success_message');
				add_action('theme_admin_notices','admin_copy_theme_success_message');
			}
			else {
				add_action('admin_notices','admin_copy_theme_failed_destination');
				add_action('theme_admin_notices','admin_copy_theme_failed_destination');
			}
 		} else {
 			add_action('admin_notices','admin_copy_theme_failed_source');
 			add_action('theme_admin_notices','admin_copy_theme_failed_source');
 		}
 	}

 }
}


// ==========================
// === Modify Admin Menus ===
// ==========================
// lots of nice hackiness here...

// Theme Options Page Redirection
// ------------------------------
// 1.9.5: moved here from titan-specific function
if (!function_exists('admin_theme_options_page_redirect')) {
 function admin_theme_options_page_redirect($vupdated='') {
	// 1.8.5: use add_query_arg
	$voptionsurl = admin_url('admin.php');
	// 1.9.5: handle Titan or Options Framework or no framework
	if (THEMETITAN) {$voptionsurl = add_query_arg('page','bioship-options',$voptionsurl);} // $vthemename.'-options'
	elseif (THEMEOPT) {$voptionsurl = add_query_arg('page','options-framework',$voptionsurl);}
	else {$voptionsurl = add_query_arg('page','theme-tools',$voptionsurl);}

	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
		$voptionsurl = add_query_arg('theme',$_REQUEST['theme'],$voptionsurl);
	}
	if ($vupdated != '') {$voptionsurl = add_query_arg('updated',$vupdated,$voptionsurl);}
	wp_redirect($voptionsurl); exit;
 }
}

// Change the 'Theme Options' Framework Admin Menu
// -----------------------------------------------
if (!function_exists('admin_options_default_submenu')) {
 add_filter('optionsframework_menu','admin_options_default_submenu',0);
 function admin_options_default_submenu($vmenu) {
 	if (THEMETRACE) {skeleton_trace('F','admin_options_default_submenu',__FILE__,func_get_args());}

	// note this filter is priority 0 so added filters applied later
	// can be further modified (see Child Theme filters.php)
	if (!is_child_theme()) {
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
	add_action('admin_menu','admin_theme_options_submenu');
	if (!function_exists('admin_theme_options_submenu')) {
	 function admin_theme_options_submenu() {
		if (THEMETRACE) {skeleton_trace('F','admin_theme_options_submenu',__FILE__);}
	 	add_theme_page('Theme Options', 'Theme Options', 'edit_theme_options', 'theme-options', 'admin_theme_options_submenu_dummy');
	 	function admin_theme_options_submenu_dummy() {} // dummy menu item function
	 }
	}
	// trigger redirect to actual admin theme options page
	if (strstr($_SERVER['REQUEST_URI'],'/wp-admin/themes.php')) {
	 	if ( (isset($_REQUEST['page'])) && ($_REQUEST['page'] == 'theme-options') ) {add_action('init','admin_theme_options_page_redirect');}
	}
}

// Hack the Theme Options Submenu Position to Top
// ----------------------------------------------
// (Appearance submenu for Options and Titan Framework)
add_action('admin_head', 'admin_theme_options_position');
if (!function_exists('admin_theme_options_position')) {
 function admin_theme_options_position() {
  	if (THEMETRACE) {skeleton_trace('F','admin_theme_options_position',__FILE__);}

	global $menu;
	if (THEMEDEBUG) {echo "<!-- Admin Menu: "; print_r($menu); echo " -->";}

	global $submenu; $vi = 0; $vdosplice = false;
	if (isset($submenu['themes.php'])) {

		foreach ($submenu['themes.php'] as $submenukey => $vvalues) {
			// 1.8.0: do same for Titan theme options submenu page
			// 1.8.5: allow for theme test drive link overrides as well
			if ( ($vvalues[2] == 'options-framework') || (strstr($vvalues[2],'page=options-framework'))
			  || ($vvalues[2] == 'theme-options') || (strstr($vvalues[2],'page=theme-options')) ) {
				unset($submenu['themes.php'][$submenukey]);
				$vnewposition = apply_filters('muscle_theme_options_position','1');
				if (isset($submenu['themes.php'][$vnewposition])) {
					// in trouble, need to insert and shift the array
					$vdosplice = true; $vj = 0; $vthemesettingsvalues = $vvalues;
					foreach ($submenu['themes.php'] as $vkey => $vvalue) {
						if ($vkey == $vnewposition) {$vposition = $vj;}
						$vj++;
					}
				}
				else {
					// just re-insert at the new position
					$submenu['themes.php'][$vnewposition] = $vvalues;
					$submenuthemes = $submenu['themes.php'];
					ksort($submenuthemes);
					$submenu['themes.php'] = $submenuthemes;
				}
			}
			// 1.8.5: get the themes.php submenu position
			if ($vvalues[2] == 'themes.php') {$vthemesposition = $submenukey; $vthemessubmenu = $vvalues;}

			// 1.8.0: no longer remove the Customize option (fixed)
			// 1.8.5: maybe rename Customize to Live Preview
			if ($vvalues[1] == 'customize') {
				if ( (THEMETITAN) || (THEMEOPT) ) {
					$submenu['themes.php'][$submenukey][0] = 'Live Preview';
				}
			}

			$vlastposition = $submenukey;
			$vi++;
		}

		if ($vdosplice) {
			// shift the $submenu array maintaining keys
			$submenuthemes = $submenu['themes.php']; $newthemesb = array();
			$submenuthemesa = array_slice($submenuthemes,0,$vposition,true);
			$submenuthemesb = array_slice($submenuthemes,$vposition,count($submenuthemes),true);
			foreach ($submenuthemesb as $key => $value) {$newthemesb[$key+1] = $value;}
			$submenuthemesa[$vnewposition] = $vthemesettingsvalues;
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
// 1.8.5: moved admin menu check to theme drive check in functions.php
// if (isset($_REQUEST['theme'])) {
//	if ($_REQUEST['theme'] != '') {add_action('admin_menu','admin_themetestdrive_options',12);}
// }

if (!function_exists('admin_themetestdrive_options')) {
 function admin_themetestdrive_options() {
 	if (THEMETRACE) {skeleton_trace('F','admin_themetestdrive_options',__FILE__);}

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
		// 1.8.5: fix to Titan Theme optons admin menu URL link
		if ($vvalues[2] == $vmenukey) {$menu[$vpriority][2] = 'admin.php?page='.$vmenukey.'&theme='.$vtdtheme; break;}
	}

	// debug points
	if (THEMEDEBUG) {
		echo "<!-- Admin Menu: "; print_r($menu); echo " -->";
		echo "<!-- Admin SubMenu: "; print_r($submenu); echo " -->";
	}

 }
}

// Add Theme Options to the Admin bar menu
// ---------------------------------------
// 1.8.5: moved here from muscle.php, option changed to filter
if (!function_exists('admin_adminbar_theme_options')) {
 $vadminbar = apply_filters('admin_adminbar_theme_options',1);
 if ($vadminbar) {add_action('wp_before_admin_bar_render', 'admin_adminbar_theme_options');}
 function admin_adminbar_theme_options() {
	if (THEMETRACE) {skeleton_trace('F','muscle_adminbar_theme_options',__FILE__);}

	global $wp_admin_bar, $vthemename, $vthemedirs;

	// 1.8.0: link to customize.php if no theme options page exists
	$vthemelink = add_query_arg('return', urlencode(wp_unslash($_SERVER['REQUEST_URI'])), admin_url('customize.php'));

	if ( ( (!THEMETITAN) && (THEMEOPT) )
	  || ( (THEMETITAN) && (class_exists('TitanFramework')) ) ) {
		// 1.8.5: use add_query_arg here
		$vthemelink = admin_url('themes.php');
		if ( (!THEMETITAN) && (THEMEOPT) ) {$vthemepage = 'options-framework';}
		else {$vthemepage = 'bioship-options';} // $vthemename.'-options';
		$vthemelink = add_query_arg('page',$vthemepage,$vthemelink);
	}

	// 1.8.5: maybe append the Theme Test Drive querystring
	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
		$vthemelink = add_query_arg('theme', $_REQUEST['theme'], $vthemelink);
	}

	// 1.5.0: Add an Icon next to the Theme Options menu item
	// ref: http://wordpress.stackexchange.com/questions/172939/how-do-i-add-an-icon-to-a-new-admin-bar-item
	// default is set to \f115 Dashicon (an eye in a screen) in skin.php
	// and can be overridden using admin_adminbar_menu_icon filter
	$vicon = skeleton_file_hierarchy('url','theme-icon.png',$vthemedirs['img']);
	$vicon = apply_filters('admin_adminbar_theme_options_icon',$vicon);
	if ($vicon) {
		$viconspan = '<span class="theme-options-icon" style="
			float:left; width:22px !important; height:22px !important;
			margin-left: 5px !important; margin-top: 5px !important;
			background-image:url(\''.$vthemesettingsicon.'\');"></span>';
	} else {$viconspan = '<span class="ab-icon"></span>';}

	$vtitle = __('Theme Options','bioship');
	$vtitle = apply_filters('admin_adminbar_theme_options_title',$vtitle);
	$vmenu = array('id' => 'theme-options', 'title' => $viconspan.$vtitle, 'href' => $vthemelink);
	$wp_admin_bar->add_menu($vmenu);
 }
}

// Replace Howdy in Admin bar
// --------------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('admin_adminbar_replace_howdy')) {
 add_filter('admin_bar_menu','admin_adminbar_replace_howdy',25);
 function admin_adminbar_replace_howdy($wp_admin_bar) {
	if (THEMETRACE) {skeleton_trace('F','admin_adminbar_replace_howdy',__FILE__,func_get_args());}
	// 1.9.8: replaced deprecated function get_currentuserinfo();
	global $current_user; wp_get_current_user();
	$vusername = $current_user->user_login;
	$vmyaccount = $wp_admin_bar->get_node('my-account');
	// 1.5.5: fixed translation for Theme Check
	$vnewtitle = __('Logged in as ', 'bioship').$vusername;
	$vnewtitle = apply_filters('admin_adminbar_howdy_title',$vnewtitle);
	$wp_admin_bar->add_node(array('id' => 'my-account','title' => $vnewtitle));
 }
}

// Remove Admin Footer
// -------------------
// 1.8.5: moved here from muscle.php
if (!function_exists('admin_remove_admin_footer')) {
 add_filter('admin_admin_footer_text', 'admin_remove_admin_footer');
 function admin_remove_admin_footer() {
	if (THEMETRACE) {skeleton_trace('F','admin_remove_admin_footer',__FILE__);}
	return apply_filters('muscle_admin_footer_text','');
 }
}

// ====================================
// === Theme Admin Page Menu Header ===
// ====================================

// Add Top Menu section to Admin Page (for Titan Framework)
if (function_exists('add_action')) {add_action('tf_admin_page_before_'.$vthemename,'admin_theme_options_menu');}
// note: other sections of the Titan Framework generated admin page
// add_action('tf_admin_page_table_start_'.$vthemename,'');
// add_action('tf_admin_page_end_'.$vthemename,'');
// add_action('tf_admin_page_after_'.$vthemename,'');

// Add Top Menu section to Admin Notices (for Options Framework)
if ( (isset($_REQUEST['page'])) && ($_REQUEST['page'] == 'options-framework') ) {
	if (function_exists('add_action')) {add_action('all_admin_notices','admin_theme_options_menu',99);}
}

// -----------------------------------
// Theme Options Menu with Filter Tabs
// -----------------------------------
if (!function_exists('admin_theme_options_menu')) {
	function admin_theme_options_menu() {

		global $wp_version, $vthemesettings, $vthemename, $vthemeversion, $vthemedirs;

		if (version_compare($wp_version,'3.8','<')) {$vnagclass = 'updated';} else {$vnagclass = 'update-nag';} // '>

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

		// maybe File System Credentials Form
		// ----------------------------------
		// 1.8.0: checks permissions for creating Child Theme
		// 1.9.5: check permission for creating clones also
		if ( (isset($_REQUEST['newchildname'])) || (isset($_REQUEST['newclonename'])) ) {
			if (THEMEOPT) {$vurl = admin_url('themes.php').'?page=options-framework';}
			else {$vurl = admin_url('admin.php').'?page=bioship-options';} // $vthemename.'-options'
			$vmethod = ''; $vcontext = false;
			$vextrafields = array('newchildname'); // not sure if really needed
			$vfilesystemcheck = admin_check_filesystem_credentials($vurl, $vmethod, $vcontext, $vextrafields);
		}

		// WordQuest Floating Sidebar
		// --------------------------
		// 1.8.5: call sidebar here directly (floats right)
		options_floating_sidebar();

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
		echo '<div id="themeoptionswrap">';
		// 1.8.5: hidden input for extend layer switching
		echo "<input type='hidden' name='prevlayer' id='prevlayer' value=''>";

		// Theme Options Page Header
		// -------------------------
		echo '<br><div id="themeoptionsheader"><table><tr>';

			// Theme Logo
			// ----------
			$vthemelogo = skeleton_file_hierarchy('url','theme-logo.png',$vthemedirs['img']);
			if ($vthemelogo) {echo '<td><img src="'.$vthemelogo.'"></td>';}
			echo '<td width="10"></td>';

			// Theme Name and Version
			// ----------------------
			echo '<td><table id="themedisplayname" cellpadding="0" cellspacing="0"><tr height="40">';
			echo '<td style="vertical-align:middle;"><h2 style="margin:5px 0;">'.THEMEDISPLAYNAME.'</h2>';
			echo '</td><td width="10"></td><td><h3 style="margin:5px 0;">v'.$vthemeversion.'</h3></td>';
			echo '</tr>';
			echo '<tr height="40"><td colspan="3" align="center" style="vertical-align:middle;">';

			// Small Theme Links
			// -----------------
			// TODO: Docs could be a popup link to /bioship/admin/docs.php?
			$vbioshipurl = "http://"."bioship.space";
			echo '<font style="font-size:11pt;"><a href="'.$vbioshipurl.'/news/" class="frameworklink" title="BioShip Theme Framework News" target=_blank>News</a>';
			echo ' | <a href="'.$vbioshipurl.'/documentation/" class="frameworklink" title="BioShip Theme Framework Documentation" target=_blank>Docs</a>';
			// echo ' | <a href="'.$vbioshipurl.'/faq/" class="frameworklink" title="BioShip Theme Framework Frequently Asked Questions" target=_blank>FAQ</a>';
			echo ' | <a href="'.$vbioshipurl.'/development/" class="frameworklink" title="BioShip Theme Framework Development" target=_blank>Dev</a>';
			// echo ' | <a href="'.$vbioshipurl.'/extensions/" class="frameworklink" title="BioShip Theme Framework Extensions" target=_blank>Extend</a>';
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
						$vmessage = admin_do_install_child();
						if (strstr($vmessage,'<!-- SUCCESS -->')) {$vnewchild = true;}
					}
					elseif (isset($_REQUEST['newclonename'])) {
						$vmessage = admin_do_install_clone();
						if (strstr($vmessage,'<!-- SUCCESS -->')) {$vnewclone = true;}
					}
				} else {$vmessage = __('Check your file system owner/group permissions!','bioship');}
				if ($vmessage != '') {echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;">'.$vmessage.'</div><br>';}
			}

			// 1.8.0: form action URL for Options or Titan Framework
			// 1.9.5: use add_query_arg for query arguments
			$vactionurl = admin_url('admin.php');
			if (THEMETITAN) {$vactionurl = add_query_arg('page','bioship-options',$vactionurl);} // $vthemename.'-options';
			else {$vactionurl = add_query_arg('page','options-framework',$vactionurl);}

			// 1.9.5: added 'Clone' to new child name for existing child themes
			if (!is_child_theme()) {$vnewchildname = "BioShip Child";} else {$vnewclonename = THEMEDISPLAYNAME.' Clone';}

			if ( (!is_child_theme()) && (!$vnewchild) ) {

				if ( (isset($_REQUEST['newchildname'])) && ($_REQUEST['newchildname'] != '') ) {$vnewchildname = $_REQUEST['newchildname'];}

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
				echo '<font style="font-size:11pt;line-height:22px;"><b>Create Child Theme:</b></font></td>';
				echo '<td width="10"></td><td><input type="text" name="newchildname" style="font-size:11pt; width:120px;" value="'.$vnewchildname.'"></td>';
				echo '<td width="10"></td><td><input type="submit" class="button-primary" title="Note: alphanumeric and spaces only." value="Create"></td>';
				echo '</td></tr></table></form>';
			}
			elseif ( (is_child_theme()) && (!$vnewclone) ) {

				if ( (isset($_REQUEST['newclonename'])) && ($_REQUEST['newclonename'] != '') ) {$vnewclonename = $_REQUEST['newclonename'];}

				// Clone Child Theme Form
				// ----------------------
				// 1.9.5: added child theme clone form

				echo '<form action="'.$vactionurl.'" method="post">';
				wp_nonce_field('bioship_child_clone');
				$vthemeslug = preg_replace("/\W/","-",strtolower(THEMEDISPLAYNAME));
				echo '<input type="hidden" name="clonetheme" value="'.$vthemeslug.'">';
				echo '<table><tr><td align="left">';
				echo '<font style="font-size:11pt;line-height:22px;"><b>Clone Child Theme to:</b></font></td>';
				echo '<td width="10"></td><td><input type="text" name="newclonename" style="font-size:11pt; width:120px;" value="'.$vnewclonename.'"></td>';
				echo '<td width="10"></td><td><input type="submit" class="button-primary" title="Note: alphanumeric and spaces only." value="Clone"></td>';
				echo '</td></tr></table></form>';
			}

			// Theme Options Filter Buttons
			// ----------------------------
			echo '<table style="float:left; margin-top:5px;"><tr>';
			// Theme Home (extend) button
			echo '<td><div id="extendoptions" class="filterbutton" onclick="showhideextend();">';
			echo '<a href="javascript:void(0);">Info</a>';
			echo '</div></td>';
			// Skin filter button
			echo '<td width="10"></td><td><div id="skinoptions" class="filterbutton activefilter" onclick="switchlayers(\'skin\');">';
			echo '<a href="javascript:void(0);">Skin</a>';
			echo '</div></td>';
			// Muscle filter button
			echo '<td width="10"></td><td><div id="muscleoptions" class="filterbutton" onclick="switchlayers(\'muscle\');">';
			echo '<a href="javascript:void(0);">Muscle</a>';
			echo '</div></td>';
			// Skeleton filter button
			echo '<td width="10"></td><td><div id="skeletonoptions" class="filterbutton" onclick="switchlayers(\'skeleton\');">';
			echo '<a href="javascript:void(0);">Skeleton</a>';
			echo '</div></td>';
			// ALL options button
			echo '<td width="10"></td><td><div id="alloptions" class="filterbutton" onclick="showalllayers();">';
			echo '<a href="javascript:void(0);">ALL</a>';
			echo '</div></td>';
			echo '</tr></table>';

		echo '</td></tr></table></div><br>';

		// maybe output Theme Updates available
		// ------------------------------------
		// 1.9.6: separate line for theme update alert
		$vthemeupdates = admin_theme_updates_available();
		if ($vthemeupdates != '') {
			$vthemeupdates = str_replace('<br>',' ',$vthemeupdates);
			echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;text-align:center;">'.$vthemeupdates.'</div></font><br>';
		}

		// Theme Admin Notices
		// -------------------
		// 1.9.5: added this theme action since main admin_notices are now boxed
		ob_start(); do_action('theme_admin_notices'); $vthemenotices = ob_get_contents(); ob_end_clean();
		if ($vthemenotices != '') {
			echo '<div style="float:none; clear:both;"></div><br>';
			echo '<div class="'.$vnagclass.'" style="padding:3px 10px;margin:0 0 10px 0;">'.$vthemenotices.'</div>';
		}

		// Theme Info Section
		// ------------------
		echo '<div id="extendwrapper" class="wrap"';
			if ($vthemesettings['layertab'] != '') {echo 'style="display:none;"';}
		echo '>';
			admin_theme_info_section();
		echo '<p>&nbsp;</p><br></div>';
		echo '<div style="float:none; clear:both;"></div>';
		// echo '<p>&nbsp;</p><br>';

		// Option/Layer Tab Javascript to Admin Footer
		// -------------------------------------------
		add_action('admin_footer','options_switch_layer_options_tabs');

		// 1.8.0: fixed nesting problem causing javascript error
		// 1.8.0: detect and switch to tab for Titan framework also
		// 1.8.5: simplified defaults and improved options switching
		if (!function_exists('options_switch_layer_options_tabs')) {
		 function options_switch_layer_options_tabs() {
		 	global $vthemename;

			// Switch Layer Display (defaults to skin)
			// ---------------------------------------
			echo "<script language='javascript' type='text/javascript'>
			function optionstabsdisplay() {
				var layertab; var optionstab;
				if (document.getElementById('layertab')) {layertab = document.getElementById('layertab').value;} else {
					if (document.getElementById('".$vthemename."_layertab')) {layertab = document.getElementById('".$vthemename."_layertab').value;}
				}

				if (layertab == '') {layertab = 'skin';}
				if (layertab == 'alloptions') {showalllayers();}
				else {if (layertab == 'extend') {switchlayers('skin'); showhideextend();}	else {switchlayers(layertab);} }

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
			if (THEMETITAN) {$vtableclasses = '.titan-framework-panel-wrap,.nav-tab-wrapper,.options-container,.form-table';} // Titan Framework
			else {
				$vtableclasses = '#optionsframework-wrap,#optionsframework-metabox,#optionsframework,.nav-tab-wrapper';
				// $vtableclasses .= ',.group,.options-container';
			} // Options Framework

			// make room for righthand sidebar...
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
				// 1.8.5: used instead of floating sidebar script
				// TODO: add check for stick_in_parent function exists
				echo "jQuery('#floatdiv').stick_in_parent({offset_top:100});";

			echo "});</script>";
		 }
		}

		// Closing Div For Theme Options Center
		// ------------------------------------
		// 1.8.5: closes #themeoptionswrap div
		add_action('tf_admin_page_after_'.$vthemename,'options_closing_div'); // Titan
		add_action('optionsframework_after','options_closing_div'); // Options Framework
		if (!function_exists('options_closing_div')) {
			function options_closing_div() {
				echo '</div>'; if (THEMECOMMENTS) {echo '<!-- /#themeoptionswrap -->';}
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

		if (function_exists('add_action')) {add_action('admin_head','options_admin_page_styles');}

		if (!function_exists('options_admin_page_styles')) {
		 function options_admin_page_styles() {
		 	global $vthemename;
		 	// 1.8.5: improved button tab colour scheme
		 	// TODO: separate this out to an admin-styles.css file
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
				#extendcolumn .postbox, #feedcolumn .postbox {width:350px;}

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
			".PHP_EOL;
			$vstyles = apply_filters('options_themepage_styles',$vstyles);
			echo "<style>".$vstyles."</style>";
		 }
		}
	}
}

// -----------------------
// Floating Sidebar Output
// -----------------------
// Donations / Testimonials / Sidebar Ads / Footer
if (!function_exists('options_floating_sidebar')) {
 function options_floating_sidebar() {

	// Include WordQuest Sidebar
	// -------------------------
	$vsidebar = skeleton_file_hierarchy('file','wordquest.php',array('includes'));
	// 1.8.5: change from wordquest_admin_load to match new helper version (1.6.0)
	if ($vsidebar) {include_once($vsidebar); wqhelper_admin_loader();}

	if (function_exists('wqhelper_sidebar_floatbox')) {

		// Filter Sidebar Save Button
		// --------------------------
		add_filter('wordquest_sidebar_save_button','admin_sidebar_save_button');
		if (!function_exists('admin_sidebar_save_button')) {
		 function admin_sidebar_save_button($vbutton) {
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
		// 1.8.5: change from wordquest_sidebar_floatbox to match new helper version (1.6.0)
		wqhelper_sidebar_floatbox($vargs);

		// 1.8.5: removed float script for theme in favour of using stickykit
		// $vfloatmenuscript = wqhelper_sidebar_floatmenuscript();
		// echo $vfloatmenuscript;

		// echo '<script>
		// floatingMenu.add("floatdiv", {targetRight: 10, targetTop: 40, centerX: false, centerY: false});
		// function floatbox_move_upper_right() {
		// 	floatingArray[0].targetTop=40;
		//	floatingArray[0].targetBottom=undefined;
		//	floatingArray[0].targetLeft=undefined;
		//	floatingArray[0].targetRight=10;
		//	floatingArray[0].centerX=undefined;
		//	floatingArray[0].centerY=undefined;
		// }
		// floatbox_move_upper_right();
		// </script>';
	}

 }
}

// QuickSave CSS
// -------------
// 1.8.5: added CSS quicksave
add_action('wp_ajax_quicksave_css_theme_settings','admin_quicksave_css');
if (!function_exists('admin_quicksave_css')) {
 function admin_quicksave_css() {
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
// - with "leave this page without saving" catch

// =======================
// === Theme Info Page ===
// =======================

// Theme Info Page
// ---------------
// standalone page for WordPress.org version (no Theme Framework page)
if (!function_exists('admin_theme_info_page')) {
 function admin_theme_info_page() {

	// TODO: improve this page title display...
	echo "<h3>BioShip Theme Info</h3><br>";

	// TODO: add Titan installation info and install link

	// Include WordQuest Sidebar
	// -------------------------
	$vsidebar = skeleton_file_hierarchy('file','wordquest.php',array('includes'));
	// 1.8.5: change from wordquest_admin_load for new helper version (1.6.0)
	if ($vsidebar) {include_once($vsidebar); wqhelper_admin_loader();}

	// Load Theme Info Section
	// -----------------------
	echo '<div class="wrap">';
		admin_theme_info_section();
	echo '<p></p><br></div><div style="float:none; clear:both;"></div>';

 }
}

// Theme Info Section
// ------------------
// 1.8.0: separate function for theme home info tab
if (!function_exists('admin_theme_info_section')) {
 function admin_theme_info_section() {

	global $vthemedirs;

	// Toggle Box Javascript
	// ---------------------
	echo "<script>function togglethemebox(divid) {
		var divid = divid+'-inside';
		if (document.getElementById(divid).style.display == '') {
			document.getElementById(divid).style.display = 'none';
		} else {document.getElementById(divid).style.display = '';}
	}</script>";

	// Admin Notices - Wide (collapsed)
	// --------------------------------
	$vboxid = 'adminnotices'; $vboxtitle = __('Admin Notices','bioship');
	echo '<div id="'.$vboxid.'" class="postbox" style="max-width:720px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>&#9660; '.$vboxtitle.'</span></h3>';
	echo '<div class="inside" id="'.$vboxid.'-inside" style="display:none;">';
	echo '<h2></h2>';
		// Admin Notices reinsert themselves magically here after the <h2> tag inside a <div class="wrap">
		// Note: TGM Plugin Activations Notice still disappears from here when dismissed :-(
		// FIXME =dismiss_admin_notices should have no effect here (and only here)
	echo '</div></div>';

	// Theme Tools - Wide (collapsed)
	// ------------------------------
	$vboxid = 'themetools'; $vboxtitle = __('Theme Options Tools','bioship');
	echo '<div id="'.$vboxid.'" class="postbox" style="max-width:720px;">';
	echo '<h3 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>&#9660; '.$vboxtitle.'</span></h3>';
	echo '<div class="inside" id="'.$vboxid.'-inside" style="display:none;"><center>';
		admin_theme_tools_forms();
	echo '</div></div>';

	// Left Column - Links / Extensions
	// --------------------------------
	echo '<div id="extendcolumn">';

		// BioShip Theme Links
		// -------------------
		// IMPROVEME: needs some lovin attention...
		$vbioshipurl = "http://"."bioship.space";
		$vwordquesturl = "http://"."wordquest.org";
		$vboxid = 'bioshiplinks'; $vboxtitle = __('Theme Links','bioship');
		echo '<div id="'.$vboxid.'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
		echo '<div class="inside" id="'.$vboxid.'-inside">';
			$vbioshiplogo = skeleton_file_hierarchy('url','bioship.png',$vthemedirs['img']);
			echo '<table><tr><td><a href="'.$vwordquesturl.'" class="themelink" target=_blank><img src="'.$vbioshiplogo.'" border="0"></a></td><td width="20"></td><td>';
			echo '<a href="'.$vbioshipurl.'/documentation/" class="themelink" target=_blank>BioShip Documentation</a><br>';
			// TODO: list of documentation subpages?
			// -- Installation ... etc.
			echo '<a href="'.$vbioshipurl.'/extensions/" class="themelink" target=_blank>BioShip Extensions</a><br>';
			// TODO: list of Theme related plugin extensions?
			// Content Sidebars / AutoSave Net (DraftNet?) / FreeStyler
			// echo '<a href="'.$vwordquesturl.'/plugins/content-sidebars/" class="themelink" target=_blank>Content Sidebars</a>';
			// echo '<a href="'.$vwordquesturl.'/plugins/autosave-net/" class="themelink" target=_blank>AutoSave Net</a>';
			// echo '<a href="'.$vwordquesturl.'/plugins/freestyler/" class="themelink" target=_blank>FreeStyler</a>';
			// Support Forum Links
			echo '<a href="'.$vwordquesturl.'/quest/quest-category/bioship-support/" class="themelink" target=_blank>Support Q&A</a><br>';
			echo '<a href="'.$vwordquesturl.'/quest/quest-category/bioship-features/" class="themelink" target=_blank>Features Q&A</a><br>';
			// Development
			// echo '<a href="http://github.com/majick777/bioship/" class="themelink" target=_blank>BioShip on GitHub</a><br>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// WordQuest Plugins
		// -----------------
		$vboxid = 'wordquestplugins'; $vboxtitle = __('WordQuest Alliance','bioship');
		echo '<div id="'.$vboxid.'" class="postbox">';
		echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
		echo '<div class="inside" id="'.$vboxid.'-inside">';
			$vwordquestlogo = skeleton_file_hierarchy('url','wordquest.png',$vthemedirs['img']);
			echo '<table><tr><td><a href="http://wordquest.org" target=_blank><img src="'.$vwordquestlogo.'" border="0"></a></td><td width="20"></td><td>';
			if (isset($GLOBALS['admin_page_hooks']['wordquest'])) {
				echo '<a href="'.admin_url('admin.php').'?page=wordquest" class="themelink">Plugin Panel</a><br>';
			}
			echo '<a href="http://wordquest.org/login/" class="themelink" target=_blank>Members Login</a><br>';
			echo '<a href="http://wordquest.org/register/" class="themelink" target=_blank>Join the Alliance</a><br>';
			echo '<a href="http://wordquest.org/solutions/" class="themelink" target=_blank>Solutions Forum</a><br>';
			echo '<a href="http://wordquest.org/plugins/" class="themelink" target=_blank>WordQuest Plugins</a><br>';
			echo '</td></tr></table>';
		echo '</div></div>';

		// Recommended Plugins
		// -------------------
		// 1.8.5: added recommended box display
		// TODO: exclude recommended.php file from wp.org version?
		$vrecommended = skeleton_file_hierarchy('file','recommended.php',array('includes'));
		if ($vrecommended) {
			include($vrecommended); $vrecommend = bioship_get_recommended();
			if ($vrecommend != '') {
				$vboxid = 'recommended'; $vboxtitle = __('Recommended','bioship');
				echo '<div id="'.$vboxid.'" class="postbox">';
				echo '<h2 class="hndle" onclick="togglethemebox(\''.$vboxid.'\');"><span>'.$vboxtitle.'</span></h2>';
				echo '<div class="inside" id="'.$vboxid.'-inside">';
				echo $vrecommend; // include($vrecommended);
				echo '</div></div>';
			}
		}

		// TODO: Child Theme Skin / Colour Presets here?

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
				muscle_bioship_dashboard_feed_widget();
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

		// Enequeue Feed Loader Javascript
		// -------------------------------
		if (!has_action('admin_footer','wqhelper_dashboard_feed_javascript')) {
			add_action('admin_footer','wqhelper_dashboard_feed_javascript');
		}

	echo '</div>'; // close feed column

 }
}


// ===================================
// Check for Theme Updates HTML Output
// ===================================

if (!function_exists('admin_theme_updates_available')) {
 function admin_theme_updates_available() {

	global $wp_version, $vthemename, $vthemeversion;
	$vthemedisplayname = THEMEDISPLAYNAME;
	$updatehtml = ''; $vthemeslug = str_replace('_','-',$vthemename);

	// check user capability just in case
	if (!current_user_can('update_themes')) {
		if (is_child_theme()) {
			$vtheme = wp_get_theme(get_stylesheet($vthemeslug));
			$vparenttheme = wp_get_theme(get_template($vtheme['Template']));
			$vparentversion = $vparenttheme['Version'];
			$updatehtml = 'Parent Theme:<br>BioShip Framework v'.$vparentversion.'<br>';
		}
		// else {$updatehtml = 'Theme Framework v'.$vthemeversion.'<br>';}
		return $updatehtml;
	}


	// note: created from modified WP function get_theme_update_available
	$updatestransient = get_site_transient('update_themes');
	$updates = $updatestransient->response;
	if (THEMEDEBUG) {echo "<!-- Updates Transient: "; print_r($updatestransient); echo " -->";}

	if (is_child_theme()) {
		if (isset($updates[$vthemeslug])) {
			// special: allow for a user specified Child Theme update location
			// ie. by calling a new Theme Updater instance in their Child Theme
			$update = $updates[$vthemeslug];
			$update_url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($vthemeslug)), 'upgrade-theme_'.$vthemeslug);
			$update_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Warning! Updating this Child Theme will lose any file customizations you have made! 'Cancel' to stop, 'OK' to update.",'bioship') ) . '\') ) {return true;} return false;"';
			if (!empty($update['package'])) {
				// if ( (!is_multisite()) || ( (is_multisite()) && (current_user_can('manage_network_themes')) ) ) {
					$updatehtml .= sprintf(__( 'New Child Theme available.<br><a href="%2$s" title="%3$s" target=_blank>View v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship' ),
					$vthemedisplayname, esc_url($update['url']), esc_attr($vthemedisplayname), $update['new_version'], $update_url, $update_onclick );
				// }
			}
		}

		// change the info so the parent theme updates are displayed next
		$vtheme = wp_get_theme($vthemeslug);
		$vparenttheme = wp_get_theme(get_template($vtheme['Template']));
		$vthemeslug = $vparenttheme['Stylesheet'];
		$vthemedisplayname = $vparenttheme['Stylesheet'];
		$vthemeversion = $vparenttheme['Version'];
		$updatehtml .= 'Parent Theme:<br>BioShip Framework v'.$vthemeversion.'<br>';

		// echo '**'.$vthemeslug.'**'.$vthemedisplayname.'**'.$vthemeversion.'<br>'; // debug point
	}

	// output in either case (child theme parent or base framework)
	if (isset($updates[$vthemeslug])) {
		$update = $updates[$vthemeslug];
		$update_url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme='.urlencode($vthemeslug)), 'upgrade-theme_'.$vthemeslug);
		$update_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Updating this Theme Framework will lose any file customizations not in your Child Theme. 'Cancel' to stop, 'OK' to update.",'bioship') ) . '\') ) {return true;} return false;"';
		if (!empty($update['package'])) {
			// if ( (!is_multisite()) || ( (is_multisite()) && (current_user_can('manage_network_themes')) ) ) {
				$updatehtml .= sprintf(__('New Framework version available!<br><a href="%2$s" title="%3$s" target=_blank>v%4$s Details</a> or <a href="%5$s">Update Now</a>.<br>','bioship'),
				$vthemedisplayname, esc_url($update['url']), esc_attr($vthemedisplayname), $update['new_version'], $update_url, $update_onclick );
			// }
		}
	}

	return $updatehtml;
 }
}

// =============================
// One-Click Child Theme Install
// =============================

if (!function_exists('admin_do_install_child')) {
 function admin_do_install_child() {

	global $wp_filesystem; // load WP Filesystem for correct writing permissions
	global $vthemetemplatedir, $vthemestyledir; $vmessage = '';

	// match new Child Name, allowing for spaces
	$vnewchildname = $_REQUEST['newchildname'];
	if ($vnewchildname == '') {return __('Error: Child Theme Name cannot be empty.','bioship');}
	if (!preg_match('/^[0-9a-z ]+$/i', $vnewchildname)) {return __('Error. Letters, numbers and spaces only please!','bioship');}
	$vnewchildslug = preg_replace("/\W/", "-",strtolower($vnewchildname));

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
			// $vfh = f-o-p-e-n($vchildsource.$vchildfile,'r');
			// $vfilesize = filesize($vchildsource.$vchildfile);
			// $vfilecontents = f-r-e-a-d($vfh,$vfilesize); f-c-l-o-s-e($vfh);
			$vfilecontents = $wp_filesystem->get_contents($vchildsource.$vchildfile);

			// replace the default Child Theme name with the New one in style.css
			if ($vchildfile == 'style.css') {
				$vfilecontents = str_replace('Theme Name: BioShip Child','Theme Name: '.$vnewchildname,$vfilecontents);
				// 1.9.5: match the child theme version to the parent version on creation
				$vfilecontents = str_replace('1.0.0',BIOSHIPVERSION,$vfilecontents);
			}

			// 1.8.0: write the file using WP_Filesystem
			// $vfh = f-o-p-e-n($vchilddir.$vchildfile,'w'); f-w-r-i-t-e($vfh,$vfilecontents); f-c-l-o-s-e($vfh);
			$wp_filesystem->put_contents($vchilddest.$vchildfile,$vfilecontents,FS_CHMOD_FILE);

		}
		else {$vmissingfiles[] = $vchildfile;}
	}

	// 1.8.5: change 'child-source' directory to 'child'
	if (count($vmissing) > 0) {
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
	if (THEMEOPT) {$vparentsettings = get_option('bioship'); $vchildoptionsslug = str_replace('-','_',$vnewchildslug);}
	else {$vparentsettings = get_option('bioship_options'); $vchildoptionsslug = $vnewchildslug.'_options';}

	$vexistingsettings = get_option($vchildoptionsslug);
	if (!$vexistingsettings) {
		delete_option($vchildoptionsslug);
		if ( (!$vparentsettings) || ($vparentsettings == '') ) {
			// 1.9.5: set to default theme options
			$vdefaultsettings = skeleton_titan_theme_options(array());
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
		add_option($vchildoptionsslug.'_widgets_backup',$vsidebarswidgets);
	}
	if (!get_option($vchildoptionsslug.'_menus_backup')) {
		delete_option($vchildoptionsslug.'_menus_backup');
		add_option($vchildoptionsslug.'_menus_backup',$vnavmenus);
	}
	if (!get_option($vchildoptionsslug.'_menu_locations_backup')) {
		delete_option($vchildoptionsslug.'_menu_locations_backup');
		add_option($vchildoptionsslug.'_menu_locations_backup',$vmenulocations);
	}

	// Set Child Creation Output Message
	// ---------------------------------
	// 1.8.0: added translation strings to messages
	if ($vmessage != '') {$vcreationresult = '('.__('with errors','bioship').')';}
	else {$vcreationresult = __('successfully','bioship').'<!-- SUCCESS -->';}

	$vmessage .= $vsettingsmessage;
	$vmessage .= __('New Child Theme','bioship').' "'.$vnewchildname.'" '.__('created','bioship').' '.$vcreationresult.'.<br>';
	$vmessage .= __('Base Directory','bioship').': '.ABSPATH.'<br>';
	$vmessage .= __('Theme Subdirectory','bioship').': '.str_replace(ABSPATH,'',$vchilddir).'<br>';
	$vmessage .= __('Activate it on your','bioship').' <a href="'.admin_url('themes.php').'">'.__('Themes Page','bioship').'</a>.';

	// One-Click Activation for New Child Theme
	// ----------------------------------------
	$vwpnonce = wp_create_nonce('switch-theme_'.$vnewchildslug);
	$vactivatelink = 'themes.php?action=activate&stylesheet='.$vnewchildslug.'&_wpnonce='.$vwpnonce;
	$vmessage .= '... '.__('or just','bioship').' <a href="'.$vactivatelink.'">'.__('click here to activate it','bioship').'</a>.';
	if (function_exists('themedrive_determine_theme')) {
		// 1.8.0: link for options framework or titan framework
		if (THEMETITAN) {$vchildthemeoptions = 'admin.php?page='.$vnewchildslug.'_options&theme='.$vnewchildslug;}
		else {$vchildthemeoptions = 'themes.php?page=options-framework&theme='.$vnewchildslug;}
		$vmessage .= '<br>('.__('or','bioship').' <a href="'.$vchildthemeoptions.'">'.__('Theme Test Drive options without activating','bioship').'</a>.)';
	}

	return $vmessage;
 }
}

// Clone Child Theme
// -----------------
// 1.9.5: added child theme cloning function
if (!function_exists('admin_do_install_clone')) {
 function admin_do_install_clone() {

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
	$vchildfiles = admin_get_directory_files($vchilddir,true);
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
	$vsubdirs = admin_get_directory_subdirs($vchilddir,true);
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
	$wp_filesystem->put_contents($vdestfile,$vfilecontents,FS_CHMOD_FILE);

	// copy child theme settings
	if (THEMEOPT) {$vnewclonekey = $vnewcloneslug;} else {$vnewclonekey = $vnewcloneslug.'_options';}
	delete_option($vnewclonekey); add_option($vnewclonekey,$vclonesettings);

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
		if (THEMETITAN) {$vclonethemeoptions = 'admin.php?page='.$vnewcloneslug.'_options&theme='.$vnewcloneslug;}
		else {$vclonethemeoptions = 'themes.php?page=options-framework&theme='.$vnewcloneslug;}
		$vmessage .= '<br>('.__('or','bioship').' <a href="'.$vclonethemeoptions.'">'.__('Theme Test Drive options without activating','bioship').'</a>.)';
	}
	$vmessage .= "<!-- SUCCESS -->";

	return $vmessage;

 }
}

// Check WP Filesystem Credentials
// -------------------------------
// 1.8.0: for Child Theme creation to pass Theme Check
// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/
// ref: http://www.webdesignerdepot.com/2012/08/wordpress-filesystem-api-the-right-way-to-operate-with-local-files/
if (!function_exists('admin_check_filesystem_credentials')) {
 function admin_check_filesystem_credentials($vurl, $vmethod, $vcontext, $vextrafields) {
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
if (!function_exists('admin_get_directory_files')) {
 function admin_get_directory_files($dir, $recursive = true, $basedir = '') {

	if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
	if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
	if ($basedir == '') {$basedir = realpath($dir).DIRECTORY_SEPARATOR;}

	$files = scandir($dir);
	foreach ($files as $key => $value){
		if ( ($value != '.') && ($value != '..') ) {
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if (is_dir($path)) { // do not combine with the next line or
				if ($recursive) { // non-recursive file list includes subdirs
					$subdirresults = admin_get_directory_files($path,$recursive,$basedir);
					$results = array_merge($results,$subdirresults);
					unset($subdirresults);
				}
			} else { // strip basedir and add to subarray to separate list
				$subresults[] = str_replace($basedir,'',$path);
			}
		}
	}
	// merge the subarray to give list of files first, then subdirectory files
	if (count($subresults) > 0) {
		$results = array_merge($subresults,$results); unset($subresults);
	}
	return $results;
 }
}

// Get Directory SubDirs Recursion
// -------------------------------
if (!function_exists('admin_get_directory_subdirs')) {
 function admin_get_directory_subdirs($dir, $recursive = true, $basedir = '') {

	if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
	if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
	if ($basedir == '') {$basedir = realpath($dir).DIRECTORY_SEPARATOR;}

	$files = scandir($dir);
	foreach ($files as $key => $value){
		if ( ($value != '.') && ($value != '..') ) {
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if (is_dir($path)) {
				$results[] = str_replace($basedir,'',$path);
				if ($recursive) {
					$subdirresults = admin_get_directory_subdirs($path,$recursive,$basedir);
					$results = array_merge($results,$subdirresults);
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
// ...this method only works for Options Framework
if ( (isset($_GET['page'])) && (isset($_GET['settings-updated'])) ) {
	if ( ($_GET['page'] == 'options-framework') && ($_GET['settings-updated'] == 'true') ) {
		add_action('admin_notices','admin_build_selective_resources');
	}
}
// 1.8.0: need to trigger this differently for Titan save!
if ( (isset($_GET['page'])) && (isset($_GET['message'])) ) {
	if ( ($_GET['page'] == $vthemename.'-options') && ($_GET['message'] == 'saved') ) {
		add_action('admin_notices','admin_build_selective_resources');
	}
}
// 1.8.0: ...also need to trigger this after a Customizer save...
add_action('customize_save_after','admin_build_selective_resources');


// Build Selective CSS and JS
// --------------------------
if (!function_exists('admin_build_selective_resources')) {
 function admin_build_selective_resources() {

	global $vthemename, $vthemesettings, $vthemedirs;

	// Maybe Combine CSS Core On Save
	// ------------------------------
	if ($vthemesettings['combinecsscore']) {

		// reset.css or normalize.css
		$vresetoption = $vthemesettings['cssreset'];
		if ($vresetoption == 'normalize') {
			$vcssfile = skeleton_file_hierarchy('file','normalize.css',$vthemedirs['css']);
			if ($vcssfile) {$vcssreset = file_get_contents($vcssfile);}
			else {echo "<b>Warning</b>: CSS Combine could not find normalize.css<br>";}
		}
		if ($vresetoption == 'reset') {
			$vcssfile = skeleton_file_hierarchy('file','reset.css',$vthemedirs['css']);
			if ($vcssfile) {$vcssreset = file_get_contents($vcssfile);}
			else {echo "<b>Warning</b>: CSS Combine could not find reset.css<br>";}
		}

		// formalize.css
		if ($vthemesettings['loadformalize']) {
			$vcssfile = skeleton_file_hierarchy('file','formalize.css',$vthemedirs['css']);
			if ($vcssfile) {$vformalize = file_get_contents($vcssfile);}
			else {echo "<b>Warning</b>: CSS Combine could not find formalize.css<br>";}
		}

		// mobile.css (previously misnamed layout.css)
		$vcssfile = skeleton_file_hierarchy('file','mobile.css',$vthemedirs['css']);
		if ($vcssfile) {$vlayout = file_get_contents($vcssfile);}
		else {echo "<b>Warning</b>: CSS Combine could not find mobile.css<br>";}

		// 1.5.0: these stylesheets are deprecated by grid.php
		// skeleton-960.css, skeleton-1120.css, skeleton-1200.css

		// skeleton.css (note: this must be *last*, or CSS breaks!)
		$vcssfile = skeleton_file_hierarchy('file','skeleton.css',$vthemedirs['css']);
		if ($vcssfile) {$vskeleton = file_get_contents($vcssfile);}
		else {echo "<b>Warning</b>: CSS Combine could not find skeleton.css<br>";}

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
		if (file_exists($vcssfile)) {skeleton_write_to_file($vcssfile,$vcsscontents);}
	}

	// Build Selective Foundation Javascripts on Save
	// -----------------------------------------------
	// TODO: build selectives for Foundation 6, this currently only works for Foundation 5
	// $vfoundation = $vthemesettings['foundationversion'];
	$vfoundation = 'foundation5'; // currently available for Foundation 5 only

	if ($vthemesettings['loadfoundation'] == 'selective') {
		$vmessage = '';
		$vjsfile = skeleton_file_hierarchy('file','foundation.js',array('javascripts','includes/'.$vfoundation.'/js/foundation'));
		$vfoundationjs = file_get_contents($vjsfile);
		$vselected = $vthemesettings['foundationselect'];

		foreach ($vselected as $vkey => $vvalue) {
			if ($vvalue == '1') {
				$vfilename = 'foundation.'.$vkey.'.js';
				$vfoundationsourcedir = 'includes/'.$vfoundation.'/js/foundation';
				$vjsfile = skeleton_file_hierarchy('file',$vfilename,array($vfoundationsourcedir));
				if ($vjsfile) {
					$vfoundationjs .= file_get_contents($vjsfile);
					// 1.5.5: fix, use specific matching EOL to pass Theme Check
					$vfoundationjs .= '\n'.'\n';
				}
				else {$vmessage .= "<b>".__('Warning','bioship')."</b>: ".__('Foundation Javascript Combine could not find','bioship')." ".$vfilename."<br>";}
			}
		}
		// 1.8.0: added admin warning for missing resources
		if ($vmessage != '') {
			add_action('admin_notice','options_foundation_resources_warning');
			function options_foundation_resources_warning() {echo "<div class='message'>".$vmessage."</div>";}
		}

		if (strlen($vfoundation) > 0) {
			// 1.8.0: write to theme javascripts directory, not foundation/js and fix directory separator
			// also, this is written to template directory so as not to overwrite a child version
			// (as such it does not need to use WP Filesystem as the file already exists)
			// ref: http://ottopress.com/2011/tutorial-using-the-wp_filesystem/#comment-10820
			$vselectedjs = get_template_directory($vthemename).DIRSEP.'javascripts'.DIRSEP.'foundation.selected.js';
			// $vfh = f-o-p-e-n($vselectedjs,'w'); f-w-r-i-t-e($vfh,$vfoundation); f-c-l-o-s-e($vfh);
			skeleton_write_to_file($vselectedjs,$vfoundationjs);
		}
	}

	// 1.5.0: add warning if Foundation load is on, but Modernizr/Fastclick off
	// 1.6.5: made these warnings into an admin notice (and for Foundation 5 only)
	// 1.8.0: changed mind and forced loading in muscle.php - if using Foundation 5
	// if ($vthemesettings['loadfoundation'] != 'off') {
	// 	if ($vthemesettings['foundationversion'] != '6') {
	//		if ($vthemesettings['loadmodernizr'] == 'off') {
	//			add_action('admin_notice','options_foundation_modernizr_warning');
	//			function options_foundation_modernizr_warning() {
	//				echo "<div class='message'><b>".__('Warning','bioship')."</b>: ".__('Foundation is not loading as it requires Modernizr.','bioship')."</div>";
	//			}
	//		}
	//		if ($vthemesettings['loadfastclick'] != '1') {
	//			add_action('admin_notice','options_foundation_fastclick_warning');
	//			function options_foundation_fastclick_warning() {
	//				echo "<div class='message'><b>".__('Warning','bioship')."</b>: ".__('Foundation is not loading as it requires Fastclick.','bioship')."</div>";
	//			}
	//		}
	//	}
	// }
 }
}

// -----
// Note: No Theme Function Tracers added to admin.php beyond this point yet... seems pointless.


// ===================
// === Theme Tools ===
// ===================

// Backup/Restore/Import/Export Theme Settings
// 1.9.5: changed usage of 'options' to 'settings'

// note: manual querystring usage
// ?backup_theme_settingss=yes or admin-ajax.php?action=backup_theme_settings
// ?restore_theme_settings=yes
// ?export_theme_settings=yes or admin-ajax.php?action=export_theme_settings
// ?import_theme_settings=yes
// ?revert_theme_settings=yes (revert to pre-import)

// -----------------
// Theme Tools Forms
// -----------------
// for backup / restore / export / import / revert
// note: AJAX action for backup / export, post refresh for restore / import / revert
if (!function_exists('admin_theme_tools_forms')) {
 function admin_theme_tools_forms() {

	global $vthemesettings, $vthemename;
	// return; // disables tools

	// Theme Tools Javascript
	// ----------------------
	$vconfirmrestore = __('Are you sure you want to Restore the Theme Settings Backup?','bioship');
	$vconfirmimport = __('Are you sure you want to Import these Theme Settings?','bioship');
	$vconfirmrevert = __('Are you sure you want to Revert to Theme Settings prior to Import?','bioship');

	$vadminajax = admin_url('admin-ajax.php');
	$vactionurl = add_query_arg('page',$_REQUEST['page'],'admin.php');
	if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {$vactionurl = add_query_arg('theme',$_REQUEST['theme'],$vactionurl);}

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
		if (document.getElementById('exportxml').checked == '1') {exportformat = 'xml';}
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
	echo "<table><tr><td>";
		echo "<input type='button' class='button-primary' value='Backup' onclick='backupthemesettings();'>";
	echo "</td><td width='75'></td><td>";
		echo "<form action='".$vactionurl."' method='post'><input type='hidden' name='restore_theme_settings' value='yes'>";
		wp_nonce_field('restore_theme_settings_'.$vthemename);
		echo "<input type='submit' class='button-primaty' value='Restore' onclick='return confirmrestore();'></form>";
	echo "</td><td width='75'></td><td>";
		echo "&#9660;<input type='button' class='button-secondary' value='Export' onclick='togglethemebox(\"exportform\");'>";
	echo "</td><td width='75'></td><td>";
		echo "&#9660;<input type='button' class='button-secondary' value='Import' onclick='togglethemebox(\"importform\");'>";
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
	// TODO: multiple/regular auto-backup options? (unique backups only)
	// TODO: set maximum number of theme settings backups to keep (revisions?)

	// Restore Form
	// TODO: display multiple theme option backups?
	// TODO: view backup data / delete backup options

	// Export Form
	echo "<tr><td colspan='7' align='center'><div id='exportform-inside' style='display:none;'>";
		echo "<center><form><table><tr height='25'><td> </td></tr><tr><td><b>Export Format:</b></td><td width='20'></td>";
		echo "<td width='125' align='right'><input type='radio' id='exportxml' name='exportformat' checked> <b>XML</b></td><td width='50'></td>";
		echo "<td width='125'><input type='radio' id='exportjson' name='exportformat'> <b>JSON</b></td><td width='20'></td>";
		echo "<td><input type='button' class='button-primary' value='Export' onclick='exportthemesettings();'></td>";
		echo "</tr></table></form>";
	echo "</div></td></tr>";

	// Import Form
	echo "<tr><td colspan='7' align='center'><div id='importform-inside' style='display:none;'>";
		echo "<form action='".$vactionurl."' enctype='multipart/form-data' method='post' target='themetoolsframe'>";
		// add import form nonce field
		wp_nonce_field('import_theme_settings_'.$vthemename);
		echo "<input type='hidden' name='import_theme_settings' value='yes'>";
		if (THEMEDEBUG) {echo "<input type='hidden' name='themedebug' value='2'>";} // for import debugging passthrough
		echo "<table><tr height='25'><td> </td></tr><tr>";

		// select import method
		echo "<td style='vertical-align:top; line-height:12px;'><b>Import Method:<b><br><br>";
		echo "<input type='radio' id='fileuploadimport' name='importmethod' value='fileupload' onchange='switchimportmethod(\"fileupload\")' checked> <a href='javascript:void(0);' onclick='switchimportmethod(\"fileupload\");' style='text-decoration:none;'>File Upload</a><br><br>";
		echo "<input type='radio' id='textareaimport' name='importmethod' value='textarea' onchange='switchimportmethod(\"textarea\");'> <a href='javascript:void(0);' onclick='switchimportmethod(\"textarea\");' style='text-decoration:none;'>Text Area</a></td>";
		echo "<td width='20'></td>";

		// textarea import fields
		echo "<td align='center' style='vertical-align:middle;'><div id='importtextareas' style='display:none;'>";
		echo "<textarea name='importtextarea' id='importtextarea' style='width:300px; height:80px;'></textarea>";
		// echo "<div id='jsontextarea'><textarea name='themeoptionsjson' id='themeoptionsjson' style='width:300px; height:80px;'></textarea></div>";
		// echo "<div id='xmltextarea'><textarea name='themeoptionsxml' id='themeoptionsxml' style='width:300px; height:80px; display:none;'></textarea></div>";
		echo "</div></td>";

		// file upload import field
		echo "<td align='center' style='vertical-align:middle;'><div id='importfileselect' style='width:300px;'>";
		echo "Select XML or JSON Theme Options file to Import:<br><br>";
		echo "<input type='file' name='importthemeoptions' size='30'></div></td>";

		// import submit button
		echo "</td><td width='20'></td>";
		echo "<td style='vertical-align:bottom;'><input type='submit' class='button-primary' value='Import' onclick='return confirmimport();'></td></tr></table></form>";

	echo "</div></td></tr></table>";

	// Theme Tools Iframe
	// ------------------
	echo "<iframe style='display:none;' src='javascript:void(0);' name='themetoolsframe' id='themetoolsframe'></iframe>";
 }
}

// -------------------------------
// Backup / Restore Theme Settings
// -------------------------------
// via URL querystring or Theme Tools UI

// Backup Theme Options
// --------------------
if (!function_exists('admin_backup_theme_settings')) {
 function admin_backup_theme_settings() {
	$vcurrentsettings = maybe_unserialize(get_option(THEMEKEY));
	$vcurrentsettings['backuptime'] = time();
	$vbackupkey = THEMEKEY.'_user_backup';
	delete_option($vbackupkey);
	add_option($vbackupkey,$vcurrentsettings);
 }
}

// Backup Triggers
// ---------------
add_action('wp_ajax_backup_theme_settings','admin_do_backup_theme_settings');
if (!function_exists('admin_do_backup_theme_settings')) {
 function admin_do_backup_theme_settings() {
 	if (current_user_can('edit_theme_options')) {
		admin_backup_theme_settings();
		echo "<script>alert('".__('Current Theme Settings User Backup has been Created!','bioship')."');</script>";
		exit;
	}
 }
}
if (isset($_REQUEST['backup_theme_settingss'])) {
	if ($_REQUEST['backup_theme_setttings'] == 'yes') {
		if (current_user_can('edit_theme_options')) {
			admin_backup_theme_settings();
			add_action('theme_admin_notices','admin_theme_settings_backup_message');
			function admin_theme_settings_backup_message() {
				echo "<br><div class='update message'><b>".__('Current Theme Settings User Backup has been Created!','bioship')."</b></div>";
			}
		}
	}
}

// Restore Theme Settings
// ----------------------
if (!function_exists('admin_restore_theme_settings')) {
 function admin_restore_theme_settings() {
 	// 1.8.5: fix to incorrect restoretime application
 	$vcurrentsettings = maybe_unserialize(get_option(THEMEKEY));
	$vbackupkey = THEMEKEY.'_user_backup';
	$vbackupsettings = maybe_unserialize(get_option($vbackupkey));
	$vbackupsettings['restoretime'] = time();

	// switch not delete, so as to backs up 'current' options (if not empty)
	// 1.9.5: define constant to not trigger force update filter
	update_option(THEMEKEY,$vbackupsettings);

	if ( ($vcurrentsettings != '') && (is_array($vcurrentsettings)) ) {
		$vcurrentsettings['backuptime'] = time(); update_option($vbackupkey,$vcurrentsettings);
	}
	// 1.9.5: update global to continue
	global $vthemesettings;	$vthemesettings = $vbackupsettings;
 }
}

// Restore Trigger
// ---------------
if (isset($_REQUEST['restore_theme_settings'])) {
	if ($_REQUEST['restore_theme_settings'] == 'yes') {
		if (current_user_can('edit_theme_options')) {
 			// 1.8.5: added nonce check
 			global $vthemename;
 			check_admin_referer('restore_theme_settings_'.$vthemename);
 			admin_restore_theme_settings();
			add_action('theme_admin_notices','admin_theme_settings_restore_message');
			function admin_theme_settings_restore_message() {
				echo "<br><div class='message'><b>".__('Theme Settings Backup Restored! (You can switch back by using this method again.)','bioship')."</b></div>";
			}
		}
	}
}



// ----------------------------
// Export/Import Theme Settings
// ----------------------------
// 1.5.0: export/import/revert triggers
// 1.8.0: changed prefix from muscle to bioship_admin, and restorepreimport to revert

// AJAX Export Action
add_action('wp_ajax_export_theme_settings','admin_export_theme_settings');

if (isset($_REQUEST['export_theme_settings'])) {
	if ($_REQUEST['export_theme_settings'] == 'yes') {add_action('init','admin_export_theme_settings');}
}
if (isset($_POST['import_theme_settings'])) {
	if ($_POST['import_theme_settings'] == 'yes') {add_action('init','admin_import_theme_settings');}
}
if (isset($_POST['revert_theme_settings'])) {
	if ($_POST['revert_theme_settings'] == 'yes') {add_action('init','admin_revert_theme_settings');}
}

// Export Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_export_theme_options
if (!function_exists('admin_export_theme_settings')) {
 function admin_export_theme_settings() {
	if (current_user_can('edit_theme_options')) {
		global $vthemename, $vthemesettings, $vthemestyledir;

		// add the export time to the array
		$vthemesettings['exporttime'] = time();
		// print_r($vthemesettings);

		$vformat = '';
		if (isset($_REQUEST['format'])) {$vformat = $_REQUEST['format'];}
		if ($vformat == '') {$vformat = 'json';}

		// set the filename
		$vdate = date('Y-m-d--H:i:s',time());
		$vfilename = $vthemename.'--options--'.$vdate.'.'.$vformat;

		if ($vformat == 'xml') {

			// create the XML file contents
			$vxml = new SimpleXMLElement('<themeoptions/>');
			admin_array_to_xml($vxml,$vthemesettings);
			$vexportxml = $vxml->asXML();
			// print_r($vexportxml); exit;

			// also add line breaks to make it readable
			// FIXME: this is *not* working any more :-(
			// $vdom = new DOMDocument();
			// $vdom->formatOutput = true;
			// $vdom->loadXML($vexportxml);
			// $vexportxml = $vdom->saveXML();
			// print_r($vexportxml); exit;

			// for export debugging
			// $vnewthemeoptions = admin_xml_to_array($vexportxml);
			// print_r($vnewthemeoptions);
			// $vdiff = array_diff($vnewthemeoptions,$vthemesettings);
			// print_r($vdiff);

			// save generated XML file
			$vexportfile = $vthemestyledir.'debug'.DIRSEP.$vfilename;
			skeleton_write_to_file($vexportfile,$vexportxml);

			// output the XML (force download)
			header('Content-disposition: attachment; filename="'.$vfilename.'"');
			header('Content-type: text/xml');
			echo $vexportxml; exit;
		}

		if ($vformat == 'json') {
			// convert array to JSON data
			$vexportjson = json_encode($vthemesettings);

			// save generated JSON file
			$vexportfile = $vthemestyledir.'debug'.DIRSEP.$vfilename;
			skeleton_write_to_file($vexportfile,$vexportjson);

			// output the JSON (force download)
			header('Content-disposition: attachment; filename="'.$vfilename.'"');
			header('Content-type: text/json');
			echo $vexportjson; exit;
		}
	}
 }
}

// Import Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_import_theme_options
if (!function_exists('admin_import_theme_settings')) {
 function admin_import_theme_settings() {
	global $vthemename, $vthemesettings;
	if (current_user_can('edit_theme_options')) {
		// verify nonce field
		check_admin_referer('import_theme_settings_'.$vthemename);

		if ($_POST['importmethod'] == 'textarea') {
			// from textarea
			$vimportdata = stripslashes(trim($_POST['importtextarea']));
			// echo "<!--|||".$vimportdata."|||-->";
			if ( (substr($vimportdata,0,1) == '<') && (substr($vimportdata,-1,1) == '>') ) {$vformat = 'xml';}
			if ( (substr($vimportdata,0,1) == '{') && (substr($vimportdata,-1,1) == '}') ) {$vformat = 'json';}

			if ($vformat == 'json') {
				// JSON validator ref: http://stackoverflow.com/a/15198925/5240159
				// convert JSON to an array
				$vnewthemeoptions = json_decode($vimportdata,true);
				if (!$vnewthemeoptions) {$vformat = '';}
			}
			elseif ($vformat == 'xml') {
				// convert the XML to an array
				$vnewthemeoptions = admin_xml_to_array($vimportdata);
				if (!$vnewthemeoptions) {$vformat = '';}
			}
			if ($vformat == '') {
				// format not recognized error
				$vmessage = __('Failed: format not recognized. Please use valid XML or JSON only.','bioship');
				echo "<script>alert('".$vmessage."');</script>"; exit;
			}
		}
		elseif ($_POST['importmethod'] == 'fileupload') {
			echo "7";
			// from file upload
			$vverifyupload = admin_verify_file_upload('importthemeoptions');
			if (is_wp_error($vverifyupload)) {
				echo "<script>alert('Upload Error: ".$vverifyupload->getErrorMessage."');</script>";
				exit;
			} else {
				$vformat = strtolower($vverifyupload['type']);
				if (THEMEDEBUG) {echo "<!-- Uploaded File Type: ".$vformat." -->";}
				if ($vformat == 'json') {$vnewthemeoptions = json_decode($vverifyupload['data'],true);}
				if ($vformat == 'xml') {$vnewthemeoptions = admin_xml_to_array($vverifyupload['data']);}
			}
		}
		// print_r($vnewthemeoptions); exit; // manual debug point

		if (is_array($vnewthemeoptions)) {
			// add the import time to the array
			$vnewthemeoptions['importedtime'] = time();

			if (THEMEDEBUG) {
				ob_start(); print_($vnewthemeoptions); $vdebugdata = ob_get_contents();
				skeleton_write_debug_file('file-upload-import.txt',$vdebugdata); ob_end_clean();
			}

			// backup the existing options
			$vbackupkey = THEMEKEY.'_import_backup';
			delete_option($vbackupkey); add_option($vbackupkey,$vthemesettings);

			// 1.8.5: allow selective import, only override new values found
			foreach ($vnewthemeoptions as $voptionkey => $voptionvalue) {
				if ( (!is_array($voptionvalue)) || (!isset($vthemesettings[$voptionkey])) ) {
					$vthemesettings[$voptionkey] = $voptionvalue;
				} elseif (is_array($voptionvalue)) {
					foreach ($voptionvalue as $vsuboptionkey => $vsuboptionvalue) {
						$vthemesettings[$voptionkey][$vsuboptionkey] = $vsuboptionvalue;
					}
				}
			}

			// load the imported theme options
			update_option(THEMEKEY,$vthemesettings);

			// 1.9.5: theme admin message
			add_action('theme_admin_notices','admin_theme_settings_import_message');
			function admin_theme_settings_import_message() {
				echo "<br><div class='update message'><b>".__('Theme Settings have been Imported successfully!','bioship')."</b></div>";
			}
		}
	}
	// temp
	exit;
 }
}

// Revert to pre-Import Backup
// ---------------------------
// 1.8.0: renamed from muscle_restore_preimport_theme_options
if (!function_exists('function admin_revert_theme_settings')) {
 function admin_revert_theme_settings() {
	global $vthemename, $vthemesettings;
	check_admin_referer('revert_theme_settings_'.$vthemename);

	// switch the preimport backup and existing options
	$vbackupkey = THEMEKEY.'_import_backup';
	$vbackupoptions = get_option($vbackupkey);
	if ( (!empty($backupoptions)) && (is_array($vbackupoptions)) ) {
		update_option(THEMEKEY,$vbackupoptions);
		update_option($vbackupkey,$vthemesettings);
		function admin_revert_settings_message() {
			echo "<br><div class='update message'><b>".__('Pre-Import Theme Settings have been reverted.','bioship');
			echo "<br>".__('(You can switch back to the Imported Settings by using this method again.)','bioship')."</b></div>";
		}
	} else {
		function admin_revert_settings_message() {
			echo "<br><div class='update message'><b>".__('Revert Failed! The Pre-Import Theme Settings are empty or corrupt!','bioship')."</b></div>";
		}
	}
	add_action('theme_admin_notices','admin_revert_settings_message');
	// temp // exit;
 }
}

// Array to XML Function (for Export)
// ----------------------------------
// ref: http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
// answer used: http://stackoverflow.com/a/19987539/5240159
if (!function_exists('admin_array_to_xml')) {
 function admin_array_to_xml(SimpleXMLElement $vobject, array $vdata) {
    foreach ($vdata as $vkey => $vvalue) {
        if (is_array($vvalue)) {
            $vnewobject = $vobject->addChild($vkey);
            admin_array_to_xml($vnewobject,$vvalue);
        }
        else {
        	// added: htmlspecialchars
            $vobject->addChild($vkey,htmlspecialchars($vvalue));
        }
    }
 }
}

// XML to Array Function (for Import)
// ----------------------------------
// ref: http://stackoverflow.com/questions/6578832/how-to-convert-xml-into-array-in-php
if (!function_exists('admin_xml_to_array')) {
 function admin_xml_to_array($vxml) {
	$vthemesettings = json_decode(json_encode((array)simplexml_load_string($vxml)),1);
	// do htmlspecialchars_decode 2 levels deep
	// 1.8.5: okay, make that 3 levels deep then
	foreach ($vthemesettings as $vkey => $vvalue) {
		if (!is_array($vvalue)) {
			$vthemesettings[$vkey] = htmlspecialchars_decode($vvalue);
		}
		elseif ($vvalue == array()) {
			// no empty array values thanks
			$vthemesettings[$vkey] = '';
		}
		else {
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
if (!function_exists('admin_verify_file_upload')) {
 function admin_verify_file_upload($vinputkey) {
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
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $extension = array_search(
				$finfo->file($_FILES[$vinputkey]['tmp_name']),
				array(
					'xml' => 'text/xml',
					'json' => 'text/json',
				),
				true
			)) {
				throw new RuntimeException(__('Invalid file format.','bioship'));
			}
		} else {
			echo "<!-- File Mime Type: ".$_FILES[$vinputkey]['mime']." -->";
			$vpathinfo = pathinfo($_FILES[$vinputkey]['name']);
			echo "<!-- File Path Info: "; print_r($vpathinfo); echo "-->";
			$extension = $vpathinfo['extension'];
			if ( ($extension != 'json') && ($extension != 'xml') ) {
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

		$vfile['type'] = $extension;
		$vfile['data'] = file_get_contents($_FILES[$vinputkey]['tmp_name']);
		return $vfile;

	} catch (RuntimeException $e) {
		$error = $e->getMessage(); echo "<!-- ERROR: ".$error." -->";
		return new WP_Error('failed',$error);
	}
 }
}


// =================================
// === Activation / Deactivation ===
// =================================

// Save/Restore Widgets/Menus on Deactivation/Activation
// -----------------------------------------------------
// for Parent Theme as Child Theme has different code
// TODO: retest this functionality

if (!is_child_theme()) {
	$vsaverestorewidgets = true;
	$vsaverestorewidgets = apply_filters('skeleton_theme_widget_backups',$vsaverestorewidgets);

	if ($vsaverestorewidgets) {

		// Backup on Deactivation
		// ----------------------
		add_action('switch_theme', 'admin_theme_deactivation');
		function admin_theme_deactivation($vnewthemename) {
			$vsidebarswidgets = get_option('sidebars_widgets');
			delete_option('bioship_widgets_backup');
			add_option('bioship_widgets_backup',$vsidebarswidgets);

			$vmenusettings = get_option('nav_menu_options');
			delete_option('bioship_menus_backup');
			add_option('bioship_menus_backup',$vmenusettings);

			// not needed: theme mods, as they are theme specific
			// (hmmmm just how the sidebars/menus should be!)
		}

		// Restore on Activation
		// ---------------------
		add_action('after_switch_theme','admin_theme_activation');
		function admin_theme_activation() {

			$vsidebarswidgets = get_option('sidebars_widgets');
			$vmenusettings = get_option('nav_menu_options');
			$vbioshipwidgets = get_option('bioship_widgets_backup');
			$vbioshipmenus = get_option('bioship_menus_backup');
			// note: no need to restore theme mods as already theme specific

			// If there are backed up widgets/menus, restore them now
			// ..also be nice and backup the deactivated theme widgets/menus
			// (even though note these cannot be automatically restored)
			if ($vbioshipwidgets != '') {
				update_option('sidebars_widgets',$vbioshipwidgets);
				delete_option('old_theme_widgets_backup');
				add_option('old_theme_widgets_backup',$vsidebarwidgets);
			}
			if ($vbioshipmenus != '') {
				update_option('nav_menu_options',$vbioshipmenus);
				delete_option('old_theme_menus_backup');
				add_option('old_theme_menus_backup',$vmenusettings);
			}

			// Redirect to Theme Options page on activation
			global $pagenow;
			if ( (is_admin()) && (isset($_GET['activated'])) && ($pagenow == 'themes.php') ) {
				// 1.8.0: Titan Framework Conversion
				if ( (!THEMETITAN) && (THEMEOPT) ) {wp_redirect('themes.php?page=options-framework'); exit;}
				else {wp_redirect('admin.php?page=bioship-options'); exit;}
			}
		}
	}
}
else {
	// Save/Restore Child Theme Widgets/Menus on Deactivation/Activation
	// -----------------------------------------------------------------
	// 1.8.0: moved here from child theme functions.php (cleaner)
	// (note: maintain function_exists wrappers for back compat)
	$vsaverestorechildwidgets = true;
	$vsaverestorechildwidgets = apply_filters('skeleton_childtheme_widget_backups',$vsaverestorechildwidgets);

	if ($vsaverestorechildwidgets) {

		if (!function_exists('admin_get_child_theme_slug')) {
		 function admin_get_child_theme_slug() {
			$vthetheme = wp_get_theme();
			if ( (!THEMETITAN) && (THEMEOPT) ) {$vchildthemeslug = preg_replace("/\W/", "_",strtolower($vthetheme['Name']));}
			else {$vchildthemeslug = preg_replace("/\W/", "-",strtolower($vthetheme['Name']));}

			return $vchildthemeslug;
		 }
		}

		// Switch Theme Hook (on Deactivation)
		// -----------------------------------
		add_action('switch_theme', 'admin_child_theme_deactivation');
		if (!function_exists('admin_child_theme_deactivation')) {
		 function admin_child_theme_deactivation($vnewthemename) {

			// Backup Child Theme Widgets and Menus
			// ------------------------------------
			$vchildthemeslug = admin_get_child_theme_slug();
			$vsidebarswidgets = get_option('sidebars_widgets');
			delete_option($vchildthemeslug.'_widgets_backup');
			add_option($vchildthemeslug.'_widgets_backup',$vsidebarswidgets);
			$vmenusettings = get_option('nav_menu_options');
			delete_option($vchildthemeslug.'_menus_backup');
			add_option($vchildthemeslug.'_menus_backup',$vmenusettings);
		 }
		}

		// After Switch Theme Hook (on Activation)
		// ---------------------------------------
		add_action('after_switch_theme','admin_child_theme_activation');
		if (!function_exists('admin_child_theme_activation')) {
		 function admin_child_theme_activation() {

			// Restore Child Theme Widgets and Menus
			// -------------------------------------
			$vchildthemeslug = admin_get_child_theme_slug();
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
			global $pagenow; $vthemepage = '';
			if ( (is_admin()) && (isset($_GET['activated'])) && ($pagenow == 'themes.php') ) {
				if ( (!THEMETITAN) && (THEMEOPT) ) {$vthemepage = 'options-framework';}
				elseif ( (THEMETITAN) && (class_exists('TitanFramework')) ) {$vthemepage = 'bioship-options';} // $vchildthemename.'-options';
				else {$vthemepage = 'theme-info';}
				wp_redirect('themes.php?page='.$vthemepage); exit;
			}
		 }
		}
	}
}

// Fix/Deprecate? theme mods and menu locations transfer?
//	if (get_option('bioship_transfer_widgets_menus') == 'yes') {
//		$vtransferwidgets = get_option('bioship_widgets_backup');
//		update_option('sidebars_widgets',$vtransferwidgets);
//
//		$vtransfermenus = get_option('bioship_menus_backup');
//		update_option('nav_menu_options',$vtransfermenus);
//
//		$vthememods = get_option('bioship_mods_backup');
//		if (count($vthememods) > 0) {
//			foreach ($vthememods as $vthememod => $vvalue) {
//				set_theme_mod($vthememod,$vvalue);
//			}
//		}
//		// $vmenulocations = get_option('bioship_menu_locations_backup');
//		// set_theme_mod('nav_menu_locations',$vmenulocations);
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
add_action('admin_init','admin_add_theme_metabox');
if (!function_exists('admin_add_theme_metabox')) {
 function admin_add_theme_metabox() {
	if (THEMETRACE) {skeleton_trace('F','admin_add_theme_metabox',__FILE__);}
	$vcpts[0] = 'post'; $vcpts[1] = 'page';
	$vargs = array('public'=>true, '_builtin' => false);
	$vcptlist = get_post_types($vargs,'names','and');
	$vcpts = array_merge($vcpts,$vcptlist);
	foreach ($vcpts as $vcpt) {
		add_meta_box('theme_metabox', 'Theme Display Overrides', 'admin_theme_metabox', $vcpt, 'side', 'high');
	}
 }
}

// PerPage Metabox Checkboxes
// --------------------------
// 1.8.0: renamed from muscle_theme_metabox
if (!function_exists('admin_theme_metabox')) {
 function admin_theme_metabox() {
 	if (THEMETRACE) {skeleton_trace('F','admin_theme_metabox',__FILE__);}

	global $post, $vthemesettings; $vpostid = $post->ID; $vposttype = $post->post_type;

	// setup available thumbnail sizes
	if ($vposttype == 'page') {$vthumbdisplay = 'Featured Image'; $vthumbdefault = $vthemesettings['pagethumbsize'];}
	else {$vthumbdisplay = 'Thumbnail';	$vthumbdefault = $vthemesettings['postthumbsize'];}

	$vthumbarray = array(
			'thumbnail' => 'Thumbnail ('.get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h').')',
			'medium' => 'Medium ('.get_option('medium_size_w').' x '.get_option('medium_size_h').')',
			'large' => 'Large ('.get_option('large_size_w').' x '.get_option('large_size_h').')',
			'full' => 'Full Size (original)');
	$image_sizes = get_intermediate_image_sizes();
	global $_wp_additional_image_sizes;
	foreach ($image_sizes as $size_name) {
		if ( ($size_name != 'thumbnail') && ($size_name != 'medium') && ($size_name != 'large') ) {
			$vthumbarray[$size_name] = $size_name.' ('.$_wp_additional_image_sizes[$size_name]['width'].' x '.$_wp_additional_image_sizes[$size_name]['height'].')';
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
	$vthemesettingstab = get_post_meta($vpostid,'_themeoptionstab',true);
	echo "<center>";
	echo "<style>.themeoptionbutton {background-color:#E0E0EF; padding:5px; border-radius:5px;}</style>";
	echo "<table><tr>";
	echo "<td><div id='themelayoutbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'layout') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"layout\");' onclick='clickthemeoptions(\"layout\");'>Layout</a></div></td><td width='10'></td>";
	echo "<td><div id='themesidebarbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'sidebar') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"sidebar\");' onclick='clickthemeoptions(\"sidebar\");'>Sidebars</a></div></td><td width='10'></td>";
	echo "<td><div id='themecontentbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'content') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"content\");' onclick='clickthemeoptions(\"content\");'>Content</a></div></td><td width='10'></td>";
	echo "<td><div id='themestylesbutton' class='themeoptionbutton'";
	if ($vthemesettingstab == 'styles') {echo " style='background-color:#DDD;'";}
	echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"styles\");' onclick='clickthemeoptions(\"styles\");'>Styles</a></div></td><td width='10'></td>";
	echo "<td><div id='themefiltersbutton' class='themeoptionbutton'";
	// if ($vthemesettingstab == 'filters') {echo " style='background-color:#DDD;'";}
	// echo "><a href='javascript:void(0);' style='text-decoration:none;' onmouseover='maybeshowthemeoptions(\"filters\");' onclick='clickthemeoptions(\"filters\");'>Filters</a></div></td>";
	echo "</tr></table>";

	// Layout Overrides
	// ----------------
	$vdisplay = muscle_get_display_overrides($vpostid);
	$voverride = muscle_get_templating_overrides($vpostid);
	$vremovefilters = muscle_get_content_filter_overrides($vpostid);
	echo "<!-- Display Overrides: "; print_r($vdisplay); echo " -->"; // debug point
	echo "<!-- Templating Overrides: "; print_r($voverride); echo " -->"; // debug point
	echo "<!-- Filter Overrides: "; print_r($vremovefilters); echo " -->"; // debug point

	echo "<div id='themelayout'";
	if ($vthemesettingstab != 'layout') {echo " style='display:none;'";}
	echo "><table cellpadding='0' cellspacing='0'>";
	echo "<tr><td colspan='3' align='center'><b>Layout Display Overrides</b></td></tr>";
	// 1.8.5: added full width container option (no wrap margins)
	echo "<tr><td>No Wrap Margins</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_wrapper' value='1'";
		if ($vdisplay['wrapper']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Hide Header</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_header' value='1'";
		if ($vdisplay['header']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Hide Footer</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footer' value='1'";
		if ($vdisplay['footer']) {echo " checked";}  echo "></td></tr>";

	// TODO: general layout displays:
	// Header Logo / Title Text / Description / Extras
	// Footer Extras / Site Credits

	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>Navigation Display<b></td><td></td><td align='center'>Hide</td></tr>";
	echo "<tr><td>Main Nav Menu</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_navigation' value='1'";
		if ($vdisplay['navigation']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Secondary Nav Menu</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_secondarynav' value='1'";
		if ($vdisplay['secondarynav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Header Nav Menu</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_secondarynav' value='1'";
		if ($vdisplay['secondarynav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Nav Menu</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_secondarynav' value='1'";
		if ($vdisplay['secondarynav']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Breadcrumbs</td><td width='10'><td align='center'><input type='checkbox' name='_display_breadcrumb' value='1'";
		if ($vdisplay['breadcrumb']) {echo " checked";}  echo "></td></td></tr>";
	echo "<tr><td>Post/Page Navi</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_pagenavi' value='1'";
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
		'one' => __(' 1 ','bioship'), 'two' => __(' 2 ','bioship'),
		'three' => __(' 3 ','bioship'), 'four' => __(' 4 ','bioship'),
		'five' => __(' 5 ','bioship'), 'six' => __(' 6 ','bioship'),
		'seven' => __(' 7 ','bioship'), 'eight'	=> __(' 8 ','bioship'),
	);
	$vsidebarcolumns = array_merge($vsubsidebarcolumns,array(
		'nine'	=> __(' 9 ','bioship'), 'ten' => __('10 ','bioship'),
		'eleven' => __('11 ','bioship'), 'twelve' => __('12 ','bioship'),
	) );
	$vcontentcolumns = array_merge($vsidebarcolumns,array(
		'thirteen' => __('13 ','bioship'), 'fourteen' => __('14 ','bioship'),
		'fifteen' => __('15 ','bioship'), 'sixteen' => __('16 ','bioship'),
		'seventeen' => __('17 ','bioship'), 'eighteen' => __('18 ','bioship'),
		'nineteen' => __('19 ','bioship'), 'twenty' => __('20 ','bioship'),
		'twentyone' => __('21 ','bioship'), 'twentytwo' => __('22 ','bioship'),
		'twentythree' => __('23 ','bioship'), 'twentyfour' => __('24 ','bioship')
	) );
	echo "<tr><td colspan='5' align='center'><table><tr><td>Content Columns</td><td width='10'></td><td><select name='_contentcolumns'>";
		foreach ($vcontentcolumns as $vwidth => $vlabel) {echo "<option value='".$vwidth."'";
		if ($voverride['contentcolumns'] == $vwidth) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td></tr></table></td></tr>";
	echo "<tr height='10'><td> </td></tr>";

	echo "<tr><td></td><td></td><td align='center'><b>Sidebar</b></td><td></td><td align='center'><b>SubSidebar</b></td></tr>";
	echo "<tr><td align='right'>Columns</td><td width='5'></td><td><select name='_sidebarcolumns' style='width:100%;font-size:9pt;'>";
		foreach ($vsidebarcolumns as $vwidth => $vlabel) {echo "<option value='".$vwidth."'";
		if ($voverride['sidebarcolumns'] == $vwidth) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td><td width='5'></td>";
	echo "<td><select name='_subsidebarcolumns' style='width:100%;font-size:9pt;'>";
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
	echo "<td style='vertical-align:top;'><select id='sidebartemplate' name='_sidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
		foreach ($vsidebartemplates as $vtemplate => $vlabel) {echo "<option value='".$vtemplate."'";
		if ($voverride['sidebartemplate'] == $vtemplate) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select><br><div id='sidebarcustom'";
		if ($voverride['sidebartemplate'] != 'custom') {echo " style='display:none;'";}
	echo "><input type='text' name='_sidebarcustom' style='width:100%;font-size:9pt;' value='".$voverride['sidebarcustom']."'></div>";
	echo "</td><td width='5'></td>";
	echo "<td style='vertical-align:top;'><select id='subsidebartemplate' name='_subsidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
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
	echo "<tr><td align='right'>Position</td><td width='5'></td><td><select name='_sidebarposition' style='width:100%;font-size:9pt;'>";
		foreach ($vsidebarpositions as $vposition => $vlabel) {echo "<option value='".$vposition."'";
		if ($voverride['sidebarposition'] == $vposition) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td><td width='5'></td>";
	echo "<td><select name='_subsidebarposition' style='width:100%;font-size:9pt;'>";
		foreach ($vsubsidebarpositions as $vposition => $vlabel) {echo "<option value='".$vposition."'";
		if ($voverride['subsidebarposition'] == $vposition) {echo " selected='selected'";} echo ">".$vlabel."</option>";}
	echo "</select></td></tr>";
	echo "</table>";

	echo "<table cellpadding='0' cellspacing='0'><tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>Sidebar Display</b></td><td></td><td align='center'>Hide</td></tr>";
	echo "<tr><td>Main Sidebar</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_sidebar' value='1'";
		if ($vdisplay['sidebar']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>SubSidebar</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_subsidebar' value='1'";
		if ($vdisplay['subsidebar']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Header Widgets</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_headerwidgets' value='1'";
		if ($vdisplay['headerwidgets']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Widgets</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footerwidgets' value='1'";
		if ($vdisplay['footerwidgets']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Area 1</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footer1' value='1'";
		if ($vdisplay['footer1']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Area 2</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footer2' value='1'";
		if ($vdisplay['footer2']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Area 3</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footer3' value='1'";
		if ($vdisplay['footer3']) {echo " checked";}  echo "></td></tr>";
	echo "<tr><td>Footer Area 4</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_footer4' value='1'";
		if ($vdisplay['footer4']) {echo " checked";}  echo "></td></tr>";
	echo "</table></div>";

	// Content Overrides
	// -----------------
	// 1.8.0: keep individual meta key for this
	$vthumbnailsize = get_post_meta($vpostid,'_thumbnailsize',true);
	echo "<div id='themecontent'";
	if ($vthemesettingstab != 'content') {echo " style='display:none;'";}
	echo "><table cellpadding='0' cellspacing='0'>";

	// Thumbnail Size Override
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td colspan='3' align='center'><b>".$vthumbdisplay." Size</b> (default ".$vthumbdefault.")<br>";
	echo "<select name='_thumbnailsize' style='font-size:9pt;'>";
	echo "<option value=''";
	if ($vthumbnailsize == '') {echo " selected='selected'";}
	echo ">Theme Settings Default</option>";
	echo "<option value=''";
	if ($vthumbnailsize == 'off') {echo " selected='selected'";}
	echo ">No Thumbail Output</option>";
	foreach ($vthumbarray as $vkey => $vvalue) {
		echo "<option value='".$vkey."'";
		if ($vthumbnailsize == $vkey) {echo " selected='selected'";}
		echo ">".$vvalue."</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><td>Hide ".$vthumbdisplay."</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_image' value='1'";
		if ($vdisplay['image']) {echo " checked";}
	echo "></td></tr>";

	// Content Overrides
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>Content Display</b></td><td></td><td align='center'>Hide</td>";
	echo "<tr><td>Title</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_title' value='1'";
		if ($vdisplay['title']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>Subtitle</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_subtitle' value='1'";
		if ($vdisplay['subtitle']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>Top Meta</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_metatop' value='1'";
		if ($vdisplay['metatop']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>Bottom Meta</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_metabottom' value='1'";
		if ($vdisplay['metabottom']) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>Author Bio</td><td width='10'></td><td align='center'><input type='checkbox' name='_display_authorbio' value='1'";
		if ($vdisplay['authorbio']) {echo " checked";}
	echo "></td></tr>";

	// Filter Overrides
	// 1.9.5: merge to content from separate filters tab
	echo "<tr height='10'><td> </td></tr>";
	echo "<tr><td align='center'><b>Content Filter</b></td><td></td><td align='center'>Disable</td></tr>";
	echo "<tr><td>wpautop</td><td width='10'></td><td align='center'><input type='checkbox' name='_wpautop' value='1'";
		if ( (isset($vremovefilters['wpautop'])) && ($vremovefilters['wpautop'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>wptexturize</td><td width='10'></td><td align='center'><input type='checkbox' name='_wptexturize' value='1'";
		if ( (isset($vremovefilters['wptexturize'])) && ($vremovefilters['wptexturize'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>convert_smilies</td><td width='10'></td><td align='center'><input type='checkbox' name='_convertsmilies' value='1'";
		if ( (isset($vremovefilters['convertsmilies'])) && ($vremovefilters['convertsmilies'] == '1') ) {echo " checked";}
	echo "></td></tr>";
	echo "<tr><td>convert_chars</td><td width='10'></td><td align='center'><input type='checkbox' name='_convertchars' value='1'";
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
	$vperpoststyles = get_post_meta($vpostid,'_perpoststyles',true);
	echo "<div id='themestyles'";
	if ($vthemesettingstab != 'styles') {echo " style='display:none;'";}
	echo "><table  cellpadding='0' cellspacing='0' style='width:100%;overflow:visible;'>";
	echo "<tr><td colspan='2' align='center'><b>Post Specific CSS Style Rules</b></td></tr>";
	echo "<tr><td><div id='expandpostcss' style='float:left; margin-left:10px;'><a href='javascript:void(0);' onclick='expandpostcss();' style='text-decoration:none;'>&larr; Expand</a></div>";
	echo "<div id='collapsepostcss' style='float:right; margin-right:20px; display:none;'><a href='javascript:void(0);' onclick='collapsepostcss();' style='text-decoration:none;'>Collapse &rarr;</a></div></tr>";
	echo "<tr><td colspan='2'><div id='perpoststylebox' style='background:#FFF;'>";
	echo "<textarea rows='5' cols'30' name='_perpoststyles' id='perpoststyles' style='width:100%;height:200px;'>";
	echo $vperpoststyles."</textarea></div></td></tr>";
	echo "<tr><td align='center'><div id='quicksavesaved'>CSS Saved!</div></td>";
	echo "<td align='right'><input type='button' onclick='quicksavecss();' value='QuickSave CSS' class='button-secondary'></td></tr>";
	echo "</table></div>";

	// theme options current tab saver
	$vthemesettingstab = get_post_meta($vpostid,'_themeoptionstab',true);
	echo "<input type='hidden' id='themeoptionstab' name='_themeoptionstab' value='".$vthemesettingstab."'>";
	echo "<input type='hidden' id='themetabclicked' name='_themetabclicked' value=''>";

	echo "</center>";

	// 1.9.5: add quicksave perpost CSS form to footer
	add_action('admin_footer','admin_quicksave_perpost_css_form');

 }
}

// Update Metabox Hook
// -------------------
add_action('publish_post','admin_update_metabox_options');
add_action('save_post','admin_update_metabox_options');

// Update Metabox Values
// ---------------------
// 1.8.0: renamed from muscle_update_metabox_options
if (!function_exists('admin_update_metabox_options')) {
 function admin_update_metabox_options() {
	if (THEMETRACE) {skeleton_trace('F','admin_update_metabox_options',__FILE__);}

	// 1.9.8: return if post is empty
	global $post; if (!is_object($post)) {return;}
	$vpostid = $post->ID;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return $vpostid;}

	// 1.8.0: cleaner save logic here
	if ( (!current_user_can('edit_posts')) || (!current_user_can('edit_post',$vpostid)) ) {return $vpostid;}

	// 1.8.0: grouped display overrides to array
	// 1.8.5: added headernav, footernav, breadcrumbs, pagenavi
	$vdisplay = array();
	$vdisplaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);

	// 1.9.5: changed _hide prefix to _display_
	foreach ($vdisplaykeys as $vkey) {
		if (isset($_POST['_display_'.$vkey])) {
			if ($_POST['_display_'.$vkey] == '1') {$vdisplay[$vkey] = '1';} else {$vdisplay[$vkey] = '0';}
		} else {$vdisplay[$vkey] = '0';}
	}
	delete_post_meta($vpostid,'_displayoverrides');	add_post_meta($vpostid,'_displayoverrides',$vdisplay);

	// 1.9.5: added override keys
	$voverride = array();
	$voverridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);
	foreach ($voverridekeys as $vkey) {
		if (isset($_POST['_'.$vkey])) {$voverride[$vkey] = $_POST['_'.$vkey];} else {$voverride[$vkey] = '';}
	}
	delete_post_meta($vpostid,'_templatingoverrides');	add_post_meta($vpostid,'_templatingoverrides',$voverride);

	// 1.8.0: grouped filters to array
	$vremovefilters = array();
	$vfilters = array('wpautop', 'wptexturize', 'convertsmilies', 'convertchars');
	foreach ($vfilters as $vfilter) {
		if (isset($_POST['_'.$vfilter])) {
			if ($_POST['_'.$vfilter] == '1') {$vremovefilters[$vfilter] = '1';}
		}
	}
	delete_post_meta($vpostid,'_removefilters'); add_post_meta($vpostid,'_removefilters',$vremovefilters,true);

	// 1.8.0: save individual key values
	$voptionkeys = array('_perpoststyles', '_thumbnailsize', '_themeoptionstab');
	foreach ($voptionkeys as $voption) {
		$voptionvalue = $_POST[$voption]; $voptions[$voption] = $voptionvalue;
		if ($voption == '_perpoststyles') {$voptionvalue = stripslashes($voptionvalue);}
		delete_post_meta($vpostid,$voption);
		// 1.9.5: cleaner, do not save empty values
		if ($voptionvalue != '') {add_post_meta($vpostid,$voption,$voptionvalue,true);}
	}

	// for manually writing a post options debug file on save
	$vmetasavedebug = false; // $vmetasavedebug = true;
	if ($vmetasavedebug) {
		$vdebuginfo = "Override".PHP_EOL; foreach ($voverride as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Display".PHP_EOL; foreach ($vdisplay as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Filters".PHP_EOL; foreach ($vremovefilters as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		$vdebuginfo .= "Options".PHP_EOL; foreach ($voptions as $vkey => $vvalue) {$vdebuginfo .= $vkey.':'.$vvalue.PHP_EOL;}
		skeleton_write_debug_file('perpost-debug-'.$vpostid.'.txt',$vdebuginfo);
	}
 }
}

// QuickSave PerPost CSS Form
// --------------------------
// 1.9.5: added this CSS quicksave form
if (!function_exists('admin_quicksave_perpost_css_form')) {
 function admin_quicksave_perpost_css_form() {

	echo "<script>function quicksavecss() {
		oldcss = document.getElementById('pageloadperpoststyles').value;
		newcss = document.getElementById('perpoststyles').value;
		if (oldcss == newcss) {return false;}
		document.getElementById('newperpoststyles').value = newcss;
		document.getElementById('quicksave-css-form').submit();
	}
	function quicksavedshow() {
		quicksaved = document.getElementById('quicksavesaved');
		quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
	}</script>";

	global $post; $vpostid = $post->ID;
	$vperpoststyles = get_post_meta($vpostid,'_perpoststyles',true);
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
add_action('wp_ajax_quicksave_perpost_css','admin_quicksave_perpost_css');
if (!function_exists('admin_quicksave_perpost_css')) {
 function admin_quicksave_perpost_css() {
	if ( (!isset($_POST['postid'])) || (!isset($_POST['newperpoststyles'])) ) {exit;}
	$vpostid = $_POST['postid']; if ($vpostid == '') {exit;}
  	if ( (current_user_can('edit_posts')) && (current_user_can('edit_post',$vpostid)) ) {
  		$vchecknonce = check_admin_referer('quicksave_perpost_css_'.$vpostid);
	  	if ($vchecknonce) {
		  	$vnewstyles = stripslashes($_POST['newperpoststyles']);
		  	$vupdatestyles = update_post_meta($vpostid,'_perpoststyles',$vnewstyles);
		} else {$verror = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}
	} else {$verror = __('Failed. Looks like you may need to login again!','bioship');}

	if ($verror) {echo "<script>alert('".$verror."');</script>";}
	else {echo "<script>parent.quicksavedshow();</script>";}
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
		add_action('init','admin_install_titan_framework');
	}
}
// Note: Otto's Theme Plugin Dependency Class
// Ref: http://ottopress.com/2012/themeplugin-dependencies/
if (!function_exists('admin_install_titan_framework')) {
 function admin_install_titan_framework() {
	if (current_user_can('install_plugins')) {
		// TODO: extracting from a bundled zip could work even better here..?
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
	$vtgmpa = skeleton_file_hierarchy('file','class-tgm-plugin-activation.php',array('includes'));
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
// - AutoSave Net 	  (bundle?)
// - Content Sidebars (bundle?)
// - FreeStyler 	  (bundle?)
// - Hybrid Hook 	  (bundle?)

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
	add_filter('tgm_plugins_array','admin_tgm_titan_framework_check');
	if (!function_exists('admin_tgm_titan_framework_check')) {
		function admin_tgm_titan_framework_check($vplugins) {
			$vthemeupdater = skeleton_file_hierarchy('file','theme-update-checker.php',array('includes'));
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
				add_action('plugins_loaded','options_tgm_notice_shift');
				// make the notice undismissable on theme page only via config filter
				add_filter('tgm_config_array','options_tgm_dismiss_notice_off');
			}
		}
		function options_tgm_notice_shift() {
			$vid = 'bioship-tgmpa'; // TGM instance id
			if (get_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid, true)) {
				add_user_meta(get_current_user_id(), 'tgmpa_temp_notice_'.$vid, 1);
				delete_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid);
				add_action('all_admin_notices','options_tgm_notice_unshift',100);
			}
		}
		function options_tgm_notice_unshift() {
			$vid = 'bioship-tgmpa'; // TGM instance id
			delete_user_meta(get_current_user_id(), 'tgmpa_temp_'.$vid);
			add_user_meta(get_current_user_id(), 'tgmpa_dismissed_notice_'.$vid, 1);
		}
		function options_tgm_dismiss_notice_off($vconfig) {
			// filter the theme page message
			$vthememessage = '<h3>BioShip Theme Framework Recommended Plugins</h3><br>';
			$vthememessage = apply_filters('tgm_theme_page_message',$vthememessage);
			$vconfig['dismissable'] = false;
			$vconfig['dismiss_msg'] = $vthememessage;
			return $vconfig;
		}


		// Register Plugins using TGMPA
		// ----------------------------
		// 1.8.0: renamed from muscle_register_plugins
		add_action('tgmpa_register','admin_register_plugins');

		// Create an array of plugins and config
		// -------------------------------------
		// Note: see includes/tgm-examples.php for more detailed plugin examples
		// Ref: http://tgmpluginactivation.com/configuration/

		if (!function_exists('admin_register_plugins')) {
			function admin_register_plugins() {

				/*
				 * TGMPA: Array of plugin arrays. Required keys are name and slug.
				 * If the source is NOT from the .org repo, then source is also required.
				 */

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
					// TODO: maybe bundle these instead?

					// 1.9.8: remove as yet unreleased plugin
					// array(
					//	'name'      		=> 'AutoSave Net',
					//	'slug'      		=> 'autosave-net',
					//	'required'  		=> false,
					//	'source'			=> 'http://wordquest.org/downloads/packages/autosave-net.zip',
					//	'external_url' 		=> 'http://wordquest.org/plugins/autosave-net/'
					// ),

					array(
						'name'      		=> 'Content Sidebars',
						'slug'      		=> 'content-sidebars',
						'required'  		=> false,
						'source'			=> 'http://wordquest.org/downloads/packages/content-sidebars.zip',
						'external_url' 		=> 'http://wordquest.org/plugins/content-sidebars/'
					),

				);

				// Filter the TGMPA plugins
				$vplugins = apply_filters('tgm_plugins_array',$vplugins);


				/*
				 * TGMPA: Array of configuration settings. Amend each line as needed.
				 *
				 */

				// filter the TGM page message
				$vtgmpagemessage = '<h3>BioShip Theme Framework - Recommended Plugins</h3><br>';
				$vtgmpagemessage = apply_filters('tgm_plugin_page_message',$vtgmpagemessage);

				// filter the bundle path
				$vbundlespath = get_template_directory().'/plugins/';
				$vbundlespath = apply_filters('tgm_plugin_bundles_path',$vbundlespath);

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
							'This theme framework recommends the following plugin: %1$s.',
							'This theme framework recommends the following plugins: %1$s.',
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
				$vconfig = apply_filters('tgm_config_array',$vconfig);

				// Load TGM Plugin Activation!
				// ---------------------------
				tgmpa($vplugins, $vconfig);

				// Note: Originally recommended by SPML Skeleton Theme:
				// Simple Shortcodes (smpl-shortcodes)

			}
		}
	}
}

?>