<?php
/**
 * Boat Meta Boxes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Boat post type
 */
function wades_add_boat_meta_boxes() {
    add_meta_box(
        'boat_details',
        'Boat Details',
        'wades_boat_details_callback',
        'boat',
        'normal',
        'high'
    );

    add_meta_box(
        'boat_gallery',
        'Boat Gallery',
        'wades_boat_gallery_callback',
        'boat',
        'normal',
        'high'
    );

    add_meta_box(
        'boat_features',
        'Boat Features & Specs',
        'wades_boat_features_callback',
        'boat',
        'normal',
        'high'
    );

    add_meta_box(
        'boat_display',
        'Display Settings',
        'wades_boat_display_callback',
        'boat',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'wades_add_boat_meta_boxes');

/**
 * Boat Details Meta Box Callback
 */
function wades_boat_details_callback($post) {
    wp_nonce_field('wades_boat_details', 'wades_boat_details_nonce');

    $boat_meta = array(
        'condition' => get_post_meta($post->ID, '_boat_condition', true),
        'price' => get_post_meta($post->ID, '_boat_price', true),
        'sale_price' => get_post_meta($post->ID, '_boat_sale_price', true),
        'status' => get_post_meta($post->ID, '_boat_status', true),
        'year' => get_post_meta($post->ID, '_boat_year', true),
        'make' => get_post_meta($post->ID, '_boat_make', true),
        'model' => get_post_meta($post->ID, '_boat_model', true),
        'featured' => get_post_meta($post->ID, '_boat_featured', true),
        'length' => get_post_meta($post->ID, '_boat_length', true),
        'beam' => get_post_meta($post->ID, '_boat_beam', true),
        'weight' => get_post_meta($post->ID, '_boat_weight', true),
        'capacity' => get_post_meta($post->ID, '_boat_capacity', true),
        'fuel_capacity' => get_post_meta($post->ID, '_boat_fuel_capacity', true),
        'max_speed' => get_post_meta($post->ID, '_boat_max_speed', true),
        'engine' => get_post_meta($post->ID, '_boat_engine', true),
        'hours' => get_post_meta($post->ID, '_boat_hours', true),
        'hull_material' => get_post_meta($post->ID, '_boat_hull_material', true),
        'location' => get_post_meta($post->ID, '_boat_location', true),
        'stock_number' => get_post_meta($post->ID, '_boat_stock_number', true)
    );
    ?>
    <div class="boat-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="basic">Basic Info</button>
            <button type="button" class="tab-button" data-tab="pricing">Pricing</button>
            <button type="button" class="tab-button" data-tab="specs">Specifications</button>
            <button type="button" class="tab-button" data-tab="engine">Engine & Performance</button>
        </div>

        <!-- Basic Info Tab -->
        <div class="tab-content active" data-tab="basic">
            <div class="meta-box-section">
        <p>
            <label for="boat_featured">
                <input type="checkbox" id="boat_featured" name="boat_featured" value="1" <?php checked($boat_meta['featured'], '1'); ?>>
                Feature this boat on homepage
            </label>
        </p>
        <p>
            <label for="boat_condition">Condition:</label>
            <select name="boat_condition" id="boat_condition" class="widefat">
                <option value="new" <?php selected($boat_meta['condition'], 'new'); ?>>New</option>
                <option value="used" <?php selected($boat_meta['condition'], 'used'); ?>>Used</option>
            </select>
        </p>
        <p>
            <label for="boat_status">Status:</label>
            <select name="boat_status" id="boat_status" class="widefat">
                <option value="Available" <?php selected($boat_meta['status'], 'Available'); ?>>Available</option>
                <option value="Sold" <?php selected($boat_meta['status'], 'Sold'); ?>>Sold</option>
                <option value="On Order" <?php selected($boat_meta['status'], 'On Order'); ?>>On Order</option>
                        <option value="Coming Soon" <?php selected($boat_meta['status'], 'Coming Soon'); ?>>Coming Soon</option>
                        <option value="Sale Pending" <?php selected($boat_meta['status'], 'Sale Pending'); ?>>Sale Pending</option>
            </select>
        </p>
                <p>
                    <label for="boat_stock_number">Stock Number:</label>
                    <input type="text" id="boat_stock_number" name="boat_stock_number" value="<?php echo esc_attr($boat_meta['stock_number']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_location">Location:</label>
                    <input type="text" id="boat_location" name="boat_location" value="<?php echo esc_attr($boat_meta['location']); ?>" class="widefat">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Model Information</h3>
        <p>
            <label for="boat_year">Year:</label>
            <input type="number" id="boat_year" name="boat_year" value="<?php echo esc_attr($boat_meta['year']); ?>" class="widefat">
        </p>
        <p>
                    <label for="boat_make">Make:</label>
                    <input type="text" id="boat_make" name="boat_make" value="<?php echo esc_attr($boat_meta['make']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_model">Model:</label>
            <input type="text" id="boat_model" name="boat_model" value="<?php echo esc_attr($boat_meta['model']); ?>" class="widefat">
        </p>
            </div>
        </div>

        <!-- Pricing Tab -->
        <div class="tab-content" data-tab="pricing">
            <div class="meta-box-section">
                <p>
                    <label for="boat_price">Regular Price:</label>
                    <input type="number" id="boat_price" name="boat_price" value="<?php echo esc_attr($boat_meta['price']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_sale_price">Sale Price:</label>
                    <input type="number" id="boat_sale_price" name="boat_sale_price" value="<?php echo esc_attr($boat_meta['sale_price']); ?>" class="widefat">
                    <span class="description">Leave empty if not on sale</span>
                </p>
            </div>
        </div>

        <!-- Specifications Tab -->
        <div class="tab-content" data-tab="specs">
            <div class="meta-box-section">
                <p>
                    <label for="boat_length">Length (ft):</label>
                    <input type="number" id="boat_length" name="boat_length" value="<?php echo esc_attr($boat_meta['length']); ?>" class="widefat" step="0.1">
                </p>
                <p>
                    <label for="boat_beam">Beam (ft):</label>
                    <input type="number" id="boat_beam" name="boat_beam" value="<?php echo esc_attr($boat_meta['beam']); ?>" class="widefat" step="0.1">
                </p>
                <p>
                    <label for="boat_weight">Weight (lbs):</label>
                    <input type="number" id="boat_weight" name="boat_weight" value="<?php echo esc_attr($boat_meta['weight']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_capacity">Capacity (persons):</label>
                    <input type="number" id="boat_capacity" name="boat_capacity" value="<?php echo esc_attr($boat_meta['capacity']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_fuel_capacity">Fuel Capacity (gal):</label>
                    <input type="number" id="boat_fuel_capacity" name="boat_fuel_capacity" value="<?php echo esc_attr($boat_meta['fuel_capacity']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_hull_material">Hull Material:</label>
                    <select name="boat_hull_material" id="boat_hull_material" class="widefat">
                        <option value="Fiberglass" <?php selected($boat_meta['hull_material'], 'Fiberglass'); ?>>Fiberglass</option>
                        <option value="Aluminum" <?php selected($boat_meta['hull_material'], 'Aluminum'); ?>>Aluminum</option>
                        <option value="Wood" <?php selected($boat_meta['hull_material'], 'Wood'); ?>>Wood</option>
                        <option value="Steel" <?php selected($boat_meta['hull_material'], 'Steel'); ?>>Steel</option>
                    </select>
                </p>
            </div>
        </div>

        <!-- Engine & Performance Tab -->
        <div class="tab-content" data-tab="engine">
            <div class="meta-box-section">
                <p>
                    <label for="boat_engine">Engine Details:</label>
                    <input type="text" id="boat_engine" name="boat_engine" value="<?php echo esc_attr($boat_meta['engine']); ?>" class="widefat">
                    <span class="description">Example: "Mercury 250HP Pro XS"</span>
                </p>
                <p>
                    <label for="boat_hours">Engine Hours:</label>
                    <input type="number" id="boat_hours" name="boat_hours" value="<?php echo esc_attr($boat_meta['hours']); ?>" class="widefat">
                </p>
                <p>
                    <label for="boat_max_speed">Maximum Speed (mph):</label>
                    <input type="number" id="boat_max_speed" name="boat_max_speed" value="<?php echo esc_attr($boat_meta['max_speed']); ?>" class="widefat">
                </p>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Tab functionality
            $('.tab-button').on('click', function() {
                $('.tab-button').removeClass('active');
                $('.tab-content').removeClass('active');
                $(this).addClass('active');
                $('.tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
            });
        });
    </script>
    <?php
}

