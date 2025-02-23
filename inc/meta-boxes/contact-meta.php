<?php
/**
 * Custom Meta Boxes for Contact Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add meta boxes for Contact template
 */
function wades_add_contact_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    
    // Only add these meta boxes for the contact template
    if ($template !== 'templates/contact.php') {
        return;
    }

    add_meta_box(
        'wades_contact_settings',
        'Page Settings',
        'wades_contact_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_contact_meta_boxes', 1);

/**
 * Contact Content Meta Box Callback
 */
function wades_contact_settings_callback($post) {
    wp_nonce_field('wades_contact_meta', 'wades_contact_meta_nonce');

    // Render the shared header fields
    wades_render_header_fields($post);
}

// Add content for the Content tab
add_action('wades_meta_box_content_tab', 'wades_contact_content_tab');
function wades_contact_content_tab($post) {
    // Get contact form settings
    $form_settings = array(
        'form_title' => get_post_meta($post->ID, '_contact_form_title', true) ?: 'Get in Touch',
        'form_description' => get_post_meta($post->ID, '_contact_form_description', true) ?: 'Have a question about our boats or services? We\'re here to help! Fill out the form below and our team will get back to you promptly.',
        'success_message' => get_post_meta($post->ID, '_contact_success_message', true) ?: 'Thank you for reaching out! Our team will get back to you within 24 hours.',
        'recipient_email' => get_post_meta($post->ID, '_contact_recipient_email', true) ?: get_option('admin_email')
    );

    // Get location information with defaults
    $location = array(
        'address' => get_post_meta($post->ID, '_contact_address', true) ?: "5185 Browns Bridge Rd\nCumming, GA 30041",
        'phone' => get_post_meta($post->ID, '_contact_phone', true) ?: '(770) 881-7808',
        'email' => get_post_meta($post->ID, '_contact_email', true) ?: 'info@impactmarinegroup.com',
        'hours' => get_post_meta($post->ID, '_contact_hours', true) ?: "Monday - Friday: 8:00 AM - 5:00 PM\nSaturday: By Appointment\nSunday: Closed",
        'map_embed' => get_post_meta($post->ID, '_contact_map_embed', true) ?: '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.7168559858897!2d-84.09678492432373!3d34.22759127220751!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f59fb0c3ff6e7d%3A0x3b1e388da1e435c4!2s5185%20Browns%20Bridge%20Rd%2C%20Cumming%2C%20GA%2030041!5e0!3m2!1sen!2sus!4v1709254559083!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'
    );

    // Get social media links with defaults
    $default_social = array(
        array(
            'platform' => 'facebook',
            'url' => 'https://www.facebook.com/ImpactMarineGroup'
        ),
        array(
            'platform' => 'instagram',
            'url' => 'https://www.instagram.com/impactmarinegroup'
        )
    );
    $social_media = get_post_meta($post->ID, '_contact_social_media', true) ?: $default_social;
    ?>
    <div class="wades-meta-section">
        <h3>Contact Form Settings</h3>
        <div class="wades-meta-field">
            <label for="contact_form_title">Form Title:</label>
            <input type="text" id="contact_form_title" name="contact_form_title" 
                   value="<?php echo esc_attr($form_settings['form_title']); ?>" class="widefat">
        </div>
        <div class="wades-meta-field">
            <label for="contact_form_description">Form Description:</label>
            <textarea id="contact_form_description" name="contact_form_description" 
                      rows="3" class="widefat"><?php echo esc_textarea($form_settings['form_description']); ?></textarea>
        </div>
        <div class="wades-meta-field">
            <label for="contact_success_message">Success Message:</label>
            <textarea id="contact_success_message" name="contact_success_message" 
                      rows="2" class="widefat"><?php echo esc_textarea($form_settings['success_message']); ?></textarea>
        </div>
        <div class="wades-meta-field">
            <label for="contact_recipient_email">Recipient Email:</label>
            <input type="email" id="contact_recipient_email" name="contact_recipient_email" 
                   value="<?php echo esc_attr($form_settings['recipient_email']); ?>" class="widefat">
            <p class="description">Email address where contact form submissions will be sent.</p>
        </div>
    </div>

    <div class="wades-meta-section">
        <h3>Location Information</h3>
        <div class="wades-meta-field">
            <label for="contact_address">Address:</label>
            <textarea id="contact_address" name="contact_address" 
                      rows="3" class="widefat"><?php echo esc_textarea($location['address']); ?></textarea>
        </div>
        <div class="wades-meta-field">
            <label for="contact_phone">Phone:</label>
            <input type="text" id="contact_phone" name="contact_phone" 
                   value="<?php echo esc_attr($location['phone']); ?>" class="widefat">
        </div>
        <div class="wades-meta-field">
            <label for="contact_email">Email:</label>
            <input type="email" id="contact_email" name="contact_email" 
                   value="<?php echo esc_attr($location['email']); ?>" class="widefat">
        </div>
        <div class="wades-meta-field">
            <label for="contact_hours">Business Hours:</label>
            <textarea id="contact_hours" name="contact_hours" 
                      rows="3" class="widefat"><?php echo esc_textarea($location['hours']); ?></textarea>
            <p class="description">Enter each line of hours (e.g., "Monday - Friday: 9am - 5pm")</p>
        </div>
        <div class="wades-meta-field">
            <label for="contact_map_embed">Google Maps Embed Code:</label>
            <textarea id="contact_map_embed" name="contact_map_embed" 
                      rows="4" class="widefat"><?php echo esc_textarea($location['map_embed']); ?></textarea>
            <p class="description">Paste the Google Maps embed code here.</p>
        </div>
    </div>

    <div class="wades-meta-section">
        <h3>Social Media Links</h3>
        <div class="social-media-list">
            <?php
            if (empty($social_media)) {
                $social_media = array(array('platform' => '', 'url' => ''));
            }
            foreach ($social_media as $index => $social) :
            ?>
                <div class="social-media-item" style="margin-bottom: 10px;">
                    <div class="wades-meta-field" style="display: flex; gap: 10px;">
                        <select name="social_media[<?php echo $index; ?>][platform]" style="width: 150px;">
                            <option value="">Select Platform</option>
                            <option value="facebook" <?php selected($social['platform'], 'facebook'); ?>>Facebook</option>
                            <option value="twitter" <?php selected($social['platform'], 'twitter'); ?>>Twitter</option>
                            <option value="instagram" <?php selected($social['platform'], 'instagram'); ?>>Instagram</option>
                            <option value="linkedin" <?php selected($social['platform'], 'linkedin'); ?>>LinkedIn</option>
                            <option value="youtube" <?php selected($social['platform'], 'youtube'); ?>>YouTube</option>
                        </select>
                        <input type="url" name="social_media[<?php echo $index; ?>][url]" 
                               value="<?php echo esc_url($social['url']); ?>" 
                               placeholder="https://" class="widefat">
                        <button type="button" class="button remove-social-media">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-social-media">Add Social Media Link</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Add social media link
        $('.add-social-media').on('click', function() {
            var index = $('.social-media-item').length;
            var template = `
                <div class="social-media-item" style="margin-bottom: 10px;">
                    <div class="wades-meta-field" style="display: flex; gap: 10px;">
                        <select name="social_media[${index}][platform]" style="width: 150px;">
                            <option value="">Select Platform</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="instagram">Instagram</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                        </select>
                        <input type="url" name="social_media[${index}][url]" 
                               placeholder="https://" class="widefat">
                        <button type="button" class="button remove-social-media">Remove</button>
                    </div>
                </div>
            `;
            $('.social-media-list').append(template);
        });

        // Remove social media link
        $(document).on('click', '.remove-social-media', function() {
            $(this).closest('.social-media-item').remove();
            // Update indices
            $('.social-media-item').each(function(index) {
                $(this).find('select, input').each(function() {
                    var name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                });
            });
        });
    });
    </script>
    <?php
}

