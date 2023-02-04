<?php

/* Subsidiary Sidebar: Single Post Type */

/* Note: Unified Option */

if ( THEMETRACE ) {bioship_trace( 'T', 'Unified Subsidebar Template', __FILE__, 'sidebar' );}

// 2.0.9: add template name to class attribute
// 2.1.1: add missing args to attibribute function call
$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-subsidiary sidebar-' . $template );

if ( is_active_sidebar( 'subsidiary' ) ) {

	bioship_do_action( 'bioship_before_subsidebar', 'subsidiary', $template );

		bioship_html_comment( '#sidebar-subsidiary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'subsidiary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'subsidiary' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-subsidiary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_subsidebar', 'subsidiary', $template );

}

