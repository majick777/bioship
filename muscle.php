<?php

// =========================
// ==== BioShip Muscle =====
// == Extending WordPress ==
// =========================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// ----------------------------
// === muscle.php Structure ===
// ----------------------------
// === Metabox Overrides ===
// - Get Archive Post ID
// - Get PerPost Display Overrides
// - Get PerPost Layout Overrides
// - Enqueue PerPost Override Styles
// - Output PerPost Override Styles
// - PerPost Thumbnail Size Filter
// - Get Content Filter Overrides
// - Maybe Remove Content Filters
//
// === Muscle Settings ===
// == Misc ==
// - Default Gravatar
// - Classic Text Widget
// - Discreet Text Widget
// - Video Background
// == Scripts ==
// - Internet Explorer
// - PrefixFree
// - PrefixFree Script Tag Filter
// x NWWatcher
// x NWEvents
// - Media Queries
// - FastClick
// - MouseWheel
// - CSS.Supports
// - MatchMedia
// - Modernizr
// == Extras ==
// - Smooth Scrolling
// - MatchHeight
// - StickyKit
// - FitVids
// - ScrollToFixed
// - Logo Resize
// - Site Text Resize
// - Header Resize
// - Script Variables
// == Thumbnails ==
// - JPEG Quality Filter
// - CPT Thumbnail Size Overrides
// - Fun with Fading Thumbnails
// == Reading ==
// - Include/Exclude Blog Categories
// - Search Results per Page
// - Make Custom Post Types Searchable
// - Jetpack Infinite Scroll Support
// == Excerpts ==
// - Add Excerpt Support to Pages
// - Enable Shortcodes in Excerpts
// - Excerpt with Shortcodes
// - Filter Excerpt Length
// == Read More ==
// - Read More Link
// - Read More Wrapper
// - Remove More Jump Link
// = Writing =
// - WP Subtitle CPT Support
// == RSS ==
// - Automatic Feed Links
// - RSS Publish Delay
// - Set Post Types in Feed
// - Full Content Page Feeds
// - Full Content Page Feed Filter
// == User Admin ==
// - Add Theme Options to Admin Bar
// - Replace Welcome in Admin Bar
// - Remove Update Notice
// - Stop New User Notifications
// - Disable Self Pings
// - Cleaner Admin Bar
// - Enqueue Code Editor for Style Box
// - Admin Bar Style Link
// - Style Editor Box
// == Login ==
// - Login Header URL
// - Login Header Title
// - Login Page Logo
// == Integrations ==
// - Integrations Loader
// ----------------------------


// Development TODOs
// -----------------
// - check/fix admin list thumbnail display
// - retest Theme Switching functionality


// -------------------------
// === MetaBox Overrides ===
// -------------------------
// (to apply various PerPost Theme Options)
// 1.8.0: metabox interface moved to admin.php

// -------------------
// Get Archive Post ID
// -------------------
// 2.2.0: added to get archive post for override values
if ( !function_exists( 'bioship_muscle_get_archive_post' ) ) {
 function bioship_muscle_get_archive_post() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $wpdb, $vthemelayout;
	$context = $vthemelayout['pagecontext'];
	$subcontext = $vthemelayout['subpagecontext'];
	$postid = false;

	// --- get matching archive/post type subcontext ---
	if ( in_array( $context, array( 'single', 'archive' ) ) ) {
		if ( 'archive' == $context ) {
			$query = "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_archive_subcontext' AND meta_value = %s";
		} elseif ( 'single' == $context ) {
			$query = "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_posttype_subcontext' AND meta_value = %s";
		} else {
		    return false;
        }
		$results = $wpdb->get_results( $wpdb->prepare( $query, $subcontext ), ARRAY_A );
		if ( $results && is_array( $results ) && ( count( $results ) > 0 ) ) {
			foreach ( $results as $result ) {
				// --- loop to check post status ---
				$query = "SELECT post_status FROM " . $wpdb->prefix . "posts WHERE ID = %d";
				$status = $wpdb->get_var( $wpdb->prepare( $query, $result['post_id'] ) );
				if ( 'trash' != $status ) {
					$postid = $results['post_id'];
					break;
				}
			}
		}
	}

	// --- get matching template context ---
	if ( !$postid ) {
		$query = "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_template_context' AND meta_value = %s";
		$query = $wpdb->prepare( $query, $context );
		$results = $wpdb->get_results( $query, ARRAY_A );
		if ( $results && is_array( $results ) && ( count( $results ) > 0 ) ) {
			foreach ( $results as $result ) {
				// --- loop to check post status ---
				$query = "SELECT post_status FROM " . $wpdb->prefix . "posts WHERE ID = %d";
				$status = $wpdb->get_var( $wpdb->prepare( $query, $result['post_id'] ) );
				if ( 'trash' != $status ) {
					$postid = $result['post_id'];
					break;
				}
			}
		}
	}

	// --- filter and return ---
	// TODO: add this new filter to docs
	$postid = bioship_apply_filters( 'muscle_archive_post', $postid, $context, $subcontext );
	return $postid;
 }
}


// -----------------------------
// Get PerPost Display Overrides
// -----------------------------
// 1.8.0: rename from muscle_get_overrides and now for displays only
// 1.8.0: removed options tab as only needed for admin display
// 1.8.0: moved content filters to a separate function
// 1.8.0: removed perpoststyles from overrides, now retrieved separately
if ( !function_exists( 'bioship_muscle_get_display_overrides' ) ) {
 function bioship_muscle_get_display_overrides( $resource ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.1.1: set empty array instead of global
	$display = array();

	// --- check resource ---
	if ( is_numeric( $resource ) ) {

		// --- get display overrides ---
		// 2.0.8: use prefixed post meta key
		$display = get_post_meta( $resource, '_' . THEMEPREFIX . '_display_overrides', true );

		// --- maybe convert old metakey values ---
		// 2.0.8: to convert to prefixed metakey
		if ( !$display ) {
			// 2.2.0: fix to reset display to an array
			$display = array();
			$oldpostmeta = get_post_meta( $resource, '_displayoverrides', true );
			if ( $oldpostmeta && is_array( $oldpostmeta ) ) {
				$display = $oldpostmeta;
				delete_post_meta( $resource, '_displayoverrides' );
				update_post_meta( $resource, '_' . THEMEPREFIX . '_display_overrides', $display );
			}
		}
	}

	// --- maybe convert old dispay override values ---
	// 1.8.0: convert old display overrides to single meta array
	// 2.1.1: [deprecated] conversion of (very) old single metakey values

	// --- fix for empty values to avoid undefined index warnings ---
	// 1.8.5: added wrapper, headernav, footernav, breadcrumb, pagenavi
	$displaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi',
	);
	foreach ( $displaykeys as $displaykey ) {
		if ( !isset( $display[$displaykey] ) ) {
			$display[$displaykey] = '';
		}
	}

	// --- filter and return display overrides ---
	// 2.0.9: changed this filter name from muscle_perpage_overrides
	// 2.1.1: added post ID as second filter argument
	$display = bioship_apply_filters( 'muscle_display_overrides', $display, $resource );
	bioship_debug( "Display Overrides", $display );
	return $display;
 }
}

// --------------------------------
// Get PerPost Templating Overrides
// --------------------------------
// 1.9.5: separated for templating overrides
if ( !function_exists( 'bioship_muscle_get_templating_overrides' ) ) {
 function bioship_muscle_get_templating_overrides( $resource ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.1.1: set empty array instead of using global
	$overrides = array();

	// --- get post ID if not supplied ---
	if ( !$resource || !is_numeric( $resource ) ) {
		global $post;
		if ( isset( $post ) && is_object( $post ) ) {
			$resource = $post->ID;
		} else {
			return $overrides;
		}
	}

	// --- get templating overrides ---
	// 2.0.8: use prefixed post meta key
	$overrides = get_post_meta( $resource, '_' . THEMEPREFIX . '_templating_overrides', true );

	// --- maybe convert old metakey values
	// 2.0.8: to convert to prefixed metakey
	if ( !$overrides ) {
		// 2.2.0: fix to reset overrides to an array
		$overrides = array();
		$oldpostmeta = get_post_meta( $resource, '_templatingoverrides', true );
		if ( $oldpostmeta && is_array( $oldpostmeta ) ) {
			$overrides = $oldpostmeta;
			delete_post_meta( $resource, '_templatingoverrides' );
			update_post_meta( $resource, '_' . THEMEPREFIX . '_templating_overrides', $overrides );
		}
	}

	// --- set override keys ---
	$overridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom',
	);

	// --- fix for empty values to avoid undefined index warnings ---
	foreach ( $overridekeys as $overridekey ) {
		if ( !isset( $overrides[$overridekey] ) ) {
			$overrides[$overridekey] = '';
		}
	}

	// --- check for thumbnail size force off option ---
	// 1.9.8: fix to undefined vpostid variable
	// 2.0.8: check prefixed post meta value for thumbnail size
	$thumbnailsize = get_post_meta( $resource, '_' . THEMEPREFIX . '_thumbnailsize', true );

	// --- maybe convert unprefixed meta key ---
	// 2.0.8: maybe convert old thumbnail size meta key
	// 2.1.1: moved here from thumbnail size filter
	if ( !$thumbnailsize ) {
		$oldpostmeta = get_post_meta( $resource, '_thumbnailsize', true );
		if ( $oldpostmeta ) {
			$thumbnailsize = $oldpostmeta;
			delete_post_meta( $resource, '_thumbnailsize' );
			// 2.2.0: fix to mismatched variable name thumbsize
			update_post_meta( $resource, '_' . THEMEPREFIX . '_thumbnailsize', $thumbnailsize );
		}
	}

	// --- maybe set thumbnail override ---
	if ( $thumbnailsize && ( 'off' == $thumbnailsize ) ) {
		$overrides['image'] = 'off';
	}

	// --- filter and return templating overrides ---
	// 2.1.1: added post ID as second filter argument
	$overrides = bioship_apply_filters( 'muscle_templating_overrides', $overrides, $resource );
	bioship_debug( "Templating Overrides", $overrides );
	return $overrides;
 }
}

// -------------------------------
// Enqueue PerPost Override Styles
// -------------------------------
// 2.2.0: added separate enqueue action
if ( !function_exists( 'bioship_muscle_enqueue_override_styles' ) ) {

 add_action( 'init', 'bioship_muscle_enqueue_override_styles' );

 function bioship_muscle_enqueue_override_styles() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- for frontend styles only ---
	// 2.0.9: check admin context internally
	if ( is_admin() ) {
		return;
	}

	global $vthemesettings;
	if ( 'footer' == $vthemesettings['themecssmode'] ) {
		add_action( 'wp_footer', 'bioship_muscle_perpage_override_styles' );
	} else {
		add_action( 'wp_head', 'bioship_muscle_perpage_override_styles' );
	}
 }
}

// ------------------------------
// Output PerPost Override Styles
// ------------------------------
if ( !function_exists( 'bioship_muscle_perpage_override_styles' ) ) {
 function bioship_muscle_perpage_override_styles() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get theme display overrides ---
	// 1.8.5: set global value via muscle_get_display_overrides
	// 1.9.5: removed post check as global already set
	// 1.9.5: just set short name for vthemedisplay global
	global $vthemedisplay, $vthemelayout;
	$hide = $vthemedisplay;

	// --- add styles for display overrides ----
	// 2.1.1: check for overrides to prevent undefined index warnings
	// 2.2.0: add to style array instead of string for easier filtering
	$styles = array();
	if ( $hide ) {

		// Full Width Container Override
		// -----------------------------
		// 1.8.5: added full width container option (no wrap margins)
		// 1.9.8: fix to override key from fullwidth to wrapper
		if ( '1' == $hide['wrapper'] ) {
			$styles[] = '#wrap.container {width: 100% !important;}';
		}

		// Main Theme Areas
		// ----------------
		if ( '1' == $hide['header'] ) {
			$styles[] = '#header {display:none !important;}';
		}
		if ( '1' == $hide['footer'] ) {
			$styles[] = '#footer {display:none !important;}';
		}

		// Navigation
		// ----------
		if ( '1' == $hide['navigation'] ) {
			$styles[] = '#navigation {display:none !important;}';
		}
		if ( '1' == $hide['secondarynav'] ) {
			$styles[] = '#secondarymenu {display:none !important;}';
		}
		if ( '1' == $hide['headernav'] ) {
			$styles[] = '#header #headermenu {display:none !important;}';
		}
		if ( '1' == $hide['footernav'] ) {
			$styles[] = '#footer #footermenu {display:none !important;}';
		}

		// Sidebars
		// --------
		// 1.8.0: Sidebar hides removed here as handled by templating
		// 1.9.5: Changed back this is really for actually hiding not for templating
		// 1.9.5: apply individual sidebar hide conditional filters here
		// 2.2.0: fix to add to style array not as string
		$hidesidebar = bioship_apply_filters( 'skeleton_sidebar_hide', false );
		$hidesubsidebar = bioship_apply_filters( 'skeleton_subsidebar_hide', false );
		if ( $hidesidebar || ( '1' == $hide['sidebar'] ) ) {
			$styles[] = '#sidebar {display:none !important;}';
		}
		if ( $hidesubsidebar || ( '1' == $hide['subsidebar'] ) ) {
			$styles[] = '#subsidebar {display:none !important;}';
		}

		// Widget Areas
		// ------------
		// 1.9.5: re-added individual footer display overrides (for completeness)
		if ( '1' == $hide['headerwidgets'] ) {
			$styles[] = '#header-widget-area {display:none !important;}';
		}
		if ( '1' == $hide['footerwidgets'] ) {
			$styles[] = '#sidebar-footer {display:none !important;}';
		}
		if ( '1' == $hide['footer1'] ) {
			$styles[] = '#footer-widget-area-1 {display:none !important;}';
		}
		if ( '1' == $hide['footer2'] ) {
			$styles[] = '#footer-widget-area-2 {display:none !important;}';
		}
		if ( '1' == $hide['footer3'] ) {
			$styles[] = '#footer-widget-area-3 {display:none !important;}';
		}
		if ( '1' == $hide['footer4'] ) {
			$styles[] = '#footer-widget-area-4 {display:none !important;}';
		}

		// Content Areas
		// -------------
		// 1.9.5: separated display and templating override for thumbnail
		// 2.0.0: fix to breadcrumb trail targeting (was #breadcrumb)
		if ( $hide['image'] ) {
			$styles[] = 'div.thumbnail img {display:none !important;}';
		}
		if ( '1' == $hide['breadcrumb'] ) {
			$styles[] = '#content .breadcrumb-trail {display:none !important;}';
		}
		if ( '1' == $hide['title'] ) {
			$styles[] = '#content .entry-title {display:none !important;}';
		}
		if ( '1' == $hide['subtitle'] ) {
			$styles[] = '#content .entry-subtitle {display:none !important;}';
		}
		if ( '1' == $hide['metatop'] ) {
			$styles[] = '#content .entry-meta {display:none !important;}';
		}
		if ( '1' == $hide['metabottom'] ) {
			$styles[] = '#content .entry-utility {display:none !important;}';
		}
		if ( '1' == $hide['authorbio'] ) {
			$styles[] = '#content .entry-author {display:none !important;}';
		}
		if ( '1' == $hide['pagenavi'] ) {
			$styles[] = '#content #nav-below {display:none !important;}';
		}
	}

	// PerPost Custom Styles
	// ---------------------
	// 1.9.5: moved singular post check to here
	// 2.1.1: moved above override styles (for resource setting)
	$resource = false;
	$customstyles = '';
	if ( is_singular() ) {
		global $post;
		$resource = $post->ID;
		// 2.0.8: use prefixed post meta key
		$customstyles = get_post_meta( $resource, '_' . THEMEPREFIX . '_perpoststyles', true );

		// --- maybe convert old post meta key ---
		// 2.0.8: to add prefixed metakey
		if ( !$customstyles ) {
			$oldpostmeta = get_post_meta( $resource, '_perpoststyles', true );
			if ( $oldpostmeta ) {
				$customstyles = $oldpostmeta;
				delete_post_meta( $resource, '_perpoststyles' );
				update_post_meta( $resource, '_' . THEMEPREFIX . '_perpoststyles', $customstyles );
			}
		}
	} else {
		// 2.1.1: added filter for archive custom styles
		// 2.2.0: allow for archive custom post type styles
		$resource = bioship_muscle_get_archive_post();
		if ( $resource ) {
			$customstyles = get_post_meta( $resource, '_' . THEMEPREFIX . '_perpoststyles', true );
		} else {
			// --- backwards compatibility for filter ---
			$resource = 'archive';
		}
	}

	// --- filter override styles ---
	// 2.1.1: added missing style override filter
	// 2.2.0: added resource argument to filter
	$styles = bioship_apply_filters( 'muscle_override_style_array', $styles, $resource );
	$styles = implode( PHP_EOL, $styles );
	$styles = bioship_apply_filters( 'muscle_override_styles', $styles, $resource );

	// --- filter perpost styles ---
	// 2.1.1: added missing perpost style filter
	$customstyles = bioship_apply_filters( 'muscle_custom_styles', $customstyles, $resource );
	bioship_debug( "Custom Styles", $customstyles );

	// --- combine perpost overrides with custom styles ---
	// 2.2.0: added is_string check to customstyles
	if ( $customstyles && ( '' != $customstyles ) && is_string( $customstyles ) ) {
		$styles .= $customstyles . PHP_EOL;
	}

	// --- output styles ---
	if ( '' != $styles ) {
		// 2.2.0: use esc_html on custom styles output
		echo '<style>' . esc_html( $styles ) . '</style>';
	}
 }
}

