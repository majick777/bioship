<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * ==== Dynamic Style Skin Loader ===
 * - Outputs CSS from Theme Options -
 *
**/

// TODO: check for possible CSS fallback usage...
// ref: http://modernweb.com/2013/07/08/using-css-fallback-properties-for-better-cross-browser-compatibility/
// TODO: when using Titan/Options Framework rgba colour picker
// ref: https://css-tricks.com/rgba-browser-support/

// Output CSS Header
// -----------------
// 1.8.5: undefined variable fix
if (!isset($vincluded)) {$vincluded = false;}
if (!isset($vadminstyles)) {$vadminstyles = false;}
// 1.8.5: no header for inline style output fix
if ( (!$vincluded) && (!$vadminstyles) ) {
	header("Content-type: text/css; charset: UTF-8");
}

if ($vadminstyles) {echo "/* Dynamic Admin Skin */".PHP_EOL.PHP_EOL;}
else {echo "/* Dynamic Stylesheet Skin */".PHP_EOL.PHP_EOL;}

// AJAX Load Time Start
// --------------------
// 1.8.0: fix for performance timer variable
if (defined('DOING_AJAX') && DOING_AJAX) {global $vthemetimestart;}

// ------------------------------
// Direct Load Memory Saving Mode
// ------------------------------
// ...a little experimental... thought it might be faster-loading.
// but it is almost exactly the same, so not much point in this.
// and, problem is, main Constants are not defined using direct method yet...
// so currently it is abandoned :-( ... wp_initial_constants(); ???

if (strstr($_SERVER['REQUEST_URI'],'skin.php')) {

	$vthemetimestart = microtime(true);

	// Use our friend Shorty...
	define('SHORTINIT', true);
	$wp_root_path = bioship_skin_find_require('wp-load.php');
	$memorysavingmode = true;

	// Include only what you need to survive...
	// not the industrial strength hairdryer. :-D

	// get_option (option.php - loaded)
	// apply_filters (plugin.php - loaded)
	// get_bloginfo (general-template.php, version.php)
	// wp_get_theme (themes.php)
	// get_theme_data (deprecated.php - conditional load)
	// get_stylesheet_directory (themes.php)

	// templates and dependencies
	if (!defined('ABSPATH')) {define('ABSPATH', $wp_root_path);}
	if (!defined('WPINC')) {define('WPINC', 'wp-includes');}

	// Include files required for initialization.
	// 1.8.0: use DIRECTORY_SEPARATOR constant

	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'version.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'general-template.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'link-template.php');

	// theme class and dependencies
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'kses.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'shortcodes.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'formatting.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'class-wp-theme.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'theme.php');

	// current_user_can (capabilities.php, pluggable.php, user.php, post.php)
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'capabilities.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'pluggable.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'user.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'post.php');


	// WP 4.4: more functions are needed now
	$postclass = ABSPATH.WPINC.DIRECTORY_SEPARATOR.'class-wp-post.php';
	if (file_exists($postclass)) {include($postclass);}
	$restapi = ABSPATH.WPINC.DIRECTORY_SEPARATOR.'rest-api.php';
	if (file_exists($restapi)) {include($restapi);} // 1.8.0
	$userclass = ABSPATH.WPINC.DIRECTORY_SEPARATOR.'class-wp-user.php';
	if (file_exists($userclass)) {include($userclass);} // 1.8.0

	// need wp_get_attachment_image_src for Titan uploads
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'media.php');
	include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'meta.php');

	// ...for options.php
	// include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'category.php');
	// include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'taxonomy.php');
	// include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'l10n.php');
	// include(ABSPATH.WPINC.DIRECTORY_SEPARATOR.'locale.php');

	// Theme functions.php : skeleton_themedrive_determine_theme (copied)
	// Theme functions.php : skeleton_file_hierarchy (replaced)

	// Custom Value Filters: filters.php
	$vfilters = get_stylesheet_directory().'/filters.php';
	if (file_exists($vfilters)) {include_once($vfilters);}
	else {
		$vfilters = get_template_directory().'/filters.php';
		if (file_exists($vfilters)) {include_once($vfilters);}
	}

	// 1.8.0: fix constants for replacement values
	if (!defined('WP_CONTENT_DIR')) {
		include_once(ABSPATH.WPINC.'/default-constants.php');
		wp_initial_constants();
		wp_plugin_directory_constants();
		// wp_cookie_constants(); // ??
	}

	// TODO: load MU Plugins?
}

// maybe define WP_CONTENT_URL
// ---------------------------
// 1.8.0: move here for dual framework compatibility
if (!defined('WP_CONTENT_URL')) {define('WP_CONTENT_URL',get_option('siteurl').'/wp-content');}

// add Default Skin value filters
// ------------------------------
// 2.0.2: [deprecated] use replacement function not filter
// if (function_exists('add_filter')) {
	// add_filter('skin_dynamic_css','skin_css_replace_values');
	// add_filter('skin_dynamic_admin_css','skin_css_replace_values');
// }


// ---------------------
// Skin Helper Functions
// ---------------------

// Find/Require for Blog Loader
// ----------------------------
function bioship_skin_find_require($file,$folder=null) {
	if ($folder === null) {$folder = dirname(__FILE__);}
	$path = $folder.DIRECTORY_SEPARATOR.$file;
	if (file_exists($path)) {require($path); return $folder;}
	else {
		$upfolder = bioship_skin_find_require($file,dirname($folder));
		if ($upfolder != '') {return $upfolder;}
	}
}

