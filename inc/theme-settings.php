<?php
/**
 * Theme Settings
 *
 * @package wades
 */

// Register blog settings
function wades_register_blog_settings() {
    // Register settings group
    register_setting('wades_blog_settings_group', 'wades_blog_settings', array(
        'type' => 'array',
        'default' => array(
            'show_featured' => true,
            'show_categories' => true,
            'posts_per_page' => 9,
            'show_sidebar' => true
        ),
        'sanitize_callback' => 'wades_sanitize_blog_settings'
    ));

    // Initialize settings if they don't exist
    if (false === get_option('wades_blog_settings')) {
        add_option('wades_blog_settings', array(
            'show_featured' => true,
            'show_categories' => true,
            'posts_per_page' => 9,
            'show_sidebar' => true
        ));
    }
}
add_action('admin_init', 'wades_register_blog_settings');

// Sanitize blog settings
function wades_sanitize_blog_settings($input) {
    $defaults = array(
        'show_featured' => true,
        'show_categories' => true,
        'posts_per_page' => 9,
        'show_sidebar' => true
    );

    $output = wp_parse_args($input, $defaults);

    $output['show_featured'] = (bool) $output['show_featured'];
    $output['show_categories'] = (bool) $output['show_categories'];
    $output['posts_per_page'] = absint($output['posts_per_page']);
    $output['show_sidebar'] = (bool) $output['show_sidebar'];

    return $output;
}

// Helper function to get blog setting
function wades_get_blog_setting($key, $default = '') {
    $settings = get_option('wades_blog_settings', array());
    return isset($settings[$key]) ? $settings[$key] : $default;
}

// Get all blog settings
function wades_get_blog_settings() {
    $defaults = array(
        'show_featured' => true,
        'show_categories' => true,
        'posts_per_page' => 9,
        'show_sidebar' => true
    );

    $settings = get_option('wades_blog_settings', array());
    return wp_parse_args($settings, $defaults);
}

