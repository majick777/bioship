<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === SKULL FUNCTIONS ===
 * ...brainzzz brainzzz...
 *
**/

if (!function_exists('add_action')) {exit;}

// ==================
// - SKULL SECTIONS -
// ==================
// - Register Nav Menus -
// - Register Sidebars -
// - Layout Setup -
// - Title Tag -
// - Template Helpers -
// - Theme Setup
// - Enqueue Scripts -
// - Site Icons -
// - Template Tracer -
// ==================


// --------------------------
// === Register Nav Menus ===
// --------------------------
// 2.0.5: check has_nav_menu, add filters and store global setting
if (!function_exists('bioship_register_nav_menus ')) {
 add_action('init', 'bioship_register_nav_menus');
 function bioship_register_nav_menus() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthememenus;

	// TODO: add vthememenus global to documentation

	// Primary Menu
	// ------------
	// 1.5.0: moved template call to skeleton.php
	$vthememenus['primary'] = false;
	if ( (isset($vthemesettings['primarymenu'])) && ($vthemesettings['primarymenu'] == '1') ) {
		register_nav_menus(array('primary' => __('Primary Navigation','bioship')));
		if (has_nav_menu('primary')) {
			$vthememenus['primary'] = bioship_apply_filters('skeleton_menu_primary', true);
		}
	}

	// Secondary Menu
	// --------------
	// note: though created, is not hooked anywhere
	// 1.5.0: moved template call to skeleton.php
	$vthememenus['secondary'] = false;
	if ( (isset($vthemesettings['secondarymenu'])) && ($vthemesettings['secondarymenu'] == '1') ) {
		register_nav_menus(array('secondary' => __('Secondary Navigation','bioship')));
		if (has_nav_menu('secondary')) {
			$vthememenus['secondary'] = bioship_apply_filters('skeleton_menu_secondary', true);
		}
	}

	// Header Menu
	// -----------
	$vthememenus['header'] = false;
	if ( (isset($vthemesettings['headermenu'])) && ($vthemesettings['headermenu'] == '1') ) {
		register_nav_menus(array('header' => __('Header Navigation','bioship')));
		// 2.0.6: fix to function typo causing fatal error (hav_nav_menu)
		if (has_nav_menu('header')) {
			$vthememenus['header'] = bioship_apply_filters('skeleton_menu_header', true);
		}
	}

	// Footer Menu
	// -----------
	$vthememenus['footer'] = false;
	if ( (isset($vthemesettings['footermenu'])) && ($vthemesettings['footermenu'] == '1') ) {
		register_nav_menus(array('footer' => __('Footer Navigation','bioship')));
		if (has_nav_menu('footer')) {
			$vthememenus['footer'] = bioship_apply_filters('skeleton_menu_footer', true);
		}
	}

 }
}

// -------------------------
// === Register Sidebars ===
// -------------------------

// Register Sidebar Helper
// -----------------------
// 1.8.5: added this helper
if (!function_exists('bioship_register_sidebar')) {
 function bioship_register_sidebar($vid, $vsettings, $vclass='') {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.9.0: added class argument for widgets page
	register_sidebar(array(
		'name' => $vsettings['name'],
		'id' => $vid,
		'description' => $vsettings['desc'],
		'class' => $vclass,
		'before_widget' => $vsettings['beforewidget'],
		'after_widget' => $vsettings['afterwidget'],
		'before_title' => $vsettings['beforetitle'],
		'after_title' => $vsettings['aftertitle'],
	) );
 }
}

// Register Sidebars
// -----------------

// 1.9.6: add widget page message regarding lowercase titles meaning inactive
add_action('widgets_admin_page','bioship_widget_page_message');
if (!function_exists('bioship_widget_page_message')) {
 function bioship_widget_page_message() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	$vmessage = __('Note: Inactive Theme Sidebars are listed with lowercase titles. Activate them via Theme Options -&gt; Skeleton -&gt; Sidebars tab', 'bioship');
	echo "<div class='message'>".$vmessage."</div>";
 }
}

// Add Active Widget Sidebars
// --------------------------
if (!function_exists('bioship_widgets_init_active')) {
 // 1.9.8: add active and inactive sidebars with different priorities
 // 2.0.5: move add_action inside for consistency
 add_action('widgets_init', 'bioship_widgets_init_active');
 function bioship_widgets_init_active() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	bioship_widgets_init(true);
 }
}

// Add Inactive Widget Sidebars
// ----------------------------
if (!function_exists('bioship_widgets_init_inactive')) {
 // 1.9.8: add active and inactive sidebars with different priorities
 // 2.0.5: move add_action inside for consistency
 add_action('widgets_init', 'bioship_widgets_init_inactive', 12);
 function bioship_widgets_init_inactive() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	bioship_widgets_init(false);
 }
}

