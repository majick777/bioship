<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === Layout Action Hook Guide ===
 *
**/

// This file is now a Hook Label Reference rather than a user guide.
// See the Layout Hooks section of docs.php for a more visual reference.

// Note: for Hybrid Hook
// 1.5.0: Now defines an array of layout hooks for use with Hybrid Hook.

// Note: for Content Sidebars
// http://wordquest.org/plugins/content-sidebars/
// This extension is maintained as a separate plugin. However, for completeness
// the default hooks and priorities it uses are noted in this reference also.

// TODO: add Page Elements for Styling Reference


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

/* see header.php */
// <HTML>
// <HEAD>
// * wp_head *
// </HEAD>
// <BODY>

// ----------------------------
// === Start Wrap Container ===
// ----------------------------
/* header.php */ 						$j = 0;				$l[$i] = __('Start Wrap Container','bioship');
$k = 'skeleton_before_container';		$s[$i][$j++] = $k;	$l[$k] = __('Before Wrap Container','bioship'); 	// (no default)
$k = 'skeleton_container_open';			$s[$i][$j++] = $k;	$l[$k] = __('Wrap Container Open','bioship');
 $f = 'skeleton_main_wrapper_open'; 	$p[$k][$f] = 5;		$l[$f] = __('Main DIV Wrapper Open','bioship');

// --------------
// === Header ===
// --------------
/* header.php */						$j = 0;	$i++;		$l[$i] = __('Header','bioship');
$k = 'skeleton_before_header';			$s[$i][$j++] = $k;	$l[$k] = __('Before Header Area','bioship');
 $f = 'skeleton_top_banner';			$p[$k][$f] = 2;		$l[$f] = __('Full Width Top Banner','bioship');
## 	* Content Sidebar *  							 5		## 'Login Sidebar' default top position

$k = 'skeleton_header'; $j++;			$s[$i][$j++] = $k;	$l[$k] = __('Main Header','bioship');
 $f = 'skeleton_header_open';	 		$p[$k][$f] = 0;
 $f = 'skeleton_header_nav';	 		$p[$k][$f] = 2;		$l[$f] = __('Header Menu','bioship');
 $f = 'skeleton_header_logo';			$p[$k][$f] = 4;		$l[$f] = __('Site Logo/Title','bioship');
 $f = 'skeleton_header_widgets';		$p[$k][$f] = 6;		$l[$f] = __('Header Widget Area','bioship');
 $f = 'skeleton_header_extras';			$p[$k][$f] = 8;		$l[$f] = __('HTML Area','bioship');
 $f = 'skeleton_header_close'; 			$p[$k][$f] = 10;
$k ='skeleton_before_header_widgets'; 	$s[$i][$j++] = $k; $l[$k] = __('Before Header Widgets','bioship'); 		// (no default)
	## 	* HEADER SIDEBAR *									/* sidebar/header.php */
$k = 'skeleton_after_header_widgets';	$s[$i][$j++] = $k; 	$l[$k] = __('After Header Widgets','bioship'); 		// (no default)

$k = 'skeleton_after_header'; 			$s[$i][$j++] = $k;	$l[$k] = __('After Header Area','bioship');
 $f = 'skeleton_header_banner';			$p[$k][$f] = 5;		$l[$f] = __('After Header Banner','bioship');

// ----------------------
// === Navigation Bar ===
// ----------------------
/* header.php */						$j = 0;	$i++;		$l[$i] = __('Navigation Bar','bioship');
$k = 'skeleton_before_navbar';			$s[$i][$j++] = $k;	$l[$k] = __('Before Main NavBar','bioship'); 		// (no default)

