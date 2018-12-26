<?php

/* Primary Sidebar: Tag Archive */

if (THEMETRACE) {bioship_trace('T','Tag Archive Sidebar Template',__FILE__);}

$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

if (is_active_sidebar('tag')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $vargs); ?>>

			<?php dynamic_sidebar('tag'); ?>

		</aside><?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

