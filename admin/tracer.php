<?php

// ============================
// === BioShip Theme Tracer ===
// ============================

// --- no direct load ---
if (!defined('ABSPATH')) {exit;}

// ----------------------------
// === tracer.php Structure ===
// ----------------------------
// - Debug Queries on Shutdown
// === Setup Theme Tracer ===
// - Tracer Querystring Usage
// - Tracer Function
// - Trace Processor
// - Filter Processing Debug
// === Trace Included Templates ===
// - Check Templates Loader
// - Get All Included Theme Files
// - Check Theme Included Files
// - Get Included Template List
// - Admin Bar Template Dropdown
// ----------------------------


// Development TODOs
// -----------------
// - fix template view links for when editing is disabled ?


// -------------------------
// Debug Queries on Shutdown
// -------------------------
// 2.1.4: moved here from functions.php
if (THEMEDEBUG) {
	// 2.0.5: also use save queries constant for debugging output
	if (!defined('SAVEQUERIES')) {define('SAVEQUERIES', true);}
	if (SAVEQUERIES) {
		if (!function_exists('bioship_debug_saved_queries')) {

		 // 2.1.1: moved add_action internally for consistency
		 add_action('shutdown', 'bioship_debug_saved_queries');

		 function bioship_debug_saved_queries() {
			global $wpdb; $queries = $wpdb->queries;
			bioship_debug("Saved Queries", $queries);
		 }
		}
	}
}


// --------------------------
// === Setup Theme Tracer ===
// --------------------------

// ------------------------
// Tracer Querystring Usage
// ------------------------
// note: performating a trace requires manage_options capability
// ?themetrace=1 		- to do a theme trace
// &trace={resource} 	- templates, functions, filters, actions		- default: all
// &tracecalls=1		- whether to trace number of function calls		- default: off
// &traceargs=1			- whether to trace function arguments			- default: off
// &tracedisplay=1		- whether to output the trace inline on page	- default: off
// &instance={instance} - prefix name for the debug trace output file	- default: timedate

// Note: of course for heavy lifting you can always use:
// add_action('shutdown', function() {echo "<!-- ".var_dump(debug_backtrace())." -->";});
// ...but this is a much leaner and more targeted approach!

// --- set Theme Tracer values ---
// 2.1.1: moved THEMETRACE definition to functions.php
// note: vthemetracer = tracer options, $vthemetrace = trace data
global $vthemetracer, $vthemetrace;
$vthemetracer = array();

// --- check for load or call trace request ---
if (isset($_REQUEST['trace'])) {
	$trace = trim($_REQUEST['trace']);
	// 2.0.5: simplify to array check
	$tracetypes = array('template', 'function', 'filter', 'action');
	// --- help to forget about plurals if debugging as may already be annoyed... O_o
	if (in_array($trace, $tracetypes)) {$trace .= 's';}
	// 2.1.1: validate specified trace option
	$valid = array('templates', 'functions', 'filters', 'actions', 'all');
	if (in_array($trace, $valid)) {$vthemetracer['trace'] = $trace;} else {$vthemetracer['trace'] = false;}
} else {$vthemetracer['trace'] = 'all';}

// --- whether to trace number of function calls ---
$vthemetracer['calls'] = false;
if (isset($_REQUEST['tracecalls'])) {
	if ( ($_REQUEST['tracecalls'] == '1') || ($_REQUEST['tracecalls'] == 'yes') ) {$vthemetracer['calls'] = true;}
}

// --- whether to trace function arguments ---
$vthemetracer['args'] = false;
if (isset($_REQUEST['traceargs'])) {
	if ( ($_REQUEST['traceargs'] == '1') || ($_REQUEST['tracedisplay'] == 'yes') ) {$vthemetracer['args'] = true;}
}