// Init Active or Inactive Widget Sidebars
// ---------------------------------------
// 1.8.5: use register_sidebar abstract to reduce code bloat
if (!function_exists('bioship_widgets_init')) {
 function bioship_widgets_init($vactive=true) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $pagenow, $vthemesettings; $vts = $vthemesettings;

	// Set Sidebar Labels
	// ------------------
	// 1.8.5: set sidebar labels separately first
	// TODO: maybe sidebar labels could be set in hooks.php instead?

	// context sidebars (and subsidebars)
	$vlabels['frontpage']['name'] = __('Frontpage Sidebar','bioship');
	$vlabels['subfrontpage']['name'] = __('Frontpage SubSidebar','bioship');
	$vlabels['frontpage']['desc'] = $vlabels['subfrontpage']['desc'] = __('Shown only on FrontPage','bioship');

	$vlabels['homepage']['name'] = __('Home (Blog) Sidebar','bioship');
	$vlabels['subhomepage']['name'] = __('Home (Blog) SubSidebar','bioship');
	$vlabels['homepage']['desc'] = $vlabels['subhomepage']['desc'] = __('Shown only on Home (Blog) Page','bioship');

	// 1.9.8: remove old unused variable
	$vlabels['archive']['name'] = __('Archive Sidebar','bioship');
	$vlabels['subarchive']['name'] = __('Archive Page SubSidebar','bioship');
	$vlabels['archive']['desc'] = $vlabels['subarchive']['desc'] = __('Shown only on Archive Pages','bioship');

		$vlabels['category']['name'] = __('Category Archives Sidebar','bioship');
		$vlabels['subcategory']['name'] = __('Category Archives SubSidebar','bioship');
		$vlabels['category']['desc'] = $vlabels['subcategory']['desc'] = __('Shown only on Category Archives','bioship');

		$vlabels['taxonomy']['name'] = __('Taxonomy Archives Sidebar','bioship');
		$vlabels['subtaxonomy']['name'] = __('Taxonomy Archives SubSidebar','bioship');
		$vlabels['taxonomy']['desc'] = $vlabels['subtaxonomy']['desc'] = __('Shown only on Taxonomy Archives','bioship');

		$vlabels['tag']['name'] = __('Tag Archives Sidebar','bioship');
		$vlabels['subtag']['name'] = __('Tag Archives SubSidebar','bioship');
		$vlabels['tag']['desc'] = $vlabels['subtag']['desc'] = __('Shown only on Tag Archives','bioship');

		$vlabels['author']['name'] = __('Author Archives Sidebar','bioship');
		$vlabels['subauthor']['name'] = __('Author Archives SubSidebar','bioship');
		$vlabels['author']['desc'] = $vlabels['subauthor']['desc'] = __('Shown only on Author Archives','bioship');

		$vlabels['date']['name'] = __('Date Archives Sidebar','bioship');
		$vlabels['subdate']['name'] = __('Date Archives SubSidebar','bioship');
		$vlabels['date']['desc'] = $vlabels['subdate']['desc'] = __('Shown only on Date Archives','bioship');

	$vlabels['search']['name'] = __('Search Page Sidebar','bioship');
	$vlabels['subsearch']['name'] = __('Search Page SubSidebar','bioship');
	$vlabels['search']['desc'] = $vlabels['subsearch']['desc'] = __('Shown only on Search Pages','bioship');

		$vlabels['notfound']['name'] = __('404 Page Sidebar','bioship');
		$vlabels['subnotfound']['name'] = __('404 Page SubSidebar','bioship');
		$vlabels['notfound']['desc'] = $vlabels['subnotfound']['desc'] = __('Shown only on 404 Not Found Pages','bioship');

	// post / page sidebars
	$vlabels['primary']['name'] = __('Post/Page Sidebar','bioship');
	$vlabels['primary']['desc'] = __( 'Shown for both Pages and Posts','bioship');

	$vlabels['posts']['name'] = __('Posts Sidebar','bioship');
	$vlabels['posts']['desc'] = __('Shown only for Posts','bioship');

	$vlabels['pages']['name'] = __('Pages Sidebar','bioship');
	$vlabels['pages']['desc'] = __('Shown only for Pages','bioship');

		$vlabels['subsidiary']['name'] = __('Post/Page SubSidebar','bioship');
		$vlabels['subsidiary']['desc'] = __( 'Shown for both Pages and Posts','bioship');

		$vlabels['subpost']['name'] = __( 'Posts SubSidebar','bioship');
		$vlabels['subpost']['desc'] = __( 'Subsidiary Sidebar for Posts only','bioship');

		$vlabels['subpage']['name'] = __( 'Pages SubSidebar','bioship');
		$vlabels['subpage']['desc'] = __( 'Subsidiary Sidebar for Pages only','bioship');

	// header / footer sidebars
	$vlabels['header-widget-area']['name'] = __('Header Widget Area','bioship');
	$vlabels['header-widget-area']['desc'] = __( 'Header Widget Area', 'bioship');
	$vlabels['footer-widget-area-1']['name'] = __('First Footer Widget Area','bioship');
	$vlabels['footer-widget-area-1']['desc'] = __( 'The first footer widget area', 'bioship');
	$vlabels['footer-widget-area-2']['name'] = __('Second Footer Widget Area','bioship');
	$vlabels['footer-widget-area-2']['desc'] = __( 'The second footer widget area','bioship');
	$vlabels['footer-widget-area-3']['name'] = __('Third Footer Widget Area','bioship');
	$vlabels['footer-widget-area-3']['desc'] = __( 'The third footer widget area','bioship');
	$vlabels['footer-widget-area-4']['name'] = __('Fourth Footer Widget Area','bioship');
	$vlabels['footer-widget-area-4']['desc'] = __( 'The fourth footer widget area','bioship');


	// 1.8.5: set default widget wrapper settings for all sidebars
	$vsidebarwrappers = array();
	$vsidebarwrappers['beforewidget'] = '<div id="%1$s" class="widget-container %2$s">';
	$vsidebarwrappers['afterwidget'] = '</div>';
	$vsidebarwrappers['beforetitle'] = '<h3 class="widget-title">';
	$vsidebarwrappers['aftertitle'] = '</h3>';
	$vsidebarwrappers = bioship_apply_filters('skeleton_sidebar_widget_wrappers',$vsidebarwrappers);

	// loop labels and add sidebar wrappers
	foreach ($vlabels as $vid => $vsettings) {
		$vlabels[$vid]['beforewidget'] = $vsidebarwrappers['beforewidget'];
		$vlabels[$vid]['afterwidget'] = $vsidebarwrappers['afterwidget'];
		$vlabels[$vid]['beforetitle'] = $vsidebarwrappers['beforetitle'];
		$vlabels[$vid]['aftertitle'] = $vsidebarwrappers['aftertitle'];
	}
	// 1.8.5: allow for sidebar label/setting filtering
	$vlabels = bioship_apply_filters('skeleton_sidebar_settings', $vlabels);


	// check Sidebar Options
	// ---------------------
	// 1.8.5: reorder all sidebars for improved display order
	// 1.9.0: added [off] to inactive sidebar labels
	// 1.9.5: removed [off] (new styling and lowercase is sufficient)
	// $voff = '['.__('off','bioship').'] ';
	$vsidebarson = array(); $vsidebarsoff = array();

	// Header Area Sidebar
	// -------------------
	$vid = 'header-widget-area'; // individual setting
	if ($vts['headersidebar'] == '1') {$vsidebarson[] = $vid;}
	else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

	// Front / Home Sidebars and SubSidebars
	// -------------------------------------
	$vsidebartypes = array('frontpage','homepage');
	foreach ($vsidebartypes as $vid) {
		if ($vts['sidebars'][$vid] == '1') {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
		$vid = 'sub'.$vid;
		if ($vts['subsidebars'][$vid] == '1') {$vsidebaron[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
	}

	// Post/Page Sidebars
	// ------------------

		// Main Primary Sidebar
		// --------------------
		$vid = 'primary';
		if ($vts['sidebarmode'] == 'unified') {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

			// Main Subsidiary Sidebar
			// -----------------------
			$vid = 'subsidiary';
			if ($vts['subsidiarysidebar'] == 'unified') {$vsidebarson[] = $vid;}
			else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

		// Posts Sidebar
		// -------------
		$vid = 'posts';
		if ( ($vts['sidebarmode'] == 'postsonly') || ($vts['sidebarmode'] == 'dual') ) {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

			// Subsidiary Posts Sidebar
			// -----------------------
			$vid = 'subpost';
			if ( ($vts['subsidiarysidebar'] == 'postsonly') || ($vts['subsidiarysidebar'] == 'dual') ) {$vsidebarson[] = $vid;}
			else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

		// Pages Sidebar
		// -------------
		$vid = 'pages';
		if ( ($vts['sidebarmode'] == 'pagesonly') || ($vts['sidebarmode'] == 'dual') ) {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

			// Subsidiary Pages Sidebar
			// -----------------------
			$vid = 'subpage';
			if ( ($vts['subsidiarysidebar'] == 'pagesonly') || ($vts['subsidiarysidebar'] == 'dual') ) {$vsidebarson[] = $vid;}
			else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}

	// Archive / Search Sidebars and SubSidebars
	// -------------------------------------------
	$vsidebartypes = array('archive','search','notfound');
	foreach ($vsidebartypes as $vid) {
		if ($vts['sidebars'][$vid] == '1') {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
		$vid = 'sub'.$vid;
		if ($vts['subsidebars'][$vid] == '1') {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
	}

	// Footer Area Sidebars
	// --------------------
	for ($vi = 1; $vi < 5; $vi++) {
		$vid = 'footer-widget-area-'.$vi;
		if ($vts['footersidebars'] > ($vi-1)) {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
	}

	// Archive Specific Sidebars and SubSidebars
	// -----------------------------------------
	$vsidebartypes = array('category','taxonomy','tag','author','date');
	foreach ($vsidebartypes as $vid) {
		if ($vts['sidebars'][$vid] == '1') {$vsidebarson[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
		$vid = 'sub'.$vid;
		if ($vts['subsidebars'][$vid] == '1') {$vsidebaron[] = $vid;}
		else {$vsidebarsoff[] = $vid; $vlabels[$vid]['name'] = strtolower($vlabels[$vid]['name']);}
	}

	// Register Sidebars
	// -----------------
	$vallwidgets = wp_get_sidebars_widgets();
	// print_r($vallwidgets);

	// 1.8.5: split on and off declarations for display
	// 1.9.8: declare active and inactive with different priorities
	if ( ($vactive) && (count($vsidebarson) > 0) ) {
		foreach ($vsidebarson as $vsidebarid) {
			if ( (is_admin()) && (is_active_sidebar($vsidebarid)) ) {
				// add widget count to sidebar label
				$vwidgetcount = count($vallwidgets[$vsidebarid]);
				$vlabels[$vsidebarid]['name'] .= ' ('.$vwidgetcount.')';
			}
			bioship_register_sidebar($vsidebarid,$vlabels[$vsidebarid],'on');
		}
	}

	if ( (!$vactive) && (count($vsidebarsoff) > 0) ) {
		foreach ($vsidebarsoff as $vsidebarid) {
			if ( (is_admin()) && (is_active_sidebar($vsidebarid)) ) {
				// add widget count to sidebar label
				$vwidgetcount = count($vallwidgets[$vsidebarid]);
				$vlabels[$vsidebarid]['name'] .= ' ('.$vwidgetcount.')';
			}
			// 1.9.9: for customizer advanced options page
			if ( ($pagenow == 'customize.php') || (is_customize_preview()) ) {
				if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'advanced') ) {
				 	bioship_register_sidebar($vsidebarid,$vlabels[$vsidebarid],'off');
				}
			} else {bioship_register_sidebar($vsidebarid,$vlabels[$vsidebarid],'off');}
		}
	}

 }
}

// --------------
// Config Helpers
// --------------

// Widget Shortcodes
// -----------------
// override to maybe remove shortcode filter from Widget Text
$vwidgettextshortcodes = bioship_apply_filters('muscle_widget_text_shortcodes', true);
if (!$vwidgettextshortcodes) {remove_filter('widget_text', 'do_shortcode');}

// add shortcode filter to Widget Titles (and maybe override)
$vwidgettextshortcodes = bioship_apply_filters('muscle_widget_title_shortcodes', true);
if ($vwidgettextshortcodes) {add_filter('widget_title', 'do_shortcode');}

// LAYOUT LOADER
// -------------
// 1.8.5: calls all layout global setup functions
// 1. so layout can is filtered and passed to grid.php
// 2. sidebars are precalculated for body tag classes
add_action('wp','bioship_set_layout');
if (!function_exists('bioship_set_layout')) {
 function bioship_set_layout() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemelayout, $vthemesidebars, $vthemedisplay, $vthemeoverride;

	// 1.9.5: initialize theme display and templating overrides
	if (is_singular()) {global $post; $vpostid = $post->ID;} else {$vpostid = '';}
	if (!function_exists('bioship_muscle_get_display_overrides')) {$vthemedisplay = array();}
	else {$vthemedisplay = bioship_muscle_get_display_overrides($vpostid);}
	if (!function_exists('bioship_muscle_get_templating_overrides')) {$vthemeoverride = array();}
	else {$vthemeoverride = bioship_muscle_get_templating_overrides($vpostid);}

	// setup all layout globals
 	bioship_set_page_context();
 	bioship_set_max_width();
 	bioship_set_grid_columns();
	bioship_set_sidebar_layout();
	bioship_set_sidebar_columns();
	bioship_set_subsidebar_columns();
	bioship_set_content_width();

	if (THEMEDEBUG) {
		echo "<!-- Theme Layout: "; print_r($vthemelayout); echo " -->";
		$vsidebars = $vthemesidebars; unset($vsidebars['output']);
		echo "<!-- Theme Sidebars: "; print_r($vsidebars); echo " -->";
		if ($vthemesidebars['sidebar']) {echo "<!-- Sidebar -->";}
		if ($vthemesidebars['subsidebar']) {echo "<!-- SubSidebar -->";}
	}
 }
}

// set Page Context
// ----------------
// 1.8.5: added this page context helper
// TODO: maybe match up more options like in /wp-includes/template-loader.php
if (!function_exists('bioship_set_page_context')) {
 function bioship_set_page_context() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 1.9.0: use new themelayout global
	global $vthemelayout;

	// get and set the page context
	$vsubpagecontext = '';
	if (is_front_page()) {$vpagecontext = 'frontpage';}
	elseif (is_home()) {$vpagecontext = 'home';}
	elseif (is_404()) {$vpagecontext = '404';}
	elseif (is_search()) {$vpagecontext = 'search';}
	elseif (is_singular()) {$vpagecontext = get_post_type();}
	elseif (is_archive()) {
		$vpagecontext = 'archive';
		if (is_tag()) {$vsubpagecontext = 'tag';}
		elseif (is_category()) {$vsubpagecontext = 'category';}
		elseif (is_tax()) {$vsubpagecontext = 'taxonomy';}
		elseif (is_author()) {$vsubpagecontext = 'author';}
		elseif (is_date()) {$vsubpagecontext = 'date';}
	} else {$vpagecontext = '';}

	$vthemelayout['pagecontext'] = $vpagecontext;
	$vthemelayout['subpagecontext'] = $vsubpagecontext;
 }
}

// set Layout Max Width
// --------------------
// 1.8.5: added this setup helper
if (!function_exists('bioship_set_max_width')) {
 function bioship_set_max_width() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemesettings, $vthemelayout;
 	$vthemelayout['maxwidth'] = $vthemesettings['layout'];
 	if ($vthemelayout['maxwidth'] == '') {$vthemelayout['maxwidth'] = '960';}

 	$vmaxwidth = bioship_apply_filters('skeleton_layout_width', $vthemelayout['maxwidth']);
 	// 2.0.5: apply absint to filtered value
 	$vmaxwidth = absint($vmaxwidth);
 	if ( ($vmaxwidth) && (is_numeric($vmaxwidth)) ) {
 		// TESTME: maybe set a minimum max-width?
 		if ($vmaxwidth > 319) {$vthemelayout['maxwidth'] = $vmaxwidth;}
 	}
 	return $vthemelayout['maxwidth'];
 }
}

// set Grid Columns
// ----------------
// 2.0.5: added missing function_exists wrapper
if (!function_exists('bioship_set_grid_columns')) {
 function bioship_set_grid_columns() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemesettings, $vthemelayout;

	$vgridcolumns = $vthemesettings['gridcolumns'];
	$vcolumns = bioship_apply_filters('skeleton_grid_columns', $vgridcolumns);
	if ($vcolumns != $vgridcolumns) {
		$vgridvalues = array('twelve','sixteen','twenty','twentyfour'); // valid values
		if (is_numeric($vcolumns)) {$vcolumns = bioship_number_to_word($vcolumns);}
		elseif (is_string($vcolumns)) {
			$vcolumns = bioship_number_to_word(bioship_word_to_number($vcolumns));
		}
		if ( ($vcolumns) && (in_array($vcolumns, $vgridvalues)) ) {$vgridcolumns = $vcolumns;}
	}
	if ($vgridcolumns == '') {$vgridcolumns = 'sixteen';} // fallback default
	$vthemelayout['gridcolumns'] = $vgridcolumns;

	$vthemelayout['numgridcolumns'] = bioship_word_to_number($vgridcolumns);
	// return $vthemelayout['gridcolumns'];

	// 1.9.5: set content grid columns separately
	if (isset($vthemesettings['contentgridcolumns'])) {$vcontentgridcolumns = $vthemesettings['contentgridcolumns'];}
	else {$vcontentgridcolumns = $vgridcolumns;}
	$vcolumns = bioship_apply_filters('skeleton_content_grid_columns',$vcontentgridcolumns);
	if ($vcolumns != $vcontentgridcolumns) {
		$vgridvalues = array('twelve', 'sixteen', 'twenty', 'twentyfour'); // valid values
		if (is_numeric($vcolumns)) {$vcolumns = bioship_number_to_word($vcolumns);}
		elseif (is_string($vcolumns)) {$vcolumns = bioship_number_to_word(bioship_word_to_number($vcolumns));}
		if ( ($vcolumns) && (in_array($vcolumns,$vgridvalues)) ) {$vcontentgridcolumns = $vcolumns;}
	}
	if ($vcontentgridcolumns == '') {$vcontentgridcolumns = 'twentyfour';} // fallback default
	$vthemelayout['contentgridcolumns'] = $vcontentgridcolumns;

	$vthemelayout['numcontentcolumns'] = bioship_word_to_number($vcontentgridcolumns);

 }
}


// set Sidebar Layout
// ------------------
// 1.8.0: added new sidebar templating setup
if (!function_exists('bioship_set_sidebar_layout')) {
 function bioship_set_sidebar_layout() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;

	// 1.9.0: set short names from theme layout global
	$vsidebars = $vthemesidebars['sidebars'];
	$vsidebar = $vthemesidebars['sidebar'];
	$vsubsidebar = $vthemesidebars['subsidebar'];

	// sidebar positions: left, inner left, inner right, right
	$vleftsidebar = ''; $vinnerleftsidebar = ''; $vinnerrightsidebar = ''; $vrightsidebar = '';
	if (is_singular()) {$vposttype = get_post_type();}
	if (!isset($vthemesidebars['sidebars'])) {$vthemesidebars['sidebars'] = array();}

	if ( (isset($vthemesidebars['sidebarcontext'])) || (isset($vthemesidebars['subsidebarcontext'])) ) {
		$vcontext = $vthemesidebars['sidebarcontext']; $vsubcontext = $vthemesidebars['subsidebarcontext'];
		if (THEMEDEBUG) {echo "<!-- Sidebar Context: ".$vcontext." - SubSidebar Context: ".$vsubcontext." -->";}
	}
	else {
		$vcontext = ''; $vsubcontext = '';

		// 1.8.0: get the optional sidebar values (as possibly not set)
		// 1.8.5: changed to multicheck array values
		$vfrontpagesidebar = false; $vhomepagesidebar = false; $vsearchsidebar = false; $vnotfoundsidebar = false;
		$varchivesidebar = false; $vcategorysidebar = false; $vtaxonomysidebar = false;
		$vtagsidebar = false; $vauthorsidebar = false; $vdatesidebar = false;

		$vsets = $vthemesettings['sidebars'];
		if ( (isset($vsets['frontpage'])) && ($vsets['frontpage'] == '1') ) {$vfrontpagesidebar = true;}
		if ( (isset($vsets['homepage'])) && ($vsets['homepage'] == '1') ) {$vhomepagesidebar = true;}
		if ( (isset($vsets['search'])) && ($vsets['search'] == '1') ) {$vsearchsidebar = true;}
		if ( (isset($vsets['notfound'])) && ($vsets['notfound'] == '1') ) {$vnotfoundsidebar = true;}
		if ( (isset($vsets['archive'])) && ($vsets['archive'] == '1') ) {$varchivesidebar = true;}
		if ( (isset($vsets['category'])) && ($vsets['category'] == '1') ) {$vcategorysidebar = true;}
		if ( (isset($vsets['taxonomy'])) && ($vsets['taxonomy'] == '1') ) {$vtaxonomysidebar = true;}
		if ( (isset($vsets['tag'])) && ($vsets['tag'] == '1') ) {$vtagsidebar = true;}
		if ( (isset($vsets['author'])) && ($vsets['author'] == '1') ) {$vauthorsidebar = true;}
		if ( (isset($vsets['date'])) && ($vsets['date'] == '1') ) {$vdatesidebar = true;}

		// get and set the sidebar context
		if ( ($vfrontpagesidebar) && (is_front_page()) ) {$vcontext = 'front';}
		elseif ( ($vhomepagesidebar) && (is_home()) ) {$vcontext = 'home';}
		elseif (is_singular()) {$vcontext = $vposttype;}
		elseif ( ($vnotfoundsidebar) && (is_404()) ) {$vcontext = 'notfound';}
		elseif ( ($vsearchsidebar) && (is_search()) ) {$vcontext = 'search';}
		elseif ( ($vtagsidebar) && (is_tag()) ) {$vcontext = 'tag';}
		elseif ( ($vcategorysidebar) && (is_category()) ) {$vcontext = 'category';}
		elseif ( ($vtaxonomysidebar) && (is_tax()) ) {$vcontext = 'taxonomy';}
		elseif ( ($vauthorsidebar) && (is_author()) ) {$vcontext = 'author';}
		elseif ( ($vdatesidebar) && (is_date()) ) {$vcontext = 'date';}
		elseif (is_archive()) {$vcontext = 'archive';}
		if (THEMEDEBUG) {echo "<!-- Sidebar Context: ".$vcontext." -->";}

		// 1.8.5: repeat same for subsidebar options
		// 1.9.8: fix to first line of subsidebar variable flags
		$vfrontpagesubsidebar = false; $vhomepagesubsidebar = false; $vsearchsubsidebar = false; $vnotfoundsubsidebar = false;
		$varchivesubsidebar = false; $vcategorysubsidebar = false; $vtaxonomysubsidebar = false;
		$vtagsubsidebar = false; $vauthorsubsidebar = false; $vdatesubsidebar = false;
		$vsubsets = $vthemesettings['subsidebars'];

		if ( (isset($vsubsets['frontpage'])) && ($vsubsets['frontpage'] == '1') ) {$vfrontpagesubsidebar = true;}
		if ( (isset($vsubsets['homepage'])) && ($vsubsets['homepage'] == '1') ) {$vhomepagesubsidebar = true;}
		if ( (isset($vsubsets['search'])) && ($vsubsets['search'] == '1') ) {$vsearchsubsidebar = true;}
		if ( (isset($vsubsets['notfound'])) && ($vsubsets['notfound'] == '1') ) {$vnotfoundsubsidebar = true;}
		if ( (isset($vsubsets['archive'])) && ($vsubsets['archive'] == '1') ) {$varchivesubsidebar = true;}
		if ( (isset($vsubsets['category'])) && ($vsubsets['category'] == '1') ) {$vcategorysubsidebar = true;}
		if ( (isset($vsubsets['taxonomy'])) && ($vsubsets['taxonomy'] == '1') ) {$vtaxonomysubsidebar = true;}
		if ( (isset($vsubsets['tag'])) && ($vsubsets['tag'] == '1') ) {$vtagsubsidebar = true;}
		if ( (isset($vsubsets['author'])) && ($vsubsets['author'] == '1') ) {$vauthorsubsidebar = true;}
		if ( (isset($vsubsets['date'])) && ($vsubsets['date'] == '1') ) {$vdatesubsidebar = true;}

		// 1.8.0: set subcontext from context
		// $vsubcontext = 'sub'.$vcontext;
		// 1.8.5: get and set the subsidebar context separately
		if ( ($vfrontpagesubsidebar) && (is_front_page()) ) {$vsubcontext = 'subfront';}
		elseif ( ($vhomepagesubsidebar) && (is_home()) ) {$vsubcontext = 'subhome';}
		elseif (is_singular()) {$vsubcontext = 'sub'.$vposttype;}
		elseif ( ($vnotfoundsubsidebar) && (is_404()) ) {$vsubcontext = 'subnotfound';}
		elseif ( ($vsearchsubsidebar) && (is_search()) ) {$vsubcontext = 'subsearch';}
		elseif ( ($vtagsubsidebar) && (is_tag()) ) {$vsubcontext = 'subtag';}
		elseif ( ($vcategorysubsidebar) && (is_category()) ) {$vsubcontext = 'subcategory';}
		elseif ( ($vtaxonomysubsidebar) && (is_tax()) ) {$vsubcontext = 'subtaxonomy';}
		elseif ( ($vauthorsubsidebar) && (is_author()) ) {$vsubcontext = 'subauthor';}
		elseif ( ($vdatesubsidebar) && (is_date()) ) {$vsubcontext = 'subdate';}
		elseif (is_archive()) {$vsubcontext = 'subarchive';}
		if (THEMEDEBUG) {echo "<!-- SubSidebar Context: ".$vsubcontext." -->";}
	}

	// Get ready for this insane conditional logic stream!

	// get the default sidebar layout and filter
	// 1.8.5: added filter value validation check
	$vsidebarpositions = array('left','right'); // valid values
	$vsidebarposition = $vthemesettings['page_layout'];
	$vcheckposition = bioship_apply_filters('skeleton_sidebar_position', $vsidebarposition);
	if ( (is_string($vcheckposition)) && (in_array($vcheckposition, $vsidebarpositions)) ) {$vsidebarposition = $vcheckposition;}
	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['sidebarposition'])) && ($vthemeoverride['sidebarposition'] != '') ) {$vsidebarposition = $vthemeoverride['sidebarposition'];}
	$vthemesidebars['sidebarposition'] = $vsidebarposition;

	// get the subsidebar layout and filter
	// 1.8.5: added filter value validation check
	$vsubsidebarpositions = array('internal','external','opposite'); // valid values
	$vsubsidebarposition = $vthemesettings['subsidiaryposition']; // internal/external/opposite
	$vchecksubposition = bioship_apply_filters('skeleton_subsidebar_position', $vsubsidebarposition);
	if ( (is_string($vchecksubposition)) && (in_array($vchecksubposition, $vsidebarpositions)) ) {$vsubsidebarposition = $vchecksubposition;}
	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['subsidebarposition'])) && ($vthemeoverride['subsidebarposition'] != '') ) {$vsubsidebarposition = $vthemeoverride['subsidebarposition'];}
	$vthemesidebars['subsidebarposition'] = $vsubsidebarposition;

	// get the default sidebar modes and filter
	// 1.8.5: added filter value validation check
	$vsidebarmodes = array('off','postsonly','pagesonly','unified','dual'); // valid values
	$vsidebarmode = $vthemesettings['sidebarmode'];
	$vcheckmode = bioship_apply_filters('skeleton_sidebar_mode',$vsidebarmode);
	if ( (is_string($vcheckmode)) && (in_array($vcheckmode,$vsidebarmodes)) ) {$vsidebarmode = $vcheckmode;}
	$vthemesidebars['sidebarmode'] = $vsidebarmode;

	$vsubsidebarmode = $vthemesettings['subsidiarysidebar'];
	$vchecksubmode = bioship_apply_filters('skeleton_subsidebar_mode',$vsubsidebarmode);
	if ( (is_string($vchecksubmode)) && (in_array($vchecksubmode,$vsidebarmodes)) ) {$vsubsidebarmode = $vchecksubmode;}
	$vthemesidebars['subsidebarmode'] = $vsubsidebarmode;

	if (THEMEDEBUG) {echo "<!-- Default Sidebar Setup: ".$vsidebarposition." - ".$vsidebarmode." - ".$vsubsidebarposition." - ".$vsubsidebarmode." -->";}

	// check Default Sidebar Conditions
	// --------------------------------
	// default to true so all other sidebar types show
	$vsidebar = true; $vsubsidebar = true;

	if (is_singular()) {
		if ( ($vposttype == 'post') || ($vposttype == 'page') ) {
			if ($vsidebarmode == 'off') {$vsidebar = false;}
			elseif ( ($vsidebarmode == 'postsonly') && ($vposttype == 'page') ) {$vsidebar = false;}
			elseif ( ($vsidebarmode == 'pagesonly') && ($vposttype == 'post') ) {$vsidebar = false;}

			if ($vsubsidebarmode == 'off') {$vsubsidebar = false;}
			elseif ( ($vsubsidebarmode == 'postsonly') && ($vposttype == 'page') ) {$vsubsidebar = false;}
			elseif ( ($vsubsidebarmode == 'pagesonly') && ($vposttype == 'post') ) {$vsubsidebar = false;}
		}
	}

	// Check Sidebar Display Filters
	// -----------------------------

	// back compat: maintain old fullwidth filter name
	$vfullwidth = bioship_apply_filters('skeleton_fullwidth_filter',false);
	if ($vfullwidth) {$vsidebar = false; $vsubsidebar = false;}

	// apply individual sidebar output conditional filters
	$vsidebar = bioship_apply_filters('skeleton_sidebar_output', $vsidebar);
	$vsubsidebar = bioship_apply_filters('skeleton_subsidebar_output', $vsubsidebar);

	if (THEMEDEBUG) {
		echo "<!-- Sidebar States: ";
			if ($vsidebar) {echo "Main Sidebar - ";} else {echo "No Main Sidebar - ";}
			if ($vsubsidebar) {echo "Sub Sidebar";} else {echo "No Sub Sidebar";}
		echo " -->";
	}

	// for no default sidebars (full width) set empty sidebar array but continue to allow overrides
	if ( (!$vsidebar) && (!$vsubsidebar) ) {$vsidebars = array('', '', '', '');}
	else {
		// 2.0.5: set empty sidebar variables
		$vleftsidebar = ''; $vinnerleftsidebar = '';
		$vrightsidebar = ''; $vinnerrightsidebar = '';

		// set Primary Sidebar Template
		// ----------------------------
		if ($vsidebar) {
			if ( ($vcontext == 'post') || ($vcontext == 'page') ) {
				if ($vsidebarmode == 'unified') {
					if ($vsidebarposition == 'left') {$vleftsidebar = 'primary';}
					if ($vsidebarposition == 'right') {$vrightsidebar = 'primary';}
				}
				if ($vcontext == 'page') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'pagesonly') ) {
						if ($vsidebarposition == 'left') {$vleftsidebar = 'page';}
						if ($vsidebarposition == 'right') {$vrightsidebar = 'page';}
					}
				}
				if ($vcontext == 'post') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'postsonly') ) {
						if ($vsidebarposition == 'left') {$vleftsidebar = 'post';}
						if ($vsidebarposition == 'right') {$vrightsidebar = 'post';}
					}
				}
			} else {
				if ($vsidebarposition == 'left') {$vleftsidebar = $vcontext;}
				if ($vsidebarposition == 'right') {$vrightsidebar = $vcontext;}
			}
		}

		// set Subsidiary Sidebar Template
		// -------------------------------
		if ($vsubsidebar) {
			if ( ($vsubcontext == 'subpost') || ($vsubcontext == 'subpage') ) {
				if ($vsidebarmode == 'unified') {
					if ($vsidebarposition == 'left') {
						if ($vsubsidebarposition == 'opposite') {$vrightsidebar = 'subsidebar';}
						if ($vsubsidebarposition == 'internal') {$vinnerleftsidebar = 'subsidebar';}
						if ($vsubsidebarposition == 'external') {$vinnerleftsidebar = $vleftsidebar; $vleftsidebar = 'subsidiary';}
					}
					if ($vsidebarposition == 'right') {
						if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subsidiary';}
						if ($vsubsidebarposition == 'internal') {$vinnerrightsidebar = 'subsidiary';}
						if ($vsubsidebarposition == 'external') {$vinnerrightsidebar = $vrightsidebar; $vrightsidebar = 'subsidiary';}
					}
				}
				if ($vsubcontext == 'subpage') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'pagesonly') ) {
						if ($vsidebarposition == 'left') {
							if ($vsubsidebarposition == 'opposite') {$vrightsidebar = 'subpage';}
							if ($vsubsidebarposition == 'internal') {$vinnerleftsidebar = 'subpage';}
							if ($vsubsidebarposition == 'external') {$vinnerleftsidebar = $vleftsidebar; $vleftsidebar = 'subpage';}
						}
						if ($vsidebarposition == 'right') {
							if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subpage';}
							if ($vsubsidebarposition == 'internal') {$vinnerrightsidebar = 'subpage';}
							if ($vsubsidebarposition == 'external') {$vinnerrightsidebar = $vrightsidebar; $vrightsidebar = 'subpage';}
						}
					}
				}
				if ($vsubcontext == 'subpost') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'postsonly') ) {
						if ($vsidebarposition == 'left') {
							if ($vsubsidebarposition == 'opposite') {$vrightsidebar = 'subpost';}
							if ($vsubsidebarposition == 'internal') {$vinnerleftsidebar = 'subpost';}
							if ($vsubsidebarposition == 'external') {$vinnerleftsidebar = $vleftsidebar; $vleftsidebar = 'subpost';}
						}
						if ($vsidebarposition == 'right') {
							if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subpost';}
							if ($vsubsidebarposition == 'internal') {$vinnerrightsidebar = 'subpost';}
							if ($vsubsidebarposition == 'external') {$vinnerrightsidebar = $vrightsidebar; $vrightsidebar = 'subpost';}
						}
					}
				}
			} else {
				if ($vsidebarposition == 'left') {
					if ($vsubsidebarposition == 'opposite') {$vrightsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'internal') {$vinnerleftsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'external') {$vinnerleftsidebar = $vleftsidebar; $vleftsidebar = $vsubcontext;}
				}
				if ($vsidebarposition == 'right') {
					if ($vsubsidebarposition == 'opposite') {$vleftsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'internal') {$vinnerrightsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'external') {$vinnerrightsidebar = $vrightsidebar; $vrightsidebar = $vsubcontext;}
				}
			}
		}

		// set full sidebar position array
		$vsidebars = array($vleftsidebar, $vinnerleftsidebar, $vinnerrightsidebar, $vrightsidebar);
	}

	if (THEMEDEBUG) {
		echo "<!-- Sidebar Positions (".$vcontext."/".$vsubcontext."): ";
		echo $vleftsidebar." - ".$vinnerleftsidebar." - ".$vinnerrightsidebar." - ".$vrightsidebar;
		echo " -->";
	}


	// Check Template Position Override
	// --------------------------------
	$voverrides = bioship_apply_filters('skeleton_sidebar_layout_override',$vsidebars);

	// 1.9.0: apply overrides if validated
	if ( ($voverrides != $vsidebars) && (is_array($voverrides)) && (count($voverrides) == 4) ) {
		$vsidebars = $voverrides;
		if (THEMEDEBUG) {
			echo "<!-- New Sidebar Positions (".$vcontext."/".$vsubcontext."): ";
			echo $vsidebars[0]." - ".$vsidebars[1]." - ".$vsidebars[2]." - ".$vsidebars[3]." -->";
		}
	}

	// Check Metabox Overrides
	// -----------------------
	// (set via theme options metabox)
	// 1.8.0: check the sidebar display overrides
	// 1.8.5: use vthemedisplay overrides global
	// 1.9.5: change to use of vthemeoverride templating global
	$vsidebaroverride = false; $vsubsidebaroverride = false;
	$vnosidebar = false; $vnosubsidebar = false;
	if ( (isset($vthemeoverride['sidebartemplate'])) && ($vthemeoverride['sidebartemplate'] != '') ) {
		$vsidebaroverride = true;
		if ($vthemeoverride['sidebartemplate'] == 'off') {$vnosidebar = true;}
		else {
			// assign sidebar and clear other unused positions
			if ($vsidebarposition == 'left') {
				if ($vsubsidebarposition == 'opposite') {$vposition = 0; $vsidebars[1] = ''; $vsidebars[2] = '';}
				if ($vsubsidebarposition == 'internal') {$vposition = 0; $vsidebars[2] = ''; $vsidebars[3] = '';}
				if ($vsubsidebarposition == 'external') {$vposition = 1; $vsidebars[2] = ''; $vsidebars[3] = '';}
				$vsidebars[$vposition] = $vthemeoverride['sidebartemplate'];
			}
			if ($vsidebarposition == 'right') {
				if ($vsubsidebarposition == 'opposite') {$vposition = 3; $vsidebars[1] = ''; $vsidebars[2] = '';}
				if ($vsubsidebarposition == 'internal') {$vposition = 3; $vsidebars[0] = ''; $vsidebars[1] = '';}
				if ($vsubsidebarposition == 'external') {$vposition = 2; $vsidebars[0] = ''; $vsidebars[1] = '';}
				$vsidebars[$vposition] = $vthemeoverride['sidebartemplate'];
			}
		}
	}
	if ( (isset($vthemeoverride['subsidebartemplate'])) && ($vthemeoverride['subsidebartemplate'] != '') ) {
		$vsubsidebaroverride = true;
		if ($vthemeoverride['subsidebartemplate'] == 'off') {$vnosubsidebar = true;}
		else {
			if ($vsubsidebarposition == 'opposite') {
				if ($vsidebarposition == 'left') {$vsidebars[3] = $vthemeoverride['subsidebartemplate'];}
				if ($vsidebarposition == 'right') {$vsidebars[0] = $vthemeoverride['subsidebartemplate'];}
			}
			if ($vsubsidebarposition == 'internal') {
				if ($vsidebarposition == 'left') {$vsidebars[1] = $vthemeoverride['subsidebartemplate'];}
				if ($vsidebarposition == 'right') {$vsidebars[2] = $vthemeoverride['subsidebartemplate'];}
			}
			if ($vsubsidebarposition == 'external') {
				if ($vsidebarposition == 'left') {$vsidebars[0] = $vthemeoverride['subsidebartemplate'];}
				if ($vsidebarposition == 'right') {$vsidebars[3] = $vthemeoverride['subsidebartemplate'];}
			}
		}
	}

	// recheck sidebar and subsidebar flags
	// ------------------------------------
	// 1.9.0: recheck sidebar states in any case
	// 1.9.0: maybe loop to apply meta override
	// 1.9.5: combined separate loops to check/override sidebars
	$vi = 0; $vfoundsidebar = false; $vfoundsubsidebar = false;
	foreach ($vsidebars as $vasidebar) {
		if ($vasidebar != '') {
			if (substr($vasidebar,0,3) == 'sub') {
				if ($vnosubsidebar) {unset($vsidebars[$vi]);} else {$vfoundsubsidebar = true;}
			} else {
				if ($vnosidebar) {unset($vsidebars[$vi]);} else {$vfoundsidebar = true;}
			}
		}
		$vi++;
	}
	if ($vfoundsidebar) {$vsidebar = true;} else {$vsidebar = false;}
	if ($vfoundsubsidebar) {$vsubsidebar = true;} else {$vsubsidebar = false;}

	if (THEMEDEBUG) {
		if ( ($vsidebaroverride) || ($vsubsidebaroverride) ) {
			echo "<!-- Sidebar Meta Override Result: "; print_r($vsidebars); echo "-->";
		}
		echo "<!-- Sidebar Flag States: ";
			if ($vsidebar) {echo "Main Sidebar - ";} else {echo "No Main Sidebar - ";}
			if ($vsubsidebar) {echo "Sub Sidebar";} else {echo "No Sub Sidebar";}
		echo " -->";
	}

	// 1.9.0: set global theme layout states for sidebars
	$vthemesidebars['sidebars'] = $vsidebars;
	$vthemesidebars['sidebar'] = $vsidebar;
	$vthemesidebars['subsidebar'] = $vsubsidebar;
	$vthemesidebars['sidebarcontext'] = $vcontext;
	$vthemesidebars['subsidebarcontext'] = $vsubcontext;

	// Prepare Sidebar Output early
	bioship_set_sidebar('left'); bioship_set_sidebar('right');

	return $vsidebars;
 }
}

