<?php

if (!function_exists('add_action')) {exit;}

// ==========================
// Options for Customizer API
// ==========================

// --------
// TODO: customizer welcome message for new activations (welcome=true)
// CHECKME: Kirki: Typo found in field xxxxx ("setting" instead of "settings") ???
// 2.0.5: added tracer calls within Customizer functions
// --------

// Development Note: Due to the difficulty in implementing the complex WordPress Customizer API,
// feature requests and fixes for Customizer *Live Preview* will receive a *very low* priority.
// On the other hand, options that are not saving properly however will receive a *high* priority.

// That said, after much ado and crazy hackiness that has to be seen to be believed,  most of the
// live preview options are working well at the time of writing this (v1.8.5) - fingers crossed.


// ref: Customizer Requirement Discussion:
// http://wptavern.com/wordpress-org-now-requires-theme-authors-to-use-the-customizer-to-build-theme-options
// An experiential point of view of the result of the WordPress.Org Customizer requirement:
// http://maddisondesigns.com/2015/06/lets-talk-about-the-wordpress-customizer/
// http://themekraft.com/what-it-means-for-us-and-others-to-follow-the-theme-review-guidelines/

// Customizer API Ref: https://developer.wordpress.org/themes/advanced-topics/customizer-api/
// https://make.wordpress.org/core/2014/10/27/toward-a-complete-javascript-api-for-the-customizer/
// http://www.titanframework.net/livepreview-parameter/
// http://wptheming.com/2012/07/options-framework-theme-customizer/

// TODO: Maybe implement separate action hooks for live and preview modes?
// https://wp-dreams.com/articles/2014/02/wodpress-theme-customizer-useful-custom-hooks/

// Note: Theme Customizer Boilerplate
// http://www.wpexplorer.com/theme-customizer-boilerplate/
// https://github.com/saas786/WordPress-Theme-Settings-Customizer-Boilerplate

// Note: Selective Refresh
// https://make.wordpress.org/core/2016/02/16/selective-refresh-in-the-customizer/


// ------------------
// Control References
// ------------------

// Default WP Customizer Controls
// ------------------------------
// checkbox, textarea, radio, select, page-dropdown, text, hidden
// also? number, range, url, tel, email, search, time, date, datetime, week

// Kirki Controls (/customizer/kirki/)
// --------------
// http://kirki.org and https://github.com/aristath/kirki
// https://github.com/aristath/kirki/wiki/Field-Structure
// https://github.com/aristath/kirki/wiki/Kirki_Color
// https://github.com/aristath/kirki-helpers
// - checkbox
// + code
// - color
// -- color-alpha
// - custom
// + dashicons
// + dimension
// - dropdown-pages
// X editor
// - image
// - multicheck
// + multicolor
// - number
// - palette
// - preset
// - radio
// -- radio-buttonset
// -- radio-image
// - repeater
// - select
// - slider
// - sortable
// - spacing
// - switch
// - text
// - textarea
// - toggle
// - typography
// + upload

// Hybrid Controls (/hybrid/customize/)
// ---------------
// Checkbox multiple: 	Hybrid_Customize_Control_Checkbox_Multiple
// Dropdown terms: 		Hybrid_Customize_Control_Dropdown_Terms
// Radio image: 		Hybrid_Customize_Control_Radio_Image
// - Layout: 			Hybrid_Customize_Control_Layout
// ref: http://justintadlock.com/archives/2015/06/04/customizer-radio-image-control
// alternatives: http://slicejack.com/customizer-api-custom-control/, http://pastebin.com/7TympE1s
// Palette: 			Hybrid_Customize_Control_Palette
// Select group: 		Hybrid_Customize_Control_Select_Group
// Select multiple: 	Hybrid_Customize_Control_Select_Multiple
// (setting) 			Hybrid_Customize_Setting_Array_Map
// (setting) 			Hybrid_Customize_Setting_Image_Data

// Titan Framework Controls
// ------------------------
// customizer controls are inside actual input classes. :-(

// Paulund Custom Controls (/customizer/customizer-controls/)
// -----------------------
// http://www.paulund.co.uk/custom-wordpress-controls
// Date picker:			Date_Picker_Custom_Control
// Layout picker:		Layout_Picker_Custom_Control
// Category dropdown:	Category_Dropdown_Custom_Control
// Google font dropdown:Google_Font_Dropdown_Custom_Control
// Menu dropdown:		Menu_Dropdown_Custom_Control
// Post dropdown:		Post_Dropdown_Custom_Control
// Post type dropdown:	Post_Type_Dropdown_Custom_Control
// Tags dropdown:		Tags_Dropdown_Custom_Control
// Taxonomy dropdown:	Taxonomy_Dropdown_Custom_Control
// User dropdown:		User_Dropdown_Custom_Control
// Text editor:			Text_Editor_Custom_Control
// Textarea: 			Textarea_Custom_Control

// Random Bits
// -----------
// using this could be cool, maybe for an advanced color picker:
// ref: http://themehybrid.com/board/topics/conditional-customizer-controls
// also: http://wordpress.stackexchange.com/questions/130467/multiple-inputs-in-a-customizer-control

// ------------------------
// Register Control Classes
// ------------------------

if (!function_exists('bioship_customizer_register_controls')) {
 function bioship_customizer_register_controls($wp_customize) {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE,func_get_args());}
	global $vthemestyledir, $vthemetemplatedir;

	// Add an Info Custom Control
	// --------------------------
	bioship_customizer_register_info_control();

	// maybe Load Hybrid Controls
	// --------------------------
	// TODO: register Hybrid control types via Kirki filter?
	// ?MAYBE? use spl_autoload_register for Hybrid control classes?

	// note: loading it this way may not work just yet?
	// ...this snippet is just for if Hybrid is turned off in options...
	if (!defined('HYBRID_CUSTOMIZE')) {
		$vhybridcustomize = $vthemetemplatedir.'includes'.DIRSEP.'hybrid3'.DIRSEP.'customize'.DIRSEP;
		define('HYBRID_CUSTOMIZE',$vhybridcustomize);
		if (!class_exists('Hybrid_Customize_Control_Checkbox_Multiple')) {
			$vcheckboxmultiple = HYBRID_CUSTOMIZE.'control-checkbox-multiple.php';
			include($vcheckboxmultiple);
		}
	}

	// Paulund Custom Controls
	// -----------------------
	// [currently not implemented]
	$vcustomcontrols = array(
		'date/date-picker' 				=> 'Date_Picker_Custom_Control',
		'layout/layout-picker' 			=> 'Layout_Picker_Custom_Control',
		'select/category-dropdown' 		=> 'Category_Dropdown_Custom_Control',
		'select/google-font-dropdown' 	=> 'Google_Font_Dropdown_Custom_Control',
		'select/menu-dropdown' 			=> 'Menu_Dropdown_Custom_Control',
		'select/post-dropdown' 			=> 'Post_Dropdown_Custom_Control',
		'select/post-type-dropdown' 	=> 'Post_Type_Dropdown_Custom_Control',
		'select/tags-dropdown' 			=> 'Tags_Dropdown_Custom_Control',
		'select/taxonomy-dropdown' 		=> 'Taxonomy_Dropdown_Custom_Control',
		'select/user-dropdown' 			=> 'User_Dropdown_Custom_Control',
		'text/textarea' 				=> 'Textarea_Custom_Control',
		'text/text-editor' 				=> 'Text_Editor_Custom_Control'
	);
	// TODO: maybe use spl_autoload_register here instead?
	// ...or register these control types via Kirki filter?
	// foreach ($vcustomcontrols as $vcontrolkey => $vcontrolclass) {
	//	$vcontrolfile = $vthemetemplatedir.'includes/customizer-controls/'.$vcontrolkey.'-custom-control.php';
	//	if (file_exists($vcontrolfile)) {include($vcontrolfile);}
	// }

	// Kirki Config URL Filter
	// -----------------------
	add_filter('kirki/config', 'bioship_customizer_kirki_url');
	if (!function_exists('bioship_customizer_kirki_url')) {
	 function bioship_customizer_kirki_url($vconfig) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
		$config['url_path'] = THEMEKIRKIURL; return $vconfig;
	 }
	}

	// Include Kirki Library
	// ---------------------
	// https://kirki.org/docs/advanced/integration.html
	// note: as we are conditionally loading Kirki inside customize_register,
	// - so that Kirki is not loaded unnecessarily outside the Customizer -
	// so we need to fire some init actions that have already missed out on...

	// ? TODO ? maybe conditionally load the Kirki library via an option/filter ?
	// ? version test ? apparently it requires PHP 5.2? (at least 5.12 due to spl_autoload_register)
	$vkirki = bioship_file_hierarchy('both', 'kirki.php', array('includes/kirki','kirki'));
	if (THEMEDEBUG) {echo "<!-- Kirki: ".$vkirki." -->";}

	// 1.8.5: fix to Kirki check for new file hierarchy syntax
	if (is_array($vkirki)) {
		$vkirkipath = str_replace('kirki.php', '', $vkirki['url']);
		define('THEMEKIRKIURL', $vkirkipath);
		include($vkirki['file']); // initialize Kirki
		// need to fire this right now, as we missed after_theme_setup hook..!
		if (function_exists('kirki_filtered_url')) {kirki_filtered_url();}

		// 1.8.5: but that was not enough, must manually override to fix script paths!
		Kirki::$url = THEMEKIRKIURL; define('THEMEKIRKI',true);
		if (THEMEDEBUG) {echo "<!-- Kirki Path: "; echo THEMEKIRKIURL; echo " -- ".Kirki::$url." -->";}

		// as we really aren't using the Code control, remove codemirror script to avoid bloat
		// note: suggest removal from default load, as is loaded in the Code control anyway?
		// ...this code not working...
		// add_action('wp_enqueue_scripts', 'bioship_customizer_dequeue_codemirror', 999);
		// function bioship_customizer_dequeue_codemirror() {wp_dequeue_script('codemirror');}

		// 1.9.5: use script loader tag filter to remove codemirror scripts
		if (!function_exists('bioship_customizer_remove_codemirror_scripts')) {
		 add_filter('script_loader_tag', 'bioship_customizer_remove_codemirror_scripts', 11, 2);
		 function bioship_customizer_remove_codemirror_scripts($vtag, $vhandle) {
			if (strstr($vtag, 'vendor/codemirror')) {return '';}
			return $vtag;
		 }
		}
	} else {
		// TODO: a more graceful way to fallback here for no Kirki?! :-/
		// (should be "okay" - if the Hybrid multicheck control works?)
		define('THEMEKIRKI',false);
	}

	// manually do the Kirki_Init
	// --------------------------
	// again fire these now, as we have missed wp_loaded also..!
	if (class_exists('Kirki_Init')) {
		// (modified copy of Kirki_Init::add_to_customizer)
		Kirki_Init::register_control_types(); // register them right now, not later
		Kirki_Init::fields_from_filters(); // yeah and whatever this does too
		// note: we are not using Kirki to add panels or sections
		add_action('customize_register', array('Kirki_Init','add_panels'), 97);
		add_action('customize_register', array('Kirki_Init','add_sections'), 98);
		// ...but we are definitely using the Kirki fields
		add_action('customize_register', array('Kirki_Init','add_fields'), 99);
		// 1.9.5: change of class name for Kirki 2.3.5
		if (class_exists('Kirki_Scripts_Loading')) {new Kirki_Scripts_Loading();}
		elseif (class_exists('Kirki_Customizer_Scripts_Loading')) {new Kirki_Customizer_Scripts_Loading();}
	}

	// Filter the Kirki Font Stacks
	// ----------------------------
	// note: as we are not using Kirki Typography Control, do not need this yet
	if (!function_exists('bioship_customizer_kirki_font_stacks')) {
	 add_filter('kirki/fonts/standard_fonts', 'bioship_customizer_kirki_font_stacks');
	 function bioship_customizer_kirki_font_stacks() {
		if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}
		$fonts = bioship_options_web_font_stacks(array());
		$fontstacks = array();
		foreach ($fonts as $fontstack => $display) {
			// format: array['fontkey'] = array('label'=>'font', 'stack'=>'stack'));
			// ? looks like fontkey should be the first 'font' in the stack for Kirki ?
			// 1.9.8: fix to fontstack and label variable typos
			$fontstacks[$fontstack] = array('label'=>$display, 'stack'=>$fontstack);
		}
	 	return $fontstacks;
	 }
	}

	// Filter the Kirki Google Fonts
	// -----------------------------
	// [unfinished] we are not using Kirki Typography Control, do not need this...
	if (!function_exists('bioship_customizer_kirki_google_fonts')) {
	 add_filter('kirki/fonts/google_fonts', 'bioship_customizer_kirki_google_fonts');
	 function bioship_customizer_kirki_google_fonts($vkirkifonts) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		$vfonts = bioship_options_title_fonts();
		$vgooglefonts = array();
		foreach ($vfonts as $vfont => $vdisplay) {
			// TODO: variants / subsets / categories?
			$vgooglefonts[$vfont] = array(
				'label'    => $vdisplay,
				'variants' => array(),
				'subsets'  => array(),
				'category' => array()
			);
		}
		return $vgooglefonts;
	 }
	}

	// Stylize the Customizer with Kirki
	// ---------------------------------
	// ref: https://kirki.org/docs/advanced/styling-the-customizer.html
	if (!function_exists('bioship_customizer_kirki_styling')) {
	 add_filter('kirki/config', 'bioship_customizer_kirki_styling');
	 function bioship_customizer_kirki_styling($vconfig) {
	 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

		// 1.9.9: cache logo value to prevent multiple hierarchy calls
		global $vcustomlogoimage;
		if (!isset($vcustomlogoimage)) {$vcustomlogoimage = bioship_file_hierarchy('url','theme-logo.png',array('','images','img'));}

		$vpreviewnotice = '<span class="preview-notice" style="float:right;max-width:40%;">';
		$vpreviewnotice .= sprintf( __( 'You are customizing %s', 'bioship' ), '<strong class="panel-title site-title">' . get_bloginfo( 'name' ) . '</strong>' );
		$vpreviewnotice .= '</span>';

	    $vconfig['description']  = apply_filters('options_customizer_description', $vpreviewnotice);
	    $vconfig['logo_image']   = apply_filters('options_customizer_logo_image', $vcustomlogoimage);
	    $vconfig['color_accent'] = apply_filters('options_customizer_color_accent', '#99BBDD');
	    $vconfig['color_back']   = apply_filters('options_customizer_color_back', '#E0E0EE');
	    $vconfig['width']        = apply_filters('options_customizer_panel_width', '20%');
	    return $vconfig;
	 }
	}

	// load Kirki I10n Filter
	// ----------------------
	// 1.8.5: added this filter
	// 1.9.9: load as filter as intended
	add_filter('kirki/bioship/l10n', 'bioship_customizer_i10n');
 }
}


