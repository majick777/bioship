<?php

	if (THEMETRACE) {skeleton_trace('T',__('Footer Template','bioship'),__FILE__);}

	do_action('skeleton_before_footer');
	wp_footer();
	do_action('skeleton_after_footer');

	do_action('skeleton_container_close');
	do_action('skeleton_after_container');

?>

</div></body>
</html>
