<?php

// =========================
// ======== BIOSHIP ========
// =========================
// ==== Theme Framework ====
// =========================

// BioShip HomePage: http://bioship.space
// Support and Features: http://wordquest.org/support/bioship/
// GitHub Repository: https://github.com/majick777/bioship/

// -------------------
// Full Documentation!
// -------------------
// To read detailed and complete Documentation for BioShip:
// a) popup via the Docs link on the BioShip Theme Options page
// b) read directly by loading /wp-content/themes/bioship/admin/docs.php
// c) available online at http://bioship.space/documentation/

// -------
// License
// -------
// BioShip Theme Framework, super flexible theme for WordPress
// Copyright (C) 2016-2019 Tony Hayes

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <https://www.gnu.org/licenses/>.

// ---------------
// Reference Links
// ---------------
// * See includes/links.txt for complete list of included resources.
// * See includes/licenses.txt for list of all resource licenses.

// ------------------
// Mini Theme History
// ------------------
// Latest Update News: http://bioship.space/news/
// Known Minimum Requirement: WordPress 3.4 (wp_get_theme)
// Original Development from: WordPress ~3.8
// Public Beta Version Available from: WordPress ~4.0
// Public Release Candidate Available: WordPress ~4.5
// Second Public Beta Available from: WordPress ~4.6
// Hotfixed Public Version: WordPress ~4.7
// First WordPress.Org Submission Version: WordPress ~4.8
// Second WordPress.Org Submission Version: WordPress ~4.9
// Third WordPress.Org Submission Version: ~5.0


// -------------------------------
// === functions.php Structure ===
// -------------------------------
// [optional] WordPress auto-loads Child Theme functions.php
// === Theme Setup ===
// - AutoSet Framework Version
// - Set WordQuest Theme 'Plugin' Info
// - Set Framework URLs
// - Set Global Theme Directories and URLs
// - Set Windows Path Constant
// - Set Global Resource Paths
// - Comprehensive File Hierarchy
// - Load Helper Functions (helpers.php)
// === Load Theme ===
// - Load Theme Backwards Compatiblity (compat.php)
// - Load Current Theme Data
// - Determine Theme for Test Drive Plugin
// - Theme Test Drive Compatibility
// - Load Theme Value Filters (filters.php)
// - Set Site Icon Constant
// - Filter Resource Directories
// - Set Global Theme Name and Parent/Child
// - Set Child Theme Version
// === Debugging ===
// - Set Theme Debug Mode Switch
// - Start Theme Debug Output/Log
// - Set Debug Directory
// - Load Theme Tracer (tracer.php)
// === Theme Settings ===
// - Convert Posted Customizer Preview Options
// - Get Theme Settings Filter - with Fallbacks!
// - Check/Fix Updated Theme Settings (PreSave)
// - Debug Updated Theme Settings
// - On/Off File Switch for Titan Framework
// - Check Options Framework to Load
// -- Titan Framework Load
// -- Options Framework Load
// - Set Framework Flag Constants
// - Map Titan Option values to Theme Settings
// - Load Theme Options and Settings
// - Load Theme Admin Functions (admin/admin.php)
// - Set HTML Comments Constant
// - Set Script Cachebusting
// - Set Stylesheet Cachebusting
// - Declare WooCommerce Support
// - Set Post Format Theme Supports
// - Output Theme Settings Debug Info
// === Customizer ===
// - Customizer Loader (admin/customizer.php)
// - Load Customizer Controls
// - Customizer Preview Window
// - Customizer Loading Image
// === Hybrid Core ===
// - Load Hybrid Core
// - Non Hybrid Fallback Loading
// - Fix to Main Element IDs
// === Skull, Skeleton, and Muscle ===
// - Require Head and Template Setup (skull.php)
// - Require Layout Hooks Definitions (hooks.php)
// - Require Skeleton Templating Functions (skeleton.php)
// - Include Muscle Extended Functions (muscle.php)
// - Grid and Skin Loading Functions (moved to skull.php)


// --- no direct load ---
if (!defined('ABSPATH')) {exit;}


// -------------------
// === Theme Setup ===
// -------------------

// ------------------------------------
// Define DIRECTORY_SEPARATOR Pseudonym
// ------------------------------------
if (!defined('DIRSEP')) {define('DIRSEP', DIRECTORY_SEPARATOR);}

// ---------------------
// AutoSet Framework Version
// ---------------------
// 2.0.1: get theme version direct from style.css
// 2.0.5: set theme prefix constant
$vthemestylecsspath = dirname(__FILE__).DIRSEP.'style.css';
$vthemeheaders = get_file_data($vthemestylecsspath, array('Version' => 'Version'));
define('THEMEVERSION', $vthemeheaders['Version']);
if (!defined('THEMEPREFIX')) {define('THEMEPREFIX', 'bioship');}

// ---------------------------------
// Set WordQuest Theme 'Plugin' Info
// ---------------------------------
// 2.1.0: use 3 letters for settings prefix
global $wordquestplugins; $slug = THEMEPREFIX;
$wordquestplugins[$slug]['version'] = THEMEVERSION;
$wordquestplugins[$slug]['title'] = __('BioShip Theme','bioship');
$wordquestplugins[$slug]['settings'] = substr(THEMEPREFIX, 0, 3);
$wordquestplugins[$slug]['plan'] = 'free';
$wordquestplugins[$slug]['hasplans'] = false;
// --- WordPress.Org values ---
// note: set to false here and rechecked with THEMEWPORG constant
$wordquestplugins[$slug]['wporg'] = false;
// TODO: add this definition if/when in WordPress.Org repo
// $wordquestplugins[$slug]['wporgslug'] = 'bioship';

// ------------------
// Set Framework URLs
// ------------------
// 2.0.0: added BioShip Home URL constant
// 2.0.1: change constant name for consistency
// 2.0.1: added Support Forum Site URL constant
// 2.0.1: set Theme SSL constant here also
define('THEMEHOMEURL', 'http://bioship.space');
define('THEMESUPPORT', 'http://wordquest.org');
if (!defined('THEMESSL')) {$vthemessl = is_ssl(); define('THEMESSL', $vthemessl);}

// -------------------------------------
// Set Global Theme Directories and URLs
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
	$vthemestyleurl = str_replace('http://', 'https://', $vthemestyleurl);
	$vthemetemplateurl = str_replace('http://', 'https://', $vthemetemplateurl);
}

// -------------------------
// Set Windows Path Constant
// -------------------------
// (may help paths on some local dev Windows IIS environments)
// 2.0.9: use operating system check for Windows paths
if (!defined('THEMEWINDOWS')) {
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {define('THEMEWINDOWS', true);}
	else {define('THEMEWINDOWS', false);}
}
if (THEMEWINDOWS) {
	$vthemetemplatedir = str_replace('/', '\\' ,$vthemetemplatedir);
	$vthemestyledir = str_replace('/', '\\', $vthemestyledir);
}

// -------------------------
// Set Global Resource Paths
// -------------------------
// 1.8.0: used for inbuilt file hierarchy calls
// 1.8.5: set defaults and filter later on (to allow for filters.php)
// 1.9.5: add scripts and styles to top of hierarchy
// 2.1.1: added includes theme directory paths
global $vthemedirs;
$vthemedirs['core'] = array();
$vthemedirs['admin'] = array('admin');
$vthemedirs['style'] = array('styles', 'css', 'assets/css');
$vthemedirs['script'] = array('scripts', 'javascripts', 'js', 'assets/js');
$vthemedirs['image'] = array('images', 'img', 'icons', 'assets/img');
$vthemedirs['includes'] = array('includes');

