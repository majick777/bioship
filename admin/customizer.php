<?php

// ==========================
// === BioShip Customizer ===
// ==========================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {exit;}

// --------------------------------
// === customizer.php Structure ===
// --------------------------------
// === Customizer Load ===
// - Control References
// - Register Control Classes
// - Kirki Library Loader
// - Update Serialized Option
// === Custom Controls ===
// - Add Info Custom Control
// - Font Customizer Control Script
// - Add Multicheck Custom Control
// - Multicheck Customizer Control Script
// === Register Customizer Controls ===
// === Customize Customizer ===
// - Change the Customizer Description
// - Main Options Panel Display Fix
// - Callbacks for Customizer Live Preview
// === Customizer Helpers ===
// - Default Sanitization Fallbacks
// - Translate Kirki Labels
// - Set Control Types (Kirki 2)
// --------------------------------

// Development TODOs
// -----------------
// ? popup thickbox customizer welcome message for new activations (where welcome=true)
// ? maybe synchronize custom CSS control with existing theme option ?


// Development Note: Due to the difficulty in implementing the complex WordPress Customizer API,
// feature requests and fixes for Customizer *Live Preview* will receive a *low* priority.
// On the other hand, options that are not saving properly will receive a *high* priority.

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

// TODO: maybe implement separate action hooks for live and preview modes?
// https://wp-dreams.com/articles/2014/02/wodpress-theme-customizer-useful-custom-hooks/

// Note: Theme Customizer Boilerplate
// http://www.wpexplorer.com/theme-customizer-boilerplate/
// https://github.com/saas786/WordPress-Theme-Settings-Customizer-Boilerplate

// Note: Selective Refresh
// https://make.wordpress.org/core/2016/02/16/selective-refresh-in-the-customizer/


// -----------------------
// === Customizer Load ===
// -----------------------

// ------------------
// Control References
// ------------------

// Default WP Customizer Controls
// ------------------------------
// checkbox, textarea, radio, select, page-dropdown, text, hidden
// also? number, range, url, tel, email, search, time, date, datetime, week

// Kirki Controls (/customizer/kirkiX/)
// --------------
// https://kirki.org and https://github.com/aristath/kirki
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

// Hybrid Controls (/hybrid3/customize/)
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

// Paulund Custom Controls (/includes/customizer-controls/)
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
if ( !function_exists( 'bioship_customizer_register_controls' ) ) {
 function bioship_customizer_register_controls( $wp_customize ) {

 	// 2.2.0: fix to constant typo (__FILE)
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemestyledir, $vthemetemplatedir;

	// Add Info Custom Control
	// -----------------------
	bioship_customizer_register_info_control();

	// Add MultiCheck Custom Control
	// -----------------------------
	// 2.0.9: added multicheck control
	bioship_customizer_register_multicheck_control();

	// maybe Load Hybrid Controls
	// --------------------------
	// [just for if Hybrid loading is turned off]
	// TEST: loading it this way may not be working just yet?
	// TODO: maybe use spl_autoload_register for Hybrid control classes?
	if ( !defined( 'HYBRID_CUSTOMIZE' ) ) {
		// 2.0.9: use file hierarchy here to allow child theme overrides
		// TODO: allow for alternative includes directory
		$hybridpath = bioship_file_hierarchy( 'file', 'hybrid.php', array( 'includes/hybrid3' ) );
		if ( $hybridpath ) {
			$hybridpath = dirname( $hybridpath );
			$hybridcustomize = $hybridpath . DIRSEP . 'customize' . DIRSEP;
			define( 'HYBRID_CUSTOMIZE', $hybridcustomize );
			if ( !class_exists( 'Hybrid_Customize_Control_Checkbox_Multiple' ) ) {
				$checkboxmultiple = HYBRID_CUSTOMIZE . 'control-checkbox-multiple.php';
				bioship_debug( "Hybrid Multicheck Controller", $checkboxmultiple );
				if ( file_exists( $checkboxmultiple ) ) {
					include $checkboxmultiple;
				}
			}
		}
	}

	// Paulund Custom Controls
	// -----------------------
	// [currently not implemented]
	$customcontrols = array(
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

	// 2.0.9: use apply_filters for user load of controls (default: load none)
	// TODO: maybe use spl_autoload_register here instead?
	$loadcontrols = apply_filters( 'options_customizer_extra_controls', array() );

	foreach ( $customcontrols as $controlkey => $controlclass ) {
		// 2.0.9: use file hierarchy here to allow child theme overrides
		if ( in_array( $controlkey, $loadcontrols ) ) {
			// 2.2.0: add filter for custom includes directory
			$ccdirs = apply_filters( 'options_customizer_custom_controls_dirs', array( 'includes/customizer-controls' ) );
			$controlfile = bioship_file_hierarchy( 'file', $controlkey . '-custom-control.php', $ccdirs );
			if ( $controlfile ) {
				include $controlfile;
			}
		}
	}

	// Kirki Config URL Filter
	// -----------------------
	if ( !function_exists( 'bioship_customizer_kirki_url' ) ) {

	 // 2.1.1: move add_filter internally for consistency
	 add_filter( 'kirki/config', 'bioship_customizer_kirki_url' );

	 function bioship_customizer_kirki_url( $config ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		if ( defined( 'THEMEKIRKIURL' ) ) {
			$config['url_path'] = THEMEKIRKIURL;
		} else {
			// 2.2.0: added fix for undefined kirki dirs variable
			global $vkirkiversion;
			$vkirkiversion = '4';
			if ( version_compare( PHP_VERSION, '5.2.0' ) >= 0 ) {
				$vkirkiversion = '0';
			}
			$vkirkiversion = bioship_apply_filters( 'options_customizer_kirki_version', $vkirkiversion );
			$kirkidirs = array( 'includes/kirki' . $vkirkiversion, 'kirki' . $vkirkiversion, 'includes/kirki', 'kirki' );
			$kirki = bioship_file_hierarchy( 'both', 'kirki.php', $kirkidirs );
			$config['url_path'] = str_replace( 'kirki.php', '', $kirki['url'] );
		}
		return $config;
	 }
	}

 }
}

// --------------------
// Kirki Library Loader
// --------------------
// ref: https://kirki.org/docs/advanced/integration.html
// 2.0.9: separated loader for Kirki
if ( !function_exists( 'bioship_kirki_loader' ) ) {
 function bioship_kirki_loader() {
 	// 2.2.0: fix to constant type (__FILE)
	if (THEMETRACE) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check Kirki version ---
	// 2.0.9: added a Kirki version global (and loading filter)
	// 2.0.9: add PHP version test as apparently Kirki requires PHP 5.2+
	global $vkirkiversion;
	$vkirkiversion = '4';
	if ( version_compare( PHP_VERSION, '5.2.0' ) >= 0 ) {
		$vkirkiversion = '0';
	}
	$vkirkiversion = bioship_apply_filters( 'options_customizer_kirki_version', $vkirkiversion );

	if ( $vkirkiversion && ( $vkirkiversion > 0 ) ) {
		// TODO: allow for alternative includes directory
		$kirkidirs = array( 'includes/kirki' . $vkirkiversion, 'kirki' . $vkirkiversion, 'includes/kirki', 'kirki' );
		$kirki = bioship_file_hierarchy( 'both', 'kirki.php', $kirkidirs );
		bioship_debug( "Kirki Filepath", $kirki );
	}

	// 1.8.5: fix to Kirki check for new file hierarchy syntax
	if ( isset( $kirki ) && is_array( $kirki ) ) {

		// --- find and initialize Kirki ---
		$kirkiurlpath = str_replace( 'kirki.php', '', $kirki['url'] );
		define( 'THEMEKIRKIURL', $kirkiurlpath );
		define( 'THEMEKIRKI', true );
		include $kirki['file'];
		bioship_debug( "Kirki URL", THEMEKIRKIURL );

		// --- backwards compatible for Kirki 2 ---
		if ( '2' == $vkirkiversion ) {
			// need to fire this right now, as we missed after_theme_setup hook..!
			if ( function_exists( 'kirki_filtered_url' ) ) {
				kirki_filtered_url();
			}
			// 1.8.5: not enough, must manually override to fix script paths also
			Kirki::$url = THEMEKIRKIURL;
			bioship_debug( "Kirki Set URL", Kirki::$url );

			// as we really aren't using the Code control, remove codemirror script to avoid bloat
			// 1.9.5: use script loader tag filter to remove the codemirror scripts
			// TEMP: disabled while debugging Kirki load
			// if (!function_exists('bioship_customizer_remove_codemirror_scripts')) {
			//  add_filter('script_loader_tag', 'bioship_customizer_remove_codemirror_scripts', 11, 2);
			// function bioship_customizer_remove_codemirror_scripts($tag, $handle) {
			//	if (strstr($tag, 'vendor/codemirror')) {return '';}
			//	return $tag;
			//  }
			// }
		}

	} else {
		// 2.0.9: added a standalone multicheck control for no Kirki
		define( 'THEMEKIRKI', false );
	}

	// manually do the Kirki_Init (for Kirki 2)
	// ----------------------------------------
	// note: as we are conditionally loading Kirki inside customize_register,
	// - so that Kirki is not loaded unnecessarily outside the Customizer -
	// so we need to fire some init actions that have already missed out on...
	// again fire these now, as we have missed wp_loaded also..!
	// 2.0.9: only do this for Kirki 2 loading
	if ( ( '2' == $vkirkiversion ) && ( class_exists( 'Kirki_Init' ) ) ) {
		// (modified copy of Kirki_Init::add_to_customizer)
		Kirki_Init::fields_from_filters();
		add_action( 'customizer_register', array( 'Kirki_Init', 'register_control_types' ) );
		// note: we are not using Kirki to add panels or sections
		add_action( 'customize_register', array( 'Kirki_Init', 'add_panels' ), 97 );
		add_action( 'customize_register', array( 'Kirki_Init', 'add_sections' ), 98 );
		// ...but we are definitely using the Kirki fields
		add_action( 'customize_register', array( 'Kirki_Init', 'add_fields' ), 99 );
		// 1.9.5: change of class name for Kirki 2.3.5
		if ( class_exists( 'Kirki_Scripts_Loading' ) ) {
			new Kirki_Scripts_Loading();
		} elseif ( class_exists( 'Kirki_Customizer_Scripts_Loading' ) ) {
			new Kirki_Customizer_Scripts_Loading();
		}
	}

	// Format Filter for the Kirki Font Stacks
	// ---------------------------------------
	// note: as we are not using Kirki Typography Control, do not need this yet
	if ( !function_exists( 'bioship_customizer_kirki_font_stacks' ) ) {

	 add_filter( 'kirki/fonts/standard_fonts', 'bioship_customizer_kirki_font_stacks' );

	 function bioship_customizer_kirki_font_stacks() {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
		$fonts = bioship_options_web_font_stacks( array() );
		$fontstacks = array();
		foreach ( $fonts as $fontstack => $display ) {
			// format: array['fontkey'] = array('label' => 'font', 'stack' => 'stack'));
			// note: it looks like fontkey should be the first 'font' in the stack for Kirki ?
			// 1.9.8: fix to fontstack and label variable typos
			// 2.0.9: refix to fontstacks array variable typo
			$fontstacks[$fontstack] = array( 'label' => $display, 'stack' => $fontstack );
		}
	 	return $fontstacks;
	 }
	}

	// Format Filter for the Kirki Google Fonts
	// ----------------------------------------
	// [not implemented] as not using Kirki Typography Control, do not need this...
	if ( !function_exists( 'bioship_customizer_kirki_google_fonts' ) ) {

	 add_filter( 'kirki/fonts/google_fonts', 'bioship_customizer_kirki_google_fonts' );

	 function bioship_customizer_kirki_google_fonts($kirkifonts) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		$options = bioship_file_hierarchy( 'file', 'options.php' );
		include_once $options;
		$fonts = bioship_options_title_fonts();
		$googlefonts = array();
		foreach ( $fonts as $font => $display ) {
			// TODO: Google font variants / subsets / categories for Kirki ?
			$googlefonts[$font] = array(
				'label'    => $display,
				'variants' => array(),
				'subsets'  => array(),
				'category' => array()
			);
		}
		return $googlefonts;
	 }
	}

	// Stylize the Customizer with Kirki
	// ---------------------------------
	// ref: https://kirki.org/docs/advanced/styling-the-customizer.html
	if ( !function_exists( 'bioship_customizer_kirki_styling' ) ) {

	 add_filter( 'kirki/config', 'bioship_customizer_kirki_styling' );

	 function bioship_customizer_kirki_styling($config) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- set custom loading logo image ---
		// 1.9.9: cache logo value to prevent multiple hierarchy calls
		// 2.1.1: removed unnecessary global declaration of customlogoimage
		if ( !isset( $customlogoimage ) ) {
			// 2.0.9: extend possible logo image paths to icons and assets/img
			$imagepaths = array( '', 'images', 'img', 'icons', 'assets/img' );
			$customlogoimage = bioship_file_hierarchy( 'url', 'theme-logo.png', $imagepaths );
		}

		// --- set preview notice ---
		$previewnotice = '<span class="preview-notice" style="float:right;max-width:40%;">';
		$previewnotice .= sprintf(  __( 'You are customizing %s', 'bioship' ), '<strong class="panel-title site-title">' . get_bloginfo( 'name' ) . '</strong>' );
		$previewnotice .= '</span>';

		// --- filter the config options ---
	    $config['description']  = bioship_apply_filters( 'options_customizer_description', $previewnotice );
	    $config['logo_image']   = bioship_apply_filters( 'options_customizer_logo_image', $customlogoimage );
	    $config['color_accent'] = bioship_apply_filters( 'options_customizer_color_accent', '#99BBDD' );
	    $config['color_back']   = bioship_apply_filters( 'options_customizer_color_back', '#E0E0EE' );
	    $config['width']        = bioship_apply_filters( 'options_customizer_panel_width', '20%' );
	    return $config;
	 }
	}

	// load Kirki Internationalization Filter
	// --------------------------------------
	// 1.8.5: added this filter
	// 1.9.9: load as filter as intended
	add_filter( 'kirki/bioship/l10n', 'bioship_customizer_i10n' );

	// maybe use Fallback Customizer Styling Class
	// -------------------------------------------
	// 2.0.9: add this for if Kirki is not loaded
	if ( !THEMEKIRKI ) {
		global $vthemedirs;
		// loads Kirki_Modules_Customizer_Styling and ariColor classes
		$styling = bioship_file_hierarchy( 'file', 'styling.php', $vthemedirs['admin'] );
		if ( $styling ) {
			include $styling;
			new Kirki_Modules_Customizer_Styling();
		}
	}

 }
}

