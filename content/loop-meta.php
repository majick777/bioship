<?php

// ==================================
// === BioShip Loop Meta Template ===
// ==================================
// (original template via Hybrid Base Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Loop Meta Template', __FILE__, 'loop' );}

// ---- open archive header div ---
// 1.9.8: switch from .loop-meta to .archive-header
bioship_html_comment( '.archive-header' );
// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
echo '<div ' . hybrid_get_attr( 'archive-header' ) . '>' . PHP_EOL;

	// --- Loop Title ---
	$looptitle = bioship_get_loop_title();
	if ( $looptitle ) {
		// 1.9.8: switch from .loop-title to .archive-title
		// 2.1.3: removed esc_attr from loop title (breaks HTML)
		bioship_html_comment( '.archive-title' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo '<h2 ' . hybrid_get_attr( 'archive-title' ) . '>' . PHP_EOL;
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo $looptitle;
		echo '</h2>';
		bioship_html_comment( '/.archive-title' );
		echo PHP_EOL;
	}

	// --- Loop Description ----
	// Check if we are on page/1
	if ( !is_paged() ) {

		$description = bioship_get_loop_description();
		if ( $description ) {
			// 1.9.8: switch from .loop-description to .archive-description
			// 2.1.3: removed esc_attr from loop description (breaks HTML)
			bioship_html_comment( '.archive-description' );
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '<div ' . hybrid_get_attr( 'archive-description' ) . '>' . PHP_EOL;
				// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo $description;
			echo '</div>';
			bioship_html_comment( '/.archive-description' );
			echo PHP_EOL;
		}
	}

// --- close archive header div ---
echo '</div>';
bioship_html_comment( '/.archive-header' );
echo PHP_EOL;