// ----------------------------
// Comprehensive File Hierarchy
// ----------------------------
// ...a magical fallback mystery tour...
// 1.8.0: use DIRECTORY_SEPARATOR in all paths
// 1.8.0: switch to directory array loop instead of 2 args
// 1.8.0: added optional search roots override argument
// (added for edge cases, ie. the search for /sidebar/page.php should *not* find /page.php)
// 2.0.9: added theme_file_search filter to return results?
// 2.1.4: added function_exists wrappers to bioship_debug calls
if (!function_exists('bioship_file_hierarchy')) {
 function bioship_file_hierarchy($returntype, $filename, $dirs = array(), $searchroots = array('stylesheet','template')) {

 	// 1.6.0: check if THEMETRACE constant is defined (in this function only!)
	if (defined('THEMETRACE') && THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemestyledir, $vthemestyleurl, $vthemetemplatedir, $vthemetemplateurl;

	// 1.8.5: just in case of bad argument fix ("I'm right", "No, I'm right!" ...)
	if (!is_array($searchroots)) {$searchroots = array('stylesheet', 'template');}

	// --- replacement for windows paths ---
	// 1.8.0: just use THEMEWINDOWS constant here instead of everywhere else...
	// 2.0.1: use numerical index when looping array
	$filedirs = $dirs;
	if (THEMEWINDOWS && (count($dirs) > 0)) {
		foreach ($dirs as $i => $dir) {$filedirs[$i] = str_replace('/', '\\', $dir);}
	}

	// --- debug hierarchy call and search directories ---
	if (defined('THEMEDEBUG')) {
		if (function_exists('bioship_debug')) {
			bioship_debug("File Hierarchy Call for ".$returntype, $filename);
			if (count($dirs) > 0) {bioship_debug("Search Directories", implode(',', $dirs));}
		}
	}

	// --- set value flags ---
	// 2.0.9: set default value flag
	// 2.1.0: set default found flag
	// 2.1.1: removed unnecessary double flag
	$value = false;

	// --- check stylesheet subdirectory(s) loop ---
	if (count($dirs) > 0) {
		foreach ($dirs as $i => $dir) {

			// 2.0.9: check for found value (stored for filtering)
			if (!$value) {

				$file = false;

				// 1.8.0: added absolute path override possibility (prototype)
				// (can be used via resource directory override filters)
				// note: to work full absolute path must start and end with #
				if (strstr($dir, '#')) {
					if ( (substr($dir, 0 ,1) == '#') && (substr($dir, -1) == '#') ) {
						$absdir = substr($dir, 1, -1);
						$file = $absdir.$filename;
						// TODO: check/improve this URL to path replacement ?
						$url = str_replace(ABSPATH, site_url(), $file);
					}
				} elseif ($dir == '') {
					// 1.8.0: allow for root file to take precedence if specified
					$file = $vthemestyledir.$filename;
					$url = $vthemestyleurl.$filename;
				} else {
					$file = $vthemestyledir.$filedirs[$i].DIRSEP.$filename;
					$url = trailingslashit($vthemestyleurl.$dir).$filename;
				}

				// 2.1.1: moved repeated logic out from if statements
				if ($file && file_exists($file)) {
					if ($returntype == 'file') {$value = $file;}
					elseif ($returntype == 'url') {$value = $url;}
					elseif ($returntype == 'both') {$value = array('file' => $file, 'url' => $url);}
				}
			}
		}
	}

	// --- fallback check of stylesheet base directory ---
	if (!$value && in_array('stylesheet', $searchroots)) {
		$file = $vthemestyledir.$filename;
		$url = $vthemestyleurl.$filename;
		if (file_exists($file)) {
			if ($returntype == 'file') {$value = $file;}
			elseif ($returntype == 'url') {$value = $url;}
			elseif ($returntype == 'both') {$value = array('file' => $file, 'url' => $url);}
		}
	}

	// --- check template subdirectory(s) loop ---
	// 1.8.0: bug out early if the template and stylesheet directory are the same (no child theme)
	// 2.1.1: combined with child theme check conditions to enable later filtering
	if (!$value && ($vthemestyledir != $vthemetemplatedir)) {
		if (count($dirs) > 0) {
			foreach ($dirs as $i => $dir) {

				// 2.0.9: check for found value (stored for filtering)
				if (!$value) {

					$file = false;

					// 1.8.0: allow for root file to take precedence if specified
					if ($dir == '') {
						$file = $vthemetemplatedir.$filename;
						$url = $vthemetemplateurl.$filename;
					} else {
						$file = $vthemetemplatedir.$filedirs[$i].DIRSEP.$filename;
						$url = trailingslashit($vthemetemplateurl.$dir).$filename;
					}

					// 2.1.1: moved repeated logic out of if statements
					if ($file && file_exists($file)) {
						if ($returntype == 'file') {$value = $file;}
						elseif ($returntype == 'url') {$value = $url;}
						elseif ($returntype == 'both') {$value = array('file' => $file, 'url' => $url);}
					}
				}
			}
		}
	}

	// --- check template base directory ---
	// 2.1.1: combine with child theme check condition to prevent duplicate searches
	if (!$value && ($vthemestyledir != $vthemetemplatedir) && in_array('template', $searchroots)) {
		$file = $vthemetemplatedir.$filename;
		$url = $vthemetemplateurl.$filename;
		if (file_exists($file)) {
			if ($returntype == 'file') {$value = $file;}
			elseif ($returntype == 'url') {$value = $url;}
			elseif ($returntype == 'both') {$value = array('file' => $file, 'url' => $url);}
		}
	}

	// --- filter the found file path value ---
	// 2.1.0: fix to incorrect filter variable name
	// 2.1.1: compressed extra filter values to args array
	// 2.1.4: added function_exists check for bioship_apply_filters
	if (function_exists('bioship_debug')) {bioship_debug($filename." Search File Return Value", $value);}
	$args = array('type' => $returntype, 'filename' => $filename, 'dirs' => $dirs, 'searchroots' => $searchroots);
	if (function_exists('bioship_apply_filters')) {$filtered = bioship_apply_filters('theme_file_search', $value, $args);}
	else {$filtered = apply_filters('bioship_theme_file_search', $value, $args);}
	if ($filtered != $value) {
		if (function_exists('bioship_debug')) {bioship_debug("Filtered Filepath Search Value", $filtered);}
		// 2.1.1: added file exists check for filtered value
		if (file_exists($filtered)) {$value = $filtered;}
		elseif (function_exists('bioship_debug')) {bioship_debug("Warning! Filtered Filepath Not Found");}
	}

	// --- return found file path ---
	return $value;
 }
}

// ---------------------
// Load Helper Functions
// ---------------------
// 2.1.4: moved helper functions to separate file
$helpers = bioship_file_hierarchy('file', 'helpers.php');
require($helpers);


// ------------------
// === Load Theme ===
// ------------------

// ---------------------------------
// Load Theme Backwards Compatiblity
// ---------------------------------
// used (sparsely) for any function/filter name changes
// 1.8.5: load theme backwards compatibility file
// 2.0.5: add ability to turn compat.php loading off with a file (as pre-filters)
// 2.1.1: changed file switch name from compat-off.php to compat.off
$compat = bioship_file_hierarchy('file', 'compat.php');
$compatoff = bioship_file_hierarchy('file', 'compat.off');
if ($compat && !$compatoff) {include_once($compat);}

// ----------------------
// Get Current Theme Data
// ----------------------
// note: pre-3.4 compatibility function loaded in compat.php
$vtheme = wp_get_theme();

// -------------------------------------
// Determine Theme for Test Drive Plugin
// -------------------------------------
// (as Theme Test Drive plugin functions not loaded yet)
if (!function_exists('bioship_themedrive_determine_theme')) {
 function bioship_themedrive_determine_theme() {

	// --- get internal test drive value if any ---
	if (!isset($_REQUEST['theme'])) {

		// --- get permission level ---
		$tdlevel = get_option('td_level');
		if ($tdlevel != '') {$permissions = 'level_'.$tdlevel;}
		else {$permissions = 'level_10';}

		// --- check permissions ---
		if (!current_user_can($permissions)) {return false;}
		else {
			$tdtheme = get_option('td_themes');
			// 2.1.1: added check for false value (unset option)
			if (!$tdtheme) {return;}
			$tdtheme = trim($tdtheme);
			if (empty($tdtheme) || ($tdtheme == '')) {return false;}
		}
	} else {$tdtheme = $_REQUEST['theme'];}

	// --- get test drive theme data
	$themedata = wp_get_theme($tdtheme);

	// -- loop all themes to match ---
	// 2.1.1: simplified theme data check logic
	if (empty($themedata)) {
		$allthemes = wp_get_themes();
		foreach ($allthemes as $themedata) {
			if ($themedata['Stylesheet'] == $tdtheme) {break;}
		}
	}

	// --- check for theme data ---
	// 2.1.1: simplified theme data check logic
	if (!empty($themedata)) {
		// note: we are skipping the 'publish' check here - as may be a theme under development
		// if (isset($themedata['Status']) && ($themedata['Status'] != 'publish')) {return false;}
		return $themedata;
	}

	return false;
 }
}

// ------------------------------
// Theme Test Drive Compatibility
// ------------------------------
// 1.5.0: added check that this is not a theme editor page
// (as theme editor can set a theme key in the querystring
// and this conflicts with a theme test drive parameter)
// TODO: recheck this behaviour on the Customizer page?
global $pagenow;
if ( ($pagenow != 'theme-editor.php') && ($pagenow != 'customize.php') ) {

	$themetestdrive = bioship_themedrive_determine_theme();

	if ($themetestdrive) {

		// --- set theme drive constant ---
		define('THEMEDRIVE', true);
		$vtheme = $themetestdrive;

		// --- set theme directories ---
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

		// --- load Admin Menu Fixes for Theme Test Driving ---
		// 2.1.1: added missing function prefix
		add_action('admin_menu', 'bioship_admin_themetestdrive_options', 12);

		// --- add theme filter for Options Framework ---
		// 2.0.1: added missing function prefix
		// 2.1.1: adding missing function exists wrapper
		// TODO: retest with Options Framework + Customizer combination
		// TODO: move to Options Framework integration section ?
		if (!function_exists('bioship_optionsframework_themetestdrive')) {

		 add_filter('of_theme_value', 'bioship_optionsframework_themetestdrive');

		 function bioship_optionsframework_themetestdrive($theme) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE,func_get_args());}

			$testdrive = bioship_themedrive_determine_theme();
			$thetheme['id'] = preg_replace("/\W/", "_", strtolower($testdrive['Name']));
			return $theme;
		 }
		}
	}
}

// ------------------------
// Load Theme Value Filters
// ------------------------
// check for possible customized filters.php in parent/child theme
// note: *loaded early* as filters are used immediately if present
// 2.0.9: run core directory filter in case override already exists
$vthemedirs['core'] = bioship_apply_filters('theme_core_dirs', $vthemedirs['core']);
$filters = bioship_file_hierarchy('file', 'filters.php', $vthemedirs['core']);
if ($filters) {include_once($filters);}

// ----------------------
// Set Site Icon Constant
// ----------------------
// 2.0.8: added this to check for global site icon once only
// 2.0.9: make sure function exists and simplify logic (for backwards compatibility)
// 2.1.1: moved down so that any value filters can be loaded and applied
if (!defined('THEMESITEICON')) {
	if (function_exists('has_site_icon') && has_site_icon()) {$siteicon = true;} else {$siteicon = false;}
	$siteicon = bioship_apply_filters('skeleton_site_icon', $siteicon);
	if ($siteicon) {define('THEMESITEICON', $siteicon);} else {define('THEMESITEICON', false);}
}

