<?php
/**
 * Template Name: Home Template
 * 
 * @package wades
 */

get_header();

// Get sections configuration
$sections = get_post_meta(get_the_ID(), '_home_sections', true);

// Initialize hero meta with defaults
$hero_meta = array(
    'backgroundVideo' => array('url' => get_post_meta(get_the_ID(), '_hero_background_video', true)),
    'backgroundImage' => array('url' => get_post_meta(get_the_ID(), '_hero_background_image', true)),
    'title' => get_post_meta(get_the_ID(), '_hero_title', true) ?: 'Expert Boat Service You Can Trust',
    'description' => get_post_meta(get_the_ID(), '_hero_description', true) ?: 'Certified Technicians, Fast Turnaround, and Unmatched Care for Your Boat.',
    'phoneNumber' => get_post_meta(get_the_ID(), '_hero_phone_number', true) ?: '(770) 881-7808',
    'rating' => array(
        'text' => get_post_meta(get_the_ID(), '_hero_rating_text', true) ?: '4.9 Star Rated',
        'value' => get_post_meta(get_the_ID(), '_hero_rating_value', true) ?: 4.9
    ),
    'primaryCta' => array(
        'label' => get_post_meta(get_the_ID(), '_hero_primary_cta_label', true) ?: 'Schedule Service',
        'link' => get_post_meta(get_the_ID(), '_hero_primary_cta_link', true) ?: '/schedule',
        'icon' => get_post_meta(get_the_ID(), '_hero_primary_cta_icon', true) ?: 'calendar'
    ),
    'secondaryCta' => array(
        'label' => get_post_meta(get_the_ID(), '_hero_secondary_cta_label', true) ?: 'View Our Services',
        'link' => get_post_meta(get_the_ID(), '_hero_secondary_cta_link', true) ?: '/services',
        'icon' => get_post_meta(get_the_ID(), '_hero_secondary_cta_icon', true) ?: 'chevron-right'
    )
);

// Initialize CTA section variables with defaults
$cta_title = get_post_meta(get_the_ID(), '_cta_title', true) ?: 'Ready to Experience the Best in Boating?';
$cta_description = get_post_meta(get_the_ID(), '_cta_description', true) ?: 'Join the Impact Marine family and discover why we\'re Georgia\'s premier boat dealer.';
$cta_button_text = get_post_meta(get_the_ID(), '_cta_button_text', true) ?: 'Contact Us Today';
$cta_button_link = get_post_meta(get_the_ID(), '_cta_button_link', true) ?: '/contact';

// Default sections configuration if not set
if (!is_array($sections)) {
    $sections = array(
        'hero' => array(
            'enabled' => true,
            'order' => 10,
            'title' => 'Hero Section'
        ),
        'featured_brands' => array(
            'enabled' => true,
            'order' => 20,
            'title' => 'Featured Brands'
        ),
        'fleet' => array(
            'enabled' => true,
            'order' => 30,
            'title' => 'Fleet Section'
        ),
        'services' => array(
            'enabled' => true,
            'order' => 40,
            'title' => 'Services Section'
        ),
        'testimonials' => array(
            'enabled' => true,
            'order' => 50,
            'title' => 'Testimonials'
        ),
        'social' => array(
            'enabled' => true,
            'order' => 60,
            'title' => 'Social Feed'
        ),
        'cta' => array(
            'enabled' => true,
            'order' => 70,
            'title' => 'Call to Action'
        )
    );
}

// Sort sections by order
uasort($sections, function($a, $b) {
    return $a['order'] - $b['order'];
});

// Get all section meta with defaults
// ... existing meta data retrieval code ...

?>

