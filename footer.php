<?php

// ====================================
// === BioShip Main Footer Template ===
// ====================================

if (THEMETRACE) {bioship_trace('T',__('Footer Template','bioship'),__FILE__,'footer');}

	// --- Before Footer ---
	bioship_do_action('bioship_before_footer');

		// --- Footer ---
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