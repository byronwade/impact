<?php
/**
 * Meta Boxes for Contact Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Contact template
 */
function wades_contact_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the contact template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'templates/contact.php' || basename($template) === 'contact.php') {
            add_meta_box(
                'contact_content',
                'Contact Page Content',
                'wades_contact_content_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_contact_meta_boxes');

/**
 * Contact Content Meta Box Callback
 */
function wades_contact_content_callback($post) {
    wp_nonce_field('wades_contact_meta', 'wades_contact_meta_nonce');

    $meta = array(
        // Hero Section
        'contact_title' => get_post_meta($post->ID, '_contact_title', true) ?: 'Get in Touch',
        'contact_subtitle' => get_post_meta($post->ID, '_contact_subtitle', true) ?: 'We\'re here to help with all your boating needs',
        'hero_background' => get_post_meta($post->ID, '_hero_background', true),
        
        // Contact Cards
        'contact_cards' => get_post_meta($post->ID, '_contact_cards', true) ?: array(
            array(
                'icon' => 'message-circle',
                'title' => 'Chat to sales',
                'description' => 'Speak to our friendly team.',
                'contact' => 'sales@impactmarinegroup.com',
                'type' => 'email'
            ),
            array(
                'icon' => 'wrench',
                'title' => 'Service support',
                'description' => 'We\'re here to help.',
                'contact' => 'service@impactmarinegroup.com',
                'type' => 'email'
            ),
            array(
                'icon' => 'map-pin',
                'title' => 'Visit us',
                'description' => '5185 Browns Bridge Rd',
                'link_text' => 'View on Google Maps',
                'href' => 'https://maps.google.com/?q=5185+Browns+Bridge+Rd'
            ),
            array(
                'icon' => 'phone',
                'title' => 'Call us',
                'description' => 'Mon-Fri from 8am to 5pm.',
                'contacts' => array(
                    array('label' => 'Sales', 'number' => '(770) 881-7808'),
                    array('label' => 'Service', 'number' => '(770) 881-7809')
                ),
                'type' => 'phone'
            )
        ),
        
        // Form Settings
        'form_settings' => get_post_meta($post->ID, '_form_settings', true) ?: array(
            'recipient_email' => get_option('admin_email'),
            'success_message' => 'Thank you for your message. We\'ll get back to you soon!',
            'error_message' => 'Sorry, there was a problem sending your message. Please try again.',
            'required_fields' => array('name', 'email', 'message')
        ),
        
        // FAQ Section
        'faqs' => get_post_meta($post->ID, '_faqs', true) ?: array(
            array(
                'icon' => 'ship',
                'question' => 'What brands of boats do you offer?',
                'answer' => 'We offer a wide range of boat brands, including Sea Fox, Bennington, and more. Visit our showroom or check our inventory online for the latest models.'
            ),
            array(
                'icon' => 'clock',
                'question' => 'What are your business hours?',
                'answer' => 'We are open Monday through Friday from 8am to 5pm, and Saturday from 9am to 3pm. We are closed on Sundays.'
            ),
            array(
                'icon' => 'wrench',
                'question' => 'Do you offer boat maintenance services?',
                'answer' => 'Yes, we provide comprehensive maintenance services including routine maintenance, repairs, and winterization.'
            ),
            array(
                'icon' => 'credit-card',
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit cards, cash, and offer various financing options through our trusted partners.'
            )
        ),
        
        // Layout Options
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'hero' => '1',
            'contact_cards' => '1',
            'form' => '1',
            'faqs' => '1'
        ),
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'hero,contact_cards,form,faqs'
    );
    ?>
    <div class="contact-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="hero">Hero</button>
            <button type="button" class="tab-button" data-tab="cards">Contact Cards</button>
            <button type="button" class="tab-button" data-tab="form">Form Settings</button>
            <button type="button" class="tab-button" data-tab="faqs">FAQs</button>
            <button type="button" class="tab-button" data-tab="layout">Layout</button>
        </div>

        <!-- Hero Tab -->
        <div class="tab-content active" data-tab="hero">
            <div class="meta-box-section">
                <h3>Hero Section</h3>
                <p>
                    <label for="contact_title">Page Title:</label><br>
                    <input type="text" id="contact_title" name="contact_title" value="<?php echo esc_attr($meta['contact_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="contact_subtitle">Subtitle:</label><br>
                    <input type="text" id="contact_subtitle" name="contact_subtitle" value="<?php echo esc_attr($meta['contact_subtitle']); ?>" class="widefat">
                </p>
                <p>
                    <label>Hero Background Image:</label><br>
                    <input type="hidden" name="hero_background" value="<?php echo esc_attr($meta['hero_background']); ?>" class="widefat">
                    <button type="button" class="button upload-image">Upload Image</button>
                    <div class="image-preview">
                        <?php if ($meta['hero_background']) : ?>
                            <?php echo wp_get_attachment_image($meta['hero_background'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>
        </div>

        <!-- Contact Cards Tab -->
        <div class="tab-content" data-tab="cards">
            <div class="meta-box-section">
                <h3>Contact Cards</h3>
                <div class="contact-cards-list">
                    <?php foreach ($meta['contact_cards'] as $index => $card) : ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Contact Card <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-card">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Icon (Lucide icon name):</label><br>
                                    <input type="text" name="contact_cards[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($card['icon']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Title:</label><br>
                                    <input type="text" name="contact_cards[<?php echo $index; ?>][title]" value="<?php echo esc_attr($card['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label><br>
                                    <input type="text" name="contact_cards[<?php echo $index; ?>][description]" value="<?php echo esc_attr($card['description']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Type:</label><br>
                                    <select name="contact_cards[<?php echo $index; ?>][type]" class="widefat card-type-select">
                                        <option value="email" <?php selected($card['type'], 'email'); ?>>Email</option>
                                        <option value="phone" <?php selected($card['type'], 'phone'); ?>>Phone</option>
                                        <option value="location" <?php selected($card['type'], 'location'); ?>>Location</option>
                                    </select>
                                </p>
                                
                                <div class="card-type-fields email-fields" <?php echo $card['type'] === 'email' ? '' : 'style="display:none;"'; ?>>
                                    <p>
                                        <label>Email Address:</label><br>
                                        <input type="email" name="contact_cards[<?php echo $index; ?>][contact]" value="<?php echo esc_attr($card['contact'] ?? ''); ?>" class="widefat">
                                    </p>
                                </div>

                                <div class="card-type-fields location-fields" <?php echo $card['type'] === 'location' ? '' : 'style="display:none;"'; ?>>
                                    <p>
                                        <label>Link Text:</label><br>
                                        <input type="text" name="contact_cards[<?php echo $index; ?>][link_text]" value="<?php echo esc_attr($card['link_text'] ?? ''); ?>" class="widefat">
                                    </p>
                                    <p>
                                        <label>Map URL:</label><br>
                                        <input type="url" name="contact_cards[<?php echo $index; ?>][href]" value="<?php echo esc_url($card['href'] ?? ''); ?>" class="widefat">
                                    </p>
                                </div>

                                <div class="card-type-fields phone-fields" <?php echo $card['type'] === 'phone' ? '' : 'style="display:none;"'; ?>>
                                    <div class="phone-numbers">
                                        <?php if (isset($card['contacts']) && is_array($card['contacts'])) : ?>
                                            <?php foreach ($card['contacts'] as $phone_index => $phone) : ?>
                                                <div class="phone-number">
                                                    <p>
                                                        <label>Label:</label><br>
                                                        <input type="text" name="contact_cards[<?php echo $index; ?>][contacts][<?php echo $phone_index; ?>][label]" value="<?php echo esc_attr($phone['label']); ?>" class="widefat">
                                                    </p>
                                                    <p>
                                                        <label>Number:</label><br>
                                                        <input type="text" name="contact_cards[<?php echo $index; ?>][contacts][<?php echo $phone_index; ?>][number]" value="<?php echo esc_attr($phone['number']); ?>" class="widefat">
                                                    </p>
                                                    <button type="button" class="button remove-phone">Remove</button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button add-phone">Add Phone Number</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-card">Add Contact Card</button>
            </div>
        </div>

        <!-- Form Settings Tab -->
        <div class="tab-content" data-tab="form">
            <div class="meta-box-section">
                <h3>Form Configuration</h3>
                <p>
                    <label for="recipient_email">Recipient Email:</label><br>
                    <input type="email" id="recipient_email" name="form_settings[recipient_email]" value="<?php echo esc_attr($meta['form_settings']['recipient_email']); ?>" class="widefat">
                </p>
                <p>
                    <label for="success_message">Success Message:</label><br>
                    <input type="text" id="success_message" name="form_settings[success_message]" value="<?php echo esc_attr($meta['form_settings']['success_message']); ?>" class="widefat">
                </p>
                <p>
                    <label for="error_message">Error Message:</label><br>
                    <input type="text" id="error_message" name="form_settings[error_message]" value="<?php echo esc_attr($meta['form_settings']['error_message']); ?>" class="widefat">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Required Fields</h3>
                <p>
                    <label>
                        <input type="checkbox" name="form_settings[required_fields][]" value="name" <?php checked(in_array('name', $meta['form_settings']['required_fields'])); ?>>
                        Name
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="form_settings[required_fields][]" value="email" <?php checked(in_array('email', $meta['form_settings']['required_fields'])); ?>>
                        Email
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="form_settings[required_fields][]" value="subject" <?php checked(in_array('subject', $meta['form_settings']['required_fields'])); ?>>
                        Subject
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="form_settings[required_fields][]" value="message" <?php checked(in_array('message', $meta['form_settings']['required_fields'])); ?>>
                        Message
                    </label>
                </p>
            </div>
        </div>

        <!-- FAQs Tab -->
        <div class="tab-content" data-tab="faqs">
            <div class="meta-box-section">
                <h3>Frequently Asked Questions</h3>
                <div class="faqs-list">
                    <?php foreach ($meta['faqs'] as $index => $faq) : ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>FAQ <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-faq">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Icon (Lucide icon name):</label><br>
                                    <input type="text" name="faqs[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($faq['icon']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Question:</label><br>
                                    <input type="text" name="faqs[<?php echo $index; ?>][question]" value="<?php echo esc_attr($faq['question']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Answer:</label><br>
                                    <textarea name="faqs[<?php echo $index; ?>][answer]" rows="3" class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-faq">Add FAQ</button>
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
                        <input type="checkbox" name="sections_visibility[contact_cards]" value="1" <?php checked($meta['sections_visibility']['contact_cards'], '1'); ?>>
                        Show Contact Cards
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[form]" value="1" <?php checked($meta['sections_visibility']['form'], '1'); ?>>
                        Show Contact Form
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[faqs]" value="1" <?php checked($meta['sections_visibility']['faqs'], '1'); ?>>
                        Show FAQs Section
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
                        'contact_cards' => 'Contact Cards',
                        'form' => 'Contact Form',
                        'faqs' => 'FAQs Section'
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

    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            $(this).addClass('active');
            $('.tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        });

        // Section order sorting
        $('#section-order').sortable({
            handle: '.dashicons-menu',
            update: function() {
                var order = [];
                $('#section-order li').each(function() {
                    order.push($(this).data('section'));
                });
                $('#section-order-input').val(order.join(','));
            }
        });

        // Contact card type toggle
        $(document).on('change', '.card-type-select', function() {
            var $card = $(this).closest('.card-body');
            $card.find('.card-type-fields').hide();
            $card.find('.' + $(this).val() + '-fields').show();
        });

        // Add contact card
        $('.add-card').on('click', function() {
            var index = $('.contact-cards-list .card').length;
            var template = `
                <div class="card">
                    <div class="card-header">
                        <h4>Contact Card ${index + 1}</h4>
                        <button type="button" class="button remove-card">Remove</button>
                    </div>
                    <div class="card-body">
                        <!-- Card fields template -->
                    </div>
                </div>
            `;
            $('.contact-cards-list').append(template);
        });

        // Remove contact card
        $(document).on('click', '.remove-card', function() {
            $(this).closest('.card').remove();
        });

        // Add FAQ
        $('.add-faq').on('click', function() {
            var index = $('.faqs-list .card').length;
            var template = `
                <div class="card">
                    <div class="card-header">
                        <h4>FAQ ${index + 1}</h4>
                        <button type="button" class="button remove-faq">Remove</button>
                    </div>
                    <div class="card-body">
                        <!-- FAQ fields template -->
                    </div>
                </div>
            `;
            $('.faqs-list').append(template);
        });

        // Remove FAQ
        $(document).on('click', '.remove-faq', function() {
            $(this).closest('.card').remove();
        });

        // Add phone number
        $(document).on('click', '.add-phone', function() {
            var $phoneNumbers = $(this).siblings('.phone-numbers');
            var cardIndex = $(this).closest('.card').index();
            var phoneIndex = $phoneNumbers.children().length;
            
            var template = `
                <div class="phone-number">
                    <p>
                        <label>Label:</label><br>
                        <input type="text" name="contact_cards[${cardIndex}][contacts][${phoneIndex}][label]" class="widefat">
                    </p>
                    <p>
                        <label>Number:</label><br>
                        <input type="text" name="contact_cards[${cardIndex}][contacts][${phoneIndex}][number]" class="widefat">
                    </p>
                    <button type="button" class="button remove-phone">Remove</button>
                </div>
            `;
            $phoneNumbers.append(template);
        });

        // Remove phone number
        $(document).on('click', '.remove-phone', function() {
            $(this).closest('.phone-number').remove();
        });
    });
    </script>
    <?php
}

/**
 * Save Contact Meta Box Data
 */
function wades_save_contact_meta($post_id) {
    if (!isset($_POST['wades_contact_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_contact_meta_nonce'], 'wades_contact_meta')) {
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
        'contact_title',
        'contact_subtitle'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save contact cards
    if (isset($_POST['contact_cards']) && is_array($_POST['contact_cards'])) {
        $cards = array();
        foreach ($_POST['contact_cards'] as $card) {
            if (!empty($card['title'])) {
                $sanitized_card = array(
                    'icon' => sanitize_text_field($card['icon']),
                    'title' => sanitize_text_field($card['title']),
                    'description' => sanitize_text_field($card['description']),
                    'type' => sanitize_text_field($card['type'])
                );

                // Add type-specific fields
                switch ($card['type']) {
                    case 'email':
                        $sanitized_card['contact'] = sanitize_email($card['contact']);
                        break;
                    case 'location':
                        $sanitized_card['link_text'] = sanitize_text_field($card['link_text']);
                        $sanitized_card['href'] = esc_url_raw($card['href']);
                        break;
                    case 'phone':
                        if (isset($card['contacts']) && is_array($card['contacts'])) {
                            $sanitized_card['contacts'] = array();
                            foreach ($card['contacts'] as $contact) {
                                $sanitized_card['contacts'][] = array(
                                    'label' => sanitize_text_field($contact['label']),
                                    'number' => sanitize_text_field($contact['number'])
                                );
                            }
                        }
                        break;
                }

                $cards[] = $sanitized_card;
            }
        }
        update_post_meta($post_id, '_contact_cards', $cards);
    }

    // Save form settings
    if (isset($_POST['form_settings'])) {
        $form_settings = array(
            'recipient_email' => sanitize_email($_POST['form_settings']['recipient_email']),
            'success_message' => sanitize_text_field($_POST['form_settings']['success_message']),
            'error_message' => sanitize_text_field($_POST['form_settings']['error_message']),
            'required_fields' => isset($_POST['form_settings']['required_fields']) ? array_map('sanitize_text_field', $_POST['form_settings']['required_fields']) : array()
        );
        update_post_meta($post_id, '_form_settings', $form_settings);
    }

    // Save FAQs
    if (isset($_POST['faqs']) && is_array($_POST['faqs'])) {
        $faqs = array();
        foreach ($_POST['faqs'] as $faq) {
            if (!empty($faq['question'])) {
                $faqs[] = array(
                    'icon' => sanitize_text_field($faq['icon']),
                    'question' => sanitize_text_field($faq['question']),
                    'answer' => wp_kses_post($faq['answer'])
                );
            }
        }
        update_post_meta($post_id, '_faqs', $faqs);
    }

    // Save section visibility
    if (isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility'])) {
        $visibility = array();
        foreach ($_POST['sections_visibility'] as $section => $value) {
            $visibility[$section] = '1';
        }
        update_post_meta($post_id, '_sections_visibility', $visibility);
    }

    // Save section order
    if (isset($_POST['section_order'])) {
        update_post_meta($post_id, '_section_order', sanitize_text_field($_POST['section_order']));
    }

    // Save hero background
    if (isset($_POST['hero_background'])) {
        update_post_meta($post_id, '_hero_background', absint($_POST['hero_background']));
    }
}
add_action('save_post', 'wades_save_contact_meta'); 