<?php
/**
 * Template part for displaying page headers
 *
 * @package wades
 */

$title = get_the_title();
$description = '';
$background_image = get_post_meta(get_the_ID(), '_hero_background_image', true);
$show_breadcrumbs = true;
$sub_header = '';

// Get template-specific meta
$template = get_page_template_slug();
switch ($template) {
    case 'templates/about.php':
        $title = get_post_meta(get_the_ID(), '_about_title', true) ?: $title;
        $description = get_post_meta(get_the_ID(), '_about_description', true) ?: 'Impact Marine Group is dedicated to providing our customers personal service and quality boat brands.';
        $sub_header = get_post_meta(get_the_ID(), '_about_sub_header', true);
        break;
    case 'templates/services.php':
        $title = get_post_meta(get_the_ID(), '_services_title', true) ?: $title;
        $description = get_post_meta(get_the_ID(), '_services_description', true);
        $sub_header = get_post_meta(get_the_ID(), '_services_sub_header', true);
        break;
    case 'templates/boats.php':
        $title = get_post_meta(get_the_ID(), '_boats_title', true) ?: 'Our Boat Inventory';
        $description = get_post_meta(get_the_ID(), '_boats_description', true) ?: 'Discover our extensive collection of new and used boats.';
        $sub_header = get_post_meta(get_the_ID(), '_boats_sub_header', true);
        break;
    case 'templates/blog.php':
        $title = get_post_meta(get_the_ID(), '_blog_title', true) ?: 'Latest News & Updates';
        $description = get_post_meta(get_the_ID(), '_blog_description', true) ?: 'Stay updated with our latest news and insights.';
        $sub_header = get_post_meta(get_the_ID(), '_blog_sub_header', true);
        break;
    case 'templates/contact.php':
        $title = get_post_meta(get_the_ID(), '_contact_title', true) ?: 'Get in Touch';
        $description = get_post_meta(get_the_ID(), '_contact_description', true) ?: 'Have questions? We\'d love to hear from you.';
        $sub_header = get_post_meta(get_the_ID(), '_contact_sub_header', true);
        break;
    case 'templates/financing.php':
        $title = get_post_meta(get_the_ID(), '_financing_title', true) ?: 'Financing Options';
        $description = get_post_meta(get_the_ID(), '_financing_description', true) ?: 'Flexible financing solutions for your dream boat.';
        $sub_header = get_post_meta(get_the_ID(), '_financing_sub_header', true);
        break;
}
?>

<!-- Page Header -->
<section class="relative overflow-hidden bg-gradient-to-b from-gray-900 to-gray-800">
    <!-- Background Pattern -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 mix-blend-multiply"></div>
        <?php if ($background_image) : ?>
            <img src="<?php echo esc_url(wp_get_attachment_image_url($background_image, 'full')); ?>" 
                 alt="" 
                 class="absolute inset-0 h-full w-full object-cover opacity-20">
        <?php endif; ?>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-30"></div>
    </div>

    <!-- Content -->
    <div class="relative">
        <?php if ($show_breadcrumbs) : ?>
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-300">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a>
                        </li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <?php if (is_singular('post') || is_post_type_archive('post')) : ?>
                            <li>
                                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="hover:text-white transition-colors">Blog</a>
                            </li>
                            <?php if (is_singular('post')) : ?>
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                <li class="text-white font-medium truncate max-w-[200px]"><?php echo get_the_title(); ?></li>
                            <?php endif; ?>
                        <?php else : ?>
                            <li class="text-white font-medium"><?php echo get_the_title(); ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
        <?php endif; ?>

        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <?php if ($sub_header) : ?>
                    <div class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-white/10 text-white/90 backdrop-blur-sm mb-6">
                        <?php echo esc_html($sub_header); ?>
                    </div>
                <?php endif; ?>
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    <?php echo esc_html($title); ?>
                </h1>
                <?php if ($description) : ?>
                    <p class="mt-6 text-lg leading-8 text-gray-300">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Optional Offset Content -->
<div class="relative z-10 -mt-12"></div> 