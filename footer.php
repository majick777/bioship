<?php

	if (THEMETRACE) {bioship_trace('T',__('Footer Template','bioship'),__FILE__);}

	bioship_do_action('bioship_before_footer');
		wp_footer();
	bioship_do_action('bioship_after_footer');

	bioship_do_action('bioship_container_close');
	bioship_do_action('bioship_after_container');

?>

</div></body>
</html>
