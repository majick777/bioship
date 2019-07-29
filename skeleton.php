<?php

// ==========================
// ==== BioShip Skeleton ====
// == Flexible Templating ===
// ==========================

// --- no direct load ---
if (!defined('ABSPATH')) {exit;}

// ------------------------------
// === skeleton.php Structure ===
// ------------------------------
// === Wrapper ===
// - Main Wrapper Open
// - Main Wrapper Close
// - Output Clear Div
// - Add Clear Divs to Layout
// === Header ===
// - Header Wrap Open
// - Header Wrap Close
// - Header Nav Menu
// - Header Logo
// - Header Widgets
// - Header Extras
// === Nav Menus ===
// - Main Menu Wrap Open
// - Primary Navigation Menu
// - Main Menu Wrap Close
// - Main Menu Mobile Button
// - Secondary Menu
// === Banners ===
// - Abstract Banner
// - Top Banner
// - Header Banner
// - NavBar Banner
// - Footer Banner
// - Bottom Banner
// === Sidebars ===
// - Add Classes to Widgets
// == Primary Sidebar ==
// - Add Body Class for Sidebar
// - Mobile Sidebar Button
// - Sidebar Wrap Open
// - Sidebar Wrap Close
// == Subsidiary Sidebar ==
// - Add Body Class for SubSidebar
// - Mobile SubSidebar Button
// - SubSidebar Wrap Open
// - SubSidebar Wrap Close
// === Content ===
// - WooCommerce Wrapper Open
// - WooCommerce Wrapper Close
// - Content Wrap Open
// - Content Wrap Close
// - Home (Blog) Page Top Content
// - Home (Blog) Page Bottom Content
// - Front Page Top Content
// - Front Page Bottom Content
// - Output the Excerpt
// - Ensure Content Not in Head
// - Output the Content
// - Media Handler
// === Content Meta ===
// == Entry Header ==
// - Entry Header Wrap Open
// - Entry Header Wrap Close
// - Entry Header Title
// - Entry Header Subitle
// - Entry Header Meta
// == Entry Footer ==
// - Entry Footer Wrap Open
// - Entry Footer Wrap Close
// - Entry Footer Meta
// === Thumbnails ===
// - Echo Thumbnail Action
// - Get Thumbnail for Templates
// - Skeleton Thumbnailer
// === Author Bio ===
// - Echo Author Bio Action
// - Echo Author Bio (Top)
// - Echo Author Bio (Bottom)
// - Author Bio Box
// - About Author Title Text
// - About Author Description
// - Author Posts Text
// === Comments ===
// - Echo Comments Action
// - Skeleton Comments Callback
// ? Comments Popup Script
// === Breadcrumbs ===
// - Output Breadcrumbs
// - Check Breadcrumbs
// === Page Navi ===
// - Output Page Navigation
// - Paged Navigation
// === Footer ===
// - Footer Wrap Open
// - Footer Wrap Close
// - Footer Extras
// - Footer Widgets
// - Footer Nav Menu
// - Footer Credits
// - Get Site Credits
// ------------------------------

// Note: Simple Skeleton Theme was initial codebase for templating functions
// (ALL templating functions have been rewritten)

// -----------------
// Development TODOs
// -----------------
// - move doubled html_extras filter names to compat.php
// ? recheck perpost meta display overrides for site title/description ?
// ? use another method instead of preg_replace for widget classes ?
// ? recheck prepend_attachment filter for improving media handler ?
// ? maybe display image sizes for image media handler ?
// ? Gallery post format display media handler ?
// ? show Author Gravatar for Status post format ?
// ? check/add author bio position for archives ?
// ? optimize comments callback template ?
// ? alternative for comments_popup_script ?
// ? maybe check existing page context for breadcrumbs ?
// ? check display options for more breadcrumb contexts ?
// ? add a fallback breadcrumb method if not using Hybrid ?
// ? check display options for other page navigation contexts ?
// ? handle image navigation with next_image_link and previous_image_link ?
// ? use post navigation paginate option ?
// ? add position hook trigger for paged navigation ?
// ? use bioship_add_action for bioship_skeleton_footer ?


// ---------------
// === Wrapper ===
// ---------------

// -----------------
// Main Wrapper Open
// -----------------
if (!function_exists('bioship_skeleton_wrapper_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_container_open', 'bioship_skeleton_wrapper_open', 5);

	function bioship_skeleton_wrapper_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout;

		// --- set default wrap container classes ---
		// 1.8.5: use new theme layout global
		$classes = array('container', $vthemelayout['gridcolumns']);

		// --- filter wrap container classes ---
		// 1.5.0: added container class compatibility
		// 1.8.5: removed grid compatibility classes (now content grid only)
		// filter the main wrap container classes
		$containerclasses = bioship_apply_filters('skeleton_container_classes', $classes);
		if (is_array($containerclasses)) {
			// 2.0.5: use standard array key index
			foreach ($containerclasses as $i => $class) {$containerclasses[$i] = trim($class);}
			$classes = $containerclasses;
		}
		$classstring = implode(' ', $classes);

		// --- output wrap container open --
		bioship_html_comment('#wrap.container');
		echo '<div id="wrap" class="'.esc_attr($classstring).'">'.PHP_EOL;
		bioship_html_comment('#wrappadding.inner');
		echo '	<div id="wrappadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// ------------------
// Main Wrapper Close
// ------------------
if (!function_exists('bioship_skeleton_wrapper_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_container_close', 'bioship_skeleton_wrapper_close', 5);

	function bioship_skeleton_wrapper_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output wrap container close ---
		echo '	</div>'.PHP_EOL;
		bioship_html_comment('/#wrappadding.inner');
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#wrap.container');
		echo PHP_EOL;
	}
}

// ----------------
// Output Clear Div
// ----------------
// 1.5.0: moved clear div here for flexibility
if (!function_exists('bioship_skeleton_echo_clear_div')) {
	function bioship_skeleton_echo_clear_div() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo PHP_EOL.'<div class="clear"></div>'.PHP_EOL;
	}
}

// ------------------------
// Add Clear Divs to Layout
// ------------------------
// (see hooks.php for full layout position map)
// 1.9.8: use bioship_add_action to make positions filterable
// note: use "hook_function_position" combination as filter name to change these positions
// eg. add_filter('bioship_header_bioship_echo_clear_div_position', function(){return 4;} );

// --- after header menu ---
bioship_add_action('bioship_header', 'bioship_skeleton_echo_clear_div', 3);
// --- after nav menu ---
// 1.8.0: moved sidebar buttons to inline
// add_action('bioship_navbar', 'skeleton_echo_clear_div', 6);
// --- after nav bar ---
bioship_add_action('bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 0);
bioship_add_action('bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 8);
// --- after content area ---
bioship_add_action('bioship_after_content', 'bioship_skeleton_echo_clear_div', 2);
// --- before footer ---
bioship_add_action('bioship_before_footer', 'bioship_skeleton_echo_clear_div', 10);
// --- after footer widgets ---
bioship_add_action('bioship_footer', 'bioship_skeleton_echo_clear_div', 5);
// --- after footer nav ---
bioship_add_action('bioship_footer', 'bioship_skeleton_echo_clear_div', 7);


// --------------
// === Header ===
// --------------

// Header Action Hooks
// -------------------
// bioship_skeleton_header_open: 	0
// bioship_skeleton_header_nav: 	2
// bioship_skeleton_header_logo:	4
// bioship_skeleton_header_widgets: 6
// bioship_skeleton_header_extras:  8
// bioship_skeleton_header_close:  10

// ----------------
// Header Wrap Open
// ----------------
if (!function_exists('bioship_skeleton_header_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_open', 0);

	function bioship_skeleton_header_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout;

		// --- set default header classes ---
		// 1.5.0: added header class compatibility and filter
		// 1.8.0: added alpha and omega classes to header div
		// 1.8.5: use new theme layout global
		// 1.9.0: removed 960gs classes from theme grid (now for content grid only)
		$classes = array($vthemelayout['gridcolumns'], 'columns', 'alpha', 'omega');

		// --- filter header classes ---
		$headerclasses = bioship_apply_filters('skeleton_header_classes', $classes);
		// 2.1.1: added filtered array check and class space trimming
		if (is_array($headerclasses)) {
			foreach ($headerclasses as $i => $class) {$headerclasses[$i] = trim($class);}
			$classes = $headerclasses;
		}
		$classstring = implode(' ', $classes);

		// --- output header wrap open ---
		bioship_html_comment('#header');
		$attributes = hybrid_get_attr('header');
		echo '<div id="header" class="'.esc_attr($classstring).'">'.PHP_EOL;
		bioship_html_comment('#headerpadding.inner');
		echo '	<div id="headerpadding" class="inner">'.PHP_EOL;
		echo '		<header '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------
// Header Wrap Close
// -----------------
if (!function_exists('bioship_skeleton_header_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_close', 10);

	function bioship_skeleton_header_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output header wrap close ---
		echo PHP_EOL.'		</header>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		bioship_html_comment('/#headerpadding.inner');
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#header');
		echo PHP_EOL;
	}
}

// ---------------
// Header Nav Menu
// ---------------
if (!function_exists('bioship_skeleton_header_nav')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header' ,'bioship_skeleton_header_nav', 2);

	function bioship_skeleton_header_nav() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for header menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['header']) || !$layout['menus']['header']) {return;}

		// --- set default header menu settings ---
		$menuargs = array(
			'theme_location'  => 'header',
			'container'       => 'div',
			'container_id'    => 'headermenu',
			'menu_class'      => 'menu',
			'echo'            => false,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1
		);

		// --- filter header menu settings ---
		// 1.8.5: added missing menu setting filter
		// 2.0.5: added _settings filter suffix
		$menuargs = bioship_apply_filters('skeleton_header_menu_settings', $menuargs);

		// --- output header menu ---
		// note: can use Hybrid attribute filter to add column classes
		$attributes = hybrid_get_attr('menu', 'header');
		$menu = bioship_html_comment('.header-menu', false);
		$menu .= '<div '.$attributes.'>'.PHP_EOL;
		$menu .= '	'.wp_nav_menu($menuargs).PHP_EOL;
		$menu .= '</div>'.PHP_EOL;
		$menu .= bioship_html_comment('/.header-menu', false);
		$menu .= PHP_EOL;

		// --- filter and output ---
		// 2.1.2: added missing header menu filter
		// TODO: add filter to doc filter list
		$menu = bioship_apply_filters('skeleton_header_menu', $menu);
		echo $menu; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------
// Header Logo
// -----------
if (!function_exists('bioship_skeleton_header_logo')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_logo', 4);

	function bioship_skeleton_header_logo() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemedisplay;

		// --- get site name and description ---
		$blogname = get_bloginfo('name', 'display');
		$blogname = bioship_apply_filters('skeleton_blog_display_name', $blogname);
		$blogdescription = get_bloginfo('description');
		$blogdescription = bioship_apply_filters('skeleton_blog_description', $blogdescription);
		// 2.0.9: make title attribute separator filterable (default to title tag separator)
		$sep = bioship_title_separator('|');
		$sep = bioship_apply_filters('skeleton_header_logo_title_separator', $sep);
		// 2.1.1: added filter for home link title
		$linktitle = $blogname.' '.$sep.' '.$blogdescription;
		$linktitle = bioship_apply_filters('skeleton_home_link_title', $linktitle);

		// --- get filtered home URL ---
		// 1.8.5: use home_url not site_url
		$homeurl = home_url('/');
		// 2.0.6: added site logo and home title link filters
		$homeurl = bioship_apply_filters('skeleton_title_link_url', $homeurl);
		$logolinkurl = bioship_apply_filters('skeleton_logo_link_url', $homeurl);

		// --- get filtered logo URL ---
		$logourl = $vthemesettings['header_logo'];
		// 1.5.0: moved logo url filter
		$logourl = bioship_apply_filters('skeleton_header_logo_url', $logourl);
		// 2.0.9: use esc_url on logo output
		$logourl = esc_url($logourl);

		// --- filter logo classes ---
		// 1.8.0: added header logo class filter
		$logoclasses = array('logo');
		$logoclasses = bioship_apply_filters('skeleton_header_logo_classes', $logoclasses);
		$logoclasses = implode(' ', $logoclasses);

		// --- set logo image display style ---
		// 1.8.5: recombined image/text template for live previewing
		// 1.8.5: added site text / description text display checkboxes
		// 1.9.0: fix to logo logic here having separated text display
		// 2.0.6: display as inline block (for combine logo and site title)
		// 2.0.7: move inline-block display to style.css for easier overriding
		$logoimagedisplay = ' style="display:none;"';
		if ($logourl) {$logoimagedisplay = '';}

		// --- check site title and description display settings ---
		// 2.1.1: fix to switch variables for site title and description display
		$sitetitle = $sitedescription = false;
		if ( (isset($vthemesettings['header_texts']['sitetitle']))
		  && ($vthemesettings['header_texts']['sitetitle'] == '1') ) {$sitetitle = true;}
		$sitetitle = bioship_apply_filters('skeleton_site_title_display', $sitetitle);
		if ( (isset($vthemesettings['header_texts']['sitedescription']))
		  && ($vthemesettings['header_texts']['sitedescription'] == '1') ) {$sitedescription = true;}
		$sitedescription = bioship_apply_filters('skeleton_site_description_display', $sitedescription);

		// TODO: recheck perpost meta display overrides for site title/description ?
		// (currently this is done via style overrides)
		// if ($vthemedisplay['sitetitle'] == '') {}
		// if ($vthemedisplay['sitedesc'] == '') {}

		// 1.9.9: set separate display variables for site title and description
		$titledisplay = $descriptiondisplay = '';
		if (!$sitetitle) {$titledisplay = ' style="display:none;"';}
		if (!$sitedescription) {$descriptiondisplay = ' style="display:none;"';}

		// --- set logo and title section output ---
		// 1.8.5: fix to hybrid attributes names (_ to -)
		// 1.9.0: added filter to site-description attribute to prevent duplicate ID
		// 1.9.6: added logo-image ID not just class
		// 2.0.6: display inline-block for site-logo-text (for combined display)
		// 2.0.7: move inline block to style.css for easier overriding
		// 2.1.1: moved esc_url and esc_attr usage inline
		// 2.1.2: changed site-desc span to div to allow text width wrapping
		$output = '';
		$output .= bioship_html_comment('#site-logo', false);
		$output .= '<div id="site-logo" class="'.esc_attr($logoclasses).'">'.PHP_EOL;
		$output .= '	<div class="inner">'.PHP_EOL;
		$output .= ' 		<div class="site-logo-image"'.$logoimagedisplay.'>'.PHP_EOL;
		$output .= '			<a class="logotype-img" href="'.esc_url($logolinkurl).'" title="'.esc_attr($linktitle).'" rel="home">'.PHP_EOL;
		$output .= '				<h1 id="site-title">'.PHP_EOL;
		$output .= '					<img id="logo-image" class="logo-image" src="'.esc_url($logourl).'" alt="'.esc_attr($blogname).'" border="0">'.PHP_EOL;
		$output .= '					<div class="alt-logo" style="display:none;"></div>'.PHP_EOL;
		$output .= '				</h1>'.PHP_EOL;
		$output .= '			</a>'.PHP_EOL;
		$output .= ' 	 	</div>'.PHP_EOL;
		$output .= ' 	 	<div class="site-logo-text">'.PHP_EOL;
		$output .= '			<h1 id="site-title-text" '.hybrid_get_attr('site-title').esc_attr($titledisplay).'>'.PHP_EOL;
		$output .= '				<a class="text" href="'.esc_url($homeurl).'" title="'.esc_attr($linktitle).'" rel="home">'.esc_attr($blogname).'</a>'.PHP_EOL;
		$output .= '			</h1>'.PHP_EOL;
		$output .= '			<div id="site-description"'.$descriptiondisplay.'>'.PHP_EOL;
		$output .= '				<div class="site-desc" '.hybrid_get_attr('site-description').'>'.esc_attr($blogdescription).'</div>'.PHP_EOL;
		$output .= '			</div>'.PHP_EOL;
		$output .= ' 		</div>'.PHP_EOL;
		$output .= '	</div>'.PHP_EOL;
		$output .= '</div>'.PHP_EOL;
		$output .= bioship_html_comment('/#site-logo', false);
		$output .= PHP_EOL;

		// --- filter HTML and output ---
		$output = bioship_apply_filters('skeleton_header_logo_override', $output);
		echo $output; // phpcs:ignore WordPress.Security.OutputNotEscaped, WordPress.Security.OutputNotEscapedShortEcho
	}
}

