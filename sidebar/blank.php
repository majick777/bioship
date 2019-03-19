<?php

/* Primary Sidebar: Blank */

if (THEMETRACE) {bioship_trace('T','Blank Sidebar Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-primary sidebar-'.$template);

bioship_do_action('bioship_before_sidebar'); ?>

	<?php bioship_html_comment('#sidebar-primary'); ?>
	<aside <?php hybrid_attr('sidebar', 'primary', $args); ?>>

		&nbsp;

	</aside>
	<?php bioship_html_comment('/#sidebar-primary'); ?>

<?php bioship_do_action('bioship_after_sidebar');

