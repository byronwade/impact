<?php
/**
 * Template Name: Blog Template
 * 
 * @package wades
 */

// Get all blog-specific options with defaults
$meta = wades_get_blog_meta();

// Get current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">

        <?php if ($meta['show_categories']) : ?>
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

        <?php if ($meta['show_featured']) : ?>
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
            <article class="featured-post bg-white rounded-xl shadow-lg overflow-hidden mb-12">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="relative aspect-w-16 aspect-h-9">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array(
                                'class' => 'object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
                        <?php else : ?>
                            <?php echo wades_get_image_html(0, 'large', array(
                                'class' => 'object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
                        <?php endif; ?>
                    </div>
                    <div class="p-8 flex flex-col justify-center">
                        <div class="inline-flex items-center rounded-full bg-primary/10 text-primary px-3 py-1 text-sm font-medium mb-4">
                            Featured Post
                        </div>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<span>•</span><span>' . esc_html($categories[0]->name) . '</span>';
                            }
                            ?>
                        </div>
                        <h2 class="text-2xl font-bold mb-4">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div class="text-muted-foreground mb-6">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" 
                           class="inline-flex items-center text-primary hover:text-primary/80 font-medium">
                            Read More
                            <i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
                        </a>
                    </div>
                </div>
            </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        <?php endif; ?>

        <!-- Posts Grid -->
        <div class="grid <?php echo esc_attr($meta['grid_columns']); ?> gap-8">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $meta['posts_per_page'],
                'paged' => $paged
            );

            if ($meta['show_featured']) {
                $args['meta_query'] = array(
                    array(
                        'key' => '_is_featured_post',
                        'value' => '1',
                        'compare' => '!='
                    )
                );
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    // Get estimated read time if enabled
                    $read_time = '';
                    if ($meta['show_read_time']) {
                        $content = get_the_content();
                        $word_count = str_word_count(strip_tags($content));
                        $read_time = ceil($word_count / 200); // Assuming 200 words per minute
                    }
            ?>
            <article class="blog-card bg-white rounded-xl overflow-hidden <?php echo esc_attr($meta['card_style']); ?> hover:<?php echo esc_attr($meta['hover_effect']); ?>">
                <a href="<?php the_permalink(); ?>" class="block">
                    <div class="relative aspect-w-16 aspect-h-9">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array(
                                'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
                        <?php else : ?>
                            <?php echo wades_get_image_html(0, 'medium_large', array(
                                'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
                        <?php if ($meta['show_date']) : ?>
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <?php endif; ?>

                        <?php if ($meta['show_author']) : ?>
                            <?php if ($meta['show_date']) echo '<span>•</span>'; ?>
                            <span><?php the_author(); ?></span>
                        <?php endif; ?>

                        <?php
                        if ($meta['show_categories']) {
                            $categories = get_the_category();
                            if ($categories) {
                                if ($meta['show_date'] || $meta['show_author']) echo '<span>•</span>';
                                echo '<span>' . esc_html($categories[0]->name) . '</span>';
                            }
                        }
                        ?>

                        <?php if ($meta['show_read_time'] && $read_time) : ?>
                            <span>•</span>
                            <span><?php echo esc_html($read_time); ?> min read</span>
                        <?php endif; ?>
                    </div>

                    <h2 class="text-xl font-semibold mb-4">
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <?php if ($meta['show_excerpt']) : ?>
                        <div class="text-muted-foreground mb-6">
                            <?php echo wp_trim_words(get_the_excerpt(), $meta['excerpt_length']); ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" 
                       class="inline-flex items-center text-primary hover:text-primary/80 font-medium">
                        Read More
                        <i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
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
            <div class="mt-12">
                <?php if ($meta['pagination_style'] === 'numbered') : ?>
                    <div class="flex justify-center">
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
                <?php elseif ($meta['pagination_style'] === 'load-more') : ?>
                    <div class="text-center">
                        <button type="button" id="load-more" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                            <?php echo esc_html($meta['load_more_text']); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
/* Card Styles */
.blog-card {
    transition: all 0.2s ease-in-out;
}

.blog-card.default {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.blog-card.minimal {
    box-shadow: none;
    border: 1px solid #e5e7eb;
}

.blog-card.bordered {
    border: 2px solid #e5e7eb;
}

.blog-card.featured {
    border: 2px solid #0f766e;
    box-shadow: 0 4px 6px -1px rgb(15 118 110 / 0.1), 0 2px 4px -2px rgb(15 118 110 / 0.1);
}

/* Hover Effects */
.blog-card.hover\:scale:hover {
    transform: scale(1.02);
}

.blog-card.hover\:lift:hover {
    transform: translateY(-4px);
}

.blog-card.hover\:glow:hover {
    box-shadow: 0 0 20px rgba(15, 118, 110, 0.2);
}

/* Pagination Styles */
.pagination {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
}

.pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.pagination a.page-numbers {
    color: #374151;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
}

.pagination a.page-numbers:hover {
    background-color: #f3f4f6;
}

.pagination .current {
    color: #ffffff;
    background-color: #0f766e;
}

.pagination .dots {
    color: #6b7280;
}

/* Load More Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading #load-more {
    position: relative;
    color: transparent;
}

.loading #load-more::after {
    content: '';
    position: absolute;
    width: 1rem;
    height: 1rem;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    <?php if ($meta['pagination_style'] === 'load-more' || $meta['pagination_style'] === 'infinite') : ?>
    // Load More functionality
    let currentPage = <?php echo $paged; ?>;
    const maxPages = <?php echo $query->max_num_pages; ?>;
    const loadMoreBtn = document.getElementById('load-more');
    const postsGrid = document.querySelector('.grid');

    <?php if ($meta['pagination_style'] === 'load-more') : ?>
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', loadMorePosts);
    }
    <?php endif; ?>

    <?php if ($meta['pagination_style'] === 'infinite') : ?>
    // Infinite scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && currentPage < maxPages) {
                loadMorePosts();
            }
        });
    });

    if (postsGrid) {
        observer.observe(postsGrid.lastElementChild);
    }
    <?php endif; ?>

    function loadMorePosts() {
        if (currentPage >= maxPages) return;

        const button = document.getElementById('load-more');
        if (button) button.classList.add('loading');

        currentPage++;
        fetch(`<?php echo admin_url('admin-ajax.php'); ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=load_more_posts&paged=${currentPage}&nonce=<?php echo wp_create_nonce('load_more_posts'); ?>`
        })
        .then(response => response.text())
        .then(html => {
            if (button) button.classList.remove('loading');
            if (html) {
                postsGrid.insertAdjacentHTML('beforeend', html);
                lucide.createIcons();
                
                <?php if ($meta['pagination_style'] === 'infinite') : ?>
                if (currentPage < maxPages) {
                    observer.observe(postsGrid.lastElementChild);
                }
                <?php endif; ?>
            }
            if (currentPage >= maxPages && button) {
                button.style.display = 'none';
            }
        });
    }
    <?php endif; ?>
});
</script>

<?php get_footer(); ?> 