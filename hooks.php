<?php

// ===========================
// ====== BioShip Hooks ======
// = Layout Hook Definitions =
// ===========================

// Note: This file is now a Hook Label Reference rather than a user guide.
// See the Layout Hooks section of docs.php for a more visual reference.

// Content Sidebars Plugin Note
// ----------------------------
// https://wordquest.org/plugins/content-sidebars/
// or https://wordpress.org/plugins/content-sidebars/
// or https://github.com/majick777/content-sidebars/
// This extension is maintained as a separate plugin. However, for completeness here
// the default action hooks and priorities it uses are noted in this reference also.

// TODO: add Page Elements for a Styling Reference
// TODO: add information array for Content Sidebars plugin


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
$t = array();	// template file notes array
$e = array();	// page elements for styling array
$c = array();	// page action contexts (s=single, a=archive)

// ==================================================================================================\\
// HOOK (is a) LEGEND ----------------- Order/Priority ---- Section/Hook/Function Labels ------------\\
// /* file/context (if any) */								$l[$i] = __('Section Label','bioship');  \\
// $k = 'layout_action_hook';			$s[$i][$j] = $k;	$l[$k] = __('Hook Label','bioship');	 \\
//  $f = 'function_hooked';				$p[$k][$f] = {x};	$l[$f] = __('Function Label','bioship'); \\
// ##	 * SIDEBAR CALL *									## Description / Notes					 \\
// //	** Function Call ***								// Description / Notes 					 \\
// ==================================================================================================\\

// 1.5.0: added array of layout hooks for use with Hybrid Hook
// 1.6.0: added labels as values to hook array
// 1.8.5: converted to extended keyed arrays
// 1.8.5: added label translations
// 1.9.8: removed 'wrap_' from skeleton_content_open and skeleton_content_close
// 1.9.8: added undeclared priority and label for excerpts
// 1.9.8: added missing front_page and home_page definitions
// 1.9.8: added missing media_handler and page_navi definitions
// 2.0.5: declare empty remove array for back compat
// 2.0.9: added missing labels for open and close wrap functions
// 2.0.9: fix labels for content, media, breadcrumbs, pagenav, footer banner, home, frontpage
// 2.0.9: added content area, design style elements and notes array definitions
// 2.1.1: added bioship_front_page_bottom and bioship_home_page_bottom hooks
// 2.2.0: change priority for bioship_skeleton_main_menu from 8 to 2

/* see header.php */
// <HTML>
// <HEAD>
// * wp_head *
// </HEAD>
// <BODY>

// -----------------------
// === Start Container ===
// -----------------------
/* header.php */ 								$j = 0;				$l[$i] = __( 'Start Wrap Container', 'bioship' );
$k = 'bioship_before_container';				$s[$i][$j++] = $k;	$l[$k] = __( 'Before Wrap Container', 'bioship' ); // (no default)
$k = 'bioship_container_open';					$s[$i][$j++] = $k;	$l[$k] = __( 'Wrap Container Open', 'bioship' );
 $f = 'bioship_skeleton_wrapper_open'; 			$p[$k][$f] = 5;		$l[$f] = __( 'Main Container Wrap Open', 'bioship' );

// --------------
// === Header ===
// --------------
/* header.php */								$j = 0;	$i++;		$l[$i] = __( 'Header', 'bioship' );
$k = 'bioship_before_header';					$s[$i][$j++] = $k;	$l[$k] = __( 'Before Header Area', 'bioship' );
 $f = 'bioship_skeleton_top_banner';			$p[$k][$f] = 2;		$l[$f] = __( 'Full Width Top Banner', 'bioship' );
## 	* Content Sidebar *  									 5		## 'Login Sidebar' default top position
 $f = 'bioship_skeleton_secondary_menu';		 $p[$k][$f] = 8;	$l[$f] = __( 'Secondary Navigation Menu', 'bioship' );

$k = 'bioship_header'; $j++;					$s[$i][$j++] = $k;	$l[$k] = __( 'Main Header', 'bioship' );
 $f = 'bioship_skeleton_header_open';			$p[$k][$f] = 0;		$l[$f] = __( 'Header Wrap Open', 'bioship' );
 $f = 'bioship_skeleton_header_nav';			$p[$k][$f] = 2;		$l[$f] = __( 'Header Menu', 'bioship' );
 $f = 'bioship_skeleton_echo_clear_div'; 		$p[$k][$f] = 3;		$l[$f] = __( 'Clear Div', 'bioship' );
 $f = 'bioship_skeleton_header_logo';			$p[$k][$f] = 4;		$l[$f] = __( 'Site Logo/Title', 'bioship' );
 $f = 'bioship_skeleton_header_widgets';		$p[$k][$f] = 6;		$l[$f] = __( 'Header Widget Area', 'bioship' );
 $f = 'bioship_skeleton_header_extras';			$p[$k][$f] = 8;		$l[$f] = __( 'HTML Area', 'bioship' );
 $f = 'bioship_skeleton_header_close'; 			$p[$k][$f] = 10;	$l[$f] = __( 'Header Wrap Close', 'bioship' );
