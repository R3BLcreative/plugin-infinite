<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    R3bl_Wp_Admin
 * @subpackage R3bl_Wp_Admin/admin/partials
 */
?>

<header id="header-wrap" class="tw-py-10">
	<div class="tw-flex tw-flex-row tw-items-center tw-justify-start tw-gap-6">
		<img src="<?php echo plugins_url('/r3bl-wp-admin/admin/images/logo_alloy.png'); ?>" alt="" width="1703" height="704" class="tw-w-[150px] tw-h-auto" />
		<div class="tw-border-l-2 tw-border-l-surface tw-h-10"></div>
		<h1 class="tw-text-primary tw-text-5xl tw-font-body tw-font-extralight tw-tracking-widest">
			<?php echo $screen->page_title; ?>
		</h1>
	</div>
	<p class="tw-text-sm tw-text-surface tw-mt-6 tw-block tw-tracking-wider tw-w-1/2">
		<?php echo $screen->description; ?>
	</p>
</header>