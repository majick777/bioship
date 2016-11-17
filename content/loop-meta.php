<?php

	/* Loop Meta Description Template */

	if (THEMETRACE) {skeleton_trace('T',__('Loop Meta Template','bioship'),__FILE__);}

?>

<?php // 1.9.8: switch from .loop-meta to .archive-header
if (THEMECOMMENTS) {echo "<!-- .archive-header -->";} ?>
<div <?php hybrid_attr('archive-header'); ?>>

<?php

	/* Loop Title */

	$vlooptitle = skeleton_get_loop_title();
	if ($vlooptitle) {
		// 1.9.8: switch from .loop-title to .archive-title
		if (THEMECOMMENTS) {echo "<!-- .archive-title -->";}
		echo "<h2 "; hybrid_attr('arhive-title'); echo ">".$vlooptitle."</h2>";
		if (THEMECOMMENTS) {echo "<!-- /.archive-title -->";}
	}

	/* Loop Description */

	if (!is_paged()) { // Check if we are on page/1

		$vdescription = skeleton_get_loop_description();
		if ($vdescription) {
			// 1.9.8: switch from .loop-description to .archive-description
			if (THEMECOMMENTS) {echo "<!-- .archive-description -->";}
			echo "<div "; hybrid_attr('archive-description'); echo ">".$vdescription."</div>";
			if (THEMECOMMENTS) {"<!-- /.archive-description -->";}
		}
	}
?>

</div><?php if (THEMECOMMENTS) {echo "<!-- /.archive-header -->";} ?>