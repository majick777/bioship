<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === Custom Value Filters ===
 * - for advanced customizing -
 * - and conditional overrides -
 *
**/

// Extensive List and EXAMPLES of all known available value filters can be found here.
// (*except* for element position filtering - for that see layout.php list instead.)

// Note: Many examples have *not* been tested thoroughly. This is a filter guide so you
// know what is available and can test custom changes for yourself.

// Update Reminder Note:
// Remember, if you update BioShip Parent Theme you will find the latest version of these
// examples and guide here: /wp-content/themes/bioship/child/filters.php
// (!Do not copy that over your *existing* Child Theme filters.php and lose your work!)

// Fresh Installation Notes:
// Copy this file to your Child Theme directory (already done for one-click installs)
// and modify the value filters with your custom conditional logic as desired.

// Better Practice:
// Selectively copy desired filter functions to your Child Theme functions.php and test
// them there. Then remove or rename this file to .txt in your Child Theme directory
// (cleaner) so it is not processed (it does nothing in this original form anyway.)

// *NOT* Pluggable:
// So you know if there is a possible duplicate filter, these examples have NOT been
// made pluggable. Declare them only once or if needs be change the function name.

// LEGEND: commented EXAMPLE CODE marked with hashes #, commented INFO marked with slashes //


// ====================
// === Filter Index ===
// - Options -
// - Hybrid -
// - Skull -
// - Thumbnails -
// - Skeleton -
// - Sidebars -
// - Meta -
// - Muscle -
// - Skin -
// - Admin -
// -------------------
// -- Integrations --
// - QuickSave -
// - ThemeMyLogin -
// - WooCommerce -
// - TGM Plugins -
// - Open Graph -
// ===================

// /====================
// === Theme Options ===
// ====================/
// options_theme_options
// optionsframework_menu
// options_submenu_position
// options_admin_menu_title
// options_admin_menu_position
// options_admin_menu_icon
// options_themepage_styles
// options_images_url
// options_font_stacks
// options_title_fonts
// /=======================/

// /= Available Theme Options =/
// -----------------------------
add_filter('options_theme_options','muscle_options_theme_options');
function muscle_options_theme_options($voptions) {
	// modify available theme options array conditionally
	return $voptions;
}

// /= Actual Theme Options =/
// --------------------------
// TODO: add this filter?


// /= Change Appearance Submenu =/
// -------------------------------
// (for Options Framework only)
// note: this is an internal filter and cannot be renamed
# add_filter('optionsframework_menu','muscle_options_submenu',1);
function muscle_options_submenu($vmenu) {

	// Default
	//	$vmenu = array(
	//		'mode' => 'submenu',
	//		'page_title' => __( 'Theme Options', 'bioship'),
	//		'menu_title' => __('Theme Options', 'bioship'),
	//		'capability' => 'edit_theme_options',
	//		'menu_slug' => 'options-framework',
    //      'parent_slug' => 'themes.php',
	// );

	// Example: Change "Theme Options" submenu title to Child Theme name
	# if (is_child_theme()) {
	#	global $vtheme;
	#	if (get_bloginfo('version') < '3.4') {$vchildtheme = get_theme_data(get_stylesheet_directory($vtheme).'/style.css');}
	#	else {$vchildtheme = wp_get_theme($vtheme);}
	#	if ($vchildtheme['Name'] != '') {$vmenu['page_title'] = $vmenu['menu_title'] = $vchildtheme['Name'].' Options';}
	# }

	// Note: Position must be changed separately (as not available via add_submenu_page)
	// Warning: Do NOT change the menu_slug value! just in case:
	$vmenu['menu_slug'] = "options-framework";
	return $vmenu;
}

// /= Change Submenu Position =/
// -----------------------------
add_filter('options_submenu_position','muscle_options_submenu_position');
function muscle_options_submenu_position($vposition) {
	// default 1 (first in Appearance menu)
	# $vposition = '6'; // moves below Themes in Appearance menu
	return $vposition;
}

// /= Add Main Theme Menu =/
// -------------------------
// (for Options Framework only - as off by default)
add_filter('muscle_theme_options_menu','muscle_add_theme_options_menu');
function muscle_add_theme_options_menu($vmenu) {

	// ref: https://codex.wordpress.org/Function_Reference/add_menu_page
	// note: by default, Options Framework does NOT add this page
	// default
	//	$vmenu = array(
	//		'mode' => 'menu',
	//		'page_title' => __( 'Theme Options', 'bioship'),
	//		'menu_title' => __('Theme Options', 'bioship'),
	//		'capability' => 'edit_theme_options',
	//		'parent_slug' => 'themes.php',
	//		'icon_url' => 'dashicons-admin-generic',
	//		'position' => '59'
	// );

	// change the menu icon URL
	# global $vthemedirs;
	# $vicon = bioship_file_hierarchy('url','theme-options-icon.png',$vthemedirs['img']);
	# if ($vicon) {$vmenu['icon_url'] = $vicon;}

	// change the menu position
	# if (is_admin()) {$vposition = '61';} 			// after 'Appearance' for admin
	# if (is_network_admin()) {$vposition = '29';} 	// above 'Updates' for network admin

	return $vmenu;
}

// /= Options Admin Menu Title =/
// ------------------------------
// 1.8.0: for Titan Framework Main Menu
add_filter('options_admin_menu_title','muscle_admin_menu_title');
function muscle_admin_menu_title($vtitle) {
	// default menu title is 'BioShip Options'
	# $vtitle = 'Theme Options';
	return $vtitle;
}

// /= Options Admin Menu Title =/
// ------------------------------
// 1.8.0: for Titan Framework Main Menu
add_filter('options_admin_menu_position','muscle_admin_menu_position');
function muscle_admin_menu_position($vposition) {
	// default position is 60
	# $vposition = 5;
	return $vposition;
}

// /= Options Admin Menu Icon =/
// -----------------------------
// 1.8.0: for Titan Framework Main Menu
// ref: http://calebserna.com/dashicons-cheatsheet/
add_filter('options_admin_menu_icon','muscle_admin_menu_icon');
function muscle_admin_menu_icon($vicon) {
	// default menu icon is 'dashicon-welcome-view-site'
	# $vicon = 'dashicon-admin-appearance';
	return $vicon;
}

// /= Theme Options Styling =/
// ---------------------------
add_filter('options_themepage_styles','muscle_options_themepage_styles');
function muscle_options_themepage_styles($vstyles) {
	// add some extra styling to the Theme Options page
	# $vstyles .= ".filterbutton {font-size:12pt; font-weight:bold;}";
	return $vstyles;
}

// /= Options Page Images Path =/
// ------------------------------
// for changing image radio buttons path
add_filter('options_images_url','muscle_options_images_url');
function muscle_options_images_url($vpath) {
	// eg. change to child theme images directory
	# $vpath = get_stylesheet_directory_uri.'/images/';
	return $vpath;
}

// /= Font Stack Options =/
// ------------------------
add_filter('options_font_stacks','muscle_font_stacks');
function muscle_font_stacks($vfonts) {
	// Here you can replace existing font stacks or add your own
	// Format: $vfonts['font1,font2,font3'] = 'DisplayName 1, Display Name 2, Display Name 3';
	// eg1. (add):
	# $vfonts['"PT Sans",helvetica,arial'] = 'PT Sans, Helvetica, Arial';
	// eg2. (replace):
	# unset($vfonts['helvetica, arial, "Nimbus Sans L", sans-serif']);
	# $vfonts['"Open Sans", helvetica, arial, "Nimbus Sans L", sans-serif'] = 'Open Sans, Helvetica, Arial, Nimbus Sans L, sans-serif';
	return $vfonts;
}

// /= Title Font Options =/
// ------------------------
add_filter('options_title_fonts','muscle_title_fonts');
function muscle_title_fonts($vfonts) {
	// Here you could add some title font options to the selector
	// Loaded from the Google Fonts API: http://www.google.com/fonts
	// remember to use + for spaces as in the following example
	# $vfonts['Special+Font'] = 'Special Font';
	return $vfonts;
}

// /= Theme Recommendations =/
// ---------------------------
add_filter('options_recommendations','muscle_options_recommendations');
function muscle_options_recommendations($vrecommend) {
	// Add to the recommendations for this theme
	# $vrecommend .= '<a href="" target=_blank>Another awesome plugin</a>.';
	// or even add a panel via a shortcode
	# $vpanel = do_shortcode('[feed-panel]')
	# return $vpanel.$vrecommend;
	return $vrecommend;
}

// /=================
// === Customizer ===
// =================/
// options_customizer_extra_styles
// options_customizer_logo_image
// options_customizer_description
// options_customizer_color_accent
// options_customizer_color_back
// options_customizer_panel_width
// /========================/
// TODO: add filter examples for Customizer Styling via Kirki

// /= Customizer Extra Styles =/
add_filter('options_customizer_extra_styles','muscle_customizer_extra_styles');
function muscle_customizer_extra_styles($vstyles) {
	# TODO: example
	return $vstyles;
}

// /= Customizer Logo Image =/
add_filter('options_customizer_logo_image','muscle_customizer_logo_image');
function muscle_customizer_logo_image($vimage) {
	# TODO: example
	return $vimage;
}

// /= Customizer Description =/
add_filter('options_customizer_description','muscle_customizer_description');
function muscle_customizer_description($vdescription) {
	# TODO: example
	return $vdescription;
}

