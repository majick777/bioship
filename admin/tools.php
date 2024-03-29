<?php

// ===========================
// === BioShip Theme Tools ===
// ===========================

// ---------------------------
// === tools.php Structure ===
// ---------------------------
// == Child Theme Install ==
// == Clone Child Theme ==
// === Theme Settings Transfers ===
// - Transfer Framework Settings
// - Manually Copy Theme Settings
// === Theme Tools Box ===
// - Theme Tools Forms
// === Backup / Restore Settings ===
// - Backup Settings AJAX
// - Backup Theme Settings
// - Restore Theme Settings
// === Export / Import Settings ===
// - Export Theme Settings AJAX
// - Export Theme Settings
// - Import Theme Settings
// - Revert to pre-Import Backup
// - Array to XML Function (for Export)
// - XML to Array Function (for Import)
// - Verify Uploaded File
// ---------------------------


// Development TODOs
// -----------------
// - fix format output for XML export (stopped working!?)


// ---------------------------
// === Child Theme Install ===
// ---------------------------
// ..."the one-click wonder"...
if ( !function_exists( 'bioship_admin_do_install_child' ) ) {
 function bioship_admin_do_install_child() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemetemplatedir, $vthemestyledir, $wp_filesystem;
	
	$message = '';

	// --- match new Child Name, allowing for spaces ---
	// 2.1.4: added esc_attr to message string outputs
	$newchildname = trim( $_REQUEST['newchildname'] );
	if ( '' == $newchildname ) {
		return esc_html( __( 'Error: Child Theme Name cannot be empty.', 'bioship' ) );
	}
	if ( !preg_match( '/^[0-9a-z ]+$/i', $newchildname ) ) {
		return esc_html( __('Error. Letters, numbers and spaces only!', 'bioship' ) );
	}
	$newchildslug = preg_replace( "/\W/", "-", strtolower( $newchildname ) );

	// 1.8.0: use directory separator, added debug dir
	$themesdir = get_theme_root() . DIRSEP;
	$childdir = $themesdir . $newchildslug . DIRSEP;
	$childimagedir = $childdir . 'images' . DIRSEP;
	$childcssdir = $childdir . 'styles' . DIRSEP;
	$childjsdir = $childdir . 'javascripts' . DIRSEP;
	$childdebugdir = $childdir . 'debug' . DIRSEP;

	// --- child theme file list ---
	// (keeping child theme files to a minimum here)
	// 1.8.0: also copy core-styles.css also to avoid using WP Filesystem for it later
	// 1.9.0: no longer copy hooks.php or template.php by default, but do copy debug/.htaccess
	// 2.0.9: removed filters.php copying (moved to /admin/ - for reference only)
	// 2.0.9: use DIRSEP and use alternative sources for WordPress.Org version
	if ( THEMEWPORG ) {
		$childfiles = array(
			'styles' . DIRSEP . 'child-styles.css'		=> 'style.css',
			'styles' . DIRSEP . 'core-styles.css'		=> 'styles' . DIRSEP . 'core-styles.css',
			'images' . DIRSEP . 'child-screenshot.jpg'	=> 'screenshot.jpg',
			'images' . DIRSEP . 'child-theme-logo.png'	=> 'theme-logo.png',
			'debug' . DIRSEP . '.htaccess'				=> 'debug' . DIRSEP . '.htaccess'
		);
	} else {
		$childfiles = array(
			'child' . DIRSEP . 'style.css'			=> 'style.css',
			'styles' . DIRSEP . 'core-styles.css' 	=> 'styles' . DIRSEP . 'core-styles.css',
			'child' . DIRSEP . 'functions.php'		=> 'functions.php',
			'child' . DIRSEP . 'screenshot.jpg'		=> 'screenshot.jpg',
			'child' . DIRSEP . 'theme-logo.png'		=> 'theme-logo.png',
			'debug' . DIRSEP . '.htaccess'			=> 'debug' . DIRSEP . '.htaccess'
		);
	}

	// Create Child Theme directory(s)
	// -------------------------------
	// 2.1.4: added esc_attr to message string outputs
	if ( is_dir( $childdir ) ) {

		// --- always avoid overwriting an existing Child Theme! ---
		$message = esc_html( __( 'Aborted! Child Theme directory of that name already exists!', 'bioship' ) ) . '<br>';
		$message .= esc_html( __( 'Remove or rename the existing directory and try again.', 'bioship' ) ) . '<br>';
		return $message;

	} else {
		// --- create child theme directory the WP Filesystem way ---
		$wp_filesystem->mkdir( $childdir );

		if ( !is_dir( $childdir ) ) {

			// --- fallback to the simple WP way ---
			// 2.1.1: re-added this method as a fallback ---
			wp_mkdir_p( $childdir );
			if ( !is_dir( $childdir ) ) {
				$message = esc_html( __( 'Aborted: Could not create Child Theme directory.', 'bioship' ) ) . '<br>';
				$message .= esc_html( __( 'Check your permissions or do a', 'bioship' ) ) . ' ';
				$message .= '<a href="' . esc_url( THEMEHOMEURL . '/documentation/' ) . '" target="_blank">' . esc_html( __( 'manual install', 'bioship' ) ) . '</a>.';
				return $message;
			}

			// --- make child subdirectories ---
			wp_mkdir_p( $childimagedir );
			wp_mkdir_p( $childcssdir );
			wp_mkdir_p( $childjsdir );
			wp_mkdir_p( $childdebugdir );

		} else {

			// --- make child subdirectories ---
			$wp_filesystem->mkdir( $childimagedir );
			$wp_filesystem->mkdir( $childcssdir );
			$wp_filesystem->mkdir( $childjsdir );
			$wp_filesystem->mkdir( $childdebugdir );
		}
	}

	// Copy Child Theme files
	// ----------------------
	$missingfiles = array();
	foreach ( $childfiles as $source => $destination ) {

		// 1.8.5: change 'child-source' directory to 'child'
		// 1.9.0: no longer copy hooks.php by default, but copy .htaccess
		// 1.9.5: fix to new child theme file destination directories
		// 2.0.9: use new source file array for sources and destinations
		$childsource = $vthemetemplatedir . $source;
		$childdest = $childdir . $destination;

		if ( file_exists( $childsource ) ) {

			// --- read the Child Theme source file ---
			// 1.8.0: read files using WP Filesystem
			$filecontents = $wp_filesystem->get_contents( $childsource );

			// --- replace Child Theme name in style.css ---
			// 1.9.5: match the child theme version to the parent version on creation
			// 2.2.0: simplify this check to match style.css destination
			if ( 'style.css' == $destination ) {
				$filecontents = str_replace( 'Theme Name: BioShip Child', 'Theme Name: ' . $newchildname, $filecontents );
				$filecontents = str_replace( '1.0.0', THEMEVERSION, $filecontents );
			}

			// --- write the destination file ---
			// 1.8.0: write the file using WP_Filesystem
			$wp_filesystem->put_contents( $childdest, $filecontents, FS_CHMOD_FILE );

		} else {
			$missingfiles[] = $source;
		}
	}

	// --- create empty Child Theme functions.php ---
	// 2.0.9: added this for WordPress.org version
	if ( THEMEWPORG ) {
		$functionspath = $childdir . 'functions.php';
		$wp_filesystem->put_contents( $functionspath, '', FS_CHMOD_FILE );
	}

	// --- message for missing files ---
	// 1.8.5: change 'child-source' directory to 'child'
	// 2.0.7: fix to changed variable typo (vmissing)
	if ( count( $missingfiles ) > 0 ) {
		$message .= esc_html( __( 'Error: Child Theme source files missing', 'bioship' ) ) . ':<br>';
		foreach ( $missingfiles as $missingfile ) {
			// 2.0.9: just display missing file paths from array
			$message .= '/bioship/' . $missingfile . '<br>';
		}
	}

	// Copy existing Parent Theme options to new Child Theme
	// -----------------------------------------------------

	// --- get parent theme settings ---
	// 1.8.0: do check for Titan Framework
	// 1.9.5: fix to parent settings framework logic
	$parentsettings = get_option( THEMEKEY );
	if ( THEMEOPT ) {	
		$childoptionsslug = str_replace( '-', '_', $newchildslug );
	} else {
		$childoptionsslug = $newchildslug . '_options';
	}

	// --- get existing settings ---
	// 2.1.4: added esc_attr to message string outputs
	$settingsmessage = '';
	$existingsettings = get_option( $childoptionsslug );
	if ( !$existingsettings ) {
		delete_option( $childoptionsslug );
		if ( !$parentsettings || ( '' == $parentsettings ) ) {
			// 1.9.5: set to default theme options
			$defaultsettings = bioship_titan_theme_options( array() );
			add_option( $childoptionsslug, $defaultsettings );
			$settingsmessage .= esc_html( __( 'No Parent Theme settings! Child Theme set to default settings.', 'bioship' ) ) . '<br>';
			$settingsmessage .= esc_html( __( 'See documentation to manually transfer settings between themes.', 'bioship' ) ) . '<br>';
		} else {
			add_option( $childoptionsslug, $parentsettings );
			$settingsmessage .= esc_html( __( 'Parent Theme settings transferred to new Child Theme.', 'bioship' ) ) . '<br>';
		}
	} else {
		$message .= esc_html( __( 'Child Theme settings exist, Parent Theme settings not transferred.', 'bioship' ) ) . '<br>';
	}

	// --- copy widgets menus and menu locations ---
	// 1.5.0: copy parent subsettings to child theme 'backup' options
	// (in preparation for child theme activation)
	$sidebarswidgets = get_option( 'sidebars_widgets' );
	$navmenus = get_option( 'nav_menu_options' );
	$menulocations = get_theme_mod( 'nav_menu_locations' );
	if ( !get_option( $childoptionsslug . '_widgets_backup' ) ) {
		delete_option( $childoptionsslug . '_widgets_backup' );
		add_option( $childoptionsslug . '_widgets_backup', $sidebarswidgets );
	}
	if ( !get_option( $childoptionsslug . '_menus_backup' ) ) {
		delete_option( $childoptionsslug . '_menus_backup' );
		add_option( $childoptionsslug . '_menus_backup', $navmenus );
	}
	if ( !get_option( $childoptionsslug . '_menu_locations_backup' ) ) {
		delete_option( $childoptionsslug . '_menu_locations_backup' );
		add_option( $childoptionsslug . '_menu_locations_backup', $menulocations );
	}

	// --- set Child creation output message ---
	// 1.8.0: added translation strings to messages
	// 2.1.4: added esc_attr to message string outputs
	if ( '' != $message ) {
		$creationresult = '(' . esc_html( __( 'with errors', 'bioship' ) ) . ')' . '<!-- FAILED -->';
	} else {
		$creationresult = esc_html( __( 'successfully', 'bioship' ) ) . '<!-- SUCCESS -->';
	}
	$message .= $settingsmessage;
	$message .= esc_html( __( 'New Child Theme', 'bioship' ) ) . ' "' . esc_attr( $newchildname ) . '" ';
	$message .= esc_html( __( 'created', 'bioship' ) ) . ' ' . $creationresult . '.<br>';
	$message .= esc_html( __( 'Base Directory','bioship' ) ) . ': ' . esc_attr( ABSPATH ) . '<br>';
	$message .= esc_html( __( 'Theme Subdirectory','bioship' ) ) . ': ' . esc_attr( str_replace( ABSPATH, '', $childdir ) ) . '<br>';
	$message .= esc_html( __( 'Activate it on your','bioship' ) ) . ' ';
	$message .= '<a href="' . esc_url( admin_url( 'themes.php' ) ) . '">' . esc_html( __( 'Themes Page', 'bioship' ) ) . '</a>.';

	// One-Click Activation for New Child Theme
	// ----------------------------------------
	$wpnonce = wp_create_nonce( 'switch-theme_'.$newchildslug );
	// 2.1.1: use add_query_arg to create URL
	$activatelink = add_query_arg( 'action', 'activate', admin_url('themes.php' ) );
	$activatelink = add_query_arg( 'stylesheet', $newchildslug, $activatelink );
	$activatelink = add_query_arg( '_wpnonce', $wpnonce, $activatelink );
	// 2.1.4: added esc_url to sanitize link output
	$message .= '... ' . esc_html( __( 'or just', 'bioship' ) ) . ' ';
	$message .= '<a href="' . esc_url( $activatelink ) . '">' . esc_html( __( 'click here to activate it', 'bioship' ) ) . '</a>.';

	// --- theme drive integration ---
	if ( function_exists( 'themedrive_determine_theme' ) ) {
		// 1.8.0: link for Titan or Options Framework
		if ( THEMETITAN ) {
			$testdriveurl = add_query_arg( 'page', THEMEPREFIX . '-options', admin_url( 'admin.php' ) );
		} else {
			$testdriveurl = add_query_arg( 'page', 'options-framework', admin_url( 'admin.php' ) );
		}
		$testdriveurl = add_query_arg( 'theme', $newchildslug, $testdriveurl );
		// 2.1.4: added esc_url to URL and esc_attr for message
		$message .= '<br>(' . __( 'or', 'bioship' ) . ' ';
		$message .= '<a href="' . esc_url( $testdriveurl ) . '">' . __('Theme Test Drive without activating', 'bioship' ) . '</a>.)';
	}

	return $message;
 }
}


