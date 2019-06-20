<?php

// ========================
// === Hybrid 3 Classes ===
// ========================
//
// (required for minimal Hybrid loading)
//
// === Classes ===
// - Media Grabber
// - Media Meta
// - Media Meta Factory


// ---------------------
// === Media Grabber ===
// ---------------------

/**
 * Hybrid Media Grabber - A script for grabbing media related to a post.
 *
 * Hybrid Media Grabber is a script for pulling media either from the post content or attached to the
 * post.  It's an attempt to consolidate the various methods that users have used over the years to
 * embed media into their posts.  This script was written so that theme developers could grab that
 * media and use it in interesting ways within their themes.  For example, a theme could get a video
 * and display it on archive pages alongside the post excerpt or pull it out of the content to display
 * it above the post on single post views.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Wrapper function for the Hybrid_Media_Grabber class.  Returns the HTML output for the found media.
 *
 * @since  1.6.0
 * @access public
 * @param  array
 * @return string
 */
function hybrid_media_grabber( $args = array() ) {

	$media = new Hybrid_Media_Grabber( $args );

	return $media->get_media();
}

/**
 * Grabs media related to the post.
 *
 * @since  1.6.0
 * @access public
 * @return void
 */
class Hybrid_Media_Grabber {

	/**
	 * The HTML version of the media to return.
	 *
	 * @since  1.6.0
	 * @access public
	 * @var    string
	 */
	public $media = '';

	/**
	 * The original media taken from the post content.
	 *
	 * @since  1.6.0
	 * @access public
	 * @var    string
	 */
	public $original_media = '';

	/**
	 * The type of media to get.  Current supported types are 'audio', 'video', and 'gallery'.
	 *
	 * @since  1.6.0
	 * @access public
	 * @var    string
	 */
	public $type = 'video';

	/**
	 * Arguments passed into the class and parsed with the defaults.
	 *
	 * @since  1.6.0
	 * @access public
	 * @var    array
	 */
	public $args = array();

	/**
	 * The content to search for embedded media within.
	 *
	 * @since  1.6.0
	 * @access public
	 * @var    string
	 */
	public $content = '';

	/**
	 * Constructor method.  Sets up the media grabber.
	 *
	 * @since  1.6.0
	 * @access public
	 * @global object $wp_embed
	 * @global int    $content_width
	 * @return void
	 */
	public function __construct( $args = array() ) {
		global $wp_embed, $content_width;

		// Use WP's embed functionality to handle the [embed] shortcode and autoembeds.
		add_filter( 'hybrid_media_grabber_embed_shortcode_media', array( $wp_embed, 'run_shortcode' ) );
		add_filter( 'hybrid_media_grabber_autoembed_media',       array( $wp_embed, 'autoembed'     ) );

		// Don't return a link if embeds don't work. Need media or nothing at all.
		add_filter( 'embed_maybe_make_link', '__return_false' );

		// Set up the default arguments.
		$defaults = array(
			'post_id'     => get_the_ID(),   // post ID (assumes within The Loop by default)
			'type'        => 'video',        // audio|video
			'before'      => '',             // HTML before the output
			'after'       => '',             // HTML after the output
			'split_media' => false,          // Splits the media from the post content
			'width'       => $content_width, // Custom width. Defaults to the theme's content width.
		);

		// Set the object properties.
		$this->args    = apply_filters( 'hybrid_media_grabber_args', wp_parse_args( $args, $defaults ) );
		$this->content = get_post_field( 'post_content', $this->args['post_id'], 'raw' );
		$this->type    = isset( $this->args['type'] ) && in_array( $this->args['type'], array( 'audio', 'video', 'gallery' ) ) ? $this->args['type'] : 'video';

		// Find the media related to the post.
		$this->set_media();
	}

	/**
	 * Destructor method.  Removes filters we needed to add.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function __destruct() {
		remove_filter( 'embed_maybe_make_link', '__return_false' );
		remove_filter( 'the_content', array( $this, 'split_media' ), 5 );
	}


	/**
	 * Basic method for returning the media found.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return string
	 */
	public function get_media() {
		return apply_filters( 'hybrid_media_grabber_media', $this->media, $this );
	}

