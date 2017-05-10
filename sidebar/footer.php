<?php

/* Footer Widget Areas */

if (THEMETRACE) {skeleton_trace('T','Footer Widget Area Template',__FILE__);}

// Count the active widgets to determine column sizes
$footerwidgets = skeleton_count_footer_widgets();

$footergrid = "one_fourth"; // Default
if ($footerwidgets == "1") {$footergrid = "full-width";} 		// if only one, full width
elseif ($footerwidgets == "2") {$footergrid = "one_half";} 		// if two, split in half
elseif ($footerwidgets == "3") {$footergrid = "one_third";} 	// if three, divide in thirds
elseif ($footerwidgets == "4") {$footergrid = "one_fourth";} 	// if four, split in quarters

?>

<?php if ($footerwidgets) : ?>

	<?php if (THEMECOMMENTS) {echo '<!-- #sidebar-footer -->';} ?>
	<div <?php hybrid_attr('sidebar','footer'); ?>>

		<?php do_action('bioship_before_footer_widgets'); ?>

			<?php if (is_active_sidebar('footer-widget-area-1')) : ?>
			<div id="footerwidgetarea1" class="<?php echo $footergrid;?>">
				<?php dynamic_sidebar('footer-widget-area-1'); ?>
			</div>
			<?php endif;?>

			<?php if (is_active_sidebar('footer-widget-area-2')) : $last = ($footerwidgets == '2' ? ' last' : false);?>
			<div id="footerwidgetarea2" class="<?php echo $footergrid.$last;?>">
				<?php dynamic_sidebar('footer-widget-area-2'); ?>
			</div>
			<?php endif;?>

			<?php if (is_active_sidebar('footer-widget-area-3')) : $last = ($footerwidgets == '3' ? ' last' : false);?>
			<div id="footerwidgetarea3" class="<?php echo $footergrid.$last;?>">
				<?php dynamic_sidebar('footer-widget-area-3'); ?>
			</div>
			<?php endif;?>

			<?php if (is_active_sidebar('footer-widget-area-4')) : $last = ($footerwidgets == '4' ? ' last' : false);?>
			<div id="footerwidgetarea4" class="<?php echo $footergrid.$last;?>">
				<?php dynamic_sidebar('footer-widget-area-4'); ?>
			</div>
			<?php endif;?>

		<?php do_action('bioship_after_footer_widgets'); ?>

	</div>
	<?php if (THEMECOMMENTS) {echo '<!-- /#sidebar-footer -->';} ?>

	<div class="clear"></div>

<?php endif;?>