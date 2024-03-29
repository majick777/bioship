<?php

// ===========================
// ====== BioShip Grid =======
// = Dynamic Grid Stylesheet =
// ===========================

// --------------------------
// === grid.php Structure ===
// --------------------------
// === Grid Helpers ===
// - Set Start Time
// - Set Grid Debug Switch
// - Number to Word Helper
// - Round Half Down
// === Set Grid Values ===
// - Set Layout Grid Columns
// - Set Content Grid Columns
// - Set Maximum Layout Width
// - Set Content Width
// - Set Content Padding Percent
// - Set EM Pixels
// - Set Column Spacing
// - Set Content Spacing
// - Grid Compatibility Classes
// - Maybe Buffer Output
// === CSS Output ===
// - Output CSS Header
// - Output Debug Lines
// - Grid Static Common CSS
// - Grid Dynamic Common CSS
// - Generate Grid Column Rules
// - Grid Rules Function
// === Media Screen Width Queries ==
// - Set Breakpoints
// - Loop Breakpoints
// - Grid Breakpoint Refernence
// --------------------------

// Development TODOs
// -----------------
// ? maybe distinguish grid padding from grid margins ?


// --------------------
// === Grid Helpers ===
// --------------------
// 2.0.5: removed WordPress (SHORTINIT) loading
// 2.0.5: removed Theme Test Drive check
// 2.0.5: removed theme settings loading

// --------------
// Set Start Time
// --------------
$starttime = microtime( true );

// ---------------------
// Set Grid Debug Switch
// ---------------------
// 2.0.5: moved to top to prevent possible undefined constant warning
$debug = '';
if ( !defined( 'THEMEDEBUG' ) ) {
	// 2.0.5: removed unused option switch check here
	$themedebug = false;
	if ( isset( $_REQUEST['themedebug'] ) ) {
		$debug = $_REQUEST['themedebug'];
		// note: no on/off switching is allowed here
		if ( ( '2' == $debug ) || ( 'yes' == $debug ) ) {
			$themedebug = true;
		}
		if ( ( '3' == $debug ) || ( 'no' == $debug ) ) {
			$themedebug = false;
		}
	}
	define( 'THEMEDEBUG', $themedebug );
}

// ---------------------
// Number to Word Helper
// ---------------------
// 2.0.5: standalone copy of bioship_number_to_word helper
if ( !function_exists( 'bioship_grid_number_to_word' ) ) {
 function bioship_grid_number_to_word( $number ) {
	$numberwords = array(
		'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight',
		'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
		'seventeen', 'eighteen', 'nineteen', 'twenty', 'twentyone', 'twentytwo', 'twentythree', 'twentyfour',
	);
	if ( array_key_exists( $number, $numberwords ) ) {
		return $numberwords[$number];
	}
	return false;
 }
}

// ---------------
// Round Half Down
// ---------------
// 2.1.2: added this helper function
// (since PHP 5.2 does not have PHP_ROUND_HALF_DOWN mode)
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

// -----------------------
// === Set Grid Values ===
// -----------------------
// 1.8.5: fix to global scope for admin-ajax load method
// 1.9.5: added separate content columns value
// 2.0.5: optimized global declarations
global $gridcolumns, $contentcolumns, $contentpercent, $maxwidth, $contentwidth;
global $gridspacing, $contentspacing, $gridcompat, $empixels, $fontpercent;

// -----------------------
// Set Layout Grid Columns
// -----------------------
// 12, 16, 20 or 24
$gridcolumns = 16; // 16 default
if ( isset( $_REQUEST['gridcolumns'] ) ) {
	$columns = $_REQUEST['gridcolumns'];
	if ( ( 12 == $columns ) || ( 20 == $columns ) || ( 24 == $columns ) ) {
		$gridcolumns = $columns;
	}
}
if ( THEMEDEBUG ) {
	$debug .= "/* Grid Columns: " . $gridcolumns . " */" . PHP_EOL;
}

// ------------------------
// Set Content Grid Columns
// ------------------------
$contentcolumns = 24;
if ( isset( $_REQUEST['contentgridcolumns'] ) ) {
	$columns = $_REQUEST['contentgridcolumns'];
	if ( ( 12 == $columns ) || ( 16 == $columns ) || ( 20 == $columns ) ) {
		$contentcolumns = $columns;
	}
}
if ( THEMEDEBUG ) {
	$debug .= "/* Content Grid Columns: " . $contentcolumns . " */" . PHP_EOL;
}

// ------------------------
// Set Maximum Layout Width
// ------------------------
$maxwidth = '960'; // 960 is default
if ( isset( $_REQUEST['maxwidth'] ) && ( abs( intval( $_REQUEST['maxwidth'] ) ) > 0 ) ) {
	$maxwidth = abs( intval( $_REQUEST['maxwidth'] ) );
}
if ( THEMEDEBUG ) {
	$debug .= "/* Max Width: " . $maxwidth . " */" . PHP_EOL;
}

// -----------------
// Set Content Width
// -----------------
// 2.0.5: removed non-viable fallback calculation
$contentwidth = 960; // no default, assume full width
if ( isset( $_REQUEST['contentwidth'] ) && ( abs( intval( $_REQUEST['contentwidth'] ) ) > 0 ) ) {
	$contentwidth = abs( intval( $_REQUEST['contentwidth'] ) );
}
if ( THEMEDEBUG ) {
	$debug .= "/* Content Width: " . $contentwidth . " */" . PHP_EOL;
}

// ---------------------------
// Set Content Padding Percent
// ---------------------------
// 2.0.5: use calculated rather than raw padding value
$contentpadding = 0;
if ( isset( $_REQUEST['contentpadding'] ) && ( abs( intval( $_REQUEST['contentpadding'] ) ) > 0 ) ) {
	$contentpadding = abs( intval( $_REQUEST['contentpadding'] ) );
}
if ( THEMEDEBUG ) {
	$debug .= "/* Content Padding: " . $contentpadding . " */" . PHP_EOL;
}
// 1.9.5: recalculate padding separately for each content width
// 2.0.5: calculate content percentage (minus padding) once only
$contentpercent = bioship_round_half_down( ( $contentwidth - $contentpadding ) / $maxwidth );
if ( THEMEDEBUG ) {
	$debug .= "/* Content Percentage: " . $contentpercent . " */" . PHP_EOL;
}

// -------------
// Set EM Pixels
// -------------
// note: we are using em values for columns rather thanr pixels or percentages
// ref: http://blog.cloudfour.com/the-ems-have-it-proportional-media-queries-ftw/
// $empixels = 16; // so 1em ~= 16px ...recommend to not change this!
// use of a different percentage value probably would be okay though,
// as we are calculating everything dynamically anyway...
$fontpercent = 100;
if ( isset( $_REQUEST['fontpercent'] ) ) {
	$fontpercentage = abs( intval( $_REQUEST['fontpercent'] ) );
	if ( ( $fontpercentage > 0 ) && ( $fontpercentage < 101 ) ) {
		$fontpercent = $fontpercentage;
	}
}
$empixels = bioship_round_half_down( 16 * ( $fontpercent / 100 ) );
if ( THEMEDEBUG ) {
	$debug .= "/* Font Percent: " . $fontpercent . " */" . PHP_EOL;
	$debug .= "/* EM Pixels: " . $empixels . " */" . PHP_EOL;
}