// Get Image Size from URL
// -----------------------
// 1.8.5: made separate for header background and login logo
// 1.8.5: cleaned logic for caching image size
if (!function_exists('bioship_skin_get_image_size')) {
 function bioship_skin_get_image_size($vimageurl,$vcachekey) {

	global $vthemename, $vthemesettings;
	if (THEMEDEBUG) {echo "/* Image URL: ".$vimageurl." */".PHP_EOL;}
	$vcachekey = $vthemename.'_'.$vcachekey;

	// really want to set an explicit width and a height for the header background
	// as it does display better, but needs a bit of hackiness to get it going okay
	// so we check for a cached image size - or we use imagesize and then cache it
	// this prevents imagesize from downloading the image url every pageload
	// update: but, need to check for allow_url_fopen to do this for URLs

	$vimagesize = false;
	if (THEMEDEBUG) {echo "/* Getting Cached Key: ".$vcachekey." */".PHP_EOL;}
	$vimagesizedata = get_option($vcachekey);
	if (THEMEDEBUG) {echo "/* Cached Image Size Data: ".$vimagesizedata." */".PHP_EOL;}
	if ( ($vimagesizedata != '') && (strstr($vimagesizedata,'::')) ) {
	 	$vimagesize = explode('::',$vimagesizedata);
		// match URL and make sure does not exceed maximum layout width
		if ( ($vimagesize[2] != $vimageurl) || ($vimagesize[0] > $vthemesettings['layout']) ) {
			delete_option($vcachekey); $vimagesize = false;
		}
	}

	if (!$vimagesize) {
		if (!ini_get('allow_url_fopen')) {
			// try to convert the url to filepath (onsite URLs only)
			$vsiteurl = site_url(); $vhomeurl = home_url(); $vabspath = untrailingslashit(ABSPATH);
			if (substr($vimageurl,0,strlen($vsiteurl)) == $vsiteurl) {$vimagepath = str_replace($vsiteurl,$vabspath,$vimageurl);}
			elseif (substr($vimageurl,0,strlen($vhomeurl)) == $vhomeurl) {$vimagepath = str_replace($vhomeurl,$vabspath,$vimageurl);}
			if (file_exists($vimagepath)) {$vimagesize = getimagesize($vimagepath);}
			if (THEMEDEBUG) {
				// TODO: check this works for subdirectory installs?
				// if ( (site_url()) == (home_url()) ) {}
				echo "/* Site URL: ".site_url()." */".PHP_EOL;
				echo "/* Home URL: ".home_url()." */".PHP_EOL;
				echo "/* Image Path: ".$vimagepath." */".PHP_EOL;
			}
		} else {$vimagesize = getimagesize($vimageurl);}

		// update the image size cache key
		if ($vimagesize) {
			// maybe adjust for maximum layout width
			if ($vimagesize[0] > $vthemesettings['layout']) {
				if (THEMEDEBUG) {echo "/* Original Size: "; print_r($vimagesize); echo " */".PHP_EOL;}
				$vratio = $vimagesize[1] / $vimagesize[0];
				$vimagesize[1] = round( ($vthemesettings['layout'] * $vratio),3,PHP_ROUND_HALF_DOWN );
				$vimagesize[0] = $vthemesettings['layout'];
				if (THEMEDEBUG) {echo "/* Adjusted Size: "; print_r($vimagesize); echo " */".PHP_EOL;}
			}
			$vimagedata = $vimagesize[0].'::'.$vimagesize[1].'::'.$vimageurl;
			delete_option($vcachekey); add_option($vcachekey,$vimagedata);
			if (THEMEDEBUG) {echo "/* Set Cached Key: ".$vcachekey." - ".$vimagedata." */".PHP_EOL;}
		}
	}

	if ($vimagesize) {
		if (THEMEDEBUG) {echo "/* Image Size: "; print_r($vimagesize); echo " */".PHP_EOL;}
		return $vimagesize;
	}

	return false;
 }
}

// CSS Replacement Value Function
// ------------------------------
if (!function_exists('bioship_skin_css_replace_values')) {
 function bioship_skin_css_replace_values($vcss) {

	// 1.8.0: added trailing slash fix
	$vthemestyleurl = get_stylesheet_directory_uri().'/';
	$vthemetemplateurl = get_template_directory_uri().'/';

	// 1.8.0: force SSL fix
	if (is_ssl()) {
		$vthemestyleurl = str_replace('http://','https://',$vthemestyleurl);
		$vthemetemplateurl = str_replace('http://','https://',$vthemetemplateurl);
	}

	// Dynamic replacement values
	if (strstr($vcss,'%STYLEURL%')) {$vcss = str_replace('%STYLEURL%',$vthemestyleurl,$vcss);}
	if (strstr($vcss,'%STYLESHEETURL%')) {$vcss = str_replace('%STYLESHEETURL%',$vthemestyleurl,$vcss);}
	if (strstr($vcss,'%STYLEIMAGEURL%')) {$vcss = str_replace('%STYLEIMAGEURL%',$vthemestyleurl.'images/',$vcss);}
	if (strstr($vcss,'%TEMPLATEURL%')) {$vcss = str_replace('%TEMPLATEURL%',$vthemetemplateurl,$vcss);}
	if (strstr($vcss,'%TEMPLATEIMAGEURL%')) {$vcss = str_replace('%TEMPLATEIMAGEURL%',$vthemetemplateurl.'images/',$vcss);}

	// HTC File Links
	// --------------
	if (strstr($vcss,'%BORDERRADIUS%')) {$vcss = str_replace('%BORDERRADIUS%',$vthemetemplateurl.'css/border-radius.htc',$vcss);}
	if (strstr($vcss,'%PIE%')) {$vcss = str_replace('%PIE%',$vthemetemplateurl.'css/pie.php',$vcss);}
	return $vcss;
 }
}

// copy of skeleton_themedrive_determine_theme function
if (!function_exists('bioship_themedrive_determine_theme')) {
 function bioship_themedrive_determine_theme() {

	// 1.8.5: added a check for if theme test drive is active!
	$vactiveplugins = maybe_unserialize(get_option('active_plugins'));
	if (!in_array('theme-test-drive/themedrive.php',$vactiveplugins)) {return false;}

	if (!isset($_REQUEST['theme'])) {
		$vtdlevel = get_option('td_level');
		if ($vtdlevel != '') {$vpermissions = 'level_'.$vtdlevel;}
		else {$vpermissions = 'level_10';}
		if (!current_user_can($vpermissions)) {return false;}
		else {
			$vtdtheme = get_option('td_themes');
			if ( (empty($vtdtheme)) || ($vtdtheme == '') ) {return false;}
		}
	}
	else {$vtdtheme = $_REQUEST['theme'];}

	$vthemedata = wp_get_theme($vtdtheme);

	if (!empty($vthemedata)) {return $vthemedata;}

	$vallthemes = wp_get_themes();
	foreach ($vallthemes as $vthemedata) {
		if ($vthemedata['Stylesheet'] == $vtdtheme) {return $vthemedata;}
	}

	return false;
 }
}

// Fix Serialized
// --------------
// (copy of skeleton_fix_serialized)
function bioship_skin_fix_serialized($string) {
    // securities
    if ( !preg_match('/^[aOs]:/', $string) ) return $string;
    if ( @unserialize($string) !== false ) return $string;
    $string = preg_replace("%\n%", "", $string);
    // doublequote exploding
    $data = preg_replace('%";%', "µµµ", $string);
    $tab = explode("µµµ", $data);
    $new_data = '';
    foreach ($tab as $line) {
        $new_data .= preg_replace_callback('%\bs:(\d+):"(.*)%', 'bioship_skin_fix_str_length', $line);
    }
    return $new_data;
}

// Fix Serialized String Callback
// ------------------------------
function bioship_skin_fix_str_length($matches) {
    $string = $matches[2];
    $right_length = strlen($string);
    return 's:' . $right_length . ':"' . $string . '";';
}


// ---------
// Get Theme
// ---------
$vtheme = wp_get_theme();

