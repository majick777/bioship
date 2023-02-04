<?php

// ====================================
// === BioShip Main Header Template ===
// ====================================

if ( THEMETRACE) {bioship_trace( 'T', 'Header Template', __FILE__, 'header' );}

// --- Doctype  ---
echo '<!doctype html>' . PHP_EOL;

// --- HTML Tag ---
// 2.1.1: get language attributes once only
// 2.2.0: revert to individual calls for direct escaped output
echo '<!--[if lt IE 7 ]><html class="ie ie6" ';
language_attributes();
echo '><![endif]-->' . PHP_EOL;
echo '<!--[if IE 7 ]><html class="ie ie7" ';
language_attributes();
echo '><![endif]-->' . PHP_EOL;
echo '<!--[if IE 8 ]><html class="ie ie8" ';
language_attributes();
echo '><![endif]-->' . PHP_EOL;
echo '<!--[if IE 9 ]><html class="ie ie9" ';
language_attributes() . '><![endif]-->' . PHP_EOL;
// note: the next line actually means 'not IE5-9' rather than just "not IE"
echo '<!--[if !IE]>--><html ';
language_attributes();
echo '> <!--<![endif]-->' . PHP_EOL;

	// --- Head Tag ---
	bioship_html_comment( 'head' );
	echo '<head ';
	hybrid_attr( 'head' );
	echo '>' . PHP_EOL;

		echo '<link rel="profile" href="http://gmpg.org/xfn/11">' . PHP_EOL;
		wp_head();

	echo '</head>';
	bioship_html_comment( '/head' );

	// --- Body Tag ---
	bioship_html_comment( 'body' );
	echo '<body ';
	hybrid_attr( 'body' );
	echo '>' . PHP_EOL;

		// --- body open action ---
		wp_body_open();

		// --- screen reader skip link ---
		echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html( __( 'Skip to Content', 'bioship' ) ) . '</a>' . PHP_EOL;

		// --- bodycontent inner div ---
		bioship_html_comment( '#bodycontent.inner' );
		echo '<div id="bodycontent" class="inner">' . PHP_EOL;

			// --- Wrap Container ---
			bioship_do_action( 'bioship_before_container' );
			bioship_do_action( 'bioship_container_open' );

				// --- Header ---
				bioship_do_action( 'bioship_before_header' );

					// --- Elementor Header Location Support -
					// 2.1.2: allow possible replacing of bioship_header action
					// 2.2.0: converted to filter usage for elementor integration
					$doneheader = bioship_apply_filters( 'elementor_location_output', false, 'header' );
					if ( !$doneheader ) {
						bioship_do_action( 'bioship_header' );
					}

				bioship_do_action( 'bioship_after_header' );

				// --- Navigation Menu ---
				bioship_do_action( 'bioship_before_navbar' );
					bioship_do_action( 'bioship_navbar' );
				bioship_do_action( 'bioship_after_navbar' );