// /= Customizer Colour Accent =/
add_filter('options_customizer_color_accent','muscle_customizer_color_accent');
function muscle_customizer_color_accent($vcolor) {
	# TODO: example
	return $vcolor;
}

// /= Customizer Sidebar Colour =/
add_filter('options_customizer_color_back','muscle_customizer_color_back');
function muscle_customizer_color_back($vcolor) {
	# TODO: example
	return $vcolor;
}

// /= Customizer Panel Width =/
add_filter('options_customizer_panel_width','muscle_customizer_panel_width');
function muscle_customizer_panel_width($vwidth) {
	# TODO: example
	return $vwidth;
}


// /=============
// === HYBRID ===
// =============/
// hybrid_content_template_hierarchy
// skeleton_content_template_directory
// skeleton_archive_template_directory
// /============/
// ...see Hybrid docs for more available filters!

// /= Content Template Hierarchy =/
// --------------------------------
// for advanced content template control
// see hybrid_get_content_template in hybrid/functions/template.php
add_filter('hybrid_content_template_hierarchy','muscle_content_template_hierarchy');
function muscle_content_template_hierarchy($vtemplates) {
	// below is the default hybrid content template hierarchy for reference

	# $vtemplates = array(); // optionally clear the hierarchy
	# $vposttype = get_post_type(get_the_ID());

	# if ($vposttype === 'attachment') {
	#	$vmimetype = get_post_mime_type();
	#	list( $type, $subtype ) = false !== strpos( $vmimetype, '/' ) ? explode( '/', $vmimetype ) : array($vmimetype, '' );
	#	$vtemplates[] = "content-attachment-{$vtype}.php";
	#	$vtemplates[] = "content/attachment-{$vtype}.php";
	# }

	# if (post_type_supports($vposttype,'post-formats')) {
	#	$vpostformat = get_post_format() ? get_post_format() : 'standard';
	#	$vtemplates[] = 'content-{$vposttype}-{$vpostformat}.php';
	#	$vtemplates[] = 'content/{$vposttype}-{$vpostformat}.php';
	#	$vtemplates[] = 'content-{$vpostformat}.php';
	#	$vtemplates[] = 'content/{$vpostformat}.php';
	# }

	# $vtemplates[] = 'content-{$vposttype}.php';
	# $vtemplates[] = 'content/{$vposttype}.php';
	# $vtemplates[] = 'content.php';
	# $vtemplates[] = 'content/content.php';

	return $vtemplates;
}

// /= Content Template Directory =/
// --------------------------------
// 1.9.5: added this filter for changing content template location
add_filter('skeleton_content_template_directory','muscle_content_template_directory');
function muscle_content_template_directory($vcontentdir) {
	// Modify the /content/ location for content templates, default is 'content'
	# $vcontentdir = 'singular';
	return $vcontentdir;
}

// /= Archive Template Directory =/
// --------------------------------
// 1.9.5: added this filter for optional archive template location
add_filter('skeleton_archive_template_directory','muscle_archive_template_directory');
function muscle_archive_template_directory($varchivedir) {
	// Modify the optional /archive/ location for archive templates, default is 'archive'
	# $varchivedir = 'multlple';
	return $varchivedir;
}

// /= Loop Description =/
// ----------------------
// 1.8.0: deprecated usage in favour of WP get_the_archive_description
// see hybrid_get_loop_description in hybrid/functions/template-general.php
// add_filter('hybrid_loop_description','muscle_loop_description_filter');
// function muscle_loop_description_filter($vdescription) {
//	// Modify the post loop description with conditional logic
//	# if (is_404()) {$vdescription = 'Whoops. Something went wrong..!';}
//	return $vdescription;
// }

// /============
// === SKULL ===
// ============/
// skeleton_theme_debug
// skeleton_title_tag_support
// skeleton_page_title_tag
// skeleton_generator_meta
// skeleton_mobile_metas
// skeleton_apple_icons
// skeleton_startup_images
// skeleton_google_jquery_version
// /===========/
// note: browser and mobile detection filters:
// muscle_styles_* (auto-added)

// /= Theme Debug =/
// -----------------
add_filter('skeleton_theme_debug','muscle_theme_debug');
function muscle_theme_debug($vdebug) {
	// whether to output theme debug info, off by default
	// eg. echo theme debug info on a particular page
	# if (is_page('1')) {
	return $vdebug;
}

// /= Title Tag Theme Support =/
// -----------------------------
// 1.8.5: added title-tag theme support
add_filter('skeleton_title_tag_support','muscle_title_tag_support');
function muscle_title_tag_support($vsupport) {
	// you can turn off theme title-tag support
	// and fallback to use of filtered wp_title
	# $vsupport = 0;
	return $vsupport;
}

// /= Page Title Tag Filter =/
// ---------------------------
// 1.8.5: for wp_title fallback only
// You could remove the title filter eg. if your SEO plugin handles it
# remove_filter('wp_title','bioship_wp_title',10,2);
// or change the title
add_filter('skeleton_page_title_tag','muscle_page_title_tag');
function muscle_page_title_tag($vtitle) {
	// Modify the existing title tag here, eg. for pages
	# if (is_page()) {
	#	$vposttitle = get_the_title();
	#   $vblogname = esc_attr(get_bloginfo('name','display'));
	#	$vtitle = $vposttitle." | ".$vblogname;
	# }
	return $vtitle;
}

// /= Mobile Specific Metas =/
// ---------------------------
add_filter('skeleton_mobile_metas','muscle_mobile_metas');
function muscle_mobile_metas($vmobilemetas) {
	// Add your own mobile meta tags
	# $vmobilemetas .= '<meta name="HandheldFriendly" content="True">';
	return $vmobilemetas;
}

// /= Change the Meta Generator Tag =/
// -----------------------------------
add_filter('skeleton_generator_meta','muscle_generator_meta');
function muscle_generator_meta($vmeta) {
	// by default is removed entirely
	# $vmeta = 'Custom Theme';
	return $vmeta;
}

// /= Apple Touch Icon Sizes =/
// ----------------------------
// https://mathiasbynens.be/notes/touch-icons
# remove_filter('skeleton_apple_icons','skeleton_apple_icon_sizes');
add_filter('skeleton_apple_icons','muscle_apple_icon_sizes');
function muscle_apple_icon_sizes($vsizes) {
	// Output your own list of sizes and icon URLs (HTML)
	# $vsizes = '<link rel="...">'; // etc.
	return $vsizes;
}

// /= Apple Startup Images =/
// --------------------------
# remove_filter('skeleton_startup_images','skeleton_apple_startup_images');
add_filter('skeleton_startup_images','muscle_apple_startup_images');
function muscle_apple_startup_images($vimages) {
	// Output your own list of startup images (HTML)
	# $vimages = '<link rel="...">'; // etc.
	return $vimages;
}


// /= Custom Browser Detection Styling =/
// --------------------------------------
// Note: These filters are auto-added so there is no need to 'add_filter'
// Any existing relevent theme option values are passed to these filters.

// Works much better with PHP Browser Detection, but is not required.
// https://wordpress.org/plugins/php-browser-detection/
// note: uses browscap.ini http://browscap.org/
# $browser_version = get_browser_version();
# $browser_info = php_browser_info();

// IE (is_ie)
function muscle_custom_styles_ie($vstyles) {
	# insert other conditional check here eg.
	# if (get_browser_version() < 7) {
	#	$vstyles .= '#updateyourbrowermessage {display:block;}';
	# }
	return $vstyles;
}
// Firefox (is_firefox)
function muscle_styles_firefox($vstyles) {
	# insert other conditional check here eg.
	return $vstyles;
}
// Safari (is_safari)
function muscle_styles_safari($vstyles) {
	# insert other conditional check here
	return $vstyles;
}
// Chrome (is_chrome)
function muscle_styles_chrome($vstyles) {
	# insert other conditional check here
	return $vstyles;
}
// Opera (is_opera)
function muscle_styles_opera($vstyles) {
	# insert other conditional check here
	return $vstyles;
}
// Lynx
function muscle_styles_lynx($vstyles) {
	# insert other conditional check here eg.
	return $vstyles;
}
// Netscape
function muscle_styles_netscape($vstyles) {
	# insert other conditional check here eg.
	return $vstyles;
}

// /= Custom Mobile Detection Styling =/
// -------------------------------------
// Note: Dynamic Skin CSS must be on for this to work as added via skin.php
// These mobile detection filters require PHP Browser Detection plugin
// https://wordpress.org/plugins/php-browser-detection/
// Note: These filters are auto-added *when PHP Browser Detection plugin is active*
// ...so there is no need for an add_filter line for these functions.
// Any existing relevent theme option values are passed to these filters.

// Desktop only (is_desktop)
function muscle_styles_desktop($vstyles) {
	# insert other conditional check here
	return $vstyles;
}

// Mobile only (is_mobile)
function muscle_styles_mobile($vstyles) {
	# insert other conditional check here
	return $vstyles;
}

// Tablet only (is_tablet)
function muscle_styles_tablet($vstyles) {
	# insert other conditional check here
	return $vstyles;
}

// Desktop only (is_iphone)
function muscle_styles_iphone($vstyles) {
	// insert other conditional check here eg.
	# is_phone($version)) {$vstyles .= '';}
	return $vstyles;
}

// Ipad only styles (is_ipad)
function muscle_styles_ipad($vstyles) {
	// insert other conditional check here eg.
	# is_pad($version)) {$vstyles .= '';}
	return $vstyles;
}