// Add an Info Custom Control
// --------------------------
if (!function_exists('bioship_customizer_register_info_control')) {
 function bioship_customizer_register_info_control() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// outputs label and description for info/note option type
	// also used to echo expand/collapse links for Typography Controls
	// TODO: maybe use contextual show/hide controls for Typography?
	// ref: https://www.gavick.com/blog/contextual-controls-theme-customizer

	class Info_Custom_Control extends WP_Customize_Control {
		public function render_content() {
			echo '<label>';
			if ($this->label != '') {echo '<span class="customize-control-title customize-info-title">'.esc_html($this->label).'</span>';}
			if ($this->description == 'typography_controller') {
				$vid = str_replace('[helper]','',$this->id);
				$vpos = strpos($vid,'[') + 1;
				$vid = substr($vid, $vpos, strlen($vid));
				$vid = str_replace(']', '', $vid);
				echo '<span id="'.$vid.'-expand"><a href="javascript:void(0);" onclick="expandoptions(\''.$vid.'\');" style="text-decoration:none;">[+] Expand Typography Options</a></span>';
				echo '<span id="'.$vid.'-collapse" style="display:none;"><a href="javascript:void(0);" onclick="collapseoptions(\''.$vid.'\');" style="text-decoration:none;">[-] Collapse Typography Options</a></span>';
			}
			elseif ($this->description != '') {echo '<p class="description">'.$this->description.'</p>';}
			echo '</label>';
		}
	}

	// register this control type via Kirki filter (before initializing Kirki)
	if (!function_exists('bioship_add_control_types')) {
     add_filter('kirki/control_types', 'bioship_add_control_types');
     function bioship_add_control_types($vcontrols) {
     	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}
    	$vcontrols['info'] = 'Info_Custom_Control';
    	$vcontrols['note'] = 'Info_Custom_Control';
    	return $vcontrols;
     }
    }
 }
}

// ----------------------------
// Register Customizer Controls
// ----------------------------