// Set Sidebars for Position
// -------------------------
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_set_sidebar')) {
 function bioship_set_sidebar($vposition) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.5: rename global sidebaroutput to themesidebars
	global $vthemelayout, $vthemesidebars;

	$vsidebar = $vthemesidebars['sidebar'];
	$vsubsidebar = $vthemesidebars['subsidebar'];
	if ( (!$vsidebar) && (!$vsubsidebar) ) {return;}

	// 1.9.0: use new themesidebars global
	$vsidebars = $vthemesidebars['sidebars'];
	$vcontext = $vthemesidebars['sidebarcontext'];
	// 1.9.8: fix to variable typo here
	$vsubcontext = $vthemesidebars['subsidebarcontext'];

	// 1.9.8: fix to undefined index warning
	if ( (!isset($vthemesidebars['output'])) || (!is_array($vthemesidebars['output'])) ) {
		$vthemesidebars['output'] = array('', '', '', ''); // set empty sidebar array
	}
	$vleftsidebar = $vsidebars[0];			$vleftoutput = $vthemesidebars['output'][0];
	$vinnerleftsidebar = $vsidebars[1];		$vinnerleftoutput = $vthemesidebars['output'][1];
	$vinnerrightsidebar = $vsidebars[2];	$vinnerrightoutput = $vthemesidebars['output'][2];
	$vrightsidebar = $vsidebars[3];			$vrightoutput = $vthemesidebars['output'][3];

	// Note: Sidebar Positions: left (0) - inner left (1) - [content] - inner right (2) - right (3)
	// any primary and subsidiary sidebars are already mapped to these positions
	// if overriding use the skeleton_sidebar_layout_override filter above

	// final fallback is to sidebar/sidebar.php via hybrid_get_sidebar
	// (this does not exist by default intentionally so shows no sidebar)

	// Prepare/Output Left Sidebars
	// ----------------------------
	if ($vposition == 'left') {
		if (THEMEDEBUG) {echo "<!-- Left Sidebar Positions - Left: ".$vleftsidebar." - SubLeft: ".$vinnerleftsidebar." -->";}

		// Left Sidebar Position
		if ($vleftsidebar != '') {
			$vleftsidebar = bioship_sidebar_template_check($vleftsidebar, 'Left');
			// prepare left sidebar position output
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vleftsidebar);
			$vleftoutput = ob_get_contents(); ob_end_clean();

			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vleftoutput)) === 0) {
				if (strstr($vleftsidebar, 'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No Left Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- Left Sidebar Length: '.strlen($vleftoutput).' -->';}
		}

		// Inner Left Sidebar Position
		if ($vinnerleftsidebar != '') {
			$vinnerleftsidebar = bioship_sidebar_template_check($vinnerleftsidebar, 'SubLeft');
			// prepare subleft sidebar position output
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vinnerleftsidebar);
			$vinnerleftoutput = ob_get_contents(); ob_end_clean();

			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vinnerleftoutput)) === 0) {
				if (strstr($vinnerleftsidebar, 'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No SubLeft Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- SubLeft Sidebar Length: '.strlen($vinnerleftoutput).' -->';}
		}
	}

	// Prepare/Output Right Sidebars
	// -----------------------------
	if ($vposition == 'right') {
		if (THEMEDEBUG) {echo "<!-- Right Sidebar Positions - SubRight: ".$vinnerrightsidebar." - Right: ".$vrightsidebar." -->";}

		// Inner Right Sidebar Position
		if ($vinnerrightsidebar != '') {
			$vinnerrightsidebar = bioship_sidebar_template_check($vinnerrightsidebar, 'SubRight');
			// prepare subright sidebar position output
			// 1.8.5: allow for blank sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vinnerrightsidebar);
			$vinnerrightoutput = ob_get_contents(); ob_end_clean();

			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vinnerrightoutput)) === 0) {
				if (strstr($vinnerrightsidebar, 'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No SubRight Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- SubRight Sidebar Length: '.strlen($vinnerrightoutput).' -->';}
		}

		// Right Sidebar Position
		if ($vrightsidebar != '') {
			$vrightsidebar = bioship_sidebar_template_check($vrightsidebar,'Right');
			// prepare right sidebar position output
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vrightsidebar);
			$vrightoutput = ob_get_contents(); ob_end_clean();

			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vrightoutput)) === 0) {
				if (strstr($vrightsidebar, 'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No Right Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- Right Sidebar Length: '.strlen($vrightoutput).' -->';}
		}
	}

	// maybe swap mobile button positions to match sidebars
	// TODO: maybe there is an easier/better way than this?
	// ...as we could check for sidebar content not just positions?

	// maybe move mobile subsidebar button to left
	if ( ( (strstr($vleftsidebar,'sub')) && ($vinnerleftsidebar == '') )
	  && ( (strstr($vinnerleftsidebar,'sub')) && ($vleftsidebar == '') ) ) {
		if (!has_action('wp_footer', 'bioship_mobile_subsidebar_button_swap')) {
			add_action('wp_footer', 'bioship_mobile_subsidebar_button_swap');
			if (!function_exists('bioship_mobile_subsidebar_button_swap')) {
			 function bioship_mobile_subsidebar_button_swap() {
				echo "<style>#subsidebarbutton {float:left !important; margin-left:10px !important; margin-right:0px !important;}</style>";
			 }
			}
		}
	}
	// maybe move mobile sidebar button to right
	// 2.0.7: fix to variable typo (subrighsidebar)
	if ( ( ($vrightsidebar != '') && (!strstr($vrightsidebar,'sub')) && ($vinnerrightsidebar == '') )
	  && ( ($vinnerrightsidebar != '') && (!strstr($vinnerrightsidebar,'sub')) && ($vrightsidebar == '') ) ) {
		if (!has_action('wp_footer', 'bioship_mobile_sidebar_button_swap')) {
			add_action('wp_head', 'bioship_mobile_sidebar_button_swap');
			if (!function_exists('bioship_mobile_sidebar_button_swap')) {
			 function bioship_mobile_sidebar_button_swap() {
				echo "<style>#sidebarbutton {float:right !important; margin-right:10px !important; margin-left:0px !important;}</style>";
			 }
			}
		}
	}

	// 1.8.5: renamed to use themesidebars global
	$vthemesidebars['output'] = array($vleftoutput, $vinnerleftoutput, $vinnerrightoutput, $vrightoutput);

	// 1.9.9: set theme sidebar states as may have changed
	$vthemesidebars['sidebar'] = $vsidebar;
	$vthemesidebars['subsidebar'] = $vsubsidebar;

	if (THEMEDEBUG) {
		echo "<!-- Stored Sidebars Lengths: ";
		echo strlen($vthemesidebars['output'][0]).','.strlen($vthemesidebars['output'][1]).',';
		echo strlen($vthemesidebars['output'][2]).','.strlen($vthemesidebars['output'][3]);
		echo " -->";
	}

	// manual debug for full sidebar output
	// if (THEMEDEBUG) {echo "<!-- Stored Sidebars: "; print_r($vthemesidebars['output']); echo " -->";}

 }
}

// set Sidebar Column Width
// ------------------------
if (!function_exists('bioship_set_sidebar_columns')) {
 function bioship_set_sidebar_columns() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemesidebars;

	// get filtered sidebar column width
	$vsidebarcolumns = $vthemesettings['sidebar_width'];
	if ($vsidebarcolumns == '') {$vsidebarcolumns = 'four';}
	$vcolumns = bioship_apply_filters('skeleton_sidebar_columns', $vsidebarcolumns);

	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['sidebarcolumns'])) && ($vthemeoverride['sidebarcolumns'] != '') ) {
		$vcolumns = $vthemeoverride['sidebarcolumns'];
	}

	// 1.8.5: added filter validation check
	if ($vcolumns != $vsidebarcolumns) {
		if (is_numeric($vcolumns)) {
			$vcolumns = bioship_number_to_word($vcolumns);
			if ($vcolumns) {$vsidebarcolumns = $vcolumns;}
		} elseif (is_string($vcolumns)) {
			$vcolumns = bioship_word_to_number($vcolumns);
			if ($vcolumns) {$vsidebarcolumns = bioship_number_to_word($vcolumns);}
		}
	}
	$vthemesidebars['sidebarcolumns'] = $vsidebarcolumns;
	return $vsidebarcolumns;
 }
}

// set SubSidebar Column Width
// ---------------------------
if (!function_exists('bioship_set_subsidebar_columns')) {
 function bioship_set_subsidebar_columns() {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	global $vthemesettings, $vthemesidebars, $vthemeoverride;

	// get filtered subsidebar column width
	$vsubsidebarcolumns = $vthemesettings['subsidiarycolumns'];
	if ($vsubsidebarcolumns == '') {$vsubsidebarcolumns = 'zero';}
	$vcolumns = bioship_apply_filters('skeleton_subsidebar_columns', $vsubsidebarcolumns);

	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['subsidebarcolumns'])) && ($vthemeoverride['subsidebarcolumns'] != '') ) {
		$vcolumns = $vthemeoverride['subsidebarcolumns'];
	}

	// 1.8.5: added filter validation check
	if ($vcolumns != $vsubsidebarcolumns) {
		if (is_numeric($vcolumns)) {
			$vcolumns = bioship_number_to_word($vcolumns);
			if ($vcolumns) {$vsubsidebarcolumns = $vcolumns;}
		} elseif (is_string($vcolumns)) {
			$vcolumns = bioship_word_to_number($vcolumns);
			if ($vcolumns) {$vsubsidebarcolumns = bioship_number_to_word($vcolumns);}
		}
	}
	$vthemesidebars['subsidebarcolumns'] = $vsubsidebarcolumns;
	return $vsubsidebarcolumns;
 }
}

// set Content Width
// -----------------
// 1.8.5: moved load action to skeleton_load_layout
if (!function_exists('bioship_set_content_width')) {
 function bioship_set_content_width() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemelayout;
 	// 1.8.5: do main content setup here
	$vcontentwidth = bioship_get_content_width();

	// 1.9.5: moved here from get_content_width
	$vpaddingwidth = bioship_get_content_padding_width($vcontentwidth);
	if ($vpaddingwidth > 0) {$vcontentwidth = $vcontentwidth - $vpaddingwidth;}
	$vcontentwidth = bioship_apply_filters('skeleton_content_width', $vcontentwidth);

	// same thing but different here...
	if (THEMEHYBRID) {hybrid_set_content_width($vcontentwidth);}
	else {global $content_width; $content_width = absint($vcontentwidth);}
	$vthemelayout['contentwidth'] = $vcontentwidth;
	return $vcontentwidth;
 }
}

// get Content Width (in pixels based on columns)
// ----------------------------------------------
// 1.8.0: non-hybrid content width set in functions.php
// 1.8.5: moved from skeleton.php
if (!function_exists('bioship_get_content_width')) {
	function bioship_get_content_width() {
	  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout;

		$vcontentcols = bioship_content_width();
		$vcolumns = bioship_word_to_number($vcontentcols);

		// 1.8.5: use new theme layout global
		$vnumgridcolumns = bioship_word_to_number($vthemelayout['gridcolumns']);

		// 1.8.0: bugfix for layoutwidth, not 960 default anymore
		// $vlayoutwidth = $vthemesettings['layout']; // maximum
		// $vlayoutwidth = bioship_apply_filters('skeleton_layout_width', $vlayoutwidth);
		// 1.8.5: use new layout global already set
		$vlayoutwidth = $vthemelayout['maxwidth'];

		// calculate actual content width
		$vcontentwidth = $vlayoutwidth / $vnumgridcolumns * $vcolumns;
		if (THEMEDEBUG) {echo "<!-- Layout Max Width: ".$vlayoutwidth." - Grid Columns: ".$vnumgridcolumns." - Content Columns: ".$vcolumns." -->";}
		// 1.9.5: set raw content width value for grid querystring
		$vcontentwidth = bioship_apply_filters('skeleton_raw_content_width', $vcontentwidth);
		$vthemelayout['rawcontentwidth'] = $vcontentwidth;

		return $vcontentwidth;
	}
}

// set Content Column Width
// ------------------------
// 1.8.5: moved here from skeleton.php
// 1.5.0: removed the filter here and moved to inside and also
if (!function_exists('bioship_content_width')) {
	function bioship_content_width() {
	  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 1.9.8: added missing global theme override declaration
		global $post, $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;
		$vsidebar = $vthemesidebars['sidebar'];
		$vsubsidebar = $vthemesidebars['subsidebar'];

		// 1.8.5: use new theme layout global
		// note: onecolumn-page.php template has been removed
		// as the theme options edit screen metabox provides this
		// skeleton wide attachment pages have also been removed
		// if (is_attachment()) {return $vthemelayout['gridcolumns'];}

		// 1.8.0: full width if no sidebars (calculated in set_sidebar_layout)
		if ( (!$vsidebar) && (!$vsubsidebar) ) {
			$vcolumns = $vthemelayout['gridcolumns'];
			// 1.9.8: filter and set content columns globals
			$vcolumns = bioship_apply_filters('skeleton_content_columns_override', $vcolumns);
			$vthemelayout['contentcolumns'] = $vcolumns;
			$vthemelayout['numcontentcolumns'] = bioship_word_to_number($vcolumns);
			return $vcolumns;
		}

		// Check/fix for the total content column width
		// 1.5.0: replaced skeleton_options here and added filters
		$vcontentcolumns = $vthemesettings['content_width'];
		if ($vcontentcolumns == '') {
			// 1.8.0: fallback default to three quarters of grid column total
			if ($vthemelayout['gridcolumns'] == 'twelve') {$vcontentcolumns = 'eight';}
			if ($vthemelayout['gridcolumns'] == 'sixteen') {$vcontentcolumns = 'twelve';}
			if ($vthemelayout['gridcolumns'] == 'twenty') {$vcontentcolumns = 'fifteen';}
			if ($vthemelayout['gridcolumns'] == 'twentyfour') {$vcontentcolumns = 'eightteen';}
		}

		// get filtered content column width
		$vcolumns = bioship_apply_filters('skeleton_content_columns',$vcontentcolumns);

		// 1.9.5: allow for metabox override
		if ($vthemeoverride['contentcolumns'] != '') {$vcolumns = $vthemeoverride['contentcolumns'];}

		// 1.8.5: added filter validation check
		if ($vcolumns != $vcontentcolumns) {
			if (is_numeric($vcolumns)) {
				$vcolumns = bioship_number_to_word($vcolumns);
				if ($vcolumns) {$vcontentcolumns = $vcolumns;}
			} elseif (is_string($vcolumns)) {
				$vcolumns = bioship_word_to_number($vcolumns);
				if ($vcolumns) {$vcontentcolumns = bioship_number_to_word($vcolumns);}
			}
		}

		// 1.8.5: use new themesidebars global
		$vsidebarcolumns = $vthemesidebars['sidebarcolumns'];
		$vsubsidebarcolumns = $vthemesidebars['subsidebarcolumns'];

		if (THEMEDEBUG) {echo "<!-- Columns: Content - ".$vcontentcolumns." - Sidebar - ".$vsidebarcolumns." - Subsidebar - ".$vsubsidebarcolumns." -->";}
		$vnumcontentcols = bioship_word_to_number($vcontentcolumns);

		// get sidebar columns width as numeric, or 0 if none
		if ($vsidebar) {$vsidebarcols = bioship_word_to_number($vsidebarcolumns);} else {$vsidebarcols = 0;}
		if ($vsubsidebar) {$vsubsidebarcols = bioship_word_to_number($vsubsidebarcolumns);} else {$vsubsidebarcols = 0;}

		// get total columns and grid columns
		$vtotalcolumns = (intval($vnumcontentcols) + intval($vsidebarcols) + intval($vsubsidebarcols));
		$vnumgridcolumns = $vthemelayout['numgridcolumns'];

		// ...and if total is too wide, reduce the content columns
		if ($vtotalcolumns > $vnumgridcolumns) {
			// Houston we have a problem. so let's fix it...
			$vnumcontentcols = ($vnumgridcolumns - intval($vsidebarcols) - intval($vsubsidebarcols));
		}

		// cap maximum at total grid columns
		if ($vnumcontentcols > $vnumgridcolumns) {$vnumcontentcols = $vnumgridcolumns;}
		$vcontentcolumns = bioship_number_to_word($vnumcontentcols);

		$vthemelayout['contentcolumns'] = $vcontentcolumns;
		$vthemelayout['numcontentcolumns'] = $vnumcontentcols;

		if (THEMEDEBUG) {echo "<!-- Content Columns: ".$vnumcontentcols." (".$vcontentcolumns.") -->";}

		// 1.9.8: fix to changed variable name
		// (probably not a good filter to use in practice)
		$vcontentcolumns = bioship_apply_filters('skeleton_content_columns_override', $vcontentcolumns);
		return $vcontentcolumns;
	}
}