// ---------------------------
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

// --------------------------------------
// Set Global Theme Name and Parent/Child
// --------------------------------------
// 2.0.5: define THEMESLUG (using dashes not underscores)
// 2.0.5: cleaner code logic for child and parent theme constants
$vthemedisplayname = (string)$vtheme['Name'];
define('THEMEDISPLAYNAME', $vthemedisplayname);
$vthemename = preg_replace("/\W/", "-", strtolower($vthemedisplayname));
define('THEMESLUG', $vthemename);
if (!is_child_theme()) {define('THEMECHILD', false); define('THEMEPARENT', false);}
else {define('THEMECHILD', true); define('THEMEPARENT', (string)$vtheme['Template']);}

// --- Set Child Theme Version ---
// 2.0.1: simplify to set child theme version constant
// 2.0.5: cleaner code logic here
if (!THEMECHILD) {define('THEMECHILDVERSION', THEMEVERSION);}
else {define('THEMECHILDVERSION', $vtheme['Version']);}


// -----------------
// === Debugging ===
// -----------------

// ---------------------------
// Set Theme Debug Mode Switch
// ---------------------------
// 1.8.0: changed this to a constant, allow for switching
// 1.8.5: added all word values and new '3' option
// TODO: maybe move to tracer.php ?

// ?themedebug=0 or ?themedebug=off 	- switch theme debug mode off
// ?themedebug=1 or ?themedebug=on 		- switch theme debug mode on (persistant)
// ?themedebug=2 or ?themedebug=yes		- debug mode on for this pageload (overrides)
// ?themedebug=3 or ?themedebug=no 		- debug mode off for this pageload (overrides)

if (!defined('THEMEDEBUG')) {

	$vthemekey = preg_replace("/\W/", "_", strtolower($vthemedisplayname));
	$themedebug = get_option($vthemekey.'_theme_debug');

	if ($themedebug == '1') {$themedebug = true;} else {$themedebug = false;}
	if (isset($_REQUEST['themedebug'])) {

		$debug = $_REQUEST['themedebug'];
		// 1.8.5: authenticate debug capability
		// 2.0.9: add filter for debug switching capability
		$debugcap = bioship_apply_filters('theme_debug_capability', 'edit_theme_options');

		if (current_user_can($debugcap)) {
			if ( ($debug == '2') || ($debug == 'yes') ) {
				// --- debug on for this pageload only
				$themedebug = true;
			}  elseif ( ($debug == '3') || ($debug == 'no') ) {
				// --- debug off for this pageload only ---
				$themedebug = false;
			} elseif ( ($debug == '1') || ($debug == 'on') ) {
				// --- switch theme debug on  ---
				$themedebug = true; delete_option($vthemekey.'_theme_debug');
				add_option($vthemekey.'_theme_debug', '1');
			} elseif ( ($debug == '0') || ($debug == 'off') ) {
				// --- switch theme debug off ---
				$themedebug = false; delete_option($vthemekey.'_theme_debug');
			}
		}
	}
	if ( ($themedebug == '') || ($themedebug == '0') ) {$themedebug = false;}

	// --- filter debug mode ---
	$themedebug = bioship_apply_filters('theme_debug', $themedebug);

	// --- finally define debug constant ---
	define('THEMEDEBUG', $themedebug);
}

// -------------------
// Set Debug Directory
// -------------------
// 1.9.8: set debug directory global value
// 2.1.1: use existing function for this purpose
// if (THEMECHILD) {global $vthemestyledir; $vthemedebugdir = $vthemestyledir.'debug';}
// else {global $vthemetemplatedir; $vthemedebugdir = $vthemetemplatedir.'debug';}
// $vthemedebugdir = bioship_apply_filters('theme_debug_dirpath', $vthemedebugdir);
// if (!is_dir($vthemedebugdir)) {wp_mkdir_p($vthemedebugdir);}
$vthemedebugdir = bioship_check_create_debug_dir();

// -----------------
// Load Theme Tracer
// -----------------
// 1.8.0: moved here as was loaded too early to work, refined logic
if (!defined('THEMETRACE')) {

	// --- check for tracer loading ---
	// 1.8.5: added querystring option for high capability
	$themetracer = false;
	if (isset($_REQUEST['themetrace'])) {
		$themetracer = $_REQUEST['themetrace'];
		if (current_user_can('manage_options')) {
			// 2.0.5: make any other value trigger a trace
			if ($themetracer != '0') {$themetracer = true;}
		}
	}

	// --- filter tracer loading ---
	// 1.9.8: fix to filtered value here
	$themetracer = bioship_apply_filters('theme_tracer', $themetracer);

}

// --- include theme tracer (tracer.php) ---
// 1.9.8: change fixed directory to admin dir global
$tracer = bioship_file_hierarchy('file', 'tracer.php', $vthemedirs['admin']);
// 2.1.1: moved THEMETRACE definition here from tracer.php (must be below above line!)
if (!defined('THEMETRACE')) {
	if ($themetracer) {define('THEMETRACE', true);} else {define('THEMETRACE', false);}
}
if ($tracer) {include($tracer);}

// --- maybe define dummy function ---
// (dummy function to avoid potential fatal errors in edge cases)
if (THEMETRACE && !function_exists('bioship_trace')) {
	function bioship_trace($arg1=null, $arg2=null, $arg3=null, $arg4=null) {return;}
}


// ----------------------
// === Theme Settings ===
// ----------------------

// -----------------------------------------
// Convert Posted Customizer Preview Options
// -----------------------------------------
// 1.8.5: moved here to be available for both options frameworks
if (!function_exists('bioship_customizer_convert_posted')) {
 function bioship_customizer_convert_posted($postedvalues, $optionvalues) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemeoptions;

	foreach ($vthemeoptions as $optionkey => $optionvalue) {

		// 2.1.1: fix to skip non-options (headings etc)
		if (isset($optionvalue['id'])) {

			// --- set variable for ID and type ---
			$id = $optionvalue['id'];
			$optiontype = $optionvalue['type'];
			$previewkey = str_replace('_options', '_customize', THEMEKEY.'['.$optionvalue['id'].']');

			if (array_key_exists($previewkey, $postedvalues)) {

				// --- apply sanitization filters to preview values ---
				// 2.1.1.: removed unneeded inline {} wrappers
				// note: the third argument above could be a Customizer setting object ?
				$previewvalue = bioship_apply_filters('customize_sanitize_'.$previewkey, $postedvalues[$previewkey], array());

				// !! WARNING: echoing debug output in Customizer prevents saving  !!
				// (bioship_debug lines are commented out below just in case)

				// --- set option value ---
				if ($optionvalue['type'] == 'checkbox') {
					// 1.8.5: fix for empty checkbox values
					if ($previewvalue == '1') {$optionvalues[$id] = '1';}
					else {$optionvalues[$id] = '0';}
				} else {
					if (!is_array($previewvalue)) {
						// --- set string value ---
						$optionvalues[$id] = $previewvalue;
						// bioship_debug("Preview Value for '".$id."'", $previewvalue, false);
					} else {
						// TODO: maybe do something else for subarray values ?
						// bioship_debug("Option Type", $optiontype, false);

						// --- Customizer Multicheck values ---
						// 1.8.5: fix for customizer multicheck arrays
						if ($optiontype == 'multicheck') {
							foreach ($optionvalue['options'] as $key => $value) {
								if (in_array($key, $previewvalue)) {$valuearray[$key] = '1';}
								else {$valuearray[$key] = '0';}
							}
							$optionvalues[$id] = $valuearray;
						} else {
							// --- set array value ---
							$optionvalues[$id] = $previewvalue;
						}

						// bioship_debug("Preview Value for '".$id."'", $optionvalues[$id], false);
					}
				}
			}
		}
	}
	return $optionvalues;
 }
}

