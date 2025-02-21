<?php
/**
 * Meta Boxes for Boats Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Boats template
 */
function wades_boats_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the boats template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'templates/boats.php' || basename($template) === 'boats.php') {
            add_meta_box(
                'boats_content',
                'Boats Page Content',
                'wades_boats_content_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_boats_meta_boxes');

/**
 * Boats Content Meta Box Callback
 */
function wades_boats_content_callback($post) {
    wp_nonce_field('wades_boats_meta', 'wades_boats_meta_nonce');

    $meta = array(
        // Hero Section
        'boats_title' => get_post_meta($post->ID, '_boats_title', true),
        'boats_description' => get_post_meta($post->ID, '_boats_description', true),
        'hero_image' => get_post_meta($post->ID, '_hero_image', true),
        
        // Layout Options
        'grid_columns' => get_post_meta($post->ID, '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'boats_per_page' => get_post_meta($post->ID, '_boats_per_page', true) ?: '12',
        'show_search' => get_post_meta($post->ID, '_show_search', true) ?: '1',
        'show_filters' => get_post_meta($post->ID, '_show_filters', true) ?: '1',
        
        // Filter Options
        'manufacturer_filter' => get_post_meta($post->ID, '_manufacturer_filter', true) ?: '1',
        'condition_filter' => get_post_meta($post->ID, '_condition_filter', true) ?: '1',
        'price_filter' => get_post_meta($post->ID, '_price_filter', true) ?: '1',
        'year_filter' => get_post_meta($post->ID, '_year_filter', true) ?: '1',
        
        // Sort Options
        'sort_options' => get_post_meta($post->ID, '_sort_options', true) ?: array(
            'newest' => '1',
            'price_low' => '1',
            'price_high' => '1',
            'name' => '1'
        ),
        
        // Card Display
        'card_style' => get_post_meta($post->ID, '_card_style', true) ?: 'default',
        'hover_effect' => get_post_meta($post->ID, '_hover_effect', true) ?: 'scale',
        'show_specs' => get_post_meta($post->ID, '_show_specs', true) ?: array(
            'length' => '1',
            'capacity' => '1',
            'engine' => '1',
            'year' => '1'
        )
    );
    ?>
    <div class="boats-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="content">Content</button>
            <button type="button" class="tab-button" data-tab="layout">Layout</button>
            <button type="button" class="tab-button" data-tab="filters">Filters</button>
            <button type="button" class="tab-button" data-tab="display">Display</button>
        </div>

        <!-- Content Tab -->
        <div class="tab-content active" data-tab="content">
            <div class="meta-box-section">
                <h3>Page Content</h3>
                <p>
                    <label for="boats_title">Page Title:</label><br>
                    <input type="text" id="boats_title" name="boats_title" value="<?php echo esc_attr($meta['boats_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boats_description">Page Description:</label><br>
                    <textarea id="boats_description" name="boats_description" rows="3" class="widefat"><?php echo esc_textarea($meta['boats_description']); ?></textarea>
                </p>
                <p>
                    <label>Hero Background Image:</label><br>
                    <input type="hidden" name="hero_image" value="<?php echo esc_attr($meta['hero_image']); ?>" class="widefat">
                    <button type="button" class="button upload-image">Upload Image</button>
                    <div class="image-preview">
                        <?php if ($meta['hero_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['hero_image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>
        </div>

        <!-- Layout Tab -->
        <div class="tab-content" data-tab="layout">
            <div class="meta-box-section">
                <h3>Grid Layout</h3>
                <p>
                    <label for="grid_columns">Number of Columns:</label><br>
                    <select id="grid_columns" name="grid_columns" class="widefat">
                        <option value="grid-cols-1 md:grid-cols-1 lg:grid-cols-2" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-1 lg:grid-cols-2'); ?>>2 Columns (Large)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-3" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?>>3 Columns (Default)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-4" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'); ?>>4 Columns</option>
                    </select>
                </p>
                <p>
                    <label for="boats_per_page">Boats Per Page:</label><br>
                    <input type="number" id="boats_per_page" name="boats_per_page" value="<?php echo esc_attr($meta['boats_per_page']); ?>" class="small-text" min="1" max="100">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Search & Filters Visibility</h3>
                <p>
                    <label>
                        <input type="checkbox" name="show_search" value="1" <?php checked($meta['show_search'], '1'); ?>>
                        Show Search Bar
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_filters" value="1" <?php checked($meta['show_filters'], '1'); ?>>
                        Show Filters
                    </label>
                </p>
            </div>
        </div>

        <!-- Filters Tab -->
        <div class="tab-content" data-tab="filters">
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
            </div>
        </div>

        <!-- Display Tab -->
        <div class="tab-content" data-tab="display">
            <div class="meta-box-section">
                <h3>Card Style</h3>
                <p>
                    <label for="card_style">Style:</label><br>
                    <select id="card_style" name="card_style" class="widefat">
                        <option value="default" <?php selected($meta['card_style'], 'default'); ?>>Default</option>
                        <option value="minimal" <?php selected($meta['card_style'], 'minimal'); ?>>Minimal</option>
                        <option value="featured" <?php selected($meta['card_style'], 'featured'); ?>>Featured</option>
                        <option value="bordered" <?php selected($meta['card_style'], 'bordered'); ?>>Bordered</option>
                    </select>
                </p>
                <p>
                    <label for="hover_effect">Hover Effect:</label><br>
                    <select id="hover_effect" name="hover_effect" class="widefat">
                        <option value="scale" <?php selected($meta['hover_effect'], 'scale'); ?>>Scale</option>
                        <option value="lift" <?php selected($meta['hover_effect'], 'lift'); ?>>Lift</option>
                        <option value="glow" <?php selected($meta['hover_effect'], 'glow'); ?>>Glow</option>
                        <option value="none" <?php selected($meta['hover_effect'], 'none'); ?>>None</option>
                    </select>
                </p>
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
            </div>
        </div>
    </div>

    <style>
        <?php include get_template_directory() . '/inc/meta-boxes/meta-box-styles.css'; ?>
    </style>
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
        'grid_columns',
        'boats_per_page',
        'card_style',
        'hover_effect'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save checkboxes
    $checkboxes = array(
        'show_search',
        'show_filters',
        'manufacturer_filter',
        'condition_filter',
        'price_filter',
        'year_filter'
    );

    foreach ($checkboxes as $checkbox) {
        update_post_meta($post_id, '_' . $checkbox, isset($_POST[$checkbox]) ? '1' : '');
    }

    // Save arrays
    if (isset($_POST['sort_options']) && is_array($_POST['sort_options'])) {
        $sort_options = array();
        foreach ($_POST['sort_options'] as $option => $value) {
            $sort_options[$option] = '1';
        }
        update_post_meta($post_id, '_sort_options', $sort_options);
    }

    if (isset($_POST['show_specs']) && is_array($_POST['show_specs'])) {
        $show_specs = array();
        foreach ($_POST['show_specs'] as $spec => $value) {
            $show_specs[$spec] = '1';
        }
        update_post_meta($post_id, '_show_specs', $show_specs);
    }

    // Save images
    if (isset($_POST['hero_image'])) {
        update_post_meta($post_id, '_hero_image', absint($_POST['hero_image']));
    }
}
add_action('save_post', 'wades_save_boats_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_boats_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (is_object($post) && get_page_template_slug($post->ID) == 'templates/boats.php') {
            wp_enqueue_media();
            wp_enqueue_script(
                'boats-admin',
                get_template_directory_uri() . '/assets/js/boats-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                _S_VERSION,
                true
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_boats_admin_scripts'); 