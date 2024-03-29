<?php

// =============================
// ==== BioShip Skin Loader ====
// = Dynamic Stylesheet Output =
// =============================

// --------------------------
// === skin.php Structure ===
// --------------------------
// - Skin Load Setup
// - Skin Helpers
// - Direct Load Mode
// - Set Theme Paths
// - Get Theme Settings
// - Typography
// - Buttons
// - Hover Buttons
// - Inputs
// - Links
// - Body Background
// - Admin Styles
// - Login Styles
// - Compile Styles
// -- Body Styles
// -- Wrap Container Styles
// -- Header Background Styles
// -- Header Text Display Styles
// -- Background Colour Styles
// -- Navigation Menu Styles
// -- Buttons, Links, Inputs, Typography
// -- Content Padding Styles
// - Output Styles
// - Browser and Mobile Styles
// - Custom Dynamic CSS
// - Output Load Time
// --------------------------


// Development TODOs
// -----------------
// ? maybe copy and use file hierarchy function
// ? maybe use improved debug switching from functions.php
// ? maybe recheck image size path for subdirectory installs
// ? check for possible CSS fallback usage
// ref: http://modernweb.com/2013/07/08/using-css-fallback-properties-for-better-cross-browser-compatibility/
// ? convert to rgba when both Titan and Options Framework use rgba colour picker
// ref: https://css-tricks.com/rgba-browser-support/


// -----------------------
// === Skin Load Setup ===
// -----------------------

// -------------------
// Set Loading Context
// -------------------
// 1.8.5: undefined variable fix
// 2.0.8: yet another undefined variable fix
// 2.1.1: allow for loginstyles via querystring
if ( !isset( $includedskin ) ) {
	$includedskin = false;
}
if ( !isset( $adminstyles ) ) {
	$adminstyles = false;
}
if ( isset( $_GET['adminstyles'] ) && ( 'yes' == $_GET['adminstyles'] ) ) {
	$adminstyles = true;
}
if ( !isset( $loginstyles ) ) {
	$loginstyles = false;
}
if ( isset( $_GET['loginstyles'] ) && ( 'yes' == $_GET['loginstyles'] ) ) {
	$loginstyles = true;
}

// -----------------
// Output CSS Header
// -----------------
// 1.8.5: no header for inline style output fix
// 2.0.7: disambiguate vincluded to vincludedskin
// 2.0.9: fix to header output for non-included only
if ( !$includedskin && !$loginstyles ) {
	header( "Content-type: text/css; charset: UTF-8" );
}

// -----------------------
// Stylesheet Title Output
// -----------------------
if ( $adminstyles ) {
	echo "/* BioShip Dynamic Admin Skin */" . PHP_EOL . PHP_EOL;
} else {
	echo "/* BioShip Dynamic Stylesheet Skin */" . PHP_EOL . PHP_EOL;
}

// --------------------
// AJAX Load Time Start
// --------------------
// 1.8.0: fix for performance timer variable
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	global $vthemetimestart;
}


// --------------------
// === Skin Helpers ===
// --------------------

// ------------------------------
// Find / Require for Blog Loader
// ------------------------------
// 2.0.5: limit search attempts
// 2.2.0: use prefixed global variable
if ( !function_exists( 'bioship_skin_find_require' ) ) {
 function bioship_skin_find_require( $file, $folder = null ) {
 	global $bioship_find;
	if ( !isset( $bioship_find['limit'] ) ) {
		$bioship_find['limit'] = 10;
	}
	if ( !isset( $bioship_find['tries'] ) ) {
		$bioship_find['tries'] = 0;
	}
	if ( $bioship_find['tries'] > $bioship_find['limit'] ) {
		return false;
	}
	if ( null === $folder ) {
		$folder = dirname( __FILE__ );
	}
	$path = $folder . DIRECTORY_SEPARATOR . $file;
	if ( file_exists( $path ) ) {
		require $path;
		return $folder;
	} else {
		$upfolder = bioship_skin_find_require( $file, dirname( $folder ) );
		if ( '' != $upfolder ) {
			return $upfolder;
		}
	}
	$bioship_find['tries']++;
 }
}

// ---------------
// Round Half Down
// ---------------
// 2.1.2: added this helper function
// (since PHP 5.2 does not have PHP_ROUND_HALF_DOWN mode)
if ( !function_exists( 'bioship_round_half_down' ) ) {
 function bioship_round_half_down( $v, $precision = 3 ) {
	$v = explode( '.', $v );
	$v = implode( '.', $v );
	$v = $v * pow( 10, $precision ) - 0.5;
	$a = ceil( $v ) * pow( 10, -$precision );
	return number_format( $a, 2, '.', '' );
 }
}

// -----------------------
// Get Image Size from URL
// -----------------------
// 1.8.5: made separate for header background and login logo
// 1.8.5: cleaned logic for caching image size
if ( !function_exists( 'bioship_skin_get_image_size' ) ) {
 function bioship_skin_get_image_size( $imageurl, $cachekey ) {

	global $vthemename, $vthemesettings;

	if ( THEMEDEBUG ) {
		echo "/* Image URL: " . esc_url( $imageurl ) . " */" . PHP_EOL;
	}
	$cachekey = $vthemename . '_' . $cachekey;

	// We really want to set an explicit width and a height for the header background
	// as it does display better, but needs a bit of hackiness to get it going well.
	// So we check for a cached image size - or we use imagesize and then cache it...
	// this prevents imagesize from downloading the image url every pageload)
	// update: but, need to check for allow_url_fopen to do this for URLs

	$imagesize = false;
	$imagesizedata = get_option( $cachekey );
	if ( THEMEDEBUG ) {
		echo "/* Cached Image Key: " . esc_html( $cachekey ) . " */" . PHP_EOL;
		echo "/* Cached Image Size Data: " . esc_html( $imagesizedata ) . " */" . PHP_EOL;
	}

	if ( ( '' != $imagesizedata ) && strstr( $imagesizedata, '::' ) ) {
	 	$imagesize = explode( '::', $imagesizedata );
		// --- match URL and make sure does not exceed maximum layout width ---
		// 2.1.3: fix to variable typo (themesettings)
		if ( ( $imagesize[2] != $imageurl ) || ( $imagesize[0] > $vthemesettings['layout'] ) ) {
			delete_option( $cachekey );
			$imagesize = false;
		}
	}

	if ( !$imagesize ) {
		if ( !ini_get( 'allow_url_fopen' ) ) {

			// --- try to convert the url to filepath (onsite URLs only) ---
			$siteurl = site_url();
			$homeurl = home_url();
			$abspath = untrailingslashit( ABSPATH );
			if ( substr( $imageurl, 0, strlen( $siteurl ) ) == $siteurl ) {
				$imagepath = str_replace( $siteurl, $abspath, $imageurl );
			} elseif ( substr( $imageurl, 0, strlen( $homeurl ) ) == $homeurl ) {
				$imagepath = str_replace( $homeurl, $abspath, $imageurl );
			}
			if ( file_exists( $imagepath ) ) {
				$imagesize = getimagesize( $imagepath );
			}

			if ( THEMEDEBUG ) {
				// TODO: maybe recheck image size path for subdirectory installs ?
				// if ( ( site_url() ) == (home_url() ) ) {}
				echo "/* Site URL: " . esc_url( $siteurl ) . " */" . PHP_EOL;
				echo "/* Home URL: " . esc_url( $homeurl ) . " */" . PHP_EOL;
				echo "/* Image Path: ".esc_html( $imagepath ) . " */" . PHP_EOL;
			}
		} else {
			$imagesize = getimagesize( $imageurl );
		}

		if ( $imagesize ) {
			// --- maybe adjust for maximum layout width ---
			if ( $imagesize[0] > $vthemesettings['layout'] ) {
				if ( THEMEDEBUG ) {
					echo "/* Original Size: " . esc_html( print_r( $imagesize, true ) ) . " */" . PHP_EOL;
				}
				$ratio = $imagesize[1] / $imagesize[0];
				$imagesize[1] = bioship_round_half_down( $vthemesettings['layout'] * $ratio );
				$imagesize[0] = $vthemesettings['layout'];
				if ( THEMEDEBUG ) {
					echo "/* Adjusted Size: " . esc_html( print_r( $imagesize, true ) ) . " */" . PHP_EOL;
				}
			}

			// --- update the image size cache key ---
			$imagedata = $imagesize[0] . '::' . $imagesize[1] . '::' . $imageurl;
			update_option( $cachekey, $imagedata );

			if ( THEMEDEBUG ) {
				echo "/* Set Cached Key: " . esc_html( $cachekey ) . " - " . esc_html( $imagedata ) . " */" . PHP_EOL;
			}
		}
	}

	if ( $imagesize ) {
		if ( THEMEDEBUG ) {
			echo "/* Image Size: " . esc_html( print_r( $imagesize, true ) ) . " */" . PHP_EOL;
		}
		return $imagesize;
	}

	return false;
 }
}

// ----------------------
// CSS Replacement Values
// ----------------------
if ( !function_exists( 'bioship_skin_css_replace_values' ) ) {
 function bioship_skin_css_replace_values( $css ) {

	// 2.0.9: use global directory and URL values
	global $vthemestyleurl, $vthemetemplateurl, $vpieurl, $vborderradiusurl;

	// --- Directory URLs ---
	if ( strstr( $css, '%STYLEURL%' ) ) {
		$css = str_replace( '%STYLEURL%', $vthemestyleurl, $css );
	}
	if ( strstr( $css, '%STYLESHEETURL%' ) ) {
		$css = str_replace( '%STYLESHEETURL%', $vthemestyleurl, $css );
	}
	if ( strstr( $css, '%TEMPLATEURL%' ) ) {
		$css = str_replace( '%TEMPLATEURL%', $vthemetemplateurl, $css );
	}

	// --- Image Directory URLs ---
	if ( strstr( $css, '%STYLEIMAGEURL%' ) ) {
		$css = str_replace( '%STYLEIMAGEURL%', $vthemestyleurl . 'images/', $css );
	}
	if ( strstr( $css, '%TEMPLATEIMAGEURL%' ) ) {
		$css = str_replace( '%TEMPLATEIMAGEURL%', $vthemetemplateurl . 'images/', $css );
	}

	// --- HTC File Links ---
	if ( strstr( $css, '%BORDERRADIUS%' ) ) {
		$css = str_replace( '%BORDERRADIUS%', $vborderradiusurl, $css );
	}
	if ( strstr( $css, '%PIE%' ) ) {
		$css = str_replace( '%PIE%', $vpieurl, $css );
	}

	return $css;
 }
}

