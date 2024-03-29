<?php

// =====================
// === BioShip Skull ===
// = brainzzz brainzzz =
// =====================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// ---------------------------
// === skull.php Structure ===
// ---------------------------
// === Custom Theme Supports ===
// - maybe Disable Widgets Block Editor
// - Custom Background Support
// - Custom Logo Support
// - Custom Header Support
// === Register Nav Menus ===
// - Primary and Secondary
// - Header and Footer
// - Add Nav Menu Item Classes
// === Register Sidebars ===
// - Register Sidebar Helper
// - Register Sidebars
// - Inactive Sidebars Message
// - Add Active Widget Sidebars
// - Add Inactive Widget Sidebars
// - Init Active / Inactive Sidebars
// - Count Footer Widgets
// === Layout Setup ===
// - Widget Shortcodes
// - Widget Title Shortcodes
// - Theme Layout Loader
// - Set Page Context
// - Set Layout Max Width
// - Set Layout Grid Columns
// - Set Sidebar Layout
// - Set Sidebars for Position
// - Set Sidebar Column Width
// - Set SubSidebar Column Width
// - Set Content Width
// - Get Content Width
// - Set Content Column Width
// === Title Tag ===
// - Title Tag Support
// - Title Tag Filter
// - Title Tag Theme Default
// - wp_title Tag Output
// - wp_title Title Filter
// === Template Helpers ===
// - WP Body Open
// - Get Content Template
// - Get Header Template
// - Get Footer Template
// - Check Sidebar Template
// - Output Sidebar at Position
// - Get Merged Sidebar Template Info
// - Get Sidebar Template Info
// - Get Loop Template
// - Get Loop Title
// - Get Loop Description
// - Locate Template Wrapper
// - Comments Template Filter
// - Add Archive Templates to Hierarchy
// - Content Directory Template Filter
// - Get Author Avatar
// - Get Author via Post ID
// - Regenerate Thumbnail
// - Formattable Entry Meta Output
// === Setup Theme ===
// - Language Translation
// - Dynamic Editor Styles
// - Post Thumbnail Support
// - Set Default Thumbnail Size
// - Add Image Sizes
// === Site Meta Tags ===
// - Meta Generator Tag
// - Mobile Header Meta
// === Site Icons ===
// - Site Icons Loader
// - Site Icon Size Filter
// - Site Icon Generator
// - Favicon Generator
// - Apple Touch Icon Sizes
// - Apple Startup Images
// === Enqueue Scripts ===
// - Enqueue Skeleton Scripts
// - Load jQuery from Google CDN
// - Fix Skip Link Focus (IE11)
// === Enqueue Styles ===
// - Enqueue Frontend Stylesheets
// - Enqueue Admin Stylesheets
// - Enqueue Heading Typography
// - Font Resource Hints
// - AJAX Skin Styles
// - Print Skin Styles Inline
// - AJAX Admin CSS Loader
// - Print Admin Styles Inline
// - Print Login Styles Inline
// ---------------------------


// Development TODOs
// -----------------
// * test favicon ICO generator class from site icon ?
// - check page contexts agains /wp-includes/template-loader.php
// ? recheck usage of document_title_parts filter ?
// ? improve mobile sidebar button loading conditions ?
// ? allow for more specific sidebar template names ?
// ? retest blank sidebar output display behaviour ?
// ? maybe use hybrid_post_format_link for post format meta ?
// ? maybe add Gutenberg editor styles ?
// - ref: https://robinroelofsen.com/editor-styling-gutenberg
// ? maybe enqueue PIE for extra editor styles compatibility ?
// ? add button hover styles to editor style rules ?
// ? maybe add a 'post-thumbnail' image size (for the post writing screen) ?
// - ref: _wp_post_thumbnail_html in /wp-admin/includes/post.php
// ? test effect of specific-width mobile meta line ?
// ? maybe distinguish grid padding from grid margins ?
// ? check for internal skin theme value override ?


// -----------------------------
// === Custom Theme Supports ===
// -----------------------------

// ----------------------------------
// maybe Disable Widgets Block Editor
// ----------------------------------
// 2.2.0: added option to disable WP 5.8+ block-based Widget page editor
if ( !function_exists( 'bioship_disable_widget_block_editor' ) ) {

 add_action( 'after_setup_theme', 'bioship_disable_widget_block_editor' );

 function bioship_disable_widget_block_editor() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;
	if ( isset( $vthemesettings['disablewidgetblockeditor'] ) && ( '1' == $vthemesettings['disablewidgetblockeditor'] ) ) {
		remove_theme_support( 'widgets_block_editor' );
	}
 }
}

// -------------------------
// Custom Background Support
// -------------------------
// ref: https://codex.wordpress.org/Custom_Backgrounds
// 2.0.9: re-added for WP.org consistency/compliance
if ( !function_exists( 'bioship_custom_background_support' ) ) {

 add_action( 'after_setup_theme', 'bioship_custom_background_support' );

 function bioship_custom_background_support() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- only needed for WP.org compliance ---
	if ( !THEMEWPORG ) {
		return;
	}

	// --- set custom background defaults ---
	$defaults = array(
		'default-color'          => 'FFF',
		'default-image'          => '',
		'default-repeat'         => 'no-repeat',
		'default-position-x'     => 'left',
		'default-position-y'     => 'top',
		'default-size'           => '100%',
		'default-attachment'     => 'scroll',

		// function to be called in theme head section
		// note: _custom_background_cb sets postMessage transport
		'wp-head-callback'		 => '__return_false',

		// TODO: maybe add customizer preview callbacks ?
		// function to be called in preview page head section
		'admin-head-callback'    => '__return_false',
		// function to produce preview markup in the admin screen
		'admin-preview-callback' => '__return_false',
	);
	$defaults = bioship_apply_filters( 'skeleton_custom_background_defaults', $defaults );

	// --- add theme support ---
	add_theme_support( 'custom-background', $defaults );
 }
}

// -------------------
// Custom Logo Support
// -------------------
// 2.0.9: added for WP.org consistency/compliance
if ( !function_exists( 'bioship_custom_logo_support' ) ) {

 add_action( 'after_setup_theme', 'bioship_custom_logo_support' );

 function bioship_custom_logo_support() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- only needed for WP.org compliance ---
	if ( !THEMEWPORG ) {
		return;
	}

 	// --- make sure custom_logo is supported in this WP version ---
 	global $wp_version;
 	if ( version_compare( $wp_version, '4.5-alpha', '<' ) ) {
		return;
	}

	// --- set custom logo defaults ---
    $defaults = array(
        'height'      		=> 200,
        'width'       		=> 200,
        'flex-height'		=> true,
        'flex-width'  		=> true,
        // 'header-text'	=> array('site-title', 'site-description')

        // function to be called in theme head section
        'wp-head-callback'	=> '__return_false',

		// TODO: maybe add customizer preview callbacks ?
        //  function to be called in preview page head section
        'admin-head-callback'       => '__return_false',
        // function to produce preview markup in the admin screen
        'admin-preview-callback'    => '__return_false',
    );
    $defaults = bioship_apply_filters( 'skeleton_custom_logo_defaults', $defaults );
    add_theme_support( 'custom-logo', $defaults );

	// --- Customizer Partial Refresh ---
	// for .custom-logo-link (see class-wp-customize-manager.php)
	// note: _render_custom_logo_partial calls get_custom_logo() calls get_custom_logo filter
	if ( !function_exists( 'bioship_custom_logo_filter' ) ) {

	 add_filter( 'get_custom_logo', 'bioship_custom_logo_filter', 10, 2 );

	 function bioship_custom_logo_filter( $html, $blogid ) {
		// TODO: handle partial_refresh output ?
		return $html;
	 }
	}
 }
}

// ---------------------
// Custom Header Support
// ---------------------
// ref: https://developer.wordpress.org/themes/functionality/custom-headers/
if ( !function_exists( 'bioship_custom_header_support' ) ) {

 add_action( 'after_setup_theme', 'bioship_custom_header_support' );

 function bioship_custom_header_support() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- only needed for WP.org compliance ---
	if ( !THEMEWPORG ) {
		return;
	}

	$defaults = array(
		'default-image'      	=> '',
		'header-text'	 		=> false,
		'default-text-color' 	=> '000',
		'width'              	=> 960,
		'height'             	=> 200,
		'flex-width'         	=> true,
		'flex-height'        	=> true,
		// function to be called in theme head section
		'wp-head-callback'      => '__return_false',
	);
	$defaults = bioship_apply_filters( 'skeleton_custom_header_defaults', $defaults );
	add_theme_support( 'custom-header', $defaults );

	// --- remove custom header support ---
	// note: as we are using a header *background* not a custom header exactly,
	// support for this feature is actually an implementation mismatch
	remove_theme_support( 'custom-header' );
 }
}


// --------------------------
// === Register Nav Menus ===
// --------------------------
// 2.2.0: added to remove menus not supported message in WP5.3+
add_theme_support( 'menus' );
// 2.0.5: check has_nav_menu, add filters and store global setting
// 2.2.0: fix to space within function check
if ( !function_exists( 'bioship_register_nav_menus' ) ) {

 add_action( 'init', 'bioship_register_nav_menus' );

 function bioship_register_nav_menus() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.0.9: use vthemelayout global instead of vthememenus
	// 2.1.1: removed unneeded vthememenus defaults
	global $vthemesettings, $vthemelayout;

	// Primary Menu
	// ------------
	// 1.5.0: moved template call to skeleton.php
	if ( isset( $vthemesettings['primarymenu'] ) && ( '1' == $vthemesettings['primarymenu'] ) ) {
		$args = array( 'primary' => esc_attr( __( 'Primary Navigation', 'bioship' ) ) );
		register_nav_menus( $args );
		if ( has_nav_menu( 'primary' ) ) {
			// 2.0.9: use vthemelayout global subkey
			$vthemelayout['menus']['primary'] = bioship_apply_filters( 'skeleton_menu_primary', true );
		}
	}

	// Secondary Menu
	// --------------
	// note: though created, is not hooked anywhere
	// 1.5.0: moved template call to skeleton.php
	if ( isset( $vthemesettings['secondarymenu'] ) && ( '1' == $vthemesettings['secondarymenu'] ) ) {
		$args = array( 'secondary' => esc_attr( __( 'Secondary Navigation', 'bioship' ) ) );
		register_nav_menus( $args );
		if ( has_nav_menu( 'secondary' ) ) {
			// 2.0.9: use vthemelayout global subkey
			$vthemelayout['menus']['secondary'] = bioship_apply_filters( 'skeleton_menu_secondary', true );
		}
	}

	// Header Menu
	// -----------
	if ( isset( $vthemesettings['headermenu'] ) && ( '1' == $vthemesettings['headermenu'] ) ) {
		$args = array( 'header' => esc_attr( __( 'Header Navigation', 'bioship' ) ) );
		register_nav_menus( $args );
		// 2.0.6: fix to function typo causing fatal error! (hav_nav_menu)
		if ( has_nav_menu( 'header' ) ) {
			// 2.0.9: use vthemelayout global subkey
			$vthemelayout['menus']['header'] = bioship_apply_filters( 'skeleton_menu_header', true );
		}
	}

	// Footer Menu
	// -----------
	if ( isset( $vthemesettings['footermenu'] ) && ( '1' == $vthemesettings['footermenu'] ) ) {
		$args = array( 'footer' => esc_attr( __( 'Footer Navigation', 'bioship' ) ) );
		register_nav_menus( $args );
		if ( has_nav_menu( 'footer' ) ) {
			// 2.0.9: use vthemelayout global subkey
			$vthemelayout['menus']['footer'] = bioship_apply_filters( 'skeleton_menu_footer', true );
		}
	}

 }
}

// -------------------------
// Add Nav Menu Item Classes
// -------------------------
// ref: https://wordpress.stackexchange.com/a/354252/76440
// 2.2.0: added classes for easier styling of menu items
if ( !function_exists( 'bioship_nav_menu_item_classes' ) ) {

 add_filter( 'wp_nav_menu_objects', 'bioship_nav_menu_item_classes' );

 function bioship_nav_menu_item_classes( $items ) {

	// --- loop items to add classes ---
	$itemcount = count( $items );
	$lastmenuitems = $lastsubmenuitems = $lastsubsubmenuitems = array();
	foreach ( $items as $i => $item ) {

		$itemparent = $item->menu_item_parent;

		// --- maybe get next item and parent ---
		if ( isset( $nextitem ) ) {
			unset( $nextitem );
		}
		if ( isset( $items[( $i + 1 )] ) ) {
			$nextitem = $items[( $i + 1 )];
			$nextparent = $nextitem->menu_item_parent;
		}

		if ( 0 == $itemparent ) {

			// --- clear for top level menu item ---
			if ( isset( $parentmenu ) ) {
				unset( $parentmenu );
			}
			$foundsubmenu = $foundsubsubmenu = false;

			// --- set first menu class and store current menu index ---
			if ( 1 == $i ) {
				$items[$i]->classes[] = 'first-menu-item';
			} else {
				$lastfoundmenu = $i;
			}

			// --- check if next item is a submenu of this menu ---
			if ( isset( $nextitem ) && ( $nextparent == $item->ID ) ) {
				$parentmenu = $item->ID;
			}

			// --- store last found submenu and subsubmenu items ---
			if ( isset( $lastfoundsubmenu ) ) {
				$lastsubmenuitems[] = $lastfoundsubmenu;
				unset( $lastfoundsubmenu );
			}
			if ( isset( $lastfoundsubsubmenu ) ) {
				$lastsubsubmenuitems[] = $lastfoundsubsubmenu;
				unset( $lastfoundsubsubmenu );
			}

		} elseif ( isset( $parentmenu ) ) {

			// --- subsubmenu items ---
			if ( isset( $parentsubmenu ) && ( $itemparent == $parentsubmenu ) ) {

				// --- set first subsubmenu item class ---
				if ( !$foundsubsubmenu ) {
					$items[$i]->classes[] = 'first-subsubmenu-item';
					$foundsubsubmenu = true;
				}

				// --- store last subsubmenu item ---
				if ( !isset( $nextitem ) || ( isset( $nextitem ) && ( $nextparent != $parentsubmenu ) ) ) {
					$items[$i]->classes[] = 'last-subsubmenu-item';
				}

			} elseif ( $itemparent == $parentmenu ) {

				// --- submenu items ---
				$foundsubsubmenu = false;

				// --- add class for first submenu item ---
				if ( !$foundsubmenu ) {
					$items[$i]->classes[] = 'first-submenu-item';
					$foundsubmenu = true;
				}

				// --- store last submenu item ---
				if ( !isset( $nextitem ) || ( isset( $nextitem ) && ( $nextparent != $parentmenu ) ) ) {
					$lastfoundsubmenu = $i;
				}

				// --- set parent submenu ---
				if ( isset( $nextitem ) && ( $nextparent == $item->ID ) ) {
					$parentsubmenu = $item->ID;
				}
			}
		}
	}

	// --- if submenu or subsubmenu item is last item ---
	if ( isset( $lastfoundsubmenu ) ) {
		$lastsubmenuitems[] = $lastfoundsubmenu;
	}
	if ( isset( $lastfoundsubsubmenu ) ) {
		$lastsubsubmenuitems[] = $lastfoundsubsubmenu;
	}

	// --- add classes to last found menu items ---
	// 2.2.0: add extra checks if found items set
	if ( isset( $lastfoundmenu ) ) {
		$items[$lastfoundmenu]->classes[] = 'last-menu-item';
	}
	if ( count( $lastsubmenuitems ) > 0 ) {
		foreach ( $lastsubmenuitems as $index ) {
			$items[$index]->classes[] = 'last-submenu-item';
		}
	}
	if ( count( $lastsubsubmenuitems ) > 0 ) {
		foreach ( $lastsubsubmenuitems as $index ) {
			$items[$index]->classes[] = 'last-subsubmenu-item';
		}
	}

    return $items;
 }
}



// -------------------------
// === Register Sidebars ===
// -------------------------

// -----------------------
// Register Sidebar Helper
// -----------------------
// 1.8.5: added this helper
if ( !function_exists( 'bioship_register_sidebar' ) ) {
 function bioship_register_sidebar( $id, $settings, $class = '' ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 1.9.0: added class argument for widgets page
	$args = array(
		'name'			=> $settings['name'],
		'id'			=> $id,
		'description'	=> $settings['desc'],
		'class'			=> $class,
		'before_widget'	=> $settings['beforewidget'],
		'after_widget'	=> $settings['afterwidget'],
		'before_title'	=> $settings['beforetitle'],
		'after_title'	=> $settings['aftertitle'],
	);
	register_sidebar( $args );
 }
}

// -------------------------
// Inactive Sidebars Message
// -------------------------
// 1.9.6: add widget page message regarding lowercase titles meaning inactive
if ( !function_exists( 'bioship_widget_page_message' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'widgets_admin_page', 'bioship_widget_page_message' );

 function bioship_widget_page_message() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	// 2.1.1: add bold markup to note heading
 	echo '<div class="message">';
		echo '<b>' . esc_html( __( 'Note', 'bioship' ) ) . '</b>: ';
		echo esc_html( __( 'Inactive Theme Sidebars are listed with lowercase titles. Activate them via Theme Options -&gt; Skeleton -&gt; Sidebars tab', 'bioship' ) );
	echo '</div>';
 }
}

// --------------------------
// Add Active Widget Sidebars
// --------------------------
if ( !function_exists( 'bioship_widgets_init_active' ) ) {

 // 1.9.8: add active and inactive sidebars with different priorities
 // 2.0.5: move add_action internally for consistency
 add_action( 'widgets_init', 'bioship_widgets_init_active' );

 function bioship_widgets_init_active() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	bioship_widgets_init( true );
 }
}

// ----------------------------
// Add Inactive Widget Sidebars
// ----------------------------
if ( !function_exists( 'bioship_widgets_init_inactive' ) ) {

 // 1.9.8: add active and inactive sidebars with different priorities
 // 2.0.5: move add_action inside for consistency
 add_action( 'widgets_init', 'bioship_widgets_init_inactive', 12 );

 function bioship_widgets_init_inactive() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	bioship_widgets_init( false );
 }
}

