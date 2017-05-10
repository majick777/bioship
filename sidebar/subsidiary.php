<?php

/* Subsidiary Sidebar (Unified Option) */

if (THEMETRACE) {skeleton_trace('T','Unified Subsidebar Template',__FILE__);}

if (is_active_sidebar('subsidiary')) {

	do_action('bioship_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr( 'sidebar', 'subsidiary' ); ?>>

			<?php dynamic_sidebar('subsidiary'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('bioship_after_subsidebar');

}

?>