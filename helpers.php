<?php

// ================================
// === BioShip Helper Functions ===
// ================================

// === Actions and Filters ===
// - Apply Filters (with Value Tracer)
// - Do Action (with Value Tracer)
// - Add to Action Hook with Priority
// - Register Removed Actions
// - Delayed Remove Actions
// === Output Sanitization ===
// - Filter KSES Allowed HTML
// - Filter Allowed Button Attributes
// - Filter Allowed Page Title Attributes
// - Filter Allowed CSS Attributes
// === Misc Helpers ===
// - Dummy Get Function Args (PHP 5.2)
// - Round Half Down (PHP 5.2)
// - Negative Return Helper
// - Dummy Function Helper
// - Start Load Timer
// - Get Current Load Time
// - Get Remote IP Address
// - Word to Number Helper
// - Number to Word Helper
// - Serialized Data Fixer
// - Fix Serialized Callback
// - Get Current User Wrapper
// - Get Single Option Value
// - WordPress.Org Version Checker
// - Get Post Type(s) Helper
// - Output HTML Comment
// === Files / Debug ===
// - Theme Debug Output
// - Get File Contents
// - Direct File Writer
// - Debug File Writer
// - Check/Create Debug Directory


// Development TODOs
// -----------------
// IDEA: add a bioship_move_action function


// ---------------------------
// === Actions and Filters ===
// ---------------------------

// ---------------------------------
// Apply Filters (with Value Tracer)
// ---------------------------------
// 2.1.1: added extra value argument
// 2.2.0: fix to allow for extra arguments (up to 6)
// note: extra args set to an '%NULL%' as a null value may be valid!
// (this could lead to a filter being called with an incorrect number of arguments)
if ( !function_exists( 'bioship_apply_filters' ) ) {
 function bioship_apply_filters( $filter, $value, $arg1 = '%NULL%', $arg2 = '%NULL%', $arg3 = '%NULL%', $arg4 = '%NULL%', $arg5 = '%NULL%', $arg6 = '%NULL%' ) {

	// TODO: fix caller argument for tracer
	global $vthemefunction;
	$caller = $vthemefunction;

	// --- standard filter ---
 	// 2.0.5: also trace applied filter levels
 	// 2.1.1: check for extra filtering value
	// 2.2.0: track extra argument values
 	$values = array(
		'in' => $value,
	);
	if ( '%NULL%' != $arg1 ) {
		$values['arg1'] = $arg1;
	}
	if ( '%NULL%' != $arg2 ) {
		$values['arg2'] = $arg2;
	}
	if ( '%NULL%' != $arg3 ) {
		$values['arg3'] = $arg3;
	}
	if ( '%NULL%' != $arg4 ) {
		$values['arg4'] = $arg4;
	}
	if ( '%NULL%' != $arg5 ) {
		$values['arg5'] = $arg5;
	}
	if ( '%NULL%' != $arg6 ) {
		$values['arg6'] = $arg6;
	}

	// --- apply filters ---
	// 2.2.0: apply_filters with argument values
	if ( '%NULL%' != $arg6 ) {
		$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6 );
	} elseif ( '%NULL%' != $arg5 ) {
		$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5 );
	} elseif ( '%NULL%' != $arg4 ) {
		$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4 );
	} elseif ( '%NULL%' != $arg3 ) {
		$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3 );
	} elseif ( '%NULL%' != $arg2 ) {
		$filtered = apply_filters( $filter, $value, $arg1, $arg2 );
	} elseif ( '%NULL%' != $arg1 ) {
		$filtered = apply_filters( $filter, $value, $arg1 );
	} else {
	 	$filtered = apply_filters( $filter, $value );
	}
	if ( $value != $filtered ) {
		$value = $values['filter'] = $filtered;
	}

	// --- theme prefixed filter ----
	// 2.0.5: process theme prefixed filter as well
	// 2.1.1: check for extra filtering value
	if ( THEMEPREFIX . '_' != substr( $filter, 0, strlen( THEMEPREFIX . '_' ) ) ) {
		if ( '%NULL%' != $arg6 ) {
			$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6 );
		} elseif ( '%NULL%' != $arg5 ) {
			$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5 );
		} elseif ( '%NULL%' != $arg4 ) {
			$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4 );
		} elseif ( '%NULL%' != $arg3 ) {
			$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3 );
		} elseif ( '%NULL%' != $arg2 ) {
			$filtered = apply_filters( $filter, $value, $arg1, $arg2 );
		} elseif ( '%NULL%' != $arg1 ) {
			$filtered = apply_filters( $filter, $value, $arg1 );
		} else {
			$filtered = apply_filters( $filter, $value );
		}
		if ( $filtered != $value ) {
			$value = $values['theme'] = $filtered;
		}
	}

	// --- child theme prefixed filter ---
	// 2.0.5: maybe process child theme specific filter (for multiple theme compatibilty)
	// 2.1.1: check for extra filtering value
	if ( defined( 'THEMECHILD' ) && THEMECHILD && ( THEMESLUG != THEMEPREFIX ) ) {
		if ( THEMESLUG . '_' != substr( $filter, 0, strlen( THEMESLUG . '_' ) ) ) {
			if ( '%NULL%' != $arg6 ) {
				$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6 );
			} elseif ( '%NULL%' != $arg5 ) {
				$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4, $arg5 );
			} elseif ( '%NULL%' != $arg4 ) {
				$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3, $arg4 );
			} elseif ( '%NULL%' != $arg3 ) {
				$filtered = apply_filters( $filter, $value, $arg1, $arg2, $arg3 );
			} elseif ( '%NULL%' != $arg2 ) {
				$filtered = apply_filters( $filter, $value, $arg1, $arg2 );
			} elseif ( '%NULL%' != $arg1 ) {
				$filtered = apply_filters( $filter, $value, $arg1 );
			} else {
				$filtered = apply_filters( $filter, $value );
			}
			if ( $filtered != $value ) {
				$value = $values['child'] = $filtered;
			}
		}
	}

	// --- trace value only if changed ---
	if ( defined( 'THEMETRACE' ) && THEMETRACE ) {
		$values['out'] = $value;
		bioship_trace( 'V', $filter, $caller, $values );
	}

	return $value;
 }
}