if (!function_exists('bioship_customizer_load_control_options')) {
 function bioship_customizer_load_control_options($wp_customize) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings, $vthemename, $vthemeoptions;
	global $vtypocontrolids, $vcontrollerids;

	// 1.9.9: show only basic options only in customizer by default
	$voptionspage = 'basic';
	if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'advanced') ) {$voptionspage = 'advanced';}
	if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'all') ) {$voptionspage = 'all';}
	if (THEMEDEBUG) {echo "<!-- Options Page: ".$voptionspage." -->";}

	// Convert all options to Layer Options
	// ------------------------------------
	$vi = 0; $vj = 0; $vk = 0; $vl = 0; $vm = 0;
	foreach ($vthemeoptions as $voptionkey => $voptionvalues) {

		// 1.8.5: fix heading type (missing class) if using Options Framework
		$vlayers = array('skin','muscle','skeleton');
		if (isset($voptionvalues['id'])) {
			if (in_array($voptionvalues['id'],$vlayers)) {
				$voptionvalues['class'] = $voptionvalues['id'];
				$voptionvalues['id'] = $voptionvalues['name'];
			}
			if (THEMEDEBUG) {echo "<!-- ID: ".$voptionvalues['id']." - For Page: ".$vforpage." -->";}
		}

		// 1.9.9: check new customizer page display value
		if (!isset($voptionvalues['page'])) {
			$vforpage = 'both'; if (THEMEDEBUG) {echo '<!-- Missing page key for '.$voptionvalues['id'].' -->';}
		} else {$vforpage = $voptionvalues['page'];}

		// 1.9.9: filter whether to split options
		// TODO: add this to filters.php examples
		$vsplitoptions = apply_filters('options_customizer_split_options',true);

		// 1.9.9: match conditions for this customizer page
		if ( (!$vsplitoptions) || ($voptionspage == 'all') || ($vforpage == 'both')
		  || ( ($vforpage == 'basic') && ($voptionspage == 'basic') )
		  || ( ($vforpage == 'advanced') && ($voptionspage == 'advanced') ) ) {
			if (strstr($voptionvalues['class'],'skin')) {$vskinoptions[$vi] = $voptionvalues; $vi++;}
			elseif (strstr($voptionvalues['class'],'muscle')) {$vmuscleoptions[$vj] = $voptionvalues; $vj++;}
			elseif (strstr($voptionvalues['class'],'skeleton')) {$vskeletonoptions[$vk] = $voptionvalues; $vk++;}
			else {$vhiddenoptions[$vl] = $voptionvalues; $vl++;}
		}
	}

	if (THEMEDEBUG) {
		echo '<!-- Skin Options: '; print_r($vskinoptions); echo ' -->';
		echo '<!-- Muscle Options: '; print_r($vmuscleoptions); echo ' -->';
		echo '<!-- Skeleton Options: '; print_r($vskeletonoptions); echo ' -->';
		echo '<!-- Hidden Options: '; print_r($vhiddenoptions); echo ' -->';
	}

	// Settings Default Types
	// ----------------------
	$vdefaulttypes = array('checkbox', 'textarea', 'radio', 'select', 'page-dropdown', 'text', 'hidden');
	$vtypography = array('color', 'font-size', 'font-family', 'font-style');

	// Set Settings Prefix
	// -------------------
	if (THEMEOPT) {$vsettingsprefix = THEMEKEY.'_customize';}
	else {$vsettingsprefix = str_replace('_options','_customize',THEMEKEY);}

	// Create Copy of Theme Options
	// ----------------------------
	// ...set a dummy unserialized array for use by the Customizer only...
	delete_option($vsettingsprefix); add_option($vsettingsprefix,$vthemesettings);

	// extra Typography options for Titan
	// ----------------------------------
	if (!THEMEOPT) {
		$vtypography[] = 'font-weight'; $vtypography[] = 'line-height';
		$vtypography[] = 'letter-spacing'; $vtypography[] = 'text-transform';
		$vtypography[] = 'font-variant';
		// typography options from titan/includes/class-option-font.php
		$vtitantypography = array();
		$vtitantypography['websafefonts'] = bioship_options_web_font_stacks(array());
		$vtitantypography['googlefonts'] = bioship_options_title_fonts();
		$vtitantypography['allfonts'] = array_merge($vtitantypography['websafefonts'],$vtitantypography['googlefonts']);
		$vfontsizeoptions[] = 'inherit';
		// 1.8.5: doubled choice arrays to value-label pairs
		for ($i = 1; $i <= 150; $i++) {$vfontsizeoptions[$i.'px'] = $i.'px';}
		$vtitantypography['font-size'] = $vfontsizeoptions;
		$vtitantypography['font-weight'] = array('normal'=>'normal', 'bold'=>'bold', 'bolder'=>'bolder', 'lighter'=>'lighter', '100'=>'100', '200'=>'200', '300'=>'300', '400'=>'400', '500'=>'500', '600'=>'600', '700'=>'700', '800'=>'800', '900'=>'900');
		$vtitantypography['font-style'] = array('normal'=>'normal', 'italic'=>'italic');
		for ($i = .5; $i <= 3; $i += 0.1) {$vlineheightoptions[$i.'em'] = $i.'em';}
		$vtitantypography['line-height'] = $vlineheightoptions;
		for ($i = -20; $i <= 20; $i++) {$vletterspacingoptions[$i.'px'] = $i.'px';}

		// having these is probably overkill as rarely used...
		$vtitantypography['letter-spacing'] = $vletterspacingoptions;
		$vtitantypography['text-transform'] = array('none'=>'none', 'capitalize'=>'capitalize', 'uppercase'=>'uppercase', 'lowercase'=>'lowercase');
		$vtitantypography['font-variant'] = array('normal'=>'normal', 'small-caps'=>'small-caps');
		// ...the text shadow options certainly seem to be...
	}


	// Set Typo Sanitization Callbacks
	// -------------------------------
	// 1.8.5: added these sanitization fallbacks
	$vtyposanitize['color'] = 'bioship_fallback_sanitize_color';
	$vtyposanitize['font-size'] = 'bioship_fallback_sanitize_css_size';
	$vtyposanitize['font-family'] = 'bioship_fallback_sanitize_select';
	$vtyposanitize['font-style'] = 'bioship_fallback_sanitize_select';

	$vtyposanitize['font-weight'] = 'bioship_fallback_sanitize_css_size';
	$vtyposanitize['line-height'] = 'bioship_fallback_sanitize_css_size';
	$vtyposanitize['letter-spacing'] = 'bioship_fallback_sanitize_css_size';
	$vtyposanitize['text-transform'] = 'bioship_fallback_sanitize_select';
	$vtyposanitize['font-variant'] = 'bioship_fallback_sanitize_select';

	// Set Kirki basic config
	// ----------------------
	// probably not even need to do this but what the heck...
	// 1.8.5: added disable_output argument for Kirki update
	if (class_exists('Kirki')) {
		Kirki::add_config('bioship', array(
			'capability' => 'edit_theme_options', 'option_type' => 'option',
			'option_name' => $vsettingsprefix, 'disable_output' => true)
		);
	}

	// Customize Sections
	// ------------------
	$wp_customize->get_section('themes')->priority = 999; 	// shift to bottom
	$wp_customize->get_section('title_tagline')->title = __('Site Options', 'bioship'); // generalize
	$wp_customize->get_section('title_tagline')->priority = 10; // ok whatever now
	// set live preview transport to postMessage for title and tagline
	$wp_customize->get_setting('blogname')->transport = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';

	// 1.9.9: clear basic sections (from advanced options page only)
	if ($voptionspage == 'advanced') {
		// $wp_customize->remove_panel('widgets');
		$wp_customize->remove_section('title_tagline');
		$wp_customize->remove_panel('nav_menus');
		$wp_customize->remove_section('themes');
		// 2.0.8: only remove unused sections from advanced page (for wordpress.org compliance)
		$wp_customize->remove_section('colors');
		$wp_customize->remove_section('header_image');
		$wp_customize->remove_section('background_image');
		// 2.0.5: remove new custom CSS section
		$wp_customize->remove_section('custom_css');
	}

	// Customize Default Sections
	// --------------------------
	// neatness: move static_front_page controls to a title_tagline 'section'
	$wp_customize->get_control('show_on_front')->section = 'title_tagline';
	$wp_customize->get_control('page_on_front')->section = 'title_tagline';
	$wp_customize->get_control('page_for_posts')->section = 'title_tagline';
	$wp_customize->remove_section('static_front_page');		// remove section

	// [deprecated] maybe move 'Site Icon' from the title_tagline section to Icons section ?
	// $wp_customize->add_control( new WP_Customize_Site_Icon_Control( $wp_customize, 'site_icon', array(
	//	'label'       => __( 'Site Icon' ),
	//	'description' => __( 'The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least 512px wide and tall.' ),
	//	'section'     => 'title_tagline', // ?? somewhere else! ??
	//	'priority'    => 60,
	//	'height'      => 512,
	//	'width'       => 512,
	// ) ) );

	// Separator Section
	// [deprecated] ...this method is not working...
	// $wp_customize->add_section('section-separator',array('title'=>__('','bioship'), 'priority'=>170));
	// $w-p_c-u-s-t-o-m-i-z-e->add_setting('separator', array('type' => 'option', 'capability' => 'edit_theme_options'));
	// $vargs = array('type' => 'info', 'section'  => 'section-separator', 'settings' => 'separator');
	// $wp_customize->add_control(new Info_Custom_Control($wp_customize, 'separator-control', $vargs));

	// Loop through the Layer Options
	// ------------------------------
	for ($vi = 0; $vi < 3; $vi++) {

		// Set Data for this Layer Panel
		// -----------------------------
		$vtheseoptions = array();
		if ($vi == 0) {
			$vtheseoptions = $vskinoptions; $vpanelslug = 'skinoptions';
			$vargs = array('title'=>__('Skin Options','bioship'), 'priority'=>180);
			$vargs['description'] = __('All the Skin Layer Options','bioship');
		}
		elseif ($vi == 1) {
			$vtheseoptions = $vmuscleoptions; $vpanelslug = 'muscleoptions';
			$vargs = array('title'=>__('Muscle Options','bioship'), 'priority'=>190);
			$vargs['description'] = __('All the Muscle Layer Options','bioship');
		}
		elseif ($vi == 2) {
			$vtheseoptions = $vskeletonoptions; $vpanelslug = 'skeletonoptions';
			$vargs = array('title'=>__('Skeleton Options','bioship'), 'priority'=>200);
			$vargs['description'] = __('All the Skeleton Layer Options','bioship');
		}
		// do we need to handle the hidden options here?
		// looks like not as only changed values are saved

		// Add the Layer Panel
		// -------------------
		// Kirki::add_panel($vpanelslug, $vargs); // not used
		$wp_customize->add_panel($vpanelslug, $vargs);

		// Loop through Layer Options
		// --------------------------
		$vtypocontrols = 0; $vsectionpriority = 10;
		foreach ($vtheseoptions as $vthisoption) {

			if (THEMEDEBUG) {echo "<!-- OPTION TYPE: ".$vthisoption['type']."-->";}
			$vcontroltypes = array();

			// Add a Customizer Section for each Heading
			// -----------------------------------------
			if ($vthisoption['type'] == 'heading') {
				if (THEMEDEBUG) {echo "<!-- SECTION: "; print_r($vthisoption); echo "-->";}
				$vsectionslug = $vthemename.'_'.strtolower($vthisoption['name']);
				$vargs = array('panel'=>$vpanelslug, 'title'=>$vthisoption['name'], 'priority'=>$vsectionpriority);
				if (isset($vthisoption['desc'])) {$vargs['description'] = $vthisoption['desc'];}
				$wp_customize->add_section($vsectionslug, $vargs);
				// Kirki::add_section($vsectionslug, $vargs); // not used
				$vsectionpriority++; $vpriority = 10;
			}
			elseif ( ($vthisoption['type'] == 'typography') || ($vthisoption['type'] == 'font') ) {

				// Typography Controls
				// -------------------
				// - Kirki Library Typography Control ?
				// - Justin Tadlocks Customizer-Typography prototype ?
				// - Titan Framework Typography Control ?
				// - Google_Font_Dropdown_Custom_Control ?
				// ...going for individual controls with expand/collapse...

				// Add a simple info type 'setting' and 'control' as a Typography label header
				$vsettingid = $vsettingsprefix.'['.$vthisoption['id'].']';
				$vsettingargs = array('type' => 'option', 'capability' => 'edit_theme_options');
				$vcontrolargs = array('type' => 'info', 'priority' => $vpriority, 'section' => $vsectionslug,
				  'label' => $vthisoption['name'], 'description' => $vthisoption['desc'], 'setting' => $vsettingid
				);

				// TODO: maybe adapt a Kirki Typography control to Titan Typography?
				// not used as currently the settings do not quite match up correctly
				// if (class_exists('Kirki')) {
				//	$vcontrolargs['type'] = 'typography';
				//	$vargs = array_merge($vsettingargs,$vcontrolargs);
				//	Kirki::add_field('bioship', $vargs);
				// }
				// else {

					// Typography Expand/Collapse
					// --------------------------
					// this is a kind of dummy Control wrapper using our Info Control
					// to show/hide all the typography options for a particular element

					// Set subcontroller element list for javascript expand/collapse
					$vj = 0;
					$vtypocontrolids[$vtypocontrols] = $vthisoption['id'];
					foreach ($vtypography as $vtypooption) {
						$vtypocontrolid = 'customize-control-'.$vsettingsprefix.'-'.$vthisoption['id'].'-'.$vtypooption;
						$vcontrollerids[$vthisoption['id']][$vj] = $vtypocontrolid; $vj++;
					}
					$vcontrolargs['description'] = 'typography_controller';
					$vtypocontrols++;

					// add the Info Control to echo the expand/collapse javascript
					$vtypoid = $vsettingid.'[helper]'; // dummy option
					// 2.0.7: fix dummy sanitization callback for requirement check
					$wp_customize->add_setting($vtypoid, array(
						'type' => $vsettingargs['type'],
						'capability' => $vsettingargs['capability'],
						'sanitize_callback' => 'bioship_fallback_sanitize_unfiltered'
					) );
					$wp_customize->add_control(new Info_Custom_Control($wp_customize, $vtypoid, $vcontrolargs));
					$vpriority++;

					// Loop through the Typography options
					// -----------------------------------
					foreach ($vtypography as $vtypooption) {
						$vdefault = '';
						if (!THEMETITAN) {
							// set to Options Framework typography defaults
							if ($vtypooption == 'color') {$vdefault = $vthisoption['std']['color'];}
							elseif ($vtypooption == 'font-size') {$vdefault = $vthisoption['std']['size'];}
							elseif ($vtypooption == 'font-family') {$vdefault = $vthisoption['std']['font'];}
							elseif ($vtypooption == 'font-style') {$vdefault = $vthisoption['std']['style'];}

							$vchoices = array();
							// TODO: recheck the font size value consistency here?
							$vchoices['font-size'] = $vthisoption['options']['sizes'];
							$vchoices['font-family'] = $vthisoption['options']['faces'];
							$vchoices['font-style'] = $vthisoption['options']['styles'];
							// note: color option always assumed to be true here
						}
						else {
							if (isset($vthisoption['default'][$vtypooption])) {$vdefault = $vthisoption['default'][$vtypooption];}
							// set choices to the Titan typography options...
							$vchoices = $vtitantypography;
							// note: just assume value to be false if set
							if (isset($vthisoption['show_websafe_fonts'])) {$vfontoptions = $vtitantypography['googlefonts'];}
							elseif (isset($vthisoption['show_google_fonts'])) {$vfontoptions = $vtitantypography['websafefonts'];}
							else {$vfontoptions = $vtitantypography['allfonts'];}
							$vchoices['font-family'] = $vfontoptions;
						}

						// set default fallback values
						if ($vdefault == '') {
							if ($vtypooption == 'color') {$vdefault = '#999999';}
							if ($vtypooption == 'font-family') {
								foreach ($vchoices['font-family'] as $vfontkey => $vfontlabel) {
									$vdefault = $vfontkey; continue; // use first font as default
								}
							}
							if ($vtypooption == 'font-size') {$vdefault = '14px';}
							if ($vtypooption == 'font-weight') {$vdefault = 'normal';}
							if ($vtypooption == 'font-style') {$vdefault = 'normal';}
							if ($vtypooption == 'line-height') {$vdefault = '1.4em';}

							// these seem superfluous...
							if ($vtypooption == 'letter-spacing') {$vdefault = '0px';}
							if ($vtypooption == 'font-variant') {$vdefault = 'normal';}
							if ($vtypooption == 'text-transform') {$vdefault = 'none';}
						}

						// setup Setting and Control Arguments
						// -------------------------------------
						$vsettingid = $vsettingsprefix.'['.$vthisoption['id'].']['.$vtypooption.']';
						$vsettingargs = array('type' => 'option', 'capability' => 'edit_theme_options', 'default' => $vdefault, 'transport' => 'postMessage');
						$vlabel = str_replace('-',' ',$vtypooption);
						$vlabel = strtoupper(substr($vlabel,0,1)).substr($vlabel,1,strlen($vlabel));
						$vcontrolargs = array('type' => 'select', 'priority' => $vpriority, 'section' => $vsectionslug,
							'label' => $vlabel, 'description' => '', 'setting' => $vsettingid);

						// 1.8.5: set default typography sanitization callbacks
						$vsettingargs['sanitize_callback'] = $vtyposanitize[$vtypooption];

						// add this Typography Customizer Setting and Control
						// --------------------------------------------------
						// add Customizer Setting
						// 2.0.7: fix to for sanitization callback requirement check
						$wp_customize->add_setting($vsettingid, array(
							'type' => $vsettingargs['type'],
							'capability' => $vsettingargs['capability'],
							'default' => $vsettingargs['default'],
							'transport' => $vsettingargs['transport'],
							'sanitize_callback' => $vsettingargs['sanitize_callback']
						) );
						// $vvalue = $wp_customize->get_setting($vsettingid)->value(); // debug point

						// typography control styling
						// FIXME? this was the right styling - but it is just being completely ignored? :-/
						// $vcontrolargs['input_attrs'] = array('style' => 'float:right; margin-top:-30px;');

						// add Customizer Control
						if ($vtypooption == 'color') {
							$vcontrolargs['type'] = 'color'; // use color picker control here not select
							$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $vsettingid, $vcontrolargs));
						} else {
							$vcontrolargs['choices'] = $vchoices[$vtypooption];
							$wp_customize->add_control($vsettingid, $vcontrolargs);
						}
						$vpriority++;
					}

				// } // close unused Kirki Typography check
			}
			else {
				$vtype = $vthisoption['type'];

				// setup Customizer Setting for each Option
				// ----------------------------------------
				if ( ($vtype == 'info') || ($vtype == 'note') ) {$vsettingid = $vsettingsprefix."[info]";} // dummy value
				else {$vsettingid = $vsettingsprefix."[".$vthisoption['id']."]";}

				if (isset($vthisoption['default'])) {$vdefault = $vthisoption['default'];}
				elseif (isset($vthisoption['std'])) {$vdefault = $vthisoption['std'];}
				else {$vdefault = '';} // clear for loop if default is empty

				$vsettingargs = array('type' => 'option', 'capability' => 'edit_theme_options', 'default' => $vdefault, 'transport' => 'postMessage');

				// note: set to postMessage by default to prevent unnecessary page refreshes
				// (only layout options and script loads should really force a refresh -
				// these are defined in the options array by setting transport to refresh)

				if (isset($vthisoption['transport'])) {if ($vthisoption['transport'] == 'refresh') {$vsettingargs['transport'] = 'refresh';} }
				// this one not used here, but included for completeness anyway
				if (isset($vthisoption['theme_supports'])) {$vsettingargs['theme_supports'] = $vthisoption['theme_supports'];}

				// setup Customizer Control for each Option
				// ----------------------------------------
				// standard inputs: checkbox, radio, text, textarea, select
				// non-standard: info/note, color, multicheck

				$vcontrolargs = array('type' => $vtype, 'priority' => $vpriority, 'section' => $vsectionslug,
				  'label' => $vthisoption['name'], 'description' => $vthisoption['desc'], 'setting' => $vsettingid);

				// set options to choices for multiple choice input types
				if (isset($vthisoption['options'])) {$vcontrolargs['choices'] = $vthisoption['options'];}

				// TODO: maybe set input attributes for some default input types?
				// ! seems like 'style' attribute here does absolutely nothing anyway ! others untested yet...
			  	// eg... 'input_attrs' => array('class' => '', 'style' => '', 'placeholder' => '');
				if ($vtype == 'textarea') {$vthisoption['input_attrs'] = array('style' => 'height:300px;');}
				// ...allow for predefined option-specific override too...
				if (isset($vthisoption['input_attrs'])) {$vcontrolargs['input_attrs'] = $vthisoption['input_attrs'];}

				// note: postMessage and active_callback are mutually exclusive methods
				// because active_callback relies on using the refresh transport...
				// for the now not using active_callback argument anyway... so whatevs
				// ref: comments on http://ottopress.com/2015/whats-new-with-the-customizer/
				if (isset($vthisoption['active_callback'])) {$vcontrolargs['active_callback'] = $vthisoption['active_callback'];}

				// 1.8.5: allow for explicit sanitization callback override
				if (isset($vthisoption['sanitize_callback'])) {$vsetttingsargs['sanitize_callback'] = $vthisoption['sanitize_callback'];}

				if ( (class_exists('Kirki')) && ($vtype != 'info') && ($vtype != 'note') )  {

					// use Kirki Controls for the option fields
					// ----------------------------------------
					// allow an option to use a help icon instead of outputting full description
					if (isset($vthisoption['help'])) {if ($vthisoption['help']) {
						$vcontrolargs['help'] = $vcontrolargs['description']; unset($vcontrolargs['description']);
					} }
					// note Kirki extra options: output, js_vars, required?
					// but Kirki documentation is still a bit sketchy on their usage
					// 1.8.5: fix for 'type' conflict - as already set by Kirki config
					// 1.9.8: only unset if array index is already set
					if (isset($vsettingargs['type'])) {unset($vsettingargs['type']);}
					if (isset($vsettingargs['capability'])) {unset($vsettingargs['capability']);}
					$vargs = array_merge($vsettingargs,$vcontrolargs);
					// 1.9.5: do not use settingsprefix for Kirki 2.3.5 update
					if (class_exists('Kirki_Scripts_Loading')) {$vargs['setting'] = $vthisoption['id'];}
					// if (THEMEDEBUG) {echo "<!-- Kirki FIeld: "; print_r($vargs); echo " -->";}
					Kirki::add_field('bioship', $vargs);

				} else {

					// fallbacks to default Customizer Controls
					// ----------------------------------------
					// 1.8.5: only for when Kirki is not loaded
					// 2.0.7: fix to key setting typo (sanitization_callback)
					if (!isset($vsettingargs['sanitize_callback'])) {
						$vcallback = '';
						if ( ($vtype == 'info') || ($vtype == 'note')
						  || ($vtype == 'hidden') || ($vtype == 'code') ) {$vcallback = 'bioship_fallback_sanitize_unfiltered';}

						if ($vtype == 'select') {$vcallback = 'bioship_fallback_sanitize_select';}
						if ( ($vtype == 'radio') || ($vtype == 'images') || ($vtype == 'radio-images') ) {$vcallback = 'bioship_fallback_sanitize_radio';}
						if ($vtype == 'checkbox') {$vcallback = 'bioship_fallback_sanitize_checkbox';}
						if ($vtype == 'multicheck') {$vcallback = 'bioship_fallback_sanitize_multicheck';}

						if ( ($vtype == 'color') || ($vtype == 'colorpicker') || ($vtype == 'color-palette') ) {$vcallback = 'bioship_fallback_sanitize_color';}
						if ( ($vtype == 'rgba') || ($vtype == 'color-alpha') ) {$vcallback = 'bioship_fallback_sanitize_rgba';}
						if ( ($vtype == 'upload') || ($vtype == 'image') || ($vtype == 'audio') ) {$vcallback = 'bioship_fallback_sanitize_url';}

						if ($vtype == 'page-dropdown') {$vcallback = 'bioship_fallback_sanitize_pagedropdown';}
						if ($vtype == 'textarea') {$vcallback = 'bioship_fallback_sanitize_textarea';}
						if ($vtype == 'text') {$vcallback = 'bioship_fallback_sanitize_unfiltered';}

						if ( ($vcallback == '') && (THEMEDEBUG) ) {
							echo "<!-- WARNING: Missing Sanitization Callback for ".$vtype." Settings -->";
						}
						$vsettingargs['sanitize_callback'] = $vcallback;
					}

					// add the Setting
					// 2.0.7: fix to for sanitization callback requirement check
					$wp_customize->add_setting($vsettingid, array(
						'type' => $vsettingargs['type'],
						'capability' => $vsettingargs['capability'],
						'default' => $vsettingargs['default'],
						'sanitize_callback' => $vsettingargs['sanitize_callback']
					) );

					// add the Control
					if (!in_array($vtype,$vdefaulttypes)) {
						if ( ($vtype == 'info') || ($vtype == 'note') ) {
							// use our simple Info control class to output the label and description text
							$wp_customize->add_control(new Info_Custom_Control($wp_customize, $vsettingid, $vcontrolargs));
						}
						if ( ($vtype == 'color') || ($vtype == 'colorpicker') ) {
							$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $vsettingid, $vcontrolargs));
						}
						elseif ( ($vtype == 'upload') || ($vtype == 'image') ) {
							// TESTME: could test the various image control options here...
							// add/modify one that also allows for simply pasting an URL?!
							// note: one cool idea is to add a *context* to the uploaded images also:
							// ref: https://gist.github.com/eduardozulian/4739075
							if (class_exists('WP_Customize_Media_Control')) {
								if ($vtype == 'image') {$vargs['mime_type'] = 'image';} // note: WP 4.1+ ... use version_compare?
								$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $vsettingid, $vcontrolargs));
							} elseif ($vtype == 'upload') {
								$wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, $vsettingid, $vcontrolargs));
							} elseif ($vtype == 'image') {
								$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $vsettingid, $vcontrolargs));
							}
						}
						elseif ($vtype == 'audio') {
							// not used here anyways, but added just for good old reference
							// $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, $vsettingid, $vcontrolargs));
							$vargs['mime_type'] = 'audio'; // note: WP 4.1+ ... use version_compare?
							$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $vsettingid, $vcontrolargs));
						}
						elseif ($vtype == 'multicheck') {
							// this is the Multicheck Control from Hybrid Core...
							// since a multicheck control is not a default WordPress one - madness!
							// but not sure if this one is working properly as yet either..? :-(
							$wp_customize->add_control(new Hybrid_Customize_Control_Checkbox_Multiple($wp_customize, $vsettingid, $vcontrolargs));
						}
						elseif ($vtype == 'textarea') {
							// replacement textarea control, but should be fine either way
							if (class_exists('Textarea_Custom_Control')) {
								$wp_customize->add_control(new Textarea_Custom_Control($wp_customize, $vsettingid, $vcontrolargs));
							} else {$wp_customize->add_control($vsettingid, $vargs);}
						}
						// elseif ( ($vtype == 'images') || ($vtype == 'radio-image') ) {
							// note plural images, singular image type is for an image upload
							// TESTME: use the Hybrid radio-images Control here?
							// $wp_customize->add_control(new Hybrid_Customize_Control_Radio_Image($wp_customize, $vsettingid, $vcontrolargs));
						// }
					}
					else {
						// fallback to a standard control type...
						$wp_customize->add_control($vsettingid, $vcontrolargs);
					}
				}

				$vpriority++;
			}
		}
	}

	if (THEMEDEBUG) {
		echo '<!-- WP CUSTOMIZE: '; print_r($wp_customize); echo ' -->';
		echo '<!-- Control Types: '; print_r($vcontroltypes); echo ' -->';
		echo '<!-- Missing Sanitize: '; print_r($vmissingsanitize); echo ' -->';
	}

	// example: maybe Add Theme Upgrade Link?
	// well there is no premium version anyway
	// $vsettingid = 'customizer_link';
	// $vargs = array('type' => 'option', 'capability' => 'edit_theme_options', 'default' => '');
	// $w-p_-c-u-s-t-o-m-i-z-e->add_setting($vsettingid, $vargs);
	// $vlabel = ''; $vdescription = 'Upgrade Theme.';
	// $vargs = array('type' => 'info', 'priority' => '210', 'label' => $vlabel, 'description' => $vdescription, 'setting' => $vsettingid);
	// $wp_customize->add_control(new Info_Custom_Control($wp_customize, $vsettingid, $vargs));

	// well, that is just about enough of that nonsense!
 }
}

