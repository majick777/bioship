<?php

/* Taxonomy Archive Primary Sidebar */

if (THEMETRACE) {bioship_trace('T','Taxonomy Archive Sidebar Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

if (is_active_sidebar('taxonomy')) {

	bioship_do_action('bioship_before_sidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-primary -->';} ?>
		<aside <?php hybrid_attr('sidebar', 'primary', $vargs); ?>>

			<?php dynamic_sidebar('taxonomy'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-primary -->';} ?>

	<?php bioship_do_action('bioship_after_sidebar');

}

?>