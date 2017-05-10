<?php

/* Primary Posts Sidebar (Dual Option) */

if (THEMETRACE) {skeleton_trace('T','Post Sidebar Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

if (is_active_sidebar('posts')) {

	do_action('bioship_before_sidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
		<aside <?php hybrid_attr('sidebar','primary',$vargs); ?>>

			<?php dynamic_sidebar('posts'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

	<?php do_action('bioship_after_sidebar');

}

?>