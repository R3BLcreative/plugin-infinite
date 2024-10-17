<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    infinite
 * @subpackage infinite/admin/partials
 */

// [ ] Responsive styles/layout

$thCss = 'px-3 py-3 font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5';
?>
<!-- FILTERS -->
<?php
$INF->infinite_filters_bar([
	'limit' => $limit,
	'cols' => $cols,
	's' => $s,
	'orderby' => $orderby,
	'direction' => $direction,
	'filterby' => $filterby,
	'filterval' => $filterval,
	'showFilter' => $showFilter,
	'filterVals' => (isset($filterVals)) ? $filterVals : false,
	'screen' => $screen,
]);
?>

<!-- BULK ACTIONS -->
<?php $INF->infinite_bulk_bar($bulk_bar_data); ?>

<table class="w-full rounded-md overflow-hidden">
	<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group bg-surface-800 shadow-lg">
		<tr class="">
			<?php foreach ($cols as $col) : ?>
				<th class="<?php echo $thCss . ' ' . $col['css']['col']; ?>">
					<?php if ($orderby == $col['slug']) : ?>
						<i class="w-4 inline-block fill-body opacity-50 align-middle"><?php echo inf_get_icon('sort'); ?></i>
					<?php endif; ?>
					<?php echo $col['label']; ?>
				</th>
			<?php endforeach; ?>
			<th class="<?php echo $thCss; ?> w-[15%] text-center">Actions</th>
		</tr>
	</thead>
</table>

<script>
	const handleListViewClick = (el) => {
		const parent = el.parentNode;
		const checkbox = parent.querySelector('input[type="checkbox"]');
		checkbox.checked = !checkbox.checked;

		var cells = parent.querySelectorAll('td');
		cells.forEach(cell => {
			cell.classList.toggle('!bg-secondary-400/25');
		});

		countListViewSelected();
	}
</script>

<div class="w-full h-[700px] overflow-y-scroll overflow-x-hidden border-b border-b-secondary-100">
	<table class="w-full">
		<tbody class="">
			<?php if (!empty($rows)) : foreach ($rows as $row) : ?>
					<tr class="even:bg-surface-800 text-body-400 hover:bg-surface-600">
						<!-- CONTENT -->
						<?php foreach ($cols as $col) : ?>
							<td onclick="handleListViewClick(this);" class="<?php echo $tdCss . ' ' . $col['css']['col'] . ' ' . $col['css']['cell']; ?>">
								<?php echo (!empty($row[$col['slug']])) ? $row[$col['slug']] : ''; ?>
							</td>
						<?php endforeach; ?>

						<!-- ACTIONS -->
						<td class="<?php echo $tdCss; ?> w-[15%]">
							<input type="checkbox" id="record-<?php echo $row['ID']; ?>" name="selected[]" value="<?php echo $row['ID']; ?>" class="hidden list-view-row-selectors" form="list-view-bulk-actions">

							<div class="flex flex-row items-center justify-center gap-4">
								<?php
								foreach ($actions as $action) :
									if (isset($action['action'])):
										// JS Controlled action
								?>
										<button
											type="button"
											id=" <?php echo $action['slug'] . '-' . $row['ID']; ?>"
											class="aap-button btn-alt btn-icon"
											aria-label="<?php echo $action['label']; ?>"
											title="<?php echo $action['label']; ?>"
											onclick="handleListViewAction(this);"
											data-verify="<?php echo $action['verify']; ?>"
											data-action="<?php echo $action['action']; ?>"
											data-record="<?php echo $row['ID']; ?>">

											<i class="w-5"><?php echo inf_get_icon($action['icon']); ?></i>
										</button>
									<?php
									else:
										// Simple link action
										$url = 'admin.php?page=' . $screen->slug . '&view=' . $action['view'] . '&ID=' . $row['ID'];
									?>
										<a
											id=" <?php echo $action['slug'] . '-' . $row['ID']; ?>"
											href="<?php echo admin_url($url); ?>"
											class="aap-button btn-alt btn-icon"
											aria-label="<?php echo $action['label']; ?>"
											title="<?php echo $action['label']; ?>">

											<i class="w-5"><?php echo inf_get_icon($action['icon']); ?></i>
										</a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</td>
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td colspan="100%">
						<div class="p-6 h-[650px] flex flex-col items-center justify-center">
							<h2 class="text-4xl font-display font-bold italic tracking-widest mb-3 text-secondary">Goose egg!!!</h2>
							<p class="font-body text-xl">Bummer... There's no records to show.</p>
						</div>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<!-- DATA BAR -->
<?php if (isset($showDataBar) && $showDataBar) $INF->infinite_data_bar($data_bar_partial_slug, $data_bar_data); ?>

<!-- PAGINATION -->
<?php $INF->infinite_pagination($total, $pages); ?>


<div id="modal-backdrop" class="mobile:hidden fixed top-0 left-0 w-full h-full z-[9999] bg-surface-500/50 flex items-start justify-center px-6 pt-[150px] pb-6 backdrop-blur-sm">

	<div id="modal-wrapper" class="w-1/3 p-6 bg-surface-800 rounded-lg border border-surface-500 shadow-inner text-white flex flex-col items-stretch justify-stretch gap-10">

		<div id="modal-title-bar" class="flex items-center justify-between gap-6">
			<h1 class="font-semibold text-white text-lg tracking-wider">Modal Title</h1>

			<button type="button" class="fill-primary w-6 hover:fill-secondary">
				<i class="w-6"><?php echo inf_get_icon('close'); ?></i>
			</button>
		</div>

		<div id="modal-content" class="leading-relaxed">
			Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Phasellus laoreet lorem vel dolor tempus vehicula. Quisque ut dolor gravida, placerat libero vel, euismod. Morbi fringilla convallis sapien, id pulvinar odio volutpat. Me non paenitet nullum festiviorem excogitasse ad hoc. Hi omnes lingua, institutis, legibus inter se differunt.
		</div>

		<div id="modal-actions" class="flex items-center justify-center gap-10">
			<button id="modal-action-cancel" type="button" class="aap-button btn-alt">
				<i class="w-6"><?php echo inf_get_icon('cancel'); ?></i>
				Cancel
			</button>
			<button id="modal-action-confirm" type="button" class="aap-button btn-primary">
				<i class="w-6"><?php echo inf_get_icon('check'); ?></i>
				Confirm
			</button>
		</div>
	</div>

</div>