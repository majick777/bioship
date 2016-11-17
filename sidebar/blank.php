<?php

/* Blank Sidebar */

if (THEMETRACE) {skeleton_trace('T','Blank Sidebar Template',__FILE__);}

do_action('skeleton_before_sidebar'); ?>

	<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
	<aside <?php hybrid_attr('sidebar','primary'); ?>>

		&nbsp;

	</aside>
	<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

<?php do_action('skeleton_after_sidebar');

?>