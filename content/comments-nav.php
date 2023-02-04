<?php

// ===================================
// === BioShip Comments Navigation ===
// ===================================
// (original template via Hybrid Base Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Comments Nav Template', __FILE__, 'comments' );}

 // --- Check for Paged Comments ---
if ( get_option( 'page_comments' ) && ( 1 < get_comment_pages_count() ) ) {

	bioship_html_comment( '.comments-nav' );
	echo '<nav class="comments-nav" role="navigation" aria-labelledby="comments-nav-title">' . PHP_EOL;

		echo '<h3 id="comments-nav-title" class="screen-reader-text">' . esc_html( __( 'Comments Navigation', 'bioship' ) ) . '</h3>' . PHP_EOL;

		previous_comments_link( esc_html( _x( '&larr; Previous', 'comments navigation', 'bioship') ) );

		echo '<span class="page-numbers">';
			/* Translators: Comments page numbers. 1 is current page and 2 is total pages. */
			printf( esc_html( _x( 'Page %1$s of %2$s', 'comments pagination', 'bioship' ) ), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() );
		echo '</span>';

		next_comments_link( esc_html( _x( 'Next &rarr;', 'comments navigation', 'bioship' ) ) );

	echo '</nav>';
	bioship_html_comment( '/.comments-nav' );
	echo PHP_EOL;
}
