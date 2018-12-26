<?php

// ===================
// ===== BIOSHIP =====
// = Theme Framework =
// ===================

// BioShip HomePage: http://bioship.space
// Support and Features: http://wordquest.org/support/bioship/
// GitHub Repository: https://github.com/majick777/bioship/

// For more detailed information see BioShip Documentation:
// Available online at http://bioship.space/documentation/
// or locally by loading /wp-content/themes/bioship/admin/docs.php

// Mini Theme History
// ------------------
// Known Minimum Requirement: WordPress 3.4 (wp_get_theme)
// Original Development from: WordPress ~3.8
// Public Beta Version Available from: WordPress ~4.0
// Public Release Candidate Available: WordPress ~4.5
// Second Public Beta Available from: WordPress ~4.6
// Hotfixed Public Version: WordPress ~4.7
// WordPress.Org Submission Version: WordPress ~4.8
// Latest Update News: http://bioship.space/news/


// -------------------------------
// === functions.php Structure ===
// -------------------------------
// - [optional] pre-load of Child Theme functions (child functions.php)
// - setup Theme Values and load Freemius SDK
// - load Helper and Debugging Functions
// - maybe backwards compatibility (compat.php)
// - load Theme Data (and maybe Theme Test Drive)
// - maybe load Child Theme Value Filters (filters.php)
// - set Debug Mode and maybe load Theme Tracer (tracer.php)
// - load Titan/Options Framework and Theme Settings

// - maybe load Admin-only functions (admin.php)
// - [optional] load Hybrid Core Framework (hybrid.php)
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
// see documentation for more details

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
// /includes/hybridX/ 		Hybrid Core Library (2 or 3)
// /includes/foundationX/ 	Foundation Library (5 or 6)
// /includes/kirkiX/ 		Kirki Library (2 or 3)


// ---------------
// Theme Constants
// ---------------
// see documentation for more details

// THEMEPREFIX		- theme function prefix (bioship)
// THEMEVERSION 	- current version of BioShip Framework

// THEMESSL			- if the current protocol is SSL
// THEMEWINDOWS 	- local environment for directory paths

// THEMEHOMEURL		- static URL of the BioShip website
// THEMESUPPORT		- static URL of Support website (WordQuest)

// THEMESLUG		- lowercase (dashed) current theme name
// THEMEKEY 		- options key value for theme options
// THEMECHILD 		- if using a Child Theme
// THEMEPARENT 		- parent Theme template slug (if any)
// THEMECHILDVERSION - Child Theme version (or parent if no child)

// THEMETITAN 		- if Titan Framework is loaded
// THEMEOPT 		- if Options Framework is loaded
// THEMEDRIVE 		- if a Theme Test Drive is active
// THEMEHYBRID		- if full Hybrid Core is loaded
// THEMEKIRKI 		- if Kirki is loaded (customizer.php only)

// THEMEDEBUG 		- output debugging information comments
// THEMETRACE 		- if performing a theme argument trace
// THEMECOMMENTS 	- output template element comments


// -------------
// === SETUP ===
// -------------

// define DIRECTORY_SEPARATOR short pseudonym
// ------------------------------------------
if (!defined('DIRSEP')) {define('DIRSEP', DIRECTORY_SEPARATOR);}

// set Framework Version
// ---------------------
// 2.0.1: get theme version direct from style.css
$vstylecsspath = dirname(__FILE__).DIRSEP.'style.css';
$vthemeheaders = get_file_data($vstylecsspath, array('Version' => 'Version'));
define('THEMEVERSION', $vthemeheaders['Version']);
// 2.0.5: set theme prefix constant
if (!defined('THEMEPREFIX')) {define('THEMEPREFIX', 'bioship');}

// set WordQuest Theme 'plugin' Info
// ---------------------------------
global $wordquestplugins; $vslug = THEMEPREFIX;
$wordquestplugins[$vslug]['version'] = THEMEVERSION;
$wordquestplugins[$vslug]['title'] = __('BioShip Theme','bioship');
$wordquestplugins[$vslug]['settings'] = THEMEPREFIX;
$wordquestplugins[$vslug]['plan'] = 'free';
$wordquestplugins[$vslug]['hasplans'] = false;
$wordquestplugins[$vslug]['wporg'] = false;
// $wordquestplugins[$vslug]['wporgslug'] = $vslug;

// 2.0.5: check for theme-update-checker.php and maybe reset compliance
if ( (file_exists(dirname(__FILE__).'/includes/theme-update-checker.php'))
  || (file_exists(dirname(__FILE__).'/theme-update-checker.php')) ) {
  	$wordquestplugins['bioship']['wporg'] = false;
}

// set Framework URLs
// ------------------
// 2.0.0: added BioShip Home URL constant
// 2.0.1: change constant name for consistency
define('THEMEHOMEURL', 'http://bioship.space');
// 2.0.1: added support forum website URL
define('THEMESUPPORT', 'http://wordquest.org');
// 2.0.1: set Theme SSL constant also
if (!defined('THEMESSL')) {$vthemessl = is_ssl(); define('THEMESSL', $vthemessl);}

// set Global Theme Directories and URLs
// -------------------------------------
// 1.8.0: use directory separator not trailingslashit for directories
// 1.9.0: added 'theme' prefix to all global names
global $vthemestyledir, $vthemestyleurl, $vthemetemplatedir, $vthemetemplateurl;
$vthemestyledir = get_stylesheet_directory().DIRSEP;
$vthemestyleurl = trailingslashit(get_stylesheet_directory_uri());
$vthemetemplatedir = get_template_directory().DIRSEP;
$vthemetemplateurl = trailingslashit(get_template_directory_uri());
// 1.8.0: enforce SSL recheck of theme URLs
if (THEMESSL) {
	$vthemestyleurl = str_replace('http://', 'https://',$vthemestyleurl);
	$vthemetemplateurl = str_replace('http://', 'https://',$vthemetemplateurl);
}

// set Global Resource Paths
// -------------------------
// 1.8.0: used for inbuilt file hierarchy calls
// 1.8.5: set defaults and filter later on (to allow for filters.php)
// 1.9.5: add scripts and styles to top of hierarchy
global $vthemedirs; $vthemedirs['core'] = array();
$vthemedirs['admin'] = array('admin');
$vthemedirs['style'] = array('styles', 'css', 'assets/css');
$vthemedirs['script'] = array('scripts', 'javascripts', 'js', 'assets/js');
$vthemedirs['image'] = array('images', 'img', 'icons', 'assets/img');


// ------------------------
// === Helper Functions ===
// ------------------------

// negative return Helper
// ----------------------
// 2.0.7: added this helper
if (!function_exists('bioship_return_negative')) {
 function bioship_return_negative() {return -1;}
}

// dummy Function Helper
// ---------------------
// 2.0.9: added this helper
if (!function_exists('bioship_dummy_function')) {
 function bioship_dummy_function() {}
}

// maybe set Helper for Windows paths
// ----------------------------------
// (may help paths on some local dev Windows IIS environments)
if (!defined('THEMEWINDOWS')) {
	// 2.0.9: use operating system check for Windows paths
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {define('THEMEWINDOWS', true);}
	else {define('THEMEWINDOWS', false);}
}
if (THEMEWINDOWS) {
	$vthemetemplatedir = str_replace('/', '\\' ,$vthemetemplatedir);
	$vthemestyledir = str_replace('/', '\\', $vthemestyledir);
}

// start Load Timer
// ----------------
if (!function_exists('bioship_timer_start')) {
 function bioship_timer_start() {
 	global $vthemetimestart; $vthemetimestart = microtime(true); return $vthemetimestart;
 }
 $vthemetimestart = bioship_timer_start();
}

// get Current Load Time
// ---------------------
if (!function_exists('bioship_timer_time')) {
 function bioship_timer_time() {
 	global $vthemetimestart; $vthemetime = microtime(true); return ($vthemetime - $vthemetimestart);
 }
}

// get Remote IP Address
// ---------------------
if (!function_exists('bioship_get_remote_ip')) {
 function bioship_get_remote_ip() {
 	// TODO: replace with more accurate IP detection
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {$vip = $_SERVER['HTTP_CLIENT_IP'];}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {$vip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
	else {$vip = $_SERVER['REMOTE_ADDR'];}
	return $vip;
 }
}

// Theme Debug Output
// ------------------
// 2.0.9: added separate debug output/logging function
if (!function_exists('bioship_debug')) {
 function bioship_debug($vprefix, $vdata=NULL, $vforceoutput=NULL, $vforcelog=NULL) {
 	if (!defined('THEMEDEBUG') || !THEMEDEBUG) {return;}

 	// maybe display debug output
 	$voutput = true;
 	if (defined('THEMEDEBUGOUTPUT') && !THEMEDEBUGOUTPUT) {$voutput = false;}
 	if (!is_null($vforceoutput)) {$voutput = $vforceoutput;}
	if ($voutput) {
 		echo "<!-- [Theme Debug] ".$vprefix;
 		if (!is_null($vdata)) {
 			echo ": ";
			if ( (is_array($vdata)) || (is_object($vdata)) ) {print_r($vdata);}
			elseif (is_string($vdata)) {echo $vdata;}
	 	}
 		echo " -->".PHP_EOL;
 	}

	// 2.0.9: check querystring for setting of debug instance
	if ( (!defined('THEMEDEBUGINSTANCE')) && (isset($_REQUEST['instance'])) ) {
 		define('THEMEDEBUGINSTANCE', $_REQUEST['instance']);
	}

 	// maybe log debug output
 	$vlog = false;
 	if (defined(THEMEDEBUGLOG) && THEMEDEBUGLOG) {$vlog = true;}
 	if (!is_null($vforcelog)) {$vlog = $vforcelog;}
	if ($vlog) {
 		$vlogline = '';

 		// theme debug log info constant (define for once only logging)
 		if (!defined('THEMEDEBUGLOGINFO')) {
 			$vlogline = PHP_EOL."Theme Debug Output";
 			if (defined('THEMEDEBUGINSTANCE') && THEMEDEBUGINSTANCE) {
 				$vlogline .= " Instance '".THEMEDEBUGINSTANCE."'";
 			}
 			$vlogline .= PHP_EOL.'['.date('j m Y H:i:s', time()).'] ';
 			// 2.0.9: add IP address to theme debug info log line
 			$vlogline .= '[IP '.bioship_get_remote_ip().'] ';
 			define('THEMEDEBUGLOGINFO', true);
 		}

		$vlogline .= $vprefix;
		if (!is_null($vdata)) {
			if (is_string($vdata)) {$vlogline .= ": ".$vdata;}
			elseif ( (is_array($vdata)) || (is_object($vdata)) ) {
				$vlogline .= ": ".PHP_EOL;
				// 2.0.9: removed unneeded output buffering
				$vlogline .= print_r($vdata, true);
			}
		}
 		$vlogline .= PHP_EOL;

		if ($vlogline != '') {
			$vfilename = 'theme_debug.log';
			// 2.0.9: allow setting of alternative filename for single debug instance
			if (defined('THEMEDEBUGINSTANCE') && THEMEDEBUGINSTANCE) {
				$vfilename = THEMEDEBUGINSTANCE.'_'.$filename;
			}
			$vfilename = bioship_apply_filters('debug_filename', $vfilename);
			bioship_write_debug_file($vfilename, $vlogline);
		}
 	}

 }
}


// Get File Contents
// -----------------
// 2.0.7: added file_get_contents alternative wrapper (for Theme Check)
if (!function_exists('bioship_file_get_contents')) {
 function bioship_file_get_contents($vfilepath) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	if (!file_exists($vfilepath)) {return '';}

	// attempt to use WP filesystem
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		if (!function_exists('WP_Filesytem')) {
			$vfilesystem = ABSPATH.DIRSEP.'wp-admin'.DIRSEP.'includes'.DIRSEP.'file.php';
			require_once($vfilesystem);
		}
		// initialize WP Filesystem
		WP_Filesystem();
	}
	$vcontents = $wp_filesystem->get_contents($vfilepath);
	if ($vcontents) {return $vcontents;}
	else {
		// fallback to using file() to read the file
		$vfilearray = @file($vfilepath);
		if (!$vfilearray) {return '';}
		$vcontents = implode('', $vfilearray);
		return $vcontents;
	}
 }
}

// Direct File Writer
// ------------------
// 1.8.0: added this for direct file writing
// 2.0.9: added append method since WP Filesystem does not have one
if (!function_exists('bioship_write_to_file')) {
 function bioship_write_to_file($vfilepath, $vdata, $vappend=false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// force direct-only write method using WP Filesystem
	global $wp_filesystem;
	if (empty($wp_filesystem)) {
		if (!function_exists('WP_Filesytem')) {
			// 2.0.9: fix to double trailing slash on WP filesystem path
			$vfilesystem = ABSPATH.'wp-admin'.DIRSEP.'includes'.DIRSEP.'file.php';
			require_once($vfilesystem);
		}
		// initialize WP Filesystem
		WP_Filesystem();
	}
	$vfiledir = dirname($vfilepath);
	$vcredentials = request_filesystem_credentials('', 'direct', false, $vfiledir, null);
	if ($vcredentials === false) {
		bioship_debug("WP Filesystem Direct Write Method Failed. Check Owner/Group Permissions.");
		// bug out since we cannot do direct writing
		return false;
	}

	// 2.0.9: bizarrely, WP Filesystem has no append method
	if ($vappend) {
		$vcontents = $wp_filesystem->get_contents($vfilepath);
		$vdata = $contents.PHP_EOL.$vdata;
	}
	$wp_filesystem->put_contents($vfilepath, $vdata, FS_CHMOD_FILE);
 }
}

// Debug File Writer
// -----------------
// 1.8.0: added this for tricky debugging output
// 1.9.8: use debug directory global here
if (!function_exists('bioship_write_debug_file')) {
 function bioship_write_debug_file($vfilename, $vdata) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemedebugdir;
	$vthemedebugdir = bioship_check_create_debug_dir();
	$vdebugfile = $vthemedebugdir.DIRSEP.$vfilename;
	// 2.0.9: use new append writing method for debug data
	$vwritedebug = bioship_write_to_file($vdebugfile, $vdata, true);
	// 2.0.9: fallback using error_log if WP Filesystem direct method failed
	if (!$vwritedebug) {error_log($vdata, 3, $vdebugfile);}
 }
}

