<?php
/**
 * Template for displaying single services
 *
 * @package wades
 */

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <?php while (have_posts()) : the_post(); 
        $icon = get_post_meta(get_the_ID(), '_service_icon', true);
        $price = get_post_meta(get_the_ID(), '_service_price', true);
        $duration = get_post_meta(get_the_ID(), '_service_duration', true);
        $location = get_post_meta(get_the_ID(), '_service_location', true);
        $features = get_post_meta(get_the_ID(), '_service_features', true);

        // Get location text
        $location_text = '';
        switch ($location) {
            case 'shop':
                $location_text = 'At Our Shop';
                break;
            case 'mobile':
                $location_text = 'Mobile Service';
                break;
            case 'both':
                $location_text = 'Shop & Mobile Service';
                break;
        }
    ?>
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aspect-w-16 aspect-h-9">
                                <?php the_post_thumbnail('full', array(
                                    'class' => 'w-full h-full object-cover',
                                )); ?>
                            </div>
                        <?php else : ?>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="<?php echo get_theme_file_uri('assets/images/default-service.jpg'); ?>" 
                                     alt="<?php echo esc_attr(get_the_title()); ?>"
                                     class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-8">
                            <article class="prose prose-lg max-w-none">
                                <div class="prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary prose-a:no-underline hover:prose-a:text-primary/80 prose-img:rounded-xl prose-strong:text-gray-900 prose-code:text-primary prose-code:bg-gray-100 prose-code:rounded prose-code:px-1 prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-ol:list-decimal prose-ul:list-disc">
                                    <?php the_content(); ?>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <?php if ($features && is_array($features)) : ?>
                        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                            <h2 class="text-xl font-semibold mb-6">Features & Benefits</h2>
                            <ul class="space-y-4">
                                <?php foreach ($features as $feature) : ?>
                                    <li class="flex items-start gap-3">
                                        <i data-lucide="check" class="h-5 w-5 text-green-500 mt-0.5"></i>
                                        <span><?php echo esc_html($feature); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Card -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-6">Schedule This Service</h2>
                        <p class="text-muted-foreground mb-6">Ready to get started? Contact us to schedule your service or request more information.</p>
                        <div class="space-y-4">
                            <a href="tel:+17708817808" 
                               class="flex items-center justify-center w-full gap-2 bg-primary text-white rounded-lg px-6 py-3 font-medium hover:bg-primary/90 transition-colors">
                                <i data-lucide="phone" class="h-5 w-5"></i>
                                Call (770) 881-7808
                            </a>
                            <a href="#" 
                               class="flex items-center justify-center w-full gap-2 border border-input rounded-lg px-6 py-3 font-medium hover:bg-accent hover:text-accent-foreground transition-colors">
                                <i data-lucide="mail" class="h-5 w-5"></i>
                                Request Information
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Services -->
            <?php
            $related_args = array(
                'post_type' => 'service',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'rand'
            );
            
            $related_services = new WP_Query($related_args);
            
            if ($related_services->have_posts()) :
            ?>
                <section class="mt-16 pt-16 border-t">
                    <h2 class="text-2xl font-semibold mb-8">Related Services</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php while ($related_services->have_posts()) : $related_services->the_post(); 
                            $r_icon = get_post_meta(get_the_ID(), '_service_icon', true);
                            $r_price = get_post_meta(get_the_ID(), '_service_price', true);
                        ?>
                            <a href="<?php the_permalink(); ?>" class="group">
                                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-center gap-4 mb-4">
                                            <?php if ($r_icon) : ?>
                                                <div class="flex-shrink-0">
                                                    <i data-lucide="<?php echo esc_attr($r_icon); ?>" class="h-8 w-8 text-primary"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h3 class="text-xl font-semibold group-hover:text-primary transition-colors"><?php the_title(); ?></h3>
                                                <?php if ($r_price) : ?>
                                                    <p class="text-sm text-muted-foreground">Starting at <?php echo esc_html($r_price); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="prose prose-sm">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?> 