// Update the Customizer Description
// ---------------------------------
// there really should be a core filter for this text... TRAC?
if (!function_exists('bioship_customizer_text_script')) {
 function bioship_customizer_text_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// just some rogue panel separators and styling
	$vstyles = "#accordion-panel-skinoptions, #accordion-section-title_tagline {border-top: 20px solid #F0F0F0 !important;}
	#accordion-panel-skeletonoptions {border-bottom: 20px solid #F0F0F0 !important;}
	#customize-theme-controls .accordion-section-content {background-color: #E0E0EE !important;}
	#customize-info .customize-panel-description {background-color: #FDFDFF !important;}
	#customize-controls .customize-info {margin-bottom:0px !important;}
	#customize-info .customize-help-toggle {margin-top: 70px;}";

	// 1.9.9: enforce panel views for advanced options page (prevent auto-hiding glitch)
	if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'advanced') ) {
		$vstyles .= PHP_EOL."#accordion-panel-nav_menus {display: none !important;}
		#accordion-panel-skinoptions, #accordion-panel-skinoptions ul li,
		#accordion-panel-muscleoptions, #accordion-panel-muscleoptions ul li,
		#accordion-panel-skeletonoptions, #accordion-panel-skeletonoptions ul li {display:block !important;}";
	}

	// 1.8.5: added a style rule filter here
	$vstyles = apply_filters('options_customizer_extra_styles',$vstyles);
	echo "<style>".$vstyles."</style>".PHP_EOL;

	// this is the default Customizer title message
	// $vmessage = 'The Customizer allows you to preview changes to your site before publishing them. You can also navigate to different pages on your site to preview them.<br>';
	// let us change the wording just slightly to shorten
	$vmessage = __('The Customizer lets you preview live style changes before applying them. You can also navigate to preview other pages on your site.','bioship').'<br>';

	// now add a link to the theme options page - or for Titan Framework install
	$vtitan = bioship_file_hierarchy('file', 'titan-framework.php', array('include/titan','titan'));
	if ( (class_exists('TitanFramework')) || ($vtitan) ) {
		// 1.9.9: fixed URL, shortened Titan Framework link message
		$vthemesettingslink = admin_url('admin.php').'?page=bioship-options';
		$vcustommessage = '<br>'.__('Feeling restricted?','bioship').'<br>';
		$vcustommessage .= '<a href="'.$vthemesettingslink.'">'.__('Access All Options via Titan','bioship').'</a>.';
	}
	else {
		// generate Titan Framework install link (via TGMPA)
		// $vtitaninstall = admin_url('themes.php').'?page=tgmpa-install-plugins';
		// $vtitaninstall = wp_nonce_url( add_query_arg(array('plugin' => urlencode('titan-framework'),'tgmpa-install-plugin'), $vtitaninstall), 'tgmpa-install', 'tgmpa-nonce' );
		// 1.8.5: use direct install method via standalone admin function
		// 1.9.9: shortened Titan Framework install message
		$vtitaninstalllink = admin_url('themes.php').'?admin_install_titan_framework=yes';
		$vcustommessage = __('Feel restricted?','bioship').' <a href="'.$vtitaninstalllink.'">';
		$vcustommessage .= __('Install Titan Framework','bioship').'</a>.<br>';
		$vcustommessage .= __('To access All Options via Titan','bioship').'.';
	}
	$vcustomizermessage = $vmessage.$vcustommessage;
	$vcustomizermessage = apply_filters('options_customizer_description', $vcustomizermessage);
	// 2.0.5: maybe remove single quotes that would break javascript insert
	$vcustomizermessage = str_replace("'", "", $vcustomizermessage);

	// preview notice title section text
	// 2.0.7: added missing text domain
	$vextratext = '<span class="preview-notice" style="float:right; max-width:40%;">';
	$vextratext .= sprintf( __( 'You are customizing %s', 'bioship' ), '<strong class="panel-title site-title">' . get_bloginfo( 'name' ) . '</strong>' );
	$vextratext .= '</span>';

	// 1.9.9: filter whether splitting options
	$vsplitoptions = apply_filters('options_customizer_split_options', true);

	if ($vsplitoptions) {
		// 1.9.9: use this section to display option page links
		$voptionspage = 'basic';
		if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'advanced') ) {$voptionspage = 'advanced';}
		if ( (isset($_REQUEST['options'])) && ($_REQUEST['options'] == 'all') ) {$voptionspage = 'all';}
		if (isset($_REQUEST['return'])) {
			$vreturn = '&return='.urlencode($_REQUEST['return']); $vqreturn = '?return='.urlencode($_REQUEST['$vreturn']);
		} else {$vreturn = ''; $vqreturn = '';}
		$vcustommessage = '<b>'.__('Options','bioship').'</b>:<br>';
		if ($voptionspage == 'basic') {$vcustommessage .= '<b>'.__('General','bioship').'</b><br>';}
		else {$vcustommessage .= '<a href="customize.php'.$vqreturn.'">'.__('General','bioship').'</a><br>';}
		if ($voptionspage == 'advanced') {$vcustommessage .= '<b>'.__('Advanced','bioship').'</b><br>';}
		else {$vcustommessage .= '<a href="customize.php?options=advanced'.$vreturn.'">'.__('Advanced','bioship').'</a><br>';}
		if ($voptionspage == 'all') {$vcustommessage .= '<b>'.__('All','bioship').'</b><br>';}
		else {$vcustommessage .= '<a href="customize.php?options=all'.$vreturn.'">'.__('All','bioship').'</a><br>';}
		$vextratext = '<span class="preview-notice" style="float:right; max-width:45%; line-height:16pt;">';
		$vextratext .= $vcustommessage.'</span>';
	}

	$vextratext = apply_filters('options_customizer_titletext', $vextratext);
	// 2.0.5: maybe remove single quotes that would break javascript insert
	$vextratext = str_replace("'", "", $vextratext);

	// jQuery to update the customizer message
	echo "<script>jQuery(document).ready(function($) {
		$('#customize-info button.customize-help-toggle').click();
		$('#customize-info .customize-panel-description').html('".$vcustomizermessage."');";
		if ($vextratext != '') {echo "
			var extratext = '".$vextratext."'; $('#customize-info .accordion-section-title').append(extratext);";
		}
	echo "});</script>";

	// Customizer Sidebar Controls
	// ---------------------------
	// 1.8.5: added these sidebar display controls
	// change the sidebar size function (ref: http://stackoverflow.com/a/19873734/5240159)
	echo "<input type='hidden' id='customizersidebarwidth' value=''>";
	echo "<input type='hidden' id='customizersidebarheight' value=''>";
	echo "<input type='hidden' id='customizersidebarposition' value='left'>";
	echo "<script>function getactualsidebarcsssize() {
		clonedelement = jQuery('.wp-full-overlay-sidebar').clone().appendTo('body').wrap('<div id=\"tempelement\" style=\"display:none\"></div>');
		sidebarwidth = clonedelement.css('width'); document.getElementById('customizersidebarwidth').value = sidebarwidth;
		sidebarheight = clonedelement.css('height'); document.getElementById('customizersidebarheight').value = sidebarheight;
		jQuery('#tempelement').remove();
	}
	getactualsidebarcsssize(); sidebarwidth = document.getElementById('customizersidebarwidth').value;
	jQuery('.expanded .wp-full-overlay-footer').css('width',sidebarwidth);

	function customizersidebarsize(plusminus) {
		sidebarposition = document.getElementById('customizersidebarposition').value;
		if ( (sidebarposition == 'left') || (sidebarposition == 'right') ) {
			sidebarwidth = document.getElementById('customizersidebarwidth').value;
			if (sidebarwidth.indexOf('px') > -1) {widthsuffix = 'px'; newwidth = parseInt(sidebarwidth.replace('px','')); newamount = 64;}
			if (sidebarwidth.indexOf('%') > -1) {widthsuffix = '%'; newwidth = parseInt(sidebarwidth.replace('%','')); newamount = 2;}
			if (sidebarwidth.indexOf('em') > -1) {widthsuffix = 'em'; newwidth = parseInt(sidebarwidth.replace('em','')); newamount = 4;}
			if (plusminus == 'plus') {newwidth = newwidth + newamount;}
			if (plusminus == 'minus') {oldwidth = newwidth; newwidth = newwidth - newamount;}
			/* console.log(newwidth+'-'+newamount+'-'+widthsuffix); */
			jQuery('.wp-full-overlay-sidebar').css('width',newwidth+widthsuffix);
			setTimeout(function() {
				pixelwidth = jQuery('.wp-full-overlay-sidebar').width(); pixelwidth = parseInt(pixelwidth); console.log(pixelwidth);
				if (pixelwidth < 200) {jQuery('.wp-full-overlay-sidebar').css('width',oldwidth+widthsuffix); return;}
				if (pixelwidth < 260) {jQuery('#customizer-sidebar-controls').css({'top':'50px','left':'100px'});}
					else {jQuery('#customizer-sidebar-controls').css({'top':'0','left':'50px'});}
				if (pixelwidth < 300) {jQuery('.preview-notice').hide();} else {jQuery('.preview-notice').show();}
				if (sidebarposition == 'left') {jQuery('.wp-full-overlay.expanded').css('margin-left',newwidth+widthsuffix);}
				if (sidebarposition == 'right') {jQuery('.wp-full-overlay.expanded').css('margin-right',newwidth+widthsuffix);}
				jQuery('.expanded .wp-full-overlay-footer').css('width',newwidth+widthsuffix); getactualsidebarcsssize();
			}, 100);
		}
		if ( (sidebarposition == 'top') || (sidebarposition == 'bottom') ) {
			sidebarheight = document.getElementById('customizersidebarheight').value;
			if (sidebarheight.indexOf('px') > -1) {heightsuffix = 'px'; newwidth = parseInt(sidebarheight.replace('px','')); newamount = 64;}
			if (sidebarheight.indexOf('%') > -1) {heightsuffix = '%'; newwidth = parseInt(sidebarheight.replace('%','')); newamount = 2;}
			if (sidebarheight.indexOf('em') > -1) {heightsuffix = 'em'; newwidth = parseInt(sidebarheight.replace('em','')); newamount = 4;}
			if (plusminus == 'plus') {newheight = newheight + newamount;}
			if (plusminus == 'minus') {oldheight = newheight; newheight = newheight - newamount;}
			console.log(newheight+'-'+newamount+'-'+heightsuffix);

			/* TODO: top/bottom sidebar jQuery CSS rules */
		}
	}

	function customizersidebarposition(position) {
		sidebarwidth = document.getElementById('customizersidebarwidth').value;
		if (position == 'left') {
			jQuery('.wp-full-overlay-sidebar').css({'left':'0','right':'initial'});
			jQuery('.wp-full-overlay.expanded').css({'margin-left':sidebarwidth,'margin-right':'0'});
			jQuery('.expanded .wp-full-overlay-footer').css({'left':'0','right':'initial'});
			jQuery('.wp-core-ui .wp-full-overlay .collapse-sidebar').css({'right':'0','left':'10px'});
			document.getElementById('customizersidebarposition').value = 'left';
		}
		if (position == 'right') {
			jQuery('.wp-full-overlay-sidebar').css({'right':'0','left':'initial'});
			jQuery('.wp-full-overlay.expanded').css({'margin-left':'0','margin-right':sidebarwidth});
			jQuery('.expanded .wp-full-overlay-footer').css({'right':'0','left':'initial'});
			jQuery('.wp-core-ui .wp-full-overlay .collapse-sidebar').css({'left':'0','right':'10px'});
			document.getElementById('customizersidebarposition').value = 'right';
		}
	}</script>";

 	// TODO: complete top and bottom sidebar position jQuery CSS rules
 	// ...as something different would need to be done with the sections / panels
 	// sidebarheight = document.getElementById('customizersidebarheight').value;
	// if (sidebarheight == '') {sidebarheight = sidebarwidth / 2;}
	// if (position == 'top') {
	// 	jQuery('.wp-full-overlay.expanded').css('margin-left','0');
	// 	jQuery('.wp-full-overlay.expanded').css('margin-top',sidebarheight+'%');
	// 	jQuery('.wp-full-overlay-sidebar').css('width','100%');
	// 	jQuery('.wp-full-overlay-sidebar').css('height',sidebarheight+'%');
	// }
	// if (position == 'bottom') {
	// 	jQuery('.wp-full-overlay.expanded').css('margin-left','0');
	// 	jQuery('.wp-full-overlay.expanded').css('margin-bottom',sidebarheight+'%');
	// 	jQuery('.wp-full-overlay-sidebar').css('width','100%');
	// 	jQuery('.wp-full-overlay-sidebar').css('height',sidebarheight+'%');
	// }

	// add the sidebar size and position controls
	// left = &#9668; right = &#9658; down = &#9660; up = &#9650;
	echo "<script>sizecontrols = document.createElement('div'); sizecontrols.setAttribute('id','customizer-sidebar-size-controls');
	headeractions = document.getElementById('customize-header-actions'); headeractions.appendChild(sizecontrols);
	positioncontrols = document.createElement('div'); positioncontrols.setAttribute('id','customizer-sidebar-position-controls');
	customizeinfo = document.getElementById('customize-info'); customizeinfo.appendChild(positioncontrols);

	sizecontrolshtml = '<div id=\"customizer-sidebar-size-controls\" class=\"customizer-sidebar-controls\"><table><tr>';
	sizecontrolshtml += '<td><a href=\"javascript:void(0);\" id=\"sidebardecreaser\" onclick=\"customizersidebarsize(\'minus\');\">&minus;</a></td><td>&nbsp;</td>';
	sizecontrolshtml += '<td><a href=\"javascript:void(0);\" id=\"sidebarincreaser\" onclick=\"customizersidebarsize(\'plus\');\">+</a></td></tr></table></div>';
	sizecontrols.innerHTML = sizecontrolshtml;

	controlshtml = '<div id=\"customizer-sidebar-position-controls\" class=\"customizer-sidebar-controls\"><table id=\"customizer-sidebar-position-table\">';
	controlshtml += '<tr height=\"15\"><td width=\"15\"></td><td width=\"80\" align=\"center\"><a href=\"javascript:void(0);\" id=\"sidebartop\" class=\"sidebararrow\" style=\"display:none;\" onclick=\"customizersidebarposition(\'top\');\">&#9650;</a></td><td width=\"15\"></td></tr>';
	controlshtml += '<tr height=\"70\"><td width=\"15\" style=\"vertical-align:middle;\"><a href=\"javascript:void(0);\" id=\"sidebarleft\" class=\"sidebararrow\" onclick=\"customizersidebarposition(\'left\');\">&#9668;</a></td>';
	controlshtml += '<td width=\"80\"></td><td width=\"15\" style=\"vertical-align:middle;\"><a href=\"javascript:void(0);\" id=\"sidebarright\" class=\"sidebararrow\" onclick=\"customizersidebarposition(\'right\');\">&#9658;</a></td></tr>';
	controlshtml += '<tr height=\"15\"><td width=\"15\"></td><td width=\"80\" align=\"center\"><a href=\"javascript:void(0);\" id=\"sidebarbottom\" class=\"sidebararrow\" style=\"display:none;\" onclick=\"customizersidebarposition(\'bottom\');\">&#9660;</a></td><td width=\"15\"></td></tr>';
	controlshtml += '</table></div>';
	positioncontrols.innerHTML = controlshtml;
	</script>";
	// temp: top/bottom sidebar position arrows hidden until jQuery is complete

	// styles for the sidebar controls
	echo "<style>#customizer-sidebar-size-controls {position:absolute; top:0; left:30px;}
	#customizer-sidebar-position-controls {position:absolute; top:0; left:0;}
	#customizer-sidebar-position-table {width:120px; height:100px; margin-top:-7px; margin-left:-5px;}
	.customizer-sidebar-updown-arrows {font-size:12pt; line-height:18px;}
	.customizer-sidebar-controls, #sidebardecreaser, #sidebarincreaser {font-size:14pt; font-weight:bold; float:left; display:inline-block;}
	#sidebarleft, #sidebarright, #sidebartop, #sidebarbottom, #sidebardecreaser, #sidebarincreaser {text-decoration:none;}
	</style>";

 }
}