// check/create Debug Directory
// ----------------------------
// 2.0.9: moved to standalone function from bioship_write_debug_file
if (!function_exists('bioship_check_create_debug_dir')) {
 function bioship_check_create_debug_dir() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemedebugdir;
 	if (!isset($vthemedebugdir)) {
		if (is_child_theme()) {global $vthemestyledir; $vthemedebugdir = $vthemestyledir.'debug';}
		else {global $vthemetemplatedir; $vthemedebugdir = $vthemetemplatedir.'debug';}
		$vthemedebugdir = bioship_apply_filters('skeleton_debug_dirpath', $vthemedebugdir);
		if (!is_dir($vthemedebugdir)) {wp_mkdir_p($vthemedebugdir);}
	}
	// 2.0.7: check and write .htaccess file for debug directory
	$vhtacontents = "order deny,allow".PHP_EOL."deny from all";
	$vhtafile = $vthemedebugdir.DIRSEP.'.htaccess';
	if (!file_exists($vhtafile)) {$vwritehta = bioship_write_to_file($vhtafile, $vhtacontents);}
	return $vthemedebugdir;
 }
}

// get Current User Wrapper
// ------------------------
// 2.0.7: extracted all calls to standalone function
if (!function_exists('bioship_get_current_user')) {
 function bioship_get_current_user() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (function_exists('wp_get_current_user')) {return wp_get_current_user();}
	else {
		global $current_user;
		// 2.0.9: streamlined copy of _wp_get_current_user (backwards compatible)
		// (as using get_currentuserinfo() does not pass theme check)
		if (!empty($current_user)) {
			if ($current_user instanceof WP_User) {return $current_user;}
			$current_user = null; $cur_id = 0;
			if (is_object($current_user) && isset($current_user->ID)) {$cur_id = $current_user->ID;}
			wp_set_current_user($cur_id);
		}
		return $current_user;
	}
	return false;
 }
}

// Apply Filters (with Value Change Tracer)
// ----------------------------------------
if (!function_exists('bioship_apply_filters')) {
 function bioship_apply_filters($vfilter, $vvalue, $vcaller=false) {
 	// 2.0.5: also trace applied filter levels
 	$vvalues['in'] = $vvalue;
	$vfiltered = apply_filters($vfilter, $vvalue);
	if ($vvalue != $vfiltered) {$vvalue = $vvalues['filter'] = $vfiltered;}

	// 2.0.5: process theme prefixed filter as well
	if (substr($vfilter, 0, strlen(THEMEPREFIX.'_')) != THEMEPREFIX.'_') {
		$vfiltered = apply_filters(THEMEPREFIX.'_'.$vfilter, $vvalue);
		if ($vfiltered != $vvalue) {$vvalue = $vvalues['theme'] = $vfiltered;}
	}

	// 2.0.5: maybe process child theme specific filter (for multiple theme compatibilty)
	if ( (defined('THEMECHILD')) && (THEMECHILD) ) {
		if (substr($vfilter, 0, strlen(THEMESLUG.'_')) != THEMESLUG.'_') {
			$vfiltered = apply_filters(THEMESLUG.'_'.$vfilter, $vvalue);
			if ($vfiltered != $vvalue) {$vvalue = $vvalues['child'] = $vfiltered;}
		}
	}

	if (defined('THEMETRACE') && THEMETRACE) {
		$vvalues['out'] = $vvalue;
		bioship_trace('V',$vfilter,$vcaller,$vvalues);
	}

	return $vvalue;
 }
}

// Do Action (with Tracer)
// -----------------------
// 2.0.5: added prefixed do_action wrapper for action load debugging/tracing
if (!function_exists('bioship_do_action')) {
 function bioship_do_action($vaction) {
 	if (THEMETRACE) {bioship_trace('A',$vaction,__FILE__);}

 	if (THEMEDEBUG) {
 		$vlist = '';
	 	if (has_action($vaction)) {
	 		global $wp_filter; $vcallbacks = $wp_filter[$vaction]->callbacks;
	 		if (count($vcallbacks) > 0) {
				foreach ($vcallbacks as $vpriority => $vcallback) {
					foreach ($vcallback as $vkey => $vfunction) {
						$vlist .= $vfunction['function'].' ('.$vpriority.')'.PHP_EOL;
					}
				}
			}
	 	}
	 	// 2.0.9: use bioship_debug function
	 	if ($vlist == '') {bioship_debug("Doing Empty Action '".$vaction."'");}
	 	else {bioship_debug("Doing Action '".$vaction."' with Hooked Functions", $vlist);}
	}

	// just do it already
 	do_action($vaction);
 }
}

// Add Action with Priority
// ------------------------
// 1.9.8: added abstract to use theme hooks array
if (!function_exists('bioship_add_action')) {
 function bioship_add_action($vhook, $vfunction, $vdefaultposition) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemehooks; $vprefix = THEMEPREFIX.'_';

	// 2.0.5: maybe auto-prefix hooks and functions
	if (substr($vhook, 0, strlen($vprefix)) != $vprefix) {$vhook = $vprefix.$vhook;}
	if (substr($vfunction, 0, strlen($vprefix)) != $vprefix) {$vfunction = $vprefix.$vfunction;}

	if (isset($vthemehooks['functions'][$vhook][$vfunction])) {
		$vposition = $vthemehooks['functions'][$vhook][$vfunction];
	} else {
		$vposition = $vdefaultposition;
		bioship_debug("Warning: Missing Template Position", $vhook." - ".$vfunction);
	}

	// 2.0.5: for old position filters eg. skeleton_wrapper_open_position
	$voldfunction = substr($vfunction, strlen(THEMEPREFIX.'_'), strlen($vfunction));
	$vposition = apply_filters($voldfunction.'_position', $vposition);

	if (function_exists('bioship_apply_filters')) {
		// eg. bioship_wrapper_open_position
		$vposition = bioship_apply_filters($vfunction.'_position', $vposition);
		// eg. bioship_container_open_bioship_wrapper_open_position
		$vposition = bioship_apply_filters($vhook.'_'.$vfunction.'_position', $vposition);
	} else {
		$vposition = apply_filters($vfunction.'_position', $vposition);
		$vposition = apply_filters($vhook.'_'.$vfunction.'_position', $vposition);
	}

	if ($vposition > -1) {
		bioship_debug("Adding Function to Action Hook Position", $vfunction." - ".$vhook." - ".$vposition);
		add_action($vhook, $vfunction, $vposition);
	}

 }
}

// Register Removed Actions
// ------------------------
// helper to remove template action from hook without needing to know priority position
// 2.0.5: added this remove_action helper wrapper
if (!function_exists('bioship_remove_action')) {
 function bioship_remove_action($vhook, $vfunction, $vposition=false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemehooks; $vprefix = THEMEPREFIX.'_';
	if (substr($vhook, 0, strlen($vprefix)) != $vprefix) {$vhook = $vprefix.$vhook;}
	if (substr($vfunction, 0, strlen($vprefix)) != $vprefix) {$vfunction = $vprefix.$vfunction;}

 	if (!$vposition) {
		if (!isset($vthemehooks['functions'][$vhook][$vfunction])) {
			$vposition = $vthemehooks['functions'][$vhook][$vfunction];
		}
		if (function_exists('bioship_apply_filters')) {
			$vposition = bioship_apply_filters($vhook.'_'.$vfunction.'_position', $vposition);
			$vposition = bioship_apply_filters($vfunction.'_position', $vposition);
		} else {
			$vposition = apply_filters($vhook.'_'.$vfunction.'_position', $vposition);
			$vposition = apply_filters($vfunction.'_position', $vposition);
		}
 	}
 	if ($vposition > -1) {
 		// add to list of actions to remove later
 		// remove_action($vhook, $vfunction, $vposition);
		$vthemehooks['remove'][$vhook][$vfunction] = $vposition;
 	}
 }
}

// Delayed Remove Actions
// ----------------------
if (!function_exists('bioship_remove_actions')) {

 // delay until init so actions added then removed
 // 2.0.9: increase priority for better child theme support
 add_action('init', 'bioship_remove_actions', 11);

 function bioship_remove_actions() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemehooks; $vremove = $vthemehooks['remove'];
	if (count($vremove) > 0) {
		foreach ($vremove as $vhook) {
			foreach ($vhook as $vfunction => $vposition) {
				remove_action($vhook, $vfunction, $vposition);
				bioship_debug("Action Removed from Hook", $vhook." - ".$vfunction." - ".$vposition);
			}
		}
	}
 }
}

// get Single Option
// -----------------
// note: internal theme use, does not honour pre_option_ or default_option filters
// 1.9.5: to get an option direct from database (help to bypass cached values)
// 2.0.9: add default (not yet used) and filter arguments
if (!function_exists('bioship_get_option')) {
 function bioship_get_option($voptionkey, $vdefault=false, $vfilter=true) {
 	if (defined('THEMETRACE') && THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

 	global $wpdb;
 	$vquery = "SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = '".$voptionkey."'";
 	// 2.0.9: always do a maybe_unserialize on option value
	$voptionvalue = maybe_unserialize($wpdb->get_var($vquery));

	// 2.0.9: maybe apply the related option filter
	if ($vfilter) {$voptionvalue = apply_filters('option_'.$voptionkey, $voptionvalue, $voptionkey);}
 	return $voptionvalue;
 }
}

// Comprehensive File Hierarchy
// ----------------------------
// ...a magical fallback mystery tour...
// 1.8.0: use DIRECTORY_SEPARATOR in all paths
// 1.8.0: switch to directory array loop instead of 2 args
// 1.8.0: added optional search roots override argument
// (added for edge cases, ie. the search for /sidebar/page.php should *not* find /page.php)
// 2.0.9: added theme_file_search filter to return results?
if (!function_exists('bioship_file_hierarchy')) {
 function bioship_file_hierarchy($vtype, $vfilename, $vdirs = array(), $vsearchroots = array('stylesheet','template')) {

 	// 1.6.0: we need to check if THEMETRACE constant is defined (in this function only)
	if (defined('THEMETRACE') && THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemestyledir, $vthemestyleurl, $vthemetemplatedir, $vthemetemplateurl;

	// 1.8.5: just in case of bad argument fix ("I'm right", "No, I'm right!" ...)
	if (!is_array($vsearchroots)) {$vsearchroots = array('stylesheet', 'template');}

	// 1.8.0: just use THEMEWINDOWS constant here instead of everywhere else...
	$vfiledirs = $vdirs;
	if ( (THEMEWINDOWS) && (count($vdirs) > 0) ) {
		// 2.0.1: use numerical index when looping array
		foreach ($vdirs as $vi => $vdir) {$vfiledirs[$vi] = str_replace('/', '\\', $vdir);}
	}

	// check defined as this debug is not called for filters.php (as too early)
	if (defined('THEMEDEBUG')) {
		bioship_debug("File Hierarchy Call for ".$vtype, $vfilename);
		if (count($vdirs) > 0) {bioship_debug("Search Directories", implode(',', $vdirs));}
	}

	// 2.0.9: set default value flag
	// 2.1.0: set default found flag
	$vvalue = false; $vfound = false;

	// check stylesheet subdirectory(s) loop
	if (count($vdirs) > 0) {
		foreach ($vdirs as $vi => $vdir) {

			// 2.0.9: check for found value (stored for filtering)
			if (!$vvalue) {
				// 1.8.0: added absolute path override possibility (prototype)
				// (can be used via resource directory override filters)
				if (strstr($vdir,'#')) {
					if ( (substr($vdir, 0 ,1) == '#') && (substr($vdir, -1) == '#') ) {
						$vabsdir = substr($vdir, 1, -1);
						$vfile = $vabsdir.$vfilename;
						// TODO: check/improve this path replacement?
						$vurl = str_replace(ABSPATH, site_url(), $vfile);
						if (file_exists($vfile)) {
							if ($vtype == 'file') {$vvalue = $vfile;}
							if ($vtype == 'url') {$vvalue = $vurl;}
							if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
						}
					}
				} elseif ($vdir == '') {
					// 1.8.0: allow for root file to take precedence if specified
					$vfile = $vthemestyledir.$vfilename;
					$vurl = $vthemestyleurl.$vfilename;
					if (file_exists($vfile)) {
						if ($vtype == 'file') {$vvalue = $vfile;}
						if ($vtype == 'url') {$vvalue = $vurl;}
						if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
					}
				} else {
					$vfile = $vthemestyledir.$vfiledirs[$vi].DIRSEP.$vfilename;
					$vurl = trailingslashit($vthemestyleurl.$vdir).$vfilename;
					if (file_exists($vfile)) {
						if ($vtype == 'file') {$vvalue = $vfile;}
						if ($vtype == 'url') {$vvalue = $vurl;}
						if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
					}
				}
			}
		}
	}

	// check stylesheet base directory
	if ( (!$vvalue) && (in_array('stylesheet', $vsearchroots)) ) {
		$vfile = $vthemestyledir.$vfilename;
		$vurl = $vthemestyleurl.$vfilename;
		if (file_exists($vfile)) {
			if ($vtype == 'file') {$vvalue = $vfile;}
			if ($vtype == 'url') {$vvalue = $vurl;}
			if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
		}
	}

	// 1.8.0: bug out early if the template and stylesheet directory are the same (no child theme)
	if ( (!$vvalue) && ($vthemestyledir == $vthemetemplatedir) ) {return false;}

	// check template subdirectory(s) loop
	if (count($vdirs) > 0) {
		foreach ($vdirs as $vi => $vdir) {
			// 2.0.9: check for found value (stored for filtering)
			if (!$vvalue) {
				// 1.8.0: allow for root file to take precedence if specified
				if ($vdir == '') {
					$vfile = $vthemetemplatedir.$vfilename;
					$vurl = $vthemetemplateurl.$vfilename;
					if (file_exists($vfile)) {
						if ($vtype == 'file') {$vvalue = $vfile;}
						if ($vtype == 'url') {$vvalue = $vurl;}
						if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
					}
				} else {
					$vfile = $vthemetemplatedir.$vfiledirs[$vi].DIRSEP.$vfilename;
					$vurl = trailingslashit($vthemetemplateurl.$vdir).$vfilename;
					if (file_exists($vfile)) {
						if ($vtype == 'file') {$vvalue = $vfile;}
						if ($vtype == 'url') {$vvalue = $vurl;}
						if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
					}
				}
			}
		}
	}
	// check template base directory
	if ( (!$vfound) && (in_array('template', $vsearchroots)) ) {
		$vfile = $vthemetemplatedir.$vfilename;
		$vurl = $vthemetemplateurl.$vfilename;
		if (file_exists($vfile)) {
			if ($vtype == 'file') {$vvalue = $vfile;}
			if ($vtype == 'url') {$vvalue = $vurl;}
			if ($vtype == 'both') {$vvalue = array('file' => $vfile, 'url' => $vurl);}
		}
	}

	bioship_debug($vfilename." Search File Return Value", $vvalue);
	$vfilteredvalue = bioship_apply_filters('theme_file_search', $vvalue, $vfilename, $vdirs, $vsearchroots);
	// 2.1.0: fix to incorrect filter variable name
	if ($vfilteredvalue != $vvalue) {bioship_debug("Search Filtered Return Value", $vfilteredvalue);}
	return $vfilteredvalue;
 }
}

// WordPress.Org Version Checker
// -----------------------------
// note: checks presence of /includes/theme-update-checker.php
// this indicates a version downloaded from Bioship.Space - not from WordPress.org
// 2.0.8: moved this check from admin.php so as to define earlier
// 2.0.9: allow for existing user override for this constant
if (!defined('THEMEWPORG')) {
	$vthemeupdater = bioship_file_hierarchy('file', 'theme-update-checker.php', array('includes'));
	// 2.1.0: fix to fallback definition value true for wordpress.org version
	if ($vthemeupdater) {define('THEMEWPORG', false);} else {define('THEMEWPORG', true);}
}

// get Post Type(s) Helper
// -----------------------
// 1.8.5: added this helper
// 2.0.5: moved here from skull.php
if (!function_exists('bioship_get_post_types')) {
 function bioship_get_post_types($queryobject=null) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// if a numeric value passed, assume it is a post ID
	if ( ($queryobject) && (is_numeric($queryobject)) ) {$queryobject = get_post($queryobject);}
	// if an object is passed, assume a post object
	if ( ($queryobject) && (is_object($queryobject)) ) {
		bioship_debug("Queried Object", $queryobject);
		return get_post_type($queryobject);
	}

	// standard single post type checks
	if (is_404()) {return '';} // no post type for a 404
	// 1.9.5: removed is_single check - incorrect usage!
 	// if (is_single()) {return 'post';}
 	if (is_page()) {return 'page';}
	if (is_attachment()) {return 'attachment';}
	// 1.9.5: added is_archive check for rare cases
	if ( (is_singular()) && (!is_archive()) ) {return get_post_type();}

    // if a custom query object was not passed, use $wp_query global
    if ( (!$queryobject) || (!is_object($queryobject)) ) {
    	// $queryobject = get_queried_object();
    	global $wp_query; $queryobject = $wp_query;
    }
    if (!is_object($queryobject)) {return '';}

	// if the post_type query var has been explicitly set
	// (or implicitly set on the cpt via a has_archive redirect)
	// ie. this is true for is_post_type_archive at least
	// $vqueriedposttype = get_query_var('post_type'); // $wp_query only
	if (property_exists($queryobject, 'query_vars')) {
	    $queriedposttype = $queryobject->query_vars['post_type'];
		if ($queriedposttype) {return $queriedposttype;}
	}

    // handle all other cases by looping posts in query object
    $posttypes = array();
	if ($queryobject->found_posts > 0) {
		$queriedposts = $queryobject->posts;
		foreach ($queriedposts as $queriedpost) {
		    $posttype = $queriedpost->post_type;
		    if (!in_array($posttype, $posttypes)) {$posttypes[] = $posttype;}
		}
		if (count($posttypes == 1)) {return $posttypes[0];}
		else {return $posttypes;}
	}

    return '';
 }
}

// Word to Number Helper
// ---------------------
if (!function_exists('bioship_word_to_number')) {
 function bioship_word_to_number($vword) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$vwordnumbers = array(
		'zero'=>'0', 'one'=>'1', 'two'=>'2', 'three'=>'3', 'four'=>'4', 'five'=>'5' ,'six'=>'6',
		'seven'=>'7', 'eight'=>'8', 'nine'=>'9', 'ten'=>'10', 'eleven'=>'11', 'twelve'=>'12',
		'thirteen'=>'13', 	'fourteen'=>'14', 	'fifteen'=>'15', 	'sixteen'=>'16',
		'seventeen'=>'17', 	'eighteen'=>'18', 	'nineteen'=>'19', 	'twenty'=>'20',
		'twentyone'=>'21', 	'twentytwo'=>'22', 	'twentythree'=>'23','twentyfour'=>'24',
	);
	// 1.8.5: added check and return false for validation
	if (array_key_exists($vword, $vwordnumbers)) {return $vwordnumbers[$vword];}
	return false;
 }
}

