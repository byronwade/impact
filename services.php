<?php
/**
 * Template Name: Services
 * 
 * This is a direct loader for the services template
 */

// Force the correct template
$template = get_template_directory() . '/templates/services.php';
if (file_exists($template)) {
    include($template);
    exit;
} else {
    // Fallback to default page template
    include(get_template_directory() . '/page.php');
} 