// ------------------------
// Update Serialized Option
// ------------------------
// we need to save back to the correct option for Titan (serialized)...
// as we created a temporary unserialized option for the Customizer
if ( !function_exists( 'bioship_customizer_save_serialized' ) ) {

 add_action( 'customize_save_after', 'bioship_customizer_save_serialized' );

 function bioship_customizer_save_serialized() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings;

	// --- set customize key ---
	if ( THEMEOPT ) {
		$previewkey = THEMEKEY . '_customize';
	} else {
		$previewkey = str_replace( '_options', '_customize', THEMEKEY );
	}

	// --- maybe unserialize current preview settings ---
 	// 2.0.5: update theme options savedtime value
 	$updatedoptions = maybe_unserialize( get_option( $previewkey ) );
 	$updatedoptions['savetime'] = time();

	// 2.2.0: fix to extra not condition on logic check
 	if ( ( '' != $updatedoptions ) && is_array( $updatedoptions ) ) {

		// --- pass new options through standardization fix ---
		$convertedoptions = bioship_titan_theme_options( $updatedoptions );

		// 2.0.9: maybe convert and save custom background, header and logo settings
		if ( THEMEWPORG ) {

			// --- Custom Background ---
			$color = get_theme_mod( 'background_color', get_theme_support( 'custom-background', 'default-color' ) );
			$image = get_theme_mod( 'background_image', get_theme_support( 'custom-background', 'default-image' ) );
			$positionx = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
			$positiony = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );
			$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ));
			$size = get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) );
			$attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
			$convertedoptions['body_bg_color'] = $color;
			$convertedoptions['background_image'] = $image;
			$convertedoptions['background_position'] = $positionx . ' ' . $positiony;
			$convertedoptions['background_repeat'] = $repeat;
			$convertedoptions['background_size'] = $size;
			$convertedoptions['background_attachment'] = $attachment;

			// --- Custom Logo ---
			$customlogo = get_theme_mod('custom_logo');
			$convertedoptions['header_logo'] = $customlogo;

			// --- Custom Header ---
			// note: feature mismatch with header background*
			// $headerimage = get_theme_mod('header_image', get_theme_support('custom-header', 'default-image'));
			// $convertedoptions['header_background_image'] = $headerimage;
		}

		// --- Debug: Write new options to file ---
		if ( THEMEDEBUG ) {
			ob_start();
			echo "Updated Options: " . print_r( $updatedoptions, true ) . PHP_EOL . PHP_EOL;
			echo "Converted Options: " . print_r( $convertedoptions, true ) . PHP_EOL . PHP_EOL;
			$data = ob_get_contents();
			ob_end_clean();
			$debugfile = 'customizer-options.txt';
			bioship_write_debug_file( $debugfile, $data );
		}

		// --- serialize and write back to the actual option ---
		// ### MARKER
		// CHECKME: use updateoptions or convertedoptions here?!
		$serializedoptions = serialize( $updatedoptions );
		// $serializedoptions = serialize($convertedoptions);
		update_option( THEMEKEY, $serializedoptions );

		// --- clear preview key ---
		delete_option ( $previewkey );
	}

 }
}


// -----------------------
// === Custom Controls ===
// -----------------------

// -----------------------
// Add Info Custom Control
// -----------------------
if ( !function_exists( 'bioship_customizer_register_info_control')) {
 function bioship_customizer_register_info_control() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- declare Info Control Class ---
	// control class outputs label and description for info/note option type
	// ...also used to echo expand/collapse links for Typography Controls
	class Info_Custom_Control extends WP_Customize_Control {
		public function render_content() {
			echo '<label>';
			if ( '' != $this->label ) {
				echo '<span class="customize-control-title customize-info-title">' . esc_html( $this->label ) . '</span>';
			}
			if ( 'typography_controller' == $this->description ) {
				$id = str_replace( '[helper]', '', $this->id );
				$pos = strpos( $id, '[' ) + 1;
				$id = substr( $id, $pos, strlen( $id ) );
				$id = str_replace( ']', '', $id );
				// 2.2.0: added missing translation wrappers to link anchors
				echo '<span id="' . esc_attr( $id ) . '-expand"><a href="javascript:void(0);" onclick="expandoptions(\''.$id.'\');" style="text-decoration:none;">';
				echo '[+] ' . __( 'Expand Typography Options', 'bioship' ) . '</a></span>';
				echo '<span id="' . esc_attr( $id ) . '-collapse" style="display:none;"><a href="javascript:void(0);" onclick="collapseoptions(\'' . esc_attr( $id ) . '\');" style="text-decoration:none;">';
				echo '[-] ' . __( 'Collapse Typography Options', 'bioship' ) . '</a></span>';
			} elseif ( '' != $this->description ) {
				// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo '<p class="description">' . $this->description . '</p>';
			}
			echo '</label>';
		}
	}

	// --- load info control script in the footer ---
	// 2.0.9: moved here from functions.php loading
	add_action( 'customize_controls_print_footer_scripts', 'bioship_customizer_font_script' );

	// --- register info control via Kirki ---
	// note: before initializing Kirki
	// 2.2.0: fix function exists check to match function name
	if ( !function_exists( 'bioship_kirki_add_info_control' ) ) {

     add_filter( 'kirki/control_types', 'bioship_kirki_add_info_control' );

     function bioship_kirki_add_info_control( $controls ) {
     	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
    	$controls['info'] = $controls['note'] = 'Info_Custom_Control';
    	return $controls;
     }
    }

 }
}

// ------------------------------
// Font Customizer Control Script
// ------------------------------
if ( !function_exists( 'bioship_customizer_font_script' ) ) {
 function bioship_customizer_font_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	global $typocontrolids, $controllerids;
 	if ( !is_array( $typocontrolids ) || !is_array( $controllerids ) ) {
 		return;
 	}

	// --- for the Typography expand/collapse javascript ---
	// TODO: maybe animate expand/collapse display functions?
	echo "<script>function expandoptions(divid) {
		document.getElementById(divid+'-expand').style.display = 'none';
		document.getElementById(divid+'-collapse').style.display = '';
		var controlids = controllerids[divid].split(',');
		for (i in controlids) {
			if (document.getElementById(controlids[i])) {
				document.getElementById(controlids[i]).style.display = '';
			}
		}
	}
    function collapseoptions(divid) {
		document.getElementById(divid+'-collapse').style.display = 'none';
		document.getElementById(divid+'-expand').style.display = '';
		var controlids = controllerids[divid].split(',');
		for (i in controlids) {
			if (document.getElementById(controlids[i])) {
				document.getElementById(controlids[i]).style.display = 'none';
			}
		}
    }" . PHP_EOL;

	// --- output the typo controller id arrays to javascript ---
	$j = 0;
	echo "var typocontrols = new Array(); var controllerids = new Array;" . PHP_EOL;
	foreach ( $typocontrolids as $typocontrolid ) {
		echo "typocontrols[" . esc_js( $j ) . "] = '" . esc_js( $typocontrolid ) . "';" . PHP_EOL;
		echo "controllerids['" . esc_js( $typocontrolid ) . "'] = '";
		$k = 0;
		foreach ( $controllerids[$typocontrolid] as $controllerid ) {
			if ( $k > 0 ) {
				echo ",";
			}
			echo esc_js( $controllerid ); $k++;
		}
		echo "';" . PHP_EOL;
		$j++;
	}

    // --- hide all the typography subcontroller options by default ---
    echo "jQuery(document).ready(function($) {setTimeout(hidetyposubcontrols,5000);});
    function hidetyposubcontrols() {
	    var controlid; var controlids = new Array();
	    for (i in typocontrols) {
	    	controlid = typocontrols[i];
			controlids = controllerids[controlid].split(',');
	    	for (i in controlids) {
	    		if (document.getElementById(controlids[i])) {
					document.getElementById(controlids[i]).style.display = 'none';
				}
	    	}
	    }
	}</script>";
 }
}

// -------------------------------
// Add a Multicheck Custom Control
// -------------------------------
// 2.0.9: standalone multicheck controller (via Titan Framework)
if ( !function_exists( 'bioship_customizer_register_multicheck_control' ) ) {
 function bioship_customizer_register_multicheck_control() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	class Multicheck_Custom_Control extends WP_Customize_Control {

		public $description;
		public $options;

		public function render_content() {

			// the saved value is an array, convert it to csv
			$savedValueCSV = '';
			if ( is_array( $this->value() ) ) {
				$savedvalues = array();
				foreach ( $this->value() as $key => $value ) {
					if ( '1' == $value ) {
						$savedvalues[] = $key;
					}
				}
				if ( count( $savedvalues ) > 1 ) {
					$savedValueCSV = (string)implode( ',', $savedvalues );
				} elseif ( 1 == count( $savedvalues ) ) {
					$savedValueCSV = (string)$savedvalues[0];
				}
				$values = $savedvalues;
			} else {
				$savedValueCSV = (string)$this->value();
				$values = explode( ',', $this->value() );
			}

			$description = '';
			if ( !empty($this->description ) ) {
				$description = '<p class="description">' . $this->description . '</p>';
			}

			echo '<label class="customize-multicheck-container">';
			echo '<span class="customize-control-title customize-multicheck-title">' . esc_html( $this->label ) . '</span>';

			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo $description;

			echo '<!-- Multicheck Values: ' . esc_attr( print_r( $values, true ) ) . ' -->';
			foreach ( $this->options as $value => $label ) {
				$id = $this->id . '[' . $value . ']';
				echo '<label for="'.esc_attr($id).'">';
				echo '<input class="customize-multicheck" id="' . esc_attr( $id ) . '" type="checkbox" value="' . esc_attr( $value ) . '"';
				if	( in_array( $value, $values ) ) {
					echo ' checked="checked"';
				}
				echo '>';
				echo esc_html( $label );
				echo '</label><br>';
			}
			echo '<!-- Saved Values: ' . esc_attr( $savedValueCSV ) . ' -->' . PHP_EOL;
			echo '<input type="text" value="' . esc_attr( $savedValueCSV ) . '" style="display:none;">' . PHP_EOL;
			echo '<input type="hidden" value="' . esc_attr( $savedValueCSV ) . '" ';
			// TODO: recheck what this->link does ?
			$this->link();
			echo '>' . PHP_EOL . '</label>';

		}
	}

	// --- load multicheck control script in footer ---
	// 2.0.9: load control script in footer
	add_action( 'customize_controls_print_footer_scripts', 'bioship_customizer_multicheck_script' );

	// --- register multicheck control via Kirki ---
	// note: before initializing Kirki
	// 2.2.0: fix function exists check to match function name
	if ( !function_exists( 'bioship_kirki_add_multicheck_control' ) ) {

     add_filter( 'kirki/control_types', 'bioship_kirki_add_multicheck_control' );

     function bioship_kirki_add_multicheck_control( $controls ) {
     	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
    	$controls['multicheck'] = 'Multicheck_Custom_Control';
    	return $controls;
     }
    }
 }
}

