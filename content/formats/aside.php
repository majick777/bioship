
<?php /** Aside Post Format Template **/ ?>

<?php $vpostid = get_the_ID(); $vposttype = get_post_type(); ?>

<div class="clear"></div>

<?php if (THEMECOMMENTS) : ?><!-- article.entry --><?php endif; ?>
<article <?php hybrid_attr('post');?>>

	<?php do_action('bioship_before_entry'); ?>


	<?php /* Entry Header */ ?>

	<?php do_action('bioship_entry_header'); ?>


	<?php /* Thumbnail */ ?>

	<?php do_action('bioship_thumbnail'); ?>


	<?php /* Entry Content Summary */ ?>

	<?php if (is_archive() || is_search() || (!is_singular($vposttype)) ) : ?>

		<?php do_action('bioship_before_excerpt'); ?>

		<?php if (THEMECOMMENTS) : ?><!-- .entry-summary --><?php endif; ?>
		<div <?php hybrid_attr('entry-summary'); ?>>

			<?php do_action('bioship_the_excerpt'); ?>

		</div><?php if (THEMECOMMENTS) : ?><!-- /.entry-summary --><?php endif; ?>

		<?php do_action('bioship_after_excerpt'); ?>


		<?php /* Entry Footer */ ?>

		<?php do_action('bioship_entry_footer'); ?>


	<?php else : ?>

		<?php do_action('bioship_before_singular'); ?>


		<?php /* Author Bio (top) */ ?>

		<?php do_action('bioship_author_bio_top'); ?>


		<?php /* Entry Content (Full) */ ?>

		<?php if (THEMECOMMENTS) : ?><!-- .entry-content --><?php endif; ?>
		<div <?php hybrid_attr('entry-content'); ?>>

			<?php do_action('bioship_the_content'); ?>

			<div class="clear"></div>

		</div><?php if (THEMECOMMENTS) : ?><!-- /.entry-content --><?php endif; ?>


		<?php /* Author Bio (bottom) */ ?>

		<?php do_action('bioship_author_bio_bottom'); ?>


		<?php /* Entry Footer */ ?>

		<?php do_action('bioship_entry_footer'); ?>


		<?php do_action('bioship_after_singular'); ?>


		<?php /* Comments */ ?>

		<?php do_action('bioship_comments'); ?>

	<?php endif; ?>

	<?php do_action('bioship_after_entry'); ?>

</article><?php if (THEMECOMMENTS) : ?><!-- /article.entry --><?php endif; ?>