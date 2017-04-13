<h1><?php _e('Remote Post Swap', RPS_SLUG); ?></h1>

<form action='options.php' method='post' style="margin-top: 40px;">
	<?php
	settings_fields( 'rps-settings' );
	do_settings_sections( 'rps-settings-admin' );
	submit_button();
	?>
</form>

<div style="margin-top: 30px">
	<button id="clear-transients" class="button">Flush Remote Post Swap Data</button>
</div>

<script>

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

(function($) {
	$('button#clear-transients').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: {action: 'rps_delete_meta'},
			success: function(data) {
				alert("All Remote Post Swap data has been flushed");
			}
		});
	});
})(jQuery);
</script>
