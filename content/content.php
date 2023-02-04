<?php

// =====================================
// === BioShip Main Content Template ===
// =====================================

if ( THEMETRACE ) {bioship_trace( 'T', 'Content Template', __FILE__, 'content' );}

// Note: For consistency the improved Hybrid template and attribute functions
// are included and used whether full Hybrid Core library is active or not.

// --- get post type(s) ---
$posttypes = bioship_get_post_types();
bioship_debug( "Post Types", $posttypes );
$posttype = is_string( $posttypes ) ? $posttypes : '';

// --- clear div ---
echo '<div class="clear"></div>';

// --- article tag open ---
bioship_html_comment( 'article.entry' );
// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
echo '<article ' . hybrid_get_attr( 'post' ) . '>' . PHP_EOL;

	// --- Before Entry ---
	bioship_do_action( 'bioship_before_entry' );

	// --- Attachment / Media Handler ---
	bioship_do_action( 'bioship_media_handler' );

	// --- Entry Header ---
	bioship_do_action( 'bioship_entry_header' );

	// --- Thumbnail ---
	bioship_do_action( 'bioship_thumbnail' );

	// Excerpt Display
	// ---------------
	if ( is_search() || ( is_archive() && !is_singular( $posttype ) ) || !is_singular( $posttype ) ) {

		// --- Before Excerpt ---
		bioship_do_action( 'bioship_before_excerpt' );

			// --- Entry Excerpt Content ---
			bioship_html_comment( '.entry-summary' );
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo '<div ' . hybrid_get_attr( 'entry-summary' ) . '>' . PHP_EOL;

				// --- the_excerpt() ---
				bioship_do_action( 'bioship_the_excerpt' );

			// --- close entry summary div ---
			echo '</div>';
			bioship_html_comment( '/.entry-summary' );

		// --- After Excerpt ---
		bioship_do_action( 'bioship_after_excerpt' );

		// --- Entry Footer ---
		bioship_do_action( 'bioship_entry_footer' );

	} else {

		// Full Content Display
		// --------------------

		// --- Before Singular ---
		bioship_do_action( 'bioship_before_singular' );

			// --- Author Bio (top) ---
			bioship_do_action( 'bioship_author_bio_top' );

				// --- Before Main Content ---
				bioship_do_action( 'bioship_before_the_content' );

					// --- Full Entry Content ---
					bioship_html_comment( '.entry-content' );
					// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
					echo '<div ' . hybrid_get_attr( 'entry-content' ) . '>' . PHP_EOL;

							// --- the_content ---
							bioship_do_action( 'bioship_the_content' );

						// --- clear div ---
						echo '<div class="clear"></div>';

					// --- close content div ---
					echo '</div>';
					bioship_html_comment( '/.entry-content' );

				// --- After Main Content ---
				bioship_do_action( 'bioship_after_the_content' );

			// --- Author Bio (bottom) ---
			bioship_do_action( 'bioship_author_bio_bottom' );

			// --- Entry Footer ---
			bioship_do_action( 'bioship_entry_footer' );

		// --- After Singular ---
		bioship_do_action( 'bioship_after_singular' );

		// --- Comments ---
		bioship_do_action( 'bioship_comments' );

	}

	// --- After Entry ---
	bioship_do_action( 'bioship_after_entry' );

// --- close article tag ---
echo '</article>';
bioship_html_comment( '/article.entry' );
echo PHP_EOL;
