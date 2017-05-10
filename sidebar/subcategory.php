<?php

/* Category Archive Subsidiary Sidebar */

if (THEMETRACE) {bioship_trace('T','Category Archive Subsidebar Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$vtemplate);

if (is_active_sidebar('subcategory')) {

	bioship_do_action('bioship_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar', 'subsidiary', $vargs); ?>>

			<?php dynamic_sidebar('subcategory'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php bioship_do_action('bioship_after_subsidebar');

}

?>