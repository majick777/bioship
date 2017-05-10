<?php

// Get Recommended
// ---------------

// 2.0.1: add wrapper to make pluggable
if (!function_exists('bioship_admin_get_recommended')) {
 function bioship_admin_get_recommended() {

	$vrec = false; $vshowrec = true;
	// 2.0.1: add a filter switch for show recommendations
	// TODO: add to filters.php examples
	$vshowrec = apply_filters('bioship_admin_show_recommendations', $vshowrec);
	if (!$vshowrec) {return false;}

	// check if CSS Hero is already installed...
	if (!is_plugin_active('css-hero/css-hero-main.php')) {

		$vcsshero = skeleton_file_hierarchy('url','csshero.png',array('images'));
		if ($vcsshero) {
			if (THEMECHILD) {$vthemetext = __('BioShip Framework','bioship');} else {$vthemetext = __('BioShip','bioship');}

			$vrec = '<center><div style="font-size:12pt;"><b>'.$vthemetext.' is CSS Hero Ready!</b><br><br>';
			$vrec .= '<div id="csshero-ad"><a href="'.THEMEHOMEURL.'/recommends/CSSHero/" target=_blank>';
			$vrec .= '<img src="'.$vcsshero.'" border="0"></a></div><br>';
			$vrec .= '&rarr; <a href="'.THEMEHOMEURL.'/recommends/CSSHero/" style="font-size:12pt;" target=_blank>';
			$vrec .= 'Edit Styles Live with CSS Hero</a> &larr;</div></center>';

			$vcssherobg = skeleton_file_hierarchy('url','csshero-hover.jpg',array('images'));
			if ($vcssherobg) {
				$vrec .= '<style>#csshero-ad {width:220px; height:220px;} ';
				$vrec .= '#csshero-ad:hover {background-image:url("'.$vcssherobg.'"); background-size: 100% 100%;}</style>';
			}
		}
	}

	if (!$vrec) {
		// TODO: add a fallback recommendation?

	}

	// 2.0.1: add filter override for recommendation
	// TODO: add to filters.php examples
	$vrec = apply_filters('bioship_admin_page_recommendation', $vrec);
	return $vrec;

 }
}

?>