$k = 'skeleton_navbar';					$s[$i][$j++] = $k;	$l[$k] = __('Main Navigation Bar','bioship');
 $f = 'skeleton_main_menu_open';		$p[$k][$f] = 0;
 $f = 'skeleton_sidebar_mobile_button'; $p[$k][$f] = 2;		$l[$f] = __('Mobile Button for Sidebar','bioship');
 $f = 'skeleton_main_menu_mobile_button'; $p[$k][$f] = 4;	$l[$f] = __('Mobile Button for Main Menu','bioship');
 $f = 'skeleton_subsidebar_mobile_button'; $p[$k][$f] = 6;	$l[$f] = __('Mobile Button for Subsidebar','bioship');
 $f = 'skeleton_main_menu';				$p[$k][$f] = 8;		$l[$f] = __('Superfish Dropdown Main Menu','bioship');
 $f = 'skeleton_main_menu_close';		$p[$k][$f] = 10;

$k = 'skeleton_after_navbar';			$s[$i][$j++] = $k; 	$l[$k] = __('After Main NavBar','bioship');
 $f = 'skeleton_clear_div';				$p[$k][$f] = 0;
 $f = 'skeleton_navbar_banner';		 	$p[$k][$f] = 4;		$l[$f] = __('Full Width Navbar Banner','bioship');
 $f = 'skeleton_clear_div';			 	$p[$k][$f] = 8;

// --------------------
// === Main Sidebar ===
// --------------------
/* /sidebar/{sidebar}.php */ 			$j = 0;	$i++;		$l[$i] = __('Primary Sidebar','bioship');
$k = 'skeleton_before_sidebar';			$s[$i][$j++] = $k;	$l[$k] = __('Before Main Sidebar','bioship');
 $f = 'skeleton_sidebar_open';			$p[$k][$f] = 5;
##			 * SIDEBAR *									## see skeleton_get_sidebar in skeleton.php
$k = 'skeleton_after_sidebar';			$s[$i][$j++] = $k;	$l[$k] = __('After Sidebar','bioship');
 $f = 'skeleton_sidebar_close';			$p[$k][$f] = 5;

// -------------------
// === Sub Sidebar ===
// -------------------
/* /sidebar/{subsidebar}.php */ 		$j = 0;	$i++;		$l[$i] = __('Subsidiary Sidebar','bioship');
$k = 'skeleton_before_subsidebar';		$s[$i][$j++] = $k;	$l[$k] = __('Before SubSidebar','bioship');
 $f = 'skeleton_subsidebar_open';		$p[$k][$f] = 5;
##			* SUBSIDEBAR *									## see skeleton_get_sidebar in skeleton.php
$k ='skeleton_after_subsidebar';		$s[$i][$j++] = $k;	$l[$k] = __('After SubSidebar','bioship');
 $f = 'skeleton_subsidebar_close';	 	$p[$k][$f] = 5;


// --------------------
// === Content Area ===
// --------------------
/* index.php */ 						$j = 0;	$i++;		$l[$i] = __('Start Content Area','bioship');
$k = 'skeleton_before_content';			$s[$i][$j++] = $k;	$l[$k] = __('Before Content Area','bioship');
##		* Content Sidebar *  		 	$p[$k][$f] = 5;		## 'Before Content Sidebar' default position
 $f = 'skeleton_content_wrap_open';		$p[$k][$f] = 10;	$l[$f] = __('Content Wrap Open','bioship');
$k = 'skeleton_before_loop';			$s[$i][$j++] = $k;	$l[$k] = __('Before Any Loop','bioship');
$k = 'skeleton_before_archive';			$s[$i][$j++] = $k;	$l[$k] = __('Before Any Archive Loop','bioship');
$k = 'skeleton_before_category';		$s[$i][$j++] = $k;	$l[$k] = __('Before Category Archive Loop','bioship');
$k = 'skeleton_before_taxonomy';		$s[$i][$j++] = $k;	$l[$k] = __('Before Taxonomy Archive Loop','bioship');
$k = 'skeleton_before_tags';			$s[$i][$j++] = $k;	$l[$k] = __('Before Tag Archive Loop','bioship');
$k = 'skeleton_before_author';			$s[$i][$j++] = $k;	$l[$k] = __('Before Author Archive Loop','bioship');
 $f = 'skeleton_author_bio_top';		$p[$k][$f] = 5;		$l[$f] = __('Author Archive Top Bio','bioship');
