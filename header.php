<!doctype html>
<?php if (THEMETRACE) {bioship_trace('T',__('Header Template','bioship'),__FILE__);} ?>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>><![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>><![endif]-->
<?php /* note: next line actually means 'not IE5-9' rather than just 'not IE' */ ?>
<!--[if !IE]>--><html <?php language_attributes();?>> <!--<![endif]-->

<head <?php hybrid_attr('head'); ?>>
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php hybrid_attr('body'); ?>>
<div id="bodycontent" class="inner">

<?php

	if (THEMEDEBUG) {
		global $vthemehooks; echo "<!-- Final Layout Positions: ";
		print_r($vthemehooks['functions']); echo " -->";
	}

	bioship_do_action('bioship_before_container');
	bioship_do_action('bioship_container_open');

	bioship_do_action('bioship_before_header');
	bioship_do_action('bioship_header');
	bioship_do_action('bioship_after_header');

	bioship_do_action('bioship_before_navbar');
	bioship_do_action('bioship_navbar');
	bioship_do_action('bioship_after_navbar');

?>