// Scripts for the Info Customizer Control
// ---------------------------------------
if (!function_exists('bioship_customizer_info_script')) {
 function bioship_customizer_info_script() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	global $vtypocontrolids, $vcontrollerids;
 	if ( (!is_array($vtypocontrolids)) || (!is_array($vcontrollerids)) ) {return;}

	// for the Typography expand/collapse javascript
	// TODO: maybe animate these expand/collapse functions?
	echo "<script>function expandoptions(divid) {
		document.getElementById(divid+'-expand').style.display = 'none';
		document.getElementById(divid+'-collapse').style.display = '';
		var controlids = controllerids[divid].split(',');
		for (i in controlids) {
			if (document.getElementById(controlids[i])) {document.getElementById(controlids[i]).style.display = '';}
		}
	}
    function collapseoptions(divid) {
		document.getElementById(divid+'-collapse').style.display = 'none';
		document.getElementById(divid+'-expand').style.display = '';
		var controlids = controllerids[divid].split(',');
		for (i in controlids) {
			if (document.getElementById(controlids[i])) {document.getElementById(controlids[i]).style.display = 'none';}
		}
    }".PHP_EOL;

	// echo the typo controller id arrays to javascript
	$vj = 0; echo "var typocontrols = new Array(); var controllerids = new Array;".PHP_EOL;
	foreach ($vtypocontrolids as $vtypocontrolid) {
		echo "typocontrols[".$vj."] = '".$vtypocontrolid."';".PHP_EOL;
		echo "controllerids['".$vtypocontrolid."'] = '";
		$vk = 0;
		foreach ($vcontrollerids[$vtypocontrolid] as $vcontrollerid) {
			if ($vk > 0) {echo ",";}
			echo $vcontrollerid; $vk++;
		}
		echo "';".PHP_EOL;
		$vj++;
	}

    // hide all the typography subcontroller options by default
    echo "jQuery(document).ready(function($) {setTimeout(hidetyposubcontrols,5000);});".PHP_EOL;
    echo "function hidetyposubcontrols() {".PHP_EOL;
    echo "var controlid; var controlids = new Array();
    for (i in typocontrols) {
    	controlid = typocontrols[i]; controlids = controllerids[controlid].split(',');
    	for (i in controlids) {
    		if (document.getElementById(controlids[i])) {document.getElementById(controlids[i]).style.display = 'none';}
    	}
    } }</script>";
 }
}