$k = 'skeleton_before_date';			$s[$i][$j++] = $k;	$l[$k] = __('Before Date Archive Loop','bioship');

// ------------------------
// === Start Entry Loop ===
// ------------------------
/* content/content.php */				$j = 0;	$i++;		$l[$i] = __('Start Entry Loop','bioship');
$k = 'skeleton_before_entry'; 			$s[$i][$j++] = $k;  $l[$k] = __('Before Entry','bioship');				 // (no default)

// Entry Header
$k = 'skeleton_entry_header';			$s[$i][$j++] = $k;	$l[$k] = __('Entry Header Hook','bioship');
 $f = 'skeleton_entry_header_open';	 	$p[$k][$f] = 0;
 $f = 'skeleton_entry_header_title';	$p[$k][$f] = 2;		$l[$f] = __('Post/Page Title','bioship');
 $f = 'skeleton_entry_header_subtitle'; $p[$k][$f] = 4;		$l[$f] = __('Post/Page SubTitle','bioship');
 $f = 'skeleton_entry_header_meta';	 	$p[$k][$f] = 6;		$l[$f] = __('Entry Meta Top','bioship');
 $f = 'skeleton_entry_header_close'; 	$p[$k][$f] = 10;

// Thumbnail
$k = 'skeleton_thumbnail';				$s[$i][$j++] = $k;  $l[$k] = __('Thumbnail or Featured Image','bioship');
 $f = 'skeleton_echo_thumbnail';		$p[$k][$f] = 5;		$l[$f] = __('Echo Thumbnail','bioship');
 $k = 'skeleton_before_thumbnail';		$s[$i][$j++] = $k;	$l[$k] = __('Before Thumbnail','bioship').' *';
//		*** Thumbnail ***									// before/after hooks only fired if thumbnail content
 $k = 'skeleton_after_thumbnail';		$s[$i][$j++] = $k;	$l[$k] = __('After Thumbnail','bioship').' *';

// -------------------------------------------
// === Excerpt for all multi-post Displays ===
// -------------------------------------------
/* not is_singular() */					$j = 0;	$i++;		$l[$i] = __('Post Excerpts','bioship');
$k = 'skeleton_before_excerpt';			$s[$i][$j++] = $k;	$l[$k] = __('Before Excerpt','bioship');			// (no default)
//	$k = 'skeleton_the_excerpt';							// Main Excerpt Output Hook
//   $f = 'skeleton_echo_the_excerpt';					5	// Simple Wrapper function
// 		*** the_excerpt() ***							 	// WordPress core function call
$k = 'skeleton_after_excerpt';			$s[$i][$j++] = $k;	$l[$k] = __('After Excerpt','bioship'); 			// (no default)

// Entry Footer
$k = 'skeleton_entry_footer';			$s[$i][$j++] = $k;	$l[$k] = __('Entry Footer','bioship');
  $f = 'skeleton_entry_footer_open'; 	$p[$k][$f] = 0;		$l[$f] = __('Bottom Meta Open','bioship');
  $f = 'skeleton_entry_footer_meta'; 	$p[$k][$f] = 6;		$l[$f] = __('Entry Meta Bottom','bioship');
  $f = 'skeleton_entry_footer_close';	$p[$k][$f] = 10;	$l[$f] = __('Bottom Meta Close','bioship');

//			 ***** OR *****

// =========================================
// === Full Content for Posts/Pages/CPTs ===
// =========================================
/* is_singular() */						$j = 0;	$i++;		$l[$i] = __('Singular Content','bioship');
$k = 'skeleton_before_singular'; 		$s[$i][$j++] = $k;	$l[$k] = __('Before Singular Content','bioship');

