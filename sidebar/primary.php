<?php

/* Primary Sidebar: Single Post Type */

/* note: Unified Option */

if (THEMETRACE) {bioship_trace('T','Unified Sidebar Template',__FILE__,'sidebar');}

// 2.0.9: add template name to class attribute
// 2.1.1: add missing args to attribute function call
$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-primary sidebar-'.$template);

if (is_active_sidebar('primary')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $args); ?>>

			<?php dynamic_sidebar('primary'); ?>

		</aside><?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