// -----------------------------
// PerPost Thumbnail Size Filter
// -----------------------------
// 2.0.1: moved add_filter internally
if ( !function_exists( 'bioship_muscle_thumbnail_size_perpost' ) ) {

 // 2.1.1: added second argument for postid
 // 2.2.0: added missing arguement for post type
 add_filter( 'skeleton_post_thumbnail_size', 'bioship_muscle_thumbnail_size_perpost', 10, 3 );

 function bioship_muscle_thumbnail_size_perpost( $size, $postid = false, $posttype = false ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- get post ID if not supplied ---
	if ( !$postid ) {
		global $post;
		if ( isset( $post ) && is_object( $post ) ) {
			$postid = $post->ID;
		} else {
			return $size;
		}
	}

	// --- get thumbnail size override ---
	// 2.0.8: use prefixed post meta key
	$thumbsize = get_post_meta( $postid, '_' . THEMEPREFIX . '_thumbnailsize', true );

	// TODO: maybe double check thumbnail size is still available before using it?
	// $thumbsizes = array_merge( array( 'small', 'medium', 'large' ), get_intermediate_image_sizes() );
	if ( $thumbsize && ( '' != $thumbsize ) ) {
		return $thumbsize;
	}

	return $size;
 }
}

// ----------------------------
// Get Content Filter Overrides
// ----------------------------
// 1.8.0: separated to just get filter overrides
if ( !function_exists( 'bioship_muscle_get_content_filter_overrides' ) ) {
 function bioship_muscle_get_content_filter_overrides( $postid ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- get content filter overrides ---
	// 1.9.5: fix to remove filters metakey (previously _disablefilters)
	// 2.0.8: use prefixed post meta key
	$removefilters = get_post_meta( $postid, '_' . THEMEPREFIX . '_removefilters', true );

	// 2.0.8: maybe convert old post meta key
	if ( !$removefilters ) {
		$oldpostmeta = get_post_meta( $postid, '_removefilters', true );
		if ( $oldpostmeta ) {
			$removefilters = $oldpostmeta;
			delete_post_meta( $postid, '_removefilters' );
			update_post_meta( $postid, '_' . THEMEPREFIX . '_removefilters', $removefilters );
		}
	}

	// --- filter and return ---
	// 1.8.0: added this conditional filter
	// 2.1.1: added postid argument to filter
	$removefilters = bioship_apply_filters( 'muscle_content_filter_overrides', $removefilters, $postid );
	return $removefilters;
 }
}

// ----------------------------
// maybe Remove Content Filters
// ----------------------------
if ( !function_exists( 'bioship_muscle_remove_content_filters' ) ) {

 // note: runs early to maybe remove the filters
 add_filter( 'the_content', 'bioship_muscle_remove_content_filters', 9 );

 function bioship_muscle_remove_content_filters( $content ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- get content filters to remove ---
	global $post;
	if ( isset( $post ) && is_object( $post ) ) {
		$postid = $post->ID;
	} else {
		$postid = bioship_muscle_get_archive_post();
		if ( !$postid ) {
			return $content;
		}
	}

	// --- get filter override settings for post ---
	$remove = bioship_muscle_get_content_filter_overrides( $postid );
	if ( !$remove || !is_array( $remove ) ) {
		return $content;
	}

	// --- loop to remove content filters ---
	// 2.0.5: loop through possible filter array
	// TODO: check and match possible change in filter priority ?
	$filters = array( 'wpautop', 'wptexturize', 'convert_smilies', 'convert_chars' );
	foreach ( $filters as $filter ) {
		if ( isset( $remove[$filter] ) && ( '1' == $remove[$filter] ) ) {
			remove_filter( 'the_content', $filter );
		}
	}

	return $content;
 }
}


// ------------
// === MISC ===
// ------------

// -----------------------------
// maybe Change default Gravatar
// -----------------------------
// eg. /wp-content/child-theme/images/avatar.png
if ( !function_exists( 'bioship_muscle_default_gravatar' ) ) {

 add_filter( 'avatar_defaults', 'bioship_muscle_default_gravatar' );

 function bioship_muscle_default_gravatar( $defaults ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemedirs;
	if ( '' != $vthemesettings['gravatarurl'] ) {
		$avatar = $vthemesettings['gravatarurl'];
		$defaults[$avatar] = 'avatar';
	} else {
		$avatar = bioship_file_hierarchy( 'url', 'gravatar.png', $vthemedirs['image'] );
		if ( $avatar ) {
			$defaults[$avatar] = 'avatar';
		}
	}
	// TODO: cache default avatar image size and pass to skeleton_comments_avatar_size filter?
	return $defaults;
 }
}

// -------------------
// Classic Text Widget
// -------------------
// 2.0.8: copied from WP 4.7.5 for Discreet Text Widget basis
// (since WP 4.8 changes WP_Widget_Text class and breaks DiscreetTextWidget)
// 2.0.9: no classic text widget for WordPress.org version :-(
// (widgets classes need to be in plugins not themes in repository)
if ( !class_exists( 'WP_Widget_Classic_Text' ) && !THEMEWPORG ) {
 class WP_Widget_Classic_Text extends WP_Widget {

	// --- Construct ---
	public function __construct() {
		$widget_ops = array(
			'classname'						=> 'widget_text',
			'description'					=> esc_html( __( 'Arbitrary text or HTML.', 'bioship' ) ),
			'customize_selective_refresh'	=> true,
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'text', esc_html( __( 'Text','bioship' ) ), $widget_ops, $control_ops );
	}

	// --- Widget ---
	public function widget( $args, $instance ) {
		$title = empty( $instance['title'] ) ? '' : $instance['title'];
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$widget_text = !empty( $instance['text'] ) ? $instance['text'] : '';
		$text = apply_filters( 'widget_text', $widget_text, $instance, $this );
		// 2.2.0: use wp_kses_post on output
		echo wp_kses_post( $args['before_widget'] );
		if ( !empty( $title ) ) {
			// 2.2.0: use wp_kses_post on output
			echo wp_kses_post( $args['before_title'] );
				// 2.2.0: added missing esc_html to title
				echo esc_html( $title );
			// 2.2.0: use wp_kses_post on output
			echo wp_kses_post( $args['after_title'] );
		}
		echo '<div class="textwidget">';
			if ( !empty( $instance['filter'] ) ) {
				$text = wpautop( $text );
			}
			// 2.2.0: use wp_kses_post on output
			echo wp_kses_post( $text );
		echo '</div>' . PHP_EOL;
		// 2.2.0: use wp_kses_post on output
		echo wp_kses_post( $args['after_widget'] );
	}

	// --- Update ---
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = wp_kses_post( $new_instance['text'] );
		}
		$instance['filter'] = !empty( $new_instance['filter'] );
		return $instance;
	}

	// --- Form ---
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
		$title = sanitize_text_field( $instance['title'] );

		echo '<p>' . PHP_EOL;
			echo '<label for="' . esc_attr( $this->get_field_id( 'title' ) ) . '">' . esc_html( __( 'Title', 'bioship' ) ) . ':</label>' . PHP_EOL;
			echo '<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" />' . PHP_EOL;
		echo '</p><p>' . PHP_EOL;
			echo '<label for="' . esc_attr( $this->get_field_id( 'text' ) ) . '">' . esc_html( __( 'Content', 'bioship' ) ) . ':</label>' . PHP_EOL;
			echo '<textarea class="widefat" rows="16" cols="20" id="' . esc_attr( $this->get_field_id( 'text' ) ) . '" name="' . esc_attr( $this->get_field_name( 'text' ) ) . '">' . PHP_EOL;
			echo esc_textarea( $instance['text'] ) . '</textarea></p>' . PHP_EOL;
		echo '</p><p>' . PHP_EOL;
			echo '<input id="' . esc_attr( $this->get_field_id( 'filter' ) ) . '" name="' . esc_attr( $this->get_field_name( 'filter' ) ) . '" type="checkbox"' . checked( $filter ) . ' />&nbsp;' . PHP_EOL;
			echo '<label for="' . esc_attr( $this->get_field_id( 'filter' ) ) . '">' . esc_html( __( 'Automatically add paragraphs', 'bioship' ) ) . '</label>' . PHP_EOL;
		echo '</p>' . PHP_EOL;
	}
 }
}

// --------------------
// Discreet Text Widget
// --------------------
// most super useful widget, especially when used with shortcodes
// (so that if the shortcode returns empty the widget is not displayed)
// ref: https://wordpress.org/plugins/hackadelic-discreet-text-widget/
// 1.8.5: removed option, always on by default
if ( !function_exists( 'bioship_muscle_discreet_text_widget' ) ) {

 add_action( 'widgets_init', 'bioship_muscle_discreet_text_widget' );

 function bioship_muscle_discreet_text_widget() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 1.9.8: added class check (for no conflict with content sidebars plugin)
	// 2.0.9: no discreet text widget for WordPress.org version
	// (widgets classes need to be in plugins not themes in repository)
	if ( !class_exists( 'DiscreetTextWidget' ) && !THEMEWPORG ) {

		// 2.0.8: extend classic text widget class (since WP 4.8)
		class DiscreetTextWidget extends WP_Widget_Classic_Text {

			// 2.2.0: added missing public visibility
			public function __construct() {
				$widgetops = array(
					'classname'		=> 'discreet_text_widget',
					'description'	=> __( 'Arbitrary text or HTML, only shown if not empty.', 'bioship' ),
				);
				$controlops = array( 'width' => 400, 'height' => 350 );
				// 1.9.8: fix to deprecated class construction method
				// 2.0.7: fix to incorrect text domain (csidebars)
				call_user_func( array( get_parent_class( get_parent_class( $this ) ), '__construct' ), 'discrete_text', esc_html( __( 'Discreet Text', 'bioship' ) ), $widgetops, $controlops );
			}

			// 2.2.0: added missing public visibility
			public function widget( $args, $instance ) {
				// 1.9.8: removed usage of extract here
				$text = bioship_apply_filters( 'widget_text', $instance['text'] );
				if ( empty( $text ) ) {
					return;
				}

				// 2.2.0: wrap output in wp_kses_post
				echo wp_kses_post( $args['before_widget'] );
				$title = bioship_apply_filters( 'widget_title', $instance['title'] );
				if ( !empty( $title ) ) {
					// 2.2.0: wrap output in wp_kses_post
					echo wp_kses_post( $args['before_title'] );
						echo esc_html( $title );
					// 2.2.0: wrap output in wp_kses_post
					echo wp_kses_post( $args['after_title'] );
				}
				echo '<div class="textwidget">';
					if ( $instance['filter'] ) {
						$text = wpautop( $text );
					}
					// 2.2.0: wrap output in wp_kses_post
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo wp_kses_post( $text );
				echo '</div>';
				// 2.2.0: wrap output in wp_kses_post
				echo wp_kses_post( $args['after_widget'] );
			}
		}

		return register_widget( 'DiscreetTextWidget' );
	}
 }
}

// ----------------------------
// Fullscreen Video Background!
// ----------------------------
// What is this doing here? A client-abandoned feature. It works tho! :-)
// Currently works for YouTube videos only (TODO: could add Vimeo etc...)
// TODO: allow for HTML5 video uploaded to media library ?
// Ref: https://wordpress.stackexchange.com/a/325790/76440
// TODO: maybe add video background to Theme Options? currently via filters only
// (see filters.php example): muscle_videobackground_type,
// muscle_videobackground_id, muscle_videobackground_delay

// 1.9.8: fix to function_exists check (missing argument)
// 2.0.1: check themesettings internally to allow better filtering
if ( !function_exists( 'bioship_muscle_video_background' ) ) {

 add_action( 'bioship_before_navbar', 'bioship_muscle_video_background' );

 function bioship_muscle_video_background() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;

	// --- filter video background loading ---
	$load = isset( $vthemesettings['videobackground'] ) ? $vthemesettings['videobackground'] : false;
	$load = bioship_apply_filters( 'muscle_videobackground_type', $load );

	if ( 'youtube' == $load ) {

		// --- get video ID and delay ---
		$videoid = $videodelay = '';
		if ( isset( $vthemesettings['videobackgroundid'] ) ) {
			$videoid = $vthemesettings['videobackgroundid'];
		}
		$videoid = bioship_apply_filters( 'muscle_videobackground_id', $videoid );
		if ( isset( $vthemesettings['videobackgrounddelay'] ) ) {
			$videodelay = $vthemesettings['videobackgrounddelay'];
		}
		$videodelay = bioship_apply_filters( 'muscle_videobackground_delay', $videodelay );
		$videodelay = absint( $videodelay );
		if ( !is_numeric( $videodelay ) || ( $videodelay < 0 ) ) {
			$videodelay = 1000;
		}

		// --- output ID and delay values ---
		$maybe = array();
		preg_match( "/[a-zA-Z0-9]+//", $videoid, $maybe );
		if ( ( '' != $videoid ) && ( $videoid == $maybe[0] ) ) {
			echo '<div id="backgroundvideowrapper">';
				echo '<input type="hidden" id="videobackgroundoid" value="' . esc_attr( $videoid ) . '">';
				echo '<input type="hidden" id="videobackgrounddelay" value="' . esc_attr( $videodelay ) . '">';
				echo '<div id="backgroundvideo"></div>';
			echo '</div>';
		}
	}
 }
}


