<?php

// =============================
// === BioShip Documentation ===
// =============================

// Load this file in your browser for documentation index.

define('THEMEDOCDEBUG',false); // define('THEMEDOCDEBUG',true);

if (!defined('THEMEHOMEURL')) {define('THEMEHOMEURL', 'http://bioship.space');}
if (!defined('THEMESUPPORT')) {define('THEMESUPPORT', 'http://wordquest.org');}


// --------------
// DOCS TODO LIST
// --------------
// - Theme Tools backup/restore/import/export notes!
// Frameworks
// - add more extensive information on Hybrid Core
// - add more extensive information on Foundation here
// Metabox
// - double-check display override reference completeness
// Sidebar Guide
// - more sidebar filter examples
// Grid
// - notes and example on using small spacer columns
// Layout Hooks
// - add Page Elements for styling reference
// - finish banner position notes
// Theme Values
// - vthemedisplay and vthemeoverride globals
// Debugging
// - better debug to file options and explanation
// - better theme tracer explanation
// --------------


// ----------------------------
// Documentation Section Titles
// ----------------------------
// 2.1.1: added doc links titles
global $vthemedoctitles;
$vthemedoctitles = array(
	'install'		=> 'Installation and Updates',
	'child'			=> 'Child Theme Setup',
	'frameworks'	=> 'Included Frameworks',
	'options'		=> 'Options Reference',
	'metabox'		=> 'Writing Screen Metabox',
	'filters'		=> 'Conditional Value Filters',
	'files'			=> 'File Guide and Hierarchy',
	'templates'		=> 'Page Template Hierarchy',
	'sidebars'		=> 'Sidebar Layout Guide',
	'grid'			=> 'Responsive Grid System',
	'hooks'			=> 'Layout Hook Reference',
	'values'		=> 'Constants and Globals',
	'debug'			=> 'Debugging and Tracing',
);


// ---------------------------
// Serve Documentation Section
// ---------------------------
// (if not included on documentation site)
if (!isset($includeddocs)) {

	if (isset($_REQUEST['page'])) {$page = $_REQUEST['page'];} else {$page = '';}

	// --- Documentation Index ---
	if ( ($page == 'home') || ($page == 'index') || ($page == '') ) {
		echo bioship_docs_index(true);
	}

	// === Installation ===
	if ($page == 'install') {echo bioship_docs_install_guide(true);}
	if ($page == 'child') {echo bioship_docs_child_themes(true);}
	if ($page == 'frameworks') {echo bioship_docs_framework_guide(true);}

	// === Theme Options ===
	if ($page == 'options') {echo bioship_docs_option_list(true);}
	if ($page == 'metabox') {echo bioship_docs_metabox_guide(true);}

	// === Hierarchies ===
	if ($page == 'files') {echo bioship_docs_file_hierarchy(true);}
	if ($page == 'templates') {echo bioship_docs_template_hierarchy(true);}

	// === Layout ===
	if ($page == 'sidebars') {echo bioship_docs_sidebar_guide(true);}
	if ($page == 'grid') {echo bioship_docs_grid_system(true);}
	if ($page == 'hooks') {echo bioship_docs_layout_hooks(true);}

	// === Development ===
	if ($page == 'filters') {echo bioship_docs_filter_list(true);}
	if ($page == 'values') {echo bioship_docs_theme_values(true);}
	if ($page == 'debug') {echo bioship_docs_debug_guide(true);}

}

// -------------------
// Documentation Index
// -------------------
// 2.0.5: added table markers for admin page
function bioship_docs_index($wrap) {

	// 2.1.1: use documentation links and titles globals
	global $vthemedoclinks, $vthemedoctitles;
	$html = '';
	if ($wrap) {$html = bioship_docs_wrap_open('index'); $html .= '<br><h2>BioShip Documentation</h2>';}

	$html .= '<div id="bioshipdocindex">'.PHP_EOL;

		$html .= '<!-- START -->';

		$html .= '<h3>Setup</h3>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['install'].'">'.$vthemedoctitles['install'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['child'].'">'.$vthemedoctitles['child'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['frameworks'].'">'.$vthemedoctitles['frameworks'].'</a><br>'.PHP_EOL;

		$html .= '<!-- SPLIT -->';

		$html .= '<h3>Options</h3>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['options'].'">'.$vthemedoctitles['options'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['metabox'].'">'.$vthemedoctitles['metabox'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['filters'].'">'.$vthemedoctitles['filters'].'</a><br>'.PHP_EOL;

		$html .= '<h3>Hierarchies</h3>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['files'].'">'.$vthemedoctitles['files'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['templates'].'">'.$vthemedoctitles['templates'].'</a><br>'.PHP_EOL;

		$html .= '<!-- SPLIT -->';

		$html .= '<h3>Layout</h3>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['sidebars'].'">'.$vthemedoctitles['sidebars'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['grid'].'">'.$vthemedoctitles['grid'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['hooks'].'">'.$vthemedoctitles['hooks'].'</a><br>'.PHP_EOL;

		$html .= '<h3>Development</h3>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['values'].'">'.$vthemedoctitles['values'].'</a><br>'.PHP_EOL;
		$html .= '<a href="'.$vthemedoclinks['debug'].'">'.$vthemedoctitles['debug'].'</a><br>'.PHP_EOL;

		$html .= '<!-- END -->';

	$html .= '</div>'.PHP_EOL;

	if ($wrap) {$html .= bioship_docs_wrap_close('index');}

	return $html;
}

// ---------------------
// Cross Links Generator
// ---------------------
function bioship_docs_links($wrap) {

	global $vthemedoclinks;

	$pages = array(
		// --- Installation ---
		'install', 'child','frameworks',
		// --- Theme Options ---
		'options', 'metabox', 'filters',
		// --- Hierarchies ---
		'files', 'templates',
		// --- Layout ---
		'sidebars', 'grid', 'hooks',
		// --- Development ---
		'values', 'debug'
	);

	// --- set doc links URLs ---
	$vthemedoclinks = array();
	if ($wrap) {
		$vthemedoclinks['index'] = 'docs.php';
		foreach ($pages as $page) {$vthemedoclinks[$page] = 'docs.php?page='.$page;}
	} else {
		$vthemedoclinks['index'] = '/documentation/';
		foreach ($pages as $page) {$vthemedoclinks[$page] = '/documentation/'.$page.'/';}
	}

	return $vthemedoclinks;
}

// ----------------------------
// Standalone Body Wrapper Open
// -----------------------------
function bioship_docs_wrap_open($page) {

	global $vthemedoclinks;

	// --- open html and head ---
	$html = '<html><head>';

	// --- documentation page styles ---
	$html .= '<style>body {font-family:calibri,tahoma,helvetica,arial; margin: 20px 10% !important;}
	a {text-decoration:none;} a:hover {text-decoration:underline;}
	h4 {font-size:1.1em;} h5 {font-size: 1em;} h6 {font-size:0.9em;}
	h3 {margin-top:1.5em; margin-bottom:0.5em;} h4, h5, h6 {margin-top: 1em; margin-bottom: 0.3em;}
	</style>';

	// --- enqueue normalize.css ---
	$parseurl = parse_url($_SERVER['SCRIPT_NAME']);
	$path = str_replace('/admin/docs.php', '/styles/normalize.css', $parseurl['path']);
	$normalizeurl = 'http://'.$_SERVER['HTTP_HOST'].$path;
	$html .= '<link rel="stylesheet" href="'.$normalizeurl.'">';

	// --- autoscroll to hash script';
	$html .= '<script>function scrolltohash(hashName) {location.hash = "#"+hashName;}</script>';

	// --- grid styles and stylesheet ---
	// 2.1.1: moved here from grid section to be inside head tag
	if ($page == 'grid') {

			// --- grid example styles ---
			$html .= '<style>#gridexample .container_12 {margin-top:5px;}
				#gridexample .container_12 .column.left, #gridexample .container_12 .columns.left,  {border-right: 1px solid #000;}
				#gridexample .container_12 .column.right, #gridexample .container_12 .columns.right {border-left: 1px solid #000;}
				#gridexample .inner {text-align:center;}
				#gridexample .inner div {background-color:#E0E0E0;}
				#gridreference td {padding:5px;}</style>';

			// --- load grid.php here for offline example displays ---
			$html .= '<link href="../grid.php" rel="stylesheet"></style>';
	}

	// --- close head open body ---
	$html .= '</head><body bgcolor="#F0EFFF">';

	// --- add top navigation link table ---
	if ( ($page != 'index') && ($page != 'quickstart') ) {
		$html .= bioship_docs_navigation($page);
	}

	return $html;
}

// -----------------------------
// Standalone Body Wrapper Close
// -----------------------------
function bioship_docs_wrap_close($page) {

	global $vthemedoclinks;

	if ( ($page != 'index') && ($page != 'quickstart') ) {

		$html = '<div style="padding-top:40px; padding-bottom:40px;">';

			// --- add bottom navigation link table ---
			// 2.1.1: added better documentation navigation
			$html .= bioship_docs_navigation($page);

		$html .= '</div>';

	} else {$html = '<div style="padding-top:40px;"></div>';}

	// --- close body and html ---
	$html .= '</body></html>';

	return $html;
}

// ----------------------------
// Documentation Navigation Box
// ----------------------------
// 2.1.1: added for better docs navigation
function bioship_docs_navigation($page) {

	global $vthemedoclinks, $vthemedoctitles, $docnav;
	if (isset($docnav)) {return $docnav;}

	// --- loop to get previous and next links / titles ---
	$found = false;
	$prevlink = $prevtitle = $nextlink = $nexttitle = '';
	foreach ($vthemedoctitles as $key => $title) {
		if ($found && ($nextlink == '')) {$nextlink = $vthemedoclinks[$key]; $nexttitle = $title;}
		if ($key == $page) {$found = true; $prevlink = $previouslink; $prevtitle = $previoustitle;}
		$previouslink = $vthemedoclinks[$key]; $previoustitle = $title;
	}

	// --- generate navigation html ---
	$html = '<table width="100%">';
		$html .= '<tr><td width="33%" align="left">';
			if ($prevlink != '') {$html .= '<a href="'.$prevlink.'">&larr; Previous: '.$prevtitle.'</a>';}
		$html .= '</td><td width="33%" align="center">';
			$html .= '<a href="'.$vthemedoclinks['index'].'">Back to Documentation Index</a>';
		$html .= '</td><td width="33%" align="right">';
			if ($nextlink != '') {$html .= '<a href="'.$nextlink.'">Next: '.$nexttitle.' &rarr;</a>';}
		$html .= '</td></tr>';
	$html .= '</table>';

	$docnav = $html;
	return $html;
}


// =============
// === SETUP ===
// =============


// ------------------
// === Quickstart ===
// ------------------
// 2.0.5: added quickstart section

function bioship_docs_quickstart($wrap) {

	$html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open('quickstart').'<h2>BioShip QuickStart</h2><br>';}

	$html .= '<p>Even though BioShip has a lot to it, getting started with BioShip is actually easy.
	The main thing to remember is you do not need to setup and use every single feature!
	For the majority of projects the default settings for many of the options are great.
	So the fastest way to get started is to leave them alone and focus on your design.</p>';

	$html .= '<p>Practically speaking, this means customizing the Skin layer settings mostly, and
	adjusting some Skeleton layer sidebar settings if you need to modify your sidebars.
	Most of the Skeleton and all of the Muscle layer settings can be left for later.</p>';

	$html .= '<p>BioShip has a lot of complex code under the hood to make everything <i>super-flexible</i>.
	This means you can leave the more advanced stuff for if and when you actually need it,
	confident and relaxed knowing that you really can change anything at all in the future.
	And in the meantime, enjoy the simplicity of having everything setup and working sooner!</p>';

	$html .= '<p>And if and when you are ready to delve deeper, complete documentation is available below.
	I hope you enjoy using BioShip and welcome any feedback or improvements.</p>';

	if ($wrap) {$html .= bioship_docs_wrap_close('quickstart');}

	return $html;

}


// --------------------
// === Installation ===
// --------------------

