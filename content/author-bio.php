<?php

// ===================================
// === BioShip Author Bio Template ===
// ===================================

if (THEMETRACE) {bioship_trace('T',__('Author Bio Template','bioship'),__FILE__,'content');}

?>

<?php bioship_html_comment('#entry-author-info'); ?><div id="entry-author-info">
	<?php bioship_html_comment('#author-avatar'); ?><div id="author-avatar">
		<?php echo bioship_get_author_avatar(); ?>
	</div><?php bioship_html_comment('/#author-avatar'); ?>
	<?php bioship_html_comment('#author-description'); ?><div id="author-description">
		<?php bioship_html_comment('#author-title'); ?><h4 id="author-title">
			<?php echo bioship_skeleton_about_author_title(); ?>
		</h4><?php bioship_html_comment('/#author-title'); ?>
		<?php bioship_html_comment('#author-description-text'); ?><div id="author-description-text">
			<?php echo bioship_skeleton_about_author_description(); ?>
		</div><?php bioship_html_comment('/#author-description-text'); ?>
		<?php bioship_html_comment('#author-link'); ?><div id="author-link">
			<?php echo bioship_skeleton_author_posts_link(); ?>
		</div><?php bioship_html_comment('/#author-link'); ?>
	</div><?php bioship_html_comment('/#author-description'); ?>
</div><?php bioship_html_comment('/#entry-author-info'); ?>