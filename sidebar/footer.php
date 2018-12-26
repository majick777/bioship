<?php

/* Footer Sidebar: Footer Widget Areas */

if (THEMETRACE) {bioship_trace('T','Footer Widget Area Template',__FILE__);}

// 2.0.9: add template name to footer class attribute
$vtemplate = str_replace('.php', '', basename(__FILE__));
$vargs = array('class' => 'sidebar sidebar-footer sidebar-'.$vtemplate);

// Count the active widgets to determine column sizes
$vfooterwidgets = bioship_count_footer_widgets();

$vfootergrid = "one_fourth"; $vfirst = false; $vlast = false;
if ($vfooterwidgets == "1") {$vfootergrid = "full-width full_width";} 		// if only one, full width
elseif ($vfooterwidgets == "2") {$vfootergrid = "one-half one_half";} 		// if two, split in half
elseif ($vfooterwidgets == "3") {$vfootergrid = "one-third one_third";} 	// if three, split in thirds
elseif ($vfooterwidgets == "4") {$vfootergrid = "one-quarter one_quarter";} // if four, split in quarters

?>

<?php if ($vfooterwidgets) : ?>

	<?php bioship_html_comment('#sidebar-footer'); ?>
	<div <?php hybrid_attr('sidebar', 'footer', $vargs); ?>>

		<?php bioship_do_action('bioship_before_footer_widgets'); ?>

			<?php if (is_active_sidebar('footer-widget-area-1')) :
			$vfirst = ' first';
			if ($vfooterwidgets == '1') {$vlast = ' last';} ?>
			<div id="footerwidgetarea1" class="<?php echo $vfootergrid.$vfirst.$vlast; ?>">
				<?php dynamic_sidebar('footer-widget-area-1'); ?>
			</div>
			<?php endif; ?>

			<?php if (is_active_sidebar('footer-widget-area-2')) :
			if (!$vfirst) {$vfirst = ' first';}
			if ($vfooterwidgets == '2') {$vlast = ' last';} ?>
			<div id="footerwidgetarea2" class="<?php echo $vfootergrid.$vfirst.$vlast;?>">
				<?php dynamic_sidebar('footer-widget-area-2'); ?>
			</div>
			<?php endif; ?>

			<?php if (is_active_sidebar('footer-widget-area-3')) :
			if (!$vfirst) {$vfirst = ' first';}
			if ($vfooterwidgets == '3') {$vlast = ' last';} ?>
			<div id="footerwidgetarea3" class="<?php echo $vfootergrid.$vlast;?>">
				<?php dynamic_sidebar('footer-widget-area-3'); ?>
			</div>
			<?php endif; ?>

			<?php if (is_active_sidebar('footer-widget-area-4')) :
			if (!$vfirst) {$vfirst = ' first';}
			if ($vfooterwidgets == '4') {$vlast = ' last';} ?>
			<div id="footerwidgetarea4" class="<?php echo $vfootergrid.$vlast;?>">
				<?php dynamic_sidebar('footer-widget-area-4'); ?>
			</div>
			<?php endif; ?>

		<?php bioship_do_action('bioship_after_footer_widgets'); ?>

	</div>
	<?php bioship_html_comment('/#sidebar-footer'); ?>

	<div class="clear"></div>

<?php endif;?>