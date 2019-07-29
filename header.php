<?php

// ====================================
// === BioShip Main Header Template ===
// ====================================

if (THEMETRACE) {bioship_trace('T',__('Header Template','bioship'),__FILE__,'header');}

// 2.1.1: get language attributes once only
$language_attributes = get_language_attributes();

?><!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php echo $language_attributes; ?>><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php echo $language_attributes; ?>><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php echo $language_attributes; ?>><![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php echo $language_attributes; ?>><![endif]-->
<?php /* note: the next line actually means 'not IE5-9' rather than just 'not IE' */ ?>
<!--[if !IE]>--><html <?php echo $language_attributes; ?>> <!--<![endif]-->

<?php bioship_html_comment('head'); ?><head <?php hybrid_attr('head'); ?>>
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head><?php bioship_html_comment('/head'); ?>

<?php bioship_html_comment('body'); ?><body <?php hybrid_attr('body'); ?>><?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to Content', 'bioship'); ?></a>
<?php bioship_html_comment('#bodycontent.inner'); ?><div id="bodycontent" class="inner">

<?php

	if (THEMEDEBUG) {global $vthemehooks; bioship_debug("Layout Positions", $vthemehooks['functions']);}

	// --- Wrap Container ---
	bioship_do_action('bioship_before_container');
	bioship_do_action('bioship_container_open');

	// --- Header ---
	bioship_do_action('bioship_before_header');

		// --- Elementor Header Location Support -
		// 2.1.2: allow possible replacing of bioship_header action
		if (function_exists('elementor_theme_do_location')) {$doneheader = elementor_theme_do_location('header');}
		if (!isset($doneheader) || !$doneheader) {bioship_do_action('bioship_header');}

	bioship_do_action('bioship_after_header');

	// --- Navigation Menu ---
	bioship_do_action('bioship_before_navbar');
	bioship_do_action('bioship_navbar');
	bioship_do_action('bioship_after_navbar');
