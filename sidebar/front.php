<?php

/* Primary Sidebar: Frontpage */

if (THEMETRACE) {bioship_trace('T','Frontpage Sidebar Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-primary sidebar-'.$template);

if (is_active_sidebar('frontpage')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $args); ?>>

			<?php dynamic_sidebar('frontpage'); ?>

		</aside><?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

