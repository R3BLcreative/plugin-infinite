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
	<thead class="border-b border-b-secondary-100">
		<tr>
			<th class="mobile:hidden tablet:table-cell <?php echo $thCss; ?>">Name</th>
			<th class="mobile:hidden tablet:table-cell <?php echo $thCss; ?>">Phone</th>
			<th class="mobile:hidden tablet:table-cell <?php echo $thCss; ?>">Location</th>
			<th class="mobile:hidden tablet:table-cell <?php echo $thCss; ?>">Source</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($customers as $customer) : ?>
			<tr class="even:bg-secondary-100 even:text-secondary-800">
				<td class="font-bold text-lg tracking-wide <?php echo $tdCss; ?>">
					<?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?>
				</td>
				<td class="mobile:hidden tablet:table-cell <?php echo $tdCss; ?>">
					<?php echo $customer['primary_phone']; ?>
				</td>
				<td class="mobile:hidden tablet:table-cell <?php echo $tdCss; ?>">
					<?php echo $customer['city'] . ', ' . $customer['state']; ?>
				</td>
				<td class="mobile:hidden tablet:table-cell <?php echo $tdCss; ?>">
					<?php echo $customer['source']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>