$k = 'bioship_before_header_widgets'; 			$s[$i][$j++] = $k;	$l[$k] = __( 'Before Header Widgets', 'bioship' ); // (no default)
	## 	* HEADER SIDEBAR *											/* sidebar/header.php */
$k = 'bioship_header_sidebar';					$s[$i][$j++] = $k;	$l[$k] = __( 'Header Widget Area Template', 'bioship' );
	$t[$k] = 'sidebar/header.php';
$k = 'bioship_after_header_widgets';			$s[$i][$j++] = $k; 	$l[$k] = __( 'After Header Widgets', 'bioship' ); // (no default)

$k = 'bioship_after_header'; 					$s[$i][$j++] = $k;	$l[$k] = __( 'After Header Area', 'bioship' );
 $f = 'bioship_skeleton_header_banner';			$p[$k][$f] = 5;		$l[$f] = __( 'After Header Banner', 'bioship' );

// ----------------------
// === Navigation Bar ===
// ----------------------
/* header.php */								$j = 0;	$i++;		$l[$i] = __( 'Navigation Bar', 'bioship' );
$k = 'bioship_before_navbar';					$s[$i][$j++] = $k;	$l[$k] = __( 'Before Main NavBar', 'bioship' ); // (no default)

$k = 'bioship_navbar';							$s[$i][$j++] = $k;	$l[$k] = __( 'Main Navigation Bar', 'bioship' );
 $f = 'bioship_skeleton_main_menu_open';		$p[$k][$f] = 0;		$l[$f] = __( 'Main Menu Wrap Open', 'bioship' );
 $f = 'bioship_skeleton_main_menu';				$p[$k][$f] = 2;		$l[$f] = __( 'Superfish Dropdown Main Menu', 'bioship' );
 $f = 'bioship_skeleton_sidebar_button'; 		$p[$k][$f] = 4;		$l[$f] = __( 'Mobile Button for Sidebar', 'bioship' );
 $f = 'bioship_skeleton_main_menu_button'; 		$p[$k][$f] = 6;		$l[$f] = __( 'Mobile Button for Main Menu', 'bioship' );
 $f = 'bioship_skeleton_subsidebar_button';		$p[$k][$f] = 8;		$l[$f] = __( 'Mobile Button for Subsidebar', 'bioship' );
 $f = 'bioship_skeleton_main_menu_close';		$p[$k][$f] = 10;	$l[$f] = __( 'Main Menu Wrap Close', 'bioship' );

$k = 'bioship_after_navbar';					$s[$i][$j++] = $k; 	$l[$k] = __( 'After Main NavBar', 'bioship' );
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 0;
 $f = 'bioship_skeleton_navbar_banner';			$p[$k][$f] = 4;		$l[$f] = __( 'Full Width Navbar Banner', 'bioship' );
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 8;

// --------------------
// === Main Sidebar ===
// --------------------
/* /sidebar/{sidebar}.php */ 					$j = 0;	$i++;		$l[$i] = __( 'Primary Sidebar', 'bioship' );
$k = 'bioship_before_sidebar';					$s[$i][$j++] = $k;	$l[$k] = __( 'Before Primary Sidebar', 'bioship' );
 $f = 'bioship_skeleton_sidebar_open';			$p[$k][$f] = 5;		$l[$f] = __( 'Primary Sidebar Wrap Open', 'bioship' );
##			 * SIDEBAR *											## see bioship_get_sidebar in skull.php
$k = 'bioship_sidebar';							$s[$i][$j++] = $k;	$l[$k] = __( 'Sidebar Widget Template', 'bioship' );
	$t[$k] = 'sidebar/*.php';
$k = 'bioship_after_sidebar';					$s[$i][$j++] = $k;	$l[$k] = __( 'After Primary Sidebar', 'bioship' );
 $f = 'bioship_skeleton_sidebar_close';			$p[$k][$f] = 5;		$l[$f] = __( 'Primary Sidebar Wrap Close', 'bioship' );

