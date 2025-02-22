<?php
/**
 * The template for displaying single posts
 *
 * @package wades
 */

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-24">
        <?php while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative aspect-video w-full overflow-hidden">
                        <?php the_post_thumbnail('full', array(
                            'class' => 'object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <div class="p-8">
                    <!-- Meta Information -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground mb-6">
                        <time datetime="<?php echo get_the_date('c'); ?>" class="flex items-center">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                            <?php echo get_the_date(); ?>
                        </time>
                        <?php if (get_the_category()) : ?>
                            <div class="flex items-center">
                                <i data-lucide="folder" class="w-4 h-4 mr-2"></i>
                                <?php the_category(', '); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (get_the_tags()) : ?>
                            <div class="flex items-center flex-wrap gap-2">
                                <i data-lucide="tag" class="w-4 h-4"></i>
                                <?php the_tags('<div class="flex flex-wrap gap-2">', ', ', '</div>'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl font-bold mb-8"><?php the_title(); ?></h1>

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none mb-12">
                        <?php the_content(); ?>
                    </div>

                    <!-- Author Box -->
                    <div class="border-t border-gray-200 pt-8 mt-8">
                        <div class="flex items-center gap-4">
                            <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', array(
                                'class' => 'rounded-full'
                            )); ?>
                            <div>
                                <h3 class="text-lg font-semibold">
                                    <?php echo get_the_author_meta('display_name'); ?>
                                </h3>
                                <?php if (get_the_author_meta('description')) : ?>
                                    <p class="text-muted-foreground">
                                        <?php echo get_the_author_meta('description'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-12">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <?php if ($prev_post) : ?>
                    <a href="<?php echo get_permalink($prev_post); ?>" 
                       class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Previous Post
                    </a>
                <?php endif; ?>
                <?php if ($next_post) : ?>
                    <a href="<?php echo get_permalink($next_post); ?>" 
                       class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors border border-input bg-background hover:bg-accent hover:text-accent-foreground">
                        Next Post
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Comments -->
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

            <!-- Related Posts -->
            <?php
            $categories = get_the_category();
            if ($categories) :
                $category_ids = array();
                foreach ($categories as $category) {
                    $category_ids[] = $category->term_id;
                }
                
                $related_query = new WP_Query(array(
                    'category__in' => $category_ids,
                    'post__not_in' => array(get_the_ID()),
                    'posts_per_page' => 3,
                    'orderby' => 'rand'
                ));

                if ($related_query->have_posts()) :
            ?>
                <div class="mt-16">
                    <h2 class="text-2xl font-bold mb-8">Related Posts</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                            <article class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <div class="relative aspect-video">
                                            <?php the_post_thumbnail('medium_large', array(
                                                'class' => 'object-cover w-full h-full',
                                                'alt' => get_the_title()
                                            )); ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-4">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <div class="text-muted-foreground mb-4">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" 
                                       class="inline-flex items-center text-primary hover:text-primary/80 font-medium">
                                        Read More
                                        <i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; endif; ?>
        <?php endwhile; ?>
    </div>
</main>

<!-- Schema.org Article Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo esc_html(get_the_title()); ?>",
    "datePublished": "<?php echo get_the_date('c'); ?>",
    "dateModified": "<?php echo get_the_modified_date('c'); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_html(get_the_author()); ?>"
    },
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
