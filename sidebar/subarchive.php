<?php

/* Archive Page Subsidiary Sidebar */

if (THEMETRACE) {skeleton_trace('T','Archive Subsidebar Template',__FILE__);}

if (is_active_sidebar('subarchive')) { // if the sidebar has widgets

	do_action('skeleton_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar','subsidiary'); ?>>

			<?php dynamic_sidebar('subarchive'); // Displays the primary page sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('skeleton_after_subsidebar');

}

?>