// -------------------------
// === Clone Child Theme ===
// -------------------------
// 1.9.5: added child theme cloning function
// 2.1.4: added esc_attr to message string outputs
if ( !function_exists( 'bioship_admin_do_install_clone' ) ) {
 function bioship_admin_do_install_clone() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	global $wp_filesystem;

	// --- check Child Theme Source ---
	if ( !isset( $_REQUEST['clonetheme'] ) || ( '' == trim( $_REQUEST['clonetheme'] ) ) ) {
		return esc_html( __( 'Error: Source Child Theme not specified.','bioship' ) );
	} else {
		$clonetheme = trim( $_REQUEST['clonetheme'] );
	}

	// --- check for Child Theme Source settings ---
	if ( THEMEOPT ) {
		$clonesettings = get_option( $clonetheme );
	} else {
		$clonesettings = get_option( $clonetheme . '_options');
	}
	if ( !$clonesettings ) {
		return esc_html( __( 'Error: Source Child Theme Settings are empty!', 'bioship' ) );
	}

	// --- check BioShip parent ---
	// 2.1.1: match to THEMEPREFIX constant ---
	$theme = wp_get_theme( get_stylesheet( $clonetheme ) );
	if ( !isset( $theme['Template'] ) || ( THEMEPREFIX != $theme['Template'] ) ) {
		return esc_html( __( 'Cloning Aborted! Child Theme parent must be BioShip!', 'bioship' ) );
	}

	// --- check new Child Theme name ---
	$newclonename = trim( $_REQUEST['newclonename'] );
	if ( '' == $newclonename ) {
		return esc_html( __( 'Error: New Child Theme Name cannot be empty.', 'bioship' ) );
	}
	if ( !preg_match( '/^[0-9a-z ]+$/i', $newclonename ) ) {
		return esc_html( __( 'Error. Letters, numbers and spaces only!', 'bioship' ) );
	}
	$newcloneslug = preg_replace( "/\W/", "-", strtolower( $newclonename ) );
	if ( get_option( $newcloneslug ) ) {
		return esc_html( __( 'Aborted! Theme Settings already exist for this name!', 'bioship' ) );
	}

	// --- get/set Child Theme dirs ---
	$themesdir = get_theme_root() . DIRSEP;
	$childdir = $themesdir . $clonetheme . DIRSEP;
	$clonedir = $themesdir . $newcloneslug . DIRSEP;

	// --- check Child Theme Source files ---
	if ( !is_dir( $childdir ) ) {
		return __( 'Aborted! Source Child Theme directory does not exist!', 'bioship' );
	}

	// --- always avoid overwriting an existing Child Theme! ---
	if ( is_dir( $clonedir ) ) {
		$message = __( 'Aborted! Child Theme directory of that name already exists!', 'bioship' ) . '<br>';
		$message .= __( 'Remove or rename the existing directory and try again.', 'bioship' ) . '<br>';
		return $message;
	}

	// --- copy all Child Theme files to Clone ---
	$childfiles = bioship_admin_get_directory_files( $childdir, true );
	foreach ( $childfiles as $childfile ) {
		$sourcefile = $childdir . $childfile;
		$destfile = $clonedir . $childfile;
		echo "<!-- Copying: " . esc_attr( $sourcefile ) . " to " . esc_attr( $destfile ) . " -->" . "\n";
		if ( !is_dir( dirname( $destfile ) ) ) {
			$wp_filesystem->mkdir( dirname( $destfile ) );
		}
		$filecontents = $wp_filesystem->get_contents( $sourcefile );

		// --- replace theme name in styles.css ---
		if ( substr( $sourcefile, -9, 9 ) == 'style.css' ) {
			// 2.2.0: use line explode method instead of str_replace
			// $filecontents = str_replace( 'Theme Name: ' . THEMEDISPLAYNAME, 'Theme Name: ' . $newclonename, $filecontents );
			$lines = explode( "\n", $filecontents );
			foreach ( $lines as $i => $line ) {
				if ( strstr( $line, 'Theme Name: ' ) ) {
					$lines[$i] = 'Theme Name: ' . $newclonename;
					if ( "\r" == substr( $line, -1, 1 ) ) {
						$lines[$i] .= "\r";
					}	
				}
			}
			$filecontents = implode( "\n", $lines );			
		}

		// --- write the cloned file ---
		$wp_filesystem->put_contents( $destfile, $filecontents, FS_CHMOD_FILE );
	}

	// --- copy any empty subdirectories also ---
	$subdirs = bioship_admin_get_directory_subdirs( $childdir, true );
	echo "<!-- Creating Subdirs: " . esc_attr( print_r( $subdirs,true ) ) . " -->";
	foreach ( $subdirs as $subdir ) {
		$destdir = $clonedir . $subdir;
		if ( !is_dir( $destdir ) ) {
			$wp_filesystem->mkdir( $destdir );
		}
	}

	// --- add a clone stamp file ---
	global $current_user;
	$current_user = wp_get_current_user();
	$filecontents = 'Cloned from existing Child Theme: ' . THEMEDISPLAYNAME . ' (' . $clonetheme . ')' . PHP_EOL;
	$filecontents .= 'on ' . date( 'd/m/Y' ) . ' (timestamp: ' . time() . ') by ' . $current_user->user_login . PHP_EOL;
	$filecontents .= 'Serialized Settings at Clone time after this line ------' . PHP_EOL;
	if ( is_serialized( $clonesettings ) ) {
		$filecontents .= $clonesettings;
	} else {
		$filecontents .= serialize( $clonesettings );
	}
	$destfile = $clonedir . 'clonestamp.txt';
	$wp_filesystem->put_contents( $destfile, $filecontents, FS_CHMOD_FILE );

	// --- copy Child Theme settings
	if ( THEMEOPT ) {
		$newclonekey = $newcloneslug;
	} else {
		$newclonekey = $newcloneslug.'_options';
	}
	delete_option( $newclonekey );
	add_option( $newclonekey, $clonesettings );

	// --- copy all widget / sidebar settings (from active or backups) ---
	if ( $clonetheme == get_stylesheet() ) {
		$sidebarswidgets = get_option( 'sidebars_widgets' );
		$navmenus = get_option( 'nav_menu_options' );
		$menulocations = get_theme_mod( 'nav_menu_locations' );
	} else {
		$sidebarswidgets = get_option( $clonetheme.'_widgets_backup' );
		$navmenus = get_option( $clonetheme . '_menus_backup' );
		$menulocations = get_option($clonetheme . '_menu_locations_backup' );
	}

	// --- copy widgets / menus / menu locations ---
	// (assume that if the new clone settings were empty / deleted
	// copying over these is safe / wanted as well)
	// 2.1.1: just use update_option here
	update_option( $newcloneslug . '_widgets_backup', $sidebarswidgets );
	update_option( $newcloneslug . '_menus_backup', $navmenus );
	update_option( $newcloneslug . '_menu_locations_backup', $menulocations );

	// --- set Clone Creation Output Message ---
	$message = esc_html( __( 'New Child Theme', 'bioship' ) ) . ' "' . esc_attr( $newclonename ) . '" ';
	$message .= esc_html( __( 'cloned successfully.', 'bioship' ) ) . '<br>';
	$message .= esc_html( __( 'Base Directory', 'bioship' ) ) . ': ' . esc_attr( ABSPATH ) . '<br>';
	$message .= esc_html( __( 'Theme Subdirectory', 'bioship' ) ) . ': ' . esc_attr( str_replace( ABSPATH, '', $clonedir ) ) . '<br>';
	$message .= esc_html( __( 'Activate it on your', 'bioship' ) ) . ' ';
	$message .= '<a href="' . esc_url( admin_url( 'themes.php' ) ) . '">' . esc_html( __( 'Themes Page','bioship' ) ) . '</a>.';

	// --- One-Click Activation for New Cloned Theme ---
	$wpnonce = wp_create_nonce('switch-theme_' . $newcloneslug );
	$activatelink = admin_url( 'themes.php' );
	$activatelink = add_query_arg( 'action', 'activate', $activatelink );
	$activatelink = add_query_arg( 'stylesheet', $newcloneslug, $activatelink );
	$activatelink = add_query_arg( '_wpnonce', $wpnonce, $activatelink );
	$message .= '... ' . esc_html( __( 'or just','bioship' ) ) . ' ';
	$message .= '<a href="' . esc_url( $activatelink ) . '">' . esc_html( __( 'click here to activate it', 'bioship' ) ) . '</a>.';

	// --- theme drive integration ---
	if ( function_exists( 'themedrive_determine_theme' ) ) {
		// 2.1.1: use add_query_arg and optimize
		if ( THEMETITAN ) {
			$testdriveurl = add_query_arg( 'page', THEMEPREFIX . '-options', admin_url('admin.php' ) );
		} else {
			$testdriveurl = add_query_arg( 'page', 'options-framework', admin_url( 'themes.php' ) );
		}
		$testdriveurl = add_query_arg( 'theme', $newcloneslug, $testdriveurl );
		$message .= '<br>(' . esc_html( __( 'or','bioship' ) ) . ' ';
		$message .= '<a href="' . esc_url( $testdriveurl ) . '">' . esc_html( __( 'Theme Test Drive without activating', 'bioship' ) ) . '</a>.)';
	}

	// --- add hidden result indicator ---
	$message .= "<!-- SUCCESS -->";

	return $message;
 }
}

