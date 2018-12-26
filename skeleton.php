<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === SKELETON FUNCTIONS ===
 * ...Flexible Templating ...
 *
**/

// cannot be called directly
if (!function_exists('add_action')) {exit;}

// ------------------------------
// === skeleton.php Structure ===
// ------------------------------
// - Helpers -
// - Wrapper -
// - Header -
// - Nav Menus -
// - Sidebars -
// - Content -
// - Meta -
// - Thumbnails -
// - Author Bio -
// - Comments -
// - Page Navi -
// - Footer -


// -------------
// Skeleton Note
// -------------

// Simple Skeleton Theme was initial codebase for templating functions
// (deprecated SMPL Skeleton skeleton.php as ALL functions rewritten)
// original can be found in includes/deprecated/skeleton-original.php


// ---------------
// === Wrapper ===
// ---------------

// Main Wrapper Open
// -----------------
if (!function_exists('bioship_skeleton_wrapper_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_container_open', 'bioship_skeleton_wrapper_open', 5);

	function bioship_skeleton_wrapper_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout;

		// 1.8.5: use new theme layout global
		$vclasses = array(); $vclasses[] = 'container';
		$vclasses[] = $vthemelayout['gridcolumns'];

		// 1.5.0: added container class compatibility
		// 1.8.5: removed grid compatibility classes (now content grid only)
		// filter the main wrap container classes
		$vcontainerclasses = bioship_apply_filters('skeleton_container_classes',$vclasses);
		if (is_array($vcontainerclasses)) {
			// 2.0.5: use standard array key index
			foreach ($vcontainerclasses as $vkey => $vclass) {$vcontainerclasses[$vkey] = trim($vclass);}
			$vclasses = $vcontainerclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		bioship_html_comment('#wrap.container');
		echo '<div id="wrap" class="'.$vclassstring.'">'.PHP_EOL;
		bioship_html_comment('#wrappadding.inner');
		echo '	<div id="wrappadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// Main Wrapper Close
// ------------------
if (!function_exists('bioship_skeleton_wrapper_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_container_close', 'bioship_skeleton_wrapper_close', 5);

	function bioship_skeleton_wrapper_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo '	</div>'.PHP_EOL;
		bioship_html_comment('/#wrappadding.inner');
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#wrap.container');
		echo PHP_EOL;
	}
}

// Echo a Clearing Div
// -------------------
// 1.5.0: moved clear div here for flexibility
if (!function_exists('bioship_skeleton_echo_clear_div')) {
	function bioship_skeleton_echo_clear_div() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo '<div class="clear"></div>'.PHP_EOL;
	}
}

// Add Clear Divs to Various Layout Points
// ---------------------------------------
// 1.9.8: use bioship_add_action to make positions filterable
// note: use "hook_function_position" combination as filter name to change these positions
// eg. add_filter('bioship_navbar_bioship_echo_clear_div_position', function(){return 4;} );

// after header nav
bioship_add_action('bioship_header', 'bioship_skeleton_echo_clear_div', 3);

// after nav menu // 1.8.0: moved sidebar buttons inline
// add_action('bioship_navbar','skeleton_echo_clear_div',6);

// after nav bar
bioship_add_action('bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 0);
bioship_add_action('bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 8);

// 1.9.8: after content
bioship_add_action('bioship_after_content', 'bioship_skeleton_echo_clear_div', 2);

// before footer
bioship_add_action('bioship_before_footer', 'bioship_skeleton_echo_clear_div', 10);

// after footer widgets
bioship_add_action('bioship_footer', 'bioship_skeleton_echo_clear_div', 5);

// after footer nav
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

// Header Wrap Open
// ----------------
if (!function_exists('bioship_skeleton_header_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_open', 0);

	function bioship_skeleton_header_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout;

		// 1.5.0: added header class compatibility and filter
		// 1.8.0: added alpha and omega classes to header div
		// 1.8.5: use new theme layout global
		// 1.9.0: removed 960gs classes from theme grid (now for content grid only)
		$vclasses = array(); $vclasses[] = $vthemelayout['gridcolumns'];
		$vclasses[] = 'columns'; $vclasses[] = 'alpha'; $vclasses[] = 'omega';
		$vclasses = bioship_apply_filters('skeleton_header_classes', $vclasses);
		$vheaderclasses = implode(' ',$vclasses);

		bioship_html_comment('#header');
		$vattributes = hybrid_get_attr('header');
		echo '<div id="header" class="'.$vheaderclasses.'">'.PHP_EOL;
		bioship_html_comment('#headerpadding.inner');
		echo '	<div id="headerpadding" class="inner">'.PHP_EOL;
		echo '		<header '.$vattributes.'>'.PHP_EOL.PHP_EOL;
	}
}

// Header Wrap Close
// -----------------
if (!function_exists('bioship_skeleton_header_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_close', 10);

	function bioship_skeleton_header_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo PHP_EOL.'		</header>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		bioship_html_comment('/#headerpadding.inner');
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#header');
		echo PHP_EOL;
	}
}

// Header Nav Menu
// ---------------
if (!function_exists('bioship_skeleton_header_nav')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header' ,'bioship_skeleton_header_nav', 2);

	function bioship_skeleton_header_nav() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['header'])) || (!$vthemelayout['menus']['header']) ) {return;}

		// note: can use Hybrid attribute filter to add column classes
		bioship_html_comment('.header-menu');
		$vattributes = hybrid_get_attr('menu','header');
		echo '<div '.$vattributes.'>'.PHP_EOL;
			$vmenuargs = array(
				'theme_location'  => 'header',
				'container'       => 'div',
				'container_id'    => 'headermenu',
				'menu_class'      => 'menu',
				'echo'            => true,
				'fallback_cb'     => false,
				'after'           => '',
				'depth'           => 1
			);
			// 1.8.5: added missing menu setting filter
			// 2.0.5: added _settings filter suffix
			$vmenuargs = bioship_apply_filters('skeleton_header_menu_settings', $vmenuargs);
			wp_nav_menu($vmenuargs);
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/.header-menu');
		echo PHP_EOL;
	}
}

// Skeleton Header Logo
// --------------------
if (!function_exists('bioship_skeleton_header_logo')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_logo', 4);

	function bioship_skeleton_header_logo() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemedisplay;

		$vlogourl = $vthemesettings['header_logo'];
		// 1.5.0: moved logo url filter
		$vlogourl = bioship_apply_filters('skeleton_header_logo_url', $vlogourl);
		// 2.0.9: use esc_url on logo output
		$vlogourl = esc_url($vlogourl);

		// 1.8.5: use home_url not site_url
		$vhomeurl = esc_url(home_url('/'));
		// 2.0.6: added site logo and home title link filters
		$vhomeurl = bioship_apply_filters('skeleton_title_link_url', $vhomeurl);
		$vlogolinkurl = bioship_apply_filters('skeleton_logo_link_url', $vhomeurl);
		// 2.0.9: use esc_url on logo link output
		$vlogolinkurl = esc_url($vlogolinkurl);

		$vblogname = get_bloginfo('name', 'display');
		$vblogname = bioship_apply_filters('skeleton_blog_display_name', $vblogname);
		$vblogdescription = get_bloginfo('description');
		$vblogdescription = bioship_apply_filters('skeleton_blog_description', $vblogdescription);

		// 1.8.0: added header logo class filter
		$vlogoclasses = bioship_apply_filters('skeleton_header_logo_classes', 'logo');

		// 1.8.5: recombined image/text template for live previewing
		// 1.8.5: added site text / description text display checkboxes
		// 1.9.0: fix to logo logic here having separated text display
		// 2.0.6: display as inline block (for combine logo and site title)
		// 2.0.7: move inline-block display to style.css for easier overriding
		$vlogoimagedisplay = ' style="display:none;"';
		if ($vlogourl) {$vlogoimagedisplay = '';}

		$vsitetitle = false; $vsitedesc = false;
		if ( (isset($vthemesettings['header_texts']['sitetitle']))
		  && ($vthemesettings['header_texts']['sitetitle'] == '1') ) {$vsitetitle = true;}
		if ( (isset($vthemesettings['header_texts']['sitedescription']))
		  && ($vthemesettings['header_texts']['sitedescription'] == '1') ) {$vsitedesc = true;}

		// TODO: add check for perpost meta display overrides for site title/desc
		// if ($vthemedisplay['sitetitle'] == '') {}
		// if ($vthemedisplay['sitedesc'] == '') {}

		$vsitetitle = bioship_apply_filters('skeleton_site_title_display', $vsitetitle);
		$vsitedesc = bioship_apply_filters('skeleton_site_description_display', $vsitedesc);
		// 1.9.9: separate display variables for site title and description
		$vsitetitledisplay = ''; $vsitedescdisplay = '';
		if (!$vsitetitle) {$vsitetitledisplay = ' style="display:none;"';}
		if (!$vsitedesc) {$vsitedescdisplay = ' style="display:none;"';}

		// 2.0.9: make title attribute separator filterable (default to title tag separator)
		$vsep = bioship_title_separator('|');
		$vsep = bioship_apply_filters('skeleton_header_logo_title_separator', $vsep);

		// 1.8.5: fix to hybrid attributes names (_ to -)
		// 1.9.0: added filter to site-description attribute to prevent duplicate ID
		// 1.9.6: added logo-image ID not just class
		// 2.0.6: display inline-block for site-logo-text (for combined display)
		// 2.0.7: move inline block to style.css for easier overriding
		$vlogo = '';

		if (THEMECOMMENTS) {$vlogo .= '<!-- #site-logo -->';}
		$vlogo .= '<div id="site-logo" class="'.$vlogoclasses.'">'.PHP_EOL;
		$vlogo .= '	<div class="inner">'.PHP_EOL;
		$vlogo .= ' 	<div class="site-logo-image"'.$vlogoimagedisplay.'>'.PHP_EOL;
		$vlogo .= '			<a class="logotype-img" href="'.$vlogolinkurl.'" title="'.$vblogname.' '.$vsep.' '.$vblogdescription.'" rel="home">'.PHP_EOL;
		$vlogo .= '				<h1 id="site-title">'.PHP_EOL;
		$vlogo .= '					<img id="logo-image" class="logo-image" src="'.$vlogourl.'" alt="'.$vblogname.'" border="0">'.PHP_EOL;
		$vlogo .= '					<div class="alt-logo" style="display:none;"></div>'.PHP_EOL;
		$vlogo .= '				</h1>'.PHP_EOL;
		$vlogo .= '			</a>'.PHP_EOL;
		$vlogo .= ' 	 </div>'.PHP_EOL;
		$vlogo .= ' 	 <div class="site-logo-text">'.PHP_EOL;
		$vlogo .= '			<h1 id="site-title-text" '.hybrid_get_attr('site-title').$vsitetitledisplay.'>'.PHP_EOL;
		$vlogo .= '				<a class="text" href="'.$vhomeurl.'" title="'.$vblogname.' '.$vsep.' '.$vblogdescription.'" rel="home">'.$vblogname.'</a>'.PHP_EOL;
		$vlogo .= '			</h1>'.PHP_EOL;
		$vlogo .= '			<div id="site-description"'.$vsitedescdisplay.'>'.PHP_EOL;
		$vlogo .= '				<span class="site-desc" '.hybrid_get_attr('site-description').'>'.$vblogdescription.'</span>'.PHP_EOL;
		$vlogo .= '			</div>'.PHP_EOL;
		$vlogo .= ' 	</div>'.PHP_EOL;
		$vlogo .= '	</div>'.PHP_EOL;
		$vlogo .= '</div>'.PHP_EOL;
		if (THEMECOMMENTS) {$vlogo .= '<!-- /#site-logo -->'.PHP_EOL;}
		$vlogo .= PHP_EOL;

		$vlogo = bioship_apply_filters('skeleton_header_logo_override', $vlogo);
		echo $vlogo;
	}
}