// --------------
// Header Widgets
// --------------
if (!function_exists('bioship_skeleton_header_widgets')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_widgets', 6);

	function bioship_skeleton_header_widgets() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output header sidebar template ---
		// note: template filterable to allow for custom post types (see filters.php)
		// default template is sidebar/header.php
		$header = bioship_apply_filters('skeleton_header_sidebar', 'header');
		hybrid_get_sidebar($header);
	}
}

// -------------
// Header Extras
// -------------
if (!function_exists('bioship_skeleton_header_extras')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_extras', 8);

	function bioship_skeleton_header_extras() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;

		// --- get header extras ---
		// 2.0.0: allow for use of shorter header extras filter name
		$headerextras = bioship_apply_filters('skeleton_header_extras', '');
		// TODO: make this a backwards compatible filter and deprecate?
		$headerextras = bioship_apply_filters('skeleton_header_html_extras', $headerextras);

		if ($headerextras != '') {
			// 1.8.0: changed #header_extras to #header-extras for consistency, added class filter
			// 2.1.1: filter header extra class array instead of string
			$classes = array('header-extras');
			$extraclasses = bioship_apply_filters('skeleton_header_extras_classes', $classes);
			if (is_array($extraclasses)) {
				foreach ($extraclasses as $i => $class) {$extraclasses[$i] = trim($class);}
				$classes = $extraclasses;
			}
			$classstring = implode(' ', $classes);

			// --- output header extras HTML ---
			bioship_html_comment('#header-extras');
			echo '<div id="header-extras" class="'.esc_attr($classes).'">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo '		'.$headerextras.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '	</div>'.PHP_EOL;
			echo '</div>';
			bioship_html_comment('/#header-extras');
			echo PHP_EOL;
		}
	}
}

// -----------------
// === Nav Menus ===
// -----------------

// -------------------
// Main Menu Wrap Open
// -------------------
if (!function_exists('bioship_skeleton_main_menu_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_open', 0);

	function bioship_skeleton_main_menu_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['primary']) || !$layout['menus']['primary']) {return;}

		// --- output main menu wrap open ---
		// note: can filter classes using Hybrid attribute filter
		bioship_html_comment('#navigation');
		$attributes = hybrid_get_attr('menu', 'primary');
		echo '<div id="navigation" '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------------
// Primary Navigation Menu
// -----------------------
if (!function_exists('bioship_skeleton_main_menu')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu', 8);

	function bioship_skeleton_main_menu() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['primary']) || !$layout['menus']['primary']) {return;}

		// --- check navigation remove filter ---
		// 1.9.9: check hide navigation override filter
		// 2.1.1: changed filter name to navigation remove (hiding is via styles)
		$removenav = bioship_apply_filters('skeleton_navigation_remove', false);
		if ($removenav) {return;}

		// --- set and filter menu args ---
		// 1.8.0: only output if there is a menu is assigned
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$menuargs = array(
			'container_id'		=> 'mainmenu',
			'container_class'	=> 'menu-header',
			'theme_location'	=> 'primary',
			'echo'				=> false
		);
		$menuargs = bioship_apply_filters('skeleton_primary_menu_settings', $menuargs);

		// --- output main menu ---
		// 2.1.2: added wrapper to main menu for consistency
		$attributes = hybrid_get_attr('menu', 'primary');
		$menu = bioship_html_comment('#primarymenu', false);
		$menu .= '<div id="secondarymenu" '.$attributes.'>'.PHP_EOL;
		$menu .= '	<div class="inner">'.PHP_EOL;
		$menu .= '		'.wp_nav_menu($menuargs).PHP_EOL;
		$menu .= '	</div>'.PHP_EOL;
		$menu .= '</div>'.PHP_EOL;
		$menu .= bioship_html_comment('/#primarymenu', false);

		// --- filter and output primary menu ---
		// 2.1.2: added missing primary menu filter override
		// TODO: add filter to documentation filter list
		$menu = bioship_apply_filters('skeleton_primary_menu', $menu);
		echo $menu; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho

	}
}

// --------------------
// Main Menu Wrap Close
// --------------------
if (!function_exists('bioship_skeleton_main_menu_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_close', 10);

	function bioship_skeleton_main_menu_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['primary']) || !$layout['menus']['primary']) {return;}

		// --- output main menu wrap close ---
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#navigation');
		echo PHP_EOL;
	}
}

// -----------------------
// Main Menu Mobile Button
// -----------------------
// 1.5.0: added mobile menu button
if (!function_exists('bioship_skeleton_main_menu_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_button', 4);

	function bioship_skeleton_main_menu_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['primary']) || !$layout['menus']['primary']) {return;}

		// --- check hide navigation filter ----
		// 1.9.9: check hide navigation override filter
		// 2.1.1: changed filter name to navigation remove (hiding is via styles)
		$removenav = bioship_apply_filters('skeleton_navigation_remove', false);
		if ($removenav) {return;}

		// --- set menu button texts ---
		// 2.1.1: added missing translation wrappers
		$showmenutext = esc_attr(__('Show Menu', 'bioship'));
		$hidemenutext = esc_attr(__('Hide Menu', 'bioship'));

		// --- set menu buttons ---
		// 2.1.3: prefix javascript functions
		$buttons = '<div id="mainmenubutton" class="mobilebutton">'.PHP_EOL;
		$buttons .= '	<a class="button" id="mainmenushow" href="javascript:void(0);" onclick="bioship_showmainmenu();">'.esc_attr($showmenutext).'</a>'.PHP_EOL;
		$buttons .= '	<a class="button" id="mainmenuhide" href="javascript:void(0);" onclick="bioship_hidemainmenu();" style="display:none;">'.esc_attr($hidemenutext).'</a>'.PHP_EOL;
		$buttons .= '</div>'.PHP_EOL;

		// --- filter and output menu buttons ---
		// 2.1.1: added mobile menu buttons filter
		$buttons = bioship_apply_filters('skeleton_mobile_menu_buttons', $buttons);
		echo $buttons; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// --------------
// Secondary Menu
// --------------
// note: action 'skeleton_secondarynav' is not actually called anywhere
// this is an auxiliary navigation bar available for custom positioning
// ...or direct firing via: do_action('bioship_secondarynav');
if (!function_exists('bioship_skeleton_secondary_menu')) {

	// 1.9.8: use new position filtered add_action method
	// 1.9.8: hooked this to an existing template position (previously unused)
	bioship_add_action('bioship_before_header', 'bioship_skeleton_secondary_menu', 8);

	add_action('bioship_secondarynav', 'bioship_skeleton_secondary_menu');

	function bioship_skeleton_secondary_menu() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check if secondary menu enabled ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['secondary']) || !$layout['menus']['secondary']) {return;}

		// --- set and filter menu arguments ---
		// 2.1.1: add echo to false to allow filtering
		$menuargs = array(
			'container_id' 		=> 'submenu',
			'container_class' 	=> 'menu-header',
			'theme_location' 	=> 'secondary',
			'echo'				=> false
		);
		$menuargs = bioship_apply_filters('skeleton_secondary_menu_settings', $menuargs);

		// --- output secondary menu ---
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$attributes = hybrid_get_attr('menu', 'secondary');
		$menu = bioship_html_comment('#secondarymenu', false);
		$menu .= '<div id="secondarymenu" '.$attributes.'>'.PHP_EOL;
		$menu .= '	<div class="inner">'.PHP_EOL;
		$menu .= '		'.wp_nav_menu($menuargs).PHP_EOL;
		$menu .= '	</div>'.PHP_EOL;
		$menu .= '</div>'.PHP_EOL;
		$menu .= bioship_html_comment('/#secondarymenu', false);
		$menu .= PHP_EOL;

		// --- filter and output secondary menu ---
		$menu = bioship_apply_filters('skeleton_secondary_menu', $menu);
		echo $menu; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// ---------------
// === Banners ===
// ---------------
// 1.8.0: added all full width banner positions
// skeleton_{position}_banner default positions:
// top: above main header area
// header: below main header area (before navbar)
// navbar: after navbar (before sidebars/content)
// footer: above main footer area
// bottom: below main footer area

// ---------------
// Abstract Banner
// ---------------
if (!function_exists('bioship_skeleton_banner_abstract')) {
 function bioship_skeleton_banner_abstract($position) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

 	// --- get initial banner output ---
 	// (to allow for HTML / ads / shortcode / widget etc...)
 	$banner = bioship_apply_filters('skeleton_banner_'.$position, '');

	// --- check per post banner field values ---
 	if (is_singular()) {
 		// note: can set custom field values (for automatic image banners only)
 		// eg. _topbannerurl and _topbannerlink etc...
 		global $post; $postid = $post->ID;

		// 2.1.1: change post meta keys from _{position}banner{url/link} to banner_{url/link}_{position}
 		$bannerurl = get_post_meta($postid, 'banner_url'.$position, true);
 		$bannerlink = get_post_meta($postid, 'banner_link_'.$position, true);
 		if ($bannerurl && $bannerlink && ($trim(bannerurl) != '') && (trim($bannerlink) != '')) {
 			$banner = '<a href="'.esc_url($bannerlink).'" target=_blank>'.PHP_EOL;
 			$banner .= '	<img src="'.esc_url($bannerurl).'" border="0">'.PHP_EOL;
 			$banner .= '</a>'.PHP_EOL;
 		}
 	}


 	if ($banner != '') {

 		// --- set banner class ---
 		// 1.9.8: added banner div class filter
 		// 2.0.5: added extra filter based on banner position
 		$class = bioship_apply_filters('skeleton_banner_class', $position);
 		$class = bioship_apply_filters('skeleton_banner_class_'.$position, $class);
 		if ($class != $position) {$class = ' class="'.$class.'"';}

 		// --- banner output ---
	 	$banner = bioship_html_comment('#'.$position.'banner', false);
	 	$banner .= '<div id="'.esc_attr($position).'banner"'.$class.'>'.PHP_EOL;
	 	$banner .= '	<div class="inner">'.PHP_EOL;
 		$banner .= '		'.$banner.PHP_EOL;
 		$banner .= '	</div>'.PHP_EOL;
 		$banner .= '</div>'.PHP_EOL;
 		$banner .= bioship_html_comment('/#'.$position.'banner', false);
 		$banner .= PHP_EOL;

 		// --- filter and output banner ---
 		$banner = bioship_apply_filters('skeleton_banner_override_'.$position, $banner);
 		echo $banner; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
 }
}