// --------------------------------
// Init Active or Inactive Sidebars
// --------------------------------
// 1.8.5: use bioship_register_sidebar helper abstract to reduce code bloat
if ( !function_exists( 'bioship_widgets_init' ) ) {
 function bioship_widgets_init( $active = true ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings;
	$vts = $vthemesettings;

	// Set Sidebar Labels
	// ------------------
	// 1.8.5: set sidebar labels separately first

	// --- front and subfront sidebars ---
	$labels['frontpage']['name'] = esc_attr( __( 'Frontpage Sidebar', 'bioship' ) );
	$labels['subfrontpage']['name'] = esc_attr( __( 'Frontpage SubSidebar', 'bioship' ) );
	$labels['frontpage']['desc'] = $labels['subfrontpage']['desc'] = esc_attr( __( 'Shown only on FrontPage', 'bioship' ) );

	// --- home and subhome sidebars ---
	$labels['homepage']['name'] = esc_attr( __( 'Home (Blog) Sidebar', 'bioship' ) );
	$labels['subhomepage']['name'] = esc_attr( __( 'Home (Blog) SubSidebar', 'bioship' ) );
	$labels['homepage']['desc'] = $labels['subhomepage']['desc'] = esc_attr( __( 'Shown only on Home (Blog) Page', 'bioship' ) );

	// --- archive and subarchive sidebars ---
	// 1.9.8: remove old unused variable
	$labels['archive']['name'] = esc_attr( __( 'Archive Sidebar', 'bioship' ) );
	$labels['subarchive']['name'] = esc_attr( __( 'Archive Page SubSidebar', 'bioship' ) );
	$labels['archive']['desc'] = $labels['subarchive']['desc'] = esc_attr( __( 'Shown only on Archive Pages', 'bioship' ) );

		$labels['category']['name'] = esc_attr( __( 'Category Archives Sidebar', 'bioship' ) );
		$labels['subcategory']['name'] = esc_attr( __( 'Category Archives SubSidebar', 'bioship' ) );
		$labels['category']['desc'] = $labels['subcategory']['desc'] = esc_attr( __( 'Shown only on Category Archives', 'bioship' ) );

		$labels['taxonomy']['name'] = esc_attr( __( 'Taxonomy Archives Sidebar', 'bioship' ) );
		$labels['subtaxonomy']['name'] = esc_attr( __( 'Taxonomy Archives SubSidebar', 'bioship' ) );
		$labels['taxonomy']['desc'] = $labels['subtaxonomy']['desc'] = esc_attr( __( 'Shown only on Taxonomy Archives', 'bioship' ) );

		$labels['tag']['name'] = esc_attr( __( 'Tag Archives Sidebar', 'bioship' ) );
		$labels['subtag']['name'] = esc_attr( __( 'Tag Archives SubSidebar', 'bioship' ) );
		$labels['tag']['desc'] = $labels['subtag']['desc'] = esc_attr( __( 'Shown only on Tag Archives', 'bioship' ) );

		$labels['author']['name'] = esc_attr( __( 'Author Archives Sidebar', 'bioship' ) );
		$labels['subauthor']['name'] = esc_attr( __( 'Author Archives SubSidebar', 'bioship' ) );
		$labels['author']['desc'] = $labels['subauthor']['desc'] = esc_attr( __( 'Shown only on Author Archives', 'bioship' ) );

		$labels['date']['name'] = esc_attr( __( 'Date Archives Sidebar', 'bioship' ) );
		$labels['subdate']['name'] = esc_attr( __( 'Date Archives SubSidebar', 'bioship' ) );
		$labels['date']['desc'] = $labels['subdate']['desc'] = esc_attr( __( 'Shown only on Date Archives', 'bioship' ) );

	// --- search and subsearch sidebars ---
	$labels['search']['name'] = esc_attr( __( 'Search Page Sidebar', 'bioship' ) );
	$labels['subsearch']['name'] = esc_attr( __( 'Search Page SubSidebar', 'bioship' ) );
	$labels['search']['desc'] = $labels['subsearch']['desc'] = esc_attr( __( 'Shown only on Search Pages', 'bioship' ) );

	// --- 404 notfound sidebars ---
	$labels['notfound']['name'] = esc_attr( __( '404 Page Sidebar', 'bioship' ) );
	$labels['subnotfound']['name'] = esc_attr( __( '404 Page SubSidebar', 'bioship' ) );
	$labels['notfound']['desc'] = $labels['subnotfound']['desc'] = esc_attr( __( 'Shown only on 404 Not Found Pages', 'bioship' ) );

	// --- post / page sidebars ---
	$labels['primary']['name'] = esc_attr( __( 'Post/Page Sidebar', 'bioship' ) );
	$labels['primary']['desc'] = esc_attr( __( 'Shown for both Pages and Posts', 'bioship' ) );

	$labels['posts']['name'] = esc_attr( __( 'Posts Sidebar', 'bioship' ) );
	$labels['posts']['desc'] = esc_attr( __( 'Shown only for Posts', 'bioship' ) );

	$labels['pages']['name'] = esc_attr( __( 'Pages Sidebar', 'bioship' ) );
	$labels['pages']['desc'] = esc_attr( __( 'Shown only for Pages', 'bioship' ) );

		$labels['subsidiary']['name'] = esc_attr( __( 'Post/Page SubSidebar', 'bioship' ) );
		$labels['subsidiary']['desc'] = esc_attr( __( 'Shown for both Pages and Posts', 'bioship' ) );

		$labels['subpost']['name'] = esc_attr( __( 'Posts SubSidebar', 'bioship' ) );
		$labels['subpost']['desc'] = esc_attr( __( 'Subsidiary Sidebar for Posts only', 'bioship' ) );

		$labels['subpage']['name'] = esc_attr( __( 'Pages SubSidebar', 'bioship' ) );
		$labels['subpage']['desc'] = esc_attr( __( 'Subsidiary Sidebar for Pages only', 'bioship' ) );

	// --- header / footer sidebars ---
	$labels['header-widget-area']['name'] = esc_attr( __( 'Header Widget Area', 'bioship' ) );
	$labels['header-widget-area']['desc'] = esc_attr( __( 'Header Widget Area', 'bioship' ) );
	$labels['footer-widget-area-1']['name'] = esc_attr( __( 'First Footer Widget Area', 'bioship' ) );
	$labels['footer-widget-area-1']['desc'] = esc_attr( __( 'The first footer widget area', 'bioship' ) );
	$labels['footer-widget-area-2']['name'] = esc_attr( __( 'Second Footer Widget Area', 'bioship' ) );
	$labels['footer-widget-area-2']['desc'] = esc_attr( __( 'The second footer widget area', 'bioship' ) );
	$labels['footer-widget-area-3']['name'] = esc_attr( __( 'Third Footer Widget Area', 'bioship' ) );
	$labels['footer-widget-area-3']['desc'] = esc_attr( __( 'The third footer widget area', 'bioship' ) );
	$labels['footer-widget-area-4']['name'] = esc_attr( __( 'Fourth Footer Widget Area', 'bioship' ) );
	$labels['footer-widget-area-4']['desc'] = esc_attr( __( 'The fourth footer widget area', 'bioship' ) );

	// --- default sidebar widget wrappers ---
	// 1.8.5: set defaults for all sidebars
	$sidebarwrappers = array();
	$sidebarwrappers['beforewidget'] = '<div id="%1$s" class="widget-container %2$s">';
	$sidebarwrappers['afterwidget'] = '</div>';
	$sidebarwrappers['beforetitle'] = '<h3 class="widget-title">';
	$sidebarwrappers['aftertitle'] = '</h3>';
	$sidebarwrappers = bioship_apply_filters( 'skeleton_sidebar_widget_wrappers', $sidebarwrappers );

	// --- loop labels and add sidebar wrappers ---
	foreach ( $labels as $id => $settings ) {
		$labels[$id]['beforewidget'] = $sidebarwrappers['beforewidget'];
		$labels[$id]['afterwidget'] = $sidebarwrappers['afterwidget'];
		$labels[$id]['beforetitle'] = $sidebarwrappers['beforetitle'];
		$labels[$id]['aftertitle'] = $sidebarwrappers['aftertitle'];
	}
	// 1.8.5: allow for sidebar label/setting filtering
	$labels = bioship_apply_filters( 'skeleton_sidebar_settings', $labels );

	// --- set on/off sidebar arrays ---
	// 1.8.5: reorder all sidebars for improved display order
	// 1.9.0: added [off] to inactive sidebar labels
	// 1.9.5: removed [off] (new styling and lowercase is sufficient)
	// $off = '['.esc_attr(__('off','bioship')).'] ';
	$sidebarson = $sidebarsoff = array();

	// Header Area Sidebar
	// -------------------
	$id = 'header-widget-area'; // individual setting
	if ( '1' == $vts['headersidebar'] ) {
		$sidebarson[] = $id;
	} else {
		$sidebarsoff[] = $id;
		$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
	}

	// Front / Home Sidebars and SubSidebars
	// -------------------------------------
	$sidebartypes = array( 'frontpage', 'homepage' );
	foreach ( $sidebartypes as $id ) {
		if ( '1' == $vts['sidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
		$id = 'sub' . $id;
		if ( '1' == $vts['subsidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
	}

	// Post/Page Sidebars
	// ------------------

		// Main Primary Sidebar
		// --------------------
		$id = 'primary';
		if ( 'unified' == $vts['sidebarmode'] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}

			// Main Subsidiary Sidebar
			// -----------------------
			$id = 'subsidiary';
			if ( 'unified' == $vts['subsidiarysidebar'] ) {
				$sidebarson[] = $id;
			} else {
				$sidebarsoff[] = $id;
				$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
			}

		// Posts Sidebar
		// -------------
		$id = 'posts';
		if ( ( 'postsonly' == $vts['sidebarmode'] ) || ( 'dual' == $vts['sidebarmode'] ) ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}

			// Subsidiary Posts Sidebar
			// -----------------------
			$id = 'subpost';
			if ( ( 'postsonly' == $vts['subsidiarysidebar'] ) || ( 'dual' == $vts['subsidiarysidebar'] ) ) {
				$sidebarson[] = $id;
			} else {
				$sidebarsoff[] = $id;
				$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
			}

		// Pages Sidebar
		// -------------
		$id = 'pages';
		if ( ( 'pagesonly' == $vts['sidebarmode'] ) || ( 'dual' == $vts['sidebarmode'] ) ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}

			// Subsidiary Pages Sidebar
			// -----------------------
			$id = 'subpage';
			if ( ( 'pagesonly' == $vts['subsidiarysidebar'] ) || ( 'dual' == $vts['subsidiarysidebar'] ) ) {
				$sidebarson[] = $id;
			} else {
				$sidebarsoff[] = $id;
				$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
			}

	// Archive / Search Sidebars and SubSidebars
	// -------------------------------------------
	$sidebartypes = array( 'archive', 'search', 'notfound' );
	foreach ( $sidebartypes as $id ) {
		if ( '1' == $vts['sidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
		$id = 'sub' . $id;
		if ( '1' == $vts['subsidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
	}

	// Footer Area Sidebars
	// --------------------
	for ( $i = 1; $i < 5; $i++ ) {
		$id = 'footer-widget-area-' . $i;
		if ( $vts['footersidebars'] > ( $i - 1 ) ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
	}

	// Archive Specific Sidebars and SubSidebars
	// -----------------------------------------
	$sidebartypes = array( 'category', 'taxonomy', 'tag', 'author', 'date' );
	foreach ( $sidebartypes as $id ) {
		if ( '1' == $vts['sidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
		$id = 'sub' . $id;
		if ( '1' == $vts['subsidebars'][$id] ) {
			$sidebarson[] = $id;
		} else {
			$sidebarsoff[] = $id;
			$labels[$id]['name'] = strtolower( $labels[$id]['name'] );
		}
	}

	// Register Sidebars
	// -----------------
	$allwidgets = wp_get_sidebars_widgets();

	// --- register active sidebars ---
	// 1.8.5: split on and off declarations for display
	// 1.9.8: declare active and inactive with different priorities
	if ( $active && ( count( $sidebarson ) > 0 ) ) {
		foreach ( $sidebarson as $sidebarid ) {
			if ( is_admin() && is_active_sidebar( $sidebarid ) ) {
				// --- add widget count to sidebar label ---
				$widgetcount = count( $allwidgets[$sidebarid] );
				$labels[$sidebarid]['name'] .= ' (' . $widgetcount . ')';
			}
			bioship_register_sidebar( $sidebarid, $labels[$sidebarid], 'on' );
		}
	}

	// --- register inactive sidebars ---
	if ( !$active && ( count( $sidebarsoff ) > 0 ) ) {
		foreach ( $sidebarsoff as $sidebarid ) {
			if ( is_admin() && is_active_sidebar( $sidebarid ) ) {
				// --- add widget count to sidebar label ---
				$widgetcount = count( $allwidgets[$sidebarid] );
				$labels[$sidebarid]['name'] .= ' (' . $widgetcount . ')';
			}

			// 1.9.9: also show on customizer advanced options page only
			global $pagenow;
			if ( ( 'customize.php' == $pagenow ) || is_customize_preview() ) {
				if ( isset( $_REQUEST['options'] ) && ( 'advanced' == $_REQUEST['options'] ) ) {
				 	bioship_register_sidebar( $sidebarid, $labels[$sidebarid], 'off' );
				}
			} else {
				bioship_register_sidebar( $sidebarid, $labels[$sidebarid], 'off' );
			}
		}
	}

 }
}

// --------------------
// Count Footer Widgets
// --------------------
// 2.0.5: moved here from skeleton.php
if ( !function_exists( 'bioship_count_footer_widgets' ) ) {
	function bioship_count_footer_widgets() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// 2.2.0: force use of specified theme setting
		global $vthemesettings;
		$footerwidgets = $vthemesettings['footersidebars'];
		return $footerwidgets;

		// --- check for active widgets in footer sidebars ---
		// 1.9.5: simplify to just use theme overrides global here
		// 1.9.9: remove override check (not implemented)
		/* $footerwidgets = 0;
		for ( $i = 1; $i < 5; $i++ ) {
			if ( is_active_sidebar( 'footer-widget-area-' . $i ) ) {
				$footerwidgets++;
			}
		}
		return $footerwidgets; */
	}
}


// --------------------
// === Layout Setup ===
// --------------------

// -----------------
// Widget Shortcodes
// -----------------
// (override to maybe remove shortcode filter from Widget Text)
$widgettextshortcodes = bioship_apply_filters( 'muscle_widget_text_shortcodes', true );
if ( !$widgettextshortcodes ) {
	remove_filter( 'widget_text', 'do_shortcode' );
}

// -----------------------
// Widget Title Shortcodes
// -----------------------
// add shortcode filter to Widget Titles (and maybe override)
$widgettitleshortcodes = bioship_apply_filters( 'muscle_widget_title_shortcodes', true );
if ( $widgettitleshortcodes ) {
	add_filter( 'widget_title', 'do_shortcode' );
}

// -------------------
// Theme Layout Loader
// -------------------
// 1.8.5: calls all layout global setup functions
// 1. so layout can is filtered and passed to grid.php
// 2. sidebars are precalculated for body tag classes
if ( !function_exists( 'bioship_set_layout' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'wp', 'bioship_set_layout' );

 function bioship_set_layout() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	global $vthemelayout, $vthemesidebars, $vthemedisplay, $vthemeoverride;

	// --- get display overrides ---
	// 1.9.5: initialize theme display and templating overrides
	// 2.1.1: optimized get overrides logic
	$vthemedisplay = $vthemeoverride = array();
	if ( is_singular() ) {
		global $post;
		$post_id = $post->ID;
	} else {
		// 2.2.0: allow for archive post type styling
		$post_id = bioship_muscle_get_archive_post();
	}

	if ( $post_id ) {
		if ( THEMEDEBUG ) {
			echo "<!-- Override Post ID: " . esc_html( $post_id ) . " -->";
		}
		if ( function_exists( 'bioship_muscle_get_display_overrides' ) ) {
			$vthemedisplay = bioship_muscle_get_display_overrides( $post_id );
		}
		if ( function_exists( 'bioship_muscle_get_templating_overrides' ) ) {
			$vthemeoverride = bioship_muscle_get_templating_overrides( $post_id );
		}
	}

	// --- setup all layout globals ---
 	bioship_set_page_context();
 	bioship_set_max_width();
 	bioship_set_grid_columns();
	bioship_set_sidebar_layout();
	bioship_set_sidebar_columns();
	bioship_set_subsidebar_columns();
	bioship_set_content_width();

	if ( THEMEDEBUG ) {
		bioship_debug( "Theme Layout", $vthemelayout );
		$sidebars = $vthemesidebars;
		unset( $sidebars['output'] );
		bioship_debug( "Theme Sidebars", $sidebars );
		if ( $vthemesidebars['sidebar'] ) {
			echo "<!-- Sidebar Found -->";
		}
		if ( $vthemesidebars['subsidebar'] ) {
			echo "<!-- SubSidebar Found -->";
		}
	}

 }
}

// ----------------
// Set Page Context
// ----------------
// 1.8.5: added this page context helper
// TODO: check page contexts against /wp-includes/template-loader.php
if ( !function_exists( 'bioship_set_page_context' ) ) {
 function bioship_set_page_context() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	// 1.9.0: use new themelayout global
	global $vthemelayout;

	// --- get and set the page context ---
	$pagecontext = $subpagecontext = '';
	if ( is_front_page() ) {
		$pagecontext = 'frontpage';
	} elseif ( is_home() ) {
		$pagecontext = 'home';
	} elseif ( is_404() ) {
		$pagecontext = '404';
	} elseif ( is_search() ) {
		$pagecontext = 'search';
	} elseif ( is_singular() ) {
		// 2.2.0: set page context to single and subcontext to post type
		$pagecontext = 'single';
		$subpagecontext = get_post_type();
	} elseif ( is_archive() ) {
		$pagecontext = 'archive';
		if ( is_tag() ) {
			$subpagecontext = 'tag';
		} elseif ( is_category() ) {
			$subpagecontext = 'category';
		} elseif ( is_tax() ) {
			$subpagecontext = 'taxonomy';
		} elseif ( is_author() ) {
			$subpagecontext = 'author';
		} elseif ( is_date() ) {
			$subpagecontext = 'date';
		}
	}

	$vthemelayout['pagecontext'] = $pagecontext;
	$vthemelayout['subpagecontext'] = $subpagecontext;
 }
}

// --------------------
// Set Layout Max Width
// --------------------
// 1.8.5: added this setup helper
if ( !function_exists( 'bioship_set_max_width' ) ) {
 function bioship_set_max_width() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	global $vthemesettings, $vthemelayout;

	// --- get max width setting ---
 	$vthemelayout['maxwidth'] = $vthemesettings['layout'];
 	if ( '' == $vthemelayout['maxwidth'] ) {$vthemelayout['maxwidth'] = '960';}

	// --- filter and check max width ---
 	$maxwidth = bioship_apply_filters( 'skeleton_layout_width', $vthemelayout['maxwidth'] );
 	// 2.0.5: apply absint to filtered value
 	$maxwidth = absint( $maxwidth );
 	if ( $maxwidth && ( $maxwidth > 0 ) ) {
		// 2.2.0: add a filter for minimum width
		$minwidth = apply_filters( 'skeleteon_layout_minwidth', 320 );
 		if ( $maxwidth < $minwidth ) {
			$maxwidth = $minwidth;
		}
		$vthemelayout['maxwidth'] = $maxwidth;
 	}

 	return $vthemelayout['maxwidth'];
 }
}

// -----------------------
// Set Layout Grid Columns
// -----------------------
// 2.0.5: added missing function_exists wrapper
if ( !function_exists( 'bioship_set_grid_columns' ) ) {
 function bioship_set_grid_columns() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemesettings, $vthemelayout;

	// --- set valid grid column values ---
	$valid = array( 'twelve', 'sixteen', 'twenty', 'twentyfour' );

	// --- set and filter layout grid columns ---
	$gridcolumns = $vthemesettings['gridcolumns'];
	$columns = bioship_apply_filters( 'skeleton_grid_columns', $gridcolumns );
	if ( $columns != $gridcolumns ) {
		if ( is_numeric( $columns ) ) {
			$columns = bioship_number_to_word( $columns );
		} elseif ( is_string( $columns ) ) {
			$columns = bioship_number_to_word( bioship_word_to_number( $columns ) );
		}
		if ( $columns && in_array( $columns, $valid ) ) {
			$gridcolumns = $columns;
		}
	}
	if ( '' == $gridcolumns ) {
		// fallback to default
		$gridcolumns = 'sixteen';
	}
	$vthemelayout['gridcolumns'] = $gridcolumns;
	$vthemelayout['numgridcolumns'] = bioship_word_to_number( $gridcolumns );

	// --- set and filter content grid columns ---
	// 1.9.5: set content grid columns separately
	if ( isset( $vthemesettings['contentgridcolumns'] ) ) {
		$contentcolumns = $vthemesettings['contentgridcolumns'];
	} else {
		$contentcolumns = $gridcolumns;
	}
	$columns = bioship_apply_filters( 'skeleton_content_grid_columns', $contentcolumns );
	if ( $columns != $contentcolumns ) {
		if ( is_numeric( $columns ) ) {
			$columns = bioship_number_to_word( $columns );
		} elseif ( is_string( $columns ) ) {
			$columns = bioship_number_to_word( bioship_word_to_number( $columns ) );
		}
		if ( $columns && in_array( $columns, $valid ) ) {
			$contentcolumns = $columns;
		}
	}
	if ( '' == $contentcolumns ) {
		// fallback to default
		$contentcolumns = 'twentyfour';
	}
	$vthemelayout['contentgridcolumns'] = $contentcolumns;
	// 2.2.0: rename conflicting layout value key
	$vthemelayout['numcontentgridcolumns'] = bioship_word_to_number( $contentcolumns );

 }
}

// ------------------
// Set Sidebar Layout
// ------------------
// 1.8.0: added new sidebar templating setup
if ( !function_exists( 'bioship_set_sidebar_layout' ) ) {
 function bioship_set_sidebar_layout() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;

	// 1.9.0: set short names from theme sidebars global
	// 2.2.0: seems to be no need to set these at this point now
	/* if ( isset( $vthemesidebars['sidebars'] ) ) {
		$sidebars = $vthemesidebars['sidebars'];
	}
	if ( isset( $vthemesidebars['sidebar'] ) ) {
		$sidebar = $vthemesidebars['sidebar'];
	}
	if ( isset( $vthemesidebars['subsidebar'] ) ) {
		$subsidebar = $vthemesidebars['subsidebar'];
	} */

	// --- note: sidebar positions: left, inner left, inner right, right ---
	$leftsidebar = $innerleftsidebar = $innerrightsidebar = $rightsidebar = '';
	if ( is_singular() ) {
		$posttype = get_post_type();
	}
	if ( !isset( $vthemesidebars['sidebars'] ) ) {
		$vthemesidebars['sidebars'] = array();
	}

	if ( isset( $vthemesidebars['sidebarcontext'] ) || isset( $vthemesidebars['subsidebarcontext'] ) ) {

		$context = $vthemesidebars['sidebarcontext'];
		$subcontext = $vthemesidebars['subsidebarcontext'];
		bioship_debug( "Sidebar Context", $context );
		bioship_debug( "SubSidebar Context", $subcontext );

	} else {

		$context = $subcontext = '';

		// --- default sidebar and subsidebar states to off ---
		// 1.8.0: get the optional sidebar values (as possibly not set)
		// 1.8.5: changed to multicheck array values
		// 1.8.5: repeated for subsidebar options
		// 1.9.8: fix to first line of subsidebar variable flags
		// 2.2.0: moved states into logic definitions

		// --- check sidebar settings ---
		// 2.2.0: simplify to single line logic definitions
		$sets = $vthemesettings['sidebars'];
		$frontpagesidebar = ( isset( $sets['frontpage'] ) && ( '1' == $sets['frontpage'] ) ) ? true : false;
		$homepagesidebar = ( isset( $sets['homepage'] ) && ( '1' == $sets['homepage'] ) ) ? true : false;
		$searchsidebar = ( isset( $sets['search'] ) && ( '1' == $sets['search'] ) ) ? true : false;
		$notfoundsidebar = ( isset( $sets['notfound'] ) && ( '1' == $sets['notfound'] ) ) ? true : false;
		$archivesidebar = ( isset( $sets['archive'] ) && ( '1' == $sets['archive'] ) ) ? true : false;
		$categorysidebar = ( isset( $sets['category'] ) && ( '1' == $sets['category'] ) ) ? true : false;
		$taxonomysidebar = ( isset( $sets['taxonomy'] ) && ( '1' == $sets['taxonomy'] ) ) ? true : false;
		$tagsidebar = ( isset( $sets['tag'] ) && ( '1' == $sets['tag'] ) ) ? true : false;
		$authorsidebar = ( isset( $sets['author'] ) && ( '1' == $sets['author'] ) ) ? true : false;
		$datesidebar = ( isset( $sets['date'] ) && ( '1' == $sets['date'] ) ) ? true : false;

		// --- get and set the sidebar context ---
		if ( $frontpagesidebar && is_front_page() ) {
			$context = 'front';
		} elseif ( $homepagesidebar && is_home() ) {
			$context = 'home';
		} elseif ( is_singular() ) {
			$context = $posttype;
		} elseif ( $notfoundsidebar && is_404() ) {
			$context = 'notfound';
		} elseif ( $searchsidebar && is_search() ) {
			$context = 'search';
		} elseif ( $tagsidebar && is_tag() ) {
			$context = 'tag';
		} elseif ( $categorysidebar && is_category() ) {
			$context = 'category';
		} elseif ( $taxonomysidebar && is_tax() ) {
			$context = 'taxonomy';
		} elseif ( $authorsidebar && is_author() ) {
			$context = 'author';
		} elseif ( $datesidebar && is_date() ) {
			$context = 'date';
		} elseif ( is_archive() ) {
			$context = 'archive';
		}
		bioship_debug( "Sidebar Context", $context );

		// --- check subsidebar settings ---
		// 2.2.0: simplify to single line logic definitions
		$subsets = $vthemesettings['subsidebars'];
		$frontpagesubsidebar = ( isset( $subsets['frontpage'] ) && ( '1' == $subsets['frontpage'] ) ) ? true : false;
		$homepagesubsidebar = ( isset( $subsets['homepage'] ) && ( '1' == $subsets['homepage'] ) ) ? true : false;
		$searchsubsidebar = ( isset( $subsets['search'] ) && ( '1' == $subsets['search'] ) ) ? true : false;
		$notfoundsubsidebar = ( isset( $subsets['notfound'] ) && ( '1' == $subsets['notfound'] ) ) ? true : false;
		$archivesubsidebar = ( isset( $subsets['archive'] ) && ( '1' == $subsets['archive'] ) ) ? true : false;
		$categorysubsidebar = ( isset( $subsets['category'] ) && ( '1' == $subsets['category'] ) ) ? true : false;
		$taxonomysubsidebar = ( isset( $subsets['taxonomy'] ) && ( '1' == $subsets['taxonomy'] ) ) ? true : false;
		$tagsubsidebar = ( isset( $subsets['tag'] ) && ( '1' == $subsets['tag'] ) ) ? true : false;
		$authorsubsidebar = ( isset( $subsets['author'] ) && ( '1' == $subsets['author'] ) ) ? true : false;
		$datesubsidebar = ( isset( $subsets['date'] ) && ( '1' == $subsets['date'] ) ) ? true : false;

		// --- check subsidebar context ---
		// 1.8.0: set subcontext from context
		// 1.8.5: get and set the subsidebar context separately
		if ( $frontpagesubsidebar && is_front_page() ) {
			$subcontext = 'subfront';
		} elseif ( $homepagesubsidebar && is_home() ) {
			$subcontext = 'subhome';
		} elseif ( is_singular() ) {
			$subcontext = 'sub' . $posttype;
		} elseif ( $notfoundsubsidebar && is_404() ) {
			$subcontext = 'subnotfound';
		} elseif ( $searchsubsidebar && is_search() ) {
			$subcontext = 'subsearch';
		} elseif ( $tagsubsidebar && is_tag() ) {
			$subcontext = 'subtag';
		} elseif ( $categorysubsidebar && is_category() ) {
			$subcontext = 'subcategory';
		} elseif ( $taxonomysubsidebar && is_tax() ) {
			$subcontext = 'subtaxonomy';
		} elseif ( $authorsubsidebar && is_author() ) {
			$subcontext = 'subauthor';
		} elseif ( $datesubsidebar && is_date() ) {
			$subcontext = 'subdate';
		} elseif ( is_archive() ) {
			$subcontext = 'subarchive';
		}
		bioship_debug( "SubSidebar Context", $subcontext );
	}

	// ...get ready for this insane conditional logic stream!

	// --- get the default sidebar layout position and filter ---
	// 1.8.5: added filter value validation check
	$sidebarposition = $vthemesettings['page_layout'];
	$position = bioship_apply_filters( 'skeleton_sidebar_position', $sidebarposition );
	$positions = array( 'left', 'right' ); // valid values
	if ( is_string( $position ) && in_array( $position, $positions ) ) {
		$sidebarposition = $position;
	}
	// 1.9.5: allow for metabox override
	if ( isset( $vthemeoverride['sidebarposition'] ) && ( '' != $vthemeoverride['sidebarposition'] ) ) {
		$sidebarposition = $vthemeoverride['sidebarposition'];
	}
	$vthemesidebars['sidebarposition'] = $sidebarposition;

	// --- get the default subsidebar layout position and filter ---
	// 1.8.5: added filter value validation check
	$subsidebarposition = $vthemesettings['subsidiaryposition'];
	$subposition = bioship_apply_filters( 'skeleton_subsidebar_position', $subsidebarposition );
	$subpositions = array( 'internal', 'external', 'opposite' ); // valid values
	if ( is_string( $subposition ) && in_array( $subposition, $subpositions ) ) {
		$subsidebarposition = $subposition;
	}
	// 1.9.5: allow for metabox override
	if ( isset( $vthemeoverride['subsidebarposition'] ) && ( '' != $vthemeoverride['subsidebarposition'] ) ) {
		$subsidebarposition = $vthemeoverride['subsidebarposition'];
	}
	$vthemesidebars['subsidebarposition'] = $subsidebarposition;

	// --- get the default sidebar mode and filter ---
	// 1.8.5: added filter value validation check
	$sidebarmodes = array( 'off', 'postsonly', 'pagesonly', 'unified', 'dual' ); // valid values
	$sidebarmode = $vthemesettings['sidebarmode'];
	$mode = bioship_apply_filters( 'skeleton_sidebar_mode', $sidebarmode );
	if ( is_string( $mode ) && in_array( $mode, $sidebarmodes ) ) {
		$sidebarmode = $mode;
	}
	$vthemesidebars['sidebarmode'] = $sidebarmode;

	// --- get the default subsidebar mode and filter ---
	$subsidebarmode = $vthemesettings['subsidiarysidebar'];
	$submode = bioship_apply_filters( 'skeleton_subsidebar_mode', $subsidebarmode );
	if ( is_string( $submode ) && in_array( $submode, $sidebarmodes ) ) {
		$subsidebarmode = $submode;
	}
	$vthemesidebars['subsidebarmode'] = $subsidebarmode;

	// --- debug sidebar states ---
	bioship_debug( "Sidebar Position", $sidebarposition );
	bioship_debug( "Sidebar Mode", $sidebarmode );
	bioship_debug( "SubSidebar Position", $subsidebarposition );
	bioship_debug( "SubSidebar Mode", $subsidebarmode );

	// --- set default sidebar conditions ---
	// (default to true so all other sidebar types show)
	$sidebar = $subsidebar = true;

	// --- check singular sidebar states ---
	if ( is_singular() ) {
		// 2.2.0: remove this check to better account for single CPTs
		// if ( ( 'post' == $posttype ) || ( 'page' == $posttype ) ) {
			if ( 'off' == $sidebarmode ) {
				$sidebar = false;
			} elseif ( ( 'postsonly' == $sidebarmode ) && ( 'page' == $posttype ) ) {
				$sidebar = false;
			} elseif ( ( 'pagesonly' == $sidebarmode ) && ( 'post' == $posttype ) ) {
				$sidebar = false;
			}
			if ( 'off' == $subsidebarmode ) {
				$subsidebar = false;
			} elseif ( ( 'postsonly' == $subsidebarmode ) && ( 'page' == $posttype ) ) {
				$subsidebar = false;
			} elseif ( ( 'pagesonly' == $subsidebarmode ) && ( 'post' == $posttype ) ) {
				$subsidebar = false;
			}
		// }
	}

	// Check Sidebar Display Filters
	// -----------------------------

	// --- full width filter ---
	// note: for backwards compatibility, maintain old fullwidth filter name
	$fullwidth = bioship_apply_filters( 'skeleton_fullwidth_filter', false );
	if ( $fullwidth ) {
		$sidebar = $subsidebar = false;
	}

	// --- apply individual sidebar output conditional filters ---
	$sidebar = bioship_apply_filters( 'skeleton_sidebar_output', $sidebar );
	$subsidebar = bioship_apply_filters( 'skeleton_subsidebar_output', $subsidebar );

	if ( THEMEDEBUG ) {
		echo "<!-- Sidebar States: ";
			if ( $sidebar ) {
				echo "Main Sidebar - ";
			} else {
				echo "No Main Sidebar - ";
			}
			if ( $subsidebar ) {
				echo "Sub Sidebar";
			} else {
				echo "No Sub Sidebar";
			}
		echo " -->";
	}

	// --- set sidebars array ---
	if ( !$sidebar && !$subsidebar ) {

		// for no default sidebars (full width) set empty sidebar array
		// ...but continue to allow overrides
		$sidebars = array( '', '', '', '' );

	} else {

		// 2.0.5: set empty sidebar variables
		$leftsidebar = $innerleftsidebar = $rightsidebar = $innerrightsidebar = '';

		// set Primary Sidebar Template
		// ----------------------------
		if ( $sidebar ) {
			if ( ( 'post' == $context ) || ( 'page' == $context ) ) {
				if ( 'unified' == $sidebarmode ) {
					if ( 'left' == $sidebarposition ) {
						$leftsidebar = 'primary';
					} elseif ( 'right' == $sidebarposition ) {
						$rightsidebar = 'primary';
					}
				} elseif ( 'page' == $context ) {
					if ( ( 'dual' == $sidebarmode ) || ( 'pagesonly' == $sidebarmode ) ) {
						if ( 'left' == $sidebarposition ) {
							$leftsidebar = 'page';
						} elseif ( 'right' == $sidebarposition ) {
							$rightsidebar = 'page';
						}
					}
				} elseif ( 'post' == $context ) {
					if ( ( 'dual' == $sidebarmode ) || ( 'postsonly' == $sidebarmode ) ) {
						if ( 'left' == $sidebarposition ) {
							$leftsidebar = 'post';
						} elseif ( 'right' == $sidebarposition ) {
							$rightsidebar = 'post';
						}
					}
				}
			} else {
				if ( 'left' == $sidebarposition ) {
					$leftsidebar = $context;
				} elseif ( 'right' == $sidebarposition ) {
					$rightsidebar = $context;
				}
			}
		}

		// set Subsidiary Sidebar Template
		// -------------------------------
		if ( $subsidebar ) {
			if ( ( 'subpost' == $subcontext ) || ( 'subpage' == $subcontext ) ) {
				if ( 'unified' == $sidebarmode ) {
					if ( 'left' == $sidebarposition ) {
						if ( 'opposite' == $subsidebarposition ) {
							$rightsidebar = 'subsidebar';
						} elseif ( 'internal' == $subsidebarposition ) {
							$innerleftsidebar = 'subsidebar';
						} elseif ( 'external' == $subsidebarposition ) {
							$innerleftsidebar = $leftsidebar;
							$leftsidebar = 'subsidiary';
						}
					} elseif ( 'right' == $sidebarposition ) {
						if ( 'opposite' == $subsidebarposition ) {
							$leftsidebar = 'subsidiary';
						} elseif ( 'internal' == $subsidebarposition ) {
							$innerrightsidebar = 'subsidiary';
						} elseif ( 'external' == $subsidebarposition ) {
							$innerrightsidebar = $rightsidebar;
							$rightsidebar = 'subsidiary';
						}
					}
				} elseif ( 'subpage' == $subcontext ) {
					if ( ( 'dual' == $sidebarmode ) || ( 'pagesonly' == $sidebarmode ) ) {
						if ( 'left' == $sidebarposition ) {
							if ( 'opposite' == $subsidebarposition ) {
								$rightsidebar = 'subpage';
							} elseif ( 'internal' == $subsidebarposition ) {
								$innerleftsidebar = 'subpage';
							} elseif ( 'external' == $subsidebarposition ) {
								$innerleftsidebar = $leftsidebar;
								$leftsidebar = 'subpage';
							}
						} elseif ( 'right' == $sidebarposition ) {
							if ( 'opposite' == $subsidebarposition ) {
								$leftsidebar = 'subpage';
							} elseif ( 'internal' == $subsidebarposition ) {
								$innerrightsidebar = 'subpage';
							} elseif ( 'external' == $subsidebarposition ) {
								$innerrightsidebar = $rightsidebar;
								$rightsidebar = 'subpage';
							}
						}
					}
				} elseif ( 'subpost' == $subcontext ) {
					if ( ( 'dual' == $sidebarmode ) || ( 'postsonly' == $sidebarmode ) ) {
						if ( 'left' == $sidebarposition ) {
							if ( 'opposite' == $subsidebarposition ) {
								$rightsidebar = 'subpost';
							} elseif ( 'internal' == $subsidebarposition ) {
								$innerleftsidebar = 'subpost';
							} elseif ( 'external' == $subsidebarposition ) {
								$innerleftsidebar = $leftsidebar;
								$leftsidebar = 'subpost';
							}
						} elseif ( 'right' == $sidebarposition ) {
							if ( 'opposite' == $subsidebarposition ) {
								$leftsidebar = 'subpost';
							} elseif ( 'internal' == $subsidebarposition ) {
								$innerrightsidebar = 'subpost';
							} elseif ( 'external' == $subsidebarposition ) {
								$innerrightsidebar = $rightsidebar;
								$rightsidebar = 'subpost';
							}
						}
					}
				}
			} else {
				if ( 'left' == $sidebarposition ) {
					if ( 'opposite' == $subsidebarposition ) {
						$rightsidebar = $subcontext;
					} elseif ( 'internal' == $subsidebarposition ) {
						$innerleftsidebar = $subcontext;
					} elseif ( 'external' == $subsidebarposition ) {
						$innerleftsidebar = $leftsidebar;
						$leftsidebar = $subcontext;
					}
				}
				if ( 'right' == $sidebarposition ) {
					if ( 'opposite' == $subsidebarposition ) {
						$leftsidebar = $subcontext;
					} elseif ( 'internal' == $subsidebarposition ) {
						$innerrightsidebar = $subcontext;
					} elseif ( 'external' == $subsidebarposition ) {
						$innerrightsidebar = $rightsidebar;
						$rightsidebar = $subcontext;
					}
				}
			}
		}

		// --- set full sidebar position array ---
		$sidebars = array( $leftsidebar, $innerleftsidebar, $innerrightsidebar, $rightsidebar );
	}

	if ( THEMEDEBUG ) {
		echo "<!-- Sidebar Positions (" . esc_attr( $context ) . " / " . esc_attr( $subcontext ) . "): ";
		echo esc_attr( $leftsidebar ) . " - " . esc_attr( $innerleftsidebar ) . " - ";
		echo esc_attr( $innerrightsidebar ) . " - " . esc_attr( $rightsidebar );
		echo " -->";
	}

	// Check Template Position Override
	// --------------------------------
	$layout = bioship_apply_filters( 'skeleton_sidebar_layout_override', $sidebars );
	// 1.9.0: apply overrides if validated
	if ( ( $layout != $sidebars ) && is_array( $layout ) && ( count( $layout ) == 4 ) ) {
		$sidebars = $layout;
		if ( THEMEDEBUG ) {
			echo "<!-- New Sidebar Positions (" . esc_attr( $context ) . " / " . esc_attr( $subcontext ) . "): ";
			echo esc_attr( $sidebars[0] ) . " - " . esc_attr( $sidebars[1] ) . " - ";
			echo esc_attr( $sidebars[2] ) . " - " . esc_attr( $sidebars[3] );
			echo " -->";
		}
	}

	// Check Metabox Overrides
	// -----------------------
	// (set via theme options metabox)
	// 1.8.0: check the sidebar display overrides
	// 1.8.5: use vthemedisplay overrides global
	// 1.9.5: change to use of vthemeoverride templating global
	$sidebaroverride = $subsidebaroverride = $nosidebar = $nosubsidebar = false;
	if ( isset( $vthemeoverride['sidebartemplate'] ) ) {
		if ( THEMEDEBUG ) {
			echo "<!-- Sidebar Template Override: " . esc_html( $vthemeoverride['sidebartemplate'] ) . " -->";
		}
		if ( '' != $vthemeoverride['sidebartemplate'] ) {
			$sidebaroverride = true;
			if ( 'off' == $vthemeoverride['sidebartemplate'] ) {
				$nosidebar = true;
			} else {
				// --- assign sidebar and clear other unused positions ---
				if ( 'left' == $sidebarposition ) {
					if ( 'opposite' == $subsidebarposition ) {
						$newposition = 0;
						$sidebars[1] = $sidebars[2] = '';
					} elseif ( 'internal' == $subsidebarposition ) {
						$newposition = 0;
						$sidebars[2] = $sidebars[3] = '';
					} elseif ( 'external' == $subsidebarposition ) {
						$newposition = 1;
						$sidebars[2] = $sidebars[3] = '';
					}
				} elseif ( 'right' == $sidebarposition ) {
					if ( 'opposite' == $subsidebarposition ) {
						$newposition = 3;
						$sidebars[1] = $sidebars[2] = '';
					} elseif ( 'internal' == $subsidebarposition ) {
						$newposition = 3;
						$sidebars[0] = $sidebars[1] = '';
					} elseif ( 'external' == $subsidebarposition ) {
						$newposition = 2;
						$sidebars[0] = $sidebars[1] = '';
					}
				}
				// 2.1.1: set newposition if changed
				if ( isset( $newposition ) ) {
					$sidebars[$newposition] = $vthemeoverride['sidebartemplate'];
				}
			}
		}
	}
	if ( isset( $vthemeoverride['subsidebartemplate'] ) ) {
		if ( THEMEDEBUG ) {
			echo "<!-- Subsidebar Template Override: " . esc_html( $vthemeoverride['subsidebartemplate'] ) . " -->";
		}
		if ( '' != $vthemeoverride['subsidebartemplate'] ) {
			$subsidebaroverride = true;
			if ( 'off' == $vthemeoverride['subsidebartemplate'] ) {
				$nosubsidebar = true;
			} else {
				if ( 'opposite' == $subsidebarposition ) {
					if ( 'left' == $sidebarposition ) {
						$sidebars[3] = $vthemeoverride['subsidebartemplate'];
					} elseif ( 'right' == $sidebarposition ) {
						$sidebars[0] = $vthemeoverride['subsidebartemplate'];
					}
				} elseif ( 'internal' == $subsidebarposition ) {
					if ( 'left' == $sidebarposition ) {
						$sidebars[1] = $vthemeoverride['subsidebartemplate'];
					} elseif ( 'right' == $sidebarposition ) {
						$sidebars[2] = $vthemeoverride['subsidebartemplate'];
					}
				} elseif ( 'external' == $subsidebarposition ) {
					if ( 'left' == $sidebarposition ) {
						$sidebars[0] = $vthemeoverride['subsidebartemplate'];
					} elseif ( 'right' == $sidebarposition ) {
						$sidebars[3] = $vthemeoverride['subsidebartemplate'];
					}
				}
			}
		}
	}

	// --- recheck sidebar and subsidebar flags ---
	// 1.9.0: recheck sidebar states in any case
	// 1.9.0: maybe loop to apply meta override
	// 1.9.5: combined separate loops to check/override sidebars
	// 2.1.1: use simple array index in loop
	$foundsidebar = $foundsubsidebar = false;
	foreach ( $sidebars as $i => $bar ) {
		if ( '' != $bar ) {
			if ( 'sub' == substr( $bar, 0, 3 ) ) {
				if ( $nosubsidebar ) {
					unset( $sidebars[$i] );
				} else {
					$foundsubsidebar = true;
				}
			} else {
				if ( $nosidebar ) {
					unset( $sidebars[$i] );
				} else {
					$foundsidebar = true;
				}
			}
		}
	}
	$sidebar = $foundsidebar ? true : false;
	$subsidebar = $foundsubsidebar ? true : false;

	if ( THEMEDEBUG ) {
		if ( $sidebaroverride || $subsidebaroverride ) {
			bioship_debug( "Sidebar Meta Override Result", $sidebars );
		}
		if ( $sidebar ) {
			$states = "Main Sidebar - ";
		} else {
			$states = "No Main Sidebar - ";
		}
		if ( $subsidebar ) {
			$states .= "Sub Sidebar";
		} else {
			$states .= "No Sub Sidebar";
		}
		bioship_debug( "Sidebar Flag States", $states );
	}

	// --- set global sidebar states ---
	// 1.9.0: set global theme states here
	$vthemesidebars['sidebars'] = $sidebars;
	$vthemesidebars['sidebar'] = $sidebar;
	$vthemesidebars['subsidebar'] = $subsidebar;
	$vthemesidebars['sidebarcontext'] = $context;
	$vthemesidebars['subsidebarcontext'] = $subcontext;

	// --- prepare sidebar output early ---
	bioship_set_sidebar( 'left' );
	bioship_set_sidebar( 'right' );

	return $sidebars;
 }
}

// -------------------------
// Set Sidebars for Position
// -------------------------
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_set_sidebar' ) ) {
 function bioship_set_sidebar( $position ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 1.8.5: rename global sidebaroutput to themesidebars
	global $vthemelayout, $vthemesidebars;

	// --- check for sidebars ---
	$sidebar = $vthemesidebars['sidebar'];
	$subsidebar = $vthemesidebars['subsidebar'];
	if ( !$sidebar && !$subsidebar ) {
		return;
	}

	// --- get sidebar contexts ---
	// 1.9.0: use new themesidebars global
	// 1.9.8: fix to variable typo for subsidebar context
	$sidebars = $vthemesidebars['sidebars'];
	$context = $vthemesidebars['sidebarcontext'];
	$subcontext = $vthemesidebars['subsidebarcontext'];

	// 1.9.8: fix to undefined index warning
	if ( !isset( $vthemesidebars['output'] ) || !is_array( $vthemesidebars['output'] ) ) {
		// set empty sidebar array
		$vthemesidebars['output'] = array( '', '', '', '' );
	}

	// Note: Sidebar Positions: left (0) - inner left (1) - [content] - inner right (2) - right (3)
	// any primary and subsidiary sidebars are already mapped to these positions
	// if overriding use the skeleton_sidebar_layout_override filter above
	// final fallback is to sidebar/sidebar.php via hybrid_get_sidebar
	// (this does not exist by default intentionally so shows no sidebar)
	$leftsidebar = $sidebars[0];
	$innerleftsidebar = $sidebars[1];
	$innerrightsidebar = $sidebars[2];
	$rightsidebar = $sidebars[3];
	$leftoutput = $vthemesidebars['output'][0];
	$innerleftoutput = $vthemesidebars['output'][1];
	$innerrightoutput = $vthemesidebars['output'][2];
	$rightoutput = $vthemesidebars['output'][3];

	// Prepare/Output Left Sidebars
	// ----------------------------
	if ( 'left' == $position ) {

		if ( THEMEDEBUG ) {
			echo "<!-- Left Sidebar Templates - Left: " . esc_attr( $leftsidebar );
			echo " - SubLeft: " . esc_attr( $innerleftsidebar ) . " -->";}

		// Left Sidebar Position
		// ---------------------
		if ( '' != $leftsidebar ) {

			// --- check sidebar template ---
			$leftsidebar = bioship_sidebar_template_check( $leftsidebar, 'Left' );

			// --- prepare left sidebar position output ---
			// 1.9.0: use blank sidebar template instead
			// 2.2.0: skip get output if template is empty
			if ( '' == $leftsidebar ) {
				$leftoutput = '';
			} else {
				ob_start();
				hybrid_get_sidebar( $leftsidebar );
				$leftoutput = ob_get_contents();
				ob_end_clean();
			}

			// --- flag sidebar as empty if no sidebar content ---
			if ( 0 === strlen( trim( $leftoutput ) ) ) {
				if ( strstr( $leftsidebar, 'sub' ) ) {
					$subsidebar = false;
				} else {
					$sidebar = false;
				}
				bioship_debug( "No Left Sidebar Content" );
			} else {
				bioship_debug( "Left Sidebar Length", strlen( $leftoutput ) );
			}
		}

		// Inner Left Sidebar Position
		// ---------------------------
		if ( '' != $innerleftsidebar ) {

			// --- check sidebar template ---
			$innerleftsidebar = bioship_sidebar_template_check( $innerleftsidebar, 'SubLeft' );

			// --- prepare subleft sidebar position output ---
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			// 2.2.0: skip get output if template is empty
			if ( '' == $innerleftsidebar ) {
				$innerleftoutput = '';
			} else {
				ob_start();
				hybrid_get_sidebar( $innerleftsidebar );
				$innerleftoutput = ob_get_contents();
				ob_end_clean();
			}

			// --- flag sidebar as empty if no sidebar content ---
			if ( 0 == strlen( trim( $innerleftoutput ) ) ) {
				if ( strstr( $innerleftsidebar, 'sub' ) ) {
					$subsidebar = false;
				} else {
					$sidebar = false;
				}
				bioship_debug( "No SubLeft Sidebar Content" );
			} else {
				bioship_debug( "SubLeft Sidebar Length", strlen( $innerleftoutput ) );
			}
		}
	}

	// Prepare/Output Right Sidebars
	// -----------------------------
	if ( 'right' == $position ) {

		if ( THEMEDEBUG ) {
			echo "<!-- Right Sidebar Templates - SubRight: " . esc_attr( $innerrightsidebar );
			echo " - Right: " . esc_attr( $rightsidebar ) . " -->";
		}

		// Inner Right Sidebar Position
		// ----------------------------
		if ( '' != $innerrightsidebar ) {

			// --- check sidebar template ---
			$innerrightsidebar = bioship_sidebar_template_check( $innerrightsidebar, 'SubRight' );

			// --- prepare subright sidebar position output ---
			// 1.8.5: allow for blank sidebar override
			// 1.9.0: use blank sidebar template instead
			// 2.2.0: skip get output if template is empty
			if ( '' == $innerrightsidebar ) {
				$innerrightoutput = '';
			} else {
				ob_start();
				hybrid_get_sidebar( $innerrightsidebar );
				$innerrightoutput = ob_get_contents();
				ob_end_clean();
			}

			// --- flag sidebar as empty if no sidebar content ---
			if ( 0 === strlen( trim( $innerrightoutput ) ) ) {
				if ( strstr( $innerrightsidebar, 'sub' ) ) {
					$subsidebar = false;
				} else {
					$sidebar = false;
				}
				bioship_debug( "No SubRight Sidebar Content" );
			} else {
				bioship_debug( "SubRight Sidebar Length", strlen( $innerrightoutput ) );
			}
		}

		// Right Sidebar Position
		// ----------------------
		if ( '' != $rightsidebar ) {

			// --- check sidebar template ---
			$rightsidebar = bioship_sidebar_template_check( $rightsidebar, 'Right' );

			// --- prepare right sidebar position output ---
			// 1.8.5: allow for blank/empty sidebar override
			// 1.9.0: use blank sidebar template instead
			// 2.2.0: skip get output if template is empty
			if ( '' == $rightsidebar ) {
				$rightoutput = '';
			} else {
				ob_start();
				hybrid_get_sidebar( $rightsidebar );
				$rightoutput = ob_get_contents();
				ob_end_clean();
			}

			// --- flag sidebar as empty if no sidebar content ---
			if ( 0 === strlen( trim( $rightoutput ) ) ) {
				if ( strstr( $rightsidebar, 'sub' ) ) {
					$subsidebar = false;
				} else {
					$sidebar = false;
				}
				bioship_debug( "No Right Sidebar Content" );
			} else {
				bioship_debug( "Right Sidebar Length", strlen( $rightoutput ) );
			}
		}
	}

	// maybe swap mobile button positions to match sidebars
	// TODO: improve mobile sidebar button loading conditions ?
	// ...as we could check for sidebar content not just positions?

	$css = '';

	// --- maybe move mobile subsidebar button to left ---
	if ( ( ( strstr( $leftsidebar, 'sub' ) ) && ( '' == $innerleftsidebar ) )
	  && ( ( strstr( $innerleftsidebar, 'sub' ) ) && ( '' == $leftsidebar ) ) ) {
		// 2.2.0: remove unnecessary functions
		/* if ( !has_action( 'wp_footer', 'bioship_mobile_subsidebar_button_swap' ) ) {
			add_action( 'wp_footer', 'bioship_mobile_subsidebar_button_swap' );
			if ( !function_exists( 'bioship_mobile_subsidebar_button_swap' ) ) {
			 function bioship_mobile_subsidebar_button_swap() { */
				// echo "<style>#subsidebarbutton {float:left !important; margin-left:10px !important; margin-right:0px !important;}</style>";
				$css .= "#subsidebarbutton {float:left !important; margin-left:10px !important; margin-right:0px !important;}";
			 /* }
			}
		} */
	}

	// --- maybe move mobile sidebar button to right ---
	// 2.0.7: fix to variable typo (subrighsidebar)
	if ( ( ( '' != $rightsidebar ) && ( !strstr( $rightsidebar, 'sub' ) ) && ( '' == $innerrightsidebar ) )
	  && ( ( '' != $innerrightsidebar ) && ( !strstr( $innerrightsidebar, 'sub' ) ) && ( '' == $rightsidebar ) ) ) {
		// 2.2.0: remove unnecessary functions
		/* if ( !has_action( 'wp_footer', 'bioship_mobile_sidebar_button_swap' ) ) {
			add_action( 'wp_head', 'bioship_mobile_sidebar_button_swap' );
			if ( !function_exists('bioship_mobile_sidebar_button_swap' ) ) {
			 function bioship_mobile_sidebar_button_swap() { */
				// echo "<style>#sidebarbutton {float:right !important; margin-right:10px !important; margin-left:0px !important;}</style>";
				$css .= "#sidebarbutton {float:right !important; margin-right:10px !important; margin-left:0px !important;}";
			 /* }
			}
		} */
	}

	// 2.2.0: use wp_add_inline_style to mobile stylesheet for CSS
	if ( '' != $css ) {
		wp_add_inline_style( THEMESLUG . '-mobile', $css );
	}

	// 1.8.5: renamed to use themesidebars global
	// 2.2.0: add filter for sidebar output
	$vthemesidebars['output'] = array( $leftoutput, $innerleftoutput, $innerrightoutput, $rightoutput );
	$vthemesidebars['output'] = bioship_apply_filters( 'skeleton_sidebars_output', $vthemesidebars['output'] );

	// 1.9.9: set theme sidebar states as they may have changed
	$vthemesidebars['sidebar'] = $sidebar;
	$vthemesidebars['subsidebar'] = $subsidebar;

	if ( THEMEDEBUG ) {
		$lengths = strlen( $vthemesidebars['output'][0] ) . ', ' . strlen( $vthemesidebars['output'][1] ) . ',';
		$lengths .= strlen( $vthemesidebars['output'][2] ) . ', ' . strlen( $vthemesidebars['output'][3] );
		bioship_debug( "Stored Sidebars Lengths", $lengths );
	}

 }
}

// ------------------------
// Set Sidebar Column Width
// ------------------------
if ( !function_exists( 'bioship_set_sidebar_columns' ) ) {
 function bioship_set_sidebar_columns() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.2.0: set missing vthemeoverride global
	global $vthemesettings, $vthemesidebars, $vthemeoverride;

	// --- get filtered sidebar column width ---
	$sidebarcolumns = $vthemesettings['sidebar_width'];
	if ( '' == $sidebarcolumns ) {
		$sidebarcolumns = 'four';
	}
	$columns = bioship_apply_filters( 'skeleton_sidebar_columns', $sidebarcolumns );
	// 1.9.5: allow for metabox override
	if ( isset( $vthemeoverride['sidebarcolumns'] ) && ( '' != $vthemeoverride['sidebarcolumns'] ) ) {
		$columns = $vthemeoverride['sidebarcolumns'];
	}

	// --- check column override values ---
	// 1.8.5: added filter validation check
	// 2.1.1: simplified column check logic
	if ( $columns != $sidebarcolumns ) {
		if ( is_numeric( $columns ) ) {
			$columns = bioship_number_to_word( $columns );
		} elseif ( is_string( $columns ) ) {
			$columns = bioship_number_to_word( bioship_word_to_number( $columns ) );
		}
		if ( $columns ) {
			$sidebarcolumns = $columns;
		}
	}

	bioship_debug( "Sidebar Columns: ", $sidebarcolumns );
	$vthemesidebars['sidebarcolumns'] = $sidebarcolumns;
	return $sidebarcolumns;
 }
}

// ---------------------------
// Set SubSidebar Column Width
// ---------------------------
if ( !function_exists( 'bioship_set_subsidebar_columns' ) ) {
 function bioship_set_subsidebar_columns() {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	global $vthemesettings, $vthemesidebars, $vthemeoverride;

	// --- get filtered subsidebar column width ---
	$subsidebarcolumns = $vthemesettings['subsidiarycolumns'];
	if ( '' == $subsidebarcolumns ) {
		$subsidebarcolumns = 'zero';
	}
	$columns = bioship_apply_filters( 'skeleton_subsidebar_columns', $subsidebarcolumns );
	// 1.9.5: allow for metabox override
	if ( isset( $vthemeoverride['subsidebarcolumns'] ) && ( '' != $vthemeoverride['subsidebarcolumns'] ) ) {
		$columns = $vthemeoverride['subsidebarcolumns'];
	}

	// --- check column override values ---
	// 1.8.5: added filter validation check
	// 2.1.1: simplified column check logic
	if ( $columns != $subsidebarcolumns ) {
		if ( is_numeric( $columns ) ) {
			$columns = bioship_number_to_word( $columns );
		} elseif ( is_string( $columns ) ) {
			$columns = bioship_number_to_word( bioship_word_to_number( $columns ) );
		}
		if ( $columns ) {
			$subsidebarcolumns = $columns;
		}
	}

	bioship_debug( "SubSidebar Columns: ", $subsidebarcolumns );
	$vthemesidebars['subsidebarcolumns'] = $subsidebarcolumns;
	return $subsidebarcolumns;
 }
}

// -----------------
// Set Content Width
// -----------------
// 1.8.5: moved load action to skeleton_load_layout
if ( !function_exists( 'bioship_set_content_width' ) ) {
 function bioship_set_content_width() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	global $vthemelayout;

 	// --- get content width ---
 	// 1.8.5: do main content setup here
	$contentwidth = bioship_get_content_width();

	// --- get padding width ---
	// 1.9.5: moved here from get_content_width
	$paddingwidth = bioship_get_content_padding_width( $contentwidth );
	if ( $paddingwidth > 0 ) {
		$contentwidth = $contentwidth - $paddingwidth;
	}
	$contentwidth = bioship_apply_filters( 'skeleton_content_width', $contentwidth );

	// --- set content width ----
	if ( THEMEHYBRID ) {
		hybrid_set_content_width( $contentwidth );
	} else {
		global $content_width;
		$content_width = absint( $contentwidth );
	}
	$vthemelayout['contentwidth'] = $contentwidth;
	return $contentwidth;
 }
}

// -----------------
// Get Content Width
// -----------------
// (in pixels based on columns)
// 1.8.0: non-hybrid content width set in functions.php
// 1.8.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_content_width' ) ) {
	function bioship_get_content_width() {
	  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		global $vthemesettings, $vthemelayout;

		// --- get content columns ---
		$contentcols = bioship_content_width();
		$columns = bioship_word_to_number( $contentcols );

		// --- get number of grid columns
		// 1.8.5: use new theme layout global
		$gridcolumns = bioship_word_to_number( $vthemelayout['gridcolumns'] );

		// --- get maximum layout width ---
		// 1.8.0: bugfix for layoutwidth, not 960 default anymore
		// 1.8.5: use new layout global already set value
		// $layoutwidth = $vthemesettings['layout']; // maximum
		// $layoutwidth = bioship_apply_filters('skeleton_layout_width', $layoutwidth);
		$layoutwidth = $vthemelayout['maxwidth'];

		// --- calculate actual content width ---
		$contentwidth = $layoutwidth / $gridcolumns * $columns;
		if ( THEMEDEBUG ) {
			echo "<!-- Layout Max Width: " . esc_attr( $layoutwidth ) . " - ";
			echo "Grid Columns: " . esc_attr( $gridcolumns ) . " - ";
			echo "Content Columns: " . esc_attr( $columns ) . " -->";}

		// --- set raw content width ---
		// 1.9.5: set raw content width value for grid querystring
		$contentwidth = bioship_apply_filters( 'skeleton_raw_content_width', $contentwidth );
		$vthemelayout['rawcontentwidth'] = $contentwidth;

		return $contentwidth;
	}
}

// ------------------------
// Set Content Column Width
// ------------------------
// 1.8.5: moved here from skeleton.php
// 1.5.0: removed the filter here and moved to inside and also
if ( !function_exists( 'bioship_content_width' ) ) {
	function bioship_content_width() {
	  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// --- set sidebar values ---
		// 1.8.5: use new theme layout global
		// 1.9.8: added missing global theme override declaration
		global $post, $vthemesettings, $vthemelayout, $vthemesidebars, $vthemeoverride;
		$sidebar = $vthemesidebars['sidebar'];
		$subsidebar = $vthemesidebars['subsidebar'];

		// [deprecated] onecolumn-page.php template has been removed
		// as the theme options edit screen metabox provides this
		// (skeleton wide attachment pages have also been removed)
		// if (is_attachment()) {return $vthemelayout['gridcolumns'];}

		// --- full width content ---
		// 1.8.0: full width if no sidebars (calculated in set_sidebar_layout)
		if ( !$sidebar && !$subsidebar ) {
			$columns = $vthemelayout['gridcolumns'];
			// 1.9.8: filter and set content columns globals
			$columns = bioship_apply_filters( 'skeleton_content_columns_override', $columns );
			$vthemelayout['contentcolumns'] = $columns;
			$vthemelayout['numcontentcolumns'] = bioship_word_to_number( $columns );
			return $columns;
		}

		// --- check/fix for total content column width ---
		// 1.5.0: replaced skeleton_options here and added filters
		$contentcolumns = $vthemesettings['content_width'];
		if ( '' == $contentcolumns ) {
			// 1.8.0: fallback default to three quarters of grid column total
			if ( 'twelve' == $vthemelayout['gridcolumns'] ) {
				$contentcolumns = 'eight';
			} elseif ( 'sixteen' == $vthemelayout['gridcolumns'] ) {
				$contentcolumns = 'twelve';
			} elseif ( 'twenty' == $vthemelayout['gridcolumns'] ) {
				$contentcolumns = 'fifteen';
			} elseif ( 'twentyfour' == $vthemelayout['gridcolumns'] ) {
				$contentcolumns = 'eightteen';
			}
		}

		// --- get filtered content column width ---
		// 1.9.5: allow for perpost metabox override
		// 2.1.2: fix for possible undefined index warning
		$columns = bioship_apply_filters( 'skeleton_content_columns', $contentcolumns );
		if ( isset( $vthemeoverride['contentcolumns'] ) && ( '' != $vthemeoverride['contentcolumns'] ) ) {
			$columns = $vthemeoverride['contentcolumns'];
		}

		// --- check column values ---
		// 1.8.5: added filter validation check
		// 2.1.1: simplified column check logic
		if ( $columns != $contentcolumns ) {
			if ( is_numeric( $columns ) ) {
				$columns = bioship_number_to_word( $columns );
			} elseif ( is_string( $columns ) ) {
				$columns = bioship_number_to_word( bioship_word_to_number( $columns ) );
			}
			if ( $columns ) {
				$contentcolumns = $columns;
			}
		}

		// --- get sidebar columns --
		// 1.8.5: use new themesidebars global
		$sidebarcolumns = $vthemesidebars['sidebarcolumns'];
		$subsidebarcolumns = $vthemesidebars['subsidebarcolumns'];

		if ( THEMEDEBUG ) {
			echo "<!-- Columns: Content - " . esc_attr( $contentcolumns ) . " - ";
			echo "Sidebar - " . esc_attr( $sidebarcolumns ) . " - ";
			echo "Subsidebar - " . esc_attr( $subsidebarcolumns ) . " -->";}

		// --- get sidebar columns number ---
		if ( $sidebar ) {
			$sidebarcols = bioship_word_to_number( $sidebarcolumns );
		} else {
			$sidebarcols = 0;
		}
		if ( $subsidebar ) {
			$subsidebarcols = bioship_word_to_number( $subsidebarcolumns );
		} else {
			$subsidebarcols = 0;
		}

		// --- get total columns and grid columns ---
		$numcontentcols = bioship_word_to_number( $contentcolumns );
		$totalcolumns = ( intval( $numcontentcols ) + intval( $sidebarcols ) + intval( $subsidebarcols ) );
		$numgridcolumns = $vthemelayout['numgridcolumns'];

		// --- reduce content columns if grid is too wide ---
		if ( $totalcolumns > $numgridcolumns ) {
			// Houston we have a problem... so let's fix it...
			// 2.2.0: fix to corrupted variable name (umcontentcolumns)!
			$numcontentcols = ( $numgridcolumns - intval( $sidebarcols ) - intval( $subsidebarcols ) );
		}

		// --- cap maximum at total grid columns ---
		if ( $numcontentcols > $numgridcolumns ) {$numcontentcols = $numgridcolumns;}
		// 1.9.8: fix to changed variable name
		$contentcolumns = bioship_number_to_word( $numcontentcols );
		// 2.1.1: move content column override filter
		$contentcolumns = bioship_apply_filters( 'skeleton_content_columns_override', $contentcolumns );

		// --- set globals and return ---
		$vthemelayout['contentcolumns'] = $contentcolumns;
		$vthemelayout['numcontentcolumns'] = $numcontentcols;
		if ( THEMEDEBUG ) {
			echo "<!-- Content Columns: " . esc_attr( $numcontentcols ) . " (" . esc_attr( $contentcolumns ) . ") -->";
		}
		return $contentcolumns;
	}
}

// -------------------------
// Get Content Padding Width
// -------------------------
// 1.8.5: moved from skeleton.php
// gets the padding width (not height) - supports px or em or %
if ( !function_exists( 'bioship_get_content_padding_width' ) ) {
	function bioship_get_content_padding_width( $contentwidth, $contentpadding = false ) {
	  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		global $vthemesettings, $vthemelayout;

		// --- get content padding CSS ---
		// 1.9.5: allow for explicit second argument (for grid.php)
		if ( $contentpadding ) {
			$paddingcss = $contentpadding;
		} else {
			$paddingcss = $vthemesettings['contentpadding'];
			$paddingcss = bioship_apply_filters( 'skeleton_raw_content_padding', $paddingcss );
		}

		// --- set content padding width ---
		if ( ( '' == $paddingcss ) || ( '0' == $paddingcss ) || ( 0 === $paddingcss ) ) {
			$paddingwidth = 0;
		} else {
			if ( strstr( $paddingcss, ' ' ) ) {
				$paddingarray = explode( ' ', $paddingcss );
			} else {
				$paddingarray[0] = $paddingcss;
			}

			// --- convert possible padding values ---
			// 2.1.1: use simple array index in loop
			foreach ( $paddingarray as $i => $padding ) {
				if ( stristr( $padding, 'px' ) ) {
					$paddingarray[$i] = intval( trim( str_ireplace( 'px', '', $padding ) ) );
				} elseif ( stristr( $padding, 'em' ) ) {
					// 1.5.0: added em support based on 1em ~= 16px
					// 1.9.5: added font percent to 100 for testing
					// 2.1.2: use round half down helper function
					$fontpercent = '100';
					$paddingvalue = trim( str_ireplace( 'em', '', $padding ) );
					$paddingvalue = bioship_round_half_down( ( $paddingvalue * 16 * $fontpercent / 100), 2 );
					$paddingarray[$i] = $paddingvalue;
				} elseif ( strstr( $padding, '%' ) ) {
					$padding = intval( trim( str_ireplace( '%', '', $padding ) ) );
					$paddingarray[$i] = bioship_round_half_down( ( $contentwidth * $padding), 2 );
				} else {
					$paddingarray[$i] = 0;
				}
			}

			// --- combine padding width from CSS values ---
			if ( 4 == count( $paddingarray ) ) {
				$paddingwidth = $paddingarray[1] + $paddingarray[3];
			} elseif ( ( 3 == count( $paddingarray ) ) || ( 2 == count( $paddingarray ) ) ) {
				$paddingwidth = $paddingarray[1];
			} elseif ( 1 == count( $paddingarray ) ) {
				$paddingwidth = $paddingarray[0];
			}
		}

		// --- filter content padding width ---
		// 1.5.0: added a padding width filter
		$paddingwidth = bioship_apply_filters( 'skeleton_content_padding_width', $paddingwidth );
		$paddingwidth = abs( intval( $paddingwidth ) );

		// --- set layout global and return ---
		$vthemelayout['contentpadding'] = $paddingwidth;
		return $paddingwidth;
	}
}


// -----------------
// === Title Tag ===
// -----------------

// -----------------
// Title Tag Support
// -----------------
// 1.8.5: use new title-tag support
// 1.9.0: off as not rendering tag yet?
// 2.0.1: fixed now, so on again (and now works fine with or without)
$titletagsupport = bioship_apply_filters( 'skeleton_title_tag_support', true );

if ( $titletagsupport ) {
	add_theme_support( 'title-tag' );

	// --- replace title tag render action to add filter ---
	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	add_action( 'wp_head', 'bioship_render_title_tag_filtered', 1 );
	add_filter( 'document_title_separator', 'bioship_title_separator' );
	if ( !function_exists( 'bioship_title_separator' ) ) {
		function bioship_title_separator( $sep ) {
			return '|';
		}
	}
	// TODO: recheck usage of document_title_parts filter ?
	// add_filter('document_title_parts', 'bioship_document_title_parts');
	// function bioship_document_title_parts($title) {return $title;}

} else {
	// --- fallback to original wp_title usage ---
	add_filter( 'wp_title', 'bioship_wp_title', 10, 2 );
	// 2.0.8: specify priority to match title tag support
	add_action( 'wp_head', 'bioship_wp_title_tag', 1 );
}

// ----------------
// Title Tag Filter
// ----------------
// 1.8.5: added title tag filter function
if ( !function_exists( 'bioship_render_title_tag_filtered' ) ) {
 function bioship_render_title_tag_filtered() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
    ob_start();
	_wp_render_title_tag();
	$titletag = ob_get_contents();
	ob_end_clean();
    $titletag = bioship_apply_filters( 'wp_render_title_tag_filter', $titletag );
    // 2.2.0: use wp_kses on title tag output
	$allowed = bioship_allowed_html( 'title', 'page' );
    echo wp_kses( $titletag, $allowed ) . PHP_EOL;
 }
}

