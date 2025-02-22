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
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow">
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
