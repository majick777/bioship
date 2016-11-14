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

// cannot be called directly
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

// TODO: skeleton_ function prefix could be changed to skull_ with backcompat?
// ...this makes it less easy to move code between here and functions.php however

// --------------------------
// === Register Nav Menus ===
// --------------------------

if (!function_exists('skeleton_register_nav_menus')) {
 add_action('init','skeleton_register_nav_menus');
 function skeleton_register_nav_menus() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_register_nav_menus',__FILE__);}

	global $vthemesettings;

	// Primary Menu
	// ------------
	// 1.5.0: moved template function call to skeleton.php
	if ($vthemesettings['primarymenu'] == '1') {register_nav_menus(array('primary' => __('Primary Navigation','bioship')));}

	// Secondary Menu
	// --------------
	// note: though created, is not hooked anywhere
	// 1.5.0: moved template function call to skeleton.php
	if ($vthemesettings['secondarymenu'] == '1') {register_nav_menus(array('secondary' => __('Secondary Navigation','bioship')));}

	// Header Menu
	// -----------
	if ($vthemesettings['headermenu'] == '1') {register_nav_menus(array('header' => __('Header Navigation','bioship')));}

	// Footer Menu
	// -----------
	if ($vthemesettings['footermenu'] == '1') {register_nav_menus(array('footer' => __('Footer Navigation','bioship')));}

 }
}

// -------------------------
// === Register Sidebars ===
// -------------------------