// ----------------------------
// Theme Test Drive Integration
// ----------------------------
// copy of skeleton_themedrive_determine_theme function
if ( !function_exists( 'bioship_themedrive_determine_theme' ) ) {
 function bioship_themedrive_determine_theme() {

	if ( isset( $_REQUEST['theme'] ) ) {
		// 2.1.2: added sanitize_title to request input
		$tdtheme = sanitize_title( trim( $_REQUEST['theme'] ) );
	} else {

		// --- check for plugin ---
		// 1.8.5: added a check for if theme test drive is active!
		// 2.1.1: only check if no theme querystring override
		$activeplugins = maybe_unserialize( get_option( 'active_plugins' ) );
		if ( !in_array( 'theme-test-drive/themedrive.php', $activeplugins ) ) {
			return false;
		}

		// --- check theme test drive level ---
		$tdlevel = get_option( 'td_level' );
		if ( '' != $tdlevel ) {
			$permissions = 'level_' . $tdlevel;
		} else {
			$permissions = 'level_10';
		}

		if ( !current_user_can( $permissions ) ) {
			return false;
		} else {
			$tdtheme = get_option( 'td_themes' );
			if ( empty( $tdtheme ) || ( '' == $tdtheme ) ) {
				return false;
			}
		}
	}

	// -- attempt to get theme data ---
	$themedata = wp_get_theme( $tdtheme );
	if ( !empty( $themedata ) ) {
		return $themedata;
	}

	// --- get all themes and attempt match ---
	$allthemes = wp_get_themes();
	foreach ( $allthemes as $themedata ) {
		if ( $themedata['Stylesheet'] == $tdtheme ) {
			return $themedata;
		}
	}

	return false;
 }
}

// --------------
// Fix Serialized
// --------------
// (copy of skeleton_fix_serialized)
// 2.2.0: make non-unique and added function exists wrapper
if ( !function_exists( 'bioship_fix_serialized' ) ) {
 function bioship_fix_serialized( $string ) {

    // --- security ---
    if ( !preg_match( '/^[aOs]:/', $string ) ) {
    	return $string;
    }
    if ( false !== @unserialize( $string ) ) {
    	return $string;
    }
    $string = preg_replace( "%\n%", "", $string );

    // --- doublequote exploding ---
    $data = preg_replace( '%";%', "ARANDOMLYLONGBUTIDENTIFYABLESTRING", $string );
    $tab = explode( "ARANDOMLYLONGBUTIDENTIFYABLESTRING", $data );
    $newdata = '';
    foreach ( $tab as $line ) {
        $newdata .= preg_replace_callback( '%\bs:(\d+):"(.*)%', 'bioship_fix_str_length', $line );
    }
    return $newdata;
 }
}

// ------------------------------
// Fix Serialized String Callback
// ------------------------------
// 2.2.0: make non-unique and added function exists wrapper
if ( !function_exists( 'bioship_fix_str_length' ) ) {
 function bioship_fix_str_length( $matches ) {
    $string = $matches[2];
    $right_length = strlen( $string );
    return 's:' . $right_length . ':"' . $string . '";';
 }
}

// ------------------------
// === Direct Load Mode ===
// -------------------------
// 2.0.5: wp-load.php path checked in functions.php before using
if ( strstr( $_SERVER['REQUEST_URI'], 'skin.php' ) ) {

	$vthemetimestart = microtime( true );

	// 1.8.0: use DIRECTORY_SEPARATOR constant
	// 2.1.1: maybe define short directory separator
	if ( !defined( 'DIRSEP' ) ) {
		define( 'DIRSEP', DIRECTORY_SEPARATOR );
	}

	// --- call on our old friend Shorty ---
	define( 'SHORTINIT', true );
	$wploadpath = bioship_skin_find_require( 'wp-load.php' );
	if ( !$wploadpath ) {
		die( 'FATAL ERROR! wp-load.php could not be found.' );
	}
	$memorysavingmode = true;

	// Include only what you need to survive...
	// not the industrial strength hairdryer. :-D

	// get_option (option.php - loaded)
	// apply_filters (plugin.php - loaded)
	// get_bloginfo (general-template.php, version.php)
	// wp_get_theme (themes.php)
	// get_theme_data (deprecated.php - conditional load)
	// get_stylesheet_directory (themes.php)

	// --- define constants ---
	if ( !defined( 'ABSPATH' ) ) {
		define( 'ABSPATH', $wploadpath );
	}
	if ( !defined( 'WPINC' ) ) {
		define( 'WPINC', 'wp-includes' );
	}

	// --- include files required for initialization ---

	// --- general files ---
	include ABSPATH . WPINC . DIRSEP . 'version.php';
	include ABSPATH . WPINC . DIRSEP . 'general-template.php';
	include ABSPATH . WPINC . DIRSEP . 'link-template.php';

	// --- theme class and dependencies ---
	include ABSPATH . WPINC . DIRSEP . 'kses.php';
	include ABSPATH . WPINC . DIRSEP . 'shortcodes.php';
	// 2.2.0: fix for possible WP 5+ redeclaration
	if ( !function_exists( 'maybe_hash_hex_color' ) ) {
		include ABSPATH . WPINC . DIRSEP . 'formatting.php';
	}
	// 2.2.0: include block functions for filter usage
	if ( file_exists( ABSPATH . WPINC . DIRSEP . 'blocks.php' ) ) {
		include ABSPATH . WPINC . DIRSEP . 'blocks.php';
		include ABSPATH . WPINC . DIRSEP . 'class-wp-block-parser.php';
	}
	include ABSPATH . WPINC . DIRSEP . 'class-wp-theme.php';
	include ABSPATH . WPINC . DIRSEP . 'theme.php';

	// --- capabilities eg current_user_can etc. ---
	include ABSPATH . WPINC . DIRSEP . 'capabilities.php';
	include ABSPATH . WPINC . DIRSEP . 'pluggable.php';
	include ABSPATH . WPINC . DIRSEP . 'user.php';
	include ABSPATH . WPINC . DIRSEP . 'post.php';

	// WP 4.4: more functions are needed now
	$postclass = ABSPATH . WPINC . DIRSEP . 'class-wp-post.php';
	if ( file_exists( $postclass ) ) {
		include $postclass;
	}
	$restapi = ABSPATH . WPINC . DIRSEP . 'rest-api.php';
	if ( file_exists( $restapi ) ) {
		include $restapi; // 1.8.0
	}
	$userclass = ABSPATH . WPINC . DIRSEP . 'class-wp-user.php';
	if ( file_exists( $userclass ) ) {
		include $userclass; // 1.8.0
	}

	// need wp_get_attachment_image_src for Titan uploads
	include ABSPATH . WPINC . DIRSEP . 'media.php';
	// 2.2.0: fix for possible WP 5+ redeclaration
	if ( !function_exists( 'get_object_subtype' ) ) {
		$meta = ABSPATH . WPINC . DIRSEP . 'meta.php';
		if ( file_exists( $meta ) ) {
			include $meta;
		}
	}

	// ...for options.php
	// include ABSPATH . WPINC . DIRSEP . 'category.php';
	// include ABSPATH . WPINC . DIRSEP . 'taxonomy.php';
	// include ABSPATH . WPINC . DIRSEP . 'l10n.php';
	// include ABSPATH . WPINC . DIRSEP . 'locale.php';

	// Theme functions.php : skeleton_themedrive_determine_theme (copied)
	// Theme functions.php : bioship_file_hierarchy (replaced)

	// --- Load Custom Value Filters ---
	$filters = get_stylesheet_directory() . '/filters.php';
	if ( file_exists( $filters ) ) {
		include_once $filters;
	} else {
		$filters = get_template_directory() . '/filters.php';
		if ( file_exists( $filters ) ) {
			include_once $filters;
		}
	}

	// 2.1.1: copied define of WP_CONTENT_URL here
	if ( !defined( 'WP_CONTENT_URL' ) ) {
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	}

	// 1.8.0: load constants for replacement values
	if ( !defined( 'WP_CONTENT_DIR' ) ) {
		include_once ABSPATH . WPINC . '/default-constants.php';
		wp_initial_constants();
		wp_plugin_directory_constants();
		// wp_cookie_constants();
	}
}

// 2.1.2: added esc_attr fallback function
if ( !function_exists( 'esc_attr' ) ) {
	function esc_attr( $v ) {
		return $v;
	}
}

// 2.2.0: added esc_html fallback function
if ( !function_exists( 'esc_html' ) ) {
	function esc_html( $v ) {
		return $v;
	}
}


// -----------------------
// === Set Theme Paths ===
// -----------------------

// ----------------------------
// Set Directory Paths and URLs
// ----------------------------
// 2.0.9: set these globals as file hierarchy is not available
$vthemestyledir = get_stylesheet_directory();
$vthemestyleurl = get_stylesheet_directory_uri();
$vthemetemplatedir = get_template_directory();
$vthemetemplateurl = get_template_directory_uri();

// --- apply trailing slashes ---
// 1.8.0: added trailing slash fix
// 2.0.9: update to trailingslash fix
if ( function_exists( 'trailingslashit' ) ) {
	$vthemestyleurl = trailingslashit( $vthemestyleurl );
	$vthemetemplateurl = trailingslashit( $vthemetemplateurl );
} else {
	if ( '/' != substr( $vthemestyleurl, -1, 1 ) ) {$vthemestyleurl .= '/';}
	if ( '/' != substr( $vthemetemplateurl, -1, 1 ) ) {$vthemetemplateurl .= '/';}
}
// 1.8.0: added force SSL fix
// 2.2.0: use str_ireplace to ignore case
if ( is_ssl() ) {
	$vthemestyleurl = str_ireplace( 'http://', 'https://', $vthemestyleurl );
	$vthemetemplateurl = str_ireplace( 'http://', 'https://', $vthemetemplateurl );
}
// --- define WP_CONTENT_URL ---
// 1.8.0: move here for dual framework compatibility
if ( !defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
}