/**
 * Boat Gallery Meta Box Callback
 */
function wades_boat_gallery_callback($post) {
    $gallery_images = get_post_meta($post->ID, '_boat_gallery', true) ?: array();
    ?>
    <div class="boat-gallery-meta">
        <input type="hidden" id="boat_gallery" name="boat_gallery" value="<?php echo esc_attr(implode(',', $gallery_images)); ?>">
        <div id="boat-gallery-container" class="gallery-container">
            <?php
            foreach ($gallery_images as $image_id) {
                echo wp_get_attachment_image($image_id, 'thumbnail', false, array('class' => 'gallery-image'));
            }
            ?>
        </div>
        <p>
            <button type="button" class="button upload-gallery-images">Add Images</button>
            <button type="button" class="button clear-gallery">Clear Gallery</button>
        </p>
        <p class="description">Select multiple images to create a gallery for this boat.</p>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Gallery image upload
            $('.upload-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: 'Select Boat Gallery Images',
                    button: {
                        text: 'Add to Gallery'
                    },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').map(function(attachment) {
                        attachment = attachment.toJSON();
                        return attachment.id;
                    });

                    var currentGallery = $('#boat_gallery').val();
                    var currentIds = currentGallery ? currentGallery.split(',') : [];
                    var newIds = currentIds.concat(attachments);
                    
                    $('#boat_gallery').val(newIds.join(','));
                    
                    // Update gallery preview
                    updateGalleryPreview(newIds);
                });

                frame.open();
            });

            // Clear gallery
            $('.clear-gallery').on('click', function() {
                $('#boat_gallery').val('');
                $('#boat-gallery-container').empty();
            });

            function updateGalleryPreview(imageIds) {
                var container = $('#boat-gallery-container');
                container.empty();

                imageIds.forEach(function(id) {
                    wp.media.attachment(id).fetch().then(function() {
                        var url = this.get('sizes').thumbnail.url;
                        container.append('<img src="' + url + '" class="gallery-image">');
                    });
                });
            }
        });
    </script>

    <style>
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }
        .gallery-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
    <?php
}

