<?php
/**
 * Template Name: Services Template
 * 
 * @package wades
 */

get_header(); 
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
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

        // Loop through sections in order
        foreach ($section_order as $section) {
            switch ($section) {
                case 'services':
                    if ($sections_visibility['services']) :
                        // Search and Filter Section
                        if ($show_search || $show_filters) :
                        ?>
                        <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <?php if ($show_search) : ?>
                                <div class="relative w-full sm:w-96">
                                    <input type="text" id="service-search" placeholder="Search services..." class="pl-10 w-full rounded-lg border border-input bg-background px-3 py-2">
                                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground"></i>
                                </div>
                            <?php endif; ?>

                            <?php if ($show_filters) : ?>
                                <select id="location-filter" class="w-full sm:w-[200px] rounded-lg border border-input bg-background px-3 py-2">
                                    <option value="">All Locations</option>
                                    <option value="shop">Shop Services</option>
                                    <option value="mobile">Mobile Services</option>
                                    <option value="both">Both</option>
                                </select>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Services Grid -->
                        <div id="services-grid" class="grid <?php echo esc_attr($grid_columns); ?> gap-8 mb-16">
                            <?php
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                            $args = array(
                                'post_type' => 'service',
                                'posts_per_page' => $services_per_page,
                                'orderby' => 'menu_order',
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
                                    $card_style = get_post_meta(get_the_ID(), '_card_style', true) ?: 'default';
                                    $hover_effect = get_post_meta(get_the_ID(), '_hover_effect', true) ?: 'scale';
                            ?>
                            <div class="service-card rounded-xl overflow-hidden bg-white shadow-md <?php 
                                echo esc_attr($card_style); ?> hover:<?php 
                                echo esc_attr($hover_effect); ?>" 
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

                                    <?php if (!empty($features)) : ?>
                                        <div class="flex flex-wrap gap-2 mb-6">
                                            <?php foreach ($features as $feature) : ?>
                                                <span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-medium">
                                                    <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                                                    <?php echo esc_html($feature); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex items-center justify-between mt-6">
                                        <?php if ($location) : ?>
                                            <span class="inline-flex items-center text-sm text-muted-foreground">
                                                <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                                <?php
                                                switch ($location) {
                                                    case 'shop':
                                                        echo 'Shop Service';
                                                        break;
                                                    case 'mobile':
                                                        echo 'Mobile Service';
                                                        break;
                                                    case 'both':
                                                        echo 'Shop & Mobile';
                                                        break;
                                                }
                                                ?>
                                            </span>
                                        <?php endif; ?>

                                        <a href="<?php the_permalink(); ?>" 
                                           class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors">
                                            Learn More
                                            <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                                        </a>
                                    </div>
                                </div>
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
                        <div class="mt-12 pb-12">
                            <div class="flex justify-center gap-2">
                                <?php
                                $pagination = paginate_links(array(
                                    'total' => ceil($total_services / $services_per_page),
                                    'current' => $paged,
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
                    <?php
                    endif;
                    break;

                case 'why_choose_us':
                    if ($sections_visibility['why_choose_us']) :
                    ?>
                    <!-- Why Choose Us Section -->
                    <section class="bg-primary text-white rounded-xl overflow-hidden mb-16">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="p-8 md:p-12">
                                <h2 class="text-3xl font-bold mb-6">Why Choose Our Service Department?</h2>
                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 bg-white/10 rounded-lg p-3">
                                            <i data-lucide="award" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-semibold mb-2">Certified Technicians</h3>
                                            <p class="text-white/80">Our team of factory-trained technicians ensures your boat receives expert care.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 bg-white/10 rounded-lg p-3">
                                            <i data-lucide="clock" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-semibold mb-2">Quick Turnaround</h3>
                                            <p class="text-white/80">We understand your time is valuable and strive for efficient service completion.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 bg-white/10 rounded-lg p-3">
                                            <i data-lucide="shield" class="w-6 h-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-semibold mb-2">Quality Guarantee</h3>
                                            <p class="text-white/80">All our work is backed by our satisfaction guarantee for your peace of mind.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php
                                $service_image = get_post_meta(get_the_ID(), '_service_image', true);
                                if ($service_image) :
                                    echo wp_get_attachment_image($service_image, 'large', false, array(
                                        'class' => 'w-full h-full object-cover',
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
                                    'description' => 'Essential winterization service for basic boat protection.',
                                    'services' => array(
                                        'Engine oil & filter change',
                                        'Fuel system treatment',
                                        'Battery check & maintenance',
                                        'Basic systems check'
                                    )
                                ),
                                array(
                                    'title' => 'Premium Winterization',
                                    'description' => 'Comprehensive winterization with added protection.',
                                    'services' => array(
                                        'All Basic package services',
                                        'Detailed systems inspection',
                                        'Antifreeze protection',
                                        'Interior dehumidification',
                                        'Shrink wrap protection'
                                    )
                                ),
                                array(
                                    'title' => 'Ultimate Protection',
                                    'description' => 'Complete winter protection and storage solution.',
                                    'services' => array(
                                        'All Premium package services',
                                        'Climate-controlled storage',
                                        'Monthly battery maintenance',
                                        'Spring recommissioning',
                                        'Priority spring launch'
                                    )
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

                                    <a href="#contact" class="inline-flex items-center justify-center w-full rounded-lg px-4 py-2 text-sm font-medium bg-primary text-white hover:bg-primary/90 transition-colors">
                                        Get Started
                                    </a>
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('service-search');
    const locationFilter = document.getElementById('location-filter');
    const serviceCards = document.querySelectorAll('.service-card');
    const noResults = document.getElementById('no-results');
    const servicesGrid = document.getElementById('services-grid');

    function filterServices() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const location = locationFilter ? locationFilter.value : '';
        let visibleCount = 0;

        serviceCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const description = card.dataset.description.toLowerCase();
            const cardLocation = card.dataset.location;

            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                description.includes(searchTerm);

            const matchesLocation = !location || 
                cardLocation === location || 
                (location === 'both' && ['shop', 'mobile', 'both'].includes(cardLocation));

            if (matchesSearch && matchesLocation) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            servicesGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            servicesGrid.classList.remove('hidden');
        }
    }

    // Add event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterServices);
    }
    if (locationFilter) {
        locationFilter.addEventListener('change', filterServices);
    }

    // Initialize Lucide icons
    lucide.createIcons();
});
</script>

<style>
/* Service Card Styles */
.service-card {
    transition: all 0.2s ease-in-out;
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
}

.service-card.hover\:lift:hover {
    transform: translateY(-4px);
}

.service-card.hover\:glow:hover {
    box-shadow: 0 0 20px rgba(15, 118, 110, 0.2);
}

/* Pagination Styles */
.pagination {
    display: none;
}

/* Breadcrumb Styles */
.breadcrumb-container {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 0;
    margin-bottom: 2rem;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
}

.breadcrumb a {
    color: #2563eb;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb a:hover {
    color: #1d4ed8;
}

.breadcrumb-separator {
    color: #94a3b8;
}
</style>

<!-- CTA Section -->
<section class="relative mt-24 mx-4">
    <!-- Main Container with gradient background -->
    <div class="relative max-w-7xl mx-auto">
        <!-- Background with complex gradient and texture -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 rounded-3xl overflow-hidden">
            <!-- Decorative gradient circles -->
            <div class="absolute inset-0">
                <div class="absolute left-1/4 top-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute right-1/4 bottom-0 w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <div class="absolute left-1/3 bottom-1/3 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
            </div>

            <!-- Mesh gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/50 to-transparent"></div>
            
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(to right, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>

        <!-- Content Container -->
        <div class="relative px-6 py-20 sm:px-12 sm:py-24">
            <!-- Top accent line -->
            <div class="absolute inset-x-12 top-0 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>

            <!-- Content Grid -->
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Text Content -->
                <div class="text-center lg:text-left space-y-6">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Ready for Professional Marine Service?
                    </h2>
                    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto lg:mx-0">
                        Our certified technicians provide expert care for your boat, ensuring peak performance and reliability.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-blue-900 font-semibold hover:bg-blue-50 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 group">
                            Schedule Service
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="tel:+17708817808" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-blue-700/30 text-white font-semibold hover:bg-blue-700/40 border border-blue-400/20 backdrop-blur-sm transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            (770) 881-7808
                        </a>
                    </div>
                </div>

                <!-- Right Column - Feature List -->
                <div class="hidden lg:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-2">Factory Certified</h3>
                                    <p class="text-blue-100">Our technicians are factory-trained and certified for expert service.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-2">Quick Turnaround</h3>
                                    <p class="text-blue-100">Fast, efficient service to get you back on the water.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-2">Satisfaction Guaranteed</h3>
                                    <p class="text-blue-100">We stand behind our work with a 100% satisfaction guarantee.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom accent line -->
            <div class="absolute inset-x-12 bottom-0 h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
        </div>
    </div>

    <!-- Add animation keyframes -->
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</section>

<?php get_footer(); ?> 