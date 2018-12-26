<?php

// ---------------------------
// === BioShip Child Theme ===
// ---------------------------

// Instructions
// ------------

// Use this Child Theme functions.php to safely make modifications to the existing theme.
// This ensures that you do not lose modifications with parent theme updates.

// This file is automatically copied to your Child Theme via the one-click creation.
// For manual Child Theme installation and creation options, see Documentation:
// http://bioship.space/documentation/

// Note: If you have existing widgets and menus in your Parent Theme, they will be copied
// to your Child Theme automatically upon creating it. (If you created your Child Theme
// manually, Parent Theme widgets and menus will be copied to it upon activation.)

// You can then copy any other files from the parent theme directory to the child
// theme directory also that you want to change and customize them there.

// The BioShip file hierarchy ensures if the file exists in the child theme
// that it is used in preference to the parent theme file - whether PHP, CSS, JS etc.
// see http://bioship.space/documentation/ for more detailed file information.


// ========================
// THEME LAYOUT ADJUSTMENTS
// ========================

// Note: As of 1.5.0 the Hook Reference has been moved to hooks.php
// Look at the Hook Reference in Documentation to familiarize yourself with the overall layout.


// Reordering Theme Layout Elements
// ================================
// You may wish to simple change the order in which page elements are displayed.
// Simply add a filter with the desired function name with _position appended.

// Example: change the position of bioship_header_extras from 5 to 8 on bioship_header
# add_filter('bioship_header_extras_position', 'my_header_extras_position');
# function my_header_extras_position() {return 8;}

# OR simply (since bioship_ prefix is automatically checked)
# add_filter('header_extras_position', 'my_header_extras_position');
# function my_header_extras_position() {return 8;}

// You can also be more specific by providing both hook and function if you prefer.
// in this case you would use a filter with the name $hook_$function_position
# add_filter('bioship_header_bioship_header_extras_position', 'my_header_extras_position');
# function my_header_extras_position() {return 8;}

// Removing Layout Elements via Filter
// -----------------------------------
// As an alternative to removing an element (see below) you can do so via filter also.
// Simply return a position of -1 and the element fcuntion will not be added to the hook.

// Example: remove the header_widgets from the header section
# add_filter('header_widgets_position', 'my_element_remover');
# function my_element_remover() {return -1;}
# OR (via anonymous function)
# add_filter('header_extras_position', function(){return -1;});


// Adding Layout Elements
// ======================
// There are many action hooks available, including before and after each element.
// You can insert your own custom elements and code blocks by adding the function
// to the desired action hook using Wordpress add_action function.

// Example: Add a welcome message above the header:
# add_action('bioship_before_header', 'my_welcome_function');
# function my_welcome_function() {echo "Welcome to the site!";}

// To insert an element between existing hooked functions, simple make sure to
// specify the correct priority as the third parameter.

// Example: Add a welcome message within the header (after header open wrapper)
# add_action('bioship_header_open', 'my_welcome_function', 1);
# function my_welcome_function() {echo "Thanks for visiting!";}


// ! IMPORTANT !
// =============

// If moving elements to a different hook (or directly removing them), postpone
// until AFTER they exist as they are created/added by the parent theme skeleton.php
// - whereas this is the Child Theme functions.php which is loaded FIRST so too early.

// Hence the below function action wrapper is needed around these Layout Adjustments.
// This fires on 'init' hook and thus after parent functions.php. Make sure to keep
// your layout adjustments *inside* it or they make a mess or just not do anything.

add_action('init', 'bioship_theme_layout_wrapper');
function bioship_theme_layout_wrapper() {

	// Removing Layout Elements
	// ========================
	// (see above example to remove a layour element via filter)
	// To unhook a function from an action hook, you need to know the priority number,
	// (listed for reference in hooks.php) or use bioship_remove_action (since 2.0.5)

	// Example: Remove the main navigation bar section entirely
	# remove_action('bioship_navbar', 'bioship_main_menu', 5);

	// Example 2: Since v2.0.5 you can use bioship_remove_action without the priority
	// and the (filtered) priority will be calculated for you and the action removed
	# remove_action('bioship_navbar', 'bioship_main_menu');


	// Moving a Layout Element
	// =======================
	// Example: To move HTML Header extra section to after Header.
	# remove_action('bioship_header', 'bioship_header_extras', 8);
	# OR (since v2.0.5 as noted above)
	# bioship_remove_action('bioship_header', 'bioship_header_extras');

	# AND (add function to different hook)
	# add_action('bioship_below_header', 'bioship_header_extras', 5);

} // end Child Theme Layout Wrapper


// =====================
// CHILD THEME FUNCTIONS
// =====================

// Autoload Theme-Named Functions File
// -----------------------------------
// so that instead of lots of open functions.php in your file editor,
// you can see the exact theme names directly. eg. child-theme.php
// 2.0.9: added autoload PHP file of same slug as Child Theme
// 2.1.0: fix to directory separator constant
$vstylesheet = get_stylesheet();
$vchildthemefunctions = dirname(__FILE__).DIRECTORY_SEPARATOR.$vstylesheet.'.php';
if (file_exists($vchildthemefunctions)) {include($vchildthemefunctions);}

// -- For adjusting custom value filters you can see your Child Theme filters.php
// As there are many existing examples available there, you just might find something
// that already does what you want to do. You can copy them here as needed, but remember
// to remove/comment them out from filters.php if using that too or they will conflict.

// -- If you use the same function name as in the parent theme functions.php, the parent
// function will be overridden because child functions.php is loaded first, and the parent
// functions.php only declares those functions if they *do not already exist*.
// This mirrors standard Wordpress Child Theme behaviour and is done for this reason.
// See http://codex.wordpress.org/Child_Themes for a more detailed explanation.

// *IMPORTANT NOTE* For flexibility, corresponding add_action calls for a function within BioShip
// are INSIDE the function_exists check, so if you replace a function be sure to re-add the action!

// Declare any custom functions needed for your Child Theme below..!
// =================================================================



