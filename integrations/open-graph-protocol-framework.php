<?php

// =============================
// Open Graph Protocol Framework
// =============================
// ...yah down wid OGP? yeah u know me...
// Ref: http://www.itthinx.com/plugins/open-graph-protocol/

// -------------------------------------
// Set Open Graph Protocol Default Image
// -------------------------------------
// requires Open Graph Protocol plugin to be installed and active
// note: if using Jetpack see filter: jetpack_open_graph_image_default
// 1.5.0: added default image meta
if ( !function_exists( 'bioship_muscle_open_graph_default_image' ) ) {

 // 2.0.5: move filter inside for consistency
 add_filter( 'open_graph_protocol_metas', 'bioship_muscle_open_graph_default_image' );

 function bioship_muscle_open_graph_default_image( $metas ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemename, $vthemesettings, $vthemedirs;

	// --- allow for open graph image override filter ---
	// (see next func for in-built custom field override)
	$image = array();
	if ( isset( $metas['og:image:width'] ) ) {
		$image[0] = $metas['og:image:width'];
	}
	if ( isset( $metas['og:image:height'] ) ) {
		$image[1] = $metas['og:image:height'];
	}
	if ( isset( $metas['og:image'] ) ) {
		$image[2] = $metas['og:image'];
	}
	$image = bioship_apply_filters( 'muscle_open_graph_override_image', $image );

	// --- if we now have an image and it is a different URL ---
	if ( isset( $image[2] ) ) {
		if ( $image[2] != $metas['og:image'] ) {
			// --- allow override to turn this meta off completely ---
			if ( 'off' == $image[2] ) {
				return array();
			}

			// --- if image changed/updated, check for new width and height ---
			if ( isset( $image[0] ) && isset( $image[1] ) ) {
				$metas['og:image:width'] = $image[0];
				$metas['og:image:height'] = $image[1];
				$metas['og:image'] = $image[2];
			} else {
				// --- otherwise, use getimagesize (slower) ---
				// 2.0.9: set missing default value
				// 2.1.1: try filepath conversion in any case as file system is faster
                // 2.2.0: fix to use of undefined url variable
				$filepath = ABSPATH . parse_url( $image[2], PHP_URL_PATH );
				$urltofilepath = false;
				if ( file_exists( $filepath ) ) {
					$urltofilepath = true;
				}

				if ( $urltofilepath || ini_get( 'allow_url_fopen' ) ) {
					if ( $urltofilepath ) {
						$imagesize = getimagesize( $filepath );
					} else {
						$imagesize = getimagesize( $image[2] );
					}
					if ( $imagesize ) {
						$metas['og:image:width'] = $imagesize[0];
						$metas['og:image:height'] = $imagesize[1];
						$metas['og:image'] = $image[2];
					}
				}
			}
		} else {
			// same URL, maybe a change in size though
			// as it is an override, just do that
			$metas['og:image:width'] = $image[0];
			$metas['og:image:height'] = $image[1];
		}
	}

	// --- default (fallback) open graph image option ---
	if ( !isset( $metas['og:image'] ) ) {

		// 1.9.6: removed this code as even 192 does not meet OG minimum of 200
		// maybe pick the largest size if set to precomposed apple touch icons
		// if ($vthemesettings['ogdefaultimage'] == 'appletouchicon') {
		//	$sizes = array('192','180','152','144','120','114','75','72');
		//	$found = false;
		//	foreach ($sizes as $size) {
		//		if (!$found) {
		//			$checkurl = bioship_file_hierarchy('url', 'touch-icon-'.$size.'x'.$size.'-precomposed.png', $vthemedirs['image']);
		//			if ($checkurl) {vurl = $checkurl; $found = true;}
		//		}
		//	}
		// }
		// else {
			// --- set the URL via theme settings suboption ---
			// 1.9.5: fix for uploaded default image
			// 2.0.8: added new open graph image off option
			$key = $vthemesettings['ogdefaultimage'];
			if ( '' == $key ) {
				$key = 'header_logo';
			} elseif ( 'none' == $key ) {
				$url = '';
			} elseif ( 'site_icon' == $key ) {
				$url = get_site_icon_url();
			} else {
				$url = $vthemesettings[$key];
			}
		// }

		// --- allow for default open graph image filter ---
		bioship_debug( "Open Graph Default Image URL", $url );
		$url = bioship_apply_filters( 'muscle_open_graph_default_image_url', $url );
		bioship_debug( "Filtered OpenGraph URL", $url );

		if ( '' != $url ) {
			// best to cache image size like in skin.php header logo for getimagesize
			// ...but again need to check for allow_url_fopen to do that

			// --- try to convert URL to filepath ---
			// 2.1.1: try filepath conversion in any case as file system is faster
			$filepath = ABSPATH . parse_url( $url, PHP_URL_PATH );
			$urltofilepath = false;
			if ( file_exists( $filepath ) ) {
				$urltofilepath = true;
			}

			if ( $urltofilepath || ini_get( 'allow_url_fopen' ) ) {
				$imagesize = get_option( $vthemename . '_ogdefaultimage' );
				if ( strstr( $imagesize,':' ) ) {
					$imagesize = explode( ':', $imagesize );
					if ( $imagesize[2] != $url ) {
						if ( $urltofilepath ) {
							$imagesize = getimagesize( $filepath );
						} else {
							$imagesize = getimagesize( $url );
						}
						if ( $imagesize ) {
							$imagedata = $imagesize[0] . ':' . $imagesize[1] . ':' . $url;
							// 2.0.5: remove unnecessary add_option fallback
							update_option( $vthemename . '_ogdefaultimage', $imagedata );
						}
					}
				} else {
					if ( $urltofilepath ) {
						$imagesize = getimagesize($filepath);
					} else {
						$imagesize = getimagesize($url);
					}
					if ( $imagesize ) {
						$imagedata = $imagesize[0] . ':' . $imagesize[1] . ':' . $url;
						// 2.0.5: remove unnecessary add_option fallback
						update_option( $vthemename . '_ogdefaultimage', $imagedata );
					}
				}
				// --- set image meta ---
				if ( $imagesize ) {
					$metas['og:image'] = $url;
					$metas['og:image:width'] = $imagesize[0];
					$metas['og:image:height'] = $imagesize[1];
				}

			} else {
				// no allow_fopen_url and filepath failed :-(
				// rely on a matching explicit width/height set via filter
				$imagesize = bioship_apply_filters( 'muscle_open_graph_default_image_size', array() );
				if ( isset( $imagesize[0] ) && isset( $imagesize[1] ) ) {
					$metas['og:image'] = $url;
					$metas['og:image:width'] = $imagesize[0];
					$metas['og:image:height'] = $imagesize[1];
				}
			}
		}
	}
	// 1.9.6: fix some mismatching WP to FB locales
	// ...there may be a number more of these?
	// http://www.roseindia.net/tutorials/i18n/locales-list.shtml
	// https://www.facebook.com/translations/FacebookLocales.xml
	if ( 'en_AU' == $metas['og:locale'] ) {
		$metas['og:locale'] = 'en_GB';
	} elseif ( 'js' == $metas['og:locale'] ) {
		$metas['og:locale'] = 'ja_JP';
	} elseif ( 'iw_IL' == $metas['og:locale'] ) {
		$metas['og:locale'] = 'he_IL';
	}

	bioship_debug( "Open Graph Meta", $metas );
	return $metas;
 }
}