// Header Widgets
// --------------
if (!function_exists('bioship_skeleton_header_widgets')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_widgets', 6);

	function bioship_skeleton_header_widgets() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// note: template filterable to allow for custom post types (see filters.php)
		// default template is sidebar/header.php
		$vheader = bioship_apply_filters('skeleton_header_sidebar', 'header');
		hybrid_get_sidebar($vheader);
	}
}

// Header Extras
// -------------
if (!function_exists('bioship_skeleton_header_extras')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_header', 'bioship_skeleton_header_extras', 8);

	function bioship_skeleton_header_extras() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;

		// 2.0.0: allow for use of shorter header extras filter name
		$vheaderextras = bioship_apply_filters('skeleton_header_extras', '');
		$vheaderextras = bioship_apply_filters('skeleton_header_html_extras', $vheaderextras);
		if ($vheaderextras) {
			// 1.8.0: changed #header_extras to #header-extras for consistency, added class filter
			$vheaderextraclasses = bioship_apply_filters('skeleton_header_extras_classes', 'header-extras');
			bioship_html_comment('#header-extras');
			echo '<div id="header-extras" class="'.$vheaderextraclasses.'">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo $vheaderextras.PHP_EOL;
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

// Main Menu Wrap Open
// -------------------
if (!function_exists('bioship_skeleton_main_menu_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_open', 0);

	function bioship_skeleton_main_menu_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['primary'])) || (!$vthemelayout['menus']['primary']) ) {return;}

		// note: can filter classes using Hybrid attribute filter
		bioship_html_comment('#navigation');
		$vattributes = hybrid_get_attr('menu', 'primary');
		echo '<div id="navigation" '.$vattributes.'>'.PHP_EOL.PHP_EOL;
	}
}

// Primary Navigation Menu
// -----------------------
if (!function_exists('bioship_skeleton_main_menu')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu', 8);

	function bioship_skeleton_main_menu() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['primary'])) || (!$vthemelayout['menus']['primary']) ) {return;}

		// 1.9.9: check hide navigation override filter
		$vhidenav = bioship_apply_filters('skeleton_navigation_hide', 0);
		if ($vhidenav) {return;}

		// 1.8.0: only output if there is a menu is assigned
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$vmenuargs = array(
			'container_id'		=> 'mainmenu',
			'container_class'	=> 'menu-header',
			'theme_location'	=> 'primary'
		);
		$vmenuargs = bioship_apply_filters('skeleton_primary_menu_settings', $vmenuargs);
		wp_nav_menu($vmenuargs);
	}
}

// Wrap Close
// ----------
if (!function_exists('bioship_skeleton_main_menu_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_close', 10);

	function bioship_skeleton_main_menu_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['primary'])) || (!$vthemelayout['menus']['primary']) ) {return;}
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#navigation');
		echo PHP_EOL;
	}
}

// Main Menu Mobile Button
// -----------------------
// 1.5.0: added mobile menu button
if (!function_exists('bioship_skeleton_main_menu_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_main_menu_button', 4);

	function bioship_skeleton_main_menu_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: use subkey of vthemelayout global
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['primary'])) || (!$vthemelayout['menus']['primary']) ) {return;}

		// 1.5.5: check for perpost navigation disable
		// 1.9.8: fix to use display override global
		// 1.9.9: check hide navigation override filter
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$vhidenav = bioship_apply_filters('skeleton_navigation_hide', 0);
		if ($vhidenav) {return;}

		echo '<div id="mainmenubutton" class="mobilebutton">';
		echo '<a class="button" id="mainmenushow" href="javascript:void(0);" onclick="showmainmenu();">Show Menu</a>'.PHP_EOL;
		echo '<a class="button" id="mainmenuhide" href="javascript:void(0);" onclick="hidemainmenu();" style="display:none;">Hide Menu</a>'.PHP_EOL;
		echo '</div>';
	}
}

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

		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['secondary'])) || (!$vthemelayout['menus']['secondary']) ) {return;}

		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$vattributes = hybrid_get_attr('menu', 'secondary');
		bioship_html_comment('#secondarymenu');
		echo '<div id="secondarymenu" '.$vattributes.'>'.PHP_EOL;
		echo '	<div class="inner">'.PHP_EOL;
			$vmenuargs = array(
				'container_id' 		=> 'submenu',
				'container_class' 	=> 'menu-header',
				'theme_location' 	=> 'secondary'
			);
			$vmenuargs = bioship_apply_filters('skeleton_secondary_menu_settings', $vmenuargs);
			wp_nav_menu($vmenuargs);
		echo '	</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#secondarymenu');
		echo PHP_EOL;
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


// Abstract Banner Wrapper
// -----------------------
if (!function_exists('bioship_skeleton_banner_abstract')) {
 function bioship_skeleton_banner_abstract($vposition) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

 	global $post; $vbanner = '';

 	// filterable to allow for HTML / ads / shortcode / widget...
 	$vbanner = bioship_apply_filters('skeleton_'.$vposition.'_banner', $vbanner);
	// banner override if custom field value is set
 	if (is_singular()) {
 		// note: can set custom field values (for automatic image banners only)
 		// eg. _topbannerurl, _topbannerlink...
 		$vpostid = $post->ID; $vbanner = get_post_meta($vpostid,'_'.$vposition.'bannerurl', true);
 		if ($vbanner != '') {$vbanner = '<img src="'.$vbanner.'" border="0">';}
 		$vbannerlink = get_post_meta($vpostid,'_'.$vposition.'bannerlink', true);
 		if ($vbannerlink != '') {$vbanner = '<a href="'.$vbannerlink.'" target=_blank>'.$vbanner.'</a>';}
 	}
 	if ($vbanner != '') {
 		// 1.9.8: added banner div class filter
 		// 2.0.5: added extra filter based on banner position
 		$vclass = bioship_apply_filters('skeleton_banner_class', $vposition);
 		$vclass = bioship_apply_filters('skeleton_banner_class_'.$vposition, $vclass);
 		if ($vclass != $vposition) {$vclass = ' class="'.$vclass.'"';}
	 	bioship_html_comment('#'.$vposition.'banner');
	 	echo '<div id="'.$vposition.'banner"'.$vclass.'>'.PHP_EOL;
	 	echo '	<div class="inner">'.PHP_EOL;
 		echo $vbanner.PHP_EOL;
 		echo '	</div>'.PHP_EOL;
 		echo '</div>'.PHP_EOL;
 		bioship_html_comment('/#'.$vposition.'banner');
 		echo PHP_EOL;
	}
 }
}

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

// Add Widget Classes for Styling
// ------------------------------
// adapted from: http://wordpress.stackexchange.com/a/54505/76440
add_filter('dynamic_sidebar_params', 'bioship_skeleton_add_widget_classes');
if (!function_exists('bioship_skeleton_add_widget_classes')) {
 function bioship_skeleton_add_widget_classes($vparams) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemewidgets, $vthemewidgetcounter;
	$vsidebarid = $vparams[0]['id'];

    if (!isset($vthemewidgets)) {$vthemewidgets = wp_get_sidebars_widgets();}
    if (!isset($vthemewidgetcounter)) {$vthemewidgetcounter = array();}

    // bail if the current sidebar has no widgets
    if ( (!isset($vthemewidgets[$vsidebarid])) || (!is_array($vthemewidgets[$vsidebarid])) ) {return $vparams;}

    // [not implemented] this is for horizontal span classes
    // Rounds number of widgets down to a whole number
    // $number_of_widgets = count($vthemewidgets[$vsidebarid]);
    // $rounded_number_of_widgets = floor(12 / $number_of_widgets);
	// $vclasses[] = 'span' . $rounded_number_of_widgets;

	// increment / start widget counter for this sidebar
    if (isset($vthemewidgetcounter[$vsidebarid])) {$vthemewidgetcounter[$vsidebarid]++;}
    else {$vthemewidgetcounter[$vsidebarid] = 1; $vclasses[] = 'first-widget';}
	if ($vthemewidgetcounter[$vsidebarid] == count($vthemewidgets[$vsidebarid])) {$vclasses[] = 'last-widget';}

	// add odd / even classes to widgets
    if ($vthemewidgetcounter[$vsidebarid] & 1 ) {$vclasses[] = 'odd-widget';} else {$vclasses[] = 'even-widget';}

	$vclassstring = implode(' ',$vclasses); $vclassstring .= ' ';

    $vparams[0]['before_widget'] = preg_replace('/class=\"/', 'class="' . $vclassstring . ' ', $vparams[0]['before_widget'], 1);

    return $vparams;
 }
}

// ---------------
// Primary Sidebar
// ---------------

// Sidebar Position Class to Body Tag
// ----------------------------------
// 1.8.0: rename from skeleton_sidebar_position
if (!function_exists('bioship_skeleton_sidebar_position_class')) {

	add_filter('body_class', 'bioship_skeleton_sidebar_position_class');

	function bioship_skeleton_sidebar_position_class($vclasses) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesidebars;
		if (!$vthemesidebars['sidebar']) {return $vclasses;}

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vi => $vasidebar) {
			if ($vasidebar != '') {
				// note: sub prefix incidates subsidebar
				if (substr($vasidebar,0,3) != 'sub') {
					// positions: left, inner left, inner right, right
					if ( ($vi == 0) || ($vi == 1) ) {$vclasses[] = 'sidebar-left';}
					if ( ($vi == 2) || ($vi == 3) ) {$vclasses[] = 'sidebar-right';}
				}
			}
		}
		return $vclasses;
	}
}