// -----------------------
// Title Tag Theme Default
// -----------------------
if ( !function_exists( 'bioship_wp_render_title_tag' ) ) {

 // 2.1.1: move add_filter internally for consistency
 add_filter( 'wp_render_title_tag_filter', 'bioship_wp_render_title_tag' );

 function bioship_wp_render_title_tag( $titletag ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	// note: rendered default is (for WP 4.4):
 	// echo '<title>' . wp_get_document_title() . '</title>' . "\n";

 	// --- add itemprop schema to title tag ---
 	// 2.0.7: concatenate to not trigger irrelevant Theme Check warning
	// phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
 	$titletag = str_replace( '<' . 'title' . '>', '<' . 'title' . ' itemprop="name">', $titletag );
	return $titletag;
 }
}

// -------------------
// wp_title Tag Output
// -------------------
// 1.8.5: moved here from header.php (wp_title only)
if ( !function_exists( 'bioship_wp_title_tag' ) ) {
 function bioship_wp_title_tag() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	// 2.0.7: concatenate to not trigger irrelevant Theme Check warning
	// phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
  	echo '<' . 'title' . ' ' . 'itemprop="name">';
  	wp_title( '|', true, 'right' );
	// phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
  	echo '</' . 'title' . '>' . PHP_EOL;
 }
}

