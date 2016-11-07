<?php
/**
 * Plugin Name: Hybrid Hook Widgets
 * Plugin URI: http://themehybrid.com/themes/hybrid/hybrid-hook-widgets
 * Description: Adds widget areas to the action hooks in the Hybrid theme, allowing you to customize your site without digging into the theme files.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * This plugin was created so that users with little or no XHTML and PHP 
 * knowledge could take advantage of Hybrid's built-in hook system.  It allows
 * them to add widgets to specific action hooks.  The user must have WordPress
 * version 2.8 or higher installed to use this plugin.
 *
 * @copyright 2008 - 2009
 * @version 0.1
 * @author Justin Tadlock
 * @link http://themehybrid.com/themes/hybrid/hybrid-hook-widgets
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package HybridHookWidgets
 */

/**
 * Yes, we're localizing the plugin.  This partly makes sure non-English
 * users can use it too.  To translate into your language use the
 * en_EN.po file as as guide.  Poedit is a good tool to for translating.
 * @link http://poedit.net
 *
 * @since 0.1
 */
load_plugin_textdomain( 'hook_widgets', false, '/hybrid-hook-widgets' );

/**
 * Add functions to action hooks available in WP and Hybrid.
 * @since 0.1
 */
add_action( 'init', 'hook_widgets_register' );
add_action( 'hybrid_before_html', 'hook_widgets_before_html', 11 );
add_action( 'hybrid_after_html', 'hook_widgets_after_html', 11 );
add_action( 'hybrid_before_header', 'hook_widgets_before_header', 11 );
add_action( 'hybrid_header', 'hook_widgets_header', 11 );
add_action( 'hybrid_after_header', 'hook_widgets_after_header', 11 );
add_action( 'hybrid_before_container', 'hook_widgets_before_container', 11 );
add_action( 'hybrid_after_container', 'hook_widgets_after_container', 11 );
add_action( 'hybrid_before_footer', 'hook_widgets_before_footer', 11 );
add_action( 'hybrid_footer', 'hook_widgets_footer', 11 );
add_action( 'hybrid_after_footer', 'hook_widgets_after_footer', 11 );
add_action( 'comment_form', 'hook_widgets_comment_form', 11 );

/**
 * Create the additional widget areas for the plugin.
 * @uses register_sidebar() Creates new widget areas.
 *
 * @since 0.1
 */
function hook_widgets_register() {
	register_sidebar( array( 'name' => __('Hook: Before HTML ', 'hook_widgets'), 'id' => 'hook-before-html', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: After HTML', 'hook_widgets'), 'id' => 'hook-after-html', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Before Header', 'hook_widgets'), 'id' => 'hook-before-header', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Header', 'hook_widgets'), 'id' => 'hook-header', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: After Header', 'hook_widgets'), 'id' => 'hook-after-header', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Before Container', 'hook_widgets'), 'id' => 'hook-before-container', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: After Container', 'hook_widgets'), 'id' => 'hook-after-container', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Before Footer', 'hook_widgets'), 'id' => 'hook-before-footer', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Footer', 'hook_widgets'), 'id' => 'hook-footer', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: After Footer', 'hook_widgets'), 'id' => 'hook-after-footer', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
	register_sidebar( array( 'name' => __('Hook: Comment Form', 'hook_widgets'), 'id' => 'hook-comment-form', 'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-inside">', 'after_widget' => '</div></div>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>' ) );
}

/**
 * Output the Before HTML widget area.
 * @since 0.1
 */
function hook_widgets_before_html() {
	if ( !is_active_sidebar( 'hook-before-html' ) )
		return;

	echo '<div id="utility-before-html" class="utility utility-before-html">';
		dynamic_sidebar( 'hook-before-html' );
	echo '</div>';
}

/**
 * Output the After HTML widget area.
 * @since 0.1
 */
function hook_widgets_after_html() {
	if ( !is_active_sidebar( 'hook-after-html' ) )
		return;

	echo '<div id="utility-after-html" class="utility utility-after-html">';
		dynamic_sidebar( 'hook-after-html' );
	echo '</div>';
}

/**
 * Output the Before Header widget area.
 * @since 0.1
 */
function hook_widgets_before_header() {
	if ( !is_active_sidebar( 'hook-before-header' ) )
		return;

	echo '<div id="utility-before-header" class="utility utility-before-header">';
		dynamic_sidebar( 'hook-before-header' );
	echo '</div>';
}

/**
 * Output the Header widget area.
 * @since 0.1
 */
function hook_widgets_header() {
	if ( !is_active_sidebar( 'hook-header' ) )
		return;

	echo '<div id="utility-header" class="utility utility-header">';
		dynamic_sidebar( 'hook-header' );
	echo '</div>';
}

/**
 * Output the After Header widget area.
 * @since 0.1
 */
function hook_widgets_after_header() {
	if ( !is_active_sidebar( 'hook-after-header' ) )
		return;

	echo '<div id="utility-after-header" class="utility utility-after-header">';
		dynamic_sidebar( 'hook-after-header' );
	echo '</div>';
}

/**
 * Output the Before Container widget area.
 * @since 0.1
 */
function hook_widgets_before_container() {
	if ( !is_active_sidebar( 'hook-before-container' ) )
		return;

	echo '<div id="utility-before-container" class="utility utility-before-container">';
		dynamic_sidebar( 'hook-before-container' );
	echo '</div>';
}

/**
 * Output the After Container widget area.
 * @since 0.1
 */
function hook_widgets_after_container() {
	if ( !is_active_sidebar( 'hook-after-container' ) )
		return;

	echo '<div id="utility-after-container" class="utility utility-after-container">';
		dynamic_sidebar( 'hook-after-container' );
	echo '</div>';
}

/**
 * Output the Before Footer widget area.
 * @since 0.1
 */
function hook_widgets_before_footer() {
	if ( !is_active_sidebar( 'hook-before-footer' ) )
		return;

	echo '<div id="utility-before-footer" class="utility utility-before-footer">';
		dynamic_sidebar( 'hook-before-footer' );
	echo '</div>';
}

/**
 * Output the Footer widget area.
 * @since 0.1
 */
function hook_widgets_footer() {
	if ( !is_active_sidebar( 'hook-footer' ) )
		return;

	echo '<div id="utility-footer" class="utility utility-footer">';
		dynamic_sidebar( 'hook-footer' );
	echo '</div>';
}

/**
 * Output the After Footer widget area.
 * @since 0.1
 */
function hook_widgets_after_footer() {
	if ( !is_active_sidebar( 'hook-after-footer' ) )
		return;

	echo '<div id="utility-after-footer" class="utility utility-after-footer">';
		dynamic_sidebar( 'hook-after-footer' );
	echo '</div>';
}

/**
 * Output the Comment Form widget area.
 * @since 0.1
 */
function hook_widgets_comment_form() {
	if ( !is_active_sidebar( 'hook-comment-form' ) )
		return;

	echo '<div id="utility-comment-form" class="utility utility-comment-form">';
		dynamic_sidebar( 'hook-comment-form' );
	echo '</div>';
}

?>