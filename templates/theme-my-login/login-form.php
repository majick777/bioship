<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">

	<?php $vloginimageurl = apply_filters('login_form_image', '');
		if ($vloginimageurl != '') { ?>
		<img src='<?php echo $vloginimageurl; ?>' border='0'><br><br>
	<?php } ?>

	<?php $template->the_action_template_message( 'login' ); ?>
	<?php $template->the_errors(); ?>
	<form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
		<p>
			<!-- <label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'bioship' ); ?></label> -->
			<?php $usernamefield = __('Username', 'bioship');
			$tml = get_option('theme_my_login'); $email_login = $tml['email_login'];
			if ($email_login) {$usernamefield = __('Email/Username', 'bioship') ;} ?>
			<input type="text" name="log" id="user_login<?php $template->the_instance(); ?>" class="input" placeholder="<?php echo $usernamefield; ?>" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" />
		</p>
		<p>
			<!-- <label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'bioship' ); ?></label> -->
			<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" class="input" placeholder="<?php _e('Password', 'bioship'); ?>" value="" size="20" />
		</p>

		<?php do_action( 'login_form' ); ?>

		<p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_instance(); ?>" value="forever" />
			<label for="rememberme<?php $template->the_instance(); ?>"><?php esc_attr_e( 'Remember Me', 'bioship' ); ?></label>
		</p>
		<p class="submit">

			<?php $loginbuttonurl = apply_filters('login_button_url', get_option('loginbuttonurl'));
			$loginbuttontext = esc_attr(apply_filters('login_button_text', __('Log In','bioship')));
			if ($loginbuttonurl != '') { ?>
				<input type="image" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" src="<?php echo $loginbuttonurl; ?>" />
			<?php }	else { ?>
				<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php echo $loginbuttontext; ?>" />
			<?php } ?>

			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="action" value="login" />
		</p>
	</form>
	<?php $template->the_action_links( array( 'login' => false ) ); ?>
</div>