// Blog Settings Page Callback
function wades_blog_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Get current settings with defaults
    $settings = wades_get_blog_settings();

    // Save settings if form is submitted
    if (isset($_POST['wades_blog_settings_nonce']) && wp_verify_nonce($_POST['wades_blog_settings_nonce'], 'wades_blog_settings')) {
        // Update settings array
        $new_settings = array(
            'show_featured' => isset($_POST['show_featured']),
            'show_categories' => isset($_POST['show_categories']),
            'posts_per_page' => isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : $settings['posts_per_page'],
            'show_sidebar' => isset($_POST['show_sidebar'])
        );

        // Save all settings at once
        update_option('wades_blog_settings', $new_settings);
        $settings = $new_settings;
        
        add_settings_error('wades_blog_messages', 'wades_blog_message', 'Settings Saved', 'updated');
    }

    settings_errors('wades_blog_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="">
            <?php wp_nonce_field('wades_blog_settings', 'wades_blog_settings_nonce'); ?>

            <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
                <h2>Layout Settings</h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">Show Featured Post</th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       name="show_featured" 
                                       value="1" 
                                       <?php checked($settings['show_featured']); ?>>
                                Display featured post at the top of the blog
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Show Categories</th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       name="show_categories" 
                                       value="1" 
                                       <?php checked($settings['show_categories']); ?>>
                                Display category filter buttons
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Show Sidebar</th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       name="show_sidebar" 
                                       value="1" 
                                       <?php checked($settings['show_sidebar']); ?>>
                                Display sidebar on blog pages
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="posts_per_page">Posts Per Page</label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="posts_per_page" 
                                   name="posts_per_page" 
                                   value="<?php echo esc_attr($settings['posts_per_page']); ?>" 
                                   min="1" 
                                   max="100" 
                                   class="small-text">
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

// Add the theme settings page to the admin menu
function wades_add_theme_settings_page() {
    // Add top level menu
    add_menu_page(
        'Wades Theme',
        'Wades',
        'manage_options',
        'wades-dashboard',
        'wades_dashboard_page',
        'dashicons-admin-customizer',
        2  // Position after Dashboard
    );

    // Add submenus
    add_submenu_page(
        'wades-dashboard',
        'Settings',
        'Settings',
        'manage_options',
        'wades-settings',
        'wades_theme_settings_page'
    );

    // Add Documentation submenu
    add_submenu_page(
        'wades-dashboard',
        'Documentation',
        'Documentation',
        'manage_options',
        'wades-documentation',
        'wades_documentation_page'
    );

    // Add Support submenu
    add_submenu_page(
        'wades-dashboard',
        'Support',
        'Support',
        'manage_options',
        'wades-support',
        'wades_support_page'
    );

    // Add Resources submenu
    add_submenu_page(
        'wades-dashboard',
        'Resources',
        'Resources',
        'manage_options',
        'wades-resources',
        'wades_resources_page'
    );

    // Add Boats Import/Export submenu
    add_submenu_page(
        'wades-dashboard',
        'Boats Import/Export',
        'Boats Import/Export',
        'manage_options',
        'wades-boats-import-export',
        'wades_boats_import_export_page'
    );

    // Add Blog Settings submenu
    add_submenu_page(
        'wades-dashboard',
        'Blog Settings',
        'Blog Settings',
        'manage_options',
        'wades-blog-settings',
        'wades_blog_settings_page'
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

// Dashboard Page
function wades_dashboard_page() {
    ?>
    <div class="wrap">
        <h1>Welcome to Wades Theme</h1>
        <div class="about-text">
            Thank you for choosing Wades Theme. This dashboard will help you manage your website effectively.
        </div>

        <div class="card" style="max-width: 100%; margin-top: 20px;">
            <h2>Quick Links</h2>
            <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px;">
                <a href="<?php echo admin_url('admin.php?page=wades-settings'); ?>" class="button button-primary" style="text-align: center;">
                    <span class="dashicons dashicons-admin-generic" style="margin-right: 8px;"></span>
                    Theme Settings
                </a>
                <a href="<?php echo admin_url('admin.php?page=wades-documentation'); ?>" class="button button-secondary" style="text-align: center;">
                    <span class="dashicons dashicons-book" style="margin-right: 8px;"></span>
                    Documentation
                </a>
                <a href="<?php echo admin_url('admin.php?page=wades-support'); ?>" class="button button-secondary" style="text-align: center;">
                    <span class="dashicons dashicons-sos" style="margin-right: 8px;"></span>
                    Get Support
                </a>
            </div>
        </div>

        <div class="card" style="max-width: 100%; margin-top: 20px;">
            <h2>Recent Updates</h2>
            <div style="padding: 20px;">
                <ul style="list-style-type: disc; margin-left: 20px;">
                    <li>Added new boat inventory management features</li>
                    <li>Improved page loading performance</li>
                    <li>Enhanced mobile responsiveness</li>
                    <li>Added new customization options</li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

// Documentation Page
function wades_documentation_page() {
    ?>
    <div class="wrap">
        <h1>Theme Documentation</h1>
        
        <!-- Table of Contents -->
        <div class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Table of Contents</h2>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li><a href="#getting-started">Getting Started</a></li>
                <li><a href="#theme-settings">Theme Settings</a></li>
                <li><a href="#page-templates">Page Templates</a></li>
                <li><a href="#boat-inventory">Boat Inventory Management</a></li>
                <li><a href="#services">Services Management</a></li>
                <li><a href="#instagram">Instagram Integration</a></li>
                <li><a href="#performance">Performance Features</a></li>
                <li><a href="#customization">Customization Options</a></li>
            </ul>
        </div>

        <!-- Getting Started -->
        <div id="getting-started" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Getting Started</h2>
            <p>Welcome to the Wades Theme documentation. This theme is specifically designed for boat dealerships and marine services.</p>
            
            <h3>Quick Start Guide</h3>
            <ol style="margin-left: 20px; list-style-type: decimal;">
                <li>Configure your theme settings under Wades > Settings</li>
                <li>Set up your homepage layout using the Home template</li>
                <li>Add your boat inventory under the Boats menu</li>
                <li>Configure your services under the Services menu</li>
                <li>Set up your contact information and business hours</li>
            </ol>
        </div>

        <!-- Theme Settings -->
        <div id="theme-settings" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Theme Settings</h2>
            <p>The theme settings can be found under Wades > Settings and include:</p>
            
            <h3>Company Information</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Company Phone Number</li>
                <li>Company Email</li>
                <li>Business Address</li>
                <li>Business Hours</li>
            </ul>

            <h3>Social Media</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Facebook URL</li>
                <li>Instagram URL</li>
                <li>YouTube URL</li>
            </ul>

            <h3>Instagram Feed Settings</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Instagram Access Token</li>
                <li>Instagram User ID</li>
                <li>Cache Duration Settings</li>
            </ul>
        </div>

        <!-- Page Templates -->
        <div id="page-templates" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Page Templates</h2>
            <p>The theme includes several custom page templates:</p>

            <h3>Home Template</h3>
            <p>Features include:</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Hero section with video/image background</li>
                <li>Featured brands showcase</li>
                <li>Fleet section for featured boats</li>
                <li>Services section</li>
                <li>Testimonials section</li>
                <li>Instagram feed integration</li>
                <li>Call-to-action section</li>
            </ul>

            <h3>About Template</h3>
            <p>Includes sections for:</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Company story</li>
                <li>Team members</li>
                <li>Specialties and expertise</li>
                <li>Service areas</li>
                <li>Contact information</li>
            </ul>

            <h3>Contact Template</h3>
            <p>Features:</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Contact form with validation</li>
                <li>Google Maps integration</li>
                <li>Business hours display</li>
                <li>FAQ section</li>
            </ul>

            <h3>Services Template</h3>
            <p>Showcases your services with:</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Service categories</li>
                <li>Pricing information</li>
                <li>Service features and benefits</li>
                <li>Call-to-action buttons</li>
            </ul>
        </div>

        <!-- Boat Inventory -->
        <div id="boat-inventory" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Boat Inventory Management</h2>
            <p>The theme includes a comprehensive boat inventory management system:</p>

            <h3>Features</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Custom post type for boats</li>
                <li>Detailed boat specifications</li>
                <li>Multiple image gallery support</li>
                <li>Pricing and availability status</li>
                <li>Boat categories and filters</li>
                <li>Featured boats designation</li>
                <li>Bulk import/export functionality</li>
            </ul>

            <h3>Adding a New Boat</h3>
            <ol style="margin-left: 20px; list-style-type: decimal;">
                <li>Go to Boats > Add New</li>
                <li>Enter boat details and specifications</li>
                <li>Upload boat images</li>
                <li>Set pricing and availability</li>
                <li>Choose categories and features</li>
                <li>Publish when ready</li>
            </ol>

            <h3>Import/Export Features</h3>
            <p>Easily manage your boat inventory in bulk:</p>
            
            <h4>Export Boats</h4>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Export all boats to CSV format</li>
                <li>Include image URLs in export (optional)</li>
                <li>Export all metadata and specifications</li>
                <li>Compatible with spreadsheet software</li>
            </ul>

            <h4>Import Boats</h4>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Bulk import boats from CSV file</li>
                <li>Update existing boats by SKU</li>
                <li>Automatically download and import images</li>
                <li>Validate and sanitize imported data</li>
                <li>Download sample CSV template</li>
            </ul>

            <h4>CSV Format</h4>
            <p>Required columns for import:</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>title (required) - Boat name/title</li>
                <li>sku - Unique identifier</li>
                <li>price - Boat price</li>
                <li>description - Full description</li>
                <li>length - Boat length</li>
                <li>capacity - Passenger capacity</li>
                <li>speed - Maximum speed</li>
                <li>features - Comma-separated list of features</li>
                <li>category - Boat category</li>
                <li>status - publish, draft, private</li>
                <li>images - Comma-separated list of image URLs (optional)</li>
            </ul>

            <h4>Import/Export Tips</h4>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Always backup your data before performing bulk imports</li>
                <li>Use the sample CSV as a template for your imports</li>
                <li>Verify data formatting in your CSV file</li>
                <li>Check the import log for any errors or issues</li>
                <li>Test imports with a small batch first</li>
            </ul>
        </div>

        <!-- Services Management -->
        <div id="services" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Services Management</h2>
            <p>Manage your marine services effectively:</p>

            <h3>Service Features</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Custom service post type</li>
                <li>Service categories</li>
                <li>Pricing options</li>
                <li>Service duration settings</li>
                <li>Location options (shop/mobile/both)</li>
                <li>Featured services selection</li>
            </ul>

            <h3>Service Display Options</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Grid or list layout</li>
                <li>Category filtering</li>
                <li>Featured services carousel</li>
                <li>Service comparison table</li>
            </ul>
        </div>

        <!-- Instagram Integration -->
        <div id="instagram" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Instagram Integration</h2>
            <p>The theme includes advanced Instagram feed features:</p>

            <h3>Setup Instructions</h3>
            <ol style="margin-left: 20px; list-style-type: decimal;">
                <li>Obtain Instagram Graph API access token</li>
                <li>Enter your Instagram business account ID</li>
                <li>Configure cache settings for optimal performance</li>
                <li>Customize display options</li>
            </ol>

            <h3>Display Options</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Grid layout with hover effects</li>
                <li>Caption and engagement metrics</li>
                <li>Responsive design</li>
                <li>Lazy loading for better performance</li>
            </ul>
        </div>

        <!-- Performance Features -->
        <div id="performance" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Performance Features</h2>
            <p>The theme is optimized for speed and performance:</p>

            <h3>Built-in Optimizations</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Server-side caching with unstable_cache</li>
                <li>Lazy loading for images</li>
                <li>Optimized asset loading</li>
                <li>Responsive image handling</li>
                <li>Minified CSS and JavaScript</li>
                <li>Modern image formats support</li>
            </ul>
        </div>

        <!-- Customization -->
        <div id="customization" class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Customization Options</h2>
            <p>The theme offers extensive customization options:</p>

            <h3>Design Customization</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Custom color schemes</li>
                <li>Typography options</li>
                <li>Layout adjustments</li>
                <li>Header and footer styles</li>
                <li>Custom CSS support</li>
            </ul>

            <h3>Content Customization</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Section visibility controls</li>
                <li>Content ordering options</li>
                <li>Custom widgets and sidebars</li>
                <li>Menu customization</li>
            </ul>
        </div>

        <!-- Video Tutorials -->
        <div class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Video Tutorials</h2>
            <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="tutorial-card">
                    <h3>Basic Setup</h3>
                    <ul style="margin-left: 20px; list-style-type: disc;">
                        <li><a href="#">Initial Theme Setup</a></li>
                        <li><a href="#">Configuring Theme Settings</a></li>
                        <li><a href="#">Setting Up Your Homepage</a></li>
                    </ul>
                </div>
                <div class="tutorial-card">
                    <h3>Advanced Features</h3>
                    <ul style="margin-left: 20px; list-style-type: disc;">
                        <li><a href="#">Managing Boat Inventory</a></li>
                        <li><a href="#">Setting Up Services</a></li>
                        <li><a href="#">Instagram Integration</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Support Page
function wades_support_page() {
    ?>
    <div class="wrap">
        <h1>Theme Support</h1>
        <div class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Need Help?</h2>
            <p>We're here to help you with any questions or issues you might have with your website.</p>

            <h3>Contact Support</h3>
            <p>Email: <a href="mailto:support@byronwade.com">support@byronwade.com</a></p>
            <p>Phone: (530) 645-7239</p>

            <h3>Common Issues</h3>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li><a href="#">How to Update Your Website</a></li>
                <li><a href="#">Troubleshooting Guide</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
    </div>
    <?php
}

// Resources Page
function wades_resources_page() {
    ?>
    <div class="wrap">
        <h1>Theme Resources</h1>
        <div class="card" style="max-width: 100%; margin-top: 20px; padding: 20px;">
            <h2>Helpful Resources</h2>
            <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="resource-card">
                    <h3>Marketing Tools</h3>
                    <ul style="margin-left: 20px; list-style-type: disc;">
                        <li><a href="#">Social Media Templates</a></li>
                        <li><a href="#">Email Marketing Guide</a></li>
                        <li><a href="#">SEO Best Practices</a></li>
                    </ul>
                </div>
                <div class="resource-card">
                    <h3>Design Assets</h3>
                    <ul style="margin-left: 20px; list-style-type: disc;">
                        <li><a href="#">Logo Files</a></li>
                        <li><a href="#">Brand Guidelines</a></li>
                        <li><a href="#">Image Optimization Tips</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}

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

// Add footer attribution
function wades_admin_footer_text($text) {
    $screen = get_current_screen();
    if (strpos($screen->base, 'wades') !== false) {
        $text = 'Made and developed by <a href="https://byronwade.com" target="_blank">byronwade.com</a>';
    }
    return $text;
}
add_filter('admin_footer_text', 'wades_admin_footer_text');

// Helper function to get theme settings
function wades_get_setting($key) {
    return get_option('wades_' . $key);
}

// Boats Import/Export Page
function wades_boats_import_export_page() {
    // Check for import/export results
    $import_results = array(
        'imported' => isset($_GET['imported']) ? intval($_GET['imported']) : 0,
        'updated' => isset($_GET['updated']) ? intval($_GET['updated']) : 0,
        'skipped' => isset($_GET['skipped']) ? intval($_GET['skipped']) : 0,
        'errors' => isset($_GET['errors']) ? explode('|', $_GET['errors']) : array(),
        'status' => isset($_GET['import_status']) ? $_GET['import_status'] : ''
    );

    ?>
    <div class="wrap">
        <h1>Boats Import/Export</h1>

        <?php if ($import_results['status']) : ?>
            <div class="notice notice-<?php echo $import_results['status'] === 'success' ? 'success' : 'error'; ?> is-dismissible">
                <p>
                    <?php
                    echo sprintf(
                        'Import completed. Imported: %d, Updated: %d, Skipped: %d%s',
                        $import_results['imported'],
                        $import_results['updated'],
                        $import_results['skipped'],
                        !empty($import_results['errors']) ? '<br>Errors: ' . implode('<br>', $import_results['errors']) : ''
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Export Boats</h2>
            <p>Download your boat inventory as a CSV file.</p>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('wades_export_boats', 'boat_export_nonce'); ?>
                <input type="hidden" name="action" value="wades_export_boats">
                
                <p>
                    <label>
                        <input type="checkbox" name="include_images" value="1">
                        Include image URLs in export
                    </label>
                </p>
                
                <?php submit_button('Export Boats to CSV', 'primary', 'submit', false); ?>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Import Boats</h2>
            <p>Import boats from a CSV file. <a href="#" id="download-sample">Download sample CSV</a></p>
            
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <?php wp_nonce_field('wades_import_boats', 'boat_import_nonce'); ?>
                <input type="hidden" name="action" value="wades_import_boats">
                
                <p>
                    <label>
                        <span class="required">CSV File:</span><br>
                        <input type="file" name="csv_file" accept=".csv" required>
                    </label>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="update_existing" value="1">
                        Update existing boats (matched by SKU)
                    </label>
                </p>
                
                <p>
                    <label>
                        <input type="checkbox" name="download_images" value="1">
                        Download and attach images from URLs
                    </label>
                </p>
                
                <?php submit_button('Import Boats from CSV', 'primary', 'submit', false); ?>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>CSV Format Guide</h2>
            <p>Your CSV file should include the following columns:</p>
            <ul style="list-style-type: disc; margin-left: 20px;">
                <li><strong>title</strong> (required) - The boat name/title</li>
                <li><strong>sku</strong> - Unique identifier for the boat</li>
                <li><strong>price</strong> - The boat price</li>
                <li><strong>description</strong> - Full description of the boat</li>
                <li><strong>length</strong> - Boat length in feet</li>
                <li><strong>capacity</strong> - Maximum number of passengers</li>
                <li><strong>speed</strong> - Maximum speed in knots</li>
                <li><strong>features</strong> - Comma-separated list of features</li>
                <li><strong>category</strong> - Comma-separated list of categories</li>
                <li><strong>status</strong> - Post status (publish, draft, private)</li>
                <li><strong>images</strong> - Comma-separated list of image URLs</li>
            </ul>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Recent Activity</h2>
            <?php
            $recent_activity = get_option('wades_boat_import_export_log', array());
            if (!empty($recent_activity)) :
            ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activity as $activity) : ?>
                            <tr>
                                <td><?php echo esc_html(human_time_diff(strtotime($activity['time']), current_time('timestamp')) . ' ago'); ?></td>
                                <td><?php echo esc_html(ucfirst($activity['type'])); ?></td>
                                <td>
                                    <span class="status-<?php echo esc_attr($activity['status']); ?>">
                                        <?php echo esc_html(ucfirst($activity['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($activity['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No recent activity.</p>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            padding: 20px;
            margin-bottom: 20px;
        }
        .required {
            color: #d63638;
        }
        .status-success {
            color: #00a32a;
        }
        .status-error {
            color: #d63638;
        }
        .widefat td {
            vertical-align: middle;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        $('#download-sample').click(function(e) {
            e.preventDefault();
            
            // Create sample data
            var sampleData = [
                // Headers must match exactly
                ['Stock Number', 'Location', 'Condition', 'Status', 'Model Year', 'Manufacturer', 'Model', 'Trim', 'Retail', 'Sales', 'Web', 'Discount', 'Floorplan'],
                [
                    'IMGCONSGPBURG',
                    'Impact Marine Group',
                    'USED',
                    'Available',
                    '2023',
                    'Godfrey',
                    '2286 SFL',
                    '',
                    '0',
                    '61999',
                    '0',
                    '',
                    ''
                ],
                [
                    'IMGCONSNBAR',
                    'Impact Marine Group',
                    'USED',
                    'Available',
                    '2014',
                    'Nitro',
                    'Z7',
                    'DC',
                    '0',
                    '24500',
                    '0',
                    '',
                    ''
                ],
                [
                    'IMGCONSSBJAC',
                    'Impact Marine Group',
                    'USED',
                    'Available',
                    '2021',
                    'Sea Born',
                    '22 LX',
                    '',
                    '0',
                    '71999',
                    '0',
                    '',
                    ''
                ]
            ];

            // Convert to CSV with proper escaping
            let csvContent = '';
            sampleData.forEach(function(row) {
                // Properly escape and quote fields
                let processedRow = row.map(function(field) {
                    // If field contains comma, quote it
                    if (field.indexOf(',') > -1) {
                        return '"' + field.replace(/"/g, '""') + '"';
                    }
                    return field;
                });
                csvContent += processedRow.join(',') + '\n';
            });

            // Create and trigger download
            var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            if (link.download !== undefined) {
                var url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', 'sample-boats.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    });
    </script>
    <?php
}

/**
 * Handle boat import
 */
function wades_handle_boat_import() {
    if (!isset($_POST['boat_import_nonce']) || !wp_verify_nonce($_POST['boat_import_nonce'], 'wades_import_boats')) {
        wp_die('Invalid nonce');
    }

    if (!current_user_can('edit_posts')) {
        wp_die('Unauthorized access');
    }

    $file = $_FILES['csv_file'];
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        wp_redirect(add_query_arg('import_status', 'error', wp_get_referer()));
        exit;
    }

    $handle = fopen($file['tmp_name'], 'r');
    if (!$handle) {
        wp_redirect(add_query_arg('import_status', 'error', wp_get_referer()));
        exit;
    }

    // Get headers
    $headers = fgetcsv($handle, 0, ',', '"', '\\');
    if (!$headers) {
        fclose($handle);
        wp_redirect(add_query_arg(array(
            'import_status' => 'error',
            'errors' => 'No headers found in CSV file'
        ), wp_get_referer()));
        exit;
    }

    // Remove any BOM characters from the first header
    $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
    
    // Define expected headers and their order
    $expected_headers = array(
        'Stock Number',
        'Location',
        'Condition',
        'Status',
        'Model Year',
        'Manufacturer',
        'Model',
        'Trim',
        'Retail',
        'Sales',
        'Web',
        'Discount',
        'Floorplan'
    );
    
    // Normalize headers (trim whitespace and lowercase)
    $headers = array_map(function($header) {
        return strtolower(trim(str_replace(' ', '_', $header)));
    }, $headers);

    $expected_headers_normalized = array_map(function($header) {
        return strtolower(trim(str_replace(' ', '_', $header)));
    }, $expected_headers);

    // Compare headers with expected headers
    $missing_headers = array_diff($expected_headers_normalized, $headers);
    $extra_headers = array_diff($headers, $expected_headers_normalized);

    if (!empty($missing_headers)) {
        fclose($handle);
        wp_redirect(add_query_arg(array(
            'import_status' => 'error',
            'errors' => 'Missing columns: ' . implode(', ', $missing_headers) . '. Expected columns are: ' . implode(', ', $expected_headers)
        ), wp_get_referer()));
        exit;
    }

    if (!empty($extra_headers)) {
        fclose($handle);
        wp_redirect(add_query_arg(array(
            'import_status' => 'error',
            'errors' => 'Extra unexpected columns found: ' . implode(', ', $extra_headers)
        ), wp_get_referer()));
        exit;
    }

    $stats = array(
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => array()
    );

    $row = 2; // Start at row 2 (after headers)
    while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
        // Clean the data array
        $data = array_map('trim', $data);
        
        // Create associative array with default empty values for missing fields
        $boat_data = array_combine($headers, $data);
        
        // Skip rows where essential data is missing
        if (empty($boat_data['stock_number']) || 
            (empty($boat_data['manufacturer']) && empty($boat_data['model']) && empty($boat_data['model_year']))) {
            $stats['skipped']++;
            $row++;
            continue;
        }

        // Check if boat exists by stock number
        $existing_boat = get_posts(array(
            'post_type' => 'boat',
            'meta_key' => '_boat_stock_number',
            'meta_value' => $boat_data['stock_number'],
            'posts_per_page' => 1
        ));

        // Generate post title - handle empty fields
        $title_parts = array_filter([
            $boat_data['model_year'],
            $boat_data['manufacturer'],
            $boat_data['model'],
            $boat_data['trim']
        ]);
        
        // If we can't generate a meaningful title, use stock number
        $post_title = !empty($title_parts) ? 
            implode(' ', $title_parts) : 
            'Boat ' . $boat_data['stock_number'];

        $boat_id = null;
        if (!empty($existing_boat) && isset($_POST['update_existing'])) {
            $boat_id = $existing_boat[0]->ID;
            wp_update_post(array(
                'ID' => $boat_id,
                'post_title' => wp_strip_all_tags($post_title),
                'post_status' => 'publish'
            ));
            $stats['updated']++;
        } elseif (empty($existing_boat)) {
            $boat_id = wp_insert_post(array(
                'post_title' => wp_strip_all_tags($post_title),
                'post_type' => 'boat',
                'post_status' => 'publish'
            ));
            $stats['imported']++;
        } else {
            $stats['skipped']++;
            $row++;
            continue;
        }

        if (is_wp_error($boat_id)) {
            $stats['errors'][] = "Line $row: Failed to create/update boat";
            $row++;
            continue;
        }

        // Update meta fields - handle numeric fields
        $meta_fields = array(
            'stock_number' => '_boat_stock_number',
            'model_year' => '_boat_model_year',
            'model' => '_boat_model',
            'trim' => '_boat_trim',
            'retail' => '_boat_retail_price',
            'sales' => '_boat_sales_price',
            'web' => '_boat_web_price',
            'discount' => '_boat_discount',
            'floorplan' => '_boat_floorplan'
        );

        foreach ($meta_fields as $csv_key => $meta_key) {
            if (isset($boat_data[$csv_key])) {
                $value = $boat_data[$csv_key];
                // Convert empty numeric fields to 0
                if (in_array($meta_key, array('_boat_retail_price', '_boat_sales_price', '_boat_web_price', '_boat_discount'))) {
                    $value = (is_numeric($value) && $value > 0) ? floatval($value) : 0;
                }
                update_post_meta($boat_id, $meta_key, sanitize_text_field($value));
            }
        }

        // Update taxonomies - only if values exist
        $taxonomies = array(
            'location' => 'boat_location',
            'condition' => 'boat_condition',
            'status' => 'boat_status',
            'manufacturer' => 'boat_manufacturer'
        );

        foreach ($taxonomies as $csv_key => $taxonomy) {
            if (!empty($boat_data[$csv_key])) {
                wp_set_object_terms($boat_id, $boat_data[$csv_key], $taxonomy);
            }
        }

        // Handle image if present
        if (!empty($boat_data['images'])) {
            $image_path = $boat_data['images'];
            // If the path starts with 'unit/', it's a relative path
            if (strpos($image_path, 'unit/') === 0) {
                $upload_dir = wp_upload_dir();
                $image_path = trailingslashit($upload_dir['basedir']) . $image_path;
                
                if (file_exists($image_path)) {
                    $filetype = wp_check_filetype(basename($image_path), null);
                    $attachment = array(
                        'post_mime_type' => $filetype['type'],
                        'post_title' => sanitize_file_name(basename($image_path)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment($attachment, $image_path, $boat_id);
                    if (!is_wp_error($attach_id)) {
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $attach_data = wp_generate_attachment_metadata($attach_id, $image_path);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        set_post_thumbnail($boat_id, $attach_id);
                    }
                }
            }
        }

        $row++;
    }

    fclose($handle);

    // Redirect with status
    $redirect_args = array(
        'import_status' => empty($stats['errors']) ? 'success' : 'warning',
        'imported' => $stats['imported'],
        'updated' => $stats['updated'],
        'skipped' => $stats['skipped']
    );

    if (!empty($stats['errors'])) {
        $redirect_args['errors'] = implode('|', $stats['errors']);
    }

    wp_redirect(add_query_arg($redirect_args, wp_get_referer()));
    exit;
}
add_action('admin_post_wades_import_boats', 'wades_handle_boat_import');

/**
 * Handle boat export
 */
function wades_handle_boat_export() {
    if (!isset($_POST['boat_export_nonce']) || !wp_verify_nonce($_POST['boat_export_nonce'], 'wades_export_boats')) {
        wp_die('Invalid nonce');
    }

    if (!current_user_can('edit_posts')) {
        wp_die('Unauthorized access');
    }

    $include_images = isset($_POST['include_images']);
    $filename = 'boats-export-' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // Define CSV headers
    $headers = array('title', 'sku', 'description', 'price', 'length', 'capacity', 'speed', 'features', 'category', 'status');
    if ($include_images) {
        $headers[] = 'images';
    }

    fputcsv($output, $headers);

    // Query boats
    $boats = get_posts(array(
        'post_type' => 'boat',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    foreach ($boats as $boat) {
        $row = array(
            $boat->post_title,
            get_post_meta($boat->ID, '_boat_sku', true),
            $boat->post_content,
            get_post_meta($boat->ID, '_boat_price', true),
            get_post_meta($boat->ID, '_boat_length', true),
            get_post_meta($boat->ID, '_boat_capacity', true),
            get_post_meta($boat->ID, '_boat_speed', true),
            get_post_meta($boat->ID, '_boat_features', true),
            get_post_meta($boat->ID, '_boat_category', true),
            get_post_meta($boat->ID, '_boat_status', true)
        );

        if ($include_images) {
            $image_url = get_the_post_thumbnail_url($boat->ID, 'full');
            $row[] = $image_url ? $image_url : '';
        }

        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
add_action('admin_post_wades_export_boats', 'wades_handle_boat_export');

/**
 * Helper function to handle image upload from URL
 */
function wades_handle_image_upload($url, $post_id) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Download file to temp dir
    $temp_file = download_url($url);

    if (is_wp_error($temp_file)) {
        return $temp_file;
    }

    $file_array = array(
        'name' => basename($url),
        'tmp_name' => $temp_file
    );

    // Check file type
    $file_type = wp_check_filetype($file_array['name'], null);
    if (empty($file_type['type'])) {
        @unlink($temp_file);
        return new WP_Error('invalid_file', 'Invalid file type');
    }

    // Upload the file
    $attachment_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($attachment_id)) {
        @unlink($temp_file);
        return $attachment_id;
    }

    // Set as featured image
    set_post_thumbnail($post_id, $attachment_id);

    return $attachment_id;
}

/**
 * Add custom columns to the boats list table
 */
function wades_add_boat_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['show_on_home'] = 'Show on Homepage';
        }
    }
    return $new_columns;
}
add_filter('manage_boat_posts_columns', 'wades_add_boat_columns');

/**
 * Add content to the custom column
 */
function wades_boat_column_content($column, $post_id) {
    if ($column === 'show_on_home') {
        $show_on_home = get_post_meta($post_id, '_show_on_home', true);
        $icon = $show_on_home ? 'visibility' : 'visibility_off';
        $text = $show_on_home ? 'Yes' : 'No';
        echo '<span class="dashicons dashicons-' . $icon . '" title="' . $text . '"></span>';
    }
}
add_action('manage_boat_posts_custom_column', 'wades_boat_column_content', 10, 2);

/**
 * Add quick edit fields
 */
function wades_add_quick_edit_fields() {
    global $post_type;
    if ($post_type !== 'boat') return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="alignleft">
                <input type="checkbox" name="show_on_home" value="1">
                <span class="checkbox-title">Show on Homepage</span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'wades_add_quick_edit_fields', 10, 2);

/**
 * Add JavaScript for quick edit
 */
function wades_quick_edit_javascript() {
    global $post_type;
    if ($post_type !== 'boat') return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $wp_inline_edit = inlineEditPost.edit;
        
        inlineEditPost.edit = function(id) {
            $wp_inline_edit.apply(this, arguments);
            
            var post_id = 0;
            if (typeof(id) == 'object') {
                post_id = parseInt(this.getId(id));
            }
            
            if (post_id > 0) {
                var $row = $('#post-' + post_id);
                var $show_on_home = $row.find('.column-show_on_home .dashicons-visibility').length > 0;
                
                var $editRow = $('#edit-' + post_id);
                $editRow.find('input[name="show_on_home"]').prop('checked', $show_on_home);
            }
        };

        // Remove bulk edit options for show_on_home
        $('.inline-edit-group option[value="show_on_home"]').parent().remove();
    });
    </script>
    <?php
}
add_action('admin_footer-edit.php', 'wades_quick_edit_javascript');

/**
 * Save quick edit data
 */
function wades_save_quick_edit_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (!current_user_can('edit_post', $post_id)) return $post_id;
    if (get_post_type($post_id) !== 'boat') return $post_id;

    // Update show on home setting
    $show_on_home = isset($_POST['show_on_home']) ? '1' : '';
    update_post_meta($post_id, '_show_on_home', $show_on_home);
}
add_action('save_post', 'wades_save_quick_edit_data');

/**
 * Make the column sortable
 */
function wades_sortable_boat_columns($columns) {
    $columns['show_on_home'] = 'show_on_home';
    return $columns;
}
add_filter('manage_edit-boat_sortable_columns', 'wades_sortable_boat_columns');

/**
 * Handle sorting
 */
function wades_boat_columns_orderby($query) {
    if (!is_admin()) return;
    
    $orderby = $query->get('orderby');
    if ('show_on_home' === $orderby) {
        $query->set('meta_key', '_show_on_home');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'wades_boat_columns_orderby');

/**
 * Add custom boat icon to admin menu
 */
function wades_admin_boat_icon() {
    ?>
    <style>
        /* Hide bulk edit options for show_on_home */
        .inline-edit-boat select option[value="show_on_home"],
        .inline-edit-boat select option[value="-1"] {
            display: none;
        }
    </style>
    <?php
}
add_action('admin_head', 'wades_admin_boat_icon');

// Add Blog Settings section to the theme settings
add_action('admin_init', 'wades_blog_settings_init');

function wades_blog_settings_init() {
    // Add new section
    add_settings_section(
        'wades_blog_settings_section',
        'Blog Page Settings',
        'wades_blog_settings_section_callback',
        'wades_theme_settings'
    );

    // Register Blog Hero Title setting
    register_setting('wades_theme_settings', 'wades_blog_hero_title');
    add_settings_field(
        'wades_blog_hero_title',
        'Blog Hero Title',
        'wades_text_field_callback',
        'wades_theme_settings',
        'wades_blog_settings_section',
        array(
            'label_for' => 'wades_blog_hero_title',
            'default' => 'Latest News & Updates'
        )
    );

    // Register Blog Hero Description setting
    register_setting('wades_theme_settings', 'wades_blog_hero_description');
    add_settings_field(
        'wades_blog_hero_description',
        'Blog Hero Description',
        'wades_textarea_field_callback',
        'wades_theme_settings',
        'wades_blog_settings_section',
        array(
            'label_for' => 'wades_blog_hero_description',
            'default' => 'Stay informed with our latest articles, tips, and industry insights.'
        )
    );

    // Register Blog Hero Background Image setting
    register_setting('wades_theme_settings', 'wades_blog_hero_image');
    add_settings_field(
        'wades_blog_hero_image',
        'Blog Hero Background Image',
        'wades_image_field_callback',
        'wades_theme_settings',
        'wades_blog_settings_section',
        array(
            'label_for' => 'wades_blog_hero_image',
            'description' => 'Select a background image for the blog hero section.'
        )
    );

    // Register Blog Hero Overlay Opacity setting
    register_setting('wades_theme_settings', 'wades_blog_hero_opacity');
    add_settings_field(
        'wades_blog_hero_opacity',
        'Blog Hero Overlay Opacity',
        'wades_number_field_callback',
        'wades_theme_settings',
        'wades_blog_settings_section',
        array(
            'label_for' => 'wades_blog_hero_opacity',
            'default' => '50',
            'min' => '0',
            'max' => '100',
            'step' => '5',
            'description' => 'Adjust the darkness of the hero image overlay (0-100)'
        )
    );
}

function wades_blog_settings_section_callback() {
    echo '<p>Configure the appearance and content of your blog page.</p>';
}

// Add image upload field callback if not already defined
if (!function_exists('wades_image_field_callback')) {
    function wades_image_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name);
        $image_url = $value ? wp_get_attachment_image_url($value, 'medium') : '';
        ?>
        <div class="wades-image-field">
            <input type="hidden" id="<?php echo esc_attr($option_name); ?>" 
                   name="<?php echo esc_attr($option_name); ?>" 
                   value="<?php echo esc_attr($value); ?>">
            
            <div class="image-preview" style="margin-bottom: 10px;">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; height: auto;">
                <?php endif; ?>
            </div>

            <button type="button" 
                    class="button wades-upload-image" 
                    data-target="<?php echo esc_attr($option_name); ?>">
                <?php echo $image_url ? 'Change Image' : 'Choose Image'; ?>
            </button>

            <?php if ($image_url) : ?>
                <button type="button" 
                        class="button wades-remove-image" 
                        data-target="<?php echo esc_attr($option_name); ?>">
                    Remove Image
                </button>
            <?php endif; ?>
        </div>
        <?php
    }
}

// Add script for image upload if not already added
add_action('admin_footer', 'wades_image_upload_script');
function wades_image_upload_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Handle image upload
        $('.wades-upload-image').click(function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetId = button.data('target');
            var previewContainer = button.closest('.wades-image-field').find('.image-preview');
            
            var frame = wp.media({
                title: 'Select Image',
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#' + targetId).val(attachment.id);
                
                var preview = $('<img>')
                    .attr('src', attachment.sizes.medium.url)
                    .css({
                        'max-width': '300px',
                        'height': 'auto'
                    });
                
                previewContainer.html(preview);
                
                button.text('Change Image');
                
                if (!button.siblings('.wades-remove-image').length) {
                    $('<button>')
                        .attr('type', 'button')
                        .addClass('button wades-remove-image')
                        .data('target', targetId)
                        .text('Remove Image')
                        .insertAfter(button);
                }
            });

            frame.open();
        });

        // Handle image removal
        $(document).on('click', '.wades-remove-image', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var container = $(this).closest('.wades-image-field');
            
            $('#' + targetId).val('');
            container.find('.image-preview').empty();
            container.find('.wades-upload-image').text('Choose Image');
            $(this).remove();
        });
    });
    </script>
    <?php
}

// Add number field callback if not already defined
if (!function_exists('wades_number_field_callback')) {
    function wades_number_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, $args['default']);
        ?>
        <input type="number" 
               id="<?php echo esc_attr($option_name); ?>" 
               name="<?php echo esc_attr($option_name); ?>"
               value="<?php echo esc_attr($value); ?>"
               min="<?php echo esc_attr($args['min']); ?>"
               max="<?php echo esc_attr($args['max']); ?>"
               step="<?php echo esc_attr($args['step']); ?>"
               class="regular-text">
        <?php if (isset($args['description'])) : ?>
            <p class="description"><?php echo esc_html($args['description']); ?></p>
        <?php endif;
    }
}

// Add textarea field callback if not already defined
if (!function_exists('wades_textarea_field_callback')) {
    function wades_textarea_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, $args['default']);
        ?>
        <textarea id="<?php echo esc_attr($option_name); ?>" 
                  name="<?php echo esc_attr($option_name); ?>"
                  class="large-text" 
                  rows="3"><?php echo esc_textarea($value); ?></textarea>
        <?php if (isset($args['description'])) : ?>
            <p class="description"><?php echo esc_html($args['description']); ?></p>
        <?php endif;
    }
} 