// --------------------------------------------------
// Add Custom Field Override for the Open Graph image
// --------------------------------------------------
// 1.5.0: added this opengraph override
// requires the Open Graph Protocol plugin to be installed and active
// by default the plugin only sets the featured image if there is one
// this lets you add custom image fields on a post/page screen and have them used:
// opengraphimageurl (required), opengraphimagewidth, opengraphimageheight

if ( !function_exists( 'bioship_muscle_open_graph_override_image_fields' ) ) {

 // 2.0.5: moved inside for consistency
 add_filter( 'muscle_open_graph_override_image', 'bioship_muscle_open_graph_override_image_fields', 0 );

 function bioship_muscle_open_graph_override_image_fields($image) {
  	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// 2.1.2: added check for singular but not-404 page
	if ( is_singular() && !is_404() ) {

		// --- override existing open graph image meta with post custom field meta ---
		// (better to set width and height field values but not totally necessary)
		global $post;
		$postid = $post->ID;
		$ogimage[0] = get_post_meta( $postid, 'opengraphimagewidth', true );
		$ogimage[1] = get_post_meta( $postid, 'opengraphimageheight', true );
		$ogimage[2] = get_post_meta( $postid, 'opengraphimageurl', true );

		// --- allow image removal for this page ---
		// (by setting opengraphimageurl value to 'off'
		if ( 'off' == $ogimage[2] ) {
			return array();
		}

		// --- require the URL to be there ---
		if ( $ogimage[2] ) {
			return $ogimage;
		}

	} // else {
		// TODO: handle archive page overrides via archive CPT ?
	// }

	return $image;
 }
}