// -----------------------------
// Do Action (with Value Tracer)
// -----------------------------
// 2.0.5: added prefixed do_action wrapper for action load debugging/tracing
// 2.2.0: added optional extra arguments passthrough (6 extra parameters)
if ( !function_exists( 'bioship_do_action' ) ) {
 function bioship_do_action( $action, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null, $arg5 = null, $arg6 = null ) {

 	// --- action tracer ---
 	// 2.1.1: removed useless third argument value
 	// (as will always be functions.php, current template filepath used instead)
 	if ( THEMETRACE ) {bioship_trace( 'A', $action, '' );}

	// --- action hook debugging ---
	// 2.2.0: fix to list count
 	if ( THEMEDEBUG ) {
 		$list = '';
		$listcount = 0;
	 	if ( has_action( $action ) ) {
	 		global $wp_filter;
	 		$callbacks = $wp_filter[$action]->callbacks;
	 		if ( count( $callbacks ) > 0 ) {
				foreach ( $callbacks as $priority => $callback ) {
					foreach ( $callback as $key => $function ) {
						$list .= $function['function'] . ' (' . $priority . ')' . PHP_EOL;
						$listcount++;
					}
				}
			}
	 	}

		// --- debug the hooked action list ---
	 	// 2.0.9: use bioship_debug function
	 	if ( '' == $list ) {
	 		bioship_debug( "Doing Empty Action '" . $action . "'" );
	 	} else {
	 		bioship_debug( "Doing Action '" . $action . "' with " . $listcount . " Hooked Functions", $list );
	 	}
	}

	// --- just do it already ---
	// 2.2.0: added optional extra arguments passthrough
	if ( !is_null( $arg6 ) ) {
		do_action( $action, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6 );
	} elseif ( !is_null( $arg5 ) ) {
		do_action( $action, $arg1, $arg2, $arg3, $arg4, $arg5 );
	} elseif ( !is_null( $arg4 ) ) {
		do_action( $action, $arg1, $arg2, $arg3, $arg4 );
	} elseif ( !is_null( $arg3 ) ) {
		do_action( $action, $arg1, $arg2, $arg3 );
	} elseif ( !is_null( $arg2 ) ) {
		do_action( $action, $arg1, $arg2 );
	} elseif ( !is_null( $arg1 ) ) {
		do_action( $action, $arg1 );
	} else {
	 	do_action( $action );
	}
 }
}

// --------------------------------
// Add to Action Hook with Priority
// --------------------------------
// 1.9.8: added abstract to use theme hooks array
if ( !function_exists( 'bioship_add_action' ) ) {
 function bioship_add_action( $hook, $function, $defaultposition ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemehooks;

	// --- add theme prefix ---
	// 2.0.5: maybe auto-prefix hooks and functions
	$prefix = THEMEPREFIX . '_';
	if ( substr( $hook, 0, strlen( $prefix ) ) != $prefix ) {
		$hook = $prefix . $hook;
	}
	if ( substr( $function, 0, strlen( $prefix ) ) != $prefix ) {
		$function = $prefix . $function;
	}

	// --- check theme hooks array ---
	if ( isset( $vthemehooks['functions'][$hook][$function] ) ) {
		$position = $vthemehooks['functions'][$hook][$function];
	} else {
		$position = $defaultposition;
		bioship_debug( "Warning: Missing Template Position for Hook " . $hook, $function );
	}

	// --- apply old filter names ---
	// 2.0.5: for old position filters eg. skeleton_wrapper_open_position
	$oldfunction = substr( $function, strlen( THEMEPREFIX . '_' ), strlen( $function ) );
	$position = apply_filters( $oldfunction . '_position', $position );

	// --- apply position filters ---
	if ( function_exists( 'bioship_apply_filters' ) ) {
		// eg. bioship_wrapper_open_position
		$position = bioship_apply_filters( $function . '_position', $position );
		// eg. bioship_container_open_bioship_wrapper_open_position
		$position = bioship_apply_filters( $hook . '_' . $function . '_position', $position );
	} else {
		$position = apply_filters( $function . '_position', $position );
		$position = apply_filters( $hook . '_' . $function . '_position', $position );
	}

	// --- add action to hook with priority ---
	if ( $position > -1 ) {
		bioship_debug( "Added to Hook " . $hook . " with Priority " . $position, $function, true );
		add_action( $hook, $function, $position );
	} else {
		bioship_debug( "Invalid Position for Hook " . $hook . " at " . $position, $function, true );
	}

 }
}