// Author Bio (top)
$k = 'skeleton_author_bio_top';			$s[$i][$j++] = $k;	$l[$k] = __('Author Bio Top Position','bioship');
 $k = 'skeleton_before_author_bio';		$s[$i][$j++] = $k;	$l[$k] = __('Before Author Bio','bioship').' *';
//    	*** Author Bio Box ***								// before/after hooks only fired if Bio content
 $k = 'skeleton_after_author_bio';		$s[$i][$j++] = $k;	$l[$k] = __('After Author Bio','bioship').' *';

// MAIN CONTENT
$k = 'skeleton_before_the_content';		$s[$i][$j++] = $k;	$l[$k] = __('Before the Content','bioship');		// (no default)
// $k = 'skeleton_the_content';								// Main Content output hook
// 	$f = 'skeleton_echo_the_content';					5	// Simple Wrapper Function
//		  *** the_content() ***	 							// WordPress core function call
$k = 'skeleton_after_the_content';		$s[$i][$j++] = $k;	$l[$k] = __('After the Content','bioship');			// (no default)

// Entry Footer
// note: these are duplicate hooks already defined above for excerpt content,
// 		 only the position the order/position they are called from is different.
// $k = 'skeleton_entry_footer';		$s[$i][$j++] = $k;	$l[$k] = __('Entry Footer','bioship');
//  $f = 'skeleton_entry_footer_open'; 	$p[$k][$f] = 0;		$l[$f] = __('Bottom Meta Open','bioship');
//  $f = 'skeleton_entry_footer_meta'; 	$p[$k][$f] = 6;		$l[$f] = __('Entry Meta Bottom','bioship');
//  $f = 'skeleton_entry_footer_close';	$p[$k][$f] = 10;	$l[$f] = __('Bottom Meta Close','bioship');

// Author Bio (bottom)
$k = 'skeleton_author_bio_bottom';		$s[$i][$j++] = $k;	$l[$k] = __('Author Bio Bottom Position','bioship');
// $k =	'skeleton_before_author_bio';							// no need to declare duplicate hook
// 	  	 *** Author Bio Box ***									// before/after hooks only fired if Bio content
// $k = 'skeleton_after_author_bio'; 							// no need to declare duplicate hook

$k = 'skeleton_after_singular';			$s[$i][$j++] = $k;	$l[$k] = __('After Singular Content','bioship');

// Comments
/* content/comments.php */
// $k = 'skeleton_comments';								// see skeleton_comments in skeleton.php
$k = 'skeleton_before_comments';		$s[$i][$j++] = $k;	$l[$k] = __('Before Comments','bioship');
// 		*** Comments ***
$k = 'skeleton_after_comments';			$s[$i][$j++] = $k;	$l[$k] = __('After Comments','bioship');
// =================================
// END SINGLE CONTENT
// ------------------
// END ENTRY LOOP

// ======================
// === End Entry Loop ===
// ======================
										$j = 0;	$i++;		$l[$i] = __('End Entry Loop','bioship');
$k = 'skeleton_after_entry';			$s[$i][$j++] = $k;	$l[$k] = __('After Entry','bioship'); 			// (no default)
$k = 'skeleton_after_date';				$s[$i][$j++] = $k;	$l[$k] = __('After Date Archive Loop','bioship');
$k = 'skeleton_after_author';			$s[$i][$j++] = $k;	$l[$k] = __('After Author Archive Loop','bioship');
 $f = 'skeleton_author_bio_bottom';		$p[$k][$f] = 0;		$l[$f] = __('Author Archive Bottom Bio','bioship');