// ------------------------------------
// Multicheck Customizer Control Script
// ------------------------------------
if ( !function_exists( 'bioship_customizer_multicheck_script' ) ) {
 function bioship_customizer_multicheck_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 2.2.0: use echo instead of PHP short tags
	echo "<script>
	jQuery(document).ready(function($) {
		'use strict';

		$('input.customize-multicheck').parents('li:eq(0)').find('input[type=text]')
		.each(function() {
			var startvalue = $(this).val();
			$(this).parent().find('input[type=hidden]').val(startvalue);
		});

		$('input.customize-multicheck').change(function(event) {
			event.preventDefault();
			var csvalue = '';

			$(this).parents('li:eq(0)').find('input[type=checkbox]').each(function() {
				if ($(this).is(':checked')) {csvalue += $(this).attr('value') + ',';}
			});

			csvalue = csvalue.replace(/,+$/, '');

			// we need to trigger the field afterwards to enable the save button
			$(this).parents('li:eq(0)').find('input[type=hidden]').val(csvalue).trigger('change');

			return true;
		});
	});</script>";
 }
}


// ------------------------------------
// === Register Customizer Controls ===
// ------------------------------------
if ( !function_exists( 'bioship_customizer_load_control_options' ) ) {
 function bioship_customizer_load_control_options( $wp_customize ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemesettings, $vthemename, $vthemeoptions, $vthemeoptionspage;
	global $vkirkiversion, $typocontrolids, $controllerids;

	// 2.2.0: include options.php for customizer
	if ( !isset( $vthemeoptions ) || !is_array( $vthemeoptions ) ) {
		if ( !function_exists( 'bioship_options' ) ) {
			$options = bioship_file_hierarchy( 'file', 'options.php' );
			include_once $options;
		}
		$vthemeoptions = bioship_options();
	}

	// --- check options page to show ---
	// 1.9.9: show only basic options only in customizer by default
	// 2.2.0: use vthemeoptionspage global (set in functions.php)
	if ( !isset( $vthemeoptionspage ) ) {
		$vthemeoptionspage = 'all';
	}

	// --- check if splitting options ---
	// 1.9.9: filter whether to split options
	// 2.1.1: moved outside of below loop to check once only
	$splitoptions = false; // 2.2.0: TEMP DISABLED
	$splitoptions = bioship_apply_filters( 'options_customizer_split_options', $splitoptions );

	// Convert all options to Layer Options
	// ------------------------------------

	// 2.2.0: set skip keys only once
	if ( THEMEWPORG ) {
		global $wp_version;

		$skipkeys = array(
			// --- Custom Background (custom_background) ---
			'background_image'	=> '',
			'background_color'	=> '',
			// 'background_position' => '',
			// 'background_size' => '',
			// 'background_repeat' => '',
			// 'background_attachment' => '',

			// --- Custom Logo (custom_logo) ---
			'header_logo'		=> '4.5-alpha',

			// --- Custom Header (custom_header) ---
			// note: support feature mismatch*
			// 'header_background_image' => '',
			// 'header_background_position' => '',
			// 'header_background_size' => '',
			// 'header_background_repeat' => '',
		);
	}

	$i = $j = $k = $l = $m = 0;
	foreach ( $vthemeoptions as $optionkey => $optionvalues ) {

		bioship_debug("Parsing Theme Option Key " . $optionkey, $optionvalues);

		// --- set skip keys for WordPress.Org version ---
		// 2.0.9: avoid duplicate settings for custom theme supports
		$skip = false;
		if ( THEMEWPORG ) {
			foreach ( $skipkeys as $skipkey => $wpversion ) {
				// 2.2.0: fix to match key ID explicitly
				if ( isset( $optionvalues['id'] ) && ( $optionvalues['id'] === $skipkey ) ) {
					if ( '' != $wpversion ) {
						if ( version_compare( $wp_version, $wpversion, '<' ) ) {
							$skip = true;
						}
					} else {
						$skip = true;
					}
				} elseif ( !isset($optionvalues['id'] ) ) {
					bioship_debug( "Theme Option missing ID key!", $optionvalues );
				}
			}
		}

		if ( !$skip ) {

			// --- check page display ---
			// 1.9.9: check new customizer page display value
			if ( isset( $optionvalues['page'] ) ) {
				$forpage = $optionvalues['page'];
			} else {
				$forpage = 'both';
				if ( THEMEDEBUG ) {
					bioship_debug("Missing Page Key for " . $optionvalues['id'] );
				}
			}

			// --- set option class layers ---
			// 1.8.5: fix heading type (missing class) if using Options Framework
			$layers = array( 'skin', 'muscle', 'skeleton' );
			if ( isset( $optionvalues['id'] ) ) {
				if ( in_array( $optionvalues['id'], $layers ) ) {
					$optionvalues['class'] = $optionvalues['id'];
					$optionvalues['id'] = $optionvalues['name'];
				}
			}
			if ( THEMEDEBUG ) {
				bioship_debug( "Option ID: " . $optionvalues['id'] . " - For Page: " . $forpage );
			}

			// --- check Customizer page conditions ---
			// 1.9.9: match conditions for this customizer page
			if ( !$splitoptions || ( 'all' == $vthemeoptionspage ) || ( 'both' == $forpage )
			  || ( ( 'basic' == $forpage ) && ( 'basic' == $vthemeoptionspage ) )
			  || ( ( 'advanced' == $forpage ) && ( 'advanced' == $vthemeoptionspage ) ) ) {
				if ( strstr( $optionvalues['class'], 'skin' ) ) {
					$skinoptions[$i] = $optionvalues; $i++;
				} elseif ( strstr( $optionvalues['class'], 'muscle') ) {
					$muscleoptions[$j] = $optionvalues; $j++;
				} elseif ( strstr( $optionvalues['class'], 'skeleton' ) ) {
					$skeletonoptions[$k] = $optionvalues; $k++;
				} else {
					$hiddenoptions[$l] = $optionvalues; $l++;
				}
			}
		} else {
			bioship_debug( "Skipping Option Key ", $optionkey );
		}
	}

	// --- debug layer options ---
	// 2.0.9: use simpler debug function
	bioship_debug( "Skin Options", $skinoptions );
	bioship_debug( "Muscle Options", $muscleoptions );
	bioship_debug( "Skeleton Options", $skeletonoptions );
	bioship_debug( "Hidden Options", $hiddenoptions );

	// --- Settings Default Types ---
	$defaulttypes = array( 'checkbox', 'textarea', 'radio', 'select', 'page-dropdown', 'text', 'hidden' );
	$typography = array( 'color', 'font-size', 'font-family', 'font-style' );

	// --- Set Settings Prefix ---
	if ( THEMEOPT ) {
		$settingsprefix = THEMEKEY.'_customize';
	} else {
		$settingsprefix = str_replace( '_options', '_customize', THEMEKEY );
	}

	// --- Create Copy of Theme Options ---
	// ...(re)set a dummy unserialized array - for use by the Customizer only...
	delete_option( $settingsprefix );
	add_option( $settingsprefix, $vthemesettings );

	// --- extra Typography options for Titan ---
	if ( !THEMEOPT ) {
		$typography[] = 'font-weight';
		$typography[] = 'line-height';
		$typography[] = 'letter-spacing';
		$typography[] = 'text-transform';
		$typography[] = 'font-variant';

		// --- add  typography options ---
		// (from titan/includes/class-option-font.php)
		$titantypography = array();
		$titantypography['websafefonts'] = bioship_options_web_font_stacks( array() );
		$titantypography['googlefonts'] = bioship_options_title_fonts();
		$titantypography['allfonts'] = array_merge( $titantypography['websafefonts'], $titantypography['googlefonts'] );
		$fontsizeoptions[] = 'inherit';

		// 1.8.5: doubled choice arrays to value-label pairs
		for ( $n = 1; $n <= 150; $n++ ) {
			$fontsizeoptions[$n . 'px'] = $n . 'px';
		}
		$titantypography['font-size'] = $fontsizeoptions;
		$titantypography['font-weight'] = array(
			'normal' => 'normal', 'bold' => 'bold', 'bolder' => 'bolder', 'lighter' => 'lighter',
			 '100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500',
			 '600' => '600', '700' => '700', '800' => '800', '900' => '900');
		$titantypography['font-style'] = array( 'normal' => 'normal', 'italic' => 'italic' );
		for ( $n = .5; $n <= 3; $n += 0.1 ) {
			$lineheightoptions[$n . 'em'] = $n . 'em';
		}
		$titantypography['line-height'] = $lineheightoptions;

		// --- letter spacing, text transform, font variant ---
		for ( $n = -20; $n <= 20; $n++ ) {
			$letterspacingoptions[$n . 'px'] = $n . 'px';
		}
		$titantypography['letter-spacing'] = $letterspacingoptions;
		$titantypography['text-transform'] = array(
			'none' => 'none', 'capitalize' => 'capitalize', 'uppercase' => 'uppercase', 'lowercase' => 'lowercase'
		);
		$titantypography['font-variant'] = array( 'normal' => 'normal', 'small-caps' => 'small-caps' );

		// note: there are text shadow options also(not implemented)
	}


	// --- set Typography Sanitization Callbacks ---
	// 1.8.5: added these sanitization fallbacks
	$typosanitize['color'] = 'bioship_fallback_sanitize_color';
	$typosanitize['font-size'] = 'bioship_fallback_sanitize_css_size';
	$typosanitize['font-family'] = 'bioship_fallback_sanitize_select';
	$typosanitize['font-style'] = 'bioship_fallback_sanitize_select';
	$typosanitize['font-weight'] = 'bioship_fallback_sanitize_css_size';
	$typosanitize['line-height'] = 'bioship_fallback_sanitize_css_size';
	$typosanitize['letter-spacing'] = 'bioship_fallback_sanitize_css_size';
	$typosanitize['text-transform'] = 'bioship_fallback_sanitize_select';
	$typosanitize['font-variant'] = 'bioship_fallback_sanitize_select';

	// --- Set Kirki basic config ---
	// (probably not even need to do this but what the heck...)
	// 1.8.5: added disable_output argument for Kirki update
	if ( class_exists( 'Kirki' ) ) {
		$args = array(
			'capability' => 'edit_theme_options',
			'option_type' => 'option',
			'option_name' => $settingsprefix,
			'disable_output' => true,
		);
		Kirki::add_config( 'bioship', $args );
	}

	// Modify Customize Sections
	// -------------------------
	if ( THEMEWPORG ) {
		$themepanel = $wp_customize->get_panel('themes')->priority = 999;
	} else {
		$wp_customize->remove_panel('themes');
	}
	$wp_customize->get_section( 'title_tagline' )->title = __( 'Site Options', 'bioship' ); // generalized
	$wp_customize->get_section( 'title_tagline' )->priority = 10;
	// set live preview transport to postMessage for title and tagline
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	// 2.0.9: always remove header_image theme support (background header image feature mismatch)
	$wp_customize->remove_section( 'header_image' );

	// --- split off some advanced options ---
	// 1.9.9: clear basic sections (from advanced options page only)
	if ( 'advanced' == $vthemeoptionspage ) {

		// -- remove some sections ---
		$wp_customize->remove_section( 'title_tagline' );
		// 2.0.8: only remove unused sections from advanced page (for WordPress.org compliance)
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'background_image' );

		// --- remove widgets and nav menus from advanced options ---
		// 2.0.9: remove widgets section from advanced page
		// 2.1.1: use filter instead of remove_panel
		// $wp_customize->remove_panel('widgets');
		// $wp_customize->remove_panel('nav_menus');
		if ( !function_exists( 'bioship_customizer_advanced_remove_panels' ) ) {

		 add_filter( 'customize_loaded_components', 'bioship_customizer_advanced_remove_panels' );

		 function bioship_customizer_advanced_remove_panels( $components ) {
		 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
		 	$remove = array( 'widgets', 'nav_menus' );
		 	if ( count( $components) > 0 ) {
			 	foreach ( $components as $i => $component ) {
			 		if ( in_array( $component, $remove ) ) {
			 			unset($components[$i]);
			 		}
			 	}
			}
			return $components;
		 }
		}
	}

	// --- custom CSS section ---
	if ( ( 'advanced' == $vthemeoptionspage ) || !THEMEWPORG ) {
		$wp_customize->remove_panel('themes');
		// 2.0.5: remove new custom CSS section (as not implemented)
		// TODO: maybe synchronize custom CSS control with existing theme option ?
		$wp_customize->remove_section( 'custom_css' );
	}

	// --- Customize Default Sections ---
	// neatness: move static_front_page controls to a title_tagline 'section'
	$wp_customize->get_control( 'show_on_front' )->section = 'title_tagline';
	$wp_customize->get_control( 'page_on_front' )->section = 'title_tagline';
	$wp_customize->get_control( 'page_for_posts' )->section = 'title_tagline';
	$wp_customize->remove_section( 'static_front_page' ); // remove section

	// --- Handle Kirki Control names ---
	$prefixedcontrols = $ignorecontrols = $kirkicontrols = array();
	if ( '2' == $vkirkiversion ) {
		$kirkicontrols = bioship_kirki_control_types();
	} elseif ( ( '3' == $vkirkiversion ) || ( '4' == $vkirkiversion ) ) {
		$kirkicontrols = apply_filters( 'kirki/control_types', array() );
		// 2.0.9: set to ignore Kirki 3 controls that are not working properly
		$ignorecontrols = array( 'select', 'multicheck' );
	}
	// 2.0.9: check kirki- prefixed controls
	if ( count( $kirkicontrols ) > 0 ) {
		foreach ( $kirkicontrols as $key => $controlclass ) {
			if ( 'kirki-' == substr( $key, 0, strlen( 'kirki-' ) ) ) {
				$prefixedcontrols[] = str_replace( 'kirki-', '', $key );
			}
		}
	}
	bioship_debug( "Kirki Controls", $kirkicontrols );
	bioship_debug( "Prefixed Controls", $prefixedcontrols );

	// Loop through the Layer Options
	// ------------------------------
	for ( $i = 0; $i < 3; $i++ ) {

		// --- Set Data for this Layer Panel ---
		$theseoptions = array();
		if ( 0 == $i ) {
			$theseoptions = $skinoptions;
			$panelslug = 'skinoptions';
			$args = array( 'title' => __( 'Skin Options', 'bioship' ), 'priority' => 180 );
			$args['description'] = __( 'All the Skin Layer Options', 'bioship' );
		} elseif ( 1 == $i ) {
			$theseoptions = $muscleoptions;
			$panelslug = 'muscleoptions';
			$args = array( 'title' => __( 'Muscle Options', 'bioship'), 'priority' => 190 );
			$args['description'] = __( 'All the Muscle Layer Options', 'bioship' );
		} elseif ( 2 == $i ) {
			$theseoptions = $skeletonoptions;
			$panelslug = 'skeletonoptions';
			$args = array( 'title' => __( 'Skeleton Options', 'bioship' ), 'priority' => 200 );
			$args['description'] = __( 'All the Skeleton Layer Options', 'bioship' );
		}
		// note: no nede ti handle the hidden options as only changed values are saved
		bioship_debug( "Panel", $panelslug );
		bioship_debug( "Panel Options", $theseoptions );

		// --- Add the Layer Panel ---
		$wp_customize->add_panel( $panelslug, $args );
		// Kirki::add_panel($panelslug, $args); // just not working!

		// Loop through Layer Options
		// --------------------------
		$typocontrols = $sectionpriority = $priority = 10;
		$types = array();
		foreach ( $theseoptions as $thisoption ) {

			bioship_debug( "Option Type", $thisoption['type'] );
			$controltypes = array();
			if ( !in_array( $thisoption['type'], $types ) ) {
				$types[] = $thisoption['type'];
			}

			// Add a Customizer Section for each Heading
			// -----------------------------------------
			if ( 'heading' == $thisoption['type'] ) {

				// --- convert heading to section ---
				bioship_debug( "Customizer Section", $thisoption );
				$sectionslug = $vthemename . '_' . strtolower( $thisoption['name'] );
				$args = array( 'panel' => $panelslug, 'title' => $thisoption['name'], 'priority' => $sectionpriority );
				if ( isset( $thisoption['desc'] ) ) {
					$args['description'] = $thisoption['desc'];
				}
				$wp_customize->add_section( $sectionslug, $args );
				// Kirki::add_section($sectionslug, $args); // not working
				$sectionpriority++;
				$priority = 10;

			} elseif ( ( 'typography' == $thisoption['type'] ) || ( 'font' == $thisoption['type'] ) ) {

				// Typography Controls
				// -------------------
				// - Kirki Library Typography Control ?
				// - Justin Tadlocks Customizer-Typography prototype ?
				// - Titan Framework Typography Control ?
				// - Google_Font_Dropdown_Custom_Control ?
				// ...going for individual controls with expand/collapse...

				// Add a simple info type 'setting' and 'control' as a Typography label header
				$settingid = $settingsprefix . '[' . $thisoption['id'] . ']';
				$settingargs = array( 'type' => 'option', 'capability' => 'edit_theme_options' );
				$controlargs = array(
					'type'			=> 'info',
					'priority'		=> $priority,
					'section'		=> $sectionslug,
				 	'label'			=> $thisoption['name'],
				 	'description'	=> $thisoption['desc'],
				 	'setting'		=> $settingid,
				);

				// TONOTDO: maybe adapt a Kirki Typography control to Titan Typography?
				// - not used as currently the settings do not quite match up correctly
				// if (class_exists('Kirki')) {
				//	$controlargs['type'] = 'typography';
				//	$args = array_merge($settingargs, $controlargs);
				//	Kirki::add_field('bioship', $args);
				// }
				// else {

					// Typography Expand/Collapse
					// --------------------------
					// this is a kind of dummy Control wrapper using our Info Control
					// to show/hide all the typography options for a particular element

					// Set subcontroller element list for javascript expand/collapse
					$j = 0;
					$typocontrolids[$typocontrols] = $thisoption['id'];
					foreach ( $typography as $typooption ) {
						$typocontrolid = 'customize-control-' . $settingsprefix . '-' . $thisoption['id'] . '-' . $typooption;
						$controllerids[$thisoption['id']][$j] = $typocontrolid;
						$j++;
					}
					$controlargs['description'] = 'typography_controller';
					$typocontrols++;

					// --- add the Info Control to echo the expand/collapse javascript ---
					$typoid = $settingid . '[helper]'; // dummy option
					// 2.0.7: fix dummy sanitization callback for requirement check
					$wp_customize->add_setting( $typoid, array(
						'type'				=> $settingargs['type'],
						'capability'		=> $settingargs['capability'],
						'sanitize_callback'	=> 'bioship_fallback_sanitize_unfiltered',
					) );
					$wp_customize->add_control(
						new Info_Custom_Control( $wp_customize, $typoid, $controlargs )
					);
					$priority++;

					// Loop through the Typography options
					// -----------------------------------
					foreach ( $typography as $typooption ) {

						$default = '';
						// TODO: maybe check for Options Framework specifically instead ?
						if ( !THEMETITAN ) {

							// --- set to Options Framework typography defaults ---
							// 2.2.0: fix to font family fallback key (face not font)
							if ( 'color' == $typooption ) {
								$default = $thisoption['std']['color'];
							} elseif ( 'font-size' == $typooption ) {
								$default = $thisoption['std']['size'];
							} elseif ( 'font-family' == $typooption ) {
								$default = $thisoption['std']['face'];
							} elseif ( 'font-style' == $typooption ) {
								$default = $thisoption['std']['style'];
							}

							$choices = array();
							// TODO: recheck font control size value consistency (without Titan)
							$choices['font-size'] = $thisoption['options']['sizes'];
							$choices['font-family'] = $thisoption['options']['faces'];
							$choices['font-style'] = $thisoption['options']['styles'];
							// note: color option always assumed to be true here

							// 2.2.0: map missing font option choices
							$choices['font-weight'] = $thisoption['options']['weights'];
							$choices['line-height'] = $thisoption['options']['heights'];
							$choices['letter-spacing'] = $thisoption['options']['spacing'];
							$choices['font-variant'] = $thisoption['options']['variant'];
							$choices['text-transform'] = $thisoption['options']['transform'];

						} else {

							// --- set choices to the Titan typography options ---
							if ( isset( $thisoption['default'][$typooption] ) ) {
								$default = $thisoption['default'][$typooption];
							}

							$choices = $titantypography;
							// note: assumes value to be false if set
							if ( isset( $thisoption['show_websafe_fonts'] ) ) {
								$fontoptions = $titantypography['googlefonts'];
							} elseif ( isset( $thisoption['show_google_fonts'] ) ) {
								$fontoptions = $titantypography['websafefonts'];
							} else {
								$fontoptions = $titantypography['allfonts'];
							}
							$choices['font-family'] = $fontoptions;
						}

						// --- set default fallback values ---
						if ( '' == $default ) {
							if ( 'color' == $typooption ) {
								$default = '#999999';
							} elseif ( 'font-family' == $typooption ) {
								foreach ( $choices['font-family'] as $fontkey => $fontlabel ) {
									// use first font as default
									$default = $fontkey;
									continue;
								}
							} elseif ( 'font-size' == $typooption ) {
								$default = '14px';
							} elseif ( 'font-style' == $typooption ) {
								$default = 'normal';
							} elseif ( 'font-weight' == $typooption ) {
								$default = 'normal';
							} elseif ( 'line-height' == $typooption ) {
								$default = '1.4em';
							} elseif ( 'letter-spacing' == $typooption ) {
								$default = 'normal';
							} elseif ( 'font-variant' == $typooption ) {
								$default = 'normal';
							} elseif ( 'text-transform' == $typooption ) {
								$default = 'none';
							}
						}

						// --- setup Setting and Control Arguments ---
						$settingid = $settingsprefix . '[' . $thisoption['id'] . '][' . $typooption . ']';
						// 1.8.5: set default typography sanitization callbacks
						// 2.2.0: add sanitization callback direct to args array
						$settingargs = array(
							'type'				=> 'option',
							'capability'		=> 'edit_theme_options',
							'default'			=> $default,
							'transport'			=> 'postMessage',
							'sanitize_callback'	=> $typosanitize[$typooption],
						);
						$label = str_replace( '-', ' ', $typooption );
						$label = strtoupper( substr( $label, 0, 1 ) ) . substr( $label, 1, strlen( $label ) );
						$controlargs = array(
							'type'			=> 'select',
							'priority'		=> $priority,
							'section'		=> $sectionslug,
							'label'			=> $label,
							'description'	=> '',
							'setting'		=> $settingid,
						);

						// -- add this Typography Customizer Setting and Control ---
						// 2.0.7: fix to for sanitization callback requirement check
						$setting = array(
							'type'				=> $settingargs['type'],
							'capability'		=> $settingargs['capability'],
							'default'			=> $settingargs['default'],
							'transport'			=> $settingargs['transport'],
							'sanitize_callback' => $settingargs['sanitize_callback'],
						);
						$wp_customize->add_setting( $settingid, $setting );
						// $value = $wp_customize->get_setting($settingid)->value(); // debug point

						// typography control styling
						// TODO: fix this? the right styling - but it is just being completely ignored? :-/
						// $controlargs['input_attrs'] = array('style' => 'float:right; margin-top:-30px;');

						// --- add the Customizer Control ---
						if ( 'color' == $typooption ) {
							// use color picker control here not select
							$controlargs['type'] = 'color';
							$wp_customize->add_control(
								new WP_Customize_Color_Control( $wp_customize, $settingid, $controlargs )
							);
						} else {
							// 2.2.0: do not add controls where choices are not set
							if ( isset( $choices[$typooption] ) ) {
								$controlargs['choices'] = $choices[$typooption];
								$wp_customize->add_control( $settingid, $controlargs );
							}
						}
						$priority++;
					}

				// } // close unused Kirki Typography check

			} else {

				if ( !isset( $sectionslug ) ) {
					bioship_debug( "Section Slug not set for Option", $thisoption );
				}

				// --- get option type ---
				$type = $thisoption['type'];

				// --- set setting ID ---
				if ( ( 'info' == $type ) || ( 'note' == $type ) ) {
					// dummy ID value
					$settingid = $settingsprefix . "[info]";
				} else {
					$settingid = $settingsprefix . "[" . $thisoption['id'] . "]";
				}

				// --- set option default ---
				if ( isset( $thisoption['default'] ) ) {
					$default = $thisoption['default'];
				} elseif ( isset( $thisoption['std'] ) ) {
					$default = $thisoption['std'];
				} else {
					// clear for loop if default is empty
					$default = '';
				}

				// --- set settings args ---
				$settingargs = array(
					'type'			=> 'option',
					'capability'	=> 'edit_theme_options',
					'default'		=> $default,
					'transport'		=> 'postMessage'
				);
				// bioship_debug( "Setting Args", $settingargs );

				// note: set to postMessage by default to prevent unnecessary page refreshes
				// (only layout options and script loads should really force a refresh -
				// these are defined in the options array by setting transport to refresh)

				// --- set control transport ---
				if ( ( isset( $thisoption['transport'] ) ) && ( 'refresh' == $thisoption['transport'] ) ) {
					$settingargs['transport'] = 'refresh';
				}
				// this one not used here, but included for completeness anyway
				if ( isset( $thisoption['theme_supports'] ) ) {
					$settingargs['theme_supports'] = $thisoption['theme_supports'];
				}

				// setup Customizer Control for each Option
				// ----------------------------------------
				// standard inputs: checkbox, radio, text, textarea, select
				// non-standard: info/note, color, multicheck

				$controlargs = array(
					'type'			=> $type,
					'priority'		=> $priority,
					'section'		=> $sectionslug,
					'label'			=> $thisoption['name'],
					'description'	=> $thisoption['desc'],
					'setting'		=> $settingid,
				);

				// --- set options to choices for multiple choice input types ---
				if ( isset( $thisoption['options'] ) ) {
					// 2.0.9: set options key as well for cross-control compatability
					$controlargs['choices'] = $controlargs['options'] = $thisoption['options'];
				}

				// [not working] set input attributes for some default input types...
				// it seems like the 'style' attribute here does absolutely nothing!
				// note: class and placeholder fields have not been tested here yet...
			  	// eg... 'input_attrs' => array('class' => '', 'style' => '', 'placeholder' => '');
				if ('textarea' == $type ) {
					$thisoption['input_attrs']['style'] = 'height:300px;';
				}
				// ...allow for predefined option-specific override too...
				if ( isset( $thisoption['input_attrs'] ) ) {
					$controlargs['input_attrs'] = $thisoption['input_attrs'];
				}

				// --- maybe set active_callback argument ---
				// note: postMessage and active_callback are mutually exclusive methods
				// because active_callback relies on using the refresh transport...
				// for the now not using active_callback argument anyway... so whatevs
				// ref: comments on http://ottopress.com/2015/whats-new-with-the-customizer/
				if ( isset( $thisoption['active_callback'] ) ) {
					$controlargs['active_callback'] = $thisoption['active_callback'];
				}

				// --- maybe set sanitization_callback override ---
				// 1.8.5: allow for explicit sanitization callback override
				if ( isset( $thisoption['sanitize_callback'] ) ) {
					$settingsargs['sanitize_callback'] = $thisoption['sanitize_callback'];
				}

				// --- Kirki controls recheck ---
				// 2.0.9: make sure the matching control type explicitly still exists in Kirki
				if ( class_exists( 'Kirki' ) && !in_array( $type, $ignorecontrols )
				  && ( in_array( $type, $kirkicontrols) || in_array( $type, $prefixedcontrols ) ) ) {

					// --- use Kirki Controls for the option fields ---
					// 2.0.9: fix for Kirki 3: maybe add the kirki- prefix to control type
					// 2.2.0: fix kirkiversion variable to vkirkiversion
					if ( ( '3' == $vkirkiversion ) && in_array( $type, $prefixedcontrols ) ) {
						$controlargs[$type] = 'kirki-' . $type;
					}
					bioship_debug( "Kirki Control", $kirkicontrols[$controlargs[$type]] );

					// --- option to use a help icon instead of full description ---
					if ( ( isset( $thisoption['help'] ) ) && ( '' != $thisoption['help'] ) ) {
						$controlargs['help'] = $controlargs['description'];
						unset( $controlargs['description'] );
					}

					// note Kirki extra options: output, js_vars, required
					// but Kirki documentation is still a bit sketchy on their usage
					// 1.8.5: fix for 'type' conflict - as already set by Kirki config
					// 1.9.8: but only attempt unset if array index is already set
					if ( isset( $settingargs['type'] ) ) {
						unset( $settingargs['type'] );
					}
					if ( isset( $settingargs['capability'] ) ) {
						unset( $settingargs['capability'] );
					}
					$controlargs = array_merge( $settingargs, $controlargs );
					// 1.9.5: do not use settingsprefix for Kirki 2.3.5 update
					$controlargs['setting'] = $thisoption['id'];
					// 2.0.9: set settings key (plural) for option ID
					$controlargs['settings'] = $thisoption['id'];
					bioship_debug( "Kirki Field", $controlargs );
					
					// TODO: check if still needed for Kirki 4 ?
					// (as apparently it is not explicitly needed any more,
					// but instead needs capability, option_name and option_type ?)
					// ref: https://kirki.org/docs/setup/config/
					// if ( 4 < (int) $vkirkiversion ) {
						Kirki::add_field( THEMEPREFIX, $controlargs );
					// }

				} else {

					// --- fallbacks to default Customizer Controls ---
					// 1.8.5: only for when Kirki is not loaded
					// 2.0.7: fix to key setting typo (sanitization_callback)
					if ( !isset($settingargs['sanitize_callback'] ) ) {
						$callback = '';
						if ( ( 'info' == $type ) || ( 'note' == $type ) || ( 'hidden' == $type ) || ( 'code' == $type ) ) {
						  	$callback = 'bioship_fallback_sanitize_unfiltered';
						} elseif ( 'select' == $type ) {
							$callback = 'bioship_fallback_sanitize_select';
						} elseif ( ( 'radio' == $type ) || ( 'images' == $type ) || ( 'radio-images' == $type ) ) {
							$callback = 'bioship_fallback_sanitize_radio';
						} elseif ( 'checkbox' == $type ) {
							$callback = 'bioship_fallback_sanitize_checkbox';
						} elseif ( 'multicheck' == $type ) {
							$callback = 'bioship_fallback_sanitize_multicheck';
						} elseif ( ( 'color' == $type ) || ( 'colorpicker' == $type ) || ( 'color-palette' == $type ) ) {
							$callback = 'bioship_fallback_sanitize_color';
						} elseif ( ( 'rgba' == $type ) || ( 'color-alpha' == $type ) ) {
							$callback = 'bioship_fallback_sanitize_rgba';
						} elseif ( ( 'upload' == $type ) || ( 'image' == $type ) || ( 'audio' == $type ) ) {
							$callback = 'bioship_fallback_sanitize_url';
						} elseif ( 'page-dropdown' == $type ) {
							$callback = 'bioship_fallback_sanitize_pagedropdown';
						} elseif ( 'textarea' == $type ) {
							$callback = 'bioship_fallback_sanitize_textarea';
						} elseif ( 'text' == $type ) {
							$callback = 'bioship_fallback_sanitize_unfiltered';
						}

						if ( THEMEDEBUG && ( '' == $callback ) ) {
							echo "<!-- WARNING: Missing Sanitization Callback for ".esc_attr($type)." Settings -->";
						}
						$settingargs['sanitize_callback'] = $callback;
					}

					// --- add the Customizer Setting ---
					// 2.0.7: fix to for sanitization callback requirement check
					$setting = array(
						'type'				=> $settingargs['type'],
						'capability'		=> $settingargs['capability'],
						'default'			=> $settingargs['default'],
						'sanitize_callback' => $settingargs['sanitize_callback'],
					);
					$wp_customize->add_setting($settingid, $setting );

					// --- add the Customizer Control ---
					if ( !in_array( $type, $defaulttypes ) ) {

						// --- info / note ---
						if ( ( 'info' == $type ) || ( 'note' == $type ) ) {
							// use our simple Info control class to output the label and description text
							$wp_customize->add_control(
								new Info_Custom_Control( $wp_customize, $settingid, $controlargs )
							);
						}
						// --- color / colorpicker ---
						if ( ( 'color' == $type ) || ( 'colorpicker' == $type ) ) {
							$wp_customize->add_control(
								new WP_Customize_Color_Control( $wp_customize, $settingid, $controlargs )
							);
						}

						// --- upload / image ---
						if ( ( 'upload' == $type ) || ( 'image' == $type ) ) {
							// TEST: could test the various image control options here...
							// add/modify one that also allows for simply pasting an URL as well?!
							// note: one cool idea is to add a *context* to the uploaded images also:
							// ref: https://gist.github.com/eduardozulian/4739075
							if ( class_exists( 'WP_Customize_Media_Control' ) ) {
								if ( 'image' == $type ) {
									// note: WP 4.1+ ... so maybe use version_compare?
									$args['mime_type'] = 'image';
								}
								$wp_customize->add_control(
									new WP_Customize_Media_Control ($wp_customize, $settingid, $controlargs )
								);
							} elseif ( 'upload' == $type ) {
								$wp_customize->add_control(
									new WP_Customize_Upload_Control( $wp_customize, $settingid, $controlargs )
								);
							} elseif ( 'image' == $type ) {
								$wp_customize->add_control(
									new WP_Customize_Image_Control( $wp_customize, $settingid, $controlargs )
								);
							}
						}

						// --- audio upload ---
						if ( 'audio' == $type ) {
							// not used here anyways, but added just for good old reference sake
							// $wp_customize->add_control(new WP_Customize_Upload_Control($wp_customize, $settingid, $controlargs));
							// note: WP 4.1+ ... so maybe use version_compare ?
							$controlargs['mime_type'] = 'audio';
							$wp_customize->add_control(
								new WP_Customize_Media_Control( $wp_customize, $settingid, $controlargs )
							);
						}

						// --- multicheck ---
						if ( 'multicheck' == $type ) {
							// this is (was) the Multicheck Control from Hybrid Core...
							// since a multicheck control is not a default WordPress one - madness!
							// ...but Hybrid Customize multicheck control not working either? :-/
							// $wp_customize->add_control(new Hybrid_Customize_Control_Checkbox_Multiple($wp_customize, $settingid, $controlargs));
							// 2.0.9: add standalone multicheck control class here instead
							$wp_customize->add_control(
								new Multicheck_Custom_Control( $wp_customize, $settingid, $controlargs )
							);
						}

						// --- textarea ---
						if ( 'textarea' == $type ) {
							// replacement textarea control, but should be fine either way
							if ( class_exists( 'Textarea_Custom_Control' ) ) {
								$wp_customize->add_control(
									new Textarea_Custom_Control( $wp_customize, $settingid, $controlargs )
								);
							} else {
								// 2.2.0: fix second argument (args)
								$wp_customize->add_control( $settingid, $controlargs );
							}
						}

						// if ( ($type == 'images') || ($type == 'radio-image') ) {
							// note plural images, singular image type is for an image upload
							// TEST: use the Hybrid radio-images Control here?
							// $wp_customize->add_control(new Hybrid_Customize_Control_Radio_Image($wp_customize, $settingid, $controlargs));
						// }

					} else {
						// --- fallback to adding a standard control type ---
						$wp_customize->add_control( $settingid, $controlargs );
					}
				}

				$priority++;
			}
		}
	}

	bioship_debug( "Customizer Control Types", $controltypes );
	bioship_debug( "Control Types Used", $types );
	bioship_debug( "WP CUSTOMIZE OBJECT", $wp_customize );

	// IDEA: maybe add Theme Pro Upgrade Link ?
	// (if/when there is a Premium Theme version)
	// $settingid = 'customizer_link';
	// $wp_customize->add_setting($settingid, array(
	// 	'type' => 'option',
	//  'capability' => 'edit_theme_options',
	//	'default' => ''
	//	'sanitize_callback' => 'bioship_fallback_sanitize_unfiltered'
	// ) );
	// $label = ''; $description = esc_attr(__('Upgrade Theme','bioship'));
	// $args = array('type' => 'info', 'priority' => '210', 'label' => $label, 'description' => $description, 'setting' => $settingid);
	// $wp_customize->add_control(new Info_Custom_Control($wp_customize, $settingid, $args));

	// well, that is just about enough of that nonsense!
	// -------------------------------------------------
 }
}


