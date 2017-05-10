<!-- AJAX Load More Repeater Template for Excerpts -->
<article <?php hybrid_attr('post'); ?>>
	<?php bioship_do_action('bioship_before_entry'); ?>
		<?php bioship_do_action('bioship_entry_header'); ?>
			<?php bioship_do_action('bioship_thumbnail'); ?>
			<?php bioship_do_action('bioship_before_excerpt'); ?>
				<div <?php hybrid_attr('entry-summary'); ?>>
					<?php bioship_do_action('bioship_the_excerpt'); ?>
				</div>
			<?php bioship_do_action('bioship_after_excerpt'); ?>
		<?php bioship_do_action('bioship_entry_footer'); ?>
	<?php bioship_do_action('bioship_after_entry'); ?>
</article>