// ----------
// Top Banner
// ----------
// 1.8.0: added banner position (above header)
if (!function_exists('bioship_skeleton_banner_top')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_header', 'bioship_skeleton_banner_top', 2);

	function bioship_skeleton_banner_top() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_banner_abstract('top');
	}
}

// -------------
// Header Banner
// -------------
// 1.8.0: added banner position (below header)
if (!function_exists('bioship_skeleton_banner_header')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_header', 'bioship_skeleton_banner_header', 5);

	function bioship_skeleton_banner_header() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_banner_abstract('header');
	}
}

// -------------
// NavBar Banner
// -------------
// 1.8.0: added banner position (under navbar)
if (!function_exists('bioship_skeleton_banner_navbar')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_navbar', 'bioship_skeleton_banner_navbar', 10);

	function bioship_skeleton_banner_navbar() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_banner_abstract('navbar');
	}
}

// -------------
// Footer Banner
// -------------
// 1.8.0: added banner position (above footer)
if (!function_exists('bioship_skeleton_banner_footer')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_footer', 'bioship_skeleton_banner_footer', 5);

	function bioship_skeleton_banner_footer() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_banner_abstract('footer');
	}
}

// -------------
// Bottom Banner
// -------------
// 1.8.0: added banner position (below footer)
if (!function_exists('bioship_skeleton_banner_bottom')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_footer', 'bioship_skeleton_banner_bottom', 5);

	function bioship_skeleton_banner_bottom() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_banner_abstract('bottom');
	}
}


// ----------------
// === Sidebars ===
// ----------------

// ----------------------
// Add Classes to Widgets
// ----------------------
// adapted from: http://wordpress.stackexchange.com/a/54505/76440
// 2.1.4: moved add_filter internally for consistency
if (!function_exists('bioship_skeleton_add_widget_classes')) {

 add_filter('dynamic_sidebar_params', 'bioship_skeleton_add_widget_classes');

 function bioship_skeleton_add_widget_classes($params) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemewidgets, $vthemewidgetcounter;

	// --- set sidebar ID ---
	$sidebarid = $params[0]['id']; $classes = array();

	// --- set widget defaults ---
    if (!isset($vthemewidgets)) {$vthemewidgets = wp_get_sidebars_widgets();}
    if (!isset($vthemewidgetcounter)) {$vthemewidgetcounter = array();}

    // --- bail if the current sidebar has no widgets ---
    if (!isset($vthemewidgets[$sidebarid]) || !is_array($vthemewidgets[$sidebarid])) {return $params;}

    // [not implemented] this is for horizontal span classes
    // Rounds number of widgets down to a whole number
    // $number_of_widgets = count($vthemewidgets[$sidebarid]);
    // $rounded_number_of_widgets = floor(12 / $number_of_widgets);
	// $classes[] = 'span'.$rounded_number_of_widgets;

	// --- increment / start widget counter for this sidebar ---
    if (isset($vthemewidgetcounter[$sidebarid])) {$vthemewidgetcounter[$sidebarid]++;}
    else {$vthemewidgetcounter[$sidebarid] = 1; $classes[] = 'first-widget';}
	if ($vthemewidgetcounter[$sidebarid] == count($vthemewidgets[$sidebarid])) {$classes[] = 'last-widget';}

	// --- add odd / even classes to widgets ---
    if ($vthemewidgetcounter[$sidebarid] & 1) {$classes[] = 'odd-widget';} else {$classes[] = 'even-widget';}

	// --- set replacement sidebar class string ---
	$classstring = implode(' ', $classes).' ';

	// --- replace widget classes ---
	// TODO: maybe use another method instead of preg_replace ?
    $params[0]['before_widget'] = preg_replace('/class=\"/', 'class="'.$classstring.' ', $params[0]['before_widget'], 1);

    return $params;
 }
}

// -----------------------
// === Primary Sidebar ===
// -----------------------

// --------------------------
// Add Body Class for Sidebar
// --------------------------
// 1.8.0: rename from skeleton_sidebar_position
if (!function_exists('bioship_skeleton_sidebar_position_class')) {

	add_filter('body_class', 'bioship_skeleton_sidebar_position_class');

	function bioship_skeleton_sidebar_position_class($classes) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// --- check for sidebars ---
		global $vthemesidebars;
		if (!$vthemesidebars['sidebar']) {return $classes;}

		// --- set sidebar position class ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ($sidebars as $i => $sidebar) {
			// note: sub prefix incidates subsidebar
			if ( ($sidebar != '') && (substr($sidebar, 0, 3) != 'sub') ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( ($i == 0) || ($i == 1) ) {$classes[] = 'sidebar-left';}
				if ( ($i == 2) || ($i == 3) ) {$classes[] = 'sidebar-right';}
			}
		}
		return $classes;
	}
}

// ---------------------
// Mobile Sidebar Button
// ---------------------
// 1.5.0: added this button
if (!function_exists('bioship_skeleton_sidebar_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_sidebar_button', 2);

	function bioship_skeleton_sidebar_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for sidebars ---
		global $vthemesidebars;
		if (!$vthemesidebars['sidebar']) {return;}

		// --- button text translations ---
		// 2.1.1: added button anchor text translations
		$showsidebar = esc_attr(__('Show Sidebar','bioship'));
		$hidesidebar = esc_attr(__('Hide Sidebar','bioship'));
		$sidebartext = esc_attr(__('Sidebar','bioship'));

		// --- create sidebar buttons output ---
		// 2.1.3: prefix javascript functions
		$buttons = bioship_html_comment('#sidebarbutton', false);
		$buttons .= '<div id="sidebarbutton" class="mobilebutton">'.PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarshow" href="javascript:void(0);" onclick="bioship_showsidebar();">'.esc_attr($showsidebar).'</a>'.PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarhide" href="javascript:void(0);" onclick="bioship_hidesidebar();" style="display:none;">'.esc_attr($hidesidebar).'</a>'.PHP_EOL;
		$buttons .= '</div>'.PHP_EOL;
		$buttons .= '<div id="sidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarshowsmall" href="javascript:void(0);" onclick="bioship_showsidebar();">[+] '.esc_attr($sidebartext).'</a>'.PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarhidesmall" href="javascript:void(0);" onclick="bioship_hidesidebar();" style="display:none;">[-] '.esc_attr($sidebartext).'</a>'.PHP_EOL;
		$buttons .= '</div>'.PHP_EOL;
		$buttons .= bioship_html_comment('/#sidebarbutton', false);
		$buttons .= PHP_EOL;

		// --- filter and echo sidebar buttons ---
		$buttons = bioship_apply_filters('skeleton_sidebar_display_buttons', $buttons);
		echo $buttons; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------
// Sidebar Wrap Open
// -----------------
// 1.5.0: skeleton_sidebar_wrap to skeleton_sidebar_open
if (!function_exists('bioship_skeleton_sidebar_open'))  {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_sidebar', 'bioship_skeleton_sidebar_open', 5);

	function bioship_skeleton_sidebar_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemesidebars;

		// --- get sidebar column count ---
		// 1.9.0: use new theme layout global
		// TODO: WTF this method is just not working here ?
		// $sidebarcolumns = $vthemesidebars['sidebarcolumns'];
		// $sidebars = $vthemesidebars; unset($sidebars['output']);
		// bioship_debug("Sidebar Layout Check", $sidebars);
		$sidebarcolumns = bioship_set_sidebar_columns();

		// --- set sidebar column classes ---
		$classes = array($sidebarcolumns, 'columns');

		// --- add alpha / omega sidebar classes ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple numerical array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ($sidebars as $i => $sidebar) {
			if ( ($sidebar != '') && (substr($sidebar, 0, 3) != 'sub') ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ($i == 0) {$classes[] = 'alpha';}
				elseif ( ($i == 1) && ($sidebars[0] == '') ) {$classes[] = 'alpha';}
				elseif ( ($i == 2) && ($sidebars[3] == '') ) {$classes[] = 'omega';}
				elseif ($i == 3) {$classes[] = 'omega';}
			}
		}

		// --- filter sidebar classes ---
		// 1.8.0: added sidebar class array filter
		$sidebarclasses = bioship_apply_filters('skeleton_sidebar_classes', $classes);
		if (is_array($sidebarclasses)) {
			// 2.0.5: use simple array key index
			foreach ($sidebarclasses as $i => $class) {$sidebarclasses[$i] = trim($class);}
			$classes = $sidebarclasses;
		}
		$classstring = implode(' ', $classes);

		// --- output sidebar wrap open ---
		bioship_html_comment('#sidebar');
		echo '<div id="sidebar" class="'.esc_attr($classstring).'" role="complementary">'.PHP_EOL;
		bioship_html_comment('#sidebarpadding.inner');
		echo '	<div id="sidebarpadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// ------------------
// Sidebar Wrap Close
// ------------------
if (!function_exists('skeleton_sidebar_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_sidebar', 'bioship_skeleton_sidebar_close', 5);

	function bioship_skeleton_sidebar_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output sidebar wrap close ---
		echo PHP_EOL.'	</div>';
		bioship_html_comment('/#sidebarpadding.inner');
		echo PHP_EOL.'</div>'.PHP_EOL;
		bioship_html_comment('/#sidebar');
		echo PHP_EOL;
	}
}


// --------------------------
// === Subsidiary Sidebar ===
// --------------------------

// -----------------------------
// Add Body Class for SubSidebar
// -----------------------------
// 1.8.0: renamed from skeleton_subsidebar_position
// 1.8.0: removed theme options check to allow for sidebar overrides
// 1.9.9: added missing function_exists check
if (!function_exists('bioship_skeleton_subsidebar_position_class')) {

	add_filter('body_class', 'bioship_skeleton_subsidebar_position_class');

	function bioship_skeleton_subsidebar_position_class($classes) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// --- check for sidebars ---
		global $vthemesidebars;
		if (!$vthemesidebars['subsidebar']) {return $classes;}

		// --- set subsidebar position class ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ($sidebars as $i => $sidebar) {
			if ( ($sidebar != '') && (substr($sidebar, 0, 3) == 'sub') ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( ($i == 0) || ($i == 1) ) {$classes[] = 'subsidebar-left';}
				elseif ( ($i == 2) || ($i == 3) ) {$classes[] = 'subsidebar-right';}
			}
		}
		return $classes;
	}
}