	/**
	 * Tries several methods to find media related to the post.  Returns the found media.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function set_media() {

		// Get the media if the post type is an attachment.
		if ( 'attachment' === get_post_type( $this->args['post_id'] ) )
			$this->do_attachment_media();

		// Find media in the post content based on WordPress' media-related shortcodes.
		if ( ! $this->media )
			$this->do_shortcode_media();

		// If no media is found and autoembeds are enabled, check for autoembeds.
		if ( ! $this->media && get_option( 'embed_autourls' ) )
			$this->do_autoembed_media();

		// If no media is found, check for media HTML within the post content.
		if ( ! $this->media )
			$this->do_embedded_media();

		// If no media is found, check for media attached to the post.
		if ( ! $this->media )
			$this->do_attached_media();

		// If media is found, let's run a few things.
		if ( $this->media ) {

			// Split the media from the content.
			if ( true === $this->args['split_media'] && !empty( $this->original_media ) )
				add_filter( 'the_content', array( $this, 'split_media' ), 5 );

			// Filter the media dimensions and add the before/after HTML.
			$this->media = $this->args['before'] . $this->filter_dimensions( $this->media ) . $this->args['after'];
		}
	}

	/**
	 * WordPress has a few shortcodes for handling embedding media:  [audio], [video], and [embed].  This
	 * method figures out the shortcode used in the content.  Once it's found, the appropriate method for
	 * the shortcode is executed.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function do_shortcode_media() {

		// Finds matches for shortcodes in the content.
		preg_match_all( '/' . get_shortcode_regex() . '/s', $this->content, $matches, PREG_SET_ORDER );

		// If matches are found, loop through them and check if they match one of WP's media shortcodes.
		if ( ! empty( $matches ) ) {

			foreach ( $matches as $shortcode ) {

				// Call the method related to the specific shortcode found and break out of the loop.
				if ( in_array( $shortcode[2], array( 'playlist', 'embed', $this->type ) ) ) {
					call_user_func( array( $this, "do_{$shortcode[2]}_shortcode_media" ), $shortcode );
					break;
				}

				// Check for Jetpack audio/video shortcodes.
				elseif ( in_array( $shortcode[2], array( 'blip.tv', 'dailymotion', 'flickr', 'ted', 'vimeo', 'vine', 'youtube', 'wpvideo', 'soundcloud', 'bandcamp' ) ) ) {
					$this->do_jetpack_shortcode_media( $shortcode );
					break;
				}
			}
		}
	}

	/**
	 * Handles the output of the WordPress playlist feature.  This searches for the [playlist] shortcode
	 * if it's used in the content.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function do_playlist_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		$this->media = do_shortcode( $this->original_media );
	}

	/**
	 * Handles the HTML when the [embed] shortcode is used.
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  array  $shortcode
	 * @return void
	 */
	public function do_embed_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		$this->media = apply_filters(
			'hybrid_media_grabber_embed_shortcode_media',
			$this->original_media
		);
	}

	/**
	 * Handles the HTML when the [audio] shortcode is used.
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  array  $shortcode
	 * @return void
	 */
	public function do_audio_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		$this->media = do_shortcode( $this->original_media );
	}

	/**
	 * Handles the HTML when the [video] shortcode is used.
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  array  $shortcode
	 * @return void
	 */
	public function do_video_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		// Need to filter dimensions here to overwrite WP's <div> surrounding the [video] shortcode.
		$this->media = do_shortcode( $this->filter_dimensions( $this->original_media ) );
	}

	/**
	 * Handles the output of audio/video shortcodes included with the Jetpack plugin (or Jetpack
	 * Slim) via the Shortcode Embeds feature.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function do_jetpack_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		$this->media = do_shortcode( $this->original_media );
	}

	/**
	 * Handles the HTML when the [gallery] shortcode is used.
	 *
	 * @since  3.0.
	 * @access public
	 * @param  array  $shortcode
	 * @return void
	 */
	public function do_gallery_shortcode_media( $shortcode ) {

		$this->original_media = array_shift( $shortcode );

		$this->media = do_shortcode( $this->original_media );
	}

	/**
	 * Uses WordPress' autoembed feature to automatically to handle media that's just input as a URL.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function do_autoembed_media() {

		preg_match_all( '|^\s*(https?://[^\s"]+)\s*$|im', $this->content, $matches, PREG_SET_ORDER );

		// If URL matches are found, loop through them to see if we can get an embed.
		if ( is_array( $matches ) ) {

			foreach ( $matches as $value ) {

				// Let WP work its magic with the 'autoembed' method.
				$embed = trim( apply_filters( 'hybrid_media_grabber_autoembed_media', $value[0] ) );

				if ( $embed ) {

					// If we're given a shortcode, roll with it.
					if ( preg_match( "/\[{$this->type}\s/", $embed ) ) {

						if ( 'video' === $this->type )
							$embed = $this->filter_dimensions( $embed );

						$embed = do_shortcode( $embed );
					}

					$this->original_media = $value[0];
					$this->media          = $embed;
					break;
				}
			}
		}
	}

	/**
	 * Grabs media embbeded into the content within <iframe>, <object>, <embed>, and other HTML methods for
	 * embedding media.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function do_embedded_media() {

		$embedded_media = get_media_embedded_in_content( $this->content );

		if ( $embedded_media )
			$this->media = $this->original_media = array_shift( $embedded_media );
	}

	/**
	 * Gets media attached to the post.  Then, uses the WordPress [audio] or [video] shortcode to handle
	 * the HTML output of the media.
	 *
	 * @since  1.6.0
	 * @access public
	 * @return void
	 */
	public function do_attached_media() {

		// Gets media attached to the post by mime type.
		$attached_media = get_attached_media( $this->type, $this->args['post_id'] );

		// If media is found.
		if ( $attached_media ) {

			// Get the first attachment/post object found for the post.
			$post = array_shift( $attached_media );

			// Gets the URI for the attachment (the media file).
			$url = esc_url( wp_get_attachment_url( $post->ID ) );

			// Run the media as a shortcode using WordPress' built-in [audio] and [video] shortcodes.
			$this->media = do_shortcode( "[{$this->type} src='{$url}']" );
		}
	}

	/**
	 * If the post type itself is an attachment, run the shortcode for the media type.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function do_attachment_media() {

		// Gets the URI for the attachment (the media file).
		$url = esc_url( wp_get_attachment_url( $this->args['post_id'] ) );

		// Run the media as a shortcode using WordPress' built-in [audio] and [video] shortcodes.
		$this->media = do_shortcode( "[{$this->type} src='{$url}']" );
	}

	/**
	 * Removes the found media from the content.  The purpose of this is so that themes can retrieve the
	 * media from the content and display it elsewhere on the page based on its design.
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  string  $content
	 * @return string
	 */
	public function split_media( $content ) {

		return get_the_ID() === $this->args['post_id'] ? str_replace( $this->original_media, '', $content ) : $content;
	}

	/**
	 * Method for filtering the media's 'width' and 'height' attributes so that the theme can handle the
	 * dimensions how it sees fit.
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  string  $html
	 * @return string
	 */
	public function filter_dimensions( $html ) {

		$media_atts = array();
		$_html      = strip_tags( $html, '<object><embed><iframe><video>' );

		// Find the attributes of the media.
		$atts = wp_kses_hair( $_html, array( 'http', 'https' ) );

		// Loop through the media attributes and add them in key/value pairs.
		foreach ( $atts as $att )
			$media_atts[ $att['name'] ] = $att['value'];

		// If no dimensions are found, just return the HTML.
		if ( empty( $media_atts ) || ! isset( $media_atts['width'] ) || ! isset( $media_atts['height'] ) )
			return $html;

		// Set the max width.
		$max_width = $this->args['width'];

		// Set the max height based on the max width and original width/height ratio.
		$max_height = round( $max_width / ( $media_atts['width'] / $media_atts['height'] ) );

		// Fix for Spotify embeds.
		if ( ! empty( $media_atts['src'] ) && preg_match( '#https?://(embed)\.spotify\.com/.*#i', $media_atts['src'], $matches ) )
			list( $max_width, $max_height ) = $this->spotify_dimensions( $media_atts );

		// Calculate new media dimensions.
		$dimensions = wp_expand_dimensions(
			$media_atts['width'],
			$media_atts['height'],
			$max_width,
			$max_height
		);

		// Allow devs to filter the final width and height of the media.
		list( $width, $height ) = apply_filters(
			'hybrid_media_grabber_dimensions',
			$dimensions,                       // width/height array
			$media_atts,                       // media HTML attributes
			$this                              // media grabber object
		);

		// Set up the patterns for the 'width' and 'height' attributes.
		$patterns = array(
			'/(width=[\'"]).+?([\'"])/i',
			'/(height=[\'"]).+?([\'"])/i',
			'/(<div.+?style=[\'"].*?width:.+?).+?(px;.+?[\'"].*?>)/i',
			'/(<div.+?style=[\'"].*?height:.+?).+?(px;.+?[\'"].*?>)/i'
		);

		// Set up the replacements for the 'width' and 'height' attributes.
		$replacements = array(
			'${1}' . $width . '${2}',
			'${1}' . $height . '${2}',
			'${1}' . $width . '${2}',
			'${1}' . $height . '${2}',
		);

		// Filter the dimensions and return the media HTML.
		return preg_replace( $patterns, $replacements, $html );
	}

	/**
	 * Fix for Spotify embeds because they're the only embeddable service that doesn't work that well
	 * with custom-sized embeds.  So, we need to adjust this the best we can.  Right now, the only
	 * embed size that works for full-width embeds is the "compact" player (height of 80).
	 *
	 * @since  1.6.0
	 * @access public
	 * @param  array   $media_atts
	 * @return array
	 */
	public function spotify_dimensions( $media_atts ) {

		$max_width  = $media_atts['width'];
		$max_height = $media_atts['height'];

		if ( 80 == $media_atts['height'] )
			$max_width  = $this->args['width'];

		return array( $max_width, $max_height );
	}
}


