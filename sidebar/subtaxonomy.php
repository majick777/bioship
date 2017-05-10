<?php

/* Taxonomy Archive Subsidiary Sidebar */

if (THEMETRACE) {skeleton_trace('T','Taxonomy Archive Subsidebar Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

if (is_active_sidebar('subtaxonomy')) {

	do_action('bioship_before_subsidebar'); ?>

		<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
		<aside <?php hybrid_attr('sidebar','subsidiary',$vargs); ?>>

			<?php dynamic_sidebar('subtaxonomy'); ?>

		</aside>
		<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

	<?php do_action('bioship_after_subsidebar');

}

?>