// ------------------------
// Mobile SubSidebar Button
// ------------------------
// 1.5.0: added this button
if (!function_exists('bioship_skeleton_subsidebar_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_subsidebar_button', 6);

	function bioship_skeleton_subsidebar_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for subsidebar ---
		global $vthemesidebars;
		if (!$vthemesidebars['subsidebar']) {return;}

		// --- button text translations ---
		// 2.1.2: added button anchor text translations
		$showsidebar = esc_attr(__('Show SubBar','bioship'));
		$hidesidebar = esc_attr(__('Hide SubBar','bioship'));
		$sidebartext = esc_attr(__('SubBar','bioship'));

		// --- set subsidebar display button output ---
		// 2.1.3: prefix javascript functions
		$buttons = bioship_html_comment('#subsidebarbutton', false);
		$buttons .= '<div id="subsidebarbutton" class="mobilebutton">'.PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarshow" href="javascript:void(0);" onclick="bioship_showsubsidebar();">'.esc_attr($showsidebar).'</a>'.PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarhide" href="javascript:void(0);" onclick="bioship_hidesubsidebar();" style="display:none;">'.esc_attr($hidesidebar).'</a>'.PHP_EOL;
		$buttons .= '</div>'.PHP_EOL;
		$buttons .= '<div id="subsidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarshowsmall" href="javascript:void(0);" onclick="bioship_showsubsidebar();">[+] '.esc_attr($sidebartext).'</a>'.PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarhidesmall" href="javascript:void(0);" onclick="bioship_hidesubsidebar();" style="display:none;">[-] '.esc_attr($sidebartext).'</a>'.PHP_EOL;
		$buttons .= '</div>'.PHP_EOL;
		$buttons .= bioship_html_comment('/#subsidebarbutton', false);
		$buttons .= PHP_EOL;

		// --- filter and output subsidebar button ---
		$buttons = bioship_apply_filters('skeleton_subsidebar_display_buttons', $buttons);
		echo $buttons; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// --------------------
// SubSidebar Wrap Open
// --------------------
if (!function_exists('bioship_skeleton_subsidebar_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_subsidebar','skeleton_subsidebar_open',5);

	function bioship_skeleton_subsidebar_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global
		// ? WTF this method is just not working ?
		// $subsidebarcolumns = $vthemesidebars['subsidebarcolumns'];
		// $sidebars = $vthemesidebars; unset($sidebars['output']);
		// bioship_debug("Sidebar Layout Check", $sidebars);
		$subsidebarcolumns = bioship_set_subsidebar_columns();

		// --- set subsidebar column classes ---
		$classes = array($subsidebarcolumns, 'columns');

		// --- set alpha and omega subsidebar classes ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ($sidebars as $i => $sidebar) {
			if ( ($sidebar != '') && (substr($sidebar, 0, 3) == 'sub') ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ($i == 0) {$classes[] = 'alpha';}
				elseif ( ($i == 1) && ($sidebars[0] == '') ) {$classes[] = 'alpha';}
				elseif ( ($i == 2) && ($sidebars[3] == '') ) {$classes[] = 'omega';}
				elseif ($i == 3) {$classes[] = 'omega';}
			}
		}

		// --- filter subsidebar classes ----
 		// 1.8.0: added subsidebar class array filter
 		$subsidebarclasses = bioship_apply_filters('skeleton_subsidebar_classes', $classes);
		if (is_array($subsidebarclasses)) {
			// 2.0.5: use simple array key index
			foreach ($subsidebarclasses as $i => $class) {$subsidebarclasses[$i] = trim($class);}
			$classes = $subsidebarclasses;
		}
		$classstring = implode(' ', $classes);

		// --- output subsidebar wrap open ---
		bioship_html_comment('#subsidebar');
		echo '<div id="subsidebar" class="'.esc_attr($classstring).'" role="complementary">'.PHP_EOL;
		bioship_html_comment('#subsidebarpadding.inner');
		echo '	<div id="subsidebarpadding" class="inner">'.PHP_EOL;
	}
}

// ---------------------
// SubSidebar Wrap Close
// ---------------------
// 1.8.0: fix from skeleton_subsidebar_wrap_close
if (!function_exists('bioship_skeleton_subsidebar_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_subsidebar', 'bioship_skeleton_subsidebar_close', 5);

	function bioship_skeleton_subsidebar_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output subsidebar wrap close ---
		echo PHP_EOL.'	</div>';
		bioship_html_comment('#subsidebarpadding.inner');
		echo PHP_EOL.'</div>'.PHP_EOL;
		bioship_html_comment('/#subsidebar');
		echo PHP_EOL;
	}
}


// ---------------
// === Content ===
// ---------------

// ------------------------
// WooCommerce Wrapper Open
// ------------------------
// 1.8.0: add div wrapper to woocommerce content for ease of style targeting
// 2.0.5: added missing function_exists check
if (!function_exists('bioship_skeleton_woocommerce_wrapper_open')) {
 add_action('woocommerce_before_main_content', 'bioship_skeleton_woocommerce_wrapper_open');
 function bioship_skeleton_woocommerce_wrapper_open() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- output woocommerce wrapper open ---
	bioship_html_comment('#woocommercecontent');
	echo '<div id="woocommercecontent">'.PHP_EOL;
 }
}

// -------------------------
// WooCommerce Wrapper Close
// -------------------------
// 1.8.0: add div wrapper to woocommerce content for ease of style targeting
// 2.0.5: added missing function_exists check
if (!function_exists('bioship_skeleton_woocommerce_wrapper_close')) {
 add_action('woocommerce_after_main_content', 'bioship_skeleton_woocommerce_wrapper_close');
 function bioship_skeleton_woocommerce_wrapper_close() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- output woocommerce wrapper close ---
	echo '</div>'.PHP_EOL;
	bioship_html_comment('/#woocommercecontent');
	echo PHP_EOL;
 }
}

// -----------------
// Content Wrap Open
// -----------------
if (!function_exists('bioship_skeleton_content_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_content', 'bioship_skeleton_content_open', 10);

	function bioship_skeleton_content_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout, $vthemesidebars;

		// --- check for sidebars ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 1.9.0: use new themesidebars global
		$sidebars = $vthemesidebars['sidebars'];
		$leftsidebar = $rightsidebar = false;
		if ( ($sidebars[0] != '') || ($sidebars[1] != '') ) {$leftsidebar = true;}
		if ( ($sidebars[2] != '') || ($sidebars[3] != '') ) {$rightsidebar = true;}

		// --- set content columns class ---
		// 1.5.0: replaced skeleton_options call here
		// 1.8.0: add alpha/omega class depending on sidebar presence
		// 1.8.5: fix to double sidebar logic
		// 1.9.8: use themelayout global content columns
		// 2.0.7: added missing content classes filter
		$columns = $vthemelayout['contentcolumns'];
		$classes = array($columns, 'columns');

		// --- set alpha and omega classes ---
		if (!$leftsidebar && !$rightsidebar) {$classes[] = 'alpha'; $classes[] = 'omega';}
		elseif ($leftsidebar && !$rightsidebar) {$classes[] = 'omega';}
		elseif ($rightsidebar && !$leftsidebar) {$classes[] = 'alpha';}

		// --- filter content classes ---
		$classes = bioship_apply_filters('skeleton_content_classes', $classes);
		$classstring = implode(' ', $classes);

		// --- output #top id for scroll links ---
		// 2.1.4: add #content name for skip links
		echo '<a name="content"></a><a id="top" name="top"></a>'.PHP_EOL;

		// --- output content wrap open ---
		bioship_html_comment('#content');
		echo '<div id="content" class="'.esc_attr($classstring).'">'.PHP_EOL;
		bioship_html_comment('#contentpadding.inner');
		echo '	<div id="contentpadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// ------------------
// Content Wrap Close
// ------------------
if (!function_exists('bioship_skeleton_content_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_content', 'bioship_skeleton_content_close', 0);

    function bioship_skeleton_content_close() {
    	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

    	// --- output content wrap close ---
    	echo PHP_EOL.'	</div>'.PHP_EOL;
    	bioship_html_comment('/#contentpadding.inner');
    	echo '</div>'.PHP_EOL;
    	bioship_html_comment('/#content');
    	echo PHP_EOL;

    	// --- output #bottom id for scroll links ---
    	echo '<a id="bottom" name="bottom"></a>';
    }
}

// ----------------------------
// Home (Blog) Page Top Content
// ----------------------------
// 1.8.5: moved this here from loop-hybrid.php
if (!function_exists('bioship_skeleton_home_page_content')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_home_page_top', 'skeleton_home_page_content', 5);

	function bioship_skeleton_home_page_content() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- set defaults to off ---
		$title = $content = false;

		// --- check for blog page ---
		$pageid = get_option('page_for_posts');

		if ($pageid) {

			// --- get page title ---
			$title = get_the_title($pageid);

			// --- get the page content ---
			$post = get_page($pageid); setup_postdata($post);
			ob_start(); the_content(); $content = ob_get_contents(); ob_end_clean();
		}

		// --- output page title ---
		// 1.9.8: added new home page title filter
		// 2.1.1: moved outside of page check so filter is run in any case
		$title = bioship_apply_filters('skeleton_home_page_title', $title);
		if ($title) {
			// 2.1.1: changed ID from blogpagetitle to blogpage-title
			bioship_html_comment('#blogpage-title');
				echo '<h2 id="blogpage-title">'.esc_attr($title).'</h2>'.PHP_EOL;
			bioship_html_comment('/#blogpage-title');
			echo PHP_EOL;
		}

		// --- filter and output page content ---
		// 1.9.8: added new home page content filter
		// 2.1.1: moved outside of page check so filter is run in any case
		$content = bioship_apply_filters('skeleton_home_page_content', $content);
		if ($content) {
			// 2.1.1: changed ID from blogpagecontent to blogpage-content
			bioship_html_comment('#blogpage-content');
			echo '<div id="blogpage-content">'.PHP_EOL;
			echo '	'.$content.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#blogpage-content');
		}
	}
}

// -------------------------------
// Home (Blog) Page Bottom Content
// -------------------------------
// 2.1.1: added this so HTML can be added via filter
// TODO: add new filter to filters.php examples
if (!function_exists('bioship_skeleton_home_page_footnote')) {

	bioship_add_action('bioship_home_page_bottom', 'skeleton_home_page_footnote', 5);

	function bioship_skeleton_home_page_footnote() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- filter and output footnote content ---
		$content = bioship_apply_filters('skeleton_home_page_footnote', false);
		if ($content) {
			bioship_html_comment('#blogpage-footnote');
			echo '<div id="blogpage-content">'.PHP_EOL;
			echo '	'.$content.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#blogpage-footnote');
		}
	}
}

// ----------------------
// Front Page Top Content
// ----------------------
// 2.1.1: added this so HTML can be added via filters
// TODO: add new filters to filters.php examples
if (!function_exists('bioship_skeleton_front_page_content')) {

	bioship_add_action('bioship_front_page_top', 'skeleton_frontpage_content', 5);

	function bioship_skeleton_frontpage_content() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- set defaults to off ---
		$title = $content = false;

		// --- check for front page ---
		// TODO: get the corrent option here ? (this one is for the blog page)
		// $pageid = get_option('page_for_posts');
		$pageid = false;

		if ($pageid) {

			// --- get page title ---
			$title = get_the_title($pageid);

			// --- get the page content ---
			$post = get_page($pageid); setup_postdata($post);
			ob_start(); the_content(); $content = ob_get_contents(); ob_end_clean();
		}

		// --- filter and output page title ---
		$title = bioship_apply_filters('skeleton_front_page_title', $title);
		if ($title) {
			bioship_html_comment('#frontpage-title');
				echo '<h2 id="frontpage-title">'.esc_attr($title).'</h2>'.PHP_EOL;
			bioship_html_comment('/#frontpage-title');
			echo PHP_EOL;
		}

		// --- filter and output page content ---
		$content = bioship_apply_filters('skeleton_front_page_content', $content);
		if ($content) {
			bioship_html_comment('#frontpagecontent');
			echo '<div id="frontpage-content">'.PHP_EOL;
			echo '	'.$content.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#frontpage-content');
		}
	}
}

// -------------------------
// Front Page Bottom Content
// -------------------------
// 2.1.1: added this so HTML can be added via filter
// TODO: add new filter to filters.php examples
if (!function_exists('bioship_skeleton_home_page_footnote')) {

	bioship_add_action('bioship_front_page_bottom', 'skeleton_frontpage_footnote', 5);

	function bioship_skeleton_frontpage_footnote() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- filter and output footnote content ---
		$content = bioship_apply_filters('skeleton_front_page_footnote', false);
		if ($content) {
			bioship_html_comment('#frontpagecontent');
			echo '<div id="frontpage-footnote">'.PHP_EOL;
			echo '	'.$content.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#frontpage-footnote');
		}
	}
}

// ------------------
// Output the Excerpt
// ------------------
// 1.5.0: added for no reason but to make it overrideable
if (!function_exists('skeleton_echo_the_excerpt')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_the_excerpt', 'bioship_skeleton_echo_the_excerpt', 5);

	function bioship_skeleton_echo_the_excerpt() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output the excerpt ---
		the_excerpt();
	}
}

// --------------------------
// Ensure Content Not in Head
// --------------------------
// 2.0.5: fix to avoid a very weird bug (unknown plugin conflict?)
global $vthemehead; $vthemehead = false;
add_action('wp_head', 'bioship_head_finished', 999);
if (!function_exists('bioship_head_finished')) {
 function bioship_head_finished() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// --- flag wp_head output as finished ---
 	global $vthemehead; $vthemehead = true;
 	bioship_debug("Theme Head Output Finished");
 }
}

// ------------------
// Output the Content
// ------------------
// 1.5.0: added for no reason but to make it overrideable
if (!function_exists('bioship_skeleton_echo_the_content')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_the_content', 'bioship_skeleton_echo_the_content', 5);

	function bioship_skeleton_echo_the_content() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- for debugging content filters ---
		if (THEMEDEBUG) {
			global $wp_filter;
			// print_r(array_keys($wp_filter));
			// $debug = print_r($wp_filter['wp_head'], true);
			// $file = dirname(__FILE__).'/debug/filters.txt';
			// bioship_write_to_file($file, $debug);
			bioship_debug("Content Filters", $wp_filter['the_content']);
		}

		// --- output the content ---
		// 2.0.5: check head output finished to ensure content is not output in head
		global $vthemehead; if ($vthemehead) {the_content();}
	}
}

