<?php

// ===================
// ===== BIOSHIP =====
// = Theme Framework =
// ===================

// For more detailed information see BioShip Documentation
// available online at http://bioship.space/documentation/
// and offline by loading /wp-content/themes/bioship/admin/docs.php

// Mini Theme History
// ------------------
// Known Minimum Requirement: WordPress 3.4 (wp_get_theme)
// Original Development from: WordPress ~3.8 (from memory)
// Public Beta Version Available from: WordPress ~4.0
// Public Release Candidate Available: WordPress ~4.5
// Second Public Beta Available from: WordPress ~4.6

// -------------------------------
// === functions.php Structure ===
// -------------------------------
// - Setup Theme and load Theme Settings
// - maybe load Admin-only functions (admin.php)
// - [optional] maybe load Hybrid Core Framework
// - Require Head and Template Setup (skull.php)
// - Require Layout Hooks Definitions (hooks.php)
// - Require Skeleton Templating Functions (skeleton.php)
// - Include Muscle Extended Functions (muscle.php)
// - Dynamic Grid Loading Functions (for grid.php)
// - Dynamic Skin Loading Functions (for skin.php)

// Reference Links (see admin/links.txt for all)
// ---------------------------------------------
// Child Themes - http://codex.wordpress.org/Child_Themes
// Skeleton Theme - http://wordpress.org/themes/smpl-skeleton
// Skeleton Boilerplate - http://getskeleton.com
// Options Framework - http://wptheming.com/options-framework-plugin/
// Titan Framework - http://titanframework.net/
// Hybrid Core - http://themehybrid.com/hybrid-core
// WShadow Theme Updater - http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
// TGM Plugin Activation - https://github.com/thomasgriffin/TGM-Plugin-Activation
// Foundation - http://foundation.zurb.com/docs
// Kirki - http://github.com/aristath/kirki

// =================
// Theme Directories
// =================

// Assets
// ------
// /styles/					Theme CSS Stylesheets
// /javascripts/ 			Theme Javascripts
// /images/					Theme Images
// /languages/				Theme Languages

// Admin
// -----
// /admin/					Theme Admin Functions
// /debug/					Theme Debug Writing
// /child/					Child Theme Sources

// Templates
// ---------
// /content/ 				Content Templates
// /content/format/			Post Format Templates
// /sidebar/ 				Sidebar Templates
// /templates/				Third Party Templates

// Libraries
// ---------
// /includes/		 		Third Party Includes
// /includes/options/ 		Options Framework
// /includes/titan/ 		Titan Framework
// /includes/hybrid/ 		Hybrid Core Library
// /includes/foundation/ 	Foundation Library
// /includes/kirki/ 		Kirki Library

// ---------------
// Theme Constants
// ---------------
// BIOSHIPVERSION 	- version of this theme framework
// THEMEKEY 		- options key value for theme options
// THEMECHILD 		- if using a Child Theme
// THEMEPARENT 		- parent Theme template slug (if any)

// THEMETITAN 		- if Titan Framework is loaded
// THEMEOPT 		- if Options Framework is loaded
// THEMEDRIVE 		- if a Theme Test Drive is active
// THEMEHYBRID		- if full Hybrid Core is loaded
// THEMEKIRKI 		- if Kirki is loaded (customizer.php only)

// THEMEDEBUG 		- output debugging information comments
// THEMECOMMENTS 	- output template element comments
// THEMETRACE 		- if performing a theme argument trace
// THEMEWINDOWS 	- local environment for directory paths


// -------------
// === SETUP ===
// -------------

// set Framework Version
// ---------------------
$vbioshipversion = '1.9.7'; define('BIOSHIPVERSION', $vbioshipversion);

// set WordQuest Theme 'plugin' Info
// ---------------------------------
global $wordquestplugins;
$wordquestplugins['bioship']['version'] = BIOSHIPVERSION;
$wordquestplugins['bioship']['title'] = 'BioShip Theme';
$wordquestplugins['bioship']['wporg'] = false;
$wordquestplugins['bioship']['settings'] = 'bioship';
$wordquestplugins['bioship']['plan'] = 'free';

// define DIRECTORY_SEPARATOR short pseudonym
// ------------------------------------------
if (!defined('DIRSEP')) {$vdirsep = DIRECTORY_SEPARATOR; define('DIRSEP',$vdirsep);}

// set Global Theme Directories and URLs
// -------------------------------------
// 1.8.0: use directory separator not trailingslashit for directories
// 1.9.0: added 'theme' prefix to all global names
global $vthemestyledir, $vthemestyleurl, $vthemetemplatedir, $vthemetemplateurl;
$vthemestyledir = get_stylesheet_directory().DIRSEP;
$vthemestyleurl = trailingslashit(get_stylesheet_directory_uri());
$vthemetemplatedir = get_template_directory().DIRSEP;
$vthemetemplateurl = trailingslashit(get_template_directory_uri());
// 1.8.0: force SSL recheck
if (is_ssl()) {
	$vthemestyleurl = str_replace('http://','https://',$vthemestyleurl);
	$vthemetemplateurl = str_replace('http://','https://',$vthemetemplateurl);
}

// set Global Resource Paths
// -------------------------
// 1.8.0: used for inbuilt file hierarchy calls
// 1.8.5: set defaults and filter later on (to allow for filters.php)
// 1.9.5: add scripts and styles to top of hierarchy
global $vthemedirs; $vthemedirs['core'] = array();
$vthemedirs['admin'] = array('admin');
$vthemedirs['css'] = array('styles','css','assets/css');
$vthemedirs['js'] = array('scripts','javascripts','js','assets/js');
$vthemedirs['img'] = array('images','img','icons','assets/img');

// maybe set Helper for Windows paths
// ----------------------------------
// (may help paths on some local dev Windows IIS environments)
// TODO: improve this to an actual OS check and test?
if (!defined('THEMEWINDOWS')) {if (strstr(ABSPATH,'\\')) {define('THEMEWINDOWS',true);} else {define('THEMEWINDOWS',false);} }
if (THEMEWINDOWS) {
	$vthemetemplatedir = str_replace('/','\\',$vthemetemplatedir);
	$vthemestyledir = str_replace('/','\\',$vthemestyledir);
}

// start Load Timer
// ----------------
if (!function_exists('skeleton_timer_start')) {
 function skeleton_timer_start() {global $vthemetimestart; $vthemetimestart = microtime(true); return $vthemetimestart;}
 $vthemetimestart = skeleton_timer_start();
}

// get Current Load Time
// ---------------------
if (!function_exists('skeleton_timer_time')) {
 function skeleton_timer_time() {global $vthemetimestart; $vthemetime = microtime(true); return ($vthemetime - $vthemetimestart);}
}

// Direct File Writer
// ------------------
// 1.8.0: added this for direct file writing
if (!function_exists('skeleton_write_to_file')) {
 function skeleton_write_to_file($vfilepath,$vdata) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_write_to_file',__FILE__,func_get_args());}

 	// note: you could uncomment this line and remove dashes to bypass WP Filesystem
 	// $vfh = f-o-p-e-n($vfilepath,'w'); f-w-r-i-t-e($vfh,$vdata); f-c-l-o-s-e($vfh); return;

	// force direct-only write method using WP Filesystem
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		if (!function_exists('WP_Filesytem')) {require_once(ABSPATH.DIRSEP.'wp-admin'.DIRSEP.'includes'.DIRSEP.'file.php');}
		WP_Filesystem(); // initialize WP Filesystem
	}
	$vfiledir = dirname($vfilepath);
	$vcredentials = request_filesystem_credentials('', 'direct', false, $vfiledir, null);
	if ($vcredentials === false) {
		if (THEMEDEBUG) {echo '<!-- WP Filesystem Direct Write Method Failed. Check Your Owner/Group Permissions. -->';}
		return false; // bug out since we cannot do direct writing
	}
	$wp_filesystem->put_contents($vfilepath,$vdata,FS_CHMOD_FILE);
 }
}

// Debug File Writer
// -----------------
// 1.8.0: added this for tricky debugging output
if (!function_exists('skeleton_write_debug_file')) {
 function skeleton_write_debug_file($vfilename,$vdata) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_write_debug_file',__FILE__,func_get_args());}
	if (is_child_theme()) {global $vthemestyledir; $vdebugdir = $vthemestyledir.'debug';}
	else {global $vthemetemplatedir; $vdebugdir = $vthemetemplatedir.'debug';}
	$vdebugdir = apply_filters('skeleton_debug_dirpath',$vdebugdir);
	$vdebugfile = $vdebugdir.DIRSEP.$vfilename;
	skeleton_write_to_file($vdebugfile,$vdata);
 }
}

// Serialized Data Fixer
// ---------------------
function skeleton_fix_serialized($string) {
    // securities
    if ( !preg_match('/^[aOs]:/', $string) ) return $string;
    if ( @unserialize($string) !== false ) return $string;
    $string = preg_replace("%\n%", "", $string);
    // doublequote exploding
    $data = preg_replace('%";%', "µµµ", $string);
    $tab = explode("µµµ", $data);
    $new_data = '';
    foreach ($tab as $line) {
        $new_data .= preg_replace_callback('%\bs:(\d+):"(.*)%', 'skeleton_fix_str_length', $line);
    }
    return $new_data;
}

// Fix Serialized String Callback
// ------------------------------
function skeleton_fix_str_length($matches) {
    $string = $matches[2];
    $right_length = strlen($string); // yes, strlen even for UTF-8 characters, PHP wants the mem size, not the char count
    return 's:' . $right_length . ':"' . $string . '";';
}

// Skeleton Get Option
// -------------------
// 1.9.5: to get an option direct from database
if (!function_exists('skeleton_get_option')) {
 function skeleton_get_option($voptionkey) {
 	global $wpdb;
 	$vquery = "SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = '".$voptionkey."'";
 	$voptionvalue = $wpdb->get_var($vquery);
 	return trim($voptionvalue);
 }
}

// get Theme Settings Filter - with Fallbacks!
// -------------------------------------------
// 1.9.5: get_option filter to help bypass crazy empty settings / saving bug
// ...this may seem completely arbitrary and unecessary but it does work...