// get Content Padding Width
// -------------------------
// 1.8.5: moved from skeleton.php
// gets the padding width (not height) - supports px or em or %
if (!function_exists('bioship_get_content_padding_width')) {
	function bioship_get_content_padding_width($vcontentwidth,$vcontentpadding=false) {
	  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings, $vthemelayout;

		// 1.9.5: allow for explicit second argument for grid.php
		if ($vcontentpadding) {$vpaddingcss = $vcontentpadding;}
		else {
			$vpaddingcss = $vthemesettings['contentpadding'];
			$vpaddingcss = bioship_apply_filters('skeleton_raw_content_padding', $vpaddingcss);
		}

		if ( ($vpaddingcss == '') || ($vpaddingcss == '0') ) {$vpaddingwidth = 0;}
		else {
			if (strstr($vpaddingcss,' ')) {$vpaddingarray = explode(' ', $vpaddingcss);}
			else {$vpaddingarray[0] = $vpaddingcss;}

			$vi = 0; // convert padding values
			foreach ($vpaddingarray as $vpadding) {
				if (stristr($vpadding,'px')) {
					$vpaddingarray[$vi] = intval(trim(str_ireplace('px', '', $vpadding))); $vi++;
				}
				elseif (stristr($vpadding,'em')) {
					// 1.5.0: added em support based on 1em ~= 16px
					// 1.9.5: added font percent to 100 for testing
					$vfontpercent = '100';
					$vpaddingvalue = trim(str_ireplace('em', '', $vpadding));
					$vpaddingvalue = round(($vpaddingvalue * 16 * $vfontpercent / 100),2,PHP_ROUND_HALF_DOWN);
					$vpaddingarray[$vi] = $vpaddingvalue; $vi++;
				}
				elseif (strstr($vpadding,'%')) {
					$vpadding = intval(trim(str_ireplace('%', '', $vpadding)));
					$vpaddingarray[$vi] = round(($vcontentwidth * $vpadding), 2, PHP_ROUND_HALF_DOWN); $vi++;
				}
				else {$vpaddingarray[$vi] = 0; $vi++;}
			}

			if (count($vpaddingarray) == 4) {$vpaddingwidth = $vpaddingarray[1] + $vpaddingarray[3];}
			elseif ( (count($vpaddingarray) == 3) || (count($vpaddingarray) == 2) ) {$vpaddingwidth = $vpaddingarray[1];}
			elseif (count($vpaddingarray == 1)) {$vpaddingwidth = $vpaddingarray[0];}
		}

		// 1.5.0: added a padding width filter
		$vpaddingwidth = bioship_apply_filters('skeleton_content_padding_width', $vpaddingwidth);
		$vpaddingwidth = abs(intval($vpaddingwidth));

		$vthemelayout['contentpadding'] = $vpaddingwidth;
		return $vpaddingwidth;
	}
}


// -----------------
// === Title Tag ===
// -----------------

// Title Tag Support
// -----------------
// 1.8.5: use new title-tag support
// 1.9.0: off as not rendering tag yet?
// 2.0.1: fixed so on again (and now works fine with or without)
$vtitletagsupport = bioship_apply_filters('skeleton_title_tag_support', true);

if ($vtitletagsupport) {
	add_theme_support('title-tag');
	// replace title tag render action to add filter
	remove_action('wp_head', '_wp_render_title_tag', 1);
	add_action('wp_head', 'bioship_render_title_tag_filtered', 1);
	add_filter('document_title_separator', 'bioship_title_separator');
	if (!function_exists('bioship_title_separator')) {
		function bioship_title_separator($vsep) {return '|';}
	}
	// TODO: recheck usage of document_title_parts filter
	// add_filter('document_title_parts','bioship_document_title_parts');
	// function bioship_document_title_parts($vtitle) {return $title;}
} else {
	// fallback to wp_title usage
	add_filter('wp_title', 'bioship_wp_title', 10, 2);
	// 2.0.8: specify priority to match title tag support
	add_action('wp_head', 'bioship_wp_title_tag', 1);
}

// Title Tag Filter
// ----------------
// 1.8.5: added title tag filter function
if (!function_exists('bioship_render_title_tag_filtered')) {
 function bioship_render_title_tag_filtered() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    ob_start(); _wp_render_title_tag(); $vtitletag = ob_get_contents(); ob_end_clean();
    $vtitletag = bioship_apply_filters('wp_render_title_tag_filter', $vtitletag);
    echo $vtitletag;
 }
}

// Title Tag Theme Default
// -----------------------
add_filter('wp_render_title_tag_filter', 'bioship_wp_render_title_tag');
if (!function_exists('bioship_wp_render_title_tag')) {
 function bioship_wp_render_title_tag($vtitletag) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	// note: rendered default is (WP 4.4):
 	// echo '<title>' . wp_get_document_title() . '</title>' . "\n";
 	// 2.0.7: concatenate to not trigger irrelevant Theme Check warning
 	$vtitletag = str_replace('<'.'title'.'>', '<'.'title'.' itemprop="name">', $vtitletag);
	return $vtitletag;
 }
}

// wp_title Tag Output
// -------------------
// 1.8.5: moved here from header.php (wp_title only)
if (!function_exists('bioship_wp_title_tag')) {
 function bioship_wp_title_tag() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 2.0.7: concatenate to not trigger irrelevant Theme Check warning
  	echo '<'.'title'.' '.'itemprop="name">';
  	wp_title('|', true, 'right');
  	echo '</'.'title'.'>'.PHP_EOL;
 }
}

// wp_title Title Filter
// ---------------------
// 1.8.5: no longer default, and moved filter actions to title-tag support check
if (!function_exists('bioship_wp_title'))  {
 function bioship_wp_title($vtitle, $vsep) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	if (is_feed()) {return $vtitle;}

	global $page, $paged;

	$vblogname = get_bloginfo('name');
	if (is_home() || is_front_page()) {
		$vtitle = $vblogname;
		$vdescription = get_bloginfo('description', 'display');
		// 1.8.0: fix to typo variable!
		if ($vdescription) {$vtitle .= " ".$vsep." ".$vdescription;}
	}
	else {
		// 1.8.5: fix to double sep
		// 2.0.1: nope, fix to unfix that
		$vtitle .= " ".$vsep." ".$vblogname;
	}

	// maybe add a page number
	if ( ($paged >= 2) || ($page >= 2) ) {
		$title .= " ".$vsep." ".sprintf( __('Page %s','bioship'), max($paged,$page) );
	}

	$vtitle = bioship_apply_filters('skeleton_page_title', $vtitle);
	return $vtitle;
 }
}


// ------------------------
// === Template Helpers ===
// ------------------------

// Get Content Template
// --------------------
// 2.0.5: added this pseudonym for consistency
if (!function_exists('bioship_get_content_template')) {
 function bioship_get_content_template() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	hybrid_get_content_template();
 }
}

// Get Header Template
// -------------------
// 1.8.5: custom header template hierarchy implementation
if (!function_exists('bioship_get_header')) {
 function bioship_get_header($vfilepath=false) {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	$vpagecontext = $vthemelayout['pagecontext'];
	$vsubpagecontext = $vthemelayout['subpagecontext'];

	// to check for header directory just once
	$vheaderdir = false;
	if (is_dir($vthemestyledir.'header')) {$vheaderdir = true;}
	elseif ( ($vthemestyledir != $vthemetemplatedir) && (is_dir($vthemetemplatedir.'header')) ) {$vheaderdir = true;}

	// if (THEMEHYBRID) {hybrid_get_header($vheader); return;}
	// 1.8.5: custom implementation like hybrid_get_header
	// 1.9.8: remove second unused variable vheader
	bioship_do_action('get_header');
	$vtemplates = array();

	// filter to allow for custom overrides
	$vheader = bioship_apply_filters('skeleton_header_template',$vpagecontext);
	if ( ($vheader) && (is_string($vheader)) && ($vheader != $vpagecontext) ) {
		if ($vheaderdir) {$vtemplates[] = 'header/'.$vheader;}
		$vtemplates[] = 'header-'.$vheader;
	}

	// for matching template by filename
	if ($vfilepath) {$vpathinfo = pathinfo($vfilepath); $vfilename = $vpathinfo['filename'];}
 	if ( ($vfilename) && ($vfilename != 'index') ) {
 		if ($vheaderdir) {$vtemplates[] = 'header/'.$vfilename.'.php';}
 		$vtemplates[] = 'header-'.$vfilename.'.php';
 	}

	// for subarchive types
	if ($vpagecontext == 'archive') {
		// filter the sub archive context also
		$vheaderarchive = bioship_apply_filters('skeleton_header_archive_template',$vsubpagecontext);
		if ( ($vheaderarchive) && (is_string($vheaderarchive)) ) {
			// TODO: trigger this action for completeness?
			// bioship_do_action('get_header', $vheaderarchive);
			if ($vheaderdir) {$vtemplates[] = 'header/'.$vheaderarchive.'.php';}
			$vtemplates[] = 'header-'.$vheaderarchive.'.php';
		}
	}

	// default template hierarchy
	if ($vheaderdir) {$vtemplates[] = 'header/'.$vpagecontext.'.php';}
	$vtemplates[] = 'header-'.$vpagecontext.'.php';
	if ($vheaderdir) {$vtemplates[] = 'header/header.php';}
	$vtemplates[] = 'header.php';

	$vheadertemplates = bioship_apply_filters('skeleton_header_templates',$vtemplates);
	if (is_array($vheadertemplates)) {$vtemplates = $vheadertemplates;}
	bioship_locate_template($vtemplates, true, false);
 }
}

// Get Footer Template
// -------------------
// 1.8.5: custom footer template hierarchy implementation
if (!function_exists('bioship_get_footer')) {
 function bioship_get_footer($vfilepath=false) {
  	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	$vpagecontext = $vthemelayout['pagecontext'];
	$vsubpagecontext = $vthemelayout['subpagecontext'];

	// to check for footer directory just once
	$vfooterdir = false;
	if (is_dir($vthemestyledir.'footer')) {$vfooterdir = true;}
	elseif ( ($vthemestyledir != $vthemetemplatedir) && (is_dir($vthemetemplatedir.'footer')) ) {$vfooterdir = true;}

	// if (THEMEHYBRID) {hybrid_get_footer($vfooter); return;}
	// 1.8.5: custom implementation like hybrid_get_footer
	// 1.9.8: remove unused second variable vfooter
	bioship_do_action('get_footer');
	$vtemplates = array();

	// filter to allow for custom overrides
	$vfooter = bioship_apply_filters('skeleton_footer_template',$vpagecontext);
	if ( ($vfooter) && (is_string($vfooter)) && ($vfooter != $vpagecontext) ) {
		if ($vfooterdir) {$vtemplates[] = 'footer/'.$vfooter;}
		$vtemplates[] = 'footer-'.$vfooter;
	}

	// for matching template by filename
	if ($vfilepath) {$vpathinfo = pathinfo($vfilepath); $vfilename = $vpathinfo['filename'];}
 	if ( ($vfilename) && ($vfilename != 'index') ) {
 		if ($vfooterdir) {$vtemplates[] = 'footer/'.$vfilename.'.php';}
 		$vtemplates[] = 'footer-'.$vfilename.'.php';
 	}

	// for subarchive types
	if ($vpagecontext == 'archive') {
		// filter the sub archive context also
		$vfooterarchive = bioship_apply_filters('skeleton_footer_archive_template',$vsubpagecontext);
		if ( ($vfooterarchive) && (is_string($vfooterarchive)) ) {
			// TODO: trigger this action for completeness?
			// bioship_do_action('get_footer', $vfooterarchive);
			if ($vfooterdir) {$vtemplates[] = 'footer/'.$vfooterarchive.'.php';}
			$vtemplates[] = 'footer-'.$vfooterarchive.'.php';
		}
	}

	// default footer template hierarchy
	if ($vfooterdir) {$vtemplates[] = 'footer/'.$vpagecontext.'.php';}
	$vtemplates[] = 'footer-'.$vpagecontext.'.php';
	if ($vfooterdir) {$vtemplates[] = 'footer/footer.php';}
	$vtemplates[] = 'footer.php';

	$vfootertemplates = bioship_apply_filters('skeleton_footer_templates',$vtemplates);
	if (is_array($vfootertemplates)) {$vtemplates = $vfootertemplates;}
	bioship_locate_template($vtemplates, true, false);
 }
}

// Check Sidebar Template
// ----------------------
// a fallback template hierarchy for sidebars
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_sidebar_template_check')) {
 function bioship_sidebar_template_check($vtemplate, $vposition) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemelayout, $vthemesidebars;

	// 1.9.0: bug out if blank or subblank override
	if ( ($vtemplate == 'blank') || ($vtemplate == 'subblank') ) {return $vtemplate;}

	// 1.9.0: use new themesidebars global
	$vcontext = $vthemesidebars['sidebarcontext'];
	$vsubcontext = $vthemesidebars['subsidebarcontext'];

	// 1.8.5: changed to use get post types helper
	$vposttypes = bioship_get_post_types();
	$vchecktemplate = false;

	// aiming to mirror WordPress page template hierarchy here (eventually)...
	// handy mini ref: https://wphierarchy.com
	// TODO: allow for specific post type ID sidebars?
	// TODO: allow for specific author (nicename/ID) sidebars?
	// TODO: allow for specific taxonomy-term (ID/slug) sidebars?

	// allow for post type archives...
	if ( ($vcontext == 'archive') || ($vsubcontext == 'subarchive') ) {
		if (!is_array($vposttypes)) {
			// 2.0.8: fix for archive subtemplate check
			$vsubtemplate = false;
			if ($vtemplate == 'archive') {$vsubtemplate = 'archive-'.$vposttypes;}
			if ($vtemplate == 'subarchive') {$vsubtemplate = 'subarchive-'.$vposttypes;}
			if ($vsubtemplate) {
				$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'), array());
				if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
			}
		}
	}
	// 1.8.5: allow for specific category slugs (and IDs)
	if ( ($vcontext == 'category') || ($vsubcontext == 'subcategory') ) {
		// 2.0.8: fix for variable typo (term)
		$vterm = get_queried_object(); $vcategory = $vterm->slug;

		// 2.0.8: fix for archive subtemplate check
		$vsubtemplate = false;
		if ($vtemplate == 'category') {$vsubtemplate = 'category-'.$vcategory;}
		if ($vtemplate == 'subcategory') {$vsubtemplate = 'subcategory-'.$vcategory;}
		if ($vsubtemplate) {
			$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'), array());
		} else {$vchecktemplate = false;}

		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		else {
			// 2.0.8: fix to variable type (term)
			$vcatid = $vterm->term_id;
			$vsubtemplate = false;
			if ($vtemplate == 'category') {$vsubtemplate = 'category-'.$vcatid;}
			if ($vtemplate == 'subcategory') {$vsubtemplate = 'subcategory-'.$vcatid;}
			if ($vsubtemplate) {
				$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'), array());
				if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
			}
		}
	}
	// 1.8.5: allow for specific taxonomy slugs
	if ( ($vcontext == 'taxonomy') || ($vsubcontext == 'subtaxonomy') ) {
		$vterm = get_queried_object(); $vtaxonomy = $vterm->taxonomy;
		if ($vtemplate == 'taxonomy') {$vsubtemplate = 'taxonomy-'.$vtaxonomy;}
		if ($vtemplate == 'subtaxonomy') {$vsubtemplate = 'subtaxonomy-'.$vtaxonomy;}
		$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'),array());
		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
	}
	// 1.8.5: allow for specific tag slugs (and IDs)
	if ( ($vcontext == 'tag') || ($vsubcontext == 'subtag') ) {
		$vterm = get_queried_object(); $vtag = $vterm->slug;
		if ($vtemplate == 'tag') {$vsubtemplate = 'tag-'.$vtag;}
		if ($vtemplate == 'subtag') {$vsubtemplate = 'subtag-'.$vtag;}
		$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'), array());
		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		else {
			$vtagid = $vterm->term_id;
			if ($vtemplate == 'tag') {$vsubtemplate = 'tag-'.$vtagid;}
			if ($vtemplate == 'subtag') {$vsubtemplate = 'subtag-'.$vtagid;}
			$vchecktemplate = bioship_file_hierarchy('file', $vsubtemplate.'.php', array('sidebar'), array());
			if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		}
	}

	// 1.8.5: allow already checked templates
	if (!$vchecktemplate) {$vchecktemplate = bioship_file_hierarchy('file', $vtemplate.'.php', array('sidebar'), array());}

	if ($vchecktemplate) {
		if (THEMEDEBUG) {echo '<!-- '.$vposition.' Sidebar Template Found: sidebar/'.$vtemplate.'.php -->';}
		return $vtemplate;
	} else {
		if (THEMEDEBUG) {echo '<!-- '.$vposition.' Sidebar Template not found (sidebar/'.$vtemplate.'.php) -->';}

		// fall back for singular post types to default sidebar
		if (is_singular()) {

			// 1.9.0: use new theme layout global
			$vsidebarmode = $vthemesidebars['sidebarmode'];
			$vsubsidebarmode = $vthemesidebars['subsidebarmode'];

			// 2.0.7: fix for singular post type usage
			// if (!is_array($vposttypes)) {$vposttype = $vposttypes;} else {
				$vposttype = get_post_type();
			// }

			if ($vcontext == $vposttype) {
				if ($vsidebarmode == 'unified') {$vtemplate = 'primary';}
				elseif ( ($vsidebarmode == 'off') || ($vsidebarmode == 'pagesonly') ) {$vtemplate = '';}
				else {$vtemplate = 'post';}
			}
			if ($vcontext == 'sub'.$vposttype) {
				if ($vsubsidebarmode == 'unified') {$vtemplate = 'subsidiary';}
				elseif ( ($vsubsidebarmode == 'off') || ($vsubsidebarmode == 'pagesonly') ) {$vtemplate = '';}
				else {$vtemplate = 'subpost';}
			}

			$vchecktemplate = bioship_file_hierarchy('file', $vtemplate.'.php', array('sidebar'), array());
			if ($vchecktemplate) {return $vtemplate;}
		}

		// TODO: test blank sidebar behaviour?
		// if substr($vtemplate,0,3)) == 'sub') {$vtemplate = 'subblank';} else {$vtemplate = 'blank';}

		$vtemplate = '';
	}

	return $vtemplate;
 }
}

// Output Sidebars at Position
// ---------------------------
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_sidebar')) {
 function bioship_get_sidebar($vposition) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.5: rename global sidebaroutput to themesidebars
	global $vthemelayout, $vthemesidebars;

	$vsidebar = $vthemesidebars['sidebar'];
	$vsubsidebar = $vthemesidebars['subsidebar'];
	if ( (!$vsidebar) && (!$vsubsidebar) ) {return;}

	if (THEMEDEBUG) {
		echo "<!-- Final Sidebar States: ";
		$vsidebarstate = $vthemesidebars; unset($vsidebarstate['output']); print_r($vsidebarstate);
		if ($vsidebar) {echo "Main Sidebar";} else {echo "No Main Sidebar";}
		if ($vsubsidebar) {echo " - SubSidebar";} else {echo " - No SubSidebar";}
		echo " -->";
	}

	$voutput = $vthemesidebars['output'];
	if ($vposition == 'left') {
		$vleftoutput = $voutput[0];
		$vinsideleftoutput = $voutput[1];
		if ($vleftoutput != '') {echo $vleftoutput;}
		if ($vinsideleftoutput != '') {echo $vinsideleftoutput;}
	} elseif ($vposition == 'right') {
		$vinsiderightoutput = $voutput[2];
		$vrightoutput = $voutput[3];
		if ($vinsiderightoutput != '') {echo $vinsiderightoutput;}
		if ($vrightoutput != '') {echo $vrightoutput;}
	}
 }
}

// Get Loop Template
// -----------------
// (allows for matching templates and /loop/ subdirectory usage)
// 1.8.5: replaces get_template_part('loop','index') for loop

