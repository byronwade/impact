<?php
/**
 * Custom Meta Boxes for Financing Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add meta boxes for Financing template
 */
function wades_add_financing_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    
    // Only add these meta boxes for the financing template
    if ($template !== 'templates/financing.php') {
        return;
    }

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
add_action('add_meta_boxes', 'wades_add_financing_meta_boxes', 1);

/**
 * Financing Settings Meta Box Callback
 */
function wades_financing_settings_callback($post) {
    wp_nonce_field('wades_financing_meta', 'wades_financing_meta_nonce');

    // Get all meta data
    $meta = array(
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true),
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '70',
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
    ?>
    <div class="meta-box-container">
        <!-- Tab Navigation -->
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="layout">Layout & Order</button>
            <button type="button" class="tab-button" data-tab="header">Page Header</button>
            <button type="button" class="tab-button" data-tab="intro">Introduction</button>
            <button type="button" class="tab-button" data-tab="partners">Financing Partners</button>
            <button type="button" class="tab-button" data-tab="steps">Application Steps</button>
            <button type="button" class="tab-button" data-tab="documents">Required Documents</button>
            <button type="button" class="tab-button" data-tab="faq">FAQ</button>
        </div>

        <!-- Layout & Order Tab -->
        <div class="tab-content active" data-tab="layout">
            <div class="meta-box-section">
                <h3>Section Order & Visibility</h3>
                <p class="description">Enable/disable sections and drag to reorder them.</p>
                <div class="sections-list" style="margin-top: 15px;">
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
                        <div class="section-item" style="padding: 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 5px;">
                            <input type="hidden" 
                                   name="section_order" 
                                   value="<?php echo esc_attr($meta['section_order']); ?>"
                                   class="section-order">
                            <label style="display: flex; align-items: center; gap: 10px;">
                                <span class="dashicons dashicons-menu" style="cursor: move;"></span>
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
        <div class="tab-content" data-tab="header">
            <div class="meta-box-section">
                <h3>Page Header Settings</h3>
                <div class="mb-6">
                    <label class="block mb-2 font-medium">Background Image</label>
                    <div class="flex items-start gap-4">
                        <div>
                            <input type="hidden" name="hero_background_image" id="hero_background_image" 
                                   value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                            <div class="button-group">
                                <button type="button" class="button upload-image" id="upload_hero_image">Select Image</button>
                                <button type="button" class="button remove-image">Remove Image</button>
                            </div>
                        </div>
                        <div id="hero_image_preview" class="max-w-xs">
                            <?php 
                            if ($meta['hero_background_image']) {
                                echo wp_get_attachment_image($meta['hero_background_image'], 'thumbnail');
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="hero_overlay_opacity" class="block mb-2 font-medium">
                        Overlay Opacity (%)
                    </label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>"
                           class="regular-text" min="0" max="100" step="5">
                </div>

                <div class="mb-6">
                    <label for="hero_height" class="block mb-2 font-medium">
                        Header Height (vh)
                    </label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>"
                           class="regular-text" min="30" max="100" step="5">
                </div>
            </div>
        </div>

        <!-- Introduction Tab -->
        <div class="tab-content" data-tab="intro">
            <div class="meta-box-section">
                <h3>Introduction Content</h3>
                <p>
                    <label for="financing_intro">Introduction Text:</label>
                    <?php 
                    wp_editor(
                        $meta['financing_intro'],
                        'financing_intro',
                        array(
                            'textarea_name' => 'financing_intro',
                            'media_buttons' => true,
                            'textarea_rows' => 10
                        )
                    );
                    ?>
                </p>
            </div>
        </div>

        <!-- Financing Partners Tab -->
        <div class="tab-content" data-tab="partners">
            <div class="meta-box-section">
                <h3>Financing Partners</h3>
                <div class="partners-list">
                    <?php 
                    $partners = $meta['financing_partners'];
                    if (empty($partners)) {
                        $partners = array(array('name' => '', 'logo' => '', 'description' => '', 'link' => ''));
                    }
                    foreach ($partners as $index => $partner) : 
                    ?>
                        <div class="partner-item card">
                            <div class="card-header">
                                <h4>Partner <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-partner">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Partner Name:</label>
                                    <input type="text" name="financing_partners[<?php echo $index; ?>][name]" 
                                           value="<?php echo esc_attr($partner['name']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Partner Logo:</label>
                                    <input type="hidden" name="financing_partners[<?php echo $index; ?>][logo]" 
                                           value="<?php echo esc_attr($partner['logo']); ?>" class="partner-logo-input">
                                    <div class="button-group">
                                        <button type="button" class="button upload-partner-logo">Select Logo</button>
                                        <button type="button" class="button remove-partner-logo">Remove Logo</button>
                                    </div>
                                    <div class="partner-logo-preview">
                                        <?php if ($partner['logo']) : ?>
                                            <?php echo wp_get_attachment_image($partner['logo'], 'thumbnail'); ?>
                                        <?php endif; ?>
                                    </div>
                                </p>
                                <p>
                                    <label>Description:</label>
                                    <textarea name="financing_partners[<?php echo $index; ?>][description]" 
                                              rows="3" class="widefat"><?php echo esc_textarea($partner['description']); ?></textarea>
                                </p>
                                <p>
                                    <label>Website Link:</label>
                                    <input type="url" name="financing_partners[<?php echo $index; ?>][link]" 
                                           value="<?php echo esc_url($partner['link']); ?>" class="widefat">
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-partner">Add Partner</button>
            </div>
        </div>

        <!-- Application Steps Tab -->
        <div class="tab-content" data-tab="steps">
            <div class="meta-box-section">
                <h3>Application Steps</h3>
                <div class="steps-list">
                    <?php 
                    $steps = $meta['financing_steps'];
                    if (empty($steps)) {
                        $steps = array(array('title' => '', 'description' => '', 'icon' => ''));
                    }
                    foreach ($steps as $index => $step) : 
                    ?>
                        <div class="step-item card">
                            <div class="card-header">
                                <h4>Step <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-step">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Step Title:</label>
                                    <input type="text" name="financing_steps[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($step['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label>
                                    <textarea name="financing_steps[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($step['description']); ?></textarea>
                                </p>
                                <p>
                                    <label>Icon (Lucide icon name):</label>
                                    <input type="text" name="financing_steps[<?php echo $index; ?>][icon]" 
                                           value="<?php echo esc_attr($step['icon']); ?>" class="widefat">
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-step">Add Step</button>
            </div>
        </div>

        <!-- Required Documents Tab -->
        <div class="tab-content" data-tab="documents">
            <div class="meta-box-section">
                <h3>Required Documents</h3>
                <div class="documents-list">
                    <?php 
                    $documents = $meta['required_documents'];
                    if (empty($documents)) {
                        $documents = array(array('title' => '', 'description' => ''));
                    }
                    foreach ($documents as $index => $document) : 
                    ?>
                        <div class="document-item card">
                            <div class="card-header">
                                <h4>Document <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-document">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Document Title:</label>
                                    <input type="text" name="required_documents[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($document['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label>
                                    <textarea name="required_documents[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($document['description']); ?></textarea>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-document">Add Document</button>
            </div>
        </div>

        <!-- FAQ Tab -->
        <div class="tab-content" data-tab="faq">
            <div class="meta-box-section">
                <h3>Frequently Asked Questions</h3>
                <div class="faq-list">
                    <?php 
                    $faqs = $meta['faq_items'];
                    if (empty($faqs)) {
                        $faqs = array(array('question' => '', 'answer' => ''));
                    }
                    foreach ($faqs as $index => $faq) : 
                    ?>
                        <div class="faq-item card">
                            <div class="card-header">
                                <h4>FAQ <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-faq">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Question:</label>
                                    <input type="text" name="faq_items[<?php echo $index; ?>][question]" 
                                           value="<?php echo esc_attr($faq['question']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Answer:</label>
                                    <textarea name="faq_items[<?php echo $index; ?>][answer]" 
                                              rows="3" class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                                </p>
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
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            $(this).addClass('active');
            $('.tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        });

        // Section ordering
        $('.sections-list').sortable({
            handle: '.dashicons-menu',
            update: function(event, ui) {
                var order = [];
                $('.sections-list .section-item').each(function() {
                    var sectionId = $(this).find('input[type="checkbox"]').attr('name').match(/\[(.*?)\]/)[1];
                    order.push(sectionId);
                });
                $('input[name="section_order"]').val(order.join(','));
            }
        });

        // Image upload functionality
        function initImageUpload(button, input, preview) {
            button.on('click', function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: 'Select Image',
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    input.val(attachment.id);
                    preview.html($('<img>', {
                        src: attachment.url,
                        style: 'max-width: 200px; height: auto;'
                    }));
                });

                frame.open();
            });
        }

        // Initialize image uploads
        $('.upload-image, .upload-partner-logo').each(function() {
            var container = $(this).closest('.meta-box-section, .partner-item');
            initImageUpload(
                $(this),
                container.find('input[type="hidden"]'),
                container.find('.image-preview, .partner-logo-preview')
            );
        });

        // Remove image functionality
        $('.remove-image, .remove-partner-logo').on('click', function() {
            var container = $(this).closest('.meta-box-section, .partner-item');
            container.find('input[type="hidden"]').val('');
            container.find('.image-preview, .partner-logo-preview').empty();
        });

        // Dynamic list functionality
        function addListItem(container, template) {
            var index = container.children().length;
            container.append(template.replace(/\[\d+\]/g, '[' + index + ']'));
            
            // Initialize image upload for new partner if applicable
            var newItem = container.children().last();
            if (newItem.find('.upload-partner-logo').length) {
                initImageUpload(
                    newItem.find('.upload-partner-logo'),
                    newItem.find('.partner-logo-input'),
                    newItem.find('.partner-logo-preview')
                );
            }
        }

        // Add partner
        $('.add-partner').on('click', function() {
            addListItem($('.partners-list'), $('#partner-template').html());
        });

        // Add step
        $('.add-step').on('click', function() {
            addListItem($('.steps-list'), $('#step-template').html());
        });

        // Add document
        $('.add-document').on('click', function() {
            addListItem($('.documents-list'), $('#document-template').html());
        });

        // Add FAQ
        $('.add-faq').on('click', function() {
            addListItem($('.faq-list'), $('#faq-template').html());
        });

        // Remove items
        $(document).on('click', '.remove-partner, .remove-step, .remove-document, .remove-faq', function() {
            $(this).closest('.card').remove();
        });
    });
    </script>

    <!-- Templates for dynamic items -->
    <script type="text/template" id="partner-template">
        <div class="partner-item card">
            <div class="card-header">
                <h4>New Partner</h4>
                <button type="button" class="button remove-partner">Remove</button>
            </div>
            <div class="card-body">
                <p>
                    <label>Partner Name:</label>
                    <input type="text" name="financing_partners[0][name]" class="widefat">
                </p>
                <p>
                    <label>Partner Logo:</label>
                    <input type="hidden" name="financing_partners[0][logo]" class="partner-logo-input">
                    <div class="button-group">
                        <button type="button" class="button upload-partner-logo">Select Logo</button>
                        <button type="button" class="button remove-partner-logo">Remove Logo</button>
                    </div>
                    <div class="partner-logo-preview"></div>
                </p>
                <p>
                    <label>Description:</label>
                    <textarea name="financing_partners[0][description]" rows="3" class="widefat"></textarea>
                </p>
                <p>
                    <label>Website Link:</label>
                    <input type="url" name="financing_partners[0][link]" class="widefat">
                </p>
            </div>
        </div>
    </script>

    <script type="text/template" id="step-template">
        <div class="step-item card">
            <div class="card-header">
                <h4>New Step</h4>
                <button type="button" class="button remove-step">Remove</button>
            </div>
            <div class="card-body">
                <p>
                    <label>Step Title:</label>
                    <input type="text" name="financing_steps[0][title]" class="widefat">
                </p>
                <p>
                    <label>Description:</label>
                    <textarea name="financing_steps[0][description]" rows="2" class="widefat"></textarea>
                </p>
                <p>
                    <label>Icon (Lucide icon name):</label>
                    <input type="text" name="financing_steps[0][icon]" class="widefat">
                </p>
            </div>
        </div>
    </script>

    <script type="text/template" id="document-template">
        <div class="document-item card">
            <div class="card-header">
                <h4>New Document</h4>
                <button type="button" class="button remove-document">Remove</button>
            </div>
            <div class="card-body">
                <p>
                    <label>Document Title:</label>
                    <input type="text" name="required_documents[0][title]" class="widefat">
                </p>
                <p>
                    <label>Description:</label>
                    <textarea name="required_documents[0][description]" rows="2" class="widefat"></textarea>
                </p>
            </div>
        </div>
    </script>

    <script type="text/template" id="faq-template">
        <div class="faq-item card">
            <div class="card-header">
                <h4>New FAQ</h4>
                <button type="button" class="button remove-faq">Remove</button>
            </div>
            <div class="card-body">
                <p>
                    <label>Question:</label>
                    <input type="text" name="faq_items[0][question]" class="widefat">
                </p>
                <p>
                    <label>Answer:</label>
                    <textarea name="faq_items[0][answer]" rows="3" class="widefat"></textarea>
                </p>
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

    // Save basic fields
    $text_fields = array(
        'hero_background_image',
        'hero_overlay_opacity',
        'hero_height',
        'financing_intro',
        'section_order'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
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

    // Save financing partners
    if (isset($_POST['financing_partners'])) {
        $partners = array();
        foreach ($_POST['financing_partners'] as $partner) {
            if (!empty($partner['name'])) {
                $partners[] = array(
                    'name' => sanitize_text_field($partner['name']),
                    'logo' => absint($partner['logo']),
                    'description' => wp_kses_post($partner['description']),
                    'link' => esc_url_raw($partner['link'])
                );
            }
        }
        update_post_meta($post_id, '_financing_partners', $partners);
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