// ---------------
// === Scripts ===
// ---------------

// ---------------------------------
// Internet Explorer Support Scripts
// ---------------------------------
// - Selectivizr (CSS3, for IE6 to IE8 inclusive)
// - HTML5 Shiv (HTML5, for less than IE9)
// - Supersleight (transparent PNGs, for IE6 and below)
// - Flexibiliity (flexbox support for IE8+9)
// 1.5.0: moved to skeleton_internet_explorer_scripts hook
// 1.8.0: added flexibility (flexbox polyfill)
// 2.0.1: added individual loading filters
if ( !function_exists( 'bioship_muscle_internet_explorer_scripts' ) ) {

 add_action( 'wp_head', 'bioship_muscle_internet_explorer_scripts' );

 function bioship_muscle_internet_explorer_scripts() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemedirs, $vjscachebust;
	$iesupports = $vthemesettings['iesupports'];

	// --- check for file modified cachebusting ---
	// 2.0.9: fix for undefined variable warning
	$filemtime = ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) ? true : false;

	// --- check script debug constant ---
	// 2.1.3: honour SCRIPT_DEBUG constant for unminified scripts
	// 2.2.0: check only once for all IE support scripts
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// --- set cachebust default ---
	$cachebust = $vjscachebust;

	// Selectivizr CSS3
	// ----------------
	// 2.0.1: added loading filter
	$load = ( isset( $iesupports['selectivizr'] ) && ( '1' == $iesupports['selectivizr'] ) ) ? true : false;
	$load = bioship_apply_filters( 'muscle_load_selectivizr', $load );
	if ( $load ) {
		$selectivizr = bioship_file_hierarchy( 'both', 'selectivizr' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $selectivizr ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( $filemtime ) {
				$cachebust = date( 'ymdHi', filemtime( $selectivizr['file'] ) );
			}
			echo '<!--[if (gte IE 6)&(lte IE 8)]><script src="' . esc_url( $selectivizr['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script><![endif]-->';
		}
	}

	// HTML5 Shiv
	// ----------
	// 2.0.1: added loading filter
	$load = ( isset( $iesupports['html5shiv'] ) && ( '1' == $iesupports['html5shiv'] ) ) ? true : false;
	$load = bioship_apply_filters( 'muscle_load_html5shiv', $load );
	if ( $load ) {
		$html5 = bioship_file_hierarchy( 'both', 'html5' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $html5 ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( $filemtime ) {
				$cachebust = date( 'ymdHi', filemtime( $html5['file'] ) );
			}
			// TODO: check if possible to wrap with IE tags if enqueued
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			echo '<!--[if lt IE 9]><script src="' . esc_url( $html5['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script><![endif]-->';
		}
	}

	// Supersleight
	// ------------
	// 2.0.1: added loading filter
	$load = ( isset( $iesupports['supersleight'] ) && ( '1' == $iesupports['supersleight'] ) ) ? true : false;
	$load = bioship_apply_filters( 'muscle_load_supersleight', $load );
	if ( $load ) {
		$supersleight = bioship_file_hierarchy( 'both', 'supersleight.js', $vthemedirs['script'] );
		if ( is_array( $supersleight ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( $filemtime ) {
				$cachebust = date( 'ymdHi', filemtime( $supersleight['file'] ) );
			}
			// TODO: check if possible to wrap with IE tags if enqueued
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			echo '<!--[if lte IE 6]><script src="' . esc_url( $supersleight['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script><![endif]-->';
		}
	}

	// IE8 DOM
	// -------
	// 1.8.5: added IE8 DOM polyfill
	// 2.0.1: added loading filter
	$load = ( isset( $iesupports['ie8'] ) && ( '1' == $iesupports['ie8'] ) ) ? true : false;
	$load = bioship_apply_filters( 'muscle_load_ie8dom', $load );
	if ( $load ) {
		$ie8 = bioship_file_hierarchy( 'both', 'ie8' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $ie8 ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( $filemtime ) {
				$cachebust = date( 'ymdHi', filemtime( $ie8['file'] ) );
			}
			// TODO: check if possible to wrap with IE tags if enqueued
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			echo '<!--[if IE 8]><script src="' . esc_url( $ie8['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script><![endif]-->';
		}
	}

	// Flexibility
	// -----------
	// 1.8.0: added flexbox polyfill
	// 2.0.1: added loading filter
	// 2.2.0: fix default load false not set
	$load = ( isset( $iesupports['flexibility'] ) && ( '1' == $iesupports['flexibility'] ) ) ? true : false;
	$load = bioship_apply_filters( 'muscle_load_flexibility', $load );
	if ( $load ) {
		$flexibility = bioship_file_hierarchy( 'both', 'flexibility.js', $vthemedirs['script'] );
		if ( is_array( $flexibility ) ) {
			if ( $filemtime ) {
				$cachebust = date( 'ymdHi', filemtime( $flexibility['file'] ) );
			}
			// TODO: check if possible to wrap with IE tags if enqueued
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			echo '<!--[if (IE 8)|(IE 9)]><script src="' . esc_url( $flexibility['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script><![endif]-->';
		}
	}

 }
}

// ----------
// PrefixFree
// ----------
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_prefixfree' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_prefixfree' );

 function bioship_muscle_load_prefixfree() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vjscachebust, $vthemedirs;
	$load = isset( $vthemesettings['prefixfree'] ) ? $vthemesettings['prefixfree'] : false;
	$load = bioship_apply_filters( 'muscle_load_prefixfree', $load );
	if ( !$load ) {
		return;
	}

	// --- load PrefixFree ---
	// 2.1.3: honour SCRIPT_DEBUG constant
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$prefixfree = bioship_file_hierarchy( 'both', 'prefixfree' . $suffix . '.js', $vthemedirs['script'] );
	if ( is_array( $prefixfree ) ) {

		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $prefixfree['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'prefixfree', $prefixfree['url'], array(), $cachebust, true );

		add_filter( 'style_loader_tag', 'bioship_muscle_fonts_noprefix_attribute', 10, 2 );
	}
 }
}

// ----------------------------
// PrefixFree Script Tag Filter
// ----------------------------
// 1.5.0: fix for Prefixfree and Google Fonts CORS conflict (a "WTF" bug!)
// 2.2.0: fix to typo mismatching for function_exists check
// 2.2.0: move function out of prefixfree loading function
// ref: http://stackoverflow.com/questions/25694456/google-fonts-giving-no-access-control-allow-origin-header-is-present-on-the-r
// ref: http://wordpress.stackexchange.com/questions/176077/add-attribute-to-link-tag-thats-generated-through-wp-register-style
// ref: https://github.com/LeaVerou/prefixfree/pull/39
if ( !function_exists( 'bioship_muscle_fonts_noprefix_attribute' ) ) {
 function bioship_muscle_fonts_noprefix_attribute( $tag, $handle ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	$original = $tag;

	// note: Google fonts style handles are 'heading-font-'x or 'custom-font-'x
	// 2.0.9: stricter checking for handle at start of string
	if ( 0 === ( strpos( $handle, 'heading-font-' ) ) || ( 0 === strpos( $handle, 'custom-font-' ) ) ) {
		$tag = str_replace( '/>', 'data-noprefix />', $tag );
	} else {
		// ...and a basic check for if the link is external to the site
		// as this problem could occur for other external sheets like this
		if ( !stristr( $tag, $_SERVER['HTTP_HOST'] ) ) {
			// 2.0.9: use stricter checking for http at start of string
			// 2.1.2: fix this check at this is a full tag not just an URL
			$pos = strpos( $tag, 'href=' );
			if ( ( 'http://' == substr( $tag, ( $pos + 6 ), strlen( 'http://' ) ) )
				|| ( 'https://' == substr( $tag, ( $pos + 6 ), strlen( 'https://' ) ) )
				|| ( '//' == substr( $tag, ( $pos + 6 ), strlen( '//' ) ) ) ) {
				$tag = str_replace( '/>', 'data-noprefix />', $tag );
			}
		}
	}
	if ( $original != $tag ) {
		bioship_debug( "No PrefixFree for Style", $handle );
	}
	return $tag;
 }
}

// -----------------------------
// NWWatcher Selector Javascript
// -----------------------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_nwwatcher' ) ) {

 // 2.2.0: disabled automatic enqueueing of NWWatcher
 // add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_nwwatcher' );

 function bioship_muscle_load_nwwatcher() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['nwwatcher'] ) ? $vthemesettings['nwwatcher'] : false;
	$load = bioship_apply_filters( 'muscle_load_nwwatcher', $load );

	// --- load NW Watcher ---
	$nwwatcher = bioship_file_hierarchy( 'both', 'nwwatcher.js', $vthemedirs['script'] );
	if ( is_array( $nwwatcher ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $nwwatcher['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		// 2.2.0: register script for NWEvents dependency
		wp_register_script( 'nwwatcher', $nwwatcher['url'], array(), $cachebust, true );
	}

	if ( $load ) {
		wp_enqueue_script( 'nwwatcher' );
	}
 }
}

// --------------------------------------------
// NWEvents Event Manager (NWWatcher dependent)
// --------------------------------------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'muscle_load_nwevents' ) ) {

 // 2.2.0: disabled automatic enqueueing of NWEvents
 // add_action( 'wp_enqueue_scripts','bioship_muscle_load_nwevents' );

 function bioship_muscle_load_nwevents() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['nwevents'] ) ? $vthemesettings['nwevents'] : false;
	$load = bioship_apply_filters( 'muscle_load_nwevents', $load );
	if ( !$load ) {
		return;
	}

	// --- load NW Events ---
	$nwevents = bioship_file_hierarchy( 'both', 'nwevents.js', $vthemedirs['script'] );
	if ( is_array( $nwevents ) ) {
		// 2.1.1: fix to cachebusting conditions and mismatched filename (prefixfree)
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $nwevents['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		// 2.1.1: fix to missing URL key
		wp_enqueue_script( 'nwevents', $nwevents['url'], array( 'nwwatcher' ), $cachebust, true );
	}
 }
}

// ---------------------
// Media Queries Support
// ---------------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_media_queries_script' ) ) {

 // note enqueue exception: apparently for these the "best place is in the footer"
 add_action( 'wp_footer', 'bioship_muscle_media_queries_script' );

 function bioship_muscle_media_queries_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['mediaqueries'] ) ? $vthemesettings['mediaqueries'] : false;
	$load = bioship_apply_filters( 'muscle_load_mediaqueries', $load );
	// 2.0.2: fix to simplified load variable typo
	if ( !$load || ( 'off' == $load ) ) {
		return;
	}

	// --- load Respond or Media Queries --
	if ( 'respond' == $vthemesettings['mediaqueries'] ) {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$respond = bioship_file_hierarchy( 'both', 'respond' . $suffix . '.js', $vthemedirs['script'] );
		if ( is_array( $respond ) ) {
			if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
				$cachebust = date( 'ymdHi', filemtime( $respond['file'] ) );
			} else {
				$cachebust = $vjscachebust;
			}
			// 2.2.0: use wp_enqueue_script instead of direct tag
			// echo '<script src="' . esc_url( $respond['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script>';
			wp_enqueue_script( 'respond', $respond['url'], array(), $cachebust, true );
		}
	} elseif ( 'mediaqueries' == $vthemesettings['mediaqueries'] ) {
		$mediaqueries = bioship_file_hierarchy( 'both', 'css3-mediaqueries.js', $vthemedirs['script'] );
		if ( is_array( $mediaqueries ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
				$cachebust = date( 'ymdHi', filemtime( $mediaqueries['file'] ) );
			} else {
				$cachebust = $vjscachebust;
			}
			// 2.2.0: use wp_enqueue_script instead of direct tag
			// echo '<script src="' . esc_url( $mediaqueries['url'] ) . '?ver=' . esc_attr( $cachebust ) . '"></script>';
			wp_enqueue_script( 'media-queries', $mediaqueries['url'], array(), $cachebust, true );
		}
	}
 }
}

// --------------
// Load FastClick
// --------------
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_fastclick' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_fastclick' );

 function bioship_muscle_load_fastclick() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadfastclick'] ) ? $vthemesettings['loadfastclick'] : false;
	$load = bioship_apply_filters( 'muscle_load_fastclick', $load );
	if ( !$load ) {
		return;
	}

	// --- load FastClick ---
	// 1.8.5: adding missing filemtime cachebusting option
	$fastclick = bioship_file_hierarchy( 'both', 'fastclick.js', $vthemedirs['script'] );
	if ( is_array( $fastclick ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $fastclick['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'fastclick', $fastclick['url'], array( 'jquery' ), $cachebust, true );
	}
 }
}

// ---------------
// Load Mousewheel
// ---------------
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_mousewheel' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_mousewheel' );

 function bioship_muscle_load_mousewheel() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	// 2.0.1: fix to reused code typo in filter variable
	$load = isset( $vthemesettings['loadmousewheel'] ) ? $vthemesettings['loadmousewheel'] : false;
	$load = bioship_apply_filters( 'muscle_load_mousewheel', $load );
	if ( !$load ) {
		return;
	}

	// --- load MouseWheel ---
	// 1.9.0: fix to file hierarchy call (both not url)
	$mousewheel = bioship_file_hierarchy( 'both', 'mousewheel.js', $vthemedirs['script'] );
	if ( is_array( $mousewheel ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $mousewheel['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'mousewheel', $mousewheel['url'], array( 'jquery' ), $cachebust, true );
	}
 }
}

// -----------------
// Load CSS.Supports
// -----------------
// 2.0.1: check themeoptions internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_csssupports' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_csssupports' );

 function bioship_muscle_load_csssupports() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadcsssupports'] ) ? $vthemesettings['loadcsssupports'] : false;
	$load = bioship_apply_filters( 'muscle_load_csssupports', $load );
	if ( !$load ) {
		return;
	}

	// --- load CSS Supports ---
	// 2.1.3: honour SCRIPT_DEBUG constant
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$csssupports = bioship_file_hierarchy( 'url', 'CSS.supports' . $suffix . '.js', $vthemedirs['script'] );
	if ( is_array( $csssupports ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $csssupports['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'csssupports', $csssupports, array(), $cachebust, true );
	}
 }
}

// ----------
// MatchMedia
// ----------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_match_media_script' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_match_media_script' );

 function bioship_muscle_match_media_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	// 2.0.1: fix to old themeoptions global typo
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadmatchmedia'] ) ? $vthemesettings['loadmatchmedia'] : false;
	$load = bioship_apply_filters( 'muscle_load_matchmedia', $load );
	if ( !$load ) {
		return;
	}

	// --- load MatchMedia ---
	// 1.9.5: fixed to file hierarchy call
	$matchmedia = bioship_file_hierarchy( 'both', 'matchMedia.js', $vthemedirs['script'] );
	if ( is_array( $matchmedia ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $matchmedia['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'matchmedia', $matchmedia['url'], array( 'jquery' ), $cachebust, true );

		// 1.9.5: fixed to file hierarchy call
		$matchmedialistener = bioship_file_hierarchy( 'both', 'matchMedia.addListener.js', $vthemedirs['script'] );
		if ( is_array( $matchmedialistener ) ) {
			// 2.1.1: fix to cachebusting conditions
			if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
				$cachebust = date( 'ymdHi', filemtime( $matchmedialistener['file'] ) );
			} else {
				$cachebust = $vjscachebust;
			}
			wp_enqueue_script( 'matchmedialistener', $matchmedialistener['url'], array( 'jquery', 'matchmedia' ), $cachebust, true );
		}
	}
 }
}

