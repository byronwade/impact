<?php
/**
 * Template Name: Boats Template
 * 
 * @package wades
 */

// Add debugging
if (defined('WP_DEBUG') && WP_DEBUG === true) {
    error_log('BOATS TEMPLATE: Template file is being loaded');
}

// Get customization options
$custom_title = get_post_meta(get_the_ID(), '_boats_title', true);
$custom_description = get_post_meta(get_the_ID(), '_boats_description', true);
$custom_sub_header = get_post_meta(get_the_ID(), '_boats_sub_header', true);

get_header();

// Get meta data with defaults
$meta = array(
    // Hero Section
    'hero_background_image' => get_post_meta(get_the_ID(), '_hero_background_image', true),
    'hero_overlay_opacity' => get_post_meta(get_the_ID(), '_hero_overlay_opacity', true) ?: '40',
    'hero_height' => get_post_meta(get_the_ID(), '_hero_height', true) ?: '70',
    'boats_title' => get_post_meta(get_the_ID(), '_boats_title', true) ?: 'Our Boat Inventory',
    'boats_description' => get_post_meta(get_the_ID(), '_boats_description', true) ?: 'Discover our extensive collection of premium boats.',
    
    // Layout Options
    'grid_columns' => get_post_meta(get_the_ID(), '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    'boats_per_page' => get_post_meta(get_the_ID(), '_boats_per_page', true) ?: '12',
    'show_search' => get_post_meta(get_the_ID(), '_show_search', true) ?: '1',
    'show_filters' => get_post_meta(get_the_ID(), '_show_filters', true) ?: '1',
    
    // Filter Options
    'manufacturer_filter' => get_post_meta(get_the_ID(), '_manufacturer_filter', true) ?: '1',
    'condition_filter' => get_post_meta(get_the_ID(), '_condition_filter', true) ?: '1',
    'price_filter' => get_post_meta(get_the_ID(), '_price_filter', true) ?: '1',
    'year_filter' => get_post_meta(get_the_ID(), '_year_filter', true) ?: '1',
    'length_filter' => get_post_meta(get_the_ID(), '_length_filter', true) ?: '1',
    'type_filter' => get_post_meta(get_the_ID(), '_type_filter', true) ?: '1',
    
    // Sort Options
    'sort_options' => get_post_meta(get_the_ID(), '_sort_options', true) ?: array(
        'newest' => '1',
        'price_low' => '1',
        'price_high' => '1',
        'name' => '1',
        'length' => '1',
        'year' => '1'
    ),
    
    // Card Display
    'card_style' => get_post_meta(get_the_ID(), '_card_style', true) ?: 'default',
    'hover_effect' => get_post_meta(get_the_ID(), '_hover_effect', true) ?: 'scale',
    'show_specs' => get_post_meta(get_the_ID(), '_show_specs', true) ?: array(
        'length' => '1',
        'capacity' => '1',
        'engine' => '1',
        'year' => '1',
        'price' => '1',
        'location' => '1'
    ),
    
    // Sections Visibility & Order
    'sections_visibility' => get_post_meta(get_the_ID(), '_sections_visibility', true) ?: array(
        'hero' => '1',
        'filters' => '1',
        'inventory' => '1',
        'pagination' => '1'
    ),
    'section_order' => get_post_meta(get_the_ID(), '_section_order', true) ?: 'hero,filters,inventory,pagination'
);

// SEO meta data
$seo_title = get_post_meta(get_the_ID(), '_seo_title', true);
$seo_description = get_post_meta(get_the_ID(), '_seo_description', true);
$schema_type = get_post_meta(get_the_ID(), '_schema_type', true) ?: 'VehicleDealer';

// Add SEO meta tags
if ($seo_title) {
    add_filter('pre_get_document_title', function() use ($seo_title) {
        return str_replace('[location]', 'Cumming, GA', $seo_title);
    });
}
if ($seo_description) {
    add_action('wp_head', function() use ($seo_description) {
        echo '<meta name="description" content="' . esc_attr(str_replace('[location]', 'Cumming, GA', $seo_description)) . '">';
    });
}

// Add Schema.org markup
add_action('wp_footer', function() use ($schema_type, $meta) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
        'name' => 'Impact Marine Group',
        'description' => $meta['boats_description'],
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '5185 Browns Bridge Rd',
            'addressLocality' => 'Cumming',
            'addressRegion' => 'GA',
            'postalCode' => '30041',
            'addressCountry' => 'US'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '34.2276',
            'longitude' => '-84.0946'
        ),
        'telephone' => '(770) 881-7808',
        'url' => get_permalink()
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
});

