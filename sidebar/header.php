<?php

/* Header Sidebar: Header Widget Area */

if (THEMETRACE) {bioship_trace('T','Header Widget Area Template',__FILE__,'sidebar');}

// 2.0.9: add template name to footer class attribute
$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-header sidebar-'.$template);

bioship_do_action('bioship_before_header_widgets');

	if (is_active_sidebar('header-widget-area')) : ?>

		<?php bioship_html_comment('#sidebar-header'); ?>
		<div <?php hybrid_attr('sidebar', 'header', $args); ?>>

			<?php dynamic_sidebar('header-widget-area'); ?>

		</div><?php bioship_html_comment('/#sidebar-header'); ?>

	<?php endif;

bioship_do_action('bioship_after_header_widgets');

