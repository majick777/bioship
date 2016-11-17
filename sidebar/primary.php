<?php

/* Primary Sidebar (Unified Option) */

if (THEMETRACE) {skeleton_trace('T','Unified Sidebar Template',__FILE__);}

if (is_active_sidebar('primary')) { // if the sidebar has widgets

	do_action('skeleton_before_sidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
		<aside <?php hybrid_attr('sidebar','primary'); ?>>

			<?php dynamic_sidebar('primary'); // Displays the primary page sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

	<?php do_action('skeleton_after_sidebar');

}

?>