// --------------------------
// === Get Theme Settings ===
// --------------------------
$vtheme = wp_get_theme();

// ------------------------------
// Theme Test Drive Compatibility
// ------------------------------
$themetestdrive = bioship_themedrive_determine_theme();
if ( $themetestdrive ) {
	$vtheme = $themetestdrive;
}

// -----------
// Theme Debug
// -----------
// 1.8.5: allow for debugging
// TODO: maybe use improved debug switching from functions.php ?
if ( !$includedskin ) {
	if ( !defined( 'THEMEDEBUG' ) ) {
		// 1.9.8: fix for undefined vthemekey variable
		$themekey = preg_replace( "/\W/", "_", strtolower( $vtheme['Name'] ) );
		$themedebug = get_option( $themekey . '_theme_debug' );
		$themedebug = false;
		if ( '1' == $themedebug ) {
			$themedebug = true;
		}
		if ( isset( $_REQUEST['themedebug'] ) ) {
			$debug = $_REQUEST['themedebug'];
			// note: no on/off switching allowed here
			if ( ( '2' == $debug ) || ( 'yes' == $debug ) ) {$themedebug = true;}
			if ( ( '3' == $debug ) || ( 'no' == $debug ) ) {$themedebug = false;}
		}
		define( 'THEMEDEBUG', $themedebug );
	}
}
if ( THEMEDEBUG ) {
	echo "/* Theme Debug Mode ON */" . PHP_EOL;
}
// --- manual debug  (eg. for private theme object) ---
// echo "/* Theme: " . print_r( $vtheme,true ) ) . "*/" . PHP_EOL . PHP_EOL;

// ------------------
// Get Theme Settings
// ------------------
$vthemename = $vtheme['Name'];
$vthemename = preg_replace( "/\W/", "-", strtolower( $vthemename ) );
// echo "/* Theme: " . esc_html( $vtheme['Name'] ) . " - Name Slug: " . esc_html( $vthemename ) . " */" . PHP_EOL;

// --- check Framework switch ---
// (note: only present if Titan is turned off)
$vthemeframework = get_option( $vthemename . '_framework' );
if ( THEMEDEBUG && $vthemeframework ) {
	echo "/* " . esc_html( $vthemename ) . '_framework: ' . esc_html( $vthemeframework ) . " */";
}

if ( 'options' == $vthemeframework ) {

	// --- Options Framework ---
	$vthemename = str_replace( "-", "_", $vthemename );
	$vthemesettings = get_option( $vthemename );
	if ( THEMEDEBUG ) {
		echo "/* Options Framework */" . PHP_EOL;
	}

} else {

	// --- Titan Framework ---
	// 2.2.0: added cached options for frontend
	$vthemecachekey = $vthemename . '_options_cache';
	$settings = get_option( $vthemecachekey );
	if ( !$settings || !is_array( $settings ) || empty( $settings ) ) {
		// 1.8.0: changed to Titan Framework Options
		$vthemekey = $vthemename . '_options';
		$settings = get_option( $vthemekey );
	}

	// 2.0.5: use maybe_unserialize not is_serialized check
	$vthemesettings = maybe_unserialize( $settings );

	// 1.9.5: added theme settings to file fallback
	// 2.2.0: added check if not empty
	if ( !$vthemesettings || empty( $vthemesettings ) ) {

		$savedfile = get_stylesheet_directory() . '/debug/' . $vthemekey . '.txt';

		if ( file_exists( $savedfile ) ) {

			echo "/* Using Saved Theme Settings: " . esc_html( $savedfile ) . " */";

			// 2.0.8: fix for undefined function (depending on method)
			if ( function_exists( 'bioship_file_get_contents' ) ) {
				$saveddata = bioship_file_get_contents( $savedfile );
			} else {
				// 2.1.1: do not re-add line breaks for file function
				$filearray = file( $savedfile );
				$saveddata = implode( "", $filearray );
			}
			if ( ( strlen( $saveddata ) > 0 ) && is_serialized( $saveddata ) ) {
				$unserialized = unserialize( $saveddata );
				if ( $unserialized ) {
					$vthemesettings = $unserialized;
				} else {
					echo "/* Unserialize Error: " . esc_html( print_r( error_get_last(), true ) ) . " */" . PHP_EOL;
					// 1.9.6: added possible auto-serialization fix
					$saveddata = bioship_fix_serialized( $saveddata );
					$unserialized = @unserialize( $saveddata );
					if ( $unserialized ) {
						$vthemesettings = $unserialized;
					} else {
						echo "/* Unserialize Error: " . esc_html( print_r( error_get_last(), true ) ) . " */" . PHP_EOL;
					}
				}
			}
		}
	}

	// 2.2.0: handle empty theme settings (theme activation)
	if ( !$vthemesettings || !is_array( $vthemesettings ) ) {
		if ( !function_exists( 'bioship_options' ) ) {
			$child_options = dirname( __FILE__ ) . '/options.php';
			$parent_options = dirname( dirname( __FILE__ ) ) . '/bioship/options.php';
			if ( file_exists( $child_options ) ) {
				include $child_options;
			} elseif ( file_exists( $parent_options ) ) {
				include $parent_options;
			} else {
				echo '/* Error: Theme Settings AND Default Options not found. */';
				exit;
			}
		}
		$options = bioship_options();
		$infotypes = array( 'heading', 'note', 'info' );
		foreach ( $options as $key => $option ) {
			if ( !in_array( $option['type'], $infotypes ) ) {
				$vthemesettings[$option['id']] = $option['std'];
			}
		}
	}

	// --- maybe fix for serialized subarrays ---
	foreach ( $vthemesettings as $key => $value ) {
		// 1.9.5: fix to the fix to the fix to the fix..!
		if ( is_serialized( $value ) ) {
			$vthemesettings[$key] = unserialize( $value );
		}
	}

	// --- convert Attachment IDs to URLs for Titan Uploads (dangit) ---
	$imagenames = array( 'background_image', 'header_background_image', 'header_logo', 'loginbackgroundurl', 'loginlogourl' );
	foreach ( $imagenames as $imagename ) {
		if ( is_numeric( $vthemesettings[$imagename] ) ) {
			$image = wp_get_attachment_image_src( $vthemesettings[$imagename], 'full' );
			$vthemesettings[$imagename] = $image[0];
		}
	}
}

// 2.2.0: split display site title and tagline option values
if ( isset( $vthemesettings['header_texts'] ) ) {
	$vthemesettings['site_title'] = $vthemesettings['header_texts']['sitetitle'];
	$vthemesettings['site_description'] = $vthemesettings['header_texts']['sitedescription'];
	unset( $vthemesettings['header_texts'] );
}

// 2.0.9: use shortened theme settings variable name
$vts = $vthemesettings;

// ------------------
// Checkbox Value Fix
// ------------------
// 1.8.5: fix for empty checkboxes values
// ...err what? this is not saved anywhere yet
$checkboxes = get_option( $vthemename . '_checkboxes' );
if ( is_array( $checkboxes ) && ( count( $checkboxes ) > 0 ) ) {
	foreach ( $checkboxes as $checkbox ) {
		if ( !isset( $vts[$checkbox] ) ) {
			$vts[$checkbox] = '0';
		}
	}
}

// --------------------
// MultiCheck Value Fix
// --------------------
// 1.8.5: fix to empty multicheck array values
$multicheck = get_option( $vthemename . '_multicheck_options' );
if ( is_array( $multicheck ) && ( count( $multicheck ) > 0 ) ) {
	foreach ( $multicheck as $key => $subkeys ) {
		$value = array();
		foreach ( $subkeys as $subkey ) {
			// 2.2.0: add isset check
			if ( isset( $vts[$key] ) && is_serialized( $vts[$key] ) ) {
				$vts[$key] = @unserialize( $vts[$key] );
			}
			// 2.2.0: add isset check for old option backups
			if ( isset( $vts[$key] ) && is_array( $vts[$key] ) ) {
				if ( in_array( $subkey, $vts[$key] ) ) {
					$value[$subkey] = '1';
				} elseif ( isset( $vts[$key][$subkey] ) && ( '1' == $vts[$key][$subkey] ) ) {
					$value[$subkey] = '1';
				} else {
					$value[$subkey] = '0';
				}
			} else {
				$value[$subkey] = '0';
			}
		}
		$vts[$key] = $value;
	}
}
if ( THEMEDEBUG ) {
	// echo "/* Checkbox Options: " . esc_html( print_r( $checkboxes, true  ) ) . " */" . PHP_EOL;
	echo "/* Multicheck Options: " . esc_html( print_r( $multicheck, true ) ) . " */" . PHP_EOL;
	echo "/* Theme Options (" . esc_html( $vthemename ) . "): " . esc_html( print_r( $vts, true ) ) . " */" . PHP_EOL;
}

// ---------------------------------
// Set Internet Explorer Helper URLs
// ---------------------------------
// (note: file hierarchy only available when including)
// 2.0.9: update to possible style subdirectory paths
// 2.1.1: switch css and styles search order for template
// TODO: maybe copy and use file hierarchy function instead?
global $vthemedirs, $vpieurl, $vborderradiusurl;

// --- PIE URL ---
if ( function_exists( 'bioship_file_hierarchy' ) ) {
	$vpieurl = bioship_file_hierarchy( 'url', 'pie.php', $vthemedirs['style'] );
} elseif ( file_exists( $vthemestyledir . '/styles/pie.php' ) ) {
	$vpieurl = $vthemestyleurl . '/styles/pie.php';
} elseif ( file_exists( $vthemestyledir . '/css/pie.php' ) ) {
	$vpieurl = $vthemestyleurl . '/css/pie.php';
} elseif ( file_exists( $vthemetemplatedir . '/css/pie.php' ) ) {
	$vpieurl = $vthemetemplateurl . '/styles/pie.php';
} else {
	$vpieurl = $vthemetemplateurl . '/css/pie.php';
}
if ( is_ssl() ) {
	$vpieurl = str_ireplace( 'http://', 'https://', $vpieurl );
}