// -------------------
// === Sub Sidebar ===
// -------------------
/* /sidebar/{subsidebar}.php */ 				$j = 0;	$i++;		$l[$i] = __( 'Subsidiary Sidebar', 'bioship' );
$k = 'bioship_before_subsidebar';				$s[$i][$j++] = $k;	$l[$k] = __( 'Before Subsidiary Sidebar', 'bioship' );
 $f = 'bioship_skeleton_subsidebar_open';		$p[$k][$f] = 5;		$l[$f] = __( 'Subsidiary Sidebar Wrap Open', 'bioship' );
##			* SUBSIDEBAR *											## see bioship_get_sidebar in skull.php
$k = 'bioship_subsidebar';						$s[$i][$j++] = $k;	$l[$k] = __( 'SubSidebar Widget Template', 'bioship' );
	$t[$k] = 'sidebar/sub*.php';
$k = 'bioship_after_subsidebar';				$s[$i][$j++] = $k;	$l[$k] = __( 'After Subsidiary Sidebar', 'bioship' );
 $f = 'bioship_skeleton_subsidebar_close'; 		$p[$k][$f] = 5;		$l[$f] = __( 'Subsidiary Sidebar Wrap Close', 'bioship' );


// --------------------
// === Content Area ===
// --------------------
/* index.php */ 								$j = 0;	$i++;		$l[$i] = __( 'Start Content Area', 'bioship' );
$k = 'bioship_before_content';					$s[$i][$j++] = $k;	$l[$k] = __( 'Before Content Area', 'bioship' );
##		* Content Sidebar *  		 			$p[$k][$f] = 5;		## 'Before Content Sidebar' default position
 $f = 'bioship_skeleton_content_open';			$p[$k][$f] = 10;	$l[$f] = __( 'Content Area Wrap Open', 'bioship' );
$k = 'bioship_front_page_top'; $c[$k] = 'f';	$s[$i][$j++] = $k;	$l[$k] = __( 'Frontpage Only Top', 'bioship' );
 $f = 'bioship_skeleton_front_page_content';	$p[$k][$f] = 5;		$l[$f] = __( 'Frontpage Only Content', 'bioship' );
$k = 'bioship_home_page_top'; $c[$k] = 'h';		$s[$i][$j++] = $k;	$l[$k] = __( 'Home (Blog) Only Top', 'bioship' );
 $f = 'bioship_skeleton_home_page_content'; 	$p[$k][$f] = 5;		$l[$f] = __( 'Home (Blog) Only Content', 'bioship' );
$k = 'bioship_before_loop';						$s[$i][$j++] = $k;	$l[$k] = __( 'Before Any Loop', 'bioship' );
 $f = 'bioship_skeleton_breadcrumbs';			$p[$k][$f] = 5;		$l[$f] = __( 'Navigation Breadcrumbs', 'bioship' );
$k = 'bioship_before_archive'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Any Archive Loop', 'bioship' );
$k = 'bioship_before_category'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Category Archive Loop', 'bioship' );
$k = 'bioship_before_taxonomy'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Taxonomy Archive Loop', 'bioship' );
$k = 'bioship_before_tags'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'Before Tag Archive Loop', 'bioship' );
$k = 'bioship_before_author'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'Before Author Archive Loop', 'bioship' );
 $f = 'bioship_skeleton_author_bio_top';		$p[$k][$f] = 5;		$l[$f] = __( 'Author Archive Top Bio', 'bioship' );
$k = 'bioship_before_date'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'Before Date Archive Loop', 'bioship' );

// ------------------------
// === Start Entry Loop ===
// ------------------------
/* content/content.php */						$j = 0;	$i++;		$l[$i] = __( 'Start Entry Loop', 'bioship' );
$k = 'bioship_before_entry'; 					$s[$i][$j++] = $k;  $l[$k] = __( 'Before Entry', 'bioship' ); // (no default)
$k = 'bioship_media_handler'; 					$s[$i][$j++] = $k;	$l[$k] = __( 'Media Handler Action', 'bioship' );
 $f = 'bioship_skeleton_media_handler';			$p[$k][$f] = 5;		$l[$f] = __( 'Attachment Media Handler', 'bioship' );