// -------------------------------------------
// Get Theme Settings Filter - with Fallbacks!
// -------------------------------------------
// ...this may seem completely arbitrary and unnecessary but it works...
// 1.9.5: get_option filter to help bypass crazy empty settings / saving bug
// 2.0.9: added theme_settings filter to return value
if (!function_exists('bioship_get_theme_settings')) {
 function bioship_get_theme_settings($value, $optionkey = false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemesettingsfix, $vthemedebugdir;

  	// --- not while updating settings ---
  	// 2.1.1: moved this check to update - not get option

	// --- check/create debug directory ---
	$vthemedebugdir = bioship_check_create_debug_dir();

	// --- this is to bypass possible cached value ---
	$settings = bioship_get_option(THEMEKEY);

	// ---- set to theme key if needed ---
	// 1.9.7: fix to missing argument 2 filter warning
	if (!$optionkey) {$optionkey = THEMEKEY;}

	// --- set found settings flag ---
	// 2.1.1: added this for better conditional logic
	$foundsettings = false;

	// --- unserialize settings check ---
	if ($settings) {
		if (is_serialized($settings)) {
			bioship_debug("Serialized Theme Settings Found");

			$settings = (string)$settings;
			$unserialized = unserialize($settings);
			if ($unserialized) {

				// 2.1.1: set as found instead of returning
				bioship_debug("Theme Settings Unserialized Successfully");
				$settings = $unserialized; $foundsettings = true;

			} else {

				// and here is the problem (read "ghost insane WTF bug")... finally! yeesh.
				// ?!sometimes!? the data JUST DOES NOT UNSERIALIZE - FOR NO CLEAR REASON.
				// ...it just returns as false even though the serialized data is there
				// and does not appear to be at all corrupt - and works in other contexts!

				// --- attempt to fix serialized settings ---
				// (but sometimes works sometimes not :-/ )
				// $repaired = bioship_fix_serialized($settings);
				// $fixedsettings = unserialize($repaired);
				// if ($fixedsettings) {return $fixedsettings;}

				// --- add theme debug warning ---
				if (THEMEDEBUG) {
					bioship_debug("WARNING: Unserialization Failed for Option Key", $optionkey);
					bioship_debug("Settings", $settings);
					bioship_debug("Maybe Unserialized", maybe_unserialize($settings));
					bioship_debug("Unserialized", $unserialized);
				}

				// ---- for now, add a filter so users can apply a custom manual fix ---
				// TODO: somehow add an admin warning for when this happens ?
				$customsettings = bioship_apply_filters('theme_settings_fallback', $settings);
				if (is_serialized($customsettings)) {
					$unserialized = @unserialize($customsettings);
					if ($unserialized) {
						bioship_debug("Unserialized Settings from Custom Override Used");
						$settings = $unserialized; $foundsettings = true;
					}
				}

			}
		}
	}

	// --- maybe apply force update settings ---
	if (!$foundsettings) {
		$forcesettings = get_transient('force_update_'.$optionkey);
		if ($forcesettings) {
			bioship_debug("Checking Force Update Settings");
			if (is_serialized($forcesettings)) {

				// --- unserialize force updated settings ---
				$unserialized = @unserialize($forcesettings);
				if (!$unserialized) {
					// --- attempt fix of serialized settings ---
					$repaired = bioship_fix_serialized($forcesettings);
					$fixedsettings = @unserialize($repaired);
					if ($fixedsettings) {
						bioship_debug("Fixed Force Update Settings");
						$unserialized = $fixedsettings;
					}
				}

				if ($unserialized) {

					// --- write to file backup ---
					// 2.1.1: use update_option instead of delete and add
					// 2.1.1: remove filter to prevent infinite loop
					bioship_write_debug_file($optionkey.'.txt', $forcesettings);
					remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);
					update_option($optionkey, $unserialized);
					add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

					// --- add notice for force update settings ---
					bioship_debug("Force Update Settings Used and Restored");
					// 2.0.0: change to skeleton prefix and add translation wrappers
					if (!function_exists('bioship_forced_settings_restored')) {

					 // 2.1.1: move add_action internally for consistency
					 add_action('theme_admin_notices', 'bioship_forced_settings_restored');

					 function bioship_forced_settings_restored() {
						echo "<div class='message'><b>".esc_attr(__('Warning','bioship')).":</b> ";
						echo esc_attr(__('Theme Settings from Force Update Used and Restored!','bioship'))."</div>";
					 }
					}

					$settings = $unserialized; $foundsettings = true;

				} else {
					bioship_debug("Force Update Settings could not be Unserialized");
				}
			}
		}
	}

	// --- maybe fallback to saved options file ---
	if (!$foundsettings) {

		// TODO: maybe use file hierarchy for debug directory ?
		$savedfile = get_stylesheet_directory().'/debug/'.$optionkey.'.txt';
		// bioship_debug("Checking for Backup Settings File", $savedfile);

		if (file_exists($savedfile)) {
			bioship_debug("Checking Found File Settings");
			$saveddata = bioship_file_get_contents($savedfile);
			if ((strlen($saveddata) > 0) && is_serialized($saveddata)) {
				$unserialized = @unserialize($saveddata);
				if (!$unserialized) {
					$repaired = bioship_fix_serialized($saveddata);
					$fixedsettings = @unserialize($repaired);
					if ($fixedsettings) {
						bioship_debug("Fixed Serialized File Settings");
						$unserialized = $fixedsettings;
						$foundsettings = true;
					}
				}

				if ($unserialized) {

					// --- update theme settings key ---
					// 2.1.1: use update_option instead of delete and add
					// 2.1.1: remove option filter to prevent infinite loop
					remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);
					update_option($optionkey, $unserialized);
					add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

					bioship_debug("File Theme Settings Used and Restored");
					// 2.0.0: change to skeleton prefix and add translation wrappers
					add_action('theme_admin_notices','skeleton_file_settings_restored');
					if (!function_exists('bioship_file_settings_restored')) {
					 function bioship_file_settings_restored() {
						echo "<div class='message'><b>".esc_attr(__('Warning','bioship')).":</b> ";
						echo esc_attr(__('Theme Settings from File Settings Used and Restored!','bioship'))."</div>";
					 }
					}

					$settings = $unserialized; $foundsettings = true;

				} else {
					bioship_debug("File Backup Settings could not be Unserialized");
				}
			}
		}
	}

	// --- maybe fallback to backup options ---
	if (!$foundsettings) {
		$backupkey = $optionkey.'_backup';
		$backupsettings = bioship_get_option($backupkey);
		if ($backupsettings) {
			if (is_serialized($backupsettings)) {
				$unserialized = unserialize($backupsettings);
				if (!$unserialized) {
					$repaired = bioship_fix_serialized($backupsettings);
					$fixedsettings = unserialize($repaired);
					if ($fixedsettings) {
						bioship_debug("Fixed AutoBackup Settings");
						$unserialized = $fixedsettings;
					}
				}

				if ($unserialized) {
					bioship_debug("AutoBackup Settings Used");
					if (!$settings) {

						// --- set updating flag while updating settings option ---
						// 2.1.1: use update_option instead of delete and add
						// 2.1.1: remove option filter to prevent infinite loop
						remove_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);
						update_option($optionkey, $unserialized);
						add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);
						bioship_debug("AutoBackup Theme Settings Restored");

						// --- add backup restored message ---
						// 2.0.0: change to skeleton prefix and add translation wrappers
						if (!function_exists('bioship_backup_settings_restored')) {

						 // 2.1.1: moved add_action internally for consistency
						 add_action('theme_admin_notices','skeleton_backup_settings_restored');

						 function bioship_backup_settings_restored() {
							echo "<div class='message'><b>".esc_attr(__('Error','bioship')).":</b> ";
							echo esc_attr(__('Theme Settings Empty! Existing Settings AutoBackup Restored.','bioship'))."</div>";
						 }
						}
					}
					$settings = $unserialized;
				}
			}
		}
	}

	bioship_debug("Unserialized Theme Settings Used");
	return bioship_apply_filters('theme_settings', $settings);
 }
}

// ------------------------------------------
// Check/Fix Updated Theme Settings (PreSave)
// ------------------------------------------
// 2.1.0: use pre-filtering of option value instead of do_action hook
if (!function_exists('bioship_admin_theme_settings_save')) {

 // 2.1.1: moved add_filter internally for consistency
 add_filter('pre_update_option', 'bioship_admin_theme_settings_save', 11, 3);

 function bioship_admin_theme_settings_save($newsettings, $option, $oldsettings) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	global $vthemedebugdir, $vthemesettingsfix, $vthemename;

	// --- check settings update conditions ---
	if ($option != THEMEKEY) {return $newsettings;}
	if ($vthemesettingsfix) {return $newsettings;}
	if (defined('THEMEUPDATED') && THEMEUPDATED) {return $newsettings;}

	// --- store the setings value ---
	// 2.0.9: store the serialized settings value
	// 2.1.0: changed to use the unserialized settings value due to filter
	$settings = maybe_unserialize($newsettings);

	// --- get posted multicheck settings ---
	// 2.0.9: for auto-fixing of multicheck theme option saving
	// 2.1.1: fix undefined variable warning for postedmulticheck
	// 2.1.1: fix undefined variable warning for oldmulticheck
	$multicheckkeys = $postedmulticheck = $oldmulticheck = array();
	$multicheckoptions = get_option($vthemename.'_multicheck_options');
	$multicheckkeys = array_keys($multicheckoptions);
	foreach ($multicheckkeys as $key) {
		if (isset($_POST[$vthemename.'_'.$key])) {$postedmulticheck[$key] = $_POST[$vthemename.'_'.$key];}
	}

	// --- unserialize old and new settings ---
	$oldsettings = maybe_unserialize($oldsettings);
	$newsettings = maybe_unserialize($newsettings);
	$newsettingskeys = array_keys($newsettings);

	// --- get new multicheck settings ---
	foreach ($newsettings as $key => $value) {
		if (in_array($key, $multicheckkeys)) {$newmulticheck[$key] = maybe_unserialize($value);}
	}

	// --- maybe get old multicheck settings ---
	// 2.1.0: can only use old settings as fallbacks if they unserialized correctly
	if (is_array($oldsettings)) {
		$oldsettingskeys = array_keys($oldsettings);
		foreach ($oldsettings as $key => $value) {
			if (in_array($key, $multicheckkeys)) {$oldmulticheck[$key] = maybe_unserialize($value);}
		}
	}

	// --- fix if new multicheck settings are not set ---
	// 2.1.0: finally, use posted or old multicheck settings if the new ones are empty!
	foreach ($newmulticheck as $key => $values) {
		if (!isset($settings[$key]) || empty($settings[$key])) {
			if (isset($_POST[$vthemename.'_'.$key]) && !empty($_POST[$vthemename.'_'.$key])) {
				// 2.1.1: fix to variable typo for key
				$settings[$key] = $_POST[$vthemename.'_'.$key];
			} elseif (isset($oldmulticheck[$key]) && !empty($oldmulticheck[$key])) {
				$settings[$key] = $oldmulticheck[$key];
			}
		}
	}

	// --- get settings save debug data ---
	// 2.1.0: for debugging multicheck option saving (THE super annoying bug)
	$vthemedebugdir = bioship_check_create_debug_dir();
	$debugdata = '<!-- '.$option.' ['.date('y/m/d H:i:s', time()).'] -->'.PHP_EOL;
	// $debugdata .= '<!-- GET Values -->'.print_r($_GET, true).PHP_EOL;
	// $debugdata .= '<!-- POST Values -->'.print_r($_POST, true).PHP_EOL;
	// $debugdata .= '<!-- Multicheck Options -->'.print_r($multicheckoptions, true).PHP_EOL;
	$debugdata .= '<!-- Multicheck Keys -->'.print_r($multicheckkeys, true).PHP_EOL;
	$debugdata .= '<!-- Posted Multicheck Options -->'.print_r($postedmulticheck,true).PHP_EOL;
	// $debugdata .= '<!-- Old Settings -->'.print_r($oldsettings, true).PHP_EOL;
	// $debugdata .= '<!-- New Settings -->'.print_r($newsettings, true).PHP_EOL;
	// $debugdata .= '<!-- Old Settings Keys -->'.print_r($oldsettingskeys, true).PHP_EOL;
	// $debugdata .= '<!-- New Settings Keys -->'.print_r($newsettingskeys, true).PHP_EOL;
	$debugdata .= '<!-- Old Multicheck Settings -->'.print_r($oldmulticheck, true).PHP_EOL;
	$debugdata .= '<!-- New Multicheck Settings -->'.print_r($newmulticheck, true).PHP_EOL;
	$debugdata .= '<!-- Fixed Updated Settings -->'.print_r($settings, true).PHP_EOL;

	// --- maybe write debug data ---
	if (THEMEDEBUG) {
		$debugfile = $vthemedebugdir.'/multicheck-options.txt';
		$debugdata = str_replace('<!-- ', '', $debugdata);
		$debugdata = str_replace(' -->', ': ', $debugdata);
		// 2.1.0: use plain error_log function here
		// 2.1.1: reverted to use internal write function
		bioship_write_to_file($debugfile, $debugdata, true);
	}

	// --- write settings to file backup ---
	// 2.1.0: reserialize the settings to ensure update
	if ($settings && !empty($settings) && is_array($settings)) {
		$serialized = serialize($settings);
		// write a manual settings file backup of the serialized data
		// 1.8.0: use standalone debug file write function
		// 2.1.1: set new append argument to false here
		bioship_write_debug_file($option.'.txt', $serialized, false);

		// --- set new settings transient to ensure saving ---
		set_transient('force_update_'.THEMEKEY, $settings, 60);
		if (!defined('THEMEUPDATED')) {define('THEMEUPDATED', true);}
		return $settings;
	}
	return $newsettings;
 }
}

