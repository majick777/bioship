<?php

	if (THEMETRACE) {skeleton_trace('T',__('Footer Template','bioship'),__FILE__);}

	do_action('bioship_before_footer');
	wp_footer();
	do_action('bioship_after_footer');

	do_action('bioship_container_close');
	do_action('bioship_after_container');

?>

</div></body>
</html>
