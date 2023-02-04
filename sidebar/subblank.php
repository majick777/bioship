<?php

/* Subsidiary Sidebar: Blank */

if ( THEMETRACE ) {bioship_trace( 'T', 'Blank Subsidebar Template', __FILE__, 'sidebar' );}

$template = str_replace( '.php', '', basename( __FILE__ ) );
$args = array( 'class' => 'sidebar sidebar-subsidiary sidebar-' . $template );

bioship_do_action( 'bioship_before_subsidebar', 'subblank', $template );

	bioship_html_comment( '#sidebar-subsidiary' );
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<aside ' . hybrid_get_attr( 'sidebar', 'subsidiary', $args ) . '>' . PHP_EOL;
		echo '&nbsp;' . PHP_EOL;
	echo '</aside>';
	bioship_html_comment( '/#sidebar-subsidiary' );
	echo PHP_EOL;

bioship_do_action( 'bioship_after_subsidebar', 'subblank', $template );