// --- Entry Header ---
$k = 'bioship_entry_header';					$s[$i][$j++] = $k;	$l[$k] = __( 'Entry Header Hook', 'bioship' );
 $f = 'bioship_skeleton_entry_header_open';	 	$p[$k][$f] = 0;		$l[$f] = __( 'Entry Header Wrap Open', 'bioship' );
 $f = 'bioship_skeleton_entry_header_title';	$p[$k][$f] = 2;		$l[$f] = __( 'Post/Page Title', 'bioship' );
 $f = 'bioship_skeleton_entry_header_subtitle'; $p[$k][$f] = 4;		$l[$f] = __( 'Post/Page SubTitle', 'bioship' );
 $f = 'bioship_skeleton_entry_header_meta';	 	$p[$k][$f] = 6;		$l[$f] = __( 'Entry Meta Top', 'bioship' );
 $f = 'bioship_skeleton_entry_header_close'; 	$p[$k][$f] = 10;	$l[$f] = __( 'Entry Header Wrap Close', 'bioship' );

// --- Thumbnail ---
$k = 'bioship_thumbnail';						$s[$i][$j++] = $k;  $l[$k] = __( 'Thumbnail or Featured Image', 'bioship' );
 $f = 'bioship_skeleton_echo_thumbnail';		$p[$k][$f] = 5;		$l[$f] = __( 'Echo Thumbnail', 'bioship' );
$k = 'bioship_before_thumbnail';				$s[$i][$j++] = $k;	$l[$k] = __( 'Before Thumbnail', 'bioship' ) . ' *';
//		*** Thumbnail ***											// before/after hooks only fired if thumbnail content //
$k = 'bioship_echo_thumbnail'; $t[$k] = '';		$s[$i][$j++] = $k;	$l[$k] = __( 'Thumbnail', 'bioship' );
$k = 'bioship_after_thumbnail';					$s[$i][$j++] = $k;	$l[$k] = __( 'After Thumbnail', 'bioship') . ' *';

// -------------------------------------------
// === Excerpt for all multi-post Displays ===
// -------------------------------------------
/* not is_singular() */							$j = 0;	$i++;		$l[$i] = __( 'Post Excerpts', 'bioship' );
$k = 'bioship_before_excerpt'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Excerpt', 'bioship' );	// (no default)
$k = 'bioship_the_excerpt'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'Main Excerpt', 'bioship' );
 $f = 'bioship_skeleton_echo_the_excerpt';		$p[$k][$f] = 5;		$l[$f] = __( 'Echo the Excerpt', 'bioship' );
// 		*** the_excerpt() ***							 			// WordPress core function call //
$k = 'bioship_excerpt'; $c[$k] = 'a';			$s[$i][$j++] = $k;	$l[$k] = __( 'Excerpt Content', 'bioship' );
	$t[$k] = 'the_excerpt()';
$k = 'bioship_after_excerpt'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Excerpt', 'bioship' ); // (no default)


$k = 'bioship_entry_footer'; 					$s[$i][$j++] = $k;	$l[$k] = __( 'Entry Footer', 'bioship' );
 $f = 'bioship_skeleton_entry_footer_open';		$p[$k][$f] = 0;		$l[$f] = __( 'Bottom Meta Open', 'bioship' );
 $f = 'bioship_skeleton_entry_footer_meta';		$p[$k][$f] = 6;		$l[$f] = __( 'Entry Meta Bottom', 'bioship' );
 $f = 'bioship_skeleton_entry_footer_close';	$p[$k][$f] = 10;	$l[$f] = __( 'Bottom Meta Close', 'bioship' );


//			 ***** OR *****

// =========================================
// === Full Content for Posts/Pages/CPTs ===
// =========================================
/* is_singular() */								$j = 0;	$i++;		$l[$i] = __( 'Singular Content', 'bioship' );
$k = 'bioship_before_singular'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Singular Content', 'bioship' );
 $f = 'bioship_skeleton_breadcrumbs';			$p[$k][$f] = 5;		$l[$k] = __( 'Breadcrumbs', 'bioship' );

// --- Author Bio (top) ---
$k = 'bioship_author_bio_top'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'Author Bio Top Position', 'bioship' );
 $k = 'bioship_before_author_bio'; $c[$k]='s';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Author Bio', 'bioship' ) . ' *';
//    	*** Author Bio Box ***										// before/after hooks only fired if Bio content //
 $k = 'bioship_author_bio'; $c[$k] = 's';		$s[$i][$j++] = $k;	$l[$k] = __( 'Author Bio Content','bioship');
 	$t[$k] = 'content/author-bio.php';
 $k = 'bioship_after_author_bio'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'After Author Bio', 'bioship' ) . ' *';