// --- whether to trace a single function ---
$vthemetracer['function'] = false;
if (isset($_REQUEST['tracefunction']) && (trim($_REQUEST['tracefunction']) != '') ) {$vthemetracer['function'] = trim($_REQUEST['tracefunction']);}
elseif (isset($_REQUEST['tracefunc']) && (trim($_REQUEST['tracefunc']) != '') ) {$vthemetracer['function'] = trim($_REQUEST['tracefunc']);}

// --- whether to trace a single filter ---
$vthemetracer['filter'] = false;
if (isset($_REQUEST['tracefilter']) && (trim($_REQUEST['tracefilter']) != '') ) {$vthemetracer['filter'] = trim($_REQUEST['tracefilter']);}

// --- whether to trace a single template ---
// 2.1.1: activated tracetemplate variable
$vthemetracer['template'] = false;
if (isset($_REQUEST['tracetemplate']) && ($_REQUEST['tracetemplate'] != '') ) {
	$tracetemplate = trim($_REQUEST['tracetemplate']);
	$valid = array('loop', 'header', 'footer', 'sidebar', 'content');
	if (in_array($tracetemplate, $valid)) {$vthemetracer['template'] = $tracetemplate;}
}

// --- whether to trace a single action ---
$vthemetracer['action'] = false;
// 2.1.1: activated traceaction variable
if (isset($_REQUEST['traceaction']) && (trim($_REQUEST['traceaction']) != '') ) {$vthemetracer['action'] = trim($_REQUEST['traceaction']);}

// --- whether to output trace inline ---
$vthemetracer['output'] = false;
if (isset($_REQUEST['tracedisplay'])) {
	if ( ($_REQUEST['tracedisplay'] == '1') || (trim($_REQUEST['tracedisplay']) == 'yes') ) {$vthemetracer['output'] = true;}
}
if (isset($_REQUEST['traceoutput'])) {
	if ( ($_REQUEST['traceoutput'] == '1') || (trim($_REQUEST['traceoutput']) == 'yes') ) {$vthemetracer['output'] = true;}
}

// --- setup empty trace data array ---
// 2.1.1: simplified empty data array
$vthemetrace = array(
	'instance'	=> false,
	'start'		=> date('Y-m-d--H-i-s', time()),
	'end'		=> '',
	'functions'	=> array(),
	'templates'	=> array(),
	'actions'	=> array(),
	'filters'	=> array(),
	'calls'		=> array(),
	'lines'		=> array(),
);

// --- maybe set a trace instance ---
if (isset($_REQUEST['instance'])) {$vthemetracer['instance'] = $_REQUEST['instance'];}


