<?php

/* Primary Sidebar: Search Results */

if ( THEMETRACE ) {bioship_trace( 'T', 'Search Sidebar Template', __FILE__, 'sidebar' );}

// 2.0.9: fix to mismatching class attribute (subsidiary)
$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-primary sidebar-' . $template );

if ( is_active_sidebar( 'search' ) ) {

	bioship_do_action( 'bioship_before_sidebar', 'search', $template );

		bioship_html_comment( '#sidebar-primary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar','primary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'search' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-primary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_sidebar', 'search', $template );

}