// === MAIN CONTENT ===
$k = 'bioship_before_the_content'; $c[$k]='s';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before the Content', 'bioship' ); // (no default)
$k = 'bioship_the_content'; $c[$k] = 's';		$s[$i][$j++] = $k; 	$l[$k] = __( 'Main Content', 'bioship' );
 $f = 'bioship_skeleton_echo_the_content';		$p[$k][$f] = 5;		$l[$f] = __( 'Echo Main Content', 'bioship' );
//		  *** the_content() ***	 									// WordPress core function call //
$k = 'bioship_content'; $c[$k] = 's';			$s[$i][$j++] = $k;	$l[$k] = __( 'Main Content', 'bioship' );
	$t[$k] = 'the_content()';
$k = 'bioship_after_the_content'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'After the Content','bioship'); // (no default)

// Entry Footer (exact duplicate of excerpt entry footer above)

// --- Author Bio (bottom) ---
$k = 'bioship_author_bio_bottom'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'Author Bio Bottom Position', 'bioship' );
// $k =	'bioship_before_author_bio';								// no need to redeclare (duplicate hook) //
// 	  	 *** Author Bio Box ***										// before/after hooks only fired if Bio content //
// $k = 'bioship_after_author_bio'; 								// no need to redeclare (duplicate hook) //

$k = 'bioship_after_singular'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'After Singular Content', 'bioship' );

// --- Comments ---
/* content/comments.php */
// $k = 'bioship_comments';											// see bioship_comments in skeleton.php
$k = 'bioship_before_comments'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'Before Comments', 'bioship' );
// 		*** Comments ***
$k = 'bioship_comments'; $c[$k] = 's';			$s[$i][$j++] = $k;	$l[$k] = __( 'Comment Content', 'bioship' );
	$t[$k] = 'content/comments.php';
$k = 'bioship_after_comments'; $c[$k] = 's';	$s[$i][$j++] = $k;	$l[$k] = __( 'After Comments', 'bioship' );
// =================================
// END SINGLE CONTENT
// ------------------
// END ENTRY LOOP

// ======================
// === End Entry Loop ===
// ======================
												$j = 0;	$i++;		$l[$i] = __( 'End Entry Loop', 'bioship' );
$k = 'bioship_after_entry';						$s[$i][$j++] = $k;	$l[$k] = __( 'After Entry', 'bioship' ); // (no default)
$k = 'bioship_page_navi';						$s[$i][$j++] = $k;	$l[$k] = __( 'Page Navigation', 'bioship' );
 $f = 'bioship_skeleton_page_navigation';		$p[$k][$f] = 5;		$l[$f] = __( 'Page Navigation', 'bioship' );
$k = 'bioship_after_date'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Date Archive Loop', 'bioship' );
$k = 'bioship_after_author'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Author Archive Loop', 'bioship' );
 $f = 'bioship_skeleton_author_bio_bottom';		$p[$k][$f] = 5;		$l[$f] = __( 'Author Archive Bottom Bio', 'bioship' );
$k = 'bioship_after_tags'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Tag Archive Loop', 'bioship' );
$k = 'bioship_after_taxonomy'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'After Taxonomy Archive Loop', 'bioship' );
$k = 'bioship_after_category'; $c[$k] = 'a';	$s[$i][$j++] = $k;	$l[$k] = __( 'After Category Archive Loop', 'bioship' );
$k = 'bioship_after_archive'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Any Archive Loop', 'bioship' );
$k = 'bioship_after_loop'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Any Loop', 'bioship' ); // (no default)

$k = 'bioship_front_page_bottom'; $c[$k] = 'f';	$s[$i][$j++] = $k;	$l[$k] = __( 'Frontpage Only Top', 'bioship' );
 $f = 'bioship_skeleton_front_page_footnote';	$p[$k][$f] = 5;		$l[$f] = __( 'Frontpage Only Content', 'bioship' );
$k = 'bioship_home_page_bottom'; $c[$k] = 'h';	$s[$i][$j++] = $k;	$l[$k] = __( 'Home (Blog) Only Top', 'bioship' );
 $f = 'skeleton_home_page_footnote';			$p[$k][$f] = 5;		$l[$f] = __( 'Home (Blog) Only Content', 'bioship' );

$k = 'bioship_after_content'; $c[$k] = 'a';		$s[$i][$j++] = $k;	$l[$k] = __( 'After Content', 'bioship' ); // (no default)
 $f = 'bioship_skeleton_content_close';			$p[$k][$f] = 0;		$l[$f] = __( 'Content Wrap Close', 'bioship' );
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 2;
##		* Content Sidebar *						 			  5		## 'Below Content Sidebar' Default Position
// ----------------------------
// END SIDEBAR AND CONTENT AREA

