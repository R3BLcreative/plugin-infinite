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

<div id="infinite-wrap" class="">
	<!-- Infinite Header -->
	<?php $this->infinite_header(); ?>

	<!-- Infinite Nav -->
	<?php $this->infinite_nav(); ?>

	<!-- Infinite Content -->
	<main id="infinite-content" class="">
		<form method="post" class="">

			<?php $this->infinite_content(); ?>

			<button type="submit" class="infinite-button btn-primary">
				<i><?php echo inf_get_icon('save'); ?></i>
				<span class="inline-block">Save</span>
			</button>
		</form>
	</main>
</div>