$k = 'skeleton_after_tags';				$s[$i][$j++] = $k;	$l[$k] = __('After Tag Archive Loop','bioship');
$k = 'skeleton_after_taxonomy';			$s[$i][$j++] = $k;	$l[$k] = __('After Taxonomy Archive Loop','bioship');
$k = 'skeleton_after_category';			$s[$i][$j++] = $k;	$l[$k] = __('After Category Archive Loop','bioship');
$k = 'skeleton_after_archive';			$s[$i][$j++] = $k;	$l[$k] = __('After Any Archive Loop','bioship');
$k = 'skeleton_after_loop';				$s[$i][$j++] = $k;	$l[$k] = __('After Any Loop','bioship');		// (no default)
$k = 'skeleton_after_content';		 	$s[$i][$j++] = $k;	$l[$k] = __('After Content','bioship');			// (no default)
 $f = 'skeleton_content_wrap_close';	$p[$k][$f] = 0;		$l[$f] = __('Content Wrap Close','bioship');
##		* Content Sidebar *				 			  5		## 'Below Content Sidebar' Default Position
// ----------------------------
// END SIDEBAR AND CONTENT AREA

// --------------
// === Footer ===
// --------------
/* footer.php */						$j = 0;	$i++;		$l[$i] = __('Footer','bioship');
$k = 'skeleton_before_footer';			$s[$i][$j++] = $k;	$l[$k] = __('Before Footer','bioship');
 $f = 'skeleton_footer_banner';		 	$p[$k][$f] = 5;		$l[$k] = __('Full Width Footer Banner','bioship');

$k = 'skeleton_footer';					$s[$i][$j++] = $k;	$l[$k] = __('Main Footer','bioship');
 $f = 'skeleton_footer_open';			$p[$k][$f] = 0;
 $f = 'skeleton_footer_extras'; 		$p[$k][$f] = 2;		$l[$f] = __('Footer Extra HTML Area','bioship');
 $f = 'skeleton_footer_widgets';		$p[$k][$f] = 4;		$l[$f] = __('Footer Widget Area','bioship');
 $f = 'skeleton_footer_nav';			$p[$k][$f] = 6;		$l[$f] = __('Footer Menu','bioship');
 $f = 'skeleton_footer_credits'; 		$p[$k][$f] = 8;		$l[$f] = __('Site Credits','bioship');
 $f = 'skeleton_footer_close';			$p[$k][$f] = 10;
$k = 'skeleton_before_footer_widgets';	$s[$i][$j++] = $k;	$l[$k] = __('Before Footer Widget','bioship');	// (no default)
	## * FOOTER SIDEBARS *									## /* sidebar/footer.php */
$k = 'skeleton_after_footer_widgets';	$s[$i][$j++] = $k;	$l[$k] = __('After Footer Widget','bioship');	// (no default)
$k = 'skeleton_after_footer';			$s[$i][$j++] = $k;	$l[$k] = __('After Footer','bioship');
 $f = 'skeleton_bottom_banner';		 	$p[$k][$f] = 5;		$l[$f] = __('Full Width Bottom Banner','bioship');

// --------------------------
// === End Wrap Container ===
// --------------------------
/* footer.php */						$j = 0;	$i++;		$l[$i] = __('End Wrap Container','bioship');
$k = 'skeleton_container_close';		$s[$i][$j++] = $k;	$l[$k] = __('Main Container Close','bioship');
 $f = 'skeleton_main_wrapper_close';	$p[$k][$f] = 5;		$l[$f] = __('Main DIV Wrapper Close','bioship');
$k = 'skeleton_after_container';		$s[$i][$j++] = $k; 	$l[$k] = __('After Wrap Container','bioship');

/* footer.php */
// * wp_footer *
// </BODY>
// </HTML>

// 1.8.5: give back long names to our short variables
// 1.9.0: store all values in a single array
global $vthemehooks; $vthemehooks = array();
$vthemehooks['sections'] = $s; unset($s);
$vthemehooks['labels'] = $l; unset($l);
$vthemehooks['functions'] = $p; unset($p);

