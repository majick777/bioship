<?php

// ----------------------
// === Test Templates ===
// ----------------------

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

define( 'THEMETRACE', false );
define( 'THEMEDEBUG', false );

$template = !isset( $_GET['template'] ) ? $_GET['template'] : 'all';
$templates = array(

	// --- Main Templates ---
	'index',
	'header',
	'loop-index',
	'footer',
	
	// --- Content Templates ---
	'content/author-bio', 
	'content/comments',
	'content/comments-error',
	'content/comments-nav',
	'content/content',
	'content/error',
	'content/loop-meta',
	'content/loop-nav',

	// --- Sidebar Templates ---
	'sidebar/archive',
	'sidebar/author',
	'sidebar/blank',
	'sidebar/category',
	'sidebar/date',
	'sidebar/footer',
	'sidebar/front',
	'sidebar/header',
	'sidebar/home',
	'sidebar/notfound',
	'sidebar/page',
	'sidebar/post',
	'sidebar/primary',
	'sidebar/search',
	'sidebar/subsidiary',
	'sidebar/tag',
	'sidebar/taxonomy',

	// --- Subsidebar Templates ---
	'sidebar/subarchive',
	'sidebar/subauthor',
	'sidebar/subblank',
	'sidebar/subcategory',
	'sidebar/subdate',
	'sidebar/subfront',
	'sidebar/subhome',
	'sidebar/subnotfound',
	'sidebar/subpage',
	'sidebar/subpost',
	'sidebar/subprimary',
	'sidebar/subsearch',
	'sidebar/subtag',
	'sidebar/subtaxonomy',
	
);

// --- load dummy functions ---
bioship_template_dummy_functions();

// --- load dummy variables ---
$req = true;
$commenter = array(
	'comment_author' => '1',
	'comment_author_name' => '***COMMENT AUTHOR***',
	'comment_author_email' => '***AUTHOR EMAIL***',
	'comment_author_url' => '/',
);

// --- include template file(s) ---
$base_path = dirname( dirname( __FILE__ ) );
if ( in_array( $template, $templates ) ) {
	$template_path = $base_path . '/' . $template . '.php';
	echo "Testing include of " . $template_path . ":<br>" . PHP_EOL;
	echo "-----<br>";
	if ( file_exists( $template_path ) ) {
		include $template_path;
	}
	echo "-----<br><br>";
} elseif ( 'all' == $template ) {
	foreach ( $templates as $template ) {
		$template_path = $base_path . '/' . $template . '.php';
		echo "Testing include of " . $template_path . ":<br>" . PHP_EOL;
		echo "-----<br>";
		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
		echo "-----<br><br>";
	}
} else {
	echo "Could not find template '" . $template . "' to test.";
	exit;
}

function bioship_template_dummy_functions() {

	function wp_head() {echo '***WP HEAD***';}
	function wp_body_open() {}
	function wp_footer() {echo '***WP FOOTER***';}
	function hybrid_get_attr( $a = false, $b = false, $c = false ) {return '';}
	function is_active_sidebar() {return true;}
	function dynamic_sidebar( $name ) {echo "*** SIDEBAR CONTENT ***";}
	function bioship_html_comment( $tag ) {echo "<!--" . $tag . " -->";}
	function bioship_do_action( $a = false, $b = false, $c = false ) {}
	function bioship_apply_filters( $a, $b, $c = false, $d = false ) {return $b;}
	function post_password_required() {return false;}
	function apply_filters( $a, $b ) {}
	function comments_open() {return true;}
	function pings_open() {return true;}
	function get_comments_number() {return 1;}
	function number_format_i18n( $n ) {return $n;}
	function get_the_title() {return "***TITLE***";}
	function wp_list_comments( $args ) {}
	function previous_comments_link() {echo "***PREVIOUS COMMENTS LINK***";}
	function next_comments_link() {echo "***NEXT COMMENTS LINK***";}
	function get_option( $name ) {return 1;}
	function comments_form( $args ) {return "***COMMENTS FORM***";}
	function cancel_comment_reply_link() {return '';}
	function esc_url( $url ) {return $url;}
	function esc_html( $html ) {return $html;}
	function esc_attr( $attr ) {return $attr;}
	function wp_login_url() {return '/login/';}
	function get_permalink( $id = false ) {return '/';}
	function get_trackback_url() {return '';}
	function bioship_get_loop_title() {return '***LOOP TITLE***';}
	function bioship_get_loop_description() {return '***LOOP DESCRIPTION***';}
	function __( $a, $b = false ) {return $a;}
	function _x( $a, $b, $c ) {return $a;}
	function _n( $a, $b = false ) {return $a;}
	function wpautop( $a ) {return $a;}
	function bioship_get_post_types() {return 'post';}
	function bioship_debug( $a, $b ) {}
	function is_search() {return false;}
	function is_archive() {return false;}
	function is_singular( $a = false ) {return true;}
	function is_front_page() {return true;}
	function is_home() {return false;}
	function is_404() {return false;}
	function is_paged() {return false;}
	function get_comment_pages_count() {return 1;}
	function get_query_var( $var ) {return '';}
	function absint( $v ) {return abs( intval( $v ) );}
	function bioship_get_author_avatar() {return '***AUTHOR AVATAR***';}
	function bioship_skeleton_about_author_title() {return '***AUTHOR TITLE***';}
	function bioship_skeleton_about_author_description() {return '***AUTHOR DESCRIPTION';}
	function bioship_skeleton_author_posts_link() {return '/';}
	function bioship_count_footer_widgets() {return 4;}
	function have_posts() {return true;}
	function have_comments() {return true;}
	function the_post() {}
	function bioship_get_content_template() {}
	function bioship_locate_template( $a, $b ) {}
	function rewind_posts() {}
	function bioship_get_header( $a ) {}
	function bioship_get_sidebar( $a ) {}
	function bioship_get_footer( $a ) {}
	function bioship_get_loop( $a ) {}
	function get_language_attributes() {return '';}
	function is_user_logged_in() {return false;}

}

exit;