// Theme Test Drive Compatibility
// ------------------------------
$vthemetestdrive = bioship_themedrive_determine_theme();
if ($vthemetestdrive) {$vtheme = $vthemetestdrive;}

// manual debug only for private theme object
// echo "/*".PHP_EOL; echo "Theme: "; print_r($vtheme); echo "*/".PHP_EOL.PHP_EOL;

// Theme Debug
// -----------
// 1.8.5: allow for debugging
// TODO: maybe use improved debug switching from functions.php?
if (!$vincluded) {
	if (!defined('THEMEDEBUG')) {
		// 1.9.8: fix for undefined vthemekey variable
		$vthemekey = preg_replace("/\W/","_",strtolower($vtheme['Name']));
		$vthemedebug = get_option($vthemekey.'_theme_debug');
		if ($vthemedebug == '1') {$vthemedebug = true;} else {$vthemedebug = false;}
		if (isset($_REQUEST['themedebug'])) {
			$vdebugrequest = $_REQUEST['themedebug'];
			// note: no on/off switching allowed here
			if ( ($vdebugrequest == '2') || ($vdebugrequest == 'yes') ) {$vthemedebug = true;}
			if ( ($vdebugrequest == '3') || ($vdebugrequest == 'no') ) {$vthemedebug = false;}
		}
		define('THEMEDEBUG',$vthemedebug);
	}
}

// -----------------
// Get Theme Options
// -----------------
$vthemename = $vtheme['Name'];
$vthemename = preg_replace("/\W/","-",strtolower($vthemename));

// Options Framework (if Titan is off)
$vthemeframework = get_option($vthemename.'_framework');
if (THEMEDEBUG) {echo "/* ".$vthemename.'_framework : '.$vthemeframework." */";}
if ($vthemeframework == 'options') {
	$vthemename = str_replace("-","_",$vthemename);
	$vthemesettings = get_option($vthemename);
	if (THEMEDEBUG) {echo "/* Options Framework */".PHP_EOL;}
} else {
	// 1.8.0: changed to Titan Framework Options
	$vthemekey = $vthemename.'_options';
	$vsettings = get_option($vthemekey);
	if (is_serialized($vsettings)) {$vthemesettings = unserialize($vsettings);}

	// 1.9.5: added theme settings to file fallback
	if ( (!$vsettings) || (!$vthemesettings) ) {
		$vsavedfile = get_stylesheet_directory().'/debug/'.$vthemekey.'.txt';
		echo "/* ".$vsavedfile." */";
		if (file_exists($vsavedfile)) {
			$vsaveddata = file_get_contents($vsavedfile);
			if ( (strlen($vsaveddata) > 0) && (is_serialized($vsaveddata)) ) {
				$vunserialized = unserialize($vsaveddata);
				if ($vunserialized) {$vthemesettings = $vunserialized;}
				else {
					echo "/* Unserialize Error: "; print_r(error_get_last()); echo " */";
					// 1.9.6: added possible serialization fix
					$vsaveddata = bioship_skin_fix_serialized($vsaveddata);
					$vunserialized = unserialize($vsaveddata);
					if ($vunserialized) {$vthemesettings = $vunserialized;}
					else {echo "/* Unserialize Error: "; print_r(error_get_last()); echo " */";}
				}
			}
		}
	}

	// maybe fix for serialized subarrays
	foreach ($vthemesettings as $vkey => $vvalue) {
		// 1.9.5: fix to the fix to the fix to the fix
		if (is_serialized($vvalue)) {$vthemesettings[$vkey] = unserialize($vvalue);}
	}

	// Convert Attachment IDs to URLs for Titan Uploads (dangit)
	$vimagenames = array('background_image','header_background_image','header_logo','loginbackgroundurl','loginlogourl');
	foreach ($vimagenames as $vimagename) {
		if (is_numeric($vthemesettings[$vimagename])) {
			$vimage = wp_get_attachment_image_src($vthemesettings[$vimagename],'full');
			$vthemesettings[$vimagename] = $vimage[0];
		}
	}
}

// 1.8.5: fix to empty checkboxes values
$vcheckboxes = get_option($vthemename.'_checkboxes');
if ( (is_array($vcheckboxes)) && (count($vcheckboxes) > 0) ) {
	foreach ($vcheckboxes as $vcheckbox) {
		if (!isset($vthemesettings[$vcheckbox])) {$vthemesettings[$vcheckbox] = '0';}
	}
}

// 1.8.5: fix to empty multicheck array values
$vmulticheck = get_option($vthemename.'_multicheck_options');
if ( (is_array($vmulticheck)) && (count($vmulticheck) > 0) ) {
	foreach ($vmulticheck as $vkey => $vsubkeys) {
		$vthisarray = array();
		foreach ($vsubkeys as $vsubkey) {
			if (is_serialized($vthemesettings[$vkey])) {$vthemesettings[$vkey] = unserialize($vthemesettings[$vkey]);}
			if (is_array($vthemesettings[$vkey])) {
				if (in_array($vsubkey,$vthemesettings[$vkey])) {$vthisarray[$vsubkey] = '1';}
				elseif ( (isset($vthemesettings[$vkey][$vsubkey])) && ($vthemesettings[$vkey][$vsubkey] == '1') ) {$vthisarray[$vsubkey] = '1';}
				else {$vthisarray[$vsubkey] = '0';}
			} else {$vthisarray[$vsubkey] = '0';}
		}
		$vthemesettings[$vkey] = $vthisarray;
	}
}

if (isset($_REQUEST['themedebug'])) {
	if ($_REQUEST['themedebug'] == 2) {
		echo "/*".PHP_EOL; echo "Checkbox Options: "; print_r($vcheckboxes); echo "*/".PHP_EOL.PHP_EOL;
		echo "/*".PHP_EOL; echo "Multicheck Options: "; print_r($vmulticheck); echo "*/".PHP_EOL.PHP_EOL;
		echo "/*".PHP_EOL; echo "Theme Options (".$vthemename."): "; print_r($vthemesettings); echo "*/".PHP_EOL.PHP_EOL;
	}
}

// PIE URL
// -------
// (skeleton file hierarchy not available)
if (file_exists(get_stylesheet_directory().'/css/pie.php')) {$vpieurl = get_stylesheet_directory_uri().'/css/pie.php';}
else {$vpieurl = get_template_directory_uri().'/css/pie.php';}
if (is_ssl()) {$vpieurl = str_replace('http://','https://',$vpieurl);} // force SSL fix

// ----------
// Typography
// ----------
// 1.8.0: added missing #content typography (no longer quite the same as body due to grid)
// 1.8.5: moved here to change handling of button font rules
// 1.8.5: added navmenu and navsubmenu typographies
$vtypographies = array('body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header',
	'navmenu', 'navsubmenu', 'sidebar', 'subsidebar', 'content', 'footer', 'button');