function bioship_docs_install_guide($wrap) {

	$page = 'install'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Installation</h2><br>';}

	$html .= 'The <b>Basic Install</b> for BioShip is simple and straightforward as with a standard WordPress Theme,
		just download the BioShip Zip file and upload it via the <i>Themes</i> page in your WordPress <i>Appearance</i> admin menu
		(or unzip locally and upload to <i>/wp-contents/themes/bioship/</i> )<br><br>';

	$html .= 'Alternatively you can do a <b>Preview Install</b> using the instructions below. This allows you to setup
		the theme before activating it - a kind of theme sandbox if you will - something traditionally hard to do in WordPress
		without creating a development copy of the entire site! (Although, you can do this to a certain extent using the in-built
		WordPress Customizer theme preview.)<br><br>';

	$html .= '<i>Sidenote:</i> To compensate for different themes having different menu and sidebar data, BioShip attempts
		to backup/restore your menus and sidebars when it is activated or deactivated. This helps preserve your menu/sidebar setup
		associated with each theme. While Wordpress <i>has</i> improved this process recently, this extra step keeping all the
		matching sidebar data for activation/deactivation themes. (Another good way to backup your Widget Layouts is with the
		<a href="http://wordpress.org/plugins/widget-saver/" target=_blank class="pluginlink">Widget Saver</a> plugin.)<br>';

	$html .= '<h3>Basic Install</h3>
		(recommended for fresh or development sites)<br><br>
		<b>1</b>. Download the <a href="'.THEMEHOMEURL.'/download/download-latest/">BioShip ZIP</a> (right-click and "Save Linked Content As") to your computer.<br>
		<b>2</b>. Login to your Wordpress admin area if you are not already.<br>
		<b>3</b>. Visit your Wordpress admin <i>Themes</i> page and upload via the <i>Add New -> Upload</i> page.<br>
		<b>4</b>. Activate the theme once it is uploaded.<br>
		<b>5</b>. You can now access the <i>Theme Options</i> page via the <i>Appearance</i> menu (or via the top <i>Admin Bar</i>.)<br>';

	$html .= '<h3>Preview Install</h3>
		(recommended for live sites with an existing theme)<br><br>
		<b>1</b>. Login to your Wordpress admin area if you are not already.<br>
		<b>2</b>. Install the <a href="http://wordpress.org/plugins/theme-test-drive/" target=_blank>Theme Test Drive</a> Plugin from your Wordpress admin <i>Plugins</i> page via <i>Add New</i> and activate.<br>
		<b>3</b>. Visit your <i>Theme Test Drive</i> settings page under the <i>Appearance</i> admin menu.<br>
		<b>4</b>. Copy the URL of the <a href="'.THEMEHOMEURL.'/download/download-latest/">BioShip ZIP</a> (right-click and "Copy Link Address") and paste into the Easy Install section and Install.<br>
		<b>5</b>. Now, you can either:<br>
		&nbsp;<b>a</b>. Activate the Theme Test Drive for the BioShip theme with Level 10 (administrator) privileges. Remember the theme test
		drive for the theme will be active for all administrators until you disable it via this page! You can now access the <i>Theme Options</i>
		page via the <i>Appearance</i> menu (or via the top <i>Admin Bar</i>.)<br>
		&nbsp;or <b>b</b>. Use a querystring for a temporary preview of the theme on any page, by adding ?theme=bioship to the page address URL in
		your browser window. You probably want to change the Theme Options first, so you can access the <i>Theme Options</i> page manually in
		this temporary preview mode by going to:<br><br>
		<b>WordPress Customizer</b>: <i>/wp-admin/customize.php?theme=bioship</i><br>
		<b>Titan Framework</b>: <i>/wp-admin/themes.php?page=bioship-options&theme=bioship</i><br>
		<b>Options Framework</b>: <i>/wp-admin/themes.php?page=options-framework&theme=bioship</i><br><br>
		(Generally speaking, if you are the sole site admin of the site then option 5a is fine, otherwise you might
		want to go with step 5b so that other site admins do not see the new theme preview while you are developing it!)<br>';

	$html .= '<h4>Theme Updates</h4>
		(via WordPress Upgrader)<br><br>
		Theme updates are available via your <i>Themes</i> page, just as they would be for any standard theme in the Wordpress.Org
		repository. Clicking on <i>Update Available</i> on the BioShip Theme will bring up the <i>Theme Details</i>. From there you
		can click on <i>view version x.x.x Details</i> before updating to show you the latest changes, and then you can simply click
		on <i>Update Now</i> to update to the latest version.<br><br>';

	$html .= 'Note: If you downloaded the  BioShip package from the WordPress theme repository, that will also be used as the update source.
		If you downloaded direct from the BioShip website, the updater will use the website\'s update server via <a href="http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/" target=_blank>WShadow Theme Updater</a>
		(Thanks WShadow!)<br>';

	$html .= '<h5>Manual Theme Update</h5>
		(for super-fast update testing)<br><br>
		<b>1</b>. Download the latest <a href="'.THEMEHOMEURL.'/download/download-latest/">BioShip ZIP</a> (right-click and "Save Linked Content As") to your computer.<br>
		<b>2</b>. Unzip the file locally, then upload it by FTP to:	<i>/wp-content/themes/bioship-new/</i><br>
		(make sure you log in FTP as the correct user so owner/group permissions match your install!)<br>
		<b>3</b>. Rename the existing <i>/bioship/</i> subdirectory to <i>/bioship-old/</i><br>
		<b>4</b>. Rename the <i>/bioship-new/</i> subdirectory to <i>/bioship/</i><br>
		<b>5</b>. Check the new version is working and when you are ready delete <i>/bioship-old/</i><br>
		This fast "switcheroo" install will update to the new framework core with no downtime,
		and if you have any problems at all you can always switch back to the previous version without hassle.<br>';

	$html .= '<h5>Optimization</h5>
		For pageload optimization use a caching plugin such as <a href="http://wordpress.org/plugin/w3-total-cache/" target=_blank>W3 Total Cache</a>.<br>
		Preferably disable the W3 Minify Engine and use <a href="http://wordpress.org/plugins/better-wordpress-minify" target=_blank>Better WordPress Minify</a>.<br>
		(BioShip 2.0.5+ integrates with Better WordPress Minity to prevent minification of a few resources.
		This stops it from breaking the page layout, whereas prior to this it needed to be done manually.)<br>';

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// --------------------
// === Child Themes ===
// --------------------

function bioship_docs_child_themes($wrap) {

	$page = 'child'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Child Theming</h2><br>';}

	$html .= "<p>Setting up a Child Theme using the BioShip Theme Framework as your Parent Theme is very easy and highly recommended
		for best practice development. The main reason being as with any Child Theme you can keep the Parent Theme Framework updated
		(allowing for improvements and fixes to the Framework to easily be installed) without losing your own file customizations and
		functions. As most websites require <i>some</i> kind of customization of this kind, being able to add and modify your Child Theme
		<i>functions.php</i> is the most basic of features that a Parent Theme should support.</p>";

	$html .= "In BioShip, to extend this support, <b>all</b> the core theme functions have been made \"pluggable\" (overrideable.)
		In other words each function declaration is wrapped in conditional <i>function_exists</i> checks to make this possible.
		So to override any function, simply place a modified copy of a function in your Child Theme's <i>functions.php</i> file,
		As WordPress intentionally loads this file <i>before</i> the Parent Theme's <i>functions.php</i>, the modified function
		will be loaded <i>instead</i> of the default Parent Theme (Framework) function. (for reference see <a href='http://codex.wordpress.org/Child_Themes' target=_blank>Child Themes WordPress Codex</a>.)
		(Note: Layout Hook changes are a possible exception as the hooks must already exist, see <a href='".$vthemedoclinks['hooks']."'>Layout Hooks</a> section for further details on overriding these.)</p>";

	// TODO: add note about add_action / add_filter calls being placed INSIDE pluggable functions

	$html .= "There are of course many other potential benefits to having a Child Theme, such as being able to override Templates,
		Javascript and CSS files - or any other files for that matter. BioShip's extensive file hierarchy allows you to do this for
		<b>all</b> theme files and templates (see the <a href='".$vthemedoclinks['files']."'>File Hierarchy</a> and <a href='".$vthemedoclinks['templates']."'>Template Hierarchy</a> sections.)
		Layout hooks can also be modifyied for easy reordering, replacing or adding of page elements (again see <a href='".$vthemedoclinks['hooks']."'>Layout Hooks</a> section.)
		Plus custom value filters can be used in your <i>functions.php</i> for even further possibilities (see <a href='".$vthemedoclinks['filters']."'>Value Filters</a> section.)
		While for simple designs these kinds of advanced customization will be unnecessary - as the existing functions are written to handle a wide variety
		of cases very well - having the easy option to override <i>anything</i> can remove makes custom theme development with BioShip a breeze.</p>";

	$html .= "<h3>One-Click Child Theme Install</h3>";
	$html .= "<p>To make it even easier, BioShip has One-click Creation for Child Themes, so you can get customizing as soon as possible.
		Plus, BioShip auto-transfers your current Parent Theme Setting to your Child Theme when you first activate your it, so there is
		no need to set the same settings again if you are moving from using BioShip as a standalone Theme to using a Child Theme.</p>";

	$html .= "<p>Visit your <i>Theme Options</i> page under the Wordpress admin <i>Appearance</i> menu. Enter a new Child Theme display name
		in the box to the right of the layer tabs and click <i>Create Child Theme</i>. The next page will show the creation result in the same
		space. Click the activate link there and you are now running your new Child Theme!</p>";

	$html .= "<h4>Manual Child Theme Install</h4>";
	$html .= "Unzip your download of BioShip locally and upload the contents of the subdirectory /bioship/child/ via FTP to a subdirectory of your choice in
		the /wp-content/themes/ directory. (eg. /wp-content/themes/sweet-child-of-mine/)<br><br>";

	$html .= "<i>Before Activating</i>: Optionally edit the <b>Name</b> Field of the Child Theme at the top of the <i>style.css</i> file in the directory you created.
		(Important Note: if you change this name later you will need to copy the saved Theme Options to the new one in the options table.) This will change the name
		of the active theme in your admin area. Once as desired, simply activate the new Child Theme from your <i>Themes</i> page and you're good to go!<br>";

	$html .= "<h4>Preview Child Theme Install</h4>
		<b>1</b>. Follow either option above but do not activate yet. Instead, make sure you have Theme Theme Drive plugin installed and activated, and either:<br>
		<b>2a</b>. Activate the Theme Test Drive for your Child Theme with Level 10 (administrator) privileges. Remember the theme test drive for the theme will be
		active until you disable it via this page! You can now access the <i>Theme Options</i> page under the <i>Appearance</i> menu or via the top <i>Admin Bar</i>.<br>
		or <b>2b</b>. Use a querystring for a temporary preview of the Child Theme on any page, by adding ?theme=child-theme-slug to the page address URL
		in your browser window (where child-theme is your Child Theme's slug - a lowercase version of Child Theme name with spaces replaced by hyphens.)<br><br>
		For example, you can access the <i>Theme Options</i> manually in this temporary preview mode by going to one of the following URLs:<br>
		WordPress Customizer: <i>/wp-admin/customize.php?theme=child-theme-slug</i><br>
		Titan Framework: <i>/wp-admin/themes.php?page=bioship-options&theme=child-theme-slug</i><br>
		Options Framework: <i>/wp-admin/themes.php?page=options-framework&theme=child-theme-slug</i><br><br>";

	$html .= "<p>In this way you can create a new Child Theme skin for your site and activate it once you are satisfied with the new design
		result. Again, generally speaking, if you are the sole site admin of the site then option 2a is fine, otherwise you might want to go
		with 2b so that other site admins aren't confused by seeing the new in theme preview while you're developing with it.</p>";

	$html .= "<h5>Cloning Child Themes</h5>";
	$html .= "<p>You can also clone any existing Child Theme with one click at the top right of the Theme Options page for that Child Theme.
		This allows you to easily 'fork' an existing Child Theme for further development without affecting the live site display.
		All files and theme settings will be automatically copied to the new clone Child Theme directory on form submission.
		You can then activate the new Child Theme at any point from the <i>Appearance</i> &gt; <i>Themes</i> page.</p>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// ------------------
// === Frameworks ===
// ------------------

function bioship_docs_framework_guide($wrap) {

	$page = 'frameworks'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Frameworks</h2><br>';}

	$html .= "Available BioShip Theme Options are separated into Skin, Muscle and Skeleton sections for all Frameworks! :-)<br>";
	$html .= "This helps organize the design, functionality and templating options respectively (see <a href='".THEMEHOMEURL."/'>BioShip Home</a>)<br><br>";

	$html .= "<h3>Theme Options Frameworks</h3>";

	$html .= "<h4>WordPress Customizer</h4>";
	$html .= "<i>/admin/customizer.php</i><br>";
	$html .= "<p>BioShip supports the in-built WordPress Customizer for all Theme Options with Live Preview updates via the Customizer API.
		The Customizer gives you a sidebar panel with Theme Settings and a live preview window that is updated by javascript or refresh.
		Bioship implements some custom controls by using the <a href='http://kirki.org' target=_blank>Kirki Library</a> in combination with it's own theme options array.
		You will also see some extra panel controls have been added for the Customizer to control sidebar width and position.
		If you find the Customizer interface restrictive is it recommended you use the Titan Framework admin page instead.</p>";

	$html .= "<h5>WordPress.Org Compliance</h5>";
	$html .= "The Customizer is required to be supported for making BioShip freely available on WordPress.Org theme repository in future.
		If you are curious, this step was taken by the Theme Review Team to provide a more consistent experience for the end user.
		Personally I prefer using Options or Titan Framework to the Customizer, but all options are available on either it is up to you! :-)
		If you are using the WordPress.Org version of BioShip, you will need to install Titan Framework <i>as a plugin</i> to access Titan Admin page.
		If you would prefer to use bundled Titan or Options Framework, reinstall the theme from <a href='".THEMEHOMEURL."/' target=_blank>BioShip.Space</a>.
		(Note the file <i>update-checker.php</i> is removed from the WP.Org version so that updates are via the WordPress.Org repository.)</p>";

	$html .= "<h4>Titan Framework</h4>";
	$html .= "<i>/includes/titan/</i><br>";
	$html .= "<p>Titan provides a great modern user interface for modifying all available Theme Settings for BioShip in the one place.
		There is a wide variety of controls available via Titan, however only a small number are used to reduce complexity.
		Note: admin page output is changed for BioShip so that all tabs are available on the one pageload (like Options Framework.)<br>
		Reference: <a href='http://titanframework.net/' target=_blank>Titan Framework Documentation</a>.<br>
		[Theme Settings option_name key: <i>child-theme-slug</i>_options]</p>";

	$html .= "<h4>Options Framework</h4>";
	$html .= "<i>/includes/options/</i><br>";
	$html .= "<p>BioShip Theme Options were originally handled by the Options Framework, which is still supported but no longer default.
		While there are some minor differences between the Titan and Options Framework, they are very similar in operation.<br>
		Reference: <a href='http://wptheming.com/options-framework-plugin/' target=_blank>Options Framework Documentation</a>.<br>
		[Theme Settings option_name key: <i>child_theme_slug</i>]</p>";

	$html .= "<h5>v1.5.0+ Upgrade Notice</h5>";
	$html .= "<p>Since Titan if now used by default after v1.5.0, if you are upgrading from that, use one of the two options given below,
		either to switch back to Options Framework or to transfer your existing theme settings to the Titan Framework format.<br>";

	$html .= "<h5>Framework Switching</h5>";
	$html .= "<p>To use Options instead of Titan, create an option name '<i>theme-slug</i>_framework' and the value of 'options'
		Alternatively, you can create a file in your desired theme directory called <i>titanswitch.off</i>
		and if you wish to switch back to using Titan again, create a file called <i>titanswitch.on</i>
		If either of these files is created it will change the above options value and then delete the file.
		After switching between these Frameworks you will want to review your theme settings and resave.</p>";

	$html .= "<h5>Transferring Settings</h5>";
	$html .= "<i>?transfersettings=totitan&fromtheme=theme-slug&totheme=theme-slug</i><br>";
	$html .= "<p>To transfer settings from one framework to another you can use the above querystring on an admin URL (when logged in as administrator or with <i>edit_theme_options</i> capability.)
		This is helpful when converting theme settings from Options Framework usage to using Titan Framework instead.
		After doing the transfer and switching to the Titan Framework, be sure to check your settings (especially fonts)
		on the Theme Options page and save them again to remove again discrepencies.</p>";

	$html .= "<p>The <i>totheme</i> parameter is optional, if left out it will transfer settings to your current active theme.
		Note: Currently only supports transferring existing theme settings <i>TO</i> Titan from Options Framework.
		as code is needed for image URLs from Options Framework to be inserted in the media library for Titan, and
		development will be moving forward with Titan rather than Options Framework.)</p>";

	$html .= "<h5>Copying Theme Settings</h5>";
	$html .= "<i>?copysettings=yes&fromtheme=theme-slug&totheme=theme-slug</i><br>";
	$html .= "<p>To copy theme settings between any two existing themes using the above query string on an admin URL (when logged in as administrator or with <i>edit_theme_options</i> capability.)
		This may be helpful if you created a Child Theme and the theme settings failed to transfer from the Parent Theme.
		Or if you simply wish to copy theme settings between themes for some other reason.</p>";

	$html .= "<h3>Other Frameworks</h3>";

	// TODO: add more extensive information on Hybrid Core here..?
	$html .= "<h4>Hybrid Core</h4>";
	$html .= "<i>/includes/hybrid2/</i> or <i>/includes/hybrid3/</i><br>";
	$html .- "<a href='http://themehybrid.com/hybrid-core' target=_blank>Hybrid Core Framework</a> is included with BioShip for a number of useful in-built function and extensions,
		such as content template hierarchy, page element attributes and inbuilt Schema.org markup<br>
		<p>Hybrid Core is activated via <i>Theme Options</i> -> <i>Skeleton</i> -> <i>Hybrid</i> tab.<br>
		Note: for consistency, Hybrid Content Template Hierarchy and attributes are implemented regardless of this.</p>";

	$html .= "<h5>Hybrid Hook</h5>";
	$html .= "<i>/includes/hybrid-hook/</i><br>";
	$html .= "<p>All hook positions are automatically made available to the Hybrid Hook plugin (modified for BioShip.)
		When activated (via <i>Theme Options</i> -> <i>Skeleton</i> -> <i>Hybrid</i>) you can access <i>Appearance</i> -> <i>Hybrid Hook</i>.
		This allows you to easily add content (Text, HTML or Shortcode) to any of the Layout Hook section positions.
		(You can also specify a priority if you need to insert between existing hooked functions.)
		see the <a href='".$vthemedoclinks['hooks']."'>Layout Hook Guide</a> for a detailed hook reference.</p>";

	$html .= "<h4>Foundation</h4>";
	$html .= "<i>/includes/foundation5/</i> or <i>/includes/foundation6/</i><br>";
	$html .= "<p>Foundation by Zurb loading is activated via <i>Theme Options</i> -> <i>Skeleton</i> -> <i>Foundation</i> tab.</p\>";
	// TODO: add more extensive information on Foundation here...

	$html .= "<h3>BioShip Extensions</h3>";
	$html .= "For further theme extensions, see the online <a href='".THEMEHOMEURL."/documentation/extensions/'>BioShip Extensions</a> page.<br><br>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// =============
// THEME OPTIONS
// =============

// -------------------
// === Option List ===
// -------------------

function bioship_docs_option_list($wrap) {

	$page = 'options'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Theme Options</h2><br>';}

	// --- maybe set selected option ---
	if (isset($_REQUEST['option'])) {$selected = $_REQUEST['option'];} else {$selected = '';}
	$html .= "<!-- Selected Option: ".$selected." -->";

	// --- toggle section script ---
	$html .= "<script>
	function togglesection(section) {
		sectionid = 'section-'+section;
		if (document.getElementById(sectionid).style.display == 'none') {
			document.getElementById(sectionid).style.display = '';
		} else {document.getElementById(sectionid).style.display = 'none';}
	}";
	if ($selected != '') {$html .= "scrolltohash('".$selected."');";}
	$html .= "</script>";

	// --- declare some dummy functions to allow include of options.php ---
	if (!function_exists('bioship_options')) {
		if (!function_exists('add_action')) {function add_action($a=null,$b=null,$c=null) {} }
		if (!function_exists('bioship_trace')) {function bioship_trace($a=null,$b=null,$c=null,$d=null) {} }
		if (!function_exists('apply_filters')) {function apply_filters($f,$v) {return $v;} }
		if (!function_exists('get_categories')) {function get_categories() {} }
		if (!function_exists('get_template_directory_uri')) {function get_template_directory_uri() {} }
		if (!function_exists('get_post_types')) {function get_post_types() {} }
		if (!function_exists('is_rtl')) {function is_rtl() {} }
		if (!function_exists('get_option')) {function get_option() {} }
		if (!function_exists('get_intermediate_image_sizes')) {function get_intermediate_image_sizes() {} }
		// 2.0.7: changed dummy translation arguments to pass theme check
		if (!function_exists('__')) {function __($s, $d = 'bioship', $a = 'bioship') {return $s;} }
		// 2.0.8: fix to missing prefixed function
		if (!function_exists('bioship_apply_filters')) {function bioship_apply_filters($f,$v,$e=null) {return $v;} }
		include(dirname(dirname(__FILE__)).'/options.php');
	}

	// TODO: add short intro for theme options ?


	// --- get the theme options ---
	// 2.0.5: change of option function to bioship_ prefix
	// $options = optionsframework_options(false);
	$options = bioship_options(false);
	// print_r($options);

	$layer = $section = '';

	foreach ($options as $option) {

		// Layer Output
		// ------------
		if ($layer == '') {

			// --- skin layer ---
			$layer = 'skin';
			$html .= "<h3>SKIN</h3>".PHP_EOL;
			$html .= "<div id='".$layer."'>".PHP_EOL;

		} elseif ( ($option['type'] == 'heading') && ($option['id'] != $layer) ) {

			// --- further layers ---
			$layer = $option['id'];
			$html .= "</div><br>".PHP_EOL.PHP_EOL;
			$html .= "<h3>".strtoupper($layer)."</h3>".PHP_EOL;
			$html .= "<div id='layer-".$layer."'>".PHP_EOL;

		} elseif (isset($option['hidden']) && $option['hidden']) {

			// --- hidden layer toggle ---
			if ($layer != 'hidden') {
				$layer = 'hidden';
				$html .= "</div><br>".PHP_EOL.PHP_EOL;
				$html .= "<h3><a href='javascript:void(0);' onclick='togglesection(\"".$layer."\");'>".strtoupper($layer)."</a></h3>".PHP_EOL;
				$html .= "<div id='section-".$layer."' style='display:none;'>".PHP_EOL;
			}
		}

		// Heading Output
		// --------------
		if ($option['type'] == 'heading') {

			// --- heading output ---
			if ($section != '') {$html .= '</div>';}
			$section = strtolower($option['name']);
			$html .= "<h4><a href='javascript:void(0);' onclick='togglesection(\"".$section."\");'>".$option['name']."</a></h4>".PHP_EOL;
			if (isset($option['desc'])) {$html .= $option['desc']."<br>";}
			$html .= "<div id='section-".$section."' style='display:none;'>".PHP_EOL.PHP_EOL;

		} elseif ($option['type'] == 'info') {

			// --- info output ---
			$html .= "<h4><i>".$option['name']."</i></h4>".PHP_EOL;
			$html .= $option['desc']."<br>".PHP_EOL.PHP_EOL;

		} else {

			// Option Output
			// -------------
			$html .= "<div id='".$option['name']."'>";
			$html .= "<b>".$option['name']."</b><br>".PHP_EOL;
			$html .= $option['desc']."<br>".PHP_EOL;

			// --- option ID ---
			$html .= "<b>ID</b>: ".$option['id']."<br>".PHP_EOL;

			// --- option type ---
			$html .= "<b>Type</b>: ".$option['type']."<br>".PHP_EOL;

			// --- default option ---
			if (!is_array($option['std'])) {
				if ($option['std'] == '') {$option['std'] = '<i>none</i>';}
				$html .= "<b>Default</b>: ".$option['std']."<br>".PHP_EOL;
			} else {
				$html .- "<b>Defaults</b>: ".implode(', ',array_keys($option['std']))."<br>".PHP_EOL;
			}

			// if (isset($option['options']) && is_array($option['options']) ) {
			//	$html .= "<b>Options</b>: <br>".PHP_EOL;
			//	foreach ($option['options'] as $value => $label) {
			//		$label." (".$value.")<br>".PHP_EOL;
			//	}
			// }

			if (isset($option['csselement'])) {$html .= "<b>CSS Element(s)</b>: ".$option['csselement']."<br>".PHP_EOL;}
			if (isset($option['cssproperty']) && ($option['cssproperty'] != 'typography')) {
				$html .= "<b>CSS Property</b>: ".$option['cssproperty']."<br>".PHP_EOL;
			}

			// TODO: display other option values ?
			// transport, fonts, etc...

			$html .= "<br></div>".PHP_EOL;
		}

	}

	$html .= "</div>".PHP_EOL; // close final heading div

	$html .= "</div><br><br>".PHP_EOL.PHP_EOL; // close final section div

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// ---------------------
// === Metabox Guide ===
// ---------------------

function bioship_docs_metabox_guide($wrap) {

	$page = 'metabox'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Theme Metabox</h2><br>';}

	$html .= "<p>You will find a Theme Settings Metabox on the Writing Screen (above the Publish box) which allows you to override default
		display and other settings on a per-post (or per-page) basis, giving you more fine-grained control over many of the page elements
		(without having to resort to other more complex workarounds to achieve the same effect, such as one-column templates,
		hiding elements with styles and endless page-targeted CSS rules that clutter your stylesheets etc.)</p>";

	$html .= "<p>These Metabox override settings take priority over filtered theme settings. (ie. Settings -&gt; Filters -&gt; Overrides)
	Currently most override options in the metabox are for display (hiding elements) rather than output (removing them.)
		When set, CSS style rules (with <i>!important</i>) are added to that page to achieve the desired effect.<br>
		[Display Overrides post meta key <i>_bioship_display_overrides</i> (pre-2.1.0: <i>_displayoverrides</i>)]<br>
		[Templating Overrides post meta key <i>_bioship_templating_overrides</i> (pre-2.1.0: <i>_templatingoverrides</i>)]</p>";

	// === Content Tab ===
	$html .= "<h3>Content Override Tab</h3>";

		// --- thumbnail override ---
		$html .= "<h4>Thumbnail / Featured Image</h4>";
		$html .= "Override default output thumbnail size (or none), and display (hide) override for thumbnail image.<br>";

		// --- content display overrides ---
		$html .= "<h4>Content Display Overrides</h4>";
		$html .= "Hide Display of: <i>Title</i>, <i>Subtitle</i>, <i>Top Meta Line</i>, <i>Bottom Meta Line</i>, <i>Author Bio</i><br>";
		// TODO: swap meta positions, author bio position ?

		// --- content filters overrides ---
		$html .= "<h4>Content Filters</h4>";
		$html .= "<p>BioShip provides an easy way to turn off some in-built WordPress content filters on a post/page basis via the metabox.
			This can be handy if they are mangling your content for a particular post for some reason and you want an easy fix.
			Currently available filters you can disable are: <i>wpautop</i>, <i>wptexturize</i>, <i>convert_smilies</i>, <i>convert_chars</i><br>
			[Filter Overrides post meta key: <i>_bioship_removefilters</i> (pre-2.1.0: <i>_disablefilters</i>)]</p>";

	// === Layout Tab ===
	$html .= "<h3>Layout Override Tab</h3>";

		// --- general layout overrides ---
		$html .= "<h4>General Layout</h4>";
		$html .= "No Wrap Margin (Full Width Page), Hide Header Section, Hide Footer Section<br>";
		// TODO: logo / header texts / header extras - footer extras / site credit ?

		// --- navigation display overrides ---
		$html .= "<h4>Navigation</h4>";
		$html .= "Hide Display of: <i>Main Navigation</i>, <i>Secondary Nav</i>, <i>Header Menu</i>, <i>Footer Menu</i>, <i>Breadcrumbs</i>, <i>PageNavi</i><br>";

	// === Sidebar Tab ===
	$html .= "<h3>Sidebar Override Tab</h3>";

		// --- sidebar overrides ---
		$html .= "<h4>Sidebar Output Overrides</h4>";
		$html .= "Dropdown overrides for Sidebar and/or SubSidebar: <i>Columns</i> (width), <i>Template</i> to use, and <i>Position</i>.<br>
			Special options available under templates for no sidebar output, blank space (empty sidebar), or specifying a custom sidebar template.
			(Content column width is also available in this tab for overriding corresponding column width to match changes in sidebar witdth.)<br>";
		$html .= "[Value Filter] <a href='".$vthemedoclinks['filters']."?filter=skeleton_sidebar_layout_override'>skeleton_sidebar_layout_override</a> - available to set template and position on a conditional basis.<br>";
		$html .= "For more details on Sidebars and related filters see the <a href='".$vthemedoclinks['sidebars']."'>Sidebar Guide</a>.<br>";

		// --- sidebar display overrides ---
		$html .= "<h4>Sidebar Display Overrides</h4>";
		$html .= "Hide Display of: <i>Sidebar</i>, <i>SubSidebar</i>, <i>Header Widgets</i>, <i>Footer Widgets</i>, <i>Footer 1/2/3/4</i><br>";

	// === Styles Tab ===
	$html .= "<h3>PerPost Styles Tab</h3>";
	$html .= "<p>You can add CSS style rules for a post/page through this metabox field. Handy if you need to tweak a specific post or page display,
		without having all these post-specific rules clogging up your global stylesheet and thus loaded sitewide on every page.
		These rules are checked for singular pages and output in the &lt;head&gt; section of the page on display automatically.
		There is also an expand/collapse link for better access to viewing and editing this stylesheet rules textarea. There is also
		an added <i>Quicksave CSS</i> button for faster changes so you don't have to Update the post just for them to take effect.<br>
		[PerPost Styles post meta key: <i>_bioship_perpoststyles</i> (pre-2.1.0: <i>_perpoststyles</i>]</p>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}

// ---------------------
// === Value Filters ===
// ---------------------

function bioship_docs_filter_list($wrap) {

	$page = 'filters'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Value Filters</h2><br>';}

	if (isset($_REQUEST['filter'])) {$selected = $_REQUEST['filter'];} else {$selected = '';}
	$html .= "<!-- Selected Filter: ".$selected." -->";

	// 2.1.1: update filter examples file location to /admin/ (previously /child/)
	if ($wrap) {$filterfile = dirname(__FILE__).'/filters.php';}
	else {$filterfile = get_template_directory().'/admin/filters.php';}

	// 2.0.7: do not use file_get_contents - just to pass Theme Check
	// ref: https://wordpress.stackexchange.com/questions/166161/why-cant-the-wp-filesystem-api-read-googlefonts-json#comment240513_166172
	// $filterdocs = file_get_contents($filterfile);
	$filearray = file($filterfile);
	$filterdocs = implode('', $filearray);

	// TODO: get/move filter file introduction here ?

	// skip filter section index as creating it

	$i = 0; // section index
	while (strstr($filterdocs, '/==')) {

		// --- get next section heading ---
		$pos = strpos($filterdocs, '/==');
		$filterdocs = substr($filterdocs, $pos, strlen($filterdocs));
		$pos = strpos($filterdocs, '==/');
		$heading = substr($filterdocs, 0, $pos);
		$filterdocs = substr($filterdocs, ($pos+3), strlen($filterdocs));
		$heading = trim( str_replace(array('=', '/', "\n"), '', $heading) );
		// echo $heading;
		$sections[$i]['title'] = $heading;
		$sections[$i]['name'] = strtolower(str_replace(' ', '-', $heading));

		// --- get filters in section ---
		$pos = strpos($filterdocs, '// /==');
		$list = substr($filterdocs, 0, $pos);
		$filterdocs = substr($filterdocs, $pos, strlen($filterdocs));
		$pos = strpos($filterdocs, '==/');
		$filterdocs = substr($filterdocs,($pos+3), strlen($filterdocs));

		// --- get section comments ---
		$pos = strpos($filterdocs,'// /=');
		$comments = trim( substr($filterdocs, 0, $pos) );
		$sections[$i]['comments'] = str_replace('//', '', $comments);
		// echo $sections[$i]['comments'];
		$filterdocs = substr($filterdocs, $pos, strlen($filterdocs));

		// --- add to filter list ---
		$list = trim( str_replace('//', '', $list) );
		$list = explode("\n", $list);
		$j = 0;
		foreach ($list as $filter) {$list[$j] = trim($filter); $j++;}
		$sections[$i]['filters'] = $list;
		// print_r($list);

		// --- get section content ---
		$pos = strpos($filterdocs, '/==');
		$sections[$i]['content'] = substr($filterdocs, 0, $pos);
		// echo "(((".$sections[$i]['content'].")))";

		$filterdocs = substr($filterdocs, $pos, strlen($filterdocs));
		$pos = strpos($filterdocs, '// /==');
		$endpos = strpos($filterdocs, '// /===== END FILTERS');
		if ($pos == $endpos) {$filterdocs = '';}

		$i++;
	}
	// print_r($sections);

	// 2.1.1: use simple section index
	foreach ($sections as $i => $section) {

		$list = $section['filters'];
		$content = $section['content'];

		$j = 0; // reset filter index
		foreach ($list as $filter) {

			$filter = trim($filter);
			$filters[$filter]['slug'] = strtolower(str_replace('_', '-', $filter));

			if (!strstr($content, $filter)) {
				if (THEMEDOCDEBUG) {
					echo "Filter not found: ".$filter."<br>".PHP_EOL;
					echo "<div>".$content."</div><br>".PHP_EOL;
				}
			} else {
				$pos = strpos($content, $filter);
				$tempa = substr($content, 0, $pos);
				$tempb = substr($content, $pos, strlen($content));
				$posa = strrpos($tempa,'/=');
				$tempc = substr($tempa, ($posa+2), strlen($tempa));
				$posb = strpos($tempc, '=/');
				$filters[$filter]['name'] = trim(substr($tempc, 0, $posb));
				$posc = strrpos($tempa, 'add_filter');
				$example = trim( substr($tempa, $posc, strlen($tempc)) );
				$tempa = substr($tempa, 0, $posa);

				if (strstr($tempb, '/=')) {$pos = strpos($tempb, '/=');}
				else {$pos = strlen($tempb);}
				$example .= substr($tempb, 0, $pos);
				$tempb = trim( substr($tempb, $pos, strlen($tempb)) );

				if (THEMEDOCDEBUG) {
					if (!strstr($example, 'return')) {
						echo "Warning: no return in example for ".$filter.":<br>".PHP_EOL;
						echo $filters[$filter]['content'];
					}
				}

				if (substr($example, -3, 3) == '// ') {$example = substr($example, 0, (strlen($example)-3));}
				// echo "(((".$example.")))";

				$filters[$filter]['example'] = $example;

				$content = $tempa.$tempb;

				// --- increment filter index
				$j++;
			}
		}
	}
	// print_r($filters);

	// --- toggle scripts ---
	$html .= "<script>
	function togglesection(section) {
		sectionid = 'section-'+section;
		if (document.getElementById(sectionid).style.display == 'none') {
			document.getElementById(sectionid).style.display = '';
		} else {document.getElementById(sectionid).style.display = 'none';}
	}
	function togglefilter(filter) {
		filterid = 'filter-'+filter;
		if (document.getElementById(filterid).style.display == 'none') {
			document.getElementById(filterid).style.display = '';
		} else {document.getElementById(filterid).style.display = 'none';}
	}".PHP_EOL;
	if ($selected != '') {$html .= "scrolltohash('".$selected."');";}
	$html .= "</script>";

	// Conditional Filter note...
	// $html .= '<b>Note</b>: For modifying options in different contexts, see <a href="'.$vthemedoclinks['filters'].'">Conditional Filter Examples</a>.<br><br>';

	// --- loop sections to create html ---
	foreach ($sections as $section) {

		// --- loop filters in section ---
		$filtershtml = ''; $foundfilter = false;
		foreach ($section['filters'] as $filter) {

			$filtershtml .= '<a name="'.$filter.'"></a>';
			$filtershtml .= '<h4><a href="javascript:void(0);" onclick="togglefilter(\''.$filters[$filter]['slug'].'\');">'.$filter." : ".$filters[$filter]['name'].'</a></h4>';
			if ($selected == $filter) {$foundfilter = true; $hide = '';} else {$hide = ' style="display:none;"';}
			$filtershtml .= '<div id="filter-'.$filters[$filter]['slug'].'"'.$hide.'>';
			$filtershtml .= '<pre><code>'.$filters[$filter]['example'].'</code></pre>';
			$filtershtml .= '</div>'; // end filter
		}

		if (!$foundfilter) {$hide = ' style="display:none;"';} else {$hide = '';}
		$sectionhtml = '<h3><a href="javascript:void(0);" onclick="togglesection(\''.$section['name'].'\');">'.$section['title'].'</a></h3>';
		$sectionhtml .= '<div id="section-'.$section['name'].'"'.$hide.'>';

		$html .= $sectionhtml.$filtershtml;
		$html .= '</div>'; // end section
	}

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// ===========
// HIERARCHIES
// ===========

// ----------------------
// === File Hierarchy ===
// ----------------------

function bioship_docs_file_hierarchy($wrap) {

	$page = 'files'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Files and Hierarchy</h2><br>';}

	$html .= "<p>BioShip uses an extended file hierarchy so you can easily override <b>any</b> of the Core Theme Files,
		Javascript and CSS Stylesheets by copying the existing files to your Child Theme and modifying them.</p>";

	$html .= "You can also override any Page Templates, Content Templates, Sidebar Templates etc. or create new ones
		for Custom Post Types or Post Formats etc. (see the <a href='".$vthemedoclinks['templates']."'>Template Hierarchy Guide</a> for further details on those.)</p>";

	$html .= "<h3>BioShip File Hierarchy</h3>";
	$html .= "<p>The file hierarchy will search for the file in this order and use the first matching file it finds:<br>
		<ol><li>the Child Theme subdirectories (if relevant)</li>
		<li>the main Child Theme directory (ie. 'Stylesheet' directory)</li>
		<li>the Parent Theme subdirectories (if relevant)</li>
		<li>then main Parent Theme directory (ie. 'Template' directory)</li></ol>";
	$html .= "For example, this allows you to override the Parent Theme (Framework) Stylesheets, Javascripts or Images with ease...</p>";

	$html .= "<h5>CSS Stylesheets</h5>";
	$html .= "<i>/wp-content/themes/bioship/styles/</i><br>";
	$html .= "eg. copy <i>/bioship/styles/stylesheet.css</i> to <i>/child-theme/styles/stylesheet.css</i><br>";
	$html .= "(or if you prefer, you can also use <i>/css/</i> or <i>/assets/css/</i> instead, eg. <i>/child-theme/css/stylesheet.css</i>)<br>";
	$html .= "[Note: <i>custom.css</i> will be auto-loaded if found in either child or parent theme.]<br>";

	$html .= "<h5>Javascripts</h5>";
	$html .= "<i>/wp-content/themes/bioship/javascripts/</i><br>";
	$html .= "eg. copy <i>/bioship/javascripts/scriptname.js</i> to <i>/child-theme/javascripts/scriptname.js</i><br>";
	$html .= "(or if you prefer, you can also use <i>/js/</i> or <i>/assets/js/</i> instead, eg. <i>/child-theme/js/scriptname.js</i>)<br>";
	$html .= "[Note: <i>custom.js</i> will be auto-loaded if found in either child or parent theme.]<br>";

	$html .= "<h5>Images</h5>";
	$html .= "<i>/wp-content/themes/bioship/images/</i><br>";
	$html .= "eg. copy <i>/bioship/image/image.png</i> to <i>/child-theme/image/image.png</i><br>";
	$html .= "(or if you prefer, you can also use <i>/img/</i> or <i>/assets/img/</i> instead, eg. <i>/child-theme/img/image.png</i>)<br>";
	$html .= "[Note: <i>gravatar.png</i> will be used for default Gravatar if found in either child or parent theme.]<br>";


	$html .= "<h3>Core Theme File Hierarchy</h3>";

	$html .= "<p>The File Hierarchy also allows you to override Core Theme Files, though in most cases this would be unnecessary,
		as generally speaking you would leave these alone for Parent Theme updates and modify individual functions instead.</p>";

	$html .= "<i>All</i> the core theme functions have been made pluggable (overrideable) - with minor exceptions (see below.)
		(In other words each function declaration is wrapped in conditional <i>function_exists</i> checks to make this possible.)
		So to override any function, simply place a modified copy of it in your Child Theme's <i>functions.php</i> file,
		As WordPress intentionally loads this file <i>before</i> the Parent Theme's <i>functions.php</i>, the modified function
		will be loaded <i>instead</i> of the default Parent Theme (Framework) function. (see <a href='http://codex.wordpress.org/Child_Themes' target=_blank>Child Themes on the WordPress Codex</a>.)</p>";

	$html .= "(Note: although that is the preffered method, you may still use the File Hierarchy for development overrides however.
		For example, if you have found and fixed a bug in the Parent Theme <i>skin.php</i> or <i>grid.php</i>.
		As these files do <i>not</i> contain pluggable functions, you can put a modified copy of any of them in your Child Theme
		instead and report the bug for the next BioShip update, then recheck those fixes when the update comes out.)</p>";

	$html .= "<h4>Core Theme Files</h4>";
	$html .= "<i>/wp-content/themes/bioship</i><br>";

	$html .= "<table>
		<tr><td><b>Theme Setup Files</b></td></tr>
		<tr><td>functions.php</td><td>Theme Setup and Loader</td></tr>
		<tr><td>options.php</td><td>Theme Options and Fonts</td></tr>
		<tr><td>hooks.php</td><td>Layout Hooks and Labels</td><td>[Definitions with Value Filter: <a href='".$vthemedoclinks['filters']."?filter=skeleton_theme_hooks'>skeleton_theme_hooks</a>]</td></tr>
		<tr><td>compat.php</td><td>Theme Backwards Compatibility</td></tr>

		<tr><td><b>Core Layer Files</b></td></tr>
		<tr><td>skull.php</td><td>Theme Helpers and Head Setup</td></tr>
		<tr><td>skeleton.php</td><td>Skeleton Page Templating Functions</td></tr>
		<tr><td>muscle.php</td><td>Muscle Extended Theme Functions</td></tr>
		<tr><td>skin.php</td><td>Skin Dynamic CSS  Styles Output</td><td>[standalone, functions not pluggable]</td></tr>
		<tr><td>grid.php</td><td>Grid System Dynamic CSS Output</td><td>[standalone, functions not pluggable]</td></tr>

		<tr><td><b>Admin Files</b></td></tr>
		<tr><td>admin/admin.php</td><td>Admin-only Functions</td></tr>
		<tr><td>admin/customizer.php</td><td>Customizer Integration</td></tr>
		<tr><td>admin/tracer.php</td><td>Theme Function Debug Tracer</td></tr>
		<tr><td>admin/docs.php</td><td>Documentation Generator</td></tr>
		</table>";

	$html .= "<h4>Default Template Files</h4>";
	$html .= "(see <a href='".$vthemedoclinks['templates']."'>Template Hierarchy Guide</a> for much more detailed information.)<br>";

	// 2.1.1: fix to type (index-loop)
	$html .= "<table><tr><td><b>Base Templates</b></td></tr>
		<tr><td>header.php</td><td>Default Header Template</td></tr>
		<tr><td>index.php</td><td>Default Index Template</td></tr>
		<tr><td>loop-index.php</td><td>Default Loop Template</td></tr>
		<tr><td>footer.php</td><td>Default Footer Template</td></tr>
		<tr><td><b>Template Directories</b></td></tr>
		<tr><td>/content/</td><td>Content Templates</td></tr>
		<tr><td>/content/format/</td><td>Post Format Templates</td></tr>
		<tr><td>/sidebar/</td><td>Sidebar Templates</td></tr>
		<tr><td>/templates/</td><td><i>Third Party</i> Templates</td></tr></table>";

	$html .= "<h4>Library Hierarchy</h4>";

	$html .= "<p>While the File Hierarchy also works for included libraries, if you want to use it for modifying a library,
		you will need to copy the <i>entire library directory and subdirectories</i> to your Child Theme to do so.
		This is because it will find the library loader file path and the library will load files <i>relative to that</i>.</p>";

	$html .= "<table>
		<tr><td><b>Library Directories</b></td><td></td></tr>
		<tr><td>/includes/</td><td width='20'></td><td>Default Includes</b></td></tr>
		<tr><td>/includes/titan/</td><td width='20'></td><td>Titan Framework</td></tr>
		<tr><td>/includes/options/</td><td width='20'></td><td>Options Framework</td></tr>
		<tr><td>/includes/kirki/</td><td width='20'></td><td>Kirki Library</td></tr>
		<tr><td>/includes/hybrid2/</td><td width='20'></td><td>Hybrid Core 2</td></tr>
		<tr><td>/includes/hybrid3/</td><td width='20'></td><td>Hybrid Core 3</td></tr>
		<tr><td>/includes/hybrid-hook/</td><td width='20'></td><td>Hybrid Hook</td></tr>
		<tr><td>/includes/foundation5/</td><td width='20'></td><td>Foundation 5</td></tr>
		<tr><td>/includes/foundation6/</td><td width='20'></td><td>Foundation 6</td></tr>
		</table>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// --------------------------
// === Template Hierarchy ===
// --------------------------

function bioship_docs_template_hierarchy($wrap) {

	$page = 'templates'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open('templates').'<h2>BioShip Template Hierarchy</h2><br>';}

	$html .= '<p>BioShip of course supports the default <a href="https://developer.wordpress.org/themes/basics/template-hierarchy/">WordPress Template Hierarchy</a>.
		So you can still implement these top level page templates overrides as you would in a standard theme (eg. <i>page.php</i>, <i>home.php</i> etc.)</p>';

	$html .= '<p>Remember, due to the <a href="'.$vthemedoclinks['files'].'">File Hierarchy</a>, <i>all</i> template files parts are searched for in your Child Theme directory first.
		(This is ideally where you would be placing any customized templates anyway so they are all preserved in Parent Theme updates.)
		In all cases the Template Hierarchy will fall back to your Parent Theme (BioShip Framework) if there is no custom template found.</p>';

	$html .= '<p>However, BioShip extends upon this default system in many different ways to make templating way more flexible.
		This of course could make it complex and unfamiliar as well - hence this guide is here to make it comprehensible!</p>';

	$html .= '<p>First, by default, it uses a single <i>index.php</i>, <i>loop-index.php</i> and <i>content/content.php</i> to handle <i>ALL</i> page conditions.
		This keeps things super clean and means you can copy these default templates as starting points for custom ones.
		It also means if you prefer, there is no need at all use the template hierarchy - but it is available if needed.
		You can instead use the in-built combination of <a href="'.$vthemedoclinks['filters'].'">Value Filters</a> and <a href="'.$vthemedoclinks['hooks'].'">Layout Hooks</a> for customizations.
		And since one of the most common thing a project needs is custom sidebars, for that see the <a href="'.$vthemedoclinks['sidebars'].'">Sidebar Guide</a>.</p>';

	$html .= '<p>Read on for deeper customizations, as you can override sublevel templates and parts at just about any level also...
		This is done by simply creating a template file with the proper naming convention to match the desired page condition.
		Again, none of these need to be used by default, but are made available for the most flexible template system possible.</p>';

	$html .= "<table>
	<tr><td><h4>Base Template Files</h4></td><td><i>/wp-content/bioship/</i></td></tr>
		<tr><td>header.php</td><td>Default Header Template</td></tr>
		<tr><td>index.php</td><td>Default Index Template</td></tr>
		<tr><td>loop-index.php</td></td><td>Default Loop Template</td></tr>
		<tr><td>footer.php</td><td>Default Footer Template</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='3'>There are several ways to change the loop, header and footer template that is used...</td></tr>
	<tr><td colspan='3'>First you can make an unchanged copy of <i>index.php</i> (eg. <i>page.php</i> or <i>archive.php</i>)</td></tr>
	<tr><td colspan='3'>and this will automatically load <i>loop/page.php</i> or <i>loop-page.php</i> (you could manually change it too.)</td></tr>
	<tr><td colspan='3'>This will work for more specific templates too, eg. <i>taxonomy-term.php</i> would auto-load <i>loop-taxonomy-term.php</i></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='2'><h5>Loop Templates</h5></td></tr>
		<tr><td>loop/{string}.php</td><td>loop-{string}.php</td><td>[Value Filter:string] <a href='".$vthemedoclinks['filters']."?filter=skeleton_loop_template'>skeleton_loop_template</a> (no default)</td></tr>
		<tr><td>loop/{filename}.php</td><td>loop-{filename}.php</td><td>matches base template filename eg. home.php -> loop-home.php</td></tr>
		<tr><td>loop/{pagecontext}.php</td><td>loop-{pagecontext}.php</td><td>frontpage, home, 404, search</td></tr>
		<tr><td>loop/{archivecontext}.php</td><td>loop-{archivecontext}.php</td><td>[archive only] category, taxonomy, tag, author, date, archive (fallback)</td></tr>
		<tr><td>loop/{post-type}.php</td><td>loop-{post-type}.php</td><td>[singular only] {post-type}</td></tr>
		<tr><td>loop/index.php</td><td>loop-index.php</td><td>Default Loop Template</td></tr>
		<tr><td>Hierarchy</td><td>Array Filter</td><td>[Value Filter:array] <a href='".$vthemedoclinks['filters']."?filter=skeleton_loop_templates'>skeleton_loop_templates</a></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='2'><h5>Header Templates</h5></td></tr>
		<tr><td>header/{string}.php</td><td>header-{string}.php</td><td>[Value Filter:string] <a href='".$vthemedoclinks['filters']."?filter=skeleton_header_template'>skeleton_header_template</a> (no default)</td></tr>
		<tr><td>header/{filename}.php</td><td>header-{filename}.php</td><td>matches base template filename, eg. home.php -> header-home.php</td></tr>
		<tr><td>header/{pagecontext}.php</td><td>header-{pagecontext}.php</td><td>frontpage, home, 404, search</td></tr>
		<tr><td>header/{archivecontext}.php</td><td>header-{archivecontext}.php</td><td>[archive only] category, taxonomy, tag, author, date, archive (fallback)</td></tr>
		<tr><td>header/{pagecontext}.php</td><td>header-{pagecontext}.php</td><td>[singular only] {post-type}</td></tr>
		<tr><td>header/index.php</td><td>header-index.php</td><td>Default Header Template</td></tr>
		<tr><td>Hierarchy</td><td>Array Filter</td><td>[Value Filter:array] <a href='".$vthemedoclinks['filters']."?filter=skeleton_header_templates'>skeleton_header_templates</a></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='2'><h5>Footer Templates</h5></td></tr>
		<tr><td>footer/{string}.php</td><td>footer-{string}.php</td><td>[Value Filter:string]<a href='".$vthemedoclinks['filters']."?filter=skeleton_footer_template'>skeleton_footer_template</a> (no default)</td></tr>
		<tr><td>footer/{filename}.php</td><td>footer-{filename}.php</td><td>matches base template filename, eg. home.php -> header-home.php</td></tr>
		<tr><td>footer/{pagecontext}.php</td><td>footer-{pagecontext}.php</td><td>frontpage, home, 404, search</td></tr>
		<tr><td>footer/{archivecontext}.php</td><td>footer-{archivecontext}.php</td><td>[archive only] category, taxonomy, tag, author, date, archive (fallback)</td></tr>
		<tr><td>footer/{post-type}.php</td><td>footer-{post-type}.php</td><td>[singular only] {post-type}</td></tr>
		<tr><td>footer/index.php</td><td>footer-index.php</td><td>Default Footer Template</td></tr>
		<tr><td>Hierarchy</td><td>Array Filter</td><td>[Value Filter:array] <a href='".$vthemedoclinks['filters']."?filter=skeleton_footer_templates'>skeleton_footer_templates</a></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='3'>The Content Template Hierarchy handles all different templates for Custom Post Types and Post Formats.</td></tr>
	<tr><td colspan='3'>In other words, if a specific matching template is not found, it falls back to the first one that is found.</td></tr>
	<tr><td colspan='3'>Note: For consistency, the Hybrid content template hierarchy whether full Hybrid Core is activated or not.</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='2'><h3>Content Template Hierarchy</h3></td></tr>
		<tr><td>content/attachment-{mimetype}.php</td><td>content-attachment-{mimetype}.php</td><td>if is_attachment()</td></tr>
		<tr><td>content/{posttype}-{postformat}.php</td><td>content-{posttype}-{postformat}.php</td><td>combination of post type and post format</td></tr>
		<tr><td>content/{postformat}.php</td><td>content-{postformat}.php</td><td>aside, audio, chat, image, gallery, link, quote, status, video</td></tr>
		<tr><td>content/{posttype}.php</td><td>content-{posttype}.php</td><td>post, page or other custom post type</td></tr>
		<tr><td>content.php</td><td>content/content.php</td><td>Default Content Template</td></tr>
		<tr><td>Hierarchy</td><td>Array Filter</td><td>[Value Filter:array] <a href='".$vthemedoclinks['filters']."?filter=hybrid_content_hierarchy'>hybrid_content_hierarchy</a></td></tr>
		<tr><td>Directory</td><td>String Filter</td><td>[Value Filter:string] <a href='".$vthemedoclinks['filters']."?filter=skeleton_content_template_directory'>skeleton_content_template_directory</a></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='3'>If you wish to use a split hierarchy you can optionally create and use an /archive/ template directory also.</td></tr>
	<tr><td colspan='3'>Again, this hierarchy falls back to the Content Template Hierarchy after, which handles all page conditions.</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td colspan='2'><h4>Archive Template Hierarchy</h4> (optional)</td></tr>
		<tr><td>archive/attachment-{mimetype}.php</td><td><i>optional template directory</i></td><td>if is_attachment()</td></tr>
		<tr><td>archive/{posttype}-{postformat}.php</td><td><i>optional template directory</i></td><td>combination of post type and post format</td></tr>
		<tr><td>archive/{postformat}.php</td><td><i>optional template directory</i></td><td>aside, audio, chat, image, gallery, link, quote, status, video</td></tr>
		<tr><td>archive/{posttype}.php</td><td><i>optional template directory</i></td><td>post, page or other custom post type</td></tr>
		<tr><td>archive/content.php</td><td><i>optional template directory</i></td><td>Archive Content Template</td></tr>
		<tr><td>Directory</td><td>String Filter</td><td>[Value Filter:string] <a href='".$vthemedoclinks['filters']."?filter=skeleton_archive_template_directory'>skeleton_archive_template_directory</a></td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><h4>Other Content Templates</h4></td><td><i>/wp-content/bioship/content/</i></td></tr>
		<tr><td>author-bio.php</td><td>Default Author Bio Template</td></tr>
		<tr><td>loop-nav.php</td><td>Default Loop Navigation Template</td></tr>
		<tr><td>loop-meta.php</td><td>Default Loop Meta Template</td></tr>
		<tr><td>error.php</td><td>Default Error Page Template</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><h4>Comments Template Hierarchy</h4></td><td><i>/wp-content/bioship/content/</i></td></tr>
		<tr><td>comments-{posttype}-{postformat}.php</td><td>[not implemented yet]</td></tr>
		<tr><td>comments-{postformat}.php</td><td>[not implemented yet]</td></tr>
		<tr><td>comments-{posttype}.php</td><td>Custom Post Type Comments Template</td></tr>
		<tr><td>comments.php</td><td>Default Main Comments Template</td></tr>
		<tr><td>comments-nav.php</td><td>Default Comments Navigation Template</td></tr>
		<tr><td>comments-error.php</td><td>Default Comments Error Template</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><h4>Sidebar Template Hierarchy</h4></td><td><i>/wp-content/bioship/sidebar/</i></td></tr>
	<tr><td colspan='3'>Sidebar and SubSidebar Templates are available by default for all sidebar contexts.<br>
		see the <a href='".$vthemedoclinks['sidebars']."'>BioShip Sidebar Guide</a> for more detailed information.</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><h5>Third Party Templates</h5></td><td><i>/wp-content/bioship/templates/</i></td></tr>
		<tr><td>/templates/ajax-load-more/</td><td><a href='https://wordpress.org/plugins/ajax-load-more/' target=_blank>AJAX Load More</a> Repeater Template</td><td>copy/paste into Repeater Template field</tr>
		<tr><td>/templates/theme-my-login/</td><td><a href='https://wordpress.org/plugins/theme-my-login/' target=_blank>Theme My Login</a> Templates</td><td>modified for Theme Integration</td></tr>
		<tr><td>/templates/woocommerce/</td><td>Alternative WooCommerce Templates</td><td>can be used <i>instead of</i> the default <i>/woocommerce/</i></td></tr>
		<tr><td>/templates/skeleton/</td><td>Legacy Skeleton Templates</td><td>for reference only [deprecated]</td></tr>
		<tr height='20'><td> </td></tr>
	</table>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// ======
// LAYOUT
// ======

// ---------------------
// === Sidebar Guide ===
// ---------------------

function bioship_docs_sidebar_guide($wrap) {

	$page = 'sidebars'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Sidebar Guide</h2><br>';}

	$html .= "<p>BioShip uses a uniquely flexible Sidebar template system to avoid using restrictive page layout templates.
		Sidebars output is pre-calculated in <i>skull.php</i> so that &lt;body&gt; classes and other tag classes can be set for the sidebar states.</p>";

	$html .= "<p>Another difference you will find from other themes is that all possible sidebars are <i>registered regardless of their setting display state</i>.
		This is so the you can add widgets to them while inactive, which also allows you to use 'inactive' otherwise sidebars in filters or perpost overrides.
		Inactive sidebars are however lowercase and styled differently on the widgets page - and listed separate for ease of use.</p>";

	$html .= "<h4>Sidebar Template Override Usage</h4>";
	$html .= "The values for the sidebar template array are calculated based on the Theme Settings and page context then filtered.<br>
		It is numerical index array of four sidebar template values (filenames without the .php extension) for these positions:<br><br>";
	$html .= "<table cellpadding='10' cellspacing='10'>
		<td width='20'></td><td>[<b>0</b>] : Outer Left Sidebar Template</td>
		<td width='20'></td><td>[<b>1</b>] : Inner Left Sidebar Template</td>
		<td width='20'></td><td><i>Content Columns Area</i></td>
		<td width='20'></td><td>[<b>2</b>]: Inner Right Sidebar Template</td>
		<td width='20'></td><td>[<b>3</b>] : Outer Right Sidebar Template</td>
	</tr></table><br>";

	$html .= "<p>The main filter used to override this layout is <a href='".$vthemedoclinks['filters']."?filter=skeleton_sidebar_layout_override'>skeleton_sidebar_layout_override</a>.
		This allows you to conditionally use any sidebar in any of the possible positions for complete layout flexibility.
		Simply set one or two of the four template positions to a template name you wish to use (leaving the other two empty.)
		Note: when setting <i>opposing sidebars</i>, you can use either position 0 or 1 for left, and either position 2 or 3 for right.</p>";

	$html .= "<h6>Override Array Examples</h6>";
	$html .= "Post with left Sidebar and opposite SubSidebar: Array ( [0] => 'post', [1] => '', [2] => 'subpost', [3] => '' )<br>";
	$html .= "Post with left Sidebar and opposite SubSidebar: Array ( [0] => 'post', [1] => '', [2] => 'subpost', [3] => '' )<br>";
	$html .= "Post with left Sidebar and internal SubSidebar: Array ( [0] => 'post', [1] => 'subpost', [2] => '', [3] => '' )<br>";
	$tml .= "Post with left Sidebar and external SubSidebar: Array ( [0] => 'subpost', [1] => 'post', [2] => '', [3] => '' )<br>";
	$html .= "Page with right sidebar only: Array ( [0] => '', [1] => '', [2] => 'page', [3] => '' )<br>";

	$html .= "<h6>Override Filter Example</h6>";
	$html .= "This example changes the templates used for single and archive template for a custom post type of 'portfolio'.<br>";
	$html .= '<pre style="font-family:Consolas, "Lucida Console", Monaco, FreeMono, monospace; background-color:#EEEEEE">';
	$html .= "add_filter('skeleton_sidebar_layout_override','my_portfolio_sidebars');".PHP_EOL;
	$html .= "function my_portfolio_sidebars(\$sidebars) {".PHP_EOL;
	$html .= "    if (get_post_type() == 'portfolio') {".PHP_EOL;
	$html .= "        // use portfolio.php for right sidebar and subportfolio.php for external right subsidebar".PHP_EOL;
	$html .= "        if (is_singular()) {\$sidebars = array('','','portfolio','subportfolio'};}".PHP_EOL;
	$html .= "        // use portfolio-archive.php for left sidebar and subportfolio-archive.php for right subsidebar".PHP_EOL;
	$html .= "        elseif (is_archive()) {\$sidebars = array('portfolio-archive','','','subportfolio-archive');}".PHP_EOL;
	$html .= "    }".PHP_EOL;
	$html .= "    // to preserve other sidebar behaviour, *always* return the first argument of the filter function".PHP_EOL;
	$html .= "    return \$sidebars;".PHP_EOL;
	$html .= "}".PHP_EOL."</pre><br>";
	// TODO: more filter examples..?

	$html .= "<h5>PerPost Metabox Overrides</h5>";
	$html .= "<p>Sidebar templates can also be overridden on a post-by-post basis using the Theme Settings Metabox on the Writing Screen.
		Note that using these settings will override the filtered sidebar settings also, not just the default sidebar settings.
		Overrides are available for the sidebar templates, sidebar positions and sidebar widths. (Content width is available on this tab also.)
		For more information see the <a href='".$vthemedoclinks['metabox']."'>BioShip Theme Metabox</a> section.</p>";

	$html .= "<h5>Template Hierarchy</h5>";
	$html .= "Instead of overriding the template setting used, you can also override any Sidebar Template at a file level.
		The <a href='".$vthemedoclinks['files']."'>BioShip File Hierarchy</a> ensures that the Child Theme sidebar template is used instead if it exists.
		All default sidebar templates are available in the Parent Theme ready to copy to the Child Theme
		(each is almost exactly the same - it checks for active widgets in that sidebar and outputs if found.)
		Note all subsidiary templates are prefixed with 'sub' to distinguish them from primary sidebar templates.</p>";

	$html .= "<h3>Sidebar Template List</h3>";
	$html .= "<i>/wp-content/bioship/sidebar/</i><br>";
	$html .= "<table>
		<tr><td>File</td><td><b>Description</b></td><td><b>Conditions</b></td><td><b>Fallback</b></td></tr>
		<tr><td><b>Header and Footer Widget Areas</b></td></tr>
		<tr><td>header.php</td><td>Header Widget Area</td><td>theme option</td></tr>
		<tr><td>footer.php</td><td>Footer Widget Areas (1-4)</td><td>theme option (more than zero)</td></tr>
		<tr><td><b>Frontpage and Home Sidebars</b></td></tr>
		<tr><td>front.php</td><td>Primary Frontpage Sidebar</td><td>theme option</td><td>Page/Archive Sidebar*</td></tr>
		<tr><td>subfront.php</td><td>Subsidiary Frontpage Sidebar</td><td>theme option</td><td>Page/Archive SubSidebar*</td></tr>
		<tr><td>home.php</td><td>Primary Frontpage Sidebar</td><td>theme option</td><td>Archive Sidebar</td></tr>
		<tr><td>subhome.php</td><td>Subsidiary Frontpage Sidebar</td><td>theme option</td><td>Archive SubSidebar</td></tr>
		<tr><td><b>Single Post and Page Sidebars</b></td></tr>
		<tr><td>primary.php</td><td>Default Primary Post/Page Sidebar</td><td>Unified Sidebar option only</td></tr>
		<tr><td>subsidiary.php</td><td>Default Subsidiary Post/Page Sidebar &nbsp;</td><td>Unified SubSidebar option only</td></tr>
		<tr><td>post.php</td><td>Default Primary Post Sidebar</td><td>Dual/Posts Sidebar option</td></tr>
		<tr><td>subpost.php</td><td>Default Subsidiary Post Sidebar</td><td>Dual/Posts SubSidebar option<td></td></tr>
		<tr><td>page.php</td><td><td>Default Primary Page Sidebar</td><td>Dual/Pages Sidebar option</td></tr>
		<tr><td>subpage.php</td><td>Default Subsidiary Page Sidebar</td><td>Dual/Pages SubSidebar option<td></td></tr>
		<tr><td><b>Extra Sidebars</b></td></tr>
		<tr><td>blank.php</td><td>Blank (empty) Sidebar</td><td>(creates 'whitespace' columns)</td></tr>
		<tr><td>subblank.php</td><td>Blank (empty) SubSidebar</td><td>(creates 'whitespace' columns)</td></tr>
		<tr><td>search.php</td><td>Searchpage Sidebar</td><td>theme option</td></tr>
		<tr><td>subsearch.php</td><td>Searchpage SubSidebar</td><td>theme option</td></tr>
		<tr><td>notfound.php</td><td>404 Not Found Sidebar</td><td>theme option</td><td>Search (if on)</td><tr>
		<tr><td>subnotfound.php</td><td>404 Not Found SubSidebar</td><td>theme option</td><td>SubSearch (if on)</td></tr>
		<tr height='10'><td>&nbsp;</td></tr>
		<tr><td><b>Main Archive Sidebars</b></td></tr>
		<tr><td>archive.php</td><td>Archive Sidebar</td><td>theme option</td></tr>
		<tr><td>subarchive.php</td><td>Archive SubSidebar</td><td>theme option</td></tr>
		<tr><td><b>Archive-Type Specific Sidebars</b></td></tr>
		<tr><td>category.php</td><td>Category Sidebar</td><td>Category Sidebar on</td></tr>
		<tr><td>subcategory.php</td><td>Category SubSidebar</td><td>Category + Subsidiary on</td></tr>
		<tr><td>taxonomy.php</td><td>Taxonomy Sidebar</td><td>Taxonomy Sidebar on</td></tr>
		<tr><td>subtaxonomy.php</td><td>Taxonomy SubSidebar</td><td>Taxonomy + Subsidiary on</td></tr>
		<tr><td>tag.php</td><td>Tag Sidebar</td><td>Tag Sidebar on</td></tr>
		<tr><td>subtag.php</td><td>Tag SubSidebar</td><td>Tag + Subsidiary on</td></tr>
		<tr><td>author.php</td><td>Author Sidebar</td><td>Author Sidebar on</td></tr>
		<tr><td>subauthor.php</td><td>Author SubSidebar</td><td>Author + Subsidiary on</td></tr>
		<tr><td>date.php</td><td>Date Sidebar</td><td>Date Sidebar on</td></tr>
		<tr><td>subdate.php</td><td>Date SubSidebar</td><td>Date + Subsidiary on</td></tr>
		</table><br>";

	$html .= "<h4>Sidebar Value Filters</h4>";
	$html .= "<table>
		<tr><td align='center'><b>Filter</b></td><td align='center'><b>Description</b></td>
			<td align='center'><b>Valid Values</b></td><td align='center'><b>Default</b></td></tr>
		<tr><td><b>Sidebar Display</b></td></tr>
		<tr><td><i>skeleton_sidebar_hide</i></td><td>Hide Sidebar</td><td><i>true</i> or <i>false</i></td><td>false</td></tr>
		<tr><td><i>skeleton_subsidebar_hide</i></td><td>Hide SubSidebar</td><td><i>true</i> or <i>false</i></td><td>false</td></tr>
		<tr><td><b>Sidebar Output</b></td></tr>
		<tr><td><i>skeleton_fullwidth_filter</i></td><td>No Sidebar or Subsidebar</td><td><i>true</i> or <i>false</i></td><td>false</td></tr>
		<tr><td><i>skeleton_sidebar_output</i></td><td>Output Sidebar</td><td><i>true</i> or <i>false</i></td><td>true</td></tr>
		<tr><td><i>skeleton_subsidebar_output</i></td><td>Output SubSidebar</td><td><i>true</i> or <i>false</i></td><td>true</td></tr>
		<tr><td><b>Sidebar Position</b></td></tr>
		<tr><td><i>skeleton_sidebar_position</i></td><td>Absolute Sidebar Position</td><td>left, right</td><td>theme option</td></tr>
		<tr><td><i>skeleton_subsidebar_position</i></td><td>Relative SubSidebar Position &nbsp;</td><td>internal, external, opposite</td><td>theme option</td></tr>
		<tr><td><b>Sidebar Mode</td></tr>
		<tr><td><i>skeleton_sidebar_mode</i></td><td>Mode for Posts/Pages</td><td>off, postsonly, pagesonly, dual, unified &nbsp;</td><td>theme option</td></tr>
		<tr><td><i>skeleton_subsidebar_mode</i></td><td>Mode for Posts/Pages</td><td>off, postsonly, pagesonly, dual, unified &nbsp;</td><td>theme option</td></tr>
		<tr><td><b>Sidebar Template Override</b></td></tr>
		<tr><td><i>skeleton_sidebar_layout_override</i> &nbsp;</td><td>Full Template Override</td><td>[array]* see above for usage</td><td>calculated state</td></tr>
		</table>";

	$html .= "<h5>Sidebar Width Filters</h5>";
	$html .= "<table>
		<tr><td align='center'><b>Filter</b></td><td align='center'><b>Description</b></td>
			<td align='center'><b>Valid Values</b></td><td align='center'><b>Default</b></td></tr>
		<tr><td><b>Sidebar Column Widths</td></tr>
		<tr><td><i>skeleton_sidebar_columns</i></td><td>Sidebar Column Width</td><td><i>numeric</i> or <i>number-word</i> (eg. four)</td><td>theme option</td></tr>
		<tr><td><i>skeleton_subsidebar_columns</i> &nbsp;</td><td>SubSidebar Column Width &nbsp;</td><td><i>numeric</i> or <i>number-word</i> (eg. two)</td><td>theme option</td></tr>
		<tr><td><b>Related Width Filters</td></tr>
		<tr><td colspan='3'>If you are modifying sidebar columns via a filter you may need to adjust these values to match.</td></tr>
		<tr><td><i>skeleton_content_columns</i></td><td>Content Column Width</td><td><i>numeric</i> or <i>number-word</i> (eg. ten)</td><td>theme option</td></tr>
		<tr><td><i>skeleton_grid_columns</i></td><td>Total Grid Columns</td><td><i>numeric</i> or <i>number-word</i> (eg. twenty)</td><td>theme option</td></tr>
		</table>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// -------------------
// === Grid System ===
// -------------------

function bioship_docs_grid_system($wrap) {

	$page = 'grid'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open('grid').'<h2>BioShip Grid System</h2><br>';}

	// 2.1.1: moved example styles to wrapper open function

	// --- add content wrapper for grid ---
	if ($wrap) {$html .= '<div id="content">';}

	$html .= "<p>The BioShip Grid System is built primarily upon the Skeleton Boilerplate grid system.
		It also incorporates some aspects of other grid systems such as 960GS and Blueprint.</p>";

	$html .= "<h4>Main Layout Grid</h4>";
	$html .= "<p>The main BioShip theme display template is based upon columns with <b>em</b> based unit widths.
		This means the grid scales flexibly and well according to browser zoom and base font size.
		The main #wrap container contains the header, footer, sidebars and content area.
		All of the main grid display is worked out automatically based on your theme settings.
		It is worth remembering that sidebar, subsidebar and content column widths of any page
		should add to the total grid columns set in your theme settings (the default is sixteen.)</p>";

	$html .= "<h4>Content Grid</h4>";
	$html .= "<p>Within the page #content area, there is an optional content grid available that uses <b>%</b> based widths.
		The number of grid columns can be set in theme settings for the <i>container</i> class (default twentyfour.)
		Alternatively you can set an explicit number of grid columns using a specific container class of:<br>
		<i>container_12</i>, <i>container_16</i>, <i>container_20</i>, or <i>container_24</i><br>";

	$html .= "<h5>Device Width Breakpoints</h5>";
	$html .= "<p>Both the Main Layout Grid and Content Grid reponsively resize according to device screen width.
		The default device breakpoints are 320px, 480px, 640px, 768px, 960px, 1140px and 1200px.
		You can change the number and value of these breakpoints via the theme options screen.
		(Note that any breakpoint larger than the <i>maximum</i> layout width set will be ignored.)</p>";

	$html .= "<h4>Content Grid Syntax</h4>";
	$html .= "Rather than using shortcodes, content columns can be generated using simple &lt;div&gt; element classes.<br>";

	$html .= '<pre style="font-family:Consolas, "Lucida Console", Monaco, FreeMono, monospace; background-color:#EEEEEE">';
	$html .= '&lt;div class="container"&gt;&lt;div class="eight columns"&gt;COLUMN A&lt;/div&gt;<br>';
	$html .= '&lt;div class="eight columns"&gt;COLUMN B&lt;/div&gt;&lt;/div&gt;</pre></p>';

	$html .= '<div class="container"><div class="eight columns" style="text-align:center;">COLUMN A</div><div class="eight columns" style="text-align:center;">COLUMN B</div></div><br>';

	$html .= "<p>Using outside margins for columns is problematic because it is unknown how many columns may be in a row.
		To resolve this problem, inner divs are used in each column to give inner padding instead of outside margins.
		Simply add a second div inside the column div with a class of <i>inner</i> to achieve the spacing effect.
		The grid stylesheet automatically applies the padding to the inner div to give the column spacing. eg.<br>";

	$html .= '<pre style="font-family:Consolas, "Lucida Console", Monaco, FreeMono, monospace; background-color:#EEEEEE">';
	$html .= '&lt;div class="container"&gt;&lt;div class="eight columns"&gt;&lt;div class="inner"&gt;COLUMN A&lt;/div&gt;&lt;/div&gt;<br>';
	$html .= '&lt;div class="eight columns"&gt;&lt;div class="inner"&gt;COLUMN B&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;</pre></p>';

	$html .= '<div class="container"><div class="eight columns"><div class="inner" style="text-align:center;">COLUMN A</div></div>';
	$html .= '<div class="eight columns"><div class="inner" style="text-align:center;">COLUMN B</div></div></div><br>';

	$html .= "<p>You can see the combination of resizable responsive grid with inner padding in the following example grid.";
	$html .= "(some colours and borders styles are added here for clearer emphasis only.)</p>";

	$example = '<br><div id="gridexample">
	<div class="container_12"><div class="one column left"><div class="inner"><div>1</div></div></div>
	<div class="eleven columns right"><div class="inner"><div>11</div></div></div></div><br>
	<div class="container_12"><div class="two columns left"><div class="inner"><div>2</div></div></div>
	<div class="ten columns right"><div class="inner"><div>10</div></div></div></div><br>
	<div class="container_12"><div class="three columns left"><div class="inner"><div>3</div></div></div>
	<div class="nine columns right"><div class="inner"><div>9</div></div></div></div><br>
	<div class="container_12"><div class="four columns left"><div class="inner"><div>4</div></div></div>
	<div class="eight columns right"><div class="inner"><div>8</div></div></div></div><br>
	<div class="container_12"><div class="five columns left"><div class="inner"><div>5</div></div></div>
	<div class="seven columns right"><div class="inner"><div>7</div></div></div></div><br>
	<div class="container_12"><div class="six columns left"><div class="inner"><div>6</div></div></div>
	<div class="six columns right"><div class="inner"><div>6</div></div></div></div><br>
	<div class="container_12"><div class="seven columns left"><div class="inner"><div>7</div></div></div>
	<div class="five columns right"><div class="inner"><div>5</div></div></div></div><br>
	<div class="container_12"><div class="eight columns left"><div class="inner"><div>8</div></div></div>
	<div class="four columns right"><div class="inner"><div>4</div></div></div></div><br>
	<div class="container_12"><div class="nine columns left"><div class="inner"><div>9</div></div></div>
	<div class="three columns right"><div class="inner"><div>3</div></div></div></div><br>
	<div class="container_12"><div class="ten columns left"><div class="inner"><div>10</div></div></div>
	<div class="two columns right"><div class="inner"><div>2</div></div></div></div><br>
	<div class="container_12"><div class="eleven columns left"><div class="inner"><div>11</div></div></div>
	<div class="one column right"><div class="inner"><div>1</div></div></div></div><br>
	</div><br>';

	$html .= $example;

	$html .= "<h4>Content Grid Class Reference</h4>";
	$html .= "<p>Extra classes are available for column shifts and offsets, to help you position the grid columns.
		Outer margins of different column sizes are applied to achieve these column shifts and offsets.
		The below reference table shows you which of the numbered class names have what effect.</p>";

	$html .= '<table id="gridreference">
	<tr><td></td><td align="center"><b>BioShip<br>Framework</b></td><td align="center">Skeleton<br>Boilerplate</td><td align="center"><i>960 Grid System</i></td><td align="center"><i>Blueprint</i></td><td align="center">Foundation</td></td></tr>
	<tr><td>Grid Columns</b></td><td align="center">12/16/20/24</td><td align="center">16</td><td align="center">12 or 16</td><td align="center">24</td><td align="center">12</td></tr>
	<tr><td>Units</td><td align="center"><b>%</b></td><td align="center">px</td><td align="center">px</td><td align="center">px</td><td align="center">%</td></tr>
	<tr><td>Container</td><td><b>.container</b></td><td>.container</td><td><i>.container_12</i><br><i>.container_16</i></td><td>.container</td><td>.row</td></tr>
	<tr><td>Grid Column</td><td><b>.{xxx}.columns</b><br>or <b>.spanX</b><br></b></td><td>.{xxx}.columns<br>or .spanX</td><td><i>.grid_X</td><td><i>.span-X</i></td><td style="font-size:9pt;">.small-X.column<br>.medium-X.column<br>.large-X.column</td></tr>
	<tr><td style="vertical-align:top;">Offset Left</td><td><b>.offsetX</b> or<br><b>.offsetleftX</b><br>(margin-left)</td><td><i>.offsetX</i><br>(padding-left)</td><td><i>.prefix_X</i><br>(padding-left)</td><td><i>.prepend-X</i><br>(padding-left)</td><td style="font-size:9pt;">.small-offset-X<br>.medium-offset-X<br>.large-offset-X<br>(margin-left)</td></tr>
	<tr><td style="vertical-align:top;">Offset Right</td><td><b>.offsetrightX</b><br>(margin-right)</td><td align="center" style="font-size:9pt;">n/a</td><td><i>.suffix_X</i><br>(padding-right</td><td><i>.append-X</i><br>(padding-right)</td><td align="center" style="font-size:9pt;">n/a</td></tr>
	<tr><td style="vertical-align:top;">Pull</td><td><b>.shiftleftX</b><br>(-margin-left)</td><td align="center" style="font-size:9pt;">n/a</td><td><i>.pull_X</i><br>(-left)</td><td><i>.pull-X</i><br>(-margin-left)</td><td style="font-size:9pt;">.pull-X,.small-pull-X<br>.medium-pull-X,.large-pull-X<br>(right)</td></tr>
	<tr><td style="vertical-align:top;">Push</td><td><b>.shiftrightX</b><br>(-margin-right)</td><td align="center" style="font-size:9pt;">n/a</td><td><i>.push_X</i><br>(left)</td><td><i>.push-X</i><br>(margins)</td><td style="font-size:9pt;">.push-X,.small-push-X<br>.medium-push-X,.large-push-X<br>(left)</td></tr>
	</table>';
	// extra ref: https://scotch.io/tutorials/cheat-sheet-for-comparing-bootstrap-and-foundation-css-classes

	// TODO: notes and example on using small spacer columns
	// $html .= "<h5>Spacer Columns</h5>";

	$html .= "<h5>Content Grid Compatibility Classes</h5>";
	$html .= "<p>If you prefer, you can use the grid class syntax for 960GS or Blueprint classes by enabling
		either of those options in theme settings so the rules are added to the grid stylesheet also.
		For Foundation grid, simply enable the Foundation resources separately in theme settings tab
		for Foundation (Skeleton layer), and use that grid syntax as you normally would.</p>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// --------------------
// === Layout Hooks ===
// --------------------

function bioship_docs_layout_hooks($wrap) {

	$page = 'hooks'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Layout Hooks</h2><br>';}

	if ($wrap) {
		// --- include the Hooks file for standalone docs ---
		$hookfile = dirname(dirname(__FILE__)).'/hooks.php';
		if (!file_exists($hookfile)) {
			$html .= "<b>Error: Layout Hooks file was not found!?</b>";
			$html .= "</body></html>"; echo $html; return;
		}
		if (!function_exists('__')) {function __($s, $d = 'bioship', $a = 'bioship') {return $s;} }
		include($hookfile);
	}

	global $vthemehooks;

	// TODO: add Page Elements for Styling Reference?

	$html .= "<table><tr><td><b>Section / Hook Label</b></td><td><b>Hook Name</b></td>";
	$html .= "<td><b>Hooked Functions</b></td><td><b>Priority</b></td></tr>";
	$html .= "<tr height='10'><td> </td></tr>";

	foreach ($vthemehooks['sections'] as $sectionid => $sectionhooks) {

		$html .= "<tr height='10'><td> </td></tr>";
		$html .= "<tr><td colspan='2'><b>".$vthemehooks['labels'][$sectionid].'</b></td></tr>';

		foreach ($sectionhooks as $sectionhook) {
			$html .= "<tr><td style='vertical-align:top;'>".$vthemehooks['labels'][$sectionhook]."</td>";
			$html .= "<td style='vertical-align:top;'>".$sectionhook."</td>";

			$hookfunctions = $vthemehooks['functions'][$sectionhook];
			if (count($hookfunctions) > 0) {
				$thesefunctions = $thesepriorities = array();
				foreach ($hookfunctions as $function => $priority) {
					$thesefunctions[] = $function; $thesepriorities[] = $priority;
				}
				$functiondisplay = implode('<br>', $thesefunctions);
				$prioritydisplay = implode('<br>', $thesepriorities);
				$html .= "<td style='font-size:12pt;'>".$functiondisplay."</td>";
				$html .= "<td style='font-size:12pt;' align='right'>".$prioritydisplay."</td>";
			}
			$html .= "</tr>";
		}
	}
	$html .= "</table>";


	$html .= "<h3>Hybrid Hook</h3>";
	$html .= "<p>All hooks are automatically made available to the Hybrid Hook plugin (modified for BioShip.)
		When activated (via <i>Theme Options</i> -> <i>Skeleton</i> -> <i>Hybrid</i>) you can access <i>Appearance</i> -> <i>Hybrid Hook</i>.
		This allows you to easily add content (Text, HTML, or Shortcode) to any of the Layout Hook sections.
		(You can also specify a priority if you need to insert between existing hooked functions.)</p>";

	$html .= "<h3>Manually Adding Functions</h3>
		You can of course simply add your own functions to any of the available hooks with a few lines of code, in the form:<br>
		<i><pre>add_action('{hook_name}', '{function_name}', {priority});</pre></i><br>
		For example, to add a simple welcome message:<br>";
	$html .= "<i><pre>add_action('bioship_after_header', 'custom_after_header_function', 5);".PHP_EOL;
	$html .= "function custom_after_header_function() {echo 'Welcome!';}</pre></i><br>
		Or for an existing shortcode function called <i>welcome_shortcode</i>:<br>";
	$html .= "<i><pre>add_action('bioship_after_header', 'custom_welcome_shortcode', 5);".PHP_EOL;
	$html .= "function custom_welcome_shortcode() {echo do_shortcode('[welcome_shortcode']);}</pre></i><br>";

	$html .= "<h4>Reordering Layout Functions</h4>
		If something is hooked in the wrong position for your desired layout, you can change it's position with a filter.<br>
		Priority filters have the same name as the function appended with _position:<br>";
	$html .= "<i><pre>add_filter('bioship_header_widgets_position', 'custom_header_widgets_position');".PHP_EOL;
	$html .= "function custom_header_widgets_position() {return 2;}</pre></i><br>";
	$html .= "OR more directly with an anonymous function:<br>
		<i><pre>add_filter('bioship_header_widgets_position', function(){return 2;} );</pre></i><br>";

	$html .= "<h4>Removing Layout Functions</h4>
		If you change the hooked function priority via filter to -1 and it will not be added. eg.
		<i><pre>add_filter('bioship_header_widgets_position', function(){return -1;} );</pre></i><br>";

	$html .= "You can also remove any of the hooked functions you can do so using <i>bioship_remove_action</i><br>
		This is a wrapper for WordPress <i>remove_action</i> with the filtered priority calculated automatically.<br>
		<i><pre>bioship_remove_action('bioship_header', 'bioship_header_widgets');</pre></i><br>";

	$html .= "Note: while you can do the same manually without using <i>bioship_remove_action</i> you would need to<br>
		delay the removal until <i>after</i> the action has been added or it will not actually be removed.<br>
		Practically speaking, this means wrapping in a further check in your functions.php eg.<br>";
	$html .= "<i><pre>add_action('init', 'custom_layout_femovals');".PHP_EOL;
	$html .= "function custom_layout_removals() {".PHP_EOL;
	$html .= "\t"."remove_action('bioship_header', 'bioship_header_widgets', 6);".PHP_EOL;
	$html .= "}</pre></i>";

	$html .= "<h4>Replacing Layout Functions</h4>
		If something is hooked in the wrong section for your desired layout, you can remove it and reinsert it.<br>
		Note again you would need to delay the removal or use <i>bioship_remove_action</i> which auto-delays for you.<br>";
	$html .= "<i><pre>bioship_remove_action('bioship_header', 'bioship_header_widgets');".PHP_EOL;
	$html .= "add_action('bioship_navbar', 'bioship_header_widgets', 2);</pre></i>";

	$html .= "<h4>Header / Footer Extra HTML</h4>
		You can also add extra HTML to the Header / Footer HTML sections using filters.<br>
		These are throwbacks from a previous implementation but are still available for use.<br>
		Filters: <i>bioship_header_html_extras</i> and <i>bioship_footer_html_extras</i>, eg:<br>";
	$html .= "<i><pre>add_filter('bioship_header_html_extra', 'custom_header_html_extras');<br>";
	$html .= "function custom_header_html_extras() {return '&lt;div&gt;Div Content&lt;/div&gt;';}</pre></i>";

	// TODO: finish banner position notes
	// $html .= "<h4>Banner Positions</h4>";
	// $html .= "You can also add banners to the banner positions with the filter: <i>skeleton_{position}_banner</i><br>";
	// $html .= "Available banner positions:<br>";
	// $html .= "<table><tr><td>top</td><td>skeleton_top_banner</td><td>above main header area</td></tr>";
	// $html .= "<tr><td>header</td><td>skeleton_header_banner</td><td>below main header area (before navbar)</td></tr>";
	// $html .= "<tr><td>navbar</td><td>skeleton_navbar_banner</td><td>after navbar (before sidebars/content)</td></tr>";
	// $html .= "<tr><td>footer</td><td>skeleton_footer_banner</td><td>above main footer area</td></tr>";
	// $html .= "<tr><td>bottom</td><td>skeleton_bottom_banner</td><td>below main footer area</td></tr></table>";
	// $html .= "eg...";

	// $html .= "You can also add a banner image and link to any of these positions for an individual post.<br>";
	// $html .= "Just add a custom field with keys of <i>{position}bannerurl</i> and <i>{position}bannerlink</i><br>";
	// $html .= "eg...";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}


// ===========
// DEVELOPMENT
// ===========

// --------------------
// === Theme Values ===
// --------------------

function bioship_docs_theme_values($wrap) {

	$page = 'values'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Theme Values</h2><br>';}

	$html .= "<h3>Theme Constants</h3>";
	$html .= "<table>
		<tr><td><b>Theme Values</b></td><td colspan='2'><b>Description</b></td><td><b>Value</b></tr>
		<tr><td><pre>THEMEPREFIX</pre></td><td colspan='2'>theme prefix</td><td>'bioship'</td></tr>
		<tr><td><pre>THEMESLUG</pre></td><td colspan='2'>sanitized lowercase and hyphenated) theme name</td><td>string</td></tr>
		<tr><td><pre>THEMEKEY</pre></td><td colspan='2'>options table key value for theme options</td><td>string</td></tr>
		<tr><td><pre>THEMEDISPLAYNAME</pre></td><td colspan='2'>the Display Name of currently active Theme</td><td>string</td></tr>
		<tr><td><pre>THEMEHOMEURL</pre></td><td colspan='2'>static URL of the BioShip website</td><td><a href='".THEMEHOMEURL."' target=_blank>".THEMEHOMEURL."</a></td></tr>
		<tr><td><pre>THEMESUPPORT</pre></td><td colspan='2'>static URL of Support website (WordQuest)</td><td><a href='".THEMESUPPORT."' target=_blank>".THEMESUPPORT."</a></td></tr>
		<tr height='10'><td> </td></tr>
		<tr><td><b>Load States</b></td></tr>
		<tr><td><pre>THEMESSL</pre></td><td colspan='2'>whether to load SSL resources (is_ssl)</td><td>true/false</td></tr>
		<tr><td><pre>THEMECHILD</pre></td><td colspan='2'>if using a Child Theme or not</td><td>true/false</td></tr>
		<tr><td><pre>THEMEPARENT</pre></td><td colspan='2'>parent Theme template slug (if any)</td><td>string</td></tr>
		<tr><td><pre>THEMEVERSION</pre></td><td colspan='2'>current version of BioShip Theme Framework</td><td>x.x.x</td></tr>
		<tr><td><pre>THEMECHILDVERSION</pre></td><td colspan='2'>Child Theme version (or parent if no child)</td><td>x.x.x</td></tr>
		<tr height='10'><td> </td></tr>
		<tr><td><b>Library States</b></td></tr>
		<tr><td><pre>THEMETITAN</pre></td><td colspan='2'>if Titan Framework is loaded</td><td>true/false</td></tr>
		<tr><td><pre>THEMEOPT</pre></td><td colspan='2'>if Options Framework is loaded</td><td>true/false</td></tr>
		<tr><td><pre>THEMEHYBRID</pre></td><td colspan='2'>if full Hybrid Core is loaded</td><td>true/false</td></tr>
		<tr><td><pre>THEMEDRIVE</pre></td><td colspan='2'>if a Theme Test Drive is active</td><td>true/false</td></tr>
		<tr><td><pre>THEMEKIRKI</pre></td><td colspan='2'>if Kirki is loaded (Customizer only)</td><td>true/false</td></tr>
		<tr height='10'><td> </td></tr>
		<tr><td><b>Theme Debugging</b></td></tr>
		<tr><td><pre>THEMEDEBUG</pre></td><td colspan='2'>output debugging information comments</td><td>true/false</td></tr>
		<tr><td><pre>THEMECOMMENTS</pre></td><td colspan='2'>output template element comments</td><td>true/false</td></tr>
		<tr><td><pre>THEMETRACE</pre></td><td colspan='2'>if performing a theme argument trace</td><td>true/false</td></tr>
		<tr><td><pre>THEMEWINDOWS</pre></td><td colspan='2'>local environment for directory paths</td><td>true/false</td></tr>
		<tr height='30'><td> </td></tr>";

	$html .= "<tr><td><h3>Theme Globals</h3></td></tr>

	<tr><td><b>Theme Options</b></td></tr>
		<tr><td>\$vthemesettings</td><td colspan='2'>All <i>Current</i> Theme Settings</td><td>Multi-level Array</td></tr>
		<tr><td>\$vthemeoptions</td><td colspan='2'>All <i>Available</i> Theme Options</td><td>Multi-level Array</tr>
		<tr height='20'><td> </td></tr>

	<tr><td><b>Theme Root Directories</b></td><td>(with trailing slashes)</td><td>Child Theme</td><td>No Child Theme</td></tr>
		<tr><td>\$vthemestyledir</td><td>Theme Stylesheet Directory</td><td>Child Theme Dir</td><td>Parent Theme Dir</td></tr>
		<tr><td>\$vthemestyleurl</td><td>Theme Stylesheet URL</td><td>Child Theme URL</td><td>Parent Theme URL</td></tr>
		<tr><td>\$vthemetemplatedir</td><td>Theme Template Directory</td><td>Parent Theme Dir</td><td>Parent Theme Dir</td></tr>
		<tr><td>\$vthemetemplateurl</td><td>Theme Template URL</td><td>Parent Theme URL</td><td>Parent Theme URL</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><b>Theme Resource Directories</b></td></tr>
		<tr><td>\$vthemedirs</td><td>File Hierarchy Search</td><td>Default Values</td><td>Filter</td></tr>
		<tr><td> ['core']</td><td>Core Theme Directories Array</td><td>empty array (theme root)</td><td>theme_core_dirs</td></tr>
		<tr><td> ['admin']</td><td>Theme Admin Directories Array</td><td>'admin'</td><td>theme_admin_dirs</td></tr>
		<tr><td> ['style']</td><td>Style Directories Array</td><td>'styles', 'css', 'assets/css'</td><td>theme_style_dirs</td></tr>
		<tr><td> ['script']</td><td>Script Directories Array</td><td>'javascripts', 'js', 'assets/js'</td><td>theme_script_dirs</td></tr>
		<tr><td> ['image']</td><td>Image Directories Array</td><td>'images', 'img', 'icons', 'assets/img'</td><td>theme_image_dirs</td></tr>
		<tr height='20'><td> </td></tr>

	<tr><td><b>Layout Sections and Hooks</b></td></tr>
		<tr><td>\$vthemehooks</td><td colspan='2'>Layout Hooks Information</td><td>Notes</td></tr>
		<tr><td> ['sections']</td><td colspan='2'>Ordered array of Layout Sections</td><td>numeric key sections with hooks</tr>
		<tr><td> ['hooks']</td><td colspan='2'>Array of Theme Layout Hook Keys</td><td>flat list of all layout hook keys</td></tr>
		<tr><td> ['functions']</td><td colspan='2'>Array of Hooked Default Functions</td><td>(default function priorities are also stored)</tr>
		<tr><td> ['labels']</td><td colspan='2'>Labels for both Sections and Hooks</td><td>(sections numeric keys, hooks string keys)</td></tr>
		<tr><td> ['hybrid']</td><td colspan='2'>Modified Layout Hook array for Hybrid Hook</td><td>(bioship_ prefix removed)</tr>
		<tr height='20'><td> </td></tr>

	<tr><td><b>Theme Layout Values</b></td></tr>
		<tr><td>\$vthemelayout</td><td colspan='2'>Calculated (and Filtered) Theme Layout</td><td>Filter</td></tr>
		<tr><td> ['pagecontext']</td><td colspan='2'>Page Context</td></td></tr>
		<tr><td> ['subpagecontext']</td><td colspan='2'>Archive SubContext</td><td></td></tr>
		<tr><td> ['menus']</td><td colspan='2'>Filtered Menu States</td><td></td></tr>
		<tr><td> ['maxwidth']</td><td colspan='2'>Maximum Layout Width (px)</td><td>skeleton_layout_width</td></tr>
		<tr><td> ['gridcolumns']</td><td colspan='2'>Word-Number of Grid Columns</td><td>skeleton_grid_columns</td></tr>
		<tr><td> ['contentcolumns']</td><td colspan='2'>Word-Number of Content Columns</td><td>skeleton_content_columns_override</td></tr>
		<tr><td> ['rawcontentwidth']</td><td colspan='2'>Content Width (px) - padding width not removed</td><td>skeleton_raw_content_width</td></tr>
		<tr><td> ['contentwidth']</td><td colspan='2'>Calculated Content Width (px)</td><td>skeleton_content_width</td></tr>
		<tr><td> ['rawcontentpadding']</td><td colspan='2'>Content Padding (CSS from Theme Option)</td><td>skeleton_raw_content_padding</td></tr>
		<tr><td> ['contentpadding']</td><td colspan='2'>Calculated Content Padding Width (px)</td><td>skeleton_content_padding_width</td></tr>
		<tr><td> ['contentgridcolumns']</td><td colspan='2'>Columns for Content Grid System</td><td>skeleton_content_grid_columns</td></tr>
		<tr height='10'><td> </td></tr>

	<tr><td><b>Sidebar Layout Values</b></td></tr>
	<tr><td>\$vthemesidebars</td><td colspan='2'>Calculated (and Filtered) Sidebar Layout</td><td>Filter</td></tr>
		<tr><td> ['sidebars']</td><td colspan='2'>Sidebar Template Layout Array</td><td>* skeleton_sidebar_layout_override</td></tr>
		<tr><td> ['sidebar']</td><td colspan='2'>Calculated Sidebar switch (true/false)</td><td></td></tr>
		<tr><td> ['subsidebar']</td><td colspan='2'>Calculated SubSidebar switch (true/false)</td><td></td></tr>
		<tr><td> ['sidebarmode']</td><td colspan='2'>Posts/Pages Sidebar Mode</td><td>skeleton_sidebar_mode</td></tr>
		<tr><td> ['subsidebarmode']</td><td colspan='2'>Posts/Pages SubSidebar Mode</td><td>skeleton_subsidebar_mode</td></tr>
		<tr><td> ['sidebarcontext']</td><td colspan='2'>Based on Page Context and Active Sidebars</td></tr>
		<tr><td> ['subsidebarcontext']</td><td colspan='2'>Based on Page Context and Active Sidebars</td></tr>
		<tr><td> ['sidebarcolumns']</td><td colspan='2'>Number-Word of Sidebar Columns</td><td>skeleton_sidebar_columns</td></tr>
		<tr><td> ['subsidebarcolumns']</td><td colspan='2'>Number-Word of SubSidebar Columns</td><td>skeleton_subsidebar_columns</td></tr>
		<tr><td> ['output']</td><td colspan='2'>Stores actual Sidebar content HTML for output</td><td></td></tr>
	</table>";

	$html .= "For more details on Sidebars see the <a href='".$vthemedoclinks['sidebars']."'>BioShip Sidebar Guide</a>.<br>";

	// TODO: add vthemedisplay and vthemeoverride globals ?

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}

// -----------------
// === Debugging ===
// -----------------

function bioship_docs_debug_guide($wrap) {

	$page = 'debug'; $html = ''; $vthemedoclinks = bioship_docs_links($wrap);
	if ($wrap) {$html = bioship_docs_wrap_open($page).'<h2>BioShip Theme Debugging</h2><br>';}

	$html .= "<h4>HTML Page Element Comments</h4>";
	$html .= "<p>The THEMECOMMENTS constant is set by the theme according the option in <i>Theme Options</i> -> <i>Skin</i> -> <i>Styles</i><br>";
	$html .= "This will wrap all the main Page Elements in HTML comments indicating start and finish of the element. eg.<br>";
	$html .= "<pre>&lt;!-- #sidebar --&gt;{Sidebar Output}&lt;!-- /#sidebar --&gt;</pre>";
	$html .= "This helps make things easier to find when viewing the page source or in a browser debug console. :-)<br>";
	$html .= "Of course it adds unnecessary length to the page size so you will want to turn this off in production.<br>";
	$html .= "[Value Filter] <a href='".$vthemedoclinks['filters']."?filter=skeleton_html_comments'><i>skeleton_html_comments</i></a> - return true/false]<br>";

	$html .= "<h3>Theme Debug Modes</h3>";
	$html .= "<p>Theme debug mode sets the THEMEDEBUG constant via the <i>themedebug</i> querystring switch (from any site URL.)
		You can change switch the debug mode on or off, or set it to temporarily output (for that particular page load.)</p>";

	$html .= "<p><i>In practice</i>, you would usually just use ?themedebug=2 or ?themedebug=yes for a specific pageload.
		However, to be able to test <i>logged out user content</i> you would need to switch debug mode ON and logout -
		or simply view the logged out content from another browser with debug ON and then switch it OFF when done.<br>
		<b>Note</b>: You need <i>edit_theme_options</i> capability to toggle or enable/disable debug mode.</p>";

	// 2.1.1: fixed to remove extraneous close cell tags
	$html .= "<br><table cellpadding='10' cellspacing='10'>";
	$html .= "<tr><td><b>Querystring</b></td><td width='20'></td><td><b>Alternative</b></td><td width='20'</td><td><b>Debug Action</b></td></tr>";
	$html .= "<tr><td>?themedebug=0</td><td></td><td>?themedebug=off</td><td><td>switch theme debug mode off (persistant)</td></tr>";
	$html .= "<tr><td>?themedebug=1</td><td></td><td>?themedebug=on</td><td><td>switch theme debug mode on (persistant)</td></tr>";
	$html .= "<tr><td>?themedebug=2</td><td></td><td>?themedebug=yes</td><td><td>debug mode on for this pageload (overrides switch)</td></tr>";
	$html .= "<tr><td>?themedebug=3</td><td></td><td>?themedebug=no</td><td><td>debug mode off for this pageload (overrides switch)</td></tr>";
	$html .= "<tr><td>[Value Filter]</td><td></td><td><a href='".$vthemedoclinks['filters']."?fllter=skeleton_theme_debug'><i>skeleton_theme_debug</i></a></td></td><td><td>return true/false</td></tr>";
	$html .= "</table>";

	$html .= "<br>When active you debug information will be output to the HTML page source wrapped in HTML comment tags.<br>";
	$html .= "Each debug info occurrence will appear in the form of:<br>";
	$html .= "<pre>&lt;!-- Debug Info Description: {INFO} --&gt;</pre>";
	$html .= "This is so you can search the theme source for a particular 'Info Description' to see what is happening.<br><br>";

	$html .= "<p><b>Warning</b>: this kind of debug output can prevent Theme Settings or other admin/form/AJAX methods from saving!
		This is because echoing this information inline means headers are already sent and thus can prevent redirects -
		causing the Settings API and other things to fail. :-/ <i>So always remember to turn theme debug mode off!</i></p>";


	// $html .= "<h4>Theme Debug to File</h4>";
	// TODO: better debug to file options and explanation

	$html .= "<h4>Included Page Templates</h4>";
	$html .= "<p>You can easily see what page templates are being included on a page (useful for WooCommerce/Forum templates etc.)
		Simply search for 'Included Template Files' (Debug Info Description) in the page source when debug mode is active.
		You will find a list of all files included between the <i>wp_loaded</i> and <i>wp_footer</i> action hooks in the page footer.</p>";
	// 2.1.1: added missing template dropdown menu explanation
	$html .= "<p>You can access this page template list more easily (as of v2.0.1) via the dropdown Template menu on the Admin Bar.
		Hover the submenu items to see full template paths or click the file to load it for viewing in the Theme Editor.
		(Note: only shows for <i>manage_options</i> capability. You can turn this feature off via Theme Options <i>Muscle</i> Layer -> <i>Admin</i> tab.)</p>";

	// TODO: better theme tracer explanation ?
	$html .= "<h3>Theme Debug Tracer</h3>";
	$html .= "<p>A Theme Function Tracer has been developed for running isolated traces within theme files and functions.
		This is a fast and powerful way of tracking down where something is not behaving in the theme as desired.
		It allows you to see the flow of all template, function, action and filter calls parsed by the theme as it runs.
		Memory load and pageload time are also tracked, allowing you to identify possible performance bottlenecks.</p>";

	$html .= "<p>To run a trace simply append <i>?themetrace=1</i> to any URL on your site to create a trace file.
		By default trace information is output to your (parent or child) themes /debug/ directory.
		(Note you need <i>manage_options</i> capability to do a theme trace.) You can also specify further
		querystrings as in the table below, for example to narrow the scope of the trace, or expand a function
		trace to log all function arguments passed, or display the trace at the end of the page source.</p>";

	// 2.1.1: added table for tracer querystring arguments (plus traceaction and tracetemplate)
	$html .= "<br><table cellpadding='10' cellspacing='10'>";
		$html .= "<tr><td><b>Querystring</b></td><td width='20'></td><td><b>Values</b></td><td width='20'></td><td><b>Trace Action</b></td><td width='20'></td><td><b>Default</b></td></tr>";
		$html .= "<tr><td>?themetrace=</td><td></td><td>1 or yes</td><td></td><td>switch to activate theme tracer</td><td></td><td>off</td></tr>";
		$html .= "<tr><td>&trace=</td><td></td><td><i>functions</i>, <i>templates</i>,<br><i>filters</i>, <i>actions</i></td><td></td><td>which resource to trace</td><td></td><td>all</td></tr>";
		$html .= "<tr><td>&tracedisplay=</td><td></td><td>1 or yes</td><td></td><td>whether output trace display or not</td><td></td><td>off</td></tr>";
		$html .= "<tr><td>&instance=</td><td></td><td>string</td><td></td><td>trace ID used for trace file prefix</td><td></td><td>timestamp<td><td></td></td></tr>";
		$html .= "<tr><td>&tracecalls=</td><td></td><td>1 or yes</td><td></td><td>whether to trace number of function calls</td><td></td><td>off</td></tr>";
		$html .= "<tr><td>&traceargs=</td><td></td><td>1 or yes</td><td></td><td>whether to trace function arguments</td><td></td><td>off</td></tr>";
		$html .= "<tr><td>&tracefunction=</td><td></td><td><i>function_name</i></td><td></td><td>trace a specific (theme) function</td><td></td><td>none</td></tr>";
		$html .= "<tr><td>&tracefilter=</td><td></td><td><i>filter_name</i></td><td></td><td>trace a specific (theme) filter</td><td></td><td>none</td></tr>";
		$html .= "<tr><td>&traceaction=</td><td></td><td><i>action_name</i></td><td></td><td>trace a specific (theme) action</td><td></td><td>none</td></tr>";
		$html .= "<tr><td>&tracetemplate=</td><td></td><td><i>loop</i>, <i>sidebar</i>, <i>content,<br><i>header</i>, <i>footer</i>, <i>comment</i></td><td></td><td>trace a (theme) template <i>type</i></td><td></td><td>all</td></tr>";
	$html .= "</table>";

	// $html .= "<h4>Heavy Debugging</h4>";
	// $html .= "Of course, you can always use good old: <pre>var_dump(debug_backtrace());</pre> in your code somewhere.<br>";
	// $html .= "Typically this gives you a massive amount of information you don't need so an above method is preferred.<br>";

	if ($wrap) {$html .= bioship_docs_wrap_close($page);} else {$html .= bioship_docs_navigation($page);}

	return $html;
}