// Update Serialized Option
// ------------------------
// we need to save back to the correct option for Titan (serialized)...
// as we created a temporary unserialized option for the Customizer

if (!function_exists('bioship_customizer_save_serialized')) {
 // if (!THEMEOPT) {
 add_action('customize_save_after', 'bioship_customizer_save_serialized');
 // }
 function bioship_customizer_save_serialized() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	global $vthemesettings;

	if (THEMEOPT) {$vpreviewkey = THEMEKEY.'_customize';}
	else {$vpreviewkey = str_replace('_options','_customize',THEMEKEY);}

 	$vupdatedoptions = maybe_unserialize(get_option($vpreviewkey));
 	// 2.0.5: update theme options savedtime value
 	$vupdatedoptions['savetime'] = time();

 	if (!$vupdateoptions != '') {
 		if (is_array($vupdatedoptions)) {

			// pass new options through theme options standardization fix
			$vconvertedoptions = bioship_titan_theme_options($vupdatedoptions);

 			// ...debug: write new options to a file...
 			// if (THEMEDEBUG) {
 				ob_start();
 				echo "Updated Options: "; print_r($vupdatedoptions); echo PHP_EOL.PHP_EOL;
 				echo "Converted Options: "; print_r($vconvertedoptions); echo PHP_EOL.PHP_EOL;
 				$vdata = ob_get_contents(); ob_end_clean();
 				$vdebugfile = 'customizer-options.txt';
 				bioship_write_debug_file($vdebugfile,$vdata);
 			// }

 			// serialize and write back to the actual option..!
 			// CHECKME: serial updateoptions or convertedoptions?!
 			$vserializedoptions = serialize($vupdatedoptions);
 			update_option(THEMEKEY, $vserializedoptions);
 			delete_option($vpreviewkey);
 		}
 	}
 }
}

// CSS Callbacks for Customizer Live Preview Javascript
// ----------------------------------------------------
// ref: http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
// load via the customize preview init hook, but put scripts in the footer...
// also this: https://github.com/aristath/kirki/wiki/Automating-CSS-output
// and this: https://github.com/aristath/kirki/wiki/Automating-postMessage-scripts

