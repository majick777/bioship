<?php

/* No Content Found Template (via Hybrid Base) */

if (THEMETRACE) {bioship_trace('T',__('Error Template','bioship'),__FILE__);}

?>

<article <?php hybrid_attr('post'); ?>>

	<?php bioship_html_comment('.entry-header'); ?><header class="entry-header">
		<h1 class="entry-title">
			<?php // 1.8.5: added no content title filter
				$nocontenttitle = __( 'Nothing Found', 'bioship' );
				$nocontenttitle = bioship_apply_filters('skeleton_no_content_title',$nocontenttitle);
				echo $nocontenttitle;
			?>
		</h1>
	</header><?php bioship_html_comment('/.entry-header'); ?>

	<?php bioship_html_comment('.entry-content'); ?><div <?php hybrid_attr('entry-content'); ?>>
		<?php // 1.8.5: added no content message filter
			$nocontent = wpautop( __( 'Apologies, but no entries were found.', 'bioship' ) );
			$nocontent = bioship_apply_filters('skeleton_no_content_message',$nocontent);
			echo $nocontent;
		?>
	</div><?php bioship_html_comment('/.entry-content'); ?>

	<?php if (THEMEDEBUG) {global $wp_query; echo "<!-- Full Query: "; print_r($wp_query); echo "-->";} ?>

</article>