// -----------------------
// === Tracer Function ===
// -----------------------
// 2.1.1: added missing function_exists wrapper
// 2.1.1: shortened arguments resourcetype, resourcename, functionargs
if (!function_exists('bioship_trace')) {
 function bioship_trace($type, $name, $filepath, $args=false) {

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	// --- change trace type abbreviation to name ---
	if ($type == 'F') {$type = 'function';}
	elseif ($type == 'T') {$type = 'template';}
	elseif ($type == 'V') {$type = 'filter';}
	elseif ($type == 'A') {$type = 'action';}
	else {return;}

	// --- strip base file path ---
	// (not used for filters)
	if ($type != 'filter') {
		$pos = strpos($filepath, 'themes') + strlen('themes');
		$filepath = substr($filepath, $pos, strlen($filepath));
	}

	// --- check for single function / filter / template / action trace ---
	// 2.1.1: added single template / action matching
	if ($vthemetracer['function'] || $vthemetracer['filter'] || $vthemetracer['template'] || $vthemetracer['action']) {
		if ( ($type != 'function') && ($type != 'filter') ) {return;}
		if ( ($type == 'function') && ($name != $vthemetracer['function']) ) {return;}
		if ( ($type == 'filter') && ($name != $vthemetracer['filter']) ) {return;}
		if ( ($type == 'action') && ($name != $vthemetracer['action']) ) {return;}
		// note: match via args (template slug) not full template name
		if ( ($type == 'template') && ($args != $vthemetracer['template']) ) {return;}
	}

	// --- maybe add to resource counts ---
	// 2.1.1: added return if not matching desired trace
	if ($type == 'function') {
		if ( ($vthemetracer['trace'] == 'functions') || ($vthemetracer['trace'] == 'all') ) {
			if (!in_array($name, $vthemetrace['functions'])) {$vthemetrace['functions'][] = $name;}
			if ($vthemetracer['output']) {echo '<!-- traced function: '.esc_attr($name).' ('.esc_attr($filepath).') -->';}

			// --- keeps track of all function calls ---
			$vthemetrace['calls'][] = $name;
			$tracecount = '('.count($vthemetrace['calls']).')';
		} else {return;}
	} elseif ($type == 'template') {
		if ( ($vthemetracer['trace'] == 'templates') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['templates'][] = $name;
			$tracecount = '['.count($vthemetrace['templates']).']';
			if ($vthemetracer['output']) {echo '<!-- traced template: '.esc_attr($name).' ('.esc_attr($filepath).') -->';}
			// 2.1.1: set current template filepath for action filepath tracing
			$vthemetrace['currentfile'] = $filepath; return;
		} else {return;}
	} elseif ($type == 'action') {
		// 2.0.5: added action tracing...
		if ( ($vthemetracer['trace'] == 'actions') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['actions'][] = $name;
			$tracecount = '+'.count($vthemetrace['actions']).'+';
			// 2.1.1: override filepath with current template filepath
			if (isset($vthemetrace['currentfile'])) {$filepath = $vthemetrace['currentfile'];}
			if ($vthemetracer['output']) {echo '<!-- traced action: '.esc_attr($name).' -->';}
		} else {return;}
	} elseif ($type == 'filter') {
		if ( ($vthemetracer['trace'] == 'filters') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['filters'][] = $name;
			$tracecount = '<'.count($themetrace['filters']).'>';
			if ($vthemetracer['output']) {echo '<!-- traced filter: '.esc_attr($name).' -->';}
		} else {return;}
	}

	// --- add the tracer line to the load record ---
	// 1.9.8: fix to tracer line
	// 2.0.5: reordered tracer line output for readability
	$memoryusage = memory_get_usage(true);
	$loadtime = bioship_timer_time();
	$tracerline = $tracecount.'::'.$type.'::'.$name.'::'.$memoryusage.'::'.$loadtime.'::'.$filepath;
	$vthemetrace['lines'][] = $tracerline;
	// if ($vthemetracer['output']) {echo '<!-- tracer line: '.esc_attr($tracerline).'-->';}

	// --- write argument trace debug log ---
	// 1.9.8: full trace logging of function/filter arguments passed
	// (note: no arguments are passed for actions or templates)
	if ( ($type == 'function') || ($type == 'filter') ) {
		if ($vthemetracer['args'] && $args) {

			$traceline = '';
			if ($type == 'function') {
				ob_start(); var_dump($args); $dump = ob_get_contents(); ob_end_clean();
				if ($vthemetracer['output']) {echo '<!-- function arguments: '.esc_attr($dump).' -->';}
				$traceline = $type.': '.$name.PHP_EOL.$dump.PHP_EOL;
			} elseif ($type == 'filter') {
				ob_start(); var_dump($args['in']); $in = ob_get_contents(); ob_end_clean();
				ob_start(); var_dump($args['out']); $out = ob_get_contents(); ob_end_clean();
				$traceline = $type.': '.$name.PHP_EOL;
				// 2.0.5: fix to concatenate properly here
				$traceline .= 'Filter Value in: '.$in.PHP_EOL.'Filter Value out: '.$out;
			}

			// 2.1.1: fix check for undefined index warning
			if (isset($vthemetracer['instance'])) {
				if ($vthemetracer['instance'] == '') {$tracefile = '';} // no file writing
				else {$tracefile = '_'.$instance.'--traceargs.txt';}
			} else {$tracefile = $vthemetrace['start'].'--traceargs.txt';}

			if ($tracefile != '') {
				// 2.0.7: replace direct with WP file system writing
				// 2.1.1: use bioship_write_file with append method
				// 2.1.1: use method whether file exists already or not
				bioship_write_debug_file($tracefile, $traceline);
			}
		}
	}
 }
}