// ---------------------
// wp_title Title Filter
// ---------------------
// 1.8.5: no longer default, and moved filter actions to title-tag support check
if ( !function_exists( 'bioship_wp_title' ) ) {
 function bioship_wp_title( $title, $sep ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// --- get blog title and description ---
	$blogname = get_bloginfo( 'name' );
	if ( is_home() || is_front_page() ) {
		$title = $blogname;
		$description = get_bloginfo( 'description', 'display' );
		// 1.8.0: fix to typo variable!
		if ( $description ) {
			$title .= " " . $sep . " " . $description;
		}
	} else {
		// 1.8.5: fix to double sep
		// 2.0.1: err nope, fix to unfix that
		$title .= " " . $sep . " " . $blogname;
	}

	// --- maybe add a page number ---
	if ( ( $paged >= 2 ) || ( $page >= 2 ) ) {
		// translators: replacement number is current page
		$title .= " " . $sep . " " . sprintf( esc_attr( __( 'Page %d', 'bioship' ) ), max( $paged, $page ) );
	}

	// --- apply title filter and return ---
	$title = bioship_apply_filters( 'skeleton_page_title', $title );
	return $title;
 }
}


// ------------------------
// === Template Helpers ===
// ------------------------

// ------------
// WP Body Open
// ------------
// 2.1.2: added for backwards compatibility for WordPress < 5.2.0
// 2.1.4: moved here from skeleton.php
if ( !function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
    	do_action( 'wp_body_open' );
    }
}

// --------------------
// Get Content Template
// --------------------
// 2.0.5: added this pseudonym for consistency
if ( !function_exists( 'bioship_get_content_template' ) ) {
 function bioship_get_content_template() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// --- get content template ---
	hybrid_get_content_template();
 }
}

// -------------------
// Get Header Template
// -------------------
// 1.8.5: custom header template hierarchy implementation
if ( !function_exists( 'bioship_get_header' ) ) {
 function bioship_get_header( $filepath = false ) {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	// --- get page context ---
	$templates = array();
	$pagecontext = $vthemelayout['pagecontext'];
	$subpagecontext = $vthemelayout['subpagecontext'];

	// --- check for header directory ---
	$headerdir = false;
	if ( is_dir( $vthemestyledir . 'header' ) ) {
		$headerdir = true;
	} elseif ( ( $vthemestyledir != $vthemetemplatedir ) && ( is_dir( $vthemetemplatedir . 'header' ) ) ) {
		$headerdir = true;
	}

	// --- do action get_header ---
	// if (THEMEHYBRID) {hybrid_get_header($header); return;}
	// 1.8.5: custom implementation like hybrid_get_header
	// 1.9.8: remove second unused variable vheader
	bioship_do_action( 'get_header' );

	// --- filter to allow for custom overrides ---
	$header = bioship_apply_filters( 'skeleton_header_template', $pagecontext );
	if ( $header && is_string( $header ) && ( $header != $pagecontext ) ) {
		if ( $headerdir ) {
			$templates[] = 'header/' . $header;
		}
		$templates[] = 'header-' . $header;
	}

	// --- for matching template by filename ---
	if ( $filepath ) {
		$pathinfo = pathinfo( $filepath );
		$filename = $pathinfo['filename'];
	}
 	if ( isset( $filename ) && $filename && ( '' != $filename ) && ( 'index' != $filename ) ) {
 		if ( $headerdir ) {
			$templates[] = 'header/' . $filename . '.php';
		}
 		$templates[] = 'header-' . $filename . '.php';
 	}

	// --- for subarchive types ---
	if ( 'archive' == $pagecontext ) {
		$headerarchive = bioship_apply_filters( 'skeleton_header_archive_template', $subpagecontext );
		if ( $headerarchive && is_string( $headerarchive ) && ( '' != $headerarchive ) ) {
			if ( $headerdir ) {
				$templates[] = 'header/' . $headerarchive . '.php';
			}
			$templates[] = 'header-' . $headerarchive . '.php';
		}
	}

	// --- for post types ---
	// 2.2.0: added for post type subpage context
	if ( 'single' == $pagecontext ) {
		// TODO: add new filter to filter docs
		$headerposttype = bioship_apply_filters( 'skeleton_header_post_type_template', $subpagecontext );
		if ( $headerposttype && is_string( $headerposttype ) && ( '' != $headerposttype ) ) {
			if ( $headerdir ) {
				$templates[] = 'header/' . $headerposttype . '.php';
			}
			$templates[] = 'header-' . $headerposttype . '.php';
		}
	}

	// --- add default template hierarchy ---
	if ( $headerdir ) {
		$templates[] = 'header/' . $pagecontext . '.php';
	}
	$templates[] = 'header-' . $pagecontext . '.php';
	if ( $headerdir ) {
		$templates[] = 'header/header.php';
	}
	$templates[] = 'header.php';

	// --- filter header template hierarchy ---
	$headertemplates = bioship_apply_filters( 'skeleton_header_templates', $templates );
	if ( is_array( $headertemplates ) ) {
		$templates = $headertemplates;
	}

	bioship_locate_template( $templates, true, false );
 }
}

// -------------------
// Get Footer Template
// -------------------
// 1.8.5: custom footer template hierarchy implementation
if ( !function_exists( 'bioship_get_footer' ) ) {
 function bioship_get_footer( $filepath = false ) {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	// --- get page context --
	$templates = array();
	$pagecontext = $vthemelayout['pagecontext'];
	$subpagecontext = $vthemelayout['subpagecontext'];

	// --- check for footer directory ---
	$footerdir = false;
	if ( is_dir( $vthemestyledir . 'footer' ) ) {
		$footerdir = true;
	} elseif ( ( $vthemestyledir != $vthemetemplatedir ) && ( is_dir( $vthemetemplatedir . 'footer' ) ) ) {
		$footerdir = true;
	}

	// --- go get_footer action ---
	// if (THEMEHYBRID) {hybrid_get_footer($footer); return;}
	// 1.8.5: custom implementation like hybrid_get_footer
	// 1.9.8: remove unused second variable vfooter
	bioship_do_action( 'get_footer' );

	// --- filter to allow for custom overrides ---
	$footer = bioship_apply_filters( 'skeleton_footer_template', $pagecontext );
	if ( $footer && is_string( $footer ) && ( '' != $footer ) && ( $footer != $pagecontext ) ) {
		if ( $footerdir ) {
			$templates[] = 'footer/' . $footer;
		}
		$templates[] = 'footer-' . $footer;
	}

	// --- for matching template by filename ---
	if ( $filepath ) {
		$pathinfo = pathinfo( $filepath );
		$filename = $pathinfo['filename'];
	}
 	if ( isset( $filename ) && $filename && ( '' != $filename ) && ( 'index' != $filename ) ) {
 		if ( $footerdir ) {
			$templates[] = 'footer/' . $filename . '.php';
		}
 		$templates[] = 'footer-' . $filename . '.php';
 	}

	// ---- for subarchive types ---
	if ( 'archive' == $pagecontext ) {
		// filter the sub archive context also
		$footerarchive = bioship_apply_filters( 'skeleton_footer_archive_template', $subpagecontext );
		if ( $footerarchive && is_string( $footerarchive ) ) {
			if ( $footerdir ) {
				$templates[] = 'footer/' . $footerarchive . '.php';
			}
			$templates[] = 'footer-' . $footerarchive . '.php';
		}
	}

	// --- for post types ---
	// 2.2.0: added for post type subpage context
	if ( 'single' == $pagecontext ) {
		// TODO: add new filter to filter docs
		$footerposttype = bioship_apply_filters( 'skeleton_footer_post_type_template', $subpagecontext );
		if ( $footerposttype && is_string( $footerposttype ) && ( '' != $footerposttype ) ) {
			if ( $footerdir ) {
				$templates[] = 'header/' . $footerposttype . '.php';
			}
			$templates[] = 'header-' . $footerposttype . '.php';
		}
	}
	// --- add default footer template hierarchy ---
	if ( $footerdir ) {
		$templates[] = 'footer/' . $pagecontext . '.php';
	}
	$templates[] = 'footer-' . $pagecontext . '.php';
	if ( $footerdir ) {
		$templates[] = 'footer/footer.php';
	}
	$templates[] = 'footer.php';

	// --- filter footer template hierarchy ---
	$footertemplates = bioship_apply_filters( 'skeleton_footer_templates', $templates );
	if ( is_array( $footertemplates ) ) {
		$templates = $footertemplates;
	}

	bioship_locate_template( $templates, true, false );
 }
}

// ----------------------
// Check Sidebar Template
// ----------------------
// a fallback template hierarchy for sidebars
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_sidebar_template_check' ) ) {
 function bioship_sidebar_template_check( $template, $position ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemelayout, $vthemesidebars;

	// 1.9.0: bug out if blank or subblank override
	if ( ( 'blank' == $template ) || ( 'subblank' == $template ) ) {
		return $template;
	}

	// --- get sidebar context ---
	// 1.9.0: use new themesidebars global
	$context = $vthemesidebars['sidebarcontext'];
	$subcontext = $vthemesidebars['subsidebarcontext'];
	bioship_debug( "Sidebar Context", $context );
	bioship_debug( "SubSidebar Context", $subcontext );

	// --- get post type(s) ---
	// 1.8.5: changed to use get post types helper
	$posttypes = bioship_get_post_types();
	$checktemplate = false;

	// aiming to mirror WordPress page template hierarchy here (eventually)...
	// handy mini ref: https://wphierarchy.com
	// TODO: allow for more specific sidebar template names: author?

	// --- archive sidebars ---
	if ( ( 'archive' == $context ) || ( 'subarchive' == $subcontext ) ) {
		if ( !is_array( $posttypes ) ) {
			// 2.0.8: fix for archive subtemplate check
			$subtemplate = false;
			if ( 'archive' == $template ) {
				$subtemplate = 'archive-' . $posttypes;
			} elseif ( 'subarchive' == $template ) {
				$subtemplate = 'subarchive-' . $posttypes;
			}
			if ( $subtemplate ) {
				$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );
				if ( $checktemplate ) {
					$template = $subtemplate;
				}
			}
		}
	}

	// --- category sidebars ---
	// 1.8.5: allow for specific category slugs (and IDs)
	if ( ( 'category' == $context ) || ( 'subcategory' == $subcontext ) ) {

		// 2.0.8: fix for variable typo (term)
		$term = get_queried_object();
		$category = $term->slug;

		// 2.0.8: fix for archive subtemplate check
		$subtemplate = false;
		if ( 'category' == $template ) {
			$subtemplate = 'category-' . $category;
		} elseif ( 'subcategory' == $template ) {
			$subtemplate = 'subcategory-' . $category;
		}
		if ( $subtemplate ) {
			$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );
		} else {
			$checktemplate = false;
		}

		if ( $checktemplate ) {
			$template = $subtemplate;
		} else {
			// 2.0.8: fix to variable type (term)
			$catid = $term->term_id;
			$subtemplate = false;
			if ( 'category' == $template ) {
				$subtemplate = 'category-' . $catid;
			} elseif ( 'subcategory' == $template ) {
				$subtemplate = 'subcategory-' . $catid;
			}
			if ( $subtemplate ) {
				$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );
				if ( $checktemplate ) {
					$template = $subtemplate;
				}
			}
		}
	}

	// --- taxonomy sidebars ---
	// 1.8.5: allow for specific taxonomy slugs
	if ( ( 'taxonomy' == $context ) || ( 'subtaxonomy' == $subcontext ) ) {
		$term = get_queried_object();
		$taxonomy = $term->taxonomy;
		if ( 'taxonomy' == $template ) {
			$subtemplate = 'taxonomy-' . $taxonomy;
		}
		if ( 'subtaxonomy' == $template ) {
			$subtemplate = 'subtaxonomy-' . $taxonomy;
		}
		$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );
		if ( $checktemplate ) {
			$template = $subtemplate;
		}
	}

	// --- tag sidebars ---
	// 1.8.5: allow for specific tag slugs (and IDs)
	if ( ( 'tag' == $context ) || ( 'subtag' == $subcontext ) ) {
		$term = get_queried_object();
		$tag = $term->slug;
		if ( 'tag' == $template ) {
			$subtemplate = 'tag-' . $tag;
		} elseif ( 'subtag' == $template ) {
			$subtemplate = 'subtag-' . $tag;
		}
		$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );

		if ( $checktemplate ) {
			$template = $subtemplate;
		} else {
			$tagid = $term->term_id;
			if ( 'tag' == $template ) {
				$subtemplate = 'tag-' . $tagid;
			} elseif ( 'subtag' == $template ) {
				$subtemplate = 'subtag-' . $tagid;
			}
			$checktemplate = bioship_file_hierarchy( 'file', $subtemplate . '.php', array( 'sidebar' ), array() );
			if ( $checktemplate ) {
				$template = $subtemplate;
			}
		}
	}

	// 1.8.5: allow for already checked templates
	if ( !$checktemplate ) {
		$checktemplate = bioship_file_hierarchy( 'file', $template . '.php', array( 'sidebar' ), array() );
	}

	if ( $checktemplate ) {

		bioship_debug( $position . " Sidebar Template Found", $checktemplate );
		return $template;

	} else {

		bioship_debug( $position . " Sidebar Template not found", 'sidebar/' . $template . '.php' );
		$searched = $template;

		// --- fallback for singular post types to default sidebar ---
		if ( is_singular() ) {

			// --- get sidebar mode ---
			// 1.9.0: use new theme layout global
			$sidebarmode = $vthemesidebars['sidebarmode'];
			$subsidebarmode = $vthemesidebars['subsidebarmode'];

			// --- check post type context ---
			// 2.0.7: fix for singular post type usage
			// 2.2.0: fix subsidebar cheeck on custom post types
			// 2.2.0: added filters for fallback templates
			$posttype = get_post_type();
			bioship_debug( "Context / Post Type", $context . '/' . $posttype );
			bioship_debug( "SubContext / Sub Post Type", $subcontext . '/sub' . $posttype );
			if ( ( 'sub' . $posttype == $subcontext ) && ( 'sub' == substr( $template, 0, 3 ) ) ) {
				// 2.2.0: added more explicit check for post type
				if ( 'subpost' == $subcontext ) {
					if ( 'unified' == $subsidebarmode ) {
						$template = 'subsidiary';
					} elseif ( ( 'off' == $subsidebarmode ) || ( 'pagesonly' == $subsidebarmode ) ) {
						$template = '';
					} else {
						$template = 'subpost';
					}
				} elseif ( 'subpage' == $subcontext ) {
					if ( 'unified' == $subsidebarmode ) {
						$template = 'subsidiary';
					} elseif ( ( 'off' == $subsidebarmode ) || ( 'postsonly' == $subsidebarmode ) ) {
						$template = '';
					} else {
						$template = 'subpage';
					}
				} else {
					$template = 'sub' . $posttype;
				}

				bioship_debug( "Fallback Sidebar Template", $template );
				$template = bioship_apply_filters( 'skeleton_fallback_template_subsidebar', $template, $posttype );

			} elseif ( $context == $posttype ) {
				// 2.2.0: added more explicit check for post type
				if ( 'post' == $context ) {
					if ( 'unified' == $sidebarmode ) {
						$template = 'primary';
					} elseif ( ( 'off' == $sidebarmode ) || ( 'pagesonly' == $sidebarmode ) ) {
						$template = '';
					} else {
						$template = 'post';
					}
				} elseif ( 'page' == $context ) {
					if ( 'unified' == $sidebarmode ) {
						$template = 'primary';
					} elseif ( ( 'off' == $sidebarmode ) || ( 'postsonly' == $sidebarmode ) ) {
						$template = '';
					} else {
						$template = 'page';
					}
				} else {
					$template = $posttype;
				}

				bioship_debug( "Fallback Sidebar Template", $template );
				$template = bioship_apply_filters( 'skeleton_fallback_template_sidebar', $template, $posttype );

			}

			bioship_debug( "Filtered Fallback Sidebar Template", $template );

			// --- check for fallback template ---
			// 2.2.0: added check to prevent double search for same template
			if ( $template != $searched ) {
				$checktemplate = bioship_file_hierarchy( 'file', $template . '.php', array( 'sidebar' ), array() );
				if ( $checktemplate ) {
					bioship_debug( "Fallback Sidebar Template found", $checktemplate );
					return $template;
				}
			}
		}

		// TODO: retest blank sidebar output display behaviour ?
		// if (substr($template,0,3)) == 'sub') {$template = 'subblank';} else {$template = 'blank';}

		return '';
	}

 }
}

// --------------------------
// Output Sidebar at Position
// --------------------------
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_sidebar' ) ) {
 function bioship_get_sidebar( $position ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 1.8.5: rename global sidebaroutput to themesidebars
	global $vthemelayout, $vthemesidebars;

	// --- check for sidebars ---
	$sidebar = $vthemesidebars['sidebar'];
	$subsidebar = $vthemesidebars['subsidebar'];
	if ( THEMEDEBUG ) {
		echo "<!-- Final Sidebar States: ";
		$sidebarstate = $vthemesidebars;
		unset( $sidebarstate['output'] );
		echo esc_html( print_r( $sidebarstate, true ) );
		if ( $sidebar ) {
			echo "Main Sidebar";
		} else {
			echo "No Main Sidebar";
		}
		if ( $subsidebar ) {
			echo " - SubSidebar";
		} else {
			echo " - No SubSidebar";
		}
		echo " -->";
	}
	if ( !$sidebar && !$subsidebar ) {
		return;
	}

	// --- output buffered sidebar HTML ---
	$output = $vthemesidebars['output'];
	if ( 'left' == $position ) {
		$leftoutput = $output[0];
		$insideleftoutput = $output[1];
		if ( '' != $leftoutput ) {
			// 2.2.0: use wp_kses with allowed HTML on sidebar output
			$allowed = bioship_allowed_html( 'sidebar', 'left_outside' );
			echo wp_kses( $leftoutput, $allowed );
		}
		if ( '' != $insideleftoutput ) {
			// 2.2.0: use wp_kses with allowed HTML on sidebar output
			$allowed = bioship_allowed_html( 'sidebar', 'left_inside' );
			echo wp_kses( $insideleftoutput, $allowed );
		}
	} elseif ( 'right' == $position ) {
		$insiderightoutput = $output[2];
		$rightoutput = $output[3];
		if ( '' != $insiderightoutput ) {
			$allowed = bioship_allowed_html( 'sidebar', 'right_inside' );
			echo wp_kses( $insiderightoutput, $allowed );
		}
		if ( '' != $rightoutput ) {
			// 2.2.0: use wp_kses with allowed HTML on sidebar output
			$allowed = bioship_allowed_html( 'sidebar', 'right_outside' );
			echo wp_kses( $rightoutput, $allowed );
		}
	}
 }
}

// --------------------------------
// Get Merged Sidebar Template Info
// --------------------------------
// 2.0.9: added sidebar template header scanner
if ( !function_exists( 'bioship_get_sidebar_templates_info' ) ) {
 function bioship_get_sidebar_templates_info( $theme ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemestyledir, $vthemetemplatedir;

	// --- scan template sidebar directory ---
	$sidebarsdir = $vthemetemplatedir . 'sidebar' . DIRSEP;
	$sidebarsdir = bioship_apply_filters( 'theme_template_sidebar_dir', $sidebarsdir, $theme );
	$sidebars = bioship_get_sidebar_template_info( $sidebarsdir, array(), $theme );

	// --- maybe scan stylesheet sidebar directory ---
	if ( THEMECHILD ) {
		$sidebarsdir = $vthemestyledir . 'sidebar' . DIRSEP;
		$sidebarsdir = bioship_apply_filters( 'theme_stylesheet_sidebar_dir', $sidebarsdir, $theme );
		$sidebars = bioship_get_sidebar_template_info( $sidebarsdir, $sidebars, $theme );
	}

	// --- filter and return ---
	$sidebars = bioship_apply_filters( 'skeleton_sidebar_templates', $sidebars );
	return $sidebars;
 }
}

// -------------------------
// Get Sidebar Template Info
// -------------------------
// 2.0.9: added this sidebar template header scanner
// 2.1.1: added missing theme passthrough argument
if ( !function_exists( 'bioship_get_sidebar_template_info' ) ) {
 function bioship_get_sidebar_template_info( $sidebarsdir, $sidebars, $theme ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- set sidebar header search strings ---
	// note: by default sidebar templates headers for string, eg. 'Primary Sidebar:'
	// 2.1.1: removed translation wrappers from search strings
	$sidebartypes = array(
		'primary' 		=> 'Primary Sidebar',
		'subsidiary'	=> 'Subsidiary Sidebar',
		'header'		=> 'Header Sidebar',
		'footer'		=> 'Footer Sidebar',
		'custom'		=> 'Custom Sidebar',
	);
	$sidebartypes = bioship_apply_filters( 'sidebar_types', $sidebartypes, $theme );

	if ( is_dir( $sidebarsdir ) ) {
		$filelist = scandir( $sidebarsdir );
		foreach ( $filelist as $i => $file ) {
			if ( in_array( $file, array( '.', '..' ) ) ) {
				unset( $filelist[$i] );
			} else {
				// --- read the sidebar template file ---
				// 2.1.1: use file function to read template in full
				$data = implode( '', file( $sidebarsdir . $file ) );

				// --- search  sidebar template headers ---
				foreach ( $sidebartypes as $key => $search ) {
					if ( strstr( $data, $search . ':' ) ) {
						$posa = strpos( $data, $search ) + strlen( $search ) + 1;
						$name = substr( $data, $posa, strlen( $data ) );
						$posb = strpos( $name, '*/' );
						$name = trim( substr( $name, 0, $posb ) );

						if ( strstr( $name, 'Archive' ) || strstr( $name, 'Search Results' ) ) {
							$sidebartype = 'archive';
						} elseif ( strstr( $name, 'Post Type' ) || strstr( $name, 'Single' ) || strstr( $name, 'Not Found' ) ) {
							$sidebartype = 'single';
						} else {
							$sidebartype = 'custom';
						}

						// 2.1.1: combined if conditions here
						if ( !isset( $sidebars[$key][$sidebartype] ) || !in_array( $file, $sidebars[$key][$sidebartype] ) ) {
							$sidebars[$key][$sidebartype][$file] = $name;
						}
					}
				}
			}

			// --- force custom type to last in array list ---
			foreach ( $sidebars as $key => $sidebarlist ) {
				foreach ( $sidebarlist as $sidebartype => $files ) {
					if ( 'custom' == $sidebartype ) {
						$customsidebars = $sidebars[$key]['custom'];
						unset( $sidebars[$key]['custom'] );
						$sidebars[$key]['custom'] = $customsidebars;
					}
				}
			}
		}
	}
	return $sidebars;
 }
}

// -----------------
// Get Loop Template
// -----------------
// (allows for matching templates and /loop/ subdirectory usage)
// 1.8.5: replaces get_template_part('loop','index') for loop
if ( !function_exists( 'bioship_get_loop' ) ) {
 function bioship_get_loop( $filepath = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	global $vthemelayout, $vthemestyledir, $vthemetemplatedir;

	// --- get page contexts ---
	$pagecontext = $vthemelayout['pagecontext'];
	$subpagecontext = $vthemelayout['subpagecontext'];

	// --- check for loop template directory ---
	$loopdir = false;
	if ( is_dir( $vthemestyledir . 'loop' ) ) {
		$loopdir = true;
	} elseif ( ( $vthemestyledir != $vthemetemplatedir ) && ( is_dir( $vthemetemplatedir . 'loop' ) ) ) {
		$loopdir = true;
	}

	// --- set empty templates ---
 	$templates = array();
	$name = '';

	// --- filter to allow for custom override ---
	$template = bioship_apply_filters( 'skeleton_loop_template', $pagecontext );
	if ( $template && is_string( $template ) && ( $template != $pagecontext ) ) {
		$name = $template;
		if ( $loopdir ) {
			$templates[] = 'loop/' . $template;
		}
		$templates[] = 'loop-' . $template;
	}

	// --- for matching base-loop template by filename ---
	if ( $filepath ) {
		$pathinfo = pathinfo( $filepath );
		$filename = $pathinfo['filename'];
	}
	bioship_debug( "Template File Basename", $filename );
 	if ( $filename && ( 'index' != $filename ) ) {
 		if ( '' == $name ) {
			$name = $filename;
		}
 		if ( $loopdir ) {
			$templates[] = 'loop/' . $filename . '.php';
		}
 		$templates[] = 'loop-' . $filename . '.php';
 	}

 	// --- for subarchive types ---
 	if ( 'archive' == $pagecontext ) {
 		// 1.9.5: fix to bioship_apply_filters typo!
 		$looparchive = bioship_apply_filters( 'skeleton_loop_archive_template', $subpagecontext );
 		if ( $looparchive && is_string( $looparchive ) && ( '' != $looparchive ) ) {
 			if ( '' == $name ) {
				$name = $looparchive;
			}
 			if ( $loopdir ) {
				$templates[] = 'loop/' . $looparchive . '.php';
			}
 			$templates[] = 'loop-' . $looparchive . '.php';
 		}
 	}

	// --- for post types ---
	// 2.2.0: added for post type subpage context
	if ( 'single' == $pagecontext ) {
		// TODO: add new filter to filter docs
		$loopposttype = bioship_apply_filters( 'skeleton_loop_post_type_template', $subpagecontext );
		if ( $loopposttype && is_string( $loopposttype ) && ( '' != $loopposttype ) ) {
			if ( $loopdir ) {
				$templates[] = 'loop/' . $loopposttype . '.php';
			}
			$templates[] = 'loop-' . $loopposttype . '.php';
		}
	}

 	// --- add default template hierarchy ---
 	if ( $loopdir ) {
		$templates[] = 'loop/' . $pagecontext . '.php';
	}
 	$templates[] = 'loop-' . $pagecontext . '.php';
 	if ( $loopdir ) {
		$templates[] = 'loop/index.php';
	}
 	$templates[] = 'loop-index.php';

 	// --- fire matching internal WordPress hook ---
 	if ( '' == $name ) {
		$name = $pagecontext;
	}
 	bioship_do_action( 'get_template_part_loop', 'loop', $name );

 	// --- and fire this one in any case for loop index ---
 	bioship_do_action( 'get_template_part_loop', 'loop', 'index' );

	// --- filter to allow for template hierarchy override ---
	$looptemplates = bioship_apply_filters( 'skeleton_loop_templates', $templates );
	if ( is_array( $looptemplates ) ) {
		$templates = $looptemplates;
	}

	bioship_locate_template( $templates, true, false );
 }
}

// --------------
// Get Loop Title
// --------------
// 1.9.8: moved from /content/loop-meta.php
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_loop_title' ) ) {
 function bioship_get_loop_title() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// --- get archive loop title ---
	// 1.8.0: replaced hybrid_loop_title (HC3 deprecated)
	if ( function_exists( 'get_the_archive_title' ) ) {
		$looptitle = get_the_archive_title();
	} elseif ( function_exists( 'hybrid_loop_title' ) ) {
		$looptitle = hybrid_loop_title();
	} else {
		$looptitle = '';
	}

	// note: get_the_archive_title filter also available
	// 2.2.0: remove bioship function prefix for hybrid filter
	$looptitle = apply_filters( 'hybrid_loop_title', $looptitle );
	$looptitle = bioship_apply_filters( 'skeleton_loop_title', $looptitle );
	return $looptitle;
 }
}

// --------------------
// Get Loop Description
// --------------------
// 1.9.8: moved here from /content/loop-meta.php
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_loop_description' ) ) {
 function bioship_get_loop_description() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get archive loop description ---
	// 1.8.0: replace hybrid_get_loop_description (HC3 deprecated)
	if ( function_exists( 'get_the_archive_description' ) ) {
		$description = get_the_archive_description();
	} elseif ( function_exists( 'hybrid_get_loop_description' ) ) {
		$description = hybrid_get_loop_description();
	} else {
		$description = '';
	}

	// note: get_the_archive_description filter also available
	// 2.2.0: remove bioship function prefix for hybrid filter
	$description = apply_filters( 'hybrid_loop_description', $description );
	$description = bioship_apply_filters( 'skeleton_loop_description', $description );
	return $description;
 }
}

// -----------------------
// Locate Template Wrapper
// -----------------------
// just a copy of WordPress 'locate_template' function
// (for a possible future filter/feature implementation)
if ( !function_exists( 'bioship_locate_template' ) ) {
 function bioship_locate_template( $template_names, $load = false, $require_once = true ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 1.8.5: this makes it just a passthrough for now
	return locate_template( $template_names, $load, $require_once );

	// 1.8.5: added locate_template copy
	// (available for debugging by commenting out return above)
	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( !$template_name ) {
			continue;
		}
		if ( file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {
			$located = get_stylesheet_directory() . '/' . $template_name;
			break;
		} elseif ( file_exists( get_template_directory() . '/' . $template_name ) ) {
			$located = get_template_directory() . '/' . $template_name;
			break;
		}
	}

	if ( $load && ( '' != $located ) ) {
		load_template( $located, $require_once );
	}

	return $located;
 }
}

