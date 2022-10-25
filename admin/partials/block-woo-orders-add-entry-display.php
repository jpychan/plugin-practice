<?php

/**
 * Page to add entry to the database
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jennychan.dev
 * @since      1.0.0
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin/partials
 */

$heading = isset($entry) ? "Update Entry" : "Add Entry";
$add_url = admin_url('admin-post.php');
if (!isset($entry)) {
    $entry = array(
        'flag' => '',
        'notes' => '',
        'name' => ''
    );
}

$type = $_REQUEST['type'] ?? '';

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1><?php echo $heading ?></h1>

<div class="wrap">
    <form id="dd-fraud-add" method="post" action="<?php echo $add_url ?>">
        <?php wp_nonce_field('bwo_add_or_update_entry'); ?>
        <input type="hidden" name="action" value="bwo_add_entry" />
        <p><label for="type">Type</label><br>
            <select name="type">
                <option value="app_user_id" <?php selected($type, 'app_user_id'); ?>>App User ID</option>
                <option value="email" <?php selected($type, 'email'); ?>>Email</option>
            </select></p>
        <p><label for="entry">App User ID / Email</label><br>
            <input type="text" name="name" value="<?php echo $entry['name'] ?>"></p>
        <p><label for="flag">Flag</label><br>
            <select name="flag">
                <option value="blocked" <?php selected($entry['flag'], 'blocked'); ?>>Blocked</option>
                <option value="review" <?php selected($entry['flag'], 'review'); ?>>Review</option>
                <option value="verified" <?php selected($entry['flag'], 'verified'); ?>>Verified</option>
            </select></p>
        <p><label for="notes">Notes</label></p>
        <textarea name="notes" cols="50" rows="5"><?php echo($entry ? stripslashes($entry['notes']) : ""); ?></textarea>
        <p><input class="button button-primary" type="submit" value="<?php echo ($heading); ?>"/></p>
    </form>
</div>