<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === Layout Action Hook Definitions ===
 *
**/

// This file is now a Hook Label Reference rather than a user guide.
// See the Layout Hooks section of docs.php for a more visual reference.

// Note: for BioHybrid Hook
// 1.5.0: Now defines an array of layout hooks for use with Hybrid Hook.

// Note: for Content Sidebars
// http://wordquest.org/plugins/content-sidebars/
// This extension is maintained as a separate plugin. However, for completeness
// the default hooks and priorities it uses are noted in this reference also.

// TODO: add Page Elements for a Styling Reference?


							// ======================================== \\
							// === LAYOUT HOOK AND ACTION REFERENCE === \\
							// ======================================== \\

$i = 0;			// section order index
$j = 0;			// current action hook index
$s = array();	// layout section array (ordered)
$k = ''; 		// current action hook key
$f = '';		// function that is hooked
$p = array();	// layout position array (priorities)
$l = array();	// translated labels array

// ==================================================================================================\\
// HOOK (is a) LEGEND ----------------- Order/Priority ---- Section/Hook/Function Labels ------------\\
// /* file/context (if any) */								$l[$i] = __('Section Label','bioship');  \\
// $k = 'layout_action_hook';			$s[$i][$j] = $k;	$l[$k] = __('Hook Label','bioship');	 \\
//  $f = 'function_hooked';				$p[$k][$f] = {x};	$l[$f] = __('Function Label','bioship'); \\
// ##	 * SIDEBAR CALL *									## Description / Notes					 \\
// //	** Function Call ***								// Description / Notes 					 \\
// ==================================================================================================\\

// 1.6.0: added labels as values to hook array
// 1.8.5: converted to extended keyed arrays
// 1.8.5: added label translations
// 1.9.8: removed 'wrap_' from skeleton_content_open and skeleton_content_close
// 1.9.8: added undeclared priority and label for excerpts
// 1.9.8: added missing front_page and home_page definitions
// 1.9.8: added missing media_handler and page_navi definitions

/* see header.php */
// <HTML>
// <HEAD>
// * wp_head *
// </HEAD>
// <BODY>

// ----------------------------
// === Start Wrap Container ===
// ----------------------------
/* header.php */ 								$j = 0;				$l[$i] = __('Start Wrap Container','bioship');
$k = 'bioship_before_container';				$s[$i][$j++] = $k;	$l[$k] = __('Before Wrap Container','bioship'); 	// (no default)
$k = 'bioship_container_open';					$s[$i][$j++] = $k;	$l[$k] = __('Wrap Container Open','bioship');
 $f = 'bioship_skeleton_wrapper_open'; 			$p[$k][$f] = 5;		$l[$f] = __('Main Div Wrapper Open','bioship');

// --------------
// === Header ===
// --------------
/* header.php */								$j = 0;	$i++;		$l[$i] = __('Header','bioship');
$k = 'bioship_before_header';					$s[$i][$j++] = $k;	$l[$k] = __('Before Header Area','bioship');
 $f = 'bioship_skeleton_top_banner';			$p[$k][$f] = 2;		$l[$f] = __('Full Width Top Banner','bioship');
## 	* Content Sidebar *  									 5		## 'Login Sidebar' default top position
 $f = 'bioship_skeleton_secondary_menu';		 $p[$k][$f] = 8;	$l[$f] = __('Secondary Navigation Menu','bioship');

$k = 'bioship_header'; $j++;					$s[$i][$j++] = $k;	$l[$k] = __('Main Header','bioship');
 $f = 'bioship_skeleton_header_open';			$p[$k][$f] = 0;
 $f = 'bioship_skeleton_header_nav';			$p[$k][$f] = 2;		$l[$f] = __('Header Menu','bioship');
 $f = 'bioship_skeleton_echo_clear_div'; 		$p[$k][$f] = 3;		$l[$f] = __('Clear Div','bioship');
 $f = 'bioship_skeleton_header_logo';			$p[$k][$f] = 4;		$l[$f] = __('Site Logo/Title','bioship');
 $f = 'bioship_skeleton_header_widgets';		$p[$k][$f] = 6;		$l[$f] = __('Header Widget Area','bioship');
 $f = 'bioship_skeleton_header_extras';			$p[$k][$f] = 8;		$l[$f] = __('HTML Area','bioship');
 $f = 'bioship_skeleton_header_close'; 			$p[$k][$f] = 10;
