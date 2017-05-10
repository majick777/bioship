<?php

/* BioShip Hybrid Content Loop */
/* based on Hybrid Base template */

if (THEMETRACE) {skeleton_trace('T',__('Loop Index Template','bioship'),__FILE__);}

// Note: For consistency the improved Hybrid template/attribute functions
// are included and used whether full Hybrid Core library is active or not.

$vposttypes = skeleton_get_post_types();
if (THEMEDEBUG) {echo "<!-- Post Types: "; print_r($vposttypes); echo " -->";}
if (is_string($vposttypes)) {$vposttype = $vposttypes;}

/* Before Content Hook */
do_action('bioship_before_content');

if (THEMECOMMENTS) {echo "<!-- #maincontent -->";}
echo '<main '.hybrid_get_attr('content').'>';

	// If viewing a multi-post page
	if ( !is_front_page() && !is_singular() && !is_404() ) {
		// Loads the content/loop-meta.php template
		// 1.5.0: change from locate_template
		skeleton_locate_template('content/loop-meta.php', true);
	}

	// 1.6.0: for front page 'blog' only, call top content action hook
	if ( (is_front_page()) &&  (get_option('show_on_front') == 'posts') ) {
		// no default here, just hook a function to use it
		// 1.9.8: shorten action name from skeleton_front_page_top_html
		do_action('bioship_front_page_top');
		rewind_posts(); // ah to be sure to sure
	}

	// 1.6.0: for home 'blog' page only, to show page content above posts
	// ref: http://zeo.my/wordpress-display-the-contents-of-static-page-posts-page/
	if ( (is_home()) && (get_option('show_on_front') == 'page') ) {
		// 1.8.5: moved to skeleton.php and use action hook
		// 1.9.8: shorten action name from skeleton_home_page_top_html
		do_action('bioship_home_page_top');
		rewind_posts(); // ah to be sure to sure
	}

	// 1.6.0: added before loop hook
	do_action('bioship_before_loop');
	// 1.8.5: added specific archive loop hooks
	// 1.9.0: double-check singular for odd queries
	// 1.9.5: fix to is_date typo for new action
	if (is_archive() && !is_singular($vposttype)) {
		do_action('bioship_before_archive');
		if (is_category()) {do_action('bioship_before_category');}
		elseif (is_tax()) {do_action('bioship_before_taxonomy');}
		elseif (is_tag()) {do_action('bioship_before_tags');}
		elseif (is_author()) {do_action('bioship_before_author');}
		elseif (is_date()) {do_action('bioship_before_date');}
	}

	// Checks if any posts were found
	if ( have_posts() ) :

		// Begins the loop through found posts
		while ( have_posts() ) :

			// Loads the post data
			the_post();

			// Hybrid Template Hierarchy
			// -------------------------
			// supporting Post Types and Post Formats
			// (Modifyable with filter: hybrid_content_template_hierarchy)
			// 1.9.5: added optional archive directory to hierarchy
			// archive/{$post_type}-{$post_format}.php
			// archive/{$post_format}.php
			// archive/{$post_type}.php
			// default content template hierarchy
			// content-{$post_type}-{$post_format}.php
			// content/{$post_type}-{$post_format}.php
			// content-{$post_format}.php
			// content/{$post_format}.php
			// content-{$post_type}.php
			// content/{$post_type}.php
			// content/content.php (default)

			// Loads the content template
			hybrid_get_content_template();

		endwhile; // End content loop

		// Loads the content/loop-nav.php template
		// 1.5.0: change from locate_template
		skeleton_locate_template('content/loop-nav.php', true);

	// If no posts were found
	else :

		// Loads the content/error.php template.
		// 1.5.0: change from locate_template
		skeleton_locate_template('content/error.php', true);

	endif; // End no posts

	// 1.8.5: added specific archive loop hooks
	// 1.9.0: double-check singular for odd queries
	if (is_archive() && !is_singular($vposttype)) {
		if (is_date()) {do_action('bioship_after_date');}
		elseif (is_author()) {do_action('bioship_after_author');}
		elseif (is_tag()) {do_action('bioship_after_tags');}
		elseif (is_tax()) {do_action('bioship_after_taxonomy');}
		elseif (is_category()) {do_action('bioship_after_category');}
		// 2.0.1: fix to before archive typo
		do_action('bioship_after_archive');
	}
	// 1.6.0: added after loop hook
	do_action('bioship_after_loop');

echo "</main>";
if (THEMECOMMENTS) {echo "<!-- /#maincontent -->";}

/* After Content Hook */
do_action('bioship_after_content');

?>
