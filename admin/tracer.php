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

// Note: for heavy lifting use: var_dump(debug_backtrace());

// TODO: maybe add filter tracers?
// TODO: full trace logging with function arguments

// check for load or call trace request
if (isset($_REQUEST['tracethemeloads'])) {
	$vthemetraceloads = $_REQUEST['tracethemeloads'];
	// help to forget about plurals if debugging as may already be annoyed...
	if ($vthemetraceloads == 'template') {$vthemetraceloads = 'templates';}
	elseif ($vthemetraceloads == 'function') {$vthemetraceloads = 'functions';}
	elseif ($vthemetraceloads == 'filter') {$vthemetraceloads = 'filters';}
	elseif ($vthemetraceloads == '') {$vthemetraceloads = false;}
} else {$vthemetraceloads = false;}

$vthemetracecalls = false;
if (isset($_REQUEST['tracethemecalls'])) {
 if ($_REQUEST['tracethemecalls']) == '1') {$vthemetracecalls = true;} }

$vthemefunctions = array(); $vthemetemplates = array();
$themefunctioncalls = array(); $vthemetrace = array();

// Tracer Function
// ---------------
function skeleton_trace($vresource,$vresourcename,$vfilepath,$vfunctionargs) {

	global $vthemetraceloads, $vthemetracecalls;
	global $vthemefunctions, $vthemetemplates;
	global $vthemefunctioncalls, $vthemetrace;

	if (isset($_REQUEST['tracedisplay'])) {
	 if ($_REQUEST['tracedisplay'] == '1') {$voutputdisplay = true;} }

	if ($vresourcetype == 'function') {
		if ($vthemetraceloads != 'templates') {
			if (!in_array($resourcename,$vthemefunctions)) {
				$vthemefunctions[] = $vresourcename;
				$vtracecount = '('.count($vthemefunctions).')';
			} else {
				if ( ($vthemetracecalls) && ($voutputdisplay) ) {
					echo '<!-- '.$vresourcename.' -->';
				}
			}
			// keeps track of all function calls
			$vthemefunctioncalls[] = $vresourcename;
		}
	}
	elseif ($vresourcetype == 'template') {
		if ($vthemetraceloads != 'functions') {
			$vthemetemplates[] = $vresourcename;
			$vtracecount = '['.count($vthemetemplates).']';
		}
	}
	// possibly filters could be tracked too?
	// elseif ($vresourcetype == 'filters') {}

	// add the tracer line to the load record
	$vtracerline = $vtracecount.'::'.$memusage.'::'.$loadtime.'::'.$vresourcetype.'::'.$vresourcename;
	$vthemetrace[] = $vtracerline;
	if ($voutputdisplay) {echo '<!-- '.$vtracerline.'-->';}


	// TODO: full trace logging of function arguments passed

	// ...and maybe full trace logging of filter arguments passed?

}

// Trace Processor
// ---------------
function skeleton_trace_processsor() {

	global $vthemetraceloads, $vthemetracecalls;
	global $vthemefunctions, $vthemetemplates;
	global $vthemefunctioncalls, $vthemetrace;

	// echo "<!-- Theme Trace Processor Started -->";

	$vtimedate = date('Y-m-d--H:i:s',time());
	if (isset($_REQUEST['instance'])) {
		$vinstance = abs(intval(($_REQUEST['instance']));
	if (isset($_REQUEST['tracedisplay'])) {
	 if ($_REQUEST['tracedisplay'] == '1') {$voutputdisplay = true;} }

	// set and maybe create tracer dir
	if (is_child_theme()) {$vtracerdir = get_stylesheet_directory();}
	else {$vtracerdir = get_template_directory();}
	$vtracerdir .= '/traces/';
	if (!is_dir($vtracerdir)) {umask(0000); @mkdir($vtracerdir,0644);}
	if (!is_writeable($vtracerdir)) {unmask(0000); @chmod($vtracerdir,0644);}

	// write load log
	if ($vthemetraceloads) {
		if ($vinstance != '') {$vtraceloadsfile = $tracerdir.'/_'.$vinstance.'--traceload.txt';}
		else {$vtraceloadsfile = $vtracerdir.'/'.$vtimedate.'--traceload.txt';}
		$vtracercontents = implode(PHP_EOL,$vthemetrace);

		// being user debug files, these should be fine to write directly
		$vfh = @fopen($vtraceloadsfile,'w'); @fwrite($vfh,$vtracercontents); @fclose($vfh);
	}

	// write call log
	if ($vthemetracecalls) {
		if ($vinstance != '') {$vtraceloadsfile = $tracerdir.'/_'.$vinstance.'--tracecalls.txt';}
		else {$vtraceloadsfile = $vtracerdir.'/'.$vtimedate.'--tracecalls.txt';}

		// parse the tracer call log into an occurrence log
		// ...extract occurrence data from the tracer calls
		$vcalls = array();
		foreach ($vthemefunctioncalls as $vtrace) {
			if (isset($vcalls[$vtrace])) {
				$vcalls[$vtrace] = (int)$vcalls[$vtrace] + 1;
			} else {$vcalls[$vtrace] = 1;}
		}
		arsort($vcalls); // reorder by occurrences

		// gather the data into lines...
		$voccurlines = array();
		foreach ($vcalls as $vfunctionname => $voccurs) {
			$voccurlines[] = $vfunctionname.'::'.$voccurs;
		}

		// write the call occurences log file
		$voccurdata = implode(PHP_EOL,$voccurlines);
		$voccurdata = "Theme Function Call Occurrences:".PHP_EOL.$voccurdata;
		$vorderedtraces = implode(PHP_EOL,$vthemefunctioncalls);
		$voccurdata .= "All Function Calls Order:".PHP_EOL.$vorderedtraces;

		// being user debug files, these should be fine to write directly
		$vfh = @fopen($vtracecallsfile,'w'); @fwrite($vfh,$voccurdata); @fclose($vfh);

		// maybe output at end of screen for source viewing
		if (isset($_REQUEST['tracedisplay'])) {
		 if ($_REQUEST['tracedisplay'] == '1') {
			$occurdata = str_replace('::',' : ',$occurdata);
			echo "<!-- ".$occurdata." -->";
		 }
		}
	}
}

// run the trace processor on shutdown
add_action('shutdown','skeleton_trace_processsor');

?>