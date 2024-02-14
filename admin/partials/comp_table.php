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

// TODO: Column class definitions to control width, sorting, etc.
// TODO: Add filters UI template
// TODO: Clickable record to link to expanded view of record.
// TODO: Add a "create new" button to UI - part of filters
// TODO: Dynamic "actions" nav
// TODO: Responsive styles/layout

$thCss = 'px-3 py-1 font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5';
?>

<?php if (!empty($content)) : ?>
	<!-- FILTERS -->
	<?php $this->infinite_filters($content); ?>

	<table class="w-full">
		<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group">
			<tr>
				<?php foreach ($content['cols'] as $col) : ?>
					<th class="<?php echo $thCss . ' ' . $col['colCss']; ?>"><?php echo $col['label']; ?></th>
				<?php endforeach; ?>
				<th class="<?php echo $thCss; ?>">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($content['rows'] as $row) : ?>
				<tr class="even:bg-surface-800 text-body-400 last:border-b last:border-b-secondary-100 hover:bg-surface-600">
					<?php foreach ($content['cols'] as $col) : ?>
						<td class="<?php echo $tdCss . ' ' . $col['cellCss']; ?>">
							<?php echo $row[$col['slug']]; ?>
						</td>
					<?php endforeach; ?>
					<td class="<?php echo $tdCss; ?> flex flex-row items-center justify-center gap-4">
						<span>x</span>
						<span>x</span>
						<span>x</span>
						<span>x</span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<!-- PAGINATION -->
	<?php $this->infinite_pagination($content['total'], $content['pages']); ?>

<?php else : ?>
	<div class="text-center p-6">
		<h2 class="text-4xl font-display font-bold italic tracking-widest mb-3 text-primary">Goose egg...</h2>
		<p class="font-body text-xl">Either your query has an error or you don't have any records... <em>Bummer!</em></p>
	</div>
<?php endif; ?>