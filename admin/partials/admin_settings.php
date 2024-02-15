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

$nonce = wp_create_nonce('customers_seeder_nonce');
$link = admin_url('admin-ajax.php?action=customers_db_seeder&nonce=' . $nonce);
?>

<div id="infinite-wrap" class="">
	<!-- Infinite Header -->
	<?php $this->infinite_header(); ?>

	<!-- Infinite Nav -->
	<?php $this->infinite_nav(); ?>

	<!-- Infinite Content -->
	<main id="infinite-content" class="">
		<a href="<?php echo $link; ?>" data-nonce="<?php echo $nonce; ?>" class="infinite-button btn-primary">Seed the DB</a>
	</main>
</div>