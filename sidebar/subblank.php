<?php

/* Blank Subsidebar */

if (THEMETRACE) {skeleton_trace('T','Blank Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-primary sidebar-'.$vtemplate);

do_action('bioship_before_subsidebar'); ?>

	<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
	<aside <?php hybrid_attr('sidebar','subsidiary',$vargs); ?>>

		&nbsp;

	</aside>
	<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

<?php do_action('bioship_after_subsidebar');

?>