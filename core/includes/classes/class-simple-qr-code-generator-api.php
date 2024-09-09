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

    public function generate_qr_code($data)
    {
        try {
            $response = $this->client->post($this->api_url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            error_log("QR code API response received.");

            // Save the binary data to a file
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir['path'] . '/qr_code.png';
            file_put_contents($file_path, $body);

            return ['imageUrl' => $upload_dir['url'] . '/qr_code.png'];
        } catch (Exception $e) {
            error_log("QR code API request failed: " . $e->getMessage());
            return null;
        }
    }
}