if (!function_exists('bioship_customizer_preview')) {
 function bioship_customizer_preview() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings, $vthemename, $vthemeoptions, $vcsscachebust, $vthemedirs;
	// echo "<!-- THEME: ".$vthemename." - KEY: ".THEMEKEY." -->";
	$vthemesettings = maybe_unserialize(get_option(THEMEKEY));
	// echo "<!-- OPTIONS: "; print_r($vthemesettings); echo " -->";

	$vcssmode = $vthemesettings['themecssmode']; // echo "<!-- CSS Mode: ".$vthemesettings['themecssmode']." -->";
	if ($vcssmode == 'adminajax') {$vskinurl = admin_url('admin-ajax.php').'?action=skin_dynamic_css';}
	else {$vskinurl = bioship_file_hierarchy('url', 'skin.php', $vthemedirs['core']);}
	// 2.0.5: add querystring arguments to skin URL early
	$vskinurl = add_query_arg('ver', $vcsscachebust, $vskinurl);
	$vskinurl = add_query_arg('livepreview', 'yes', $vskinurl);

	$vtypography = array('color', 'font-size', 'font-family', 'font-style');
	if (THEMETITAN) {
		$vthemename = $vthemename.'_customize';
		$vtypography[] = 'font-weight'; $vtypography[] = 'line-height';
		$vtypography[] = 'letter-spacing'; $vtypography[] = 'text-transform';
		$vtypography[] = 'font-variant';
	}

	// start jQuery customizer live preview functions
	// 1.8.5: added footer credits live preview
	echo "<script type='text/javascript'>( function(\$) {
		wp.customize('blogname', function(value) {	value.bind(function(to) {\$('#site-title-text a').html(to);}); });
		wp.customize('blogdescription', function(value) { value.bind(function(to) {\$('#site-description .site-desc').html(to);}); });
		wp.customize('".$vthemename."[sitecredits]', function(value) { value.bind(function(to) {if (to === '0') {to = '';} \$('#footercredits').html(to);}); });
		var buttontop = ''; var buttonbottom = '';
    ";

	// note: helpful function reference for adding hover events...
 	//	function setPreviewHover(obj, mouseenter, mouseleave) {
	//		obj.data('_mouseenter', mouseenter); obj.data('_mouseleave', mouseleave);
	//		obj.hover(obj.data('_mouseenter'), obj.data('_mouseleave'));
	//	}

	$vtypojs = '';
	foreach ($vthemeoptions as $voption) {

		// 1.8.5: send dynamic CSS to header/footer or skin.php
		if ( (isset($voption['id'])) && ($voption['id'] == 'dynamiccustomcss') ) {
			$vsettingid = $vthemename.'['.$voption['id'].']';
			echo "wp.customize('".$vsettingid."',function(value) {
				value.bind(function(to) {
					/* console.log(to); */
					var skinhref = '".$vskinurl."';
					if (document.getElementById('skin-css')) {newhref = \$('#skin-css').href;
						if (newhref != null) {if (newhref.indexOf('livepreview=yes') == -1) {\$('#skin-css').href = newhref+'&livepreview=yes';} }
					} else {
						newstylesheet = document.createElement('link'); newstylesheet.setAttribute('rel','stylesheet');
						newstylesheet.setAttribute('id','skin-css'); newstylesheet.href = skinhref;
						document.getElementsByTagName('head')[0].appendChild(newstylesheet);
					}
					\$('style').each(function() {thishref = \$(this).attr('data-href');
						if (thishref != null) {
							if (thishref.indexOf('skin.php') > -1) {\$(this).remove();}
							if (thishref.indexOf('action=skin_dynamic_css') > -1) {\$(this).remove();}
						}
					});
					if (document.getElementById('dynamic-styles')) {\$('#dynamic-styles').remove();}
					newstylesheet = document.createElement('style'); newstylesheet.setAttribute('id','dynamic-styles');
					newstylesheet.textContent = to; document.getElementsByTagName('head')[0].appendChild(newstylesheet);
				});
			});".PHP_EOL;
		}

		// TESTME: grid option change postMessage options...
		// TODO: add new grid URL arguments to querystring?
		if (isset($voption['id'])) {
			$vid = $voption['id'];
			// refresh only : layout, gridcolumns, content_width
			// postMessage : breakpoints, gridcompatibility, contentpadding
			if ( ($vid == 'breakpoints') || ($vid == 'gridcompatibility') || ($vid == 'content_width') ) {
				$vsettingid = $vthemename.'['.$voption['id'].']';
				echo "wp.customize('".$vsettingid."',function(value) {
					value.bind(function(to) {
						if (document.getElementById('grid-url')) {
							gridhref = \$('#grid-url').href; \$('#grid-css').remove();
							if (gridhref.indexOf('livepreview=yes') == -1) {gridhref += '&livepreview=yes';}
							newstylesheet = document.createElement('style'); newstylesheet.setAttribute('id','grid-css');
							newstylesheet.textContent = '@import('+gridhref+');';
							document.getElementsByTagName('head')[0].appendChild(newstylesheet);
						}
					});
				});".PHP_EOL;
			}
		}

		// other CSS property rules...
		if ( (isset($voption['csselement'])) && (isset($voption['cssproperty'])) ) {
			$vsettingid = $vthemename.'['.$voption['id'].']';
			// typography multiple CSS values
			// note: stored and re-inserted shorted...
			if ($voption['cssproperty'] == 'typography') {
				foreach ($vtypography as $vtypooption) {
					$vtyposetting = $vsettingid.'['.$vtypooption.']';
					$vtypojs .= "wp.customize('".$vtyposetting."',function(value) {
						value.bind(function(to) {
							\$('".$voption['csselement']."').css('".$vtypooption."',to);
							/* console.log('".$voption['csselement']." -- ".$vtypooption." -- '+to); */
						});
					});".PHP_EOL;
				}
			}
			elseif ($voption['csselement'] == '#header h1#site-title-text a,#site-description .site-desc') {
				// 1.8.5: text header hide/show display values
				echo "wp.customize('".$vsettingid."',function(value) {
					value.bind(function(to) {
						if (to.indexOf('sitetitle') > -1) {\$('#header h1#site-title-text a').fadeIn();}
													 else {\$('#header h1#site-title-text a').fadeOut();}
						if (to.indexOf('sitedescription') > -1) {\$('#site-description .site-desc').fadeIn();}
													 else {\$('#site-description .site-desc').fadeOut();}

					});
				});".PHP_EOL;
			}
			elseif ($voption['csselement'] == '#site-logo') {
				// 1.8.5: update logo and show/hide image (and/or text) depending on condition
				echo "wp.customize('".$vsettingid."',function(value) {
					value.bind(function(to) {
						\$('#site-logo .logo-image').attr('src',to);
						if (to == '') {\$('#site-logo .site-logo-image').hide(); /* \$('#site-logo .site-logo-text').show(); */}
						else {/* \$('#site-logo .site-logo-text').hide();*/ \$('#site-logo .site-logo-image').show();}
						/* console.log('".$voption['csselement']." img -- ".$voption['cssproperty']." -- '+to); */
					});
				});".PHP_EOL;
			}
			elseif ( (strstr($voption['csselement'],'body button')) && (strstr($voption['cssproperty'],'background')) ) {
				// 1.8.5: handle button gradient changes
				if (!strstr($voption['cssproperty'],':hover')) {
					if ($voption['cssproperty'] == 'backgroundtop') {$vtop = 'to'; $vbottom = "'".$vthemesettings['button_bgcolor_bottom']."'";}
					if ($voption['cssproperty'] == 'backgroundbottom') {$vbottom = 'to'; $vtop = "'".$vthemesettings['button_bgcolor_top']."'";}

					echo "wp.customize('".$vsettingid."',function(value) {
						value.bind(function(to) {
							buttons = \$('".$voption['csselement']."'); btntop = ".$vtop."; btnbot = ".$vbottom.";
							if (btntop == to) {buttontop = btntop; if (buttonbottom != '') {btnbot = buttonbottom;} else {buttonbottom = btnbot;} }
							if (btnbot == to) {buttonbottom = btnbot; if (buttontop != '') {btntop = buttontop;} else {buttontop = btntop;} }
							buttons.css({'background':btntop, 'background-color':btntop});
							buttons.css('background','-webkit-gradient(linear, left top, left bottom, color-stop(0%, '+btntop+'), color-stop(100%, '+btnbot+'))');
							buttons.css('background','-webkit-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%)');
							buttons.css('background','-o-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%)');
							buttons.css('background','-ms-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%)');
							buttons.css('background','-moz-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%)');
							buttons.css('background','linear-gradient(top bottom, '+btntop+' 0%, '+btnbot+' 100%)');
							buttons.css('-pie-background','linear-gradient(top, '+btntop+', '+btnbot+')');
							/* console.log('".$voption['csselement']." -- '+btntop+' -- '+btnbot); */
						});
					});".PHP_EOL;
				}

				// 1.8.5: hover button gradients preview...
				if (strstr($voption['cssproperty'],':hover')) {

					if ($voption['cssproperty'] == 'backgroundtop:hover') {$vtop = 'to'; $vbottom = "'".$vthemesettings['button_hoverbg_bottom']."'";}
					if ($voption['cssproperty'] == 'backgroundbottom:hover') {$vbottom = 'to'; $vtop = "'".$vthemesettings['button_hoverbg_top']."'";}

					echo "wp.customize('".$vsettingid."',function(value) {
						value.bind(function(to) {
							console.log(to);
							btntop = ".$vtop."; btnbot = ".$vbottom.";
							if (btntop == to) {buttontop = btntop; if (buttonbottom != '') {btnbot = buttonbottom;} else {buttonbottom = btnbot;} }
							if (btnbot == to) {buttonbottom = btnbot; if (buttontop != '') {btntop = buttontop;} else {buttontop = btntop;} }
							hoverclass = '.buttonhoverpreview:hover {';
							hoverclass += 'background: '+btntop+' !important; background-color: '+btntop+' !important;';
							hoverclass += 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, '+btntop+'), color-stop(100%, '+btnbot+')) !important;';
							hoverclass += 'background: -webkit-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%) !important;';
							hoverclass += 'background: -o-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%) !important;';
							hoverclass += 'background: -ms-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%) !important;';
							hoverclass += 'background: moz-linear-gradient(top, '+btntop+' 0%, '+btnbot+' 100%) !important;';
							hoverclass += 'background: linear-gradient(top bottom, '+btntop+' 0%, '+btnbot+' 100%) !important;';
							hoverclass += '-pie-background: linear-gradient(top, '+btntop+', '+btnbot+') !important;';
							hoverclass += '}';

							if (document.getElementById('hover-buttons')) {\$('#hover-buttons').remove();}
							newstylesheet = document.createElement('style'); newstylesheet.setAttribute('id','hover-buttons');
							newstylesheet.textContent = hoverclass; document.getElementsByTagName('head')[0].appendChild(newstylesheet);
							\$('".$voption['csselement']."').each(function() {\$(this).addClass('buttonhoverpreview');});
						});
					});".PHP_EOL;
				}
			}
			elseif (strstr($voption['csselement'],':hover')) {
				// 1.8.5: handle hover elements (ie. links)
				$voption['csselement'] = str_replace(':hover','',$voption['csselement']);
				echo "wp.customize('".$vsettingid."',function(value) {
					value.bind(function(to) {
						from = \$('".$voption['csselement']."').css('".$voption['cssproperty']."');
						\$('".$voption['csselement']."').hover(
							function() {\$(this).css('".$voption['cssproperty']."',to);},
							function() {\$(this).css('".$voption['cssproperty']."',from)} );
						/* console.log('(hover) ".$voption['csselement']." -- ".$voption['cssproperty']." -- '+to); */
					});
				});".PHP_EOL;
			} else {
				// any other singular CSS rule value
				// 1.8.5: fix for background-image CSS property preview
				echo "wp.customize('".$vsettingid."',function(value) {
					value.bind(function(to) {";
					if ($voption['cssproperty'] == 'background-image') {echo "to = 'url('+to+')';";}
						echo "\$('".$voption['csselement']."').css('".$voption['cssproperty']."',to);
						/* console.log('".$voption['csselement']." -- ".$voption['cssproperty']." -- '+to); */
					});
				});".PHP_EOL;
			}
		}
	}

	echo $vtypojs; // insert all typography javascript last (just to be neater)...
	echo "} )(jQuery)</script>";
	// end jQuery live preview functions
 }
}

