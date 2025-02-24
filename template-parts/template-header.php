<?php
/**
 * Template part for displaying page headers
 *
 * @package wades
 */

$post_id = get_queried_object_id();
$is_blog = is_home() || is_archive();

if ($is_blog) {
    $post_id = get_option('page_for_posts');
}

// Get current template
$template = get_page_template_slug($post_id);

// Get template-specific defaults
$defaults = wades_get_template_defaults($template);

// Get meta values with defaults
$meta = array(
    'title' => get_post_meta($post_id, '_custom_header_title', true) ?: $defaults['title'],
    'description' => get_post_meta($post_id, '_custom_header_subtext', true) ?: $defaults['description'],
    'background_image' => get_post_meta($post_id, '_hero_background_image', true),
    'overlay_opacity' => get_post_meta($post_id, '_hero_overlay_opacity', true) ?: '40',
    'height' => get_post_meta($post_id, '_hero_height', true) ?: '40'
);

// Get brand logo settings for boats template
$logo_settings = array(
    'bg_style' => get_theme_mod('brand_logos_bg', 'white'),
    'mb_sports' => get_theme_mod('mb_sports_logo'),
    'viaggio' => get_theme_mod('viaggio_logo')
);

// Build logo classes
$logo_classes = 'h-10 object-contain p-2 rounded';
switch ($logo_settings['bg_style']) {
    case 'white':
        $logo_classes .= ' bg-white';
        break;
    case 'dark':
        $logo_classes .= ' bg-gray-900';
        break;
    case 'transparent':
        // No additional background class
        break;
}
?>

<header class="relative overflow-hidden" style="height: <?php echo esc_attr($meta['height']); ?>vh;">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <?php if ($meta['background_image']) : ?>
            <div class="absolute inset-0 w-full h-full">
                <?php echo wades_get_image_html($meta['background_image'], 'full', array(
                    'class' => 'absolute inset-0 w-full h-full object-cover object-center',
                    'style' => 'object-position: center center;',
                    'alt' => esc_attr($meta['title'])
                )); ?>
            </div>
        <?php else : ?>
            <div class="absolute inset-0 bg-gradient-to-r from-primary to-primary-dark"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo esc_attr($meta['overlay_opacity'] / 100); ?>"></div>
    </div>

    <!-- Content -->
    <div class="relative h-full flex flex-col justify-center px-6 sm:px-12">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col justify-center h-full">
                <h1 class="text-white text-4xl sm:text-6xl font-bold mb-4 leading-tight max-w-3xl">
                    <?php echo esc_html($meta['title']); ?>
                </h1>
                <?php if ($meta['description']) : ?>
                    <p class="text-gray-200 text-xl sm:text-2xl mb-8 max-w-2xl">
                        <?php echo wp_kses_post($meta['description']); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Brand Logos -->
                <?php if ($template === 'templates/boats.php') : 
                    // Get brand logos from theme mods
                    $mb_sports_logo = get_theme_mod('mb_sports_logo');
                    $viaggio_logo = get_theme_mod('viaggio_logo');
                    
                    if ($mb_sports_logo || $viaggio_logo) : ?>
                        <div class="flex items-center space-x-6 mt-8">
                            <?php if ($mb_sports_logo) : ?>
                                <?php echo wades_get_image_html($mb_sports_logo, 'full', array(
                                    'class' => $logo_classes,
                                    'alt' => 'MB Sports Logo',
                                    'width' => '120',
                                    'height' => '40'
                                )); ?>
                            <?php endif; ?>

                            <?php if ($viaggio_logo) : ?>
                                <?php echo wades_get_image_html($viaggio_logo, 'full', array(
                                    'class' => $logo_classes,
                                    'alt' => 'Viaggio Pontoon Boats Logo',
                                    'width' => '120',
                                    'height' => '40'
                                )); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- Optional Offset Content -->
<div class="bg-gray-50 relative z-20"></div>
<div class="relative z-20 -mt-12 bg-gray-50"></div> 