// --- Border Radius URL ---
if ( function_exists( 'bioship_file_hierarchy' ) ) {
	$vborderradiusurl = bioship_file_hierarchy( 'url', 'border-radius.htc', $vthemedirs['style'] );
} elseif ( file_exists( $vthemestyledir . '/styles/border-radius.htc' ) ) {
	$vborderradiusurl = $vthemestyleurl . '/styles/border-radius.htc';
} elseif ( file_exists( $vthemestyledir . '/css/pie.php' ) ) {
	$vborderradiusurl = $vthemestyleurl . '/css/border-radius.htc';
} elseif ( file_exists( $vthemetemplatedir . '/css/pie.php' ) ) {
	$vborderradiusurl = $vthemetemplateurl . '/styles/border-radius.htc';
} else {
	$vborderradiusurl = $vthemetemplateurl . '/css/border-radius.htc';
}
if ( is_ssl() ) {
	$vborderradiusurl = str_ireplace( 'http://', 'https://', $vborderradiusurl );
}


// ------------------
// === Typography ===
// ------------------
// 1.8.0: added missing #content typography (no longer quite the same as body due to grid)
// 1.8.5: moved here to change handling of button font rules
// 1.8.5: added navmenu and navsubmenu typographies
// 1.8.5: add headline typography CSS in any case to support customizer live preview logo/text switching
$typographies = array(
	'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'headline', 'tagline',
	'navmenu', 'navsubmenu', 'sidebar', 'subsidebar', 'content', 'footer', 'button',
);
$typographyrules = '';

// 2.1.2: fix to use of duplicate variable (typography)
foreach ( $typographies as $key ) {

	$typekey = $key . '_typography';
	if ( isset( $vts[$typekey] ) ) {

		$typography = maybe_unserialize( $vts[$typekey] );
		// echo "/* Typography: " . esc_html( print_r( $typography, true ) ) . " */"; // debug point

		// 1.8.0: fix for font-sizes, target inside divs, fix content column inners
		// 2.0.9: fix to navigation targeting for em font-size scaling
		// 2.2.0: added button element selector (body button)
		if ( 'body' == $key ) {
			$selector = "#content #maincontent";
		} elseif ( 'headline' == $key ) {
			$selector = "#header h1#site-title-text a";
		} elseif ( 'tagline' == $key ) {
			$selector = "#header #site-description .site-desc";
		} elseif ( 'header' == $key ) {
			$selector = "#header .inner";
		} elseif ( 'navmenu' == $key ) {
			$selector = "#navigation #mainmenu ul li a";
		} elseif ( 'navsubmenu' == $key ) {
			$selector = "#navigation #mainmenu ul ul li a";
		} elseif ( 'sidebar' == $key ) {
			$selector = "#sidebar .sidebar";
		} elseif ( 'subsidebar' == $key ) {
			$selector = "#subsidebar .sidebar";
		} elseif ( 'content' == $key ) {
			// 2.2.0: added #woocommercecontent selector
			$selector = "#content .entry-content, #content .column .inner, #content .columns .inner, , #woocommercecontent";
		} elseif ( 'footer' == $key ) {
			$selector = "#footer #mainfooter";
		} elseif ( 'button' == $key ) {
			$selector = "body button, body input[type='reset'], body input[type='submit'], body input[type='button'], body a.button, body button, body .button ";
		} else {
			// for h1, h2, h3, h4, h5, h6
			$selector = $key;
		}

		if ( isset( $typography ) && is_array( $typography ) ) {

			// --- fix for typography keys ---
			// 1.8.0: adjust for Titan Framework Typography
			if ( isset( $typography['font-size'] ) ) {
				$typography['size'] = $typography['font-size'];
			}
			if ( isset( $typography['font-family'] ) ) {
				$typography['face'] = $typography['font-family'];
			}
			if ( isset( $typography['font-style'] ) ) {
				$typography['style'] = $typography['font-style'];
			}

			// --- rule wrappers ---
			// 2.1.2: change to key for fix to use of duplicate variable typography
			if ( 'button' != $key ) {
				$typorules = $selector . " {";
			} else {
				$typorules = '';
			}

			// --- font colour ---
			if ( '' != $typography['color'] ) {
				$typorules .= "color:" . $typography['color'] . "; ";
			}

			// --- font size ---
			if ( '' != $typography['size'] ) {

				$fontsize = $typography['size'];
				// 2.0.9: convert font sizes to em for better screen scaling
				if ( ( 'headline' == $key ) || ( 'tagline' == $key ) ) {
					// not for title/description so they can be autoscaled correctly
					$typorules .= "font-size:" . $fontsize . "; ";
				} elseif ( ( 'inherit' == $fontsize ) || ( 'initial' == $fontsize ) ) {
					// also do not convert font property value of inherit
					// 2.1.1: also do not convert font property value of initial
					$typorules .= "font-size:" . $fontsize . "; ";
				} else {
					// 2.2.0: only convert to em if in pixels
					if ( strstr( $fontsize, 'px' ) ) {
						$fontsize = (int) str_replace( 'px', '', $fontsize );
						$fontsize = bioship_round_half_down( $fontsize / 16 );
					}
					$typorules .= "font-size:" . $fontsize;
					if ( !strstr( $fontsize, 'em' ) ) {
						$typorules .= "em";
					}
					$typorules .= "; ";
				}
			}

			// --- font face ---
			if ( '' != $typography['face'] ) {
				if ( '"inherit"' == $typography['face'] ) {
					$typography['face'] = 'inherit';
				}
				if ( strstr( $typography['face'], '+' ) ) {
					$typography['face'] = '"' . str_replace( '+', ' ', $typography['face'] ) . '"';
				}
				// 1.8.0: detect font stacks vs. singular fonts to add maybe quotes
				// 2.1.2: fix to remove double quotes around inherit value
				if ( strstr( $typography['face'], ',' ) || ( 'inherit' == $typography['face'] ) ) {
					$typorules .= "font-family:" . $typography['face'] . "; ";
				} else {
					$typorules .= "font-family:\"" . $typography['face'] . "\"; ";
				}
			}

			// --- font style to bold fix ---
			// 1.8.0: fix as options framework 'style' value can be set to bold
			if ( '' != $typography['style'] ) {
				if ( 'bold' == $typography['style'] ) {
					$typorules .= "font-weight: bold; ";
				} else {
					$typorules .= "font-style:" . $typography['style'] . "; ";
				}
			}

			// --- font weight and line height ---
			// 1.8.0: Titan Framework extended typography options
			if ( isset( $typography['font-weight'] ) ) {
				$typorules .= "font-weight:" . $typography['font-weight'] . "; ";
			}
			if ( isset( $typography['line-height'] ) ) {
				$typorules .= "line-height:" . $typography['line-height'] . "; ";
			}

			// --- letter spacing, text transform, font variant ---
			// note: these are rather superfluous...
			if ( isset( $typography['letter-spacing'] ) ) {
				$typorules .= "letter-spacing:" . $typography['letter-spacing'] . "; ";
			}
			if ( isset( $typography['text-transform'] ) ) {
				$typorules .= "text-transform:" . $typography['text-transform'] . "; ";
			}
			if ( isset( $typography['font-variant'] ) ) {
				$typorules .= "font-variant:" . $typography['font-variant'] . "; ";
			}

			// note: these are Titan text-shadow attributes
			// (off as seem superfluous to set for all fonts, leave for setting manually)
			// [text-shadow-location] => none
			// [text-shadow-distance] => 0px
			// [text-shadow-blur] => 0px
			// [text-shadow-color] => #333333
			// [text-shadow-opacity] => 1

			// 1.8.5: store button font rules to use for button selectors
			if ( 'button' == $key ) {
				$buttonfontrules = $typorules;
			} else {
				$typorules .= "}" . PHP_EOL;
				$typographyrules .= $typorules;
			}

		}
	}
}


// ---------------
// === Buttons ===
// ---------------
// 1.5.0: added body prefix to better override skeleton defaults
// 2.2.0: added missing matching selector (body button)
// 2.2.0: set button selector variable
$buttonselector = "body button, body input[type='reset'], body input[type='submit'], body input[type='button'], body a.button, body button, body .button";
$buttons = $buttonrules = '';
if ( ( '' == $vts['button_bgcolor_bottom'] ) || ( $vts['button_bgcolor_bottom'] == $vts['button_bgcolor_top'] ) ) {
	// 2.1.0: set rule empty if top button ackground color is also not specified yet
	if ( '' != $vts['button_bgcolor_top'] ) {
		$buttonrules .= "background: " . $vts['button_bgcolor_top'] . "; ";
		$buttonrules .= "background-color: " . $vts['button_bgcolor_top'] . ";" . PHP_EOL;
		// $buttonrules .= "	behavior: url('" . $vpieurl . "');" . PHP_EOL;
	}
} else {
	$top = $vts['button_bgcolor_top'];
	$bottom = $vts['button_bgcolor_bottom'];
	$buttonrules .= "background: " . $top . "; background-color: " . $top . ";" . PHP_EOL;
	$buttonrules .= "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, " . $top . "), color-stop(100%, " . $bottom . "));" . PHP_EOL;
	$buttonrules .= "background: -webkit-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$buttonrules .= "background: -o-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$buttonrules .= "background: -ms-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$buttonrules .= "background: -moz-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$buttonrules .= "background: linear-gradient(top bottom, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$buttonrules .= "-pie-background: linear-gradient(top, " . $top . ", " . $bottom . ");" . PHP_EOL;
	$buttonrules .= "behavior: url('" . $vpieurl . "');" . PHP_EOL;
}
// 1.8.5: added button font rules here directly instead
// 2.1.2: added check if button font rules are set
if ( isset( $buttonfontrules ) && ( '' != $buttonfontrules ) ) {
	$buttonrules .= PHP_EOL . $buttonfontrules . PHP_EOL;
}

