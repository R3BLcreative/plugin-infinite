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

<header id="header-wrap" class="py-5 px-3 mr-5 border-b border-b-surface-100 shadow-lg">
	<div class="flex mobile:flex-col tablet:flex-row tablet:items-center justify-start mobile:gap-2 tablet:gap-6">
		<img src="<?php echo plugins_url('/r3bl-wp-admin/admin/images/logo_header.png'); ?>" alt="Infinite Admin" width="1703" height="704" class="mobile:w-[50px] tablet:w-[75px] laptop:w-[100px] h-auto" />
		<div class="w-[1px] bg-surface-100 h-10 mobile:hidden tablet:block"></div>
		<h1 class="text-secondary mobile:!text-4xl laptop:!text-5xl font-body font-extralight tracking-widest">
			<?php echo $screen->page_title; ?>
		</h1>
	</div>
	<p class="text-sm text-body mt-6 block tracking-wider mobile:w-full laptop:w-1/2">
		<?php echo $screen->description; ?>
	</p>
</header>