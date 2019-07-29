<?php

// ========================================
// ==== BioShip Comments Error Template ===
// ========================================
// (original template via Hybrid Base Theme)

	if (THEMETRACE) {bioship_trace('T',__('Comments Error Template','bioship'),__FILE__,'comments');}

?>

<?php // --- If only pingbacks open ---
if ( pings_open() && !comments_open() ) : ?>
	<?php bioship_html_comment('.comments-closed.pings-open'); ?>
	<p class="comments-closed pings-open">
		<?php
			// 2.0.8: use %1$s and %2$s instead of two %s
			/* Translators: The two %s are placeholders for HTML. The order cannot be changed. */
			printf( esc_attr(__('Comments are closed, but %1$strackbacks%2$s and pingbacks are open.', 'bioship')), '<a href="' . esc_url( get_trackback_url() ) . '">', '</a>' );
		?>
	</p><?php bioship_html_comment('/.comments-closed.pings-open'); ?>

<?php // --- If all comments are closed ---
elseif ( !comments_open() ) : ?>
	<?php bioship_html_comment('.comments-closed.pings-closed'); ?>
	<p class="comments-closed pings-closed"> ?>
		<?php echo esc_attr(__( 'Comments are closed.', 'bioship' )); ?>
	</p><?php bioship_html_comment('/.comments-closed.pings-closed'); ?>
<?php endif; ?>