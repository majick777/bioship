<?php

/* Author Bio Template */

if (THEMETRACE) {bioship_trace('T',__('Author Bio Template','bioship'),__FILE__);}

?>

<?php if (THEMECOMMENTS) {echo "<!-- #entry-author-info -->";} ?><div id="entry-author-info">
	<?php if (THEMECOMMENTS) {echo "<!-- #author-avatar -->";} ?><div id="author-avatar">
		<?php echo bioship_get_author_avatar(); ?>
	</div><?php if (THEMECOMMENTS) {echo "<!-- /#author-avatar -->";} ?>
	<?php if (THEMECOMMENTS) {echo "<!-- #author-description -->";} ?><div id="author-description">
		<?php if (THEMECOMMENTS) {echo "<!-- #author-title -->";} ?><h4 id="author-title">
			<?php echo bioship_skeleton_about_author_title(); ?>
		</h4><?php if (THEMECOMMENTS) {echo "<!-- /#author-title -->";} ?>
		<?php if (THEMECOMMENTS) {echo "<!-- #author-description-text -->";} ?><div id="author-description-text">
			<?php echo bioship_skeleton_about_author_description(); ?>
		</div><?php if (THEMECOMMENTS) {echo "<!-- /#author-description-text -->";} ?>
		<?php if (THEMECOMMENTS) {echo "<!-- #author-link -->";} ?><div id="author-link">
			<?php echo bioship_skeleton_author_posts_link(); ?>
		</div><?php if (THEMECOMMENTS) {echo "<!-- /#author-link -->";} ?>
	</div><?php if (THEMECOMMENTS) {echo "<!-- /#author-description -->";} ?>
</div><?php if (THEMECOMMENTS) {echo "<!-- /#entry-author-info -->";} ?>