// ------------------
// Set Column Spacing
// ------------------
// note: it is actually padding acting as an internal margin now
// 1.8.5: made array and added content margins
// 1.9.5: changed variable name from margins to spacing
// 2.0.5: set default to em pixel value adjusted via font percent
// 2.1.1: added missing argument for strstr check
$gridspacing['left'] = $gridspacing['right'] = $empixels;
if ( isset( $_REQUEST['gridspacing'] ) ) {
	if ( !strstr( $_REQUEST['gridspacing'], ',' ) ) {
		if ( abs( intval( $_REQUEST['gridspacing'] ) ) > 0 ) {
			$spacing = abs( intval( $_REQUEST['gridspacing'] ) );
			$gridspacing['left'] = $gridspacing['right'] = $spacing;
		}
	} else {
		// 2.0.5: allow for left/right spacing difference
		$sides = explode( ',', $_REQUEST['gridspacing'] );
		if ( abs( intval( $sides[0] ) ) > 0 ) {
			$gridspacing['left'] = abs( intval( trim( $sides[0] ) ) );
		}
		if ( abs( intval( $sides[1] ) ) > 0 ) {
			$gridspacing['right'] = abs( intval( trim( $sides[1] ) ) );
		}
	}
}

// -------------------
// Set Content Spacing
// -------------------
// note 12px = ~0.75em as 1em ~= 16px
// 2.0.5: set default to three quarter em pixel value
$contentspacing['left'] = $contentspacing['right'] = $empixels * 0.75;
if ( isset( $_REQUEST['contentspacing'] ) ) {
	if ( !strstr( $_REQUEST['contentspacing'], ',' ) ) {
		if ( abs( intval( $_REQUEST['contentspacing'] ) ) > 0 ) {
			$spacing = abs( intval( $_REQUEST['contentspacing'] ) );
			$contentspacing['left'] = $contentspacing['right'] = $spacing;
		}
	} else {
		// 2.0.5: allow for left/right spacing difference
		$sides = explode( ',', $_REQUEST['contentspacing'] );
		if ( abs( intval( $sides[0] ) ) > 0 ) {
			$contentspacing['left'] = abs( intval( trim( $sides[0] ) ) );
		}
		if ( abs( intval( $sides[1] ) ) > 0 ) {
			$contentspacing['right'] = abs( intval( trim( $sides[1] ) ) );
		}
	}
}

// --------------------------
// Grid Compatibility Classes
// --------------------------
// 2.0.5: get grid compatibility from querystring only
$gridcompat = array( '960gs' => '', 'blueprint' => '' );
if ( isset( $_REQUEST['compat'] ) ) {
	$compat = $_REQUEST['compat'];
	if ( strstr( $compat, '960gs' ) ) {
		$gridcompat['960gs'] = 1;
	}
	if ( strstr( $compat, 'blueprint' ) ) {
		$gridcompat['blueprint'] = 1;
	}
}
if ( THEMEDEBUG ) {
	$debug .= "/* Grid Compatibility: " . print_r( $gridcompat, true ) . " */" . PHP_EOL;
}

// -------------------
// Maybe Buffer Output
// -------------------
// to get CSS output length - just for curiosity really
$buffer = false;
if ( isset( $_REQUEST['buffer'] ) ) {
	if ( ( 'yes' == $_REQUEST['buffer'] ) || ( '1' == $_REQUEST['buffer'] ) ) {
		ob_start();
		$buffer = true;
	}
}


// ------------------
// === CSS Output ===
// ------------------

// -----------------
// Output CSS Header
// -----------------
// 2.1.4: moved down for structure
header( "Content-type: text/css; charset: UTF-8" );

// ------------------
// Output Debug Lines
// ------------------
// 2.1.4: output buffered debug lines
if ( '' != $debug ) {
	echo $debug;
}

// ---------------
// Grid Common CSS
// ---------------

?>

/* ------------------- */
/* BioShip Grid System */
/* ------------------- */

/* <?php echo $gridcolumns; ?> Column Layout Grid, <?php echo $contentcolumns; ?> Column Content Grid */

/* Set the default font size to 100% so grid 1em = ~16px */
html, body {font-size: <?php echo $fontpercent; ?>%;}

/* Column Sizing Em Fix */
.column, .columns, #content .column, #content .columns {font-size:initial; float:left; display:inline;}

/* Skeleton Boilerplate Common Rules */
.container, .container_24, .container_20, .container_16, .container_12 {position:relative; margin:0 auto; padding:0;}
.first {margin-left:0 !important;} .first .inner {padding-left:0 !important;}
.last {margin-right:0 !important;} .last .inner {padding-left:0 !important;}
.alpha, .column.alpha, .columns.alpha {margin-left:0;}
.column.alpha .inner, .columns.alpha .inner, .span1.alpha .inner, .span2.alpha .inner, .span3.alpha .inner, .span4.alpha .inner,
.span5.alpha .inner, .span6.alpha .inner, .span7.alpha .inner, .span8.alpha .inner, .span9.alpha .inner, .span10.alpha .inner,
.span11.alpha .inner, .span12.alpha .inner, .span13.alpha .inner, .span14.alpha .inner, .span15.alpha .inner, .span16.alpha .inner,
.span17.alpha .inner, .span18.alpha .inner, .span19.alpha .inner, .span20.alpha .inner, .span21.alpha .inner, .span22.alpha .inner,
.span23.alpha .inner, .span24.alpha .inner {padding-left:0;}
.omega, .column.omega, .columns.omega {margin-right:0;}
.column.omega .inner, .columns.omega .inner, .span1.omega .inner, .span2.omega .inner, .span3.omega .inner, .span4.omega .inner,
.span5.omega .inner, .span6.omega .inner, .span7.omega .inner, .span8.omega .inner, .span9.omega .inner, .span10.omega .inner,
.span11.omega .inner, .span12.omega .inner, .span13.omega .inner, .span14.omega .inner, .span15.omega .inner, .span16.omega .inner,
.span17.omega .inner, .span18.omega .inner, .span19.omega .inner, .span20.omega .inner, .span21.omega .inner, .span22.omega .inner,
.span23.omega .inner, .span24.omega .inner {padding-right:0;}

/* Fraction Percentage Widths */
.one_half, .one_halve, .one_third, .two_thirds, .one_fourth, .three_fourths, .one_quarter, .two_quarters, .three_quarters,
.one_fifth, .two_fifth, .two_fifths, .three_fifth, .three_fifths, .four_fifth, .four_fifths, .one_sixth, .two_sixth, .two_sixths,
.three_sixth, .three_sixths, .four_sixth, .four_sixths, .five_sixth, .five_sixths {position:relative; float:left;}
.one_half, .two_quarters, .three_sixth, .three_sixths {width:49.5%}
.one_third, .two_sixth, .two_sixths {width:32.5%} .two_thirds, .four_sixth, .four_sixths {width:65.5%}
.one_fourth, .one_quarter {width:24.5%} .three_fourths, .three_quarters {width:74.5%}
.one_fifth {width:19.5%} .two_fifth, .two_fifths {width:39.5%} .three_fifth, .three_fifths {width:59.5%} .four_fifth, .four_fifths {width:79.5%}
.one_sixth {width:16%} .five_sixth, .five_sixths {width:83%}

/* Clear and Clearfix */
<?php // 1.9.8: fix to remove overflow:hidden from clears (causing display height to actually exist?) ?>
.container:after {content:"\0020"; display:block; height:0; clear:both; visibility:hidden;}
.clearfix:before, .clearfix:after {content:"\0020"; display:block; visibility:hidden; width:0; height:0; font-size: 0; line-height: 0;}
.clear {clear:both; display:block; visibility:hidden; width:0; height:0;}
.clearfix:after, .u-cf {clear:both;} .clearfix {zoom:1;}

