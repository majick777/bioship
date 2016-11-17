<?php

	/* Comments Error (via Hybrid Base) */

	if (THEMETRACE) {skeleton_trace('T',__('Comments Error Template','bioship'),__FILE__);}

?>

<?php if ( pings_open() && !comments_open() ) : ?>

	<p class="comments-closed pings-open">
		<?php
			/* Translators: The two %s are placeholders for HTML. The order can't be changed. */
			printf( __( 'Comments are closed, but %strackbacks%s and pingbacks are open.', 'bioship' ), '<a href="' . esc_url( get_trackback_url() ) . '">', '</a>' );
		?>
	</p><!-- .comments-closed .pings-open -->

<?php elseif ( !comments_open() ) : ?>

	// Prefer to not display comments closed.
	<?php // <p class="comments-closed"> ?>
	<?php // _e( 'Comments are closed.', 'bioship' ); ?>
	<?php // </p><!-- .comments-closed --> ?>

<?php endif; ?>