if (!function_exists('bioship_get_loop')) {
 function bioship_get_loop($vfilepath=false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

 	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	$vpagecontext = $vthemelayout['pagecontext'];
	$vsubpagecontext = $vthemelayout['subpagecontext'];

	// to check for loop directory just once
	$vloopdir = false;
	if (is_dir($vthemestyledir.'loop')) {$vloopdir = true;}
	elseif ( ($vthemestyledir != $vthemetemplatedir) && (is_dir($vthemetemplatedir.'loop')) ) {$vloopdir = true;}

 	$vtemplates = array(); $vname = '';

	// filter to allow for custom override
	$vtemplate = bioship_apply_filters('skeleton_loop_template',$vpagecontext);
	if ( ($vtemplate) && (is_string($vtemplate)) && ($vtemplate != $vpagecontext) ) {
		$vname = $vtemplate;
		if ($vloopdir) {$vtemplates[] = 'loop/'.$vtemplate;}
		$vtemplates[] = 'loop-'.$vtemplate;
	}

	// for matching base-loop template by filename
	if ($vfilepath) {$vpathinfo = pathinfo($vfilepath); $vfilename = $vpathinfo['filename'];}
	if (THEMEDEBUG) {echo "<!-- Template File Basename: ".$vfilename." -->";}
 	if ( ($vfilename) && ($vfilename != 'index') ) {
 		if ($vname == '') {$vname = $vfilename;}
 		if ($vloopdir) {$vtemplates[] = 'loop/'.$vfilename.'.php';}
 		$vtemplates[] = 'loop-'.$vfilename.'.php';
 	}

 	// for subarchive types
 	if ($vpagecontext == 'archive') {
 		// 1.9.5: fix to bioship_apply_filters typo!
 		$vlooparchive = bioship_apply_filters('skeleton_loop_archive_template', $vsubpagecontext);
 		if ( ($vlooparchive) && (is_string($vlooparchive)) ) {
 			if ($vname == '') {$vname = $vlooparchive;}
 			if ($vloopdir) {$vtemplates[] = 'loop/'.$vlooparchive.'.php';}
 			$vtemplates[] = 'loop-'.$vlooparchive.'.php';
 		}
 	}

 	// default template hierarchy
 	if ($vloopdir) {$vtemplates[] = 'loop/'.$vpagecontext.'.php';}
 	$vtemplates[] = 'loop-'.$vpagecontext.'.php';
 	if ($vloopdir) {$vtemplates[] = 'loop/index.php';}
 	$vtemplates[] = 'loop-index.php';

 	// fire matching internal wordpress hook
 	if ($vname == '') {$vname = $vpagecontext;}
 	bioship_do_action('get_template_part_loop', 'loop', $vname);
 	// and fire this one in any case for loop index
 	bioship_do_action('get_template_part_loop', 'loop', 'index');

	// filter to allow for complete override
	$vlooptemplates = bioship_apply_filters('skeleton_loop_templates', $vtemplates);
	if (is_array($vlooptemplates)) {$vtemplates = $vlooptemplates;}
	bioship_locate_template($vtemplates, true, false);
 }
}

// Get Loop Title
// --------------
// 1.9.8: moved from /content/loop-meta.php
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_loop_title')) {
 function bioship_get_loop_title() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 1.8.0: replaced hybrid_loop_title (HC3 deprecated)
	if (function_exists('get_the_archive_title')) {$vlooptitle = get_the_archive_title();}
	elseif (function_exists('hybrid_loop_title')) {$vlooptitle = hybrid_loop_title();}
	else {$vlooptitle = '';}
	// note: get_the_archive_title filter also available
	$vlooptitle = bioship_apply_filters('hybrid_loop_title', $vlooptitle);
	$vlooptitle = bioship_apply_filters('skeleton_loop_title', $vlooptitle);
	return $vlooptitle;
 }
}

// Get Loop Description
// --------------------
// 1.9.8: moved here from /content/loop-meta.php
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_loop_description')) {
 function bioship_get_loop_description() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	// 1.8.0: replace hybrid_get_loop_description (HC3 deprecated)
	if (function_exists('get_the_archive_description')) {$vdescription = get_the_archive_description();}
	elseif (function_exists('hybrid_get_loop_description')) {$vdescription = hybrid_get_loop_description();}
	else {$vdecription = '';}
	// note: get_the_archive_description filter also available
	$vdescription = bioship_apply_filters('hybrid_loop_description', $vdescription);
	$vdescription = bioship_apply_filters('skeleton_loop_description', $vdescription);
	return $vdescription;
 }
}

// Locate Template Wrapper
// -----------------------
// copy of WordPress 'locate_template' function
// for a possible future filter/feature implementation
if (!function_exists('bioship_locate_template')) {
 function bioship_locate_template($template_names, $load = false, $require_once = true) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.8.5: this makes it just a passthrough for now
	return locate_template($template_names, $load, $require_once);

	// 1.8.5: added locate_template copy available for debugging by commenting out return above
	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( !$template_name )
				continue;
		if ( file_exists(get_stylesheet_directory() . '/' . $template_name)) {
				$located = get_stylesheet_directory() . '/' . $template_name;
				break;
		} elseif ( file_exists(get_template_directory() . '/' . $template_name) ) {
				$located = get_template_directory() . '/' . $template_name;
				break;
		}
	}

	if ( $load && '' != $located )
		load_template( $located, $require_once );

	return $located;
 }
}

// Comments Template Filter
// ------------------------
// ...this is a tricky one, WordPress has not made this easy..!
// (first, maybe remove the Hybrid comments template filter)
remove_filter('comments_template', 'hybrid_comments_template', 5);

if (!function_exists('bioship_comments_template')) {

 // add our own filter, as moving comments.php to theme /content/ template path
 // 2.0.5: increase priority to run later as template override
 add_filter('comments_template', 'bioship_comments_template', 11);

 function bioship_comments_template($vtemplatepath) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	// 1.5.0: Change the default Comments are Closed to invisible
	// if this is *not* done here, it somehow magically appears?! :-/
	if ( (!have_comments()) && (!comments_open()) ) {
		echo '<p class="nocomments" style="display:none;">'.__('Comments are Closed.','bioship').'</p>';
		return false; // so that the comments_template is not called
	}

	// note: default comment template is: STYLESHEETPATH.'/comments.php';
	// with fallback to TEMPLATEPATH.'/comments.php'
	$vpathinfo = pathinfo($vtemplatepath);
	$vtemplate = $vpathinfo['basename'];

	// if the default is sent, check for a post type comments template
	if ($vtemplate == 'comments.php') {
		$vposttypetemplate = 'comments-'.get_post_type(get_the_ID()).'.php';
		$vcommentstemplate = bioship_file_hierarchy('file', $vposttypetemplate, array('content'));
		if ($vcommentstemplate) {
			if (THEMEDEBUG) {echo '<!-- Comments Template Path: '.$vcommentstemplate.' -->';}
			return $vcommentstemplate;
		}
	}

	// for other templates (or no post type template)
	// use the skeleton file hierarchy to locate instead
	$vcommentstemplate = bioship_file_hierarchy('file', $vtemplate, array('content'));
	if ($vcommentstemplate) {
		if (THEMEDEBUG) {echo '<!-- Comments Template Path: '.$vcommentstemplate.' -->';}
		return $vcommentstemplate;
	}

	// otherwise return the path as is
	if (THEMEDEBUG) {echo '<!-- Comments Template Path: '.$vtemplatepath.' -->';}
	return $vtemplatepath;
 }
}

// Add Archive Content Templates to Hierarchy
// ------------------------------------------
// idea via Flagship Library: flagship_content_template_hierarchy
// slight change in implementation but the idea remains the same...
// split the templates into singular and archive to avoid conditionals
// uses the existing Hybrid Core content filter... (default is off)
if (!function_exists('bioship_archive_template_hierarchy')) {
 add_filter('hybrid_content_template_hierarchy', 'bioship_archive_template_hierarchy', 11);
 function bioship_archive_template_hierarchy($vtemplates) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
	// 1.9.5: only add archive template search if an archive subdirectory exists
	if ( (is_singular()) || (is_attachment()) ) {return $vtemplates;}
	global $vthemestyledir, $vthemetemplatedir;
	$varchivedir = bioship_apply_filters('skeleton_archive_template_directory', 'archive');
	$vcontentdir = bioship_apply_filters('skeleton_content_template_directory', 'content');
	if ( (!is_string($varchivedir)) || (!is_string($vcontentdir)) ) {return $vtemplates;}
	if ( (!is_dir($vthemestyledir.$varchivedir)) && (!is_dir($vthemetemplatedir.$varchivedir)) ) {return $vtemplates;}

	$varchivetemplates = array();
	foreach ($vtemplates as $vtemplate) {
		// 1.9.5: add archive subdirectory to a hierarchy instead of replacing content
		if (strstr($vtemplate,$vcontentdir.'/')) {$varchivetemplates[] = str_replace($vcontentdir.'/', $varchivedir.'/', $vtemplate);}
	}
	$vnewtemplates = array_merge($varchivetemplates, $vtemplates);
	if (THEMEDEBUG) {echo "<!-- Archive Template Hiearchy: "; print_r($vnewtemplates); echo " -->";}
	return $vnewtemplates;
 }
}

// Content Directory Template Filter
// ---------------------------------
// 1.9.5: similar to archive template filter, allows change of /content/ directory usage
if (!function_exists('bioship_content_template_hierarchy')) {
 add_filter('hybrid_content_template_hierarchy', 'bioship_content_template_hierarchy', 10, 3);
 function bioship_content_template_hierarchy($vtemplates) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
 	$vcontentdir = bioship_apply_filters('skeleton_content_template_directory', 'content');
 	if ( ($vcontentdir == 'content') || (!is_string($vcontentdir)) ) {return $vtemplates;}
	foreach ($vtemplates as $vkey => $vtemplate) {
		if (strstr($vtemplate, 'content/')) {$vtemplates[$vkey] = str_replace('content/', $vcontentdir.'/', $vtemplate);}
	}
	if (THEMEDEBUG) {echo "<!-- Content Template Hiearchy: "; print_r($vtemplates); echo " -->";}
	return $vtemplates;
 }
}

// Get Author Avatar
// -----------------
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_author_avatar')) {
 function bioship_get_author_avatar($vemail=false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

 	global $vthemesettings;
	$vavatarsize = bioship_apply_filters('skeleton_author_bio_avatar_size', $vthemesettings['authoravatarsize']);
	if (!is_numeric($vavatarsize)) {$vavatarsize = 64;}

	// 2.0.8: fix to get avatar outside content loop
	$vpostid = false;
	if ( (!$vemail) && (is_singular()) ) {global $post; $vpostid = $post->ID;}
	$vauthor = bioship_get_author_by_post($vpostid);
	if ($vauthor) {$vemail = get_the_author_meta('user_email', $vauthor->ID);}

	$vemail = bioship_apply_filters('skeleton_author_gravatar_email', $vemail);
	if (!$vemail) {return false;}
	$vavatar = get_avatar($vemail, $vavatarsize);
	return $vavatar;
 }
}

// Get Author via Post ID
// ----------------------
// 1.8.0: added these helpers as seems no easy way
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_author_by_post')) {
	function bioship_get_author_by_post($vpostid=false) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// 2.0.8: added filter for global author override
		$vauthorid = false;
		if ($vpostid) {$vauthorid = get_post_field('post_author', $vpostid);}
		$vauthorid = bioship_apply_filters('skeleton_author_id', $vauthorid);
		if (THEMEDEBUG) {echo '<!-- Author ID: '.$vauthorid.' -->';}
		if (!$vauthorid) {return false;}
		$vauthor = get_user_by('id', $vauthorid);
		return $vauthor;
	}
}

// Get Author Display from Author Object
// -------------------------------------
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_author_display')) {
	function bioship_get_author_display($vauthor) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		$vauthordisplay = trim($vauthor->data->display_name);
		if ($vauthordisplay == '') {
			$vauthordisplay = trim($vauthor->data->nice_name);
			if ($vauthordisplay == '') {$vauthordisplay = $vauthor->data->user_login;}
		}
		$vauthordisplay = bioship_apply_filters('skeleton_author_display_name', $vauthordisplay);
		if (THEMEDEBUG) {echo '<!-- Author Display Name: '.$vauthordisplay.' -->';}
		return $vauthordisplay;
	}
}

// Get Author Display via Post ID
// ------------------------------
// 2.0.5: moved from skeleton.php
if (!function_exists('bioship_get_author_display_by_post')) {
	function bioship_get_author_display_by_post($vpostid=false) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$vauthor = bioship_get_author_by_post($vpostid);
		if (!$vauthor) {return false;}
		$vauthordisplay = bioship_get_author_display($vauthor);
		return $vauthordisplay;
	}
}

// Count Footer Widgets
// --------------------
// 2.0.5: moved here from skeleton.php
if (!function_exists('bioship_count_footer_widgets')) {
	function bioship_count_footer_widgets() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 1.9.5: simplify to just use theme overrides global here
		// 1.9.9: remove override check (not implemented)
		$vfooterwidgets = 0;
		if (is_active_sidebar('footer-widget-area-1')) {$vfooterwidgets++;}
		if (is_active_sidebar('footer-widget-area-2')) {$vfooterwidgets++;}
		if (is_active_sidebar('footer-widget-area-3')) {$vfooterwidgets++;}
		if (is_active_sidebar('footer-widget-area-4')) {$vfooterwidgets++;}
		return $vfooterwidgets;
	}
}