/**
 * Boat Features Meta Box Callback
 */
function wades_boat_features_callback($post) {
    $features = get_post_meta($post->ID, '_boat_features', true) ?: array(
        'standard' => array(),
        'optional' => array()
    );
    ?>
    <div class="boat-features-meta">
        <div class="meta-box-section">
            <h3>Standard Features</h3>
            <div class="features-list standard-features">
                <?php foreach ($features['standard'] as $feature) : ?>
                    <p>
                        <input type="text" name="boat_features[standard][]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                        <button type="button" class="button remove-feature">Remove</button>
                    </p>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button add-feature" data-type="standard">Add Standard Feature</button>
        </div>

        <div class="meta-box-section">
            <h3>Optional Features</h3>
            <div class="features-list optional-features">
                <?php foreach ($features['optional'] as $feature) : ?>
                    <p>
                        <input type="text" name="boat_features[optional][]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                        <button type="button" class="button remove-feature">Remove</button>
                    </p>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button add-feature" data-type="optional">Add Optional Feature</button>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Add feature
            $('.add-feature').on('click', function() {
                var type = $(this).data('type');
                var template = `
                    <p>
                        <input type="text" name="boat_features[${type}][]" class="widefat">
                        <button type="button" class="button remove-feature">Remove</button>
                    </p>
                `;
                $(`.${type}-features`).append(template);
            });

            // Remove feature
            $(document).on('click', '.remove-feature', function() {
                $(this).parent('p').remove();
            });

            // Make features sortable
            $('.features-list').sortable({
                handle: 'input',
                cursor: 'move'
            });
        });
    </script>
    <?php
}

