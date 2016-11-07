<?php

/* Header Widget Area */

$showsidebar = true;

if (is_singular(get_post_type())) {
	// 1.8.0: fix undeclared object post->ID
	if (get_post_meta(get_the_ID(), "_hideheaderwidgets", true) == "1") {
	  	$showsidebar = false;
	}
}

if ($showsidebar) {

	do_action('skeleton_before_header_widgets');

		if (is_active_sidebar('header-widget-area')) : ?>

			<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-header -->';} ?>
			<div <?php hybrid_attr('sidebar','header'); ?>>

				<?php dynamic_sidebar('header-widget-area'); ?>

			</div>
			<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-header -->';} ?>

		<?php endif;

	do_action('skeleton_after_header_widgets');

}

?>