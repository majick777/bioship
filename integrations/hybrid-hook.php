<?php

// ===========
// Hybrid Hook
// ===========
// (does not require Hybrid Core to be loaded)
// (note: Hybrid Hook Widgets is available also)
// 2.2.0: moved load check to separate function
if ( !function_exists( 'bioship_muscle_load_hybrid_hook' ) ) {
	
 add_action( 'init', 'bioship_muscle_load_hybrid_hook' );

 function bioship_muscle_load_hybrid_hook() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	 
	// --- check setting ---
	// 2.2.0: simplified settings check
    // 2.2.0: added missing vthemedirs declaration
	global $vthemesettings, $vthemedirs;
	$loadhybridhook = isset( $vthemesettings['hybridhook'] ) ? $vthemesettings['hybridhook'] : false;
	// 2.0.1: filter Hybrid Hook loading here
	$loadhybridhook = bioship_apply_filters( 'muscle_load_hybrid_hook', $loadhybridhook );
	if ( !$loadhybridhook ) {
		return;
	}

	// --- check Hybrid Hook directory ---
	// 1.8.0: changed hybrid hook location to /includes/ subfolder
	// 2.1.1: check alternative includes directories
	$hybridhookdirs = array();
	if ( count( $vthemedirs['includes'] ) > 0 ) {
		foreach ( $vthemedirs['includes'] as $dir ) {
			if ( is_dir( $dir . DIRSEP . 'hybrid-hook' ) ) {
				$hybridhookdirs[] = $dir . DIRSEP . 'hybrid-hook';
			}
		}
	}

	// --- load Hybrid Hook ---
	$hybridhook = bioship_file_hierarchy( 'file', 'hybrid-hook.php', $hybridhookdirs );
	if ( !$hybridhook ) {
		return;
	}

	include $hybridhook;
	bioship_debug( "Hybrid Hook Loaded" );

	// --- load setup now ---
	// (as we have missed the plugins_loaded hook)
	hybrid_hook_setup();

	// --- add hybrid hook filters ---
	add_filter( 'hybrid_hook_allow_php', 'bioship_muscle_disallow_hook_php', 5 );
	add_filter( 'hybrid_hooks', 'bioship_muscle_hybrid_get_hooks' );
	add_filter( 'hybrid_hook_theme_prefix', 'bioship_muscle_hybrid_hook_prefix' );
 }
}

// ----------------------------------
// Disallow Hybrid Hook PHP Execution
// ----------------------------------
// 1.8.5: added this filter (as e-v-a-l commented out for Theme Check)
// (HTML / Shortcode / Widget methods are better for this anyway)
// ...this could of course be turned back on if needed by another filter
if ( !function_exists( 'bioship_muscle_disallow_hook_php' ) ) {
	function bioship_muscle_disallow_hook_php( $allowed ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		return false;
	}
}

// ----------------------------------
// Load Theme Layout for Hybrid Hooks
// ----------------------------------
if ( !function_exists( 'bioship_muscle_hybrid_get_hooks' ) ) {
 function bioship_muscle_hybrid_get_hooks() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 1.9.0: hooks now loaded by default in functions.php
	global $vthemehooks;

	if ( THEMEDEBUG ) {bioship_debug( "Hybrid Hooks", $vthemehooks['hybrid'] );}

	// 1.9.0: handle admin metabox defaults
	if ( is_admin() ) {

		// 1.8.5: default the hybrid hook metaboxes to closed
		// ref: https://surniaulula.com/2013/05/29/collapse-close-wordpress-metaboxes/
		$userid = get_current_user_id();
		$optionkey = 'closedpostboxes_' . 'appearance_page_' . 'hybrid-hook-settings';

		if ( isset( $_REQUEST['metaboxes'] ) && ( 'reset' == $_REQUEST['metaboxes'] ) ) {
			delete_user_option( $optionkey, $userid );
		} else {
			$closedboxes = get_user_option( $optionkey, $userid );
		}

		// --- create an empty array if get_user_option() had nothing to return ---
        // 2.2.0: added isset check
		if ( !isset( $closedboxes ) || !is_array( $closedboxes ) ) {
			$closedboxes = array();
			// 2.1.1: fix to use vthemehooks subkey
			foreach ( $vthemehooks['hybrid'] as $hook ) {
				$closedboxes[] = 'hybrid-hook-' . $hook;
			}
			update_user_option( $userid, $optionkey, $closedboxes, true );
		}

		if ( THEMEDEBUG ) {
			bioship_debug( "Closed Boxes", $closedboxes );
		}
	}

	return $vthemehooks['hybrid'];
 }
}

// -------------------------------
// Filter Hybrid Hook Theme Prefix
// -------------------------------
// hook into the theme filter (for modified Hybrid Hook plugin)
if ( !function_exists( 'bioship_muscle_hybrid_hook_prefix' ) ) {
 function bioship_muscle_hybrid_hook_prefix() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.0.5: change to bioship prefix to match new action names
	return 'bioship';
 }
}
