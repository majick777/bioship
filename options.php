<?php

// =============================
// === BioShip Theme Options ===
// ==== for Titan Framework ====
// === and Options Framework ===
// == and WordPress Customizer =
// =============================

// -----------------------------
// === options.php Structure ===
// -----------------------------
// === Titan Framework Integration ===
// - Convert Options Framework to Titan
// === Options Framework Integration ===
// - Options Framework Option Name
// - Options Framework Resource URL Fix
// - Admin Stickykit Enqueue
// === Font Options ===
// - Default Web Font Stacks
// - Default Google Title Fonts
// - Title Font Display
// - Display Body Fonts
// - Get Image Sizes
// === Set All Theme Options ===
// -----------------------------

// 1.8.0: moved all UI / admin pages to admin.php
// (Framework specific integrations remain here)

// Development List
// ----------------
// - use sprintf on option descriptions with links
// - mark some non-theme territory options for removal
// -- eg. admin all options submenu item, RSS ...


// -----------------------------------
// === Titan Framework Integration ===
// -----------------------------------

// ----------------------------------
// Convert Options Framework to Titan
// ----------------------------------
// note: do not change, used for checking in functions.php
if ( !function_exists( 'bioship_optionsframework_to_titan' ) ) {
 function bioship_optionsframework_to_titan() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemename, $vthemetitan, $vthemeoptions;
	$vthemeoptions = bioship_options();

	$vthemetitan = TitanFramework::getInstance( $vthemename );
	$titanoptionkey = $vthemetitan->optionNamespace;
	bioship_debug( "Titan Object", $vthemetitan );
	bioship_debug( "Titan Settings", $vthemetitan->settings );
	bioship_debug( "Titan Option Namespace", $titanoptionkey );

	// 1.9.5: do NOT output generated styles, thanks Titan
	$vthemetitan->settings['css'] = false;

	// 2.1.1: hey, that includes Google webfont stacks thanks!
	if ( !function_exists( 'bioship_titan_no_font_enqueueing' ) ) {

	 add_filter( 'tf_enqueue_google_webfont_' . $titanoptionkey, 'bioship_titan_no_font_enqueueing', 10, 2 );

	 function bioship_titan_no_font_enqueueing( $url, $name ) {
		// echo "<!--- Font Name: ".$name." - Font URL: ".$url." -->";
		return false;
	 }
	}

	// 2.0.9: fix to use updated Titan color picker alpha script
	// ref: https://github.com/kallookoo/wp-color-picker-alpha-plugin
	// TODO: recheck if this fix is still needed after Titan update ?
	if ( !function_exists( 'bioship_titan_color_picker_alpha_fix' ) ) {

	 // 2.1.1: move add_filter internally for consistency
	 // add_filter('script_loader_tag', 'bioship_titan_color_picker_alpha_fix', 10, 2);

	 function bioship_titan_color_picker_alpha_fix( $tag, $handle ) {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	 	if ( 'wp-color-picker-alpha' == $handle ) {
	 		// to debug: uncomment to use non-minified dev version
	 		// $tag = str_replace('/js/min/wp-color-picker-alpha-min.js', '/js/dev/wp-color-picker-alpha.js', $tag);
	 		// modify the querystring to cachebust the script load
	 		$tag = str_replace( '&ver=', '&updated=yes&ver=', $tag );
	 		$tag = str_replace( '?ver=', '?updated=yes&ver=', $tag );
	 	}
	 	return $tag;
	 }
	}

	// ------------------------------
	// Set Custom WebSafe Font Stacks
	// ------------------------------
	// (since Titan by default adds ALL the Google Fonts here!)
	// 2.1.1: added missing function_exists wrapper
	if ( !function_exists( 'bioship_titan_websafe_fonts' ) ) {

	 add_filter( 'titan_websafefonts', 'bioship_titan_websafe_fonts' );

	 function bioship_titan_websafe_fonts( $fonts ) {
		// 1.9.8: use global value to avoid multiple calls
		global $vthemewebfontstacks;
		if ( !isset( $vthemewebfontstacks ) ) {
			$vthemewebfontstacks = bioship_options_web_font_stacks( array() );
		}
		return $vthemewebfontstacks;
	 }
	}

	// --------------------------
	// Custom Filter Google Fonts
	// --------------------------
	// note: this filter could be removed if you want all Google Font options
	// 2.1.1: added missing function exists wrapper
	if ( !function_exists( 'bioship_titan_google_fonts' ) ) {

	 add_filter( 'titan_googlefonts', 'bioship_titan_google_fonts' );

	 function bioship_titan_google_fonts( $fonts ) {
		$optionsfonts = bioship_options_title_fonts();
		// 2.1.1: use simple array index in loop
		foreach ( $fonts as $i => $font ) {
			$keep = false;
			foreach ( $optionsfonts as $fontkey => $fontname ) {
				// echo $font['name'].'---'.$fontname.PHP_EOL; // debug point
				if ( $font['name'] == $fontname ) {
					$keep = true;
				}
			}
			if ( !$keep ) {
				unset( $fonts[$i] );
			}
		}
		return $fonts;
	 }
	}

	// -------------------------------------
	// Convert Options Array for Titan Usage
	// -------------------------------------
	$i = $j = $k = $l = $m = 0;
	foreach ( $vthemeoptions as $optionkey => $optionvalues ) {

		// --- convert some option naming convention crackliness ---
		if ( !isset( $optionvalues['default'] ) ) {
			if ( isset( $optionvalues['std'] ) ) {
				$optionvalues['default'] = $optionvalues['std'];
			}
		}
		if ( 'typography' == $optionvalues['type'] ) {
			$optionvalues['type'] = 'font';
		} elseif ( 'info' == $optionvalues['type'] ) {
			$optionvalues['type'] = 'note';
		}

		// --- convert id to class (for headings) ---
		$layers = array( 'skin', 'muscle', 'skeleton' );
		if ( isset( $optionvalues['id'] ) ) {
			if ( in_array( $optionvalues['id'], $layers ) ) {
				$optionvalues['class'] = $optionvalues['id'];
				$optionvalues['id'] = $optionvalues['name'];
			}
		}

		// --- resort options into layers by class ---
		$vthemeoptions[$optionkey] = $optionvalues;
		if ( isset( $optionvalues['class'] ) ) {
			if ( strstr( $optionvalues['class'], 'skin' ) ) {
				$skinoptions[$i] = $optionvalues; $i++;
			} elseif ( strstr( $optionvalues['class'], 'muscle' ) ) {
				$muscleoptions[$j] = $optionvalues; $j++;
			} elseif ( strstr( $optionvalues['class'], 'skeleton' ) ) {
				$skeletonoptions[$k] = $optionvalues; $k++;
			} else {
				$hiddenoptions[$l] = $optionvalues; $l++;
			}
		} else {
			// debug point
			// echo '<span style="display:none;">Missing Option Class for ' . esc_attr( $optionvalues['id'] ) . '</span>';
		}

		// --- live preview customizer options array ---
		// (this Titan option is not used, as using a custom customizer.php integration)
		// if (isset($optionvalues['livepreview'])) {$customizeroptions[$m] = $optionvalues; $m++;}
	}

	// debug points
	// print_r($skinoptions);
	// print_r($muscleoptions);
	// print_r($skeletonoptions);
	// print_r($hiddenoptions);

	// Create the Admin Page
	// ---------------------
	$optionspagename = bioship_apply_filters( 'options_admin_menu_title', __( 'BioShip Options','bioship' ) );
	$optionspageposition = bioship_apply_filters( 'options_admin_menu_position', 61 );
	$optionspageicon = bioship_apply_filters( 'options_admin_menu_icon', 'dashicons-welcome-view-site' );
	$optionspageargs = array( 'name' => $optionspagename, 'position' => $optionspageposition, 'icon' => $optionspageicon );
	$adminpanel = $vthemetitan->createAdminPanel( $optionspageargs );

	// Notes for adding an option to existing customizer sections via Titan
	// - title_tagline, colors, background_image, nav, static_front_page -
	// $section = $vthemetitan->createThemeCustomizerSection(array('name' => 'title_tagline'));
	// - panel, desc, capability, position -
	// $section->createOption($someoption);
	// note: Titan method is not used here as we are handling tabs differently...
	// $skinpanel = $adminpanel->createOption(array('type'=>'heading','name'=>'Skin','id'=>'skintab'));
	// $skintab = $skinpanel->createTab(array('name' => 'Skin'));

	// ---------------------------------------------------
	// Add Skin / Muscle / Skeleton Options to Titan Admin
	// ---------------------------------------------------
	foreach ( $skinoptions as $skinoption ) {
		$addoption = $adminpanel->createOption( $skinoption );
	}
	// --- Muscle options ---
	foreach ( $muscleoptions as $muscleoption ) {
		$addoption = $adminpanel->createOption( $muscleoption );
	}
	// --- Skeleton options ---
	foreach ( $skeletonoptions as $skeletonoption ) {
		$addoption = $adminpanel->createOption( $skeletonoption );
	}
	// --- add the hidden option values ----
	foreach ( $hiddenoptions as $hiddenoption ) {
		$addoption = $adminpanel->createOption( $hiddenoption );
	}
	// ---- add a Save Button ---
	$adminpanel->createOption( array( 'type' => 'save' ) );

	// ----------------------------------------------
	// Replace Titan Tabs with Options Framework Tabs
	// ----------------------------------------------
	// 1.8.0: for Titan admin tab and layer filter button compatibility
	// (this is so as to prevent page refreshes for each tab - a downside of Titan)
	if ( !function_exists( 'bioship_titan_options_tab' ) ) {

	 // 2.1.1: moved add_action internally for consistency
	 add_action( 'tf_admin_page_start', 'bioship_titan_options_tabs' );

	 function bioship_titan_options_tabs() {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

		// replace Titan tabs: ie. foreach ($this->tabs as $tab) {$tab->displayTab();}
		// ...with Options Framework style Titan tabs (from optionsframework_tabs)

		global $vthemeoptions;
		$counter = 0;
		$menu = $prevclass = '';

		echo '<h2 class="nav-tab-wrapper"><div class="menu-block">';
		foreach ( $vthemeoptions as $key => $value ) {
			if ( 'heading' == $value['type'] ) {
				$counter++;
				$class = $value['class'] . '-tab';
				// 1.8.5: removed unnecessary line break between layer classes
				if ( ( '' != $prevclass ) && ( $class != $prevclass ) ) {
					$menu .= '</div><div class="menu-block">';
				}
				// $class = ! empty( $value['id'] ) ? $value['id'] : $value['name'];
				// $class = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower($class) ) . '-tab';
				$menu .= '<a id="options-group-' . esc_attr( $counter ) . '-tab" class="nav-tab ' . esc_attr( $class ) . '" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#options-group-' . $counter ) . '">' . esc_attr( $value['name'] ) . '</a>';
				$prevclass = $class;
			}
		}
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo $menu;
		echo '</div></h2>';
	 }
	}

	// ----------------------------------------
	// Replace Titan Admin Options Table Output
	// ----------------------------------------
	// 1.8.0: restyle and wrap the option sections for one-page tabbing
	// (this makes options tabs cross-compatible with options framwork javascript)
	if ( !function_exists( 'bioship_titan_admin_page_options' ) ) {

	 // 2.1.1: moved add_action internally for consistency
	 add_action( 'tf_admin_page_table_start', 'bioship_titan_admin_page_options' );

	 function bioship_titan_admin_page_options() {
		global $vthemesettings, $vthemename, $vthemetitan;

		// --- get Titan instance ---
		$vthemetitan = TitanFramework::getInstance( $vthemename );

		// --- get admin page containers ---
		$containers = $vthemetitan->mainContainers;
		$adminpage = $containers['admin-page'][0];
		// $titandata = print_r($adminpage, true); // huh? printing directly here fails?!
		// error_log($titandata, 3, dirname(__FILE__).'/debug/titan-admin-debug.txt');
		// unset($titandata);

		// --- loop admin page options ---
		$tabcounter = 0;
		$optionheader = '<tr valign="top" class="';
		$optionheading = '<tr valign="top" class="even first tf-heading">';
		foreach ( $adminpage->options as $option ) {
			$settings = $option->settings;
			// print_r($settings); // debug point

			$optiontype = $settings['type'];
			// echo '***'.$optiontype.'***'; // debug point
			// 2.1.1: fix for undefined class index
			if ( !isset( $settings['class'] ) ) {
				$layerclass = '';
			} else {
				$layerclass = $settings['class'];
				$layerclass = str_replace( 'mini', '', $layerclass );
				$layerclass = str_replace( 'none', '', $layerclass );
				$layerclass = str_replace( 'hidden', '', $layerclass );
				$layerclass = str_replace( ' ', '', $layerclass );
			}

			// --- buffer to grab class method output ---
			ob_start();
			$option->display();
			$optionoutput = ob_get_contents();

			// --- tweak the output for save/headings ---
			if ( 'save' == $optiontype ) {
				// ergh, bit of a painful workaround, but all good...
				$output = '</table></div><table class="form-table"><tbody><tr><td>';
				$output .= '<table><tbody>' . $optionoutput . '</tbody></table></tbody></table>';
				$optionoutput = $output;
			} elseif ( strstr( $optionoutput, $optionheading ) ) {
				$tabcounter++;
				// 1.8.5: display all tabs then hide later (makes options accessible if javascript crashing)
				// if (strtolower($settings['name']) == $vthemesettings['optionstab']) {$displaystyle = '';}
				// else {$displaystyle = ' style="display:none;"';}
				// 2.0.9: set temporaray undefined display style variable
				$displaystyle = '';
				$optionoutput = '<div id="options-group-' . $tabcounter . '" class="group ' . $layerclass . '"' . $displaystyle . '><table class="form-table">' . $optionoutput;
				if ( 1 == $tabcounter ) {
					$optionoutput = '</table>'.$optionoutput;
				} else {
					$optionoutput = '</table></div>' . $optionoutput;
				}
			}

			// --- clear the buffer and output option ---
			ob_end_clean();
			// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo $optionoutput;
		}
		// --- start output buffer to catch default display ---
		ob_start();
	 }
	}

	// ---------------------------------------------
	// Clear Buffer of Default Titan Options Display
	// ---------------------------------------------
	// (having fully replaced the Titan tabs/options output - clear default)
	if ( !function_exists( 'bioship_titan_admin_page_clean' ) ) {

		// 2.1.1: moved add_action internally for consistency
		add_action( 'tf_admin_page_table_end', 'bioship_titan_admin_page_clean' );

		function bioship_titan_admin_page_clean() {
			ob_end_clean();
		}
	}

	// ----------------------
	// Titan Nonce Keep Alive
	// ----------------------
	// 2.0.9: keep-alive by refreshing Titan Nonce cyclically
	if ( !function_exists( 'bioship_titan_nonce_refresher' ) ) {

	 // 2.1.1: move add_action internally for consistency
	 add_action( 'admin_footer', 'bioship_titan_nonce_refresher' );

	 function bioship_titan_nonce_refresher() {
	 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	 	// 2.1.0: only load if on Bioship Titan options page
	 	if ( !isset( $_GET['page'] ) || ( THEMESLUG . '-options' != $_GET['page'] ) ) {
	 		return;
	 	}
	 	$adminajax = admin_url( 'admin-ajax.php' );
	 	$refreshurl = add_query_arg( 'action', 'bioship_theme_options_refresh_titan_nonce', $adminajax );
		// 2.2.1: maybe add theme test drive argument
		if ( isset( $_GET['theme'] ) ) {
			$refreshurl = add_query_arg( 'theme', sanitize_text_field( 'theme' ), $refreshurl );
		}
	 	echo "<script>jQuery(document).ready(function() {
	 		setInterval(function() {
	 			document.getElementById('titan-nonce-refresh').src = '" . esc_url( $refreshurl ) . "';
	 		}, 300000);
	 	});</script>" . PHP_EOL;
	 	echo "<iframe id='titan-nonce-refresh' src='javascript:void(0)'></iframe>" . PHP_EOL;
	 }
	}

	// ------------------
	// Enqueue Sticky Kit
	// ------------------
	add_action( 'admin_enqueue_scripts', 'bioship_options_enqueue_stickykit' );

	return true; // too tru bru, too tru...
 }
}


// -------------------------------------
// === Options Framework Integration ===
// -------------------------------------

// -----------------------------
// Options Framework Option Name
// -----------------------------
if ( !function_exists( 'bioship_optionsframework_option_name' ) ) {
 function bioship_optionsframework_option_name() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	$theme = wp_get_theme();

	/** A unique identifier is defined to store the options in the database and reference them from the theme.
	 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
	 * If the identifier changes, it will appear as if the options have been reset. */

	$themename = $theme['Name'];
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$themename = bioship_apply_filters( 'options_framework_themename', $themename );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );

 }
}

// ----------------------------------
// Options Framework Resource URL Fix
// ----------------------------------
// 1.8.0: fix for use of plugin_dir_url() in enqueues (as this is not a plugin!)
// (see class Options_Framework_Admin in class-options-framework-admin.php)
if ( !function_exists( 'bioship_optionsframework_resource_url_fix' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'admin_enqueue_scripts', 'bioship_optionsframework_resource_url_fix', 11 );

 function bioship_optionsframework_resource_url_fix() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	if ( THEMETITAN || !class_exists( 'Options_Framework' ) ) {
		return;
	}

	// --- set Options Framework directories ---
	global $vthemedirs;
	$optionsdirs = array();
	if ( count( $vthemedirs['includes'] ) > 0 ) {
		foreach ( $vthemedirs['includes'] as $i => $dir ) {
			$optionsdirs[$i] = $dir . DIRSEP . 'options';
		}
	}
	// 2.2.0: simplify adding of old options dir but last
	$optionsdirs[] = 'options';

	// --- get options.php location ---
	$optionsframework = bioship_file_hierarchy( 'url', 'options-framework.php', $optionsdirs );

	if ( $optionsframework ) {

		$optionsurlpath = str_replace( 'options-framework.php', '', $optionsframework );

		// --- Options Framework style URL fix ---
		global $wp_styles;
		foreach ( $wp_styles->registered as $handle => $style ) {
			if ( 'optionsframework' == $handle ) {
				$style->src = $optionsurlpath . 'css/optionsframework.css';
				$wp_styles->registered[$handle] = $style;
			}
		}

		// --- Options Framework script URL fix ---
		global $wp_scripts;
		foreach ( $wp_scripts->registered as $handle => $script ) {
			// 2.1.3: removed as deprecated this script usage
			// if ($handle == 'options-custom') {
			//	$script->src = $optionsurlpath.'js/options-custom.js';
			//	$wp_scripts->registered[$handle] = $script;
			// }
			if ( 'of-media-uploader' == $handle ) {
				// 1.8.5: fix to media-uploader.js
				$script->src = $optionsurlpath . 'js/media-uploader.js';
				$wp_scripts->registered[$handle] = $script;
			}
		}
	}

	// --- enqueue Sticky Kit ---
	// 1.9.5: moved here from optionsframework_option_name above
	bioship_options_enqueue_stickykit();
 }
}

// -----------------------
// Admin StickyKit Enqueue
// -----------------------
if ( !function_exists( 'bioship_options_enqueue_stickykit' ) ) {
 function bioship_options_enqueue_stickykit() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vjscachebust, $vthemedirs;
	$stickykit = bioship_file_hierarchy( 'both', 'jquery.sticky-kit.min.js', $vthemedirs['script'] );
	if ( is_array( $stickykit ) ) {
		if ( 'filemtime' == $vthemesettings['javascriptcachebusting'] ) {
			$cachebust = date( 'ymdHi', filemtime( $stickykit['file'] ) );
		} else {
			$cachebust  = $vjscachebust;
		}
		wp_enqueue_script( 'stickykit', $stickykit['url'], array( 'jquery' ), $cachebust, true );
	}
 }
}


// --------------------
// === Font Options ===
// --------------------

// -----------------------
// Default Web Font Stacks
// -----------------------
// ref: http://www.onedesigns.com/tutorials/font-families-for-cross-compatible-typography
// ref: https://wiki.bath.ac.uk/display/webservices/Fonts+-+readable,+cross-platform+typography
// 2.1.2: add some from extra options from Titan framework fonts (Trebuchet, Aial Black)
if ( !function_exists( 'bioship_options_web_font_stacks' ) ) {

 // --- add the font filter for Options Framework ---
 // 2.1.1: move add_filter internally for consistency
 if ( function_exists( 'add_filter' ) ) {
 	add_filter( 'of_recognized_font_faces', 'bioship_options_web_font_stacks' );
 }

 function bioship_options_web_font_stacks( $faces ) {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 1.9.0: re-added Raleway, legacy skeleton boilerplate default
	$fonts = array(
		'"Raleway", "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif' => 'Raleway, HelveticaNeue, Helvetica Neue, Helvetica, Arial, sans-serif',
		'helvetica, arial, "Nimbus Sans L", sans-serif' => 'Helvetica, Arial, Nimbus Sans L, sans-serif',
		'"Arial Black", Gadget, sans-serif' => 'Arial Black, Gadget, sans-serif',
		'tahoma, helvetica, arial, "Nimbus Sans L", sans-serif' => 'Tahoma, Helvetica, Arial, Nimbus Sans L, sans-serif',
		'verdana, "DejaVu Sans", sans-serif' => 'Verdana, DejaVu Sans, sans-serif',

		'"Lucida Sans", "Lucida Grande", "Bitstream Vera Sans", Garuda, sans-serif' => 'Lucida Sans, Lucida Grande, Bitstream Vera Sans, Garuda, sans-serif',
		'"Century Gothic", CenturyGothic, AppleGothic, helvetica, sans-serif' => 'Century Gothic, CenturyGothic, AppleGothic, helvetica, sans-serif',
		'"Trebuchet MS", URW Grotesk T, helvetica, sans-serif' => 'Trebuchet MS, URW Grotesk T, helvetics, sans-serif',
		'Impact, Charcoal, Haettenschweiler, "Arial Narrow Bold", sans-serif' => 'Impact, Charcoal, Haettenschweiler, Arial Narrow Bold, sans-serif',

		'georgia, garamond, "URW Bookman L", serif' => 'Georgia, Garamond, URW Bookman L, serif',
		'Palatino, "Book Antiqua", "URW Palladio L", serif' => 'Palatino, Book Antiqua, URW Palladio L, serif',

		'"Comic Sans MS", cursive, sans-serif' => 'Comic Sans MS, cursive, sans-serif',
		'"Times New Roman", Times, sans-serif' => 'Times New Roman, Times, sans-serif',

		'"Courier New", Courier, FreeMono, "Nimbus Mono L", monospace' => 'Courier New, Courier, Nimbus Mono L, FreeMono, monospace',
		'Consolas, "Lucida Console", Monaco, FreeMono, monospace' => 'Consolas, Lucida Console, Monaco, FreeMono, monospace',
	);
	$fonts = bioship_apply_filters( 'options_font_stacks', $fonts );
	return $fonts;

	// Some notable alternatives... (see ref URLS for details)
	// (serif) Bitstream Vera Serif, Century Schoolbook, Utopia Std
	// (monospace) Bitstream Vera Sans Mono, Andale Mono
 }
}

