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
// Look at the Hook Reference to familiarize yourself with the overall layout

// ! IMPORTANT !
// If removing or reordering layout elements, these need to be postponed
// until AFTER they exist as they are created by the parent theme functions.php
// and this is the Child Theme functions.php which is loaded FIRST.

// Hence the below function wrapper is needed around Layout Adjustments.
// This fires on init so after parent functions.php. Make sure to keep your
// layout adjustments *inside* it or they make a mess or just not do anything.


add_action('init','bioship_theme_layout_wrapper');
function bioship_theme_layout_wrapper() { // <!-- DO NOT MODIFY THIS LINE -->

	// Adding Layout Elements
	// ======================
	// There are many action hooks available, including before and after each element.
	// You can insert your own custom elements and code blocks by adding the function
	// to the desired action hook using Wordpress add_action function.

	// Example: Add a welcome message above the header:
	# add_action('skeleton_before_header','my_welcome_function',0);
	# function my_welcome_function() {echo "Welcome to the site!";}

	// To insert an element between existing hooked functions, simple make sure to
	// specify the correct priority as the third parameter.

	// Example: Add a welcome message within the header (after header open wrapper)
	# add_action('skeleton_header_open','my_welcome_function',1);
	# function my_welcome_function() {echo "Thanks for visiting!";}




	// Removing Layout Elements
	// ========================
	// To unhook a function from an action hook, you need to know the priority number.
	// These are listed for reference in hooks.php

	// Example: Remove the main navigation bar section entirely
	# remove_action('skeleton_navbar','skeleton_main_menu',5);




	// Reordering Theme Layout Elements
	// ================================
	// You may wish to simple swap the order in which page elements are displayed.

	// Example: To move HTML Header extra section to before Header widget area:
	// remove_action('skeleton_header','skeleten_header_extras',8);
	// add_action('skeleton_header','skeleten_header_extras',5);



// <!-- DO NOT MODIFY NEXT LINE -->
} // end Child Theme Layout Wrapper


// =====================
// CHILD THEME FUNCTIONS
// =====================

// -- For adjusting custom value filters you can see your Child Theme filters.php
// As there are many existing examples available there, you just might find something
// that already does what you want to do. You can copy them here as needed, but remember
// to remove/comment them from filters.php, or rename that file or they will conflict.

// -- If you use the same function name as in the parent theme functions.php, the parent
// function will be overridden because child functions.php is loaded first, and parent
// functions.php only declares those functions if they *do not already exist*. This is
// standard Wordpress Child Theme behaviour and done intentionally for this reason.
// See http://codex.wordpress.org/Child_Themes for a more detailed explanation.

// Declare any custom functions needed for your Child Theme below..!
// =================================================================





?>