// --------------------------------
// === Theme Settings Transfers ===
// --------------------------------

// ---------------------------
// Transfer Framework Settings
// ---------------------------
// 1.9.5: rewritten and expanded transfer function
// 2.1.1: check for trigger internally
add_action( 'init', 'bioship_admin_framework_settings_transfer' );
if ( !function_exists('bioship_admin_framework_settings_transfer' ) ) {
 function bioship_admin_framework_settings_transfer() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check trigger and permissions ---
	if ( !isset( $_REQUEST[ 'transfersettings'] ) || !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	global $vthemestyledir, $vthemetemplatedir, $vthemeoptions;

	// 2.2.0: maybe load theme options
	if ( !isset( $vthemeoptions ) || !is_array( $vthemeoptions ) ) {
		if ( !function_exists( 'bioship_options' ) ) {
			$options = bioship_file_hierarchy( 'file', 'options.php' );
			include_once $options;
		}
		$vthemeoptions = bioship_options();
	}

	// --- check transfer existing options triggers ---
	if ( 'totitan' == $_REQUEST['transfersettings'] ) {
		$transferfrom = $transferto = false;
		if ( isset( $_REQUEST['fromtheme'] ) && ( '' != trim( $_REQUEST['fromtheme'] ) ) ) {
			$transferfrom = preg_replace( "/\W/", "_", strtolower( trim( $_REQUEST['fromtheme'] ) ) );
			$transferfrom = str_replace( '-', '_', $transferfrom );
		}
		if ( isset( $_REQUEST['totheme'] ) && ( '' != trim( $_REQUEST['totheme'] ) ) ) {
			$transferto = preg_replace( "/\W/", "-", strtolower( trim( $_REQUEST['totheme'] ) ) );
			$transferto = str_replace( '_', '-', $transferto ) . '_options';
		} elseif ( THEMETITAN ) {
			$transferto = THEMEKEY;
		}

		// 2.2.0: fix to missing variable indicator on transferfrom
		if ( $transferfrom && $transferto) {

			$optionvalues = get_option( $transferfrom );
			if ( !$optionvalues ) {

				// --- fallback to retrieving serialized settings from a file ---
				// if the database is acting up (this code from when it has happened)
				// used by copying the value from database to text in the debug directory
				$stylefile = $vthemestyledir . 'debug' . DIRSEP . $transferfrom . '.txt';
				$templatefile = $vthemestyledir . 'debug' . DIRSEP . $transferfrom . '.txt';

				if ( file_exists( $stylefile ) ) {
					$settingsfile = $stylefile;
				} elseif ( file_exists( $templatefile ) ) {
					$settingsfile = $templatefile;
				} else {
					$message = esc_html( __( 'Transfer Failed! Could not retrieve existing settings.', 'bioship' ) );
					global $vthemeadminmessages;
					$vthemeadminmessages[] = $message;
					bioship_admin_notices_enqueue();
					return;
				}

				$filecontents = trim( bioship_file_get_contents($settingsfile ) );
				$optionvalues = unserialize( $filecontents );
				if ( !$optionvalues || !is_array( $optionvalues ) ) {
				    $repaired = bioship_fix_serialized( $filecontents );
    				$optionvalues = unserialize( $repaired );
    				if ( !$optionvalues ) {
    				    // 2.2.0: fix echo to message set
    					$message = esc_html( __( 'Error! Could not unserialize settings from file!', 'bioship' ) );
						global $vthemeadminmessages;
						$vthemeadminmessages[] = $message;
						bioship_admin_notices_enqueue();
						return;
    				}
				}
			} else {
				if ( is_string( $optionvalues ) ) {
					$optionvalues = trim( $optionvalues );
				}
				if ( is_serialized( $optionvalues ) ) {
					$optionvalues = unserialize( $optionvalues );
				}
			}

			if ( ( '' != $optionvalues ) && ( is_array( $optionvalues ) ) ) {

				// --- map settings via theme options array ---
				// 2.2.0: fix themeoptions to vthemeoptions
				foreach ( $vthemeoptions as $option => $optionvalue ) {

					$optionkey = $optionvalue['id'];

					// --- map missing defaults ---
					if ( !isset($optionvalues[$optionkey] ) ) {
						if ( isset( $optionvalues['std'] ) ) {
							$optionvalues[$optionkey] = $optionvalue['std'];
						}
					}

					// --- fix to multicheck arrays ---
					if ( ( 'multicheck' == $optionvalue['type'] ) && is_array( $optionvalues[$optionkey] ) ) {
						$i = 0;
						$optionarray = array();
						foreach ( $optionvalues[$optionkey] as $key => $value ) {
							$optionarray[$i] = $key;
						}
						$optionvalues[$optionkey] = $optionarray;
					}

					// --- fix to serialize all subarray values ---
					if ( is_array( $optionvalues[$optionkey] ) ) {
						$optionvalues[$optionkey] = serialize( $optionvalues[$optionkey] );
					}

					// TODO: fix for font values and multicheck value transfers ?
					// TODO: fix for changing image URLs to attachment IDs ?
					// (would need to be manually inserted as new attachments?)

				}

				// --- update theme settings ---
				delete_option( $transferto );
				add_option( $transferto, serialize( $optionvalues ) );

				// --- also write settings to file just in case ---
				bioship_write_debug_file( $transferto.'.txt', serialize( $optionvalues ) );
				// echo serialize($optionvalues); exit; // for manual output

				// --- set admin transferred message ---
				$message = esc_html( __( 'Transferred Existing Theme Settings to Titan Framework.', 'bioship' ) );
				global $vthemeadminmessages;
				$vthemeadminmessages[] = $message;
				bioship_admin_notices_enqueue();
			}
		}
	}

	// TODO: Titan Framework settings to Options Framework settings Transfer here ?
	// (this is likely not going to happen as moving forwards with using Titan)
	// if ($_REQUEST['transfersettings'] == 'tooptions') {
	//	if ( (isset($_REQUEST['fromtheme'])) && (trim($_REQUEST['fromtheme']) != '') ) {
	//		$transferfrom = preg_replace("/\W/", "-", strtolower(trim($_REQUEST['fromtheme'])));
	//		$transferfrom = str_replace('_', '-', $transferfrom); $transferfrom .= '_options';
	//	}
	//	if ( (isset($_REQUEST['totheme'])) && (trim($_REQUEST['totheme']) != '') ) {
	//		$transferto = preg_replace("/\W/", "_", strtolower(trim($_REQUEST['fromtheme'])));
	//		$transferto = str_replace('-', '_', $transferto);
	//	} elseif (THEMEOPT) {$transferto = THEMEKEY;}

 }
}

