<?php
/**
 * Meta Boxes for Services Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Services template
 */
function wades_services_meta_boxes() {
    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = get_page_template_slug(get_the_ID());
    
    if ($template === 'templates/services.php') {
        add_meta_box(
            'services_content',
            'Services Page Content',
            'wades_services_content_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'wades_services_meta_boxes');

/**
 * Services Content Meta Box Callback
 */
function wades_services_content_callback($post) {
    wp_nonce_field('wades_services_meta', 'wades_services_meta_nonce');

    $meta = array(
        'services_title' => get_post_meta($post->ID, '_services_title', true),
        'services_description' => get_post_meta($post->ID, '_services_description', true),
        'services_grid' => get_post_meta($post->ID, '_services_grid', true),
        'why_choose_us' => get_post_meta($post->ID, '_why_choose_us', true),
        'service_image' => get_post_meta($post->ID, '_service_image', true),
        'winterization_packages' => get_post_meta($post->ID, '_winterization_packages', true),
        'service_policies' => get_post_meta($post->ID, '_service_policies', true)
    );
    ?>
    <div class="services-meta-box">
        <!-- Hero Section -->
        <div class="meta-box-section">
            <h3>Hero Section</h3>
            <p>
                <label for="services_title">Page Title:</label><br>
                <input type="text" id="services_title" name="services_title" value="<?php echo esc_attr($meta['services_title']); ?>" class="widefat">
            </p>
            <p>
                <label for="services_description">Page Description:</label><br>
                <textarea id="services_description" name="services_description" rows="3" class="widefat"><?php echo esc_textarea($meta['services_description']); ?></textarea>
            </p>
        </div>

        <!-- Services Grid -->
        <div class="meta-box-section">
            <h3>Services Grid</h3>
            <div class="services-grid">
                <?php
                $services = $meta['services_grid'] ?: array();
                if (!empty($services)) :
                    foreach ($services as $index => $service) :
                ?>
                    <div class="service-item card">
                        <div class="card-header">
                            <h4>Service <?php echo $index + 1; ?></h4>
                            <button type="button" class="button remove-service">Remove</button>
                        </div>
                        <div class="card-body">
                            <p>
                                <label>Icon (Lucide icon name):</label><br>
                                <input type="text" name="services_grid[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($service['icon']); ?>" class="widefat">
                            </p>
                            <p>
                                <label>Title:</label><br>
                                <input type="text" name="services_grid[<?php echo $index; ?>][title]" value="<?php echo esc_attr($service['title']); ?>" class="widefat">
                            </p>
                            <p>
                                <label>Description:</label><br>
                                <textarea name="services_grid[<?php echo $index; ?>][description]" rows="3" class="widefat"><?php echo esc_textarea($service['description']); ?></textarea>
                            </p>
                        </div>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <button type="button" class="button add-service">Add Service</button>
        </div>

        <!-- Why Choose Us -->
        <div class="meta-box-section">
            <h3>Why Choose Us Section</h3>
            <div class="reasons-list">
                <?php
                $reasons = $meta['why_choose_us'] ?: array();
                if (!empty($reasons)) :
                    foreach ($reasons as $index => $reason) :
                ?>
                    <p>
                        <input type="text" name="why_choose_us[]" value="<?php echo esc_attr($reason); ?>" class="widefat">
                        <button type="button" class="button remove-reason">Remove</button>
                    </p>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <button type="button" class="button add-reason">Add Reason</button>
            <p>
                <label>Service Department Image:</label><br>
                <input type="hidden" name="service_image" value="<?php echo esc_attr($meta['service_image']); ?>" class="widefat">
                <button type="button" class="button upload-image">Upload Image</button>
                <div class="image-preview">
                    <?php if ($meta['service_image']) : ?>
                        <?php echo wp_get_attachment_image($meta['service_image'], 'thumbnail'); ?>
                    <?php endif; ?>
                </div>
            </p>
        </div>

        <!-- Winterization Packages -->
        <div class="meta-box-section">
            <h3>Winterization Packages</h3>
            <div class="packages-list">
                <?php
                $packages = $meta['winterization_packages'] ?: array();
                if (!empty($packages)) :
                    foreach ($packages as $index => $package) :
                ?>
                    <div class="package-item card">
                        <div class="card-header">
                            <h4>Package <?php echo $index + 1; ?></h4>
                            <button type="button" class="button remove-package">Remove</button>
                        </div>
                        <div class="card-body">
                            <p>
                                <label>Title:</label><br>
                                <input type="text" name="winterization_packages[<?php echo $index; ?>][title]" value="<?php echo esc_attr($package['title']); ?>" class="widefat">
                            </p>
                            <p>
                                <label>Description:</label><br>
                                <textarea name="winterization_packages[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($package['description']); ?></textarea>
                            </p>
                            <div class="package-services">
                                <label>Services:</label>
                                <?php if (!empty($package['services'])) : foreach ($package['services'] as $service_index => $service) : ?>
                                    <p>
                                        <input type="text" name="winterization_packages[<?php echo $index; ?>][services][]" value="<?php echo esc_attr($service); ?>" class="widefat">
                                        <button type="button" class="button remove-package-service">Remove</button>
                                    </p>
                                <?php endforeach; endif; ?>
                                <button type="button" class="button add-package-service">Add Service</button>
                            </div>
                            <p>
                                <label>Price:</label><br>
                                <input type="text" name="winterization_packages[<?php echo $index; ?>][price]" value="<?php echo esc_attr($package['price']); ?>" class="widefat">
                            </p>
                            <p>
                                <label>Note:</label><br>
                                <input type="text" name="winterization_packages[<?php echo $index; ?>][note]" value="<?php echo esc_attr($package['note']); ?>" class="widefat">
                            </p>
                        </div>
                    </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <button type="button" class="button add-package">Add Package</button>
        </div>

        <!-- Service Policies -->
        <div class="meta-box-section">
            <h3>Service Policies</h3>
            <div class="policies-list">
                <?php
                $policies = $meta['service_policies'] ?: array();
                if (!empty($policies)) :
                    foreach ($policies as $index => $policy) :
                ?>
                    <p>
                        <input type="text" name="service_policies[]" value="<?php echo esc_attr($policy); ?>" class="widefat">
                        <button type="button" class="button remove-policy">Remove</button>
                    </p>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <button type="button" class="button add-policy">Add Policy</button>
        </div>
    </div>

    <style>
        .meta-box-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .meta-box-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .card {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            background: #fff;
        }
        .card-header {
            padding: 10px;
            background: #f5f5f5;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-body {
            padding: 10px;
        }
        .image-preview {
            margin-top: 10px;
        }
        .image-preview img {
            max-width: 150px;
            height: auto;
        }
    </style>
    <?php
}

/**
 * Save Services Meta Box Data
 */
function wades_save_services_meta($post_id) {
    if (!isset($_POST['wades_services_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_services_meta_nonce'], 'wades_services_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save hero section
    if (isset($_POST['services_title'])) {
        update_post_meta($post_id, '_services_title', sanitize_text_field($_POST['services_title']));
    }
    if (isset($_POST['services_description'])) {
        update_post_meta($post_id, '_services_description', wp_kses_post($_POST['services_description']));
    }

    // Save services grid
    if (isset($_POST['services_grid'])) {
        $services = array();
        foreach ($_POST['services_grid'] as $service) {
            if (!empty($service['title'])) {
                $services[] = array(
                    'icon' => sanitize_text_field($service['icon']),
                    'title' => sanitize_text_field($service['title']),
                    'description' => wp_kses_post($service['description'])
                );
            }
        }
        update_post_meta($post_id, '_services_grid', $services);
    }

    // Save why choose us
    if (isset($_POST['why_choose_us'])) {
        $reasons = array_map('sanitize_text_field', array_filter($_POST['why_choose_us']));
        update_post_meta($post_id, '_why_choose_us', $reasons);
    }

    // Save service image
    if (isset($_POST['service_image'])) {
        update_post_meta($post_id, '_service_image', absint($_POST['service_image']));
    }

    // Save winterization packages
    if (isset($_POST['winterization_packages'])) {
        $packages = array();
        foreach ($_POST['winterization_packages'] as $package) {
            if (!empty($package['title'])) {
                $packages[] = array(
                    'title' => sanitize_text_field($package['title']),
                    'description' => wp_kses_post($package['description']),
                    'services' => isset($package['services']) ? array_map('sanitize_text_field', array_filter($package['services'])) : array(),
                    'price' => sanitize_text_field($package['price']),
                    'note' => sanitize_text_field($package['note'])
                );
            }
        }
        update_post_meta($post_id, '_winterization_packages', $packages);
    }

    // Save service policies
    if (isset($_POST['service_policies'])) {
        $policies = array_map('sanitize_text_field', array_filter($_POST['service_policies']));
        update_post_meta($post_id, '_service_policies', $policies);
    }
}
add_action('save_post', 'wades_save_services_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_services_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (is_object($post) && get_page_template_slug($post->ID) == 'templates/services.php') {
            wp_enqueue_media();
            wp_enqueue_script(
                'services-admin',
                get_template_directory_uri() . '/assets/js/services-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                _S_VERSION,
                true
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_services_admin_scripts'); 