// --------------
// === Footer ===
// --------------
/* footer.php */								$j = 0;	$i++;		$l[$i] = __( 'Footer', 'bioship' );
$k = 'bioship_before_footer';					$s[$i][$j++] = $k;	$l[$k] = __( 'Before Footer', 'bioship' );
 $f = 'bioship_skeleton_footer_banner';			$p[$k][$f] = 5;		$l[$f] = __( 'Full Width Footer Banner', 'bioship' );
 $f = 'bioship_skeleton_echo_clear_div';		$p[$k][$f] = 10;
// * wp_footer *
$k = 'bioship_footer';							$s[$i][$j++] = $k;	$l[$k] = __( 'Main Footer', 'bioship' );
 $f = 'bioship_skeleton_footer_open';			$p[$k][$f] = 0;		$l[$f] = __( 'Footer Wrap Open', 'bioship' );
 $f = 'bioship_skeleton_footer_extras'; 		$p[$k][$f] = 2;		$l[$f] = __( 'Footer Extra HTML Area', 'bioship' );
 $f = 'bioship_skeleton_footer_widgets';		$p[$k][$f] = 4;		$l[$f] = __( 'Footer Widget Area', 'bioship' );
 $f = 'bioship_skeleton_footer_nav';			$p[$k][$f] = 6;		$l[$f] = __( 'Footer Menu', 'bioship' );
 $f = 'bioship_skeleton_footer_credits'; 		$p[$k][$f] = 8;		$l[$f] = __( 'Site Credits', 'bioship' );
 $f = 'bioship_skeleton_footer_close';			$p[$k][$f] = 10;	$l[$f] = __( 'Footer Wrap Close', 'bioship' );
$k = 'bioship_before_footer_widgets';			$s[$i][$j++] = $k;	$l[$k] = __( 'Before Footer Widget', 'bioship' ); // (no default)
	## * FOOTER SIDEBARS *									## /* sidebar/footer.php */
$k = 'bioship_footer_widgets'; 					$s[$i][$j++] = $k;	$l[$k] = __( 'Footer Widget Area Template', 'bioship' );
	$t[$k] = 'sidebar/footer.php';
$k = 'bioship_after_footer_widgets';			$s[$i][$j++] = $k;	$l[$k] = __( 'After Footer Widget', 'bioship' ); // (no default)
$k = 'bioship_after_footer';					$s[$i][$j++] = $k;	$l[$k] = __( 'After Footer', 'bioship' );
 $f = 'bioship_skeleton_bottom_banner';			$p[$k][$f] = 5;		$l[$f] = __( 'Full Width Bottom Banner', 'bioship' );

// ---------------------
// === End Container ===
// ---------------------
/* footer.php */								$j = 0;	$i++;		$l[$i] = __( 'End Wrap Container', 'bioship' );
$k = 'bioship_container_close';					$s[$i][$j++] = $k;	$l[$k] = __( 'Main Container Close', 'bioship' );
 $f = 'bioship_skeleton_wrapper_close';			$p[$k][$f] = 5;		$l[$f] = __( 'Main Container Wrap Close', 'bioship' );
$k = 'bioship_after_container';					$s[$i][$j++] = $k; 	$l[$k] = __( 'After Wrap Container', 'bioship' );

// </BODY>
// </HTML>

// --- set global vthemehooks array ---
// 1.8.5: give back long names to our short variables
// 1.9.0: store all values in a single array
// 2.0.9: added template file and page elements
global $vthemehooks;
if ( !isset( $vthemehooks ) ) {
	$vthemehooks = array();
}
$vthemehooks['sections'] = $s;
unset( $s );
$vthemehooks['functions'] = $p;
unset( $p );
$vthemehooks['labels'] = $l;
unset( $l );
$vthemehooks['remove'] = array();
$vthemehooks['templates'] = $t;
unset( $t );
$vthemehooks['elements'] = $e;
unset( $e );

// --- single / archive contexts ---
// 2.0.9: add action contexts (single / archive)
// 2.2.0: fix to single equals conditions
foreach ( $c as $k => $v ) {
	if ( 'a' == $v ) {
		$v = 'archive';
	} elseif ( 's' == $v ) {
		$v = 'single';
	} elseif ( 'f' == $v ) {
		$v = 'front';
	} elseif ( 'h' == $v ) {
		$v = 'home';
	}
	$vthemehooks['contexts'][$k] = $v;
}
unset( $c );

