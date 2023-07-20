<?php

// ==========================
// ==== BioShip Skeleton ====
// == Flexible Templating ===
// ==========================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

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
if ( !function_exists( 'bioship_skeleton_wrapper_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_container_open', 'bioship_skeleton_wrapper_open', 5 );

	function bioship_skeleton_wrapper_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemelayout;

		// --- set default wrap container classes ---
		// 1.8.5: use new theme layout global
		$classes = array( 'container', $vthemelayout['gridcolumns'] );

		// --- filter wrap container classes ---
		// 1.5.0: added container class compatibility
		// 1.8.5: removed grid compatibility classes (now content grid only)
		// filter the main wrap container classes
		$container_classes = bioship_apply_filters( 'skeleton_container_classes', $classes );
		if ( is_array( $container_classes ) ) {
			// 2.0.5: use standard array key index
			foreach ( $container_classes as $i => $class ) {
				$container_classes[$i] = trim( $class );
			}
			$classes = $container_classes;
		}
		$class_list = implode( ' ', $classes );

		// --- output wrap container open --
		bioship_html_comment( '#wrap.container' );
		echo '<div id="wrap" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
		bioship_html_comment( '#wrappadding.inner' );
		echo '	<div id="wrappadding" class="inner">' . PHP_EOL . PHP_EOL;
	}
}

// ------------------
// Main Wrapper Close
// ------------------
if ( !function_exists( 'bioship_skeleton_wrapper_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_container_close', 'bioship_skeleton_wrapper_close', 5 );

	function bioship_skeleton_wrapper_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output wrap container close ---
		echo '	</div>' . PHP_EOL;
		bioship_html_comment( '/#wrappadding.inner' );
		echo '</div>' . PHP_EOL;
		bioship_html_comment( '/#wrap.container' );
		echo PHP_EOL;
	}
}

// ----------------
// Output Clear Div
// ----------------
// 1.5.0: moved clear div here for flexibility
if ( !function_exists( 'bioship_skeleton_echo_clear_div' ) ) {
	function bioship_skeleton_echo_clear_div() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output clear div ---
		echo PHP_EOL . '<div class="clear"></div>' . PHP_EOL;
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
bioship_add_action( 'bioship_header', 'bioship_skeleton_echo_clear_div', 3 );
// --- after nav menu ---
// 1.8.0: moved sidebar buttons to inline
// add_action('bioship_navbar', 'skeleton_echo_clear_div', 6);
// --- after nav bar ---
bioship_add_action( 'bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 0 );
bioship_add_action( 'bioship_after_navbar', 'bioship_skeleton_echo_clear_div', 8 );
// --- after content area ---
bioship_add_action( 'bioship_after_content', 'bioship_skeleton_echo_clear_div', 2 );
// --- before footer ---
bioship_add_action( 'bioship_before_footer', 'bioship_skeleton_echo_clear_div', 10 );
// --- after footer widgets ---
bioship_add_action( 'bioship_footer', 'bioship_skeleton_echo_clear_div', 5 );
// --- after footer nav ---
bioship_add_action( 'bioship_footer', 'bioship_skeleton_echo_clear_div', 7 );


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
if ( !function_exists( 'bioship_skeleton_header_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_open', 0 );

	function bioship_skeleton_header_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemelayout;

		// --- set default header classes ---
		// 1.5.0: added header class compatibility and filter
		// 1.8.0: added alpha and omega classes to header div
		// 1.8.5: use new theme layout global
		// 1.9.0: removed 960gs classes from theme grid (now for content grid only)
		$classes = array( $vthemelayout['gridcolumns'], 'columns', 'alpha', 'omega' );

		// --- filter header classes ---
		$header_classes = bioship_apply_filters( 'skeleton_header_classes', $classes );
		// 2.1.1: added filtered array check and class space trimming
		if ( is_array( $header_classes ) ) {
			foreach ( $header_classes as $i => $class ) {
				$header_classes[$i] = trim( $class );
			}
			$classes = $header_classes;
		}
		$class_list = implode( ' ', $classes );

		// --- output header wrap open ---
		bioship_html_comment( '#header' );

		echo '<div id="header" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
		bioship_html_comment( '#headerpadding.inner' );
		echo '	<div id="headerpadding" class="inner">' . PHP_EOL;
		echo '		<header ';
		hybrid_attr( 'header' );
		echo '>' . PHP_EOL;
	}
}

// -----------------
// Header Wrap Close
// -----------------
if ( !function_exists( 'bioship_skeleton_header_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_close', 10 );

	function bioship_skeleton_header_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output header wrap close ---
		echo '		</header>' . PHP_EOL;
		echo '	</div>' . PHP_EOL;
		bioship_html_comment( '/#headerpadding.inner' );
		echo '</div>' . PHP_EOL;
		bioship_html_comment( '/#header' );
		echo PHP_EOL;
	}
}

// ---------------
// Header Nav Menu
// ---------------
if ( !function_exists( 'bioship_skeleton_header_nav' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_nav', 2 );

	function bioship_skeleton_header_nav() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for header menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['header'] ) || !$vthemelayout['menus']['header'] ) {
			return;
		}

		// --- set default header menu settings ---
		$menuargs = array(
			'theme_location'  => 'header',
			'container'       => 'div',
			'container_id'    => 'headermenu',
			'menu_class'      => 'menu',
			'echo'            => false,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1,
		);

		// --- filter header menu settings ---
		// 1.8.5: added missing menu setting filter
		// 2.0.5: added _settings filter suffix
		$args = bioship_apply_filters( 'skeleton_header_menu_settings', $args );

		// --- output header menu ---
		// note: can use Hybrid attribute filter to add column classes
		$attributes = hybrid_get_attr( 'menu', 'header' );
		$menu = bioship_html_comment( '.header-menu', false );
		$menu .= '<div ' . $attributes . '>' . PHP_EOL;
		$menu .= '	' . wp_nav_menu( $args ) . PHP_EOL;
		$menu .= '</div>' . PHP_EOL;
		$menu .= bioship_html_comment( '/.header-menu', false );
		$menu .= PHP_EOL;

		// --- filter and output ---
		// 2.1.2: added missing header menu filter
		// TODO: add filter to doc filter list
		$menu = bioship_apply_filters( 'skeleton_header_menu', $menu );
		// 2.2.0: use wp_kses on menu output
		$allowed = bioship_allowed_html( 'menu', 'header' );
		echo wp_kses( $menu, $allowed );
	}
}

// -----------
// Header Logo
// -----------
if ( !function_exists( 'bioship_skeleton_header_logo' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_logo', 4 );

	function bioship_skeleton_header_logo() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemedisplay;

		// --- get site name and description ---
		$blog_name = get_bloginfo( 'name', 'display' );
		$blog_name = bioship_apply_filters( 'skeleton_blog_display_name', $blog_name );
		$blog_description = get_bloginfo( 'description' );
		$blog_description = bioship_apply_filters( 'skeleton_blog_description', $blog_description );

		// --- filter title separator ---
		// 2.0.9: make title attribute separator filterable (default to title tag separator)
		$sep = bioship_title_separator( '|' );
		$sep = bioship_apply_filters( 'skeleton_header_logo_title_separator', $sep );

		// --- filter home link title ---
		// 2.1.1: added filter for home link title
		$link_title = $blog_name . ' ' . $sep . ' ' . $blog_description;
		$link_title = bioship_apply_filters( 'skeleton_home_link_title', $link_title );

		// --- get filtered home URL ---
		// 1.8.5: use home_url not site_url
		// 2.0.6: added site logo and home title link filters
		$home_url = home_url( '/' );
		$home_url = bioship_apply_filters( 'skeleton_title_link_url', $home_url );
		$logo_link_url = bioship_apply_filters( 'skeleton_logo_link_url', $home_url );

		// --- get filtered logo URL ---
		// 1.5.0: moved logo url filter
		// 2.0.9: use esc_url on logo output
		// 2.2.0: removed duplicate esc_url
		$logo_url = $vthemesettings['header_logo'];
		$logo_url = bioship_apply_filters( 'skeleton_header_logo_url', $logo_url );

		// --- filter logo classes ---
		// 1.8.0: added header logo class filter
		$logo_classes = array( 'logo' );
		$logo_classes = bioship_apply_filters( 'skeleton_header_logo_classes', $logo_classes );
		$logo_class_list = implode( ' ', $logo_classes );

		// --- set logo image display style ---
		// 1.8.5: recombined image/text template for live previewing
		// 1.8.5: added site text / description text display checkboxes
		// 1.9.0: fix to logo logic here having separated text display
		// 2.0.6: display as inline block (for combine logo and site title)
		// 2.0.7: move inline-block display to style.css for easier overriding
		// 2.2.0: simplified check logic to single line
		$logo_image_display = $logo_url ? '' : ' style="display:none;"';

		// --- check site title and description display settings ---
		// 2.1.1: fix to switch variables for site title and description display
		// 2.2.0: split header text display options
		$site_title = $site_description = false;
		if ( isset( $vthemesettings['site_title'] ) && ( '1' == $vthemesettings['site_title'] ) ) {
			$site_title = true;
		}
		$site_title = bioship_apply_filters( 'skeleton_site_title_display', $site_title );
		if ( isset( $vthemesettings['site_description'] ) && ( '1' == $vthemesettings['site_description'] ) ) {
			$site_description = true;
		}
		$site_description = bioship_apply_filters( 'skeleton_site_description_display', $site_description );

		// TODO: recheck perpost meta display overrides for site title/description ?
		// (currently this is done via style overrides)
		// if ($vthemedisplay['sitetitle'] == '') {}
		// if ($vthemedisplay['sitedesc'] == '') {}

		// 1.9.9: set separate display variables for site title and description
		// 2.2.0: simplified check logic to single line
		$title_display = $site_title ? '' : ' style="display:none;"';
		$description_display = $site_description ? '' : ' style="display:none;"';

		// --- set logo and title section output ---
		// 1.8.5: fix to hybrid attributes names (_ to -)
		// 1.9.0: added filter to site-description attribute to prevent duplicate ID
		// 1.9.6: added logo-image ID not just class
		// 2.0.6: display inline-block for site-logo-text (for combined display)
		// 2.0.7: move inline block to style.css for easier overriding
		// 2.1.1: moved esc_url and esc_attr usage inline
		// 2.1.2: changed site-desc span to div to allow text width wrapping
		$logo = '';
		$logo .= bioship_html_comment( '#site-logo', false );
		$logo .= '<div id="site-logo" class="' . esc_attr( $logo_class_list ) . '">' . PHP_EOL;
		$logo .= '	<div class="inner">' . PHP_EOL;
		$logo .= ' 		<div class="site-logo-image"' . $logo_image_display . '>' . PHP_EOL;
		$logo .= '			<a class="logotype-img" href="' . esc_url( $logo_link_url ) . '" title="' . esc_attr( $link_title ) . '" rel="home">' . PHP_EOL;
		$logo .= '				<h1 id="site-title">' . PHP_EOL;
		$logo .= '					<img id="logo-image" class="logo-image" src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $blog_name ) . '" border="0">' . PHP_EOL;
		$logo .= '					<div class="alt-logo" style="display:none;"></div>' . PHP_EOL;
		$logo .= '				</h1>' . PHP_EOL;
		$logo .= '			</a>' . PHP_EOL;
		$logo .= ' 	 	</div>' . PHP_EOL;
		$logo .= ' 	 	<div class="site-logo-text">' . PHP_EOL;
		$logo .= '			<h1 id="site-title-text" ' . hybrid_get_attr( 'site-title' ) . $title_display . '>' . PHP_EOL;
		$logo .= '				<a class="text" href="' . esc_url( $home_url ) . '" title="' . esc_attr( $link_title ) . '" rel="home">' . esc_attr( $blog_name ) . '</a>' . PHP_EOL;
		$logo .= '			</h1>' . PHP_EOL;
		$logo .= '			<div id="site-description"' . $description_display . '>' . PHP_EOL;
		$logo .= '				<div class="site-desc" ' . hybrid_get_attr( 'site-description' ) . '>' . esc_html( $blog_description ) . '</div>' . PHP_EOL;
		$logo .= '			</div>' . PHP_EOL;
		$logo .= ' 		</div>' . PHP_EOL;
		$logo .= '	</div>' . PHP_EOL;
		$logo .= '</div>' . PHP_EOL;
		$logo .= bioship_html_comment( '/#site-logo', false );
		$logo .= PHP_EOL;

		// --- filter HTML and output ---
		$logo = bioship_apply_filters( 'skeleton_header_logo_override', $logo );
		// 2.2.0: use wp_kses on logo output
		$allowed = bioship_allowed_html( 'logo', 'header' );
		echo wp_kses( $logo, $allowed );
	}
}

// --------------
// Header Widgets
// --------------
if ( !function_exists( 'bioship_skeleton_header_widgets' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_widgets', 6 );

	function bioship_skeleton_header_widgets() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output header sidebar template ---
		// note: template filterable to allow for custom post types (see filters.php)
		// default template is sidebar/header.php
		$header = bioship_apply_filters( 'skeleton_header_sidebar', 'header' );
		hybrid_get_sidebar( $header );
	}
}

// -------------
// Header Extras
// -------------
if ( !function_exists( 'bioship_skeleton_header_extras' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_header', 'bioship_skeleton_header_extras', 8 );

	function bioship_skeleton_header_extras() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
		global $vthemesettings;

		// --- get header extras ---
		// 2.0.0: allow for use of shorter header extras filter name
		$header_extras = bioship_apply_filters( 'skeleton_header_extras', '' );
		// TODO: make this a backwards compatible filter and deprecate?
		$header_extras = bioship_apply_filters( 'skeleton_header_html_extras', $header_extras );

		if ( '' != $header_extras ) {
			// 1.8.0: changed #header_extras to #header-extras for consistency, added class filter
			// 2.1.1: filter header extra class array instead of string
			$classes = array( 'header-extras' );
			$extra_classes = bioship_apply_filters( 'skeleton_header_extras_classes', $classes );
			if ( is_array( $extra_classes ) ) {
				foreach ( $extra_classes as $i => $class ) {
					$extra_classes[$i] = trim( $class );
				}
				$classes = $extra_classes;
			}
			$class_list = implode( ' ', $classes );

			// --- output header extras HTML ---
			bioship_html_comment( '#header-extras' );
			echo '<div id="header-extras" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
			echo '	<div class="inner">' . PHP_EOL;
			// 2.2.0: use wp_kses on header extras output
			$allowed = bioship_allowed_html( 'content', 'header' );
			echo '		' . wp_kses( $header_extras, $allowed ) . PHP_EOL;
			echo '	</div>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#header-extras' );
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
if ( !function_exists( 'bioship_skeleton_main_menu_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_main_menu_open', 0 );

	function bioship_skeleton_main_menu_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['primary'] ) || !$vthemelayout['menus']['primary'] ) {
			return;
		}

		// --- output main menu wrap open ---
		// note: can filter classes using Hybrid attribute filter
		bioship_html_comment( '#navigation' );
		echo '<div id="navigation" ';
		hybrid_attr( 'menu', 'primary' );
		echo '>' . PHP_EOL;
	}
}

