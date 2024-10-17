<!-- WRAPPER -->
<div id="<?php echo $options['slug']; ?>" class="hidden fixed z-0 left-0 top-0 w-full h-full overflow-auto bg-black/50">

	<!-- MODAL -->
	<div class="bg-surface-800 my-[15%] mx-auto p-6 border border-secondary rounded-lg w-full max-w-[800px] relative">

		<!-- CLOSE MODAL -->
		<button id="<?php echo $options['slug']; ?>-close" class="absolute top-2 right-2 w-6 h-6 fill-white hover:fill-primary">
			<?php echo inf_get_icon('close'); ?>
		</button>
		<!-- /CLOSE MODAL -->

		<!-- CONTENT -->
		<div class="pt-6">
			<h2 class="h2 mb-4"><?php echo $options['title']; ?></h2>

			<form action="<?php echo admin_url('admin-ajax.php'); ?>">
				<input type="hidden" name="action" value="<?php echo $options['action']; ?>">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('ajax_' . $options['action']); ?>">

				<?php require_once plugin_dir_path(__FILE__) . 'form-' . $options['form'] . '.php'; ?>

				<div class="mt-6">
					<button type="submit" class="aap-button btn-alt btn-sm !w-full mt-2">Save</button>
				</div>
			</form>
		</div>
		<!-- /CONTENT -->

	</div>
	<!-- /MODAL -->

</div>
<!-- /WRAPPER -->