if (!function_exists('skeleton_get_theme_settings')) {
 function skeleton_get_theme_settings($vvalue,$voptionkey=false) {
 	global $vthemesettingsupdating;
 	if ($vthemesettingsupdating) {return $vvalue;}

	$vsettings = skeleton_get_option(THEMEKEY);
	// 1.9.7: fix to missing argument 2 filter warning
	if (!$voptionkey) {$voptionkey = THEMEKEY;}

	if ($vsettings) {
		if (is_serialized($vsettings)) {
			if (THEMEDEBUG) {echo "<!-- Serialized Theme Settings Found -->";}

			// $vsettings = str_replace("\r","",$vsettings);
			$vsettings = (string)$vsettings;
			$vunserialized = unserialize($vsettings);
			if ($vunserialized) {
				if (THEMEDEBUG) {echo "<!-- Unserialized Successfully -->";}
				return $vunserialized;
			} else {
				// and here is the problem... finally! yeesh.
				// sometimes the data JUST DOES NOT UNSERIALIZE - FOR NO CLEAR REASON
				// it just returns false even though the serialized data is there

				// attempt to fix serialized settings, but sometimes works sometimes not :-/
				// $vrepaired = skeleton_fix_serialized($vsettings);
				// $vfixedsettings = unserialize($vrepaired);
				// if ($vfixedsettings) {return $vfixedsettings;}

				// for now, add a filter so users can apply a custom manual fix
				$vcustomsettings = apply_filters('skeleton_theme_settings_fallback',$vsettings);
				if (is_serialized($vcustomsettings)) {
					$vunserialized = unserialize($vcustomsettings);
					if ($vunserialized) {
						if (THEMEDEBUG) {echo "<!-- Unserialized Settings from Custom Override Used -->";}
						return $vunserialized;
					}
				}

				// if (THEMEDEBUG) {
				//	echo "<!-- Unserialization Failed for Option Key ".$voptionkey." ! -->";
				//	// echo "<!-- ***"; print_r($vsettings); echo "***-->";
				//	echo "<!-- Maybe Unserialized: "; var_dump(maybe_unserialize($vsettings)); echo "-->";
				//	echo "<!-- Unserialized: "; var_dump($vunserialized); echo "-->";
				// }
			}
		}
	}

	$vforcesettings = get_transient('force_update_'.$voptionkey);
	if ($vforcesettings) {
		if (THEMEDEBUG) {echo "<!-- Checking Force Update Settings -->";}
		if (is_serialized($vforcesettings)) {
			$vunserialized = unserialize($vforcesettings);
			if (!$vunserialized) {
				$vrepaired = skeleton_fix_serialized($vforcesettings);
				$vfixedsettings = unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				skeleton_write_debug_file($voptionkey.'.txt',$vforcesettings);
				$vthemesettingsupdating = true;
				delete_option($voptionkey); add_option($voptionkey,$vunserialized);
				$vthemesettingsupdating = false;
				if (THEMEDEBUG) {echo "<!-- Force Update Settings Used and Restored -->";}
				add_action('theme_admin_notices','admin_forced_settings_restored');
				if (!function_exists('admin_forced_settings_restored')) {
				 function admin_forced_settings_restored() {
					echo "<div class='message'><b>Warning:</b> Theme Settings from Force Update Used and Restored!</div>";
				 }
				}
				return $vunserialized;
			} elseif (THEMEDEBUG) {echo "<!-- Force Update Settings could not be Unserialized -->";}
		}
	}

	$vsavedfile = get_stylesheet_directory().'/debug/'.$voptionkey.'.txt';
	// if (THEMEDEBUG) {echo "<!-- Check Backup Settings File: ".$vsavedfile." -->";}
	if (file_exists($vsavedfile)) {
		if (THEMEDEBUG) {echo "<!-- Checking Found File Settings -->";}
		$vsaveddata = file_get_contents($vsavedfile);
		if ( (strlen($vsaveddata) > 0) && (is_serialized($vsaveddata)) ) {
			$vunserialized = unserialize($vsaveddata);
			if (!$vunserialized) {
				$vrepaired = skeleton_fix_serialized($vsaveddata);
				$vfixedsettings = unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				$vthemesettingsupdating = true;
				delete_option($voptionkey); add_option($voptionkey,$vunserialized);
				$vthemesettingsupdating = false;
				if (THEMEDEBUG) {echo "<!-- File Theme Settings Used and Restored -->";}
				add_action('theme_admin_notices','admin_file_settings_restored');
				if (!function_exists('admin_file_settings_restored')) {
				 function admin_file_settings_restored() {
					echo "<div class='message'><b>Warning:</b> Theme Settings from File Settings Used and Restored!</div>";
				 }
				}
				return $vunserialized;
			} elseif (THEMEDEBUG) {echo "<!-- File Settingss could not be Unserialized -->";}
		}
	}

	$vbackupkey = $voptionkey.'_backup';
	$vbackupsettings = skeleton_get_option($vbackupkey);
	if ($vbackupsettings) {
		if (is_serialized($vbackupsettings)) {
			$vunserialized = unserialize($vbackupsettings);
			if (!$vunserialized) {
				$vrepaired = skeleton_fix_serialized($vbackupsettings);
				$vfixedsettings = unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				if (THEMEDEBUG) {echo "<!-- AutoBackup Settings Used -->";}
				if (!$vsettings) {
					$vthemesettingsupdating = true;
					delete_option($voptionkey); add_option($voptionkey,$vunserialized);
					$vthemesettingsupdating = false;
					if (THEMEDEBUG) {echo "<!-- AutoBackup Theme Settings Restored -->";}
					add_action('theme_admin_notices','admin_backup_settings_restored');
					if (!function_exists('admin_backup_settings_restored')) {
					 function admin_backup_settings_restored() {
						echo "<div class='message'><b>Error:</b> Theme Settings Empty! Existing Settings AutoBackup Restored.</div>";
					 }
					}
				}
				return $vunserialized;
			}
		}
	}

	if (THEMEDEBUG) {echo "<!-- Unserialized Theme Settings Used -->";}
	return $vsettings;
 }
}

// Skeleton File Hierarchy
// -----------------------
// ...a magical fallback mystery tour...
// 1.8.0: use DIRECTORY_SEPARATOR in all paths
// 1.8.0: switch to directory array loop instead of 2 args
// 1.8.0: added optional search roots override argument
// (added for edge cases, ie. the search for /sidebar/page.php should not find /page.php)

if (!function_exists('skeleton_file_hierarchy')) {
 function skeleton_file_hierarchy($vtype,$vfilename,$vdirs = array(),$vsearchroots = array('stylesheet','template')) {
 	// 1.6.0: we need to check if THEMETRACE constant is defined here only
	if (defined('THEMETRACE') && THEMETRACE) {skeleton_trace('F','skeleton_file_hierarchy',__FILE__,func_get_args());}

	global $vthemestyledir, $vthemestyleurl, $vthemetemplatedir, $vthemetemplateurl;

	// 1.8.5: just in case of bad argument fix
	if (!is_array($vsearchroots)) {$vsearchroots = array('stylesheet','template');}

	// 1.8.0: just use THEMEWINDOWS here instead of everywhere else...
	$vfiledirs = $vdirs; $vi = 0;
	if ( (THEMEWINDOWS) && (count($vdirs) > 0) ) {
		foreach ($vdirs as $vdir) {$vfiledirs[$vi] = str_replace('/','\\',$vdir); $vi++;}
	}

	// note: this debug not called for filters.php as too early
	if (defined('THEMEDEBUG') && THEMEDEBUG) {
		echo "<!-- File Hierarchy Call: ".$vtype." - ".$vfilename;
		if (count($vdirs) > 0) {echo " (Directories: "; echo implode(',',$vdirs); echo ")";}
		echo " -->".PHP_EOL;
	}

	// check stylesheet subdirectory(s) loop
	$vi = 0;
	if (count($vdirs) > 0) {
		foreach ($vdirs as $vdir) {
			// 1.8.0: added absolute path override possibility (prototype)
			// (to be used via resource path filters)
			if (strstr($vdir,'#')) {
				if ( (substr($vdir,0,1) == '#') && (substr($vdir,-1) == '#') ) {
					$vabsdir = substr($vdir,1,-1);
					$vfile = $vabsdir.$vfilename;
					if (file_exists($vfile)) {
						if ($vtype == 'file') {return $vfile;}
						$vurl = str_replace(ABSPATH,site_url(),$vfile);
						if ($vtype == 'url') {return $vurl;}
						if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
					}
				}
			}

			// 1.8.0: allow for root file to take precedence if specified
			if ($vdir == '') {
				$vfile = $vthemestyledir.$vfilename;
				// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
				if (file_exists($vfile)) {
					if ($vtype == 'file') {return $vfile;}
					$vurl = $vthemestyleurl.$vfilename;
					if ($vtype == 'url') {return $vurl;}
					if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
				}
			} else {
				$vfile = $vthemestyledir.$vfiledirs[$vi].DIRSEP.$vfilename;
				// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
				if (file_exists($vfile)) {
					if ($vtype == 'file') {return $vfile;}
					$vurl = trailingslashit($vthemestyleurl.$vdir).$vfilename;
					if ($vtype == 'url') {return $vurl;}
					if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
				}
			}
			$vi++;
		}
	}
	// check stylesheet directory
	if (in_array('stylesheet',$vsearchroots)) {
		$vfile = $vthemestyledir.$vfilename;
		// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
		if (file_exists($vfile)) {
			if ($vtype == 'file') {return $vfile;}
			$vurl = $vthemestyleurl.$vfilename;
			if ($vtype == 'url') {return $vurl;}
			if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
		}
	}

	// 1.8.0: bug out early if the template and stylesheet directory are the same (no child theme)
	if ($vthemestyledir == $vthemetemplatedir) {return false;}

	// check template subdirectory(s) loop
	$vi = 0;
	if (count($vdirs) > 0) {
		foreach ($vdirs as $vdir) {
			// 1.8.0: allow for root file to take precedence if specified
			if ($vdir == '') {
				$vfile = $vthemetemplatedir.$vfilename;
				// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
				if (file_exists($vthemetemplatedir.$vfilename)) {
					if ($vtype == 'file') {return $vfile;}
					$vurl = $vthemetemplateurl.$vfilename;
					if ($vtype == 'url') {return $vurl;}
					if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
				}
			} else {
				$vfile = $vthemetemplatedir.$vfiledirs[$vi].DIRSEP.$vfilename;
				// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
				if (file_exists($vfile)) {
					if ($vtype == 'file') {return $vfile;}
					$vurl = trailingslashit($vthemetemplateurl.$vdir).$vfilename;
					if ($vtype == 'url') {return $vurl;}
					if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
				}
			}
			$vi++;
		}
	}
	// check template directory
	if (in_array('template',$vsearchroots)) {
		$vfile = $vthemetemplatedir.$vfilename;
		// if (THEMEDEBUG) {echo "<!-- Search File: ".$vfile." -->";}
		if (file_exists($vthemetemplatedir.$vfilename)) {
			if ($vtype == 'file') {return $vfile;}
			$vurl = $vthemetemplateurl.$vfilename;
			if ($vtype == 'url') {return $vurl;}
			if ($vtype == 'both') {return array('file'=>$vfile, 'url'=>$vurl);}
		}
	}

	return false;
 }
}

// Theme Backwards Compatiblity
// ----------------------------
// used (sparsely) for any function/filter name changes
// 1.8.5: load theme backwards compatibility file
$vcompat = skeleton_file_hierarchy('file','compat.php');
if ($vcompat) {include_once($vcompat);}

// ----------------------
// Get Current Theme Data
// ----------------------

$vtheme = wp_get_theme();