// ------------------
// === Media Meta ===
// ------------------

/**
 * Media metadata class. This class is for getting and formatting attachment media file metadata. This
 * is for metadata about the actual file and not necessarily any post metadata.  Currently, only
 * image, audio, and video files are handled.
 *
 * Theme authors need not access this class directly.  Instead, utilize the template tags in the
 * `/inc/template-media.php` file.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Gets attachment media file metadata.  Each piece of meta will be escaped and formatted when
 * returned so that theme authors can properly utilize it within their themes.
 *
 * Theme authors shouldn't access this class directly.  Instead, utilize the `hybrid_media_meta()`
 * and `hybrid_get_media_meta()` functions.
 *
 * @since  3.0.0
 * @access public
 */
class Hybrid_Media_Meta {

	/**
	 * Arguments passed in.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    array
	 */
	protected $post_id  = 0;

	/**
	 * Metadata from the wp_get_attachment_metadata() function.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    array
	 */
	protected $meta  = array();

	/**
	 * Type of media for the current attachment.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    string  image|audio|video
	 */
	protected $type = '';

	/**
	 * Allowed media types.
	 *
	 * @since  3.0.0
	 * @access public
	 * @var    array
	 */
	protected $allowed_types = array( 'image', 'audio', 'video' );

	/* ====== Magic Methods ====== */