// Formattable Meta Replacement Output
// -----------------------------------
// 2.0.5: moved here from skeleton.php
if (!function_exists('bioship_get_entry_meta')) {
	function bioship_get_entry_meta($vpostid, $vposttype, $vposition) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vpost, $vthemesettings;

		if ($vposttype == 'page') {$vmetaformat = $vthemesettings['pagemeta'.$vposition];}
		else {$vmetaformat = $vthemesettings['postmeta'.$vposition];}

		// 1.5.0 fix to this for post only list meta
		// 1.6.0 fix from is_search to is_search()
		if ($vposttype == 'post') {
			if ( (is_archive()) || (is_search()) || (!is_singular($vposttype)) ) {
				if ( ($vposition == 'top') && ($vthemesettings['listentrymetatop'] == 0) ) {$vmetaformat = '';}
				// 1.9.9: fix to setting check value (0 not 1)
				if ( ($vposition == 'bottom') && ($vthemesettings['listentrymetabottom'] == 0) ) {$vmetaformat = '';}
			}
		}

		// Apply Meta Filters
		// ------------------
		// (see filters.php example)
		// 1.5.0: optional meta format for an archive list
		if (is_archive() || is_search() || (!is_singular($vposttype)) ) {
			$vformat = bioship_apply_filters('skeleton_list_meta_format_'.$vposition, $vmetaformat);
		} else {
			$vformat = bioship_apply_filters('skeleton_meta_format_'.$vposition, $vmetaformat);
			// 1.9.9: added post type specific meta format filter
			$vformat = bioship_apply_filters('skeleton_meta_format_'.$vposttype, $vformat);
		}
		if ($vformat == '') {return '';} // bug out if empty format string...
		if (THEMEDEBUG) {echo '<!-- Meta Format: '; print_r($vformat); echo ' -->';}

		// Add Meta Separator Span
		// -----------------------
		if (strstr($vformat,' by ')) {$vformat = str_replace(' by ', ' <span class="meta-sep">by</span> ', $vformat);}
		if (strstr($vformat,' BY ')) {$vformat = str_replace(' BY ', ' <span class="meta-sep">BY</span> ', $vformat);}
		if (strstr($vformat,' By ')) {$vformat = str_replace(' By ', ' <span class="meta-sep">By</span> ', $vformat);}
		if (strstr($vformat,'|')) {$vformat = str_replace('|', '<span class="meta-sep">|</span>', $vformat);}
		if (strstr($vformat,':')) {$vformat = str_replace(':', '<span class="meta-sep">:</span>', $vformat);}
		// 2.0.0: remove this one as causing double replacements
		// if (strstr($vformat,'-')) {$vformat = str_replace('-', '<span class="meta-sep">-</span>', $vformat);}

		// Do Replacement Values
		// ---------------------
		// 1.9.9: do single old % to # replacement here to remove old rechecks
		$vformat = str_replace('%', '#', $vformat);

		// New Lines
		// ---------
		// 2.0.8: add new line tag replacements
		$vformat = str_replace('#NEWLINE#', '<br />', $vformat);

		// Post Format Link
		// ----------------
		if ( (strstr($vformat, '#POSTFORMAT#')) || (strstr($vformat, '#POSTFORMATLINK#')) ) {
			$vpostformat = get_post_format();
			if ($vpostformat) {
				// TODO: maybe use hybrid_post_format_link here?
				$vurl = get_post_format_link($vpostformat);
				$vpostformatlink = sprintf( '<a href="%s" class="post-format-link">%s</a>', esc_url($vurl), get_post_format_string($vpostformat) );
			}
			$vformat = str_replace('#POSTFORMAT#', $vpostformatlink, $vformat);
			$vformat = str_replace('#POSTFORMATLINK#', $vpostformatlink, $vformat);
		}

		// Edit Link
		// ---------
		if ( (strstr($vformat, '#EDITLINK#')) || (strstr($vformat, '#EDIT#')) ) {
			// 1.9.9: added post id for possible archive pages
			$veditlink = get_edit_post_link($vpostid);
			if ($veditlink) {
				// 1.5.0: use the post type display label
				if ($vposttype == 'page') {$vposttypedisplay = __('Page','bioship');}
				elseif ($vposttype == 'post') {$vposttypedisplay = __('Post','bioship');}
				else {
					$vposttypeobject = get_post_type_object($vposttype);
					$vposttypedisplay = $vposttypeobject->labels->singular_name;
				}
				$vposttypedisplay = bioship_apply_filters('skeleton_post_type_display', $vposttypedisplay);
				$veditlink = '<span class="edit-link"><a href="'.$veditlink.'">';
				// 2.0.5: added missing translation wrapper
				$veditlink .= __('Edit this','bioship').' '.$vposttypedisplay.'</a>.</span>';
			}
			$vformat = str_replace('#EDITLINK#', $veditlink, $vformat);
			$vformat = str_replace('#EDIT#', $veditlink, $vformat);
		}

		// Permalink
		// ---------
		if (strstr($vformat, '#PERMALINK#')) {
			$vpermalink = get_permalink();
			$vthepermalink = '<a href="'.$vpermalink.'" rel="bookmark">'.__('Permalink','bioship').'</a>';
			$vformat = str_replace('#PERMALINK#', $vpermalink, $vformat);
		}

		// Datelink
		// --------
		if (strstr($vformat, '#DATELINK#')) {
			// 1.8.0: fix to post date/time display to match passsed ID
			$vpermalink = get_permalink($vpostid);
			$vtimeformat = get_option('time_format');
			$vthetime = esc_attr(get_the_time($vtimeformat,$vpostid));
			$vdateformat = get_option('date_format');
			$vpostdate = get_the_date($vdateformat,$vpostid);
			$vthedate = '<time '.hybrid_get_attr('entry-published').'>'.$vpostdate.'</time>';

			$vdatelink = '<a href="'.$vpermalink.'" title="'.$vthetime.'" rel="bookmark"><span class="entry-date">'.$vthedate.'</span></a>';
			// $vdatelink = '<a href="'.$vpermalink.'" title="'.$vthetime.'" rel="bookmark">'.$vthedate.'</a>';
			$vformat = str_replace('#DATELINK#', $vdatelink, $vformat);
		}

		// Parent Page Link
		// ----------------
		// 1.5.0: display parent page link
		if ( (strstr($vformat, '#PARENTPAGE#')) || (strstr($vformat, '#PARENTLINK#')) ) {
			// 1.9.9: added post id for possible archive pages
			if (is_page($vpostid)) {
				$vparentid = $post->post_parent;
				if ($vparentid == 0) {$vpageparent = ''; $vpageparentlink = '';}
				else {
					$vparentpermalink = get_permalink($vparentid);
					$vpageparent = get_the_title($vparentid);
					$vpageparentlink = '<a href="'.$vparentpermalink.'">'.$vpageparent.'</a>';
				}
			} else {$vpageparent = ''; $vpageparentlink = '';}
			// 1.9.9: shifted this outside page check
			$vformat = str_replace('#PARENTPAGE#', $vpageparent, $vformat);
			$vformat = str_replace('#PARENTLINK#', $vpageparentlink, $vformat);
		}

		// Category List / Taxonomy Cats (linked)
		// -----------------------------
		if ( (strstr($vformat, '#CATEGORIES#')) || (strstr($vformat, '#CATS#')) || (strstr($vformat, '#CATSLIST#')) ) {
		  	$vcategorylist = '';
			if ($vposttype == 'post') {
				$vcategorylist = get_the_category_list(', ', '', $vpostid);
				// 2.0.8: count number of categories for display prefix
				$vterms = get_the_terms($vpostid, 'post_tag');
				$vnumcats = count($vterms);
			} elseif ($vposttype == 'page') {$vcategorylist = '';}
			else {
				// handle CPT category terms
				// 2.0.7: declare global post here
				global $post;
				$vtaxonomies = get_object_taxonomies($post);
				$vcategoryterms = array();
				if (count($vtaxonomies) > 0) {
					foreach ($vtaxonomies as $vtaxonomy) {
						if ( ($vtaxonomy != 'post_tag') && ($vtaxonomy != 'post_format') ) {
							if (THEMEHYBRID) {$vterms = hybrid_get_post_terms(array('taxonomy' => 'category', 'text' => '', 'before' => ''));}
							else {$vterms = get_the_terms($vpostid, $vtaxonomy);}
							$vcategoryterms = array_merge($vterms, $vcategoryterms);
						}
					}
					if (count($vcategoryterms) > 0) {
						// 2.0.8: count number of categories for display prefix
						$vnumcats = count($vcategoryterms);
						$vtermlinks = array();
						foreach ($vcategoryterms as $vcategoryterm) {
							$vtermlinks[] = '<a href="'.esc_url(get_term_link($vcategoryterm->slug,'post_tag')).'">'.$vcategoryterm->name.'</a>';
						}
						$vcategorylist = implode(', ', $vtermlinks);
					}
				}
			}
			if ($vcategorylist != '') {
				// 1.8.5: use hybrid attributes entry-terms (category context)
				$vcategorylist = '<span '.hybrid_get_attr('entry-terms', 'category').'>'.$vcategorylist.'</span>';
				// $vcategorylist = '<span class="cat-links">'.$vcategorylist.'</span>';
				// note skeleton classes: entry-utility-prep entry-utility-prep-cat-links
			}

			// 1.9.9: use strip tags to create unlinked category list
			$vcatlist = strip_tags($vcategorylist);

			$vformat = str_replace('#CATS#', $vcatlist, $vformat);
			$vformat = str_replace('#CATEGORIES#', $vcategorylist, $vformat);
			if (strstr($vformat, '#CATSLIST#')) {
				if ($vcategorylist != '') {
					if ($vnumcats == 1) {$vreplace = __('Category: ','bioship').$vcategorylist.'<br>';}
					else {$vreplace = __('Categories: ','bioship').$vcategorylist.'<br>';}
				} else {$vreplace = '';}
				$vformat = str_replace('#CATSLIST#', '', $vformat);
			}
		}

		// Parent Category(s)
		// ------------------
		if ( (strstr($vformat, '#PARENTCATEGORIES#')) || (strstr($vformat, '#PARENTCATS#')) ) {
		  	$vparentcategorylist = ''; $vparentcatlist = '';
			$vcategories = get_the_category($vpostid);
			if (count($vcategories) > 0) {
				$vcatparentids = array();
				foreach ($vcategories as $vcategory) {$vcatparentids[] = $category->category_parent;}
				if (count($vcatparentids) > 0) {
					$vcategorylinks = array(); $vcatlinks = array();
					foreach ($vcatparentids as $vcatparentid) {
						$vcatname = get_cat_name($vcatparentid);
						$vcategorylinks[] = '<a href="'.esc_url(get_category_link($vcatparentid)).'">'.$vcatname.'</a>';
						$vcatlinks[] = $vcatname;
					}
					if (count($vcategorylinks) > 0) {
						if (count($vcategorylinks) == 1) {
							$vparentcategorylist = __('Parent Category: ','bioship').$vcategorylinks[0];
						} else {
							$vparentcategories = implode(', ',$vcategorylinks);
							$vparentcategorylist = __('Parent Categories: ','bioship').$vparentcategories;
							$vparentcats = implode(', ',$vcatlinks);
							$vparentcatlist = __('Parent Categories: ','bioship').$vparentcats;
						}
					}
				}
			}
			// 1.8.5: added hybrid attribute entry-terms (category context)
			if ($vparentcategorylist != '') {$vparentcategorylist = '<span '.hybrid_get_attr('entry-terms', 'category').'>'.$vparentcategorylist.'</span>';}
			if ($vparentcatlist != '') {$vparentcatlist = '<span '.hybrid_get_attr('entry-terms', 'category').'>'.$vparentcatlist.'</span>';}

			$vformat = str_replace('#PARENTCATEGORIES#', $vparentcategorylist, $vformat);
			$vformat = str_replace('#PARENTCATS#', $vparentcatlist, $vformat);
		}

		// Post Tags / CPT Terms (linked)
		// ---------------------
		if ( (strstr($vformat, '#POSTTAGS#')) || (strstr($vformat, '#TAGS#')) || (strstr($vformat, '#TAGSLIST#')) ) {
			$vposttags = '';
			// 1.9.9: handle page as CPT as may have post_tag taxonomy added
			if ($vposttype == 'post') {
				$vposttags = trim(get_the_tag_list('', ', '));
			} else {
				// handle CPT tag terms ('post_tag' taxonomy)...
				// 2.0.7: declare global post here
				global $post;
				$vtaxonomies = get_object_taxonomies($post);
				if (in_array('post_tag',$vtaxonomies)) {
					if (THEMEHYBRID) {$vposttags = hybrid_get_post_terms(array('taxonomy' => 'post_tag', 'text' => '', 'before' => ''));}
					else {
						$vtagterms = get_the_terms($vpostid,'post_tag');
						if (count($vtagterms) > 0) {
							// 2.0.8: count tags for display prefix
							$vnumtags = count($vtagterms);
							$vtermlinks = array();
							foreach ($vtagterms as $vtagterm) {
								$vtermlinks[] = '<a href="'.esc_url(get_term_link($vtagterm->slug,'post_tag')).'">'.$vtagterm->name.'</a>';
							}
							$vposttags = implode(', ', $vtermlinks);
						}
					}
				}
			}
			if ($vposttags != '') {
				// 1.8.5: use hybrid attribute entry-terms
				$vposttags = '<span '.hybrid_get_attr('entry-terms', 'post_tag').'>'.$vposttags.'</span>';
				// $vposttags = '<span class="tag-links">'.$vposttags.'</span>';
				// note skeleton classes: entry-utility-prep entry-utility-prep-tag-links
			}

			// 1.9.9: use strip tags to create unlinked tag list
			$vtaglist = strip_tags($vposttags);

			$vformat = str_replace('#TAGS#', $vtaglist, $vformat);
			$vformat = str_replace('#POSTTAGS#', $vposttags, $vformat);
			if (strstr($vformat,'#TAGSLIST#')) {
				if ($vposttags != '') {
					// 2.0.8: change display prefix for more than one tag
					if ($vnumtags == 1) {$vreplace = __('Tagged: ','bioship').$vposttags.'<br>';}
					else {$vreplace = __('Tags: ','bioship').$vposttags.'<br>';}
				} else {$vreplace = '';}
				$vformat = str_replace('#TAGSLIST#', $vreplace, $vformat);
			}
		}

		// Comments
		// --------
		if ( (strstr($vformat, '#COMMENTS#')) || (strstr($vformat, '#COMMENTSLINK#')) ) {
			$vnumcomments = (int)get_comments_number(get_the_ID());
			$vcommentsdisplay = '';
			// 1.9.9: add possible missing argument for archives
			if (comments_open($vpostid)) {
				if ($vnumcomments === 0) {$vcommentsdisplay = number_format_i18n(0).' '.__('comments','bioship').'.';}
				elseif ($vnumcomments === 1) {$vcommentsdisplay = number_format_i18n(1).' '.__('comment','bioship').'.';}
				elseif ($vnumcomments > 1) {$vcommentsdisplay = number_format_i18n($vnumcomments).' '.__('comments','bioship').'.';}
				$vcommentsdisplay = '<span class="comments-link">'.$vcommentsdisplay.'</span>';
			}
			$vformat = str_replace('#COMMENTS#', $vcommentsdisplay, $vformat);
			$vformat = str_replace('#COMMENTSLINK#', $vcommentsdisplay, $vformat);
		}
		if ( (strstr($vformat, '#COMMENTSPOPUP#')) || (strstr($vformat, '#COMMENTSPOPUPLINK#')) ) {
			$vcomments = '';
			// 1.9.9: add possible missing argument for archives
			if (comments_open($vpostid)) {
				// 1.9.9: add switch for conditional load of comments popup script
				global $vthemecommentspopup; $vthemecommentspopup = true;
				$vcommentscss = 'no-comments';
				$vnumcomments = (int)get_comments_number(get_the_ID());
				if ($vnumcomments === 1) {$vcommentscss = 'one-comment';}
				elseif ($vnumcomments > 1) {$vcommentscss = 'multiple-comments';}
				ob_start();
				comments_popup_link(
					number_format_i18n(0).' '.__('comments','bioship').'.',
					number_format_i18n(1).' '.__('comment','bioship').'.',
					number_format_i18n($vnumcomments).' '.__('comments','bioship').'.', // ? __('% comments','bioship').'.' ?
					$vcommentscss, '' );
				$vcomments = ob_get_contents();
				ob_end_clean();
				$vcomments = '<span class="comments-link">'.$vcomments.'</span>';
			}
			$vformat = str_replace('#COMMENTSPOPUP#', $vcomments, $vformat);
			$vformat = str_replace('#COMMENTSPOPUPLINK#', $vcomments, $vformat);
		}

		// Author Info
		// -----------
		// 1.8.0: disambiguate #AUTHOR# and #AUTHORURL#
		if ( (strstr($vformat, '#AUTHORLINK#')) || (strstr($vformat, '#AUTHORNAME#'))
		  || (strstr($vformat, '#AUTHOR#')) || (strstr($vformat, '#AUTHORURL#')) ) {

			// 1.8.0: use separate get author display name
			$vauthordisplay = bioship_get_author_display_by_post($vpostid);

			// 1.8.0: fix to the author posts link, add title tag
			$vauthorid = get_post_field('post_author', $vpostid);
			$vauthorurl = get_author_posts_url($vauthorid);
			if ($vposttype == 'page') {$vposttypedisplay = __('Pages','bioship');}
			elseif ($vposttype == 'post') {$vposttypedisplay = __('Posts','bioship');}
			else {
				$vposttypeobject = get_post_type_object($vposttype);
				$vposttypedisplay = $vposttypeobject->labels->name;
			}
			// 2.0.5: added missing translation wrappers
			$vauthorpoststitle = __('View all','bioship').' '.$vposttypedisplay.' '.__('by','bioship').' '.$vauthordisplay.'.';
			$vauthoranchor = '<a href="'.$vauthorurl.'" title="'.$vauthorpoststitle.'">'.$vauthordisplay.'</a>';
			$vauthorlink = bioship_skeleton_author_posts_link($vauthorurl);

			// 1.9.8: fix to use vauthordisplay not old vauthor variable
			$vformat = str_replace('#AUTHORLINK#', $vauthorlink, $vformat);
			$vformat = str_replace('#AUTHORURL#', $vauthorurl, $vformat);
			$vformat = str_replace('#AUTHOR#', $vauthoranchor, $vformat);
			$vformat = str_replace('#AUTHORNAME#', $vauthordisplay, $vformat);
		}

		// add Meta Wrapper
		$vmeta = '<span class="meta-prep">'.PHP_EOL;
		$vmeta .= $vformat.PHP_EOL;
		$vmeta .= '</span>'.PHP_EOL;

		// allow for complete meta override
		$vmeta = bioship_apply_filters('skeleton_meta_override_'.$vposition, $vmeta);
		return $vmeta;
	}
}


// -------------------
// === Setup Theme ===
// -------------------

if (!function_exists('bioship_setup')) {
 add_action('after_setup_theme','bioship_setup');
 function bioship_setup() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemename, $vthemesettings, $vthemedirs;

	// Language Translation
	// --------------------
	// note: https://make.wordpress.org/meta/handbook/documentation/translations/
	// ref: https://ulrich.pogson.ch/load-theme-plugin-translations
	// 2.0.5: load multiple language locations
	load_theme_textdomain('bioship', trailingslashit(WP_LANG_DIR).'bioship');
	load_theme_textdomain('bioship', get_stylesheet_directory().'/languages' );
	load_theme_textdomain('bioship', get_template_directory().'/languages');

	// Editor Styles
	// -------------
	// 1.9.5: removed is_rtl check as handled automatically by add_editor_style
	// if (!is_rtl()) {$veditorstyle = 'editor-style.css';} else {$veditorstyle = 'editor-style-rtl.css';}
	$veditorstyleurl = bioship_file_hierarchy('url', 'editor-style.css', $vthemedirs['css']);
	if ($veditorstyleurl) {add_editor_style($veditorstyleurl);}

	// Dynamic Editor Styles
	// ---------------------
	if ( (isset($vthemesettings['dynamiceditorstyles'])) && ($vthemesettings['dynamiceditorstyles'] == '1') ) {

		// TODO: better way to maybe enqueue matching Google font?
		// (currently done in functions.php)

		// 1.9.5: add dynamic editor styles to match skin theme settings styles
		// ref: https://www.mattcromwell.com/dynamic-tinymce-editor-styles-wordpress/
		if (is_admin()) {add_filter('tiny_mce_before_init', 'bioship_add_dynamic_editor_styles');}
		if (!function_exists('bioship_add_dynamic_editor_styles')) {
		 function bioship_add_dynamic_editor_styles($mceInit) {
		 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
			global $vthemesettings;

			// Content Background Color
			if ( (isset($vthemesettings['contentbgcolor'])) && ($vthemesettings['contentbgcolor'] != '') ) {
				$vstyles = 'body.mce-content-body {background-color: '.$vthemesettings['contentbgcolor'].';} ';
			}

			// Content Typography
			// 1.9.8: set empty undefined variables
			$vtypography = false; $vtyporules = ''; $vstyles = '';
			if (isset($vthemesettings['content_typography'])) {$vtypography = $vthemesettings['content_typography'];}
			elseif (isset($vthemesettings['body_typography'])) {$vtypography = $vthemesettings['body_typography'];}

			if ( ($vtypography) && (is_array($vtypography)) ) {
				if (isset($vtypography['font-size'])) {$vtypography['size'] = $vtypography['font-size'];}
				if (isset($vtypography['font-family'])) {$vtypography['face'] = $vtypography['font-family'];}
				if (isset($vtypography['font-style'])) {$vtypography['style'] = $vtypography['font-style'];}

				if ($vtypography['color'] != '') {$vtyporules .= "color:".$vtypography['color']."; ";}
				if ($vtypography['size'] != '') {$vtyporules .= "font-size:".$vtypography['size']."; ";}
				if ($vtypography['face'] != '') {
					if (strstr($vtypography['face'],'+')) {$vtypography['face'] = '"'.str_replace('+',' ',$vtypography['face']).'"';}
					// note: double quotes must be double escaped or changed to single for tinyMCE javascript
					if (strstr($vtypography['face'],',')) {
						// $vtypography['face'] = str_replace('"','\\"',$vtypography['face']);
						$vtypography['face'] = str_replace('"',"'",$vtypography['face']);
						$vtyporules .= "font-family:".$vtypography['face']."; ";
					} else {$vtyporules .= "font-family:'".$vtypography['face']."'; ";}
				}
				if ($vtypography['style'] != '') {
					if ($vtypography['style'] == 'bold') {$vtyporules .= "font-weight: bold;";}
					else {$vtyporules .= "font-style:".$vtypography['style']."; ";}
				}
				if (isset($vtypography['font-weight'])) {$vtyporules .= "font-weight:".$vtypography['font-weight']."; ";}
				if (isset($vtypography['line-height'])) {$vtyporules .= "line-height:".$vtypography['line-height']."; ";}
				if (isset($vtypography['letter-spacing'])) {$vtyporules .= "letter-spacing:".$vtypography['letter-spacing']."; ";}
				if (isset($vtypography['text-transform'])) {$vtyporules .= "text-transform:".$vtypography['text-transform']."; ";}
				if (isset($vtypography['font-variant'])) {$vtyporules .= "font-variant:".$vtypography['font-variant']."; ";}

				$vstyles .= "body.mce-content-body, body.mce-content-body .column .inner, body.mce-content-body .columns .inner {".$vtyporules."} ";
			}

			// Inputs
			if ( (isset($vthemesettings['inputcolor'])) || (isset($themeoptions['inputbgcolor'])) ) {
				$vinputs = " body.mce-content-body input[type='text'], body.mce-content-body input[type='checkbox'], ";
				$vinputs .= " body.mce-content-body input[type='password'], body.mce-content-body select, body.mce-content-body textarea {";
				if ($vthemesettings['inputcolor'] != '') {$vinputs .= "color: ".$vthemesettings['inputcolor']."; ";}
				if ($vthemesettings['inputbgcolor'] != '') {$vinputs .= "background-color: ".$vthemesettings['inputbgcolor'].";";}
				$vinputs .= "} "; $vstyles .= $vinputs;
			}

			// Link Colors and Underlines
			if ( ($vthemesettings['alinkunderline'] != 'inherit') || ($vthemesettings['link_color'] != '') ) {
				$vlinks = "body.mce-content-body a, body.mce-content-body a:visited {";
				if ($vthemesettings['link_color'] != '') {$vlinks .= "color:".$vthemesettings['link_color'].";";}
				if ($vthemesettings['alinkunderline'] != 'inherit') {$vlinks .= " text-decoration:".$vthemesettings['alinkunderline'].";";}
				$vlinks .= "} "; $vstyles .= $vlinks;
			}
			if ( ($vthemesettings['alinkhoverunderline'] != 'inherit') || ($vthemesettings['link_color'] != '') ) {
				$vlinks = "body.mce-content-body a:hover, body.mce-content-body a:focus, body.mce-content-body a:active {";
				if ($vthemesettings['link_hover_color'] != '') {$vlinks .= "color:".$vthemesettings['link_hover_color'].";";}
				if ($vthemesettings['alinkhoverunderline'] != 'inherit') {$vlinks .= " text-decoration:".$vthemesettings['alinkhoverunderline'].";";}
				$vlinks .= "} "; $vstyles .= $vlinks;
			}

			// 2.0.8: add button style rules also (as in skin.php)
			$woocommercebuttons = array('.woocommerce a.alt.button', '.woocommerce button.alt.button', '.woocommerce input.alt.button',
				'.woocommerce #respond input.alt#submit', '.woocommerce #content input.alt.button',
				'.woocommerce-page a.alt.button', '.woocommerce-page button.alt.button', '.woocommerce-page input.alt.button',
				'.woocommerce-page #respond input.alt#submit', '.woocommerce-page #content input.alt.button');
			$vbuttons = "body.mce-content-body button, body.mce-content-body input[type='reset'],
				body.mce-content-body input[type='submit'], body.mce-content-body input[type='button'],
				body.mce-content-body a.button, body.mce-content-body .button";
			if ( ($vthemesettings['button_bgcolor_bottom'] == '') || ($vthemesettings['button_bgcolor_bottom'] == $vthemesettings['button_bgcolor_top']) ) {
				$vbuttonrules = "	background: ".$vthemesettings['button_bgcolor_top']."; ";
				$vbuttonrules .= "background-color: ".$vthemesettings['button_bgcolor_top'].";".PHP_EOL;
				// $vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
			} else {
				$vtop = $vthemesettings['button_bgcolor_top'];
				$vbottom = $vthemesettings['button_bgcolor_bottom'];
				$vbuttonrules = "	background: ".$vtop."; background-color: ".$vtop.";".PHP_EOL;
				$vbuttonrules .= "	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ".$vtop."), color-stop(100%, ".$vbottom."));".PHP_EOL;
				$vbuttonrules .= "	background: -webkit-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
				$vbuttonrules .= "	background: -o-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
				$vbuttonrules .= "	background: -ms-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
				$vbuttonrules .= "	background: -moz-linear-gradient(top, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
				$vbuttonrules .= "	background: linear-gradient(top bottom, ".$vtop." 0%, ".$vbottom." 100%);".PHP_EOL;
				// $vbuttonrules .= "	-pie-background: linear-gradient(top, ".$vtop.", ".$vbottom.");".PHP_EOL;
				// $vbuttonrules .= "	behavior: url('".$vpieurl."');".PHP_EOL;
			}

			// TODO: add button font rules
			// $vbuttonrules .= '	'.$vbuttonfontrules.PHP_EOL;

			if ( (isset($vthemesettings['woocommercebuttons'])) && ($vthemesettings['woocommercebuttons'] == '1') ) {
				$woocommerceselectors = implode(', body.mce-content-body ', $woocommercebuttons);
				$vbuttons .= ', '.PHP_EOL.'body.mce-content-body '.$woocommerceselectors.' ';
			}
			$vstyles .= $vbuttons.' {'.PHP_EOL.$vbuttonrules.'}'.PHP_EOL;

			// TODO: add button hover style rules


			// TODO: maybe add any other relevant style rules?

			// 1.9.8: addded check if array key exists
			if (isset($mceInit['content_style'])) {$mceInit['content_style'] .= ' '.$vstyles.' ';}
			else {$mceInit['content_style'] = $vstyles;}

			return $mceInit;
		 }
		}

	}

	// Post Thumbnail Support
	// ----------------------
	add_theme_support('post-thumbnails'); // add post thumbnail support
	// 1.5.0: add custom post type thumbnail support override
	// CHECKME: are page thumbnails (featured images) on by adding default support?!
	$vthumbcpts = $vthemesettings['thumbnailcpts'];
	if ( ($vthumbcpts) && (is_array($vthumbcpts)) && (count($vthumbcpts) > 0) ) {
		foreach ($vthumbcpts as $vcpt => $vvalue) {
			if ( ($vvalue == '1') && (!post_type_supports($vcpt,'thumbnail')) ) {
				add_post_type_support($vcpt, 'thumbnail');
			}
		}
	}

	// Set Default Post Thumbnail Size
	// -------------------------------
	// TODO: maybe add a 'post-thumbnail' image size (for the post writing screen) ?
	// (ref: _wp_post_thumbnail_html in /wp-admin/includes/post.php)

	// get_option('thumbnail_image_w'); get_option('thumbnail_image_h');
	// 1.8.0: changed default to 200x200 as square250 already exists
	// 1.5.0: changed default to 250x250 from 150x150
	// for better FB sharing support, as minimum required there is 200x200
	$vthumbnailwidth = bioship_apply_filters('skeleton_thumbnail_width', 200);
	$vthumbnailheight = bioship_apply_filters('skeleton_thumbnail_height', 200);
	$vcrop = get_option('thumbnail_crop');
	$vthumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ($vthumbnailcrop == 'nocrop') {$vcrop = false;}
	if ($vthumbnailcrop == 'auto') {$vcrop = true;}
	if (strstr($vthumbnailcrop,'-')) {$vcrop = explode('-', $vthumbnailcrop);}

	set_post_thumbnail_size($vthumbnailwidth, $vthumbnailheight, $vcrop);

	// Ref: Wordpress Thumbnail Size Defaults
	// 'thumbnail' 		: Thumbnail (150 x 150 hard cropped)
	// 'medium'    		: Medium resolution (300 x 300 max height 300px)
	// 'medium_large' 	: Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
	// 'large' 			: Large resolution (1024 x 1024 max height 1024px)
	// 'full' 			: Full resolution (original size uploaded)
	// with WooCommerce
	// 'shop_thumbnail' : Shop thumbnail (180 x 180 hard cropped)
	// 'shop_catalog'	: Shop catalog (300 x 300 hard cropped)
	// 'shop_single'    : Shop single (600 x 600 hard cropped)

	// Add Image Sizes array (via Skeleton)
	// 150px square, 250px square, 4:3 Video, 16:9 Video
	// 1.5.0: added open graph size 560x292
	// 2.0.5: replaced with prefixed image sizes
	// (note: thumbnail regeneration in
	$vimagesizes[0] = array('name' => 'bioship-150', 'width' => 150, 'height' => 150, 'crop' => $vcrop);
	$vimagesizes[1] = array('name' => 'bioship-250', 'width' => 250, 'height' => 250, 'crop' => $vcrop);
	$vimagesizes[2] = array('name' => 'bioship-4-3', 'width' => 320, 'height' => 240, 'crop' => $vcrop);
	$vimagesizes[3] = array('name' => 'bioship-16-9', 'width' => 320, 'height' => 180, 'crop' => $vcrop);
	$vimagesizes[4] = array('name' => 'bioship-opengraph', 'width' => 560, 'height' => 292, 'crop' => $vcrop);

	$vimagesizes = bioship_apply_filters('skeleton_image_sizes', $vimagesizes);

	if (count($vimagesizes) > 0) {
		foreach ($vimagesizes as $vsize) {
			add_image_size($vsize['name'], $vsize['width'], $vsize['height'], $vsize['crop']);
		}
	}

 }
}

