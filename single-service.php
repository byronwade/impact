<?php
/**
 * Template for displaying single services
 *
 * @package wades
 */

get_header(); ?>

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
        <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white py-16 md:py-24 relative overflow-hidden">
            <!-- Breadcrumb -->
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-300">
                        <li>
                            <a href="<?php echo home_url(); ?>" class="hover:text-white transition-colors">Home</a>
                        </li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <li>
                            <a href="<?php echo get_post_type_archive_link('service'); ?>" class="hover:text-white transition-colors">Services</a>
                        </li>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <li class="text-white font-medium truncate max-w-[200px]"><?php the_title(); ?></li>
                    </ol>
                </nav>
            </div>

            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex items-center gap-6 mb-6">
                    <?php if ($icon) : ?>
                        <div class="flex-shrink-0 bg-white/10 rounded-xl p-4">
                            <i data-lucide="<?php echo esc_attr($icon); ?>" class="h-12 w-12"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold"><?php the_title(); ?></h1>
                        <?php if ($price) : ?>
                            <p class="text-xl text-gray-200 mt-2">Starting at <?php echo esc_html($price); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (has_excerpt()) : ?>
                    <div class="max-w-3xl">
                        <p class="text-xl text-gray-200"><?php echo get_the_excerpt(); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($duration || $location_text) : ?>
                    <div class="flex gap-6 mt-8">
                        <?php if ($duration) : ?>
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="h-5 w-5 text-gray-400"></i>
                                <span class="text-gray-200"><?php echo esc_html($duration); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($location_text) : ?>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="h-5 w-5 text-gray-400"></i>
                                <span class="text-gray-200"><?php echo esc_html($location_text); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                    <rect width="100" height="100" fill="url(#grid)"/>
                </svg>
            </div>
        </div>

        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary prose-a:no-underline hover:prose-a:text-primary/80 prose-img:rounded-xl prose-strong:text-gray-900 prose-code:text-primary prose-code:bg-gray-100 prose-code:rounded prose-code:px-1 prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-ol:list-decimal prose-ul:list-disc">
                        <?php the_content(); ?>
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