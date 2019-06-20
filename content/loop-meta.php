<?php

// ==================================
// === BioShip Loop Meta Template ===
// ==================================
// (original template via Hybrid Base Theme)

if (THEMETRACE) {bioship_trace('T',__('Loop Meta Template','bioship'),__FILE__,'loop');}

// ---- open archive header div ---
// 1.9.8: switch from .loop-meta to .archive-header
bioship_html_comment('.archive-header');
$attributes = hybrid_get_attr('archive-header');
echo "<div ".$attributes.">";


	// --- Loop Title ---

	$looptitle = bioship_get_loop_title();
	if ($looptitle) {
		// 1.9.8: switch from .loop-title to .archive-title
		bioship_html_comment('.archive-title');
		$attributes = hybrid_get_attr('archive-title');
		echo "<h2 ".$attributes.">".esc_attr($looptitle)."</h2>";
		bioship_html_comment('/.archive-title');
	}

	// --- Loop Description ----

	if (!is_paged()) { // Check if we are on page/1

		$description = bioship_get_loop_description();
		if ($description) {
			// 1.9.8: switch from .loop-description to .archive-description
			bioship_html_comment('.archive-description');
			$attributes = hybrid_get_attr('archive-description');
			echo "<div ".$attributes.">".esc_attr($description)."</div>";
			bioship_html_comment('/.archive-description');
		}
	}

// --- close archive header div ---
echo "</div>";
bioship_html_comment('/.archive-header');
