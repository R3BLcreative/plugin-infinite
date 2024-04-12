<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    Infinite
 * @subpackage Infinite/admin/partials
 */

// [ ] Responsive styles/layout

$thCss = 'px-3 py-3 font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5';
?>
<!-- FILTERS -->
<?php $INF->infinite_filters(['limit' => $limit, 'cols' => $cols, 's' => $s, 'total' => $total]); ?>

<table class="w-full rounded-md overflow-hidden">
	<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group bg-surface-800 shadow-lg">
		<tr class="">
			<?php foreach ($cols as $col) : ?>
				<th class="<?php echo $thCss . ' ' . $col->colCss; ?>">
					<?php if ($orderby == $col->slug) : ?>
						<i class="w-4 inline-block fill-body opacity-50 align-middle"><?php echo inf_get_icon('sort'); ?></i>
					<?php endif; ?>
					<?php echo $col->label; ?>
				</th>
			<?php endforeach; ?>
			<th class="<?php echo $thCss; ?> w-[15%] text-center">Actions</th>
		</tr>
	</thead>
</table>

<script>
	const handleClick = (el) => {
		const id = el.dataset.record;
		const action = el.dataset.action;

		// Programmatically click the primary action link
		const link = document.getElementById(action + '-' + id);
		link.click();
	}
</script>

<div class="w-full h-[700px] overflow-y-scroll overflow-x-hidden border-b border-b-secondary-100">
	<table class="w-full">
		<tbody class="">
			<?php if (!empty($rows)) : foreach ($rows as $row) : ?>
					<tr class="even:bg-surface-800 text-body-400 hover:bg-surface-600 cursor-pointer">

						<!-- CONTENT -->
						<?php foreach ($cols as $col) : ?>
							<td onclick="handleClick(this);" data-record="<?php echo $row['ID']; ?>" data-action="<?php echo $primary_action; ?>" class="<?php echo $tdCss . ' ' . $col->colCss . ' ' . $col->cellCss; ?>">
								<?php echo $row[$col->slug]; ?>
							</td>
						<?php endforeach; ?>

						<!-- ACTIONS -->
						<td class="<?php echo $tdCss; ?> w-[15%]">
							<div class="flex flex-row items-center justify-center gap-4">
								<?php foreach ($actions as $action) : ?>
									<a id="<?php echo $action->slug . '-' . $row['ID']; ?>" href="<?php echo admin_url('admin.php?page=' . $screen->slug . '&view=' . $action->view . '&ID=' . $row['ID']); ?>" class="infinite-button btn-alt btn-icon" aria-label="<?php echo $action->label; ?>">
										<i class="w-5"><?php echo inf_get_icon($action->icon); ?></i>
									</a>
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

<!-- PAGINATION -->
<?php $INF->infinite_pagination($total, $pages); ?>