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

$inactiveCss = 'px-6 py-4 transition-all ease-in-out opacity-50 border-t border-b border-transparent hover:border-surface-50 hover:opacity-100 hover:text-body-50 focus:shadow-none focus:outline-0';
$activeCss = 'px-6 py-4 border-t border-b border-surface-50 text-body-50 focus:shadow-none focus:outline-0';
?>

<nav id="infinite-nav" class="border border-surface-500 bg-surface-800 flex flex-row flex-nowrap items-stretch justify-between gap-4 text-base text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
	<div class="flex flex-row flex-nowrap items-stretch justify-start gap-4">
		<?php foreach ($screen->nav_items as $nav) : ?>
			<a href="<?php echo admin_url('admin.php?page=' . $screen->slug . '&view=' . $nav->slug); ?>" class="<?php echo ((!$view && $nav->main_view) || $view == $nav->slug) ? $activeCss : $inactiveCss; ?>"><?php echo $nav->menu_title; ?></a>
		<?php endforeach; ?>
	</div>
	<div class="flex flex-row flex-nowrap items-center justify-end gap-4">
		<!-- SECONDARY NAV ITEMS??? -->
	</div>
</nav>