// 1.8.5: loop sections to create hook arrays
$vhooks = array(); $vthemehybridhooks = array(); $vi = 0;
foreach ($vthemehooks['sections'] as $vlayoutsection) {
	foreach ($vlayoutsection as $vhook) {
		// simplified hook list array
		$vthemehooks['hooks'][$vi] = $vhook;

		// also create hook array for Hybrid Hook integration
		// by stripping skeleton_ prefix (added back by Hybrid Hook filter)
		$vthemehooks['hybrid'][$vi] = str_replace('skeleton_','',$vhook);
		$vi++;
	}
}

// Happy space fishing.



if (THEMEDEBUG) {

	echo "<!-- ".PHP_EOL;

	echo "Layout Sections: ".PHP_EOL;
	print_r($vthemehooks['sections']);

	echo "Layout Hooks: ".PHP_EOL;
	print_r($vthemehooks['hooks']);

	echo "Hooked Functions: ".PHP_EOL;
	print_r($vthemehook['functions']);

	echo "Section / Hook Labels".PHP_EOL;
	print_r($vthemehook['labels']);

	echo "-->".PHP_EOL;
}

// /==============================
// === Layout Position Filters ===
// ==============================/
// 1.5.0: you can easily change assigned positions by filtering these values
// eg. swap the subtitle and title on pages to use subtitle as "lead-in" text...
# add_filter('skeleton_entry_header_title_position','custom_title_position');
# add_filter('skeleton_entry_header_subtitle_position','custom_subtitle_position');
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
# add_filter('skeleton_header_nav_position','custom_header_nav_position');
# function custom_header_nav_position($vposition) {
#	if (is_page()) {return -1;} else {return $vposition;}
# }

// TODO: add new missing positions (author bio, breadcrumbs, ...?)

// /= Wrap Container =/
// --------------------
// skeleton_main_wrapper_open_position
// skeleton_main_wrapper_close_position

// /= Header =/
// ------------
// skeleton_header_open_position
// skeleton_header_nav_position
// skeleton_header_logo_position
// skeleton_header_widgets_position
// skeleton_header_extras_position
// skeleton_header_close_position

// /= Primary Menu =/
// ------------------
// skeleton_main_menu_mobile_button_position
// skeleton_main_menu_open_position
// skeleton_main_menu_position
// skeleton_main_menu_close_position

// /= Secondary Menu =/
// --------------------
// skeleton_secondary_menu_position

// /= Banners =/
// -------------
// skeleton_top_banner_position
// skeleton_header_banner_position
// skeleton_footer_banner_position
// skeleton_bottom_banner_position

// /= Sidebar =/
// -------------
// skeleton_sidebar_mobile_button_position
// skeleton_sidebar_open_position
// skeleton_sidebar_close_position

// /= Subsidebar =/
// ----------------
// skeleton_subsidebar_mobile_button_position
// skeleton_subsidebar_open_position
// skeleton_subsidebar_close_position

// /= Content =/
// -------------
// skeleton_content_open_position
// skeleton_content_close_position
// skeleton_content_position
// skeleton_excerpt_position
// skeleton_thumbnail_position

// /= Entry Header =/
// ------------------
// skeleton_entry_header_open_position
// skeleton_entry_header_title_position
// skeleton_entry_header_subtitle_position
// skeleton_entry_header_meta_position
// skeleton_entry_header_close_position

// /= Entry Footer =/
// ------------------
// skeleton_entry_footer_open_position
// skeleton_entry_footer_meta_position
// skeleton_entry_footer_close_position

// /= Page Navigation =/
// ---------------------
// skeleton_page_navi_position

// /= Comments =/
// --------------
// skeleton_comments_position

// Footer
// ------
// skeleton_footer_wrap_open_position
// skeleton_footer_extras_position
// skeleton_footer_widgets_position
// skeleton_footer_nav_position
// skeleton_footer_credits_position
// skeleton_footer_wrap_close_position

?>