// -----------------------
// === Trace Processor ===
// -----------------------
// 2.1.1: added missing function_exists wrappers
// 2.1.1: removed use of vthemetimestart in favour of vthemetracer['start']
if (!function_exists('bioship_trace_processor')) {

 // --- run the trace processor on shutdown ---
 add_action('shutdown', 'bioship_trace_processsor');

 function bioship_trace_processsor() {

	// 2.1.1: bug out if not tracing
	if (!THEMETRACE) {return;}

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	// --- set trace end to shutdown time ---
	$vthemetrace['end'] = date('Y-m-d--H-i-s', time());

	// --- processor started message ---
	if ($vthemetracer['output']) {echo "<!-- Theme Trace Processor Started -->";}

	// --- maybe get tracer instance ---
	if (isset($_REQUEST['instance'])) {$instance = $_REQUEST['instance'];}

	// --- write load log ---
	if ($vthemetracer['trace'] && (count($vthemetrace['lines']) > 0)) {

		// --- set trace loads debug filename ---
		// 2.1.1: fix check for undefined index warning
		if (isset($vthemetracer['instance'])) {
			if ($vthemetracer['instance'] == '') {$traceloadsfile = '';} // no file writing
			else {$traceloadsfile = '_'.$instance.'--traceload.txt';}
		} else {$traceloadsfile = $vthemetrace['start'].'--traceload.txt';}
		$tracercontents = implode(PHP_EOL, $vthemetrace['lines']);

		// --- write trace loads debug file ---
		if ($traceloadsfile != '') {
			// 2.0.7: replace direct with file system debug writing
			bioship_write_debug_file($traceloadsfile, $tracercontents);
			if ($vthemetrace['output']) {echo "<!-- Trace Written to: ".esc_attr($traceloadsfile)." -->";}
		}
	}

	// --- write call log ---
	if ($vthemetracer['calls'] && (count($vthemetrace['calls']) > 0)) {

		// -- parse the tracer call log into an occurrence log ---
		// ...extract occurrence data from the tracer calls
		$calls = array();
		foreach ($vthemetrace['calls'] as $trace) {
			if (isset($calls[$vtrace])) {
				$calls[$trace] = (int)$calls[$trace] + 1;
			} else {$calls[$trace] = 1;}
		}
		// reorder by occurrences
		arsort($calls);

		// --- gather the data into lines ---
		$occurlines = array();
		foreach ($calls as $functionname => $occurs) {$occurlines[] = $functionname.'::'.$occurs;}

		// --- write the call occurences log file ---
		$occurdata = implode(PHP_EOL, $occurlines);
		$occurdata = __('Theme Function Call Occurrences','bioship').':'.PHP_EOL.$occurdata;
		$orderedtraces = implode(PHP_EOL, $vthemetrace['calls']);
		$occurdata .= PHP_EOL.PHP_EOL.__('All Function Calls Order','bioship').':'.PHP_EOL.$orderedtraces;

		// --- set trace calls debug filename ---
		if ($vthemetracer['instance']) {
			if ($vthemetracer['instance'] == '') {$tracecallsfile = '';} // no file writing
			else {$tracecallsfile = '_'.$instance.'--tracecalls.txt';}
		} else {$tracecallsfile = $vthemetrace['start'].'--tracecalls.txt';}

		// --- write trace calls debug file ---
		if ($tracecallsfile != '') {
			// 2.0.7: replace direct with WP file system debug writing
			bioship_write_debug_file($tracecallsfile, $occurdata);
		}
	}

	// --- maybe output at end of screen for source viewing ---
	if ($vthemetracer['output']) {

		// --- main trace results ---
		echo "<!-- Trace Processor Finished -->";
		echo "<!-- Results: ".esc_attr(print_r($vthemetrace,true))." -->";

		// --- occurrences results ---
		if (isset($occurdata)) {
			$occurdata = str_replace('::', ' : ', $occurdata);
			echo "<!-- Trace Occurrences: ".PHP_EOL.esc_attr($occurdata)." -->";
		}
	}

 }
}

