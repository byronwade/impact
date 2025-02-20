<?php
/**
 * Default Content Creation
 *
 * @package wades
 */

/**
 * Create default pages on theme activation
 */
function wades_create_default_pages() {
    // Array of default pages with their content and settings
    $default_pages = array(
        'home' => array(
            'title' => 'Home',
            'template' => 'templates/home.php',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:paragraph -->
    <p>Welcome to Impact Marine Group</p>
    <!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
            'meta' => array(
                '_wp_page_template' => 'templates/home.php',
            ),
        ),
        'about' => array(
            'title' => 'About Us',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:wades/hero -->
    <div class="wp-block-wades-hero">
        <h1>About Impact Marine Group</h1>
        <p>Learn about our history and commitment to excellence</p>
    </div>
    <!-- /wp:wades/hero -->

    <!-- wp:group {"className":"container mx-auto px-4 py-12"} -->
    <div class="wp-block-group container mx-auto px-4 py-12">
        <!-- wp:heading -->
        <h2>Our Story</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>Founded with a passion for marine excellence, Impact Marine Group has been serving boat enthusiasts for years...</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
        ),
        'services' => array(
            'title' => 'Services',
            'template' => 'templates/services.php',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:wades/hero -->
    <div class="wp-block-wades-hero">
        <h1>Our Services</h1>
        <p>Comprehensive marine services for all your needs</p>
    </div>
    <!-- /wp:wades/hero -->
</div>
<!-- /wp:group -->',
            'meta' => array(
                '_wp_page_template' => 'templates/services.php',
                'service_features' => array(
                    array(
                        'icon' => '🛥️',
                        'title' => 'Boat Maintenance',
                        'description' => 'Regular maintenance and servicing to keep your boat in top condition.',
                    ),
                    array(
                        'icon' => '🔧',
                        'title' => 'Repairs',
                        'description' => 'Expert repair services for all types of marine vessels.',
                    ),
                    array(
                        'icon' => '🏷️',
                        'title' => 'Parts & Accessories',
                        'description' => 'Quality marine parts and accessories for your boat.',
                    ),
                ),
            ),
        ),
        'boats' => array(
            'title' => 'Boats',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:wades/hero -->
    <div class="wp-block-wades-hero">
        <h1>Our Boats</h1>
        <p>Explore our selection of quality boats</p>
    </div>
    <!-- /wp:wades/hero -->
</div>
<!-- /wp:group -->',
        ),
        'financing' => array(
            'title' => 'Financing',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:wades/hero -->
    <div class="wp-block-wades-hero">
        <h1>Financing Options</h1>
        <p>Flexible financing solutions for your dream boat</p>
    </div>
    <!-- /wp:wades/hero -->
</div>
<!-- /wp:group -->',
        ),
        'contact' => array(
            'title' => 'Contact',
            'template' => 'templates/contact.php',
            'content' => '<!-- wp:group {"align":"full"} -->
<div class="wp-block-group alignfull">
    <!-- wp:wades/hero -->
    <div class="wp-block-wades-hero">
        <h1>Contact Us</h1>
        <p>Get in touch with our team</p>
    </div>
    <!-- /wp:wades/hero -->
</div>
<!-- /wp:group -->',
            'meta' => array(
                '_wp_page_template' => 'templates/contact.php',
                'contact_form_shortcode' => '[contact-form-7 id="1" title="Contact form 1"]',
                'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3316.0770670822847!2d-84.5194!3d34.1016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDA2JzA1LjgiTiA4NMKwMzEnMTAuMCJX!5e0!3m2!1sen!2sus!4v1635787269281!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            ),
        ),
    );

    // Create each page
    foreach ($default_pages as $slug => $page_data) {
        // Check if the page already exists
        $existing_page = get_page_by_path($slug);
        
        if (!$existing_page) {
            // Create the page
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_name' => $slug,
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));

            // Set page template if specified
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }

            // Set any additional meta data
            if (!empty($page_data['meta'])) {
                foreach ($page_data['meta'] as $meta_key => $meta_value) {
                    update_post_meta($page_id, $meta_key, $meta_value);
                }
            }

            // Set home page
            if ($slug === 'home') {
                update_option('page_on_front', $page_id);
                update_option('show_on_front', 'page');
            }
        }
    }

    // Create primary menu
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        
        // Add pages to menu
        foreach ($default_pages as $slug => $page_data) {
            $page = get_page_by_path($slug);
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page_data['title'],
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                ));
            }
        }

        // Assign menu to primary location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['menu-1'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Set up some default theme settings
    if (!get_option('wades_company_phone')) {
        update_option('wades_company_phone', '(770) 881-7808');
    }
    if (!get_option('wades_company_email')) {
        update_option('wades_company_email', 'info@impactmarinegroup.com');
    }
    if (!get_option('wades_company_address')) {
        update_option('wades_company_address', '123 Marina Way, Lake Lanier, GA 30506');
    }
    if (!get_option('wades_business_hours')) {
        update_option('wades_business_hours', "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed");
    }
}

// Hook into theme activation
add_action('after_switch_theme', 'wades_create_default_pages'); 