<?php

// ---------------------- //
/* BioShip Index Template */
// ---------------------- //

/* http://bioship.space/documentation/templates/ */
/* https://developer.wordpress.org/themes/basics/template-hierarchy/ */

if (THEMETRACE) {skeleton_trace('T',__('Main Index Template','bioship'),__FILE__);}

/* HEADER */
// ------ //
// header/{context}.php, header-{context}.php, header/header.php, header.php
skeleton_get_header(__FILE__);

/* SIDEBAR (left) */
// -------------- //
// /sidebar/*.php
skeleton_get_sidebar('left');

/* SIDEBAR (right) */
// --------------- //
// /sidebar/*.php
skeleton_get_sidebar('right');

/* LOOP */
// ---- //
// loop/{context}.php, loop-{context}.php, loop/index.php, loop-index.php
skeleton_get_loop(__FILE__);
// get_template_part('loop','index');

/* FOOTER */
// ------ //
// footer/{context}.php, footer-{context}.php, footer/footer.php, footer.php
skeleton_get_footer(__FILE__);

?>