// -----------------------
// Filter Processing Debug
// -----------------------
// 2.0.5: add this to help debug action load order
if (!function_exists('bioship_all_actions_filters')) {

 // --- add to all actions / filters ---
 add_action('all', 'bioship_all_actions_filters');

 function bioship_all_actions_filters() {

 	// --- for theme debug mode only ---
	if (!THEMEDEBUG) {return;}

	// --- processing filter debug output ---
	$filter = current_filter();
	if (substr($filter, 0, strlen(THEMEPREFIX.'_')) == THEMEPREFIX.'_') {
		if (substr($filter, -9, 9) != '_position') {
			echo "<!-- [Processing Filter] ".esc_attr($filter);
				if (!has_filter($filter)) {echo " [n/a]";}
			echo " -->".PHP_EOL;
		}
	}
 }
}


// --------------------------------
// === Trace Included Templates ===
// --------------------------------

// ----------------------
// Check Templates Loader
// ----------------------
if (!function_exists('bioship_check_templates_loader')) {

 add_action('init', 'bioship_check_templates_loader');

 function bioship_check_templates_loader() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- for Debug Mode or for any Site Admin ---
	// 2.1.1: added edit_theme_options capability
  	if (THEMEDEBUG || current_user_can('edit_theme_options') || current_user_can('manage_options')) {

		// --- check included files on pageload ---
		add_action('wp_loaded', 'bioship_check_theme_includes');

		// --- check included templates after pageload ---
		add_action('wp_footer', 'bioship_check_theme_templates');
	}
 }
}

// ----------------------------
// Get All Included Theme Files
// ----------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_get_theme_includes')) {
 function bioship_get_theme_includes() {
 	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- get included files ---
	$includedfiles = get_included_files();

	// --- normalize theme paths for matching ---
	$vstyledirectory = str_replace("\\", "/", get_stylesheet_directory());
	$vtemplatedirectory = str_replace("\\", "/", get_template_directory());
	$vplugindir = str_replace("\\", "/", WP_PLUGIN_DIR);

	// --- loop included files ---
	foreach ($includedfiles as $i => $includedfile) {

		// --- normalize include path for match ---
		$includedfile = str_replace("\\", "/", $includedfile);

		// --- check if included file is in stylesheet directory ---
		if (substr($includedfile, 0, strlen($vstyledirectory)) == $vstyledirectory) {

			// --- strip stylesheet dir from include path ---
			// 2.0.1: re-add full filepath to pathinfo array
			// 2.1.1: just index via full file path
			// 2.1.2: add type key to pathinfo
			$pathinfo = pathinfo(str_replace($vstyledirectory, '', $includedfile));
			$pathinfo['type'] = 'child';

			$vthemeincludes[$includedfile] = $pathinfo;

		} else {

			// --- if stylesheet is same as template, this is not a child theme ---
			// 2.1.1: use THEMECHILD constant and check in parent directory
			if (THEMECHILD && (substr($includedfile, 0, strlen($vtemplatedirectory)) == $vtemplatedirectory)) {

					// --- strip template directory from include path ---
					// 2.0.7: fix to variable name (pathinfo)
					// 2.1.1: just index via full file path
					// 2.1.2: add type key to pathinfo
					$pathinfo = pathinfo(str_replace($vtemplatedirectory, '', $includedfile));
					$pathinfo['type'] = 'parent';
					$vthemeincludes[$includedfile] = $pathinfo;

			} else {

				// --- possibly a plugin template file ---
				// 2.1.1: handle other included files
				$pathinfo = pathinfo($includedfile);

				// 2.1.2: added type key to pathinfo (plugin/other)
				if (strpos($includedfile, $vplugindir) === 0) {$pathinfo['type'] = 'plugin';}
				else {$pathinfo['type'] = 'other';}

				$vthemeincludes[$includedfile] = $pathinfo;
				// unset($includedfiles[$i]);
			}

		}
	}

	return $vthemeincludes;
 }
}

