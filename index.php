<?php

// ===================================
// === BioShip Main Index Template ===
// ===================================

// BioShip Template Documentation: https://bioship.space/documentation/templates/
// WordPress Template Hierarchy: https://developer.wordpress.org/themes/basics/template-hierarchy/

// You can copy this file (without needing to change it) to an existing file in the WordPress hierarchy,
// and it will auto-load the corresponding header, loop and footer templates for that context (if found.)
// example: copy index.php to page.php to auto-load header-page.php, loop-page.php and footer-page.php

if ( defined( 'THEMETRACE' ) && THEMETRACE ) {
	bioship_trace( 'T', 'Main Index Template', __FILE__, 'loop' );
}

// --------------
// === HEADER ===
// --------------
// header/{context}.php, header-{context}.php, header/header.php, header.php
bioship_get_header( __FILE__ );

// --------------------
// === LEFT SIDEBAR ===
// --------------------
// /sidebar/*.php
bioship_get_sidebar( 'left' );

// ---------------------
// === RIGHT SIDEBAR ===
// ---------------------
// /sidebar/*.php
bioship_get_sidebar( 'right' );

// --------------------
// === CONTENT LOOP ===
// --------------------
// loop/{context}.php, loop-{context}.php, loop/index.php, loop-index.php
bioship_get_loop( __FILE__ );

// --------------
// === FOOTER ===
// --------------
// footer/{context}.php, footer-{context}.php, footer/footer.php, footer.php
bioship_get_footer( __FILE__ );

