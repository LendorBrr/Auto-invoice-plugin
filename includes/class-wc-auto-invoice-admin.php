<?php
class WC_Auto_Invoice_Admin {
    public function __construct() {
        // Add necessary admin actions and filters
        add_filter('woocommerce_email_attachments', array($this, 'attach_invoice_to_email'), 10, 3);
        add_action('woocommerce_admin_order_data_after_order_details', array($this, 'display_invoice_fields'));
        add_action('woocommerce_process_shop_order_meta', array($this, 'save_invoice_fields'), 10, 2);
    }

    public function attach_invoice_to_email($attachments, $email_id, $order) {
        // Attach invoice PDF to the email
    }

    public function display_invoice_fields($order) {
        // Display invoice input fields on the order edit page
        // You can customize the HTML and CSS as needed
        ?>
        <div class="invoice-fields">
            <h4><?php _e('Invoice Details', 'woocommerce-auto-invoice'); ?></h4>
            <p class="form-field form-field-wide">
                <label for="invoice_number"><?php _e('Invoice Number', 'woocommerce-auto-invoice'); ?>:</label>
                <input type="text" id="invoice_number" name="invoice_number" value="<?php echo esc_attr(get_post_meta($order->get_id(), '_invoice_number', true)); ?>" />
            </p>
            <p class="form-field form-field-wide">
                <label for="invoice_date"><?php _e('Invoice Date', 'woocommerce-auto-invoice'); ?>:</label>
                <input type="date" id="invoice_date" name="invoice_date" value="<?php echo esc_attr(get_post_meta($order->get_id(), '_invoice_date', true)); ?>" />
            </p>
        </div>
        <?php
    }

    public function save_invoice_fields($post_id, $post) {
        // Save the input fields values when the order is updated
        if (isset($_POST['invoice_number'])) {
            update_post_meta($post_id, '_invoice_number', sanitize_text_field($_POST['invoice_number']));
        }

        if (isset($_POST['invoice_date'])) {
            update_post_meta($post_id, '_invoice_date', sanitize_text_field($_POST['invoice_date']));
        }
    }
}