// --------------
// Load Modernizr
// --------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_modernizr' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_modernizr' );

 function bioship_muscle_load_modernizr() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemevars, $vjscachebust;
	$load = isset( $vthemesettings['load'] ) ? $vthemesettings['loadmodernizr'] : false;
	$load = bioship_apply_filters( 'muscle_load_modernizr', $load );
	// 2.0.2: fix to simplified variable typo
	if ( !$load || ( 'off' == $load ) ) {
		return;
	}

	// --- load Modernizr ---
	// 2.0.1: use filtered value here also
	// 2.5.0: remove foundation directories from hierarchies
	// TODO: allow for alternative includes/scripts directories ?
	if ( 'production' == $load ) {
		// (with fallback to development version)
		$dirs = array( 'javascripts', 'js', 'assets/js' );
		$modernizr = bioship_file_hierarchy( 'both', 'modernizr.js', $dirs );
	} elseif ( 'development' == $load ) {
		// (with fallback to production version)
		$dirs = array( 'javascripts', 'js', 'assets/js' );
		$modernizr = bioship_file_hierarchy( 'both', 'modernizr.js', $dirs );
	}
	if ( is_array( $modernizr ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $modernizr['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'modernizr', $modernizr['url'], array( 'jquery' ), $cachebust, true );
		// 2.0.9: add javascript variable to global to auto-initialize modernizr
		// 2.1.3: add to prefixed global settings variable
		$vthemevars[] = "bioship.loadmodernizr = 'yes'; ";
	}
 }
}


// --------------
// === Extras ===
// --------------

// ------------------
// Load Sticky Navbar
// ------------------
// 2.2.0: added sticky navigation bar load check
if ( !function_exists( 'bioship_muscle_sticky_navbar' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_sticky_navbar' );

 function bioship_muscle_sticky_navbar() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load for nav bar---
	global $vthemesettings, $vthemevars;
	$load = isset( $vthemesettings['stickynavbar'] ) ? $vthemesettings['stickynavbar'] : false;
	$load = bioship_apply_filters( 'muscle_sticky_navbar', $load );
	if ( !$load ) {
		return;
	}

	// --- add run trigger to footer ---
	// (detected by bioship-init.js)
	$vthemevars[] = "bioship.stickynavbar = 'yes'; ";
 }
}
// ----------------
// Load Sticky Logo
// ----------------
// 2.2.0: added sticky logo load check
if ( !function_exists( 'bioship_muscle_sticky_logo' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_sticky_logo' );

 function bioship_muscle_sticky_logo() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load for stick logo ---
	global $vthemesettings, $vthemevars;
	$load = isset( $vthemesettings['stickylogo'] ) ? $vthemesettings['stickylogo'] : false;
	$load = bioship_apply_filters( 'muscle_sticky_logo', $load );
	if ( !$load ) {
		return;
	}

	// --- add run trigger to footer ---
	// (detected by bioship-init.js)
	$vthemevars[] = "bioship.stickylogo = 'yes'; ";
 }
}

// ---------------------
// Load Smooth Scrolling
// ---------------------
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_smooth_scrolling' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_smooth_scrolling' );

 function bioship_muscle_smooth_scrolling() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemevars;
	$load = isset( $vthemesettings['smoothscrolling'] ) ? $vthemesettings['smoothscrolling'] : false;
	$load = bioship_apply_filters( 'muscle_smooth_scrolling', $load );
	if ( !$load ) {
		return;
	}

	// --- add run trigger to footer ---
	// (detected by bioship-init.js)
	// 2.0.9: use theme load variables instead of input field
	// 2.1.3: add to prefixed global settings variable
	$vthemevars[] = "bioship.smoothscrolling = 'yes'; ";
 }
}

// -----------------------
// Load jQuery matchHeight
// -----------------------
// 1.9.9: added this for content grid (and other) usage
if ( !function_exists( 'bioship_muscle_load_matchheight' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_matchheight' );

 function bioship_muscle_load_matchheight() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadmatchheight'] ) ? $vthemesettings['loadmatchheight'] : false;
	$load = bioship_apply_filters( 'muscle_load_matchheight', $load );
	if ( !$load ) {
		return;
	}

	// --- load MatchHeight ---
	// 2.1.3: honour SCRIPT_DEBUG constant for unminified scripts
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$matchheight = bioship_file_hierarchy( 'both', 'jquery.matchHeight' . $suffix . '.js', $vthemedirs['script'] );
	if ( is_array( $matchheight ) ) {
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $matchheight['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'matchheight', $matchheight['url'], array( 'jquery' ), $cachebust, true );

		// --- add run trigger to footer ---
		// (detected by bioship-init.js)
		// 2.0.9: use theme load variables instead of input field
		// 2.1.3: add to prefixed global settings variable
		$vthemevars[] = "bioship.loadmatchheights = 'yes'; ";
	}
 }
}

// ---------------
// Load Sticky Kit
// ---------------
// 1.5.0: Added Sticky Kit Loading
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_stickykit' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_stickykit' );

 function bioship_muscle_load_stickykit() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 1.8.5: seems to cause customizer some troubles
	// 1.9.9: added pagenow check also for same reason
	global $pagenow;
	if ( ( 'customizer.php' == $pagenow ) || is_customize_preview() ) {
		return;
	}

	// --- check load ---
	// 1.9.9: fix to incorrect filter name typo
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadstickykit'] ) ? $vthemesettings['loadstickykit'] : false;
	$load = bioship_apply_filters( 'muscle_load_stickykit', $load );
	if ( !$load ) {
		return;
	}

	// --- load Sticky Kit ---
	// 2.1.3: honour SCRIPT_DEBUG constant for unminified scripts
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$stickykit = bioship_file_hierarchy( 'both', 'jquery.sticky-kit' . $suffix . '.js', $vthemedirs['script'] );
	if ( is_array( $stickykit ) ) {
		// 2.1.1: fix to cachebusting conditions
		// 2.1.3: moved from footer as was loaded after bioship-init.js
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $stickykit['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'stickykit', $stickykit['url'], array( 'jquery' ), $cachebust, true );

		// --- set Sticky Kit elements ---
		// 2.0.9: set stickykit elements array variable instead of input field
		// 2.1.1: fix to themesettings variable typo
		$stickyelements = bioship_apply_filters( 'muscle_sticky_elements', $vthemesettings['stickyelements'] );
		if ( !$stickyelements ) {
			return;
		}
		// 2.2.0: added trim to be safe
		$stickyelements = trim( $stickyelements );
		if ( is_string( $stickyelements ) ) {
			if ( '' == $stickyelements ) {
				return;
			} elseif ( strstr( $stickyelements, ',' ) ) {
				$stickyelements = explode( ',', $stickyelements );
			} else {
				$stickyelements = array( $stickyelements );
			}
		}
		if ( !is_array( $stickyelements ) || ( count( $stickyelements ) < 1 ) ) {
			return;
		}

		// 2.1.3: add to prefixed global settings variable
		$scriptvar = "bioship.stickyelements = new Array(); ";
		foreach ( $stickyelements as $i => $element ) {
			$scriptvar .= "bioship.stickyelements[" . $i . "] = '" . esc_js( trim( $element ) ) . "'; ";
		}
		$vthemevars[] = $scriptvar;
	}
 }
}

// ------------
// Load FitVids
// ------------
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_fitvids' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_fitvids' );

 function bioship_muscle_load_fitvids() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemevars, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadfitvids'] ) ? $vthemesettings['loadfitvids'] : false;
	$load = bioship_apply_filters( 'muscle_load_fitvids', $load );
	if ( !$load ) {
		return;
	}

	// --- load FitVids ---
	$fitvids = bioship_file_hierarchy( 'both', 'jquery.fitvids.js', $vthemedirs['script'] );
	if ( is_array( $fitvids ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $fitvids['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'fitvids', $fitvids['url'], array( 'jquery' ), $cachebust, true );

		// --- set FitVids elements ---
		// 2.0.9: set fitvids elements array variable instead of input field
		$fitvidselements = bioship_apply_filters( 'muscle_fitvids_elements', $vthemesettings['fitvidselements'] );
		if ( !$fitvidselements ) {
			return;
		}
		// 2.2.0: added trim to be safe
		$fitvidselements = trim( $fitvidselements );
		if ( is_string( $fitvidselements ) ) {
			if ( '' == $fitvidselements ) {
				return;
			} elseif ( strstr( $fitvidselements, ',' ) ) {
				$fitvidselements = explode( ',', $fitvidselements );
			} else {
				$fitvidselements = array( $fitvidselements );
			}
		}
		if ( !is_array( $fitvidselements ) || ( count( $fitvidselements ) < 1 ) ) {
			return;
		}

		// --- add to script variables global ---
		// 2.1.3: add to prefixed global settings variable
		$scriptvar = "bioship.fitvidselements = new Array(); ";
		foreach ( $fitvidselements as $i => $element ) {
			$scriptvar .= "bioship.fitvidselements[" . $i . "] = '" . esc_js( trim( $element ) ) . "'; ";
		}
		$vthemevars[] = $scriptvar;
	}
 }
}

// ------------------
// Load ScrollToFixed
// ------------------
// 1.5.0: added Scroll To Fixed library
// 1.8.5: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_load_scrolltofixed' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_scrolltofixed' );

 function bioship_muscle_load_scrolltofixed() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings, $vthemedirs, $vjscachebust;
	$load = isset( $vthemesettings['loadscrolltofixed'] ) ? $vthemesettings['loadscrolltofixed'] : false;
	$load = bioship_apply_filters( 'muscle_load_scrolltofixed', $load );
	if ( !$load ) {
		return;
	}

	// --- load ScrollToFixed ---
	$scrolltofixed = bioship_file_hierarchy( 'both', 'jquery-scrolltofixed.min.js', $vthemedirs['script'] );
	if ( is_array( $scrolltofixed ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $scrolltofixed['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'scrolltofixed', $scrolltofixed['url'], array( 'jquery' ), $cachebust, true );
	}
 }
}

// ------------------
// Logo Resize Switch
// ------------------
// 1.8.5: added this input switch for init.js
if ( !function_exists( 'bioship_muscle_logo_resize' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_logo_resize' );

 function bioship_muscle_logo_resize() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

    // --- check load ---
    global $vthemesettings, $vthemevars;
    $load = isset( $vthemesettings['logoresize'] ) ? $vthemesettings['logoresize'] : false;
    $load = bioship_apply_filters( 'muscle_logo_resize', $load );
    if ( !$load ) {
    	return;
    }

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme load variables instead of input field
    // 2.1.3: add to prefixed global settings variable
    $vthemevars[] = "bioship.logoresize = 'yes'; ";
 }
}

// -----------------------
// Site Text Resize Switch
// -----------------------
// 2.0.9: expermental feature for bioship-init.js
if ( !function_exists( 'bioship_muscle_site_text_resize' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_site_text_resize' );

 function bioship_muscle_site_text_resize() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

    // --- check load ---
    global $vthemesettings, $vthemelayout, $vthemevars;
    $load = isset( $vthemesettings['sitetextresize'] ) ? $vthemesettings['sitetextresize'] : false;
    $load = bioship_apply_filters( 'muscle_site_text_resize', $load );
    if ( !$load ) {
    	return;
    }

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
    // 2.1.3: add to prefixed global settings variable
    $vthemevars[] = "bioship.sitetextresize = 'yes'; ";
 }
}

// --------------------
// Header Resize Switch
// --------------------
// 2.0.9: added prototype feature for bioship-init.js
if ( !function_exists( 'bioship_muscle_header_resize' ) ) {

 // 2.2.0: move action from wp_footer to wp_enqueue scripts
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_header_resize' );

 function bioship_muscle_header_resize() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

    // --- check load ---
    global $vthemesettings, $vthemelayout, $vthemevars;
    $load = isset( $vthemesettings['headerresize'] ) ? $vthemesettings['headerresize'] : false;
    $load = bioship_apply_filters( 'muscle_header_resize', $load );
    if ( !$load ) {
    	return;
    }

    // --- add run trigger to footer ---
    // (detected by bioship-init.js)
    // 2.0.9: use theme script variables instead of input field
    // 2.1.3: add to prefixed global settings variable
	$vthemevars[] = "bioship.headerresize = 'yes'; ";
 }
}

// -------------------
// Contrast Mode Check
// -------------------
// 2.2.0: added prototype check for contrast mode
if ( !function_exists( 'bioship_muscle_dark_mode_check' ) ) {

 add_action( 'wp_enqueue_scripts', 'bioship_muscle_dark_mode_check' );

 function bioship_muscle_dark_mode_check() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemevars;
	if ( isset( $vthemesettings['contrastmode'] ) ) {
		if ( 'light' == $vthemesettings['contrastmode'] ) {
			$js = "bioship.contrastmode = 'light';";
		} elseif ( 'dark' == $vthemesettings['contrastmode'] ) {
			$js = "bioship.contrastmode = 'dark';";
		} elseif ( 'default' == $vthemesettings['contrastmode'] ) {
			$js = "dark = window.matchMedia('(prefers-color-scheme: dark)').matches;" . PHP_EOL;
			$js .= "if (dark) {bioship.contrastmode = 'dark';} else {bioship.contrastmode = 'default';}";
		}
		if ( isset( $js ) ) {
    		$vthemevars[] = $js;

    		// TODO: add contrast mode body class on load ?
		}
	}
 }
}


// -------------------------------
// Output Script Loading Variables
// -------------------------------
// 2.0.9: added for outputting script load variables (for bioship-init.js)
if ( !function_exists( 'bioship_muscle_output_script_vars' ) ) {

 // 2.2.0: enqueue script variables instead of adding in footer
 // add_action( 'wp_footer', 'bioship_muscle_output_script_vars', 11 );
 add_action( 'wp_enqueue_scripts', 'bioship_muscle_output_script_vars', 11 );

 function bioship_muscle_output_script_vars() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- set script variables ---
 	global $vthemevars, $vthemelayout;
 	// 2.1.1: always output maxwidth at minimum
	// 2.2.0: set javascript debug variable
	$vthemevars[] = "bioship.maxwidth = '" . $vthemelayout['maxwidth'] . "'; ";
	if ( THEMEDEBUG ) {
		$themevars[] = 'bioship.debug = true;';
	} else {
		$vthemevars[] = 'bioship.debug = false;';
	}

 	// --- and filter script variables ---
	// 2.1.1: add filter for script variables before output
	$scriptvars = bioship_apply_filters( 'muscle_script_vars', $vthemevars );

 	// --- output theme script variables ---
 	// 2.1.3: use global settings object
	// 2.2.0: set javascript instead of echo
	$js = "var bioship = {}; ";

	// 2.2.0: check vars instead of early return to ensure object is defined
 	if ( is_array( $scriptvars ) || ( count( $scriptvars ) > 0 ) ) {
		foreach ( $scriptvars as $scriptvar ) {
			$js .= $scriptvar;
		}
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		// echo "<script>" . $js . "</script>";
	}

	// 2.2.0: use add inline script to prepend vars to bioship-init.js
	wp_add_inline_script( THEMESLUG . '-init', $js, 'before' );
 }
}


