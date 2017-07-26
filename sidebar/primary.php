<?php

/* Primary Sidebar (Unified Option) */

if (THEMETRACE) {bioship_trace('T','Unified Sidebar Template',__FILE__);}

if (is_active_sidebar('primary')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary'); ?>>

			<?php dynamic_sidebar('primary'); ?>

		</aside><?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

?>