// ----------------------------
// Debug Updated Theme Settings
// ----------------------------
// 2.1.1: added missing function exists wrapper
if (!function_exists('bioship_check_updated_option')) {
 add_action('updated_option', 'bioship_check_updated_option', 10, 3);
 function bioship_check_updated_option($option, $oldvalue, $value) {
	if ($option == THEMEKEY) {
		if (THEMEDEBUG) {bioship_debug("Updated Value for ".THEMEKEY, $value);}
		$settings = get_option(THEMEKEY);
		if (THEMEDEBUG) {bioship_debug("Saved Value for ".THEMEKEY, $settings);}
	}
 }
}

// --------------------------------------
// On/Off File Switch for Titan Framework
// --------------------------------------
// 1.8.5: convert previous version file switches
// Usage Note: creating a titanswitch.off file will revert to Options Framework usage,
// and creating a titanswitch.on file will remove Options Framework usage...
if (THEMECHILD) {$titanoff = $titanon = $vthemestyledir;}
else {$titanoff = $titanon = $vthemetemplatedir;}
$titanoff .= 'titanswitch.off'; $titanon .= 'titanswitch.on';
if (file_exists($titanoff)) {add_option($vthemename.'_framework', 'options'); unlink($titanoff);}
elseif (file_exists($titanon)) {delete_option($vthemename.'_framework'); unlink($titanon);}

// ---------------------------
// Check for Framework to Load
// ---------------------------
// note: if Titan is present it is loaded, otherwise things should still run okay...
// 1.8.5: new method to check switch for Options Framework usage
$vthemeframework = get_option($vthemename.'_framework');
if ($vthemeframework == 'options') {$optionsload = true; $titanload = false;}
else {$optionsload = false; $titanload = true;}