$k ='bioship_before_header_widgets'; 			$s[$i][$j++] = $k;	$l[$k] = __('Before Header Widgets','bioship'); 		// (no default)
	## 	* HEADER SIDEBAR *											/* sidebar/header.php */
$k = 'bioship_after_header_widgets';			$s[$i][$j++] = $k; 	$l[$k] = __('After Header Widgets','bioship'); 		// (no default)

$k = 'bioship_after_header'; 					$s[$i][$j++] = $k;	$l[$k] = __('After Header Area','bioship');
 $f = 'bioship_skeleton_header_banner';			$p[$k][$f] = 5;		$l[$f] = __('After Header Banner','bioship');

// ----------------------
// === Navigation Bar ===
// ----------------------
/* header.php */								$j = 0;	$i++;		$l[$i] = __('Navigation Bar','bioship');
$k = 'bioship_before_navbar';					$s[$i][$j++] = $k;	$l[$k] = __('Before Main NavBar','bioship'); 		// (no default)

$k = 'bioship_navbar';							$s[$i][$j++] = $k;	$l[$k] = __('Main Navigation Bar','bioship');
 $f = 'bioship_skeleton_main_menu_open';		$p[$k][$f] = 0;
 $f = 'bioship_skeleton_sidebar_button'; 		$p[$k][$f] = 2;		$l[$f] = __('Mobile Button for Sidebar','bioship');
 $f = 'bioship_skeleton_main_menu_button'; 		$p[$k][$f] = 4;		$l[$f] = __('Mobile Button for Main Menu','bioship');
 $f = 'bioship_skeleton_subsidebar_button';		$p[$k][$f] = 6;		$l[$f] = __('Mobile Button for Subsidebar','bioship');
 $f = 'bioship_skeleton_main_menu';				$p[$k][$f] = 8;		$l[$f] = __('Superfish Dropdown Main Menu','bioship');
 $f = 'bioship_skeleton_main_menu_close';		$p[$k][$f] = 10;

$k = 'bioship_after_navbar';					$s[$i][$j++] = $k; 	$l[$k] = __('After Main NavBar','bioship');
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 0;
 $f = 'bioship_skeleton_navbar_banner';			$p[$k][$f] = 4;		$l[$f] = __('Full Width Navbar Banner','bioship');
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 8;

// --------------------
// === Main Sidebar ===
// --------------------
/* /sidebar/{sidebar}.php */ 					$j = 0;	$i++;		$l[$i] = __('Primary Sidebar','bioship');
$k = 'bioship_before_sidebar';					$s[$i][$j++] = $k;	$l[$k] = __('Before Main Sidebar','bioship');
 $f = 'bioship_skeleton_sidebar_open';			$p[$k][$f] = 5;
##			 * SIDEBAR *											## see bioship_get_sidebar in skull.php
$k = 'bioship_after_sidebar';					$s[$i][$j++] = $k;	$l[$k] = __('After Sidebar','bioship');
 $f = 'bioship_skeleton_sidebar_close';			$p[$k][$f] = 5;

// -------------------
// === Sub Sidebar ===
// -------------------
/* /sidebar/{subsidebar}.php */ 				$j = 0;	$i++;		$l[$i] = __('Subsidiary Sidebar','bioship');
$k = 'bioship_before_subsidebar';				$s[$i][$j++] = $k;	$l[$k] = __('Before SubSidebar','bioship');
 $f = 'bioship_skeleton_subsidebar_open';		$p[$k][$f] = 5;