// -----------------------
// Primary Navigation Menu
// -----------------------
if ( !function_exists( 'bioship_skeleton_main_menu' ) ) {

	// 1.9.8: use new position filtered add_action method
	// 2.2.0: move navigation output to above mobile buttons
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_main_menu', 2 );

	function bioship_skeleton_main_menu() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['primary'] ) || !$vthemelayout['menus']['primary'] ) {
			return;
		}

		// --- check navigation remove filter ---
		// 1.9.9: check hide navigation override filter
		// 2.1.1: changed filter name to navigation remove (hiding is via styles)
		$remove_nav = bioship_apply_filters( 'skeleton_navigation_remove', false );
		if ( $remove_nav ) {
			return;
		}

		// --- set and filter menu args ---
		// 1.8.0: only output if there is a menu is assigned
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$args = array(
			'container_id'		=> 'mainmenu',
			'container_class'	=> 'menu-header',
			'theme_location'	=> 'primary',
			'echo'				=> false,
		);
		$args = bioship_apply_filters( 'skeleton_primary_menu_settings', $args );

		// --- output main menu ---
		// 2.1.2: added wrapper to main menu for consistency
		// 2.2.0: fix to primary menu ID attribute
		$attributes = hybrid_get_attr( 'menu', 'primary' );
		$menu = bioship_html_comment( '#primarymenu', false );
		$menu .= '<div id="primarymenu" ' . $attributes . '>' . PHP_EOL;
		$menu .= '	<div class="inner">' . PHP_EOL;
		$menu .= '		' . wp_nav_menu( $args ) . PHP_EOL;
		$menu .= '	</div>' . PHP_EOL;
		$menu .= '</div>' . PHP_EOL;
		$menu .= bioship_html_comment( '/#primarymenu', false );

		// --- filter and output primary menu ---
		// 2.1.2: added missing primary menu filter override
		$menu = bioship_apply_filters( 'skeleton_primary_menu', $menu );
		// 2.2.0: use wp_kses on menu output
		$allowed = bioship_allowed_html( 'menu', 'primary' );
		echo wp_kses( $menu, $allowed );
	}
}

// --------------------
// Main Menu Wrap Close
// --------------------
if ( !function_exists( 'bioship_skeleton_main_menu_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_main_menu_close', 10 );

	function bioship_skeleton_main_menu_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['primary'] ) || !$vthemelayout['menus']['primary'] ) {
			return;
		}

		// --- output main menu wrap close ---
		echo '</div>' . PHP_EOL;
		bioship_html_comment( '/#navigation' );
		echo PHP_EOL;
	}
}

// -----------------------
// Main Menu Mobile Button
// -----------------------
// 1.5.0: added mobile menu button
if ( !function_exists( 'bioship_skeleton_main_menu_button' ) ) {

	// 1.9.8: use new position filtered add_action method
	// 2.2.0: move button to below navigation bar output
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_main_menu_button', 6 );

	function bioship_skeleton_main_menu_button() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for main menu ---
		// 2.0.9: use subkey of vthemelayout global
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['primary'] ) || !$vthemelayout['menus']['primary'] ) {
			return;
		}

		// --- check hide navigation filter ----
		// 1.9.9: check hide navigation override filter
		// 2.1.1: changed filter name to navigation remove (hiding is via styles)
		$remove_nav = bioship_apply_filters( 'skeleton_navigation_remove', false );
		if ( $remove_nav ) {
			return;
		}

		// --- set menu button texts ---
		// 2.1.1: added missing translation wrappers
		// 2.2.0: remove duplicated esc_attr wrappers
		$show_menu_text = __( 'Show Menu', 'bioship' );
		$hide_menu_text = __( 'Hide Menu', 'bioship' );

		// --- set menu buttons ---
		// 2.1.3: prefix javascript functions
		// 2.2.0: added extra mainmenubutton class
		// 2.2.0: add filter to allow for possible menu icon HTML instead of text
		// TODO: add this filter to filter list documentation
		$main_menu_icon = bioship_apply_filters( 'skeleton_main_menu_icon', false );
		$buttons = '<div id="mainmenubutton" class="mobilebutton">' . PHP_EOL;
		if ( $main_menu_icon ) {
			$buttons .= '	<a id="mainmenushow" class="button mainmenubutton icon" href="javascript:void(0);" onclick="bioship_showmainmenu();" title="' . esc_attr( $show_menu_text ) . '">' . $main_menu_icon . '</a>' . PHP_EOL;
			$buttons .= '	<a id="mainmenuhide" class="button mainmenubutton icon" href="javascript:void(0);" onclick="bioship_hidemainmenu();" title="' . esc_attr( $hide_menu_text ) . '" style="display:none;">' . $main_menu_icon . '</a>' . PHP_EOL;
		} else {
			// 2.2.0: use esc_html instead of esc_attr for labels
			$buttons .= '	<a id="mainmenushow" class="button mainmenubutton" href="javascript:void(0);" onclick="bioship_showmainmenu();">' . esc_html( $show_menu_text ) . '</a>' . PHP_EOL;
			$buttons .= '	<a id="mainmenuhide" class="button mainmenubutton" href="javascript:void(0);" onclick="bioship_hidemainmenu();" style="display:none;">' . esc_html( $hide_menu_text ) . '</a>' . PHP_EOL;
		}
		$buttons .= '</div>' . PHP_EOL;

		// --- filter and output menu buttons ---
		// 2.1.1: added mobile menu buttons filter
		$buttons = bioship_apply_filters( 'skeleton_mobile_menu_buttons', $buttons );
		// 2.2.0: use wp_kses on buttons output
		$allowed = bioship_allowed_html( 'buttons', 'mobile' );
		echo wp_kses( $buttons, $allowed );
		// echo $buttons;
	}
}

// --------------
// Secondary Menu
// --------------
// note: action 'skeleton_secondarynav' is not actually called anywhere
// this is an auxiliary navigation bar available for custom positioning
// ...or direct firing via: do_action('bioship_secondarynav');
if ( !function_exists( 'bioship_skeleton_secondary_menu' ) ) {

	// 1.9.8: use new position filtered add_action method
	// 1.9.8: hooked this to an existing template position (previously unused)
	bioship_add_action( 'bioship_before_header', 'bioship_skeleton_secondary_menu', 8 );

	add_action( 'bioship_secondarynav', 'bioship_skeleton_secondary_menu' );

	function bioship_skeleton_secondary_menu() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check if secondary menu enabled ---
		// 2.0.9: use subkey of vthemelayout global
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['secondary'] ) || !$vthemelayout['menus']['secondary'] ) {
			return;
		}

		// --- set and filter menu arguments ---
		// 2.1.1: add echo to false to allow filtering
		$args = array(
			'container_id' 		=> 'submenu',
			'container_class' 	=> 'menu-header',
			'theme_location' 	=> 'secondary',
			'echo'				=> false,
		);
		$args = bioship_apply_filters( 'skeleton_secondary_menu_settings', $args );

		// --- output secondary menu ---
		// 2.0.5: moved has_nav_menu check to register_nav_menus
		$attributes = hybrid_get_attr( 'menu', 'secondary' );
		$menu = bioship_html_comment( '#secondarymenu', false );
		$menu .= '<div id="secondarymenu" ' . $attributes . '>' . PHP_EOL;
		$menu .= '	<div class="inner">' . PHP_EOL;
		$menu .= '		' . wp_nav_menu( $args ) . PHP_EOL;
		$menu .= '	</div>' . PHP_EOL;
		$menu .= '</div>' . PHP_EOL;
		$menu .= bioship_html_comment( '/#secondarymenu', false );
		$menu .= PHP_EOL;

		// --- filter and output secondary menu ---
		$menu = bioship_apply_filters( 'skeleton_secondary_menu', $menu );
		// 2.2.0: use wp_kses on menu output
		$allowed = bioship_allowed_html( 'menu', 'secondary' );
		echo wp_kses( $menu, $allowed );
	}
}

// ---------------
// === Banners ===
// ---------------
// 1.8.0: added all full width banner positions
// TODO: add filter examples to filters.php
// skeleton_{position}_banner default positions:
// top: above main header area
// header: below main header area (before navbar)
// navbar: after navbar (before sidebars/content)
// footer: above main footer area
// bottom: below main footer area

// ---------------
// Abstract Banner
// ---------------
if ( !function_exists( 'bioship_skeleton_banner_abstract' ) ) {
 function bioship_skeleton_banner_abstract( $position ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	// --- get initial banner output ---
 	// (to allow for HTML / ads / shortcode / widget etc...)
	// 2.2.0: fix to duplicate variable usage (banner)
 	$banner_html = bioship_apply_filters( 'skeleton_banner_' . $position, '' );

	// --- check per post banner field values ---
 	if ( is_singular() ) {
 		// note: can set custom field values (for automatic image banners only)
 		// eg. _topbannerurl and _topbannerlink etc...
 		global $post;
		$postid = $post->ID;

		// 2.1.1: change post meta keys from _{position}banner{url/link} to banner_{url/link}_{position}
 		$banner_url = get_post_meta( $postid, 'banner_url' . $position, true );
 		$banner_link = get_post_meta( $postid, 'banner_link_' . $position, true );
 		// 2.2.0: fix to misplaced variable indicator in trim/bannerurl
 		if ( $banner_url && $banner_link && ( '' != trim( $banner_url ) ) && ( '' != trim( $banner_link ) ) ) {
 			$banner_html = '<a href="' . esc_url( $banner_link ) . '" target="_blank">' . PHP_EOL;
 			$banner_html .= '	<img src="' . esc_url( $banner_url ) . '" border="0">' . PHP_EOL;
 			$banner_html .= '</a>' . PHP_EOL;
 		}
 	}

 	if ( '' != $banner_html ) {

 		// --- set banner class ---
 		// 1.9.8: added banner div class filter
 		// 2.0.5: added extra filter based on banner position
 		// 2.2.0: set classes as array for filtering
 		$classes = array( 'banner', $position );
 		$classes = bioship_apply_filters( 'skeleton_banner_class', $classes );
 		$classes = bioship_apply_filters( 'skeleton_banner_class_' . $position, $classes );
 		$class_list = implode( ' ', $classes );

 		// --- banner output ---
	 	$banner = bioship_html_comment( '#' . $position . 'banner', false );
	 	$banner .= '<div id="' . esc_attr( $position ) . 'banner" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
	 	$banner .= '	<div class="inner">' . PHP_EOL;
 		$banner .= '		' . $banner_html . PHP_EOL;
 		$banner .= '	</div>' . PHP_EOL;
 		$banner .= '</div>' . PHP_EOL;
 		$banner .= bioship_html_comment( '/#' . $position . 'banner', false );
 		$banner .= PHP_EOL;

 		// --- filter and output banner ---
 		$banner = bioship_apply_filters( 'skeleton_banner_override_' . $position, $banner );
 		// 2.2.0: use wp_kses on banner output
		$allowed = bioship_allowed_html( 'banner', $position );
 		echo wp_kses( $banner, $allowed );
	}
 }
}

// ----------
// Top Banner
// ----------
// 1.8.0: added banner position (above header)
if ( !function_exists( 'bioship_skeleton_banner_top' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_before_header', 'bioship_skeleton_banner_top', 2 );

	function bioship_skeleton_banner_top() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- call abstract banner with position ---
		bioship_skeleton_banner_abstract( 'top' );
	}
}

// -------------
// Header Banner
// -------------
// 1.8.0: added banner position (below header)
if ( !function_exists( 'bioship_skeleton_banner_header' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_header', 'bioship_skeleton_banner_header', 5 );

	function bioship_skeleton_banner_header() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- call abstract banner with position ---
		bioship_skeleton_banner_abstract( 'header' );
	}
}

// -------------
// NavBar Banner
// -------------
// 1.8.0: added banner position (under navbar)
if ( !function_exists( 'bioship_skeleton_banner_navbar' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_navbar', 'bioship_skeleton_banner_navbar', 10 );

	function bioship_skeleton_banner_navbar() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- call abstract banner with position ---
		bioship_skeleton_banner_abstract( 'navbar' );
	}
}

// -------------
// Footer Banner
// -------------
// 1.8.0: added banner position (above footer)
if ( !function_exists( 'bioship_skeleton_banner_footer' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_before_footer', 'bioship_skeleton_banner_footer', 5 );

	function bioship_skeleton_banner_footer() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- call abstract banner with position ---
		bioship_skeleton_banner_abstract( 'footer' );
	}
}