// ----------------------------
// === Customize Customizer ===
// ----------------------------

// -------------------------
// Custom Customizer Scripts
// -------------------------
// there really should be a core filter for this text... TRAC?
if ( !function_exists( 'bioship_customizer_text_script' ) ) {
 function bioship_customizer_text_script() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- add Kirki styling for non-Kirki ---
	// 2.0.9: consistency display fix for no Kirki loading (via Kirki branding.js)
	if ( !THEMEKIRKI ) {
		$config = bioship_customizer_kirki_styling( array() );
		echo "<script>jQuery(document).ready(function() {'use strict';
			jQuery('div#customize-info .preview-notice').replaceWith('<img src=\"" . esc_url( $config['logo_image'] ) . "\">');
			jQuery('div#customize-info > .customize-panel-description').replaceWith('<div class=\"customize-panel-description\">" . esc_js( $config['description'] ) . "</div>');
		});</script>";
	}

	// --- shift theme panel to bottom ---
	// 2.2.0: added fix since priority is not shifting the themes panel
	if ( THEMEWPORG ) {
		echo "<script>jQuery(document).ready(function() {setTimeout(function() {
			themesection = jQuery('#accordion-section-themes');
			if (themesection) {themesection.parent().append(themesection);
		} }, 3000) });</script>";
	}

	// --- just some rogue panel separators and styling ---
	// 2.1.4: prevent unecessary scrollbar appearing on info pane
	// 2.2.0: added bottom margin to option items for easier reading
	$styles = "#customize-info {overflow:hidden;}
	#accordion-panel-skinoptions, #accordion-section-title_tagline {border-top: 20px solid #F0F0F0 !important;}
	#accordion-panel-skeletonoptions {border-bottom: 20px solid #F0F0F0 !important;}
	#customize-theme-controls .accordion-section-content {background-color: #E0E0EE !important;}
	#customize-info .customize-panel-description {background-color: #FDFDFF !important;}
	#customize-controls .customize-info {margin-bottom:0px !important;}
	#customize-info .customize-help-toggle {margin-top: 70px;}
	#accordion-panel-skinoptions ul li ul li, #accordion-panel-muscleoptions ul li ul li,
	#accordion-panel-skeletonoptions ul li ul li {margin-bottom: 15px;}";

	// 1.9.9: enforce panel views for advanced options page (prevent auto-hiding glitch)
	if ( isset( $_REQUEST['options'] ) && ( 'advanced' == $_REQUEST['options'] ) ) {
		$styles .= PHP_EOL . "#accordion-panel-nav_menus {display: none !important;}";
	}
	// 2.2.0: fix to force options display for all options page as well as advanced page
	$forcedisplay = array( 'advanced', 'all', 'both' );
	if ( isset( $_REQUEST['options'] ) && in_array( $_REQUEST['options'], $forcedisplay ) ) {
		$styles .= PHP_EOL . "#accordion-panel-skinoptions, #accordion-panel-skinoptions ul li,
		#accordion-panel-muscleoptions, #accordion-panel-muscleoptions ul li,
		#accordion-panel-skeletonoptions, #accordion-panel-skeletonoptions ul li {display: list-item !important;}";
	}

	// --- filter and output styles ---
	// 1.8.5: added a style rule filter here
	$styles = bioship_apply_filters( 'options_customizer_extra_styles', $styles );
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "<style>" . $styles . "</style>" . PHP_EOL;

	// --- change Customizer title message ---
	// default: 'The Customizer allows you to preview changes to your site before publishing them. You can also navigate to different pages on your site to preview them.<br>';
	$message = esc_html( __( 'The Customizer lets you preview live style changes before applying them. You can also navigate to preview other pages on your site.', 'bioship' ) ) . '<br>';

	// --- add a link to the theme options page ---
	// TODO: check if Titan framework plugin is installed but not active
	$titan = bioship_file_hierarchy( 'file', 'titan-framework.php', array( 'include/titan','titan' ) );
	if ( class_exists( 'TitanFramework') || $titan ) {
		// 1.9.9: fixed URL, shortened Titan Framework link message
		// 2.2.0: use add_query arg to generate link
		$themesettingslink = add_query_arg( 'page', 'bioship-options', admin_url( 'admin.php') );
		$custommessage = '<br>' .  __(  'Feeling restricted?', 'bioship' ) . '<br>';
		$custommessage .= '<a href="' . esc_url( $themesettingslink) . '">';
		$custommessage .= esc_html( __( 'Access All Options via Titan', 'bioship' ) ) . '</a>.';
	} else {
		// --- generate Titan Framework install link ---
		// (originally via TGM Plugin Activation)
		// $titaninstall = admin_url('themes.php').'?page=tgmpa-install-plugins';
		// $titaninstall = wp_nonce_url( add_query_arg(array('plugin' => urlencode('titan-framework'),'tgmpa-install-plugin'), $titaninstall), 'tgmpa-install', 'tgmpa-nonce' );
		// 1.8.5: use direct install method via standalone admin function
		// 1.9.9: shortened the Titan Framework install message
		// 2.2.0: use add_query_arg to generate link
		$titaninstalllink = add_query_arg( 'admin_install_titan_framework', 'yes', admin_url( 'themes.php' ) );
		$custommessage = esc_html( __( 'Feel restricted?', 'bioship' ) ) . ' <a href="' . esc_url( $titaninstalllink ) . '">';
		$custommessage .= esc_html( __('Install Titan Framework', 'bioship' ) ) . '</a>.<br>';
		$custommessage .= esc_html( __( 'To access All Options via Titan', 'bioship' ) ) . '.';
	}

	// --- set combined Customizer message ---
	$customizermessage = $message . $custommessage;
	$customizermessage = bioship_apply_filters( 'options_customizer_description', $customizermessage );
	// 2.0.5: remove any single quotes that would break javascript insert
	$customizermessage = str_replace( "'", "", $customizermessage );

	// --- preview notice title section text ---
	// 2.0.7: added missing translation text domain
	$extratext = '<span class="preview-notice" style="float:right; max-width:40%;">';
	$extratext .= sprintf( esc_html( __( 'You are customizing %s', 'bioship' ) ), '<strong class="panel-title site-title">' . get_bloginfo( 'name' ) . '</strong>');
	$extratext .= '</span>';

	// --- check if split options ---
	// 1.9.9: filter whether splitting options
	$splitoptions = false; // 2.2.0: TEMP DISABLED
	$splitoptions = bioship_apply_filters( 'options_customizer_split_options', false );
	if ( $splitoptions ) {

		// 1.9.9: use this section to display option page links
		// 2.1.1: simplified append of return querystring argument
		$optionspage = 'all';
		if ( isset( $_REQUEST['options'] ) ) {
			$valid = array( 'basic', 'advanced', 'all' );
			if ( in_array( $_REQUEST['options'], $valid ) ) {
				$optionspage = $_REQUEST['options'];
			}
		}
		$return = '';
		if ( isset( $_REQUEST['return'] ) ) {
			$return = urlencode( $_REQUEST['return'] );
		}
		
		// 2.2.0: added possible theme argument
		$theme = '';
		if ( isset( $_REQUEST['theme'] ) ) {
			$theme = $_REQUEST['theme'];
		}

		// 2.2.0: use add_query_arg to generate link URLs
		$custommessage = '<b>' . esc_html(  __( 'Options','bioship' ) ) . '</b>:<br>';
		if ( 'basic' == $optionspage ) {
			$custommessage .= '<b>' . esc_html( __( 'General', 'bioship' ) ) . '</b><br>';
		} else {
			$customurl = add_query_arg( 'options', 'basic', admin_url( 'customize.php' ) );
			if ( '' != $return ) {
				$customurl = add_query_arg( 'return', $return, $customurl );
			}
			if ( '' != $theme ) {
				$customurl = add_query_arg( 'theme', $theme, $customurl );
			}
			$custommessage .= '<a href="' . esc_url( $customurl ) . '">' . __( 'General', 'bioship' ) . '</a><br>';
		}
		if ( 'advanced' == $optionspage ) {
			$custommessage .= '<b>' . esc_html( __( 'Advanced', 'bioship' ) ) . '</b><br>';
		} else {
			$customurl = add_query_arg( 'options', 'advanced', admin_url( 'customize.php' ) );
			if ( '' != $return ) {
				$customurl = add_query_arg( 'return', $return, $customurl );
			}
			if ( '' != $theme ) {
				$customurl = add_query_arg( 'theme', $theme, $customurl );
			}
			$custommessage .= '<a href="' . esc_url( $customurl ) . '">' . __( 'Advanced', 'bioship' ) . '</a><br>';
		}
		if ( 'all' == $optionspage ) {
			$custommessage .= '<b>' . esc_html( __( 'All','bioship' ) ) . '</b><br>';
		} else {
			// 2.2.0: fix to broken link for all options
			$customurl = add_query_arg( 'options', 'all', admin_url( 'customize.php' ) );
			if ( '' != $return ) {
				$customurl = add_query_arg( 'return', $return, $customurl );
			}
			if ( '' != $theme ) {
				$customurl = add_query_arg( 'theme', $theme, $customurl );
			}
			$custommessage .= '<a href="' . esc_url( $customurl ) . '">' . __( 'All', 'bioship' ) . '</a><br>';
		}
		$extratext = '<span class="preview-notice" style="float:right; max-width:45%; line-height:16pt;">';
		$extratext .= $custommessage . '</span>';
	}
	$extratext = bioship_apply_filters( 'options_customizer_titletext', $extratext );
	// 2.0.5: maybe remove single quotes that would break javascript insert
	$extratext = str_replace( "'", "", $extratext );

	// --- jQuery to update the customizer message ---
	echo "<script>jQuery(document).ready(function($) {";
	echo "	$('#customize-info button.customize-help-toggle').click();";
	// note: no escape here as mangles HTML
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "	$('#customize-info .customize-panel-description').html('" . $customizermessage . "');";
	if ( '' != $extratext ) {
		// note: no escape here as mangles HTML
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "extratext = '" . $extratext . "';";
		echo "$('#customize-info .accordion-section-title').append(extratext);";
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
			/* if (customizerdebug) {console.log(newwidth+'-'+newamount+'-'+widthsuffix);} */
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

			/* TODO: extra top/bottom sidebar jQuery CSS rules */
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
 	// ...as something very different would need to be done with the sections / panels
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

	// --- output styles for the sidebar controls ---
	// 2.2.0: add sidebar arrow font family for proper triangle display
	echo "<style>#customizer-sidebar-size-controls {position:absolute; top:0; left:30px;}
	#customizer-sidebar-position-controls {position:absolute; top:0; left:0;}
	#customizer-sidebar-position-table {width:120px; height:100px; margin-top:-7px; margin-left:-5px;}
	.customizer-sidebar-updown-arrows {font-size:12pt; line-height:18px;}
	.customizer-sidebar-controls, #sidebardecreaser, #sidebarincreaser {font-size:14pt; font-weight:bold; float:left; display:inline-block;}
	#sidebarleft, #sidebarright, #sidebartop, #sidebarbottom, #sidebardecreaser, #sidebarincreaser {text-decoration:none;}
	.sidebararrow {font-family: Verdana, sans-serif;}
	</style>";

 }
}