// 1.8.5: add headline typography CSS in any case to support customizer live preview logo/text switching
$vtypographies[] = 'headline'; $vtypographies[] = 'tagline'; $vtypographyrules = '';

foreach ($vtypographies as $vtype) {

	$vtyperef = $vtype.'_typography';
	if (isset($vthemesettings[$vtyperef])) {
		$vtypography = maybe_unserialize($vthemesettings[$vtyperef]);
		// echo "/* "; print_r($vtypography); echo " */"; // debug point

		// 1.8.0: fix for font-sizes, target inside divs, fix content column inners
		if ($vtype == 'body') {$vcssrule = "#content #maincontent";}
		elseif ($vtype == 'headline') {$vcssrule = "#header h1#site-title-text a";}
		elseif ($vtype == 'tagline') {$vcssrule = "#header #site-description .site-desc";}
		elseif ($vtype == 'header') {$vcssrule = "#header .inner";}
		elseif ($vtype == 'navmenu') {$vcssrule = "#navigation #mainmenu ul li, #navigation #mainmenu ul li a";}
		elseif ($vtype == 'navsubmenu') {$vcssrule = "#navigation #mainmenu ul ul li, #navigation #mainmenu ul ul li a";}
		elseif ($vtype == 'sidebar') {$vcssrule = "#sidebar .sidebar";}
		elseif ($vtype == 'subsidebar') {$vcssrule = "#subsidebar .sidebar";}
		elseif ($vtype == 'content') {$vcssrule = "#content .entry-content, #content .column .inner, #content .columns .inner";}
		elseif ($vtype == 'footer') {$vcssrule = "#footer #mainfooter";}
		elseif ($vtype == 'button') {$vcssrule = "body button, body input[type='reset'], body input[type='submit'], body input[type='button'], body a.button, body button a, body .button ";}
		else {$vcssrule = $vtype;} // for h1,h2,h3,h4,h5,h6

		if (isset($vtypography)) {
			// 1.8.0: adjust for Titan Framework Typography
			if (isset($vtypography['font-size'])) {$vtypography['size'] = $vtypography['font-size'];}
			if (isset($vtypography['font-family'])) {$vtypography['face'] = $vtypography['font-family'];}
			if (isset($vtypography['font-style'])) {$vtypography['style'] = $vtypography['font-style'];}

			if ($vtype != 'button') {$vtyporules = $vcssrule." {";} else {$vtyporules = '';}

			if ($vtypography['color'] != '') {$vtyporules .= "color:".$vtypography['color']."; ";}
			if ($vtypography['size'] != '') {$vtyporules .= "font-size:".$vtypography['size']."; ";}
			if ($vtypography['face'] != '') {
				if (strstr($vtypography['face'],'+')) {$vtypography['face'] = '"'.str_replace('+',' ',$vtypography['face']).'"';}
				// 1.8.0: detect font stacks vs. singular fonts to add quotes
				if (strstr($vtypography['face'],',')) {$vtyporules .= "font-family:".$vtypography['face']."; ";}
				else {$vtyporules .= "font-family:\"".$vtypography['face']."\"; ";}
			}
			// 1.8.0: fix, options framework 'style' value can be set to bold
			if ($vtypography['style'] != '') {
				if ($vtypography['style'] == 'bold') {$vtyporules .= "font-weight: bold;";}
				else {$vtyporules .= "font-style:".$vtypography['style']."; ";}
			}

			// 1.8.0: Titan Framework extended typography options
			if (isset($vtypography['font-weight'])) {$vtyporules .= "font-weight:".$vtypography['font-weight']."; ";}
			if (isset($vtypography['line-height'])) {$vtyporules .= "line-height:".$vtypography['line-height']."; ";}

			// note: these are rather superfluous...
			if (isset($vtypography['letter-spacing'])) {$vtyporules .= "letter-spacing:".$vtypography['letter-spacing']."; ";}
			if (isset($vtypography['text-transform'])) {$vtyporules .= "text-transform:".$vtypography['text-transform']."; ";}
			if (isset($vtypography['font-variant'])) {$vtyporules .= "font-variant:".$vtypography['font-variant']."; ";}

			// Titan text-shadow attributes (off as seem superfluous)
			// [text-shadow-location] => none
			// [text-shadow-distance] => 0px
			// [text-shadow-blur] => 0px
			// [text-shadow-color] => #333333
			// [text-shadow-opacity] => 1

			// 1.8.5: store button font rules to use for button selectors
			if ($vtype == 'button') {$vbuttonfontrules = $vtyporules;}
			else {$vtyporules .= "}".PHP_EOL; $vtypographyrules .= $vtyporules;}

		}
	}
}
echo PHP_EOL;

// -------
// Buttons
// -------
// 1.8.5: set woocommerce button selectors
$woocommercebuttons = array('.woocommerce a.alt.button','.woocommerce button.alt.button','.woocommerce input.alt.button',
	'.woocommerce #respond input.alt#submit', '.woocommerce #content input.alt.button',
	'.woocommerce-page a.alt.button','.woocommerce-page button.alt.button','.woocommerce-page input.alt.button',
	'.woocommerce-page #respond input.alt#submit','.woocommerce-page #content input.alt.button');

// 1.5.0: added body prefix to better override skeleton defaults
$vbuttons = "body button, body input[type='reset'], body input[type='submit'], body input[type='button'], body a.button, body .button";
if ( ($vthemesettings['button_bgcolor_bottom'] == '') || ($vthemesettings['button_bgcolor_bottom'] == $vthemesettings['button_bgcolor_top']) ) {
	$vbuttonrules = "	background: ".$vthemesettings['button_bgcolor_top']."; ";
	$vbuttonrules .= "background-color: ".$vthemesettings['button_bgcolor_top'].";".PHP_EOL;
	// $vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
}
else {
	$vtop = $vthemesettings['button_bgcolor_top'];
	$vbottom = $vthemesettings['button_bgcolor_bottom'];
	$vbuttonrules = "	background: ".$vtop."; background-color: ".$vtop.";".PHP_EOL;
	$vbuttonrules .= "	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ".$vtop."), color-stop(100%, ".$vbottom."));".PHP_EOL;
	$vbuttonrules .= "	background: -webkit-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -o-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -ms-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -moz-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: linear-gradient(top bottom, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	-pie-background: linear-gradient(top, ".$vtop.", ".$vbottom.");".PHP_EOL;
	$vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
}
// 1.8.5: added button font rules here directly instead
$vbuttonrules .= '	'.$vbuttonfontrules.PHP_EOL;

// 1.8.5: added woocommerce button selector option
if ( (isset($vthemesettings['woocommercebuttons'])) && ($vthemesettings['woocommercebuttons'] == '1') ) {
	$woocommerceselectors = implode(', ',$woocommercebuttons);
	$vbuttons .= ', '.PHP_EOL.$woocommerceselectors.' ';
}