// ------------------------
// Comments Template Filter
// ------------------------
// ...this is a tricky one, WordPress has not made this easy..!
// (first, maybe remove the Hybrid comments template filter)
remove_filter( 'comments_template', 'hybrid_comments_template', 5 );

if ( !function_exists( 'bioship_comments_template' ) ) {

 // --- add our own comments template filter ---
 // (we are moving comments.php to theme /content/ template path)
 // 2.0.5: increase priority to run later as template override
 add_filter( 'comments_template', 'bioship_comments_template', 11 );

 function bioship_comments_template( $templatepath ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- handle comments are closed ---
	// 1.5.0: hide the default "Comments are Closed"
	// if this is *not* done here, it somehow magically re-appears?! :-/
	if ( !have_comments() && !comments_open() ) {
		// 2.2.0: use esc_html instead of esc_attr on output text
		echo '<p class="nocomments" style="display:none;">' . esc_html( __( 'Comments are Closed.', 'bioship' ) ) . '</p>';
		// return so that the comments_template is not called
		return false;
	}

	// note: default comment template is: STYLESHEETPATH.'/comments.php';
	// with fallback to TEMPLATEPATH.'/comments.php'
	$pathinfo = pathinfo( $templatepath );
	$template = $pathinfo['basename'];

	// --- check for post type comments template ---
	if ( 'comments.php' == $template ) {
		$posttype = get_post_type();
		$posttypetemplate = 'comments-' . $posttype . '.php';
		$commentstemplate = bioship_file_hierarchy( 'file', $posttypetemplate, array( 'content' ) );
		if ( $commentstemplate ) {
			bioship_debug( "Comments Template Path", $commentstemplate );
			return $commentstemplate;
		}
	}

	// --- for other templates (or no post type template) ---
	// use the file hierarchy to locate template instead
	$commentstemplate = bioship_file_hierarchy( 'file', $template, array( 'content' ) );
	if ( $commentstemplate ) {
		bioship_debug( "Comments Template Path", $commentstemplate );
		return $commentstemplate;
	}

	// --- otherwise return unchanged template path ---
	bioship_debug( "Comments Template Path", $templatepath );
	return $templatepath;
 }
}

// ----------------------------------
// Add Archive Templates to Hierarchy
// ----------------------------------
// idea via Flagship Library: flagship_content_template_hierarchy
// slight change in implementation but the idea remains the same...
// split the templates into singular and archive to avoid conditionals
// uses the existing Hybrid Core content filter... (default is off)
if ( !function_exists( 'bioship_archive_template_hierarchy' ) ) {

 add_filter( 'hybrid_content_template_hierarchy', 'bioship_archive_template_hierarchy', 11 );

 function bioship_archive_template_hierarchy( $templates ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemestyledir, $vthemetemplatedir;

	// --- check archive templates directory ---
	// 1.9.5: only add archive template search if an archive subdirectory exists
	if ( is_singular() || is_attachment() ) {return $templates;}
	$archivedir = bioship_apply_filters( 'skeleton_archive_template_directory', 'archive' );
	$contentdir = bioship_apply_filters( 'skeleton_content_template_directory', 'content' );
	if ( !is_string( $archivedir ) || !is_string( $contentdir ) ) {
		return $templates;
	}
	if ( !is_dir( $vthemestyledir . $archivedir ) && !is_dir( $vthemetemplatedir . $archivedir ) ) {
		return $templates;
	}

	// --- set archive templates ---
	$archivetemplates = array();
	foreach ( $templates as $template ) {
		// 1.9.5: add archive subdirectory to a hierarchy instead of replacing content
		if ( strstr( $template, $contentdir . '/' ) ) {
			$archivetemplates[] = str_replace( $contentdir . '/', $archivedir . '/', $template );
		}
	}

	// --- merge archive templates and return ---
	$newtemplates = array_merge( $archivetemplates, $templates );
	bioship_debug( "Archive Template Hiearchy", $newtemplates );
	return $newtemplates;
 }
}

// ---------------------------------
// Content Directory Template Filter
// ---------------------------------
// 1.9.5: similar to archive template filter, but just allows change of default /content/ directory
if ( !function_exists( 'bioship_content_template_hierarchy' ) ) {

 add_filter( 'hybrid_content_template_hierarchy', 'bioship_content_template_hierarchy', 10, 3 );

 function bioship_content_template_hierarchy( $templates ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	// --- filter content directory ---
 	// 2.1.1: added content directory check
 	$contentdir = bioship_apply_filters( 'skeleton_content_template_directory', 'content' );
 	if ( !is_string( $contentdir ) || ( 'content' == $contentdir ) ) {
		return $templates;
	}
	if ( !is_dir( $vthemestyledir . $contentdir ) && !is_dir( $vthemetemplatedir . $contentdir ) ) {
		return $templates;
	}

 	// --- modify content template paths ---
	foreach ( $templates as $key => $template ) {
		if ( strstr( $template, 'content/' ) ) {
			$templates[$key] = str_replace( 'content/', $contentdir . '/', $template );
		}
	}

	// --- return content templates ---
	bioship_debug( "Content Template Hierarchy", $templates );
	return $templates;
 }
}

// -----------------
// Get Author Avatar
// -----------------
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_author_avatar' ) ) {
 function bioship_get_author_avatar( $email = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	global $vthemesettings;

 	// --- filter avatar size ---
	// 2.2.0: move filter to after theme setting
	$avatarsize = $vthemesettings['authoravatarsize'];
	$avatarsize = bioship_apply_filters( 'skeleton_author_bio_avatar_size', $avatarsize );

	// --- filter author email ---
	$email = bioship_apply_filters( 'skeleton_author_gravatar_email', $email );
	if ( !$email ) {
		// 2.0.8: fix to get avatar outside content loop
		if ( is_singular() ) {
			global $post;
			$postid = $post->ID;
		} else {
			$postid = false;
		}
		$author = bioship_get_author_by_post( $postid );
		if ( $author ) {
			$email = get_the_author_meta( 'user_email', $author->ID );
		}
	}
	$avatar = get_avatar( $email, $avatarsize );

	return $avatar;
 }
}

// ----------------------
// Get Author via Post ID
// ----------------------
// 1.8.0: added these helpers as seems no easy way
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_author_by_post' ) ) {
	function bioship_get_author_by_post( $postid = false ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// 2.0.8: added filter for global author override
		$authorid = false;
		if ( $postid ) {
			$authorid = get_post_field( 'post_author', $postid );
		}
		// 2.1.1: added postid argument to author ID filter
		$authorid = bioship_apply_filters( 'skeleton_author_id', $authorid, $postid );
		bioship_debug( "Author ID", $authorid );
		if ( !$authorid ) {
			return false;
		}
		$author = get_user_by( 'id', $authorid );
		return $author;
	}
}

// -------------------------------------
// Get Author Display from Author Object
// -------------------------------------
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_author_display' ) ) {
	function bioship_get_author_display( $author ) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	 	// --- get author display name ---
	 	$authordata = $author->data;
		$authordisplay = trim( $authordata->display_name );
		if ( '' != $authordisplay ) {
			// 2.2.0: check if nice_name property exists
			if ( property_exists( $authordata, 'nice_name' ) ) {
				$authordisplay = trim( $authordata->nice_name );
			} else {
				$authordisplay = $authordata->user_login;
			}
		}

		// -- filter author display name ---
		// 2.2.0: added author as second filter argument
		$authordisplay = bioship_apply_filters( 'skeleton_author_display_name', $authordisplay, $author );
		bioship_debug( "Author Display Name", $authordisplay );
		return $authordisplay;
	}
}

// ------------------------------
// Get Author Display via Post ID
// ------------------------------
// 2.0.5: moved from skeleton.php
if ( !function_exists( 'bioship_get_author_display_by_post' ) ) {
	function bioship_get_author_display_by_post( $postid = false ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- get author for post ---
		$author = bioship_get_author_by_post( $postid );
		if ( !$author ) {
			return false;
		}

		// --- get author display name ---
		$authordisplay = bioship_get_author_display( $author );
		return $authordisplay;
	}
}

// --------------------
// Regenerate Thumbnail
// --------------------
// 2.0.5: added for on-the-fly regeneration of new image sizes
// ref: https://gist.github.com/rnagle/2366998
if ( !function_exists( 'bioship_regenerate_thumbnails' ) ) {
 function bioship_regenerate_thumbnails( $postid, $size = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- get thumbnail attachment ---
    $attachmentid = get_post_thumbnail_id( $postid );
    if ( $attachmentid ) {

    	// --- get attachment meta ---
	    $attachmentmeta = wp_get_attachment_metadata( $attachmentid );
	    bioship_debug( "Thumbnail Metadata", $attachmentmeta );

	    // --- check for image size ---
	    // 2.0.9: added a fix here for when sizes array is not set
	    if ( isset( $attachmentmeta['sizes'] ) ) {
			$sizes = array_keys( $attachmentmeta['sizes'] );

			// 2.0.9: add check that sizes in an array
			if ( !$size || !is_array( $sizes ) || !in_array( $size, $sizes ) ) {
				// 2.1.4: fix to empty function exists argument
				if ( !function_exists( 'wp_generate_attachment_metadata' ) ) {
					include_once ABSPATH . '/wp-admin/includes/image.php';
				}
				$attachedfile = get_attached_file( $attachmentid );

				// ---generate and update image size attachment ---
				$regenerated = wp_generate_attachment_metadata( $attachmentid, $attachedfile );
				$updated = wp_update_attachment_metadata( $attachmentid, $regenerated );
				bioship_debug( "Regenerated Thumbnail Metadata", $regenerated );
			}
		}
	}
	return $postid;
 }
}

// -----------------------------
// Formattable Entry Meta Output
// -----------------------------
// 2.0.5: moved here from skeleton.php
if ( !function_exists( 'bioship_get_entry_meta' ) ) {
	function bioship_get_entry_meta( $postid, $posttype, $position ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		global $vthemesettings;

		// --- set post object ---
		// 2.1.1: get post via postid instead of post global
		$post = get_post( $postid );

		// --- get metaformat according to position ---
		if ( 'page' == $posttype ) {
			$metaformat = $vthemesettings['pagemeta' . $position];
		} else {
			$metaformat = $vthemesettings['postmeta' . $position];
		}

		// --- get list entry meta format ---
		// 1.5.0 fix to this for post only list meta
		// 1.6.0 fix from is_search to is_search()
		if ( 'post' == $posttype ) {
			if ( is_archive() || is_search() || !is_singular( $posttype ) ) {
				if ( ( 'top' == $position ) && ( 0 == $vthemesettings['listentrymetatop'] ) ) {
					$metaformat = '';
				}
				// 1.9.9: fix to setting check value (0 not 1)
				if ( ( 'bottom' == $position ) && ( 0 == $vthemesettings['listentrymetabottom'] ) ) {
					$metaformat = '';
				}
			}
		}

		// --- apply meta format filters ---
		// 1.5.0: optional meta format for an archive list
		if ( is_archive() || is_search() || !is_singular( $posttype ) ) {
			$format = bioship_apply_filters( 'skeleton_list_meta_format_' . $position, $metaformat );
		} else {
			$format = bioship_apply_filters( 'skeleton_meta_format_' . $position, $metaformat );
			// 1.9.9: added post type specific meta format filter
			$format = bioship_apply_filters( 'skeleton_meta_format_' . $posttype, $format );
		}

		// --- bug out if empty format string ---
		if ( '' == $format ) {
			return '';
		}
		bioship_debug( "Meta Format", $format );

		// --- maybe add meta separator span ---
		if ( strstr( $format, ' by ' ) ) {
			$format = str_replace( ' by ', ' <span class="meta-sep">by</span> ', $format );
		}
		if ( strstr( $format, ' BY ' ) ) {
			$format = str_replace( ' BY ', ' <span class="meta-sep">BY</span> ', $format );
		}
		if ( strstr( $format, ' By ' ) ) {
			$format = str_replace( ' By ', ' <span class="meta-sep">By</span> ', $format );
		}
		if ( strstr( $format, '|' ) ) {
			$format = str_replace( '|', '<span class="meta-sep">|</span>', $format );
		}
		if ( strstr( $format, ':' ) ) {
			$format = str_replace( ':', '<span class="meta-sep">:</span>', $format );
		}
		// 2.0.0: remove this one as causing some duplicate replacements
		// if (strstr($format,'-')) {$format = str_replace('-', '<span class="meta-sep">-</span>', $format);}

		// Do Replacement Values
		// ---------------------
		// 1.9.9: do single old % to # replacement here to remove old rechecks
		$format = str_replace( '%', '#', $format );

		// New Lines
		// ---------
		// 2.0.8: add new line tag replacements
		$format = str_replace( '#NEWLINE#', '<br>', $format );

		// Post Format Link
		// ----------------
		if ( strstr( $format, '#POSTFORMAT#' ) || strstr( $format, '#POSTFORMATLINK#' ) ) {

			// --- get post format link ---
			// 2.1.1: set default replacement value to empty
			$postformatlink = '';
			$postformat = get_post_format();
			if ( $postformat ) {
				// TODO: maybe use hybrid_post_format_link for post format meta ?
				$url = get_post_format_link( $postformat );
				$postformatlink = sprintf( '<a href="%s" class="post-format-link">%s</a>', esc_url( $url ), get_post_format_string( $postformat ) );
			}

			// --- replace meta tags ---
			$format = str_replace( '#POSTFORMAT#', $postformatlink, $format );
			$format = str_replace( '#POSTFORMATLINK#', $postformatlink, $format );
		}

		// Edit Link
		// ---------
		if ( strstr( $format, '#EDITLINK#' ) || strstr( $format, '#EDIT#' ) ) {

			// --- set edit URL ---
			// 1.9.9: added post id for possible archive pages
			// 2.1.1: set default replacement value to empty
			$editlink = '';
			$editurl = get_edit_post_link( $postid );

			if ( $editurl ) {

				// --- get edit link post type display ---
				// 1.5.0: use the post type display label
				if ( 'page' == $posttype ) {
					$posttypedisplay = esc_attr( __( 'Page', 'bioship' ) );
				} elseif ( 'post' == $posttype ) {
					$posttypedisplay = esc_attr( __( 'Post', 'bioship' ) );
				} else {
					$posttypeobject = get_post_type_object( $posttype );
					$posttypedisplay = $posttypeobject->labels->singular_name;
				}
				$posttypedisplay = bioship_apply_filters( 'skeleton_post_type_display', $posttypedisplay );

				// --- set edit link ---
				// 2.0.5: added missing link anchor translation wrapper
				// 2.2.0: add title indicating edit permissions applied
				// 2.2.0: use esc_html instead of esc_attr on anchor text
				$title = __( 'Note: Only users with edit permissions will see this link.', 'bioship' );
				$editlink = '<span class="edit-link"><a href="' . esc_url( $editurl ) . '" title="' . esc_attr( $title ) . '">';
				$editlink .= esc_html( __( 'Edit this', 'bioship' ) ) . ' ' . $posttypedisplay . '</a>.</span>';
			}

			// --- replace meta tags ---
			$format = str_replace( '#EDITLINK#', $editlink, $format );
			$format = str_replace( '#EDIT#', $editlink, $format );
		}

		// Permalink
		// ---------
		if ( strstr( $format, '#PERMALINK#' ) ) {

			// --- set permalink tag ---
			// 2.1.1: set default permalink replacement to empty
			$permalink = '';
			$permalink_url = get_permalink( $postid );
			if ( $permalink_url ) {
				$permalink = '<a href="' . esc_url( $permalink_url ) . '" rel="bookmark">' . esc_html( __( 'Permalink', 'bioship' ) ) . '</a>';
			}

			// --- replace meta tags ---
			// 2.1.1: replace with thepermalink tag
			$format = str_replace( '#PERMALINK#', $permalink, $format );
		}

		// Datelink
		// --------
		if ( strstr( $format, '#DATELINK#' ) ) {

			// --- get date display values ---
			// 1.8.0: fix to post date/time display to match passsed ID
			// 2.1.1: set default replacement value to empty
			$datelink = '';
			$permalink = get_permalink( $postid );
			if ( $permalink ) {
				$timeformat = get_option( 'time_format' );
				$thetime = get_the_time( $timeformat, $postid );
				$dateformat = get_option( 'date_format' );
				$postdate = get_the_date( $dateformat, $postid );
				$thedate = '<time ' . hybrid_get_attr( 'entry-published' ) . '>' . esc_html( $postdate ) . '</time>';

				// --- set date link tag ---
				$datelink = '<a href="' . esc_url( $permalink ) . '" title="' . esc_attr( $thetime ) . '" rel="bookmark">';
				$datelink .= '<span class="entry-date">' . $thedate . '</span></a>';
			}

			// --- replace meta tags ---
			$format = str_replace( '#DATELINK#', $datelink, $format );
		}

		// Parent Page Link
		// ----------------
		// 1.5.0: display parent page link
		if ( strstr( $format, '#PARENTPAGE#' ) || strstr( $format, '#PARENTLINK#' ) ) {

			// 2.1.1: moved default replacements to top
			$pageparent = $pageparentlink = '';

			// 1.9.9: added post ID (for possible archive page cases)
			if ( is_page( $postid ) ) {

				// --- get page parent ID ---
				$parentid = $post->post_parent;

				// --- set page parent link ---
				// 2.1.1: simplified post parent check
				if ( $parentid > 0 ) {
					$parentpermalink = get_permalink( $parentid );
					$parenttitle = get_the_title( $parentid );
					$parentlink = '<a href="' . esc_url( $parentpermalink ) . '">' . esc_html( $parenttitle ) . '</a>';
				}
			}

			// --- replace meta tags ---
			// 1.9.9: shifted this outside page check
			$format = str_replace( '#PARENTPAGE#', $parenttitle, $format );
			$format = str_replace( '#PARENTLINK#', $parentlink, $format );
		}

		// Category List / Taxonomy Cats (linked)
		// -----------------------------
		if ( strstr( $format, '#CATEGORIES#' ) || strstr( $format, '#CATS#' ) || strstr( $format, '#CATSLIST#' ) ) {

			// 2.1.1: removed default off for page type (as may have taxonomy)
		  	$categorylist = '';
			if ( 'post' == $posttype ) {

				// --- get the post category list ---
				$categorylist = get_the_category_list( ', ', '', $postid );

				// --- count post categories ---
				// 2.0.8: count number of categories for display prefix
				// 2.1.1: fix to incorrect term taxonomy (post_tag)
				$terms = get_the_terms( $postid, 'category' );
				$numcats = count( $terms );

			} else {

				// -- handle CPT category terms ---
				$taxonomies = get_object_taxonomies( $post );
				$categoryterms = array();
				if ( count( $taxonomies ) > 0 ) {
					foreach ( $taxonomies as $taxonomy ) {
						if ( ( 'post_tag' != $taxonomy ) && ( 'post_format' != $taxonomy ) ) {
							// 2.1.2: fix to hardcoded category taxonomy
							// 2.1.2: removed use of Hybrid function (returns HTML not an array)
							$terms = get_the_terms( $postid, $taxonomy );
							if ( $terms ) {
								$categoryterms = array_merge( $terms, $categoryterms );
							}
						}
					}
					if ( count( $categoryterms ) > 0 ) {
						// 2.0.8: count number of categories for display prefix
						$numcats = count( $categoryterms );
						$termlinks = array();
						foreach ( $categoryterms as $categoryterm ) {
							// 2.2.0: fix second argument to be term taxonomy (not post_tag)
							$termlink = get_term_link( $categoryterm->slug, $categoryterm->taxonomy );
							$termlinks[] = '<a href="' . esc_url( $termlink ) . '">' . esc_html( $categoryterm->name ) . '</a>';
						}
						$categorylist = implode( ', ', $termlinks );
					}
				}
			}

			// --- add category list wrappers ---
			if ( '' != $categorylist ) {
				// 1.8.5: use hybrid attributes entry-terms (category context)
				$categorylist = '<span ' . hybrid_get_attr( 'entry-terms', 'category' ) . '>' . $categorylist . '</span>';
				// $categorylist = '<span class="cat-links">'.$categorylist.'</span>';
				// note: skeleton classes - entry-utility-prep entry-utility-prep-cat-links
			}

			// --- create unlinked cateogy list ---
			// 1.9.9: use strip tags to create unlinked category list
			$catlist = strip_tags( $categorylist );

			// --- replace meta tags ---
			$format = str_replace( '#CATS#', $catlist, $format );
			$format = str_replace( '#CATEGORIES#', $categorylist, $format );
			if ( strstr( $format, '#CATSLIST#' ) ) {
				if ( '' != $categorylist ) {
					if ( 1 == $numcats ) {
						$replace = esc_html( __( 'Category', 'bioship' ) ) . ': ' . $categorylist . '<br>';
					} else {
						$replace = esc_html( __( 'Categories', 'bioship' ) ) . ': ' . $categorylist . '<br>';
					}
				} else {
					$replace = '';
				}
				// 2.2.0: fix to empty string replacement
				$format = str_replace( '#CATSLIST#', $replace, $format );
			}
		}

		// Parent Category(s)
		// ------------------
		if ( strstr( $format, '#PARENTCATEGORIES#' ) || strstr( $format, '#PARENTCATS#' ) ) {

		  	$parentcategorylist = $parentcatlist = '';
			$categories = get_the_category( $postid );
			if ( count( $categories ) > 0 ) {
				// --- get parent category IDs ---
				$catparentids = array();
				foreach ( $categories as $category ) {
					$catparentids[] = $category->category_parent;
				}

				if ( count( $catparentids ) > 0 ) {
					// --- get parent category links ---
					$categorylinks = $catlinks = array();
					foreach ( $catparentids as $catparentid ) {
						$categoryname = get_cat_name( $catparentid );
						$categorylink = get_category_link( $catparentid );
						$categorylinks[] = '<a href="' . esc_url( $categorylink ) . '">' . esc_html( $categoryname ) . '</a>';
						$catlinks[] = esc_html( $categoryname );
					}

					// --- parent categories prefix ---
					if ( count( $categorylinks ) > 0 ) {
						if ( 1 == count( $categorylinks ) ) {
							$parentcategorylist = esc_html( __( 'Parent Category', 'bioship' ) ) . ': ' . $categorylinks[0];
						} else {
							$parentcategories = implode( ', ', $categorylinks );
							$parentcategorylist = esc_html( __( 'Parent Categories', 'bioship' ) ) . ': ' . $parentcategories;
							$parentcats = implode( ', ', $catlinks );
							$parentcatlist = esc_html( __( 'Parent Categories', 'bioship' ) ) . ': ' . $parentcats;
						}
					}
				}
			}

			// --- parent category list wrappers ---
			// 1.8.5: added hybrid attribute entry-terms (category context)
			if ( '' != $parentcategorylist ) {
				$parentcategorylist = '<span ' . hybrid_get_attr( 'entry-terms', 'category' ) . '>' . $parentcategorylist . '</span>';
			}
			if ( '' != $parentcatlist ) {
				$parentcatlist = '<span ' . hybrid_get_attr( 'entry-terms', 'category' ) . '>' . $parentcatlist . '</span>';
			}

			// --- replace meta tags ---
			$format = str_replace( '#PARENTCATEGORIES#', $parentcategorylist, $format );
			$format = str_replace( '#PARENTCATS#', $parentcatlist, $format );
		}

		// Post Tags / CPT Terms (linked)
		// ---------------------
		if ( strstr( $format, '#POSTTAGS#' ) || strstr( $format, '#TAGS#' ) || strstr( $format, '#TAGSLIST#' ) ) {

			$posttags = '';
			$numtags = 0;
			// 1.9.9: handle page as CPT as may have post_tag taxonomy added
			if ( 'post' == $posttype ) {

				// --- get the post tag list ---
				$posttags = trim( get_the_tag_list( '', ', ' ) );
				// 2.1.1: added undefined tag count
				$tags = explode( ', ', $posttags );
				$numtags = count( $tags );

			} else {

				// --- handle CPT tag terms ('post_tag' taxonomy) ---
				$taxonomies = get_object_taxonomies( $post );
				if ( in_array( 'post_tag', $taxonomies ) ) {
					if ( THEMEHYBRID ) {
						$args = array( 'taxonomy' => 'post_tag', 'text' => '', 'before' => '' );
						$posttags = hybrid_get_post_terms( $args );
					} else {
						// --- get post tag terms ---
						$tagterms = get_the_terms( $postid, 'post_tag' );
						if ( count( $tagterms ) > 0 ) {
							// 2.0.8: count tags for display prefix
							$numtags = count( $tagterms );
							$termlinks = array();
							foreach ( $tagterms as $tagterm ) {
								$termlink = get_term_link( $tagterm->slug, 'post_tag' );
								$termlinks[] = '<a href="' . esc_url( $termlink ) . '">' . esc_html( $tagterm->name ) . '</a>';
							}
							$posttags = implode( ', ', $termlinks );
						}
					}
				}
			}

			// --- add post tags wrapper ---
			if ( '' != $posttags ) {
				// 1.8.5: use hybrid attribute entry-terms
				$posttags = '<span ' . hybrid_get_attr( 'entry-terms', 'post_tag' ) . '>' . $posttags . '</span>';
				// $posttags = '<span class="tag-links">'.$posttags.'</span>';
				// note: skeleton classes - entry-utility-prep entry-utility-prep-tag-links
			}

			// --- create unlinked tag list ---
			// 1.9.9: use strip tags to create unlinked tag list
			$taglist = strip_tags( $posttags );

			// --- replace meta tags ---
			$format = str_replace( '#TAGS#', $taglist, $format );
			$format = str_replace( '#POSTTAGS#', $posttags, $format );
			if ( strstr( $format, '#TAGSLIST#' ) ) {
				if ( '' != $posttags ) {
					// 2.0.8: change display prefix for more than one tag
					if ( 1 == $numtags ) {
						$replace = esc_html( __( 'Tagged', 'bioship' ) ) . ': ' . $posttags . '<br>';
					} else {
						$replace = esc_html( __( 'Tags', 'bioship' ) ) . ': ' . $posttags . '<br>';
					}
				} else {
					$replace = '';
				}
				$format = str_replace( '#TAGSLIST#', $replace, $format );
			}
		}

		// Comments
		// --------
		if ( strstr( $format, '#COMMENTS#' ) || strstr( $format, '#COMMENTSLINK#' ) ) {

			// --- create comment number display ---
			$numcomments = (int) get_comments_number( get_the_ID() );
			$commentsdisplay = '';
			// 1.9.9: add possible missing argument for archives
			if ( comments_open( $postid ) ) {
				if ( 0 === $numcomments ) {
					$commentsdisplay = number_format_i18n( 0 ) . ' ' . esc_html( __( 'comments', 'bioship' ) ) . '.';
				} elseif ( 1 === $numcomments ) {
					$commentsdisplay = number_format_i18n( 1 ) . ' ' . esc_html( __( 'comment', 'bioship' ) ) . '.';
				} elseif ( $numcomments > 1 ) {
					$commentsdisplay = number_format_i18n( $numcomments ) . ' ' . esc_html( __( 'comments', 'bioship' ) ) . '.';
				}
				$commentsdisplay = '<span class="comments-link">' . $commentsdisplay . '</span>';
			}

			// --- replace meta tags ---
			$format = str_replace( '#COMMENTS#', $commentsdisplay, $format );
			$format = str_replace( '#COMMENTSLINK#', $commentsdisplay, $format );
		}

		// Comments Popup
		// --------------
		if ( strstr( $format, '#COMMENTSPOPUP#' ) || strstr( $format, '#COMMENTSPOPUPLINK#' ) ) {
			$comments = '';
			// 1.9.9: add possible missing argument for archives
			if ( comments_open( $postid ) ) {

				// --- set comments popup global ---
				// 1.9.9: add switch for conditional load of comments popup script
				global $vthemecommentspopup;
				$vthemecommentspopup = true;

				// --- get number of comments ---
				$commentscss = 'no-comments';
				// 2.1.1: use postid instead of get_the_ID here
				$numcomments = (int) get_comments_number( $postid );
				if ( 1 === $numcomments ) {
					$commentscss = 'one-comment';
				} elseif ( $numcomments > 1 ) {
					$commentscss = 'multiple-comments';
				}

				// --- get comments popup link ---
				ob_start();
				comments_popup_link(
					number_format_i18n( 0 ) . ' ' . esc_html( __( 'comments', 'bioship' ) ) . '.',
					number_format_i18n( 1 ) . ' ' . esc_html( __( 'comment', 'bioship' ) ) . '.',
					number_format_i18n( $numcomments ) . ' ' . esc_html( __( 'comments', 'bioship' ) ) . '.',
					$commentscss,
					''
				);
				$comments = ob_get_contents();
				ob_end_clean();

				// --- comments link wrapper ---
				$comments = '<span class="comments-link">' . $comments . '</span>';
			}

			// --- replace meta tags ---
			$format = str_replace( '#COMMENTSPOPUP#', $comments, $format );
			$format = str_replace( '#COMMENTSPOPUPLINK#', $comments, $format );
		}

		// Author Info
		// -----------
		// 1.8.0: disambiguate #AUTHOR# and #AUTHORURL#
		if ( strstr( $format, '#AUTHORLINK#' ) || strstr( $format, '#AUTHORNAME#' )
		  || strstr( $format, '#AUTHOR#' ) || strstr( $format, '#AUTHORURL#' ) ) {

			// --- get author info ---
			// 1.8.0: use separate get author display name
			// 1.8.0: fix to the author posts link, add title tag
			$authordisplay = bioship_get_author_display_by_post( $postid );
			$authorid = get_post_field( 'post_author', $postid );
			$authorurl = get_author_posts_url( $authorid );

			// --- get post type display ---
			if ( 'page' == $posttype ) {
				$posttypedisplay = __( 'Pages', 'bioship' );
			} elseif ( 'post' == $posttype ) {
				$posttypedisplay = __( 'Posts', 'bioship' );
			} else {
				$posttypeobject = get_post_type_object( $posttype );
				$posttypedisplay = $posttypeobject->labels->name;
			}

			// --- get author posts link ---
			// 2.0.5: added missing translation wrappers
			$authorpoststitle = esc_attr( __( 'View all', 'bioship' ) ) . ' ' . esc_attr( $posttypedisplay ) . ' ' . esc_attr( __( 'by', 'bioship' ) ) . ' ' . esc_attr( $authordisplay ) . '.';
			$authoranchor = '<a href="' . esc_url( $authorurl ) . '" title="' . $authorpoststitle . '">' . esc_html( $authordisplay ) . '</a>';
			$authorlink = bioship_skeleton_author_posts_link( $authorurl );

			// --- replace meta tags ---
			// 1.9.8: fix to use vauthordisplay not old vauthor variable
			$format = str_replace( '#AUTHORLINK#', $authorlink, $format );
			$format = str_replace( '#AUTHORURL#', $authorurl, $format );
			$format = str_replace( '#AUTHOR#', $authoranchor, $format );
			$format = str_replace( '#AUTHORNAME#', $authordisplay, $format );
		}

		// --- add meta tag wrapper ---
		$meta = bioship_html_comment( '.meta-prep', false ) . PHP_EOL;
		$meta .= '<span class="meta-prep">' . PHP_EOL . $format . PHP_EOL . '</span>' . PHP_EOL;
		$meta .= bioship_html_comment( '/.meta-prep', false ) . PHP_EOL;

		// --- allow for complete meta override ---
		// 2.2.0: added post type specific meta override
		$meta = bioship_apply_filters( 'skeleton_meta_override_' . $position, $meta );
		$meta = bioship_apply_filters( 'skeleton_meta_override_' . $posttype, $meta );
		return $meta;
	}
}


