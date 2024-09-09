<?php
/**
 * Simple QR Code Generator
 *
 * @package       SIMPLEQRCO
 * @author        El Mehdi Mouhajer
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Simple QR Code Generator
 * Plugin URI:    https://github.com/elmehdimouhajer/simpleQrCodeGenrerator
 * Description:   A simple WordPress plugin to generate QR codes for your posts and pages using QR Code Monkey API.
 * Version:       1.0.0
 * Author:        El Mehdi Mouhajer
 * Author URI:    https://linkedin.com/in/elmehdimouhajer
 * Text Domain:   simple-qr-code-generator
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Simple QR Code Generator. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function SIMPLEQRCO() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'SIMPLEQRCO_NAME',			'Simple QR Code Generator' );

// Plugin version
define( 'SIMPLEQRCO_VERSION',		'1.0.0' );

// Plugin Root File
define( 'SIMPLEQRCO_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'SIMPLEQRCO_PLUGIN_BASE',	plugin_basename( SIMPLEQRCO_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'SIMPLEQRCO_PLUGIN_DIR',	plugin_dir_path( SIMPLEQRCO_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'SIMPLEQRCO_PLUGIN_URL',	plugin_dir_url( SIMPLEQRCO_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once SIMPLEQRCO_PLUGIN_DIR . 'core/class-simple-qr-code-generator.php';
require_once SIMPLEQRCO_PLUGIN_DIR . 'vendor/autoload.php';
require_once SIMPLEQRCO_PLUGIN_DIR . 'admin/class-simple-qr-code-generator-admin.php';
/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  El Mehdi Mouhajer
 * @since   1.0.0
 * @return  object|Simple_Qr_Code_Generator
 */
function SIMPLEQRCO() {
	return Simple_Qr_Code_Generator::instance();
}

SIMPLEQRCO();
