<?php
/**
 * Template Name: About Template
 * 
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <!-- Hero Section with Background -->
    <div class="relative bg-gradient-to-b from-gray-900 to-gray-800 text-white py-24">
        <div class="absolute inset-0 overflow-hidden">
            <?php 
            $about_image_id = wades_get_meta('about_image');
            if ($about_image_id) :
                echo wp_get_attachment_image($about_image_id, 'full', false, array(
                    'class' => 'w-full h-full object-cover opacity-20',
                ));
            endif;
            ?>
        </div>
        <div class="relative container mx-auto px-4 max-w-7xl">
            <h1 class="text-5xl md:text-6xl font-bold mb-6"><?php echo wades_get_meta('about_title') ?: 'About Impact Marine Group'; ?></h1>
            <div class="max-w-3xl">
                <p class="text-xl mb-4 text-gray-200">Impact Marine Group is dedicated to providing our customers personal service and quality boat brands. Whether you are flying through the air on a wakeboard or just learning, tearing up a slalom course or taking a few easy turns, slinging on a tube, or slowing it down Wake Surfing - Impact is your place to find great products and connect with people who share the love of being on the water.</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-50">
        <main class="container mx-auto px-4 -mt-16 relative z-10 space-y-24 max-w-7xl pb-24">
            <!-- Our Story Section -->
            <section class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <h2 class="text-3xl font-bold">Our Story</h2>
                        <div class="prose max-w-none">
                            <p>Located at 5185 Browns Bridge Rd, our boat sales and marine services location is dedicated to getting you exactly what you need. Here on our website you will find we provide the same great products, prices and service. When you shop from Impact – you can feel confident that there is a real person on the other end.</p>
                            <p>Our Marine Services Department is one of the most trusted service and repair centers in North Georgia. Our team of technicians are factory trained and certified by some of the biggest names in marine. Our team is proud Authorized Service Providers of Yamaha and Indmar Marine Engines.</p>
                            <p>We don't just sell the products – we use them. When we're not working to provide you with the best service and equipment, you'll find us on the water, trying out the latest and greatest.</p>
                        </div>
                    </div>
                    <div class="space-y-8">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-semibold mb-4">Our Specialties</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>Inboards</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>Yamaha Outboards</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>Inboard/Outboards</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>PWCs</span>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="text-xl font-semibold mb-4">Engine Expertise</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>Indmar Marine Engines</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>PCM & Ilmore</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>MerCruiser & Volvo Penta</span>
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                    <span>Yamaha & Sea-Doo PWCs</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php 
                $features = wades_get_meta('about_features');
                if ($features && is_array($features)) :
                    foreach ($features as $feature) :
                ?>
                    <div class="bg-white rounded-xl border shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex flex-col items-center text-center">
                            <?php if (!empty($feature['icon'])) : ?>
                                <i data-lucide="<?php echo esc_attr($feature['icon']); ?>" class="w-12 h-12 mb-4 text-blue-600"></i>
                            <?php endif; ?>
                            <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($feature['title']); ?></h3>
                            <p class="text-gray-600"><?php echo wp_kses_post($feature['description']); ?></p>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else:
                    $default_features = array(
                        array(
                            'icon' => 'shield',
                            'title' => 'Factory Certified',
                            'description' => 'Our technicians are factory trained and certified by leading marine manufacturers'
                        ),
                        array(
                            'icon' => 'tool',
                            'title' => 'Expert Service',
                            'description' => 'Comprehensive maintenance, repairs, and winterization services'
                        ),
                        array(
                            'icon' => 'map',
                            'title' => 'Wide Coverage',
                            'description' => 'Serving Lake Lanier and all Georgia Lakes with nationwide shipping'
                        ),
                        array(
                            'icon' => 'heart',
                            'title' => 'Passion for Boating',
                            'description' => 'We don\'t just sell boats - we live the boating lifestyle'
                        )
                    );
                    foreach ($default_features as $feature) :
                ?>
                    <div class="bg-white rounded-xl border shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex flex-col items-center text-center">
                            <i data-lucide="<?php echo esc_attr($feature['icon']); ?>" class="w-12 h-12 mb-4 text-blue-600"></i>
                            <h3 class="text-xl font-semibold mb-2"><?php echo esc_html($feature['title']); ?></h3>
                            <p class="text-gray-600"><?php echo wp_kses_post($feature['description']); ?></p>
                        </div>
                    </div>
                <?php 
                    endforeach;
                endif;
                ?>
            </section>

            <!-- Service Areas Section -->
            <section class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-xl p-12 text-white">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold mb-4">Areas We Serve</h2>
                    <p class="text-xl opacity-90">Proudly serving Georgia's finest lakes and beyond</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8">
                            <h3 class="text-2xl font-semibold mb-4">Georgia Lakes</h3>
                            <ul class="grid grid-cols-2 gap-4">
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    Lake Lanier
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    Lake Allatoona
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    Lake Burton
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    Lake Sinclair
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    Lake Hartwell
                                </li>
                                <li class="flex items-center">
                                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                                    All Georgia Lakes
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8">
                        <h3 class="text-2xl font-semibold mb-4">National Reach</h3>
                        <p class="mb-6">We are happy to ship nationally through impactmarinegroup.com. Our complete catalog includes:</p>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                                Marine Parts & Accessories
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                                Engine & Electrical Components
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                                Marine Lighting & Dock Supplies
                            </li>
                            <li class="flex items-center">
                                <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                                Cleaners & Maintenance Products
                            </li>
                        </ul>
                        <div class="mt-6 p-4 bg-white/5 rounded-lg">
                            <p class="text-sm">Not sure if the part you need is in stock? Give us a call at <a href="tel:770-881-7808" class="font-semibold hover:text-blue-200 transition-colors">770-881-7808</a></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="p-8 lg:p-12 space-y-8">
                        <div>
                            <h2 class="text-4xl font-bold mb-4">Visit Our Location</h2>
                            <p class="text-xl text-gray-600">Your trusted partner in marine excellence</p>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-start">
                                <i data-lucide="map-pin" class="w-6 h-6 text-blue-600 mt-1 mr-4"></i>
                                <p class="text-gray-700">5185 Browns Bridge Rd</p>
                            </div>

                            <?php 
                            $business_hours = wades_get_meta('business_hours');
                            if ($business_hours && is_array($business_hours)) :
                            ?>
                                <div class="flex items-start">
                                    <i data-lucide="clock" class="w-6 h-6 text-blue-600 mt-1 mr-4"></i>
                                    <div class="text-gray-700">
                                        <?php foreach ($business_hours as $hours) : ?>
                                            <p><?php echo esc_html($hours); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-start">
                                <i data-lucide="phone" class="w-6 h-6 text-blue-600 mt-1 mr-4"></i>
                                <div class="text-gray-700">
                                    <p><a href="tel:770-881-7808" class="hover:text-blue-600 transition-colors">770-881-7808</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <?php 
                        $map_image_id = wades_get_meta('map_image');
                        if ($map_image_id) :
                            echo wp_get_attachment_image($map_image_id, 'large', false, array(
                                'class' => 'absolute inset-0 w-full h-full object-cover',
                                'alt' => 'Map to Impact Marine Group'
                            ));
                        else:
                        ?>
                            <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                                <i data-lucide="map" class="w-24 h-24 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-8">
                            <div class="bg-white rounded-xl shadow-lg p-6 w-full">
                                <h3 class="font-semibold mb-2">Ready to Get Started?</h3>
                                <p class="text-sm text-gray-600 mb-4">Contact us today to discuss your boating needs or schedule a service appointment.</p>
                                <a href="/contact" 
                                   class="inline-flex items-center justify-center w-full bg-blue-600 text-white rounded-lg px-6 py-3 font-medium hover:bg-blue-700 transition-colors">
                                    Contact Us Now
                                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</main>

<?php get_footer(); ?> 