// -------------------
// === Setup Theme ===
// -------------------

if ( !function_exists( 'bioship_setup' ) ) {

 add_action( 'after_setup_theme', 'bioship_setup' );

 function bioship_setup() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemename, $vthemesettings, $vthemedirs;

	// Language Translation
	// --------------------
	// note: https://make.wordpress.org/meta/handbook/documentation/translations/
	// ref: https://ulrich.pogson.ch/load-theme-plugin-translations
	// 2.0.5: load multiple language locations
	load_theme_textdomain( 'bioship', trailingslashit( WP_LANG_DIR ) . 'bioship' );
	load_theme_textdomain( 'bioship', get_stylesheet_directory() . '/languages' );
	load_theme_textdomain( 'bioship', get_template_directory() . '/languages' );

	// Editor Styles
	// -------------
	// 1.9.5: removed is_rtl check as handled automatically by add_editor_style
	// TODO: maybe add Gutenberg editor styles ?
	// ref: https://robinroelofsen.com/editor-styling-gutenberg
	// if (!is_rtl()) {$editorstyle = 'editor-style.css';} else {$editorstyle = 'editor-style-rtl.css';}
	$editorstyleurl = bioship_file_hierarchy( 'url', 'editor-style.css', $vthemedirs['style'] );
	if ( $editorstyleurl ) {
		add_editor_style( $editorstyleurl );
	}

	// Dynamic Editor Styles
	// ---------------------
	if ( isset( $vthemesettings['dynamiceditorstyles'] ) && ( '1' == $vthemesettings['dynamiceditorstyles'] ) ) {

		// 1.9.5: add dynamic editor styles to match skin theme settings styles
		// 2.1.1: removed is_admin() wrapper to allow possible frontend integration
		// ref: https://www.mattcromwell.com/dynamic-tinymce-editor-styles-wordpress/
		add_filter( 'tiny_mce_before_init', 'bioship_add_dynamic_editor_styles' );

		if ( !function_exists( 'bioship_add_dynamic_editor_styles' ) ) {
		 function bioship_add_dynamic_editor_styles( $mceinit ) {
		 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
			global $vthemesettings;

			// --- Editor Content Background Color ---
			// 2.1.1: fix by moving empty style declaration to top
			$styles = '';
			if ( isset( $vthemesettings['contentbgcolor'] ) && ( '' != $vthemesettings['contentbgcolor'] ) ) {
				$styles = 'body.mce-content-body {background-color: ' . $vthemesettings['contentbgcolor'] . ';} ';
			}

			// --- Editor Content Typography ---
			// 1.9.8: set empty undefined variables
			// 2.1.1: set content typography as array key
			$typographies = array();
			if ( isset( $vthemesettings['content_typography'] ) ) {
				$typographies['content'] = $vthemesettings['content_typography'];
			} elseif ( isset( $vthemesettings['body_typography'] ) ) {
				$typographies['content'] = $vthemesettings['body_typography'];
			}

			// --- Heading (and Button) Typographies ---
			// 2.1.1: loop to get all heading typography settings
			$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'button' );
			foreach ( $headings as $heading ) {
				$typographies[$heading] = $vthemesettings[$heading . '_typography'];
			}

			// --- Create Typography Rules ---
			// 2.1.1: loop to set heading typographies also
			foreach ( $typographies as $key => $typography ) {

				// 2.1.1: reset typo rules on each loop
				$typorules = '';

				if ( is_array( $typography ) ) {
					if ( isset( $typography['font-size'] ) ) {
						$typography['size'] = $typography['font-size'];
					}
					if ( isset( $typography['font-family'] ) ) {
						$typography['face'] = $typography['font-family'];
					}
					if ( isset( $typography['font-style'] ) ) {
						$typography['style'] = $typography['font-style'];
					}

					if ( '' != $typography['color'] ) {
						$typorules .= "color:" . $typography['color'] . "; ";
					}
					if ( '' != $typography['size'] ) {
						$fontsize = $typography['size'];
						// $typorules .= "font-size:".$fontsize."; ";
						// 2.0.9: convert font sizes to em for screen scaling
						$fontsize = (int) str_replace( 'px', '', $fontsize );
						// 2.1.2: use round half down helper function
						$fontsize = bioship_round_half_down( $fontsize / 16 );
						$typorules .= "font-size:" . $fontsize . "em; ";
					}
					if ( '' != $typography['face'] ) {
						if ( strstr( $typography['face'], '+' ) ) {
							$typography['face'] = '"' . str_replace( '+', ' ', $typography['face'] ) . '"';
						}
						// 2.1.2: fix to remove double quotes around inherit value
						// note: double quotes must be double escaped or changed to single for tinyMCE javascript
						if ( strstr( $typography['face'], ',' ) || strstr( $typography['face'], '"' ) || ( 'inherit' == $typography['face'] ) ) {
							// $typography['face'] = str_replace('"', '\\"', $typography['face']);
							$typography['face'] = str_replace( '"', "'", $typography['face'] );
							$typorules .= "font-family:" . $typography['face'] . "; ";
						} else {
							// 2.2.0: fix to unnecessary possible double quoting
							$typorules .= "font-family: " . $typography['face'] . "; ";
						}
					}
					if ( '' != $typography['style'] ) {
						if ( 'bold' == $typography['style'] ) {
							$typorules .= "font-weight: bold;";
						} else {
							$typorules .= "font-style:" . $typography['style'] . "; ";
						}
					}
					if ( isset( $typography['font-weight'] ) ) {
						$typorules .= "font-weight:" . $typography['font-weight'] . "; ";
					}
					if ( isset( $typography['line-height'] ) ) {
						$typorules .= "line-height:" . $typography['line-height'] . "; ";
					}
					if ( isset( $typography['letter-spacing'] ) ) {
						$typorules .= "letter-spacing:" . $typography['letter-spacing'] . "; ";
					}
					if ( isset( $typography['text-transform'] ) ) {
						$typorules .= "text-transform:" . $typography['text-transform'] . "; ";
					}
					if ( isset( $typography['font-variant'] ) ) {
						$typorules .= "font-variant:" . $typography['font-variant'] . "; ";
					}

					// --- add typography styles ---
					// 2.1.1: store button rules and use key for headings selectors
					if ( 'button' == $key ) {
						$buttontyporules = $typorules;
					}
					else {
						if ( 'content' == $key ) {
							$key = '';
						} else {
							$key = ' ' . $key;
						}
						$styles .= "body.mce-content-body" . $key . ", ";
						$styles .= "body.mce-content-body .column .inner" . $key . ", ";
						$styles .= "body.mce-content-body .columns .inner" . $key . " {" . $typorules . "} ";
					}
				}
			}

			// --- Inputs ---
			if ( isset( $vthemesettings['inputcolor'] ) || isset( $vthemesettings['inputbgcolor'] ) ) {
				$inputs = " body.mce-content-body input[type='text'], body.mce-content-body input[type='checkbox'], ";
				$inputs .= " body.mce-content-body input[type='password'], body.mce-content-body select, body.mce-content-body textarea {";
				if ( '' != $vthemesettings['inputcolor'] ) {
					$inputs .= "color: " . $vthemesettings['inputcolor'] . "; ";
				}
				if ( '' != $vthemesettings['inputbgcolor'] ) {
					$inputs .= "background-color: " . $vthemesettings['inputbgcolor'] . ";";
				}
				$inputs .= "} ";
				$styles .= $inputs;
			}

			// --- Link Colors and Underlines ---
			if ( ( 'inherit' != $vthemesettings['alinkunderline'] ) || ( '' != $vthemesettings['link_color'] ) ) {
				$links = "body.mce-content-body a, body.mce-content-body a:visited {";
				if ( '' != $vthemesettings['link_color'] ) {
					$links .= "color:" . $vthemesettings['link_color'] . "; ";
				}
				if ( 'inherit' != $vthemesettings['alinkunderline'] ) {
					$links .= " text-decoration:" . $vthemesettings['alinkunderline'] . ";";
				}
				$links .= "} ";
				$styles .= $links;
			}

			// --- Hover Links and Colors ---
			if ( ( 'inherit' != $vthemesettings['alinkhoverunderline'] ) || ( '' != $vthemesettings['link_color'] ) ) {
				$links = "body.mce-content-body a:hover, body.mce-content-body a:focus, body.mce-content-body a:active {";
				if ( '' != $vthemesettings['link_hover_color'] ) {
					$links .= "color:" . $vthemesettings['link_hover_color'] . ";";
				}
				if ( 'inherit' != $vthemesettings['alinkhoverunderline'] ) {
					$links .= " text-decoration:" . $vthemesettings['alinkhoverunderline'] . ";";
				}
				$links .= "} ";
				$styles .= $links;
			}

			// --- Buttons ---
			// 2.0.8: add button style rules also (as in skin.php)
			// 2.1.1: removed all line breaks (as breaking tinymcepreinit)
			// TODO: maybe enqueue PIE for extra editor styles compatibility ?
			$buttons = "body.mce-content-body button, body.mce-content-body input[type='reset'], ";
			$buttons .= "body.mce-content-body input[type='submit'], body.mce-content-body input[type='button'], ";
			$buttons .= "body.mce-content-body a.button, body.mce-content-body .button";

			if ( ( '' == $vthemesettings['button_bgcolor_bottom'] ) || ( $vthemesettings['button_bgcolor_bottom'] == $vthemesettings['button_bgcolor_top'] ) ) {
				$buttonrules = "background: " . $vthemesettings['button_bgcolor_top'] . "; ";
				$buttonrules .= " background-color: " . $vthemesettings['button_bgcolor_top'] . "; ";
				// $buttonrules .= "	behavior: url('".$pieurl."'); ";
			} else {
				$top = $vthemesettings['button_bgcolor_top'];
				$bottom = $vthemesettings['button_bgcolor_bottom'];
				$buttonrules = "background: " . $top . "; background-color: " . $top . "; ";
				$buttonrules .= "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, " . $top . "), color-stop(100%, " . $bottom . ")); ";
				$buttonrules .= "background: -webkit-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%); ";
				$buttonrules .= "background: -o-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%); ";
				$buttonrules .= "background: -ms-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%); ";
				$buttonrules .= "background: -moz-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%); ";
				$buttonrules .= "background: linear-gradient(top bottom, " . $top . " 0%, " . $bottom . " 100%); ";
				// $buttonrules .= "-pie-background: linear-gradient(top, " . $top . ", ".$bottom."); ";
				// $buttonrules .= "behavior: url('".$pieurl."'); ";
			}

			// TODO: add button hover styles to editor style rules ?
			// $buttonrules .= '	'.$buttonhoverrules;

			// --- WooCommerce Buttons ---
			$woocommercebuttons = array(
				'.woocommerce a.alt.button', '.woocommerce button.alt.button', '.woocommerce input.alt.button',
				'.woocommerce #respond input.alt#submit', '.woocommerce #content input.alt.button',
				'.woocommerce-page a.alt.button', '.woocommerce-page button.alt.button', '.woocommerce-page input.alt.button',
				'.woocommerce-page #respond input.alt#submit', '.woocommerce-page #content input.alt.button',
			);
			if ( isset( $vthemesettings['woocommercebuttons'] ) && ( '1' == $vthemesettings['woocommercebuttons'] ) ) {
				$woocommerceselectors = implode( ', body.mce-content-body ', $woocommercebuttons );
				$buttons .= ', body.mce-content-body ' . $woocommerceselectors . ' ';
			}

			// --- add button style rules ---
			// 2.1.1: add stored button typography rules
			$styles .= $buttons . ' {' . $buttonrules . $buttontyporules . '}';

			// --- filter dynamic editor styles ---
			// 2.1.1: added filter for possible style overrides / replacements
			$styles = bioship_apply_filters( 'admin_dynamic_editor_styles', $styles );

			// --- set TinyMCE init content styles ---
			// 1.9.8: addded check if array key exists
			// 2.1.1: remove any line breaks just in case (as they break script)
			$styles = str_replace( "\r\n", '', $styles );
			$styles = str_replace( "\n", '', $styles );
			if ( isset( $mceinit['content_style'] ) ) {
				$mceinit['content_style'] .= ' ' . $styles . ' ';
			} else {
				$mceinit['content_style'] = $styles;
			}

			return $mceinit;
		 }
		}

	}

	// Post Thumbnail Support
	// ----------------------

	// --- add post thumbnail support ---
	// 2.2.0: specify standard post types for add_theme_support
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

	// 1.5.0: add custom post type thumbnail support override
	$thumbcpts = $vthemesettings['thumbnailcpts'];
	if ( $thumbcpts && is_array( $thumbcpts ) && ( count( $thumbcpts ) > 0 ) ) {
		foreach ( $thumbcpts as $cpt => $value ) {
			if ( ( '1' == $value ) && !post_type_supports( $cpt, 'thumbnail' ) ) {
				// 2.2.0: add specific theme support for selected post types
				add_theme_support( 'post-thumbnails', array( $cpt ) );
				add_post_type_support( $cpt, 'thumbnail' );
			}
		}
	}

	// Set Default Post Thumbnail Size
	// -------------------------------
	// TODO: maybe add a 'post-thumbnail' image size (for the post writing screen) ?
	// ref: _wp_post_thumbnail_html in /wp-admin/includes/post.php

	// --- get post thumbnail size ---
	// get_option('thumbnail_image_w');
	// get_option('thumbnail_image_h');
	// 1.5.0: changed default to 250x250 from 150x150
	// for better FB sharing support, as minimum required there is 200x200
	// 1.8.0: changed default to 200x200 as square250 already exists
	$thumbnailwidth = bioship_apply_filters( 'skeleton_thumbnail_width', 200 );
	$thumbnailheight = bioship_apply_filters( 'skeleton_thumbnail_height', 200 );
	$crop = get_option( 'thumbnail_crop' );
	$thumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ( 'nocrop' == $thumbnailcrop ) {
		$crop = false;
	} elseif ( 'auto' == $thumbnailcrop ) {
		$crop = true;
	} elseif ( strstr( $thumbnailcrop, '-' ) ) {
		$crop = explode( '-', $thumbnailcrop );
	}

	// --- set post thumbnail size ---
	set_post_thumbnail_size( $thumbnailwidth, $thumbnailheight, $crop );

	// --- get image sizes ---
	// 2.2.0: separated function to get image sizes
	$imagesizes = bioship_get_image_sizes();

	// --- add image sizes ---
	if ( is_array( $imagesizes ) && ( count( $imagesizes ) > 0 ) ) {
		foreach ( $imagesizes as $size ) {
			add_image_size( $size['name'], $size['width'], $size['height'], $size['crop'] );
		}
	} elseif ( THEMEDEBUG ) {
		bioship_debug( "Image Sizes Error!", $imagesizes );
	}
 }
}


// ----------------------
// === Site Meta Tags ===
// ----------------------

// -------------------------
// Filter Meta Generator Tag
// -------------------------
// 1.8.5: moved here from muscle.php
// changed name from muscle_meta_generator to skeleton_meta_generator
if ( !function_exists( 'bioship_meta_generator' ) ) {

 // 1.8.5: add Hybrid filter to match
 add_filter( 'the_generator', 'bioship_meta_generator', 999 );
 add_filter( 'hybrid_meta_generator', 'bioship_meta_generator' );

 function bioship_meta_generator( $generator ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- filter generator meta (default empty) ---
	$generator = bioship_apply_filters( 'skeleton_generator_meta', '' );
	return $generator;
 }
}

// ------------------
// Mobile Header Meta
// ------------------
// ref: http://www.quirksmode.org/blog/archives/2010/09/combining_meta.html
// ref: http://stackoverflow.com/questions/1988499/meta-tags-for-mobile-should-they-be-used
if ( !function_exists( 'bioship_mobile_meta' ) ) {

 // 1.8.5: add to wp_head hook instead of separate skeleton_mobile_meta action
 add_action( 'wp_head', 'bioship_mobile_meta', 2 );

 function bioship_mobile_meta() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	$mobilemeta = '';

	// TODO: test effect of specific-width mobile meta line ?
	// (320 is a good minimum width but not sure if this info helps mobiles)
	$mobilemeta .= '<meta name="MobileOptimized" content="320">' . PHP_EOL;

	// --- add meta compatibility tags ---
	$mobilemeta = "<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->" . PHP_EOL;
	$mobilemeta .= '<meta name="HandheldFriendly" content="True">' . PHP_EOL; // i wanna hold your haaand...

	// --- maybe add mobile viewport compatibility tag ---
	// 1.8.5: fix to remove duplicate line if using Hybrid Core
	// TODO: check if different action needed for Hybrid Core 3 ?
	if ( !THEMEHYBRID ) {
		$mobilemeta .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
	}

	// --- filter and output mobile meta tags ---
	$mobilemeta = bioship_apply_filters( 'skeleton_mobile_metas', $mobilemeta );
	// TODO: escape mobile meta output
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo $mobilemeta;
 }
}


// ------------------
// === Site Icons ===
// ------------------

// -----------------
// Site Icons Loader
// -----------------
// ref: http://www.jonathantneal.com/blog/understand-the-favicon/
if ( !function_exists( 'bioship_site_icons' ) ) {

 add_action( 'admin_head', 'bioship_site_icons' );
 add_action( 'wp_head', 'bioship_site_icons' );

 function bioship_site_icons() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemedirs;

	// --- if theme site icon is available ---
	// 2.0.8: maybe use the global site icon
	if ( THEMESITEICON ) {

		// --- maybe output debug info for site icon sizes ---
		if ( THEMEDEBUG ) {
			$iconid = get_option( 'site_icon' );
			if ( $iconid ) {
				$iconmeta = wp_get_attachment_metadata( $iconid );
				$sizes = array_keys( $iconmeta['sizes'] );
				bioship_debug( "Site Icon Sizes", $sizes );
			}
		}

		// 2.0.8: add new site icon tag filter and output
		add_filter( 'site_icon_meta_tags', 'bioship_site_icon_tags' );
		wp_site_icon();
		return;
	}

	// 2.0.8: now a fallback if no global site icon
	// 2.0.8: use esc_url on all icon hrefs
	// 2.0.8: explicit files override theme settings
	// 1.8.0: added fallbacks to auto-check for favicon files when URLs are not set

	// --- Apple Touch, use the 144x144 default, then optional sizes ---
	$appleicons = '';
	// 2.2.0: switch to use theme settings before file hierarchy
	$wintile = $vthemesettings['wineighttile'];
	if ( !$wintile ) {
		$wintile = bioship_file_hierarchy( 'url', 'win8tile.png', $vthemedirs['image'] );
	}
	if ( $wintile && ( '' != $wintile  ) ) {
		// 2.0.8: fix to use variable not theme settings value
		$appleicons = '<link rel="apple-touch-icon-precomposed" size="144x144" href="' . esc_url( $wintile ) . '">' . PHP_EOL;
	}
	$icons = bioship_apply_filters( 'skeleton_apple_icons', $appleicons );

	// --- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices, 57x57 size ---
	// 2.2.0: switch to use theme settings before file hierarchy
	$appletouchicon = $vthemesettings['appletouchicon'];
	if ( !$appletouchicon ) {
		$appletouchicon = bioship_file_hierarchy( 'url', 'apple-touch-icon.png', $vthemedirs['image'] );
	}
	if ( $appletouchicon && ( '' != $appletouchicon ) ) {
		// 2.0.8: fix to use variable not theme settings value
		$icons .= '<link rel="apple-touch-icon-precomposed" href="' . esc_url( $appletouchicon ) . '">' . PHP_EOL;
		$icons .= '<link rel="apple-touch-icon" href="' . esc_url( $appletouchicon ) . '">' . PHP_EOL;
	}

	// --- For anything accepting PNG icons, 96x96 default ---
	// 2.2.0: switch to use theme settings before file hierarchy
	$faviconpng = $vthemesettings['faviconpng'];
	if ( !$faviconpng ) {
		$faviconpng = bioship_file_hierarchy( 'url', 'favicon.png', $vthemedirs['image'] );
	}
	if ( $faviconpng && ( '' != $faviconpng ) ) {
		$icons .= '<link rel="icon" href="' . esc_url( $faviconpng ) . '">' . PHP_EOL;
	}

	// --- Just for IE, the default 32x32 or 16x16 size ---
	// 2.2.0: switch to use theme settings before file hierarchy
	$faviconico = $vthemesettings['faviconico'];
	if ( !$faviconico ) {
		// 2.2.0: add missing file hierarchy check
		$faviconico = bioship_file_hierarchy( 'url', 'favicon.ico', $vthemedirs['image'] );
		// 1.8.0: allow for default favicon fallback in root directory
		if ( !$faviconico && file_exists( ABSPATH . DIRSEP . 'favicon.ico' ) ) {
			$faviconico = site_url( '/favicon.ico' );
		}
	}
	if ( $faviconico && ( '' != $faviconico ) ) {
		$icons .= '<!--[if IE]><link rel="shortcut icon" href="' . esc_url( $faviconico ) . '"><![endif]-->' . PHP_EOL;
	}

	// --- For Windows 8, the tile and background ---
	if ( $wintile && ( '' != $wintile ) ) {
		$winbgcolor = '#FFFFFF';
		if ( '' != $vthemesettings['wineightbg'] ) {
			$winbgcolor = $vthemesettings['wineightbg'];
		}
		$icons .= '<meta name="msapplication-TileColor" content="' . esc_url( $winbgcolor ) . '">' . PHP_EOL;
		$icons .= '<meta name="msapplication-TileImage" content="' . esc_url( $wintile ) . '">' . PHP_EOL;
	}

	// --- get startup images ---
	// 1.8.5: moved optional startup image output here
	$startupimages = bioship_apply_filters( 'skeleton_startup_images', '' );
	if ( '' != $startupimages ) {
		$icons .= $startupimages;
	}

	// --- filter and output icon tags ---
	// 1.8.5: added icon override filter
	$icons = bioship_apply_filters( 'skeleton_icons_override', $icons );
	// TODO: escape icons output
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo $icons;
 }
}

// ---------------------
// Site Icon Size Filter
// ---------------------
if ( !function_exists( 'bioship_site_icon_sizes' ) ) {

 // 2.0.8: add the new icon size upload filter
 // 2.1.1: moved from inside to bioship_site_icons function
 add_filter( 'site_icon_image_sizes', 'bioship_site_icon_sizes' );

 function bioship_site_icon_sizes( $sizes ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// note: WordPress defaults are 32, 180, 192 and 270
	$newsizes = array( 57, 72, 76, 96, 114, 120, 144, 152 );
	$sizes = array_merge( $sizes, $newsizes );
	return $sizes;
 }
}

// ---------------------
// Site Icon Tags Filter
// ---------------------
// 2.0.8: added this filter to add site icon meta tags
if ( !function_exists( 'bioship_site_icon_tags' ) ) {
 function bioship_site_icon_tags( $tags ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.2.1: fix for missing globals
	global $vthemesettings, $vthemedirs;
	
	// ---- Apple Touch, use the 144x144 default, then optional sizes ---
	$wintile = bioship_file_hierarchy( 'url', 'win8tile.png', $vthemedirs['image'] );
	if ( !$wintile ) {
		$wintile = get_site_icon_url( 144, $vthemesettings['wineighttile'] );
	}
	if ( '' != $wintile ) {
		$tags[] = '<link rel="apple-touch-icon-precomposed" size="144x144" href="' . esc_url( $wintile ) . '">' . PHP_EOL;
	}

	// --- Apple Icon Sizes ---
	$appleicons = bioship_apply_filters( 'skeleton_apple_icons', '' );
	if ( '' != $appleicons ) {
		$icons = explode( PHP_EOL, $appleicons );
		foreach ( $icons as $icon ) {
			if ( '' != $icon ) {
				$tags[] = $icon;
			}
		}
	}

	// ---- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices, 57x57 size ---
	$appletouchicon = bioship_file_hierarchy( 'url', 'apple-touch-icon.png', $vthemedirs['image'] );
	// 2.2.0: fix to logic condition typo
	if ( !$appletouchicon ) {
		$appletouchicon = get_site_icon_url( 57, $vthemesettings['appletouchicon'] );
	}
	if ( $appletouchicon && ( '' != $appletouchicon ) ) {
		$tags[] = '<link rel="apple-touch-icon-precomposed" href="' . esc_url( $appletouchicon ) . '">';
		$tags[] = '<link rel="apple-touch-icon" href="' . esc_url( $appletouchicon ) . '">';
	}

	// --- For anything accepting PNG icons, 96x96 default ---
	$faviconpng = bioship_file_hierarchy( 'url', 'favicon.png', $vthemedirs['image'] );
	if ( !$faviconpng ) {
		$faviconpng = get_site_icon_url( 96, $vthemesettings['faviconpng'] );
	}
	if ( $faviconpng && ( '' != $faviconpng ) ) {
		$tags[] = '<link rel="icon" href="' . esc_url( $faviconpng ) . '">';
	}

	// --- Just for IE, the default 32x32 (or 16x16) size ---
	$faviconico = bioship_file_hierarchy( 'url', 'favicon.ico', $vthemedirs['image'] );
	if ( !$faviconico && file_exists( ABSPATH . DIRSEP . 'favicon.ico' ) ) {
		$faviconico = site_url( '/favicon.ico' );
	}
	if ( !$faviconico ) {
		$faviconico = $vthemesettings['faviconico'];
	}
	if ( '' == $faviconico ) {
		$faviconico = bioship_generate_favicon();
	}
	if ( $faviconico && ( '' != $faviconico ) ) {
		$tags[] = '<!--[if IE]><link rel="shortcut icon" href="' . esc_url( $faviconico ) . '"><![endif]-->';
	}

	// --- For Windows 8, the tile and background ---
	// 2.1.1: removed duplicate file hierarchy check for win8 tile
	// $wintile = bioship_file_hierarchy('url', 'win8tile.png', $vthemedirs['image']);
	if ( !$wintile ) {
		// 2.1.1: fix to missing variable assignment
		$wintile = get_site_icon_url( 270, $vthemesettings['wineighttile'] );
	}
	if ( $wintile && ( '' != $wintile ) ) {
		if ( '' != $vthemesettings['wineightbg'] ) {
			$winbgcolor = $vthemesettings['wineightbg'];
		} else {
			$winbgcolor = '#FFFFFF';
		}
		$tags[] = '<meta name="msapplication-TileColor" content="' . esc_url( $winbgcolor ) . '">';
		$tags[] = '<meta name="msapplication-TileImage" content="' . esc_url( $wintile ) . '">';
	}

	// 2.0.8: to maintain existing theme startup images filter
	$startupimages = bioship_apply_filters( 'skeleton_startup_images', '' );
	if ( '' != $startupimages ) {
		if ( strstr( $startupimages, PHP_EOL ) ) {
			$images = explode( PHP_EOL, $startupimages );
			foreach ( $images as $startupimage ) {
				if ( '' != trim( $startupimage ) ) {
					$tags[] = $startupimage;
				}
			}
		} else {
			$tags[] = $startupimages;
		}
	}

	// --- icon tags override filter ---
	// 2.0.8: to maintain existing theme icon filter behaviour
	$tags = bioship_apply_filters( 'skeleton_icons_override', $tags );

	return $tags;
 }
}

// -------------------
// Site Icon Generator
// -------------------
// 2.0.8: auto-generate site icon sizes if size does not exist
if ( !function_exists( 'bioship_site_icon_generator' ) ) {

 add_filter( 'get_site_icon_url', 'bioship_site_icon_generator', 1, 3 );

 function bioship_site_icon_generator( $url, $size, $blogid ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	if ( $size >= 512 ) {
		return $url;
	}
	$iconid = get_option( 'site_icon' );

	if ( $iconid ) {
		$iconmeta = wp_get_attachment_metadata( $iconid );
		$sizes = array_keys( $iconmeta['sizes'] );
		bioship_debug( "Requested Site Icon Size", $size );

		// 2.0.9: generate site icon for this size automatically
		if ( !$size || !is_array( $sizes ) || !in_array( $size, $sizes ) ) {
			include_once ABSPATH . '/wp-admin/includes/image.php';
			$attachedfile = get_attached_file( $iconid );
			$generated = wp_generate_attachment_metadata( $iconid, $attachedfile );
			$updated = wp_update_attachment_metadata( $iconid, $generated );
		}

		$url = wp_get_attachment_image_url( $iconid, array( $size, $size ) );
	}

	$url = bioship_apply_filters( 'skeleton_site_icon_url', $url );
 	return $url;
 }
}

