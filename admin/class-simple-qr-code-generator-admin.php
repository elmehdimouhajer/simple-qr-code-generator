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
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media_uploader' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
    }

    /**
     * Callback for the meta box.
     *
     * @since 1.0.0
     */
    public function add_meta_box(): void
    {
        add_meta_box(
            'simple_qr_code_generator_meta_box',
            __( 'QR Code Settings', 'simple-qr-code-generator' ),
            array( $this, 'meta_box_callback' ),
            'post',
            'side',
            'high'
        );
    }

    /**
     * Save meta box data.
     *
     * @since 1.0.0
     */
    public function meta_box_callback( $post ): void
    {
        wp_nonce_field( 'simple_qr_code_generator_save_meta_box_data', 'simple_qr_code_generator_meta_box_nonce' );
        $value = get_post_meta( $post->ID, '_simple_qr_code_generator_meta_key', true );
        echo '<label for="simple_qr_code_generator_field">';
        _e( 'QR Code URL:', 'simple-qr-code-generator' );
        echo '</label> ';
        echo '<input type="text" id="simple_qr_code_generator_field" name="simple_qr_code_generator_field" value="' . esc_attr( $value ) . '" size="25" />';
    }
    public function save_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['simple_qr_code_generator_meta_box_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['simple_qr_code_generator_meta_box_nonce'], 'simple_qr_code_generator_save_meta_box_data' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        $new_value = sanitize_text_field( $_POST['simple_qr_code_generator_field'] );
        update_post_meta( $post_id, '_simple_qr_code_generator_meta_key', $new_value );
    }


    /**
     * Add options page.
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu(): void
    {
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
     * Register settings.
     *
     * @since 1.0.0
     */
    public function register_settings(): void
    {
        register_setting( 'simple_qr_code_generator_options_group', 'simple_qr_code_generator_options', 'sanitize_callback' );

        add_settings_section(
            'simple_qr_code_generator_section',
            __( 'Base Settings', 'simple-qr-code-generator' ),
            null,
            'simple-qr-code-generator'
        );

        add_settings_field(
            'logo_url',
            __( 'Logo URL', 'simple-qr-code-generator' ),
            array( $this, 'logo_url_callback' ),
            'simple-qr-code-generator',
            'simple_qr_code_generator_section'
        );

        add_settings_field(
            'qr_size',
            __( 'Size', 'simple-qr-code-generator' ),
            array( $this, 'qr_size_callback' ),
            'simple-qr-code-generator',
            'simple_qr_code_generator_section'
        );
    }

    /**
     * Enqueue media uploader scripts.
     *
     * @since 1.0.0
     */
    public function enqueue_media_uploader(): void
    {
        wp_enqueue_media();
        wp_enqueue_script( 'simple-qr-code-generator-admin', SIMPLEQRCO_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery' ), SIMPLEQRCO_VERSION, true );
    }

    /**
     * Callback for Logo URL field.
     *
     * @since 1.0.0
     */
    public function logo_url_callback(): void
    {
        $options = get_option( 'simple_qr_code_generator_options' );
        $logo_url = esc_attr( $options['logo_url'] ?? '' );
        echo '<input type="text" id="logo_url" name="simple_qr_code_generator_options[logo_url]" value="' . $logo_url . '" />';
        echo '<button type="button" class="button" id="upload_logo_button">' . __( 'Upload Logo', 'simple-qr-code-generator' ) . '</button>';
        echo '<div id="logo_preview" style="margin-top:10px;">';
        if ( $logo_url ) {
            echo '<img src="' . $logo_url . '" style="max-width:100px;" />';
        }
        echo '</div>';
    }

    /**
     * Callback for QR Size field.
     *
     * @since 1.0.0
     */
    public  function qr_size_callback(): void
    {
        $options = get_option( 'simple_qr_code_generator_options' );
        $qr_size = esc_attr( $options['qr_size'] ?? '' );
        echo '<input type="text" id="qr_size" name="simple_qr_code_generator_options[qr_size]" value="' . $qr_size . '" />';
        echo '<p class="description">' . __( 'Enter the size of the QR code in pixels.', 'simple-qr-code-generator' ) . '</p>';
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     */
    public function display_plugin_admin_page(): void
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you are not allowed to access this page.', 'simple-qr-code-generator' ) );
        }
        include_once( SIMPLEQRCO_PLUGIN_DIR . 'admin/views/settings-page.php' );
    }
}

new Simple_Qr_Code_Generator_Admin();