// --- filter theme hooks array ---
// 2.0.9: added global hooks array filter
// 2.1.1: added function_exists check (for docs.php loading)
if ( function_exists( 'apply_filters' ) ) {
	$vthemehooks = apply_filters( 'skeleton_theme_hooks', $vthemehooks );
}

// --- create all hook arrays ---
// 1.8.5: loop sections to create hook arrays
$hooks = array();
$i = 0;
foreach ( $vthemehooks['sections'] as $layoutsection ) {
	foreach ( $layoutsection as $hook ) {

		// --- create simplified hook list ---
		$vthemehooks['hooks'][$i] = $hook;

		// --- create Hybrid Hooks array ---
		// created by stripping skeleton_ prefix (added back by Hybrid Hook filter)
		// 2.0.5: strip bioship_ prefix not skeleton_ prefix
		$vthemehooks['hybrid'][$i] = substr( $hook, strlen( 'bioship_' ), strlen( $hook ) );
		$i++;
	}
}


// ------------
// Special Info
// ------------
// TODO: define further info for layout manager?
// - Element Style References
// - Content Sidebars Information

// ---------------------
// Beaver Themer Support
// ---------------------
// 2.0.9: added basic Beaver Themer support
// 2.1.1: added function_exists check (for docs.php loading)
if ( !function_exists( 'bioship_beaver_builder_support' ) && function_exists( 'add_action' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'after_setup_theme', 'bioship_beaver_builder_support' );

 function bioship_beaver_builder_support() {
	add_theme_support( 'fl-theme-builder-headers' );
	add_theme_support( 'fl-theme-builder-footers' );
	add_theme_support( 'fl-theme-builder-parts' );
 }
}

// --------------------------
// Beaver Themer Parts Filter
// --------------------------
// (creates a hook array in Beaver Themer format)
// 2.1.1: added function_exists check (for docs.php loading)
if ( !function_exists( 'bioship_beaver_themer_register_parts' ) && function_exists( 'add_filter' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_filter( 'fl_theme_builder_part_hooks', 'bioship_beaver_themer_register_parts' );

 function bioship_beaver_themer_register_parts() {
	global $vthemehooks;
	$beaverdata = array();
	foreach ( $vthemehooks['sections'] as $section => $hooks ) {
		if ( count( $hooks ) > 0 ) {
			$actionhooks = array();
			foreach ( $hooks as $hook ) {
				$actionhooks[$hook] = $vthemehooks['labels'][$hook];
			}
			$beaverdata[] = array(
				'label' => $vthemehooks['labels'][$section],
				'hooks' => $actionhooks,
			);
		}
	}
	// 2.0.9: add debug line for Beaver Themer hooks
	// 2.2.0: moved internally to prevent calling twice
	if ( defined( 'THEMEDEBUG' ) && THEMEDEBUG ) {
		bioship_debug( "Beaver Themer Hooks", $beaverdata );
	}
	return $beaverdata;
 }
}

// -------------------------------------------
// Beaver Themer Header and Footer Integration
// -------------------------------------------
// 2.1.1: added function_exists check (for docs.php loading)
if ( !function_exists( 'bioship_beaver_themer_headers_footers' ) && function_exists( 'add_action' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'wp', 'bioship_beaver_themer_headers_footers' );

 function bioship_beaver_themer_headers_footers() {

 	if ( !class_exists( 'FLThemeBuilderLayoutData' ) ) {
 		return;
 	}

	// 2.2.0: added missing global declaration
	global $vthemehooks;

	// --- Beaver Themer header ---
	$headerids = FLThemeBuilderLayoutData::get_current_page_header_ids();
	if ( !empty( $headerids ) ) {

		// --- to use Beaver Themer header, remove all actions hooked on bioship_header ---
		foreach ( $vthemehooks['functions']['bioship_header'] as $function => $priority ) {
			// --- remove all but open and close wrapper functions ---
			if ( '_open' != ( substr( $function, -5, 5 ) ) && ( '_close' != substr( $function, -6, 6 ) ) ) {
				remove_action( 'bioship_header', $function, $priority );
			}
		}

		// --- add header rendering ---
		add_action( 'bioship_header', 'FLThemeBuilderLayoutRenderer::render_header', 5 );
	}

	// --- Beaver Themer footer ---
	$footerids = FLThemeBuilderLayoutData::get_current_page_footer_ids();
	if ( !empty( $footerids ) ) {

		// --- to use Beaver Themer footer, remove all actions hooked on bioship_footer ---
		foreach ( $vthemehooks['functions']['bioship_footer'] as $function => $priority ) {
			// --- remove all but open and close wrapper functions ---
			if ( '_open' != ( substr( $function, -5, 5 ) ) && ( '_close' != substr( $function, -6, 6 ) ) ) {
				remove_action( 'bioship_footer', $function, $priority );
			}
		}

		// --- add footer rendering ---
		// 2.2.0: fix to mismatched action name
		add_action( 'bioship_footer', 'FLThemeBuilderLayoutRenderer::render_footer', 5 );
	}
 }
}

// -------------------------
// Elementor Location Output
// -------------------------
// 2.2.0: added for calling elementor location output via filter
if ( !function_exists( 'bioship_elementor_location_output' ) ) {

 add_filter( 'bioship_elementor_location_output', 'bioship_elementor_location_output', 10, 2 );

 function bioship_elementor_location_output( $done, $location ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	if ( function_exists( 'elementor_theme_do_location' ) ) {
		$done = elementor_theme_do_location( $location );
	}
	return $done;
 }
}

// ----------------------------
// Register Elementor Locations
// ----------------------------
// ref: https://developers.elementor.com/theme-locations-api/
// 2.1.2: added prototype code for Elementor locations
// 2.1.4: added function_exists check (for docs.php loading)
if ( !function_exists( 'bioship_register_elementor_locations' ) && function_exists( 'add_action' ) ) {

 add_action( 'elementor/theme/register_locations', 'bioship_register_elementor_locations' );

 function bioship_register_elementor_locations( $elementor_theme_manager ) {

	// --- register standard locations ---
	// TODO: test header and footer locations
	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );
	$elementor_theme_manager->register_location( 'single' );
	$elementor_theme_manager->register_location( 'archive' );

	// --- register sidebar locations ---
	// $elementor_theme_manager->register_location(
	//	'sidebar',
	//	array (
	//		'label' => __('Sidebar', 'bioship'),
	//		'multiple' => true,
	//		'edit_in_content' => false,
	//	)
	// );
	// $elementor_theme_manager->register_location(
	//	'subsidebar',
	//	array (
	//		'label' => __('Subsidebar', 'bioship'),
	//		'multiple' => true,
	//		'edit_in_content' => false,
	//	)
	// );

 }
}