// -------------
// Media Handler
// -------------
// 1.8.0: media handler for attachments and post formats
if (!function_exists('bioship_skeleton_media_handler')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_media_handler', 'bioship_skeleton_media_handler', 5);

	function bioship_skeleton_media_handler() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// --- only for singular pages ---
		if (!is_singular()) {return;}

		// Attachments
		// -----------
		if (is_attachment()) {

			// --- check attachment mime type ---
			$mimetype = get_post_mime_type();
			if (strstr($mimetype, 'audio')) {$type = 'audio';}
			elseif (strstr($mimetype, 'video')) {$type = 'video';}
			elseif (strstr($mimetype, 'image')) {$type = 'image';}
			elseif (strstr($mimetype, 'text')) {$type = 'text';}
			elseif (strstr($mimetype, 'application')) {$type = 'application';}
			else {return;} // unrecognized

			// Display Attachment
			// ------------------
			bioship_html_comment('#attachment');
			echo '<div id="attachment">'.PHP_EOL;
			if ( ($type == 'audio') || ($type == 'video') || ($type == 'application') ) {
				if (!THEMEHYBRID && !function_exists('hybrid_attachment')) {bioship_load_hybrid_media();}
				hybrid_attachment();
			}
			if ($type == 'image') {
				if (has_excerpt()) { // image caption check
					$src = wp_get_attachment_image_src(get_the_ID(), 'full');
					echo img_caption_shortcode( array('align' => 'aligncenter', 'width' => esc_attr($src[1]), 'caption' => get_the_excerpt()), wp_get_attachment_image(get_the_ID(), 'full', false) );
				} else {
					echo wp_get_attachment_image( get_the_ID(), 'full', false, array('class' => 'aligncenter') );
				}
			}
			if ( ($type == 'text') || ($type == 'application') ) {
				$attachment = wp_get_attachment_metadata();
				$uploaddir = wp_upload_dir();
				$fileurl = trailingslashit($uploaddir['baseurl']).$attachment['file'];
				// 2.1.1: added missing translation wrapper
				$downloadtext = __('Download this Attachment','bioship');
				echo '<center><a href="'.esc_url($fileurl).'">'.esc_attr($downloadtext).'</a></center><br>'.PHP_EOL;
			}
			if ($type == 'text') {
				$filepath = trailingslashit($uploaddir['basedir']).$attachment['file'];
				echo '<div id="attachment-text"><textarea style="width:100%; height:500px;">'.PHP_EOL;
					echo bioship_file_get_contents($filepath);
				echo '</textarea></div><br>'.PHP_EOL;
			}
			echo PHP_EOL.'</div>'.PHP_EOL;
			bioship_html_comment('/#attachment');
			echo PHP_EOL;

			// Attachment Meta
			// ---------------
			bioship_html_comment('.attachment-meta');
			echo '<div class="attachment-meta">'.PHP_EOL;
			echo '	<div class="media-info '.esc_attr($type).'-info">'.PHP_EOL;
			echo '		<h4 class="attachment-meta-title">';
				if ($type == 'audio') {echo esc_attr(__('Audio Info','bioship'));}
				elseif ($type == 'video') {echo esc_attr(__('Video Info','bioship'));}
				elseif ($type == 'image') {echo esc_attr(__('Image Info','bioship'));}
				elseif ($type == 'text') {echo esc_attr(__('Text Info','bioship'));}
				elseif ($type == 'application') {echo esc_attr(__('Attachment Info','bioship'));}
			echo '</h4>'.PHP_EOL;

			// 2.1.4: added function_exists check for Hybrid function
			// 2.1.4: temporarily disabled as uncertain on usage here
			// if (function_exists('hybrid_media_meta')) {hybrid_media_meta();}

			echo PHP_EOL.'	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/.attachment-meta');
			echo PHP_EOL;

			// remove default WordPress attachment display (prepended to content)
			// TODO: recheck prepend_attachment filter for improving media handler ?
			remove_filter('the_content', 'prepend_attachment');
			return;
		}

		// Post Formats
		// ------------
		// (audio/video/image)

		if ($vthemesettings['postformatsupport'] != '1') {return;}

		// Audio Grabber
		// -------------
		if ( ($vthemesettings['postformats']['audio'] == '1') && has_post_format('audio')) {
			if (!THEMEHYBRID && !function_exists('hybrid_media_grabber')) {bioship_load_hybrid_media();}
			$audio = hybrid_media_grabber(array('type' => 'audio', 'split_media' => true));
			if ($audio) {
				echo '<div id="post-format-media" class="post-format-audio">'.PHP_EOL;
				echo '	'.$audio.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo '</div>'.PHP_EOL;
			}
		}

		// Video Grabber
		// -------------
		if ( ($vthemesettings['postformats']['video'] == '1') && has_post_format('video')) {
			if (!THEMEHYBRID && !function_exists('hybrid_media_grabber')) {bioship_load_hybrid_media();}
			$video = hybrid_media_grabber(array('type' => 'video', 'split_media' => true));
			if ($video) {
				echo '<div id="post-format-media" class="post-format-video">'.PHP_EOL;
				echo '	'.$video.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo '</div>'.PHP_EOL;
			}
		}

		// Image Grabber
		// -------------
		if ( ($vthemesettings['postformats']['image'] == '1') && has_post_format('image')) {
			if (!THEMEHYBRID && !function_exists('hybrid_media_grabber')) {bioship_load_hybrid_media();}
			$image = get_the_image(array( 'echo' => false, 'size' => 'full', 'split_content' => true, 'scan_raw' => true, 'scan' => true, 'order' => array( 'scan_raw', 'scan', 'featured', 'attachment' ) ) );
			if ($image) {
				echo '<div id="post-format-media" class="post-format-image">';
					echo '	'.$image.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo '</div>';

				// TODO: maybe display image sizes for image media handler ?
				// echo '<div class="entry-byline"><span class="image-sizes">';
				// printf(esc_attr(__( 'Sizes: %s', 'bioship')), hybrid_get_image_size_links() );
				// echo '</span></div>';

			}
		}

		// Gallery
		// -------
		// TODO: gallery post format display media handler ?
		// if ( ($vthemesettings['postformats']['gallery'] == '1') && (has_post_format('gallery')) ) {
			// $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'orderby' => 'rand', 'id' => get_queried_object()->post_parent, 'exclude' => get_the_ID() ) );
			// if ( !empty( $gallery ) ) {
			// 	echo '<div class="image-gallery">';
			// 	echo '<h3 class="attachment-meta-title">'.esc_attr(__('Gallery', 'bioship')).'</h3>';
			// 	echo $gallery; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			// 	echo '</div>';
			// }
		// }

		// Status
		// ------
		// TODO: show Author Gravatar for Status post format ?
		// if (get_option('show_avatars')) {
		// 	echo '<header class="entry-header">'.get_avatar(get_the_author_meta('email'));.'</header>';
		// ]

	}
}


// --------------------
// === Content Meta ===
// --------------------

// ------------
// Entry Header
// ------------

// Entry Header Hooks
// ------------------
// bioship_skeleton_entry_header_open:		 0
// bioship_skeleton_entry_header_title:		 2
// bioship_skeleton_entry_header_subtitle: 	 4
// bioship_skeleton_entry_header_meta:		 6
// bioship_skeleton_entry_header_close: 	10

// ----------------------
// Entry Header Wrap Open
// ----------------------
if (!function_exists('bioship_skeleton_entry_header_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_open', 0);

	function bioship_skeleton_entry_header_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- entry header wrap open ---
		bioship_html_comment('.entry-header');
		$attributes = hybrid_get_attr('entry-header');
		echo '<header '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------------
// Entry Header Wrap Close
// -----------------------
if (!function_exists('bioship_skeleton_entry_header_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_close', 10);

	function bioship_skeleton_entry_header_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- entry header wrap close ---
		echo PHP_EOL.'</header>'.PHP_EOL;
		bioship_html_comment('/.entry-header');
		echo PHP_EOL;
	}
}

// ------------------
// Entry Header Title
// ------------------
if (!function_exists('bioship_skeleton_entry_header_title')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_title', 2);

	function bioship_skeleton_entry_header_title() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get post values ---
		global $post; $postid = $post->ID; $posttype = $post->post_type;

		// --- set heading size ---
		// 1.5.0: use h3 instead of h2 for archive/excerpt listings
		if (is_archive() || is_search() || (!is_singular($posttype)) ) {$hsize = 'h3';} else {$hsize = 'h2';}

		// --- output the entry title ---
		$permalink = get_the_permalink($postid);
		bioship_html_comment('.entry-title');
		$attributes = hybrid_get_attr('entry-title');
		echo '<'.$hsize.' '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		// TODO: optimize this tag output ?
		echo '	<a href="'.esc_url($permalink).'" rel="bookmark" itemprop="url" title="';
			// translaters: replacement string is the post title
			printf(esc_attr(__('Permalink to %s','bioship')), the_title_attribute('echo=0'));
		echo '">'.esc_attr(get_the_title($postid)).'</a>'.PHP_EOL;
		echo '</'.$hsize.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		bioship_html_comment('/.entry-title');
		echo PHP_EOL;
	}
}

// ---------------------
// Entry Header Subtitle
// ---------------------
// Uses WP Subtitle plugin (still shows saved subtitle if plugin is deactivated)
if (!function_exists('bioship_skeleton_entry_header_subtitle')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_subtitle', 4);

	function bioship_skeleton_entry_header_subtitle() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get post values ---
		global $post; $postid = $post->ID; $posttype = $post->post_type;

		// --- get the subtitle post metakey ---
		// 1.5.0: moved key filter here before WP subtitle check
		$subtitlekey = 'wps_subtitle'; // see filters.php example
		$subtitlekey = bioship_apply_filters('skeleton_subtitle_key', $subtitlekey);

		// --- get the subtitle ---
		if ( (function_exists('get_the_subtitle')) && ($subtitlekey == 'wps_subtitle') ) {
			$subtitle = get_the_subtitle($postid, '', '', false);
		} else {$subtitle = get_post_meta($postid, $subtitlekey, true);}

		if ($subtitle != '') {

			// --- set heading size ---
			// 1.5.0: use h4 instead of h3 for archive/excerpt listings
			if (is_archive() || is_search() || (!is_singular($posttype)) ) {$hsize = 'h4';} else {$hsize = 'h3';}

			// --- output the subtitle ---
			// note: there are no default hybrid attributes for entry-subtitle
			bioship_html_comment('.entry-subtitle');
			$attributes = hybrid_get_attr('entry-subtitle');
			echo '<'.$hsize.' '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped
			echo '	'.esc_attr($subtitle).PHP_EOL;
			echo '</'.$hsize.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped
			bioship_html_comment('/.entry-subtitle');
			echo PHP_EOL;
		}
	}
}

// -----------------
// Entry Header Meta
// -----------------
if (!function_exists('bioship_skeleton_entry_header_meta')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_meta', 6);

	function bioship_skeleton_entry_header_meta() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get post values ---
		global $post; $postid = $post->ID; $posttype = $post->post_type;

		// --- output entry meta top ---
		$meta = bioship_get_entry_meta($postid, $posttype, 'top');
		if ($meta != '') {
			bioship_html_comment('.entry-meta');
			echo '<div class="entry-meta entry-byline">'.PHP_EOL;
			echo '	'.$meta.PHP_EOL; // // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/.entry-meta');
			echo PHP_EOL;
		}
	}
}

// ------------
// Entry Footer
// ------------

// Entry Footer Action Hooks
// -------------------------
// bioship_skeleton_entry_footer_open: 	 0
// bioship_skeleton_entry_footer_meta: 	 5
// bioship_skeleton_entry_footer_close: 10

// ----------------------
// Entry Footer Wrap Open
// ----------------------
if (!function_exists('bioship_skeleton_entry_footer_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_open', 0);

	function bioship_skeleton_entry_footer_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output entry footer wrap open ---
		bioship_html_comment('.entry-footer');
		$attributes = hybrid_get_attr('entry-footer');
		echo '<footer '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------------
// Entry Footer Wrap Close
// -----------------------
if (!function_exists('bioship_skeleton_entry_footer_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_close', 10);

	function bioship_skeleton_entry_footer_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output entry footer wrap close ---
		echo PHP_EOL.'</footer>';
		bioship_html_comment('/.entry-footer');
		echo PHP_EOL;
	}
}

// -----------------
// Entry Footer Meta
// -----------------
if (!function_exists('bioship_skeleton_entry_footer_meta')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_meta', 6);

	function bioship_skeleton_entry_footer_meta() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get post values ---
		global $post; $postid = $post->ID; $posttype = get_post_type();

		// --- output entry meta bottom ---
		$meta = bioship_get_entry_meta($postid, $posttype, 'bottom');
		if ($meta != '') {
			$attributes = hybrid_get_attr('entry-utility');
			bioship_html_comment('.entry-utility');
			echo '<div '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '	'.$meta.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/.entry-utility');
			echo PHP_EOL;
		}
	}
}


// ------------------
// === Thumbnails ===
// ------------------

