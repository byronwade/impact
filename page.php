<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wades
 */

global $post;

// Direct template handling for services page
if ($post && $post->post_name === 'services') {
    $services_template = get_template_directory() . '/templates/services.php';
    if (file_exists($services_template)) {
        include($services_template);
        exit;
    }
}

// Handle other page templates
$template = get_page_template_slug();
if ($template && file_exists(get_template_directory() . '/' . $template)) {
    include(get_template_directory() . '/' . $template);
    exit;
}

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-24">
        <?php while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative aspect-video w-full overflow-hidden">
                        <?php the_post_thumbnail('full', array(
                            'class' => 'object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>

                <div class="p-8">
                    <h1 class="text-4xl font-bold mb-8"><?php the_title(); ?></h1>

                    <div class="prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links mt-8 flex gap-2">',
                        'after' => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after' => '</span>',
                    ));
                    ?>
                </div>
            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                echo '<div class="mt-12">';
                comments_template();
                echo '</div>';
            endif;
            ?>
        <?php endwhile; ?>
    </div>
</main>

<!-- Schema.org WebPage Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "<?php echo esc_html(get_the_title()); ?>",
    "description": "<?php echo esc_html(get_the_excerpt()); ?>",
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo esc_html(get_bloginfo('name')); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url(get_site_icon_url()); ?>"
        }
    }
    <?php if (has_post_thumbnail()) : ?>
    ,"image": {
        "@type": "ImageObject",
        "url": "<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>"
    }
    <?php endif; ?>
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
});
</script>

<?php get_footer(); ?>
