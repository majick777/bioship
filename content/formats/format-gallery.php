
<?php /* Skeleton Gallery Post Format Template */ ?>
<?php /* to merge with Hybrid Gallery Template */ ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'smpl' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

	<div class="entry-meta">
		<?php skeleton_posted_on(); ?>
	</div><!-- .entry-meta -->

	<div class="entry-content">
	<?php if ( post_password_required() ) : ?>
			<?php the_content(); ?>
	<?php else : ?>
			<?php
				$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $images ) :
					$total_images = count( $images );
					$image = array_shift( $images );
					$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
			?>
					<div class="gallery-thumb">
						<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					</div><!-- .gallery-thumb -->
					<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'smpl' ),
							'href="' . get_permalink() . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'smpl' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
							number_format_i18n( $total_images )
						); ?></em></p>
			<?php endif; ?>
					<?php the_excerpt(); ?>
	<?php endif; ?>
	</div><!-- .entry-content -->

	<div class="entry-utility">
	<?php if ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) ) : ?>
		<a href="<?php echo get_post_format_link( 'gallery' ); ?>" title="<?php esc_attr_e( 'View Galleries', 'smpl' ); ?>"><?php _e( 'More Galleries', 'smpl' ); ?></a>
		<span class="meta-sep">|</span>
	<?php elseif ( in_category( _x( 'gallery', 'gallery category slug', 'smpl' ) ) ) : ?>
		<a href="<?php echo get_term_link( _x( 'gallery', 'gallery category slug', 'smpl' ), 'category' ); ?>" title="<?php esc_attr_e( 'View posts in the Gallery category', 'smpl' ); ?>"><?php _e( 'More Galleries', 'smpl' ); ?></a>
		<span class="meta-sep">|</span>
	<?php endif; ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'smpl' ), __( '1 Comment', 'smpl' ), __( '% Comments', 'smpl' ) ); ?></span>
		<?php edit_post_link( __( 'Edit', 'smpl' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-utility -->
</div><!-- #post-## -->