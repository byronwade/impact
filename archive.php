<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wades
 */

// Redirect to blog page
$blog_page = get_option('page_for_posts');
if ($blog_page) {
    wp_redirect(get_permalink($blog_page), 301);
    exit;
}

get_header();

// Get hero settings from theme settings
$hero_title = get_option('wades_blog_hero_title', 'Latest News & Updates');
$hero_description = get_option('wades_blog_hero_description', 'Stay informed with our latest articles, tips, and industry insights.');
$hero_background_image = get_option('wades_blog_hero_image');
$hero_overlay_opacity = get_option('wades_blog_hero_opacity', '50');

// Get current archive title
$archive_title = '';
if (is_category()) {
    $archive_title = single_cat_title('Category: ', false);
} elseif (is_tag()) {
    $archive_title = single_tag_title('Tag: ', false);
} elseif (is_author()) {
    $archive_title = get_the_author();
} elseif (is_date()) {
    if (is_day()) {
        $archive_title = get_the_date();
    } elseif (is_month()) {
        $archive_title = get_the_date('F Y');
    } elseif (is_year()) {
        $archive_title = get_the_date('Y');
    }
}
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="relative py-24 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <?php if ($hero_background_image) : ?>
                <?php echo wp_get_attachment_image($hero_background_image, 'full', false, array(
                    'class' => 'absolute inset-0 w-full h-full object-cover',
                    'alt' => esc_attr($archive_title ?: $hero_title)
                )); ?>
            <?php else : ?>
                <?php echo wades_get_image_html(0, 'full', array(
                    'class' => 'absolute inset-0 w-full h-full object-cover',
                    'alt' => esc_attr($archive_title ?: $hero_title)
                )); ?>
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-r from-black/<?php echo esc_attr($hero_overlay_opacity); ?> to-black/25"></div>
        </div>

        <!-- Content -->
        <div class="container relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <?php if ($archive_title) : ?>
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-6"><?php echo esc_html($archive_title); ?></h1>
                <?php else : ?>
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-6"><?php echo esc_html($hero_title); ?></h1>
                    <p class="text-xl text-white/90"><?php echo esc_html($hero_description); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous',
                'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4"></i>',
            ));
            ?>

        <?php else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
