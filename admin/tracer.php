<?php

/**
 * @package BioShip Theme Framework
 * @subpackage bioship
 * @author WordQuest - WordQuest.Org
 * @author DreamJester - DreamJester.Net
 *
 * === THEME TRACER ===
 *
**/

if (!function_exists('add_action')) {exit;}


// 2.0.5: add this to help debug action load order
if (!function_exists('bioship_all_actions_filters')) {

 add_action('all', 'bioship_all_actions_filters');

 function bioship_all_actions_filters() {
	if (THEMEDEBUG) {
		$vfilter = current_filter();
		if (substr($vfilter,0,strlen(THEMEPREFIX.'_')) == THEMEPREFIX.'_') {
			if (substr($vfilter,-9,9) != '_position') {
				echo "<!-- Processing: "; print_r($vfilter);
				if (!has_filter($vfilter)) {echo " [n/a]";}
				echo " -->".PHP_EOL;
			}
		}
	}
 }
}


// TRACER QUERYSTRING USAGE
// ------------------------
// ?themetrace=1 		- to do a theme trace (requires manage_options capability)
// &trace={resource} 	- all, templates, functions, filters, actions	- default: all
// &tracecalls=1		- whether to trace number of function calls		- default: off
// &traceargs=1			- whether to trace function arguments			- default: off
// &tracedisplay=1		- whether to output the trace inline on page	- default: off
// &instance={instance} - prefix name for the debug trace output file	- default: timedate

// Note: for heavy lifting you can use:
// add_action('shutdown',function() {echo "<!-- ".var_dump(debug_backtrace())." -->";});

if (!defined('THEMETRACE')) {define('THEMETRACE',true);}

global $vthemetracer; $vthemetracer = array(); // holds theme trace options
global $vthemetrace; $vthemetrace = array();   // holds theme trace data

// check for load or call trace request
if (isset($_REQUEST['trace'])) {
	$vthemetracer['trace'] = $_REQUEST['trace'];
	// help to forget about plurals if debugging as may already be annoyed... O_o
	// 2.0.5: simplify to array check
	$vtracetypes = array('template', 'function', 'filter', 'action');
	if (in_array($vthemetracer['trace'], $vtracetypes)) {$vthemetracer['trace'] .= 's';}
	elseif ($vthemetracer['trace'] == '') {$vthemetracer['trace'] = false;}
} else {$vthemetracer['trace'] = 'all';}

// whether to trace number of function calls
$vthemetracer['calls'] = false;
if (isset($_REQUEST['tracecalls'])) {
	if ( ($_REQUEST['tracecalls'] == '1') || ($_REQUEST['tracecalls'] == 'yes') ) {$vthemetracer['calls'] = true;}
}

// whether to trace function arguments
$vthemetracer['args'] = false;
if (isset($_REQUEST['traceargs'])) {
	if ( ($_REQUEST['traceargs'] == '1') || ($_REQUEST['tracedisplay'] == 'yes') ) {$vthemetracer['args'] = true;}
}

// whether to trace a single function
$vthemetracer['function'] = false;
if ( (isset($_REQUEST['tracefunc'])) && ($_REQUEST['tracefunc'] != '') ) {$vthemetracer['function'] = $_REQUEST['tracefunc'];}
if ( (isset($_REQUEST['tracefunction'])) && ($_REQUEST['tracefunction'] != '') ) {$vthemetracer['function'] = $_REQUEST['tracefunction'];}

// whether to trace a single filter
$vthemetracer['filter'] = false;
if ( (isset($_REQUEST['tracefilter'])) && ($_REQUEST['tracefilter'] != '') ) {$vthemetracer['filter'] = $_REQUEST['tracefilter'];}

// whether to trace a single template
// $vthemetracer['template'] = false;
// if ( (isset($_REQUEST['tracetemplate'])) && ($_REQUEST['tracetemplate'] != '') ) {$vthemetracer['template'] = $_REQUEST['tracetemplate'];}
// whether to trace a single action
// $vthemetracer['action'] = false;
// if ( (isset($_REQUEST['traceaction'])) && ($_REQUEST['traceaction'] != '') ) {$vthemetracer['action'] = $_REQUEST['traceaction'];}

// whether to output trace inline
$vthemetracer['output'] = false;
if (isset($_REQUEST['tracedisplay'])) {
	if ( ($_REQUEST['tracedisplay'] == '1') || ($_REQUEST['tracedisplay'] == 'yes') ) {$vthemetracer['output'] = true;}
}
if (isset($_REQUEST['traceoutput'])) {
	if ( ($_REQUEST['traceoutput'] == '1') || ($_REQUEST['traceoutput'] == 'yes') ) {$vthemetracer['output'] = true;}
}