// -------------
// Bottom Banner
// -------------
// 1.8.0: added banner position (below footer)
if ( !function_exists( 'bioship_skeleton_banner_bottom' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_footer', 'bioship_skeleton_banner_bottom', 5 );

	function bioship_skeleton_banner_bottom() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- call abstract banner with position ---
		bioship_skeleton_banner_abstract( 'bottom' );
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
if ( !function_exists( 'bioship_skeleton_add_widget_classes' ) ) {

 add_filter( 'dynamic_sidebar_params', 'bioship_skeleton_add_widget_classes' );

 function bioship_skeleton_add_widget_classes( $params ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemewidgets, $vthemewidgetcounter;

	// --- set sidebar ID ---
	$sidebarid = $params[0]['id'];
	$classes = array();

	// --- set widget defaults ---
    if ( !isset( $vthemewidgets ) ) {
		$vthemewidgets = wp_get_sidebars_widgets();
	}
    if ( !isset( $vthemewidgetcounter ) ) {
		$vthemewidgetcounter = array();
	}

    // --- bail if the current sidebar has no widgets ---
    if ( !isset( $vthemewidgets[$sidebarid] ) || !is_array( $vthemewidgets[$sidebarid] ) ) {
		return $params;
	}

    // [not implemented] this is for horizontal span classes
    // Rounds number of widgets down to a whole number
    // $number_of_widgets = count($vthemewidgets[$sidebarid]);
    // $rounded_number_of_widgets = floor(12 / $number_of_widgets);
	// $classes[] = 'span'.$rounded_number_of_widgets;

	// --- increment / start widget counter for this sidebar ---
    if ( isset( $vthemewidgetcounter[$sidebarid] ) ) {
    	$vthemewidgetcounter[$sidebarid]++;
    } else {
    	$vthemewidgetcounter[$sidebarid] = 1;
    	$classes[] = 'first-widget';
    }
	if ( count( $vthemewidgets[$sidebarid] ) == $vthemewidgetcounter[$sidebarid] ) {
		$classes[] = 'last-widget';
	}

	// --- add odd / even classes to widgets ---
    if ( $vthemewidgetcounter[$sidebarid] & 1 ) {
		$classes[] = 'odd-widget';
	} else {
		$classes[] = 'even-widget';
	}

	// --- set replacement sidebar class string ---
	$classlist = implode( ' ', $classes ) . ' ';

	// --- replace widget classes ---
	// TODO: maybe use another method instead of preg_replace ?
    $params[0]['before_widget'] = preg_replace( '/class=\"/', 'class="' . $classlist . ' ', $params[0]['before_widget'], 1 );

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
if ( !function_exists( 'bioship_skeleton_sidebar_position_class' ) ) {

	add_filter( 'body_class', 'bioship_skeleton_sidebar_position_class' );

	function bioship_skeleton_sidebar_position_class( $classes ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- check for sidebars ---
		global $vthemesidebars;
		if ( !$vthemesidebars['sidebar'] ) {
			return $classes;
		}

		// --- set sidebar position class ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ( $sidebars as $i => $sidebar ) {
			// note: sub prefix incidates subsidebar
			if ( ( '' != $sidebar ) && ( 'sub' != substr( $sidebar, 0, 3 ) ) ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( ( 0 == $i ) || ( 1 == $i ) ) {
					$classes[] = 'sidebar-left';
				}
				if ( ( 2 == $i ) || ( 3 == $i ) ) {
					$classes[] = 'sidebar-right';
				}
			}
		}
		return $classes;
	}
}

// ---------------------
// Mobile Sidebar Button
// ---------------------
// 1.5.0: added this button
if ( !function_exists( 'bioship_skeleton_sidebar_button' ) ) {

	// 1.9.8: use new position filtered add_action method
	// 2.2.0: move button to below navigation bar output
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_sidebar_button', 4 );

	function bioship_skeleton_sidebar_button() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for sidebars ---
		global $vthemesidebars;
		if ( !$vthemesidebars['sidebar'] ) {
			return;
		}

		// 2.2.0: no button needed for blank or none templates
		foreach ( $vthemesidebars['sidebars'] as $sidebar ) {
			if ( ( 'blank' == $sidebar ) || ( 'none' == $sidebar ) ) {
				return;
			}
		}

		// --- button text translations ---
		// 2.1.1: added button anchor text translations
		// 2.2.0: removed duplicate esc_attr wrappers
		$labels = array(
			'show'	=> __( 'Show Sidebar', 'bioship' ),
			'hide'	=> __( 'Hide Sidebar', 'bioship' ),
			'text'	=> __( 'Sidebar', 'bioship' ),
		);
		$labels = bioship_apply_filters( 'skeleton_sidebar_button_labels', $labels );

		// --- create sidebar buttons output ---
		// 2.1.3: prefix javascript functions
		// 2.2.0: fix to small show sidebar label
		$buttons = bioship_html_comment( '#sidebarbutton', false );
		$buttons .= '<div id="sidebarbutton" class="mobilebutton">' . PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarshow" href="javascript:void(0);" onclick="bioship_showsidebar();">' . esc_html( $labels['show'] ) . '</a>' . PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarhide" href="javascript:void(0);" onclick="bioship_hidesidebar();" style="display:none;">' . esc_html( $labels['hide'] ) . '</a>' . PHP_EOL;
		$buttons .= '</div>' . PHP_EOL;
		$buttons .= '<div id="sidebarbuttonsmall" class="mobilebutton">' . PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarshowsmall" href="javascript:void(0);" onclick="bioship_showsidebar();">[+] ' . esc_html( $labels['text'] ) . '</a>' . PHP_EOL;
		$buttons .= '	<a class="button" id="sidebarhidesmall" href="javascript:void(0);" onclick="bioship_hidesidebar();" style="display:none;">[-] ' . esc_html( $labels['text'] ) . '</a>' . PHP_EOL;
		$buttons .= '</div>' . PHP_EOL;
		$buttons .= bioship_html_comment( '/#sidebarbutton', false );
		$buttons .= PHP_EOL;

		// --- filter and echo sidebar buttons ---
		$buttons = bioship_apply_filters( 'skeleton_sidebar_display_buttons', $buttons );
		// 2.2.0: use wp_kses on buttons output
		$allowed = bioship_allowed_html( 'buttons', 'sidebar' );
		echo wp_kses( $buttons, $allowed );
		// echo $buttons;
	}
}

// -----------------
// Sidebar Wrap Open
// -----------------
// 1.5.0: skeleton_sidebar_wrap to skeleton_sidebar_open
if ( !function_exists( 'bioship_skeleton_sidebar_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_before_sidebar', 'bioship_skeleton_sidebar_open', 5 );

	function bioship_skeleton_sidebar_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemesidebars;

		// --- get sidebar column count ---
		// 1.9.0: use new theme layout global
		// TODO: WTF this method is just not working here ?
		// $sidebarcolumns = $vthemesidebars['sidebarcolumns'];
		// $sidebars = $vthemesidebars; unset($sidebars['output']);
		// bioship_debug("Sidebar Layout Check", $sidebars);
		$sidebar_columns = bioship_set_sidebar_columns();

		// --- set sidebar column classes ---
		$classes = array( $sidebar_columns, 'columns' );

		// --- add alpha / omega sidebar classes ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple numerical array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ( $sidebars as $i => $sidebar ) {
			if ( ( '' != $sidebar ) && ( 'sub' != substr( $sidebar, 0, 3 ) ) ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( 0 == $i ) {
					$classes[] = 'alpha';
				} 	elseif ( ( 1 == $i ) && ( '' == $sidebars[0] ) ) {
					$classes[] = 'alpha';
				} elseif ( ( 2 == $i ) && ( '' == $sidebars[3] ) ) {
					$classes[] = 'omega';
				} elseif ( 3 == $i ) {
					$classes[] = 'omega';
				}
			}
		}
		// 2.2.0: run array unique to prevent duplicates
		$classes = array_unique( $classes );

		// --- filter sidebar classes ---
		// 1.8.0: added sidebar class array filter
		$sidebar_classes = bioship_apply_filters( 'skeleton_sidebar_classes', $classes );
		if ( is_array( $sidebar_classes ) ) {
			// 2.0.5: use simple array key index
			foreach ( $sidebar_classes as $i => $class ) {
				$sidebar_classes[$i] = trim( $class );
			}
			$classes = $sidebar_classes;
		}
		$class_list = implode( ' ', $classes );

		// --- output sidebar wrap open ---
		bioship_html_comment( '#sidebar' );
		echo '<div id="sidebar" class="' . esc_attr( $class_list ) . '" role="complementary">' . PHP_EOL;
		bioship_html_comment( '#sidebarpadding.inner' );
		echo '	<div id="sidebarpadding" class="inner">' . PHP_EOL . PHP_EOL;
	}
}

// ------------------
// Sidebar Wrap Close
// ------------------
if ( !function_exists( 'skeleton_sidebar_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_sidebar', 'bioship_skeleton_sidebar_close', 5 );

	function bioship_skeleton_sidebar_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output sidebar wrap close ---
		echo PHP_EOL . '	</div>';
		bioship_html_comment( '/#sidebarpadding.inner' );
		echo PHP_EOL . '</div>' . PHP_EOL;
		bioship_html_comment( '/#sidebar' );
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
if ( !function_exists( 'bioship_skeleton_subsidebar_position_class' ) ) {

	add_filter( 'body_class', 'bioship_skeleton_subsidebar_position_class' );

	function bioship_skeleton_subsidebar_position_class( $classes ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- check for sidebars ---
		global $vthemesidebars;
		if ( !$vthemesidebars['subsidebar'] ) {
			return $classes;
		}

		// --- set subsidebar position class ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ( $sidebars as $i => $sidebar ) {
			if ( ( '' != $sidebar ) && ( 'sub' == substr( $sidebar, 0, 3 ) ) ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( ( 0 == $i ) || ( 1 == $i ) ) {
					$classes[] = 'subsidebar-left';
				} elseif ( ( 2 == $i ) || ( 3 == $i ) ) {
					$classes[] = 'subsidebar-right';
				}
			}
		}
		return $classes;
	}
}

// ------------------------
// Mobile SubSidebar Button
// ------------------------
// 1.5.0: added this button
if ( !function_exists( 'bioship_skeleton_subsidebar_button' ) ) {

	// 1.9.8: use new position filtered add_action method
	// 2.2.0: move button to below navigation bar output
	bioship_add_action( 'bioship_navbar', 'bioship_skeleton_subsidebar_button', 9 );

	function bioship_skeleton_subsidebar_button() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for subsidebar ---
		global $vthemesidebars;
		if ( !$vthemesidebars['subsidebar'] ) {
			return;
		}

		// 2.2.0: no button needed for subblank or subnone templates
		foreach ( $vthemesidebars['sidebars'] as $sidebar ) {
			if ( ( 'subblank' == $sidebar ) || ( 'subnone' == $sidebar ) ) {
				return;
			}
		}

		// --- button text translations ---
		// 2.1.2: added button anchor text translations
		// 2.2.0: remove duplicate esc_attr wrappers
		// 2.2.0: make button labels filterable
		$labels = array(
			'show'	=> __( 'Show SubBar', 'bioship' ),
			'hide'	=> __( 'Hide SubBar', 'bioship' ),
			'text'	=> __( 'SubBar', 'bioship' ),
		);
		$labels = bioship_apply_filters( 'skeleton_subsidebar_button_labels', $labels );

		// --- set subsidebar display button output ---
		// 2.1.3: prefix javascript functions
		$buttons = bioship_html_comment( '#subsidebarbutton', false );
		$buttons .= '<div id="subsidebarbutton" class="mobilebutton">' . PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarshow" href="javascript:void(0);" onclick="bioship_showsubsidebar();">' . esc_html( $labels['show'] ) . '</a>' . PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarhide" href="javascript:void(0);" onclick="bioship_hidesubsidebar();" style="display:none;">' . esc_html( $labels['hide'] ) . '</a>' . PHP_EOL;
		$buttons .= '</div>' . PHP_EOL;
		$buttons .= '<div id="subsidebarbuttonsmall" class="mobilebutton">' . PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarshowsmall" href="javascript:void(0);" onclick="bioship_showsubsidebar();">[+] ' . esc_html( $labels['text'] ) . '</a>' . PHP_EOL;
		$buttons .= '	<a class="button" id="subsidebarhidesmall" href="javascript:void(0);" onclick="bioship_hidesubsidebar();" style="display:none;">[-] ' . esc_html( $labels['text'] ) . '</a>' . PHP_EOL;
		$buttons .= '</div>' . PHP_EOL;
		$buttons .= bioship_html_comment( '/#subsidebarbutton', false );
		$buttons .= PHP_EOL;

		// --- filter and output subsidebar button ---
		$buttons = bioship_apply_filters( 'skeleton_subsidebar_display_buttons', $buttons );
		$allowed = bioship_allowed_html( 'buttons', 'subsidebar' );
		echo wp_kses( $buttons, $allowed );
		// echo $buttons;
	}
}

// --------------------
// SubSidebar Wrap Open
// --------------------
if ( !function_exists( 'bioship_skeleton_subsidebar_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_before_subsidebar', 'skeleton_subsidebar_open', 5 );

	function bioship_skeleton_subsidebar_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemesidebars;

		// 1.9.0: use new theme layout global
		// ? WTF this method is just not working ?
		// $subsidebarcolumns = $vthemesidebars['subsidebarcolumns'];
		// $sidebars = $vthemesidebars; unset($sidebars['output']);
		// bioship_debug("Sidebar Layout Check", $sidebars);
		$subsidebar_columns = bioship_set_subsidebar_columns();

		// --- set subsidebar column classes ---
		$classes = array( $subsidebar_columns, 'columns' );

		// --- set alpha and omega subsidebar classes ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 2.0.5: use simple array key index
		$sidebars = $vthemesidebars['sidebars'];
		foreach ( $sidebars as $i => $sidebar ) {
			if ( ( '' != $sidebar ) && ( 'sub' == substr( $sidebar, 0, 3 ) ) ) {
				// positions: 0=left, 1=inner left, 2=inner right, 3=right
				if ( 0 == $i ) {
					$classes[] = 'alpha';
				} elseif ( ( 1 == $i ) && ( '' == $sidebars[0] ) ) {
					$classes[] = 'alpha';
				} elseif ( ( 2 == $i ) && ( '' == $sidebars[3] ) ) {
					$classes[] = 'omega';
				} elseif ( 3 == $i ) {
					$classes[] = 'omega';
				}
			}
		}
		// 2.2.0: make unique to prevent duplicates
		$classes = array_unique( $classes );

		// --- filter subsidebar classes ----
 		// 1.8.0: added subsidebar class array filter
 		$subsidebar_classes = bioship_apply_filters( 'skeleton_subsidebar_classes', $classes );
		if ( is_array( $subsidebar_classes ) ) {
			// 2.0.5: use simple array key index
			foreach ( $subsidebar_classes as $i => $class ) {
				$subsidebar_classes[$i] = trim( $class );
			}
			$classes = $subsidebar_classes;
		}
		$class_list = implode( ' ', $classes );

		// --- output subsidebar wrap open ---
		bioship_html_comment( '#subsidebar' );
		echo '<div id="subsidebar" class="' . esc_attr( $class_list ) . '" role="complementary">' . PHP_EOL;
		bioship_html_comment( '#subsidebarpadding.inner' );
		echo '	<div id="subsidebarpadding" class="inner">' . PHP_EOL;
	}
}

