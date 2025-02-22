<?php
/**
 * The template for displaying blog posts
 *
 * @package wades
 */

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
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

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    // Skip if this is the featured post
                    if (get_post_meta(get_the_ID(), '_is_featured_post', true)) {
                        continue;
                    }
            ?>
            <article class="blog-card bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <a href="<?php the_permalink(); ?>" class="block">
                    <div class="relative aspect-w-16 aspect-h-9">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array(
                                'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<span>•</span><span>' . esc_html($categories[0]->name) . '</span>';
                        }
                        ?>
                    </div>
                    <h2 class="text-xl font-semibold mb-4">
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
            </article>
            <?php
                endwhile;
            endif;
            ?>
        </div>

        <!-- Pagination -->
        <?php if ($wp_query->max_num_pages > 1) : ?>
        <div class="mt-12 pb-12">
            <div class="flex justify-center gap-2">
                <?php
                $pagination = paginate_links(array(
                    'prev_text' => '<i data-lucide="arrow-left" class="h-5 w-5 text-gray-700"></i>',
                    'next_text' => '<i data-lucide="arrow-right" class="h-5 w-5 text-gray-700"></i>',
                    'type' => 'array'
                ));

                if ($pagination) :
                    echo '<nav class="inline-flex items-center gap-2" aria-label="Pagination">';
                    
                    foreach ($pagination as $key => $page_link) : 
                        // Convert the link to a DOMDocument to extract href and class
                        $doc = new DOMDocument();
                        @$doc->loadHTML(mb_convert_encoding($page_link, 'HTML-ENTITIES', 'UTF-8'));
                        $tags = $doc->getElementsByTagName('a');
                        $spans = $doc->getElementsByTagName('span');
                        
                        $is_current = strpos($page_link, 'current') !== false;
                        $is_dots = strpos($page_link, 'dots') !== false;
                        
                        if ($is_dots) {
                            echo '<span class="px-2 text-gray-400">...</span>';
                            continue;
                        }
                        
                        $classes = 'relative inline-flex items-center justify-center min-w-[40px] h-10 text-sm font-medium transition-all duration-200';
                        
                        if ($is_current) {
                            $classes .= ' z-10 bg-primary text-white rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2';
                        } else {
                            $classes .= ' text-gray-700 hover:bg-gray-50 rounded-md border border-gray-300 bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2';
                        }
                        
                        // If it's a link
                        if ($tags->length > 0) {
                            $href = $tags->item(0)->getAttribute('href');
                            $content = strip_tags($page_link);
                            // Only show numbers for page links, not for prev/next
                            if (strpos($content, 'Previous') !== false || strpos($content, 'Next') !== false) {
                                echo '<a href="' . esc_url($href) . '" class="' . esc_attr($classes) . '">' . $content . '</a>';
                            } else {
                                echo '<a href="' . esc_url($href) . '" class="' . esc_attr($classes) . '">' . $content . '</a>';
                            }
                        } 
                        // If it's the current page (span)
                        else if ($spans->length > 0) {
                            echo '<span class="' . esc_attr($classes) . '">' . strip_tags($page_link) . '</span>';
                        }
                    endforeach;
                    
                    echo '</nav>';
                endif;
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
});
</script>

<?php get_footer(); ?> 