// Ipod only styles (is_ipod)
function muscle_styles_ipod($vstyles) {
	// insert other conditional check here eg.
	# eg. is_pod($version)) {$vstyles .= '';}
	return $vstyles;
}


// /= Google CDN jQuery Version =/
// -------------------------------
add_filter('skeleton_google_jquery_version','muscle_google_jquery_version');
function muscle_google_jquery_version($vversion) {
	// use a different version of jquery
	// (matching current WordPress version used by default)
	# $vversion = '1.7.2';
	return $vversion;
}


// /=================
// === Thumbnails ===
// =================/
// skeleton_thumbnail_width
// skeleton_thumbnail_height
// skeleton_image_sizes
// skeleton_post_thumbnail_size
// skeleton_list_thumbnail_size
// skeleton_thumbnail_wrapper_classes
// skeleton_thumbnail_classes
// skeleton_thumbnail_override
// /================/

// /= Default Thumbnail Width =/
// -----------------------------
add_filter('skeleton_thumbnail_width','muscle_thumbnail_width');
function muscle_thumbnail_width($vwidth) {
	# $vwidth = 200;
	return $vwidth;
}

// /= Default Thumbnail Height =/
// ------------------------------
add_filter('skeleton_thumbnail_height','muscle_thumbnail_height');
function muscle_thumbnail_height($vheight) {
	# $vheight = 200;
	return $vheight;
}


// /= Media Image Sizes =/
// -----------------------
add_filter('skeleton_image_sizes','muscle_image_sizes');
function muscle_image_sizes($vimagesizes) {

	// Ref: Default image sizes
	// $vimagesizes[0] = array('name' => 'squared150', 'width' => 150, 'height' => 150, 'crop' => true);
	// $vimagesizes[1] = array('name' => 'squared250', 'width' => 250, 'height' => 250, 'crop' => true);
	// $vimagesizes[2] = array('name' => 'video43', 'width' => 320, 'height' => 240, 'crop' => true);
	// $vimagesizes[3] = array('name' => 'video169', 'width' => 320, 'height' => 180, 'crop' => true);

	// eg. Add an image size (here 600x600)
	# $vimagesizes[] = array('name' => 'squared600', 'width' => 600, 'height' => 600, 'crop' => true);

	// eg. Change cropping defaults for an image size
	# foreach ($vimagesizes as $vkey => $vimagesize) {
	#	if ($vimagesize['name'] == 'squared150') {
	#			$vimagesizes[$vkey]['crop'] = array('top','left');
	#	}
	# }

	return $vimagesizes;
}

// /= Post List Thumbnail Size =/
// ------------------------------
add_filter('skeleton_list_thumbnail_size','muscle_list_thumbnail_size');
function muscle_list_thumbnail_size($vsize) {
	// eg. Set to 4:3 video size for custom post type of 'video'
	# if (is_post_type_archive('video')) {$vsize = 'video43';}
	// eg. Set to squared150 for search pages
	# if (is_search()) {$vsize = 'squared150';}
	return $vsize;
}

// /= Post List Thumbnail Alignment =/
// -----------------------------------
add_filter('skeleton_list_thumbnail_align','muscle_list_thumbnail_align');
function muscle_list_thumbnail_align($valign) {
	// change the thumbnail list alignment
	# if (is_post_type_archive('video')) {$valign = 'alignright';}
	return $valign;
}

// /= Post Thumbnail Size =/
// -------------------------
// note: also used for page featured images
// filter exists at priority 1 for theme options perpost metabox override
add_filter('skeleton_post_thumbnail_size','muscle_post_thumbnail_size',10);
function muscle_post_thumbnail_size($vsize) {
	// eg. Set to full size for the 'images' category
	# if (is_category('images')) {$vsize = 'full';}
	// eg. Set to full size for custom post type of 'image'
	# if (get_post_type(get_the_ID()) == 'image') {$vsize = 'full';}
	# OR alternatively
	# if (is_singular('image')) {$vsize = 'full';}
	return $vsize;
}

// /= Post Thumbnail Alignment =/
// ------------------------------
add_filter('skeleton_post_thumbnail_align','muscle_post_thumbnail_align');
function muscle_post_thumbnail_align($valign) {
	// change the post thumbnail alignment
	# if (get_post_type(get_the_ID()) == 'video')) {$valign = 'alignright';}
	return $valign;
}

// /= Thumbnail Wrapper Classes =/
// -------------------------------
add_filter('skeleton_thumbnail_wrapper_classes','muscle_thumbnail_wrapper_classes');
function muscle_thumbnail_wrapper_classes($vclasses) {
	// eg. change the default alignment to right for a particular post ID
	# if (get_the_ID() == '100') {$vclasses = str_replace('alignleft','alignright',$vclasses);}
	return $vclasses;
}

// /= Thumbnail Image Classes =/
// -----------------------------
add_filter('skeleton_thumbnail_classes','muscle_thumbnail_classes');
function muscle_thumbnail_classes($vclasses) {
	// eg. add an additional class for all archive type pages
	# if (is_archive()) {$vclasses .= ' archive-thumbnail';}
	return $vclasses;
}

// /= Thumbnail Override =/
// ------------------------
add_filter('skeleton_thumbnail_override','muscle_thumbnail_override');
function muscle_thumbnail_override($vhtml) {
	// completely override the outputted thumbnail HTML
	// eg. do not show thumbnails at all on search pages
	# if (is_search()) {$vhtml = '';}
	// eg. set a different thumbnail size for the first thumbnail
	// if on the homepage or frontpage, here medium (300x300)
	# if ( (is_home()) || (is_front_page()) ) {
	# 	if (strstr($vhtml,'<!-- .thumbnail1 -->')) {
	#		$vhtml = skeleton_get_thumbnail(get_the_ID(),get_post_type(get_the_ID()),'medium');
	# 	}
	# }
	return $vhtml;
}


// /===============
// === SKELETON ===
// ===============/
// skeleton_html_comments
// skeleton_layout_width
// skeleton_grid_columns
// skeleton_content_grid_columns
// skeleton_container_classes
// skeleton_header_classes
// skeleton_blog_display_name
// skeleton_blog_description
// skeleton_header_logo_url
// skeleton_header_logo_override
// skeleton_logo_resize
// skeleton_header_menu_settings
// skeleton_header_html_extras
// skeleton_navigation_hide
// skeleton_content_classes
// skeleton_content_columns
// skeleton_content_columns_override
// skeleton_content_width
// skeleton_content_padding_width
// skeleton_footer_classes
// skeleton_footer_menu_settings
// skeleton_footer_html_extras
// /==============/

// /= HTML Comments =/
// -------------------
// 1.8.5: added this filter
add_filter('skeleton_html_comments','muscle_html_comments');
function muscle_html_comments($vhtmlcomments) {
	// show HTML element comment wrappers on pages only
	# if (is_page()) {$vhtmlcomments = 1;}
	return $vhtmlcomments;
}

// /= Maximum Layout Width =/
// --------------------------
add_filter('skeleton_layout_width','muscle_layout_width');
function muscle_layout_width($vwidth) {
	// maximum width to scale up to via media queries
	// 1.5.0: original layout width maximums were 960, 1140 or 1200
	// but now this can be set to anything in theme options
	# $vwidth = 1200;
	return $vwidth;
}

// /= Grid Columns Override =/
// ---------------------------
// 1.8.5: added override in grid.php
add_filter('skeleton_grid_columns','muscle_grid_columns');
function muscle_grid_columns($vcolumns) {
	// set different total layout grid columns for a page
	// (you would want to filter sidebar and content columns also)
	// valid values 12,16,20,24 (word or number)
	# $vcolumns = '20';
	return $vcolumns;
}

// /= Content Grid Columns Override =/
// -----------------------------------
// 1.9.5: added this option and filter
add_filter('skeleton_content_grid_columns','muscle_content_grid_columns');
function muscle_content_grid_columns($vcolumns) {
	// set different total content grid columns, eg. for blog page
	// valid values 12,16,20,24 (word or number)
	# if (is_home()) ($vcolumns = '20';}
	return $vcolumns;
}

// /= Wrap Container Div Classes =/
// --------------------------------
add_filter('skeleton_container_classes','muscle_container_classes');
function muscle_container_classes($vclasses) {
	// default is an array of container classes based on grid
	// you can add extra classes, eg.
	# if (is_page()) {$vclasses[] = 'mycontainerclass';}
	return $vclasses;
}

// /= Header Div Classes =/
// ------------------------
add_filter('skeleton_header_classes','muscle_header_classes');
function muscle_header_classes($vclasses) {
	// default is an array of header classes based on grid
	// you can add extra classes, eg.
	# if (is_page()) {$vclasses[] = 'myheaderclass';}
	return $vclasses;
}

// /= Blog Display Name =/
// -----------------------
// 1.8.5: (currently only applies to header blog title output)
add_filter('skeleton_blog_display_name','muscle_blog_display_name');
function muscle_blog_display_name($vname) {
	// change the blog title display, eg. recapitalize
	# $vname = "Bio Ship";
	return $vname;
}

// /= Blog Description =/
// ----------------------
// 1.8.5: (currently only applies to header description output)
add_filter('skeleton_blog_description','muscle_blog_description');
function muscle_blog_description($vdescription) {
	// change the blog description displayed, eg. split up with line breaks
	# $vdescription = str_replace(' ','<br>',$vdescription);
	return $vdescription;
}

