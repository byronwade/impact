<?php
/**
 * Initialize and register blocks
 *
 * @package wades
 * @version 1.0.1
 */

// Force refresh on theme version change
define('WADES_BLOCKS_VERSION', '1.0.1');

function wades_register_blocks() {
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Get the blocks build directory path
    $blocks_dir = __DIR__ . '/build';
    
    error_log("Scanning for blocks in directory: " . $blocks_dir);
    
    // Check if directory exists
    if (!file_exists($blocks_dir)) {
        error_log("Blocks build directory does not exist: " . $blocks_dir);
        return;
    }

    // Scan for block directories
    $block_directories = glob($blocks_dir . '/*', GLOB_ONLYDIR);
    
    foreach ($block_directories as $block_dir) {
        // Check if block.json exists
        if (!file_exists($block_dir . '/block.json')) {
            error_log("block.json does not exist in: " . $block_dir);
            continue;
        }

        // Read block.json contents for debugging
        $block_json = json_decode(file_get_contents($block_dir . '/block.json'), true);
        error_log("Registering block: " . $block_json['name'] . " from: " . $block_dir);

        // Register the block
        $registered_block = register_block_type($block_dir);
        
        if (!$registered_block) {
            error_log("Failed to register block from: " . $block_dir);
        } else {
            error_log("Successfully registered block: " . $block_json['name']);
        }
    }
}
add_action('init', 'wades_register_blocks');

// Add block category with unique name to avoid conflicts
function wades_custom_block_category_filter($categories) {
    // Define our custom categories
    $custom_categories = array(
        array(
            'slug' => 'wades-blocks',
            'title' => __('⭐ Wade\'s Web Development', 'wades'),
            'description' => __('Custom blocks designed specifically for your website.', 'wades'),
            'icon' => 'star-filled',
        ),
    );

    // Define the allowed categories and their order
    $allowed_categories = array(
        'text' => __('Text', 'wades'),
        'media' => __('Media', 'wades'),
        'design' => __('Design', 'wades'),
        'widgets' => __('Widgets', 'wades'),
        'theme' => __('Theme', 'wades'),
        'embed' => __('Embeds', 'wades')
    );

    // Filter and reorder the categories
    $filtered_categories = array();
    foreach ($categories as $category) {
        if (isset($allowed_categories[$category['slug']])) {
            $filtered_categories[] = $category;
        }
    }

    // Return our custom categories first, then the filtered WordPress categories
    return array_merge(
        $custom_categories,
        $filtered_categories
    );
}

// Remove any existing filter first to prevent duplicates
remove_filter('block_categories_all', 'wades_block_categories');
remove_filter('block_categories_all', 'wades_custom_block_category_filter');

// Add our new filter
add_filter('block_categories_all', 'wades_custom_block_category_filter', 0); 