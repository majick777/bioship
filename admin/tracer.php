<?php

// ============================
// === BioShip Theme Tracer ===
// ============================

// --- no direct load ---
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

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
// - Admin Bar Templates Dropdown
// ----------------------------


// Development TODOs
// -----------------
// - fix template view links for when editing is disabled ?


// -------------------------
// Debug Queries on Shutdown
// -------------------------
// 2.1.4: moved here from functions.php
if ( THEMEDEBUG ) {
	// 2.0.5: also use save queries constant for debugging output
	if ( !defined( 'SAVEQUERIES' ) ) {
		define( 'SAVEQUERIES', true );
	}
	if ( SAVEQUERIES ) {
		if ( !function_exists( 'bioship_debug_saved_queries' ) ) {

		 // 2.1.1: moved add_action internally for consistency
		 add_action( 'shutdown', 'bioship_debug_saved_queries' );

		 function bioship_debug_saved_queries() {
			global $wpdb;
			$queries = $wpdb->queries;
			bioship_debug( "Saved Queries", $queries );
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
if ( isset( $_REQUEST['trace'] ) ) {
	$trace = trim( $_REQUEST['trace'] );
	// 2.0.5: simplify to array check
	$tracetypes = array( 'template', 'function', 'filter', 'action' );
	// --- help to forget about plurals if debugging as may already be annoyed... O_o
	if ( in_array( $trace, $tracetypes ) ) {
		$trace .= 's';
	}
	// 2.1.1: validate specified trace option
	$valid = array( 'templates', 'functions', 'filters', 'actions', 'all' );
	if ( in_array( $trace, $valid ) ) {
		$vthemetracer['trace'] = $trace;
	} else {
		$vthemetracer['trace'] = false;
	}
} else {
	$vthemetracer['trace'] = 'all';
}

// --- whether to trace number of function calls ---
$vthemetracer['calls'] = false;
if ( isset( $_REQUEST['tracecalls'] ) ) {
	if ( ( '1' == $_REQUEST['tracecalls'] ) || ( 'yes' == $_REQUEST['tracecalls'] ) ) {
		$vthemetracer['calls'] = true;
	}
}

// --- whether to trace function arguments ---
$vthemetracer['args'] = false;
if ( isset( $_REQUEST['traceargs'] ) ) {
	if ( ( '1' == $_REQUEST['traceargs'] ) || ( 'yes' == $_REQUEST['tracedisplay'] ) ) {
		$vthemetracer['args'] = true;
	}
}

// --- whether to trace a single function ---
$vthemetracer['function'] = false;
if ( isset( $_REQUEST['tracefunction'] ) && ( '' != trim( $_REQUEST['tracefunction'] ) ) ) {
	$vthemetracer['function'] = trim( $_REQUEST['tracefunction'] );
} elseif ( isset( $_REQUEST['tracefunc'] ) && ( '' != trim( $_REQUEST['tracefunc'] ) ) ) {
	$vthemetracer['function'] = trim( $_REQUEST['tracefunc'] );
}

// --- whether to trace a single filter ---
$vthemetracer['filter'] = false;
if ( isset($_REQUEST['tracefilter'] ) && ( '' != trim( $_REQUEST['tracefilter'] ) ) ) {
	$vthemetracer['filter'] = trim( $_REQUEST['tracefilter'] );
}

// --- whether to trace a single template ---
// 2.1.1: activated tracetemplate variable
$vthemetracer['template'] = false;
if ( isset( $_REQUEST['tracetemplate'] ) && ( '' != trim( $_REQUEST['tracetemplate'] ) ) ) {
	$tracetemplate = trim( $_REQUEST['tracetemplate'] );
	$valid = array( 'loop', 'header', 'footer', 'sidebar', 'content' );
	if ( in_array( $tracetemplate, $valid ) ) {
		$vthemetracer['template'] = $tracetemplate;
	}
}

// --- whether to trace a single action ---
$vthemetracer['action'] = false;
// 2.1.1: activated traceaction variable
if ( isset( $_REQUEST['traceaction'] ) && ( '' != trim( $_REQUEST['traceaction'] ) ) ) {
	$vthemetracer['action'] = trim( $_REQUEST['traceaction'] );
}

// --- whether to output trace inline ---
$vthemetracer['output'] = false;
if ( isset($_REQUEST['tracedisplay'] ) ) {
	if ( ( '1' == $_REQUEST['tracedisplay'] ) || ( 'yes' == $_REQUEST['tracedisplay'] ) ) {
		$vthemetracer['output'] = true;
	}
}
if ( isset( $_REQUEST['traceoutput'] ) ) {
	if ( ( '1' == $_REQUEST['traceoutput'] ) || ( 'yes' == $_REQUEST['traceoutput'] ) ) {
		$vthemetracer['output'] = true;
	}
}

// --- setup empty trace data array ---
// 2.1.1: simplified empty data array
$vthemetrace = array(
	'instance'	=> false,
	'start'		=> date( 'Y-m-d--H-i-s', time() ),
	'end'		=> '',
	'functions'	=> array(),
	'templates'	=> array(),
	'actions'	=> array(),
	'filters'	=> array(),
	'calls'		=> array(),
	'lines'		=> array(),
);

// --- maybe set a trace instance ---
if ( isset( $_REQUEST['instance'] ) ) {
	// 2.2.0: sanitize instance to alphanumeric for security
	$instance = trim( $_REQUEST['instance'] );
	$check = preg_match( '/^[a-zA-Z0-9_]+$/', $instance );
	if ( $check ) {
		$vthemetracer['instance'] = $instance;
	}
}


// -----------------------
// === Tracer Function ===
// -----------------------
// 2.1.1: added missing function_exists wrapper
// 2.1.1: shortened arguments resourcetype, resourcename, functionargs
if ( !function_exists( 'bioship_trace' ) ) {
 function bioship_trace( $type, $name, $filepath, $args = false ) {

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	// --- change trace type abbreviation to name ---
	if ( 'F' == $type ) {
		$type = 'function';
	} elseif ( 'T' == $type ) {
		$type = 'template';
	} elseif ( 'V' == $type ) {
		$type = 'filter';
	} elseif ( 'A' == $type ) {
		$type = 'action';
	} else {
		return;
	}

	// --- strip base file path ---
	// (not used for filters)
	// 2.2.0: also not used for actions
	if ( ( 'filter' != $type ) && ( 'action' != $type ) ) {
		$pos = strpos( $filepath, 'themes' ) + strlen( 'themes' );
		$filepath = substr( $filepath, $pos, strlen( $filepath ) );
	}

	// --- check for single function / filter / template / action trace ---
	// 2.1.1: added single template / action matching
	if ( $vthemetracer['function'] || $vthemetracer['filter'] || $vthemetracer['template'] || $vthemetracer['action'] ) {
		if ( ( 'function' != $type ) && ( 'filter' != $type ) ) {return;}
		if ( ( 'function' == $type ) && ( $name != $vthemetracer['function'] ) ) {return;}
		if ( ( 'filter' == $type ) && ( $name != $vthemetracer['filter'] ) ) {return;}
		if ( ( 'action' == $type ) && ( $name != $vthemetracer['action'] ) ) {return;}
		// note: match via args (template slug) not full template name
		if ( ( 'template' == $type ) && ( $args != $vthemetracer['template'] ) ) {return;}
	}

	// --- maybe add to resource counts ---
	// 2.1.1: added return if not matching desired trace
	if ( 'function' == $type ) {
		if ( ( 'functions' == $vthemetracer['trace'] ) || ( 'all' == $vthemetracer['trace'] ) ) {
			if ( !in_array( $name, $vthemetrace['functions'] ) ) {
				$vthemetrace['functions'][] = $name;
			}
			if ( $vthemetracer['output'] ) {
				echo '<!-- traced function: ' . esc_attr( $name ) . ' (' . esc_attr( $filepath ) . ') -->';
			}

			// --- keeps track of all function calls ---
			$vthemetrace['calls'][] = $name;
			$tracecount = '(' . count( $vthemetrace['calls'] ) . ')';
		} else {
			return;
		}
	} elseif ( 'template' == $type ) {
		if ( ( 'templates' == $vthemetracer['trace'] ) || ( 'all' == $vthemetracer['trace'] ) ) {
			$vthemetrace['templates'][] = $name;
			$tracecount = '[' . count( $vthemetrace['templates'] ) . ']';
			if ( $vthemetracer['output'] ) {
				echo '<!-- traced template: ' . esc_attr( $name ) . ' (' . esc_attr( $filepath ) . ') -->';
			}
			// 2.1.1: set current template filepath for action filepath tracing
			$vthemetrace['currentfile'] = $filepath;
			return;
		} else {
			return;
		}
	} elseif ( 'action' == $type ) {
		// 2.0.5: added action tracing...
		if ( ( 'actions' == $vthemetracer['trace'] ) || ( 'all' == $vthemetracer['trace'] ) ) {
			$vthemetrace['actions'][] = $name;
			$tracecount = '+' . count( $vthemetrace['actions'] ) . '+';
			// 2.1.1: override filepath with current template filepath
			if ( isset( $vthemetrace['currentfile'] ) ) {
				$filepath = $vthemetrace['currentfile'];
			}
			if ( $vthemetracer['output'] ) {
				echo '<!-- traced action: ' . esc_attr( $name ) . ' -->';
			}
		} else {
			return;
		}
	} elseif ( 'filter' == $type ) {
		if ( ( 'filters' == $vthemetracer['trace'] ) || ( 'all' == $vthemetracer['trace'] ) ) {
			$vthemetrace['filters'][] = $name;
			// 2.2.0: fix to global variable type (themetrace)
			$tracecount = '<' . count( $vthemetrace['filters'] ) . '>';
			if ( $vthemetracer['output'] ) {
				echo '<!-- traced filter: ' . esc_attr( $name ) . ' -->';
			}
		} else {
			return;
		}
	}

	// --- add the tracer line to the load record ---
	// 1.9.8: fix to tracer line
	// 2.0.5: reordered tracer line output for readability
	$memoryusage = memory_get_usage( true );
	$loadtime = bioship_timer_time();
	$tracerline = $tracecount . '::' . $type . '::' . $name . '::' . $memoryusage . '::' . $loadtime . '::' . $filepath;
	$vthemetrace['lines'][] = $tracerline;
	// if ($vthemetracer['output']) {echo '<!-- tracer line: '.esc_attr($tracerline).'-->';}

	// --- write argument trace debug log ---
	// 1.9.8: full trace logging of function/filter arguments passed
	// (note: no arguments are passed for actions or templates)
	if ( ( 'function' == $type ) || ( 'filter' == $type ) ) {
		if ( $vthemetracer['args'] && $args ) {

			$traceline = '';
			if ( 'function' == $type ) {
				ob_start();
				var_dump( $args ); 
				$dump = ob_get_contents();
				ob_end_clean();
				if ( $vthemetracer['output'] ) {
					echo '<!-- function arguments: ' . esc_attr( $dump ) . ' -->';
				}
				$traceline = $type . ': ' . $name . PHP_EOL . $dump . PHP_EOL;
			} elseif ( 'filter' == $type ) {
				ob_start(); 
				var_dump( $args['in'] ); 
				$in = ob_get_contents(); 
				ob_end_clean();
				ob_start();
				var_dump( $args['out'] ); 
				$out = ob_get_contents();
				ob_end_clean();
				$traceline = $type . ': '.$name.PHP_EOL;
				// 2.0.5: fix to concatenate properly here
				$traceline .= 'Filter Value in: ' . $in . PHP_EOL . 'Filter Value out: ' . $out;
			}

			// 2.1.1: fix check for undefined index warning
			if ( isset( $vthemetracer['instance'] ) ) {
				// 2.2.0: fix to old undefined variable (instance)
				if ( '' == $vthemetracer['instance'] ) {
					// no file writing
					$tracefile = '';
				} else {
					$tracefile = '_' . $vthemetrace['instance'] . '--traceargs.txt';
				}
			} else {
				$tracefile = $vthemetrace['start'] . '--traceargs.txt';
			}

			if ( '' != $tracefile ) {
				// 2.0.7: replace direct with WP file system writing
				// 2.1.1: use bioship_write_file with append method
				// 2.1.1: use method whether file exists already or not
				bioship_write_debug_file( $tracefile, $traceline );
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
if ( !function_exists( 'bioship_trace_processor' ) ) {

 // --- run the trace processor on shutdown ---
 add_action( 'shutdown', 'bioship_trace_processsor' );

 function bioship_trace_processsor() {

	// 2.1.1: bug out if not tracing
	if ( !THEMETRACE ) {
		return;
	}

	global $vthemetracer, $vthemetrace, $vthemedebugdir;

	// --- set trace end to shutdown time ---
	$vthemetrace['end'] = date( 'Y-m-d--H-i-s', time() );

	// --- processor started message ---
	if ( $vthemetracer['output'] ) {
		echo "<!-- Theme Trace Processor Started -->";
	}

	// --- maybe get tracer instance ---
	// 2.2.0: get from already set global
	$instance = $vthemetracer['instance'];

	// --- write load log ---
	if ( $vthemetracer['trace'] && ( count( $vthemetrace['lines'] ) > 0 ) ) {

		// --- set trace loads debug filename ---
		// 2.1.1: fix check for undefined index warning
		if ( false !== $instance ) {
			if ( '' == $instance ) {
				// no file writing
				$traceloadsfile = '';
			} else {
				$traceloadsfile = '_' . $instance . '--traceload.txt';
			}
		} else {
			$traceloadsfile = $vthemetrace['start'] . '--traceload.txt';
		}
		$tracercontents = implode( PHP_EOL, $vthemetrace['lines'] );

		// --- write trace loads debug file ---
		if ( '' != $traceloadsfile ) {
			// 2.0.7: replace direct with file system debug writing
			bioship_write_debug_file( $traceloadsfile, $tracercontents );
			// 2.2.0: fix to global variable typo (vthemetrace)
			if ( $vthemetracer['output'] ) {
				echo "<!-- Trace Written to: " . esc_attr( $traceloadsfile ) . " -->";
			}
		}
	}

	// --- write call log ---
	if ( $vthemetracer['calls'] && ( count( $vthemetrace['calls'] ) > 0 ) ) {

		// -- parse the tracer call log into an occurrence log ---
		// ...extract occurrence data from the tracer calls
		$calls = array();
		foreach ( $vthemetrace['calls'] as $trace ) {
			// 2.2.0: fix to key index typo (vtrace)
			if ( isset( $calls[$trace] ) ) {
				$calls[$trace] = (int)$calls[$trace] + 1;
			} else {
				$calls[$trace] = 1;
			}
		}
		// --- reorder by occurrences ---
		arsort( $calls );

		// --- gather the data into lines ---
		$occurlines = array();
		foreach ( $calls as $functionname => $occurs ) {
			$occurlines[] = $functionname . '::' . $occurs;
		}

		// --- write the call occurences log file ---
		$occurdata = implode( PHP_EOL, $occurlines );
		$occurdata = __( 'Theme Function Call Occurrences', 'bioship') . ':' . PHP_EOL . $occurdata;
		$orderedtraces = implode( PHP_EOL, $vthemetrace['calls'] );
		$occurdata .= PHP_EOL . PHP_EOL . __( 'All Function Calls Order', 'bioship' ) . ':' . PHP_EOL . $orderedtraces;

		// --- set trace calls debug filename ---
		if ( false !== $instance ) {
			if ( '' == $instance ) {
				// no file writing
				$tracecallsfile = '';
			} else {
				$tracecallsfile = '_' . $instance . '--tracecalls.txt';
			}
		} else {
			$tracecallsfile = $vthemetrace['start'] . '--tracecalls.txt';
		}

		// --- write trace calls debug file ---
		if ( '' != $tracecallsfile ) {
			// 2.0.7: replace direct with WP file system debug writing
			bioship_write_debug_file( $tracecallsfile, $occurdata );
		}
	}

	// --- maybe output at end of screen for source viewing ---
	if ( $vthemetracer['output'] ) {

		// --- main trace results ---
		echo "<!-- Trace Processor Finished -->";
		echo "<!-- Results: " . esc_attr( print_r( $vthemetrace, true ) ) . " -->";

		// --- occurrences results ---
		if ( isset( $occurdata ) ) {
			$occurdata = str_replace( '::', ' : ', $occurdata );
			echo "<!-- Trace Occurrences: " . PHP_EOL . esc_attr( $occurdata ) . " -->";
		}
	}

 }
}

// -----------------------
// Filter Processing Debug
// -----------------------
// 2.0.5: add this to help debug action load order
if ( !function_exists( 'bioship_all_actions_filters' ) ) {

 // --- add to all actions / filters ---
 add_action( 'all', 'bioship_all_actions_filters' );

 function bioship_all_actions_filters() {

 	// --- for theme debug mode only ---
	if ( !THEMEDEBUG ) {
		return;
	}

	// --- processing filter debug output ---
	$filter = current_filter();
	if ( THEMEPREFIX . '_' == substr( $filter, 0, strlen( THEMEPREFIX . '_' ) ) ) {
		if ( substr( $filter, -9, 9 ) != '_position' ) {
			echo "<!-- [Processing Filter] " . esc_attr( $filter );
				if ( !has_filter( $filter ) ) {
					echo " [n/a]";
				}
			echo " -->" . PHP_EOL;
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
if ( !function_exists( 'bioship_check_templates_loader' ) ) {

 add_action( 'init', 'bioship_check_templates_loader' );

 function bioship_check_templates_loader() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- for Debug Mode or for any Site Admin ---
	// 2.1.1: added edit_theme_options capability
  	if ( THEMEDEBUG || current_user_can( 'edit_theme_options' ) || current_user_can( 'manage_options' ) ) {

		// 2.2.0: fix admin bar render position (breaking templates dropdown)
		remove_action( 'wp_body_open', 'wp_admin_bar_render', 0 );
		if ( !has_action( 'wp_footer', 'wp_admin_bar_render' ) ) {
			add_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		}
	 
		// --- check included files on pageload ---
		add_action( 'wp_loaded', 'bioship_check_theme_includes' );

		// --- check included templates after pageload ---
		add_action( 'wp_footer', 'bioship_check_theme_templates', 999 );

		bioship_debug( 'Templates Trace Loaded' );
	}
 }
}

// ----------------------------
// Get All Included Theme Files
// ----------------------------
// 1.8.5: added this debugging function
if ( !function_exists( 'bioship_get_theme_includes' ) ) {
 function bioship_get_theme_includes() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- get included files ---
	$includedfiles = get_included_files();
    $themeincludes = array();

	// --- normalize theme paths for matching ---
	$styledir = str_replace( "\\", "/", get_stylesheet_directory() );
	$templatedir = str_replace( "\\", "/", get_template_directory() );
	$plugindir = str_replace( "\\", "/", WP_PLUGIN_DIR );

	// --- loop included files ---
	foreach ( $includedfiles as $i => $includedfile ) {

		// --- normalize include path for match ---
		$includedfile = str_replace( "\\", "/", $includedfile );

		// --- check if included file is in stylesheet directory ---
		if ( $styledir == substr( $includedfile, 0, strlen( $styledir ) ) ) {

			// --- strip stylesheet dir from include path ---
			// 2.0.1: re-add full filepath to pathinfo array
			// 2.1.1: just index via full file path
			// 2.1.2: add type key to pathinfo
			$pathinfo = pathinfo( str_replace( $styledir, '', $includedfile ) );
			$pathinfo['type'] = 'child';
			$themeincludes[$includedfile] = $pathinfo;

		} else {

			// --- if stylesheet is same as template, this is not a child theme ---
			// 2.1.1: use THEMECHILD constant and check in parent directory
			if ( THEMECHILD && ( $templatedir == substr( $includedfile, 0, strlen( $templatedir ) ) ) ) {

					// --- strip template directory from include path ---
					// 2.0.7: fix to variable name (pathinfo)
					// 2.1.1: just index via full file path
					// 2.1.2: add type key to pathinfo
					$pathinfo = pathinfo( str_replace( $templatedir, '', $includedfile ) );
					$pathinfo['type'] = 'parent';
					$themeincludes[$includedfile] = $pathinfo;

			} else {

				// --- possibly a plugin template file ---
				// 2.1.1: handle other included files
				$pathinfo = pathinfo( $includedfile );

				// 2.1.2: added type key to pathinfo (plugin/other)
				if ( 0 ==- strpos( $includedfile, $plugindir ) ) {
					$pathinfo['type'] = 'plugin';
				} else {
					$pathinfo['type'] = 'other';
				}
				$themeincludes[$includedfile] = $pathinfo;
			}

		}
	}

	return $themeincludes;
 }
}

// --------------------------
// Check Theme Included Files
// --------------------------
// 1.8.5: added this debugging function
if ( !function_exists( 'bioship_check_theme_includes' ) ) {
 function bioship_check_theme_includes() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

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
if ( !function_exists( 'bioship_check_theme_templates' ) ) {
 function bioship_check_theme_templates() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check template includes ---
	// 2.1.1: change vtemplateincludes global to vthemetemplates
	global $vthemeincludes, $vthemetemplates;
	$vthemetemplates = bioship_get_theme_includes();

	// --- strip out included theme files from template list ---
	$includespath = str_replace( "\\", "/", ABSPATH . WPINC );
	foreach ( $vthemetemplates as $filepath => $pathinfo ) {
		// 2.1.1: match with full file path instead
		// 2.1.1: ignore standard wordpress includes files
		if ( array_key_exists( $filepath, $vthemeincludes )
		  || strstr( $filepath, $includespath )
		  || strstr( $filepath, 'wp-admin/includes' ) ) {
			unset( $vthemetemplates[$filepath] );
		}
	}
	bioship_debug( "Included Template Files", $vthemetemplates );

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
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {

		// --- check filtered setting ---
		// 2.1.1: added missing global declaration for vthemesettings
		global $vthemesettings;
		$addmenu = isset( $vthemesettings['templatesdropdown'] ) ? $vthemesettings['templatesdropdown'] : true;

		// 2.1.1: added theme debug override to display
		if ( THEMEDEBUG ) {
			$addmenu = true;
		}
		$addmenu = bioship_apply_filters( 'admin_template_list_dropdown', $addmenu );
		bioship_debug( 'Template List Dropdown', $addmenu );
		$addmenu = true;
		if ( !$addmenu ) {
			return;
		}

		// --- add template dropdown list ---
		add_action( 'wp_before_admin_bar_render', 'bioship_admin_template_dropdown' );
	}

 }
}

// ----------------------------
// Admin Bar Templates Dropdown
// ----------------------------
// 2.0.1: added dropdown template list to admin bar
if ( !function_exists( 'bioship_admin_template_dropdown' ) ) {
 function bioship_admin_template_dropdown() {

	global $wp_admin_bar, $vthemetemplates, $vthemename;

	// --- set menu settings ---
	// 2.2.0: add ab-label class wrapper to title anchor
	$menu = array(
		'id' => 'page-templates',
		'title' => '<span class="ab-icon"></span><span class="ab-label">' . esc_html( __( 'Templates', 'bioship' ) ) . '</span>',
		'href' => 'javascript:void(0);',
		'meta' => array(
			'title' => esc_attr( __( 'Ordered list of included templates for this pageload.', 'bioship' ) )
		)
	);

	// --- add menu to admin bar ---
	$wp_admin_bar->add_menu( $menu );

	// --- normalize paths for matching ---
	$plugindir = str_replace( "\\", "/", WP_PLUGIN_DIR );
	$abspath = str_replace( "\\", "/", ABSPATH );
	$active_plugins = get_option( 'active_plugins' );

	bioship_debug( "Included Template Files B", $vthemetemplates );

	// --- loop templates and add to menu ---
	$i = 0;
	foreach ( $vthemetemplates as $filepath => $pathinfo ) {

	    $editlink = '';

		// --- handle template types ---
		// 2.1.2: added check to pathinfo type key
		$type = $pathinfo['type'];
		if ( ( 'child' == $type ) || ( 'parent' == $type ) ) {

			// --- get relative file path ---
			// 2.1.1: fix for change to use full path index
			$relfilepath = str_replace( $vthemename, '', $pathinfo['dirname'] );
			$relfilepath = str_replace( "\\", "/", $relfilepath );
			while ( '/' == substr( $relfilepath, 0, 1 ) ) {
				$relfilepath = substr( $relfilepath, 1, strlen( $relfilepath ) );
			}
			if ( strlen( $relfilepath ) > 0 ) {
				$relfilepath = urlencode( $relfilepath . '/' . $pathinfo['basename'] );
			} else {
				$relfilepath = urlencode( $pathinfo['basename'] );
			}

			// --- create theme editor link ---
			// 2.0.8: fix to duplicate theme parameter
			// TODO: make this a view-only link if editing is disabled ?
			// if ( defined( 'DISABLE_FILE_EDIT' ) ) {}
			$editlink = admin_url( 'theme-editor.php' );
			// 2.2.0: fix to link argument for parent theme file
			if ( THEMECHILD && ( 'parent' == $type ) ) {
				$editlink = add_query_arg( 'theme', THEMEPARENT, $editlink );
			} else {
				$editlink = add_query_arg( 'theme', THEMESLUG, $editlink );
			}
			$editlink = add_query_arg( 'file', $relfilepath, $editlink );

			// --- set display for parent or child theme template ---
			if ( !THEMECHILD ) {
				$displayfile = '[Theme] ';
			} elseif ( 'child' == $type ) {
				$displayfile = '[Child] ';
			} elseif ( 'parent' == $type ) {
				$displayfile = '[Parent] ';
			}
			$displayfile .= substr( $pathinfo['dirname'], 1, strlen( $pathinfo['dirname'] ) ) . '/' . $pathinfo['basename'];

		} elseif ( 'plugin' == $type ) {

			// --- get relative file path ---
			$relfilepath = str_replace( $plugindir, '', $pathinfo['dirname'] ) . '/' . $pathinfo['basename'];
			while ( '/' == substr( $relfilepath, 0, 1 ) ) {
				$relfilepath = substr( $relfilepath, 1, strlen( $relfilepath ) );
			}

			// --- create plugin editor link ---
			// TODO: make this a view link if editing is disabled ?
			// if ( defined( 'DISABLE_FILE_EDIT' ) ) {}
			$editlink = admin_url( 'plugin-editor.php' );
			$editlink = add_query_arg( 'file', $relfilepath, $editlink );

			// --- set display for plugin template ---
			$displayfile = '[Plugin] ' . $relfilepath;
			$parts = explode( '/', $relfilepath );
			$plugin = $parts[0];
			
			// 2.2.0: attempt to match to active plugin path
			if ( $active_plugins && is_array( $active_plugins ) && ( count( $active_plugins ) > 0 ) ) {
				foreach ( $active_plugins as $active_plugin ) {
					if ( substr( $active_plugin, 0, strlen( $plugin ) ) == $plugin ) {
						$plugin = $active_plugin;
					}
				}
			}

		} elseif ( 'other' == $type ) {

			// --- just strip WordPress load path ---
			$displayfile = '[?] ' . str_replace( $abspath, '', $filepath );
			$editlink = 'javascript:void(0);';

		}

		// --- set node arguments  ---
		$items[$i] = array(
			'id'		=> 'template-' . $i,
			'title'		=> $displayfile,
			'parent'	=> 'page-templates',
			'href'		=> $editlink,
			'meta'		=> array(
				'title' => $filepath,
				'class' => 'page-template'
			),
		);

		// 2.2.0: added plugin dir for grouping
		if ( 'plugin' == $type ) {
			$items[$i]['plugin'] = $plugin;
		}

		// --- increment submenu counter ---
		$i++;
	}

	// --- reloop items to group plugin submenus ---
	// 2.2.0: group as some plugins load many templates
	$previtem = false;
	$paths = array();
	$count = 1;
	foreach ( $items as $i => $item ) {

		// --- maybe set as submenu item ---
		if ( isset( $previtem['plugin'] ) && isset( $item['plugin'] ) && ( $previtem['plugin'] == $item['plugin'] ) ) {
			$count++;
			if ( 0 == count( $paths ) ) {
				$paths[] = $previtem['meta']['title'];
			}
			$paths[] = str_replace( '[Plugin] ', '', $item['title'] );
			$pathlist = implode( ', ', $paths );
			$items[$i]['title'] = '[Plugin] (' . $count . ') : ' . $item['plugin'];
			$items[$i]['meta']['title'] = $pathlist;
			unset( $items[$previ] );
		} else {
			$count = 1;
			$paths = array();
		}

		// --- set previous item to check against ---
		$previtem = $item;
		$previ = $i;
	}

	// --- add admin bar nodes ---
	foreach ( $items as $item ) {
		$wp_admin_bar->add_node( $item );
	}

	// --- add page menu template dashicon ---
	$icon = "\\f232";
	$icon = apply_filters( 'admin_adminbar_templates_icon', $icon );
	echo '<style>#wp-admin-bar-page-templates .ab-icon:before {content: "' . esc_attr( $icon ) . '"; top: 3px;}</style>';

	bioship_debug( "Admin Bar with Templates Menu", $wp_admin_bar );
 }
}
