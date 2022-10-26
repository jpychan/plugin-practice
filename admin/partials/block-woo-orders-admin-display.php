<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jennychan.dev
 * @since      1.0.0
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">

        <?php
        // security
        settings_fields('bwo-settings-page-options-group');

        // display sections
        do_settings_sections('bwo-settings-page');
        ?>
        <?php submit_button(); ?>
    </form>
</div>