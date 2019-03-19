<?php

/* Subsidiary Sidebar: Tag Archive */

if (THEMETRACE) {bioship_trace('T','Tag Archive Subsidebar Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$template);

if (is_active_sidebar('subtag')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php bioship_html_comment('#sidebar-subsidiary'); ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary', $args); ?>>

			<?php dynamic_sidebar('subtag'); ?>

		</aside><?php bioship_html_comment('/#sidebar-subsidiary'); ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}