// --- default load, loads Titan if present ---
if (!$optionsload) {

	// maybe Load Titan Framework
	// --------------------------
	// note: hyphenated theme names eg. my-child-theme

	// --- set theme settings key ---
	if (!defined('THEMEKEY')) {define('THEMEKEY', $vthemename.'_options');}

	// --- check for Titan Framework plugin ---
	// 1.8.0: do a check as Titan may already active as a plugin
	// note: the TitanFramework class itself is loaded after_theme_setup (so not available yet)
	// 1.8.5: maybe use the already loaded Titan Framework plugin
	if (class_exists('TitanFrameworkPlugin')) {define('THEMETITAN', true);}
	else {

		// --- set Titan directories ---
		// 2.1.1: allow for alternative includes directory
		$titandirs = array('titan');
		if (count($vthemedirs['includes']) > 0) {
			foreach ($vthemedirs['includes'] as $dir) {$titandirs[] = $dir.DIRSEP.'titan';}
		}

		// --- Titan Framework embedder ---
		$titan = false;
		if ($titanload) {

			// --- locate Titan Framework embedder ---
			$titan = bioship_file_hierarchy('file', 'titan-framework-embedder.php', $titandirs);

			// --- load Titan Framework embedder ---
			if ($titan) {require_once($titan); define('THEMETITAN', true);}
		}

		if (!$titanload || !$titan) {

			// --- load for Titan Framwork checker ---
			// note: lack of Titan Framework embedder indicates it was not found
			// - probably this indicates it is a WordPress.Org version of the theme
			// 2.0.9: changed Titan checker setting to required=false for wordpress.org version
			// 2.0.9: moved Theme Tools submenu loading to admin.php
			// 2.1.1: allow for alternative includes directory
			// 2.1.2: fix to incorrect directory variable (dirs)
			$titandirs = array_merge($vthemedirs['includes'], $titandirs);
			$titanchecker = bioship_file_hierarchy('file', 'titan-framework-checker.php', $titandirs);
			if ($titanchecker) {require_once($titanchecker);}
			else {bioship_debug("Warning! Titan Framework Checker not found.");}
		}
	}

} else {

	// maybe Load Options Framework
	// ----------------------------

	// --- set theme settings key ---
	// note: underscored theme names eg. my_child_theme
	$vthemename = str_replace('-', '_', $vthemename);
	if (!defined('THEMEKEY')) {define('THEMEKEY', $vthemename);}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

	// --- set Options Framework directories ---
	// 1.8.0: use file hierarchy search here
	// 2.1.1: allow for an alternative includes directory
	$optionsdirs = array('options');
	if (count($vthemedirs['includes']) > 0) {
		foreach ($vthemedirs['includes'] as $dir) {$optionsdirs[] = $dir.DIRSEP.'options';}
	}

	// --- find and load Options Framework ---
	$optionsframework = bioship_file_hierarchy('file', 'options-framework.php', $optionsdirs);
	if ($optionsframework) {
		$optionspath = dirname($optionsframework).DIRSEP;
		define('OPTIONS_FRAMEWORK_DIRECTORY', $optionspath);
		require_once($optionsframework);
		// 1.8.5: define constant for Options Framework
		define('THEMEOPT', true);
	} else {define('THEMEOPT', false);}

	// --- load Theme Options array ---
	// 1.8.5: fix - load the theme options array here!
	$optionsframework = bioship_file_hierarchy('file', 'options.php');
	if ($optionsframework) {include($optionsframework);}
	else {wp_die(esc_attr(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship')));}

	// --- get all Theme Options ---
	global $vthemeoptions;
	$vthemeoptions = bioship_options();

	// --- get Options Framework theme settings ---
	$vthemesettings = get_option(THEMEKEY);

	// --- Auto Backup of Theme Settings
	// 1.9.5: moved here for better checking
	if ($vthemesettings && !empty($vthemesettings) && ($vthemesettings != '') && is_array($vthemesettings)) {
		$backupkey = THEMEKEY.'_backup'; update_option($backupkey, $vthemesettings);
	}

	// Customizer Live Preview Values
	// ------------------------------
	// TODO: retest with Options Framework + Customizer combo!
	global $pagenow;
	if (is_customize_preview() && ($pagenow != 'customizer.php')) {
		// !!! WARNING: DEBUG OUTPUT IN CUSTOMIZER CAN PREVENT SAVING !!!
		if (isset($_POST['customized'])) {$postedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($postedvalues)) {
			// TODO: check theme options override with preview options ?
			// 1.9.5: fix to ridiculous typo bug here "(", not ".)"
			$vthemesettings = bioship_customizer_convert_posted($postedvalues, $vthemesettings);
		}
	}

	// TODO: test/fix for empty checkbox/multicheck values ?
	// note: seems to be working fine now though

}

// ----------------------------
// Set Framework Flag Constants
// ----------------------------
// 1.8.5: set framework constants to false if not loaded
if (!defined('THEMETITAN')) {define('THEMETITAN', false);}
if (!defined('THEMEOPT')) {define('THEMEOPT', false);}
if (!THEMETITAN) {bioship_debug("Titan Framework is OFF");}
if (!THEMEOPT) {bioship_debug("Options Framework is OFF");}

// -----------------------------------------
// Map Titan Option values to Theme Settings
// -----------------------------------------
// 1.8.0: move because of function_exists wrapper this needs to be *here* not later
// 1.8.5: use optionsarray global and made single argument only
if (!function_exists('bioship_titan_theme_options')) {
 function bioship_titan_theme_options($optionvalues) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemeoptions, $vthemename;

	// --- loop the options array and convert to Theme Settings ---
	$checkboxes = $multicheck = array();
	foreach ($vthemeoptions as $option => $optionvalue) {

		// --- check type and skip headings/info ---
		if ( ($optionvalue['type'] != 'heading') && ($optionvalue['type'] != 'info') ) {

			// --- use option ID for key ---
			if (!isset($optionvalue['id'])) {
				bioship_debug("Option definition error found!", $optionvalue);
			} else {$optionkey = $optionvalue['id'];}

			// --- set to default for any unset options ---
			if (!isset($optionvalues[$optionkey])) {
				// 1.9.8: fix to mapping of default options for empty settings
				if (isset($optionvalue['std'])) {$vthemesettings[$optionkey] = $optionvalue['std'];}
			}

			// --- fix for serialized suboption values ---
			if (isset($optionvalues[$optionkey])) {
				$vthemesettings[$optionkey] = maybe_unserialize($optionvalues[$optionkey]);
			}

			// --- fix for empty checkbox values ---
			// 1.8.5: added this checkbox value fix
			if ($optionvalue['type'] == 'checkbox') {
				// 1.9.5: fix to undefined index warning (typically for new settings)
				if (isset($optionvalues[$optionkey]) && ($optionvalues[$optionkey] != '1')) {
					$vthemesettings[$optionkey] = '0';
				}
			}

			// --- fix for multicheck array options ---
			// 1.8.5: redo fix for multicheck options
			if ($optionvalue['type'] == 'multicheck') {

				// --- check option key is set ---
				// 1.9.5: fix to undefined index (typically for new settings)
				if (isset($optionvalues[$optionkey])) {
					$optionvalues[$optionkey] = maybe_unserialize($optionvalues[$optionkey]);
				} else {$optionvalues[$optionkey] = array();}
				// bioship_debug("Option Values", $optionvalues[$optionkey]);

				// --- set option value array ---
				$optionarray = array();
				// 2.0.9: fix for standalone multicheck customizer control saving
				if (is_string($optionvalues[$optionkey])) {
					if (strstr($optionvalues[$optionkey], ',')) {
						$optionvalues[$optionkey] = explode(',', $optionvalues[$optionkey]);
					} else {$optionvalues[$optionkey] = array($optionvalues[$optionkey]);}
				}

				// --- loop multicheck options ---
				foreach ($optionvalue['options'] as $key => $label) {

					$multicheck[$optionkey][] = $key;
					// if (THEMEDEBUG) {echo "<!-- Key: ".$key." - Label: ".$label." ? -->";}

					// 1.9.9: fix to arrays switching around weirdly
					// 2.1.0: add check for if optionvalues is an array
					if (is_array($optionvalues[$optionkey])) {
						if (array_key_exists($key, $optionvalues[$optionkey])) {
							// 2.0.0: one more fix for this absolute madness!
							// bioship_debug("!A!", $optionvalues[$optionkey][$key]);
							if ($optionvalues[$optionkey][$key] == '0') {$optionarray[$key] = '0';}
							else {$optionarray[$key] = '1';}
						} elseif (in_array($key, $optionvalues[$optionkey])) {
							// bioship_debug("!B!", $optionvalues[$optionkey][$key]);
							$optionarray[$key] = '1';
						} else {$optionarray[$key] = '0';}
					} else {$optionarray[$key] = '0';}
				}

				// ! WARNING: uncommenting this debug line may prevent Customizer saving !
				bioship_debug("Option Array for '".$optionkey."'", $optionarray, false, true);
				$vthemesettings[$optionkey] = $optionarray;
			}

			// --- convert attachment IDs to actual image URL ---
			if ($optionvalue['type'] == 'upload') {
				// 1.9.8: add check for empty options to avoid warning
				if (isset($vthemesettings[$optionkey]) && is_numeric($vthemesettings[$optionkey])) {
					$image = wp_get_attachment_image_src($vthemesettings[$optionkey], 'full');
					$vthemesettings[$optionkey] = $image[0];
				}
			}
		}
	}

	// --- reset the multicheck options index ---
	// 2.1.1: just use update_option instead of delete and add
	update_option($vthemename.'_multicheck_options', $multicheck);

	return $vthemesettings;
 }
}

// -------------------------------
// Load Theme Options and Settings
// -------------------------------
// 1.8.0: load options.php (if not already using Options Framework)
// 1.9.5: moved settings transfers to admin.php
// note: converts all options whether Titan Framework is loaded or not
if (!THEMEOPT) {

	// --- set theme settings key ---
	// 2.0.5: simplify to use THEMESLUG here
	$vthemename = THEMESLUG;
	if (!defined('THEMEKEY')) {define(THEMEKEY, $vthemename.'_options');}
	// 1.9.5: add filter to get theme settings with fallback
	add_filter('pre_option_'.THEMEKEY, 'bioship_get_theme_settings', 10, 2);

	// --- load the theme options array ---
	$options = bioship_file_hierarchy('file', 'options.php');
	if ($options) {include($options);}
	else {wp_die(esc_attr(__('Uh oh, the required Theme Option definitions are missing! Reinstall?!','bioship')));}

	// 1.9.5: fix for when transferred options manually ?
	// if (!function_exists('bioship_titan_no_options_fix')) {
	// add_filter('tf_init_no_options_'.THEMEKEY, 'bioship_titan_no_options_fix');
	//  function bioship_titan_no_options_fix() {return maybe_unserialize(get_option(THEMEKEY));}
	// }

	// --- to initialize the Titan Framework admin page ---
	if (!function_exists('bioship_titan_create_options')) {
	 add_action('tf_create_options', 'bioship_titan_create_options');
	 function bioship_titan_create_options() {$loadtitan = bioship_optionsframework_to_titan();}
	}

	// --- load options array  ---
	// note: whether Titan class available or not
	global $vthemeoptions;
	$vthemeoptions = bioship_options();

	$settings = maybe_unserialize(get_option(THEMEKEY));
	bioship_debug("Titan Framework Settings", $settings);
	$optionvalues = maybe_unserialize($settings);

	// Customizer Live Preview Values
	// ------------------------------
	// 1.8.5: fix for Customizer transport refresh method
	// check for the posted preview values manually as Customizer is failing to do this for us!
	// TODO: check if this is needed with Options Framework + Customizer combination
	// note: http://badfunproductions.com/use-is_customize_preview-and-not-is_admin-with-theme-customization-api/
	global $pagenow;
	if (is_customize_preview() && ($pagenow != 'customize.php')) {
		// ! WARNING: Debug output in Customizer can prevent saving !
		// bioship_debug("Customize Preview Theme Options");
		if (isset($_POST['customized'])) {$postedvalues = json_decode(wp_unslash($_POST['customized']), true);}
		if (!empty($postedvalues)) {
			// bioship_debug("Posted Preview Values", $postedvalues);
			$optionvalues = bioship_customizer_convert_posted($postedvalues, $optionvalues);
			// bioship_debug("Full Preview Options", $optionvalues);
		}
	}

	// --- get Theme Settings ---
	$vthemesettings = bioship_titan_theme_options($optionvalues);

	// to manually debug all theme options / values
	// bioship_debug("THEMEKEY", THEMEKEY);
	// bioship_debug("Theme Options", $vthemeoptions);
	// bioship_debug("Option Values", $optionvalues);
	// bioship_debug("Theme Settings", $vthemesettings);

	// --- AutoBackup Theme Settings ---
	// 1.9.5: moved here for better checking placement
	// 2.1.1: just use update_option instead of delete and add
	if ($optionvalues && !empty($optionvalues) && ($optionvalues != '') && is_array($optionvalues)) {
		$backupkey = THEMEKEY.'_backup'; update_option($backupkey, $vthemesettings);
	}

}

// --------------------------
// Load Theme Admin Functions
// --------------------------
// 2.1.1: simplified admin loading conditional logic
if (is_admin()) {$loadadmin = true;} else {$loadadmin = false;}
// 1.9.5: also load if requesting theme dump output
if ( ($vthemesettings == '') || (isset($_REQUEST['themedump'])) ) {$loadadmin = true;}
// 1.8.0: allow for backup/restore/import/export/revert theme options AJAX requests
if (isset($_REQUEST['action']) && strstr($_REQUEST['action'], '_theme_options')) {$loadadmin = true;}
if ($loadadmin) {
	$themeadmin = bioship_file_hierarchy('file', 'admin.php', $vthemedirs['admin']);
	if ($themeadmin) {include($themeadmin);}
}

// --------------------------
// Set HTML Comments Constant
// --------------------------
if (!defined('THEMECOMMENTS')) {
	$htmlcomments = false;
	$htmlcomments = true;
	if ( (isset($vthemesettings['htmlcomments'])) && ($vthemesettings['htmlcomments'] == '1') ) {$htmlcomments = true;}
	$htmlcomments = bioship_apply_filters('skeleton_html_comments', $htmlcomments);
	define('THEMECOMMENTS', $htmlcomments);
}

// -----------------------
// Set Script Cachebusting
// -----------------------
global $vjscachebust; $cachebust = $vthemesettings['javascriptcachebusting'];
// 1.9.5: clear stat cache for filemtime cachebusting
if ( ($cachebust == 'yearmonthdate') || ($cachebust == '') ) {$vjscachebust = date('ymd').'0000';}
elseif ($cachebust == 'yearmonthdatehour') {$vjscachebust = date('ymdH').'00';}
elseif ($cachebust == 'datehourminutes') {$vjscachebust = date('ymdHi');}
elseif ($cachebust == 'themeversion') {$vjscachebust = THEMEVERSION;}
elseif ($cachebust == 'childversion') {$vjscachebust = THEMECHILDVERSION;}
elseif ($cachebust == 'filemtime') {clearstatcache();}

// ----------------------------
// Set Stylesheet Cachebusting
// ----------------------------
global $vcsscachebust; $cachebust = $vthemesettings['stylesheetcachebusting'];
// 1.9.5: clear stat cache for filemtime cachebusting
if ( ($cachebust == 'yearmonthdate') || ($cachebust == '') ) {$vcsscachebust = date('ymd').'0000';}
elseif ($cachebust == 'yearmonthdatehour') {$vcsscachebust = date('ymdH').'00';}
elseif ($cachebust == 'datehourminutes') {$vcsscachebust = date('ymdHi');}
elseif ($cachebust == 'themeversion') {$vcsscachebust = THEMEVERSION;}
elseif ($cachebust == 'childversion') {$vcsscachebust = THEMECHILDVERSION;}
elseif ($cachebust == 'filemtime') {clearstatcache();}

// ---------------------------
// Declare WooCommerce Support
// ---------------------------
// ref: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
// WARNING: Do NOT create a woocommerce.php page template for your theme!
// This is a 'beginner mistake' as it prevents you from overriding default templates
// such as single-product.php and archive-product.php later (see class-wc-template-loader.php)
// TODO: test whether default to true has any negative effects
$woosupport = bioship_apply_filters('skeleton_declare_woocommerce_support', true);
if ($woosupport) {add_theme_support('woocommerce');}
else {
	// --- remove no support notice ---
	// 1.8.0: auto-remove the 'this theme does not declare WooCommerce support' notice
	// (as this theme does support WooCommerce whether it is flagged or not)
	if (!function_exists('bioship_remove_woocommerce_theme_notice')) {

		add_action('admin_notices', 'bioship_remove_woocommerce_theme_notice');

		function bioship_remove_woocommerce_theme_notice() {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

			// 2.1.1: moved class exists check internally
			if (!class_exists('WC_Admin_Notices')) {return;}

			// --- maybe remove admin notice ---
			$notices = array_diff(get_option('woocommerce_admin_notices', array()), array('theme_support'));
			update_option('woocommerce_admin_notices', $notices);
		}
	}
}

// ------------------------------
// Set Post Format Theme Supports
// ------------------------------
// note: need to declare this support before loading Hybrid Core
if ($vthemesettings['postformatsupport'] == '1') {

	$i = 0; $postformatsupport = array();

	// --- filter post format support ---
	// 2.0.9: added missing filter for this theme setting
	$postformats = bioship_apply_filters('skeleton_post_format_support', $vthemesettings['postformats']);

	// --- add theme support for post formats ---
	// 2.1.1: added post format array check
	if (is_array($postformats) && (count($postformats) > 0)) {
		foreach ($postformats as $postformat => $value) {
			if ($value == '1') {$postformatsupport[$i] = $postformat; $i++;}
		}
		if (count($postformatsupport) > 0) {
			add_theme_support('post-formats', $postformatsupport);
		}
	}
}

// --------------------------------
// Output Theme Settings Debug Info
// --------------------------------
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

// -----------------
// Customizer Loader
// -----------------
// 1.8.0: conditionally load Customizer options via customizer.php
if (!function_exists('bioship_customizer_loader')) {

 // 2.0.9: changed to earlier loading action (for Kirki 3)
 add_action('after_setup_theme', 'bioship_customizer_loader', 5);

 function bioship_customizer_loader($previewwindow = false) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	if (function_exists('bioship_customizer_preview')) {return true;}

	// --- check conditions ---
	// 2.0.9: check conditions if not in preview window
	if (!$previewwindow) {
		if (!is_admin() || !is_user_logged_in()) {return false;}
		if (!current_user_can('edit_theme_options')) {return false;}
	}

	// --- load admin Customizer integration ---
	global $vthemedirs;
	$customizer = bioship_file_hierarchy('file', 'customizer.php', $vthemedirs['admin']);
	if ($customizer) {
		include_once($customizer);

		// --- load Kirki libary ---
		// 2.0.9: maybe initialize Kirki library now
		// 2.1.1: moved inside customizer loaded check
		bioship_kirki_loader();
	}

	// --- return whether loaded ---
	if ($customizer) {return true;} else {return false;}
 }
}

// ------------------------
// Load Customizer Controls
// ------------------------
if (!function_exists('bioship_customizer_init')) {

 add_action('customize_register', 'bioship_customizer_init');

 function bioship_customizer_init($wp_customize) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	remove_action('customize_register', 'bioship_customizer_init');

	// --- load Customizer ---
	// 2.0.9: load Customizer functions for the preview window
	$loaded = bioship_customizer_loader();
	if (!$loaded) {return;}

	// --- register and load Customizer controls ---
	bioship_customizer_register_controls($wp_customize);
	bioship_customizer_load_control_options($wp_customize);

	// --- load Customizer scripts in footer ---
	add_action('customize_controls_print_footer_scripts', 'bioship_customizer_text_script');
 }
}

