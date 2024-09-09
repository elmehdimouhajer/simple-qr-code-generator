<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Simple_Qr_Code_Generator_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		SIMPLEQRCO
 * @subpackage	Classes/Simple_Qr_Code_Generator_Helpers
 * @since		1.0.0
 */
class Simple_Qr_Code_Generator_Helpers
{

    public static function upload_image_to_media_library($image_url, $post_id)
    {
        if (empty($image_url)) {
            return new WP_Error('invalid_image_url', 'The image URL is empty.');
        }

        $upload_dir = wp_upload_dir();
        $response = wp_remote_get($image_url, array(
            'sslverify' => false,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $image_data = wp_remote_retrieve_body($response);
        $filename = basename($image_url);

        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        global $wp_filesystem;
        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        WP_Filesystem();

        if (!$wp_filesystem->put_contents($file, $image_data, FS_CHMOD_FILE)) {
            return new WP_Error('file_write_error', 'Failed to write file.');
        }

        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return wp_get_attachment_url($attach_id);
    }
}