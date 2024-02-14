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
?>

<div id="infinite-filters" class="py-10 flex flex-row flex-nowrap items-center justify-between gap-10">
	<!-- DISPLAY -->
	<form method="get" class="">
		<?php
		// Capture existing URL params if any
		foreach ($_GET as $key => $value) :
			$key = htmlspecialchars($key);
			$value = htmlspecialchars($value);
		?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		<?php endforeach; ?>
		<label for="limit" class="sr-only">Display</label>
		<input id="limit" name="limit" type="number" placeholder="00" tabindex="0" class="w-[75px]" value="<?php echo $data['limit']; ?>">
		<span class="inline-block pl-2">rows per page</span>
	</form>

	<!-- SORTING -->
	<form method="get" class="flex flex-row items-center justify-center gap-6">
		<?php
		// Capture existing URL params if any
		foreach ($_GET as $key => $value) :
			$key = htmlspecialchars($key);
			$value = htmlspecialchars($value);
		?>
			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
		<?php endforeach; ?>

		<div class="flex flex-col">
			<label for="sortby" class="sr-only">Sort By</label>
			<select id="sortby" name="sortby" tabindex="0">
				<option disabled>Sort By</option>
				<?php
				foreach ($data['cols'] as $col) :
					if ($col['sortable']) :
						$is_selected = inf_is_selected($col['slug'], 'sortby', $col['initSort']);
				?>
						<option value="<?php echo $col['slug']; ?>" <?php echo ($is_selected) ? 'selected' : ''; ?>>
							<?php echo $col['label']; ?>
						</option>
				<?php
					endif;
				endforeach;
				?>
			</select>
		</div>
		<div class="flex flex-col">
			<label for="sortdir" class="sr-only">Sort Direction</label>
			<select id="sortdir" name="sortdir" tabindex="0">
				<option disabled>Sort Direction</option>
				<option value="ASC" <?php echo (inf_is_selected('ASC', 'sortdir', true)) ? 'selected' : ''; ?>>ABC / 123</option>
				<option value="DESC" <?php echo (inf_is_selected('DESC', 'sortdir')) ? 'selected' : ''; ?>>ZXY / 987</option>
			</select>
		</div>

		<button type="submit" class="infinite-button btn-primary btn-md">
			<i><?php echo inf_get_icon('sort'); ?></i>
			<span class="mobile:hidden tablet:inline-block">Sort</span>
		</button>

	</form>

	<div class="flex flex-row items-center justify-end gap-4">
		<form method="get" class="">
			<?php
			// Capture existing URL params if any
			foreach ($_GET as $key => $value) :
				$key = htmlspecialchars($key);
				$value = htmlspecialchars($value);
			?>
				<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
			<?php endforeach; ?>
			<label for="infs" class="sr-only">Search</label>
			<input id="infs" name="infs" type="text" placeholder="Search..." tabindex="0">
		</form>
	</div>
</div>