// --------------------------
// Add Filter (auto-prefixed)
// --------------------------
// 2.2.0: added for completeness and future use
if ( !function_exists( 'bioship_add_filter' ) ) {
 function bioship_add_filter( $filter, $function, $priority = 10, $args = null ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- add prefixed filter ---
	if ( !is_null( $args ) ) {
		add_filter( 'bioship_' . $filter, $function, $priority, $args );
	} else {
		add_filter( 'bioship_' . $filter, $function, $priority, $args );
	}
 }
}

// ------------------------
// Register Removed Actions
// ------------------------
// helper to remove template action from hook without needing to know priority position
// 2.0.5: added this remove_action helper wrapper
if ( !function_exists( 'bioship_remove_action' ) ) {
 function bioship_remove_action( $hook, $function, $position = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemehooks;

	// --- auto-add theme prefix ---
	$prefix = THEMEPREFIX . '_';
	if ( substr( $hook, 0, strlen( $prefix ) ) != $prefix ) {
		$hook = $prefix . $hook;
	}
	if ( substr( $function, 0, strlen( $prefix ) ) != $prefix ) {
		$function = $prefix . $function;
	}

	// --- find action hook position ---
 	if ( !$position ) {
		if ( !isset( $vthemehooks['functions'][$hook][$function] ) ) {
			$position = $vthemehooks['functions'][$hook][$function];
		}

		// --- apply position filters ---
		// note: position filters intentionally reversed as removing not adding!
		if ( function_exists( 'bioship_apply_filters' ) ) {
			$position = bioship_apply_filters( $hook . '_' . $function . '_position', $position );
			$position = bioship_apply_filters( $function . '_position', $position );
		} else {
			$position = apply_filters( $hook . '_' . $function . '_position', $position );
			$position = apply_filters( $function . '_position', $position );
		}
 	}

 	// --- add to list of actions to remove later ---
 	// 2.1.1: added recheck of position false for edge cases
 	if ( $position && ( $position > -1 ) ) {
 		// note: hook is removed later in bioship_remove_actions
 		// remove_action( $hook, $function, $position );
		$vthemehooks['remove'][$hook][$function] = $position;
 	}
 }
}

// ----------------------
// Delayed Remove Actions
// ----------------------
if ( !function_exists( 'bioship_remove_actions' ) ) {

 // --- delay until init so actions added then removed ---
 // 2.0.9: increase priority for better child theme support
 add_action( 'init', 'bioship_remove_actions', 11 );

 function bioship_remove_actions() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $vthemehooks;
	if ( !isset( $vthemehooks['remove'] ) ) {
		return;
	}
	$remove = $vthemehooks['remove'];
	if ( count( $remove ) > 0 ) {
		foreach ( $remove as $hook ) {
			foreach ( $hook as $function => $position ) {
				remove_action( $hook, $function, $position );
				bioship_debug( "Action Removed from Hook " . $hook . " Position " . $position, $function );
			}
		}
	}
 }
}


// ---------------------------
// === Output Sanitization ===
// ---------------------------

// ------------------------
// Filter KSES Allowed HTML
// ------------------------
if ( !function_exists( 'bioship_allowed_html' ) ) {
	function bioship_allowed_html( $type, $context = false ) {
		$allowed = wp_kses_allowed_html( 'post' );
		$allowed = apply_filters( 'bioship_skeleton_allowed_html', $allowed, $type, $context );
		return $allowed;
	}
}

// --------------------------------
// Filter Allowed Button Attributes
// --------------------------------
// note: temporary buttons filter to allow button onclick attributes
if ( !function_exists( 'bioship_allowed_html_buttons' ) ) {
 add_filter( 'bioship_skeleton_allowed_html', 'bioship_allowed_html_buttons', 10, 3 );
 function bioship_allowed_html_buttons( $allowed, $type, $context ) {
	if ( 'buttons' == $type ) {
		$allowed['a']['onclick'] = array();
	}
	return $allowed;
 }
}

// ------------------------------------
// Filter Allowed Page Title Attributes
// ------------------------------------
// 2.2.0: added for wp_kses output of title tag
if ( !function_exists( 'bioship_allowed_html_page_title' ) ) {
 add_filter( 'bioship_skeleton_allowed_html', 'bioship_allowed_html_page_title', 10, 3 );
 function bioship_allowed_html_page_title( $allowed, $type, $context ) {
	if ( ( 'title' == $type ) && ( 'page' == $context ) ) {
		$allowed['title'] = array( 'itemprop' => array() );
	}
	return $allowed;
 }
}

// -----------------------------
// Filter Allowed CSS Attributes
// -----------------------------
// 2.2.0: added for allowing inline style="display:none;" rules
if ( !function_exists( 'bioship_safe_css_attributes' ) ) {

 add_filter( 'safe_style_css', 'bioship_safe_css_attributes' );

 function bioship_safe_css_attributes( $atts ) {
   $atts[] = 'display';
   $atts[] = 'visibility';
   return $atts;
 }
}


// --------------------
// === Misc Helpers ===
// --------------------

// -----------------------
// Dummy Get Function Args
// -----------------------
// 2.1.2: added backwards compatible function for WP < 5.3
if ( !function_exists( 'func_get_args' ) ) {
	function func_get_args() {
		return '';
	}
}