<?php

	// Full Width Container Override
	// -----------------------------
	if ( isset( $_GET['fullwidth'] ) ) {
		if ( ( 'yes' == $_GET['fullwidth'] ) || ( '1' == $_GET['fullwidth'] ) ) {
			echo "/* Full Width Override */";
			echo PHP_EOL . '#wrap.container {width: 100% !important;}' . PHP_EOL . PHP_EOL;
		}
	}

	// 960 Grid System Common Rules
	// ----------------------------
	// 1.9.5: set for content grid only and optimized
	if ( '1' == $gridcompat['960gs'] ) {

		echo PHP_EOL . "/* 960 Grid System */" . PHP_EOL . PHP_EOL;

		$gridrules = $pushrules = $pullrules = '';
		for ( $i = 1; $i < ( $contentcolumns + 1 ); $i++ ) {
			if ( '' != $gridrules ) {$gridrules .= ', ';}
			if ( '' != $pushrules ) {$pushrules .= ', ';}
			if ( '' != $pullrules ) {$pullrules .= ', ';}
			if ( ( 8 == $i ) || ( 16 == $i ) ) {
				$gridrules .= PHP_EOL;
				$pushrules .= PHP_EOL;
				$pullrules .= PHP_EOL;
			}
			$gridrules .= '.grid_' . $i;
			$pushrules .= '.push_' . $i;
			$pullrules .= '.pull_' . $i;
		}
		echo $gridrules;
		echo ' {float:left; display:inline; margin-left:0; margin-right:0;}' . PHP_EOL;
		echo PHP_EOL . $pushrules . ',' . PHP_EOL . $pullrules . ' {position:relative;}' . PHP_EOL;
	}

	// Percentage Content Columns
	// --------------------------
	// 1.9.5: percentages prototype
	echo '/* Content Column Grid */' . PHP_EOL;
	echo '#content .container_24, #content .container_20, #content .container_16, #content .container_12 {width: 100%;}' . PHP_EOL;
	echo '#content .container_24:after, #content .container_20:after, #content .container_16:after, #content .container_12:after {clear: both;}' . PHP_EOL;
	// echo '#content .container_'.$contentcolumns.':before, .container_'.$contentcolumns.':after
	// echo " {content: "."; display: block; overflow: hidden; visibility: hidden;  width: 0; height: 0; font-size: 0; line-height: 0;}'.PHP_EOL;

	// no need to set anything for inner columns here ?
	// #content .column .inner, #content .columns .inner {}

	$rules = array(
		'content' => '', 'padleft' => '', 'padright'=> '' , 'shiftleft' => '', 'shiftright' => ''
	);
	$rules = bioship_content_grid_generate_rules( $rules, 24 );
	$rules = bioship_content_grid_generate_rules( $rules, 20 );
	$rules = bioship_content_grid_generate_rules( $rules, 16 );
	$rules = bioship_content_grid_generate_rules( $rules, 12 );

	echo $rules['content'] . PHP_EOL;
	echo $rules['padleft'] . PHP_EOL;
	echo $rules['padright'] . PHP_EOL;
	echo $rules['shiftleft'] . PHP_EOL;
	echo $rules['shiftright'] . PHP_EOL;

	// Generate Content Grid Rules
	// ---------------------------
	// 1.9.5: new content grid
	function bioship_content_grid_generate_rules( $rules, $columns ) {

		global $gridcompat, $contentcolumns;
		$c = $columns;
		$contentrules = $padleftrules = $padrightrules = $shiftleftrules = $shiftrightrules = '';

		// --- 24 columns container ---
		for ( $i = 1; $i < ( $c + 1 ); $i++ ) {

			$word = bioship_grid_number_to_word( $i );
			$percent = bioship_round_half_down( 99 * ( $i / $c ) );

			if ( 1 == $i ) {
				if ( $contentcolumns == $c ) {
					$contentrules .= "#content .container .one.column, ";
				}
				$contentrules .= "#content .container_" . $c . " .one.column, ";
			}
			if ( $contentcolumns == $c ) {
				$contentrules .= "#content .container ." . $word . ".columns, #content .container .span" . $i . ", ";
			}
			$contentrules .= "#content .container_" . $c . " ." . $word . ".columns, #content .container_" . $c . " .span" . $i;

			// --- 960gs and Blueprint ---
			if ( '1' == $gridcompat['960gs'] ) {
				if ( $contentcolumns == $c ) {
					$contentrules .= ", #content .container .grid_" . $i;
				}
				$contentrules .= ", #content .container_" . $c . " .grid_" . $i;
			}
			if ( '1' == $gridcompat['blueprint'] ) {
				if ( $contentcolumns == $c ) {
					$contentrules .= ", #content .container .grid_" . $i;
				}
				$contentrules .= ", #content .container_" . $c . " .span-" . $i;
			}
			$contentrules .= " {width: " . $percent . "%;}" . PHP_EOL;

			if ( 1 == $i ) {
				if ( $contentcolumns == $c ) {
					$padleftrules .= "#content .container .offsethalfleft, #content .container .offsethalf, ";
				}
				$padleftrules .= "#content .container_" . $c . " .offsethalfleft, #content .container_" . $c . " .offsethalf";
				$padleftrules .= " {padding-left:" . ( $percent / 2 ) . "%;}" . PHP_EOL;
				if ( $contentcolumns == $c ) {
					$padleftrules .= "#content .container .offsetquarter, #content .container .offsetquarterleft, ";
				}
				$padleftrules .= "#content .container_" . $c . " .offsetquarter, #content .container_" . $c . " .offsetquarterleft";
				$padleftrules .= " {padding-left:" . ( $percent / 4 ) . "%;}" . PHP_EOL;
			}
			// 1.9.6: fix to offsetleft typo
			if ( $contentcolumns == $c ) {
				$padleftrules .= "#content .container .offset" . $i . ", #content .container .offsetleft" . $i . ", ";
			}
			$padleftrules .= "#content .container_" . $c . " .offset" . $i . ", #content .container_" . $c . " .offsetleft" . $i;
			if ( '1' == $gridcompat['960gs'] ) {
				if ( $contentcolumns == $c ) {
					$padleftrules .= ", #content .container .prefix_" . $i;
				}
				$padleftrules .= ", #content .container_" . $c . " .prefix_" . $i;
			}
			if ( '1' == $gridcompat['blueprint'] ) {
				if ( $contentcolumns == $c ) {
					$padleftrules .= ", #content .container .prepend-" . $i;
				}
				$padleftrules .= ", #content .container_" . $c . " .prepend-" . $i;
			}
			$padleftrules .= " {padding-left:" . $percent . "%;}" . PHP_EOL;

			if ( 1 == $i ) {
				if ( $contentcolumns == $c ) {
					$padrightrules .= "#content .container .offsethalfright, ";
				}
				$padrightrules .= "#content .container_" . $c . " .offsethalfright";
				$padrightrules .= " {padding-right:" . ( $percent / 2 ) . "%;}" . PHP_EOL;
				if ( $contentcolumns == $c ) {
					$padrightrules .= "#content .container .offsetquarterright, ";
				}
				$padrightrules .= "#content .container_" . $c . " .offsetquarterright";
				$padrightrules .= " {padding-right:" . ( $percent / 4 ) . "%;}" . PHP_EOL;
			}
			if ( $contentcolumns == $c ) {
				$padrightrules .= "#content .container .offsetright" . $i . ", ";
			}
			$padrightrules .= "#content .container_" . $c . " .offsetright" . $i;
			if ( '1' == $gridcompat['960gs'] ) {
				if ( $contentcolumns == $c ) {
					$padrightrules .= ", #content .container .suffix_" . $i;
				}
				$padrightrules .= ", #content .container_" . $c . " .suffix_" . $i;
			}
			if ( '1' == $gridcompat['blueprint'] ) {
				if ( $contentcolumns == $c ) {
					$padrightrules .= ", #content .container .append-" . $i;
				}
				$padrightrules .= ", #content .container_" . $c . " .append-" . $i;
			}
			$padrightrules .= " {padding-right:" . $percent . "%;}" . PHP_EOL;

			if ( 1 == $i ) {
				if ( $contentcolumns == $c ) {
					$shiftleftrules .= "#content .container .shifthalfleft, ";
				}
				$shiftleftrules .= "#content .container_" . $c . " .shifthalfleft";
				$shiftleftrules .= " {margin-left:-" . ( $percent / 2 ) . "%;}" . PHP_EOL;
				if ( $contentcolumns == $c ) {
					$shiftleftrules .= "#content .container .shiftquarterleft, ";
				}
				$shiftleftrules .= "#content .container_" . $c . " .shiftquarterleft";
				$shiftleftrules .= " {margin-left:-" . ( $percent / 4 ) . "%;}" . PHP_EOL;
			}
			if ( $contentcolumns == $c ) {
				$shiftleftrules .= "#content .container .shiftleft" . $i . ", ";
			}
			$shiftleftrules .= "#content .container_" . $c . " .shiftleft" . $i;
			if ( '1' == $gridcompat['960gs'] ) {
				if ( $contentcolumns == $c ) {
					$shiftleftrules .= ", #content .container .pull_" . $i;
				}
				$shiftleftrules .= ", #content .container_" . $c . " .pull_" . $i;
			}
			if ( '1' == $gridcompat['blueprint'] ) {
				if ( $contentcolumns == $c ) {
					$shiftleftrules .= ", #content .container .pull-" . $i;
				}
				$shiftleftrules .= ", #content .container_" . $c . " .pull-" . $i;
			}
			$shiftleftrules .= " {margin-left:-" . $percent . "%;}" . PHP_EOL;

			if ( 1 == $i ) {
				if ( $contentcolumns == $c ) {
					$shiftrightrules .= "#content .container .shifthalfright, ";
				}
				$shiftrightrules .= "#content .container_" . $c . " .shifthalfright";
				$shiftrightrules .= " {margin-left:" . ( $percent / 2 ) . "%;}" . PHP_EOL;
				if ( $contentcolumns == $c ) {
					$shiftrightrules .= "#content .container .shiftquarterright, ";
				}
				$shiftrightrules .= "#content .container_" . $c . " .shiftquarterright";
				$shiftrightrules .= " {margin-left:" . ( $percent / 4 ) . "%;}" . PHP_EOL;
			}
			if ( $contentcolumns == $c ) {
				$shiftrightrules .= "#content .container .shiftright" . $i . ", ";
			}
			$shiftrightrules .= "#content .container_" . $c . " .shiftright" . $i;
			if ( '1' == $gridcompat['960gs'] ) {
				if ( $contentcolumns == $c ) {
					$shiftrightrules .= ", #content .container .push_" . $i;
				}
				$shiftrightrules .= ", #content .container_" . $c . " .push_" . $i;
			}
			if ( '1' == $gridcompat['blueprint'] ) {
				if ( $contentcolumns == $c ) {
				    // 2.2.0: fix to missing lefthand assignment variable
                    $shiftrightrules .= ", #content .container .push-" . $i;
				}
				$shiftrightrules .= ", #content .container_" . $c . " .push-" . $i;
			}
			$shiftrightrules .= " {margin-left:" . $percent . "%;}" . PHP_EOL;
		}

		$rules['content'] .= $contentrules;
		$rules['padleft'] .= $padleftrules;
		$rules['padright'] .= $padrightrules;
		$rules['shiftleft'] .= $shiftleftrules;
		$rules['shiftright'] .= $shiftrightrules;
		return $rules;
	}