// ---------------------
// SubSidebar Wrap Close
// ---------------------
// 1.8.0: fix from skeleton_subsidebar_wrap_close
if ( !function_exists( 'bioship_skeleton_subsidebar_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_subsidebar', 'bioship_skeleton_subsidebar_close', 5 );

	function bioship_skeleton_subsidebar_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output subsidebar wrap close ---
		echo PHP_EOL . '	</div>';
		bioship_html_comment( '#subsidebarpadding.inner' );
		echo PHP_EOL . '</div>' . PHP_EOL;
		bioship_html_comment( '/#subsidebar' );
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
if ( !function_exists( 'bioship_skeleton_woocommerce_wrapper_open' ) ) {
 add_action( 'woocommerce_before_main_content', 'bioship_skeleton_woocommerce_wrapper_open' );
 function bioship_skeleton_woocommerce_wrapper_open() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- output woocommerce wrapper open ---
	bioship_html_comment( '#woocommercecontent' );
	echo '<div id="woocommercecontent">' . PHP_EOL;
 }
}

// -------------------------
// WooCommerce Wrapper Close
// -------------------------
// 1.8.0: add div wrapper to woocommerce content for ease of style targeting
// 2.0.5: added missing function_exists check
if ( !function_exists( 'bioship_skeleton_woocommerce_wrapper_close' ) ) {

 add_action( 'woocommerce_after_main_content', 'bioship_skeleton_woocommerce_wrapper_close' );

 function bioship_skeleton_woocommerce_wrapper_close() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- output woocommerce wrapper close ---
	echo '</div>' . PHP_EOL;
	bioship_html_comment( '/#woocommercecontent' );
	echo PHP_EOL;
 }
}

// -----------------
// Content Wrap Open
// -----------------
if ( !function_exists( 'bioship_skeleton_content_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_before_content', 'bioship_skeleton_content_open', 10 );

	function bioship_skeleton_content_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemelayout, $vthemesidebars;

		// --- check for sidebars ---
		// 1.8.0: sidebars calculated in skeleton_set_sidebar_layout
		// 1.9.0: use new themesidebars global
		// 2.2.0: add isset check to prevent undefined index warnings
		$sidebars = $vthemesidebars['sidebars'];
		$left_sidebar = $right_sidebar = false;
		if ( ( isset( $sidebars[0] ) && ( '' != $sidebars[0] ) ) || ( isset( $sidebars[1] ) && ( '' != $sidebars[1] ) ) ) {
			  $left_sidebar = true;
		}
		if ( ( isset( $sidebars[2] ) && ( '' != $sidebars[2] ) ) || ( isset( $sidebars[3] ) && ( '' != $sidebars[3] ) ) ) {
			$right_sidebar = true;
		}

		// --- set content columns class ---
		// 1.5.0: replaced skeleton_options call here
		// 1.8.0: add alpha/omega class depending on sidebar presence
		// 1.8.5: fix to double sidebar logic
		// 1.9.8: use themelayout global content columns
		// 2.0.7: added missing content classes filter
		$columns = $vthemelayout['contentcolumns'];
		$classes = array( $columns, 'columns' );

		// --- set alpha and omega classes ---
		if ( !$left_sidebar && !$right_sidebar ) {
			$classes[] = 'alpha';
			$classes[] = 'omega';
		} elseif ( $left_sidebar && !$right_sidebar ) {
			$classes[] = 'omega';
		} elseif ( $right_sidebar && !$left_sidebar ) {
			$classes[] = 'alpha';
		}

		// --- filter content classes ---
		$classes = bioship_apply_filters( 'skeleton_content_classes', $classes );
		$class_list = implode( ' ', $classes );

		// --- output #top id for scroll links ---
		// 2.1.4: add #content name for skip links
		echo '<a name="content"></a><a id="top" name="top"></a>' . PHP_EOL;

		// --- output content wrap open ---
		bioship_html_comment( '#content' );
		echo '<div id="content" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
		bioship_html_comment( '#contentpadding.inner' );
		echo '	<div id="contentpadding" class="inner">' . PHP_EOL . PHP_EOL;
	}
}

// ------------------
// Content Wrap Close
// ------------------
if ( !function_exists( 'bioship_skeleton_content_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_after_content', 'bioship_skeleton_content_close', 0 );

    function bioship_skeleton_content_close() {
    	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

    	// --- output content wrap close ---
    	echo PHP_EOL . '	</div>' . PHP_EOL;
    	bioship_html_comment( '/#contentpadding.inner' );
    	echo '</div>' . PHP_EOL;
    	bioship_html_comment( '/#content' );
    	echo PHP_EOL;

    	// --- output #bottom id for scroll links ---
    	echo '<a id="bottom" name="bottom"></a>';
    }
}

// ----------------------------
// Home (Blog) Page Top Content
// ----------------------------
// 1.8.5: moved this here from loop-hybrid.php
if ( !function_exists( 'bioship_skeleton_home_page_content' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_home_page_top', 'skeleton_home_page_content', 5 );

	function bioship_skeleton_home_page_content() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- set defaults to off ---
		$title = $content = false;

		// --- check for blog page ---
		$page_id = get_option( 'page_for_posts' );

		if ( $page_id ) {

			// --- get page title ---
			$title = get_the_title( $page_id );

			// --- get the page content ---
			// 2.2.0: use the_content filter instead of setup_postdata
			$post = get_page( $page_id );
			$content = apply_filters( 'the_content', $post->post_content );
		}

		// --- output page title ---
		// 1.9.8: added new home page title filter
		// 2.1.1: moved outside of page check so filter is run in any case
		$title = bioship_apply_filters( 'skeleton_home_page_title', $title );
		if ( $title ) {
			// 2.1.1: changed ID from blogpagetitle to blogpage-title
			// 2.2.0: use esc_html instead of esc_attr on title output
			bioship_html_comment( '#blogpage-title' );
				echo '<h2 id="blogpage-title">' . esc_html( $title ) . '</h2>' . PHP_EOL;
			bioship_html_comment( '/#blogpage-title' );
			echo PHP_EOL;
		}

		// --- filter and output page content ---
		// 1.9.8: added new home page content filter
		// 2.1.1: moved outside of page check so filter is run in any case
		$content = bioship_apply_filters( 'skeleton_home_page_content', $content );
		if ( $content ) {
			// 2.1.1: changed ID from blogpagecontent to blogpage-content
			bioship_html_comment( '#blogpage-content' );
			echo '<div id="blogpage-content">' . PHP_EOL;
			// 2.2.0: use wp_kses on content output
			$allowed = bioship_allowed_html( 'content', 'home_top' );
			echo '	' . wp_kses( $content, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#blogpage-content' );
		}
	}
}

// -------------------------------
// Home (Blog) Page Bottom Content
// -------------------------------
// 2.1.1: added this so HTML can be added via filter
if ( !function_exists( 'bioship_skeleton_home_page_footnote' ) ) {

	bioship_add_action( 'bioship_home_page_bottom', 'skeleton_home_page_footnote', 5 );

	function bioship_skeleton_home_page_footnote() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- filter and output footnote content ---
		$content = bioship_apply_filters( 'skeleton_home_page_footnote', false );
		if ( $content ) {
			// 2.2.0: fix to duplicate ID (blogpage-content)
			bioship_html_comment( '#blogpage-footnote' );
			echo '<div id="blogpage-footnote">' . PHP_EOL;
			// 2.2.0: use wp_kses on content output
			$allowed = bioship_allowed_html( 'content', 'home_bottom' );
			echo '	' . wp_kses( $content, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#blogpage-footnote' );
		}
	}
}

// ----------------------
// Front Page Top Content
// ----------------------
// 2.1.1: added this so HTML can be added via filters
// TODO: add new filters to filters.php examples
if ( !function_exists( 'bioship_skeleton_front_page_content' ) ) {

	bioship_add_action( 'bioship_front_page_top', 'skeleton_frontpage_content', 5 );

	function bioship_skeleton_frontpage_content() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- set defaults to off ---
		$title = $content = false;

		// --- check for front page ---
		// TODO: get the current option here ?
		/* $pageid = get_option( '' );
		if ( $pageid ) {

			// --- get page title ---
			$title = get_the_title( $pageid );

			// --- get the page content ---
			$post = get_page( $pageid );
			setup_postdata( $post );
			ob_start();
			the_content();
			$content = ob_get_contents();
			ob_end_clean();
			rewind_posts();
		} */

		// --- filter and output page title ---
		$title = bioship_apply_filters( 'skeleton_front_page_title', $title );
		if ( $title ) {
			// 2.2.0: use esc_html instead of esc_attr on title
			bioship_html_comment( '#frontpage-title' );
				echo '<h2 id="frontpage-title">' . esc_html( $title ) . '</h2>' . PHP_EOL;
			bioship_html_comment( '/#frontpage-title' );
			echo PHP_EOL;
		}

		// --- filter and output page content ---
		$content = bioship_apply_filters( 'skeleton_front_page_content', $content );
		if ( $content ) {
			bioship_html_comment( '#frontpagecontent' );
			echo '<div id="frontpage-content">' . PHP_EOL;
			// 2.2.0: use wp_kses on content output
			$allowed = bioship_allowed_html( 'content', 'front_top' );
			echo '	' . wp_kses( $content, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#frontpage-content' );
		}
	}
}

// -------------------------
// Front Page Bottom Content
// -------------------------
// 2.1.1: added this so HTML can be added via filter
// 2.2.0: fix to mismatching function wrapper
if ( !function_exists( 'bioship_skeleton_front_page_footnote' ) ) {

	bioship_add_action( 'bioship_front_page_bottom', 'bioship_skeleton_front_page_footnote', 5 );

	function bioship_skeleton_front_page_footnote() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- filter and output footnote content ---
		$content = bioship_apply_filters( 'skeleton_front_page_footnote', false );
		if ( $content ) {
			bioship_html_comment( '#frontpagecontent' );
			echo '<div id="frontpage-footnote">' . PHP_EOL;
			// 2.2.0: use wp_kses on content output
			$allowed = bioship_allowed_html( 'content', 'front_bottom' );
			echo '	' . wp_kses( $content, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#frontpage-footnote' );
		}
	}
}

// ------------------
// Output the Excerpt
// ------------------
// 1.5.0: added for no reason but to make it overrideable
if ( !function_exists( 'skeleton_echo_the_excerpt' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_the_excerpt', 'bioship_skeleton_echo_the_excerpt', 5 );

	function bioship_skeleton_echo_the_excerpt() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output the excerpt ---
		the_excerpt();
	}
}

// --------------------------
// Ensure Content Not in Head
// --------------------------
// 2.0.5: fix to avoid a very weird bug (unknown plugin conflict?)
// 2.2.0: move action to inside function_exists for consistency
global $vthemehead;
$vthemehead = false;
if ( !function_exists( 'bioship_head_finished' ) ) {
 add_action( 'wp_head', 'bioship_head_finished', 999 );
 function bioship_head_finished() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// --- flag wp_head output as finished ---
 	// (this prevents content output in head)
 	global $vthemehead;
	$vthemehead = true;
 	bioship_debug( "Theme Head Output Finished" );
 }
}

// ------------------
// Output the Content
// ------------------
// 1.5.0: added for no reason but to make it overrideable
if ( !function_exists( 'bioship_skeleton_echo_the_content' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_the_content', 'bioship_skeleton_echo_the_content', 5 );

	function bioship_skeleton_echo_the_content() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- for debugging content filters ---
		if ( THEMEDEBUG ) {
			global $wp_filter;
			bioship_debug( "Head Filters", $wp_filter['wp_head'] );
			bioship_debug( "Content Filters", $wp_filter['the_content'] );
		}

		// --- output the content ---
		// 2.0.5: check head output finished to ensure content is not output in head
		global $vthemehead;
		if ( $vthemehead ) {
			the_content();
		}
	}
}