// Mobile Sidebar Display Button
// -----------------------------
// 1.5.0: added this button
if (!function_exists('bioship_skeleton_sidebar_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_sidebar_button', 2);

	function bioship_skeleton_sidebar_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesidebars; if (!$vthemesidebars['sidebar']) {return;}

		bioship_html_comment('#sidebarbutton');
		echo '<div id="sidebarbutton" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="sidebarshow" href="javascript:void(0);" onclick="showsidebar();">Show Sidebar</a>'.PHP_EOL;
		echo '	<a class="button" id="sidebarhide" href="javascript:void(0);" onclick="hidesidebar();" style="display:none;">Hide Sidebar</a>'.PHP_EOL;
		echo '</div>';
		echo '<div id="sidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="sidebarshowsmall" href="javascript:void(0);" onclick="showsidebar();">[+] Sidebar</a>'.PHP_EOL;
		echo '	<a class="button" id="sidebarhidesmall" href="javascript:void(0);" onclick="hidesidebar();" style="display:none;">[-] Sidebar</a>'.PHP_EOL;
		echo '</div>';
		bioship_html_comment('/#sidebarbutton');
		echo PHP_EOL;
	}
}

// Sidebar Wrap Open
// -----------------
// 1.5.0: skeleton_sidebar_wrap to skeleton_sidebar_open
if (!function_exists('bioship_skeleton_sidebar_open'))  {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_sidebar', 'bioship_skeleton_sidebar_open', 5);

	function bioship_skeleton_sidebar_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global
		// [!?! WTFF this is just not working !?!]
		// $vsidebarcolumns = $vthemesidebars['sidebarcolumns'];
		// $vsidebars = $vthemesidebars; unset($vsidebars['output']);
		// bioship_debug("Sidebar Layout Check", $vsidebars);
		$vsidebarcolumns = bioship_set_sidebar_columns();

		$vclasses = array(); $vclasses[] = $vsidebarcolumns; $vclasses[] = 'columns';

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple numerical array key index
		// TODO: check if still necessary to add alpha/omega sidebar classes
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vi => $vasidebar) {
			if ($vasidebar != '') {
				if (substr($vasidebar,0,3) != 'sub') {
					// positions: left, inner left, inner right, right
					if ($vi == 0) {$vclasses[] = 'alpha';}
					if ( ($vi == 1) && ($vsidebars[0] == '') ) {$vclasses[] = 'alpha';}
					if ( ($vi == 2) && ($vsidebars[3] == '') ) {$vclasses[] = 'omega';}
					if ($vi == 3) {$vclasses[] = 'omega';}
				}
			}
		}

		// 1.8.0: added sidebar class array filter
		$vsidebarclasses = bioship_apply_filters('skeleton_sidebar_classes', $vclasses);
		if (is_array($vsidebarclasses)) {
			// 2.0.5: use simple array key index
			foreach ($vsidebarclasses as $vkey => $vclass) {$vsidebarclasses[$vkey] = trim($vclass);}
			$vclasses = $vsidebarclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		bioship_html_comment('#sidebar');
		echo '<div id="sidebar" class="'.$vclassstring.'" role="complementary">'.PHP_EOL;
		bioship_html_comment('#sidebarpadding.inner');
		echo '	<div id="sidebarpadding" class="inner">'.PHP_EOL.PHP_EOL;

	}
}

// Sidebar Wrap Close
// ------------------
if (!function_exists('skeleton_sidebar_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_sidebar', 'bioship_skeleton_sidebar_close', 5);

	function bioship_skeleton_sidebar_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo PHP_EOL.'	</div>';
		bioship_html_comment('/#sidebarpadding.inner');
		echo PHP_EOL.'</div>'.PHP_EOL;
		bioship_html_comment('/#sidebar');
		echo PHP_EOL;
	}
}


// ------------------
// Subsidiary Sidebar
// ------------------

// Add SubSidebar Class to Body Tag
// --------------------------------
// 1.8.0: renamed from skeleton_subsidebar_position
// 1.8.0: removed theme options check to allow for sidebar overrides
// 1.9.9: added missing function_exists check
if (!function_exists('bioship_skeleton_subsidebar_position_class')) {

	add_filter('body_class', 'bioship_skeleton_subsidebar_position_class');

	function bioship_skeleton_subsidebar_position_class($vclasses) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		global $vthemesidebars; if (!$vthemesidebars['subsidebar']) {return $vclasses;}

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vi => $vasidebar) {
			if (substr($vasidebar,0,3) == 'sub') {
				// positions: left, inner left, inner right, right
				if ( ($vi == 0) || ($vi == 1) ) {$vclasses[] = 'subsidebar-left';}
				if ( ($vi == 2) || ($vi == 3) ) {$vclasses[] = 'subsidebar-right';}
			}
		}
		return $vclasses;
	}
}

// Mobile Subsidebar Display Button
// --------------------------------
// 1.5.0: added this button
if (!function_exists('bioship_skeleton_subsidebar_button')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_navbar', 'bioship_skeleton_subsidebar_button', 6);

	function bioship_skeleton_subsidebar_button() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesidebars; if (!$vthemesidebars['subsidebar']) {return;}

		bioship_html_comment('#subsidebarbutton');
		echo '<div id="subsidebarbutton" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="subsidebarshow" href="javascript:void(0);" onclick="showsubsidebar();">Show SubBar</a>'.PHP_EOL;
		echo '	<a class="button" id="subsidebarhide" href="javascript:void(0);" onclick="hidesubsidebar();" style="display:none;">Hide SubBar</a>'.PHP_EOL;
		echo '</div>';
		echo '<div id="subsidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="subsidebarshowsmall" href="javascript:void(0);" onclick="showsubsidebar();">[+] SubBar</a>'.PHP_EOL;
		echo '	<a class="button" id="subsidebarhidesmall" href="javascript:void(0);" onclick="hidesubsidebar();" style="display:none;">[-] SubBar</a>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#subsidebarbutton');
		echo PHP_EOL;
	}
}

// Subsidebar Wrap Open
// --------------------
if (!function_exists('bioship_skeleton_subsidebar_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_subsidebar','skeleton_subsidebar_open',5);

	function bioship_skeleton_subsidebar_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global
		// [!?! WTFF this is just not working !?!]
		// $vsubsidebarcolumns = $vthemesidebars['subsidebarcolumns'];
		// $vsidebars = $vthemesidebars; unset($vsidebars['output']);
		// bioship_debug("Sidebar Layout Check", $vsidebars);
		$vsubsidebarcolumns = bioship_set_subsidebar_columns();

		$vclasses = array(); $vclasses[] = $vsubsidebarcolumns; $vclasses[] = 'columns';

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		// TODO: check if still necessary to add alpha/omega subsidebar classes
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vi => $vasidebar) {
			if (substr($vasidebar,0,3) == 'sub') {
				// positions: left, subleft, subright, right
				if ($vi == 0) {$vclasses[] = 'alpha';}
				if ( ($vi == 1) && ($vsidebars[0] == '') ) {$vclasses[] = 'alpha';}
				if ( ($vi == 2) && ($vsidebars[3] == '') ) {$vclasses[] = 'omega';}
				if ($vi == 3) {$vclasses[] = 'omega';}
			}
		}

 		// 1.8.0: added subsidebar class array filter
 		$vsubsidebarclasses = bioship_apply_filters('skeleton_subsidebar_classes',$vclasses);
		if (is_array($vsubsidebarclasses)) {
			// 2.0.5: use simple array key index
			foreach ($vsubsidebarclasses as $vkey => $vclass) {$vsubsidebarclasses[$vkey] = trim($vclass);}
			$vclasses = $vsubsidebarclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		bioship_html_comment('#subsidebar');
		echo '<div id="subsidebar" class="'.$vclassstring.'" role="complementary">'.PHP_EOL;
		bioship_html_comment('#subsidebarpadding.inner');
		echo '	<div id="subsidebarpadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// Subsidebar Wrap Close
// ---------------------
// 1.8.0: fix from skeleton_subsidebar_wrap_close
if (!function_exists('bioship_skeleton_subsidebar_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_subsidebar', 'bioship_skeleton_subsidebar_close', 5);

	function bioship_skeleton_subsidebar_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
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

// WooCommerce Wrapper Open
// ------------------------
// 1.8.0: add div wrapper to woocommerce content for ease of style targeting
// 2.0.5: added missing function_exists check
if (!function_exists('bioship_skeleton_woocommerce_wrapper_open')) {
 add_action('woocommerce_before_main_content', 'bioship_skeleton_woocommerce_wrapper_open');
 function bioship_skeleton_woocommerce_wrapper_open() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	bioship_html_comment('#woocommercecontent');
	echo '<div id="woocommercecontent">'.PHP_EOL.PHP_EOL;
 }
}

// WooCommerce Wrapper Close
// -------------------------
// 1.8.0: add div wrapper to woocommerce content for ease of style targeting
// 2.0.5: added missing function_exists check
if (!function_exists('bioship_skeleton_woocommerce_wrapper_close')) {
 add_action('woocommerce_after_main_content', 'bioship_skeleton_woocommerce_wrapper_close');
 function bioship_skeleton_woocommerce_wrapper_close() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
	echo '</div>'.PHP_EOL;
	bioship_html_comment('/#woocommercecontent');
	echo PHP_EOL;
 }
}

// Content Wrap Open
// -----------------
if (!function_exists('bioship_skeleton_content_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_before_content', 'bioship_skeleton_content_open', 10);

	function bioship_skeleton_content_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings, $vthemelayout, $vthemesidebars;

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 1.9.0: use new themesidebars global
		$vsidebars = $vthemesidebars['sidebars'];
		$vleftsidebar = false; $vrightsidebar = false;
		if ( ($vsidebars[0] != '') || ($vsidebars[1] != '') ) {$vleftsidebar = true;}
		if ( ($vsidebars[2] != '') || ($vsidebars[3] != '') ) {$vrightsidebar = true;}

		// 1.5.0: replaced skeleton_options call here
		// 1.9.8: use themelayout global content columns
		$vcolumns = $vthemelayout['contentcolumns'];

		// 1.8.0: add alpha/omega class depending on sidebar presence
		// 1.8.5: fix to double sidebar logic
		// 2.0.7: added missing content classes filter
		$vclasses = array(); $vclasses[0] = $vcolumns; $vclasses[1] = 'columns';
		if ( (!$vleftsidebar) && (!$vrightsidebar) ) {$vclasses[] = 'alpha'; $vclasses[] = 'omega';}
		elseif ( ($vleftsidebar) && (!$vrightsidebar) ) {$vclasses[] = 'omega';}
		elseif ( ($vrightsidebar) && (!$vleftsidebar) ) {$vclasses[] = 'alpha';}
		$vclasses = bioship_apply_filters('skeleton_content_classes', $vclasses);
		if (count($vclasses) > 0) {$vclasslist = implode(" ",$vclasses);}

		// #top id for scroll links
		echo '<a id="top" name="top"></a>';

		bioship_html_comment('#content');
		echo '<div id="content" class="'.$vclasslist.'">'.PHP_EOL;
		bioship_html_comment('#contentpadding.inner');
		echo '	<div id="contentpadding" class="inner">'.PHP_EOL.PHP_EOL;
	}
}

