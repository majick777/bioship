<?php

/* Primary Sidebar: Search Results */

if (THEMETRACE) {bioship_trace('T','Search Sidebar Template',__FILE__,'sidebar');}

// 2.0.9: fix to mismatching class attribute (subsidiary)
$template = str_replace('.php','',basename(__FILE__));
$args = array('class' => 'sidebar sidebar-primary sidebar-'.$template);

if (is_active_sidebar('search')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar','primary', $args); ?>>

			<?php dynamic_sidebar('search'); ?>

		</aside><?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