// Register Sidebar Helper
// -----------------------
// 1.8.5: added this helper
if (!function_exists('skeleton_register_sidebar')) {
 function skeleton_register_sidebar($vid,$vsettings,$vclass='') {
	if (THEMETRACE) {skeleton_trace('F','skeleton_register_sidebar',__FILE__,func_get_args());}

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
// 1.8.5: use skeleton_register_sidebar to reduce code bloat
if (!function_exists('skeleton_widgets_init')) {
 add_action('widgets_init','skeleton_widgets_init');
 function skeleton_widgets_init() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_widgets_init',__FILE__);}

	global $vthemesettings; $vts = $vthemesettings;

	// Set Sidebar Labels
	// ------------------
	// 1.8.5: set sidebar labels separately here
	// TODO: these labels could be set in hooks.php instead?

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
	$vsidebarwrappers = apply_filters('skeleton_sidebar_widget_wrappers',$vsidebarwrappers);

	// loop labels and add sidebar wrappers
	foreach ($vlabels as $vid => $vsettings) {
		$vlabels[$vid]['beforewidget'] = $vsidebarwrappers['beforewidget'];
		$vlabels[$vid]['afterwidget'] = $vsidebarwrappers['afterwidget'];
		$vlabels[$vid]['beforetitle'] = $vsidebarwrappers['beforetitle'];
		$vlabels[$vid]['aftertitle'] = $vsidebarwrappers['aftertitle'];
	}
	// 1.8.5: allow for sidebar label/setting filtering
	$vlabels = apply_filters('skeleton_sidebar_settings',$vlabels);


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
	if (count($vsidebarson) > 0) {
		foreach ($vsidebarson as $vsidebarid) {
			if ( (is_admin()) && (is_active_sidebar($vsidebarid)) ) {
				// add widget count to sidebar label
				$vsidebarwidgets = count($vallwidgets[$vsidebarid]);
				$vlabels[$vsidebarid]['name'] .= ' ('.$vsidebarwidgets.')';
			}
			skeleton_register_sidebar($vsidebarid,$vlabels[$vsidebarid],'on');
		}
	}
	if (count($vsidebarsoff) > 0) {
		foreach ($vsidebarsoff as $vsidebarid) {
			if ( (is_admin()) && (is_active_sidebar($vsidebarid)) ) {
				// add widget count to sidebar label
				$vsidebarwidgets = count($vallwidgets[$vsidebarid]);
				$vlabels[$vsidebarid]['name'] .= ' ('.$vsidebarwidgets.')';
			}
			skeleton_register_sidebar($vsidebarid,$vlabels[$vsidebarid],'off');
		}
	}

	// 1.9.6: add widget page message regarding lowercase titles meaning inactive
	add_action('widgets_admin_page','skeleton_widget_page_message');
	function skeleton_widget_page_message() {
		$vmessage = __('Note: Inactive Sidebars are listed with lowercase titles. Activate them via Theme Options -&gt; Skeleton -&gt; Sidebars ', 'bioship');
		echo "<div class='message'>".$vmessage."</div>";
	}

 }
}

// --------------
// Config Helpers
// --------------

// Widget Shortcodes
// -----------------
// override to maybe remove shortcode filter from Widget Text
$vwidgettextshortcodes = apply_filters('muscle_widget_text_shortcodes',true);
if (!$vwidgettextshortcodes) {remove_filter('widget_text','do_shortcode');}

// add shortcode filter to Widget Titles (and maybe override)
$vwidgettextshortcodes = apply_filters('muscle_widget_title_shortcodes',true);
if ($vwidgettextshortcodes) {add_filter('widget_title','do_shortcode');}

// LAYOUT LOADER
// -------------
// 1.8.5: calls all layout global setup functions
// 1. so layout can is filtered and passed to grid.php
// 2. sidebars are precalculated for body tag classes
add_action('wp','skeleton_load_layout');
if (!function_exists('skeleton_load_layout')) {
 function skeleton_load_layout() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_load_layout',__FILE__);}
 	global $vthemelayout, $vthemesidebars, $vthemedisplay, $vthemeoverride;

	// 1.9.5: initialize theme display and templating overrides
	if (is_singular()) {global $post; $vpostid = $post->ID;} else {$vpostid = '';}
	if (!function_exists('muscle_get_display_overrides')) {$vthemedisplay = array();}
	else {$vthemedisplay = muscle_get_display_overrides($vpostid);}
	if (!function_exists('muscle_get_templating_overrides')) {$vthemeoverride = array();}
	else {$vthemeoverride = muscle_get_templating_overrides($vpostid);}

	// setup all layout globals
 	skeleton_set_page_context();
 	skeleton_set_max_width();
 	skeleton_set_grid_columns();
	skeleton_set_sidebar_layout();
	skeleton_set_sidebar_columns();
	skeleton_set_subsidebar_columns();
	skeleton_set_content_width();

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
// TODO: match up more options like /wp-includes/template-loader.php
if (!function_exists('skeleton_set_page_context')) {
 function skeleton_set_page_context() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_set_page_context',__FILE__);}
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
if (!function_exists('skeleton_set_max_width')) {
 function skeleton_set_max_width() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_set_max_width',__FILE__);}
 	global $vthemesettings, $vthemelayout;
 	$vthemelayout['maxwidth'] = $vthemesettings['layout'];
 	if ($vthemelayout['maxwidth'] == '') {$vthemelayout['maxwidth'] = '960';}

 	$vmaxwidth = apply_filters('skeleton_layout_width',$vthemelayout['maxwidth']);
 	if ( ($vmaxwidth) && (is_numeric($vmaxwidth)) ) {
 		// TESTME: maybe set a minimum max-width?
 		// if ($vmaxwidth > 320) {
 			$vthemelayout['maxwidth'] = $vmaxwidth;
 		// }
 	}
 	return $vthemelayout['maxwidth'];
 }
}

// set Grid Columns
// ----------------
function skeleton_set_grid_columns() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_set_grid_columns',__FILE__);}
	global $vthemesettings, $vthemelayout;

	$vgridcolumns = $vthemesettings['gridcolumns'];
	$vcolumns = apply_filters('skeleton_grid_columns',$vgridcolumns);
	if ($vcolumns != $vgridcolumns) {
		$vgridvalues = array('twelve','sixteen','twenty','twentyfour'); // valid values
		if (is_numeric($vcolumns)) {$vcolumns = skeleton_number_to_word($vcolumns);}
		elseif (is_string($vcolumns)) {$vcolumns = skeleton_number_to_word(skeleton_word_to_number($vcolumns));}
		if ( ($vcolumns) && (in_array($vcolumns,$vgridvalues)) ) {$vgridcolumns = $vcolumns;}
	}
	if ($vgridcolumns == '') {$vgridcolumns = 'sixteen';} // fallback default
	$vthemelayout['gridcolumns'] = $vgridcolumns;

	$vthemelayout['numgridcolumns'] = skeleton_word_to_number($vgridcolumns);
	// return $vthemelayout['gridcolumns'];

	// 1.9.5: set content grid columns separately
	if (isset($vthemesettings['contentgridcolumns'])) {$vcontentgridcolumns = $vthemesettings['contentgridcolumns'];}
	else {$vcontentgridcolumns = $vgridcolumns;}
	$vcolumns = apply_filters('skeleton_content_grid_columns',$vcontentgridcolumns);
	if ($vcolumns != $vcontentgridcolumns) {
		$vgridvalues = array('twelve','sixteen','twenty','twentyfour'); // valid values
		if (is_numeric($vcolumns)) {$vcolumns = skeleton_number_to_word($vcolumns);}
		elseif (is_string($vcolumns)) {$vcolumns = skeleton_number_to_word(skeleton_word_to_number($vcolumns));}
		if ( ($vcolumns) && (in_array($vcolumns,$vgridvalues)) ) {$vcontentgridcolumns = $vcolumns;}
	}
	if ($vcontentgridcolumns == '') {$vcontentgridcolumns = 'twentyfour';} // fallback default
	$vthemelayout['contentgridcolumns'] = $vcontentgridcolumns;

	$vthemelayout['numcontentcolumns'] = skeleton_word_to_number($vcontentgridcolumns);

}


// set Sidebar Layout
// ------------------
// 1.8.0: added new sidebar templating setup
if (!function_exists('skeleton_set_sidebar_layout')) {
 function skeleton_set_sidebar_layout() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_set_sidebar_layout',__FILE__);}

	global $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;

	// 1.9.0: set short names from theme layout global
	$vsidebars = $vthemesidebars['sidebars'];
	$vsidebar = $vthemesidebars['sidebar'];
	$vsubsidebar = $vthemesidebars['subsidebar'];

	// sidebar positions: left, inner left, inner right, right
	$vleftsidebar = ''; $vsubleftsidebar = ''; $vsubrightsidebar = ''; $vrightsidebar = '';
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
	$vcheckposition = apply_filters('skeleton_sidebar_position',$vsidebarposition);
	if ( (is_string($vcheckposition)) && (in_array($vcheckposition,$vsidebarpositions)) ) {$vsidebarposition = $vcheckposition;}
	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['sidebarposition'])) && ($vthemeoverride['sidebarposition'] != '') ) {$vsidebarposition = $vthemeoverride['sidebarposition'];}
	$vthemesidebars['sidebarposition'] = $vsidebarposition;

	// get the subsidebar layout and filter
	// 1.8.5: added filter value validation check
	$vsubsidebarpositions = array('internal','external','opposite'); // valid values
	$vsubsidebarposition = $vthemesettings['subsidiaryposition']; // internal/external/opposite
	$vchecksubposition = apply_filters('skeleton_subsidebar_position',$vsubsidebarposition);
	if ( (is_string($vchecksubposition)) && (in_array($vchecksubposition,$vsidebarpositions)) ) {$vsubsidebarposition = $vchecksubposition;}
	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['subsidebarposition'])) && ($vthemeoverride['subsidebarposition'] != '') ) {$vsubsidebarposition = $vthemeoverride['subsidebarposition'];}
	$vthemesidebars['subsidebarposition'] = $vsubsidebarposition;

	// get the default sidebar modes and filter
	// 1.8.5: added filter value validation check
	$vsidebarmodes = array('off','postsonly','pagesonly','unified','dual'); // valid values
	$vsidebarmode = $vthemesettings['sidebarmode'];
	$vcheckmode = apply_filters('skeleton_sidebar_mode',$vsidebarmode);
	if ( (is_string($vcheckmode)) && (in_array($vcheckmode,$vsidebarmodes)) ) {$vsidebarmode = $vcheckmode;}
	$vthemesidebars['sidebarmode'] = $vsidebarmode;

	$vsubsidebarmode = $vthemesettings['subsidiarysidebar'];
	$vchecksubmode = apply_filters('skeleton_subsidebar_mode',$vsubsidebarmode);
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
	$vfullwidth = apply_filters('skeleton_fullwidth_filter',false);
	if ($vfullwidth) {$vsidebar = false; $vsubsidebar = false;}

	// apply individual sidebar output conditional filters
	$vsidebar = apply_filters('skeleton_sidebar_output',$vsidebar);
	$vsubsidebar = apply_filters('skeleton_subsidebar_output',$vsubsidebar);

	if (THEMEDEBUG) {
		echo "<!-- Sidebar States: ";
			if ($vsidebar) {echo "Main Sidebar - ";} else {echo "No Main Sidebar - ";}
			if ($vsubsidebar) {echo "Sub Sidebar";} else {echo "No Sub Sidebar";}
		echo " -->";
	}

	// for no default sidebars (full width) set empty sidebar array but continue to allow overrides
	if ( (!$vsidebar) && (!$vsubsidebar) ) {$vsidebars = array('','','','');}
	else {

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
						if ($vsubsidebarposition == 'internal') {$vsubleftsidebar = 'subsidebar';}
						if ($vsubsidebarposition == 'external') {$vsubleftsidebar = $vleftsidebar; $vleftsidebar = 'subsidiary';}
					}
					if ($vsidebarposition == 'right') {
						if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subsidiary';}
						if ($vsubsidebarposition == 'internal') {$vsubrightsidebar = 'subsidiary';}
						if ($vsubsidebarposition == 'external') {$vsubrightsidebar = $vrightsidebar; $vrightsidebar = 'subsidiary';}
					}
				}
				if ($vsubcontext == 'subpage') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'pagesonly') ) {
						if ($vsidebarposition == 'left') {
							if ($vsubsidebarposition == 'opposite') {$vrightsidebar = 'subpage';}
							if ($vsubsidebarposition == 'internal') {$vsubleftsidebar = 'subpage';}
							if ($vsubsidebarposition == 'external') {$vsubleftsidebar = $vleftsidebar; $vleftsidebar = 'subpage';}
						}
						if ($vsidebarposition == 'right') {
							if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subpage';}
							if ($vsubsidebarposition == 'internal') {$vsubrightsidebar = 'subpage';}
							if ($vsubsidebarposition == 'external') {$vsubrightsidebar = $vrightsidebar; $vrightsidebar = 'subpage';}
						}
					}
				}
				if ($vsubcontext == 'subpost') {
					if ( ($vsidebarmode == 'dual') || ($vsidebarmode == 'postsonly') ) {
						if ($vsidebarposition == 'left') {
							if ($vsubsidebarposition == 'opposite') {$vrightsidebar = 'subpost';}
							if ($vsubsidebarposition == 'internal') {$vsubleftsidebar = 'subpost';}
							if ($vsubsidebarposition == 'external') {$vsubleftsidebar = $vleftsidebar; $vleftsidebar = 'subpost';}
						}
						if ($vsidebarposition == 'right') {
							if ($vsubsidebarposition == 'opposite') {$vleftsidebar = 'subpost';}
							if ($vsubsidebarposition == 'internal') {$vsubrightsidebar = 'subpost';}
							if ($vsubsidebarposition == 'external') {$vsubrightsidebar = $vrightsidebar; $vrightsidebar = 'subpost';}
						}
					}
				}
			} else {
				if ($vsidebarposition == 'left') {
					if ($vsubsidebarposition == 'opposite') {$vrightsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'internal') {$vsubleftsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'external') {$vsubleftsidebar = $vleftsidebar; $vleftsidebar = $vsubcontext;}
				}
				if ($vsidebarposition == 'right') {
					if ($vsubsidebarposition == 'opposite') {$vleftsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'internal') {$vsubrightsidebar = $vsubcontext;}
					if ($vsubsidebarposition == 'external') {$vsubrightsidebar = $vrightsidebar; $vrightsidebar = $vsubcontext;}
				}
			}
		}

		// set full sidebar position array
		$vsidebars = array($vleftsidebar, $vsubleftsidebar, $vsubrightsidebar, $vrightsidebar);
	}

	if (THEMEDEBUG) {echo "<!-- Sidebar Positions (".$vcontext."/".$vsubcontext."): ".$vleftsidebar." - ".$vsubleftsidebar." - ".$vsubrightsidebar." - ".$vrightsidebar." -->";}


	// Check Template Position Override
	// --------------------------------
	$voverrides = apply_filters('skeleton_sidebar_layout_override',$vsidebars);

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
	$vsidebaroverride = false; $vsubsidebaroverride = false; $vnosidebar = false; $vnosubsidebar = false;
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
	skeleton_set_sidebar('left'); skeleton_set_sidebar('right');

	return $vsidebars;
 }
}