// -----------------
// Favicon Generator
// -----------------
if ( !function_exists( 'bioship_generate_favicon' ) ) {
 function bioship_generate_favicon() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// TEMP: bug out here until below is tested
	return '';

	global $vthemedirs;
	// 2.1.1: allow for alternative includes directories
	$phpico = bioship_file_hierarchy( 'file', 'class-php-ico.php', $vthemedirs['includes'] );
	if ( !$phpico ) {
		return false;
	}
	require_once $phpico;

	// TODO: maybe auto-generate a favicon.ico from site icon ?
	// ref: PHP ICO: https://github.com/chrisbliss18/php-ico
	$iconid = get_option( 'site_icon' );
	$source = wp_get_attachment_image_url( $iconid, 'full' );
	// TODO: use an uploads or images directory ?
	$destination = ABSPATH . '/favicon.ico';
	$sizes = array( array( 16, 16 ), array( 24, 24 ), array( 32, 32 ), array( 48, 48 ) );
	$icolib = new PHP_ICO( $source, $sizes );
	// note: get data instead of using save_ico method to write with WP Filesystem
	// extra note: this pseudo-private method is actually public and callable
	$icodata = $icolib->_get_ico_data();
	if ( !$icodata ) {
		return false;
	}
	bioship_write_to_file( $destination, $icodata );
	$iconurl = site_url( '/favicon.ico' );
	return $iconurl;
 }
}

// ----------------------
// Apple Touch Icon Sizes
// ----------------------
// ref: https://mathiasbynens.be/notes/touch-icons
// 2.0.5: check setting in function to allow filtering
if ( function_exists( 'bioship_apple_icon_sizes' ) ) {

 // 2.0.8: added missing filter prefix
 add_filter( 'bioship_skeleton_apple_icons', 'bioship_apple_icon_sizes', 9 );

 // 1.8.5: fix to missing argument
 function bioship_apple_icon_sizes( $appleicons ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemedirs, $vthemesettings;

	// --- check for Apple Icon sizes ---
	// 2.0.5: check setting internally to allow filtering
	$checkforicons = false;
	if ( isset( $vthemesettings['appleiconsizes'] ) && ( '1' == $vthemesettings['appleiconsizes'] ) ) {
		$checkforicons = true;
	}
	$checkforicons = bioship_apply_filters( 'skeleton_apple_icon_sizes', $checkforicons );
	if ( !$checkforicons ) {
		return $appleicons;
	}

	// --- Chrome for Android ---
	// 1.8.0: bugfix typo in hierarchy and url
	// 2.2.0: added missing esc_url in tag output
	$imageurl = bioship_file_hierarchy( 'url', 'touch-icon-192x192.png', $vthemedirs['image'] );
	if ( !$imageurl ) {
		$imageurl = bioship_file_hierarchy( 'url', 'touch-icon-192x192-precomposed.png', $vthemedirs['image'] );
	}
	if ( $imageurl ) {
		$appleicons .= '<link rel="icon" sizes="192x192" href="' . esc_url( $imageurl ) . '">';
	}

	// 2.0.8: use string and numeric array
	$sizes = array();
	// --- For iPhone 6 Plus with @3� display: ---
	$sizes['180x180'] = 180;
	// --- For iPad with @2� display running iOS = 7: ---
	$sizes['152x152'] = 152;
	// --- For iPad with @2� display running iOS = 6: ---
	$sizes['144x144'] = 144;
	// --- For iPhone with @2� display running iOS = 7: ---
	$sizes['120x120'] = 120;
	// --- For iPhone with @2� display running iOS = 6: ---
	$sizes['114x114'] = 114;
	// --- For the iPad mini and the first- and second-generation iPad (@1� display) on iOS = 7: ---
	$sizes['76x76'] = 76;
	// --- For the iPad mini and the first- and second-generation iPad (@1� display) on iOS = 6: ---
	$sizes['72x72'] = 72;

	// --- loop icon sizes ---
	foreach ( $sizes as $size => $iconsize ) {

		// --- set icon image fallback ---
		$fallback = bioship_file_hierarchy( 'url', 'touch-icon-' . $size . '.png', $vthemedirs['image'] );
		if ( !$fallback ) {
			// 1.8.5: allow for fallback for maybe using -precomposed suffix
			$fallback = bioship_file_hierarchy( 'url', 'touch-icon-' . $size . '-precomposed.png', $vthemedirs['image'] );
		}

		// --- maybe use global site icon ---
		// 2.0.8: added for new site icons
		if ( THEMESITEICON ) {
			$iconurl = get_site_icon_url( $iconsize, $fallback );
		} else {
			$iconurl = $fallback;
		}

		// --- maybe add to apple icons links ---
		if ( $iconurl ) {
			// 2.0.8: use esc_url on icon hrefs
			// 2.1.1: fix to mismatching iconurl variable
			// 2.2.0: added missing esc_attr on size variable
			$appleicons .= '<link rel="apple-touch-icon-precomposed" sizes="' . esc_attr( $size ) . '" href="' . esc_url( $iconurl ) . '">' . PHP_EOL;
		}
	}

	return $appleicons;
 }
}

// --------------------
// Apple Startup Images
// --------------------
// 2.0.5: check setting internally to allow filtering
if ( !function_exists( 'bioship_apple_startup_images' ) ) {

 add_filter( 'skeleton_startup_images', 'bioship_apple_startup_images' );

 function bioship_apple_startup_images( $images ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemedirs, $vthemesettings;

	// 2.0.5: check setting internally to allow filtering
	$startupimages = false;
	if ( isset( $vthemesettings['startupimages'] ) && ( '1' == $vthemesettings['startupimages'] ) ) {
		$startupimages = true;
	}
	$startupimages = bioship_apply_filters( 'skeleton_apple_startup_images', $startupimages );
	if ( !$startupimages ) {
		return $images;
	}

	// -- enable Startup Image for iOS Home Screen Web App ---
	$images = '<meta name="apple-mobile-web-app-capable" content="yes" />' . PHP_EOL;
	// $images .= '<link rel="apple-touch-startup-image" href="" />'.PHP_EOL;

	// 2.2.0: fix to startups array indexes
	// --- iPhone 3GS, 2011 iPod Touch (320x460) ---
	$startups[0]['size'] = '320x460';
	$startups[0]['media'] = 'screen and (max-device-width : 320px)';
	// --- iPhone 4, 4S and 2011 iPod Touch (640x920) ---
	$startups[1]['size'] = '640x920';
	$startups[1]['media'] = '(max-device-width : 480px) and (-webkit-min-device-pixel-ratio : 2)';
	// --- iPhone 5 and 2012 iPod Touch (640x1096) ---
	$startups[2]['size'] = '640x1096';
	$startups[2]['media'] = '(max-device-width : 548px) and (-webkit-min-device-pixel-ratio : 2)';
	// --- iPad (non-retina) Landscape (1024x768) ---
	$startups[3]['size'] = '1024x748';
	$startups[3]['media'] = 'screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : landscape)';
	// --- iPad (non-retina) Portrait (768x1004) ---
	$startups[4]['size'] = '768x1004';
	$startups[4]['media'] = 'screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : portrait)';
	// --- iPad (Retina) (Portrait) (1536x2008) ---
	$startups[5]['size'] = '1536x2008';
	$startups[5]['media'] = 'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait) and (-webkit-min-device-pixel-ratio: 2)';
	// --- iPad (Retina) (Landscape) (2048x1496) ---
	$startups[6]['size'] = '2048x1496';
	$startups[6]['media'] = 'screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) and (-webkit-min-device-pixel-ratio: 2)';

	// --- filter startup image sizes ---
	// 2.2.0: added this filter
	$startups = bioship_apply_filters( 'skeleton_startup_image_sizes', $startups );

	// --- loop startup images to add tags ---
	$tags = array();
	if ( is_array( $startups ) && ( count( $startups ) > 0 ) ) {
		foreach ( $startups as $startup ) {
			$url = bioship_file_hierarchy( 'url', 'startup-' . $startup['size'] . '.png', $vthemedirs['image'] );
			if ( $url ) {
				// 2.0.8: use esc_url on image hrefs
				// 2.1.1: use esc_url on hrefs not media
				// 2.2.0: move esc_url within tag
                // 2.2.0: fix to undefined variable size
				$href = $url . 'startup-' . $startup['size'] . '.png';
				$tags[] = '<link rel="apple-touch-startup-image" sizes="' . $startup['size'] . '" href="' . esc_url( $href ) . '" media="' . $startup['media'] . '">';
			}
		}
	}

	// --- filter and return ---
	// 2.2.0: added startup image tags override
	$tags = apply_filters( 'skeleton_startup_image_tags', $tags );
	if ( is_array( $tags ) && ( count( $tags ) > 0 ) ) {
		$images = implode( PHP_EOL, $tags );
	}
	return $images;
 }
}


// -----------------------
// === Enqueue Scripts ===
// -----------------------

// ------------------------
// Enqueue Skeleton Scripts
// ------------------------
// note: Styles moved to Skin Section
// note: for Foundation loading functions see muscle.php
// 1.8.0: added filemtime cache busting option
// 2.0.2: use THEMESLUG instead of vthemename
if ( !function_exists( 'bioship_scripts' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_scripts' );

 function bioship_scripts() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemename, $vthemesettings, $vjscachebust, $vthemedirs;

	// --- check for file modified time cachebusting ---
	// 1.9.5: check and set filemtime use just once
	$filemtime = ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) ? true : false;

	// superfish.js
	// ------------
	// 1.8.5: conditionally load only if there is primary Nav Menu
	// 1.9.5: fix to dashes in theme name slug for theme mods
	// 2.0.9: added filter for superfish enqueuing override
	// 2.2.0: fix variable typo thememods to vthememods
	$loadsuperfish = false;
	$vthememods = get_option( 'theme_mods_' . THEMESLUG );
	if ( isset( $vthememods['nav_menu_locations']['primary'] ) && ( '' != $vthememods['nav_menu_locations']['primary'] ) ) {
		$loadsuperfish = true;
	}
	$loadsuperfish = bioship_apply_filters( 'script_load_superfish', $loadsuperfish );

	if ( $loadsuperfish ) {

		// --- load hover intent (for Superfish) ---
		// 2.1.3: added this script with Superfish update
		$hoverintent = bioship_file_hierarchy( 'both', 'jquery.hoverIntent.js', $vthemedirs['script'] );
		if ( is_array( $hoverintent ) ) {
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $hoverintent['file'] ) ) : $vjscachebust;
			wp_enqueue_script( 'hover-intent', $hoverintent['url'], array( 'jquery' ), $cachebust, true );
		}

		// --- enqueue superfish script ---
		// 2.1.3: honour SCRIPT_DEBUG constant for unminified scripts
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$superfish = bioship_file_hierarchy( 'both', 'superfish' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $superfish ) ) {
			// 2.1.1: fix to cachebusting conditions
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $superfish['file'] ) ) : $vjscachebust;
			// 2.0.1: add theme name prefix to script handle
			wp_enqueue_script( THEMESLUG . '-superfish', $superfish['url'], array( 'jquery' ), $cachebust, true );
		}

		// --- get main menu ID ---
		// 1.8.5: count and set main menu (not submenu) items
		$menuid = $vthememods['nav_menu_locations']['primary'];
		// $mainmenu = get_term( $menuid, 'nav_menu' );
		bioship_debug( "Main Menu ID", $menuid );

		// -- get main menu items ---
		$menuitems = wp_get_nav_menu_items( $menuid, 'nav_menu' );
		// bioship_debug("Main Menu Items", $menuitems);

		// --- count main menu items ---
		// 2.0.7: fix for undefined variable warning
		$menumainitems = 0;
		foreach ( $menuitems as $item ) {
			if ( 0 == $item->menu_item_parent ) {
				$menumainitems++;
			}
		}
		bioship_debug( "Menu Main Items", $menumainitems );

		// --- store menu items count ---
		// note: menu item count is used in skin.php
		if ( get_option( $vthemename . '_menumainitems' ) != $menumainitems ) {
			// 2.0.5: remove unnecessary add_option fallback
			update_option( $vthemename . '_menumainitems', $menumainitems );
		}
	}

	// formalize.js
	// ------------
	// 2.0.9: added filter for load formalize
	// 2.2.0: fix for non-load setting
	$loadformalize = $vthemesettings['loadformalize'] ? true : false;
	$loadformalize = bioship_apply_filters( 'script_load_formalize', $loadformalize );
	if ( $loadformalize ) {
		// 2.1.3: honour SCRIPT_DEBUG constant for unminified scripts
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$formalize = bioship_file_hierarchy( 'both', 'jquery.formalize' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $formalize ) ) {
			// 2.1.1: fix to cachebusting conditions
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $formalize['file'] ) ) : $vjscachebust;
			wp_enqueue_script( 'formalize', $formalize['url'], array( 'jquery' ), $cachebust, true );
		}
	}

	// bioship-init.js
	// ---------------
	// 2.0.9: maintain backwards compatibility for child theme init.js override
	$init = bioship_file_hierarchy( 'both', 'init.js', $vthemedirs['script'] );
	if ( is_array( $init ) ) {
		// 2.1.1: fix to cachebusting conditions
		// 2.0.1: add theme name prefix to script handle
		$cachebust = $filemtime ? date( 'ymdHi', filemtime( $init['file'] ) ) : $vjscachebust;
		wp_enqueue_script( THEMESLUG . '-init', $init['url'], array( 'jquery' ), $cachebust, true );
	} else {
		// --- load theme init script ---
		// 2.0.9: prefix all the things, change init.js filename to bioship-init.js
		// 2.2.0: change filename back to static bioship-init.js
		$themeinit = bioship_file_hierarchy( 'both', 'bioship-init.js', $vthemedirs['script'] );
		if ( is_array( $themeinit ) ) {
			// 2.1.1: fix to cachebusting conditions
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $themeinit['file'] ) ) : $vjscachebust;
			wp_enqueue_script( THEMESLUG . '-init', $themeinit['url'], array( 'jquery' ), $cachebust, true );
		}
	}

	// custom.js
	// ---------
	$custom = bioship_file_hierarchy( 'both', 'custom.js', $vthemedirs['script'] );
	if ( is_array( $custom ) ) {
		// 2.1.1: fix to cachebust conditions
		// 2.0.1: add theme name prefix to script handle
		$cachebust = $filemtime ? date( 'ymdHi', filemtime( $custom['file'] ) ) : $vjscachebust;
		wp_enqueue_script( THEMESLUG . '-custom', $custom['url'], array( 'jquery' ), $cachebust, true );
	}

	// Comment Reply Script
	// --------------------
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Better WordPress Minify Integration
	// -----------------------------------
	// 2.0.2: added to make automatic script filtering possible
	// (as bwp_minify_ignore filter has been missed)
	// TODO: add automatic exclusions for Autoptimize?
	global $bwp_minify;
	if ( is_object( $bwp_minify ) && property_exists( $bwp_minify, 'print_positions' ) ) {
		$positions = $bwp_minify->print_positions;
		if ( is_array( $positions ) && isset( $positions['ignore'] ) ) {
			$handles = $positions['ignore'];
			$nominifyscripts = array( THEMESLUG . '-init', THEMESLUG . '-custom' );
			$nominifyscripts = bioship_apply_filters( 'bwp_nominify_scripts', $nominifyscripts );
			foreach ( $nominifyscripts as $handle ) {
				if ( !in_array( $handle, $handles ) ) {
					$handles[] = $handle;
				}
			}

			// 2.0.9: fix to incorrect array index (style)
			if ( $handles != $positions['ignore'] ) {
				$positions['ignore'] = $handles;
				$bwp_minify->print_positions = $positions;
				bioship_debug( "BWP Ignore Scripts", $handles );
			}
		}
	}

 }
}

// ---------------------------
// Load jQuery from Google CDN
// ---------------------------
// Ref: http://stackoverflow.com/questions/1014203/best-way-to-use-googles-hosted-jquery-but-fall-back-to-my-hosted-library-on-go
// 1.5.0: added a jQuery fallback for if Google CDN fails
// 1.9.5: only do this for frontend to prevent admin conflicts (with load-scripts.php)
// 2.0.7: do this all via filter to avoid reregistering script
// 2.1.1: moved function outside of bioship_scripts
// 2.1.1: check trigger conditions internally
if ( !function_exists( 'bioship_jquery_fallback' ) ) {

 add_filter( 'script_loader_tag', 'bioship_jquery_fallback', 10, 2 );

 function bioship_jquery_fallback( $scripttag, $handle ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings, $vjscachebust, $wp_version, $wp_scripts;

	// --- check trigger conditions ---
	// 2.2.0: bug out early if script debugging
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		return $scripttag;
	}
	// 2.1.1: moved check internal to function
	if ( is_admin() || ( '1' != $vthemesettings['jquerygooglecdn'] ) ) {
		return $scripttag;
	}

	// --- check jquery handle ---
	// 1.8.5: added jquery handle check
	// ref: http://stackoverflow.com/a/17431575/5240159
	// Get jquery handle - WP 3.6 or newer changed the jQuery handle
	// note: new jquery handle is a dependency handle with children of jquery-core and jquery-migrate
	$jqueryhandle = ( version_compare( $wp_version, '3.6-alpha1', '>=' ) ) ? 'jquery-core' : 'jquery';
	// 2.1.1: bug out early if handle does not match
	if ( $handle != $jqueryhandle ) {
		return $scripttag;
	}

	// --- get the built-in jQuery version for current WordPress install ---
	// 1.9.5: fix to silly typo here to make it work again
	// 2.0.7: use wp_scripts global name to get version directly
	// 2.2.0: strip -wp suffix from jquery version added by WP 5+
	$wpjqueryversion = $wp_scripts->registered[$jqueryhandle]->ver;
	$wpjqueryversion = str_replace( '-wp', '', $wpjqueryversion );

	$jqueryversion = bioship_apply_filters( 'skeleton_google_jquery_version', $wpjqueryversion );
	$jquery = 'https://ajax.googleapis.com/ajax/libs/jquery/' . $jqueryversion . '/jquery.min.js';
	// note: test with wp_remote_fopen pointless here as comes from server not client

	// 2.0.7: change script source directly instead of reregistering
	$srcstart = "src='";
	$srcend = "'";
	$posa = strpos( $scripttag, $srcstart ) + strlen( $srcstart );
	$chunks = str_split( $scripttag, $posa );
	unset( $chunks[0] );
	$srctemp = implode( '', $chunks );
	$posb = strpos( $srctemp, $srcend );
	$chunks = str_split( $srctemp, $posb );
	$srctemp = $chunks[0];
	unset( $chunks );
	$scripttag = str_replace( $srctemp, $jquery, $scripttag );

	if ( THEMEDEBUG ) {
		bioship_debug( "jQuery Handle", $jqueryhandle );
		bioship_debug( "WP jQuery Version", $wpjqueryversion );
		bioship_debug( "WP jQuery URL", $srctemp );
		bioship_debug( "New jQuery URL", $jquery );
	}

	if ( strstr( $scripttag, 'jquery.min.js' ) ) {

		// 2.1.2: fix to cachebust == typo in else condition
		// if ($vthemesettings['javascriptcachebusting'] == 'filemtime') {
		// 	$cachebust = date('ymdHi', filemtime(ABSPATH.WPINC.'/js/jquery/jquery.js'));
		// } else {$cachebust = $vjscachebust;}
		// 2.2.0: use extracted jquery src and version instead of cachebusting
		// $jquery = urlencode(site_url().'/wp-includes/js/jquery/jquery.js?ver='.$jqueryversion);
		$jquery = urlencode( add_query_arg( 'ver', $jqueryversion, $srctemp ) );

		// 2.0.7: fix to undefined variable warning
		if ( THEMEDEBUG ) {
			$consoledebug = "console.log('Loading jQuery from Google CDN failed. Loading jQuery from site.'); ";
		} else {
			$consoledebug = '';
		}
		$fallback = "</script><script>if (!window.jQuery) {" . $consoledebug . "document.write(unescape('%3Cscript src=\"" . esc_url( $jquery ) . "\"%3E%3C\/script%3E'));}</script>";
		$scripttag = str_replace( '</script>', $fallback, $scripttag );

		// 2.1.1: remove filter to prevent further tests
		remove_filter( 'script_loader_tag', 'bioship_jquery_fallback', 10, 2 );
	}
	return $scripttag;
 }
}

// --------------------------
// Fix Skip Link Focus (IE11)
// --------------------------
// ref: https://make.wordpress.org/themes/2019/07/14/how-to-add-and-test-skip-links/
// This does not enqueue the script because it is tiny and because it is only for IE11,
// thus it does not warrant having an entire dedicated blocking script being loaded.
// @link https://git.io/vWdr2
if ( !function_exists( 'bioship_skip_link_focus_fix' ) ) {

 add_action( 'wp_print_footer_scripts', 'bioship_skip_link_focus_fix' );

 function bioship_skip_link_focus_fix() {
	echo '<script>';
	echo '/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);';
	echo '</script>';
 }
}


// ----------------------
// === Enqueue Styles ===
// ----------------------
// 2.1.1: moved here from functions.php