?>


/* Grid Column Rules */
/* ----------------- */

<?php

// --------------------------
// Generate Grid Column Rules
// --------------------------
// (for the layout maxwidth)
$defaultcss = bioship_grid_css_rules( $maxwidth, false, 'full' );
echo $defaultcss . PHP_EOL . PHP_EOL;


// -------------------
// Grid Rules Function
// -------------------
function bioship_grid_css_rules( $totalwidth, $mobile, $offset ) {

	// 2.0.5: optimized global declarations
	global $gridcolumns, $contentcolumns, $contentpercent, $maxwidth, $contentwidth;
	global $gridspacing, $contentspacing, $gridcompat, $empixels, $fontpercent;

	// echo "/* ".$gridcolumns." - ".$contentcolumns." - ".$contentpadding." - ".$gridspacing." - ".$contentspacing.PHP_EOL;
	// echo $maxwidth." - ".$contentwidth." - ".$empixels." - ".$fontpercent." */".PHP_EOL;

	// Column Size Calculations
	// ------------------------

	// --- margins ---
	$leftmarginem = bioship_round_half_down( $gridspacing['left'] / $empixels );
	$rightmarginem = bioship_round_half_down( $gridspacing['right'] / $empixels );
	$halfleftmarginem = bioship_round_half_down( $leftmarginem / 2 );
	$halfrightmarginem = bioship_round_half_down( $rightmarginem / 2 );
	// 1.8.5: added separate content margin values
	$contentleftmarginem = bioship_round_half_down( $contentspacing['left'] / $empixels );
	$contentrightmarginem = bioship_round_half_down( $contentspacing['right'] / $empixels );

	// --- total width ---
	$totalwidthem = bioship_round_half_down( $totalwidth / $empixels );
	$almostfullwidth = bioship_round_half_down( ( $totalwidth - $gridspacing['left'] - $gridspacing['right']) / $empixels );
	// $totalwidthem = $totalwidthem - $halfleftmarginem - $halfrightmarginem;

	// --- layout columns ---
	// 1.8.0: removed outer margins for replacement by inner padding
	// $totalwidth = $totalwidth - (($gridspacing['left'] + $gridspacing['right']) / 2);
	$columnwidth = $totalwidth / $gridcolumns;
	$columnwidthem  = bioship_round_half_down( $columnwidth / $empixels );
	// 1.8.0: add half, third and quarter columns for spacing
	$halfcolumnwidthem  = bioship_round_half_down( ( $columnwidth / 2 ) / $empixels );
	$thirdcolumnwidthem = bioship_round_half_down( ( $columnwidth / 3 ) / $empixels );
	$quartercolumnwidthem = bioship_round_half_down( ( $columnwidth / 4 ) / $empixels );

	// --- content columns ---
	// 1.8.0: work out content column widths (via actual content width passed in querystring)
	$thiscontentwidth = bioship_round_half_down( $totalwidth * $contentpercent );
	// 1.9.5: for mobile sizes, use layout width rules for full width content columns
	if ( $mobile ) {
		$thiscontentwidth = $totalwidth;
	}
	$thiscontentwidthem = bioship_round_half_down( $thiscontentwidth / $empixels );
	// 1.9.5: use separate content columns value at 98% content width
	// $contentcolumnwidth = $thiscontentwidth / $gridcolumns;
	$contentcolumnwidth = $thiscontentwidth / $contentcolumns;
	$contentcolumnsem = bioship_round_half_down( ( 0.98 * $contentcolumnwidth ) / $empixels );
	// $contentcolumnsem = $contentcolumnsem - (($halfleftmarginem + halfrightmarginem) / 2);
	// 1.8.0: add half, third and quarter columns for spacing
	$halfcontentcolumnsem = bioship_round_half_down( $contentcolumnsem / 2 );
	$thirdcontentcolumnwidthem = bioship_round_half_down( $contentcolumnsem / 3 );
	$quartercontentcolumnsem = bioship_round_half_down( $contentcolumnsem / 4 );

	// --- Header for this Media Width Size ---
	$rules = PHP_EOL . '	/* Column Width Rules based on ' . $totalwidth . 'px (' . $totalwidthem . 'em) */' . PHP_EOL . PHP_EOL;
	$contentrules = '';

	// --- set numbered column widths array in em ---
	for ( $i = 1; $i < ( $gridcolumns + 1 ); $i++ ) {
		$column[$i] = $columnwidthem * $i;
		// $halfcolumn{$i] = $halfcolumnwidthem * $i;
	}

	// 1.9.5: set content column widths separately
	for ( $i = 1; $i < ( $contentcolumns + 1 ); $i++ ) {
		$contentcolumn[$i] = bioship_round_half_down( $contentcolumnsem * $i );
		$halfcontentcolumn[$i] = bioship_round_half_down( $halfcontentcolumnsem * $i );
	}

	// --- Skeleton Boilerplate ---
	// 1.9.5: set container width only here, moved common rules out and above
	// $rules .= '	.container {position:relative; width:'.$totalwidthem.'em; margin:0 auto; padding:0;}'.PHP_EOL;
	// $rules .= '	.column, .columns {float:left; display:inline;}'.PHP_EOL;
	// 1.9.6: fix to recalculate wrap width total
	$wrapwidthem = $columnwidthem * $gridcolumns;
	$rules .= '	#wrap.container {width:' . $wrapwidthem . 'em;}' . PHP_EOL;

	// 1.8.0: changed outer margins to inner padding!
	$rules .= '	.column .inner, .columns .inner {padding-left:' . $leftmarginem . 'em; padding-right:' . $rightmarginem . 'em;}' . PHP_EOL;
	// 1.8.5: added separate content margin sizes
	$rules .= '	#content .column .inner, #content .columns .inner {padding-left:' . $contentleftmarginem . 'em; padding-right:' . $contentrightmarginem . 'em;}' . PHP_EOL;

	// --- 960 Grid System ---
	// 1.9.5: for content grid only, removed duplicate rules
	if ( '1' == $gridcompat['960gs'] ) {
		// $contentrules .= '	#content .container_'.$contentcolumns.' {margin-left: auto; margin-right: auto; width: '.$thiscontentwidthem.'em; font-size:initial;}'.PHP_EOL;
		// $contentrules .= '	#content .container_'.$contentcolumns.':after {clear: both;}'.PHP_EOL;
		$contentrules .= '	#content .container_' . $contentcolumns . ':before, .container_' . $contentcolumns . ':after {content: "."; display: block; overflow: hidden; visibility: hidden; width: 0; height: 0; font-size: 0; line-height: 0;}' . PHP_EOL;
	}
	// 1.9.6: fix to em content width, should just be 100% width now
	$contentrules .= '	#content .container {width: 100%;}' . PHP_EOL;
	// $contentrules .= '	#content .container {width: '.$thiscontentwidthem.'em; font-size:initial;}'.PHP_EOL;

	// --- set element rule names ---
	$widthrules = $padleftrules = $padrightrules = $pushrules = $pullrules = array();
	$mobilequeries = '';

	// if ($mobile) {$offsetprefix = '.container ';} else {$offsetprefix = '';}
	$offsetprefix = '';

	// Layout Grid Column Rules
	// ------------------------
	for ( $i = 1; $i < ( $gridcolumns + 1 ); $i++ ) {

		// 1.8.0: added inner width rules
		// .spanx and .xxxxx.columns
		$widthrulea = '.span' . $i;
		$innerwidthrulea = $widthrulea . ' .inner';
		$widthruleb = ', .';
		$widthruleb .= bioship_grid_number_to_word( $i );
		$widthruleb .= '.column';
		if ( $i > 1 ) {
			$widthruleb .= 's';
		}
		$innerwidthruleb = $widthruleb . ' .inner';
		// 1.8.5: allow for a 'one.columns' plural typo
		// 2.0.8: fix for 'one.columns' typo
		if ( 1 == $i ) {
			$widthruleb .= ', .one.columns';
			$innerwidthruleb .= ', .one.columns .inner';
		}
		$widthrules[$i] = $widthrulea . $widthruleb;
		$innerwidthrules[$i] = $innerwidthrulea . $innerwidthruleb;
	}

	// Content Grid Column Rules
	// -------------------------
	// 1.9.5: separate content columns grid loop
	for ( $i = 1; $i < ( $contentcolumns + 1 ); $i++ ) {

		// 1.5.5: fix, #content subelements (not main #content element)
		// 1.8.0: added inner content rules
		$contentwidthrulea = '	#content .span' . $i;
		$innercontentwidthrulea = $contentwidthrulea . ' .inner';
		$contentwidthruleb = ', #content .';
		$contentwidthruleb .= bioship_grid_number_to_word( $i );
		$contentwidthruleb .= '.column';
		if ( $i > 1 ) {
			$contentwidthruleb .= 's';
		}
		$innercontentwidthruleb = $contentwidthruleb . ' .inner';

		// 1.8.5: allow for a 'one.columns' plural typo
		// 1.9.8: fix to innercontentwidthruleb variable
		// 2.0.8: prefix for one.columns rule specificity
		if ( 1 == $i ) {
			$contentwidthruleb .= ', #content .one.columns';
			$innercontentwidthruleb .= ', .one.columns .inner';
		}
		$contentwidthrules[$i] = $contentwidthrulea . $contentwidthruleb;
		$innercontentwidthrules[$i] = $innercontentwidthrulea . $innercontentwidthruleb;

		$padleftrules[$i] = '	' . $offsetprefix . '.offset' . $i . ', ' . $offsetprefix . '.offsetleft' . $i;
		$padrightrules[$i] = '	' . $offsetprefix . '.offsetright' . $i;
		$pushrules[$i] = '	.shiftright' . $i;
		$pullrules[$i] = '	.shiftleft' . $i;

		// --- 960 Grid System ---
		// 1.9.5: add to content grid only
		if ( isset( $gridcompat['960gs'] ) && ( '1' == $gridcompat['960gs'] ) ) {
			// $widthrules[$i] .= ', .grid_'.$i;
			// $innerwidthrules[$i] .= ', .grid_'.$i.' .inner';
			$contentwidthrules[$i] .= ', .grid_' . $i;
			$innercontentwidthrules[$i] .= ', .grid_' . $i . ' .inner';
			if ( $i < $contentcolumns ) {
				$padleftrules[$i] .= ', ' . $offsetprefix . '.prefix_' . $i;
				$padrightrules[$i] .= ', ' . $offsetprefix . '.suffix_' . $i;
				$pushrules[$i] .= ', .push_' . $i;
				$pullrules[$i] .= ', .pull_' . $i;
			}
		}

		// --- Blueprint ---
		// 1.9.5: add to content grid only
		if ( isset( $gridcompat['blueprint'] ) && ( '1' == $gridcompat['blueprint'] ) ) {
			// $widthrules[$i] .= ', .span-'.$i;
			// $innerwidthrules[$i] .= ', .span-'.$i.' .inner';
			$contentwidthrules[$i] .= ', .span-' . $i;
			$innercontentwidthrules[$i] .= ', .span-' . $i . ' .inner';
			if ( $i < $contentcolumns ) {
				$padleftrules[$i] .= ', ' . $offsetprefix . '.prepend-' . $i;
				$padrightrules[$i] .= ', ' . $offsetprefix . '.append-' . $i;
				$pushrules[$i] .= ', .push-' . $i;
				$pullrules[$i] .= ', .pull-' . $i;
			}
		}
	}
	$rules .= PHP_EOL;

	// Main Width Rules
	// ----------------
	// Skeleton Boilerplate: .spanX, .xxxxxx.columns
	// 1.9.0: separate layout and content grids
	for ( $i = 1; $i < ( $gridcolumns + 1 ); $i++ ) {
		if ( !$mobile ) {
			$rules .= '	' . $widthrules[$i] . ' {width: ' . $column[$i] . 'em;}' . PHP_EOL;
		} else {
			// 1.8.0: added full width mobile columns with max-widths
			$mobilequeries .= '	' . $widthrules[$i] . ' {width: ' . $column[$gridcolumns] . 'em; max-width:96%;}' . PHP_EOL;
		}
	}
	$rules .= PHP_EOL;

	// Content Width Rules
	// -------------------
	// Skeleton Boilerplate: .spanX, .xxxxxx.columns
	// 960 Grid System: grid_X
	// Blueprint: span-X
	// if ( !$mobile ) {
		// 1.9.5: removed em specific mobile query content grid widths in favour of percentages
		// for ($i = 1; $i < ($contentcolumns+1); $i++) {
		// 	$contentrules .= ' '.$contentwidthrules[$i].' {width: '.$contentcolumn[$i].'em;}'.PHP_EOL;
		// }
	// }
	if ( $mobile ) {
		// 1.8.0: added full width mobile columns with max-widths
		// 1.9.5: replaced with min-widths for dual column mobile content layout
		if ( 'zero' == $offset ) {
			$minwidth = '100';
		} elseif ( 'half' == $offset ) {
			$minwidth = '50';
		} else {
			$minwidth = '50';
		}
		// 2.0.8: fix for full width mobile columns on smaller screens
		// $contentrules .= '	'.$contentwidthrules[$i].' {width: '.$contentcolumn[$i].'em; max-width:100%; min-width:'.$minwidth.'%;}'.PHP_EOL;
		for ( $i = 1; $i < ( $contentcolumns + 1 ); $i++ ) {
			$contentwidthrule = str_replace( '	#content', '#wrap #content', $contentwidthrules[$i] );
			$contentrules .= $contentwidthrule;
			if ( $i < $contentcolumns ) {
				$contentrules .= ', ';
			}
		}
		// $contentrules .= '	'.$contentwidthrule.' {width: '.$thiscontentwidthem.'em; max-width:100%; min-width:'.$minwidth.'%;}'.PHP_EOL;
		$contentrules .= ' {max-width:100%; min-width:' . $minwidth . '%;}' . PHP_EOL;
	}
	$contentrules .= PHP_EOL;

	// Add Padding Left Rules
	// ----------------------
	// (uses: padding-left)
	// Skeleton Boilerplate: offsetX, offsetleftX
	// 960 Grid System: prefix_X
	// Blueprint: prepend-X
	// note: offset-by-xxxxx classes removed
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ( $i = 1; $i < $contentcolumns; $i++ ) {
		if ( 'half' == $offset ) {
			$contentrules .= '	' . $padleftrules[$i] . ' {padding-left:' . $halfcontentcolumn[$i] . 'em;}' . PHP_EOL;
		} elseif ( 'zero' == $offset ) {
			$contentrules .= '	' . $padleftrules[$i] . ' {padding-left:0;}' . PHP_EOL;
		}
		// elseif ($offset == 'full') {
		//	$contentrules .= '	' . $padleftrules[$i] . ' {padding-left:' . $contentcolumn[$i] . 'em;}' . PHP_EOL;
		// }
	}
	$contentrules .= PHP_EOL;

	// Add Padding Right Rules
	// -----------------------
	// (uses: padding-right)
	// Skeleton Boilerplate: offsetrightX
	// 960 Grid System: suffix_X
	// Blueprint: append-X
	// note: offset-by-xxxxx classes removed
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ( $i = 1; $i < $contentcolumns; $i++ ) {
		if ( 'half' == $offset ) {
			$contentrules .= '	' . $padrightrules[$i] . ' {padding-right:' . $halfcontentcolumn[$i] . 'em;}' . PHP_EOL;
		} elseif ( 'zero' == $offset ) {
			$contentrules .= '	' . $padrightrules[$i] . ' {padding-right:0;}' . PHP_EOL;
		}
		// elseif ( 'full' == $offset ) {
		//	$contentrules .= '	' . $padrightrules[$i] . ' {padding-right:' . $contentcolumn[$i] . 'em;}' . PHP_EOL;
		// }
	}
	$contentrules .= PHP_EOL;

	// Add Push Rules
	// --------------
	// 1.9.5: no longer negative margin-right (unreliable)
	// (uses: positive margin-left)
	// Skeleton Boilerplate - n/a (added shiftrightX)
	// 960GS - left - push_X
	// Blueprint - strange margins - push-X
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ( $i = 1; $i < $contentcolumns; $i++ ) {
		if ( '' != $pushrules[$i] ) {
			if ( 'half' == $offset ) {
				$contentrules .= '	' . $pushrules[$i] . ' {margin-left: ' . $halfcontentcolumn[$i] . 'em;}' . PHP_EOL;
			} elseif ( 'zero' == $offset ) {
				$contentrules .= '	' . $pushrules[$i] . ' {margin-left:0;}' . PHP_EOL;
			}
			// elseif ( 'full' == $offset ) {
			//	$contentrules .= '	' . $pushrules[$i] . ' {margin-left: ' . $contentcolumn[$i] . 'em;}' . PHP_EOL;
			// }
		}
	}
	$contentrules .= PHP_EOL;

	// Add Pull Rules
	// --------------
	// (uses: negative margin-left)
	// Skeleton Boilerplate - n/a (added: shiftleftX)
	// 960GS - negative left - pull_X
	// Blueprint - negative margin-left - pull-X
	// 1.8.5: only really needed for content columns
	// 1.9.5: use separate content grid columns, mobile only overrides
	for ( $i = 1; $i < $contentcolumns; $i++ ) {
		if ( '' != $pullrules[$i] ) {
			if ( 'half' == $offset ) {
				$contentrules .= '	' . $pullrules[$i] . ' {margin-left: -' . $halfcontentcolumn[$i] . 'em;}' . PHP_EOL;
			} elseif ( 'zero' == $offset ) {
				$contentrules .= '	' . $pullrules[$i] . ' {margin-left:0;}' . PHP_EOL;
			}
			// elseif ( 'full' == $offset ) {
			//	$contentrules .= '	' . $pullrules[$i] . ' {margin-left: -' . $contentcolumn[$i] . 'em;}' . PHP_EOL;
			// }
		}
	}
	$contentrules .= PHP_EOL;

	// Half Offsets and Shifts
	// -----------------------
	// 1.8.5: only really needed for content columns
	// // $rules .= ' .offset-by-half, .offsetbyhalf {padding-left:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsethalfleft, .columns.offsethalfleft {padding-left:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsethalfright, .columns.offsethalfright {padding-right:'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shifthalfleft, .columns.shifthalfleft {margin-left:-'.$halfcolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shifthalfright, .columns.shifthalfright {margin-right:-'.$halfcolumnwidthem.'em;}'.PHP_EOL;

	// 1.9.5: handled in content container grid generation
	// $contentrules .= '	#content .column.offsethalfleft, #content .columns.offsethalfleft {padding-left:'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.offsethalfright, #content .columns.offsethalfright {padding-right:'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shifthalfleft, #content .columns.shifthalfleft {margin-left:-'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shifthalfright, #content .columns.shifthalfright {margin-right:-'.$halfcontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= PHP_EOL;

	// Quarter Offsets and Shifts
	// --------------------------
	// 1.8.0: added quarter offsets and shifts
	// 1.8.5: only really needed for content columns
	// // $rules .= ' .offset-by-quarter, .offsetbyquarter {padding-left:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsetquarterleft, .columns.offsetquarterleft {padding-left:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.offsetquarterright, .columns.offsetquarterright {padding-right:'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shiftquarterleft, .columns.shiftquarterleft {margin-left:-'.$quartercolumnwidthem.'em;}'.PHP_EOL;
	// $rules .= '	.column.shiftquarterright, .columns.shiftquarterright {margin-right:-'.$quartercolumnwidthem.'em;}'.PHP_EOL;

	// 1.9.5: handled in content container grid generation
	// $contentrules .= '	#content .column.offsetquarterleft, #content .columns.offsetquarterleft {padding-left:'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.offsetquarterright, #content .columns.offsetquarterright {padding-right:'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shiftquarterleft, #content .columns.shiftquarterleft {margin-left:-'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= '	#content .column.shiftquarterright, #content .columns.shiftquarterright {margin-right:-'.$quartercontentcolumnsem.'em;}'.PHP_EOL;
	// $contentrules .= PHP_EOL;

	// Fractional Column Widths
	// ------------------------
	if ( $mobile ) {
		$halfcolumnwidthem = $thirdcolumnwidthem = $quartercolumnwidthem = 0;
	}
	$rules .= '	.one-half.column, .one-half.columns, .half-column.column, .half-column.columns {width:' . $halfcolumnwidthem . 'em;}' . PHP_EOL;
	$rules .= '	.one-third.column, .one-third.columns {width:' . $thirdcolumnwidthem . 'em;}' . PHP_EOL;
	$rules .= '	.two-thirds.column, .two-thirds.columns {width:' . ( $thirdcolumnwidthem * 2 ) . 'em;}' . PHP_EOL;
	$rules .= '	.one-quarter.column, .one-quarter.columns, .quarter-column.column, .quarter-column.columns {width:' . $quartercolumnwidthem . 'em;}' . PHP_EOL;
	$rules .= '	.two-quarters.column, .two-quarters.columns {width:' . $halfcolumnwidthem . 'em;}' . PHP_EOL;
	$rules .= '	.three-quarters.column, .three-quarters.columns {width:' . ( $quartercolumnwidthem * 3 ) . 'em;}' . PHP_EOL;

	// Content Fractional Width Columns
	// --------------------------------
	$contentrules .= '	#content .one-half.column, #content .one-half.columns, #content .half-column.column, #content .half-column.columns {width:' . $halfcolumnwidthem . 'em;}' . PHP_EOL;
	$contentrules .= '	#content .one-third.column, #content .one-third.columns {width:' . $thirdcolumnwidthem . 'em;}' . PHP_EOL;
	$contentrules .= '	#content .two-thirds.column, #content .two-thirds.columns {width:' . ( $thirdcolumnwidthem * 2 ) . 'em;}' . PHP_EOL;
	$contentrules .= '	#content .one-quarter.column, #content .one-quarter.columns, #content .quarter-column.column, #content .quarter-column.columns {width:' . $quartercolumnwidthem . 'em;}' . PHP_EOL;
	$contentrules .= '	#content .two-quarters.column, #content .two-quarters.columns {width:' . $halfcolumnwidthem . 'em;}' . PHP_EOL;
	$contentrules .= '	#content .three-quarters.column, #content .three-quarters.columns {width:' . ( $quartercolumnwidthem * 3 ) . 'em;}' . PHP_EOL;

	$rules .= PHP_EOL;

	// Mobile Queries
	// --------------
	if ( $mobile ) {
		// override to full width container (no wrapper) for small screens
		// 1.9.5: target #wrap container with this rule
		$mobilequeries .= PHP_EOL . '	#wrap.container {width:100% !important;}' . PHP_EOL;

		// override to almost full width for partial percentage columns for small screens
		$mobilequeries .= '	.one_half, .one_third, .two_thirds, .one_fourth, .three_fourths, .one_quarter, .three_quarters,' . PHP_EOL;
		$mobilequeries .= '	.one_fifth, .two_fifth, .two_fifths, .three_fifth, three_fifths, .four_fifth, four_fifths,' . PHP_EOL;
		$mobilequeries .= '	.one_sixth, .two_sixth, .two_sixths, .three_sixth, .three_sixths, .four_sixth, .four_sixths, .five_sixth, .five_sixths' . PHP_EOL;
		$mobilequeries .= ' {width:96% !important; margin-left:2% !important; margin-right:2% !important;}' . PHP_EOL;

		$rules .= PHP_EOL . $mobilequeries;
	}

	$rules .= PHP_EOL;

	// Content Rules Header and Output
	// -------------------------------
	// $rules .= PHP_EOL.'	/* Content Column Width Rules based on '.$thiscontentwidth.'px ('.$thiscontentwidthem.'em) */'.PHP_EOL.PHP_EOL;
	$rules .= $contentrules . PHP_EOL;

	return $rules;

}