// /= Header Logo URL =/
// ---------------------
add_filter('skeleton_header_logo_url','muscle_header_logo_url');
function muscle_header_logo_url($vlogo) {
	// You could override the logo per page here eg.
	# if (is_front_page()) {$vlogo = site_url().'/images/frontpagelogo.png';}
	# if (is_home()) {$vlogo = site_url().'/images/blogpagelogo.png';}
	return $vlogo;
}

// /= Header Logo Override =/
// --------------------------
add_filter('skeleton_header_logo_override','muscle_header_logo_override');
function muscle_header_logo_override($vlogo) {
	// You could override the entire HTML logo output eg.
	# if (is_front_page()) {$vlogo = 'http://url.to/frontpagelogo.png';}
	# if (is_home()) {$vlogo = 'http://url.to/blogpagelogo.png';}
	return $vlogo;
}

// /= Header Logo Resize =/
// ------------------------
add_filter('skeleton_logo_resize','muscle_logo_resize_override');
function muscle_logo_resize_override($vresize) {
	// You could turn logo resize on/off for specific page eg.
	# if (is_front_page()) {$vresize = 0;}
	return $vresize;
}

// /= Header Menu Settings =/
// --------------------------
add_filter('skeleton_header_menu_settings','muscle_header_menu_settings');
function muscle_header_menu_settings($vsettings) {
	// change the settings for the header menu display
	return $vsettings;
}

// /= Header Extras HTML Override =/
// ---------------------------------
add_filter('skeleton_header_html_extras','muscle_header_extras');
function muscle_header_extras($vextras) {
	// You can set header extra text here as HTML, eg.
	# $vextras = '<div id="welcome">Welcome!</div>';
	// or maybe add shortcode output, eg.
	# $vextras .= do_shortcode('[header-html]');
	return $vextras;
}

// /= Navigation Hide Override =/
// ------------------------------
// 1.5.5: allow override of navigation hide
add_filter('skeleton_navigation_hide','muscle_navigation_hide');
function muscle_navigation_hide($vhidenav) {
	// hide main navigation menu for a specific category
	# if (is_category('articles')) {$vhidenav = '1';}
	return $vhidenav;
}

// /= Content Classes =/
// ---------------------
add_filter('skeleton_content_classes','muscle_content_classes');
function muscle_content_classes($vclasses) {
	// add CSS classes to the #content div
	# if (is_page('home')) {$vclasses = 'contentclass';}
	return $vclasses;
}

// /= Content Columns =/
// ---------------------
add_filter('skeleton_content_columns','muscle_content_columns');
function muscle_content_columns($vcolumns) {
	// Modify the content width value (in columns value as a word)
	// (important: differs from $content_width variable! see functions.php)
	# $vcolumns = 'ten';
	return $vcolumns;
}

// /= Content Columns Override =/
// ------------------------------
// prefer above filter, takes into account sidebar columns
// this one can override regardless of that result
add_filter('skeleton_content_columns_override','muscle_content_columns_override');
function muscle_content_columns_override($vcolumns) {
	// eg. override as full width for a specific page
	# if (is_page(1)) {$vcolumns = 'sixteen';}
	return $vcolumns;
}

// /= Content Width Override =/
// ----------------------------
add_filter('skeleton_content_width','muscle_content_width');
function muscle_content_width($vwidth) {
	// the actual content width in pixels
	// default is calculated based on content columns and content padding
	return $vwidth;
}

// /= Content Padding Width Override =/
// ------------------------------------
add_filter('skeleton_content_padding_width','muscle_content_padding_width');
function muscle_content_padding_width($vwidth) {
	// used to calculate actual content width in pixels
	// default is calculated from theme options content padding value
	return $vwidth;
}

// /= Footer Div Classes =/
// ------------------------
add_filter('skeleton_footer_classes','muscle_footer_classes');
function muscle_footer_classes($vclasses) {
	// default is an array of footer classes based on grid
	// you can add extra classes, eg.
	# if (is_page()) {$vclasses[] = 'myfooterclass';}
	return $vclasses;
}

// /= Footer Menu Settings =/
// --------------------------
add_filter('skeleton_footer_menu_settings','muscle_footer_menu_settings');
function muscle_footer_menu_settings($vsettings) {
	// change the settings for the footer menu display
	return $vsettings;
}

// /= Footer Extras HTML Override =/
// --------------------------------
add_filter('skeleton_footer_html_extras','muscle_footer_extras');
function muscle_footer_extras($vextras) {
	// You can set footer extra text as HTML, eg. copyright year
	# $vextras = '<div id="copyright">Copyright '.date('Y').'</div>';
	// or maybe add shortcode output, eg.
	# $vextras .= do_shortcode('[footer-html]');
	return $vextras;
}


// /===============
// === SIDEBARS ===
// ===============/
// skeleton_sidebar_widget_wrappers
// skeleton_sidebar_settings
// skeleton_sidebar_layout_override
// skeleton_header_sidebar
// skeleton_sidebar_position
// skeleton_sidebar_mode
// skeleton_sidebar_columns
// skeleton_sidebar_hide
// skeleton_subsidebar_position
// skeleton_subsidebar_mode
// skeleton_subsidebar_columns
// skeleton_subsidebar_hide
// skeleton_footer_sidebar
// /==============/


// /= Sidebar Widget Wrappers =/
// -----------------------------
// 1.9.0: added this filter
add_filter('skeleton_sidebar_widget_wrappers','muscle_sidebar_widget_wrappers');
function muscle_sidebar_widget_wrappers($vwrappers) {
	// this sets the widget wrappers for all sidebars
	// eg. change widget titles from <h3> to <h4> headings
	# $vwrappers['beforetitle'] = '<h4 class="widget-title">';
	# $vwrappers['aftertitle'] = '</h4>';
	return $vwrappers;
}

// /= Sidebar Labels Override =/
// -----------------------------
add_filter('skeleton_sidebar_settings','muscle_sidebar_settings');
function muscle_sidebar_settings($vsettings) {
	// can change the registered sidebar label array
	// for individual sidebar labels and wrappers by sidebar id
	// eg. add a title class to a particular sidebar type
	# foreach ($vsettings as $vid => $vsetting) {
	#	if ($vid == 'search') {
	#		$vsettings[$vid]['beforetitle'] = '<h3 class="widget-title search-widget-title">';
	#	}
	# }
	return $vsettings;
}

// /= Sidebar Layout Override =/
// -----------------------------
add_filter('skeleton_sidebar_layout_override','muscle_sidebar_layout_override');
function muscle_sidebar_layout_override($vsidebars) {
	# extensive examples and explanation needed for this in guide
	return $vsidebars;
}


// /= Change the Header Sidebar Template =/
// ----------------------------------------
add_filter('skeleton_header_sidebar','muscle_header_sidebar_template');
function muscle_header_sidebar_template($vtemplate) {
	// eg, look for header-page.php sidebar template for pages
	// (note: template file and corresponding widget will need to be created)
	# if (get_post_type(get_the_ID()) == 'page') {return 'header-page';}
	return $vtemplate;
}

// /= Sidebar Position =/
// ----------------------
// 1.5.0: added this filter
// Valid Values: left, right
add_filter('skeleton_sidebar_position','muscle_sidebar_position');
function muscle_sidebar_position($vposition) {
	// eg. swap the from left to right for pages
	# if ( (is_page()) && ($vposition == 'left') ) {$vposition = 'right';}
	return $vposition;
}

// /= Sidebar Mode =/
// ------------------
// 1.8.5: added missing example
// Valid Values: off, postsonly, pagesonly, dual, unified
add_filter('skeleton_sidebar_mode','muscle_sidebar_mode');
function muscle_sidebar_mode($vmode) {

	return $vmode;
}

// /= Sidebar Column Width =/
// --------------------------
// 1.5.0: moved here from old skeleton_set_sidebarwidth function
add_filter('skeleton_sidebar_columns', 'muscle_sidebar_columns');
function muscle_sidebar_columns($vcolumns) {
	// eg. change the main sidebar column width for a particlur page
	# if (is_page('about')) {return 'three';}
	// eg. change the column width for video archive pages
	# if (is_post_type_archive('video')) {return 'two';}
	return $vcolumns;
}

// /= Sidebar Class Filter =/
// --------------------------
// 1.8.0: allow sidebar classes to be filtered
add_filter('skeleton_sidebar_classes', 'muscle_sidebar_classes');
function muscle_sidebar_classes($vclasses) {
	// eg. add an alpha or omega class to fix margins
	# $vclasses[] = 'alpha';
	return $vclasses;
}

// /= Sidebar Hide =/
// ------------------
// 1.5.5: allow the sidebar to be hidden conditionally
// (perpost meta is checked for this also)
add_filter('skeleton_sidebar_hide', 'muscle_sidebar_hide');
function muscle_sidebar_hide($vhidesidebar) {
	// eg. hide the sidebar for a specific category
	# is (is_category('articles')) {$vhidesidebar = true;}
	return $vhidesidebar;
}

// /= Sidebar Output =/
// --------------------
// 1.9.5: allow the sidebar to be removed conditionally
add_filter('skeleton_sidebar_output', 'muscle_sidebar_output');
function muscle_sidebar_output($voutputsidebar) {
	// eg. remove the sidebar for a specific category
	# is (is_category('articles')) {$voutputsidebar = false;}
	return $voutputsidebar;
}

