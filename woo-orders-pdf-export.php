<?php
/**
 * Plugin Name: Woo Orders PDF Export
 * Plugin URI: https://github.com/[your-username]/woo-orders-pdf-export
 * Description: A powerful tool to export selected WooCommerce orders to PDF format directly from the admin panel. Features a user-friendly interface with bulk export options, detailed order information, and support for custom fields like YITH WooCommerce Product Add-Ons.
 * Version: 1.2
 * Author: Rafael Joelson
 * Author URI: https://github.com/Rafaeljoelson
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: woo-orders-pdf-export
 * Domain Path: /languages
 */

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('ABSPATH')) exit;

require_once __DIR__ . '/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Load text domain for translations
add_action('plugins_loaded', function() {
    load_plugin_textdomain('woo-orders-pdf-export', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Add menu in admin
add_action('admin_menu', function () {
    add_menu_page(
        __('Export Orders PDF', 'woo-orders-pdf-export'), // Page title
        __('Export Orders PDF', 'woo-orders-pdf-export'), // Menu title
        'manage_woocommerce',
        'export-orders-pdf',
        'wped_pdf_admin_page',
        'dashicons-media-document',
        56
    );
});

// Admin page with orders
function wped_pdf_admin_page() {
    $orders = wc_get_orders(['limit' => 50, 'orderby' => 'date', 'order' => 'DESC']);

    ?>
    <div class="wrap">
        <h1><?php _e('Export Orders to PDF', 'woo-orders-pdf-export'); ?></h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="export_selected_orders_pdf">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 30px;"><input type="checkbox" id="select-all"></th>
                        <th style="width: 80px;"><?php _e('ID', 'woo-orders-pdf-export'); ?></th>
                        <th><?php _e('Customer', 'woo-orders-pdf-export'); ?></th>
                        <th style="width: 150px;"><?php _e('Date', 'woo-orders-pdf-export'); ?></th>
                        <th style="width: 120px;"><?php _e('Status', 'woo-orders-pdf-export'); ?></th>
                        <th style="width: 80px;"><?php _e('Items', 'woo-orders-pdf-export'); ?></th>
                        <th style="width: 100px;"><?php _e('Total', 'woo-orders-pdf-export'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><input type="checkbox" name="order_ids[]" value="<?php echo esc_attr($order->get_id()); ?>"></td>
                            <td><?php echo esc_html($order->get_id()); ?></td>
                            <td><?php echo esc_html($order->get_formatted_billing_full_name()); ?></td>
                            <td><?php echo esc_html($order->get_date_created()->date('d/m/Y H:i')); ?></td>
                            <td><?php echo wc_get_order_status_name($order->get_status()); ?></td>
                            <td><?php echo esc_html($order->get_item_count()); ?></td>
                            <td><?php echo wc_price($order->get_total()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top: 15px;">
                <input type="submit" class="button button-primary" value="<?php _e('Export Selected to PDF', 'woo-orders-pdf-export'); ?>">
            </p>
        </form>
    </div>

    <style>
        .wp-list-table th, .wp-list-table td {
            vertical-align: middle;
        }
        .wp-list-table th {
            font-weight: bold;
        }
        .wp-list-table td {
            padding: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('select-all').addEventListener('click', function(e) {
                document.querySelectorAll('input[name="order_ids[]"]').forEach(cb => cb.checked = e.target.checked);
            });
        });
    </script>
    <?php
}

// Process export
add_action('admin_post_export_selected_orders_pdf', 'wped_handle_export_pdf');
function wped_handle_export_pdf() {
    if (!current_user_can('manage_woocommerce')) wp_die(__('No permission.', 'woo-orders-pdf-export'));
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) wp_die(__('No orders selected.', 'woo-orders-pdf-export'));

    $order_ids = array_map('intval', $_POST['order_ids']);
    
    // Simple log test
    error_log("Starting export for orders: " . implode(', ', $order_ids));

    $html = "<html><head><style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
    </style></head><body>";
    $html .= "<h1>" . __('Orders Export', 'woo-orders-pdf-export') . "</h1>";

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if (!$order) continue;

        $html .= '<h2>' . sprintf(__('Order #%s - %s', 'woo-orders-pdf-export'), $order->get_id(), $order->get_formatted_billing_full_name()) . '</h2>';
        $html .= '<table><thead><tr><th>' . __('Product', 'woo-orders-pdf-export') . '</th><th>' . __('Qty', 'woo-orders-pdf-export') . '</th><th>' . __('Total', 'woo-orders-pdf-export') . '</th><th>' . __('Add-ons', 'woo-orders-pdf-export') . '</th></tr></thead><tbody>';

        foreach ($order->get_items() as $item_id => $item) {
            $product_name = $item->get_name();
            $quantity = $item->get_quantity();
            $total = $item->get_total();
        
            // Add-ons (YITH)
            $addons_html = '';
        
            // Get all item meta data
            $all_meta = $item->get_meta_data();
        
            // First, try individual YITH meta keys
            foreach ($all_meta as $meta) {
                $key = $meta->key;
                $value = $meta->value;
        
                // Check if the key is a YITH add-on
                if (strpos($key, 'ywapo-addon-') === 0) {
                    $addons_html .= esc_html($value); // Display value (e.g., "Option 2", "yellow")
                    
                    // Look for additional price, if it exists
                    $price_key = $key . '_price';
                    $price = $item->get_meta($price_key, true);
                    if ($price) {
                        $addons_html .= ' (+' . wc_price($price) . ')';
                    }
                    $addons_html .= '<br>';
                }
            }
        
            // If no individual add-ons found, try _ywapo_meta_data
            if (empty($addons_html)) {
                $yith_data = $item->get_meta('_ywapo_meta_data', true);
                if (!empty($yith_data) && is_array($yith_data)) {
                    foreach ($yith_data as $addon) {
                        $addon_name = isset($addon['name']) ? $addon['name'] : '';
                        $addon_value = isset($addon['value']) ? $addon['value'] : '';
                        $addon_price = isset($addon['price']) ? $addon['price'] : '';
                        if ($addon_name || $addon_value) {
                            $addons_html .= esc_html($addon_name ? $addon_name . ': ' : '') . esc_html($addon_value);
                            if ($addon_price) {
                                $addons_html .= ' (+' . wc_price($addon_price) . ')';
                            }
                            $addons_html .= '<br>';
                        }
                    }
                }
            }
        
            // Fallback: show all meta data if no add-ons found
            if (empty($addons_html)) {
                $meta_debug = '';
                foreach ($all_meta as $meta) {
                    $meta_debug .= esc_html($meta->key) . ': ' . esc_html(is_array($meta->value) ? print_r($meta->value, true) : $meta->value) . '<br>';
                }
                $addons_html = __('No add-ons found.', 'woo-orders-pdf-export') . '<br><strong>' . __('Debug Meta Data:', 'woo-orders-pdf-export') . '</strong><br>' . $meta_debug;
            }
        
            $html .= '<tr>';
            $html .= '<td>' . esc_html($product_name) . '</td>';
            $html .= '<td>' . esc_html($quantity) . '</td>';
            $html .= '<td>' . wc_price($total) . '</td>';
            $html .= '<td>' . $addons_html . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
    }

    $html .= "</body></html>";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=orders.pdf");
    echo $dompdf->output();
    exit;
}