// --------------------------
// Default Google Title Fonts
// --------------------------
// ref: http://www.google.com/fonts
if ( !function_exists( 'bioship_options_title_fonts' ) ) {
 function bioship_options_title_fonts() {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// 1.9.0: re-added Raleway, legacy skeleton boilerplate default
	$titlefonts = array(
		'Sans-Serif' => 'Sans-Serif',
		'Serif' => 'Serif',
		'Abel' => 'Abel',
		'Alice' => 'Alice',
		'Aller' => 'Aller',
		'Andada' => 'Andada',
		'Arbutus+Slab' => 'Arbutus Slab',
		'Arvo' => 'Arvo',
		'Brawler' => 'Brawler',
		'Cambo' => 'Cambo',
		'Cookie' => 'Cookie',
		'Droid+Sans' => 'Droid Sans',
		'Droid+Serif' => 'Droid Serif',
		'Fenix' => 'Fenix',
		'Judson' => 'Judson',
		'Josefin+Slab' => 'Josefin Slab',
		'Kameron' => 'Kameron',
		'Ledger' => 'Ledger',
		'Libre+Baskerville' => 'Libre Baskerville',
		'Lora' => 'Lora',
		'Lato' => 'Lato',
		'Mako' => 'Mako',
		'Marck+Script' => 'Marck Script',
		'Maven+Pro' => 'Maven Pro',
		'Neuton' => 'Neuton',
		'Ovo' => 'Ovo',
		'Open+Sans' => 'Open Sans',
		'PT+Sans' => 'PT Sans',
		'PT+Serif+Caption' => 'PT Serif',
		'Raleway' => 'Raleway',
		'Roboto' => 'Roboto',
		'Rokkitt' => 'Rokkitt',
		'Ubuntu' => 'Ubuntu',
		'Vollkorn' => 'Vollkorn'
	);

	// 1.8.0: automatically add 'extra fonts' to selections
	// 2.2.0: get separate saved option for extra fonts
	global $vthemename;
	$extra_fonts =  get_option( $vthemename . '_extra_fonts' );
	if ( $extra_fonts && ( '' != $extra_fonts ) ) {

		// --- get extra fonts ---
		if ( strstr( $extra_fonts, ',' ) ) {
			$extrafonts = explode( ',', $extra_fonts );
		} else {
			$extrafonts[0] = $extra_fonts;
		}

		// --- create extra fonts array ---
        $extrafontarray = array();
		foreach ( $extrafonts as $extrafont ) {
			$extrafont = trim( $extrafont );
			$extrafontkey = str_replace( ' ', '+', $extrafont );
			// 1.8.5: fix to font array key
			$extrafontarray[$extrafontkey] = $extrafont;
		}

		// --- merge extra fonts with title fonts ---
        if ( count( $extrafontarray ) > 0 ) {
            $titlefonts = array_merge( $extrafontarray, $titlefonts );
        }
	}

	// --- apply filters and return ---
	$titlefonts = bioship_apply_filters( 'options_title_fonts', $titlefonts );
	return $titlefonts;
 }
}

// ------------------
// Title Font Display
// ------------------
// note: displayed via /wp-admin/admin.php?show=titlefontexamples
// 2.0.9: check for request internally
if ( !function_exists( 'bioship_options_title_font_display' ) ) {

 // 2.0.9: add action internally for consistency
 add_action( 'admin_init', 'bioship_options_title_font_display' );

 function bioship_options_title_font_display() {

 	if ( !isset( $_GET['show'] ) || ( 'titlefontexamples' != $_GET['show'] ) ) {
 		return;
 	}
 	$titlefonts = bioship_options_title_fonts();

	$table = '<center><table>';
	$loadfonts = '';
	foreach ( $titlefonts as $fontface => $display ) {
		if ( '' != $fontface ) {
			$fontface = trim( $fontface );
			if ( ( 'Sans-Serif' != $fontface ) && ( 'Serif' != $fontface ) ) {
				if ( strstr( $fontface, '+' ) ) {
					$fontface = str_replace( '+', ' ', $fontface );
				}
				$loadfonts .= '<' . 'link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' . $fontface . '" type="text/css" media="all" ' . '/>';
				$table .= '<tr><td class="displayname">' . esc_html( $display ) . '</td>';
				$table .= '<td width="15"></td>';
				$table .= '<td class="displayfont">';
					$table .= '<font style="font-family:\'' . $fontface . '\';">The quick brown fox jumped over the lazy dog.</font>';
				$table .= '</td></tr>';
			}
		}
	}
	$table .= '</table></center>';

	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<head>' . $loadfonts;
	echo '<style>.displayname {font-family:helvetica,arial;font-size:14pt;} .displayfont {font-size:24pt;}</style>';
	echo '</head>';
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<body>' . $table . '</body>';
	exit;
 }
}

// ------------------
// Display Body Fonts
// ------------------
// note: displayed via /wp-admin/admin.php?show=bodyfontexamples
// 2.0.9: check for display request internally
if ( !function_exists( 'bioship_options_body_font_display' ) ) {

 // 2.0.9: add action internally for consistency
 add_action( 'admin_init', 'bioship_options_body_font_display' );

 function bioship_options_body_font_display() {

	if ( !isset( $_GET['show'] ) || ( 'bodyfontexamples' != $_GET['show'] ) ) {
		return;
	}
	$bodyfonts = bioship_options_web_font_stacks( array() );

	$table = '<center><table>';
	$loadfonts = '';
	$queried = array();
	foreach ( $bodyfonts as $font => $display ) {
		if ( '' != $font ) {
			$table .= '<tr><td colspan="3" class="displayname">' . esc_html( $display ) . '</td></tr>';
			$fontfaces = explode( ',', $font );
			foreach ( $fontfaces as $fontface ) {
				$fontface = trim( $fontface );
				if ( ( 'sans-serif' != $fontface ) && ( 'serif' != $fontface ) && ( 'monospace' != $fontface ) ) {
					if ( strstr( $fontface, '+' ) ) {
						$fontface = str_replace( '+', ' ', $fontface );
					}
					if ( strstr( $fontface, '"' ) ) {
						$fontface = str_replace( '"', '', $fontface );
					}
					if ( !in_array( $fontface, $queried ) ) {
						$loadfonts .= '<' . 'link rel="stylesheet" id="skeleton-body-fonts-css" href="http://fonts.googleapis.com/css?family=' . $fontface . '" type="text/css" media="all" ' . '/>';
						$queried[] = $fontface;
					}
					// 2.2.0: added missing esc_html on display name
					$table .= '<tr><td class="displayname">' . esc_html( $fontface ) . '</td>';
					$table .= '<td width="15"></td>';
					$table .= '<td class="displayfont"><font style="font-family:\'' . $fontface . '\';">The quick brown fox jumped over the lazy dog.</font>';
					$table .= '</td></tr>';
				}
			}
			$table .= '<tr height="15"><td> </td></tr>';
		}
	}
	$table .= '</table></center>';

	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<head>' . $loadfonts;
	echo '<style>.displayname {font-family:helvetica,arial;font-size:12pt;} .displayfont {font-size:16pt;}</style>';
	echo '</head>';
	// 2.2.0: added missing translation wrappers
	echo '<body>' . esc_html( __( 'Web Safe Font Stacks', 'bioship' ) ) . ' ';
	echo '<i>' . esc_html( __( 'as displayed in your current browser and operating system', 'bioship') ) . '</i>...<br>';
	echo '(' . sprintf( esc_html( __( 'If a font looks like %s, it is because you do not have it installed.', 'bioship' ) ), '<span style="font-family:\'Times New Roman\';">Times New Roman</span>' ) . ')<br><br>';
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo $table;
	echo '</body>';
	exit;
 }
}

// -----------------------------
// === Set All Theme Options ===
// -----------------------------

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'bioship' with the actual text domain for your theme.
 * Read more: http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