// ---------------------
// Echo Thumbnail Action
// ---------------------
if (!function_exists('bioship_skeleton_echo_thumbnail')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_thumbnail', 'bioship_skeleton_echo_thumbnail', 5);

	function bioship_skeleton_echo_thumbnail() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// --- check for media handler format ---
		// 1.8.0: bug out for image post format media
		if ($vthemesettings['postformatsupport'] == '1') {
			if (has_post_format('image')) {return;} // displayed by media handler
		}

		// --- check current post number ---
		// 1.8.5: allow for custom query/loop numbering override
		global $wp_query; $postnumber = '';
		if (isset($wp_query->current_post)) {$postnumber = $wp_query->current_post + 1;}
		$postnumber = bioship_apply_filters('skeleton_loop_post_number', $postnumber);

		// --- get post values ---
		// 1.5.0: improved thumbnail function
		global $post; $postid = $post->ID; $posttype = get_post_type();
		$thumbnail = bioship_skeleton_get_thumbnail($postid, $posttype, $postnumber);

		// --- output thumbnail content ---
		if ($thumbnail != '') {
			bioship_do_action('bioship_before_thumbnail');
				echo $thumbnail; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			bioship_do_action('bioship_after_thumbnail');
		}
	}
}

// ---------------------------
// Get Thumbnail for Templates
// ---------------------------
// 1.5.0: moved here as separate (from content template)
if (!function_exists('bioship_skeleton_get_thumbnail')) {
	function bioship_skeleton_get_thumbnail($postid, $posttype, $postnumber, $thumbsize='') {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings;

		// --- set default values ---
		$thumbnail = $method = '';
		$wrapperclasses = 'thumbnail thumbnail-'.$postnumber;

		// --- check for thumbnail image and get sizes ---
		if (is_archive() || is_search() || (!is_singular($posttype)) ) {

			// --- get list thumbnail size and alignment ---
			if ($thumbsize == '') {$thumbsize = $vthemesettings['listthumbsize'];}
			$thumbsize = bioship_apply_filters('skeleton_list_thumbnail_size', $thumbsize);
			$thumblistalign = bioship_apply_filters('skeleton_list_thumbnail_align', $vthemesettings['thumblistalign']);
			if ( ($thumblistalign != 'none') && ($thumblistalign != '') ) {
				if ($thumblistalign == 'alternateleftright') {
					if ($postnumber & 1) {$align = 'alignleft';} else {$align = 'alignright';}
				} elseif ($thumblistalign == 'alternaterightleft') {
					if ($postnumber & 1) {$align = 'alignright';} else {$align = 'alignleft';}
				} else {$align = $thumblistalign;}
			}
			$wrapperclasses .= ' '.$align;
			$thumbclasses = 'scale-with-grid thumbtype-'.$posttype;

		} else {

			// --- set singular thumbnail size ---
			if ($posttype == 'page') {$thumbsize = $vthemesettings['pagethumbsize'];}
			else {$thumbsize = $vthemesettings['postthumbsize'];}
			// 2.1.1: added post ID argument for post-specific filtering
			$thumbsize = bioship_apply_filters('skeleton_post_thumbnail_size', $thumbsize, $postid);

			// --- for custom post type filtering switch to attachment method ---
			// 2.0.5: test for actual change not just with has_filter
			$newthumbsize = bioship_apply_filters('muscle_post_thumb_size_'.$posttype, $thumbsize);
			if ($newthumbsize != $thumbsize) {
				// custom size overrides are set to 'post-thumbnail' type
				$method = 'attachment'; $thumbsize = 'post-thumbnail';
				$thumbclasses .= ' attachment-'.$thumbsize;
			}

			// --- allow for perpost meta override ---
			// 1.8.5: fix to perpost image display override check
			// 1.9.5: move override to after default and filters applied
			// 2.0.8: use prefixed post meta key
			// 2.1.1: revert to unprefixed post meta key
			$postthumbsize = get_post_meta($postid, '_post_thumbsize', true);

			// --- fix for unprefixed size names ---
			// 2.0.5: auto-update post meta to new size names
			$newthumbsize = false;
			if ($postthumbsize == 'squared150') {$newthumbsize = 'bioship-150s';}
			elseif ($postthumbsize == 'squared250') {$newthumbsize = 'bioship-250s';}
			elseif ($postthumbsize == 'video43') {$newthumbsize = 'bioship-4-3';}
			elseif ($postthumbsize == 'video169') {$newthumbsize = 'bioship-16-9';}
			elseif ($postthumbsize == 'opengraph') {$newthumbsize = 'bioship-opengraph';}
			if ($newthumbsize) {
				update_post_meta($postid, '_postthumbsize', $newthumbsize);
				$postthumbsize = $newthumbsize;
			}
			if ($postthumbsize != '') {$thumbsize = $postthumbsize;}

			// --- set thumbnail alignment and classes ---
			if ($posttype == 'page') {$thumbalign = $vthemesettings['featuredalign'];}
			else {$thumbalign = $vthemesettings['thumbnailalign'];}
			$thumbalign = bioship_apply_filters('skeleton_post_thumbnail_align', $thumbalign);
			if ( ($thumbalign != 'none') && ($thumbalign != '') ) {$wrapperclasses .= ' '.$thumbalign;}
			$thumbclasses = 'scale-with-grid thumbtype-'.$posttype;
		}

		// --- maybe get the thumbnail image ---
		if ($thumbsize != 'off') {

			// --- set and filter wrapper and thumbnail classes ---
			if ($posttype == 'page') {$wrapperclasses .= ' featured-image';} else {$wrapperclasses .= ' post-thumbnail';}
			$wrapperclasses = bioship_apply_filters('skeleton_thumbnail_wrapper_classes', $wrapperclasses);
			$thumbclasses = bioship_apply_filters('skeleton_thumbnail_classes', $thumbclasses);

			// 2.0.5: convert old size names to new prefixed ones
			if ($thumbsize == 'squared150') {$thumbsize = 'bioship-150s';}
			if ($thumbsize == 'squared250') {$thumbsize = 'bioship-250s';}
			if ($thumbsize == 'video43') {$thumbsize = 'bioship-4-3';}
			if ($thumbsize == 'video169') {$thumbsize = 'bioship-16-9';}
			if ($thumbsize == 'opengraph') {$thumbsize = 'bioship-opengraph';}

			// 2.0.5: maybe auto-regenerate thumbnails (in case of a new size)
			bioship_regenerate_thumbnails($postid, $thumbsize);

			// --- output thumbnail ---
			$thumbnail = bioship_html_comment('.thumbnail'.$postnumber, false);
			$thumbnail .= '<div id="postimage-'.esc_attr($postid).'" class="'.esc_attr($wrapperclasses).'">'.PHP_EOL;
			// use Hybrid get_the_image extension with fallback to skeleton_thumbnailer
			if (THEMEHYBRID && ($vthemesettings['hybridthumbnails'] == '1')) {
				$args = array('post_id' => $postid, 'size' => $thumbsize, 'image_class' => $thumbclasses, 'echo' => false);
				if ($method == 'attachment') {$args['method'] = 'attachment';}
				$thumbnail .= get_the_image($args);
			} else {$thumbnail .= bioship_skeleton_thumbnailer($postid, $thumbsize, $thumbclasses, $method);}
			$thumbnail .= PHP_EOL.'</div>'.PHP_EOL;
			$thumbnail = bioship_html_comment('/.thumbnail'.$postnumber, false);
			$thumbnail .= PHP_EOL;
		}

		bioship_debug("Thumbnail Size", $thumbsize);
		$thumbnail = bioship_apply_filters('skeleton_thumbnail_override', $thumbnail);
		return $thumbnail;
	}
}

// --------------------
// Skeleton Thumbnailer
// --------------------
// 1.3.0: no longer a Skeleton content filter
// 1.5.0: changed to more general classes and added method
if (!function_exists('bioship_skeleton_thumbnailer')) {
	function bioship_skeleton_thumbnailer($postid, $thumbsize, $thumbclasses, $method='') {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		if (has_post_thumbnail($postid)) {
			// 1.5.0: added attachment method support for custom post types
			if ($method == 'attachment') {

				// --- get post thumbnail attachment ID ---
				$attachmentid = get_post_thumbnail_id($postid);

				if ($attachmentid) {
					// --- get the attachment image with alt attributes ---
					// (via wp_get_attachment_image codex example)
					$attributes = array(
						'class'	=> $thumbclasses,
						'alt'   => trim(strip_tags(get_post_meta($attachmentid, '_wp_attachment_image_alt', true)))
					);
					$image = wp_get_attachment_image($attachmentid, $thumbsize, false, $attributes);
					return $image;
				}
			}

			// --- fallback to default thumbnail method ---
			// 2.0.5: simplified fallback
			$image = get_the_post_thumbnail($postid, $thumbsize, array('class' => $thumbclasses));
			return $image;
		}
	}
}


// ------------------
// === Author Bio ===
// ------------------

// ----------------------
// Echo Author Bio Action
// ----------------------
// 1.9.8: abstracted for bottom and top
if (!function_exists('bioship_skeleton_echo_author_bio')) {
	function bioship_skeleton_echo_author_bio($position) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// --- check author bio context ---
		// 2.0.5: undefined variable warning fix
		$authorbio = false;
		if (is_author()) {$authorbio = bioship_skeleton_author_bio_box('archive', 'archive', $position);}
		elseif (is_singular()) {
			global $post; $postid = $post->ID; $posttype = $post->post_type;
			$authorbio = bioship_skeleton_author_bio_box($postid, $posttype, $position);
		}

		// --- output author bio ---
		if ($authorbio) {
			bioship_do_action('bioship_before_author_bio');
				bioship_locate_template(array('content/author-bio.php'), true);
			bioship_do_action('bioship_after_author_bio');
		}
	}
}

// ---------------------
// Echo Author Bio (Top)
// ---------------------
// 1.9.8: abstracted call for top and bottom
if (!function_exists('bioship_skeleton_echo_author_bio_top')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_author_bio_top', 'bioship_skeleton_echo_author_bio_top', 5);

	// 1.9.0: add author bio to author archive top ?
	// bioship_add_action('bioship_before_author', 'bioship_skeleton_echo_author_bio_top', 5);

	function bioship_skeleton_echo_author_bio_top() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- author bio for top position ---
		bioship_skeleton_echo_author_bio('top');
	}
}

// ------------------------
// Echo Author Bio (Bottom)
// ------------------------
// 1.9.8: abstracted call
if (!function_exists('bioship_skeleton_echo_author_bio_bottom')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_author_bio_bottom', 'bioship_skeleton_echo_author_bio_bottom', 5);

	// 1.9.0: add author bio to author archive page bottom ?
	// bioship_add_action('bioship_after_author', 'bioship_skeleton_echo_author_bio_bottom', 5);

	function bioship_skeleton_echo_author_bio_bottom() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- author bio for bottom position ---
		bioship_skeleton_echo_author_bio('bottom');
	}
}

// --------------
// Author Bio Box
// --------------
// 1.5.0: separated from content template
// if author has a description, show a bio on their entries
if (!function_exists('bioship_skeleton_author_bio_box')) {
	function bioship_skeleton_author_bio_box($postid, $posttype, $position) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings;

		// --- check for author bio description ---
		if (!get_the_author_meta('description')) {return false;}

		if ( ($postid == 'archive') && ($posttype == 'archive') ) {

			// TODO: check/add author bio position for archives ?

			return false;

		} else {

			// --- check whether global show is on and filter ---
			// 1.8.0: fix to showbox filter variable
			$showbox = $vthemesettings['authorbiocpts'][$posttype];
			$showbox = bioship_apply_filters('skeleton_author_bio_box', $showbox);
			if (!$showbox) {return false;}

			// --- check default position and filter ---
			$biopos = $vthemesettings['authorbiopos'];
			$biopos = bioship_apply_filters('skeleton_author_bio_box_position', $biopos);
			if ( ($position == 'top') && (!strstr($biopos, 'top')) ) {return false;}
			if ( ($position == 'bottom') && (!strstr($biopos, 'bottom')) ) {return false;}

			// 1.9.9: removed old meta check
			return true;
		}
	}
}

// -----------------------
// About Author Title Text
// -----------------------
// 1.5.0: moved from author-bio.php
if (!function_exists('bioship_skeleton_about_author_title')) {
	function bioship_skeleton_about_author_title() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get author display name ---
		// 1.8.0: use separately to get author display name
		// 2.0.5: fix to typo (vauthordosplay) :-/
		// 2.0.8: fix for non-singular display usage
		if (is_singular()) {
			global $post;
			$authordisplay = bioship_get_author_display_by_post($post->ID);
			// translators: replacement string is author name
			$boxtitle = esc_attr(sprintf( esc_attr(__('About %s', 'bioship')), $authordisplay));
		} else {$boxtitle = esc_attr(__('About the Author','bioship'));}

	 	// --- apply filters and return ---
	 	// 2.0.5: fix to fatal function typo (apply_filter)
		$boxtitle = bioship_apply_filters('skeleton_about_author_text', $vboxtitle);
		return $boxtitle; // .meta-prep-author ?
	}
}