// ----------------------------------
// === Media Screen Width Queries ===
// ----------------------------------

// ---------------
// Set Breakpoints
// ---------------
// 2.0.5: check passed querystring only
$breakpoints = '320, 400, 480, 640, 768, 959, 1140, 1200'; // defaults
if ( isset( $_REQUEST['breakpoints'] ) ) {
	$breakpoints = $_REQUEST['breakpoints'];
}

if ( '0' == $breakpoints ) {
	// forced off, no breakpoints
	$numbreakpoints = 0;
} else {
	echo PHP_EOL . "/* Media Width Breakpoints: " . $breakpoints . " */" . PHP_EOL . PHP_EOL;
	if ( !strstr( $breakpoints, ',' ) ) {
		$breakpoints[0] = $breakpoints;
	} else {
		$breakpoints = explode( ',', $breakpoints );
	}

	// 2.0.5: clean and validate each breakpoint is numeric
	foreach ( $breakpoints as $i => $breakpoint ) {
		$breakpoint = abs( intval( trim( $breakpoint ) ) );
		if ( $breakpoint > 1 ) {
			$breakpoints[$i] = $breakpoint;
		} else {
			unset( $breakpoints[$i] );
		}
	}
	if ( is_array( $breakpoints ) ) {
		$numbreakpoints = count( $breakpoints );
	} else {
		$numbreakpoints = 0;
	}
}

