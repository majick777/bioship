<?php

function bioship_get_recommended() {

	$vr = false; $vbaseurl = BIOSHIPHOME;

	// check if CSS Hero is already installed...
	if (!is_plugin_active('css-hero/css-hero-main.php')) {

		$vcsshero = skeleton_file_hierarchy('url','csshero.png',array('images'));
		if ($vcsshero) {
			if (THEMECHILD) {$vthemetext = __('This Framework','bioship');} else {$vthemetext = __('BioShip','bioship');}

			$vr = '<center><div style="font-size:12pt;"><b>'.$vthemetext.' is CSS Hero Ready!</b><br><br>';
			$vr .= '<div id="csshero-ad"><a href="'.$vbaseurl.'/recommends/CSSHero/" target=_blank>';
			$vr .= '<img src="'.$vcsshero.'" border="0"></a></div><br>';
			$vr .= '&rarr; <a href="'.$vbaseurl.'/recommends/CSSHero/" style="font-size:12pt;" target=_blank>';
			$vr .= 'Edit Styles Live with CSS Hero</a> &larr;</div></center>';

			$vcssherobg = skeleton_file_hierarchy('url','csshero-hover.jpg',array('images'));
			if ($vcssherobg) {
				$vr .= '<style>#csshero-ad {width:220px; height:220px;} ';
				$vr .= '#csshero-ad:hover {background-image:url("'.$vcssherobg.'"); background-size: 100% 100%;} </style>';
			}
		}
	} else {
		// TODO: add a fallback recommendation?



	}

	return $vr;
}

?>