// set Sidebar Column Width
// ------------------------
if (!function_exists('skeleton_set_sidebar_columns')) {
 function skeleton_set_sidebar_columns() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_set_sidebar_columns',__FILE__);}

	global $vthemesettings, $vthemesidebars;

	// get filtered sidebar column width
	$vsidebarcolumns = $vthemesettings['sidebar_width'];
	if ($vsidebarcolumns == '') {$vsidebarcolumns = 'four';}
	$vcolumns = apply_filters('skeleton_sidebar_columns',$vsidebarcolumns);

	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['sidebarcolumns'])) && ($vthemeoverride['sidebarcolumns'] != '') ) {
		$vcolumns = $vthemeoverride['sidebarcolumns'];
	}

	// 1.8.5: added filter validation check
	if ($vcolumns != $vsidebarcolumns) {
		if (is_numeric($vcolumns)) {
			$vcolumns = skeleton_number_to_word($vcolumns);
			if ($vcolumns) {$vsidebarcolumns = $vcolumns;}
		} elseif (is_string($vcolumns)) {
			$vcolumns = skeleton_word_to_number($vcolumns);
			if ($vcolumns) {$vsidebarcolumns = skeleton_number_to_word($vcolumns);}
		}
	}
	$vthemesidebars['sidebarcolumns'] = $vsidebarcolumns;
	return $vsidebarcolumns;
 }
}

// set SubSidebar Column Width
// ---------------------------
if (!function_exists('skeleton_set_subsidebar_columns')) {
 function skeleton_set_subsidebar_columns() {
  	if (THEMETRACE) {skeleton_trace('F','skeleton_set_subsidebar_columns',__FILE__);}

 	global $vthemesettings, $vthemesidebars, $vthemeoverride;

	// get filtered subsidebar column width
	$vsubsidebarcolumns = $vthemesettings['subsidiarycolumns'];
	if ($vsubsidebarcolumns == '') {$vsubsidebarcolumns = 'zero';}
	$vcolumns = apply_filters('skeleton_subsidebar_columns',$vsubsidebarcolumns);

	// 1.9.5: allow for metabox override
	if ( (isset($vthemeoverride['subsidebarcolumns'])) && ($vthemeoverride['subsidebarcolumns'] != '') ) {
		$vcolumns = $vthemeoverride['subsidebarcolumns'];
	}

	// 1.8.5: added filter validation check
	if ($vcolumns != $vsubsidebarcolumns) {
		if (is_numeric($vcolumns)) {
			$vcolumns = skeleton_number_to_word($vcolumns);
			if ($vcolumns) {$vsubsidebarcolumns = $vcolumns;}
		} elseif (is_string($vcolumns)) {
			$vcolumns = skeleton_word_to_number($vcolumns);
			if ($vcolumns) {$vsubsidebarcolumns = skeleton_number_to_word($vcolumns);}
		}
	}
	$vthemesidebars['subsidebarcolumns'] = $vsubsidebarcolumns;
	return $vsubsidebarcolumns;
 }
}

