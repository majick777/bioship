<?php

/* ----------------------------------- */
/* Footer Sidebar: Footer Widget Areas */
/* ----------------------------------- */

if (THEMETRACE) {bioship_trace('T','Footer Widget Area Template',__FILE__,'sidebar');}

// --- count the active widgets to determine column sizes ---
$footerwidgets = bioship_count_footer_widgets();

// --- set footer widget classes ---
$first = $last = ''; $footercount = 0;
if ($footerwidgets == 1) {$footergrid = "full-width full_width";} 		// if only one, full width
elseif ($footerwidgets == 2) {$footergrid = "one-half one_half";} 		// if two, split in half
elseif ($footerwidgets == 3) {$footergrid = "one-third one_third";} 		// if three, split in thirds
elseif ($footerwidgets == 4) {$footergrid = "one-quarter one_quarter";}	// if four, split in quarters

if ($footerwidgets > 0) {

	// --- set sidebar footer attributes ---
	// 2.0.9: added template name to footer class attribute
	$template = str_replace('.php', '', basename(__FILE__));
	$classes = 'sidebar sidebar-footer';
	if ($template != 'footer') {$classes .= ' sidebar-'.$template;}
	$args = array('class' => $classes);

	// --- open footer div ---
	bioship_html_comment('#sidebar-footer');
	$attributes = hybrid_get_attr('sidebar', 'footer', $args);
	echo '<div '.$attributes.'>'.PHP_EOL;

		// --- before widgets action hook ---
		bioship_do_action('bioship_before_footer_widgets');

			// --- Footer Sidebar 1 ---
			if (is_active_sidebar('footer-widget-area-1')) {
				$footercount++;
				$first = ' first';
				if ($footercount == $footerwidgets) {$last = ' last';}
				$footerclasses = $footergrid.$first.$last;
				echo '<div id="footerwidgetarea1" class="'.esc_attr($footerclasses).'">'.PHP_EOL;
					dynamic_sidebar('footer-widget-area-1');
				echo '</div>'.PHP_EOL;
			}

			// --- Footer Sidebar 2 ---
			if (is_active_sidebar('footer-widget-area-2')) {
				$footercount++;
				if ($first == '') {$first = ' first';}
				if ($footercount == $footerwidgets) {$last = ' last';}
				$footerclasses = $footergrid.$first.$last;
				echo '<div id="footerwidgetarea2" class="'.esc_attr($footerclasses).'">'.PHP_EOL;
					dynamic_sidebar('footer-widget-area-2');
				echo '</div>'.PHP_EOL;
			}

			// --- Footer Sidebar 3 ---
			if (is_active_sidebar('footer-widget-area-3')) {
				$footercount++;
				if ($first == '') {$first = ' first';}
				if ($footercount == $footerwidgets) {$last = ' last';}
				$footerclasses = $footergrid.$first.$last;
				echo '<div id="footerwidgetarea3" class="'.esc_attr($footerclasses).'">'.PHP_EOL;
					dynamic_sidebar('footer-widget-area-3');
				echo '</div>'.PHP_EOL;
			}

			// --- Footer Sidebar 4 ---
			if (is_active_sidebar('footer-widget-area-4')) {
				$footercount++;
				if ($first == '') {$first = ' first';}
				if ($footercount == $footerwidgets) {$last = ' last';}
				$footerclasses = $footergrid.$first.$last;
				echo '<div id="footerwidgetarea4" class="'.esc_attr($footerclasses).'">'.PHP_EOL;
					dynamic_sidebar('footer-widget-area-4');
				echo '</div>'.PHP_EOL;
			}

		// --- after widgets action hook ---
		bioship_do_action('bioship_after_footer_widgets');

	echo '</div>'.PHP_EOL;
	bioship_html_comment('/#sidebar-footer');

	// --- output clear div ---
	echo '<div class="clear"></div>'.PHP_EOL;

}