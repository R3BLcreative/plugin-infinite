<div id="<?php echo $options['slug']; ?>" class="hidden max-w-[225px]">
	<form action="<?php echo admin_url('admin-ajax.php'); ?>">
		<input type="hidden" name="action" value="<?php echo $options['action']; ?>">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('ajax_' . $options['action']); ?>">

		<?php require_once plugin_dir_path(__FILE__) . 'form-' . $options['form'] . '.php'; ?>

		<button type="submit" class="aap-button btn-alt btn-sm !w-full mt-2">Save</button>
	</form>
</div>