// set Content Width
// -----------------
// 1.8.5: moved load action to skeleton_load_layout
if (!function_exists('skeleton_set_content_width')) {
 function skeleton_set_content_width() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_set_content_width',__FILE__);}
 	global $vthemelayout;
 	// 1.8.5: do main content setup here
	$vcontentwidth = skeleton_get_content_width();

	// 1.9.5: moved here from skeleton_get_content_width
	$vpaddingwidth = skeleton_get_content_padding_width($vcontentwidth);
	if ($vpaddingwidth > 0) {$vcontentwidth = $vcontentwidth - $vpaddingwidth;}
	$vcontentwidth = apply_filters('skeleton_content_width',$vcontentwidth);

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
if (!function_exists('skeleton_get_content_width')) {
	function skeleton_get_content_width() {
	  	if (THEMETRACE) {skeleton_trace('F','skeleton_get_content_width',__FILE__);}

		global $vthemesettings, $vthemelayout;

		$vcontentcols = skeleton_content_width();
		$vcolumns = skeleton_word_to_number($vcontentcols);

		// 1.8.5: use new theme layout global
		$vnumgridcolumns = skeleton_word_to_number($vthemelayout['gridcolumns']);

		// 1.8.0: bugfix for layoutwidth, not 960 default anymore
		// $vlayoutwidth = $vthemesettings['layout']; // maximum
		// $vlayoutwidth = apply_filters('skeleton_layout_width',$vlayoutwidth);
		// 1.8.5: use new layout global already set
		$vlayoutwidth = $vthemelayout['maxwidth'];

		// calculate actual content width
		$vcontentwidth = $vlayoutwidth / $vnumgridcolumns * $vcolumns;
		if (THEMEDEBUG) {echo "<!-- Layout Max Width: ".$vlayoutwidth." - Grid Columns: ".$vnumgridcolumns." - Content Columns: ".$vcolumns." -->";}
		// 1.9.5: set raw content width value for grid querystring
		$vcontentwidth = apply_filters('skeleton_raw_content_width',$vcontentwidth);
		$vthemelayout['rawcontentwidth'] = $vcontentwidth;

		// 1.9.5: moved padding calculation to skeleton_set_content_width

		return $vcontentwidth;
	}
}

// set Content Column Width
// ------------------------
// 1.8.5: moved here from skeleton.php
// 1.5.0: removed the filter here and moved to inside function and
// skeleton_content_width is called directly in skeleton_content_wrap

if (!function_exists('skeleton_content_width')) {
	function skeleton_content_width() {
	  	if (THEMETRACE) {skeleton_trace('F','skeleton_content_width',__FILE__);}

		// 1.9.8: added missing global theme override declaration
		global $post, $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;
		$vsidebar = $vthemesidebars['sidebar'];
		$vsubsidebar = $vthemesidebars['subsidebar'];

		// 1.8.5: use new theme layout global
		// note: onecolumn-page.php template has been removed
		// as the theme options edit screen metabox provides this
		// skeleton wide attachment pages have also been removed
		// if (is_attachment()) {return $vthemelayout['gridcolumns'];}

		// 1.8.0: full width if no sidebars (calculated in skeleton_set_sidebar_layout)
		if ( (!$vsidebar) && (!$vsubsidebar) ) {$vcolumns = $vthemelayout['gridcolumns']; return $vcolumns;}

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
		$vcolumns = apply_filters('skeleton_content_columns',$vcontentcolumns);

		// 1.9.5: allow for metabox override
		if ($vthemeoverride['contentcolumns'] != '') {$vcolumns = $vthemeoverride['contentcolumns'];}

		// 1.8.5: added filter validation check
		if ($vcolumns != $vcontentcolumns) {
			if (is_numeric($vcolumns)) {
				$vcolumns = skeleton_number_to_word($vcolumns);
				if ($vcolumns) {$vcontentcolumns = $vcolumns;}
			} elseif (is_string($vcolumns)) {
				$vcolumns = skeleton_word_to_number($vcolumns);
				if ($vcolumns) {$vcontentcolumns = skeleton_number_to_word($vcolumns);}
			}
		}

		// 1.8.5: use new themesidebars global
		$vsidebarcolumns = $vthemesidebars['sidebarcolumns'];
		$vsubsidebarcolumns = $vthemesidebars['subsidebarcolumns'];

		if (THEMEDEBUG) {echo "<!-- Columns: Content - ".$vcontentcolumns." - Sidebar - ".$vsidebarcolumns." - Subsidebar - ".$vsubsidebarcolumns." -->";}
		$vnumcontentcols = skeleton_word_to_number($vcontentcolumns);

		// get sidebar columns width as numeric, or 0 if none
		if ($vsidebar) {$vsidebarcols = skeleton_word_to_number($vsidebarcolumns);} else {$vsidebarcols = 0;}
		if ($vsubsidebar) {$vsubsidebarcols = skeleton_word_to_number($vsubsidebarcolumns);} else {$vsubsidebarcols = 0;}

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
		$vcontentcolumns = skeleton_number_to_word($vnumcontentcols);

		$vthemelayout['contentcolumns'] = $vcontentcolumns;
		$vthemelayout['numcontentcolumns'] = $vnumcontentcols;

		if (THEMEDEBUG) {echo "<!-- Content Columns: ".$vnumcontentcols." (".$vcontentcolumns.") -->";}

		// ...probably not a good filter to use in practice
		// 1.9.8: fix to changed variable name
		$vcontentcolumns = apply_filters('skeleton_content_columns_override',$vcontentcolumns);
		return $vcontentcolumns;
	}
}


// get Content Padding Width
// -------------------------
// 1.8.5: moved from skeleton.php
// gets the padding width (not height) - supports px or em or %
if (!function_exists('skeleton_get_content_padding_width')) {
	function skeleton_get_content_padding_width($vcontentwidth,$vcontentpadding=false) {
	  	if (THEMETRACE) {skeleton_trace('F','skeleton_get_content_padding_width',__FILE__,func_get_args());}

		global $vthemesettings, $vthemelayout;

		// 1.9.5: allow for explicit second argument for grid.php
		if ($vcontentpadding) {$vpaddingcss = $vcontentpadding;}
		else {
			$vpaddingcss = $vthemesettings['contentpadding'];
			$vpaddingcss = apply_filters('skeleton_raw_content_padding',$vpaddingcss);
		}

		if ( ($vpaddingcss == '') || ($vpaddingcss == '0') ) {$vpaddingwidth = 0;}
		else {
			if (strstr($vpaddingcss,' ')) {$vpaddingarray = explode(' ',$vpaddingcss);}
			else {$vpaddingarray[0] = $vpaddingcss;}

			$vi = 0; // convert padding values
			foreach ($vpaddingarray as $vpadding) {
				if (stristr($vpadding,'px')) {
					$vpaddingarray[$vi] = intval(trim(str_ireplace('px','',$vpadding))); $vi++;
				}
				elseif (stristr($vpadding,'em')) {
					// 1.5.0: added em support based on 1em ~= 16px
					// 1.9.5: added font percent to 100 for testing
					$vfontpercent = '100';
					$vpaddingvalue = trim(str_ireplace('em','',$vpadding));
					$vpaddingvalue = round(($vpaddingvalue * 16 * $vfontpercent / 100),2,PHP_ROUND_HALF_DOWN);
					$vpaddingarray[$vi] = $vpaddingvalue; $vi++;
				}
				elseif (strstr($vpadding,'%')) {
					$vpadding = intval(trim(str_ireplace('%','',$vpadding)));
					$vpaddingarray[$vi] = round(($vcontentwidth * $vpadding),2,PHP_ROUND_HALF_DOWN); $vi++;
				}
				else {$vpaddingarray[$vi] = 0; $vi++;}
			}

			if (count($vpaddingarray) == 4) {$vpaddingwidth = $vpaddingarray[1] + $vpaddingarray[3];}
			elseif ( (count($vpaddingarray) == 3) || (count($vpaddingarray) == 2) ) {$vpaddingwidth = $vpaddingarray[1];}
			elseif (count($vpaddingarray == 1)) {$vpaddingwidth = $vpaddingarray[0];}
		}

		// 1.5.0: added a padding width filter
		$vpaddingwidth = apply_filters('skeleton_content_padding_width',$vpaddingwidth);
		$vpaddingwidth = abs(intval($vpaddingwidth));

		$vthemelayout['contentpadding'] = $vpaddingwidth;
		return $vpaddingwidth;
	}
}


// get Post Type(s) Helper
// -----------------------
// 1.8.5: added this helper
if (!function_exists('skeleton_get_post_types')) {
 function skeleton_get_post_types($queryobject=null) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_get_post_types',__FILE__,func_get_args());}

	// if a numeric value passed, assume it is a post ID
	if ( ($queryobject) && (is_numeric($queryobject)) ) {$queryobject = get_post($queryobject);}
	// if an object is passed, assume a post object
	if ( ($queryobject) && (is_object($queryobject)) ) {
		if (THEMEDEBUG) {echo "<!-- Queried Object: "; print_r($queryobject); echo " -->";}
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
	if (property_exists($queryobject,'query_vars')) {
	    $queriedposttype = $queryobject->query_vars['post_type'];
		if ($queriedposttype) {return $queriedposttype;}
	}

    // handle all other cases by looping posts in query object
    $posttypes = array();
	if ($queryobject->found_posts > 0) {
		$queriedposts = $queryobject->posts;
		foreach ($queriedposts as $queriedpost) {
		    $posttype = $queriedpost->post_type;
		    if (!in_array($posttype,$posttypes)) {$posttypes[] = $posttype;}
		}
		if (count($posttypes == 1)) {return $posttypes[0];}
		else {return $posttypes;}
	}

    return '';

	// -----------
	// [unused] (working but incomplete towards the end as too many conditions)

	// get the queried object
	$vqueriedobject = get_queried_object();

	// get possible custom taxonomy queries
	$vterms = $vqueriedobject->tax_query->queried_terms;
	if (is_array($vterms)) {$vtaxterms = array_keys($vterms);}
	// echo "<!-- TAX TERMS: "; print_r($vtaxterms); echo " -->";

	if (count($vtaxterms) > 0)  {

		// modified from WordPress core query.php
		// Do a fully inclusive search for currently registered post types of queried taxonomies
		$vcpts = get_post_types(array('exclude_from_search' => false));
		foreach ($vcpts as $vcpt) {
			$vobjecttaxonomies = $vcpt;
			if ($vcpt === 'attachment') {$vobjecttaxonomies = get_taxonomies_for_attachments();}
			else {$vobjecttaxonomies = get_object_taxonomies($vcpt);}
			if (array_intersect($vtaxterms, $vobjecttaxonomies)) {$vposttypes[] = $vcpt;}
		}
		if (count($vposttypes) == 1) {return $vposttypes[0];}
		elseif (count($vposttypes) > 1) {return $vposttypes;}
	}

	if (is_archive()) {
		// note: is_archive is true for:
		// is_post_type_archive, is_date, is_author, is_category, is_tag, is_tax

		if (is_category()) {$vtax = 'category'; $vchecktax = true;}
		elseif (is_tag()) {$vtax = 'post_tag'; $vchecktax = true;}
		elseif (is_tax()) {$vtax = get_queried_object()->taxonomy; $vchecktax = true;}

		if ($vchecktax) {
			$vtaxonomy = get_taxonomy($vtax);
			$vobjecttypes = $vtaxonomy->object_type;
			if (THEMEDEBUG) {echo "<!-- OBJECT TYPES: "; print_r($vobjecttypes); echo "-->";}
			if (count($vobjecttypes) === 1) {return $vobjecttypes[0];}
			else {return $vobjecttypes;}
		}
		else {
			// TODO: handle date and author archives?
			// if (is_date()) {
			//	// $vposttypes = ???
			// }
			// elseif (is_author()) {
			//	// $vposttypes = ???
			// }
		}
	}
	else {
		// anything else, a sock perhaps?
		// is_search
	}

	return '';
 }
}


// -----------------
// === Title Tag ===
// -----------------

// Title Tag Support
// -----------------
// 1.8.5: use new title-tag support
// (off by default as it renders no home tag yet?)
$vtitletagsupport = apply_filters('skeleton_title_tag_support',0);
if ($vtitletagsupport) {
	add_theme_support('title-tag');
	// replace title tag render action to add filter
	remove_action('wp_head','_wp_render_title_tag',1);
	add_action('wp_head','skeleton_render_title_tag_filtered',1);
	add_filter('document_title_separator','skeleton_title_separator');
	if (!function_exists('skeleton_title_separator')) {
		function skeleton_title_separator($vseparator) {return '|';}
	}
	// TODO: check usage of document_title_parts filter
	// add_filter('document_title_parts','skeleton_document_title_parts');
	// function skeleton_document_title_parts($title) {return $title;}
} else {
	// fallback to wp_title usage
	add_filter('wp_title','skeleton_wp_title',10,2);
	add_action('wp_head','skeleton_wp_title_tag');
}

// Title Tag Filter
// ----------------
// 1.8.5: added title tag filter function
if (!function_exists('skeleton_render_title_tag_filtered')) {
 function skeleton_render_title_tag_filtered() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_render_title_tag_filtered',__FILE__);}
    ob_start(); _wp_render_title_tag(); $titletag = ob_get_contents(); ob_end_clean();
    return apply_filters('wp_render_title_tag_filter',$titletag);
 }
}

// Title Tag Theme Default
// -----------------------
add_filter('wp_render_title_tag_filter','skeleton_wp_render_title_tag');
if (!function_exists('skeleton_wp_render_title_tag')) {
 function skeleton_wp_render_title_tag($vtitletag) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_wp_render_title_tag',__FILE__,func_get_args());}
 	// note: rendered default is (WP 4.4)
 	// echo '<title itemprop="name">' . wp_get_document_title() . '</title>' . "\n";
 	$vtitletag = str_replace('<title>','<title itemprop="name">',$vtitletag);
	return $vtitletag;
 }
}

// wp_title Tag Output
// -------------------
// 1.8.5: moved here from header.php (wp_title only)
if (!function_exists('skeleton_wp_title_tag')) {
 function skeleton_wp_title_tag() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_wp_title_tag',__FILE__);}
  	echo '<title'.' '.'itemprop="name">'; wp_title('|',true,'right'); echo '</title>'.PHP_EOL;
 }
}

// wp_title Title Filter
// ---------------------
// 1.8.5: no longer default, and moved filter actions to title-tag support check
if (!function_exists('skeleton_wp_title'))  {
 function skeleton_wp_title($vtitle,$vsep) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_wp_title',__FILE__,func_get_args());}

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
		$vtitle .= " ".$vblogname;
	}

	// maybe add a page number
	if ( ($paged >= 2) || ($page >= 2) ) {
		$title .= " ".$vsep." ".sprintf( __('Page %s','bioship'), max($paged,$page) );
	}

	$vtitle = apply_filters('skeleton_page_title',$vtitle);
	return $vtitle;
 }
}



