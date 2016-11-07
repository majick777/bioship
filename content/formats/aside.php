
<?php /** Aside Post Format Template **/ ?>

<?php $vpostid = get_the_ID(); $vposttype = get_post_type(); ?>

<div class="clear"></div>

<?php if (THEMECOMMENTS) : ?><!-- article.entry --><?php endif; ?>
<article <?php hybrid_attr('post');?>>

	<?php do_action('skeleton_before_entry'); ?>


	<?php /* Entry Header */ ?>

	<?php do_action('skeleton_entry_header'); ?>


	<?php /* Thumbnail */ ?>

	<?php do_action('skeleton_thumbnail'); // 1.5.0: changed to an action hook ?>


	<?php /* Entry Content Summary */ ?>

	<?php if (is_archive() || is_search() || (!is_singular($vposttype)) ) : ?>

		<?php do_action('skeleton_before_excerpt'); ?>

		<?php if (THEMECOMMENTS) : ?><!-- .entry-summary --><?php endif; ?>
		<div <?php hybrid_attr('entry-summary'); ?>>

			<?php do_action('skeleton_the_excerpt'); // 1.5.0: changed to an action hook ?>

		</div><?php if (THEMECOMMENTS) : ?><!-- /.entry-summary --><?php endif; ?>

		<?php do_action('skeleton_after_excerpt'); ?>


		<?php /* Entry Footer */ ?>

		<?php do_action('skeleton_entry_footer'); ?>


	<?php else : ?>

		<?php do_action('skeleton_before_singular'); ?>


		<?php /* Author Bio (top) */ ?>

		<?php do_action('skeleton_author_bio_top'); // 1.5.0: changed to action hook ?>


		<?php /* Entry Content (Full) */ ?>

		<?php if (THEMECOMMENTS) : ?><!-- .entry-content --><?php endif; ?>
		<div <?php hybrid_attr('entry-content'); ?>>

			<?php do_action('skeleton_the_content'); // 1.5.0: changed to an action hook ?>

			<div class="clear"></div>

		</div><?php if (THEMECOMMENTS) : ?><!-- /.entry-content --><?php endif; ?>


		<?php /* Author Bio (bottom) */ ?>

		<?php do_action('skeleton_author_bio_top'); // 1.5.0: changed to an action hook ?>


		<?php /* Entry Footer */ ?>

		<?php do_action('skeleton_entry_footer'); ?>


		<?php do_action('skeleton_after_singular'); ?>


		<?php /* Comments */ ?>

		<?php do_action('skeleton_comments'); // 1.5.0: changed to an action hook ?>

	<?php endif; ?>

	<?php do_action('skeleton_after_entry'); ?>

</article><?php if (THEMECOMMENTS) : ?><!-- /article.entry --><?php endif; ?>