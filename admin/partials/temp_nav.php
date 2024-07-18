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

$inactiveCss = 'px-6 py-4 transition-all ease-in-out opacity-50 border-t border-b border-transparent hover:border-surface-50 hover:opacity-100 hover:text-body-50 focus:shadow-none focus:outline-0 cursor-pointer';
$activeCss = 'px-6 py-4 border-t border-b border-surface-50 text-body-50 focus:shadow-none focus:outline-0 cursor-pointer';
?>

<nav id="infinite-nav" class="border border-surface-500 bg-surface-800 text-base text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
	<div class="flex flex-row flex-nowrap items-stretch justify-start gap-4">
		<?php foreach ($views as $view) : if ($view->visible) : ?>
				<a href="<?php echo admin_url('admin.php?page=' . $screen->slug . '&view=' . $view->slug); ?>" class="<?php echo ((!$current && $view->main_view) || $current == $view->slug) ? $activeCss : $inactiveCss; ?>"><?php echo $view->menu_title; ?></a>
		<?php endif;
		endforeach; ?>
	</div>
</nav>