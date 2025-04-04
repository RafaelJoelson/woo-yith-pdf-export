=== Woo Orders PDF Export ===
Contributors: Rafael Joelson
Tags: woocommerce, pdf, export, orders, yith
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

A powerful tool to export selected WooCommerce orders to PDF format directly from the admin panel.

== Description ==
Woo Orders PDF Export allows you to export multiple WooCommerce orders to PDF with a single click. It features a user-friendly interface with bulk export options, detailed order information (including customer name, total, date, status, and item count), and support for custom fields from YITH WooCommerce Product Add-Ons.

== Installation ==
1. Upload the `woo-orders-pdf-export` folder to the `/wp-content/plugins/` directory.
2. Install the Dompdf library:
   - Via Composer: `composer require dompdf/dompdf`
   - Or manually: Download from https://github.com/dompdf/dompdf and place it in the plugin folder, ensuring `require_once __DIR__ . '/dompdf/autoload.inc.php';` is in the main file.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to "Export Orders PDF" in the admin menu to start exporting orders.

== Frequently Asked Questions ==
= Does this plugin require WooCommerce? =
Yes, it’s designed to work with WooCommerce 3.0 or higher.

= Can I export custom fields? =
Yes, it supports custom fields like those from YITH WooCommerce Product Add-Ons.

== Screenshots ==
1. Admin interface showing the order list with selection options.
2. Example of the generated PDF with order details.

== Changelog ==
= 1.2 =
* Improved admin interface with additional order details (date, status, item count).
* Enhanced support for YITH WooCommerce Product Add-Ons custom fields.

= 1.0 =
* Initial release with basic order export functionality.

== Upgrade Notice ==
= 1.2 =
Update for improved interface and YITH support.