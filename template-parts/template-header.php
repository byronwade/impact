<?php
/**
 * Template part for displaying page headers
 *
 * @package wades
 */

// Get page-specific content
$page_title = get_the_title();
$page_excerpt = has_excerpt() ? get_the_excerpt() : '';

// Get customization options from theme mods
$background_image = get_post_meta(get_the_ID(), '_hero_background_image', true) ?: get_theme_mod('default_hero_background');
$overlay_opacity = get_theme_mod('hero_overlay_opacity', '40');
$header_height = get_theme_mod('hero_height', '70');
?>

<header class="relative overflow-hidden" style="height: <?php echo esc_attr($header_height); ?>vh;">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <?php if ($background_image) : ?>
            <?php echo wp_get_attachment_image($background_image, 'full', false, array(
                'class' => 'h-full w-full object-cover object-center',
            )); ?>
        <?php else : ?>
            <img src="<?php echo get_theme_file_uri('assets/images/default-hero.jpg'); ?>" 
                 alt="" 
                 class="h-full w-full object-cover object-center">
        <?php endif; ?>
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo esc_attr($overlay_opacity / 100); ?>"></div>
    </div>

    <!-- Content -->
    <div class="relative h-full flex flex-col justify-center px-6 sm:px-12">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col justify-center h-full">
                <h1 class="text-white text-4xl sm:text-6xl font-bold mb-4 leading-tight max-w-3xl">
                    <?php echo esc_html($page_title); ?>
                </h1>
                <?php if ($page_excerpt) : ?>
                    <p class="text-gray-200 text-xl sm:text-2xl mb-8 max-w-2xl mx-auto">
                        <?php echo esc_html($page_excerpt); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Brand Logos -->
                <?php if (get_theme_mod('show_brand_logos', true)) : 
                    $logo_bg_style = get_theme_mod('brand_logos_bg', 'white');
                    $logo_classes = 'h-10 object-contain p-2 rounded';
                    
                    // Add background color class based on setting
                    switch ($logo_bg_style) {
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
                    <div class="flex items-center space-x-6 mt-8">
                        <?php
                        // MB Sports Logo
                        $mb_sports_logo = get_theme_mod('mb_sports_logo');
                        if ($mb_sports_logo) : ?>
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($mb_sports_logo, 'full')); ?>"
                                 alt="MB Sports Logo"
                                 class="<?php echo esc_attr($logo_classes); ?>"
                                 width="120"
                                 height="40">
                        <?php endif; ?>

                        <?php
                        // Viaggio Logo
                        $viaggio_logo = get_theme_mod('viaggio_logo');
                        if ($viaggio_logo) : ?>
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($viaggio_logo, 'full')); ?>"
                                 alt="Viaggio Pontoon Boats Logo"
                                 class="<?php echo esc_attr($logo_classes); ?>"
                                 width="120"
                                 height="40">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- Optional Offset Content -->
<div class="bg-gray-50 relative z-20"></div>
<div class="relative z-20 -mt-12 bg-gray-50"></div> 