##			* SUBSIDEBAR *											## see bioship_get_sidebar in skull.php
$k ='bioship_after_subsidebar';					$s[$i][$j++] = $k;	$l[$k] = __('After SubSidebar','bioship');
 $f = 'bioship_skeleton_subsidebar_close'; 		$p[$k][$f] = 5;


// --------------------
// === Content Area ===
// --------------------
/* index.php */ 								$j = 0;	$i++;		$l[$i] = __('Start Content Area','bioship');
$k = 'bioship_before_content';					$s[$i][$j++] = $k;	$l[$k] = __('Before Content Area','bioship');
##		* Content Sidebar *  		 			$p[$k][$f] = 5;		## 'Before Content Sidebar' default position
 $f = 'bioship_skeleton_content_open';			$p[$k][$f] = 10;	$l[$f] = __('Content Wrap Open','bioship');
$k = 'bioship_front_page_top';					$s[$i][$j++] = $k;	$l[$k] = __('Frontpage Only Top','bioship');
 $f = 'bioship_skeleton_front_page_content';	$p[$k][$f] = 5;		$l[$k] = __('Frontpage Only Content','bioship');
$k = 'bioship_home_page_top';					$s[$i][$j++] = $k;	$l[$k] = __('Home (Blog) Only Top','bioship');
 $f = 'bioship_skeleton_home_page_content'; 	$p[$k][$f] = 5;		$l[$k] = __('Home (Blog) Only Content','bioship');
$k = 'bioship_before_loop';						$s[$i][$j++] = $k;	$l[$k] = __('Before Any Loop','bioship');
 $f = 'bioship_skeleton_breadcrumbs';			$p[$k][$f] = 5;		$l[$k] = __('Breadcrumbs','bioship');
$k = 'bioship_before_archive';					$s[$i][$j++] = $k;	$l[$k] = __('Before Any Archive Loop','bioship');
$k = 'bioship_before_category';					$s[$i][$j++] = $k;	$l[$k] = __('Before Category Archive Loop','bioship');
$k = 'bioship_before_taxonomy';					$s[$i][$j++] = $k;	$l[$k] = __('Before Taxonomy Archive Loop','bioship');
$k = 'bioship_before_tags';						$s[$i][$j++] = $k;	$l[$k] = __('Before Tag Archive Loop','bioship');
$k = 'bioship_before_author';					$s[$i][$j++] = $k;	$l[$k] = __('Before Author Archive Loop','bioship');
 $f = 'bioship_skeleton_author_bio_top';		$p[$k][$f] = 5;		$l[$f] = __('Author Archive Top Bio','bioship');
$k = 'bioship_before_date';						$s[$i][$j++] = $k;	$l[$k] = __('Before Date Archive Loop','bioship');

// ------------------------
// === Start Entry Loop ===
// ------------------------
/* content/content.php */						$j = 0;	$i++;		$l[$i] = __('Start Entry Loop','bioship');
$k = 'bioship_before_entry'; 					$s[$i][$j++] = $k;  $l[$k] = __('Before Entry','bioship');				 // (no default)
$k = 'bioship_media_handler'; 					$s[$i][$j++] = $k;	$l[$k] = __('Media Handler Action','bioship');
 $f = 'bioship_skeleton_media_handler';			$p[$k][$f] = 5;		$l[$k] = __('Attachment Media Handler','bioship');

// Entry Header
$k = 'bioship_entry_header';					$s[$i][$j++] = $k;	$l[$k] = __('Entry Header Hook','bioship');
 $f = 'bioship_skeleton_entry_header_open';	 	$p[$k][$f] = 0;
 $f = 'bioship_skeleton_entry_header_title';	$p[$k][$f] = 2;		$l[$f] = __('Post/Page Title','bioship');
 $f = 'bioship_skeleton_entry_header_subtitle'; $p[$k][$f] = 4;		$l[$f] = __('Post/Page SubTitle','bioship');
 $f = 'bioship_skeleton_entry_header_meta';	 	$p[$k][$f] = 6;		$l[$f] = __('Entry Meta Top','bioship');
 $f = 'bioship_skeleton_entry_header_close'; 	$p[$k][$f] = 10;

