<?php

/* Blank Subsidebar */

do_action('skeleton_before_subsidebar'); ?>

	<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
	<aside <?php hybrid_attr( 'sidebar', 'subsidiary' ); ?>>

		&nbsp;

	</aside>
	<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

<?php do_action('skeleton_after_subsidebar');

?>