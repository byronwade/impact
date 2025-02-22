<?php
/**
 * Custom Meta Boxes for Wades Theme
 *
 * @package wades
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register meta boxes based on template
 */
function wades_register_template_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current post ID and template
    $post_id = get_the_ID();
    $template = get_page_template_slug($post_id);

    // Register meta boxes based on template
    switch ($template) {
        case 'templates/home.php':
            require_once get_template_directory() . '/inc/meta-boxes/home-meta.php';
            break;

        case 'templates/about.php':
            require_once get_template_directory() . '/inc/meta-boxes/about-meta.php';
            break;

        case 'templates/services.php':
            require_once get_template_directory() . '/inc/meta-boxes/services-meta.php';
            break;

        case 'templates/blog.php':
            require_once get_template_directory() . '/inc/meta-boxes/blog-meta.php';
            break;

        case 'templates/contact.php':
            require_once get_template_directory() . '/inc/meta-boxes/contact-meta.php';
            break;

        case 'templates/boats.php':
            require_once get_template_directory() . '/inc/meta-boxes/boats-meta.php';
            break;

        default:
            // For all pages, load shared meta boxes
            require_once get_template_directory() . '/inc/meta-boxes/shared-meta.php';
            break;
    }
}
add_action('add_meta_boxes', 'wades_register_template_meta_boxes', 10);

/**
 * Load meta box styles and scripts
 */
function wades_load_meta_box_assets($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'))) {
        return;
    }

    wp_enqueue_style(
        'wades-meta-box-styles',
        get_template_directory_uri() . '/inc/meta-boxes/meta-box-styles.css',
        array(),
        _S_VERSION
    );

    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-sortable');
}
add_action('admin_enqueue_scripts', 'wades_load_meta_box_assets');

/**
 * Register meta boxes
 */
function wades_register_meta_boxes() {
    add_meta_box(
        'wades_home_hero_meta',
        'Hero Section Settings',
        'wades_home_hero_meta_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_register_meta_boxes');

/**
 * Hero section meta box callback
 */
function wades_home_hero_meta_callback($post) {
    // Add nonce for security
    wp_nonce_field('wades_home_meta_box', 'wades_home_meta_box_nonce');

    // Get current values
    $hero_meta = array(
        'backgroundVideo' => get_post_meta($post->ID, '_hero_background_video', true),
        'backgroundImage' => get_post_meta($post->ID, '_hero_background_image', true),
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

    // Get all pages for dropdown
    $pages = get_pages();
    ?>
    <div class="wades-meta-box">
        <h3>Primary Call to Action</h3>
        <p>
            <label for="hero_primary_cta_label">Button Label:</label><br>
            <input type="text" id="hero_primary_cta_label" name="hero_primary_cta_label" 
                   value="<?php echo esc_attr($hero_meta['primaryCta']['label']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_primary_cta_page">Link to Page:</label><br>
            <select id="hero_primary_cta_page" name="hero_primary_cta_page" class="widefat">
                <option value="">Select a page</option>
                <?php foreach ($pages as $page) : ?>
                    <option value="<?php echo $page->ID; ?>" <?php selected($hero_meta['primaryCta']['page_id'], $page->ID); ?>>
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="hero_primary_cta_icon">Icon:</label><br>
            <input type="text" id="hero_primary_cta_icon" name="hero_primary_cta_icon" 
                   value="<?php echo esc_attr($hero_meta['primaryCta']['icon']); ?>" class="widefat">
        </p>

        <h3>Secondary Call to Action</h3>
        <p>
            <label for="hero_secondary_cta_label">Button Label:</label><br>
            <input type="text" id="hero_secondary_cta_label" name="hero_secondary_cta_label" 
                   value="<?php echo esc_attr($hero_meta['secondaryCta']['label']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_secondary_cta_page">Link to Page:</label><br>
            <select id="hero_secondary_cta_page" name="hero_secondary_cta_page" class="widefat">
                <option value="">Select a page</option>
                <?php foreach ($pages as $page) : ?>
                    <option value="<?php echo $page->ID; ?>" <?php selected($hero_meta['secondaryCta']['page_id'], $page->ID); ?>>
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="hero_secondary_cta_icon">Icon:</label><br>
            <input type="text" id="hero_secondary_cta_icon" name="hero_secondary_cta_icon" 
                   value="<?php echo esc_attr($hero_meta['secondaryCta']['icon']); ?>" class="widefat">
        </p>

        <!-- Other hero settings -->
        <h3>Other Settings</h3>
        <p>
            <label for="hero_title">Hero Title:</label><br>
            <input type="text" id="hero_title" name="hero_title" 
                   value="<?php echo esc_attr($hero_meta['title']); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_description">Hero Description:</label><br>
            <textarea id="hero_description" name="hero_description" class="widefat" rows="3"><?php echo esc_textarea($hero_meta['description']); ?></textarea>
        </p>
        <p>
            <label for="hero_phone_number">Phone Number:</label><br>
            <input type="text" id="hero_phone_number" name="hero_phone_number" 
                   value="<?php echo esc_attr($hero_meta['phoneNumber']); ?>" class="widefat">
        </p>
    </div>
    <?php
}

/**
 * Save meta box data
 */
function wades_save_meta_box_data($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['wades_home_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['wades_home_meta_box_nonce'], 'wades_home_meta_box')) {
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

    // Save primary CTA data
    if (isset($_POST['hero_primary_cta_label'])) {
        update_post_meta($post_id, '_hero_primary_cta_label', sanitize_text_field($_POST['hero_primary_cta_label']));
    }
    if (isset($_POST['hero_primary_cta_page'])) {
        update_post_meta($post_id, '_hero_primary_cta_page', absint($_POST['hero_primary_cta_page']));
    }
    if (isset($_POST['hero_primary_cta_icon'])) {
        update_post_meta($post_id, '_hero_primary_cta_icon', sanitize_text_field($_POST['hero_primary_cta_icon']));
    }

    // Save secondary CTA data
    if (isset($_POST['hero_secondary_cta_label'])) {
        update_post_meta($post_id, '_hero_secondary_cta_label', sanitize_text_field($_POST['hero_secondary_cta_label']));
    }
    if (isset($_POST['hero_secondary_cta_page'])) {
        update_post_meta($post_id, '_hero_secondary_cta_page', absint($_POST['hero_secondary_cta_page']));
    }
    if (isset($_POST['hero_secondary_cta_icon'])) {
        update_post_meta($post_id, '_hero_secondary_cta_icon', sanitize_text_field($_POST['hero_secondary_cta_icon']));
    }

    // Save other hero settings
    if (isset($_POST['hero_title'])) {
        update_post_meta($post_id, '_hero_title', sanitize_text_field($_POST['hero_title']));
    }
    if (isset($_POST['hero_description'])) {
        update_post_meta($post_id, '_hero_description', sanitize_textarea_field($_POST['hero_description']));
    }
    if (isset($_POST['hero_phone_number'])) {
        update_post_meta($post_id, '_hero_phone_number', sanitize_text_field($_POST['hero_phone_number']));
    }
}
add_action('save_post', 'wades_save_meta_box_data'); 