// ------------------------
// === Template Helpers ===
// ------------------------

// Get Header Template
// -------------------
// 1.8.5: custom header template hierarchy implementation
if (!function_exists('skeleton_get_header')) {
 function skeleton_get_header($vfilepath=false) {
  	if (THEMETRACE) {skeleton_trace('F','skeleton_get_header',__FILE__,func_get_args());}

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
	do_action('get_header');
	$vtemplates = array();

	// filter to allow for custom overrides
	$vheader = apply_filters('skeleton_header_template',$vpagecontext);
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
		$vheaderarchive = apply_filters('skeleton_header_archive_template',$vsubpagecontext);
		if ( ($vheaderarchive) && (is_string($vheaderarchive)) ) {
			// do_action('get_header', $vheaderarchive); // ???
			if ($vheaderdir) {$vtemplates[] = 'header/'.$vheaderarchive.'.php';}
			$vtemplates[] = 'header-'.$vheaderarchive.'.php';
		}
	}

	// default template hierarchy
	if ($vheaderdir) {$vtemplates[] = 'header/'.$vpagecontext.'.php';}
	$vtemplates[] = 'header-'.$vpagecontext.'.php';
	if ($vheaderdir) {$vtemplates[] = 'header/header.php';}
	$vtemplates[] = 'header.php';

	$vheadertemplates = apply_filters('skeleton_header_templates',$vtemplates);
	if (is_array($vheadertemplates)) {$vtemplates = $vheadertemplates;}
	skeleton_locate_template($vtemplates, true, false);
 }
}

// Get Footer Template
// -------------------
// 1.8.5: custom footer template hierarchy implementation
if (!function_exists('skeleton_get_footer')) {
 function skeleton_get_footer($vfilepath=false) {
  	if (THEMETRACE) {skeleton_trace('F','skeleton_get_footer',__FILE__,func_get_args());}

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
	do_action('get_footer');
	$vtemplates = array();

	// filter to allow for custom overrides
	$vfooter = apply_filters('skeleton_footer_template',$vpagecontext);
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
		$vfooterarchive = apply_filters('skeleton_footer_archive_template',$vsubpagecontext);
		if ( ($vfooterarchive) && (is_string($vfooterarchive)) ) {
			// do_action('get_footer', $vfooterarchive); // ???
			if ($vfooterdir) {$vtemplates[] = 'footer/'.$vfooterarchive.'.php';}
			$vtemplates[] = 'footer-'.$vfooterarchive.'.php';
		}
	}

	// default footer template hierarchy
	if ($vfooterdir) {$vtemplates[] = 'footer/'.$vpagecontext.'.php';}
	$vtemplates[] = 'footer-'.$vpagecontext.'.php';
	if ($vfooterdir) {$vtemplates[] = 'footer/footer.php';}
	$vtemplates[] = 'footer.php';

	$vfootertemplates = apply_filters('skeleton_footer_templates',$vtemplates);
	if (is_array($vfootertemplates)) {$vtemplates = $vfootertemplates;}
	skeleton_locate_template($vtemplates, true, false);
 }
}

// Get Loop Template
// -----------------
// (allows for matching templates and /loop/ subdirectory usage)
// 1.8.5: replaces get_template_part('loop','index') for loop

if (!function_exists('skeleton_get_loop')) {
 function skeleton_get_loop($vfilepath=false) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_get_loop',__FILE__,func_get_args());}

 	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	$vpagecontext = $vthemelayout['pagecontext'];
	$vsubpagecontext = $vthemelayout['subpagecontext'];

	// to check for loop directory just once
	$vloopdir = false;
	if (is_dir($vthemestyledir.'loop')) {$vloopdir = true;}
	elseif ( ($vthemestyledir != $vthemetemplatedir) && (is_dir($vthemetemplatedir.'loop')) ) {$vloopdir = true;}

 	$vtemplates = array(); $vname = '';

	// filter to allow for custom override
	$vtemplate = apply_filters('skeleton_loop_template',$vpagecontext);
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
 		// 1.9.5: fix to apply_filters typo!
 		$vlooparchive = apply_filters('skeleton_loop_archive_template',$vsubpagecontext);
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
 	do_action('get_template_part_loop', 'loop', $vname);
 	// and fire this one in any case for loop index
 	do_action('get_template_part_loop', 'loop', 'index');

	// filter to allow for complete override
	$vlooptemplates = apply_filters('skeleton_loop_templates',$vtemplates);
	if (is_array($vlooptemplates)) {$vtemplates = $vlooptemplates;}
	skeleton_locate_template($vtemplates, true, false);
 }
}

