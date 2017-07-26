<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">

	<?php $vregisterimageurl = apply_filters('register_form_image','');
		if ($vregisterimageurl != '') { ?>
		<img src='<?php echo $vregisterimageurl; ?>' border='0'><br><br>
	<?php } ?>

	<?php $template->the_action_template_message( 'register' ); ?>
	<?php $template->the_errors(); ?>
	<form name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register' ); ?>" method="post">
		<p>
			<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'bioship' ); ?></label>
			<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" />
		</p>

		<p>
			<label for="user_email<?php $template->the_instance(); ?>"><?php _e( 'E-mail', 'bioship' ); ?></label>
			<input type="text" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" />
		</p>

		<?php do_action( 'register_form' ); ?>

		<p id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you.', 'bioship' ) ); ?></p>

		<p class="submit">

			<?php $registerbuttonurl = apply_filters('register_button_url', get_option('registerbuttonurl'));
			$registerbuttontext = esc_attr(apply_filters('register_button_text', __('Register','bioship')));
			if ($registerbuttonurl != '') { ?>
				<input type="image" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" src="<?php echo $registerbuttonurl; ?>" />
			<?php }	else { ?>
				<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php echo $registerbuttontext; ?>" />
			<?php } ?>

			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="action" value="register" />
		</p>
	</form>
	<?php $template->the_action_links( array( 'register' => false ) ); ?>
</div>
