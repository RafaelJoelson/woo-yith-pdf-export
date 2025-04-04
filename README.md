# WooYITH Orders PDF Export

A powerful WordPress plugin to export selected WooCommerce orders to PDF format directly from the admin panel. This plugin provides a user-friendly interface with bulk export options, detailed order information, and support for custom fields, including YITH WooCommerce Product Add-Ons.

## Features
- Export multiple WooCommerce orders to PDF with a single click.
- Displays detailed order data, including customer name, total, date, status, and item count.
- Supports custom fields from YITH WooCommerce Product Add-Ons for enhanced order details.
- Simple and intuitive admin interface with bulk selection capabilities.
- Lightweight and compatible with the latest WooCommerce versions.

## Requirements
- **WordPress**: 5.0 or higher
- **WooCommerce**: 3.0 or higher
- **PHP**: 7.0 or higher
- **Dompdf Library**: Required for PDF generation (see Installation for details)

## Installation
1. **Download the Plugin**:
   - Clone this repository or download the ZIP file:
     ```bash
     git clone https://github.com/Rafaeljoelson/woo-yith-pdf-export.git
     ```

2. **Install Dompdf**:
   - This plugin requires the Dompdf library to generate PDFs. Install it via Composer:
     ```bash
     composer require dompdf/dompdf
     ```
   - Alternatively, download Dompdf from [its GitHub repository](https://github.com/dompdf/dompdf) and place it in the plugin folder. Ensure the autoload file is included as shown in the plugin code (`require_once __DIR__ . '/dompdf/autoload.inc.php';`).

3. **Upload the Plugin**:
   - Upload the `woo-orders-pdf-export` folder to your WordPress plugins directory (`/wp-content/plugins/`).

4. **Activate the Plugin**:
   - Go to **Plugins** in your WordPress admin panel and activate "Woo Orders PDF Export".

5. **Access the Tool**:
   - After activation, find the "Export Orders PDF" menu item in your WordPress admin sidebar.

## Usage
1. Navigate to **Export Orders PDF** in the WordPress admin menu.
2. Select the orders you want to export by checking the boxes next to each order.
3. Click the "Export Selected to PDF" button to generate and download a PDF file containing the selected orders.

## Changelog
### Version 1.2
- Improved admin interface with additional order details (date, status, item count).
- Enhanced support for YITH WooCommerce Product Add-Ons custom fields.

### Version 1.0
- Initial release with basic order export functionality.

## Contributing
Contributions are welcome! Feel free to submit pull requests or open issues on GitHub to suggest improvements or report bugs.

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m "Add your feature"`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

## License
This plugin is licensed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html), compatible with WordPress.

## Author
- **Rafael Joelson** - [GitHub Profile](https://github.com/Rafaeljoelson)

## Support
For questions or support, please open an issue on this repository or contact the author directly.
