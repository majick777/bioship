<?php

/* Primary Sidebar (Unified Option) */

if (THEMETRACE) {skeleton_trace('T','Unified Sidebar Template',__FILE__);}

if (is_active_sidebar('primary')) {

	do_action('bioship_before_sidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
		<aside <?php hybrid_attr('sidebar','primary'); ?>>

			<?php dynamic_sidebar('primary'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

	<?php do_action('bioship_after_sidebar');

}

?>