// Theme Test Drive Determine Theme function (modified)
// ----------------------------------------------------
// (as Theme Test Drive plugin functions not loaded yet)
if (!function_exists('skeleton_themedrive_determine_theme')) {
 function skeleton_themedrive_determine_theme() {
	if (defined('THEMETRACE') && THEMETRACE) {skeleton_trace('F','skeleton_themedrive_determine_theme',__FILE__);}

	// get test drive value if any
	if (!isset($_REQUEST['theme'])) {
		$vtdlevel = get_option('td_level');
		if ($vtdlevel != '') {$vpermissions = 'level_'.$vtdlevel;} else {$vpermissions = 'level_10';}
		if (!current_user_can($vpermissions)) {return false;}
		else {
			$vtdtheme = get_option('td_themes');
			if ( (empty($vtdtheme)) || ($vtdtheme == '') ) {return false;}
		}
	} else {$vtdtheme = $_REQUEST['theme'];}

	$vthemedata = wp_get_theme($vtdtheme);

	if (!empty($vthemedata)) {
		// skip the 'publish' check here - as may be a theme under development
		// if (isset($vthemedata['Status']) && $vthemedata['Status'] != 'publish') {return false;}
		return $vthemedata;
	}

	$vallthemes = wp_get_themes();
	foreach ($vallthemes as $vthemedata) {
		if ($vthemedata['Stylesheet'] == $vtdtheme) {
			// skip the 'publish' check here - as may be a theme under development
			// if ( (isset($vthemedata['Status'])) && ($vthemedata['Status'] != 'publish') ) {return false;}
			return $vthemedata;
		}
	}

	return false;
 }
}

// Theme Test Drive Compatibility
// ------------------------------
global $pagenow;
// 1.5.0: added check that this is not a theme editor page
// as theme editor can set a theme key in the querystring
// and this conflicts with a theme test drive parameter
// TODO: recheck this behaviour on the Customizer page?

if ( ($pagenow != 'theme-editor.php') && ($pagenow != 'customize.php') ) {
	$vthemetestdrive = skeleton_themedrive_determine_theme();
	if ($vthemetestdrive) {
		define('THEMEDRIVE',true); $vtheme = $vthemetestdrive;

		// 1.8.0: override the style and template directory values
		$vthemestyledir = get_stylesheet_directory($vtheme['Stylesheet']).DIRSEP;
		$vthemestyleurl = trailingslashit(get_stylesheet_directory_uri($vtheme['Stylesheet']));
		$vthemetemplatedir = get_template_directory($vtheme['Template']).DIRSEP;
		$vthemetemplateurl = trailingslashit(get_template_directory_uri($vtheme['Template']));
		// 1.8.5: re-enforce SSL recheck
		if (is_ssl()) {
			$vthemestyleurl = str_replace('http://','https://',$vthemestyleurl);
			$vthemetemplateurl = str_replace('http://','https://',$vthemetemplateurl);
		}

		// load Admin Menu Fixes for Theme Test Driving
		add_action('admin_menu','admin_themetestdrive_options',12);

		// Set Temporary Filter for Theme Value [Options Framework only]
		// TODO: Test with Options Framework + Customizer combo
		add_filter('of_theme_value','optionsframework_themetestdrive');
		function optionsframework_themetestdrive($vthetheme) {
			// global $vthemetestdrive;
			$vthemetestdrive = skeleton_themedrive_determine_theme();
			$vthetheme['id'] = preg_replace("/\W/", "_",strtolower($vthemetestdrive['Name']));
			return $vthetheme;
		}
	}
}

// maybe Load Value Filters
// ------------------------
// *always load early* as they are used immediately if present
// checks for possible customized filters.php in parent/child theme
$vfilters = skeleton_file_hierarchy('file','filters.php',$vthemedirs['core']);
if ($vfilters) {include_once($vfilters);} // initialize filters

// Filter Resource Directories
// ---------------------------
// 1.8.5: run the directory search filters here (as could not earlier)
$vthemedirs['core'] = apply_filters('skeleton_core_dirs',$vthemedirs['core']);
$vthemedirs['admin'] = apply_filters('skeleton_admin_dirs',$vthemedirs['admin']);
$vthemedirs['css'] = apply_filters('skeleton_css_dirs',$vthemedirs['css']);
$vthemedirs['js'] = apply_filters('skeleton_js_dirs',$vthemedirs['js']);
$vthemedirs['img'] = apply_filters('skeleton_img_dirs',$vthemedirs['img']);

// set Global Theme Name and Parent/Child
// --------------------------------------
$vthemedisplayname = (string)$vtheme['Name'];
$vthemename = preg_replace("/\W/","-",strtolower($vthemedisplayname));
define('THEMEDISPLAYNAME',$vthemedisplayname);
if (is_child_theme()) {$vthemechild = true; $vthemetemplate = (string)$vtheme['Template'];}
				 else {$vthemechild = false; $vthemetemplate = false;}
define('THEMECHILD',$vthemechild); define('THEMEPARENT',$vthemetemplate);

// set Theme Debug Mode Switch
// ---------------------------
// 1.8.0: changed this to a constant, allow for switching
// 1.8.5: added all word values and new '3' option
// ?themedebug=0 or ?themedebug=off 	- switch theme debug mode off
// ?themedebug=1 or ?themedebug=on 		- switch theme debug mode on (persistant)
// ?themedebug=2 or ?themedebug=yes		- debug mode on for this pageload (overrides)
// ?themedebug=3 or ?themedebug=no 		- debug mode off for this pageload (overrides)

if (!defined('THEMEDEBUG')) {
	$vthemekey = preg_replace("/\W/","_",strtolower($vthemename));
	$vthemedebug = get_option($vthemekey.'_theme_debug');
	if ($vthemedebug == '1') {$vthemedebug = true;} else {$vthemedebug = false;}
	if (isset($_REQUEST['themedebug'])) {
		$vdebugrequest = $_REQUEST['themedebug'];
		// 1.8.5: authenticate debug capability
		// TODO: maybe filter this capability?
		if (current_user_can('edit_theme_options')) {
			if ( ($vdebugrequest == '2') || ($vdebugrequest == 'yes') ) {$vthemedebug = true;} // pageload only
			elseif ( ($vdebugrequest == '3') || ($vdebugrequest == 'no') ) {$vthemedebug = false;} // pageload only
			elseif ( ($vdebugrequest == '1') || ($vdebugrequest == 'on') ) { // switch on
				$vthemedebug = true; delete_option($vthemekey.'_theme_debug');
				add_option($vthemekey.'_theme_debug','1');
			}
			elseif ( ($vdebugrequest == '0') || ($vdebugrequest == 'off') ) { // switch off
				$vthemedebug = false; delete_option($vthemekey.'_theme_debug');
			}
		}
	}
	if ( ($vthemedebug == '') || ($vthemedebug == '0') ) {$vthemedebug = false;}
	$vthemedebug = apply_filters('skeleton_theme_debug',$vthemedebug);
	// ...and finally...
	define('THEMEDEBUG',$vthemedebug);
}

if (THEMEDEBUG) {global $pagenow; echo "<!-- Pagenow: ".$pagenow." -->";}

// maybe Load Theme Function Tracer
// --------------------------------
// 1.8.0: moved here as was loaded too early to work, refined logic
if (!defined('THEMETRACE')) {
	// 1.8.5: added querystring option for high capability
	if (isset($_REQUEST['themetrace'])) {
		$vthemetrace = $_REQUEST['themetrace'];
		if (current_user_can('manage_options')) {
			if ( ($vthemetrace == '1') || ($vthemetrace == 'yes') ) {$vthemetracer = true;}
		}
	}
	$vthemetracer = apply_filters('skeleton_theme_tracer',false);
	define('THEMETRACE',$vthemetracer);
}
if (!function_exists('skeleton_trace')) { // still overrideable
	if (THEMETRACE) {
		// $vtracer = dirname(__FILE__).DIRSEP.'admin'.DIRSEP.'tracer.php';
		$vtracer = skeleton_file_hierarchy('file','tracer.php',$vadmindirs);
		if ($vtracer) {include($vtracer);}
		else {function skeleton_trace($varg1=null,$varg2=null,$varg3=null,$varg4=null) {return;} } // dummy
	} else {function skeleton_trace($varg1=null,$varg2=null,$varg3=null,$varg4=null) {return;} } // dummy
}

// Convert Posted Customizer Preview Options
// -----------------------------------------
// 1.8.5: moved here to be available for both options frameworks
if (!function_exists('skeleton_customizer_convert_posted')) {
 function skeleton_customizer_convert_posted($vpostedvalues,$voptionvalues) {

	global $vthemeoptions;

	foreach ($vthemeoptions as $voptionkey => $voptionvalue) {
		$vpreviewkey = str_replace('_options','_customize',THEMEKEY).'['.$voptionvalue['id'].']';
		if (array_key_exists($vpreviewkey, $vpostedvalues)) {
			$vpreviewvalue = apply_filters('customize_sanitize_{$vpreviewkey}', $vpostedvalues[$vpreviewkey], array());
			// note: the third argument above should actually be a Customizer setting object?

			// !!! WARNING: ECHOING DEBUG OUTPUT IN CUSTOMIZER PREVENTS SAVING !!!

			// 1.8.5: fix to empty checkbox values
			if ($voptionvalue['type'] == 'checkbox') {
				if ($vpreviewvalue == '1') {$voptionvalues[$voptionvalue['id']] = '1';}
				else {$voptionvalues[$voptionvalue['id']] = '0';}
			}
			else {
				if (!is_array($vpreviewvalue)) {
					$voptionvalues[$voptionvalue['id']] = $vpreviewvalue;
					if (THEMEDEBUG) {echo "<!-- Preview Value for '".$voptionvalue['id']."': ".$vpreviewvalue." -->";}
				} else {
					// TODO: maybe do something else for subarray values?
					if (THEMEDEBUG) {echo "<!-- Option Type: ".$voptionvalue['type']." -->";}

					// 1.8.5: fix for customizer multicheck arrays
					if ($voptionvalue['type'] == 'multicheck') {
						foreach ($voptionvalue['options'] as $vkey => $vvalue) {
							if (in_array($vkey,$vpreviewvalue)) {$vvaluearray[$vkey] = '1';}
							else {$vvaluearray[$vkey] = '0';}
						}
						$voptionvalues[$voptionvalue['id']] = $vvaluearray;
					} else {$voptionvalues[$voptionvalue['id']] = $vpreviewvalue;}
					// echo "<!-- Preview Value for '".$voptionvalue['id']."': "; print_r($voptionvalues[$voptionvalue['id']]); echo " -->";
				}
			}
		}
	}
	return $voptionvalues;
 }
}


// maybe Load Titan or Options Framework
// -------------------------------------
// if Titan is present it is loaded, otherwise things "should" still run okay.

// 1.8.5: convert previous version file switches
// usage note: creating a titanswitch.off file will revert to Options Framework usage
// and creating a titanswitch.on file will remove Options Framework usage...
if (THEMECHILD) {$vtitanoff = $vthemestyledir.'titanswitch.off'; $vtitanon = $vthemestyledir.'titanswitch.on';}
else {$vtitanoff = $vthemetemplatedir.'titanswitch.off'; $vtitanon = $vthemetemplatedir.'titanswitch.on';}
if (file_exists($vtitanoff)) {add_option($vthemename.'_framework','options'); unlink($vtitanoff);}
elseif (file_exists($vtitanon)) {delete_option($vthemename.'_framework'); unlink($vtitanon);}

// 1.8.5: new method to check for Options Framework usage
$vthemeframework = get_option($vthemename.'_framework');
if ($vthemeframework == 'options') {$voptionsload = true; $vtitanload = false;}
else {$voptionsload = false; $vtitanload = true;} // always attempt to load Titan if present

