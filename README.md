=== Block Woocommerce Orders === 

Contributors: Jenny Chan  
Donate link: https://jennychan.dev  
Tags: comments, spam  
Requires at least: 3.0.1  
Tested up to: 6.0  
Stable tag: 6.0  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

This plugin blocks Woocommerce orders based on the order email and the `app_user_id` (custom field).
This plugin uses the [Plugin Boilerplate](https://wppb.me/).

== Description ==

As e-commerce stores may encounter fraud orders, this plugin allows you to enter in known bad actors based on their emails and a custom field (`app_user_id`) in this case.
When a Woocommerce order is submitted, the plugin checks the order email to see if it's in the block list in the database.
If a matching email/app_user_id is found, then the order is blocked and payment will not be processed.

The list of order emails and app_user_ids are stored in a custom database table and listed by extending the `WP_List_Table` class.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `block-woo-orders.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress