<?php

/* Subsidiary Page Sidebar (Dual Option) */

if (THEMETRACE) {bioship_trace('T','Page Subsidebar Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$vtemplate);

if (is_active_sidebar('subpage')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary', $vargs); ?>>

			<?php dynamic_sidebar('subpage'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}

?>