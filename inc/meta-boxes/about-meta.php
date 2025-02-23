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
function wades_add_about_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    
    // Only add these meta boxes for the about template
    if ($template !== 'templates/about.php') {
        return;
    }

    add_meta_box(
        'wades_about_settings',
        'Page Settings',
        'wades_about_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_about_meta_boxes', 1);

/**
 * About Content Meta Box Callback
 */
function wades_about_settings_callback($post) {
    wp_nonce_field('wades_about_meta', 'wades_about_meta_nonce');

    // Get all meta data
    $hero_meta = array(
        'title' => get_post_meta($post->ID, '_hero_title', true),
        'description' => get_post_meta($post->ID, '_hero_description', true),
        'background_image' => get_post_meta($post->ID, '_hero_background_image', true)
    );

    $team_meta = array(
        'title' => get_post_meta($post->ID, '_team_section_title', true) ?: 'Our Team',
        'description' => get_post_meta($post->ID, '_team_section_description', true),
        'members' => get_post_meta($post->ID, '_team_members', true) ?: array()
    );

    $history_meta = array(
        'title' => get_post_meta($post->ID, '_history_section_title', true),
        'content' => get_post_meta($post->ID, '_history_content', true),
        'image' => get_post_meta($post->ID, '_history_image', true)
    );

    $values_meta = array(
        'title' => get_post_meta($post->ID, '_values_section_title', true),
        'values' => get_post_meta($post->ID, '_company_values', true) ?: array()
    );

    // Get sections configuration
    $sections = get_post_meta($post->ID, '_about_sections', true) ?: array(
        'hero' => array('enabled' => true, 'order' => 10, 'title' => 'Hero Section'),
        'history' => array('enabled' => true, 'order' => 20, 'title' => 'Our History'),
        'values' => array('enabled' => true, 'order' => 30, 'title' => 'Our Values'),
        'team' => array('enabled' => true, 'order' => 40, 'title' => 'Our Team')
    );

    // Render the shared header fields
    wades_render_header_fields($post);
}

