<?php

// ==============================
// === BioShip Error Template ===
// ==============================
// (original template via Hybrid Base Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Error Template', __FILE__, 'content' );}

// --- open article tag ---
// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
echo '<article ' . hybrid_get_attr( 'post' ) . '>' . PHP_EOL;

	// --- entry header ---
	bioship_html_comment( '.entry-header' );
	echo '<header class="entry-header">' . PHP_EOL;
		echo '<h2 class="entry-title">' .PHP_EOL;

			// --- no content title ---
			// 1.8.5: added no content title filter
			$nocontenttitle = esc_html( __( 'Nothing Found.', 'bioship' ) );
			$nocontenttitle = bioship_apply_filters( 'skeleton_no_content_title', $nocontenttitle );
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo $nocontenttitle;

		echo '</h2>' . PHP_EOL;
	echo '</header>' . PHP_EOL;
	bioship_html_comment( '/.entry-header' );

	// --- entry content ---
	bioship_html_comment( '.entry-content' );
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<div ' . hybrid_get_attr( 'entry-content' ) . '>' . PHP_EOL;

			// --- no content message ---
			// 1.8.5: added no content message filter
			// 2.1.3: moved paragraphs outside to prevent tag escaping
			$nocontent = wpautop( esc_html( __( 'Apologies, but no entries were found.', 'bioship' ) ) );
			$nocontent = bioship_apply_filters( 'skeleton_no_content_message', $nocontent );
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo $nocontent;

	echo '</div>'; 
	bioship_html_comment( '/.entry-content' );
	echo PHP_EOL;

echo '</article>';
echo PHP_EOL;
