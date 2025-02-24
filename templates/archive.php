<?php
/**
 * The template for displaying archive pages
 *
 * @package wades
 */

get_header();

// Get hero settings from theme options
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
                    'alt' => esc_attr($hero_title)
                )); ?>
            <?php else : ?>
                <?php echo wades_get_image_html(0, 'full', array(
                    'class' => 'absolute inset-0 w-full h-full object-cover',
                    'alt' => esc_attr($hero_title)
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

    <!-- Posts Grid -->
    <section class="py-16">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <?php if (have_posts()) : ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('bg-white rounded-xl overflow-hidden shadow-lg transition-transform hover:translate-y-[-4px]'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="aspect-video overflow-hidden">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                                </div>
                            <?php endif; ?>
                            <div class="p-6">
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <?php echo get_the_date(); ?>
                                </div>
                                <h2 class="text-xl font-semibold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                <p class="text-gray-600 mb-4"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                    Read More
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php if (get_the_posts_pagination()) : ?>
                    <div class="mt-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => '<i data-lucide="chevron-left" class="w-5 h-5"></i>',
                            'next_text' => '<i data-lucide="chevron-right" class="w-5 h-5"></i>',
                            'class' => 'flex justify-center gap-2'
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                        <i data-lucide="file-text" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-semibold mb-2">No Posts Found</h2>
                    <p class="text-gray-600">It seems we can't find what you're looking for.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Initialize Lucide icons -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php get_footer(); ?> 