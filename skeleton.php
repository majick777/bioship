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

// Simple Skeleton theme was the initial codebase for these functions
// (deprecated SMPL Skeleton skeleton.php as ALL functions rewritten)
// original can be found in includes/deprecated/skeleton-original.php

// note: deprecated Skeleton functions with mismatched name formats)
// function smpl_recommended_plugins() {return;}
// function st_remove_wpautop($vcontent) {return $vcontent;}
// function remove_more_jump_link($vlink) {return $vlink;}
// changed to skin_typography_loader: function skeleton_typography() {return;}


// ---------------
// === Helpers ===
// ---------------
// 1.8.0: moved here from functions.php

// Word to Number Helper
// ---------------------
if (!function_exists('skeleton_word_to_number')) {
 function skeleton_word_to_number($vword) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_word_to_number',__FILE__,func_get_args());}
	$vwordnumbers = array(
		'zero'=>'0', 'one'=>'1', 'two'=>'2', 'three'=>'3', 'four'=>'4', 'five'=>'5' ,'six'=>'6',
		'seven'=>'7', 'eight'=>'8', 'nine'=>'9', 'ten'=>'10', 'eleven'=>'11', 'twelve'=>'12',
		'thirteen'=>'13', 	'fourteen'=>'14', 	'fifteen'=>'15', 	'sixteen'=>'16',
		'seventeen'=>'17', 	'eighteen'=>'18', 	'nineteen'=>'19', 	'twenty'=>'20',
		'twentyone'=>'21', 	'twentytwo'=>'22', 	'twentythree'=>'23','twentyfour'=>'24',
	);
	// 1.8.5: added check and return false for validation
	if (array_key_exists($vword,$vwordnumbers)) {return $vwordnumbers[$vword];}
	return false;
 }
}

// Number to Word Helper
// ---------------------
if (!function_exists('skeleton_number_to_word')) {
 function skeleton_number_to_word($vnumber) {
 	if (THEMETRACE) {skeleton_trace('F','skeleton_number_to_word',__FILE__,func_get_args());}
	$vnumberwords = array(
		'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight',
		'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
		'seventeen', 'eighteen', 'nineteen', 'twenty', 'twentyone', 'twentytwo', 'twentythree', 'twentyfour'
	);
	// 1.8.5: added check and return false for validation
	if (array_key_exists($vnumber,$vnumberwords)) {return $vnumberwords[$vnumber];}
	return false;
 }
}


// ---------------
// === Wrapper ===
// ---------------