// -------------------------
// Customizer Preview Window
// -------------------------
// note: controls do not need to be registered for preview window
if (!function_exists('bioship_customizer_preview_loader')) {

 add_action('customize_preview_init', 'bioship_customizer_preview_loader');

 function bioship_customizer_preview_loader() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$loaded = bioship_customizer_loader(true);
	if (!$loaded) {return;}

	// --- register and load Customizer controls ---
 	// 2.0.9: make sure to register and load controls in preview window
 	// (otherwise active states are not set and controls, panels and sections disappear!)
 	global $wp_customize;
 	bioship_customizer_register_controls($wp_customize);
	bioship_customizer_load_control_options($wp_customize);

	// --- load the Customizer Preview scripts ---
	add_action('wp_footer', 'bioship_customizer_preview', 21);
 }
}

// ------------------------
// Customizer Loading Image
// ------------------------
// 1.8.5: added Kirki preview loading image override
// 1.9.5: moved this to Customizer Section from stylesheet loading
global $pagenow;
if (is_customize_preview() && ($pagenow != 'customize.php')) {
	if (!function_exists('bioship_customizer_loading_icon')) {

	 add_action('wp_head', 'bioship_customizer_loading_icon');

	 function bioship_customizer_loading_icon() {
		// 1.8.5: use bioship loading image
		global $vthemedirs;
		$loadingimage = bioship_file_hierarchy('url', 'customizer-loading.png', $vthemedirs['image']);
		if ($loadingimage) {
			echo "<style>.kirki-customizer-loading-wrapper {background-image: url('".esc_url($loadingimage)."') !important;}</style>";
		}
	 }
	}
}


