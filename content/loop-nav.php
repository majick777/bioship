
<?php /* Loop Navigation (via Hybrid Base) */ ?>

<?php if ( is_singular() ) : // If viewing a single page post type. ?>

	<?php
		/* Post/Page Navigation */
		do_action('skeleton_page_navi');

		//	<!-- .loop-nav --><div class="loop-nav">
		//		<#php previous_post_link( '<div class="prev">' . __( 'Previous '.$vposttypedisplay.': %link', 'bioship' ) . '</div>', '%title' ); #>
		//		<#php next_post_link(     '<div class="next">' . __( 'Next '.$vposttypedisplay.': %link',     'bioship' ) . '</div>', '%title' ); #>
		//	</div><!-- /.loop-nav -->
	?>

<?php elseif ( is_home() || is_archive() || is_search() ) : // If viewing the blog, an archive, or search results. ?>

	<?php
		// 1.8.0: fix to use same function now
		do_action('skeleton_page_navi');

		// loop_pagination(
		//	array(
		//		'prev_text' => _x( '&larr; Previous', 'posts navigation', 'bioship' ),
		//		'next_text' => _x( 'Next &rarr;',     'posts navigation', 'bioship' )
		//	)
		// );
	?>

<?php endif; // End check for type of page being viewed. ?>