// ----------------------------
// Manually Copy Theme Settings
// ----------------------------
// 2.2.0: fix to incorrect usage example keys here
// WARNING: will overwrite the existing Theme Settings for a theme
// usage: ?copysettings=yes&fromtheme=source-theme-slug&totheme=destination-theme-slug
// 1.9.5: added this to copy to/from any theme settings
// 2.0.9: check action trigger internally
if ( !function_exists( 'bioship_admin_copy_theme_settings' ) ) {

 // 2.0.9: move add action internally for consistency
 add_action( 'admin_init', 'bioship_admin_copy_theme_settings' );

 function bioship_admin_copy_theme_settings() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check trigger and permissions ---
	// 2.1.0: fix to AND instead of OR operator
 	if ( !isset( $_REQUEST['copysettings'] ) || ( 'yes' != $_REQUEST['copysettings'] ) ) {
 		return;
 	}
 	if ( !current_user_can( 'edit_theme_options' ) ) {
 		return;
 	}
 	
	// --- get copy from and copy to slugs ---
	// 2.2.0: fix to re-append _options suffix
 	$copyfrom = $copyto = $copyfromslug = $copytoslug = false;
 	if ( isset( $_REQUEST['fromtheme'] ) ) {
 		$copyfrom = trim( $_REQUEST['fromtheme'] );
 		if ( '' != $copyfrom ) {
 			$copyfromslug = preg_replace( "/\W/", "-", strtolower( $copyfrom ) );
 			$copyfromslug .= '_options';
 		}
 	}
	if ( isset( $_REQUEST['totheme'] ) ) {
		$copyto = trim( $_REQUEST['totheme'] );
		if ( '' != $copyto ) {
			$copytoslug = preg_replace( "/\W/", "-", strtolower( $copyto ) );
			$copytoslug .= '_options';
		}
	}

	// --- use underscores for Options Framework slugs ---
	if ( THEMEOPT ) {
		$copyfromslug = str_replace( '-', '_', $copyfromslug );
		// 2.1.1: fix to incorrect variable vtransferto
		$copytoslug = str_replace( '-', '_', $copytoslug );
	}

	// debug point
 	// echo $copyfrom . ' -> ' . $copyto . ' : ' . $copyfromslug . ' -> ' . $copytoslug;

 	if ( $copyfromslug && $copytoslug ) {

		// --- get theme settings ---
 		$fromsettings = get_option( $copyfromslug );
 		$tosettings = get_option( $copytoslug );

		// --- copy theme settings ---
 		// 2.0.5: removed numerous separate message functions
 		// TODO: backup existing theme settings (if any) ?
 		// TODO: copy over parent widgets/menus ?
 		if ( $fromsettings ) {
 			$copysettings = update_option( $copytoslug, $fromsettings );
			if ( $copysettings ) {
	 		 	$message = esc_html( __( 'Theme Settings have been copied from ', 'bioship' ) ) . esc_attr( $copyfrom );
	 		 	$message .= ' ' . esc_html( __( 'to', 'bioship' ) ) . ' ' . esc_attr( $copyto );
			} else {
				$message = esc_html( __( 'Theme Settings failed to copy to ', 'bioship' ) ) . esc_attr( $copyto );
			}
 		} else {
 		 	$message = esc_html( __( 'Copy Theme Settings failed! Could not retrieve settings for ', 'bioship' ) ) . esc_attr( $copyfrom );
 		}

 		// --- set theme admin messages ---
		global $vthemeadminmessages;
		$vthemeadminmessages[] = $message;
		bioship_admin_notices_enqueue();
 	}

 }
}


