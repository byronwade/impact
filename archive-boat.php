<?php
/**
 * The template for displaying boat archives
 *
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col items-center justify-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Our Boat Inventory</h1>
            <p class="text-lg text-muted-foreground text-center max-w-2xl">
                Discover our extensive collection of new and used boats, featuring top brands and models to suit every boating lifestyle.
            </p>
        </div>
        
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
            if (have_posts()) :
                while (have_posts()) : the_post();
                    // Get boat meta data
                    $condition = get_post_meta(get_the_ID(), '_boat_condition', true);
                    $manufacturer_terms = get_the_terms(get_the_ID(), 'boat_manufacturer');
                    $manufacturer_slug = $manufacturer_terms ? $manufacturer_terms[0]->slug : '';
                    $year = get_post_meta(get_the_ID(), '_boat_model_year', true);
                    $model = get_post_meta(get_the_ID(), '_boat_model', true);
                    $retail_price = get_post_meta(get_the_ID(), '_boat_retail_price', true);
                    $sales_price = get_post_meta(get_the_ID(), '_boat_sales_price', true);
                    $web_price = get_post_meta(get_the_ID(), '_boat_web_price', true);
                    $status = get_post_meta(get_the_ID(), '_boat_status', true);
            ?>
            <div class="boat-card rounded-xl overflow-hidden bg-white shadow-md hover:shadow-lg transition-shadow" 
                 data-manufacturer="<?php echo esc_attr($manufacturer_slug); ?>"
                 data-condition="<?php echo esc_attr($condition); ?>"
                 data-name="<?php echo esc_attr(get_the_title()); ?>"
                 data-year="<?php echo esc_attr($year); ?>"
                 data-model="<?php echo esc_attr($model); ?>">
                <a href="<?php the_permalink(); ?>" class="block">
                    <div class="relative aspect-w-16 aspect-h-9">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array(
                                'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                'alt' => get_the_title()
                            )); ?>
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
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                            <?php the_title(); ?>
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
                    ?>
                    
                    <div class="mt-4 space-y-4">
                        <?php if ($display_price > 0) : ?>
                            <p class="text-2xl font-bold text-primary">$<?php echo number_format($display_price); ?></p>
                        <?php else : ?>
                            <p class="text-2xl font-bold text-primary">Call for Price</p>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <a href="#inquiry" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-input hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                Request Info
                            </a>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                View Details
                                <i data-lucide="chevron-right" class="ml-2 h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
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

        <!-- Pagination -->
        <?php if ($wp_query->max_num_pages > 1) : ?>
            <div class="mt-12 flex justify-center items-center gap-2">
                <?php
                echo paginate_links(array(
                    'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i> Previous',
                    'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>',
                    'type' => 'list',
                    'class' => 'pagination'
                ));
                ?>
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
                model: card.dataset.model.toLowerCase()
            };

            const matchesSearch = searchTerm === '' || 
                                cardData.name.includes(searchTerm) || 
                                cardData.model.includes(searchTerm) ||
                                cardData.year.includes(searchTerm);
            
            const matchesManufacturer = manufacturer === '' || cardData.manufacturer === manufacturer;
            const matchesCondition = condition === '' || cardData.condition === condition;

            if (matchesSearch && matchesManufacturer && matchesCondition) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        resultsCount.textContent = `Showing ${visibleCount} boat${visibleCount !== 1 ? 's' : ''}`;
    }

    // Add event listeners
    boatSearch.addEventListener('input', filterBoats);
    manufacturerFilter.addEventListener('change', filterBoats);
    conditionFilter.addEventListener('change', filterBoats);

    // Initialize Lucide icons
    lucide.createIcons();

    // Initial count
    filterBoats();
});
</script>

<style>
/* Pagination Styles */
.pagination {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
}

.pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.pagination .page-numbers.current {
    background-color: var(--primary);
    color: white;
}

.pagination .page-numbers:not(.current):hover {
    background-color: var(--accent);
    color: var(--accent-foreground);
}

.pagination .prev,
.pagination .next {
    display: inline-flex;
    align-items: center;
    padding: 0 1rem;
}
</style>

<?php get_footer(); ?> 