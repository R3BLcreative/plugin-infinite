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

$thCss = 'px-3 py-1 text-left font-semibold text-lg tracking-wider text-primary';
$tdCss = 'px-3 py-5 text-left';
?>

<table class="w-full">
	<thead class="border-b border-b-secondary-100 mobile:hidden tablet:table-header-group">
		<tr>
			<?php foreach ($cols as $col) : ?>
				<th class="<?php echo $thCss; ?>"><?php echo $col['label']; ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $row) : ?>
			<tr class="even:bg-secondary-100 even:text-secondary-800">
				<?php foreach ($cols as $col) : ?>
					<td class="<?php echo $tdCss; ?>">
						<?php echo $row[$col['slug']]; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>