// add the rules to the button output
$vbuttons .= ' {'.PHP_EOL.$vbuttonrules.'}'.PHP_EOL;

// 1.5.0: add extra button selectors to override later 3rd party rules with !important
$vextrabuttons = trim($vthemesettings['extrabuttonselectors']);
if ($vextrabuttons != '') {
	$vextrabuttonrules = $vextrabuttons.' {'.str_replace(';',' !important;',$vbuttonrules).'}'.PHP_EOL;
	$vbuttons .= $vextrabuttonrules;
}

$vbuttons .= "body button:hover, body input[type='submit']:hover, body input[type='reset']:hover, body input[type='button']:hover, body a.button:hover, body .button a:hover, body .button:hover";
if ( ($vthemesettings['button_hoverbg_bottom'] == '') || ($vthemesettings['button_hoverbg_bottom'] == $vthemesettings['button_hoverbg_top']) ) {
	$vbuttonrules = "	background: ".$vthemesettings['button_hoverbg_top']."; ";
	$vbuttonrules .= "background-color: ".$vthemesettings['button_hoverbg_top'].";".PHP_EOL;
	// $vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
}
else {
	$vtop = $vthemesettings['button_hoverbg_top'];
	$vbottom = $vthemesettings['button_hoverbg_bottom'];
	$vbuttonrules = "	background: ".$vtop."; background-color: ".$vtop.";".PHP_EOL;
	$vbuttonrules .= "	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ".$vtop."), color-stop(100%, ".$vbottom."));".PHP_EOL;
	$vbuttonrules .= "	background: -webkit-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -o-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -ms-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: -moz-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	background: linear-gradient(top bottom, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
	$vbuttonrules .= "	-pie-background: linear-gradient(top, ".$vtop.", ".$vbottom.");".PHP_EOL;
	if ($vthemesettings['button_font_hover'] !='') {$vbuttonrules .= "	color:".$vthemesettings['button_font_hover'].";".PHP_EOL;}
	$vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
}

// 1.8.5: added woocommerce hover button selector option
if ( (isset($vthemesettings['woocommercebuttons'])) && ($vthemesettings['woocommercebuttons'] == '1') ) {
	$woohoverbuttons = array();
	foreach ($woocommercebuttons as $woocommercebutton) {$woohoverbuttons[] = $woocommercebutton.':hover';}
	$woohoverselectors = implode(', ',$woohoverbuttons);
	$vbuttons .= ', '.PHP_EOL.$woohoverselectors.' ';
}

// add the hover rules to the button output
$vbuttons .= ' {'.PHP_EOL.$vbuttonrules."}".PHP_EOL;

// 1.5.0: add extra button selectors suffixed with :hover and !important
if ($vextrabuttons != '') {
	if (strstr($vextrabuttons,',')) {
		$vextrabuttonarray = explode(',',$vextrabuttons);
		$vextrabuttonrules = '';
		foreach ($vextrabuttonarray as $vextrabutton) {
			if ($vextrabuttonrules != '') {$vextrabuttonrules .= ', ';}
			$vextrabuttonrules .= 'body '.trim($vextrabutton).':hover';
		}
		$vextrabuttonrules .=  '{'.str_replace(';',' !important;',$vbuttonrules).'}'.PHP_EOL;
	}
	else {
		$vextrabuttonrules = 'body '.$vextrabuttons.':hover {'.str_replace(';',' !important;',$vbuttonrules).'}'.PHP_EOL;
	}
	$vbuttons .= $vextrabuttonrules;
}

// ------
// Inputs
// ------
// 1.8.5: added input styling options (available here for login form)
// 1.9.6: added missing number input type, select option and optgroup
if ( (isset($vthemesettings['inputcolor'])) || (isset($themeoptions['inputbgcolor'])) ) {
	$vinputs = "body input[type='text'], body input[type='checkbox'], body input[type='password'], input[type='number'], body select, body select option, body select optgroup, body textarea {";
	if ($vthemesettings['inputcolor'] != '') {$vinputs .= "color: ".$vthemesettings['inputcolor']."; ";}
	if ($vthemesettings['inputbgcolor'] != '') {$vinputs .= "background-color: ".$vthemesettings['inputbgcolor'].";";}
	$vinputs .= "}".PHP_EOL.PHP_EOL;
}

// --------------------------
// Link Colors and Underlines
// --------------------------
if ( ($vthemesettings['alinkunderline'] != 'inherit') || ($vthemesettings['link_color'] != '') ) {
	$vlinks = "body a, body a:visited {";
	if ($vthemesettings['link_color'] != '') {$vlinks .= "color:".$vthemesettings['link_color'].";";}
	if ($vthemesettings['alinkunderline'] != 'inherit') {$vlinks .= " text-decoration:".$vthemesettings['alinkunderline'].";";}
	$vlinks .= "}".PHP_EOL;
}
if ( ($vthemesettings['alinkhoverunderline'] != 'inherit') || ($vthemesettings['link_color'] != '') ) {
	$vlinks .= "body a:hover, body a:focus, body a:active {";
	if ($vthemesettings['link_hover_color'] != '') {$vlinks .= "color:".$vthemesettings['link_hover_color'].";";}
	if ($vthemesettings['alinkhoverunderline'] != 'inherit') {$vlinks .= " text-decoration:".$vthemesettings['alinkhoverunderline'].";";}
	$vlinks .= "}".PHP_EOL;
}
$vlinks .= PHP_EOL;

// ---------------
// Body Background
// ---------------
// 1.8.5: moved login background image url rule
$vbody = '';
if ($vthemesettings['body_bg_color'] != '') {$vbody .= "background-color: ".$vthemesettings['body_bg_color'].";";}
if ($vthemesettings['background_image'] != '') {$vbody .= " background-image: url('".$vthemesettings['background_image']."');";}
if ($vthemesettings['background_size'] != '') {$vbody .= " background-size: ".$vthemesettings['background_size'].";";}
if ($vthemesettings['background_position'] != '') {$vbody .= " background-position: ".$vthemesettings['background_position'].";";}
if ($vthemesettings['background_repeat'] != '') {$vbody .= " background-repeat: ".$vthemesettings['background_repeat'].";";}
if ($vthemesettings['background_attachment'] != '') {$vbody .= " background-attachment: ".$vthemesettings['background_attachment'].";";}