// ---------------
// Round Half Down
// ---------------
// 2.1.2: added this helper function
// (since PHP 5.2 does not have PHP_ROUND_HALF_DOWN mode)
// ref: https://stackoverflow.com/questions/7103233/round-mode-round-half-down-with-php-5-2-17
if ( !function_exists( 'bioship_round_half_down' ) ) {
 function bioship_round_half_down( $v, $precision = 3 ) {
	// 2.2.0: check PHP version for support
 	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		return round( $v, $precision, PHP_ROUND_HALF_DOWN );
	}
	$v = explode( '.', $v );
	$v = implode( '.', $v );
	$v = $v * pow( 10, $precision ) - 0.5;
	$a = ceil( $v ) * pow( 10, -$precision );
	return number_format( $a, 2, '.', '' );
 }
}

// ----------------------
// Negative return Helper
// ----------------------
// 2.0.7: added this little helper function
if ( !function_exists( 'bioship_return_negative' ) ) {
 function bioship_return_negative() {
	 return -1;
 }
}

// ---------------------
// Dummy Function Helper
// ---------------------
// 2.0.9: added this little helper function
if ( !function_exists( 'bioship_dummy_function' ) ) {
 function bioship_dummy_function() {}
}

// ----------------
// Start Load Timer
// ----------------
if ( !function_exists( 'bioship_timer_start' ) ) {
 function bioship_timer_start() {
 	global $vthemetimestart;
 	$vthemetimestart = microtime( true );
 	return $vthemetimestart;
 }
 $vthemetimestart = bioship_timer_start();
}

// ---------------------
// Get Current Load Time
// ---------------------
if ( !function_exists( 'bioship_timer_time' ) ) {
 function bioship_timer_time() {
 	global $vthemetimestart;
 	$themetimer = microtime( true );
 	$difference = $themetimer - $vthemetimestart;
 	return $difference;
 }
}

// ---------------------
// Get Remote IP Address
// ---------------------
if ( !function_exists( 'bioship_get_remote_ip' ) ) {
 function bioship_get_remote_ip() {
 	if ( THEMETRACE ) {bioship_debug( 'F', __FUNCTION__, __FILE__ );}

 	// TODO: replace with more accurate IP detection ?
 	// note: this is only used by the theme in debug log lines
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
 }
}

// ---------------------
// Word to Number Helper
// ---------------------
if ( !function_exists( 'bioship_word_to_number' ) ) {
 function bioship_word_to_number( $word ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	$wordnumbers = array(
		'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3', 'four' => '4', 'five' => '5', 'six' => '6',
		'seven' => '7', 'eight' => '8', 'nine' => '9', 'ten' => '10', 'eleven' => '11', 'twelve' => '12',
		'thirteen' => '13',	'fourteen' => '14', 'fifteen' => '15', 'sixteen' => '16',
		'seventeen' => '17', 'eighteen' => '18', 'nineteen' => '19', 'twenty' => '20',
		'twentyone' => '21', 'twentytwo' => '22', 'twentythree' => '23', 'twentyfour' => '24',
	);

	// 1.8.5: added check and return false for validation
	if ( array_key_exists( $word, $wordnumbers ) ) {
		return $wordnumbers[$word];
	}
	return false;
 }
}

// ---------------------
// Number to Word Helper
// ---------------------
if ( !function_exists( 'bioship_number_to_word' ) ) {
 function bioship_number_to_word( $number ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	$numberwords = array(
		'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight',
		'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
		'seventeen', 'eighteen', 'nineteen', 'twenty', 'twentyone', 'twentytwo', 'twentythree', 'twentyfour',
	);

	// 1.8.5: added check and return false for validation
	if ( array_key_exists( $number, $numberwords ) ) {
		return $numberwords[$number];
	}
	return false;
 }
}

// ---------------------
// Serialized Data Fixer
// ---------------------
if ( !function_exists( 'bioship_fix_serialized' ) ) {
 function bioship_fix_serialized( $string ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

    // --- security ---
    if ( !preg_match( '/^[aOs]:/', $string ) ) {
    	return $string;
    }
    if ( false !== @unserialize( $string ) ) {
    	return $string;
    }
    $string = preg_replace( "%\n%", "", $string );

    // --- doublequote exploding ---
    $data = preg_replace( '%";%', "ARANDOMLYLONGBUTIDENTIFYABLESTRING", $string );
    $tab = explode( "ARANDOMLYLONGBUTIDENTIFYABLESTRING", $data );
    $newdata = '';
    foreach ( $tab as $line ) {
        $newdata .= preg_replace_callback( '%\bs:(\d+):"(.*)%', 'bioship_fix_str_length', $line );
    }
    return $newdata;
 }
}

// -----------------------
// Fix Serialized Callback
// -----------------------
if ( !function_exists( 'bioship_fix_str_length' ) ) {
 function bioship_fix_str_length( $matches ) {
    $string = $matches[2];
    // yes, strlen even for UTF-8 characters
    // PHP wants the mem size, not the char count
    $rightlength = strlen( $string );
    $corrected = 's:' . $rightlength . ':"' . $string . '";';
    return $corrected;
 }
}