// Content Wrap Close
// ------------------
if (!function_exists('bioship_skeleton_content_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_after_content', 'bioship_skeleton_content_close', 0);

    function bioship_skeleton_content_close() {
    	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
    	echo PHP_EOL.'	</div>'.PHP_EOL;
    	bioship_html_comment('/#contentpadding.inner');
    	echo '</div>'.PHP_EOL;
    	bioship_html_comment('/#content');
    	echo PHP_EOL;
    	echo '<a id="bottom" name="bottom"></a>'; // #bottom id for scroll links
    }
}

// Home (Blog) Page Top Content
// ----------------------------
// 1.8.5: moved this here from loop-hybrid.php
if (!function_exists('bioship_skeleton_home_page_content')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_home_page_top', 'skeleton_home_page_content', 5);

	function bioship_skeleton_home_page_content() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		$vpageid = get_option('page_for_posts');
		if ($vpageid) {
			$vtitle = get_the_title($vpageid);
			// 1.9.8: added new home page title filter
			$vtitle = bioship_apply_filters('skeleton_home_page_title', $vtitle);
			if ($vtitle) {
				bioship_html_comment('#blogpagetitle');
				echo '<h2 id="blogpagetitle">'.$vtitle.'</h2>'.PHP_EOL;
				bioship_html_comment('/#blogpagetitle');
				echo PHP_EOL;
			}

			setup_postdata(get_page($vpageid));
			ob_start(); the_content(); $vcontent = ob_get_contents(); ob_end_clean();
			// 1.9.8: added new home page content filter
			$vcontent = bioship_apply_filters('skeleton_home_page_content', $vcontent);
			if ($vcontent) {
				bioship_html_comment('#blogpagecontent');
				echo '<div id="blogpagecontent">'.$vcontent.'</div>'.PHP_EOL;
				bioship_html_comment('/#blogpagecontent');
			}
		}
	}
}

// Echo the Excerpt via Action Hook
// --------------------------------
// 1.5.0: added for no reason but to make it overrideable
if (!function_exists('skeleton_echo_the_excerpt')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_the_excerpt', 'bioship_skeleton_echo_the_excerpt', 5);

	function bioship_skeleton_echo_the_excerpt() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		the_excerpt();
	}
}

// Ensure Body Content Not Output in Head
// --------------------------------------
// 2.0.5: fix to avoid very weird bug (unknown plugin conflict?)
$vthemehead = false;
add_action('wp_head', 'bioship_head_finished', 999);
if (!function_exists('bioship_head_finished')) {
 function bioship_head_finished() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
 	global $vthemehead; $vthemehead = true;
 	bioship_debug("Theme Head Output Finished");
 }
}

// Echo the Content via Action Hook
// --------------------------------
// 1.5.0: added for no reason but to make it overrideable
if (!function_exists('bioship_skeleton_echo_the_content')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_the_content', 'bioship_skeleton_echo_the_content', 5);

	function bioship_skeleton_echo_the_content() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		if (THEMEDEBUG) {
			global $wp_filter;
			// print_r(array_keys($wp_filter));
			// $debug = print_r($wp_filter['wp_head'], true);
			// $file = dirname(__FILE__).'/debug/filters.txt';
			// bioship_write_to_file($file, $debug);
			bioship_debug("Content Filters", $wp_filter['the_content']);
		}

		// 2.0.5: ensure content is not output in head
		global $vthemehead;
		if ($vthemehead) {the_content();}
	}
}

// Media Template Handler
// ----------------------
// 1.8.0: media handler for attachments and post formats
if (!function_exists('bioship_skeleton_media_handler')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_media_handler', 'bioship_skeleton_media_handler', 5);

	function bioship_skeleton_media_handler() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (!is_singular()) {return;}

		global $vthemesettings;

		// Attachments
		// -----------
		if (is_attachment()) {
			// check attachment mime type
			$vmimetype = get_post_mime_type();
			if (strstr($vmimetype,'audio')) {$vtype = 'audio';}
			elseif (strstr($vmimetype,'video')) {$vtype = 'video';}
			elseif (strstr($vmimetype,'image')) {$vtype = 'image';}
			elseif (strstr($vmimetype,'text')) {$vtype = 'text';}
			elseif (strstr($vmimetype,'application')) {$vtype = 'application';}
			else {return;} // unrecognized

			// Display Attachment
			// ------------------
			bioship_html_comment('#attachment');
			echo '<div id="attachment">'.PHP_EOL;
			if ( ($vtype == 'audio') || ($vtype == 'video') || ($vtype == 'application') ) {
				if ( (!THEMEHYBRID) && (!function_exists('hybrid_attachment')) ) {bioship_load_hybrid_media();}
				hybrid_attachment();
			}
			if ($vtype == 'image') {
				if (has_excerpt()) { // image caption check
					$src = wp_get_attachment_image_src(get_the_ID(), 'full');
					echo img_caption_shortcode( array('align' => 'aligncenter', 'width' => esc_attr($src[1]), 'caption' => get_the_excerpt()), wp_get_attachment_image(get_the_ID(), 'full', false) );
				} else {echo wp_get_attachment_image( get_the_ID(), 'full', false, array('class' => 'aligncenter') );}
			}
			if ( ($vtype == 'text') || ($vtype == 'application') ) {
				$vattachment = wp_get_attachment_metadata();
				$vuploaddir = wp_upload_dir();
				$vfileurl = trailingslashit($vuploaddir['baseurl']).$vattachment['file'];
				echo '<center><a href="'.$vfileurl.'">Download this Attachment</a>.</center><br>';
			}
			if ($vtype == 'text') {
				$vfilepath = trailingslashit($vuploaddir['basedir']).$vattachment['file'];
				echo '<div id="attachment-text"><textarea style="width:100%; height:500px;">';
				echo bioship_file_get_contents($vfilepath);
				echo '</textarea></div><br>';
			}
			echo PHP_EOL.'</div>'.PHP_EOL;
			bioship_html_comment('/#attachment');
			echo PHP_EOL;

			// Attachment Meta
			// ---------------
			bioship_html_comment('.attachment-meta');
			echo '<div class="attachment-meta">'.PHP_EOL;
			echo '	<div class="media-info '.$vtype.'-info">'.PHP_EOL;
			echo '		<h4 class="attachment-meta-title">';
			if ($vtype == 'audio') {echo __('Audio Info','bioship');}
			if ($vtype == 'video') {echo __('Video Info','bioship');}
			if ($vtype == 'image') {echo __('Image Info','bioship');}
			if ($vtype == 'text') {echo __('Text Info','bioship');}
			if ($vtype == 'application') {echo __('Attachment Info','bioship');}
			echo '</h4>'.PHP_EOL;
			hybrid_media_meta();
			echo PHP_EOL.'	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/.attachment-meta');
			echo PHP_EOL;

			// remove default WordPress attachment display (prepended to content)
			// TODO: recheck prepend_attachment filter for improving media handler
			remove_filter('the_content', 'prepend_attachment');
			return;
		}

		// Post Formats
		// ------------
		// (audio/video/image)

		if ($vthemesettings['postformatsupport'] != '1') {return;}

		// Audio Grabber
		// -------------
		if ( ($vthemesettings['postformats']['audio'] == '1') && (has_post_format('audio')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {bioship_load_hybrid_media();}
			$vaudio = hybrid_media_grabber(array('type' => 'audio', 'split_media' => true));
			if ($vaudio) {echo '<div id="post-format-media" class="post-format-audio">'.$vaudio.'</div>';}
		}

		// Video Grabber
		// -------------
		if ( ($vthemesettings['postformats']['video'] == '1') && (has_post_format('video')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {bioship_load_hybrid_media();}
			$vvideo = hybrid_media_grabber(array('type' => 'video', 'split_media' => true));
			if ($vvideo) {echo '<div id="post-format-media" class="post-format-video">'.$vvideo.'</div>';}
		}

		// Image Grabber
		// -------------
		if ( ($vthemesettings['postformats']['image'] == '1') && (has_post_format('image')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {bioship_load_hybrid_media();}
			$vimage = get_the_image(array( 'echo' => false, 'size' => 'full', 'split_content' => true, 'scan_raw' => true, 'scan' => true, 'order' => array( 'scan_raw', 'scan', 'featured', 'attachment' ) ) );
			if ($vimage) {
				echo '<div id="post-format-media" class="post-format-image">'.$vimage.'</div>';

				// TODO: maybe display image sizes
				// echo '<div class="entry-byline"><span class="image-sizes">';
				// printf(__( 'Sizes: %s', 'bioship'), hybrid_get_image_size_links() );
				// echo '</span></div>';

			}
		}

		// Show Gravatar for Status?
		// if (get_option('show_avatars')) {
		// 	echo '<header class="entry-header">'.get_avatar(get_the_author_meta('email'));.'</header>';
		// ]

		// Gallery
		// -------
		// TODO: gallery post format display output handler
		if ( ($vthemesettings['postformats']['gallery'] == '1') && (has_post_format('gallery')) ) {
			// $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'orderby' => 'rand', 'id' => get_queried_object()->post_parent, 'exclude' => get_the_ID() ) );
			// if ( !empty( $gallery ) ) {
			// 	echo '<div class="image-gallery">';
			// 	echo '<h3 class="attachment-meta-title">'.__('Gallery', 'bioship').'</h3>';
			// 	echo $gallery;
			// 	echo '</div>';
			// }
		}

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

// Entry Header Wrap Open
// ----------------------
if (!function_exists('bioship_skeleton_entry_header_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_open', 0);

	function bioship_skeleton_entry_header_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_html_comment('.entry-header');
		$vattributes = hybrid_get_attr('entry-header');
		echo '<header '.$vattributes.'>'.PHP_EOL;
	}
}

// Entry Header Wrap Close
// -----------------------
if (!function_exists('bioship_skeleton_entry_header_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_close', 10);

	function bioship_skeleton_entry_header_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo PHP_EOL.'</header>'.PHP_EOL;
		bioship_html_comment('/.entry-header');
		echo PHP_EOL;
	}
}

// Entry Header Title
// ------------------
if (!function_exists('bioship_skeleton_entry_header_title')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_title', 2);

	function bioship_skeleton_entry_header_title() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $post; $vpostid = $post->ID; $vposttype = $post->post_type;
		// 1.5.0: use h3 instead of h2 for archive/excerpt listings
		if (is_archive() || is_search() || (!is_singular($vposttype)) ) {$vhsize = 'h3';} else {$vhsize = 'h2';}

		bioship_html_comment('.entry-title');
		$vattributes = hybrid_get_attr('entry-title');
		echo '<'.$vhsize.' '.$vattributes.'>'.PHP_EOL;
		echo '	<a href="'; the_permalink(); echo '" rel="bookmark" itemprop="url" title="';
		printf(esc_attr__('Permalink to %s','bioship'), the_title_attribute('echo=0'));
		echo '">'.get_the_title($vpostid).'</a>'.PHP_EOL;
		echo '</'.$vhsize.'>'.PHP_EOL;
		bioship_html_comment('/.entry-title');
		echo PHP_EOL;
	}
}