// default load, loads Titan if present
if (!$voptionsload) {

	// maybe Load Titan Framework
	// --------------------------
	// note: hyphenated theme names eg. my-child-theme
	if (!defined('THEMEKEY')) {define('THEMEKEY',$vthemename.'_options');}

	// Titan Saving Redirect Bypass
	// ----------------------------
	// 1.8.0: crazy fix for *intermittant* bug on *some* old sites (no reason!)
	// 1.9.5: removed as alternative solution is implemented
	// TODO: recheck and deprecate this?
	// add_filter('wp_redirect','skeleton_titan_redirect_bypass',10,2);
	if (!function_exists('skeleton_titan_redirect_bypass')) {
		function skeleton_titan_redirect_bypass($vlocation, $vstatus) {
			// global $vthemename; $vpage = $vthemename.'-options';
			$vpage = 'bioship-options';
			if (strstr($vlocation,'page='.$vpage)) {
				if ( (strstr($vlocation,'message=saved')) || (strstr($vlocation,'message=reset')) ) {return false;}
			}
			return $vlocation;
		}
	}

	// 1.8.0: do a check as Titan may already active as a plugin
	// note: TitanFramework class itself is loaded after_theme_setup (so not available yet)
	if (class_exists('TitanFrameworkPlugin')) {
		// 1.8.5: use loaded Titan Framework plugin
		define('THEMETITAN',true);
	} else {
		if ($vtitanload) {
			$vtitan = skeleton_file_hierarchy('file','titan-framework-embedder.php',array('includes/titan','includes'));
			if ($vtitan) {require_once($vtitan); define('THEMETITAN',true);} // initialize Titan now
		}

		if ( (!$vtitanload) || (!$vtitan) ) {
			// lack of Titan Framework indicates it was not found
			// - possibly this is the WordPress.Org version of the theme -
			// TEMP: use Titan Checker to generate Titan Framework plugin install admin notice (via TGMPA)
			// TODO: exclude+add zip (via svn:ignore?) the /titan directory from the WordPress.Org version?

			$vtitanchecker = skeleton_file_hierarchy('file','titan-framework-checker.php',array('includes','includes/titan'));
			if ($vtitanchecker) {require_once($vtitanchecker);} // note: this also calls a unique instance of TGMPA
			else {if (THEMEDEBUG) {echo "<!-- Warning! Titan Framework Checker not found. -->";} }

			// add only Theme Info page for access to info and backup/restore/export/import tools etc.
			if (!function_exists('skeleton_add_theme_info_page')) {
			 add_action('admin_menu','skeleton_add_theme_info_page');
			 function skeleton_add_theme_info_page() {
			 	// 1.9.5: change menu name to theme tools
				add_theme_page('Theme Tools', 'Theme Tools', 'edit_theme_options', 'theme-info', 'admin_theme_info_page');
			 }
			}
		}
	}
}
else {

	// maybe Load Options Framework
	// ----------------------------
	// note: underscored theme names eg. my_child_theme
	$vthemename = str_replace('-','_',$vthemename);
	if (!defined('THEMEKEY')) {define('THEMEKEY',$vthemename);}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY,'skeleton_get_theme_settings',10,2);

	// 1.8.0: use file hierarchy here
	$voptionsframework = skeleton_file_hierarchy('file','options-framework.php',array('includes/options','options'));
	if ($voptionsframework) {
		$voptionspath = dirname($voptionsframework).DIRSEP;
		define('OPTIONS_FRAMEWORK_DIRECTORY',$voptionspath);
		require_once($voptionsframework);
		// 1.8.5: define constant for Options Framework
		define('THEMEOPT',true);
	} else {define('THEMEOPT',false);}

	// 1.8.5: fix - load the theme options array here!
	$voptions = skeleton_file_hierarchy('file','options.php');
	if ($voptions) {include($voptions);}
	else {wp_die(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship'));}
	global $vthemeoptions; $vthemeoptions = optionsframework_options();

	// get Options Framework theme settings
	$vthemesettings = get_option(THEMEKEY);

	// AutoBackup Theme Settings
	// 1.9.5: moved here for better checking
	if ( ($vthemesettings) && (!empty($vthemesettings)) && ($vthemesettings != '') && (is_array($vthemesettings)) ) {
		$vbackupkey = THEMEKEY.'_backup'; delete_option($vbackupkey); add_option($vbackupkey,$vthemesettings);
	}

	// Customizer Live Preview Values
	// ------------------------------
	// !! TODO: retest with Options Framework+Customizer !!
	global $pagenow;
	if ( (is_customize_preview()) && ($pagenow != 'customizer.php') ) {
		// !!! WARNING: DEBUG OUTPUT IN CUSTOMIZER CAN PREVENT SAVING !!!
		if (isset($_POST['customized'])) {$vpostedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($vpostedvalues)) {
			// TODO: check theme options override with preview options?
			// 1.9.5: fix to ridiculous typo bug here (, not .)
			$vthemesettings = skeleton_customizer_convert_posted($vpostedvalues,$vthemesettings);
		}
	}

	// TESTME: ? test/fix for empty checkbox/multicheck values ?
	// ...seems to be working fine now...

}

// 1.8.5: set framework constants to false if not loaded
if (!defined('THEMETITAN')) {define('THEMETITAN',false);}
if (!defined('THEMEOPT')) {define('THEMEOPT',false);}
if ( (!THEMETITAN) && (THEMEDEBUG) ) {echo "<!-- Titan Framework is OFF -->";}
if ( (!THEMEOPT) && (THEMEDEBUG) ) {echo "<!-- Options Framework is OFF -->";}

// Map Titan option values to the Theme Options
// --------------------------------------------
// 1.8.0: move because of function exits wrapper this needs to be *here* not later
// 1.8.5: use optionsarray global and make function single argument only
if (!function_exists('skeleton_titan_theme_options')) {
 function skeleton_titan_theme_options($voptionvalues) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_titan_theme_options',__FILE__,func_get_args());}

	global $vthemeoptions, $vthemename;

	// loop the options array and convert Titan to theme options array
	// TODO: cache current theme options using savetime as key?
	$vcheckboxes = array(); $vmulticheck = array();
	foreach ($vthemeoptions as $voption => $voptionvalue) {
		if ( ($voptionvalue['type'] != 'heading') && ($voptionvalue['type'] != 'info') ) {
			if (!isset($voptionvalue['id'])) {
				if (THEMEDEBUG) {echo "<!-- Whoops! Option defintion error found: "; print_r($voptionvalue); echo " -->";}
			} else {$voptionkey = $voptionvalue['id'];}

			// set to default for any unset options
			if (!isset($voptionvalues[$voptionkey])) {
				if (isset($voptionvalues['std'])) {$voptionvalues[$voptionkey] = $voptionvalue['std'];}
			}

			// fix for serialized suboption values
			if (isset($voptionvalues[$voptionkey])) {
				$vthemesettings[$voptionkey] = maybe_unserialize($voptionvalues[$voptionkey]);
			}

			// 1.8.5: fix for empty checkbox values
			if ($voptionvalue['type'] == 'checkbox') {
				// 1.9.5: fix to undefined index warning (typically for new settings)
				if ( (isset($voptionvalues[$voptionkey])) && ($voptionvalues[$voptionkey] != '1') ) {$vthemesettings[$voptionkey] = '0';}
			}

			// fix for multicheck array options
			// 1.8.5: redo fix for multicheck options
			if ($voptionvalue['type'] == 'multicheck') {
				$voptionarray = array();
				// 1.9.5: fix to undefined index (typically for new settings)
				if (isset($voptionvalues[$voptionkey])) {
					$voptionvalues[$voptionkey] = maybe_unserialize($voptionvalues[$voptionkey]);
				} else {$voptionvalues[$voptionkey] = array();}
				// if (THEMEDEBUG) {echo "**"; print_r($voptionvalues[$voptionkey]); echo "**";}
				foreach ($voptionvalue['options'] as $vkey => $vlabel) {
					$vmulticheck[$voptionkey][] = $vkey;
					// if (THEMEDEBUG) {echo "--".$voptionkey."--".$vkey."--";}
					if ( (is_array($voptionvalues[$voptionkey])) && (in_array($vkey,$voptionvalues[$voptionkey])) ) {
						// if (THEMEDEBUG) {echo "*".$voptionvalues[$voptionkey][$vkey]."*";}
						$voptionarray[$vkey] = '1';
					} else {$voptionarray[$vkey] = '0';}
				}

				// WARNING: uncommenting this debug line will prevent Customizer saving
				// TODO: maybe debug this to a file instead..?
				// if (THEMEDEBUG) {echo "<!-- ".$voptionkey; print_r($voptionarray); echo " -->";}
				$vthemesettings[$voptionkey] = $voptionarray;
			}

			// convert attachment IDs to actual image/upload URL
			if ($voptionvalue['type'] == 'upload') {
				if (is_numeric($vthemesettings[$voptionkey])) {
					$vimage = wp_get_attachment_image_src($vthemesettings[$voptionkey],'full');
					$vthemesettings[$voptionkey] = $vimage[0];
				}
			}
		}
	}
	// reset the multicheck options index for customizer saving
	delete_option($vthemename.'_multicheck_options');
	add_option($vthemename.'_multicheck_options',$vmulticheck);

	return $vthemesettings;
 }
}

