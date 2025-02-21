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
                <header class="mb-8">
                    <div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
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
                    <h1 class="text-4xl font-bold mb-6"><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <div class="text-xl text-muted-foreground mb-6">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative aspect-w-16 aspect-h-9 mb-8 rounded-xl overflow-hidden">
                        <?php the_post_thumbnail('large', array(
                            'class' => 'object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary prose-a:no-underline hover:prose-a:text-primary/80 prose-img:rounded-xl prose-strong:text-gray-900 prose-code:text-primary prose-code:bg-gray-100 prose-code:rounded prose-code:px-1 prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-ol:list-decimal prose-ul:list-disc mb-12">
                    <?php the_content(); ?>
                </div>

                <!-- Tags -->
                <?php
                $tags = get_the_tags();
                if ($tags) : ?>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="inline-flex items-center rounded-full px-3 py-1 text-sm bg-secondary text-secondary-foreground hover:bg-secondary/80 transition-colors">
                                #<?php echo $tag->name; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Author Box -->
                <div class="bg-white rounded-xl shadow-md p-8 mb-12">
                    <div class="flex items-center gap-6">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'rounded-full')); ?>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">About <?php echo get_the_author(); ?></h3>
                            <p class="text-muted-foreground mb-4"><?php echo get_the_author_meta('description'); ?></p>
                            <div class="flex gap-4">
                                <?php
                                $author_website = get_the_author_meta('user_url');
                                if ($author_website) : ?>
                                    <a href="<?php echo esc_url($author_website); ?>" class="text-sm text-primary hover:text-primary/80" target="_blank" rel="noopener noreferrer">
                                        <i data-lucide="globe" class="w-5 h-5"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                <?php
                $categories = get_the_category();
                if ($categories) {
                    $category_ids = array();
                    foreach ($categories as $category) {
                        $category_ids[] = $category->term_id;
                    }
                    
                    $related_args = array(
                        'category__in' => $category_ids,
                        'post__not_in' => array(get_the_ID()),
                        'posts_per_page' => 3,
                        'orderby' => 'rand'
                    );
                    
                    $related_query = new WP_Query($related_args);
                    
                    if ($related_query->have_posts()) : ?>
                        <div class="border-t border-border pt-12">
                            <h2 class="text-2xl font-semibold mb-8">Related Posts</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                    <article class="bg-white rounded-xl shadow-md overflow-hidden group">
                                        <div class="relative aspect-w-16 aspect-h-9">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('medium', array(
                                                    'class' => 'object-cover w-full h-full transition-transform duration-300 group-hover:scale-105',
                                                    'alt' => get_the_title()
                                                )); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-6">
                                            <time datetime="<?php echo get_the_date('c'); ?>" class="text-sm text-muted-foreground">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                            <h3 class="text-lg font-semibold mt-2 group-hover:text-primary transition-colors">
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
                }
                ?>

                <!-- Comments -->
                <?php
                if (comments_open() || get_comments_number()) :
                    echo '<div class="border-t border-border pt-12">';
                    comments_template();
                    echo '</div>';
                endif;
                ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