// maybe Regenerate Thumbnails
// ---------------------------
// 2.0.5: added for on-the-fly regeneration of new size names
// ref: https://gist.github.com/rnagle/2366998
if (!function_exists('bioship_regenerate_thumbnails')) {
 function bioship_regenerate_thumbnails($vpostid, $vsize=false) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

    $vattachmentid = get_post_thumbnail_id($vpostid);
    if ($vattachmentid) {
	    $vattachmentmeta = wp_get_attachment_metadata($vattachmentid);
	    $vsizes = array_keys($vattachmentmeta['sizes']);
	    if ( (!$vsize) || (!in_array($vsize, $vsizes)) ) {
	        include_once(ABSPATH.'/wp-admin/includes/image.php');
	        $vattachedfile = get_attached_file($vattachmentid);
	        // note: this is where the actual regeneration occurs
	        $vgenerated = wp_generate_attachment_metadata($vattachmentid, $vattachedfile);
	        $vupdated = wp_update_attachment_metadata($vattachmentid, $vgenerated);
	    }
	}
 }
}

// Enqueue Skeleton Scripts
// ------------------------
// note: Styles moved to Skin Section
// note: for Foundation loading functions see muscle.php
// 1.8.0: added filemtime cache busting option
// 2.0.2: use THEMESLUG instead of vthemename
if (!function_exists('bioship_scripts')) {
 add_action('wp_enqueue_scripts', 'bioship_scripts');
 function bioship_scripts() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemename, $vthemesettings, $vjscachebust, $vthemedirs;

	// 1.9.5: check and set filemtime use just once
	$vfilemtime = false; if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// maybe load jQuery from Google CDN
	// ---------------------------------
	// 1.9.5: only do this for frontend to prevent admin conflicts (with load-scripts.php)
	// 2.0.7: do this all via filter to avoid reregistering script
	if ( (!is_admin()) && ($vthemesettings['jquerygooglecdn'] == '1') ) {

		// 1.5.0: added a jQuery fallback for if Google CDN fails
		// Ref: http://stackoverflow.com/questions/1014203/best-way-to-use-googles-hosted-jquery-but-fall-back-to-my-hosted-library-on-go
		if (!function_exists('bioship_jquery_fallback')) {
		 add_filter('script_loader_tag', 'bioship_jquery_fallback', 10, 2);
		 function bioship_jquery_fallback($vscripttag, $vhandle) {
			if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

			// 1.8.5: added jquery handle check
			// ref: http://stackoverflow.com/a/17431575/5240159
			// Get jquery handle - WP 3.6 or newer changed the jQuery handle
			// note: new jquery handle is a dependency handle with children of jquery-core and jquery-migrate
			global $wp_version, $wp_scripts;
			$vjqueryhandle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';

			// get the built-in jQuery version for current WordPress install
			// 1.9.5: fix to silly typo here to make it work again
			// 2.0.7: use wp_scripts global name directly
			$vwpjqueryversion = $wp_scripts->registered[$vjqueryhandle]->ver;

			$vjqueryversion = bioship_apply_filters('skeleton_google_jquery_version', $vwpjqueryversion);
			$vjquery = 'https://ajax.googleapis.com/ajax/libs/jquery/'.$vjqueryversion.'/jquery.min.js';
			// note: test with wp_remote_fopen pointless here as comes from server not client

			// 2.0.7: change script source directly instead or reregistering
			$vsrcstart = "src='"; $vsrcend = "'";
			$vposa = strpos($vscripttag, $vsrcstart) + strlen($vsrcstart);
			$vposb = strpos($vscripttag, $vsrcend);
			$vsrctemp = substr($vscripttag, $vposa, ($vposb - $vposa));
			$vscripttag = str_replace($vsrctemp, $vjquery, $vscripttag);

			if (THEMEDEBUG) {
				echo "<!-- jQuery Handle: ".$vjqueryhandle." -->";
				echo "<!-- WP jQuery Version: ".$vwpjqueryversion." -->";
				echo "<!-- WP jQuery URL: ".$vsrctemp." -->";
				echo "<!-- New jQuery URL: ".$vjquery." -->";
			}

			$vjqueryhandle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';
			if ( ($vhandle == $vjqueryhandle) && (strstr($vscripttag, 'jquery.min.js')) ) {
				global $vthemesettings, $vjscachebust;
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
					$vjscachebust = date('ymdHi', filemtime(ABSPATH.WPINC.'/js/jquery/jquery.js'));
				}
				$vjquery = urlencode(site_url().'/wp-includes/js/jquery/jquery.js?'.$vjscachebust);
				// 2.0.7: fix to undefined variable warning
				$vconsoledebug = '';
				if (THEMEDEBUG) {$vconsoledebug = "console.log('Loading jQuery from Google CDN failed. Loading jQuery from site.'); ";}
				$vfallback = "</script><script>if (!window.jQuery) {".$vconsoledebug."document.write(unescape('%3Cscript src=\"".$vjquery."\"%3E%3C\/script%3E'));}</script>";
				$vscripttag = str_replace('</script>', $vfallback, $vscripttag);
			}
			return $vscripttag;
		 }
		}
	}

	// superfish.js
	// ------------
	// 1.8.5: conditionally load only if there is primary Nav Menu
	// 1.9.5: fix to dashes in theme name slug for theme mods
	$vthememods = get_option('theme_mods_'.THEMESLUG);
	if ( (isset($vthememods['nav_menu_locations']['primary'])) && ($vthememods['nav_menu_locations']['primary'] != '') ) {
		$vsuperfish = bioship_file_hierarchy('both', 'superfish.js', $vthemedirs['js']);
		if (is_array($vsuperfish)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vsuperfish['file']));}
			// 2.0.1: add theme name prefix to script handle
			wp_enqueue_script(THEMESLUG.'-superfish', $vsuperfish['url'], array('jquery'), $vjscachebust, true);
		}

		// 1.8.5: count and set main menu (not submenu) items
		$vmenuid = $vthememods['nav_menu_locations']['primary'];
		// $vmainmenu = get_term($vmenuid, 'nav_menu');
		if (THEMEDEBUG) {echo "<!-- Main Menu ID: ".$vmenuid." -->";}
		$vmenuitems = wp_get_nav_menu_items($vmenuid, 'nav_menu');
		// if (THEMEDEBUG) {echo "<!-- Main Menu Items: "; print_r($vmenuitems)." -->";}
		// 2.0.7: fix for undefined variable warning
		$vmenumainitems = 0;
		foreach ($vmenuitems as $vitem) {
			if ($vitem->menu_item_parent == 0) {$vmenumainitems++;}
		}
		if (THEMEDEBUG) {echo "<!-- Menu Main Items: ".$vmenumainitems." -->";}
		// note: this menu item count is used in skin.php
		if (get_option($vthemename.'_menumainitems') != $vmenumainitems) {
			// 2.0.5: remove unnecessary add_option fallback
			update_option($vthemename.'_menumainitems', $vmenumainitems);
		}
	}

	// formalize.js
	// ------------
	if ($vthemesettings['loadformalize']) {
		$vformalize = bioship_file_hierarchy('both', 'jquery.formalize.min.js', $vthemedirs['js']);
		if (is_array($vformalize)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vformalize['file']));}
			wp_enqueue_script('formalize', $vformalize['url'], array('jquery'), $vjscachebust, true);
		}
	}

	// theme init.js
	// -------------
	$vinit = bioship_file_hierarchy('both', 'init.js', $vthemedirs['js']);
	if (is_array($vinit)) {
		if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vinit['file']));}
		// 2.0.1: add theme name prefix to script handle
		wp_enqueue_script(THEMESLUG.'-init', $vinit['url'], array('jquery'), $vjscachebust, true);
	}

	// custom.js
	// ---------
	$vcustom = bioship_file_hierarchy('both', 'custom.js', $vthemedirs['js']);
	if (is_array($vcustom)) {
		if ($vfilemtime) {$vjscachebust = date('ymdHi', filemtime($vcustom['file']));}
		// 2.0.1: add theme name prefix to script handle
		wp_enqueue_script(THEMESLUG.'-custom', $vcustom['url'], array('jquery'), $vjscachebust, true);
	}

	// maybe enqueue comment reply script
	if ( is_singular() && comments_open() && get_option('thread_comments') ) {wp_enqueue_script('comment-reply');}

	// Better WordPress Minify Integration
	// -----------------------------------
	// 2.0.2: added to make automatic script filtering possible
	// (as bwp_minify_ignore filter has been missed)
	global $bwp_minify;
	if ( (is_object($bwp_minify)) && (property_exists($bwp_minify, 'print_positions')) ) {
		$vpositions = $bwp_minify->print_positions;
		if ( (is_array($vpositions)) && (isset($vpositions['ignore'])) ) {
			$vhandles = $vpositions['ignore'];
			$vnominifyscripts = array(THEMESLUG.'-init', THEMESLUG.'-custom');
			$vnominifyscripts = apply_filters('bioship_bwp_nominify_scripts', $vnominifyscripts);
			foreach ($vnominifyscripts as $vhandle) {
				if (!in_array($vhandle, $vhandles)) {$vhandles[] = $vhandle;}
			}
			if ($vhandles != $vpositions['style']) {
				$vpositions['ignore'] = $vhandles;
				$bwp_minify->print_positions = $vpositions;
				if (THEMEDEBUG) {echo "<!-- BWP Ignore Scripts: "; print_r($vhandles); echo " -->";}
			}
		}
	}

 }
}

// Change/Remove the Meta Generator Tag
// ------------------------------------
// 1.8.5: moved to skull from muscle.php
// changed name from muscle_meta_generator to skeleton_meta_generator
if (!function_exists('bioship_meta_generator')) {
 add_filter('the_generator', 'bioship_meta_generator', 999);
 // 1.8.5: add Hybrid filter to match
 add_filter('hybrid_meta_generator', 'bioship_meta_generator');
 function bioship_meta_generator($vgenerator) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	$vgenerator = bioship_apply_filters('skeleton_generator_meta', '');
	return $vgenerator;
 }
}

// Mobile Header Meta
// ------------------
// ref: http://www.quirksmode.org/blog/archives/2010/09/combining_meta.html
// ref: http://stackoverflow.com/questions/1988499/meta-tags-for-mobile-should-they-be-used
if (!function_exists('bioship_mobile_meta')) {
 // 1.8.5: add to wp_head hook instead of separate skeleton_mobile_meta action
 add_action('wp_head', 'bioship_mobile_meta', 2);
 function bioship_mobile_meta() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// TODO: somehow test this specific-width mobile meta line has any effect?
	// (320 is a good minimum width but not sure if this info helps mobiles)
	// $vmobilemeta .= '<meta name="MobileOptimized" content="320">'.PHP_EOL;

	$vmobilemeta = "<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->".PHP_EOL;
	$vmobilemeta = '<meta name="HandheldFriendly" content="True">'.PHP_EOL; // i wanna hold your haaand...

	// 1.8.5: fix to remove duplicate line if using Hybrid Core
	if (!THEMEHYBRID) {$vmobilemeta .= '<meta name="viewport" content="width=device-width, initial-scale=1" />'.PHP_EOL;}
	$vmobilemeta = bioship_apply_filters('skeleton_mobile_metas', $vmobilemeta);
	echo $vmobilemeta;
 }
}

// Site Icons Loader
// -----------------
// ref: http://www.jonathantneal.com/blog/understand-the-favicon/
if (!function_exists('bioship_site_icons')) {

 // 2.0.8: add the new icon size upload filter
 add_filter('site_icon_image_sizes', 'bioship_site_icon_sizes');

 add_action('admin_head', 'bioship_site_icons');
 add_action('wp_head', 'bioship_site_icons');

 function bioship_site_icons() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemedirs;

	// 2.0.8: maybe use the global site icon
	if (THEMESITEICON) {
		// maybe output debug info for site icon sizes
		if (THEMEDEBUG) {
			$viconid = get_option('site_icon');
			if ($viconid) {
				$viconmeta = wp_get_attachment_metadata($viconid);
				$vsizes = array_keys($viconmeta['sizes']);
				echo "<!-- Site Icon Sizes: "; print_r($vsizes); echo " -->";
			}
		}

		// 2.0.8: use new site icon tag filter and output
		add_filter('site_icon_meta_tags', 'bioship_site_icon_tags');
		wp_site_icon(); return;
	}

	// 2.0.8: now a fallback if no global site icon
	// 2.0.8: use esc_url on all icon hrefs
	// 2.0.8: explicit files override theme settings
	// 1.8.0: added fallbacks to auto-check for favicon files when URLs are not set

	// <!-- Apple Touch, use the 144x144 default, then optional sizes -->
	$vappleicons = '';
	$vwineighttile = bioship_file_hierarchy('url', 'win8tile.png', $vthemedirs['img']);
	if (!$vwineighttile) {$vwineighttile = $vthemesettings['wineighttile'];}
	if ( ($vwineighttile) && ($vwineighttile != '') ) {
		// 2.0.8: fix to use variable not theme settings value
		$vappleicons = '<link rel="apple-touch-icon-precomposed" size="144x144" href="'.esc_url($vwineighttile).'">'.PHP_EOL;
	}
	$vicons = bioship_apply_filters('skeleton_apple_icons', $vappleicons);

	// <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices, 57x57 size -->
	$vappletouchicon = bioship_file_hierarchy('url', 'apple-touch-icon.png', $vthemedirs['img']);
	if (!$vappletouchicon) {$vappletouchicon = $vthemesettings['appletouchicon'];}
	if ( ($vappletouchicon) && ($vappletouchicon != '') ) {
		// 2.0.8: fix to use variable not theme settings value
		$vicons .= '<link rel="apple-touch-icon-precomposed" href="'.esc_url($vappletouchicon).'">'.PHP_EOL;
		$vicons .= '<link rel="apple-touch-icon" href="'.esc_url($vappletouchicon).'">'.PHP_EOL;
	}

	// <!-- For anything accepting PNG icons, 96x96 default -->
	$vfaviconpng = bioship_file_hierarchy('url', 'favicon.png', $vthemedirs['img']);
	if (!$vfaviconpng) {$vfaviconpng = $vthemesettings['faviconpng'];}
	if ( ($vfaviconpng) && ($vfaviconpng != '') ) {
		$vicons .= '<link rel="icon" href="'.esc_url($vfaviconpng).'">'.PHP_EOL;
	}

	// <!-- Just for IE, the default 32x32 or 16x16 size -->
	$vfaviconico = bioship_file_hierarchy('url', 'favicon.ico', $vthemedirs['img']);
	// 1.8.0: allow for default favicon fallback in root directory
	if ( (!$vfaviconico) && (file_exists(ABSPATH.DIRSEP.'favicon.ico')) ) {
		$vfaviconico = trailingslashit(site_url()).'favicon.ico';
	}
	if (!$vfaviconico) {$vfaviconico = $vthemesettings['faviconico'];}
	if ( ($vfaviconico) && ($vfaviconico != '') ) {
		$vicons .= '<!--[if IE]><link rel="shortcut icon" href="'.esc_url($vfaviconico).'"><![endif]-->'.PHP_EOL;
	}

	// <!-- For Windows 8, the tile and background -->
	if ( ($vwineighttile) && ($vwineighttile != '') ) {
		$vwineightbg = '#FFFFFF';
		if ($vthemesettings['wineightbg'] != '') {$vwineightbg = $vthemesettings['wineightbg'];}
		$vicons .= '<meta name="msapplication-TileColor" content="'.esc_url($vwineightbg).'">'.PHP_EOL;
		$vicons .= '<meta name="msapplication-TileImage" content="'.esc_url($vwineighttile).'">'.PHP_EOL;
	}

	// 1.8.5: moved optional startup image output here
	$vstartupimages = bioship_apply_filters('skeleton_startup_images', '');
	if ($vstartupimages != '') {$vicons .= $vstartupimages;}

	// 1.8.5: added icon override filter
	$vicons = bioship_apply_filters('skeleton_icons_override', $vicons);
	echo $vicons;
 }
}

