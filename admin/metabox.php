<?php

// ==============================
// === BioShip Editor Metabox ===
// ==============================

// --- no direct load ---
if (!defined('ABSPATH')) {exit;}

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
if (!function_exists('bioship_admin_add_theme_metabox')) {

 add_action('admin_init', 'bioship_admin_add_theme_metabox');

 function bioship_admin_add_theme_metabox() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get custom post types ----
	// TODO: add multicheck option for Theme Options Metabox on CPTs
	$cpts = array('post', 'page');
	$args = array('public' => true, '_builtin' => false);
	$cptlist = get_post_types($args, 'names', 'and');
	$cpts = array_merge($cpts, $cptlist);
	// 2.0.5: add filter for post types metabox
	$cpts = bioship_apply_filters('admin_theme_metabox_post_types', $cpts);

	// --- metabox position ---
	// 2.1.1: added filter for metabox priority position
	$priority = bioship_apply_filters('admin_theme_metabox_priority', 'high');

	// --- add metaboxes ---
	foreach ($cpts as $cpt) {
		add_meta_box('theme_metabox', __('Theme Display Overrides','bioship'), 'bioship_admin_theme_metabox', $cpt, 'side', $priority);
	}
 }
}

// ----------------------------
// Editor Theme Options Metabox
// ----------------------------
// 1.8.0: renamed from muscle_theme_metabox
// 2.0.0: added missing translation wrappers
if (!function_exists('bioship_admin_theme_metabox')) {
 function bioship_admin_theme_metabox() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemesettings;

	// --- get post data ---
	// 2.1.1: handle new post (no post ID)
	global $post;
	if (is_object($post)) {$postid = $post->ID; $posttype = $post->post_type;}
	else {
		$postid = ''; $posttype = 'post';
		if (isset($_REQUEST['post_type'])) {$posttype = $_REQUEST['post_type'];}
	}

	// --- get current override values ---
	$display = bioship_muscle_get_display_overrides($postid);
	$override = bioship_muscle_get_templating_overrides($postid);
	$removefilters = bioship_muscle_get_content_filter_overrides($postid);

	if (THEMEDEBUG) {
		bioship_debug("Post ID", $postid);
		bioship_debug("Display Overrides", $display);
		bioship_debug("Templating Overrides", $override);
		bioship_debug("Filter Overrides", $removefilters);
	}

	// --- option tab script ---
	echo "<script>
	function clickthemeoptions(themeoption) {
		if (document.getElementById('themetabclicked').value == 'mouseover') {var mouseover = true;}
		if ( (document.getElementById('theme'+themeoption).style.display == 'none') || (mouseover == true) ) {
			document.getElementById('themeoptionstab').value = themeoption;
			document.getElementById('themetabclicked').value = 'clicked';
			showthemeoptions(themeoption);
		} else {
			document.getElementById('themeoptionstab').value = '';
			document.getElementById('themetabclicked').value = '';
			hidethemeoptions(themeoption);
		}
	}
	function maybeshowthemeoptions(themeoption) {
		if (document.getElementById('themetabclicked').value == 'clicked') {return;}
		document.getElementById('themetabclicked').value = 'mouseover';
		showthemeoptions(themeoption);
	}
	function showthemeoptions(themeoption) {
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
	function hidethemeoptions(themeoption) {
		document.getElementById('theme'+themeoption).style.display = 'none';
		document.getElementById('theme'+themeoption+'button').style.backgroundColor = '#EEE';
	}
	function checkcustomtemplates() {
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
	$settingstab = ''; // empty default
	if ($postid != '') {$tab = get_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', true);}
	if ($tab) {$settingstab = $tab;}

	// --- tab button styles ---
	// 2.1.1: added a tag text decoration style
	echo "<style>.themeoptionbutton {background-color:#E0E0EF; padding:5px; border-radius:5px;}
	.themeoptionbutton a {text-decoration:none;}</style>";

	// --- theme options tab buttons ---
	// 2.1.0: removed filters tab cell/button remnant
	// TODO: maybe convert a tags to input buttons ?
	echo "<center><table><tr>";

		// --- content tab button ---
		if ($settingstab == 'content') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td><div id='themecontentbutton' class='themeoptionbutton'".$bgcolor.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"content\");' onclick='clickthemeoptions(\"content\");'>";
			echo esc_attr(__('Content','bioship'))."</a>";
		echo "</div></td>";

		// --- sidebar tab button ---
		if ($settingstab == 'sidebar') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td width='10'></td><td><div id='themesidebarbutton' class='themeoptionbutton'".$bgcolor.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"sidebar\");' onclick='clickthemeoptions(\"sidebar\");'>";
			echo esc_attr(__('Sidebars','bioship'))."</a>";
		echo "</div></td>";

		// --- layout tab button ---
		if ($settingstab == 'layout') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td width='10'></td><td><div id='themelayoutbutton' class='themeoptionbutton' class='themeoptionbutton'".$bgcolor.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"layout\");' onclick='clickthemeoptions(\"layout\");'>";
			echo esc_attr(__('Layout','bioship'))."</a>";
		echo "</div></td>";

		// --- styles tab button ---
		// 2.1.4: fix to echo instead of setting variable
		if ($settingstab == 'styles') {$bgcolor = " style='background-color:#DDD;'";} else {$bgcolor = '';}
		echo "<td width='10'></td><td><div id='themestylesbutton' class='themeoptionbutton'".$bgcolor.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo "<a href='javascript:void(0);' onmouseover='maybeshowthemeoptions(\"styles\");' onclick='clickthemeoptions(\"styles\");'>";
			echo esc_attr(__('Styles','bioship'))."</a>";
		echo "</div></td>";

	echo "</tr></table>";


	// Content Override Tab
	// --------------------
	if ($settingstab != 'content') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themecontent'".$hide.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "<table cellpadding='0' cellspacing='0'>";

		// Thumbnail Size Override
		// -----------------------

		// --- setup available thumbnail sizes ---
		if ($posttype == 'page') {$thumbdisplay = esc_attr(__('Featured Image','bioship')); $thumbdefault = $vthemesettings['pagethumbsize'];}
		else {$thumbdisplay = esc_attr(__('Thumbnail','bioship')); $thumbdefault = $vthemesettings['postthumbsize'];}
		$thumbarray = array(
			'thumbnail' => esc_attr(__('Thumbnail','bioship')).' ('.get_option('thumbnail_size_w').' x '.get_option('thumbnail_size_h').')',
			'medium' => esc_attr(__('Medium','bioship')).' ('.get_option('medium_size_w').' x '.get_option('medium_size_h').')',
			'large' => esc_attr(__('Large','bioship')).' ('.get_option('large_size_w').' x '.get_option('large_size_h').')',
			'full' => esc_attr(__('Full Size','bioship')).' ('.esc_attr(__('original','bioship')).')'
		);

		// --- get additional image sizes ---
		global $_wp_additional_image_sizes;
		$image_sizes = get_intermediate_image_sizes();
		$oldsizenames = array('squared150', 'squared250', 'video43', 'video169');
		foreach ($image_sizes as $size_name) {
			if ( ($size_name != 'thumbnail') && ($size_name != 'medium') && ($size_name != 'large') ) {
				// 1.9.8: fix to sporadic undefined index warning (huh? size names should match?)
				if (isset($_wp_additional_image_sizes[$size_name])) {
					// 2.0.5: no longer output old size names as options
					if (!in_array($size_name, $oldsizenames)) {
						$thumbarray[$size_name] = $size_name.' ('.$_wp_additional_image_sizes[$size_name]['width'].' x '.$_wp_additional_image_sizes[$size_name]['height'].')';
					}
				}
			}
		}

		// --- get thumbnail size override ---
		// 1.8.0: keep individual meta key for this
		// 2.1.1: added theme prefix to thumbnail size metakey
		$thumbnailsize = '';
		if ($postid != '') {$thumbnailsize = get_post_meta($postid, '_'.THEMEPREFIX.'_thumbnailsize', true);}

		// --- maybe convert old thumbnail size names ---
		// 2.0.5: maybe convert to prefixed names and update meta
		$newthumbsize = false;
		if ($thumbnailsize == 'squared150') {$newthumbsize = 'bioship-150s';}
		elseif ($thumbnailsize == 'squared250') {$newthumbsize = 'bioship-250s';}
		elseif ($thumbnailsize == 'video43') {$newthumbsize = 'bioship-4-3';}
		elseif ($thumbnailsize == 'video169') {$newthumbsize = 'bioship-16-9';}
		elseif ($thumbnailsize == 'opengraph') {$newthumbsize = 'bioship-opengraph';}
		if ($newthumbsize) {
			update_post_meta($postid, '_'.THEMEPREFIX.'_thumbnailsize', $newthumbsize);
			$thumbnailsize = $newthumbsize;
		}

		// --- thumbnail size override selector ---
		// 2.0.7: fix to text domin typo (bioship.)
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td colspan='3' align='center'>";
			echo "<b>".$thumbdisplay." "; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
			echo esc_attr(__('Size','bioship'))."</b> (".esc_attr(__('default','bioship'))." ".esc_attr($thumbdefault).")<br>";
			echo "<select name='_thumbnailsize' id='_thumbnailsize' style='font-size:9pt;'>";
				if ($thumbnailsize == '') {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value=''".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr(__('Theme Settings Default','bioship'))."</option>";
				// 2.1.1: fix to missing option value for no thumbnail
				if ($thumbnailsize == 'off') {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='off'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr(__('No Thumbail Output','bioship'))."</option>";
				foreach ($thumbarray as $key => $value) {
					if ($thumbnailsize == $key) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".esc_attr($key)."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
					echo esc_attr($value)."</option>";
				}
			echo "</select>";
		echo "</td></tr>";

		// --- hide thumbnail ---
		// 2.1.1.: added missing id for checkbox field
		echo "<tr><td>".__('Hide','bioship')." ".$thumbdisplay."</td><td width='10'></td><td align='center'>";
			if ($display['image'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_image' id='_display_image' value='1'".$checked.">";
		echo "</td></tr>";

		// Content Display Overrides
		// -------------------------

		// --- content override headings ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Content Display','bioship'))."</b></td><td width='10'></td>";
		echo "<td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- content title ---
		echo "<tr><td>".esc_attr(__('Title','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['title'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_title' id='_display_title' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// ---- content subtitle ---
		echo "<tr><td>".esc_attr(__('Subtitle','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['subtitle'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_subtitle' id='_display_subtitle' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- content meta top ---
		echo "<tr><td>".esc_attr(__('Top Meta','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['metatop'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_metatop' id='_display_metatop' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- content meta bottom ---
		echo "<tr><td>".esc_attr(__('Bottom Meta','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['metabottom'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_metabottom' id='_display_metabottom' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- author bio box ---
		echo "<tr><td>".esc_attr(__('Author Bio','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['authorbio'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_authorbio' id='_display_authorbio' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";


		// Filter Overrides
		// ----------------
		// 1.9.5: merged to content tab from separate filters tab

		// --- content filters heading ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Content Filter','bioship'))."</b></td><td></td>";
		echo "<td align='center'>".esc_attr(__('Disable','bioship'))."</td></tr>";

		// --- wpautop filter ---
		echo "<tr><td>wpautop</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['wpautop']) && ($removefilters['wpautop'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_wpautop' id='_wpautop' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- wptexturize filter ---
		echo "<tr><td>wptexturize</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['wptexturize']) && ($removefilters['wptexturize'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_wptexturize' id='_wptexturize' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- convert_smilies filter ---
		echo "<tr><td>convert_smilies</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['convertsmilies']) && ($removefilters['convertsmilies'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_convertsmilies' id='_convertsmilies' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- convert_chars filter ---
		echo "<tr><td>convert_chars</td><td width='10'></td><td align='center'>";
			if (isset($removefilters['convertchars']) && ($removefilters['convertchars'] == '1') ) {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_convertchars' id='_convertchars' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-content'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"content\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close content tab ---
	echo "</table></div>";


	// Sidebar Overrides
	// -----------------
	// TODO: add display of total column width ?
	// 1.9.5: separate tab for sidebar overrides
	if ($settingstab != 'sidebar') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themesidebar'".$hide.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "<table cellpadding='0' cellspacing='0'>";

		// -- set column options ---
		$subsidebarcolumns = array(
			'' => esc_attr(__('Default','bioship')),
			'one' => ' '.__('1','bioship').' ', 'two' => ' '.__('2','bioship').' ',
			'three' => ' '.__('3','bioship').' ', 'four' => ' '.__('4','bioship').' ',
			'five' => ' '.__('5','bioship').' ', 'six' => ' '.__('6','bioship').' ',
			'seven' => ' '.__('7','bioship').' ', 'eight' => ' '.__('8','bioship').' ',
		);
		$sidebarcolumns = array_merge($subsidebarcolumns, array(
			'nine'	=> ' '.__('9','bioship').' ', 'ten' => __('10','bioship').' ',
			'eleven' => __('11','bioship').' ', 'twelve' => __('12','bioship').' ',
		) );
		$contentcolumns = array_merge($sidebarcolumns, array(
			'thirteen' => __('13','bioship').' ', 'fourteen' => __('14','bioship').' ',
			'fifteen' => __('15','bioship').' ', 'sixteen' => __('16','bioship').' ',
			'seventeen' => __('17','bioship').' ', 'eighteen' => __('18','bioship').' ',
			'nineteen' => __('19','bioship').' ', 'twenty' => __('20','bioship').' ',
			'twentyone' => __('21','bioship').' ', 'twentytwo' => __('22','bioship').' ',
			'twentythree' => __('23','bioship').' ', 'twentyfour' => __('24','bioship').' '
		) );

		// --- content columns ---
		echo "<tr><td colspan='5' align='center'>";
			echo "<table><tr><td>".esc_attr(__('Content Columns','bioship'))."</td>";
				echo "<td width='10'></td><td>";
				echo "<select name='_contentcolumns' id='_contentcolumns'>";
				foreach ($contentcolumns as $width => $label) {
					if ($override['contentcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".$width."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
					echo esc_attr($label)."</option>";
				}
				echo "</select>";
			echo "</td></tr></table>";
		echo "</td></tr>";

		// --- column headings ---
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td></td><td></td><td align='center'><b>".esc_attr(__('Sidebar','bioship'))."</b></td><td></td>";
		echo "<td align='center'><b>".esc_attr(__('SubSidebar','bioship'))."</b></td></tr>";
		echo "<tr><td align='right'>".esc_attr(__('Columns','bioship'))."</td><td width='5'></td>";

		// --- sidebar columns ---
		echo "<td>";
			echo "<select name='_sidebarcolumns' id='_sidebarcolumns' style='width:100%;font-size:9pt;'>";
			foreach ($sidebarcolumns as $width => $label) {
				if ($override['sidebarcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".$width."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td><td width='5'></td>";

		// --- subsidebar columns ---
		echo "<td>";
			echo "<select name='_subsidebarcolumns' id='_subsidebarcolumns' style='width:100%;font-size:9pt;'>";
			foreach ($subsidebarcolumns as $width => $label) {
				if ($override['subsidebarcolumns'] == $width) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".$width."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td></tr>";

		// Sidebar Templates
		// -----------------
		$sidebartemplates = array(
			''			=> esc_attr(__('Default','bioship')),
			'off'		=> esc_attr(__('None','bioship')),
			'blank'		=> esc_attr(__('Blank','bioship')),
			'primary'	=> esc_attr(__('Primary','bioship'))
		);
		$subsidebartemplates = array(
			''				=> esc_attr(__('Default','bioship')),
			'off'			=> esc_attr(__('None','bioship')),
			'subblank'		=> esc_attr(__('Blank','bioship')),
			'subsidiary'	=> esc_attr(__('Subsidiary','bioship'))
		);

		// TODO: use the new sidebar template search function here ?
		// (bioship_get_sidebar_templates_info in skull.php) ?
		$templates = array(
			'page'		=> esc_attr(__('Page','bioship')),
			'post'		=> esc_attr(__('Post','bioship')),
			'front'		=> esc_attr(__('Front','bioship')),
			'home'		=> esc_attr(__('Home','bioship')),
			'archive'	=> esc_attr(__('Archive','bioship')),
			'category'	=> esc_attr(__('Category','bioship')),
			'taxonomy'	=> esc_attr(__('Taxonomy','bioship')),
			'tag'		=> esc_attr(__('Tag','bioship')),
			'author'	=> esc_attr(__('Author','bioship')),
			'date'		=> esc_attr(__('Date','bioship')),
			'search'	=> esc_attr(__('Search','bioship')),
			'notfound'	=> esc_attr(__('NotFound','bioship'))
		);
		$sidebartemplates = array_merge($sidebartemplates, $templates);
		foreach ($templates as $key => $label) {$subsidebartemplates['sub'.$key] = $label;}
		$sidebartemplates['custom'] = $subsidebartemplates['custom'] = esc_attr(__('Custom','bioship'));

		// --- sidebar template headings ---
		// 2.1.1: added missing translation wrappers
		echo "<tr><td style='vertical=align:top;' align='right'>";
			echo esc_attr(__('Template','bioship'))."<br>";
			if ( ($override['sidebartemplate'] != 'custom') && ($override['subsidebartemplate'] != 'custom') ) {$hide = "display:none;";} else {$hide = '';}
			echo "<div id='customtemplatelabel' style='margin-top:10px;".$hide."'>".esc_attr(__('Slug','bioship')).":</div>";
		echo "</td><td width='5'></td>";

		// --- sidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo "<td style='vertical-align:top;'>";
			echo "<select name='_sidebartemplate' id='_sidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
				foreach ($sidebartemplates as $template => $label) {
					if ($override['sidebartemplate'] == $template) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".esc_attr($template)."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
					echo esc_attr($label)."</option>";
				}
			echo "</select><br>";

			// --- custom sidebar template ---
			if ($override['sidebartemplate'] != 'custom') {$hide = " style='display:none;'";} else {$hide = '';}
			echo "<div id='sidebarcustom'".$hide.">";
				// 2.1.1: added missing id attribute for input
				echo "<input type='text' name='_sidebarcustom' id='_sidebarcustom' style='width:80px;font-size:9pt;' value='".esc_attr($override['sidebarcustom'])."'>";
			echo "</div>";
		echo "</td><td width='5'></td>";

		// --- subsidebar template ---
		// 2.1.1: remove duplicate id attribute from select
		echo "<td style='vertical-align:top;'>";
			echo "<select name='_subsidebartemplate' id='_subsidebartemplate' style='width:100%;font-size:9pt;' onchange='checkcustomtemplates();'>";
				foreach ($subsidebartemplates as $template => $label) {
					if ($override['subsidebartemplate'] == $template) {$selected = " selected='selected'";} else {$selected = '';}
					echo "<option value='".esc_attr($template)."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
					echo esc_attr($label)."</option>";
				}
			echo "</select><br>";

			// --- custom subsidebar template ---
			if ($override['subsidebartemplate'] != 'custom') {$hide = " style='display:none;'";} else {$hide = '';}
			echo "<div id='subsidebarcustom'".$hide.">";
				// 2.1.1: added missing id attribute for input
				echo "<input type='text' name='_subsidebarcustom' id='_subsidebarcustom' style='width:80px;font-size:9pt;' value='".esc_attr($override['subsidebarcustom'])."'>";
			echo "</div>";
		echo "</td></tr>";

		// --- main sidebar position ---
		$sidebarpositions = array(
			''		=> esc_attr(__('Default','bioship')),
			'left'	=> esc_attr(__('Left','bioship')),
			'right'	=> esc_attr(__('Right','bioship'))
		);
		echo "<tr><td align='right'>";
			// 2.1.1: added missing translation wrapper
			echo esc_attr(__('Position','bioship'));
		echo "</td><td width='5'></td><td>";
			echo "<select name='_sidebarposition' id='_sidebarposition' style='width:100%;font-size:9pt;'>";
			foreach ($sidebarpositions as $position => $label) {
				if ($override['sidebarposition'] == $position) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".esc_attr($position)."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td><td width='5'></td>";

		// --- subsidebar position ---
		$subsidebarpositions = array(
			''			=> esc_attr(__('Default','bioship')),
			'opposite'	=> esc_attr(__('Opposite','bioship')),
			'internal'	=> esc_attr(__('Internal','bioship')),
			'external'	=> esc_attr(__('External','bioship'))
		);
		echo "<td>";
			// 2.1.1: added missing id field for subsidebar position
			echo "<select name='_subsidebarposition' id='_subsidebarposition' style='width:100%;font-size:9pt;'>";
			foreach ($subsidebarpositions as $position => $label) {
				if ($override['subsidebarposition'] == $position) {$selected = " selected='selected'";} else {$selected = '';}
				echo "<option value='".esc_attr($position)."'".$selected.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
				echo esc_attr($label)."</option>";
			}
			echo "</select>";
		echo "</td></tr>";
		echo "</table>";

		// --- sidebar display headings ---
		echo "<table cellpadding='0' cellspacing='0'><tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Sidebar Display','bioship'))."</b></td>";
		echo "<td></td><td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- main sidebar hide ---
		echo "<tr><td>".esc_attr(__('Main Sidebar','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['sidebar'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_sidebar' id='_display_sidebar' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- subsidebar hide ---
		echo "<tr><td>".esc_attr(__('SubSidebar','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['subsidebar'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_subsidebar' id='_display_subsidebar' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- header widgets hide ---
		echo "<tr><td>".esc_attr(__('Header Widgets','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['headerwidgets'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_headerwidgets' id='_display_headerwidgets' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer widgets hide ---
		echo "<tr><td>".esc_attr(__('Footer Widgets','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footerwidgets'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footerwidgets' id='_display_footerwidgets' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer widget area 1 ---
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 1</td><td width='10'></td><td align='center'>";
			if ($display['footer1'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer1' id='_display_footer1' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer widget area 2 ---
		echo "<tr><td>".__('Footer Area','bioship')." 2</td><td width='10'></td><td align='center'>";
			if ($display['footer2'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer2' id='_display_footer2' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer widget area 3 ---
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 3</td><td width='10'></td><td align='center'>";
			if ($display['footer3'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer3' id='_display_footer3' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer widget area 4 ----
		echo "<tr><td>".esc_attr(__('Footer Area','bioship'))." 4</td><td width='10'></td><td align='center'>";
			if ($display['footer4'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer4' id='_display_footer4' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-sidebar'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"sidebar\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close sidebar overrides tab ---
	echo "</table></div>";


	// Layout Overrides
	// ----------------
	if ($settingstab != 'layout') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themelayout'".$hide.">";
	echo "<table cellpadding='0' cellspacing='0'>";

		// --- layout overrides heading ---
		echo "<tr><td colspan='3' align='center'>";
			echo "<b>".esc_attr(__('Layout Display Overrides','bioship'))."</b>";
		echo "</td></tr>";

		// --- no wrap margins (full width) ---
		// 1.8.5: added full width container option (no wrap margins)
		echo "<tr><td>".esc_attr(__('No Wrap Margins','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['wrapper'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_wrapper' id='_display_wrapper' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- hide header ---
		echo "<tr><td>".esc_attr(__('Hide Header','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['header'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_header' id='_display_header' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- hide footer ---
		echo "<tr><td>".esc_attr(__('Hide Footer','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footer'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footer' id='_display_footer' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// TODO: general layout displays?
		// Header Logo / Title Text / Description / Extras
		// Footer Extras / Site Credits

		// --- navigation display headings ---
		// 1.9.8: fix to headernav and footernav keys
		echo "<tr height='10'><td> </td></tr>";
		echo "<tr><td align='center'><b>".esc_attr(__('Navigation Display','bioship'))."<b></td>";
		echo "<td></td><td align='center'>".esc_attr(__('Hide','bioship'))."</td></tr>";

		// --- main navigation menu ---
		echo "<tr><td>".esc_attr(__('Main Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['navigation'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_navigation' id='_display_navigation' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- secondary navigation ---
		echo "<tr><td>".esc_attr(__('Secondary Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['secondarynav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_secondarynav' id='_display_secondarynav' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- header navigation menu ---
		echo "<tr><td>".esc_attr(__('Header Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['headernav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_headernav' id='_display_headernav' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- footer navigation menu ---
		echo "<tr><td>".esc_attr(__('Footer Nav Menu','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['footernav'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_footernav' id='_display_footernav' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- breadcrumbs ---
		echo "<tr><td>".esc_attr(__('Breadcrumbs','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['breadcrumb'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_breadcrumb' id='_display_breadcrumb' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- page navi ---
		echo "<tr><td>".esc_attr(__('Post/Page Navi','bioship'))."</td><td width='10'></td><td align='center'>";
			if ($display['pagenavi'] == '1') {$checked = ' checked';} else {$checked = '';}
			echo "<input type='checkbox' name='_display_pagenavi' id='_display_pagenavi' value='1'".$checked.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</td></tr>";

		// --- quicksave settings button ---
		echo "<tr height='5'><td> </td></tr><tr><td align='center'>";
			echo "<div class='quicksavesettings' id='quicksavesettings-layout'>".esc_attr(__('Saved!','bioship'))."</div>";
		echo "</td><td width='10'></td><td align='right'>";
			echo "<input type='button' onclick='quicksavesettings(\"layout\");' value='".esc_attr(__('Save Overrides','bioship'))."' class='button-secondary'>";
		echo "</td></tr>";

	// --- close layout override tab ---
	echo "</table></div>";


	// Style Overrides
	// ---------------
	// 1.8.0: javascript to expand/collapse style box
	// 2.1.1: added marginTop to help prevent editor overlay
	echo "<script>function expandpostcss() {
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
	function collapsepostcss() {
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
	echo "<style>#quicksavedcss, .quicksavesettings {display:none; padding:3px 6px; max-width:80px; ";
	echo "font-size:10pt; color: #333; font-weight:bold; background-color: lightYellow; border: 1px solid #E6DB55;}</style>";

	// --- get per post styles ---
	// 1.8.0: keep individual meta key for this
	// 2.1.1: added theme prefix to post metakey
	$perpoststyles = '';
	if ($postid != '') {$perpoststyles = get_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', true);}

	// --- per post styles tab ---
	if ($settingstab != 'styles') {$hide = " style='display:none;'";} else {$hide = '';}
	echo "<div id='themestyles'".$hide.">"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "<table cellpadding='0' cellspacing='0' style='width:100%;overflow:visible;'>";

		// --- style textarea ---
		echo "<tr><td colspan='2' align='center'><b>".esc_attr(__('Post Specific CSS Style Rules','bioship'))."</b></td></tr>";
		echo "<tr><td><div id='expandpostcss' style='float:left; margin-left:10px;'><a href='javascript:void(0);' onclick='expandpostcss();' style='text-decoration:none;'>&larr; ".esc_attr(__('Expand','bioship'))."</a></div>";
		echo "<div id='collapsepostcss' style='float:right; margin-right:20px; display:none;'><a href='javascript:void(0);' onclick='collapsepostcss();' style='text-decoration:none;'>".esc_attr(__('Collapse','bioship'))." &rarr;</a></div></tr>";
		echo "<tr><td colspan='2'><div id='perpoststylebox' style='background:#FFF;'>";
		echo "<textarea rows='5' cols'30' name='_perpoststyles' id='perpoststyles' style='width:100%;height:200px;'>";
			echo $perpoststyles; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo "</textarea></div></td></tr>";

		// --- quicksave CSS button ---
		echo "<tr><td align='center'><div id='quicksavedcss'>".esc_attr(__('CSS Saved!','bioship'))."</div></td>";
		echo "<td align='right'><input type='button' onclick='quicksavecss();' value='".esc_attr(__('QuickSave CSS','bioship'))."' class='button-secondary'></td></tr>";

	// --- close style override tab ---
	echo "</table></div>";

	// --- end tabs output ---
	echo "</center>";

	// --- theme options current tab saver ---
	echo "<input type='hidden' id='themeoptionstab' name='_themeoptionstab' value='".esc_attr($settingstab)."'>";
	echo "<input type='hidden' id='themetabclicked' name='_themetabclicked' value=''>";

	// --- enqueue quicksave forms ---
	// 1.9.5: added quicksave perpost CSS form to footer
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_css_form');
	// 2.0.0: added quicksave perpost settings form to footer (prototype)
	add_action('admin_footer', 'bioship_admin_quicksave_perpost_settings_form');
	// 2.1.1: added quicksave cyclic nonce refresher
	add_action('admin_footer', 'bioship_admin_quicksave_nonce_refresher');

 }
}

// ---------------------
// Update Metabox Values
// ---------------------
add_action('publish_post', 'bioship_admin_update_metabox_options');
add_action('save_post', 'bioship_admin_update_metabox_options');

// 1.8.0: renamed from muscle_update_metabox_options
if (!function_exists('bioship_admin_update_metabox_options')) {
 function bioship_admin_update_metabox_options() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check post values ---
	// 1.9.8: return if post is empty
	global $post; if (!is_object($post)) {return;}
	$postid = $post->ID;

	// --- check for autosave ---
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return;}

	// --- check user capabilities ---
	// 1.8.0: cleaner save logic here
	if (!current_user_can('edit_posts') || !current_user_can('edit_post', $postid)) {return $postid;}

	// --- save display overrides --
	// 1.8.0: grouped display overrides to array
	// 1.8.5: added headernav, footernav, breadcrumbs, pagenavi
	$display = array(); $postdata = false;
	$displaykeys = array(
		'wrapper', 'header', 'footer', 'navigation', 'secondarynav', 'headernav', 'footernav',
		'sidebar', 'subsidebar', 'headerwidgets', 'footerwidgets', 'footer1', 'footer2', 'footer3', 'footer4',
		'image', 'breadcrumb', 'title', 'subtitle', 'metatop', 'metabottom', 'authorbio', 'pagenavi'
	);
	// 1.9.5: changed _hide prefix to _display_
	foreach ($displaykeys as $key) {
		if (!isset($_POST['_display_'.$key])) {$display[$key] = '';}
		elseif ($_POST['_display_'.$key] == '1') {$display[$key] = '1'; $postdata = true;}
		else {$display[$key] = '';}
	}
	// 1.9.9: check and save only if new post data
	// 2.1.1: use prefixed metakey for saving
	// 2.1.1: set unique argument to true here
	delete_post_meta($postid, '_'.THEMEPREFIX.'_display_overrides');
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_display_overrides', $display, true);}

	// --- save layout overrides ---
	// 1.9.5: added override keys
	$override = array(); $postdata = false;
	$overridekeys = array(
		'contentcolumns', 'sidebarcolumns', 'subsidebarcolumns', 'sidebarposition', 'subsidebarposition',
		'sidebartemplate', 'subsidebartemplate', 'sidebarcustom', 'subsidebarcustom'
	);
	foreach ($overridekeys as $key) {
		if (!isset($_POST['_'.$key])) {$override[$key] = '';}
		else {$override[$key] = $_POST['_'.$key]; $postdata = true;}
	}
	delete_post_meta($postid, '_'.THEMEPREFIX.'_templating_overrides');
	// 1.9.9: check and save if new post data
	// 2.1.1: use prefixed metakey for saving
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_templating_overrides', $override);}

	// --- save filter overrides ---
	// 1.8.0: grouped filters to array
	// 2.0.0: better checkbox save logic
	$removefilters = array(); $postdata = false;
	$filters = array('wpautop', 'wptexturize', 'convertsmilies', 'convertchars');
	foreach ($filters as $filter) {
		if (!isset($_POST['_'.$filter])) {$removefilters[$filter] = '';}
		else {
			if ($_POST['_'.$filter] == '1') {$removefilters[$filter] = '1'; $postdata = true;}
			else {$removefilters[$filter] = '';}
		}
	}
	delete_post_meta($postid, '_'.THEMEPREFIX.'_removefilters');
	// 1.9.9: check and save if new filters
	// 2.0.0: save if post data found
	// 2.1.1: use prefixed metakey for saving
	if ($postdata) {add_post_meta($postid, '_'.THEMEPREFIX.'_removefilters', $removefilters, true);}

	// --- save individual options ---
	// 1.8.0: save individual key values
	$optionkeys = array('_perpoststyles', '_thumbnailsize', '_themeoptionstab');
	foreach ($optionkeys as $option) {
		// 1.9.9: make sure option value is actually set (as metabox may be removed)
		if (isset($_POST[$option])) {
			$optionvalue = $_POST[$option];
			if ($option == '_perpoststyles') {$optionvalue = stripslashes($optionvalue);}
			// 2.1.1: use prefixed metakey for saving
			$option = str_replace('_', '_'.THEMEPREFIX.'_', $option);
			delete_post_meta($postid, $option);
			// 1.9.5: to make cleaner, do not save empty values
			if (trim($optionvalue) != '') {add_post_meta($postid, $option, $optionvalue, true);}
			$options[$option] = $optionvalue;
		}
	}

	// --- for manual debug of per post options ---
	$metasavedebug = false; // $metasavedebug = true;
	if ($metasavedebug) {
		$debuginfo = PHP_EOL." Saved Post ".$postid." at ".date('j/m/d H:i:s', time()).PHP_EOL;
		$debuginfo .= "--- Override ---".PHP_EOL; foreach ($override as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		$debuginfo .= "--- Display ---".PHP_EOL; foreach ($display as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		$debuginfo .= "--- Filters ---".PHP_EOL; foreach ($removefilters as $key => $value) {$debuginfo .= $key.': '.$value.PHP_EOL;}
		// 2.1.2: fix for possible undefined variable warning
		if (isset($options)) {$debuginfo .= "--- Options ---".PHP_EOL; foreach ($options as $key => $value) {$debuginfo .= $key.': '.print_r($value,true).PHP_EOL;} }
		// $debuginfo .= "--- Posted ---".PHP_EOL; foreach ($posted as $key => $value) {$debuginfo .= $key.': '.print_r($value,true).PHP_EOL;}
		bioship_write_debug_file('perpost-debug-'.$postid.'.txt', $debuginfo);
	}
 }
}


// ------------------
// === QuickSaves ===
// ------------------

// --------------------------
// QuickSave PerPost CSS Form
// --------------------------
// 1.9.5: added this CSS quicksave form
if (!function_exists('bioship_admin_quicksave_perpost_css_form')) {
 function bioship_admin_quicksave_perpost_css_form() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

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
	global $post; $postid = $post->ID;
	// 2.0.8: use prefixed post meta key
	// 2.1.1: do not convert old values here
	$perpoststyles = get_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', true);

	// --- perpost styles form ---
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	$adminajax = admin_url('admin-ajax.php');
	echo "<form action='".esc_url($adminajax)."' method='post' id='quicksave-css-form' target='quicksave-css-frame'>";
	$nonce = wp_create_nonce('quicksave-perpost-css-'.$postid);
	echo "<input type='hidden' name='_wpnonce' id='quicksave-css-nonce' value='".esc_attr($nonce)."'>";
	echo "<input type='hidden' name='action' value='quicksave_perpost_css'>";
	echo "<input type='hidden' name='postid' value='".esc_attr($postid)."'>";
	echo "<input type='hidden' name='pageloadperpoststyles' id='pageloadperpoststyles' value='".$perpoststyles."'>"; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
	echo "<input type='hidden' name='newperpoststyles' id='newperpoststyles' value=''></form>";

	// --- perpost styles saving iframe ---
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-css-frame' id='quicksave-css-frame'></iframe>";
 }
}

// ---------------------
// QuickSave PerPost CSS
// ---------------------
// 1.9.5: added this CSS quicksave
if (!function_exists('bioship_admin_quicksave_perpost_css')) {

 add_action('wp_ajax_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_quicksave_perpost_css', 'bioship_admin_quicksave_perpost_css');

 function bioship_admin_quicksave_perpost_css() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get edit post ID ---
	if (!isset($_POST['postid']) || !isset($_POST['newperpoststyles'])) {exit;}
	$postid = $_POST['postid'];
	if (!is_numeric($postid)) {exit;}

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if (is_user_logged_in()) {

		// --- check edit permissions ---
		if (current_user_can('edit_posts') && current_user_can('edit_post', $postid)) {

			// --- check nonce ---
			// 2.0.0: use wp_verify_nonce instead of check_admin_referer for error message output
			$checknonce = false;
			if (isset($_POST['_wpnonce'])) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce($nonce, 'quicksave-perpost-css-'.$postid);
			}

			// --- update perpost styles ---
			if ($checknonce) {
				$newstyles = stripslashes($_POST['newperpoststyles']);
				// 2.1.1: use prefixed perpost styles metakey
				update_post_meta($postid, '_'.THEMEPREFIX.'_perpoststyles', $newstyles);
			} else {$error = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}

			// --- update current tab to styles ---
			update_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', 'styles');

		} else {$error = __('Failed! You do not have permission to edit this post.','bioship');}
	} else {$error = __('Failed. Looks like you may need to login again!','bioship');}

	// --- script output and exit ---
	if (isset($error)) {echo "<script>alert('".esc_js($error)."');</script>";}
	else {echo "<script>parent.quicksavedshow();</script>";}
	exit;
 }
}

// -------------------------------
// QuickSave PerPost Settings Form
// -------------------------------
// 2.0.0: dummy form copy to save all metabox theme option overrides (prototype)
if (!function_exists('bioship_admin_quicksave_perpost_settings_form')) {
 function bioship_admin_quicksave_perpost_settings_form() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

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
	$textkeys = array('sidebarcustom', 'subsidebarcustom');

	// --- convert settings inputs to javascript arrays ---
	$settingskeys = '';
	foreach ($checkboxkeys as $i => $key) {$settingskeys .= "checkboxkeys[".esc_js($i)."] = '".esc_js($key)."'; "; $i++;}
	$settingskeys .= PHP_EOL;
	foreach ($selectkeys as $j => $key) {$settingskeys .= "selectkeys[".esc_js($j)."] = '".esc_js($key)."'; "; $j++;}
	$settingskeys .= PHP_EOL;
	foreach ($textkeys as $k => $key) {$settingskeys .= "textkeys[".esc_js($k)."] = '".esc_js($key)."'; "; $k++;}
	$settingskeys .= PHP_EOL;

	// --- output settings save script ---
	// 2.1.1: added tab for displaying message and saving current tab
	echo "<script>function quicksavesettings(tab) {
		checkboxkeys = new Array(); selectkeys = new Array(); textkeys = new Array(); ";

		// --- output settings keys ---
		echo PHP_EOL.$settingskeys.PHP_EOL; // phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho

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
		quicksaved = document.getElementById('quicksavesettings-'+tab); quicksaved.style.display = 'block';
		setTimeout(function() {jQuery(quicksaved).fadeOut(5000,function(){});}, 5000);
	}</script>";

	// --- quicksave settings form ---
	// 2.1.1: added buttonid and tab fields
	// 2.1.1: use wp_create_nonce instead of wp_nonce_field
	global $post; $postid = $post->ID;
	$adminajax = admin_url('admin-ajax.php');
	echo "<form action='".esc_url($adminajax)."' method='post' id='quicksave-settings-form' target='quicksave-settings-frame'>";
	$nonce = wp_create_nonce('quicksave-perpost-settings-'.$postid);
	echo "<input type='hidden' name='_wpnonce' id='quicksave-settings-nonce' value='".esc_attr($nonce)."'>";
	echo "<input type='hidden' name='action' value='quicksave_perpost_settings'>";
	echo "<input type='hidden' name='tab' id='quicksave-options-tab' value=''>";
	echo "<input type='hidden' name='postid' value='".esc_attr($postid)."'>";
	foreach ($checkboxkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	foreach ($selectkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	foreach ($textkeys as $key) {echo "<input type='hidden' name='_".esc_attr($key)."' id='__".esc_attr($key)."' value=''>";}
	echo "</form>";

	// --- quicksave settings iframe ---
	echo "<iframe src='javascript:void(0);' style='display:none;' name='quicksave-settings-frame' id='quicksave-settings-frame'></iframe>";
 }
}

// --------------------------
// QuickSave PerPost Settings
// --------------------------
// 2.0.0: save theme overrides via AJAX trigger (prototype)
if (!function_exists('bioship_admin_update_metabox_settings')) {

 add_action('wp_ajax_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_quicksave_perpost_settings', 'bioship_admin_update_metabox_settings');

 function bioship_admin_update_metabox_settings() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check trigger conditions
 	if (!isset($_REQUEST['postid'])) {exit;}
 	$postid = $_REQUEST['postid'];
 	if (!is_numeric($postid)) {exit;}
 	$error = false;

	// --- check if logged in ---
	// 2.1.1: added user logged in check
	if (is_user_logged_in()) {

		// --- check permissions ---
		if (current_user_can('edit_posts') && current_user_can('edit_post', $postid)) {

			// --- check nonce ---
			$checknonce = false;
			if (isset($_POST['_wpnonce'])) {
				$nonce = $_POST['_wpnonce'];
				$checknonce = wp_verify_nonce($nonce, 'quicksave-perpost-settings-'.$postid);
			}
			if ($checknonce) {
				global $post; $post = get_post($postid);
				bioship_admin_update_metabox_options();
			} else {$error = __('Whoops! Nonce has expired. Try reloading the page.','bioship');}

			// --- update current options tab
			// 2.1.1: added saving of current tab
			$tab = $_POST['tab'];
			update_post_meta($postid, '_'.THEMEPREFIX.'_themeoptionstab', $tab);

		} else {$error = __('Failed! You do not have permission to edit this post.','bioship');}
	} else {$error = __('Failed! Looks like you may need to login again!','bioship');}

	// --- output script and exit ---
	// 2.1.1: added tab argument for saved message display
	if ($error) {echo "<script>alert('".esc_js($error)."');</script>";}
	else {echo "<script>parent.quicksavedsettings('".esc_attr($tab)."');</script>";}
	exit;
 }
}

// --------------------------------
// QuickSave Cyclic Nonce Refresher
// --------------------------------
if (!function_exists('bioship_admin_quicksave_nonce_refresher')) {
 function bioship_admin_quicksave_nonce_refresher() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

 	// --- output cyclic nonce refresh script ---
 	global $post; $postid = $post->ID;
	$adminajax = admin_url('admin-ajax.php');
	echo "<script>jQuery(document).ready(function() {
		setTimeout(function() {
			document.getElementById('quicksave-doing-refresh').value = 'yes';
			document.getElementById('quicksave-refresh-iframe').src = '".esc_js($adminajax)."?action=quicksave_update_nonces&postid=".esc_js($postid)."';
		}, 300000);
	});</script>";

	// --- hidden doing refresh input ---
	// 2.1.1: added input to prevent possible multiple alerts
	echo "<input type='hidden' id='quicksave-doing-refresh' value=''>";

	// --- quicksave nonce refresh iframe ---
	echo "<iframe src='javascript:void(0);' id='quicksave-refresh-iframe' style='display:none;'></iframe>";
 }
}

// ----------------------------
// AJAX Update Quicksave Nonces
// ----------------------------
if (!function_exists('bioship_admin_update_quicksave_nonces')) {

 add_action('wp_ajax_quicksave_update_nonces', 'bioship_admin_update_quicksave_nonces');
 // 2.1.1: also trigger for not logged in for logged out alert message display
 add_action('wp_ajax_nopriv_update_nonces', 'bioship_admin_update_quicksave_nonces');

 function bioship_admin_update_quicksave_nonces() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get post ID ---
	$postid = $_REQUEST['postid'];
 	if (!isset($_REQUEST['postid'])) {exit;}
 	$postid = $_REQUEST['postid'];
 	if (!is_numeric($postid)) {exit;}

	// --- session timeout message ---
	// 2.1.1: added alert message to inform of session timeout
	// TODO: trigger showing of popup interim login thickbox ?
	if (!is_user_logged_in()) {
		$message = __('Your session has timed out. Please Login again to continue editing.','bioship');
		echo "<script>alert('".esc_js($message)."');</script>";
		exit;
	}

	// --- check edit permissions ---
	if (!current_user_can('edit_posts') || !current_user_can('edit_post', $postid)) {exit;}

	// --- create new nonces ---
	$settingsnonce = wp_create_nonce('quicksave-perpost-settings-'.$postid);
	$cssnonce = wp_create_nonce('quicksave-perpost-css-'.$postid);

	// --- send new nonces back to parent window ---
	// 2.1.1: reset doing nonce refresh flag on refresh
	echo "<script>parent.document.getElementById('quicksave-doing-refresh').value = '';
	parent.document.getElementById('quicksave-settings-nonce').value = '".esc_js($settingsnonce)."';
	parent.document.getElementById('quicksave-css-nonce').value = '".esc_js($cssnonce)."';</script>";
	exit;
 }
}
