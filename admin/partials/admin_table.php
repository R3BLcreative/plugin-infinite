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

// TODO: Need to add pagination to this - pagination should be a separate partial include/function
// TODO: Responsive styles
// TODO: Column class definitions to control width, sorting, etc.
// TODO: Add actions menu/column
// TODO: Add clickable row to link to record item view

$thCss = 'px-3 py-1 text-left font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5 text-left';
?>

<?php if (!empty($content)) : ?>
	<table class="w-full">
		<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group">
			<tr>
				<?php foreach ($content['cols'] as $col) : ?>
					<th class="<?php echo $thCss; ?>"><?php echo $col['label']; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($content['rows'] as $row) : ?>
				<tr class="even:bg-secondary-100 even:text-secondary-800">
					<?php foreach ($content['cols'] as $col) : ?>
						<td class="<?php echo $tdCss; ?>">
							<?php echo $row[$col['slug']]; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<div class="text-center p-6">
		<h2 class="text-4xl font-display font-bold italic tracking-widest mb-3 text-primary">Goose egg...</h2>
		<p class="font-body text-xl">Either your query has an error or you don't have any records... <em>Bummer!</em></p>
	</div>
<?php endif; ?>