
<?php /** Aside Post Format Template **/ ?>

<?php $vpostid = get_the_ID(); $vposttype = get_post_type(); ?>

<div class="clear"></div>

<?php bioship_html_comment('article.entry'); ?>
<article <?php hybrid_attr('post');?>>

	<?php bioship_do_action('bioship_before_entry'); ?>


	<?php /* Entry Header */ ?>

	<?php bioship_do_action('bioship_entry_header'); ?>


	<?php /* Thumbnail */ ?>

	<?php bioship_do_action('bioship_thumbnail'); ?>


	<?php /* Entry Content Summary */ ?>

	<?php if (is_archive() || is_search() || (!is_singular($vposttype)) ) : ?>

		<?php bioship_do_action('bioship_before_excerpt'); ?>

		<?php bioship_html_comment('.entry-summary'); ?>
		<div <?php hybrid_attr('entry-summary'); ?>>

			<?php bioship_do_action('bioship_the_excerpt'); ?>

		</div><?php bioship_html_comment('/.entry-summary'); ?>

		<?php bioship_do_action('bioship_after_excerpt'); ?>


		<?php /* Entry Footer */ ?>

		<?php bioship_do_action('bioship_entry_footer'); ?>


	<?php else : ?>

		<?php bioship_do_action('bioship_before_singular'); ?>


		<?php /* Author Bio (top) */ ?>

		<?php bioship_do_action('bioship_author_bio_top'); ?>


		<?php /* Entry Content (Full) */ ?>

		<?php bioship_html_comment('.entry-content'); ?>
		<div <?php hybrid_attr('entry-content'); ?>>

			<?php bioship_do_action('bioship_the_content'); ?>

			<div class="clear"></div>

		</div><?php bioship_html_comment('/.entry-content'); ?>


		<?php /* Author Bio (bottom) */ ?>

		<?php bioship_do_action('bioship_author_bio_bottom'); ?>


		<?php /* Entry Footer */ ?>

		<?php bioship_do_action('bioship_entry_footer'); ?>


		<?php bioship_do_action('bioship_after_singular'); ?>


		<?php /* Comments */ ?>

		<?php bioship_do_action('bioship_comments'); ?>

	<?php endif; ?>

	<?php bioship_do_action('bioship_after_entry'); ?>

</article><?php bioship_html_comment('/article.entry'); ?>