// /= Subsidebar Position =/
// -------------------------
// 1.5.0: added this filter
// Valid Values: internal, external, opposite
add_filter('skeleton_subsidebar_position','muscle_subsidebar_position');
function muscle_subsidebar_position($vposition) {
	// eg. swap the from internal to opposite for pages
	# if ( (is_page()) && ($vposition == 'internal') ) {$vposition = 'opposite';}
	return $vposition;
}

// /= SubSidebar Mode =/
// ------------------
// 1.8.5: added missing example
// Valid Values: off, postsonly, pagesonly, dual, unified
add_filter('skeleton_subsidebar_mode','muscle_subsidebar_mode');
function muscle_subsidebar_mode($vmode) {

	return $vmode;
}

// /= SubSidebar Column Width =/
// -----------------------------
// 1.5.0: activated this filter
add_filter('skeleton_subsidebar_columns', 'muscle_subsidebar_columns');
function muscle_subsidebar_columns($vcolumns) {
	// eg. change the subsidebar column width for a particlur page
	# if (is_page('about')) {return 'three';}
	// eg. change the column width for video archive pages
	# if (is_post_type_archive('video')) {return 'two';}
	return $vcolumns;
}

// = SubSidebar Class Filter =
// ---------------------------
// 1.8.0: allow subsidebar classes to be filtered
add_filter('skeleton_subsidebar_classes', 'muscle_subsidebar_classes');
function muscle_subsidebar_classes($vclasses) {
	// eg. add an alpha or omega class to fix margins
	# $vclasses[] = 'alpha';
	return $vclasses;
}

// /= SubSidebar Hide =/
// ---------------------
// 1.5.5: allow the subsidebar to be hidden conditionally
// (perpost meta is checked for this also)
add_filter('skeleton_subsidebar_hide', 'muscle_subsidebar_hide');
function muscle_subsidebar_hide($vhidesubsidebar) {
	// eg. hide the subsidebar for a specific category
	# is (is_category('articles')) {$vhidesubsidebar = true;}
	return $vhidesubsidebar;
}

// /= SubSidebar Output =/
// -----------------------
// 1.9.5: allow the subsidebar to be removed conditionally
add_filter('skeleton_subsidebar_output', 'muscle_subsidebar_output');
function muscle_subsidebar_output($voutputsubsidebar) {
	// eg. remove the subsidebar from a specific category
	# is (is_category('articles')) {$voutputsubsidebar = false;}
	return $voutputsubsidebar;
}

// /= Change the Footer Sidebar Template =/
// ----------------------------------------
add_filter('skeleton_footer_sidebar','muscle_footer_sidebar_template');
function muscle_footer_sidebar_template($vtemplate) {
	// eg, look for footer-page.php sidebar template for pages
	// (note: template file and corresponding widget will need to be created)
	# if (get_post_type(get_the_ID()) == 'page') {return 'footer-page';}
	return $vtemplate;
}

// /= Post/Page Sidebars =/
// ------------------------
// (1.8.0 DEPRECATED) use skeleton_sidebar_layout_override instead
// for override post/page sidebar template used
// add_filter('skeleton_sidebar_layout_position','muscle_sidebar_layout_position');
// function muscle_sidebar_layout_position($vposition) {
// 	// eg. swap the order that sidebars are called
// 	// (note this does not change the position styling however)
// 	# if ($vposition == 'left') {$vposition = 'right';}
// 	# elseif ($vposition == 'right') {$vposition = 'left';}
// 	// eg. remove a righthand page subsidebar for about page
// 	// by returning an empty position value
// 	# if ( (is_page('about')) && ($vposition == 'right') ) {return '';}
// 	// eg. or call an alternative sidebar template for an about page
// 	// (note: template file and corresponding widget will need to be created)
// 	# if ( (is_page('about')) && ($vposition == 'right') ) {
// 	#	hybrid_get_sidebar('about'); return '';
// 	# }
// 	return $vposition;
// }

// /= Custom Post Type Sidebars =/
// -------------------------------
// (1.8.0 DEPRECATED) use skeleton_sidebar_layout_override instead
// sidebar_layout_* filter
// calling alternative sidebar templates for custom post type 'customslug'
// called by skeleton_get_sidebar_layout if post type is not 'post' or 'page'
// add_filter('skeleton_sidebar_layout_customslug','muscle_sidebar_layout_customslug');
// function muscle_sidebar_layout_customslug($vposition) {
// 	// eg, load a custom sidebar for this post type
// 	# if ($vposition == 'left') {hybrid_get_sidebar('customleft');}
// 	# if ($vposition == 'right') {hybrid_get_sidebar('customright');}
// 	// or for 2 left-hand sidebars for this post type
// 	# if ($vposition == 'left') {
// 	#	hybrid_get_sidebar('customleft');
// 	#   hybrid_get_sidebar('customleft2');
// 	# }
// 	// does not output any sidebars by default
// 	return $vposition;
// }

// /= No Post Type Sidebar =/
// --------------------------
// (1.8.0 DEPRECATED) use skeleton_sidebar_layout_override instead
// catches if post type is passed as empty
// add_filter('skeleton_sidebar_layout_general','muscle_sidebar_layout_general');
// function muscle_sidebar_layout_general($vposition) {
// 	// probably doesn't ever happen but post type might be empty somehow
// 	// if sidebars are disappearing because of this you could use a fallback
// 	# hybrid_get_sidebar('fallback');
// 	# return 'anything';
// 	return $vposition;
// }


// /===========
// === META ===
// ===========/
// skeleton_subtitle_key
// skeleton_meta_format_top
// skeleton_meta_format_bottom
// skeleton_list_meta_format_top
// skeleton_list_meta_format_bottom
// skeleton_meta_override_top
// skeleton_meta_override_bottom
// skeleton_author_bio_box
// skeleton_author_bio_box_position
// skeleton_author_bio_avatar_size
// skeleton_about_author_text
// skeleton_author_posts_anchor
// skeleton_comments_avatar_size
// skeleton_comments_popup_size
// skeleton_comments_template
// skeleton_post_type_display
// skeleton_pagenavi_post_types
// skeleton_pagenavi_archive_types
// skeleton_pagenavi_override
// skeleton_breadcrumb_post_types
// skeleton_breadcrumb_archive_types
// skeleton_breadcrumb_override
// /==========/
// TODO: Breadcrumb Filter Examples

// /= Subtitle Key Filter =/
// -------------------------
add_filter('skeleton_subtitle_key','muscle_subtitle_key');
function muscle_subtitle_key($vsubtitlekey) {
	// returns the post/page custom field meta key used for subtitles
	// default is the WP Subtitle Plugin key: 'wps_subtitle'
	// if using another subtitle plugin you may need to change this
	// eg. for video posts if subtitle is stored in 'video_subtitle'
	# if (get_post_type(get_the_ID()) == 'video') {$vsubtitlekey = 'video_subtitle';}
	return $vsubtitlekey;
}

// /= Meta Top Format =/
// ---------------------
add_filter('skeleton_meta_format_top','muscle_meta_format_top');
function muscle_meta_format_top($vargs) {
	# $vformat = $vargs['format'];
	# $vposttype = $vargs['posttype'];
	# $vpostid = $vargs['postid'];
	// Here you could return a different meta format
	// according to post type or other logic
	# if ($vposttype == 'video') {$vargs['format'] = 'Video Metaline.';}
	return $vargs;
}

// /= Meta Bottom Format =/
// ------------------------
add_filter('skeleton_meta_format_bottom','muscle_meta_format_bottom');
function muscle_meta_format_bottom($vargs) {
	# $vformat = $vargs['format'];
	# $vposttype = $vargs['posttype'];
	# $vpostid = $vargs['postid'];
	// Here you could return a different meta format
	// according to post type or other logic, eg.
	# if ($vposttype == 'video') {$vargs['format'] = 'Video Metaline.';}
	return $vargs;
}

// /= List Entry Meta Top =/
// -------------------------
add_filter('skeleton_list_meta_format_top','muscle_list_meta_format_top');
function muscle_list_meta_format_top($vformat) {
	// default is set via theme options
	// override whether the entry meta top is shown in a post list
	// eg. for a 'video' post type, don't show the entry meta in post list
	# if (get_post_type() == 'video') {return '0';}
	return $vdefault;
}

// /= List Entry Meta Bottom =/
// ----------------------------
add_filter('skeleton_list_meta_format_bottom','muscle_list_meta_format_bottom');
function muscle_list_meta_format_bottom($vformat) {
	// default is set via theme options
	// override whether the entry meta bottom is shown in a post list
	// eg. for a 'video' post type, do show the entry meta bottom in post list
	# if (get_post_type() == 'video') {return '1';}
	return $vdefault;
}

// /= Entry Meta Top Override =/
// -----------------------------
add_filter('skeleton_meta_override_top','muscle_meta_override_top');
function muscle_meta_override_top($vmeta) {
	// here you can completely override the entry meta top output
	// eg. clear the top meta output for search results
	# if (is_search()) {$vmeta = '';}
	return $vmeta;
}

// /= Entry Meta Bottom Override =/
// --------------------------------
add_filter('skeleton_meta_override_bottom','muscle_meta_override_bottom');
function muscle_meta_override_bottom($vmeta) {
	// here you can completely override the entry meta bottom output
	// eg. clear the bottom meta output for all archive pages
	# if (is_archive()) {$vmeta = '';}
	return $vmeta;
}