// ------------------------------
// Main Options Panel Display Fix
// ------------------------------
// 2.0.9: fix for something (Customizer?!) setting option panels to display:none
// TODO: check whether this fix is still needed or not ?
if (!function_exists('bioship_customizer_panel_display_fix')) {

 add_action( 'customize_controls_print_footer_scripts', 'bioship_customizer_panel_display_fix', 99 );

 function bioship_customizer_panel_display_fix() {
	echo "<script>jQuery(document).ready(function($) {
		setTimeout(function() {
			\$('#accordion-panel-skinoptions').css('display','list-item');
			\$('#accordion-panel-muscleoptions').css('display','list-item');
			\$('#accordion-panel-skeletonoptions').css('display','list-item');
		}, 5000);
	});</script>";
 }
}

// -------------------------------------
// Callbacks for Customizer Live Preview
// -------------------------------------
// ref: http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
// load via the customize preview init hook, but put scripts in the footer...
// also this: https://github.com/aristath/kirki/wiki/Automating-CSS-output
// and this: https://github.com/aristath/kirki/wiki/Automating-postMessage-scripts

if ( !function_exists( 'bioship_customizer_preview' ) ) {
 function bioship_customizer_preview() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemename, $vthemeoptions, $vcsscachebust, $vthemedirs;
	$vthemesettings = maybe_unserialize(get_option( THEMEKEY ) );

	// 2.2.0: load theme options for Live Preview
	if ( !isset( $vthemeoptions ) || !is_array( $vthemeoptions ) ) {
		if ( !function_exists( 'bioship_options' ) ) {
			$options = bioship_file_hierarchy( 'file', 'options.php' );
			include_once $options;
		}
		$vthemeoptions = bioship_options();
	}

	// --- Set Skin URL ---
	$cssmode = $vthemesettings['themecssmode'];
	if ( 'adminajax' == $cssmode ) {
		// 2.2.0: use add_query_arg to generate skin URL
		$skinurl = add_query_arg( 'action', 'skin_dynamic_css', admin_url( 'admin-ajax.php' ) );
	} else {
		$skinurl = bioship_file_hierarchy( 'url', 'skin.php', $vthemedirs['core'] );
	}
	// 2.0.5: add querystring arguments to skin URL early
	$skinurl = add_query_arg( 'ver', $vcsscachebust, $skinurl );
	$skinurl = add_query_arg( 'livepreview', 'yes', $skinurl );

	// --- set typography keys ---
	$typography = array( 'color', 'font-size', 'font-family', 'font-style' );
	if ( THEMETITAN ) {
		$vthemename = $vthemename . '_customize';
		$typography[] = 'font-weight';
		$typography[] = 'line-height';
		$typography[] = 'letter-spacing';
		$typography[] = 'text-transform';
		$typography[] = 'font-variant';
	}

	// --- start jQuery customizer live preview script ---
	// 1.8.5: added footer credits live preview
	// 2.0.9: set javascript console debug variable
	$debug = 'false';
	if ( THEMEDEBUG ) {
		$debug = 'true';
	}
	echo "<script>( function(\$) {
		var customizerdebug = " . esc_js( $debug ) . "; var buttontop = ''; var buttonbottom = '';
		wp.customize('blogname', function(value) {	value.bind(function(to) {\$('#site-title-text a').html(to);}); });
		wp.customize('blogdescription', function(value) { value.bind(function(to) {\$('#site-description .site-desc').html(to);}); });
		wp.customize('" . esc_js( $vthemename ) . "[sitecredits]', function(value) { value.bind(function(to) {if (to === '0') {to = '';} \$('#footercredits').html(to);}); });
    ";

	// note: helpful function reference for adding hover events...
 	//	function setPreviewHover(obj, mouseenter, mouseleave) {
	//		obj.data('_mouseenter', mouseenter); obj.data('_mouseleave', mouseleave);
	//		obj.hover(obj.data('_mouseenter'), obj.data('_mouseleave'));
	//	}

	$typojs = '';
	foreach ( $vthemeoptions as $option ) {

		// --- bind new stylesheet ---
		// 1.8.5: send dynamic CSS to header/footer or skin.php
		if ( isset ($option['id'] ) && ( 'dynamiccustomcss' == $option['id'] ) ) {
			$settingid = $vthemename . '[' . $option['id'] . ']';
			echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
				value.bind(function(to) {
					var skinhref = '" . esc_url( $skinurl ) . "';
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

		// --- grid stylesheet ---
		// TODO: add new grid URL arguments to querystring of @import?
		if ( isset( $option['id'] ) ) {
			$id = $option['id'];
			// TODO: recheck grid reloading options and transports
			// refresh only: layout, gridcolumns, content_width
			// postMessage: breakpoints, gridcompatibility, contentpadding
			if ( ( 'breakpoints' == $id ) || ( 'gridcompatibility' == $id ) || ( 'content_width' == $id ) ) {
				$settingid = $vthemename . '[' . $option['id'] . ']';
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {
						if (document.getElementById('grid-url')) {
							gridhref = \$('#grid-url').href; \$('#grid-css').remove();
							if (gridhref.indexOf('livepreview=yes') == -1) {gridhref += '&livepreview=yes';}
							newstylesheet = document.createElement('style');
							newstylesheet.setAttribute('id','grid-css');
							newstylesheet.textContent = '@import('+gridhref+');';
							document.getElementsByTagName('head')[0].appendChild(newstylesheet);
						}
					});
				});" . PHP_EOL;
			}
		}

		// --- other CSS property rules... ---
		if ( isset( $option['csselement'] ) && isset( $option['cssproperty'] ) ) {

			// --- typography multiple CSS values ---
			// note: stored and re-inserted shorted...
			$settingid = $vthemename . '[' . $option['id'] . ']';
			if ( $option['cssproperty'] == 'typography' ) {
				foreach ( $typography as $typooption ) {
					$typosetting = $settingid . '[' . $typooption . ']';
					$typojs .= "wp.customize('" . esc_js( $typosetting ) . "',function(value) {
						value.bind(function(to) {
							\$('" . esc_js( $option['csselement'] ) . "').css('" . esc_js( $typooption ) . "',to);
							if (customizerdebug) {
								console.log('Typography Change: " . esc_js( $option['csselement'] ) . " - " . esc_js( $typooption ) . " - '+to);
							}
						});
					});" . PHP_EOL;
				}

			} elseif ( '#header h1#site-title-text a' == $option['csselement'] ) {

				// --- site title ---
				// 1.8.5: text header hide/show display values
				// 2.2.0: split title/description option
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {
						if (to) {\$('#header h1#site-title-text a').fadeIn();}
						else {\$('#header h1#site-title-text a').fadeOut();}
					});
				});" . PHP_EOL;

			} elseif ( '#site-description .site-desc' == $option['csselement'] ) {

				// --- site description ---
				// 1.8.5: text header hide/show display values
				// 2.2.0: split title/description option
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {
						if (to) {\$('#site-description .site-desc').fadeIn();}
						else {\$('#site-description .site-desc').fadeOut();}
					});
				});" . PHP_EOL;

			} elseif ( '#site-logo' == $option['csselement'] ) {

				// --- site logo ---
				// 1.8.5: update logo and show/hide image (and/or text) depending on condition
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {
						\$('#site-logo .logo-image').attr('src',to);
						if (to == '') {\$('#site-logo .site-logo-image').hide();}
						else {\$('#site-logo .site-logo-image').show();}
					});
				});" . PHP_EOL;

			} elseif ( ( strstr($option['csselement'], 'body button' ) ) && ( strstr( $option['cssproperty'], 'background' ) ) ) {

				// --- button gradients ---
				// 1.8.5: handle button gradient changes
				if ( !strstr( $option['cssproperty'], ':hover' ) ) {
					if ( 'backgroundtop' == $option['cssproperty'] ) {
						$top = 'to';
						$bottom = $vthemesettings['button_bgcolor_bottom'];
					} elseif ( 'backgroundbottom' == $option['cssproperty'] ) {
						$bottom = 'to';
						$top = $vthemesettings['button_bgcolor_top'];
					}

					echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
						value.bind(function(to) {
							buttons = \$('" . esc_js( $option['csselement'] ) . "'); btntop = '" . esc_js( $top ) . "'; btnbot = '" . esc_js( $bottom ) . "';
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
							if (customizerdebug) {
								console.log('Button Style: " . esc_js( $option['csselement'] ) . " - '+btntop+' - '+btnbot);
							}
						});
					});" . PHP_EOL;
				}

				// --- button hover gradients ---
				// 1.8.5: hover button gradients preview...
				if ( strstr( $option['cssproperty'], ':hover' ) ) {

					if ( 'backgroundtop:hover' == $option['cssproperty'] ) {
						$top = 'to';
						$bottom = $vthemesettings['button_hoverbg_bottom'];
					} elseif ( 'backgroundbottom:hover' == $option['cssproperty'] ) {
						$bottom = 'to';
						$top = $vthemesettings['button_hoverbg_top'];
					}

					echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
						value.bind(function(to) {
							btntop = '" . esc_js( $top ) . "'; btnbot = '" . esc_js( $bottom ) . "';
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
							\$('" . esc_js( $option['csselement'] ) . "').each(function() {\$(this).addClass('buttonhoverpreview');});
						});
					});" . PHP_EOL;
				}

			} elseif ( strstr( $option['csselement'], ':hover' ) ) {

				// --- link hover elemetns ---
				// 1.8.5: handle hover elements (ie. links)
				$option['csselement'] = str_replace( ':hover', '', $option['csselement'] );
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {
						from = \$('" . esc_js( $option['csselement'] ) . "').css('" . esc_js( $option['cssproperty'] ) . "');
						\$('" . esc_js( $option['csselement'] ) . "').hover(
							function() {\$(this).css('" . esc_js( $option['cssproperty'] ) . "',to);},
							function() {\$(this).css('" . esc_js( $option['cssproperty'] ) . "',from)
						});
						if (customizerdebug) {
							console.log('Hover Style: " . esc_js( $option['csselement'] ) . " - " . esc_js( $option['cssproperty'] ) . " - '+to);
						}
					});
				});" . PHP_EOL;

			} else {

				// --- any other singular CSS rule value ---
				// 1.8.5: fix for background-image CSS property preview
				echo "wp.customize('" . esc_js( $settingid ) . "',function(value) {
					value.bind(function(to) {";
						if ( 'background-image' == $option['cssproperty'] ) {
							echo "to = 'url('+to+')';";
						}
						echo "\$('" . esc_js( $option['csselement'] ) . "').css('" . esc_js( $option['cssproperty'] ) . "',to);
						if (customizerdebug) {
							console.log('Style Element: " . esc_js( $option['csselement'] ) . " - " . esc_js( $option['cssproperty'] ) . " - '+to);
						}
					});
				});" . PHP_EOL;
			}
		}
	}

	// --- insert all typography javascript last ---
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo $typojs;

	// --- end jQuery live preview functions ---
	echo "} )(jQuery)</script>";
 }
}