<main id="primary" class="site-main">
    <?php foreach ($sections as $section_id => $section) : ?>
        <?php if (!$section['enabled']) continue; ?>
        
        <?php if ($section_id === 'hero') : ?>
            <!-- Hero Section -->
            <section class="relative h-[90vh] flex items-center justify-center overflow-hidden">
                <!-- Background Video/Image Container -->
                <div class="absolute right-0 w-2/3 h-full" style="z-index: 1">
                    <?php if ($hero_meta['backgroundVideo']['url']) : ?>
                        <video class="absolute inset-0 w-full h-full object-cover" autoPlay muted loop playsInline poster="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>">
                            <source src="<?php echo esc_url($hero_meta['backgroundVideo']['url']); ?>" type="video/mp4">
                            <?php if ($hero_meta['backgroundImage']['url']) : ?>
                                <img src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>" alt="Video fallback" class="absolute inset-0 w-full h-full object-cover">
                            <?php endif; ?>
                        </video>
                    <?php elseif ($hero_meta['backgroundImage']['url']) : ?>
                        <img src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>" alt="Hero background" class="absolute inset-0 w-full h-full object-cover">
                    <?php endif; ?>
                </div>

                <!-- Base dark overlay for left side -->
                <div class="absolute left-0 w-1/3 h-full bg-black" style="z-index: 2"></div>

                <!-- Gradient overlay (middle layer) -->
                <div class="absolute inset-0 pointer-events-none" style="z-index: 2">
                    <!-- Left to right gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black from-30% via-black/90 via-50% to-transparent to-100%"></div>
                </div>

                <!-- Content Container (top layer) -->
                <div class="relative container mx-auto px-4 py-12 sm:py-24 lg:py-32" style="z-index: 3">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Rating Badge - Moved to top -->
                            <?php if ($hero_meta['rating']) : ?>
                                <div class="mb-6">
                                    <div class="inline-flex items-center px-4 py-2 text-lg bg-white/10 backdrop-blur-sm text-white rounded-lg border border-white/20">
                                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="font-bold"><?php echo esc_html($hero_meta['rating']['text']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight text-white tracking-tight" style="content-visibility: auto">
                                <?php echo esc_html($hero_meta['title']); ?>
                            </h1>
                            <p class="text-xl sm:text-2xl text-white/90 max-w-2xl leading-relaxed">
                                <?php echo esc_html($hero_meta['description']); ?>
                            </p>

                            <div class="flex flex-wrap gap-4 pt-4">
                                <?php if ($hero_meta['primaryCta']['label']) : ?>
                                    <a href="<?php echo esc_url($hero_meta['primaryCta']['link']); ?>" 
                                       class="group inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                                        <?php if ($hero_meta['primaryCta']['icon'] === 'calendar') : ?>
                                            <i data-lucide="calendar" class="w-5 h-5 mr-2 transition-transform group-hover:scale-110"></i>
                                        <?php endif; ?>
                                        <?php echo esc_html($hero_meta['primaryCta']['label']); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($hero_meta['secondaryCta']['label']) : ?>
                                    <a href="<?php echo esc_url($hero_meta['secondaryCta']['link']); ?>" 
                                       class="group inline-flex items-center px-8 py-4 text-lg font-medium bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:bg-white/20 rounded-lg transition-all duration-300">
                                        <?php echo esc_html($hero_meta['secondaryCta']['label']); ?>
                                        <?php if ($hero_meta['secondaryCta']['icon'] === 'chevron-right') : ?>
                                            <i data-lucide="chevron-right" class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1"></i>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if ($hero_meta['phoneNumber']) : ?>
                                <div class="flex items-center text-white mt-8 pt-4 border-t border-white/10">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm mr-4">
                                        <i data-lucide="phone-call" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-white/70 mb-1">24/7 Support</div>
                                        <span class="text-2xl font-semibold tracking-wide"><?php echo esc_html($hero_meta['phoneNumber']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'featured_brands') : ?>
            <!-- Featured Brands Section -->
            <section aria-label="Featured Brands" class="bg-muted py-6 overflow-hidden hidden md:block">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap justify-center gap-8 sm:gap-12 md:gap-16">
                        <?php
                        $brands = get_post_meta(get_the_ID(), '_home_brands', true);
                        if ($brands) :
                            foreach ($brands as $brand) :
                                if (!empty($brand['image'])) :
                                    $image = wp_get_attachment_image_src($brand['image'], 'full');
                                    if ($image) :
                        ?>
                                <div class="flex items-center justify-center w-1/2 sm:w-1/3 md:w-1/4 lg:w-auto">
                                    <?php if (!empty($brand['url'])) : ?>
                                        <a href="<?php echo esc_url($brand['url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php endif; ?>
                                    <img 
                                        alt="<?php echo esc_attr($brand['name']); ?> logo"
                                        loading="lazy"
                                        width="<?php echo esc_attr($image[1]); ?>"
                                        height="<?php echo esc_attr($image[2]); ?>"
                                        decoding="async"
                                        data-nimg="1"
                                        class="w-auto h-[24px] sm:h-[28px] md:h-[32px] grayscale hover:grayscale-0 transition-all duration-300"
                                        style="color:transparent"
                                        src="<?php echo esc_url($image[0]); ?>"
                                    >
                                    <?php if (!empty($brand['url'])) : ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                        <?php
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'fleet') : ?>
            <!-- Fleet Section -->
            <section id="fleet" aria-labelledby="fleet-heading" class="py-16">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <h2 id="fleet-heading" class="text-4xl font-bold tracking-tighter text-center mb-8">Discover Our Premium Fleet</h2>
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                        <?php
                        // Query featured boats
                        $boats_args = array(
                            'post_type' => 'boat',
                            'posts_per_page' => 5,
                            'meta_query' => array(
                                array(
                                    'key' => '_boat_featured',
                                    'value' => '1',
                                    'compare' => '='
                                )
                            )
                        );
                        
                        $boats_query = new WP_Query($boats_args);
                        
                        if ($boats_query->have_posts()) :
                            $first_boat = true;
                            while ($boats_query->have_posts()) : $boats_query->the_post();
                                if ($first_boat) :
                                    $boat_price = get_post_meta(get_the_ID(), '_boat_price', true);
                                    $boat_length = get_post_meta(get_the_ID(), '_boat_length', true);
                                    $boat_capacity = get_post_meta(get_the_ID(), '_boat_capacity', true);
                                    $boat_speed = get_post_meta(get_the_ID(), '_boat_speed', true);
                        ?>
                                <div class="w-full lg:w-3/5 relative">
                                    <div class="relative aspect-video overflow-hidden rounded-xl shadow-2xl">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('large', array(
                                                'class' => 'object-cover transition-transform duration-500 hover:scale-105',
                                                'style' => 'position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;',
                                                'alt' => get_the_title()
                                            )); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($boats_query->post_count > 1) : ?>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground h-9 w-9 absolute top-1/2 left-4 -translate-y-1/2 bg-background/80 hover:bg-background" aria-label="Previous boat">
                                            <i data-lucide="chevron-left" class="h-6 w-6"></i>
                                        </button>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground h-9 w-9 absolute top-1/2 right-4 -translate-y-1/2 bg-background/80 hover:bg-background" aria-label="Next boat">
                                            <i data-lucide="chevron-right" class="h-6 w-6"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="w-full lg:w-2/5 space-y-6">
                                    <div>
                                        <h3 class="text-3xl font-bold mb-2"><?php the_title(); ?></h3>
                                        <p class="text-lg text-foreground mb-4"><?php echo get_the_excerpt(); ?></p>
                                        <div class="flex flex-wrap gap-4 mb-6">
                                            <?php if ($boat_length) : ?>
                                                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                                    <i data-lucide="waves" class="w-4 h-4 mr-1"></i>
                                                    Length: <?php echo esc_html($boat_length); ?> ft
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($boat_capacity) : ?>
                                                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                                    <i data-lucide="anchor" class="w-4 h-4 mr-1"></i>
                                                    Capacity: <?php echo esc_html($boat_capacity); ?> guests
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($boat_speed) : ?>
                                                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                                    <i data-lucide="wind" class="w-4 h-4 mr-1"></i>
                                                    Speed: <?php echo esc_html($boat_speed); ?> knots
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($boat_price) : ?>
                                            <p class="text-2xl font-bold mb-6">$<?php echo number_format($boat_price); ?></p>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 rounded-md w-full sm:w-auto text-lg px-8 py-3">
                                            Request a Viewing
                                        </a>
                                    </div>
                                </div>
                            <?php
                                $first_boat = false;
                            endif;
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="w-full text-center">
                            <p class="text-lg text-muted-foreground">No featured boats available at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        <?php elseif ($section_id === 'services') : ?>
            <!-- Services Section -->
            <section id="services" aria-labelledby="services-heading" class="py-24 bg-muted">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 mb-4 text-foreground font-medium">
                            <i data-lucide="award" class="w-4 h-4 mr-2"></i>
                            Premier Boat Services
                        </div>
                        <h2 id="services-heading" class="text-4xl md:text-5xl font-bold mb-6">Georgia's Leader in Boat Sales &amp; Marine Service</h2>
                        <p class="text-xl text-foreground max-w-3xl mx-auto">
                            Offering active boaters comprehensive marine services with expert technicians and state-of-the-art facilities.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-12 mb-16">
                        <?php
                        // Query services that are marked to show on homepage
                        $services_args = array(
                            'post_type' => 'service',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_show_on_home',
                                    'value' => '1',
                                    'compare' => '='
                                )
                            ),
                            'orderby' => array(
                                'meta_value_num' => 'ASC'
                            ),
                            'meta_key' => '_home_order'
                        );
                        
                        $services_query = new WP_Query($services_args);

                        if ($services_query->have_posts()) :
                            $count = 0;
                            while ($services_query->have_posts()) : $services_query->the_post();
                                $meta = array(
                                    'icon' => get_post_meta(get_the_ID(), '_service_icon', true) ?: 'tool',
                                    'price' => get_post_meta(get_the_ID(), '_service_price', true),
                                    'duration' => get_post_meta(get_the_ID(), '_service_duration', true),
                                    'location' => get_post_meta(get_the_ID(), '_service_location', true),
                                    'features' => get_post_meta(get_the_ID(), '_service_features', true) ?: array()
                                );
                                $count++;
                                $layout_reverse = $count % 2 === 0;
                        ?>
                            <div class="rounded-xl border bg-card text-card-foreground shadow overflow-hidden transition-all duration-300 hover:shadow-xl">
                                <div class="md:flex">
                                    <?php if (!$layout_reverse) : ?>
                                    <div class="md:w-3/5">
                                        <div class="flex flex-col space-y-1.5 p-6 bg-muted">
                                            <h3 class="tracking-tight text-2xl font-bold flex items-center">
                                                <i data-lucide="<?php echo esc_attr($meta['icon']); ?>" class="w-6 h-6 mr-3"></i>
                                                <?php the_title(); ?>
                                            </h3>
                                            <?php if ($meta['duration'] || $meta['location']) : ?>
                                                <p class="text-lg text-foreground">
                                                    <?php 
                                                    $service_details = array();
                                                    if ($meta['duration']) $service_details[] = $meta['duration'];
                                                    if ($meta['location'] === 'mobile') {
                                                        $service_details[] = 'Mobile Service Available';
                                                    } elseif ($meta['location'] === 'both') {
                                                        $service_details[] = 'Shop & Mobile Service';
                                                    }
                                                    echo esc_html(implode(' • ', $service_details));
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-6">
                                            <?php if (has_excerpt()) : ?>
                                                <p class="text-foreground mb-6"><?php echo get_the_excerpt(); ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($meta['features'])) : ?>
                                                <div class="flex flex-wrap gap-3 mb-6">
                                                    <?php foreach ($meta['features'] as $feature) : ?>
                                                        <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-sm">
                                                            <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                                            <?php echo esc_html($feature); ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($meta['price']) : ?>
                                                <p class="text-xl font-semibold mb-6">Starting at <?php echo esc_html($meta['price']); ?></p>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                                                Learn More About <?php the_title(); ?>
                                                <i data-lucide="chevron-right" class="ml-2 h-5 w-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="md:w-2/5 relative">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('large', array(
                                                'class' => 'absolute inset-0 object-cover w-full h-full',
                                                'style' => 'height: 100%',
                                                'loading' => 'lazy',
                                                'alt' => get_the_title()
                                            )); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php else : ?>
                                    <div class="md:w-2/5 relative">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('large', array(
                                                'class' => 'absolute inset-0 object-cover w-full h-full',
                                                'style' => 'height: 100%',
                                                'loading' => 'lazy',
                                                'alt' => get_the_title()
                                            )); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="md:w-3/5">
                                        <div class="flex flex-col space-y-1.5 p-6 bg-muted">
                                            <h3 class="tracking-tight text-2xl font-bold flex items-center">
                                                <i data-lucide="<?php echo esc_attr($meta['icon']); ?>" class="w-6 h-6 mr-3"></i>
                                                <?php the_title(); ?>
                                            </h3>
                                            <?php if ($meta['duration'] || $meta['location']) : ?>
                                                <p class="text-lg text-foreground">
                                                    <?php 
                                                    $service_details = array();
                                                    if ($meta['duration']) $service_details[] = $meta['duration'];
                                                    if ($meta['location'] === 'mobile') {
                                                        $service_details[] = 'Mobile Service Available';
                                                    } elseif ($meta['location'] === 'both') {
                                                        $service_details[] = 'Shop & Mobile Service';
                                                    }
                                                    echo esc_html(implode(' • ', $service_details));
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-6">
                                            <?php if (has_excerpt()) : ?>
                                                <p class="text-foreground mb-6"><?php echo get_the_excerpt(); ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($meta['features'])) : ?>
                                                <div class="flex flex-wrap gap-3 mb-6">
                                                    <?php foreach ($meta['features'] as $feature) : ?>
                                                        <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-sm">
                                                            <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                                            <?php echo esc_html($feature); ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($meta['price']) : ?>
                                                <p class="text-xl font-semibold mb-6">Starting at <?php echo esc_html($meta['price']); ?></p>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                                                Learn More About <?php the_title(); ?>
                                                <i data-lucide="chevron-right" class="ml-2 h-5 w-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>

                    <div class="rounded-xl border shadow bg-primary text-primary-foreground overflow-hidden">
                        <div class="p-8 md:p-12">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                                <div class="text-center md:text-left md:w-2/3">
                                    <h3 class="text-3xl md:text-4xl font-bold mb-4">We Don't Just Service Boats - We Live It!</h3>
                                    <p class="text-xl md:text-2xl">
                                        Our passion for boating goes beyond service. We're active boaters ourselves, ensuring we provide you with the best care based on real-world experience.
                                    </p>
                                </div>
                                <div class="flex flex-wrap justify-center gap-6 md:w-1/3">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="users" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Expert Team</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="wrench" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Quality Service</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="award" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Top Brands</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'testimonials') : ?>
            <!-- Testimonials Section -->
            <section id="testimonials" aria-labelledby="testimonials-heading" class="py-24 overflow-hidden">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <h2 id="testimonials-heading" class="text-4xl font-bold text-center mb-16">What Our Customers Say</h2>
                    
                    <div class="relative">
                        <!-- Large Quote Background -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-5">
                            <i data-lucide="quote" class="w-96 h-96"></i>
                        </div>

                        <!-- Testimonials Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
                            <?php
                            $testimonials = get_post_meta(get_the_ID(), '_testimonials', true) ?: array(
                                array(
                                    'content' => 'The team at Impact Marine Group made buying my first yacht a breeze. Their expertise and customer service are unmatched!',
                                    'author' => 'John D.',
                                    'rating' => 5
                                ),
                                array(
                                    'content' => 'Outstanding service and attention to detail. They went above and beyond to ensure my boat was in perfect condition.',
                                    'author' => 'Sarah M.',
                                    'rating' => 5
                                ),
                                array(
                                    'content' => 'Professional, knowledgeable, and incredibly helpful. The best marine service I\'ve experienced.',
                                    'author' => 'Mike R.',
                                    'rating' => 5
                                )
                            );

                            foreach ($testimonials as $testimonial) :
                                $initial = substr($testimonial['author'], 0, 1);
                            ?>
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center mb-6 shadow-lg">
                                        <span class="text-3xl font-bold text-primary-foreground"><?php echo esc_html($initial); ?></span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg italic mb-4 relative">
                                            <span class="absolute -top-4 -left-2 text-4xl text-muted">"</span>
                                            <?php echo esc_html($testimonial['content']); ?>
                                            <span class="absolute -bottom-4 -right-2 text-4xl text-muted">"</span>
                                        </p>
                                        <p class="font-semibold"><?php echo esc_html($testimonial['author']); ?></p>
                                        <div class="flex items-center justify-center mt-2">
                                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                                <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-16 text-center">
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-8">
                            Read More Reviews
                        </button>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'social') : ?>
            <!-- Social Section -->
            <section id="social" aria-labelledby="social-heading" class="py-16 bg-muted">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <div class="flex items-center justify-center mb-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-anchor w-10 h-10 mr-3"><path d="M12 22V8"></path><path d="M5 12H2a10 10 0 0 0 20 0h-3"></path><circle cx="12" cy="5" r="3"></circle></svg>
                        <h2 id="social-heading" class="text-4xl font-bold">Sail Through Our Instagram</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $instagram_posts = wades_get_instagram_posts(6);
                        
                        if (!empty($instagram_posts)) :
                            foreach ($instagram_posts as $post) :
                                $caption = wp_trim_words($post['caption'], 15, '...');
                                $time_ago = wades_get_relative_time($post['timestamp']);
                        ?>
                            <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                                <div class="p-4 bg-muted">
                                    <div class="flex items-center space-x-4">
                                        <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                            <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium">Impact Marine Group</p>
                                            <p class="text-xs text-foreground">@impactmarinegroup</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative group">
                                    <img 
                                        alt="<?php echo esc_attr($caption); ?>" 
                                        loading="lazy" 
                                        width="400" 
                                        height="400" 
                                        decoding="async" 
                                        data-nimg="1" 
                                        class="transition-transform duration-300 group-hover:scale-105 object-cover w-full h-[400px]"
                                        src="<?php echo esc_url($post['media_url']); ?>"
                                        style="color: transparent;"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                        <a href="<?php echo esc_url($post['permalink']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                            View on Instagram
                                            <i data-lucide="external-link" class="w-4 h-4 ml-1"></i>
                                        </a>
                                        <span class="text-xs text-white/90"><?php echo esc_html($time_ago); ?></span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm">
                                        <span class="font-medium">impactmarinegroup</span>
                                        <span class="text-foreground"><?php echo esc_html($caption); ?></span>
                                    </p>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        else :
                        ?>
                            <div class="col-span-full text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-muted mb-4">
                                    <i data-lucide="instagram" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">No Instagram Posts Found</h3>
                                <p class="text-muted-foreground">Please check your Instagram API settings in the Theme Settings.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="text-center mt-12">
                        <a href="<?php echo esc_url(get_option('wades_social_instagram')); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 rounded-md px-8">
                            <i data-lucide="instagram" class="w-5 h-5 mr-2"></i>
                            Follow Our Nautical Journey
                        </a>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'cta') : ?>
            <!-- CTA Section -->
            <section class="cta-section py-24 bg-blue-900 text-white">
                <div class="container mx-auto px-4">
                    <div class="cta-content max-w-4xl mx-auto text-center">
                        <?php if ($cta_title) : ?>
                            <h2 class="text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($cta_title); ?></h2>
                        <?php endif; ?>

                        <?php if ($cta_description) : ?>
                            <p class="text-xl text-blue-100 mb-8"><?php echo wp_kses_post($cta_description); ?></p>
                        <?php endif; ?>

                        <?php if ($cta_button_text && $cta_button_link) : ?>
                            <a href="<?php echo esc_url($cta_button_link); ?>" 
                               class="button inline-flex items-center bg-white hover:bg-blue-50 text-blue-900 px-8 py-4 rounded-lg font-medium transition-colors">
                                <?php echo esc_html($cta_button_text); ?>
                                <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</main>

<!-- Initialize Lucide icons -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php get_footer(); ?> 