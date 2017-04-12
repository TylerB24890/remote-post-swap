<form action='options.php' method='post'>

	<h2>Remote Dev Database</h2>

	<?php
	settings_fields( 'rdd-settings' );
	do_settings_sections( 'rdd_settings_section' );
	submit_button();
	?>

</form>