	/**
	 * Sets up and runs the functionality for getting the attachment meta.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $post_id ) {

		$this->post_id  = $post_id;
		$this->meta     = wp_get_attachment_metadata( $this->post_id );
		$this->type     = hybrid_get_attachment_type( $this->post_id );

		// If we have a type that's in the whitelist, run filters.
		if ( $this->type && in_array( $this->type, $this->allowed_types ) ) {

			// Run common media filters for any media type.
			$this->media_filters();

			// Run type-specific filters.
			call_user_func( array( $this, "{$this->type}_filters" ) );
		}
	}

	/**
	 * Magic method for getting media object properties.  Let's keep from failing if a theme
	 * author attempts to access a property that doesn't exist.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $property
	 * @return mixed
	 */
	public function __get( $property ) {

		return isset( $this->property ) ? $this->property : $this->get( $property );
	}

	/* ====== Protected Methods ====== */

	/**
	 * Function for escaping properties when there is not a specific method for handling them
	 * within the class.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @param  string|int  $value
	 * @param  string      $property
	 * @return string|int
	 */
	protected function escape( $value, $property ) {

		if ( has_filter( "hybrid_media_meta_escape_{$property}" ) )
			return apply_filters( "hybrid_media_meta_escape_{$property}", $value, $this->type );

		return is_numeric( $value ) ? intval( $value ) : esc_html( $value );
	}