// /= Author Bio Box =/
// --------------------
add_filter('skeleton_author_bio_box','muscle_author_bio_box');
function muscle_author_bio_box($vshowbox) {
	// override when the author bio box is shown
	// set $vshowbox to '1' to display, anything else to not display
	// (value is already set for current post type)
	// eg. always hide for a particular post category
	# if (is_category('Site News')) {$vshowbox = 0;}
	return $vshowbox;
}

// /= Author Bio Box Position =/
// -----------------------------
add_filter('skeleton_author_bio_box_position','muscle_author_bio_box_position');
function muscle_author_bio_box_position($vposition) {
	// override the selected theme options bio box position
	// eg. if bottom by default for a page, switch to top
	// remembering to handle to other position or you get two
	# if (get_post_type()) == 'page') {
	#	if ($vposition == 'top') {$vposition == 'bottom';}
	#   elseif ($vposition == 'bottom') {$vposition == 'top;}
	# }
	return $vposition;
}

// /= Author Bio Avatar Size =/
// ----------------------------
add_filter('skeleton_author_bio_avatar_size','muscle_author_bio_avatar_size');
function muscle_author_bio_avatar_size($vsize) {
	// change the squared size of the Author Avatar in the bio box
	// eg. a different size for a video post type
	# if (get_post_type() == 'video') {$vsize = '96';}
	return $vsize;
}

// /= About Author Text =/
// -----------------------
add_filter('skeleton_about_author_text','muscle_about_author_text');
function muscle_about_author_text($vboxtitle) {
	// override the "About {author_name}" box title
	// eg. use "{authorname}'s Bio" instead
	# $vboxtitle = esc_attr(sprintf(__('%s\'s Bio', 'bioship'), get_the_author() ));
	return $vboxtitle;
}

// /= Author Posts Anchor Text =/
// ------------------------------
add_filter('skeleton_author_posts_anchor','muscle_author_posts_anchor');
function muscle_author_posts_anchor($vanchor) {
	// override the 'View all {post_type}s by {author_name}' anchor text
	// eg. use 'Read more {post_type}s by {author_name}
	# global $post; $vposttype = $post->post_type;
	# if ( ($vposttype != 'page') && ($vposttype != 'post') ) {
	#	$vposttypeobject = get_post_type_object($vposttype);
	#	$vposttypedisplay = $vposttypeobject->labels->singular_name;
	#	$vposttypedisplay = apply_filters('skeleton_post_type_display',$vposttypedisplay);
	# }
	# $vanchor = sprintf(__( 'Read more '.$vposttypedisplay.'s by %s <span class="meta-nav">&rarr;</span>', 'bioship' ), get_the_author()) );
	return $vanchor;
}

// /= Comments Closed Text =/
// --------------------------
add_filter('skeleton_comments_closed_text','muscle_comments_closed_text');
function muscle_comments_closed_text($vtext) {
	// default is empty, no text displayed
	// eg. say comments are closed
	# $vtext = 'Comments are Closed.';
	return $vtext;
}

// /= No Comments Text =/
// ----------------------
add_filter('skeleton_no_comments_text','muscle_no_comments_text');
function muscle_no_comments_text($vtext) {
	// default is empty, no text displayed
	// eg. say Be the first to add a comment
	# $vtext = 'Be the first to add a comment...';
	return $vtext;
}

// /= Commenter Avatar Size =/
// ---------------------------
add_filter('skeleton_comments_avatar_size','muscle_comments_avatar_size');
function muscle_comments_avatar_size($vsize) {
	// change the avatar size in comments, default 48, eg. 32 square
	# $vsize = '32';
	return $vsize;
}

// /= Comments Template =/
// -----------------------
add_filter('skeleton_comments_template','muscle_comments_template');
function muscle_comments_template($vtemplate) {
	// change the default comments template used from comments.php
	// note: do not forget the leading / slash or it will fail
	// you can already use comments-{posttype}.php via hierarchy
	// eg. for an alternative about page comments template
	# if (is_page('About')) {$vtemplate = '/about-comments.php';}
	return $vtemplate;
}

// /= Comments Popup Size =/
// -------------------------
add_filter('skeleton_comments_popup_size','muscle_comments_popup_size');
function muscle_comments_popup_size($vsizearray) {
	// change the comments popup window size (500x500 default)
	// eg. for a 640x480 comments popup window
	# $vsizearray[0] = 600; $vsizearray[1] = 480;
	return $vsizearray;
}

// /= Post Type Display Name =/
// ----------------------------
add_filter('skeleton_post_type_display','muscle_post_type_display');
function muscle_post_type_display($vposttypedisplay) {
	// defaults to the label set in post type object
	// change a post type display name eg, a CPT slug to display name
	# if ($vposttype == 'dwqa-question') {return 'Support Question';}
	return $vposttypedisplay;
}

// /= Page Navigation Post Types =/
// --------------------------------
add_filter('skeleton_pagenavi_post_types','muscle_pagenavi_post_types');
function muscle_pagenavi_post_types($vposttype) {
	// by default page navigation is off for pages
	// eg, you could turn it on for a certain page
	# if (is_page('123')) {$vposttype = 'page';}
	return $vposttype;
}

// /= Page Navigation Archive Post Types =/
// -----------------------------------
add_filter('skeleton_pagenavi_archive_types','muscle_pagenavi_archive_types');
function muscle_pagenavi_archive_types($vposttype) {
	// by default page navigation is off for pages
	// eg, you could turn it on for a certain page
	# if (is_page('123')) {$vposttype = 'page';}
	return $vposttype;
}

// /= Page Navigation Override =/
// ------------------------------
add_filter('skeleton_pagenavi_override','muscle_pagenavi_override');
function muscle_pagenavi_override($vpagenav) {
	// override the page navigation output
	# $vpagenave = my_custom_pagenav();
	return $vpagenav;
}

// /= Breadcrumb Post Types =/
add_filter('skeleton_breadcrumb_post_types','muscle_breadcrumb_post_types');
function muscle_breadcrumb_post_types($vposttypes) {
	# TODO: example
	return $vposttypes;
}

// /= Breadcrumb Archive Post Types =/
add_filter('skeleton_breadcrumb_archive_types','muscle_breadcrumb_archive_types');
function muscle_breadcrumb_archive_types($varchivetypes) {
	# TODO: example
	return $varchivetypes;
}

// /= Breadcrumb Override =/
add_filter('skeleton_breadcrumb_override','muscle_breadcrumb_override');
function muscle_breadcrumb_override($vhtml) {
	# TODO: example
	return $vhtml;
}


// /=============
// === MUSCLE ===
// =============/
// muscle_widget_text_shortcodes
// muscle_widget_title_shortcodes
// muscle_smooth_scrolling
// muscle_load_selectivizr
// muscle_load_html5shiv
// muscle_load_supersleight
// muscle_load_ie8dom
// muscle_load_flexibility
// muscle_load_nwwatcher
// muscle_load_nwevents
// muscle_load_mediaqueries
// muscle_load_csssupports
// muscle_load_matchmedia
// muscle_load_modernizr
// muscle_load_matchheight
// muscle_load_prefixfree
// muscle_load_fastclick
// muscle_load_mousewheel
// muscle_load_stickykit
// muscle_sticky_elements
// muscle_load_fitvids
// muscle_fitvids_elements
// muscle_load_scrolltofixed
// muscle_perpage_overrides
// muscle_perpage_styles
// /============/

// /= Do shortcodes in Widget Text =/
// ----------------------------------
add_filter('muscle_widget_text_shortcodes','muscle_widget_text_do_shortcodes');
function muscle_widget_text_do_shortcodes($vswitch) {
	// defaults to true, but you can turn them off eg.
	# $vswitch = false;
	return $vswitch;
}

// /= Do Shortcodes in Widget Titles =/
// ------------------------------------
add_filter('muscle_widget_title_shortcodes','muscle_widget_title_do_shortcodes');
function muscle_widget_title_do_shortcodes($vswitch) {
	// defaults to true, but you can turn them off eg.
	# $vswitch = false;
	return $vswitch;
}

// /= Smooth Scrolling =/
// ----------------------
add_filter('muscle_smooth_scrolling','muscle_custom_smooth_scrolling');
function muscle_custom_smooth_scrolling($vsmoothscrolling) {
	// could conditionally load smooth scrolling, eg.
	# if (is_page()) {$vsmoothscrolling = 1;}
	return $vsmoothscrolling;
}

// /= Selectivizr Loading =/
// -------------------------
// add_filter('muscle_load_selectivizr', '');

// /= HTML5 Shiv Loading =/
// ------------------------
// add_filter('muscle_load_html5shiv', '');

// /= Supersleight Loading =/
// --------------------------
// add_filter('muscle_load_supersleight', '');

// /= IE8 DOM Polyfill Loadin =/
// -----------------------------
// add_filter('muscle_load_ie8dom', '');

// /= Flexibility (Flexbox Polyfill) Loading =/
// --------------------------------------------
// add_filter('muscle_load_flexibility', '');

// /= NW Watcher Loading =/
// ------------------------
// add_filter('muscle_load_nwwatcher', '');

// /= NW Events Loading =/
// -----------------------
// add_filter('muscle_load_nwevents', '');

// /= MediQueries Loading =/
// -------------------------
// add_filter('muscle_load_mediaqueries', '');

// /= CSS Supports Loading =/
// --------------------------
// add_filter('muscle_load_csssupports', '');

// /= MatchMedia Loading =/
// ------------------------
// add_filter('muscle_load_matchmedia', '');

// /= Modernizr Loading =/
// -----------------------
// add_filter('muscle_load_modernizr', '');

