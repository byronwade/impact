<?php
/**
 * Theme Settings
 *
 * @package wades
 */

// Add the theme settings page to the admin menu
function wades_add_theme_settings_page() {
    add_menu_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'wades-settings',
        'wades_theme_settings_page',
        'dashicons-admin-generic',
        60
    );
}
add_action('admin_menu', 'wades_add_theme_settings_page');

// Register settings
function wades_register_settings() {
    register_setting('wades_settings', 'wades_company_phone');
    register_setting('wades_settings', 'wades_company_email');
    register_setting('wades_settings', 'wades_company_address');
    register_setting('wades_settings', 'wades_social_facebook');
    register_setting('wades_settings', 'wades_social_instagram');
    register_setting('wades_settings', 'wades_social_youtube');
    register_setting('wades_settings', 'wades_business_hours');
    register_setting('wades_settings', 'wades_company_logo');
    
    // Instagram API Settings
    register_setting('wades_settings', 'wades_instagram_access_token');
    register_setting('wades_settings', 'wades_instagram_user_id');
    register_setting('wades_settings', 'wades_instagram_cache_time', array(
        'default' => 3600 // 1 hour default cache time
    ));
}
add_action('admin_init', 'wades_register_settings');

// Create the settings page HTML
function wades_theme_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('wades_messages', 'wades_message', 'Settings Saved', 'updated');
    }

    settings_errors('wades_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wades_settings');
            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Company Phone</th>
                    <td>
                        <input type="tel" name="wades_company_phone" value="<?php echo esc_attr(get_option('wades_company_phone')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Company Email</th>
                    <td>
                        <input type="email" name="wades_company_email" value="<?php echo esc_attr(get_option('wades_company_email')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Company Address</th>
                    <td>
                        <textarea name="wades_company_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('wades_company_address')); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Facebook URL</th>
                    <td>
                        <input type="url" name="wades_social_facebook" value="<?php echo esc_attr(get_option('wades_social_facebook')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Instagram URL</th>
                    <td>
                        <input type="url" name="wades_social_instagram" value="<?php echo esc_attr(get_option('wades_social_instagram')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">YouTube URL</th>
                    <td>
                        <input type="url" name="wades_social_youtube" value="<?php echo esc_attr(get_option('wades_social_youtube')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Business Hours</th>
                    <td>
                        <textarea name="wades_business_hours" rows="5" class="large-text"><?php echo esc_textarea(get_option('wades_business_hours')); ?></textarea>
                    </td>
                </tr>

                <!-- Instagram API Settings -->
                <tr>
                    <th scope="row" colspan="2">
                        <h2 class="title">Instagram Feed Settings</h2>
                    </th>
                </tr>
                <tr>
                    <th scope="row">Instagram Access Token</th>
                    <td>
                        <input type="text" name="wades_instagram_access_token" value="<?php echo esc_attr(get_option('wades_instagram_access_token')); ?>" class="regular-text">
                        <p class="description">Enter your Instagram Graph API Access Token. <a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">Learn how to get it</a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Instagram User ID</th>
                    <td>
                        <input type="text" name="wades_instagram_user_id" value="<?php echo esc_attr(get_option('wades_instagram_user_id')); ?>" class="regular-text">
                        <p class="description">Your Instagram Business Account ID</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Cache Duration (seconds)</th>
                    <td>
                        <input type="number" name="wades_instagram_cache_time" value="<?php echo esc_attr(get_option('wades_instagram_cache_time', 3600)); ?>" class="small-text">
                        <p class="description">How long to cache Instagram feed (default: 3600 seconds / 1 hour)</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Helper function to get theme settings
function wades_get_setting($key) {
    return get_option('wades_' . $key);
} 