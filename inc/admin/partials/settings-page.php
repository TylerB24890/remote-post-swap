<h1><?php _e('Remote Development Database', RDD_SLUG); ?></h1>

<?php if($this->rdd_check_connection_options()) : ?>
	<div class="notice notice-warning is-dismissible" style="padding: 15px;">
		Remote Database Connection is active.
	</div>
<?php else : ?>
	<div class="notice notice-warning is-dismissible" style="padding: 15px;">
		Remote Database Connection is not active.
	</div>
<?php endif; ?>

<form action='options.php' method='post'>
	<?php
	settings_fields( 'rdd-settings' );
	do_settings_sections( 'rdd-settings-admin' );
	submit_button();
	?>
</form>