// ------------------------
// About Author Description
// ------------------------
// 2.0.5: separated to add filter
// 2.0.8: fix to incorrect function prefix (missing _skeleton)
if (!function_exists('bioship_skeleton_about_author_description')) {
	function bioship_skeleton_about_author_description() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get author ---
		// 2.0.8: fix to get description outside the content loop
		// 2.0.8: fix to singular post check for post object
		$postid = false;
		if (is_singular()) {global $post; $postid = $post->ID;}
		$author = bioship_get_author_by_post($postid);
		if (!$author) {return;}

		// --- get author description ---
		$authordesc = get_the_author_meta('description', $vauthor->ID);

		// --- filter and return ---
		$authordesc = bioship_apply_filters('skeleton_about_author_description', $authordesc);
		return $authordesc;
	}
}

// -----------------
// Author Posts Text
// -----------------
// 1.5.0: moved from author-bio.php
// 1.8.0: fix for missing author URL
if (!function_exists('bioship_skeleton_author_posts_link')) {
	function bioship_skeleton_author_posts_link($authorurl=false) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		$postid = $posttype = false;
		if (is_singular()) {global $post; $posttype = $post->post_type; $postid = $post->ID;}

		// --- get author ---
		// 2.0.8: fix for possible missing author URL (author-bio.php template)
		$author = bioship_get_author_by_post($postid);
		if (!$author) {return;}
		if (!$authorurl) {$authorurl = get_author_posts_url($author->ID);}

		// --- get post type display name ---
		// 1.5.0: use post type display name
		// 2.0.8: fix for possible undefined variable
		$posttypedisplay = false;
		if ($posttype == 'page') {$posttypedisplay = esc_attr(__('Pages','bioship'));}
		elseif ($posttype == 'post') {$posttypedisplay = esc_attr(__('Posts','bioship'));}
		elseif ($posttype) {
			// 1.8.0: use the plural name not the singular one
			// $posttypedisplay = $posttypeobject->labels->singular_name;
			$posttypeobject = get_post_type_object($posttype);
			$posttypedisplay = $posttypeobject->labels->name;
		} else {$posttypedisplay = esc_attr(__('Writings','bioship'));}
		$posttypedisplay = bioship_apply_filters('skeleton_post_type_display', $posttypedisplay);
		// 2.0.8: bug out if unable to get valid post type display label
		if (!$posttypedisplay) {return false;}

		// --- get author display name ---
		// 1.8.0: use separately to get author display name
		// 2.0.8: bug out if unable to get valid author display name
		$authordisplay = bioship_get_author_display($author);
		if (!$authordisplay) {return false;}

		// --- set anchor text ---
		// 1.5.5: fix to translations here for theme check
		$anchor = sprintf( esc_attr(__('View all','bioship')).' '.$posttypedisplay.' '.__('by','bioship').' %s <span class="meta-nav">&rarr;</span>', $authordisplay);
		$anchor = bioship_apply_filters('skeleton_author_posts_anchor', $anchor);
		if (!$anchor) {return false;}

		// --- set author link ---
		// 1.8.5: class attribute override fix
		$attributes = hybrid_get_attr('entry-author', '', array('class' => 'author vcard entry-author'));
		$authorlink = '<span '.$attributes.'>'.PHP_EOL;
		$authorlink .= '	<a class="url fn n" href="'.esc_url($authorurl).'">'.$anchor.'</a>'.PHP_EOL;
		$authorlink .= '</span>'.PHP_EOL;

		// --- filter and return ---
		// 2.0.8: added override filter for author link HTML
		$authorlink = bioship_apply_filters('skeleton_author_link_html', $authorlink);
		return $authorlink;
	}
}


// ----------------
// === Comments ===
// ----------------

// --------------------
// Echo Comments Action
// --------------------
if (!function_exists('bioship_skeleton_echo_comments')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_comments', 'bioship_skeleton_echo_comments', 5);

	function bioship_skeleton_echo_comments() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- load comments template ---
		// note: comments template filter is located in functions.php
		// 1.5.0: Loads the comments template (default /comments.php)
		if (have_comments() || comments_open()) {comments_template('/comments.php', true);}
		else {
			// note: default to NOT say (irrelevently) that "comments are closed"
			$commentsclosed = bioship_apply_filters('skeleton_comments_closed_text', '');
			bioship_html_comment('.commentclosed');
			echo '<p class="commentsclosed">'.PHP_EOL;
				echo $commentsclosed.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</p>'.PHP_EOL;
			bioship_html_comment('/.commentsclosed');
		}
	}
}

// --------------------------
// Skeleton Comments Callback
// --------------------------
// wp_list_comments callback called in comments.php
if (!function_exists('bioship_skeleton_comments')) {
	function bioship_skeleton_comments($comment, $args, $depth) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesettings; $GLOBALS['comment'] = $comment;

		// --- maybe set comment buttons class ---
		// 1.8.5: added comment edit/reply link buttons option
		if (isset($vthemesettings['commentbuttons']) && ($vthemesettings['commentbuttons'] == '1')) {
			$commentbuttons = ' button';
		} else {$commentbuttons = '';}

		// --- filter comment avatar size
		$avatarsize = bioship_apply_filters('skeleton_comments_avatar_size', 48);

		// --- output comment ---
		// TODO: optimize comments callback template ?
		bioship_html_comment('li');

		// TODO: maybe use Hybrid comment attributes ?
		// echo '<li '.hybrid_get_attr('comment').'>'.PHP_EOL;
		echo '<li '.get_comment_class().' id="li-comment-'.get_comment_ID().'">'.PHP_EOL;

		echo '<div id="comment-'.get_comment_ID().'" class="single-comment clearfix">';
			echo '<div class="comment-author vcard">'.get_avatar($comment, $avatarsize).'</div>';
			echo '<div class="comment-meta commentmetadata">';
				if ($comment->comment_approved == '0') {
					echo '<em>'.esc_attr(__('Comment is awaiting moderation','bioship')).'</em> <br />';
				}
				// 1.8.5: added 'on' and 'at' to string
				echo '<span class="comment-author-meta">'.esc_attr(__('by','bioship')).' '.get_comment_author_link().'</span>';
				echo '<br><span class="comment-time">'.esc_attr(__('on','bioship')).' '.get_comment_date().'  '.esc_attr(__('at','bioship')).' '.get_comment_time().'</span>';
			echo '</div>';
			echo '<div class="comment-edit'.esc_attr($commentbuttons).'">';
				edit_comment_link(esc_attr(__('Edit','bioship')),' ',' ');
			echo '</div>';
			echo '<div class="comment-reply'.esc_attr($commentbuttons).'">';
				comment_reply_link(array_merge( $args, array('reply_text' => esc_attr(__('Reply','bioship')), 'login_text' => esc_attr(__('Login to Comment','bioship')), 'depth' => $depth, 'max_depth' => $args['max_depth'])));
			echo '</div>';
			echo '<div class="clear"></div>';
			echo '<div class="comment-text">';
				comment_text();
			echo '</div>';
		echo PHP_EOL.'</div>'.PHP_EOL;
		bioship_html_comment('/li');
		echo PHP_EOL;

	}
}

// ---------------------
// Comments Popup Script
// ---------------------
if (!function_exists('bioship_skeleton_comments_popup_script')) {

	add_action('wp_footer', 'bioship_skeleton_comments_popup_script', 11);

	function bioship_skeleton_comments_popup_script() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 1.9.9: added check for theme comments popup being used
		global $vthemecommentspopup;
		if (!isset($vthemecommentspopup) || !$vthemecommentspopup) {return;}

		// 1.9.9: only check comments_open on singular pages
		if (is_archive() || (is_singular() && comments_open()) ) {
			// 1.8.5: changed default from 500x500 to 640x480
			$popupsize = bioship_apply_filters('skeleton_comments_popup_size', array(640,480));
			// 1.8.0: added these checks to bypass possible filter errors
			if ( (!is_array($popupsize)) || (count($popupsize) != 2) ) {$popupsize = array(640,480);}
			if ( (!is_numeric($popupsize[0])) || (!is_numeric($popupsize[1])) ) {$popupsize = array(640,480);}

			// TODO: maybe replace this as deprecated since WP 4.5+ with "no alternative available" ?
			@comments_popup_script($popupsize);
		}
	}
}


// -------------------
// === Breadcrumbs ===
// -------------------

// ------------------
// Output Breadcrumbs
// ------------------
// 1.8.5: added Hybrid Breadcrumbs
if (!function_exists('bioship_skeleton_breadcrumbs')) {
	function bioship_skeleton_breadcrumbs() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (is_front_page()) {return;} // no breadcrumbs on front page!

		global $vthemesettings;

		$cpts = array(); $display = false; $breadcrumbs = '';
		// TODO: maybe check existing page context for breadcrumbs ?

		if (is_singular()) {

			$posttype = get_post_type();
			if (isset($vthemesettings['breadcrumbposttypes'])) {$cpts = $vthemesettings['breadcrumbposttypes'];}
			$cpts = bioship_apply_filters('skeleton_breadcrumb_post_types', $cpts);
			if (!is_array($cpts) || (count($cpts) == 0)) {return;}
			bioship_debug("Breadcrumbs for Single Post Types", $cpts);
			foreach ($cpts as $cpt => $value) {
				if ( ($cpt == $posttype) && ($value == '1') ) {$display = true;}
			}

		} elseif (is_archive()) {

			$posttypes = bioship_get_post_types();
			if (!is_array($posttypes)) {$posttypes = array($posttypes);}
			if (isset($vthemesettings['breadcrumbarchivetypes'])) {$cpts = $vthemesettings['breadcrumbarchivetypes'];}
			$cpts = bioship_apply_filters('skeleton_breadcrumb_archive_types', $cpts);
			if (!is_array($cpts) || (count($cpts) == 0) ) {return;}
			foreach ($cpts as $cpt => $value) {
				if (($value == '1') && in_array($cpt, $posttypes)) {$display = true;}
			}
			bioship_debug("Breadcrumbs for Archive Post Types", $cpts);

		}

		// TODO: check display options for more breadcrumb contexts ?
		// elseif (is_author()) {$display = true;}
		// elseif (is_search()) {$display = true;}
		// elseif (is_404()) {$display = true;}
		// elseif (is_home()) {$display = true;}

		if ($display) {
			if ($vthemesettings['hybridbreadcrumbs'] == '1') {
				// --- use Hybrid Breadcrumb Trail extension ---
				if (function_exists('breadcrumb_trail')) {$breadcrumbs = breadcrumb_trail();}
			} else {
				// TODO: add a fallback breadcrumb method if not using Hybrid ?
				$breadcrumbs = '';
			}
		}

		// --- filter and output breadcrumbs ---
		$breadcrumbs = bioship_apply_filters('skeleton_breadcrumb_override', $breadcrumbs);
		if ($breadcrumbs != '') {
			bioship_html_comment('#breadcrumb');
			echo '<div id="breadcrumb" class="'.esc_attr($posttype).'-breadcrumb">'.PHP_EOL;
			echo '	'.$breadcrumbs.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#breadcrumb');
			echo PHP_EOL;
		}
	}
}

// -----------------
// Check Breadcrumbs
// -----------------
// 1.8.5: added this check to hook breadcrumbs to singular/archive templates
// 1.9.8: move this check to very top so can be moved higher than before_loop
if (!function_exists('bioship_skeleton_check_breadcrumbs')) {

	add_action('wp', 'bioship_skeleton_check_breadcrumbs');

	function bioship_skeleton_check_breadcrumbs() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- add breadcrumbs to position ---
		// 1.9.8: use new position filtered add_action method
		if (is_singular()) {bioship_add_action('bioship_before_singular', 'bioship_skeleton_breadcrumbs', 5);}
		else {bioship_add_action('bioship_before_loop', 'bioship_skeleton_breadcrumbs', 5);}
	}
}


// -----------------------
// === Page Navigation ===
// -----------------------