// ----------------
// Loop Breakpoints
// ----------------
$i = 1;
if ( $numbreakpoints > 0 ) {
	$mediaqueries = $usedbreakpoint = '';
	foreach ( $breakpoints as $breakpoint ) {

		if ( THEMEDEBUG ) {
			echo '/* Breakpoint ' . $i . ' of ' . $numbreakpoints . ' */' . PHP_EOL;
		}

		// respect maximum width and ignore higher breakpoints
		if ( $breakpoint < $maxwidth ) {

			$usebreakpoint = $breakpoint - 1;
			$usebreakpoint = bioship_round_half_down( $usebreakpoint / $empixels );

			if ( 1 == $i ) {
				$mediaqueries .= '/* ' . $breakpoint . ' and under */' . PHP_EOL;
			} elseif ( $i < $numbreakpoints ) {
				$mediaqueries .= '/* ' . $previousbreakpoint . ' to ' . $breakpoint . ' */' . PHP_EOL;
			} elseif ( $i == $numbreakpoints ) {
				$mediaqueries .= '/* ' . $breakpoint . ' and over */' . PHP_EOL;
			}

			if ( $breakpoint > 320 ) {
				$lastbreakpoint = $previousbreakpoint;
				$lastbreakpoint = bioship_round_half_down( $lastbreakpoint / $empixels );
				if ( $lastbreakpoint == $usedbreakpoint ) {
					$lastbreakpoint = (int) $lastbreakpoint + 0.001;
				}
			}

			if ( $breakpoint < 321 ) {
				// --- generally smallest mobile (default 320) ---
				if ( $usebreakpoint == $usedbreakpoint ) {
					$usebreakpoint = (int) $usebreakpoint + 0.001;
				}
				$mediaqueries .= '@media only screen and (max-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $breakpoint, true, 'zero' );
				$mediaqueries .= '}' . PHP_EOL;
			} elseif ( $breakpoint < 401 ) {
				// --- generally small mobile (default 400) ---
				$mediaqueries .= '@media only screen and (min-width: ' . $lastbreakpoint . 'em) and (max-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $previousbreakpoint, true, 'zero' );
				$mediaqueries .= '}' . PHP_EOL;
			} elseif ( $breakpoint < 481 ) {
				// --- generally standard mobile (default 480) ---
				$mediaqueries .= '@media only screen and (min-width: ' . $lastbreakpoint . 'em) and (max-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $previousbreakpoint, true, 'half' );
				$mediaqueries .= '}' . PHP_EOL;
			} elseif ( $breakpoint < 641 ) {
				// --- generally larger mobile (default 640) ---
				// 2.0.8: add extra mobile handling for content widths
				$mediaqueries .= '@media only screen and (min-width: ' . $lastbreakpoint . 'em) and (max-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $previousbreakpoint, true, 'full' );
				$mediaqueries .= '}' . PHP_EOL;
			} else {
				// --- everything in between ---
				$mediaqueries .= '@media only screen and (min-width: ' . $lastbreakpoint . 'em) and (max-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $previousbreakpoint, false, 'full' );
				$mediaqueries .= '}' . PHP_EOL;
			}
			if ( $i == $numbreakpoints ) {
				// --- largest width (default 1200) ---
				$usebreakpoint = bioship_round_half_down( $breakpoint / $empixels );
				if ( $usebreakpoint == $usedbreakpoint ) {
					$usebreakpoint = (int) $usebreakpoint + 0.001;
				}
				$mediaqueries .= '@media only screen and (min-width: ' . $usebreakpoint . 'em) {' . PHP_EOL;
				$mediaqueries .= bioship_grid_css_rules( $breakpoint, false, 'full' );
				$mediaqueries .= '}' . PHP_EOL;
			}
		}
		$mediaqueries .= PHP_EOL;

		$previousbreakpoint = $breakpoint;
		$usedbreakpoint = $usebreakpoint;
		$i++;
	}
}
echo $mediaqueries . PHP_EOL . PHP_EOL;

