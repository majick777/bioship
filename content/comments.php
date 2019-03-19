<?php

// =================================
// === BioShip Comments Template ===
// =================================
// (original template via Skeleton Theme)

if (THEMETRACE) {bioship_trace('T',__('Comments Template','bioship'),__FILE__,'comments');}

if ( post_password_required() ) {return;}

?>

<?php // --- Before Comments ---
bioship_do_action('bioship_before_comments'); ?>

<?php bioship_html_comment('#comment'); ?><div id="comments">

<?php // --- if there are comments ---
if ( have_comments() ) : ?>

	<h3 class="comments-title">
	<?php printf(
		_n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'bioship' ),
		number_format_i18n( get_comments_number() ), '<span class="normal">&quot;'.get_the_title().'&quot;</span>'
	); ?></h3>

	<ul class="commentlist">
		<?php // --- List comments via Callback function ---
		wp_list_comments("callback=bioship_skeleton_comments"); ?>
	</ul>

	<div class="navigation nav-below">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

<?php // --- if there are no comments ---
	else :  ?>

	<p class="nocomments">
		<?php echo bioship_apply_filters('skeleton_no_comments_text', ''); ?>
	</p>

<?php endif; ?>

<?php // --- Comment Reply Form ---
	if ( comments_open() ) : ?>

	<?php // -- Cancel Comment Reply Link --- ?>
	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div>

	<?php // --- set Comment Reply arguments ---
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$comment_args = array(
		'fields' => apply_filters( 'comment_form_default_fields', array(
				'author' => '<p class="comment-form-author">'.
					( $req ? '<span class="required">*</span>' : '' ).
					'<label for="author">'.__('Your Name', 'bioship').'</label><br />' .
					'<input id="author" name="author" type="text" value="' .
					esc_attr($commenter['comment_author']).'" size="30"'.$aria_req.' /></p>',
				'email'  => '<p class="comment-form-email">'.
					( $req ? '<span class="required">*</span>' : '' ).
					'<label for="email">'.__('Your Email', 'bioship').'</label><br />'.
					'<input id="email" name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.$aria_req.' /></p>',
				'url' =>
					'<p class="comment-form-url"><label for="url">'.__('Website', 'bioship').':</label><br />'.
					'<input id="url" name="url" type="text" value="'.esc_attr( $commenter['comment_author_url']).'" size="30" /></p>' )
			),
			'comment_field' => '<p class="comment-form-comment">'.
				'<label for="comment"><span class="required">*</span>'.__('Comment', 'bioship').':</label><br />' .
				'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'comment_notes_after' => ''
		);

	// --- if registration required and not logged in ---
	if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<p><a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('You must be logged in to post a comment.', 'bioship'); ?></a></p>
	<?php else : comment_form($comment_args); ?>
	<?php endif; ?>

<?php endif; ?>

</div><?php bioship_html_comment('/#comment');

// --- After Comments ---
bioship_do_action('bioship_after_comments');
