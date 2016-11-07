<!doctype html>

<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>><![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>><![endif]-->
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes();?>><![endif]-->
<?php /* note: next line actually means 'not IE5-9' rather than 'not IE' */ ?>
<!--[if !IE]>--><html <?php language_attributes();?>> <!--<![endif]-->


<head <?php hybrid_attr('head'); ?>>
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php hybrid_attr('body'); ?>>
<div id="bodycontent" class="inner">

<?php

	do_action('skeleton_before_container');
	do_action('skeleton_container_open');

	do_action('skeleton_before_header');
	do_action('skeleton_header');
	do_action('skeleton_after_header');

	do_action('skeleton_before_navbar');
	do_action('skeleton_navbar');
	do_action('skeleton_after_navbar');

?>