// Number to Word Helper
// ---------------------
if (!function_exists('bioship_number_to_word')) {
 function bioship_number_to_word($vnumber) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	$vnumberwords = array(
		'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight',
		'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
		'seventeen', 'eighteen', 'nineteen', 'twenty', 'twentyone', 'twentytwo', 'twentythree', 'twentyfour'
	);
	// 1.8.5: added check and return false for validation
	if (array_key_exists($vnumber, $vnumberwords)) {return $vnumberwords[$vnumber];}
	return false;
 }
}

// load Theme Backwards Compatiblity
// ---------------------------------
// used (sparsely) for any function/filter name changes
// 1.8.5: load theme backwards compatibility file
$vcompat = bioship_file_hierarchy('file', 'compat.php');
// 2.0.5: add ability to turn compat.php loading off with a file (as pre-filters)
$vcompatoff = bioship_file_hierarchy('file', 'compat-off.php');
if ( ($vcompat) && (!$vcompatoff) ) {include_once($vcompat);}


// ------------------
// === Load Theme ===
// ------------------

// Site Icon Check
// ---------------
// 2.0.8: added this to check for global site icon once only
// 2.0.9: make sure function exists and simplify logic (for backwards compatibility)
$vsiteicon = false;
if ( (function_exists('has_site_icon')) && has_site_icon() ) {$vsiteicon = true;}
// $vsiteicon = bioship_apply_filters('skeleton_site_icon', $vsiteicon);
define('THEMESITEICON', $vsiteicon);

// get Current Theme Data
// ----------------------
// note: pre-3.4 compatibility function loaded in compat.php
$vtheme = wp_get_theme();

