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
        add_action('wp_ajax_generate_qr_code', array($this, 'generate_qr_code'));
    }

    /**
     * Add plugin admin menu.
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu(): void
    {
        add_menu_page(
            esc_html__('Simple QR Code Generator', 'simple-qr-code-generator'),
            esc_html__('QR Code Generator', 'simple-qr-code-generator'),
            'manage_options',
            'simple-qr-code-generator',
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-admin-generic'
        );
    }

    /**
     * Register settings.
     *
     * @since 1.0.0
     */
    public function register_settings(): void
    {
        register_setting('simple_qr_code_generator_options_group', 'simple_qr_code_generator_options', array($this, 'sanitize_callback'));

        add_settings_section(
            'simple_qr_code_generator_section',
            esc_html__('Base Settings', 'simple-qr-code-generator'),
            null,
            'simple-qr-code-generator'
        );

        add_settings_field(
            'logo_url',
            esc_html__('Logo URL', 'simple-qr-code-generator'),
            array($this, 'logo_url_callback'),
            'simple-qr-code-generator',
            'simple_qr_code_generator_section'
        );

        add_settings_field(
            'qr_size',
            esc_html__('Size', 'simple-qr-code-generator'),
            array($this, 'qr_size_callback'),
            'simple-qr-code-generator',
            'simple_qr_code_generator_section'
        );
    }

    /**
     * Sanitize callback for settings.
     *
     * @since 1.0.0
     */
    public function sanitize_callback($input)
    {
        $sanitized_input = array();
        if (isset($input['logo_url'])) {
            $sanitized_input['logo_url'] = esc_url_raw($input['logo_url']);
        }
        if (isset($input['qr_size'])) {
            $sanitized_input['qr_size'] = absint($input['qr_size']);
        }
        return $sanitized_input;
    }

    /**
     * Add meta box.
     *
     * @since 1.0.0
     */
    public function add_meta_box(): void
    {
        add_meta_box(
            'simple_qr_code_generator_meta_box',
            esc_html__('QR Code', 'simple-qr-code-generator'),
            array($this, 'meta_box_callback'),
            'post',
            'side',
            'high'
        );
    }

    /**
     * Callback for the meta box.
     *
     * @since 1.0.0
     */
    public function meta_box_callback($post): void
    {
        wp_nonce_field('simple_qr_code_generator_save_meta_box_data', 'simple_qr_code_generator_meta_box_nonce');
        $value = get_post_meta($post->ID, '_simple_qr_code_generator_meta_key', true);
        $qr_code_image = get_post_meta($post->ID, '_simple_qr_code_image', true);

        if ($post->post_status !== 'publish') {
            echo '<p>' . esc_html__('Please publish the post to generate a QR code.', 'simple-qr-code-generator') . '</p>';
        } else {
            if ($qr_code_image) {
                echo '<div><img src="' . esc_url($qr_code_image) . '" alt="QR Code" style="max-width:100%;" /></div>';
                echo '<a href="' . esc_url($qr_code_image) . '" download="qr-code.png">' . esc_html__('Download QR Code', 'simple-qr-code-generator') . '</a>';
            } else {
                echo '<button type="button" id="generate_qr_code_button" class="button">' . esc_html__('Generate QR Code', 'simple-qr-code-generator') . '</button>';
                echo '<div id="qr_code_preview"></div>';
            }
        }
    }

    /**
     * Save meta box data.
     *
     * @since 1.0.0
     */
    public function save_meta_box_data($post_id)
    {
        // Check if nonce is set
        if (!isset($_POST['simple_qr_code_generator_meta_box_nonce'])) {
            return;
        }

        // Verify the nonce
        $nonce = sanitize_text_field(wp_unslash($_POST['simple_qr_code_generator_meta_box_nonce']));
        if (!wp_verify_nonce($nonce, 'simple_qr_code_generator_save_meta_box_data')) {
            return;
        }

        // Check if the field is set
        if (isset($_POST['simple_qr_code_generator_field'])) {
            $field_value = sanitize_text_field(wp_unslash($_POST['simple_qr_code_generator_field']));
            update_post_meta($post_id, '_simple_qr_code_generator_meta_key', $field_value);
        }

        // Validate the post_id
        if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);
            // Further processing with $post_id
        }
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
        echo '<input type="text" id="logo_url" name="simple_qr_code_generator_options[logo_url]" value="' . esc_attr($logo_url) . '" />';
        echo '<button type="button" class="button" id="upload_logo_button">' . esc_html__('Upload Logo', 'simple-qr-code-generator') . '</button>';
        echo '<div id="logo_preview" style="margin-top:10px;">';
        if ( $logo_url ) {
            echo '<img src="' . esc_url($logo_url) . '" style="max-width:100px;" />';
        }
        echo '</div>';
    }

    /**
     * Callback for QR Size field.
     *
     * @since 1.0.0
     */
    public function qr_size_callback(): void
    {
        $options = get_option('simple_qr_code_generator_options');
        $qr_size = esc_attr($options['qr_size'] ?? '');
        echo '<input type="text" id="qr_size" name="simple_qr_code_generator_options[qr_size]" value="' . esc_attr($qr_size) . '" />';
        echo '<p class="description">' . esc_html__('Enter the size of the QR code in pixels.', 'simple-qr-code-generator') . '</p>';
    }

    /**
     * Generate QR code via AJAX.
     *
     * @since 1.0.0
     */
    public function generate_qr_code(): void
    {
        check_ajax_referer('simple_qr_code_generator_nonce', 'nonce');
        $options = get_option('simple_qr_code_generator_options');
        if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);
            $post_url = get_permalink($post_id);
        } else {
            wp_send_json_error('Invalid post ID.');
        }
        $qr_size = esc_attr($options['qr_size'] ?? 300);
        $logo_url = esc_url($options['logo_url'] ?? '');
        $api = new Simple_Qr_Code_Generator_API();
        $qr_data = [
            'data' => $post_url,
            'config' => [
                'logoMode' => 'clean',
                'body' => 'circle',
                'logo' => "$logo_url",
            ],
            'size' => $qr_size,
            'download' => false,
            'file' => 'png'
        ];

        // Ensure both arguments are passed
        $qr_code = $api->generate_qr_code($qr_data, $post_id);

        if (!empty($qr_code['imageUrl'])) {
            $qr_code_url = Simple_Qr_Code_Generator_Helpers::upload_image_to_media_library($qr_code['imageUrl'], $post_id);
            update_post_meta($post_id, '_simple_qr_code_image', $qr_code_url);
            error_log("QR code generated successfully: $qr_code_url");
            wp_send_json_success(['imageUrl' => $qr_code_url]);
        } else {
            error_log("QR code generation failed. Response: " . print_r($qr_code, true));
            wp_send_json_error('QR code generation failed.');
        }
    }

    /**
     * Enqueue media uploader scripts.
     *
     * @since 1.0.0
     */
    public function enqueue_media_uploader(): void
    {
        wp_enqueue_media();
        wp_enqueue_script('simple-qr-code-generator-admin', SIMPLEQRCO_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), SIMPLEQRCO_VERSION, true);
        wp_localize_script('simple-qr-code-generator-admin', 'SimpleQrCodeGenerator', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('simple_qr_code_generator_nonce'),
            'download_text' => esc_html__('Download QR Code', 'simple-qr-code-generator'),
            'regenerate_text' => esc_html__('Regenerate QR Code', 'simple-qr-code-generator')
        ));
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     */
    public function display_plugin_admin_page(): void
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(esc_html__('Sorry, you are not allowed to access this page.', 'simple-qr-code-generator'));
        }
        include_once( SIMPLEQRCO_PLUGIN_DIR . 'admin/views/settings-page.php' );
    }
}

new Simple_Qr_Code_Generator_Admin();