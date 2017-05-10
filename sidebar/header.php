<?php

/* Header Widget Area */

if (THEMETRACE) {skeleton_trace('T','Header Widget Area Template',__FILE__);}

do_action('bioship_before_header_widgets');

	if (is_active_sidebar('header-widget-area')) : ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-header -->';} ?>
		<div <?php hybrid_attr('sidebar','header'); ?>>

			<?php dynamic_sidebar('header-widget-area'); ?>

		</div>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-header -->';} ?>

	<?php endif;

do_action('bioship_after_header_widgets');

?>