// Theme Test Drive Determine Theme (modified)
// -------------------------------------------
// (as Theme Test Drive plugin functions not loaded yet)
if (!function_exists('bioship_themedrive_determine_theme')) {
 function bioship_themedrive_determine_theme() {

	// get test drive value if any
	if (!isset($_REQUEST['theme'])) {
		$vtdlevel = get_option('td_level');
		if ($vtdlevel != '') {$vpermissions = 'level_'.$vtdlevel;}
		else {$vpermissions = 'level_10';}

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
	$vthemetestdrive = bioship_themedrive_determine_theme();
	if ($vthemetestdrive) {
		define('THEMEDRIVE', true); $vtheme = $vthemetestdrive;

		// 1.8.0: override the style and template directory values
		$vthemestyledir = get_stylesheet_directory($vtheme['Stylesheet']).DIRSEP;
		$vthemestyleurl = trailingslashit(get_stylesheet_directory_uri($vtheme['Stylesheet']));
		$vthemetemplatedir = get_template_directory($vtheme['Template']).DIRSEP;
		$vthemetemplateurl = trailingslashit(get_template_directory_uri($vtheme['Template']));
		// 1.8.5: re-enforce SSL recheck
		if (is_ssl()) {
			$vthemestyleurl = str_replace('http://', 'https://', $vthemestyleurl);
			$vthemetemplateurl = str_replace('http://', 'https://', $vthemetemplateurl);
		}

		// load Admin Menu Fixes for Theme Test Driving
		add_action('admin_menu','admin_themetestdrive_options',12);

		// Set Temporary Filter for Theme Value [Options Framework only]
		// TODO: Test with Options Framework + Customizer combo
		// 2.0.1: added missing prefix
		add_filter('of_theme_value', 'bioship_optionsframework_themetestdrive');
		function bioship_optionsframework_themetestdrive($vthetheme) {
			$vthemetestdrive = bioship_themedrive_determine_theme();
			$vthetheme['id'] = preg_replace("/\W/", "_",strtolower($vthemetestdrive['Name']));
			return $vthetheme;
		}
	}
}

// maybe Load Theme Value Filters
// ------------------------------
// check for possible customized filters.php in parent/child theme
// *loaded early* as filters are used immediately if present
// 2.0.9: run core directory filter in case override already exists
$vthemedirs['core'] = bioship_apply_filters('theme_core_dirs', $vthemedirs['core']);
$vfilters = bioship_file_hierarchy('file', 'filters.php', $vthemedirs['core']);
if ($vfilters) {include_once($vfilters);} // initialize filters

// Filter Resource Directories
// ---------------------------
// 1.8.5: run all the directory search filters here (as may be in filters.php)
$vthemedirs['core'] = bioship_apply_filters('theme_core_dirs', $vthemedirs['core']);
$vthemedirs['admin'] = bioship_apply_filters('theme_admin_dirs', $vthemedirs['admin']);
$vthemedirs['style'] = $vthemedirs['css'] = bioship_apply_filters('theme_style_dirs', $vthemedirs['style']);
$vthemedirs['script'] = $vthemedirs['js'] = bioship_apply_filters('theme_script_dirs', $vthemedirs['script']);
$vthemedirs['image'] = $vthemedirs['img'] = bioship_apply_filters('theme_image_dirs', $vthemedirs['image']);
// 2.0.9: add a single directory array override filter for easier usage
$vthemedirs = bioship_apply_filters('theme_resource_dirs', $vthemedirs);

// set Global Theme Name and Parent/Child
// --------------------------------------
$vthemedisplayname = (string)$vtheme['Name']; define('THEMEDISPLAYNAME',$vthemedisplayname);
$vthemename = preg_replace("/\W/", "-", strtolower($vthemedisplayname));
// 2.0.5: define THEMESLUG (using dashes not underscores)
define('THEMESLUG', $vthemename);
// 2.0.5: cleaner code logic
if (!is_child_theme()) {define('THEMECHILD', false); define('THEMEPARENT', false);}
else {define('THEMECHILD', true); define('THEMEPARENT', (string)$vtheme['Template']);}

// set Child Theme Version for Cache Busting
// -----------------------------------------
// 2.0.1: simplify to set child theme version constant
// 2.0.5: cleaner code logic
if (!THEMECHILD) {define('THEMECHILDVERSION', THEMEVERSION);}
else {define('THEMECHILDVERSION', $vtheme['Version']);}

// set Theme Debug Mode Switch
// ---------------------------
// 1.8.0: changed this to a constant, allow for switching
// 1.8.5: added all word values and new '3' option
// ?themedebug=0 or ?themedebug=off 	- switch theme debug mode off
// ?themedebug=1 or ?themedebug=on 		- switch theme debug mode on (persistant)
// ?themedebug=2 or ?themedebug=yes		- debug mode on for this pageload (overrides)
// ?themedebug=3 or ?themedebug=no 		- debug mode off for this pageload (overrides)

if (!defined('THEMEDEBUG')) {
	$vthemekey = preg_replace("/\W/", "_", strtolower($vthemedisplayname));
	$vthemedebug = get_option($vthemekey.'_theme_debug');
	if ($vthemedebug == '1') {$vthemedebug = true;} else {$vthemedebug = false;}
	if (isset($_REQUEST['themedebug'])) {
		$vdebugrequest = $_REQUEST['themedebug'];
		// 1.8.5: authenticate debug capability
		// 2.0.9: add filter for debug switching capability
		$vdebugcap = bioship_apply_filters('theme_debug_capability', 'edit_theme_options');
		if (current_user_can($vdebugcap)) {
			if ( ($vdebugrequest == '2') || ($vdebugrequest == 'yes') ) {$vthemedebug = true;} // pageload only
			elseif ( ($vdebugrequest == '3') || ($vdebugrequest == 'no') ) {$vthemedebug = false;} // pageload only
			elseif ( ($vdebugrequest == '1') || ($vdebugrequest == 'on') ) { // switch on
				$vthemedebug = true; delete_option($vthemekey.'_theme_debug');
				add_option($vthemekey.'_theme_debug', '1');
			}
			elseif ( ($vdebugrequest == '0') || ($vdebugrequest == 'off') ) { // switch off
				$vthemedebug = false; delete_option($vthemekey.'_theme_debug');
			}
		}
	}
	if ( ($vthemedebug == '') || ($vthemedebug == '0') ) {$vthemedebug = false;}
	$vthemedebug = bioship_apply_filters('theme_debug', $vthemedebug);
	// ...and finally define the constant...
	define('THEMEDEBUG', $vthemedebug);
}

// maybe Start Debug Output/Log
// ----------------------------
if (THEMEDEBUG) {
	global $pagenow; bioship_debug("PageNow", $pagenow);

	// 2.0.5: also use save queries constant for debugging output
	if (!defined('SAVEQUERIES')) {define('SAVEQUERIES', true);}
	if (SAVEQUERIES) {
		add_action('shutdown', 'bioship_debug_saved_queries');
		if (!function_exists('bioship_debug_saved_queries')) {
		 function bioship_debug_saved_queries() {
			global $wpdb; $queries = $wpdb->queries;
			bioship_debug("Saved Queries", $queries);
		 }
		}
	}
}

// set Debug Directory
// -------------------
// 1.9.8: set debug directory global value
if (THEMECHILD) {global $vthemestyledir; $vthemedebugdir = $vthemestyledir.'debug';}
else {global $vthemetemplatedir; $vthemedebugdir = $vthemetemplatedir.'debug';}
$vthemedebugdir = bioship_apply_filters('theme_debug_dirpath', $vthemedebugdir);
if (!is_dir($vthemedebugdir)) {wp_mkdir_p($vthemedebugdir);}

// maybe Load Theme Function Tracer
// --------------------------------
// 1.8.0: moved here as was loaded too early to work, refined logic
if (!defined('THEMETRACE')) {
	// 1.8.5: added querystring option for high capability
	$vthemetracer = false;
	if (isset($_REQUEST['themetrace'])) {
		$vthemetracer = $_REQUEST['themetrace'];
		if (current_user_can('manage_options')) {
			// 2.0.5: make any value trigger the trace
			if ($vthemetrace != '0') {$vthemetracer = true;}
		}
	}
	// 1.9.8: fix to filtered value here, define constant moved to file
	$vthemetracer = bioship_apply_filters('theme_tracer', $vthemetracer);
}
if (!function_exists('bioship_trace')) { // still overrideable
	if ($vthemetracer) {
		// 1.9.8: change fixed directory to admin dir global
		$vtracer = bioship_file_hierarchy('file', 'tracer.php', $vthemedirs['admin']);
		if ($vtracer) {include($vtracer);}
	}
}
if (!defined('THEMETRACE')) {define('THEMETRACE', false);}
if ( (THEMETRACE) && (!function_exists('bioship_trace')) ) {
	// dummy function to avoid potential fatal errors in edge cases
	function bioship_trace($varg1=null,$varg2=null,$varg3=null,$varg4=null) {return;}
}

// Convert Posted Customizer Preview Options
// -----------------------------------------
// 1.8.5: moved here to be available for both options frameworks
if (!function_exists('bioship_customizer_convert_posted')) {
 function bioship_customizer_convert_posted($vpostedvalues, $voptionvalues) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemeoptions;

	foreach ($vthemeoptions as $voptionkey => $voptionvalue) {
		$vpreviewkey = str_replace('_options', '_customize', THEMEKEY.'['.$voptionvalue['id'].']');
		if (array_key_exists($vpreviewkey, $vpostedvalues)) {
			$vpreviewvalue = bioship_apply_filters('customize_sanitize_{$vpreviewkey}', $vpostedvalues[$vpreviewkey], array());
			// note: the third argument above could be a Customizer setting object?

			// !!! WARNING: ECHOING DEBUG OUTPUT IN CUSTOMIZER PREVENTS SAVING !!!

			// 1.8.5: fix to empty checkbox values
			if ($voptionvalue['type'] == 'checkbox') {
				if ($vpreviewvalue == '1') {$voptionvalues[$voptionvalue['id']] = '1';}
				else {$voptionvalues[$voptionvalue['id']] = '0';}
			}
			else {
				if (!is_array($vpreviewvalue)) {
					$voptionvalues[$voptionvalue['id']] = $vpreviewvalue;
					bioship_debug("Preview Value for '".$voptionvalue['id']."'", $vpreviewvalue, false);
				} else {
					// TODO: maybe do something else for subarray values?
					bioship_debug("Option Type", $voptionvalue['type'], false);

					// 1.8.5: fix for customizer multicheck arrays
					if ($voptionvalue['type'] == 'multicheck') {
						foreach ($voptionvalue['options'] as $vkey => $vvalue) {
							if (in_array($vkey,$vpreviewvalue)) {$vvaluearray[$vkey] = '1';}
							else {$vvaluearray[$vkey] = '0';}
						}
						$voptionvalues[$voptionvalue['id']] = $vvaluearray;
					} else {$voptionvalues[$voptionvalue['id']] = $vpreviewvalue;}
					// bioship_debug("Preview Value for '".$voptionvalue['id']."'", $voptionvalues[$voptionvalue['id']], false);
				}
			}
		}
	}
	return $voptionvalues;
 }
}

// get Theme Settings Filter - with Fallbacks!
// -------------------------------------------
// ...this may seem completely arbitrary and unnecessary but it works...
// 1.9.5: get_option filter to help bypass crazy empty settings / saving bug
// 2.0.9: added theme_settings filter to return value
if (!function_exists('bioship_get_theme_settings')) {
 function bioship_get_theme_settings($vvalue, $voptionkey=false) {
 	global $vthemesettingsupdating;
 	if ($vthemesettingsupdating) {return $vvalue;}

	// this is to bypass possible cached value
	$vsettings = bioship_get_option(THEMEKEY);

	// 1.9.7: fix to missing argument 2 filter warning
	if (!$voptionkey) {$voptionkey = THEMEKEY;}

	if ($vsettings) {
		if (is_serialized($vsettings)) {
			bioship_debug("Serialized Theme Settings Found");

			// $vsettings = str_replace("\r","",$vsettings);
			$vsettings = (string)$vsettings;
			$vunserialized = unserialize($vsettings);
			if ($vunserialized) {
				bioship_debug("Settings Unserialized Successfully");
				return bioship_apply_filters('theme_settings', $vunserialized);
			} else {
				// and here is the problem (read ghost insane WTF bug)... finally! yeesh.
				// ?sometimes? the data JUST DOES NOT UNSERIALIZE - FOR NO CLEAR REASON
				// it just returns false even though the serialized data is there
				// and does not appear to be at all corrupt and works in other contexts

				// attempt to fix serialized settings, but sometimes works sometimes not :-/
				// $vrepaired = bioship_fix_serialized($vsettings);
				// $vfixedsettings = unserialize($vrepaired);
				// if ($vfixedsettings) {return $vfixedsettings;}

				// for now, add a filter so users can apply a custom manual fix
				$vcustomsettings = bioship_apply_filters('theme_settings_fallback', $vsettings);
				if (is_serialized($vcustomsettings)) {
					$vunserialized = unserialize($vcustomsettings);
					if ($vunserialized) {
						bioship_debug("Unserialized Settings from Custom Override Used");
						return bioship_apply_filters('theme_settings', $vunserialized);
					}
				}

				//	bioship_debug("Unserialization Failed for Option Key", $voptionkey);
				//  bioship_debug("Settings", $vsettings);
				//	bioship_debug("Maybe Unserialized", maybe_unserialize($vsettings));
				//	bioship_debug("Unserialized", $vunserialized);
			}
		}
	}

	// maybe apply force update settings
	$vforcesettings = get_transient('force_update_'.$voptionkey);
	if ($vforcesettings) {
		bioship_debug("Checking Force Update Settings");
		if (is_serialized($vforcesettings)) {
			$vunserialized = @unserialize($vforcesettings);
			if (!$vunserialized) {
				$vrepaired = bioship_fix_serialized($vforcesettings);
				$vfixedsettings = @unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				bioship_write_debug_file($voptionkey.'.txt',$vforcesettings);
				$vthemesettingsupdating = true;
				delete_option($voptionkey); add_option($voptionkey, $vunserialized);
				$vthemesettingsupdating = false;
				bioship_debug("Force Update Settings Used and Restored");
				// 2.0.0: change to skeleton prefix and add translation wrappers
				add_action('theme_admin_notices','bioship_forced_settings_restored');
				if (!function_exists('bioship_forced_settings_restored')) {
				 function bioship_forced_settings_restored() {
					echo "<div class='message'><b>".__('Warning','bioship').":</b> ";
					echo __('Theme Settings from Force Update Used and Restored!','bioship')."</div>";
				 }
				}
				return bioship_apply_filters('theme_settings', $vunserialized);
			} else {bioship_debug("Force Update Settings could not be Unserialized");}
		}
	}

	// maybe fallback to saved options file
	$vsavedfile = get_stylesheet_directory().'/debug/'.$voptionkey.'.txt';
	// bioship_debug("Check Backup Settings File", $vsavedfile);
	if (file_exists($vsavedfile)) {
		bioship_debug("Checking Found File Settings");
		$vsaveddata = bioship_file_get_contents($vsavedfile);
		if ( (strlen($vsaveddata) > 0) && (is_serialized($vsaveddata)) ) {
			$vunserialized = @unserialize($vsaveddata);
			if (!$vunserialized) {
				$vrepaired = bioship_fix_serialized($vsaveddata);
				$vfixedsettings = @unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				$vthemesettingsupdating = true;
				delete_option($voptionkey); add_option($voptionkey, $vunserialized);
				$vthemesettingsupdating = false;
				bioship_debug("File Theme Settings Used and Restored");
				// 2.0.0: change to skeleton prefix and add translation wrappers
				add_action('theme_admin_notices','skeleton_file_settings_restored');
				if (!function_exists('bioship_file_settings_restored')) {
				 function bioship_file_settings_restored() {
					echo "<div class='message'><b>".__('Warning','bioship').":</b> ";
					echo __('Theme Settings from File Settings Used and Restored!','bioship')."</div>";
				 }
				}
				return bioship_apply_filters('theme_settings', $vunserialized);
			} else {bioship_debug("File Settings could not be Unserialized");}
		}
	}

	// maybe fallback to backup options
	$vbackupkey = $voptionkey.'_backup';
	$vbackupsettings = bioship_get_option($vbackupkey);
	if ($vbackupsettings) {
		if (is_serialized($vbackupsettings)) {
			$vunserialized = unserialize($vbackupsettings);
			if (!$vunserialized) {
				$vrepaired = bioship_fix_serialized($vbackupsettings);
				$vfixedsettings = unserialize($vrepaired);
				if ($vfixedsettings) {$vunserialized = $vfixedsettings;}
			}
			if ($vunserialized) {
				bioship_debug("AutoBackup Settings Used");
				if (!$vsettings) {
					$vthemesettingsupdating = true;
					delete_option($voptionkey); add_option($voptionkey, $vunserialized);
					$vthemesettingsupdating = false;
					bioship_debug("AutoBackup Theme Settings Restored");
					// 2.0.0: change to skeleton prefix and add translation wrappers
					add_action('theme_admin_notices','skeleton_backup_settings_restored');
					if (!function_exists('bioship_backup_settings_restored')) {
					 function bioship_backup_settings_restored() {
						echo "<div class='message'><b>".__('Error','bioship').":</b> ";
						echo __('Theme Settings Empty! Existing Settings AutoBackup Restored.','bioship')."</div>";
					 }
					}
				}
				return bioship_apply_filters('theme_settings', $vunserialized);
			}
		}
	}

	bioship_debug("Unserialized Theme Settings Used");
	return bioship_apply_filters('theme_settings', $vsettings);
 }
}

// Serialized Data Fixer
// ---------------------
if (!function_exists('bioship_fix_serialized')) {
 function bioship_fix_serialized($string) {
    // securities
    if ( !preg_match('/^[aOs]:/', $string) ) return $string;
    if ( @unserialize($string) !== false ) return $string;
    $string = preg_replace("%\n%", "", $string);
    // doublequote exploding
    $data = preg_replace('%";%', "µµµ", $string);
    $tab = explode("µµµ", $data);
    $new_data = '';
    foreach ($tab as $line) {
        $new_data .= preg_replace_callback('%\bs:(\d+):"(.*)%', 'bioship_fix_str_length', $line);
    }
    return $new_data;
 }
}

// Fix Serialized String Callback
// ------------------------------
if (!function_exists('bioship_fix_str_length')) {
 function bioship_fix_str_length($matches) {
    $string = $matches[2];
    // yes, strlen even for UTF-8 characters
    // PHP wants the mem size, not the char count
    $right_length = strlen($string);
    return 's:' . $right_length . ':"' . $string . '";';
 }
}

// Check / Fix Updated Theme Settings (PreSave)
// --------------------------------------------
// 2.1.0: use pre-filtering of option value instead of do_action hook
add_filter('pre_update_option', 'bioship_admin_theme_settings_save', 11, 3);
if (!function_exists('bioship_admin_theme_settings_save')) {
 function bioship_admin_theme_settings_save($vnewsettings, $voption, $voldsettings) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	if ($voption != THEMEKEY) {return $vnewsettings;}
	if (defined('THEMEUPDATED') && THEMEUPDATED) {return $vnewsettings;}

	// 2.0.9: store the serialized settings value
	// 2.1.0: changed to use the unserialized settings value due to filter
	$vsettings = maybe_unserialize($vnewsettings);

	// 2.0.9: for auto-fixing of multicheck theme option saving
	global $vthemedebugdir, $vthemename;
	$vmulticheckkeys = array();
	$vmulticheckoptions = get_option($vthemename.'_multicheck_options');
	$vmulticheckkeys = array_keys($vmulticheckoptions);
	foreach ($vmulticheckkeys as $vkey) {
		// echo $vthemename.'_'.$vkey;
		if (isset($_POST[$vthemename.'_'.$vkey])) {$vpostedmulticheck[$vkey] = $_POST[$vthemename.'_'.$vkey];}
	}

	$voldsettings = maybe_unserialize($voldsettings);
	$vnewsettings = maybe_unserialize($vnewsettings);
	$vnewsettingskeys = array_keys($vnewsettings);
	foreach ($vnewsettings as $vkey => $vvalue) {
		if (in_array($vkey, $vmulticheckkeys)) {$vnewmulticheck[$vkey] = maybe_unserialize($vvalue);}
	}

	// 2.1.0: can only use old settings as fallbacks if they unserialized correctly
	if (is_array($voldsettings)) {
		$voldsettingskeys = array_keys($voldsettings);
		foreach ($voldsettings as $vkey => $vvalue) {
			if (in_array($vkey, $vmulticheckkeys)) {$voldmulticheck[$vkey] = maybe_unserialize($vvalue);}
		}
	}
	// 2.1.0: finally, use posted or old multicheck settings if the new ones are empty!
	foreach ($vnewmulticheck as $vkey => $vvalues) {
		if (!isset($vsettings[$vkey]) || empty($vsettings[$vkey])) {
			if (isset($_POST[$vthemename.'_'.$vkey]) && !empty($_POST[$vthemename.'_'.$vkey])) {
				$vsettings[$vkey] = $_POST[$vthemename.'_'.vkey];
			} elseif (isset($voldmulticheck[$vkey]) && !empty($voldmulticheck[$vkey])) {
				$vsettings[$vkey] = $voldmulticheck[$vkey];
			}
		}
	}

	// 2.1.0: for debugging multicheck option saving (THE super annoying bug)
	$vthemedebugdir = bioship_check_create_debug_dir();
	$vdebugdata .= '<!-- '.$voption.' ['.date('y/m/d H:i:s', time()).'] -->'.PHP_EOL;
	// $vdebugdata .= '<!-- GET Values -->'.print_r($_GET, true).PHP_EOL;
	// $vdebugdata .= '<!-- POST Values -->'.print_r($_POST, true).PHP_EOL;
	// $vdebugdata .= '<!-- Multicheck Options -->'.print_r($vmulticheckoptions, true).PHP_EOL;
	$vdebugdata .= '<!-- Multicheck Keys -->'.print_r($vmulticheckkeys, true).PHP_EOL;
	$vdebugdata .= '<!-- Posted Multicheck Options -->'.print_r($vpostedmulticheck,true).PHP_EOL;
	// $vdebugdata .= '<!-- Old Settings -->'.print_r($voldsettings, true).PHP_EOL;
	// $vdebugdata .= '<!-- New Settings -->'.print_r($vnewsettings, true).PHP_EOL;
	// $vdebugdata .= '<!-- Old Settings Keys -->'.print_r($voldsettingskeys, true).PHP_EOL;
	// $vdebugdata .= '<!-- New Settings Keys -->'.print_r($vnewsettingskeys, true).PHP_EOL;
	$vdebugdata .= '<!-- Old Multicheck Settings -->'.print_r($voldmulticheck, true).PHP_EOL;
	$vdebugdata .= '<!-- New Multicheck Settings -->'.print_r($vnewmulticheck, true).PHP_EOL;
	$vdebugdata .= '<!-- Fixed Updated Settings -->'.print_r($vsettings, true).PHP_EOL;

	// echo $vdebugdata;
	// if (THEMEDEBUG) {
		$vdebugfile = $vthemedebugdir.'/multicheck-options.txt';
		$vdebugdata = str_replace('<!-- ', '', $vdebugdata);
		$vdebugdata = str_replace(' -->', '',$vdebugdata);
		// 2.1.0: use error_log function here
		error_log($vdebugdata, 3, $vdebugfile);
		// bioship_write_to_file($vdebugfile, $vdebugdata, true);
	// }

	// 2.1.0: reserialize the settings to ensure update
	if ($vsettings && !empty($vsettings) && is_array($vsettings)) {
		$vserialized = serialize($vsettings);
		// write a manual settings file backup of the serialized data
		// 1.8.0: use standalone debug file write function
		bioship_write_debug_file($voption.'.txt', $vserialized);
		// set a transient of the new settings to ensure saving
		set_transient('force_update_'.THEMEKEY, $vsettings, 60);
		if (!defined('THEMEUPDATED')) {define('THEMEUPDATED', true);}
		return $vsettings;
	}
	return $vnewsettings;
 }
}

