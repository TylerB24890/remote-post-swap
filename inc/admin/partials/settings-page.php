<h1><?php _e('Remote Post Swap', RPS_SLUG); ?></h1>

<form action='options.php' method='post' style="margin-top: 40px;">
	<?php
	settings_fields( 'rps-settings' );
	do_settings_sections( 'rps-settings-admin' );
	submit_button();
	?>
</form>
