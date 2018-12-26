<?php

/* Primary Sidebar: Author Archive */

if (THEMETRACE) {bioship_trace('T','Author Archive Sidebar Template',__FILE__);}

$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

if (is_active_sidebar('author')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php bioship_html_comment('#sidebar-primary'); ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $vargs); ?>>

			<?php dynamic_sidebar('author'); ?>

		</aside>
		<?php bioship_html_comment('/#sidebar-primary'); ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

