<?php

/* ----------------------------------- */
/* Footer Sidebar: Footer Widget Areas */
/* ----------------------------------- */

if ( THEMETRACE ) {bioship_trace( 'T', 'Footer Widget Area Template', __FILE__, 'sidebar' );}

// --- count the widget areas to determine column sizes ---
$footerwidgets = bioship_count_footer_widgets();

// --- set footer widget classes ---
$first = false;
$last = '';
$footercount = 0;
// 2.2.0: add footerwidgetarea class to each area
$footergrid = 'footerwidgetarea';
if ( 1 == $footerwidgets ) {
	$footergrid .= ' full-width full_width';
} elseif ( 2 == $footerwidgets ) {
	$footergrid .= ' one-half one_half';
} elseif ( 3 == $footerwidgets ) {
	$footergrid .= ' one-third one_third';
} elseif ( 4 == $footerwidgets ) {
	$footergrid .= ' one-quarter one_quarter';
}

if ( $footerwidgets > 0 ) {

	// --- set sidebar footer attributes ---
	// 2.0.9: added template name to footer class attribute
	$template = str_replace( '.php', '', basename( __FILE__ ) );
	$classes = 'sidebar sidebar-footer';
	if ( 'footer' != $template ) {
		$classes .= ' sidebar-' . $template;
	}
	$args = array( 'class' => $classes );

	// --- open footer div ---
	bioship_html_comment( '#sidebar-footer' );
	echo '<div ' . hybrid_get_attr( 'sidebar', 'footer', $args ) . '>' . PHP_EOL;

		// --- before widgets action hook ---
		bioship_do_action( 'bioship_before_footer_widgets', $template );

			// --- Footer Sidebar 1 ---
			// if ( is_active_sidebar( 'footer-widget-area-1' ) ) {
			if ( $footerwidgets > 0 ) {	
				$footercount++;
				$first = ' first';
				$last = ( $footercount == $footerwidgets ) ? ' last' : '';
				$footerclasses = $footergrid . $first . $last;
				echo '<div id="footerwidgetarea1" class="' . esc_attr( $footerclasses ) . '">' . PHP_EOL;
					dynamic_sidebar( 'footer-widget-area-1' );
				echo '</div>' . PHP_EOL;
			}

			// --- Footer Sidebar 2 ---
			// 2.2.0: check actual footer widget settings count
			// if ( is_active_sidebar( 'footer-widget-area-2' ) ) {
			if ( $footerwidgets > 1 ) {
				$footercount++;
				$first = ( false === $first ) ? ' first' : $first = '';
				$last = ( $footercount == $footerwidgets ) ? ' last' : '';
				$footerclasses = $footergrid . $first . $last;
				echo '<div id="footerwidgetarea2" class="' . esc_attr( $footerclasses ) . '">' . PHP_EOL;
					dynamic_sidebar( 'footer-widget-area-2' );
				echo '</div>' . PHP_EOL;
			}

			// --- Footer Sidebar 3 ---
			// 2.2.0: check actual footer widget settings count
			// if ( is_active_sidebar( 'footer-widget-area-3' ) ) {
			if ( $footerwidgets > 2 ) {
				$footercount++;
				$first = ( false === $first ) ? ' first' : $first = '';
				$last = ( $footercount == $footerwidgets ) ? ' last' : '';
				$footerclasses = $footergrid . $first . $last;
				echo '<div id="footerwidgetarea3" class="' . esc_attr( $footerclasses ) . '">' . PHP_EOL;
					dynamic_sidebar( 'footer-widget-area-3' );
				echo '</div>' . PHP_EOL;
			}

			// --- Footer Sidebar 4 ---
			// 2.2.0: check actual footer widget settings count
			// if ( is_active_sidebar( 'footer-widget-area-4' ) ) {
			if ( $footerwidgets > 3 ) {
				$footercount++;
				$first = ( false === $first ) ? ' first' : $first = '';
				$last = ' last';
				$footerclasses = $footergrid . $first . $last;
				echo '<div id="footerwidgetarea4" class="' . esc_attr( $footerclasses ) . '">' . PHP_EOL;
					dynamic_sidebar( 'footer-widget-area-4' );
				echo '</div>' . PHP_EOL;
			}

		// --- after widgets action hook ---
		bioship_do_action( 'bioship_after_footer_widgets', $template );

	echo '</div>'.PHP_EOL;
	bioship_html_comment( '/#sidebar-footer' );

	// --- output clear div ---
	echo '<div class="clear"></div>' . PHP_EOL;

}