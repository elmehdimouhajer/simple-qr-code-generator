<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Simple_Qr_Code_Generator_Admin
 *
 * This class handles the admin functionality of the plugin.
 *
 * @package SIMPLEQRCO
 * @subpackage Admin
 * @since 1.0.0
 */
class Simple_Qr_Code_Generator_Admin {

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
    }

    /**
     * Add options page.
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __( 'Simple QR Code Generator Settings', 'simple-qr-code-generator' ),
            __( 'QR Code Settings', 'simple-qr-code-generator' ),
            'manage_options',
            'simple-qr-code-generator',
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-admin-generic',
            26
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( SIMPLEQRCO_PLUGIN_DIR . 'admin/views/settings-page.php' );
    }
}

new Simple_Qr_Code_Generator_Admin();