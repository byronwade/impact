<?php
/**
 * Template Name: About Template
 * 
 * @package wades
 */

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="bg-gray-50 pt-24">
        <div class="container mx-auto px-4 relative z-10 space-y-24 max-w-7xl pb-24">
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
                            'icon' => 'wrench',
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
                <div class="grid md:grid-cols-2 gap-0">
                    <!-- Contact Info -->
                    <div class="p-8 lg:p-12 bg-gradient-to-br from-blue-50 to-white">
                        <div class="max-w-lg">
                            <div class="mb-10">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 mb-4">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    Our Location
                                </span>
                                <h2 class="text-4xl font-bold mb-4">Visit Our Dealership</h2>
                                <p class="text-lg text-gray-600">Experience our exceptional service and explore our premium boat selection in person.</p>
                            </div>

                            <div class="space-y-8">
                                <!-- Address -->
                                <div class="flex items-start group">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4 group-hover:bg-blue-200 transition-colors">
                                        <i data-lucide="map-pin" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-1">Address</h3>
                                        <p class="text-gray-600">5185 Browns Bridge Rd</p>
                                        <a href="https://maps.google.com/?q=5185+Browns+Bridge+Rd" target="_blank" rel="noopener noreferrer" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 mt-2">
                                            Get Directions
                                            <i data-lucide="arrow-up-right" class="w-4 h-4 ml-1"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start group">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4 group-hover:bg-blue-200 transition-colors">
                                        <i data-lucide="phone" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-1">Phone</h3>
                                        <a href="tel:770-881-7808" class="text-gray-600 hover:text-blue-600 transition-colors">
                                            (770) 881-7808
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">Mon-Fri: 8am - 5pm</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-start group">
                                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4 group-hover:bg-blue-200 transition-colors">
                                        <i data-lucide="mail" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                        <a href="mailto:info@impactmarinegroup.com" class="text-gray-600 hover:text-blue-600 transition-colors">
                                            info@impactmarinegroup.com
                                        </a>
                                        <p class="text-sm text-gray-500 mt-1">We'll respond within 24 hours</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map and CTA -->
                    <div class="relative">
                        <!-- Map Image -->
                        <?php
                        $map_image = get_post_meta(get_the_ID(), '_map_image', true);
                        if ($map_image) :
                            echo wp_get_attachment_image($map_image, 'large', false, array(
                                'class' => 'w-full h-full object-cover',
                                'alt' => 'Location Map'
                            ));
                        else :
                            echo wades_get_image_html(0, 'large', array(
                                'class' => 'w-full h-full object-cover',
                                'alt' => 'Location Map'
                            ));
                        endif;
                        ?>

                        <!-- Overlay with CTA -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex items-end p-8">
                            <div class="bg-white rounded-xl shadow-lg p-8 w-full backdrop-blur-sm bg-white/95">
                                <div class="flex items-start gap-6">
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-bold mb-2">Ready to Experience Impact Marine?</h3>
                                        <p class="text-gray-600 mb-0">Visit our showroom or schedule a service appointment today.</p>
                                    </div>
                                    <a href="/contact" 
                                       class="flex-shrink-0 inline-flex items-center justify-center bg-blue-600 text-white rounded-lg px-6 py-3 font-medium hover:bg-blue-700 transition-colors group">
                                        Contact Us
                                        <i data-lucide="arrow-right" class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?> 