// Main Wrapper Open
// -----------------
if (!function_exists('skeleton_main_wrapper_open')) {
	function skeleton_main_wrapper_open() {
		global $vthemesettings, $vthemelayout;

		// 1.8.5: use new theme layout global
		$vclasses = array(); $vclasses[] = 'container';
		$vclasses[] = $vthemelayout['gridcolumns'];

		// 1.5.0: added container class compatibility
		// 1.8.5: removed 960gs container class from main wrap
		// ...as grid compatibility classes are now only applied to content columns
		// if (isset($vthemesettings['gridcompatibility']['960gridsystem'])) {
		//	if ($vthemesettings['gridcompatibility']['960gridsystem'] == '1') {
		//		$vcolumnnumbers = skeleton_word_to_number($vthemelayout['gridcolumns']);
		//		$vcontainerclasses[] = 'container_'.$vcolumnnumbers;
		//	}
		// }

		// filter the main wrap container classes
		$vcontainerclasses = apply_filters('skeleton_container_classes',$vclasses);
		if (is_array($vcontainerclasses)) {
			$vi = 0; foreach ($vcontainerclasses as $vclass) {$vcontainerclasses[$vi] = trim($vclass); $vi++;}
			$vclasses = $vcontainerclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		if (THEMECOMMENTS) {echo '<!-- #wrap.container -->';}
		echo '<div id="wrap" class="'.$vclassstring.'">'.PHP_EOL;
		echo '	<div id="wrappadding" class="inner">'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_main_wrapper_open_position',5);
	if ($vposition > -1) {add_action('skeleton_container_open','skeleton_main_wrapper_open',$vposition);}
}

// Main Wrapper Close
// ------------------
if (!function_exists('skeleton_main_wrapper_close')) {
	function skeleton_main_wrapper_close() {
		echo '	</div>'.PHP_EOL;
		echo '</div>';
		if (THEMECOMMENTS) {echo '<!-- /#wrap.container -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_main_wrapper_close_position',5);
	if ($vposition > -1) {add_action('skeleton_container_close','skeleton_main_wrapper_close',$vposition);}
}

// Echo a Clearing Div
// -------------------
// 1.5.0: moved clear div here for flexibility
if (!function_exists('skeleton_echo_clear_div')) {
	function skeleton_echo_clear_div() {echo '<div class="clear"></div>'.PHP_EOL;}
}

// Add Clear Divs to Various Layout Points
// ---------------------------------------
// after header nav
add_action('skeleton_header','skeleton_echo_clear_div',3);
// after nav menu // 1.8.0: moved sidebar buttons inline
// add_action('skeleton_navbar','skeleton_echo_clear_div',6);
// after nav bar
add_action('skeleton_after_navbar','skeleton_echo_clear_div',0);
add_action('skeleton_after_navbar','skeleton_echo_clear_div',8);
// before footer
add_action('skeleton_before_footer','skeleton_echo_clear_div',10);
// after footer widgets
add_action('skeleton_footer','skeleton_echo_clear_div',5);
// after footer nav
add_action('skeleton_footer','skeleton_echo_clear_div',7);


// --------------
// === Header ===
// --------------

// Header Action Hooks
// -------------------
// skeleton_header_open: 	0
// skeleton_header_nav: 	2
// skeleton_header_logo:	4
// skeleton_header_widgets: 6
// skeleton_header_extras:  8
// skeleton_header_close:  10

// Header Wrappers
// --------------
// Header Wrap Open
if (!function_exists('skeleton_header_open')) {
	function skeleton_header_open() {
		global $vthemesettings, $vthemelayout;

		// 1.8.5: use new theme layout global
		$vclasses = array(); $vclasses[] = $vthemelayout['gridcolumns'];

		// 1.5.0: added header class compatibility and filter
		// 1.8.0: added alpha and omega classes to header div
		$vclasses[] = 'columns'; $vclasses[] = 'alpha'; $vclasses[] = 'omega';

		// 1.9.0: removed 960gs classes from theme grid
		// if (isset($vthemesettings['gridcompatibility']['960gridsystem'])) {
		//	if ($vthemesettings['gridcompatibility']['960gridsystem'] == '1') {
		//	 	$vcolumnnumbers = skeleton_word_to_number($vthemelayout['gridcolumns']);
		//		$vclasses[] = 'grid_'.$vcolumnnumbers;
		//	}
		// }

		$vclasses = apply_filters('skeleton_header_classes',$vclasses);
		$vheaderclasses = implode(' ',$vclasses);

		if (THEMECOMMENTS) {echo '<!-- #header -->';}
		echo '<div id="header" class="'.$vheaderclasses.'">'.PHP_EOL;
		echo '	<div id="headerpadding" class="inner">'.PHP_EOL;
		echo '		<header '.hybrid_get_attr('header').'>'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_header_open_position',0);
	if ($vposition > -1) {add_action('skeleton_header','skeleton_header_open', $vposition);}
}
// Header Wrap Close
if (!function_exists('skeleton_header_close')) {
	function skeleton_header_close() {
		echo PHP_EOL.'		</header>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '</div>';
		if (THEMECOMMENTS) {echo '<!--/#header-->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_header_close_position',10);
	if ($vposition > -1) {add_action('skeleton_header','skeleton_header_close',$vposition);}
}

// Header Nav Menu
// ---------------
if (!function_exists('skeleton_header_nav')) {
	function skeleton_header_nav() {
		// note: can use Hybrid attribute filter to add column classes
		if (THEMECOMMENTS) {echo '<!-- .header-menu -->';}
		echo '<div '.hybrid_get_attr('menu','header').'>'.PHP_EOL;

		$vheadernav = array(
			'theme_location'  => 'header',
			'container'       => 'div',
			'container_id'    => 'headermenu',
			'menu_class'      => 'menu',
			'echo'            => true,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1);
		// 1.8.5: added missing setting filter
		$vheadernav = apply_filters('skeleton_header_menu',$vheadernav);
		wp_nav_menu($vheadernav);

		echo '</div>';
		if (THEMECOMMENTS) {echo '<!-- /.header-menu -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_header_nav_position',2);
	if ($vposition > -1) {add_action('skeleton_header', 'skeleton_header_nav',$vposition);}
}

// Skeleton Header Logo
// --------------------
if (!function_exists('skeleton_logo')) {
	function skeleton_header_logo() {
		global $vthemesettings;

		$vlogourl = $vthemesettings['header_logo'];
		// 1.5.0: moved logo url filter
		$vlogourl = apply_filters('skeleton_header_logo_url',$vlogourl);
		// 1.8.5: use home_url not site_url
		$vhomeurl = esc_url(home_url('/'));
		$vblogname = apply_filters('skeleton_blog_display_name',get_bloginfo('name','display'));
		$vblogdescription = apply_filters('skeleton_blog_description',get_bloginfo('description'));

		// 1.8.0: added header logo class filter
		$vlogoclasses = apply_filters('skeleton_header_logo_classes','logo');
		if (THEMECOMMENTS) {$vlogo = '<!-- #site-logo -->';} else {$vlogo = '';}

		// 1.8.5: recombined image/text template for live previewing
		// 1.8.5: added site text / description text display checkboxes
		// 1.9.0: fix to logo logic here having separated text display
		if ($vlogourl) {$vlogoimagedisplay = '';} else {$vlogoimagedisplay = ' style="display:none;"';}

		$vsitetitle = false; $vsitedesc = false;
		if ( (isset($vthemesettings['header_texts']['sitetitle'])) && ($vthemesettings['header_texts']['sitetitle'] == '1') ) {$vsitetitle = true;}
		if ( (isset($vthemesettings['header_texts']['sitedescription'])) && ($vthemesettings['header_texts']['sitedescription'] == '1') ) {$vsitedesc = true;}

		// TODO: check post meta display overrides?
		// if (is_singular()) {}

		$vsitetitle = apply_filters('skeleton_site_title_display',$vsitetitle);
		$vsitedesc = apply_filters('skeleton_site_description_display',$vsitedesc);
		if ( (!$vsitetitle) && (!$vsitedesc) ) {$vlogotextdisplay = ' style="display:none;"';}

		// 1.8.5: fix to hybrid attributes names (_ to -)
		// 1.9.0: added filter to site-description attribute to prevent duplicate ID
		// 1.9.6: added logo-image ID not just class
		$vlogo .= '<div id="site-logo" class="'.$vlogoclasses.'">'.PHP_EOL;
		$vlogo .= '	<div class="inner">'.PHP_EOL;
		$vlogo .= '  <div class="site-logo-image"'.$vlogoimagedisplay.'>'.PHP_EOL;
		$vlogo .= '		<a class="logotype-img" href="'.$vhomeurl.'" title="'.$vblogname.' | '.$vblogdescription.'" rel="home">'.PHP_EOL;
		$vlogo .= '			<h1 id="site-title">'.PHP_EOL;
		$vlogo .= '				<img id="logo-image" class="logo-image" src="'.$vlogourl.'" alt="'.$vblogname.'" border="0">'.PHP_EOL;
		$vlogo .= '				<div class="alt-logo" style="display:none;"></div>'.PHP_EOL;
		$vlogo .= '			</h1>'.PHP_EOL;
		$vlogo .= '		</a>'.PHP_EOL;
		$vlogo .= '  </div>'.PHP_EOL;
		$vlogo .= '  <div class="site-logo-text"'.$vlogotextdisplay.'>'.PHP_EOL;
		$vlogo .= '		<h1 id="site-title-text" '.hybrid_get_attr('site-title').'>'.PHP_EOL;
		$vlogo .= '			<a class="text" href="'.$vhomeurl.'" title="'.$vblogname.' | '.$vblogdescription.'" rel="home">'.$vblogname.'</a>'.PHP_EOL;
		$vlogo .= '		</h1>'.PHP_EOL;
		$vlogo .= '		<div id="site-description">'.PHP_EOL;
		$vlogo .= '			<span class="site-desc" '.hybrid_get_attr('site-description').'>'.$vblogdescription.'</span>'.PHP_EOL;
		$vlogo .= '		</div>'.PHP_EOL;
		$vlogo .= '  </div>'.PHP_EOL;
		$vlogo .= '	</div>'.PHP_EOL;
		$vlogo .= '</div>';

		if (THEMECOMMENTS) {$vlogo .= '<!-- /#site-logo -->';}
		$vlogo .= PHP_EOL;
		$vlogo = apply_filters('skeleton_header_logo_override',$vlogo);
		echo $vlogo;
	}
	$vposition = apply_filters('skeleton_header_logo_position',4);
	if ($vposition > -1) {add_action('skeleton_header','skeleton_header_logo',$vposition);}
}

// Header Widgets
// --------------
if (!function_exists('skeleton_header_widgets')) {
	function skeleton_header_widgets() {
		// note: template filterable to allow for custom post types (see filters.php)
		// default template is sidebar/header.php
		$vheader = apply_filters('skeleton_header_sidebar','header');
		hybrid_get_sidebar($vheader);
	}
	$vposition = apply_filters('skeleton_header_widgets_position',6);
	if ($vposition > -1) {add_action('skeleton_header', 'skeleton_header_widgets',$vposition);}
}

// Header Extras
// -------------
if (!function_exists('skeleton_header_extras')) {
	function skeleton_header_extras() {
		global $vthemesettings;
		// 1.5.0: changed from skeleton_options to value filter
		// $vheaderextras = skeleton_options('header_extras','');
		// $vheaderextras = $vthemesettings['header_extras'];
		$vheaderextras = apply_filters('skeleton_header_html_extras','');
		if ($vheaderextras) {
			// 1.8.0: changed #header_extras to #header-extras for consistency, added class filter
			$vheaderextraclasses = apply_filters('skeleton_header_extras_classes','header-extras');
			if (THEMECOMMENTS) {echo '<!-- #header-extras -->';}
			echo '<div id="header-extras" class="'.$vheaderextraclasses.'">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo $vheaderextras.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '</div>';
			if (THEMECOMMENTS) {echo '<!-- /#header-extras -->';}
			echo PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_header_extras_position',8);
	if ($vposition > -1) {add_action('skeleton_header','skeleton_header_extras',$vposition);}
}

// -----------------
// === Nav Menus ===
// -----------------

// Main Menu
// ---------
// hooked on 'skeleton_navbar' called in header.php
if (isset($vthemesettings['primarymenu'])) {
 if ($vthemesettings['primarymenu'] == '1') {
	// Wrap Open
	// ---------
	if (!function_exists('skeleton_main_menu_open')) {
		function skeleton_main_menu_open() {
			// note: can filter classes using Hybrid attribute filter
			echo '<!-- #navigation --><div id="navigation" '.hybrid_get_attr('menu','primary').'>'.PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_main_menu_open_position',0);
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_main_menu_open',$vposition);}

	// Main Menu
	// ---------
	if (!function_exists('skeleton_main_menu')) {
		function skeleton_main_menu() {
			// 1.8.0: only display if a menu is assigned to primary location
			if (has_nav_menu('primary')) {
				wp_nav_menu( array( 'container_id' => 'mainmenu', 'container_class' => 'menu-header', 'theme_location' => 'primary'));
			}
		}
	}
	$vposition = apply_filters('skeleton_main_menu_position',8);
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_main_menu',$vposition);}

	// Wrap Close
	// ----------
	if (!function_exists('skeleton_main_menu_close')) {
		function skeleton_main_menu_close() {echo '</div><!-- /#navigation -->'.PHP_EOL;}
	}
	$vposition = apply_filters('skeleton_main_menu_close_position',10);
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_main_menu_close',$vposition);}
 }
}

// Main Menu Mobile Button
// -----------------------
// 1.5.0: added mobile menu button
if (!function_exists('skeleton_main_menu_mobile_button')) {
	function skeleton_main_menu_mobile_button() {

		// 1.5.5: check for perpost navigation disable
		$vhidenav = '';
		if (is_singular()) {
			global $post; $vpostid = $post->ID;
			$vhidenav = get_post_meta($vpostid,'_hidenavigation',true);
		}
		$vhidenav = apply_filters('skeleton_navigation_hide',$vhidenav);
		if ($vhidenav == '1') {return;}

		if (has_nav_menu('primary')) {
			echo '<div id="mainmenubutton" class="mobilebutton">';
			echo '<a class="button" id="mainmenushow" href="javascript:void(0);" onclick="showmainmenu();">Show Menu</a>'.PHP_EOL;
			echo '<a class="button" id="mainmenuhide" href="javascript:void(0);" onclick="hidemainmenu();" style="display:none;">Hide Menu</a>'.PHP_EOL;
			echo '</div>';
		}
	}
	$vposition = apply_filters('skeleton_main_menu_mobile_button_position',4);
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_main_menu_mobile_button',$vposition);}
}

// Secondary Menu
// --------------
// note: action 'skeleton_secondarynav' is not actually called anywhere -
// this is an auxiliary navigation bar available for custom positioning
// ...or direct firing via: do_action('skeleton_secondarynav');
if (isset($vthemesettings['secondarymenu'])) {
 if ($vthemesettings['secondarymenu'] == '1') {
	if (!function_exists('skeleton_secondary_menu')) {
		function skeleton_secondary_menu() {
			if (has_nav_menu('secondary')) {
				if (THEMECOMMENTS) {echo '<!-- #secondarymenu -->';}
				echo '<div id="secondarymenu" '.hybrid_get_attr('menu','secondary').'>'.PHP_EOL;
				echo '	<div class="inner">'.PHP_EOL;
				wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'secondary'));
				echo '	</div>'.PHP_EOL;
				echo '</div>';
				if (THEMECOMMENTS) {echo '<!-- /#secondarymenu -->';}
				echo PHP_EOL;
			}
		}
	}
	$vposition = apply_filters('skeleton_secondary_menu_position',5);
	if ($vposition > -1) {add_action('skeleton_secondarynav','skeleton_secondary_menu',$vposition);}
 }
}

// ---------------
// === Banners ===
// ---------------
// 1.8.0: added all full width banner positions
// skeleton_{position}_banner
// top: above main header area
// header: below main header area (before navbar)
// navbar: after navbar (before sidebars/content)
// footer: above main footer area
// bottom: below main footer area


// Abstract Banner Wrapper
// -----------------------
if (!function_exists('skeleton_banner_abstract')) {
 function skeleton_banner_abstract($vposition) {
 	global $post; $vbanner = '';
 	// filterable to allow for HTML / ads / shortcode / widget...
 	$vbanner = apply_filters('skeleton_'.$vposition.'_banner',$vbanner);
	// banner override if custom field value is set
 	if (is_singular()) {
 		// note: can set custom field values (for automatic image banners only)
 		// eg. _topbannerurl, _topbannerlink...
 		$vpostid = $post->ID; $vbanner = get_post_meta($vpostid,'_'.$vposition.'bannerurl',true);
 		if ($vbanner != '') {$vbanner = '<img src="'.$vbanner.'" border="0">';}
 		$vbannerlink = get_post_meta($vpostid,'_'.$vposition.'bannerlink',true);
 		if ($vbannerlink != '') {$vbanner = '<a href="'.$vbannerlink.'" target=_blank>'.$vbanner.'</a>';}
 	}
 	if ($vbanner != '') {
 		// TODO: add banner div class filter
	 	if (THEMECOMMENTS) {echo '<!-- #'.$vposition.'banner -->';}
	 	echo '<div id="'.$vposition.'banner">'.PHP_EOL;
	 	echo '	<div class="inner">'.PHP_EOL;
 		echo $vbanner.PHP_EOL;
 		echo '	</div>'.PHP_EOL;
 		echo '</div>';
 		if (THEMECOMMENTS) {echo '<!-- /#'.$vposition.'banner -->';}
 		echo PHP_EOL;
	}
 }
}

// Top Banner
// ----------
// 1.8.0: added banner position (above header)
if (!function_exists('skeleton_top_banner')) {
 function skeleton_top_banner() {skeleton_banner_abstract('top');}
 $vposition = apply_filters('skeleton_top_banner_position',2);
 if ($vposition > -1) {add_action('skeleton_before_header','skeleton_top_banner',$vposition);}
}

// Header Banner
// -------------
// 1.8.0: added banner position (below header)
if (!function_exists('skeleton_header_banner')) {
 function skeleton_header_banner() {skeleton_banner_abstract('header');}
 $vposition = apply_filters('skeleton_header_banner_position',5);
 if ($vposition > -1) {add_action('skeleton_after_header','skeleton_header_banner',$vposition);}
}

// NavBar Banner
// -------------
// 1.8.0: added banner position (under navbar)
if (!function_exists('skeleton_navbar_banner')) {
 function skeleton_navbar_banner() {skeleton_banner_abstract('navbar');}
 $vposition = apply_filters('skeleton_navbar_banner_position',4);
 if ($vposition > -1) {add_action('skeleton_after_navbar','skeleton_navbar_banner',$vposition);}
}

// Footer Banner
// -------------
// 1.8.0: added banner position (above footer)
if (!function_exists('skeleton_footer_banner')) {
 function skeleton_footer_banner() {skeleton_banner_abstract('footer');}
 $vposition = apply_filters('skeleton_footer_banner_position',5);
 if ($vposition > -1) {add_action('skeleton_before_footer','skeleton_footer_banner',$vposition);}
}

// Bottom Banner
// -------------
// 1.8.0: added banner position (below footer)
if (!function_exists('skeleton_bottom_banner')) {
 function skeleton_bottom_banner() {skeleton_banner_abstract('bottom');}
 $vposition = apply_filters('skeleton_bottom_banner_position',5);
 if ($vposition > -1) {add_action('skeleton_after_footer','skeleton_bottom_banner',$vposition);}
}


// ----------------
// === Sidebars ===
// ----------------

// Add Widget Classes for Styling
// ------------------------------
// adapted from: http://wordpress.stackexchange.com/a/54505/76440
add_filter('dynamic_sidebar_params','skeleton_add_widget_classes');
if (!function_exists('skeleton_add_widget_classes')) {
 function skeleton_add_widget_classes($vparams) {

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


// set Sidebars for Position
// -------------------------
if (!function_exists('skeleton_set_sidebar')) {
 function skeleton_set_sidebar($vposition) {

	// 1.8.5: rename global sidebaroutput to themesidebars
	global $vthemelayout, $vthemesidebars;

	$vsidebar = $vthemesidebars['sidebar'];
	$vsubsidebar = $vthemesidebars['subsidebar'];
 	if ( (!$vsidebar) && (!$vsubsidebar) ) {return;}

	// 1.9.0: use new themesidebars global
	$vsidebars = $vthemesidebars['sidebars'];
	$vcontext = $vthemesidebars['sidebarcontext'];
	$vsubcontext = $vthemsidebars['subsidebarcontext'];

 	if (!is_array($vthemesidebars['output'])) {$vthemesidebars['output'] = array('','','','');}
 	$vleftsidebar = $vsidebars[0];			$vleftoutput = $vthemesidebars['output'][0];
 	$vsubleftsidebar = $vsidebars[1];		$vsubleftoutput = $vthemesidebars['output'][1];
 	$vsubrightsidebar = $vsidebars[2];		$vsubrightoutput = $vthemesidebars['output'][2];
 	$vrightsidebar = $vsidebars[3];			$vrightoutput = $vthemesidebars['output'][3];

	// Note: Sidebar Positions: left - subleft - [content] - subright - right
	// any primary and subsidiary sidebars are already mapped to these positions
 	// if overriding use the skeleton_sidebar_layout_override filter above

 	// final fallback is to sidebar/sidebar.php via hybrid_get_sidebar
 	// this does not exist by default intentionally so shows no sidebar

 	// Prepare/Output Left Sidebars
 	// ----------------------------
 	if ($vposition == 'left') {
 		if (THEMEDEBUG) {echo "<!-- Left Sidebar Positions - Left: ".$vleftsidebar." - SubLeft: ".$vsubleftsidebar." -->";}

	 	// Left Sidebar Position
		if ($vleftsidebar != '') {
			$vleftsidebar = skeleton_sidebar_check_template($vleftsidebar,'Left');
			// prepare left sidebar position output
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vleftsidebar); $vleftoutput = ob_get_contents(); ob_end_clean();
			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vleftoutput)) === 0) {
				if (strstr($vleftsidebar,'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No Left Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- Left Sidebar Length: '.strlen($vleftoutput).' -->';}
		}

		// Subleft Sidebar Position
		if ($vsubleftsidebar != '') {
			$vsubleftsidebar = skeleton_sidebar_check_template($vsubleftsidebar,'SubLeft');
			// prepare subleft sidebar position output
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vsubleftsidebar); $vsubleftoutput = ob_get_contents(); ob_end_clean();
			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vsubleftoutput)) === 0) {
				if (strstr($vsubleftsidebar,'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No SubLeft Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- SubLeft Sidebar Length: '.strlen($vsubleftoutput).' -->';}
		}
	}

 	// Prepare/Output Right Sidebars
 	// -----------------------------
 	if ($vposition == 'right') {
 		if (THEMEDEBUG) {echo "<!-- Right Sidebar Positions - SubRight: ".$vsubrightsidebar." - Right: ".$vrightsidebar." -->";}

 	 	// Subright Sidebar Position
		if ($vsubrightsidebar != '') {
			$vsubrightsidebar = skeleton_sidebar_check_template($vsubrightsidebar,'SubRight');
			// prepare subright sidebar position output
			// 1.8.5: allow for blank sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vsubrightsidebar); $vsubrightoutput = ob_get_contents(); ob_end_clean();
			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vsubrightoutput)) === 0) {
				if (strstr($vsubrightsidebar,'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No SubRight Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- SubRight Sidebar Length: '.strlen($vsubrightoutput).' -->';}
		}

		// Right Sidebar Position
		if ($vrightsidebar != '') {
			$vrightsidebar = skeleton_sidebar_check_template($vrightsidebar,'Right');
			// prepare right sidebar position output
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			ob_start(); hybrid_get_sidebar($vrightsidebar); $vrightoutput = ob_get_contents(); ob_end_clean();
			// flag sidebar as empty if no sidebar content
			if (strlen(trim($vrightoutput)) === 0) {
				if (strstr($vrightsidebar,'sub')) {$vsubsidebar = false;} else {$vsidebar = false;}
				if (THEMEDEBUG) {echo '<!-- No Right Sidebar Content -->';}
			} elseif (THEMEDEBUG) {echo '<!-- Right Sidebar Length: '.strlen($vrightoutput).' -->';}
		}
	}

	// maybe swap mobile button positions to match sidebars
	// TODO: maybe there is an easier/better way than this?
	// ...we should check for sidebar content not just positions

	// maybe move mobile subsidebar button to left
	if ( ( (strstr($vleftsidebar,'sub')) && ($vsubleftsidebar == '') )
	  && ( (strstr($vsubleftsidebar,'sub')) && ($vleftsidebar == '') ) ) {
		if (!has_action('wp_footer','skeleton_mobile_subsidebar_button_swap')) {
			add_action('wp_footer','skeleton_mobile_subsidebar_button_swap');
			if (!function_exists('skeleton_mobile_subsidebar_button_swap')) {
			 function skeleton_mobile_subsidebar_button_swap() {
				echo "<style>#subsidebarbutton {float:left !important; margin-left:10px !important; margin-right:0px !important;}</style>";
			 }
			}
		}
	}
	// maybe move mobile sidebar button to right
	if ( ( ($vrightsidebar != '') && (!strstr($vrightsidebar,'sub')) && ($vsubrightsidebar == '') )
	  && ( ($vsubrighsidebar != '') && (!strstr($vsubrightsidebar,'sub')) && ($vrightsidebar == '') ) ) {
		if (!has_action('wp_footer','skeleton_mobile_sidebar_button_swap')) {
			add_action('wp_head','skeleton_mobile_sidebar_button_swap');
			if (!function_exists('skeleton_mobile_sidebar_button_swap')) {
			 function skeleton_mobile_sidebar_button_swap() {
				echo "<style>#sidebarbutton {float:right !important; margin-right:10px !important; margin-left:0px !important;}</style>";
			 }
			}
		}
	}

	// 1.8.5: rename to themesidebars global
	$vthemesidebars['output'] = array($vleftoutput, $vsubleftoutput, $vsubrightoutput, $vrightoutput);

	if (THEMEDEBUG) {echo "<!-- Stored Sidebars Lengths: ";
		echo strlen($vthemesidebars['output'][0]).','.strlen($vthemesidebars['output'][1]).',';
		echo strlen($vthemesidebars['output'][2]).','.strlen($vthemesidebars['output'][3]); echo " -->";
	}

	// if (THEMEDEBUG) {echo "<!-- Stored Sidebars: "; print_r($vthemesidebars['output']); echo " -->";}

 }
}

// Check Sidebar Template
// ----------------------
// a fallback template hierarchy for sidebars
if (!function_exists('skeleton_sidebar_check_template')) {
 function skeleton_sidebar_check_template($vtemplate,$vposition) {

 	global $vthemelayout, $vthemesidebars;

	// 1.9.0: bug out if blank or subblank override
	if ( ($vtemplate == 'blank') || ($vtemplate == 'subblank') ) {return $vtemplate;}

 	// 1.9.0: use new themesidebars global
	$vcontext = $vthemesidebars['sidebarcontext'];
	$vsubcontext = $vthemesidebars['subsidebarcontext'];

	// 1.8.5: changed to use get post types helper
	$vposttypes = skeleton_get_post_types();
	$vchecktemplate = false;

	// aiming to mirror WordPress page template hierarchy here (eventually)...
	// handy mini ref: https://wphierarchy.com
	// TODO: allow for specific post type ID sidebars?
	// TODO: allow for specific author (nicename/ID) sidebars?
	// TODO: allow for specific taxonomy-term (ID/slug) sidebars?

	// allow for post type archives...
	if ( ($vcontext == 'archive') || ($vsubcontext == 'subarchive') ) {
		if (!is_array($vposttypes)) {
			if ($vtemplate == 'archive') {$vsubtemplate = 'archive-'.$vposttypes;}
			if ($vtemplate == 'subarchive') {$vsubtemplate = 'subarchive-'.$vposttypes;}
			$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
			if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		}
	}
	// 1.8.5: allow for specific category slugs (and IDs)
	if ( ($vcontext == 'category') || ($vsubcontext == 'subcategory') ) {
		$vterm = get_queried_object(); $vcategory = $term->slug;
		if ($vtemplate == 'category') {$vsubtemplate = 'category-'.$vcategory;}
		if ($vtemplate == 'subcategory') {$vsubtemplate = 'subcategory-'.$vcategory;}
		$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		else {
			$vcatid = $term->term_id;
			if ($vtemplate == 'category') {$vsubtemplate = 'category-'.$vcatid;}
			if ($vtemplate == 'subcategory') {$vsubtemplate = 'subcategory-'.$vcatid;}
			$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
			if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		}
	}
	// 1.8.5: allow for specific taxonomy slugs
	if ( ($vcontext == 'taxonomy') || ($vsubcontext == 'subtaxonomy') ) {
		$vterm = get_queried_object(); $vtaxonomy = $vterm->taxonomy;
		if ($vtemplate == 'taxonomy') {$vsubtemplate = 'taxonomy-'.$vtaxonomy;}
		if ($vtemplate == 'subtaxonomy') {$vsubtemplate = 'subtaxonomy-'.$vtaxonomy;}
		$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
	}
	// 1.8.5: allow for specific tag slugs (and IDs)
	if ( ($vcontext == 'tag') || ($vsubcontext == 'subtag') ) {
		$vterm = get_queried_object(); $vtag = $vterm->slug;
		if ($vtemplate == 'tag') {$vsubtemplate = 'tag-'.$vtag;}
		if ($vtemplate == 'subtag') {$vsubtemplate = 'subtag-'.$vtag;}
		$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
		if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		else {
			$vtagid = $vterm->term_id;
			if ($vtemplate == 'tag') {$vsubtemplate = 'tag-'.$vtagid;}
			if ($vtemplate == 'subtag') {$vsubtemplate = 'subtag-'.$vtagid;}
			$vchecktemplate = skeleton_file_hierarchy('file',$vsubtemplate.'.php',array('sidebar'),array());
			if ($vchecktemplate) {$vtemplate = $vsubtemplate;}
		}
	}

	// 1.8.5: allow already checked templates
	if (!$vchecktemplate) {$vchecktemplate = skeleton_file_hierarchy('file',$vtemplate.'.php',array('sidebar'),array());}

	if ($vchecktemplate) {
		if (THEMEDEBUG) {echo '<!-- '.$vposition.' Sidebar Template Found: sidebar/'.$vtemplate.'.php -->';}
		return $vtemplate;
	}
	else {
		if (THEMEDEBUG) {echo '<!-- '.$vposition.' Sidebar Template not found (sidebar/'.$vtemplate.'.php) -->';}

		// fall back for singular post types to default sidebar
		if (is_singular()) {

			// 1.9.0: use new theme layout global
			$vsidebarmode = $vthemesidebars['sidebarmode'];
			$vsubsidebarmode = $vthemesidebars['subsidebarmode'];

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

			$vchecktemplate = skeleton_file_hierarchy('file',$vtemplate.'.php',array('sidebar'),array());
			if ($vchecktemplate) {return $vtemplate;}
		}

		// TODO: test blank sidebar behaviour
		// if substr($vtemplate,0,3)) == 'sub') {$vtemplate = 'subblank';} else {$vtemplate = 'blank';}
		$vtemplate = '';
	}

	return $vtemplate;
 }
}

// Output Sidebars at Position
// ---------------------------
if (!function_exists('skeleton_get_sidebar')) {
 function skeleton_get_sidebar($vposition) {

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

	$vleftoutput = $vthemesidebars['output'][0];
	$vsubleftoutput = $vthemesidebars['output'][1];
	$vsubrightoutput = $vthemesidebars['output'][2];
	$vrightoutput = $vthemesidebars['output'][3];

	if ($vposition == 'left') {
		if ($vleftoutput != '') {echo $vleftoutput;}
		if ($vsubleftoutput != '') {echo $vsubleftoutput;}
	}
	if ($vposition == 'right') {
		if ($vsubrightoutput != '') {echo $vsubrightoutput;}
		if ($vrightoutput != '') {echo $vrightoutput;}
	}
 }
}


// ---------------
// Primary Sidebar
// ---------------

// Sidebar Position Class to Body Tag
// ----------------------------------
// 1.8.0: rename from skeleton_sidebar_position
if (!function_exists('skeleton_sidebar_position_class')) {
	add_filter('body_class','skeleton_sidebar_position_class');
	function skeleton_sidebar_position_class($vclasses) {
		global $vthemesidebars;
		if (!$vthemesidebars['sidebar']) {return $vclasses;}

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		$vi = 0;
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vasidebar) {
			if ($vasidebar != '') {
				// note: sub prefix incidates subsidebar
				if (substr($vasidebar,0,3) != 'sub') {
					// positions: left, inner left, inner right, right
					if ( ($vi == 0) || ($vi == 1) ) {$vclasses[] = 'sidebar-left';}
					if ( ($vi == 2) || ($vi == 3) ) {$vclasses[] = 'sidebar-right';}
				}
			}
			$vi++;
		}
		return $vclasses;
	}
}

// Mobile Sidebar Display Button
// -----------------------------
// 1.5.0: added this button
if (!function_exists('skeleton_sidebar_mobile_button')) {
	function skeleton_sidebar_mobile_button() {
		global $vthemesidebars;
		if (!$vthemesidebars['sidebar']) {return;}

		if (THEMECOMMENTS) {echo '<!-- #sidebarbutton -->';}
		echo '<div id="sidebarbutton" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="sidebarshow" href="javascript:void(0);" onclick="showsidebar();">Show Sidebar</a>'.PHP_EOL;
		echo '	<a class="button" id="sidebarhide" href="javascript:void(0);" onclick="hidesidebar();" style="display:none;">Hide Sidebar</a>'.PHP_EOL;
		echo '</div>';
		echo '<div id="sidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="sidebarshowsmall" href="javascript:void(0);" onclick="showsidebar();">[+] Sidebar</a>'.PHP_EOL;
		echo '	<a class="button" id="sidebarhidesmall" href="javascript:void(0);" onclick="hidesidebar();" style="display:none;">[-] Sidebar</a>'.PHP_EOL;
		echo '</div>';
		if (THEMECOMMENTS) {echo '<!-- /#sidebarbutton -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_sidebar_mobile_button_position',2); // or 6
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_sidebar_mobile_button',$vposition);}
}

// Sidebar Wrap Open
// -----------------
// 1.5.0: skeleton_sidebar_wrap to skeleton_sidebar_open
if (!function_exists('skeleton_sidebar_open'))  {
	function skeleton_sidebar_open() {
		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global [!?! WTFF not working !?!]
		// $vsidebarcolumns = $vthemesidebars['sidebarcolumns'];
		if (THEMEDEBUG) {echo "<!-- *Sidebar Layout*";  $vsidebars = $vthemesidebars; unset($vsidebars['output']);
			print_r($vsidebars); echo "*1* -->";}

		$vsidebarcolumns = skeleton_set_sidebar_columns();

		$vclasses = array(); $vclasses[] = $vsidebarcolumns; $vclasses[] = 'columns';

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 1.8.5: note: maybe no longer necessay?
		$vi = 0;
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vasidebar) {
			if ($vasidebar != '') {
				if (substr($vasidebar,0,3) != 'sub') {
					// positions: left, subleft, subright, right
					if ($vi == 0) {$vclasses[] = 'alpha';}
					if ( ($vi == 1) && ($vsidebars[0] == '') ) {$vclasses[] = 'alpha';}
					if ( ($vi == 2) && ($vsidebars[3] == '') ) {$vclasses[] = 'omega';}
					if ($vi == 3) {$vclasses[] = 'omega';}
				}
			}
			$vi++;
		}

		// 1.8.0: added sidebar class array filter
		$vsidebarclasses = apply_filters('skeleton_sidebar_classes',$vclasses);
		if (is_array($vsidebarclasses)) {
			$vi = 0; foreach ($vsidebarclasses as $vclass) {$vsidebarclasses[$vi] = trim($vclass); $vi++;}
			$vclasses = $vsidebarclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		if (THEMECOMMENTS) {echo '<!-- #sidebar -->';}
		echo '<div id="sidebar" class="'.$vclassstring.'" role="complementary">'.PHP_EOL;
		echo '	<div id="sidebarpadding" class="inner">';

	}
	$vposition = apply_filters('skeleton_sidebar_open_position',5);
	if ($vposition > -1) {add_action('skeleton_before_sidebar','skeleton_sidebar_open',$vposition);}
}

// Sidebar Wrap Close
// ------------------
if (!function_exists('skeleton_sidebar_close')) {
	function skeleton_sidebar_close() {
		echo PHP_EOL.'	</div>';
		echo PHP_EOL.'</div>';
		if (THEMECOMMENTS) {echo '<!-- /#sidebar -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_sidebar_close_position',5);
	if ($vposition > -1) {add_action('skeleton_after_sidebar', 'skeleton_sidebar_close',$vposition);}
}


// ------------------
// Subsidiary Sidebar
// ------------------

// Add SubSidebar Class to Body Tag
// --------------------------------
// 1.8.0: renamed from skeleton_subsidebar_position
// 1.8.0: removed theme options check to allow for sidebar overrides
add_filter('body_class','skeleton_subsidebar_position_class');
function skeleton_subsidebar_position_class($vclasses) {
	global $vthemesidebars;
	if (!$vthemesidebars['subsidebar']) {return $vclasses;}

	// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
	$vi = 0;
	$vsidebars = $vthemesidebars['sidebars'];
	foreach ($vsidebars as $vasidebar) {
		if (substr($vasidebar,0,3) == 'sub') {
			// positions: left, subleft, subright, right
			if ( ($vi == 0) || ($vi == 1) ) {$vclasses[] = 'subsidebar-left';}
			if ( ($vi == 2) || ($vi == 3) ) {$vclasses[] = 'subsidebar-right';}
		}
		$vi++;
	}
	return $vclasses;
}

// Mobile Subsidebar Display Button
// --------------------------------
// 1.5.0: added this button
if (!function_exists('skeleton_subsidebar_mobile_button')) {
	function skeleton_subsidebar_mobile_button() {
		global $vthemesidebars;
		if (!$vthemesidebars['subsidebar']) {return;}

		if (THEMECOMMENTS) {echo '<!-- #subsidebarbutton -->';}
		echo '<div id="subsidebarbutton" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="subsidebarshow" href="javascript:void(0);" onclick="showsubsidebar();">Show SubBar</a>'.PHP_EOL;
		echo '	<a class="button" id="subsidebarhide" href="javascript:void(0);" onclick="hidesubsidebar();" style="display:none;">Hide SubBar</a>'.PHP_EOL;
		echo '</div>';
		echo '<div id="subsidebarbuttonsmall" class="mobilebutton">'.PHP_EOL;
		echo '	<a class="button" id="subsidebarshowsmall" href="javascript:void(0);" onclick="showsubsidebar();">[+] SubBar</a>'.PHP_EOL;
		echo '	<a class="button" id="subsidebarhidesmall" href="javascript:void(0);" onclick="hidesubsidebar();" style="display:none;">[-] SubBar</a>'.PHP_EOL;
		echo '</div>';
		if (THEMECOMMENTS) {echo '<!-- /#subsidebarbutton -->';}
	}
	$vposition = apply_filters('skeleton_subsidebar_mobile_button_position',6); // or 2
	if ($vposition > -1) {add_action('skeleton_navbar','skeleton_subsidebar_mobile_button',$vposition);}
}

// Subsidebar Wrap Open
// --------------------
if (!function_exists('skeleton_subsidebar_open')) {
	function skeleton_subsidebar_open() {
		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global [!?! WTFF not working !?!]
		// $vsubsidebarcolumns = $vthemesidebars['subsidebarcolumns'];
		if (THEMEDEBUG) {echo "<!-- *SubSidebars*"; $vsidebars = $vthemesidebars; unset($vsidebars['output']);
						print_r($vsidebars); echo "*2* -->";}

		$vsubsidebarcolumns = skeleton_set_subsidebar_columns();

		$vclasses = array(); $vclasses[] = $vsubsidebarcolumns; $vclasses[] = 'columns';

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// TESTME: may not even be necessary any more?
		$vi = 0;
		$vsidebars = $vthemesidebars['sidebars'];
		foreach ($vsidebars as $vasidebar) {
			if (substr($vasidebar,0,3) == 'sub') {
				// positions: left, subleft, subright, right
				if ($vi == 0) {$vclasses[] = 'alpha';}
				if ( ($vi == 1) && ($vsidebars[0] == '') ) {$vclasses[] = 'alpha';}
				if ( ($vi == 2) && ($vsidebars[3] == '') ) {$vclasses[] = 'omega';}
				if ($vi == 3) {$vclasses[] = 'omega';}
			}
			$vi++;
		}

 		// 1.8.0: added subsidebar class array filter
 		$vsubsidebarclasses = apply_filters('skeleton_subsidebar_classes',$vclasses);
		if (is_array($vsubsidebarclasses)) {
			$vi = 0; foreach ($vsubsidebarclasses as $vclass) {$vsubsidebarclasses[$vi] = trim($vclass); $vi++;}
			$vclasses = $vsubsidebarclasses;
		}
		$vclassstring = implode(' ',$vclasses);

		if (THEMECOMMENTS) {echo '<!-- #subsidebar -->';}
		echo '<div id="subsidebar" class="'.$vclassstring.'" role="complementary">'.PHP_EOL;
		echo '	<div id="subsidebarpadding" class="inner">'.PHP_EOL;

	}
	$vposition = apply_filters('skeleton_subsidebar_open_position',5);
	if ($vposition > -1) {add_action('skeleton_before_subsidebar', 'skeleton_subsidebar_open',$vposition);}
}

// Subsidebar Wrap Close
// ---------------------
// 1.8.0: fix from skeleton_subsidebar_wrap_close
if (!function_exists('skeleton_subsidebar_close')) {
	function skeleton_subsidebar_close() {
		echo PHP_EOL.'	</div>';
		echo PHP_EOL.'</div>';
		if (THEMECOMMENTS) {echo '<!-- /#subsidebar -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_subsidebar_wrap_close_position',5);
	if ($vposition > -1) {add_action('skeleton_after_subsidebar', 'skeleton_subsidebar_close',$vposition);}
}


// ---------------
// === Content ===
// ---------------

// WooCommerce Wrapper
// -------------------
// 1.8.0: add woocommercecontent div wrapper to woocommerce content for ease of style targeting
add_action('woocommerce_before_main_content','skeleton_woocommerce_page_wrapper_open');
function skeleton_woocommerce_page_wrapper_open() {
	if (THEMECOMMENTS) {echo '<!-- #woocommercecontent -->';} echo '<div id="woocommercecontent">';
}
add_action('woocommerce_after_main_content','skeleton_woocommerce_page_wrapper_close');
function skeleton_woocommerce_page_wrapper_close() {
	echo '</div>'; if (THEMECOMMENTS) {echo '<!-- /#woocommercecontent -->';}
}

// Content Wrap Open
// -----------------
if (!function_exists('skeleton_content_open')) {
	function skeleton_content_open() {
		global $vthemesettings, $vthemelayout, $vthemesidebars;

		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 1.9.0: use new themesidebars global
		$vsidebars = $vthemesidebars['sidebars'];
		$vleftsidebar = false; $vrightsidebar = false;
		if ( ($vsidebars[0] != '') || ($vsidebars[1] != '') ) {$vleftsidebar = true;}
		if ( ($vsidebars[2] != '') || ($vsidebars[3] != '') ) {$vrightsidebar = true;}

		// 1.5.0: replaced skeleton_options call here
		// TODO: use themelayout global content columns?
		// $vcolumns = $vthemelayout['contentcolumns'];
		$vcolumns = skeleton_content_width();

		// 1.8.0: add alpha/omega class depending on sidebar presence
		// 1.8.5: fix to double sidebar logic
		$vclasses = array(); $vclasses[0] = $vcolumns; $vclasses[1] = 'columns';
		if ( (!$vleftsidebar) && (!$vrightsidebar) ) {$vclasses[] = 'alpha'; $vclasses[] = 'omega';}
		elseif ( ($vleftsidebar) && (!$vrightsidebar) ) {$vclasses[] = 'omega';}
		elseif ( ($vrightsidebar) && (!$vleftsidebar) ) {$vclasses[] = 'alpha';}
		if (count($vclasses) > 0) {$vclasslist = implode(" ",$vclasses);}

		echo '<a id="top" name="top"></a>'; // #top id for scroll links
		if (THEMECOMMENTS) {echo '<!-- #content -->';}
		echo '<div id="content" class="'.$vclasslist.'">'.PHP_EOL;
		echo '	<div id="contentpadding" class="inner">'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_content_open_position',10);
	if ($vposition > -1) {add_action('skeleton_before_content','skeleton_content_open',$vposition);}
}

// Home (Blog) Page Content
// ------------------------
// 1.8.5: moved this template function here from loop-hybrid.php
if (!function_exists('skeleton_home_page_content')) {
 function skeleton_home_page_content() {
	$vpageid = get_option('page_for_posts');
	if ($vpageid) {
		// TODO: test if the title is really needed here?
		$vtitle = get_the_title($vpageid);
		echo '<h2 id="blogpagetitle">'.$vtitle.'</h2>';

		setup_postdata(get_page($vpageid));
		echo '<div id="blogpagecontent">'; the_content(); echo '</div>';

	}
 }
 $vposition = apply_filters('skeleton_homepage_content_position',5);
 if ($vposition > -1) {add_action('skeleton_home_page_top_html','skeleton_home_page_content',$vposition);}
}


// Echo the Excerpt via Action Hook
// --------------------------------
// 1.5.0: for no reason but to make it overrideable
if (!function_exists('skeleton_echo_the_excerpt')) {
	function skeleton_echo_the_excerpt() {the_excerpt();}
	$vposition = apply_filters('skeleton_excerpt_position',5);
	if ($vposition > -1) {add_action('skeleton_the_excerpt','skeleton_echo_the_excerpt',$vposition);}
}

// Echo the Content via Action Hook
// --------------------------------
// 1.5.0: for no reason but to make it overrideable
if (!function_exists('skeleton_echo_the_content')) {
	function skeleton_echo_the_content() {the_content();}
	$vposition = apply_filters('skeleton_content_position',5);
	if ($vposition > -1) {add_action('skeleton_the_content','skeleton_echo_the_content',$vposition);}
}

// Content Wrap Close
// ------------------
if (!function_exists('skeleton_content_close')) {
    function skeleton_content_close() {
    	echo PHP_EOL.'	</div>';
    	echo PHP_EOL.'</div>';
    	if (THEMECOMMENTS) {echo '<!-- /#content -->';}
    	echo PHP_EOL;
    	echo '<a id="bottom" name="bottom"></a>'; // #bottom id for scroll links
    }
    $vposition = apply_filters('skeleton_content_close_position',0);
    if ($vposition > -1) {add_action('skeleton_after_content','skeleton_content_close',$vposition);}
}

// Media Template Handler
// ----------------------
// 1.8.0: media handler for attachments and post formats
if (!function_exists('skeleton_attachment_media_handler')) {
	function skeleton_attachment_media_handler() {

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
			if (THEMECOMMENTS) {echo '<!-- /#attachment -->';}
			echo '<div id="attachment">'.PHP_EOL;
			if ( ($vtype == 'audio') || ($vtype == 'video') || ($vtype == 'application') ) {
				if ( (!THEMEHYBRID) && (!function_exists('hybrid_attachment')) ) {skeleton_load_hybrid_media();}
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
				echo file_get_contents($vfilepath);
				echo '</textarea></div><br>';
			}
			echo PHP_EOL.'</div>';
			if (THEMECOMMENTS) {echo '<!-- /#attachment -->';}
			echo PHP_EOL;

			// Attachment Meta
			// ---------------
			if (THEMECOMMENTS) {echo '<!-- .attachment-meta -->';}
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
			echo '</div>';
			if (THEMECOMMENTS) {echo '<!-- /.attachment-meta -->';}
			echo PHP_EOL;

			// Remove default WordPress attachment display (prepended to content)
			// TODO: check prepend_attachment filter for improving above
			remove_filter( 'the_content', 'prepend_attachment' );
			return;
		}

		// Post Formats
		// ------------
		// (audio/video/image)

		if ($vthemesettings['postformatsupport'] != '1') {return;}

		// Audio Grabber
		if ( ($vthemesettings['postformats']['audio'] == '1') && (has_post_format('audio')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {skeleton_load_hybrid_media();}
			$vaudio = hybrid_media_grabber(array('type' => 'audio', 'split_media' => true));
			if ($vaudio) {echo '<div id="post-format-media" class="post-format-audio">'.$vaudio.'</div>';}
		}

		// Video Grabber
		if ( ($vthemesettings['postformats']['video'] == '1') && (has_post_format('video')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {skeleton_load_hybrid_media();}
			$vvideo = hybrid_media_grabber(array('type' => 'video', 'split_media' => true));
			if ($vvideo) {echo '<div id="post-format-media" class="post-format-video">'.$vvideo.'</div>';}
		}

		// Image Grabber
		if ( ($vthemesettings['postformats']['image'] == '1') && (has_post_format('image')) ) {
			if ( (!THEMEHYBRID) && (!function_exists('hybrid_media_grabber')) ) {skeleton_load_hybrid_media();}
			$vimage = get_the_image(array( 'echo' => false, 'size' => 'full', 'split_content' => true, 'scan_raw' => true, 'scan' => true, 'order' => array( 'scan_raw', 'scan', 'featured', 'attachment' ) ) );
			if ($vimage) {
				echo '<div id="post-format-media" class="post-format-image">'.$vimage.'</div>';

				// TODO: maybe display image sizes/gallery..?
				// echo '<div class="entry-byline"><span class="image-sizes">';
				// printf(__( 'Sizes: %s', 'bioship'), hybrid_get_image_size_links() );
				// echo '</span></div>';

				// $gallery = gallery_shortcode( array( 'columns' => 4, 'numberposts' => 8, 'orderby' => 'rand', 'id' => get_queried_object()->post_parent, 'exclude' => get_the_ID() ) );
				// if ( !empty( $gallery ) ) {
				// 	echo '<div class="image-gallery">';
				// 	echo '<h3 class="attachment-meta-title">'.__('Gallery', 'bioship').'</h3>';
				// 	echo $gallery;
				// 	echo '</div>';
				// }
			}
		}

		// Show Gravatar for Status?
		// if (get_option('show_avatars')) {
		// 	echo '<header class="entry-header">'.get_avatar(get_the_author_meta('email'));.'</header>';
		// ]

		// TODO: Gallery?

	}
	$vposition = apply_filters('skeleton_media_handler_position',5);
	if ($vposition > -1) {add_action('skeleton_media_handler','skeleton_attachment_media_handler',$vposition);}
}


// --------------------
// === Content Meta ===
// --------------------

// ------------
// Entry Header
// ------------

// Entry Header Hooks
// ------------------
// skeleton_entry_header_open:		 0
// skeleton_entry_header_title:		 2
// skeleton_entry_header_subtitle: 	 4
// skeleton_entry_header_meta:		 6
// skeleton_entry_header_close: 	10

// Entry Header Wrappers
// ---------------------
if (!function_exists('skeleton_entry_header_open')) {
	function skeleton_entry_header_open() {
		if (THEMECOMMENTS) {echo '<!-- .entry-header -->';}
		echo '<header '.hybrid_get_attr('entry-header').'>'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_entry_header_open_position',0);
	if ($vposition > -1) {add_action('skeleton_entry_header','skeleton_entry_header_open',$vposition);}
}
if (!function_exists('skeleton_entry_header_close')) {
	function skeleton_entry_header_close() {
		echo PHP_EOL.'</header>';
		if (THEMECOMMENTS) {echo '<!-- /.entry-header -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_entry_header_close_position',10);
	if ($vposition > -1) {add_action('skeleton_entry_header','skeleton_entry_header_close',$vposition);}
}

// Entry Header Title
// ------------------
if (!function_exists('skeleton_entry_header_title')) {
	function skeleton_entry_header_title() {
		global $post; $vpostid = $post->ID; $vposttype = $post->post_type;
		// 1.5.0: use h3 instead of h2 for archive/excerpt listings
		if (is_archive() || is_search() || (!is_singular($vposttype)) ) {$vhsize = 'h3';} else {$vhsize = 'h2';}
		if (THEMECOMMENTS) {echo '<!-- .entry-title -->';}
		echo '<'.$vhsize.' '.hybrid_get_attr('entry-title').'>'.PHP_EOL;
		echo '	<a href="'; the_permalink(); echo '" rel="bookmark" itemprop="url" title="';
		printf(esc_attr__('Permalink to %s','bioship'), the_title_attribute('echo=0'));
		echo '">'.get_the_title($vpostid).'</a>'.PHP_EOL;
		echo '</'.$vhsize.'>';
		if (THEMECOMMENTS) {echo '<!-- /.entry-title -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_entry_header_title_position',2);
	if ($vposition > -1) {add_action('skeleton_entry_header','skeleton_entry_header_title',$vposition);}
}

// Entry Header Subtitle
// ---------------------
// Uses WP Subtitle plugin, still shows saved subtitle if plugin deactivated
if (!function_exists('skeleton_entry_header_subtitle')) {
	function skeleton_entry_header_subtitle() {
		global $post; $vpostid = $post->ID; $vposttype = $post->post_type;

		// 1.5.0: moved key filter here before WP subtitle check
		$vsubtitlekey = 'wps_subtitle'; // see filters.php example
		$vsubtitlekey = apply_filters('skeleton_subtitle_key',$vsubtitlekey);

		// Check for WP Subtitle Function
		if ( (function_exists('get_the_subtitle')) && ($vsubtitlekey == 'wps_subtitle') ) {
			$vsubtitle = get_the_subtitle($vpostid,'','',false);
		}
		else {$vsubtitle = get_post_meta($vpostid,$vsubtitlekey,true);}

		if ($vsubtitle != '') {
			// 1.5.0: use h4 instead of h3 for archive/excerpt listings
			if (is_archive() || is_search() || (!is_singular($vposttype)) ) {$vhsize = 'h4';} else {$vhsize = 'h3';}
			// note: there is no actual hybrid attributes for entry-subtitle
			if (THEMECOMMENTS) {echo '<!-- .entry-subtitle -->';}
			echo '<'.$vhsize.' '.hybrid_get_attr('entry-subtitle').'>'.$vsubtitle.'</'.$vhsize.'>';
			if (THEMECOMMENTS) {echo '<!-- /.entry-subtitle -->';}
			echo PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_entry_header_subtitle_position',4);
	if ($vposition > -1) {add_action('skeleton_entry_header','skeleton_entry_header_subtitle',$vposition);}
}

// Entry Header Meta/Byline
// ------------------------
if (!function_exists('skeleton_entry_header_meta')) {
	function skeleton_entry_header_meta() {
		global $vthemesettings, $post;
		$vpostid = $post->ID; $vposttype = $post->post_type;

		$vmeta = skeleton_get_entry_meta($vpostid,$vposttype,'top');
		if ($vmeta != '') {
			if (THEMECOMMENTS) {echo '<!-- .entry-meta -->';}
			echo '<div class="entry-meta entry-byline">'.PHP_EOL;
			echo $vmeta.PHP_EOL.'</div>';
			if (THEMECOMMENTS) {echo '<!-- /.entry-meta -->';}
			echo PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_entry_header_meta_position',6);
	if ($vposition > -1) {add_action('skeleton_entry_header','skeleton_entry_header_meta',$vposition);}
}

// ------------
// Entry Footer
// ------------

// Entry Footer Action Hooks
// -------------------------
// skeleton_entry_footer_open: 	 0
// skeleton_entry_footer_meta: 	 5
// skeleton_entry_footer_close: 10

// Entry Footer Wrappers
// ---------------------
if (!function_exists('skeleton_entry_footer_open')) {
	function skeleton_entry_footer_open() {
		if (THEMECOMMENTS) {echo '<!-- .entry-footer -->';}
		echo '<footer '.hybrid_get_attr('entry-footer').'>'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_entry_footer_open_position',0);
	if ($vposition > -1) {add_action('skeleton_entry_footer','skeleton_entry_footer_open',$vposition);}
}
if (!function_exists('skeleton_entry_footer_close')) {
	function skeleton_entry_footer_close() {
		echo PHP_EOL.'</footer>';
		if (THEMECOMMENTS) {echo '<!-- /.entry-footer -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_entry_footer_close_position',10);
	if ($vposition > -1) {add_action('skeleton_entry_footer','skeleton_entry_footer_close',$vposition);}
}

// Entry Footer Meta/Byline
// ------------------------
if (!function_exists('skeleton_entry_footer_meta')) {
	function skeleton_entry_footer_meta() {
		global $vthemesettings, $post;
		$vpostid = $post->ID; $vposttype = get_post_type();

		$vmeta = skeleton_get_entry_meta($vpostid,$vposttype,'bottom');
		if ($vmeta != '') {
			if (THEMECOMMENTS) {echo '<!-- .entry-utility -->';}
			echo '<div '.hybrid_get_attr('entry-utility').'>'.PHP_EOL;
			echo $vmeta.PHP_EOL.'</div>';
			if (THEMECOMMENTS) {echo '<!-- /.entry-utility -->';}
			echo PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_entry_footer_meta_position',6);
	if ($vposition > -1) {add_action('skeleton_entry_footer','skeleton_entry_footer_meta',$vposition);}
}

// Formattable Meta Replacement Output
// -----------------------------------
if (!function_exists('skeleton_get_entry_meta')) {
	function skeleton_get_entry_meta($vpostid,$vposttype,$vposition) {

		global $vpost, $vthemesettings;

		if ($vposttype == 'page') {$vmetaformat = $vthemesettings['pagemeta'.$vposition];}
		else {$vmetaformat = $vthemesettings['postmeta'.$vposition];}

		// 1.5.0 fix to this for post only list meta
		// 1.6.0 fix from is_search to is_search()
		if ($vposttype == 'post') {
			if ( (is_archive()) || (is_search()) || (!is_singular($vposttype)) ) {
				if ( ($vposition == 'top') && ($vthemesettings['listentrymetatop'] == 0) ) {$vmetaformat = '';}
				if ( ($vposition == 'bottom') && ($vthemesettings['listentrymetabottom'] == 1) ) {$vmetaformat = '';}
			}
		}

		// Apply Meta Filters (see filters.php example)
		// --------------------------------------------
		// 1.5.0: optional meta format for an archive list
		if (is_archive() || is_search() || (!is_singular($vposttype)) ) {
			$vformat = apply_filters('skeleton_list_meta_format_'.$vposition,$vmetaformat);
		} else {$vformat = apply_filters('skeleton_meta_format_'.$vposition,$vmetaformat);}

		// bug out if empty...
		if ($vformat == '') {return '';}
		// echo '<!-- Meta Format: '; print_r($vformat); echo ' -->'; // debug point

		// Add Meta Separator Span
		// -----------------------
		if (strstr($vformat,' by ')) {$vformat = str_replace(' by ',' <span class="meta-sep">by</span> ',$vformat);}
		if (strstr($vformat,' BY ')) {$vformat = str_replace(' BY ',' <span class="meta-sep">BY</span> ',$vformat);}
		if (strstr($vformat,' By ')) {$vformat = str_replace(' By ',' <span class="meta-sep">By</span> ',$vformat);}
		if (strstr($vformat,'|')) {$vformat = str_replace('|','<span class="meta-sep">|</span>',$vformat);}

		// Do Replacement Values
		// ---------------------

		// Post Format Link
		// ----------------
		if (strstr($vformat,'POSTFORMAT')) {
			if ( (strstr($vformat,'%POSTFORMAT%')) || (strstr($vformat,'%POSTFORMATLINK%'))
			  || (strstr($vformat,'#POSTFORMAT#')) || (strstr($vformat,'#POSTFORMATLINK#')) ) {
				$vpostformat = get_post_format();
				if ($vpostformat) {
					// TODO: use hybrid_post_format_link here?
					$vurl = get_post_format_link($vpostformat);
					$vpostformatlink = sprintf( '<a href="%s" class="post-format-link">%s</a>', esc_url($vurl), get_post_format_string($vpostformat) );
				}
				$vformat = str_replace('%POSTFORMAT%',$vpostformatlink,$vformat);
				$vformat = str_replace('%POSTFORMATLINK%',$vpostformatlink,$vformat);
				$vformat = str_replace('#POSTFORMAT#',$vpostformatlink,$vformat);
				$vformat = str_replace('#POSTFORMATLINK#',$vpostformatlink,$vformat);
			}
		}

		// Edit Link
		// ---------
		if (strstr($vformat,'EDIT')) {
			if ( (strstr($vformat,'%EDITLINK%')) || (strstr($vformat,'%EDIT%'))
			  || (strstr($vformat,'#EDITLINK#')) || (strstr($vformat,'#EDIT#')) ) {
				$veditlink = get_edit_post_link();
				if ($veditlink) {
					// 1.5.0: use the post type display label
					if ($vposttype == 'page') {$vposttypedisplay = __('Page','bioship');}
					elseif ($vposttype == 'post') {$vposttypedisplay = __('Post','bioship');}
					else {
						$vposttypeobject = get_post_type_object($vposttype);
						$vposttypedisplay = $vposttypeobject->labels->singular_name;
					}
					$vposttypedisplay = apply_filters('skeleton_post_type_display',$vposttypedisplay);
					$veditlink = '<span class="edit-link"><a href="'.$veditlink.'">Edit this '.$vposttypedisplay.'</a>.</span>';
				}
				$vformat = str_replace('%EDITLINK%',$veditlink,$vformat);
				$vformat = str_replace('%EDIT%',$veditlink,$vformat);
				$vformat = str_replace('#EDITLINK#',$veditlink,$vformat);
				$vformat = str_replace('#EDIT#',$veditlink,$vformat);
			}
		}

		// Permalink
		// ---------
		if ( (strstr($vformat,'%PERMALINK%')) || (strstr($vformat,'#PERMALINK#')) ) {
			$vpermalink = get_permalink();
			$vthepermalink = '<a href="'.$vpermalink.'" rel="bookmark">'.__('Permalink','bioship').'</a>';
			$vformat = str_replace('%PERMALINK%',$vpermalink,$vformat);
			$vformat = str_replace('#PERMALINK#',$vpermalink,$vformat);
		}

		// Datelink
		// --------
		if (strstr($vformat,'DATE')) {
			if ( (strstr($vformat,'%DATELINK%')) || (strstr($vformat,'#DATELINK#')) ) {
				// 1.8.0: fix to post date/time display to match passsed ID
				$vpermalink = get_permalink($vpostid);
				$vtimeformat = get_option('time_format');
				$vthetime = esc_attr(get_the_time($vtimeformat,$vpostid));
				$vdateformat = get_option('date_format');
				$vpostdate = get_the_date($vdateformat,$vpostid);
				$vthedate = '<time '.hybrid_get_attr('entry-published').'>'.$vpostdate.'</time>';

				$vdatelink = '<a href="'.$vpermalink.'" title="'.$vthetime.'" rel="bookmark"><span class="entry-date">'.$vthedate.'</span></a>';
				// $vdatelink = '<a href="'.$vpermalink.'" title="'.$vthetime.'" rel="bookmark">'.$vthedate.'</a>';
				$vformat = str_replace('%DATELINK%',$vdatelink,$vformat);
				$vformat = str_replace('#DATELINK#',$vdatelink,$vformat);
			}
		}

		// Parent Page Link
		// ----------------
		// 1.5.0: display parent page link
		if (strstr($vformat,'PARENT')) {
			if ( (strstr($vformat,'%PARENTPAGE%')) || (strstr($vformat,'%PARENTLINK%'))
			  || (strstr($vformat,'#PARENTPAGE#')) || (strstr($vformat,'#PARENTLINK#')) ) {
				if (is_page()) {
					$vparentid = $post->post_parent;
					if ($vparentid == 0) {$vpageparent = ''; $vpageparentlink = '';}
					else {
						$vparentpermalink = get_permalink($vparentid);
						$vpageparent = get_the_title($vparentid);
						$vpageparentlink = '<a href="'.$vparentpermalink.'">'.$vpageparent.'</a>';
					}
					$vformat = str_replace('%PARENTPAGE%',$vpageparent,$vformat);
					$vformat = str_replace('%PARENTLINK%',$vpageparentlink,$vformat);
					$vformat = str_replace('#PARENTPAGE#',$vpageparent,$vformat);
					$vformat = str_replace('#PARENTLINK#',$vpageparentlink,$vformat);
				}
				else {$vpageparent = ''; $vpageparentlink = '';}
			}
		}

		// Category List / Taxonomy Cats (not linked)
		// -----------------------------
		// if (strstr($vformat,'%CATS%')) {
			// TODO: get unlinked category list?
			// $vcatlist = '';
		// }

		// Category List / Taxonomy Cats (linked)
		// -----------------------------
		$vcategorylist = '';
		if ( (strstr($vformat,'%CATEGORIES%')) || (strstr($vformat,'%CATS%')) || (strstr($vformat,'%CATSLIST%'))
		  || (strstr($vformat,'#CATEGORIES#')) || (strstr($vformat,'#CATS#')) || (strstr($vformat,'#CATSLIST#')) ) {
			if ($vposttype == 'post') {$vcategorylist = get_the_category_list(', ','',$vpostid);}
			elseif ($vposttype == 'page') {$vcategorylist = '';}
			else {
				// handle CPT category terms
				$vtaxonomies = get_object_taxonomies($post);
				$vcategoryterms = array();
				if (count($vtaxonomies) > 0) {
					foreach ($vtaxonomies as $vtaxonomy) {
						if ( ($vtaxonomy != 'post_tag') && ($vtaxonomy != 'post_format') ) {
							if (THEMEHYBRID) {$vterms = hybrid_get_post_terms(array('taxonomy' => 'category', 'text' => '', 'before' => ''));}
							else {$vterms = get_the_terms($vpostid,$vtaxonomy);}
							$vcategoryterms = array_merge($vterms,$vcategoryterms);
						}
					}
					if (count($vcategoryterms) > 0) {
						$vtermlinks = array();
						foreach ($vcategoryterms as $vcategoryterm) {
							$vtermlinks[] = '<a href="'.esc_url(get_term_link($vcategoryterm->slug,'post_tag')).'">'.$vcategoryterm->name.'</a>';
						}
						$vcategorylist = implode(', ',$vtermlinks);
					}
				}
			}
			if ($vcategorylist != '') {
				// 1.8.5: use hybrid attributes entry-terms (category context)
				$vcategorylist = '<span '.hybrid_get_attr('entry-terms','category').'>'.$vcategorylist.'</span>';
				// $vcategorylist = '<span class="cat-links">'.$vcategorylist.'</span>';
				// note skeleton classes: entry-utility-prep entry-utility-prep-cat-links
			}

			$vformat = str_replace('%CATEGORIES%',$vcategorylist,$vformat);
			$vformat = str_replace('%CATS%',$vcategorylist,$vformat);
			if (strstr($vformat,'%CATSLIST%')) {
				if ($vcategorylist != '') {$vformat = str_replace('%CATSLIST%',__('Categories: ','bioship').$vcategorylist.'<br>',$vformat);}
				else {$vformat = str_replace('%CATSLIST%','',$vformat);}
			}
			$vformat = str_replace('#CATEGORIES#',$vcategorylist,$vformat);
			$vformat = str_replace('#CATS#',$vcategorylist,$vformat);
			if (strstr($vformat,'#CATSLIST#')) {
				if ($vcategorylist != '') {$vformat = str_replace('#CATSLIST#',__('Categories: ','bioship').$vcategorylist.'<br>',$vformat);}
				else {$vformat = str_replace('#CATSLIST#','',$vformat);}
			}
		}

		// Parent Category(s)
		// ------------------
		$vparentcategorylist = ''; $vparentcatlist = '';
		if ( (strstr($vformat,'%PARENTCATEGORIES%')) || (strstr($vformat,'%PARENTCATS%'))
		  || (strstr($vformat,'#PARENTCATEGORIES#')) || (strstr($vformat,'#PARENTCATS#')) ) {
			$vcategories = get_the_category($vpostid);
			if (count($vcategories) > 0) {
				$vcatparentids = array();
				foreach ($vcategories as $vcategory) {
					$vcatparentids[] = $category->category_parent;
				}
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
						}
						else {
							$vparentcategories = implode(', ',$vcategorylinks);
							$vparentcategorylist = __('Parent Categories: ','bioship').$vparentcategories;
							$vparentcats = implode(', ',$vcatlinks);
							$vparentcatlist = __('Parent Categories: ','bioship').$vparentcats;
						}
					}
				}
			}
			// 1.8.5: added hybrid attribute entry-terms (category context)
			if ($vparentcategorylist != '') {$vparentcategorylist = '<span '.hybrid_get_attr('entry-terms','category').'>'.$vparentcategorylist.'</span>';}
			if ($vparentcatlist != '') {$vparentcatlist = '<span '.hybrid_get_attr('entry-terms','category').'>'.$vparentcatlist.'</span>';}

			$vformat = str_replace('%PARENTCATEGORIES%',$vparentcategorylist,$vformat);
			$vformat = str_replace('%PARENTCATS%',$vparentcatlist,$vformat);
			$vformat = str_replace('#PARENTCATEGORIES#',$vparentcategorylist,$vformat);
			$vformat = str_replace('#PARENTCATS#',$vparentcatlist,$vformat);
		}

		// Post Tags / CPT Terms (unlinked)
		// ---------------------
		// if (strstr($vformat,'%TAGS')) {
			// TODO: get unlinked tag list?
			// $vtaglist = '';
		// }

		// Post Tags / CPT Terms (linked)
		// ---------------------
		$vposttags = '';
		if (strstr($vformat,'TAGS')) {
			if ( (strstr($vformat,'%POSTTAGS%')) || (strstr($vformat,'%TAGS%')) || (strstr($vformat,'%TAGSLIST%'))
			  || (strstr($vformat,'#POSTTAGS#')) || (strstr($vformat,'#TAGS#')) || (strstr($vformat,'#TAGSLIST#')) ) {
				if ($vposttype == 'post') {
					$vposttags = get_the_tag_list('',', ');
					$vposttags = trim($vposttags);
				}
				elseif ($vposttype == 'page') {$vposttags = '';}
				else {
					// handle CPT tag terms ('post_tag' taxonomy)...
					$vtaxonomies = get_object_taxonomies($post);
					if (in_array('post_tag',$vtaxonomies)) {
						if (THEMEHYBRID) {$vposttags = hybrid_get_post_terms(array('taxonomy' => 'post_tag', 'text' => '', 'before' => ''));}
						else {
							$vtagterms = get_the_terms($vpostid,'post_tag');
							if (count($vtagterms) > 0) {
								$vtermlinks = array();
								foreach ($vtagterms as $vtagterm) {
									$vtermlinks[] = '<a href="'.esc_url(get_term_link($vtagterm->slug,'post_tag')).'">'.$vtagterm->name.'</a>';
								}
								$vposttags = implode(', ',$vtermlinks);
							}
						}
					}
				}
				if ($vposttags != '') {
					// 1.8.5: use hybrid attribute entry-terms
					$vposttags = '<span '.hybrid_get_attr('entry-terms','post_tag').'>'.$vposttags.'</span>';
					// $vposttags = '<span class="tag-links">'.$vposttags.'</span>';
					// note skeleton classes: entry-utility-prep entry-utility-prep-tag-links
				}

				$vformat = str_replace('%POSTTAGS%',$vposttags,$vformat);
				$vformat = str_replace('%TAGS%',$vposttags,$vformat);
				if (strstr($vformat,'%TAGSLIST%')) {
					if ($vposttags != '') {$vformat = str_replace('%TAGSLIST%',__('Tagged: ','bioship').$vposttags.'<br>',$vformat);}
					else {$vformat = str_replace('%TAGSLIST%','',$vformat);}
				}
				$vformat = str_replace('#POSTTAGS#',$vposttags,$vformat);
				$vformat = str_replace('#TAGS#',$vposttags,$vformat);
				if (strstr($vformat,'#TAGSLIST#')) {
					if ($vposttags != '') {$vformat = str_replace('#TAGSLIST#',__('Tagged: ','bioship').$vposttags.'<br>',$vformat);}
					else {$vformat = str_replace('#TAGSLIST#','',$vformat);}
				}
			}
		}

		// Comments
		// --------
		if (strstr($vformat,'COMMENTS')) {
			if ( (strstr($vformat,'%COMMENTS%')) || (strstr($vformat,'%COMMENTSLINK%'))
			  || (strstr($vformat,'#COMMENTS#')) || (strstr($vformat,'#COMMENTSLINK#')) ) {
				$vnumcomments = (int)get_comments_number(get_the_ID());
				$vcommentsdisplay = '';
				if (comments_open()) {
					if ($vnumcomments === 0) {$vcommentsdisplay = number_format_i18n(0).' '.__('comments','bioship').'.';}
					elseif ($vnumcomments === 1) {$vcommentsdisplay = number_format_i18n(1).' '.__('comment','bioship').'.';}
					elseif ($vnumcomments > 1) {$vcommentsdisplay = number_format_i18n($vnumcomments).' '.__('comments','bioship').'.';}
					$vcommentsdisplay = '<span class="comments-link">'.$vcommentsdisplay.'</span>';
				}
				$vformat = str_replace('%COMMENTS%',$vcommentsdisplay,$vformat);
				$vformat = str_replace('%COMMENTSLINK%',$vcommentsdisplay,$vformat);
				$vformat = str_replace('#COMMENTS#',$vcommentsdisplay,$vformat);
				$vformat = str_replace('#COMMENTSLINK#',$vcommentsdisplay,$vformat);
			}
			if ( (strstr($vformat,'%COMMENTSPOPUP%')) || (strstr($vformat,'%COMMENTSPOPUPLINK%'))
			  || (strstr($vformat,'#COMMENTSPOPUP#')) || (strstr($vformat,'#COMMENTSPOPUPLINK#')) ) {
			  	$vcomments = '';
			  	if (comments_open()) {
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
				$vformat = str_replace('%COMMENTSPOPUP%',$vcomments,$vformat);
				$vformat = str_replace('%COMMENTSPOPUPLINK%',$vcomments,$vformat);
				$vformat = str_replace('#COMMENTSPOPUP#',$vcomments,$vformat);
				$vformat = str_replace('#COMMENTSPOPUPLINK#',$vcomments,$vformat);
			}
		}

		// Author Info
		// -----------
		// 1.8.0: disambiguate %AUTHOR% and %AUTHORURL%
		if (strstr($vformat,'AUTHOR')) {
			if ( (strstr($vformat,'%AUTHORLINK%')) || (strstr($vformat,'%AUTHORNAME%'))
			  || (strstr($vformat,'%AUTHOR%')) || (strstr($vformat,'%AUTHORURL%'))
			  || (strstr($vformat,'#AUTHORLINK#')) || (strstr($vformat,'#AUTHORNAME#'))
			  || (strstr($vformat,'#AUTHOR#')) || (strstr($vformat,'#AUTHORURL#')) ) {

				// 1.8.0: use separate function to get author display name
				$vauthordisplay = skeleton_get_author_display_by_post($vpostid);

				// 1.8.0: fix to the author posts link, add title tag
				$vauthorid = get_post_field('post_author',$vpostid);
				$vauthorurl = get_author_posts_url($vauthorid);
				if ($vposttype == 'page') {$vposttypedisplay = __('Pages','bioship');}
				elseif ($vposttype == 'post') {$vposttypedisplay = __('Posts','bioship');}
				else {
					$vposttypeobject = get_post_type_object($vposttype);
					$vposttypedisplay = $vposttypeobject->labels->name;
				}
				$vauthorpoststitle = 'View all '.$vposttypedisplay.' by '.$vauthordisplay.'.';
				$vauthoranchor = '<a href="'.$vauthorurl.'" title="'.$vauthorpoststitle.'">'.$vauthordisplay.'</a>';
				$vauthorlink = skeleton_author_posts_link($vauthorurl);

				// 1.9.8: fix to use vauthordisplay not old vauthor variable
				$vformat = str_replace('%AUTHORLINK%',$vauthorlink,$vformat);
				$vformat = str_replace('%AUTHORURL%',$vauthorurl,$vformat);
				$vformat = str_replace('%AUTHOR%',$vauthoranchor,$vformat);
				$vformat = str_replace('%AUTHORNAME%',$vauthordisplay,$vformat);
				$vformat = str_replace('#AUTHORLINK#',$vauthorlink,$vformat);
				$vformat = str_replace('#AUTHORURL#',$vauthorurl,$vformat);
				$vformat = str_replace('#AUTHOR#',$vauthoranchor,$vformat);
				$vformat = str_replace('#AUTHORNAME#',$vauthordisplay,$vformat);
			}
		}

		// add Meta Wrapper
		$vmeta = '<span class="meta-prep">';
		$vmeta .= $vformat.PHP_EOL;
		$vmeta .= '</span>'.PHP_EOL;

		// allow for complete meta override
		$vmeta = apply_filters('skeleton_meta_override_'.$vposition,$vmeta);
		return $vmeta;
	}
}

// ------------------
// === Thumbnails ===
// ------------------

// Echo Thumbnail Action Hook
// --------------------------
if (!function_exists('skeleton_echo_thumbnail')) {
	function skeleton_echo_thumbnail() {
		global $vthemesettings, $wp_query, $post;

		// 1.8.0: bug out for image post format media
		if ($vthemesettings['postformatsupport'] == '1') {
			if (has_post_format('image')) {return;} // displayed by media handler
		}

		if (isset($wp_query->current_post)) {$vpostnumber = $wp_query->current_post + 1;}
		else {$vpostnumber = '';}
		// 1.8.5: allow for custom query/loop numbering override
		$vpostnumber = apply_filters('skeleton_loop_post_number',$vpostnumber);

		// 1.5.0: improved thumbnail function
		$vpostid = $post->ID; $vposttype = get_post_type();
		$vthumbnail = skeleton_get_thumbnail($vpostid,$vposttype,$vpostnumber);

		// only trigger template wrapper actions if there is thumbnail content
		if ($vthumbnail != '') {
			do_action('skeleton_before_thumbnail');
			echo $vthumbnail;
			do_action('skeleton_after_thumbnail');
		}
	}
	// 1.8.0: added missing position filter
	$vposition = apply_filters('skeleton_thumbnail_position',5);
	if ($vposition > -1) {add_action('skeleton_thumbnail','skeleton_echo_thumbnail',$vposition);}
}

// Get Thumbnail for Templates
// ---------------------------
// 1.5.0: moved here to be a separate function (from content template)
if (!function_exists('skeleton_get_thumbnail')) {
function skeleton_get_thumbnail($vpostid, $vposttype, $vpostnumber, $vthumbsize='') {
	global $vthemesettings; $vthumbnail = ''; $vmethod = '';
	$vwrapperclasses = 'thumbnail thumbnail-'.$vpostnumber;

	// check for the thumbnail image and get sizes etc
	if (is_archive() || is_search() || (!is_singular($vposttype)) ) {
		// get thumbnail size and alignment
		if ($vthumbsize == '') {$vthumbsize = $vthemesettings['listthumbsize'];}
		$vthumbsize = apply_filters('skeleton_list_thumbnail_size',$vthumbsize);
		$vthumblistalign = apply_filters('skeleton_list_thumbnail_align',$vthemesettings['thumblistalign']);
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
	}
	else {
		// set thumbnail size
		if ($vposttype == 'page') {$vthumbsize = $vthemesettings['pagethumbsize'];}
		else {$vthumbsize = $vthemesettings['postthumbsize'];}
		$vthumbsize = apply_filters('skeleton_post_thumbnail_size',$vthumbsize);

		// for custom post type filtering switch to attachment method
		if (has_filter('muscle_post_thumb_size_'.$vposttype)) {
			// custom size overrides are set to 'post-thumbnail' type
			$vmethod = 'attachment'; $vthumbsize = 'post-thumbnail';
			$vthumbclasses .= ' attachment-'.$vthumbsize;
		}

		// allow for perpost meta override
		// 1.8.5: fix to perpost image display override check
		// 1.9.5: move override to after default and filters applied
		$vpostthumbsize = get_post_meta($vpostid,'_postthumbsize',true);
		if ($vpostthumbsize != '') {$vthumbsize = $vpostthumbsize;}

		// set thumbnail alignment and classes
		if ($vposttype == 'page') {$vthumbalign = $vthemesettings['featuredalign'];}
		else {$vthumbalign = $vthemesettings['thumbnailalign'];}
		$vthumbalign = apply_filters('skeleton_post_thumbnail_align',$vthumbalign);
		if ( ($vthumbalign != 'none') && ($vthumbalign != '') ) {
			$vwrapperclasses .= ' '.$vthumbalign;
		}
		$vthumbclasses = 'scale-with-grid thumbtype-'.$vposttype;
	}

	// maybe get the thumbnail image
	if ($vthumbsize != 'off') {
		if ($vposttype == 'page') {$vwrapperclasses .= ' featured-image';}
		else {$vwrapperclasses .= ' post-thumbnail';}
		$vwrapperclasses = apply_filters('skeleton_thumbnail_wrapper_classes',$vwrapperclasses);
		$vthumbclasses = apply_filters('skeleton_thumbnail_classes',$vthumbclasses);

		if (THEMECOMMENTS) {$vthumbnail .= '<!-- .thumbnail'.$vpostnumber.' -->';}
		$vthumbnail .= '<div id="postimage-'.$vpostid.'" class="'.$vwrapperclasses.'">'.PHP_EOL;
		// use Hybrid get_the_image extension with fallback to skeleton_thumbnailer
		if ( (THEMEHYBRID) && ($vthemesettings['hybridthumbnails'] == '1') ) {
			$vargs = array('post_id'=>$vpostid,'size'=>$vthumbsize,'image_class'=>$vthumbclasses,'echo'=>false);
			if ($vmethod == 'attachment') {$vargs['method'] = 'attachment';}
			$vthumbnail .= get_the_image($vargs);
		}
		else {$vthumbnail .= skeleton_thumbnailer($vpostid,$vthumbsize,$vthumbclasses,$vmethod);}
		$vthumbnail .= PHP_EOL.'</div>';
		if (THEMECOMMENTS) {$vthumbnail .= '<!-- /.thumbnail'.$vpostnumber.' -->';}
		$vthumbnail .= PHP_EOL;
	}

	if (THEMEDEBUG) {echo "<!-- Thumbnail Size: "; print_r($vthumbsize); echo " -->";}
	$vthumbnail = apply_filters('skeleton_thumbnail_override',$vthumbnail);
	return $vthumbnail;
} }


// Skeleton Thumbnailer
// --------------------
// 1.3.0: no longer a Skeleton content filter
// 1.5.0: changed to more general classes and added method
if (!function_exists('skeleton_thumbnailer')) {
	function skeleton_thumbnailer($vpostid,$vthumbsize,$vthumbclasses,$vmethod='') {
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
				} else {return '';}
			}
			else {
				// simpler default method
				$vimage = get_the_post_thumbnail($vpostid, $vthumbsize, array('class'=>$vthumbclasses));
			}
			return $vimage;
		}
	}
}

// ------------------
// === Author Bio ===
// ------------------

// get Author Avatar
// -----------------
if (!function_exists('skeleton_get_author_avatar')) {
 function skeleton_get_author_avatar() {
 	global $vthemesettings;
	$vavatarsize = apply_filters('skeleton_author_bio_avatar_size', $vthemesettings['authoravatarsize']);
	if (!is_numeric($vavatarsize)) {$vavatarsize = 60;}
	return get_avatar(get_the_author_meta('user_email'), $vavatarsize);
 }
}

// Get Author via Post ID
// ----------------------
// 1.8.0: added these helpers as seems no easy way
if (!function_exists('skeleton_get_author_by_post')) {
 function skeleton_get_author_by_post($vpostid) {
	$vauthorid = get_post_field('post_author',$vpostid);
	if (THEMEDEBUG) {echo '<!-- Author ID: '.$vauthorid.' -->';}
	$vauthor = get_user_by('id', $vauthorid);
	return $vauthor;
 }
}

// Get Author Display from Author Object
// -------------------------------------
if (!function_exists('skeleton_get_author_display')) {
 function skeleton_get_author_display($vauthor) {
	$vauthordisplay = trim($vauthor->data->display_name);
	if ($vauthordisplay == '') {
		$vauthordisplay = trim($vauthor->data->nice_name);
		if ($vauthordisplay == '') {$vauthordisplay = $vauthor->data->user_login;}
	}
	if (THEMEDEBUG) {echo '<!-- Author Display Name: '.$vauthordisplay.' -->';}
	return $vauthordisplay;
 }
}

// Get Author Display via Post ID
// ------------------------------
if (!function_exists('skeleton_get_author_display_by_post')) {
 function skeleton_get_author_display_by_post($vpostid) {
	$vauthor = skeleton_get_author_by_post($vpostid);
	$vauthordisplay = skeleton_get_author_display($vauthor);
	return $vauthordisplay;
 }
}

// Echo Author Bio Action (top)
// ---------------------------
if (!function_exists('skeleton_echo_author_bio_top')) {
	function skeleton_echo_author_bio_top() {

		if (is_author()) {$vauthorbio = skeleton_author_bio_box('archive','archive','top');}
		elseif (is_singular()) {
			global $post; $vpostid = $post->ID; $vposttype = $post->post_type;
			$vauthorbio = skeleton_author_bio_box($vpostid,$vposttype,'top');
		}

		if ($vauthorbio) {
			do_action('skeleton_before_author_bio');
			skeleton_locate_template(array('content/author-bio.php'), true);
			do_action('skeleton_after_author_bio');
		}
	}
	// 1.8.0: added missing position filter
	$vposition = apply_filters('skeleton_author_bio_top_position',5);
	if ($vposition > -1) {add_action('skeleton_author_bio_top','skeleton_echo_author_bio_top',$vposition);}

	// 1.9.0: add author bio to author archive top?
	// $vposition = apply_filters('skeleton_author_bio_top_position',5);
	// add_action('skeleton_before_author','skeleton_echo_author_bio_top',$vposition);
}

// Echo Author Bio Action (bottom)
// -------------------------------
if (!function_exists('skeleton_echo_author_bio_bottom')) {
	function skeleton_echo_author_bio_bottom() {

		if (is_author()) {
		 	$vauthorbio = skeleton_author_bio_box('archive','archive','top');
		}
		elseif (is_singular()) {
			global $post; $vpostid = $post->ID; $vposttype = $post->post_type;
			$vauthorbio = skeleton_author_bio_box($vpostid,$vposttype,'bottom');

		}

		if ($vauthorbio) {
			do_action('skeleton_before_author_bio');
			skeleton_locate_template(array('content/author-bio.php'), true);
			do_action('skeleton_after_author_bio');
		}
	}
	// 1.8.0: added missing position filter
	$vposition = apply_filters('skeleton_author_bio_bottom_position',5);
	if ($vposition > -1) {add_action('skeleton_author_bio_bottom','skeleton_echo_author_bio_bottom',$vposition);}

	// 1.9.0: add author bio to author archive top?
	// $vposition = apply_filters('skeleton_author_bio_top_position',5);
	// add_action('skeleton_after_author','skeleton_echo_author_bio_bottom',$vposition);
}

// Author Bio Box
// --------------
// 1.5.0: moved here as a separate function from content template
// if author has a description, show a bio on their entries
if (!function_exists('skeleton_author_bio_box')) {
	function skeleton_author_bio_box($vpostid,$vposttype,$vposition) {
		global $vthemesettings;

		if (!get_the_author_meta('description')) {return false;}

		if ( ($vpostid == 'archive') && ($vposttype == 'archive') ) {
			// TODO: add archive options for author bio position


			return false;
		}
		else {
			// check whether global show is on and filter
			// 1.8.0: fix to showbox filter variable
			$vshowbox = $vthemesettings['authorbiocpts'][$vposttype];
			$vshowbox = apply_filters('skeleton_author_bio_box',$vshowbox);
			if (!$vshowbox) {return false;}

			// check the default position and filter
			$vbiopos = $vthemesettings['authorbiopos'];
			$vbiopos = apply_filters('skeleton_author_bio_box_position',$vbiopos);
			if ( ($vposition == 'top') && (!strstr($vbiopos,'top')) ) {return false;}
			if ( ($vposition == 'bottom') && (!strstr($vbiopos,'bottom')) ) {return false;}

			// check for meta override and for an author description
			if (get_post_meta($vpostid,'_hideauthorbio') == '1') {return false;}

			return true;
		}
	}
}

// About Author Text
// -----------------
// 1.5.0: moved from author-bio.php
if (!function_exists('skeleton_about_author_title')) {
	function skeleton_about_author_title() {
		// 1.8.0: use separate function to get author display name
		global $post; $vauthordosplay = skeleton_get_author_display_by_post($post->ID);
	 	$vboxtitle = esc_attr(sprintf( __('About %s', 'bioship'), $vauthordisplay ));
		$vboxtitle = apply_filter('skeleton_about_author_text',$vboxtitle);
		return $vauthortitle; // .meta-prep-author?
	}
}

// Author Posts Text
// -----------------
// 1.5.0: moved from author-bio.php
// 1.8.0: fix for missing author URL
if (!function_exists('skeleton_author_posts_link')) {
	function skeleton_author_posts_link($vauthorurl) {
		global $post; $vposttype = $post->post_type; $vpostid = $post->ID;
		// 1.5.0: use post type display name
		if ($vposttype == 'page') {$vposttypedisplay = __('Pages','bioship');}
		elseif ($vposttype == 'post') {$vposttypedisplay = __('Posts','bioship');}
		else {
			// 1.8.0: use the plural name not the singular one
			// $vposttypedisplay = $vposttypeobject->labels->singular_name;
			$vposttypeobject = get_post_type_object($vposttype);
			$vposttypedisplay = $vposttypeobject->labels->name;
		}
		$vposttypedisplay = apply_filters('skeleton_post_type_display',$vposttypedisplay);

		// 1.8.0: use separate function to get author display name
		$vauthordisplay = skeleton_get_author_display_by_post($vpostid);

		// 1.5.5: fix to translations here for theme check
		$vanchor = sprintf( __('View all ','bioship').$vposttypedisplay.' '.__('by','bioship').' %s <span class="meta-nav">&rarr;</span>', $vauthordisplay );
		$vanchor = apply_filters('skeleton_author_posts_anchor',$vanchor);
		// 1.8.5: class attribute override fix
		$vattributes['class'] = 'author vcard entry-author';
		$vauthorlink = '<span '.hybrid_get_attr('entry-author','',$vattributes).'>'.PHP_EOL;
		$vauthorlink .= '	<a class="url fn n" href="'.$vauthorurl.'">'.$vanchor.'</a>'.PHP_EOL;
		$vauthorlink .= '</span>'.PHP_EOL;
		return $vauthorlink;
	}
}

// ----------------
// === Comments ===
// ----------------

// Echo Comments Action Hook
// -------------------------
if (!function_exists('skeleton_echo_comments')) {
	function skeleton_echo_comments() {
		// note: comments template filter is located in functions.php
		// 1.5.0: Loads the comments template (default /comments.php)
		if ( (have_comments()) || (comments_open()) ) {
			comments_template('/comments.php', true);
		} else {
			$vcommentsclosedtext = apply_filters('skeleton_comments_closed_text','');
			echo '<p class="commentsclosed">'.$vcommentsclosedtext.'</p>';
		}
	}
	// 1.8.0: added missing position filter
	$vposition = apply_filters('skeleton_comments_position',5);
	if ($vposition > -1) {add_action('skeleton_comments','skeleton_echo_comments',$vposition);}
}

// Skeleton Comments Callback
// --------------------------
// wp_list_comments callback called in comments.php
if (!function_exists('skeleton_comments')) {
	function skeleton_comments($comment, $args, $depth) {

		global $vthemesettings;

		// 1.8.5: added comment edit/reply link buttons option
		if ( (isset($vthemesettings['commentbuttons'])) && ($vthemesettings['commentbuttons'] == '1') ) {
			$vcommentbuttons = ' button';} else {$vcommentbuttons = '';}

		$GLOBALS['comment'] = $comment;
		$vavatarsize = apply_filters('skeleton_comments_avatar_size',48);

		if (THEMECOMMENTS) {echo '<!-- li -->';}
		// TODO: use Hybrid comment attributes?
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
		echo PHP_EOL.'</div>';
		if (THEMECOMMENTS) {echo '<!-- /li -->';}
		echo PHP_EOL;
	}
}

// Comments Popup Script
// ---------------------
if (!function_exists('skeleton_comments_popup_script')) {
	add_action('wp_footer','skeleton_comments_popup_script');
	function skeleton_comments_popup_script() {
		if (comments_open()) {
			$vpopupsize = apply_filters('skeleton_comments_popup_size',array(500,500));
			// 1.8.0: added these checks to bypass possible filter errors
			// 1.8.5: changed default from 500x500 to 640x480
			if ( (!is_array($vpopupsize)) || (count($vpopupsize) != 2) ) {$vpopupsize = array(640,480);}
			if ( (!is_numeric($vpopupsize[0])) || (!is_numeric($vpopupsize[1])) ) {$vpopupsize = array(640,490);}
			comments_popup_script($vpopupsize);
		}
	}
}


// -------------------
// === Breadcrumbs ===
// -------------------
// 1.8.5: added Hybrid Breadcrumbs
// TODO: fallback breadcrumb function?
if (!function_exists('skeleton_breadcrumbs')) {
 function skeleton_breadcrumbs() {
 	if (is_front_page()) {return;} // no breadcrumbs on front page
	global $vthemesettings; $vcpts = array(); $vi = 0;

	$vdisplay = false; $vbreadcrumbs = '';
	if (is_singular()) {
		$vposttype = get_post_type();
		if (isset($vthemesettings['breadcrumbposttypes'])) {$vcpts = $vthemesettings['breadcrumbposttypes'];}
		$vcpts = apply_filters('skeleton_breadcrumb_post_types',$vcpts);
		if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
		foreach ($vcpts as $vcpt => $vvalue) {
			if ( ($vcpt == $vposttype) && ($vvalue == '1') ) {$vdisplay = true;}
		}
	} elseif (is_archive()) {
		$vposttypes = skeleton_get_post_types();
		if (!is_array($vposttypes)) {$vposttypes = array($vposttypes);}
		if (isset($vthemesettings['breadcrumbarchivetypes'])) {$vcpts = $vthemesettings['breadcrumbarchivetypes'];}
		$vcpts = apply_filters('skeleton_breadcrumb_archive_types',$vcpts);
		if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
		foreach ($vcpts as $vcpt => $vvalue) {
			if ( ($vvalue == '1') && (in_array($vcpt,$vposttypes)) ) {$vdisplay = true;}
		}
	}
	// TODO: add options/filters for these breadcrumbs types...
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
	$vbreadcrumbs = apply_filters('skeleton_breadcrumb_override',$vbreadcrumbs);
	if ($vbreadcrumbs != '') {
		if (THEMECOMMENTS) {echo "<!-- #breadcrumb -->";}
		echo "<div id='breadcrumb' class='".$vposttype."-breadcrumb'>".PHP_EOL;
		echo $vbreadcrumbs.PHP_EOL."</div>".PHP_EOL;
		if (THEMECOMMENTS) {echo "<!-- /#breadcrumb -->";}
		echo PHP_EOL;
	}
 }
}

// Check Breadcrumbs
// -----------------
// 1.8.5: added this check to hook breadcrumbs to singular/archive templates
add_action('skeleton_before_loop','skeleton_check_breadcrumbs',0);
if (!function_exists('skeleton_check_breadcrumbs')) {
 function skeleton_check_breadcrumbs() {
	$vposition = apply_filters('skeleton_breadcrumb_position',5);
	if ($vposition > -1) {
		if (is_singular()) {add_action('skeleton_before_singular','skeleton_breadcrumbs',$vposition);}
		else {add_action('skeleton_before_loop','skeleton_breadcrumbs',$vposition);}
	}
 }
}

// -----------------
// === Page Navi ===
// -----------------
// with WP Pagenavi Support
if (!function_exists('skeleton_page_navigation')) {
	function skeleton_page_navigation() {

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
			$vcpts = apply_filters('skeleton_pagenavi_post_types',$vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			if (in_array($vposttype,$vcpts)) {$vdisplay = true;}
		}
		elseif (is_archive()) {
			// 1.8.5: use new get post type helper
			$vposttypes = skeleton_get_post_types(); $vcpts = array();
			if ( (isset($vthemesettings['pagenavarchivetypes'])) && (is_array($vthemesettings['pagenavarchivetypes'])) ) {
				foreach ($vthemesettings['pagenavarchivetypes'] as $vcpt => $vvalue) {if ($vvalue == '1') {$vcpts[] = $vcpt;} }
			}
			$vcpts = apply_filters('skeleton_pagenavi_archive_types',$vcpts);
			if ( (!is_array($vcpts)) || (count($vcpts) == 0) ) {return;}
			$vposttypes = skeleton_get_post_types();
			if (!is_array($vposttypes)) {$vposttypes = array($vposttypes);}
			if (array_intersect($vcpts,$vposttypes)) {
				$vdisplay = true; $vposttype = $vposttypes[0]; // for labels...
			}
		} else {return;}
		// TODO: maybe add display options for other contexts?

		if ($vdisplay) {

			// 1.5.0: Handle other CPT display names
			// 1.8.5: moved inside display check
			if ($vposttype == 'page') {$vposttypedisplay = __('Page','bioship');}
			elseif ($vposttype == 'post') {$vposttypedisplay = __('Post','bioship');}
			else {
				$vposttypeobject = get_post_type_object($vposttype);
				$vposttypedisplay = $vposttypeobject->labels->singular_name;
			}
			$vposttypedisplay = apply_filters('skeleton_post_type_display',$vposttypedisplay);

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
			}
			elseif (is_archive()) {

				// 1.8.0: use the plural label name
				$vposttypeobject = get_post_type_object($vposttype);
				$vposttypedisplay = $vposttypeobject->labels->name;
				$vposttypedisplay = apply_filters('skeleton_post_type_display',$vposttypedisplay);

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

			$vpagenav = apply_filters('skeleton_pagenavi_override',$vpagenav);
			if ($vpagenav != '') {
				if (THEMECOMMENTS) {echo '<!-- #nav-below -->';}
				echo '<div id="nav-below" class="navigation">'.PHP_EOL;
				echo $vpagenav;
				echo PHP_EOL.'</div>';
				if (THEMECOMMENTS) {echo '<!-- /#nav-below -->';}
				echo PHP_EOL;
			}
		}
	}
	$vposition = apply_filters('skeleton_page_navi_position',5);
	if ($vposition > -1) {add_action('skeleton_page_navi','skeleton_page_navigation',$vposition);}
}


// Paged Navigation
// ----------------
// 1.8.5: separated from page navi for paged pages
// TODO: add position hook trigger for paged nav
if (!function_exists('skeleton_paged_navi')) {
 function skeleton_paged_navi() {
	if (function_exists('wp_pagenavi')) {
		ob_start(); wp_pagenavi(array('type' => 'multipart'));
		$vpagednav = ob_get_contents(); ob_end_clean();
	} else {
		$vpagednav = wp_link_pages( array( 'before' => '<div class="page-link">' . __('Pages','bioship').':', 'after' => '</div>', 'echo' => 0 ) );
	}
	$vpagednav = apply_filters('skeleton_paged_navi_override',$vpagednav);
	if ($vpagednav != '') {echo $vpagednav;}
 }
}


// --------------
// === Footer ===
// --------------
// skeleton_footer is hooked on wp_footer
// and footer functions hooked on skeleton_footer
function skeleton_footer() {do_action('skeleton_footer');}
$vposition = apply_filters('skeleton_footer_position',0);
if (!is_admin()) {add_action('wp_footer','skeleton_footer',$vposition);}

// Footer Hook Order
// -----------------
// skeleton_footer_open: 	0
// skeleton_footer_extras:  2
// skeleton_footer_widgets: 4
// skeleton_footer_nav: 	6
// skeleton_footer_credits: 8
// skeleton_footer_close:  10

// Skeleton Footer Wrappers
// ------------------------
// Footer Wrap Open
if (!function_exists('skeleton_footer_wrap_open')) {
	function skeleton_footer_wrap_open() {
		global $vthemesettings, $vthemelayout; $vclasses = array();

		// 1.5.0: added footer class compatibility and filter
		// $vfooterwidgets = skeleton_count_footer_widgets();
		// $vclasses[] = ($vfooterwidgets == '0' ? 'noborder' : 'normal');
		// $vclasses[] = $vthemelayout['gridcolumns'];
		// $vclasses[] = 'columns';
		// if ($vthemesettings['gridcompatibility']['960gridsystem'] == '1') {
		//	$vcolumnnumbers = skeleton_word_to_number($vthemelayout['gridcolumns']);
		//	$vclasses[] = 'grid_'.$vcolumnnumbers;
		// }

		$vclasses[] = 'noborder';
		$vclasses = apply_filters('skeleton_footer_classes',$vclasses);
		$vfooterclasses = implode(' ',$vclasses);

		if (THEMECOMMENTS) {echo '<!-- #footer -->';}
		echo '<div id="footer" class="'.$vfooterclasses.'">'.PHP_EOL;
		echo '	<div id="footerpadding" class="inner">'.PHP_EOL;
		echo '		<footer '.hybrid_get_attr('footer').'>'.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_footer_wrap_open_position',0);
	if ($vposition > -1) {add_action('skeleton_footer', 'skeleton_footer_wrap_open',$vposition);}
}
// Footer Wrap Close
if (!function_exists('skeleton_footer_wrap_close')) {
	function skeleton_footer_wrap_close() {
		echo '		</footer>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '</div>';
		if (THEMECOMMENTS) {echo '<!-- /#footer -->';}
		echo PHP_EOL.PHP_EOL;
	}
	$vposition = apply_filters('skeleton_footer_wrap_close_position',10);
	if ($vposition > -1) {add_action('skeleton_footer', 'skeleton_footer_wrap_close',$vposition);}
}

// Footer Extras HTML
// ------------------
if (!function_exists('skeleton_footer_extras')) {
	function skeleton_footer_extras() {
		global $vthemesettings;
		// 1.5.0: changed from skeleton_options to filtered theme option
		// $vfooterextras = skeleton_options('footer_extras','');
		// $vfooterextras = $vthemesettings['footer_extras'];
		$vfooterextras = apply_filters('skeleton_footer_html_extras','');
		if ($vfooterextras) {
			// 1.8.0: changed #footer_extras to #footer-extras for consistency
			if (THEMECOMMENTS) {echo '<!-- #footer-extras -->';}
			echo '<div id="footer-extras" class="footer-extras">'.PHP_EOL;
			echo '	<div class="inner">'.PHP_EOL;
			echo $vfooterextras.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			if (THEMECOMMENTS) {echo '<!-- /#footer-extras -->';}
			echo PHP_EOL.PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_footer_extras_position',2);
	if ($vposition > -1) {add_action('skeleton_footer','skeleton_footer_extras',$vposition);}
}

// Count Footer Widgets, allowing for Post Overrides
// -------------------------------------------------
if (!function_exists('skeleton_count_footer_widgets')) {
	function skeleton_count_footer_widgets() {
		$vfooterwidgets = 0;
		// 1.9.5: simplify and just use theme overrides global here
		global $vthemeoverride;
		if ($vthemeoverride['footerwidgets'] == 'off') {return $vfooterwidgets;}
		if ( (is_active_sidebar('footer-widget-area-1')) && ($vthemeoverride['footer1'] != 'off') ) {$vfooterwidgets++;}
		if ( (is_active_sidebar('footer-widget-area-2')) && ($vthemeoverride['footer2'] != 'off') ) {$vfooterwidgets++;}
		if ( (is_active_sidebar('footer-widget-area-3')) && ($vthemeoverride['footer3'] != 'off') ) {$vfooterwidgets++;}
		if ( (is_active_sidebar('footer-widget-area-4')) && ($vthemeoverride['footer4'] != 'off') ) {$vfooterwidgets++;}
		return $vfooterwidgets;
	}
}

// Call Footer Widgets
// -------------------
if (!function_exists('skeleton_footer_widgets')) {
	function skeleton_footer_widgets() {
		// filterable to allow for custom post types (see filters.php)
		// default template is sidebar/footer.php
		$vfooter = apply_filters('skeleton_footer_sidebar','footer');
		hybrid_get_sidebar($vfooter);
	}
	$vposition = apply_filters('skeleton_footer_widgets_position',4);
	if ($vposition > -1) {add_action('skeleton_footer', 'skeleton_footer_widgets',$vposition);}
}

// Footer Nav Menu
// ---------------
if (!function_exists('skeleton_footer_nav')) {
	function skeleton_footer_nav() {
		if (THEMECOMMENTS) {echo '<!-- .footer-menu -->';}
		echo '<div class="footer-menu" '.hybrid_get_attr('menu','footer').'>'.PHP_EOL;
		$vfooternav = array(
			'theme_location'  => 'footer',
			'container'       => 'div',
			'container_id' 	  => 'footermenu',
			'menu_class'      => 'menu',
			'echo'            => true,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1);
		// 1.8.5: added missing setting filter
		$vfooternav = apply_filters('skeleton_header_menu',$vfooternav);
		wp_nav_menu($vfooternav);
		echo PHP_EOL.'</div>';
		if (THEMECOMMENTS) {echo '<!-- /.footer-menu -->';}
		echo PHP_EOL;
	}
	$vposition = apply_filters('skeleton_footer_nav_position',6);
	if ($vposition > -1) {add_action('skeleton_footer','skeleton_footer_nav',$vposition);}
}

// Footer Credits Area
// -------------------
if (!function_exists('skeleton_footer_credits')) {
	function skeleton_footer_credits() {
		// calls skeleton_credit_link for default theme credits
		$vcredits = apply_filters('skeleton_author_credits','');
		if ($vcredits) {
			if (THEMECOMMENTS) {echo '<!-- #footercredits -->';}
			echo '<div id="footercredits">'.PHP_EOL.$vcredits.PHP_EOL.'</div>';
			if (THEMECOMMENTS) {echo '<!-- /#footercredits -->';}
			echo PHP_EOL;
		}
	}
	$vposition = apply_filters('skeleton_footer_credits_position',8);
	if ($vposition > -1) {add_action('skeleton_footer','skeleton_footer_credits',$vposition);}
}

// Site Credits
// ------------
if (!function_exists('skeleton_credit_link')) {
	function skeleton_credit_link(){
		global $vthemesettings;
		if ($vthemesettings['sitecredits'] != '') {
			if ($vthemesettings['sitecredits'] == '0') {return '';}
			return $vthemesettings['sitecredits'];
		}
		else {
			$vsitecredits = '<div id="themecredits">';
			if (is_child_theme()) {$vsitecredits .= THEMEDISPLAYNAME.' Theme for '; $vmfanchor = 'BioShip';} else {$vmfanchor = 'BioShip Framework';}
			$vsitecredits .= '<a href="http://'.'bioship.space/" title="BioShip Responsive Wordpress Theme Framework" target=_blank>'.$vmfanchor.'</a>';
			if (!is_child_theme()) {$vsitecredits .= ' by <a href="http://'.'wordquest.org/" title="WordQuest Alliance" target=_blank>WordQuest</a>';}
			$vsitecredits .= '</div>';
			return $vsitecredits;
		}
	}
	add_filter('skeleton_author_credits','skeleton_credit_link');
}

?>