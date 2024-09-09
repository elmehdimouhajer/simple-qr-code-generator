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
            file_put_contents($file_path, $body);

            // Upload the image to the media library
            $image_url = $upload_dir['url'] . '/qr_code_' . $post_slug . '.png';
            $attachment_url = Simple_Qr_Code_Generator_Helpers::upload_image_to_media_library($image_url, $post_id);

            return ['imageUrl' => $attachment_url];
        } catch (Exception $e) {
            error_log("QR code API request failed: " . $e->getMessage());
            return null;
        }
    }
}