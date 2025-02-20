<?php
/**
 * Shared Meta Box Callbacks
 * 
 * @package wades
 */

if (!function_exists('wades_hero_section_callback')) {
    function wades_hero_section_callback($post) {
        wp_nonce_field('wades_hero_meta_box', 'wades_hero_meta_box_nonce');

        $hero_title = get_post_meta($post->ID, '_hero_title', true) ?: get_the_title();
        $hero_description = get_post_meta($post->ID, '_hero_description', true) ?: get_the_excerpt();
        ?>
        <p>
            <label for="hero_title">Hero Title:</label><br>
            <input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($hero_title); ?>" class="widefat">
        </p>
        <p>
            <label for="hero_description">Hero Description:</label><br>
            <textarea id="hero_description" name="hero_description" rows="3" class="widefat"><?php echo esc_textarea($hero_description); ?></textarea>
        </p>
        <?php
    }
}

if (!function_exists('wades_cta_section_callback')) {
    function wades_cta_section_callback($post) {
        $cta_text = get_post_meta($post->ID, '_cta_text', true);
        $cta_link = get_post_meta($post->ID, '_cta_link', true);
        ?>
        <p>
            <label for="cta_text">CTA Button Text:</label><br>
            <input type="text" id="cta_text" name="cta_text" value="<?php echo esc_attr($cta_text); ?>" class="widefat">
        </p>
        <p>
            <label for="cta_link">CTA Button Link:</label><br>
            <input type="text" id="cta_link" name="cta_link" value="<?php echo esc_attr($cta_link); ?>" class="widefat">
        </p>
        <?php
    }
}

if (!function_exists('wades_save_shared_meta')) {
    function wades_save_shared_meta($post_id) {
        // Save Hero Section
        if (isset($_POST['hero_title'])) {
            update_post_meta($post_id, '_hero_title', sanitize_text_field($_POST['hero_title']));
        }
        if (isset($_POST['hero_description'])) {
            update_post_meta($post_id, '_hero_description', wp_kses_post($_POST['hero_description']));
        }

        // Save CTA Section
        if (isset($_POST['cta_text'])) {
            update_post_meta($post_id, '_cta_text', sanitize_text_field($_POST['cta_text']));
        }
        if (isset($_POST['cta_link'])) {
            update_post_meta($post_id, '_cta_link', esc_url_raw($_POST['cta_link']));
        }
    }
} 