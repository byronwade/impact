<?php
/**
 * Custom Meta Boxes for About Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function wades_about_meta_boxes($post_type, $post) {
    // Only add meta boxes for pages
    if ($post_type !== 'page') {
        return;
    }

    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = '';
    if (isset($_GET['post'])) {
        $template = get_page_template_slug($_GET['post']);
    } elseif (isset($_POST['post_ID'])) {
        $template = get_page_template_slug($_POST['post_ID']);
    }

    // Add meta boxes based on template
    if ($template === 'templates/about.php') {
        // Hero Section
        add_meta_box(
            'wades_about_hero',
            'Hero Section',
            'wades_about_hero_callback',
            'page',
            'normal',
            'high'
        );

        // Our Story Section
        add_meta_box(
            'wades_about_story',
            'Our Story Section',
            'wades_about_story_callback',
            'page',
            'normal',
            'high'
        );

        // Features Section
        add_meta_box(
            'wades_about_features',
            'Features Section',
            'wades_about_features_callback',
            'page',
            'normal',
            'high'
        );

        // Service Areas Section
        add_meta_box(
            'wades_service_areas',
            'Service Areas Section',
            'wades_service_areas_callback',
            'page',
            'normal',
            'high'
        );

        // Contact Section
        add_meta_box(
            'wades_about_contact',
            'Contact Section',
            'wades_about_contact_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'wades_about_meta_boxes', 10, 2);

// Hero Section Callback
function wades_about_hero_callback($post) {
    wp_nonce_field('wades_about_meta_box', 'wades_about_meta_box_nonce');

    $hero_meta = array(
        'title' => get_post_meta($post->ID, '_about_title', true) ?: 'About Impact Marine Group',
        'description' => get_post_meta($post->ID, '_about_description', true) ?: 'Impact Marine Group is dedicated to providing our customers personal service and quality boat brands.',
        'background_image' => get_post_meta($post->ID, '_about_image', true)
    );
    ?>
    <p>
        <label for="about_title">Hero Title:</label><br>
        <input type="text" id="about_title" name="about_title" value="<?php echo esc_attr($hero_meta['title']); ?>" class="widefat">
    </p>
    <p>
        <label for="about_description">Hero Description:</label><br>
        <textarea id="about_description" name="about_description" rows="3" class="widefat"><?php echo esc_textarea($hero_meta['description']); ?></textarea>
    </p>
    <p>
        <label for="about_image">Background Image:</label><br>
        <input type="hidden" id="about_image" name="about_image" value="<?php echo esc_attr($hero_meta['background_image']); ?>">
        <button type="button" class="button upload-image" data-uploader-title="Select Background Image" data-uploader-button-text="Use this image">Upload Image</button>
        <div class="image-preview">
            <?php if ($hero_meta['background_image']) : ?>
                <?php echo wp_get_attachment_image($hero_meta['background_image'], 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>
    <?php
}

// Our Story Section Callback
function wades_about_story_callback($post) {
    $story_meta = array(
        'content' => get_post_meta($post->ID, '_about_story_content', true) ?: array(
            'Located at 5185 Browns Bridge Rd, our boat sales and marine services location is dedicated to getting you exactly what you need.',
            'Our Marine Services Department is one of the most trusted service and repair centers in North Georgia.',
            'Our team of technicians are factory trained and certified by some of the biggest names in marine.'
        )
    );
    ?>
    <div class="story-paragraphs">
        <?php foreach ($story_meta['content'] as $index => $paragraph) : ?>
        <p>
            <label for="about_story_content_<?php echo $index; ?>">Paragraph <?php echo $index + 1; ?>:</label><br>
            <textarea id="about_story_content_<?php echo $index; ?>" name="about_story_content[]" rows="3" class="widefat"><?php echo esc_textarea($paragraph); ?></textarea>
        </p>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button add-paragraph">Add Paragraph</button>
    <?php
}

// Features Section Callback
function wades_about_features_callback($post) {
    $features = get_post_meta($post->ID, '_about_features', true);
    
    if (!is_array($features) || empty($features)) {
        $features = array(
            array(
                'icon' => 'shield',
                'title' => 'Factory Certified',
                'description' => 'Our technicians are factory trained and certified by leading marine manufacturers'
            ),
            array(
                'icon' => 'tool',
                'title' => 'Expert Service',
                'description' => 'Comprehensive maintenance, repairs, and winterization services'
            ),
            array(
                'icon' => 'map',
                'title' => 'Wide Coverage',
                'description' => 'Serving Lake Lanier and all Georgia Lakes with nationwide shipping'
            ),
            array(
                'icon' => 'heart',
                'title' => 'Passion for Boating',
                'description' => 'We don\'t just sell boats - we live the boating lifestyle'
            )
        );
    }
    ?>
    <div class="features-container">
        <?php foreach ($features as $index => $feature) : ?>
        <div class="feature-item">
            <p>
                <label for="feature_icon_<?php echo $index; ?>">Icon (Lucide icon name):</label><br>
                <input type="text" id="feature_icon_<?php echo $index; ?>" name="about_features[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($feature['icon']); ?>" class="widefat">
            </p>
            <p>
                <label for="feature_title_<?php echo $index; ?>">Title:</label><br>
                <input type="text" id="feature_title_<?php echo $index; ?>" name="about_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title']); ?>" class="widefat">
            </p>
            <p>
                <label for="feature_description_<?php echo $index; ?>">Description:</label><br>
                <textarea id="feature_description_<?php echo $index; ?>" name="about_features[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($feature['description']); ?></textarea>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button add-feature">Add Feature</button>
    <?php
}

// Service Areas Section Callback
function wades_service_areas_callback($post) {
    $service_areas = get_post_meta($post->ID, '_service_areas', true);
    
    if (!is_array($service_areas) || empty($service_areas)) {
        $service_areas = array(
            'Lake Lanier',
            'Lake Allatoona',
            'Lake Burton',
            'Lake Sinclair',
            'Lake Hartwell',
            'All Georgia Lakes'
        );
    }

    $shipping_services = get_post_meta($post->ID, '_shipping_services', true);
    
    if (!is_array($shipping_services) || empty($shipping_services)) {
        $shipping_services = array(
            'Marine Parts & Accessories',
            'Engine & Electrical Components',
            'Marine Lighting & Dock Supplies',
            'Cleaners & Maintenance Products'
        );
    }
    ?>
    <div class="service-areas-section">
        <h4>Georgia Lakes We Serve</h4>
        <div class="service-areas-list">
            <?php foreach ($service_areas as $index => $area) : ?>
            <p>
                <input type="text" name="service_areas[]" value="<?php echo esc_attr($area); ?>" class="widefat">
            </p>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-service-area">Add Service Area</button>

        <h4>National Shipping Services</h4>
        <div class="shipping-services-list">
            <?php foreach ($shipping_services as $index => $service) : ?>
            <p>
                <input type="text" name="shipping_services[]" value="<?php echo esc_attr($service); ?>" class="widefat">
            </p>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-shipping-service">Add Shipping Service</button>
    </div>
    <?php
}

// Contact Section Callback
function wades_about_contact_callback($post) {
    $contact_meta = array(
        'address' => get_post_meta($post->ID, '_contact_address', true) ?: '5185 Browns Bridge Rd',
        'business_hours' => get_post_meta($post->ID, '_business_hours', true) ?: array(
            'Monday - Friday: 9AM-6PM',
            'Saturday: 10AM-4PM',
            'Sunday: Closed'
        ),
        'phone' => get_post_meta($post->ID, '_contact_phone', true) ?: '770-881-7808',
        'map_image' => get_post_meta($post->ID, '_map_image', true)
    );
    ?>
    <p>
        <label for="contact_address">Address:</label><br>
        <input type="text" id="contact_address" name="contact_address" value="<?php echo esc_attr($contact_meta['address']); ?>" class="widefat">
    </p>
    <div class="business-hours">
        <label>Business Hours:</label><br>
        <?php foreach ($contact_meta['business_hours'] as $index => $hours) : ?>
        <p>
            <input type="text" name="business_hours[]" value="<?php echo esc_attr($hours); ?>" class="widefat">
        </p>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button add-hours">Add Hours</button>
    <p>
        <label for="contact_phone">Phone Number:</label><br>
        <input type="text" id="contact_phone" name="contact_phone" value="<?php echo esc_attr($contact_meta['phone']); ?>" class="widefat">
    </p>
    <p>
        <label for="map_image">Map Image:</label><br>
        <input type="hidden" id="map_image" name="map_image" value="<?php echo esc_attr($contact_meta['map_image']); ?>">
        <button type="button" class="button upload-image" data-uploader-title="Select Map Image" data-uploader-button-text="Use this image">Upload Image</button>
        <div class="image-preview">
            <?php if ($contact_meta['map_image']) : ?>
                <?php echo wp_get_attachment_image($contact_meta['map_image'], 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>
    <?php
}

// Save meta box data
function wades_save_about_meta($post_id) {
    // Check if our nonce is set and verify it
    if (!isset($_POST['wades_about_meta_box_nonce']) || !wp_verify_nonce($_POST['wades_about_meta_box_nonce'], 'wades_about_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Hero Section
    if (isset($_POST['about_title'])) {
        update_post_meta($post_id, '_about_title', sanitize_text_field($_POST['about_title']));
    }
    if (isset($_POST['about_description'])) {
        update_post_meta($post_id, '_about_description', wp_kses_post($_POST['about_description']));
    }
    if (isset($_POST['about_image'])) {
        update_post_meta($post_id, '_about_image', absint($_POST['about_image']));
    }

    // Our Story Section
    if (isset($_POST['about_story_content']) && is_array($_POST['about_story_content'])) {
        $story_content = array_map('wp_kses_post', $_POST['about_story_content']);
        update_post_meta($post_id, '_about_story_content', $story_content);
    }

    // Features Section
    if (isset($_POST['about_features']) && is_array($_POST['about_features'])) {
        $features = array();
        foreach ($_POST['about_features'] as $feature) {
            if (!empty($feature['title'])) {
                $features[] = array(
                    'icon' => sanitize_text_field($feature['icon']),
                    'title' => sanitize_text_field($feature['title']),
                    'description' => wp_kses_post($feature['description'])
                );
            }
        }
        update_post_meta($post_id, '_about_features', $features);
    }

    // Service Areas Section
    if (isset($_POST['service_areas']) && is_array($_POST['service_areas'])) {
        $areas = array_map('sanitize_text_field', array_filter($_POST['service_areas']));
        update_post_meta($post_id, '_service_areas', $areas);
    }
    if (isset($_POST['shipping_services']) && is_array($_POST['shipping_services'])) {
        $services = array_map('sanitize_text_field', array_filter($_POST['shipping_services']));
        update_post_meta($post_id, '_shipping_services', $services);
    }

    // Contact Section
    if (isset($_POST['contact_address'])) {
        update_post_meta($post_id, '_contact_address', sanitize_text_field($_POST['contact_address']));
    }
    if (isset($_POST['business_hours']) && is_array($_POST['business_hours'])) {
        $hours = array_map('sanitize_text_field', array_filter($_POST['business_hours']));
        update_post_meta($post_id, '_business_hours', $hours);
    }
    if (isset($_POST['contact_phone'])) {
        update_post_meta($post_id, '_contact_phone', sanitize_text_field($_POST['contact_phone']));
    }
    if (isset($_POST['map_image'])) {
        update_post_meta($post_id, '_map_image', absint($_POST['map_image']));
    }
}
add_action('save_post', 'wades_save_about_meta');

// Add JavaScript for dynamic fields
function wades_about_admin_scripts() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'page' && get_page_template_slug() === 'templates/about.php') {
        wp_enqueue_media();
        wp_enqueue_script(
            'wades-about-admin',
            get_template_directory_uri() . '/assets/js/about-admin.js',
            array('jquery'),
            _S_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'wades_about_admin_scripts'); 