// ------------------------
// Get Current User Wrapper
// ------------------------
// 2.0.7: extracted all calls to standalone function
if ( !function_exists( 'bioship_get_current_user' ) ) {
 function bioship_get_current_user() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	if ( function_exists( 'wp_get_current_user' ) ) {
		return wp_get_current_user();
	} else {
		global $current_user;
		// 2.0.9: streamlined copy of _wp_get_current_user (backwards compatible)
		// (as using get_currentuserinfo() does not pass theme check)
		if ( !empty( $current_user ) ) {
			if ( $current_user instanceof WP_User ) {
				return $current_user;
			}

			// 2.1.1: removed setting of $current_user to null, fixed isset check
			$current_user_id = 0;
			if ( is_object( $current_user ) && property_exists( $current_user, 'ID' ) ) {
				$current_user_id = $current_user->ID;
			}
			wp_set_current_user( $current_user_id );
		}
		return $current_user;
	}
 }
}

// -----------------------
// Get Single Option Value
// -----------------------
// note: for internal theme use, does not honour pre_option_ or default_option filters!
// 1.9.5: added to get an option direct from database (to bypass any cached values)
// 2.0.9: add default (not yet used) and filter arguments
if ( !function_exists( 'bioship_get_option' ) ) {
 function bioship_get_option( $optionkey, $default = false, $filter = true ) {
 	if ( defined( 'THEMETRACE' ) && THEMETRACE ) {
 		bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );
 	}

 	// --- get value direct from database ---
 	global $wpdb;
 	// $query = "SELECT option_value FROM " . $wpdb->prefix . "options WHERE option_name = '" . $optionkey . "'";
 	$query = "SELECT option_value FROM " . $wpdb->prefix . "options WHERE option_name = %s";

 	// --- maybe unserialize option value
 	// 2.0.9: always do a maybe_unserialize on option value
 	// 2.2.0: use wpdb prepare method on query
	$optionvalue = maybe_unserialize( $wpdb->get_var( $wpdb->prepare( $query, $optionkey ) ) );

	// --- apply filter to value ---
	// 2.0.9: maybe apply the related option filter
	if ( $filter ) {
		$optionvalue = apply_filters( 'option_' . $optionkey, $optionvalue, $optionkey );
	}
 	return $optionvalue;
 }
}

// -----------------------------
// WordPress.Org Version Checker
// -----------------------------
// note: checks presence of /includes/theme-update-checker.php
// this indicates a version downloaded from Bioship.Space - not from WordPress.org
// 2.0.8: moved this check from admin.php so as to define earlier
// 2.0.9: allow for existing user override for this constant
// 2.1.1: allow for alternative include directory
if ( !function_exists( 'bioship_release_type_checker' ) ) {
 function bioship_release_type_checker() {
	global $vthemedirs;
	if ( !defined( 'THEMEWPORG' ) ) {
		$themeupdater = bioship_file_hierarchy( 'file', 'theme-update-checker.php', $vthemedirs['includes'] );
		// 2.1.0: fix to fallback definition value true for WordPress.Org version
		// 2.2.0: simplified to definition of opposite boolean value
		define( 'THEMEWPORG', !$themeupdater );
		bioship_debug( "Theme WP.Org Version?", (bool) THEMEWPORG );

		// 2.1.1: set wporg key value on wordquestplugins array
		$wordquestplugins[THEMEPREFIX]['wporg'] = THEMEWPORG;
	}
 }
}

// -----------------------
// Get Post Type(s) Helper
// -----------------------
// 1.8.5: added this helper
// 2.0.5: moved here from skull.php
if ( !function_exists( 'bioship_get_post_types' ) ) {
 function bioship_get_post_types( $queryobject = null ) {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- if a numeric value passed, assume it is a post ID ---
	if ( $queryobject && is_numeric( $queryobject ) ) {
		$queryobject = get_post( $queryobject );
	}

	// --- if an object is passed, assume a post object ---
	if ( $queryobject && is_object( $queryobject ) ) {
		bioship_debug( "Queried Object", $queryobject );
		return get_post_type( $queryobject );
	}

	// --- standard single post type checks ---
	if ( is_404() ) {
		// no post type for a 404
		return '';
	}
	// 1.9.5: removed is_single check - incorrect usage!
 	// if ( is_single() ) {return 'post';}
 	if ( is_page() ) {
 		return 'page';
 	}
	if ( is_attachment() ) {
		return 'attachment';
	}
	// 1.9.5: added is_archive check for rare cases
	if ( is_singular() && !is_archive() ) {
		return get_post_type();
	}

	// --- if a custom query object was not passed, use $wp_query global ---
	if ( !$queryobject || !is_object( $queryobject ) ) {
		global $wp_query;
		$queryobject = $wp_query;
	}
	if ( !is_object( $queryobject ) ) {
		return '';
	}

	// --- if the post_type query var has been explicitly set ---
	// (or implicitly set on the cpt via a has_archive redirect)
	// ie. this is true for is_post_type_archive at least
	// $queriedposttype = get_query_var('post_type'); // works for $wp_query only
	if ( property_exists( $queryobject, 'query_vars' ) ) {
	    $queriedposttype = $queryobject->query_vars['post_type'];
		if ( $queriedposttype ) {
			return $queriedposttype;
		}
	}

	// --- handle all other cases by looping posts in query object ---
	$posttypes = array();
	if ( $queryobject->found_posts > 0 ) {
		$queriedposts = $queryobject->posts;
		foreach ( $queriedposts as $queriedpost ) {
		    $posttype = $queriedpost->post_type;
		    if ( !in_array( $posttype, $posttypes ) ) {
				$posttypes[] = $posttype;
		    }
		}
		// 2.2.0: add check for 0 and fix incorrect bracket nesting in 1
		if ( 0 == count( $posttypes ) ) {
			return '';
		} elseif ( 1 == count( $posttypes ) ) {
			return $posttypes[0];
		} else {
			return $posttypes;
		}
	}

	return '';
 }
}