// ============
// Admin Styles
// ============
// 1.8.5: fix to adminstyles variable default clearing
if (!isset($vadminstyles)) {$vadminstyles = false;}
if ( (isset($_GET['adminstyles'])) && ($_GET['adminstyles'] == 'yes') ) {$vadminstyles = true;}
if ($vadminstyles) {

	echo $vbuttons.PHP_EOL;
	$vadmincss = bioship_skin_css_replace_values($vthemesettings['dynamicadmincss']);
	if (function_exists('apply_filters')) {$vadmincss = apply_filters('skin_dynamic_admin_css',$vadmincss);}
	echo $vadmincss.PHP_EOL;

	// 1.5.0: set Theme Options default icon (as \ is stripped in Admin CSS Theme Options save)
	// ref: https://developer.wordpress.org/resource/dashicons/
	// and: http://calebserna.com/dashicons-cheatsheet/
	$vicon = '\\f115';
	if (function_exists('apply_filters')) {$vicon = apply_filters('admin_adminbar_menu_icon',$vicon);}
	echo '#wp-admin-bar-theme-options .ab-icon:before {content: "'.$vicon.'"; top:2px;}'.PHP_EOL;

	// 1.8.5: added simple hybrid hook style fixes
	if ( (isset($vthemesettings['hybridhook'])) && ($vthemesettings['hybridhook'] == '1') ) {
		echo "
		/* Hybrid Hook display fixes */
		.hook-editor textarea {width: 100%;}
		.hook-editor .alignleft {float: none;}
		".PHP_EOL.PHP_EOL;
	}

	// 1.9.0: admin Widget page sidebar class styles
	echo "/* Widget Sidebars */
	.sidebar-on {background-color:#F0F0FF;} .sidebar-on h2 {font-size: 13pt;}
	.sidebar-off {background-color:#F3F3FF;} .sidebar-off h2 {font-weight: normal; font-size: 10pt;}".PHP_EOL.PHP_EOL;


	// ------------
	// Login Styles
	// ------------
	if ($vloginstyles) {

		// 1.8.5: add input and link styling (since login is 'frontend')
		// 1.9.6: target input styling
		$vinputs = str_replace('body','body.login',$vinputs);
		$vlogin = $vinputs.PHP_EOL.$vlinks.PHP_EOL;

		// Login Background
		// ----------------
		if ($vthemesettings['loginbackgroundurl'] != '') {
			$vlogin .= "body.login {background-image: url('".$vthemesettings['loginbackgroundurl']."');}".PHP_EOL;
		} else {
			// 1.9.6: fix to missing fallback to main background settings
			if ($vbody) {$vlogin .= "body.login {".$vbody."}".PHP_EOL;}
		}

		// Login Wrapper Hack Restyling
		// ----------------------------
		// (thanks to our login body class hack)
		// 1.9.5: added body.login prefix (to not conflict with theme my login)
		$vlogin .= "body.login #loginwrapper {padding-top:8%;}".PHP_EOL;
		$vlogin .= "body.login #loginform, body.login #lostpasswordform, body.login #resetpassform, body.login #registerform {border-radius:20px 20px 0 0;}".PHP_EOL;
		$vlogin .= "body.login #login {padding:0 20px 10px 20px;} body.login #nav {text-align:right;}".PHP_EOL;
		$vlogin .= "body.login #nav, body.login #backtoblog {margin:0 !important; padding:0 !important;}".PHP_EOL;
		$vlogin .= "body.login #nav a, body.login #backtoblog a {line-height: 3em; padding: 20px}".PHP_EOL;
		$vlogin .= "body.login #backtoblog {border-radius: 0 0 20px 20px;}".PHP_EOL;
		$vlogin .= "body.interim-login #loginform {border-radius:20px !important;}".PHP_EOL;

		// Login Wrap Background Colour
		// ----------------------------
		if ( (isset($vthemesettings['loginwrapbgcolor'])) && ($vthemesettings['loginwrapbgcolor'] != '') ) {
			$vlogin .= "body.login #loginform, body.login #lostpasswordform, body.login #nav, body.login #backtoblog {background-color: ".$vthemesettings['loginwrapbgcolor'].";}".PHP_EOL;
		}

		// 1.8.5: added missing 'none' setting handler
		if ($vthemesettings['loginlogo'] == 'none') {$vlogin .= 'body.login h1 {display:none;}'.PHP_EOL;}
		elseif ($vthemesettings['loginlogo'] != 'default') {

			// Get the Logo Image Size
			// -----------------------
			$imageurl = false;
			if ( ($vthemesettings['loginlogo'] == 'custom') && ($vthemesettings['header_logo'] != '') ) {
				$imageurl = $vthemesettings['header_logo'];
			}
			elseif ( ($vthemesettings['loginlogo'] == 'upload') && ($vthemesettings['loginlogourl'] != '') ) {
				$imageurl = $vthemesettings['loginlogourl'];
			}

			if ($imageurl) {

				$vcachekey = 'login_logo';

				// 1.8.5: allow for login logo size refresh via querystring
				// (can be used to recheck image size - if you overwrite the image but keep same URL)
				if ( (isset($_GET['loginlogo_image_size'])) && ($_GET['loginlogo_image_size'] == 'refresh') ) {delete_option($vthemename.'_'.$vcachekey);}

				$imagesize = bioship_skin_get_image_size($imageurl,$vcachekey);

				// Output the Login Logo Style
				// ---------------------------
				if ($imagesize) {
					$width = $imagesize['0']; $height = $imagesize['1'];
					$vlogin .= 'body.login h1 a {'.PHP_EOL;
					$vlogin .= ' background-image: url("';
					// 1.8.5: fix to old header_image usage
					if ($vthemesettings['loginlogo'] == 'custom') {$vlogin .= $vthemesettings['header_logo'];}
					elseif ($vthemesettings['loginlogo'] == 'upload') {$vlogin .= $vthemesettings['loginlogourl'];}
					$vlogin .= ' ") !important;'.PHP_EOL;
					$vlogin .= ' background-repeat: no-repeat !important;'.PHP_EOL;
					// if (!$imagesize) {$vlogin .= ' width:100%; height:auto; background-size:100% auto;';}
					// else {
						$vlogin .= ' width: '.$width.'px !important;'.PHP_EOL;
						$vlogin .= ' height: '.$height.'px !important;'.PHP_EOL;
						$vlogin .= ' background-size: '.$width.'px '.$height.'px !important;'.PHP_EOL;
					// }
					// if (!$imagesize) {$vlogin .= ' margin-left: -50%;';}
					// else {
						if ($width > 320) {$vlogin .= '  margin-left: -'.round(($width - 320)/2).'px;';}
					// }
					$vlogin .= PHP_EOL.'}'.PHP_EOL;
				}
			}
		}

		echo $vlogin; // output login styles
	}

	// 1.9.6: use return not exit here
	return; // finish here for admin styling output
}

// ---------------
// Body Background
// ---------------
if ($vbody != '') {echo "body {".$vbody."}".PHP_EOL;}
echo PHP_EOL;

// --------------
// Wrap Container
// --------------
// 1.8.5: added maximum layout width wrapper rule
if (THEMEDEBUG) {echo "/* Raw Max Width: ".$vthemesettings['layout']."px */".PHP_EOL;}
$vmaximumwidth = round( (abs(intval($vthemesettings['layout'])) / 16), 3, PHP_ROUND_HALF_DOWN);
if (THEMEDEBUG) {echo "/* Sanitized Max Width: ".$vmaximumwidth."em */".PHP_EOL;}
if ($vmaximumwidth > 320) {echo "#wrap {max-width: ".$vmaximumwidth."em;}".PHP_EOL;}

// -----------------
// Header Background
// -----------------
$vheader = '';
if ($vthemesettings['headerbgcolor'] != '') {$vheader .= "background-color: ".$vthemesettings['headerbgcolor'].";";}
if ($vthemesettings['header_background_image'] != '') {
	$vheader .= " background-image: url('".$vthemesettings['header_background_image']."');";

	$vcachekey = 'header_image';

	// 1.8.5: allow for header image background size refresh via querystring
	// (can be used to recheck image size - if you overwrite the image but keep same URL)
	if ( (isset($_GET['header_image_size'])) && ($_GET['header_image_size'] == 'refresh') ) {delete_option($vthemename.'_'.$vcachekey);}

	// 1.8.5: improved image size handling function
	$vimagesize = bioship_skin_get_image_size($vthemesettings['header_background_image'],$vcachekey);
	if (!$vimagesize) {
		// hmmmm... no allow_url_fopen and no filepath found (and all our lovely automated code has failed)
		// if this is happening to you, you may need to set an explicit filter for this in your filters.php
		// TODO: rethink this as filters maybe not loaded?
		$vfilteredsize = apply_filters('muscle_skin_header_size',array());

		if ( (isset($vfilteredsize[0])) && (isset($vfilteredsize[1])) ) {
			$vimagesize[0] = intval($vimagesize[0]); $vimagesize[1] = intval($vimagesize[1]);
		}
	}

	if ($vimagesize) {
		// 1.8.5: account for maximum layout width and maybe scale to it
		if ($vimagesize[0] > $vthemesettings['layout']) {
			$vratio = $vimagesize[1] / $vimagesize[0];
			$vimagesize[1] = $vthemesettings['layout'] * $vratio;
			$vimagesize[0] = $vthemesettings['layout'];
		}
		// 1.8.5: set header width and height in em
		// 1.9.5: only set the height in em if not using repeat or repeat-y
		if ( ($vthemesettings['header_background_repeat'] != 'repeat') && ($vthemesettings['header_background_repeat'] != 'repeat-y') ) {
			$vfontpercent = 100;
			$vheight = round( ($vimagesize[1] / 16 * $vfontpercent / 100), 3, PHP_ROUND_HALF_DOWN);
			$vheader .= " height: ".$vheight."em;";
		}
	}
}
if ($vthemesettings['header_background_size'] != '') {$vheader .= " background-size: ".$vthemesettings['header_background_size'].";";}
if ($vthemesettings['header_background_position'] != '') {$vheader .= " background-position: ".$vthemesettings['header_background_position'].";";}
if ($vthemesettings['header_background_repeat'] != '') {$vheader .= " background-repeat: ".$vthemesettings['header_background_repeat'].";";}
// if ($vthemesettings['header_background_attachment'] != '') {$vheader .= " background-attachment: ".$vthemesettings['header_background_attachment'].";";}
// 1.8.5: fix to check typo here
if ($vheader != '') {echo "#header {".$vheader."}".PHP_EOL;}
echo PHP_EOL;

// -------------------
// Header Text Display
// -------------------
// 1.8.5: add header text display rules
// echo '/*'; print_r($vthemesettings['header_texts']); echo '*/';
if ( (isset($vthemesettings['header_texts']['sitetitle'])) && ($vthemesettings['header_texts']['sitetitle'] != '1') ) {
	echo '#header h1#site-title-text a {display:none;}'.PHP_EOL;
}
if ( (isset($vthemesettings['header_texts']['sitedescription'])) && ($vthemesettings['header_texts']['sitedescription'] != '1') ) {
	echo '#header #site-description .site-desc {display: none;}'.PHP_EOL;
}
echo PHP_EOL;

// ------------------
// Background Colours
// ------------------

$vbgcolors = array('wrap','content','sidebar','subsidebar','footer');
foreach ($vbgcolors as $vbgcolor) {
	$vbgcolorref = $vbgcolor.'bgcolor';
	if ($vthemesettings[$vbgcolorref] != '') {
		echo "#".$vbgcolor." {background-color: ".$vthemesettings[$vbgcolorref].";}".PHP_EOL;
	}
}
echo PHP_EOL;

// --------------------
// Main Navigation Menu
// --------------------

$vnavmenurules = '';

// 1.8.5: added autospacing of top level menu items option
if ( (isset($vthemesettings['navmenuautospace'])) && ($vthemesettings['navmenuautospace'] == '1') ) {
	// 1.9.5: fix to dashes in theme name for theme mods
	$vthememods = get_option('theme_mods_'.str_replace('_','-',$vthemename));
	if ( (isset($vthememods['nav_menu_locations']['primary'])) && ($vthememods['nav_menu_locations']['primary'] != '') ) {
		// note: this is set in skull.php with superfish.js check
		$vmenumainitems = get_option($vthemename.'_menumainitems');
		if ( ($vmenumainitems != '') && ($vmenumainitems > 0) ) {
			$vitempercent = round( (99 / $vmenumainitems), 3, PHP_ROUND_HALF_DOWN);
			$vnavmenurules .= "#navigation #mainmenu ul li {width: ".$vitempercent."%; min-width:6em;}".PHP_EOL;
			$vnavmenurules .= "#navigation #mainmenu ul ul li {width: 100%;}".PHP_EOL;
		}
	}
}

// 1.8.5: added navigation container, submenu container and item background color options
if ( (isset($vthemesettings['navmenubgcolor'])) && ($vthemesettings['navmenubgcolor'] != '') ) {
	$vnavmenurules .= "#navigation, #navigation #mainmenu, #navigation #mainmenu ul {background-color: ".$vthemesettings['navmenubgcolor'].";}".PHP_EOL;
}
if ( (isset($vthemesettings['navmenusubbgcolor'])) && ($vthemesettings['navmenusubbgcolor'] != '') ) {
	$vnavmenurules .= "#navigation #mainmenu ul ul, #navigation #mainmenu ul ul li {background-color: ".$vthemesettings['navmenusubbgcolor'].";}".PHP_EOL;
}
if ( (isset($vthemesettings['navmenuitembgcolor'])) && ($vthemesettings['navmenuitembgcolor'] != '') ) {
	$vnavmenurules .= "#navigation #mainmenu ul li, #navigation #mainmenu ul li.active li {background-color: ".$vthemesettings['navmenuitembgcolor'].";}".PHP_EOL;
}

// 1.8.5: added active and hover item options
// 1.9.5: changed isset tests to maybe not output
$vnavmenuactiverules = '';
if ( (isset($vthemesettings['navmenuactivecolor'])) && ($vthemesettings['navmenuactivecolor'] != '') ) {
	$vnavmenuactiverules .= "color: ".$vthemesettings['navmenuactivecolor']."; ";
}
if ( (isset($vthemesettings['navmenuactivebgcolor'])) && ($vthemesettings['navmenuactivebgcolor'] != '') ) {
	$vnavmenuactiverules .= "background-color: ".$vthemesettings['navmenuactivebgcolor'].";";
}
if ($vnavmenuactiverules != '') {$vnavmenurules .= "#navigation #mainmenu ul li.active {".$vnavmenuactiverules."}".PHP_EOL;}

$vnavmenuhoverrules = '';
if ( (isset($vthemesettings['navmenuhovercolor'])) && ($vthemesettings['navmenuhovercolor'] != '') ) {
	$vnavmenuhoverrules .= "color: ".$vthemesettings['navmenuhovercolor']."; ";
}
if ( (isset($vthemesettings['navmenuhoverbgcolor'])) && ($vthemesettings['navmenuhoverbgcolor'] != '') ) {
	$vnavmenuhoverrules .= "background-color: ".$vthemesettings['navmenuhoverbgcolor'].";";
}
if ($vnavmenuhoverrules != '') {$vnavmenurules .= "#navigation #mainmenu ul li:hover {".$vnavmenuhoverrules."}".PHP_EOL;}

if ($vnavmenurules != '') {echo $vnavmenurules.PHP_EOL;}

// -------------------------------------
// Buttons, Links, Inputs and Typography
// -------------------------------------
// (as already defined above)
echo $vlinks.PHP_EOL;
echo $vinputs.PHP_EOL;
echo $vbuttons.PHP_EOL;
echo $vtypographyrules.PHP_EOL;

// ---------------
// Content Padding
// ---------------
if ($vthemesettings['contentpadding'] != '') {
	echo "#contentpadding {padding: ".$vthemesettings['contentpadding'].";}".PHP_EOL;
}
echo PHP_EOL;

// ----------------------------------
// Browser and Mobile Specific Styles
// ----------------------------------
// (alternative to deprecated <body> class function in muscle.php)
// - as that method does not account for the page being cached

// special case: if using shortinit method, maybe load PHP browser detection plugin
if ( (defined('SHORTINIT')) && (!function_exists('php_browser_info')) ) {
	$vplugin = 'php-browser-detection/php-browser-detection.php';
	// 1.8.5: fix for undefined is_plugin_active function
	$vactiveplugins = get_option('active_plugins');
	$vpluginpath = WP_CONTENT_DIR.'/plugins/'.$vplugin;
	if ( (file_exists($vpluginpath)) && (in_array($vplugin,$vactiveplugins)) ) {
		include($vpluginpath);
	}
}

if (!function_exists('php_browser_info')) {
	// use in-built wp functions
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	// $vbrowser = wp check browser version();
	$vclass = array();
	if ($is_lynx) {$vclasses[] = 'lynx';}
	elseif ($is_gecko) {$vclasses[] = 'firefox';}
	elseif ($is_opera) {$vclasses[] = 'opera';}
	elseif ($is_NS4) {$vclasses[] = 'netscape';}
	elseif ($is_safari) {$vclasses[] = 'safari';}
	elseif ($is_chrome) {$vclasses[] = 'chrome';}
	elseif ($is_IE) {$vclasses[] = 'ie';}
	else {$vclasses[] = 'unknown';}
	// 'iPhone Safari'
	if ($is_iphone) {$vclasses[] = 'iphone';}
}
else {
	// use PHP Browser Detection plugin functions
	$vbrowserinfo = php_browser_info();
	$vbrowser = $vbrowser['Browser'];
	if ($vbrowser == "Netscape") {$vclasses[] = 'netscape';}
	if ($vbrowser == "Lynx") {$vclasses[] = 'Lynx';}
	if ($vbrowser == "Firefox") {$vclasses[] = 'firefox';}
	if ($vbrowser == "Safari") {$vclasses[] = 'safari';}
	if ($vbrowser == "Chrome") {$vclasses[] = 'chrome';}
	if ($vbrowser == "Opera") {$vclasses[] = 'opera';}
	if ($vbrowser == "IE") {$vclasses[] = 'ie';}

	// add mobile browser styles to class list
	if (is_tablet()) {$vclasses[] = 'tablet';}
	elseif (is_mobile()) {
		$vclasses[] = 'mobile';
		if (is_iphone()) {$vclasses[] = 'iphone';}
		elseif (is_ipad()) {$vclasses[] = 'ipad';}
		elseif (is_ipod()) {$vclasses[] = 'ipod';}
	} elseif (is_desktop()) {$vclasses[] = 'desktop';}
}

if (count($vclasses) > 0) {
	$vbrowserstyles = '';
	foreach ($vclasses as $vclass) {
		$vthisbrowserstyles = '';
		if (isset($vthemesettings['browser_'.$vclass])) {$vthisbrowserstyles = $vthemesettings['browser_'.$vclass];}
		if (has_filter('muscle_browser_styles_'.$vclass)) {
			// TODO: rethink this as filters maybe not loaded?
			$vbrowserstyles = apply_filters('muscle_browser_styles_custom'.$vclass,$vthisbrowserstyles);
		}
	}
}

if ($vbrowserstyles != '') {
	echo PHP_EOL."/* Styles for Detected Browser/Device  */".PHP_EOL.PHP_EOL;
	echo $vbrowserstyles.PHP_EOL;
}

// ------------------
// Custom Dynamic CSS
// ------------------
// 1.8.5: no output here for customizer live preview (transport refresh)
// as we load the preview CSS styles dynamically and separately...
$vlivepreview = false;
if ( (isset($_REQUEST['livepreview'])) && ($_REQUEST['livepreview'] == 'yes') ) {$vlivepreview = true;}
if (!$vlivepreview) {
	$vcustomcss = $vthemesettings['dynamiccustomcss'];
	$vcustomcss = bioship_skin_css_replace_values($vcustomcss);
	if (function_exists('apply_filters')) {$vcustomcss = apply_filters('skin_dynamic_css',$vcustomcss);}

	if ($vcustomcss != '') {
		echo PHP_EOL."/* Dynamic CSS */".PHP_EOL.PHP_EOL;
		// 1.8.0: added stripslashes to fix single quotes for Titan
		echo stripslashes($vcustomcss).PHP_EOL;
	}
}

// ----------------
// Output Load Time
// ----------------
$vendtime = microtime(true);
$vdifference = $vendtime - $vthemetimestart;
echo PHP_EOL."/* Load Time: ".$vdifference." */".PHP_EOL;

exit;

?>