	/**
	 * Adds filters for common media meta.
	 *
	 * Properties: file_name, filesize, file_type, mime_type
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function media_filters() {

		add_filter( 'hybrid_media_meta_escape_file_name', array( $this, 'file_name' ), 5 );
		add_filter( 'hybrid_media_meta_escape_filesize',  array( $this, 'file_size' ), 5 );
		add_filter( 'hybrid_media_meta_escape_file_size', array( $this, 'file_size' ), 5 ); // alias for filesize
		add_filter( 'hybrid_media_meta_escape_file_type', array( $this, 'file_type' ), 5 );
		add_filter( 'hybrid_media_meta_escape_mime_type', array( $this, 'mime_type' ), 5 );
	}

	/**
	 * Adds filters for image meta.
	 *
	 * Properties: aperture, camera, caption, copyright, credit, created_timestamp, dimensions,
	 *             focal_length, iso, shutter_speed
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function image_filters() {

		add_filter( 'hybrid_media_meta_escape_dimensions',        array( $this, 'dimensions'        ), 5 );
		add_filter( 'hybrid_media_meta_escape_created_timestamp', array( $this, 'created_timestamp' ), 5 );
		add_filter( 'hybrid_media_meta_escape_aperture',          array( $this, 'aperture'          ), 5 );
		add_filter( 'hybrid_media_meta_escape_shutter_speed',     array( $this, 'shutter_speed'     ), 5 );
		add_filter( 'hybrid_media_meta_escape_focal_length',      'absint',                            5 );
		add_filter( 'hybrid_media_meta_escape_iso',               'absint',                            5 );
	}

	/**
	 * Adds filters for audio meta.
	 *
	 * Properties: album, artist, composer, genre, length_formatted, lyrics, track_number, year
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function audio_filters() {

		add_filter( 'hybrid_media_meta_escape_track_number', 'absint', 5 );
		add_filter( 'hybrid_media_meta_escape_year',         'absint', 5 );

		// Filters for the audio transcript.
		add_filter( 'hybrid_media_meta_escape_lyrics', array( $this, 'lyrics' ), 5 );
		add_filter( 'hybrid_media_meta_escape_lyrics', 'wptexturize',            10 );
		add_filter( 'hybrid_media_meta_escape_lyrics', 'convert_chars',          15 );
		add_filter( 'hybrid_media_meta_escape_lyrics', 'wpautop',                20 );
	}

	/**
	 * Adds filters for video meta.
	 *
	 * Properties: dimensions, length-formatted
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function video_filters() {

		add_filter( 'hybrid_media_meta_escape_dimensions', array( $this, 'dimensions' ), 5 );
	}

	/* ====== Public Methods ====== */

	/**
	 * Method for grabbing meta formatted metadata by key.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $property
	 * @return mixed
	 */
	public function get( $property ) {

		$value = null;

		// If the property exists in the meta array.
		if ( isset( $this->meta[ $property ] ) )
			$value = $this->meta[ $property ];

		// If the property exists in the image meta array.
		elseif ( 'image' === $this->type && isset( $this->meta['image_meta'][ $property ] ) )
			$value = $this->meta['image_meta'][ $property ];

		// If the property exists in the video's audio meta array.
		elseif ( 'video' === $this->type && isset( $this->meta['audio'][ $property ] ) )
			$value = $this->meta['audio'][ $property ];

		// Escape and return.
		return $this->escape( $value, $property );
	}

	/**
	 * Image/Video meta. Media width + height dimensions.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $dimensions
	 * @return string
	 */
	public function dimensions( $dimensions ) {

		// If there's a width and height.
		if ( ! empty( $this->meta['width'] ) && ! empty( $this->meta['height'] ) ) {

			$dimensions = sprintf(
				// Translators: Media dimensions - 1 is width and 2 is height.
				esc_html__( '%1$s &#215; %2$s', 'hybrid-core' ),
				number_format_i18n( absint( $this->meta['width'] ) ),
				number_format_i18n( absint( $this->meta['height'] ) )
			);
		}

		return $dimensions;
	}

	/**
	 * Image meta.  Date the image was created.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $timestamp
	 * @return string
	 */
	public function created_timestamp( $timestamp ) {

		if ( ! empty( $this->meta['image_meta']['created_timestamp'] ) ) {

			$timestamp = date_i18n(
				get_option( 'date_format' ),
				strip_tags( $this->meta['image_meta']['created_timestamp'] )
			);
		}

		return $timestamp;
	}

	/**
	 * Image meta.  Camera aperture in the form of `f/{$aperture}`.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $aperture
	 * @return string
	 */
	public function aperture( $aperture ) {

		if ( !empty( $this->meta['image_meta']['aperture'] ) )
			$aperture = sprintf( '<sup>f</sup>&#8260;<sub>%s</sub>', absint( $this->meta['image_meta']['aperture'] ) );

		return $aperture;
	}