// /= MatchHeight Loading =/
// -------------------------
// add_filter('muscle_load_matchheight', '');

// /= Prefix Free Loading =/
// -------------------------
add_filter('muscle_load_prefixfree','muscle_custom_load_prefixfree');
function muscle_custom_load_prefixfree($vprefixfree) {
	// could conditionally load prefixfree, eg.
	# if (is_page()) {$vprefixfree = 1;}
	return $vprefixfree;
}

// /= Fast Click Loading =/
// ------------------------
add_filter('muscle_load_fastclick','muscle_custom_load_fastclick');
function muscle_custom_load_fastclick($vfastclick) {
	// could conditionally load fastclick, eg.
	# if (is_page()) {$vfastclick = 1;}
	return $vfastclick;
}

// /= Mouse Wheel Loading =/
// -------------------------
add_filter('muscle_load_mousewheel','muscle_custom_load_mousewheel');
function muscle_custom_load_mousewheel($vmousewheel) {
	// could conditionally load mousewheel, eg.
	# if (is_page()) {$vmousewheel = 1;}
	return $vmousewheel;
}

// /= StickyKit Loading =/
// -----------------------
add_filter('muscle_load_stickykit','muscle_custom_load_stickykit');
function muscle_custom_load_stickykit($vstickykit) {
	// could conditionally load stickykit, eg.
	# if (is_page()) {$vstickykit = 1;}
	return $vstickykit;
}

// /= Sticky Kit Elements =/
// -------------------------
add_filter('muscle_sticky_elements','muscle_custom_stickykit_elements');
function muscle_custom_stickykit_elements($velements) {
	// eg. do not sticky float subsidebar for single posts
	# if ( (is_post_type('post')) && (is_singular()) ) {
	#	$velements = str_replace('#sidebar','',$velements);
	# }
	return $velements;
}

// /= FitVids Loading =/
// ---------------------
add_filter('muscle_load_fitvids','muscle_custom_load_fitvids');
function muscle_custom_load_fitvids($vfitvids) {
	// could conditionally load fitvids, eg.
	# if (is_page()) {$vfitvids = 1;}
	return $vfitvids;
}

// /= FitVids Elements =/
// ----------------------
add_filter('muscle_fitvids_elements','muscle_custom_fitvids_elements');
function muscle_custom_fitvids_elements($velements) {
	// eg. Use fitvids on #sidebar as well as #content for video archives
	# if (is_post_type_archive('video')) {$velements .= ',#sidebar';
	return $velements;
}

// /= Scroll to Fixed Loading =/
// -----------------------------
add_filter('muscle_load_scrolltofixed','muscle_custom_load_scrolltofixed');
function muscle_custom_load_scrolltofixed($vscrolltofixed) {
	// could conditionally load scroll to fixed, eg.
	# if (is_page()) {$vscrolltofixed = 1;}
	return $vscrolltofixed;
}

// /= Fullscreen Video Background =/
// ---------------------------------
// TODO: a theme option could be added for this, currently only via filters
// this uses filters to create a hidden input in footer (see muscle.php)
// these launch the video using these values via init.js and video-background.js
// note: javascripts/video-background.js currently only supports 'youtube' mode

// Sitewide Example
# add_filter('muscle_videobackground_type','muscle_video_background_type');
# function muscle_video_background_type() {return 'youtube';}
// note: the youtube video ID ie. http:/youtube.com/?v=myvideoid
# add_filter('muscle_videobackground_id','muscle_video_background_id');
# function muscle_video_background_id() {return 'myvideoid';}
// note: the delay before video start in milliseconds, default 1000
# add_filter('muscle_videobackground_delay','muscle_video_background_delay');
# function muscle_video_background_delay() {return '5000';}

// Frontpage Only Example
# add_filter('muscle_videobackground_type','muscle_video_background_type');
# function muscle_video_background_type() {
# 	if (is_front_page()) {return 'youtube';}
# }
# add_filter('muscle_videobackground_id','muscle_video_background_id');
# function muscle_video_background_id() {
#	if (is_front_page()) {return 'myvideoid';}
# }
# add_filter('muscle_videobackground_delay','muscle_video_background_delay');
# function muscle_video_background_delay() {
#	if (is_front_page()) {return '5000';}
# }


// /= Display Overrides =/
// -----------------------
add_filter('muscle_perpage_overrides','muscle_perpage_override_filter');
function muscle_perpage_override_filter($voverride) {
	// Here you could force enable or disable of a page override eg.
	# if (get_post_type(get_the_ID()) == 'video') {
	# 		$voverride['hideheader'] = ''; $voverride['hidesidebar'] = '1';
	# }
	return $voverride;
}

// /= Perpage Styles =/
// --------------------
add_filter('muscle_perpage_styles','muscle_perpage_styles_filter');
function muscle_perpage_styles_filter($vstyles) {
	// Here you could add styles to specific post types eg.
	# if (get_post_type(get_the_ID()) == 'video') {
	# 		$vstyles .= '.videocontainer {width:95%;}';
	# }
	return $vstyles;
}


// /===========
// === SKIN ===
// ===========/
// skin_dynamic_css
// skin_dynamic_admin_css
// skin_dynamic_login_css
// /==========/

// /= Dynamic CSS Replacement =/
// -----------------------------
add_filter('skin_dynamic_css','muscle_dynamic_css');
function muscle_dynamic_css($vcss) {
	// Add some CSS string replacement rules
	# if (strstr($vcss,'%SOMETHING%')) {
	# 	$vcss = str_replace('%SOMETHING%','something.png',$vcss);
	# }
	// or add some extra CSS to your site
	// note: conditional page context checks will fail in this filter
	// if it is loaded by the (preferred) direct or admin-ajax methods
	# $vcss .= '';
	return $vcss;
}

// /= Dynamic Admin CSS =/
// -----------------------
add_filter('skin_dynamic_admin_css','muscle_dynamic_admin_css');
function muscle_dynamic_admin_css($vcss) {
	// Add some CSS string replacement rules
	# if (strstr($vcss,'%SOMETHING%')) {
	# 	$vcss = str_replace('%SOMETHING%','something.png',$vcss);
	# }
	// or add some extra CSS to the admin
	# $vcss .= '';
	return $vcss;
}

// /= Dynamic Login CSS =/
// -----------------------
add_filter('skin_dynamic_login_css','muscle_dynamic_login_css');
function muscle_dynamic_login_css($vcss) {
	// Add some CSS string replacement rules
	# if (strstr($vcss,'%SOMETHING%')) {
	# 	$vcss = str_replace('%SOMETHING%','something.png',$vcss);
	# }
	// or add some extra CSS to the login page
	# $vcss .= '';
	return $vcss;
}

// /============
// === ADMIN ===
// ============/
// admin_adminbar_theme_options
// admin_adminbar_menu_icon
// admin_adminbar_theme_options_title
// admin_adminbar_theme_options_icon
// admin_adminbar_howdy_title
// admin_adminbar_remove_items
// admin_admin_footer_text
// /===========/

// /= AdminBar Theme Options Link =/
// ---------------------------------
// 1.8.5: changed from option to filter
add_filter('admin_adminbar_theme_options','muscle_admin_bar_theme_options');
function muscle_admin_bar_theme_options($vadminbar) {
	// could turn this off
	# $vadminbar = 0;
	return $vadminbar;
}

// /= Change AdminBar Theme Options menu Title =/
// --------------------------------------------------
add_filter('admin_adminbar_theme_options_title','muscle_adminbar_menu_title');
function muscle_adminbar_menu_title($vtitle) {
	// note: default menu title is "Theme Options"
	// eg. change to '{Theme Name} Options' for child theme
	# if (is_child_theme()) {
	# 	global $vthemename; // $vtheme = wp_get_theme(); $vthemename = $vtheme['Name'];
	# 	$vtitle = $vthemename.' '.__('Options', 'bioship');
	# }
	return $vtitle;
}

// /= Options AdminBar Icon =/
// ---------------------------
// 1.8.0: for default theme options AdminBar item link
// ref: http://calebserna.com/dashicons-cheatsheet/
add_filter('admin_adminbar_menu_icon','muscle_adminbar_menu_icon');
function muscle_adminbar_menu_icon($vicon) {
	// default menu icon is '\\f115'
	// note: be sure to escape the backslash!
	# $vicon = '\\f100';
	return $vicon;
}

// /= Change the Admin Bar Theme Options Icon =/
// ---------------------------------------------
add_filter('admin_adminbar_theme_options_icon','muscle_adminbar_theme_options_icon');
function muscle_adminbar_theme_options_icon($vurl) {
	// note: adminbar icon size is forced to 22px by 22px via skin.php
	// default searches for /images/theme-icon.png via file hierarchy
	// you could change this to a different filename, eg. child-icon.png
	# global $vthemedirs;
	# $viconurl = bioship_file_hierarchy('url','child-icon.png',$vthemedirs['img']);
	# if ($viconurl) {return $viconurl;}
	return $vurl;
}

// /= Replace the Admin Bar "Howdy" text =/
// ----------------------------------------
add_filter('admin_adminbar_howdy_title','muscle_loggedinas_message');
function muscle_loggedinas_message($vtitle) {
	// note: default already changed to "Logged in as {user_name}"
	// eg. change to "Account: {user_name}"
	# global $current_user; wp_get_current_user();
	# $vusername = $current_user->user_login;
	# $vtitle = __('Account: ', 'bioship').$vusername;
	return $vtitle;
}

