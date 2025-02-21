<?php
/**
 * Brand Post Type
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Brand Post Type
 */
function wades_register_brand_post_type() {
    $labels = array(
        'name'               => _x('Brands', 'post type general name', 'wades'),
        'singular_name'      => _x('Brand', 'post type singular name', 'wades'),
        'menu_name'         => _x('Brands', 'admin menu', 'wades'),
        'name_admin_bar'    => _x('Brand', 'add new on admin bar', 'wades'),
        'add_new'           => _x('Add New', 'brand', 'wades'),
        'add_new_item'      => __('Add New Brand', 'wades'),
        'new_item'          => __('New Brand', 'wades'),
        'edit_item'         => __('Edit Brand', 'wades'),
        'view_item'         => __('View Brand', 'wades'),
        'all_items'         => __('All Brands', 'wades'),
        'search_items'      => __('Search Brands', 'wades'),
        'parent_item_colon' => __('Parent Brands:', 'wades'),
        'not_found'         => __('No brands found.', 'wades'),
        'not_found_in_trash'=> __('No brands found in Trash.', 'wades')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'brands'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 5,
        'menu_icon'         => 'dashicons-store',
        'supports'          => array('title', 'thumbnail'),
        'show_in_rest'      => false
    );

    register_post_type('brand', $args);
}
add_action('init', 'wades_register_brand_post_type');

/**
 * Add Brand Meta Boxes
 */