if ( !function_exists( 'bioship_options' ) ) {
 function bioship_options( $internal = true ) {

	global $vthemename, $vthemesettings;

	// Header Typography Options
	// -------------------------
	// 2.0.9: added missing tranlation wrappers
	$titlefonts = bioship_options_title_fonts();
	$spacing = $heights = array();
	for ( $i = -20; $i < 21; $i++ ) {
		$spacing[$i . 'px'] = $i . 'px';
	}
	$spacing['normal'] = __( 'Normal','bioship' );
	for ( $i = 0.5; $i <= 3; $i += 0.1 ) {
		$heights[] = $i . 'em';
	}
	$headertype_options = array(
		'sizes'		=> array(
			'12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24',
			'26','28','30','32','34','36','40','44','48'
		),
		'faces'		=> $titlefonts,
		'styles'	=> array(
			'normal' => __( 'Normal', 'bioship' ), 'bold' => __( 'Bold', 'bioship' ), 'italics' => __( 'Italics','bioship' )
		),
		'color'		=> true,
		// 2.2.0: added missing typography options for backwards compatibility
		'weights'	=> array(
			'inherit' => __( 'Inherit', 'bioship' ), 'normal' => __( 'Normal', 'bioship' ),
			'bold' => __( 'Bold', 'bioship' ), 'bolder' => __('Bolder','bioship'),
			'lighter' => __( 'Lighter', 'bioship' ),
			'100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500',
			'600' => '600', '700' => '700', '800' => '800', '900' => '900',
		),
		'heights'	=> array( '0.5em', '0.6em', '0.7em', '0.8em', '0.9em', '1.0em', '1.1em', '1.2em', '1.3em', '1.4em', '1.5em', '1.6em',
			'1.7em', '1.8em', '1.9em', '2.0em', '2.1em', '2.2em', '2.3em', '2.4em', '2.5em', '2.6em', '2.7em', '2.8em', '2.9em'
		),
		'spacing'	=> $spacing,
		'transform'	=> array( 'none' => __( 'None', 'bioship' ), 'capitalize' => __( 'Capitalize', 'bioship' ),
		'lowercase' => __( 'Lower Case', 'bioship' ), 'uppercase' => __( 'Upper Case', 'bioship' ) ),
		'variants'	=> array( 'none' => __( 'None', 'bioship' ), 'small-caps' => __( 'Small Caps', 'bioship' ) ),
	);

	// Body Typography Options
	// -----------------------
	// 2.0.9: added missing translation wrappers
	$bodyfonts = bioship_options_web_font_stacks( array() );
	$typography_options = array(
		'sizes'		=> array(
			'8', '9', '10', '11', '12', '13', '14', '15', '16', '17',
			'18', '19', '20', '21', '22', '23', '24', '26', '28', '30', '32', '34', '36'
		),
		'faces'		=> $bodyfonts,
		'styles'	=> array( 'normal' => __( 'Normal', 'bioship' ), 'bold' => __( 'Bold', 'bioship' ), 'italics' => __( 'Italics', 'bioship' ) ),
		'color'		=> true,
		// 2.2.0: added missing typography option for backwards compatibility
		'heights'	=> $heights,
	);

	// Category Array
	// --------------
	$options_categories = array();
	if ($internal) {
		$args = array( 'hide_empty' => 0 );
		$options_categories_obj = get_categories( $args );
		foreach ( $options_categories_obj as $category ) {
			$options_categories[$category->cat_ID] = $category->cat_name;
		}
	}

	// Tags Array
	// ----------
	// [not used]
	// $options_tags = array();
	// $options_tags_obj = get_tags();
	// foreach ( $options_tags_obj as $tag ) {
	// 	$options_tags[$tag->term_id] = $tag->name;
	// }

	// Page Array
	// ----------
	// [not used]
	// $options_pages = array();
	// $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	// $options_pages[''] = 'Select a page:';
	// foreach ($options_pages_obj as $page) {
	// 	$options_pages[$page->ID] = $page->post_title;
	// }

	// Cachebusting Options
	// --------------------
	$cachebusting_options = array(
		'themeversion'		=> __( 'Parent Theme Version', 'bioship' ),
		'childversion'		=> __( 'Child Theme Version', 'bioship' ),
		'yearmonthdate'		=> __( 'YearMonthDate', 'bioship' ),
		'yearmonthdatehour'	=> __( 'YearMonthDateHour', 'bioship' ),
		'datehourminutes'	=> __( 'YearMonthDateHourMinutes', 'bioship' ),
		'filemtime'			=> __( 'File Modified Time', 'bioship' ),
	);

	// Image Radio Buttons Directory path (unused)
	$imagepath = bioship_apply_filters( 'options_framework_images_url', get_template_directory_uri() . '/images/' );

	// Custom Post Type Array
	// ----------------------
	$cpts = array( 'page', 'post' );
	$args = array( 'public' => true, '_builtin' => false );
	$thumbcpt_options['page'] = __( 'Page','bioship' );

	if ( $internal ) {
		$cptlist = get_post_types( $args, 'names', 'and' );
		$cpts = array_merge( $cpts, $cptlist );

		foreach ( $cpts as $cpt ) {
			// 2.0.9: get post type label from post type object
			// $cptname = strtoupper(substr($cpt,0,1)).substr($cpt,1);
			$posttypeobject = get_post_type_object( $cpt );
			$cptname = $posttypeobject->labels->singular_name;
			$cpt_options[$cpt] = $cptname;
			if ( 'post' != $cpt ) {
				$thumbcpt_options[$cpt] = $cptname;
			}
		}
	}


	// --- START THEME OPTIONS ---
	// ---------------------------

	$options = array();

	// ============
	// === SKIN ===
	// ============

	// --------------
	// === Styles ===
	// --------------

	$options[] = array(
		'name'	=> __( 'Styles', 'bioship' ),
		'id'	=> 'skin',
		'type'	=> 'heading',
		'page'	=> 'both',
	);

	// Dynamic CSS
	// -----------
	$options[] = array(
		'name'	=> __( 'Dynamic Custom CSS', 'bioship' ),
		'desc'	=> '',
		'id'	=> 'dynamiccustomcss',
		'std'	=> '',
		'class'	=> 'skin',
		// 'transport' => 'refresh', // TODO: Customizer test
		'type'	=> 'textarea',
		'page'	=> 'basic',
	);

	// Replacement Values Reference
	// ----------------------------
	$rvdesc = __( 'Dynamic Stylesheet includes some replacement value support.', 'bioship' ) . '<br>';
	$rvdesc .= __( 'Mostly for pointing to your stylesheet/template directory URLs, but also for some IE helper files.', 'bioship' ) . '<br>';
	$rvdesc .= '%STYLEURL% = %STYLESHEETURL% = '.__('Full URL to your Stylesheet directory (eg. themes/my-child-theme/)', 'bioship' ) . '<br>';
	$rvdesc .= '%STYLEIMAGEURL% = ' . __('Full URL to your Stylesheet directory Images (eg. themes/my-child-theme/images/)', 'bioship' ) . '<br>';
	$rvdesc .= '%TEMPLATEURL% = ' . __( 'Full URL to your Template directory (themes/bioship/)', 'bioship' ) . '<br>';
	$rvdesc .= '%TEMPLATEIMAGEURL% = ' . __( 'Full URL to your Template directory Images (themes/bioship/images/)', 'bioship' ) . '<br>';
	$rvdesc .= '%PIE% = ' . __( 'Full URL to pie.htc file.', 'bioship' ) . ' eg. behavior:url(%PIE%); (' . __( 'for CSS3 on IE 6-9', 'bioship' ) . ' <a href="http://css3pie.com" target="_blank">' . __( 'more info', 'bioship' ) . '</a>)<br>';
	$rvdesc .= '%BORDERRADIUS% = ' . __( 'Full URL to border-radius.htc file.', 'bioship') . ' eg. behavior:url(%BORDERRADIUS%); (<a href="http://www.htmlremix.com/css/curved-corner-border-radius-cross-browser" target="_blank">' . __( 'more info', 'bioship' ) . '</a>)<br>';
	$rvdesc .= __( 'Note:', 'bioship' ) . ' <i>custom.css</i> ' . __( 'will also be loaded if it exists.', 'bioship' ) . '<br>';

	$options[] = array(
	 	'name'	=> __( 'Replacement Values', 'bioship' ),
		'desc'	=> $rvdesc,
		'class'	=> 'skin',
		'type'	=> 'info',
		'page'	=> 'basic',
	);

	// CSS Mode
	// --------
	// 1.8.0: changed default to direct load from admin-ajax method
	// though loadtime is roughly equal, often in practice admin-ajax is blocking!
	$css_array = array(
		'direct'	=> __( 'Load skin.php via URL (uses Shortinit)', 'bioship' ),
		'adminajax'	=> __( 'Load using AJAX via admin-ajax.php', 'bioship' ),
		'header'	=> __( 'Print in HTML Page Header', 'bioship' ),
		'footer'	=> __( 'Print in HTML Page Footer', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Dynamic CSS Mode', 'bioship' ),
		'desc'		=> __( 'Whether to load Dynamic Skin and Grid via AJAX or Direct URL.', 'bioship' ),
		'id'		=> 'themecssmode',
		'std'		=> 'direct', // changed default
		'type'		=> 'select', // was radio
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'options'	=> $css_array,
		'page'		=> 'advanced',
	);

	// CSS Normalize or Reset
	// ----------------------
	$reset_array = array(
		'off'		=> __( 'Do not reset', 'bioship' ),
		'normalize'	=> __( 'Use normalize.css', 'bioship' ),
		'reset'		=> __( 'Use reset.css', 'bioship' ),
		'reseter'	=> __( 'Use reseter.css', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Perform CSS Reset', 'bioship' ),
		'desc'		=> __( 'Whether to do a CSS reset before loading main stylesheets. Can use either normalize.css (recommended) or plain CSS reset.', 'bioship'),
		'id'		=> 'cssreset',
		'std'		=> 'normalize',
		'type'		=> 'select', // was radio
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'options'	=> $reset_array,
		'page'		=> 'basic',
	);

	// Formalize
	// ---------
	$options[] = array(
		'name'		=> __( 'Load Formalize (CSS and JS)', 'bioship' ),
		'desc'		=> __( 'Loads Formalize CSS and Javascript for cleaner cross-browser &lt;form&gt; element styling.', 'bioship' ),
		'id'		=> 'loadformalize',
		'std'		=> '1',
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Combine Core Stylesheets
	// ------------------------
	$options[] = array(
		'name'		=> __( 'Combine Core Stylesheets', 'bioship' ),
		'desc'		=> __( 'Core BioShip Stylesheets will be combined and used upon saving (but <i>only</i> upon saving, so re-save if you change any!)', 'bioship' ),
		'id'		=> 'combinecsscore',
		'std'		=> '0',
		'class'		=> 'skin',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Stylesheet Cache Busting
	// ------------------------
	// 2.0.7: change default to filemtime
	$options[] = array(
		'name'		=> __( 'Cache Busting for Stylesheets', 'bioship' ),
		'desc'		=> __( 'Querystring used as version for cache busting Stylesheet files.', 'bioship' ),
		'id'		=> 'stylesheetcachebusting',
		'std'		=> 'filemtime',
		'class'		=> 'skin',
		'type'		=> 'select', // was radio
		'options'	=> $cachebusting_options,
		'page'		=> 'advanced',
	);

	// HTML Comments
	// -------------
	// 1.8.0: added this option
	$options[] = array(
		'name'		=> __( 'HTML Element Comments', 'bioship' ),
		'desc'		=> __( 'Add Comment Wrappers to main HTML Elements for easier reading of page source while developing. You may want to turn this off later.',  'bioship' ),
		'id'		=> 'htmlcomments',
		'class'		=> 'skin',
		'std'		=> '0',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);


	// ------------------
	// === Background ===
	// ------------------

	$options[] = array(
		'name'		=> __( 'Background', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Background Colour
	// -----------------
	$options[] = array(
		'name'		=> __( 'Background Colour', 'bioship'),
		'desc'		=> __( 'Sets the default body background-color property.', 'bioship'),
		'id'		=> 'body_bg_color',
		'std'		=> '#FFFFFF',
		'class'		=> 'skin',
		// 'css'	=> 'body {background-color: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-color',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Background Image
	// ----------------
	$options[] = array(
		'name'		=> __( 'Background Image', 'bioship' ),
		'desc'		=> __( 'Set the body background image, you can upload or paste the URL here. Your background should be resized prior to uploading.', 'bioship' ),
		'id'		=> 'background_image',
		'std'		=> '',
		'class'		=> 'skin',
		// 'css'	=> 'body {background-image: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-image',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Background Position
	// -------------------
	$options[] = array(
		'name'		=> __( 'Background Position', 'bioship' ),
		'desc'		=> __( 'Set the body background-position CSS property.', 'bioship' ),
		'id'		=> 'background_position',
		'std'		=> 'top center',
		'class'		=> 'mini skin',
		// 'css'	=> 'body {background-position: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-position',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Background Size
	// ---------------
	$options[] = array(
		'name'		=> __( 'Background Size', 'bioship' ),
		'desc'		=> __( 'Set the body background-size CSS property.', 'bioship' ),
		'id'		=> 'background_size',
		'std'		=> '',
		'class'		=> 'mini skin',
		// 'css'	=> 'body {background-size: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-size',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Background Repeat
	// -----------------
	// 1.8.5: use select dropdown options instead of text field
	// 1.9.6: fix to background repeat option array
	$backgroundrepeat_options = array(
		'no-repeat'	=> __( 'No Repeat', 'bioship' ),
		'repeat'	=> __( 'Repeat (Tile)', 'bioship' ),
		'repeat-x'	=> __( 'Repeat Horizontal', 'bioship' ) . ' (x)',
		'repeat-y'	=> __( 'Repeat Vertical', 'bioship' ) . ' (y)',
		'initial'	=> __( 'Initial', 'bioship' ),
		'inherit'	=> __('Inherit', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Background Repeat', 'bioship' ),
		'desc'		=> __( 'Set the body background-repeat CSS property.', 'bioship' ),
		'id'		=> 'background_repeat',
		'std'		=> 'no-repeat',
		'class'		=> 'mini skin',
		// 'css'	=> 'body {background-repeat: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-repeat',
		'options'	=> $backgroundrepeat_options,
		'type'		=> 'select',
		'page'		=> 'basic',
	);

	// Background Attachment
	// ---------------------
	// 1.8.5: use select dropdown options instead of text field
	$backgroundattach_options = array(
		'scroll'	=> __( 'Scroll', 'bioship' ),
		'Fixed'		=> __( 'Fixed', 'bioship' ),
		'local'		=> __( 'Local', 'bioship' ),
		'initial'	=> __( 'Initial', 'bioship' ),
		'inherit'	=> __( 'Inherit', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Background Attachment', 'bioship' ),
		'desc'		=> __( 'Set the body background-attachment CSS property.', 'bioship' ),
		'id'		=> 'background_attachment',
		'std'		=> 'scroll',
		'class'		=> 'mini skin',
		// 'css'	=> 'body {background-attachment: value;}',
		'csselement'	=> 'body',
		'cssproperty'	=> 'background-attachment',
		'options'	=> $backgroundattach_options,
		'type'		=> 'select',
		'page'		=> 'basic'
	);


	// --------------
	// === Header ===
	// --------------

	$options[] = array(
		'name'	=> __( 'Header', 'bioship' ),
		'id'	=> 'skin',
		'type'	=> 'heading',
		'page'	=> 'basic',
	);

	// Header Background Colour
	// ------------------------
	$options[] = array(
		'name'		=> __('Header Background Colour', 'bioship'),
		'desc'		=> __('Sets the default body background-color property.', 'bioship'),
		'id'		=> 'headerbgcolor',
		'std'		=> '#FFFFFF',
		'class'		=> 'skin',
		'alpha'		=> true,
		// 'css' 		 => '#header {background-color: value;}',
		'csselement'	=> '#header',
		'cssproperty'	=> 'background-color',
		'type' 		=> 'color',
		'page'		=> 'basic'
	);

	// Header Background Image
	// -----------------------
	$options[] = array(
		'name'		=> __( 'Header Background Image', 'bioship' ),
		'desc'		=> __( 'Set the header image background image, you can upload or paste the URL here. Your background should be resized prior to uploading.', 'bioship' ),
		'id'		=> 'header_background_image',
		'std'		=> '',
		'class'		=> 'skin',
		// 'css'		=> '#header {background-image: value;}',
		'csselement'	=> '#header',
		'cssproperty'	=> 'background-image',
		'transport'	=> 'refresh', // 1.8.5: for resize
		'type'		=> 'upload',
		'page'		=> 'basic'
	);

	// Header Background Position
	// --------------------------
	$options[] = array(
		'name'		=> __( 'Header Background Position', 'bioship' ),
		'desc'		=> __( 'Set the header image background-position CSS property.', 'bioship' ),
		'id'		=> 'header_background_position',
		'std'		=> 'top center',
		'class'		=> 'mini skin',
		// 'css'		=> '#header {background-position: value;}',
		'csselement'	=> '#header',
		'cssproperty'	=> 'background-position',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Header Background Size
	// ----------------------
	$options[] = array(
		'name'		=> __( 'Header Background Size', 'bioship' ),
		'desc'		=> __( 'Set the header image background-size CSS property.', 'bioship' ),
		'id'		=> 'header_background_size',
		'std'		=> '100%',
		'class'		=> 'mini skin',
		// 'css'		=> '#header {background-size: value;}',
		'csselement'	=> '#header',
		'cssproperty'	=> 'background-size',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Header Background Repeat
	// ------------------------
	// 1.8.5: use select dropdown options instead of text field
	$options[] = array(
		'name'		=> __( 'Header Background Repeat', 'bioship' ),
		'desc'		=> __( 'Set the header image background-repeat CSS property.', 'bioship' ),
		'id'		=> 'header_background_repeat',
		'std'		=> 'no-repeat',
		'class'		=> 'mini skin',
		// 'css'		=> '#header {background-repeat: value;}',
		'csselement'	=> '#header',
		'cssproperty'	=> 'background-repeat',
		'options'	=> $backgroundrepeat_options,
		'type'		=> 'select',
		'page'		=> 'basic'
	);

	// Header Logo
	// -----------
	$options[] = array(
		'name'		=> __( 'Header Logo', 'bioship' ),
		'desc'		=> __( 'If you prefer to show a graphic logo in place of the site title, you can upload or paste the URL here. Your logo should be resized prior to uploading.', 'bioship' ),
		'id'		=> 'header_logo',
		'std'		=> '',
		'class'		=> 'skin',
		// 'css'		=> '#site-logo {background-image: value;}',
		'csselement'	=> '#site-logo',
		'cssproperty'	=> 'background-image',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Auto Resize Header
	// ------------------
	// 2.0.9: added this option for header resizing (experimental)
	// TEMP: disabled while in further development, activated via filter
	// $options[] = array(
	//	'name' 		=> __('Auto Resize Header', 'bioship'),
	//	'desc' 		=> __('Resizes the header proportionally with window resize.', 'bioship'),
	//	'id'		=> 'headerresize',
	//	'std'		=> '0',
	//	'class'		=> 'skin',
	//	'transport'	=> 'refresh',
	//	'type' =>	'checkbox',
	//	'page' =>	'basic'
	// );

	// Auto Resize Logo
	// ----------------
	$options[] = array(
		'name'		=> __( 'Auto Resize Logo', 'bioship' ),
		'desc'		=> __( 'Resizes the logo image proportionally with header on window resize.', 'bioship' ),
		'id'		=> 'logoresize',
		'std'		=> '0',
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Sticky Logo
	// -----------
	// 2.2.0: added this option
	$options[] = array(
		'name'		=> __( 'Sticky Logo', 'bioship' ),
		'desc'		=> __( 'Fixes the logo image to the top of the page when it is scrolled past.', 'bioship' ),
		'id'		=> 'stickylogo',
		'std'		=> '0',
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic'
	);

	// Auto Resize Site Title Text
	// ---------------------------
	// 2.0.9: added option for scaling of site title text (experimental)
	// TEMP: disabled while in further development, can be activated via filter
	// $options[] = array(
	//	'name'		=> __('Auto Resize Site Title Text', 'bioship'),
	//	'desc'		=> __('Resizes the site title text and description with header on window resize.', 'bioship'),
	//	'id'		=> 'sitetextresize',
	//	'std'		=> '0',
	//	'class'		=> 'skin',
	//	'transport'	=> 'refresh',
	//	'type'		=> 'checkbox',
	//	'page'		=> 'basic'
	// );

	// Text Header Info
	// ----------------
	// $options[] = array( 'name' => __('Text Header Settings','bioship'),
	//	'desc'	=> __('If you choose not to upload a logo for your header, the options below allow you to customize the text and tagline.<br>If you have uploaded a logo, the settings below have no effect.','bioship'),
	//	'class' => 'none skin',
	//	'type'	=> 'info');

	// Text Header Display
	// -------------------
	// 2.2.0: split this multicheck option into separate checkboxes

	/* $textheader_defaults = array( 'sitetitle' => '1', 'sitedescription' => '1' );
	$textheader_options = array(
		'sitetitle'		=> __( 'Display Site Title Headline', 'bioship' ),
		'sitedescription'	=> __( 'Site Description Tagline', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Display Header Text', 'bioship' ),
		'desc'		=> __( 'Select which header title text elements to display.', 'bioship' ),
		'id'		=> 'header_texts',
		'std'		=> $textheader_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'skin',
		'transport'	=> 'postMessage',
		'csselement'	=> '#header h1#site-title-text a,#site-description .site-desc',
		'cssproperty'	=> 'display',
		'options'	=> $textheader_options,
		'page'		=> 'basic',
	); */

	// Site Title Display
	// ------------------
	$options[] = array(
		'name'		=> __( 'Display Site Title', 'bioship' ),
		'desc'		=> __( 'Whether to display the Site Title text.', 'bioship' ),
		'id'		=> 'site_title',
		'std'		=> '1',
		'type'		=> 'checkbox',
		'class'		=> 'skin',
		'transport'	=> 'postMessage',
		'csselement'	=> '#header h1#site-title-text a',
		'cssproperty'	=> 'display',
		'page'		=> 'basic',
	);

	// Site Tagline Display
	// --------------------
	$options[] = array(
		'name'		=> __( 'Display Site Tagline', 'bioship' ),
		'desc'		=> __( 'Whether to display the Site Tagline text.', 'bioship' ),
		'id'		=> 'site_description',
		'std'		=> '1',
		'type'		=> 'checkbox',
		'class'		=> 'skin',
		'transport'	=> 'postMessage',
		'csselement'	=> '#site-description .site-desc',
		'cssproperty'	=> 'display',
		'page'		=> 'basic',
	);

	// Header Site Title Typography
	// ----------------------------
	$options[] = array(
		'name'			=> __( 'Site Title Text Style', 'bioship' ),
		'desc'			=> '',
		'id'			=> 'headline_typography',
		'std'			=> array(
			'size'		=> '40px',
			'face'		=> 'Open+Sans',
			'style'		=> 'normal',
			'color'		=> '#181818'
		),
		'default'		=> array(
			'font-size'	=> '40px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans'
		),
		'show_websafe_fonts'	=> false,
		'enqueue'		=> false,
		// 'css'		=> '#header h1#site-title a {value;}',
		'csselement'		=> '#header h1#site-title-text a',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic'
	);

	// Header Tagline Typography
	// -------------------------
	$options[] = array(
		'name'			=> __( 'Site Tagline Text Style', 'bioship' ),
		'desc'			=> '',
		'id'			=> 'tagline_typography',
		'std'			=> array(
			'size'		=> '24px',
			'face'		=> 'Open+Sans',
			'style'		=> 'normal',
			'color'		=> '#CCCCCC'
		),
		'default' => array(
			'font-size'	=> '24px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#999999',
			'font-family'	=> 'Open Sans'
		),
		'show_websafe_fonts'	=> false,
		'enqueue'		=> false,
		// 'css'		=> '#header div.site-desc {value;}',
		'csselement'		=> '#site-description .site-desc',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic'
	);


	// ------------
	// === Menu ===
	// ------------

	$options[] = array(
		'name'		=> __( 'Menu', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Auto Space Menu
	// ---------------
	// 1.8.5: added this option
	$options[] = array(
		'name'		=> __( 'Auto Space Navigation', 'bioship' ),
		'desc'		=> __( 'Sets percentage widths for top level Menu Item Display based on number of items (tip: works well with short menu item names, not well with long ones.)', 'bioship' ),
		'id'		=> 'navmenuautospace',
		'std'		=> '0',
		'class'		=> 'skin',
		'transport'	=> 'refresh', // TODO: postMessage?
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Sticky Nav Bar
	// --------------
	// 2.2.0: added this option
	$options[] = array(
		'name'		=> __( 'Sticky Navigation Bar', 'bioship' ),
		'desc'		=> __( 'Fixes the navigation menu to the top of the page when it is scrolled past.', 'bioship' ),
		'id'		=> 'stickynavbar',
		'std'		=> '0',
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Mobile Nav Menu
	// ---------------
	// 2.2.0: added this option
	$options[] = array(
		'name'		=> __( 'Mobile Navigation Button', 'bioship' ),
		'desc'		=> __( 'Hide the Navigation Bar and add a Show Menu button on smaller screens (less than 480px)', 'bioship' ),
		'id'		=> 'mobilenavmanu',
		'std'		=> '1',
		'class'		=> 'skin',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);


	// Menu Item Typography
	// --------------------
	// 2.0.9: removed text shadows
	$options[] = array(
		'name'			=> __( 'Main Menu Item Typography','bioship' ),
		'desc'			=> __( 'Typography for top level menu items.', 'bioship' ),
		'id'			=> 'navmenu_typography',
		'std'			=> array(
			'size'		=> '20px',
			'face'		=> 'Open Sans',
			'style'		=> 'bold',
			'color'		=> '#222222'
		),
		'default'		=> array(
			'font-size'	=> '14px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'bold',
			'color'		=> '#222222',
			'font-family'	=> 'Open Sans'
		),
		'show_google_fonts'	=> true,
		'show_text_shadow'	=> false,
		// 'css'			=> '#navigation #mainmenu ul li, #navigation #mainmenu ul li a {value;}',
		'csselement'		=> '#navigation #mainmenu ul li, #navigation #mainmenu ul li a',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
	);

	// Submenu Item Typography
	// -----------------------
	// 2.0.9: removed text shadows
	$options[] = array(
		'name'			=> __( 'Main SubMenu Item Typography','bioship' ),
		'desc'			=> __( 'Typography for secondary level submenu items.','bioship' ),
		'id'			=> 'navsubmenu_typography',
		'std'			=> array(
			'size' => '16px',
			'face' => 'Open Sans',
			'style' => 'bold',
			'color' => '#444444'
		),
		'default'		=> array(
			'font-size'	=> '14px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'bold',
			'color'		=> '#444444',
			'font-family'	=> 'Open Sans'
		),
		'show_google_fonts'	=> true,
		'show_text_shadow'	=> false,
		// 'css'			=> '#navigation #mainmenu ul li ul li, #navigation #mainmenu ul li ul li a {value;}',
		'csselement'		=> '#navigation #mainmenu ul li ul li, #navigation #mainmenu ul li ul li a',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
	);

	// Navigation Background Colour
	// ----------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'Navigation Container Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Navigation Container.', 'bioship' ),
		'id'		=> 'navmenubgcolor',
		'std'		=> '',
		// 'css'	=> '#navigation {background-color: value;}',
		'csselement'	=> '#navigation, #navigation #mainmenu, #navigation #mainmenu ul',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Default Menu Item Background Colour
	// -----------------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'Menu Item Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Menu Items.', 'bioship' ),
		'id'		=> 'navmenuitembgcolor',
		'std'		=> '',
		// 'css'	=> '#navigation #mainmenu ul li {background-color: value;}',
		'csselement'	=> '#navigation #mainmenu ul li',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Active Menu Item Colour
	// -----------------------
	// 1.8.5: added this style option
	// 2.1.3: changed default active colour from #DDDDDD
	// 2.2.0: fix to CSS element typo curent-menu-item
	$options[] = array(
		'name'		=> __( 'Active Menu Item Text Color', 'bioship' ),
		'desc'		=> __( 'Text Color for Current Page Menu Item.', 'bioship' ),
		'id'		=> 'navmenuactivecolor',
		'std'		=> '#555555',
		// 'css'	=> '#navigation #mainmenu ul li.active, #navigation #mainmenu ul li.current-menu-item, #navigation #mainmenu ul li.current-menu-item {color: value;}',
		'csselement'	=> '#navigation #mainmenu ul li.active, #navigation #mainmenu ul li.current-menu-item, #navigation #mainmenu ul li.current-menu-item a',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Active Menu Item Background Colour
	// ----------------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'Active Menu Item Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Current Page Menu Item.', 'bioship' ),
		'id'		=> 'navmenuactivebgcolor',
		'std'		=> '#E0E0E0',
		// 'css'	=> '#navigation #mainmenu ul li.active, #navigation #mainmenu ul li.current-menu-item {background-color: value;}',
		'csselement'	=> '#navigation #mainmenu ul li.active, #navigation #mainmenu ul li.current-menu-item',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover Menu Item Colour
	// ----------------------
	// 1.8.5: added this style option
	// 2.0.9: fix to label name (removed background)
	// 2.1.4: fix CSS selector to include a link
	$options[] = array(
		'name'		=> __( 'Hover Menu Item Text Color', 'bioship' ),
		'desc'		=> __( 'Text Color for Page Menu Item Hover.', 'bioship' ),
		'id'		=> 'navmenuhovercolor',
		'std'		=> '',
		// 'css'	=> '#navigation #mainmenu ul li:hover {color: value;}',
		'csselement'	=> '#navigation #mainmenu ul li:hover, #navigation #mainmenu ul li:hover a',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover Menu Item Background Colour
	// ---------------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'Hover Menu Item Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Page Menu Item Hover.', 'bioship' ),
		'id'		=> 'navmenuhoverbgcolor',
		'std'		=> '#F0F0F0',
		// 'css'	=> '#navigation #mainmenu ul li:hover {background-color: value;}',
		'csselement'	=> '#navigation #mainmenu ul li:hover',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Submenu Background Colour
	// -------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'SubMenu Container Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Navigation SubMenu Container.', 'bioship' ),
		'id'		=> 'navmenusubbgcolor',
		'std'		=> '#FFFFFF',
		// 'css'	=> '#navigation {background-color: value;}',
		'csselement'	=> '#navigation #mainmenu ul ul, #navigation #mainmenu ul ul li',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover SubMenu Item Colour
	// -------------------------
	// 2.0.9: added this submenu style option
	$options[] = array(
		'name'		=> __( 'Hover SubMenu Item Text Color', 'bioship' ),
		'desc'		=> __( 'Text Color for Page SubMenu Item Hover.', 'bioship' ),
		'id'		=> 'submenuhovercolor',
		'std'		=> '',
		// 'css'	=> '#navigation #mainmenu ul ul li:hover {color: value;}',
		'csselement'	=> '#navigation #mainmenu ul ul li:hover',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover SubMenu Item Background Colour
	// ------------------------------------
	// 2.0.9: added this submenu style option
	$options[] = array(
		'name'		=> __( 'Hover SubMenu Item Background Color', 'bioship' ),
		'desc'		=> __( 'Background Color for Page SubMenu Item Hover.', 'bioship' ),
		'id'		=> 'submenuhoverbgcolor',
		'std'		=> '#F0F0F0',
		// 'css'	=> '#navigation #mainmenu ul ul li:hover {background-color: value;}',
		'csselement'	=> '#navigation #mainmenu ul ul li:hover',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// ---------------
	// === Colours ===
	// ---------------
	// TODO: maybe use rgba colorpicker? (not available for options framework yet)
	// TODO: somehow allow for 'transparent' property if doing that?

	$options[] = array(
		'name'		=> __( 'Colours', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Inputs Text Colour
	// ------------------
	// 1.8.5: added this option
	$options[] = array(
		'name'		=> __( 'Input Text Color', 'bioship' ),
		'desc'		=> __('Default input text color (text input, select input, textarea)','bioship'),
		'id'		=> 'inputcolor',
		'std'		=> '#111111',
		// 'css'	=> 'body input[type="text], body select, body textarea {color: value;}',
		'csselement'	=> 'body input[type="text"], body input[type="checkbox"], body select, body textarea',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Inputs Background Colour
	// ------------------------
	// 1.8.5: added this option
	$options[] = array(
		'name'		=> __( 'Input Background Color', 'bioship' ),
		'desc'		=> __( 'Default input text background color (body a)', 'bioship' ),
		'id'		=> 'inputbgcolor',
		'std'		=> '#FFFFFF',
		// 'css'	=> 'body input[type="text"], body input type="checkbox"], body input[type="password"], body select, body textarea {background-color: value;}',
		'csselement'	=> 'body input[type="text"], body input[type="checkbox"], body input[type="pasword"], body select, body textarea',
		'cssproperty'	=> 'background-color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Colour Info
	// -----------
	$options[] = array(
		'name'		=> '',
		'desc'		=> __( 'Here you can set some basic styles that will be loaded via the Dynamic CSS file.', 'bioship' ),
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Main Div Section
	// ----------------
	$options[] = array(
		'name'		=> __( 'Main Div Area Colours', 'bioship' ),
		'desc'		=> '',
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Colourpicker Info
	// -----------------
	$options[] = array(
		'name'		=> __( 'Colorpicker', 'bioship' ),
		'desc'		=> __( 'Helps you pick hex colours. But does not set anything as you might want to use transparent or rgb/rgba.', 'bioship' ),
		'id'		=> '',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Wrapper Background Colour
	// -------------------------
	$options[] = array(
		'name'		=> __( 'Wrapper Background Colour', 'bioship' ),
		'desc'		=> __( 'Sets background-color property for #wrap div.', 'bioship' ),
		'id'		=> 'wrapbgcolor',
		'std'		=> '',
		'class'		=> 'mini skin',
		'alpha'		=> true,
		// 'css'	=> '#wrap.container {background-color: value;}',
		'csselement'	=> '#wrap.container',
		'cssproperty'	=> 'background-color',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Content Background Colour
	// -------------------------
	$options[] = array(
		'name'		=> __( 'Content Background Colour', 'bioship' ),
		'desc'		=> __( 'Sets background-color property for #content div.', 'bioship' ),
		'id'		=> 'contentbgcolor',
		'std'		=> '',
		'class'		=> 'mini skin',
		'alpha'		=> true,
		// 'css'	=> '#content {background-color: value;}',
		'csselement'	=> '#content',
		'cssproperty'	=> 'background-color',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Sidebar Background Colour
	// -------------------------
	$options[] = array(
		'name'		=> __( 'Sidebar Background Colour', 'bioship' ),
		'desc'		=> __( 'Sets background-color property for #sidebar div.', 'bioship' ),
		'id'		=> 'sidebarbgcolor',
		'std'		=> '',
		'class'		=> 'mini skin',
		'alpha'		=> true,
		// 'css'	=> '#sidebar {background-color: value;}',
		'csselement'	=> '#sidebar',
		'cssproperty'	=> 'background-color',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// SubSidebar Background Colour
	// ----------------------------
	$options[] = array(
		'name'		=> __( 'Subsidiary Sidebar Background Colour', 'bioship' ),
		'desc'		=> __( 'Sets background-color property for #sidebar-subsidiary div.', 'bioship' ),
		'id'		=> 'subsidebarbgcolor',
		'std'		=> '',
		'class'		=> 'mini skin',
		'alpha'		=> true,
		// 'css'	=> '#subsidebar {background-color: value;}',
		'csselement'	=> '#subsidebar',
		'cssproperty'	=> 'background-color',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Footer Background Colour
	// ------------------------
	$options[] = array(
		'name'		=> __( 'Footer Background Colour', 'bioship' ),
		'desc'		=> __( 'Sets background-color property for #footer div.', 'bioship' ),
		'id'		=> 'footerbgcolor',
		'std'		=> '',
		'class'		=> 'mini skin',
		'alpha'		=> true,
		// 'css'	=> '#footer {background-color: value;}',
		'csselement'	=> '#footer',
		'cssproperty'	=> 'background-color',
		'type'		=> 'text',
		'page'		=> 'basic',
	);


	// -------------
	// === Fonts ===
	// -------------
	// 1.8.5: turn off some Titan Typography options for websafe fonts
	// - show_letter_spacing (default false)
	// - show_text_transform (default false)
	// - show_font_variant (default false)
	// - show_text_shadow (default false)

	$options[] = array(
		'name'		=> __( 'Fonts', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Main Typography
	// ---------------
	if ( function_exists( 'admin_url' ) ) {
		$fontstackexamplelink = admin_url( 'admin.php' ) . '?show=bodyfontexamples=yes';
	} else {
		$fontstackexamplelink = '/wp-admin/admin.php?show=bodyfontexamples';
	}
	$options[] = array(
		'name'			=> __( 'Body Content Typography', 'bioship' ),
		'desc'			=> __( 'Default Main Typography for the webpage Body.', 'bioship' ) . ' <a href="' . esc_url( $fontstackexamplelink ) . '" target=_blank>' . __( 'Example Font Stack Page', 'bioship' ) . '</a>.',
		'id'			=> 'body_typography',
		'std'			=> array(
			'size'		=> '15px',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color' 	=> '#444444'
		),
		'default' => array(
			'font-size'	=> '15px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#444444',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css' 		=> '#wrap.container {value;}',
		'csselement'		=> '#wrap.container',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page' 			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Extra Fonts
	// -----------
	$options[] = array(
		'name'		=> __( 'Extra Fonts', 'bioship' ),
		'desc'		=> __( 'Fonts to load via Google Fonts, comma separated. You can then customize font stacks via the options_font_stacks filter (see filters.php)', 'bioship' ),
		'id'		=> 'extrafonts',
		'std'		=> 'Open Sans',
		'class'		=> 'skin',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// TODO: maybe load a local Open Sans font?
	// $options[] = array(
	//	'name'		=> __( 'Local Open Sans', 'bioship' ),
	//	'desc'		=> __( 'Theme Default Font, you can load a local copy instead of using Google Fonts. Handy for offline development.', 'bioship' );
	//	'std'		=> '0';
	//	'class'		=> 'skin',
	//	'type'		=> 'checkbox'
	// );

	// Section Header
	// --------------
	// 2.2.0: set section font default to inherit for color and size
	$options[] = array(
		'name'		=> __( 'Section Typography', 'bioship' ),
		'desc'		=> '',
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Header Typography
	// -----------------
	$options[] = array(
		'name'			=> __( 'Header Typography', 'bioship' ),
		'desc'			=> __( 'Typography for within the #header div.', 'bioship' ),
		'id'			=> 'header_typography',
		'std'			=> array(
			'size'		=> 'inherit',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color'		=> 'inherit',
		),
		'default' => array(
			'font-size'	=> 'inherit',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> 'inherit',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> '#header {value;}',
		'csselement'		=> '#header',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Sidebar Typography
	// ------------------
	$options[] = array(
		'name'			=> __( 'Sidebar Typography', 'bioship' ),
		'desc'			=> __( 'Typography for within the #sidebar div.', 'bioship' ),
		'id'			=> 'sidebar_typography',
		'std'			=> array(
			'size'		=> 'inherit',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color'		=> 'inherit'
		),
		'default'		=> array(
			'font-size'	=> 'inherit',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> 'inherit',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> '#sidebar {value;}',
		'csselement'		=> '#sidebar',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Subsidebar Typography
	// ---------------------
	$options[] = array(
		'name'			=> __( 'Subsidebar Typography', 'bioship' ),
		'desc'			=> __( 'Typography for within the #subsidebar div.', 'bioship' ),
		'id'			=> 'subsidebar_typography',
		'std'			=> array(
			'size'		=> 'inherit',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color'		=> 'inherit'
		),
		'default'		=> array(
			'font-size'	=> 'inherit',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> 'inherit',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> '#subsidebar {value;}',
		'csselement'		=> '#subsidebar',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Content Typography
	// ------------------
	$options[] = array(
		'name'			=> __( 'Content Area Typography', 'bioship' ),
		'desc'			=> __( 'Typography for within the #content div.', 'bioship' ),
		'id'			=> 'content_typography',
		'std'			=> array(
			'size'		=> 'inherit',
			'face' => 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style' => 'normal',
			'color' => 'inherit'
		),
		'default' => array(
			'font-size'	=> 'inherit',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> 'inherit',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> '#content .entry-content {value;}',
		'csselement'		=> '#content .entry-content',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Footer Typography
	// -----------------
	$options[] = array(
		'name'			=> __( 'Footer Typography', 'bioship' ),
		'desc'			=> __( 'Typography for within the #footer div.', 'bioship' ),
		'id'			=> 'footer_typography',
		'std'			=> array(
			'size'		=> 'inherit',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color'		=> 'inherit'
		),
		'default'		=> array(
			'font-size'	=> 'inherit',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> 'inherit',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> '#footer {value;}',
		'csselement'		=> '#footer',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Headings Typography Info
	// ------------------------
	if ( function_exists( 'admin_url' ) ) {
		$titlefontslink = admin_url( 'admin.php' ) . '?show=titlefontexamples';
	} else {
		$titlefontslink = '/wp-admin/admin.php?show=titlefontexamples';
	}
	$options[] = array(
		'name'		=> __('Headings Typography', 'bioship'),
		'desc'		=> __('Selected Heading fonts are auto-loaded via Google Fonts.','bioship').' <a href="'.$titlefontslink.'" target=_blank>'.__('Example Title Font Page','bioship').'</a>. '.__('Customize available font selections via the bioship_options_title_fonts filter (see filters.php)', 'bioship'),
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// H1 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H1&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading One typography.', 'bioship' ),
		'id'			=> 'h1_typography',
		'std'			=> array(
			'size'		=> '32px',
			'face'		=> 'Open+Sans',
			'style'		=> 'normal',
			'color'		=> '#181818'
		),
		'default' => array(
			'font-size'	=> '32px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans'
		),
		// 'show_websafe_fonts' => false,
		'enqueue'		=> false,
		// 'css'		=> '#h1 {value;}',
		'csselement'		=> '#h1',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);

	// H2 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H2&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading Two typography.', 'bioship' ),
		'id'			=> 'h2_typography',
		'std'			=> array(
			'size'		=> '28px',
			'face'		=> 'Open+Sans',
			'style'		=> 'normal',
			'color' => '#181818'
		),
		'default' => array(
			'font-size'	=> '28px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#181818',
			'font-family' => 'Open Sans'
		),
		// 'show_websafe_fonts' => false,
		'enqueue'		=> false,
		// 'css'		=> '#h2 {value;}',
		'csselement'		=> '#h2',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);

	// H3 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H3&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading Three typography.', 'bioship' ),
		'id'			=> 'h3_typography',
		'std'			=> array(
			'size'		=> '22px',
			'face'		=> 'Open+Sans',
			'style'		=> 'normal',
			'color'		=> '#181818'
		),
		'default' => array(
			'font-size'	=> '22px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans' 
		),
		// 'show_websafe_fonts' => false,
		'enqueue'		=> false,
		// 'css'		=> '#h3 {value;}',
		'csselement'		=> '#h3',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);

	// H4 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H4&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading Four typography.', 'bioship' ),
		'id'			=> 'h4_typography',
		'std'			=> array(
			'size'		=> '20px',
			'face'		=> 'Open+Sans',
			'style'		=> 'bold',
			'color' => '#181818'
		),
		'default' => array(
			'font-size'	=> '20px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'bold',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans'
		),
		// 'show_websafe_fonts' => false,
		// 'css' 		=> '#h4 {value;}',
		'csselement'		=> '#h4',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);

	// H5 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H5&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading Five typography.', 'bioship' ),
		'id'			=> 'h5_typography',
		'std'			=> array(
			'size'		=> '18px',
			'face'		=> 'Open+Sans',
			'style'		=> 'bold',
			'color'		=> '#181818'
		),
		'default' => array(
			'font-size'	=> '18px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'bold',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans'
		),
		// 'show_websafe_fonts' => false,
		'enqueue'		=> false,
		// 'css'		=> '#h5 {value;}',
		'csselement'		=> '#h5',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);

	// H6 Typography
	// -------------
	$options[] = array(
		'name'			=> __( '&lt;H6&gt; Heading Typography', 'bioship' ),
		'desc'			=> __( 'Heading Six typography.', 'bioship' ),
		'id'			=> 'h6_typography',
		'std'			=> array(
			'size'		=> '16px',
			'face'		=> 'Open+Sans',
			'style'		=> 'bold',
			'color'		=> '#181818'
		),
		'default'		=> array(
			'font-size'	=> '16px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'bold',
			'color'		=> '#181818',
			'font-family'	=> 'Open Sans'
		),
		// 'show_websafe_fonts' => false,
		'enqueue'		=> false,
		// 'css'		=> '#h6 {value;}',
		'csselement'		=> '#h6',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $headertype_options,
		'page'			=> 'basic',
	);


	// -------------
	// === Links ===
	// -------------

	// 2.2.0: added missing translation wrappers
	$link_options = array(
		'inherit'	=> __( 'Do Not Set', 'bioship' ),
		'underline'	=> __( 'Underline', 'bioship' ),
		'none'		=> __( 'No Underline', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Links', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Link Section
	// ------------
	$options[] = array(
		'name'		=> __( 'Link Styles', 'bioship' ),
		'desc'		=> '',
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Standard Links Colour
	// ---------------------
	$options[] = array(
		'name'		=> __( 'Link Color', 'bioship' ),
		'desc'		=> __( 'Default hyperlinks color (body a)', 'bioship' ),
		'id'		=> 'link_color',
		'std'		=> '#3568A9',
		// 'css'	=> 'body a {color: value;}',
		'csselement'	=> 'body a',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Link Underlines
	// ---------------
	$options[] = array(
		'name'		=> __( 'Link Underlines', 'bioship' ),
		'desc'		=> __( 'Underlines for hyperlinks (body a)', 'bioship' ),
		'id'		=> 'alinkunderline',
		'std'		=> 'none',
		'type'		=> 'radio',
		'class'		=> 'skin',
		// 'css'	=> 'body a {text-decoration: value;}',
		'csselement'	=> 'body a',
		'cssproperty'	=> 'text-decoration',
		'options'	=> $link_options,
		'page'		=> 'basic',
	);

	// Hover Links Colour
	// ------------------
	$options[] = array(
		'name'		=> __( 'Hover Link Color', 'bioship' ),
		'desc'		=> __( 'Hover hyperlinks color (body a:hover)', 'bioship' ),
		'id'		=> 'link_hover_color',
		'std'		=> '#3376EA',
		'class'		=> 'skin',
		// 'css'	=> 'body a:hover {color: value;}',
		'csselement'	=> 'body a:hover',
		'cssproperty'	=> 'color',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover Link Underlines
	// ---------------------
	// TODO: maybe add option for link hover highlighting ?
	// TODO: maybe add option for link hover underline/highlight colour ?
	$options[] = array(
		'name'		=> __( 'Link Hover Underlines', 'bioship' ),
		'desc'		=> __( 'Underlines for Hover hyperlinks (body a:hover)', 'bioship' ),
		'id'		=> 'alinkhoverunderline',
		'std'		=> 'underline',
		'type'		=> 'radio',
		'class'		=> 'skin',
		// 'css'	=> 'body a {text-decoration: value;}',
		'csselement'	=> 'body a:hover',
		'cssproperty'	=> 'text-decoration',
		'options'	=> $link_options,
		'page'		=> 'basic',
	);


	// ---------------
	// === Buttons ===
	// ---------------

	$options[] = array(
		'name'		=> __( 'Buttons', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Link Section
	// ------------
	$options[] = array(
		'name'		=> __( 'Button Styles', 'bioship' ),
		'desc'		=> '',
		'class' 	=> 'skin',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Button Typography
	// -----------------
	// 1.8.5: turned off some Titan options (for websafe fonts)
	$options[] = array(
		'name'			=> __( 'Button Typography', 'bioship' ),
		'desc'			=> __( 'Button Typography.', 'bioship' ),
		'id'			=> 'button_typography',
		'std'			=> array(
			'size'		=> '12px',
			'face'		=> 'helvetica, arial, "Nimbus Sans L", sans-serif',
			'style'		=> 'normal',
			'color' 	=> '#EEEEEE'
		),
		'default'		=> array(
			'font-size'	=> '14px',
			'font-style'	=> 'normal',
			'font-weight'	=> 'normal',
			'color'		=> '#EEEEEE',
			'font-family'	=> 'helvetica, arial, "Nimbus Sans L", sans-serif'
		),
		// 'css'		=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button {value;}',
		'csselement'		=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button',
		'cssproperty'		=> 'typography',
		'class'			=> 'skin',
		'type'			=> 'typography',
		'options'		=> $typography_options,
		'page'			=> 'basic',
		'show_google_fonts'	=> false,
		'show_letter_spacing'	=> false,
		'show_text_transform'	=> false,
		'show_font_variant'	=> false,
		'show_text_shadow'	=> false,
	);

	// Button Font Hover Colour
	// ------------------------
	$options[] = array(
		'name'		=> __( 'Button Font Hover Colour', 'bioship' ),
		'desc'		=> __( 'Button Font Hover Colour.', 'bioship' ),
		'id'		=> 'button_font_hover',
		'std'		=> '#FFFFFF',
		// 'css'	=> 'body button:hover, body input[type="submit"]:hover, body input[type="reset"]:hover, body input[type="button"]:hover, body a.button:hover, body .button a:hover, body .button:hover {color: value;}',
		'csselement'	=> 'body button:hover, body input[type="submit"]:hover, body input[type="reset"]:hover, body input[type="button"]:hover, body a.button:hover, body .button a:hover, body .button:hover',
		'cssproperty'	=> 'color',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Button Gradient Colour Top
	// --------------------------
	$options[] = array(
		'name'		=> __( 'Button Gradient Top Colour', 'bioship' ),
		'desc'		=> __( 'Default Buttons, Gradient Top Colour.', 'bioship' ),
		'id'		=> 'button_bgcolor_top',
		'std'		=> '#2a72c0',
		'class'		=> 'skin',
		'csselement'	=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button',
		'cssproperty'	=> 'backgroundtop', // pseudo
		'type'		=> 'color',
		'page'		=> 'basic' );

	// Button Gradient Colour Bottom
	// -----------------------------
	$options[] = array(
		'name'		=> __( 'Button Gradient Bottom Colour', 'bioship' ),
		'desc'		=> __( 'Default Buttons, Gradient Bottom Colour.', 'bioship' ),
		'id'		=> 'button_bgcolor_bottom',
		'std'		=> '#1d65b3',
		'class'		=> 'skin',
		'csselement'	=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button',
		'cssproperty'	=> 'backgroundbottom', // pseudo
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover Button Gradient Colour Top
	// --------------------------------
	$options[] = array(
		'name'		=> __( 'Hover Button Gradient Top Colour ', 'bioship' ),
		'desc'		=> __( 'On Button Hover, Gradient Top Colour.', 'bioship' ),
		'id'		=> 'button_hoverbg_top',
		'std'		=> '#156bc6',
		'class'		=> 'skin',
		'transport'	=> 'postMessage',
		'csselement'	=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button',
		'cssproperty'	=> 'backgroundtop:hover', // pseudo
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Hover Button Gradient Colour Bottom
	// -----------------------------------
	$options[] = array(
		'name'		=> __( 'Hover Button Gradient Bottom Colour', 'bioship' ),
		'desc'		=> __( 'On Button Hover, Gradient Bottom Colour.', 'bioship' ),
		'id'		=> 'button_hoverbg_bottom',
		'std'		=> '#156bc6',
		'class'		=> 'skin',
		'transport'	=> 'postMessage', // TODO: test
		'csselement'	=> 'body button, body input[type="reset"], body input[type="submit"], body input[type="button"], body a.button, body button a, body .button',
		'cssproperty'	=> 'backgroundbottom:hover', // pseudo
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Comment Buttons
	// ---------------
	// a.comment-edit-link, a.comment-reply-link
	$options[] = array(
		'name'		=> __( 'Comment Buttons', 'bioship' ),
		'desc'		=> __( 'Also apply the above button styling to make Edit/Reply Comment Links into buttons.', 'bioship' ),
		'id'		=> 'commentbuttons',
		'std'		=> '1',
		'class'		=> 'skin',
		'transport'	=> 'refresh', // TODO: postMessage?
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// WooCommerce Button Selectors
	// ----------------------------
	$options[] = array(
		'name'		=> __( 'WooCommerce Buttons', 'bioship' ),
		'desc'		=> __( 'Also apply the above button styling to WooCommerce CSS Button selectors.', 'bioship' ),
		'id'		=> 'woocommercebuttons',
		'std'		=> '',
		'class'		=> 'skin',
		'transport'	=> 'refresh', // TODO: postMessage?
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// TODO: add BuddyPress Button Selectors?

	// Extra Button CSS Selectors
	// --------------------------
	$options[] = array(
		'name'		=> __( 'Extra Button Selectors', 'bioship' ),
		'desc'		=> __( 'Also apply the above button styling to a comma separates list of CSS selectors with !important (note: hover rule will be added for each selector. Useful if default button code is being over-ridden by 3rd party stylesheets. eg. button.alm-load-more-btn.more for AJAX Load More Button.)', 'bioship' ),
		'id'		=> 'extrabuttonselectors',
		'std'		=> '',
		'class'		=> 'skin',
		'transport'	=> 'refresh', // TODO: postMessage?
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// TODO: maybe add Browser Specific Style Options tab?
	// TODO: maybe add Mobile Specific Style Options tab?
	// (using PHP Browser Detection plugin)


	// -------------
	// === Login ===
	// -------------

	$options[] = array(
		'name'		=> __( 'Login', 'bioship' ),
		'id'		=> 'skin',
		'type'		=> 'heading',
		'page'		=> 'both',
	);

	// Replace Login Logo
	// ------------------
	// TODO: use header background image option?
	$loginlogo_array = array(
		'none'		=> __( 'None - remove Logo', 'bioship' ),
		'default'	=> __( 'Default Wordpress Logo', 'bioship' ),
		'custom'	=> __( 'Use the header logo image', 'bioship' ),
		'upload'	=> __( 'Use uploaded image below', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Login Page Logo', 'bioship' ),
		'desc'		=> __( 'Replaces the Logo on your wp-login.php Page.', 'bioship' ),
		'id'		=> 'loginlogo',
		'std'		=> 'default',
		'type'		=> 'select',
		'class'		=> 'skin',
		'options'	=> $loginlogo_array,
		'page'		=> 'basic',
	);

	// Login Logo URL
	// --------------
	$options[] = array(
		'name'		=> __( 'Upload Login Page Logo', 'bioship' ),
		'desc'		=> __( 'Replaces Wordpress Logo on Login Page.', 'bioship' ),
		'id'		=> 'loginlogourl',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Login Form Box Background
	// -------------------------
	// 1.8.5: added this style option
	$options[] = array(
		'name'		=> __( 'Login Form Wrap Color', 'bioship' ),
		'desc'		=> __( 'Background Colour for the Login Form Box', 'bioship' ),
		'id'		=> 'loginwrapbgcolor',
		'std'		=> '#F0F0F0',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);

	// Login Form Wrap Background
	// --------------------------
	// 2.2.0: added this style option
	$options[] = array(
		'name'		=> __( 'Login Form Text Color', 'bioship' ),
		'desc'		=> __( 'Text Colour for the Login Form Box', 'bioship' ),
		'id'		=> 'loginwrapcolor',
		'std'		=> 'inherit',
		'class'		=> 'skin',
		'type'		=> 'color',
		'page'		=> 'basic',
	);


	// Login Background URL
	// --------------------
	$options[] = array(
		'name'		=> __( 'Login Background Image', 'bioship' ),
		'desc'		=> __( 'Leave blank to use main background image. For styling use body.login', 'bioship' ),
		'id'		=> 'loginbackgroundurl',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// --------------------------
	// Theme My Login Integration
	// --------------------------

	// TML Info
	// --------
	$options[] = array(
		'name'		=> __( 'Theme My Login', 'bioship' ),
		'desc'		=> __( 'Integrated TML Templates into Theme (if TML plugin is installed.)', 'bioship' ),
		'class'		=> 'skin',
		'type'		=> 'info',
		'page'		=> 'advanced',
	);

	// TML Templates
	// -------------
	// 2.2.0: default to on to automatically use improved hierarchy
	$options[] = array(
		'name'		=> __( 'Improve TML Template Hierarchy', 'bioship' ),
		'desc'		=> __( 'Makes TML look for TML templates in child and parent theme directories first (note: must be active for below image options to work!)', 'bioship' ),
		'id'		=> 'tmltemplates',
		'std'		=> '1',
		'class'		=> 'skin',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Login Button URL
	// ----------------
	$options[] = array(
		'name'		=> __( 'Login Button Image URL', 'bioship' ),
		'desc'		=> __( 'Replace TML login submit button with image.', 'bioship' ),
		'id'		=> 'loginbuttonurl',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'upload',
		'page'		=> 'advanced',
	);

	// Register Button URL
	// -------------------
	$options[] = array(
		'name'		=> __( 'Register Button Image URL', 'bioship' ),
		'desc'		=> __( 'Replace TML register submit button with image.', 'bioship' ),
		'id'		=> 'registerbuttonurl',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'upload',
		'page'		=> 'advanced',
	);

	// Update Button URL
	// -----------------
	$options[] = array(
		'name'		=> __( 'Profile Update Button Image URL', 'bioship' ),
		'desc'		=> __( 'Replace TML profile update submit button with image.', 'bioship' ),
		'id'		=> 'profilebuttonurl',
		'std'		=> '',
		'class'		=> 'skin',
		'type'		=> 'upload',
		'page'		=> 'advanced',
	);

	// Logo on Register
	// ----------------
	$options[] = array(
		'name'		=> __( 'Login Logo on Register Form', 'bioship' ),
		'desc'		=> __( 'Add Admin login logo to top of TML Register Form.', 'bioship' ),
		'id'		=> 'registerformimage',
		'std'		=> '0',
		'class'		=> 'skin',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Logo on Login
	// -------------
	$options[] = array(
		'name'		=> __( 'Login Logo on Login Form', 'bioship' ),
		'desc'		=> __( 'Add Admin login logo to top of TML Login Form.', 'bioship' ),
		'id'		=> 'loginformimage',
		'std'		=> '0',
		'class'		=> 'skin',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// TODO: Logo on Profile Form?


	// ==============
	// === MUSCLE ===
	// ==============

	// ---------------
	// === Scripts ===
	// ---------------
	// note: use transport refresh for most scripts...

	$options[] = array(
		'name'		=> __( 'Scripts', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced',
	);

	// 1.5.0: Deprecated (page cache fail) - Add Browser Classes
	// $options[] = array(
	//	'name'		=> __('Add Browser Classes', 'bioship'),
	//	'desc'		=> __('Detects Browser and adds related classes to &lt;body&gt; tag to help with cross browser styling<br>(lynx, gecko, opera, ns4, safari, chrome, ie, macie, winie, unknown, iphone)', 'bioship'),
	//	'id'		=> 'browserclass',
	//	'std'		=> '1',
	//	'class'		=> 'muscle',
	//	'type'		=> 'checkbox'
	// );

	// Javascript Cache Busting
	// ------------------------
	// 2.0.7: change default to filemtime
	$options[] = array(
		'name'		=> __( 'Cache Busting for Javascripts', 'bioship' ),
		'desc'		=> __( 'Querystring used as version for cache busting Javascript files.', 'bioship' ),
		'id'		=> 'javascriptcachebusting',
		'std'		=> 'filemtime',
		'class'		=> 'muscle',
		'type'		=> 'select', // was radio
		'options'	=> $cachebusting_options,
		'page'		=> 'advanced'
	);

	// Load jQuery from Google CDN
	// ---------------------------
	$options[] = array(
		'name'		=> __( 'Load jQuery from Google CDN', 'bioship' ),
		'desc'		=> __( 'Loads jQuery from the Google CDN instead of the WordPress copy. Can speed up pageload as it is probably already cached in most browsers. (Automatically falls back to local copy if Google CDN is unreachable.)', 'bioship' ),
		'id'		=> 'jquerygooglecdn',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Load PrefixFree
	// ---------------
	$options[] = array(
		'name'		=> __( 'Load PrefixFree Javascript', 'bioship' ),
		'desc'		=> __( 'Auto-adds browser-specific prefixes to unprefixed CSS code.', 'bioship')
				. ' ('.__( 'See', 'bioship' ) . ' <a href="http://leaverou.github.io/prefixfree/" target="_blank">PrefixFree</a> '
				. __( 'for details.', 'bioship' ) . ' ' . __( 'Dynamic DOM and jQuery plugins included.', 'bioship' ) . ')',
		'id'		=> 'prefixfree',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Internet Explorer Supports
	// --------------------------
	// 1.8.5: added IE8 DOM Fix
	$iesupports_array = array(
		'selectivizr' 	=> __( 'Selectivizr (CSS3 Selectors for IE6-IE8)', 'bioship' ),
		'html5shiv' 	=> __( 'HTML5 Shiv (HTML5 for IE6-9)', 'bioship' ),
		'supersleight' 	=> __( 'SuperSleight (Transparent PNGs IE6-)', 'bioship' ),
		'ie8'		=> __( 'DOM Fix (IE8 only)', 'bioship' ),
		'flexibility' 	=> __( 'Flexibility (Flexbox Polyfill IE8-9)', 'bioship' )
	);
	$iesupports_default = array(
		'selectivizr'	=> '1',
		'html5shiv'	=> '1',
		'supersleight'	=> '1',
		'flexibility'	=> '0',
	);
	$options[] = array(
		'name'		=> __( 'Internet Exporer Specific Supports', 'bioship' ),
		'desc'		=> __( 'Load', 'bioship' ) . ' <a href="http://selectivizr.com" target="_blank">Selectivizr</a>, '.__( 'HTML5, SuperSleight and/or Flexibility.', 'bioship' ),
		'id'		=> 'iesupports',
		'std'		=> $iesupports_default,
		'default'	=> $iesupports_default,
		'class'		=> 'muscle',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $iesupports_array,
		'page'		=> 'advanced',
	);

	// Media Queries Support
	// ---------------------
	$mediaqueries_options = array(
		'off'		=> __( 'Do not load polyfill support', 'bioship' ),
		'respond'	=> __( 'Respond.js: Fast and lightweight', 'bioship' ),
		'mediaqueries'	=> __( 'MediaQueries.js: Slower but fuller support' ,'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Media Queries Javascript', 'bioship' ),
		'desc'		=> __( 'Polyfill to help fix CSS3 Media Queries for older browsers eg. IE6-IE8. (See ', 'bioship' ) .
			'<a href="https://github.com/scottjehl/Respond" target=_blank>Respond</a> ' . __( 'and', 'bioship' ) .
			' <a href="https://code.google.com/p/css3-mediaqueries-js/" target=_blank>MediaQueries</a>)',
		'id'		=> 'mediaqueries',
		'std'		=> 'respond',
		'class'		=> 'muscle',
		'type'		=> 'select',
		'transport'	=> 'refresh',
		'options'	=> $mediaqueries_options,
		'page'		=> 'advanced',
	);

	// Fastclick Javascript
	// --------------------
	$options[] = array(
		'name'		=> __( 'Load Fastclick Javascript', 'bioship' ),
		'desc'		=> __( 'Loads fastclick.js (polyfill to remove click delays on browsers with touch UIs.)', 'bioship' ),
		'id'		=> 'loadfastclick',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Mousewheel Javascript
	// ---------------------
	$options[] = array(
		'name'		=> __( 'Load Mousewheel Javascript', 'bioship' ),
		'desc'		=> __( 'Loads mousewheel.js (polyfill to enable mousewheel on older browsers.)', 'bioship' ),
		'id'		=> 'loadmousewheel',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Load NWMatcher
	// --------------
	// 2.2.0: fix to name typo (NW Watcher)
	// 2.2.0: update link to github repository
	$options[] = array(
		'name'		=> __( 'Load NW Matcher Javascript', 'bioship' ),
		'desc'		=> __( 'Adds further CSS selector support.', 'bioship' ) . ' <a href="https://github.com/dperini/nwmatcher" target="_blank">' . __( 'More Details', 'bioship' ) . '</a>.',
		'id'		=> 'nwwatcher',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Load NWEvents
	// -------------
	$options[] = array(
		'name'		=> __( 'Load NW Event Javascript', 'bioship' ),
		'desc'		=> __( 'Adds improved Event Manager support. Requires NW Matcher.', 'bioship' ) . ' <a href="https://github.com/dperini/nwevents" target="_blank">' . __( 'More Details', 'bioship' ) . '</a>.',
		'id'		=> 'nwevents',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Load Modernizr
	// --------------
	$modernizr_options = array(
		'off'		=> __( 'Do Not Load', 'bioship' ),
		'production'	=> __( 'Load Production version', 'bioship' ),
		'development'	=> __( 'Load Development version', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Load Modernizr', 'bioship' ),
		'desc'		=> __( 'Whether to load','bioship' ) . ' <a href="http://modernizr.com" target=_blank>Modernizr</a> ' . __('javascript or not, production (~10kb) or development (~50kb) Note: required for loading Foundation.', 'bioship' ),
		'id'		=> 'loadmodernizr',
		'std'		=> 'off',
		'class'		=> 'muscle',
		'type'		=> 'select',
		'transport'	=> 'refresh',
		'options'	=> $modernizr_options,
		'page'		=> 'advanced',
	);

	// CSS Supports
	// ------------
	$options[] = array(
		'name'		=> __( 'Load CSS.Supports Javascript', 'bioship' ),
		'desc'		=> __( 'Loads CSS.Supports (polyfill for Javascript API to check for CSS rule support.)', 'bioship' ),
		'id'		=> 'loadcsssupports',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// MatchMedia
	// ----------
	$options[] = array(
		'name'		=> __( 'Load MatchMedia Javascript', 'bioship' ),
		'desc'		=> __( 'Loads MatchMedia (polyfill for Javascript MatchMedia media query support.)', 'bioship' ),
		'id'		=> 'loadmatchmedia',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);


	// --------------
	// === Extras ===
	// --------------

	$options[] = array(
		'name'		=> __( 'Extras', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced',
	);

	// Discreet Text Widget
	// 1.8.5: removed option, always on by default
	// $options[] = array(
	//	'name'	=> __( 'Enable Discreet Text Widget', 'bioship' ),
	//	'desc'	=> __( 'Adds a new text widget type that only shows if not empty.', 'bioship' ) . '<br>' .__( 'Useful for executing shortcodes within widgets (thus avoiding widget titles with no content or unwanted spaces.)', 'bioship' ) . '<br>' . __( 'Warning: check for active discreet text widgets before disabling.', 'bioship' ),
	//	'id'	=> 'discreetwidget',
	//	'std'	=> '1',
	//	'class'	=> 'skeleton',
	//	'transport'	=> 'refresh',
	//	'type'		=> 'checkbox');

	// Disable Emojis
	// --------------
	// 1.9.5: added this option
	$options[] = array(
		'name'		=> __( 'Disable Emojis', 'bioship' ),
		'desc'		=> __( 'Unqueues Emoji scripts and styles from frontend and backend.', 'bioship' ),
		'id'		=> 'disableemojis',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Smooth Scrolling
	// ----------------
	$options[] = array(
		'name'		=> __( 'Load Smooth Scrolling', 'bioship' ),
		'desc'		=> __( 'Loads jQuery Smooth Scrolling for #hash (a name) links.', 'bioship' ),
		'id'		=> 'smoothscrolling',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced'
	);

	// jQuery Match Height
	// -------------------
	$options[] = array(
		'name'		=> __( 'Load jQuery Match Height', 'bioship' ),
		'desc'		=> __( 'Loads jQuery MatchHeight to apply matching heights to any page element with .matchheight class.', 'bioship' ),
		'id'		=> 'loadmatchheight',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// jQuery Sticky Kit
	// -----------------
	$options[] = array(
		'name'		=> __( 'Load jQuery Sticky Kit', 'bioship' ),
		'desc'		=> __( 'Loads jQuery Sticky Kit for smart scrolling page elements inside parent element.', 'bioship' ),
		'id'		=> 'loadstickykit',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Sticky Kit Elements
	// -------------------
	$options[] = array(
		'name'		=> __( 'Sticky Kit Elements', 'bioship' ),
		'desc'		=> __( 'List of elements to apply Sticky Kit to, comma separated.', 'bioship' ),
		'id'		=> 'stickyelements',
		'std'		=> '#sidebar,#subsidebar',
		'class'		=> 'mini muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	// Load Fitvids
	// ------------
	$options[] = array(
		'name'		=> __( 'Load FitVids Javascript', 'bioship' ),
		'desc'		=> __( 'Loads fitvids.js and applies it to the selected elements (auto-fits video widths to element area.)', 'bioship' ),
		'id'		=> 'loadfitvids',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Fitvids Elements
	// ----------------
	$options[] = array(
		'name'		=> __( 'FitVids Elements', 'bioship' ),
		'desc'		=> __( 'List of elements to apply Fitvids to, comma separated.', 'bioship' ),
		'id'		=> 'fitvidselements',
		'std'		=> '#content,.videowrapper',
		'class'		=> 'mini muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	// jQuery Scroll To Fixed
	// ----------------------
	$options[] = array(
		'name'	=> __( 'Load jQuery Scroll To Fixed', 'bioship' ),
		'desc'		=> __( 'Loads jQuery Scroll To Fixed for floating fixed position scrolling elements.', 'bioship' ),
		'id'		=> 'loadscrolltofixed',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);


	// ---------------
	// === Reading ===
	// ---------------

	$options[] = array(
		'name'		=> __( 'Reading', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'basic',
	);

	// Site Credits
	// ------------
	$options[] = array(
		'name'		=> __( 'Site Credits', 'bioship' ),
		'desc'		=> __( 'Set the site credits to display in the Footer. 0 for blank.', 'bioship' ),
		'id'		=> 'sitecredits',
		'std'		=> '',
		'class'		=> 'muscle',
		'transport'	=> 'postMessage',
		'type'		=> 'text',
		'page'		=> 'basic',
	);

	// Home Category Mode
	// ------------------
	$homeblog_array = array(
		'all'			=> __( 'All Categories', 'bioship' ),
		'include'		=> __( 'Include Selected Categories', 'bioship' ),
		'exclude'		=> __( 'Exclude Selected Categories', 'bioship' ),
		'includeexclude'	=> __( 'Include and Exclude Categories', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( '"Home" Blog Category Mode', 'bioship' ),
		'desc'		=> __( 'Whether to filter category query on "Home" Blog Page.', 'bioship' ),
		'id'		=> 'homecategorymode',
		'std'		=> 'all',
		'type'		=> 'select', // was radio
		'class'		=> 'muscle',
		'options'	=> $homeblog_array,
		'page'		=> 'basic',
	);

	// Select Categories [deprecated method]
	// if ( $options_categories ) {
	//	$homecats_defaults = array();
	//	$options[] = array(
	//		'name'		=> __( 'Include Selected Categories', 'bioship' ),
	//		'desc'		=> __( 'Used if Category Mode above is include or both include/exclude.', 'bioship' ),
	//		'id'		=> 'homecategories',
	//		'std'		=> $homecats_defaults,
	//		'type'		=> 'multicheck',
	//		'class'		=> 'muscle',
	//		'options'	=> $options_categories);
	// }

	// Select Include Categories
	// -------------------------
	if ( $options_categories ) {
		$homecats_defaults = array();
		$options[] = array(
			'name'		=> __( 'Include Selected Categories', 'bioship' ),
			'desc'		=> __( 'Used if Category Mode above is include or both include/exclude.', 'bioship' ),
			'id'		=> 'homeincludecategories',
			'std'		=> $homecats_defaults,
			'type'		=> 'multicheck',
			'class'		=> 'muscle',
			'transport'	=> 'refresh',
			'options'	=> $options_categories,
			'page'		=> 'basic',
		);
	}

	// Select Exclude Categories
	// -------------------------
	if ( $options_categories ) {
		$homecats_defaults = array();
		$options[] = array(
			'name'		=> __( 'Exclude Selected Categories', 'bioship' ),
			'desc'		=> __( 'Used if Category Mode above is exclude or both include/exclude.', 'bioship' ),
			'id'		=> 'homeexcludecategories',
			'std'		=> $homecats_defaults,
			'type'		=> 'multicheck',
			'class'		=> 'muscle',
			'transport'	=> 'refresh',
			'options'	=> $options_categories,
			'page'		=> 'basic',
		);
	}

	// Include CPTs on Home Page? Off. Leave to devs.
	//	$cpt_defaults = array( 'post' => '1' );
	//	$options[] = array(
	//		'name'		=> __( 'Add Custom Post Types to "Home" Blog Page Results', 'bioship' ),
	//		'desc'		=> __( 'Includes the selected Custom Post Types in the Home Blog page.', 'bioship' ),
	//		'id'		=> 'homeblogcpts',
	//		'std'		=> $cpt_defaults,
	//		'type'		=> 'multicheck',
	//		'class'		=> 'muscle',
	//		'options'	=> $cpt_options);

	// Number of Search Results
	// ------------------------
	// 1.8.5: add number sanitization
	$options[] = array(
		'name'			=> __( 'Search Results per Page', 'bioship' ),
		'desc'			=> __( 'Number of search results to show for keyword search.', 'bioship' ),
		'id'			=> 'searchresults',
		'std'			=> '30',
		'class'			=> 'mini muscle',
		'type'			=> 'text',
		'sanitize_callback'	=> 'fallback_sanitize_number',
		'page'			=> 'basic',
	);

	// Make CPTs Searchable
	// --------------------
	$cpt_defaults = array(
		'post'	=> '1',
		'page'	=> '1'
	);
	$options[] = array(
		'name'		=> __( 'Searchable Post Types', 'bioship' ),
		'desc'		=> __( 'Includes the selected Post Types in the site search results.', 'bioship' ),
		'id'		=> 'searchablecpts',
		'std'		=> $cpt_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'muscle',
		'options'	=> $cpt_options,
		'page'		=> 'basic',
	);

	// Jetpack Infinite Scroll
	// -----------------------
	$scroll_options = array(
		'disable'	=> __( 'Disable Support', 'bioship' ),
		'scroll'	=> __( 'Autoload Scrolling', 'bioship' ),
		'click'		=> __( 'Click to Load Link', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Jetpack Infinite Scroll', 'bioship' ),
		'desc'		=> __( 'Adds theme support for Jetpack Infinite Scroll.', 'bioship' ) . ' ' . __( 'Alternatively use', 'bioship').' <a href="https://wordpress.org/plugins/ajax-load-more/" target=_blank>AJAX Load More</a> (' . __( 'with loop template', 'bioship') . ' <i>/templates/ajax-load-more/</i>)',
		'id'		=> 'infinitescroll',
		'std'		=> 'disable',
		'type'		=> 'select',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'options'	=> $scroll_options,
		'page'		=> 'basic',
	);

	// -----------------
	// Excerpt/Read More
	// -----------------

	$options[] = array(
		'name'		=> __( 'Excerpts', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'both',
	);

	// Page Excerpts
	// -------------
	// 1.8.0: page excerpt support option
	$options[] = array(
		'name'		=> __( 'Page Excerpts', 'bioship' ),
		'desc'		=> __( 'Add Excerpt Support to Pages', 'bioship' ),
		'id'		=> 'pageexcerpts',
		'std'		=> '0',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Excerpt Shortcodes
	// ------------------
	$options[] = array(
		'name'		=> __( 'Excerpt Shortcodes', 'bioship' ),
		'desc'		=> __( 'Process Shortcodes in Excerpts. (Note: formatting is still stripped from shortcode output.)', 'bioship' ),
		'id'		=> 'excerptshortcodes',
		'std'		=> '1',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Excerpt Length
	// --------------
	// 1.8.5: number sanitization
	$options[] = array(
		'name'			=> __( 'Excerpt Length', 'bioship' ),
		'desc'			=> __( 'Number of words to show in Excerpts.', 'bioship' ) . ' (' . __( 'Blank for default of 55, 0 for full content.', 'bioship' ) . ')',
		'id'			=> 'excerptlength',
		'std'			=> '100',
		'class'			=> 'mini muscle',
		'transport'		=> 'refresh',
		'type'			=> 'text',
		'sanitize_callback'	=> 'fallback_sanitize_number',
		'page' 			=> 'basic',
	);

	// Read More Anchor
	// ----------------
	$options[] = array(
		'name'			=> __( 'Read More Anchor', 'bioship' ),
		'desc'			=> __( 'Anchor Text for the Read More Link.', 'bioship' ),
		'id'			=> 'readmoreanchor',
		'std'			=> 'Continue reading <span class="meta-nav">&rarr;</span>',
		'class'			=> 'muscle',
		'transport'		=> 'refresh',
		'type'			=> 'text',
		'page'			=> 'basic',
	);

	// Read More Link
	// --------------
	$options[] = array(
		'name'			=> __( 'Before Read More Link', 'bioship' ),
		'desc'			=> __( 'Replaces ... (ellipsis) before Read More link.', 'bioship'),
		'id'			=> 'readmorebefore',
		'std'			=> '&hellip;',
		'class'			=> 'mini muscle',
		'transport'		=> 'refresh',
		'type'			=> 'text',
		'page'			=> 'basic',
	);


	// ------------
	// === Meta ===
	// ------------

	// TODO: author archive author box options
	$options[] = array(
		'name'		=> __( 'Meta', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced'
	);

	// Post Meta Formatting Reference
	// ------------------------------
	$rvdesc = __( 'Replacement Values', 'bioship' ) . ':<br>';
	$rvdesc .= '#DATELINK# - ' . __( 'Post/Page Permalink with Anchor of Post Date.', 'bioship' ) . '<br>';
	$rvdesc .= '#PERMALINK# - ' . __( 'Post/Page Permalink with Anchor of "Permalink"', 'bioship' ) . '<br>';
	$rvdesc .= '#PARENTPAGE# - ' . __( 'Linkless name of the Page Parent (if any)', 'bioship' ) . '<br>';
	$rvdesc .= '#PARENTLINK# - ' . __( 'Linked name of the Page Parent (if any)', 'bioship' ).'<br>';
	$rvdesc .= '#CATEGORIES# - ' . __( 'Linked comma separated post category list (<i>get_the_category_list</i>)', 'bioship' ) . '<br>';
	$rvdesc .= '#CATSLIST# - ' . __( 'Outputs "Category: "', 'bioship' ).' {<i>get_the_category_list</i>} (' . __('blank if none','bioship' ) . ')<br>';
	$rvdesc .= '#POSTTAGS# - ' . __( 'Linked comma separated post tag list', 'bioship' ) . ' (<i>get_the_tag_list</i>)<br>';
	$rvdesc .= '#TAGSLIST# - ' . __( 'Outputs "Tagged: "', 'bioship' ) . ' {<i>get_the_tag_list</i>} (' . __( 'blank if none', 'bioship' ) . ')<br>';
	$rvdesc .= '#COMMENTS# - ' . __( 'Number of Comments with Comments Link', 'bioship' ) . '<br>';
	$rvdesc .= '#COMMENTSPOPUP# - ' . __( 'Number of Comments with Popup Link', 'bioship') . ' (<i>comments_popup_link</i>)<br>';
	$rvdesc .= '#AUTHORNAME# - ' . __( 'Linkless Author Display Name', 'bioship' ) . ' (<i>the_author</i>)<br>';
	$rvdesc .= '#AUTHORURL# - ' . __( 'Author Posts Link URL (only) - no anchor','bioship' ) . ' (<i>author_posts_link</i>)<br>';
	$rvdesc .= '#AUTHOR# - ' . __( 'Author Posts Link with Author name anchor','bioship' ) . ' (<i>author_posts_link</i>)<br>';
	$rvdesc .= '#AUTHORLINK# - ' . __( 'Author Link with View (posttype) by Author anchor', 'bioship' ) . ' (<i>author_posts_link</i>)<br>';
	$rvdesc .= '#EDITLINK# - ' . __( 'Post/Page (admin/editor) Edit Link', 'bioship' ) . ' (<i>edit_post_link</i>)<br>';
	$rvdesc .= '#NEWLINE# - ' . __( 'Insert a Line Break', 'bioship' ) . ' (&lt;br&gt;)<br>';
	$rvdesc .= __( 'Note: Categories and Tags also work for Custom Post Type taxonomies.', 'bioship' ) . '<br>';

	$options[] = array(
		'name'		=> __( 'Post Meta Formatting', 'bioship' ),
		'desc'		=> $rvdesc,
		'class'		=> 'muscle',
		'type'		=> 'info',
		'page'		=> 'advanced',
	);

	// Post Meta Top
	// -------------
	$options[] = array(
		'name'		=> __( 'Post Meta Top', 'bioship' ),
		'desc'		=> __( 'The byline and author info to display below the post title.', 'bioship' ),
		'id'		=> 'postmetatop',
		'std'		=> 'Posted on #DATELINK# by #AUTHOR#. #COMMENTSPOPUP# #EDITLINK#',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	// Post Meta Bottom
	// ----------------
	$options[] = array(
		'name'		=> __( 'Post Meta Bottom', 'bioship' ),
		'desc'		=> __( 'The category/tags etc. to display below the post content.', 'bioship' ),
		'id'		=> 'postmetabottom',
		'std'		=> '#CATSLIST#<br>#TAGSLIST#',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	// Page Meta Top
	// -------------
	$options[] = array(
		'name'		=> __( 'Page Meta Top', 'bioship'),
		'desc'		=> __( 'The byline etc. to display below the page title.', 'bioship' ),
		'id'		=> 'pagemetatop',
		'std'		=> '',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	// Post Meta Bottom
	// ----------------
	$options[] = array(
		'name'		=> __( 'Page Meta Bottom', 'bioship' ),
		'desc'		=> __( 'What to display below the page content.', 'bioship' ),
		'id'		=> 'pagemetabottom',
		'std'		=> '#EDITLINK#',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'text',
		'page'		=> 'advanced',
	);

	$options[] = array(
		'name'		=> __( 'Custom Post Type Metas', 'bioship' ),
		'desc'		=> __( 'Note: Meta Top and Bottom for Custom Post Types can be set via filters.', 'bioship' ),
		'class'		=> 'muscle',
		'type'		=> 'info',
		'page'		=> 'advanced',
	);

	// Entry Meta Top in Post Lists
	// ----------------------------
	$options[] = array(
		'name'		=> __( 'Entry Meta Top in Post Lists', 'bioship' ),
		'desc'		=> __( 'Whether to show the entry meta top in post lists such as archives, category pages, searches etc.', 'bioship' ),
		'id'		=> 'listentrymetatop',
		'std'		=> '1',
		'class' 	=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Entry Meta Bottom in Post Lists
	// -------------------------------
	$options[] = array(
		'name'		=> __( 'Entry Meta Bottom in Post Lists', 'bioship' ),
		'desc'		=> __( 'Whether to show the entry meta bottom in post lists such as archives, category pages, searches etc.', 'bioship' ),
		'id'		=> 'listentrymetabottom',
		'std'		=> '0',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Author Bio
	// ----------
	$options[] = array(
		'name'		=> __( 'Author Bio', 'bioship' ),
		'desc'		=> '',
		'class'		=> 'muscle',
		'type'		=> 'info',
		'page'		=> 'advanced',
	);

	// TODO: author bio box position for author archive pages?

	// Show Author Bio for CPTs
	// ------------------------
	$cpt_defaults = array( 'post' => '1' );
	$options[] = array(
		'name'		=> __( 'Author Bio Box', 'bioship' ),
		'desc'		=> __( 'Show Author Biography Box for selected Post Types.', 'bioship' ) . ' (' . __( 'Only shows if author description is not empty.', 'bioship' ) . ')',
		'id'		=> 'authorbiocpts',
		'std'		=> $cpt_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'options' 	=> $cpt_options,
		'page'		=> 'advanced',
	);

	// Bio Box Position
	// ----------------
	$biopos_array = array(
		'none'		=> __( 'Do Not Display', 'bioship' ),
		'top'		=> __( 'Top Position', 'bioship' ),
		'bottom'	=> __( 'Bottom Position', 'bioship' ),
		'topandbottom'	=> __( 'Top and Bottom', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __('Bio Box Position', 'bioship'),
		'desc'		=> __('Where to show the Author Bio Box for select posted types.', 'bioship'),
		'id'		=> 'authorbiopos',
		'std'		=> 'bottom',
		'type'		=> 'select',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'options'	=> $biopos_array,
		'page'		=> 'advanced',
	);

	// Author Avatar Size
	// ------------------
	// 1.8.5: number sanitization
	$options[] = array(
		'name'			=> __( 'Author Avatar Size', 'bioship' ),
		'desc'			=> __( 'The squared size of the Author Avatar in the Bio Box (default 64)', 'bioship' ),
		'id'			=> 'authoravatarsize',
		'std'			=> '64',
		'class'			=> 'mini muscle',
		'transport'		=> 'refresh',
		'type'			=> 'text',
		'sanitize_callback'	=> 'fallback_sanitize_number',
		'page'			=> 'advanced',
	);


	// ---------------
	// === Writing ===
	// ---------------
	// TODO: add multicheck option for Theme Options Metabox on CPTs

	$options[] = array(
		'name'		=> __( 'Writing', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced',
	);

	// Dynamic Editor Styles
	// ---------------------
	// 1.9.5: added this option
	$options[] = array(
		'name'		=> 'Dynamic Editor Styles',
		'desc'		=> __( 'Automatically add relevent theme styles to the TinyMCE Post Writing Editor.', 'bioship' ),
		'id'		=> 'dynamiceditorstyles',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// QuickSave/AutoSave Net
	// 1.8.0: [deprecated] plugin to be installed/activated via TGMPA
	// $options[] = array(
	//	'name'		=> __( 'Load AutoSave Net', 'bioship' ),
	//	'desc'		=> __( 'Loads internal <a href="https://wordquest.org/plugins/autosave-net/" target=_blank>QuickSave Content Backup Timer</a> plugin to prevent editing content loss. Auto-save on steroids. (Note: if the plugin is installed, plugin has precedence to prevent conflicts.)', 'bioship' ),
	//	'id'		=> 'loadquicksave',
	//	'std'		=> '1',
	//	'class'		=> 'muscle',
	//	'type'		=> 'checkbox',
	// );

	// Limit Post Revisions
	// [deprecated] unworking method, leave this for plugin territory!
	// $options[] = array(
	//	'name'		=> __( 'Limit Post Revisions', 'bioship' ),
	//	'desc'		=> __( 'Set maximum number of Post Revisions stored to avoid Database bloat. Blank or 0 for off. Does not override if already set in wp-config.php. To limit revisions by post type, use <a href="https://wordpress.org/plugins/revision-control/" target=_blank>Revision Control</a> plugin.', 'bioship' ),
	//	'id'		=> 'postrevisions',
	//	'std'		=> '20',
	//	'class'		=> 'mini muscle',
	//	'type'		=> 'text',
	// );

	// Post Format Support
	// -------------------
	$options[] = array(
		'name'		=> __( 'Post Formats Support', 'bioship' ),
		'desc'		=> __( 'Adds specified Post Formats selection to the post writing screen.', 'bioship'),
		'id'		=> 'postformatsupport',
		'std'		=> '0',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Active Post Formats
	// -------------------
	$post_formats = array(
		'aside'		=> __( 'Aside', 'bioship' ),
		'audio'		=> __( 'Audio', 'bioship' ),
		'chat'		=> __( 'Chat', 'bioship' ),
		'image'		=> __( 'Image', 'bioship' ),
		'gallery'	=> __( 'Gallery', 'bioship' ),
		'link'		=> __( 'Link', 'bioship' ),
		'quote'		=> __( 'Quote', 'bioship' ),
		'status'	=> __( 'Status', 'bioship' ),
		'video'		=> __( 'Video', 'bioship' )
	);
	$multicheck_defaults = array(
		'aside'		=> '1',
		'audio'		=> '1',
		'chat'		=> '0',
		'image'		=> '1',
		'gallery'	=> '1',
		'link'		=> '0',
		'quote'		=> '0',
		'status'	=> '0',
		'video'		=> '1',
	);
	$options[] = array(
		'name'		=> __( 'Active Post Formats', 'bioship' ),
		'desc'		=> __( 'Make these post formats available (if Post Formats Support is active.)', 'bioship' ),
		'id'		=> 'postformats',
		'std'		=> $multicheck_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'muscle',
		'options'	=> $post_formats,
		'page'		=> 'advanced',
	);

	// WP Subtitle Support
	// -------------------
	$subtitle_defaults = array(
		'post'	=> '1',
		'page'	=> '1'
	);
	$options[] = array(
		'name'		=> __( 'Subtitle Support', 'bioship' ),
		'desc'		=> __( 'Add Subtitles Field to specified Post Types.', 'bioship' ) . '<br>' . __( 'Requires', 'bioship' ) . ' <a href="http://wordpress.org/plugins/wp-subtitle/" target="_blank">' . __( 'WP Subtitle','bioship' ) .'</a>',
		'id'		=> 'subtitlecpts',
		'std'		=> $subtitle_defaults,
		'class'		=> 'muscle',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $cpt_options,
		'page'		=> 'advanced',
	);


	// -----------
	// === RSS ===
	// -----------

	$options[] = array(
		'name'		=> __( 'RSS', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced',
	);

	// Automatic Feed Links
	// --------------------
	$options[] = array(
		'name'		=> __( 'Automatic Feed Links Theme Support', 'bioship' ),
		'desc'		=> __( 'Automatically Add Feed Links to page &lt;head&gt;', 'bioship' ),
		'id'		=> 'autofeedlinks',
		'std'		=> '1',
		'class' 	=> 'muscle',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// 2.0.9: no RSS options for wordpress.org version
	if ( !THEMEWPORG ) {

		// RSS Excerpt Length
		// ------------------
		// 1.8.5: sanitize number callback
		$options[] = array(
			'name'			=> __( 'RSS Item Excerpt Length', 'bioship' ),
			'desc'			=> __( 'Number of words to show in RSS Feed Excerpts.', 'bioship' ) . ' (' . __( 'Blank for excerpt default, 0 for full content.' ,'bioship' ) . ')',
			'id'			=> 'rssexcerptlength',
			'std'			=> '',
			'class'			=> 'mini muscle',
			'type'			=> 'text',
			'sanitize_callback'	=> 'fallback_sanitize_number',
			'page'			=> 'advanced',
		);

		// RSS Publish Delay
		// -----------------
		// 1.8.5: sanitize number callback
		$options[] = array(
			'name'			=> __( 'RSS Publish Delay', 'bioship' ),
			'desc'			=> __( 'Delays Publishing of Post to RSS Feed for x minutes. Helpful to let you fix typos etc.', 'bioship' ) . ' (' . __( 'Blank or 0 for off.', 'bioship' ) . ')',
			'id'			=> 'rsspublishdelay',
			'std'			=> '10',
			'class'			=> 'mini muscle',
			'type'			=> 'text',
			'sanitize_callback'	=> 'fallback_sanitize_number',
			'page'			=> 'advanced',
		);

		// Post Types in Feed
		// ------------------
		$cpt_defaults = array();
		$options[] = array(
			'name'			=> __( 'Post Types in RSS Feed', 'bioship' ),
			'desc'			=> __( 'Include the selected Post Types in the main RSS Feed. Uncheck all to not use this post type filtering.', 'bioship' ),
			'id'			=> 'cptsinfeed',
			'std'			=> '',
			'type'			=> 'multicheck',
			'class'			=> 'muscle',
			'options'		=> $cpt_options,
			'page'			=> 'advanced',
		);

		// Full Content Page Feeds
		// -----------------------
		// 1.8.5: added this option
		$options[] = array(
			'name'			=> __( 'Page Content Feeds', 'bioship' ),
			'desc'			=> __( 'Output full page content for page feed links','bioship').' (<i>/pagename/feed/</i>) '.__('instead of page comments', 'bioship' ) . ' (' . __( 'comments will then be available via', 'bioship' ) . ' <i>/pagename/feed/?withcomments=1</i>',
			'id'			=> 'pagecontentfeeds',
			'std'			=> '0',
			'class'			=> 'muscle',
			'type'			=> 'checkbox',
			'page'			=> 'advanced',
		);
	}
	// end no RSS options for wordpress.org version


	// -------------
	// === Admin ===
	// -------------

	$options[] = array(
		'name'		=> __( 'Admin', 'bioship' ),
		'id'		=> 'muscle',
		'type'		=> 'heading',
		'page'		=> 'advanced',
	);

	// 1.5.0: Theme Update Admin Notification
	// TODO: maybe resurrect this at some stage?
	// TODO: maybe add an admin email for updates
	// $options[] = array(
	//	'name' => __('Admin Update Notification', 'bioship'),
	//	'desc' => __('Display Theme Update Available Notifications in the Admin Header Notices (for capability update_themes)', 'bioship'),
	//	'id' => 'admindisplayupdates',
	//	'std' => '0',
	//	'class' => 'muscle',
	//	'type' => 'checkbox');

	// Disable Self Pings
	// ------------------
	$options[] = array(
		'name'		=> __( 'Disable Self Pings', 'bioship' ),
		'desc'		=> __( 'Stops pingbacks (trackback links) sent from this site to itself.', 'bioship' ),
		'id'		=> 'disableselfpings',
		'std'		=> '1',
		'class' 	=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Disable Widgets Block Editor
	// ----------------------------
	// 2.2.0: added for WP 5.8+ block-based Widget page editor
	$options[] = array(
		'name'		=> __( 'Disable Block-based Widget Editor', 'bioship' ),
		'desc'		=> __( 'Reverts to classic Widget editor on the Widgets admin page.', 'bioship' ),
		'id'		=> 'disablewidgetblockeditor',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// All Options Page
	// ----------------
	// TODO: may need to remove this option?
	$options[] = array(
		'name'		=> __( 'Add All Options Page', 'bioship' ),
		'desc'		=> __( 'Add an "All Options" Page to the Settings Menu.', 'bioship' ),
		'id'		=> 'alloptionspage',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Admin Only Update Notice
	// ------------------------
	$options[] = array(
		'name'		=> __( 'Admin Only Core Update Notice', 'bioship' ),
		'desc'		=> __( 'Only Show Update Core Notice to Admins.', 'bioship' ),
		'id'		=> 'removeupdatenotice',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Disable New User Notifications
	// ------------------------------
	$options[] = array(
		'name'		=> __( 'Disable Admin New User Notifications', 'bioship' ),
		'desc'		=> __( 'Catch and cancel email notifications to site admin regarding new users and lost/reset passwords.', 'bioship' ),
		'id'		=> 'disablenotifications',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Cleaner Adminbar
	// ----------------
	$options[] = array(
		'name'		=> __( 'Cleaner Admin Bar', 'bioship' ),
		'desc'		=> __( 'Remove the WordPress Links from the top Admin Bar.', 'bioship' ),
		'id'		=> 'cleaneradminbar',
		'std'		=> '1',
		'class'		=> 'muscle',
		// 'transport'	=> 'refresh', // no admin bar on customizer pages
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Template Dropdown
	// -----------------
	$options[] = array(
		'name'		=> __( 'Templates Dropdown', 'bioship' ),
		'desc'		=> __( 'Add an ordered list of included templates for the current page to the Admin Bar.', 'bioship' ),
		'id'		=> 'templatesdropdown',
		'std'		=> '1',
		'class'		=> 'muscle',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Admin Bar Link
	// --------------
	// 1.8.5: changed from option to filter
	// $options[] = array(
	//	'name'		=> __( 'Admin Bar Link', 'bioship' ),
	//	'desc'		=> __( 'Add a link to Theme Options in the Admin Bar.', 'bioship' ),
	//	'id'		=> 'adminbarlink',
	//	'std'		=> '1',
	//	'class'		=> 'muscle',
	//	// 'transport'	=> 'refresh', // no admin bar on customizer pages
	//	'type'		=> 'checkbox'
	// );

	// Admin Thumbnail Column
	// ----------------------
	// 2.1.4: allow for multiple CPT selection
	$options[] = array(
		'name'		=> __( 'Show Thumbnail Column in Admin Post Lists', 'bioship' ),
		'desc'		=> __( 'Adds thumbnail display column to selected custom post types list screens.', 'bioship' ),
	 	'id'		=> 'adminthumbnailcols',
		'class'		=> 'muscle',
		'std'		=> array( 'post' => '1' ),
		'options'	=> $cpt_options,
		'type'		=> 'multicheck',
		'page'		=> 'advanced',
	);

	// CPTs in Dashboard
	// -----------------
	$options[] = array(
		'name'		=> __( 'Show CPTs in "Right Now"', 'bioship' ),
		'desc'		=> __( 'Adds the display of Custom Post Types to the "Right Now" Dashboard widget.', 'bioship' ),
		'id'		=> 'cptsrightnow',
		'class'		=> 'muscle',
		'std'		=> '1',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Theme Tracer
	// ------------
	// 1.8.0: removed as auto-loaded separately when performing traces
	// $options[] = array(
	//	'name'		=> __( 'Theme Performance Tracer', 'bioship' ),
	//	'desc'		=> __( 'Loads Theme Performance Tracer functions (for developing / bugfixing)',  'bioship' ),
	//	'id'		=> 'themetracer',
	//	'class'		=> 'muscle',
	//	'std'		=> '0',
	//	'type'		=> 'checkbox',
	// );

	// Dynamic Admin CSS
	// -----------------
	// 2.2.0: remove !important from submenu a color
	$admincss_default = "/* General Admin Screen Tweaks */" .PHP_EOL;
	$admincss_default .= "#wp-admin-bar-wp-logo {display: none;}" . PHP_EOL;
	$admincss_default .= "#footer-thankyou {display:none;}" . PHP_EOL;
	$admincss_default .= "#footer-upgrade {display:none;}".PHP_EOL;
	$admincss_default .= "#wp-auth-check-wrap #wp-auth-check {left:30% !important; width:900px;}" . PHP_EOL;
	$admincss_default .= "#adminmenu {background: transparent;}".PHP_EOL.
	$admincss_default .= "#adminmenu .wp-submenu a {color: #888;}" . PHP_EOL;
	$admincss_default .= "/* Widget Saver Display Fix */".PHP_EOL.".widget-liquid-left {float:none !important;}" . PHP_EOL;
	$admincss_default .= "div#widgets-left {float: left !important;}".PHP_EOL.".widget-liquid-left .widgets-holder-wrap {background-color: #F3F9FF;}" . PHP_EOL;
	$admincss_default .= PHP_EOL;
	$options[] = array(
		'name'		=> __('Dynamic Admin CSS', 'bioship'),
		'desc'		=> __('Loads this custom CSS in the Admin Area. Defaults hide WP admin bar logo, and thankyou/version footer.', 'bioship'),
		'id'		=> 'dynamicadmincss',
		'std'		=> $admincss_default,
		'class'		=> 'muscle',
		'type'		=> 'textarea',
		'page'		=> 'advanced'
	);

	// Admin CSS Mode
	// --------------
	$options[] = array(
		'name'		=> __( 'Admin CSS Mode', 'bioship' ),
		'desc'		=> __( 'How to load the Dynamic Admin CSS.', 'bioship' ),
		'id'		=> 'admincssmode',
		'std'		=> 'direct',
		'type'		=> 'select',
		'class'		=> 'muscle',
		'transport'	=> 'refresh',
		'options'	=> $css_array,
		'page'		=> 'advanced',
	);


	// ================
	// === SKELETON ===
	// ================

	// --------------
	// === Layout ===
	// --------------

	// RETEST: Customizer Live Preview for Grid
	// (as values need to be passed to grid.php)

	$options[] = array(
		'name'		=> __( 'Layout', 'bioship' ),
		'id'		=> 'skeleton',
		'type'		=> 'heading',
		'page'		=> 'both',
	);

	// Maximum Layout Width
	// --------------------
	// 1.8.5: sanitize number callback
	$options[] = array(
		'name'			=> __( 'Max Layout Width','bioship' ),
		'desc'			=> __( 'Select preferred container maximum container layout width in pixels. (Media queries will not scale container above this point.) Originally 960, 1140 or 1200 selection but can be anything now.','bioship' ),
		'id'			=> 'layout',
		'std'			=> '960',
		'type'			=> 'text',
		'class'			=> 'mini skeleton',
		'transport'		=> 'refresh', // not postMessage
		'sanitize_callback'	=> 'fallback_sanitize_number',
		// 'options'		=> array( '960' => '960px', '1140' => '1140px', '1200' => '1200px' ),
		'page'			=> 'basic',
	);

	// Grid Columns
	// ------------
	$gridcols_array = array(
		'twelve'	=> __( 'Twelve Column Grid', 'bioship' ),
		'sixteen'	=> __( 'Sixteen Column Grid', 'bioship' ),
		'twenty'	=> __( 'Twenty Column Grid', 'bioship' ),
		'twentyfour'	=> __( 'Twenty Four Column Grid', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Layout Grid Columns', 'bioship' ),
		'desc'		=> __( 'Number of Grid Columns used to generate dynamic Layout Grid.', 'bioship' ) . ' (' . __( 'Warning: If you change this, remember to adjust content and sidebar column values to match.', 'bioship' ) . ')',
		'id'		=> 'gridcolumns',
		'std'		=> 'sixteen',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh', // not postMessage
		'options'	=> $gridcols_array,
		'page'		=> 'basic',
	);

	// Responsive Grid Breakpoints
	// ---------------------------
	$options[] = array(
		'name'		=> __( 'Responsive Grid Breakpoints', 'bioship' ),
		'desc'		=> __( 'Dynamic Layout Grid Breakpoints. Media queries are built from these which auto-size the container width based on window width.', 'bioship' ) . ' ' . __( 'Default', 'bioship' ) . ': 320,480,640,768,960,1140,1200',
		'id'		=> 'breakpoints',
		'std'		=> '320,480,640,768,960,1140,1200',
		'type'		=> 'text',
		'transport'	=> 'postMessage', // TODO: no live preview here yet, but no point refreshing (grid.php)
		'class'		=> 'skeleton',
		'page'		=> 'advanced',
	);

	// Content Grid Columns
	// --------------------
	$options[] = array(
		'name'		=> __( 'Content Grid Columns', 'bioship' ),
		'desc'		=> __( 'Default Number of Grid Columns used for dynamic Content Grid.', 'bioship' ) . ' (' . __( 'Warning: If you change this, existing content markup may need to be changed to match.', 'bioship' ) . ')',
		'id'		=> 'contentgridcolumns',
		'std'		=> 'twentyfour',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh', // not postMessage
		'options'	=> $gridcols_array,
		'page'		=> 'advanced',
	);

	// Backwards Compatibility Grid Styles
	// -----------------------------------
	$compat_options = array(
		'960gridsystem'		=> __( '960 Grid System', 'bioship' ),
		'blueprint'		=> __( 'Blueprint', 'bioship' ),
		// TODO: 'foundation5'	=> __( 'Foundation 5', 'bioship' ),
		// TODO: 'foundation6'	=> __( 'Foundation 5', 'bioship' ),
		// TODO: 'bootstrap'	=> __( 'Bootstrap 3 (Twitter)', 'bioship' ),
	);
	$compat_defaults = array();
	$options[] = array(
		'name'		=> __( 'Content Grid Compatibility', 'bioship' ),
		'desc'		=> __( 'Adds Classes for these Grid Boilerplates to your content columns if you prefer to use their syntax.', 'bioship' ),
		'id'		=> 'gridcompatibility',
		'std'		=> $compat_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'skeleton',
		'transport'	=> 'postMessage', // TODO: no live preview here yet, but no point refreshing (grid.php)
		'options'	=> $compat_options,
		'page'		=> 'advanced'
	);

	// Content Columns
	// ---------------
	// TODO: use number format internationization ?
	$contentcols_array = array(
		'one'		=> ' ' . __( '1 Column', 'bioship' ),
		'two'		=> ' ' . __( '2 Columns', 'bioship' ),
		'three' 	=> ' ' . __( '3 Columns', 'bioship' ),
		'four'		=> ' ' . __( '4 Columns', 'bioship' ),
		'five'		=> ' ' . __( '5 Columns', 'bioship' ),
		'six'		=> ' ' . __( '6 Columns', 'bioship' ),
		'seven'		=> ' ' . __( '7 Columns', 'bioship' ),
		'eight'		=> ' ' . __( '8 Columns', 'bioship' ),
		'nine'		=> ' ' . __( '9 Columns', 'bioship' ),
		'ten'		=> __( '10 Columns', 'bioship' ),
		'eleven'	=> __( '11 Columns', 'bioship' ),
		'twelve'	=> __( '12 Columns', 'bioship' ),
		'thirteen'	=> __( '13 Columns', 'bioship' ),
		'fourteen'	=> __( '14 Columns', 'bioship' ),
		'fifteen'	=> __( '15 Columns', 'bioship' ),
		'sixteen'	=> __( '16 Columns', 'bioship' ),
		'seventeen'	=> __( '17 Columns', 'bioship' ),
		'eighteen'	=> __( '18 Columns', 'bioship' ),
		'nineteen'	=> __( '19 Columns', 'bioship' ),
		'twenty'	=> __( '20 Columns', 'bioship' ),
		'twentyone'	=> __( '21 Columns', 'bioship' ),
		'twentytwo'	=> __( '22 Columns', 'bioship' ),
		'twentythree'	=> __( '23 Columns', 'bioship' ),
		'twentyfour'	=> __( '24 Columns', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Content Width', 'bioship' ),
		'desc'		=> __( 'Define the width of your content area in columns out of total layout grid columns.', 'bioship' ),
		'id'		=> 'content_width',
		'std'		=> 'twelve',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh', // not postMessage
		'options'	=> $contentcols_array,
		'page'		=> 'basic',
	);

	// Content Padding
	// ---------------
	$options[] = array(
		'name'		=> __( 'Content Padding', 'bioship' ),
		'desc'		=> __( 'Sets the CSS padding property for the #contentpadding wrapper around #content. Use px or em or %.', 'bioship' ) . '<br>' . __( 'Also used to help calculate actual content width area for embeds and images.', 'bioship' ),
		'id'		=> 'contentpadding',
		'std'		=> '1em 0.75em',
		'class'		=> 'mini skeleton',
		'transport'	=> 'postMessage', // TODO: not full live preview here yet (grid.php)
		'csselement'	=> '#content #contentpadding',
		'cssproperty'	=> 'padding',
		'type'		=> 'text',
		'page'		=> 'basic',
	);


	// ----------------
	// === Sidebars ===
	// ----------------

	$options[] = array(
		'name'	=> 	__( 'Sidebars', 'bioship' ),
		'id'	=> 	'skeleton',
		'type'	=> 	'heading',
		'page'	=> 	'both',
	);

	// Sidebar Info
	// ------------
	// 1.8.5: removed old warning (no longer needed, sidebars are now registered anyway)
	// [Old] Warning: By changing sidebar modes or disabling sidebars their associated widgets will be found in an "Inactive Sidebar" on the Widgets page. Best to take note of your existing widgets before changing these!
	// (Sidebars are registered so you can add widgets to them and display them in other ways.)
	$options[] = array(
		'name'		=> 	'',
		'desc'		=> 	__( 'All sidebars are registered regardless of their active state so the you can add widgets while inactive. Note: You can save/restore widget layouts with', 'bioship' ) . ' <a href="https://wordpress.org/plugins/widget-saver/" target=_blank>Widget Saver</a>, ' . __( 'or import/export widgets with ', 'bioship' ) . '<a href="https://wordpress.org/plugins/widget-settings-importexport/" target=_blank>Widget Import/Export</a>',
		'class'		=> 	'skeleton',
		'type'		=> 	'info',
		'page'		=> 	'advanced',
	);

	// Sidebar Mode
	// ------------
	// 1.5.0: added posts only and page only option like subsidebar
	// 1.8.0: moved 'off' option to here for consistency
	$sidebar_array = array(
		'off'		=> __( 'Do not add a Primary Sidebar', 'bioship' ),
		'postsonly'	=> __( 'Add to Posts only', 'bioship' ),
		'pagesonly'	=> __( 'Add to Pages only', 'bioship' ),
		'unified'	=> __( 'Unified Sidebar for Posts and Pages', 'bioship' ),
		'dual'		=> __( 'Separate Post and Page Sidebars', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Sidebar Mode', 'bioship' ),
		'desc'		=> __( 'Whether to use a single sidebar area for both posts and pages, or a different sidebar for posts and pages.', 'bioship' ),
		'id'		=> 'sidebarmode',
		'std'		=> 'dual',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $sidebar_array,
		'page'		=> 'basic',
	);

	// Sidebar Position
	// ----------------
	// 2.2.0: shorten if/then to single line check
	$sidebar_default = ( is_rtl() ) ? 'right' : 'left';
	$sidebar_options = array(
		'left'		=> __( 'Left Sidebar', 'bioship' ),
		'right'		=> __( 'Right Sidebar', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Sidebar Position', 'bioship' ),
		'desc'		=> __( 'Default primary sidebar layout position.', 'bioship' ),
		'id'		=> 'page_layout',
		'std'		=> $sidebar_default,
		'type'		=> 'select',
		'class'		=> 'mini skeleton',
		'transport'	=> 'refresh',
		'options'	=> $sidebar_options,
		'page'		=> 'basic'
	);
	// 'options' => array( 'left' => $imagepath . '2cl.png', 'right' => $imagepath . '2cr.png' )

	// Sidebar Columns
	// ---------------
	// TODO: use number format internationalization ?
	$sidebarcols_array = array(
		'one'		=> ' ' . __( '1 Column', 'bioship' ),
		'two'		=> ' ' . __( '2 Columns', 'bioship' ),
		'three'		=> ' ' . __( '3 Columns', 'bioship' ),
		'four'		=> ' ' . __( '4 Columns', 'bioship' ),
		'five'		=> ' ' . __( '5 Columns', 'bioship' ),
		'six'		=> ' ' . __( '6 Columns', 'bioship' ),
		'seven'		=> ' ' . __( '7 Columns', 'bioship' ),
		'eight'		=> ' ' . __( '8 Columns', 'bioship' ),
		'nine'		=> ' ' . __( '9 Columns', 'bioship' ),
		'ten'		=> __( '10 Columns', 'bioship' ),
		'eleven'	=> __( '11 Columns', 'bioship' ),
		'twelve'	=> __( '12 Columns', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Sidebar Width','bioship' ),
		'desc'		=> __( 'Define the width of primary Sidebar in columns out of total layout grid columns.)', 'bioship' ),
		'id'		=> 'sidebar_width',
		'std'		=> 'four',
		'type'		=> 'select',
		'class'		=> 'mini skeleton',
		'transport'	=> 'refresh',
		'options'	=> $sidebarcols_array,
		'page'		=> 'basic',
	);

	// Subsidiary Sidebar
	// ------------------
	$suboptions_array = array(
		'off'		=> __( 'Do not add Subsidiary Sidebar', 'bioship' ),
		'postsonly'	=> __( 'Add to Posts only', 'bioship' ),
		'pagesonly'	=> __( 'Add to Pages only', 'bioship' ),
		'unified'	=> __( 'Add a unified subsidebar to Posts and Pages', 'bioship' ),
		'dual'		=> __( 'Add a separate subsidebar for Posts and Pages', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Subsidiary Sidebar', 'bioship' ),
		'desc'		=> __( 'Adds a Subsidiary Sidebar Widget Area. Either for posts or pages, or for both posts/pages together, or for posts/pages separately.', 'bioship' ),
		'id'		=> 'subsidiarysidebar',
		'std'		=> 'off',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $suboptions_array,
		'page'		=> 'basic',
	);

	// Subsidiary Sidebar Position
	// ---------------------------
	$subposition_array = array(
		'external'	=> __( 'External to Primary Sidebar', 'bioship' ),
		'internal'	=> __( 'Internal to Primary Sidebar', 'bioship' ),
		'opposite'	=> __( 'Opposite to Primary Sidebar', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Subsidiary Sidebar Position', 'bioship' ),
		'desc'		=> __( 'Whether to call the Subsidiary Sidebar external to (towards wrapper), or internal to (towards content), or opposite to the Primary Sidebar.', 'bioship' ),
		'id'		=> 'subsidiaryposition',
		'std'		=> 'opposite',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $subposition_array,
		'page'		=> 'basic',
	);

	// Subsidiary Sidebar Columns
	// --------------------------
	// TODO: use number format internationalization ?
	$subcolumns_array = array(
		'one'		=> __( '1 Column', 'bioship' ),
		'two'		=> __( '2 Columns', 'bioship' ),
		'three'		=> __( '3 Columns', 'bioship' ),
		'four'		=> __( '4 Columns', 'bioship' ),
		'five'		=> __( '5 Columns', 'bioship' ),
		'six'		=> __( '6 Columns', 'bioship' ),
		'seven'		=> __( '7 Columns', 'bioship' ),
		'eight'		=> __( '8 Columns', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Subsidiary Sidebar Columns', 'bioship' ),
		'desc'		=> __( 'Number of columns for subsidiary sidebar out of total layout grid columns.', 'bioship' ),
		'id'		=> 'subsidiarycolumns',
		'std'		=> 'one',
		'type'		=> 'select',
		'class'		=> 'mini skeleton',
		'transport'	=> 'refresh',
		'options'	=> $subcolumns_array,
		'page'		=> 'basic',
	);

	// Header Widget Area
	// ------------------
	$options[] = array(
		'name'		=> __( 'Header Widget Area', 'bioship' ),
		'desc'		=> __( 'Enable the Header Sidebar Widget Area', 'bioship' ),
		'id'		=> 'headersidebar',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Footer Widget Areas
	// -------------------
	// TODO: use number format internationalization ?
	$footers_array = array(
		'0'		 => __( 'No Footer Widget Areas', 'bioship' ),
		'1'		 => __( '1 Widget Area', 'bioship' ),
		'2'		 => __( '2 Widget Areas', 'bioship' ),
		'3'		 => __( '3 Widget Areas', 'bioship' ),
		'4'		 => __( '4 Widget Areas', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Footer Widget Areas', 'bioship' ),
		'desc'		=> __( 'Number of Footer Sidebar Widget Areas to make available. Actual display is split into columns based on widget areas with widgets.', 'bioship' ),
		'id'		=> 'footersidebars',
		'std'		=> '1',
		'type'		=> 'select',
		'class'		=> 'mini skeleton',
		'transport'	=> 'refresh',
		'options'	=> $footers_array,
		'page'		=> 'basic',
	);


	// Context Sidebars
	// ----------------
	// 1.8.5: added this option
	$sidebar_options = array(
		'frontpage'	=> __( 'Frontpage Sidebar', 'bioship' ),
		'homepage'	=> __( 'Home (Blog) Sidebar', 'bioship' ),
		'search'	=> __( 'Searchpage Sidebar', 'bioship' ),
		'notfound'	=> __( '404 Not Found Sidebar', 'bioship' ),
		'archive'	=> __( 'Archive Page Sidebar', 'bioship' ),
		'category'	=> __( 'Category Archive Sidebar', 'bioship' ),
		'taxonomy'	=> __( 'Taxonomy Archive Sidebar', 'bioship' ),
		'tag'		=> __( 'Tag Archive Sidebar', 'bioship' ),
		'author'	=> __( 'Author Archive Sidebar', 'bioship' ),
		'date'		=> __( 'Date Archive Sidebar', 'bioship' )
	);
	$sidebar_defaults = array( 'frontpage' => '1' );
	$options[] = array(
		'name'		=> __( 'Display Contextual Sidebars', 'bioship' ),
		'desc'		=> __( 'Which contextual Sidebars to Display by default. They will only be displayed if they have widgets in them.', 'bioship' ),
		'id'		=> 'sidebars',
		'std'		=> $sidebar_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $sidebar_options,
		'page'		=> 'advanced',
	);

	// Context Subsidebars
	// -------------------
	// 1.8.5: added this option
	$subsidebar_options = array(
		'subfrontpage'	=> __( 'Frontpage SubSidebar', 'bioship' ),
		'subhomepage'	=> __( 'Home (Blog) SubSidebar', 'bioship' ),
		'subsearch'	=> __( 'Searchpage SubSidebar', 'bioship' ),
		'subnotfound'	=> __( '404 Not Found SubSidebar', 'bioship' ),
		'subarchive'	=> __( 'Archive Page SubSidebar', 'bioship' ),
		'subcategory'	=> __( 'Category Archive SubSidebar', 'bioship' ),
		'subtaxonomy'	=> __( 'Taxonomy Archive SubSidebar', 'bioship' ),
		'subtag'	=> __( 'Tag Archive SubSidebar', 'bioship' ),
		'subauthor'	=> __( 'Author Archive SubSidebar', 'bioship' ),
		'subdate'	=> __( 'Date Archive SubSidebar', 'bioship' ),
	);
	$subsidebar_defaults = array();
	$options[] = array(
		'name'		=> __( 'Display Contextual SubSidebars', 'bioship' ),
		'desc'		=> __( 'Which contextual SubSidebars to Display by default. They will only be displayed if they have widgets in them.', 'bioship' ),
		'id'		=> 'subsidebars',
		'std'		=> $subsidebar_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $subsidebar_options,
		'page'		=> 'advanced',
	);


	// ------------------
	// === Navigation ===
	// ------------------

	// Navigation Menus
	// ----------------
	$options[] = array(
		'name'		=> __( 'Navigation', 'bioship' ),
		'id'		=> 'skeleton',
		'type'		=> 'heading',
		'page'		=> 'both',
	);

	// Primary Menu
	// ------------
	$options[] = array(
		'name'		=> __( 'Primary Menu', 'bioship' ),
		'desc'		=> __( 'Enable the Primary Navigation Menu', 'bioship' ),
		'id'		=> 'primarymenu',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Secondary Menu
	// --------------
	$options[] = array(
		'name'		=> __( 'Secondary Menu', 'bioship' ),
		'desc'		=> __( 'Enable the Secondary Navigation Menu', 'bioship' ),
		'id'		=> 'secondarymenu',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Header Menu
	// -----------
	$options[] = array(
		'name'		=> __( 'Header Menu', 'bioship' ),
		'desc'		=> __( 'Enable the Header Navigation Menu', 'bioship' ),
		'id'		=> 'headermenu',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Footer Menu
	// -----------
	$options[] = array(
		'name'		=> __( 'Footer Menu', 'bioship' ),
		'desc'		=> __( 'Enable the Footer Navigation Menu', 'bioship' ),
		'id'		=> 'footermenu',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Page Navi
	// ---------
	// 1.8.5: moved here from meta tab
	$pagenav_defaults = array( 'post' => '1' );
	$options[] = array(
		'name'		=> __( 'Page Navigation Display', 'bioship' ),
		'desc'		=> __( 'Which Post Types to display Post/Page Navigation on.', 'bioship' ),
		'id'		=> 'pagenavposttypes',
		'std'		=> $pagenav_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $cpt_options,
		'page'		=> 'advanced',
	);

	// Archive Page Navi
	// -----------------
	// 1.8.5: moved here from meta tab
	$pagenav_defaults = array( 'post' => '1' );
	$options[] = array(
		'name'		=> __( 'Archive Navigation Display', 'bioship' ),
		'desc'		=> __( 'Which Post Types to display Archive Navigation on.', 'bioship' ),
		'id'		=> 'pagenavarchivetypes',
		'std'		=> $pagenav_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $cpt_options,
		'page'		=> 'advanced',
	);

	// Breadcrumbs Post Types
	// ----------------------
	// 1.8.5: added this option
	$breadcrumb_defaults = array();
	$options[] = array(
		'name'		=> __( 'Breadcrumbs Posts Display', 'bioship' ),
		'desc'		=> __( 'Which Post Types to display Breadcrumbs on.', 'bioship' ),
		'id'		=> 'breadcrumbposttypes',
		'std'		=> $breadcrumb_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $cpt_options,
		'page'		=> 'advanced',
	);

	// Breadcrumb Archive Types
	// ------------------------
	// 1.8.5: added this option
	$options[] = array(
		'name'		=> __( 'Breadcrumbs Archive Display', 'bioship' ),
		'desc'		=> __( 'Which Post Archive Types to display Breadcrumbs on.', 'bioship' ),
		'id'		=> 'breadcrumbarchivetypes',
		'std'		=> $breadcrumb_defaults,
		'class'		=> 'skeleton',
		'type'		=> 'multicheck',
		'transport'	=> 'refresh',
		'options'	=> $cpt_options,
		'page'		=> 'advanced'
	);


	// ------------------
	// === Thumbnails ===
	// ------------------

	$options[] = array(
		'name'		=> __( 'Thumbnails', 'bioship' ),
		'id'		=> 'skeleton',
		'type'		=> 'heading',
		'page'		=> 'both',
	);

	// Thumbsize Change Note
	// ---------------------
	$options[] = array(
		'name'		=> __( 'Thumbnail Size Changes Reminder', 'bioship' ),
		'desc'		=> __( 'If this is a new theme install, or you have changed the explicit thumbnail sizes via filters, you can use','bioship').' <a href="http://wordpress.org/plugins/regenerate-thumbnails/" target=_blank>Regenerate Thumbnails</a> '.__('to re-process your existing thumbnails.', 'bioship' ),
		'class'		=> 'skeleton',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// JPEG Quality
	// ------------
	// 2.0.9: added missing min and max number values
	// 2.1.0: changed back to text as Titan 1.12 number control is broken
	// ? TEST: does this input type work in options framework ?
	$options[] = array(
		'name'		=> __( 'JPEG Quality', 'bioship' ),
		'desc'		=> __( 'Set JPEG Quality for Media Library.', 'bioship' ) . ' (' . __( '0 or blank for off.', 'bioship' ) . ')',
		'id'		=> 'jpegquality',
		'std'		=> '90',
		'type'		=> 'text',
		'class'		=> 'mini skeleton',
		'min'		=> 50,
		'max'		=> 100,
		'page'		=> 'advanced',
	);

	// Cropping Options
	// ----------------
	$cropoptions_array = array(
		'nocrop'	=> __( 'No Crop', 'bioship' ),
		'auto'		=> __( 'Auto Crop', 'bioship' ),
		'top-left'	=> __( 'Top Left', 'bioship' ),
		'top-center'	=> __( 'Top Center', 'bioship' ),
		'top-right'	=> __( 'Top Right', 'bioship' ),
		'center-left'	=> __( 'Center Left', 'bioship' ),
		'center-center'	=> __( 'Center Center', 'bioship' ),
		'center-right'	=> __( 'Center Right', 'bioship' ),
		'bottom-left'	=> __( 'Bottom Left', 'bioship' ),
		'bottom-center'	=> __( 'Bottom Center', 'bioship' ),
		'bottom-right'	=> __( 'Bottom Right', 'bioship' )
	);
	$options[] = array(
		'name'		=> __( 'Thumbnail Cropping','bioship' ),
		'desc'		=> __( 'Set Default Cropping for Thumbnails.', 'bioship' ) . ' ' . __( 'For specific image size cropping use filters.', 'bioship' ),
		'id'		=> 'thumbnailcrop',
		'std'		=> 'auto',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'options'	=> $cropoptions_array,
		'page'		=> 'advanced'
	);

	// Thumbnail Display Sizes
	// -----------------------
	$thumb_array = array(
		'off'		=> __( 'Do Not Display', 'bioship' ),
		'thumbnail'	=> __( 'Thumbnail', 'bioship' ). ' (' . get_option( 'thumbnail_size_w' ).' x ' . get_option( 'thumbnail_size_h' ) . ')',
		'medium'	=> __( 'Medium', 'bioship' ) . ' (' . get_option( 'medium_size_w' ) . ' x ' . get_option( 'medium_size_h' ) . ')',
		'large'		=> __( 'Large', 'bioship' ) . ' (' . get_option( 'large_size_w' ) . ' x ' . get_option( 'large_size_h' ) . ')',
		'full'		=> __( 'Full Size (Original)', 'bioship' ),
	);
	// if ( $internal ) {
		// 2.2.0: removed as not working as desired
		/* $image_sizes = get_intermediate_image_sizes();
		global $_wp_additional_image_sizes;
		$image_sizes = $_wp_additional_image_sizes;
		echo "<!-- Additional Image Sizes: " . print_r( $image_sizes, true ) . " -->";
		$default = array( 'thumbnail', 'medium', 'large', 'full' );
		foreach ( $image_sizes as $size_name ) {
			if ( ( '' != $size_name ) && !in_array( $size_name, $default_sizes ) ) {
				// 1.8.0: bypass undefined index warning
				if ( isset($image_sizes[$size_name]['width'] ) && isset( $image_sizes[$size_name]['height'] ) ) {
					$thumb_array[$size_name] = $size_name . ' (' . $image_sizes[$size_name]['width'] . ' x ' . $image_sizes[$size_name]['height'] . ')';
				} else {
					// TOODO: check why there was a warning happening here ?
					// echo "<!-- Warning! Check Image Size " . print_r( $image_sizes[$size_name], true) . " -->";
				}
			}
		} */

		// 2.2.0: get and merge theme image size options
		$image_sizes = bioship_get_image_sizes();
		if ( $image_sizes && is_array( $image_sizes ) && ( count( $image_sizes ) > 0 ) ) {
			foreach ( $image_sizes as $image_size ) {
				if ( !array_key_exists( $image_size['name'], $thumb_array ) ) {
					$thumb_array[$image_size['name']] = $image_size['title'] . ' (' . $image_size['width'] . ' x ' . $image_size['height'] . ')';
				}
			}
		}
		// debug point
		// echo "<!-- Thumbnail Sizes: " . print_r( $thumb_array, true ) . " -->";
	// }

	// Post List Thumbnail Display Size
	// --------------------------------
	$options[] = array(
		'name'		=> __( 'Post List Thumbnail Image Display Size','bioship' ),
		'desc'		=> __( 'Set Post Thumbnail size for Post Lists (Blog/Category/Archive/Search pages etc.)', 'bioship' ),
		'id'		=> 'listthumbsize',
		'std'		=> 'thumbnail',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $thumb_array,
		'page'		=> 'basic');

	// Post List Thumbnail Alignment Class
	// -----------------------------------
	// 1.5.0: added this option
	$thumbalign_array = array(
		'none'			=> __( 'No Alignment Class', 'bioship' ),
		'alignleft'		=> __( 'Align Left','bioship' ) . ' (<i>.alignleft</i>)',
		'alignright'		=> __( 'Align Right','bioship' ) . ' (<i>.alignright<i>)',
		'aligncenter'		=> __( 'Align Center','bioship' ) . ' (<i>.aligncenter</i>)',
		'alternateleftright'	=> __( 'Alternate Left-Right', 'bioship' ),
		'alternaterightleft'	=> __( 'Alternate Right-Left', 'bioship' ),
	);
	$options[] = array(
		'name'		=> __( 'Post List Thumbnail Alignment','bioship' ),
		'desc'		=> __( 'Post Thumbnail alignment class for Post Lists (Blog/Category/Archive/Search pages etc.)', 'bioship' ),
		'id'		=> 'thumblistalign',
		'std'		=> 'alignleft',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh', // use active_callback for lists only?
		'options'	=> $thumbalign_array,
		'page'		=> 'advanced',
	);

	// Post Thumbnail Display Size
	// ---------------------------
	$options[] = array(
		'name'		=> __( 'Single Post Thumbnail Image Display Size', 'bioship' ),
		'desc'		=> __( 'Set Post Thumbnail size for Single Post display. PerPost size via edit post metabox. Suggested minimum 200 square (for social sharing.)', 'bioship' ),
		'id'		=> 'postthumbsize',
		'std'		=> 'off',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $thumb_array,
		'page'		=> 'basic',
	);

	// Post Thumbnail Alignment Class
	// ------------------------------
	// 1.5.0: added this option
	$thumbalign_array = array(
		'none'		=> __( 'No Alignment Class', 'bioship' ),
		'alignleft'	=> __( 'Align Left', 'bioship' ) . ' (<i>.alignleft</i>)',
		'alignright'	=> __( 'Align Right', 'bioship' ) . ' (<i>.alignright</i>)',
		'aligncenter'	=> __( 'Align Center', 'bioship' ) . ' (<i>.aligncenter</i>)'
	);
	$options[] = array(
		'name'		=> __( 'Single Post Thumbnail Alignment', 'bioship' ),
		'desc'		=> __( 'Post Thumbnail alignment class for Single Post display.', 'bioship' ),
		'id'		=> 'thumbnailalign',
		'std'		=> 'alignleft',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $thumbalign_array,
		'page'		=> 'advanced'
	);

	// Page Featured Image Display Size
	// --------------------------------
	// 1.5.0: added this option
	$options[] = array(
		'name'		=> __( 'Single Page Featured Image Display Size', 'bioship' ),
		'desc'		=> __( 'Set Feature Image (page thumbnail) size for Single Page display. PerPage size via edit page metabox. Suggested minimum 200 square (for social sharing.)', 'bioship' ),
		'id'		=> 'pagethumbsize',
		'std'		=> 'off',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $thumb_array,
		'page'		=> 'basic'
	);

	// Feature Image Alignment Class
	// -----------------------------
	// 1.5.0: added this option
	$options[] = array(
		'name'		=> __( 'Single Page Featured Image Alignment', 'bioship' ),
		'desc'		=> __( 'Featured Image alignment class for Single Page display.', 'bioship' ),
		'id'		=> 'featuredalign',
		'std'		=> 'alignleft',
		'type'		=> 'select',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'options'	=> $thumbalign_array,
		'page'		=> 'advanced',
	);

	// Thumbnail CPTs Support
	// ----------------------
	// 1.5.0: added this option
	$thumbcpt_defaults = array( 'page' => '1' );
	$options[] = array(
		'name'		=> __( 'Add Thumbnails Support for Custom Post Types', 'bioship' ),
		'desc'		=> __( 'Adds Thumbnail support for selected Custom Post Types. Will not remove existing support.', 'bioship' ),
		'id'		=> 'thumbnailcpts',
		'std'		=> $thumbcpt_defaults,
		'type'		=> 'multicheck',
		'class'		=> 'skeleton',
		'options'	=> $thumbcpt_options,
		'page'		=> 'advanced',
	);

	// -------------
	// === Icons ===
	// -------------
	// 1.8.5: moved from skin section to skeleton

	$options[] = array(
		'name'		=> __( 'Icons', 'bioship' ),
		'id'		=> 'skeleton',
		'type'		=> 'heading',
		'page'		=> 'both'
	);

	// Default Gravatar
	// ----------------
	// 2.2.0: switch gravatar position to be above site icon note
	$options[] = array(
		'name'		=> __( 'Default Gravatar URL', 'bioship' ),
		'desc'		=> __( 'Default', 'bioship' ) . ' 96x96 <i>gravatar.png</i>. (' . __( 'Alternatively place in your parent or child theme', 'bioship' ) . ' <i>/images/</i>)',
		'id'		=> 'gravatarurl',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'basic'
	);

	// Site Icon Note
	// --------------
	// 2.0.8: added this note for site icon support
	$options[] = array(
		'name'		=> __( 'Site Icon Note', 'bioship' ),
		'desc'		=> __( 'If a Site Icon is set via Customizer, it will be used instead of these icons. Specifically named files will override both for that size. See documentation for more details.', 'bioship' ),
		'class'		=> 'skeleton',
		'type'		=> 'info',
		'page'		=> 'basic',
	);

	// Favicon.ico
	// -----------
	// 1.8.5: set default to empty, fallback to root handled in function
	$options[] = array(
		'name'		=> __( 'Favicon.ico URL', 'bioship' ),
		'desc'		=> __( 'Default','bioship' ) . ' 32x32 or 16x16 <i>favicon.ico</i>. (' . __(' Alternatively place in parent or child theme', 'bioship' ) . ' <i>/images/</i>)',
		'id'		=> 'faviconico',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Favicon.png
	// -----------
	$options[] = array(
		'name'		=> __( 'Favicon.png URL', 'bioship' ),
		'desc'		=> __( 'Default', 'bioship' ) . ' 96x96 <i>favicon.png</i> (' . __( 'Alternatively place in parent or child theme', 'bioship' ) . ' <i>/images/</i>)',
		'id'		=> 'faviconpng',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Apple Touch Default
	// -------------------
	$options[] = array(
		'name'		=> __( 'Apple Touch Icon URL', 'bioship' ),
		'desc'		=> __( 'Default', 'bioship' ) . ' 57x57 <i>apple-touch-icon.png</i> (' . __( 'Alternatively place in parent or child theme', 'bioship' ) . ' <i>/images/</i>)',
		'id'		=> 'appletouchicon',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Win8 Tile/Apple Touch 144
	// -------------------------
	$options[] = array(
		'name'		=> __( 'Windows 8 Tile/Apple Touch 144', 'bioship' ),
		'desc'		=> __( 'Default', 'bioship' ) . ' 144x144 <i>win8-tile.png</i> ' . __( 'for Windows 8/Apple Touch','bioship' ) . '. (' . __( 'Alternatively place in child or parent theme', 'bioship' ) . ' <i>/images/</i>)',
		'id'		=> 'wineighttile',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'basic',
	);

	// Win8 Tile Background Colour
	// ---------------------------
	$options[] = array(
		'name'		=> __( 'Windows 8 Tile Background', 'bioship' ),
		'desc'		=> __( 'Background Color for Windows 8 Tile Icon.', 'bioship' ),
		'id'		=> 'wineightbg',
		'std'		=> '#FFFFFF',
		'class'		=> 'skeleton',
		'type'		=> 'color',
		'page'		=> 'basic'
	);

	// Apple Touch Icon Sizes
	// ----------------------
	// sizes: 57, 72, 76, 114, 120, 144, 152, 180, 192
	$options[] = array(
		'name'		=> __( 'Enable Apple Touch Icon Sizes', 'bioship' ),
		'desc'		=> __( 'Adds header code for all the specific Apple Touch Icon sizes (if found.)', 'bioship' ) . '<br>'
			. __( 'Place files in child or parent theme', 'bioship' ) . ' <i>/images/</i> <br>'
			. __( 'Filenames', 'bioship' ) . ': <i>apple-touch-icon-XXXxYYY-precomposed.png</i> - '
			. __( 'Sizes', 'bioship' ) . ' (' .__( 'square', 'bioship' ) . '): 57,72,76,114,120,144,152,180,192',
		'id'		=> 'appleiconsizes',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Apple Startup Image Sizes
	// -------------------------
	// sizes: 320x460,640x920,640x1096,1024x748,768x1004,1536x2008,2048x1496
	$options[] = array(
		'name'		=> __( 'Enable Apple Touch Startup Images', 'bioship' ),
		'desc'		=> __( 'Adds header code for all the specific Apple Touch Startup Image sizes (if found.)', 'bioship' ) . '<br>'
			. __( 'Place files in child or parent theme', 'bioship' ) .' <i>/images/</i><br>'
			. __( 'Filenames', 'bioship' ) . ': <i>startup-XXXXxYYYY.png</i> - '
			. __( 'Sizes', 'bioship' ) . ': 320x460,640x920,640x1096,1024x748,768x1004,1536x2008,2048x1496',
		'id'		=> 'startupimages',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Open Graph Default Image
	// ------------------------
	// TODO: add login logo and site icon Customizer option? (512x512)
	$ogdefaultimage_array = array(
		'none'			=> __( 'None (off)', 'bioship' ), 						// 2.0.8: added off option
		'header_logo'		=> __( 'Header Logo', 'bioship' ),
		'ogimageurl'		=> __( 'Image URL (Upload Below)', 'bioship' ),
		'loginlogourl'		=> __( 'Login Logo', 'bioship' ),						// 2.0.8: added this option
		'site_icon'		=> __( 'WordPress Site Icon', 'bioship' )				// 2.0.8: added this option
		// 'faviconpng'		=> __( 'Favicon PNG 96x96', 'bioship' ), 			// too small
		// 'wineighttile'	=> __( 'WinTile/AppleTouch 144x144', 'bioship' ),	// too small
		// 'appleiconsizes'	=> __( 'Largest Apple Icon Size', 'bioship' ),		// too small
	);
	$options[] = array(
		'name'			=> __( 'Open Graph Default Image', 'bioship' ),
		'desc'			=> __( 'Recommended miniumum', 'bioship' ) . ' 200x200px ' . __( 'requires', 'bioship' )
			. ' <a href="http://wordpress.org/plugins/open-graph-protocol-framework/" target=_blank>'
			. __( 'Open Graph Protocol', 'bioship') . '</a> ' . __( 'plugin installed and active.', 'bioship' ),
		'id'			=> 'ogdefaultimage',
		'std'			=> 'header_logo',
		'type'			=> 'select',
		'class'			=> 'skeleton',
		'options'		=> $ogdefaultimage_array,
		'page'			=> 'advanced',
	);

	// Open Graph Image URL
	// --------------------
	$options[] = array(
		'name'		=> __( 'Default Open Graph Image URL', 'bioship' ),
		'desc'		=> __( 'Only used if the option above option is URL and the Open Graph Protocol plugin is installed and active.', 'bioship' ),
		'id'		=> 'ogimageurl',
		'std'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'upload',
		'page'		=> 'advanced'
	);


	// -------------------
	// === Hybrid Core ===
	// -------------------

	$options[] = array(
		'name'		=> __( 'Hybrid', 'bioship' ),
		'id'		=> 'skeleton',
		'type'		=> 'heading',
		'page'		=> 'both'
	);

	// Load Hybrid Core
	// ----------------
	// 1.8.0: option to load HC2 or HC3
	// note: value 1 is for HC2 (for backwards option compatibility)
	// 2.0.9: added missing translation wrappers
	$hybrid_options = array(
		'0' => __( 'Do Not Load', 'bioship' ),
		'1' => __( 'Hybrid Core 2', 'bioship' ),
		'3' => __( 'Hybrid Core 3', 'bioship' ),
	);
	// 2.0.9: deprecate Hybrid 2 for WordPress.org version
	if ( THEMEWPORG ) {
		unset( $hybrid_options[1] );
	}
	$options[] = array(
		'name'		=> __( 'Load Hybrid Core', 'bioship' ),
		'desc'		=> __( 'Loads the Hybrid Core Library Version (highly recommended)', 'bioship' ),
		'id'		=> 'hybridloadcore',
		'std'		=> '3',
		'class'		=> 'skeleton',
		'transport'	=> 'refresh',
		'type'		=> 'select',
		'options'	=> $hybrid_options,
		'page'		=> 'basic',
	);

	// Hybrid Hook
	// -----------
	$options[] = array(
		'name'		=> __( 'Hybrid Hook', 'bioship' ),
		'desc'		=> __( 'Modified plugin to add HTML block to all available theme Hooks.', 'bioship' )
			. ' ' . __( 'Note: Does not require Hybrid Core.', 'bioship' )
			. ' (<i>includes/hybrid-hook/hybrid-hook.php</i>)',
		'id'		=> 'hybridhook',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'basic',
	);

	// Hybrid Shortcodes
	// -----------------
	// 1.8.0: [deprecated] removed hybrid shortcodes
	// $options[] = array(
	//	'name'	=> __( 'Hybrid Shortcode Support', 'bioship' ),
	//	'desc'	=> __( 'Activate extra Hybrid Shortcodes (hybrid2/functions/shortcodes.php)', 'bioship' ),
	//	'id'	=> 'hybridshortcodes',
	//	'std'	=> '0',
	//	'class'	=> 'skeleton',
	//	'type'	=> 'checkbox'
	//	);

	// Hybrid Post Format Filters
	// --------------------------
	// TODO: recheck the full effects of using post format filters?
	$options[] = array(
		'name'		=> __( 'Hybrid Post Format Filters', 'bioship' ),
		'desc'		=> __( 'Activate extra Post Formats Filters (note: full effects untested.)', 'bioship' ),
		'id'		=> 'hybridpostformats',
		'std'		=> '0',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// -----------------
	// Hybrid Extensions
	// -----------------

	$options[] = array(
		'name'		=> __( 'Hybrid Extensions', 'bioship' ),
		'desc'		=> '',
		'class'		=> 'skeleton',
		'type'		=> 'info',
		'page'		=> 'advanced'
	);

	// Get the Image
	// -------------
	$options[] = array(
		'name'		=> __(' Get the Image Extension', 'bioship' ),
		'desc'		=> __(' Recommended! Activate Thumbnail/Image Script Extension.', 'bioship' ),
		'id'		=> 'hybridthumbnails',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Hybrid Breadcrumbs
	// ------------------
	$options[] = array(
		'name'		=> __( 'Breadcrumb Trail Extension', 'bioship' ),
		'desc'		=> __( 'Activate Breadcrumb Trail Extension (required for Breadcrumb display)', 'bioship' ),
		'id'		=> 'hybridbreadcrumbs',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Cleaner Gallery
	// ---------------
	$options[] = array(
		'name'		=> __( 'Cleaner Gallery Extension', 'bioship' ),
		'desc'		=> __( 'Activate Cleaner Gallery Extension.', 'bioship' ),
		'id'		=> 'hybridgallery',
		'std'		=> '1',
		'class'		=> 'skeleton',
		'type'		=> 'checkbox',
		'page'		=> 'advanced',
	);

	// Loop Pagination
	// 1.8.0: [deprecated] removed to match Hybrid Core 3
	// $options[] = array(
	//	'name'		=> __( 'Loop Pagination Extension', 'bioship' ),
	//	'desc'		=> __( 'Activate Loop Pagination Extension (Hybrid 2 only)', 'bioship' ),
	//	'id'		=> 'hybridpagination',
	//	'std'		=> '1',
	//	'class'		=> 'skeleton',
	//	'type'		=> 'checkbox'
	// );

	// Cleaner Captions
	// $options[] = array(
	// 	'name'		=> __( 'Cleaner Captions Extension', 'bioship' ),
	//	'desc'		=> __( 'Activate Cleaner Captions Extension (hybrid/extensions/cleaner-caption.php', 'bioship' ),
	//	'id'		=> 'hybridcaptions',
	//	'std'		=> '1',
	//	'class'		=> 'skeleton',
	//	'type'		=> 'checkbox'
	// );

	// Random Background
	// 1.8.0: [deprecated]
	// $options[] = array(
	//	'name'		=> __( 'Random Background Extension', 'bioship' ),
	//	'desc'		=> __( 'Activate Random Background Extension (hybrid/extensions/random-custom-background.php)', 'bioship' ),
	//	'id'		=> 'hybridrandombackground',
	//	'std'		=> '0',
	//	'class'		=> 'skeleton',
	//	'type'		=> 'checkbox'
	// );

	// Post Stylesheets
	// 1.5.0: [deprecated] done via theme display metabox
	// $options[] = array(
	//	'name'		=> __( 'Post Stylesheets Extension', 'bioship' ),
	//	'desc'		=> __( 'Activate Post Stylesheets Extension (hybrid/extensions/post-stylesheets.php)', 'bioship' ),
	//	'id'		=> 'hybridpoststylesheets',
	//	'std'		=> '0',
	//	'class'		=> 'skeleton',
	//	'type'		=> 'checkbox'
	// );

	// PerPost Headers
	// 1.3.0: removed as custom_header is no longer supported
	// $options[] = array(
	//	'name'		=> __('PerPost Featured Headers Extension', 'bioship' ),
	//	'desc'		=> __('Activate PerPost Featured Headers Extension (hybrid/extensions/featured-header.php)', 'bioship' ),
	//	'id'		=> 'hybridfeatuedheaders',
	//	'std'		=> '0',
	//	'class'		=> 'skeleton',
	//	'type'		=> 'checkbox'
	// );


	// ------------------
	// === Foundation ===
	// ------------------
	// 2.0.9: remove Foundation options for WordPress.org version
	// 2.2.0: removed Foundation loading (unused)
	/* if ( !THEMEWPORG ) {

		// Experimental Foundation by Zurb integration...
		// see: http://foundation.zurb.com/docs
		// eg. http://community.sitepoint.com/t/htmlboilerplate-modernizr-yepnope-polyfills-js-awesome-web-design/80590

		$options[] = array(
			'name'		=> __( 'Foundation', 'bioship' ),
			'id'		=> 'skeleton',
			'type'		=> 'heading',
			'page'		=> 'advanced',
		);

		// note: Foundation 5 Dependencies: modernizr and fastclick (autoloaded for foundation 5)
		// 2.0.5: removed experimental note for Foundation loading

		// Foundation Version
		// ------------------
		// 1.8.0: added Foundation version select (directory => label)
		$version_options = array(
			'foundation5' => 'Foundation 5 (5.5.2)',
			'foundation6' => 'Foundation 6 (6.2)'
		);
		$options[] = array(
			'name'		=> __( 'Foundation Version', 'bioship' ),
			'desc'		=> __( 'What major release version of Foundation to use.', 'bioship' ),
			'id'		=> 'foundationversion',
			'std'		=> '5',
			'class'		=> 'skeleton',
			'transport'	=> 'refresh',
			'type'		=> 'select',
			'options'	=> $version_options,
			'page'		=> 'advanced',
		);

		// Foundation Stylesheet
		// ---------------------
		$options[] = array(
			'name'		=> __( 'Foundation Stylesheet', 'bioship' ),
			'desc'		=> __( 'Whether to Enqueue the Foundation Stylesheet (~145kb, or ~55kb if using essentials below.)', 'bioship' ),
			'id'		=> 'foundationcss',
			'std'		=> '0',
			'class'		=> 'skeleton',
			'transport'	=> 'refresh',
			'type'		=> 'checkbox',
			'page'		=> 'advanced',
		);

		// Foundation Javascript
		// ---------------------
		$foundation_options = array(
			'off'		=> __( 'Do Not Load', 'bioship' ),
			'essentials'	=> __( 'Load Essentials', 'bioship' ),
			'selective'	=> __( 'Selective Load', 'bioship' ),
			'full' 		=> __( 'Kitchen Sink (Everything)','bioship' )
		);
		$options[] = array(
			'name'		=> __( 'Load Foundation Javascript', 'bioship' ),
			'desc'		=> __( 'Whether to load Foundation or not, essentials (~20kb), selectively (see below) or the kitchen sink (everything ~180kb)', 'bioship' ),
			'id'		=> 'loadfoundation',
			'std'		=> 'off',
			'class'		=> 'skeleton',
			'type'		=> 'select',
			'transport'	=> 'refresh',
			'options'	=> $foundation_options,
			'page'		=> 'advanced',
		);

		// Foundation Selective Javascript
		// -------------------------------
		// TODO: somehow correlate these with Foundation 6?
		// as they don't seem to match up anymore :-(
		$selective_options = array(
			'abide'		=> __( 'Abide', 'bioship' ),
			'accordion'	=> __( 'Accordion', 'bioship' ),
			'alert'		=> __( 'Alert', 'bioship' ),
			'clearing'	=> __( 'Clearing', 'bioship' ),
			'dropdown'	=> __( 'Dropdown', 'bioship' ),
			'equalizer'	=> __( 'Equalizer', 'bioship' ),
			'interchange'	=> __( 'Interchange', 'bioship' ),
			'joyride'	=> __( 'Joyride', 'bioship' ),
			'magellan'	=> __( 'Magellan', 'bioship' ),
			'offcanvas'	=> __( 'Off Canvas', 'bioship' ),
			'orbit'		=> __( 'Orbit', 'bioship' ),
			'reveal'	=> __( 'Reveal', 'bioship' ),
			'slider'	=> __( 'Slider', 'bioship' ),
			'tab'		=> __( 'Tab', 'bioship' ),
			'tooltip'	=> __( 'Tooltip', 'bioship' ),
			'topbar'	=> __( 'Top Bar', 'bioship' ),
		);
		$selected_defaults = array();
		$options[] = array(
			'name'		=> __( 'Selective Foundation Javascript', 'bioship' ),
			'desc'		=> __( 'Which Foundation Javascripts to load if using selective load option. Combined javascript file is built upon saving Theme Options.', 'bioship' )
				. __( 'See the Foundation ', 'bioship' ) . '<a href="http://foundation.zurb.com/docs/components/kitchen_sink.html" target=_blank>' . __( 'Kitchen Sink', 'bioship' ) . '</a> '
				. __( 'page to decide what you want.','bioship' ),
			'id'		=> 'foundationselect',
			'std'		=> $selected_defaults,
			'type'		=> 'multicheck',
			'class'		=> 'skeleton',
			'options'	=> $selective_options,
			'page'		=> 'advanced',
		);

	} */

	// ---------------------
	// === Hidden Inputs ===
	// ---------------------

	// Hidden inputs to keep track of Skin/Muscle/Skeleton/All Options selection
	// ...and preserve backup/import/export times for managing Theme Options revisions

	// Layer Tab
	// ---------
	$options[] = array(
		'name'		=> __( 'Layer Tab', 'bioship' ),
		'desc'		=> __( 'Reserved for Saving current display Layer tab.', 'bioship' ),
		'id'		=> 'layertab',
		'std'		=> 'skin',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// Options Tab
	// -----------
	$options[] = array(
		'name'		=> __( 'Options Tab', 'bioship' ),
		'desc'		=> __( 'Reserved for Saving current display Options tab.', 'bioship' ),
		'id'		=> 'optionstab',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// Save Time
	// ---------
	// 1.8.5: added save time record
	$options[] = array(
		'name'		=> __( 'Save Time', 'bioship' ),
		'desc'		=> __( 'Reserved for preserving options save timestamp.', 'bioship' ),
		'id'		=> 'savetime',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// Backup Time
	// -----------
	$options[] = array(
		'name'		=> __( 'Backup Time', 'bioship' ),
		'desc'		=> __( 'Reserved for preserving user backup timestamp.', 'bioship' ),
		'id'		=> 'backuptime',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// Restore Time
	// ------------
	// 1.8.0: added missing restore time (for completeness)
	$options[] = array(
		'name'		=> __( 'Restore Time', 'bioship' ),
		'desc'		=> __( 'Reserved for preserving user restore timestamp.', 'bioship' ),
		'id'		=> 'restoretime',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// Import Time
	// -----------
	$options[] = array(
		'name'		=> __( 'Import Time', 'bioship' ),
		'desc'		=> __( 'Reserved for preserving user import timestamp.', 'bioship' ),
		'id'		=> 'importtime',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both'
	);

	// Export Time
	// -----------
	$options[] = array(
		'name'		=> __( 'Export Time', 'bioship' ),
		'desc'		=> __( 'Reserved for preserving user export timestamp.', 'bioship' ),
		'id'		=> 'exporttime',
		'std'		=> '',
		'class'		=> 'hidden',
		'hidden'	=> true,
		'type'		=> 'text',
		'page'		=> 'both',
	);

	// -----------------------
	// -- END THEME OPTIONS --

	// --- filter and return ---
	$options = bioship_apply_filters( 'options_themeoptions', $options );
	return $options;
 }
}
