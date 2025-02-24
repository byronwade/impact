<?php
/**
 * Meta Boxes for Boats Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Debug function
 */
function wades_boats_meta_debug_log($message) {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        error_log('BOATS META: ' . $message);
    }
}

/**
 * Register the boats template
 */
function wades_register_boats_template($templates) {
    wades_boats_meta_debug_log('Registering boats template');
    wades_boats_meta_debug_log('Current templates: ' . print_r($templates, true));
    $templates['templates/boats.php'] = 'Boats Template';
    wades_boats_meta_debug_log('Updated templates: ' . print_r($templates, true));
    return $templates;
}
add_filter('theme_page_templates', 'wades_register_boats_template', 1);

/**
 * Add meta boxes for Boats template
 */
function wades_add_boats_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    
    // Only add these meta boxes for the boats template
    if ($template !== 'templates/boats.php') {
        return;
    }

    // Single meta box with tabs
    add_meta_box(
        'wades_boats_settings',
        'Boats Page Settings',
        'wades_boats_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_boats_meta_boxes', 1);

/**
 * Boats Settings Meta Box Callback
 */
function wades_boats_settings_callback($post) {
    wp_nonce_field('wades_boats_meta', 'wades_boats_meta_nonce');

    // Get all meta data with default values
    $meta = array(
        // Hero Section
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true),
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '70',
        'boats_title' => get_post_meta($post->ID, '_boats_title', true) ?: 'Our Boat Inventory',
        'boats_description' => get_post_meta($post->ID, '_boats_description', true) ?: 'Discover our extensive collection of premium boats.',
        
        // Layout Options
        'boats_per_page' => get_post_meta($post->ID, '_boats_per_page', true) ?: '12',
        'show_search' => get_post_meta($post->ID, '_show_search', true) ?: '1',
        'show_filters' => get_post_meta($post->ID, '_show_filters', true) ?: '1',
        
        // Filter Options
        'manufacturer_filter' => get_post_meta($post->ID, '_manufacturer_filter', true) ?: '1',
        'condition_filter' => get_post_meta($post->ID, '_condition_filter', true) ?: '1',
        'price_filter' => get_post_meta($post->ID, '_price_filter', true) ?: '1',
        'year_filter' => get_post_meta($post->ID, '_year_filter', true) ?: '1',
        'length_filter' => get_post_meta($post->ID, '_length_filter', true) ?: '1',
        'type_filter' => get_post_meta($post->ID, '_type_filter', true) ?: '1',
        
        // Sort Options
        'sort_options' => get_post_meta($post->ID, '_sort_options', true) ?: array(
            'newest' => '1',
            'price_low' => '1',
            'price_high' => '1',
            'name' => '1',
            'length' => '1',
            'year' => '1'
        ),
        
        // Card Display
        'show_specs' => get_post_meta($post->ID, '_show_specs', true) ?: array(
            'length' => '1',
            'capacity' => '1',
            'engine' => '1',
            'year' => '1',
            'price' => '1',
            'location' => '1'
        ),

        // SEO Settings
        'seo_title' => get_post_meta($post->ID, '_seo_title', true) ?: 'Boats for Sale in [location] | Impact Marine Group',
        'seo_description' => get_post_meta($post->ID, '_seo_description', true) ?: 'Browse our selection of new and used boats for sale in [location]. Find fishing boats, pontoons, and more at Impact Marine Group.',
        'schema_type' => get_post_meta($post->ID, '_schema_type', true) ?: 'VehicleDealer',
        
        // Sections Visibility & Order
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'hero' => '1',
            'filters' => '1',
            'inventory' => '1',
            'pagination' => '1'
        ),
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'hero,filters,inventory,pagination'
    );
    ?>
    <div class="wades-meta-box-tabs">
        <div class="wades-tab-buttons">
            <button type="button" class="wades-tab-button active" data-tab="content">Content</button>
            <button type="button" class="wades-tab-button" data-tab="filters">Filters & Sorting</button>
            <button type="button" class="wades-tab-button" data-tab="display">Display</button>
            <button type="button" class="wades-tab-button" data-tab="seo">SEO</button>
        </div>

        <!-- Content Tab -->
        <div class="wades-tab-content active" data-tab="content">
            <div class="meta-box-section">
                <h3>Hero Section</h3>
                <div class="wades-meta-field">
                    <label for="boats_title">Page Title:</label>
                    <input type="text" id="boats_title" name="boats_title" 
                           value="<?php echo esc_attr($meta['boats_title']); ?>" class="widefat">
                </div>
                <div class="wades-meta-field">
                    <label for="boats_description">Page Description:</label>
                    <textarea id="boats_description" name="boats_description" 
                            rows="3" class="widefat"><?php echo esc_textarea($meta['boats_description']); ?></textarea>
                </div>
                <div class="wades-meta-field">
                    <label for="hero_background_image">Hero Background Image:</label>
                    <div class="image-upload-field">
                        <input type="hidden" id="hero_background_image" name="hero_background_image" 
                               value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                        <button type="button" class="button upload-image">Select Image</button>
                        <button type="button" class="button remove-image">Remove Image</button>
                        <div class="image-preview">
                            <?php if ($meta['hero_background_image']) : ?>
                                <?php echo wp_get_attachment_image($meta['hero_background_image'], 'medium'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wades-meta-field">
                    <label for="hero_overlay_opacity">Hero Overlay Opacity (%):</label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>" 
                           min="0" max="100" class="small-text">
                </div>
                <div class="wades-meta-field">
                    <label for="hero_height">Hero Height (vh):</label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>" 
                           min="10" max="100" class="small-text">
                </div>
            </div>
        </div>

        <!-- Filters & Sorting Tab -->
        <div class="wades-tab-content" data-tab="filters">
            <div class="meta-box-section">
                <h3>Filter Options</h3>
                <p>
                    <label>
                        <input type="checkbox" name="manufacturer_filter" value="1" <?php checked($meta['manufacturer_filter'], '1'); ?>>
                        Show Manufacturer Filter
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="condition_filter" value="1" <?php checked($meta['condition_filter'], '1'); ?>>
                        Show Condition Filter
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="price_filter" value="1" <?php checked($meta['price_filter'], '1'); ?>>
                        Show Price Filter
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="year_filter" value="1" <?php checked($meta['year_filter'], '1'); ?>>
                        Show Year Filter
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="length_filter" value="1" <?php checked($meta['length_filter'], '1'); ?>>
                        Show Length Filter
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="type_filter" value="1" <?php checked($meta['type_filter'], '1'); ?>>
                        Show Type Filter
                    </label>
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Sort Options</h3>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[newest]" value="1" <?php checked($meta['sort_options']['newest'], '1'); ?>>
                        Newest First
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[price_low]" value="1" <?php checked($meta['sort_options']['price_low'], '1'); ?>>
                        Price (Low to High)
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[price_high]" value="1" <?php checked($meta['sort_options']['price_high'], '1'); ?>>
                        Price (High to Low)
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[name]" value="1" <?php checked($meta['sort_options']['name'], '1'); ?>>
                        Name (A-Z)
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[length]" value="1" <?php checked($meta['sort_options']['length'], '1'); ?>>
                        Length
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sort_options[year]" value="1" <?php checked($meta['sort_options']['year'], '1'); ?>>
                        Year
                    </label>
                </p>
            </div>
        </div>

        <!-- Display Tab -->
        <div class="wades-tab-content" data-tab="display">
            <div class="meta-box-section">
                <h3>Display Settings</h3>
                <div class="wades-meta-field">
                    <label for="boats_per_page">Boats Per Page:</label>
                    <input type="number" id="boats_per_page" name="boats_per_page" 
                           value="<?php echo esc_attr($meta['boats_per_page']); ?>" 
                           min="1" max="100" class="small-text">
                </div>
            </div>

            <div class="meta-box-section">
                <h3>Specifications Display</h3>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[length]" value="1" <?php checked($meta['show_specs']['length'], '1'); ?>>
                        Show Length
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[capacity]" value="1" <?php checked($meta['show_specs']['capacity'], '1'); ?>>
                        Show Capacity
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[engine]" value="1" <?php checked($meta['show_specs']['engine'], '1'); ?>>
                        Show Engine
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[year]" value="1" <?php checked($meta['show_specs']['year'], '1'); ?>>
                        Show Year
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[price]" value="1" <?php checked($meta['show_specs']['price'], '1'); ?>>
                        Show Price
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_specs[location]" value="1" <?php checked($meta['show_specs']['location'], '1'); ?>>
                        Show Location
                    </label>
                </p>
            </div>
        </div>

        <!-- SEO Tab -->
        <div class="wades-tab-content" data-tab="seo">
            <div class="meta-box-section">
                <h3>SEO Settings</h3>
                <div class="wades-meta-field">
                    <label for="seo_title">SEO Title:</label>
                    <input type="text" id="seo_title" name="seo_title" 
                           value="<?php echo esc_attr($meta['seo_title']); ?>" class="widefat">
                    <p class="description">Use [location] to dynamically insert the location.</p>
                </div>
                <div class="wades-meta-field">
                    <label for="seo_description">SEO Description:</label>
                    <textarea id="seo_description" name="seo_description" 
                            rows="3" class="widefat"><?php echo esc_textarea($meta['seo_description']); ?></textarea>
                    <p class="description">Use [location] to dynamically insert the location.</p>
                </div>
                <div class="wades-meta-field">
                    <label for="schema_type">Schema Type:</label>
                    <select id="schema_type" name="schema_type" class="widefat">
                        <option value="VehicleDealer" <?php selected($meta['schema_type'], 'VehicleDealer'); ?>>Vehicle Dealer</option>
                        <option value="BoatDealer" <?php selected($meta['schema_type'], 'BoatDealer'); ?>>Boat Dealer</option>
                        <option value="Store" <?php selected($meta['schema_type'], 'Store'); ?>>Store</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save Boats Meta Box Data
 */
function wades_save_boats_meta($post_id) {
    if (!isset($_POST['wades_boats_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_boats_meta_nonce'], 'wades_boats_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save text fields
    $text_fields = array(
        'boats_title',
        'boats_description',
        'boats_per_page',
        'hero_overlay_opacity',
        'hero_height',
        'seo_title',
        'seo_description',
        'schema_type',
        'section_order'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save hero background image
    if (isset($_POST['hero_background_image'])) {
        update_post_meta($post_id, '_hero_background_image', absint($_POST['hero_background_image']));
    }

    // Save filter options
    $filter_options = array(
        'manufacturer_filter',
        'condition_filter',
        'price_filter',
        'year_filter',
        'length_filter',
        'type_filter'
    );

    foreach ($filter_options as $option) {
        update_post_meta($post_id, '_' . $option, isset($_POST[$option]) ? '1' : '');
    }

    // Save sort options
    if (isset($_POST['sort_options']) && is_array($_POST['sort_options'])) {
        $sort_options = array();
        foreach ($_POST['sort_options'] as $key => $value) {
            $sort_options[$key] = '1';
        }
        update_post_meta($post_id, '_sort_options', $sort_options);
    }

    // Save specification display options
    if (isset($_POST['show_specs']) && is_array($_POST['show_specs'])) {
        $show_specs = array();
        foreach ($_POST['show_specs'] as $key => $value) {
            $show_specs[$key] = '1';
        }
        update_post_meta($post_id, '_show_specs', $show_specs);
    }

    // Save sections visibility
    if (isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility'])) {
        $sections_visibility = array();
        foreach ($_POST['sections_visibility'] as $section => $value) {
            $sections_visibility[$section] = '1';
        }
        update_post_meta($post_id, '_sections_visibility', $sections_visibility);
    }
}
add_action('save_post', 'wades_save_boats_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_boats_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        // Check if we're editing a page
        if (isset($post) && $post->post_type === 'page') {
            // Get the template
            $template = get_page_template_slug($post->ID);
            
            // Check if it's our boats template
            if ($template === 'templates/boats.php' || basename($template) === 'boats.php') {
                // Enqueue required WordPress media scripts
                wp_enqueue_media();
                
                // Enqueue jQuery UI for sortable functionality
                wp_enqueue_script('jquery-ui-sortable');
                
                // Enqueue our custom admin script
                wp_enqueue_script(
                    'boats-admin',
                    get_template_directory_uri() . '/assets/js/boats-admin.js',
                    array('jquery', 'jquery-ui-sortable'),
                    _S_VERSION,
                    true
                );
                
                // Enqueue meta box styles
                wp_enqueue_style(
                    'wades-meta-box-styles',
                    get_template_directory_uri() . '/inc/meta-boxes/meta-box-styles.css',
                    array(),
                    _S_VERSION
                );
                
                // Add inline styles for boats meta box specifically
                wp_add_inline_style('wades-meta-box-styles', '
                    .boats-meta-box .meta-box-tabs {
                        margin: 0 -12px;
                        padding: 0 12px;
                        background: #fff;
                        border-bottom: 1px solid #ddd;
                    }
                    .boats-meta-box .tab-button {
                        padding: 12px 16px;
                        margin-right: 4px;
                        border: none;
                        background: none;
                        cursor: pointer;
                        border-bottom: 2px solid transparent;
                    }
                    .boats-meta-box .tab-button.active {
                        border-bottom-color: #2271b1;
                        color: #2271b1;
                    }
                    .boats-meta-box .tab-content {
                        display: none;
                        padding: 12px 0;
                    }
                    .boats-meta-box .tab-content.active {
                        display: block;
                    }
                ');
            }
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_boats_admin_scripts'); 