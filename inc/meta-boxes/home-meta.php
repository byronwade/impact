<?php
/**
 * Custom Meta Boxes for Home Page Template
 */

function wades_add_home_meta_boxes() {
    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = get_page_template_slug();

    // Add meta boxes for all pages or specifically for home template
    add_meta_box(
        'wades_home_hero',
        'Hero Section',
        'wades_home_hero_callback',
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

// Add this to register the template
function wades_add_home_template($templates) {
    $templates['templates/home.php'] = 'Home Template';
    return $templates;
}
add_filter('theme_page_templates', 'wades_add_home_template');

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
        'heading' => get_post_meta($post->ID, '_hero_heading', true),
        'subheading' => get_post_meta($post->ID, '_hero_subheading', true),
        'video_url' => get_post_meta($post->ID, '_hero_video_url', true),
        'primary_text' => get_post_meta($post->ID, '_hero_primary_text', true),
        'primary_link' => get_post_meta($post->ID, '_hero_primary_link', true),
        'secondary_text' => get_post_meta($post->ID, '_hero_secondary_text', true),
        'secondary_link' => get_post_meta($post->ID, '_hero_secondary_link', true),
        'phone' => get_post_meta($post->ID, '_hero_phone', true),
        'rating' => get_post_meta($post->ID, '_hero_rating', true)
    );
    ?>
    <div class="home-meta-box">
        <p>
            <label for="hero_heading">Hero Heading:</label>
            <input type="text" id="hero_heading" name="hero_heading" value="<?php echo esc_attr($hero_meta['heading']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_subheading">Hero Subheading:</label>
            <input type="text" id="hero_subheading" name="hero_subheading" value="<?php echo esc_attr($hero_meta['subheading']); ?>" class="widefat">
        </p>
        
        <!-- Video Upload Field -->
        <div class="video-field">
            <label for="hero_video_url">Background Video:</label>
            <div class="video-upload-container">
                <input type="text" id="hero_video_url" name="hero_video_url" value="<?php echo esc_attr($hero_meta['video_url']); ?>" class="widefat video-url" readonly>
                <button type="button" class="button upload-video-button">Upload Video</button>
                <button type="button" class="button remove-video-button" <?php echo empty($hero_meta['video_url']) ? 'style="display:none;"' : ''; ?>>Remove</button>
            </div>
            <div id="video-preview" class="video-preview">
                <?php if ($hero_meta['video_url']) : ?>
                    <video width="300" controls>
                        <source src="<?php echo esc_url($hero_meta['video_url']); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
        </div>

        <div class="cta-section">
            <h4>Call to Action Buttons</h4>
            <div class="primary-cta">
                <p>
                    <label for="hero_primary_text">Primary Button Text:</label>
                    <input type="text" id="hero_primary_text" name="hero_primary_text" value="<?php echo esc_attr($hero_meta['primary_text']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_primary_link">Primary Button Link:</label>
                    <input type="text" id="hero_primary_link" name="hero_primary_link" value="<?php echo esc_attr($hero_meta['primary_link']); ?>" class="widefat">
                </p>
            </div>
            <div class="secondary-cta">
                <p>
                    <label for="hero_secondary_text">Secondary Button Text:</label>
                    <input type="text" id="hero_secondary_text" name="hero_secondary_text" value="<?php echo esc_attr($hero_meta['secondary_text']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_secondary_link">Secondary Button Link:</label>
                    <input type="text" id="hero_secondary_link" name="hero_secondary_link" value="<?php echo esc_attr($hero_meta['secondary_link']); ?>" class="widefat">
                </p>
            </div>
        </div>
        <p>
            <label for="hero_phone">Phone Number:</label>
            <input type="text" id="hero_phone" name="hero_phone" value="<?php echo esc_attr($hero_meta['phone']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_rating">Rating Text:</label>
            <input type="text" id="hero_rating" name="hero_rating" value="<?php echo esc_attr($hero_meta['rating']); ?>" class="widefat">
        </p>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Video Upload
        $('.upload-video-button').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.video-field');
            var videoUploader = wp.media({
                title: 'Select Background Video',
                button: {
                    text: 'Use this video'
                },
                multiple: false,
                library: {
                    type: 'video'
                }
            }).on('select', function() {
                var attachment = videoUploader.state().get('selection').first().toJSON();
                container.find('.video-url').val(attachment.url);
                container.find('.remove-video-button').show();
                var preview = container.find('.video-preview');
                preview.html('<video width="300" controls><source src="' + attachment.url + '" type="video/mp4"></video>');
            }).open();
        });

        // Remove Video
        $('.remove-video-button').click(function(e) {
            e.preventDefault();
            var container = $(this).closest('.video-field');
            container.find('.video-url').val('');
            container.find('.video-preview').empty();
            $(this).hide();
        });
    });
    </script>
    <?php
}

