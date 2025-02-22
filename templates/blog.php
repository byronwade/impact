<?php
/**
 * Template Name: Blog Template
 * 
 * @package wades
 */

// Get customization options
$custom_title = get_post_meta(get_the_ID(), '_blog_title', true);
$custom_description = get_post_meta(get_the_ID(), '_blog_description', true);
$custom_sub_header = get_post_meta(get_the_ID(), '_blog_sub_header', true);

// Get blog-specific options
$show_featured = get_post_meta(get_the_ID(), '_show_featured_post', true) !== 'no';
$show_categories = get_post_meta(get_the_ID(), '_show_categories', true) !== 'no';
$posts_per_page = absint(get_post_meta(get_the_ID(), '_posts_per_page', true)) ?: 9;
$layout = get_post_meta(get_the_ID(), '_blog_layout', true) ?: 'grid';

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <?php if ($show_categories) : ?>
            <!-- Categories Filter -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <?php
                $categories = get_categories();
                foreach ($categories as $category) :
                    $active_class = (get_query_var('cat') == $category->term_id) ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
                ?>
                    <a href="<?php echo get_category_link($category->term_id); ?>" 
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo $active_class; ?>">
                        <?php echo esc_html($category->name); ?>
                        <span class="ml-1 text-xs">(<?php echo $category->count; ?>)</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_featured) : ?>
            <!-- Featured Post -->
            <?php
            $featured_args = array(
                'posts_per_page' => 1,
                'meta_key' => '_is_featured_post',
                'meta_value' => '1'
            );
            $featured_query = new WP_Query($featured_args);

            if ($featured_query->have_posts()) :
                while ($featured_query->have_posts()) : $featured_query->the_post();
            ?>
            <article class="featured-post">
                <div class="featured-post__image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array(
                            'class' => 'object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    <?php endif; ?>
                </div>
                <div class="featured-post__content">
                    <span class="featured-post__badge">Featured</span>
                    <div class="featured-post__meta">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <span>•</span>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<span>' . esc_html($categories[0]->name) . '</span>';
                        }
                        ?>
                    </div>
                    <h2 class="featured-post__title">
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <div class="featured-post__excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="featured-post__link">
                        Read More
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        <?php endif; ?>

        <!-- Posts Grid -->
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged
        );

        // Exclude featured post if shown
        if ($show_featured) {
            $args['meta_query'] = array(
                array(
                    'key' => '_is_featured_post',
                    'value' => '1',
                    'compare' => '!='
                )
            );
        }

        $query = new WP_Query($args);
        ?>

        <div class="blog-grid">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
            ?>
            <article class="blog-card">
                <div class="blog-card__image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium_large', array(
                            'class' => 'object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    <?php endif; ?>
                </div>
                <div class="blog-card__content">
                    <div class="blog-card__meta">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <span>•</span>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<span>' . esc_html($categories[0]->name) . '</span>';
                        }
                        ?>
                    </div>
                    <h2 class="blog-card__title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <div class="blog-card__excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="blog-card__link">
                        Read More
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </article>
            <?php
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($query->max_num_pages > 1) : ?>
            <div class="mt-12 flex justify-center">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $query->max_num_pages,
                    'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous',
                    'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4"></i>',
                    'type' => 'list',
                    'class' => 'pagination'
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?> 