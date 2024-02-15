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

// TODO: Clickable record to link to expanded view of record.
// TODO: Dynamic "actions" nav
// TODO: Responsive styles/layout

$thCss = 'px-3 py-3 font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5';
?>
<!-- FILTERS -->
<?php $this->infinite_filters($content); ?>

<table class="w-full rounded-md overflow-hidden">
	<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group bg-surface-800 shadow-lg">
		<tr class="">
			<?php foreach ($content['cols'] as $col) : ?>
				<th class="<?php echo $thCss . ' ' . $col->colCss; ?>"><?php echo $col->label; ?></th>
			<?php endforeach; ?>
			<th class="<?php echo $thCss; ?> w-[15%] text-center">Actions</th>
		</tr>
	</thead>
</table>

<div class="w-full h-[700px] overflow-y-scroll overflow-x-hidden border-b border-b-secondary-100">
	<table class="w-full">
		<tbody class="">
			<?php if (!empty($content['rows'])) : foreach ($content['rows'] as $row) : ?>
					<tr onclick="console.log('CLICKED')" class="even:bg-surface-800 text-body-400 hover:bg-surface-600 cursor-pointer">
						<?php foreach ($content['cols'] as $col) : ?>
							<td class="<?php echo $tdCss . ' ' . $col->colCss . ' ' . $col->cellCss; ?>">
								<?php echo $row[$col->slug]; ?>
							</td>
						<?php endforeach; ?>
						<td class="<?php echo $tdCss; ?> w-[15%]">
							<div class="flex flex-row items-center justify-center gap-4">
								<span>x</span>
								<span>x</span>
								<span>x</span>
								<span>x</span>
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
<?php $this->infinite_pagination($content['total'], $content['pages']); ?>