
<?php /* Loop Meta Description Template */ ?>

<?php /* Loop Meta */ ?>

<?php if (THEMECOMMENTS) : ?><!-- .loop-meta --><?php endif; ?>
<?php // TODO: archive-header for HC3, loop-meta for HC2? ?>
<div <?php hybrid_attr('loop-meta'); ?>>

	<?php /* Loop Title */ ?>

	<?php
		 // 1.8.0: replaced hybrid_loop_title (hc3 deprecated)
		 // note: get_the_archive_title filter available
		 if (function_exists('get_the_archive_title')) {$vlooptitle = get_the_archive_title();}
		 elseif (function_exists('hybrid_loop_title')) {$vlooptitle = hybrid_loop_title();}
		 else {$vlooptitle = '';} // TODO: add a fallback here?
		 $vlooptitle = apply_filters('hybrid_loop_title',$vlooptitle);
		 if ($vlooptitle) {
		 	// TODO: archive-title for HC3, loop-title for HC2
		 	if (THEMECOMMENTS) {echo "<!-- .loop-title -->";}
		 	echo "<h2 "; hybrid_attr('loop-title'); echo ">".$vlooptitle."</h2>";
		 	if (THEMECOMMENTS) {echo "<!-- /.loop-title -->";}
		 }
	?>

	<?php /* Loop Description */ ?>

	<?php if (!is_paged()) : // Check if we are on page/1 ?>

		<?php
			// $vdescription = skeleton_get_loop_description();
			// 1.8.0: replace hybrid_get_loop_description (hc3 deprecated)
			if (function_exists('get_the_archive_description')) {$vdescription = get_the_archive_description();}
			elseif (function_exists('hybrid_get_loop_description')) {$vdescription = hybrid_get_loop_description();}
			else {$vdecription = '';} // TODO: add a fallback here?
			$vdescription = apply_filters('hybrid_loop_description',$vdescription);
			if ($vdescription) {
				// TODO: archive-description for HC3, loop-description for HC2
				if (THEMECOMMENTS) {echo "<!-- .loop-description -->";}
				echo "<div "; hybrid_attr('loop-description'); echo ">".$vdescription."</div>";
				if (THEMECOMMENTS) {"<!-- /.loop-description -->";}
			}
		?>

	<?php endif; // End paged check ?>

</div><?php if (THEMECOMMENTS) : ?><!-- /.loop-meta --><?php endif; ?>