// --- output stylesheet generation time ---
$endtime = microtime( true );
$difference = $endtime - $starttime;
echo "/* Generation Time: " . $difference . " */" . PHP_EOL;

// --- maybe output buffered stylesheet length ---
if ( $buffer ) {
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
	echo "/* CSS Output Length: " . esc_html( strlen( $output ) ) . " */";
}

// --- finish script output ---
exit;


// -------------------------
// Grid Breakpoint Reference
// -------------------------

// ref: http://bradfrost.com/blog/post/7-habits-of-highly-effective-media-queries/

// -------------------
// BioShip Breakpoints
// -------------------
// [320, 400, 480, 640, 768, 960, 1040, 1200]

// @media only screen and (max-width: 319px) {}
// @media only screen and (min-width: 320px) and (max-width: 399px) {}
// @media only screen and (min-width: 400px) and (max-width: 479px) {}
// @media only screen and (min-width: 480px) and (max-width: 639px) {}
// @media only screen and (min-width: 640px) and (max-width: 767px) {}
// @media only screen and (min-width: 768px) and (max-width: 959px) {}
// @media only screen and (min-width: 960px) and (max-width: 1139px) {}
// @media only screen and (min-width: 1140px) and (max-width: 1199px) {}
// @media only screen and (min-width: 1200px) {}

