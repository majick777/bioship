<?php

/* Subsidiary Page Sidebar (Dual Option) */

if (THEMETRACE) {skeleton_trace('T','Page Subsidebar Template',__FILE__);}

if (is_active_sidebar('subpage')) { // If the sidebar has widgets.

	do_action('skeleton_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar','subsidiary'); ?>>

			<?php dynamic_sidebar('subpage'); // Displays the subsidiary page sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('skeleton_after_subsidebar');

}

?>