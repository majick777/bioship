<?php

	/* Loop Navigation (via Hybrid Base) */

	if (THEMETRACE) {skeleton_trace('T',__('Loop Nav Template','bioship'),__FILE__);}

?>

<?php if ( is_singular() ) {
	// If viewing a single page post type.

	/* Post/Page Navigation */

	do_action('bioship_page_navi');

	//	<!-- .loop-nav --><div class="loop-nav">
	//		<#php previous_post_link( '<div class="prev">' . __( 'Previous '.$vposttypedisplay.': %link', 'bioship' ) . '</div>', '%title' ); #>
	//		<#php next_post_link(     '<div class="next">' . __( 'Next '.$vposttypedisplay.': %link',     'bioship' ) . '</div>', '%title' ); #>
	//	</div><!-- /.loop-nav -->

} elseif ( is_home() || is_archive() || is_search() ) {
	// If viewing the blog, an archive, or search results.

	// 1.8.0: fix to use same function now
	do_action('bioship_page_navi');

	// loop_pagination(
	//	array(
	//		'prev_text' => _x( '&larr; Previous', 'posts navigation', 'bioship' ),
	//		'next_text' => _x( 'Next &rarr;',     'posts navigation', 'bioship' )
	//	)
	// );

} ?>
