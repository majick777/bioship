<?php

/* Header Sidebar: Header Widget Area */

if ( THEMETRACE ) {bioship_trace( 'T', 'Header Widget Area Template', __FILE__, 'sidebar' );}

// 2.0.9: add template name to footer class attribute
$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-header sidebar-' . $template );

if ( is_active_sidebar('header-widget-area' ) ) {

	bioship_do_action( 'bioship_before_header_widgets', $template );

		bioship_html_comment( '#sidebar-header' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<div ' . hybrid_get_attr( 'sidebar', 'header', $args ) . '>' . PHP_EOL;
			dynamic_sidebar( 'header-widget-area' );
		echo '</div>';
		bioship_html_comment( '/#sidebar-header' );
		echo PHP_EOL;

	bioship_do_action( 'bioship_after_header_widgets', $template );

}

