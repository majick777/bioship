<?php

	/* Author Bio Template */

	if (THEMETRACE) {skeleton_trace('T',__('Author Bio Template','bioship'),__FILE__);}

?>

<!-- #entry-author-info --><div id="entry-author-info">
	<!-- #author-avatar --><div id="author-avatar">
	<?php echo skeleton_get_author_avatar(); ?>
	</div><!-- /#author-avatar -->

	<!-- #author-description --><div id="author-description">
		<!-- #author-title --><h4 id="author-title">
		<?php echo skeleton_about_author_title(); ?>
		</h4><!-- /#author-title -->
		<!-- #author-desctription-text --><div id="author-description-text">
		<?php the_author_meta('description'); ?>
		</div><!-- /#author-description-text -->

		<!-- #author-link --><div id="author-link">
			<?php echo skeleton_author_posts_link(); ?>
		</div><!-- /#author-link -->
	</div><!-- /#author-description -->
</div><!-- /#entry-author-info -->