// -------------------
// === HYBRID CORE ===
// -------------------
// 2.1.1: removed old vhybrid global (no longer used)
// 2.1.1: use simplified valid load values check
$loadhybrid = $vthemesettings['hybridloadcore'];
$valid = array('1', '2', '3');
if (in_array($loadhybrid, $valid)) {

	// --- set Hybrid Core flag ---
	// 2.1.1: removed old vhybrid global (no longer used)
	define('THEMEHYBRID', true);

	// --- set Hybrid Core directories ---
	// 1.8.0: set hybrid core library version to use
	// note: value 1 or 2 = HC2 (as previously a checkbox), value 3 = HC3
	if ( ($loadhybrid == '1') || ($loadhybrid == '2') ) {$hybridversion = '2';}
	elseif ($loadhybrid == '3') {$hybridversion= '3';}

	// 2.1.1: loop to allow for alternative includes directories
	$hybriddirs = array();
	if (count($vthemedirs['includes']) > 0) {
		// --- set directories
		if ($hybridversion == '2') {
			foreach ($vthemedirs['includes'] as $dir) {$hybriddirs[] = $dir.DIRSEP.'hybrid2';}
		}
		// 2.0.9: also fallback for HC2 to HC3 (in case HC2 removed/deprecated)
		foreach ($vthemedirs['includes'] as $dir) {$hybriddirs[] = $dir.DIRSEP.'hybrid3';}
	}
	// 2.0.9: filter the Hybrid directory search paths
	$hybriddirs = bioship_apply_filters('hybrid_dirs', $hybriddirs);

	// --- set Hybrid Core paths ---
	// (note: if loading Hybrid from Child Theme, ALL of Hybrid Core must be there - not just hybrid.php!)
	// 2.0.9: use file hierarchy to find Hybrid file path
	$hybridpath = bioship_file_hierarchy('file', 'hybrid.php', $hybriddirs);
	if (!$hybridpath) {wp_die(esc_attr(__('Uh oh, the required Hybrid Core Framework is missing!','bioship')));}
	$hybriddir = trailingslashit(dirname($hybridpath));
	define('HYBRID_DIR', $hybriddir);

	// initialize Hybrid Core
	// ----------------------
	require_once($hybridpath);
	new Hybrid();

	// Hybrid Core Setup
	// -----------------
	if (!function_exists('bioship_hybrid_core_setup')) {

	 // 2.1.1: moved add_action internally for consistency
	 add_action('after_setup_theme', 'bioship_hybrid_core_setup', 11);

	 function bioship_hybrid_core_setup() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// Hybrid Core Setup
		// -----------------

		// --- Add Custom Template Hierarchy ---
		// note: !*required*!
		add_theme_support('hybrid-core-template-hierarchy');

		// --- Backwards Compatibility ---
		// 1.8.0: added for HC3 (I like backwards compatibility)
		add_theme_support('hybrid-core-deprecated');

		// note: unuse Hybrid Core Theme Layout and Settings
		// add_theme_support('theme-layouts');
		// add_theme_support('hybrid-core-theme-settings');

		// Hybrid Extensions
		// -----------------

		// --- Get The Image ---
		// "The best thumbnail/image script ever"
		if ($vthemesettings['hybridthumbnails'] == '1') {add_theme_support('get-the-image');}

		// --- Hybrid Breadcrumbs ---
		if ($vthemesettings['hybridbreadcrumbs'] == '1') {add_theme_support('breadcrumb-trail');}

		// --- Nicer [gallery] shortcode styles ---
		if ($vthemesettings['hybridgallery'] == '1') {add_theme_support('cleaner-gallery');}

		// ...the rest of these were mostly just for Hybrid 2...
		// - Hybrid Shortcodes
		// deactivated shortcodes for now... is this even in HC3?
		// if ($vthemesettings['hybridshortcodes'] == '1') {add_theme_support('hybrid-core-shortcodes');}
		// --- Pagination ---
		// 1.8.0: removed in Hybrid Core v3
		// if ($vthemesettings['hybridpagination'] == '1') {add_theme_support('loop-pagination');}
		// --- Better captions for themes to style ---
		// note: removed in Hybrid Core v3
		// if ($vthemesettings['hybridcaptions'] == '1') {add_theme_support('cleaner-caption');}
		// --- Per Page Featured Header image --- (removed as custom_header no longer used)
		// if ($vthemesettings['hybridfeaturedheaders'] == '1') {add_theme_support('featured-header');}
		// --- Random Custom Background ---
		// note: removed in Hybrid Core v3
		// if ($vthemesettings['hybridrandombackground'] == '1') {add_theme_support('random-custom-background');}
		// --- Per Post Stylesheets ---
		// 1.5.0: removed as handled by theme metabox
		// if ($vthemesettings['hybridpoststylesheets'] == '1') {add_theme_support('post-stylesheets');}

		// --- backwards compatibility images fix ---
		// 1.3.5: WP < 3.9 Fix: remove hybrid_image_size_names_choose (media.php) // >
		// causing editor footer scripts to crash prior to 3.9 (to support WP 3.4+)
		// (avoids fatal error: has_image_size function does not exist)
		global $wp_version;
		if (version_compare($wp_version,'3.9','<')) { //'>
			remove_filter('image_size_names_choose', 'hybrid_image_size_names_choose');
		}

		// --- remove the Hybrid Title Action ---
		// (Fix for Hybrid 2)
		// 2.0.5: check action is hooked before removing
		// 2.0.8: revert to not check action as preventing removal
		remove_action('wp_head', 'hybrid_doctitle', 0);

	 }
	}

} else {

	// Non-Hybrid Fallback Loading
	// ---------------------------

	// --- set Hybrid flag ---
	// 1.8.0: set constant flag for non-Hybrid
	// 2.1.1: removed old vhybrid constant (no longer used)
	define('THEMEHYBRID', false);

	// --- HTML5 Theme Supports ---
	// 2.0.5: add HTML5 support without Hybrid
	add_theme_support('html5', array('caption', 'comment-form', 'comment-list', 'gallery', 'search-form'));

	// --- Enable Shortcodes in Widget Text ---
	// (as loading Hybrid Core adds this)
	// 1.9.8: added has_filter check
	// note: https://core.trac.wordpress.org/changeset/41361
	// TODO: maybe change this in conjunction with discreet text widget (filtered)?
	if (!has_filter('widget_text', 'do_shortcode')) {add_filter('widget_text', 'do_shortcode');}

	// Include only necessary Hybrid functions
	// ---------------------------------------
	// 1.8.0: updated to use the Hybrid Core 3 versions of these files
	// 2.1.1: use file hierarchy to allow child theme file overrides

	// --- Hybrid Attributes ---
	// (for the better/cleaner markup)
	$attributes = bioship_file_hierarchy('file', 'hybrid3-attr.php', $vthemedirs['includes']);
	require_once($attributes);

	// --- Template hierarchy ---
	// (to support improved template locations)
	$template = bioship_file_hierarchy('file', 'hybrid3-template.php', $vthemedirs['includes']);
	require_once($template);

	// --- general template functions ---
	$templategeneral = bioship_file_hierarchy('file', 'hybrid3-template-general.php', $vthemedirs['includes']);
	require_once($templategeneral);

	// --- load media template ---
	// 1.8.5: load the media template so that attachments are not fatal
	// (or we could strip media functions from hybrid3-attr.php?)
	$templatemedia = bioship_file_hierarchy('file', 'hybrid3-template-media.php', $vthemedirs['includes']);
	require_once($templatemedia);

	// --- load media classes ---
	// 2.1.2: load the media classes to prevent crashing
	$mediaclasses = bioship_file_hierarchy('file', 'hybrid3-media-classes.php', $vthemedirs['includes']);
	require_once($mediaclasses);

	// --- load utility functions ---
	// (fix for possible undefined hybrid_get_menu_location_name)
	$utility = bioship_file_hierarchy('file', 'hybrid3-utility.php', $vthemedirs['includes']);
	require_once($utility);

	// --- conditional Hybrid Media Grabber loader ---
	if (!function_exists('bioship_load_hybrid_media')) {
	 function bioship_load_hybrid_media() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemedirs;
		// 2.1.1: use file hierarchy here to allow child theme file overrides
		$mediagrabber = bioship_file_hierarchy('file', 'hybrid-media-grabber.php', $vthemedirs['includes']);
		if ($mediagrabber) {require_once($mediagrabber);}
	 }
	}

	// --- meta character set tag ---
	// 1.8.5: moved here from header.php to prevent duplicate if using Hybrid
	if (!function_exists('bioship_meta_charset')) {

	 // 2.1.1: moved add_action internally for consistency
	 add_action('wp_head', 'bioship_meta_charset', 0);

	 function bioship_meta_charset() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	 	// 2.1.1: added esc_attr wrapper to output
	 	echo '<meta charset="'.esc_attr(get_bloginfo('charset')).'">';
	 }
	}

	// --- add pingback link to document head ---
	if (!function_exists('bioship_pingback_link')) {

	 // 2.1.1: move add_action internally for consistency
	 add_action('wp_head', 'bioship_pingback_link', 3);

	 function bioship_pingback_link() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (get_option('default_ping_status') == 'open') {
			// 2.1.1: added esc_url wrapper to output
			echo '<link rel="pingback" href="'.esc_url(get_bloginfo('pingback_url')).'">';
		}
	 }
	}

	// --- Hybrid Core 3 compatibility fix ---
	// 1.8.0: fix as missing for HC3 (from functions-sidebar.php)
	if (!function_exists('hybrid_get_sidebar_name')) {
	 function hybrid_get_sidebar_name($id) {
		global $wp_registered_sidebars;
		if (!isset($wp_registered_sidebars[$id])) {return '';}
		return $wp_registered_sidebars[$id]['name'];
	 }
	}
}

// -----------------------
// Fix to Main Element IDs
// -----------------------
// 1.5.0: prevent duplicate element IDs via Hybrid attribute filters (eg. content and maincontent)
// 2.0.5: added missing function_exists wrappers to attribute fixes
// --- header ID attribute ---
if (!function_exists('bioship_hybrid_attr_header')) {
 add_filter('hybrid_attr_header', 'bioship_hybrid_attr_header', 6);
 function bioship_hybrid_attr_header($attr) {$attr['id'] = 'mainheader'; return $attr;}
}
// --- site description ID attribute ---
if (!function_exists('bioship_hybrid_attr_site_description')) {
 // 1.9.0: added fix for new site-description attribute duplicate
 add_filter('hybrid_attr_site-description', 'bioship_hybrid_attr_site_description', 6);
 function bioship_hybrid_attr_site_description($attr) {$attr['id'] = 'site-desc'; return $attr;}
}
// --- content ID attribute ---
if (!function_exists('bioship_hybrid_attr_content')) {
 add_filter('hybrid_attr_content', 'bioship_hybrid_attr_content', 6);
 function bioship_hybrid_attr_content($attr) {$attr['id'] = 'maincontent'; return $attr;}
}
// --- footer ID attribute ---
if (!function_exists('bioship_attr_footer')) {
 add_filter('hybrid_attr_footer', 'bioship_hybrid_attr_footer', 6);
 function bioship_hybrid_attr_footer($attr) {$attr['id'] = 'mainfooter'; return $attr;}
}


// =============
// === SKULL ===
// =============

// --- Load the Skull ---
// (head, resource and layout calculation functions)
// (override pluggable skull functions in Child Theme functions.php)

$skull = bioship_file_hierarchy('file', 'skull.php');
require_once($skull);


// ================
// === SKELETON ===
// ================

// --- Load Hook definitions ---
// (page template layout definitions)
// 1.9.0: load hook definitions before skeleton.php
$hooks = bioship_file_hierarchy('file', 'hooks.php');
require_once($hooks);

// --- Load the Skeleton ---
// (all layout and template tag functions)
$skeleton = bioship_file_hierarchy('file', 'skeleton.php');
require_once($skeleton);


// ==============
// === MUSCLE ===
// ==============

// --- Load all the Muscle ---
// (extended theme feature functions ---
$muscle = bioship_file_hierarchy('file', 'muscle.php');
if ($muscle) {require_once($muscle);}


// ============
// === SKIN ===
// ============
// Note: skin.php is used for outputting the Dynamic CSS
// but all the Skin trigger and enqueueing functions were here
// 2.1.1: but now have all been moved to the end of skull.php


// -------------------
// Fully shipped bruz.
