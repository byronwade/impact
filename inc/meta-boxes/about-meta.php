<?php
/**
 * Custom Meta Boxes for About Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add meta boxes for About template
 */
function wades_about_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the about template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'templates/about.php' || basename($template) === 'about.php') {
            add_meta_box(
                'about_content',
                'About Page Content',
                'wades_about_content_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_about_meta_boxes');

/**
 * About Content Meta Box Callback
 */
function wades_about_content_callback($post) {
    wp_nonce_field('wades_about_meta', 'wades_about_meta_nonce');

    $meta = array(
        // Hero Section
        'about_title' => get_post_meta($post->ID, '_about_title', true),
        'about_description' => get_post_meta($post->ID, '_about_description', true),
        'about_image' => get_post_meta($post->ID, '_about_image', true),
        
        // Story Section
        'story_title' => get_post_meta($post->ID, '_story_title', true),
        'story_content' => get_post_meta($post->ID, '_story_content', true),
        'specialties' => get_post_meta($post->ID, '_specialties', true) ?: array(),
        'expertise' => get_post_meta($post->ID, '_expertise', true) ?: array(),
        
        // Features
        'about_features' => get_post_meta($post->ID, '_about_features', true) ?: array(),
        
        // Service Areas
        'service_areas' => get_post_meta($post->ID, '_service_areas', true) ?: array(),
        'shipping_services' => get_post_meta($post->ID, '_shipping_services', true) ?: array(),
        
        // Contact Info
        'contact_address' => get_post_meta($post->ID, '_contact_address', true),
        'business_hours' => get_post_meta($post->ID, '_business_hours', true) ?: array(),
        'contact_phone' => get_post_meta($post->ID, '_contact_phone', true),
        'map_image' => get_post_meta($post->ID, '_map_image', true),

        // Layout Options
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'hero' => '1',
            'story' => '1',
            'features' => '1',
            'service_areas' => '1',
            'contact' => '1'
        ),
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'hero,story,features,service_areas,contact'
    );
    ?>
    <div class="about-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="hero">Hero</button>
            <button type="button" class="tab-button" data-tab="story">Our Story</button>
            <button type="button" class="tab-button" data-tab="features">Features</button>
            <button type="button" class="tab-button" data-tab="areas">Service Areas</button>
            <button type="button" class="tab-button" data-tab="contact">Contact</button>
            <button type="button" class="tab-button" data-tab="layout">Layout</button>
        </div>

        <!-- Hero Tab -->
        <div class="tab-content active" data-tab="hero">
            <div class="meta-box-section">
                <h3>Hero Section</h3>
                <p>
                    <label for="about_title">Page Title:</label><br>
                    <input type="text" id="about_title" name="about_title" value="<?php echo esc_attr($meta['about_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="about_description">Hero Description:</label><br>
                    <textarea id="about_description" name="about_description" rows="3" class="widefat"><?php echo esc_textarea($meta['about_description']); ?></textarea>
                </p>
                <p>
                    <label>Hero Background Image:</label><br>
                    <input type="hidden" name="about_image" value="<?php echo esc_attr($meta['about_image']); ?>" class="widefat">
        <button type="button" class="button upload-image">Upload Image</button>
        <div class="image-preview">
                        <?php if ($meta['about_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['about_image'], 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>
            </div>
        </div>

        <!-- Story Tab -->
        <div class="tab-content" data-tab="story">
            <div class="meta-box-section">
                <h3>Our Story Section</h3>
                <p>
                    <label for="story_title">Section Title:</label><br>
                    <input type="text" id="story_title" name="story_title" value="<?php echo esc_attr($meta['story_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="story_content">Story Content:</label><br>
                    <textarea id="story_content" name="story_content" rows="5" class="widefat"><?php echo esc_textarea($meta['story_content']); ?></textarea>
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Specialties</h3>
                <div class="specialties-list dynamic-list">
                    <?php if (!empty($meta['specialties'])) : foreach ($meta['specialties'] as $specialty) : ?>
                        <div class="dynamic-item">
                            <input type="text" name="specialties[]" value="<?php echo esc_attr($specialty); ?>" class="widefat">
                            <button type="button" class="button remove-item">Remove</button>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <button type="button" class="button add-specialty">Add Specialty</button>
            </div>

            <div class="meta-box-section">
                <h3>Engine Expertise</h3>
                <div class="expertise-list dynamic-list">
                    <?php if (!empty($meta['expertise'])) : foreach ($meta['expertise'] as $expertise) : ?>
                        <div class="dynamic-item">
                            <input type="text" name="expertise[]" value="<?php echo esc_attr($expertise); ?>" class="widefat">
                            <button type="button" class="button remove-item">Remove</button>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <button type="button" class="button add-expertise">Add Expertise</button>
            </div>
        </div>

        <!-- Features Tab -->
        <div class="tab-content" data-tab="features">
            <div class="meta-box-section">
                <h3>Features Grid</h3>
                <div class="features-list">
                    <?php 
                    if (!empty($meta['about_features'])) :
                        foreach ($meta['about_features'] as $index => $feature) :
                    ?>
                        <div class="feature-item card">
                            <div class="card-header">
                                <h4>Feature <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-feature">Remove</button>
                            </div>
                            <div class="card-body">
                <p>
                    <label>Icon (Lucide icon name):</label><br>
                    <input type="text" name="about_features[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($feature['icon']); ?>" class="widefat">
                </p>
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="about_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="about_features[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($feature['description']); ?></textarea>
                </p>
            </div>
    </div>
    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="button add-feature">Add Feature</button>
            </div>
        </div>

        <!-- Service Areas Tab -->
        <div class="tab-content" data-tab="areas">
            <div class="meta-box-section">
                <h3>Service Areas</h3>
                <div class="areas-list dynamic-list">
                    <?php if (!empty($meta['service_areas'])) : foreach ($meta['service_areas'] as $area) : ?>
                        <div class="dynamic-item">
                            <input type="text" name="service_areas[]" value="<?php echo esc_attr($area); ?>" class="widefat">
                            <button type="button" class="button remove-item">Remove</button>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
                <button type="button" class="button add-area">Add Service Area</button>
            </div>

            <div class="meta-box-section">
                <h3>Shipping Services</h3>
                <div class="shipping-list dynamic-list">
                    <?php if (!empty($meta['shipping_services'])) : foreach ($meta['shipping_services'] as $service) : ?>
                        <div class="dynamic-item">
                            <input type="text" name="shipping_services[]" value="<?php echo esc_attr($service); ?>" class="widefat">
                            <button type="button" class="button remove-item">Remove</button>
                        </div>
                    <?php endforeach; endif; ?>
    </div>
                <button type="button" class="button add-shipping">Add Shipping Service</button>
            </div>
    </div>

        <!-- Contact Tab -->
        <div class="tab-content" data-tab="contact">
            <div class="meta-box-section">
                <h3>Contact Information</h3>
                <p>
                    <label for="contact_address">Address:</label><br>
                    <input type="text" id="contact_address" name="contact_address" value="<?php echo esc_attr($meta['contact_address']); ?>" class="widefat">
                </p>
                <p>
                    <label for="contact_phone">Phone Number:</label><br>
                    <input type="text" id="contact_phone" name="contact_phone" value="<?php echo esc_attr($meta['contact_phone']); ?>" class="widefat">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Business Hours</h3>
                <div class="hours-list dynamic-list">
                    <?php if (!empty($meta['business_hours'])) : foreach ($meta['business_hours'] as $hours) : ?>
                        <div class="dynamic-item">
                <input type="text" name="business_hours[]" value="<?php echo esc_attr($hours); ?>" class="widefat">
                            <button type="button" class="button remove-item">Remove</button>
    </div>
                    <?php endforeach; endif; ?>
    </div>
                <button type="button" class="button add-hours">Add Business Hours</button>
    </div>

            <div class="meta-box-section">
                <h3>Map Image</h3>
                <p>
                    <input type="hidden" name="map_image" value="<?php echo esc_attr($meta['map_image']); ?>" class="widefat">
                    <button type="button" class="button upload-image">Upload Map Image</button>
                    <div class="image-preview">
                        <?php if ($meta['map_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['map_image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>
        </div>

        <!-- Layout Tab -->
        <div class="tab-content" data-tab="layout">
            <div class="meta-box-section">
                <h3>Section Visibility</h3>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[hero]" value="1" <?php checked($meta['sections_visibility']['hero'], '1'); ?>>
                        Show Hero Section
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[story]" value="1" <?php checked($meta['sections_visibility']['story'], '1'); ?>>
                        Show Our Story Section
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[features]" value="1" <?php checked($meta['sections_visibility']['features'], '1'); ?>>
                        Show Features Grid
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[service_areas]" value="1" <?php checked($meta['sections_visibility']['service_areas'], '1'); ?>>
                        Show Service Areas Section
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[contact]" value="1" <?php checked($meta['sections_visibility']['contact'], '1'); ?>>
                        Show Contact Section
                    </label>
                </p>
                </div>

            <div class="meta-box-section">
                <h3>Section Order</h3>
                <p class="description">Drag and drop sections to reorder them on the page.</p>
                <ul id="section-order" class="section-order-list">
                    <?php
                    $sections = explode(',', $meta['section_order']);
                    $section_labels = array(
                        'hero' => 'Hero Section',
                        'story' => 'Our Story',
                        'features' => 'Features Grid',
                        'service_areas' => 'Service Areas',
                        'contact' => 'Contact Section'
                    );
                    foreach ($sections as $section) :
                        if (isset($section_labels[$section])) :
                    ?>
                        <li data-section="<?php echo esc_attr($section); ?>">
                            <i class="dashicons dashicons-menu"></i>
                            <?php echo esc_html($section_labels[$section]); ?>
                        </li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
                <input type="hidden" name="section_order" id="section-order-input" value="<?php echo esc_attr($meta['section_order']); ?>">
            </div>
        </div>
    </div>

    <style>
        <?php include get_template_directory() . '/inc/meta-boxes/meta-box-styles.css'; ?>
    </style>
    <?php
}

/**
 * Save About Meta Box Data
 */
function wades_save_about_meta($post_id) {
    if (!isset($_POST['wades_about_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_about_meta_nonce'], 'wades_about_meta')) {
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
        'about_title',
        'about_description',
        'story_title',
        'story_content',
        'contact_address',
        'contact_phone',
        'section_order'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save arrays
    $array_fields = array(
        'specialties',
        'expertise',
        'service_areas',
        'shipping_services',
        'business_hours'
    );

    foreach ($array_fields as $field) {
        if (isset($_POST[$field]) && is_array($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, array_map('sanitize_text_field', array_filter($_POST[$field])));
        }
    }

    // Save features
    if (isset($_POST['about_features'])) {
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

    // Save images
    $image_fields = array('about_image', 'map_image');
    foreach ($image_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, absint($_POST[$field]));
        }
    }

    // Save section visibility
    if (isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility'])) {
        $visibility = array();
        foreach ($_POST['sections_visibility'] as $section => $value) {
            $visibility[$section] = '1';
        }
        update_post_meta($post_id, '_sections_visibility', $visibility);
    }
}
add_action('save_post', 'wades_save_about_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_about_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (is_object($post) && get_page_template_slug($post->ID) == 'templates/about.php') {
            wp_enqueue_media();
            wp_enqueue_script(
                'about-admin',
                get_template_directory_uri() . '/assets/js/about-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                _S_VERSION,
                true
            );

            // Add inline script for tab functionality
            wp_add_inline_script('about-admin', '
        jQuery(document).ready(function($) {
                    // Tab functionality
                    $(".tab-button").on("click", function() {
                        $(".tab-button").removeClass("active");
                        $(".tab-content").removeClass("active");
                        $(this).addClass("active");
                        $(".tab-content[data-tab=\"" + $(this).data("tab") + "\"]").addClass("active");
                    });

                    // Section order sorting
                    $("#section-order").sortable({
                        handle: ".dashicons-menu",
                        update: function() {
                            var order = [];
                            $("#section-order li").each(function() {
                                order.push($(this).data("section"));
                            });
                            $("#section-order-input").val(order.join(","));
                        }
                    });

                    // Image upload functionality
                    $(".upload-image").on("click", function(e) {
                e.preventDefault();
                var button = $(this);
                        var imagePreview = button.siblings(".image-preview");
                        var imageInput = button.siblings("input[type=\"hidden\"]");

                        var frame = wp.media({
                            title: "Select or Upload Image",
                            button: {
                                text: "Use this image"
                            },
                    multiple: false
                        });

                        frame.on("select", function() {
                            var attachment = frame.state().get("selection").first().toJSON();
                            imageInput.val(attachment.id);
                            imagePreview.html("<img src=\"" + attachment.url + "\" style=\"max-width:150px;\">");
                        });

                        frame.open();
                    });

                    // Dynamic lists functionality
                    function setupDynamicList(addButton, container, template) {
                        $(addButton).on("click", function() {
                            var index = container.children().length;
                            var newItem = template.replace(/\{index\}/g, index);
                            container.append(newItem);
                        });

                        $(document).on("click", ".remove-item", function() {
                            $(this).closest(".dynamic-item").remove();
                        });
                    }

                    // Setup for specialties list
                    setupDynamicList(".add-specialty", $(".specialties-list"), "<div class=\"dynamic-item\"><input type=\"text\" name=\"specialties[]\" class=\"widefat\"><button type=\"button\" class=\"button remove-item\">Remove</button></div>");

                    // Setup for expertise list
                    setupDynamicList(".add-expertise", $(".expertise-list"), "<div class=\"dynamic-item\"><input type=\"text\" name=\"expertise[]\" class=\"widefat\"><button type=\"button\" class=\"button remove-item\">Remove</button></div>");

                    // Make lists sortable
                    $(".specialties-list, .expertise-list").sortable({
                        handle: ".dashicons-menu",
                        items: ".dynamic-item",
                        cursor: "move"
                    });
                });
            ');
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_about_admin_scripts'); 