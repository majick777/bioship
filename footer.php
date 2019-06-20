<?php

// ====================================
// === BioShip Main Footer Template ===
// ====================================

if (THEMETRACE) {bioship_trace('T',__('Footer Template','bioship'),__FILE__,'footer');}

	// --- Before Footer ---
	bioship_do_action('bioship_before_footer');

		// --- Elementor Footer Location Support ---
		// 2.1.2: allow possible replacing of bioship_footer action
		if (function_exists('elementor_theme_do_location')) {$donefooter = elementor_theme_do_location('footer');}
		if (!isset($donefooter) || !$donefooter) {bioship_do_action('bioship_footer');}

		// --- WP Footer ---
		wp_footer();

	// --- After Footer ---
	bioship_do_action('bioship_after_footer');

	// --- Close Container ---
	bioship_do_action('bioship_container_close');
	bioship_do_action('bioship_after_container');

?>

</div><?php bioship_html_comment('/#bodycontent.inner'); ?>
</body><?php bioship_html_comment('/body'); ?>
</html>