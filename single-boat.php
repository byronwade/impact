<?php
/**
 * Single Boat Template
 *
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
    <article class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <?php while (have_posts()) : the_post(); 
            // Get all meta data
            $stock_number = get_post_meta(get_the_ID(), '_boat_stock_number', true);
            $model_year = get_post_meta(get_the_ID(), '_boat_model_year', true);
            $model = get_post_meta(get_the_ID(), '_boat_model', true);
            $trim = get_post_meta(get_the_ID(), '_boat_trim', true);
            $retail_price = get_post_meta(get_the_ID(), '_boat_retail_price', true);
            $sales_price = get_post_meta(get_the_ID(), '_boat_sales_price', true);
            $web_price = get_post_meta(get_the_ID(), '_boat_web_price', true);
            $discount = get_post_meta(get_the_ID(), '_boat_discount', true);
            $floorplan = get_post_meta(get_the_ID(), '_boat_floorplan', true);

            // Get taxonomy terms
            $location = wp_get_post_terms(get_the_ID(), 'boat_location', array('fields' => 'names'));
            $condition = wp_get_post_terms(get_the_ID(), 'boat_condition', array('fields' => 'names'));
            $status = wp_get_post_terms(get_the_ID(), 'boat_status', array('fields' => 'names'));
            $manufacturer = wp_get_post_terms(get_the_ID(), 'boat_manufacturer', array('fields' => 'names'));

            $location = !empty($location) ? $location[0] : '';
            $condition = !empty($condition) ? $condition[0] : '';
            $status = !empty($status) ? $status[0] : '';
            $manufacturer = !empty($manufacturer) ? $manufacturer[0] : '';
        ?>

        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-muted-foreground">
                <li><a href="<?php echo home_url(); ?>" class="hover:text-primary">Home</a></li>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <li><a href="<?php echo get_post_type_archive_link('boat'); ?>" class="hover:text-primary">Boats</a></li>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <li class="text-primary font-medium"><?php the_title(); ?></li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="relative aspect-w-16 aspect-h-9 rounded-xl overflow-hidden bg-white shadow-lg">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array(
                            'class' => 'object-cover w-full h-full',
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
            </div>

            <!-- Boat Details -->
            <div class="space-y-8">
                <div>
                    <h1 class="text-4xl font-bold mb-2"><?php the_title(); ?></h1>
                    <div class="flex items-center gap-4 text-lg text-muted-foreground">
                        <?php if ($model_year) : ?>
                            <span><?php echo esc_html($model_year); ?></span>
                        <?php endif; ?>
                        <?php if ($manufacturer) : ?>
                            <span>•</span>
                            <span><?php echo esc_html($manufacturer); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <?php if ($sales_price) : ?>
                            <span class="text-3xl font-bold">$<?php echo number_format($sales_price); ?></span>
                        <?php endif; ?>
                        <?php if ($status) : ?>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-primary/10 text-primary">
                                <?php echo esc_html($status); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <?php if ($stock_number) : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Stock Number</span>
                                <p class="font-medium"><?php echo esc_html($stock_number); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($location) : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Location</span>
                                <p class="font-medium"><?php echo esc_html($location); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($model) : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Model</span>
                                <p class="font-medium"><?php echo esc_html($model); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($trim) : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Trim</span>
                                <p class="font-medium"><?php echo esc_html($trim); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($retail_price && $retail_price != '0') : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Retail Price</span>
                                <p class="font-medium">$<?php echo number_format($retail_price); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($web_price && $web_price != '0') : ?>
                            <div>
                                <span class="text-sm text-muted-foreground">Web Price</span>
                                <p class="font-medium">$<?php echo number_format($web_price); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <?php if (get_the_content()) : ?>
                    <div class="prose max-w-none">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <!-- Contact Buttons -->
                <div class="flex gap-4">
                    <a href="#request-info" class="flex-1 inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-medium bg-primary text-white hover:bg-primary/90 transition-colors">
                        Request Information
                    </a>
                    <a href="tel:+17708817808" class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-medium border border-input hover:bg-accent hover:text-accent-foreground transition-colors">
                        <i data-lucide="phone" class="w-5 h-5 mr-2"></i>
                        Call Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Similar Boats -->
        <?php
        $similar_args = array(
            'post_type' => 'boat',
            'posts_per_page' => 3,
            'post__not_in' => array(get_the_ID()),
            'orderby' => 'rand',
            'meta_query' => array(
                array(
                    'key' => '_boat_type',
                    'value' => $model,
                    'compare' => '='
                )
            )
        );
        $similar_boats = new WP_Query($similar_args);

        if ($similar_boats->have_posts()) :
        ?>
        <section class="mt-16">
            <h2 class="text-2xl font-semibold mb-8">Similar Boats</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php while ($similar_boats->have_posts()) : $similar_boats->the_post(); 
                    $s_condition = get_post_meta(get_the_ID(), '_boat_condition', true);
                    $s_price = get_post_meta(get_the_ID(), '_boat_price', true);
                    $s_year = get_post_meta(get_the_ID(), '_boat_year', true);
                    $s_type = get_post_meta(get_the_ID(), '_boat_type', true);
                ?>
                <a href="<?php the_permalink(); ?>" class="group">
                    <div class="rounded-xl overflow-hidden bg-white shadow-md">
                        <div class="relative aspect-w-16 aspect-h-9">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array(
                                    'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
                                    'alt' => get_the_title()
                                )); ?>
                            <?php endif; ?>
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $s_condition === 'NEW' ? 'bg-primary text-white' : 'bg-secondary text-secondary-foreground'; ?>">
                                    <?php echo esc_html($s_condition); ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold"><?php the_title(); ?></h3>
                            <p class="text-muted-foreground text-sm"><?php echo esc_html($s_year . ' ' . $s_type); ?></p>
                            <p class="font-semibold mt-2">$<?php echo number_format($s_price); ?></p>
                        </div>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
        <?php endif; ?>

        <?php endwhile; ?>
    </article>
</main>

<?php get_footer(); ?> 