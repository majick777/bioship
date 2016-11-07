<?php

/* Tag Archive Primary Sidebar */

if (is_active_sidebar('tag')) { // If the sidebar has widgets

	do_action('skeleton_before_sidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
		<aside <?php hybrid_attr('sidebar','primary'); ?>>

			<?php dynamic_sidebar('tag'); // Displays the primary page sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

	<?php do_action('skeleton_after_sidebar');

}

?>