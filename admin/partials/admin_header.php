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

<header id="infinite-header-wrap" class="py-5 px-3 mr-5">
	<div class="flex mobile:flex-col tablet:flex-row tablet:items-center justify-start mobile:gap-2 tablet:gap-6">
		<img src="<?php echo plugins_url('/infinite/admin/images/logo_header.png'); ?>" alt="Infinite Admin" width="1703" height="704" class="mobile:w-[50px] tablet:w-[75px] laptop:w-[100px] h-auto" />
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

<nav id="infinite-subnav" class="mr-5 border border-surface-500 bg-surface-800 flex flex-row flex-nowrap items-end justify-start gap-4 text-base text-body-50 font-body font-normal shadow-lg rounded-lg overflow-hidden px-6">
	<?php for ($i = 1; $i <= 4; $i++) : ?>
		<a href="#" class="px-6 py-4 transition-all ease-in-out opacity-50 border-t border-b border-transparent hover:border-surface-50 hover:opacity-100 hover:text-body-50">View <?php echo $i; ?></a>
	<?php endfor; ?>
</nav>