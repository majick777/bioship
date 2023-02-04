<?php

// ========================================
// === BioShip Loop Navigation Template ===
// ========================================
// (original template via Hybrid Base Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Loop Nav Template', __FILE__, 'loop' );}

// 2.1.4: deprecate conditionals and just do page navigation action
// (this template is kept to make replaceable in a Child Theme)
// 2.2.0: change from do_action( 'bioship_page_navi' );
bioship_do_action( 'page_navi' );