// Entry Header Subtitle
// ---------------------
// Uses WP Subtitle plugin, still shows saved subtitle if plugin deactivated
if (!function_exists('bioship_skeleton_entry_header_subtitle')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_subtitle', 4);

	function bioship_skeleton_entry_header_subtitle() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $post; $vpostid = $post->ID; $vposttype = $post->post_type;

		// 1.5.0: moved key filter here before WP subtitle check
		$vsubtitlekey = 'wps_subtitle'; // see filters.php example
		$vsubtitlekey = bioship_apply_filters('skeleton_subtitle_key', $vsubtitlekey);

		// Check for WP Subtitle Function
		if ( (function_exists('get_the_subtitle')) && ($vsubtitlekey == 'wps_subtitle') ) {
			$vsubtitle = get_the_subtitle($vpostid, '', '', false);
		} else {$vsubtitle = get_post_meta($vpostid,$vsubtitlekey, true);}

		if ($vsubtitle != '') {
			// 1.5.0: use h4 instead of h3 for archive/excerpt listings
			if (is_archive() || is_search() || (!is_singular($vposttype)) ) {$vhsize = 'h4';} else {$vhsize = 'h3';}
			// note: there is no actual hybrid attributes for entry-subtitle
			bioship_html_comment('.entry-subtitle');
			$vattributes = hybrid_get_attr('entry-subtitle');
			echo '<'.$vhsize.' '.$vattributes.'>'.$vsubtitle.'</'.$vhsize.'>'.PHP_EOL;
			bioship_html_comment('/.entry-subtitle');
			echo PHP_EOL;
		}
	}
}

// Entry Header Meta/Byline
// ------------------------
if (!function_exists('bioship_skeleton_entry_header_meta')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_header', 'bioship_skeleton_entry_header_meta', 6);

	function bioship_skeleton_entry_header_meta() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $post;
		$vpostid = $post->ID; $vposttype = $post->post_type;

		$vmeta = bioship_get_entry_meta($vpostid, $vposttype, 'top');
		if ($vmeta != '') {
			bioship_html_comment('.entry-meta');
			echo '<div class="entry-meta entry-byline">'.PHP_EOL;
			echo $vmeta.PHP_EOL.'</div>'.PHP_EOL;
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

// Entry Footer Wrap Open
// ----------------------
if (!function_exists('bioship_skeleton_entry_footer_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_open', 0);

	function bioship_skeleton_entry_footer_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_html_comment('.entry-footer');
		$vattributes = hybrid_get_attr('entry-footer');
		echo '<footer '.$vattributes.'>'.PHP_EOL;
	}
}

// Entry Footer Wrap Close
// -----------------------
if (!function_exists('bioship_skeleton_entry_footer_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_close', 10);

	function bioship_skeleton_entry_footer_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo PHP_EOL.'</footer>';
		bioship_html_comment('/.entry-footer');
		echo PHP_EOL;
	}
}

// Entry Footer Meta/Byline
// ------------------------
if (!function_exists('bioship_skeleton_entry_footer_meta')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_entry_footer', 'bioship_skeleton_entry_footer_meta', 6);

	function bioship_skeleton_entry_footer_meta() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $post;
		$vpostid = $post->ID; $vposttype = get_post_type();

		$vmeta = bioship_get_entry_meta($vpostid, $vposttype, 'bottom');
		if ($vmeta != '') {
			bioship_html_comment('.entry-utility');
			echo '<div '.hybrid_get_attr('entry-utility').'>'.PHP_EOL;
			echo $vmeta.PHP_EOL.'</div>';
			bioship_html_comment('/.entry-utility');
			echo PHP_EOL;
		}
	}
}


// ------------------
// === Thumbnails ===
// ------------------

// Echo Thumbnail Action Hook
// --------------------------
if (!function_exists('bioship_skeleton_echo_thumbnail')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_thumbnail', 'bioship_skeleton_echo_thumbnail', 5);

	function bioship_skeleton_echo_thumbnail() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $wp_query, $post;

		// 1.8.0: bug out for image post format media
		if ($vthemesettings['postformatsupport'] == '1') {
			if (has_post_format('image')) {return;} // displayed by media handler
		}

		if (isset($wp_query->current_post)) {$vpostnumber = $wp_query->current_post + 1;}
		else {$vpostnumber = '';}
		// 1.8.5: allow for custom query/loop numbering override
		$vpostnumber = bioship_apply_filters('skeleton_loop_post_number', $vpostnumber);

		// 1.5.0: improved thumbnail function
		$vpostid = $post->ID; $vposttype = get_post_type();
		$vthumbnail = bioship_skeleton_get_thumbnail($vpostid, $vposttype, $vpostnumber);

		// only trigger template wrapper actions if there is thumbnail content
		if ($vthumbnail != '') {
			bioship_do_action('bioship_before_thumbnail');
				echo $vthumbnail;
			bioship_do_action('bioship_after_thumbnail');
		}
	}
}

// Get Thumbnail for Templates
// ---------------------------
// 1.5.0: moved here as separate (from content template)
if (!function_exists('bioship_skeleton_get_thumbnail')) {
	function bioship_skeleton_get_thumbnail($vpostid, $vposttype, $vpostnumber, $vthumbsize='') {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings; $vthumbnail = ''; $vmethod = '';
		$vwrapperclasses = 'thumbnail thumbnail-'.$vpostnumber;

		// check for the thumbnail image and get sizes etc
		if (is_archive() || is_search() || (!is_singular($vposttype)) ) {
			// get thumbnail size and alignment
			if ($vthumbsize == '') {$vthumbsize = $vthemesettings['listthumbsize'];}
			$vthumbsize = bioship_apply_filters('skeleton_list_thumbnail_size', $vthumbsize);
			$vthumblistalign = bioship_apply_filters('skeleton_list_thumbnail_align', $vthemesettings['thumblistalign']);
			if ( ($vthumblistalign != 'none') && ($vthumblistalign != '') ) {
				if ($vthumblistalign == 'alternateleftright') {
					if ($vpostnumber & 1 ) {$valign = 'alignleft';} else {$valign = 'alignright';}
				}
				elseif ($vthumblistalign == 'alternaterightleft') {
					if ($vpostnumber & 1 ) {$valign = 'alignright';} else {$valign = 'alignleft';}
				}
				else {$valign = $vthumblistalign;}
			}
			$vwrapperclasses .= ' '.$valign;
			$vthumbclasses = 'scale-with-grid thumbtype-'.$vposttype;
		} else {
			// set thumbnail size
			if ($vposttype == 'page') {$vthumbsize = $vthemesettings['pagethumbsize'];}
			else {$vthumbsize = $vthemesettings['postthumbsize'];}
			$vthumbsize = bioship_apply_filters('skeleton_post_thumbnail_size', $vthumbsize);

			// for custom post type filtering switch to attachment method
			// 2.0.5: test for actual change not just with has_filter
			$vnewthumbsize = bioship_apply_filters('muscle_post_thumb_size_'.$vposttype, $vthumbsize);
			if ($vnewthumbsize != $vthumbsize) {
				// custom size overrides are set to 'post-thumbnail' type
				$vmethod = 'attachment'; $vthumbsize = 'post-thumbnail';
				$vthumbclasses .= ' attachment-'.$vthumbsize;
			}

			// allow for perpost meta override
			// 1.8.5: fix to perpost image display override check
			// 1.9.5: move override to after default and filters applied
			// 2.0.8: use prefixed post meta key
			$vpostthumbsize = get_post_meta($vpostid, '_'.THEMEPREFIX.'_post_thumbsize', true);
			if (!$vpostthumbsize) {
				// 2.0.8: maybe convert old post meta key
				$voldpostmeta = get_post_meta($vpostid, '_postthumbsize', true);
				if ($voldpostmeta) {
					$vpostthumbsize = $voldpostmeta; delete_post_meta($vpostid, '_postthumbsize');
					update_post_meta($vpostid, '_'.THEMEPREFIX.'_post_thumbsize');
				}
			}

			// 2.0.5: auto-update post meta to new size names
			$vnewthumbsize = false;
			if ($vpostthumbsize == 'squared150') {$vnewthumbsize = 'bioship-150s';}
			elseif ($vpostthumbsize == 'squared250') {$vnewthumbsize = 'bioship-250s';}
			elseif ($vpostthumbsize == 'video43') {$vnewthumbsize = 'bioship-4-3';}
			elseif ($vpostthumbsize == 'video169') {$vnewthumbsize = 'bioship-16-9';}
			elseif ($vpostthumbsize == 'opengraph') {$vnewthumbsize = 'bioship-opengraph';}
			if ($vnewthumbsize) {
				update_post_meta($vpostid, '_postthumbsize', $vnewthumbsize);
				$vpostthumbsize = $vnewthumbsize;
			}
			if ($vpostthumbsize != '') {$vthumbsize = $vpostthumbsize;}

			// set thumbnail alignment and classes
			if ($vposttype == 'page') {$vthumbalign = $vthemesettings['featuredalign'];}
			else {$vthumbalign = $vthemesettings['thumbnailalign'];}
			$vthumbalign = bioship_apply_filters('skeleton_post_thumbnail_align', $vthumbalign);
			if ( ($vthumbalign != 'none') && ($vthumbalign != '') ) {
				$vwrapperclasses .= ' '.$vthumbalign;
			}
			$vthumbclasses = 'scale-with-grid thumbtype-'.$vposttype;
		}

		// maybe get the thumbnail image
		if ($vthumbsize != 'off') {
			if ($vposttype == 'page') {$vwrapperclasses .= ' featured-image';}
			else {$vwrapperclasses .= ' post-thumbnail';}
			$vwrapperclasses = bioship_apply_filters('skeleton_thumbnail_wrapper_classes', $vwrapperclasses);
			$vthumbclasses = bioship_apply_filters('skeleton_thumbnail_classes', $vthumbclasses);

			// 2.0.5: convert old size names to new prefixed ones
			if ($vthumbsize == 'squared150') {$vthumbsize = 'bioship-150s';}
			if ($vthumbsize == 'squared250') {$vthumbsize = 'bioship-250s';}
			if ($vthumbsize == 'video43') {$vthumbsize = 'bioship-4-3';}
			if ($vthumbsize == 'video169') {$vthumbsize = 'bioship-16-9';}
			if ($vthumbsize == 'opengraph') {$vthumbsize = 'bioship-opengraph';}

			// 2.0.5: maybe regenerate thumbnails (in case of a new size)
			bioship_regenerate_thumbnails($vpostid, $vthumbsize);

			if (THEMECOMMENTS) {$vthumbnail .= '<!-- .thumbnail'.$vpostnumber.' -->';}
			$vthumbnail .= '<div id="postimage-'.$vpostid.'" class="'.$vwrapperclasses.'">'.PHP_EOL;
			// use Hybrid get_the_image extension with fallback to skeleton_thumbnailer
			if ( (THEMEHYBRID) && ($vthemesettings['hybridthumbnails'] == '1') ) {
				$vargs = array('post_id' => $vpostid, 'size' => $vthumbsize,
							   'image_class' => $vthumbclasses, 'echo' => false);
				if ($vmethod == 'attachment') {$vargs['method'] = 'attachment';}
				$vthumbnail .= get_the_image($vargs);
			} else {
				$vthumbnail .= bioship_skeleton_thumbnailer($vpostid, $vthumbsize, $vthumbclasses, $vmethod);
			}
			$vthumbnail .= PHP_EOL.'</div>'.PHP_EOL;
			if (THEMECOMMENTS) {$vthumbnail .= '<!-- /.thumbnail'.$vpostnumber.' -->'.PHP_EOL;}
			$vthumbnail .= PHP_EOL;
		}

		bioship_debug("Thumbnail Size", $vthumbsize);
		$vthumbnail = bioship_apply_filters('skeleton_thumbnail_override', $vthumbnail);
		return $vthumbnail;
	}
}

