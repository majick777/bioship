<?php

/* Subsidiary Sidebar: 404 Not Found */

if ( THEMETRACE ) {bioship_trace( 'T', '404 Subsidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-subsidiary sidebar-' . $template );

if ( is_active_sidebar( 'subnotfound' ) ) {

	bioship_do_action( 'bioship_before_subsidebar', 'subnotfound', $template );

		bioship_html_comment( '#sidebar-subsidiary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'subsidiary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'subnotfound' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-subsidiary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_subsidebar', 'subnotfound', $template );

}