// maybe set a trace instance
$vthemetrace['instance'] = false;
if (isset($_REQUEST['instance'])) {$vthemetracer['instance'] = $_REQUEST['instance'];}

// setup empty trace arrays
$vthemetrace['functions'] = array(); $vthemetrace['templates'] = array();
$vthemetrace['calls'] = array(); $vthemetrace['lines'] = array();
$vthemetrace['start'] = date('Y-m-d--H-i-s',time());

// Tracer Function
// ---------------
function bioship_trace($vresourcetype, $vresourcename, $vfilepath, $vfunctionargs=false) {

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	// change trace type short name to long
	if ($vresourcetype == 'F') {$vresourcetype = 'function';}
	elseif ($vresourcetype == 'T') {$vresourcetype = 'template';}
	elseif ($vresourcetype == 'V') {$vresourcetype = 'filter';}
	elseif ($vresourcetype == 'A') {$vresourcetype = 'action';}
	else {return;}

	// strip base file path (not used for filters)
	if ($vresourcetype != 'filter') {
		$vpos = strpos($vfilepath,'themes') + strlen('themes');
		$vfilepath = substr($vfilepath,$vpos,strlen($vfilepath));
	}

	// check for single function or filter trace
	if ( ($vthemetracer['function']) || ($vthemetracer['filter']) ) {
		if ( ($vresourcetype != 'function') && ($vresourcetype != 'filter') ) {return;}
		if ( ($vresourcetype == 'function') && ($vresourcename != $vthemetracer['function']) ) {return;}
		if ( ($vresourcetype == 'filter') && ($vresourcename != $vthemetracer['filter']) ) {return;}
	}

	if ($vresourcetype == 'function') {
		if ( ($vthemetracer['trace'] == 'functions') || ($vthemetracer['trace'] == 'all') ) {
			if (!in_array($vresourcename,$vthemetrace['functions'])) {
				$vthemetrace['functions'][] = $vresourcename;
			}
			if ($vthemetracer['output']) {echo '<!-- function: '.$vresourcename.' ('.$vfilepath.') -->';}
			// keeps track of all function calls
			$vthemetrace['calls'][] = $vresourcename;
			$vtracecount = '('.count($vthemetrace['calls']).')';
		}
	}
	elseif ($vresourcetype == 'template') {
		if ( ($vthemetracer['trace'] == 'templates') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['templates'][] = $vresourcename;
			$vtracecount = '['.count($vthemetrace['templates']).']';
			if ($vthemetracer['output']) {echo '<!-- template: '.$vresourcename.' ('.$vfilepath.') -->';}
		}
	}
	elseif ($vresourcetype == 'action') {
		// 2.0.5: added action tracing...
		if ( ($vthemetracer['trace'] == 'actions') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['actions'][] = $vresourcename;
			$vtracecount = '+'.count($vthemetrace['actions']).'+';
			if ($vthemetracer['output']) {echo '<!-- action: '.$vresourcename.' -->';}
		}
	}
	elseif ($vresourcetype == 'filter') {
		if ( ($vthemetracer['trace'] == 'filters') || ($vthemetracer['trace'] == 'all') ) {
			$vthemetrace['filters'][] = $vresourcename;
			$vtracecount = '<'.count($vthemetrace['filters']).'>';
			if ($vthemetracer['output']) {echo '<!-- filter: '.$vresourcename.' -->';}
		}
	}

	// add the tracer line to the load record
	// 1.9.8: fix to tracer line
	$vmemusage = memory_get_usage(true); $vloadtime = bioship_timer_time();
	// 2.0.5: reorder tracer line output for readability
	$vtracerline = $vtracecount.'::'.$vresourcetype.'::'.$vresourcename.'::'.$vmemusage.'::'.$vloadtime.'::'.$vfilepath;
	$vthemetrace['lines'][] = $vtracerline;

	// if ($vthemetracer['output']) {echo '<!-- '.$vtracerline.'-->';}

	// 1.9.8: full trace logging of function/filter arguments passed
	if ( ($vresourcetype == 'function') || ($vresourcetype == 'filter') ) {
		if ( ($vthemetracer['args']) && ($vfunctionargs) ) {

			if ($vresourcetype == 'function') {
				ob_start(); var_dump($vfunctionargs); $vdump = ob_get_contents(); ob_end_clean();
				if ($vthemetracer['output']) {echo '<!-- function arguments: '.$vdump.' -->';}
				$vdumpline = $vresourcetype.': '.$vresourcename.PHP_EOL.$vdump.PHP_EOL;
			} elseif ($vresourcetype == 'filter') {
				ob_start(); var_dump($vfunctionargs['in']); $vin = ob_get_contents(); ob_end_clean();
				ob_start(); var_dump($vfunctionargs['out']); $vout = ob_get_contents(); ob_end_clean();
				$vdumpline = $vresourcetype.': '.$vresourcename.PHP_EOL;
				// 2.0.5: fix to concatenate properly here
				$vdumpline .= 'value in: '.$vin.PHP_EOL.'value out: '.$vout;
			}

			if ($vthemetracer['instance']) {
				if ($vthemetracer['instance'] == '') {$vdumpfile = '';} // no file writing
				else {$vdumpfile = $vthemedebugdir.DIRSEP.'_'.$vinstance.'--traceargs.txt';}
			} else {$vdumpfile = $vthemedebugdir.DIRSEP.$vthemetrace['start'].'--traceargs.txt';}

			if ($vdumpfile != '') {
				if (file_exists($vdumpfile)) {$vfh = @fopen($vdumpfile,'a');} else {$vfh = @fopen($vdumpfile,'w');}
				@fwrite($vfh,$vdumpline); @fclose($vfh);
			}
		}
	}

}