// Skeleton Thumbnailer
// --------------------
// 1.3.0: no longer a Skeleton content filter
// 1.5.0: changed to more general classes and added method
if (!function_exists('bioship_skeleton_thumbnailer')) {
	function bioship_skeleton_thumbnailer($vpostid, $vthumbsize, $vthumbclasses, $vmethod='') {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		// $vclasses .= ' scale-with-grid'; // now by default in templates
		if (has_post_thumbnail($vpostid)) {
			// 1.5.0: added attachment method support for CPTs
			if ($vmethod == 'attachment') {
				$vattachmentid = get_post_thumbnail_id($vpostid);
				if ($vattachmentid) {
					// get the attachment image with alt attributes
					// via wp_get_attachment_image codex example
					$vattributes = array(
						'class'	=> $vthumbclasses,
						'alt'   => trim(strip_tags(get_post_meta($vattachmentid, '_wp_attachment_image_alt', true)))
					);
					$vimage = wp_get_attachment_image($vattachmentid, $vthumbsize, false, $vattributes);
					return $vimage;
				}
			}
			// 2.0.5: simplified fallback
			// simpler default method
			$vimage = get_the_post_thumbnail($vpostid, $vthumbsize, array('class'=>$vthumbclasses));
			return $vimage;
		}
	}
}

// ------------------
// === Author Bio ===
// ------------------

// Echo Author Bio Action
// ----------------------
// 1.9.8: abstracted for bottom and top
if (!function_exists('bioship_skeleton_echo_author_bio')) {
	function bioship_skeleton_echo_author_bio($vposition) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// 2.0.5: undefined variable warning fix
		$vauthorbio = false;
		if (is_author()) {$vauthorbio = bioship_skeleton_author_bio_box('archive', 'archive', $vposition);}
		elseif (is_singular()) {
			global $post; $vpostid = $post->ID; $vposttype = $post->post_type;
			$vauthorbio = bioship_skeleton_author_bio_box($vpostid, $vposttype, $vposition);
		}

		if ($vauthorbio) {
			bioship_do_action('bioship_before_author_bio');
				bioship_locate_template(array('content/author-bio.php'), true);
			bioship_do_action('bioship_after_author_bio');
		}
	}
}

// Echo Author Bio Action (Top)
// ---------------------------
// 1.9.8: abstracted call for top and bottom
if (!function_exists('bioship_skeleton_echo_author_bio_top')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_author_bio_top', 'bioship_skeleton_echo_author_bio_top', 5);
	// 1.9.0: add author bio to author archive top?
	// bioship_add_action('bioship_before_author', 'bioship_skeleton_echo_author_bio_top', 5);

	function bioship_skeleton_echo_author_bio_top() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_echo_author_bio('top');
	}
}

// Echo Author Bio Action (Bottom)
// -------------------------------
// 1.9.8: abstracted call
if (!function_exists('bioship_skeleton_echo_author_bio_bottom')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_author_bio_bottom', 'bioship_skeleton_echo_author_bio_bottom', 5);
	// 1.9.0: add author bio to author archive bottom?
	// bioship_add_action('bioship_after_author', 'bioship_skeleton_echo_author_bio_bottom', 5);

	function bioship_skeleton_echo_author_bio_bottom() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		bioship_skeleton_echo_author_bio('bottom');
	}
}

// Author Bio Box
// --------------
// 1.5.0: separated from content template
// if author has a description, show a bio on their entries
if (!function_exists('bioship_skeleton_author_bio_box')) {
	function bioship_skeleton_author_bio_box($vpostid, $vposttype, $vposition) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings, $vthemedisplay;

		// author must have filled in their bio description
		if (!get_the_author_meta('description')) {return false;}

		if ( ($vpostid == 'archive') && ($vposttype == 'archive') ) {
			// TODO: add archive option for author bio position

			return false;
		} else {
			// check whether global show is on and filter
			// 1.8.0: fix to showbox filter variable
			$vshowbox = $vthemesettings['authorbiocpts'][$vposttype];
			$vshowbox = bioship_apply_filters('skeleton_author_bio_box',$vshowbox);
			if (!$vshowbox) {return false;}

			// check the default position and filter
			$vbiopos = $vthemesettings['authorbiopos'];
			$vbiopos = bioship_apply_filters('skeleton_author_bio_box_position', $vbiopos);
			if ( ($vposition == 'top') && (!strstr($vbiopos,'top')) ) {return false;}
			if ( ($vposition == 'bottom') && (!strstr($vbiopos,'bottom')) ) {return false;}

			// 1.9.9: removed old meta check
			return true;
		}
	}
}

// About Author Text
// -----------------
// 1.5.0: moved from author-bio.php
if (!function_exists('bioship_skeleton_about_author_title')) {
	function bioship_skeleton_about_author_title() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 1.8.0: use separately to get author display name
		// 2.0.5: fix to typo (vauthordosplay) :-/
		// 2.0.8: fix for non-singular display usage
		if (is_singular()) {
			global $post; $vauthordisplay = bioship_get_author_display_by_post($post->ID);
			$vboxtitle = esc_attr(sprintf( __('About %s', 'bioship'), $vauthordisplay));
		} else {$vboxtitle = __('About the Author','bioship');}
	 	// 2.0.5: fix to fatal function typo (apply_filter)
		$vboxtitle = bioship_apply_filters('skeleton_about_author_text', $vboxtitle);
		return $vboxtitle; // .meta-prep-author ?
	}
}

// About Author Description
// ------------------------
// 2.0.5: separated to add filter
// 2.0.8: fix to incorrect function prefix (missing _skeleton)
if (!function_exists('bioship_skeleton_about_author_description')) {
	function bioship_skeleton_about_author_description() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 2.0.8: fix to get description outside the content loop
		// 2.0.8: fix to singular post check for post object
		$vpostid = false;
		if (is_singular()) {global $post; $vpostid = $post->ID;}
		$vauthor = bioship_get_author_by_post($vpostid);
		if (!$vauthor) {return;}
		$vauthordesc = get_the_author_meta('description', $vauthor->ID);
		$vauthordesc = bioship_apply_filters('skeleton_about_author_description', $vauthordesc);
		return $vauthordesc;
	}
}

// Author Posts Text
// -----------------
// 1.5.0: moved from author-bio.php
// 1.8.0: fix for missing author URL
if (!function_exists('bioship_skeleton_author_posts_link')) {
	function bioship_skeleton_author_posts_link($vauthorurl=false) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		$vpostid = false; $vposttype = false;
		if (is_singular()) {global $post; $vposttype = $post->post_type; $vpostid = $post->ID;}

		// 2.0.8: fix for possible missing author URL (author-bio.php template)
		$vauthor = bioship_get_author_by_post($vpostid);
		if (!$vauthor) {return;}
		if (!$vauthorurl) {$vauthorurl = get_author_posts_url($vauthor->ID);}

		// 1.5.0: use post type display name
		// 2.0.8: fix for possible
		$vposttypedisplay = false;
		if ($vposttype == 'page') {$vposttypedisplay = __('Pages','bioship');}
		elseif ($vposttype == 'post') {$vposttypedisplay = __('Posts','bioship');}
		elseif ($vposttype) {
			// 1.8.0: use the plural name not the singular one
			// $vposttypedisplay = $vposttypeobject->labels->singular_name;
			$vposttypeobject = get_post_type_object($vposttype);
			$vposttypedisplay = $vposttypeobject->labels->name;
		} else {$vposttypedisplay = __('Writings','bioship');}
		$vposttypedisplay = bioship_apply_filters('skeleton_post_type_display', $vposttypedisplay);
		// 2.0.8: bug out if unable to get valid post type display label
		if (!$vposttypedisplay) {return false;}

		// 1.8.0: use separately to get author display name
		// 2.0.8: bug out if unable to get valid author display name
		$vauthordisplay = bioship_get_author_display($vauthor);
		if (!$vauthordisplay) {return false;}

		// 1.5.5: fix to translations here for theme check
		$vanchor = sprintf( __('View all','bioship').' '.$vposttypedisplay.' '.__('by','bioship').' %s <span class="meta-nav">&rarr;</span>', $vauthordisplay );
		$vanchor = bioship_apply_filters('skeleton_author_posts_anchor', $vanchor);
		if (!$vanchor) {return false;}

		// 1.8.5: class attribute override fix
		$vattributes = hybrid_get_attr('entry-author', '', array('class' => 'author vcard entry-author'));
		$vauthorlink = '<span '.$vattributes.'>'.PHP_EOL;
		$vauthorlink .= '	<a class="url fn n" href="'.$vauthorurl.'">'.$vanchor.'</a>'.PHP_EOL;
		$vauthorlink .= '</span>'.PHP_EOL;

		// 2.0.8: added override filter for author link HTML
		$vauthorlink = bioship_apply_filters('skeleton_author_link_html', $vauthorlink);
		return $vauthorlink;
	}
}

