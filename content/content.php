<?php

/** Main Post Content Template **/

if (THEMETRACE) {bioship_trace('T','Content Template',__FILE__);}

// Note: For consistency the improved Hybrid template/attribute functions
// are included and used whether full Hybrid Core library is active or not.

$vposttypes = bioship_get_post_types();
if (THEMEDEBUG) {echo "<!-- Post Types: "; print_r($vposttypes); echo " -->";}
if (is_string($vposttypes)) {$vposttype = $vposttypes;} else {$vposttype = '';}

echo '<div class="clear"></div>';

if (THEMECOMMENTS) {echo "<!-- article.entry -->";}
$vattributes = hybrid_get_attr('post');
echo '<article '.$vattributes.'>';

	/* Before Entry Hook */
	bioship_do_action('bioship_before_entry');

	/* Attachment / Media Handler */
	bioship_do_action('bioship_media_handler');

	/* Entry Header */
	bioship_do_action('bioship_entry_header');

	/* Thumbnail */
	bioship_do_action('bioship_thumbnail');

	if ( (THEMEDEBUG) || ( (isset($_GET['debugquery'])) && ($_GET['debugquery'] == 'yes') ) ) :
		// 1.9.5: allow for separate query-only debugging
		global $wp_query; $debugquery = $wp_query;
		echo "<pre style='display:none;'><!-- WP Query: ";	print_r($debugquery); echo " --></pre>";
	endif;

	if (is_search() || (is_archive() && !is_singular($vposttype)) || !is_singular($vposttype)) :

		/* Before Excerpt Hook */
		bioship_do_action('bioship_before_excerpt');

			/* Entry Content Summary */
			if (THEMECOMMENTS) {echo '<!-- .entry-summary -->';}
			echo '<div '.hybrid_get_attr('entry-summary').'>';

				/* the_excerpt() */
				bioship_do_action('bioship_the_excerpt');

			echo '</div>';
			if (THEMECOMMENTS) {echo '<!-- /.entry-summary -->';}

		/* After Excerpt Hook */
		bioship_do_action('bioship_after_excerpt');

		/* Entry Footer */
		bioship_do_action('bioship_entry_footer');

	else :

		/* Full Content Display */

		/* Before Singular Hook */
		bioship_do_action('bioship_before_singular');

			/* Author Bio (top)
			bioship_do_action('bioship_author_bio_top');

				/* Before Main Content */
				bioship_do_action('bioship_before_the_content');

					/* Entry Content (Full) */
					if (THEMECOMMENTS) {echo '<!-- .entry-content -->';}
					echo '<div '.hybrid_get_attr('entry-content').'>';

							/* the_content */
							bioship_do_action('bioship_the_content');

						echo '<div class="clear"></div>';

					echo '</div>';
					if (THEMECOMMENTS) {echo '<!-- /.entry-content -->';}

				/* After Main Content */
				bioship_do_action('bioship_after_the_content');

			/* Author Bio (bottom) */
			bioship_do_action('bioship_author_bio_bottom');

			/* Entry Footer */
			bioship_do_action('bioship_entry_footer');

		/* After Singular Hook */
		bioship_do_action('bioship_after_singular');

		/* Comments */
		bioship_do_action('bioship_comments');

	endif;

	/* After Entry Hook */
	bioship_do_action('bioship_after_entry');

echo '</article>';
if (THEMECOMMENTS) {echo '<!-- /article.entry -->';}

?>