<?php

/* Primary Sidebar: Author Archive */

if (THEMETRACE) {bioship_trace('T','Author Archive Sidebar Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-primary sidebar-'.$template);

if (is_active_sidebar('author')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $args); ?>>

			<?php dynamic_sidebar('author'); ?>

		</aside>
		<?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