// ----------------
// === Comments ===
// ----------------

// Echo Comments Action Hook
// -------------------------
if (!function_exists('bioship_skeleton_echo_comments')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_comments', 'bioship_skeleton_echo_comments', 5);

	function bioship_skeleton_echo_comments() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// note: comments template filter is located in functions.php
		// 1.5.0: Loads the comments template (default /comments.php)
		if ( (have_comments()) || (comments_open()) ) {
			comments_template('/comments.php', true);
		} else {
			$vcommentsclosedtext = bioship_apply_filters('skeleton_comments_closed_text', '');
			bioship_html_comment('.commentclosed');
			echo '<p class="commentsclosed">'.$vcommentsclosedtext.'</p>';
			bioship_html_comment('/.commentsclosed');
		}
	}
}

// Skeleton Comments Callback
// --------------------------
// wp_list_comments callback called in comments.php
if (!function_exists('bioship_skeleton_comments')) {
	function bioship_skeleton_comments($comment, $args, $depth) {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		global $vthemesettings;

		// 1.8.5: added comment edit/reply link buttons option
		if ( (isset($vthemesettings['commentbuttons'])) && ($vthemesettings['commentbuttons'] == '1') ) {
			$vcommentbuttons = ' button';} else {$vcommentbuttons = '';}

		$GLOBALS['comment'] = $comment;
		$vavatarsize = bioship_apply_filters('skeleton_comments_avatar_size', 48);

		bioship_html_comment('li');
		// TODO: maybe use Hybrid comment attributes?
		// echo '<li '.hybrid_get_attr('comment').'>'.PHP_EOL;

		echo '<li '; comment_class(); echo ' id="li-comment-'; comment_ID(); echo '">'.PHP_EOL;
		echo '<div id="comment-'; comment_ID(); echo '" class="single-comment clearfix">';
			echo '<div class="comment-author vcard">'.get_avatar($comment,$vavatarsize).'</div>';
			echo '<div class="comment-meta commentmetadata">';
				if ($comment->comment_approved == '0') {
					echo '<em>'.__('Comment is awaiting moderation','bioship').'</em> <br />';
				}
				// 1.8.5: added 'on' and 'at' to string
				echo '<span class="comment-author-meta">'.__('by','bioship').' '.get_comment_author_link().'</span>';
				echo '<br><span class="comment-time">'.__('on','bioship').' '.get_comment_date().'  '.__('at','bioship').' '.get_comment_time().'</span>';
			echo '</div>';
			echo '<div class="comment-edit'.$vcommentbuttons.'">';
				edit_comment_link(__('Edit','bioship'),' ',' ');
			echo '</div>';
			echo '<div class="comment-reply'.$vcommentbuttons.'">';
				comment_reply_link(array_merge( $args, array('reply_text' => __('Reply','bioship'),'login_text' => __('Login to Comment','bioship'), 'depth' => $depth, 'max_depth' => $args['max_depth'])));
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

// Comments Popup Script
// ---------------------
if (!function_exists('bioship_skeleton_comments_popup_script')) {

	add_action('wp_footer', 'bioship_skeleton_comments_popup_script', 11);

	function bioship_skeleton_comments_popup_script() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 1.9.9: added check for theme comments popup being used
		global $vthemecommentspopup;
		if ( (!isset($vthemecommentspopup)) || (!$vthemecommentspopup) ) {return;}

		// 1.9.9: only check comments_open on singular pages
		if ( is_archive() || (is_singular() && comments_open()) ) {
			// 1.8.5: changed default from 500x500 to 640x480
			$vpopupsize = bioship_apply_filters('skeleton_comments_popup_size', array(640,480));
			// 1.8.0: added these checks to bypass possible filter errors
			if ( (!is_array($vpopupsize)) || (count($vpopupsize) != 2) ) {$vpopupsize = array(640,480);}
			if ( (!is_numeric($vpopupsize[0])) || (!is_numeric($vpopupsize[1])) ) {$vpopupsize = array(640,480);}

			// TODO: maybe replace this as deprecated since WP 4.5+ with "no alternative available" ?
			@comments_popup_script($vpopupsize);
		}
	}
}


// -------------------
// === Breadcrumbs ===
// -------------------
// 1.8.5: added Hybrid Breadcrumbs
if (!function_exists('bioship_skeleton_breadcrumbs')) {
	function bioship_skeleton_breadcrumbs() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		if (is_front_page()) {return;} // no breadcrumbs on front page
		global $vthemesettings; $vcpts = array(); $vi = 0;

		$vdisplay = false; $vbreadcrumbs = '';
		// TODO: maybe check page context here instead?
		if (is_singular()) {
			$vposttype = get_post_type();
			if (isset($vthemesettings['breadcrumbposttypes'])) {$vcpts = $vthemesettings['breadcrumbposttypes'];}
			$vcpts = bioship_apply_filters('skeleton_breadcrumb_post_types', $vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			bioship_debug("Breadcrumbs for Single Post Types", $vcpts);
			foreach ($vcpts as $vcpt => $vvalue) {
				if ( ($vcpt == $vposttype) && ($vvalue == '1') ) {$vdisplay = true;}
			}
		} elseif (is_archive()) {
			$vposttypes = bioship_get_post_types();
			if (!is_array($vposttypes)) {$vposttypes = array($vposttypes);}
			if (isset($vthemesettings['breadcrumbarchivetypes'])) {$vcpts = $vthemesettings['breadcrumbarchivetypes'];}
			$vcpts = bioship_apply_filters('skeleton_breadcrumb_archive_types', $vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			foreach ($vcpts as $vcpt => $vvalue) {
				if ( ($vvalue == '1') && (in_array($vcpt,$vposttypes)) ) {$vdisplay = true;}
			}
			bioship_debug("Breadcrumbs for Archive Post Types", $vcpts);
		}

		// TODO: add further options/filters for these breadcrumbs types..?
		//elseif (is_author()) {
		//	$vdisplay = true;
		//} elseif (is_search()) {
		//	$vdisplay = true;
		//} elseif (is_404()) {
		//	$vdisplay = true;
		//} elseif (is_home()) {
		//	$vdisplay = true;
		//}

		if ($vdisplay) {
			if ($vthemesettings['hybridbreadcrumbs'] == '1') {
				// TODO: auto-include the breadcrumb trail?
				// if (!function_exists('breadcrumb_trail')) {
				// 	include('breadcrumb-trail.php');
				// }

				// use Hybrid Breadcrumb Trail extension
				if (function_exists('breadcrumb_trail')) {
					// get the Hybrid breadcrumb trail HTML
					$vbreadcrumbs = breadcrumb_trail();
				}
			}
			else {
				// TODO: add a fallback method if not using Hybrid?
				$vbreadcrumbs = '';
			}
		}

		// allow for breadcrumb filter override
		$vbreadcrumbs = bioship_apply_filters('skeleton_breadcrumb_override', $vbreadcrumbs);
		if ($vbreadcrumbs != '') {
			bioship_html_comment('#breadcrumb');
			echo "<div id='breadcrumb' class='".$vposttype."-breadcrumb'>".PHP_EOL;
			echo $vbreadcrumbs.PHP_EOL."</div>".PHP_EOL;
			bioship_html_comment('/#breadcrumb');
			echo PHP_EOL;
		}
	}
}

// Check Breadcrumbs
// -----------------
// 1.8.5: added this check to hook breadcrumbs to singular/archive templates
// 1.9.8: move this check to very top so can be moved higher than before_loop
if (!function_exists('bioship_skeleton_check_breadcrumbs')) {

	add_action('wp', 'bioship_skeleton_check_breadcrumbs');

	function bioship_skeleton_check_breadcrumbs() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// 1.9.8: use new position filtered add_action method
		if (is_singular()) {bioship_add_action('bioship_before_singular', 'bioship_skeleton_breadcrumbs', 5);}
		else {bioship_add_action('bioship_before_loop', 'bioship_skeleton_breadcrumbs', 5);}
	}
}

// -----------------
// === Page Navi ===
// -----------------
// with WP Pagenavi Support
if (!function_exists('bioship_skeleton_page_navigation')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_page_navi', 'bioship_skeleton_page_navigation', 5);

	function bioship_skeleton_page_navigation() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// print_r($vthemesettings['pagenavposttypes']); // debug point
		// print_r($vthemesettings['pagenavarchivetypes']); // debug point

		// 1.5.0: filter whether to display page navigation for post / archive
		$vdisplay = false; // default to not display then check
		if (is_singular()) {
			// 1.8.0: simplified to get post type
			$vposttype = get_post_type(); $vcpts = array();
			if ( (isset($vthemesettings['pagenavposttypes'])) && (is_array($vthemesettings['pagenavposttypes'])) ) {
				foreach ($vthemesettings['pagenavposttypes'] as $vcpt => $vvalue) {if ($vvalue == '1') {$vcpts[] = $vcpt;} }
			}
			$vcpts = bioship_apply_filters('skeleton_pagenavi_post_types', $vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			if (in_array($vposttype,$vcpts)) {$vdisplay = true;}
		}
		elseif (is_archive()) {
			// 1.8.5: use new get post type helper
			$vposttypes = bioship_get_post_types(); $vcpts = array();
			if ( (isset($vthemesettings['pagenavarchivetypes'])) && (is_array($vthemesettings['pagenavarchivetypes'])) ) {
				foreach ($vthemesettings['pagenavarchivetypes'] as $vcpt => $vvalue) {if ($vvalue == '1') {$vcpts[] = $vcpt;} }
			}
			$vcpts = bioship_apply_filters('skeleton_pagenavi_archive_types', $vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			$vposttypes = bioship_get_post_types();
			if (!is_array($vposttypes)) {$vposttypes = array($vposttypes);}
			if (array_intersect($vcpts,$vposttypes)) {
				$vdisplay = true; $vposttype = $vposttypes[0]; // for labels...
			}
		} else {return;}

		// TODO: maybe add display options for other page contexts?

		if ($vdisplay) {

			// 1.5.0: Handle other CPT display names
			// 1.8.5: moved inside display check
			if ($vposttype == 'page') {$vposttypedisplay = __('Page','bioship');}
			elseif ($vposttype == 'post') {$vposttypedisplay = __('Post','bioship');}
			else {
				$vposttypeobject = get_post_type_object($vposttype);
				$vposttypedisplay = $vposttypeobject->labels->singular_name;
			}
			$vposttypedisplay = bioship_apply_filters('skeleton_post_type_display', $vposttypedisplay);

			// 1.8.0: left and right arrows for RTL and non-RTL display
			if (is_rtl()) {$vprevright = ' &rarr;'; $vnextleft = '&larr; ';}
			else {$vprevleft = '&larr; '; $vnextright = ' &rarr;';}

			// TODO: Images with next_image_link and previous_image_link?

			// 1.8.5: re-ordered logic
			if ( (!is_page()) && (is_singular()) ) {
				$vnextpost = get_next_post_link( '<div class="nav-next">%link</div>', $vnextleft.__('Next','bioship').' '.$vposttypedisplay.$vnextright);
				$vprevpost = get_previous_post_link( '<div class="nav-prev">%link</div>', $vprevleft.__('Previous','bioship').' '.$vposttypedisplay.$vprevright);

				// 1.8.5: added RTL switchover
				if (is_rtl()) {$vpagenav = $vnextpost.$vprevpost;}
				else {$vpagenav = $vprevpost.$vnextpost;}
			}

			// defaults to WP PageNavi plugin
			// 1.5.5: some translation fixes to pass theme check
			if (function_exists('wp_pagenavi')) {
				// 1.8.5: buffer to allow for override
				ob_start();
				if (!is_singular()) {wp_pagenavi();}
				$vpagenav = ob_get_contents(); ob_end_clean();
			} elseif (is_archive()) {
				// 1.8.0: use the plural label name
				$vposttypeobject = get_post_type_object($vposttype);
				$vposttypedisplay = $vposttypeobject->labels->name;
				$vposttypedisplay = bioship_apply_filters('skeleton_post_type_display', $vposttypedisplay);

				$vnexposts = get_next_posts_link( '<div class="nav-next">'.$vnextleft.__('Newer','bioship').' '.$vposttypedisplay.$vnextright.'</div>' );
				$vprevposts = get_previous_posts_link( '<div class="nav-prev">'.$vprevleft.__('Older','bioship').' '.$vposttypedisplay.$vprevright.'</div>' );

				// 1.8.5: added rtl switchover
				if (is_rtl()) {$vpagenav = $vnextposts.$vprevposts;}
				else {$vpagenav = $vprevposts.$vnextposts;}

				// TODO: paginate option?
				// ref: https://codex.wordpress.org/Pagination
				// $pagination = get_the_posts_pagination( array(
				//	'mid_size' => 3,
				//	'prev_text' => __('Newer', 'bioship'),
				//	'next_text' => __('Older', 'bioship'),
				// ) );

			}

			$vpagenav = bioship_apply_filters('skeleton_pagenavi_override', $vpagenav);
			if ($vpagenav != '') {
				bioship_html_comment('#nav-below');
				echo '<div id="nav-below" class="navigation">'.PHP_EOL;
					echo $vpagenav;
				echo PHP_EOL.'</div>'.PHP_EOL;
				bioship_html_comment('/#nav-below');
				echo PHP_EOL;
			}
		}
	}
}


// Paged Navigation
// ----------------
// 1.8.5: separated from page navi for paged pages
// TODO: add position hook trigger for paged nav
if (!function_exists('bioship_skeleton_paged_navi')) {
 function bioship_skeleton_paged_navi() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if (function_exists('wp_pagenavi')) {
		ob_start(); wp_pagenavi(array('type' => 'multipart'));
		$vpagednav = ob_get_contents(); ob_end_clean();
	} else {
		$vnavargs = array(
			'before' => '<div class="page-link">' . __('Pages','bioship').':',
			'after' => '</div>',
			'echo' => 0
		);
		$vpagednav = wp_link_pages($vnavargs);
	}
	$vpagednav = bioship_apply_filters('skeleton_paged_navi_override', $vpagednav);
	if ($vpagednav != '') {echo $vpagednav;}
 }
}


// --------------
// === Footer ===
// --------------
// note: skeleton_footer is hooked on wp_footer and footer functions are hooked on bioship_footer
// 2.0.5: added missing function_exists wrapper
if (!function_exists('bioship_skeleton_footer')) {
 function bioship_skeleton_footer() {bioship_do_action('bioship_footer');}
 $vposition = bioship_apply_filters('skeleton_footer_position', 0);
 add_action('wp_footer', 'bioship_skeleton_footer', $vposition);
}

// Footer Hook Order
// -----------------
// bioship_skeleton_footer_open: 	0
// bioship_skeleton_footer_extras:  2
// bioship_skeleton_footer_widgets: 4
// bioship_skeleton_footer_nav: 	6
// bioship_skeleton_footer_credits: 8
// bioship_skeleton_footer_close:  10

// Footer Wrap Open
// ----------------
if (!function_exists('bioship_skeleton_footer_open')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_open', 0);

	function bioship_skeleton_footer_open() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings, $vthemelayout;

		// 1.5.0: added footer class filter and grid class compatibility
		// 1.8.0: removed grid class compatibility (now for content grid only)
		$vclasses = array(); $vclasses[] = 'noborder';
		$vclasses = bioship_apply_filters('skeleton_footer_classes', $vclasses);
		$vfooterclasses = implode(' ', $vclasses);

		bioship_html_comment('#footer');
		$vattributes = hybrid_get_attr('footer');
		echo '<div id="footer" class="'.$vfooterclasses.'">'.PHP_EOL;
		echo '	<div id="footerpadding" class="inner">'.PHP_EOL;
		echo '		<footer '.$vattributes.'>'.PHP_EOL.PHP_EOL;
	}
}

