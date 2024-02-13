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

// TODO: Anything over 6 pages concats to 123...789
// TODO: Next & Prev buttons - mobile nav
// TODO: First & Last buttons - mobile nav

$inactiveCss = 'px-3 py-2 transition-colors ease-in-out hover:bg-secondary-50 hover:text-secondary-700';
$activeCss = 'px-3 py-2 bg-primary text-primary-50 pointer-events-none';
?>

<?php if ($pages > 1) : ?>
	<nav id="infinite-pagination" class="mt-10 flex flex-col items-center gap-5">
		<div class="w-fit flex flex-row items-center justify-center gap-3 border border-surface-500 bg-surface-800 text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
			<?php for ($i = $pg; $i <= ($pg + 2); $i++) : ?>
				<a href="<?php echo add_query_arg('pg', $i, get_the_permalink()); ?>" class="<?php echo ($pg == $i) ? $activeCss : $inactiveCss; ?>">
					<?php echo $i; ?>
				</a>
			<?php endfor; ?>
			<span>&hellip;</span>
			<?php for ($i = ($pages - 2); $i <= $pages; $i++) : ?>
				<a href="<?php echo add_query_arg('pg', $i, get_the_permalink()); ?>" class="<?php echo ($pg == $i) ? $activeCss : $inactiveCss; ?>">
					<?php echo $i; ?>
				</a>
			<?php endfor; ?>
		</div>

		<div class="flex flex-row items-center justify-center gap-3 text-sm text-secondary">
			<span class="flex flex-row items-center justify-start gap-3">
				<em>Page</em><strong><?php echo $pg; ?></strong>
			</span>
			<span class="flex flex-row items-center justify-start gap-3">
				<em>of</em><strong><?php echo $pages; ?></strong>
			</span>
		</div>
	</nav>
<?php endif; ?>