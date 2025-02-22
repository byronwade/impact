<?php
/**
 * The template for displaying single posts
 *
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <?php while (have_posts()) : the_post(); ?>
            <article class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="mb-8">
                    <ol class="flex items-center space-x-2 text-sm text-muted-foreground">
                        <li><a href="<?php echo home_url(); ?>" class="hover:text-primary">Home</a></li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <li><a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="hover:text-primary">Blog</a></li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <li class="text-primary font-medium"><?php the_title(); ?></li>
                    </ol>
                </nav>

                <!-- Post Header -->
                <header class="single-post__header">
                    <div class="single-post__meta">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <span>•</span>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category_links = array();
                            foreach ($categories as $category) {
                                $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="hover:text-primary">' . esc_html($category->name) . '</a>';
                            }
                            echo implode(', ', $category_links);
                        }
                        ?>
                        <span>•</span>
                        <span><?php echo get_the_author(); ?></span>
                    </div>
                    <h1 class="single-post__title"><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <div class="single-post__excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-post__featured-image">
                        <?php the_post_thumbnail('large', array(
                            'class' => 'w-full h-auto',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="single-post__content prose">
                    <?php the_content(); ?>
                </div>

                <!-- Tags -->
                <?php
                $tags = get_the_tags();
                if ($tags) : ?>
                    <div class="flex flex-wrap gap-2 mt-8">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="inline-flex items-center rounded-full px-3 py-1 text-sm bg-secondary text-secondary-foreground hover:bg-secondary/80 transition-colors">
                                #<?php echo $tag->name; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Related Posts -->
                <?php
                $related_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'orderby' => 'rand'
                );

                // If post has categories, use them to find related posts
                $cats = wp_get_post_categories(get_the_ID());
                if ($cats) {
                    $related_args['category__in'] = $cats;
                }

                $related_query = new WP_Query($related_args);

                if ($related_query->have_posts()) :
                ?>
                    <div class="border-t border-border pt-12 mt-12">
                        <h2 class="text-2xl font-semibold mb-8">Related Posts</h2>
                        <div class="blog-grid">
                            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
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
                                        </div>
                                        <h3 class="blog-card__title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>

                <!-- Comments -->
                <?php
                if (comments_open() || get_comments_number()) :
                    echo '<div class="border-t border-border pt-12 mt-12">';
                    comments_template();
                    echo '</div>';
                endif;
                ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