// Save meta box data
function wades_save_home_meta($post_id) {
    // Basic security checks
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (get_post_type($post_id) !== 'page') return;

    // Verify nonce
    if (!isset($_POST['wades_home_meta_nonce']) || !wp_verify_nonce($_POST['wades_home_meta_nonce'], 'wades_home_meta')) {
        return;
    }

    // Save hero section fields
    $text_fields = array(
        'hero_heading',
        'hero_subheading',
        'hero_primary_text',
        'hero_primary_link',
        'hero_secondary_text',
        'hero_secondary_link',
        'hero_phone',
        'hero_rating'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save video URL separately
    if (isset($_POST['hero_video_url'])) {
        update_post_meta($post_id, '_hero_video_url', esc_url_raw($_POST['hero_video_url']));
    }

    // Save Featured Services
    if (isset($_POST['services_section_title'])) {
        update_post_meta($post_id, '_services_section_title', sanitize_text_field($_POST['services_section_title']));
    }
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

    // Save About Section
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

    // Save CTA Section
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
add_action('save_post_page', 'wades_save_home_meta');

// Add JavaScript for dynamic fields and image upload
function wades_home_meta_scripts() {
    global $post;
    if (!$post) return;
    
    if (get_page_template_slug($post->ID) === 'templates/home.php') {
        ?>
        <script>
            jQuery(document).ready(function($) {
                // Image Upload
                $('.upload-image').click(function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var customUploader = wp.media({
                        title: 'Select Image',
                        button: { text: 'Use this image' },
                        multiple: false
                    }).on('select', function() {
                        var attachment = customUploader.state().get('selection').first().toJSON();
                        button.siblings('input[type="hidden"]').val(attachment.id);
                        button.siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
                    }).open();
                });

                // Add Service
                $('.add-service').click(function() {
                    var index = $('.service-item').length;
                    var newService = '<div class="service-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">' +
                        '<p><label>Icon (HTML):</label><br>' +
                        '<input type="text" name="featured_services[' + index + '][icon]" class="widefat"></p>' +
                        '<p><label>Title:</label><br>' +
                        '<input type="text" name="featured_services[' + index + '][title]" class="widefat"></p>' +
                        '<p><label>Description:</label><br>' +
                        '<textarea name="featured_services[' + index + '][description]" rows="3" class="widefat"></textarea></p>' +
                        '<p><label>Link:</label><br>' +
                        '<input type="text" name="featured_services[' + index + '][link]" class="widefat"></p>' +
                        '<button type="button" class="button remove-service">Remove Service</button>' +
                        '</div>';
                    $(this).before(newService);
                });

                // Remove Service
                $(document).on('click', '.remove-service', function() {
                    $(this).parent('.service-item').remove();
                });
            });
        </script>
        <?php
    }
}
add_action('admin_footer', 'wades_home_meta_scripts');

// Add CSS for the meta boxes
function wades_home_meta_styles() {
    ?>
    <style>
        .home-meta-box .video-upload-container {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .home-meta-box .video-preview {
            margin-top: 10px;
        }
        .home-meta-box .cta-section {
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .home-meta-box .cta-section h4 {
            margin-top: 0;
        }
    </style>
    <?php
}
add_action('admin_head', 'wades_home_meta_styles');

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