// maybe Load Theme Options
// ------------------------
// 1.8.0: load options.php (if not already using Options Framework)
// note: converts all options whether Titan Framework is loaded or not
if (!THEMEOPT) {

	$vthemename = preg_replace("/\W/","-",strtolower($vthemename));
	if (!defined('THEMEKEY')) {define(THEMEKEY,$vthemename.'_options');}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY,'skeleton_get_theme_settings',10,2);

	// load the theme options array
	$voptions = skeleton_file_hierarchy('file','options.php');
	if ($voptions) {include($voptions);}
	else {wp_die(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship'));}

	// 1.9.5: fix for when transferred options manually?
	// add_filter('tf_init_no_options_'.THEMEKEY,'skeleton_titan_no_options_fix');
	// if (!function_exists('skeleton_titan_no_options_fix')) {
	//  function skeleton_titan_no_options_fix() {return maybe_unserialize(get_option(THEMEKEY));}
	// }

	// [Titan only] maybe initialize the Titan Framework Options admin page
	add_action('tf_create_options', 'skeleton_titan_create_options');
	if (!function_exists('skeleton_titan_create_options')) {
	 function skeleton_titan_create_options() {$vloadtitan = optionsframework_to_titan();}
	}

	// load options array (whether Titan class available or not)
	global $vthemeoptions; $vthemeoptions = optionsframework_options();
	$vtitansettings = maybe_unserialize(get_option(THEMEKEY));
	if (THEMEDEBUG) {echo "<!-- Titan Framework Settings: "; print_r($vtitansettings); echo " -->";}

	// 1.9.5: moved settings transfers to admin.php
	$voptionvalues = maybe_unserialize($vtitansettings);

	// Customizer Live Preview Values
	// ------------------------------
	// 1.8.5: fix for Customizer transport refresh method
	// check for the posted preview values manually as Customizer is failing to do this for us!
	// TODO: check if this is needed with Options Framework + Customizer combination

	// note: http://badfunproductions.com/use-is_customize_preview-and-not-is_admin-with-theme-customization-api/
	global $pagenow;
	if ( (is_customize_preview()) && ($pagenow != 'customize.php') ) {
		// !!! WARNING: DEBUG OUTPUT IN CUSTOMIZER CAN PREVENT SAVING !!!
		// TODO: debug these values to a file instead?
		// echo "<!-- Customize Preview Theme Options -->";
		if (isset($_POST['customized'])) {$vpostedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($vpostedvalues)) {
			// echo "<!-- Posted Preview Values: "; print_r($vpostedvalues); echo " -->";
			$voptionvalues = skeleton_customizer_convert_posted($vpostedvalues,$voptionvalues);
			// echo "<!-- Full Preview Options: "; print_r($voptionvalues); echo " -->";
		}
	}

	$vthemesettings = skeleton_titan_theme_options($voptionvalues);

	// to manually debug all theme options / values
	if (THEMEDEBUG) {
		echo "<!-- THEMEKEY: ".THEMEKEY." -->".PHP_EOL;
		// echo "<!-- Theme Options: "; print_r($vthemeoptions); echo " -->".PHP_EOL;
		echo "<!-- Option Values: "; print_r($voptionvalues); echo " -->".PHP_EOL;
		echo "<!-- Theme Settings: "; print_r($vthemesettings); echo " -->".PHP_EOL;
	}

	// AutoBackup Theme Settings
	// 1.9.5: moved here for better checking placement
	if ( ($voptionvalues) && (!empty($voptionvalues)) && ($voptionvalues != '') && (is_array($voptionvalues)) ) {
		$vbackupkey = THEMEKEY.'_backup'; delete_option($vbackupkey); add_option($vbackupkey,$vthemesettings);
	}

}

// maybe Load Theme Admin Specific Functions
// -----------------------------------------
$vloadadmin = false; if (is_admin()) {$vloadadmin = true;}
// 1.9.5: load for theme dump or if theme settings are empty to maybe force update settings
if ( ($vthemesettings == '') || (isset($_REQUEST['themedump'])) ) {$vloadadmin = true;}
// 1.8.0: allow for backup/restore/import/export theme options AJAX requests
if (isset($_REQUEST['action'])) {if (strstr($_REQUEST['action'],'_theme_options')) {$vloadadmin = true;} }
if ($vloadadmin) {$vadmin = skeleton_file_hierarchy('file','admin.php',$vthemedirs['admin']); if ($vadmin) {include($vadmin);} }

// set HTML Comment Wrapper Constant
// ---------------------------------
$vhtmlcomments = false;
if (isset($vthemesettings['htmlcomments'])) {if ($vthemesettings['htmlcomments'] == '1') {$vhtmlcomments = true;} }
$vhtmlcomments = apply_filters('skeleton_html_comments',$vhtmlcomments);
if (!defined('THEMECOMMENTS')) {define('THEMECOMMENTS',$vhtmlcomments);}

// set Theme Versions for Cache Busting
// ------------------------------------
if (!THEMECHILD) {$vthemeversion = $vchildversion = $vtheme['Version'];} // simplified
else {$vparent = $vtheme->parent(); $vthemeversion = $vparent['Version']; $vchildversion = $vtheme['Version'];}

// set Javascript Cache Busting
// ----------------------------
$vcachebust = $vthemesettings['javascriptcachebusting'];
if ( ($vcachebust == 'yearmonthdate') || ($vcachebust == '') ) {$vjscachebust = date('ymd').'0000';}
if ($vcachebust == 'yearmonthdatehour') {$vjscachebust = date('ymdH').'00';}
if ($vcachebust == 'datehourminutes') {$vjscachebust = date('ymdHi');}
if ($vcachebust == 'themeversion') {$vjscachebust = $vthemeversion;}
if ($vcachebust == 'childversion') {$vjscachebust = $vchildversion;} // simplified
if ($vcachebust == 'filemtime') {clearstatcache();} // 1.9.5: clear stat cache here

// set Stylesheet Cache Busting
// ----------------------------
$vcachebust = $vthemesettings['stylesheetcachebusting'];
if ( ($vcachebust == 'yearmonthdate') || ($vcachebust == '') ) {$vcsscachebust = date('ymd').'0000';}
if ($vcachebust == 'yearmonthdatehour') {$vcsscachebust = date('ymdH').'00';}
if ($vcachebust == 'datehourminutes') {$vcsscachebust = date('ymdHi');}
if ($vcachebust == 'themeversion') {$vcsscachebust = $vthemeversion;}
if ($vcachebust == 'childversion') {$vcsscachebust = $vchildversion;} // simplified
if ($vcachebust == 'filemtime') {clearstatcache();} // 1.9.5: clear stat cache here


// Declare WooCommerce Support
// ---------------------------
// ref: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
// WARNING: Do NOT create a woocommerce.php page template for your theme!
// This is a 'beginner mistake' as it prevents you from over-riding default templates
// such as single-product.php and archive-product.php (see class-wc-template-loader.php)
$vwoosupport = apply_filters('skeleton_declare_woocommerce_support',false);
if ($vwoosupport) {add_theme_support('woocommerce');}
else {
	// 1.8.0: auto-remove 'this theme does not declare WooCommerce support' notice
	if (class_exists('WC_Admin_Notices')) {
		if (!function_exists('skeleton_remove_woocommerce_theme_notice')) {
			add_action('admin_notices','skeleton_remove_woocommerce_theme_notice');
			function skeleton_remove_woocommerce_theme_notice() {
				$vnotices = array_diff(get_option('woocommerce_admin_notices', array()), array('theme_support'));
				update_option('woocommerce_admin_notices', $vnotices);
			}
		}
	}
}

// set Post Format Theme Supports
// ------------------------------
// note: need to declare this support before loading Hybrid Core
if ($vthemesettings['postformatsupport'] == '1') {
	$vi = 0; $vpostformatsupport = array();
	$vpostformats = $vthemesettings['postformats'];
	foreach ($vpostformats as $vpostformat => $vvalue) {
		if ($vvalue == '1') {$vpostformatsupport[$vi] = $vpostformat; $vi++;}
	}
	if (count($vpostformatsupport) > 0) {add_theme_support('post-formats',$vpostformatsupport);}
}

// maybe Output Theme Debug Info
// -----------------------------
if (THEMEDEBUG) {
	echo "<!-- ";
	if (defined('THEMEDRIVE') && THEMEDRIVE) {echo "Theme Test Drive ACTIVE".PHP_EOL;}
	echo PHP_EOL."Stylesheet - Dir: ".$vthemestyledir." - URL: ".$vthemestyleurl.PHP_EOL;
	echo PHP_EOL."Template - Dir: ".$vthemetemplatedir." - URL: ".$vthemetemplateurl.PHP_EOL;
	echo PHP_EOL."Active Theme Object: "; print_r($vtheme);
	echo PHP_EOL."Sidebars/Widget Settings: ".PHP_EOL; print_r(get_option('sidebars_widgets'));
	echo PHP_EOL."Nav Menu Settings: ".PHP_EOL; print_r(get_option('nav_menu_options'));
	echo PHP_EOL."Theme Mods: ".PHP_EOL; print_r(get_option('theme_mods_'.str_replace('_','-',$vthemename)));
	echo PHP_EOL."Theme Options (".THEMEKEY."): ".PHP_EOL; print_r($vthemesettings);
	echo " -->".PHP_EOL;
}

// Skeleton Options
// ----------------
// [deprecated] transitional from SMPL Skeleton Theme, here for for option name references...
if (!function_exists('skeleton_options')) {
 function skeleton_options($vname,$vdefault) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_options',__FILE__,func_get_args());}

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
	if ($vname == 'logotype') {$vname = 'header_logo';}
	if ($vname == 'sidebar_position') {$vname = 'page_layout';}
	if ($vname == 'layout_width') {$vname = 'layout';}

	$vvalue = $vthemesettings[$vname];
	if ($vvalue == '') {return $vdefault;}
	return (apply_filters('skeleton_options_'.$vname,$vvalue));
 }
}

// Fix to silly default timezone warning for strtotime() and date()
// Ref: http://fuel-efficient-vehicles.org/pwsdb/?p=181
// This should really not be needed as it is done in wp-settings.php anyway,
// but for some reason got a warning that this fixed, here in case ever needed..?
// add_action('plugins_loaded','skeleton_timezone_fix');
// function skeleton_timezone_fix() {@date_default_timezone_set(date_default_timezone_get());}

// this helps remove this ugly warning coming from some unknown plugins at times...
// add_filter('deprecated_constructor_trigger_error', '__return_false');


// ------------------
// === CUSTOMIZER ===
// ------------------

// Customizer API Triggers
// -----------------------
// 1.8.0: conditionally load Customizer options via customizer.php
add_action('customize_register', 'skeleton_options_customize_loader');
if (!function_exists('skeleton_options_customize_loader')) {
 function skeleton_options_customize_loader($wp_customize) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_options_customize_loader',__FILE__);}
	remove_action('customize_register','skeleton_options_customize_loader');
	global $vthemedirs;
	$vcustomizer = skeleton_file_hierarchy('file','customizer.php',$vthemedirs['admin']);
	if ($vcustomizer) {
		include_once($vcustomizer);
		// register and load controls
		options_customize_register_controls($wp_customize);
		options_customize_load_control_options($wp_customize);
		// load extra control scripts in the footer
		add_action('customize_controls_print_footer_scripts','options_customize_text_script');
		add_action('customize_controls_print_footer_scripts','options_customizer_info_script');
	}
 }
}

// Customizer Preview Window
// -------------------------
add_action('customize_preview_init','skeleton_options_customize_preview_loader');
if (!function_exists('skeleton_options_customize_preview_loader')) {
 function skeleton_options_customize_preview_loader() {
	if (!function_exists('options_customize_preview')) {
		global $vthemedirs;
		$vcustomizer = skeleton_file_hierarchy('file','customizer.php',$vthemedirs['admin']);
		if ($vcustomizer) {include_once($vcustomizer);}
	}
	if (function_exists('options_customize_preview')) {
		// load the Customizer Live Preview javascript
		add_action('wp_footer', 'options_customize_preview', 21);
	}
 }
}

// Customizer Loading Image
// ------------------------
// 1.8.5: added Kirki loading image override
// 1.9.5: moved this to Customizer Section from stylesheet loading
global $pagenow;
if ( (is_customize_preview()) && ($pagenow != 'customize.php') ) {
	add_action('wp_head','skeleton_customizer_loading_icon');
	if (!function_exists('skeleton_customizer_loading_icon')) {
	 function skeleton_customizer_loading_icon() {
		// 1.8.5: use bioship loading image
		global $vthemedirs; $vloadingimage = skeleton_file_hierarchy('url','customizer-loading.png',$vthemedirs['img']);
		if ($vloadingimage) {echo "<style>.kirki-customizer-loading-wrapper {background-image: url('".$vloadingimage."') !important;}</style>";}
	 }
	}
}

// -------------------
// === HYBRID CORE ===
// -------------------