// --------------------
// Skeleton Boilerplate
// --------------------
// [400, 550, 750, 1000]
// ref: http://getskeleton.com/

/* Larger than mobile */
// @media (min-width: 400px) {}

/* Larger than phablet */
// @media (min-width: 550px) {}

/* Larger than tablet */
// @media (min-width: 750px) {}

/* Larger than desktop */
// @media (min-width: 1000px) {}

/* Desktop HD */
// @media only screen and (min-width : 1200px) {}

// -----------------
// Twitter Bootstrap
// -----------------
// [320, 480, 758, 992, 1200]
// http://getbootstrap.com/examples/grid/
// https://scotch.io/tutorials/understanding-the-bootstrap-3-grid-system

/* Custom, iPhone Retina */
// @media only screen and (min-width : 320px) {}

/* Extra Small Devices, Phones */
// @media only screen and (min-width : 480px) {}

/* Small Devices, Tablets */
// @media only screen and (min-width : 768px) {}

/* Medium Devices, Desktops (or 979?) */
// @media only screen and (min-width : 992px) {}

/* Large Devices, Wide Screens */
// @media only screen and (min-width : 1200px) {}

// ----------
// Foundation
// ----------
// [various]
// note: taken from Foundation 5
// http://foundation.zurb.com/docs/media-queries.html
// https://scotch.io/tutorials/understanding-zurb-foundation-5s-grid-system

// Small screens
// @media only screen { } /* Define mobile styles */

/* max-width 640px, mobile-only styles, use when QAing mobile issues */
// @media only screen and (max-width: 40em) { }

/* Medium screens min-width 641px */
// @media only screen and (min-width: 40.063em) { }

/* min-width 641px and max-width 1024px, use when QAing tablet-only issues */
// @media only screen and (min-width: 40.063em) and (max-width: 64em) { }

/* Large screens min-width 1025px */
// @media only screen and (min-width: 64.063em) { }

/* min-width 1025px and max-width 1440px, use when QAing large screen-only issues */
// @media only screen and (min-width: 64.063em) and (max-width: 90em) { }

/* XLarge screens min-width 1441px */
// @media only screen and (min-width: 90.063em) { }

/* min-width 1441px and max-width 1920px, use when QAing xlarge screen-only issues */
// @media only screen and (min-width: 90.063em) and (max-width: 120em) { }

/* XXLarge screens min-width 1921px */
// @media only screen and (min-width: 120.063em) { }