// /= Remove Admin Bar Items =/
// ----------------------------
// 1.8.0: added this filter
add_filter('admin_adminbar_remove_items','muscle_adminbar_remove_items');
function muscle_adminbar_remove_items($vitems) {
	// WordPress adminbar items removed by default
	# $vitems = array();
	return $vitems;
}

// /= Replace the Admin Footer text =/
// -----------------------------------
add_filter('admin_admin_footer_text','muscle_admin_footer_text');
function muscle_admin_footer_text($vtext) {
	// default is to remove footer text (return empty)
	// You could say anything else here, eg.
	# $vtext = "Don't forget to Smile! :-)";
	return $vtext;
}

// --------------------
// === INTEGRATIONS ===
// --------------------

// /============================
// == Jetpack Infinite Scroll ==
// ============================/
// skeleton_infinite_scroll_numposts
// skeleton_infinite_scroll_settings
// /===========================/

// /= Infinite Scroll Number of Posts each Load =/
// -----------------------------------------------
add_filter('skeleton_infinite_scroll_numposts','muscle_infinite_scroll_numposts');
function muscle_infinite_scroll_numposts($vnumposts) {
	// change the number of posts each load
	# $vnumposts = 3;
	return $vnumposts;
}

// /= Infinite Scroll Settings =/
// ------------------------------
add_filter('skeleton_infinite_scroll_settings','muscle_infinite_scroll_settings');
function muscle_infinite_scroll_settings($vsettings) {
	// use an alternative custom loop render function
	# $vsettings['render'] = 'my_custom_render_loop';
	return $vsettings;
}


// /==================
// === WooCommerce ===
// ==================/
// skeleton_woocommerce_alternative_directory
// skeleton_declare_woocommerce_support
// /=================/

// Even though WooCommerce is "free", all the extensions for WooCommerce are
// ridiculously expensive - probably as a result of the development costs for
// having to integrate with the existing WooCommerce programming. (Just my
// opinion of course, you can compare ecommerce extension costs yourself.)

// So if you have NOT already setup an online store with it, I recommend looking
// at alternatives such as eStore: http://bioship.space/recommends/eStore/
// Even though it is not "free", it is better value overall considering extensions
// that come with it, and it has just as many, actually many more options in-built.

// /= Alternative Template Directory =/
// ------------------------------------
add_filter('skeleton_woocommerce_alternative_directory','muscle_woocommerce_alternative_directory');
function muscle_woocommerce_alternative_directory($vswitch) {
	// to turn off alternate /templates/woocommerce/ directory and use /woocommerce/ default
	# $vswitch = false;
	return $vswitch;
}

// /= Declare WooCommerce Support =/
// ---------------------------------
// ref: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
add_filter('skeleton_declare_woocommerce_support','muscle_declare_woocommerce_support');
function muscle_declare_woocommerce_support($vswitch) {
	// you could turn off woocommerce support declaration
	# $vswitch = '0';
	return $vswitch;
}


// /========================
// = TGM Plugin Activation =
// ========================/
// tgm_plugins_array
// tgm_config_array
// tgm_theme_page_message
// tgm_plugin_page_message
// tgm_plugin_bundles_path
// /=======================/

// /= TGM Recommended Plugins =/
// -----------------------------
add_filter('tgm_plugins_array','muscle_tgm_plugins_array');
function muscle_tgm_plugins_array($vplugins) {
	// Add to or modify the recommended plugins array
	// see end of parent functions.php for existing array
	// see /includes/tgm-examples.php for in-depth examples
	// eg. add back some recommendations from the SMPL Skeleton Theme
	# $vplugins[] = array('name'=>'WP-PageNavi','slug'=>'wp-pagenavi','required'=>false);
	# $vplugins[] = array('name'=>'Simple Shortcodes','slug'=>'smpl-shortcodes','required'=>false);
	return $vplugins;
}

// /= TGM Configuration =/
// -----------------------
add_filter('tgm_config_array','muscle_tgm_config_array');
function muscle_tgm_config_array($vconfig) {
	// Add to or modify the TGM configuration array
	// see end of parent functions.php for existing array
	# $vconfig[''] =
	return $vconfig;
}

// /= TGM Theme Page Message =/
// ----------------------------
// note: called inside of the tgm_config_array filter
add_filter('tgm_theme_page_message','muscle_theme_page_message');
function muscle_theme_page_message($vmessage) {
	// Here you can change the outputted message on the theme options page.
	# $vmessage = '<b>Theme Plugin Recommendations</b>.<br>';
	return $vmessage;
}

// /= TGM Plugin Page Message =/
// -----------------------------
add_filter('tgm_plugin_page_message','muscle_plugin_page_message');
function muscle_plugin_page_message($vmessage) {
	// Here you can change the outputted message on the TGM plugin page.
	# $vmessage = '<b>Plugin Recommendation Installation Page</b>.<br>';
	return $vmessage;
}

// /= TGM Plugins Bundle Path =/
// -----------------------------
add_filter('tgm_plugin_bundles_path','muscle_plugin_bundles_path');
function muscle_plugin_bundles_path($vpath) {
	// change bundle path to the child theme subdirectory
	# $vpath = get_stylesheet_directory().'/plugins/';
	return $vpath;
}

// /==========================
// === Open Graph Protocol ===
// ==========================/
// muscle_open_graph_default_image
// muscle_open_graph_default_image_size
// muscle_open_graph_override_image
// /=========================/
// some custom filters are added here for image handling:

// http://www.itthinx.com/plugins/open-graph-protocol/
// Ref: http://docs.itthinx.com/document/open-graph-protocol-framework/
// note available filters for Open Graph Protocol plugin meta tags:
// open_graph_protocol_meta
// open_graph_protocol_meta_tag
// open_graph_protocol_metas
// open_graph_protocol_echo_metas

// /= Open Graph Default Image =/
// ------------------------------
// Just set the default open graph image (in-built call in muscle.php)
// (if there is no featured image for the post)
add_filter('muscle_open_graph_default_image','muscle_open_graph_default_image_url');
function muscle_open_graph_default_image_url($vurl) {
	// override the default open graph url set via theme options
	// any valid image URL will do here, 600x600 is optimal (afaik)
	# $vurl = site_url().'/images/my-sites-default-image.png';
	return $vurl;
}

// /= Open Graph Default Image Size =/
// -----------------------------------
// Set the default image size fallback - for trouble cases only
// (you only need this if both allow_fopen_url is off and url to path fails)
add_filter('muscle_open_graph_default_image_size','muscle_open_graph_default_size');
function muscle_open_graph_default_size($vimagesize) {
	// set an explicit width[0] and height[1] for the default image URL
	# $vimagesize[0] = '600'; $vimagesize[1] = '600';
	return $vimagesize;
}

// /= Open Graph Image Override =/
// -------------------------------
// Override open graph images conditionally (in-built call in muscle.php)
// (even if there is a already a featured image for this post)
// priority set to 1 as we have the existing custom field value override at 0
add_filter('muscle_open_graph_override_image','muscle_open_graph_override_image_custom',1);
function muscle_open_graph_override_image_custom($vimage) {
	// override the existing open graph image meta
	// return any array with width[0], height[1] and url[2]
	// the URL needs to be here at least, width and height better but optional
	// for example, setup image URLs for categories based on ID so we can upload to:
	// /images/category-image-1.jpg, /images/category-image-2.jpg, etc...
	# if (is_category()) {
	#	global $wp_query; // bizarre: no wp function to get current category ID
	#   $vi = $wp_query->cat_ID; // so just getting it from the main wp_query
	# 	$vogimage = array();
	#	$vimagepath = '/images/category-image-'.$vi.'.jpg';
	// here we are setting an explicit common width[0] and height[1]
	// and the image URL itself [2] to the image array returned
	# 	if (file_exists(ABSPATH.$vimagepath)) {
	# 		$vogimage[0] = '600'; $vogimage[1] = '600';
	# 		$vogimage[2] = site_url().$vimagepath;
	#	}
	# }
	# if (isset($vogimage[2])) {if ($vogimage[2] != '') {return $vogimage;} }
	return $vimage;
}

// /===== END FILTERS ===== DOC BOUNDARY =====/ //

// ...DEPRECATING...

// --------------------
// == Theme My Login ==
// --------------------
// register_form_image
// login_form_image
// --------------------
// note: these 2 have been added to theme options so removed here

// Customize the Register Form top image URL
// -----------------------------------------
# remove_filter('register_form_image','muscle_register_form_image');
# add_filter('register_form_image','custom_muscle_register_form_image');
# function custom_muscle_register_form_image($vregisterformimage) {
#		// this example will look for and use register-image.png if found
#		global $vthemedirs;
# 		$vregisterimage = bioship_file_hierarchy('url','register-image.png',$vthemedirs['img']);
#		if ($vregisterimage) {return $vregisterimage;}
# 		return $vregisterformimage;
# }

// Customize the Login Form top image URL
// --------------------------------------
# remove_filter('login_form_image','muscle_login_form_image');
# add_filter('login_form_image','custom_muscle_login_form_image');
# function custom_muscle_login_form_image($vloginformimage) {
#		// this example will look for and use login-image.png
#		global $vthemedirs
# 		$vloginimage = bioship_file_hierarchy('url','login-image.png',$vthemedirs['img']);
#		if ($vloginimage) {return $vloginimage;}
# 		return $vloginformimage;
# }


?>