// -------------------
// Output HTML Comment
// -------------------
// 2.0.8: new function to reduce comment code clutter in templates
// 2.1.1: added second argument for output or return
if ( !function_exists( 'bioship_html_comment' ) ) {
 function bioship_html_comment( $comment, $output = true ) {
 	if ( !defined( 'THEMECOMMENTS' ) || !THEMECOMMENTS ) {
 		return '';
 	}

	// --- set HTML comment output ---
    // 2.2.0: fix to duplicate output variable usage
 	// 2.1.1: added handling echo or return
	// 2.2.0: echo directly if not returning
	if ( $output ) {
		echo "<!-- " . esc_html( $comment ) . " -->" . PHP_EOL;
	} else {
		$html = "<!-- " . esc_html( $comment ) . " -->" . PHP_EOL;
		return $html;	
	}
 }
}

// ---------------
// Get Image Sizes
// ---------------
// 2.2.0: separate function to get image sizes moved from skull.php
if ( !function_exists( 'bioship_get_image_sizes' ) ) {
 function bioship_get_image_sizes() {

	global $vthemesettings;

	// --- set crop options ---
	// 2.2.0: check if option is set for back-compat
	$crop = get_option( 'thumbnail_crop' );
	$thumbnailcrop = false;
	if ( isset( $vthemesettings['thumbnailcrop'] ) ) {
		$thumbnailcrop = $vthemesettings['thumbnailcrop'];
	}
	if ( $thumbnailcrop ) {
		if ( 'nocrop' == $thumbnailcrop ) {
			$crop = false;
		} elseif ( 'auto' == $thumbnailcrop ) {
			$crop = true;
		} elseif ( strstr( $thumbnailcrop, '-' ) ) {
			$crop = explode( '-', $thumbnailcrop );
		}
	}

	// Ref: WordPress Thumbnail Size Defaults
	// 'thumbnail' 		: Thumbnail (150 x 150 hard cropped)
	// 'medium'    		: Medium resolution (300 x 300 max height 300px)
	// 'medium_large' 	: Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
	// 'large' 			: Large resolution (1024 x 1024 max height 1024px)
	// 'full' 			: Full resolution (original size uploaded)
	// with WooCommerce
	// 'shop_thumbnail' : Shop thumbnail (180 x 180 hard cropped)
	// 'shop_catalog'	: Shop catalog (300 x 300 hard cropped)
	// 'shop_single'    : Shop single (600 x 600 hard cropped)

	// --- create image size array ---
	// 1.5.0: added open graph size 560x292
	// 2.0.5: replaced with prefixed image sizes
	// (note: thumbnail regeneration in bioship_regenerate_thumbnails)
	$image_sizes = array(
		// --- 150px square ---
		array( 'title' => __( '150 Square', 'bioship' ), 'name' => 'bioship-150', 'width' => 150, 'height' => 150, 'crop' => $crop ),
		// --- 250px square --0
		array( 'title' => __( '250 Square', 'bioship' ), 'name' => 'bioship-250', 'width' => 250, 'height' => 250, 'crop' => $crop ),
		// --- 4:3 Video (320 x 240) ---
		array( 'title' => __( '4:3 Ratio', 'bioship' ), 'name' => 'bioship-4-3', 'width' => 320, 'height' => 240, 'crop' => $crop ),
		// --- 16:9 Video (320 x 180) ---
		array( 'title' => __( '16:9 Ratio', 'bioship' ), 'name' => 'bioship-16-9', 'width' => 320, 'height' => 180, 'crop' => $crop ),
		// --- OpenGraph (560 x 292) ---
		array( 'title' => __( 'OpenGraph', 'bioship' ), 'name' => 'bioship-opengraph', 'width' => 560, 'height' => 292, 'crop' => $crop ),
		// --- Admin Thumbnail ---
		array( 'title' => __( 'Admin Thumb', 'bioship' ), 'name' => 'admin-thumb', 'width' => 64, 'height' => 64, 'crop' => $crop ),
	);

	// --- filter image sizes ---
	$image_sizes = bioship_apply_filters( 'skeleton_image_sizes', $image_sizes );

	return $image_sizes;
 }
}


// ---------------------------
// === File System / Debug ===
// ---------------------------