function wades_add_brand_meta_boxes() {
    add_meta_box(
        'brand_information',
        __('Brand Information', 'wades'),
        'wades_brand_information_callback',
        'brand',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_brand_meta_boxes');

/**
 * Brand Information Meta Box Callback
 */
function wades_brand_information_callback($post) {
    wp_nonce_field('wades_brand_meta_box', 'wades_brand_meta_box_nonce');

    $brand_details = get_post_meta($post->ID, '_brand_details', true) ?: array();
    $brand_models = get_post_meta($post->ID, '_brand_models', true) ?: array();
    $brand_features = get_post_meta($post->ID, '_brand_features', true) ?: array();
    ?>
    <div class="acf-fields">
        <!-- Brand Details -->
        <div class="acf-field-group">
            <div class="acf-label">
                <label><?php _e('Brand Details', 'wades'); ?></label>
            </div>
            <div class="acf-input">
                <div class="acf-fields">
                    <div class="acf-field">
                        <div class="acf-label">
                            <label for="brand_short_description"><?php _e('Short Description', 'wades'); ?></label>
                        </div>
                        <div class="acf-input">
                            <textarea id="brand_short_description" name="brand_details[short_description]" rows="3"><?php echo esc_textarea($brand_details['short_description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="acf-field">
                        <div class="acf-label">
                            <label for="brand_website"><?php _e('Website URL', 'wades'); ?></label>
                        </div>
                        <div class="acf-input">
                            <input type="url" id="brand_website" name="brand_details[website]" value="<?php echo esc_url($brand_details['website'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="acf-field">
                        <div class="acf-label">
                            <label for="brand_type"><?php _e('Brand Type', 'wades'); ?></label>
                        </div>
                        <div class="acf-input">
                            <select id="brand_type" name="brand_details[type]">
                                <option value=""><?php _e('Select Type', 'wades'); ?></option>
                                <option value="boat" <?php selected($brand_details['type'] ?? '', 'boat'); ?>><?php _e('Boat', 'wades'); ?></option>
                                <option value="equipment" <?php selected($brand_details['type'] ?? '', 'equipment'); ?>><?php _e('Equipment', 'wades'); ?></option>
                                <option value="accessories" <?php selected($brand_details['type'] ?? '', 'accessories'); ?>><?php _e('Accessories', 'wades'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="acf-field">
                        <div class="acf-label">
                            <label for="brand_featured"><?php _e('Featured Brand', 'wades'); ?></label>
                        </div>
                        <div class="acf-input">
                            <div class="acf-true-false">
                                <input type="checkbox" id="brand_featured" name="brand_details[featured]" value="1" <?php checked($brand_details['featured'] ?? '', '1'); ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Models -->
        <div class="acf-field-group">
            <div class="acf-label">
                <label><?php _e('Brand Models', 'wades'); ?></label>
                <p class="description"><?php _e('Add and manage models for this brand.', 'wades'); ?></p>
            </div>
            <div class="acf-input">
                <div class="acf-repeater">
                    <div class="acf-table">
                        <div class="acf-repeater-items">
                            <?php
                            if (!empty($brand_models)) {
                                foreach ($brand_models as $index => $model) {
                                    ?>
                                    <div class="acf-row">
                                        <div class="acf-row-handle order">
                                            <span class="reorder-handle">≡</span>
                                            <span class="acf-row-number"><?php echo $index + 1; ?></span>
                                        </div>
                                        <div class="acf-fields">
                                            <div class="acf-field">
                                                <div class="acf-label">
                                                    <label><?php _e('Model Name', 'wades'); ?></label>
                                                </div>
                                                <div class="acf-input">
                                                    <input type="text" name="brand_models[<?php echo $index; ?>][name]" value="<?php echo esc_attr($model['name'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="acf-field">
                                                <div class="acf-label">
                                                    <label><?php _e('Description', 'wades'); ?></label>
                                                </div>
                                                <div class="acf-input">
                                                    <textarea name="brand_models[<?php echo $index; ?>][description]" rows="2"><?php echo esc_textarea($model['description'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="acf-field">
                                                <div class="acf-label">
                                                    <label><?php _e('Specifications', 'wades'); ?></label>
                                                </div>
                                                <div class="acf-input">
                                                    <textarea name="brand_models[<?php echo $index; ?>][specs]" rows="2"><?php echo esc_textarea($model['specs'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="acf-field">
                                                <div class="acf-label">
                                                    <label><?php _e('Model Image', 'wades'); ?></label>
                                                </div>
                                                <div class="acf-input">
                                                    <div class="acf-image-uploader" data-preview_size="thumbnail">
                                                        <input type="hidden" name="brand_models[<?php echo $index; ?>][image]" value="<?php echo esc_attr($model['image'] ?? ''); ?>">
                                                        <div class="image-wrap">
                                                            <?php if (!empty($model['image'])) {
                                                                echo wp_get_attachment_image($model['image'], 'thumbnail');
                                                            } ?>
                                                        </div>
                                                        <div class="acf-actions">
                                                            <a href="#" class="acf-button button"><?php _e('Add Image', 'wades'); ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="acf-row-handle remove">
                                            <a class="acf-icon -minus small" href="#" data-event="remove-row"></a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="acf-actions">
                        <a class="acf-button button button-primary" href="#" data-event="add-row"><?php _e('Add Model', 'wades'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brand Features -->
        <div class="acf-field-group">
            <div class="acf-label">
                <label><?php _e('Brand Features', 'wades'); ?></label>
                <p class="description"><?php _e('Add key features of this brand.', 'wades'); ?></p>
            </div>
            <div class="acf-input">
                <div class="acf-repeater">
                    <div class="acf-table">
                        <div class="acf-repeater-items">
                            <?php
                            if (!empty($brand_features)) {
                                foreach ($brand_features as $index => $feature) {
                                    ?>
                                    <div class="acf-row">
                                        <div class="acf-row-handle order">
                                            <span class="reorder-handle">≡</span>
                                            <span class="acf-row-number"><?php echo $index + 1; ?></span>
                                        </div>
                                        <div class="acf-fields">
                                            <div class="acf-field">
                                                <div class="acf-input">
                                                    <input type="text" name="brand_features[]" value="<?php echo esc_attr($feature); ?>" placeholder="<?php esc_attr_e('Enter feature', 'wades'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="acf-row-handle remove">
                                            <a class="acf-icon -minus small" href="#" data-event="remove-row"></a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="acf-actions">
                        <a class="acf-button button button-primary" href="#" data-event="add-row"><?php _e('Add Feature', 'wades'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save Brand Meta Box Data
 */
function wades_save_brand_meta($post_id) {
    if (!isset($_POST['wades_brand_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_brand_meta_box_nonce'], 'wades_brand_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save brand details
    if (isset($_POST['brand_details'])) {
        $brand_details = array_map('sanitize_text_field', $_POST['brand_details']);
        update_post_meta($post_id, '_brand_details', $brand_details);
    }

    // Save brand models
    if (isset($_POST['brand_models'])) {
        $brand_models = array();
        foreach ($_POST['brand_models'] as $model) {
            $brand_models[] = array(
                'name' => sanitize_text_field($model['name']),
                'description' => sanitize_textarea_field($model['description']),
                'specs' => sanitize_textarea_field($model['specs']),
                'image' => absint($model['image'])
            );
        }
        update_post_meta($post_id, '_brand_models', $brand_models);
    }

    // Save brand features
    if (isset($_POST['brand_features'])) {
        $brand_features = array_map('sanitize_text_field', $_POST['brand_features']);
        $brand_features = array_filter($brand_features);
        update_post_meta($post_id, '_brand_features', $brand_features);
    }
}
add_action('save_post_brand', 'wades_save_brand_meta');

/**
 * Enqueue Admin Scripts and Styles
 */
function wades_brand_admin_enqueue_scripts($hook) {
    global $post_type;
    
    if ('brand' === $post_type) {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('brand-admin', get_template_directory_uri() . '/assets/js/brand-admin.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('brand-admin', get_template_directory_uri() . '/assets/css/brand-admin.css', array(), '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'wades_brand_admin_enqueue_scripts');

/**
 * Add Custom Columns to Brand List
 */
function wades_brand_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['brand_type'] = __('Type', 'wades');
            $new_columns['featured'] = __('Featured', 'wades');
            $new_columns['models'] = __('Models', 'wades');
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_brand_posts_columns', 'wades_brand_columns');

/**
 * Populate Custom Columns
 */
function wades_brand_custom_column($column, $post_id) {
    $brand_details = get_post_meta($post_id, '_brand_details', true) ?: array();
    $brand_models = get_post_meta($post_id, '_brand_models', true) ?: array();

    switch ($column) {
        case 'brand_type':
            $type = isset($brand_details['type']) ? $brand_details['type'] : '';
            echo esc_html(ucfirst($type));
            break;
        case 'featured':
            $featured = isset($brand_details['featured']) ? $brand_details['featured'] : '';
            echo $featured ? '★' : '—';
            break;
        case 'models':
            echo count($brand_models);
            break;
    }
}
add_action('manage_brand_posts_custom_column', 'wades_brand_custom_column', 10, 2);

/**
 * Add help tab to brand post type
 */
function wades_brand_help_tab() {
    $screen = get_current_screen();
    
    if ($screen->post_type !== 'brand') {
        return;
    }
    
    $screen->add_help_tab(array(
        'id'       => 'wades_brand_help',
        'title'    => __('Brand Management Help', 'wades'),
        'content'  => '
            <h2>Managing Brands</h2>
            <p>Here are some tips for managing your brands:</p>
            <ul>
                <li><strong>Brand Details:</strong> Add basic information about the brand including description, website, and type.</li>
                <li><strong>Models:</strong> Add all models available from this brand. You can reorder them by dragging.</li>
                <li><strong>Features:</strong> List key features or selling points of the brand.</li>
                <li><strong>Featured Image:</strong> Set a brand logo as the featured image.</li>
            </ul>
            <p><strong>Quick Tips:</strong></p>
            <ul>
                <li>Use the search box to filter models</li>
                <li>Click the model header to expand/collapse</li>
                <li>Drag the ↕ icon to reorder items</li>
                <li>Click × to remove items</li>
            </ul>
        '
    ));
}
add_action('admin_head', 'wades_brand_help_tab');

/**
 * Add quick edit fields
 */
function wades_brand_quick_edit($column_name, $post_type) {
    if ($post_type !== 'brand' || $column_name !== 'featured') {
        return;
    }
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-featured">
                <input type="checkbox" name="brand_featured" value="1">
                <span class="checkbox-title"><?php _e('Featured Brand', 'wades'); ?></span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'wades_brand_quick_edit', 10, 2);

/**
 * Save quick edit data
 */
function wades_save_quick_edit_data($post_id) {
    if (
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        !current_user_can('edit_post', $post_id) ||
        get_post_type($post_id) !== 'brand'
    ) {
        return;
    }

    $featured = isset($_POST['brand_featured']) ? '1' : '';
    update_post_meta($post_id, '_brand_featured', $featured);
}
add_action('save_post', 'wades_save_quick_edit_data');

/**
 * Add bulk edit fields
 */
function wades_brand_bulk_edit() {
    ?>
    <div class="inline-edit-group wp-clearfix">
        <label class="alignleft">
            <span class="title"><?php _e('Featured', 'wades'); ?></span>
            <select name="brand_featured">
                <option value="-1"><?php _e('— No Change —', 'wades'); ?></option>
                <option value="1"><?php _e('Yes', 'wades'); ?></option>
                <option value="0"><?php _e('No', 'wades'); ?></option>
            </select>
        </label>
    </div>
    <?php
}
add_action('bulk_edit_custom_box', 'wades_brand_bulk_edit', 10, 2);

/**
 * Save bulk edit data
 */
function wades_save_bulk_edit_data($post_id) {
    if (
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        !current_user_can('edit_post', $post_id) ||
        get_post_type($post_id) !== 'brand'
    ) {
        return;
    }

    if (isset($_REQUEST['brand_featured']) && $_REQUEST['brand_featured'] !== '-1') {
        $featured = $_REQUEST['brand_featured'] === '1' ? '1' : '';
        update_post_meta($post_id, '_brand_featured', $featured);
    }
}
add_action('save_post', 'wades_save_bulk_edit_data'); 