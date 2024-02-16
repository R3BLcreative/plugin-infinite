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

$inactiveCss = 'px-3 py-2 transition-colors ease-in-out text-body fill-body hover:bg-secondary-50 hover:text-secondary-700 hover:fill-secondary-700 h-full flex items-center justify-center';
$activeCss = 'px-3 py-2 bg-primary text-primary-50 fill-primary-50 pointer-events-none h-full flex items-center justify-center';
$disabledCss = 'px-3 py-2 opacity-10 text-body fill-body pointer-events-none h-full flex items-center justify-center';

// Pagination vars
if ($pages <= 5) {
	$min = 1;
	$max = $pages;
} elseif ($pg <= 3) {
	$min = 1;
	$max = 5;
} elseif ($pg > 3 && $pg < ($pages - 2)) {
	$min = $pg - 2;
	$max = $pg + 2;
} elseif ($pg >= ($pages - 2)) {
	$min = $pages - 4;
	$max = $pages;
}
?>

<nav id="infinite-pagination" class="mt-10 flex flex-col items-center gap-5">
	<div class="w-fit flex flex-row items-stretch justify-center gap-3">

		<!-- SIMPLE NAV - LEFT -->
		<div class="w-fit flex flex-row items-center justify-center gap-3 border border-surface-500 bg-surface-800 text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
			<a href="<?php echo add_query_arg('pg', 1, get_the_permalink()); ?>" class="<?php echo ($pg == 1) ? $disabledCss : $inactiveCss; ?>" aria-label="Go to first page">
				<i class="w-5 block"><?php echo inf_get_icon('chevrons'); ?></i>
			</a>
			<a href="<?php echo add_query_arg('pg', $pg - 1, get_the_permalink()); ?>" class="<?php echo ($pg == 1) ? $disabledCss : $inactiveCss; ?>" aria-label="Go to previous page">
				<i class="w-5 block"><?php echo inf_get_icon('chevron'); ?></i>
			</a>
		</div>

		<!-- PAGED NAV -->
		<div class="tablet:hidden w-fit flex flex-row items-center justify-center gap-3 border border-surface-500 bg-surface-800 text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
			<?php for ($i = $min; $i <= $max; $i++) : ?>
				<a href="<?php echo add_query_arg('pg', $i, get_the_permalink()); ?>" class="<?php echo ($pg == $i) ? $activeCss : $inactiveCss; ?>">
					<?php echo $i; ?>
				</a>
			<?php endfor; ?>
		</div>

		<!-- SIMPLE NAV - RIGHT -->
		<div class="w-fit flex flex-row items-center justify-center gap-3 border border-surface-500 bg-surface-800 text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
			<a href="<?php echo add_query_arg('pg', $pg + 1, get_the_permalink()); ?>" class="<?php echo ($pg == $pages) ? $disabledCss : $inactiveCss; ?>" aria-label="Go to next page">
				<i class="w-5 block rotate-180"><?php echo inf_get_icon('chevron'); ?></i>
			</a>
			<a href="<?php echo add_query_arg('pg', $pages, get_the_permalink()); ?>" class="<?php echo ($pg == $pages) ? $disabledCss : $inactiveCss; ?>" aria-label="Go to last page">
				<i class="w-5 block rotate-180"><?php echo inf_get_icon('chevrons'); ?></i>
			</a>
		</div>

	</div>

	<!-- PAGE INFO -->
	<div class="flex flex-row items-center justify-center gap-3 text-sm text-secondary">
		<span class="flex flex-row items-center justify-start gap-3">
			<em>Page</em><strong><?php echo $pg; ?></strong>
		</span>
		<span class="flex flex-row items-center justify-start gap-3">
			<em>of</em><strong><?php echo $pages; ?></strong>
		</span>
	</div>

</nav>