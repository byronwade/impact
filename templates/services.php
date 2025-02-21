<?php
/**
 * Template Name: Services Template
 * 
 * @package wades
 */

get_header(); ?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <main class="space-y-16">
            <!-- Hero Section -->
            <section class="text-center">
                <h1 class="text-4xl font-bold mb-4"><?php echo wades_get_meta('services_title') ?: 'Impact Marine Group Service Center'; ?></h1>
                <p class="text-xl text-muted-foreground max-w-3xl mx-auto mb-8"><?php echo wades_get_meta('services_description') ?: 'Expert marine services on Lake Lanier and beyond. We\'re boaters too, and we know how important it is to have your boat running right while keeping it affordable.'; ?></p>
                <a href="tel:+17708817808" class="hidden md:inline-flex items-center text-xs bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                    <i data-lucide="phone-call" class="h-3 w-3 mr-2"></i>
                    770-881-7808
                </a>
            </section>

            <!-- Services Grid -->
            <section>
                <h2 class="text-3xl font-semibold mb-8 text-center">Our Comprehensive Services</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $services = wades_get_meta('services_grid') ?: array(
                        array(
                            'icon' => 'wrench',
                            'title' => 'Comprehensive Engine Service',
                            'description' => 'Our Lead Techs have extensive experience with I/O, Inboard, and PWC engines. We\'re certified by leading manufacturers including Yamaha and Indmar Marine Engines, supporting our Tige boater community.'
                        ),
                        array(
                            'icon' => 'anchor',
                            'title' => 'Repairs & Troubleshooting',
                            'description' => 'From simple maintenance to comprehensive engine repair, our 10,000 sq ft shop is equipped to handle all your boating needs.'
                        ),
                        array(
                            'icon' => 'snowflake',
                            'title' => 'Winterization Services',
                            'description' => 'Protect your investment during the off-season with our thorough winterization services. We offer several packages to fit your boat\'s needs, ensuring it\'s ready to launch when spring arrives.'
                        ),
                        array(
                            'icon' => 'music',
                            'title' => 'Audio & Lighting Installation',
                            'description' => 'Upgrade your boat with the latest audio systems and LED lighting. Our technicians are skilled in installing and configuring a wide range of devices to enhance your boating experience.'
                        ),
                        array(
                            'icon' => 'sun',
                            'title' => 'Gel Coat & Fiberglass Repair',
                            'description' => 'Keep your boat looking its best with our expert gel coat and fiberglass repair services. We restore damage and maintain the pristine appearance of your vessel.'
                        ),
                        array(
                            'icon' => 'zap',
                            'title' => 'Wake Boat Performance',
                            'description' => 'We specialize in wake boat performance enhancements, including surf systems and ballast installation. Maximize your wake for the ultimate riding experience.'
                        ),
                        array(
                            'icon' => 'users',
                            'title' => 'On-Dock Lake Service',
                            'description' => 'We offer on-dock lake service for inboard and I/O boats. We also provide free pickup from nearby ramps and storage locations for your convenience.'
                        ),
                        array(
                            'icon' => 'shield',
                            'title' => 'Parts & Accessories',
                            'description' => 'Access our complete catalog of marine supplies, parts, and accessories. From ropes and bumpers to cleaners and waxes, we likely have what you need with next-day delivery for orders placed before 4 PM.'
                        )
                    );

                    foreach ($services as $service) :
                    ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                            <div class="p-6">
                                <i data-lucide="<?php echo esc_attr($service['icon']); ?>" class="h-12 w-12 mb-4"></i>
                                <h3 class="text-xl font-semibold mb-4"><?php echo esc_html($service['title']); ?></h3>
                                <p class="text-muted-foreground"><?php echo esc_html($service['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Why Choose Us Section -->
            <section class="bg-secondary rounded-lg p-8">
                <h2 class="text-3xl font-semibold text-secondary-foreground mb-6 text-center">Why Choose Impact Marine Group?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <ul class="space-y-4">
                        <?php
                        $reasons = wades_get_meta('why_choose_us') ?: array(
                            'Over 10,000 sq ft of fully equipped shop space',
                            'Certified technicians with extensive experience',
                            'Authorized Indmar and Yamaha Outboards service center',
                            'Serving Lake Lanier, Lake Allatoona, Lake Burton, and more',
                            'On-dock lake service and free local pickup available',
                            'Comprehensive parts catalog with quick delivery'
                        );

                        foreach ($reasons as $reason) :
                        ?>
                            <li class="flex items-center">
                                <i data-lucide="anchor" class="h-5 w-5 mr-2"></i>
                                <span class="text-secondary-foreground"><?php echo esc_html($reason); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="relative w-full h-64 md:h-full rounded-lg overflow-hidden">
                        <?php
                        $service_image = wades_get_meta('service_image');
                        if ($service_image) :
                            echo wp_get_attachment_image($service_image, 'large', false, array(
                                'class' => 'absolute inset-0 w-full h-full object-cover',
                                'alt' => 'Impact Marine Group Service Center'
                            ));
                        else :
                        ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/service-department.webp" alt="Impact Marine Group Service Center" class="absolute inset-0 w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Winterization Packages -->
            <section>
                <h2 class="text-3xl font-semibold mb-6 text-center">2024 Boat Winterization Specials</h2>
                <p class="text-center text-muted-foreground mb-8">Save Time, Save Money, And Protect Your Investment.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $winterization_packages = wades_get_meta('winterization_packages') ?: array(
                        array(
                            'title' => 'Winterization Station',
                            'description' => 'While You Wait or Same Day Pick Up – Complete Protection with the convenience of Same Day Service',
                            'services' => array(
                                'Draining Water',
                                'Anti-Freeze throughout system',
                                'Fogging Oil',
                                'Fuel Stabilizer'
                            ),
                            'price' => '$229',
                            'note' => 'Up to 3 gallons of anti-freeze – additional may incur additional fee'
                        ),
                        array(
                            'title' => 'Winterization - Drop Off Only',
                            'description' => 'Complete Protection – Your boat will be ready to go in the spring',
                            'services' => array(
                                'Draining Water',
                                'Anti-Freeze throughout engine',
                                'Fogging Oil',
                                'Fuel Stabilizer'
                            ),
                            'price' => '$199',
                            'note' => 'Up to 3 gallons of anti-freeze – additional may incur additional fee'
                        ),
                        array(
                            'title' => 'Winterize and Oil Change Special',
                            'description' => 'Protect for the winter and be ready for spring!',
                            'services' => array(
                                'Complete winterization',
                                'Complete Oil Change'
                            ),
                            'price' => '$399',
                            'note' => 'Up to 6 quarts of standard oil and filter – additional may incur additional fee'
                        )
                    );

                    foreach ($winterization_packages as $package) :
                    ?>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-4"><?php echo esc_html($package['title']); ?></h3>
                                <p class="text-muted-foreground mb-4"><?php echo esc_html($package['description']); ?></p>
                                <ul class="list-disc list-inside text-muted-foreground mb-4">
                                    <?php foreach ($package['services'] as $service) : ?>
                                        <li><?php echo esc_html($service); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <p class="font-semibold text-2xl mb-2 text-primary"><?php echo esc_html($package['price']); ?></p>
                                <p class="text-sm text-muted-foreground"><?php echo esc_html($package['note']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="text-center">
                <h2 class="text-3xl font-semibold mb-4">Ready to Schedule Your Service?</h2>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-8">Our team is standing by to assist you with all your boating service needs. Don't wait, call now to speak with a service advisor!</p>
                <a href="tel:+17708817808" class="hidden md:inline-flex items-center text-xs bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition-colors">
                    <i data-lucide="phone-call" class="h-3 w-3 mr-2"></i>
                    770-881-7808
                </a>
            </section>

            <!-- Service Policies -->
            <section class="bg-muted rounded-lg p-8">
                <h2 class="text-2xl font-semibold mb-4">Important Service Policies</h2>
                <ul class="list-disc list-inside space-y-2 text-muted-foreground">
                    <?php
                    $service_policies = wades_get_meta('service_policies') ?: array(
                        'Leave your keys in the ignition',
                        'Provide us with your current engine hours',
                        'Cover your boat or we\'ll assume you prefer it uncovered',
                        'Remove personal items to keep labor costs down',
                        'Invoices must be paid before boat can be released',
                        'Pick up vessels within 3 days of completion to avoid storage fees',
                        'Parts over $300 or non-returnable parts require a deposit',
                        'Remote services must be paid for in advance'
                    );

                    foreach ($service_policies as $policy) :
                    ?>
                        <li><?php echo esc_html($policy); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </div>
</main>

<?php get_footer(); ?> 