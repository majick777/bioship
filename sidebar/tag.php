<?php

/* Primary Sidebar: Tag Archive */

if ( THEMETRACE ) {bioship_trace( 'T', 'Tag Archive Sidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-primary sidebar-' . $template );

if ( is_active_sidebar( 'tag' ) ) {

	bioship_do_action( 'bioship_before_sidebar', 'tag', $template );

		bioship_html_comment( '#sidebar-primary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'primary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'tag' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-primary' );

	bioship_do_action( 'bioship_after_sidebar', 'tag', $template );

}