// Thumbnail
$k = 'bioship_thumbnail';						$s[$i][$j++] = $k;  $l[$k] = __('Thumbnail or Featured Image','bioship');
 $f = 'bioship_skeleton_echo_thumbnail';		$p[$k][$f] = 5;		$l[$f] = __('Echo Thumbnail','bioship');
$k = 'bioship_before_thumbnail';				$s[$i][$j++] = $k;	$l[$k] = __('Before Thumbnail','bioship').' *';
//		*** Thumbnail ***											// before/after hooks only fired if thumbnail content //
 $k = 'bioship_after_thumbnail';				$s[$i][$j++] = $k;	$l[$k] = __('After Thumbnail','bioship').' *';

// -------------------------------------------
// === Excerpt for all multi-post Displays ===
// -------------------------------------------
/* not is_singular() */							$j = 0;	$i++;		$l[$i] = __('Post Excerpts','bioship');
$k = 'bioship_before_excerpt';					$s[$i][$j++] = $k;	$l[$k] = __('Before Excerpt','bioship');			// (no default)
$k = 'bioship_the_excerpt';						$s[$i][$j++] = $k;	$l[$k] = __('Main Excerpt Output Hook','bioship');
 $f = 'bioship_skeleton_echo_the_excerpt';		$p[$k][$f] = 5;		$l[$f] = __('Main Excerpt Output','bioship');
// 		*** the_excerpt() ***							 			// WordPress core function call //
$k = 'bioship_after_excerpt';					$s[$i][$j++] = $k;	$l[$k] = __('After Excerpt','bioship'); 			// (no default)

$k = 'bioship_entry_footer';					$s[$i][$j++] = $k;	$l[$k] = __('Entry Footer','bioship');
 $f = 'bioship_skeleton_entry_footer_open';		$p[$k][$f] = 0;		$l[$f] = __('Bottom Meta Open','bioship');
 $f = 'bioship_skeleton_entry_footer_meta';		$p[$k][$f] = 6;		$l[$f] = __('Entry Meta Bottom','bioship');
 $f = 'bioship_skeleton_entry_footer_close';	$p[$k][$f] = 10;	$l[$f] = __('Bottom Meta Close','bioship');

//			 ***** OR *****

// =========================================
// === Full Content for Posts/Pages/CPTs ===
// =========================================
/* is_singular() */								$j = 0;	$i++;		$l[$i] = __('Singular Content','bioship');
$k = 'bioship_before_singular'; 				$s[$i][$j++] = $k;	$l[$k] = __('Before Singular Content','bioship');
 $f = 'bioship_skeleton_breadcrumbs';			$p[$k][$f] = 5;		$l[$k] = __('Breadcrumbs','bioship');

// Author Bio (top)
$k = 'bioship_author_bio_top';					$s[$i][$j++] = $k;	$l[$k] = __('Author Bio Top Position','bioship');
 $k = 'bioship_before_author_bio';				$s[$i][$j++] = $k;	$l[$k] = __('Before Author Bio','bioship').' *';
//    	*** Author Bio Box ***										// before/after hooks only fired if Bio content //
 $k = 'bioship_after_author_bio';				$s[$i][$j++] = $k;	$l[$k] = __('After Author Bio','bioship').' *';

// MAIN CONTENT
$k = 'bioship_before_the_content';				$s[$i][$j++] = $k;	$l[$k] = __('Before the Content','bioship'); // (no default)
$k = 'bioship_the_content';						$s[$i][$j++] = $k; 	// Main Content output hook //
 $f = 'bioship_skeleton_echo_the_content';		$p[$k][$f] = 5;		// Simple Wrapper Function //
