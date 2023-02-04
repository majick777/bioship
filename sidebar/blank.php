<?php

/* Primary Sidebar: Blank */

if ( THEMETRACE ) {bioship_trace( 'T', 'Blank Sidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-primary sidebar-' . $template );

bioship_do_action( 'bioship_before_sidebar', 'blank', $template );

	bioship_html_comment( '#sidebar-primary' );
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<aside ' . hybrid_get_attr( 'sidebar', 'primary', $args ) . '>' . PHP_EOL;
		echo '&nbsp;' . PHP_EOL;
	echo '</aside>';
	bioship_html_comment( '/#sidebar-primary' );
	echo PHP_EOL;

bioship_do_action( 'bioship_after_sidebar', 'blank', $template );

