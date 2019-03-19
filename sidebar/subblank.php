<?php

/* Subsidiary Sidebar: Blank */

if (THEMETRACE) {bioship_trace('T','Blank Template',__FILE__,'sidebar');}

$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$template);

bioship_do_action('bioship_before_subsidebar'); ?>

	<?php bioship_html_comment('#sidebar-subsidiary'); ?>
	<aside <?php hybrid_attr('sidebar', 'subsidiary', $args); ?>>

		&nbsp;

	</aside><?php bioship_html_comment('/#sidebar-subsidiary'); ?>

<?php bioship_do_action('bioship_after_subsidebar');

