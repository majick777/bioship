<?php

// -------------------------
// Get Recommended Resources
// -------------------------

// 2.0.1: add wrapper to make pluggable
if (!function_exists('bioship_admin_get_recommended')) {
 function bioship_admin_get_recommended() {

	$rec = false;
	$css = '';

	if ( THEMECHILD ) {
		$themetext = __( 'BioShip Framework', 'bioship' );
	} else {
		$themetext = __( 'BioShip','bioship' );
	}

	// --- check whether to show recommendations ---
	// 2.0.1: add a filter switch for show recommendations
	$showrec = bioship_apply_filters( 'admin_show_recommendations', false );
	if ( !$showrec ) {
		return false;
	}

	// --- check if Beaver Builder is already installed ---
	// 2.2.0: add Beaver Builder recommendation
	if ( !is_plugin_active( 'bb-plugin/fl-builder.php' ) ) {

		// --- find CSS Hero recommendation image ---
		$bb_image = bioship_file_hierarchy( 'url', 'beaverbuilder.png', array( 'images' ) );
		if ( $bb_image ) {

			$bb_link = 'https://bioship.space/recommends/BeaverBuilder/';
			$rec = '<center><div style="font-size:12pt;"><b>' . esc_html( $themetext ) . ' ' . esc_html( __( 'is Beaver Builder Ready!', 'bioship' ) ) . '</b><br><br>';
			$rec .= '<div id="bb-ad"><a href="' . esc_url( $bb_link ) . '" target=_blank>';
			$rec .= '<img src="' . esc_url( $bb_image ) . '" border="0"></a></div><br>';
			$rec .= '&rarr; <a href="' . esc_url( $bb_link ) . '" style="font-size:12pt;" target="_blank">';
			$rec .= esc_html( __( 'Edit Pages with Beaver Builder', 'bioship' ) ) . '</a> &larr;</div></center>';
			$css .= '#bb-ad {width:220px; height:220px;}';

			// --- use CSS Hero background image ---
			$bb_bg = bioship_file_hierarchy( 'url', 'beaverbuilder-hover.jpg', array( 'images' ) );
			if ( $bb_bg ) {
				$css .= '#bb-ad:hover {background-image:url("' . esc_url( $bb_bg ) . '"); background-size: 100% 100%;}';
			}

		}

	}

	// --- check if CSS Hero is already installed ---
	if ( !is_plugin_active('css-hero/css-hero-main.php' ) ) {

		// --- find CSS Hero recommendation image ---
		$csshero_image = bioship_file_hierarchy( 'url', 'csshero.png', array( 'images' ) );
		if ( $csshero_image ) {

			$csshero_link = THEMEHOMEURL . '/recommends/CSSHero/';

			$rec = '<center><div style="font-size:12pt;"><b>' . esc_html( $themetext ) . ' ' . esc_html( __( 'is CSS Hero Ready!', 'bioship' ) ) . '</b><br><br>';
			$rec .= '<div id="csshero-ad"><a href="' . esc_url(  $csshero_link ) . '" target=_blank>';
			$rec .= '<img src="' . esc_url( $csshero_image ) . '" border="0"></a></div><br>';
			$rec .= '&rarr; <a href="' . esc_url(  $csshero_link ) . '" style="font-size:12pt;" target="_blank">';
			$rec .= esc_html( __( 'Edit Styles Live with CSS Hero', 'bioship' ) ) . '</a> &larr;</div></center>';
			$css .= '#csshero-ad {width:220px; height:220px;}';

			// --- use CSS Hero background image ---
			$csshero_bg = bioship_file_hierarchy( 'url', 'csshero-hover.jpg', array( 'images' ) );
			if ( $csshero_bg ) {
				$css .= '#csshero-ad:hover {background-image:url("' . esc_url( $csshero_bg ) . '"); background-size: 100% 100%;}';
			}
		}
	}

	if ( '' != $css ) {
		$rec .= '<style>' . $css . '</style>';
	}

	// --- filter and return ---
	// 2.0.1: add filter override for recommendation
	$rec = bioship_apply_filters( 'admin_page_recommendations', $rec );
	return $rec;

 }
}