// --- add WooCommerce button selectors ---
// 1.8.5: added woocommerce button selector option
// 1.8.5: set woocommerce button selectors
$woocommercebuttons = array(
	'.woocommerce a.alt.button', '.woocommerce button.alt.button', '.woocommerce input.alt.button',
	'.woocommerce #respond input.alt#submit', '.woocommerce #content input.alt.button',
	'.woocommerce-page a.alt.button', '.woocommerce-page button.alt.button', '.woocommerce-page input.alt.button',
	'.woocommerce-page #respond input.alt#submit', '.woocommerce-page #content input.alt.button',
);
if ( isset( $vts['woocommercebuttons'] ) && ( '1' == $vts['woocommercebuttons'] ) ) {
	$woocommerceselectors = implode( ', ', $woocommercebuttons );
	$buttonselector .= ', ' . PHP_EOL . $woocommerceselectors . ' ';
}

// --- add the rules to the button output ---
if ( '' != $buttonrules ) {
	$buttons .= $buttonselector . ' {' . PHP_EOL . $buttonrules . '}' . PHP_EOL;
}

// --- extra Button Selectors ---
// 1.5.0: add extra button selectors to override later 3rd party rules with !important
$extrabuttons = trim( $vts['extrabuttonselectors'] );
if ( '' != $extrabuttons ) {
	$extrabuttonrules = $extrabuttons . ' {' . str_replace( ';', ' !important;', $buttonrules ) . '}' . PHP_EOL;
	$buttons .= $extrabuttonrules;
}