// Locate Template Wrapper
// -----------------------
// copy of WordPress 'locate_template' function
// for a possible future filter/feature implementation
if (!function_exists('skeleton_locate_template')) {
 function skeleton_locate_template($template_names, $load = false, $require_once = true) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_locate_template',__FILE__,func_get_args());}

	// 1.8.5: this makes it just a passthrough function for now
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
// ...this is a tricky one, Wordpress has not made this one easy..!
// (first, maybe remove the Hybrid comments template filter)
remove_filter('comments_template', 'hybrid_comments_template', 5);

// add our own filter, as moving comments.php to theme /content/ template path
add_filter('comments_template','skeleton_comments_template',5);

if (!function_exists('skeleton_comments_template')) {
 function skeleton_comments_template($vtemplatepath) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_comments_template',__FILE__,func_get_args());}

	// 1.5.0: Change the default Comments are Closed to invisible
	// if this is *not* done here, it somehow magically appears?! :-/
	if ( (!have_comments()) && (!comments_open()) ) {
		echo '<p class="nocomments" style="display:none;">'.__('Comments are Closed.','bioship').'</p>';
		return false; // so that the comments_template function is not called
	}

	// note: default comment template is: STYLEPATH.'/comments.php';
	$vpathinfo = pathinfo($vtemplatepath);
	$vtemplate = $vpathinfo['basename'];

	// if the default is sent, check for a post type comments template
	if ($vtemplate == 'comments.php') {
		$vposttypetemplate = 'comments-'.get_post_type(get_the_ID()).'.php';
		$vcommentstemplate = skeleton_file_hierarchy('file',$vposttypetemplate,array('content'));
		if ($vcommentstemplate) {
			if (THEMEDEBUG) {echo '<!-- Comments Template Path: '.$vcommentstemplate.' -->';}
			return $vcommentstemplate;
		}
	}

	// for other templates (or no post type template)
	// use the skeleton file hierarchy to locate instead
	$vcommentstemplate = skeleton_file_hierarchy('file',$vtemplate,array('content'));
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
add_filter('hybrid_content_template_hierarchy', 'skeleton_archive_template_hierarchy',11);
if (!function_exists('skeleton_archive_template_hierarchy')) {
 function skeleton_archive_template_hierarchy($vtemplates) {
	if (THEMETRACE) {skeleton_trace('F','skeleton_split_template_hierarchy',__FILE__,func_get_args());}
	// 1.9.5: only add archive template search if an archive subdirectory exists
	if ( (is_singular()) || (is_attachment()) ) {return $vtemplates;}
	global $vthemestyledir, $vthemetemplatedir;
	$varchivedir = apply_filters('skeleton_archive_template_directory','archive');
	$vcontentdir = apply_filters('skeleton_content_template_directory','content');
	if ( (!is_string($varchivedir)) || (!is_string($vcontentdir)) ) {return $vtemplates;}
	if ( (!is_dir($vthemestyledir.$varchivedir)) && (!is_dir($vthemetemplatedir.$varchivedir)) ) {return $vtemplates;}

	$varchivetemplates = array();
	foreach ($vtemplates as $vtemplate) {
		// 1.9.5: add archive subdirectory to a hierarchy instead of replacing content
		if (strstr($vtemplate,$vcontentdir.'/')) {$varchivetemplates[] = str_replace($vcontentdir.'/',$varchivedir.'/',$vtemplate);}
	}
	$vnewtemplates = array_merge($varchivetemplates,$vtemplates);
	if (THEMEDEBUG) {echo "<!-- Archive Template Hiearchy: "; print_r($vnewtemplates); echo " -->";}
	return $vnewtemplates;
 }
}

// Content Directory Template Filter
// ---------------------------------
// 1.9.5: similar to archive template filter, allows change of /content/ usage
add_filter('hybrid_content_template_hierarchy','skeleton_content_template_hierarchy',10,3);
if (!function_exists('skeleton_content_template_hierarchy')) {
 function skeleton_content_template_hierarchy($vtemplates) {
 	$vcontentdir = apply_filters('skeleton_content_template_directory','content');
 	if ( ($vcontentdir == 'content') || (!is_string($vcontentdir)) ) {return $vtemplates;}
	foreach ($vtemplates as $vkey => $vtemplate) {
		if (strstr($vtemplate,'content/')) {$vtemplates[$vkey] = str_replace('content/',$vcontentdir.'/',$vtemplate);}
	}
	if (THEMEDEBUG) {echo "<!-- Content Template Hiearchy: "; print_r($vtemplates); echo " -->";}
	return $vtemplates;
 }
}


// -------------------
// === Setup Theme ===
// -------------------

add_action('after_setup_theme','skeleton_setup');

if (!function_exists('skeleton_setup')) {
 function skeleton_setup() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_setup',__FILE__);}

	global $vthemesettings, $vthemedirs;

	// Language Translation
	// --------------------
	// TODO: create translations file in the /languages/ directory
	// (they have not been touched in a loooong time... ok ever.)
	// https://make.wordpress.org/meta/handbook/documentation/translations/
	load_theme_textdomain('bioship', get_template_directory().'/languages');

	// Editor Styles
	// -------------
	// 1.9.5: removed is_rtl check as handled automatically by add_editor_style
	// if (!is_rtl()) {$veditorstyle = 'editor-style.css';} else {$veditorstyle = 'editor-style-rtl.css';}
	$veditorstyleurl = skeleton_file_hierarchy('url','editor-style.css',$vthemedirs['css']);
	if ($veditorstyleurl) {add_editor_style($veditorstyleurl);}

	// Dynamic Editor Styles
	// ---------------------
	if ( (isset($vthemesettings['dynamiceditorstyles'])) && ($vthemesettings['dynamiceditorstyles'] == '1') ) {

		// TODO: better way to maybe enqueue matching Google font?
		// (currently done in functions.php)

		// 1.9.5: add dynamic editor styles to match skin theme settings styles
		// ref: https://www.mattcromwell.com/dynamic-tinymce-editor-styles-wordpress/
		if (is_admin()) {add_filter('tiny_mce_before_init','skeleton_add_dynamic_editor_styles');}
		if (!function_exists('skeleton_add_dynamic_editor_styles')) {
		 function skeleton_add_dynamic_editor_styles($mceInit) {
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

			// TODO: add any other relevant style rules? eg. buttons ?

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
	// check: are page thumbnails (featured images) on by adding default support?!
	$vthumbcpts = $vthemesettings['thumbnailcpts'];
	if ($vthumbcpts != '') {
		if ( (count($vthumbcpts) > 0) && (is_array($vthumbcpts)) ) {
			foreach ($vthumbcpts as $vcpt => $vvalue) {
				if ($vvalue == '1') {
					if (!post_type_supports($vcpt,'thumbnail')) {
						add_post_type_support($vcpt,'thumbnail');
					}
				}
			}
		}
	}

	// Set Default Post Thumbnail Size
	// -------------------------------
	// TODO: maybe add 'post-thumbnail' image size (for post screen) ?
	// (ref: _wp_post_thumbnail_html in /wp-admin/includes/post.php)

	// get_option('thumbnail_image_w'); get_option('thumbnail_image_h');
	// 1.8.0: changed default to 200x200 as square250 already exists
	// 1.5.0: changed default to 250x250 from 150x150
	// for better FB sharing support, as minimum required there is 200x200
	$vthumbnailwidth = apply_filters('skeleton_thumbnail_width',200);
	$vthumbnailheight = apply_filters('skeleton_thumbnail_height',200);
	$vcrop = get_option('thumbnail_crop');
	$vthumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ($vthumbnailcrop == 'nocrop') {$vcrop = false;}
	if ($vthumbnailcrop == 'auto') {$vcrop = true;}
	if (strstr($vthumbnailcrop,'-')) {$vcrop = explode('-',$vthumbnailcrop);}

	set_post_thumbnail_size($vthumbnailwidth, $vthumbnailheight, $vcrop);

	// Ref: Wordpress Thumbnail Size Defaults
	// - thumbnail : 150x150 max
	// - medium : 	 300x300 max
	// - large :	 640x640 max
	// - full : 	 original size

	// Add Image Sizes array (via Skeleton)
	// 150px square, 250px square, 4:3 Video, 16:9 Video
	$vimagesizes[0] = array('name' => 'squared150', 'width' => 150, 'height' => 150, 'crop' => $vcrop);
	$vimagesizes[1] = array('name' => 'squared250', 'width' => 250, 'height' => 250, 'crop' => $vcrop);
	$vimagesizes[2] = array('name' => 'video43', 'width' => 320, 'height' => 240, 'crop' => $vcrop);
	$vimagesizes[3] = array('name' => 'video169', 'width' => 320, 'height' => 180, 'crop' => $vcrop);
	// 1.5.0: added open graph size 560x292
	$vimagesizes[4] = array('name' => 'opengraph', 'width' => 560, 'height' => 292, 'crop' => $vcrop);
	$vimagesizes = apply_filters('skeleton_image_sizes',$vimagesizes);

	if (count($vimagesizes) > 0) {
		foreach ($vimagesizes as $vsize) {
			add_image_size($vsize['name'],$vsize['width'],$vsize['height'],$vsize['crop']);
		}
	}

 }
}

// Enqueue Skeleton Scripts
// ------------------------
// note: Styles moved to Skin Section
// 1.8.0: added filemtime cache busting option
if (!function_exists('skeleton_scripts')) {
 add_action('wp_enqueue_scripts', 'skeleton_scripts');
 function skeleton_scripts() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_scripts',__FILE__);}

	global $vthemesettings, $vthemename, $vjscachebust, $vthemedirs;

	// 1.9.5: check and set filemtime use just once
	$vfilemtime = false; if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {$vfilemtime = true;}

	// Theme Javascripts
	// -----------------
	// 1.9.5: only do this for frontend to prevent admin conflicts (with load-scripts.php)
	if ( (!is_admin()) && ($vthemesettings['jquerygooglecdn'] == '1') ) {

		// maybe load jQuery from Google CDN
		// ---------------------------------
		// 1.8.5: added jquery handle check
		// ref: http://stackoverflow.com/a/17431575/5240159
		// Get jquery handle - WP 3.6 or newer changed the jQuery handle
		global $wp_version;
        $vjqueryhandle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';
        if (THEMEDEBUG) {echo "<!-- jQuery Handle: ".$vjqueryhandle." -->";}
        // get the built-in jQuery version for current WordPress install
        // 1.9.5: fix to silly typo here to make it work again
        $vwpjqueryversion = $GLOBALS['wp_scripts']->registered[$vjqueryhandle]->ver;
        if (THEMEDEBUG) {echo "<!-- jQuery Version: ".$vwpjqueryversion." -->";}

		$vjqueryversion = apply_filters('skeleton_google_jquery_version',$vwpjqueryversion);
		$vjquery = 'https://ajax.googleapis.com/ajax/libs/jquery/'.$vjqueryversion.'/jquery.min.js';
		// note: test with wp_remote_fopen pointless here as comes from server not client

		// 1.8.0: fix to use new jquery-core handle
		// 1.8.5: use version matched jquery handle
		// note: new jquery handle is a dependency handle with children of jquery-core and jquery-migrate
		wp_deregister_script($vjqueryhandle);
		wp_register_script($vjqueryhandle, $vjquery, false, $vjqueryversion, true);
		wp_enqueue_script($vjqueryhandle);

		// 1.5.0: added a jQuery fallback for if Google CDN fails
		// Ref: http://stackoverflow.com/questions/1014203/best-way-to-use-googles-hosted-jquery-but-fall-back-to-my-hosted-library-on-go
		add_filter('script_loader_tag','skeleton_jquery_fallback', 10, 2);
		function skeleton_jquery_fallback($vscripttag, $vhandle) {
			// 1.8.0: fix to handle, should now be jquery-core
			// 1.9.5: match handle for WP version here too
			global $wp_version;
			$vjqueryhandle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';
			if ( ($vhandle == $vjqueryhandle) && (strstr($vscripttag,'jquery.min.js')) ) {
				global $vthemesettings, $vjscachebust;
				if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
					$vjscachebust = date('ymdHi',filemtime(ABSPATH.WPINC.'/js/jquery/jquery.js'));
				}
				$vjquery = urlencode(site_url().'/wp-includes/js/jquery/jquery.js?'.$vjscachebust);
				if (THEMEDEBUG) {$vconsoledebug = "console.log('Loading jQuery from Google CDN failed. Loading jQuery from site.'); ";}
				$vfallback = "</script><script>if (!window.jQuery) {".$vconsoledebug."document.write(unescape('%3Cscript src=\"".$vjquery."\"%3E%3C\/script%3E'));}</script>";
				$vscripttag = str_replace('</script>',$vfallback, $vscripttag);
			}
			return $vscripttag;
		}
	}

	// superfish.js
	// 1.8.5: conditionally load only if there is primary Nav Menu
	// 1.9.5: fix to dashes in theme name slug for theme mods
	$vthememods = get_option('theme_mods_'.str_replace('_','-',$vthemename));
	if ( (isset($vthememods['nav_menu_locations']['primary'])) && ($vthememods['nav_menu_locations']['primary'] != '') ) {
		$vsuperfish = skeleton_file_hierarchy('both','superfish.js',$vthemedirs['js']);
		if (is_array($vsuperfish)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vsuperfish['file']));}
			wp_enqueue_script('superfish',$vsuperfish['url'],array('jquery'),$vjscachebust,true);
		}

		// 1.8.5: count and set main menu (not submenu) items
		$vmenuid = $vthememods['nav_menu_locations']['primary'];
		// $vmainmenu = get_term($vmenuid,'nav_menu');
		if (THEMEDEBUG) {echo "<!-- Main Menu ID: ".$vmenuid." -->";}
		$vmenuitems = wp_get_nav_menu_items( $vmenuid, 'nav_menu' );
		// if (THEMEDEBUG) {echo "<!-- Main Menu Items: "; print_r($vmenuitems)." -->";}
		foreach ($vmenuitems as $vitem) {
			if ($vitem->menu_item_parent == 0) {$vmenumainitems++;}
		}
		if (THEMEDEBUG) {echo "<!-- Menu Main Items: ".$vmenumainitems." -->";}
		// note: this menu item count is used in skin.php
		if (get_option($vthemename.'_menumainitems') != $vmenumainitems) {
			if (!update_option($vthemename.'_menumainitems',$vmenumainitems)) {add_option($vthemename.'_menumainitems',$vmenumainitems);}
		}
	}

	// formalize.js
	if ($vthemesettings['loadformalize']) {
		$vformalize = skeleton_file_hierarchy('both','jquery.formalize.min.js',$vthemedirs['js']);
		if (is_array($vformalize)) {
			if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vformalize['file']));}
			wp_enqueue_script('formalize',$vformalize['url'],array('jquery'),$vjscachebust,true);
		}
	}

	// init.js
	$vinit = skeleton_file_hierarchy('both','init.js',$vthemedirs['js']);
	if (is_array($vinit)) {
		if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vinit['file']));}
		wp_enqueue_script('init',$vinit['url'],array('jquery'),$vjscachebust,true);
	}

	// custom.js
	$vcustom = skeleton_file_hierarchy('both','custom.js',$vthemedirs['js']);
	if (is_array($vcustom)) {
		if ($vfilemtime) {$vjscachebust = date('ymdHi',filemtime($vcustom['file']));}
		wp_enqueue_script('custom',$vcustom['url'],array('jquery'),$vjscachebust,true);
	}

	// maybe enqueue comment reply script
	if ( is_singular() && comments_open() && get_option('thread_comments') ) {wp_enqueue_script('comment-reply');}

	// note: for Foundation loading functions see muscle.php
 }
}

