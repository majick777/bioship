<?php

/* Subsidiary Sidebar: Date Archive  */

if (THEMETRACE) {bioship_trace('T','Date Archive Sidebar Template',__FILE__);}

$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$vtemplate);

if (is_active_sidebar('subdate')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php bioship_html_comment('#sidebar-subsidiary'); ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary', $vargs); ?>>

			<?php dynamic_sidebar('subdate'); ?>

		</aside><?php bioship_html_comment('/#sidebar-subsidiary'); ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}