// -----------------------
// === Theme Tools Box ===
// -----------------------

// Note: No Tracers added to theme tools functions (seems pointless.)
// 1.9.5: changed usage of 'options' to 'settings'

// note: Manual querystring usage
// ?backup_theme_settings=yes or admin-ajax.php?action=backup_theme_settings
// ?restore_theme_settings=yes (manual usage deprecated - requires nonce check)
// ?export_theme_settings=yes or admin-ajax.php?action=export_theme_settings
// ?import_theme_settings=yes (manual usage deprecated - requires nonce check)
// ?revert_theme_settings=yes (revert to pre-import - manual deprecated requires nonce)

// -----------------
// Theme Tools Forms
// -----------------
// for backup / restore / export / import / revert
// note: AJAX action for backup / export, post refresh for restore / import / revert
if ( !function_exists( 'bioship_admin_theme_tools_forms' ) ) {
 function bioship_admin_theme_tools_forms() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemesettings, $vthemename;

	// Theme Tools Javascript
	// ----------------------

	// --- set action URLs ---
	$adminajax = admin_url( 'admin-ajax.php' );
	$actionurl = add_query_arg( 'page', $_REQUEST['page'], admin_url( 'admin.php' ) );

	// --- check theme selection for action URL ---
	if ( isset( $_REQUEST['theme'] ) && ( '' != $_REQUEST['theme'] ) ) {
		$actionurl = add_query_arg( 'theme', $_REQUEST['theme'], $actionurl );
	}

	// --- set confirm labels ---
	$confirmrestore = __( 'Are you sure you want to Restore the Theme Settings Backup?', 'bioship' );
	$confirmimport = __( 'Are you sure you want to Import these Theme Settings?', 'bioship' );
	$confirmrevert = __( 'Are you sure you want to Revert to Theme Settings prior to Import?', 'bioship' );

	// --- output javascript ---
	// 2.2.1: prefix javascript functions
	echo "<script>
	function bioship_confirmrestore() {
		var agree = '" . esc_js( $confirmrestore ) . "';
		if (confirm(agree)) {return true;} return false;
	}
	function bioship_confirmimport() {
		if (document.getElementById('textareaimport').checked == '1') {
			if (document.getElementById('importtextarea').value == '') {return false;} }
		var agree = '" . esc_js( $confirmimport ) . "';
		if (confirm(agree)) {return true;} return false;
	}
	function bioship_confirmrevert() {
		var agree = '".esc_js( $confirmrevert ) . "';
		if (confirm(agree)) {return true;} return false;
	}
	function bioship_backupthemesettings() {document.getElementById('themetoolsframe').src = '" . esc_url( $adminajax ) . "?action=backup_theme_settings';}
	function bioship_exportthemesettings() {
		if (document.getElementById('exportjson').checked == '1') {exportformat = 'json';}
		if (document.getElementById('exportserial').checked == '1') {exportformat = 'ser';}
		/* if (document.getElementById('exportxml').checked == '1') {exportformat = 'xml';} */
		document.getElementById('themetoolsframe').src = '" . esc_url( $adminajax ) . "?action=export_theme_settings&format='+exportformat;
	}
	function bioship_switchexportformat(format) {
		if (format == 'json') {document.getElementById('exportjson').checked = '1';}
		if (format == 'ser') {document.getElementById('exportserial').checked = '1';}
		if (format == 'xml') {document.getElementById('exportxml').checked = '1';}
	}
	function bioship_switchimportmethod(importmethod) {
		if (importmethod == 'fileupload') {
			document.getElementById('fileuploadimport').checked = '1';
			document.getElementById('importtextareas').style.display = 'none';
			document.getElementById('importfileselect').style.display = '';
		}
		if (importmethod == 'textarea') {
			document.getElementById('textareaimport').checked = '1';
			document.getElementById('importfileselect').style.display = 'none';
			document.getElementById('importtextareas').style.display = '';
		}
	}
	</script>" . "\n";

	// Theme Tools Interface
	// ---------------------

	// --- backup button ---
	// 2.2.0: fix to mismatching quotes
	echo '<table><tr>' . "\n";
		echo '<td style="vertical-align:middle;">';
		echo '<input type="button" class="button-primary" value="' . esc_attr( __( 'Backup', 'bioship' ) ) . '" onclick="bioship_backupthemesettings();">';
	echo '</td><td width="75"></td>' . "\n";
	
	// --- restore button ---
	echo '<td style="vertical-align:middle;">';
		echo '<form action="' . esc_url( $actionurl ) . '" method="post">' . "\n";
		echo '<input type="hidden" name="restore_theme_settings" value="yes">' . "\n";
		wp_nonce_field( 'restore_theme_settings_' . $vthemename );
		echo '<input type="submit" class="button-primary" value="' . esc_attr( __( 'Restore', 'bioship' ) ) . '" onclick="return bioship_confirmrestore();"></form>' . "\n";
	echo '</td><td width="75"></td>' . "\n";

	// --- export button ---
	echo '<td style="vertical-align:middle;">' . "\n";
		echo '<span id="exportform-arrow">&#9662;</span>' . "\n";
		echo '<input type="button" class="button-secondary" value="' . esc_attr( __( 'Export', 'bioship' ) ) . '" onclick="togglethemebox(\'exportform\');">' . "\n";
	echo '</td><td width="75"></td>' . "\n";
	
	// --- import button ---
	echo '<td style="vertical-align:middle;">' . "\n";
		echo '<span id="importform-arrow">&#9662;</span>' . "\n";
		echo '<input type="button" class="button-secondary" value="' . esc_attr( __( 'Import', 'bioship' ) ) . '" onclick="togglethemebox(\'importform\');">' . "\n";

	// --- revert button ---
	if ( isset( $vthemesettings['importtime'] ) ) {
		if ( '' != $vthemesettings['importtime'] ) {
			echo '</td><td width="75"></td><td>' . "\n";
			echo '<form action="' . esc_url( $actionurl ) . '" target="themetoolsframe" method="post">';
			echo '<input type="hidden" name="revert_theme_settings" value="yes">' . "\n";
			wp_nonce_field( 'revert_theme_settings_' . $vthemename );
			// 2.1.2: added missing translation wrapper
			echo '<input type="submit" value="' . esc_attr( __( 'Revert', 'bioship' ) ) . '" onclick="return bioship_confirmrevert();"></form>' . "\n";
		}
	}
	echo '</td></tr>' . "\n";

	// Backup Form
	// -----------
	// TODO: multiple/regular auto-backup options? (unique backups only)
	// TODO: set maximum number of theme settings backups to keep (revisions?)

	// Restore Form
	// ------------
	// TODO: display multiple theme option backups?
	// TODO: view backup data / delete backup options

	// Export Form
	// -----------
	echo '<tr><td colspan="7" align="center"><div id="exportform-inside" style="display:none;">' . "\n";
		echo '<center><form><table><tr height="25"><td> </td></tr>' . "\n";
		// wp_nonce_field( 'export_theme_settings_' . $vthemename );

		// --- export format selection ---
		// 2.2.1: added missing onclicks for labels
		echo '<tr><td><b>' . esc_html( __( 'Export Format', 'bioship' ) ) . ':</b></td><td width="20"></td>' . "\n";
		echo '<td width="80" align="right"><input type="radio" id="exportserial" name="exportformat" value="ser" checked="checked"> <a href="javascript:void(0);" onclick="bioship_switchexportformat(\'ser\');" style="text-decoration:none;">' . esc_html( __( 'Serialized', 'bioship' ) ) . '</a></td><td width="40"></td>' . "\n";
		echo '<td width="80" align="right"><input type="radio" id="exportjson" name="exportformat" value="json"> <a href="javascript:void(0);" onclick="bioship_switchexportformat(\'json\');" style="text-decoration:none;">JSON</a></td><td width="40"></td>' . "\n";
		// TEMP: disabled while XML export format not working
		// echo '<td width="80" align="right"><input type="radio" id="exportxml" name="exportformat" value="xml"> <b>XML</b></td><td width="40"></td>' . "\n";

		// --- export button ---
		echo '<td><input type="button" class="button-primary" value="' . esc_attr( __( 'Export', 'bioship' ) ) . '" onclick="bioship_exportthemesettings();"></td>' . "\n";
		echo '</tr></table></form>' . "\n";

	echo '</div></td></tr>' . "\n";

	// Import Form
	// -----------
	echo '<tr><td colspan="7" align="center"><div id="importform-inside" style="display:none;">' . "\n";

		// --- start import form ---
		// 2.0.7: remove form target='themetoolsframe'
		echo '<form action="' . esc_url( $actionurl ) . '" enctype="multipart/form-data" method="post">' . "\n";
		echo '<input type="hidden" name="import_theme_settings" value="yes">' . "\n";
		wp_nonce_field( 'import_theme_settings_' . $vthemename );
		// for import debugging switch passthrough
		if ( THEMEDEBUG ) {
			echo '<input type="hidden" name="themedebug" value="2">' . "\n";
		}

		echo '<table><tr height="25"><td> </td></tr><tr>' . "\n";

			// --- select import method ---
			echo '<td style="vertical-align:top; line-height:12px;"><b>' . esc_html( __( 'Import Method', 'bioship' ) ) . ':<b><br><br>' . "\n";
			echo '<input type="radio" id="fileuploadimport" name="importmethod" value="fileupload" onchange="bioship_switchimportmethod(\'fileupload\');" checked="checked"> ' . "\n";
			echo '<a href="javascript:void(0);" onclick="bioship_switchimportmethod(\'fileupload\');" style="text-decoration:none;">' . esc_html( __( 'File Upload', 'bioship' ) ) . '</a><br><br>' . "\n";
			echo '<input type="radio" id="textareaimport" name="importmethod" value="textarea" onchange="bioship_switchimportmethod(\'textarea\');"> ' . "\n";
			echo '<a href="javascript:void(0);" onclick="bioship_switchimportmethod(\'textarea\');" style="text-decoration:none;">' . esc_html( __( 'Text Area', 'bioship' ) ) . '</a></td>' . "\n";
			echo '<td width="20"></td>' . "\n";

			// --- textarea import fields ---
			echo '<td align="center" style="vertical-align:middle;"><div id="importtextareas" style="display:none;">' . "\n";
			echo '(' . esc_html( __( 'XML, JSON or Serialized', 'bioship' ) ) . '<br>' . esc_html( __( 'are auto-detected.', 'bioship' ) ) . ')<br>' . "\n";
			echo '<textarea name="importtextarea" id="importtextarea" style="width:300px; height:80px;"></textarea>' . "\n";
			echo '</div></td>' . "\n";

			// --- file upload import field ---
			echo '<td align="center" style="vertical-align:middle;"><div id="importfileselect" style="width:300px;">' . "\n";
			echo esc_html( __( 'Select Theme Options file to Import', 'bioship' ) ) . ':<br><br>' . "\n";
			echo '<input type="file" name="importthemeoptions" size="30"></div></td>' . "\n";

			// --- import submit button ---
			echo '</td><td width="20"></td><td style="vertical-align:bottom;">' . "\n";
			echo '<input type="submit" class="button-primary" value="' . esc_attr( __( 'Import', 'bioship' ) ) . '" onclick="return bioship_confirmimport();"></td>' . "\n";

		echo '</tr></table></form>' . "\n";

	echo '</div></td></tr></table>' . "\n";

	// Theme Tools Iframe
	// ------------------
	echo '<iframe style="display:none;" src="javascript:void(0);" name="themetoolsframe" id="themetoolsframe"></iframe>' . "\n";
 }
}

