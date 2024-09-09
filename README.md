# Simple QR Code Generator

A simple WordPress plugin to generate QR codes for your posts and pages using QR Code Monkey API.

## Description

The Simple QR Code Generator plugin allows you to easily generate QR codes for your WordPress posts and pages. The QR
codes can be customized with a logo and size.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/simple-qr-code-generator` directory, or install the plugin
   through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->QR Code Settings screen to configure the plugin.

## Usage

1. After activation, a new meta box will appear on the post edit screen.
2. Enter the URL you want to generate a QR code for in the meta box.
3. Save the post to generate and display the QR code.

## Files

### Main Plugin File: `simple-qr-code-generator.php`

This file contains the main information about the plugin and registers all components necessary to run the plugin.

### Admin Class File: `admin/class-simple-qr-code-generator-admin.php`

This file handles the admin functionality of the plugin, including adding meta boxes and saving meta box data.

### Core Class File: `core/class-simple-qr-code-generator.php`

This file contains the core functionality of the plugin.

### JavaScript File: `admin/js/admin.js`

This file contains JavaScript code to handle the media uploader for the logo.

### Settings Page: `admin/views/settings-page.php`

This file contains the HTML for the plugin's settings page.

## Changelog

### 1.0.0

* Initial release.

## License

This plugin is licensed under the GPLv2. See the [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) file for more
details.

## Author

* **El Mehdi Mouhajer** - [LinkedIn](https://linkedin.com/in/elmehdimouhajer)