// ------------------
// Theme Debug Output
// ------------------
// 2.0.9: added separate debug output/logging function
if ( !function_exists( 'bioship_debug' ) ) {
 function bioship_debug( $prefix, $data = '%NULL%', $forceoutput = null, $forcelog = null ) {

	// --- maybe bug out if not in debug mode ---
 	if ( !defined( 'THEMEDEBUG' ) || !THEMEDEBUG ) {
		return;
	}

 	// --- maybe display debug output ---
 	$output = true;
 	if ( defined( 'THEMEDEBUGOUTPUT' ) && !THEMEDEBUGOUTPUT ) {
 		$output = false;
 	}
 	if ( !is_null( $forceoutput ) ) {
 		$output = $forceoutput;
 	}

	// --- maybe output debug data ---
	if ( $output ) {
 		echo "<!-- [Theme Debug] " . esc_html( $prefix );
		// 2.2.0: check for special null string instead of null
		if ( '%NULL%' != $data ) {
			echo ": ";
			if ( is_null( $data ) ) {
				// 2.2.0: special output if actually null value
				echo '*NULL*';
			} elseif ( is_bool( $data ) ) {
				// 2.2.0: fix for boolean and integer value detection debug output
				if ( $data ) {
					echo 'true';
				} else {
					echo 'false';
				}
			} elseif ( is_array( $data ) || is_object( $data ) ) {
				echo PHP_EOL . wp_kses_post( print_r( $data, true ) );
			} elseif ( is_string( $data ) || is_int( $data ) ) {
				echo esc_html( $data );
			}
		}
 		echo " -->" . PHP_EOL;
 	}

	// --- check for debug instance ---
	// 2.0.9: check querystring for setting of debug instance
	// 2.2.0: force instance to numeric for security
	if ( !defined( 'THEMEDEBUGINSTANCE' ) && isset( $_REQUEST['instance'] ) ) {
		$instance = abs( inval( $_REQUEST['instance'] ) );
		if ( $instance > -1 ) {
	 		define( 'THEMEDEBUGINSTANCE', $instance );
	 	} else {
	 		define( 'THEMEDEBUGINSTANCE', '' );
	 	}
	}

 	// --- maybe log debug output ---
 	// 2.1.1: added missing single quotes in define check
 	$log = false;
 	if ( defined( 'THEMEDEBUGLOG' ) && THEMEDEBUGLOG ) {
 		$log = true;
 	}
 	if ( !is_null( $forcelog ) ) {
 		$log = $forcelog;
 	}

	// 2.1.1: bug out here if not logging
	if ( !$log ) {
		return;
	}

	$logline = '';

	// --- theme debug log info constant ---
	// (define for once only logging)
	if ( !defined( 'THEMEDEBUGLOGINFO' ) ) {
		$logline = PHP_EOL . "Theme Debug Output";
		if ( defined( 'THEMEDEBUGINSTANCE' ) && ( '' != THEMEDEBUGINSTANCE ) ) {
			$logline .= " Instance '" . THEMEDEBUGINSTANCE . "'";
		}
		$logline .= PHP_EOL . '[' . date( 'j m Y H:i:s', time() ) . '] ';

		// --- user IP address ---
		// 2.0.9: add IP address to theme debug info log line
		if ( !defined( 'THEMEDEBUGIP' ) ) {
			$ip = bioship_get_remote_ip();
			if ( '' != $ip ) {
				// 2.2.0: fix to constant typo THEMDEBUGIP (and missing quotes!)
				define( 'THEMEDEBUGIP', $ip );
			}
		}
		if ( defined( 'THEMEDBUGIP' ) ) {
			$logline .= '[IP ' . THEMEDEBUGIP . '] ';
		}
		define( 'THEMEDEBUGLOGINFO', true );
	}

	// --- set log line data ---
	$logline .= $prefix;
	if ( !is_null( $data ) ) {
		// 2.2.0: fix for boolean and integer value detection for debug logging
		if ( is_bool( $data ) ) {
			if ( $data ) {
				$logline .= ": true";
			} else {
				$logline .= ": false";
			}
		} elseif ( is_string( $data ) || is_int( $data ) ) {
			$logline .= ": " . $data;
		} elseif ( is_array( $data ) || is_object( $data ) ) {
			// 2.0.9: removed unneeded output buffering
			$logline .= ": " . PHP_EOL . print_r( $data, true );
		}
	}

	if ( '' != $logline ) {

		$logline .= PHP_EOL;

		// --- set debug log filename ---
		// 2.1.1: check/set THEMEDEBUGFILE constant
		if ( defined( 'THEMEDEBUGFILE' ) ) {
			$filename = THEMEDEBUGFILE;
		} else {
			$filename = 'theme_debug.log';
			// 2.0.9: allow setting of alternative filename for single debug instance
			if ( defined( 'THEMEDEBUGINSTANCE' ) && ( '' != THEMEDEBUGINSTANCE ) ) {
				$instance = THEMEDEBUGINSTANCE;
				$filename = $instance . '_' . $filename;
			} else {
				$instance = false;
			}

			// --- filter debug log filename ---
			// 2.1.1: added instance as extra filter value
			$filename = bioship_apply_filters( 'debug_filename', $filename, $instance );
			define( 'THEMEDEBUGFILE', $filename );
		}

		// --- write log line to debug file ---
		bioship_write_debug_file( $filename, $logline );
	}

 }
}