// PostCheck of Updated Theme Settings (Debug)
// -------------------------------------------
add_action('updated_option', 'bioship_check_updated_option', 10, 3);
function bioship_check_updated_option($voption, $voldvalue, $vvalue) {
	if ($voption == THEMEKEY) {
		echo "<!-- Updated Value for ".THEMEKEY.": ".print_r($vvalue,true)." -->";
		$vsettings = get_option(THEMEKEY);
		echo "<!-- Saved Option for ".THEMEKEY.": ".print_r($vsettings,true)." -->";
	}
}


// maybe Load Titan (or Options) Framework
// ---------------------------------------
// if Titan is present it is loaded, otherwise things should still run okay...

// 1.8.5: convert previous version file switches
// Usage Note: creating a titanswitch.off file will revert to Options Framework usage,
// and creating a titanswitch.on file will remove Options Framework usage...
if (THEMECHILD) {$vtitanoff = $vthemestyledir.'titanswitch.off'; $vtitanon = $vthemestyledir.'titanswitch.on';}
else {$vtitanoff = $vthemetemplatedir.'titanswitch.off'; $vtitanon = $vthemetemplatedir.'titanswitch.on';}
if (file_exists($vtitanoff)) {add_option($vthemename.'_framework', 'options'); unlink($vtitanoff);}
elseif (file_exists($vtitanon)) {delete_option($vthemename.'_framework'); unlink($vtitanon);}

// 1.8.5: new method to check switch for Options Framework usage
$vthemeframework = get_option($vthemename.'_framework');
if ($vthemeframework == 'options') {$voptionsload = true; $vtitanload = false;}
else {$voptionsload = false; $vtitanload = true;} // always attempt to load Titan if present

// default load, loads Titan if present
if (!$voptionsload) {

	// maybe Load Titan Framework
	// --------------------------
	// note: hyphenated theme names eg. my-child-theme
	if (!defined('THEMEKEY')) {define('THEMEKEY', $vthemename.'_options');}

	// 1.8.0: do a check as Titan may already active as a plugin
	// note: the TitanFramework class itself is loaded after_theme_setup (so not available yet)
	// 1.8.5: maybe use the already loaded Titan Framework plugin
	if (class_exists('TitanFrameworkPlugin')) {define('THEMETITAN', true);}
	else {
		$vtitan = false;
		if ($vtitanload) {
			$vtitan = bioship_file_hierarchy('file', 'titan-framework-embedder.php', array('includes/titan','includes'));
			if ($vtitan) {require_once($vtitan); define('THEMETITAN', true);} // initialize Titan now
		}

		if ( (!$vtitanload) || (!$vtitan) ) {
			// lack of Titan Framework embedder indicates it was not found
			// - possibly this indicates it is a WordPress.Org version of the theme
			// 2.0.9: changed Titan checker setting to required=false for wordpress.org version
			$vtitanchecker = bioship_file_hierarchy('file', 'titan-framework-checker.php', array('includes','includes/titan'));
			if ($vtitanchecker) {require_once($vtitanchecker);}
			else {bioship_debug("Warning! Titan Framework Checker not found.");}
			// 2.0.9: moved Theme Tools submenu loading to admin.php
		}
	}

} else {

	// maybe Load Options Framework
	// ----------------------------
	// note: underscored theme names eg. my_child_theme
	$vthemename = str_replace('-', '_', $vthemename);
	if (!defined('THEMEKEY')) {define('THEMEKEY', $vthemename);}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

	// 1.8.0: use file hierarchy search here
	$voptionsframework = bioship_file_hierarchy('file', 'options-framework.php', array('includes/options','options'));
	if ($voptionsframework) {
		$voptionspath = dirname($voptionsframework).DIRSEP;
		define('OPTIONS_FRAMEWORK_DIRECTORY', $voptionspath);
		require_once($voptionsframework);
		// 1.8.5: define constant for Options Framework
		define('THEMEOPT', true);
	} else {define('THEMEOPT', false);}

	// 1.8.5: fix - load the theme options array here!
	$voptions = bioship_file_hierarchy('file', 'options.php');
	if ($voptions) {include($voptions);}
	else {wp_die(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship'));}
	global $vthemeoptions; $vthemeoptions = bioship_options();

	// get Options Framework theme settings
	$vthemesettings = get_option(THEMEKEY);

	// AutoBackup of Theme Settings
	// 1.9.5: moved here for better checking
	if ( ($vthemesettings) && (!empty($vthemesettings)) && ($vthemesettings != '') && (is_array($vthemesettings)) ) {
		$vbackupkey = THEMEKEY.'_backup'; delete_option($vbackupkey); add_option($vbackupkey, $vthemesettings);
	}

	// Customizer Live Preview Values
	// ------------------------------
	// TODO: retest with Options Framework + Customizer combo!
	global $pagenow;
	if ( (is_customize_preview()) && ($pagenow != 'customizer.php') ) {
		// !!! WARNING: DEBUG OUTPUT IN CUSTOMIZER CAN PREVENT SAVING !!!
		if (isset($_POST['customized'])) {$vpostedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($vpostedvalues)) {
			// TODO: check theme options override with preview options?
			// 1.9.5: fix to ridiculous typo bug here "(", not ".)"
			$vthemesettings = bioship_customizer_convert_posted($vpostedvalues, $vthemesettings);
		}
	}

	// TESTME: ? test/fix for empty checkbox/multicheck values ?
	// ...seems to be working fine now...

}

// 1.8.5: set framework constants to false if not loaded
if (!defined('THEMETITAN')) {define('THEMETITAN', false);}
if (!defined('THEMEOPT')) {define('THEMEOPT', false);}
if (!THEMETITAN) {bioship_debug("Titan Framework is OFF");}
if (!THEMEOPT) {bioship_debug("Options Framework is OFF");}

// Map Titan option values to the Theme Options
// --------------------------------------------
// 1.8.0: move because of function_exists wrapper this needs to be *here* not later
// 1.8.5: use optionsarray global and made single argument only
if (!function_exists('bioship_titan_theme_options')) {
 function bioship_titan_theme_options($voptionvalues) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemeoptions, $vthemename;

	// loop the options array and convert Titan to theme options array
	$vcheckboxes = array(); $vmulticheck = array();
	foreach ($vthemeoptions as $voption => $voptionvalue) {
		if ( ($voptionvalue['type'] != 'heading') && ($voptionvalue['type'] != 'info') ) {
			if (!isset($voptionvalue['id'])) {
				bioship_debug("Option definition error found!", $voptionvalue);
			} else {$voptionkey = $voptionvalue['id'];}

			// set to default for any unset options
			if (!isset($voptionvalues[$voptionkey])) {
				// 1.9.8: fix to mapping of default options for empty settings
				if (isset($voptionvalue['std'])) {$vthemesettings[$voptionkey] = $voptionvalue['std'];}
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

				// 1.9.5: fix to undefined index (typically for new settings)
				if (isset($voptionvalues[$voptionkey])) {
					$voptionvalues[$voptionkey] = maybe_unserialize($voptionvalues[$voptionkey]);
				} else {$voptionvalues[$voptionkey] = array();}
				// bioship_debug("Option Values", $voptionvalues[$voptionkey]);

				$voptionarray = array();
				// 2.0.9: fix for standalone multicheck customizer control saving
				if (is_string($voptionvalues[$voptionkey])) {
					if (strstr($voptionvalues[$voptionkey], ',')) {
						$voptionvalues[$voptionkey] = explode(',', $voptionvalues[$voptionkey]);
					} else {$voptionvalues[$voptionkey] = array($voptionvalues[$voptionkey]);}
				}
				foreach ($voptionvalue['options'] as $vkey => $vlabel) {
					$vmulticheck[$voptionkey][] = $vkey;
					// if (THEMEDEBUG) {echo "<!-- ? ".$vkey." ? ".$vlabel." ? -->";}
					// 1.9.9: fix to arrays switching around weirdly
					// 2.1.0: add check for if optionvalues is an array
					if (is_array($voptionvalues[$voptionkey])) {
						if (array_key_exists($vkey, $voptionvalues[$voptionkey])) {
							// 2.0.0: one more fix for this absolute madness
							// bioship_debug("!A!", $voptionvalues[$voptionkey][$vkey]);
							if ($voptionvalues[$voptionkey][$vkey] == '0') {$voptionarray[$vkey] = '0';}
							else {$voptionarray[$vkey] = '1';}
						} elseif (in_array($vkey, $voptionvalues[$voptionkey])) {
							// bioship_debug("!B!", $voptionvalues[$voptionkey][$vkey]);
							$voptionarray[$vkey] = '1';
						} else {$voptionarray[$vkey] = '0';}
					} else {$voptionarray[$key] = '0';}
				}

				// WARNING: uncommenting this debug line may prevent Customizer saving
				bioship_debug("Option Array for '".$voptionkey."'", $voptionarray, false, true);
				$vthemesettings[$voptionkey] = $voptionarray;
			}

			// convert attachment IDs to actual image/upload URL
			if ($voptionvalue['type'] == 'upload') {
				// 1.9.8: add check for empty options to avoid warning
				if ( (isset($vthemesettings[$voptionkey])) && (is_numeric($vthemesettings[$voptionkey])) ) {
					$vimage = wp_get_attachment_image_src($vthemesettings[$voptionkey],'full');
					$vthemesettings[$voptionkey] = $vimage[0];
				}
			}
		}
	}

	// reset the multicheck options index for customizer saving
	delete_option($vthemename.'_multicheck_options');
	add_option($vthemename.'_multicheck_options', $vmulticheck);

	return $vthemesettings;
 }
}

// maybe Load Theme Options
// ------------------------
// 1.8.0: load options.php (if not already using Options Framework)
// note: converts all options whether Titan Framework is loaded or not
if (!THEMEOPT) {

	// 2.0.5: simplify to use THEMESLUG here
	$vthemename = THEMESLUG;
	if (!defined('THEMEKEY')) {define(THEMEKEY, $vthemename.'_options');}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

	// load the theme options array
	$voptions = bioship_file_hierarchy('file', 'options.php');
	if ($voptions) {include($voptions);}
	else {wp_die(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship'));}

	// 1.9.5: fix for when transferred options manually?
	// add_filter('tf_init_no_options_'.THEMEKEY, 'bioship_titan_no_options_fix');
	// if (!function_exists('bioship_titan_no_options_fix')) {
	//  function bioship_titan_no_options_fix() {return maybe_unserialize(get_option(THEMEKEY));}
	// }

	// [Titan only] maybe initialize the Titan Framework Options admin page
	add_action('tf_create_options', 'bioship_titan_create_options');
	if (!function_exists('bioship_titan_create_options')) {
	 function bioship_titan_create_options() {$vloadtitan = bioship_optionsframework_to_titan();}
	}

	// load options array (whether Titan class available or not)
	global $vthemeoptions; $vthemeoptions = bioship_options();
	$vtitansettings = maybe_unserialize(get_option(THEMEKEY));
	bioship_debug("Titan Framework Settings", $vtitansettings);

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
		// !! WARNING: DEBUG OUTPUT IN CUSTOMIZER CAN PREVENT SAVING !!
		// bioship_debug("Customize Preview Theme Options");
		if (isset($_POST['customized'])) {$vpostedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($vpostedvalues)) {
			// bioship_debug("Posted Preview Values", $vpostedvalues);
			$voptionvalues = bioship_customizer_convert_posted($vpostedvalues,$voptionvalues);
			// bioship_debug("Full Preview Options", $voptionvalues);
		}
	}

	$vthemesettings = bioship_titan_theme_options($voptionvalues);

	// to manually debug all theme options / values
	// bioship_debug("THEMEKEY", THEMEKEY);
	// bioship_debug("Theme Options", $vthemeoptions);
	// bioship_debug("Option Values", $voptionvalues);
	// bioship_debug("Theme Settings", $vthemesettings);

	// AutoBackup Theme Settings
	// 1.9.5: moved here for better checking placement
	if ( ($voptionvalues) && (!empty($voptionvalues)) && ($voptionvalues != '') && (is_array($voptionvalues)) ) {
		$vbackupkey = THEMEKEY.'_backup'; delete_option($vbackupkey); add_option($vbackupkey, $vthemesettings);
	}

}

// maybe Load Theme Admin Specific Functions
// -----------------------------------------
$vloadadmin = false; $vadmin = false;
if (is_admin()) {$vloadadmin = true;}
// 1.9.5: load for theme dump output
if ( ($vthemesettings == '') || (isset($_REQUEST['themedump'])) ) {$vloadadmin = true;}
// 1.8.0: allow for backup/restore/import/export theme options AJAX requests
if ( (isset($_REQUEST['action'])) && (strstr($_REQUEST['action'], '_theme_options')) ) {$vloadadmin = true;}
if ($vloadadmin) {$vadmin = bioship_file_hierarchy('file', 'admin.php', $vthemedirs['admin']);}
if ($vadmin) {include($vadmin);}

// set HTML Comment Wrapper Constant
// ---------------------------------
if (!defined('THEMECOMMENTS')) {
	$vhtmlcomments = false;
	if ( (isset($vthemesettings['htmlcomments'])) && ($vthemesettings['htmlcomments'] == '1') ) {$vhtmlcomments = true;}
	$vhtmlcomments = bioship_apply_filters('skeleton_html_comments', $vhtmlcomments);
	define('THEMECOMMENTS', $vhtmlcomments);
}

// maybe Output HTML Comment
// -------------------------
// 2.0.8: new function to reduce comment code clutter in templates
if (!function_exists('bioship_html_comment')) {
 function bioship_html_comment($vcomment) {
 	if (defined('THEMECOMMENTS') && THEMECOMMENTS) {echo "<!-- ".$vcomment." -->".PHP_EOL;}
 }
}

// set Javascript Cache Busting
// ----------------------------
global $vjscachebust; $vcachebust = $vthemesettings['javascriptcachebusting'];
if ( ($vcachebust == 'yearmonthdate') || ($vcachebust == '') ) {$vjscachebust = date('ymd').'0000';}
if ($vcachebust == 'yearmonthdatehour') {$vjscachebust = date('ymdH').'00';}
if ($vcachebust == 'datehourminutes') {$vjscachebust = date('ymdHi');}
if ($vcachebust == 'themeversion') {$vjscachebust = THEMEVERSION;}
if ($vcachebust == 'childversion') {$vjscachebust = THEMECHILDVERSION;}
if ($vcachebust == 'filemtime') {clearstatcache();} // 1.9.5: clear stat cache here

// set Stylesheet Cache Busting
// ----------------------------
global $vcsscachebust; $vcachebust = $vthemesettings['stylesheetcachebusting'];
if ( ($vcachebust == 'yearmonthdate') || ($vcachebust == '') ) {$vcsscachebust = date('ymd').'0000';}
if ($vcachebust == 'yearmonthdatehour') {$vcsscachebust = date('ymdH').'00';}
if ($vcachebust == 'datehourminutes') {$vcsscachebust = date('ymdHi');}
if ($vcachebust == 'themeversion') {$vcsscachebust = THEMEVERSION;}
if ($vcachebust == 'childversion') {$vcsscachebust = THEMECHILDVERSION;}
if ($vcachebust == 'filemtime') {clearstatcache();} // 1.9.5: clear stat cache here


// Declare WooCommerce Support
// ---------------------------
// ref: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
// WARNING: Do NOT create a woocommerce.php page template for your theme!
// This is a 'beginner mistake' as it prevents you from overriding default templates
// such as single-product.php and archive-product.php later (see class-wc-template-loader.php)
$vwoosupport = bioship_apply_filters('skeleton_declare_woocommerce_support', false);
if ($vwoosupport) {add_theme_support('woocommerce');}
else {
	// 1.8.0: auto-remove the 'this theme does not declare WooCommerce support' notice
	if (class_exists('WC_Admin_Notices')) {
		if (!function_exists('bioship_remove_woocommerce_theme_notice')) {
			add_action('admin_notices', 'bioship_remove_woocommerce_theme_notice');
			function bioship_remove_woocommerce_theme_notice() {
				if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
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
	// 2.0.9: added missing filter for this theme setting
	$vpostformats = bioship_apply_filters('skeleton_post_format_support', $vthemesettings['postformats']);
	foreach ($vpostformats as $vpostformat => $vvalue) {
		if ($vvalue == '1') {$vpostformatsupport[$vi] = $vpostformat; $vi++;}
	}
	if (count($vpostformatsupport) > 0) {add_theme_support('post-formats', $vpostformatsupport);}
}

// maybe Output Theme Debug Info
// -----------------------------
if (THEMEDEBUG) {
	// 2.0.9: converted to bioship_debug calls
	if (defined('THEMEDRIVE') && THEMEDRIVE) {bioship_debug("Theme Test Drive is ACTIVE");}
	bioship_debug("Stylesheet Dir", $vthemestyledir);
	bioship_debug("Stylesheet URL", $vthemestyleurl);
	bioship_debug("Theme Template Dir", $vthemetemplatedir);
	bioship_debug("Theme Template URL", $vthemetemplateurl);
	bioship_debug("Active Theme Object", $vtheme);
	bioship_debug("Sidebars/Widgets", get_option('sidebars_widgets'));
	bioship_debug("Nav Menu Settings", get_option('nav_menu_options'));
	bioship_debug("Theme Mods", get_option('theme_mods_'.str_replace('_','-',$vthemename)));
	bioship_debug("Theme Options (".THEMEKEY.")", $vthemesettings);
}


// ------------------
// === CUSTOMIZER ===
// ------------------

// Customizer Loader
// -----------------
// 1.8.0: conditionally load Customizer options via customizer.php
if (!function_exists('bioship_customizer_loader')) {

 // 2.0.9: changed to earlier loading action (for Kirki 3)
 add_action('after_setup_theme', 'bioship_customizer_loader', 5);

 function bioship_customizer_loader($vpreviewwindow=false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (function_exists('bioship_customizer_preview')) {return true;}

	// 2.0.9: check conditions if not in preview window
	if (!$vpreviewwindow) {
		// TODO: maybe improve conditional loading here?
		if (!is_admin()) {return false;}
		if (!is_user_logged_in()) {return false;}
		if (!current_user_can('edit_theme_options')) {return false;}
	}

	global $vthemedirs;
	$vcustomizer = bioship_file_hierarchy('file', 'customizer.php', $vthemedirs['admin']);
	if ($vcustomizer) {include_once($vcustomizer);}

	// 2.0.9: maybe initialize Kirki library now
	bioship_kirki_loader();

	if ($vcustomizer) {return true;} else {return false;}
 }
}

// Customizer API Load Controls
// ----------------------------
if (!function_exists('bioship_customizer_init')) {
 add_action('customize_register', 'bioship_customizer_init');
 function bioship_customizer_init($wp_customize) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	remove_action('customize_register', 'bioship_customizer_init');

	// 2.0.9: load Customizer functions for the preview window
	$vloaded = bioship_customizer_loader();
	if ($vloaded) {
		// register and load Customizer controls
		bioship_customizer_register_controls($wp_customize);
		bioship_customizer_load_control_options($wp_customize);
		// load scripts in the footer
		add_action('customize_controls_print_footer_scripts', 'bioship_customizer_text_script');
	}
 }
}

// Customizer Preview Window
// -------------------------
// note: controls do not need to be registered for preview window
if (!function_exists('bioship_customizer_preview_loader')) {
 add_action('customize_preview_init', 'bioship_customizer_preview_loader');
 function bioship_customizer_preview_loader() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$vloaded = bioship_customizer_loader(true);
	if (!$vloaded) {return;}

 	// 2.0.9: make sure to register and load controls in preview window
 	// (otherwise active states are not set and controls, panels and sections disappear!)
 	global $wp_customize;
 	bioship_customizer_register_controls($wp_customize);
	bioship_customizer_load_control_options($wp_customize);

	// load the Customizer Live Preview javascript in footer
	add_action('wp_footer', 'bioship_customizer_preview', 21);
 }
}

// Customizer Loading Image
// ------------------------
// 1.8.5: added Kirki preview loading image override
// 1.9.5: moved this to Customizer Section from stylesheet loading
global $pagenow;
if ( (is_customize_preview()) && ($pagenow != 'customize.php') ) {
	if (!function_exists('bioship_customizer_loading_icon')) {
	 add_action('wp_head', 'bioship_customizer_loading_icon');
	 function bioship_customizer_loading_icon() {
		// 1.8.5: use bioship loading image
		global $vthemedirs; $vloadingimage = bioship_file_hierarchy('url', 'customizer-loading.png', $vthemedirs['image']);
		if ($vloadingimage) {echo "<style>.kirki-customizer-loading-wrapper {background-image: url('".$vloadingimage."') !important;}</style>";}
	 }
	}
}


// -------------------
// === HYBRID CORE ===
// -------------------

global $vhybrid;
$vhybridloadcore = $vthemesettings['hybridloadcore'];
if ( ($vhybridloadcore == '1') || ($vhybridloadcore == '2') || ($vhybridloadcore == '3') ) {

	// set flags for Hybrid loading
	$vhybrid = true; define('THEMEHYBRID', true);

	// 1.8.0: set hybrid core library version to use
	// note: value 1 or 2 = Hybrid Core 2 (was a checkbox), value 3 = Hybrid Core 3
	if ( ($vhybridloadcore == '1') || ($vhybridloadcore == '2') ) {
		// 2.0.9: set fallback to Hybrid 3 in case Hybrid 2 has been removed (ie. deprecated)
		$vhybridversion = '2'; $vhybriddirs = array('includes/hybrid2', 'includes/hybrid3');
	} elseif ($vhybridloadcore == '3') {$vhybridversion= '3'; $vhybriddirs = array('includes/hybrid3');}
	// 2.0.9: filter the Hybrid directory search paths
	$vhybriddirs = bioship_apply_filters('hybrid_dirs', $vhybriddirs);

	// load Hybrid from child or theme directory
	// (usage note: if loading from Child Theme, ALL of Hybrid Core must be there - not just hybrid.php)
	// 2.0.9: use file hierarchy to find Hybrid file path
	$vhybridpath = bioship_file_hierarchy('file', 'hybrid.php', $vhybriddirs);
	if (!$vhybridpath) {wp_die(__('Uh oh, the required Hybrid Core Framework is missing!','bioship'));}
	$vhybriddir = trailingslashit(dirname($vhybridpath));

	// initialize Hybrid Core
	// ----------------------
	define('HYBRID_DIR', $vhybriddir);
	require_once($vhybridpath);
	new Hybrid(); // initialize Hybrid

	// Add Hybrid Core Setup Hook
	// --------------------------
	add_action('after_setup_theme', 'bioship_hybrid_core_setup', 11);

	if (!function_exists('bioship_hybrid_core_setup')) {
	 function bioship_hybrid_core_setup() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// Hybrid Core Setup
		// -----------------

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

		// ...the rest of these were mostly just for Hybrid 2...
		// - Hybrid Shortcodes
		// deactivated shortcodes for now... is this even in HC3?
		// if ($vthemesettings['hybridshortcodes'] == '1') {add_theme_support('hybrid-core-shortcodes');}
		// - Pagination -
		// 1.8.0: removed in Hybrid Core v3
		// if ($vthemesettings['hybridpagination'] == '1') {add_theme_support('loop-pagination');}
		// - Better captions - for themes to style
		// note: removed in Hybrid Core v3
		// if ($vthemesettings['hybridcaptions'] == '1') {add_theme_support('cleaner-caption');}
		// - Per Page Featured Header image - (removed as custom_header no longer used)
		// if ($vthemesettings['hybridfeaturedheaders'] == '1') {add_theme_support('featured-header');}
		// - Random Custom Background
		// note: removed in Hybrid Core v3
		// if ($vthemesettings['hybridrandombackground'] == '1') {add_theme_support('random-custom-background');}
		// - Per Post Stylesheets -
		// 1.5.0: removed as handled by theme metabox
		// if ($vthemesettings['hybridpoststylesheets'] == '1') {add_theme_support('post-stylesheets');}

		// 1.3.5: WP < 3.9 Fix: remove hybrid_image_size_names_choose (media.php) // >
		// causing editor footer scripts to crash prior to 3.9 (to support WP 3.4+)
		// (avoids fatal error: has_image_size function does not exist)
		global $wp_version;
		if (version_compare($wp_version,'3.9','<')) { //'>
			remove_filter('image_size_names_choose', 'hybrid_image_size_names_choose');
		}

		// Fix: Remove the Hybrid Title Action (for Hybrid 2)
		// 2.0.5: check action is hooked before removing
		// 2.0.8: revert to not check action as preventing removal
		remove_action('wp_head', 'hybrid_doctitle', 0);
	 }
	}
} else {

	// Non-Hybrid Fallback Loading
	// ---------------------------

	// 1.8.0: set constant flag for non-Hybrid
	$vhybrid = false; define('THEMEHYBRID', false);

	// 2.0.5: add HTML5 support without Hybrid
	add_theme_support('html5', array('caption', 'comment-form', 'comment-list', 'gallery', 'search-form'));

	// Enable Shortcodes in Widget Text/Titles (as loading Hybrid Core adds this)
	// 1.9.8: added has_filter check
	// note: https://core.trac.wordpress.org/changeset/41361
	// TODO: change this in conjunction with discreet text widget (filtered)?
	if (!has_filter('widget_text', 'do_shortcode')) {add_filter('widget_text', 'do_shortcode');}

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
	if (!function_exists('bioship_load_hybrid_media')) {
		function bioship_load_hybrid_media() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
			require_once($vthemetemplatedir.'includes/hybrid3-media-grabber.php');
		}
	}

	// 1.8.5: moved here from header.php to prevent duplicate if using Hybrid
	add_action('wp_head', 'bioship_meta_charset', 0);
	if (!function_exists('bioship_meta_charset')) {
	 function bioship_meta_charset() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	 	echo '<meta charset="'.get_bloginfo('charset').'">';
	 }
	}

	// add pingback link to document head
	add_action('wp_head', 'bioship_pingback_link', 3);
	if (!function_exists('bioship_pingback_link')) {
	 function bioship_pingback_link() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (get_option('default_ping_status') == 'open') {
			echo '<link rel="pingback" href="'.get_bloginfo('pingback_url').'">';
		}
	 }
	}

	// 1.8.0: fix as missing for HC3 (from functions-sidebar.php)
	if (!function_exists('hybrid_get_sidebar_name')) {
	 function hybrid_get_sidebar_name($sidebar_id) {
		global $wp_registered_sidebars;
		if (!isset($wp_registered_sidebars[$sidebar_id])) {return '';}
		return $wp_registered_sidebars[$sidebar_id]['name'];
	 }
	}
}