//		  *** the_content() ***	 									// WordPress core function call //
$k = 'bioship_after_the_content';				$s[$i][$j++] = $k;	$l[$k] = __('After the Content','bioship'); // (no default)

// Entry Footer (exact duplicate of excerpt entry footer above)

// Author Bio (bottom)
$k = 'bioship_author_bio_bottom';				$s[$i][$j++] = $k;	$l[$k] = __('Author Bio Bottom Position','bioship');
// $k =	'bioship_before_author_bio';								// no need to redeclare (duplicate hook) //
// 	  	 *** Author Bio Box ***										// before/after hooks only fired if Bio content //
// $k = 'bioship_after_author_bio'; 								// no need to redeclare (duplicate hook) //

$k = 'bioship_after_singular';					$s[$i][$j++] = $k;	$l[$k] = __('After Singular Content','bioship');

// Comments
/* content/comments.php */
// $k = 'bioship_comments';											// see bioship_comments in skeleton.php
$k = 'bioship_before_comments';					$s[$i][$j++] = $k;	$l[$k] = __('Before Comments','bioship');
// 		*** Comments ***
$k = 'bioship_after_comments';					$s[$i][$j++] = $k;	$l[$k] = __('After Comments','bioship');
// =================================
// END SINGLE CONTENT
// ------------------
// END ENTRY LOOP

// ======================
// === End Entry Loop ===
// ======================
												$j = 0;	$i++;		$l[$i] = __('End Entry Loop','bioship');
$k = 'bioship_after_entry';						$s[$i][$j++] = $k;	$l[$k] = __('After Entry','bioship'); 			// (no default)
$k = 'bioship_page_navi';						$s[$i][$j++] = $k;	$l[$k] = __('Page Navigation','bioship');
 $f = 'bioship_skeleton_page_navigation';		$p[$k][$f] = 5;		$l[$k] = __('Page Navigation','bioship');
$k = 'bioship_after_date';						$s[$i][$j++] = $k;	$l[$k] = __('After Date Archive Loop','bioship');
$k = 'bioship_after_author';					$s[$i][$j++] = $k;	$l[$k] = __('After Author Archive Loop','bioship');
 $f = 'bioship_skeleton_author_bio_bottom';		$p[$k][$f] = 0;		$l[$f] = __('Author Archive Bottom Bio','bioship');
$k = 'bioship_after_tags';						$s[$i][$j++] = $k;	$l[$k] = __('After Tag Archive Loop','bioship');
$k = 'bioship_after_taxonomy';					$s[$i][$j++] = $k;	$l[$k] = __('After Taxonomy Archive Loop','bioship');
$k = 'bioship_after_category';					$s[$i][$j++] = $k;	$l[$k] = __('After Category Archive Loop','bioship');
$k = 'bioship_after_archive';					$s[$i][$j++] = $k;	$l[$k] = __('After Any Archive Loop','bioship');
$k = 'bioship_after_loop';						$s[$i][$j++] = $k;	$l[$k] = __('After Any Loop','bioship');		// (no default)
$k = 'bioship_after_content';		 			$s[$i][$j++] = $k;	$l[$k] = __('After Content','bioship');			// (no default)
 $f = 'bioship_skeleton_content_close';			$p[$k][$f] = 0;		$l[$f] = __('Content Wrap Close','bioship');
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 2;
##		* Content Sidebar *						 			  5		## 'Below Content Sidebar' Default Position
// ----------------------------
// END SIDEBAR AND CONTENT AREA

