<?php
/**
 * Plugin Name: ACRCloud Song Analyzer
 * Description: Analyzes uploaded songs using ACRCloud API and sends an email if the song contains copied parts.
 * Version: 1.0.1
 * Author: Muneeb Rana
 */


if (!defined('ABSPATH')) {
    exit;
}

// Define constants.
define('ACRCLOUD_API_HOST', 'https://identify-eu-west-1.acrcloud.com');
define('ACRCLOUD_ACCESS_KEY', 'your_access_key'); 
define('ACRCLOUD_ACCESS_SECRET', 'your_access_secret');
define('NOTIFICATION_EMAIL', 'example@mail.com');

/**
 * Handle file uploads and send the song for analysis.
 */
function acrcloud_handle_file_upload($file)
{

    $audio_data = file_get_contents($file['tmp_name']);

    $timestamp = time();
    $string_to_sign = "POST\n/acrcloud/v1/audios\n{$ACRCLOUD_ACCESS_KEY}\n{$timestamp}";
    wp_die($timestamp.$string_to_sign);
    $signature = base64_encode(hash_hmac('sha1', $string_to_sign, ACRCLOUD_ACCESS_SECRET, true));
    $response = wp_remote_post(ACRCLOUD_API_HOST . '/v1/identify', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => "ACRCloud {$ACRCLOUD_ACCESS_KEY}:{$signature}"
        ],
        'body' => json_encode([
            'audio' => base64_encode($audio_data),
            'timestamp' => $timestamp
        ])
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (!empty($result['status']['msg']) && $result['status']['msg'] === 'Success') {
        $is_copied = false;
        foreach ($result['metadata']['music'] as $track) {
            if (!empty($track['score']) && $track['score'] > 80) {
                $is_copied = true;
                break;
            }
        }

        if ($is_copied) {
            wp_mail(
                NOTIFICATION_EMAIL,
                __('Copied Song Detected', 'acrcloud-analyzer'),
                __('A song with copied parts was uploaded.', 'acrcloud-analyzer')
            );
        }

        return $is_copied ? __('Song contains copied parts.', 'acrcloud-analyzer') : __('Song is original.', 'acrcloud-analyzer');
    }

    return __('Failed to analyze the song.', 'acrcloud-analyzer');
}

/**
 * Handle file upload via WordPress Media Library.
 */
function acrcloud_media_upload_handler($file)
{
    $result = acrcloud_handle_file_upload($file);

    if (is_wp_error($result)) {
        wp_die($result->get_error_message());
    }

    return $file;
}
add_filter('wp_handle_upload', 'acrcloud_media_upload_handler');
