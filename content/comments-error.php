<?php

// ========================================
// ==== BioShip Comments Error Template ===
// ========================================
// (original template via Hybrid Base Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Comments Error Template', __FILE__, 'comments');}

if ( pings_open() && !comments_open() ) {

	// --- If only pingbacks open ---
	bioship_html_comment( '.comments-closed.pings-open' );
	echo '<p class="comments-closed pings-open">' . PHP_EOL;
		// 2.0.8: use %1$s and %2$s instead of two %s
		/* Translators: The two %s are placeholders for HTML. The order cannot be changed. */
		printf( esc_html( __( 'Comments are closed, but %1$strackbacks%2$s and pingbacks are open.', 'bioship' ) ), '<a href="' . esc_url( get_trackback_url() ) . '">', '</a>' );
	echo '</p>';
	bioship_html_comment( '/.comments-closed.pings-open' );
	echo PHP_EOL;

} elseif ( !comments_open() ) {

	// --- If all comments are closed ---
	bioship_html_comment( '.comments-closed.pings-closed' );
	echo '<p class="comments-closed pings-closed">' . PHP_EOL;
		echo esc_html( __( 'Comments are closed.', 'bioship' ) );
	echo '</p>';
	bioship_html_comment( '/.comments-closed.pings-closed' );
	echo PHP_EOL;
}
