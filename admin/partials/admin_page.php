<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://r3blcreative.com
 * @since      1.0.0
 *
 * @package    infinite
 * @subpackage infinite/admin/partials
 */
?>

<div id="infinite-wrap" class="">
	<!-- Header -->
	<?php $this->infinite_header(); ?>

	<!-- Nav -->
	<?php $this->infinite_nav(); ?>

	<!-- Content -->
	<main id="infinite-content" class="">
		<?php $this->infinite_content(); ?>
	</main>
</div>