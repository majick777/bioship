<?php
/**
 * Handles the administration functions for the Hybrid Hook plugin, sets up the plugin settings page, and loads
 * any and all files dealing with the admin for the plugin.
 *
 * @package HybridHook
 */

/* Set up the administration functionality. */
add_action( 'admin_menu', 'hybrid_hook_settings_page_init' );

/**
 * Adds an additional menu item under the Themes/Appearance menu.
 * Loads actions specifically for the Hybrid Hook settings page.
 *
 * @since 0.2.0
 * @global $hybrid_hook
 */
function hybrid_hook_settings_page_init() {
	global $hybrid_hook;

	/* Register the theme settings. */
	add_action( 'admin_init', 'hybrid_hook_register_settings' );

	/* Add Hybrid Hook settings page. */
	@$hybrid_hook->settings_page = add_theme_page( __( 'Hybrid Hook', 'bioship' ), __( 'Hybrid Hook', 'bioship' ), 'edit_theme_options', 'hybrid-hook-settings', 'hybrid_hook_settings_page' );

	/* Add media for the settings page. */
	add_action( "load-{$hybrid_hook->settings_page}", 'hybrid_hook_admin_enqueue_style' );
	add_action( "load-{$hybrid_hook->settings_page}", 'hybrid_hook_settings_page_media' );
	add_action( "admin_head-{$hybrid_hook->settings_page}", 'hybrid_hook_settings_page_scripts' );

	/* Load the meta boxes. */
	add_action( "load-{$hybrid_hook->settings_page}", 'hybrid_hook_load_meta_boxes' );

	/* Create a hook for adding meta boxes. */
	add_action( "load-{$hybrid_hook->settings_page}", 'hybrid_hook_add_meta_boxes' );
}

/**
 * Registers the Hybrid Hook settings.
 * @uses register_setting() to add the settings to the database.
 *
 * @since 0.2.0
 */
function hybrid_hook_register_settings() {
	register_setting( 'hybrid_hook_plugin_settings', 'hybrid_hook_settings', 'hybrid_hook_settings_validate' );
}

/**
 * Executes the 'add_meta_boxes' action hook because WordPress doesn't fire this on custom admin pages.
 *
 * @since 0.3.0
 */
function hybrid_hook_add_meta_boxes() {
	global $hybrid_hook;
	$plugin_data = get_plugin_data( HYBRID_HOOK_DIR . 'hybrid-hook.php' );
	do_action( 'add_meta_boxes', $hybrid_hook->settings_page, $plugin_data );
}

/**
 * Loads the plugin settings page meta boxes.
 *
 * @since 0.3.0
 */
function hybrid_hook_load_meta_boxes() {
	require_once( HYBRID_HOOK_DIR . 'meta-boxes.php' );
}

/**
 * Function for validating the settings input from the plugin settings page.
 *
 * @since 0.2.0
 */
function hybrid_hook_settings_validate( $input ) {

	/* Get all available hooks. */
	$hooks = hybrid_hook_get_hooks();

	/* Loop through each of the hooks and validate/sanitize the settings based on the current hook in the loop. */
	foreach ( $hooks as $hook ) {

		/* Kill evil scripts if the user doesn't have the 'unfiltered_html' cap. */
		if ( current_user_can( 'unfiltered_html' ) )
			$settings[$hook] = $input[$hook];
		else
			$settings[$hook] = stripslashes( wp_filter_post_kses( addslashes( $input[$hook] ) ) );

		/* Only allow PHP if the current user has the 'unfiltered_html' cap. */
		if ( current_user_can( 'unfiltered_html' ) && hybrid_hook_allow_php() )
			$settings["{$hook}_php"] = ( isset( $input["{$hook}_php"] ) ? 1 : 0 );
		else
			$settings["{$hook}_php"] = 0;

		/* Make sure the priority is an integer. */
		$settings["{$hook}_priority"] = intval( $input["{$hook}_priority"] );
	}

	/* Return the validated/sanitized settings. */
	return $settings;
}

/**
 * Displays the HTML and meta boxes for the plugin settings page.
 *
 * @since 0.2.0
 */
function hybrid_hook_settings_page() {
	global $hybrid_hook; ?>

	<div class="wrap">

		<?php /* s-c-r-e-e-n_i-c-o-n(-)-; */ ?>

		<h2><?php _e( 'Hybrid Hook Settings', 'bioship' ); ?></h2>

		<?php // if ( 'hybrid' !== get_template() ) echo '<div class="error"><p><strong>' . sprintf( __( "The theme you're currently using is incompatible with this plugin. Hybrid Hook was designed to work with the %s theme.", 'bioship' ), '<a href="http://themehybrid.com/themes/hybrid" title="' . __( 'Hybrid WordPress Theme', 'bioship' ) . '">' . __( 'Hybrid', 'bioship' ) . '</a>' ) . '</strong></p></div>'; ?>

		<?php if ( isset( $_GET['settings-updated'] ) && 'true' == esc_attr( $_GET['settings-updated'] ) ) echo '<div class="updated"><p><strong>' . __( 'Settings saved.', 'bioship' ) . '</strong></p></div>'; ?>

		<div id="poststuff">

			<form method="post" action="options.php">

				<?php settings_fields( 'hybrid_hook_plugin_settings' ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $hybrid_hook->settings_page, 'normal', null ); ?></div>
					<div class="post-box-container column-2 side"><?php do_meta_boxes( $hybrid_hook->settings_page, 'side', null ); ?></div>
				</div>

				<?php submit_button( esc_attr__( 'Update Settings', 'bioship' ) ); ?>

			</form>

		</div><!-- #poststuff -->

	</div><!-- .wrap --><?php
}

/**
 * Loads the admin stylesheet for the plugin settings page.
 *
 * @since 0.3.0
 */
function hybrid_hook_admin_enqueue_style() {
	wp_enqueue_style( 'hybrid-hook-admin', trailingslashit( HYBRID_HOOK_URI ) . 'css/admin.css', false, 0.3, 'screen' );
}

/**
 * Loads needed JavaScript files for handling the meta boxes on the settings page.
 *
 * @since 0.2.0
 */
function hybrid_hook_settings_page_media() {
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
}

/**
 * Loads JavaScript for handling the open/closed state of each meta box.
 *
 * @since 0.2.0
 * @global $hybrid_hook The path of the settings page.
 */
function hybrid_hook_settings_page_scripts() {
	global $hybrid_hook; ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo $hybrid_hook->settings_page; ?>' );
		});
		//]]>
	</script>
<?php }

?>