// ------------------
// === Thumbnails ===
// ------------------

// -------------------
// JPEG Quality Filter
// -------------------
// 2.0.5: added a jpeg quality filter
if ( !function_exists( 'bioship_muscle_jpeg_quality' ) ) {

 add_filter( 'jpeg_quality', 'bioship_muscle_jpeg_quality', 10, 2 );

 // note: context = image_resize or edit_image
 function bioship_muscle_jpeg_quality( $quality, $context ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemesettings;
	if ( isset( $vthemesettings['jpegquality'] ) ) {
	    // 2.2.0: fix to absint application
		$newquality = absint( $vthemesettings['jpegquality'] );
        if ( ( $newquality > 0 ) && ( $newquality < 101 ) ) {
            $quality = $newquality;
        }
	}
	return $quality;
 }
}

// ----------------------------
// CPT Thumbnail Size Overrides
// ----------------------------
// (note: post type support for the CPT must be active via theme options)
// each filter must be explicity set, ie. muscle_post_type_thumbsize_{size}
// ref: http://wordpress.stackexchange.com/questions/6103/change-set-post-thumbnail-size-according-to-post-type-admin-page
if ( !function_exists( 'bioship_muscle_thumbnail_size_custom' ) ) {

 add_filter( 'intermediate_image_sizes_advanced', 'bioship_muscle_thumbnail_size_custom', 10 );

 function bioship_muscle_thumbnail_size_custom( $sizes ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- get post ID and type ---
	// 2.2.0: check for global post object first
	global $post;
	if ( is_object( $post ) ) {
		$posttype = $post->post_type;
	} elseif ( isset( $_REQUEST['post_id'] ) ) {
		// for the admin post/page editing screen
		$postid = absint( $_REQUEST['post_id'] );
		$posttype = get_post_type( $postid );
	} elseif ( isset( $_REQUEST['post_type'] ) ) {
		// 2.2.0: added for add new post type page
		$posttype = sanitize_text_field( $_REQUEST['post_type'] );
	} else {
		$posttype = '';
	}

	// --- get default thumbnail size options ---
	// (as in theme setup)
	global $vthemesettings;
	$thumbnailwidth = bioship_apply_filters( 'skeleton_thumbnail_width', 250 );
	$thumbnailheight = bioship_apply_filters( 'skeleton_thumbnail_height', 250 );

	// --- get cropping options ---
	$crop = get_option( 'thumbnail_crop' );
	$thumbnailcrop = $vthemesettings['thumbnailcrop'];
	if ( 'nocrop' == $thumbnailcrop ) {
		$crop = false;
	}
	if ( 'auto' == $thumbnailcrop ) {
		$crop = true;
	}
	if ( strstr( $thumbnailcrop, '-' ) ) {
		$crop = explode( '-', $thumbnailcrop );
	}
	$thumbsize = array( $thumbnailwidth, $thumbnailheight, $crop );

	// --- filter for this post type ---
	// 2.2.0: added filter for if post type undetectable
	if ( '' == $posttype ) {
		$newthumbsize = bioship_apply_filters( 'muscle_post_type_thumbsize', $thumbsize );
	} else {
		$newthumbsize = bioship_apply_filters( 'muscle_post_type_thumbsize_' . $posttype, $thumbsize );
	}
	if ( $thumbsize != $newthumbsize ) {
		if ( ( is_numeric( $newthumbsize[0] ) ) && ( is_numeric( $newthumbsize[1] ) ) ) {
			$thumbsize = $newthumbsize;
		}
	}

	// --- set explicitly whether default or changed ---
	$sizes['post-thumbnail'] = array(
		'width' => $thumbsize[0], 'height' => $thumbsize[1], 'crop' => $thumbsize[2],
	);
    return $sizes;
 }
}

// --------------------------
// Fun with Fading Thumbnails
// --------------------------
// TODO: retest and add this feature to theme options ?
if ( !function_exists( 'bioship_muscle_fading_thumbnails' ) ) {

 // 2.2.0: disabled this feature for review
 // add_filter('the_posts', 'bioship_muscle_fading_thumbnails', 10, 2);

 function bioship_muscle_fading_thumbnails( $posts, $query ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

 	if ( !is_archive() ) {
 		return $posts;
 	}

 	// TODO: add fading thumbnails loading filter here ?

	$cptslug = 'post';
	$fadingthumbs = false;
	$posttypes = bioship_get_post_types( $query );
	if ( is_array( $posttypes ) && in_array( $cptslug, $posttypes ) ) {
		$fadingthumbs = true;
	} elseif ( !is_array( $posttypes ) && ( $cptslug == $posttypes ) ) {
		$fadingthumbs = true;
	}

	if ( $fadingthumbs ) {
	    global $vthemelayout;
	    $vthemelayout['fadingthumbnails'] = $cptslug;
	    // 2.1.1: fix to function name type had_action
	    if ( !has_action( 'wp_footer', 'bioship_muscle_fading_thumbnail_script' ) ) {
	    	add_action( 'wp_footer', 'bioship_muscle_fading_thumbnail_script' );
	    }
	}

	return $posts;
 }
}

// -----------------------
// Fading Thumbnail Script
// -----------------------
if ( !function_exists( 'bioship_muscle_fading_thumbnail_script' ) ) {
 function bioship_muscle_fading_thumbnail_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemelayout;
	$fadingthumbs = $vthemelayout['fadingthumbnails'];

	// TODO: allow for multiple CPTs/classes?
	echo "<script>var thumbnailclass = 'img.thumbtype-" . esc_attr( $fadingthumbs ) . "';
	function fadeoutthumbnails() {jQuery(thumbnailclass).fadeOut(3000, fadeinthumbnails);}
	function fadeinthumbnails() {jQuery(thumbnailclass).fadeIn(3000, fadeoutthumbnails);}
	jQuery(document).ready(function() {fadeoutthumbnails();});
	</script>";
 }
}


// ---------------
// === Reading ===
// ---------------

// ------------------------------------------------
// Include/Exclude Categories from Home (Blog) Page
// ------------------------------------------------
if ( !function_exists( 'bioship_muscle_select_home_categories' ) ) {

 add_filter( 'pre_get_posts', 'bioship_muscle_select_home_categories' );

 function bioship_muscle_select_home_categories( $query ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// TODO: check this on page contexts (eg. blog posts page, blog front page)
	if ( $query->is_home() ) {

		global $vthemesettings;
		$mode = isset( $vthemesettings['homecategorymode'] ) ? $vthemesettings['homecategorymode'] : false;
		$mode = bioship_apply_filters( 'muscle_home_category_mode', $mode );
		if ( !$mode || ( 'all' == $mode ) ) {
			return;
		}
		$valid = array( 'include', 'exclude', 'includeexclude' );
		if ( !in_array( $mode, $valid ) ) {
			return;
		}

		// 2.0.0: added category mode/include/exclude filters
		$includecategories = bioship_apply_filters( 'muscle_home_include_categories', $vthemesettings['homeincludecategories'] );
		$excludecategories = bioship_apply_filters( 'muscle_home_exclude_categories', $vthemesettings['homeexcludecategories'] );

		// 2.0.1: revamped include / exclude logic
		// 2.2.0: simplify boolean logic checks
		$categories = get_categories();
		$selected = array();
		$included = is_array( $includecategories ) ? true : false;
		$excluded = is_array( $excludecategories ) ? true : false;

		foreach ( $categories as $category ) {
			$catid = $category->cat_ID;
			if ( $included && ( ( 'include' == $mode ) || ( 'includeexclude' == $mode ) ) ) {
				if ( isset( $includecategories[$catid] ) ) {
					$selected[] = $catid;
				}
			}
			if ( $excluded && ( ( 'exclude' == $mode ) || ( 'includeexclude' == $mode ) ) ) {
				if ( isset( $excludecategories[$catid] ) ) {
					$selected[] = '-' . $catid;
				}
			}
		}

		if ( count( $selected ) > 0 ) {
			$catstring = implode( ' ', $selected );
			$query->set( 'cat', $catstring );
		}
	}
	return $query;
 }
}

// -----------------------
// Search Results per Page
// -----------------------
// 2.0.1: filter themesettings internally
if ( !function_exists( 'bioship_muscle_search_results_per_page' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'pre_get_posts', 'bioship_muscle_search_results_per_page' );

 function bioship_muscle_search_results_per_page( $query ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings, $wp_the_query;

	// 2.0.0: added muscle_search_results filter
	$searchresults = bioship_apply_filters( 'muscle_search_results', $vthemesettings['searchresults'] );
	$searchresults = absint( $searchresults );
	// 2.2.0: replaced is_numeric check with greater than zero check
	if ( $searchresults > 0 ) {
		if ( !is_admin() && ( $query === $wp_the_query ) && ( $query->is_search() ) ) {
			$query->set( 'posts_per_page', $searchresults );
		}
	}
	return $query;
 }
}

// ---------------------------------
// Make Custom Post Types Searchable
// ---------------------------------
// 2.0.1: filter themesettings internally
if ( !function_exists( 'bioship_muscle_searchable_cpts' ) ) {

 // TODO: retest this feature to see if working ?
 // add_filter( 'the_search_query', 'bioship_muscle_searchable_cpts' );

 function bioship_muscle_searchable_cpts( $query ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.1.1: moved trigger check internally
	if ( !is_search() ) {
		return;
	}

	// --- get searchable CPTs ---
	global $vthemesettings;
	$searchablecpts = isset( $vthemesettings['searchablecpts'] ) ? $vthemesettings['searchablecpts'] : false;
	$searchablecpts = bioship_apply_filters( 'muscle_searchable_cpts', $searchablecpts );

	// --- set searchable CPTs ---
	// 2.0.1: fix to search logic array here
	// 2.2.0: fix to variable typo mismatch
	if ( is_array( $searchablecpts ) && ( count( $searchablecpts ) > 0 ) ) {
		$cpts = array();
		foreach ( $searchablecpts as $cpt => $value ) {
			if ( '1' == $value ) {
				$cpts[] = $cpt;
			}
		}
		if ( $query->is_search ) {
			$query->set( 'post_type', $cpts );
		}
	}
	return $query;
 }
}

// -------------------------------
// Jetpack Infinite Scroll Support
// -------------------------------
// Jetpack Infinite Scroll info: http://jetpack.me/support/infinite-scroll/
// also could use AJAX Load More: https://wordpress.org/plugins/ajax-load-more/
// Loading Span selector: span.infinite-loader (default image: /images/infinite-loader.gif)
// Load More Button selector: div.infinite-handler (for click only, not scroll)

if ( !function_exists( 'bioship_muscle_jetpack_scroll_setup' ) ) {

 add_action( 'after_setup_theme', 'bioship_muscle_jetpack_scroll_setup' );

 function bioship_muscle_jetpack_scroll_setup() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check load ---
	global $vthemesettings;
	$load = isset( $vthemesettings['infinitescroll'] ) ? $vthemesettings['infinitescroll'] : false;
	$load = bioship_apply_filters( 'muscle_load_infinitescroll', $load );
	if ( ( 'scroll' != $load ) && ( 'click' != $load ) ) {
		return;
	}

	// --- get footer widget areas ---
    // 2.2.0: fix to possible undefined variable footerwidgets
    $footerwidgets = array();
	$footersidebars = $vthemesettings['footersidebars'];
	if ( $footersidebars > 0 ) {
		$footerwidgets[0] = 'footer-widget-area-1';
	}
	if ( $footersidebars > 1 ) {
		$footerwidgets[1] = 'footer-widget-area-2';
	}
	if ( $footersidebars > 2 ) {
		$footerwidgets[2] = 'footer-widget-area-3';
	}
	if ( $footersidebars > 3 ) {
		$footerwidgets[3] = 'footer-widget-area-4';
	}

	// --- set scroll settings ---
	// 2.2.0: fix to footer_widgets key
	// 2.2.0: fix to add prefix to render function
	$settings = array(
		'type'				=> $load,
		'container'			=> 'content',
		'footer'			=> 'footer',
		'footer_widgets'	=> $footerwidgets,
		'wrapper'			=> 'infinite-wrap',
		'render'			=> 'bioship_muscle_infinite_scroll_loop',
	);

	// --- filter settings ---
	// 1.8.0: added override filters
	$postsperpage = bioship_apply_filters( 'skeleton_infinite_scroll_numposts', '' );
	// 2.2.0: replaced is_numeric with absint more than zero check
	if ( absint( $postsperpage ) > 0 ) {
		$settings['posts_per_page'] = $postsperpage;
	}
	$settings = bioship_apply_filters( 'skeleton_infinite_scroll_settings', $settings );

	// --- add theme support ---
	add_theme_support( 'infinite-scroll', $settings );

	// TODO: use file hierarchy for infinite loader gif
	// ... and then remove this style from style.css
	// span.infinite-loader {url('../images/infinite-loader.gif');}

 }
}

// -----------------------------
// Infinite Scroll Loop Callback
// -----------------------------
// 2.0.1: moved this inside loader function
// TODO: maybe update Infinite Scroll to use AJAX Load More Template ?
if ( !function_exists( 'bioship_muscle_infinite_scroll_loop' ) ) {
 function bioship_muscle_infinite_scroll_loop() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- simple post loop ---
	// 1.5.0: fix: always use hybrid content hierarchy
	while ( have_posts() ) {
		the_post();
		hybrid_get_content_template();
	}
 }
}


// -----------------------------
// === Excerpt and Read More ===
// -----------------------------

// ----------------------------
// Add Excerpt Support to Pages
// ----------------------------
// 1.8.0: add page excerpt support option
// 2.2.0: add action to after setup theme
if ( !function_exists( 'bioship_muscle_enable_page_excerpts' ) ) {
 add_action( 'after_setup_theme', 'bioship_muscle_enable_page_excerpts' );
 function bioship_muscle_enable_page_excerpts() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemesettings;
	if ( isset( $vthemesettings['pageexcerpts'] ) && ( '1' == $vthemesettings['pageexcerpts'] ) ) {
		add_post_type_support( 'page', 'excerpt' );
	}
 }
}

// -----------------------------
// Enable Shortcodes in Excerpts
// -----------------------------
// if ( '1' == $vthemesettings['excerptshortcodes'] ) {
	// 1.9.8: very much "doing it wrong"! - replaced these filters...
	//	add_filter('the_excerpt', 'do_shortcode');
	//	add_filter('get_the_excerpt', 'do_shortcode');

	// if (has_filter('get_the_excerpt', 'wp_trim_excerpt')) {
	//	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	//	add_filter('get_the_excerpt', 'bioship_muscle_excerpts_with_shortcodes');
	// }
// }

