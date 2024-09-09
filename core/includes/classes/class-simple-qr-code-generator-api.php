<?php

use GuzzleHttp\Client;

class Simple_Qr_Code_Generator_API
{

    private $client;
    private $api_url = 'https://api.qrcode-monkey.com/qr/custom';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function generate_qr_code($data, $post_id)
    {
        try {
            $response = $this->client->post($this->api_url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            error_log("QR code API response received.");

            // Retrieve the post slug
            $post = get_post($post_id);
            $post_slug = $post->post_name;

            // Save the binary data to a file with the post slug or post ID
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir['path'] . '/qr_code_' . $post_slug . '.png';

            // Initialize the WP_Filesystem
            global $wp_filesystem;
            if (!function_exists('WP_Filesystem')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            WP_Filesystem();

            if ($wp_filesystem->put_contents($file_path, $body, FS_CHMOD_FILE)) {
                // File written successfully
                // Upload the image to the media library
                $image_url = $upload_dir['url'] . '/qr_code_' . $post_slug . '.png';
                $attachment_url = Simple_Qr_Code_Generator_Helpers::upload_image_to_media_library($image_url, $post_id);

                return ['imageUrl' => $attachment_url];
            } else {
                // Handle error
                error_log("Failed to write QR code file.");
                return null;
            }
        } catch (Exception $e) {
            error_log("QR code API request failed: " . $e->getMessage());
            return null;
        }
    }
}