// Fix to Main Element IDs
// -----------------------
// 1.5.0: prevent duplicate element IDs via Hybrid attribute filters (eg. content and maincontent)
// 2.0.5: added missing function_exists wrappers to attribute fixes
if (!function_exists('bioship_hybrid_attr_header')) {
 add_filter('hybrid_attr_header', 'bioship_hybrid_attr_header', 6);
 function bioship_hybrid_attr_header($vattr) {$vattr['id'] = 'mainheader'; return $vattr;}
}
if (!function_exists('bioship_hybrid_attr_site_description')) {
 // 1.9.0: added this one for new site-description attribute duplicate
 add_filter('hybrid_attr_site-description', 'bioship_hybrid_attr_site_description', 6);
 function bioship_hybrid_attr_site_description($vattr) {$vattr['id'] = 'site-desc'; return $vattr;}
}
if (!function_exists('bioship_hybrid_attr_content')) {
 add_filter('hybrid_attr_content', 'bioship_hybrid_attr_content', 6);
 function bioship_hybrid_attr_content($vattr) {$vattr['id'] = 'maincontent'; return $vattr;}
}
if (!function_exists('bioship_attr_footer')) {
 add_filter('hybrid_attr_footer', 'bioship_hybrid_attr_footer', 6);
 function bioship_hybrid_attr_footer($vattr) {$vattr['id'] = 'mainfooter'; return $vattr;}
}


// =============
// === SKULL ===
// =============

// Load all the Skull - helpers, setup and head calculation functions
// (override pluggable skull functions in Child Theme functions.php)

$vskull = bioship_file_hierarchy('file', 'skull.php');
require_once($vskull);


// ================
// === SKELETON ===
// ================

// 1.9.0: load hook defintions before skeleton.php
$vhooks = bioship_file_hierarchy('file', 'hooks.php');
require_once($vhooks);

// Load all the Skeleton - layout and template tag functions
// (override pluggable skeleton functions in Child Theme functions.php)

$vskeleton = bioship_file_hierarchy('file', 'skeleton.php');
require_once($vskeleton);


// ==============
// === MUSCLE ===
// ==============

// Load all the Muscle - extended theme functions
// (override pluggable muscle functions in Child Theme functions.php)

$vmuscle = bioship_file_hierarchy('file', 'muscle.php');
if ($vmuscle) {require_once($vmuscle);}


// ============
// === SKIN ===
// ============
// note: skin.php is used for outputting the Dynamic CSS
// but all the Skin trigger and enqueueing functions here

