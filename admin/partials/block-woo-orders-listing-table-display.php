<?php

/**
 * Provide the listing table display for the email and app user id entries
 *
 * @link       https://jennychan.dev
 * @since      1.0.0
 *
 * @package    Block_Woo_Orders
 * @subpackage Block_Woo_Orders/admin/partials
 */

if ( isset( $_GET['deleted'] ) && $_GET['deleted'] == 1 ) { ?>
    <div class="notice notice-success is-dismissible"><p><?php _e( 'Entry Deleted', 'block-woo-orders' ); ?>.</p></div>
<?php } ?>

<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>
    <div>
        <form id="bigo_id" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			<?php
			$this->listing_table->views();
			$this->listing_table->search_box( __( 'Find', 'block-woo-orders' ), 'block-woo-orders-search' );
			$this->listing_table->display();
			?>
        </form>
    </div>
</div>
