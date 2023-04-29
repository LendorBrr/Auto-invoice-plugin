<?php
/*
Plugin Name: WooCommerce Auto Invoice
Description: Automatically generates invoice PDF after order completion and allows admin to download and send it to customers.
Version: 1.0
Author: Your Name
Author URI: Your Website
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if WooCommerce is active
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    // Include necessary libraries and files
    require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-auto-invoice.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-auto-invoice-admin.php';

    // Initialize the plugin
    function wc_auto_invoice() {
        return WooCommerce_Auto_Invoice::instance();
    }

    // Run the plugin
    add_action('plugins_loaded', 'wc_auto_invoice');
}

public function __construct() {
    new WC_Auto_Invoice_Admin();
    $this->init_hooks();
}

