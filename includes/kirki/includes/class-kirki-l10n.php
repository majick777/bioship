<?php
/**
 * Internationalization helper.
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

if ( ! class_exists( 'Kirki_l10n' ) ) {

	/**
	 * Handles translations
	 */
	class Kirki_l10n {

		/**
		 * The plugin textdomain
		 *
		 * @access protected
		 * @var string
		 */
		protected $textdomain = 'bioship';

		/**
		 * The class constructor.
		 * Adds actions & filters to handle the rest of the methods.
		 *
		 * @access public
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		}

		/**
		 * Load the plugin textdomain
		 *
		 * @access public
		 */
		public function load_textdomain() {

			if ( null !== $this->get_path() ) {
				load_textdomain( $this->textdomain, $this->get_path() );
			}
			load_plugin_textdomain( $this->textdomain, false, Kirki::$path . '/languages' );

		}

		/**
		 * Gets the path to a translation file.
		 *
		 * @access protected
		 * @return string Absolute path to the translation file.
		 */
		protected function get_path() {
			$path_found = false;
			$found_path = null;
			foreach ( $this->get_paths() as $path ) {
				if ( $path_found ) {
					continue;
				}
				$path = wp_normalize_path( $path );
				if ( file_exists( $path ) ) {
					$path_found = true;
					$found_path = $path;
				}
			}

			return $found_path;

		}

		/**
		 * Returns an array of paths where translation files may be located.
		 *
		 * @access protected
		 * @return array
		 */
		protected function get_paths() {

			return array(
				WP_LANG_DIR . '/' . $this->textdomain . '-' . get_locale() . '.mo',
				Kirki::$path . '/languages/' . $this->textdomain . '-' . get_locale() . '.mo',
			);

		}

		/**
		 * Shortcut method to get the translation strings
		 *
		 * @static
		 * @access public
		 * @param string $config_id The config ID. See Kirki_Config.
		 * @return array
		 */
		public static function get_strings( $config_id = 'global' ) {

			$translation_strings = array(
				'background-color'      => esc_attr__( 'Background Color', 'bioship' ),
				'background-image'      => esc_attr__( 'Background Image', 'bioship' ),
				'no-repeat'             => esc_attr__( 'No Repeat', 'bioship' ),
				'repeat-all'            => esc_attr__( 'Repeat All', 'bioship' ),
				'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'bioship' ),
				'repeat-y'              => esc_attr__( 'Repeat Vertically', 'bioship' ),
				'inherit'               => esc_attr__( 'Inherit', 'bioship' ),
				'background-repeat'     => esc_attr__( 'Background Repeat', 'bioship' ),
				'cover'                 => esc_attr__( 'Cover', 'bioship' ),
				'contain'               => esc_attr__( 'Contain', 'bioship' ),
				'background-size'       => esc_attr__( 'Background Size', 'bioship' ),
				'fixed'                 => esc_attr__( 'Fixed', 'bioship' ),
				'scroll'                => esc_attr__( 'Scroll', 'bioship' ),
				'background-attachment' => esc_attr__( 'Background Attachment', 'bioship' ),
				'left-top'              => esc_attr__( 'Left Top', 'bioship' ),
				'left-center'           => esc_attr__( 'Left Center', 'bioship' ),
				'left-bottom'           => esc_attr__( 'Left Bottom', 'bioship' ),
				'right-top'             => esc_attr__( 'Right Top', 'bioship' ),
				'right-center'          => esc_attr__( 'Right Center', 'bioship' ),
				'right-bottom'          => esc_attr__( 'Right Bottom', 'bioship' ),
				'center-top'            => esc_attr__( 'Center Top', 'bioship' ),
				'center-center'         => esc_attr__( 'Center Center', 'bioship' ),
				'center-bottom'         => esc_attr__( 'Center Bottom', 'bioship' ),
				'background-position'   => esc_attr__( 'Background Position', 'bioship' ),
				'background-opacity'    => esc_attr__( 'Background Opacity', 'bioship' ),
				'on'                    => esc_attr__( 'ON', 'bioship' ),
				'off'                   => esc_attr__( 'OFF', 'bioship' ),
				'all'                   => esc_attr__( 'All', 'bioship' ),
				'cyrillic'              => esc_attr__( 'Cyrillic', 'bioship' ),
				'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'bioship' ),
				'devanagari'            => esc_attr__( 'Devanagari', 'bioship' ),
				'greek'                 => esc_attr__( 'Greek', 'bioship' ),
				'greek-ext'             => esc_attr__( 'Greek Extended', 'bioship' ),
				'khmer'                 => esc_attr__( 'Khmer', 'bioship' ),
				'latin'                 => esc_attr__( 'Latin', 'bioship' ),
				'latin-ext'             => esc_attr__( 'Latin Extended', 'bioship' ),
				'vietnamese'            => esc_attr__( 'Vietnamese', 'bioship' ),
				'hebrew'                => esc_attr__( 'Hebrew', 'bioship' ),
				'arabic'                => esc_attr__( 'Arabic', 'bioship' ),
				'bengali'               => esc_attr__( 'Bengali', 'bioship' ),
				'gujarati'              => esc_attr__( 'Gujarati', 'bioship' ),
				'tamil'                 => esc_attr__( 'Tamil', 'bioship' ),
				'telugu'                => esc_attr__( 'Telugu', 'bioship' ),
				'thai'                  => esc_attr__( 'Thai', 'bioship' ),
				'serif'                 => _x( 'Serif', 'font style', 'bioship' ),
				'sans-serif'            => _x( 'Sans Serif', 'font style', 'bioship' ),
				'monospace'             => _x( 'Monospace', 'font style', 'bioship' ),
				'font-family'           => esc_attr__( 'Font Family', 'bioship' ),
				'font-size'             => esc_attr__( 'Font Size', 'bioship' ),
				'font-weight'           => esc_attr__( 'Font Weight', 'bioship' ),
				'line-height'           => esc_attr__( 'Line Height', 'bioship' ),
				'font-style'            => esc_attr__( 'Font Style', 'bioship' ),
				'letter-spacing'        => esc_attr__( 'Letter Spacing', 'bioship' ),
				'top'                   => esc_attr__( 'Top', 'bioship' ),
				'bottom'                => esc_attr__( 'Bottom', 'bioship' ),
				'left'                  => esc_attr__( 'Left', 'bioship' ),
				'right'                 => esc_attr__( 'Right', 'bioship' ),
				'center'                => esc_attr__( 'Center', 'bioship' ),
				'justify'               => esc_attr__( 'Justify', 'bioship' ),
				'color'                 => esc_attr__( 'Color', 'bioship' ),
				'add-image'             => esc_attr__( 'Add Image', 'bioship' ),
				'change-image'          => esc_attr__( 'Change Image', 'bioship' ),
				'no-image-selected'     => esc_attr__( 'No Image Selected', 'bioship' ),
				'add-file'              => esc_attr__( 'Add File', 'bioship' ),
				'change-file'           => esc_attr__( 'Change File', 'bioship' ),
				'no-file-selected'      => esc_attr__( 'No File Selected', 'bioship' ),
				'remove'                => esc_attr__( 'Remove', 'bioship' ),
				'select-font-family'    => esc_attr__( 'Select a font-family', 'bioship' ),
				'variant'               => esc_attr__( 'Variant', 'bioship' ),
				'subsets'               => esc_attr__( 'Subset', 'bioship' ),
				'size'                  => esc_attr__( 'Size', 'bioship' ),
				'height'                => esc_attr__( 'Height', 'bioship' ),
				'spacing'               => esc_attr__( 'Spacing', 'bioship' ),
				'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'bioship' ),
				'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'bioship' ),
				'light'                 => esc_attr__( 'Light 200', 'bioship' ),
				'light-italic'          => esc_attr__( 'Light 200 Italic', 'bioship' ),
				'book'                  => esc_attr__( 'Book 300', 'bioship' ),
				'book-italic'           => esc_attr__( 'Book 300 Italic', 'bioship' ),
				'regular'               => esc_attr__( 'Normal 400', 'bioship' ),
				'italic'                => esc_attr__( 'Normal 400 Italic', 'bioship' ),
				'medium'                => esc_attr__( 'Medium 500', 'bioship' ),
				'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'bioship' ),
				'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'bioship' ),
				'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'bioship' ),
				'bold'                  => esc_attr__( 'Bold 700', 'bioship' ),
				'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'bioship' ),
				'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'bioship' ),
				'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'bioship' ),
				'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'bioship' ),
				'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'bioship' ),
				'invalid-value'         => esc_attr__( 'Invalid Value', 'bioship' ),
				'add-new'           	=> esc_attr__( 'Add new', 'bioship' ),
				'row'           		=> esc_attr__( 'row', 'bioship' ),
				'limit-rows'            => esc_attr__( 'Limit: %s rows', 'bioship' ),
				'open-section'          => esc_attr__( 'Press return or enter to open this section', 'bioship' ),
				'back'                  => esc_attr__( 'Back', 'bioship' ),
				'reset-with-icon'       => sprintf( esc_attr__( '%s Reset', 'bioship' ), '<span class="dashicons dashicons-image-rotate"></span>' ),
				'text-align'            => esc_attr__( 'Text Align', 'bioship' ),
				'text-transform'        => esc_attr__( 'Text Transform', 'bioship' ),
				'none'                  => esc_attr__( 'None', 'bioship' ),
				'capitalize'            => esc_attr__( 'Capitalize', 'bioship' ),
				'uppercase'             => esc_attr__( 'Uppercase', 'bioship' ),
				'lowercase'             => esc_attr__( 'Lowercase', 'bioship' ),
				'initial'               => esc_attr__( 'Initial', 'bioship' ),
				'select-page'           => esc_attr__( 'Select a Page', 'bioship' ),
				'open-editor'           => esc_attr__( 'Open Editor', 'bioship' ),
				'close-editor'          => esc_attr__( 'Close Editor', 'bioship' ),
				'switch-editor'         => esc_attr__( 'Switch Editor', 'bioship' ),
				'hex-value'             => esc_attr__( 'Hex Value', 'bioship' ),
			);

			// Apply global changes from the kirki/config filter.
			// This is generally to be avoided.
			// It is ONLY provided here for backwards-compatibility reasons.
			// Please use the kirki/{$config_id}/l10n filter instead.
			$config = apply_filters( 'kirki/config', array() );
			if ( isset( $config['i18n'] ) ) {
				$translation_strings = wp_parse_args( $config['i18n'], $translation_strings );
			}

			// Apply l10n changes using the kirki/{$config_id}/l10n filter.
			return apply_filters( 'kirki/' . $config_id . '/l10n', $translation_strings );

		}
	}
}