// --------------
// === Footer ===
// --------------
/* footer.php */								$j = 0;	$i++;		$l[$i] = __('Footer','bioship');
$k = 'bioship_before_footer';					$s[$i][$j++] = $k;	$l[$k] = __('Before Footer','bioship');
 $f = 'bioship_skeleton_footer_banner';			$p[$k][$f] = 5;		$l[$k] = __('Full Width Footer Banner','bioship');
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 10;
// * wp_footer *
$k = 'bioship_footer';							$s[$i][$j++] = $k;	$l[$k] = __('Main Footer','bioship');
 $f = 'bioship_skeleton_footer_open';			$p[$k][$f] = 0;
 $f = 'bioship_skeleton_footer_extras'; 		$p[$k][$f] = 2;		$l[$f] = __('Footer Extra HTML Area','bioship');
 $f = 'bioship_skeleton_footer_widgets';		$p[$k][$f] = 4;		$l[$f] = __('Footer Widget Area','bioship');
 $f = 'bioship_skeleton_footer_nav';			$p[$k][$f] = 6;		$l[$f] = __('Footer Menu','bioship');
 $f = 'bioship_skeleton_footer_credits'; 		$p[$k][$f] = 8;		$l[$f] = __('Site Credits','bioship');
 $f = 'bioship_skeleton_footer_close';			$p[$k][$f] = 10;
$k = 'bioship_before_footer_widgets';			$s[$i][$j++] = $k;	$l[$k] = __('Before Footer Widget','bioship');	// (no default)
	## * FOOTER SIDEBARS *									## /* sidebar/footer.php */
$k = 'bioship_after_footer_widgets';			$s[$i][$j++] = $k;	$l[$k] = __('After Footer Widget','bioship');	// (no default)
$k = 'bioship_after_footer';					$s[$i][$j++] = $k;	$l[$k] = __('After Footer','bioship');
 $f = 'bioship_skeleton_bottom_banner';			$p[$k][$f] = 5;		$l[$f] = __('Full Width Bottom Banner','bioship');

// --------------------------
// === End Wrap Container ===
// --------------------------
/* footer.php */								$j = 0;	$i++;		$l[$i] = __('End Wrap Container','bioship');
$k = 'bioship_container_close';					$s[$i][$j++] = $k;	$l[$k] = __('Main Container Close','bioship');
 $f = 'bioship_skeleton_wrapper_close';			$p[$k][$f] = 5;		$l[$f] = __('Main DIV Wrapper Close','bioship');
$k = 'bioship_after_container';					$s[$i][$j++] = $k; 	$l[$k] = __('After Wrap Container','bioship');

/* footer.php */
// </BODY>
// </HTML>

// 1.8.5: give back long names to our short variables
// 1.9.0: store all values in a single array
global $vthemehooks;
if (!isset($vthemehooks)) {$vthemehooks = array();}
$vthemehooks['sections'] = $s; unset($s);
$vthemehooks['labels'] = $l; unset($l);
$vthemehooks['functions'] = $p; unset($p);
// 2.0.5: declare empty remove array for back compat
$vthemehooks['remove'] = array();

// 1.8.5: loop sections to create hook arrays
$vhooks = array(); $vthemehybridhooks = array(); $vi = 0;
foreach ($vthemehooks['sections'] as $vlayoutsection) {
	foreach ($vlayoutsection as $vhook) {
		// simplified hook list array
		$vthemehooks['hooks'][$vi] = $vhook;

		// also create hook array for Hybrid Hook integration
		// by stripping skeleton_ prefix (added back by Hybrid Hook filter)
		// 2.0.5: strip bioship_ prefix not skeleton_ prefix
		$vthemehooks['hybrid'][$vi] = substr($vhook, strlen('bioship_'), strlen($vhook));
		$vi++;
	}
}

// Debug Output
// ------------
if (THEMEDEBUG) {
	echo "<!-- ".PHP_EOL;

	echo "Layout Sections: ".PHP_EOL;
	print_r($vthemehooks['sections']);

	echo "Layout Hooks: ".PHP_EOL;
	print_r($vthemehooks['hooks']);

	echo "Hooked Functions: ".PHP_EOL;
	print_r($vthemehooks['functions']);

	echo "Section / Hook Labels: ".PHP_EOL;
	print_r($vthemehooks['labels']);

	echo "Hybrid Hooks: ".PHP_EOL;
	print_r($vthemehooks['hybrid']);

	echo "-->".PHP_EOL;
}