// Frontend Stylesheets
// --------------------
// 2.0.5: use THEMESLUG constant insted of vthemename
if (!function_exists('bioship_skin_enqueue_styles')) {

 // 2.0.5: moved add_action inside for consistency
 add_action('wp_enqueue_scripts', 'bioship_skin_enqueue_styles');

 function bioship_skin_enqueue_styles() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vcsscachebust, $vthemedirs;

	// maybe use file modified time cachebusting
	$vfilemtime = false; if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// Combined Stylesheet
	// -------------------
	if ($vthemesettings['combinecsscore']) {
		$vcorestyles = bioship_file_hierarchy('both', 'core-styles.css', $vthemedirs['style']);
		if (!is_array($vcorestyles)) {$vcombinefail = true;}
		else {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vcorestyles['file']));}
			// 2.0.1: add themename prefix to style handle
			wp_register_style(THEMESLUG.'-core', $vcorestyles['url'], array(), $vcsscachebust);
			wp_enqueue_style(THEMESLUG.'-core'); $vcoredep = array($vthemename.-'core-styles');

			// theme style.css (note: must be separate or CSS breaks)
			if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime(get_stylesheet(THEMESLUG)));}
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style(THEMESLUG.'-styles', get_stylesheet_uri(THEMESLUG), $vcoredep, $vcsscachebust);
		}
	}

	// or Individual Stylesheets
	// -------------------------
	if ( (!$vthemesettings['combinecsscore']) || ($vcombinefail) ) {

		$vcoredep = array(); $vmaindep = array(); $vdep = array();

		// Normalize or Reset CSS
		// ----------------------
		if ($vthemesettings['cssreset'] == 'normalize') {
			$vnormalize = bioship_file_hierarchy('both', 'normalize.css', $vthemedirs['style']);
			if (is_array($vnormalize)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vnormalize['file']));}
				wp_register_style('normalize', $vnormalize['url'], array(), $vcsscachebust);
				wp_enqueue_style('normalize'); $vmaindep = array('normalize');
			}
		}
		elseif ($vthemesettings['cssreset'] == 'reset') {
			$vreset = bioship_file_hierarchy('both', 'reset.css', $vthemedirs['style']);
			if (is_array($vreset)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vreset['file']));}
				wp_register_style('reset', $vreset['url'], array(), $vcsscachebust);
				wp_enqueue_style('reset'); $vmaindep = array('reset');
			}
		}
		elseif ( ($vthemesettings['cssreset'] == 'none') || ($vthemesettings['cssreset'] == '') ) {$vmaindep = array();}

		// Dynamic Grid Stylesheet
		// -----------------------
		// 1.5.0: layout stylesheets replaced with new dynamic grid
		// 1.5.0: [deprecated skeleton-960.css, skeleton-1140.css, skeleton-1200.css]
		// 1.8.0: allow for direct load method
		// 1.5.0: added dynamic grid stylesheet
		if ($vthemesettings['themecssmode'] == 'adminajax') {
			$vgridurl = admin_url('admin-ajax.php').'?action=grid_dynamic_css';
		} else {$vgridurl = bioship_file_hierarchy('url', 'grid.php');}

		// 1.8.0: pass content width for calculating content grid
		// 1.8.5: fix to grid URL query separator for admin ajax method
		// 1.9.0: start to pass filtered variables via querystring
		// 1.9.5: pass more filtered variables via querystring
		// 2.0.5: use add_query_arg for all querystring arguments
		global $vthemelayout;

		// 2.0.5: convert values to numbers before passing
		$vgridcolumns = bioship_word_to_number($vthemelayout['gridcolumns']);
		$vgridurl = add_query_arg('gridcolumns', $vgridcolumns, $vgridurl);
		$vcontentcolumns = bioship_word_to_number($vthemelayout['contentgridcolumns']);
		$vgridurl = add_query_arg('contentgridcolumns', $vcontentcolumns, $vgridurl);

		$vgridurl = add_query_arg('contentwidth', $vthemelayout['rawcontentwidth'], $vgridurl);
		$vgridurl = add_query_arg('maxwidth', $vthemelayout['maxwidth'], $vgridurl);
		// 2.0.5: pass calculated content padding value directly via querystring
		$vgridurl = add_query_arg('contentpadding', $vthemelayout['contentpadding'], $vgridurl);

		// 2.0.5: pass filtered options for layout and content grid spacing
		// TODO: maybe distinguish grid padding from grid margins?
		$vgridspacing = false; $vcontentspacing = false;
		if (isset($vthemelayout['gridspacing'])) {$vgridspacing = $vthemelayout['gridspacing'];}
		$vgridspacing = bioship_apply_filters('skeleton_layout_grid_spacing', $vgridspacing);
		if ($vgridspacing) {$vgridurl = add_query_arg('gridspacing', $vgridspacing, $vgridurl);}
		if (isset($vthemelayout['contentspacing'])) {$vcontentspacing = $vthemelayout['contentspacing'];}
		$vcontentspacing = bioship_apply_filters('skeleton_content_grid_spacing', $vcontentspacing);
		if ($vcontentspacing) {$vgridurl = add_query_arg('contentspacing', $vcontentspacing, $vgridurl);}

		// 1.8.5: set theme variable to allow for Multiple Themes usage
		// 2.0.5: removed as no longer checking theme settings in grid.php
		// $vgridtheme = get_option('stylesheet');
		// if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {$vgridtheme = $_REQUEST['theme'];}
		// $vgridurl = add_query_arg('theme', $vgridtheme, $vgridurl);

		// 2.0.5: also pass grid compatibility in querystring
		$vgridcompat = array();
		if (isset($vthemesettings['gridcompatibility'])) {
			if (is_array($vthemesettings['gridcompatibility'])) {
				// 1.9.5: convert cross-framework multicheck options
				$vcompatibility = $vthemesettings['gridcompatibility'];
				if ( (isset($vcompatibility['960gridsystem'])) && ($vcompatibility['960gridsystem'] == '1') ) {$vgridcompat['960gs'] = '1';}
				if ( (isset($vcompatibility['blueprint'])) && ($vcompatibility['blueprint'] == '1') ) {$vgridcompat['blueprint'] = '1';}
				if (in_array('960gs', $vcompatibility)) {$vgridcompat['960gs'] = '1';}
				if (in_array('blueprint', $vcompatibility)) {$vgridcompat['blueprint'] = '1';}
			}
		}
		if (count($vgridcompat) > 0) {
			$vgridcompat = implode(',',$vgridcompat);
			add_query_arg('compat', $vgridcompat, $vgridurl);
		}

		// 2.0.5: also pass breakpoints in querystring
		if (isset($vthemesettings['breakpoints'])) {
			$vbreakpoints = $vthemesettings['breakpoints'];
			$vbreakpoints = bioship_apply_filters('skeleton_media_breakpoints', $vbreakpoints);
			$vgridurl = add_query_arg('breakpoints', $vbreakpoints, $vgridurl);
		}

		// 1.8.5: maybe use last theme option save time for cache busting
		if ($vfilemtime) {
			if ( (isset($vthemesettings['savetime'])) && ($vthemesettings['savetime'] != '') ) {$vtime = $vthemesettings['savetime'];}
			// 2.0.7: fix for possible non-numeric bugginess
			if ( (!isset($vtime)) || (!is_numeric($vtime)) ) {$vtime = time();}
			$vcsscachebust = date('YmdHi', $vtime);
		}

		// Load grid.php directly or via admin-ajax.php
		// --------------------------------------------
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style(THEMESLUG.'-grid', $vgridurl, $vmaindep, $vcsscachebust);

		// Grid URL for Customizer Preview
		// -------------------------------
		// 1.9.8: fix to declare pagenow global
		global $pagenow;
		if ( (is_customize_preview()) && ($pagenow != 'customize.php') ) {

			// For Customizer Preview Load in Header/Footer
			// ...but somehow doing this breaks Customizer ..?
			//	if ($vthemesettings['themecssmode'] == 'header') {
			//		add_action('wp_head', 'bioship_grid_dynamic_css_inline');
			//	} else {add_action('wp_footer', 'bioship_grid_dynamic_css_inline');}

			add_action('customize_preview_init', 'bioship_grid_url_reference');
			if (!function_exists('bioship_grid_url_reference')) {
			 function bioship_grid_url_reference() {
				global $vgridurl; echo '<a href="'.$vgridurl.'" id="grid-url" style="display:none;"></a>';
			 }
			}
		}

		// mobile.css
		// ----------
		// 1.5.0: changed from layout.css (misleading name)
		$vmobile = bioship_file_hierarchy('both', 'mobile.css', $vthemedirs['style']);
		if (is_array($vmobile)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vmobile['file']));}
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style(THEMESLUG.'-mobile',$vmobile['url'], $vmaindep, $vcsscachebust);
		}

		// Main Theme Stylesheets
		// ----------------------

		// Formalize.css
		// -------------
		if ($vthemesettings['loadformalize']) {
			$vformalize = bioship_file_hierarchy('both', 'formalize.css', $vthemedirs['style']);
			if (is_array($vformalize)) {
				if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vformalize['file']));}
				// note: intentionally not theme-prefixed (common core stylesheet library)
				wp_enqueue_style('formalize', $vformalize['url'], $vmaindep, $vcsscachebust, 'screen, projection');
			}
		}

		// skeleton.css
		// ------------
		$vskeletoncss = bioship_file_hierarchy('both', 'skeleton.css', $vthemedirs['style']);
		if (is_array($vskeletoncss)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vskeletoncss['file']));}
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style(THEMESLUG.'-skeleton', $vskeletoncss['url'], $vmaindep, $vcsscachebust);
			$vdep = array(THEMESLUG.'-skeleton');
		}

		// style.css
		// ---------
		$vdep = array_merge($vmaindep, $vdep);
		// 2.0.0: fix for filemtime cachebusting stylesheet filepath
		$vstylesheetpath = get_stylesheet_directory().DIRSEP.'style.css';
		if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vstylesheetpath));}
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style(THEMESLUG.'-styles', get_stylesheet_uri(THEMESLUG), $vdep, $vcsscachebust);

	}

	// Superfish menu
	// --------------
	$vsuperfish = bioship_file_hierarchy('both', 'superfish.css', $vthemedirs['style']);
	if (is_array($vsuperfish)) {
		if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vsuperfish['file']));}
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style(THEMESLUG.'-superfish', $vsuperfish['url'], $vcoredep, $vcsscachebust, 'screen, projection');
	}

	// custom.css
	// ----------
	// auto-loaded CSS (only if file is found)
	$vcustomcss = bioship_file_hierarchy('both', 'custom.css', $vthemedirs['style']);
	if (is_array($vcustomcss)) {
		if ($vfilemtime) {$vcsscachebust = date('ymdHi', filemtime($vcustomcss['file']));}
		// 2.0.1: add themename prefix to style handle, remove css suffix
		wp_enqueue_style(THEMESLUG.'-custom', $vcustomcss['url'], $vcoredep, $vcsscachebust);
	}

	// Hybrid Cleaner Gallery
	// ----------------------
	if (current_theme_supports('cleaner-gallery')) {
		// 1.8.0: use Hybrid version-specific CSS, added missing cachebuster
		if ($vthemesettings['hybridloadcore'] == '3') {$vhybriddir = 'hybrid3';} else {$vhybriddir = 'hybrid2';}
		$vgallerycss = bioship_file_hierarchy('both', 'gallery.css', array($vhybriddir.'/css','css'));
		if (is_array($vgallerycss)) {
			if ($vfilemtime) {$vcsscachebust = date('ymdHi',filemtime($vgallerycss['file']));}
			wp_enqueue_style('hybrid-gallery', $vgallerycss['url'], array(), $vcsscachebust);
		}
	}

	// Enqueue Dynamic Stylesheet Skin
	// -------------------------------
	// 1.5.0: tested direct skin loader option for performance
	// 1.8.0: moved here for better enqueueing
	$vcssmode = $vthemesettings['themecssmode'];

	// 1.8.0: allow for header/footer inline page load
	// 1.8.5: fix to wrap header/footer output in style tags!
	if ($vcssmode == 'header') {add_action('wp_head', 'bioship_skin_dynamic_css_inline');}
	elseif ($vcssmode == 'footer') {add_action('wp_footer', 'bioship_skin_dynamic_css_inline');}
	else {
		// 2.0.5: set default to admin ajax skin load
		$vskinurl = admin_url('admin-ajax.php').'?action=bioship_skin_dynamic_css';

		// 2.0.8: only use direct method if not WP.org version
		if (!THEMEWPORG && ($vcssmode == 'direct')) {
			$vskin = bioship_file_hierarchy('both', 'skin.php', $vthemedirs['core']);
			if (is_array($vskin)) {
				// 2.0.5: check path to wp-load.php is above skin.php before using!
				$vfileincludes = get_included_files();
				foreach ($vfileincludes as $vfilepath) {
					$vpathinfo = pathinfo($vfilepath);
					if ($vpathinfo['basename'] == 'wp-load.php') {
						$vloadpath = dirname($vfilepath); continue;
					}
				}
				$vskinpath = substr($vskin['file'], 0, strlen($vloadpath));
				if ($vskinpath === $vloadpath) {$vskinurl = $vskin['url'];}
				bioship_debug("WP Load Directory", $vloadpath);
				bioship_debug("Skin Directory to Match", $vskinpath);
			}
		}
		bioship_debug("Skin URL", $vskinurl);

		// 1.8.5: set anyway to allow for Multiple Themes/Theme Test Drive override
		$vskintheme = get_stylesheet();
		if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '') ) {
			// 2.0.9: check this is a valid theme before allowing override
			$vtheme = wp_get_theme($_REQUEST['theme']);
			if ( (is_object($vtheme)) && ($vtheme instanceof WP_Theme) ) {
				$vskintheme = $_REQUEST['theme'];
			}
		}
		$vskinurl = add_query_arg('theme', $vskintheme, $vskinurl);

		if ($vfilemtime) {
			// 1.8.5: maybe use last theme options saved time for cachebusting
			// 2.0.7: fix to key typo (savedtime) and possible non-numeric bugginess
			if (isset($vtime)) {unset($vtime);}
			if ( (isset($vthemesettings['savetime'])) && ($vthemesettings['savetime'] != '') ) {$vtime = $vthemesettings['savetime'];}
			if ( (!isset($vtime)) || (!is_numeric($vtime)) ) {$vtime = time();}
			$vcsscachebust = date('ymdHi', $vtime);
		}
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style(THEMESLUG.'-skin', $vskinurl, array(), $vcsscachebust);
	}

	// maybe Disable Emojis
	// --------------------
	// 1.9.5: added disable emojis option
	// 2.0.9: fix to setting and filter logic
	// 2.1.0: fix to default variable name
	$vdisableemojis = false;
	if ( (isset($vthemesettings['disablemojis'])) && ($vthemesettings['disableemojis'] == '1') ) {
		$vdisableemojis = true;
	}
	// 2.0.5: added this missing filter check
	$vdisableemojis = bioship_apply_filters('skeleton_disable_emojis', $vdisableemojis);
	if ($vdisableemojis) {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');
	}

	// deregister WP PageNavi Style
	// ----------------------------
	// (native style support via imported skeleton.css styles)
	if (!function_exists('bioship_skin_deregister_styles')) {
		add_action('wp_print_styles', 'bioship_skin_deregister_styles', 100);
		function bioship_skin_deregister_styles() {wp_deregister_style('wp-pagenavi');}
	}

	// Typography Heading Fonts
	// ------------------------
	bioship_do_action('bioship_skin_typography');

	// Better WordPress Minify Integration
	// -----------------------------------
	// 2.0.2: automatically ignore some styles for BWP plugin
	// (as bwp_minify_ignore filter has been missed)
	global $bwp_minify;
	if ( (is_object($bwp_minify)) && (property_exists($bwp_minify,'print_positions')) ) {
		$vpositions = $bwp_minify->print_positions;
		if ( (is_array($vpositions)) && (isset($vpositions['style_ignore'])) ) {
			$vhandles = $vpositions['style_ignore'];
			$vnominifystyles = array(THEMESLUG.'-core', THEMESLUG.'-skeleton', THEMESLUG.'-mobile', THEMESLUG.'-styles');
			$vnominifystyles = apply_filters('bioship_bwp_nominify_styles', $vnominifystyles);
			foreach ($vnominifystyles as $vhandle) {
				if (!in_array($vhandle,$vhandles)) {$vhandles[] = $vhandle;}
			}
			if ($vhandles != $vpositions['style_ignore']) {
				$vpositions['style_ignore'] = $vhandles;
				$bwp_minify->print_positions = $vpositions;
				bioship_debug("BWP Ignore Styles", $vhandles);
			}
		}
	}

 }
}