// ---------------------------------
// === Backup / Restore Settings ===
// ---------------------------------
// Backup via URL querystring or Theme Tools UI
// Restore via Theme Tools UI (requires nonce)

// --------------------
// Backup Settings AJAX
// --------------------
// 2.1.1: make separate AJAX trigger function
if ( !function_exists( 'bioship_admin_backup_theme_settings_ajax' ) ) {

 add_action( 'wp_ajax_backup_theme_settings', 'bioship_admin_do_backup_theme_settings_ajax' );

 function bioship_admin_do_backup_theme_settings_ajax() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	bioship_admin_backup_theme_settings( true );
 }
}

// ---------------------
// Backup Theme Settings
// ---------------------
if ( !function_exists( 'bioship_admin_backup_theme_settings' ) ) {

 // 2.0.7: merged repetitive trigger function and use add_action
 // 2.1.1: move add_action internally for
 add_action( 'init', 'bioship_admin_backup_theme_settings' );

 function bioship_admin_backup_theme_settings( $ajax = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	// --- check triggers ---
	if ( !$ajax ) {
		if ( !isset( $_REQUEST['backup_theme_settings'] ) || ( 'yes' != $_REQUEST['backup_theme_setttings'] ) ) {
			return;
		}
	}

 	// --- check permissions ---
 	if ( !current_user_can( 'edit_theme_options' ) ) {
 		return;
 	}
 	// (do not check nonce - to allow querystring backup)

	// --- backup theme settings ---
	$currentsettings = maybe_unserialize( get_option( THEMEKEY ) );
	$currentsettings['backuptime'] = time();
	$backupkey = THEMEKEY . '_user_backup';
	update_option( $backupkey, $currentsettings );

	// --- set/alert admin message ---
	$message = esc_html( __( 'Current Theme Settings User Backup has been Created!', 'bioship' ) );
	if ( defined( 'DOING_AJAX') && DOING_AJAX ) {
		echo "<script>alert('" . esc_js( $message ) . "');</script>";
		exit;
	} else {
		global $vadminmessages;
		$vadminmessages[] = $message;
		bioship_admin_notices_enqueue();
	}
 }
}

// ----------------------
// Restore Theme Settings
// ----------------------
if ( !function_exists( 'bioship_admin_restore_theme_settings' ) ) {

 // 2.1.1: use add_action for consistency
 add_action( 'init', 'bioship_admin_restore_theme_settings' );

 function bioship_admin_restore_theme_settings() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	// --- check triggers ---
	if ( !isset( $_POST['restore_theme_settings'] ) || ( 'yes' != $_POST['restore_theme_settings'] ) ) {
		return;
	}

	// --- check permissions ---
	if ( !current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// 1.8.5: added nonce check
	global $vthemename;
	check_admin_referer( 'restore_theme_settings_' . $vthemename);

	// --- restore theme settings ---
	// switch not delete, so as to backs up 'current' options (if not empty)
 	// 1.8.5: fix to incorrect restoretime application
 	$currentsettings = maybe_unserialize( get_option( THEMEKEY ) );
	$backupkey = THEMEKEY . '_user_backup';
	$backupsettings = maybe_unserialize( get_option( $backupkey ) );
	$backupsettings['restoretime'] = time();

	// --- force update bypass ---
	// 1.9.5: define constant to not trigger force update filter
	// 2.0.7: fix by deleting the force update transient and removing filter
	delete_transient( 'force_update_' . THEMEKEY );
	remove_filter( 'pre_option_' . THEMEKEY, 'bioship_get_theme_settings', 10, 2 );
	update_option( THEMEKEY, $backupsettings );

	if ( is_array( $currentsettings ) ) {
		$currentsettings['backuptime'] = time();
		update_option( $backupkey, $currentsettings );
	}
	// 1.9.5: update settings global to continue
	global $vthemesettings;	$vthemesettings = $backupsettings;

	$message = esc_html( __( 'Theme Settings Backup Restored! (You can switch back by using this method again.)', 'bioship' ) );
	global $vadminmessages;
	$vadminmessages[] = $message;
	bioship_admin_notices_enqueue();
 }
}

// --------------------------------
// === Export / Import Settings ===
// --------------------------------
// 1.5.0: added export / import / revert triggers
// 1.8.0: changed prefix from muscle to bioship_admin, and restorepreimport to revert

// --------------------------
// Export Theme Settings AJAX
// --------------------------
if ( !function_exists( 'bioship_admin_export_theme_settings_ajax' ) ) {

 // 2.1.1: move add_action internally for consistency
 add_action( 'wp_ajax_export_theme_settings', 'bioship_admin_export_theme_settings_ajax' );

 function bioship_admin_export_theme_settings_ajax() {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}
	bioship_admin_export_theme_settings( true );
 }
}