// -------------
// Media Handler
// -------------
// 1.8.0: media handler for attachments and post formats
if ( !function_exists( 'bioship_skeleton_media_handler' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_media_handler', 'bioship_skeleton_media_handler', 5 );

	function bioship_skeleton_media_handler() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings;

		// --- only for singular pages ---
		if ( !is_singular() ) {
			return;
		}

		// Attachments
		// -----------
		if ( is_attachment() ) {

			// --- check attachment mime type ---
			$mimetype = get_post_mime_type();
			if ( strstr( $mimetype, 'audio' ) ) {
				$type = 'audio';
			} elseif ( strstr( $mimetype, 'video' ) ) {
				$type = 'video';
			} elseif ( strstr( $mimetype, 'image' ) ) {
				$type = 'image';
			} elseif ( strstr( $mimetype, 'text' ) ) {
				$type = 'text';
			} elseif ( strstr( $mimetype, 'application' ) ) {
				$type = 'application';
			} else {
				// unrecognized
				return;
			}

			// Display Attachment
			// ------------------
			bioship_html_comment( '#attachment' );
			echo '<div id="attachment">' . PHP_EOL;
			$hybrid_types = array( 'audio', 'video', 'application' );
			if ( in_array( $type, $hybrid_types ) ) {
				if ( !THEMEHYBRID && !function_exists( 'hybrid_attachment' ) ) {
					bioship_load_hybrid_media();
				}
				hybrid_attachment();
			}
			if ( 'image' == $type ) {
				// image caption check
				if ( has_excerpt() ) {
					$src = wp_get_attachment_image_src( get_the_ID(), 'full' );
					$args = array(
						'align' => 'aligncenter',
						'width' => esc_attr( $src[1] ),
						'caption' => get_the_excerpt(),
					);
					$image = wp_get_attachment_image( get_the_ID(), 'full', false );
					// 2.2.0: ouput image caption with wp_kses_post
					echo wp_kses_post( img_caption_shortcode( $args, $image ) );
				} else {
					echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) );
				}
			}
			if ( ( 'text' == $type ) || ( 'application' == $type ) ) {
				// 2.1.1: added missing download text translation wrapper
				// 2.2.0: use esc_html instead of esc_attr on download label
				$attachment = wp_get_attachment_metadata();
				$uploaddir = wp_upload_dir();
				$fileurl = trailingslashit( $uploaddir['baseurl'] ) . $attachment['file'];
				$downloadtext = __( 'Download this Attachment', 'bioship' );
				echo '<center><a href="' . esc_url( $fileurl ) . '">' . esc_html( $downloadtext ) . '</a></center><br>' . PHP_EOL;
			}
			if ( 'text' == $type ) {
				$filepath = trailingslashit( $uploaddir['basedir'] ) . $attachment['file'];
				echo '<div id="attachment-text"><textarea style="width:100%; height:500px;">' . PHP_EOL;
					// 2.2.0: use wp_kses on text output
					$allowed = bioship_allowed_html( 'content', 'file' );
					echo wp_kses( bioship_file_get_contents( $filepath ), $allowed );
				echo '</textarea></div><br>' . PHP_EOL;
			}
			echo PHP_EOL . '</div>' . PHP_EOL;
			bioship_html_comment( '/#attachment' );
			echo PHP_EOL;

			// Attachment Meta
			// ---------------
			// 2.2.0: use esc_html instead of esc_attr on info labels
			bioship_html_comment( '.attachment-meta' );
			echo '<div class="attachment-meta">' . PHP_EOL;
			echo '	<div class="media-info ' . esc_attr( $type ) . '-info">' . PHP_EOL;
			echo '		<h4 class="attachment-meta-title">';
			if ( 'audio' == $type ) {
				echo esc_html( __( 'Audio Info', 'bioship' ) );
			} elseif ( 'video' == $type ) {
				echo esc_html( __( 'Video Info', 'bioship' ) );
			} elseif ( 'image' == $type ) {
				echo esc_html( __( 'Image Info', 'bioship' ) );
			} elseif ( 'text' == $type ) {
				echo esc_html( __( 'Text Info', 'bioship' ) );
			} elseif ( 'application' == $type ) {
				echo esc_html( __( 'Attachment Info', 'bioship' ) );
			}
			echo '</h4>' . PHP_EOL;

			// 2.1.4: added function_exists check for Hybrid function
			// 2.1.4: temporarily disabled as uncertain on usage here
			if ( in_array( $type, $hybrid_types ) ) {
				if ( function_exists( 'hybrid_media_meta' ) ) {
					hybrid_media_meta();
				}
			}
			echo PHP_EOL . '	</div>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/.attachment-meta' );
			echo PHP_EOL;

			// --- remove default WordPress attachment display (prepended to content) ---
			// TODO: recheck prepend_attachment filter for improving media handler ?
			remove_filter( 'the_content', 'prepend_attachment' );
			return;
		}

		// ------------
		// Post Formats
		// ------------
		// (audio/video/image etc.)
		if ( '1' != $vthemesettings['postformatsupport'] ) {
			return;
		}

		// Audio Grabber
		// -------------
		if ( ( '1' == $vthemesettings['postformats']['audio'] ) && has_post_format( 'audio' ) ) {
			if ( !THEMEHYBRID && !function_exists( 'hybrid_media_grabber' ) ) {
				bioship_load_hybrid_media();
			}
			$audio = hybrid_media_grabber( array('type' => 'audio', 'split_media' => true ) );
			if ( $audio ) {
				echo '<div id="post-format-media" class="post-format-audio">' . PHP_EOL;
				// 2.2.0: use wp_kses on audio output
				$allowed = bioship_allowed_html( 'media', 'audio' );
				echo '	' . wp_kses( $audio, $allowed ) . PHP_EOL;
				echo '</div>' . PHP_EOL;
			}
		}

		// Video Grabber
		// -------------
		if ( ( '1' == $vthemesettings['postformats']['video'] ) && has_post_format( 'video' ) ) {
			if ( !THEMEHYBRID && !function_exists( 'hybrid_media_grabber' ) ) {
				bioship_load_hybrid_media();
			}
			$video = hybrid_media_grabber( array('type' => 'video', 'split_media' => true ) );
			if ( $video ) {
				echo '<div id="post-format-media" class="post-format-video">' . PHP_EOL;
				// 2.2.0: use wp_kses on video output
				$allowed = bioship_allowed_html( 'media', 'video' );
				echo '	' . wp_kses( $video, $allowed ) . PHP_EOL;
				echo '</div>' . PHP_EOL;
			}
		}

		// Image Grabber
		// -------------
		if ( ( '1' == $vthemesettings['postformats']['image'] ) && has_post_format( 'image' ) ) {
			if ( !THEMEHYBRID && !function_exists( 'hybrid_media_grabber' ) ) {
				bioship_load_hybrid_media();
			}
			$image = get_the_image( array( 'echo' => false, 'size' => 'full', 'split_content' => true, 'scan_raw' => true, 'scan' => true, 'order' => array( 'scan_raw', 'scan', 'featured', 'attachment' ) ) );
			if ( $image ) {
				echo '<div id="post-format-media" class="post-format-image">' . PHP_EOL;
					// 2.2.0: use wp_kses on image output
					$allowed = bioship_allowed_html( 'media', 'image' );
					echo '	' . wp_kses( $image, $allowed ) . PHP_EOL;
				echo '</div>' . PHP_EOL;

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
			//		phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			// 		echo $gallery;
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
if ( !function_exists( 'bioship_skeleton_entry_header_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_header', 'bioship_skeleton_entry_header_open', 0 );

	function bioship_skeleton_entry_header_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- entry header wrap open ---
		bioship_html_comment( '.entry-header' );
		// #####
		echo '<header ';
		hybrid_attr( 'entry-header' );
		echo '>' . PHP_EOL;
	}
}

// -----------------------
// Entry Header Wrap Close
// -----------------------
if ( !function_exists( 'bioship_skeleton_entry_header_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_header', 'bioship_skeleton_entry_header_close', 10 );

	function bioship_skeleton_entry_header_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- entry header wrap close ---
		echo PHP_EOL . '</header>' . PHP_EOL;
		bioship_html_comment( '/.entry-header' );
		echo PHP_EOL;
	}
}

// ------------------
// Entry Header Title
// ------------------
if ( !function_exists( 'bioship_skeleton_entry_header_title' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_header', 'bioship_skeleton_entry_header_title', 2 );

	function bioship_skeleton_entry_header_title() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get post values ---
		global $post;
		$post_id = $post->ID;
		$post_type = $post->post_type;

		// --- open heading tag ---
		// 1.5.0: use h3 instead of h2 for archive/excerpt listings
		// 2.2.0: echo heading tags directly
		bioship_html_comment( '.entry-title' );
		if ( is_archive() || is_search() || !is_singular( $post_type ) ) {
			echo '<h3 ';
		} else {
			echo '<h2 ';
		}
		hybrid_attr( 'entry-title' );
		echo '>' . PHP_EOL;

		// --- output entry title ---
		$permalink = get_the_permalink( $post_id );
		// translaters: replacement string is the post title
		$title = sprintf( __( 'Permalink to %s', 'bioship' ), the_title_attribute( 'echo=0' ) );
		echo '	<a href="' . esc_url( $permalink ) . '" rel="bookmark" itemprop="url" title="' . esc_attr( $title ) . '">';
			echo esc_html( get_the_title( $post_id ) ) . PHP_EOL;
		echo '</a>' . PHP_EOL;

		// --- close heading tag ---
		if ( is_archive() || is_search() || !is_singular( $post_type ) ) {
			echo '</h3>';
		} else {
			echo '</h2';
		}
		bioship_html_comment( '/.entry-title' );
		echo PHP_EOL;
	}
}

// ---------------------
// Entry Header Subtitle
// ---------------------
// Uses WP Subtitle plugin (still shows saved subtitle if plugin is deactivated)
if ( !function_exists( 'bioship_skeleton_entry_header_subtitle' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_header', 'bioship_skeleton_entry_header_subtitle', 4 );

	function bioship_skeleton_entry_header_subtitle() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get post values ---
		global $post;
		$post_id = $post->ID;
		$post_type = $post->post_type;

		// --- get the subtitle post metakey ---
		// 1.5.0: moved key filter here before WP subtitle check
		$subtitle_key = 'wps_subtitle';
		$subtitle_key = bioship_apply_filters( 'skeleton_subtitle_key', $subtitle_key );

		// --- get the subtitle ---
		if ( ( function_exists( 'get_the_subtitle' ) ) && ( 'wps_subtitle' == $subtitle_key ) ) {
			$subtitle = get_the_subtitle( $post_id, '', '', false );
		} else {
			$subtitle = get_post_meta( $post_id, $subtitle_key, true );
		}

		if ( $subtitle && ( '' != $subtitle ) ) {

			// --- open heading tag ---
			// 1.5.0: use h4 instead of h3 for archive/excerpt listings
			// 2.2.0: output heading tag directly
			bioship_html_comment( '.entry-subtitle' );
			if ( is_archive() || is_search() || !is_singular( $post_type ) ) {
				echo '<h4 ';
			} else {
				echo '<h3 ';
			}
			hybrid_attr( 'entry-subtitle' );
			echo '>' . PHP_EOL;

			// --- output the subtitle ---
			// 2.2.0: use esc_html instead of esc_attr on subtitle output
			// note: there are no default hybrid attributes for entry-subtitle
			echo esc_html( $subtitle ) . PHP_EOL;

			// --- close heading tag ---
			if ( is_archive() || is_search() || !is_singular( $post_type ) ) {
				echo '</h4>';
			} else {
				echo '</h3>';
			}
			bioship_html_comment( '/.entry-subtitle' );
			echo PHP_EOL;
		}
	}
}