// -----------------
// Get File Contents
// -----------------
// 2.0.7: added file_get_contents alternative wrapper (for Theme Check)
if ( !function_exists( 'bioship_file_get_contents' ) ) {
 function bioship_file_get_contents( $filepath ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}
 	if ( !file_exists( $filepath ) ) {
 		return '';
 	}

	// --- attempt to use WP filesystem ---
	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		// --- maybe require filesystem ---
		if ( !function_exists( 'WP_Filesytem' ) ) {
			$filesystem = ABSPATH . DIRSEP . 'wp-admin' . DIRSEP . 'includes' . DIRSEP . 'file.php';
			require_once $filesystem;
		}

		// --- initialize WP Filesystem ---
		WP_Filesystem();
	}

	// --- get file contents ---
	$contents = $wp_filesystem->get_contents( $filepath );
	if ( $contents ) {
		return $contents;
	} else {
		// --- fallback to using file() to read the file ---
		// (recommended WP fallback instead of file_get_contents)
		// 2.1.1: do not re-add line break when using file() function
		$filearray = @file( $filepath );
		if ( !$filearray ) {
			return '';
		}
		$contents = implode( "", $filearray );
		return $contents;
	}
 }
}

// ------------------
// Direct File Writer
// ------------------
// 1.8.0: added this for direct file writing
// 2.0.9: added append method since WP Filesystem does not have one
if ( !function_exists( 'bioship_write_to_file' ) ) {
 function bioship_write_to_file( $filepath, $data, $append = false ) {

 	// 2.1.1: fix for early use of this function (where tracer not loaded yet)
 	// if (defined('THEMETRACE') && THEMETRACE && function_exists('bioship_trace')) {
 	// 	bioship_trace('F',__FUNCTION__,__FILE__);
 	// }

	// --- force direct-only write method using WP Filesystem ---
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		// --- maybe require filesystem ---
		if ( !function_exists( 'WP_Filesytem' ) ) {
			// 2.0.9: fix to double trailing slash on WP filesystem path
			$filesystem = ABSPATH . 'wp-admin' . DIRSEP . 'includes' . DIRSEP . 'file.php';
			require_once $filesystem;
		}
		// --- initialize WP Filesystem ---
		WP_Filesystem();
	}
	$filedir = dirname( $filepath );

	// --- get filesystem credentials ---
	$credentials = request_filesystem_credentials( '', 'direct', false, $filedir, null );
	if ( false === $credentials ) {
		// --- bug out since we cannot do direct writing ---
		bioship_debug( "WP Filesystem Direct Write Method Failed. Check Owner/Group Permissions." );
		return false;
	}

	// --- append method ---
	// note: used in debug line writing
	// 2.0.9: added as bizarrely WP Filesystem has no append method??
	// 2.1.1: double check file exists before getting content
	if ( $append && file_exists( $filepath ) ) {
		$contents = $wp_filesystem->get_contents( $filepath );
		$data = $contents . PHP_EOL . $data;
	}

	// --- write to file --
	// 2.1.1: return write result
	// 2.2.0: check if FS_CHMOD_FILE constant is defined
	$chmod = 0644;
	if ( defined( 'FS_CHMOD_FILE' ) ) {
		$chmod = FS_CHMOD_FILE;
	}
	$result = $wp_filesystem->put_contents( $filepath, $data, $chmod );
	return $result;
 }
}

// -----------------
// Debug File Writer
// -----------------
// 1.8.0: added this for tricky debugging output
// 1.9.8: use debug directory global here
// 2.1.1: added optional append argument
if ( !function_exists( 'bioship_write_debug_file' ) ) {
 function bioship_write_debug_file( $filename, $data, $append = true ) {
 	// if ( defined( 'THEMETRACE') && THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	// -- check/create debug directory --
 	global $vthemedebugdir;
	$vthemedebugdir = bioship_check_create_debug_dir();
	$debugfile = $vthemedebugdir . DIRSEP . $filename;

	// --- write debug file ---
	// 2.0.9: use new append writing method for debug data
	$writedebug = bioship_write_to_file( $debugfile, $data, $append );

	// --- error log writing fallback ---
	// 2.0.9: fallback using error_log if WP Filesystem direct method failed
	if ( !$writedebug ) {
		error_log( $data, 3, $debugfile );
	}
 }
}

// ----------------------------
// Check/Create Debug Directory
// ----------------------------
// 2.0.9: moved to standalone function from bioship_write_debug_file
if ( !function_exists( 'bioship_check_create_debug_dir' ) ) {
 function bioship_check_create_debug_dir() {
 	// if (defined('THEMETRACE') && THEMETRACE && function_exists('bioship_trace')) {
 	// bioship_trace('F',__FUNCTION__,__FILE__);
 	// }

 	// --- maybe create debug directory ---
 	global $vthemedebugdir, $vthemestyledir,  $vthemetemplatedir;
 	if ( !isset( $vthemedebugdir ) ) {
		if ( is_child_theme() ) {
			$vthemedebugdir = $vthemestyledir . 'debug';
		} else {
			$vthemedebugdir = $vthemetemplatedir . 'debug';
		}
		$vthemedebugdir = bioship_apply_filters( 'skeleton_debug_dirpath', $vthemedebugdir );
		if ( !is_dir( $vthemedebugdir ) ) {
			// TODO: maybe use WP Filesystem to create debug directory ?
			wp_mkdir_p( $vthemedebugdir );
		}
	}

	// --- write htaccess file ---
	// 2.0.7: check and write .htaccess file for debug directory
	$htacontents = "order deny,allow" . PHP_EOL . "deny from all";
	$htafile = $vthemedebugdir . DIRSEP . '.htaccess';
	if ( !file_exists( $htafile ) ) {
		$writehta = bioship_write_to_file( $htafile, $htacontents );
	}
	return $vthemedebugdir;
 }
}


