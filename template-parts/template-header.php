<?php
/**
 * Template part for displaying page headers
 *
 * @package wades
 */

$title = get_the_title();
$description = '';
$show_breadcrumbs = true;

// Get custom meta if available
if (is_page()) {
    // Get template-specific meta
    $template = get_page_template_slug();
    switch ($template) {
        case 'templates/boats.php':
            $title = wades_get_meta('boats_title') ?: $title;
            $description = wades_get_meta('boats_description');
            break;
        case 'templates/services.php':
            $title = wades_get_meta('services_title') ?: $title;
            $description = wades_get_meta('services_description');
            break;
        case 'templates/about.php':
            $title = wades_get_meta('about_title') ?: $title;
            $description = wades_get_meta('about_description');
            break;
    }
} elseif (is_singular('post')) {
    // For blog posts
    $show_breadcrumbs = true;
    $description = has_excerpt() ? get_the_excerpt() : '';
} elseif (is_home()) {
    // For blog index
    $title = get_the_title(get_option('page_for_posts'));
    $description = 'Stay updated with our latest news and insights.';
} elseif (is_archive()) {
    // For archives
    $title = get_the_archive_title();
    $description = get_the_archive_description();
}
?>

<div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white py-16 md:py-24 relative overflow-hidden">
    <?php if ($show_breadcrumbs) : ?>
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-300">
                    <li>
                        <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a>
                    </li>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    <?php if (is_singular('post')) : ?>
                        <li>
                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="hover:text-white transition-colors">Blog</a>
                        </li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <li class="text-white font-medium truncate max-w-[200px]"><?php echo $title; ?></li>
                    <?php else : ?>
                        <li class="text-white font-medium"><?php echo $title; ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    <?php endif; ?>

    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6"><?php echo $title; ?></h1>
        <?php if ($description) : ?>
            <div class="max-w-3xl">
                <p class="text-xl text-gray-200"><?php echo $description; ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
            </pattern>
            <rect width="100" height="100" fill="url(#grid)"/>
        </svg>
    </div>
</div> 