// -----------------
// Entry Header Meta
// -----------------
if ( !function_exists( 'bioship_skeleton_entry_header_meta' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_header', 'bioship_skeleton_entry_header_meta', 6 );

	function bioship_skeleton_entry_header_meta() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get post values ---
		global $post;
		$post_id = $post->ID;
		$post_type = $post->post_type;

		// --- output entry meta top ---
		$meta = bioship_get_entry_meta( $post_id, $post_type, 'top' );
		if ( '' != $meta ) {
			bioship_html_comment( '.entry-meta' );
			echo '<div class="entry-meta entry-byline">' . PHP_EOL;
			// 2.2.0: use wp_kses for meta output
			$allowed = bioship_allowed_html( 'meta', 'entry_top' );
			echo '	' . wp_kses( $meta, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/.entry-meta' );
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
if ( !function_exists( 'bioship_skeleton_entry_footer_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_footer', 'bioship_skeleton_entry_footer_open', 0 );

	function bioship_skeleton_entry_footer_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output entry footer wrap open ---
		bioship_html_comment( '.entry-footer' );
		// #####
		echo '<footer ';
		hybrid_attr( 'entry-footer' );
		echo '>' . PHP_EOL;
	}
}

// -----------------------
// Entry Footer Wrap Close
// -----------------------
if ( !function_exists( 'bioship_skeleton_entry_footer_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_footer', 'bioship_skeleton_entry_footer_close', 10 );

	function bioship_skeleton_entry_footer_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output entry footer wrap close ---
		echo PHP_EOL . '</footer>';
		bioship_html_comment( '/.entry-footer' );
		echo PHP_EOL;
	}
}

// -----------------
// Entry Footer Meta
// -----------------
if ( !function_exists( 'bioship_skeleton_entry_footer_meta' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_entry_footer', 'bioship_skeleton_entry_footer_meta', 6 );

	function bioship_skeleton_entry_footer_meta() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get post values ---
		global $post;
		$post_id = $post->ID;
		$post_type = get_post_type();

		// --- output entry meta bottom ---
		$meta = bioship_get_entry_meta( $post_id, $post_type, 'bottom' );
		if ( '' != $meta ) {
			bioship_html_comment( '.entry-utility' );
			echo '<div ';
			hybrid_attr( 'entry-utility' );
			echo '>' . PHP_EOL;
			// 2.2.0: use wp_kses for meta output
			$allowed = bioship_allowed_html( 'meta', 'entry_bottom' );
			echo '	' . wp_kses( $meta, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/.entry-utility' );
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
if ( !function_exists( 'bioship_skeleton_echo_thumbnail' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_thumbnail', 'bioship_skeleton_echo_thumbnail', 5 );

	function bioship_skeleton_echo_thumbnail() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings;

		// --- check for media handler format ---
		// 1.8.0: bug out for image post format media
		if ( '1' == $vthemesettings['postformatsupport'] ) {
			if ( has_post_format( 'image' ) ) {
				// displayed by media handler
				return;
			}
		}

		// --- check current post number ---
		// 1.8.5: allow for custom query/loop numbering override
		global $wp_query;
		$post_number = '';
		if ( isset( $wp_query->current_post ) ) {
			$postnumber = $wp_query->current_post + 1;
		}
		$post_number = bioship_apply_filters( 'skeleton_loop_post_number', $post_number );

		// --- get post values ---
		// 1.5.0: improved thumbnail function
		global $post;
		$post_id = $post->ID;
		$post_type = get_post_type();
		bioship_debug( "Getting Thumbnail for Post ID, Type", $post_id . ',' . $post_type );
		$thumbnail = bioship_skeleton_get_thumbnail( $post_id, $post_type, $post_number );

		// --- output thumbnail content ---
		if ( '' != $thumbnail ) {
			bioship_do_action( 'bioship_before_thumbnail' );
				// 2.2.0: use wp_kses on thumbnail output
				$allowed = bioship_allowed_html( 'media', 'image' );
				echo wp_kses( $thumbnail, $allowed );
			bioship_do_action( 'bioship_after_thumbnail' );
		}
	}
}

// ---------------------------
// Get Thumbnail for Templates
// ---------------------------
// 1.5.0: moved here as separate (from content template)
if ( !function_exists( 'bioship_skeleton_get_thumbnail' ) ) {
	function bioship_skeleton_get_thumbnail( $post_id, $post_type, $post_number, $thumb_size = '' ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		global $vthemesettings;

		// --- set default values ---
		// 2.2.0: set classes as arrays
		$thumbnail = $method = '';
		// $wrapperclasses = 'thumbnail thumbnail-' . $postnumber;
		$wrapper_classes = array( 'thumbnail', 'thumbnail-' . $post_number );
		$thumb_classes = array();

		// 2.2.0: set array of old and new (prefixed) size names
		$sizes = array(
			'squared150'	=> 'bioship-150s',
			'squared250'	=> 'bioship-250s',
			'video43'		=> 'bioship-4-3',
			'video169'		=> 'bioship-16-9',
			'opengraph'		=> 'bioship-opengraph',
		);

		// --- check for thumbnail image and get sizes ---
		if ( is_archive() || is_search() || !is_singular( $post_type ) ) {

            // --- get list thumbnail size and alignment ---
            if ( '' == $thumb_size ) {
                $thumb_size = $vthemesettings['listthumbsize'];
            }
            $thumb_size = bioship_apply_filters( 'skeleton_list_thumbnail_size', $thumb_size );
            $thumblist_align = bioship_apply_filters( 'skeleton_list_thumbnail_align', $vthemesettings['thumblistalign'] );
            if ( ( 'none' != $thumblist_align ) && ( '' != $thumblist_align ) ) {
                if ( 'alternateleftright' == $thumblist_align ) {
                    $align = ( $post_number & 1 ) ? 'alignleft' : 'alignright';
                } elseif ( 'alternaterightleft' == $thumblist_align ) {
                    $align = ( $post_number & 1 ) ? 'alignright' : 'alignleft';
                } else {
                    $align = $thumblist_align;
                }
            }
            // 2.2.0: added isset check to align
            if ( isset( $align ) ) {
                $wrapper_classes[] = $align;
            }
			$thumb_classes[] = 'scale-with-grid';
			$thumb_classes[] = 'thumbtype-' . $post_type;

		} else {

			// --- set singular thumbnail size ---
			$thumb_size = ( 'page' == $post_type ) ? $vthemesettings['pagethumbsize'] : $vthemesettings['postthumbsize'];
			// 2.1.1: added post ID argument for post-specific filtering
			// 2.2.0: added post type argument for post type filtering
			$thumbsize = bioship_apply_filters( 'skeleton_post_thumbnail_size', $thumb_size, $post_id, $post_type );

			// --- for custom post type filtering switch to attachment method ---
			// 2.0.5: test for actual change not just with has_filter
			// 2.2.0: fix to use skeleton_ prefix instead of muscle_
			$new_thumb_size = bioship_apply_filters( 'skeleton_post_thumb_size_' . $post_type, $thumb_size );
			if ( $new_thumb_size != $thumb_size ) {
				// custom size overrides are set to 'post-thumbnail' type
				$method = 'attachment';
				$thumb_size = 'post-thumbnail';
				$thumb_classes[] = 'attachment-' . $thumb_size;
			}

			// --- allow for perpost meta override ---
			// 1.8.5: fix to perpost image display override check
			// 1.9.5: move override to after default and filters applied
			// 2.0.8: use prefixed post meta key
			// 2.1.1: revert to unprefixed post meta key
			$post_thumb_size = get_post_meta( $post_id, '_post_thumbsize', true );

			// --- fix for unprefixed size names ---
			// 2.0.5: auto-update post meta to new size names
			if ( $post_thumb_size ) {
				$new_thumb_size = false;
				// 2.2.0: loop array of old and new size names
				foreach ( $sizes as $old => $new ) {
					if ( $old == $post_thumb_size ) {
						$new_thumb_size = $new;
					}
				}
				if ( $new_thumb_size ) {
					update_post_meta( $post_id, '_postthumbsize', $new_thumb_size );
					$post_thumb_size = $new_thumb_size;
				}
			}
			if ( '' != $post_thumb_size ) {
				$thumb_size = $post_thumb_size;
			}

			// --- set thumbnail alignment and classes ---
			$thumb_align = ( 'page' == $post_type ) ? $vthemesettings['featuredalign'] : $vthemesettings['thumbnailalign'];
			$thumb_align = bioship_apply_filters( 'skeleton_post_thumbnail_align', $thumb_align );
			if ( ( 'none' != $thumb_align ) && ( '' != $thumb_align ) ) {
				$wrapperclasses[] = $thumb_align;
			}
			$thumb_classes[] = 'scale-with-grid';
			$thumb_classes[] = 'thumbtype-' . $post_type;
		}
		bioship_debug( "Thumbnail Size for Post ID " . $post_id, $thumb_size );

		// --- maybe get the thumbnail image ---
		if ( 'off' != $thumb_size ) {

			// --- set and filter wrapper and thumbnail classes ---
			// 2.2.0: filter classes via array
			if ( 'page' == $post_type ) {
				$wrapper_classes[] = 'featured-image';
			} else {
				$wrapper_classes[] = 'post-thumbnail';
			}
			$wrapper_classes = bioship_apply_filters( 'skeleton_thumbnail_wrapper_class', $wrapper_classes );
			$thumb_classes = bioship_apply_filters( 'skeleton_thumbnail_class', $thumb_classes );
			$wrapper_classes = implode( ' ', $wrapper_classes );
			$thumb_classes = implode( ' ', $thumb_classes );
			// 2.2.0: keep fallback to class string filter
			$wrapper_classes = bioship_apply_filters( 'skeleton_thumbnail_wrapper_classes', $wrapper_classes );
			$thumb_classes = bioship_apply_filters( 'skeleton_thumbnail_classes', $thumb_classes );

			// 2.0.5: convert old size names to new prefixed ones
			// 2.2.0: loop array of old and new size names
			foreach ( $sizes as $old => $new ) {
				if ( $old == $thumb_size ) {
					$thumb_size = $new;
				}
			}

			// 2.0.5: maybe auto-regenerate thumbnails (in case of a new size)
			bioship_regenerate_thumbnails( $post_id, $thumb_size );

			// --- output thumbnail ---
			$thumbnail = bioship_html_comment( '.thumbnail-' . $post_number, false );
			$thumbnail .= '<div id="postimage-' . esc_attr( $post_id ) . '" class="' . esc_attr( $wrapper_classes ) . '">' . PHP_EOL;
			// use Hybrid get_the_image extension with fallback to skeleton_thumbnailer
			if ( THEMEHYBRID && ( '1' == $vthemesettings['hybridthumbnails'] ) ) {
				$args = array(
					'post_id'		=> $post_id,
					'size'			=> $thumb_size,
					'image_class'	=> $thumb_classes,
					'echo'			=> false,
				);
				if ( 'attachment' == $method ) {
					$args['method'] = 'attachment';
				}
				bioship_debug( "Using Hybrid Get the Image Extension with Args", $args );
				$thumbnail .= get_the_image( $args );
			} else {
				bioship_debug( "Using Internal Thumbnail Grabber for Post ID", $post_id );
				$thumbnail .= bioship_skeleton_thumbnailer( $post_id, $thumb_size, $thumb_classes, $method );
			}
			$thumbnail .= PHP_EOL . '</div>' . PHP_EOL;
			// 2.2.0: fix to thumbnail display comment string append typo
			$thumbnail .= bioship_html_comment( '/.thumbnail-' . $post_number, false );
			$thumbnail .= PHP_EOL;
		}

		// 2.2.0: added post ID and post type to filter
		$thumbnail = bioship_apply_filters( 'skeleton_thumbnail_override', $thumbnail, $post_id, $post_type, $thumb_size );
		return $thumbnail;
	}
}

// --------------------
// Skeleton Thumbnailer
// --------------------
// 1.3.0: no longer a Skeleton content filter
// 1.5.0: changed to more general classes and added method
if ( !function_exists( 'bioship_skeleton_thumbnailer' ) ) {

	function bioship_skeleton_thumbnailer( $post_id, $thumb_size, $thumb_classes, $method = '' ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		if ( has_post_thumbnail( $post_id ) ) {
			// 1.5.0: added attachment method support for custom post types
			if ( 'attachment' == $method ) {

				// --- get post thumbnail attachment ID ---
				$attachment_id = get_post_thumbnail_id( $post_id );

				if ( $attachment_id ) {
					// --- get the attachment image with alt attributes ---
					// (via wp_get_attachment_image codex example)
					bioship_debug( "Using wp_get_attachment_image, Attachment ID", $attachment_id );
					$attributes = array(
						'class'	=> $thumb_classes,
						'alt'   => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
					);
					$image = wp_get_attachment_image( $attachment_id, $thumb_size, false, $attributes );
					return $image;
				}
			}

			// --- fallback to default thumbnail method ---
			// 2.0.5: simplified fallback method
			bioship_debug( "Using fallback to get_the_post_thumbnail, Post ID", $post_id );
			$image = get_the_post_thumbnail( $post_id, $thumb_size, array( 'class' => $thumb_classes ) );
			return $image;
		}

		// 2.2.0: add missing return empty on no thumbnail
		return '';
	}
}


// ------------------
// === Author Bio ===
// ------------------

// ----------------------
// Echo Author Bio Action
// ----------------------
// 1.9.8: abstracted for bottom and top
if ( !function_exists( 'bioship_skeleton_echo_author_bio' ) ) {
	function bioship_skeleton_echo_author_bio( $position ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- check author bio context ---
		// 2.0.5: undefined variable warning fix
		$author_bio = false;
		if ( is_author() ) {
			$author_bio = bioship_skeleton_author_bio_box( 'archive', 'archive', $position );
		} elseif ( is_singular() ) {
			global $post;
			$post_id = $post->ID;
			$post_type = $post->post_type;
			$authorbio = bioship_skeleton_author_bio_box( $post_id, $post_type, $position );
		}

		// --- output author bio ---
		if ( $author_bio ) {
			bioship_do_action( 'bioship_before_author_bio' );
			bioship_locate_template( array( 'content/author-bio.php' ), true );
			bioship_do_action( 'bioship_after_author_bio' );
		}
	}
}

// ---------------------
// Echo Author Bio (Top)
// ---------------------
// 1.9.8: abstracted call for top and bottom
if ( !function_exists( 'bioship_skeleton_echo_author_bio_top' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_author_bio_top', 'bioship_skeleton_echo_author_bio_top', 5 );

	// 1.9.0: add author bio to author archive top ?
	// bioship_add_action('bioship_before_author', 'bioship_skeleton_echo_author_bio_top', 5);

	function bioship_skeleton_echo_author_bio_top() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- author bio for top position ---
		bioship_skeleton_echo_author_bio( 'top' );
	}
}

// ------------------------
// Echo Author Bio (Bottom)
// ------------------------
// 1.9.8: abstracted call
if ( !function_exists( 'bioship_skeleton_echo_author_bio_bottom' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_author_bio_bottom', 'bioship_skeleton_echo_author_bio_bottom', 5 );

	// 1.9.0: add author bio to author archive page bottom ?
	// bioship_add_action('bioship_after_author', 'bioship_skeleton_echo_author_bio_bottom', 5);

	function bioship_skeleton_echo_author_bio_bottom() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- author bio for bottom position ---
		bioship_skeleton_echo_author_bio( 'bottom' );
	}
}

// --------------
// Author Bio Box
// --------------
// 1.5.0: separated from content template
// if author has a description, show a bio on their entries
if ( !function_exists( 'bioship_skeleton_author_bio_box' ) ) {
	function bioship_skeleton_author_bio_box( $post_id, $post_type, $position ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		global $vthemesettings;

		// --- check for author bio description ---
		if ( !get_the_author_meta( 'description' ) ) {
			return false;
		}

		if ( ( 'archive' == $post_id ) && ( 'archive' == $post_type ) ) {

			// TODO: check/add author bio position setting for archives ?

			return false;

		} else {

			// --- check whether global show is on and filter ---
			// 1.8.0: fix to showbox filter variable
			// 2.2.1: add check if showbox set for this post type
			// 2.2.1: fix posttype variable to post_type
			$show_box = false;
			if ( isset( $vthemesettings['authorbiocpts'][$post_type] ) ) {
				$show_box = $vthemesettings['authorbiocpts'][$post_type];
			}
			$show_box = bioship_apply_filters( 'skeleton_author_bio_box', $show_box );
			if ( !$show_box ) {
				return false;
			}

			// --- check default position and filter ---
			$bio_pos = $vthemesettings['authorbiopos'];
			$bio_pos = bioship_apply_filters( 'skeleton_author_bio_box_position', $bio_pos );
			if ( ( 'top' == $position ) && ( !strstr( $bio_pos, 'top' ) ) ) {
				return false;
			}
			if ( ( 'bottom' == $position ) && ( !strstr( $bio_pos, 'bottom' ) ) ) {
				return false;
			}

			// 1.9.9: removed old meta check
			return true;
		}
	}
}

// -----------------------
// About Author Title Text
// -----------------------
// 1.5.0: moved from author-bio.php
if ( !function_exists( 'bioship_skeleton_about_author_title' ) ) {
	function bioship_skeleton_about_author_title() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get author display name ---
		// 1.8.0: use separately to get author display name
		// 2.0.5: fix to typo (vauthordosplay) :-/
		// 2.0.8: fix for non-singular display usage
		if ( is_singular() ) {
			global $post;
			$author_display = bioship_get_author_display_by_post( $post->ID );
			// translators: replacement string is author display name
			$boxtitle = sprintf( __( 'About %s', 'bioship' ), $author_display );
		} else {
			$boxtitle = __( 'About the Author', 'bioship' );
		}

	 	// --- apply filters and return ---
	 	// 2.0.5: fix to fatal function typo (apply_filter)
	 	// 2.2.0: fix to mismatched variable name (vboxtitle)
		$box_title = bioship_apply_filters( 'skeleton_about_author_text', $box_title );
		return $box_title;
	}
}

// ------------------------
// About Author Description
// ------------------------
// 2.0.5: separated to add filter
// 2.0.8: fix to incorrect function prefix (missing _skeleton)
if ( !function_exists( 'bioship_skeleton_about_author_description' ) ) {
	function bioship_skeleton_about_author_description() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get author ---
		// 2.0.8: fix to get description outside the content loop
		// 2.0.8: fix to singular post check for post object
		$post_id = false;
		if ( is_singular() ) {
			global $post;
			$post_id = $post->ID;
		}
		$author = bioship_get_author_by_post( $post_id );

		// --- get author description ---
		// 2.2.0: remove early return to allow filtering
		$author_desc = '';
		if ( $author ) {
			// 2.2.0: fix to mismatched variable name (vauthor)
			$author_desc = get_the_author_meta( 'description', $author->ID );
		}

		// --- filter and return ---
		// 2.2.0: added author as second filter argument
		$author_desc = bioship_apply_filters( 'skeleton_about_author_description', $author_desc, $author );
		return $author_desc;
	}
}

// -----------------
// Author Posts Text
// -----------------
// 1.5.0: moved from author-bio.php
// 1.8.0: fix for missing author URL
if ( !function_exists( 'bioship_skeleton_author_posts_link' ) ) {
	function bioship_skeleton_author_posts_link( $author_url = false ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		$post_id = $post_type = false;
		if ( is_singular() ) {
			global $post;
			$post_type = $post->post_type;
			$post_id = $post->ID;
		}

		// --- get author ---
		// 2.0.8: fix for possible missing author URL (author-bio.php template)
		$author = bioship_get_author_by_post( $post_id );
		if ( !$author ) {
		    // 2.2.0: added missing return value
			return false;
		}
		if ( !$author_url ) {
			$author_url = get_author_posts_url( $author->ID );
		}

		// --- get post type display name ---
		// 1.5.0: use post type display name
		// 2.0.8: fix for possible undefined variable
		$post_type_display = false;
		if ( 'page' == $post_type ) {
			$post_type_display = __( 'Pages', 'bioship' );
		} elseif ( 'post' == $post_type ) {
			$post_type_display = __( 'Posts', 'bioship' );
		} elseif ( $post_type ) {
			// 1.8.0: use the plural name not the singular one
			// $posttypedisplay = $posttypeobject->labels->singular_name;
			// 2.2.1: fix to posttypeobject variable mismatch
			$post_type_object = get_post_type_object( $post_type );
			$post_type_display = $post_type_object->labels->name;
		} else {
			$post_type_display = __( 'Writings', 'bioship' );
		}
		$post_type_display = bioship_apply_filters( 'skeleton_post_type_display', $post_type_display );
		// 2.0.8: bug out if unable to get valid post type display label
		if ( !$post_type_display ) {
			return false;
		}

		// --- get author display name ---
		// 1.8.0: use separately to get author display name
		// 2.0.8: bug out if unable to get valid author display name
		$author_display = bioship_get_author_display( $author );
		if ( !$author_display ) {
			return false;
		}

		// --- set anchor text ---
		// 1.5.5: fix to translations here for theme check
		// 2.2.0: improve replacement text for anchor link
		$anchor = sprintf( __( 'View all %s by %s', 'bioship' ), $post_type_display, $author_display );
		$anchor .= ' <span class="meta-nav">&rarr;</span>';
		$anchor = bioship_apply_filters( 'skeleton_author_posts_anchor', $anchor );
		if ( !$anchor ) {
		    return false;
		}

		// --- set author link ---
		// 1.8.5: class attribute override fix
		$attributes = hybrid_get_attr( 'entry-author', '', array( 'class' => 'author vcard entry-author' ) );
		$author_link = '<span ' . $attributes . '>' . PHP_EOL;
		$author_link .= '	<a class="url fn n" href="' . esc_url( $author_url ) . '">' . esc_html( $anchor ) . '</a>' . PHP_EOL;
		$author_link .= '</span>' . PHP_EOL;

		// --- filter and return ---
		// 2.0.8: added override filter for author link HTML
		$author_link = bioship_apply_filters( 'skeleton_author_link_html', $author_link );
		return $author_link;
	}
}


// ----------------
// === Comments ===
// ----------------

// --------------------
// Echo Comments Action
// --------------------
if ( !function_exists( 'bioship_skeleton_echo_comments' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_comments', 'bioship_skeleton_echo_comments', 5 );

	function bioship_skeleton_echo_comments() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- load comments template ---
		// note: comments template filter is located in functions.php
		// 1.5.0: Loads the comments template (default /comments.php)
		if ( have_comments() || comments_open() ) {
			comments_template( '/comments.php', true );
		} else {
			// note: default is to NOT say (irrelevently) that "comments are closed"
			$comments_closed = bioship_apply_filters( 'skeleton_comments_closed_text', '' );
			bioship_html_comment( '.commentclosed' );
			echo '<p class="commentsclosed">' . PHP_EOL;
				// 2.2.0: use wp_kses on comments closed output
				$allowed = bioship_allowed_html( 'content', 'comments' );
				echo wp_kses( $comments_closed, $allowed ) . PHP_EOL;
			echo '</p>' . PHP_EOL;
			bioship_html_comment( '/.commentsclosed' );
		}
	}
}

// --------------------------
// Skeleton Comments Callback
// --------------------------
// note: wp_list_comments callback called in comments.php
if ( !function_exists( 'bioship_skeleton_comments' ) ) {
	function bioship_skeleton_comments( $comment, $args, $depth ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		global $vthemesettings;
		$GLOBALS['comment'] = $comment;

		// --- maybe set comment buttons class ---
		// 1.8.5: added comment edit/reply link buttons option
		$commentbuttons = '';
		if ( isset( $vthemesettings['commentbuttons'] ) && ( '1' == $vthemesettings['commentbuttons'] ) ) {
			$commentbuttons = ' button';
		}

		// --- filter comment avatar size
		$avatarsize = bioship_apply_filters( 'skeleton_comments_avatar_size', 48 );

		// --- output comment ---
		// TODO: optimize comments callback template ?
		bioship_html_comment( 'li.comment' );

		// TODO: maybe use Hybrid comment attributes ?
		// echo '<li ' . hybrid_get_attr('comment') . '>' . PHP_EOL;
		$comment_id = get_comment_ID();
		$comment_classes = get_comment_class();
		$class_list = implode( ' ', $comment_classes );
		echo '<li id="li-comment-' . esc_attr( $comment_id ) . '" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;

		echo '<div id="comment-' . esc_attr( $comment_id ) . '" class="single-comment clearfix">' . PHP_EOL;

			// --- comment avatar ---
			echo '<div class="comment-author vcard">' . get_avatar( $comment, $avatarsize ) . '</div>' . PHP_EOL;

			// --- comment metadata ---
			echo '<div class="comment-meta commentmetadata">' . PHP_EOL;
				// 2.2.0: use esc_html instead of esc_attr for output
				if ( '0' == $comment->comment_approved ) {
					echo '<em>' . esc_html( __( 'Comment is awaiting moderation', 'bioship' ) ) . '</em><br>' . PHP_EOL;
				}
				// 1.8.5: added 'on' and 'at' to string
				echo '<span class="comment-author-meta">' . esc_html( __( 'by', 'bioship' ) ) . ' ' . get_comment_author_link() . ' </span>' . PHP_EOL;
				echo '<br><span class="comment-time">' . esc_html( __( 'on', 'bioship' ) ) . ' ' . get_comment_date();
				echo ' ' . esc_html( __( 'at', 'bioship' ) ) . ' ' . get_comment_time() . '</span>' . PHP_EOL;
			echo '</div>';

			// --- comment edir button ---
			echo '<div class="comment-edit' . esc_attr( $commentbuttons ) . '">' . PHP_EOL;
				edit_comment_link( esc_html( __( 'Edit', 'bioship' ) ), ' ', ' ' );
			echo '</div>' . PHP_EOL;

			// --- comment reply link ---
			echo '<div class="comment-reply' . esc_attr( $commentbuttons ) . '">' . PHP_EOL;
				// 2.2.0: fix to variable typo replayargs
				$reply_args = array(
					'reply_text'	=> esc_html( __( 'Reply', 'bioship' ) ),
					'login_text'	=> esc_html( __( 'Login to Comment', 'bioship' ) ),
					'depth'		=> $depth,
				);
				$args = array_merge( $args, $reply_args );
				comment_reply_link( $args );
			echo '</div>' . PHP_EOL;

			// --- clear div ---
			echo '<div class="clear"></div>' . PHP_EOL;

			// --- comment text ---
			echo '<div class="comment-text">' . PHP_EOL;
				comment_text();
			echo '</div>' . PHP_EOL;

		echo PHP_EOL . '</div>' . PHP_EOL;
		bioship_html_comment( '/li.comment' );
		echo PHP_EOL;
	}
}

// ---------------------
// Comments Popup Script
// ---------------------
if ( !function_exists( 'bioship_skeleton_comments_popup_script' ) ) {

	add_action( 'wp_footer', 'bioship_skeleton_comments_popup_script', 11 );

	function bioship_skeleton_comments_popup_script() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// 1.9.9: added check for theme comments popup being used
		global $vthemecommentspopup;
		if ( !isset( $vthemecommentspopup ) || !$vthemecommentspopup ) {
			return;
		}

		// 1.9.9: only check comments_open on singular pages
		if ( is_archive() || ( is_singular() && comments_open() ) ) {
			// 1.8.5: changed default from 500x500 to 640x480
			$popupsize = bioship_apply_filters( 'skeleton_comments_popup_size', array( 640, 480 ) );
			// 1.8.0: added these checks to bypass possible filter errors
			if ( !is_array( $popupsize ) || ( 2 != count( $popupsize ) ) ) {
				$popupsize = array( 640, 480 );
			}
			if ( !is_numeric( $popupsize[0] ) || !is_numeric( $popupsize[1] ) ) {
				$popupsize = array( 640, 480 );
			}

			// TODO: maybe replace this as deprecated since WP 4.5+ with "no alternative available" ?!
			@comments_popup_script( $popupsize );
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
if ( !function_exists( 'bioship_skeleton_breadcrumbs' ) ) {
	function bioship_skeleton_breadcrumbs() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- no breadcrumbs on front page! ---
		// TODO: check for breadcrumbs on home (blog) page?
		if ( is_front_page() ) {
			return;
		}

		global $vthemesettings;

		$cpts = array();
		$display = false;
		$breadcrumbs = '';

		// TODO: maybe check existing page context ?
		if ( is_singular() ) {

			$post_type = get_post_type();
			$classes = $post_type . '-breadcrumb';
			if ( isset( $vthemesettings['breadcrumbposttypes'] ) ) {
				$cpts = $vthemesettings['breadcrumbposttypes'];
			}
			$cpts = bioship_apply_filters( 'skeleton_breadcrumb_post_types', $cpts );
			if ( !is_array( $cpts ) || ( 0 == count( $cpts ) ) ) {
				return;
			}
			bioship_debug( "Breadcrumbs for Single Post Types", $cpts );
			foreach ( $cpts as $cpt => $value ) {
				if ( ( $cpt == $post_type ) && ( '1' == $value ) ) {
					$display = true;
				}
			}

		} elseif ( is_archive() ) {

			$post_types = bioship_get_post_types();
			if ( !is_array( $post_types ) ) {
				$post_types = array( $post_types );
			}
			// 2.2.0: set multiple post types classes
			$classes = array();
			foreach ( $post_types as $post_type ) {
				$classes[] = $post_type . '-breadcrumb';
			}
			if ( isset( $vthemesettings['breadcrumbarchivetypes'] ) ) {
				$cpts = $vthemesettings['breadcrumbarchivetypes'];
			}
			$cpts = bioship_apply_filters( 'skeleton_breadcrumb_archive_types', $cpts );
			if ( !is_array( $cpts ) || ( 0 == count( $cpts ) ) ) {
				return;
			}
			foreach ( $cpts as $cpt => $value ) {
				if ( ( '1' == $value ) && in_array( $cpt, $post_types ) ) {
					$display = true;
				}
			}
			bioship_debug( "Breadcrumbs for Archive Post Types", $cpts );

		}

		// TODO: check display options for more breadcrumb contexts ?
		// elseif (is_author()) {$display = true;}
		// elseif (is_search()) {$display = true;}
		// elseif (is_404()) {$display = true;}
		// elseif (is_home()) {$display = true;}

		if ( $display ) {
			if ( '1' == $vthemesettings['hybridbreadcrumbs'] ) {
				// --- use Hybrid Breadcrumb Trail extension ---
				if ( function_exists( 'breadcrumb_trail' ) ) {
					$breadcrumbs = breadcrumb_trail();
				}
			} else {
				// TODO: add a fallback breadcrumb method if not using Hybrid ?
				$breadcrumbs = '';
			}
		}

		// --- filter and output breadcrumbs ---
		$breadcrumbs = bioship_apply_filters( 'skeleton_breadcrumb_override', $breadcrumbs );
		if ( '' != $breadcrumbs ) {
			bioship_html_comment( '#breadcrumb' );
			// 2.2.0: output class list (for possible multiple post types)
			$class_list = implode( ' ', $classes );
			echo '<div id="breadcrumb" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
			// 2.2.0: use wp_kses on breadcrumbs
			$allowed = bioship_allowed_html( 'menu', 'breadcrumbs' );
			echo '	' . wp_kses( $breadcrumbs, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#breadcrumb' );
			echo PHP_EOL;
		}
	}
}

// -----------------
// Check Breadcrumbs
// -----------------
// 1.8.5: added this check to hook breadcrumbs to singular/archive templates
// 1.9.8: move this check to very top so can be moved higher than before_loop
if ( !function_exists( 'bioship_skeleton_check_breadcrumbs' ) ) {

	add_action( 'wp', 'bioship_skeleton_check_breadcrumbs' );

	function bioship_skeleton_check_breadcrumbs() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- add breadcrumbs to position ---
		// 1.9.8: use new position filtered add_action method
		if ( is_singular() ) {
			bioship_add_action( 'bioship_before_singular', 'bioship_skeleton_breadcrumbs', 5 );
		} else {
			bioship_add_action( 'bioship_before_loop', 'bioship_skeleton_breadcrumbs', 5 );
		}
	}
}


// -----------------------
// === Page Navigation ===
// -----------------------

// ----------------------
// Output Page Navigation
// ----------------------
// (with WP Pagenavi plugin support)
if ( !function_exists( 'bioship_skeleton_page_navigation' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_page_navi', 'bioship_skeleton_page_navigation', 5 );

	function bioship_skeleton_page_navigation() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings;

		// 1.5.0: filter whether to display page navigation for post / archive
		$display = false; // default to not display then check
		$archive = false; // add switch for post type archives

		// TODO: maybe check page context instead ?
		if ( is_singular() ) {

			// --- check post type ---
			// 1.8.0: simplified to get post type
			$cpts = array();
			if ( isset( $vthemesettings['pagenavposttypes'] ) && is_array( $vthemesettings['pagenavposttypes'] ) ) {
				foreach ( $vthemesettings['pagenavposttypes'] as $cpt => $value ) {
					if ( '1' == $value ) {
						$cpts[] = $cpt;
					}
				}
			}
			$cpts = bioship_apply_filters( 'skeleton_pagenavi_post_types', $cpts );
			if ( is_array( $cpts ) && ( count( $cpts ) > 0 ) ) {
				$post_type = get_post_type();
				if ( in_array( $post_type, $cpts ) ) {
					$display = true;
				}
			}

		} elseif ( is_archive() ) {

			$archive = true;

			// --- check post types ---
			// 1.8.5: use new get post type helper
			$cpts = array();
			if ( isset( $vthemesettings['pagenavarchivetypes'] ) && is_array( $vthemesettings['pagenavarchivetypes'] ) ) {
				foreach ( $vthemesettings['pagenavarchivetypes'] as $cpt => $value ) {
					if ( '1' == $value ) {
						$cpts[] = $cpt;
					}
				}
			}
			$cpts = bioship_apply_filters( 'skeleton_pagenavi_archive_types', $cpts );
			if ( is_array( $cpts ) && ( count( $cpts ) > 0 ) ) {
				$post_types = bioship_get_post_types();
				if ( !is_array( $post_types ) ) {
					$post_types = array( $post_types );
				}
				if ( array_intersect( $cpts, $post_types ) ) {
					// for labels...
					$display = true;
					$post_type = $post_types[0];
				}
			}

		} else {

			// --- check for front page and blog page ---
			// 2.1.4: added missing pagination check for front and blog pages
			$show_on_front = get_option( 'show_on_front' );
			if ( ( is_home() && ( 'page' == $show_on_front ) ) || ( is_front_page() && ( 'posts' == $show_on_front ) ) ) {

				$archive = true;

				$cpts = array();
				if ( isset( $vthemesettings['pagenavarchivetypes'] ) && is_array( $vthemesettings['pagenavarchivetypes'] ) ) {
					foreach ( $vthemesettings['pagenavarchivetypes'] as $cpt => $value ) {
						if ( '1' == $value ) {
							$cpts[] = $cpt;
						}
					}
				}
				$cpts = bioship_apply_filters( 'skeleton_pagenavi_archive_types', $cpts );

				if ( is_home() ) {
					$post_type = bioship_apply_filters( 'blog_page_post_type', 'post' );
				} elseif ( is_front_page() ) {
					$post_type = bioship_apply_filters( 'front_page_post_type', 'post' );
				}
				if ( in_array( $post_type, $cpts ) ) {
					$display = true;
				}
			}
		}

		$page_nav = '';
		if ( $display ) {

			// 1.5.0: handle other CPT display names
			// 1.8.5: moved inside display check
			if ( 'page' == $post_type ) {
				$post_type_display = __( 'Page', 'bioship' );
			} elseif ( 'post' == $post_type ) {
				$post_type_display = __( 'Post', 'bioship' );
			} else {
				$post_type_object = get_post_type_object( $post_type );
				$post_type_display = $post_type_object->labels->singular_name;
			}
			$post_type_display = bioship_apply_filters( 'skeleton_post_type_display', $post_type_display );

			// 1.8.0: left and right arrows for RTL and non-RTL display
			// 2.1.1: fix undefined index warning for opposite values
			$prev_left = $next_left = $prev_right = $next_right = '';
			if ( is_rtl() ) {
				$prev_right = ' &rarr;';
				$next_left = '&larr; ';
			} else {
				$prev_left = '&larr; ';
				$next_right = ' &rarr;';
			}

			// TODO: maybe handle image navigation with next_image_link and previous_image_link ?

			// 1.8.5: re-ordered logic
			// 2.2.0: fix to escapingwith esc_html
			if ( !is_page() && is_singular() ) {
				$next_label = __( 'Next', 'bioship' );
				$prev_label = __( 'Previous', 'bioship' );
				$next = $next_left . $next_label . ' ' . $post_type_display . $next_right;
				$prev = $prev_left . $prev_label . ' ' . $post_type_display . $prev_right;
				$next_post = get_next_post_link( '<div class="nav-next">%link</div>', $next );
				$prev_post = get_previous_post_link( '<div class="nav-prev">%link</div>', $prev );

				// 1.8.5: added RTL switchover
				if ( is_rtl() ) {
					$page_nav = $next_post . $prev_post;
				} else {
					$page_nav = $prev_post . $next_post;
				}
			}

			// --- defaults to WP PageNavi plugin ---
			// 1.5.5: some translation fixes to pass theme check
			if ( function_exists( 'wp_pagenavi' ) ) {

				// 1.8.5: add buffer to allow for override
				ob_start();
				if ( !is_singular() ) {
					wp_pagenavi();
				}
				$page_nav = ob_get_contents();
				ob_end_clean();

			} elseif ( $archive ) {

				// 1.8.0: use the plural label name
				$post_type_object = get_post_type_object( $post_type );
				$post_type_display = $post_type_object->labels->name;
				$post_type_display = bioship_apply_filters( 'skeleton_post_type_display', $post_type_display );

				// 2.1.1: fix to nexposts variable typo
				// 2.1.4: fix (switch) incorrect older and newer labels (as default is descending)
				$next_label = __( 'Older', 'bioship' );
				$prev_label = __( 'Newer', 'bioship' );
				$next = $next_left . $next_label . ' ' . $post_type_display . $next_right;
				$prev = $prev_left . $prev_label . ' ' . $post_type_display . $prev_right;
				$next_posts = get_next_posts_link( '<div class="nav-next">' . $next . '</div>' );
				$prev_posts = get_previous_posts_link( '<div class="nav-prev">' . $prev . '</div>' );

				// 1.8.5: added rtl switchover
				if ( is_rtl() ) {
					$page_nav = $next_posts . $prev_posts;
				} else {
					$page_nav = $prev_posts . $next_posts;
				}

				// TODO: maybe use post navigation paginate option ?
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
		$page_nav = bioship_apply_filters( 'skeleton_pagenavi_override', $page_nav );
		if ( '' != $page_nav ) {
			bioship_html_comment( '#nav-below' );
			echo '<div id="nav-below" class="navigation">' . PHP_EOL;
			// 2.2.0: use wp_kses on page nav output
			$allowed = bioship_allowed_html( 'menu', 'page_navigation' );
			echo '	' . wp_kses( $page_nav, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#nav-below' );
			echo PHP_EOL;
			echo '<div class="clear"></div>' . PHP_EOL;
		}
	}
}

// ----------------
// Paged Navigation
// ----------------
// 1.8.5: separated from page navi for paged pages
// TODO: add position hook trigger for paged navigation ?
if ( !function_exists( 'bioship_skeleton_paged_navi' ) ) {
 function bioship_skeleton_paged_navi() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get page navigation ---
	if ( function_exists( 'wp_pagenavi' ) ) {
		ob_start();
		wp_pagenavi( array( 'type' => 'multipart' ) );
		$paged_nav = ob_get_contents();
		ob_end_clean();
	} else {
		$args = array(
			'before' => '<div class="page-link">' . esc_html( __( 'Pages', 'bioship' ) ) . ':',
			'after' => '</div>',
			'echo' => 0,
		);
		$paged_nav = wp_link_pages( $args );
	}

	// --- filter and output ---
	$paged_nav = bioship_apply_filters( 'skeleton_paged_navi_override', $paged_nav );
	// 2.2.0: use wp_kses on pagednav output
	$allowed = bioship_allowed_html( 'menu', 'paged_navigation' );
	echo wp_kses( $paged_nav, $allowed );
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
if ( !function_exists( 'bioship_skeleton_footer_open' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_open', 0 );

	function bioship_skeleton_footer_open() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- set and filter footer classes ---
		// 1.5.0: added footer class filter and grid class compatibility
		// 1.8.0: removed grid class compatibility (now for content grid only)
		$classes = array( 'main-footer', 'noborder' );
		$classes = bioship_apply_filters( 'skeleton_footer_classes', $classes );
		$class_list = implode( ' ', $classes );

		// --- output footer wrap open ---
		bioship_html_comment( '#footer' );
		echo '<div id="footer" class="' . esc_attr( $class_list ) . '">' . PHP_EOL;
		echo '	<div id="footerpadding" class="inner">' . PHP_EOL;
		// #####
		echo '		<footer ';
		hybrid_attr( 'footer' );
		echo '>' . PHP_EOL;
	}
}

// -----------------
// Footer Wrap Close
// -----------------
if ( !function_exists( 'bioship_skeleton_footer_close' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_close', 10 );

	function bioship_skeleton_footer_close() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output footer wrap close ---
		echo '		</footer>' . PHP_EOL;
		echo '	</div>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		bioship_html_comment( '/#footer' );
		echo PHP_EOL;
	}
}

// -------------
// Footer Extras
// -------------
if ( !function_exists( 'bioship_skeleton_footer_extras' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_extras', 2 );

	function bioship_skeleton_footer_extras() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get footer extras via filter ---
		// 1.6.0: removed theme option, now by filter only
		// 2.0.0: allow for usage of shorter footer extras filter name
		$footer_extras = bioship_apply_filters( 'skeleton_footer_extras', '' );
		// TODO: remove to backwards compatible filter list ?
		$footer_extras = bioship_apply_filters( 'skeleton_footer_html_extras', $footer_extras );

		// --- output footer extras ---
		if ( $footer_extras ) {
			// 1.8.0: changed #footer_extras to #footer-extras for consistency
			bioship_html_comment( '#footer-extras' );
			echo '<div id="footer-extras" class="footer-extras">' . PHP_EOL;
			echo '	<div class="inner">' . PHP_EOL;
			// 2.2.0: output footer extras via wp_kses
			$allowed = bioship_allowed_html( 'content', 'footer' );
			echo '		' . wp_kses( $footer_extras, $allowed ) . PHP_EOL;
			echo '	</div>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#footer-extras' );
			echo PHP_EOL;
		}
	}
}

// --------------
// Footer Widgets
// --------------
if ( !function_exists( 'bioship_skeleton_footer_widgets' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_widgets', 4 );

	function bioship_skeleton_footer_widgets() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- output footer sidebar ---
		// filterable to allow for custom post types (see filters.php)
		// default template is sidebar/footer.php
		$footer = bioship_apply_filters( 'skeleton_footer_sidebar', 'footer' );
		hybrid_get_sidebar( $footer );
	}
}

// ---------------
// Footer Nav Menu
// ---------------
if ( !function_exists( 'bioship_skeleton_footer_nav' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_nav', 6 );

	function bioship_skeleton_footer_nav() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- check for footer menu ---
		// 2.0.9: added missing menu declaration check
		// 2.1.0: added check for array key
		global $vthemelayout;
		if ( !isset( $vthemelayout['menus']['footer'] ) || !$vthemelayout['menus']['footer'] ) {
			return;
		}

		// --- set and filter menu settings ---
		$args = array(
			'theme_location'  => 'footer',
			'container'       => 'div',
			'container_id' 	  => 'footermenu',
			'menu_class'      => 'menu',
			'echo'            => false,
			'fallback_cb'     => false,
			'after'           => '',
			'depth'           => 1,
		);
		// 1.8.5: added missing setting filter
		// 2.0.1: fix to filter name typo
		// 2.0.5: added _setting suffix to filter name
		$args = bioship_apply_filters( 'skeleton_footer_menu_settings', $args );

		// --- output footer menu ---
		$menu = bioship_html_comment( '.footer-menu', false );
		$menu = '<div class="footer-menu" ' . hybrid_get_attr( 'menu', 'footer' ) . '>' . PHP_EOL;
		$menu .= '	' . wp_nav_menu( $args ) . PHP_EOL;
		$menu .= '</div>' . PHP_EOL;
		$menu .= bioship_html_comment( '/.footer-menu', false );
		$menu .= PHP_EOL;

		// --- filter and output ---
		// 2.1.2: added missing footer menu filter
		$menu = bioship_apply_filters( 'skeleton_footer_menu', $menu );
		// 2.2.0: output menu via wp_kses
		$allowed = bioship_allowed_html( 'menu', 'footer' );
		echo wp_kses( $menu, $allowed );
	}
}

// --------------
// Footer Credits
// --------------
if ( !function_exists( 'bioship_skeleton_footer_credits' ) ) {

	// 1.9.8: use new position filtered add_action method
	bioship_add_action( 'bioship_footer', 'bioship_skeleton_footer_credits', 8 );

	function bioship_skeleton_footer_credits() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- get default theme credits and filter ---
		// 1.9.9: get initial value using skeleton_credit_link
		$credits = bioship_skeleton_credit_link();
		$credits = bioship_apply_filters( 'skeleton_author_credits', $credits );

		// --- output site credits ---
		if ( $credits ) {
			bioship_html_comment( '#footercredits' );
			echo '<div id="footercredits">' . PHP_EOL;
			// 2.2.0: output credits via wp_kses
			$allowed = bioship_allowed_html( 'content', 'credits' );
			echo '	' . wp_kses( $credits, $allowed ) . PHP_EOL;
			echo '</div>' . PHP_EOL;
			bioship_html_comment( '/#footercredits' );
			echo PHP_EOL;
		}
	}
}

// ----------------
// Get Site Credits
// ----------------
// 1.9.9: use as direct return not as filter
if ( !function_exists( 'bioship_skeleton_credit_link' ) ) {
	function bioship_skeleton_credit_link() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings;

		// --- check for site credits ---
		if ( '' != $vthemesettings['sitecredits'] ) {
			if ( '0' == $vthemesettings['sitecredits'] ) {
				return '';
			}
			return $vthemesettings['sitecredits'];
		} else {
			// --- set default site credits ---
			$site_credits = '<div id="themecredits">' . PHP_EOL;
				$anchor = __( 'BioShip Framework', 'bioship' );
				if ( THEMECHILD ) {
					$site_credits .= esc_html( THEMEDISPLAYNAME ) . ' ';
					$site_credits .= esc_html( __( 'Theme for', 'bioship' ) ) . ' ';
					$anchor = 'BioShip';
				}
				$title = __( 'BioShip Responsive WordPress Theme Framework', 'bioship' );
				$site_credits .= '<a href="' . esc_url( THEMEHOMEURL ) . '" title="' . esc_attr( $title ) . '" target="_blank">' . esc_html( $anchor ) . '</a>';
				if ( THEMEPARENT ) {
					$site_credits .= ' ' . esc_html( __( 'by', 'bioship' ) ) . ' ';
					$site_credits .= '<a href="' . esc_url( THEMESUPPORT ) . '" title="WordQuest Alliance" target="_blank">WordQuest</a>' . PHP_EOL;
				}
			$site_credits .= '</div>' . PHP_EOL;
			return $site_credits;
		}
	}
}

// ------------------------
// The closet is now empty.
