<?php
/**
 * Services Page Meta Boxes
 */

function wades_services_meta_boxes() {
    add_meta_box(
        'wades_services_hero',
        'Services Hero Section',
        'wades_services_hero_callback',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'wades_services_content',
        'Services Content',
        'wades_services_content_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_services_meta_boxes');

function wades_services_hero_callback($post) {
    wp_nonce_field('wades_services_meta', 'wades_services_meta_nonce');

    $hero_meta = array(
        'title' => get_post_meta($post->ID, '_services_hero_title', true),
        'subtitle' => get_post_meta($post->ID, '_services_hero_subtitle', true),
        'description' => get_post_meta($post->ID, '_services_hero_description', true)
    );
    ?>
    <div class="services-meta-box">
        <p>
            <label for="services_hero_title">Hero Title:</label>
            <input type="text" id="services_hero_title" name="services_hero_title" 
                   value="<?php echo esc_attr($hero_meta['title']); ?>" class="widefat">
        </p>
        <p>
            <label for="services_hero_subtitle">Hero Subtitle:</label>
            <input type="text" id="services_hero_subtitle" name="services_hero_subtitle" 
                   value="<?php echo esc_attr($hero_meta['subtitle']); ?>" class="widefat">
        </p>
        <p>
            <label for="services_hero_description">Hero Description:</label>
            <textarea id="services_hero_description" name="services_hero_description" 
                      class="widefat" rows="4"><?php echo esc_textarea($hero_meta['description']); ?></textarea>
        </p>
    </div>
    <?php
}

function wades_services_content_callback($post) {
    $services = get_post_meta($post->ID, '_services_list', true) ?: array();
    ?>
    <div class="services-list-container">
        <div class="services-list">
            <?php 
            if (!empty($services)) :
                foreach ($services as $index => $service) : ?>
                    <div class="service-item">
                        <p>
                            <label>Service Title:</label>
                            <input type="text" name="services[<?php echo $index; ?>][title]" 
                                   value="<?php echo esc_attr($service['title']); ?>" class="widefat">
                        </p>
                        <p>
                            <label>Service Description:</label>
                            <textarea name="services[<?php echo $index; ?>][description]" 
                                      class="widefat" rows="3"><?php echo esc_textarea($service['description']); ?></textarea>
                        </p>
                        <button type="button" class="button remove-service">Remove Service</button>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
        <button type="button" class="button add-service">Add Service</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.add-service').click(function() {
            var index = $('.service-item').length;
            var newService = `
                <div class="service-item">
                    <p>
                        <label>Service Title:</label>
                        <input type="text" name="services[${index}][title]" class="widefat">
                    </p>
                    <p>
                        <label>Service Description:</label>
                        <textarea name="services[${index}][description]" class="widefat" rows="3"></textarea>
                    </p>
                    <button type="button" class="button remove-service">Remove Service</button>
                </div>
            `;
            $('.services-list').append(newService);
        });

        $(document).on('click', '.remove-service', function() {
            $(this).closest('.service-item').remove();
        });
    });
    </script>
    <?php
}

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
    $hero_fields = array('services_hero_title', 'services_hero_subtitle', 'services_hero_description');
    foreach ($hero_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save services list
    if (isset($_POST['services'])) {
        $services = array();
        foreach ($_POST['services'] as $service) {
            $services[] = array(
                'title' => sanitize_text_field($service['title']),
                'description' => wp_kses_post($service['description'])
            );
        }
        update_post_meta($post_id, '_services_list', $services);
    }
}
add_action('save_post', 'wades_save_services_meta'); 