// ---------------------
// === Hover Buttons ===
// ---------------------
// 2.2.0: set hover button selector variable
$hoverbuttonselector = "body button:hover, body input[type='submit']:hover, body input[type='reset']:hover, body input[type='button']:hover, body a.button:hover, body button:hover, body .button:hover";
$hoverbuttonrules = '';
if ( ( '' == $vts['button_hoverbg_bottom'] ) || ( $vts['button_hoverbg_bottom'] == $vts['button_hoverbg_top'] ) ) {
	if ( isset( $vts['button_hoverbg_top'] ) && ( '' != $vts['button_hoverbg_top'] ) ) {
		$hoverbuttonrules .= "background: " . $vts['button_hoverbg_top'] . "; ";
		$hoverbuttonrules .= "background-color: " . $vts['button_hoverbg_top'] . ";" . PHP_EOL;
		// $hoverbuttonrules .= "behavior: url('" . $vpieurl . "');" . PHP_EOL;
	}
} else {
	$top = $vts['button_hoverbg_top'];
	$bottom = $vts['button_hoverbg_bottom'];
	$hoverbuttonrules .= "background: " . $top . "; background-color: " . $top . ";" . PHP_EOL;
	$hoverbuttonrules .= "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, " . $top . "), color-stop(100%, " . $bottom . "));" . PHP_EOL;
	$hoverbuttonrules .= "background: -webkit-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$hoverbuttonrules .= "background: -o-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$hoverbuttonrules .= "background: -ms-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$hoverbuttonrules .= "background: -moz-linear-gradient(top, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$hoverbuttonrules .= "background: linear-gradient(top bottom, " . $top . " 0%, " . $bottom . " 100%);" . PHP_EOL;
	$hoverbuttonrules .= "-pie-background: linear-gradient(top, " . $top . ", " . $bottom . ");" . PHP_EOL;
	if ( '' != $vts['button_font_hover'] ) {
		$hoverbuttonrules .= "color: " . $vts['button_font_hover'] . ";" . PHP_EOL;
	}
	$hoverbuttonrules .= "	behavior: url('" . $vpieurl . "');" . PHP_EOL;
}

// 1.8.5: added woocommerce hover button selector option
if ( isset( $vts['woocommercebuttons'] ) && ( '1' == $vts['woocommercebuttons'] ) ) {
	$woohoverbuttons = array();
	foreach ( $woocommercebuttons as $woocommercebutton ) {
		$woohoverbuttons[] = $woocommercebutton . ':hover';
	}
	$woohoverselectors = implode( ', ', $woohoverbuttons );
	$hoverbuttonselector .= ', ' . PHP_EOL . $woohoverselectors . ' ';
}

// --- add the hover rules to the button output ---
if ( isset( $hoverbuttonrules ) && ( '' != $hoverbuttonrules ) ) {
	$buttons .= $hoverbuttonselector . ' {' . PHP_EOL . $hoverbuttonrules . "}" . PHP_EOL;
}

// --- extra button selectors hover ---
// 1.5.0: add extra button selectors suffixed with both :hover and !important
if ( '' != $extrabuttons ) {
	if ( strstr( $extrabuttons, ',' ) ) {
		$extrabuttonarray = explode( ',', $extrabuttons );
		$extrabuttonrules = '';
		// --- add :hover suffix to each selector ---
		foreach ( $extrabuttonarray as $extrabutton ) {
			if ( '' != $extrabuttonrules ) {
				$extrabuttonrules .= ', ';
			}
			$extrabuttonrules .= 'body ' . trim( $extrabutton ) . ':hover';
		}
		// --- make the rules important ---
		$extrabuttonrules .= '{' . str_replace( ';', ' !important;', $buttonrules ) . '}' . PHP_EOL;
	} else {
		$extrabuttonrules = 'body ' . $extrabuttons . ':hover {' . str_replace( ';', ' !important;', $buttonrules ) . '}' . PHP_EOL;
	}
	$buttons .= $extrabuttonrules;
}

// --------------
// === Inputs ===
// --------------
// 1.8.5: added input styling options (available here for login form)
// 1.9.6: added missing number input type, select option and optgroup
// 2.0.8: added missing body prefix to number input selector
// 2.0.9: added missing input type email for consistent styling
if ( isset( $vts['inputcolor'] ) || isset( $vts['inputbgcolor'] ) ) {
	// 2.2.1: fix to not change background color of selected options
	$inputs = "body input[type='text'], body input[type='checkbox'], body input[type='password'], body input[type='number'], body input[type='email'], body select, body select option:not(:checked), body select optgroup, body textarea {";
	if ( isset( $vts['inputcolor'] ) && ( '' != $vts['inputcolor'] ) ) {
		$inputs .= "color: " . $vts['inputcolor'] . "; ";
	}
	if ( isset( $vts['inputbgcolor'] ) && ( '' != $vts['inputbgcolor'] ) ) {
		$inputs .= "background-color: " . $vts['inputbgcolor'] . ";";
	}
	$inputs .= "}" . PHP_EOL . PHP_EOL;
}


// -------------
// === Links ===
// -------------
// 2.2.0: use variable for link selector
$links = $linkrules = '';
$linkselector = "body a, body a:visited";
if ( ( 'inherit' != $vts['alinkunderline'] ) || ( '' != $vts['link_color'] ) ) {
	if ( '' != $vts['link_color'] ) {
		$linkrules .= "color:" . $vts['link_color'] . ";";
	}
	if ( ( 'inherit' != $vts['alinkunderline'] ) && ( '' != $vts['alinkunderline'] ) ) {
		$linkrules .= " text-decoration:" . $vts['alinkunderline'] . ";";
	}
}
if ( '' != $linkrules ) {
	$links .= $linkselector . ' {' . $linkrules . '}' . PHP_EOL;
}

// 2.2.0: fix to incorrect key check (link_color)
$linkrules = '';
$linkselector = "body a:hover, body a:focus, body a:active";
if ( ( 'inherit' != $vts['alinkhoverunderline'] ) || ( '' != $vts['link_hover_color'] ) ) {
	if ( '' != $vts['link_hover_color'] ) {
		$linkrules .= "color:" . $vts['link_hover_color'] . ";";
	}
	if ( ( 'inherit' != $vts['alinkhoverunderline'] ) && ( '' != $vts['alinkhoverunderline'] ) ) {
		$linkrules .= " text-decoration:" . $vts['alinkhoverunderline'] . ";";
	}
}
if ( '' != $linkrules ) {
	$links .= $linkselector . ' {' . $linkrules . '}' . PHP_EOL;
}


// -----------------------
// === Body Background ===
// -----------------------
// 1.8.5: moved login background image url rule
// 2.1.3: set default body font (for fonts set to inherit)
$body = 'font-family: arial, helvetica;';
if ( '' != $vts['body_bg_color'] ) {
	$body .= "background-color: " . $vts['body_bg_color'] . ";";
}

// IDEA: maybe use Customizer background image as override ?
// $backgroundimage = get_theme_mod('background_image');
// if (!$backgroundimage) {}

// 2.2.0: fix to possible SSL mismatch
$backgroundimage = $vts['background_image'];
if ( is_ssl() ) {
	$backgroundimage = str_ireplace( 'http://', 'https://', $backgroundimage );
}
if ( '' != $vts['background_image'] ) {
	$body .= " background-image: url('" . $backgroundimage . "');";
}
if ( '' != $vts['background_size'] ) {
	$body .= " background-size: " . $vts['background_size'] . ";";
}
if ( '' != $vts['background_position'] ) {
	$body .= " background-position: " . $vts['background_position'] . ";";
}
if ( '' != $vts['background_repeat'] ) {
	$body .= " background-repeat: " . $vts['background_repeat'] . ";";
}
if ( '' != $vts['background_attachment'] ) {
	$body .= " background-attachment: " . $vts['background_attachment'] . ";";
}

// --- declare empty style string ---
$styles = '';

// --------------------
// === Admin Styles ===
// --------------------
if ( $adminstyles ) {

	// --- output matching button styles ---
	// 2.2.0: added strip_tags to button styles output
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo strip_tags( $buttons ) . PHP_EOL;

	// --- admin button fix ---
	// 2.2.0: added rules to preserve button text
	// echo "body.wp-admin button, body.wp-admin input[type='submit'], body.wp-admin input[type='reset'],
	// body.wp-admin input[type='button'], body.wp-admin a.button, body.wp-admin button a, body.wp-admin .button {color: inherit;}".PHP_EOL;

	// --- Theme Settings admin styles ---
	$admincss = bioship_skin_css_replace_values( $vts['dynamicadmincss'] );
	if ( function_exists( 'apply_filters' ) ) {
		$admincss = apply_filters( 'skin_dynamic_admin_css', $admincss );
	}
	// 2.2.0: added strip_tags to style output
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo strip_tags( $admincss ) . PHP_EOL;

	// --- Hybrid Hook ---
	// 1.8.5: added simple hybrid hook style fixes
	if ( isset( $vts['hybridhook'] ) && ( '1' == $vts['hybridhook'] ) ) {
		$styles .= PHP_EOL . "/* Hybrid Hook fixes */
		.hook-editor textarea {width: 100%;}
		.hook-editor .alignleft {float: none;}
		" . PHP_EOL . PHP_EOL;
	}

	// --- Admin Bar Icons ---
	// 2.2.0: moved bar icon styles to individual menu items
	// $styles .= $baricons;

	// --- Sidebar Widgets page ---
	// 1.9.0: admin Widget page sidebar class styles
	$styles .= PHP_EOL . "/* Widget Sidebars */
	.sidebar-on {background-color:#F0F0FF;} .sidebar-on h2 {font-size: 13pt;}
	.sidebar-off {background-color:#F3F3FF;} .sidebar-off h2 {font-weight: normal; font-size: 10pt;}" . PHP_EOL . PHP_EOL;

	// 2.2.0: added strip_tags to style output
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo strip_tags( $styles );

	// 2.1.1: return here if no login styles
	if ( !$loginstyles ) {
		return;
	}
}

// --------------------
// === Login Styles ===
// --------------------
// 2.1.1: combined check conditions
if ( $adminstyles && $loginstyles ) {

	// 1.8.5: add input and link styling (since login is 'frontend')
	// 1.9.6: target input styling
	$inputs = str_replace( 'body', 'body.login', $inputs );
	$login = $inputs . PHP_EOL . $links . PHP_EOL;

	// Login Background
	// ----------------
	$loginbackgroundurl = $vts['loginbackgroundurl'];
	// 2.2.0: fix to possible SSL conflict
	if ( is_ssl() ) {
		$loginbackgroundurl = str_ireplace( 'http://', 'https://', $loginbackgroundurl );
	}
	if ( '' != $loginbackgroundurl ) {
		$login .= "body.login {background-image: url('" . $loginbackgroundurl . "');}" . PHP_EOL;
	} elseif ( $body ) {
		// 1.9.6: fix to missing fallback to main background
		$login .= "body.login {" . $body . "}" . PHP_EOL;
	}

	// Login Wrapper Hack Restyling
	// ----------------------------
	// (thanks to our login body class hack)
	// 1.9.5: added body.login prefix (to not conflict with theme my login)
	// 2.0.9: full border radius for reset password form (not just top corners)
	// 2.2.0: override mismatched style applied to form borders (WP ~5.3+)
	$login .= "body.login #loginwrapper {padding-top:8%;}" . PHP_EOL;
	$login .= "body.login #loginform, body.login #lostpasswordform, body.login #registerform {border-radius:20px 20px 0 0; border: transparent;}" . PHP_EOL;
	$login .= "body.login #resetpassform {border-radius: 20px;}" . PHP_EOL;
	$login .= "body.login #login {padding:0 20px 10px 20px;} body.login #nav {text-align:right;}" . PHP_EOL;
	$login .= "body.login #nav, body.login #backtoblog {margin:0 !important; padding:0 !important;}" . PHP_EOL;
	$login .= "body.login #nav a, body.login #backtoblog a {line-height: 3em; padding: 20px}" . PHP_EOL;
	$login .= "body.login #backtoblog {border-radius: 0 0 20px 20px;}" . PHP_EOL;
	$login .= "body.interim-login #loginform {border-radius:20px !important;}" . PHP_EOL;

	// Login Wrap Background Colour
	// ----------------------------
	if ( isset( $vts['loginwrapbgcolor'] ) && ( '' != $vts['loginwrapbgcolor'] ) ) {
		$loginbgcolor = 'background-color: ' . $vts['loginwrapbgcolor'] . ';';
	} else {
		// 2.0.8: if background empty explicitly set transparency for consistency
		$loginbgcolor = 'background-color: transparent; box-shadow: none;';
	}

	// Login Wrap Text Colour
	// ----------------------
	// 2.2.0: added login wrap box text colour option
	if ( isset( $vts['loginwrapcolor'] ) && ( '' != $vts['loginwrapcolor'] ) ) {
		$logincolor = 'color: ' . $vts['loginwrapcolor'] . ';';
	}
	// else {
	//	$logincolor = 'color: inherit;';
	// }

	// Add Login Background and Text Colours
	// -------------------------------------
	// 2.0.9: add missing CSS targeting for #registerform and #resetpassform
	$login .= "body.login #loginform, body.login #lostpasswordform, body.login #registerform, body.login #resetpassform, body.login #nav, body.login #backtoblog {" . $loginbgcolor . " " . $logincolor . "}" . PHP_EOL;

	// Login Logo
	// ----------
	// 1.8.5: added missing 'none' setting handler
	if ( 'none' == $vts['loginlogo'] ) {
		$login .= 'body.login h1 {display:none;}' . PHP_EOL;
	} elseif ( 'default' != $vts['loginlogo'] ) {

		// --- get the Logo Image Size ---
		$imageurl = false;
		if ( ( 'custom' == $vts['loginlogo'] ) && ( '' != $vts['header_logo'] ) ) {
			$imageurl = $vts['header_logo'];
		} elseif ( ( 'upload' == $vts['loginlogo'] ) && ( '' != $vts['loginlogourl'] ) ) {
			$imageurl = $vts['loginlogourl'];
		}
		// 2.2.0: fix to possible SSL conflict
		if ( is_ssl() ) {
			$imageurl = str_ireplace( 'http://', 'https://', $imageurl );
		}

		if ( $imageurl ) {

			$cachekey = 'login_logo';

			// 1.8.5: allow for login logo size refresh via querystring
			// (can be used to recheck image size - if you overwrite the image but keep same URL)
			if ( isset( $_GET['loginlogo_image_size'] ) && ( 'refresh' === $_GET['loginlogo_image_size'] ) ) {
				delete_option( $vthemename . '_' . $cachekey );
			}

			$imagesize = bioship_skin_get_image_size( $imageurl, $cachekey );

			// --- set the Login Logo styles ---
			if ( $imagesize ) {

				// 2.1.3: fix to undefined variable warning
				$width = $imagesize['0'];
				$height = $imagesize['1'];
				$logo = 'body.login h1 a {' . PHP_EOL;

				// --- logo image URL ---
				// 2.2.0: remove duplication of getting image URL
				$logo .= ' background-image: url("' . $imageurl . '") !important;' . PHP_EOL;

				// --- no repeat ---
				$logo .= ' background-repeat: no-repeat !important;' . PHP_EOL;

				// ---- width height and size ---
				// if (!$imagesize) {$logos .= ' width:100%; height:auto; background-size:100% auto;';}
				// else {
					$logo .= ' width: ' . $width . 'px !important;' . PHP_EOL;
					$logo .= ' height: ' . $height . 'px !important;' . PHP_EOL;
					$logo .= ' background-size: ' . $width . 'px ' . $height . 'px !important;' . PHP_EOL;
				// }
				// if (!$imagesize) {$logos .= ' margin-left: -50%;';}
				// else {
					if ( $width > 320 ) {
						$logos .= '  margin-left: -' . round( ( $width - 320 ) / 2 ) . 'px;';
					}
				// }

				$logo .= PHP_EOL . '}' . PHP_EOL;
				$login .= $logo;
			}
		}
	}

	// 2.1.1: add filter for login styles
	if ( function_exists( 'apply_filters' ) ) {
		$login = apply_filters( 'skin_login_styles', $login );
	}

	// --- output login styles ---
	// 1.9.6: use return not exit here
	// 2.2.0: use strip_tags on login styles
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo strip_tags( $login );
	return;
}


// ----------------------
// === Compile Styles ===
// ----------------------

// -----------
// Body Styles
// -----------
// 2.1.3: simplified as one body rule is now always set
$styles = "body {" . $body . "}" . PHP_EOL . PHP_EOL;

// ---------------------
// Wrap Container Styles
// ---------------------
// 1.8.5: added maximum layout width wrapper rule
if ( THEMEDEBUG ) {
	$styles .= "/* Raw Max Width: " . $vts['layout'] . "px */" . PHP_EOL;
}
$maximumwidth = bioship_round_half_down( abs( intval( $vts['layout'] ) ) / 16 );
if ( THEMEDEBUG ) {
	$styles .= "/* Sanitized Max Width: " . $maximumwidth . "em */" . PHP_EOL;
}
if ( $maximumwidth > 320 ) {
	$styles .= "#wrap {max-width: " . $maximumwidth . "em;}" . PHP_EOL;
}

// -----------------
// Stick Logo Styles
// -----------------
if ( isset( $vts['stickylogo'] ) && ( '1' == $vts['stickylogo'] ) ) {
	$styles .= "#site-logo.sticky-logo {position: fixed; top: 0; left: 0; z-index: 5; -webkit-transition: all .1s ease; ";
 	$styles .= "-moz-transition: all .1s ease; -o-transition: all .1s ease; transition: all .1s ease;}" . PHP_EOL;
}

// ------------------------
// Header Background Styles
// ------------------------
$header = '';
if ( '' != $vts['headerbgcolor'] ) {
	$header .= "background-color: " . $vts['headerbgcolor'] . ";";
}

// IDEA: maybe use custom header_image as override ?
// $headerimage = get_theme_mod('header_image');
// if (!$headerimage) {}

// 2.2.0: fix to possible SSL conflict
$headerimage = $vts['header_background_image'];
if ( is_ssl() ) {
	$headerimage = str_ireplace( 'http://', 'https://', $headerimage );
}
if ( '' != $headerimage ) {

	$header .= " background-image: url('" . $headerimage . "');";

	$cachekey = 'header_image';

	// 1.8.5: allow for header image background size refresh via querystring
	// (can be used to recheck image size - if you overwrite the image but keep same URL)
	if ( isset( $_GET['header_image_size'] ) && ( 'refresh' === $_GET['header_image_size'] ) ) {
		delete_option( $vthemename . '_' . $cachekey );
	}

	// 1.8.5: improved image size handling function
	$imagesize = bioship_skin_get_image_size( $headerimage, $cachekey );

	if ( $imagesize ) {
		// 1.8.5: account for maximum layout width and maybe scale to it
		if ( $imagesize[0] > $vts['layout'] ) {
			$ratio = $imagesize[1] / $imagesize[0];
			$imagesize[1] = $vts['layout'] * $ratio;
			$imagesize[0] = $vts['layout'];
		}

		// 1.8.5: set header width and height in em
		// 1.9.5: only set the height in em if not using repeat or repeat-y
		if ( ( 'repeat' != $vts['header_background_repeat'] ) && ( 'repeat-y' != $vts['header_background_repeat'] ) ) {
			$fontpercent = 100;
			$height = bioship_round_half_down( $imagesize[1] / 16 * $fontpercent / 100 );
			$header .= " height: " . $height . "em;";
		}
	}
}

if ( '' != $vts['header_background_size'] ) {
	$header .= " background-size: " . $vts['header_background_size'] . ";";
}
if ( '' != $vts['header_background_position'] ) {
	$header .= " background-position: " . $vts['header_background_position'] . ";";
}
if ( '' != $vts['header_background_repeat'] ) {
	$header .= " background-repeat: " . $vts['header_background_repeat'] . ";";
}
// if ( '' != $vts['header_background_attachment'] ) {
//	$header .= " background-attachment: " . $vts['header_background_attachment'] . ";";
// }

// 1.8.5: fix to check typo here
if ( '' != $header ) {
	$styles .= "#header {" . $header . "}" . PHP_EOL;
}
$styles .= PHP_EOL;

// --------------------------
// Header Text Display Styles
// --------------------------
// 1.8.5: add header text display rules
// 2.2.0: changed header_text multicheck to single items

// --- site title display ---
if ( isset( $vts['site_title'] ) && ( '1' != $vts['site_title'] ) ) {
	$styles .= '#header h1#site-title-text a {display:none;}' . PHP_EOL;
}
// --- site description display ---
if ( isset( $vts['site_description'] ) && ( '1' != $vts['site_description'] ) ) {
	$styles .= '#header #site-description .site-desc {display: none;}' . PHP_EOL;
}
$styles .= PHP_EOL;

// ------------------------
// Background Colour Styles
// ------------------------
$bgcolors = array( 'wrap', 'content', 'sidebar', 'subsidebar', 'footer' );
foreach ( $bgcolors as $bgcolor ) {
	$bgcolorref = $bgcolor . 'bgcolor';
	if ( '' != $vts[$bgcolorref] ) {
		$selector = "#" . $bgcolor;
		// 2.2.0: add #woocommercecontent selector for content
		if ( 'content' == $bgcolor ) {
			$selector .= ', #woocommercecontent';
		}
		$styles .= $selector . " {background-color: " . $vts[$bgcolorref] . ";}" . PHP_EOL;
	}
}
$styles .= PHP_EOL;

// ----------------------
// Navigation Menu Styles
// ----------------------
$navmenurules = '';

// --- navigation menu autospacing option ---
// 1.8.5: added autospacing of top level menu items option
if ( isset( $vts['navmenuautospace'] ) && ( '1' == $vts['navmenuautospace'] ) ) {
	// 1.9.5: fix to dashes in theme name for theme mods
	$thememods = get_option( 'theme_mods_' . str_replace( '_', '-', $vthemename ) );
	// $navmenurules .= '/* Theme Mods: ' . print_r( $thememods, true ) . ' */';
	if ( isset( $thememods['nav_menu_locations']['primary'] ) && ( '' != $thememods['nav_menu_locations']['primary'] ) ) {
		// note: this is checked and set in skull.php with superfish.js check
		$menumainitems = get_option( $vthemename . '_menumainitems' );
		// $navmenurules .= '/* Menu Items: ' . $menumainitems . ' */';
		if ( ( '' != $menumainitems ) && ( $menumainitems > 0 ) ) {
			$itempercent = bioship_round_half_down( 99 / $menumainitems );
			$navmenurules .= "#navigation #mainmenu ul li {width: " . $itempercent . "%; min-width:6em;}" . PHP_EOL;
			$navmenurules .= "#navigation #mainmenu ul ul li {width: 100%;}" . PHP_EOL;
		}
	}
}

// --- sticky navigation bar ---
// 2.2.0: added rules for sticky navbar
if ( isset( $vts['stickynavbar'] ) && ( '1' == $vts['stickynavbar'] ) ) {
	$navmenurules .= "#navigation {-webkit-transition: all .5s ease; -moz-transition: all .5s ease; -o-transition: all .5s ease; transition: all .5s ease;}" . PHP_EOL;
	$navmenurules .= "#navigation.sticky-navbar {position: fixed; top: 0; left: 0; z-index: 5; -webkit-transition: all .1s ease; ";
 	$navmenurules .= "-moz-transition: all .1s ease; -o-transition: all .1s ease; transition: all .1s ease;}" . PHP_EOL;
}

// --- menu container background colour ---
// 1.8.5: added navigation container, submenu container and item background color options
if ( isset( $vts['navmenubgcolor'] ) && ( '' != $vts['navmenubgcolor'] ) ) {
	$navmenurules .= "#navigation, #navigation #mainmenu, #navigation #mainmenu ul {background-color: " . $vts['navmenubgcolor'] . ";}" . PHP_EOL;
}

// --- submenu container background colour ---
if ( isset( $vts['navmenusubbgcolor'] ) && ( '' != $vts['navmenusubbgcolor'] ) ) {
	$navmenurules .= "#navigation #mainmenu ul ul, #navigation #mainmenu ul ul li {background-color: " . $vts['navmenusubbgcolor'] . ";}" . PHP_EOL;
}

// --- menu item background colour ---
if ( isset( $vts['navmenuitembgcolor'] ) && ( '' != $vts['navmenuitembgcolor'] ) ) {
	// 2.0.9: add current-menu-item class as active class alternative
	$navmenurules .= "#navigation #mainmenu ul li, #navigation #mainmenu ul li.active li, #navigation #mainmenu ul li.current-menu-item li";
	$navmenurules .= " {background-color: " . $vts['navmenuitembgcolor'] . ";}" . PHP_EOL;
}

// --- active menu item font color ---
// 1.8.5: added active and hover item options
// 1.9.5: changed isset tests to maybe not output
$navmenuactive = '';
if ( isset( $vts['navmenuactivecolor'] ) && ( '' != $vts['navmenuactivecolor'] ) ) {
	$navmenuactive .= "color: " . $vts['navmenuactivecolor'] . "; ";
}
// --- active menu item background color ---
if ( isset( $vts['navmenuactivebgcolor'] ) && ( '' != $vts['navmenuactivebgcolor'] ) ) {
	$navmenuactive .= "background-color: " . $vts['navmenuactivebgcolor'] . ";";
}
// --- add active menu item rules ---
if ( '' != $navmenuactive ) {
	// 2.0.9: fix to rule output variable typo
	// 2.0.9: add li.active a rule to fix inheritance
	$navmenurules .= "#navigation #mainmenu ul li.active, #navigation #mainmenu ul li.active a, ";
	// 2.0.9: add current-menu-item class as active class alternative
	$navmenurules .= "#navigation #mainmenu ul li.current-menu-item, #navigation #mainmenu ul li.current-menu-item a";
	$navmenurules .= " {" . $navmenuactive . "}" . PHP_EOL;
}

// --- menu hover font color ---
$navmenuhover = '';
if ( isset( $vts['navmenuhovercolor'] ) && ( '' != $vts['navmenuhovercolor'] ) ) {
	$navmenuhover .= "color: " . $vts['navmenuhovercolor'] . "; ";
}
// --- menu hover background colour ---
if ( isset( $vts['navmenuhoverbgcolor'] ) && ( '' != $vts['navmenuhoverbgcolor'] ) ) {
	$navmenuhover .= "background-color: " . $vts['navmenuhoverbgcolor'] . ";";
}
// --- add hover menu item rules ---
if ( '' != $navmenuhover ) {
	// 2.0.7: fix to text hover color targeting
	// 2.1.4: add rules for menu element focus (keyboard tabbing)
	$navmenurules .= "#navigation #mainmenu ul li:hover, #navigation #mainmenu ul li:hover a, ";
	$navmenurules .= "#navigation #mainmenu ul li:focus, #navigation #mainmenu ul li a:focus {" . $navmenuhover . "}" . PHP_EOL;
}

// --- submenu hover font colour ---
// 2.0.9: added missing submenu hover color and background hover options
$submenuhover = '';
if ( isset( $vts['submenuhovercolor'] ) && ( '' != $vts['submenuhovercolor'] ) ) {
	$submenuhover .= "color: " . $vts['submenuhovercolor'] . "; ";
}
// --- submenu hover background colour ---
if ( isset( $vts['submenuhoverbgcolor'] ) && ( '' != $vts['submenuhoverbgcolor'] ) ) {
	$submenuhover .= "background-color: " . $vts['submenuhoverbgcolor'] . ";";
}

// --- add submenu item rules ---
// 2.1.1: fix to variable typo submenuhoverrules
if ( '' != $submenuhover ) {
	$navmenurules .= "#navigation #mainmenu ul ul li:hover, #navigation #mainmenu ul ul li:hover a {" . $submenuhover . "}" . PHP_EOL;
}

// --- mobile navigation menu ---
// 2.2.0: moved here from mobile.css and option added
if ( isset( $vts['mobilenavmenu'] ) && ( '1' == $vts['mobilenavmenu'] ) ) {

	$navmenurules .= "@media only screen and (max-width: 479px) {

	/* Mobile Navigation */

	#mainmenu {display:none;}
	#mainmenubutton {display:inline-block; width:33%; margin-top:5px;}
	#mainmenushow, #mainmenuhide {margin:0 auto;}

	#wrap #navigation {margin: 10px 0px; text-align:center;}
	#wrap #navigation ul li {margin-left:20px;}

	#wrap #navigation ul, #wrap #navigation ul li, #wrap #navigation ul li a {
		background: transparent; border: none;
		box-shadow:none; -moz-box-shadow:none; -webkit-box-shadow:none;	behavior:none;
	}

	#wrap #navigation ul li a, #wrap #navigation ul li.active a,
	#wrap #navigation ul li a:hover, #wrap #navigation ul li:hover a {
		border: none; padding: 2px 0; line-height: 150%;
		background: transparent; background-image: none;
	}

	/* Initial reset and hide sublevels in mobile view */
	#wrap #navigation ul.sub-menu,#navigation ul.children,
	#wrap #navigation ul.sub-menu li,#navigation ul.children li,
	#wrap #navigation ul.sub-menu li:hover,#navigation ul.children li:hover,
	#wrap #navigation ul.sub-menu li a,#navigation ul.children li a,
	#wrap #navigation ul.sub-menu li a:hover,#navigation ul.children li a:hover {
		display: none; position: relative; left: 0px; line-height: 100%;
		padding: 0; margin: 0; height: auto; background-image: none;
		background: none; border: none; border-style: none; box-shadow:none;
	}

	/* Declare new padding for submenu items */
	#wrap #navigation ul.sub-menu li a,#navigation ul.children li a,
	#wrap #navigation ul.sub-menu li a:hover,#navigation ul.children li a:hover {
		padding: 4px 0px 4px 10px;
	}

	/* Show on hover */
	#wrap #navigation li:hover ul.sub-menu,#navigation li:hover ul.children,
	#wrap #navigation li:hover ul.sub-menu li,#navigation li:hover ul.children li,
	#wrap #navigation li:hover ul.sub-menu li a,#navigation li:hover ul.children li a {
		display: inline-block;
	}" . PHP_EOL;
}

