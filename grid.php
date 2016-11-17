<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * ====== Dynamic Grid Style Loader ======
 * - Creates Grid CSS from Theme Options -
 *
**/

// Output CSS Header
// -----------------
header("Content-type: text/css; charset: UTF-8");

// ------------------------------
// Direct Load Memory Saving Mode
// ------------------------------
// ...same as skin.php but maybe does not need as much...

if (strstr($_SERVER['REQUEST_URI'],'grid.php')) {

	$vthemestarttime = microtime(true);

	// Use our friend Shorty...
	define('SHORTINIT', true);
	$wp_root_path = grid_find_require('wp-load.php');
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
	if (!defined('THEMETRACE')) {define('THEMETRACE',false);}
	if (!defined('DIRSEP')) {define('DIRSEP',DIRECTORY_SEPARATOR);}

	// Include files required for initialization.
	// 1.8.0: use DIRECTORY_SEPARATOR constant
	// 1.9.8: use short DIRSEP constant

	include(ABSPATH.WPINC.DIRSEP.'version.php');
	include(ABSPATH.WPINC.DIRSEP.'general-template.php');
	include(ABSPATH.WPINC.DIRSEP.'link-template.php');
	$restapi = ABSPATH.WPINC.DIRSEP.'rest-api.php';
	if (file_exists($restapi)) {include($restapi);} // 1.6.0

	// theme class and dependencies
	include(ABSPATH.WPINC.DIRSEP.'kses.php');
	include(ABSPATH.WPINC.DIRSEP.'shortcodes.php');
	include(ABSPATH.WPINC.DIRSEP.'formatting.php');
	include(ABSPATH.WPINC.DIRSEP.'class-wp-theme.php');
	include(ABSPATH.WPINC.DIRSEP.'theme.php');

	// current_user_can (capabilities.php, pluggable.php, user.php, post.php)
	include(ABSPATH.WPINC.DIRSEP.'capabilities.php');
	include(ABSPATH.WPINC.DIRSEP.'pluggable.php');
	include(ABSPATH.WPINC.DIRSEP.'user.php');
	$userclass = ABSPATH.WPINC.DIRSEP.'class-wp-user.php';
	if (file_exists($userclass)) {include($userclass);} // 1.6.0
	include(ABSPATH.WPINC.DIRSEP.'post.php');

	// Theme functions.php : skeleton_themedrive_determine_theme (copied)
	// Theme functions.php : skeleton_file_hierarchy (replaced)

}

// ================
// Helper Functions
// ================

// find and require for blog loader
function grid_find_require($file,$folder=null) {
	if ($folder === null) {$folder = dirname(__FILE__);}
	$path = $folder.'/'.$file;
	if (file_exists($path)) {require($path); return $folder;}
	else {
		$upfolder = grid_find_require($file,dirname($folder));
		if ($upfolder != '') {return $upfolder;}
	}
}

// copy of skeleton_themedrive_determine_theme function
if (!function_exists('skeleton_themedrive_determine_theme')) {
	function skeleton_themedrive_determine_theme() {

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

		// TESTME? maybe use get_template($vtdtheme) here ?
		$vthemedata = wp_get_theme($vtdtheme);

		if (!empty($vthemedata)) {return $vthemedata;}

		$vallthemes = wp_get_themes();
		foreach ($vallthemes as $vthemedata) {
			if ($vthemedata['Stylesheet'] == $vtdtheme) {return $vthemedata;}
		}

		return false;
	}
}



// get Current Theme
// -----------------
$vtheme = wp_get_theme();

// Theme Test Drive Compatibility
// ------------------------------
$vthemetestdrive = skeleton_themedrive_determine_theme();
if ($vthemetestdrive) {$vtheme = $vthemetestdrive;}

// get Theme Options
// -----------------
$vthemedisplayname = $vtheme['Name'];
$vthemename = preg_replace("/\W/","-",strtolower($vthemedisplayname));

// 1.8.0: get Options Framework or Titan Framework Options
// 1.8.5: use options framework option switch check instead
$vthemeframework = get_option($vthemename.'_framework');
if ($vthemeframework == 'options') {$voptionsload = true;} else {$voptionsload = false;}

if ($voptionsload) {
  	// for Options Framework only
	$vthemename = preg_replace("/\W/","_",strtolower($vthemedisplayname));
	$vthemesettings = get_option($vthemename);
}
if ( (!$voptionsload) || (!$vthemesettings) ) {
	// for Titan Framework or default
	$vthemename = preg_replace("/\W/","-",strtolower($vthemedisplayname));
	$vthemesettings = maybe_unserialize(get_option($vthemename.'_options'));
}

