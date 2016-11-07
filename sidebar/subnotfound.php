<?php

/* 404 Not Found Subsidiary Sidebar */

if (is_active_sidebar('subnotfound')) { // if the sidebar has widgets

	do_action('skeleton_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar','subsidiary'); ?>>

			<?php dynamic_sidebar('subnotfound'); // Displays the primary page sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('skeleton_after_subsidebar');

}

?>