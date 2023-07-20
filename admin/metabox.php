<?php

// ==============================
// === BioShip Editor Metabox ===
// ==============================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// to override custom values (via muscle_get_display_overrides in muscle.php)
// 2.1.4: moved to separate file from admin.php

// -----------------------------
// === metabox.php Structure ===
// -----------------------------
// === Editor Metabox ===
// - Meta Key Notes
// - Add Editor Metabox
// - Editor Theme Options Metabox
// - Update Metabox Values
// === QuickSaves ===
// - QuickSave PerPost CSS Form
// - QuickSave PerPost CSS
// - QuickSave PerPost Settings Form
// - QuickSave PerPost Settings
// - QuickSave Cyclic Nonce Refresher
// - AJAX Update Quicksave Nonces
// -----------------------------

// Development TODOs
// -----------------
// - set standard page to archive page via metabox ?
// -- auto-add category / subcategory navigation menu option ?
// - better sticky posts support ?


// ----------------------
// === Editor Metabox ===
// ----------------------

// --------------
// Meta Key Notes
// --------------
// 2.1.1: keys now use _THEMEPREFIX_ instead of just _
// _display_overrides (array)		- header, footer, navigation, secondarynav,
// 									  sidebar, subsidebar, headerwidgets, footerwidgets,
//			 						  image, title, subtitle, metatop, metabottom, authorbio
// _templating_overrides (array)	- TODO: add missing values
// _removefilters (array)			- wpautop, wptexturize, convertsmilies, convertchars
// _thumbnailsize (single key)		- stores override
// _perpoststyles (single key)		- stores style additions

// ------------------
// Add Editor Metabox
// ------------------
// 1.8.0: renamed from muscle_add_metabox
// 2.0.5: move add_action inside for consistency
if ( !function_exists( 'bioship_admin_add_theme_metabox' ) ) {

 add_action( 'admin_init', 'bioship_admin_add_theme_metabox' );

 function bioship_admin_add_theme_metabox() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get custom post types ----
	// TODO: add multicheck option for Theme Options Metabox on CPTs
	$cpts = array( 'post', 'page' );
	$args = array( 'public' => true, '_builtin' => false );
	$cptlist = get_post_types( $args, 'names', 'and' );
	$cpts = array_merge( $cpts, $cptlist );
	// 2.0.5: add filter for post types metabox
	$cpts = bioship_apply_filters( 'admin_theme_metabox_post_types', $cpts );

	// --- metabox position ---
	// 2.1.1: added filter for metabox priority position
	$priority = bioship_apply_filters( 'admin_theme_metabox_priority', 'high' );

	// --- add metaboxes ---
	foreach ( $cpts as $cpt ) {
		add_meta_box( 'theme_metabox', __( 'Theme Display Overrides', 'bioship' ), 'bioship_admin_theme_metabox', $cpt, 'side', $priority );
	}
 }
}

