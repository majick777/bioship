<?php

// ---------------------- //
/* BioShip Index Template */
// ---------------------- //

/* http://bioship.space/documentation/templates/ */
/* https://developer.wordpress.org/themes/basics/template-hierarchy/ */

// You can copy this file (without needing to change it) to an existing file of the WordPress hierarchy
// and it will auto-load the corresponding header, loop and footer templates for that context if found.
// example: copy index.php to page.php to auto-load header-page.php, loop-page.php and footer-page.php

if (THEMETRACE) {bioship_trace('T',__('Main Index Template','bioship'),__FILE__);}

/* HEADER */
// ------ //
// header/{context}.php, header-{context}.php, header/header.php, header.php
bioship_get_header(__FILE__);

/* SIDEBAR (left) */
// -------------- //
// /sidebar/*.php
bioship_get_sidebar('left');

/* SIDEBAR (right) */
// --------------- //
// /sidebar/*.php
bioship_get_sidebar('right');

/* LOOP */
// ---- //
// loop/{context}.php, loop-{context}.php, loop/index.php, loop-index.php
bioship_get_loop(__FILE__);
// get_template_part('loop','index');

/* FOOTER */
// ------ //
// footer/{context}.php, footer-{context}.php, footer/footer.php, footer.php
bioship_get_footer(__FILE__);

?>