<?php

/* Subsidiary Sidebar: Single Post Type */

/* Note: Unified Option */

if (THEMETRACE) {bioship_trace('T','Unified Subsidebar Template',__FILE__);}

// 2.0.9: add template name to class attribute
$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$vtemplate);

if (is_active_sidebar('subsidiary')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php bioship_html_comment('#sidebar-subsidiary'); ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary'); ?>>

			<?php dynamic_sidebar('subsidiary'); ?>

		</aside><?php bioship_html_comment('/#sidebar-subsidiary'); ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}

