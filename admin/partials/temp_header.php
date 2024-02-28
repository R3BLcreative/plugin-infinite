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

<header id="infinite-header" class="py-5 px-3">
	<div class="flex mobile:flex-col tablet:flex-row tablet:items-center justify-start mobile:gap-2 tablet:gap-6">
		<i class="mobile:w-[50px] tablet:w-[75px] laptop:w-[100px] h-auto fill-primary">
			<?php echo $admin_logo; ?>
		</i>
		<div class="w-[1px] bg-surface-100 h-10 mobile:hidden tablet:block"></div>
		<h1 class="text-secondary mobile:!text-4xl laptop:!text-5xl !font-display font-extralight tracking-widest">
			<?php echo $screen->page_title; ?>
		</h1>
		<div class="w-10 bg-surface-100 h-[1px] mobile:hidden tablet:block"></div>
		<p class="text-sm text-body !font-body tracking-wider block">
			<?php echo $screen->description; ?>
		</p>
	</div>
</header>