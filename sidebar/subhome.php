<?php

/* Subsidiary Sidebar: Homepage (Blog) */

if (THEMETRACE) {bioship_trace('T','Home Subsidebar Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$template);

if (is_active_sidebar('subhomepage')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php bioship_html_comment('#sidebar-subsidiary'); ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary', $args); ?>>

			<?php dynamic_sidebar('subhomepage'); ?>

		</aside><?php bioship_html_comment('/#sidebar-subsidiary'); ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}

