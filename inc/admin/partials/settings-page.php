<h1><?php _e('Remote Development Database', RDD_SLUG); ?></h1>

<form action='options.php' method='post' style="margin-top: 40px;">
	<?php
	settings_fields( 'rdd-settings' );
	do_settings_sections( 'rdd-settings-admin' );
	submit_button();
	?>
</form>