global $vhybrid;
$vhybridloadcore = $vthemesettings['hybridloadcore'];
if ( ($vhybridloadcore == '1') || ($vhybridloadcore == '3') ) {

	// set flag for Hybrid loading
	$vhybrid = true; define('THEMEHYBRID',true);

	// 1.8.0: set hybrid core library version to use
	if ( ($vhybridloadcore == '1') || ($vhybridloadcore == '2') ) {$vhv = '2';} 	// value 1 = Hybrid Core 2 (was a checkbox)
	if ($vhybridloadcore == '3') {$vhv = '3';} 										// value 3 = Hybrid Core 3

	// load Hybrid from child or theme directory
	// (usage note: if loading from Child Theme, ALL of Hybrid Core must be there - not just hybrid.php)
	if (file_exists($vthemestyledir.'includes'.DIRSEP.'hybrid'.$vhv.DIRSEP.'hybrid.php')) {
		$vhybriddir = $vthemestyledir.'includes'.DIRSEP.'hybrid'.$vhv.DIRSEP;
	}
	elseif (file_exists($vthemetemplatedir.'includes'.DIRSEP.'hybrid'.$vhv.DIRSEP.'hybrid.php')) {
		$vhybriddir = $vthemetemplatedir.'includes'.DIRSEP.'hybrid'.$vhv.DIRSEP;
	} else {wp_die('Uh oh, the required Hybrid Core Framework is missing!');}

	$vhybridcore = $vhybriddir.'hybrid.php';

	// initialize Hybrid Core
	// ----------------------
	define('HYBRID_DIR',$vhybriddir);
	require_once($vhybridcore);
	new Hybrid();

	// Add Hybrid Core Setup Hook
	// --------------------------
	add_action('after_setup_theme', 'skeleton_hybrid_core_setup', 11);

	// 1.8.5: change function from hybrid_core_setup to skeleton_hybrid_core_setup
	if (!function_exists('skeleton_hybrid_core_setup')) {
	 function skeleton_hybrid_core_setup() {
	 	if (THEMETRACE) {skeleton_trace('F','skeleton_hybrid_core_setup',__FILE__);}

		// Hybrid Core Setup
		// -----------------

		global $vthemesettings;

		// Set content width for embeds and images
		// ---------------------------------------
		// 1.8.0: doing this at a later point now
		// $vsidebars = skeleton_set_sidebar_layout();
		// converts Skeleton content columns to width in pixels
		// $vcontentwidth = skeleton_get_content_width();
		// hybrid_set_content_width($vcontentwidth);

		// Core Features
		// -------------

		// Add Custom Template Hierarchy (!*required*!)
		add_theme_support('hybrid-core-template-hierarchy');

		// 1.8.0: for HC3 (I like backwards compatibility)
		add_theme_support('hybrid-core-deprecated');

		// Note: Hybrid Core Theme Layout and Settings (not used)
		// add_theme_support('theme-layouts');
		// add_theme_support('hybrid-core-theme-settings');

		// Hybrid Extensions
		// -----------------

		// "The best thumbnail/image script ever"
		if ($vthemesettings['hybridthumbnails'] == '1') {add_theme_support('get-the-image');}

		// Hybrid Breadcrumbs
		if ($vthemesettings['hybridbreadcrumbs'] == '1') {add_theme_support('breadcrumb-trail');}

		// Nicer [gallery] shortcode implementation
		if ($vthemesettings['hybridgallery'] == '1') {add_theme_support('cleaner-gallery');}

		// - Hybrid Shortcodes
		// deactivated shortcodes for now... is this even in HC3?
		// if ($vthemesettings['hybridshortcodes'] == '1') {add_theme_support('hybrid-core-shortcodes');}
		// - Pagination -
		// 1.8.0: removed in hybrid core v3
		// if ($vthemesettings['hybridpagination'] == '1') {add_theme_support('loop-pagination');}
		// - Better captions - for themes to style
		// note: removed in hybrid core v3
		// if ($vthemesettings['hybridcaptions'] == '1') {add_theme_support('cleaner-caption');}
		// - Per Page Featured Header image - (removed as custom_header no longer used)
		// if ($vthemesettings['hybridfeaturedheaders'] == '1') {add_theme_support('featured-header');}
		// - Random Custom Background
		// note: removed in hybrid core v3
		// if ($vthemesettings['hybridrandombackground'] == '1') {add_theme_support('random-custom-background');}
		// - Per Post Stylesheets -
		// 1.5.0: removed as handled by theme metabox
		// if ($vthemesettings['hybridpoststylesheets'] == '1') {add_theme_support('post-stylesheets');}

		// 1.3.5: wp < 3.9 fix: remove hybrid_image_size_names_choose (media.php) // >
		// causing editor footer scripts to crash prior to 3.9 (via function has_image_size)
		// ...keeping this since we are supporting from WP 3.4 and upwards...
		global $wp_version;
		if (version_compare($wp_version,'3.9','<')) { //'>
			remove_filter('image_size_names_choose','hybrid_image_size_names_choose');
		}

		// Fix: Remove the Hybrid Title Action (v2)
		// TODO: recheck this for v3 / title-tag support
		remove_action('wp_head','hybrid_doctitle',0);
	 }
	}
}
else {
	// 1.8.0: set constant flag for non-Hybrid
	$vhybrid = false; define('THEMEHYBRID',false);

	// Enable Shortcodes in Widget Text/Titles (as loading Hybrid Core adds this)
	add_filter('widget_text','do_shortcode');

	// Include only necessary Hybrid functions
	// ---------------------------------------
	// 1.8.0: use the Hybrid Core 3 versions of these files

	// Hybrid Attributes for the better/cleaner markup
	require_once($vthemetemplatedir.'includes/hybrid3-attr.php');

	// Template hierarchy to support improved template locations
	require_once($vthemetemplatedir.'includes/hybrid3-template.php');

	// General template functions here
	require_once($vthemetemplatedir.'includes/hybrid3-template-general.php');

	// 1.8.5: Load the media template so that attachments are not fatal
	// (or we could strip media functions from hybrid3-attr.php?)
	require_once($vthemetemplatedir.'includes/hybrid3-template-media.php');

	// Load utility (fix to undefined hybrid_get_menu_location_name)
	require_once($vthemetemplatedir.'includes/hybrid3-utility.php');

	// Conditional Hybrid Media Grabber Loader
	if (!function_exists('skeleton_load_hybrid_media')) {
		function skeleton_load_hybrid_media() {
			if (THEMETRACE) {skeleton_trace('F','skeleton_load_hybrid_media',__FILE__);}
			require_once($vthemetemplatedir.'includes/hybrid3-media-grabber.php');
		}
	}

	// 1.8.5: moved here from header.php to prevent duplicate if using Hybrid
	add_action('wp_head','skeleton_meta_charset',0);
	if (!function_exists('skeleton_meta_charset')) {
	 function skeleton_meta_charset() {echo '<meta charset="'.get_bloginfo('charset').'">';}
	}
	add_action('wp_head','skeleton_pingback_link',3);
	function skeleton_pingback_link() {
		if (get_option('default_ping_status') == 'open') {
			echo '<link rel="pingback" href="'.get_bloginfo('pingback_url').'">';
		}
	}

	// 1.8.0: fix to a missing function for HC3 (from functions-sidebar.php)
	if (!function_exists('hybrid_get_sidebar_name')) {
	 function hybrid_get_sidebar_name( $sidebar_id ) {
		global $wp_registered_sidebars;
		return isset( $wp_registered_sidebars[ $sidebar_id ] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : '';
	 }
	}
}

// Fix to Main Element IDs
// -----------------------
// 1.5.0: prevent duplicate element IDs via Hybrid attribute filters (eg. content and maincontent)
add_filter('hybrid_attr_header','skeleton_hybrid_attr_header', 6);
function skeleton_hybrid_attr_header($vattributes) {$vattributes['id'] = 'mainheader'; return $vattributes;}
// 1.9.0: added this one for new site-description attribute duplicate
add_filter('hybrid_attr_site-description','skeleton_hybrid_attr_site_description', 6);
function skeleton_hybrid_attr_site_description($vattributes) {$vattributes['id'] = 'site-desc'; return $vattributes;}
add_filter('hybrid_attr_content','skeleton_hybrid_attr_content', 6);
function skeleton_hybrid_attr_content($vattributes) {$vattributes['id'] = 'maincontent'; return $vattributes;}
add_filter('hybrid_attr_footer','skeleton_hybrid_attr_footer', 6);
function skeleton_hybrid_attr_footer($vattributes) {$vattributes['id'] = 'mainfooter'; return $vattributes;}


// =============
// === SKULL ===
// =============

// Load all the Skull - helpers, setup and head calculation functions
// (override pluggable skull functions in Child Theme functions.php)

$vskull = skeleton_file_hierarchy('file','skull.php');
require_once($vskull);


// ================
// === SKELETON ===
// ================

// 1.9.0: load hooks.php before skeleton.php
$vhooks = skeleton_file_hierarchy('file','hooks.php');
require_once($vhooks);

// Load all the Skeleton - layout and template tag functions
// (override pluggable skeleton functions in Child Theme functions.php)

$vskeleton = skeleton_file_hierarchy('file','skeleton.php');
require_once($vskeleton);


// ==============
// === MUSCLE ===
// ==============

// Load all the Muscle - extended theme functions
// (override pluggable muscle functions in Child Theme functions.php)

$vmuscle = skeleton_file_hierarchy('file','muscle.php');
if ($vmuscle) {require_once($vmuscle);}


// ============
// === SKIN ===
// ============
// note: skin.php is used for outputting the Dynamic CSS
// but all the Skin trigger and enqueueing functions here

// 1.8.5: fix to admin script enqueue typo - argh seriously?
if (is_admin()) {add_action('admin_enqueue_scripts','skin_enqueue_admin_styles');}
else {add_action('wp_enqueue_scripts','skin_enqueue_styles');}

// Frontend Stylesheets
// --------------------
if (!function_exists('skin_enqueue_styles')) {
 function skin_enqueue_styles() {
	if (THEMETRACE) {skeleton_trace('F','skin_enqueue_styles',__FILE__);}

	global $vthemename, $vthemesettings, $vcsscachebust, $vthemedirs;

	$vfilemtime = false; if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// Combined Stylesheet
	// -------------------
	if ($vthemesettings['combinecsscore']) {
		$vcorestyles = skeleton_file_hierarchy('both','core-styles.css',$vthemedirs['css']);
		if (!is_array($vcorestyles)) {$vcombinefail = true;}
		else {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vcorestyles['file']));}
			wp_register_style('core-styles', $vcorestyles['url'], array(), $vcsscachebust);
			wp_enqueue_style('core-styles'); $vcoredep = array('core-styles');
		}

		// theme style.css (note: must be separate or CSS breaks)
		if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime(get_stylesheet($vthemename)));}
		wp_enqueue_style('bioship-style', get_stylesheet_uri($vthemename), $vcoredep, $vcsscachebust);

	}

	// or Individual Stylesheets
	// -------------------------
	if ( (!$vthemesettings['combinecsscore']) || ($vcombinefail) ) {

		$vcoredep = array(); $vmaindep = array(); $vdep = array();

		// Normalize or Reset CSS
		// ----------------------
		if ($vthemesettings['cssreset'] == 'normalize') {
			$vnormalize = skeleton_file_hierarchy('both','normalize.css',$vthemedirs['css']);
			if (is_array($vnormalize)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vnormalize['file']));}
				wp_register_style('normalize', $vnormalize['url'], array(), $vcsscachebust);
				wp_enqueue_style('normalize'); $vmaindep = array('normalize');
			}
		}
		elseif ($vthemesettings['cssreset'] == 'reset') {
			$vreset = skeleton_file_hierarchy('both','reset.css',$vthemedirs['css']);
			if (is_array($vreset)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vreset['file']));}
				wp_register_style('reset', $vreset['url'], array(), $vcsscachebust);
				wp_enqueue_style('reset'); $vmaindep = array('reset');
			}
		}
		elseif ( ($vthemesettings['cssreset'] == 'none') || ($vthemesettings['cssreset'] == '') ) {$vmaindep = array();}

		// Layout Stylesheets
		// 1.5.0: these have been entirely replaced with dynamic grid
		// (skeleton-960, skeleton-1140, skeleton-1200)

		// Dynamic Grid Stylesheet
		// -----------------------

		// 1.8.0: allow for direct load method
		// 1.5.0: added dynamic grid stylesheet
		if ($vthemesettings['themecssmode'] == 'adminajax') {
			$vgridurl = admin_url('admin-ajax.php').'?action=grid_dynamic_css';
		} else {$vgridurl = skeleton_file_hierarchy('url','grid.php');}

		// 1.8.0: pass content width for calculating content grid
		// 1.8.5: fix to grid URL query separater for admin ajax method
		// TODO: use wp_localize_script for variables instead?
		global $vthemelayout;
		if (strstr($vgridurl,'?')) {$vgridurl .= '&';} else {$vgridurl .= '?';}
		// 1.9.5: pass raw content width (with padding not yet removed)
		$vgridurl .= 'contentwidth='.$vthemelayout['rawcontentwidth'];
		// 1.9.0: pass filtered layout variables
		$vgridurl .= '&gridcolumns='.$vthemelayout['gridcolumns'];
		$vgridurl .= '&maxwidth='.$vthemelayout['maxwidth'];
		// 1.9.5: pass filtered content columns and raw content padding
		$vgridurl .= '&contentgridcolumns='.$vthemelayout['contentgridcolumns'];
		$vcontentpadding = apply_filters('skeleton_raw_content_padding',$vthemesettings['contentpadding']);
		$vgridurl .= '&contentpadding='.urlencode($vcontentpadding);

		// TODO: pass filtered options for grid spacing? (padding/margins)
		// $vgridurl .= '&gridspacing='.$vthemelayout['gridspace'];
		// $vgridurl .= '&contentspacing='.$vthemelayout['contentspace'];

		// 1.8.5: set theme anyway to allow for Multiple Themes usage
		$vgridtheme = get_option('stylesheet');
		if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {$vgridtheme = $_REQUEST['theme'];}
		$vgridurl = add_query_arg('theme',$vgridtheme,$vgridurl);

		// 1.8.5: maybe use last option save time for cache busting
		if ($vfilemtime) {
			$vtime = time();
			if ( (isset($vthemesettings['savetime'])) && ($vthemesettings['savetime'] != '') ) {$vtime = $vthemesettings['savetime'];}
			$vcsscachebust = date('YmdHi',$vtime);
		}

		// Load grid.php directly or via admin-ajax.php
		// --------------------------------------------
		wp_enqueue_style('grid', $vgridurl, $vmaindep, $vcsscachebust);

		// Grid URL for Customizer Preview
		// -------------------------------
		if ( (is_customize_preview()) && ($pagenow != 'customize.php') ) {

			// For Customizer Preview Load in Header/Footer
			// ...but somehow doing this breaks Customizer ..?
			//	if ($vthemesettings['themecssmode'] == 'header') {
			//		add_action('wp_head','skeleton_grid_dynamic_css_inline');
			//	} else {add_action('wp_footer','skeleton_grid_dynamic_css_inline');}

			add_action('customize_preview_init','skeleton_grid_url_reference');
			if (!function_exists('skeleton_grid_url_reference')) {
			 function skeleton_grid_url_reference() {
				global $vgridurl; echo '<a href="'.$vgridurl.'" id="grid-url" style="display:none;"></a>';
			 }
			}
		}

		// mobile.css
		// ----------
		// 1.5.0: changed from layout.css (misleading name)
		$vmobile = skeleton_file_hierarchy('both','mobile.css',$vthemedirs['css']);
		if (is_array($vmobile)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vmobile['file']));}
			wp_enqueue_style('mobile',$vmobile['url'], $vmaindep, $vcsscachebust);
		}

		// Main Theme Stylesheets
		// ----------------------

		// Formalize.css
		// -------------
		if ($vthemesettings['loadformalize']) {
			$vformalize = skeleton_file_hierarchy('both','formalize.css',$vthemedirs['css']);
			if (is_array($vformalize)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vformalize['file']));}
				wp_enqueue_style('formalize',$vformalize['url'], $vmaindep, $vcsscachebust, 'screen, projection');
			}
		}

		// skeleton.css
		// ------------
		$vskeletoncss = skeleton_file_hierarchy('both','skeleton.css',$vthemedirs['css']);
		if (is_array($vskeletoncss)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vskeletoncss['file']));}
			wp_enqueue_style('skeleton-style', $vskeletoncss['url'], $vmaindep, $vcsscachebust);
			$vdep = array('skeleton-style');
		}

		// style.css
		// ---------
		$vdep = array_merge($vmaindep,$vdep);
		if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime(get_stylesheet($vthemename)));}
		wp_enqueue_style('bioship-style', get_stylesheet_uri($vthemename), $vdep, $vcsscachebust);

	}

	// Superfish menu
	// --------------
	$vsuperfish = skeleton_file_hierarchy('both','superfish.css',$vthemedirs['css']);
	if (is_array($vsuperfish)) {
		if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vsuperfish['file']));}
		wp_enqueue_style('superfish',$vsuperfish['url'], $vcoredep, $vcsscachebust, 'screen, projection');
	}

	// custom.css
	// ----------
	// auto-loaded CSS (only if file is found)
	$vcustomcss = skeleton_file_hierarchy('both','custom.css',$vthemedirs['css']);
	if (is_array($vcustomcss)) {
		if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vcustomcss['file']));}
		wp_enqueue_style('custom-css', $vcustomcss['url'], $vcoredep, $vcsscachebust);
	}

	// Hybrid Cleaner Gallery
	// ----------------------
	if (current_theme_supports('cleaner-gallery')) {
		// 1.8.0: use Hybrid version-specific CSS, added missing cachebuster
		if ($vthemesettings['hybridloadcore'] == '3') {$vhybriddir = 'hybrid3';} else {$vhybriddir = 'hybrid2';}
		$vgallerycss = skeleton_file_hierarchy('both','gallery.css',array($vhybriddir.'/css','css'));
		if (is_array($vgallerycss)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vgallerycss['file']));}
			wp_enqueue_style('gallery', $vgallerycss['url'], array(), $vcsscachebust);
		}
	}

	// Enqueue Dynamic Stylesheet Skin
	// -------------------------------
	// 1.8.0: moved here for better enqueueing
	// 1.5.0: tested direct skin loader option for performance
	$vcssmode = $vthemesettings['themecssmode'];
	if ($vcssmode == 'adminajax') {$vskinurl = admin_url('admin-ajax.php').'?action=skin_dynamic_css';}
	elseif ( ($vcssmode == 'direct') || ($vcssmode == '') ) {$vskinurl = skeleton_file_hierarchy('url','skin.php',$vthemedirs['core']);}

	// 1.8.5: set anyway to allow for Multiple Themes/Theme Test Drive override
	$vskintheme = get_stylesheet();
	if (isset($_REQUEST['theme'])) {if ($_REQUEST['theme'] != '') {$vskintheme = $_REQUEST['theme'];} }
	$vskinurl = add_query_arg('theme',$vskintheme,$vskinurl);
	// if (strstr($vskinurl,'?')) {$vskinurl .= '&';} else {$vskinurl .= '?';}
	// $vskinurl .= 'theme='.$vskintheme;

	// 1.8.0: allow for header/footer inline page load
	// 1.8.5: fix to wrap header/footer output in style tags!
	if ($vthemesettings['themecssmode'] == 'header') {add_action('wp_head','skin_dynamic_css_inline');}
	elseif ($vthemesettings['themecssmode'] == 'footer') {add_action('wp_footer','skin_dynamic_css_inline');}
	else {
		if ($vfilemtime) {
			$vtime = time();
			// 1.8.5: maybe use last theme options saved time for cachebusting
			if ( (isset($vthemesettings['savedtime'])) && ($vthemesettings['savetime'] != '') ) {$vtime = $vthemesettings['savetime'];}
			$vcsscachebust = date('ymdHi',$vtime);
		}
		wp_enqueue_style('skin', $vskinurl, array(), $vcsscachebust);
	}

	// 1.9.5: disable emojis option
	if ( (isset($vthemesettings['disablemojis'])) && ($vthemesettings['disableemojis'] == '1') ) {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');
	}

	// deregister WP PageNavi Style
	// ----------------------------
	// (native style support via SMPL skeleton.css styles)
	if (!function_exists('skeleton_deregister_styles')) {
		function skeleton_deregister_styles() {wp_deregister_style('wp-pagenavi');}
		add_action('wp_print_styles','skeleton_deregister_styles',100);
	}

	// Typography Heading Fonts
	// ------------------------
	do_action('skin_typography');
 }
}

