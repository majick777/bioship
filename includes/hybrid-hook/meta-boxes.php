<?php
/**
 * This file holds all the meta boxes and the function to create the meta boxes for the Hybrid Hook settings
 * page in the admin.
 *
 * @package HybridHook
 */

/* Add the meta boxes for the settings page on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'hybrid_hook_create_meta_boxes' );

/**
 * Adds all the meta boxes to the Hybrid Hook settings page in the WP admin.
 *
 * @since 0.3.0
 */
function hybrid_hook_create_meta_boxes() {
	global $hybrid_hook;

	/* Add the 'About' meta box. */
	add_meta_box( 'hybrid-hook-about', __( 'About', 'hybrid-hook' ), 'hybrid_hook_meta_box_display_about', $hybrid_hook->settings_page, 'side', 'default' );

	/* Add the 'Donate' meta box. */
	add_meta_box( 'hybrid-hook-donate', __( 'Like this plugin?', 'hybrid-hook' ), 'hybrid_hook_meta_box_display_donate', $hybrid_hook->settings_page, 'side', 'high' );

	/* Add the 'Support' meta box. */
	// add_meta_box( 'hybrid-hook-support', __( 'Support', 'hybrid-hook' ), 'hybrid_hook_meta_box_display_support', $hybrid_hook->settings_page, 'side', 'low' );

	/* Get all available hooks. */
	$hooks = hybrid_hook_get_hooks();

	/* Loop through the hooks, adding a meta box for each hook. */
	foreach ( $hooks as $hook ) {

		// MOD: added the theme_prefix filter
		$theme_prefix = apply_filters('hybrid_hook_theme_prefix','hybrid');
		$hook_name = "{$theme_prefix}_{$hook}";

		add_meta_box( "hybrid-hook-{$hook}", $hook_name, 'hybrid_hook_meta_box_display_hook_editor', $hybrid_hook->settings_page, 'normal', 'high' );
	}
}

/**
 * Displays the about plugin meta box.
 *
 * @since 0.3.0
 */
function hybrid_hook_meta_box_display_about( $object, $box ) {

	$plugin_data = get_plugin_data( HYBRID_HOOK_DIR . 'hybrid-hook.php' ); ?>

	<p>
		<strong><?php _e( 'Version:', 'hybrid-hook' ); ?></strong> <?php echo $plugin_data['Version']; ?>
	</p>
	<p>
		<strong><?php _e( 'Description:', 'hybrid-hook' ); ?></strong>
	</p>
	<p>
		<?php echo $plugin_data['Description']; ?>
	</p>
<?php }

/**
 * Displays the donation meta box.
 *
 * @since 0.3.0
 */
function hybrid_hook_meta_box_display_donate( $object, $box ) { ?>

	<p><?php _e( "Here's how you can give back:", 'hybrid-hook' ); ?></p>

	<ul>
		<li><a href="http://wordpress.org/extend/plugins/hybrid-hook" title="<?php _e( 'Hybrid Hook on the WordPress plugin repository', 'hybrid-hook' ); ?>"><?php _e( 'Give the plugin a good rating.', 'hybrid-hook' ); ?></a></li>
		<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=3687060" title="<?php _e( 'Donate via PayPal', 'hybrid-hook' ); ?>"><?php _e( 'Donate a few dollars.', 'hybrid-hook' ); ?></a></li>
		<li><a href="http://amzn.com/w/31ZQROTXPR9IS" title="<?php _e( "Justin Tadlock's Amazon Wish List", 'hybrid-hook' ); ?>"><?php _e( 'Get me something from my wish list.', 'hybrid-hook' ); ?></a></li>
	</ul>
<?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.3.0
 */
function hybrid_hook_meta_box_display_support( $object, $box ) { ?>
	<p>
		<?php printf( __( 'Support for this plugin is provided via the support forums at %1$s. If you need any help using it, please ask your support questiosn there.', 'hybrid-hook' ), '<a href="http://themehybrid.com/support" title="' . __( 'Theme Hybrid Support Forums', 'hybrid-hook' ) . '">' . __( 'Theme Hybrid', 'hybrid-hook' ) . '</a>' ); ?>
	</p>
<?php }

/**
 * Displays the meta box for individual hooks.  Each meta box's content is defined by the $box['id'] variable, so
 * we only need a single function to handle the display of all the hook meta boxes.
 *
 * @since 0.3.0
 */
function hybrid_hook_meta_box_display_hook_editor( $object, $box ) {

	$hook = str_replace( 'hybrid-hook-', '', $box['id'] ); ?>

	<div class="hook-editor">
		<p>
			<textarea name="hybrid_hook_settings[<?php echo esc_attr( $hook ); ?>]" id="<?php echo esc_attr( $hook ); ?>_hook_editor" cols="60" rows="5"><?php echo esc_textarea( hybrid_hook_get_setting( $hook ) ); ?></textarea>
			<br />
			<?php
			if ( current_user_can( 'unfiltered_html' ) )
				_e( 'Use this box to add <abbr title="Hypertext Markup Language">HTML</abbr> and/or shortcodes.', 'hybrid-hook' );
			else
				_e( '<abbr title="Hypertext Markup Language">HTML</abbr> entered into this box will be filtered because you do not have the <code>unfiltered_html</code> capability.', 'hybrid-hook' );
			?>
		</p>

		<p class="alignleft">
			<?php $priority = hybrid_hook_get_setting( "{$hook}_priority" ); ?>
			<input type="text" name="hybrid_hook_settings[<?php echo esc_attr( $hook ); ?>_priority]" id="<?php echo esc_attr( $hook ); ?>_priority" value="<?php echo isset( $priority ) ? esc_attr( $priority ) : '10'; ?>" size="3" />
			<label for="<?php echo esc_attr( $hook ); ?>_priority"><?php _e( 'Priority (<code>10</code> is default).', 'hybrid-hook' ); ?></label>
		</p>

		<?php if ( hybrid_hook_allow_php() && current_user_can( 'unfiltered_html' ) ) { ?>
			<p class="alignright">
				<label for="<?php echo esc_attr( $hook ); ?>_php"><?php _e( 'Execute <acronym title="Hypertext Preprocessor">PHP</acronym>?', 'hybrid-hook' ); ?></label>
				<input name="hybrid_hook_settings[<?php echo esc_attr( $hook ); ?>_php]" id="<?php echo esc_attr( $hook ); ?>_php" type="checkbox" value="1" <?php checked( '1', hybrid_hook_get_setting( "{$hook}_php" ) ); ?> />
			</p>
		<?php } ?>
	</div>
<?php
}

?>