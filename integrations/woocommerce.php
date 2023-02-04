<?php

// ===========
// WooCommerce
// ===========

// ------------------------------
// WooCommerce Template Directory
// ------------------------------
// Changes directory for Woocommerce templates (for both child and parent theme directories)
// intended so you could use: /theme/theme-name/templates/woocommerce/
// instead of the default: /theme/theme-name/woocommerce/
// (as a better way of organizing 3rd party templates)
// WARNING: use one directory OR the other, it is NOT a hierarchy so you cannot use both!

// --------------------------------
// WooCommerce Template Path Filter
// --------------------------------
// 2.2.0: remove unnecessary class_exists check (filter only applied if Woo is loaded)
// if ( class_exists( 'WC_Template_Loader' ) ) {

	if ( !function_exists( 'bioship_muscle_woocommerce_template_path' ) ) {

	 add_filter( 'woocommerce_template_path', 'bioship_muscle_woocommerce_template_path' );

	 function bioship_muscle_woocommerce_template_path($path) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// 1.9.5: added this filter to allow further change
		// override woocommerce/ to (filtered) templates/woocommerce/
		$newpath = bioship_apply_filters( 'skeleton_woocommerce_template_directory', 'templates/woocommerce/' );

		global $vthemetemplatedir, $vthemestyledir;
		if ( is_dir( $vthemetemplatedir . $newpath ) || is_dir( $vthemestyledir . $newpath ) ) {
			// 1.9.5: only if new template directory exists do we apply other template filters
			add_filter( 'wc_get_template', 'bioship_muscle_woocommerce_template', 10, 5 );
			add_filter( 'wc_get_template_part', 'bioship_muscle_woocommerce_template_part', 10, 3 );
			return $newpath;
		} else {
			return $path;
		}
	 }
	}
// }

// ---------------------------------------------
// Woocommerce Template subdirectories Templates
// ---------------------------------------------
// 2.1.1: removed unneeded function_exists check
if ( !function_exists( 'bioship_muscle_woocommerce_template' ) ) {
	function bioship_muscle_woocommerce_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// --- find the new template via file hierarchy ---
		// looking in templates/woocommerce/ then woocommerce/
		// 1.9.5: apply the template directory filter and search that only
		// 2.1.1: removed unnecessary trailing slash from default path
		$newpath = bioship_apply_filters( 'skeleton_woocommerce_template_directory', 'templates/woocommerce' );
		$newtemplate = bioship_file_hierarchy( 'file', $template_name, array( $newpath ) );

		// -- write debug info ---
		// (not used but kept here as useful for finding templates)
		// ob_start();
		// echo "new template: "; print_r($newtemplate); echo PHP_EOL;
		// echo "located: "; print_r($located); echo PHP_EOL;
		// echo "template_name: "; print_r($template_name); echo PHP_EOL;
		// $data = ob_get_contents(); ob_end_clean();
		// bioship_write_debug_file('woo-templates.txt', $data);

		// return the new template location if found
		if ( $newtemplate ) {
			return $newtemplate;
		}

		return $located;
	}
}

// --------------------------
// Woocommerce Template Parts
// --------------------------
// 2.1.1: removed unneeded function_exists check
// eg. single-product-content.php and anything retrieved by wc_get_template_part
if ( !function_exists( 'bioship_muscle_woocommerce_template_part' ) ) {
	function bioship_muscle_woocommerce_template_part( $template, $slug, $name ) {
		if ( THEMETRACE ) {bioship_trace( 'F', __FUNCTION__, __FILE__, func_get_args() );}

		// 1.9.5: apply the template directory filter and search that only
		// 2.1.1: removed unnecessary trailing slash from default path
		$newpath = bioship_apply_filters( 'skeleton_woocommerce_template_directory', 'templates/woocommerce' );
		// get slug-name template via file hierarchy
		$newtemplate = bioship_file_hierarchy( 'file', $slug . '-' . $name . '.php', array( $newpath ) );
		// include a fallback to slug based template
		$slugtemplate = bioship_file_hierarchy( 'file', $slug . '.php', array( $newpath ) );

		// write debug info (kept here as useful for finding templates)
		// ob_start();
		// echo "name template (".$name."): "; print_r($newtemplate); echo PHP_EOL;
		// echo "slug template (".$slug."): "; print_r($slugtemplate); echo PHP_EOL;
		// $data = ob_get_contents(); ob_end_clean();
		// bioship_write_debug_file('woo-template-parts.txt', $data);

		// maybe return the altered template location
		if ( $newtemplate ) {
			return $newtemplate;
		}
		if ( $slugtemplate ) {
			return $slugtemplate;
		}

		return $template;
	}
}