// Add content for the Content tab
add_action('wades_meta_box_content_tab', 'wades_about_content_tab');
function wades_about_content_tab($post) {
    // Get all meta data
    $team_meta = array(
        'title' => get_post_meta($post->ID, '_team_section_title', true) ?: 'Our Team',
        'description' => get_post_meta($post->ID, '_team_section_description', true),
        'members' => get_post_meta($post->ID, '_team_members', true) ?: array()
    );

    // Get sections configuration
    $sections = get_post_meta($post->ID, '_about_sections', true) ?: array(
        'hero' => array('enabled' => true, 'order' => 10, 'title' => 'Hero Section'),
        'history' => array('enabled' => true, 'order' => 20, 'title' => 'Our History'),
        'values' => array('enabled' => true, 'order' => 30, 'title' => 'Our Values'),
        'team' => array('enabled' => true, 'order' => 40, 'title' => 'Our Team')
    );
    ?>
    <div class="wades-meta-section">
        <h3>Section Order & Visibility</h3>
        <p class="description">Enable/disable sections and drag to reorder them.</p>
        <div class="sections-list" style="margin-top: 15px;">
            <?php foreach ($sections as $section_id => $section) : ?>
                <div class="section-item" style="padding: 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 5px;">
                    <input type="hidden" 
                           name="about_sections[<?php echo esc_attr($section_id); ?>][order]" 
                           value="<?php echo esc_attr($section['order']); ?>"
                           class="section-order">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <span class="dashicons dashicons-menu" style="cursor: move;"></span>
                        <input type="checkbox" 
                               name="about_sections[<?php echo esc_attr($section_id); ?>][enabled]" 
                               <?php checked($section['enabled']); ?>>
                        <?php echo esc_html($section['title']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Section-specific content fields -->
    <div class="wades-meta-section">
        <h3>Section Content</h3>
        
        <!-- History Section -->
        <div class="section-content" data-section="history">
            <h4>Our History</h4>
            <div class="wades-meta-field">
                <label for="history_section_title">Section Title:</label>
                <input type="text" id="history_section_title" name="history_section_title" 
                       value="<?php echo esc_attr(get_post_meta($post->ID, '_history_section_title', true)); ?>" 
                       class="widefat">
            </div>
            <div class="wades-meta-field">
                <label for="history_content">Content:</label>
                <?php 
                wp_editor(
                    get_post_meta($post->ID, '_history_content', true),
                    'history_content',
                    array(
                        'textarea_name' => 'history_content',
                        'media_buttons' => true,
                        'textarea_rows' => 10
                    )
                ); 
                ?>
            </div>
        </div>

        <!-- Values Section -->
        <div class="section-content" data-section="values">
            <h4>Our Values</h4>
            <div class="wades-meta-field">
                <label for="values_section_title">Section Title:</label>
                <input type="text" id="values_section_title" name="values_section_title" 
                       value="<?php echo esc_attr(get_post_meta($post->ID, '_values_section_title', true)); ?>" 
                       class="widefat">
            </div>
            <div class="values-list">
                <?php 
                $values = get_post_meta($post->ID, '_company_values', true) ?: array();
                if (empty($values)) {
                    $values = array(array('title' => '', 'description' => '', 'icon' => ''));
                }
                foreach ($values as $index => $value) : 
                ?>
                    <div class="value-item card">
                        <div class="card-header">
                            <h4>Value <?php echo $index + 1; ?></h4>
                            <button type="button" class="button remove-value">Remove</button>
                        </div>
                        <div class="card-body">
                            <div class="wades-meta-field">
                                <label>Title:</label>
                                <input type="text" name="company_values[<?php echo $index; ?>][title]" 
                                       value="<?php echo esc_attr($value['title']); ?>" class="widefat">
                            </div>
                            <div class="wades-meta-field">
                                <label>Description:</label>
                                <textarea name="company_values[<?php echo $index; ?>][description]" 
                                          rows="2" class="widefat"><?php echo esc_textarea($value['description']); ?></textarea>
                            </div>
                            <div class="wades-meta-field">
                                <label>Icon (Lucide icon name):</label>
                                <input type="text" name="company_values[<?php echo $index; ?>][icon]" 
                                       value="<?php echo esc_attr($value['icon']); ?>" class="widefat">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button add-value">Add Value</button>
        </div>

        <!-- Team Section -->
        <div class="section-content" data-section="team">
            <h4>Our Team</h4>
            <div class="wades-meta-field">
                <label for="team_section_title">Section Title:</label>
                <input type="text" id="team_section_title" name="team_section_title" 
                       value="<?php echo esc_attr($team_meta['title']); ?>" class="widefat">
            </div>
            <div class="wades-meta-field">
                <label for="team_section_description">Section Description:</label>
                <textarea id="team_section_description" name="team_section_description" 
                          rows="3" class="widefat"><?php echo esc_textarea($team_meta['description']); ?></textarea>
            </div>
            <div class="team-members-list">
                <?php 
                $members = $team_meta['members'];
                if (empty($members)) {
                    $members = array(array('name' => '', 'position' => '', 'bio' => '', 'image' => ''));
                }
                foreach ($members as $index => $member) : 
                ?>
                    <div class="team-member-item card">
                        <div class="card-header">
                            <h4>Team Member <?php echo $index + 1; ?></h4>
                            <button type="button" class="button remove-member">Remove</button>
                        </div>
                        <div class="card-body">
                            <div class="wades-meta-field">
                                <label>Name:</label>
                                <input type="text" name="team_members[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($member['name']); ?>" class="widefat">
                            </div>
                            <div class="wades-meta-field">
                                <label>Position:</label>
                                <input type="text" name="team_members[<?php echo $index; ?>][position]" 
                                       value="<?php echo esc_attr($member['position']); ?>" class="widefat">
                            </div>
                            <div class="wades-meta-field">
                                <label>Bio:</label>
                                <textarea name="team_members[<?php echo $index; ?>][bio]" 
                                          rows="3" class="widefat"><?php echo esc_textarea($member['bio']); ?></textarea>
                            </div>
                            <div class="wades-meta-field">
                                <label>Photo:</label>
                                <input type="hidden" name="team_members[<?php echo $index; ?>][image]" 
                                       value="<?php echo esc_attr($member['image']); ?>" class="member-image-input">
                                <div class="button-group">
                                    <button type="button" class="button upload-member-image">Select Photo</button>
                                    <button type="button" class="button remove-member-image">Remove Photo</button>
                                </div>
                                <div class="member-image-preview">
                                    <?php if ($member['image']) : ?>
                                        <?php echo wp_get_attachment_image($member['image'], 'thumbnail'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button add-team-member">Add Team Member</button>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Section ordering
        $('.sections-list').sortable({
            handle: '.dashicons-menu',
            update: function(event, ui) {
                $('.sections-list .section-item').each(function(index) {
                    $(this).find('.section-order').val((index + 1) * 10);
                });
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
                        src: attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url,
                        style: 'max-width: 150px; height: auto;'
                    }));
                });

                frame.open();
            });
        }

        // Initialize team member image uploads
        $('.upload-member-image').each(function() {
            var container = $(this).closest('.team-member-item');
            initImageUpload(
                $(this),
                container.find('.member-image-input'),
                container.find('.member-image-preview')
            );
        });

        // Remove image functionality
        $('.remove-member-image').on('click', function() {
            var container = $(this).closest('.team-member-item');
            container.find('.member-image-input').val('');
            container.find('.member-image-preview').empty();
        });

        // Add value
        $('.add-value').on('click', function() {
            var index = $('.value-item').length;
            var template = `
                <div class="value-item card">
                    <div class="card-header">
                        <h4>Value ${index + 1}</h4>
                        <button type="button" class="button remove-value">Remove</button>
                    </div>
                    <div class="card-body">
                        <div class="wades-meta-field">
                            <label>Title:</label>
                            <input type="text" name="company_values[${index}][title]" class="widefat">
                        </div>
                        <div class="wades-meta-field">
                            <label>Description:</label>
                            <textarea name="company_values[${index}][description]" rows="2" class="widefat"></textarea>
                        </div>
                        <div class="wades-meta-field">
                            <label>Icon (Lucide icon name):</label>
                            <input type="text" name="company_values[${index}][icon]" class="widefat">
                        </div>
                    </div>
                </div>
            `;
            $('.values-list').append(template);
        });

        // Remove value
        $(document).on('click', '.remove-value', function() {
            $(this).closest('.value-item').remove();
            // Update indices
            $('.value-item').each(function(index) {
                $(this).find('h4').text('Value ' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                });
            });
        });

        // Add team member
        $('.add-team-member').on('click', function() {
            var index = $('.team-member-item').length;
            var template = `
                <div class="team-member-item card">
                    <div class="card-header">
                        <h4>Team Member ${index + 1}</h4>
                        <button type="button" class="button remove-member">Remove</button>
                    </div>
                    <div class="card-body">
                        <div class="wades-meta-field">
                            <label>Name:</label>
                            <input type="text" name="team_members[${index}][name]" class="widefat">
                        </div>
                        <div class="wades-meta-field">
                            <label>Position:</label>
                            <input type="text" name="team_members[${index}][position]" class="widefat">
                        </div>
                        <div class="wades-meta-field">
                            <label>Bio:</label>
                            <textarea name="team_members[${index}][bio]" rows="3" class="widefat"></textarea>
                        </div>
                        <div class="wades-meta-field">
                            <label>Photo:</label>
                            <input type="hidden" name="team_members[${index}][image]" class="member-image-input">
                            <div class="button-group">
                                <button type="button" class="button upload-member-image">Select Photo</button>
                                <button type="button" class="button remove-member-image">Remove Photo</button>
                            </div>
                            <div class="member-image-preview"></div>
                        </div>
                    </div>
                </div>
            `;
            var $newMember = $(template);
            $('.team-members-list').append($newMember);
            
            // Initialize image upload for new member
            initImageUpload(
                $newMember.find('.upload-member-image'),
                $newMember.find('.member-image-input'),
                $newMember.find('.member-image-preview')
            );
        });

        // Remove team member
        $(document).on('click', '.remove-member', function() {
            $(this).closest('.team-member-item').remove();
            // Update indices
            $('.team-member-item').each(function(index) {
                $(this).find('h4').text('Team Member ' + (index + 1));
                $(this).find('input, textarea').each(function() {
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
add_action('wades_meta_box_settings_tab', 'wades_about_settings_tab');
function wades_about_settings_tab($post) {
    ?>
    <div class="wades-meta-section">
        <h3>SEO Settings</h3>
        <div class="wades-meta-field">
            <label for="seo_title">SEO Title:</label>
            <input type="text" id="seo_title" name="seo_title" 
                   value="<?php echo esc_attr(get_post_meta($post->ID, '_seo_title', true)); ?>" 
                   class="widefat">
            <p class="description">Custom title for search engines. Leave empty to use the default page title.</p>
        </div>
        <div class="wades-meta-field">
            <label for="seo_description">SEO Description:</label>
            <textarea id="seo_description" name="seo_description" 
                      rows="3" class="widefat"><?php echo esc_textarea(get_post_meta($post->ID, '_seo_description', true)); ?></textarea>
            <p class="description">Custom description for search engines.</p>
        </div>
    </div>
    <?php
}

/**
 * Save About Meta Box Data
 */
function wades_save_about_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['wades_about_meta_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['wades_about_meta_nonce'], 'wades_about_meta')) {
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

    // Save sections configuration
    if (isset($_POST['about_sections'])) {
        $sections = array();
        foreach ($_POST['about_sections'] as $section_id => $section_data) {
            $sections[$section_id] = array(
                'enabled' => isset($section_data['enabled']),
                'order' => absint($section_data['order']),
                'title' => sanitize_text_field($section_data['title'])
            );
        }
        update_post_meta($post_id, '_about_sections', $sections);
    }

    // Save history section
    if (isset($_POST['history_section_title'])) {
        update_post_meta($post_id, '_history_section_title', sanitize_text_field($_POST['history_section_title']));
    }
    if (isset($_POST['history_content'])) {
        update_post_meta($post_id, '_history_content', wp_kses_post($_POST['history_content']));
    }

    // Save values section
    if (isset($_POST['values_section_title'])) {
        update_post_meta($post_id, '_values_section_title', sanitize_text_field($_POST['values_section_title']));
    }
    if (isset($_POST['company_values']) && is_array($_POST['company_values'])) {
        $values = array();
        foreach ($_POST['company_values'] as $value) {
            if (!empty($value['title'])) {
                $values[] = array(
                    'title' => sanitize_text_field($value['title']),
                    'description' => wp_kses_post($value['description']),
                    'icon' => sanitize_text_field($value['icon'])
                );
            }
        }
        update_post_meta($post_id, '_company_values', $values);
    }

    // Save team section
    if (isset($_POST['team_section_title'])) {
        update_post_meta($post_id, '_team_section_title', sanitize_text_field($_POST['team_section_title']));
    }
    if (isset($_POST['team_section_description'])) {
        update_post_meta($post_id, '_team_section_description', wp_kses_post($_POST['team_section_description']));
    }
    if (isset($_POST['team_members']) && is_array($_POST['team_members'])) {
        $members = array();
        foreach ($_POST['team_members'] as $member) {
            if (!empty($member['name'])) {
                $members[] = array(
                    'name' => sanitize_text_field($member['name']),
                    'position' => sanitize_text_field($member['position']),
                    'bio' => wp_kses_post($member['bio']),
                    'image' => absint($member['image'])
                );
            }
        }
        update_post_meta($post_id, '_team_members', $members);
    }

    // Save SEO settings
    if (isset($_POST['seo_title'])) {
        update_post_meta($post_id, '_seo_title', sanitize_text_field($_POST['seo_title']));
    }
    if (isset($_POST['seo_description'])) {
        update_post_meta($post_id, '_seo_description', sanitize_textarea_field($_POST['seo_description']));
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
                    $(".wades-tab-button").on("click", function() {
                        $(".wades-tab-button").removeClass("active");
                        $(".wades-tab-content").removeClass("active");
                        $(this).addClass("active");
                        $(".wades-tab-content[data-tab=\"" + $(this).data("tab") + "\"]").addClass("active");
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
add_action('admin_enqueue_scripts', 'wades_about_admin_scripts'); 