// --- add navigation menu rules to styles ---
if ( '' != $navmenurules ) {
	$styles .= $navmenurules . PHP_EOL;
}

// ---------------
// Admin Bar Fixes
// ---------------
// 2.2.0: added fixes to go with sticky navbar and sticky logo
if ( ( isset( $vts['stickynavbar'] ) && ( '1' == $vts['stickynavbar'] ) )
  || ( isset( $vts['stickylogo'] ) && ( '1' == $vts['stickylogo'] ) ) ) {
	$adminbarfixes = "body.admin-bar #navigation.sticky-navbar, body.admin-bar #header #site-logo.sticky-logo {top: 32px;}
	@media screen and (max-width: 782px) {
	  body.admin-bar #navigation.sticky-navbar {top: 46px;}
	  body.admin-bar #header #site-logo.sticky-logo {top: 46px;}
	}
	@media screen and (max-width: 600px) {
	  body.admin-bar #navigation.sticky-navbar {top: 0;}
	  body.admin-bar #header #site-logo.sticky-logo {top: 0;}
	}";
	$styles .= $adminbarfixes . PHP_EOL;
}

// -------------------------------------
// Buttons, Links, Inputs and Typography
// -------------------------------------
// (as already defined above)
$styles .= $links . PHP_EOL;
$styles .= $inputs . PHP_EOL;
$styles .= $buttons . PHP_EOL;
$styles .= $typographyrules . PHP_EOL;

