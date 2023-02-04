<?php

/* Subsidiary Sidebar: Archive General */

if ( THEMETRACE ) {bioship_trace( 'T', 'Archive Subsidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-subsidiary sidebar-' . $template );

if ( is_active_sidebar( 'subarchive' ) ) {

	bioship_do_action( 'bioship_before_subsidebar', 'subarchive', $template );

		bioship_html_comment( '#sidebar-subsidiary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'subsidiary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'subarchive' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-subsidiary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_subsidebar', 'subarchive', $template );

}