// Trace Processor
// ---------------
function bioship_trace_processsor() {

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	if ($vthemetracer['output']) {echo "<!-- Theme Trace Processor Started -->";}

	global $vthemetimestart; $vtimedate = date('Y-m-d--H:i:s',$vthemetimestart);
	if (isset($_REQUEST['instance'])) {$vinstance = $_REQUEST['instance'];}

	// write load log
	if ( ($vthemetracer['trace']) && (count($vthemetrace['lines']) > 0) ) {

		if ($vthemetracer['instance']) {
			if ($vthemetracer['instance'] == '') {$vtraceloadsfile = '';} // no file writing
			else {$vtraceloadsfile = $vthemedebugdir.DIRSEP.'_'.$vinstance.'--traceload.txt';}
		} else {$vtraceloadsfile = $vthemedebugdir.DIRSEP.$vthemetrace['start'].'--traceload.txt';}
		$vtracercontents = implode(PHP_EOL, $vthemetrace['lines']);

		// being user debug files, these should be fine to write directly
		if ($vtraceloadsfile != '') {
			$vfh = @fopen($vtraceloadsfile,'w'); @fwrite($vfh,$vtracercontents); @fclose($vfh);
			if (!file_exists($vtraceloadsfile)) {return;} // if writing failed
		}
	}

	// write call log
	if ( ($vthemetracer['calls']) && (count($vthemetrace['calls']) > 0) ) {

		// parse the tracer call log into an occurrence log
		// ...extract occurrence data from the tracer calls
		$vcalls = array();
		foreach ($vthemetrace['calls'] as $vtrace) {
			if (isset($vcalls[$vtrace])) {
				$vcalls[$vtrace] = (int)$vcalls[$vtrace] + 1;
			} else {$vcalls[$vtrace] = 1;}
		}
		arsort($vcalls); // reorder by occurrences

		// gather the data into lines...
		$voccurlines = array();
		foreach ($vcalls as $vfunctionname => $voccurs) {$voccurlines[] = $vfunctionname.'::'.$voccurs;}

		// write the call occurences log file
		$voccurdata = implode(PHP_EOL,$voccurlines);
		$voccurdata = "Theme Function Call Occurrences:".PHP_EOL.$voccurdata;
		$vorderedtraces = implode(PHP_EOL,$vthemetrace['calls']);
		$voccurdata .= PHP_EOL.PHP_EOL."All Function Calls Order:".PHP_EOL.$vorderedtraces;

		if ($vthemetracer['instance']) {
			if ($vthemetracer['instance'] == '') {$vtracecallsfile = '';} // no file writing
			else {$vtracecallsfile = $vthemedebugdir.DIRSEP.'_'.$vinstance.'--tracecalls.txt';}
		} else {$vtracecallsfile = $vthemedebugdir.DIRSEP.$vthemetrace['start'].'--tracecalls.txt';}

		// being user debug files, these should be fine to write directly
		if ($vtracecallsfile != '') {
			$vfh = @fopen($vtracecallsfile,'w'); @fwrite($vfh,$voccurdata); @fclose($vfh);
		}

		// maybe output at end of screen for source viewing
		if ($vthemetracer['output']) {
			$voccurdata = str_replace('::',' : ',$voccurdata);
			echo "<!-- ".$voccurdata." -->";
		}
	}
}

// run the trace processor on shutdown
add_action('shutdown', 'bioship_trace_processsor');

?>