/**
 * Boat Display Settings Meta Box Callback
 */
function wades_boat_display_callback($post) {
    $display_meta = array(
        'show_on_home' => get_post_meta($post->ID, '_show_on_home', true),
        'home_order' => get_post_meta($post->ID, '_home_order', true) ?: 0,
        'card_style' => get_post_meta($post->ID, '_card_style', true) ?: 'default'
    );
    ?>
    <div class="boat-display-meta">
        <p>
            <label>
                <input type="checkbox" name="show_on_home" value="1" <?php checked($display_meta['show_on_home'], '1'); ?>>
                Show on Homepage
            </label>
        </p>
        <p>
            <label for="home_order">Display Order:</label><br>
            <input type="number" id="home_order" name="home_order" value="<?php echo esc_attr($display_meta['home_order']); ?>" 
                   class="small-text" min="0" max="99">
            <span class="description">Lower numbers appear first</span>
        </p>
        <p>
            <label for="card_style">Card Style:</label><br>
            <select id="card_style" name="card_style" class="widefat">
                <option value="default" <?php selected($display_meta['card_style'], 'default'); ?>>Default</option>
                <option value="featured" <?php selected($display_meta['card_style'], 'featured'); ?>>Featured</option>
                <option value="minimal" <?php selected($display_meta['card_style'], 'minimal'); ?>>Minimal</option>
                <option value="compact" <?php selected($display_meta['card_style'], 'compact'); ?>>Compact</option>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Save Boat Meta Box Data
 */
function wades_save_boat_meta($post_id) {
    if (!isset($_POST['wades_boat_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_boat_details_nonce'], 'wades_boat_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save basic fields
    $text_fields = array(
        'boat_condition',
        'boat_price',
        'boat_sale_price',
        'boat_status',
        'boat_year',
        'boat_make',
        'boat_model',
        'boat_length',
        'boat_beam',
        'boat_weight',
        'boat_capacity',
        'boat_fuel_capacity',
        'boat_max_speed',
        'boat_engine',
        'boat_hours',
        'boat_hull_material',
        'boat_location',
        'boat_stock_number'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save featured status
    update_post_meta($post_id, '_boat_featured', isset($_POST['boat_featured']) ? '1' : '');

    // Save gallery
    if (isset($_POST['boat_gallery'])) {
        $gallery_ids = array_filter(explode(',', sanitize_text_field($_POST['boat_gallery'])));
        update_post_meta($post_id, '_boat_gallery', $gallery_ids);
    }

    // Save features
    if (isset($_POST['boat_features']) && is_array($_POST['boat_features'])) {
        $features = array(
            'standard' => isset($_POST['boat_features']['standard']) ? array_map('sanitize_text_field', $_POST['boat_features']['standard']) : array(),
            'optional' => isset($_POST['boat_features']['optional']) ? array_map('sanitize_text_field', $_POST['boat_features']['optional']) : array()
        );
        update_post_meta($post_id, '_boat_features', $features);
    }

    // Save display settings
    update_post_meta($post_id, '_show_on_home', isset($_POST['show_on_home']) ? '1' : '');
    update_post_meta($post_id, '_home_order', isset($_POST['home_order']) ? absint($_POST['home_order']) : 0);
    update_post_meta($post_id, '_card_style', isset($_POST['card_style']) ? sanitize_text_field($_POST['card_style']) : 'default');
}
add_action('save_post_boat', 'wades_save_boat_meta'); 