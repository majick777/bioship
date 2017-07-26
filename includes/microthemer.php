<?php

	/* BioShip Microthemer Scaffold */
	/* (writes array to JSON file) */
	/* v0.1.0 (alpha) */

	// include STD Class?


	// set section data
	$sections = array();
	$sections['main_body'] =
    $sections['header'] =
    $sections['main_menu'] =
    $sections['content'] =
    $sections['pages'] =
    $sections['posts'] =
    $sections['posts_single_view'] =
    $sections['post_navigation'] =
    $sections['comments'] =
    $sections['leave_reply_form'] =
    $sections['main_asides'] =
    $sections['search_site'] =
    $sections['footer'] =
    $sections['subsidiary_asides'] =
    $sections['main_widget_areas'] =
    $sections['footer_widget_areas'] =
    $sections['widget_areas'] =
    $sections['main_widget_area'] =
    $sections['top_strip'] =
    $sections['breadcrumbs'] =
    $sections['results_pagination'] =
    $sections['sidebar_widget_area'] =
    $sections['contact_poster_sidebar_form'] =
    $sections['ads_search_bar'] =
    $sections['featured_listings_slider'] =
    $sections['home_and_widget_tabs'] =
    $sections['homepage_ad_categories'] =
    $sections['homepage_listings'] =
    $sections['dashboard_listings'] =
    $sections['single_ad_listing'] =
    $sections['post_an_ad_steps'] =
    $sections['edit_profile_page'] =
    $sections['footer_menu'] =
    $sections['footer_widget_area'] =
    $sections['top_social_media_area'] =
    $sections['title_banner'] =
    $sections['home_page_before_content_widget_area'] =
    $sections['home_page_column_widget_areas'] =
    $sections['home_page_after_content_widget_areas'] =
    $sections['all_page_sidebar_widget_areas'] =
    $sections['bottom_widget_areas'] =
    $sections['search_widget'] =
    $sections['sidebar_widget_areas'] =
    $sections['content_widget_areas'] =
    $sections['page_navigation'] =
    $sections['sidebar_widgets'] =
    $sections['footer_widgets'] =

	// $sections['non_section'] =

    // set style keys
 	$styles = array(
 		'font' => '',
 		'text' => '',
 		'forecolor' => '',
        'background' => '',
        'dimensions' => '',
		'padding' => '',
		'margin' => '',
		'border' => '',
		'behaviour' => '',
		'position' => '',
		'CSS3' => ''
	);

	// add style keys to each section
 	$data = array();
 	foreach ($sections as $sectionkey => $section) {
 		$data[$sectionkey]['label'] = $section;
 		$data[$sectionkey]['styles'] = $styles;
 		$data[$sectionkey]['PIE'] = '0';
 	}


	// convert to json
	$json = json_encode($data);

	// maybe write to file
	// $filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'bioship-scaffold.json';
	// $fh = fopen($filename,'w'); fwrite($fh,$data); fclose($fh);

	// all done here.

?>