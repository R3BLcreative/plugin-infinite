<div id="infinite-filters" class="py-3 flex flex-row flex-nowrap items-start justify-between gap-10">
	<script>
		const handleBulkActionsTargetChange = (el) => {
			const targ = el.options[el.selectedIndex].dataset.target;
			const form = document.querySelector('#list-view-bulk-actions');

			form.target = targ;
		};
	</script>
	<!--  -->
	<div class="">
		<?php if ($data['showBulkActions']): ?>
			<form id="list-view-bulk-actions" action="<?php echo admin_url('admin-ajax.php'); ?>" class="flex flex-row items-center justify-center gap-6" target="">
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('ajax_bulk_actions'); ?>">

				<div class="flex flex-col">
					<label for="action" class="sr-only">Bulk Actions</label>
					<select id="action" name="action" tabindex="0" onchange="handleBulkActionsTargetChange(this);">
						<option selected disabled>Bulk Actions</option>
						<?php foreach ($data['actions'] as $value => $params): ?>
							<option value="<?php echo $value; ?>" data-target="<?php echo $params['target']; ?>">
								<?php echo $params['display']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<button type="submit" onclick="location.reload();" class="infinite-button btn-secondary btn-md">
					<i><?php echo inf_get_icon('layers'); ?></i>
					<span class="mobile:hidden tablet:inline-block">Apply</span>
				</button>
			</form>
		<?php endif; ?>
	</div>

	<!--  -->
	<div class="flex flex-row items-center justify-end gap-6">
		<span class="pt-2 pl-4 text-sm tracking-wider flex gap-3">
			<em>Total Records:</em>
			<strong class="text-secondary"><?php echo $data['total']; ?></strong>
		</span>
		<span class="pt-2 pl-4 text-sm tracking-wider flex gap-3">
			<em>Selected Records:</em>
			<strong id="list-view-selected-counter" class="text-secondary">0</strong>
		</span>
	</div>
</div>