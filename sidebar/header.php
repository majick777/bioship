<?php

/* Header Sidebar: Header Widget Area */

if (THEMETRACE) {bioship_trace('T','Header Widget Area Template',__FILE__);}

// 2.0.9: add template name to footer class attribute
$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-header sidebar-'.$vtemplate);

bioship_do_action('bioship_before_header_widgets');

	if (is_active_sidebar('header-widget-area')) : ?>

		<?php bioship_html_comment('#sidebar-header'); ?>
		<div <?php hybrid_attr('sidebar', 'header', $vargs); ?>>

			<?php dynamic_sidebar('header-widget-area'); ?>

		</div><?php bioship_html_comment('/#sidebar-header'); ?>

	<?php endif;

bioship_do_action('bioship_after_header_widgets');

