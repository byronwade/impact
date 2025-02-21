<?php
/**
 * Custom Meta Boxes for Home Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function wades_add_home_meta_boxes() {
    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = get_page_template_slug();
    
    // Only add these meta boxes for the home template
    if ($template !== 'templates/home.php') {
        return;
    }

    add_meta_box(
        'wades_home_hero',
        'Hero Section',
        'wades_home_hero_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_home_brands',
        'Featured Brands Section',
        'wades_home_brands_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_home_sections',
        'Page Sections',
        'wades_home_sections_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_featured_services',
        'Featured Services Section',
        'wades_featured_services_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_home_about',
        'About Section',
        'wades_home_about_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_home_cta',
        'Call to Action Section',
        'wades_home_cta_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_home_meta_boxes');

// Register the home template
function wades_add_home_template($templates) {
    $templates['templates/home.php'] = 'Home Template';
    return $templates;
}
add_filter('theme_page_templates', 'wades_add_home_template');

// Save the meta box data
function wades_save_home_meta($post_id) {
    // Verify nonce
    if (!isset($_POST['wades_home_meta_nonce']) || !wp_verify_nonce($_POST['wades_home_meta_nonce'], 'wades_home_meta')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Verify nonce for sections
    if (isset($_POST['wades_home_sections_meta_nonce']) && 
        wp_verify_nonce($_POST['wades_home_sections_meta_nonce'], 'wades_home_sections_meta')) {
        
        // Save sections configuration
        if (isset($_POST['home_sections'])) {
            $sections = array();
            foreach ($_POST['home_sections'] as $section_id => $section_data) {
                $sections[$section_id] = array(
                    'enabled' => isset($section_data['enabled']),
                    'order' => absint($section_data['order']),
                    'title' => sanitize_text_field($section_data['title'])
                );
            }
            update_post_meta($post_id, '_home_sections', $sections);
        }
    }

    // Save brands
    if (isset($_POST['home_brands']) && is_array($_POST['home_brands'])) {
        $brands = array();
        foreach ($_POST['home_brands'] as $brand) {
            if (!empty($brand['name'])) {
                $brands[] = array(
                    'name' => sanitize_text_field($brand['name']),
                    'url' => esc_url_raw($brand['url']),
                    'image' => absint($brand['image'])
                );
            }
        }
        update_post_meta($post_id, '_home_brands', $brands);
    }

    // Hero Section
    $hero_fields = array(
        'hero_title',
        'hero_description',
        'hero_background_video',
        'hero_background_image',
        'hero_primary_cta_label',
        'hero_primary_cta_link',
        'hero_secondary_cta_label',
        'hero_secondary_cta_link'
    );

    foreach ($hero_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Featured Services
    if (isset($_POST['featured_services'])) {
        $services = array();
        foreach ($_POST['featured_services'] as $service) {
            $services[] = array(
                'icon' => wp_kses_post($service['icon']),
                'title' => sanitize_text_field($service['title']),
                'description' => wp_kses_post($service['description']),
                'link' => esc_url_raw($service['link'])
            );
        }
        update_post_meta($post_id, '_featured_services', $services);
    }

    // About Section
    if (isset($_POST['about_title'])) {
        update_post_meta($post_id, '_about_title', sanitize_text_field($_POST['about_title']));
    }
    if (isset($_POST['about_content'])) {
        update_post_meta($post_id, '_about_content', wp_kses_post($_POST['about_content']));
    }
    if (isset($_POST['about_image'])) {
        update_post_meta($post_id, '_about_image', absint($_POST['about_image']));
    }
    if (isset($_POST['about_cta_text'])) {
        update_post_meta($post_id, '_about_cta_text', sanitize_text_field($_POST['about_cta_text']));
    }
    if (isset($_POST['about_cta_link'])) {
        update_post_meta($post_id, '_about_cta_link', esc_url_raw($_POST['about_cta_link']));
    }

    // CTA Section
    if (isset($_POST['cta_title'])) {
        update_post_meta($post_id, '_cta_title', sanitize_text_field($_POST['cta_title']));
    }
    if (isset($_POST['cta_description'])) {
        update_post_meta($post_id, '_cta_description', wp_kses_post($_POST['cta_description']));
    }
    if (isset($_POST['cta_button_text'])) {
        update_post_meta($post_id, '_cta_button_text', sanitize_text_field($_POST['cta_button_text']));
    }
    if (isset($_POST['cta_button_link'])) {
        update_post_meta($post_id, '_cta_button_link', esc_url_raw($_POST['cta_button_link']));
    }
}
add_action('save_post', 'wades_save_home_meta');

// Add necessary JavaScript
function wades_home_meta_scripts() {
    global $post;
    if (!$post || get_page_template_slug($post->ID) !== 'templates/home.php') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
    
    // Add inline script for media uploads and dynamic fields
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Video upload
            $('.upload-video-button').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var customUploader = wp.media({
                    title: 'Select Video',
                    library: { type: 'video' },
                    button: { text: 'Use this video' },
                    multiple: false
                }).on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    button.siblings('.video-url').val(attachment.url);
                    button.siblings('.remove-video-button').show();
                    $('#video-preview').html('<video width="300" controls><source src="' + attachment.url + '" type="video/mp4"></video>');
                }).open();
            });

            // Image upload
            $('.upload-image').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var customUploader = wp.media({
                    title: 'Select Image',
                    library: { type: 'image' },
                    button: { text: 'Use this image' },
                    multiple: false
                }).on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    button.siblings('input[type="hidden"]').val(attachment.id);
                    button.siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
                }).open();
            });

            // Remove video
            $('.remove-video-button').click(function() {
                $(this).siblings('.video-url').val('');
                $(this).hide();
                $('#video-preview').empty();
            });

            // Add service
            $('.add-service').click(function() {
                var index = $('.service-item').length;
                var template = wp.template('service-item');
                $(this).before(template({ index: index }));
            });

            // Remove service
            $(document).on('click', '.remove-service', function() {
                $(this).closest('.service-item').remove();
            });

            $('.sections-list').sortable({
                handle: '.dashicons-menu',
                update: function(event, ui) {
                    $('.sections-list .section-item').each(function(index) {
                        $(this).find('.section-order').val((index + 1) * 10);
                    });
                }
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'wades_home_meta_scripts');

// Add necessary styles
function wades_home_meta_styles() {
    global $post;
    if (!$post || get_page_template_slug($post->ID) !== 'templates/home.php') {
        return;
    }
    ?>
    <style>
        .home-meta-box {
            padding: 10px;
        }
        .video-upload-container {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .video-preview {
            margin-top: 10px;
        }
        .service-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
        }
        .cta-section {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
        }
        .home-sections-manager .section-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .home-sections-manager .section-item:hover {
            background: #f0f0f0 !important;
        }
        .home-sections-manager .ui-sortable-helper {
            background: #fff !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }
    </style>
    <?php
}
add_action('admin_head', 'wades_home_meta_styles');

// Add section management meta box
function wades_home_sections_callback($post) {
    wp_nonce_field('wades_home_sections_meta', 'wades_home_sections_meta_nonce');

    // Get sections configuration
    $sections = get_post_meta($post->ID, '_home_sections', true);
    
    // Default sections configuration
    if (!is_array($sections)) {
        $sections = array(
            'hero' => array(
                'enabled' => true,
                'order' => 10,
                'title' => 'Hero Section'
            ),
            'featured_brands' => array(
                'enabled' => true,
                'order' => 20,
                'title' => 'Featured Brands'
            ),
            'fleet' => array(
                'enabled' => true,
                'order' => 30,
                'title' => 'Fleet Section'
            ),
            'services' => array(
                'enabled' => true,
                'order' => 40,
                'title' => 'Services Section'
            ),
            'testimonials' => array(
                'enabled' => true,
                'order' => 50,
                'title' => 'Testimonials'
            ),
            'social' => array(
                'enabled' => true,
                'order' => 60,
                'title' => 'Social Feed'
            ),
            'cta' => array(
                'enabled' => true,
                'order' => 70,
                'title' => 'Call to Action'
            )
        );
    }

    // Sort sections by order
    uasort($sections, function($a, $b) {
        return $a['order'] - $b['order'];
    });
    ?>
    <div class="home-sections-manager">
        <p class="description">Enable/disable sections and drag to reorder them. Changes will be reflected on the home page.</p>
        <div class="sections-list" style="margin-top: 15px;">
            <?php foreach ($sections as $section_id => $section) : ?>
                <div class="section-item" style="padding: 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 5px;">
                    <input type="hidden" 
                           name="home_sections[<?php echo esc_attr($section_id); ?>][order]" 
                           value="<?php echo esc_attr($section['order']); ?>"
                           class="section-order">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <span class="dashicons dashicons-menu" style="cursor: move;"></span>
                        <input type="checkbox" 
                               name="home_sections[<?php echo esc_attr($section_id); ?>][enabled]" 
                               <?php checked($section['enabled']); ?>>
                        <?php echo esc_html($section['title']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

// Featured Services Section Callback
function wades_featured_services_callback($post) {
    $services_title = get_post_meta($post->ID, '_services_section_title', true);
    $featured_services = get_post_meta($post->ID, '_featured_services', true);

    if (!is_array($featured_services)) {
        $featured_services = array(array(
            'icon' => '',
            'title' => '',
            'description' => '',
            'link' => ''
        ));
    }
    ?>
    <p>
        <label for="services_section_title">Section Title:</label><br>
        <input type="text" id="services_section_title" name="services_section_title" value="<?php echo esc_attr($services_title); ?>" class="widefat">
    </p>

    <div class="featured-services">
        <h4>Featured Services</h4>
        <?php foreach ($featured_services as $index => $service) : ?>
            <div class="service-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Icon (HTML):</label><br>
                    <input type="text" name="featured_services[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($service['icon']); ?>" class="widefat">
                </p>
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="featured_services[<?php echo $index; ?>][title]" value="<?php echo esc_attr($service['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="featured_services[<?php echo $index; ?>][description]" rows="3" class="widefat"><?php echo esc_textarea($service['description']); ?></textarea>
                </p>
                <p>
                    <label>Link:</label><br>
                    <input type="text" name="featured_services[<?php echo $index; ?>][link]" value="<?php echo esc_url($service['link']); ?>" class="widefat">
                </p>
                <button type="button" class="button remove-service">Remove Service</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-service">Add Service</button>
    </div>
    <?php
}

// About Section Callback
function wades_home_about_callback($post) {
    $about_title = get_post_meta($post->ID, '_about_title', true);
    $about_content = get_post_meta($post->ID, '_about_content', true);
    $about_image = get_post_meta($post->ID, '_about_image', true);
    $about_cta_text = get_post_meta($post->ID, '_about_cta_text', true);
    $about_cta_link = get_post_meta($post->ID, '_about_cta_link', true);
    ?>
    <p>
        <label for="about_title">Section Title:</label><br>
        <input type="text" id="about_title" name="about_title" value="<?php echo esc_attr($about_title); ?>" class="widefat">
    </p>
    <p>
        <label for="about_content">Content:</label><br>
        <?php wp_editor($about_content, 'about_content', array('textarea_name' => 'about_content')); ?>
    </p>
    <p>
        <label for="about_image">Image:</label><br>
        <input type="hidden" id="about_image" name="about_image" value="<?php echo esc_attr($about_image); ?>">
        <button type="button" class="button upload-image">Upload Image</button>
        <div class="image-preview">
            <?php if ($about_image) : ?>
                <?php echo wp_get_attachment_image($about_image, 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>
    <p>
        <label for="about_cta_text">CTA Button Text:</label><br>
        <input type="text" id="about_cta_text" name="about_cta_text" value="<?php echo esc_attr($about_cta_text); ?>" class="widefat">
    </p>
    <p>
        <label for="about_cta_link">CTA Button Link:</label><br>
        <input type="text" id="about_cta_link" name="about_cta_link" value="<?php echo esc_url($about_cta_link); ?>" class="widefat">
    </p>
    <?php
}

// Call to Action Section Callback
function wades_home_cta_callback($post) {
    $cta_title = get_post_meta($post->ID, '_cta_title', true);
    $cta_description = get_post_meta($post->ID, '_cta_description', true);
    $cta_button_text = get_post_meta($post->ID, '_cta_button_text', true);
    $cta_button_link = get_post_meta($post->ID, '_cta_button_link', true);
    ?>
    <p>
        <label for="cta_title">CTA Title:</label><br>
        <input type="text" id="cta_title" name="cta_title" value="<?php echo esc_attr($cta_title); ?>" class="widefat">
    </p>
    <p>
        <label for="cta_description">CTA Description:</label><br>
        <textarea id="cta_description" name="cta_description" rows="3" class="widefat"><?php echo esc_textarea($cta_description); ?></textarea>
    </p>
    <p>
        <label for="cta_button_text">Button Text:</label><br>
        <input type="text" id="cta_button_text" name="cta_button_text" value="<?php echo esc_attr($cta_button_text); ?>" class="widefat">
    </p>
    <p>
        <label for="cta_button_link">Button Link:</label><br>
        <input type="text" id="cta_button_link" name="cta_button_link" value="<?php echo esc_url($cta_button_link); ?>" class="widefat">
    </p>
    <?php
}

// Hero Section Callback
function wades_home_hero_callback($post) {
    wp_nonce_field('wades_home_meta', 'wades_home_meta_nonce');

    $hero_meta = array(
        'title' => get_post_meta($post->ID, '_hero_title', true) ?: 'Expert Boat Service You Can Trust',
        'description' => get_post_meta($post->ID, '_hero_description', true) ?: 'Certified Technicians, Fast Turnaround, and Unmatched Care for Your Boat.',
        'phoneNumber' => get_post_meta($post->ID, '_hero_phone_number', true) ?: '(555) 123-4567',
        'rating' => array(
            'value' => get_post_meta($post->ID, '_hero_rating_value', true) ?: 4.9,
            'text' => get_post_meta($post->ID, '_hero_rating_text', true) ?: '4.9 Star Rated'
        ),
        'backgroundImage' => get_post_meta($post->ID, '_hero_background_image', true),
        'backgroundVideo' => get_post_meta($post->ID, '_hero_background_video', true),
        'primaryCta' => array(
            'label' => get_post_meta($post->ID, '_hero_primary_cta_label', true) ?: 'Schedule Service',
            'link' => get_post_meta($post->ID, '_hero_primary_cta_link', true) ?: '/schedule',
            'icon' => get_post_meta($post->ID, '_hero_primary_cta_icon', true) ?: 'calendar'
        ),
        'secondaryCta' => array(
            'label' => get_post_meta($post->ID, '_hero_secondary_cta_label', true) ?: 'View Our Services',
            'link' => get_post_meta($post->ID, '_hero_secondary_cta_link', true) ?: '/services',
            'icon' => get_post_meta($post->ID, '_hero_secondary_cta_icon', true) ?: 'chevron-right'
        )
    );
    ?>
    <div class="home-meta-box">
        <p>
            <label for="hero_title">Hero Title:</label>
            <input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($hero_meta['title']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_description">Hero Description:</label>
            <textarea id="hero_description" name="hero_description" rows="3" class="widefat"><?php echo esc_textarea($hero_meta['description']); ?></textarea>
        </p>

        <p>
            <label for="hero_phone_number">Phone Number:</label>
            <input type="text" id="hero_phone_number" name="hero_phone_number" value="<?php echo esc_attr($hero_meta['phoneNumber']); ?>" class="widefat">
        </p>

        <div class="rating-section">
            <h4>Rating Badge</h4>
            <p>
                <label for="hero_rating_value">Rating Value (0-5):</label>
                <input type="number" id="hero_rating_value" name="hero_rating_value" value="<?php echo esc_attr($hero_meta['rating']['value']); ?>" min="0" max="5" step="0.1" class="widefat">
            </p>
            <p>
                <label for="hero_rating_text">Rating Text:</label>
                <input type="text" id="hero_rating_text" name="hero_rating_text" value="<?php echo esc_attr($hero_meta['rating']['text']); ?>" class="widefat">
            </p>
        </div>
        
        <!-- Background Video Field -->
        <div class="video-field">
            <label for="hero_background_video">Background Video:</label>
            <div class="video-upload-container">
                <input type="text" id="hero_background_video" name="hero_background_video" value="<?php echo esc_url($hero_meta['backgroundVideo']); ?>" class="widefat video-url" readonly>
                <button type="button" class="button upload-video-button">Upload Video</button>
                <button type="button" class="button remove-video-button" <?php echo empty($hero_meta['backgroundVideo']) ? 'style="display:none;"' : ''; ?>>Remove</button>
            </div>
            <p class="description">MP4 format recommended. Will fallback to background image if video cannot be played.</p>
            <div id="video-preview" class="video-preview">
                <?php if ($hero_meta['backgroundVideo']) : ?>
                    <video width="300" controls>
                        <source src="<?php echo esc_url($hero_meta['backgroundVideo']); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
        </div>

        <!-- Background Image Field -->
        <div class="image-field">
            <label for="hero_background_image">Background Image:</label>
            <div class="image-upload-container">
                <input type="hidden" id="hero_background_image" name="hero_background_image" value="<?php echo esc_attr($hero_meta['backgroundImage']); ?>">
                <button type="button" class="button upload-image-button">Upload Image</button>
                <div class="image-preview">
                    <?php if ($hero_meta['backgroundImage']) : ?>
                        <?php echo wp_get_attachment_image($hero_meta['backgroundImage'], 'medium'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <p class="description">Recommended size: 1920x1080px</p>
        </div>

        <div class="cta-section">
            <h4>Primary Call to Action</h4>
            <div class="primary-cta">
                <p>
                    <label for="hero_primary_cta_label">Button Text:</label>
                    <input type="text" id="hero_primary_cta_label" name="hero_primary_cta_label" value="<?php echo esc_attr($hero_meta['primaryCta']['label']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_primary_cta_link">Button Link:</label>
                    <input type="text" id="hero_primary_cta_link" name="hero_primary_cta_link" value="<?php echo esc_attr($hero_meta['primaryCta']['link']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_primary_cta_icon">Icon:</label>
                    <select id="hero_primary_cta_icon" name="hero_primary_cta_icon" class="widefat">
                        <option value="calendar" <?php selected($hero_meta['primaryCta']['icon'], 'calendar'); ?>>Calendar</option>
                        <option value="none" <?php selected($hero_meta['primaryCta']['icon'], 'none'); ?>>None</option>
                    </select>
                </p>
            </div>

            <h4>Secondary Call to Action</h4>
            <div class="secondary-cta">
                <p>
                    <label for="hero_secondary_cta_label">Button Text:</label>
                    <input type="text" id="hero_secondary_cta_label" name="hero_secondary_cta_label" value="<?php echo esc_attr($hero_meta['secondaryCta']['label']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_secondary_cta_link">Button Link:</label>
                    <input type="text" id="hero_secondary_cta_link" name="hero_secondary_cta_link" value="<?php echo esc_attr($hero_meta['secondaryCta']['link']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_secondary_cta_icon">Icon:</label>
                    <select id="hero_secondary_cta_icon" name="hero_secondary_cta_icon" class="widefat">
                        <option value="chevron-right" <?php selected($hero_meta['secondaryCta']['icon'], 'chevron-right'); ?>>Chevron Right</option>
                        <option value="none" <?php selected($hero_meta['secondaryCta']['icon'], 'none'); ?>>None</option>
                    </select>
                </p>
            </div>
        </div>
    </div>
    <?php
}

// Add this new function for the brands meta box
function wades_home_brands_callback($post) {
    $brands = get_post_meta($post->ID, '_home_brands', true) ?: array(
        array(
            'image' => '',
            'name' => 'Sea Fox',
            'url' => ''
        ),
        array(
            'image' => '',
            'name' => 'Bennington',
            'url' => ''
        ),
        array(
            'image' => '',
            'name' => 'Yamaha',
            'url' => ''
        ),
        array(
            'image' => '',
            'name' => 'Mercury',
            'url' => ''
        ),
        array(
            'image' => '',
            'name' => 'Suzuki',
            'url' => ''
        )
    );
    ?>
    <div class="brands-meta-box">
        <div class="brands-list">
            <?php foreach ($brands as $index => $brand) : ?>
                <div class="brand-item" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <h4>Brand <?php echo $index + 1; ?></h4>
                    <p>
                        <label>Brand Name:</label><br>
                        <input type="text" name="home_brands[<?php echo $index; ?>][name]" 
                               value="<?php echo esc_attr($brand['name']); ?>" class="widefat">
                    </p>
                    <p>
                        <label>Brand URL:</label><br>
                        <input type="url" name="home_brands[<?php echo $index; ?>][url]" 
                               value="<?php echo esc_url($brand['url']); ?>" class="widefat">
                    </p>
                    <p>
                        <label>Brand Logo:</label><br>
                        <input type="hidden" name="home_brands[<?php echo $index; ?>][image]" 
                               value="<?php echo esc_attr($brand['image']); ?>" class="brand-image-input">
                        <button type="button" class="button upload-brand-image">Upload Logo</button>
                        <button type="button" class="button remove-brand-image" style="color: #a00;">Remove Logo</button>
                        <div class="brand-image-preview" style="margin-top: 10px;">
                            <?php if ($brand['image']) : ?>
                                <?php echo wp_get_attachment_image($brand['image'], 'thumbnail'); ?>
                            <?php endif; ?>
                        </div>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.upload-brand-image').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.brand-item');
            var imageInput = container.find('.brand-image-input');
            var imagePreview = container.find('.brand-image-preview');

            var frame = wp.media({
                title: 'Select Brand Logo',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                imageInput.val(attachment.id);
                imagePreview.html('<img src="' + attachment.url + '" style="max-width: 150px;">');
            });

            frame.open();
        });

        $('.remove-brand-image').click(function(e) {
            e.preventDefault();
            var container = $(this).closest('.brand-item');
            container.find('.brand-image-input').val('');
            container.find('.brand-image-preview').empty();
        });
    });
    </script>
    <?php
}

// Add this to your functions to ensure the template is loaded
function wades_load_home_template($template) {
    if (is_page()) {
        $page_template = get_page_template_slug();
        error_log('Current template: ' . $page_template);
        
        if ($page_template === 'templates/home.php') {
            $new_template = locate_template(array('templates/home.php'));
            if (!empty($new_template)) {
                error_log('Loading home template: ' . $new_template);
                return $new_template;
            }
        }
    }
    return $template;
}
add_filter('template_include', 'wades_load_home_template', 99); 