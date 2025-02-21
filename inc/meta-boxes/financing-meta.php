<?php
/**
 * Meta Boxes for Financing Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Financing template
 */
function wades_financing_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the financing template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'templates/financing.php' || basename($template) === 'financing.php') {
            add_meta_box(
                'financing_content',
                'Financing Page Content',
                'wades_financing_content_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_financing_meta_boxes');

/**
 * Financing Content Meta Box Callback
 */
function wades_financing_content_callback($post) {
    wp_nonce_field('wades_financing_meta', 'wades_financing_meta_nonce');

    $meta = array(
        // Hero Section
        'hero_title' => get_post_meta($post->ID, '_hero_title', true) ?: 'Financing Options',
        'hero_description' => get_post_meta($post->ID, '_hero_description', true) ?: 'Flexible financing solutions for your dream boat and expert service work',
        'hero_background' => get_post_meta($post->ID, '_hero_background', true),
        
        // Financing Options
        'financing_options' => get_post_meta($post->ID, '_financing_options', true) ?: array(
            array(
                'icon' => 'anchor',
                'title' => 'Boat Financing',
                'description' => 'Find the perfect loan for your new or used boat',
                'features' => array(
                    'Competitive interest rates',
                    'Flexible terms up to 20 years',
                    'Financing available for boats up to $5 million',
                    'Quick and easy application process'
                )
            ),
            array(
                'icon' => 'wrench',
                'title' => 'Service Financing',
                'description' => 'Affordable options for repairs and maintenance',
                'features' => array(
                    '0% interest for 12 months on services over $2,000',
                    'Low monthly payments',
                    'Cover unexpected repairs or planned upgrades',
                    'Quick approval process'
                )
            )
        ),
        
        // Calculator Settings
        'calculator_settings' => get_post_meta($post->ID, '_calculator_settings', true) ?: array(
            'default_amount' => '50000',
            'default_rate' => '5',
            'default_term' => '60',
            'min_amount' => '5000',
            'max_amount' => '5000000',
            'min_rate' => '3',
            'max_rate' => '15',
            'min_term' => '12',
            'max_term' => '240'
        ),
        
        // FAQ Section
        'faqs' => get_post_meta($post->ID, '_faqs', true) ?: array(
            array(
                'question' => 'What credit score do I need to qualify?',
                'answer' => 'While we consider various factors, a credit score of 640 or higher typically results in the best rates and terms. However, we offer options for a wide range of credit profiles.'
            ),
            array(
                'question' => 'How long does the approval process take?',
                'answer' => 'Our streamlined process often provides a decision within 24-48 hours of receiving a completed application.'
            ),
            array(
                'question' => 'Can I finance both new and used boats?',
                'answer' => 'Yes, we offer financing options for both new and used boats, as well as refinancing for your current boat.'
            ),
            array(
                'question' => 'Is there a minimum or maximum loan amount?',
                'answer' => 'We offer financing from $5,000 up to $5 million, accommodating a wide range of boats and budgets.'
            )
        ),
        
        // CTA Section
        'cta_settings' => get_post_meta($post->ID, '_cta_settings', true) ?: array(
            'title' => 'Ready to Get Started?',
            'description' => 'Our financing experts are here to help you navigate your options and find the best solution for your needs.',
            'button_text' => 'Contact Us Today',
            'button_link' => '/contact',
            'phone_number' => '(770) 881-7808'
        ),
        
        // Layout Options
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'hero' => '1',
            'financing_options' => '1',
            'calculator' => '1',
            'faqs' => '1',
            'cta' => '1'
        ),
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'hero,financing_options,calculator,faqs,cta'
    );
    ?>
    <div class="financing-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="hero">Hero</button>
            <button type="button" class="tab-button" data-tab="options">Financing Options</button>
            <button type="button" class="tab-button" data-tab="calculator">Calculator</button>
            <button type="button" class="tab-button" data-tab="faqs">FAQs</button>
            <button type="button" class="tab-button" data-tab="cta">CTA</button>
            <button type="button" class="tab-button" data-tab="layout">Layout</button>
        </div>

        <!-- Hero Tab -->
        <div class="tab-content active" data-tab="hero">
            <div class="meta-box-section">
                <h3>Hero Section</h3>
                <p>
                    <label for="hero_title">Page Title:</label><br>
                    <input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($meta['hero_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_description">Description:</label><br>
                    <textarea id="hero_description" name="hero_description" rows="3" class="widefat"><?php echo esc_textarea($meta['hero_description']); ?></textarea>
                </p>
                <p>
                    <label>Background Image:</label><br>
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

        <!-- Financing Options Tab -->
        <div class="tab-content" data-tab="options">
            <div class="meta-box-section">
                <h3>Financing Options</h3>
                <div class="financing-options-list">
                    <?php foreach ($meta['financing_options'] as $index => $option) : ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Option <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-option">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Icon (Lucide icon name):</label><br>
                                    <input type="text" name="financing_options[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($option['icon']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Title:</label><br>
                                    <input type="text" name="financing_options[<?php echo $index; ?>][title]" value="<?php echo esc_attr($option['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label><br>
                                    <textarea name="financing_options[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($option['description']); ?></textarea>
                                </p>
                                <div class="features-list">
                                    <label>Features:</label>
                                    <?php foreach ($option['features'] as $feature_index => $feature) : ?>
                                        <p>
                                            <input type="text" name="financing_options[<?php echo $index; ?>][features][]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                                            <button type="button" class="button remove-feature">Remove</button>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="button add-feature">Add Feature</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-option">Add Financing Option</button>
            </div>
        </div>

        <!-- Calculator Tab -->
        <div class="tab-content" data-tab="calculator">
            <div class="meta-box-section">
                <h3>Loan Calculator Settings</h3>
                <div class="grid-2">
                    <p>
                        <label for="default_amount">Default Loan Amount ($):</label><br>
                        <input type="number" id="default_amount" name="calculator_settings[default_amount]" value="<?php echo esc_attr($meta['calculator_settings']['default_amount']); ?>" class="widefat">
                    </p>
                    <p>
                        <label for="default_rate">Default Interest Rate (%):</label><br>
                        <input type="number" id="default_rate" name="calculator_settings[default_rate]" value="<?php echo esc_attr($meta['calculator_settings']['default_rate']); ?>" step="0.1" class="widefat">
                    </p>
                </div>
                <p>
                    <label for="default_term">Default Loan Term (months):</label><br>
                    <input type="number" id="default_term" name="calculator_settings[default_term]" value="<?php echo esc_attr($meta['calculator_settings']['default_term']); ?>" class="widefat">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Calculator Limits</h3>
                <div class="grid-2">
                    <p>
                        <label for="min_amount">Minimum Amount ($):</label><br>
                        <input type="number" id="min_amount" name="calculator_settings[min_amount]" value="<?php echo esc_attr($meta['calculator_settings']['min_amount']); ?>" class="widefat">
                    </p>
                    <p>
                        <label for="max_amount">Maximum Amount ($):</label><br>
                        <input type="number" id="max_amount" name="calculator_settings[max_amount]" value="<?php echo esc_attr($meta['calculator_settings']['max_amount']); ?>" class="widefat">
                    </p>
                </div>
                <div class="grid-2">
                    <p>
                        <label for="min_rate">Minimum Rate (%):</label><br>
                        <input type="number" id="min_rate" name="calculator_settings[min_rate]" value="<?php echo esc_attr($meta['calculator_settings']['min_rate']); ?>" step="0.1" class="widefat">
                    </p>
                    <p>
                        <label for="max_rate">Maximum Rate (%):</label><br>
                        <input type="number" id="max_rate" name="calculator_settings[max_rate]" value="<?php echo esc_attr($meta['calculator_settings']['max_rate']); ?>" step="0.1" class="widefat">
                    </p>
                </div>
                <div class="grid-2">
                    <p>
                        <label for="min_term">Minimum Term (months):</label><br>
                        <input type="number" id="min_term" name="calculator_settings[min_term]" value="<?php echo esc_attr($meta['calculator_settings']['min_term']); ?>" class="widefat">
                    </p>
                    <p>
                        <label for="max_term">Maximum Term (months):</label><br>
                        <input type="number" id="max_term" name="calculator_settings[max_term]" value="<?php echo esc_attr($meta['calculator_settings']['max_term']); ?>" class="widefat">
                    </p>
                </div>
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

        <!-- CTA Tab -->
        <div class="tab-content" data-tab="cta">
            <div class="meta-box-section">
                <h3>Call to Action</h3>
                <p>
                    <label for="cta_title">Title:</label><br>
                    <input type="text" id="cta_title" name="cta_settings[title]" value="<?php echo esc_attr($meta['cta_settings']['title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="cta_description">Description:</label><br>
                    <textarea id="cta_description" name="cta_settings[description]" rows="3" class="widefat"><?php echo esc_textarea($meta['cta_settings']['description']); ?></textarea>
                </p>
                <p>
                    <label for="cta_button_text">Button Text:</label><br>
                    <input type="text" id="cta_button_text" name="cta_settings[button_text]" value="<?php echo esc_attr($meta['cta_settings']['button_text']); ?>" class="widefat">
                </p>
                <p>
                    <label for="cta_button_link">Button Link:</label><br>
                    <input type="text" id="cta_button_link" name="cta_settings[button_link]" value="<?php echo esc_attr($meta['cta_settings']['button_link']); ?>" class="widefat">
                </p>
                <p>
                    <label for="cta_phone_number">Phone Number:</label><br>
                    <input type="text" id="cta_phone_number" name="cta_settings[phone_number]" value="<?php echo esc_attr($meta['cta_settings']['phone_number']); ?>" class="widefat">
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
                        <input type="checkbox" name="sections_visibility[financing_options]" value="1" <?php checked($meta['sections_visibility']['financing_options'], '1'); ?>>
                        Show Financing Options
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[calculator]" value="1" <?php checked($meta['sections_visibility']['calculator'], '1'); ?>>
                        Show Loan Calculator
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[faqs]" value="1" <?php checked($meta['sections_visibility']['faqs'], '1'); ?>>
                        Show FAQs Section
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[cta]" value="1" <?php checked($meta['sections_visibility']['cta'], '1'); ?>>
                        Show CTA Section
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
                        'financing_options' => 'Financing Options',
                        'calculator' => 'Loan Calculator',
                        'faqs' => 'FAQs Section',
                        'cta' => 'Call to Action'
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
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
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

        // Add financing option
        $('.add-option').on('click', function() {
            var index = $('.financing-options-list .card').length;
            var template = `
                <div class="card">
                    <div class="card-header">
                        <h4>Option ${index + 1}</h4>
                        <button type="button" class="button remove-option">Remove</button>
                    </div>
                    <div class="card-body">
                        <p>
                            <label>Icon (Lucide icon name):</label><br>
                            <input type="text" name="financing_options[${index}][icon]" class="widefat">
                        </p>
                        <p>
                            <label>Title:</label><br>
                            <input type="text" name="financing_options[${index}][title]" class="widefat">
                        </p>
                        <p>
                            <label>Description:</label><br>
                            <textarea name="financing_options[${index}][description]" rows="2" class="widefat"></textarea>
                        </p>
                        <div class="features-list">
                            <label>Features:</label>
                        </div>
                        <button type="button" class="button add-feature">Add Feature</button>
                    </div>
                </div>
            `;
            $('.financing-options-list').append(template);
        });

        // Remove financing option
        $(document).on('click', '.remove-option', function() {
            $(this).closest('.card').remove();
        });

        // Add feature
        $(document).on('click', '.add-feature', function() {
            var $featuresList = $(this).siblings('.features-list');
            var template = `
                <p>
                    <input type="text" name="financing_options[${$(this).closest('.card').index()}][features][]" class="widefat">
                    <button type="button" class="button remove-feature">Remove</button>
                </p>
            `;
            $featuresList.append(template);
        });

        // Remove feature
        $(document).on('click', '.remove-feature', function() {
            $(this).closest('p').remove();
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
                        <p>
                            <label>Question:</label><br>
                            <input type="text" name="faqs[${index}][question]" class="widefat">
                        </p>
                        <p>
                            <label>Answer:</label><br>
                            <textarea name="faqs[${index}][answer]" rows="3" class="widefat"></textarea>
                        </p>
                    </div>
                </div>
            `;
            $('.faqs-list').append(template);
        });

        // Remove FAQ
        $(document).on('click', '.remove-faq', function() {
            $(this).closest('.card').remove();
        });
    });
    </script>
    <?php
}

/**
 * Save Financing Meta Box Data
 */
function wades_save_financing_meta($post_id) {
    if (!isset($_POST['wades_financing_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_financing_meta_nonce'], 'wades_financing_meta')) {
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
        'hero_title',
        'hero_description'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save financing options
    if (isset($_POST['financing_options']) && is_array($_POST['financing_options'])) {
        $options = array();
        foreach ($_POST['financing_options'] as $option) {
            if (!empty($option['title'])) {
                $options[] = array(
                    'icon' => sanitize_text_field($option['icon']),
                    'title' => sanitize_text_field($option['title']),
                    'description' => wp_kses_post($option['description']),
                    'features' => isset($option['features']) ? array_map('sanitize_text_field', array_filter($option['features'])) : array()
                );
            }
        }
        update_post_meta($post_id, '_financing_options', $options);
    }

    // Save calculator settings
    if (isset($_POST['calculator_settings'])) {
        $calculator_settings = array();
        foreach ($_POST['calculator_settings'] as $key => $value) {
            $calculator_settings[$key] = absint($value);
        }
        update_post_meta($post_id, '_calculator_settings', $calculator_settings);
    }

    // Save FAQs
    if (isset($_POST['faqs']) && is_array($_POST['faqs'])) {
        $faqs = array();
        foreach ($_POST['faqs'] as $faq) {
            if (!empty($faq['question'])) {
                $faqs[] = array(
                    'question' => sanitize_text_field($faq['question']),
                    'answer' => wp_kses_post($faq['answer'])
                );
            }
        }
        update_post_meta($post_id, '_faqs', $faqs);
    }

    // Save CTA settings
    if (isset($_POST['cta_settings'])) {
        $cta_settings = array(
            'title' => sanitize_text_field($_POST['cta_settings']['title']),
            'description' => wp_kses_post($_POST['cta_settings']['description']),
            'button_text' => sanitize_text_field($_POST['cta_settings']['button_text']),
            'button_link' => esc_url_raw($_POST['cta_settings']['button_link']),
            'phone_number' => sanitize_text_field($_POST['cta_settings']['phone_number'])
        );
        update_post_meta($post_id, '_cta_settings', $cta_settings);
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
add_action('save_post', 'wades_save_financing_meta'); 