// set Debug Option
// ----------------
if (!defined('THEMEDEBUG')) {
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

// include Skeleton and Skull Functions
// ------------------------------------
// 1.9.8: added these passthrough functions
if (!function_exists('skeleton_apply_filters')) {
	function skeleton_apply_filters($vfilter,$vvalue) {return apply_filters($vfilter,$vvalue);}
}
$vthemesettings = array(); // prevents undefined index warnings here
if (!function_exists('skeleton_word_to_number')) {include(dirname(__FILE__).DIRSEP.'skeleton.php');}
// 1.9.5: include skull.php (moved functions)
// for skeleton_get_content_width, skeleton_get_content_padding_width
if (!function_exists('skeleton_get_content_width')) {include(dirname(__FILE__).DIRSEP.'skull.php');}

// ===============
// set Grid Values
// ===============
// 1.8.5: fix for global scope for admin ajax load method
// 1.9.5: added separate content columns value
global $totalcolumns, $contentcolumns, $contentpadding, $empixels, $maxwidth, $contentwidth, $spacing, $gridcompat;

// set EM Pixels
// -------------
// note: we are using em values over px or percentages
// ref: http://blog.cloudfour.com/the-ems-have-it-proportional-media-queries-ftw/
// $empixels = 16; // so 1em ~= 16px ...recommend to not change this!
// use of a different percentage value probably would be okay though,
// as we are calculating everything dynamically anyway...
$fontpercent = 100;
if (isset($_REQUEST['fontpercent'])) {
	// TODO: test this font percentage override
	$fontpercentage = abs(intval($_REQUEST['fontpercent']));
	if ( (is_numeric($fontpercentage)) && ($fontpercentage < 101) ) {$fontpercent = $fontpercentage;}
}
$empixels = round( (16 * ($fontpercent / 100)), 3, PHP_ROUND_HALF_DOWN);

// set Layout Grid Columns
// -----------------------
if (isset($_REQUEST['gridcolumns'])) {$gridcolumns = $_REQUEST['gridcolumns'];}
else {$gridcolumns = $vthemesettings['gridcolumns'];}
// echo "/* GRID COLUMNS: ".$gridcolumns." */"; // debug point

// set Numeric Total Columns
// -------------------------
// 12, 16, 20 or 24
$totalcolumns = skeleton_word_to_number($gridcolumns);
if (!$totalcolumns) {$totalcolumns = 16;}
// echo "/* TOTAL COLUMNS: ".$totalcolumns." */"; // debug point

// set Content Grid Columns
// ------------------------
if (isset($_REQUEST['contentgridcolumns'])) {$contentgridcolumns = $_REQUEST['contentgridcolumns'];}
else {$contentgridcolumns = $vthemesettings['contentgridcolumns'];}
// echo "/* LAYOUT GRID COLUMNS: ".$gridcolumns." */"; // debug point

// set Numeric Total Columns
// -------------------------
// 12, 16, 20 or 24
$contentcolumns = skeleton_word_to_number($contentgridcolumns);
if (!$contentcolumns) {$contentcolumns = 24;}
// echo "/* CONTENT GRID COLUMNS: ".$contentcolumns." */"; // debug point

// set Maximum Layout Width
// ------------------------
if ( (isset($_REQUEST['maxwidth'])) && ($_REQUEST['maxwidth'] != '') ) {$maxwidth = abs(intval($_REQUEST['maxwidth']));}
else {$maxwidth = $vthemesettings['layout'];} // fallback to unfiltered option

if ($maxwidth == '') {$vmaxwidth = '960';} // 960 is default
// $maxwidth = apply_filters('skeleton_layout_width',$maxwidth); // off as filters not loaded
// echo "/* MAXWIDTH: ".$maxwidth." */"; // debug point

// 1.9.6: set some theme layout variables for fallbacks
global $vthemelayout;
$vthemelayout['maxwidth'] = $maxwidth;
$vthemelayout['gridcolumns'] = $gridcolumns;

// set Content Width
// -----------------
// note: uses raw value (content padding not yet removed)
if (isset($_REQUEST['contentwidth'])) {
	$contentwidth = $_REQUEST['contentwidth'];
	if ($contentwidth == '') {
		ob_start(); $contentwidth = skeleton_get_content_width(); ob_end_clean();
	}
} else {
	// note: not a good fallback method because page context is lost for filters,
	// and content padding width has already been removed from this value
	ob_start(); $contentwidth = skeleton_get_content_width(); ob_end_clean();
}
// echo  "/* CONTENT WIDTH: ".$contentwidth." */";

// set Content Padding Width
// -------------------------
// note: uses raw value to pass to skeleton_get_padding_width
if (isset($_REQUEST['contentpadding'])) {
	$contentpadding = $_REQUEST['contentpadding'];
	if ($contentpadding == '') {$contentpadding = 0;}
} else {$contentpadding = $vthemesettings['contentpadding'];} // unfiltered fallback
// echo "/* CONTENT PADDING: ".$contentpadding." */";

// Column Spacing
// --------------
// note: it is actually padding acting as an internal margin now
// 1.8.5: made array and added content margins
// 1.9.5: changed variable name from margins to spacing
$spacing['left'] = 16; $spacing['right'] = 16;
if (isset($_REQUEST['gridspacing'])) {
	$gridspacing = abs(intval($_REQUEST['gridspacing']));
	if (is_numeric($gridspacing)) {$spacing['left'] = $gridspacing; $spacing['right'] = $gridspacing;}
}
// note 12px = ~0.75em as 1em ~= 16px
$spacing['leftcontent'] = 12; $spacing['rightcontent'] = 12;
if (isset($_REQUEST['contentspacing'])) {
	$contentspacing = abs(intval($_REQUEST['contentspacing']));
	if (is_numeric($contentspacing)) {$spacing['leftcontent'] = $contentspacing; $spacing['rightcontent'] = $contentspacing;}
}

// Grid Compatibility Classes
// --------------------------
// 1.9.5: added maybe unserialize and convert cross-framework options for multicheck
if (isset($vthemesettings['gridcompatibility'])) {
	$gridcompatibility = maybe_unserialize($vthemesettings['gridcompatibility']);
} // not filtered
global $gridcompat; $gridcompat = array('960gridsystem'=>'','blueprint'=>'');
if ( (isset($gridcompatibility)) && (is_array($gridcompatibility)) ) {
	if ( (isset($gridcompatibility['960gridsystem'])) && ($gridcompatibility['960gridsystem'] == '1') ) {$gridcompat['960gridsystem'] = '1';}
	if ( (isset($gridcompatibility['blueprint'])) && ($gridcompatibility['blueprint'] == '1') ) {$gridcompat['blueprint'] = '1';}
	if (in_array('960gridsystem',$gridcompatibility)) {$gridcompat['960gridsystem'] = '1';}
	if (in_array('blueprint',$gridcompatibility)) {$gridcompat['blueprint'] = '1';}
}
// echo "/* GRID COMPAT: "; print_r($gridcompat); echo " */";

// maybe buffer output
// -------------------
// to get CSS output length - just for curiosity really
$buffer = false;
if (isset($_REQUEST['buffer'])) {
	if ( ($_REQUEST['buffer'] == 'yes') || ($_REQUEST['buffer'] == '1') ) {
		ob_start(); $buffer = true;
	}
}

// START CSS OUTPUT
// ----------------

?>

/* ------------------- */
/* BioShip Grid System */
/* ------------------- */

/* <?php echo $totalcolumns; ?> Column Layout Grid, <?php echo $contentcolumns; ?> Column Content Grid */

/* Set the default font size to 100% so grid 1em = ~16px */
html, body {font-size: <?php echo $fontpercent; ?>%;}

/* Column Sizing Em Fix */
.column, .columns, #content .column, #content .columns {font-size:initial; float:left; display:inline;}

/* Skeleton Boilerplate Common Rules */
.container, .container_24, .container_20, .container_16, .container_12 {position:relative; margin:0 auto; padding:0;}
.first {margin-left:0 !important;} .first .inner {padding-left:0 !important;}
.last {margin-right:0 !important;} .last .inner {padding-left:0 !important;}
.alpha, .column.alpha, .columns.alpha {margin-left:0;}
.column.alpha .inner, .columns.alpha .inner, .span1.alpha .inner, .span2.alpha .inner, .span3.alpha .inner, .span4.alpha .inner,
.span5.alpha .inner, .span6.alpha .inner, .span7.alpha .inner, .span8.alpha .inner, .span9.alpha .inner, .span10.alpha .inner,
.span11.alpha .inner, .span12.alpha .inner, .span13.alpha .inner, .span14.alpha .inner, .span15.alpha .inner, .span16.alpha .inner,
.span17.alpha .inner, .span18.alpha .inner, .span19.alpha .inner, .span20.alpha .inner, .span21.alpha .inner, .span22.alpha .inner,
.span23.alpha .inner, .span24.alpha .inner {padding-left:0;}
.omega, .column.omega, .columns.omega {margin-right:0;}
.column.omega .inner, .columns.omega .inner, .span1.omega .inner, .span2.omega .inner, .span3.omega .inner, .span4.omega .inner,
.span5.omega .inner, .span6.omega .inner, .span7.omega .inner, .span8.omega .inner, .span9.omega .inner, .span10.omega .inner,
.span11.omega .inner, .span12.omega .inner, .span13.omega .inner, .span14.omega .inner, .span15.omega .inner, .span16.omega .inner,
.span17.omega .inner, .span18.omega .inner, .span19.omega .inner, .span20.omega .inner, .span21.omega .inner, .span22.omega .inner,
.span23.omega .inner, .span24.omega .inner {padding-right:0;}

/* Fraction Percentage Widths */
.one_half, .one_halve, .one_third, .two_thirds, .one_fourth, .three_fourths, .one_quarter, .two_quarters, .three_quarters,
.one_fifth, .two_fifth, .two_fifths, .three_fifth, .three_fifths, .four_fifth, .four_fifths, .one_sixth, .two_sixth, .two_sixths,
.three_sixth, .three_sixths, .four_sixth, .four_sixths, .five_sixth, .five_sixths {position:relative; float:left;}
.one_half, .two_quarters, .three_sixth, .three_sixths {width:49.5%}
.one_third, .two_sixth, .two_sixths {width:32.5%} .two_thirds, .four_sixth, .four_sixths {width:65.5%}
.one_fourth, .one_quarter {width:24.5%} .three_fourths, .three_quarters {width:74.5%}
.one_fifth {width:19.5%} .two_fifth, .two_fifths {width:39.5%} .three_fifth, .three_fifths {width:59.5%} .four_fifth, .four_fifths {width:79.5%}
.one_sixth {width:16%} .five_sixth, .five_sixths {width:83%}

/* Clear and Clearfix */
// 1.9.8: fix to remove overflow:hidden from clears (causing display height to actually exist?)
.container:after {content:"\0020"; display:block; height:0; clear:both; visibility:hidden;}
.clearfix:before, .clearfix:after {content:"\0020"; display:block; visibility:hidden; width:0; height:0; font-size: 0; line-height: 0;}
.clear {clear:both; display:block; visibility:hidden; width:0; height:0;}
.clearfix:after, .u-cf {clear:both;} .clearfix {zoom:1;}

<?php

	// Full Width Container Override
	// -----------------------------
	if ( (isset($_GET['fullwidth'])) && ($_GET['fullwidth'] == 'yes') ) {
		echo "/* Full Width Override */";
		echo PHP_EOL.'#wrap.container {width: 100% !important;}'.PHP_EOL.PHP_EOL;
	}

	// 960 Grid System Common Rules
	// ----------------------------
	// 1.9.5: set for content grid only and optimized
	if ($gridcompat['960gridsystem'] == '1') {

		echo PHP_EOL."/* 960 Grid System */".PHP_EOL.PHP_EOL;

		$gridrules = ''; $pushrules = ''; $pullrules = '';
		for ($i = 1; $i < ($contentcolumns+1); $i++) {
			if ($gridrules != '') {$gridrules .= ', ';}
			if ($pushrules != '') {$pushrules .= ', ';}
			if ($pullrules != '') {$pullrules .= ', ';}
			if ( ($i == 8) || ($i == 16) ) {$gridrules .= PHP_EOL; $pushrules .= PHP_EOL; $pullrules .= PHP_EOL;}
			$gridrules .= '.grid_'.$i; $pushrules .= '.push_'.$i; $pullrules .= '.pull_'.$i;
		}
		echo $gridrules;
		echo ' {float:left; display:inline; margin-left:0; margin-right:0;}'.PHP_EOL;
		echo PHP_EOL.$pushrules.','.PHP_EOL.$pullrules.' {position:relative;}'.PHP_EOL;
	}


	// Percentage Content Columns
	// --------------------------
	// 1.9.5: percentages prototype
	echo '/* Content Column Grid */'.PHP_EOL;
	echo '#content .container_24, #content .container_20, #content .container_16, #content .container_12 {width: 100%;}'.PHP_EOL;
	echo '#content .container_24:after, #content .container_20:after, #content .container_16:after, #content .container_12:after {clear: both;}'.PHP_EOL;
	// echo '#content .container_'.$contentcolumns.':before, .container_'.$contentcolumns.':after
	// echo " {content: "."; display: block; overflow: hidden; visibility: hidden;  width: 0; height: 0; font-size: 0; line-height: 0;}'.PHP_EOL;

	// no need to set anything for inner columns here?
	// #content .column .inner, #content .columns .inner {}

	$rules = array('content'=>'','padleft'=>'','padright'=>'','shiftleft'=>'','shiftright'=>'');
	$rules = skeleton_content_grid_generate_rules($rules,24);
	$rules = skeleton_content_grid_generate_rules($rules,20);
	$rules = skeleton_content_grid_generate_rules($rules,16);
	$rules = skeleton_content_grid_generate_rules($rules,12);

	echo $rules['content'].PHP_EOL;
	echo $rules['padleft'].PHP_EOL;
	echo $rules['padright'].PHP_EOL;
	echo $rules['shiftleft'].PHP_EOL;
	echo $rules['shiftright'].PHP_EOL;

	// Generate Content Grid Rules
	// ---------------------------
	// 1.9.5: new content grid
	function skeleton_content_grid_generate_rules($rules, $columns) {

		global $gridcompat, $contentcolumns; $c = $columns;
		$contentrules = ''; $padleftrules = ''; $padrightrules = ''; $shiftleftrules = ''; $shiftrightrules = '';

		// 24 columns container
		for ($i = 1; $i < ($c+1); $i++) {
			$word = skeleton_number_to_word($i);
			$percent = round( (99 * ($i / $c)), 3, PHP_ROUND_HALF_DOWN);

			if ($i == 1) {
				if ($contentcolumns == $c) {$contentrules .= "#content .container .one.column, ";}
				$contentrules .= "#content .container_".$c." .one.column, ";
			}
			if ($contentcolumns == $c) {$contentrules .= "#content .container .".$word.".columns, #content .container .span".$i.", ";}
			$contentrules .= "#content .container_".$c." .".$word.".columns, #content .container_".$c." .span".$i;

			// 960gs and Blueprint
			if ($gridcompat['960gridsystem'] == '1') {
				if ($contentcolumns == $c) {$contentrules .= ", #content .container .grid_".$i;}
				$contentrules .= ", #content .container_".$c." .grid_".$i;
			}
			if ($gridcompat['blueprint'] == '1') {
				if ($contentcolumns == $c) {$contentrules .= ", #content .container .grid_".$i;}
				$contentrules .= ", #content .container_".$c." .span-".$i;
			}
			$contentrules .= " {width: ".$percent."%;}".PHP_EOL;

			if ($i == 1) {
				if ($contentcolumns == $c) {$padleftrules .= "#content .container .offsethalfleft, #content .container .offsethalf, ";}
				$padleftrules .= "#content .container_".$c." .offsethalfleft, #content .container_".$c." .offsethalf";
				$padleftrules .= " {padding-left:".($percent/2)."%;}".PHP_EOL;
				if ($contentcolumns == $c) {$padleftrules .= "#content .container .offsetquarter, #content .container .offsetquarterleft, ";}
				$padleftrules .= "#content .container_".$c." .offsetquarter, #content .container_".$c." .offsetquarterleft";
				$padleftrules .= " {padding-left:".($percent/4)."%;}".PHP_EOL;
			}
			// 1.9.6: fix to offsetleft typo
			if ($contentcolumns == $c) {$padleftrules .= "#content .container .offset".$i.", #content .container .offsetleft".$i.", ";}
			$padleftrules .= "#content .container_".$c." .offset".$i.", #content .container_".$c." .offsetleft".$i;
			if ($gridcompat['960gridsystem'] == '1') {
				if ($contentcolumns == $c) {$padleftrules .= ", #content .container .prefix_".$i;}
				$padleftrules .= ", #content .container_".$c." .prefix_".$i;
			}
			if ($gridcompat['blueprint'] == '1') {
				if ($contentcolumns == $c) {$padleftrules .= ", #content .container .prepend-".$i;}
				$padleftrules .= ", #content .container_".$c." .prepend-".$i;
			}
			$padleftrules .= " {padding-left:".$percent."%;}".PHP_EOL;

			if ($i == 1) {
				if ($contentcolumns == $c) {$padrightrules .= "#content .container .offsethalfright, ";}
				$padrightrules .= "#content .container_".$c." .offsethalfright";
				$padrightrules .= " {padding-right:".($percent/2)."%;}".PHP_EOL;
				if ($contentcolumns == $c) {$padrightrules .= "#content .container .offsetquarterright, ";}
				$padrightrules .= "#content .container_".$c." .offsetquarterright";
				$padrightrules .= " {padding-right:".($percent/4)."%;}".PHP_EOL;
			}
			if ($contentcolumns == $c) {$padrightrules .= "#content .container .offsetright".$i.", ";}
			$padrightrules .= "#content .container_".$c." .offsetright".$i;
			if ($gridcompat['960gridsystem'] == '1') {
				if ($contentcolumns == $c) {$padrightrules .= ", #content .container .suffix_".$i;}
				$padrightrules .= ", #content .container_".$c." .suffix_".$i;
			}
			if ($gridcompat['blueprint'] == '1') {
				if ($contentcolumns == $c) {$padrightrules .= ", #content .container .append-".$i;}
				$padrightrules .= ", #content .container_".$c." .append-".$i;
			}
			$padrightrules .= " {padding-right:".$percent."%;}".PHP_EOL;

			if ($i == 1) {
				if ($contentcolumns == $c) {$shiftleftrules .= "#content .container .shifthalfleft, ";}
				$shiftleftrules .= "#content .container_".$c." .shifthalfleft";
				$shiftleftrules .= " {margin-left:-".($percent/2)."%;}".PHP_EOL;
				if ($contentcolumns == $c) {$shiftleftrules .= "#content .container .shiftquarterleft, ";}
				$shiftleftrules .= "#content .container_".$c." .shiftquarterleft";
				$shiftleftrules .= " {margin-left:-".($percent/4)."%;}".PHP_EOL;
			}
			if ($contentcolumns == $c) {$shiftleftrules .= "#content .container .shiftleft".$i.", ";}
			$shiftleftrules .= "#content .container_".$c." .shiftleft".$i;
			if ($gridcompat['960gridsystem'] == '1') {
				if ($contentcolumns == $c) {$shiftleftrules .= ", #content .container .pull_".$i;}
				$shiftleftrules .= ", #content .container_".$c." .pull_".$i;
			}
			if ($gridcompat['blueprint'] == '1') {
				if ($contentcolumns == $c) {$shiftleftrules .= ", #content .container .pull-".$i;}
				$shiftleftrules .= ", #content .container_".$c." .pull-".$i;
			}
			$shiftleftrules .= " {margin-left:-".$percent."%;}".PHP_EOL;

			if ($i == 1) {
				if ($contentcolumns == $c) {$shiftrightrules .= "#content .container .shifthalfright, ";}
				$shiftrightrules .= "#content .container_".$c." .shifthalfright";
				$shiftrightrules .= " {margin-left:".($percent/2)."%;}".PHP_EOL;
				if ($contentcolumns == $c) {$shiftrightrules .= "#content .container .shiftquarterright, ";}
				$shiftrightrules .= "#content .container_".$c." .shiftquarterright";
				$shiftrightrules .= " {margin-left:".($percent/4)."%;}".PHP_EOL;
			}
			if ($contentcolumns == $c) {$shiftrightrules .= "#content .container .shiftright".$i.", ";}
			$shiftrightrules .= "#content .container_".$c." .shiftright".$i;
			if ($gridcompat['960gridsystem'] == '1') {
				if ($contentcolumns == $c) {$shiftrightrules .= ", #content .container .push_".$i;}
				$shiftrightrules .= ", #content .container_".$c." .push_".$i;
			}
			if ($gridcompat['blueprint'] == '1') {
				if ($contentcolumns == $c) {", #content .container .push-".$i;}
				$shiftrightrules .= ", #content .container_".$c." .push-".$i;
			}
			$shiftrightrules .= " {margin-left:".$percent."%;}".PHP_EOL;
		}

		$rules['content'] .= $contentrules;
		$rules['padleft'] .= $padleftrules;
		$rules['padright'] .= $padrightrules;
		$rules['shiftleft'] .= $shiftleftrules;
		$rules['shiftright'] .= $shiftrightrules;
		return $rules;
	}

?>


/* Grid Column Rules */
/* ----------------- */

<?php

// generate and output the default rules for the layout maxwidth
$defaultcss = skeleton_grid_css_rules($maxwidth,false,'full');
echo $defaultcss.PHP_EOL.PHP_EOL;


// -------------------
// Grid Rules Function
// -------------------
function skeleton_grid_css_rules($totalwidth,$mobile,$offset) {

	global $totalcolumns, $contentcolumns, $contentpadding, $empixels, $maxwidth, $contentwidth, $spacing, $gridcompat;

	// echo $totalcolumns." - ".$contentcolumns." - ".$empixels." - ".$maxwidth." - ".$contentwidth.PHP_EOL;
	// print_r($spacing);

	// Column Size Calculations
	// ------------------------
	$leftmarginem = round(($spacing['left'] / $empixels),3,PHP_ROUND_HALF_DOWN);
	$rightmarginem = round(($spacing['right'] / $empixels),3,PHP_ROUND_HALF_DOWN);
	$halfleftmarginem = round(($leftmarginem / 2),3,PHP_ROUND_HALF_DOWN);
	$halfrightmarginem = round(($rightmarginem / 2),3,PHP_ROUND_HALF_DOWN);
	// 1.8.5: added separate content margin values
	$contentleftmarginem = round(($spacing['leftcontent'] / $empixels),3,PHP_ROUND_HALF_DOWN);
	$contentrightmarginem = round(($spacing['rightcontent'] / $empixels),3,PHP_ROUND_HALF_DOWN);

	$totalwidthem = round(($totalwidth / $empixels),3,PHP_ROUND_HALF_DOWN);
	$almostfullwidth = round((($totalwidth - $spacing['left'] - $spacing['right']) / $empixels),3,PHP_ROUND_HALF_DOWN);
	// $totalwidthem = $totalwidthem - $halfleftmarginem - $halfrightmarginem;

	// 1.8.0: removed outer margins for replacement by inner padding
	// $totalwidth = $totalwidth - (($spacing['left'] + $spacing['right']) / 2);
	$columnwidth = $totalwidth / $totalcolumns;
	$columnwidthem  = round( ($columnwidth / $empixels),3,PHP_ROUND_HALF_DOWN);
	// 1.8.0: add half, third and quarter columns for spacing
	$halfcolumnwidthem  = round( (($columnwidth / 2) / $empixels),3,PHP_ROUND_HALF_DOWN);
	$thirdcolumnwidthem = round( (($columnwidth / 3) / $empixels),3,PHP_ROUND_HALF_DOWN);
	$quartercolumnwidthem = round( (($columnwidth / 4) / $empixels),3,PHP_ROUND_HALF_DOWN);

	// 1.8.0: work out content column widths (via actual content width passed in querystring)
	// 1.9.5: recalculate padding separately for each content width
	$paddingwidth = skeleton_get_content_padding_width($contentwidth,$contentpadding);
	$contentpercent = round( (($contentwidth - $paddingwidth) / $maxwidth),3,PHP_ROUND_HALF_DOWN);
	$thiscontentwidth = round( ($totalwidth * $contentpercent),3,PHP_ROUND_HALF_DOWN);
	// 1.9.5: for mobile sizes, use layout width rules for full width content columns
	if ($mobile) {$thiscontentwidth = $totalwidth;}
	$thiscontentwidthem = round(($thiscontentwidth / $empixels),3,PHP_ROUND_HALF_DOWN);
	// 1.9.5: use separate content columns value at 98% content width
	// $contentcolumnwidth = $thiscontentwidth / $totalcolumns;
	$contentcolumnwidth = $thiscontentwidth / $contentcolumns;
	$contentcolumnsem = round((0.98 * $contentcolumnwidth / $empixels),3,PHP_ROUND_HALF_DOWN);
	// $contentcolumnsem = $contentcolumnsem - (($halfleftmarginem + halfrightmarginem) / 2);
	// 1.8.0: add half, third and quarter columns for spacing
	$halfcontentcolumnsem = round(($contentcolumnsem / 2),3,PHP_ROUND_HALF_DOWN);
	$thirdcontentcolumnwidthem = round(($contentcolumnsem / 3),3,PHP_ROUND_HALF_DOWN);
	$quartercontentcolumnsem = round(($contentcolumnsem / 4),3,PHP_ROUND_HALF_DOWN);

	// Header for this Media Width Size
	$rules = PHP_EOL.'	/* Column Width Rules based on '.$totalwidth.'px ('.$totalwidthem.'em) */'.PHP_EOL.PHP_EOL;
	$contentrules = '';

	// Set numbered column widths array in em
	for ($i = 1; $i < ($totalcolumns+1); $i++) {
		$column[$i] = $columnwidthem * $i;
		// $halfcolumn{$i] = $halfcolumnwidthem * $i;
	}

	// 1.9.5: set content column widths separately
	for ($i = 1; $i < ($contentcolumns+1); $i++) {
		$contentcolumn[$i] = round(($contentcolumnsem * $i),3,PHP_ROUND_HALF_DOWN);
		$halfcontentcolumn[$i] = round(($halfcontentcolumnsem * $i),3,PHP_ROUND_HALF_DOWN);
	}

	/* Skeleton Boilerplate */
	// 1.9.5: set container width only here, moved common rules out and above
	// $rules .= '	.container {position:relative; width:'.$totalwidthem.'em; margin:0 auto; padding:0;}'.PHP_EOL;
	// $rules .= '	.column, .columns {float:left; display:inline;}'.PHP_EOL;
	// 1.9.6: fix to recalculate wrap width total
	$wrapwidthem = $columnwidthem * $totalcolumns;
	$rules .= '	#wrap.container {width:'.$wrapwidthem.'em;}'.PHP_EOL;

	// 1.8.0: changed outer margins to inner padding!
	$rules .= '	.column .inner, .columns .inner {padding-left:'.$leftmarginem.'em; padding-right:'.$rightmarginem.'em;}'.PHP_EOL;
	// 1.8.5: added separate content margin sizes
	$rules .= '	#content .column .inner, #content .columns .inner {padding-left:'.$contentleftmarginem.'em; padding-right:'.$contentrightmarginem.'em;}'.PHP_EOL;


	/* 960 Grid System */
	// 1.9.5: for content grid only, removed duplicate rules
	if ($gridcompat['960gridsystem'] == '1') {
		// $contentrules .= '	#content .container_'.$contentcolumns.' {margin-left: auto; margin-right: auto; width: '.$thiscontentwidthem.'em; font-size:initial;}'.PHP_EOL;
		// $contentrules .= '	#content .container_'.$contentcolumns.':after {clear: both;}'.PHP_EOL;
		$contentrules .= '	#content .container_'.$contentcolumns.':before, .container_'.$contentcolumns.':after {content: "."; display: block; overflow: hidden; visibility: hidden;  width: 0; height: 0; font-size: 0; line-height: 0;}'.PHP_EOL;
	}
	// 1.9.6: fix to em content width, should just be 100% width now
	$contentrules .= '	#content .container {width: 100%;}'.PHP_EOL;
	// $contentrules .= '	#content .container {width: '.$thiscontentwidthem.'em; font-size:initial;}'.PHP_EOL;

	// set element rule names
	$widthrules = array(); $padleftrules = array(); $padrightrules = array();
	$pushrules = array(); $pullrules = array(); $mobilequeries = '';

	// if ($mobile) {$offsetprefix = '.container ';} else {$offsetprefix = '';}
	$offsetprefix = '';

	for ($i = 1; $i < ($totalcolumns+1); $i++) {

		/* Skeleton Boilerplate */
		// 1.8.0: added inner width rules
		// .spanx and .xxxxx.columns
		$widthrulea = '.span'.$i;
		$innerwidthrulea = $widthrulea.' .inner';
		$widthruleb = ', .';
		$widthruleb .= skeleton_number_to_word($i);
		$widthruleb .= '.column';
		if ($i > 1) {$widthruleb .= 's';}
		$innerwidthruleb = $widthruleb.' .inner';
		// 1.8.5: allow for a 'one.columns' plural typo
		if ($i == 1) {$widthruleb .= ', one.columns'; $innerwidthruleb .= ', .one.columns .inner';}
		$widthrules[$i] = $widthrulea.$widthruleb;
		$innerwidthrules[$i] = $innerwidthrulea.$innerwidthruleb;
	}

	// 1.9.5: separate content columns grid loop
	for ($i = 1; $i < ($contentcolumns+1); $i++) {

		/* BioShip Content Grid */
		// 1.5.5: fix, #content subelements (not main #content element)
		// 1.8.0: added inner content rules
		$contentwidthrulea = '	#content .span'.$i;
		$innercontentwidthrulea = $contentwidthrulea.' .inner';
		$contentwidthruleb = ', #content .';
		$contentwidthruleb .= skeleton_number_to_word($i);
		$contentwidthruleb .= '.column';
		if ($i > 1) {$contentwidthruleb .= 's';}
		$innercontentwidthruleb = $contentwidthruleb.' .inner';
		// 1.8.5: allow for a 'one.columns' plural typo
		// 1.9.8: fix to innercontentwidthruleb variable
		if ($i == 1) {$contentwidthruleb .= ', one.columns'; $innercontentwidthruleb .= ', .one.columns .inner';}
		$contentwidthrules[$i] = $contentwidthrulea.$contentwidthruleb;
		$innercontentwidthrules[$i] = $innercontentwidthrulea.$innercontentwidthruleb;

		$padleftrules[$i] = '	'.$offsetprefix.'.offset'.$i.', '.$offsetprefix.'.offsetleft'.$i;
		$padrightrules[$i] = '	'.$offsetprefix.'.offsetright'.$i;
		$pushrules[$i] = '	.shiftright'.$i;
		$pullrules[$i] = '	.shiftleft'.$i;

		/* 960 Grid System */
		// 1.9.5: add to content grid only
		if ( (isset($gridcompat['960gridsystem'])) && ($gridcompat['960gridsystem'] == '1') ) {
			// $widthrules[$i] .= ', .grid_'.$i;
			// $innerwidthrules[$i] .= ', .grid_'.$i.' .inner';
			$contentwidthrules[$i] .= ', .grid_'.$i;
			$innercontentwidthrules[$i] .= ', .grid_'.$i.' .inner';
			if ($i < $contentcolumns) {
				$padleftrules[$i] .= ', '.$offsetprefix.'.prefix_'.$i;
				$padrightrules[$i] .= ', '.$offsetprefix.'.suffix_'.$i;
				$pushrules[$i] .= ', .push_'.$i;
				$pullrules[$i] .= ', .pull_'.$i;
			}
		}

		/* Blueprint */
		// 1.9.5: add to content grid only
		if ( (isset($gridcompat['blueprint'])) && ($gridcompat['blueprint'] == '1') ) {
			// $widthrules[$i] .= ', .span-'.$i;
			// $innerwidthrules[$i] .= ', .span-'.$i.' .inner';
			$contentwidthrules[$i] .= ', .span-'.$i;
			$innercontentwidthrules[$i] .= ', .span-'.$i.' .inner';
			if ($i < $contentcolumns) {
				$padleftrules[$i] .= ', '.$offsetprefix.'.prepend-'.$i;
				$padrightrules[$i] .= ', '.$offsetprefix.'.append-'.$i;
				$pushrules[$i] .= ', .push-'.$i;
				$pullrules[$i] .= ', .pull-'.$i;
			}
		}
	}
	$rules .= PHP_EOL;

	// Main Width Rules
	// ----------------
	// Skeleton Boilerplate: .spanX, .xxxxxx.columns
	// 1.9.0: separate layout and content grids
	for ($i = 1; $i < ($totalcolumns+1); $i++) {
		if (!$mobile) {
			$rules .= '	'.$widthrules[$i].' {width: '.$column[$i].'em;}'.PHP_EOL;
		} else {
			// 1.8.0: added full width mobile columns with max-widths
			$mobilequeries .= '	'.$widthrules[$i].' {width: '.$column[$totalcolumns].'em; max-width:96%;}'.PHP_EOL;
		}
	}
	$rules .= PHP_EOL;

	// Content Width Rules
	// -------------------
	// Skeleton Boilerplate: .spanX, .xxxxxx.columns
	// 960 Grid System: grid_X
	// Blueprint: span-X
	for ($i = 1; $i < ($contentcolumns+1); $i++) {
		if (!$mobile) {
			// 1.9.5: removed em specific mobile query content grid widths in favour of percentages
			// $contentrules .= ' '.$contentwidthrules[$i].' {width: '.$contentcolumn[$i].'em;}'.PHP_EOL;
		} else {
			// 1.8.0: added full width mobile columns with max-widths
			// 1.9.5: replaced with min-widths for dual column mobile content layout
			if ($offset == 'zero') {$minwidth = '100';} else {$minwidth = '50';}
			// $contentrules .= '	'.$contentwidthrules[$i].' {width: '.$contentcolumn[$i].'em; max-width:100%; min-width:'.$minwidth.'%;}'.PHP_EOL;
			// $contentrules .= '	'.$contentwidthrules[$i].' {max-width:100%; min-width:'.$minwidth.'%;}'.PHP_EOL;
		}
	}
	$contentrules .= PHP_EOL;

	// Add Padding Left Rules
	// ----------------------
	// (uses: padding-left)
	// Skeleton Boilerplate: offsetX, offsetleftX
	// 960 Grid System: prefix_X
	// Blueprint: prepend-X
	// note: offset-by-xxxxx classes removed
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ($i = 1; $i < $contentcolumns; $i++) {
		// if ($offset == 'full') {$contentrules .= '	'.$padleftrules[$i].' {padding-left:'.$contentcolumn[$i].'em;}'.PHP_EOL;}
		if ($offset == 'half') {$contentrules .= '	'.$padleftrules[$i].' {padding-left:'.$halfcontentcolumn[$i].'em;}'.PHP_EOL;}
		elseif ($offset == 'zero') {$contentrules .= '	'.$padleftrules[$i].' {padding-left:0;}'.PHP_EOL;}
	}
	$contentrules .= PHP_EOL;

	// Add Padding Right Rules
	// -----------------------
	// (uses: padding-right)
	// Skeleton Boilerplate: offsetrightX
	// 960 Grid System: suffix_X
	// Blueprint: append-X
	// note: offset-by-xxxxx classes removed
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ($i = 1; $i < $contentcolumns; $i++) {
		// if ($offset == 'full') {$contentrules .= '	'.$padrightrules[$i].' {padding-right:'.$contentcolumn[$i].'em;}'.PHP_EOL;}
		if ($offset == 'half') {$contentrules .= '	'.$padrightrules[$i].' {padding-right:'.$halfcontentcolumn[$i].'em;}'.PHP_EOL;}
		elseif ($offset == 'zero') {$contentrules .= '	'.$padrightrules[$i].' {padding-right:0;}'.PHP_EOL;}
	}
	$contentrules .= PHP_EOL;

	// Add Push Rules
	// --------------
	// 1.9.5: no longer negative margin-right (unreliable)
	// (uses: positive margin-left)
	// Skeleton Boilerplate - n/a (added shiftrightX)
	// 960GS - left - push_X
	// Blueprint - strange margins - push-X
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ($i = 1; $i < $contentcolumns; $i++) {
		if ($pushrules[$i] != '') {
			// if ($offset == 'full') {$contentrules .= '	'.$pushrules[$i].' {margin-left: '.$contentcolumn[$i].'em;}'.PHP_EOL;}
			if ($offset == 'half') {$contentrules .= '	'.$pushrules[$i].' {margin-left: '.$halfcontentcolumn[$i].'em;}'.PHP_EOL;}
			elseif ($offset == 'zero') {$contentrules .= '	'.$pushrules[$i].' {margin-left:0;}'.PHP_EOL;}
		}
	}
	$contentrules .= PHP_EOL;

	// Add Pull Rules
	// --------------
	// (uses: negative margin-left)
	// Skeleton Boilerplate - n/a (added: shiftleftX)
	// 960GS - negative left - pull_X
	// Blueprint - negative margin-left - pull-X
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ($i = 1; $i < $contentcolumns; $i++) {
		if ($pullrules[$i] != '') {
			// if ($offset == 'full') {$contentrules .= '	'.$pullrules[$i].' {margin-left: -'.$contentcolumn[$i].'em;}'.PHP_EOL;}
			if ($offset == 'half') {$contentrules .= '	'.$pullrules[$i].' {margin-left: -'.$halfcontentcolumn[$i].'em;}'.PHP_EOL;}
			elseif ($offset == 'zero') {$contentrules .= '	'.$pullrules[$i].' {margin-left:0;}'.PHP_EOL;}
		}
	}
	$contentrules .= PHP_EOL;


	// Half Offsets and Shifts
	// -----------------------
	// 1.8.5: only really needed for content columns
	// // $rules .= ' .offset-by-half, .offsetbyhalf {padding-left:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsethalfleft, .columns.offsethalfleft {padding-left:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsethalfright, .columns.offsethalfright {padding-right:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shifthalfleft, .columns.shifthalfleft {margin-left:-'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shifthalfright, .columns.shifthalfright {margin-right:-'.$halfcolumnwidthem.'em;}'.PHP_EOL;

	// 1.9.5: handled in content container grid generation
	// $contentrules .= '	#content .column.offsethalfleft, #content .columns.offsethalfleft {padding-left:'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.offsethalfright, #content .columns.offsethalfright {padding-right:'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shifthalfleft, #content .columns.shifthalfleft {margin-left:-'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shifthalfright, #content .columns.shifthalfright {margin-right:-'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= PHP_EOL;

	// Quarter Offsets and Shifts
	// --------------------------
	// 1.8.0: added quarter offsets and shifts
	// 1.8.5: only really needed for content columns
	// // $rules .= ' .offset-by-quarter, .offsetbyquarter {padding-left:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsetquarterleft, .columns.offsetquarterleft {padding-left:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsetquarterright, .columns.offsetquarterright {padding-right:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shiftquarterleft, .columns.shiftquarterleft {margin-left:-'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shiftquarterright, .columns.shiftquarterright {margin-right:-'.$quartercolumnwidthem.'em;}'.PHP_EOL;

	// 1.9.5: handled in content container grid generation
	// $contentrules .= '	#content .column.offsetquarterleft, #content .columns.offsetquarterleft {padding-left:'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.offsetquarterright, #content .columns.offsetquarterright {padding-right:'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shiftquarterleft, #content .columns.shiftquarterleft {margin-left:-'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shiftquarterright, #content .columns.shiftquarterright {margin-right:-'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= PHP_EOL;

	// Fractional Column Widths
	// ------------------------
	if ($mobile) {$halfcolumnwidthem = 0; $thirdcolumnwidthem = 0; $quartercolumnwidthem = 0;}
	$rules .= '	.one-half.column, .one-half.columns, .half-column.column, .half-column.columns {width:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	$rules .= '	.one-third.column, .one-third.columns {width:'.$thirdcolumnwidthem.'em;}'.PHP_EOL;
	$rules .= '	.two-thirds.column, .two-thirds.columns {width:'.($thirdcolumnwidthem * 2).'em;}'.PHP_EOL;
	$rules .= '	.one-quarter.column, .one-quarter.columns, .quarter-column.column, .quarter-column.columns {width:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	$rules .= '	.two-quarters.column, .two-quarters.columns {width:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	$rules .= '	.three-quarters.column, .three-quarters.columns {width:'.($quartercolumnwidthem * 3).'em;}'.PHP_EOL;

	// Content Fractional Width Columns
	// --------------------------------
	$contentrules .= '	#content .one-half.column, #content .one-half.columns, #content .half-column.column, #content .half-column.columns {width:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	$contentrules .= '	#content .one-third.column, #content .one-third.columns {width:'.$thirdcolumnwidthem.'em;}'.PHP_EOL;
	$contentrules .= '	#content .two-thirds.column, #content .two-thirds.columns {width:'.($thirdcolumnwidthem * 2).'em;}'.PHP_EOL;
	$contentrules .= '	#content .one-quarter.column, #content .one-quarter.columns, #content .quarter-column.column, #content .quarter-column.columns {width:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	$contentrules .= '	#content .two-quarters.column, #content .two-quarters.columns {width:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	$contentrules .= '	#content .three-quarters.column, #content .three-quarters.columns {width:'.($quartercolumnwidthem * 3).'em;}'.PHP_EOL;

	$rules .= PHP_EOL;

	// Mobile Queries
	// --------------
	if ($mobile) {
		// override to full width container (no wrapper) for small screens
		// 1.9.5: target #wrap container with this rule
		$mobilequeries .= PHP_EOL.'	#wrap.container {width:100% !important;}'.PHP_EOL;

		// override to almost full width for partial percentage columns for small screens
		$mobilequeries .= '	.one_half, .one_third, .two_thirds, .one_fourth, .three_fourths, .one_quarter, .three_quarters,'.PHP_EOL;
		$mobilequeries .= '	.one_fifth, .two_fifth, .two_fifths, .three_fifth, three_fifths, .four_fifth, four_fifths,'.PHP_EOL;
		$mobilequeries .= '	.one_sixth, .two_sixth, .two_sixths, .three_sixth, .three_sixths, .four_sixth, .four_sixths, .five_sixth, .five_sixths'.PHP_EOL;
		$mobilequeries .= '		{width:96% !important; margin-left:2% !important; margin-right:2% !important;}'.PHP_EOL;

		$rules .= PHP_EOL.$mobilequeries;
	}

	$rules .= PHP_EOL;

	// Content Rules Header and Output
	// -------------------------------
	// $rules .= PHP_EOL.'	/* Content Column Width Rules based on '.$thiscontentwidth.'px ('.$thiscontentwidthem.'em) */'.PHP_EOL.PHP_EOL;
	$rules .= $contentrules.PHP_EOL;

	return $rules;

}

// Tested Forcing backgrounds to span full width, even if there is horizontal scrolling.
// note: experimental, using this seems to force a horizontal scrollbar also though,
// by forcing the content width bigger than screen width - not what I was looking for!
// therefore: NOT IMPLEMENTED
// function skeleton_grid_body_min_width($width,$empixels) {
//	// $widthem = round(($width / $empixels),3,PHP_ROUND_HALF_DOWN);
//	// $minwidth = 'html body {min-width: '.$widthem.'em;}'.PHP_EOL;
//
//	// so for now stick with this instead..?
//	$minwidth = 'html body {min-width: '.$width.'px;}'.PHP_EOL;
// 	return $minwidth;
// }


// -------------------------- //
/* Media Screen Width Queries */
// -------------------------- //

// Get Breakpoints
// ---------------
if (isset($vthemesettings['breakpoints'])) {$breakpoints = $vthemesettings['breakpoints'];}
else {$breakpoints = '320, 400, 480, 640, 768, 959, 1140, 1200';} // defaults
// $breakpoints = apply_filters('skeleton_media_breakpoints',$breakpoints); // not filtered

if ($breakpoints == '0') {$numbreakpoints = 0;} // forced off, no breakpoints
else {
	echo PHP_EOL."/* Media Width Breakpoints: ".$breakpoints." */".PHP_EOL.PHP_EOL;
	if (!strstr($breakpoints,',')) {$breakpoints[0] = $breakpoints;}
	else {$breakpoints = explode(',',$breakpoints);}
	$numbreakpoints = count($breakpoints);
}

// Loop Breakpoints
// ----------------
$i = 1;
if ($numbreakpoints > 0) {
	$mediaqueries = ''; $usedbreakpoint = '';
	foreach ($breakpoints as $breakpoint) {
		$breakpoint = trim($breakpoint);
		// echo '/* Breakpoint '.$i.' of '.$numbreakpoints.' */'.PHP_EOL; // debug point

		// respect maximum width and ignore higher breakpoints
		if ($breakpoint < $maxwidth) {

			$usebreakpoint = $breakpoint - 1;
			$usebreakpoint = round(($usebreakpoint/$empixels),3,PHP_ROUND_HALF_DOWN);

			if ($i == 1) {$mediaqueries .= '/* '.$breakpoint.' and under */'.PHP_EOL;}
			elseif ($i < $numbreakpoints) {$mediaqueries .= '/* '.$previousbreakpoint.' to '.$breakpoint.' */'.PHP_EOL;}
			elseif ($i == $numbreakpoints) {$mediaqueries .= '/* '.$breakpoint.' and over */'.PHP_EOL;}

			if ($breakpoint > 320) {
				$lastbreakpoint = $previousbreakpoint;
				$lastbreakpoint = round(($lastbreakpoint/$empixels),3,PHP_ROUND_HALF_DOWN);
				if ($lastbreakpoint == $usedbreakpoint) {$lastbreakpoint = $lastbreakpoint + 0.001;}
			}

			if ($breakpoint < 321) { 			// generally smallest mobile (default 320)
				if ($usebreakpoint == $usedbreakpoint) {$usebreakpoint = $usebreakpoint + 0.001;}
				$mediaqueries .= '@media only screen and (max-width: '.$usebreakpoint.'em) {'.PHP_EOL;
				$mediaqueries .= skeleton_grid_css_rules($breakpoint,true,'zero');
				$mediaqueries .= '}'.PHP_EOL;
			}
			elseif ($breakpoint < 401) { 		// generally small mobile (default 400)
				$mediaqueries .= '@media only screen and (min-width: '.$lastbreakpoint.'em) and (max-width: '.$usebreakpoint.'em) {'.PHP_EOL;
				$mediaqueries .= skeleton_grid_css_rules($previousbreakpoint,true,'zero');
				$mediaqueries .= '}'.PHP_EOL;
			}
			elseif ($breakpoint < 481) { 		// generally standard mobile (default 480)
				$mediaqueries .= '@media only screen and (min-width: '.$lastbreakpoint.'em) and (max-width: '.$usebreakpoint.'em) {'.PHP_EOL;
				$mediaqueries .= skeleton_grid_css_rules($previousbreakpoint,true,'half');
				$mediaqueries .= '}'.PHP_EOL;
			}
			else {								// everything in between
				$mediaqueries .= '@media only screen and (min-width: '.$lastbreakpoint.'em) and (max-width: '.$usebreakpoint.'em) {'.PHP_EOL;
				$mediaqueries .= skeleton_grid_css_rules($previousbreakpoint,false,'full');
				$mediaqueries .= '}'.PHP_EOL;
			}
			if ($i == $numbreakpoints) { 		// largest width (default 1200)
				$usebreakpoint = round(($breakpoint/$empixels),3,PHP_ROUND_HALF_DOWN);
				if ($usebreakpoint == $usedbreakpoint) {$usebreakpoint = $usebreakpoint + 0.001;}
				$mediaqueries .= '@media only screen and (min-width: '.$usebreakpoint.'em) {'.PHP_EOL;
				$mediaqueries .= skeleton_grid_css_rules($breakpoint,false,'full');
				$mediaqueries .= '}'.PHP_EOL;
			}
		}
		$mediaqueries .= PHP_EOL;

		$previousbreakpoint = $breakpoint;
		$usedbreakpoint = $usebreakpoint;
		$i++;
	}
}

echo $mediaqueries.PHP_EOL.PHP_EOL;

// Output Generation Time
// ----------------------
$vendtime = microtime(true); $vdifference = $vendtime - $vthemestarttime;
echo "/* Generation Time: ".$vdifference." */".PHP_EOL;

// maybe output buffered length
// ----------------------------
if ($buffer) {
	$output = ob_get_contents(); ob_end_clean(); echo $output;
	echo "/* Output Length: ".strlen($output)." */";
}

exit;

// -------------------------------- //
/* Media Query Breakpoint Reference */
// -------------------------------- //

// ref: http://bradfrost.com/blog/post/7-habits-of-highly-effective-media-queries/

/* Default Breakpoints */
/* ------------------- */
// [320, 400, 480, 640, 768, 960, 1040, 1200]

// @media only screen and (max-width: 319px) {}
// @media only screen and (min-width: 320px) and (max-width: 399px) {}
// @media only screen and (min-width: 400px) and (max-width: 479px) {}
// @media only screen and (min-width: 480px) and (max-width: 639px) {}
// @media only screen and (min-width: 640px) and (max-width: 767px) {}
// @media only screen and (min-width: 768px) and (max-width: 959px) {}
// @media only screen and (min-width: 960px) and (max-width: 1139px) {}
// @media only screen and (min-width: 1140px) and (max-width: 1199px) {}
// @media only screen and (min-width: 1200px) {}


/* Skeleton Boilerplate */
/* -------------------- */
// [400, 550, 750, 1000]
// ref: http://getskeleton.com/

/* Larger than mobile */
// @media (min-width: 400px) {}

/* Larger than phablet */
// @media (min-width: 550px) {}

/* Larger than tablet */
// @media (min-width: 750px) {}

/* Larger than desktop */
// @media (min-width: 1000px) {}

/* Desktop HD */
// @media only screen and (min-width : 1200px) {}


/* Twitter Bootstrap */
/* ----------------- */
// [320, 480, 758, 992, 1200]
// http://getbootstrap.com/examples/grid/
// https://scotch.io/tutorials/understanding-the-bootstrap-3-grid-system

/* Custom, iPhone Retina */
// @media only screen and (min-width : 320px) {}

/* Extra Small Devices, Phones */
// @media only screen and (min-width : 480px) {}

/* Small Devices, Tablets */
// @media only screen and (min-width : 768px) {}

/* Medium Devices, Desktops (or 979?) */
// @media only screen and (min-width : 992px) {}

/* Large Devices, Wide Screens */
// @media only screen and (min-width : 1200px) {}


/* Foundation */
/* ---------- */
// [various]
// note: taken from Foundation 5
// http://foundation.zurb.com/docs/media-queries.html
// https://scotch.io/tutorials/understanding-zurb-foundation-5s-grid-system

// Small screens
// @media only screen { } /* Define mobile styles */

/* max-width 640px, mobile-only styles, use when QAing mobile issues */
// @media only screen and (max-width: 40em) { }

/* Medium screens min-width 641px */
// @media only screen and (min-width: 40.063em) { }

/* min-width 641px and max-width 1024px, use when QAing tablet-only issues */
// @media only screen and (min-width: 40.063em) and (max-width: 64em) { }

/* Large screens min-width 1025px */
// @media only screen and (min-width: 64.063em) { }

/* min-width 1025px and max-width 1440px, use when QAing large screen-only issues */
// @media only screen and (min-width: 64.063em) and (max-width: 90em) { }

/* XLarge screens min-width 1441px */
// @media only screen and (min-width: 90.063em) { }

/* min-width 1441px and max-width 1920px, use when QAing xlarge screen-only issues */
// @media only screen and (min-width: 90.063em) and (max-width: 120em) { }

/* XXLarge screens min-width 1921px */
// @media only screen and (min-width: 120.063em) { }

?>