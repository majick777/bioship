<?php

/* Footer Sidebar: Footer Widget Areas */

if (THEMETRACE) {bioship_trace('T','Footer Widget Area Template',__FILE__,'sidebar');}

// 2.0.9: add template name to footer class attribute
$template = str_replace('.php', '', basename(__FILE__));
$args = array('class' => 'sidebar sidebar-footer sidebar-'.$template);

// --- count the active widgets to determine column sizes ---
$footerwidgets = bioship_count_footer_widgets();

$first = $last = '';
if ($footerwidgets == "1") {$footergrid = "full-width full_width";} 		// if only one, full width
elseif ($footerwidgets == "2") {$footergrid = "one-half one_half";} 		// if two, split in half
elseif ($footerwidgets == "3") {$footergrid = "one-third one_third";} 		// if three, split in thirds
elseif ($footerwidgets == "4") {$footergrid = "one-quarter one_quarter";}	// if four, split in quarters

?>

<?php if ($footerwidgets > 0) : ?>

	<?php bioship_html_comment('#sidebar-footer'); ?>
	<div <?php hybrid_attr('sidebar', 'footer', $args); ?>>

		<?php bioship_do_action('bioship_before_footer_widgets'); ?>

			<?php // --- Footer Sidebar 1 ---
			if (is_active_sidebar('footer-widget-area-1')) :
				$first = ' first';
				if ($footerwidgets == '1') {$last = ' last';} ?>
				<div id="footerwidgetarea1" class="<?php echo $footergrid.$first.$last; ?>">
					<?php dynamic_sidebar('footer-widget-area-1'); ?>
				</div>
			<?php endif; ?>

			<?php // --- Footer Sidebar 2 ---
			if (is_active_sidebar('footer-widget-area-2')) :
				if ($first == '') {$first = ' first';}
				if ($footerwidgets == '2') {$last = ' last';} ?>
				<div id="footerwidgetarea2" class="<?php echo $footergrid.$first.$last; ?>">
					<?php dynamic_sidebar('footer-widget-area-2'); ?>
				</div>
			<?php endif; ?>

			<?php // --- Footer Sidebar 3 ---
			if (is_active_sidebar('footer-widget-area-3')) :
				if ($first == '') {$first = ' first';}
				if ($footerwidgets == '3') {$last = ' last';} ?>
				<div id="footerwidgetarea3" class="<?php echo $footergrid.$first.$last; ?>">
					<?php dynamic_sidebar('footer-widget-area-3'); ?>
				</div>
			<?php endif; ?>

			<?php // --- Footer Sidebar 4 ---
			if (is_active_sidebar('footer-widget-area-4')) :
				if ($first == '') {$first = ' first';}
				if ($footerwidgets == '4') {$last = ' last';} ?>
				<div id="footerwidgetarea4" class="<?php echo $footergrid.$first.$last; ?>">
					<?php dynamic_sidebar('footer-widget-area-4'); ?>
				</div>
			<?php endif; ?>

		<?php bioship_do_action('bioship_after_footer_widgets'); ?>

	</div><?php bioship_html_comment('/#sidebar-footer'); ?>

	<div class="clear"></div>

<?php endif;?>