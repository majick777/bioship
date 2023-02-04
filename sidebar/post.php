<?php

/* Primary Sidebar: Single Post */

/* note: Dual Option */

if ( THEMETRACE ) {bioship_trace( 'T', 'Post Sidebar Template', __FILE__, 'sidebar' );}

// 2.0.9: fix to mismatching class attribute (subsidiary)
$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-primary sidebar-' . $template );

if ( is_active_sidebar( 'posts' ) ) {

	bioship_do_action( 'bioship_before_sidebar', 'posts', $template );

		bioship_html_comment('#sidebar-primary');
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'primary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'posts' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-primary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_sidebar', 'posts', $template );

}