// ---------------------
// Export Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_export_theme_options
// 2.0.7: added serialized format export option
if ( !function_exists( 'bioship_admin_export_theme_settings' ) ) {

 // 2.1.1: move add_action internally
 add_action( 'init', 'bioship_admin_export_theme_settings' );

 function bioship_admin_export_theme_settings( $ajax = false ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	global $vthemename, $vthemesettings, $vthemestyledir;

	// --- check export trigger ---
	if ( !$ajax ) {
		if ( !isset( $_REQUEST['export_theme_settings'] ) || ( 'yes' != $_REQUEST['export_theme_settings'] ) ) {
			return;
		}
	}

	// --- check permissions ---
	if ( !current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	// (do not check nonce - to allow for querystring export)
	// check_admin_referer( 'export_theme_settings_' . $vthemename );

	// --- add the export time to the array ---
	$vthemesettings['exporttime'] = time();

	$format = '';
	if ( isset( $_REQUEST['format'] ) ) {
		$format = trim( $_REQUEST['format'] );
	}
	$valid = array( 'ser', 'json', 'xml' );
	if ( ( '' == $format ) || !in_array( $format, $valid ) ) {
		$format = 'ser';
	}

	// --- set the export filename ---
	$date = date( 'Y-m-d--H:i:s', time() );
	$filename = $vthemename .'_options--' . $date . '.' . $format;

	// --- convert the theme settings array ---
	// 2.2.0: fix to variable name typos
	if ( 'json' == $format ) {
		// --- convert to JSON data ---
		$export = json_encode( $vthemesettings );
		$contenttype = 'text/json';
	} elseif ( 'ser' == $format ) {
		// --- convert to serialized string ---
		$export = serialize( $vthemesettings );
		$contenttype = 'text/plain';
	} elseif ( 'xml' == $format ) {

		// --- create an XML document ---
		$xml = new SimpleXMLElement( '<themeoptions/>' );
		bioship_admin_array_to_xml( $xml, $vthemesettings );
		$export = $xml->asXML();
		$contenttype = 'text/xml';

		$dom = new DOMDocument();
		// also add line breaks to make it human readable?
		$dom->formatOutput = true;

		// for export debugging
		// print_r( $export );
		// $newsettings = bioship_admin_xml_to_array( $exportxml );
		// print_r( $newsettings );
		// $diff = array_diff( $newsettings, $vthemesettings );
		// print_r( $diff ); exit;

		// TODO: fix format output for XML export (stopped working!?)
		$dom->loadXML( $export );
		$export = $dom->saveXML();

	}

	// --- save generated export file ---
	$exportfile = $vthemestyledir . 'debug' . DIRSEP . $filename;
	bioship_write_to_file( $exportfile, $export );

	// --- output XML file (force download) ---
	header( 'Content-disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-type: ' . $contenttype );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.OutputNotEscaped
	echo $export;
	exit;
 }
}

// ---------------------
// Import Theme Settings
// ---------------------
// 1.8.0: renamed from muscle_import_theme_options
if ( !function_exists( 'bioship_admin_import_theme_settings' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'init', 'bioship_admin_import_theme_settings' );

 function bioship_admin_import_theme_settings() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemename, $vthemesettings;

	// --- check for trigger ---
	// 2.1.1: moved internally for consistency
	if ( !isset( $_POST['import_theme_settings'] ) || ( 'yes' != $_POST['import_theme_settings'] ) ) {
		return;
	}

	// --- check permissions ---
	if ( !current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	check_admin_referer( 'import_theme_settings_' . $vthemename );

	// --- enqueue admin notices ---
	bioship_admin_notices_enqueue();

	$importmethod = $_POST['importmethod'];
	if ( 'textarea' == $importmethod ) {

		// --- import from textarea ---
        // 2.2.0: fix to remove old variable prefix v
		$importdata = stripslashes( trim( $_POST['importtextarea'] ) );
		if ( ( '<' == substr( $importdata, 0, 1 ) ) && ( '>' == substr( $importdata, -1, 1 ) ) ) {
			$format = 'xml';
		} elseif ( ( '{' == substr( $importdata, 0, 1 ) ) && ( '}' == substr( $importdata, -1, 1 ) ) ) {
			$format = 'json';
		} elseif ( is_serialized( $importdata ) ) {
			$format = 'serial';
		}
		if ( THEMEDEBUG ) {
			echo "<!-- Import Data Type: " . esc_html( $format ) . " -->";
		}

		// --- convert according to file format ---
		if ( 'json' == $format ) {
			// --- convert JSON data ---
			// JSON validator ref: http://stackoverflow.com/a/15198925/5240159
			$newthemesettings = json_decode( $importdata, true );
		} elseif ( 'xml' == $format ) {
			// --- convert the XML data ---
			$newthemesettings = bioship_admin_xml_to_array( $importdata );
		} elseif ( 'serial' == $format ) {
			// --- convert serialized data ---
			// 2.0.7: unserialize serialized data
			$newthemesettings = unserialize( $importdata );
		} else {
			// --- format not recognized error message ---
			$message = __( 'Failed: format not recognized. Please upload valid XML, JSON or Serialized data.', 'bioship' );
		}

	} elseif ( 'fileupload' == $importmethod ) {

		// --- import from file upload ---
		$verifyupload = bioship_admin_verify_file_upload( 'importthemeoptions' );
		if ( is_wp_error( $verifyupload ) ) {
			$message = __( 'Upload Error', 'bioship' ) . ": " . esc_attr( $verifyupload->get_error_message );
		} else {
			$format = strtolower( $verifyupload['type'] );
			$data = $verifyupload['data'];
			if ( THEMEDEBUG ) {
				echo "<!-- Uploaded File Type: " . esc_attr( $format ) . " -->";
			}
			if ( 'json' == $format ) {
				$newthemesettings = json_decode( $data, true );
			} elseif ( 'xml' == $format ) {
				$newthemesettings = bioship_admin_xml_to_array( $data );
			} elseif ( is_serialized( $data ) ) {
				$newthemesettings = unserialize( $data );
			}
		}
	}

	if ( THEMEDEBUG ) {
		echo "<-- Uploaded Theme Options: " . esc_html( print_r( $newthemesettings ) ) . " -->";
	}

	if ( $newthemesettings && is_array( $newthemesettings ) ) {

		// --- bypass force update fix ---
		// 2.0.7: fix by deleting the force update transient and removing filter
		delete_transient( 'force_update_' . THEMEKEY );
		remove_filter( 'pre_option_' . THEMEKEY, 'bioship_get_theme_settings', 10, 2 );

		// --- set the import timestamp ---
		$newthemesettings['importedtime'] = time();

		// --- backup the existing theme settings ---
		$backupkey = THEMEKEY . '_import_backup';
		update_option( $backupkey, $vthemesettings );

		// --- check for theme settings changes ---
		// 1.8.5: allow selective import, to only override new values found
		$changed = false;
		foreach ( $newthemesettings as $optionkey => $optionvalue ) {
			if ( !is_array( $optionvalue ) || !isset( $vthemesettings[$optionkey] ) ) {
				$vthemesettings[$optionkey] = $optionvalue;
				$changed = true;
			} elseif ( is_array( $optionvalue ) ) {
				foreach ( $optionvalue as $suboptionkey => $suboptionvalue ) {
					$vthemesettings[$optionkey][$suboptionkey] = $suboptionvalue;
					$changed = true;
				}
			}
		}

		// --- maybe update to the newly imported theme options ---
		if ( $changed ) {
			update_option( THEMEKEY, $vthemesettings );
			$message = __( 'Theme Settings have been Imported successfully!', 'bioship' );
		} else {
			$message = __( 'No changed Theme Settings detected in import!', 'bioship' );
		}

	} else {
		$message = __( 'Could not convert import data to Theme Settings array.', 'bioship' );
	}

	// --- set import result message ---
	// 1.9.5: add theme admin message
	global $vadminmessages;
	$vadminmessages[] = $message;
	bioship_admin_notices_enqueue();
	// echo "<script>alert('".esc_js($message)."');</script>"; exit;

	// TODO: use bioship_debug function here ?
	if ( THEMEDEBUG ) {
		$newsettings = get_option( THEMEKEY );
		echo "<!-- New Theme Settings: " . esc_html( print_r( $newsettings ) ) . " -->";
		$debugdata = print_r( $newthemesettings, true )  .PHP_EOL . print_r( $vthemesettings, true );
		bioship_write_debug_file( 'file-upload-import.txt', $debugdata );
	}

 }
}

// ---------------------------
// Revert to pre-Import Backup
// ---------------------------
// 1.8.0: renamed from muscle_restore_preimport_theme_options
if ( !function_exists( 'function bioship_admin_revert_theme_settings' ) ) {

 // 2.1.1: moved add_action internally for consistency
 add_action( 'init', 'bioship_admin_revert_theme_settings' );

 function bioship_admin_revert_theme_settings() {
	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	global $vthemename, $vthemesettings;

	// --- check triggers ---
	if ( !isset( $_POST['revert_theme_settings'] ) || ( 'yes' != $_POST['revert_theme_settings'] ) ) {
		return;
	}

 	// --- check permissions ---
 	if ( !current_user_can( 'edit_theme_options' ) ) {
 		return;
 	}
	check_admin_referer( 'revert_theme_settings_' . $vthemename );

	// --- switch the pre-import backup and existing options ---
	$backupkey = THEMEKEY . '_import_backup';
	$backupoptions = get_option( $backupkey );

	// --- bypass force update fix ---
	// 2.0.7: fix by deleting the force update transient and removing filter
	delete_transient( 'force_update_' . THEMEKEY );
	remove_filter( 'pre_option_' . THEMEKEY, 'bioship_get_theme_settings', 10, 2 );

	// --- revert backed up settings ---
	if ( !empty( $backupoptions ) && is_array( $backupoptions ) ) {
		update_option( THEMEKEY, $backupoptions );
		update_option( $backupkey, $vthemesettings );
		$message = esc_html( __( 'Pre-Import Theme Settings have been reverted.', 'bioship' ) ) . "<br>";
		$message .= esc_html( __( '(You can switch back to the Imported Settings by using this method again.)', 'bioship' ) );
	} else {
		$message = esc_html( __( 'Revert Failed! Pre-Import Theme Settings are empty or corrupt!', 'bioship' ) );
	}

	// 2.0.5: enqueue admin notice message
	global $vadminmessages;
	$vadminmessages[] = $message;
	bioship_admin_notices_enqueue();
 }
}

// ----------------------------------
// Array to XML Function (for Export)
// ----------------------------------
// ref: http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
// answer used: http://stackoverflow.com/a/19987539/5240159
if ( !function_exists( 'bioship_admin_array_to_xml' ) ) {
 function bioship_admin_array_to_xml( SimpleXMLElement $object, array $data ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

    foreach ( $data as $key => $value ) {
        if ( is_array( $value ) ) {
            $newobject = $object->addChild( $key );
            bioship_admin_array_to_xml( $newobject, $value );
        } else {
        	// --- handle htmlspecialchars ---
            $object->addChild( $key, htmlspecialchars( $value ) );
        }
    }
 }
}

// ----------------------------------
// XML to Array Function (for Import)
// ----------------------------------
// ref: http://stackoverflow.com/questions/6578832/how-to-convert-xml-into-array-in-php
if ( !function_exists( 'bioship_admin_xml_to_array' ) ) {
 function bioship_admin_xml_to_array( $xml ) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__ );}

	$vthemesettings = json_decode( json_encode( (array)simplexml_load_string( $xml ) ), 1 );

	// --- do htmlspecialchars_decode 3 levels deep ---
	// 1.8.5: decode 3 levels instead of only 2
	foreach ( $vthemesettings as $key => $value ) {
		if ( !is_array( $value ) ) {
			$vthemesettings[$key] = htmlspecialchars_decode( $value );
		} elseif ( $value == array() ) {
			// no non-set array values thanks
			$vthemesettings[$key] = '';
		} else {
			foreach ( $value as $subkey => $subvalue ) {
				if ( !is_array( $subvalue ) ) {
					$vthemesettings[$key][$subkey] = htmlspecialchars_decode( $subvalue );
				} elseif ( $subvalue == array() ) {
					$vthemesettings[$key][$subkey] = '';
				} else {
					foreach ( $subvalue as $subsubkey => $subsubvalue ) {
						if ( !is_array( $subsubvalue ) ) {
							$vthemesettings[$key][$subkey][$subsubkey] = htmlspecialchars_decode( $subsubvalue );
						} elseif ( $subsubvalue == array() ) {
							$vthemesettings[$key][$subkey][$subsubkey] = '';
						}
						// else {print_r($subsubvalue);} // debug point
					}
				}
			}
		}
	}
	return $vthemesettings;
 }
}

