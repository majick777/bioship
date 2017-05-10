<?php

/* Blank Subsidebar */

if (THEMETRACE) {bioship_trace('T','Blank Template',__FILE__);}

$vtemplate = str_replace('.php','',basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-subsidiary sidebar-'.$vtemplate);

bioship_do_action('bioship_before_subsidebar'); ?>

	<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-subsidiary -->';} ?>
	<aside <?php hybrid_attr('sidebar', 'subsidiary', $vargs); ?>>

		&nbsp;

	</aside>
	<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-subsidiary -->';} ?>

<?php bioship_do_action('bioship_after_subsidebar');

?>