// /==============================
// === Layout Position Filters ===
// ==============================/
// 1.5.0: you can easily change assigned positions by filtering these values
// eg. swap the subtitle and title on pages to use subtitle as "lead-in" text...
# add_filter('bioship_skeleton_entry_header_title_position', 'custom_title_position');
# add_filter('bioship_skeleton_entry_header_subtitle_position', 'custom_subtitle_position');
# function custom_title_position($vposition) {
#	if (is_page()) {return 4;} else {return $vposition;}
# }
# function custom_subtitle_position($vposition) {
#	if (is_page()) {return 2;} else {return $vposition;}
# }


// 1.6.0: allowed to remove a section entirely by setting position filter to -1
// note: this is for advanced usage only and may produce erratic results
// (if removing open and close wrappers, remove both not just one or the other)
// eg. to remove header widgets from pages just return -1
# add_filter('bioship_skeleton_header_nav_position','custom_header_nav_position');
# function custom_header_nav_position($vposition) {
#	if (is_page()) {return -1;} else {return $vposition;}
# }

// TODO: add new missing positions (author bio, breadcrumbs, ...?)

// /= Wrap Container =/
// --------------------
// bioship_skeleton_wrapper_open_position
// bioship_skeleton_wrapper_close_position

// /= Header =/
// ------------
// bioship_skeleton_header_open_position
// bioship_skeleton_header_nav_position
// bioship_skeleton_header_logo_position
// bioship_skeleton_header_widgets_position
// bioship_skeleton_header_extras_position
// bioship_skeleton_header_close_position

// /= Primary Menu =/
// ------------------
// bioship_skeleton_main_menu_mobile_button_position
// bioship_skeleton_main_menu_open_position
// bioship_skeleton_main_menu_position
// bioship_skeleton_main_menu_close_position

// /= Secondary Menu =/
// --------------------
// bioship_skeleton_secondary_menu_position

// /= Banners =/
// -------------
// bioship_skeleton_top_banner_position
// bioship_skeleton_header_banner_position
// bioship_skeleton_footer_banner_position
// bioship_skeleton_bottom_banner_position

// /= Sidebar =/
// -------------
// bioship_skeleton_sidebar_mobile_button_position
// bioship_skeleton_sidebar_open_position
// bioship_skeleton_sidebar_close_position

// /= Subsidebar =/
// ----------------
// bioship_skeleton_subsidebar_mobile_button_position
// bioship_skeleton_subsidebar_open_position
// bioship_skeleton_subsidebar_close_position

// /= Content =/
// -------------
// bioship_skeleton_content_open_position
// bioship_skeleton_content_close_position
// bioship_skeleton_content_position
// bioship_skeleton_excerpt_position
// bioship_skeleton_thumbnail_position

// /= Entry Header =/
// ------------------
// bioship_skeleton_entry_header_open_position
// bioship_skeleton_entry_header_title_position
// bioship_skeleton_entry_header_subtitle_position
// bioship_skeleton_entry_header_meta_position
// bioship_skeleton_entry_header_close_position

// /= Entry Footer =/
// ------------------
// bioship_skeleton_entry_footer_open_position
// bioship_skeleton_entry_footer_meta_position
// bioship_skeleton_entry_footer_close_position

// /= Page Navigation =/
// ---------------------
// bioship_skeleton_page_navi_position

// /= Comments =/
// --------------
// bioship_skeleton_comments_position

// Footer
// ------
// bioship_skeleton_footer_wrap_open_position
// bioship_skeleton_footer_extras_position
// bioship_skeleton_footer_widgets_position
// bioship_skeleton_footer_nav_position
// bioship_skeleton_footer_credits_position
// bioship_skeleton_footer_wrap_close_position


// --------------------
// Happy space fishing.

?>