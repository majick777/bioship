<?php

// ============================
// === BioShip Content Loop ===
// ============================

// orginally forked from Hybrid Base theme template

if (THEMETRACE) {bioship_trace('T',__('Loop Index Template','bioship'),__FILE__,'loop');}

// Note: For consistency, the improved Hybrid template/attribute functions
// are included and used whether full Hybrid Core library is active or not.

// --- get post type(s) ---
$posttypes = bioship_get_post_types();
if (THEMEDEBUG) {echo "<!-- Post Types: "; print_r($posttypes); echo " -->";}
if (is_string($posttypes)) {$posttype = $posttypes;}

// --- Before Content ---
bioship_do_action('bioship_before_content');

// --- open main content tag ---
bioship_html_comment('#maincontent');
$attributes = hybrid_get_attr('content');
echo '<main '.$attributes.'>';

	// --- if viewing a multi-post page ---
	if ( !is_front_page() && !is_singular() && !is_404() ) :
		// Loads the content/loop-meta.php template
		// 1.5.0: change from locate_template
		bioship_locate_template('content/loop-meta.php', true);
	endif;

	// --- Frontpage Top ---
	// 1.6.0: for front page 'blog' only, call top content action hook
	if ( is_front_page() &&  (get_option('show_on_front') == 'posts') ) :
		// no default here, just hook a function to use it
		// 1.9.8: shorten action name from front_page_top_html
		bioship_do_action('bioship_front_page_top');
		rewind_posts(); // ah to be sure to sure
	endif;

	// --- Homepage Top ---
	// 1.6.0: for home 'blog' page only, to show page content above posts
	// ref: http://zeo.my/wordpress-display-the-contents-of-static-page-posts-page/
	if ( is_home() && (get_option('show_on_front') == 'page') ) :
		// 1.8.5: moved to skeleton.php and use action hook
		// 1.9.8: shorten action name from home_page_top_html
		bioship_do_action('bioship_home_page_top');
		rewind_posts(); // ah to be sure to sure
	endif;

	// --- Before Loop ---
	// 1.6.0: added before loop hook
	bioship_do_action('bioship_before_loop');

	// --- Before Archive Hooks ---
	// 1.8.5: added specific archive loop hooks
	// 1.9.0: double-check singular for odd queries
	// 1.9.5: fix to is_date typo for new action
	if ( is_archive() && !is_singular($posttype) ) :
		bioship_do_action('bioship_before_archive');
		if (is_category()) {bioship_do_action('bioship_before_category');}
		elseif (is_tax()) {bioship_do_action('bioship_before_taxonomy');}
		elseif (is_tag()) {bioship_do_action('bioship_before_tags');}
		elseif (is_author()) {bioship_do_action('bioship_before_author');}
		elseif (is_date()) {bioship_do_action('bioship_before_date');}
	endif;

	// --- check if any posts were found ---
	if ( have_posts() ) :

		// --- begin the loop through found posts ---
		while ( have_posts() ) :

			// --- loads the post data ---
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

			// --- loads the content template ----
			// 2.0.5: added wrapper function
			bioship_get_content_template();

		endwhile;

		// --- load the content/loop-nav.php template ---
		// 1.5.0: change from locate_template
		bioship_locate_template('content/loop-nav.php', true);

	// --- if no posts were found ---
	else :

		// --- load the content/error.php template ---
		// 1.5.0: change from locate_template
		bioship_locate_template('content/error.php', true);

	endif;

	// --- After Archive Hooks ---
	// 1.8.5: added specific archive loop hooks
	// 1.9.0: double-check singular for odd queries
	if ( is_archive() && !is_singular($posttype) ) :
		if (is_date()) {bioship_do_action('bioship_after_date');}
		elseif (is_author()) {bioship_do_action('bioship_after_author');}
		elseif (is_tag()) {bioship_do_action('bioship_after_tags');}
		elseif (is_tax()) {bioship_do_action('bioship_after_taxonomy');}
		elseif (is_category()) {bioship_do_action('bioship_after_category');}
		// 2.0.1: fix to before archive typo
		bioship_do_action('bioship_after_archive');
	endif;

	// --- Homepage Bottom ---
	// 2.1.1: added homepage bottom to mirror homepage top
	// ref: http://zeo.my/wordpress-display-the-contents-of-static-page-posts-page/
	if ( is_home() && (get_option('show_on_front') == 'page') ) :
		bioship_do_action('bioship_home_page_bottom');
		rewind_posts(); // ah to be sure to sure
	endif;

	// --- Frontpage Bottom ---
	// 2.1.1: added frontpage bottom to mirror frontpage top
	if ( is_front_page() &&  (get_option('show_on_front') == 'posts') ) :
		bioship_do_action('bioship_front_page_bottom');
		rewind_posts(); // ah to be sure to sure
	endif;

	// --- After Loop ---
	// 1.6.0: added after loop hook
	bioship_do_action('bioship_after_loop');

// --- close main content tag ---
echo "</main>";
bioship_html_comment('/#maincontent');

// --- After Content ---
bioship_do_action('bioship_after_content');
