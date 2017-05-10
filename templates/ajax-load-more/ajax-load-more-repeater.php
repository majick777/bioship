<!-- AJAX Load More Repeater Template for Excerpts -->
<article <?php hybrid_attr('post'); ?>>
	<?php do_action('bioship_before_entry'); ?>
		<?php do_action('bioship_entry_header'); ?>
			<?php do_action('bioship_thumbnail'); ?>
			<?php do_action('bioship_before_excerpt'); ?>
				<div <?php hybrid_attr('entry-summary'); ?>>
					<?php do_action('bioship_the_excerpt'); ?>
				</div>
			<?php do_action('bioship_after_excerpt'); ?>
		<?php do_action('bioship_entry_footer'); ?>
	<?php do_action('bioship_after_entry'); ?>
</article>