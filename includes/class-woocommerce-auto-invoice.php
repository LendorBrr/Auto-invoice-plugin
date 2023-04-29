<?php
class WooCommerce_Auto_Invoice {
    protected static $instance = null;

    public function __construct() {
        $this->init_hooks();
    }

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init_hooks() {
        // Hook into necessary actions and filters
        add_action('woocommerce_order_status_completed', array($this, 'generate_invoice'));
        add_filter('woocommerce_admin_order_actions', array($this, 'add_download_invoice_action'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function generate_invoice($order_id) {
        // Generate invoice PDF and store it
    }

    public function add_download_invoice_action($actions, $order) {
        // Add the 'Download Invoice' action button
    }

    public function enqueue_admin_scripts() {
        // Enqueue admin styles and scripts
    }
}

require_once plugin_dir_path(__FILE__) . 'tcpdf/tcpdf.php';

public function generate_invoice($order_id) {
    // Load the order
    $order = wc_get_order($order_id);
    $order_items = $order->get_items();

    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Invoice ' . $order_id);

    // Set header and footer data
    // You can customize the header and footer as needed
    $pdf->setHeaderData('', '', 'Your Company Name', 'Your Company Address');
    $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0));

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default font settings
    $pdf->SetFont('helvetica', '', 10);

    // Add a page
    $pdf->AddPage();

    // Create the invoice HTML
    // You can customize the HTML and CSS as needed
    $html = '...';

    // Generate order items HTML
$order_items_html = '';

foreach ($order_items as $item_id => $item) {
    $product = $item->get_product();
    $sku = $product ? $product->get_sku() : '-';
    $item_total = wc_price($order->get_line_total($item, true));

    $order_items_html .= "<tr>
        <td>{$item->get_name()}</td>
        <td>{$sku}</td>
        <td>{$item->get_quantity()}</td>
        <td>{$item_total}</td>
    </tr>";
}
// Create the invoice HTML
$html = '
    <h2>Invoice</h2>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            ' . $order_items_html . '
        </tbody>
    </table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Set the PDF file name
    $filename = 'invoice-' . $order_id . '.pdf';

    // Save the PDF to a temporary file
    $temp_file = tempnam(sys_get_temp_dir(), 'pdf');
    $pdf->Output($temp_file, 'F');

    // Store the PDF as an attachment to the order
    $attachment_id = wp_insert_attachment(array(
        'guid' => wp_upload_dir()['url'] . '/' . basename($filename),
        'post_mime_type' => 'application/pdf',
        'post_title' => $filename,
        'post_content' => '',
        'post_status' => 'inherit',
    ), $temp_file, $order_id);

    // Set the attachment as the order invoice
    update_post_meta($order_id, '_invoice_attachment', $attachment_id);
}

public function attach_invoice_to_email($attachments, $email_id, $order) {
    // Check if it's the order completion email
    if ('customer_completed_order' === $email_id) {
        // Get the invoice attachment ID
        $attachment_id = get_post_meta($order->get_id(), '_invoice_attachment', true);

        // Get the attachment file path
        $attachment_path = get_attached_file($attachment_id);

        // Add the attachment to the email
        if ($attachment_path) {
            $attachments[] = $attachment_path;
        }
    }

   


