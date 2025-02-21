<?php
/**
 * Instagram Feed Functionality
 *
 * @package wades
 */

/**
 * Fetch Instagram posts from the API
 */
function wades_get_instagram_posts($limit = 6) {
    // Check if we have cached data
    $cache_key = 'wades_instagram_feed';
    $cached_data = get_transient($cache_key);
    
    if (false !== $cached_data) {
        return array_slice($cached_data, 0, $limit);
    }

    $access_token = get_option('wades_instagram_access_token');
    $user_id = get_option('wades_instagram_user_id');
    $cache_time = get_option('wades_instagram_cache_time', 3600);

    if (!$access_token || !$user_id) {
        return array();
    }

    $api_url = sprintf(
        'https://graph.instagram.com/%s/media?fields=id,caption,media_type,media_url,thumbnail_url,permalink,timestamp&access_token=%s',
        $user_id,
        $access_token
    );

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return array();
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!isset($data['data']) || !is_array($data['data'])) {
        return array();
    }

    // Format the posts data
    $posts = array_map(function($post) {
        return array(
            'id' => $post['id'],
            'caption' => isset($post['caption']) ? $post['caption'] : '',
            'media_url' => $post['media_type'] === 'VIDEO' ? $post['thumbnail_url'] : $post['media_url'],
            'permalink' => $post['permalink'],
            'timestamp' => $post['timestamp'],
            'type' => $post['media_type']
        );
    }, $data['data']);

    // Cache the data
    set_transient($cache_key, $posts, $cache_time);

    return array_slice($posts, 0, $limit);
}

/**
 * Clear Instagram feed cache
 */
function wades_clear_instagram_cache() {
    delete_transient('wades_instagram_feed');
}

/**
 * Clear cache when settings are updated
 */
function wades_clear_instagram_cache_on_update($old_value, $value, $option) {
    if ($option === 'wades_instagram_access_token' || $option === 'wades_instagram_user_id') {
        wades_clear_instagram_cache();
    }
}
add_action('update_option_wades_instagram_access_token', 'wades_clear_instagram_cache_on_update', 10, 3);
add_action('update_option_wades_instagram_user_id', 'wades_clear_instagram_cache_on_update', 10, 3);

/**
 * Get relative time string
 */
function wades_get_relative_time($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = round($diff / 60);
        return $mins . 'm ago';
    } elseif ($diff < 86400) {
        $hours = round($diff / 3600);
        return $hours . 'h ago';
    } else {
        $days = round($diff / 86400);
        return $days . 'd ago';
    }
} 