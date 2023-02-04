<?php

// =================================
// === BioShip Comments Template ===
// =================================
// (original template via Skeleton Theme)

if ( THEMETRACE ) {bioship_trace( 'T', 'Comments Template', __FILE__, 'comments' );}

if ( post_password_required() ) {return;}

// --- Before Comments ---
bioship_do_action( 'bioship_before_comments' );

bioship_html_comment( '#comments' );
echo '<div id="comments">' . PHP_EOL;

if ( have_comments() ) {

	// --- if there are comments ---
	bioship_html_comment( '.comments-title' );
	echo '<h3 class="comments-title">' . PHP_EOL;
		printf(
			_n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'bioship' ),
			number_format_i18n( get_comments_number() ),
			'<span class="normal">&quot;' . get_the_title() . '&quot;</span>'
		);
	echo '</h3>';
	bioship_html_comment( '/.comments-title' );
	echo PHP_EOL;

	bioship_html_comment( '.commentlist' );
	echo '<ul class="commentlist">' . PHP_EOL;
		// --- List comments via Callback function ---
		wp_list_comments( 'callback=bioship_skeleton_comments' );
	echo '</ul>';
	bioship_html_comment( '/.commentlist' );
	echo PHP_EOL;

	// --- comment navigation ---
	bioship_html_comment( '.comments-nav' );
	echo '<div class="comments-nav navigation nav-below">' . PHP_EOL;
		echo '<div class="alignleft">' . PHP_EOL;
			previous_comments_link();
		echo '</div>' . PHP_EOL;
		echo '<div class="alignright">' . PHP_EOL;
			next_comments_link();
		echo '</div>' . PHP_EOL;
	echo '</div>' . PHP_EOL;

} else {

	// --- if there are no comments ---
	echo '<p class="nocomments">' . PHP_EOL;
		// phpcs:ignore WordPress.Security.OutputNotEscaped,WordPress.Security.OutputNotEscapedShortEcho
		echo bioship_apply_filters( 'skeleton_no_comments_text', '' );
	echo '</p>' . PHP_EOL;

}

// --- Comment Reply Form ---
if ( comments_open() ) {

	// -- Cancel Comment Reply Link ---
	echo '<div class="cancel-comment-reply">' . PHP_EOL;
		echo '<small>';
			cancel_comment_reply_link();
		echo '</small>';
	echo '</div>';

	// --- set Comment Reply arguments ---
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$comment_args = array(
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' => '<p class="comment-form-author">' .
				( $req ? '<span class="required">*</span>' : '' ) .
				'<label for="author">' . esc_html( __('Your Name', 'bioship') ) . '</label><br />' .
				'<input id="author" name="author" type="text" value="' .
				esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email">' .
				( $req ? '<span class="required">*</span>' : '' ) .
				'<label for="email">' . esc_attr( __( 'Your Email', 'bioship' ) ) . '</label><br />' .
				'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url' =>
				// 2.1.1: use esc_url instead of esc_attr for comment author URL
				'<p class="comment-form-url"><label for="url">' . esc_html( __( 'Website', 'bioship' ) ) . ':</label><br />' .
				'<input id="url" name="url" type="text" value="' . esc_url( $commenter['comment_author_url'] ) . '" size="30" /></p>' )
		),
		'comment_field' => '<p class="comment-form-comment">' .
			'<label for="comment"><span class="required">*</span>' . esc_html( __('Comment', 'bioship' ) ) . ':</label><br />' .
			'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'comment_notes_after' => ''
	);

	// --- if registration required and not logged in ---
	if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) {
		$login_url = wp_login_url( get_permalink() );
		echo '<p><a href="' . esc_url( $login_url ) . '">' . esc_html( __( 'You must be logged in to post a comment.', 'bioship' ) )  . '</a></p>' . PHP_EOL;
	} else {
		comment_form( $comment_args );
	}
}

echo '</div>';
bioship_html_comment( '/#comment' );
echo PHP_EOL;

// --- After Comments ---
bioship_do_action( 'bioship_after_comments' );