// Enqueue Admin Stylesheets
// -------------------------
if (!function_exists('skin_enqueue_admin_styles')) {
 function skin_enqueue_admin_styles() {
	global $vthemesettings, $vthemedirs;

	// Dynamic Admin Stylesheet
	// ------------------------
	if ($vthemesettings['dynamicadmincss'] != '') {

		// TODO: maybe add separate option for admin styles loading mode?
		$vcssmode = $vthemesettings['themecssmode'];

		if ($vcssmode == 'adminajax') {$vskinurl = admin_url('admin-ajax.php').'?action=skin_dynamic_admin_css';}
		elseif ($vcssmode == 'direct') {
			$vskinurl = skeleton_file_hierarchy('url','skin.php',$vthemedirs['core']);
			// 1.8.5: set admin styles load via querystring
			$vskinurl = add_query_arg('adminstyles','yes',$vskinurl);
		}

		// 1.8.5: use add_query_arg here
		$vskintheme = get_stylesheet();
		if (isset($_REQUEST['theme'])) {if ($_REQUEST['theme'] != '') {$vskintheme = $_REQUEST['theme'];} }
		$vskinurl = add_query_arg('theme',$vskintheme,$vskinurl);
		// if (strstr($vskinurl,'?')) {$vskinurl .= '&';} else {$vskinurl .= '?';}
		// $vskinurl .= 'theme='.$_REQUEST['theme'];

		// 1.8.5: wrap in style for inline header/footer printing
		if ($vthemesettings['themecssmode'] == 'header') {add_action('admin_head','skin_dynamic_admin_css_inline');}
		elseif ($vthemesettings['themecssmode'] == 'footer') {add_action('admin_footer','skin_dynamic_admin_css_inline');}
		else {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$vtime = time(); // $vtime = $vthemesettings['savetime'];
				$vcsscachebust = date('ymdHi',$vtime);
			}
			wp_enqueue_style('admin-skin', $vskinurl, array(), $vcsscachebust);
		}
	}

	// Formalize.css
	// -------------
	if ($vthemesettings['loadformalize']) {
		$vformalize = skeleton_file_hierarchy('both','formalize.css',$vthemedirs['css']);
		if (is_array($vformalize)) {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$vcsscachebust = date('ymdHi',filemtime($vformalize['file']));
			}
			wp_enqueue_style('formalize',$vformalize['url'], $vmaindep, $vcsscachebust, 'screen, projection');
		}
	}

	// 1.9.5: disable emojis option
	if ( (isset($vthemesettings['disablemojis'])) && ($vthemesettings['disableemojis'] == '1') ) {
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('admin_print_styles', 'print_emoji_styles');
	}

	// 1.9.5: for dynamic editor styles, maybe enqueue Google fonts for post writing / editing pages
	if ( (isset($vthemesettings['dynamiceditorstyles'])) && ($vthemesettings['dynamiceditorstyles'] == '1') ) {
		global $pagenow; if ( ($pagenow == 'post.php') || ($pagenow == 'edit.php') ) {do_action('skin_typography');}
	}

 }
}

