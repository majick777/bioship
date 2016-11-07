<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>

	<h1 class="entry-title"><?php the_title(); ?></h1>
	<div class="entry-meta">
		<?php skeleton_posted_on(); ?>
	</div><!-- .entry-meta -->

	<div class="entry-content">
	<?php the_content(); ?>
	<div class="clear"></div>
	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'smpl' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>

		<?php get_template_part('author','bio'); ?>

	<?php endif; ?>

	<div class="entry-utility">
		<?php skeleton_posted_in(); ?>
		<?php edit_post_link( __( 'Edit', 'smpl' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-utility -->

</div><!-- #post-## -->

	<?php do_action('skeleton_page_navi'); ?>

	<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>