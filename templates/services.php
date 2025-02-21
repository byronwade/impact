<?php
/**
 * Template Name: Services Template
 * 
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <?php get_template_part('template-parts/template-header'); ?>

    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <?php
        // Get customization options
        $show_search = get_post_meta(get_the_ID(), '_show_search', true) !== '';
        $show_filters = get_post_meta(get_the_ID(), '_show_filters', true) !== '';
        $grid_columns = get_post_meta(get_the_ID(), '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        $services_per_page = absint(get_post_meta(get_the_ID(), '_services_per_page', true)) ?: 12;
        $sections_visibility = get_post_meta(get_the_ID(), '_sections_visibility', true) ?: array(
            'services' => '1',
            'why_choose_us' => '1',
            'winterization' => '1',
            'policies' => '1'
        );
        $section_order = explode(',', get_post_meta(get_the_ID(), '_section_order', true) ?: 'services,why_choose_us,winterization,policies');
        ?>

        <!-- Search and Filter Section -->
        <?php if ($show_search || $show_filters) : ?>
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <?php if ($show_search) : ?>
            <div class="relative w-full sm:w-96">
                <input type="text" id="service-search" placeholder="Search services..." class="pl-10 w-full rounded-lg border border-input bg-background px-3 py-2">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground"></i>
            </div>
            <?php endif; ?>
            <?php if ($show_filters) : ?>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <select id="location-filter" class="w-full sm:w-[200px] rounded-lg border border-input bg-background px-3 py-2">
                    <option value="">All Locations</option>
                    <option value="shop">At Our Shop</option>
                    <option value="mobile">Mobile Service</option>
                    <option value="both">Both Available</option>
                </select>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Results Count -->
        <p id="results-count" class="text-sm text-muted-foreground mb-4"></p>

        <?php
        // Render sections in the specified order
        foreach ($section_order as $section) {
            if (!isset($sections_visibility[$section]) || $sections_visibility[$section] !== '1') {
                continue;
            }

            switch ($section) {
                case 'services':
                    if ($sections_visibility['services']) :
        ?>
                    <!-- Services Grid -->
                    <div id="services-grid" class="grid <?php echo esc_attr($grid_columns); ?> gap-8 mb-16">
                        <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                        $args = array(
                            'post_type' => 'service',
                            'posts_per_page' => $services_per_page,
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'paged' => $paged
                        );

                        $services_query = new WP_Query($args);
                        $total_services = $services_query->found_posts;

                        if ($services_query->have_posts()) :
                            while ($services_query->have_posts()) : $services_query->the_post();
                                $icon = get_post_meta(get_the_ID(), '_service_icon', true);
                                $price = get_post_meta(get_the_ID(), '_service_price', true);
                                $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                                $location = get_post_meta(get_the_ID(), '_service_location', true);
                                $features = get_post_meta(get_the_ID(), '_service_features', true);
                        ?>
                        <div class="service-card rounded-xl overflow-hidden bg-white shadow-md <?php 
                            echo esc_attr(get_post_meta(get_the_ID(), '_card_style', true)); ?> hover:<?php 
                            echo esc_attr(get_post_meta(get_the_ID(), '_hover_effect', true)); ?>" 
                            data-location="<?php echo esc_attr($location); ?>"
                            data-name="<?php echo esc_attr(get_the_title()); ?>"
                            data-description="<?php echo esc_attr(get_the_excerpt()); ?>">
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <?php if ($icon) : ?>
                                        <div class="flex-shrink-0">
                                            <i data-lucide="<?php echo esc_attr($icon); ?>" 
                                               class="h-8 w-8" 
                                               style="color: <?php echo esc_attr(get_post_meta(get_the_ID(), '_service_icon_color', true)); ?>">
                                            </i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="text-xl font-semibold"><?php the_title(); ?></h3>
                                        <?php if ($price) : ?>
                                            <p class="text-sm text-muted-foreground">Starting at <?php echo esc_html($price); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="prose prose-sm mb-6">
                                    <?php the_excerpt(); ?>
                                </div>

                                <?php if ($features && is_array($features)) : ?>
                                    <ul class="space-y-2 mb-6">
                                        <?php foreach (array_slice($features, 0, 3) as $feature) : ?>
                                            <li class="flex items-center gap-2 text-sm">
                                                <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                <span><?php echo esc_html($feature); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                        <?php if (count($features) > 3) : ?>
                                            <li class="text-sm text-muted-foreground">
                                                +<?php echo count($features) - 3; ?> more features
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t">
                                    <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                        <?php if ($duration) : ?>
                                            <i data-lucide="clock" class="h-4 w-4"></i>
                                            <span><?php echo esc_html($duration); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" 
                                       class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                        Learn More
                                        <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                                    </a>
                                </div>
                            </div>

                            <?php
                            // Add Schema.org markup
                            $schema_type = get_post_meta(get_the_ID(), '_schema_type', true) ?: 'Service';
                            $seo_title = get_post_meta(get_the_ID(), '_seo_title', true) ?: get_the_title();
                            $seo_description = get_post_meta(get_the_ID(), '_seo_description', true) ?: get_the_excerpt();
                            
                            $schema = array(
                                '@context' => 'https://schema.org',
                                '@type' => $schema_type,
                                'name' => $seo_title,
                                'description' => wp_strip_all_tags($seo_description),
                                'provider' => array(
                                    '@type' => 'Organization',
                                    'name' => get_bloginfo('name'),
                                    'url' => home_url()
                                ),
                                'areaServed' => array(
                                    '@type' => 'Place',
                                    'address' => array(
                                        '@type' => 'PostalAddress',
                                        'addressLocality' => 'Your City', // You should customize this
                                        'addressRegion' => 'Your State', // You should customize this
                                        'addressCountry' => 'US'
                                    )
                                )
                            );

                            if ($price) {
                                $schema['offers'] = array(
                                    '@type' => 'Offer',
                                    'price' => preg_replace('/[^0-9.]/', '', $price),
                                    'priceCurrency' => 'USD'
                                );
                            }
                            ?>
                            <script type="application/ld+json"><?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?></script>
                        </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>

                    <!-- No Results Message -->
                    <div id="no-results" class="hidden text-center py-12">
                        <h3 class="text-xl font-semibold mb-2">No services found</h3>
                        <p class="text-muted-foreground">Try adjusting your search to find what you're looking for.</p>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_services > $services_per_page) : ?>
                    <div class="mt-12 mb-16 flex justify-center items-center gap-2">
                        <?php
                        $total_pages = ceil($total_services / $services_per_page);
                        $current_page = max(1, $paged);

                        // Previous page
                        if ($current_page > 1) : ?>
                            <a href="<?php echo get_pagenum_link($current_page - 1); ?>" 
                               class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium border border-input hover:bg-accent hover:text-accent-foreground transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i>
                                Previous
                            </a>
                        <?php endif; ?>

                        <!-- Page numbers -->
                        <div class="flex items-center gap-1">
                            <?php
                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $current_page) {
                                    echo '<span class="inline-flex items-center justify-center rounded-lg w-10 h-10 text-sm font-medium bg-primary text-white">' . $i . '</span>';
                                } else {
                                    echo '<a href="' . get_pagenum_link($i) . '" class="inline-flex items-center justify-center rounded-lg w-10 h-10 text-sm font-medium hover:bg-accent hover:text-accent-foreground transition-colors">' . $i . '</a>';
                                }
                            }
                            ?>
                        </div>

                        <!-- Next page -->
                        <?php if ($current_page < $total_pages) : ?>
                            <a href="<?php echo get_pagenum_link($current_page + 1); ?>" 
                               class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium border border-input hover:bg-accent hover:text-accent-foreground transition-colors">
                                Next
                                <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php
                    endif;
                    break;

                case 'why_choose_us':
                    if ($sections_visibility['why_choose_us']) :
                    ?>
                    <!-- Why Choose Us Section -->
                    <section class="bg-white rounded-xl shadow-lg p-8 md:p-12 mb-16">
                        <div class="grid md:grid-cols-2 gap-12">
                            <div class="space-y-6">
                                <h2 class="text-3xl font-bold">Why Choose Our Service Department?</h2>
                                <?php
                                $reasons = get_post_meta(get_the_ID(), '_why_choose_us', true) ?: array(
                                    'Factory trained and certified technicians',
                                    'State-of-the-art diagnostic equipment',
                                    'Comprehensive service for all boat brands',
                                    'Convenient mobile service options',
                                    'Transparent pricing and estimates',
                                    'Quality OEM parts and materials'
                                );
                                if ($reasons) : ?>
                                    <ul class="space-y-4">
                                        <?php foreach ($reasons as $reason) : ?>
                                            <li class="flex items-start gap-3">
                                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 flex-shrink-0"></i>
                                                <span><?php echo esc_html($reason); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php
                                $service_image = get_post_meta(get_the_ID(), '_service_image', true);
                                if ($service_image) :
                                    echo wp_get_attachment_image($service_image, 'large', false, array(
                                        'class' => 'rounded-xl shadow-lg w-full h-full object-cover',
                                        'alt' => 'Our Service Department'
                                    ));
                                endif;
                                ?>
                            </div>
                        </div>
                    </section>
                    <?php
                    endif;
                    break;

                case 'winterization':
                    if ($sections_visibility['winterization']) :
                    ?>
                    <!-- Winterization Packages -->
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold mb-8">Winterization Packages</h2>
                        <div class="grid md:grid-cols-3 gap-8">
                            <?php
                            $packages = get_post_meta(get_the_ID(), '_winterization_packages', true) ?: array(
                                array(
                                    'title' => 'Basic Winterization',
                                    'description' => 'Essential winterization service for basic protection',
                                    'services' => array(
                                        'Engine oil & filter change',
                                        'Fuel system treatment',
                                        'Battery check & service',
                                        'Basic systems check'
                                    ),
                                    'price' => '$299',
                                    'note' => 'Perfect for smaller boats'
                                ),
                                array(
                                    'title' => 'Premium Winterization',
                                    'description' => 'Comprehensive winterization with added protection',
                                    'services' => array(
                                        'All Basic package services',
                                        'Gear oil change',
                                        'Engine fogging',
                                        'Antifreeze flush',
                                        'Detailed systems check'
                                    ),
                                    'price' => '$499',
                                    'note' => 'Recommended for most boats'
                                ),
                                array(
                                    'title' => 'Ultimate Winterization',
                                    'description' => 'Complete winterization with full-service care',
                                    'services' => array(
                                        'All Premium package services',
                                        'Complete detail service',
                                        'Shrink wrap protection',
                                        'Storage preparation',
                                        'Spring readiness check'
                                    ),
                                    'price' => '$799',
                                    'note' => 'Best for long-term protection'
                                )
                            );

                            if ($packages) :
                                foreach ($packages as $package) :
                            ?>
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($package['title']); ?></h3>
                                    <p class="text-muted-foreground mb-6"><?php echo esc_html($package['description']); ?></p>
                                    
                                    <?php if (!empty($package['services'])) : ?>
                                        <ul class="space-y-3 mb-6">
                                            <?php foreach ($package['services'] as $service) : ?>
                                                <li class="flex items-center gap-2">
                                                    <i data-lucide="check" class="w-5 h-5 text-green-500"></i>
                                                    <span><?php echo esc_html($service); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <div class="border-t pt-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-2xl font-bold"><?php echo esc_html($package['price']); ?></span>
                                            <a href="#" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium bg-primary text-white hover:bg-primary/90 transition-colors">
                                                Schedule Now
                                            </a>
                                        </div>
                                        <?php if (!empty($package['note'])) : ?>
                                            <p class="text-sm text-muted-foreground"><?php echo esc_html($package['note']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </section>
                    <?php
                    endif;
                    break;

                case 'policies':
                    if ($sections_visibility['policies']) :
                    ?>
                    <!-- Service Policies -->
                    <section class="bg-gray-50 rounded-xl p-8 md:p-12">
                        <h2 class="text-3xl font-bold mb-8">Important Service Policies</h2>
                        <?php
                        $policies = get_post_meta(get_the_ID(), '_service_policies', true) ?: array(
                            'Appointments required for all service work',
                            'Diagnostic fees may apply for complex issues',
                            '48-hour cancellation notice required',
                            'Written estimates provided before work begins',
                            'OEM parts used unless otherwise specified',
                            '90-day warranty on all service work'
                        );

                        if ($policies) : ?>
                            <ul class="grid md:grid-cols-2 gap-6">
                                <?php foreach ($policies as $policy) : ?>
                                    <li class="flex items-start gap-3">
                                        <i data-lucide="info" class="w-6 h-6 text-blue-500 flex-shrink-0"></i>
                                        <span><?php echo esc_html($policy); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </section>
                    <?php
                    endif;
                    break;
            }
        }
        ?>

        <!-- Call to Action -->
        <section class="text-center mt-16">
            <h2 class="text-3xl font-bold mb-4">Ready to Schedule Your Service?</h2>
            <p class="text-xl text-muted-foreground mb-8">Contact us today to book your appointment or discuss your service needs.</p>
            <a href="tel:+17708817808" class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-lg font-medium bg-primary text-white hover:bg-primary/90 transition-colors">
                <i data-lucide="phone" class="w-6 h-6 mr-2"></i>
                Call (770) 881-7808
            </a>
        </section>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSearch = document.getElementById('service-search');
    const locationFilter = document.getElementById('location-filter');
    const serviceCards = document.querySelectorAll('.service-card');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const servicesGrid = document.getElementById('services-grid');

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    const locationParam = urlParams.get('location');

    // Set initial filter values from URL parameters
    if (searchParam) serviceSearch.value = searchParam;
    if (locationParam) locationFilter.value = locationParam;

    function filterServices() {
        const searchTerm = serviceSearch.value.toLowerCase();
        const location = locationFilter.value.toLowerCase();
        let visibleCount = 0;

        serviceCards.forEach(card => {
            const cardData = {
                location: card.dataset.location.toLowerCase(),
                name: card.dataset.name.toLowerCase(),
                description: card.dataset.description.toLowerCase()
            };

            const matchesSearch = searchTerm === '' || 
                                cardData.name.includes(searchTerm) || 
                                cardData.description.includes(searchTerm);
            
            const matchesLocation = location === '' || cardData.location === location;

            if (matchesSearch && matchesLocation) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Update results count and visibility
        resultsCount.textContent = `Showing ${visibleCount} service${visibleCount !== 1 ? 's' : ''}`;
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            servicesGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            servicesGrid.classList.remove('hidden');
        }

        // Update URL with filter parameters
        const params = new URLSearchParams();
        if (searchTerm) params.set('search', searchTerm);
        if (location) params.set('location', location);
        
        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.replaceState({}, '', newUrl);
    }

    // Add event listeners with debounce for search
    let searchTimeout;
    serviceSearch.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterServices, 300);
    });

    // Immediate filtering for dropdowns
    locationFilter.addEventListener('change', filterServices);

    // Initial filtering
    filterServices();
});
</script>

<style>
/* Card Styles */
.service-card.minimal {
    box-shadow: none;
    border: 1px solid #e5e7eb;
}

.service-card.featured {
    border: 2px solid #0f766e;
    box-shadow: 0 4px 6px -1px rgb(15 118 110 / 0.1), 0 2px 4px -2px rgb(15 118 110 / 0.1);
}

.service-card.bordered {
    border: 2px solid #e5e7eb;
}

/* Hover Effects */
.service-card.hover\:scale:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}

.service-card.hover\:lift:hover {
    transform: translateY(-4px);
    transition: transform 0.2s ease-in-out;
}

.service-card.hover\:glow:hover {
    box-shadow: 0 0 20px rgba(15, 118, 110, 0.2);
    transition: box-shadow 0.2s ease-in-out;
}
</style>

<?php get_footer(); ?> 