// Add content for the Settings tab
add_action('wades_meta_box_settings_tab', 'wades_contact_settings_tab');
function wades_contact_settings_tab($post) {
    // Get default subject options
    $default_subjects = "Sales Inquiry\nService Request\nParts Order\nFinancing Question\nGeneral Information";
    
    // Get default SEO settings
    $default_seo_title = "Contact Impact Marine Group | Boat Sales & Service in Cumming, GA";
    $default_seo_description = "Contact Impact Marine Group for boat sales, service, and parts in Cumming, GA. Visit our showroom or reach out online. Expert assistance for all your boating needs.";

    // Get layout settings with defaults
    $spacing_top = get_post_meta($post->ID, '_content_spacing_top', true) ?: '96';
    $spacing_bottom = get_post_meta($post->ID, '_content_spacing_bottom', true) ?: '96';
    $content_width = get_post_meta($post->ID, '_content_max_width', true) ?: '7xl';
    ?>
    <div class="wades-meta-section">
        <h3>Layout Settings</h3>
        <div class="wades-meta-field">
            <label for="content_spacing_top">Space After Header (px):</label>
            <input type="number" id="content_spacing_top" name="content_spacing_top" 
                   value="<?php echo esc_attr($spacing_top); ?>" class="small-text"
                   min="0" max="200" step="8">
            <p class="description">Amount of space between header and content (default: 96px)</p>
        </div>
        <div class="wades-meta-field">
            <label for="content_spacing_bottom">Space Before Footer (px):</label>
            <input type="number" id="content_spacing_bottom" name="content_spacing_bottom" 
                   value="<?php echo esc_attr($spacing_bottom); ?>" class="small-text"
                   min="0" max="200" step="8">
            <p class="description">Amount of space between content and footer (default: 96px)</p>
        </div>
        <div class="wades-meta-field">
            <label for="content_max_width">Content Width:</label>
            <select id="content_max_width" name="content_max_width" class="regular-text">
                <option value="5xl" <?php selected($content_width, '5xl'); ?>>Narrow (1024px)</option>
                <option value="6xl" <?php selected($content_width, '6xl'); ?>>Medium (1152px)</option>
                <option value="7xl" <?php selected($content_width, '7xl'); ?>>Wide (1280px)</option>
                <option value="8xl" <?php selected($content_width, '8xl'); ?>>Extra Wide (1440px)</option>
            </select>
            <p class="description">Maximum width of the content area</p>
        </div>
    </div>

    <div class="wades-meta-section">
        <h3>Form Settings</h3>
        <div class="wades-meta-field">
            <label>
                <input type="checkbox" name="contact_show_phone" 
                       <?php checked(get_post_meta($post->ID, '_contact_show_phone', true) ?: '1', '1'); ?>>
                Show phone field in contact form
            </label>
        </div>
        <div class="wades-meta-field">
            <label>
                <input type="checkbox" name="contact_phone_required" 
                       <?php checked(get_post_meta($post->ID, '_contact_phone_required', true) ?: '1', '1'); ?>>
                Make phone field required
            </label>
        </div>
        <div class="wades-meta-field">
            <label>
                <input type="checkbox" name="contact_show_subject" 
                       <?php checked(get_post_meta($post->ID, '_contact_show_subject', true) ?: '1', '1'); ?>>
                Show subject field in contact form
            </label>
        </div>
        <div class="wades-meta-field">
            <label for="contact_subject_options">Subject Options:</label>
            <textarea id="contact_subject_options" name="contact_subject_options" 
                      rows="4" class="widefat"><?php echo esc_textarea(get_post_meta($post->ID, '_contact_subject_options', true) ?: $default_subjects); ?></textarea>
            <p class="description">Enter each subject option on a new line. Leave empty for free-form subject field.</p>
        </div>
    </div>

    <div class="wades-meta-section">
        <h3>SEO Settings</h3>
        <div class="wades-meta-field">
            <label for="seo_title">SEO Title:</label>
            <input type="text" id="seo_title" name="seo_title" 
                   value="<?php echo esc_attr(get_post_meta($post->ID, '_seo_title', true) ?: $default_seo_title); ?>" 
                   class="widefat">
            <p class="description">Custom title for search engines. Leave empty to use the default page title.</p>
        </div>
        <div class="wades-meta-field">
            <label for="seo_description">SEO Description:</label>
            <textarea id="seo_description" name="seo_description" 
                      rows="3" class="widefat"><?php echo esc_textarea(get_post_meta($post->ID, '_seo_description', true) ?: $default_seo_description); ?></textarea>
            <p class="description">Custom description for search engines.</p>
        </div>
    </div>
    <?php
}