// Enqueue Admin Stylesheets
// -------------------------
if (!function_exists('bioship_skin_enqueue_admin_styles')) {

 // 1.8.5: fix to admin script enqueue typo
 // 2.0.5: moved add action inside for consistency
 add_action('admin_enqueue_scripts', 'bioship_skin_enqueue_admin_styles');

 // 2.0.9: added hook parameter to function argument
 function bioship_skin_enqueue_admin_styles($vhook = false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// 1.9.8: fix add missing global vcsscachebust here
	global $vthemename, $vthemesettings, $vthemedirs, $vcsscachebust;

	// Dynamic Admin Stylesheet
	// ------------------------
	if ($vthemesettings['dynamicadmincss'] != '') {

		$vcssmode = $vthemesettings['themecssmode'];
		// 2.0.1: maybe use separate option for admin styles loading mode?
		if (isset($vthemesettings['admincssmode'])) {$vcssmode = $vthemesettings['admincssmode'];}

		if ($vcssmode == 'adminajax') {$vskinurl = admin_url('admin-ajax.php').'?action=bioship_skin_dynamic_admin_css';}
		elseif ($vcssmode == 'direct') {
			$vskinurl = bioship_file_hierarchy('url', 'skin.php', $vthemedirs['core']);
			// 1.8.5: set admin styles load via querystring
			$vskinurl = add_query_arg('adminstyles', 'yes', $vskinurl);
		}

		// 1.8.5: use add_query_arg here
		$vskintheme = get_stylesheet();
		if (isset($_REQUEST['theme'])) {if ($_REQUEST['theme'] != '') {$vskintheme = $_REQUEST['theme'];} }
		$vskinurl = add_query_arg('theme', $vskintheme, $vskinurl);

		// 1.8.5: wrap in style for inline header/footer printing
		if ($vthemesettings['themecssmode'] == 'header') {add_action('admin_head','bioship_skin_dynamic_admin_css_inline');}
		elseif ($vthemesettings['themecssmode'] == 'footer') {add_action('admin_footer','bioship_skin_dynamic_admin_css_inline');}
		else {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				if ( (isset($vthemesettings['savetime'])) && ($vthemesettings['savetime'] != '') ) {$vtime = $vthemesettings['savetime'];}
				// 2.0.7: fix to non-numeric saved time bugginess
				if ( (!isset($vtime)) || (!is_numeric($vtime)) ) {$vtime = time();}
				$vcsscachebust = date('ymdHi', $vtime);
			}
			// 2.0.1: prefix style handle with theme name
			wp_enqueue_style($vthemename.'-admin-skin', $vskinurl, array(), $vcsscachebust);
		}
	}

	// Formalize.css
	// -------------
	if ($vthemesettings['loadformalize']) {
		$vformalize = bioship_file_hierarchy('both', 'formalize.css', $vthemedirs['style']);
		if (is_array($vformalize)) {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$vcsscachebust = date('ymdHi', filemtime($vformalize['file']));
			}
			// 1.9.8: fix to remove unneeded dependency
			wp_enqueue_style('formalize', $vformalize['url'], array(), $vcsscachebust, 'screen, projection');
		}
	}

	// maybe Disable Emojis
	// --------------------
	// 1.9.5: added disable emojis option
	// 2.0.9: fix to setting and filter logic
	// 2.1.0: fix to default variable typo
	$vdisableemojis = false;
	if ( (isset($vthemesettings['disablemojis'])) && ($vthemesettings['disableemojis'] == '1') ) {
		$vdisableemojis = true;
	}
	// 2.0.9: added this missing filter check
	$vdisableemojis = bioship_apply_filters('skeleton_disable_emojis', $vdisableemojis);
	if ($vdisableemojis) {
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('admin_print_styles', 'print_emoji_styles');
	}

	// Editor Styles
	// -------------
	// 1.9.5: for editor styles, maybe enqueue Google fonts for post writing / editing pages
	if ( (isset($vthemesettings['dynamiceditorstyles'])) && ($vthemesettings['dynamiceditorstyles'] == '1') ) {
		global $pagenow;
		if ( ($pagenow == 'post.php') || ($pagenow == 'edit.php') ) {
			bioship_do_action('bioship_skin_typography');
		}
	}

	// Sticky Widget Areas
	// -------------------
	// 2.0.9: add sticky widgets admin page styles
	// ref: https://github.com/srikat/admin-sticky-widget-areas
	if ( ($vhook) && ($vhook == 'widgets.php') ) {
		$vstickywidgets = bioship_file_hierarchy('both', 'sticky-widgets.css', $vthemedirs['style']);
		if (is_array($vformalize)) {
			if ($vthemesettings['stylesheetcachebusting'] == 'filemtime') {
				$vcsscachebust = date('ymdHi', filemtime($vstickywidgets['file']));
			}
			wp_enqueue_style('stick-widgets', $vstickywidgets['url'], $vcsscachebust);
		}
	}

 }
}

// Heading Typography
// ------------------
// autoload any requested fonts from Google Fonts...
// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (see muscle.php)

if (!function_exists('bioship_skin_typography_loader')) {

 add_action('bioship_skin_typography', 'bioship_skin_typography_loader');

 function bioship_skin_typography_loader() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;

	// Handle Extra Font options
	// -------------------------
	if ($vthemesettings['extrafonts'] != '') {
		if (strstr($vthemesettings['extrafonts'],',')) {
			$vextrafonts = explode(',',$vthemesettings['extrafonts']);
		} else {$vextrafonts[0] = $vthemesettings['extrafonts'];}
		// 2.0.5: use simple array key index
		foreach ($vextrafonts as $vi => $vextrafont) {
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
			// 2.0.5: start extra fonts at 1 not 0
			$vfonts['extra'.($vi+1)] = $vthisfont;
		}
	}
	// print_r($vfonts); // debug point

	// 1.9.0: check for Raleway default in body/section font stacks...
	$vbodyfonts = array('body', 'header', 'navmenu', 'navsubmenu',
			'sidebar', 'subsidebar', 'content', 'footer', 'button');
	foreach ($vbodyfonts as $vbodyfont) {
		$vthisfont = $vthemesettings[$vbodyfont.'_typography'];
		if (isset($vthisfont['face'])) {$vthisface = $vthisfont['face'];}
		elseif (isset($vthisfont['font-family'])) {$vthisface = $vthisfont['font-family'];}
		if (substr($vthisface,0,strlen('"Raleway"')) == '"Raleway"') {
			// 2.0.5: double check existing to prevent duplicates
			$vaddfont = true;
			foreach ($vfonts as $vfont) {
				if ($vfont['face'] == 'Raleway') {$vaddfont = false;}
			}
			if ($vaddfont) {
				// 2.0.5: count extra fonts to get next index
				$vi = count($vextrafonts) + 1;
				$vthisfont['face'] = 'Raleway';	$vfonts['extra'.$vi] = $vthisfont; break;
			}
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
	// 1.9.8: fix to key typo: use header_texts not header_text
	if ($vthemesettings['header_texts']['sitetitle'] == '1') {$vfonts['headline'] = $vthemesettings['headline_typography'];}
	if ($vthemesettings['header_texts']['sitedescription'] == '1') {$vfonts['tagline'] = $vthemesettings['tagline_typography'];}

	// print_r($vfonts); // debug point

	// Autoload Selected Fonts (avoiding duplicates)
	// ---------------------------------------------
	if (THEMESSL) {$vprotocol = 'https';} else {$vprotocol = 'http';}
	$vqueried = array(); $vi = $vj = $vk = 1;
	foreach ($vfonts as $vfontkey => $vfont) {
		// 1.8.0: fix for Titan Framework options
		if (!isset($vfont['face'])) {$vfont['face'] = $vfont['font-family'];}
		$vstyle = ''; $vthisface = strtolower($vfont['face']);
		// echo $vfontkey.'---'.$vfont['face'].'---'.$vfont['style']; // debug point
		// 1.8.0: filter out possible font stack requests
		// (load via extra fonts option if using a title font stack)
		// 2.1.0: ignore where font face value is set to inherit
		if ( ($vthisface != '') && (!strstr($vthisface, ',')) && ($vthisface != 'inherit')
		  && (strtolower($vthisface) != 'sans-serif') && (strtolower($vthisface) != 'serif') ) {
			// 1.5.0: fix to replace all whitespace for custom font
			// $vfontface = preg_replace('/\s+/', '%20', $vfont['face']);
			// $vfontface = str_replace('+', '%20', $vfontface);
			// 1.8.0: actually better to use + instead of %20 !
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
			if (!in_array($vquery, $vqueried)) {
				if (in_array($vfontkey, $vheadingfonts)) {$vfontstackkey = 'heading-font-'.$vi; $vi++;}
				else {$vfontstackkey = 'custom-font-'.$vj; $vj++;}
				wp_enqueue_style($vfontstackkey, add_query_arg($vqueryargs, $vprotocol."://fonts.googleapis.com/css"), array(), null);
				$vk++; $vqueried[$vk] = $vquery;

				// 2.0.9: add preconnect for Google Font resources
				// ref: https://core.trac.wordpress.org/ticket/37171
				if (!isset($vaddedpreconnect)) {
					add_filter('wp_resource_hints', 'bioship_google_font_resource_hint', 10, 2);
					$vaddedpreconnect = true;
				}
			}
		}
	}
	// print_r($vqueried); // debug point
 }
}

// Font Resource Hints
// -------------------
// 2.0.9: added Google Font preconnect hint
// ref: https://core.trac.wordpress.org/ticket/37171
if (!function_exists('bioship_google_font_resource_hint')) {
 function bioship_google_font_resource_hint($urls, $relation_type) {
 	// adds <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
 	if ('preconnect' === $relation_type) {
		if (version_compare($GLOBALS['wp_version'], '4.7-alpha', '>=')) {
			$urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin');
		} else {$urls[] = 'https://fonts.gstatic.com';}
	}
	return $urls;
 }
}

// AJAX Skin CSS Loader
// --------------------
add_action('wp_ajax_bioship_skin_dynamic_css', 'bioship_skin_dynamic_css');
add_action('wp_ajax_nopriv_bioship_skin_dynamic_css', 'bioship_skin_dynamic_css');

// 2.0.7: added optional included argument for inline
if (!function_exists('bioship_skin_dynamic_css')) {
 function bioship_skin_dynamic_css($vincludedskin = false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemedirs;
	$vloadskin = bioship_file_hierarchy('file', 'skin.php', $vthemedirs['core']);
	if ($vloadskin) {require($vloadskin);}
 }
}
// 1.8.5: for printing inline header/footer styles
if (!function_exists('bioship_skin_dynamic_css_inline')) {
 function bioship_skin_dynamic_css_inline() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	echo "<style id='dynamic-styles'>"; bioship_skin_dynamic_css(true); echo "</style>";
 }
}

// AJAX Grid CSS Loader
// --------------------
// 1.5.0: added dynamic grid stylesheet
add_action('wp_ajax_grid_dynamic_css', 'bioship_grid_dynamic_css');
add_action('wp_ajax_nopriv_grid_dynamic_css', 'bioship_grid_dynamic_css');

// 1.8.5: added optional included argument
if (!function_exists('bioship_grid_dynamic_css')) {
 function bioship_grid_dynamic_css($vincluded = false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemedirs;
	$vloadgrid = bioship_file_hierarchy('file', 'grid.php', $vthemedirs['core']);
	require($vloadgrid);
 }
}
// 1.8.5: for printing inline header/footer styles
if (!function_exists('bioship_grid_dynamic_css_inline')) {
 function bioship_grid_dynamic_css_inline() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	echo "<style id='grid-styles'>"; bioship_grid_dynamic_css(); echo "</style>";
 }
}

// AJAX Admin CSS Loader
// ---------------------
add_action('wp_ajax_bioship_skin_dynamic_admin_css', 'bioship_skin_dynamic_admin_css');
add_action('wp_ajax_nopriv_bioship_skin_dynamic_admin_css', 'bioship_skin_dynamic_admin_css');

// 1.8.5: added optional login style argument
if (!function_exists('bioship_skin_dynamic_admin_css')) {
 function bioship_skin_dynamic_admin_css($vloginstyles = false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemedirs;
	$vadminstyles = true; // admin-only styles switch
	$vloadskin = bioship_file_hierarchy('file', 'skin.php', $vthemedirs['core']);
	if ($vloadskin) {include($vloadskin);}
 }
}
// 1.8.5: for printing inline admin-only header/footer styles
if (!function_exists('bioship_skin_dynamic_admin_css_inline')) {
 function bioship_skin_dynamic_admin_css_inline() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	echo "<style id='dynamic-admin-styles'>"; bioship_skin_dynamic_admin_css(); echo "</style>";
 }
}
// 1.8.5: for printing inline login-only styles
if (!function_exists('bioship_skin_dynamic_login_css_inline')) {
 function bioship_skin_dynamic_login_css_inline() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	echo "<style id='dynamic-login-styles'>"; bioship_skin_dynamic_admin_css(true); echo "</style>";
 }
}

// -------------------
// Fully shipped bruz.
