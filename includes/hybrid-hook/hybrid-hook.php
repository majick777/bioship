<?php

/**
 * Plugin Name: Hybrid Hook for BioShip
 * Plugin URI: http://themehybrid.com/themes/hybrid/hybrid-hook
 * Description: Gives you easy access to the Hybrid theme's hooks straight from your WordPress admin.  You can add <acronym title="Hypertext Markup Language">HTML</acronym>, shortcodes, <acronym title="Hypertext Preprocessor">PHP</acronym>, and/or JavaScript without having to dig into any theme files.
 * Version: 0.36b
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * This plugin was created so that end users and non-developers could take advantage of the Hybrid theme's
 * extensive hook system.  It allows the input of HTML, PHP, shortcodes, and JavaScript from the WordPress
 * admin.  Its a way to work around having to learn how to use the WordPress plugin API and just add content
 * anywhere.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package HybridHook
 * @version 0.3.5
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2011, Justin Tadlock
 * @link http://themehybrid.com/themes/hybrid/hybrid-hook
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// MODIFIED: for BioShip Theme Compatibility (search 'MOD')
// --------------------------------------------------------
// @author Tony Hayes http://dreamjester.net
// ! Please do not expect original author to support this modified plugin! Thanks!
// - changed plugindir to dirname to include via theme options
// - added a filter to the hook array so it can use theme hooks
// - further changes noted in add-actions.php and meta-boxes.php


/* Set up the plugin on the 'plugins_loaded' hook. */
add_action( 'plugins_loaded', 'hybrid_hook_setup' );

/**
 * Plugin setup function.  Loads the textdomain and plugin files where appropriate.  It also defines a few
 * constants for use throughout the plugin.
 *
 * @since 0.3.0
 */
function hybrid_hook_setup() {

	/* Load the translation files. */
	load_plugin_textdomain( 'hybrid-hook', false, '/hybrid-hook/languages' );

	// MOD: change the plugin directories to theme subdirectory
	if (strstr(dirname(__FILE__),'bioship/includes/hybrid-hook')) {
		define( 'HYBRID_HOOK_DIR', dirname( __FILE__ ).DIRECTORY_SEPARATOR );
		define( 'HYBRID_HOOK_URI', trailingslashit(get_template_directory_uri().'/includes/hybrid-hook'));
	} elseif (strstr(dirname(__FILE__),'includes/hybrid-hook')) {
		define( 'HYBRID_HOOK_DIR', dirname( __FILE__ ).DIRECTORY_SEPARATOR );
		define( 'HYBRID_HOOK_URI', trailingslashit(get_stylesheet_directory_uri().'/includes/hybrid-hook'));
	} else {
		// otherwise assume being used as an activated plugin

		/* Define the directory path constant. */
		define( 'HYBRID_HOOK_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Define the URI path constant. */
		define( 'HYBRID_HOOK_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}


	/* Load admin files if we're in the admin. */
	if ( is_admin() )
		require_once( HYBRID_HOOK_DIR . 'settings.php' );

	/* Load functions if we're on the front end of the site. */
	else
		require_once( HYBRID_HOOK_DIR . 'add-actions.php' );
}

/**
 * Returns a list of all the available hooks.  This is used in several places in the plugin to quickly grab a
 * complete array of all the hooks without having to repeat code.
 *
 * @since 0.3.0
 */
function hybrid_hook_get_hooks() {

	$hooks = array(
		'before_html',
		'after_html',
		'before_header',
		'header',
		'after_header',
		'before_primary_menu',
		'after_primary_menu',
		'before_container',
		'after_container',
		'before_content',
		'after_content',
		'before_entry',
		'after_entry',
		'after_singular',
		'before_primary',
		'after_primary',
		'before_secondary',
		'after_secondary',
		'before_subsidiary',
		'after_subsidiary',
		'before_footer',
		'footer',
		'after_footer'
	);

	// MOD: add hybrid_hooks filter (used to load theme hooks)
	return apply_filters('hybrid_hooks',$hooks);
}

/**
 * Allows developers to disable the PHP execution feature.  By default, PHP is allowed.
 *
 * @since 0.3.0
 */
function hybrid_hook_allow_php() {
	return apply_filters( 'hybrid_hook_allow_php', true );
}

/**
 * Get a setting for the Hybrid Hook plugin.  Set $hybrid_hook_settings as a global variable, so we're
 * not having to use get_option() every time we need to find a setting.
 *
 * @since 0.2.0
 * @global $hybrid_hook Hybrid Hook plugin object.
 * @param $option string|int|array Specific Hybrid Hook setting we want to get.
 * @return $hybrid_hook->settings[$option] mixed Value of the setting input.
 */
function hybrid_hook_get_setting( $option = '' ) {
	global $hybrid_hook;

	if ( !$option )
		return false;

	if ( !isset( $hybrid_hook->settings ) || !is_array( $hybrid_hook->settings ) )
		@$hybrid_hook->settings = get_option( 'hybrid_hook_settings' );

	return $hybrid_hook->settings[$option];
}

?>