// --------------------------
// Check Theme Included Files
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_check_theme_includes')) {
 function bioship_check_theme_includes() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	global $vthemeincludes;
	$vthemeincludes = bioship_get_theme_includes();

	// 2.1.1: added return value
	return $vthemeincludes;
 }
}

// --------------------------
// Get Included Template List
// --------------------------
// 1.8.5: added this debugging function
if (!function_exists('bioship_check_theme_templates')) {
 function bioship_check_theme_templates() {
	if (THEMETRACE) {bioship_trace('F',__FUNCTION__,__FILE__);}

	// --- check template includes ---
	// 2.1.1: change vtemplateincludes global to vthemetemplates
	global $vthemeincludes, $vthemetemplates;
	$vthemetemplates = bioship_get_theme_includes();

	// --- strip out included theme files from template list ---
	$includespath = str_replace("\\", "/", ABSPATH.WPINC);
	foreach ($vthemetemplates as $filepath => $pathinfo) {
		// 2.1.1: match with full file path instead
		// 2.1.1: ignore standard wordpress includes files
		if ( array_key_exists($filepath, $vthemeincludes)
		  || strstr($filepath, $includespath)
		  || strstr($filepath, 'wp-admin/includes') ) {
			unset($vthemetemplates[$filepath]);
		}
	}
	// echo "<!-- Template Includes: ".esc_attr(print_r($vthemetemplates,true))." -->";
	bioship_debug("Included Template Files", $vthemetemplates);

	// IDEA: output a template array for use by jQuery/AJAX loading?
	// ...what would it be the use case for it though ?
	// echo "<script>var templatenames = new Array(); var templatepaths = new Array(); ";
	// $i = 0;
	// foreach ($vthemetemplates as $template => $pathinfo) {
	//  // optionally strip the .php extension
	//  $template = str_replace('.php', '', $template);
	//  // output the template array key/value
	//  echo "templatenames[".$i."] = '".esc_js($pathinfo['filename'])."'; ";
	//  echo "templatepaths[".$i."] = '".esc_js($pathinfo['dirname'])."'; ";
	//  $i++;
	// }
	// echo "</script>";

	// --- admin bar template dropdown list ---
	// 2.0.1: maybe add list of included templates as dropdown menu in admin bar
	if (is_user_logged_in() && current_user_can('manage_options')) {

		// --- check filtered setting ---
		// 2.1.1: added missing global declaration for vthemesettings
		global $vthemesettings; $addmenu = false;
		if (isset($vthemesettings['templatesdropdown'])) {$addmenu = $vthemesettings['templatesdropdown'];}
		// 2.1.1: added theme debug override to display
		if (THEMEDEBUG) {$addmenu = true;}
		$addmenu = bioship_apply_filters('admin_template_list_dropdown', $addmenu);
		if (!$addmenu) {return;}

		// --- add template dropdown list ---
		add_action('wp_before_admin_bar_render', 'bioship_admin_template_dropdown');
	}

 }
}