	/**
	 * Image meta. Camera shutter speed in seconds (i18n number format).
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $shutter
	 * @return string
	 */
	public function shutter_speed( $shutter ) {

		// If a shutter speed is given, format the float into a fraction.
		if ( ! empty( $this->meta['image_meta']['shutter_speed'] ) ) {

			$shutter = $speed = floatval( strip_tags( $this->meta['image_meta']['shutter_speed'] ) );

			if ( ( 1 / $speed ) > 1 ) {
				$shutter = sprintf( '<sup>%s</sup>&#8260;', number_format_i18n( 1 ) );

				if ( number_format( ( 1 / $speed ), 1 ) ==  number_format( ( 1 / $speed ), 0 ) )
					$shutter .= sprintf( '<sub>%s</sub>', number_format_i18n( ( 1 / $speed ), 0, '.', '' ) );

				else
					$shutter .= sprintf( '<sub>%s</sub>', number_format_i18n( ( 1 / $speed ), 1, '.', '' ) );
			}
		}

		return $shutter;
	}

	/**
	 * Audio meta. Lyrics/transcript for an audio file.
	 *
	 * @since  3.0.0
	 * @access public
	 * @return void
	 */
	public function lyrics( $lyrics ) {

		// Look for the 'unsynchronised_lyric' tag.
		if ( isset( $this->meta['unsynchronised_lyric'] ) )
			$lyrics = $this->meta['unsynchronised_lyric'];

		// Seen this misspelling of the id3 tag.
		elseif ( isset( $this->meta['unsychronised_lyric'] ) )
			$lyrics = $this->meta['unsychronised_lyric'];

		return strip_tags( $lyrics );
	}

	/**
	 * Name of the file linked to the permalink for the file.
	 *
	 * @since  3.0.0
	 * @access public
	 * @return string
	 */
	public function file_name() {

		return sprintf(
			'<a href="%s">%s</a>',
			esc_url( wp_get_attachment_url( $this->post_id ) ),
			basename( get_attached_file( $this->post_id ) )
		);
	}

	/**
	 * Audio/Video meta. Size of the file.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  int    $file_size
	 * @return int
	 */
	public function file_size( $file_size ) {

		return ! empty( $this->meta['filesize'] ) ? size_format( strip_tags( $this->meta['filesize'] ), 2 ) : $file_size;
	}

	/**
	 * Type of file.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $file_type
	 * @return string
	 */
	public function file_type( $file_type ) {

		if ( preg_match( '/^.*?\.(\w+)$/', get_attached_file( $this->post_id ), $matches ) )
			$file_type = esc_html( strtoupper( $matches[1] ) );

		return $file_type;
	}

	/**
	 * Mime type for the file.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  string  $mime_type
	 * @return string
	 */
	public function mime_type( $mime_type ) {

		$mime_type = get_post_mime_type( $this->post_id );

		if ( empty( $mime_type ) && ! empty( $this->meta['mime_type'] ) )
			$mime_type = $this->meta['mime_type'];

		return esc_html( $mime_type );
	}
}


// --------------------------------
// === Media Meta Factory Class ===
// --------------------------------

/**
 * Media metadata factory class. This is a singleton factory class for creating and storing
 * `Hybrid_Media_Meta` objects.
 *
 * Theme authors need not access this class directly.  Instead, utilize the template tags in the
 * `/inc/template-media.php` file.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Singleton factory class that registers and instantiates `Hybrid_Media_Meta` classes. Use the
 * `hybrid_media_factory()` function to get the instance.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
class Hybrid_Media_Meta_Factory {

	/**
	 * Array of media meta objects created via `Hybrid_Media_Meta`.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @var    array
	 */
	protected $media = array();

	/**
	 * Creates a new `Hybrid_Media_Meta` object and stores it in the `$media` array by
	 * post ID.
	 *
	 * @see    Hybrid_Media_Meta::__construct()
	 * @since  3.0.0
	 * @access protected
	 * @param  int       $post_id
	 */
	protected function create_media_meta( $post_id ) {

		$this->media[ $post_id ] = new Hybrid_Media_Meta( $post_id );
	}

	/**
	 * Gets a specific `Hybrid_Media_Meta` object by post (attachment) ID.
	 *
	 * @since  3.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return object
	 */
	public function get_media_meta( $post_id ) {

		// If the media meta object doesn't exist, create it.
		if ( ! isset( $this->media[ $post_id ] ) )
			$this->create_media_meta( $post_id );

		return $this->media[ $post_id ];
	}

	/**
	 * Returns the instance of the `Hybrid_Media_Meta_Factory`.
	 *
	 * @since  3.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new Hybrid_Media_Meta_Factory;

		return $instance;
	}
}
