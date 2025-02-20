<?php
/**
 * Template Name: About Template
 * 
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="min-h-screen bg-gray-50">
        <main class="container mx-auto px-4 py-12 space-y-24 max-w-7xl">
            <!-- About Section -->
            <section id="about" class="space-y-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold mb-8"><?php echo wades_get_meta('about_title') ?: 'About Impact Marine Group'; ?></h2>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <?php 
                            $paragraphs = wades_get_meta('about_paragraphs');
                            if ($paragraphs && is_array($paragraphs)) :
                                foreach ($paragraphs as $paragraph) :
                                    echo '<p>' . wp_kses_post($paragraph) . '</p>';
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <div class="space-y-6">
                            <div class="relative h-64 md:h-80">
                                <?php 
                                $about_image_id = wades_get_meta('about_image');
                                if ($about_image_id) :
                                    echo wp_get_attachment_image($about_image_id, 'large', false, array(
                                        'class' => 'rounded-lg object-cover w-full h-full',
                                    ));
                                endif;
                                ?>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <?php 
                                $features = wades_get_meta('about_features');
                                if ($features && is_array($features)) :
                                    foreach ($features as $feature) :
                                ?>
                                    <div class="rounded-xl border bg-card text-card-foreground shadow">
                                        <div class="p-4">
                                            <?php if (!empty($feature['icon'])) : ?>
                                                <i data-lucide="<?php echo esc_attr($feature['icon']); ?>" class="w-8 h-8 mb-2 text-blue-600"></i>
                                            <?php endif; ?>
                                            <h3 class="font-semibold mb-1"><?php echo esc_html($feature['title']); ?></h3>
                                            <p class="text-sm"><?php echo wp_kses_post($feature['description']); ?></p>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Brands Section -->
            <section id="brands" class="space-y-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold mb-8"><?php echo wades_get_meta('brands_title') ?: 'Our Premium Brands'; ?></h2>
                    <h3 class="text-xl font-semibold mb-4"><?php echo wades_get_meta('brands_subtitle'); ?></h3>
                    
                    <?php 
                    $featured_brands = wades_get_meta('featured_brands');
                    if ($featured_brands && is_array($featured_brands)) :
                    ?>
                        <div class="space-y-6">
                            <?php foreach ($featured_brands as $brand) : ?>
                                <h4 class="text-lg font-semibold"><?php echo esc_html($brand['name']); ?></h4>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <?php echo wp_kses_post($brand['description']); ?>
                                        <?php if (!empty($brand['features']) && is_array($brand['features'])) : ?>
                                            <ul class="list-disc list-inside space-y-2">
                                                <?php foreach ($brand['features'] as $feature) : ?>
                                                    <li><?php echo esc_html($feature); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="relative h-64">
                                            <?php 
                                            if (!empty($brand['image'])) :
                                                echo wp_get_attachment_image($brand['image'], 'large', false, array(
                                                    'class' => 'rounded-lg object-cover w-full h-full',
                                                ));
                                            endif;
                                            ?>
                                        </div>
                                        <?php if (!empty($brand['models']) && is_array($brand['models'])) : ?>
                                            <div class="rounded-xl border bg-card text-card-foreground shadow">
                                                <div class="p-4">
                                                    <h3 class="font-semibold mb-2">Popular Models</h3>
                                                    <ul class="list-disc list-inside space-y-1">
                                                        <?php foreach ($brand['models'] as $model) : ?>
                                                            <li><?php echo esc_html($model); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Services Section -->
            <section id="services" class="space-y-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold mb-8"><?php echo wades_get_meta('services_title') ?: 'Comprehensive Marine Services'; ?></h2>
                    <h3 class="text-xl font-semibold mb-4"><?php echo wades_get_meta('services_subtitle'); ?></h3>
                    <div class="space-y-6">
                        <p class="text-lg"><?php echo wp_kses_post(wades_get_meta('services_description')); ?></p>
                        
                        <?php 
                        $service_cards = wades_get_meta('service_cards');
                        if ($service_cards && is_array($service_cards)) :
                        ?>
                            <div class="grid md:grid-cols-3 gap-6">
                                <?php foreach ($service_cards as $card) : ?>
                                    <div class="rounded-xl border bg-card text-card-foreground shadow">
                                        <div class="p-6">
                                            <?php if (!empty($card['icon'])) : ?>
                                                <i data-lucide="<?php echo esc_attr($card['icon']); ?>" class="w-12 h-12 mb-4 text-blue-600"></i>
                                            <?php endif; ?>
                                            <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($card['title']); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo wp_kses_post($card['description']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="space-y-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold mb-8"><?php echo wades_get_meta('testimonials_title') ?: 'Customer Testimonials'; ?></h2>
                    <h3 class="text-xl font-semibold mb-4"><?php echo wades_get_meta('testimonials_subtitle'); ?></h3>
                    
                    <?php 
                    $testimonials = wades_get_meta('testimonials');
                    if ($testimonials && is_array($testimonials)) :
                    ?>
                        <div class="grid md:grid-cols-2 gap-6">
                            <?php foreach ($testimonials as $testimonial) : ?>
                                <div class="rounded-xl border bg-card text-card-foreground shadow">
                                    <div class="p-6">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                                </div>
                                                <p class="mb-4 italic"><?php echo wp_kses_post($testimonial['content']); ?></p>
                                                <div>
                                                    <p class="font-semibold"><?php echo esc_html($testimonial['author']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="space-y-8">
                <div class="max-w-5xl mx-auto">
                    <h2 class="text-3xl font-bold mb-8"><?php echo wades_get_meta('contact_title') ?: 'Contact Us'; ?></h2>
                    <h3 class="text-xl font-semibold mb-4"><?php echo wades_get_meta('contact_subtitle'); ?></h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <?php if ($address = wades_get_meta('address')) : ?>
                                    <div class="flex items-center">
                                        <i data-lucide="map-pin" class="w-6 h-6 mr-2 text-blue-600"></i>
                                        <p><?php echo esc_html($address); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php 
                                $business_hours = wades_get_meta('business_hours');
                                if ($business_hours && is_array($business_hours)) :
                                ?>
                                    <div class="flex items-center">
                                        <i data-lucide="clock" class="w-6 h-6 mr-2 text-blue-600"></i>
                                        <div>
                                            <?php foreach ($business_hours as $hours) : ?>
                                                <p><?php echo esc_html($hours); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php 
                                $phone_numbers = wades_get_meta('phone_numbers');
                                if ($phone_numbers && is_array($phone_numbers)) :
                                ?>
                                    <div class="flex items-center">
                                        <i data-lucide="phone" class="w-6 h-6 mr-2 text-blue-600"></i>
                                        <div>
                                            <?php foreach ($phone_numbers as $phone) : ?>
                                                <p><?php echo esc_html($phone); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php 
                                $email_addresses = wades_get_meta('email_addresses');
                                if ($email_addresses && is_array($email_addresses)) :
                                ?>
                                    <div class="flex items-center">
                                        <i data-lucide="mail" class="w-6 h-6 mr-2 text-blue-600"></i>
                                        <div>
                                            <?php foreach ($email_addresses as $email) : ?>
                                                <p><?php echo esc_html($email); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php 
                            $service_areas = wades_get_meta('service_areas');
                            if ($service_areas && is_array($service_areas)) :
                            ?>
                                <div class="space-y-4">
                                    <h3 class="text-xl font-semibold"><?php echo wades_get_meta('service_areas_title'); ?></h3>
                                    <p><?php echo wp_kses_post(wades_get_meta('service_areas_description')); ?></p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <?php foreach ($service_areas as $area) : ?>
                                            <li><?php echo esc_html($area); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-6">
                            <div class="relative h-64 md:h-80">
                                <?php 
                                $map_image_id = wades_get_meta('map_image');
                                if ($map_image_id) :
                                    echo wp_get_attachment_image($map_image_id, 'large', false, array(
                                        'class' => 'rounded-lg object-cover w-full h-full',
                                        'alt' => 'Map to Impact Marine Group'
                                    ));
                                endif;
                                ?>
                            </div>
                            <div class="rounded-xl border bg-card text-card-foreground shadow">
                                <div class="p-4">
                                    <h3 class="font-semibold mb-2"><?php echo wades_get_meta('contact_cta_title'); ?></h3>
                                    <p class="text-sm mb-4"><?php echo wp_kses_post(wades_get_meta('contact_cta_description')); ?></p>
                                    <a href="<?php echo esc_url(wades_get_meta('contact_cta_link')); ?>" 
                                       class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full">
                                        <?php echo wades_get_meta('contact_cta_text') ?: 'Contact Us Now'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</main>

<?php get_footer(); ?> 