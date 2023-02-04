<?php

// ==========
// Foundation
// ==========
// ref: http://foundation.zurb.com/docs
if ( !function_exists( 'bioship_muscle_load_foundation' ) ) {

 // 2.5.0: remove foundation loading (unused)
 // add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_foundation' );

 function bioship_muscle_load_foundation() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemevars, $vcsscachebust, $vjscachebust;

	// --- check load ---
	// 2.0.9: filter Foundation loading internally
	$load = isset( $vthemesettings['loadfoundation'] ) ? $vthemesettings['loadfoundation'] : false;
	$load = bioship_apply_filters( 'muscle_load_foundation', $load );
	if ( !$load || ( 'off' == $load ) ) {
		return;
	}

	// --- check Foundation version ---
	// 1.8.0: check Foundation 5 or 6 directory to use for loading
	// TODO: check for alternative includes directory
	if ( isset( $vthemesettings['foundationversion'] ) ) {
		$foundation = 'includes/' . $vthemesettings['foundationversion'];
	} else {
		// backwards compatibility default
		$foundation = 'includes/foundation5';
	}

	// --- force auto-load of modernizr and fastclick for Foundation 5 ---
	if ( strstr( $foundation, '5' ) ) {
		if ( !has_action( 'wp_enqueue_scripts', 'bioship_muscle_load_modernizr' ) ) {
			add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_modernizr' );
		}
		if ( !has_action( 'wp_enqueue_scripts', 'bioship_muscle_load_fastclick' ) ) {
			add_action( 'wp_enqueue_scripts', 'bioship_muscle_load_fastclick' );
		}
		$deps = array( 'jquery', 'fastclick', 'modernizr' );
	} else {
		$deps = array( 'jquery' );
	}

	// --- check script debng constant ---
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Foundation Stylesheet
	// ---------------------
	// http://foundation.zurb.com/docs/css.html
	if ( $vthemesettings['foundationcss'] ) {
		if ( 'essentials' == $vthemesettings['loadfoundation'] ) {
			$stylesheet = bioship_file_hierarchy( 'both', 'foundation.essentials.' . $suffix . '.css', array( $foundation . '/css', 'css' ) );
		} else {
			$stylesheet = bioship_file_hierarchy( 'both', 'foundation.' . $suffix . '.css', array( $foundation . '/css', 'css' ) );
		}
		if ( is_array( $stylesheet ) ) {
			if ( 'filemtime' == $vthemesettings['stylesheetcachebusting'] ) {
				$cachebust = date( 'ymdHi', filemtime( $stylesheet['file'] ) );
			} else {
				$cachebust = $vcsscachebust;
			}
			wp_register_style( 'foundation', $stylesheet['url'], array(), $cachebust );
			wp_enqueue_style( 'foundation' );
		}
	}

	// Full or Partial Foundation Javascript
	// -------------------------------------
	// http://foundation.zurb.com/docs/javascript.html

	// --- get Foundation script ---
	// 2.2.0: change fallback directory from javascripts to scripts
	// TODO: add check for alternative script directories
	if ( 'full' == $vthemesettings['loadfoundation'] ) {
		$script = bioship_file_hierarchy( 'both', 'foundation.' . $suffix . '.js', array( $foundation . '/js', 'scripts' ) );
	}
	if ( 'essentials' == $vthemesettings['loadfoundation'] ) {
		$script = bioship_file_hierarchy( 'both', 'foundation.essentials.js', array( $foundation . '/js', 'scripts' ) );
	} elseif ($vthemesettings['loadfoundation'] == 'selective') {
		$script = bioship_file_hierarchy( 'both', 'foundation.selected.js', array( 'scripts', $foundation.'/js' ) );
		// 1.8.0: note, selective javascript is currently only working for Foundation 5, so just in case, fallback to min.js
		if ( !is_array( $script ) ) {
			$script = bioship_file_hierarchy( 'both', 'foundation.' . $suffix . '.js', array( 'scripts', $foundation.'/js' ) );
		}
	}

	// --- enqueue Foundation script ---
	if ( isset( $script ) && is_array( $script ) ) {
		// 2.1.1: fix to cachebusting conditions
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $script['file'] ) );
		} else {
			$cachebust = $vjscachebust;
		}
		wp_enqueue_script( 'foundation', $script['url'], $deps, $cachebust, true );

		// --- initialize via script variable ---
		// 2.0.9: use script load variable instead of input
		// 2.1.3: add to prefixed global settings variable
		$vthemevars[] = "var bioship.loadfoundation = 'yes'; ";
	}
 }
}