// ----------------------------
// Enqueue Frontend Stylesheets
// ----------------------------
// 2.0.5: use THEMESLUG constant insted of vthemename
if ( !function_exists( 'bioship_skin_enqueue_styles' ) ) {

 // 2.0.5: moved add_action inside for consistency
 add_action( 'wp_enqueue_scripts', 'bioship_skin_enqueue_styles' );

 function bioship_skin_enqueue_styles() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vcsscachebust, $vthemedirs;

	// --- maybe use file modified time cachebusting ---
	$filemtime = ( 'filemtime' == $vthemesettings['stylesheetcachebusting'] ) ? true : false;

	// Combined Stylesheet
	// -------------------
	$combinefail = false;
	if ( $vthemesettings['combinecsscore'] ) {
		$corestyles = bioship_file_hierarchy( 'both', 'core-styles.css', $vthemedirs['style'] );
		if ( !is_array( $corestyles ) ) {
			$combinefail = true;
		} else {
			// --- set combined styles cachebusting ---
			// 2.1.1: fix to cachebusting condition
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $corestyles['file'] ) ) : $vcsscachebust;

			// --- enqueue combined core styles ---
			// 2.0.1: add themename prefix to style handle
			// 2.1.1: fix to core dependency array (removed -styles suffix)
			// 2.1.1: direct enqueue without registering first
			// 2.2.1: fix to incorrect coredep concatenation
			wp_enqueue_style( THEMESLUG . '-core', $corestyles['url'], array(), $cachebust );
			$coredep = array( THEMESLUG . '-core' );

			// --- enqueue theme style.css ---
			// (note: must be enqueued separate or CSS breaks)
			// 2.2.1: fix to styleshhet path for filemtime
			$theme_root = get_theme_root( THEMESLUG );
			$stylesheet_path = $theme_root . '/' . THEMESLUG . '/style.css';
			$csscachebust = $filemtime ? date( 'ymdHi', filemtime( $stylesheet_path ) ) : $vcsscachebust;
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style( THEMESLUG . '-styles', get_stylesheet_uri( THEMESLUG ), $coredep, $csscachebust );
		}
	}

	// or Individual Stylesheets
	// -------------------------
	if ( $combinefail || !$vthemesettings['combinecsscore'] ) {

		// --- set empty dependency arrays ---
		$coredep = $maindep = $dep = array();

		// Normalize or Reset CSS
		// ----------------------
		// 2.1.1: removed unnecessary elseif check
		$reset = false;
		if ( 'normalize' == $vthemesettings['cssreset'] ) {
			$normalize = bioship_file_hierarchy( 'both', 'normalize.css', $vthemedirs['style'] );
			if ( is_array( $normalize ) ) {
				// 2.1.1: fix for cachebusting condition
				// 2.1.1: direct enqueue without registering first
				// if ( $filemtime ) {$cachebust = date( 'ymdHi', filemtime( $normalize['file'] ) );} else {$cachebust = $vcsscachebust;}
				$cachebust = $filemtime ? date( 'ymdHi', filemtime( $normalize['file'] ) ) : $vcsscachebust;
				wp_enqueue_style( 'normalize', $normalize['url'], array(), $cachebust );
				$maindep[] = 'normalize';
			}
		} elseif ( 'reset' == $vthemesettings['cssreset'] ) {
			$reset = bioship_file_hierarchy( 'both', 'reset.css', $vthemedirs['style'] );
			if ( is_array( $reset ) ) {
				// 2.1.1: direct enqueue without registering first
				// if ( $filemtime ) {$cachebust = date( 'ymdHi', filemtime( $reset['file'] ) );} else {$cachebust = $vcsscachebust;}
				$cachebust = $filemtime ? date( 'ymdHi', filemtime( $reset['file'] ) ) : $vcsscachebust;
				wp_enqueue_style( 'reset', $reset['url'], array(), $cachebust );
				$maindep[] = 'reset';
			}
		} elseif ( 'reseter' == $vthemesettings['cssreset'] ) {
			// 2.2.0: added reseter.css option
			$filename = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'reseter.css' : 'reseter.min.css';
			$reseter = bioship_file_hierarchy( 'both', $filename, $vthemedirs['style'] );
			if ( is_array( $reseter ) ) {
				// 2.1.1: direct enqueue without registering first
				// if ( $filemtime ) {$cachebust = date( 'ymdHi', filemtime( $reseter['file'] ) );} else {$cachebust = $vcsscachebust;}
				$cachebust = $filemtime ? date( 'ymdHi', filemtime( $reseter['file'] ) ) : $vcsscachebust;
				wp_enqueue_style( 'reseter', $reseter['url'], array(), $cachebust );
				$maindep[] = 'reseter';
			}
		}

		// Dynamic Grid Stylesheet
		// -----------------------
		// 1.5.0: layout stylesheets replaced with new dynamic grid
		// 1.5.0: [deprecated skeleton-960.css, skeleton-1140.css, skeleton-1200.css]
		// 1.8.0: allow for direct load method
		// 1.5.0: added dynamic grid stylesheet
		if ( 'adminajax' == $vthemesettings['themecssmode'] ) {
			$gridurl = admin_url( 'admin-ajax.php' );
			$gridurl = add_query_arg( 'action', 'bioship_grid_dynamic_css', $gridurl );
		} else {
			// 2.1.1: added missing core themedirs argument
			$gridurl = bioship_file_hierarchy( 'url', 'grid.php', $vthemedirs['core'] );
		}

		// 1.8.0: pass content width for calculating content grid
		// 1.8.5: fix to grid URL query separator for admin ajax method
		// 1.9.0: start to pass filtered variables via querystring
		// 1.9.5: pass more filtered variables via querystring
		// 2.0.5: use add_query_arg for all querystring arguments
		global $vthemelayout;

		// --- set grid columns ---
		// 2.0.5: convert values to numbers before passing
		$gridcolumns = bioship_word_to_number( $vthemelayout['gridcolumns'] );
		$gridurl = add_query_arg( 'gridcolumns', $gridcolumns, $gridurl );
		$contentcolumns = bioship_word_to_number( $vthemelayout['contentgridcolumns'] );
		$gridurl = add_query_arg( 'contentgridcolumns', $contentcolumns, $gridurl );

		// --- set widths and padding ---
		// 2.0.5: pass calculated content padding value directly via querystring
		$gridurl = add_query_arg( 'contentwidth', $vthemelayout['rawcontentwidth'], $gridurl );
		$gridurl = add_query_arg( 'maxwidth', $vthemelayout['maxwidth'], $gridurl );
		$gridurl = add_query_arg( 'contentpadding', $vthemelayout['contentpadding'], $gridurl );

		// --- set grid spacing ---
		// 2.0.5: pass filtered options for layout and content grid spacing
		// TODO: maybe distinguish grid padding from grid margins ?
		$gridspacing = isset( $vthemelayout['gridspacing'] ) ? $vthemelayout['gridspacing'] : false;
		$gridspacing = bioship_apply_filters( 'skeleton_layout_grid_spacing', $gridspacing );
		if ( $gridspacing ) {
			$gridurl = add_query_arg( 'gridspacing', $gridspacing, $gridurl );
		}

		// --- set content grid spacing ---
		$contentspacing = isset( $vthemelayout['contentspacing'] ) ? $contentspacing = $vthemelayout['contentspacing'] : false;
		$contentspacing = bioship_apply_filters( 'skeleton_content_grid_spacing', $contentspacing );
		if ( $contentspacing ) {
			$gridurl = add_query_arg( 'contentspacing', $contentspacing, $gridurl );
		}

		// 1.8.5: set theme variable to allow for Multiple Themes usage
		// 2.0.5: removed as no longer checking theme settings in grid.php
		// (all relevant theme settings are checked here and passed to grid.php)
		// $gridtheme = get_option('stylesheet');
		// if (isset($_REQUEST['theme'])) && ($_REQUEST['theme'] != '')) {$gridtheme = $_REQUEST['theme'];}
		// $gridurl = add_query_arg('theme', $gridtheme, $gridurl);

		// --- grid compatibility ---
		// 2.0.5: also pass grid compatibility in querystring
		$gridcompat = array();
		if ( isset( $vthemesettings['gridcompatibility'] ) ) {
			if ( is_array( $vthemesettings['gridcompatibility'] ) ) {
				// 1.9.5: convert cross-framework multicheck options
				$compat = $vthemesettings['gridcompatibility'];
				if ( isset( $compat['960gridsystem'] ) && ( '1' == $compat['960gridsystem'] ) ) {
					$gridcompat['960gs'] = '1';
				} elseif ( in_array( '960gs', $compat ) ) {
					$gridcompat['960gs'] = '1';
				}
				if ( isset( $compat['blueprint'] ) && ( '1' == $compat['blueprint'] ) ) {
					$gridcompat['blueprint'] = '1';
				} elseif ( in_array( 'blueprint', $compat ) ) {
					$gridcompat['blueprint'] = '1';
				}
			}
		}
		if ( count( $gridcompat ) > 0 ) {
			$gridcompat = implode( ',', $gridcompat );
			add_query_arg( 'compat', $gridcompat, $gridurl );
		}

		// --- mobile breakpoints ---
		// 2.0.5: also pass breakpoints in querystring
		if ( isset( $vthemesettings['breakpoints'] ) ) {
			$breakpoints = $vthemesettings['breakpoints'];
			$breakpoints = bioship_apply_filters( 'skeleton_media_breakpoints', $breakpoints );
			$gridurl = add_query_arg( 'breakpoints', $breakpoints, $gridurl );
		}

		// --- set grid cachebusting ---
		// 1.8.5: maybe use last theme option save time for cache busting
		// 2.1.1: fix for cachebusting condition
		if ( $filemtime ) {
			if ( isset( $vthemesettings['savetime'] ) && ( '' != $vthemesettings['savetime'] ) ) {
				$time = $vthemesettings['savetime'];
			}
			// 2.0.7: fix for possible non-numeric bugginess
			if ( !isset( $time ) || !is_numeric( $time ) ) {
				$time = time();
			}
			$cachebust = date( 'YmdHi', $time );
		} else {
			$cachebust = $vcsscachebust;
		}

		// --- enqueue grid.php style URL ---
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style( THEMESLUG . '-grid', $gridurl, $maindep, $cachebust );

		// Grid URL for Customizer Preview
		// -------------------------------
		// 1.9.8: fix to declare pagenow global
		global $pagenow;
		if ( is_customize_preview() && ( $pagenow != 'customize.php' ) ) {

			// --- set gridurl to theme layout global ---
			$vthemelayout['gridurl'] = $gridurl;

			// For Customizer Preview Load in Header/Footer
			// ...but somehow doing this breaks Customizer ?
			//	if ($vthemesettings['themecssmode'] == 'header') {
			//		add_action('wp_head', 'bioship_grid_dynamic_css_inline');
			//	} else {add_action('wp_footer', 'bioship_grid_dynamic_css_inline');}

			if ( !function_exists( 'bioship_grid_url_reference' ) ) {

			 // 2.1.1: move add_action internally for consistency
			 add_action( 'customize_preview_init', 'bioship_grid_url_reference' );

			 function bioship_grid_url_reference() {
				global $vthemelayout;
				$gridurl = $vthemelayout['gridurl'];
				echo '<a href="' . esc_url( $gridurl ) . '" id="grid-url" style="display:none;"></a>';
			 }
			}
		}

		// mobile.css
		// ----------
		// 1.5.0: changed from layout.css (misleading name)
		$mobile = bioship_file_hierarchy( 'both', 'mobile.css', $vthemedirs['style'] );
		if ( is_array( $mobile ) ) {
			// 2.1.1: fix for cachebusting condition
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $mobile['file'] ) ) : $vcsscachebust;

			// --- enqueue mobile.css ---
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style( THEMESLUG . '-mobile', $mobile['url'], $maindep, $cachebust );
		}

		// Main Theme Stylesheets
		// ----------------------

		// formalize.css
		// -------------
		if ( $vthemesettings['loadformalize'] ) {
			$formalize = bioship_file_hierarchy( 'both', 'formalize.css', $vthemedirs['style'] );
			if ( is_array( $formalize ) ) {
				// 2.1.1: fix for cachebusting condition
				$cachebust = $filemtime ? date( 'ymdHi', filemtime( $formalize['file'] ) ) : $vcsscachebust;

				// --- enqueue formalize.css ---
				// note: intentionally not theme-prefixed (common core stylesheet library)
				wp_enqueue_style( 'formalize', $formalize['url'], $maindep, $cachebust, 'screen, projection' );
			}
		}

		// skeleton.css
		// ------------
		$skeletoncss = bioship_file_hierarchy( 'both', 'skeleton.css', $vthemedirs['style'] );
		if ( is_array( $skeletoncss ) ) {
			// 2.1.1: fix for cachebusting condition
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $skeletoncss['file'] ) ) : $vcsscachebust;

			// --- enqueue skeleton.css ---
			// 2.0.1: add themename prefix to style handle
			wp_enqueue_style( THEMESLUG . '-skeleton', $skeletoncss['url'], $maindep, $cachebust );
			$dep = array( THEMESLUG . '-skeleton' );
		}

		// style.css
		// ---------
		// 2.0.0: fix for filemtime cachebusting stylesheet filepath
		// 2.1.1: fix for cachebusting condition
		$dep = array_merge( $maindep, $dep );
		$stylesheetpath = get_stylesheet_directory() . DIRSEP . 'style.css';
		$cachebust = $filemtime ? date( 'ymdHi', filemtime( $stylesheetpath ) ) : $vcsscachebust;

		// --- enqueue theme style.css ---
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style( THEMESLUG . '-styles', get_stylesheet_uri( THEMESLUG ), $dep, $cachebust );

	}

	// Superfish Menu
	// --------------
	$superfish = bioship_file_hierarchy( 'both', 'superfish.css', $vthemedirs['style'] );
	if ( is_array( $superfish ) ) {
		// 2.1.1: fix for cachebusting condition
		$cachebust = $filemtime ? date( 'ymdHi', filemtime( $superfish['file'] ) ) : $vcsscachebust;

		// --- enqueue Superfish styles ---
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style( THEMESLUG . '-superfish', $superfish['url'], $coredep, $cachebust, 'screen, projection' );
	}

	// custom.css
	// ----------
	// auto-loaded custom CSS (if found)
	$customcss = bioship_file_hierarchy( 'both', 'custom.css', $vthemedirs['style'] );
	if ( is_array( $customcss ) ) {
		// 2.1.1: fix for cachebusting condition
		$cachebust = $filemtime ? date( 'ymdHi', filemtime( $customcss['file'] ) ) : $vcsscachebust;

		// --- enqueue custom CSS ---
		// 2.0.1: add themename prefix to style handle, remove css suffix
		wp_enqueue_style( THEMESLUG . '-custom', $customcss['url'], $coredep, $cachebust );
	}

	// Hybrid Cleaner Gallery
	// ----------------------
	if ( current_theme_supports( 'cleaner-gallery' ) ) {

		// 1.8.0: use Hybrid version-specific CSS, added missing cachebuster
		$hybriddir = ( '3' == $vthemesettings['hybridloadcore'] ) ? 'hybrid3' : 'hybrid2';

		// 2.1.1: fix for missing includes directory prefixing
		$hybridstyledirs = array();
		if ( count( $vthemedirs['includes'] ) > 0 ) {
			foreach ( $vthemedirs['includes'] as $dir ) {
				$hybridstyledirs[] = $dir . '/' . $hybriddir . '/css';
			}
		}
		$hybridstyledirs = array_merge( $hybridstyledirs, $vthemedirs['style'] );

		// --- check for min suffix ---
		// 2.1.1: add minified source suffix unless debugging
		$suffix = ( THEMEDEBUG || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min';

		// --- find Hybrid cleaner gallery styles ---
		$gallerycss = bioship_file_hierarchy( 'both', 'gallery' . $suffix . '.css', $hybridstyledirs );
		if ( is_array( $gallerycss ) ) {
			// 2.1.1: fix for cachebusting condition
			$cachebust = $filemtime ? date( 'ymdHi', filemtime( $gallerycss['file'] ) ) : $vcsscachebust;
			wp_enqueue_style( 'cleaner-gallery', $gallerycss['url'], array(), $cachebust );
		}
	}

	// Enqueue Dynamic Stylesheet Skin
	// -------------------------------
	// 1.5.0: tested direct skin loader option for performance
	// 1.8.0: moved here for better enqueueing
	$cssmode = $vthemesettings['themecssmode'];

	// 1.8.0: allow for header/footer inline page load
	// 1.8.5: fix to wrap header/footer output in style tags!
	if ( 'header' == $cssmode ) {
		add_action( 'wp_head', 'bioship_skin_dynamic_css_inline' );
	} elseif ( 'footer' == $cssmode ) {
		add_action( 'wp_footer', 'bioship_skin_dynamic_css_inline' );
	} else {
		// --- set skin URL ---
		// 2.0.5: set default to admin ajax skin load
		// 2.1.1: use add_query_arg for skin URL
		$skinurl = admin_url( 'admin-ajax.php' );
		$skinurl = add_query_arg( 'action', 'bioship_skin_dynamic_css', $skinurl );

		// 2.0.8: only use direct method if not WP.org version
		if ( !THEMEWPORG && ( 'direct' == $cssmode ) ) {
			$skin = bioship_file_hierarchy( 'both', 'skin.php', $vthemedirs['core'] );
			if ( is_array( $skin ) ) {

				// --- check direct load path ---
				// 2.0.5: check path to wp-load.php is above skin.php before using!
				$fileincludes = get_included_files();
				foreach ( $fileincludes as $filepath ) {
					$pathinfo = pathinfo( $filepath );
					if ( 'wp-load.php' == $pathinfo['basename'] ) {
						$loadpath = dirname( $filepath );
						continue;
					}
				}
				$skinpath = substr( $skin['file'], 0, strlen( $loadpath ) );
				if ( $skinpath === $loadpath ) {
					$skinurl = $skin['url'];
				}
				bioship_debug( "WP Load Directory", $loadpath );
				bioship_debug( "Skin Directory to Match", $skinpath );
			}
		}
		bioship_debug( "Skin URL", $skinurl );

		// --- set skin theme ---
		// 1.8.5: set anyway to allow for Multiple Themes/Theme Test Drive override
		// TODO: check for internal skin theme value override ?
		$skintheme = get_stylesheet();
		if ( isset( $_REQUEST['theme'] ) ) {
			// 2.1.2: use sanitize_title on request input
			$themeslug = sanitize_title( trim( $_REQUEST['theme'] ) );
			if ( '' != $themeslug ) {
			    // 2.2.0: fix to undefined variable theme
				$theme = wp_get_theme( $themeslug );
				// 2.0.9: check this is a valid theme before using override
				if ( is_object( $theme ) && ( $theme instanceof WP_Theme ) ) {
					$skintheme = $themeslug;
				}
			}
		}
		$skinurl = add_query_arg( 'theme', $skintheme, $skinurl );

		// --- set skin cachebusting ---
		if ( $filemtime ) {
			// 1.8.5: maybe use last theme options saved time for cachebusting
			// 2.0.7: fix to key typo (savedtime) and possible non-numeric bugginess
			if ( isset( $time ) ) {
				unset( $time );
			}
			if ( isset( $vthemesettings['savetime'] ) && ( '' != $vthemesettings['savetime'] ) ) {
				$time = $vthemesettings['savetime'];
			}
			if ( !isset( $time ) || !is_numeric( $time ) ) {
				$time = time();
			}
			$cachebust = date( 'ymdHi', $time );
		} else {
			$cachebust = $vcsscachebust;
		}

		// --- enqueue dynamic skin ---
		// 2.0.1: add themename prefix to style handle
		wp_enqueue_style( THEMESLUG . '-skin', $skinurl, array(), $cachebust );
	}

	// maybe Disable Emojis
	// --------------------
	// 1.9.5: added disable emojis option
	// 2.0.9: fix to setting and filter logic
	// 2.1.0: fix to default variable name
	$disableemojis = false;
	if ( isset( $vthemesettings['disablemojis'] ) && ( '1' == $vthemesettings['disableemojis'] ) ) {
		$disableemojis = true;
	}
	// 2.0.5: added this missing filter check
	$disableemojis = bioship_apply_filters( 'skeleton_disable_emojis', $disableemojis );
	if ( $disableemojis ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	// deregister WP PageNavi Style
	// ----------------------------
	// (native style support via imported skeleton.css styles)
	if ( !function_exists( 'bioship_skin_deregister_styles' ) ) {
		add_action( 'wp_print_styles', 'bioship_skin_deregister_styles', 100 );
		function bioship_skin_deregister_styles() {
			wp_deregister_style( 'wp-pagenavi' );
		}
	}

	// Enqueue Heading Fonts
	// ---------------------
	bioship_do_action( 'bioship_skin_typography' );

	// Better WordPress Minify Integration
	// -----------------------------------
	// 2.0.2: automatically ignore some styles for BWP plugin
	// (as bwp_minify_ignore filter has been missed)
	// TODO: add automatic exclusions for Autoptimize?
	global $bwp_minify;
	if ( is_object( $bwp_minify ) && property_exists( $bwp_minify, 'print_positions' ) ) {
		$positions = $bwp_minify->print_positions;
		if ( is_array( $positions ) && isset( $positions['style_ignore'] ) ) {
			$handles = $positions['style_ignore'];
			$nominifystyles = array( THEMESLUG . '-core', THEMESLUG . '-skeleton', THEMESLUG . '-mobile', THEMESLUG . '-styles' );
			$nominifystyles = apply_filters( 'bioship_bwp_nominify_styles', $nominifystyles );
			foreach ( $nominifystyles as $handle ) {
				if ( !in_array( $handle, $handles ) ) {
					$handles[] = $handle;
				}
			}
			if ( $handles != $positions['style_ignore'] ) {
				$positions['style_ignore'] = $handles;
				$bwp_minify->print_positions = $positions;
				bioship_debug( "BWP Ignore Styles", $handles );
			}
		}
	}

 }
}

// -------------------------
// Enqueue Admin Stylesheets
// -------------------------
if ( !function_exists( 'bioship_skin_enqueue_admin_styles' ) ) {

 // 1.8.5: fix to admin script enqueue typo
 // 2.0.5: moved add action inside for consistency
 add_action( 'admin_enqueue_scripts', 'bioship_skin_enqueue_admin_styles' );

 // 2.0.9: added hook parameter to function argument
 function bioship_skin_enqueue_admin_styles( $hook = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// 1.9.8: fix add missing global vcsscachebust here
	global $vthemename, $vthemesettings, $vthemedirs, $vcsscachebust;

	// Dynamic Admin Stylesheet
	// ------------------------
	if ( '' != $vthemesettings['dynamicadmincss'] ) {

		$cssmode = $vthemesettings['themecssmode'];
		// 2.0.1: maybe use separate option for admin styles loading mode?
		if ( isset( $vthemesettings['admincssmode'] ) ) {
			$cssmode = $vthemesettings['admincssmode'];
		}

		if ( 'adminajax' == $cssmode ) {
			$skinurl = admin_url( 'admin-ajax.php' );
			$skinurl = add_query_arg( 'action', 'bioship_skin_dynamic_admin_css', $skinurl );
		} elseif ( 'direct' == $cssmode ) {
			// 1.8.5: set admin styles load via querystring
			$skinurl = bioship_file_hierarchy( 'url', 'skin.php', $vthemedirs['core'] );
			$skinurl = add_query_arg( 'adminstyles', 'yes', $skinurl );
		}

		// 1.8.5: use add_query_arg here
		$skintheme = get_stylesheet();
		if ( isset( $_REQUEST['theme'] ) ) {
			// 2.1.2: use sanitize_title on request input
			$themeslug = sanitize_title( trim( $_REQUEST['theme'] ) );
			if ( '' != $themeslug ) {
				$skintheme = $themeslug;
			}
		}
		$skinurl = add_query_arg( 'theme', $skintheme, $skinurl );

		// 1.8.5: wrap in style for inline header/footer printing
		if ( 'header' == $vthemesettings['themecssmode'] ) {
			add_action( 'admin_head', 'bioship_skin_dynamic_admin_css_inline' );
		} elseif ( 'footer' == $vthemesettings['themecssmode'] ) {
			add_action( 'admin_footer', 'bioship_skin_dynamic_admin_css_inline' );
		} else {
			if ( 'filemtime' == $vthemesettings['stylesheetcachebusting'] ) {
				if ( isset( $vthemesettings['savetime'] ) && ( '' != $vthemesettings['savetime'] ) ) {
					$time = $vthemesettings['savetime'];
				}
				// 2.0.7: fix to non-numeric saved time bugginess
				if ( !isset( $time ) || !is_numeric( $time ) ) {
					$time = time();
				}
				$cachebust = date( 'ymdHi', $time );
			} else {
				$cachebust = $vcsscachebust;
			}
			// 2.0.1: prefix style handle with theme name
			wp_enqueue_style( $vthemename . '-admin-skin', $skinurl, array(), $cachebust );
		}
	}

	// Formalize.css
	// -------------
	if ( $vthemesettings['loadformalize'] ) {
		$formalize = bioship_file_hierarchy( 'both', 'formalize.css', $vthemedirs['style'] );
		if ( is_array( $formalize ) ) {
			$cachebust = ( 'filemtime' == $vthemesettings['stylesheetcachebusting'] ) ? date( 'ymdHi', filemtime( $formalize['file'] ) ) : $vcsscachebust;
			// 1.9.8: fix to remove unneeded dependency
			wp_enqueue_style( 'formalize', $formalize['url'], array(), $cachebust, 'screen, projection' );
		}
	}

	// maybe Disable Emojis
	// --------------------
	// 1.9.5: added disable emojis option
	// 2.0.9: fix to setting and filter logic
	// 2.1.0: fix to default variable typo
	$disableemojis = false;
	if ( isset( $vthemesettings['disablemojis'] ) && ( '1' == $vthemesettings['disableemojis'] ) ) {
		$disableemojis = true;
	}
	// 2.0.9: added this missing filter check
	$disableemojis = bioship_apply_filters( 'skeleton_disable_emojis', $disableemojis );
	if ( $disableemojis ) {
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	// Editor Styles
	// -------------
	// 1.9.5: for editor styles, maybe enqueue Google fonts for post writing / editing pages
	if ( isset( $vthemesettings['dynamiceditorstyles'] ) && ( '1' == $vthemesettings['dynamiceditorstyles'] ) ) {
		global $pagenow;
		// 2.2.0: added post-new.php to pagenow check
		if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
			bioship_do_action( 'bioship_skin_typography' );
		}
	}

	// Sticky Widget Areas
	// -------------------
	// 2.0.9: add sticky widgets admin page styles
	// 2.1.3: removed from here to load conditionally via admin.php

 }
}

// --------------------------
// Enqueue Heading Typography
// --------------------------
// (autoload any requested fonts from Google Fonts)
// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (see muscle.php)

if ( !function_exists( 'bioship_skin_typography_loader' ) ) {

 add_action( 'bioship_skin_typography', 'bioship_skin_typography_loader' );

 function bioship_skin_typography_loader() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;

	// 2.2.0: added to prevent undefined variable warnings
	$fonts = $extrafonts = array();

	// Handle Extra Font options
	// -------------------------
	if ( '' != $vthemesettings['extrafonts'] ) {

		// --- get extra fonts ---
		if ( strstr( $vthemesettings['extrafonts'], ',' ) ) {
			$extrafonts = explode( ',', $vthemesettings['extrafonts'] );
		} else {
			$extrafonts = array( $vthemesettings['extrafonts'] );
		}

		// --- loop extra fonts ---
		// 2.0.5: use simple array key index
		foreach ( $extrafonts as $i => $extrafont ) {
			$extrafont = trim( $extrafont );
			$thisstyle = 'normal';
			if ( strstr( $extrafont, ':' ) ) {
				$fontparts = explode( ':', $extrafont );
				$thisface = $fontparts[0];
				if ( 'b' == $fontparts[1] ) {
					$thisstyle = 'bold';
				} elseif ( 'i' == $fontparts[1] ) {
					$thisstyle = 'italics';
				} elseif ( 'bi' == $fontparts[1] ) {
					$thisstyle = 'bolditalics';
				}
			} else {
				$thisface = $extrafont;
			}

			$thisfont = array( 'face' => $thisface, 'style' => $thisstyle );
			// 2.0.5: start extra fonts at 1 not 0
			$fonts['extra' . ( $i + 1 ) ] = $thisfont;
		}
	}

	// Body Font Stacks
	// ----------------
	// 1.9.0: check for Raleway default in body/section font stacks...

	// --- loop body font settings ---
	$ralewayfound = false;
	$bodyfonts = array( 'body', 'header', 'navmenu', 'navsubmenu', 'sidebar', 'subsidebar', 'content', 'footer', 'button' );
	foreach ( $bodyfonts as $bodyfont ) {

		// --- this font face ---
		$thisfont = $vthemesettings[$bodyfont . '_typography'];
		if ( isset( $thisfont['face'] ) ) {
			$thisface = $thisfont['face'];
		} elseif ( isset( $thisfont['font-family'] ) ) {
			$thisface = $thisfont['font-family'];
		} else {
		    $thisface = '';
        }

		// --- add Raleway font by default ---
		if ( ( substr( $thisface, 0, strlen( '"Raleway"' ) ) == '"Raleway"' ) && ( count( $fonts ) > 0 ) ) {
			$ralewayfound = true;
		}
	}

	// Heading Typography
	// ------------------
	// 1.9.0: loop heading fonts
	$headingfonts = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	foreach ( $headingfonts as $headingfont ) {
		$fonts[$headingfont] = $vthemesettings[$headingfont . '_typography'];
	}

	// --- maybe auto-add default Raleway font ---
	// 2.0.5: double check existing to prevent duplicates
	// 2.2.0: moved outside of body font loop
	if ( $ralewayfound ) {
		$addfont = true;
		foreach ( $fonts as $font ) {
			if ( isset( $font['face'] ) && ( 'Raleway' == $font['face'] ) ) {
				$addfont = false;
			} elseif ( isset( $font['font-family'] ) && ( 'Raleway' == $font['font-family'] ) ) {
				$addfont = false;
			}
		}
		if ( $addfont ) {
			// 2.0.5: count extra fonts to get next index
			$i = count( $extrafonts ) + 1;
			$thisfont['face'] = 'Raleway';
			$fonts['extra' . $i] = $thisfont;
		}
	}

	// Headline and Tagline
	// --------------------
	// 1.9.0: updated to match new separate text display options
	// 1.9.8: fix to key typo: use header_texts not header_text
	// 2.2.0: split header text options
	// if ( '1' == $vthemesettings['header_texts']['sitetitle'] ) {$fonts['headline'] = $vthemesettings['headline_typography'];}
	// if ( '1' == $vthemesettings['header_texts']['sitedescription'] ) {$fonts['tagline'] = $vthemesettings['tagline_typography'];}
	if ( '1' == $vthemesettings['site_title'] ) {
		$fonts['headline'] = $vthemesettings['headline_typography'];
	}
	if ( '1' == $vthemesettings['site_description'] ) {
		$fonts['tagline'] = $vthemesettings['tagline_typography'];
	}

	// Autoload Selected Fonts (avoiding duplicates)
	// ---------------------------------------------
	$protocol = THEMESSL ? 'https' : 'http';
	$queried = array();
	$i = $j = $k = 1;
	foreach ( $fonts as $fontkey => $font ) {

		$style = '';

		// --- set font face ---
		// 1.8.0: fix for Titan Framework options
		if ( !isset( $font['face'] ) ) {$font['face'] = $font['font-family'];}
		$thisface = strtolower( $font['face'] );
		// echo '<!-- '.$fontkey.' - '.$font['face'].' - '.$font['style'].' -->'; // debug point

		// 1.8.0: filter out possible font stack requests
		// (load via extra fonts option if using a title font stack)
		// 2.1.0: ignore where font face value is set to inherit
		// 2.2.0: streamlined check ignore logic
		$ignore = array( 'inherit', 'sans-serif', 'serif' );
		if ( ( '' != $thisface ) && !strstr( $thisface, ',' ) && !in_array( $thisface, $ignore ) ) {

			// --- replace whitespace ---
			// 1.5.0: fix to replace all whitespace for custom font
			// $fontface = preg_replace('/\s+/', '%20', $font['face']);
			// $fontface = str_replace('+', '%20', $fontface);
			// 1.8.0: actually better to use + instead of %20 after all!
			$fontface = preg_replace( '/\s+/', '+', $font['face'] );

			// --- maybe set style suffix ---
			// 1.8.0: fix for Titan Framework options
			if ( !isset( $font['style'] ) ) {
				$font['style'] = $font['font-style'];
			}
			if ( 'bold' == $font['style'] ) {
				$style = ':b';
			}
			if ( 'bolditalics' == $font['style'] ) {
				$style = ':bi';
			}
			if ( ( 'italics' == $font['style'] ) || ( 'italic' == $font['style'] ) ) {
				$style = ':i';
				// TESTME: this may need some better logic?
				if ( isset( $font['font-weight'] ) && ( 'bold' == $font['font-weight'] ) ) {
					$style = ':bi';
				}
			}

			// --- set font stack query args ---
			$query = $fontface . $style;
			$queryargs = array( 'family' => $query );
			if ( !in_array( $query, $queried ) ) {

				// -- set font stack key ---
				if ( in_array( $fontkey, $headingfonts ) ) {
					$fontstackkey = 'heading-font-' . $i;
					$i++;
				} else {
					$fontstackkey = 'custom-font-' . $j;
					$j++;
				}

				// --- enqueue font style ---
				$fonturl = add_query_arg( $queryargs, $protocol . '://fonts.googleapis.com/css' );
				wp_enqueue_style( $fontstackkey, $fonturl, array(), null );
				$k++;
				$queried[$k] = $query;

				// --- add preconnect resource hints ---
				// 2.0.9: add preconnect for Google Font resources
				// ref: https://core.trac.wordpress.org/ticket/37171
				if ( !isset( $addedpreconnect ) ) {
					add_filter( 'wp_resource_hints', 'bioship_google_font_resource_hint', 10, 2 );
					$addedpreconnect = true;
				}
			}
		}
	}

	bioship_debug( "Enqueued Fonts", $queried );
 }
}

// -------------------
// Font Resource Hints
// -------------------
// 2.0.9: added Google Font preconnect hint
// ref: https://core.trac.wordpress.org/ticket/37171
if ( !function_exists( 'bioship_google_font_resource_hint' ) ) {
 function bioship_google_font_resource_hint( $urls, $relation_type ) {
 	// adds <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
 	if ( 'preconnect' === $relation_type ) {
		if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '>=' ) ) {
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		} else {
			$urls[] = 'https://fonts.gstatic.com';
		}
	}
	return $urls;
 }
}

// ----------------
// AJAX Skin Styles
// ----------------
// 2.0.7: added optional included argument for inline
if ( !function_exists( 'bioship_skin_dynamic_css' ) ) {

 // 2.1.1: move add_action internally for consistency
 add_action( 'wp_ajax_bioship_skin_dynamic_css', 'bioship_skin_dynamic_css' );
 add_action( 'wp_ajax_nopriv_bioship_skin_dynamic_css', 'bioship_skin_dynamic_css' );

 function bioship_skin_dynamic_css( $includedskin = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	global $vthemedirs;
	$skin = bioship_file_hierarchy( 'file', 'skin.php', $vthemedirs['core'] );
	if ( $skin ) {
		include $skin;
	}

	// 2.1.2: fix to not exit for inline styles
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		exit;
	}
 }
}

// ------------------------
// Print Skin Styles Inline
// ------------------------
// 1.8.5: for printing inline header/footer styles
if ( !function_exists( 'bioship_skin_dynamic_css_inline' ) ) {
 function bioship_skin_dynamic_css_inline() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	echo '<style id="dynamic-styles">';
 	bioship_skin_dynamic_css( true );
 	echo '</style>';
 }
}

// ----------------
// AJAX Grid Styles
// ----------------
// 1.5.0: added dynamic grid stylesheet
// 1.8.5: added optional included argument
if ( !function_exists( 'bioship_grid_dynamic_css' ) ) {

 // 2.1.1: move add_action internally for consistency
 // 2.1.1: added missing theme prefix to action name
 add_action( 'wp_ajax_bioship_grid_dynamic_css', 'bioship_grid_dynamic_css' );
 add_action( 'wp_ajax_nopriv_bioship_grid_dynamic_css', 'bioship_grid_dynamic_css' );

 function bioship_grid_dynamic_css( $included = false ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemedirs;
	$grid = bioship_file_hierarchy( 'file', 'grid.php', $vthemedirs['core'] );
	if ( $grid ) {
		include $grid;
	}

	// 2.1.2: fix to not exit for inline styles
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		exit;
	}
 }
}

// -----------------------
// Print Grid Style Inline
// -----------------------
// 1.8.5: for printing inline header/footer styles
if ( !function_exists( 'bioship_grid_dynamic_css_inline' ) ) {
 function bioship_grid_dynamic_css_inline() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	echo '<style id="grid-styles">';
 	bioship_grid_dynamic_css();
 	echo '</style>' . PHP_EOL;
 }
}

// ---------------------
// AJAX Admin CSS Loader
// ---------------------
// 1.8.5: added optional login style argument
if ( !function_exists( 'bioship_skin_dynamic_admin_css' ) ) {

 // 2.1.1: move add_action internally for consistency
 add_action( 'wp_ajax_bioship_skin_dynamic_admin_css', 'bioship_skin_dynamic_admin_css' );
 add_action( 'wp_ajax_nopriv_bioship_skin_dynamic_admin_css', 'bioship_skin_dynamic_admin_css' );

 function bioship_skin_dynamic_admin_css( $loginstyles = false ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemedirs;

	// --- admin-only styles switch ---
	$adminstyles = true;

	// --- load skin output ---
	$skin = bioship_file_hierarchy( 'file', 'skin.php', $vthemedirs['core'] );
	if ( $skin ) {
		include $skin;
	}

	// 2.1.2: fix to not exit for inline admin/login styles
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		exit;
	}
 }
}

// -------------------------
// Print Admin Styles Inline
// -------------------------
// 1.8.5: added for printing inline admin-only header/footer styles
if ( !function_exists( 'bioship_skin_dynamic_admin_css_inline' ) ) {
 function bioship_skin_dynamic_admin_css_inline() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	echo '<style id="dynamic-admin-styles">';
 	bioship_skin_dynamic_admin_css();
 	echo '</style>' . PHP_EOL;
 }
}

// -------------------------
// Print Login Styles Inline
// -------------------------
// 1.8.5: added for printing inline login-only styles
if ( !function_exists( 'bioship_skin_dynamic_login_css_inline' ) ) {
 function bioship_skin_dynamic_login_css_inline() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
 	echo '<style id="dynamic-login-styles">';
 	bioship_skin_dynamic_admin_css( true );
 	echo '</style>' . PHP_EOL;
 }
}
