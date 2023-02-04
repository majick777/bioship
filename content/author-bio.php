<?php

// ===================================
// === BioShip Author Bio Template ===
// ===================================

if ( THEMETRACE ) {bioship_trace( 'T', 'Author Bio Template', __FILE__ , 'content' );}

// --- author info box ---
bioship_html_comment( '#entry-author-info' );
echo '<div id="entry-author-info">' . PHP_EOL;

	// --- author avatar ---
	bioship_html_comment( '#author-avatar' );
	echo '<div id="author-avatar">' . PHP_EOL;
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo bioship_get_author_avatar();
	echo '</div>';
	bioship_html_comment( '/#author-avatar' );
	echo PHP_EOL;

	// --- author description ---
	bioship_html_comment( '#author-description' );
	echo '<div id="author-description">' . PHP_EOL;

		// --- description title ---
		bioship_html_comment( '#author-title' );
		echo '<h4 id="author-title">' . PHP_EOL;
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo esc_html( bioship_skeleton_about_author_title() );
		echo '</h4>';
		bioship_html_comment( '/#author-title' );
		echo PHP_EOL;

		// --- description text ---
		bioship_html_comment( '#author-description-text' );
		echo '<div id="author-description-text">' . PHP_EOL;
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo bioship_skeleton_about_author_description();
		echo '</div>';
		bioship_html_comment( '/#author-description-text' );
		echo PHP_EOL;

		// --- author posts link ---
		bioship_html_comment( '#author-link' );
		echo '<div id="author-link">' . PHP_EOL;
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo bioship_skeleton_author_posts_link();
		echo '</div>';
		bioship_html_comment( '/#author-link' );
		echo PHP_EOL;

	echo '</div>';
	bioship_html_comment(' /#author-description' );
	echo PHP_EOL;

echo '</div>';
bioship_html_comment( '/#entry-author-info' );
echo PHP_EOL;