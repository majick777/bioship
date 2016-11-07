<?php

/* No Content Found Template (via Hybrid Base) */

?>

<article <?php hybrid_attr('post'); ?>>

	<?php if (THEMECOMMENTS) : ?><!-- .entry-header --><?php endif; ?><header class="entry-header">
		<h1 class="entry-title">
			<?php // 1.8.5: added no content title filter
				$nocontenttitle = __( 'Nothing Found', 'bioship' );
				$nocontenttitle = apply_filters('skeleton_no_content_title',$nocontenttitle);
				echo $nocontenttitle;
			?>
		</h1>
	</header><?php if (THEMECOMMENTS) : ?><!-- /.entry-header --><?php endif; ?>

	<?php if (THEMECOMMENTS) : ?><!-- .entry-content --><?php endif; ?><div <?php hybrid_attr('entry-content'); ?>>
		<?php // 1.8.5: added no content message filter
			$nocontent = wpautop( __( 'Apologies, but no entries were found.', 'bioship' ) );
			$nocontent = apply_filters('skeleton_no_content_message',$nocontent);
			echo $nocontent;
		?>
	</div><?php if (THEMECOMMENTS) : ?><!-- /.entry-content --><?php endif; ?>

	<?php if (THEMEDEBUG) {global $wp_query; echo "<!-- Query: "; print_r($wp_query); echo "-->";} ?>

</article>