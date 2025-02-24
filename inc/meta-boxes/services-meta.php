<?php
/**
 * Meta Boxes for Services Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Remove any conflicting template registration
if (function_exists('wades_register_services_template')) {
    remove_filter('theme_page_templates', 'wades_register_services_template');
}

/**
 * Add meta boxes for Services template
 */
function wades_add_services_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    wades_debug_log('Services meta box - Current template: ' . $template);
    
    // Only add these meta boxes for the services template
    if ($template !== 'templates/services.php') {
        return;
    }

    // Remove any existing meta boxes to prevent duplicates
    remove_meta_box('wades_page_header', 'page', 'normal');
    remove_meta_box('wades_services_settings', 'page', 'normal');
    remove_meta_box('wades_header_settings', 'page', 'normal');

    // Add services settings meta box
    add_meta_box(
        'wades_services_settings',
        __('Services Page Settings', 'wades'),
        'wades_services_settings_callback',
        'page',
        'normal',
        'high'
    );
}
// Change priority to run after other meta box registrations
remove_action('add_meta_boxes', 'wades_add_services_meta_boxes', 20);
add_action('add_meta_boxes', 'wades_add_services_meta_boxes', 100);

/**
 * Services Settings Meta Box Callback
 */
function wades_services_settings_callback($post) {
    // Ensure we only output one nonce field
    wp_nonce_field('wades_services_meta', 'wades_services_meta_nonce');

    // Get all meta data
    $meta = array(
        'services_title' => get_post_meta($post->ID, '_services_title', true),
        'services_description' => get_post_meta($post->ID, '_services_description', true),
        'services_grid' => get_post_meta($post->ID, '_services_grid', true) ?: array(),
        'why_choose_us' => get_post_meta($post->ID, '_why_choose_us', true) ?: array(),
        'service_image' => get_post_meta($post->ID, '_service_image', true),
        'winterization_packages' => get_post_meta($post->ID, '_winterization_packages', true) ?: array(),
        'service_policies' => get_post_meta($post->ID, '_service_policies', true) ?: array(),
        'grid_columns' => get_post_meta($post->ID, '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'services_per_page' => get_post_meta($post->ID, '_services_per_page', true) ?: '12',
        'show_search' => get_post_meta($post->ID, '_show_search', true) ?: '1',
        'show_filters' => get_post_meta($post->ID, '_show_filters', true) ?: '1',
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'services,why_choose_us,winterization,policies',
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'services' => '1',
            'why_choose_us' => '1',
            'winterization' => '1',
            'policies' => '1'
        ),
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true) ?: '',
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '50',
        'custom_header_title' => get_post_meta($post->ID, '_custom_header_title', true),
        'custom_header_subtext' => get_post_meta($post->ID, '_custom_header_subtext', true)
    );

    // Start meta box container
    ?>
    <div class="meta-box-container">
        <!-- Tab Navigation -->
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="layout">Layout & Order</button>
            <button type="button" class="tab-button" data-tab="header">Page Header</button>
            <button type="button" class="tab-button" data-tab="services">Services Grid</button>
            <button type="button" class="tab-button" data-tab="why-choose">Why Choose Us</button>
            <button type="button" class="tab-button" data-tab="winterization">Winterization</button>
            <button type="button" class="tab-button" data-tab="policies">Policies</button>
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
                        'services' => 'Main Services Grid Section',
                        'why_choose_us' => 'Why Choose Our Service Department Section',
                        'winterization' => 'Winterization & Service Packages Section',
                        'policies' => 'Service Policies & Requirements Section'
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
                                <div>
                                    <strong><?php echo esc_html($section_labels[$section_id]); ?></strong>
                                    <p class="description" style="margin: 2px 0 0 0; font-size: 12px;">
                                        <?php
                                        switch ($section_id) {
                                            case 'services':
                                                echo 'The main grid displaying all available services with search and filtering options.';
                                                break;
                                            case 'why_choose_us':
                                                echo 'Highlights our expertise, certifications, and service guarantees.';
                                                break;
                                            case 'winterization':
                                                echo 'Displays winterization service packages and seasonal maintenance options.';
                                                break;
                                            case 'policies':
                                                echo 'Lists important service policies, requirements, and warranty information.';
                                                break;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </label>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>

            <div class="meta-box-section">
                <h3>Grid Layout Settings</h3>
                <p>
                    <label for="grid_columns">Number of Columns:</label><br>
                    <select id="grid_columns" name="grid_columns" class="widefat">
                        <option value="grid-cols-1 md:grid-cols-1 lg:grid-cols-2" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-1 lg:grid-cols-2'); ?>>2 Columns (Large)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-3" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?>>3 Columns (Default)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-4" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'); ?>>4 Columns</option>
                    </select>
                </p>
                <p>
                    <label for="services_per_page">Services Per Page:</label><br>
                    <input type="number" id="services_per_page" name="services_per_page" 
                           value="<?php echo esc_attr($meta['services_per_page']); ?>" 
                           class="small-text" min="1" max="100">
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_search" value="1" 
                               <?php checked($meta['show_search'], '1'); ?>>
                        Show Search Bar
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_filters" value="1" 
                               <?php checked($meta['show_filters'], '1'); ?>>
                        Show Location Filter
                    </label>
                </p>
            </div>
        </div>

        <!-- Header Tab -->
        <div class="tab-content" data-tab="header">
            <div class="meta-box-section">
                <h3>Page Header Options</h3>
                <div class="wades-meta-field">
                    <label for="custom_header_title"><strong>Header Title</strong></label>
                    <input type="text" id="custom_header_title" name="custom_header_title" 
                           value="<?php echo esc_attr($meta['custom_header_title']); ?>" 
                           class="widefat" placeholder="Our Services">
                    <p class="description">Override the default page title in the header section.</p>
                </div>

                <div class="wades-meta-field">
                    <label for="custom_header_subtext"><strong>Header Description</strong></label>
                    <textarea id="custom_header_subtext" name="custom_header_subtext" 
                            class="widefat" rows="3" 
                            placeholder="Expert marine services and maintenance for your boat."><?php echo esc_textarea($meta['custom_header_subtext']); ?></textarea>
                    <p class="description">Add a subtitle or description that appears below the main title.</p>
                </div>

                <div class="wades-meta-field">
                    <label><strong>Background Image</strong></label>
                    <input type="hidden" name="hero_background_image" id="hero_background_image" 
                           value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                    <div class="button-group">
                        <button type="button" class="button upload-image" id="upload_hero_image">Select Image</button>
                        <button type="button" class="button remove-image" <?php echo empty($meta['hero_background_image']) ? 'style="display:none;"' : ''; ?>>Remove Image</button>
                    </div>
                    <div id="hero_image_preview" class="image-preview">
                        <?php 
                        if (!empty($meta['hero_background_image'])) {
                            echo wp_get_attachment_image($meta['hero_background_image'], 'medium');
                        }
                        ?>
                    </div>
                    <p class="description">Recommended size: 1920x1080px or larger</p>
                </div>

                <div class="wades-meta-field">
                    <label for="hero_overlay_opacity"><strong>Overlay Opacity (%)</strong></label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>"
                           class="small-text" min="0" max="100" step="5">
                    <p class="description">Adjust the darkness of the overlay on the background image</p>
                </div>

                <div class="wades-meta-field">
                    <label for="hero_height"><strong>Header Height (vh)</strong></label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>"
                           class="small-text" min="30" max="100" step="5">
                    <p class="description">Set the height of the header (70 = 70% of viewport height)</p>
                </div>
            </div>
        </div>

        <!-- Services Grid Tab -->
        <div class="tab-content" data-tab="services">
            <div class="meta-box-section">
                <h3>Services Grid Content</h3>
                <p>
                    <label for="services_title">Section Title:</label>
                    <input type="text" id="services_title" name="services_title" 
                           value="<?php echo esc_attr($meta['services_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="services_description">Section Description:</label>
                    <textarea id="services_description" name="services_description" rows="3" 
                              class="widefat"><?php echo esc_textarea($meta['services_description']); ?></textarea>
                </p>
            </div>
        </div>

        <!-- Why Choose Us Tab -->
        <div class="tab-content" data-tab="why-choose">
            <div class="meta-box-section">
                <h3>Why Choose Us Content</h3>
                <div class="reasons-list">
                    <?php foreach ($meta['why_choose_us'] as $index => $reason) : ?>
                        <p>
                            <input type="text" name="why_choose_us[]" 
                                   value="<?php echo esc_attr($reason); ?>" class="widefat">
                            <button type="button" class="button remove-reason">Remove</button>
                        </p>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-reason">Add Reason</button>
                <p>
                    <label>Featured Image:</label><br>
                    <input type="hidden" name="service_image" 
                           value="<?php echo esc_attr($meta['service_image']); ?>" class="widefat">
                    <div class="button-group">
                        <button type="button" class="button upload-image">Select Image</button>
                        <button type="button" class="button remove-image">Remove Image</button>
                    </div>
                    <div class="image-preview">
                        <?php if ($meta['service_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['service_image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>
        </div>

        <!-- Winterization Tab -->
        <div class="tab-content" data-tab="winterization">
            <div class="meta-box-section">
                <h3>Winterization Packages</h3>
                <div class="packages-list">
                    <?php foreach ($meta['winterization_packages'] as $index => $package) : ?>
                        <div class="package-item card">
                            <div class="card-header">
                                <h4>Package <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-package">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Title:</label>
                                    <input type="text" name="winterization_packages[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($package['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label>
                                    <textarea name="winterization_packages[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($package['description']); ?></textarea>
                                </p>
                                <div class="package-services">
                                    <label>Services:</label>
                                    <?php foreach ($package['services'] as $service_index => $service) : ?>
                                        <p>
                                            <input type="text" name="winterization_packages[<?php echo $index; ?>][services][]" 
                                                   value="<?php echo esc_attr($service); ?>" class="widefat">
                                            <button type="button" class="button remove-package-service">Remove</button>
                                        </p>
                                    <?php endforeach; ?>
                                    <button type="button" class="button add-package-service">Add Service</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-package">Add Package</button>
            </div>
        </div>

        <!-- Policies Tab -->
        <div class="tab-content" data-tab="policies">
            <div class="meta-box-section">
                <h3>Service Policies</h3>
                <div class="policies-list">
                    <?php foreach ($meta['service_policies'] as $index => $policy) : ?>
                        <p>
                            <input type="text" name="service_policies[]" 
                                   value="<?php echo esc_attr($policy); ?>" class="widefat">
                            <button type="button" class="button remove-policy">Remove</button>
                        </p>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-policy">Add Policy</button>
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

        // Initialize image uploads
        $('.upload-image').each(function() {
            var container = $(this).closest('.meta-box-section');
            initImageUpload(
                $(this),
                container.find('input[type="hidden"]'),
                container.find('.image-preview')
            );
        });

        // Remove image functionality
        $('.remove-image').on('click', function() {
            var container = $(this).closest('.meta-box-section');
            container.find('input[type="hidden"]').val('');
            container.find('.image-preview').empty();
        });

        // Dynamic list functionality
        function setupDynamicList(addButton, container, template) {
            $(addButton).on('click', function() {
                var index = container.children().length;
                container.append(template.replace(/\{index\}/g, index));
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.dynamic-item').remove();
            });
        }

        // Setup dynamic lists
        setupDynamicList('.add-reason', $('.reasons-list'), 
            '<p><input type="text" name="why_choose_us[]" class="widefat"><button type="button" class="button remove-reason">Remove</button></p>');
        
        setupDynamicList('.add-policy', $('.policies-list'), 
            '<p><input type="text" name="service_policies[]" class="widefat"><button type="button" class="button remove-policy">Remove</button></p>');
    });
    </script>
    <?php
}

