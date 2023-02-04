<?php

// ===============
// Theme Switching
// ===============
// TODO: retest this Theme Switching functionality

// for Theme Test Drive and JonRadio Multiple Themes...
// ref: http://wordpress.stackexchange.com/q/227532/76440

// *** IMPORTANT USAGE NOTE *** only works *HERE* for BioShip Parent and Child Theme switching
// if you want the same theme switching functionality to work with other themes as well,
// you will need to simply put a copy of this function in /wp-content/mu-plugins/ folder.
// and that is because THIS file is loaded BY this theme, so therefore the fix will not be
// loaded for other themes - unless it is loaded at an earlier time, ie. mu-plugins or plugins

// note: currently for JonRadio Multiple Themes, select-theme.php is NOT loaded for admin
// (this means the advanced setting 'AJAX All' currently has no effect anyway...)
// loading select-theme.php will automatically set the active theme via cookie storage,
// BUT this will not work for using admin-ajax.php or admin-post.php when visiting multiple
// pages on the same site at once where a different theme may be active for different pages!

// note: if loading via mu-plugins or a plugin, this action hook must change to 'plugins_loaded'
// 2.0.5: disable all this by default until retesting
if ( !function_exists( 'bioship_muscle_theme_switch_admin_fix' ) ) {

 // 2.1.1: move add_action internally for consistency
 add_action( 'init', 'bioship_muscle_theme_switch_admin_fix' );

 function bioship_muscle_theme_switch_admin_fix() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

 	$debug = true; // $debug = false;

	// --- check for a valid active plugin ---
	$activeplugins = maybe_unserialize( get_option( 'active_plugins' ) );
	if ( !is_array( $activeplugins ) ) {
		return;
	}
	$multiplethemes = $themetestdrive = false;
	if ( in_array( 'jonradio-multiple-themes/jonradio-multiple-themes.php', $activeplugins ) ) {
		// 2.1.1: add extra check to ensure plugin is actually loaded
		if ( function_exists( 'jr_mt_template' ) ) {
			$multiplethemes = true;
		}
	}
	if ( in_array( 'theme-test-drive/themedrive.php', $activeplugins ) ) {
		// 2.1.1: add extra check to ensure plugin is actually loaded
		if ( function_exists( 'themedrive_get_template' ) ) {
			$themetestdrive = true;
		}
	}
	 // --- bug out if neither plugin active ---
	if ( !$multiplethemes && !$themetestdrive ) {
		return;
	}

	// --- multiple themes option: 'site', 'sticky' or 'both' ---
	$method = defined( 'MT_METHOD' ) ? MT_METHOD : 'site';
	$parameter = 'theme'; // multiple theme switch querystring parameter name

	// --- user data save settings ---
	$datamethod = 'both'; // how to save user data: 'cookie', 'usermeta' or 'both'
	$datakey = 'theme_switch_data'; // cookie and user meta key name
	$expires = 24 * 60 * 60; // length of time for cookies and transients

	// --- maybe include pluggable.php for accessing user ---
	// 2.1.1: fix to incorrect variable name (userdata)
	if ( ( 'cookie' != $datamethod ) && !function_exists( 'is_user_logged_in' ) ) {
		require ABSPATH . WPINC . '/pluggable.php';
	}

	// --- maybe reset cookie and URL data by user request ---
	if ( isset( $_GET['resetthemes'] ) && ( '1' == $_GET['resetthemes'] ) ) {
		bioship_debug( "THEME SWITCH DATA RESET" );
		// 2.2.0: fix to incorrect variable (themecookie)
		if ( $themetestdrive ) {
			setCookie( $datakey, '', -300 );
		}
		delete_option( 'theme_switch_request_urls' );
		return;
	}

	// --- maybe set debug switch ---
	$debug = false;
	if ( isset( $_GET['debug'] ) && ( '1' == $_GET['debug'] ) ) {
		$debug = true;
	} elseif ( defined( 'THEMEDEBUG' ) ) {
		$debug = THEMEDEBUG;
	}

	// --- improve theme test drive to use options filters like multiple themes ---
	// (theme test drive by default only filters via get_stylesheet and get_template)
	if ( $themetestdrive ) {
		$parameter = 'theme';
		remove_filter( 'template', 'themedrive_get_template' );
		remove_filter( 'stylesheet', 'themedrive_get_stylesheet' );
		add_filter( 'pre_option_stylesheet', 'themedrive_get_stylesheet' );
		add_filter( 'pre_option_template', 'themedrive_get_template' );
	}

	// --- maybe load stored alternative theme for AJAX/admin calls ---
	if ( is_admin() ) {

		// --- let WordPress handle customize previews ---
		if ( is_customize_preview() ) {
			return;
		}

		// --- get pagenow to check for admin-post.php as well ---
		global $pagenow;

		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( 'admin-post.php' == $pagenow ) ) {

			// 2.2.0: added isset check for referer to prevent undefined index warnings
			if ( !isset( $_SERVER['HTTP_REFERER'] ) ) {
				return;
			}
			
			// set the referer path for URL matching
			// TODO: use and explicitly set referral address
			$referer = parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_PATH );
			// 2.1.4: fix for undefined variable warning
			$matchedurlpath = false;

			// set some globals for the AJAX theme options
			global $ajax_stylesheet, $ajax_template;

			// --- check for temporary Theme Test Drive cookie data ---
			if ( $themetestdrive || ( $multiplethemes && ( 'site' != $method ) ) ) {
				if ( 'usermeta' != $datamethod ) {
					if ( isset( $_COOKIE[$datakey]) && ( '' != $_COOKIE[$datakey] ) ) {
						$cookiedata = explode( ',', $_COOKIE[$datakey] );
						// attempt to match referer data with stored transient request
						foreach ( $cookiedata as $transientkey ) {
							$transientdata = get_transient( $transientkey );
							if ( $transientdata ) {
								$data = explode( ':', $transientdata );
								if ( $data[0] == $referer ) {
									$ajax_stylesheet = $data[1];
									$ajax_template = $data[2];
									$transientdebug = $transientdata;
									$matchedurlpath = true;
								}
							}
						}
					}
					if ( ( 'cookie' != $datamethod ) && is_user_logged_in() ) {
						// 2.0.1: allow for fallback for older installs
						$current_user = bioship_get_current_user();
						$usermetadata = get_user_meta( $current_user->ID, $datakey, true );
						if ( is_array( $usermetadata ) ) {
							// --- attempt to match referer data with stored transient request ---
							foreach ( $usermetadata as $transientkey ) {
								$transientdata = get_transient( $transientkey );
								if ( $transientdata ) {
									$data = explode( ':', $transientdata );
									if ( $data[0] == $referer ) {
										$ajax_stylesheet = $data[1];
										$ajax_template = $data[2];
										$transientdebug = $transientdata;
										$matchedurlpath = true;
									}
								}
							}
						}
					}
				}
			} elseif ( $multiplethemes && ( 'sticky' != $method ) ) {
				// --- check the request URL list to handle sitewide cases ---
				if ( !$matchedurlpath ) { // but not if we already have a match
					$requesturls = get_option( 'theme_switch_request_urls' );
					if ( is_array( $requesturls ) ) {
						if ( is_array( $requesturls ) && array_key_exists( $referer, $requesturls ) ) {
							$matchedurlpath = true;
							$ajax_stylesheet = $requesturls[$referer]['stylesheet'];
							$ajax_template = $requesturls[$referer]['template'];
						}
					}
				}
			}

			if ( $matchedurlpath ) {
				// add theme option filters for admin-ajax (and admin-post)
				// so any admin actions defined by the theme are finally loaded!
				add_filter( 'pre_option_stylesheet', 'bioship_muscle_admin_ajax_stylesheet' );
				add_filter( 'pre_option_template', 'bioship_muscle_admin_ajax_template' );

				// 2.1.1: added function_exists wrappers for consistency
				if ( !function_exists( 'bioship_muscle_admin_ajax_stylesheet' ) ) {
				 function bioship_muscle_admin_ajax_stylesheet() {
				 	global $ajax_stylesheet;
				 	return $ajax_stylesheet;
				 }
			  	}
			  	if ( !function_exists( 'bioship_muscle_admin_ajax_template' ) ) {
				 function bioship_muscle_admin_ajax_template() {
				 	global $ajax_template;
				 	return $ajax_template;
				 }
				}
			}

			// --- maybe output debug info for AJAX/admin test frame ---
			if ( $debug ) {
				echo "<!-- COOKIE DATA: " . esc_html( $_COOKIE[$datakey] ) . " -->";
				echo "<!-- TRANSIENT DATA: " . esc_html( $transientdebug ) . " -->";
				echo "<!-- REFERER: " . esc_html( $referer ) . " -->";
				echo "<!-- STORED URLS: " . esc_html( print_r( $requesturls, true ) ) . " -->";
				if ( $matchedurlpath ) {
					echo "<!-- URL MATCH FOUND -->";
				} else {
					echo "<!-- NO URL MATCH FOUND -->";
				}
				echo "<!-- AJAX Stylesheet: " . esc_html( get_option( 'stylesheet' ) ) . " -->";
				echo "<!-- AJAX Template: " . esc_html( get_option( 'template' ) ) . " -->";
			}

			// done for admin requests so bug out here
			return;
		}
	}

	// --- store public request URLs where an alternate theme is active ---
	// (note: multiple themes does not load in admin, but theme test drive does)
	if ( $themetestdrive || ( !is_admin() && $multiplethemes ) ) {

		// --- get current theme (possibly overriden) setting ---
		$themestylesheet = get_option( 'stylesheet' );
		$themetemplate = get_option( 'template' );

		// --- remove filters, get default theme setting, re-add filters ---
		if ( $multiplethemes ) {
			remove_filter( 'pre_option_stylesheet', 'jr_mt_stylesheet' );
			remove_filter( 'pre_option_template', 'jr_mt_template' );
			$stylesheet = get_option( 'stylesheet' );
			$template = get_option( 'template' );
			add_filter( 'pre_option_stylesheet', 'jr_mt_stylesheet' );
			add_filter( 'pre_option_template', 'jr_mt_template' );
		}
		if ( $themetestdrive ) {
			// note: default theme test drive filters are changed earlier on
			remove_filter( 'pre_option_stylesheet', 'themedrive_get_stylesheet' );
			remove_filter( 'pre_option_template', 'themedrive_get_template' );
			$stylesheet = get_stylesheet();
			$template = get_template();
			add_filter( 'pre_option_stylesheet', 'themedrive_get_stylesheet' );
			add_filter( 'pre_option_template', 'themedrive_get_template' );
		}

		// --- set/get request URL values (URL path only) ---
		$requesturl = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$requesturls = get_option( 'theme_switch_request_urls' );

		// --- store the request data ---
		if ( $themetestdrive || ( $multiplethemes && ( 'site' != $method ) ) ) {
			if ( isset( $_REQUEST[$parameter] ) && ( '' != $_REQUEST[$parameter] ) ) {
				if ( 'usermeta' != $datamethod ) {
					 // --- check existing cookie data ---
					 $cookiedata = array();
					 if ( ( isset( $_COOKIE[$datakey] ) ) && ( '' != $_COOKIE[$datakey] ) ) {
						$existingmatch = false;
						$i = 0;
						$cookiedata = explode( ',', $_COOKIE[$datakey] );
						foreach ( $cookiedata as $transientkey ) {
							$transientdata = get_transient( $transientkey );
							if ( $transientdata ) {
								$data = explode(':' , $transientdata);
								if ( $data[0] == $requesturl ) {
									// update existing transient data
									$transientdata = $transientdebug = $requesturl . ':' . $themestylesheet . ':' . $themetemplate;
									set_transient( $transientkey, $transientdata, $expires );
									$existingmatch = true;
								}
							} else {
								// remove expired
								unset($cookiedata[$i]);
							}
							$i++;
						}
					}
				}
				if ( ( 'cookie' != $datamethod ) && is_user_logged_in() ) {
					// --- check existing usermeta data ---
					// 2.0.1: allow for fallback for older installs
					// 2.0.7: use new prefixed current user function
					$current_user = bioship_get_current_user();
					$usermetadata = get_user_meta( $current_user->ID, $datakey, true );
					if ( is_array( $usermetadata ) ) {
						$existingmatch = false;
						$i = 0;
						// --- remove expired transient IDs from usermeta ---
						foreach ( $usermetadata as $transientkey ) {
							$transientdata = get_transient( $transientkey );
							if ( $transientdata ) {
								$data = explode( ':',$transientdata );
								if ( $data[0] == $requesturl ) {
									// update existing transient data
									$transientdata = $transientdebug = $requesturl . ':' . $themestylesheet . ':' . $themetemplate;
									set_transient( $transientkey, $transientdata, $expires );
									$existingmatch = true;
								}
							} else {
								// remove expired
								unset( $usermetadata[$i] );
							}
							$i++;
						}
					} else {
						$usermetadata = array();
					}
				}

				// --- set the transient with matching cookie/usermeta data ---
				if ( !$existingmatch ) { // avoid duplicates
					 // --- set the new transient ---
					 $transientkey = $datakey . '_' . uniqid();
					 $transientdata = $transientdebug = $requesturl . ':' . $themestylesheet . ':' . $themetemplate;
					 set_transient( $transientkey, $transientdata, $expires );

					 // --- add transient to cookie for matching later ---
					 if ( 'usermeta' != $datamethod ) {
						 $cookiedata[] = $transientkey;
						 $cookiedatastring = implode( ',', $cookiedata );
						 // 2.2.0: fix to incorrect variable (datakey)
						 setCookie( $datakey, $cookiedatastring, time() + $expires );
					 }
					 // --- add transient to usermeta for matching later ---
					 if ( 'cookie' != $datamethod ) {
					 	$usermetadata[] = $transientkey;
					 	update_user_meta( $current_user->ID, $datakey, $usermetadata );
					 }
				}

				// --- maybe output debug info ---
				if ( $debug ) {
					echo "<!-- COOKIE DATA: " . esc_html( print_r( $cookiedata, true ) ) . " -->";
					if ( 'cookie' != $datamethod ) {
						echo "<!-- USERMETA DATA: " . esc_html( print_r( $usermetadata, true ) ) . " -->";
					}
					echo "<!-- TRANSIENT DATA: " . esc_html( $transientdebug ) . " -->";
				}
			}

		} elseif ( $multiplethemes && ( 'sticky' != $method ) ) {
			// --- save/remove the requested URL path in the list ---
			if ( ( $stylesheet == $themestylesheet ) && ( $template == $themetemplate ) ) {
				// maybe remove this request from the stored URL list
				if ( is_array( $requesturls ) && array_key_exists( $requesturl, $requesturls ) ) {
					unset( $requesturls[$requesturl] );
					if ( 0 === count( $requesturls ) ) {
						delete_option( 'theme_switch_request_urls' );
					} else {
						update_option( 'theme_switch_request_urls', $requesturls );
					}
				}
			} else {
				// --- add this request URL to the stored list ---
				$requesturls[$requesturl]['stylesheet'] = $themestylesheet;
				$requesturls[$requesturl]['template'] = $themetemplate;
				update_option( 'theme_switch_request_urls', $requesturls );
			}

			// --- maybe output debug info ---
			if ( !is_admin() && $debug ) {
				echo "<!-- REQUEST URL: " . esc_url( $requesturl ) . " -->";
				echo "<!-- STORED URLS: " . esc_attr( print_r( $requesturls, true ) ) . " -->";
			}
		}

		// --- maybe output hidden ajax debugging frames ---
		if ( !is_admin() && $debug ) {
			echo "<iframe src='" . esc_url( admin_url( 'admin-ajax.php' ) ) . "?debug=1' style='display:none;'></iframe>";
			echo "<iframe src='" . esc_url( admin_url( 'admin-post.php' ) ) . "?debug=1' style='display:none;'></iframe>";
		}
	}
 }
}