// Footer Wrap Close
// -----------------
if (!function_exists('bioship_skeleton_footer_close')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_close', 10);

	function bioship_skeleton_footer_close() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		echo '		</footer>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		bioship_html_comment('/#footer');
		echo PHP_EOL;
	}
}

// Footer Extras HTML
// ------------------
if (!function_exists('bioship_skeleton_footer_extras')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_extras', 2);

	function bioship_skeleton_footer_extras() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		global $vthemesettings;

		// 1.6.0: removed theme option, now by filter only
		// 2.0.0: allow for usage of shorter footer extras filter name
		$vfooterextras = bioship_apply_filters('skeleton_footer_extras', '');
		$vfooterextras = bioship_apply_filters('skeleton_footer_html_extras', $vfooterextras);

		if ($vfooterextras) {
			// 1.8.0: changed #footer_extras to #footer-extras for consistency
			bioship_html_comment('#footer-extras');
			echo '<div id="footer-extras" class="footer-extras">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo $vfooterextras.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			bioship_html_comment('/#footer-extras');
			echo PHP_EOL;
		}
	}
}

// Call Footer Widgets
// -------------------
if (!function_exists('bioship_skeleton_footer_widgets')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_widgets', 4);

	function bioship_skeleton_footer_widgets() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// filterable to allow for custom post types (see filters.php)
		// default template is sidebar/footer.php
		$vfooter = bioship_apply_filters('skeleton_footer_sidebar', 'footer');
		hybrid_get_sidebar($vfooter);
	}
}

// Footer Nav Menu
// ---------------
if (!function_exists('bioship_skeleton_footer_nav')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_nav', 6);

	function bioship_skeleton_footer_nav() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

		// 2.0.9: added missing menu declaration check
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( (!isset($vthemelayout['menus']['footer'])) || (!$vthemelayout['menus']['footer']) ) {return;}

		bioship_html_comment('.footer-menu');
		$vattributes = hybrid_get_attr('menu','footer');
		echo '<div class="footer-menu" '.$vattributes.'>'.PHP_EOL;
			$vmenuargs = array(
				'theme_location'  => 'footer',
				'container'       => 'div',
				'container_id' 	  => 'footermenu',
				'menu_class'      => 'menu',
				'echo'            => true,
				'fallback_cb'     => false,
				'after'           => '',
				'depth'           => 1
			);
			// 1.8.5: added missing setting filter
			// 2.0.1: fix to filter name typo
			// 2.0.5: added _setting suffix to filter name
			$vmenuargs = bioship_apply_filters('skeleton_footer_menu_settings', $vmenuargs);
			wp_nav_menu($vmenuargs);
		echo PHP_EOL.'</div>'.PHP_EOL;
		bioship_html_comment('/.footer-menu');
		echo PHP_EOL;
	}
}

// Footer Credits Area
// -------------------
if (!function_exists('bioship_skeleton_footer_credits')) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action('bioship_footer', 'bioship_skeleton_footer_credits', 8);

	function bioship_skeleton_footer_credits() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		// calls skeleton_credit_link for default theme credits
		// 1.9.9: get initial value using skeleton_credit_link
		$vcredits = bioship_skeleton_credit_link();
		$vcredits = bioship_apply_filters('skeleton_author_credits', $vcredits);
		if ($vcredits) {
			bioship_html_comment('#footercredits');
			echo '<div id="footercredits">'.$vcredits.'</div>';
			bioship_html_comment('/#footercredits');
			echo PHP_EOL.PHP_EOL;
		}
	}
}

// Get Site Credits
// ----------------
// 1.9.9: use as direct return not as filter
if (!function_exists('bioship_skeleton_credit_link')) {
	function bioship_skeleton_credit_link(){
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		global $vthemesettings;
		if ($vthemesettings['sitecredits'] != '') {
			if ($vthemesettings['sitecredits'] == '0') {return '';}
			return $vthemesettings['sitecredits'];
		} else {
			$vsitecredits = '<div id="themecredits">';
			if (THEMECHILD) {$vsitecredits .= THEMEDISPLAYNAME.' Theme for '; $vanchor = 'BioShip';} else {$vanchor = 'BioShip Framework';}
			$vsitecredits .= '<a href="'.THEMEHOMEURL.'" title="BioShip '.__('Responsive Wordpress Theme Framework','bioship').'" target=_blank>'.$vanchor.'</a>';
			if (THEMEPARENT) {$vsitecredits .= ' '.__('by','bioship').' <a href="'.THEMESUPPORT.'" title="WordQuest Alliance" target=_blank>WordQuest</a>';}
			$vsitecredits .= '</div>';
			return $vsitecredits;
		}
	}
}

// ------------------------
// The closet is now empty.

?>