// ----------------------
// Content Padding Styles
// ----------------------
if ( '' != $vts['contentpadding'] ) {
	$styles .= "#contentpadding {padding: " . $vts['contentpadding'] . ";}" . PHP_EOL;
}
$styles .= PHP_EOL;

// --- Admin Bar Icons ---
// 2.1.3: added here for frontend admin bar
// 2.2.0: moved bar icon styles to individual menu items
// $styles .= $baricons;

// ---------------------
// === Output Styles ===
// ---------------------
// 2.1.1: compile styles, filter and output
// 2.1.2: check for bioship_apply_filters function also, add prefix to apply_filters hook
if ( function_exists( 'bioship_apply_filters' ) ) {
	$styles = apply_filters( 'skin_settings_css', $styles );
} elseif ( function_exists( 'apply_filters' ) ) {
	$styles = apply_filters( 'bioship_skin_settings_css', $styles );
}
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
// 2.2.0: added strip_tags to CSS output
echo strip_tags( $styles ) . PHP_EOL;

// ---------------------------------
// === Browser and Mobile Styles ===
// ---------------------------------
// (alternative to deprecated <body> class function in muscle.php)
// - as that method does not account for the page being cached!

// 2.2.0: removed plugin auto-load (bad practice)
// special case: if using Shortinit method, maybe load PHP browser detection plugin
/* if (defined('SHORTINIT') && !function_exists('php_browser_info')) {
	$plugin = 'php-browser-detection/php-browser-detection.php';
	// 1.8.5: fix for undefined is_plugin_active function
	$activeplugins = get_option('active_plugins');
	$pluginpath = WP_CONTENT_DIR.'/plugins/'.$plugin;
	if (file_exists($pluginpath) && in_array($plugin, $activeplugins)) {include($pluginpath);}
} */

$browserstyles = '';
$classes = array();
if ( !function_exists( 'php_browser_info' ) ) {

	// --- use in-built wp functions ---
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	// $browser = wp check browser version();
	$class = array();
	if ( $is_lynx ) {
		$classes[] = 'lynx';
	} elseif ( $is_gecko ) {
		$classes[] = 'firefox';
	} elseif ( $is_opera ) {
		$classes[] = 'opera';
	} elseif ( $is_NS4 ) {
		$classes[] = 'netscape';
	} elseif ( $is_safari ) {
		$classes[] = 'safari';
	} elseif ( $is_chrome ) {
		$classes[] = 'chrome';
	} elseif ( $is_IE ) {
		$classes[] = 'ie';
	} else {
		$classes[] = 'unknown';
	}
	// 'iPhone Safari'
	if ( $is_iphone ) {
		$classes[] = 'iphone';
	}

} else {

	// --- use PHP Browser Detection plugin ---
	$browserinfo = php_browser_info();
	$browser = $browserinfo['Browser'];
	if ( 'Netscape' == $browser ) {
		$classes[] = 'netscape';
	} elseif ( 'Lynx' == $browser ) {
		$classes[] = 'Lynx';
	} elseif ( 'Firefox' == $browser ) {
		$classes[] = 'firefox';
	} elseif ( 'Safari' == $browser ) {
		$classes[] = 'safari';
	} elseif ( 'Chrome' == $browser ) {
		$classes[] = 'chrome';
	} elseif ( 'Opera' == $browser ) {
		$classes[] = 'opera';
	} elseif ( 'IE' == $browser ) {
		$classes[] = 'ie';
	}

	// --- add mobile browser styles to class list ---
	if ( is_tablet() ) {
		$classes[] = 'tablet';
	} elseif ( is_mobile() ) {
		$classes[] = 'mobile';
		if ( is_iphone() ) {
			$classes[] = 'iphone';
		} elseif ( is_ipad() ) {
			$classes[] = 'ipad';
		} elseif ( is_ipod() ) {
			$classes[] = 'ipod';
		}
	} elseif ( is_desktop() ) {
		$classes[] = 'desktop';
	}
}

if ( count( $classes ) > 0 ) {
	$browserstyles = '';
	foreach ( $classes as $class ) {
		$thisbrowserstyles = '';
		if ( isset( $vts['browser_' . $class] ) ) {
			$thisbrowserstyles = $vts['browser_' . $class];
		}

		// 2.0.9: conditionally call apply_filters to be safe
		// 2.1.2: added check for bioship_apply_filters function
		if ( function_exists( 'bioship_apply_filters' ) ) {
			$browserstyles .= bioship_apply_filters( 'muscle_browser_styles_custom_' . $class, $thisbrowserstyles );
		} elseif ( function_exists( 'apply_filters' ) ) {
			$browserstyles .= apply_filters( 'bioship_muscle_browser_styles_custom_' . $class, $thisbrowserstyles );
		} else {
			$browserstyles .= $thisbrowserstyles;
		}
	}
}

// --- output browser styles ---
if ( '' != $browserstyles ) {
	echo PHP_EOL . "/* Styles for Detected Browser/Device  */" . PHP_EOL;
	// 2.2.0: added strip tags for possible user browser styles
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo strip_tags( $browserstyles ) . PHP_EOL;
}


// ------------------
// Custom Dynamic CSS
// ------------------
// 1.8.5: no output here for customizer live preview (transport refresh)
// ...as we load the preview CSS styles dynamically and separately...
$livepreview = false;
if ( isset( $_REQUEST['livepreview'] ) && ( 'yes' == $_REQUEST['livepreview'] ) ) {
	$livepreview = true;
}
if ( !$livepreview ) {
	$customcss = $vts['dynamiccustomcss'];
	$customcss = bioship_skin_css_replace_values( $customcss );

	// 2.1.2: added check for bioship_apply_filters function
	if ( function_exists( 'bioship_apply_filters' ) ) {
		$customcss = bioship_apply_filters( 'skin_dynamic_css', $customcss );
	} elseif ( function_exists( 'apply_filters' ) ) {
		$customcss = apply_filters( 'bioship_skin_dynamic_css', $customcss );
	}

	if ( '' != $customcss ) {
		echo PHP_EOL . "/* Dynamic Custom CSS */" . PHP_EOL . PHP_EOL;
		// 1.8.0: added stripslashes to fix single quotes for Titan
		// 2.2.0: added strip_tags ala custom_css_cb function
		// (esc_html / wp_kses can mangle some custom selectors to &gt;)
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo strip_tags( stripslashes( $customcss ) ) . PHP_EOL;
	}
}

// ----------------
// Output Load Time
// ----------------
$endtime = microtime( true );
$difference = $endtime - $vthemetimestart;
echo PHP_EOL . "/* Generation Time: " . esc_html( $difference ) . " */" . PHP_EOL;

exit;