// Get sections order
$sections = explode(',', $meta['section_order']);

// Start main content
?>
<main role="main" aria-label="Main content" class="flex-grow">
    <?php
    // Loop through sections in order
    foreach ($sections as $section) :
        if (!isset($meta['sections_visibility'][$section]) || $meta['sections_visibility'][$section] !== '1') :
            continue;
        endif;

        switch ($section) :
            case 'hero':
                // Hero Section
                ?>
                <header class="relative overflow-hidden" style="height: <?php echo esc_attr($meta['hero_height']); ?>vh;">
                    <?php if ($meta['hero_background_image']) : ?>
                        <div class="absolute inset-0">
                            <?php echo wp_get_attachment_image($meta['hero_background_image'], 'full', false, array(
                                'class' => 'h-full w-full object-cover object-center',
                            )); ?>
                            <div class="absolute inset-0 bg-black" style="opacity: <?php echo esc_attr($meta['hero_overlay_opacity'] / 100); ?>"></div>
                        </div>
                    <?php endif; ?>

                    <div class="relative h-full flex flex-col justify-center px-6 sm:px-12">
                        <div class="container mx-auto max-w-7xl">
                            <div class="flex flex-col justify-center h-full">
                                <h1 class="text-white text-4xl sm:text-6xl font-bold mb-4 leading-tight max-w-3xl">
                                    <?php echo esc_html($meta['boats_title']); ?>
                                </h1>
                                <?php if ($meta['boats_description']) : ?>
                                    <p class="text-gray-200 text-xl sm:text-2xl mb-8 max-w-2xl">
                                        <?php echo esc_html($meta['boats_description']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </header>
                <?php
                break;

            case 'filters':
                if ($meta['show_search'] || $meta['show_filters']) :
                ?>
                <div class="sticky top-0 z-10 bg-white border-b border-gray-200">
                    <div class="container mx-auto px-4 py-4">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex flex-col sm:flex-row gap-4 flex-grow lg:flex-grow-0">
                                <?php if ($meta['show_search']) : ?>
                                    <div class="relative flex-grow lg:w-64">
                                        <input type="text" 
                                               id="boat-search" 
                                               placeholder="Search boats..." 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    </div>
                                <?php endif; ?>

                                <?php if ($meta['show_filters']) : ?>
                                    <div class="flex flex-wrap gap-2 sm:flex-nowrap">
                                        <select id="type-filter" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                            <option value="">All Types</option>
                                            <option value="pontoon">Pontoon</option>
                                            <option value="ski">Ski & Wake</option>
                                            <option value="fishing">Fishing</option>
                                        </select>
                                        <select id="condition-filter" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                            <option value="">All Conditions</option>
                                            <option value="NEW">New</option>
                                            <option value="USED">Used</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                                <div class="flex items-center gap-2">
                                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                                    <select id="sort-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                        <option value="newest">Newest</option>
                                        <option value="price-asc">Price: Low to High</option>
                                        <option value="price-desc">Price: High to Low</option>
                                        <option value="length">Length</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endif;
                break;

            case 'inventory':
                // Inventory content
                ?>
                <div class="container mx-auto px-4 py-12">
                    <!-- Results Count -->
                    <p id="results-count" class="text-sm text-muted-foreground mb-4"></p>

                    <!-- Boats Grid -->
                    <div id="boats-grid" class="grid <?php echo esc_attr($meta['grid_columns']); ?> gap-8">
                        <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $posts_per_page = absint($meta['boats_per_page']);

                        $args = array(
                            'post_type' => 'boat',
                            'posts_per_page' => $posts_per_page,
                            'paged' => $paged,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'post_status' => 'publish'
                        );

                        $boats_query = new WP_Query($args);
                        $total_boats = $boats_query->found_posts;

                        if ($boats_query->have_posts()) :
                            while ($boats_query->have_posts()) : $boats_query->the_post();
                                // Get boat meta data
                                $condition = get_post_meta(get_the_ID(), '_boat_condition', true);
                                $manufacturer_terms = get_the_terms(get_the_ID(), 'boat_manufacturer');
                                $manufacturer_slug = (!is_wp_error($manufacturer_terms) && !empty($manufacturer_terms)) ? $manufacturer_terms[0]->slug : '';
                                $year = get_post_meta(get_the_ID(), '_boat_model_year', true);
                                $model = get_post_meta(get_the_ID(), '_boat_model', true);
                                $retail_price = get_post_meta(get_the_ID(), '_boat_retail_price', true);
                                $sales_price = get_post_meta(get_the_ID(), '_boat_sales_price', true);
                                $web_price = get_post_meta(get_the_ID(), '_boat_web_price', true);
                                $status = get_post_meta(get_the_ID(), '_boat_status', true);

                                // Get the proper permalink
                                $boat_url = get_permalink();
                        ?>
                        <div class="boat-card rounded-xl overflow-hidden bg-white shadow-md <?php echo esc_attr($meta['card_style']); ?> hover:<?php echo esc_attr($meta['hover_effect']); ?>" 
                             data-manufacturer="<?php echo esc_attr($manufacturer_slug); ?>"
                             data-condition="<?php echo esc_attr($condition); ?>"
                             data-name="<?php echo esc_attr(get_the_title()); ?>"
                             data-year="<?php echo esc_attr($year); ?>"
                             data-model="<?php echo esc_attr($model); ?>">
                            <a href="<?php echo esc_url($boat_url); ?>" class="block">
                                <div class="relative aspect-w-16 aspect-h-9 min-h-[400px]">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', array(
                                            'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                            'alt' => get_the_title()
                                        )); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full min-h-[400px] bg-gray-100 flex flex-col items-center justify-center">
                                            <i data-lucide="image-off" class="w-12 h-12 text-gray-400 mb-2"></i>
                                            <span class="text-sm text-gray-500 font-medium">No Image Available</span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($condition) : ?>
                                        <div class="absolute top-4 right-4">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium <?php echo $condition === 'NEW' ? 'bg-primary text-white' : 'bg-secondary text-secondary-foreground'; ?>">
                                                <?php echo esc_html($condition); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>

                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-2">
                                    <a href="<?php echo esc_url($boat_url); ?>" class="hover:text-primary transition-colors">
                                        <?php echo wades_get_boat_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php if ($year || $model) : ?>
                                    <p class="text-muted-foreground">
                                        <?php 
                                        $details = array_filter([$year, $model]);
                                        echo esc_html(implode(' ', $details));
                                        ?>
                                    </p>
                                <?php endif; ?>

                                <?php
                                // Determine which price to show (prioritize sales price)
                                $display_price = 0;
                                if (!empty($sales_price) && is_numeric($sales_price) && $sales_price > 0) {
                                    $display_price = $sales_price;
                                } elseif (!empty($retail_price) && is_numeric($retail_price) && $retail_price > 0) {
                                    $display_price = $retail_price;
                                } elseif (!empty($web_price) && is_numeric($web_price) && $web_price > 0) {
                                    $display_price = $web_price;
                                }

                                // Get boat SKU or model number for inquiry
                                $boat_sku = get_post_meta(get_the_ID(), '_boat_sku', true);
                                $inquiry_subject = urlencode("I'm interested in your: " . ($boat_sku ?: ($year . ' ' . $model)));
                                $contact_url = home_url('/contact') . '?subject=' . $inquiry_subject;
                                ?>
                                
                                <div class="mt-4 space-y-4">
                                    <p class="text-2xl font-bold text-primary"><?php echo wades_format_price($display_price); ?></p>

                                    <div class="flex items-center justify-between">
                                        <a href="<?php echo esc_url($contact_url); ?>" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                            Request Info
                                        </a>
                                        <a href="<?php echo esc_url($boat_url); ?>" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                            View Details
                                            <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <div class="col-span-full text-center py-12">
                                <h3 class="text-xl font-semibold mb-2">No boats found</h3>
                                <p class="text-muted-foreground">Check back later for new inventory.</p>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
                <?php
                break;

            case 'pagination':
                if ($total_boats > $posts_per_page) :
                ?>
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-12">
                    <div class="flex justify-center gap-2">
                        <?php
                        $pagination = paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => $paged,
                            'total' => ceil($total_boats / $posts_per_page),
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
                                    if (strpos($content, 'Previous') !== false || strpos($content, 'Next') !== false) {
                                        echo '<a href="' . esc_url($href) . '" class="' . esc_attr($classes) . '">' . $content . '</a>';
                                    } else {
                                        echo '<a href="' . esc_url($href) . '" class="' . esc_attr($classes) . '">' . $content . '</a>';
                                    }
                                } 
                                // If it's the current page (span)
                                elseif ($spans->length > 0) {
                                    echo '<span class="' . esc_attr($classes) . '">' . strip_tags($page_link) . '</span>';
                                }
                            endforeach;
                            
                            echo '</nav>';
                        endif;
                        ?>
                    </div>
                </div>
                <?php
                endif;
                break;
        endswitch;
    endforeach;
    ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const boatSearch = document.getElementById('boat-search');
    const typeFilter = document.getElementById('type-filter');
    const conditionFilter = document.getElementById('condition-filter');
    const sortFilter = document.getElementById('sort-filter');
    const boatCards = document.querySelectorAll('.boat-card');
    const resultsCount = document.getElementById('results-count');

    function filterBoats() {
        const searchTerm = boatSearch ? boatSearch.value.toLowerCase() : '';
        const type = typeFilter ? typeFilter.value.toLowerCase() : '';
        const condition = conditionFilter ? conditionFilter.value : '';
        let visibleCount = 0;

        boatCards.forEach(card => {
            const cardData = {
                name: card.dataset.name.toLowerCase(),
                model: card.dataset.model.toLowerCase(),
                year: card.dataset.year,
                condition: card.dataset.condition,
                manufacturer: card.dataset.manufacturer.toLowerCase()
            };

            const matchesSearch = !searchTerm || 
                                cardData.name.includes(searchTerm) || 
                                cardData.model.includes(searchTerm) ||
                                cardData.year.includes(searchTerm);
            
            const matchesType = !type || cardData.manufacturer.includes(type);
            const matchesCondition = !condition || cardData.condition === condition;

            if (matchesSearch && matchesType && matchesCondition) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        resultsCount.textContent = `Showing ${visibleCount} boat${visibleCount !== 1 ? 's' : ''}`;

        // Handle sorting
        if (sortFilter) {
            const sortValue = sortFilter.value;
            const sortedCards = Array.from(boatCards);
            const boatsGrid = document.getElementById('boats-grid');

            sortedCards.sort((a, b) => {
                switch (sortValue) {
                    case 'newest':
                        // Assuming data-year is available
                        return b.dataset.year - a.dataset.year;
                    case 'price-asc':
                        return getPriceValue(a) - getPriceValue(b);
                    case 'price-desc':
                        return getPriceValue(b) - getPriceValue(a);
                    default:
                        return 0;
                }
            });

            // Reorder the cards in the DOM
            sortedCards.forEach(card => {
                boatsGrid.appendChild(card);
            });
        }
    }

    // Helper function to get price value from card
    function getPriceValue(card) {
        const priceText = card.querySelector('.text-primary').textContent;
        // Remove '$' and ',' and convert to number
        return Number(priceText.replace(/[$,]/g, '')) || 0;
    }

    // Add event listeners
    if (boatSearch) boatSearch.addEventListener('input', filterBoats);
    if (typeFilter) typeFilter.addEventListener('change', filterBoats);
    if (conditionFilter) conditionFilter.addEventListener('change', filterBoats);
    if (sortFilter) sortFilter.addEventListener('change', filterBoats);

    // Initialize Lucide icons
    lucide.createIcons();

    // Initial filter
    filterBoats();

    // Initialize sticky toolbar behavior
    const toolbar = document.querySelector('.sticky');
    const toolbarOffset = toolbar.offsetTop;

    function updateToolbarSticky() {
        if (window.pageYOffset > toolbarOffset) {
            toolbar.classList.add('shadow-md');
        } else {
            toolbar.classList.remove('shadow-md');
        }
    }

    window.addEventListener('scroll', updateToolbarSticky);
});
</script>

<style>
/* Card Styles */
.boat-card.default {
    transition: all 0.2s ease-in-out;
}

.boat-card.minimal {
    box-shadow: none;
    border: 1px solid #e5e7eb;
}

.boat-card.featured {
    border: 2px solid #0f766e;
    box-shadow: 0 4px 6px -1px rgb(15 118 110 / 0.1), 0 2px 4px -2px rgb(15 118 110 / 0.1);
}

.boat-card.bordered {
    border: 2px solid #e5e7eb;
}

/* Hover Effects */
.boat-card.hover\:scale:hover {
    transform: scale(1.02);
}

.boat-card.hover\:lift:hover {
    transform: translateY(-4px);
}

.boat-card.hover\:glow:hover {
    box-shadow: 0 0 20px rgba(15, 118, 110, 0.2);
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

<?php get_footer(); ?> 