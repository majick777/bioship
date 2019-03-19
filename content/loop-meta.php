<?php

// ==================================
// === BioShip Loop Meta Template ===
// ==================================
// (original template via Hybrid Base Theme)

if (THEMETRACE) {bioship_trace('T',__('Loop Meta Template','bioship'),__FILE__,'loop');}

// ---- open archive header div ---
// 1.9.8: switch from .loop-meta to .archive-header
bioship_html_comment('.archive-header');
echo "<div "; hybrid_attr('archive-header'); echo ">";


	// --- Loop Title ---

	$looptitle = bioship_get_loop_title();
	if ($looptitle) {
		// 1.9.8: switch from .loop-title to .archive-title
		bioship_html_comment('.archive-title');
		echo "<h2 "; hybrid_attr('archive-title'); echo ">".$looptitle."</h2>";
		bioship_html_comment('/.archive-title');
	}

	// --- Loop Description ----

	if (!is_paged()) { // Check if we are on page/1

		$description = bioship_get_loop_description();
		if ($description) {
			// 1.9.8: switch from .loop-description to .archive-description
			bioship_html_comment('.archive-description');
			echo "<div "; hybrid_attr('archive-description'); echo ">".$description."</div>";
			bioship_html_comment('/.archive-description');
		}
	}

// --- close archive header div ---
echo "</div>";
bioship_html_comment('/.archive-header');
