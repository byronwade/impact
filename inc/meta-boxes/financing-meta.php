<?php
/**
 * Custom Meta Boxes for Financing Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register the financing template
 */
function wades_register_financing_template($templates) {
    $templates['templates/financing.php'] = 'Financing Template';
    return $templates;
}
add_filter('theme_page_templates', 'wades_register_financing_template', 1);

/**
 * Add meta boxes for Financing template
 */
function wades_add_financing_meta_boxes() {
    global $post;
    if (!$post) return;

    // Get current template
    $template = get_page_template_slug($post->ID);
    
    // Only add these meta boxes for the financing template
    if ($template !== 'templates/financing.php') {
        return;
    }

    // Remove any existing meta boxes that might conflict
    remove_meta_box('wades_page_header', 'page', 'normal');

    // Single meta box with tabs
    add_meta_box(
        'wades_financing_settings',
        'Financing Page Settings',
        'wades_financing_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_financing_meta_boxes', 20);

/**
 * Financing Settings Meta Box Callback
 */
function wades_financing_settings_callback($post) {
    wp_nonce_field('wades_financing_meta', 'wades_financing_meta_nonce');

    // Get current settings with defaults
    $meta = array(
        // Hero Section
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true),
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '70',
        'hero_title' => get_post_meta($post->ID, '_custom_header_title', true) ?: 'Financing Options',
        'hero_description' => get_post_meta($post->ID, '_custom_header_subtext', true) ?: 'Flexible financing solutions for your dream boat',
        'financing_intro' => get_post_meta($post->ID, '_financing_intro', true) ?: 'At Impact Marine Group, we understand that purchasing your dream boat is a significant investment. That\'s why we\'ve partnered with leading marine financing institutions to provide you with flexible, competitive financing options. Our dedicated team will work with you to find the best financing solution tailored to your needs, ensuring a smooth and hassle-free process from application to approval.',
        'financing_partners' => get_post_meta($post->ID, '_financing_partners', true) ?: array(
            array(
                'name' => 'Marine Lending',
                'logo' => '',
                'description' => 'Specializing in boat loans with competitive rates and flexible terms up to 20 years.',
                'link' => '#'
            ),
            array(
                'name' => 'Boat Finance Direct',
                'logo' => '',
                'description' => 'Quick approvals and customized financing solutions for new and used boats.',
                'link' => '#'
            ),
            array(
                'name' => 'Nautical Credit',
                'logo' => '',
                'description' => 'Expert marine financing with industry-leading rates and exceptional customer service.',
                'link' => '#'
            )
        ),
        'financing_steps' => get_post_meta($post->ID, '_financing_steps', true) ?: array(
            array(
                'title' => 'Complete Application',
                'description' => 'Fill out our simple online application form with your basic information and financing preferences.',
                'icon' => 'clipboard-list'
            ),
            array(
                'title' => 'Document Submission',
                'description' => 'Provide required documentation such as proof of income and identification.',
                'icon' => 'file-text'
            ),
            array(
                'title' => 'Credit Review',
                'description' => 'Our financing partners will review your application and credit history.',
                'icon' => 'search'
            ),
            array(
                'title' => 'Approval & Terms',
                'description' => 'Receive your approval with detailed terms and conditions for your boat loan.',
                'icon' => 'check-circle'
            ),
            array(
                'title' => 'Closing',
                'description' => 'Sign the final paperwork and take delivery of your new boat.',
                'icon' => 'boat'
            )
        ),
        'required_documents' => get_post_meta($post->ID, '_required_documents', true) ?: array(
            array(
                'title' => 'Personal Identification',
                'description' => 'Valid government-issued photo ID and proof of residence.'
            ),
            array(
                'title' => 'Income Verification',
                'description' => 'Recent pay stubs, W-2s, or tax returns from the past two years.'
            ),
            array(
                'title' => 'Bank Statements',
                'description' => 'Last three months of personal bank statements.'
            ),
            array(
                'title' => 'Employment Information',
                'description' => 'Current employer details and employment history.'
            ),
            array(
                'title' => 'Boat Information',
                'description' => 'Details about the boat you wish to finance, including make, model, and year.'
            )
        ),
        'faq_items' => get_post_meta($post->ID, '_faq_items', true) ?: array(
            array(
                'question' => 'What credit score do I need to qualify for boat financing?',
                'answer' => 'While requirements vary by lender, a credit score of 680 or higher typically results in the best rates and terms. However, we work with multiple lenders who can accommodate various credit profiles.'
            ),
            array(
                'question' => 'How long can I finance my boat?',
                'answer' => 'Boat loans typically range from 10-20 years, depending on the loan amount and type of boat. Longer terms mean lower monthly payments, but may result in higher total interest costs.'
            ),
            array(
                'question' => 'What is the minimum down payment required?',
                'answer' => 'Down payment requirements typically range from 10-20% of the boat\'s value, though this can vary based on the loan amount, your credit profile, and the lender\'s requirements.'
            ),
            array(
                'question' => 'Can I finance a used boat?',
                'answer' => 'Yes, we offer financing for both new and used boats. The terms and rates may vary based on the boat\'s age and condition.'
            ),
            array(
                'question' => 'How long does the approval process take?',
                'answer' => 'Most applications receive an initial response within 24-48 hours. The complete process, from application to closing, typically takes 5-7 business days.'
            ),
            array(
                'question' => 'Do you offer financing for service and accessories?',
                'answer' => 'Yes, you can include accessories, electronics, and even extended warranties in your boat loan. We also offer separate financing options for major service work.'
            )
        ),
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'intro' => '1',
            'partners' => '1',
            'steps' => '1',
            'documents' => '1',
            'faq' => '1'
        ),
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'intro,partners,steps,documents,faq'
    );

    // Save settings if form is submitted
    if (isset($_POST['wades_financing_meta_nonce']) && wp_verify_nonce($_POST['wades_financing_meta_nonce'], 'wades_financing_meta')) {
        // Update settings array
        $new_meta = array(
            'hero_title' => isset($_POST['hero_title']) ? sanitize_text_field($_POST['hero_title']) : $meta['hero_title'],
            'hero_description' => isset($_POST['hero_description']) ? wp_kses_post($_POST['hero_description']) : $meta['hero_description'],
            'hero_background_image' => isset($_POST['hero_background_image']) ? absint($_POST['hero_background_image']) : $meta['hero_background_image'],
            'hero_overlay_opacity' => isset($_POST['hero_overlay_opacity']) ? min(100, max(0, absint($_POST['hero_overlay_opacity']))) : $meta['hero_overlay_opacity'],
            'hero_height' => isset($_POST['hero_height']) ? absint($_POST['hero_height']) : $meta['hero_height'],
            'financing_intro' => isset($_POST['financing_intro']) ? wp_kses_post($_POST['financing_intro']) : $meta['financing_intro'],
            'financing_partners' => isset($_POST['financing_partners']) && is_array($_POST['financing_partners']) ? array_values($_POST['financing_partners']) : $meta['financing_partners'],
            'financing_steps' => isset($_POST['financing_steps']) && is_array($_POST['financing_steps']) ? array_values($_POST['financing_steps']) : $meta['financing_steps'],
            'required_documents' => isset($_POST['required_documents']) && is_array($_POST['required_documents']) ? array_values($_POST['required_documents']) : $meta['required_documents'],
            'faq_items' => isset($_POST['faq_items']) && is_array($_POST['faq_items']) ? array_values($_POST['faq_items']) : $meta['faq_items'],
            'sections_visibility' => isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility']) ? $_POST['sections_visibility'] : $meta['sections_visibility'],
            'section_order' => isset($_POST['section_order']) ? sanitize_text_field($_POST['section_order']) : $meta['section_order']
        );

        // Save meta fields
        update_post_meta($post->ID, '_custom_header_title', $new_meta['hero_title']);
        update_post_meta($post->ID, '_custom_header_subtext', $new_meta['hero_description']);
        update_post_meta($post->ID, '_hero_background_image', $new_meta['hero_background_image']);
        update_post_meta($post->ID, '_hero_overlay_opacity', $new_meta['hero_overlay_opacity']);
        update_post_meta($post->ID, '_hero_height', $new_meta['hero_height']);
        update_post_meta($post->ID, '_financing_intro', $new_meta['financing_intro']);
        update_post_meta($post->ID, '_financing_partners', $new_meta['financing_partners']);
        update_post_meta($post->ID, '_financing_steps', $new_meta['financing_steps']);
        update_post_meta($post->ID, '_required_documents', $new_meta['required_documents']);
        update_post_meta($post->ID, '_faq_items', $new_meta['faq_items']);
        update_post_meta($post->ID, '_sections_visibility', $new_meta['sections_visibility']);
        update_post_meta($post->ID, '_section_order', $new_meta['section_order']);

        $meta = $new_meta;
        add_settings_error('wades_financing_messages', 'wades_financing_message', 'Settings Saved', 'updated');
    }

    settings_errors('wades_financing_messages');
    ?>
    <div class="wades-meta-box-container">
        <!-- Tab Navigation -->
        <div class="wades-meta-box-tabs-nav">
            <button type="button" class="wades-tab-button active" data-tab="layout">Layout & Order</button>
            <button type="button" class="wades-tab-button" data-tab="header">Page Header</button>
            <button type="button" class="wades-tab-button" data-tab="intro">Introduction</button>
            <button type="button" class="wades-tab-button" data-tab="partners">Financing Partners</button>
            <button type="button" class="wades-tab-button" data-tab="steps">Application Steps</button>
            <button type="button" class="wades-tab-button" data-tab="documents">Required Documents</button>
            <button type="button" class="wades-tab-button" data-tab="faq">FAQ</button>
        </div>

        <!-- Layout & Order Tab -->
        <div class="wades-tab-content active" data-tab="layout">
            <div class="wades-meta-section">
                <h3>Section Order & Visibility</h3>
                <p class="description">Enable/disable sections and drag to reorder them.</p>
                <div class="wades-sortable-list">
                    <?php
                    $sections = explode(',', $meta['section_order']);
                    $section_labels = array(
                        'intro' => 'Introduction Section',
                        'partners' => 'Financing Partners Section',
                        'steps' => 'Application Steps Section',
                        'documents' => 'Required Documents Section',
                        'faq' => 'FAQ Section'
                    );
                    foreach ($sections as $section_id) :
                        if (isset($section_labels[$section_id])) :
                    ?>
                        <div class="wades-sortable-item">
                            <span class="dashicons dashicons-menu wades-sortable-handle"></span>
                            <label class="wades-checkbox-group">
                                <input type="checkbox" 
                                       name="sections_visibility[<?php echo esc_attr($section_id); ?>]" 
                                       value="1"
                                       <?php checked($meta['sections_visibility'][$section_id], '1'); ?>>
                                <?php echo esc_html($section_labels[$section_id]); ?>
                            </label>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>
        </div>

        <!-- Header Tab -->
        <div class="wades-tab-content" data-tab="header">
            <div class="wades-meta-section">
                <h3>Page Header Settings</h3>
                <div class="wades-meta-field">
                    <label for="hero_title">Header Title</label>
                    <input type="text" 
                           id="hero_title" 
                           name="hero_title" 
                           value="<?php echo esc_attr($meta['hero_title']); ?>" 
                           class="widefat">
                </div>

                <div class="wades-meta-field">
                    <label for="hero_description">Header Description</label>
                    <textarea id="hero_description" 
                              name="hero_description" 
                              rows="3" 
                              class="widefat"><?php echo esc_textarea($meta['hero_description']); ?></textarea>
                </div>

                <div class="wades-meta-field">
                    <label>Background Image</label>
                    <input type="hidden" name="hero_background_image" id="hero_background_image" 
                           value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                    <div class="wades-button-group">
                        <button type="button" class="button upload-image" id="upload_hero_image">Select Image</button>
                        <button type="button" class="button remove-image">Remove Image</button>
                    </div>
                    <div class="wades-image-preview">
                        <?php 
                        if ($meta['hero_background_image']) {
                            echo wp_get_attachment_image($meta['hero_background_image'], 'thumbnail');
                        }
                        ?>
                    </div>
                </div>

                <div class="wades-meta-field">
                    <label for="hero_overlay_opacity">Overlay Opacity (%)</label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>"
                           class="regular-text" min="0" max="100" step="5">
                </div>

                <div class="wades-meta-field">
                    <label for="hero_height">Header Height (vh)</label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>"
                           class="regular-text" min="30" max="100" step="5">
                </div>
            </div>
        </div>

        <!-- Partners Tab -->
        <div class="wades-tab-content" data-tab="partners">
            <div class="wades-meta-section">
                <h3>Financing Partners</h3>
                <div class="partners-list">
                    <?php foreach ($meta['financing_partners'] as $index => $partner) : ?>
                        <div class="wades-card">
                            <div class="wades-card-header">
                                <h4>Partner <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-partner">Remove</button>
                            </div>
                            <div class="wades-card-body">
                                <div class="wades-meta-field">
                                    <label>Partner Name:</label>
                                    <input type="text" name="financing_partners[<?php echo $index; ?>][name]" 
                                           value="<?php echo esc_attr($partner['name']); ?>" class="widefat">
                                </div>
                                <div class="wades-meta-field">
                                    <label>Partner Logo:</label>
                                    <input type="hidden" name="financing_partners[<?php echo $index; ?>][logo]" 
                                           value="<?php echo esc_attr($partner['logo']); ?>" class="partner-logo-input">
                                    <div class="wades-button-group">
                                        <button type="button" class="button upload-partner-logo">Select Logo</button>
                                        <button type="button" class="button remove-partner-logo">Remove Logo</button>
                                    </div>
                                    <div class="wades-image-preview">
                                        <?php if ($partner['logo']) : ?>
                                            <?php echo wp_get_attachment_image($partner['logo'], 'thumbnail'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="wades-meta-field">
                                    <label>Description:</label>
                                    <textarea name="financing_partners[<?php echo $index; ?>][description]" 
                                              rows="3" class="widefat"><?php echo esc_textarea($partner['description']); ?></textarea>
                                </div>
                                <div class="wades-meta-field">
                                    <label>Website Link:</label>
                                    <input type="text" 
                                           name="financing_partners[<?php echo $index; ?>][link]" 
                                           value="<?php echo esc_url($partner['link']); ?>" 
                                           class="widefat website-url"
                                           placeholder="https://">
                                    <span class="description">Enter full URL including http:// or https:// (leave empty if no website)</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-partner">Add Partner</button>
            </div>
        </div>

        <!-- Steps Tab -->
        <div class="wades-tab-content" data-tab="steps">
            <div class="wades-meta-section">
                <h3>Application Steps</h3>
                <div class="steps-list">
                    <?php foreach ($meta['financing_steps'] as $index => $step) : ?>
                        <div class="wades-card">
                            <div class="wades-card-header">
                                <h4>Step <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-step">Remove</button>
                            </div>
                            <div class="wades-card-body">
                                <div class="wades-meta-field">
                                    <label>Step Title:</label>
                                    <input type="text" name="financing_steps[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($step['title']); ?>" class="widefat">
                                </div>
                                <div class="wades-meta-field">
                                    <label>Description:</label>
                                    <textarea name="financing_steps[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($step['description']); ?></textarea>
                                </div>
                                <div class="wades-meta-field">
                                    <label>Icon (Lucide icon name):</label>
                                    <input type="text" name="financing_steps[<?php echo $index; ?>][icon]" 
                                           value="<?php echo esc_attr($step['icon']); ?>" class="widefat">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-step">Add Step</button>
            </div>
        </div>

        <!-- Documents Tab -->
        <div class="wades-tab-content" data-tab="documents">
            <div class="wades-meta-section">
                <h3>Required Documents</h3>
                <div class="documents-list">
                    <?php foreach ($meta['required_documents'] as $index => $document) : ?>
                        <div class="wades-card">
                            <div class="wades-card-header">
                                <h4>Document <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-document">Remove</button>
                            </div>
                            <div class="wades-card-body">
                                <div class="wades-meta-field">
                                    <label>Document Title:</label>
                                    <input type="text" name="required_documents[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($document['title']); ?>" class="widefat">
                                </div>
                                <div class="wades-meta-field">
                                    <label>Description:</label>
                                    <textarea name="required_documents[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($document['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-document">Add Document</button>
            </div>
        </div>

        <!-- FAQ Tab -->
        <div class="wades-tab-content" data-tab="faq">
            <div class="wades-meta-section">
                <h3>Frequently Asked Questions</h3>
                <div class="faq-list">
                    <?php foreach ($meta['faq_items'] as $index => $faq) : ?>
                        <div class="wades-card">
                            <div class="wades-card-header">
                                <h4>FAQ <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-faq">Remove</button>
                            </div>
                            <div class="wades-card-body">
                                <div class="wades-meta-field">
                                    <label>Question:</label>
                                    <input type="text" name="faq_items[<?php echo $index; ?>][question]" 
                                           value="<?php echo esc_attr($faq['question']); ?>" class="widefat">
                                </div>
                                <div class="wades-meta-field">
                                    <label>Answer:</label>
                                    <textarea name="faq_items[<?php echo $index; ?>][answer]" 
                                              rows="3" class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-faq">Add FAQ</button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.wades-tab-button').on('click', function() {
            const tab = $(this).data('tab');
            $('.wades-tab-button').removeClass('active');
            $('.wades-tab-content').removeClass('active');
            $(this).addClass('active');
            $(`.wades-tab-content[data-tab="${tab}"]`).addClass('active');
        });

        // Form validation before submit
        $('form#post').on('submit', function(e) {
            $('.website-url').each(function() {
                let val = $(this).val().trim();
                if (val && val !== '#') {
                    if (!val.match(/^https?:\/\//)) {
                        $(this).val('https://' + val);
                    }
                } else {
                    $(this).val('');
                }
            });
        });

        // URL field validation on input
        $(document).on('input', '.website-url', function() {
            let val = $(this).val().trim();
            if (val && val !== '#') {
                if (!val.match(/^https?:\/\//)) {
                    $(this).next('.description').css('color', '#d63638');
                } else {
                    $(this).next('.description').css('color', '');
                }
            } else {
                $(this).next('.description').css('color', '');
            }
        });

        // Generic function to handle image upload
        function handleImageUpload(button, inputSelector, previewSelector) {
            const frame = wp.media({
                title: 'Select or Upload Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                $(inputSelector).val(attachment.id);
                $(previewSelector).html(`<img src="${attachment.url}" alt="">`);
            });

            frame.open();
        }

        // Hero background image upload
        $('#upload_hero_image').on('click', function(e) {
            e.preventDefault();
            handleImageUpload(
                $(this),
                '#hero_background_image',
                '#wades-image-preview'
            );
        });

        // Partner logo upload
        $(document).on('click', '.upload-partner-logo', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.wades-card-body');
            handleImageUpload(
                $(this),
                $parent.find('input[type="hidden"]'),
                $parent.find('.wades-image-preview')
            );
        });

        // Image remove functionality
        $('.remove-image').on('click', function(e) {
            e.preventDefault();
            $('#hero_background_image').val('');
            $('#wades-image-preview').empty();
        });

        $(document).on('click', '.remove-partner-logo', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.wades-card-body');
            $parent.find('input[type="hidden"]').val('');
            $parent.find('.wades-image-preview').empty();
        });

        // Add partner functionality
        let partnerCount = $('.wades-card').length;
        $('.add-partner').on('click', function() {
            let template = $('#partner-template').html();
            template = template.replace(/\[0\]/g, `[${partnerCount}]`);
            $('.partners-list').append(template);
            partnerCount++;
        });

        // Remove partner functionality
        $(document).on('click', '.remove-partner', function() {
            $(this).closest('.wades-card').remove();
        });

        // Section ordering
        $('.wades-sortable-list').sortable({
            handle: '.wades-sortable-handle',
            update: function(event, ui) {
                var order = [];
                $('.wades-sortable-item').each(function() {
                    var sectionId = $(this).find('input[type="checkbox"]').attr('name').match(/\[(.*?)\]/)[1];
                    order.push(sectionId);
                });
                $('.section-order').val(order.join(','));
            }
        });
    });
    </script>

    <!-- Templates for dynamic items -->
    <script type="text/template" id="partner-template">
        <div class="wades-card">
            <div class="wades-card-header">
                <h4>New Partner</h4>
                <button type="button" class="button remove-partner">Remove</button>
            </div>
            <div class="wades-card-body">
                <div class="wades-meta-field">
                    <label>Partner Name:</label>
                    <input type="text" name="financing_partners[0][name]" class="widefat">
                </div>
                <div class="wades-meta-field">
                    <label>Partner Logo:</label>
                    <input type="hidden" name="financing_partners[0][logo]" class="partner-logo-input">
                    <div class="wades-button-group">
                        <button type="button" class="button upload-partner-logo">Select Logo</button>
                        <button type="button" class="button remove-partner-logo">Remove Logo</button>
                    </div>
                    <div class="wades-image-preview"></div>
                </div>
                <div class="wades-meta-field">
                    <label>Description:</label>
                    <textarea name="financing_partners[0][description]" rows="3" class="widefat"></textarea>
                </div>
                <div class="wades-meta-field">
                    <label>Website Link:</label>
                    <input type="text" 
                           name="financing_partners[0][link]" 
                           class="widefat website-url"
                           placeholder="https://">
                    <span class="description">Enter full URL including http:// or https:// (leave empty if no website)</span>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="step-template">
        <div class="wades-card">
            <div class="wades-card-header">
                <h4>New Step</h4>
                <button type="button" class="button remove-step">Remove</button>
            </div>
            <div class="wades-card-body">
                <div class="wades-meta-field">
                    <label>Step Title:</label>
                    <input type="text" name="financing_steps[0][title]" class="widefat">
                </div>
                <div class="wades-meta-field">
                    <label>Description:</label>
                    <textarea name="financing_steps[0][description]" rows="2" class="widefat"></textarea>
                </div>
                <div class="wades-meta-field">
                    <label>Icon (Lucide icon name):</label>
                    <input type="text" name="financing_steps[0][icon]" class="widefat">
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="document-template">
        <div class="wades-card">
            <div class="wades-card-header">
                <h4>New Document</h4>
                <button type="button" class="button remove-document">Remove</button>
            </div>
            <div class="wades-card-body">
                <div class="wades-meta-field">
                    <label>Document Title:</label>
                    <input type="text" name="required_documents[0][title]" class="widefat">
                </div>
                <div class="wades-meta-field">
                    <label>Description:</label>
                    <textarea name="required_documents[0][description]" rows="2" class="widefat"></textarea>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="faq-template">
        <div class="wades-card">
            <div class="wades-card-header">
                <h4>New FAQ</h4>
                <button type="button" class="button remove-faq">Remove</button>
            </div>
            <div class="wades-card-body">
                <div class="wades-meta-field">
                    <label>Question:</label>
                    <input type="text" name="faq_items[0][question]" class="widefat">
                </div>
                <div class="wades-meta-field">
                    <label>Answer:</label>
                    <textarea name="faq_items[0][answer]" rows="3" class="widefat"></textarea>
                </div>
            </div>
        </div>
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

    // Save header title and description
    if (isset($_POST['hero_title'])) {
        update_post_meta($post_id, '_custom_header_title', sanitize_text_field($_POST['hero_title']));
    }
    
    if (isset($_POST['hero_description'])) {
        update_post_meta($post_id, '_custom_header_subtext', wp_kses_post($_POST['hero_description']));
    }

    // Save hero background image
    if (isset($_POST['hero_background_image'])) {
        update_post_meta($post_id, '_hero_background_image', absint($_POST['hero_background_image']));
    }

    // Save hero overlay opacity
    if (isset($_POST['hero_overlay_opacity'])) {
        $opacity = max(0, min(100, absint($_POST['hero_overlay_opacity'])));
        update_post_meta($post_id, '_hero_overlay_opacity', $opacity);
    }

    // Save hero height
    if (isset($_POST['hero_height'])) {
        $height = max(30, min(100, absint($_POST['hero_height'])));
        update_post_meta($post_id, '_hero_height', $height);
    }

    // Save financing intro
    if (isset($_POST['financing_intro'])) {
        update_post_meta($post_id, '_financing_intro', wp_kses_post($_POST['financing_intro']));
    }

    // Save financing partners
    if (isset($_POST['financing_partners']) && is_array($_POST['financing_partners'])) {
        $partners = array();
        foreach ($_POST['financing_partners'] as $partner) {
            if (!empty($partner['name'])) {
                $partners[] = array(
                    'name' => sanitize_text_field($partner['name']),
                    'logo' => !empty($partner['logo']) ? absint($partner['logo']) : '',
                    'description' => wp_kses_post($partner['description']),
                    'link' => !empty($partner['link']) ? esc_url_raw($partner['link']) : ''
                );
            }
        }
        update_post_meta($post_id, '_financing_partners', $partners);
    }

    // Save section order
    if (isset($_POST['section_order'])) {
        update_post_meta($post_id, '_section_order', sanitize_text_field($_POST['section_order']));
    }

    // Save section visibility
    if (isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility'])) {
        $visibility = array();
        foreach ($_POST['sections_visibility'] as $section => $value) {
            $visibility[$section] = '1';
        }
        update_post_meta($post_id, '_sections_visibility', $visibility);
    }

    // Save financing steps
    if (isset($_POST['financing_steps'])) {
        $steps = array();
        foreach ($_POST['financing_steps'] as $step) {
            if (!empty($step['title'])) {
                $steps[] = array(
                    'title' => sanitize_text_field($step['title']),
                    'description' => wp_kses_post($step['description']),
                    'icon' => sanitize_text_field($step['icon'])
                );
            }
        }
        update_post_meta($post_id, '_financing_steps', $steps);
    }

    // Save required documents
    if (isset($_POST['required_documents'])) {
        $documents = array();
        foreach ($_POST['required_documents'] as $document) {
            if (!empty($document['title'])) {
                $documents[] = array(
                    'title' => sanitize_text_field($document['title']),
                    'description' => wp_kses_post($document['description'])
                );
            }
        }
        update_post_meta($post_id, '_required_documents', $documents);
    }

    // Save FAQ items
    if (isset($_POST['faq_items'])) {
        $faqs = array();
        foreach ($_POST['faq_items'] as $faq) {
            if (!empty($faq['question'])) {
                $faqs[] = array(
                    'question' => sanitize_text_field($faq['question']),
                    'answer' => wp_kses_post($faq['answer'])
                );
            }
        }
        update_post_meta($post_id, '_faq_items', $faqs);
    }
}
add_action('save_post', 'wades_save_financing_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_financing_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (isset($post) && $post->post_type === 'page') {
            // Get the template
            $template = get_page_template_slug($post->ID);
            
            // Check if it's our financing template
            if ($template === 'templates/financing.php') {
                // Enqueue required WordPress media scripts
                wp_enqueue_media();
                
                // Enqueue jQuery UI for sortable functionality
                wp_enqueue_script('jquery-ui-sortable');
                
                // Enqueue meta box styles
                wp_enqueue_style(
                    'wades-meta-box-styles',
                    get_template_directory_uri() . '/inc/meta-boxes/meta-box-styles.css',
                    array(),
                    _S_VERSION
                );
            }
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_financing_admin_scripts'); 