// Change/Remove the Meta Generator Tag
// ------------------------------------
// 1.8.5: moved to skull from muscle.php
// changed name from muscle_meta_generator to skeleton_meta_generator
if (!function_exists('skeleton_meta_generator')) {
 add_filter('the_generator','skeleton_meta_generator',999);
 // 1.8.5: add Hybrid filter to match
 add_filter('hybrid_meta_generator','skeleton_meta_generator');
 function skeleton_meta_generator() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_meta_generator',__FILE__);}
	return apply_filters('skeleton_generator_meta','');
 }
}

// Mobile Header Meta
// ------------------
// ref: http://www.quirksmode.org/blog/archives/2010/09/combining_meta.html
// ref: http://stackoverflow.com/questions/1988499/meta-tags-for-mobile-should-they-be-used
if (!function_exists('skeleton_mobile_meta')) {
 // 1.8.5: add to wp_head hook instead of separate skeleton_mobile_meta action
 add_action('wp_head','skeleton_mobile_meta',2);
 function skeleton_mobile_meta() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_mobile_meta',__FILE__);}
	 // TODO: test this specific-width mobile meta line effect?
	// $vmobilemeta .= '<meta name="MobileOptimized" content="320">'.PHP_EOL;
	$vmobilemeta = "<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->".PHP_EOL;
	$vmobilemeta = '<meta name="HandheldFriendly" content="True">'.PHP_EOL; // i wanna hold your haaand...
	// 1.8.5: fix to duplicate line if using Hybrid
	if (!THEMEHYBRID) {$vmobilemeta .= '<meta name="viewport" content="width=device-width, initial-scale=1" />'.PHP_EOL;}
	$vmobilemeta = apply_filters('skeleton_mobile_metas',$vmobilemeta);
	echo $vmobilemeta;
 }
}

// Skeleton Site Icons
// -------------------
// ref: http://www.jonathantneal.com/blog/understand-the-favicon/
if (!function_exists('skeleton_icons')) {
 add_action('admin_head','skeleton_icons');
 add_action('wp_head','skeleton_icons');
 function skeleton_icons() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_icons',__FILE__);}

	global $vthemesettings, $vthemedirs;

	// 1.8.0: fallback - auto-check for favicon files when URLs are not set

	// <!-- Apple Touch, use the 144x144 default, then optional sizes -->
	$vappleicons = '';
	$vwineighttile = $vthemesettings['wineighttile'];
	if ($vwineighttile == '') {$vwineighttile = skeleton_file_hierarchy('url','win8tile.png',$vthemedirs['img']);}
	if ($vwineighttile != '') {$vappleicons = '<link rel="apple-touch-icon-precomposed" size="144x144" href="'.$vthemesettings['wineighttile'].'">'.PHP_EOL;}
	$vicons = apply_filters('skeleton_apple_icons',$vappleicons);

	// <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices, 57x57 size -->
	$vappletouchicon = $vthemesettings['appletouchicon'];
	if ($vappletouchicon == '') {$vappletouchicon = skeleton_file_hierarchy('url','apple-touch-icon.png',$vthemedirs['img']);}
	if ($vappletouchicon != '') {
		$vicons .= '<link rel="apple-touch-icon-precomposed" href="'.$vthemesettings['appletouchicon'].'">'.PHP_EOL;
		$vicons .= '<link rel="apple-touch-icon" href="'.$vthemesettings['appletouchicon'].'">'.PHP_EOL;
	}

	// <!-- For anything accepting PNG icons, 96x96 default -->
	$vfaviconpng = $vthemesettings['faviconpng'];
	if ($vfaviconpng == '') {$vfaviconpng = skeleton_file_hierarchy('url','favicon.png',$vthemedirs['img']);}
	if ($vfaviconpng) {$vicons .= '<link rel="icon" href="'.$vfaviconpng.'">'.PHP_EOL;}

	// <!-- Just for IE, the default 32x32 or 16x16 size -->
	$vfaviconico = $vthemesettings['faviconico'];
	if ($vfaviconico == '') {$vfaviconico = skeleton_file_hierarchy('url','favicon.ico',$vthemedirs['img']);}
	// 1.8.0: allow for default favicon fallback in wordpress root directory
	if (!$vfaviconico) {if (file_exists(ABSPATH.DIRSEP.'favicon.ico')) {$vfaviconico = trailingslashit(site_url()).'favicon.ico';} }
	if ($vfaviconico) {$vicons .= '<!--[if IE]><link rel="shortcut icon" href="'.$vfaviconico.'"><![endif]-->'.PHP_EOL;}

	// <!-- For Windows 8, the tile and background -->
	if ($vwineighttile != '') {
		if ($vthemesettings['wineightbg'] != '') {$vwineightbg = $vthemesettings['wineightbg'];} else {$vwineightbg = '#FFFFFF';}
		$vicons .= '<meta name="msapplication-TileColor" content="'.$vwineightbg.'">'.PHP_EOL;
		$vicons .= '<meta name="msapplication-TileImage" content="'.$vwineighttile.'">'.PHP_EOL;
	}

	// 1.8.5: moved optional startup image output here
	$vstartupimages = apply_filters('skeleton_startup_images','');
	if ($vstartupimages != '') {$vicons .= $vstartupimages;}

	// 1.8.5: added icon override filter
	$vicons = apply_filters('skeleton_icons_override',$vicons);
	echo $vicons;
 }
}

