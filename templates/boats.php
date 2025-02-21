<?php
/**
 * Template Name: Boats Template
 * 
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-4xl font-bold text-center mb-8"><?php echo wades_get_meta('boats_title') ?: 'Our Boat Inventory'; ?></h1>
        
        <!-- Search and Filter Section -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:w-96">
                <input type="text" id="boat-search" placeholder="Search boats..." class="pl-10 w-full rounded-lg border border-input bg-background px-3 py-2">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <select id="manufacturer-filter" class="w-full sm:w-[200px] rounded-lg border border-input bg-background px-3 py-2">
                    <option value="">All Manufacturers</option>
                    <?php
                    $manufacturers = get_terms(array(
                        'taxonomy' => 'boat_manufacturer',
                        'hide_empty' => true
                    ));
                    
                    if (!empty($manufacturers) && !is_wp_error($manufacturers)) {
                        foreach ($manufacturers as $manufacturer) {
                            echo '<option value="' . esc_attr($manufacturer->slug) . '">' . esc_html($manufacturer->name) . '</option>';
                        }
                    }
                    ?>
                </select>
                <select id="condition-filter" class="w-full sm:w-[200px] rounded-lg border border-input bg-background px-3 py-2">
                    <option value="">All Conditions</option>
                    <option value="NEW">New</option>
                    <option value="USED">Used</option>
                </select>
            </div>
        </div>

        <!-- Results Count -->
        <p id="results-count" class="text-sm text-muted-foreground mb-4"></p>

        <!-- Boats Grid -->
        <div id="boats-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $posts_per_page = 12;

            $args = array(
                'post_type' => 'boat',
                'posts_per_page' => $posts_per_page,
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => $paged
            );

            $boats_query = new WP_Query($args);
            $total_boats = $boats_query->found_posts;

            if ($boats_query->have_posts()) :
                while ($boats_query->have_posts()) : $boats_query->the_post();
                    $boat_meta = get_post_meta(get_the_ID());
                    $condition = get_post_meta(get_the_ID(), '_boat_condition', true);
                    $price = get_post_meta(get_the_ID(), '_boat_price', true);
                    $status = get_post_meta(get_the_ID(), '_boat_status', true);
                    $year = get_post_meta(get_the_ID(), '_boat_year', true);
                    $type = get_post_meta(get_the_ID(), '_boat_type', true);
                    $manufacturer = get_post_meta(get_the_ID(), '_boat_manufacturer', true);
                    $model = get_post_meta(get_the_ID(), '_boat_model', true);

                    // Get manufacturer term
                    $manufacturer_terms = get_the_terms(get_the_ID(), 'boat_manufacturer');
                    $manufacturer_slug = $manufacturer_terms ? $manufacturer_terms[0]->slug : '';
            ?>
            <div class="boat-card rounded-xl overflow-hidden bg-white shadow-md" 
                 data-manufacturer="<?php echo esc_attr($manufacturer_slug); ?>"
                 data-condition="<?php echo esc_attr($condition); ?>"
                 data-name="<?php echo esc_attr(get_the_title()); ?>"
                 data-year="<?php echo esc_attr($year); ?>"
                 data-type="<?php echo esc_attr($type); ?>"
                 data-model="<?php echo esc_attr($model); ?>">
                <div class="relative aspect-w-16 aspect-h-9">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array(
                            'class' => 'transition-transform duration-300 hover:scale-105 object-cover w-full h-full',
                            'alt' => get_the_title()
                        )); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.webp" 
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             class="transition-transform duration-300 hover:scale-105 object-cover w-full h-full">
                    <?php endif; ?>
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $condition === 'NEW' ? 'bg-primary text-white' : 'bg-secondary text-secondary-foreground'; ?>">
                            <?php echo esc_html($condition); ?>
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold leading-none tracking-tight flex justify-between items-center">
                        <span><?php the_title(); ?></span>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium">
                            <?php echo esc_html($year); ?>
                        </span>
                    </h3>
                    <p class="text-muted-foreground mb-2 mt-2"><?php echo esc_html($type); ?></p>
                    <p class="font-semibold text-lg">$<?php echo number_format($price); ?></p>
                    <p class="text-sm text-muted-foreground">Status: <?php echo esc_html($status); ?></p>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="#" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                            Request Info
                        </a>
                        <a href="<?php the_permalink(); ?>" 
                           class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                            View Details
                            <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            endif;
            ?>
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-12">
            <h3 class="text-xl font-semibold mb-2">No boats found</h3>
            <p class="text-muted-foreground">Try adjusting your search or filters to find what you're looking for.</p>
        </div>

        <!-- Pagination -->
        <?php if ($total_boats > $posts_per_page) : ?>
        <div class="mt-12 flex justify-center items-center gap-2">
            <?php
            $total_pages = ceil($total_boats / $posts_per_page);
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const boatSearch = document.getElementById('boat-search');
    const manufacturerFilter = document.getElementById('manufacturer-filter');
    const conditionFilter = document.getElementById('condition-filter');
    const boatCards = document.querySelectorAll('.boat-card');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const boatsGrid = document.getElementById('boats-grid');

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    const manufacturerParam = urlParams.get('manufacturer');
    const conditionParam = urlParams.get('condition');

    // Set initial filter values from URL parameters
    if (searchParam) boatSearch.value = searchParam;
    if (manufacturerParam) manufacturerFilter.value = manufacturerParam;
    if (conditionParam) conditionFilter.value = conditionParam;

    function filterBoats() {
        const searchTerm = boatSearch.value.toLowerCase();
        const manufacturer = manufacturerFilter.value.toLowerCase();
        const condition = conditionFilter.value;
        let visibleCount = 0;

        boatCards.forEach(card => {
            const cardData = {
                manufacturer: card.dataset.manufacturer.toLowerCase(),
                condition: card.dataset.condition,
                name: card.dataset.name.toLowerCase(),
                year: card.dataset.year,
                type: card.dataset.type.toLowerCase(),
                model: card.dataset.model.toLowerCase()
            };

            const matchesSearch = searchTerm === '' || 
                                cardData.name.includes(searchTerm) || 
                                cardData.type.includes(searchTerm) || 
                                cardData.model.includes(searchTerm) ||
                                cardData.year.includes(searchTerm);
            
            const matchesManufacturer = manufacturer === '' || cardData.manufacturer === manufacturer;
            const matchesCondition = condition === '' || cardData.condition === condition;

            if (matchesSearch && matchesManufacturer && matchesCondition) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Update results count and visibility
        resultsCount.textContent = `Showing ${visibleCount} boat${visibleCount !== 1 ? 's' : ''}`;
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            boatsGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            boatsGrid.classList.remove('hidden');
        }

        // Update URL with filter parameters
        const params = new URLSearchParams();
        if (searchTerm) params.set('search', searchTerm);
        if (manufacturer) params.set('manufacturer', manufacturer);
        if (condition) params.set('condition', condition);
        
        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        window.history.replaceState({}, '', newUrl);
    }

    // Add event listeners with debounce for search
    let searchTimeout;
    boatSearch.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterBoats, 300);
    });

    // Immediate filtering for dropdowns
    manufacturerFilter.addEventListener('change', filterBoats);
    conditionFilter.addEventListener('change', filterBoats);

    // Initial filtering
    filterBoats();
});
</script>

<?php get_footer(); ?> 