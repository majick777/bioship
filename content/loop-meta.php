<?php

/* Loop Meta Description Template */

if (THEMETRACE) {bioship_trace('T',__('Loop Meta Template','bioship'),__FILE__);}

?>

<?php // 1.9.8: switch from .loop-meta to .archive-header
bioship_html_comment('.archive-header'); ?>
<div <?php hybrid_attr('archive-header'); ?>>

<?php

	/* Loop Title */

	$vlooptitle = bioship_get_loop_title();
	if ($vlooptitle) {
		// 1.9.8: switch from .loop-title to .archive-title
		bioship_html_comment('.archive-title');
		echo "<h2 "; hybrid_attr('archive-title'); echo ">".$vlooptitle."</h2>";
		bioship_html_comment('/.archive-title');
	}

	/* Loop Description */

	if (!is_paged()) { // Check if we are on page/1

		$vdescription = bioship_get_loop_description();
		if ($vdescription) {
			// 1.9.8: switch from .loop-description to .archive-description
			bioship_html_comment('.archive-description');
			echo "<div "; hybrid_attr('archive-description'); echo ">".$vdescription."</div>";
			bioship_html_comment('/.archive-description');
		}
	}
?>

</div><?php bioship_html_comment('/.archive-header'); ?>