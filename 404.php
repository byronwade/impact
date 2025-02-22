<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package wades
 */

get_header();
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <!-- 404 Icon -->
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-primary/10 mb-8">
                <i data-lucide="alert-circle" class="w-12 h-12 text-primary"></i>
            </div>

            <h1 class="text-4xl font-bold mb-4">Page Not Found</h1>
            <p class="text-xl text-muted-foreground mb-8">Sorry, we couldn't find the page you're looking for.</p>

            <!-- Search Form -->
            <div class="max-w-md mx-auto mb-12">
                <form role="search" method="get" class="search-form relative" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" 
                           class="w-full pl-12 rounded-lg border border-input bg-background px-3 py-4" 
                           placeholder="Search..." 
                           value="<?php echo get_search_query(); ?>" 
                           name="s">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-muted-foreground"></i>
                </form>
            </div>

            <!-- Helpful Links -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <a href="<?php echo esc_url(home_url('/')); ?>" 
                   class="flex flex-col items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="home" class="w-8 h-8 mb-3 text-primary"></i>
                    <span class="font-medium">Homepage</span>
                </a>
                <a href="<?php echo esc_url(home_url('/boats')); ?>" 
                   class="flex flex-col items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="anchor" class="w-8 h-8 mb-3 text-primary"></i>
                    <span class="font-medium">Our Boats</span>
                </a>
                <a href="<?php echo esc_url(home_url('/services')); ?>" 
                   class="flex flex-col items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="tool" class="w-8 h-8 mb-3 text-primary"></i>
                    <span class="font-medium">Services</span>
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" 
                   class="flex flex-col items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <i data-lucide="phone" class="w-8 h-8 mb-3 text-primary"></i>
                    <span class="font-medium">Contact Us</span>
                </a>
            </div>

            <!-- Recent Posts -->
            <?php
            $recent_posts = get_posts(array(
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));

            if ($recent_posts) : ?>
                <div class="text-left">
                    <h2 class="text-2xl font-bold mb-6">Latest Articles</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <?php foreach ($recent_posts as $post) : 
                            setup_postdata($post); ?>
                            <article class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
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
                                    <h3 class="font-semibold mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <time datetime="<?php echo get_the_date('c'); ?>" class="text-sm text-muted-foreground">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </div>
                            </article>
                        <?php endforeach; 
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
});
</script>

<?php get_footer(); ?>