// ----------------------------
// Admin Bar Templates Dropdown
// ----------------------------
// 2.0.1: added dropdown template list to admin bar
if (!function_exists('bioship_admin_template_dropdown')) {
 function bioship_admin_template_dropdown() {

	global $wp_admin_bar, $vthemetemplates, $vthemename;

	// --- set menu settings ---
	$menu = array(
		'id' => 'page-templates',
		'title' => '<span class="ab-icon"></span>'.esc_attr(__('Templates','bioship')),
		'href' => 'javascript:void(0);',
		'meta' => array(
			'title' => esc_attr(__('Ordered list of included templates for this pageload.','bioship'))
		)
	);

	// --- add menu to admin bar ---
	$wp_admin_bar->add_menu($menu);

	// --- normalize paths for matching ---
	$plugindir = str_replace("\\", "/", WP_PLUGIN_DIR);
	$abspath = str_replace("\\", "/", ABSPATH);

	// --- loop templates and add to menu ---
	$i = 0;
	foreach ($vthemetemplates as $filepath => $pathinfo) {

		// --- handle template types ---
		// 2.1.2: added check to pathinfo type key
		$type = $pathinfo['type'];
		if ( ($type == 'child') || ($type == 'parent') ) {

			// --- get relative file path ---
			// 2.1.1: fix for change to use full path index
			$relfilepath = str_replace($vthemename, '', $pathinfo['dirname']);
			$relfilepath = str_replace("\\", "/", $relfilepath);
			while (substr($relfilepath, 0, 1) == '/') {
				$relfilepath = substr($relfilepath, 1, strlen($relfilepath));
			}
			if (strlen($relfilepath) > 0) {$relfilepath = urlencode($relfilepath.'/'.$pathinfo['basename']);}
			else {$relfilepath = urlencode($pathinfo['basename']);}

			// --- create theme editor link ---
			// 2.0.8: fix to duplicate theme parameter
			// TODO: make this a view link if editing is disabled ?
			$editlink = admin_url('theme-editor.php');
			$editlink = add_query_arg('theme', $vthemename, $editlink);
			$editlink = add_query_arg('file', $relfilepath, $editlink);

			// --- set display for parent or child theme template ---
			if (!THEMECHILD) {$displayfile = '[Theme] ';}
			elseif ($type == 'child') {$displayfile = '[Child] ';}
			elseif ($type == 'parent') {$displayfile = '[Parent] ';}
			$displayfile .= substr($pathinfo['dirname'], 1, strlen($pathinfo['dirname'])).'/'.$pathinfo['basename'];

		} elseif ($type == 'plugin') {

			// --- get relative file path ---
			$relfilepath = str_replace($plugindir, '', $pathinfo['dirname']).'/'.$pathinfo['basename'];
			while (substr($relfilepath, 0, 1) == '/') {
				$relfilepath = substr($relfilepath, 1, strlen($relfilepath));
			}

			// --- create plugin editor link ---
			// TODO: make this a view link if editing is disabled ?
			$editlink = admin_url('plugin-editor.php');
			$editlink = add_query_arg('file', $relfilepath, $editlink);

			// --- set display for plugin template ---
			$displayfile = '[Plugin] '.$relfilepath;

		} elseif ($type == 'other') {

			// --- just strip WordPress load path ---
			$displayfile = '[?] '.str_replace($abspath, '', $filepath);
			$editlink = 'javascript:void(0);';

		}

		// --- set node arguments  ---

		$args = array(
			'id'		=> 'template-'.$i,
			'title'		=> $displayfile,
			'parent'	=> 'page-templates',
			'href'		=> $editlink,
			'meta'		=> array(
				'title' => $filepath,
				'class' => 'page-template'
			)
		);

		// --- add admin bar node ---
		$wp_admin_bar->add_node($args);

		// --- increment submenu counter ---
		$i++;
	}

	// --- add page menu template dashicon ---
	echo '<style>#wp-admin-bar-page-templates .ab-icon:before {content: "\\f232"; top: 3px;}</style>';

	bioship_debug("Admin Bar", $wp_admin_bar);
 }
}