// Site Icon Size Filter
// ---------------------
if (!function_exists('bioship_site_icon_sizes')) {
 function bioship_site_icon_sizes($vsizes) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// note: WordPress defaults are 32, 180, 192 and 270
	$vnewsizes = array(57, 72, 76, 96, 114, 120, 144, 152);
	$vsizes = array_merge($vsizes, $vnewsizes);
	return $vsizes;
 }
}

// Site Icon Tags Filter
// ---------------------
// 2.0.8: added this filter to add site icon meta tags
if (!function_exists('bioship_site_icon_tags')) {
 function bioship_site_icon_tags($vtags) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// <!-- Apple Touch, use the 144x144 default, then optional sizes -->
	$vwineighttile = bioship_file_hierarchy('url', 'win8tile.png', $vthemedirs['img']);
	if (!$vwineighttile) {$vwineighttile = get_site_icon_url(144, $vthemesettings['wineighttile']);}
	if ($vwineighttile != '') {
		$vtags[] = '<link rel="apple-touch-icon-precomposed" size="144x144" href="'.esc_url($vwineighttile).'">'.PHP_EOL;
	}

	$vappleicons = bioship_apply_filters('skeleton_apple_icons', '');
	if ($vappleicons != '') {
		$vicons = explode(PHP_EOL, $vappleicons);
		foreach ($vicons as $vicon) {
			if ($vicon != '') {$vtags[] = $vicon;}
		}
	}

	// <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices, 57x57 size -->
	$vappletouchicon = bioship_file_hierarchy('url', 'apple-touch-icon.png', $vthemedirs['img']);
	if (!$vappletouchicon == '') {$vappletouchicon = get_site_icon_url(57, $vthemesettings['appletouchicon']);}
	if ( ($vappletouchicon) && ($vappletouchicon != '') ) {
		$vtags[] = '<link rel="apple-touch-icon-precomposed" href="'.esc_url($vappletouchicon).'">';
		$vtags[] = '<link rel="apple-touch-icon" href="'.esc_url($vappletouchicon).'">';
	}

	// <!-- For anything accepting PNG icons, 96x96 default -->
	$vfaviconpng = bioship_file_hierarchy('url', 'favicon.png', $vthemedirs['img']);
	if (!$vfaviconpng) {$vfaviconpng = get_site_icon_url(96, $vthemesettings['faviconpng']);}
	if ( ($vfaviconpng) && ($vfaviconpng != '') ) {
		$vtags[] = '<link rel="icon" href="'.esc_url($vfaviconpng).'">';
	}

	// <!-- Just for IE, the default 32x32 (or 16x16) size -->
	$vfaviconico = bioship_file_hierarchy('url', 'favicon.ico', $vthemedirs['img']);
	if ( (!$vfaviconico) && (file_exists(ABSPATH.DIRSEP.'favicon.ico')) ) {
		$vfaviconico = trailingslashit(site_url()).'favicon.ico';
	}
	if (!$vfaviconico) {$vfaviconico = $vthemesettings['faviconico'];}
	if ($vfaviconico == '') {$vfaviconico = bioship_generate_favicon();}
	if ( ($vfaviconico) && ($vfaviconico != '') ) {
		$vtags[] = '<!--[if IE]><link rel="shortcut icon" href="'.esc_url($vfaviconico).'"><![endif]-->';
	}

	// <!-- For Windows 8, the tile and background -->
	$vwineighttile = bioship_file_hierarchy('url', 'win8tile.png', $vthemedirs['img']);
	if (!$vwineighttile) {get_site_icon_url(270, $vthemesettings['wineighttile']);}
	if ( ($vwineighttile) && ($vwineighttile != '') ) {
		if ($vthemesettings['wineightbg'] != '') {$vwineightbg = $vthemesettings['wineightbg'];} else {$vwineightbg = '#FFFFFF';}
		$vtags[] = '<meta name="msapplication-TileColor" content="'.esc_url($vwineightbg).'">';
		$vtags[] = '<meta name="msapplication-TileImage" content="'.esc_url($vwineighttile).'">';
	}

	// 2.0.8: to maintain existing theme startup images filter
	$vstartupimages = bioship_apply_filters('skeleton_startup_images', '');
	if ($vstartupimages != '') {
		if (strstr($vstartupimages, PHP_EOL)) {
			$vstartuparray = explode(PHP_EOL, $vstartupimages);
			foreach ($vstartuparray as $vstartupimage) {
				if (trim($vstartupimage) != '') {$vtags[] = $vstartupimage;}
			}
		} else {$vtags[] = $vstartupimages;}
	}

	// 2.0.8: to maintain existing theme icon filter behaviour
	$vtags = bioship_apply_filters('skeleton_icons_override', $vtags);

	return $vtags;
 }
}

// Site Icon Generator
// -------------------
// 2.0.8: auto-generate site icon sizes if size does not exist
if (!function_exists('bioship_site_icon_generator')) {

 add_filter('get_site_icon_url', 'bioship_site_icon_generator', 1, 3);

 function bioship_site_icon_generator($vurl, $vsize, $vblogid) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	if ($vsize >= 512) {return $vurl;}
	$viconid = get_option('site_icon');

	if ($viconid) {
		$viconmeta = wp_get_attachment_metadata($viconid);
		$vsizes = array_keys($viconmeta['sizes']);
		if (THEMEDEBUG) {echo "<!-- Requested Icon Size: ".$vsize." -->";}

		// TODO: debug size values and test before implementing
		// if ( (!$vsize) || (!in_array($vsize, $vsizes)) ) {
		//	include_once(ABSPATH.'/wp-admin/includes/image.php');
		//	$vattachedfile = get_attached_file($viconid);
		// note: this is for thumbnails, need to retest for site icons
		//	$vgenerated = wp_generate_attachment_metadata($viconid, $vattachedfile);
		//	$vupdated = wp_update_attachment_metadata($viconid, $vgenerated);
		// }
		// $vurl = wp_get_attachment_image_url($viconid, array($vsize, $vsize));
	}

 	return $vurl;
 }
}

// Favicon Generator
// -----------------
if (!function_exists('bioship_generate_favicon')) {
 function bioship_generate_favicon() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$vphpico = bioship_file_hierarchy('file', 'class-php-ico.php', array('includes'));
	if ($vphpico) {require($vphpico);} else {return '';}

	return ''; // TEMP: bug out here until below is tested

	// TODO: maybe auto-generate a favicon.ico from site icon?
	// ref: PHP ICO: https://github.com/chrisbliss18/php-ico
	$viconid = get_option('site_icon');
	$vsource = wp_get_attachment_image_url($viconid, 'full');
	$vdestination = ABSPATH.'/favicon.ico'; // ???
	$vsizes = array( array(16,16), array(24,24), array(32, 32), array(48,48) );
	$vicolib = new PHP_ICO($vsource, $vsizes);
	// note: get data instead of using save_ico method to write with WP Filesystem
	// also note: this pseudo-'private' method is actually public and callable
	$vicodata = $vicolib->_get_ico_data();
	if (!$vicodata) {return false;}
	bioship_write_to_file($vdestination, $vicodata);
	return trailingslashit(site_url()).'favicon.ico';
 }
}

// Apple Touch Icon Sizes
// ----------------------
// ref: https://mathiasbynens.be/notes/touch-icons
// 2.0.5: check setting in function to allow filtering
if (function_exists('bioship_apple_icon_sizes')) {

 // 2.0.8: added missing filter prefix
 add_filter('bioship_skeleton_apple_icons', 'bioship_apple_icon_sizes', 9);

 // 1.8.5: fix to missing argument
 function bioship_apple_icon_sizes($vappleicons) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemedirs, $vthemesettings;

	// 2.0.5: check setting internally to allow filtering
	$vcheckforicons = false;
	if ( (isset($vthemesettings['appleiconsizes'])) && ($vthemesettings['appleiconsizes'] == '1') ) {
		$vcheckforicons = true;
	}
	$vcheckforicons = bioship_apply_filters('skeleton_apple_icon_sizes', $vcheckforicons);
	if (!$vcheckforicons) {return $vappleicons;}

	// 1.8.0: bugfix typo in hierarchy and url
	// <!-- For Chrome for Android -->
	$vimageurl = bioship_file_hierarchy('url', 'touch-icon-192x192.png', $vthemedirs['img']);
	if (!$vimageurl) {$vimageurl = bioship_file_hierarchy('url', 'touch-icon-192x192-precomposed.png', $vthemedirs['img']);}
	if ($vimageurl) {$vappleicons .= '<link rel="icon" sizes="192x192" href="'.$vimageurl.'">';}

	// 2.0.8: use string and numeric array
	$vsizes = array();
	// <!-- For iPhone 6 Plus with @3× display: -->
	$vsizes['180x180'] = 180;
	// <!-- For iPad with @2× display running iOS = 7: -->
	$vsizes['152x152'] = 152;
	// <!-- For iPad with @2× display running iOS = 6: -->
	$vsizes['144x144'] = 144;
	// <!-- For iPhone with @2× display running iOS = 7: -->
	$vsizes['120x120'] = 120;
	// <!-- For iPhone with @2× display running iOS = 6: -->
	$vsizes['114x114'] = 114;
	// <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS = 7: -->
	$vsizes['76x76'] = 76;
	// <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS = 6: -->
	$vsizes['72x72'] = 72;


	foreach ($vsizes as $vsize => $viconsize) {

		$vfallback = bioship_file_hierarchy('url', 'touch-icon-'.$vsize.'.png', $vthemedirs['img']);
		if (!$vfallback) {
			// 1.8.5: allow for fallback for maybe using -precomposed suffix
			$vfallback = bioship_file_hierarchy('url', 'touch-icon-'.$vsize.'-precomposed.png', $vthemedirs['img']);
		}

		// 2.0.8: maybe use global site icon
		if (THEMESITEICON) {$viconurl = get_site_icon_url($viconsize, $vfallback);}
		else {$viconurl = $vfallback;}

		if ($viconurl) {
			// 2.0.8: use esc_url on icon hrefs
			$vappleicons .= '<link rel="apple-touch-icon-precomposed" sizes="'.$vsize.'" href="'.esc_url($vurl).'">'.PHP_EOL;
		}
	}

	return $vappleicons;
 }
}

// Apple Startup Images
// --------------------
// 2.0.5: check setting internally to allow filtering
if (!function_exists('bioship_apple_startup_images')) {
 add_filter('skeleton_startup_images', 'bioship_apple_startup_images');
 function bioship_apple_startup_images($vimages) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemedirs, $vthemesettings;

	// 2.0.5: check setting internally to allow filtering
	$vstartupimages = false;
	if ( (isset($vthemesettings['startupimages'])) && ($vthemesettings['startupimages'] == '1') ) {
		$vstartupimages = true;
	}
	$vstartupimages = bioship_apply_filters('skeleton_startup_images_sizes', $vstartupimages);
	if (!$vstartupimages) {return $vimages;}

	// <!-- Enable Startup Image for iOS Home Screen Web App -->
	$vimages = '<meta name="apple-mobile-web-app-capable" content="yes" />'.PHP_EOL;
	// $vimages .= '<link rel="apple-touch-startup-image" href="" />'.PHP_EOL;

	// <!-- iPhone 3GS, 2011 iPod Touch (320x460) -->
	$vstartup[]['size'] = '320x460'; $vstartup[]['media'] = 'screen and (max-device-width : 320px)';
	// <!-- iPhone 4, 4S and 2011 iPod Touch (640x920) -->
	$vstartup[]['size'] = '640x920'; $vstartup[]['media'] = '(max-device-width : 480px) and (-webkit-min-device-pixel-ratio : 2)';
	// <!-- iPhone 5 and 2012 iPod Touch (640x1096) -->
	$vstartup[]['size'] = '640x1096'; $vstartup[]['media'] = '(max-device-width : 548px) and (-webkit-min-device-pixel-ratio : 2)';
	// <!-- iPad (non-retina) Landscape (1024x768) -->
	$vstartup[]['size'] = '1024x748'; $vstartup[]['media'] = 'screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : landscape)';
	// <!-- iPad (non-retina) Portrait (768x1004) -->
	$vstartup[]['size'] = '768x1004'; $vstartup[]['media'] = 'screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : portrait)';
	// <!-- iPad (Retina) (Portrait) (1536x2008) -->
	$vstartup[]['size'] = '1536x2008'; $vstartup[]['media'] = 'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait) and (-webkit-min-device-pixel-ratio: 2)';
	// <!-- iPad (Retina) (Landscape) (2048x1496) -->
	$vstartup[]['size'] = '2048x1496'; $vstartup[]['media'] = 'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) and (-webkit-min-device-pixel-ratio: 2)';

	foreach ($vstartups as $vstartup) {
		$vurl = bioship_file_hierarchy('url', 'startup-'.$vstartup['size'].'.png', $vthemedirs['img']);
		// 2.0.8: use esc_url on image hrefs
		if ($vurl) {$vimages .= '<link rel="apple-touch-startup-image" sizes="'.$vstartup['size'].'" href="'.$vurl.'startup-'.$vsize.'.png" media="'.esc_url($vstartup['media']).'">'.PHP_EOL;}
	}

	return $vimages;
 }
}

// Adjust CSS Hero Declarations Path
// ---------------------------------
// 1.8.5: allow moving of csshero.js from theme root to javascript dirs
// also allows for the file to be in the parent theme directory
// (this is a bit hacky, hopefully a real filter is available for this in future!)
if ( (isset($_GET['csshero_action'])) && ($_GET['csshero_action'] == 'edit_page') ) {
	// 1.9.5: added filter to optionally disable this path adjustment
	$vcsshero = bioship_apply_filters('skeleton_adjust_css_hero_script_dir', true);
	if ($vcsshero) {
		add_action('wp_loaded', 'bioship_csshero_script_dir', 0);
		if (!function_exists('bioship_csshero_script_dir')) {
		 function bioship_csshero_script_dir() {
			add_filter('stylesheet_directory_uri', 'bioship_csshero_script_url', 10, 3);
			function bioship_csshero_script_url($stylesheet_dir_uri, $stylesheet, $theme_root_uri) {
				if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
				global $vthemedirs;
				$vcsshero = bioship_file_hierarchy('url', 'csshero.js', $vthemedirs['js']);
				if ($vcsshero) {$stylesheet_dir_uri = dirname($vcsshero);}
				remove_filter('stylesheet_directory_uri', 'skeleton_css_hero_script_url', 10, 3);
				return $stylesheet_dir_uri;
			}
		 }
		}
	}
}

// -------------------------
// Included Templates Tracer
// -------------------------

// load for debug mode or site admin
// ---------------------------------
if ( (THEMEDEBUG) || (current_user_can('manage_options')) ) {
	add_action('wp_loaded','bioship_check_theme_includes');
	add_action('wp_footer','bioship_check_theme_templates');
}

// Get All Included Theme Files
// ----------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_get_theme_includes')) {
 function bioship_get_theme_includes() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	$vincludedfiles = get_included_files();
	// normalize theme paths for matching
	$vstyledirectory = str_replace("\\", "/", get_stylesheet_directory());
	$vtemplatedirectory = str_replace("\\", "/", get_template_directory());

	// loop included files
	foreach ($vincludedfiles as $vi => $vincludedfile) {
		// normalize include path for match
		$vincludedfile = str_replace("\\","/",$vincludedfile);
		// check if included file is in stylesheet directory
		if (substr($vincludedfile, 0, strlen($vstyledirectory)) != $vstyledirectory) {
			// if stylesheet is same as template, not a child theme
			if ($vstyledirectory == $vtemplatedirectory) {unset($vincludedfiles[$vi]);}
			else {
				// check if included file is in template directory
				// 2.0.7: fix to variable typo (templatedir)
				if (substr($vincludedfile, 0, strlen($vtemplatedirectory)) != $vtemplatedirectory) {
					unset($vincludedfiles[$vi]);
				} else {
					// strip template directory from include path
					$vpathinfo = pathinfo(str_replace(dirname($vtemplatedirectory), '', $vincludedfile));
					// 2.0.1: re-add full filepath to pathinfo array
					$vpathinfo['fullpath'] = $vincludedfile;
					// add filename.php => pathinfo array to the template array
					// 2.0.7: fix to variable name (pathinfo)
					$vthemeincludes[$vpathinfo['basename']] = $vpathinfo;
				}
			}
		} else {
			// strip stylesheet dir from include path
			$vpathinfo = pathinfo(str_replace(dirname($vstyledirectory), '', $vincludedfile));
			// 2.0.1: re-add full filepath to pathinfo array
			$vpathinfo['fullpath'] = $vincludedfile;
			// add filename.php => pathinfo array to the template array
			$vthemeincludes[$vpathinfo['basename']] = $vpathinfo;
		}
	}
	return $vthemeincludes;
 }
}

// check Theme Included Files
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_check_theme_includes')) {
	function bioship_check_theme_includes() {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemeincludes; $vthemeincludes = bioship_get_theme_includes();
	}
}

// get Included Template List
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_check_theme_templates')) {
 function bioship_check_theme_templates() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	global $vthemeincludes, $vtemplateincludes;
	$vtemplateincludes = bioship_get_theme_includes();

	// strip out included theme files from template list
	foreach ($vtemplateincludes as $vtemplate => $vpathinfo) {
		if (array_key_exists($vtemplate, $vthemeincludes)) {
			if ($vpathinfo['dirname'] == $vthemeincludes[$vtemplate]['dirname']) {
				unset($vtemplateincludes[$vtemplate]);
			}
		}
	}
	if (THEMEDEBUG) {echo "<!-- Included Template Files: "; print_r($vtemplateincludes); echo "-->";}

	// TODO: idea? make this an option or filter option?
	// could output a template array for use by jQuery/AJAX loading
	// echo "<script>var templatenames = new Array(); var templatepaths = new Array(); ";
	// $i = 0;
	// foreach ($vtemplateincludes as $vtemplate => $vpathinfo) {
	//  // optionally strip the .php extension
	//  $vtemplate = str_replace('.php', '', $vtemplate);
	//  // output the template array key/value
	//  echo "templatenames[".$i."] = '".$vpathinfo['filename']."'; ";
	//  echo "templatepaths[".$i."] = '".$vpathinfo['dirname']."'; ";
	//  $i++;
	// }
	// echo "</script>";

	// 2.0.1: maybe add list of included templates as dropdown menu in admin bar
	if ( (is_user_logged_in()) && (current_user_can('manage_options')) ) {
		$vaddmenu = false;
		if (isset($vthemesettings['templatesdropdown'])) {$vaddmenu = $vthemesettings['templatesdropdown'];}
		$vaddmenu = apply_filters('admin_template_list_dropdown', $vaddmenu);
		if ($vaddmenu != '1') {return;}
		add_action('wp_before_admin_bar_render', 'bioship_admin_template_dropdown');
	}

 }
}

// Admin Bar Templates Dropdown
// ----------------------------
// 2.0.1: added dropdown template list to admin bar
if (!function_exists('bioship_admin_template_dropdown')) {
 function bioship_admin_template_dropdown() {

	global $wp_admin_bar, $vtemplateincludes, $vthemename;

	$vmenu = array(
		'id' => 'page-templates',
		'title' => '<span class="ab-icon"></span>'.__('Templates','bioship'),
		'href' => 'javascript:void(0);',
		'meta' => array(
			'title' => __('Ordered list of included templates for this page.','bioship')
		)
	);
	$wp_admin_bar->add_menu($vmenu);

	$vi = 0;
	foreach ($vtemplateincludes as $vfilename => $vpathinfo) {
		$vrelfilepath = str_replace($vthemename,'',$vpathinfo['dirname']);
		while (substr($vrelfilepath, 0, 1) == '/') {
			$vrelfilepath = substr($vrelfilepath, 1, strlen($vrelfilepath));
		}
		$vrelfilepath = urlencode($vrelfilepath.'/'.$vfilename);
		// 2.0.8: fix to duplicate theme parameter
		$veditlink = admin_url('theme-editor.php');
		$veditlink = add_query_arg('theme', $vthemename, $veditlink);
		$veditlink = add_query_arg('file', $vrelfilepath, $veditlink);

		$vargs = array(
			'id' => 'template-'.$vi,
			'title' => $vfilename,
			'parent' => 'page-templates',
			'href' => $veditlink,
			'meta' => array(
				'title' => $vpathinfo['fullpath'],
				'class' => 'page-template'
			)
		);
		$wp_admin_bar->add_node($vargs);
		$vi++;
	}

	// add page menu template dashicon
	echo '<style>#wp-admin-bar-page-templates .ab-icon:before {content: "\\f232"; top: 3px;}</style>';

	if (THEMEDEBUG) {echo "<!-- Admin Bar: "; print_r($wp_admin_bar); echo " -->";}
 }
}

?>