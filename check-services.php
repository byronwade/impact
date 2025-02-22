<?php
// Get the services page
$services_page = get_page_by_path('services');

if ($services_page) {
    $template = get_post_meta($services_page->ID, '_wp_page_template', true);
    echo "Services page found (ID: {$services_page->ID})\n";
    echo "Template: {$template}\n";
} else {
    echo "Services page not found. Creating it now...\n";
    
    // Create the services page
    $page_data = array(
        'post_title'    => 'Services',
        'post_name'     => 'services',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '<!-- wp:paragraph --><p>Our comprehensive marine services.</p><!-- /wp:paragraph -->'
    );
    
    $page_id = wp_insert_post($page_data);
    
    if (!is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'templates/services.php');
        echo "Services page created successfully with ID: {$page_id}\n";
    } else {
        echo "Error creating services page: " . $page_id->get_error_message() . "\n";
    }
} 