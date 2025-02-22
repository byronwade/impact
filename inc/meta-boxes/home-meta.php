<?php
/**
 * Custom Meta Boxes for Home Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function wades_remove_duplicate_meta_boxes() {
    remove_meta_box('wades_hero_section', 'page', 'normal');
    remove_meta_box('wades_cta_section', 'page', 'normal');
    remove_meta_box('wades_home_hero_meta', 'page', 'normal');
}
add_action('do_meta_boxes', 'wades_remove_duplicate_meta_boxes');

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

    // Single meta box with tabs
    add_meta_box(
        'wades_home_settings',
        'Home Page Settings',
        'wades_home_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_home_meta_boxes', 1);

// Register the home template
function wades_add_home_template($templates) {
    $templates['templates/home.php'] = 'Home Template';
    return $templates;
}
add_filter('theme_page_templates', 'wades_add_home_template');

// Save the meta box data
function wades_save_home_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['wades_home_meta_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['wades_home_meta_nonce'], 'wades_home_meta')) {
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

    // Save hero section
    $hero_fields = array(
        'hero_title', 'hero_description', 'hero_phone_number',
        'hero_primary_cta_label', 'hero_primary_cta_page', 'hero_primary_cta_icon',
        'hero_secondary_cta_label', 'hero_secondary_cta_page', 'hero_secondary_cta_icon'
    );

    foreach ($hero_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
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

    // Save services section
    $services_fields = array(
        'services_section_title' => 'text',
        'services_section_description' => 'textarea',
        'services_layout' => 'text',
        'services_count' => 'number',
        'show_services_view_all' => 'checkbox'
    );

    foreach ($services_fields as $field => $type) {
        if ($type === 'checkbox') {
            update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? 'yes' : 'no');
        } elseif ($type === 'number') {
            update_post_meta($post_id, '_' . $field, absint($_POST[$field]));
        } else {
            if (isset($_POST[$field])) {
                $value = $type === 'textarea' ? wp_kses_post($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Save about section
    $about_fields = array(
        'about_title' => 'text',
        'about_content' => 'html',
        'about_image' => 'number',
        'about_cta_text' => 'text',
        'about_cta_link' => 'url'
    );

    foreach ($about_fields as $field => $type) {
        if (isset($_POST[$field])) {
            switch ($type) {
                case 'html':
                    update_post_meta($post_id, '_' . $field, wp_kses_post($_POST[$field]));
                    break;
                case 'url':
                    update_post_meta($post_id, '_' . $field, esc_url_raw($_POST[$field]));
                    break;
                case 'number':
                    update_post_meta($post_id, '_' . $field, absint($_POST[$field]));
                    break;
                default:
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    // Save CTA section
    $cta_fields = array(
        'cta_title' => 'text',
        'cta_description' => 'textarea',
        'cta_button_text' => 'text',
        'cta_button_link' => 'url'
    );

    foreach ($cta_fields as $field => $type) {
        if (isset($_POST[$field])) {
            switch ($type) {
                case 'textarea':
                    update_post_meta($post_id, '_' . $field, wp_kses_post($_POST[$field]));
                    break;
                case 'url':
                    update_post_meta($post_id, '_' . $field, esc_url_raw($_POST[$field]));
                    break;
                default:
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post', 'wades_save_home_meta');

// Keep only the main settings callback function
function wades_home_settings_callback($post) {
    wp_nonce_field('wades_home_meta', 'wades_home_meta_nonce');

    // Get all meta data
    $hero_meta = array(
        'title' => get_post_meta($post->ID, '_hero_title', true),
        'description' => get_post_meta($post->ID, '_hero_description', true),
        'phoneNumber' => get_post_meta($post->ID, '_hero_phone_number', true),
        'primaryCta' => array(
            'label' => get_post_meta($post->ID, '_hero_primary_cta_label', true),
            'page_id' => get_post_meta($post->ID, '_hero_primary_cta_page', true),
            'icon' => get_post_meta($post->ID, '_hero_primary_cta_icon', true)
        ),
        'secondaryCta' => array(
            'label' => get_post_meta($post->ID, '_hero_secondary_cta_label', true),
            'page_id' => get_post_meta($post->ID, '_hero_secondary_cta_page', true),
            'icon' => get_post_meta($post->ID, '_hero_secondary_cta_icon', true)
        )
    );

    $brands = get_post_meta($post->ID, '_home_brands', true) ?: array(
        array('name' => 'MB Sports', 'url' => '', 'image' => ''),
        array('name' => 'Viaggio Pontoons', 'url' => '', 'image' => ''),
        array('name' => 'Yamaha', 'url' => '', 'image' => ''),
        array('name' => 'Mercury', 'url' => '', 'image' => ''),
        array('name' => 'Suzuki', 'url' => '', 'image' => '')
    );

    $services_meta = array(
        'title' => get_post_meta($post->ID, '_services_section_title', true) ?: 'Our Services',
        'description' => get_post_meta($post->ID, '_services_section_description', true) ?: 'Explore our comprehensive range of boat services',
        'layout' => get_post_meta($post->ID, '_services_layout', true) ?: 'grid',
        'count' => get_post_meta($post->ID, '_services_count', true) ?: 6,
        'show_view_all' => get_post_meta($post->ID, '_show_services_view_all', true) !== 'no'
    );

    $about_meta = array(
        'title' => get_post_meta($post->ID, '_about_title', true),
        'content' => get_post_meta($post->ID, '_about_content', true),
        'image' => get_post_meta($post->ID, '_about_image', true),
        'cta_text' => get_post_meta($post->ID, '_about_cta_text', true),
        'cta_link' => get_post_meta($post->ID, '_about_cta_link', true)
    );

    $cta_meta = array(
        'title' => get_post_meta($post->ID, '_cta_title', true),
        'description' => get_post_meta($post->ID, '_cta_description', true),
        'button_text' => get_post_meta($post->ID, '_cta_button_text', true),
        'button_link' => get_post_meta($post->ID, '_cta_button_link', true)
    );

    // Get sections configuration
    $sections = get_post_meta($post->ID, '_home_sections', true) ?: array(
        'hero' => array('enabled' => true, 'order' => 10, 'title' => 'Hero Section'),
        'featured_brands' => array('enabled' => true, 'order' => 20, 'title' => 'Featured Brands'),
        'fleet' => array('enabled' => true, 'order' => 30, 'title' => 'Fleet Section'),
        'services' => array('enabled' => true, 'order' => 40, 'title' => 'Services Section'),
        'testimonials' => array('enabled' => true, 'order' => 50, 'title' => 'Testimonials'),
        'social' => array('enabled' => true, 'order' => 60, 'title' => 'Social Feed'),
        'cta' => array('enabled' => true, 'order' => 70, 'title' => 'Call to Action')
    );

    // Define section descriptions
    $section_descriptions = array(
        'hero' => 'Main hero banner with title, description, and call-to-action buttons.',
        'featured_brands' => 'Display logos of featured boat brands and manufacturers.',
        'fleet' => 'Showcase featured boats and inventory highlights.',
        'services' => 'Highlight key marine services and maintenance offerings.',
        'testimonials' => 'Customer reviews and testimonials section.',
        'social' => 'Instagram feed and social media integration.',
        'cta' => 'Final call-to-action section with contact information.'
    );
    ?>
    <div class="meta-box-container">
        <!-- Tab Navigation -->
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="layout">Layout & Order</button>
            <button type="button" class="tab-button" data-tab="hero">Hero Section</button>
            <button type="button" class="tab-button" data-tab="brands">Featured Brands</button>
            <button type="button" class="tab-button" data-tab="services">Services</button>
            <button type="button" class="tab-button" data-tab="about">About Section</button>
            <button type="button" class="tab-button" data-tab="cta">Call to Action</button>
        </div>

        <!-- Layout & Order Tab -->
        <div class="tab-content active" data-tab="layout">
            <div class="meta-box-section">
                <h3>Section Order & Visibility</h3>
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
                                <div>
                                    <strong><?php echo esc_html($section['title']); ?></strong>
                                    <?php if (isset($section_descriptions[$section_id])) : ?>
                                        <p class="description" style="margin: 2px 0 0 0; font-size: 12px;">
                                            <?php echo esc_html($section_descriptions[$section_id]); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Hero Section Tab -->
        <div class="tab-content" data-tab="hero">
            <div class="meta-box-section">
                <h3>Hero Content</h3>
                <p>
                    <label for="hero_title">Hero Title:</label>
                    <input type="text" id="hero_title" name="hero_title" 
                           value="<?php echo esc_attr($hero_meta['title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="hero_description">Hero Description:</label>
                    <textarea id="hero_description" name="hero_description" rows="3" 
                              class="widefat"><?php echo esc_textarea($hero_meta['description']); ?></textarea>
                </p>
                <p>
                    <label for="hero_phone_number">Phone Number:</label>
                    <input type="text" id="hero_phone_number" name="hero_phone_number" 
                           value="<?php echo esc_attr($hero_meta['phoneNumber']); ?>" class="widefat">
                </p>
            </div>

            <div class="meta-box-section">
        <h3>Primary Call to Action</h3>
        <p>
                    <label for="hero_primary_cta_label">Button Text:</label>
            <input type="text" id="hero_primary_cta_label" name="hero_primary_cta_label" 
                   value="<?php echo esc_attr($hero_meta['primaryCta']['label']); ?>" class="widefat">
        </p>
        <p>
                    <label for="hero_primary_cta_page">Link to Page:</label>
            <?php echo wades_page_select_field('hero_primary_cta_page', $hero_meta['primaryCta']['page_id']); ?>
        </p>
        <p>
                    <label for="hero_primary_cta_icon">Icon:</label>
            <input type="text" id="hero_primary_cta_icon" name="hero_primary_cta_icon" 
                   value="<?php echo esc_attr($hero_meta['primaryCta']['icon']); ?>" class="widefat">
        </p>
            </div>

            <div class="meta-box-section">
        <h3>Secondary Call to Action</h3>
        <p>
                    <label for="hero_secondary_cta_label">Button Text:</label>
            <input type="text" id="hero_secondary_cta_label" name="hero_secondary_cta_label" 
                   value="<?php echo esc_attr($hero_meta['secondaryCta']['label']); ?>" class="widefat">
        </p>
        <p>
                    <label for="hero_secondary_cta_page">Link to Page:</label>
            <?php echo wades_page_select_field('hero_secondary_cta_page', $hero_meta['secondaryCta']['page_id']); ?>
        </p>
        <p>
                    <label for="hero_secondary_cta_icon">Icon:</label>
            <input type="text" id="hero_secondary_cta_icon" name="hero_secondary_cta_icon" 
                   value="<?php echo esc_attr($hero_meta['secondaryCta']['icon']); ?>" class="widefat">
        </p>
            </div>
        </div>

        <!-- Featured Brands Tab -->
        <div class="tab-content" data-tab="brands">
            <div class="meta-box-section">
                <h3>Brand Logos</h3>
                <div class="brands-list">
                    <?php foreach ($brands as $index => $brand) : ?>
                        <div class="brand-item card">
                            <div class="card-header">
                                <h4>Brand <?php echo $index + 1; ?></h4>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Brand Name:</label>
                                    <input type="text" name="home_brands[<?php echo $index; ?>][name]" 
                                           value="<?php echo esc_attr($brand['name']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Brand URL:</label>
                                    <input type="url" name="home_brands[<?php echo $index; ?>][url]" 
                                           value="<?php echo esc_url($brand['url']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Brand Logo:</label>
                                    <input type="hidden" name="home_brands[<?php echo $index; ?>][image]" 
                                           value="<?php echo esc_attr($brand['image']); ?>" class="brand-image-input">
                                    <div class="button-group">
                                        <button type="button" class="button upload-brand-image">Upload Logo</button>
                                        <button type="button" class="button remove-brand-image">Remove Logo</button>
                                    </div>
                                    <div class="brand-image-preview">
                                        <?php if ($brand['image']) : ?>
                                            <?php echo wp_get_attachment_image($brand['image'], 'thumbnail'); ?>
                                        <?php endif; ?>
                                    </div>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Services Tab -->
        <div class="tab-content" data-tab="services">
            <div class="meta-box-section">
                <h3>Services Section Settings</h3>
                <p>
                    <label for="services_section_title">Section Title:</label>
                    <input type="text" id="services_section_title" name="services_section_title" 
                           value="<?php echo esc_attr($services_meta['title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="services_section_description">Section Description:</label>
                    <textarea id="services_section_description" name="services_section_description" 
                              rows="3" class="widefat"><?php echo esc_textarea($services_meta['description']); ?></textarea>
                </p>
                <p>
                    <label for="services_layout">Layout Style:</label>
                    <select id="services_layout" name="services_layout" class="widefat">
                        <option value="grid" <?php selected($services_meta['layout'], 'grid'); ?>>Grid</option>
                        <option value="list" <?php selected($services_meta['layout'], 'list'); ?>>List</option>
                        <option value="carousel" <?php selected($services_meta['layout'], 'carousel'); ?>>Carousel</option>
                    </select>
                </p>
                <p>
                    <label for="services_count">Number of Services to Display:</label>
                    <input type="number" id="services_count" name="services_count" 
                           value="<?php echo esc_attr($services_meta['count']); ?>" 
                           class="small-text" min="1" max="12">
                    <span class="description">Maximum 12 services</span>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_services_view_all" value="1" 
                               <?php checked($services_meta['show_view_all']); ?>>
                        Show "View All Services" button
                    </label>
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Currently Selected Homepage Services</h3>
                <?php
                $services = get_posts(array(
                    'post_type' => 'service',
                    'posts_per_page' => -1,
                    'meta_key' => '_home_order',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => '_show_on_home',
                            'value' => '1'
                        )
                    )
                ));

                if ($services) : ?>
                    <table class="widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Service</th>
                                <th>Homepage Description</th>
                                <th>Icon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service) : 
                                $home_order = get_post_meta($service->ID, '_home_order', true);
                                $home_desc = get_post_meta($service->ID, '_home_description', true);
                                $home_icon = get_post_meta($service->ID, '_home_icon', true);
                            ?>
                                <tr>
                                    <td><?php echo esc_html($home_order); ?></td>
                                    <td><?php echo esc_html($service->post_title); ?></td>
                                    <td><?php echo esc_html($home_desc ?: '(Using default description)'); ?></td>
                                    <td><?php echo esc_html($home_icon ?: '(Using default icon)'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="description">
                        To modify which services appear here, edit individual services and update their homepage settings.
                    </p>
                <?php else : ?>
                    <p>No services are currently set to display on the homepage. Edit individual services to enable homepage display.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- About Section Tab -->
        <div class="tab-content" data-tab="about">
            <div class="meta-box-section">
                <h3>About Section Content</h3>
                <p>
                    <label for="about_title">Section Title:</label>
                    <input type="text" id="about_title" name="about_title" 
                           value="<?php echo esc_attr($about_meta['title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="about_content">Content:</label>
                    <?php wp_editor($about_meta['content'], 'about_content', array('textarea_name' => 'about_content')); ?>
                </p>
                <p>
                    <label for="about_image">Image:</label>
                    <input type="hidden" id="about_image" name="about_image" 
                           value="<?php echo esc_attr($about_meta['image']); ?>">
                    <div class="button-group">
                        <button type="button" class="button upload-image">Upload Image</button>
                    </div>
                    <div class="image-preview">
                        <?php if ($about_meta['image']) : ?>
                            <?php echo wp_get_attachment_image($about_meta['image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
                <p>
                    <label for="about_cta_text">CTA Button Text:</label>
                    <input type="text" id="about_cta_text" name="about_cta_text" 
                           value="<?php echo esc_attr($about_meta['cta_text']); ?>" class="widefat">
                </p>
                <p>
                    <label for="about_cta_link">CTA Button Link:</label>
                    <input type="text" id="about_cta_link" name="about_cta_link" 
                           value="<?php echo esc_url($about_meta['cta_link']); ?>" class="widefat">
                </p>
            </div>
        </div>

        <!-- Call to Action Tab -->
        <div class="tab-content" data-tab="cta">
            <div class="meta-box-section">
                <h3>Call to Action Settings</h3>
                <p>
                    <label for="cta_title">CTA Title:</label>
                    <input type="text" id="cta_title" name="cta_title" 
                           value="<?php echo esc_attr($cta_meta['title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="cta_description">CTA Description:</label>
                    <textarea id="cta_description" name="cta_description" rows="3" 
                              class="widefat"><?php echo esc_textarea($cta_meta['description']); ?></textarea>
        </p>
        <p>
                    <label for="cta_button_text">Button Text:</label>
                    <input type="text" id="cta_button_text" name="cta_button_text" 
                           value="<?php echo esc_attr($cta_meta['button_text']); ?>" class="widefat">
        </p>
        <p>
                    <label for="cta_button_link">Button Link:</label>
                    <input type="text" id="cta_button_link" name="cta_button_link" 
                           value="<?php echo esc_url($cta_meta['button_link']); ?>" class="widefat">
                </p>
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
                        src: attachment.url,
                        style: 'max-width: 200px; height: auto;'
                    }));
                });

                frame.open();
            });
        }

        // Initialize image upload for brand logos
        $('.upload-brand-image').each(function() {
            var container = $(this).closest('.brand-item');
            initImageUpload(
                $(this),
                container.find('.brand-image-input'),
                container.find('.brand-image-preview')
            );
        });

        // Initialize image upload for about section
        initImageUpload(
            $('.upload-image'),
            $('#about_image'),
            $('.image-preview')
        );

        // Remove image functionality
        $('.remove-brand-image').on('click', function() {
            var container = $(this).closest('.brand-item');
            container.find('.brand-image-input').val('');
            container.find('.brand-image-preview').empty();
        });
    });
    </script>
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

// Update the save function to handle the new fields
add_action('save_post', function($post_id) {
    if (!isset($_POST['wades_home_meta_nonce']) || 
        !wp_verify_nonce($_POST['wades_home_meta_nonce'], 'wades_home_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save services section settings
    $fields = array(
        'services_section_title' => 'text',
        'services_section_description' => 'textarea',
        'services_layout' => 'text',
        'services_count' => 'number',
        'show_services_view_all' => 'checkbox'
    );

    foreach ($fields as $field => $type) {
        if ($type === 'checkbox') {
            update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? 'yes' : 'no');
        } elseif ($type === 'number') {
            update_post_meta($post_id, '_' . $field, absint($_POST[$field]));
        } else {
            if (isset($_POST[$field])) {
                $value = $type === 'textarea' ? wp_kses_post($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
}); 