/**
 * Save Services Meta Box Data
 */
function wades_save_services_meta($post_id) {
    // Verify nonce
    if (!isset($_POST['wades_services_meta_nonce']) || !wp_verify_nonce($_POST['wades_services_meta_nonce'], 'wades_services_meta')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save meta fields
    $fields = array(
        'services_title',
        'services_description',
        'services_grid',
        'why_choose_us',
        'service_image',
        'winterization_packages',
        'service_policies',
        'grid_columns',
        'services_per_page',
        'show_search',
        'show_filters',
        'section_order',
        'sections_visibility',
        'hero_background_image',
        'hero_overlay_opacity',
        'hero_height',
        'custom_header_title',
        'custom_header_subtext'
    );

    foreach ($fields as $field) {
        $value = isset($_POST[$field]) ? $_POST[$field] : '';
        
        // Handle array values
        if (is_array($value)) {
            update_post_meta($post_id, '_' . $field, $value);
        } else {
            // Sanitize and save string values
            update_post_meta($post_id, '_' . $field, sanitize_text_field($value));
        }
    }
}
add_action('save_post', 'wades_save_services_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_services_admin_scripts($hook) {
    global $post;
    
    // Only load on post.php or post-new.php
    if (!in_array($hook, array('post.php', 'post-new.php'))) {
        return;
    }

    // Only load for pages using the services template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        if ($template === 'templates/services.php') {
            // Remove any conflicting scripts
            wp_dequeue_script('services-admin');
            wp_dequeue_script('wades-meta-boxes');
            
            // Enqueue WordPress media scripts
            wp_enqueue_media();
            
            // Enqueue jQuery UI for sortable functionality
            wp_enqueue_script('jquery-ui-sortable');
            
            // Enqueue your custom admin script
            wp_enqueue_script(
                'services-admin',
                get_template_directory_uri() . '/assets/js/services-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                filemtime(get_template_directory() . '/assets/js/services-admin.js'),
                true
            );

            // Add custom admin styles
            wp_add_inline_style('wp-admin', '
                .meta-box-tabs {
                    margin-bottom: 20px;
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 0;
                }
                .tab-button {
                    padding: 10px 20px;
                    margin-right: 5px;
                    border: none;
                    background: none;
                    cursor: pointer;
                }
                .tab-button.active {
                    border-bottom: 2px solid #2271b1;
                    font-weight: bold;
                }
                .tab-content {
                    display: none;
                }
                .tab-content.active {
                    display: block;
                }
                .meta-box-section {
                    margin-bottom: 30px;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 4px;
                }
            ');
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_services_admin_scripts', 100);

function wades_render_services_meta_box($post) {
    // Get the current template
    $template = get_post_meta($post->ID, '_wp_page_template', true);
    
    // Get template defaults from shared function
    $defaults = wades_get_template_defaults($template);
    
    // Get existing values
    $meta = array(
        'services_title' => get_post_meta($post->ID, 'services_title', true),
        'services_description' => get_post_meta($post->ID, 'services_description', true),
        'grid_columns' => get_post_meta($post->ID, 'grid_columns', true),
        'services_per_page' => get_post_meta($post->ID, 'services_per_page', true),
        'show_search' => get_post_meta($post->ID, 'show_search', true),
        'show_filters' => get_post_meta($post->ID, 'show_filters', true),
        'section_order' => get_post_meta($post->ID, 'section_order', true),
        'sections_visibility' => get_post_meta($post->ID, 'sections_visibility', true)
    );

    // Set defaults if values are empty
    foreach ($meta as $key => $value) {
        if (empty($value) && isset($defaults[$key])) {
            $meta[$key] = $defaults[$key];
            update_post_meta($post->ID, $key, $defaults[$key]);
        }
    }

    // Render the meta box fields
    ?>
    <div class="wades-meta-box">
        <!-- Services Settings -->
        <div class="meta-box-section">
            <h3>Services Page Settings</h3>
            
            <div class="field-group">
                <label for="services_title">Services Section Title</label>
                <input type="text" id="services_title" name="services_title" value="<?php echo esc_attr($meta['services_title']); ?>" />
            </div>
            
            <div class="field-group">
                <label for="services_description">Services Section Description</label>
                <textarea id="services_description" name="services_description"><?php echo esc_textarea($meta['services_description']); ?></textarea>
            </div>
            
            <div class="field-group">
                <label for="grid_columns">Grid Layout</label>
                <select id="grid_columns" name="grid_columns">
                    <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-3" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?>>3 Columns</option>
                    <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-4" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'); ?>>4 Columns</option>
                </select>
            </div>
            
            <div class="field-group">
                <label for="services_per_page">Services Per Page</label>
                <input type="number" id="services_per_page" name="services_per_page" value="<?php echo esc_attr($meta['services_per_page']); ?>" />
            </div>
            
            <div class="field-group">
                <label><input type="checkbox" name="show_search" value="1" <?php checked($meta['show_search'], '1'); ?>> Show Search Bar</label>
            </div>
            
            <div class="field-group">
                <label><input type="checkbox" name="show_filters" value="1" <?php checked($meta['show_filters'], '1'); ?>> Show Filters</label>
            </div>
        </div>

        <!-- Section Visibility -->
        <div class="meta-box-section">
            <h3>Section Visibility</h3>
            <?php
            $sections = array(
                'services' => 'Services Grid',
                'why_choose_us' => 'Why Choose Us',
                'winterization' => 'Winterization',
                'policies' => 'Policies'
            );
            
            foreach ($sections as $section_key => $section_label) {
                $visibility = isset($meta['sections_visibility'][$section_key]) ? $meta['sections_visibility'][$section_key] : '1';
                ?>
                <div class="field-group">
                    <label>
                        <input type="checkbox" 
                               name="sections_visibility[<?php echo esc_attr($section_key); ?>]" 
                               value="1" 
                               <?php checked($visibility, '1'); ?>>
                        <?php echo esc_html($section_label); ?>
                    </label>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Section Order -->
        <div class="meta-box-section">
            <h3>Section Order</h3>
            <div class="field-group">
                <input type="hidden" name="section_order" id="section_order" value="<?php echo esc_attr($meta['section_order']); ?>">
                <div id="sortable-sections" class="sortable-list">
                    <?php
                    $order = !empty($meta['section_order']) ? explode(',', $meta['section_order']) : array_keys($sections);
                    foreach ($order as $section_key) {
                        if (isset($sections[$section_key])) {
                            echo '<div class="sortable-item" data-section="' . esc_attr($section_key) . '">';
                            echo '<span class="dashicons dashicons-menu"></span> ';
                            echo esc_html($sections[$section_key]);
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .wades-meta-box {
            padding: 12px;
        }
        .meta-box-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
        }
        .meta-box-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        .field-group {
            margin-bottom: 15px;
        }
        .field-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .field-group input[type="text"],
        .field-group input[type="number"],
        .field-group select,
        .field-group textarea {
            width: 100%;
            max-width: 400px;
        }
        .field-group textarea {
            height: 100px;
        }
        .sortable-list {
            border: 1px solid #ddd;
            background: #fff;
            padding: 10px;
            max-width: 400px;
        }
        .sortable-item {
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            cursor: move;
        }
        .sortable-item:last-child {
            margin-bottom: 0;
        }
        .dashicons-menu {
            color: #666;
            margin-right: 5px;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $("#sortable-sections").sortable({
            handle: ".dashicons-menu",
            update: function(event, ui) {
                var order = [];
                $(".sortable-item").each(function() {
                    order.push($(this).data("section"));
                });
                $("#section_order").val(order.join(","));
            }
        });
    });
    </script>
    <?php
} 