// ----------------------------
// Editor Theme Options Metabox
// ----------------------------
// 1.8.0: renamed from muscle_theme_metabox
// 2.0.0: added missing translation wrappers
if ( !function_exists( 'bioship_admin_theme_metabox' ) ) {
 function bioship_admin_theme_metabox() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings;

	// --- get post data ---
	// 2.1.1: handle new post (no post ID)
	global $post;
	if ( isset( $post ) && is_object( $post ) ) {
		$postid = $post->ID;
		$posttype = $post->post_type;
	} else {
		$postid = '';
		$posttype = 'post';
		if ( isset( $_REQUEST['post_type'] ) ) {
			$posttype = $_REQUEST['post_type'];
		}
	}

	// --- get current override values ---
	$display = bioship_muscle_get_display_overrides( $postid );
	$override = bioship_muscle_get_templating_overrides( $postid );
	$removefilters = bioship_muscle_get_content_filter_overrides( $postid );

	if ( THEMEDEBUG ) {
		bioship_debug( "Post ID", $postid );
		bioship_debug( "Display Overrides", $display );
		bioship_debug( "Templating Overrides", $override );
		bioship_debug( "Filter Overrides", $removefilters );
	}

	// --- option tab script ---
	echo "<script>
	function bioship_click_theme_tab(themeoption) {
		if (document.getElementById('themetabclicked').value == 'mouseover') {var mouseover = true;}
		if ( (document.getElementById('theme'+themeoption).style.display == 'none') || (mouseover == true) ) {
			document.getElementById('themeoptionstab').value = themeoption;
			document.getElementById('themetabclicked').value = 'clicked';
			bioship_show_theme_tab(themeoption);
		} else {
			document.getElementById('themeoptionstab').value = '';
			document.getElementById('themetabclicked').value = '';
			bioship_hide_theme_tab(themeoption);
		}
	}
	function bioship_hover_theme_tab(themeoption) {
		if (document.getElementById('themetabclicked').value == 'clicked') {return;}
		document.getElementById('themetabclicked').value = 'mouseover';
		bioship_show_theme_tab(themeoption);
	}
	function bioship_show_theme_tab(themeoption) {
		document.getElementById('themelayout').style.display = 'none';
		document.getElementById('themesidebar').style.display = 'none';
		document.getElementById('themecontent').style.display = 'none';
		document.getElementById('themestyles').style.display = 'none';
		/* document.getElementById('themefilters').style.display = 'none'; */
		document.getElementById('theme'+themeoption).style.display = '';
		document.getElementById('themelayoutbutton').style.backgroundColor = '#EEE';
		document.getElementById('themesidebarbutton').style.backgroundColor = '#EEE';
		document.getElementById('themecontentbutton').style.backgroundColor = '#EEE';
		document.getElementById('themestylesbutton').style.backgroundColor = '#EEE';
		/* document.getElementById('themefiltersbutton').style.backgroundColor = '#EEE'; */
		document.getElementById('theme'+themeoption+'button').style.backgroundColor = '#DDD';
	}
	function bioship_hide_theme_tab(themeoption) {
		document.getElementById('theme'+themeoption).style.display = 'none';
		document.getElementById('theme'+themeoption+'button').style.backgroundColor = '#EEE';
	}
	function bioship_check_templates() {
		selectelement = document.getElementById('_sidebartemplate');
		template = selectelement.options[selectelement.selectedIndex].value;
		if (template == 'custom') {document.getElementById('sidebarcustom').style.display = '';}
		else {document.getElementById('sidebarcustom').style.display = 'none';}
		selectelement = document.getElementById('_subsidebartemplate');
		subtemplate = selectelement.options[selectelement.selectedIndex].value;
		if (subtemplate == 'custom') {document.getElementById('subsidebarcustom').style.display = '';}
		else {document.getElementById('subsidebarcustom').style.display = 'none';}
		if ( (template == 'custom') || (subtemplate == 'custom') ) {
			document.getElementById('customtemplatelabel').style.display = '';
		} else {document.getElementById('customtemplatelabel').style.display = 'none';}
	}</script>";


	// Button Tabs
	// -----------
	// 1.9.5: merged filters with content tab and add separate sidebar tab
	// 1.9.5: changed _hide prefix to _display_ prefix for form option names

	// --- get current options tab ---
	// 1.8.0: use separate tab value so only for metabox itself
	// 2.1.1: use prefixed post meta key for theme options tab
	$settingstab = '';
	if ( '' != $postid ) {
		$tab = get_post_meta( $postid, '_' . THEMEPREFIX . '_themeoptionstab', true );
	}
	if ( isset( $tab ) && $tab ) {
		$settingstab = $tab;
	}

	// --- tab button styles ---
	// 2.1.1: added a tag text decoration style
	echo "<style>.themeoptionbutton {background-color:#E0E0EF; padding:5px; border-radius:5px;}
	.themeoptionbutton a {text-decoration:none;}</style>";

	// --- theme options tab buttons ---
	// 2.1.0: removed filters tab cell/button remnant
	// TODO: maybe convert a tags to input buttons ?
	echo '<center><table><tr>';

		// --- content tab button ---
		$bgcolor = ( 'content' == $settingstab ) ?  'background-color:#DDD;' : '';
		echo '<td><div id="themecontentbutton" class="themeoptionbutton" style="' . esc_attr( $bgcolor ) . '">';
			echo '<a href="javascript:void(0);" onmouseover="bioship_hover_theme_tab(\'content\');" onclick="bioship_click_theme_tab(\'content\');">';
			echo esc_html( __( 'Content','bioship' ) ) . '</a>';
		echo '</div></td>';

		// --- sidebar tab button ---
		$bgcolor = ( 'sidebar' == $settingstab ) ? 'background-color:#DDD;' : '';
		echo '<td width="10"></td>';
		echo '<td><div id="themesidebarbutton" class="themeoptionbutton" style="' . esc_attr( $bgcolor ) . '">';
			echo '<a href="javascript:void(0);" onmouseover="bioship_hover_theme_tab(\'sidebar\');" onclick="bioship_click_theme_tab(\'sidebar\');">';
			echo esc_html( __( 'Sidebars', 'bioship' ) ) . '</a>';
		echo '</div></td>';

		// --- layout tab button ---
		$bgcolor = ( 'layout' == $settingstab ) ? 'background-color:#DDD;' : '';
		echo '<td width="10"></td>';
		echo '<td><div id="themelayoutbutton" class="themeoptionbutton" style="' . esc_attr( $bgcolor ). '">';
			echo '<a href="javascript:void(0);" onmouseover="bioship_hover_theme_tab(\'layout\');" onclick="bioship_click_theme_tab(\'layout\');">';
			echo esc_html( __( 'Layout', 'bioship' ) ) . '</a>';
		echo '</div></td>';

		// --- styles tab button ---
		// 2.1.4: fix to echo instead of setting variable
		$bgcolor = ( 'styles' == $settingstab ) ? 'background-color:#DDD;' : '';
		echo '<td width="10"></td>';
		echo '<td><div id="themestylesbutton" class="themeoptionbutton" style="' . esc_attr( $bgcolor ) . '">';
			echo '<a href="javascript:void(0);" onmouseover="bioship_hover_theme_tab(\'styles\');" onclick="bioship_click_theme_tab(\'styles\');">';
			echo esc_html( __( 'Styles','bioship' ) ) . '</a>';
		echo '</div></td>';

	echo "</tr></table>";


	// Content Override Tab
	// --------------------
	echo '<div id="themecontent"';
	if ( 'content' != $settingstab ) {
		echo ' style="display:none;"';
	}
	echo '>';
	echo '<table cellpadding="0" cellspacing="0">';

		// Content Display Overrides
		// -------------------------

		// --- apply to template context ---
		// 2.2.0: added for template styling overrides
		$context = get_post_meta( $postid, '_template_context', true );
		if ( !$context ) {
			$context = '';
		}
		$help_text = __( 'Use this to apply these overrides to an additional page or archive context.', 'bioship' );
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr id="template-context-row"><td>';
		echo '<b>' . esc_html( __( 'Extra Context', 'bioship' ) ) . '</b>';
		echo ' <span id="extra-context-icon" class="help-icon dashicons dashicons-editor-help" title="' . esc_attr( $help_text ) . '" onclick="bioship_help_text(\'extra-context\');"></span>';
		echo '</td><td width="10"></td>';
		echo '<td><select id="template-context-select" name="_template_context" onchange="bioship_check_context();">';
		$contexts = array(
			''			=> __( '', 'bioship' ),
			'home'		=> __( 'Home (Blog) Page', 'bioship' ),
			'search'	=> __( 'Search Page', 'bioship' ),
			'404'		=> __( 'Not Found', 'bioship' ),
			'archive'	=> __( 'Archive', 'bioship' ),
			// 'posttype' -> __( 'Post Type', 'bioship' ),
		);
		foreach ( $contexts as $key => $label ) {
			echo '<option value="' . esc_attr( $key ) . '"';
			if ( $context == $key ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $label ) . '</option>';
		}
		echo '</select></td></tr>';
		echo '<tr><td colspan="3" id="extra-context-help" style="max-width:100px; display:none;">';
			echo esc_html( $help_text ) . ' <span class="help-icon dashicons dashicons-no" onclick="bioship_help_close(\'extra-context\');"></span>';
		echo '</td></tr>';
		
		// --- apply to template subcontext ---
		// 2.2.0: added for template styling overrides
		$subcontext = get_post_meta( $postid, '_template_subcontext', true );
		if ( !$subcontext ) {
			$subcontext = '';
		}		
		echo '<tr id="archive-subcontext-row"';
		if ( 'archive' != $context ) {
			echo ' style="display:none;"';
		}
		echo '><td>' . esc_html( 'Archive Context', 'bioship' ) . '</td><td width="10"></td>';
		echo '<td><select name="_archive_subcontext">';
		$subcontexts = array(
			''			=> __( 'General Archive', 'bioship' ),
			'date'		=> __( 'Date Archive', 'bioship' ),
			'author'	=> __( 'Author Archive', 'bioship' ),
			'tag'		=> __( 'Tag Archive', 'bioship' ),
			'category'	=> __( 'Category Archive', 'bioship' ),
			'taxonomy'	=> __( 'Taxonomy Archive', 'bioship' ),
			// 'posttype' => __( 'Post Type Archive', 'bioship' ),
		);
		foreach ( $subcontexts as $key => $label ) {
			echo '<option value="' . esc_attr( $key ) . '"';
			if ( $subcontext == $key ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $label ) . '</option>';
		}
		echo '</td></tr>';
		
		// --- post type subcontext selection ---
		echo '<tr id="posttype-subcontext-row"';
		if ( 'posttype' != $context ) {
			echo ' style="display:none;"';
		}
		echo '><td>' . esc_html( 'Post Type', 'bioship' ) . '</td><td width="10"></td>';
		echo '<td><select name="_posttype_subcontext">';
		$cpts = array( 'page', 'post' );
		$args = array( 'public' => true, '_builtin' => false );		
		$cptlist = get_post_types( $args, 'names', 'and' );
		$cpts = array_merge( $cpts, $cptlist );
		$posttypes = array();
		foreach ( $cpts as $cpt ) {
			$posttypeobject = get_post_type_object( $cpt );
			$cptname = $posttypeobject->labels->singular_name;
			$posttypes[$cpt] = $cptname;
		}
		foreach ( $posttypes as $key => $label ) {
			echo '<option value="' . esc_attr( $key ) . '"';
			if ( $subcontext == $key ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html( $label ) . '</option>';
		}
		echo '</td></tr>';

		// --- content override headings ---
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr><td align="center"><b>' . esc_html( __( 'Content Display', 'bioship' ) ) . '</b></td>';
		echo '<td width="10"></td>';
		echo '<td align="center">' . esc_html( __( 'Hide', 'bioship' ) ) . '</td></tr>';

		// --- content title ---
		echo '<tr><td>' . esc_html( __( 'Title', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_title" id="_display_title" value="1"';
			if ( '1' == $display['title'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// ---- content subtitle ---
		echo '<tr><td>' . esc_html( __( 'Subtitle', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_subtitle" id="_display_subtitle" value="1"';
			if ( '1' == $display['subtitle'] ) {
				echo 'checked="checked"';			
			}
			echo '>';
		echo '</td></tr>';

		// --- hide thumbnail ---
		// 2.1.1.: added missing id for checkbox field
		$thumbdisplay = ( 'page' == $posttype ) ? __( 'Featured Image', 'bioship' ) : __( 'Thumbnail', 'bioship' );
		echo '<tr><td>' . esc_html( $thumbdisplay ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_image" id="_display_image" value="1"';
			if ( '1' == $display['image'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';
		
		// --- content meta top ---
		echo '<tr><td>' . esc_html( __( 'Top Meta', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_metatop" id="_display_metatop" value="1"';
			if ( '1' == $display['metatop'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- content meta bottom ---
		echo '<tr><td>' . esc_html( __( 'Bottom Meta', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_metabottom" id="_display_metabottom" value="1"';
			if ( '1' == $display['metabottom'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- author bio box ---
		echo '<tr><td>' . esc_html( __( 'Author Bio', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_authorbio" id="_display_authorbio" value="1"';
			if ( '1' == $display['authorbio'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// Thumbnail Size Override
		// -----------------------
		// 2.2.0: moved down to below main content overide settings

		// --- get default setting ---
		if ( 'page' == $posttype ) {
			$thumbdefault = $vthemesettings['pagethumbsize'];
		} else {
			$thumbdefault = $vthemesettings['postthumbsize'];
		}

		// --- setup available thumbnail sizes ---
		$thumbarray = array(
			'thumbnail'	=> __( 'Thumbnail', 'bioship' ) . ' (' . get_option( 'thumbnail_size_w' ) . ' x ' . get_option( 'thumbnail_size_h' ) . ')',
			'medium'	=> __( 'Medium', 'bioship' ) . ' (' . get_option( 'medium_size_w' ) . ' x ' . get_option( 'medium_size_h' ) . ')',
			'large'		=> __( 'Large', 'bioship' ) . ' (' . get_option( 'large_size_w' ) . ' x ' . get_option( 'large_size_h' ) . ')',
			'full'		=> __( 'Full Size', 'bioship' ) . ' (' . __( 'original', 'bioship' ) . ')',
		);

		// --- get additional image sizes ---
		// 2.2.0: use internal theme image sizes
		$image_sizes = bioship_get_image_sizes();
		foreach ( $image_sizes as $image_size ) {
			$thumbarray[$image_size['name']] = $image_size['title'] . ' (' . $image_size['width'] . ' x ' .$image_size['height'] . ')';
		}
		global $_wp_additional_image_sizes;
		$image_sizes = get_intermediate_image_sizes();
		$oldsizenames = array( 'squared150', 'squared250', 'video43', 'video169' );
		foreach ( $image_sizes as $size_name ) {
			// 2.0.5: no longer output old size names as options
			// 2.2.0: only add if not already added
			if ( !in_array( $size_name, $oldsizenames ) && !array_key_exists( $size_name, $thumbarray ) ) {
				// 1.9.8: fix to sporadic undefined index warning (huh? size names should match?)
				if ( isset( $_wp_additional_image_sizes[$size_name] ) ) {
					$label = $size_name; // use size title instead ?
					$label .= ' (' . $_wp_additional_image_sizes[$size_name]['width'] . ' x ' . $_wp_additional_image_sizes[$size_name]['height'] . ')';
					$thumbarray[$size_name] = $label;
				}
			}
		}

		// --- get thumbnail size override ---
		// 1.8.0: keep individual meta key for this
		// 2.1.1: added theme prefix to thumbnail size metakey
		$thumbnailsize = '';
		if ( '' != $postid ) {
			$thumbnailsize = get_post_meta( $postid, '_'. THEMEPREFIX . '_thumbnailsize', true );
		}

		// --- maybe convert old thumbnail size names ---
		// 2.0.5: maybe convert to prefixed names and update meta
		// 2.2.0: simplified by array loop
		$sizes = array(
			'squared150'	=> 'bioship-150s',
			'squared250'	=> 'bioship-250s',
			'video43'		=> 'bioship-4-3',
			'video169'		=> 'bioship-16-9',
			'opengraph'		=> 'bioship-opengraph',
		);
		foreach ( $sizes as $old => $new ) {
			if ( $old = $thumbnailsize ) {
				$newthumbsize = $new;
			}
		}
		if ( isset( $newthumbsize ) ) {
			update_post_meta( $postid, '_' . THEMEPREFIX . '_thumbnailsize', $newthumbsize );
			$thumbnailsize = $newthumbsize;
		}

		// --- thumbnail size override selector ---
		// 2.0.7: fix to text domin typo (bioship.)
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr><td colspan="3" align="center">';
			echo '<b>' . esc_html( $thumbdisplay ) . ' ' . esc_html( __( 'Size','bioship' ) ) . '</b>';
			echo '(' . esc_html( __( 'default', 'bioship' ) ) . ' ' . esc_attr( $thumbdefault ) . ')<br>';
			echo '<select name="_thumbnailsize" id="_thumbnailsize" style="font-size:9pt;">';
				echo '<option value=""';
				if ( '' == $thumbnailsize ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( __( 'Theme Settings Default', 'bioship' ) ) . '</option>';
				// 2.1.1: fix to missing option value for no thumbnail
				echo '<option value="off"';
				if ( 'off' == $thumbnailsize ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( __( 'No Thumbail Output', 'bioship' ) ) . '</option>';
				foreach ( $thumbarray as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '"';
					if ($thumbnailsize == $key) {
						echo ' selected="selected"';
					}
					echo '>' . esc_html( $value ) . '</option>';
				}
			echo '</select>';
		echo '</td></tr>';

		// Content Filter Overrides
		// ------------------------
		// 1.9.5: merged to content tab from separate filters tab

		// --- content filters heading ---
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr><td align="center"><b>' . esc_html( __( 'Content Filter', 'bioship' ) ) . '</b></td><td></td>';
		echo '<td align="center">' . esc_html( __( 'Disable', 'bioship' ) ) . '</td></tr>';

		// --- wpautop filter ---
		echo '<tr><td>wpautop</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_wpautop" id="_wpautop" value="1"';
			if ( isset( $removefilters['wpautop'] ) && ( '1' == $removefilters['wpautop'] ) ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- wptexturize filter ---
		echo '<tr><td>wptexturize</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_wptexturize" id="_wptexturize" value="1"';
			if ( isset( $removefilters['wptexturize'] ) && ( '1' == $removefilters['wptexturize'] ) ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- convert_smilies filter ---
		echo '<tr><td>convert_smilies</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_convertsmilies" id="_convertsmilies" value="1"';
			if ( isset( $removefilters['convertsmilies'] ) && ( '1' == $removefilters['convertsmilies'] ) ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- convert_chars filter ---
		echo '<tr><td>convert_chars</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_convertchars" id="_convertchars" value="1"';
			if ( isset( $removefilters['convertchars'] ) && ( '1' == $removefilters['convertchars'] ) ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- quicksave settings button ---
		echo '<tr height="5"><td> </td></tr><tr><td align="center">';
			echo '<div class="quicksavedsettings" id="quicksavedsettings-content">';
				echo esc_html( __( 'Saved!', 'bioship' ) );
			echo '</div>';
		echo '</td><td width="10"></td><td align="right">';
			echo '<input type="button" onclick="quicksavesettings(\'content\');" value="' . esc_attr( __( 'Save Overrides', 'bioship' ) ) . '" class="button-secondary">';
		echo '</td></tr>';

	// --- close content tab ---
	echo '</table></div>';

	// --- check template context value script ---
	// 2.2.0: added to show/hide archive subcontext
	echo "<script>function bioship_check_context() {
		select = document.getElementById('template-context-select');
		val = select.options[select.selectedIndex].value;
		console.log('Select Value: '+val);
		if (val == 'archive') {a = ''; b = 'none';}
		else if (val == 'postttype') {a = 'none'; b = '';}
		else {a = 'none'; b = 'none';}
		document.getElementById('archive-subcontext-row').style.display = a;
		document.getElementById('posttype-subcontext-row').style.display = b;
	}</script>";


	// Sidebar Overrides
	// -----------------
	// TODO: add display of total column width ?
	// 1.9.5: separate tab for sidebar overrides
	echo '<div id="themesidebar"';
	if ( 'sidebar' != $settingstab ) {
		echo ' style="isplay:none;"';
	}
	echo '>';
	echo '<table cellpadding="0" cellspacing="0">';

		// -- set column options ---
		// 2.2.0: use number internationalization instead of translate
		$subsidebarcolumns = array(
			''		=> __( 'Default', 'bioship' ),
			'one'	=> ' ' . number_format_i18n( 1 ) . ' ',
			'two'	=> ' ' . number_format_i18n( 2 ) . ' ',
			'three'	=> ' ' . number_format_i18n( 3 ) . ' ',
			'four'	=> ' ' . number_format_i18n( 4 ) . ' ',
			'five'	=> ' ' . number_format_i18n( 5 ) . ' ',
			'six'	=> ' ' . number_format_i18n( 6 ) . ' ',
			'seven'	=> ' ' . number_format_i18n( 7 ) . ' ',
			'eight'	=> ' ' . number_format_i18n( 8 ) . ' ',
		);
		$sidebarcolumns = array_merge( $subsidebarcolumns, array(
			'nine'		=> ' ' . number_format_i18n( 9 ) . ' ',
			'ten'		=> number_format_i18n( 10 ),
			'eleven'	=> number_format_i18n( 11 ),
			'twelve'	=> number_format_i18n( 12 ),
		) );
		$contentcolumns = array_merge( $sidebarcolumns, array(
			'thirteen'		=> number_format_i18n( 13 ),
			'fourteen'		=> number_format_i18n( 14 ),
			'fifteen'		=> number_format_i18n( 15 ),
			'sixteen'		=> number_format_i18n( 16 ),
			'seventeen'		=> number_format_i18n( 17 ),
			'eighteen'		=> number_format_i18n( 18 ),
			'nineteen'		=> number_format_i18n( 19 ),
			'twenty'		=> number_format_i18n( 20 ),
			'twentyone'		=> number_format_i18n( 21 ),
			'twentytwo'		=> number_format_i18n( 22 ),
			'twentythree'	=> number_format_i18n( 23 ),
			'twentyfour'	=> number_format_i18n( 24 ),
		) );

		// --- content columns ---
		echo '<tr><td colspan="5" align="center">';
			echo '<table><tr><td>' . esc_html( __( 'Content Columns', 'bioship' ) ) . '</td>';
				echo '<td width="10"></td><td>';
				echo '<select name="_contentcolumns" id="_contentcolumns">';
				foreach ( $contentcolumns as $width => $label ) {
					echo '<option value="' . esc_attr( $width ) . '"';
					if ( $override['contentcolumns'] == $width ) {
						echo ' selected="selected"';
					}					
					echo '>' . esc_html( $label ) . '</option>';
				}
				echo '</select>';
			echo '</td></tr></table>';
		echo '</td></tr>';

		// --- column headings ---
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr><td></td><td></td><td align="center"><b>' . esc_html( __( 'Sidebar', 'bioship' ) ) . '</b></td><td></td>';
		echo '<td align="center"><b>' . esc_html( __( 'SubSidebar', 'bioship' ) ) . '</b></td></tr>';
		echo '<tr><td align="right">' . esc_html( __( 'Columns', 'bioship' ) ) . '</td><td width="5"></td>';

		// --- sidebar columns ---
		echo '<td>';
			echo '<select name="_sidebarcolumns" id="_sidebarcolumns" style="width:100%;font-size:9pt;">';
			foreach ( $sidebarcolumns as $width => $label ) {
				echo '<option value="' . esc_attr( $width ) . '"';
				if ( $override['sidebarcolumns'] == $width ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		echo '</td><td width="5"></td>';

		// --- subsidebar columns ---
		echo '<td>';
			echo '<select name="_subsidebarcolumns" id="_subsidebarcolumns" style="width:100%;font-size:9pt;">';
			foreach ( $subsidebarcolumns as $width => $label ) {
				echo '<option value="' . esc_attr( $width ) . '"';
				if ( $override['subsidebarcolumns'] == $width ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		echo '</td></tr>';

		// Sidebar Templates
		// -----------------
		$sidebartemplates = array(
			''			=> __( 'Default', 'bioship' ),
			'off'		=> __( 'None', 'bioship' ),
			'blank'		=> __( 'Blank', 'bioship' ),
			'primary'	=> __( 'Primary', 'bioship' ),
		);
		$subsidebartemplates = array(
			''				=> __( 'Default', 'bioship' ),
			'off'			=> __( 'None', 'bioship' ),
			'subblank'		=> __( 'Blank', 'bioship' ),
			'subsidiary'	=> __( 'Subsidiary', 'bioship' ),
		);

		// TODO: use the new sidebar template search function here ?
		// $get_templates = bioship_get_sidebar_templates_info();
		$templates = array(
			'page'		=> __( 'Page', 'bioship' ),
			'post'		=> __( 'Post', 'bioship' ),
			'front'		=> __( 'Front', 'bioship' ),
			'home'		=> __( 'Home', 'bioship' ),
			'archive'	=> __( 'Archive', 'bioship' ),
			'category'	=> __( 'Category', 'bioship' ),
			'taxonomy'	=> __( 'Taxonomy', 'bioship' ),
			'tag'		=> __( 'Tag', 'bioship' ),
			'author'	=> __( 'Author', 'bioship' ),
			'date'		=> __( 'Date', 'bioship' ),
			'search'	=> __( 'Search', 'bioship' ),
			'notfound'	=> __( 'NotFound', 'bioship' ),
		);
		$sidebartemplates = array_merge( $sidebartemplates, $templates );
		foreach ( $templates as $key => $label ) {
			$subsidebartemplates['sub' . $key] = $label;
		}
		$sidebartemplates['custom'] = $subsidebartemplates['custom'] = __( 'Custom', 'bioship' );

		// --- sidebar template headings ---
		// 2.1.1: added missing translation wrappers
		// 2.2.0: fix to vertical-align style typo
		echo '<tr><td style="vertical-align:top;" align="right">';
			echo esc_html( __( 'Template', 'bioship' ) ) . "<br>";
			
			echo '<div id="customtemplatelabel" style="margin-top:10px;';
			if ( ( 'custom' != $override['sidebartemplate'] ) && ( 'custom' != $override['subsidebartemplate'] ) ) {
				echo ' display:none;';
			}
			echo '">' . esc_html( __( 'Slug', 'bioship' ) ) . ":</div>";
		echo '</td><td width="5"></td>';

		// --- sidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo '<td style="vertical-align:top;">';
			echo '<select name="_sidebartemplate" id="_sidebartemplate" style="width:100%;font-size:9pt;" onchange="bioship_check_templates();">';
			foreach ( $sidebartemplates as $template => $label ) {
				echo '<option value="' . esc_attr( $template ) . '"';
				if ( $override['sidebartemplate'] == $template ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( $label ) . '</option>';
			}
			echo "</select><br>";

			// --- custom sidebar template ---
			echo '<div id="sidebarcustom"';
			if ( 'custom' != $override['sidebartemplate'] ) {
				echo ' style="display:none;"';
			}
			echo '>';
				// 2.1.1: added missing id attribute for input
				echo '<input type="text" name="_sidebarcustom" id="_sidebarcustom" style="width:80px;font-size:9pt;" value="' . esc_attr( $override['sidebarcustom'] ) . '">';
			echo '</div>';
		echo '</td><td width="5"></td>';

		// --- subsidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo '<td style="vertical-align:top;">';
			echo '<select name="_subsidebartemplate" id="_subsidebartemplate" style="width:100%;font-size:9pt;" onchange="bioship_check_templates();">';
				foreach ( $subsidebartemplates as $template => $label ) {
					echo '<option value="' . esc_attr( $template ) . '"';
					if ( $override['subsidebartemplate'] == $template ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_html( $label ) . '</option>';
				}
			echo '</select><br>';

			// --- custom subsidebar template ---
			echo '<div id="subsidebarcustom"';
			if ( 'custom' != $override['subsidebartemplate'] ) {
				echo ' style="display:none;"';
			}
			echo '>';
				// 2.1.1: added missing id attribute for input
				echo '<input type="text" name="_subsidebarcustom" id="_subsidebarcustom" style="width:80px;font-size:9pt;" value="' . esc_attr( $override['subsidebarcustom'] ) . '">';
			echo '</div>';
		echo '</td></tr>';

		// --- main sidebar position ---
		$sidebarpositions = array(
			''		=> __( 'Default','bioship' ),
			'left'	=> __( 'Left','bioship' ),
			'right'	=> __( 'Right','bioship' ),
		);
		echo '<tr><td align="right">';
			// 2.1.1: added missing translation wrapper
			echo esc_html( __( 'Position','bioship' ) );
		echo '</td><td width="5"></td><td>';
			echo '<select name="_sidebarposition" id="_sidebarposition" style="width:100%;font-size:9pt;">';
			foreach ( $sidebarpositions as $position => $label ) {
				echo '<option value="' . esc_attr( $position ) . '"';
				if ($override['sidebarposition'] == $position) {$selected = " selected='selected'";} else {$selected = '';}
				echo '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		echo '</td><td width="5"></td>';

		// --- subsidebar position ---
		$subsidebarpositions = array(
			''			=> __( 'Default', 'bioship' ),
			'opposite'	=> __( 'Opposite', 'bioship' ),
			'internal'	=> __( 'Internal', 'bioship' ),
			'external'	=> __( 'External', 'bioship' ),
		);
		echo '<td>';
			// 2.1.1: added missing id field for subsidebar position
			echo '<select name="_subsidebarposition" id="_subsidebarposition" style="width:100%;font-size:9pt;">';
			foreach ( $subsidebarpositions as $position => $label ) {
				echo '<option value="' . esc_attr( $position ) . '"';
				if ( $override['subsidebarposition'] == $position ) {
					echo ' selected="selected"';
				}
				echo '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
		echo '</td></tr>';
		echo '</table>';

		// --- sidebar display headings ---
		echo '<table cellpadding="0" cellspacing="0"><tr height="10"><td> </td></tr>';
		echo '<tr><td align="center"><b>' . esc_html( __( 'Sidebar Display', 'bioship' ) ) . '</b></td>';
		echo '<td></td><td align="center">' . esc_html( __( 'Hide','bioship' ) ) . '</td></tr>';

		// --- main sidebar hide ---
		echo '<tr><td>' . esc_html( __( 'Main Sidebar', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_sidebar" id="_display_sidebar" value="1"';
			if ( '1' == $display['sidebar'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- subsidebar hide ---
		echo '<tr><td>' . esc_html( __( 'SubSidebar', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_subsidebar" id="_display_subsidebar" value="1"';
			if ( '1' == $display['subsidebar'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- header widgets hide ---
		echo '<tr><td>' . esc_html( __( 'Header Widgets', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_headerwidgets" id="_display_headerwidgets" value="1"';
			if ( '1' == $display['headerwidgets'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer widgets hide ---
		echo '<tr><td>' . esc_html( __( 'Footer Widgets', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footerwidgets" id="_display_footerwidgets" value="1"';
			if ( '1' == $display['footerwidgets'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer widget area 1 ---
		echo '<tr><td>' . esc_html( __( 'Footer Area', 'bioship' ) ) . ' ' . number_format_i18n( 1 ) . '</td>';
			echo '<td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footer1" id="_display_footer1" value="1"';
			if ( '1' == $display['footer1'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer widget area 2 ---
		echo '<tr><td>' . esc_html( __( 'Footer Area', 'bioship' ) ) . ' ' . number_format_i18n( 2 ) . '</td>';
			echo '<td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footer2" id="_display_footer2" value="1"';
			if ( '1' == $display['footer2'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer widget area 3 ---
		echo '<tr><td>' . esc_html( __( 'Footer Area', 'bioship' ) ) . ' ' . number_format_i18n( 3 ) . '</td>';
		echo '<td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footer3" id="_display_footer3" value="1"';
			if ( '1' == $display['footer3'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer widget area 4 ----
		echo '<tr><td>' . esc_html( __( 'Footer Area', 'bioship' ) ) . ' ' . number_format_i18n( 4 ) . '</td>';
			echo '<td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footer4" id="_display_footer4" value="1"';
			if ( '1' == $display['footer4'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- quicksave settings button ---
		echo '<tr height="5"><td> </td></tr><tr><td align="center">';
			echo '<div class="quicksavedsettings" id="quicksavedsettings-sidebar">' . esc_html( __( 'Saved!', 'bioship' ) ) . '</div>';
		echo '</td><td width="10"></td><td align="right">';
			// 2.2.0: fix to class typo (utton-secondary)
			echo '<input type="button" onclick="quicksavesettings(\'sidebar\');" value="' . esc_attr( __( 'Save Overrides', 'bioship' ) ) . '" class="button-secondary">';
		echo '</td></tr>';

	// --- close sidebar overrides tab ---
	echo '</table></div>';


	// Layout Overrides
	// ----------------
	echo '<div id="themelayout"';
	if ( 'layout' != $settingstab ) {
		echo ' style="display:none;"';
	}	
	echo '>';
	echo '<table cellpadding="0" cellspacing="0">';

		// --- layout overrides heading ---
		echo '<tr><td colspan="3" align="center">';
			echo '<b>' . esc_html( __( 'Layout Display Overrides', 'bioship' ) ) . '</b>';
		echo '</td></tr>';

		// --- no wrap margins (full width) ---
		// 1.8.5: added full width container option (no wrap margins)
		echo '<tr><td>' . esc_html( __( 'No Wrap Margins', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_wrapper" id="_display_wrapper" value="1"';
			if ( '1' == $display['wrapper'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- hide header ---
		echo '<tr><td>' . esc_html( __( 'Hide Header', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_header" id="_display_header" value="1"';
			if ( '1' == $display['header'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- hide footer ---
		echo '<tr><td>' . esc_html( __( 'Hide Footer', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footer" id="_display_footer" value="1"';
			if ( '1' == $display['footer'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// TODO: general layout displays?
		// Header Logo / Title Text / Description / Extras
		// Footer Extras / Site Credits

		// --- navigation display headings ---
		// 1.9.8: fix to headernav and footernav keys
		echo '<tr height="10"><td> </td></tr>';
		echo '<tr><td align="center"><b>' . esc_html( __( 'Navigation Display', 'bioship' ) ) . '<b></td>';
		echo '<td></td><td align="center">' . esc_html( __( 'Hide','bioship' ) ) . '</td></tr>';

		// --- main navigation menu ---
		echo '<tr><td>' . esc_html( __( 'Main Nav Menu', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_navigation" id="_display_navigation" value="1"';
			if ( '1' == $display['navigation'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- secondary navigation ---
		echo '<tr><td>' . esc_html( __( 'Secondary Nav Menu', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_secondarynav" id="_display_secondarynav" value="1"';
			if ( '1' == $display['secondarynav'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- header navigation menu ---
		echo '<tr><td>' . esc_html( __( 'Header Nav Menu', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_headernav" id="_display_headernav" value="1"';
			if ( '1' == $display['headernav'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- footer navigation menu ---
		echo '<tr><td>' . esc_html( __( 'Footer Nav Menu', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_footernav" id="_display_footernav" value="1"';
			if ( '1' == $display['footernav'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- breadcrumbs ---
		echo '<tr><td>' . esc_html( __( 'Breadcrumbs', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_breadcrumb" id="_display_breadcrumb" value="1"';
			if ( '1' == $display['breadcrumb'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- page navi ---
		echo '<tr><td>' . esc_html( __( 'Post/Page Navi', 'bioship' ) ) . '</td><td width="10"></td><td align="center">';
			echo '<input type="checkbox" name="_display_pagenavi" id="_display_pagenavi" value="1"';
			if ( '1' == $display['pagenavi'] ) {
				echo ' checked="checked"';
			}
			echo '>';
		echo '</td></tr>';

		// --- quicksave settings button ---
		echo '<tr height="5"><td> </td></tr><tr><td align="center">';
			echo '<div class="quicksavedsettings" id="quicksavedsettings-layout">' . esc_html( __( 'Saved!', 'bioship' ) ) . '</div>';
		echo '</td><td width="10"></td><td align="right">';
			echo '<input type="button" onclick="quicksavesettings(\'layout\');" value="' . esc_attr( __( 'Save Overrides', 'bioship' ) ) . '" class="button-secondary">';
		echo '</td></tr>';

	// --- close layout override tab ---
	echo '</table></div>';


	// Style Overrides
	// ---------------
	// 1.8.0: javascript to expand/collapse style box
	// 2.1.1: added marginTop to help prevent editor overlay
	// 2.2.0: added prefix to expand/collapse function names
	echo "<script>function bioship_expand_post_css() {
		document.getElementById('expandpostcss').style.display = 'none';
		document.getElementById('collapsepostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '600px';
		document.getElementById('perpoststyles').style.height = '300px';
		perpoststylebox = document.getElementById('perpoststylebox');
		perpoststylebox.style.width = '620px';
		perpoststylebox.style.marginTop = '40px';
		perpoststylebox.style.marginLeft = '-375px';
		perpoststylebox.style.paddingLeft = '20px';
		perpoststylebox.style.paddingTop = '20px';
		perpoststylebox.style.paddingBottom = '15px';
		perpoststylebox.style.borderLeft = '1px solid #CCC';
	}
	function bioship_collapse_post_css() {
		document.getElementById('collapsepostcss').style.display = 'none';
		document.getElementById('expandpostcss').style.display = '';
		document.getElementById('perpoststyles').style.width = '100%';
		document.getElementById('perpoststyles').style.height = '200px';
		perpoststylebox = document.getElementById('perpoststylebox');
		perpoststylebox.style.width = '100%';
		perpoststylebox.style.marginTop = '0px';
		perpoststylebox.style.marginLeft = '0px';
		perpoststylebox.style.paddingLeft = '0px';
		perpoststylebox.style.paddingTop = '0px';
		perpoststylebox.style.paddingBottom = '0px';
		perpoststylebox.style.borderLeft = '0';
	}</script>";

	// 2.1.1: added .quicksavesettings class
	// 2.2.0: change to .quicksavedsettings class
	echo "<style>#quicksavedsettings-css, .quicksavedsettings {display:none; padding:3px 6px; max-width:80px; ";
	echo "font-size:10pt; color: #333; font-weight:bold; background-color: lightYellow; border: 1px solid #E6DB55;}</style>";

	// --- get per post styles ---
	// 1.8.0: keep individual meta key for this
	// 2.1.1: added theme prefix to post metakey
	$perpoststyles = '';
	if ( '' != $postid ) {
		$perpoststyles = get_post_meta( $postid, '_' . THEMEPREFIX . '_perpoststyles', true );
	}

	// --- per post styles tab ---
	echo '<div id="themestyles"';
	if ( 'styles' != $settingstab ) {
		echo ' style="display:none;"';
	}
	echo '>';
	echo '<table cellpadding="0" cellspacing="0" style="width:100%;overflow:visible;">';

		// --- style textarea ---
		echo '<tr><td colspan="2" align="center"><b>' . esc_html( __( 'Post Specific CSS Style Rules', 'bioship' ) ) . "</b></td></tr>";
		echo '<tr><td>';
			echo '<div id="expandpostcss" style="float:left; margin-left:10px;">';
				echo '<a href="javascript:void(0);" onclick="bioship_expand_post_css();" style="text-decoration:none;">&larr; ' . esc_html( __( 'Expand', 'bioship' ) ) . '</a>';
			echo '</div>';
			echo '<div id="collapsepostcss" style="float:right; margin-right:20px; display:none;">';
				echo '<a href="javascript:void(0);" onclick="bioship_collapse_post_css();" style="text-decoration:none;">' . esc_html( __( 'Collapse', 'bioship' ) ) . ' &rarr;</a>';
			echo '</div>';
		echo '</td></tr>';
		echo '<tr><td colspan="2">';
			echo '<div id="perpoststylebox" style="background:#FFF;">';
				echo '<textarea rows="5" cols"30" name="_perpoststyles" id="perpoststyles" style="width:100%;height:200px;">';
				// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo $perpoststyles;
				echo '</textarea>';
			echo '</div>';
		echo '</td></tr>';

		// --- quicksave CSS button ---
		// 2.2.0: fix to quicksavedsettings message ID
		echo '<tr><td align="center">';
			echo '<div id="quicksavedsettings-css">' . esc_html( __( 'CSS Saved!', 'bioship' ) ) . '</div>';
		echo '</td><td align="right">';
			echo '<input type="button" onclick="quicksavecss();" value="' . esc_attr( __( 'QuickSave CSS', 'bioship' ) ) . '" class="button-secondary">';
		echo '</td></tr>';

	// --- close style override tab ---
	echo '</table></div>';

	// --- end tabs output ---
	echo '</center>';

	// --- theme options current tab saver ---
	echo '<input type="hidden" id="themeoptionstab" name="_themeoptionstab" value="' . esc_attr( $settingstab ) . '">';
	echo '<input type="hidden" id="themetabclicked" name="_themetabclicked" value="">';


	// --- enqueue quicksave forms ---
	// 1.9.5: added quicksave perpost CSS form to footer
	add_action( 'admin_footer', 'bioship_admin_quicksave_perpost_css_form' );
	// 2.0.0: added quicksave perpost settings form to footer (prototype)
	add_action( 'admin_footer', 'bioship_admin_quicksave_perpost_settings_form' );
	// 2.1.1: added quicksave cyclic nonce refresher
	add_action( 'admin_footer', 'bioship_admin_quicksave_nonce_refresher' );
	// 2.2.0: added for mobile help text displays
	add_action( 'admin_footer', 'bioship_admin_help_text_script');

 }
}

// -----------------
// Help Text Scripts
// -----------------
if ( !function_exists( 'bioship_admin_help_text_script' ) ) {
 function bioship_admin_help_text_script() {
	 
	// --- mobile help display functions ---
	echo "<script>function bioship_help_text(id) {
		help = document.getElementById(id+'-help');
		if (help.style.display == 'none') {
			document.getElementById(id+'-icon').style.color = '#0077dd';
			document.getElementById(id+'-help').style.display = '';
		} else {
			document.getElementById(id+'-icon').style.color = '';
			document.getElementById(id+'-help').style.display = 'none';
		}
	}
	function bioship_help_close(id) {
		document.getElementById(id+'-icon').style.color = '';
		document.getElementById(id+'-help').style.display = 'none';
	}</script>";

	// --- help hover icon styles ---
	echo "<style>.help-icon.dashicons-editor-help:hover {color: #0077dd;}
	.help-icon.dashicons-no:hover {color: #bb0000;}</style>";
 }
}

// ---------------------
// Update Metabox Values
// ---------------------
// 1.8.0: renamed from muscle_update_metabox_options
if ( !function_exists( 'bioship_admin_update_metabox_options' ) ) {

 // 2.2.0: moved add actions inside for consistency
 add_action( 'publish_post', 'bioship_admin_update_metabox_options' );
 add_action( 'save_post', 'bioship_admin_update_metabox_options' );

 function bioship_admin_update_metabox_options() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check post values ---
	// 1.9.8: return if post is empty
	global $post;
	if ( !is_object( $post ) ) {
		return;
	}
	$postid = $post->ID;

	// --- check for autosave ---
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// --- check user capabilities ---
	// 1.8.0: cleaner save logic here
	if ( !current_user_can( 'edit_posts' ) || !current_user_can( 'edit_post', $postid ) ) {
		return;
	}

	// --- save template context value ---
	$context = $_POST['_template_context'];
	$valid = array( '', 'home', 'search', '404', 'archive' );
	if ( in_array( $context, $valid ) ) {
		update_post_meta( $postid, '_template_context', $context );
	}
	$archive_subcontext = $_POST['_archive_subcontext'];
	$valid = array( '', 'tag', 'author', 'category', 'taxonomy', 'date' );
	if ( ( 'archive' == $context ) && in_array( $archive_subcontext, $valid ) ) {
		update_post_meta( $postid, '_archive_subcontext', $context );
	}
	$posttype_subcontext = $_POST['_posttype_subcontext'];
	$valid = array( 'post', 'page' );
	if ( ( 'posttype' == $context ) && in_array( $posttype_subcontext, $valid ) ) {
		update_post_meta( $postid, '_posttype_subcontext' );
	}

	// --- save display overrides --
	// 1.8.0: grouped display overrides to array
	// 1.8.5: added headernav, footernav, breadcrumbs, pagenavi
	$display = array();
	$postdata = false;
	$displaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);
	// 1.9.5: changed _hide prefix to _display_
	foreach ( $displaykeys as $key ) {
		if ( !isset( $_POST['_display_' . $key] ) ) {
			$display[$key] = '';
		} elseif ( '1' == $_POST['_display_' . $key] ) {
			$display[$key] = '1';
			$postdata = true;
		} else {
			$display[$key] = '';
		}
	}
	// 1.9.9: check and save only if new post data
	// 2.1.1: use prefixed metakey for saving
	// 2.1.1: set unique argument to true here
	delete_post_meta( $postid, '_' . THEMEPREFIX . '_display_overrides' );
	if ( $postdata ) {
		add_post_meta( $postid, '_' . THEMEPREFIX . '_display_overrides', $display, true );
	}

	// --- save layout overrides ---
	// 1.9.5: added override keys
	$override = array();
	$postdata = false;
	$overridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);
	foreach ( $overridekeys as $key ) {
		if ( !isset( $_POST['_' . $key] ) ) {
			$override[$key] = '';
		} else {
			$override[$key] = $_POST['_' . $key];
			$postdata = true;
		}
	}
	delete_post_meta( $postid, '_' . THEMEPREFIX . '_templating_overrides' );
	// 1.9.9: check and save if new post data
	// 2.1.1: use prefixed metakey for saving
	if ( $postdata ) {
		add_post_meta($postid, '_' . THEMEPREFIX . '_templating_overrides', $override );
	}

	// --- save filter overrides ---
	// 1.8.0: grouped filters to array
	// 2.0.0: better checkbox save logic
	$removefilters = array();
	$postdata = false;
	$filters = array( 'wpautop', 'wptexturize', 'convertsmilies', 'convertchars' );
	foreach ( $filters as $filter ) {
		if ( !isset( $_POST['_' . $filter] ) ) {
			$removefilters[$filter] = '';
		} else {
			if ( '1' == $_POST['_' . $filter] ) {
				$removefilters[$filter] = '1';
				$postdata = true;
			} else {
				$removefilters[$filter] = '';
			}
		}
	}
	delete_post_meta( $postid, '_' . THEMEPREFIX . '_removefilters' );
	// 1.9.9: check and save if new filters
	// 2.0.0: save if post data found
	// 2.1.1: use prefixed metakey for saving
	if ( $postdata ) {
		add_post_meta( $postid, '_' . THEMEPREFIX . '_removefilters', $removefilters, true );
	}

	// --- save individual options ---
	// 1.8.0: save individual key values
	$optionkeys = array( '_perpoststyles', '_thumbnailsize', '_themeoptionstab' );
	foreach ( $optionkeys as $option ) {
		// 1.9.9: make sure option value is actually set (as metabox may be removed)
		if ( isset( $_POST[$option] ) ) {
			$optionvalue = $_POST[$option];
			if ( '_perpoststyles' == $option) {
				$optionvalue = stripslashes( $optionvalue );
			}
			// 2.1.1: use prefixed metakey for saving
			$option = str_replace( '_', '_' . THEMEPREFIX . '_', $option);
			delete_post_meta( $postid, $option );
			// 1.9.5: to make cleaner, do not save empty values
			if ( '' != trim( $optionvalue ) ) {
				add_post_meta( $postid, $option, $optionvalue, true );
			}
			$options[$option] = $optionvalue;
		}
	}

	// --- for manual debug of per post options ---
	$metasavedebug = false; // $metasavedebug = true;
	if ( $metasavedebug ) {
		$debuginfo = PHP_EOL . " Saved Post " . $postid." at " . date( 'j/m/d H:i:s', time() ) . PHP_EOL;
		$debuginfo .= "--- Override ---" . PHP_EOL;
		foreach ( $override as $key => $value ) {
			$debuginfo .= $key . ': ' . $value . PHP_EOL;
		}
		$debuginfo .= "--- Display ---" . PHP_EOL;
		foreach ( $display as $key => $value ) {
			$debuginfo .= $key . ': ' . $value . PHP_EOL;
		}
		$debuginfo .= "--- Filters ---" . PHP_EOL;
		foreach ( $removefilters as $key => $value ) {
			$debuginfo .= $key . ': ' . $value . PHP_EOL;
		}
		// 2.1.2: fix for possible undefined variable warning
		if ( isset( $options ) ) {
			$debuginfo .= "--- Options ---" . PHP_EOL;
			foreach ( $options as $key => $value ) {
				$debuginfo .= $key . ': ' . print_r( $value, true ) . PHP_EOL;
			}
		}
		// $debuginfo .= "--- Posted ---".PHP_EOL; foreach ($posted as $key => $value) {$debuginfo .= $key.': '.print_r($value,true).PHP_EOL;}
		bioship_write_debug_file( 'perpost-debug-'.$postid.'.txt', $debuginfo );
	}
 }
}


// ------------------
// === QuickSaves ===
// ------------------

// -----------------------
// Quicksave Styles Action
// -----------------------
// 2.2.0: added for admin bar styles dropdown saving
if ( !function_exists( 'bioship_admin_quicksave_styles' ) ) {

 add_action( 'wp_ajax_bioship_quicksave_styles', 'bioship_admin_quicksave_styles' );
 add_action( 'wp_ajax_nopriv_bioship_quicksave_styles', 'bioship_admin_quicksave_styles' );

 function bioship_admin_quicksave_styles() {
 
 	global $vthemesettings, $vthemename;
  
 	// --- check style type ---
 	$valid = array( 'theme', 'post', 'admin' );
 	if ( !isset( $_POST['style-type'] ) || !in_array( $_POST['style-type'], $valid ) ) {
 		exit;
 	}
 	$type = $_POST['style-type'];
	
	if ( is_user_logged_in() ) {

		// --- check nonce ---
		$checknonce = false;
		if ( isset( $_POST['_wpnonce'] ) ) {
			$nonce = $_POST['_wpnonce'];
			$checknonce = wp_verify_nonce( $nonce, 'quicksave_css_' . $vthemename );
		}

		if ( $checknonce ) {
			// --- set style key for type ---
			if ( 'theme' == $type ) {
				$stylekey = 'dynamiccustomcss';
				$originalid = 'theme-styles-textarea-original';
				if ( current_user_can('edit_theme_options' ) || current_user_can( 'edit_css' ) ) {
					$newstyles = stripslashes( $_POST[$stylekey] );
					$vthemesettings['dynamiccustomcss'] = $newstyles;
					if ( THEMETITAN ) {
						$vthemesettings = serialize( $vthemesettings );
					}
					update_option( THEMEKEY, $vthemesettings );
				} else {
					$error = __( 'You do not have permission to change theme styles.', 'bioship' );
				}
			} elseif ( 'admin' == $type ) {
				// --- update admin styles ---
				$stylekey = 'dynamicadmincss';
				$originalid = 'theme-styles-textarea-original';
				if ( current_user_can( 'edit_theme_options' ) ) {
					$newstyles = stripslashes( $_POST[$stylekey] );
					$vthemesettings['dynamicadmincss'] = $newstyles;
					if ( THEMETITAN ) {
						$vthemesettings = serialize( $vthemesettings );
					}
					update_option( THEMEKEY, $vthemesettings );					
				} else {
					$error = __( 'You do not have permission to change admin styles.', 'bioship' );
				}
			} elseif ( 'post' == $type ) {
				// --- update perpost styles ---
				$stylekey = '_' . THEMEPREFIX . '_perpoststyles';
				$originalid = 'theme-styles-textarea-original-post';
				$postid = $_POST['postid'];
				if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_post', $postid ) ) {
					$newstyles = stripslashes( $_POST[$stylekey] );
					update_post_meta( $postid, $stylekey, $newstyles );
				} else {
					$error = __( 'You do not have permission to edit this post.', 'bioship' );
				}
			}
			
			// --- save current style editor box config ---
			$position = $_POST['theme-styles-position'];
			$positions = array( 'top', 'bottom', 'left', 'right' );
			if ( !in_array( $position, $positions ) ) {
				$position = 'top';
			}
			$width = absint( $_POST['theme-styles-width'] );
			if ( $width < 200 ) {
				$width = 200;
			}
			$height = absint($_POST['theme-styles-height']);
			if ( $height < 150 ) {
				$height = 150;
			}
			$box = array( 'position' => $position, 'width' => $width, 'height' => $height );
			$userid = get_current_user_id();
			update_user_meta( $userid, '_' . THEMEPREFIX . '_style_editor_box', $box );
			
		} else {
			$error = __( 'Whoops! Nonce has expired. Try reloading the page.', 'bioship' );
		}
	
	} else {
		$error = __( 'Failed. Looks like you may need to login again!', 'bioship' );
	}

	// --- output new styles textarea ---
	$args = array(
		'type' => 'text/css',
		'codemirror' => array(
			'indentUnit' => 2,
			'tabSize' => 2,
		),
	);
	$editor_settings = wp_enqueue_code_editor( $args );
	// 2.2.0: added missing esc_textarea on newstyles
	echo '<textarea id="new-styles">' . esc_textarea( $newstyles ) . '</textarea>';

	// --- script output and exit ---
	echo "<script>";
	if ( isset( $error ) ) {
		echo "alert('" . esc_js( $error ) . "');";
	} else {
		// --- show updated message in parent window ---
		echo "parent.bioship_quicksave_styles('" . esc_js( $type ) . "');" . PHP_EOL;

		// --- change original styles to new ---
		// (to resets the style modified flag) 
		echo "originalid = '" . esc_js( $originalid ) . "';
		newstyles = document.getElementById('new-styles').value;
		el = parent.document.getElementById(originalid);
		el.removeAttribute('readonly');
		el.value = newstyles;
		el.setAttribute('readonly','');" . PHP_EOL;

		if ( 'admin' != $type ) {
			// 2.2.0: reload current stylesheet in parent frame
			echo "id = '" . THEMESLUG . "-skin-css';
			el = parent.document.getElementById(id);
			if (el) {href = el.href; byid = true;}
			else {
				/* fix for PrefixFree link to style tag conversions */
				els = parent.document.getElementsByTagName('style');
				for (i = 0; i < els.length; i++ ) {
					data = els[i].getAttribute('data-href');
					if ( data != null ) {
						if ( (data.indexOf('skin.php') > -1) || (data.indexOf('skin_dynamic') > -1) ) {
							el = els[i]; href = data; byid = false;
						}
					}
				}
			}
			if (typeof href != 'undefined') {
				console.log('Reloading Style URL: '+href);
				pos = href.indexOf('ver=');
				newtime = (new Date()).getTime();
				newhref = href.substr(0, pos) + 'ver=' + newtime;
				if (byid) {el.href = newhref;}
				else {
					parentnode = el.parentNode;
					link = document.createElement('link');
					link.setAttribute('id', id);
					link.setAttribute('rel', 'stylesheet');
					link.setAttribute('type', 'text/css');
					link.setAttribute('media', 'all');
					link.setAttribute('href', newhref);
					parentnode.appendChild(link);
					parentnode.removeChild(el);
				}
			}";
		}
		
	}
	echo "</script>";

	exit;
 }
}

// --------------------------
// QuickSave PerPost CSS Form
// --------------------------
// 1.9.5: added this CSS quicksave form
if ( !function_exists( 'bioship_admin_quicksave_perpost_css_form' ) ) {
 function bioship_admin_quicksave_perpost_css_form() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- quicksave CSS scripts ---
	// 2.1.2: moved quicksave show fadeout to AJAX action
	echo "<script>function quicksavecss() {
		oldcss = document.getElementById('pageloadperpoststyles').value;
		newcss = document.getElementById('perpoststyles').value;
		if (oldcss == newcss) {return false;}
		document.getElementById('newperpoststyles').value = newcss;
		document.getElementById('quicksave-css-form').submit();
	}</script>";

	// --- get perpost styles ---
	global $post;
	$postid = $post->ID;
	// 2.0.8: use prefixed post meta key
	// 2.1.1: do not convert old values here
	$perpoststyles = get_post_meta( $postid, '_' . THEMEPREFIX . '_perpoststyles', true );

	// --- perpost styles form ---
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	$adminajax = admin_url( 'admin-ajax.php' );
	echo '<form action="' . esc_url( $adminajax ) . '" method="post" id="quicksave-css-form" target="quicksave-css-frame">';
	$nonce = wp_create_nonce( 'quicksave-perpost-css-' . $postid );
	echo '<input type="hidden" name="_wpnonce" id="quicksave-css-nonce" value="' . esc_attr( $nonce ) . '"';
	echo '<input type="hidden" name="action" value="quicksave_perpost_css">';
	echo '<input type="hidden" name="postid" value="' . esc_attr( $postid ) . '">';
	// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo '<input type="hidden" name="pageloadperpoststyles" id="pageloadperpoststyles" value="' . $perpoststyles . '">';
	echo '<input type="hidden" name="newperpoststyles" id="newperpoststyles" value=""></form>';

	// --- perpost styles saving iframe ---
	echo '<iframe src="javascript:void(0);" style="display:none;" name="quicksave-css-frame" id="quicksave-css-frame"></iframe>';
 }
}

// ---------------------
// QuickSave PerPost CSS
// ---------------------
// 1.9.5: added this CSS quicksave
if ( !function_exists( 'bioship_admin_quicksave_perpost_css' ) ) {

 add_action( 'wp_ajax_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css' );
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action( 'wp_ajax_nopriv_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css' );

 function bioship_admin_quicksave_perpost_css() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get edit post ID ---
	if ( !isset( $_POST['postid'] ) || !isset( $_POST['newperpoststyles'] ) ) {
		exit;
	}
	$postid = $_POST['postid'];
	if ( !is_numeric( $postid ) ) {
		exit;
	}

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if ( is_user_logged_in() ) {

		// --- check edit permissions ---
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_post', $postid ) ) {

			// --- check nonce ---
			// 2.0.0: use wp_verify_nonce instead of check_admin_referer for error message output
			$checknonce = false;
			if ( isset( $_POST['_wpnonce'] ) ) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce( $nonce, 'quicksave-perpost-css-' . $postid );
			}

			// --- update perpost styles ---
			if ( $checknonce ) {
				$newstyles = stripslashes( $_POST['newperpoststyles'] );
				// 2.1.1: use prefixed perpost styles metakey
				update_post_meta( $postid, '_' . THEMEPREFIX . '_perpoststyles', $newstyles );
			} else {
				$error = __( 'Whoops! Nonce has expired. Try reloading the page.','bioship' );
			}

			// --- update current tab to styles ---
			update_post_meta( $postid, '_' . THEMEPREFIX . '_themeoptionstab', 'styles' );

		} else {
			$error = __( 'Failed! You do not have permission to edit this post.', 'bioship' );
		}
	} else {
		// TODO: trigger showing of popup interim login thickbox ?
		$error = __( 'Failed. Looks like you may need to login again!', 'bioship' );
	}

	// --- script output and exit ---
	if ( isset( $error ) ) {
		echo "<script>alert('" . esc_js( $error ) . "');</script>";
	} else {
		echo "<script>parent.quicksavedsettings('css');</script>";
	}
	exit;
 }
}

// -------------------------------
// QuickSave PerPost Settings Form
// -------------------------------
// 2.0.0: dummy form copy to save all metabox theme option overrides (prototype)
if ( !function_exists( 'bioship_admin_quicksave_perpost_settings_form' ) ) {
 function bioship_admin_quicksave_perpost_settings_form() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- set perpost metabox settings keys ---
	$checkboxkeys = array(
		'display_wrapper', 'display_header', 'display_footer', 'display_navigation', 'display_secondarynav', 'display_headernav', 'display_footernav',
		'display_sidebar', 'display_subsidebar', 'display_headerwidgets', 'display_footerwidgets', 'display_footer1', 'display_footer2', 'display_footer3', 'display_footer4',
		'display_image', 'display_breadcrumb', 'display_title', 'display_subtitle', 'display_metatop', 'display_metabottom', 'display_authorbio', 'display_pagenavi',
		'wpautop', 'wptexturize', 'convertsmilies', 'convertchars' // filter keys
	);
	$selectkeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'thumbnailsize' // *
	);
	$textkeys = array( 'sidebarcustom', 'subsidebarcustom' );

	// --- output settings save script ---
	// 2.1.1: added tab for displaying message and saving current tab
	echo "<script>function quicksavesettings(tab) {
		checkboxkeys = new Array(); selectkeys = new Array(); textkeys = new Array(); ";

		// --- output settings keys ---
		// 2.2.0: removed duplicate internal counters
		// 2.2.0: moved internal to script for direct echoing
		foreach ( $checkboxkeys as $i => $key ) {
			echo "checkboxkeys[" . esc_js( $i ) . "] = '" . esc_js( $key ) . "'; ";
		}
		echo PHP_EOL;
		foreach ( $selectkeys as $j => $key ) {
			echo "selectkeys[" . esc_js( $j ) . "] = '" . esc_js( $key ) . "'; ";
		}
		echo PHP_EOL;
		foreach ( $textkeys as $k => $key ) {
			echo "textkeys[" . esc_js( $k ) . "] = '" . esc_js( $key ) . "'; ";
		}
		echo PHP_EOL;

		// --- copy settings to quicksave form ---
		echo "
		for (i in checkboxkeys) {
			if (document.getElementById('_'+checkboxkeys[i])) {
				if (document.getElementById('_'+checkboxkeys[i]).checked) {
					document.getElementById('__'+checkboxkeys[i]).value = '1';
				} else {document.getElementById('__'+checkboxkeys[i]).value = '';}
			} else {console.log('Warning! Missing Checkbox Setting Key: _'+checkboxkeys[i]);}
		}
		for (i in selectkeys) {
			if (document.getElementById('_'+selectkeys[i])) {
				selectelement = document.getElementById('_'+selectkeys[i]);
				selectedvalue = selectelement.options[selectelement.selectedIndex].value;
				document.getElementById('__'+selectkeys[i]).value = selectedvalue;
			} else {console.log('Warning! Missing Select Setting Key: _'+selectkeys[i]);}
		}
		for (i in textkeys) {
			if (document.getElementById('_'+textkeys[i])) {
				document.getElementById('__'+textkeys[i]).value = document.getElementById('_'+textkeys[i]).value;
			} else {console.log('Warning! Missing Text Setting Key: _'+textkeys[i]);}
		}";

		// --- submit quicksave form ---
		echo "
		document.getElementById('quicksave-options-tab').value = tab;
		document.getElementById('quicksave-settings-form').submit();
	}
	function quicksavedsettings(tab) {
		quicksaved = document.getElementById('quicksavedsettings-'+tab); quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000);}, 5000);
	}</script>";

	// --- quicksave settings form ---
	// 2.1.1: added buttonid and tab fields
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	global $post;
	$postid = $post->ID;
	$adminajax = admin_url( 'admin-ajax.php' );
	// 2.2.1: fix to ensure save still works when theme test driving
	if ( isset( $_GET['theme'] ) ) {
		$adminajax = add_query_arg( 'theme', sanitize_text_field( $_GET['theme'] ), $adminajax );
	}
	echo '<form action="' . esc_url( $adminajax ) . '" method="post" id="quicksave-settings-form" target="quicksave-settings-frame">';
	$nonce = wp_create_nonce( 'quicksave-perpost-settings-' . $postid );
	echo '<input type="hidden" name="_wpnonce" id="quicksave-settings-nonce" value="' . esc_attr( $nonce ) . '">';
	echo '<input type="hidden" name="action" value="quicksave_perpost_settings">';
	echo '<input type="hidden" name="tab" id="quicksave-options-tab" value="">';
	echo '<input type="hidden" name="postid" value="' . esc_attr( $postid ) . '">';
	foreach ( $checkboxkeys as $key ) {
		echo '<input type="hidden" name="_' . esc_attr( $key ) . '" id="__' . esc_attr( $key ) . '" value="">';
	}
	foreach ( $selectkeys as $key ) {
		echo '<input type="hidden" name="_' . esc_attr( $key ) . '" id="__' . esc_attr( $key ) . '" value="">';
	}
	foreach ( $textkeys as $key ) {
		echo '<input type="hidden" name="_' . esc_attr( $key ) . '" id="__' . esc_attr( $key ) . '" value="">';
	}
	echo '</form>';

	// --- quicksave settings iframe ---
	echo '<iframe src="javascript:void(0);" style="display:none;" name="quicksave-settings-frame" id="quicksave-settings-frame"></iframe>';
 }
}

// --------------------------
// QuickSave PerPost Settings
// --------------------------
// 2.0.0: save theme overrides via AJAX trigger (prototype)
if ( !function_exists( 'bioship_admin_update_metabox_settings' ) ) {

 add_action( 'wp_ajax_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings' );
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action( 'wp_ajax_nopriv_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings' );

 function bioship_admin_update_metabox_settings() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check trigger conditions
 	if ( !isset( $_REQUEST['postid'] ) || !is_numeric( $_REQUEST['postid'] ) ) {
 		exit;
 	}
 	$postid = $_REQUEST['postid'];
 	$error = false;

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if ( is_user_logged_in() ) {

		// --- check permissions ---
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_post', $postid ) ) {

			// --- check nonce ---
			$checknonce = false;
			if ( isset( $_POST['_wpnonce'] ) ) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce( $nonce, 'quicksave-perpost-settings-' . $postid );
			}
			if ( $checknonce ) {
				global $post;
				$post = get_post( $postid );
				bioship_admin_update_metabox_options();
			} else {
				$error = __( 'Whoops! Nonce has expired. Try reloading the page.', 'bioship' );
			}

			// --- update current options tab
			// 2.1.1: added saving of current tab
			$tab = $_POST['tab'];
			update_post_meta( $postid, '_' . THEMEPREFIX . '_themeoptionstab', $tab );

		} else {
			$error = __( 'Failed! You do not have permission to edit this post.', 'bioship' );
		}
	} else {
		// TODO: trigger showing of popup interim login thickbox ?
		$error = __( 'Failed! Looks like you may need to login again!', 'bioship' );
	}

	// --- output script and exit ---
	// 2.1.1: added tab argument for saved message display
	if ( $error ) {
		echo "<script>alert('" . esc_js( $error ) . "');</script>";
	} else {
		echo "<script>parent.quicksavedsettings('" . esc_attr( $tab ) . "');</script>";
	}
	exit;
 }
}

// --------------------------------
// QuickSave Cyclic Nonce Refresher
// --------------------------------
if ( !function_exists( 'bioship_admin_quicksave_nonce_refresher' ) ) {
 function bioship_admin_quicksave_nonce_refresher() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// --- output cyclic nonce refresh script ---
 	global $post;
 	$postid = $post->ID;
	$adminajax = admin_url( 'admin-ajax.php' );
	echo "<script>jQuery(document).ready(function() {
		setTimeout(function() {
			document.getElementById('quicksave-doing-refresh').value = 'yes';
			document.getElementById('quicksave-refresh-iframe').src = '" . esc_url( $adminajax ) . "?action=quicksave_update_nonces&postid=" . esc_js( $postid ) . "';
		}, 300000);
	});</script>";

	// --- hidden doing refresh input ---
	// 2.1.1: added input to prevent possible multiple alerts
	echo '<input type="hidden" id="quicksave-doing-refresh" value="">';

	// --- quicksave nonce refresh iframe ---
	echo '<iframe src="javascript:void(0);" id="quicksave-refresh-iframe" style="display:none;"></iframe>';
 }
}

// ----------------------------
// AJAX Update Quicksave Nonces
// ----------------------------
if ( !function_exists( 'bioship_admin_update_quicksave_nonces' ) ) {

 add_action( 'wp_ajax_quicksave_update_nonces', 'bioship_admin_update_quicksave_nonces' );
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action( 'wp_ajax_nopriv_update_nonces', 'bioship_admin_update_quicksave_nonces' );

 function bioship_admin_update_quicksave_nonces() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get post ID ---
	$postid = $_REQUEST['postid'];
 	if ( !isset( $_REQUEST['postid'] ) || !is_numeric( $_REQUEST['postid']) ) {
 		exit;
 	}
 	$postid = $_REQUEST['postid'];

	// --- session timeout message ---
	// 2.1.1: added alert message to inform of session timeout
	// TODO: trigger showing of popup interim login thickbox ?
	if ( !is_user_logged_in() ) {
		$message = __('Your session has timed out. Please Login again to continue editing.','bioship');
		echo "<script>alert('" . esc_js( $message ) . "');</script>";
		exit;
	}

	// --- check edit permissions ---
	if ( !current_user_can( 'edit_posts' ) || !current_user_can( 'edit_post', $postid ) ) {
		exit;
	}

	// --- create new nonces ---
	$settingsnonce = wp_create_nonce( 'quicksave-perpost-settings-' . $postid );
	$cssnonce = wp_create_nonce( 'quicksave-perpost-css-' . $postid );

	// --- send new nonces back to parent window ---
	// 2.1.1: reset doing nonce refresh flag on refresh
	echo "<script>parent.document.getElementById('quicksave-doing-refresh').value = '';
	parent.document.getElementById('quicksave-settings-nonce').value = '" . esc_js( $settingsnonce ) . "';
	parent.document.getElementById('quicksave-css-nonce').value = '" . esc_js( $cssnonce ) . "';</script>";
	exit;
 }
}
