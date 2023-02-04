<?php

// ========
// CSS Hero
// ========

// ---------------------------------
// Adjust CSS Hero Declarations Path
// ---------------------------------
// also allows for the file to be in the parent theme directory
// (this is a bit hacky, hopefully a real filter is available for this in future!)
// 1.8.5: allow moving of csshero.js from theme root to javascript dirs
// 2.1.1: moved integration function here from skull.php
if ( !function_exists( 'bioship_muscle_csshero_check' ) ) {

 add_action( 'init', 'bioship_muscle_csshero_check' );

 function bioship_muscle_csshero_check() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check condition ---
	if ( !isset( $_GET['csshero_action'] ) || ( 'edit_page' != $_GET['csshero_action'] ) ) {
		return;
	}

	// 1.9.5: added filter to optionally disable this path adjustment
	$csshero = bioship_apply_filters( 'skeleton_adjust_css_hero_script_dir', true );
	if ( $csshero ) {
		// 2.1.1: moved add_action internally for consistency
		// 2.2.0: moved action inside this separate function
		add_action( 'wp_loaded', 'bioship_csshero_script_dir', 0 );
	}
 }
}

// --------------------------
// Add CSS Hero Script Filter
// --------------------------
// 2.2.0: added muscle_ to function prefix
if ( !function_exists( 'bioship_muscle_csshero_script_dir' ) ) {
 function bioship_muscle_csshero_script_dir() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	add_filter( 'stylesheet_directory_uri', 'bioship_csshero_script_url', 10, 3 );
 }
}

// --------------------------
// CSS Hero Script URL Filter
// --------------------------
// 2.1.1: added missing function_exists wrapper
// 2.1.3: renamed csshero.js to bioship-csshero.js
if ( !function_exists( 'bioship_csshero_script_url' ) ) {
 function bioship_csshero_script_url( $stylesheet_dir_url, $stylesheet, $theme_root_url ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemedirs;
	$csshero = bioship_file_hierarchy( 'url', 'bioship-csshero.js', $vthemedirs['script'] );
	if ( $csshero ) {
		$stylesheet_dir_url = dirname( $csshero );
	}
	remove_filter( 'stylesheet_directory_url', 'skeleton_css_hero_script_url', 10, 3 );
	return $stylesheet_dir_url;
 }
}
