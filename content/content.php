<?php

/** Main Post Content Template **/

if (THEMETRACE) {skeleton_trace('T','Content Template',__FILE__);}

// Note: For consistency the improved Hybrid template/attribute functions
// are included and used whether full Hybrid Core library is active or not.

$vposttypes = skeleton_get_post_types();
if (THEMEDEBUG) {echo "<!-- Post Types: "; print_r($vposttypes); echo " -->";}
if (is_string($vposttypes)) {$vposttype = $vposttypes;} else {$vposttype = '';}

echo '<div class="clear"></div>';

if (THEMECOMMENTS) {echo "<!-- article.entry -->";}
echo '<article '.hybrid_get_attr('post'),'>';

	/* Before Entry Hook */
	do_action('bioship_before_entry');

	/* Attachment / Media Handler */
	do_action('bioship_media_handler');

	/* Entry Header */
	do_action('bioship_entry_header');

	/* Thumbnail */
	do_action('bioship_thumbnail');

	if ( (THEMEDEBUG) || ( (isset($_GET['debugquery'])) && ($_GET['debugquery'] == 'yes') ) ) {
		// 1.9.5: allow for separate query-only debugging
		global $wp_query; $debugquery = $wp_query;
		echo "<pre style='display:none;'><!-- WP Query: ";	print_r($debugquery); echo " --></pre>";
	}

	if (is_search() || (is_archive() && !is_singular($vposttype)) || !is_singular($vposttype)) :

		/* Before Excerpt Hook */
		do_action('bioship_before_excerpt');

			/* Entry Content Summary */
			if (THEMECOMMENTS) {echo '<!-- .entry-summary -->';}
			echo '<div '.hybrid_get_attr('entry-summary').'>';

				/* the_excerpt() */
				do_action('bioship_the_excerpt');

			echo '</div>';
			if (THEMECOMMENTS) {echo '<!-- /.entry-summary -->';}

		/* After Excerpt Hook */
		do_action('bioship_after_excerpt');

		/* Entry Footer */
		do_action('bioship_entry_footer');

	else :

		/* Full Content Display */

		/* Before Singular Hook */
		do_action('bioship_before_singular');

			/* Author Bio (top)
			do_action('bioship_author_bio_top');

				/* Before Main Content */
				do_action('bioship_before_the_content');

					/* Entry Content (Full) */
					if (THEMECOMMENTS) {echo '<!-- .entry-content -->';}
					echo '<div '.hybrid_get_attr('entry-content').'>';

							/* the_content */
							do_action('bioship_the_content');

						echo '<div class="clear"></div>';

					echo '</div>';
					if (THEMECOMMENTS) {echo '<!-- /.entry-content -->';}

				/* After Main Content */
				do_action('bioship_after_the_content');

			/* Author Bio (bottom) */
			do_action('bioship_author_bio_bottom');

			/* Entry Footer */
			do_action('bioship_entry_footer');

		/* After Singular Hook */
		do_action('bioship_after_singular');

		/* Comments */
		do_action('bioship_comments');

	endif;

	/* After Entry Hook */
	do_action('bioship_after_entry');

echo '</article>';
if (THEMECOMMENTS) {echo '<!-- /article.entry -->';}

?>