/**
 * Save Contact Meta Box Data
 */
function wades_save_contact_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['wades_contact_meta_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['wades_contact_meta_nonce'], 'wades_contact_meta')) {
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

    // Save form settings
    $form_fields = array(
        'contact_form_title' => 'sanitize_text_field',
        'contact_form_description' => 'wp_kses_post',
        'contact_success_message' => 'wp_kses_post',
        'contact_recipient_email' => 'sanitize_email'
    );

    foreach ($form_fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_callback($_POST[$field]));
        }
    }

    // Save location information
    $location_fields = array(
        'contact_address' => 'wp_kses_post',
        'contact_phone' => 'sanitize_text_field',
        'contact_email' => 'sanitize_email',
        'contact_hours' => 'wp_kses_post',
        'contact_map_embed' => 'wp_kses_post'
    );

    foreach ($location_fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_callback($_POST[$field]));
        }
    }

    // Save social media links
    if (isset($_POST['social_media']) && is_array($_POST['social_media'])) {
        $social_media = array();
        foreach ($_POST['social_media'] as $social) {
            if (!empty($social['platform']) && !empty($social['url'])) {
                $social_media[] = array(
                    'platform' => sanitize_text_field($social['platform']),
                    'url' => esc_url_raw($social['url'])
                );
            }
        }
        update_post_meta($post_id, '_contact_social_media', $social_media);
    }

    // Save form settings
    $checkbox_fields = array(
        'contact_show_phone',
        'contact_phone_required',
        'contact_show_subject'
    );

    foreach ($checkbox_fields as $field) {
        update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? '1' : '');
    }

    if (isset($_POST['contact_subject_options'])) {
        update_post_meta($post_id, '_contact_subject_options', sanitize_textarea_field($_POST['contact_subject_options']));
    }

    // Save SEO settings
    if (isset($_POST['seo_title'])) {
        update_post_meta($post_id, '_seo_title', sanitize_text_field($_POST['seo_title']));
    }
    if (isset($_POST['seo_description'])) {
        update_post_meta($post_id, '_seo_description', sanitize_textarea_field($_POST['seo_description']));
    }

    // Save layout settings
    $layout_fields = array(
        'content_spacing_top' => 'absint',
        'content_spacing_bottom' => 'absint',
        'content_max_width' => 'sanitize_text_field'
    );

    foreach ($layout_fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_callback($_POST[$field]));
        }
    }
}
add_action('save_post', 'wades_save_contact_meta'); 