// ----------------------
// Debug Output for Hooks
// ----------------------
// 2.0.9: use cleaner debugging function
// 2.1.1: added function_exists check (for docs.php loading)
if ( function_exists( 'bioship_debug' ) ) {
	bioship_debug( "Layout Sections", $vthemehooks['sections'] );
	bioship_debug( "Layout Hooks", $vthemehooks['hooks'] );
	bioship_debug( "Hooked Functions", $vthemehooks['functions'] );
	bioship_debug( "Section / Hook Labels", $vthemehooks['labels'] );
	bioship_debug( "Template Files", $vthemehooks['templates'] );
	bioship_debug( "Page Elements", $vthemehooks['elements'] );
	bioship_debug( "Page Contexts", $vthemehooks['contexts'] );
	bioship_debug( "Hybrid Hooks", $vthemehooks['hybrid'] );
}


// -------------------------------
// === Layout Position Filters ===
// -------------------------------

// Change Layout Positions
// -----------------------
// 1.5.0: you can easily change assigned positions by filtering these values
// eg. swap the subtitle and title on pages to use subtitle as "lead-in" text...
# add_filter('bioship_skeleton_entry_header_title_position', 'custom_title_position');
# add_filter('bioship_skeleton_entry_header_subtitle_position', 'custom_subtitle_position');
# function custom_title_position($position) {
#	if (is_page()) {return 4;} else {return $position;}
# }
# function custom_subtitle_position($position) {
#	if (is_page()) {return 2;} else {return $position;}
# }

// Remove Hooked Layout Section
// ----------------------------
// 1.6.0: allowed to remove a section entirely by setting position filter to -1
// note: this is for advanced usage only and may produce erratic results
// (if removing open and close wrappers, remove both not just one or the other)
// eg. to remove header widgets from pages just return -1
# add_filter('bioship_skeleton_header_nav_position', 'custom_header_nav_position');
# function custom_header_nav_position($position) {
#	if (is_page()) {return -1;} else {return $position;}
# }


// ----------------------------
// === Layout Position List ===
// ----------------------------
// TODO: add any missing positions to list (author bio, breadcrumbs, ... ?)

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
// bioship_skeleton_navbar banner_position
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
