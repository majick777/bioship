<?php

// ===================================
// === BioShip Comments Navigation ===
// ===================================
// (original template via Hybrid Base Theme)

if (THEMETRACE) {bioship_trace('T',__('Comments Nav Template','bioship'),__FILE__,'comments');}

?>

<?php // --- Check for Paged Comments ---
if ( get_option( 'page_comments' ) && 1 < get_comment_pages_count() ) :  ?>

	<?php bioship_html_comment('.comments-nav'); ?>
	<nav class="comments-nav" role="navigation" aria-labelledby="comments-nav-title">

		<h3 id="comments-nav-title" class="screen-reader-text"><?php echo esc_attr(__('Comments Navigation', 'bioship')); ?></h3>

		<?php previous_comments_link( _x('&larr; Previous', 'comments navigation', 'bioship') ); ?>

		<span class="page-numbers"><?php
			/* Translators: Comments page numbers. 1 is current page and 2 is total pages. */
			printf( esc_attr(_x('Page %1$s of %2$s', 'comments pagination', 'bioship')), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() );
		?></span>

		<?php next_comments_link( esc_attr(_x('Next &rarr;', 'comments navigation', 'bioship') ) ); ?>

	</nav><?php bioship_html_comment('/.comments-nav'); ?>

<?php endif; ?>