// Apple Touch Icon Sizes
// ----------------------
// ref: https://mathiasbynens.be/notes/touch-icons
if ( (isset($vthemesettings['appleiconsizes'])) && ($vthemesettings['appleiconsizes'] == '1') ) {
	if (function_exists('skeleton_apple_icon_sizes')) {
	 add_filter('skeleton_apple_icons','skeleton_apple_icon_sizes');
	 // 1.8.5: fix to missing function argument
	 function skeleton_apple_icon_sizes($vsizes) {
		if (THEMETRACE) {skeleton_trace('F','skeleton_apple_icon_sizes',__FILE__,func_get_args());}

		global $vthemedirs;

		// 1.8.0: bugfix typo in hierarchy and url
		// <!-- For Chrome for Android -->
		$vimageurl = skeleton_file_hierarchy('url','touch-icon-192x192.png',$vthemedirs['img']);
		if ($vimageurl) {$vsizes .= '<link rel="icon" sizes="192x192" href="'.$vimageurl.'">';}

		$viconsizes = array();
		// <!-- For iPhone 6 Plus with @3× display: -->
		$viconsizes[] = '180x180';
		// <!-- For iPad with @2× display running iOS = 7: -->
		$viconsizes[] = '152x152';
		// <!-- For iPad with @2× display running iOS = 6: -->
		$viconsizes[] = '144x144';
		// <!-- For iPhone with @2× display running iOS = 7: -->
		$viconsizes[] = '120x120';
		// <!-- For iPhone with @2× display running iOS = 6: -->
		$viconsizes[] = '114x114';
		// <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS = 7: -->
		$viconsizes[] = '76x76';
		// <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS = 6: -->
		$viconsizes[] = '72x72';

		foreach ($viconsizes as $viconsize) {
			$vurl = skeleton_file_hierarchy('url','touch-icon-'.$viconsize.'.png',$vthemedirs['img']);
			if ($vurl) {$vsizes .= '<link rel="apple-touch-icon-precomposed" sizes="'.$viconsize.'" href="'.$vurl.'">'.PHP_EOL;}
			else {
				// 1.8.5: allow for fallback for maybe using -precomposed suffix
				$vurl = skeleton_file_hierarchy('url','touch-icon-'.$viconsize.'-precomposed.png',$vthemedirs['img']);
				if ($vurl) {$vsizes .= '<link rel="apple-touch-icon-precomposed" sizes="'.$viconsize.'" href="'.$vurl.'">'.PHP_EOL;}
			}
		}

		return $vsizes;
	 }
	}
}

// Apple Startup Images
// --------------------
if ( (isset($vthemesettings['startupimages'])) && ($vthemesettings['startupimages'] == '1') ) {
	if (!function_exists('skeleton_startup_images')) {
	 add_filter('skeleton_startup_images','skeleton_apple_startup_images');
	 function skeleton_apple_startup_images() {
		if (THEMETRACE) {skeleton_trace('F','skeleton_apple_startup_images',__FILE__);}

		global $vthemedirs;

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
			$vurl = skeleton_file_hierarchy('url','startup-'.$vstartup['size'].'.png',$vthemedirs['img']);
			if ($vurl) {$vimages .= '<link rel="apple-touch-startup-image" sizes="'.$vstartup['size'].'" href="'.$vurl.'startup-'.$vsize.'.png" media="'.$vstartup['media'].'">'.PHP_EOL;}
		}

		return $vimages;
	 }
	}
}

// Adjust CSS Hero Declarations Path
// ---------------------------------
// 1.8.5: allow moving of csshero.js from theme root to javascript dirs
// also allows for the file to be in the parent theme directory
// (this is a bit hacky, hopefully a real filter is available for this in future!)
if ( (isset($_GET['csshero_action'])) && ($_GET['csshero_action'] == 'edit_page') ) {
	// 1.9.5: added filter to optionally disable this path adjustment
	$vcsshero = apply_filters('skeleton_adjust_css_hero_script_dir',true);
	if ($vcsshero) {
		add_action('wp_loaded','skeleton_csshero_script_dir',0);
		if (!function_exists('skeleton_csshero_script_dir')) {
		 function skeleton_csshero_script_dir() {
			add_filter('stylesheet_directory_uri','skeleton_csshero_script_url',10,3);
			function skeleton_csshero_script_url($stylesheet_dir_uri, $stylesheet, $theme_root_uri) {
				if (THEMETRACE) {skeleton_trace('F','skeleton_csshero_script_url',__FILE__,func_get_args());}
				global $vthemedirs;
				$vcsshero = skeleton_file_hierarchy('url','csshero.js',$vthemedirs['js']);
				if ($vcsshero) {$stylesheet_dir_uri = dirname($vcsshero);}
				remove_filter('stylesheet_directory_uri','skeleton_css_hero_script_url',10,3);
				return $stylesheet_dir_uri;
			}
		 }
		}
	}
}

// -------------------------
// Included Templates Tracer
// -------------------------

// only load for Debug Mode
// ------------------------
if (THEMEDEBUG) {
	add_action('wp_loaded','skeleton_check_theme_includes');
	add_action('wp_footer','skeleton_check_theme_templates');
}

// Get All Included Theme Files
// ----------------------------
// 1.8.5: added this debugging function
if (!function_exists('skeleton_get_theme_includes')) {
 function skeleton_get_theme_includes() {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_get_theme_includes',__FILE__);}

	$vincludedfiles = get_included_files();
	// normalize theme paths for matching
	$vstyledirectory = str_replace("\\","/",get_stylesheet_directory());
	$vtemplatedirectory = str_replace("\\","/",get_template_directory());

	$vi = 0; // loop included files
	foreach ($vincludedfiles as $vincludedfile) {
		// normalize include path for match
		$vincludedfile = str_replace("\\","/",$vincludedfile);
		// check if included file is in stylesheet directory
		if (substr($vincludedfile,0,strlen($vstyledirectory)) != $vstyledirectory) {
			// if stylesheet is same as template, not a child theme
			if ($vstyledirectory == $vtemplatedirectory) {unset($vincludedfiles[$vi]);}
			else {
				// check if included file is in template directory
				if (substr($vincludedfile,0,strlen($templatedir)) != $templatedir) {unset($vincludedfiles[$vi]);}
				else {
					// strip template directory from include path
					$vpathinfo = pathinfo(str_replace(dirname($templatedir),'',$vincludedfile));
					// add filename.php => pathinfo array to the template array
					$vthemeincludes[$pathinfo['basename']] = $vpathinfo;
				}
			}
		} else {
			// strip stylesheet dir from include path
			$vpathinfo = pathinfo(str_replace(dirname($vstyledirectory),'',$vincludedfile));
			// add filename.php => pathinfo array to the template array
			$vthemeincludes[$vpathinfo['basename']] = $vpathinfo;
		}
		$vi++;
	}
	return $vthemeincludes;
 }
}

// check Theme Included Files
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('skeleton_check_theme_includes')) {
	function skeleton_check_theme_includes() {
	 	if (THEMETRACE) {skeleton_trace('F','skeleton_check_theme_includes',__FILE__);}
		global $vthemeincludes; $vthemeincludes = skeleton_get_theme_includes();
	}
}

// get Included Template List
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('skeleton_check_theme_templates')) {
 function skeleton_check_theme_templates() {
	if (THEMETRACE) {skeleton_trace('F','skeleton_check_theme_templates',__FILE__);}
	global $vthemeincludes, $vtemplateincludes;
	$vtemplateincludes = skeleton_get_theme_includes();

	// strip out included theme files from template list
	foreach ($vtemplateincludes as $template => $pathinfo) {
		if (array_key_exists($template,$vthemeincludes)) {
			if ($pathinfo['dirname'] == $vthemeincludes[$template]['dirname']) {
				unset($vtemplateincludes[$template]);
			}
		}
	}
	if (THEMEDEBUG) {echo "<!-- Included Template Files: "; print_r($vtemplateincludes); echo "-->";}

	// TODO: make this an option or filter option?
	// could output a template array for use by jquery/ajax loading
	// echo "<script>var templatenames = new Array(); var templatepaths = new Array(); ";
	// $i = 0;
	// foreach ($vtemplateincludes as $template => $pathinfo) {
	//  // optionally strip the .php extension
	//  $template = str_replace('.php','',$template);
	//  // output the template array key/value
	//  echo "templatenames[".$i."] = '".$pathinfo['filename']."'; ";
	//  echo "templatepaths[".$i."] = '".$pathinfo['dirname']."'; ";
	//  $i++;
	// }
	// echo "</script>";

	// TODO: inspector-style list of included templates as dropdown in admin bar?

 }
}

?>