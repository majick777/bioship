<?php

// ====================================
// === BioShip Main Footer Template ===
// ====================================

if ( THEMETRACE ) {bioship_trace( 'T', 'Footer Template', __FILE__, 'footer' );}

	// --- Before Footer ---
	bioship_do_action( 'bioship_before_footer' );

		// --- Elementor Footer Location Support ---
		// 2.1.2: allow possible replacing of bioship_footer action
		// 2.2.0: converted to filter usage for elementor integration
		$donefooter = bioship_apply_filters( 'elementor_location_output', false, 'footer' );
		if ( !$donefooter ) {
			bioship_do_action( 'bioship_footer' );
		}

		// --- WP Footer ---
		wp_footer();

	// --- After Footer ---
	bioship_do_action( 'bioship_after_footer' );

	// --- Close Container ---
	bioship_do_action( 'bioship_container_close' );
	bioship_do_action( 'bioship_after_container' );

// --- Close Body Content ---
echo '</div>';
bioship_html_comment( '/#bodycontent.inner' );
echo PHP_EOL;

// --- Close Body ---
echo '</body>';
bioship_html_comment( '/body' );
echo PHP_EOL;

// --- Close HTML ---
echo '</html>';