// ----------------------
// Output Page Navigation
// ----------------------
// (with WP Pagenavi plugin support)
if (!function_exists('bioship_skeleton_page_navigation')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_page_navi', 'bioship_skeleton_page_navigation', 5);

	function bioship_skeleton_page_navigation() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// print_r($vthemesettings['pagenavposttypes']); // debug point
		// print_r($vthemesettings['pagenavarchivetypes']); // debug point

		// 1.5.0: filter whether to display page navigation for post / archive
		$display = false; // default to not display then check
		$archive = false; // add switch for post type archives
		if (is_singular()) {

			// --- check post type ---
			// 1.8.0: simplified to get post type
			$cpts = array();
			if (isset($vthemesettings['pagenavposttypes']) && is_array($vthemesettings['pagenavposttypes'])) {
				foreach ($vthemesettings['pagenavposttypes'] as $cpt => $value) {
					if ($value == '1') {$cpts[] = $cpt;}
				}
			}
			$cpts = bioship_apply_filters('skeleton_pagenavi_post_types', $cpts);
			if (is_array($cpts) && (count($cpts) > 0)) {
				$posttype = get_post_type();
				if (in_array($posttype, $cpts)) {$display = true;}
			}

		} elseif (is_archive()) {

			$archive = true;

			// --- check post types ---
			// 1.8.5: use new get post type helper
			$cpts = array();
			if (isset($vthemesettings['pagenavarchivetypes']) && is_array($vthemesettings['pagenavarchivetypes'])) {
				foreach ($vthemesettings['pagenavarchivetypes'] as $cpt => $value) {
					if ($value == '1') {$cpts[] = $cpt;}
				}
			}
			$cpts = bioship_apply_filters('skeleton_pagenavi_archive_types', $cpts);
			if (is_array($cpts) && (count($cpts) > 0) ) {
				$posttypes = bioship_get_post_types();
				if (!is_array($posttypes)) {$posttypes = array($posttypes);}
				if (array_intersect($cpts, $posttypes)) {
					$display = true; $posttype = $posttypes[0]; // for labels...
				}
			}

		} else {

			// --- check for front page and blog page ---
			// 2.1.4: added missing pagination check for front and blog pages
			$showonfront = get_option('show_on_front');
			if ( (is_home() && ($showonfront == 'page'))
			  || (is_front_page() && ($showonfront == 'posts')) ) {

				$archive = true;

				$cpts = array();
				if (isset($vthemesettings['pagenavarchivetypes']) && is_array($vthemesettings['pagenavarchivetypes'])) {
					foreach ($vthemesettings['pagenavarchivetypes'] as $cpt => $value) {
						if ($value == '1') {$cpts[] = $cpt;}
					}
				}
				$cpts = bioship_apply_filters('skeleton_pagenavi_archive_types', $cpts);

				if (is_home()) {$posttype = bioship_apply_filters('blog_page_post_type', 'post');}
				elseif (is_front_page()) {$posttype = bioship_apply_filters('front_page_post_type', 'post');}
				if (in_array($posttype, $cpts)) {$display = true;}
			}
		}

		$pagenav = '';
		if ($display) {

			// 1.5.0: handle other CPT display names
			// 1.8.5: moved inside display check
			if ($posttype == 'page') {$posttypedisplay = esc_attr(__('Page','bioship'));}
			elseif ($posttype == 'post') {$posttypedisplay = esc_attr(__('Post','bioship'));}
			else {
				$posttypeobject = get_post_type_object($posttype);
				$posttypedisplay = $posttypeobject->labels->singular_name;
			}
			$posttypedisplay = bioship_apply_filters('skeleton_post_type_display', $posttypedisplay);

			// 1.8.0: left and right arrows for RTL and non-RTL display
			// 2.1.1: fix undefined index warning for opposite values
			if (is_rtl()) {$prevright = ' &rarr;'; $nextleft = '&larr; '; $prevleft = $nextright = '';}
			else {$prevleft = '&larr; '; $nextright = ' &rarr;'; $nextleft = $prevright = '';}

			// TODO: handle image navigation with next_image_link and previous_image_link ?

			// 1.8.5: re-ordered logic
			if (!is_page() && is_singular()) {
				$nextlabel = esc_attr(__('Next','bioship'));
				$prevlabel = esc_attr(__('Previous','bioship'));
				$nextpost = get_next_post_link('<div class="nav-next">%link</div>', $nextleft.$nextlabel.' '.$posttypedisplay.$nextright);
				$prevpost = get_previous_post_link('<div class="nav-prev">%link</div>', $prevleft.$prevlabel.' '.$posttypedisplay.$prevright);

				// 1.8.5: added RTL switchover
				if (is_rtl()) {$pagenav = $nextpost.$prevpost;}
				else {$pagenav = $prevpost.$nextpost;}
			}

			// --- defaults to WP PageNavi plugin ---
			// 1.5.5: some translation fixes to pass theme check
			if (function_exists('wp_pagenavi')) {

				// 1.8.5: add buffer to allow for override
				ob_start(); if (!is_singular()) {wp_pagenavi();}
				$pagenav = ob_get_contents(); ob_end_clean();

			} elseif ($archive) {

				// 1.8.0: use the plural label name
				$posttypeobject = get_post_type_object($posttype);
				$posttypedisplay = $posttypeobject->labels->name;
				$posttypedisplay = bioship_apply_filters('skeleton_post_type_display', $posttypedisplay);

				// 2.1.1: fix to nexposts variable typo
				// 2.1.4: fix (switch) incorrect older and newer labels (as default is descending)
				$nextlabel = esc_attr(__('Older','bioship'));
				$prevlabel = esc_attr(__('Newer','bioship'));
				$nextposts = get_next_posts_link('<div class="nav-next">'.$nextleft.$nextlabel.' '.$posttypedisplay.$nextright.'</div>');
				$prevposts = get_previous_posts_link('<div class="nav-prev">'.$prevleft.$prevlabel.' '.$posttypedisplay.$prevright.'</div>');

				// 1.8.5: added rtl switchover
				if (is_rtl()) {$pagenav = $nextposts.$prevposts;}
				else {$pagenav = $prevposts.$nextposts;}

				// TODO: use post navigation paginate option ?
				// ref: https://codex.wordpress.org/Pagination
				// $pagination = get_the_posts_pagination( array(
				//	'mid_size' => 3,
				//	'prev_text' => esc_attr(__('Newer', 'bioship')),
				//	'next_text' => esc_attr(__('Older', 'bioship')),
				// ) );

			}
		}

		// --- output page navigation ---
		// 2.1.4: move outside to display check to make override filter more available
		$pagenav = bioship_apply_filters('skeleton_pagenavi_override', $pagenav);
		if ($pagenav != '') {
			bioship_html_comment('#nav-below');
			echo '<div id="nav-below" class="navigation">'.PHP_EOL;
			echo '	'.$pagenav.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#nav-below');
			echo PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
		}
	}
}

// ----------------
// Paged Navigation
// ----------------
// 1.8.5: separated from page navi for paged pages
// TODO: add position hook trigger for paged navigation ?
if (!function_exists('bioship_skeleton_paged_navi')) {
 function bioship_skeleton_paged_navi() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get page navigation ---
	if (function_exists('wp_pagenavi')) {
		ob_start(); wp_pagenavi(array('type' => 'multipart'));
		$pagednav = ob_get_contents(); ob_end_clean();
	} else {
		$navargs = array(
			'before' => '<div class="page-link">'.esc_attr(__('Pages','bioship')).':',
			'after' => '</div>',
			'echo' => 0
		);
		$pagednav = wp_link_pages($navargs);
	}

	// --- filter and output ---
	$pagednav = bioship_apply_filters('skeleton_paged_navi_override', $pagednav);
	echo $pagednav; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
 }
}


// --------------
// === Footer ===
// --------------
// note: footer functions are hooked on bioship_footer

// 2.0.5: added missing function_exists wrapper
// 2.1.1: [deprecated] bioship_footer action now called directly in footer.php
// (skeleton_footer was previously hooked on wp_footer with this function)
// if (!function_exists('bioship_skeleton_footer')) {
//	function bioship_skeleton_footer() {bioship_do_action('bioship_footer');}
//	$position = bioship_apply_filters('skeleton_footer_position', 0);
//	bioship_add_action('wp_footer', 'bioship_skeleton_footer', $position);
// }

// Footer Hook Order
// -----------------
// bioship_skeleton_footer_open: 	0
// bioship_skeleton_footer_extras:  2
// bioship_skeleton_footer_widgets: 4
// bioship_skeleton_footer_nav: 	6
// bioship_skeleton_footer_credits: 8
// bioship_skeleton_footer_close:  10

// ----------------
// Footer Wrap Open
// ----------------
if (!function_exists('bioship_skeleton_footer_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_open', 0);

	function bioship_skeleton_footer_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- set and filter footer classes ---
		// 1.5.0: added footer class filter and grid class compatibility
		// 1.8.0: removed grid class compatibility (now for content grid only)
		$classes = array('noborder');
		$classes = bioship_apply_filters('skeleton_footer_classes', $classes);
		$footerclasses = implode(' ', $classes);

		// --- output footer wrap open ---
		bioship_html_comment('#footer');
		$attributes = hybrid_get_attr('footer');
		echo '<div id="footer" class="'.esc_attr($footerclasses).'">'.PHP_EOL;
		echo '	<div id="footerpadding" class="inner">'.PHP_EOL;
		echo '		<footer '.$attributes.'>'.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// -----------------
// Footer Wrap Close
// -----------------
if (!function_exists('bioship_skeleton_footer_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_close', 10);

	function bioship_skeleton_footer_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output footer wrap close ---
		echo '		</footer>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#footer');
		echo PHP_EOL;
	}
}

// -------------
// Footer Extras
// -------------
if (!function_exists('bioship_skeleton_footer_extras')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_extras', 2);

	function bioship_skeleton_footer_extras() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get footer extras via filter ---
		// 1.6.0: removed theme option, now by filter only
		// 2.0.0: allow for usage of shorter footer extras filter name
		$footerextras = bioship_apply_filters('skeleton_footer_extras', '');
		// TODO: remove to backwards compatible filter list
		$footerextras = bioship_apply_filters('skeleton_footer_html_extras', $footerextras);

		// --- output footer extras ---
		if ($footerextras) {
			// 1.8.0: changed #footer_extras to #footer-extras for consistency
			bioship_html_comment('#footer-extras');
			echo '<div id="footer-extras" class="footer-extras">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo '		'.$footerextras.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#footer-extras');
			echo PHP_EOL;
		}
	}
}

// --------------
// Footer Widgets
// --------------
if (!function_exists('bioship_skeleton_footer_widgets')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_widgets', 4);

	function bioship_skeleton_footer_widgets() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- output footer sidebar ---
		// filterable to allow for custom post types (see filters.php)
		// default template is sidebar/footer.php
		$footer = bioship_apply_filters('skeleton_footer_sidebar', 'footer');
		hybrid_get_sidebar($footer);
	}
}

// ---------------
// Footer Nav Menu
// ---------------
if (!function_exists('bioship_skeleton_footer_nav')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_nav', 6);

	function bioship_skeleton_footer_nav() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- check for footer menu ---
		// 2.0.9: added missing menu declaration check
		// 2.1.0: added check for array key
		global $vthemelayout; $layout = $vthemelayout;
		if (!isset($layout['menus']['footer']) || !$layout['menus']['footer']) {return;}

		// --- set and filter menu settings ---
		$menuargs = array(
			'theme_location'  => 'footer',
			'container'       => 'div',
			'container_id' 	  => 'footermenu',
			'menu_class'      => 'menu',
			'echo'            => false,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1
		);
		// 1.8.5: added missing setting filter
		// 2.0.1: fix to filter name typo
		// 2.0.5: added _setting suffix to filter name
		$menuargs = bioship_apply_filters('skeleton_footer_menu_settings', $menuargs);

		// --- output footer menu ---
		$attributes = hybrid_get_attr('menu', 'footer');
		$menu = bioship_html_comment('.footer-menu', false);
		$menu = '<div class="footer-menu" '.$attributes.'>'.PHP_EOL;
		$menu .= '	'.wp_nav_menu($menuargs).PHP_EOL;
		$menu .= '</div>'.PHP_EOL;
		$menu .= bioship_html_comment('/.footer-menu', false);
		$menu .= PHP_EOL;

		// --- filter and output ---
		// 2.1.2: added missing footer menu filter
		// TODO: add filter to doc filter list
		$menu = bioship_apply_filters('skeleton_footer_menu', $menu);
		echo $menu; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	}
}

// --------------
// Footer Credits
// --------------
if (!function_exists('bioship_skeleton_footer_credits')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_credits', 8);

	function bioship_skeleton_footer_credits() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// --- get default theme credits and filter ---
		// 1.9.9: get initial value using skeleton_credit_link
		$credits = bioship_skeleton_credit_link();
		$credits = bioship_apply_filters('skeleton_author_credits', $credits);

		// --- output site credits ---
		if ($credits) {
			bioship_html_comment('#footercredits');
			echo '<div id="footercredits">'.PHP_EOL;
			echo '	'.$credits.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#footercredits');
			echo PHP_EOL;
		}
	}
}

// ----------------
// Get Site Credits
// ----------------
// 1.9.9: use as direct return not as filter
if (!function_exists('bioship_skeleton_credit_link')) {
	function bioship_skeleton_credit_link(){
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// --- check for site credits ---
		if ($vthemesettings['sitecredits'] != '') {
			if ($vthemesettings['sitecredits'] == '0') {return '';}
			return $vthemesettings['sitecredits'];
		} else {
			// --- set default site credits ---
			$sitecredits = '<div id="themecredits">';
				$anchor = 'BioShip Framework';
				if (THEMECHILD) {$sitecredits .= esc_attr(THEMEDISPLAYNAME).' '.esc_attr(__('Theme for','bioship')).' '; $anchor = 'BioShip';}
				$sitecredits .= '<a href="'.esc_url(THEMEHOMEURL).'" title="BioShip '.esc_attr(__('Responsive WordPress Theme Framework','bioship')).'" target=_blank>'.esc_attr($anchor).'</a>';
				if (THEMEPARENT) {$sitecredits .= ' '.esc_attr(__('by','bioship')).' <a href="'.esc_url(THEMESUPPORT).'" title="WordQuest Alliance" target=_blank>WordQuest</a>';}
			$sitecredits .= '</div>'.PHP_EOL;
			return $sitecredits;
		}
	}
}

// ------------------------
// The closet is now empty.
