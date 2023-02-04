<?php

/* Primary Sidebar: Category Archive */

if ( THEMETRACE ) {bioship_trace( 'T', 'Category Archive Sidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-primary sidebar-' . $template );

if ( is_active_sidebar( 'category' ) ) {

	bioship_do_action( 'bioship_before_sidebar', 'category', $template );

		bioship_html_comment( '#sidebar-primary' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<aside ' . hybrid_get_attr( 'sidebar', 'primary', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'category' );
		echo '</aside>';
		bioship_html_comment( '/#sidebar-primary' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_sidebar', 'category', $template );

}

