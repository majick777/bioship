<?php

	/* Comments Navigation (via Hybrid Base) */

	if (THEMETRACE) {skeleton_trace('T',__('Comments Nav Template','bioship'),__FILE__);}

?>

<?php if ( get_option( 'page_comments' ) && 1 < get_comment_pages_count() ) : // Check for paged comments. ?>

	<nav class="comments-nav" role="navigation" aria-labelledby="comments-nav-title">

		<h3 id="comments-nav-title" class="screen-reader-text"><?php _e( 'Comments Navigation', 'bioship' ); ?></h3>

		<?php previous_comments_link( _x( '&larr; Previous', 'comments navigation', 'bioship' ) ); ?>

		<span class="page-numbers"><?php
			/* Translators: Comments page numbers. 1 is current page and 2 is total pages. */
			printf( __( 'Page %1$s of %2$s', 'bioship' ), get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1, get_comment_pages_count() );
		?></span>

		<?php next_comments_link( _x( 'Next &rarr;', 'comments navigation', 'bioship' ) ); ?>

	</nav><!-- .comments-nav -->

<?php endif; // End check for paged comments. ?>