// ------------------------
// Excerpts with Shortcodes
// ------------------------
// 1.9.8: copy of wp_trim_excerpt but with shortcodes kept
// note: formatting is still stripped but shortcode text remains
if ( !function_exists( 'bioship_muscle_excerpts_with_shortcodes' ) ) {
 function bioship_muscle_excerpts_with_shortcodes( $text ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- for use in shortcodes to provide alternative output ---
	global $doingexcerpt;
	$doingexcerpt = true;

	// 2.2.0: fix to not apply the_content filters - just do_shortcode
	// (as calling get_the_excerpt in a shortcode can cause endless loop!)
	// $text = get_the_content('');
	// $text = strip_shortcodes($text);
	// $text = bioship_apply_filters('the_content', $text);
	// $text = str_replace(']]>', ']]&gt;', $text);
	$text = do_shortcode( $text );

	$excerpt_length = bioship_apply_filters( 'excerpt_length', 55 );
	$excerpt_more = bioship_apply_filters( 'excerpt_more', ' [&hellip;]' );
	// $text = wp_trim_words($text, $excerpt_length, $excerpt_more);

	$doingexcerpt = false;
	return $text;
 }
}

// ---------------------
// Filter Excerpt Length
// ---------------------
// 1.8.5: move checks to inside filter
if ( !function_exists( 'bioship_muscle_excerpt_length' ) ) {

	add_filter( 'excerpt_length', 'bioship_muscle_excerpt_length' );

	// 2.0.5: move old pseudonym to compat.php
	function bioship_muscle_excerpt_length( $length ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		global $vthemesettings;

		// --- RSS feed excerpt length ---
		// 1.8.5: added alternative feed excerpt length
		// 2.0.9: fix to old themeoption variable
		if ( is_feed() ) {
			if ( isset( $vthemesettings['rssexcerptlength'] ) && ( '' != $vthemesettings['rssexcerptlength'] ) ) {
				// 2.1.1: added missing rss excerpt length filter
				$length = bioship_apply_filters( 'muscle_excerpt_length_rss', $vthemesettings['rssexcerptlength'] );
				$length = abs( intval( $length ) );
				// 2.2.0: simplified length check
				if ( $length < 1 ) {
					$length = PHP_INT_MAX;
				}
				return $length;
			}
		}

		// --- standard excerpt length ---
		// 1.8.5: simplified and improved code here
		if ( isset( $vthemesettings['excerptlength'] ) && ( '' != $vthemesettings['excerptlength'] ) ) {
			// 2.1.1: added missing excerpt length filter
			$length = bioship_apply_filters( 'muscle_excerpt_length', $vthemesettings['excerptlength'] );
			$length = abs( intval( $length ) );
			// 2.2.0: simplified length check
			if ( $length < 1 ) {
				$length = PHP_INT_MAX;
			}
		}

		return $length;
	}
}


// -----------------
// === Read More ===
// -----------------

// --------------
// Read More Link
// --------------
// note: Default = 'Continue reading <span class="meta-nav">&rarr;</span>';
// 2.0.5: move old pseudonym to compat.php
if ( !function_exists( 'bioship_muscle_continue_reading_link' ) ) {
 function bioship_muscle_continue_reading_link() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- create and filter read more link ---
	global $vthemesettings;
	// 2.0.0: added muscle_read_more_anchor filter
	if ( '' != $vthemesettings['readmoreanchor'] ) {
		$readmoreanchor = $vthemesettings['readmoreanchor'];
	} else {
		// 2.0.9: added default continue reading link back into readmore div
		// 2.2.0: move default value to within this function
		$readmoreanchor = esc_html( __( 'Continue reading', 'bioship' ) ) . ' <span class="meta-nav">&rarr;</span>';
	}
	$readmoreanchor = bioship_apply_filters( 'muscle_read_more_anchor', $readmoreanchor );
	// 2.2.0: added read more link class
	$readmore = ' <a href="' . esc_url( get_permalink() ) . '" class="read-more-link">' . $readmoreanchor . '</a>';
	// 2.1.1: added filter muscle_read_more_link filter
	$readmore = bioship_apply_filters( 'muscle_read_more_link', $readmore );
	return $readmore;
 }
}

// -----------------
// Read More Wrapper
// -----------------
// Default = ' &hellip;';
// 2.0.5: removed outside settings check so filtered
// 2.0.5: move old pseudonym to compat.php
if ( !function_exists( 'bioship_muscle_auto_excerpt_more' ) ) {

 add_filter( 'excerpt_more', 'bioship_muscle_auto_excerpt_more' );

 function bioship_muscle_auto_excerpt_more( $more ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings;
	// 2.0.0: added muscle_read_more_before filter
	$readmorebefore = bioship_apply_filters( 'muscle_read_more_filter', $vthemesettings['readmorebefore'] );

	// 2.2.0: set default anchor within function
	$readmore = '<div class="readmore">' . $readmorebefore;
	$readmore .= bioship_muscle_continue_reading_link();
	$readmore .= '</div>';

	return $readmore;
 }
}

// ---------------------
// Remove More Jump Link
// ---------------------
// TODO: maybe add a theme option for removing jump link?
if ( !function_exists( 'bioship_muscle_remove_more_jump_link' ) ) {

 add_filter( 'the_content_more_link', 'bioship_muscle_remove_more_jump_link' );

 function bioship_muscle_remove_more_jump_link( $link ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- remove the hashed suffix ---
	$offset = strpos( $link, '#more-' );
	// 2.0.9: fix to link variable typo
	if ( $offset ) {
		$end = strpos( $link, '"', $offset );
	}
	// 2.2.0: changed to isset check
	if ( isset( $end ) ) {
		$link = substr_replace( $link, '', $offset, ( $end - $offset ) );
	}
	return $link;
 }
}


// ---------------
// === Writing ===
// ---------------

// --------------------
// Limit Post Revisions
// --------------------
// 1.8.0: [deprecated] moved to separate AutoSave Net plugin

// ------------------------------------
// WP Subtitle Custom Post Type Support
// ------------------------------------
if ( !function_exists( 'bioship_muscle_wp_subtitle_custom_support' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'init', 'bioship_muscle_wp_subtitle_custom_support' );

 function bioship_muscle_wp_subtitle_custom_support() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- WP Subtitle plugin check ---
	if ( !function_exists( 'get_the_subtitle' ) ) {
		return;
	}

	// -- add/remove CPT subtitle support ---
	global $vthemesettings;
	$cptsubtitles = $vthemesettings['subtitlecpts'];
	// 2.0.8: fix for possible empty subtitle setting
	if ( is_array( $cptsubtitles ) ) {
		foreach ( $cptsubtitles as $cpt => $value ) {
			if ( $value ) {
				if ( ( 'post' != $cpt ) && ( 'page' != $cpt ) ) {
					add_post_type_support( $cpt, 'wps_subtitle' );
				}
			} else {
				if ( 'post' == $cpt ) {
					remove_post_type_support( 'post', 'wps_subtitle' );
				}
				if ( 'page' == $cpt ) {
					remove_post_type_support( 'page', 'wps_subtitle' );
				}
			}
		}
	}
 }
}


// -----------------
// === RSS Feeds ===
// -----------------

// --------------------
// Automatic Feed Links
// --------------------
// 2.2.0: moved to after setup theme action
add_action( 'after_setup_theme', 'bioship_muscle_auto_feed_link_support' );
if ( !function_exists( 'bioship_muscle_auto_feed_link_support' ) ) {
 function bioship_muscle_auto_feed_link_support() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;
	// 2.0.5: added missing setting filter
	$autofeedlinks = isset( $vthemesettings['autofeedlinks'] ) ? $vthemesettings['autofeedlinks'] : true;
	$autofeedlinks = bioship_apply_filters( 'muscle_automatic_feed_links', $autofeedlinks );

	if ( $autofeedlinks ) {
		add_theme_support( 'automatic-feed-links' );
	} else {
		remove_theme_support( 'automatic-feed-links' );
	}
 }
}

// -----------------
// RSS Publish Delay
// -----------------
// 2.0.5: check setting internally to allow filtering
if ( !function_exists( 'bioship_muscle_delay_feed_publish' ) ) {

 add_filter( 'posts_where', 'bioship_muscle_delay_feed_publish' );

 function bioship_muscle_delay_feed_publish( $where ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $wpdb, $vthemesettings;

	// --- check conditions ---
	// 2.0.5: added missing setting filter
	// 2.1.0: still allow filtering if not set (wp.org version)
	// 2.1.1: fix to delay bypass conditions
	if ( !is_feed() ) {
		return $where;
	}

	$delay = isset( $vthemesettings['rsspublishdelay'] ) ? $vthemesettings['rsspublishdelay'] : false;
	$delay = bioship_apply_filters( 'muscle_rss_feed_publish_delay', $delay );
	// 2.2.0: simplified delay value check
	$delay = absint( $delay );
	if ( $delay < 1 ) {
		return $where;
	}

	$now = gmdate( 'Y-m-d H:i:s' );
	// ref: http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
	$units = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
	$units = bioship_apply_filters( 'muscle_rss_feed_delay_units', $units );
	// add SQL-sytax to default $where
	global $wpdb;
	$extra = " AND TIMESTAMPDIFF(" . $units . ", $wpdb->posts.post_date_gmt, '" . $now . "') > " . $delay . " ";
	// TODO: use wpdb prepare method here ?
	// $extra = $wpdb->prepare( $extra, array( $units, $now, $delay ) );
	$where .= $extra;

	return $where;
 }
}

// --------------------------
// Set Post Types in RSS Feed
// --------------------------
// 2.0.5: check settings internally to allow filtering
// 2.0.5: simplified logic for this filter function
if ( !function_exists( 'bioship_muscle_custom_feed_request' ) ) {

 add_filter( 'request', 'bioship_muscle_custom_feed_request' );

 function bioship_muscle_custom_feed_request( $vars ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	if ( !is_feed() ) {
		return $vars;
	}

	// --- check setting ---
	// 2.1.0: set to false if setting is not present (WordPress.Org version)
	global $vthemesettings;
	$cptsinfeed = isset( $vthemesettings['cptsinfeed'] ) ? $vthemesettings['cptsinfeed'] : false;
	$cptsinfeed = bioship_apply_filters( 'muscle_rss_feed_post_types', $cptsinfeed );
	bioship_debug( "Feed CPTs", $cptsinfeed );

	if ( $cptsinfeed && is_array( $cptsinfeed ) ) {
		if ( isset( $vars['feed'] ) && !isset( $vars['post_type'] ) ) {
			// TODO: recheck whether this is still working as desired
			$vars['post_type'] = $cptsinfeed;
		}
	}
	return $vars;
 }
}

// -----------------------
// Full Content Page Feeds
// -----------------------
// ref: http://wordpress.stackexchange.com/a/227455/76440
// 1.8.5: added this option
// 2.0.0: fix to query object typo
// 2.0.1: fix to match function_exists check
// 2.0.5: check setting internally to allow filtering
if ( !function_exists( 'bioship_muscle_rss_page_feed_full_content' ) ) {

 // 2.0.5: move add_action inside for consistency
 add_action( 'pre_get_posts', 'bioship_muscle_rss_page_feed_full_content' );

 function bioship_muscle_rss_page_feed_full_content( $query ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- check conditions ---
	global $vthemesettings;
	// 2.1.0: default to false if setting is not present (WordPress.Org version)
	$pagefeeds = isset( $vthemesettings['pagecontentfeeds'] ) ? $vthemesettings['pagecontentfeeds'] : false;
	$pagefeeds = bioship_apply_filters( 'muscle_rss_full_page_feeds', $pagefeeds );
	if ( !$pagefeeds ) {
		return $query;
	}

	// --- check for single page feed request ---
	if ( $query->is_main_query() && $query->is_feed() && $query->is_page() ) {
		// - set the post type to page -
		$query->set( 'post_type', array( 'page' ) );
		// - allow for page comments feed via ?withcomments=1 -
		if ( isset( $_GET['withcomments'] ) && ( '1' == sanitize_title( $_GET['withcomments'] ) ) ) {
			return;
		}
		// - set the comment feed to false -
		$query->is_comment_feed = false;
	}

	// --- debug feed query ---
	if ( $query->is_feed() ) {
		bioship_debug( "Feed Query", $query );
	}
	return $query;
 }
}

// -----------------------------
// Full Content Page Feed Filter
// -----------------------------
// 2.0.0: fix to typo in funcname
if ( !function_exists( 'bioship_muscle_page_rss_excerpt_option' ) ) {

 // 2.0.5: move add_filter inside for consistency
 add_filter( 'pre_option_rss_use_excerpt', 'bioship_muscle_page_rss_excerpt_option' );

 function bioship_muscle_page_rss_excerpt_option( $option ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- check setting ---
	global $vthemesettings;
	$pagefeeds = bioship_apply_filters( 'muscle_rss_full_page_feeds', $vthemesettings['pagecontentfeeds'] );

	// --- force full content output for pages ---
	// 2.2.0: combined logic conditions
	if ( $pagefeeds && is_page() ) {
		return '0';
	}
	return $option;
 }
}

// TODO: retest strip_shortcode result for excerpt_rss (on multiple installs?)
// (this code causing some troubles)
// add_filter('the_excerpt_rss','bioship_muscle_rss_page_excerpt');
// if (!function_exists('bioship_muscle_rss_page_excerpt')) {
// function bioship_muscle_rss_page_excerpt($excerpt) {
//	  if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
//    if (is_page()) {
//        global $post; $text = $post->post_content;
//        // removed this line otherwise got blank
//        // $text = strip_shortcodes( $text );
//        $text = bioship_apply_filters( 'the_content', $text );
//        $text = str_replace(']]>', ']]&gt;', $text);
//        $excerpt_length = bioship_apply_filters( 'excerpt_length', 55 );
//        $excerpt_more = bioship_apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
//        $excerpt = wp_trim_words( $text, $excerpt_length, $excerpt_more );
//    }
//    return $excerpt;
//  }
// }


// -------------
// === Admin ===
// -------------

