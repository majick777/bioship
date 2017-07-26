<?php

	/* Comments Error Template (via Hybrid Base) */

	if (THEMETRACE) {bioship_trace('T',__('Comments Error Template','bioship'),__FILE__);}

?>

<?php if ( pings_open() && !comments_open() ) : ?>
	<?php bioship_html_comment('.comments-closed.pings-open'); ?>
	<p class="comments-closed pings-open">
		<?php
			// 2.0.8: use %1$s and %2$s instead of two %s
			/* Translators: The two %s are placeholders for HTML. The order cannot be changed. */
			printf( __( 'Comments are closed, but %1$strackbacks%2$s and pingbacks are open.', 'bioship' ), '<a href="' . esc_url( get_trackback_url() ) . '">', '</a>' );
		?>
	</p><?php bioship_html_comment('/.comments-closed.pings-open'); ?>

<?php elseif ( !comments_open() ) : ?>
	<?php bioship_html_comment('.comments-closed.pings-closed'); ?>
	<p class="comments-closed pings-closed"> ?>
		<?php _e( 'Comments are closed.', 'bioship' ); ?>
	</p><?php bioship_html_comment('/.comments-closed.pings-closed'); ?>
<?php endif; ?>