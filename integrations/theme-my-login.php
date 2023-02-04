<?php

// ==============
// Theme My Login
// ==============
// 2.0.1: filter Theme My Login template loading
// 2.0.9: check loading filter internally
if ( !function_exists( 'muscle_load_theme_my_login_filters' ) ) {

 // 2.2.0: change from plugins_loaded hook (no longer firing?!)
 add_action( 'init', 'muscle_load_theme_my_login_filters' );

 function muscle_load_theme_my_login_filters() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// --- check load ---
 	global $vthemesettings;
 	$load = isset( $vthemesettings['tmltemplates'] ) ? $vthemesettings['tmltemplates'] : false;
	$load = bioship_apply_filters( 'muscle_load_tml_templates', $load );
	if ( !$load ) {
		return;
	}

	// --- add Theme My Login filters ---
	// 2.0.9: add Theme My Login filters here
	add_filter( 'tml_template_paths', 'bioship_muscle_tml_template_paths' );
	add_filter( 'login_button_url', 'bioship_muscle_login_button_url' );
	add_filter( 'register_button_url', 'bioship_muscle_register_button_url' );
 	add_filter( 'profile_button_url', 'bioship_muscle_profile_button_url' );
	add_filter( 'register_form_image', 'bioship_muscle_register_form_image' );
	add_filter( 'login_form_image', 'bioship_muscle_login_form_image' );
	
	bioship_debug( "ThemeMyLogin Hierarchy Extended" );
 }
}

// -------------------------------
// TML Improved Template Hierarchy
// -------------------------------
if ( !function_exists( 'muscle_tml_template_paths' ) ) {
 function bioship_muscle_tml_template_paths( $paths ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 1.8.5: use existing globals
	global $vthemestyledir, $vthemetemplatedir;
	$templatepaths = array(
		$vthemestyledir . 'templates/theme-my-login',
		$vthemestyledir . 'theme-my-login',
		$vthemestyledir,
		$vthemetemplatedir . 'templates/theme-my-login',
		$vthemetemplatedir . 'theme-my-login',
		$vthemetemplatedir,
		WP_PLUGIN_DIR . '/theme-my-login/templates',
	);
	bioship_debug( "New ThemeMyLogin Paths", $templatepaths );
	return $templatepaths;
 }
}

// ---------------------------
// TML Login Button URL Filter
// ---------------------------
if ( !function_exists( 'bioship_muscle_login_button_url' ) ) {
 function bioship_muscle_login_button_url( $buttonurl ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemesettings;
	if ( '' != $vthemesettings['loginbuttonurl'] ) {
		$buttonurl = $vthemesettings['loginbuttonurl'];
	}
	return $buttonurl;
 }
}

// ------------------------------
// TML Register Button URL Filter
// ------------------------------
if ( !function_exists( 'bioship_muscle_register_button_url' ) ) {
 function bioship_muscle_register_button_url( $buttonurl ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemesettings;
	if ( '' != $vthemesettings['registerbuttonurl'] ) {
		$buttonurl = $vthemesettings['registerbuttonurl'];
	}
	return $buttonurl;
 }
}

// -----------------------------
// TML Profile Button URL Filter
// -----------------------------
if ( !function_exists( 'bioship_muscle_profile_button_url' ) ) {
 function bioship_muscle_profile_button_url( $buttonurl ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
	global $vthemesettings;
	if ( '' != $vthemesettings['profilebuttonurl'] ) {
		$buttonurl = $vthemesettings['profilebuttonurl'];
	}
	return $buttonurl;
 }
}

// ----------------------------
// TML Register Form Logo Image
// ----------------------------
if ( !function_exists( 'bioship_muscle_register_form_image' ) ) {
 function bioship_muscle_register_form_image( $image ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;
	if ( '1' == $vthemesettings['registerformimage'] ) {
		if ( 'custom' == $vthemesettings['loginlogo'] ) {
			$image = $vthemesettings['header_logo'];
		}
		if ( 'upload' == $vthemesettings['loginlogo'] ) {
			$image = $vthemesettings['loginlogourl'];
		}
	}
	return $image;
 }
}

// -------------------------
// TML Login Form Logo Image
// -------------------------
// 2.1.1: added missing image argument definition
if ( !function_exists( 'bioship_muscle_login_form_image' ) ) {
 function bioship_muscle_login_form_image( $image ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemesettings;

	if ( '1' == $vthemesettings['loginformimage'] ) {
		if ( 'custom' == $vthemesettings['loginlogo'] ) {
			$image = $vthemesettings['header_logo'];
		}
		if ( 'upload' == $vthemesettings['loginlogo'] ) {
			$image = $vthemesettings['loginlogourl'];
		}
	}
	return $image;
 }
}