// --------------------------
// === Customizer Helpers ===
// --------------------------

// ------------------------------
// Default Sanitization Fallbacks
// ------------------------------
// (most of these are just pared down/modified from Kirki sanitization)
// 1.8.5: added these fallbacks for if Kirki is not loaded
// 2.0.5: added function_exists wrappers (for possible fix overrides)
// --- sanitize unfiltered ---
if ( !function_exists( 'bioship_fallback_sanitize_unfiltered' ) ) {
	function bioship_fallback_sanitize_unfiltered( $value ) {
		return $value;
	}
}
// --- sanitize radio input ---
if ( !function_exists( 'bioship_fallback_sanitize_radio' ) ) {
	function bioship_fallback_sanitize_radio( $value ) {
		return esc_attr( $value );
	}
}
// --- sanitize textarea input ---
if ( !function_exists( 'bioship_fallback_sanitize_textarea' ) ) {
	function bioship_fallback_sanitize_textarea( $value ) {
		return esc_textarea( $value );
	}
}
// --- sanitize URL ---
if ( !function_exists( 'bioship_fallback_sanitize_url' ) ) {
	function bioship_fallback_sanitize_url( $value ) {
		return esc_raw_url( $value );
	}
}
// --- sanitize number ---
if ( !function_exists( 'bioship_fallback_sanitize_number' ) ) {
	function bioship_fallback_sanitize_number( $value ) {
		return ( is_numeric( $value ) ) ? $value : intval( $value );
	}
}
// --- sanitize serialized value ---
if ( !function_exists( 'bioship_fallback_sanitize_serialized' ) ) {
	function bioship_fallback_sanitize_serialized( $value ) {
		if ( is_serialized( $value ) ) {
			return $value;
		} else {
			return serialize( $value );
		}
	}
}
// --- sanitize select option ---
if ( !function_exists( 'bioship_fallback_sanitize_select' ) ) {
	function bioship_fallback_sanitize_select( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $subvalue ) {
				$value[ $key ] = esc_attr( $subvalue );
			}
			return $value;
		}
		return esc_attr( $value );
	}
}
// --- sanitize checkbox value ---
if ( !function_exists( 'bioship_fallback_sanitize_checkbox' ) ) {
	function bioship_fallback_sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && ( true == $checked || 'on' == $checked ) ) ? true : false );
	}
}
// --- sanitize multicheck values ---
if ( !function_exists( 'bioship_fallback_sanitize_multicheck' ) ) {
	function bioship_fallback_sanitize_multicheck( $values ) {
		$multi_values = ( ! is_array( $values ) ) ? explode( ',', $values ) : $values;
		return ( ! empty( $multi_values ) ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	}
}
// --- sanitize page dropdown ---
if ( !function_exists( 'bioship_fallback_sanitize_pagedropdown' ) ) {
	function bioship_fallback_sanitize_pagedropdown( $page_id, $setting ) {
		$page_id = absint( $page_id );
		return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}
}
// --- sanitize CSS size ---
if ( !function_exists( 'bioship_fallback_sanitize_css_size' ) ) {
	function bioship_fallback_sanitize_css_size( $value ) {
		$value = trim( $value );
		if ( 'round' === $value ) {
			$value = '50%';
		}
		if ( ( '' === $value ) || ( 'auto' === $value ) ) {
			return $value;
		}
		if ( ! preg_match( '#[0-9]#' , $value ) ) {
			return '';
		}
		if ( false !== strpos( $value, 'calc(' ) ) {
			return $value;
		}

		$raw_value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$unit_used = '';
		$units = array( 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' );
		foreach ( $units as $unit ) {
			if ( false !== strpos( $value, $unit ) ) {
				$unit_used = $unit;
			}
		}
		if ( ( 'em' === $unit_used ) && ( false !== strpos( $value, 'rem' ) ) ) {
			$unit_used = 'rem';
		}
		return $raw_value . $unit_used;
	}
}
// --- sanitize color value ---
if ( !function_exists( 'bioship_fallback_sanitize_color' ) ) {
	function bioship_fallback_sanitize_color( $value ) {
		if ( '' === $value ) {
			return '';
		}
		if ( is_string( $value ) && ( 'transparent' === trim( $value ) ) ) {
			return 'transparent';
		}
		if ( false === strpos( $value, 'rgba' ) ) {
			return bioship_fallback_sanitize_hex( $value );
		} else {
			return bioship_fallback_sanitize_rgba( $value );
		}
	}
}
// --- sanitize rgba color value ---
if ( !function_exists( 'bioship_fallback_sanitize_rgba' ) ) {
	function bioship_fallback_sanitize_rgba( $value ) {
		if ( false === strpos( $value, 'rgba' ) ) {
			return bioship_fallback_sanitize_color ( $value );
		}
		$value = str_replace( ' ', '', $value );
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}
// --- sanitize hex color value ---
if ( !function_exists( 'bioship_fallback_sanitize_hex' ) ) {
	function bioship_fallback_sanitize_hex( $color ) {
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

// ----------------------
// Translate Kirki Labels
// ----------------------
// 1.8.5: added this filter
// 1.9.8: fixed missing quotes on text domain
// 1.9.9: use as a filter function directly
if ( !function_exists( 'bioship_customizer_i10n' ) ) {
 function bioship_customizer_i10n($l10n) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	$l10n = array(
		'background-color'      => esc_attr__( 'Background Color', 'bioship' ),
		'background-image'		=> esc_attr__( 'Background Image', 'bioship' ),
		'no-repeat'				=> esc_attr__( 'No Repeat', 'bioship' ),
		'repeat-all'			=> esc_attr__( 'Repeat All', 'bioship' ),
		'repeat-x'				=> esc_attr__( 'Repeat Horizontally', 'bioship' ),
		'repeat-y'				=> esc_attr__( 'Repeat Vertically', 'bioship' ),
		'inherit'				=> esc_attr__( 'Inherit', 'bioship' ),
		'background-repeat'		=> esc_attr__( 'Background Repeat', 'bioship' ),
		'cover'					=> esc_attr__( 'Cover', 'bioship' ),
		'contain'				=> esc_attr__( 'Contain', 'bioship' ),
		'background-size'		=> esc_attr__( 'Background Size', 'bioship' ),
		'fixed'					=> esc_attr__( 'Fixed', 'bioship' ),
		'scroll'				=> esc_attr__( 'Scroll', 'bioship' ),
		'background-attachment'	=> esc_attr__( 'Background Attachment', 'bioship' ),
		'left-top'				=> esc_attr__( 'Left Top', 'bioship' ),
		'left-center'			=> esc_attr__( 'Left Center', 'bioship' ),
		'left-bottom'			=> esc_attr__( 'Left Bottom', 'bioship' ),
		'right-top'				=> esc_attr__( 'Right Top', 'bioship' ),
		'right-center'			=> esc_attr__( 'Right Center', 'bioship' ),
		'right-bottom'			=> esc_attr__( 'Right Bottom', 'bioship' ),
		'center-top'			=> esc_attr__( 'Center Top', 'bioship' ),
		'center-center'			=> esc_attr__( 'Center Center', 'bioship' ),
		'center-bottom'			=> esc_attr__( 'Center Bottom', 'bioship' ),
		'background-position'	=> esc_attr__( 'Background Position', 'bioship' ),
		'background-opacity'	=> esc_attr__( 'Background Opacity', 'bioship' ),
		'on'					=> esc_attr__( 'ON', 'bioship' ),
		'off'					=> esc_attr__( 'OFF', 'bioship' ),
		'all'					=> esc_attr__( 'All', 'bioship' ),
		'cyrillic'				=> esc_attr__( 'Cyrillic', 'bioship' ),
		'cyrillic-ext'			=> esc_attr__( 'Cyrillic Extended', 'bioship' ),
		'devanagari'			=> esc_attr__( 'Devanagari', 'bioship' ),
		'greek'					=> esc_attr__( 'Greek', 'bioship' ),
		'greek-ext'				=> esc_attr__( 'Greek Extended', 'bioship' ),
		'khmer'					=> esc_attr__( 'Khmer', 'bioship' ),
		'latin'					=> esc_attr__( 'Latin', 'bioship' ),
		'latin-ext'				=> esc_attr__( 'Latin Extended', 'bioship' ),
		'vietnamese'			=> esc_attr__( 'Vietnamese', 'bioship' ),
		'hebrew'				=> esc_attr__( 'Hebrew', 'bioship' ),
		'arabic'				=> esc_attr__( 'Arabic', 'bioship' ),
		'bengali'				=> esc_attr__( 'Bengali', 'bioship' ),
		'gujarati'				=> esc_attr__( 'Gujarati', 'bioship' ),
		'tamil'					=> esc_attr__( 'Tamil', 'bioship' ),
		'telugu'				=> esc_attr__( 'Telugu', 'bioship' ),
		'thai'					=> esc_attr__( 'Thai', 'bioship' ),
		'serif'					=> esc_attr( _x( 'Serif', 'font style', 'bioship' ) ),
		'sans-serif'			=> esc_attr( _x( 'Sans Serif', 'font style', 'bioship' ) ),
		'monospace'				=> esc_attr( _x( 'Monospace', 'font style', 'bioship' ) ),
		'font-family'			=> esc_attr__( 'Font Family', 'bioship' ),
		'font-size'				=> esc_attr__( 'Font Size', 'bioship' ),
		'font-weight'			=> esc_attr__( 'Font Weight', 'bioship' ),
		'line-height'			=> esc_attr__( 'Line Height', 'bioship' ),
		'font-style'			=> esc_attr__( 'Font Style', 'bioship' ),
		'letter-spacing'		=> esc_attr__( 'Letter Spacing', 'bioship' ),
		'top'					=> esc_attr__( 'Top', 'bioship' ),
		'bottom'				=> esc_attr__( 'Bottom', 'bioship' ),
		'left'					=> esc_attr__( 'Left', 'bioship' ),
		'right'					=> esc_attr__( 'Right', 'bioship' ),
		'color'					=> esc_attr__( 'Color', 'bioship' ),
		'add-image'				=> esc_attr__( 'Add Image', 'bioship' ),
		'change-image'			=> esc_attr__( 'Change Image', 'bioship' ),
		'remove'				=> esc_attr__( 'Remove', 'bioship' ),
		'no-image-selected'		=> esc_attr__( 'No Image Selected', 'bioship' ),
		'select-font-family'	=> esc_attr__( 'Select a font-family', 'bioship' ),
		'variant'				=> esc_attr__( 'Variant', 'bioship' ),
		'subsets'				=> esc_attr__( 'Subset', 'bioship' ),
		'size'					=> esc_attr__( 'Size', 'bioship' ),
		'height'				=> esc_attr__( 'Height', 'bioship' ),
		'spacing'				=> esc_attr__( 'Spacing', 'bioship' ),
		'ultra-light'			=> esc_attr__( 'Ultra-Light 100', 'bioship' ),
		'ultra-light-italic'	=> esc_attr__( 'Ultra-Light 100 Italic', 'bioship' ),
		'light'					=> esc_attr__( 'Light 200', 'bioship' ),
		'light-italic'			=> esc_attr__( 'Light 200 Italic', 'bioship' ),
		'book'					=> esc_attr__( 'Book 300', 'bioship' ),
		'book-italic'			=> esc_attr__( 'Book 300 Italic', 'bioship' ),
		'regular'				=> esc_attr__( 'Normal 400', 'bioship' ),
		'italic'				=> esc_attr__( 'Normal 400 Italic', 'bioship' ),
		'medium'				=> esc_attr__( 'Medium 500', 'bioship' ),
		'medium-italic'			=> esc_attr__( 'Medium 500 Italic', 'bioship' ),
		'semi-bold'				=> esc_attr__( 'Semi-Bold 600', 'bioship' ),
		'semi-bold-italic'		=> esc_attr__( 'Semi-Bold 600 Italic', 'bioship' ),
		'bold'					=> esc_attr__( 'Bold 700', 'bioship' ),
		'bold-italic'			=> esc_attr__( 'Bold 700 Italic', 'bioship' ),
		'extra-bold'			=> esc_attr__( 'Extra-Bold 800', 'bioship' ),
		'extra-bold-italic'		=> esc_attr__( 'Extra-Bold 800 Italic', 'bioship' ),
		'ultra-bold'			=> esc_attr__( 'Ultra-Bold 900', 'bioship' ),
		'ultra-bold-italic'		=> esc_attr__( 'Ultra-Bold 900 Italic', 'bioship' ),
		'invalid-value'			=> esc_attr__( 'Invalid Value', 'bioship' ),
	);

	return $l10n;
 }
}

// ---------------------------
// Set Control Types (Kirki 2)
// ---------------------------
// TODO: maybe deprecate as using Kirki 3 now ?
if ( !function_exists( 'bioship_kirki_control_types' ) ) {
 function bioship_kirki_control_types() {

 	// --- set control types ---
	$controltypes = array(
		'kirki-checkbox'        => 'Kirki_Controls_Checkbox_Control',
		'kirki-code'            => 'Kirki_Controls_Code_Control',
		'kirki-color'           => 'Kirki_Controls_Color_Control',
		'kirki-color-palette'   => 'Kirki_Controls_Color_Palette_Control',
		'kirki-custom'          => 'Kirki_Controls_Custom_Control',
		'kirki-date'            => 'Kirki_Controls_Date_Control',
		'kirki-dashicons'       => 'Kirki_Controls_Dashicons_Control',
		'kirki-dimension'       => 'Kirki_Controls_Dimension_Control',
		'kirki-editor'          => 'Kirki_Controls_Editor_Control',
		'kirki-multicolor'      => 'Kirki_Controls_Multicolor_Control',
		'kirki-multicheck'      => 'Kirki_Controls_MultiCheck_Control',
		'kirki-number'          => 'Kirki_Controls_Number_Control',
		'kirki-palette'         => 'Kirki_Controls_Palette_Control',
		'kirki-preset'          => 'Kirki_Controls_Preset_Control',
		'kirki-radio'           => 'Kirki_Controls_Radio_Control',
		'kirki-radio-buttonset' => 'Kirki_Controls_Radio_ButtonSet_Control',
		'kirki-radio-image'     => 'Kirki_Controls_Radio_Image_Control',
		'repeater'              => 'Kirki_Controls_Repeater_Control',
		'kirki-select'          => 'Kirki_Controls_Select_Control',
		'kirki-slider'          => 'Kirki_Controls_Slider_Control',
		'kirki-sortable'        => 'Kirki_Controls_Sortable_Control',
		'kirki-spacing'         => 'Kirki_Controls_Spacing_Control',
		'kirki-switch'          => 'Kirki_Controls_Switch_Control',
		'kirki-generic'         => 'Kirki_Controls_Generic_Control',
		'kirki-toggle'          => 'Kirki_Controls_Toggle_Control',
		'kirki-typography'      => 'Kirki_Controls_Typography_Control',
		'kirki-dropdown-pages'  => 'Kirki_Controls_Dropdown_Pages_Control',
		'image'                 => 'WP_Customize_Image_Control',
		'cropped_image'         => 'WP_Customize_Cropped_Image_Control',
		'upload'                => 'WP_Customize_Upload_Control',
	);

	// --- filter and return control types ---
	$controltypes = apply_filters( 'kirki/control_types', $controltypes );
	foreach ( $controltypes as $key => $classname ) {
		if ( !class_exists( $classname ) ) {
			unset( $controltypes[$key] );
		}
	}
	return $controltypes;
 }
}