// ------------------------------
// Add Theme Options to Admin Bar
// ------------------------------
// 1.8.5: moved here from muscle.php, option changed to filter
// 2.1.3: moved back to muscle.php (so it works on frontend)
if ( !function_exists( 'bioship_muscle_adminbar_theme_options' ) ) {

 // 2.0.5: check filter inside function for consistency
 add_action( 'wp_before_admin_bar_render', 'bioship_muscle_adminbar_theme_options' );

 function bioship_muscle_adminbar_theme_options() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $wp_admin_bar, $vthemename, $vthemedirs;

	// 2.1.2: check admin permissions
	if ( !current_user_can( 'manage_options' ) && !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// --- filter adding of theme options link ---
	$themeoptions = bioship_apply_filters( 'admin_adminbar_theme_options', true );
	if ( !$themeoptions ) {
		return;
	}

	// --- set theme options link ---
	if ( THEMEOPT || THEMETITAN || class_exists( 'TitanFramework' ) ) {
		// 1.8.5: use add_query_arg here
		$themelink = admin_url( 'themes.php' );
		$themepage = THEMEOPT ? 'options-framework' : 'bioship-options';
		$themelink = add_query_arg( 'page', $themepage, $themelink );
	} else {
		// 1.8.0: link to customize.php if no theme options page exists
		$themelink = admin_url( 'customize.php' );
		$themelink = add_query_arg( 'return', rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $themelink );
	}

	// --- theme test drive compatibility ---
	// 1.8.5: maybe append the Theme Test Drive querystring
	if ( isset( $_REQUEST['theme'] ) && ( '' != sanitize_title( $_REQUEST['theme'] ) ) ) {
		$themelink = add_query_arg( 'theme', $_REQUEST['theme'], $themelink );
	}

	// --- add theme options link icon ---
	// 1.5.0: Add an Icon next to the Theme Options menu item
	// ref: http://wordpress.stackexchange.com/questions/172939/how-do-i-add-an-icon-to-a-new-admin-bar-item
	// default is set to \f115 Dashicon (an eye in a screen) in skin.php
	// and can be overridden using admin_adminbar_menu_icon filter
	$icon = bioship_file_hierarchy( 'url', 'theme-icon.png', $vthemedirs['image'] );
	$icon = bioship_apply_filters( 'admin_adminbar_theme_options_icon', $icon );
	if ( $icon ) {
		// 2.0.9: fix for variable name (vthemesettingsicon)
		// 2.2.0: add esc_url wrapper to icon url
		$iconspan = '<span class="theme-options-icon" style="';
		$iconspan .= 'float:left; width:22px !important; height:22px !important;';
		$iconspan .= 'margin-left: 5px !important; margin-top: 5px !important;';
		$iconspan .= 'background-image:url(\'' . esc_url( $icon ) . '\');"></span>';
	} else {
		$iconspan = '<span class="ab-icon"></span>';
	}

	// --- add admin bar link and title ---
	// 2.2.0: changed Theme Options label to Design
	// 2.2.0: add ab-label class wrapper to title anchor
	$title = '<span class="ab-label">' . esc_html( __( 'Design', 'bioship' ) ) . '</a>';
	$title = bioship_apply_filters( 'admin_adminbar_theme_options_title', $title );
	$menu = array( 'id' => 'theme-options', 'title' => $iconspan . $title, 'href' => $themelink );
	$wp_admin_bar->add_menu( $menu );

	// --- admin bar Theme Options icon ---
	// 1.5.0: set Theme Options default icon
	// 2.1.3: moved outside admin styles for frontend admin bar
	// (as \ is stripped in Admin CSS Theme Options save)
	$icon = '\\f115';
	$icon = apply_filters( 'admin_adminbar_menu_icon', $icon );
	echo '<style>#wp-admin-bar-theme-options .ab-icon:before {content: "' . esc_attr( $icon ) . '"; top: 3px;}</style>' . PHP_EOL;

 }
}

// ----------------------------
// Replace Welcome in Admin Bar
// ----------------------------
// 1.8.5: moved here from muscle.php
// 2.1.3: moved back to muscle.php (to work on frontend)
if ( !function_exists( 'bioship_muscle_adminbar_replace_howdy' ) ) {

 add_filter( 'admin_bar_menu', 'bioship_muscle_adminbar_replace_howdy', 25 );

 function bioship_muscle_adminbar_replace_howdy( $wp_admin_bar ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.1.1: filter whether to replace welcome message
	$replacehowdy = bioship_apply_filters( 'admin_adminbar_replace_howdy', true );
	if ( !$replacehowdy ) {
		return;
	}

	// 1.9.8: replaced deprecated function get_currentuserinfo();
	// 2.0.7: use new prefixed current user function
	$current_user = bioship_get_current_user();
	$username = $current_user->user_login;
	$myaccount = $wp_admin_bar->get_node( 'my-account' );

	// --- filter the new node title ---
	// 1.5.5: fixed translation for Theme Check
	$newtitle = __( 'Logged in as', 'bioship' ) . ' ' . $username;
	$newtitle = bioship_apply_filters( 'admin_adminbar_howdy_title', $newtitle );

	$wp_admin_bar->add_node( array( 'id' => 'my-account', 'title' => $newtitle ) );
 }
}

// ---------------------------------------
// Add "All Options" Page to Settings Menu
// ---------------------------------------
if ( !function_exists( 'bioship_muscle_all_options_link' ) ) {

 // 2.0.1: moved filter option internally
 add_action( 'admin_menu', 'bioship_muscle_all_options_link', 11 );

 function bioship_muscle_all_options_link() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.1.1: do not add All Options link for WordPress.Org version
	if ( THEMEWPORG ) {
		return;
	}

	// --- check setting ---
	global $vthemesettings, $submenu;
	$addlink = isset( $vthemesettings['alloptionspage'] ) ? $vthemesettings['alloptionspage'] : false;
	$addlink = bioship_apply_filters( 'muscle_all_options_page', $addlink );

	if ( $addlink ) {

		// 2.0.7: changed to use add_theme_page instead of add_options_page
		add_theme_page( __( 'All Options', 'bioship' ), __( 'All Options', 'bioship' ), 'manage_options', 'options.php' );

		// 2.0.7: then shift from themes to settings menu
		// 2.1.0: check submenu key exists before looping
		if ( isset( $submenu['options-general.php'] ) ) {
			foreach ( $submenu['options-general.php'] as $key => $values ) {
				$lastkey = $key + 1;
			}
		}
		if ( isset( $submenu['themes.php'] ) ) {
			foreach ( $submenu['themes.php'] as $key => $values ) {
				if ( 'options.php' == $values[2] ) {
					$submenu['options-general.php'][$lastkey] = $values;
					unset( $submenu['themes.php'][$key] );
				}
			}
		}
	}
 }
}

// --------------------
// Remove Update Notice
// --------------------
if ( !function_exists( 'bioship_muscle_remove_update_notice' ) ) {

 // 2.2.0: change action to admin_init
 add_action( 'admin_init', 'bioship_muscle_remove_update_notice', 1 );

 function bioship_muscle_remove_update_notice() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check setting ---
	global $vthemesettings;
	$remove = isset( $vthemesettings['removeupdatenotice'] ) ? $vthemesettings['removeupdatenotice'] : false;
	$remove = bioship_apply_filters( 'muscle_remove_update_notice', $remove );
	if ( !$remove ) {
		return;
	}

	// --- permission checks ---
	// 2.1.1: changed from update_plugins to update_core
	if ( !current_user_can( 'update_core' ) ) {
		// 2.0.1: simplify to remove version check action here
		remove_action( 'init', 'wp_version_check' );
		add_filter( 'pre_option_update_core', '__return_null' );
	}
 }
}

// ---------------------------
// Stop New User Notifications
// ---------------------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_stop_new_user_notifications' ) ) {

 add_action( 'phpmailer_init', 'bioship_muscle_stop_new_user_notifications' );

 function bioship_muscle_stop_new_user_notifications() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check setting ---
	global $vthemesettings;
	$disable = isset( $vthemesettings['disablenotifications'] ) ? $vthemesettings['disablenotifications'] : false;
	$disable = bioship_apply_filters( 'muscle_stop_new_user_notifications', $disable );
	if ( !$disable ) {
		return;
	}

	// note: handling translation wrappers in subject line is not working,
	// so this feature may not work with multi-language translations
	global $phpmailer;
	if ( is_multisite() ) {
		// 2.0.7: added missing translation wrapper
		// 2.0.9: duh, removed translation wrapper
		$subject = 'New User Registration';
		if ( $phpmailer->Subject == $subject ) {
			$phpmailer = new PHPMailer( true );
		}
	} else {
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		// 2.0.7: added missing text domain
		// 2.0.9: duh, removed text domain again
		// 2.1.0: need to remove translation wrappers
		$subject = array(
			sprintf( '[%s] New User Registration', $blogname ),
			sprintf( '[%s] Password Lost/Changed', $blogname ),
		);
		if ( in_array( $phpmailer->Subject, $subject ) ) {
			$phpmailer = new PHPMailer( true );
		}
	}
 }
}

// ------------------
// Disable Self Pings
// ------------------
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_disable_self_pings' ) ) {

 add_action( 'pre_ping', 'bioship_muscle_disable_self_pings' );

 // 2.0.0: remove unneeded pass by reference in argument
 function bioship_muscle_disable_self_pings( $links ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- check settings ---
	global $vthemesettings;
	$disable = isset( $vthemesettings['disableselfpings'] ) ? $vthemesettings['disableselfpings'] : false;
	$disable = bioship_apply_filters( 'muscle_disable_self_pings', $disable );
	if ( !$disable ) {
		return;
	}

	// --- remove ping if contains home URL ---
	// 1.5.5: fix to use home_url for theme check
	$home = home_url();
	foreach ( $links as $i => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[$i] );
		}
	}
 }
}

// -----------------
// Cleaner Admin Bar
// -----------------
// (removes WP links)
// 2.0.1: check themesettings internally to allow filtering
if ( !function_exists( 'bioship_muscle_cleaner_adminbar' ) ) {

 add_action( 'wp_before_admin_bar_render', 'bioship_muscle_cleaner_adminbar' );

 function bioship_muscle_cleaner_adminbar() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.2.0: do not do this for WP.Org version
	if ( THEMEWPORG ) {
		return;
	}

	// --- check settings ---
	global $vthemesettings;
	$clean = isset( $vthemesettings['cleaneradminbar'] ) ? $vthemesettings['cleaneradminbar'] : false;
	$clean = bioship_apply_filters( 'muscle_cleaner_admin_bar', $clean );
	if ( !$clean ) {
		return;
	}

	// --- set items to remove ---
	global $wp_admin_bar;
	// 1.8.0: added array filter for altering adminbar link removal
	$removeitems = array( 'wp-logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback' );
	$removeitems = bioship_apply_filters( 'admin_adminbar_remove_items', $removeitems );

	// --- remove admin bar items ---
	if ( count( $removeitems ) > 0 ) {
		foreach ( $removeitems as $removeitem ) {
			$wp_admin_bar->remove_menu( $removeitem );
		}
	}
 }
}

// ---------------------------------
// Enqueue Code Editor for Style Box
// ---------------------------------
// 2.2.0: added enqueueing of code mirror for style box
if ( !function_exists( 'bioship_enqueue_code_editor' ) ) {

 add_action( 'admin_enqueue_scripts', 'bioship_enqueue_code_editor' );
 add_action( 'wp_enqueue_scripts', 'bioship_enqueue_code_editor' );

 function bioship_enqueue_code_editor() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	if ( !is_user_logged_in() ) {
		return;
	}
	if ( !current_user_can( 'manage_options' ) && !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	global $vthemedirs;

	// --- filter adding of theme options link ---
	$styleeditor = bioship_apply_filters( 'admin_adminbar_style_editor', true );
	$codeeditor = bioship_apply_filters( 'admin_adminbar_style_code_editor', true );
	if ( !$styleeditor || !$codeeditor || !function_exists( 'wp_enqueue_code_editor' ) ) {
		return;
	}

	// --- localize script settings ---
	$style_settings = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
    wp_localize_script( 'code-editor', 'bioship_style_settings', $style_settings );

	// --- enqueue scripts ---
    wp_enqueue_script( 'wp-theme-plugin-editor' );
    wp_enqueue_style( 'wp-codemirror' );

	// --- styles box scripts ---
	// 2.2.0: enqueue separate box styles script file
	$box_script = bioship_file_hierarchy( 'both', 'styles-box.js', $vthemedirs['script'] );
	bioship_debug( "Box Script", $box_script );
	if ( $box_script ) {
		$version = filemtime( $box_script['file'] );
		wp_enqueue_script( THEMESLUG . '-styles-box', $box_script['url'], array( 'jquery' ), $version, true );
	}

	// --- styles box styles ---
	// 2.2.0: enqueue separate box styles stylesheet
	$box_styles = bioship_file_hierarchy( 'both', 'styles-box.css', $vthemedirs['style'] );
	if ( $box_styles ) {
		$version = filemtime( $box_styles['file'] );
		wp_enqueue_style( THEMESLUG . '-styles-box', $box_styles['url'], array(), $version );

		// --- admin bar styles icon ---
		// 2.2.0: append filtered icon style using wp_add_inline_style
		$icon = '\\f100';
		$icon = apply_filters( 'admin_adminbar_styles_icon', $icon );
		$css = '#wp-admin-bar-theme-styles .ab-icon:before {content: "' . esc_attr( $icon ) . '"; top: 3px;}';
		wp_add_inline_style( THEMESLUG . '-styles-box', $css );
	}
 }
}

// --------------------
// Admin Bar Style Link
// --------------------
if ( !function_exists( 'bioship_muscle_adminbar_style_editor' ) ) {

 add_action( 'wp_before_admin_bar_render', 'bioship_muscle_adminbar_style_editor' );

 function bioship_muscle_adminbar_style_editor() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $wp_admin_bar, $vthemename, $vthemedirs;

	// --- check theme edit permissions ---
	if ( !current_user_can( 'manage_options' ) && !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// --- filter adding of theme options link ---
	$styleeditor = bioship_apply_filters( 'admin_adminbar_style_editor', true );
	if ( !$styleeditor ) {
		return;
	}

	// --- set theme options link ---
	$stylelink = admin_url( 'admin-ajax.php' );

	// --- add theme options link icon ---
	$icon = bioship_file_hierarchy( 'url', 'style-icon.png', $vthemedirs['image'] );
	$icon = bioship_apply_filters( 'admin_adminbar_style_editor_icon', $icon );
	if ( $icon ) {
		// 2.2.0: add esc_url to icon output
		$iconspan = '<span class="theme-options-icon" style="';
		$iconspan .= 'float:left; width:22px !important; height:22px !important;';
		$iconspan .= 'margin-left: 5px !important; margin-top: 5px !important;';
		$iconspan .= 'background-image:url(\'' . esc_url( $icon ) . '\');"></span>';
	} else {
		$iconspan = '<span class="ab-icon"></span>';
	}

	// --- add admin bar link and title ---
	// 2.2.0: add ab-label class wrapper to title anchor
	$title = '<span class="ab-label">' . esc_html( __( 'Styles', 'bioship' ) ) . '</span>';
	$title = bioship_apply_filters( 'admin_adminbar_style_editor_title', $title );
	$menu = array( 'id' => 'theme-styles', 'title' => $iconspan . $title, 'href' => $stylelink );
	$wp_admin_bar->add_menu( $menu );

	// --- load the style editor after admin bar rendered ---
	add_action( 'wp_after_admin_bar_render', 'bioship_muscle_style_editor_box' );
 }
}