// Default Sanitization Fallbacks
// ------------------------------
// (most of these are just pared down/modified from Kirki sanitization)
// 1.8.5: added these fallbacks for if Kirki is not loaded
// 2.0.5: added function_exists wrappers (for possible fix overrides)
if (!function_exists('bioship_fallback_sanitize_unfiltered')) {
	function bioship_fallback_sanitize_unfiltered($value) {return $vvalue;}
}
if (!function_exists('bioship_fallback_sanitize_radio')) {
	function bioship_fallback_sanitize_radio($value) {return esc_attr($value);}
}
if (!function_exists('bioship_fallback_sanitize_textarea')) {
	function bioship_fallback_sanitize_textarea($value) {return esc_textarea($value);}
}
if (!function_exists('bioship_fallback_sanitize_url')) {
	function bioship_fallback_sanitize_url($value) {return esc_raw_url($value);}
}
if (!function_exists('bioship_fallback_sanitize_number')) {
	function bioship_fallback_sanitize_number($value) {
		return ( is_numeric( $value ) ) ? $value : intval( $value );
	}
}
if (!function_exists('bioship_fallback_sanitize_serialized')) {
	function bioship_fallback_sanitize_serialized($value) {
		if ( is_serialized( $value ) ) { return $value; }
		else { return serialize( $value ); }
	}
}
if (!function_exists('bioship_fallback_sanitize_select')) {
	function bioship_fallback_sanitize_select($value) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $subvalue ) {
				$value[ $key ] = esc_attr( $subvalue );
			}
			return $value;
		}
		return esc_attr( $value );
	}
}
if (!function_exists('bioship_fallback_sanitize_checkbox')) {
	function bioship_fallback_sanitize_checkbox($checked) {
		return ( ( isset( $checked ) && ( true == $checked || 'on' == $checked ) ) ? true : false );
	}
}
if (!function_exists('bioship_fallback_sanitize_multicheck')) {
	function bioship_fallback_sanitize_multicheck($values) {
		$multi_values = ( ! is_array( $values ) ) ? explode( ',', $values ) : $values;
		return ( ! empty( $multi_values ) ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	}
}
if (!function_exists('bioship_fallback_sanitize_pagedropdown')) {
	function bioship_fallback_sanitize_pagedropdown($page_id, $setting) {
		$page_id = absint( $page_id );
		return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}
}
if (!function_exists('bioship_fallback_sanitize_css_size')) {
	function bioship_fallback_sanitize_css_size($value) {
		$value = trim( $value );
		if ( 'round' === $value ) {	$value = '50%';	}
		if ( '' === $value ) { return ''; }
		if ( 'auto' === $value ) { return 'auto'; }
		if ( ! preg_match( '#[0-9]#' , $value ) ) {	return ''; }
		if ( false !== strpos( $value, 'calc(' ) ) { return $value; }

		$raw_value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$unit_used = '';
		$units = array( 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' );
		foreach ( $units as $unit ) {
			if ( false !== strpos( $value, $unit ) ) { $unit_used = $unit; }
		}
		if ( 'em' === $unit_used && false !== strpos( $value, 'rem' ) ) { $unit_used = 'rem'; }
		return $raw_value . $unit_used;
	}
}
if (!function_exists('bioship_fallback_sanitize_color')) {
	function bioship_fallback_sanitize_color($value) {
		if ( '' === $value ) {return '';}
		if ( is_string( $value ) && 'transparent' === trim( $value ) ) {return 'transparent';}
		if ( false === strpos( $value, 'rgba' ) ) { return bioship_fallback_sanitize_hex( $value ); }
		else {return bioship_fallback_sanitize_rgba( $value );}
	}
}
if (!function_exists('bioship_fallback_sanitize_rgba')) {
	function bioship_fallback_sanitize_rgba($value) {
		if ( false === strpos( $value, 'rgba' ) ) { return bioship_fallback_sanitize_color ( $value ); }
		$value = str_replace( ' ', '', $value );
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}
if (!function_exists('bioship_fallback_sanitize_hex')) {
	function bioship_fallback_sanitize_hex($color) {
		$color = trim( $color );
		$color = str_replace( '#', '', $color );
		if ( 3 == strlen( $color ) ) {
			$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
		}
		$substr = array();
		for ( $i = 0; $i <= 5; $i++ ) {
			$default = ( 0 == $i ) ? 'F' : ( $substr[ $i - 1 ] );
			$substr[ $i ] = substr( $color, $i, 1 );
			$substr[ $i ] = ( false === $substr[ $i ] || ! ctype_xdigit( $substr[ $i ] ) ) ? $default : $substr[ $i ];
		}
		$hex = implode( '', $substr );
		return '#' . $hex;
	}
}

// Translate Kirki Labels
// ----------------------
// 1.8.5: added this filter
// 1.9.8: fixed missing quotes on text domain
// 1.9.9: use as a filter function directly
if (!function_exists('bioship_customizer_i10n')) {
 function bioship_customizer_i10n($l10n) {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__,func_get_args());}

	$l10n['background-color']      = esc_attr__( 'Background Color', 'bioship' );
	$l10n['background-image']      = esc_attr__( 'Background Image', 'bioship' );
	$l10n['no-repeat']             = esc_attr__( 'No Repeat', 'bioship' );
	$l10n['repeat-all']            = esc_attr__( 'Repeat All', 'bioship' );
	$l10n['repeat-x']              = esc_attr__( 'Repeat Horizontally', 'bioship' );
	$l10n['repeat-y']              = esc_attr__( 'Repeat Vertically', 'bioship' );
	$l10n['inherit']               = esc_attr__( 'Inherit', 'bioship' );
	$l10n['background-repeat']     = esc_attr__( 'Background Repeat', 'bioship' );
	$l10n['cover']                 = esc_attr__( 'Cover', 'bioship' );
	$l10n['contain']               = esc_attr__( 'Contain', 'bioship' );
	$l10n['background-size']       = esc_attr__( 'Background Size', 'bioship' );
	$l10n['fixed']                 = esc_attr__( 'Fixed', 'bioship' );
	$l10n['scroll']                = esc_attr__( 'Scroll', 'bioship' );
	$l10n['background-attachment'] = esc_attr__( 'Background Attachment', 'bioship' );
	$l10n['left-top']              = esc_attr__( 'Left Top', 'bioship' );
	$l10n['left-center']           = esc_attr__( 'Left Center', 'bioship' );
	$l10n['left-bottom']           = esc_attr__( 'Left Bottom', 'bioship' );
	$l10n['right-top']             = esc_attr__( 'Right Top', 'bioship' );
	$l10n['right-center']          = esc_attr__( 'Right Center', 'bioship' );
	$l10n['right-bottom']          = esc_attr__( 'Right Bottom', 'bioship' );
	$l10n['center-top']            = esc_attr__( 'Center Top', 'bioship' );
	$l10n['center-center']         = esc_attr__( 'Center Center', 'bioship' );
	$l10n['center-bottom']         = esc_attr__( 'Center Bottom', 'bioship' );
	$l10n['background-position']   = esc_attr__( 'Background Position', 'bioship' );
	$l10n['background-opacity']    = esc_attr__( 'Background Opacity', 'bioship' );
	$l10n['on']                    = esc_attr__( 'ON', 'bioship' );
	$l10n['off']                   = esc_attr__( 'OFF', 'bioship' );
	$l10n['all']                   = esc_attr__( 'All', 'bioship' );
	$l10n['cyrillic']              = esc_attr__( 'Cyrillic', 'bioship' );
	$l10n['cyrillic-ext']          = esc_attr__( 'Cyrillic Extended', 'bioship' );
	$l10n['devanagari']            = esc_attr__( 'Devanagari', 'bioship' );
	$l10n['greek']                 = esc_attr__( 'Greek', 'bioship' );
	$l10n['greek-ext']             = esc_attr__( 'Greek Extended', 'bioship' );
	$l10n['khmer']                 = esc_attr__( 'Khmer', 'bioship' );
	$l10n['latin']                 = esc_attr__( 'Latin', 'bioship' );
	$l10n['latin-ext']             = esc_attr__( 'Latin Extended', 'bioship' );
	$l10n['vietnamese']            = esc_attr__( 'Vietnamese', 'bioship' );
	$l10n['hebrew']                = esc_attr__( 'Hebrew', 'bioship' );
	$l10n['arabic']                = esc_attr__( 'Arabic', 'bioship' );
	$l10n['bengali']               = esc_attr__( 'Bengali', 'bioship' );
	$l10n['gujarati']              = esc_attr__( 'Gujarati', 'bioship' );
	$l10n['tamil']                 = esc_attr__( 'Tamil', 'bioship' );
	$l10n['telugu']                = esc_attr__( 'Telugu', 'bioship' );
	$l10n['thai']                  = esc_attr__( 'Thai', 'bioship' );
	$l10n['serif']                 = _x( 'Serif', 'font style', 'bioship' );
	$l10n['sans-serif']            = _x( 'Sans Serif', 'font style', 'bioship' );
	$l10n['monospace']             = _x( 'Monospace', 'font style', 'bioship' );
	$l10n['font-family']           = esc_attr__( 'Font Family', 'bioship' );
	$l10n['font-size']             = esc_attr__( 'Font Size', 'bioship' );
	$l10n['font-weight']           = esc_attr__( 'Font Weight', 'bioship' );
	$l10n['line-height']           = esc_attr__( 'Line Height', 'bioship' );
	$l10n['font-style']            = esc_attr__( 'Font Style', 'bioship' );
	$l10n['letter-spacing']        = esc_attr__( 'Letter Spacing', 'bioship' );
	$l10n['top']                   = esc_attr__( 'Top', 'bioship' );
	$l10n['bottom']                = esc_attr__( 'Bottom', 'bioship' );
	$l10n['left']                  = esc_attr__( 'Left', 'bioship' );
	$l10n['right']                 = esc_attr__( 'Right', 'bioship' );
	$l10n['color']                 = esc_attr__( 'Color', 'bioship' );
	$l10n['add-image']             = esc_attr__( 'Add Image', 'bioship' );
	$l10n['change-image']          = esc_attr__( 'Change Image', 'bioship' );
	$l10n['remove']                = esc_attr__( 'Remove', 'bioship' );
	$l10n['no-image-selected']     = esc_attr__( 'No Image Selected', 'bioship' );
	$l10n['select-font-family']    = esc_attr__( 'Select a font-family', 'bioship' );
	$l10n['variant']               = esc_attr__( 'Variant', 'bioship' );
	$l10n['subsets']               = esc_attr__( 'Subset', 'bioship' );
	$l10n['size']                  = esc_attr__( 'Size', 'bioship' );
	$l10n['height']                = esc_attr__( 'Height', 'bioship' );
	$l10n['spacing']               = esc_attr__( 'Spacing', 'bioship' );
	$l10n['ultra-light']           = esc_attr__( 'Ultra-Light 100', 'bioship' );
	$l10n['ultra-light-italic']    = esc_attr__( 'Ultra-Light 100 Italic', 'bioship' );
	$l10n['light']                 = esc_attr__( 'Light 200', 'bioship' );
	$l10n['light-italic']          = esc_attr__( 'Light 200 Italic', 'bioship' );
	$l10n['book']                  = esc_attr__( 'Book 300', 'bioship' );
	$l10n['book-italic']           = esc_attr__( 'Book 300 Italic', 'bioship' );
	$l10n['regular']               = esc_attr__( 'Normal 400', 'bioship' );
	$l10n['italic']                = esc_attr__( 'Normal 400 Italic', 'bioship' );
	$l10n['medium']                = esc_attr__( 'Medium 500', 'bioship' );
	$l10n['medium-italic']         = esc_attr__( 'Medium 500 Italic', 'bioship' );
	$l10n['semi-bold']             = esc_attr__( 'Semi-Bold 600', 'bioship' );
	$l10n['semi-bold-italic']      = esc_attr__( 'Semi-Bold 600 Italic', 'bioship' );
	$l10n['bold']                  = esc_attr__( 'Bold 700', 'bioship' );
	$l10n['bold-italic']           = esc_attr__( 'Bold 700 Italic', 'bioship' );
	$l10n['extra-bold']            = esc_attr__( 'Extra-Bold 800', 'bioship' );
	$l10n['extra-bold-italic']     = esc_attr__( 'Extra-Bold 800 Italic', 'bioship' );
	$l10n['ultra-bold']            = esc_attr__( 'Ultra-Bold 900', 'bioship' );
	$l10n['ultra-bold-italic']     = esc_attr__( 'Ultra-Bold 900 Italic', 'bioship' );
	$l10n['invalid-value']         = esc_attr__( 'Invalid Value', 'bioship' );

	return $l10n;
 }
}

?>