// --------------------
// Verify Uploaded File
// --------------------
// 1.8.5: added this upload check handler
// ref: http://php.net/manual/en/features.file-upload.php
if ( !function_exists( 'bioship_admin_verify_file_upload' ) ) {
 function bioship_admin_verify_file_upload($inputkey) {
 	if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

	try {

		// --- Undefined | Multiple Files | $_FILES Corruption Attack ---
		// If this request falls under any of them, treat it invalid.
		if ( isset($_FILES[$inputkey]['error'] ) || is_array( $_FILES[$inputkey]['error'] ) ) {
			throw new RuntimeException( __( 'Invalid parameters.','bioship' ) );
		}

		// --- check $_FILES[$inputkey]['error'] value ---
		switch ( $_FILES[$inputkey]['error'] ) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException( __( 'No file sent.','bioship' ) );
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException( __( 'Exceeded filesize limit.', 'bioship' ) );
			default:
				throw new RuntimeException( __( 'Unknown errors.','bioship' ) );
		}

		// --- should also check filesize here ---
		if ( $_FILES[$inputkey]['size'] > 1000000) {
			throw new RuntimeException( __( 'Exceeded filesize limit.', 'bioship' ) );
		} elseif ( 0 === $_FILES[$inputkey]['size'] ) {
			throw new RuntimeException( __( 'File is empty.', 'bioship' ) );
		}

		// --- Check MIME Type ---
		// note: DO NOT TRUST $_FILES['upfile']['mime'] VALUE !
		if ( class_exists( 'finfo' ) ) {
			// 2.0.8: fix for serialized extension validation
			$finfo = new finfo( FILEINFO_MIME_TYPE );
			if (false === $extension = array_search(
				$finfo->file( $_FILES[$inputkey]['tmp_name'] ),
				array( 'xml' => 'text/xml', 'json' => 'text/json', 'ser' => 'text/plain' ),
				true
			)) {
				echo "<!-- File Info: " . esc_attr( print_r( $finfo, true ) ) . " -->";
				// echo "<!-- Tmp Name: ".esc_attr($finfo->file[$_FILES[$inputkey]['tmp_name']))." -->";
				throw new RuntimeException( __( 'Invalid file format.', 'bioship' ) );
			}
		} else {
			if ( isset( $_FILES[$inputkey]['mime'] ) ) {
				echo "<!-- File Mime Type: " . esc_attr( $_FILES[$inputkey]['mime'] ) . " -->";
			}
			$pathinfo = pathinfo( $_FILES[$inputkey]['name'] );
			echo "<!-- File Path Info: " . esc_attr( print_r( $pathinfo ) ) . "-->";
			$extension = $pathinfo['extension'];
			$valid = array( 'json', 'xml', 'ser' );
			if ( !in_array( $extension, $valid ) ) {
				throw new RuntimeException( __( 'Invalid file extension.', 'bioship' ) );
			}
		}

		// You should name it uniquely.
		// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
		// On this example, obtain safe unique name from its binary data.
		// if (!move_uploaded_file(
		//	$_FILES[$inputkey]['tmp_name'],
		//	sprintf('./uploads/%s.%s',
		//		sha1_file($_FILES[$inputkey]['tmp_name']),
		//		$extension
		//	)
		// )) {
		// 	throw new RuntimeException(__('Failed to move uploaded file.','bioship'));
		// }

		// --- set file data ---
		$file['type'] = $extension;
		$file['data'] = bioship_file_get_contents( $_FILES[$inputkey]['tmp_name'] );
		return $file;

	} catch ( RuntimeException $e ) {
		$error = $e->getMessage();
		echo "<!-- ERROR: " . esc_attr( $error ) . " -->";
		return new WP_Error( 'failed', $error );
	}
 }
}
