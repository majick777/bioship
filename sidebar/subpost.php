<?php

/* Subsidiary Post Sidebar (Dual Option) */

if (THEMETRACE) {skeleton_trace('T','Post Subsidebar Template',__FILE__);}

if (is_active_sidebar('subpost')) { // If the sidebar has widgets

	do_action('skeleton_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar','subsidiary'); ?>>

			<?php dynamic_sidebar('subpost'); // Displays the subsidiary post sidebar. ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('skeleton_after_subsidebar');

}

?>