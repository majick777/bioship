<!-- AJAX Load More Repeater Template for Excerpts -->
<article <?php hybrid_attr('post'); ?>>
	<?php do_action('skeleton_before_entry'); ?>
		<?php do_action('skeleton_entry_header'); ?>
			<?php do_action('skeleton_thumbnail'); ?>
			<?php do_action('skeleton_before_excerpt'); ?>
				<div <?php hybrid_attr('entry-summary'); ?>>
					<?php do_action('skeleton_the_excerpt'); ?>
				</div>
			<?php do_action('skeleton_after_excerpt'); ?>
		<?php do_action('skeleton_entry_footer'); ?>
	<?php do_action('skeleton_after_entry'); ?>
</article>