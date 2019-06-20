<?php

// -------------------------
// Get Recommended Resources
// -------------------------

// 2.0.1: add wrapper to make pluggable
if (!function_exists('bioship_admin_get_recommended')) {
 function bioship_admin_get_recommended() {

	$rec = false; $showrec = true;

	// --- check whether to show recommendations ---
	// 2.0.1: add a filter switch for show recommendations
	$showrec = bioship_apply_filters('admin_show_recommendations', $showrec);
	if (!$showrec) {return false;}

	// --- check if CSS Hero is already installed ---
	if (!is_plugin_active('css-hero/css-hero-main.php')) {

		// --- find CSS Hero recommendation image ---
		$csshero = bioship_file_hierarchy('url','csshero.png',array('images'));
		if ($csshero) {
			if (THEMECHILD) {$themetext = __('BioShip Framework','bioship');} else {$themetext = __('BioShip','bioship');}

			$rec = '<center><div style="font-size:12pt;"><b>'.esc_attr($themetext).' '.esc_attr(__('is CSS Hero Ready!','bioship')).'</b><br><br>';
			$rec .= '<div id="csshero-ad"><a href="'.esc_url(THEMEHOMEURL.'/recommends/CSSHero/').'" target=_blank>';
			$rec .= '<img src="'.esc_url($csshero).'" border="0"></a></div><br>';
			$rec .= '&rarr; <a href="'.esc_url(THEMEHOMEURL.'/recommends/CSSHero/').'" style="font-size:12pt;" target=_blank>';
			$rec .= esc_attr(__('Edit Styles Live with CSS Hero','bioship')).'</a> &larr;</div></center>';

			// --- use CSS Hero background image ---
			$cssherobg = bioship_file_hierarchy('url','csshero-hover.jpg',array('images'));
			if ($cssherobg) {
				$rec .= '<style>#csshero-ad {width:220px; height:220px;} ';
				$rec .= '#csshero-ad:hover {background-image:url("'.esc_url($cssherobg).'"); background-size: 100% 100%;}</style>';
			}
		}
	}

	// if (!$rec) {
		// TODO: maybe add a fallback recommendation ?
	// }

	// --- filter and return ---
	// 2.0.1: add filter override for recommendation
	$rec = bioship_apply_filters('admin_page_recommendations', $rec);
	return $rec;

 }
}