// ----------------
// Style Editor Box
// ----------------
if ( !function_exists( 'bioship_muscle_style_editor_box' ) ) {
 function bioship_muscle_style_editor_box() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemename;

	// --- get styles or admin styles ---
	if ( is_admin() ) {
		$label = __( 'Admin Theme Styles', 'bioship' );
		$stylekey = 'dynamicadmincss';
	} else {
		$label = __( 'Theme Styles', 'bioship' );
		$stylekey = 'dynamiccustomcss';
		if ( is_singular() ) {
			global $post;
			$postid = $post->ID;
			$postlabel = __( 'Post Styles', 'bioship' );
			$poststylekey = '_' . THEMEPREFIX . '_perpoststyles';
			$poststyles = get_post_meta( $postid, $poststylekey, true );
		}
	}
	$styles = '';
	if ( isset( $vthemesettings[$stylekey] ) ) {
		$styles = $vthemesettings[$stylekey];
	}

	// -- get / set style box config ---
	$userid = get_current_user_id();
	$box = get_user_meta( $userid, '_' . THEMEPREFIX . '_style_editor_box', true );
	if ( !$box ) {
		$box = array( 'position' => 'top', 'width' => '300', 'height' => '200' );
	}

	// --- theme styles box ---
	echo '<div id="theme-styles-box" class="' . esc_attr( $box['position'] ) . '" style="display:none;">';

		// --- theme styles wrapper ---
		echo '<div id="theme-styles-wrapper">';

			// --- open theme styles form ---
			$formaction = admin_url( 'admin-ajax.php' );
			echo '<form action="' . esc_url( $formaction ) . '" method="post" target="theme-styles-frame">' . PHP_EOL;
			echo '<input type="hidden" name="action" value="bioship_quicksave_styles">' . PHP_EOL;
			if ( is_singular() ) {
				echo '<input type="hidden" name="postid" value="' . esc_attr( $postid ) . '">' . PHP_EOL;
			}
			echo '<input type="hidden" id="theme-styles-position" name="theme-styles-position" value="' . esc_attr( $box['position'] ) . '">' . PHP_EOL;
			echo '<input type="hidden" id="theme-styles-width" name="theme-styles-width" value="' . esc_attr( $box['width'] ) . '">' . PHP_EOL;
			echo '<input type="hidden" id="theme-styles-height" name="theme-styles-height" value="' . esc_attr( $box['height'] ) . '">' . PHP_EOL;
			wp_nonce_field( 'quicksave_css_' . $vthemename );

				// --- theme styles label ---
				echo '<div id="theme-styles-labels">' . PHP_EOL;
					echo '<div id="theme-styles-label">' . esc_html( $label ) . '</div>' . PHP_EOL;
					echo '<div id="theme-styles-modified-label">' . PHP_EOL;
						// 2.2.0: added missing text domain to translation
						echo '<div id="theme-styles-textarea-modified" style="display:none;"> * ' . esc_html( __( 'Modified', 'bioship' ) ) . '</div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
					if ( is_admin() ) {
						echo '<input type="hidden" name="style-type" value="admin">' . PHP_EOL;
					} else {
						if ( is_singular() ) {
							echo '<div id="theme-styles-label-post" style="display:none;">' . esc_html( $postlabel ) . '</div>' . PHP_EOL;
							echo '<div id="theme-styles-modified-post-label">' . PHP_EOL;
								// 2.2.0: added missing text domain to translation
								echo '<div id="theme-styles-textarea-post-modified" style="display:none;"> * ' . esc_html( __( 'Modified', 'bioship' ) ) . '</div>' . PHP_EOL;
							echo '</div>' . PHP_EOL;
							echo '<div id="theme-styles-types">' . PHP_EOL;
								echo '<br><b>' . esc_html( __( 'Type', 'bioship' ) ) . '</b>: ' . PHP_EOL;
								echo '<input type="radio" onclick="bioship_toggle_theme_style_type(\'theme\');" id="theme-styles-type-theme" name="style-type" value="theme" checked="checked"> ' . PHP_EOL;
								echo '<a href="javascript:void(0);" onclick="bioship_toggle_theme_style_type(\'theme\')">' . esc_html( __( 'Theme', 'bioship' ) ) . '</a>' . PHP_EOL;
								echo '<span id="theme-styles-modified" style="display:none;"> *</span>' . PHP_EOL;
								echo ' &nbsp; ';
								echo '<input type="radio" onclick="bioship_toggle_theme_style_type(\'post\');" id="theme-styles-type-post" name="style-type" value="post"> ' . PHP_EOL;
								echo '<a href="javascript:void(0);" onclick="bioship_toggle_theme_style_type(\'post\')">' . esc_html( __( 'Post', 'bioship' ) ) . '</a>' . PHP_EOL;
								echo '<span id="theme-styles-modified-post" style="display:none;"> *</span>' . PHP_EOL;
							echo '</div>' . PHP_EOL;
						} else {
							// TODO: archive page context styling ?
							// echo '<input type="hidden" name="style-type" value="theme">' . PHP_EOL;
							// echo '<span id="theme-styles-modified" style="display:none;"></span>' . PHP_EOL;
						}
					}
					echo '<br>';

					// --- style editor box position ---
					echo '<div id="theme-styles-control-position">' . PHP_EOL;
						echo '<div id="theme-styles-label-position">' . esc_html( __( 'Box Position', 'bioship' ) ) . '</div>' . PHP_EOL;
						echo '<table id="theme-styles-position-table" cellpadding="0" cellspacing="0">' . PHP_EOL;
							echo '<tr><td><a href="javascript:void(0);" class="theme-styles-arrow" id="theme-styles-left" onclick="bioship_shift_style_editor(\'left\');" title="' . esc_attr( __( 'Float Style Editor Box to Left', 'bioship' ) ) . '">&#9668;</a></td>' . PHP_EOL;
							echo '<td><a href="javascript:void(0);" class="theme-styles-arrow" id="theme-styles-top" onclick="bioship_shift_style_editor(\'top\');" title="' . esc_attr( __( 'Float Style Editor Box to Top', 'bioship' ) ) . '">&#9650;</a>' . PHP_EOL;
							echo '<a href="javascript:void(0);" class="theme-styles-arrow" id="theme-styles-bottom" onclick="bioship_shift_style_editor(\'bottom\');" title="' . esc_attr( __( 'Float Style Editor Box to Bottom', 'bioship' ) ) . '">&#9660;</a></td>' . PHP_EOL;
							echo '<td><a hef="javascript:void(0);" class="theme-styles-arrow" id="theme-styles-right" onclick="bioship_shift_style_editor(\'right\');" title="' . esc_attr( __( 'Float Style Editor Box to Right', 'bioship' ) ) . '">&#9658;</a></td></tr>' . PHP_EOL;
						echo '</table>' . PHP_EOL;
					echo '</div>' . PHP_EOL;

					// --- style editor box size ---
					echo '<div id="theme-styles-control-size">' . PHP_EOL;
						echo '<div id="theme-styles-label-size">' . esc_html( __( 'Box Size', 'bioship' ) ) . '</div>' . PHP_EOL;
						echo '<div id="theme-styles-sizer-pluses">' . PHP_EOL;
							echo '<a href="javascript:void(0);" class="theme-styles-sizer-plus" id="theme-styles-height-plus" onclick="bioship_resize_style_editor(\'height\', \'plus\');" title="' . esc_attr( __( 'Increase Style Editor Box Height', 'bioship' ) ) . '">&oplus;</a>' . PHP_EOL;
							echo '<a hef="javascript:void(0);" class="theme-styles-sizer-plus" id="theme-styles-width-plus" onclick="bioship_resize_style_editor(\'width\', \'plus\');" title="' . esc_attr( __( 'Increase Style Editor Box Width', 'bioship' ) ) . '">&oplus;</a>' . PHP_EOL;
						echo '</div>' . PHP_EOL;
						echo '<div id="theme-styles-sizer-minuses">' . PHP_EOL;
							echo '<a href="javascript:void(0);" class="theme-styles-sizer-minus" id="theme-styles-width-minus" onclick="bioship_resize_style_editor(\'width\', \'minus\');" title="' . esc_attr( __( 'Reduce Style Editor Box Width', 'bioship' ) ) . '">&Theta;</a>' . PHP_EOL;
							echo '<a href="javascript:void(0);" class="theme-styles-sizer-minus" id="theme-styles-height-minus" onclick="bioship_resize_style_editor(\'height\', \'minus\');" title="' . esc_attr( __( 'Reduce Style Editor Box Height', 'bioship' ) ) . '">&Theta;</a>' . PHP_EOL;
						echo '</div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;

				echo '</div>' . PHP_EOL;

				// --- theme styles input ---
				echo '<div id="theme-styles-inputs">' . PHP_EOL;
					echo '<div id="theme-styles-input">' . PHP_EOL;
                        // 2.2.0: fix to add missing esc_textarea on style content
						echo '<textarea id="theme-styles-textarea" name="' . esc_attr( $stylekey ) . '" onkeyup="bioship_check_textarea(\'\');">' . esc_textarea( $styles ) . '</textarea>' . PHP_EOL;
						echo '<textarea id="theme-styles-textarea-original" name="' . esc_attr( $stylekey ) . '-original" style="display:none;" readonly>' . esc_textarea( $styles ) . '</textarea>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
					if ( isset( $poststylekey ) ) {
						// 2.2.0: move hidden style to container instead of textarea
						echo '<div id="theme-styles-input-post" style="display:none;">' . PHP_EOL;
						    // 2.2.0: fix to add missing esc_textarea on style content
							echo '<textarea id="theme-styles-textarea-post" name="' . esc_attr( $poststylekey ) . '" onkeyup="bioship_check_textarea(\'post\');">' . esc_textarea( $poststyles ) . '</textarea>' . PHP_EOL;
							echo '<textarea id="theme-styles-textarea-post-original" name="' . esc_attr( $poststylekey ) . '-original" style="display:none;" readonly>' . esc_textarea( $poststyles ) . '</textarea>' . PHP_EOL;
						echo '</div>' . PHP_EOL;
					}
				echo '</div>' . PHP_EOL;

				// --- theme styles save button ---
				echo '<div id="theme-styles-save">' . PHP_EOL;
					echo '<div class="theme-styles-save-message">' . PHP_EOL;
						echo '<div id="quicksavesaved" style="display:none;">' . esc_html( __( 'Theme Styles Saved!', 'bioship' ) ) . '</div>' . PHP_EOL;
						echo '<div id="adminquicksavesaved" style="display:none;">' . esc_html( __( 'Admin Styles Saved!', 'bioship' ) ) . '</div>' . PHP_EOL;
						echo '<div id="postquicksavesaved" style="display:none;">' . esc_html( __( 'Post Styles Saved!', 'bioship' ) ) . '</div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
					echo '<div class="theme-styles-save-button">' . PHP_EOL;
						echo '<input class="button button-primary" type="submit" value="' . esc_attr( __( 'Save Styles', 'bioship' ) ) . '">' . PHP_EOL;
					echo '</div>' . PHP_EOL;
				echo '</div>' . PHP_EOL;

			// --- close theme styles form ---
			echo '</form>' . PHP_EOL;

		// --- close theme styles wrapper ---
		echo '</div>' . PHP_EOL;

	// --- close theme styles box ---
	echo '</div>' . PHP_EOL;

	// --- theme styles save iframe ---
	echo '<iframe src="javascript:void(0);" name="theme-styles-frame" id="theme-styles-frame" style="display:none;"></iframe>' . PHP_EOL;

 }
}

// -------------
// === Login ===
// -------------

// ----------------
// Login Header URL
// ----------------
if ( !function_exists( 'bioship_muscle_login_headerurl' ) ) {

 add_filter( 'login_headerurl', 'bioship_muscle_login_headerurl' );

 function bioship_muscle_login_headerurl( $url ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.0.9: added missing theme-specific filtering
	// 2.1.1: added missing bioship_ function prefix
	$url = bioship_apply_filters( 'muscle_login_header_title', site_url( '/' ) );
	return $url;
 }
}

// ------------------
// Login Header Title
// ------------------
if ( !function_exists( 'bioship_muscle_login_headertitle' ) ) {

 add_filter( 'login_headertitle', 'bioship_muscle_login_headertitle' );

 function bioship_muscle_login_headertitle( $title ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.0.9: added missing theme-specific filtering
	// 2.1.1: added missing bioship_ function prefix
	$title = bioship_apply_filters( 'muscle_login_header_title', get_bloginfo( 'name' ) );
	return $title;
 }
}

// ---------------
// Login Page Logo
// ---------------
// (adds a #loginwrapper element to help styling)
// 1.8.5: moved actual login styling to skin.php
// 1.8.5: much fun with login wrapper hacks!
if ( !function_exists( 'bioship_muscle_login_styles' ) ) {

 add_action( 'login_head', 'bioship_muscle_login_styles' );

 function bioship_muscle_login_styles() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- add loginwrapper class placeholder ---
	if ( !function_exists( 'bioship_muscle_login_body_hack' ) ) {
	 add_filter( 'login_body_class', 'bioship_muscle_login_body_hack', 999 );
	 function bioship_muscle_login_body_hack( $classes ) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		$classes[] = 'LOGINWRAPPER';
		add_filter( 'attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2 );
		return $classes;
	 }
	}

	// --- replace loginwrapper class with div ---
	if ( !function_exists( 'bioship_muscle_login_body_filter_hack' ) ) {
	 function bioship_muscle_login_body_filter_hack( $safetext, $text ) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		$replace = '"><div id="loginwrapper';
		$safetext = str_replace( 'LOGINWRAPPER', $replace, $safetext );
		remove_filter( 'attribute_escape', 'bioship_muscle_login_body_filter_hack', 999, 2 );
		return $safetext;
	 }
	}

	// --- close loginwrapper div ---
	if ( !function_exists( 'bioship_muscle_close_login_wrapper' ) ) {
	 add_action( 'login_footer', 'bioship_muscle_close_login_wrapper' );
	 function bioship_muscle_close_login_wrapper() {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
		bioship_skin_dynamic_login_css_inline();
		echo '</div>' . PHP_EOL;
		bioship_html_comment( '/#loginwrapper' );
	 }
	}
 }
}


// --------------------
// === Integrations ===
// --------------------

// --- Integration Notes ---
// - AJAX Load More Templates (/templates/ajax-load-more/)
// - Theme My Login Templates (/templates/theme-my-login/)
// - Theme Test Drive (throughout theme)
// - WP Subtitle (see skeleton.php)
// - WP PageNavi (see skeleton.php)

// -------------------
// Integrations Loader
// -------------------
if ( !function_exists( 'bioship_muscle_integrations_loader' ) ) {

 add_action( 'plugins_loaded', 'bioship_muscle_integrations_loader' );

 function bioship_muscle_integrations_loader() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- filtered array of integrations ---
	$integrations = array(
		'css-hero' => 'css-hero/css-hero.php',
		'hybrid-hook' => 'hybrid-hook/hybrid-hook',
		'woocommerce' => 'woocommerce/woocommerce.php',
		'open-graph-protocol-framework' => 'open-graph-protocol-framework/open-graph-protocol-framework.php',
		// 'foundation' => n/a
		'theme-my-login' => 'theme-my-login/theme-my-login.php',
		'theme-switching' => 'jonradio-multiple-themes/jonradio-multiple-themes.php',
		'theme-switching' => 'theme-test-drive/themedrive.php',
	);
	$integrations = bioship_apply_filters( 'muscle_integrations', $integrations );

	// --- check for a valid active plugin ---
	$activeplugins = maybe_unserialize( get_option( 'active_plugins' ) );
	if ( !is_array( $activeplugins ) ) {
		$activeplugins = array();
	}

	// --- filter force loaded integrations ---
	$force_load_integrations = bioship_apply_filters( 'muscle_force_load_integrations', array( 'hybrid-hook' ) );

	// --- loop integrations to load ---
	foreach ( $integrations as $file_slug => $plugin_file ) {
		if ( in_array( $plugin_file, $activeplugins ) || in_array( $file_slug, $force_load_integrations ) ) {
			$filepath = bioship_file_hierarchy( 'file', $filename . '.php', array( 'integrations' ) );
			include_once $filepath;
		}
	}
 }
}

