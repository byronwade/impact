<?php
/**
 * Block Template Loader
 */

function wades_get_block_template($template_slug) {
    $template_path = get_template_directory() . '/templates/blocks/' . $template_slug . '.php';
    if (file_exists($template_path)) {
        return include $template_path;
    }
    return false;
}

function wades_register_template_post_meta() {
    register_post_meta('page', '_template_settings', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'object',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'wades_register_template_post_meta'); 