// Heading Typography
// ------------------
// autoload any requested fonts from Google Fonts...
// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (see muscle.php)

if (!function_exists('skin_typography_loader')) {
 add_action('skin_typography','skin_typography_loader');
 function skin_typography_loader() {
	if (THEMETRACE) {skeleton_trace('F','skin_typography_loader',__FILE__);}

	global $vthemesettings;

	// Handle Extra Font options
	// -------------------------
	$vi = 0;
	if ($vthemesettings['extrafonts'] != '') {
		if (strstr($vthemesettings['extrafonts'],',')) {
			$vextrafonts = explode(',',$vthemesettings['extrafonts']);
		} else {$vextrafonts[0] = $vthemesettings['extrafonts'];}
		foreach ($vextrafonts as $vextrafont) {
			$vextrafont = trim($vextrafont);
			$vthisstyle = 'normal';
			if (strstr($vextrafont,':')) {
				$vfontparts = explode(':',$vextrafont);
				$vthisface = $vfontparts[0];
				if ($vfontparts[1] == 'b') {$vthisstyle = 'bold';}
				if ($vfontparts[1] == 'i') {$vthisstyle = 'italics';}
				if ($vfontparts[1] == 'bi') {$vthisstyle = 'bolditalics';}
			}
			else {$vthisface = $vextrafont;}
			$vthisfont = array('face'=>$vthisface,'style'=>$vthisstyle);
			$vfonts['extra'.$vi] = $vthisfont; $vi++;
		}
	}
	// print_r($vfonts); // debug point

	// 1.9.0: check for Raleway default in body/section font stacks...
	$vbodyfonts = array('body','header','navmenu','navsubmenu','sidebar','subsidebar','content','footer','button');
	foreach ($vbodyfonts as $vbodyfont) {
		$vthisfont = $vthemesettings[$vbodyfont.'_typography'];
		if (isset($vthisfont['face'])) {$vthisface = $vthisfont['face'];}
		elseif (isset($vthisfont['font-family'])) {$vthisface = $vthisfont['font-family'];}
		if (substr($vthisface,0,strlen('"Raleway"')) == '"Raleway"') {
			$vthisfont['face'] = 'Raleway';	$vfonts['extra'.$vi] = $vthisfont; break;
		}
	}

	// Heading Typography
	// ------------------
	// 1.9.0: loop heading fonts
	$vheadingfonts = array('h1','h2','h3','h4','h5','h6');
	foreach ($vheadingfonts as $vheadingfont) {
		$vfonts[$vheadingfont] = $vthemesettings[$vheadingfont.'_typography'];
	}

	// 1.9.0: updated to match new separate text display options
	if ($vthemesettings['header_text']['sitetitle'] == '1') {$vfonts['headline'] = $vthemesettings['headline_typography'];}
	if ($vthemesettings['header_text']['sitedescription'] == '1') {$vfonts['tagline'] = $vthemesettings['tagline_typography'];}

	// print_r($vfonts); // debug point

	// Autoload selected fonts, avoiding duplicates
	// --------------------------------------------
	$vprotocol = is_ssl() ? 'https' : 'http'; $vqueried = array();
	$vi = $vj = $vk = 1;
	foreach ($vfonts as $vfontkey => $vfont) {
		// 1.8.0: fix for Titan Framework options
		if (!isset($vfont['face'])) {$vfont['face'] = $vfont['font-family'];}
		$vstyle = ''; $vthisface = strtolower($vfont['face']);
		// echo $vfontkey.'---'.$vfont['face'].'---'.$vfont['style']; // debug point
		// 1.8.0: filter out possible font stack requests
		// (load via extra fonts option if using a title font stack)
		if ( ($vthisface != '') && (!strstr($vthisface,','))
		  && (strtolower($vthisface) != 'sans-serif') && (strtolower($vthisface) != 'serif') ) {
			// 1.5.0: fix to replace all whitespace for custom font
			// $vfontface = preg_replace('/\s+/', '%20', $vfont['face']);
			// $vfontface = str_replace('+','%20',$vfontface);
			// 1.8.0: actually better to use + instead of %20 ?!
			$vfontface = preg_replace('/\s+/', '+', $vfont['face']);

			// 1.8.0: fix for Titan Framework options
			if (!isset($vfont['style'])) {$vfont['style'] = $vfont['font-style'];}
			if ($vfont['style'] == 'bold') {$vstyle = ':b';}
			if ($vfont['style'] == 'bolditalics') {$vstyle = ':bi';}
			if ( ($vfont['style'] == 'italics') || ($vfont['style'] == 'italic') ) {
				$vstyle = ':i';
				// TESTME: this may need some better logic?
				if ( (isset($vfont['font-weight'])) && ($vfont['font-weight'] == 'bold') ) {$vstyle = ':bi';}
			}

			$vquery = $vfontface.$vstyle;
			$vqueryargs = array('family' => $vquery);
			if (!in_array($vquery,$vqueried)) {
				if (in_array($vfontkey,$vheadingfonts)) {$vfontstackkey = 'heading-font-'.$vi; $vi++;}
				else {$vfontstackkey = 'custom-font-'.$vj; $vj++;}
				wp_enqueue_style($vfontstackkey,add_query_arg($vqueryargs, $vprotocol."://fonts.googleapis.com/css"), array(), null);
				$vk++; $vqueried[$vk] = $vquery;
			}
		}
	}
	// print_r($vqueried); // debug point
 }
}

// AJAX Skin CSS Loader
// --------------------
add_action('wp_ajax_skin_dynamic_css', 'skin_dynamic_css');
add_action('wp_ajax_nopriv_skin_dynamic_css', 'skin_dynamic_css');

if (!function_exists('skin_dynamic_css')) {
 function skin_dynamic_css() {
 	if (THEMETRACE) {skeleton_trace('F','skin_dynamic_css',__FILE__);}
 	global $vthemedirs;
	$vloadskin = skeleton_file_hierarchy('file','skin.php',$vthemedirs['core']);
	if ($vloadskin) {require($vloadskin);}
 }
}
// 1.8.5: for printing inline header/footer styles
if (!function_exists('skin_dynamic_css_inline')) {
 function skin_dynamic_css_inline() {echo "<style id='dynamic-styles'>"; skin_dynamic_css(true); echo "</style>";}
}

// AJAX Grid CSS Loader
// --------------------
// 1.5.0: added dynamic grid stylesheet
add_action('wp_ajax_grid_dynamic_css', 'skeleton_grid_dynamic_css');
add_action('wp_ajax_nopriv_grid_dynamic_css', 'skeleton_grid_dynamic_css');

// 1.8.5: added optional included argument
if (!function_exists('skeleton_grid_dynamic_css')) {
 function skeleton_grid_dynamic_css($vincluded = false) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_grid_dynamic_css',__FILE__);}
	global $vthemedirs;
	$vloadgrid = skeleton_file_hierarchy('file','grid.php',$vthemedirs['core']);
	require($vloadgrid);
 }
}
// 1.8.5: for printing inline header/footer styles
if (!function_exists('skeleton_grid_dynamic_css_inline')) {
 function skeleton_grid_dynamic_css_inline() {echo "<style id='grid-styles'>"; skeleton_grid_dynamic_css(); echo "</style>";}
}

// AJAX Admin CSS Loader
// ---------------------
add_action('wp_ajax_skin_dynamic_admin_css', 'skin_dynamic_admin_css');
add_action('wp_ajax_nopriv_skin_dynamic_admin_css', 'skin_dynamic_admin_css');

// 1.8.5: added optional login style argument
if (!function_exists('skin_dynamic_admin_css')) {
 function skin_dynamic_admin_css($vloginstyles = false) {
	if (THEMETRACE) {skeleton_trace('F','skin_dynamic_admin_css',__FILE__);}
	global $vthemedirs; $vadminstyles = true; // admin-only styles switch
	$vloadskin = skeleton_file_hierarchy('file','skin.php',$vthemedirs['core']);
	if ($vloadskin) {include($vloadskin);}
 }
}
// 1.8.5: for printing inline header/footer styles
if (!function_exists('skin_dynamic_admin_css_inline')) {
 function skin_dynamic_admin_css_inline() {echo "<style id='dynamic-admin-styles'>"; skin_dynamic_admin_css(); echo "</style>";}
}
// 1.8.5: for printing inline login-only styles
if (!function_exists('skin_dynamic_login_css_inline')) {
 function skin_dynamic_login_css_inline() {echo "<style id='dynamic-login-styles'>"; skin_dynamic_admin_css(true); echo "</style>";}
}

// ---------------
// === UPDATES ===
// ---------------

// WShadow Theme Update Checker
// ----------------------------
// note: checks presence of /includes/theme-update-checker.php
// this indicates a version downloaded from http://bioship.space - not WordPress.org
add_action('init','skeleton_theme_update_checker');
if (!function_exists('skeleton_theme_update_checker')) {
 function skeleton_theme_update_checker() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_theme_update_checker',__FILE__);}
	if (is_admin()) {
		// TODO: exclude (via svn:ignore?) this file from the WordPress.Org theme version on SVN repo
		// 1.8.0: use file hierarchy for theme update checker
		$vthemeupdater = skeleton_file_hierarchy('file','theme-update-checker.php',array('includes'));
		if ($vthemeupdater) {
			require_once($vthemeupdater);
			// 1.5.0: use custom theme update server location
			$vjsoninfo = 'http://bioship.space/download/?action=get_metadata&slug=bioship';
			// $vjsoninfo = 'http://bioship.space/download/version.json'; // for 1.4.5 to 1.5.0 only
			$vupdatechecker = new ThemeUpdateChecker('bioship', $vjsoninfo);
			// 1.8.5: add default sidebar option values
			if (!is_array(get_option('bioship_sidebar_options'))) {
				$vsidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => '', 'installdate' => date('Y-m-d'));
				add_option('bioship_sidebar_options',$vsidebaroptions);
			}
		} else {
			// 1.8.0: default sidebar ads to off for WordPress.Org guideline compliance
			// 1.8.5: switched this to single option array value
			if (!is_array(get_option('bioship_sidebar_options'))) {
				$vsidebaroptions = array('reportboxoff' => '', 'donationboxoff' => '', 'adsboxoff' => 'checked', 'installdate' => date('Y-m-d'));
				add_option('bioship_sidebar_options',$vsidebaroptions);
			}

			// 1.8.5: maybe disable directory clearing to keep installed theme bundles
			// ref: http://wordpress.stackexchange.com/a/228798/76440
			// add_filter('upgrader_package_options', 'skeleton_avoid_deletion', 999);
			if (!function_exists('skeleton_avoid_deletion')) {
			 function skeleton_avoid_deletion($voptions) {
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
		}
	}
 }
}

// Fully shipped bruz.

?>