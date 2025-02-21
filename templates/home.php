<?php
/**
 * Template Name: Home Template
 * 
 * @package wades
 */

get_header();

// Get sections configuration
$sections = get_post_meta(get_the_ID(), '_home_sections', true);

// Initialize hero meta with defaults
$hero_meta = array(
    'backgroundVideo' => array('url' => get_post_meta(get_the_ID(), '_hero_background_video', true)),
    'backgroundImage' => array('url' => get_post_meta(get_the_ID(), '_hero_background_image', true)),
    'title' => get_post_meta(get_the_ID(), '_hero_title', true) ?: 'Expert Boat Service You Can Trust',
    'description' => get_post_meta(get_the_ID(), '_hero_description', true) ?: 'Certified Technicians, Fast Turnaround, and Unmatched Care for Your Boat.',
    'phoneNumber' => get_post_meta(get_the_ID(), '_hero_phone_number', true) ?: '(770) 881-7808',
    'rating' => array(
        'text' => get_post_meta(get_the_ID(), '_hero_rating_text', true) ?: '4.9 Star Rated',
        'value' => get_post_meta(get_the_ID(), '_hero_rating_value', true) ?: 4.9
    ),
    'primaryCta' => array(
        'label' => get_post_meta(get_the_ID(), '_hero_primary_cta_label', true) ?: 'Schedule Service',
        'link' => get_post_meta(get_the_ID(), '_hero_primary_cta_link', true) ?: '/schedule',
        'icon' => get_post_meta(get_the_ID(), '_hero_primary_cta_icon', true) ?: 'calendar'
    ),
    'secondaryCta' => array(
        'label' => get_post_meta(get_the_ID(), '_hero_secondary_cta_label', true) ?: 'View Our Services',
        'link' => get_post_meta(get_the_ID(), '_hero_secondary_cta_link', true) ?: '/services',
        'icon' => get_post_meta(get_the_ID(), '_hero_secondary_cta_icon', true) ?: 'chevron-right'
    )
);

// Initialize CTA section variables with defaults
$cta_title = get_post_meta(get_the_ID(), '_cta_title', true) ?: 'Ready to Experience the Best in Boating?';
$cta_description = get_post_meta(get_the_ID(), '_cta_description', true) ?: 'Join the Impact Marine family and discover why we\'re Georgia\'s premier boat dealer.';
$cta_button_text = get_post_meta(get_the_ID(), '_cta_button_text', true) ?: 'Contact Us Today';
$cta_button_link = get_post_meta(get_the_ID(), '_cta_button_link', true) ?: '/contact';

// Default sections configuration if not set
if (!is_array($sections)) {
    $sections = array(
        'hero' => array(
            'enabled' => true,
            'order' => 10,
            'title' => 'Hero Section'
        ),
        'featured_brands' => array(
            'enabled' => true,
            'order' => 20,
            'title' => 'Featured Brands'
        ),
        'fleet' => array(
            'enabled' => true,
            'order' => 30,
            'title' => 'Fleet Section'
        ),
        'services' => array(
            'enabled' => true,
            'order' => 40,
            'title' => 'Services Section'
        ),
        'testimonials' => array(
            'enabled' => true,
            'order' => 50,
            'title' => 'Testimonials'
        ),
        'social' => array(
            'enabled' => true,
            'order' => 60,
            'title' => 'Social Feed'
        ),
        'cta' => array(
            'enabled' => true,
            'order' => 70,
            'title' => 'Call to Action'
        )
    );
}

// Sort sections by order
uasort($sections, function($a, $b) {
    return $a['order'] - $b['order'];
});

// Get all section meta with defaults
// ... existing meta data retrieval code ...

?>

<main id="primary" class="site-main">
    <?php foreach ($sections as $section_id => $section) : ?>
        <?php if (!$section['enabled']) continue; ?>
        
        <?php if ($section_id === 'hero') : ?>
            <!-- Hero Section -->
            <section class="relative h-[90vh] flex items-center justify-center overflow-hidden">
                <!-- Background Video/Image Container -->
                <div class="absolute right-0 w-2/3 h-full" style="z-index: 1">
                    <?php if ($hero_meta['backgroundVideo']['url']) : ?>
                        <video class="absolute inset-0 w-full h-full object-cover" autoPlay muted loop playsInline poster="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>">
                            <source src="<?php echo esc_url($hero_meta['backgroundVideo']['url']); ?>" type="video/mp4">
                            <?php if ($hero_meta['backgroundImage']['url']) : ?>
                                <img src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>" alt="Video fallback" class="absolute inset-0 w-full h-full object-cover">
                            <?php endif; ?>
                        </video>
                    <?php elseif ($hero_meta['backgroundImage']['url']) : ?>
                        <img src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>" alt="Hero background" class="absolute inset-0 w-full h-full object-cover">
                    <?php endif; ?>
                </div>

                <!-- Base dark overlay for left side -->
                <div class="absolute left-0 w-1/3 h-full bg-black" style="z-index: 2"></div>

                <!-- Gradient overlay (middle layer) -->
                <div class="absolute inset-0 pointer-events-none" style="z-index: 2">
                    <!-- Left to right gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black from-30% via-black/90 via-50% to-transparent to-100%"></div>
                </div>

                <!-- Content Container (top layer) -->
                <div class="relative container mx-auto px-4 py-12 sm:py-24 lg:py-32" style="z-index: 3">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Rating Badge - Moved to top -->
                            <?php if ($hero_meta['rating']) : ?>
                                <div class="mb-6">
                                    <div class="inline-flex items-center px-4 py-2 text-lg bg-white/10 backdrop-blur-sm text-white rounded-lg border border-white/20">
                                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="font-bold"><?php echo esc_html($hero_meta['rating']['text']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight text-white tracking-tight" style="content-visibility: auto">
                                <?php echo esc_html($hero_meta['title']); ?>
                            </h1>
                            <p class="text-xl sm:text-2xl text-white/90 max-w-2xl leading-relaxed">
                                <?php echo esc_html($hero_meta['description']); ?>
                            </p>

                            <div class="flex flex-wrap gap-4 pt-4">
                                <?php if ($hero_meta['primaryCta']['label']) : ?>
                                    <a href="<?php echo esc_url($hero_meta['primaryCta']['link']); ?>" 
                                       class="group inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-blue-500/25">
                                        <?php if ($hero_meta['primaryCta']['icon'] === 'calendar') : ?>
                                            <i data-lucide="calendar" class="w-5 h-5 mr-2 transition-transform group-hover:scale-110"></i>
                                        <?php endif; ?>
                                        <?php echo esc_html($hero_meta['primaryCta']['label']); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($hero_meta['secondaryCta']['label']) : ?>
                                    <a href="<?php echo esc_url($hero_meta['secondaryCta']['link']); ?>" 
                                       class="group inline-flex items-center px-8 py-4 text-lg font-medium bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:bg-white/20 rounded-lg transition-all duration-300">
                                        <?php echo esc_html($hero_meta['secondaryCta']['label']); ?>
                                        <?php if ($hero_meta['secondaryCta']['icon'] === 'chevron-right') : ?>
                                            <i data-lucide="chevron-right" class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1"></i>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if ($hero_meta['phoneNumber']) : ?>
                                <div class="flex items-center text-white mt-8 pt-4 border-t border-white/10">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm mr-4">
                                        <i data-lucide="phone-call" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-white/70 mb-1">24/7 Support</div>
                                        <span class="text-2xl font-semibold tracking-wide"><?php echo esc_html($hero_meta['phoneNumber']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'featured_brands') : ?>
            <!-- Featured Brands Section -->
            <section aria-label="Featured Brands" class="bg-muted py-6 overflow-hidden hidden md:block">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap justify-center gap-8 sm:gap-12 md:gap-16">
                        <?php
                        $brand_logos = array(
                            array(
                                'url' => 'https://cdn.sanity.io/images/f9jkdh97/production/f507e2c18f446b9ad350d5a53af5d0c2c9229329-128x26.svg',
                                'alt' => 'Sea Fox logo',
                                'width' => 128,
                                'height' => 26
                            ),
                            array(
                                'url' => 'https://cdn.sanity.io/images/f9jkdh97/production/f507e2c18f446b9ad350d5a53af5d0c2c9229329-128x26.svg',
                                'alt' => 'Bennington logo',
                                'width' => 128,
                                'height' => 26
                            ),
                            array(
                                'url' => 'https://cdn.sanity.io/images/f9jkdh97/production/f507e2c18f446b9ad350d5a53af5d0c2c9229329-128x26.svg',
                                'alt' => 'Yamaha logo',
                                'width' => 128,
                                'height' => 26
                            ),
                            array(
                                'url' => 'https://cdn.sanity.io/images/f9jkdh97/production/f507e2c18f446b9ad350d5a53af5d0c2c9229329-128x26.svg',
                                'alt' => 'Mercury logo',
                                'width' => 128,
                                'height' => 26
                            ),
                            array(
                                'url' => 'https://cdn.sanity.io/images/f9jkdh97/production/f507e2c18f446b9ad350d5a53af5d0c2c9229329-128x26.svg',
                                'alt' => 'Suzuki logo',
                                'width' => 128,
                                'height' => 26
                            )
                        );

                        // Display only first two logos
                        for ($i = 0; $i < 2; $i++) : 
                            $logo = $brand_logos[$i];
                        ?>
                            <div class="flex items-center justify-center w-1/2 sm:w-1/3 md:w-1/4 lg:w-auto">
                                <img 
                                    alt="<?php echo esc_attr($logo['alt']); ?>"
                                    loading="lazy"
                                    width="<?php echo esc_attr($logo['width']); ?>"
                                    height="<?php echo esc_attr($logo['height']); ?>"
                                    decoding="async"
                                    data-nimg="1"
                                    class="w-auto h-[24px] sm:h-[28px] md:h-[32px] grayscale hover:grayscale-0 transition-all duration-300"
                                    style="color:transparent"
                                    src="<?php echo esc_url($logo['url']); ?>"
                                >
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'fleet') : ?>
            <!-- Fleet Section -->
            <section id="fleet" aria-labelledby="fleet-heading" class="py-16">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <h2 id="fleet-heading" class="text-4xl font-bold tracking-tighter text-center mb-8">Discover Our Premium Fleet</h2>
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                        <div class="w-full lg:w-3/5 relative">
                            <div class="relative aspect-video overflow-hidden rounded-xl shadow-2xl">
                                <img 
                                    alt="Test - Luxury boat by Impact Marine Group" 
                                    loading="eager" 
                                    decoding="async" 
                                    data-nimg="fill" 
                                    class="object-cover transition-transform duration-500 hover:scale-105" 
                                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw" 
                                    src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>"
                                    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;"
                                >
                            </div>
                            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground h-9 w-9 absolute top-1/2 left-4 -translate-y-1/2 bg-background/80 hover:bg-background" aria-label="Previous boat">
                                <i data-lucide="chevron-left" class="h-6 w-6"></i>
                            </button>
                            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input shadow-sm hover:text-accent-foreground h-9 w-9 absolute top-1/2 right-4 -translate-y-1/2 bg-background/80 hover:bg-background" aria-label="Next boat">
                                <i data-lucide="chevron-right" class="h-6 w-6"></i>
                            </button>
                        </div>
                        <div class="w-full lg:w-2/5 space-y-6">
                            <div>
                                <h3 class="text-3xl font-bold mb-2">Test</h3>
                                <p class="text-lg text-foreground mb-4">gergwrwerg</p>
                                <div class="flex flex-wrap gap-4 mb-6">
                                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                        <i data-lucide="waves" class="w-4 h-4 mr-1"></i>
                                        Length: 120 ft
                                    </div>
                                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                        <i data-lucide="anchor" class="w-4 h-4 mr-1"></i>
                                        Capacity: 12 guests
                                    </div>
                                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 text-foreground text-sm font-medium">
                                        <i data-lucide="wind" class="w-4 h-4 mr-1"></i>
                                        Speed: 25 knots
                                    </div>
                                </div>
                                <p class="text-2xl font-bold mb-6">$300,000</p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 rounded-md w-full sm:w-auto text-lg px-8 py-3">
                                    Request a Viewing
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'services') : ?>
            <!-- Services Section -->
            <section id="services" aria-labelledby="services-heading" class="py-24 bg-muted">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 mb-4 text-foreground font-medium">
                            <i data-lucide="award" class="w-4 h-4 mr-2"></i>
                            Premier Boat Dealer
                        </div>
                        <h2 id="services-heading" class="text-4xl md:text-5xl font-bold mb-6">Georgia's Leader in Boat Sales &amp; Marine Service</h2>
                        <p class="text-xl text-foreground max-w-3xl mx-auto">
                            Offering active boaters the best brands at the best prices, we're proud to be Lake Lanier's premier dealer for top boat manufacturers.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-12 mb-16">
                        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-hidden transition-all duration-300 hover:shadow-xl">
                            <div class="md:flex">
                                <div class="md:w-3/5">
                                    <div class="flex flex-col space-y-1.5 p-6 bg-muted">
                                        <h3 class="tracking-tight text-2xl font-bold flex items-center">
                                            <i data-lucide="anchor" class="w-6 h-6 mr-3"></i>
                                            Godfrey Pontoons
                                        </h3>
                                        <p class="text-lg text-foreground">Sweetwater, Aqua Patio, San Pan</p>
                                    </div>
                                    <div class="p-6">
                                        <p class="text-foreground mb-6">
                                            For over 60 years, Godfrey has been building quality boats with innovative layouts and top-performing materials. Powered by Yamaha Outboards, we bring performance and features to you at an affordable price.
                                        </p>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                                            Learn More About Godfrey
                                            <i data-lucide="chevron-right" class="ml-2 h-5 w-5"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="md:w-2/5 relative">
                                    <img 
                                        alt="Godfrey Pontoon Boat - Luxury pontoon by Impact Marine Group" 
                                        loading="lazy" 
                                        width="500" 
                                        height="300" 
                                        decoding="async" 
                                        data-nimg="1" 
                                        class="absolute inset-0 object-cover w-full h-full"
                                        style="color:transparent" 
                                        src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-hidden transition-all duration-300 hover:shadow-xl">
                            <div class="md:flex h-full">
                                <div class="md:w-2/5 relative">
                                    <img 
                                        alt="Tige Boat - Premium wakesurfing boat by Impact Marine Group" 
                                        loading="lazy" 
                                        width="500" 
                                        height="300" 
                                        decoding="async" 
                                        data-nimg="1" 
                                        class="absolute inset-0 object-cover w-full h-full"
                                        style="color:transparent" 
                                        src="<?php echo esc_url($hero_meta['backgroundImage']['url']); ?>"
                                    >
                                </div>
                                <div class="md:w-3/5">
                                    <div class="flex flex-col space-y-1.5 p-6 bg-muted">
                                        <h3 class="tracking-tight text-2xl font-bold flex items-center">
                                            <i data-lucide="waves" class="w-6 h-6 mr-3"></i>
                                            Tige Boats
                                        </h3>
                                        <p class="text-lg text-foreground">Premium Wakesurfing Experience</p>
                                    </div>
                                    <div class="p-6">
                                        <p class="text-foreground mb-6">
                                            We were blown away by the shape and quality of the wave behind a Tige RZ2. The style, quality, and performance of Tige impressed us so much that our Pro Shop business expanded to become a Tige dealer.
                                        </p>
                                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                                            Explore Tige Boats
                                            <i data-lucide="chevron-right" class="ml-2 h-5 w-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border shadow bg-primary text-primary-foreground overflow-hidden">
                        <div class="p-8 md:p-12">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                                <div class="text-center md:text-left md:w-2/3">
                                    <h3 class="text-3xl md:text-4xl font-bold mb-4">We Don't Just Sell Stuff - We Live It!</h3>
                                    <p class="text-xl md:text-2xl">
                                        Our passion for boating goes beyond sales. We're active boaters ourselves, ensuring we provide you with the best advice and service based on real-world experience.
                                    </p>
                                </div>
                                <div class="flex flex-wrap justify-center gap-6 md:w-1/3">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="users" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Expert Team</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="wrench" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Quality Service</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="bg-primary-foreground/20 rounded-full p-4 mb-2">
                                            <i data-lucide="award" class="w-8 h-8"></i>
                                        </div>
                                        <span class="text-sm font-medium">Top Brands</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'testimonials') : ?>
            <!-- Testimonials Section -->
            <section id="testimonials" aria-labelledby="testimonials-heading" class="py-24 overflow-hidden">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <h2 id="testimonials-heading" class="text-4xl font-bold text-center mb-16">What Our Customers Say</h2>
                    
                    <div class="relative">
                        <!-- Large Quote Background -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-5">
                            <i data-lucide="quote" class="w-96 h-96"></i>
                        </div>

                        <!-- Testimonials Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
                            <?php
                            $testimonials = get_post_meta(get_the_ID(), '_testimonials', true) ?: array(
                                array(
                                    'content' => 'The team at Impact Marine Group made buying my first yacht a breeze. Their expertise and customer service are unmatched!',
                                    'author' => 'John D.',
                                    'rating' => 5
                                ),
                                array(
                                    'content' => 'Outstanding service and attention to detail. They went above and beyond to ensure my boat was in perfect condition.',
                                    'author' => 'Sarah M.',
                                    'rating' => 5
                                ),
                                array(
                                    'content' => 'Professional, knowledgeable, and incredibly helpful. The best marine service I\'ve experienced.',
                                    'author' => 'Mike R.',
                                    'rating' => 5
                                )
                            );

                            foreach ($testimonials as $testimonial) :
                                $initial = substr($testimonial['author'], 0, 1);
                            ?>
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center mb-6 shadow-lg">
                                        <span class="text-3xl font-bold text-primary-foreground"><?php echo esc_html($initial); ?></span>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg italic mb-4 relative">
                                            <span class="absolute -top-4 -left-2 text-4xl text-muted">"</span>
                                            <?php echo esc_html($testimonial['content']); ?>
                                            <span class="absolute -bottom-4 -right-2 text-4xl text-muted">"</span>
                                        </p>
                                        <p class="font-semibold"><?php echo esc_html($testimonial['author']); ?></p>
                                        <div class="flex items-center justify-center mt-2">
                                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                                <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-16 text-center">
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-8">
                            Read More Reviews
                        </button>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'social') : ?>
            <!-- Social Section -->
            <section id="social" aria-labelledby="social-heading" class="py-16 bg-muted">
                <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                    <div class="flex items-center justify-center mb-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-anchor w-10 h-10 mr-3"><path d="M12 22V8"></path><path d="M5 12H2a10 10 0 0 0 20 0h-3"></path><circle cx="12" cy="5" r="3"></circle></svg>
                        <h2 id="social-heading" class="text-4xl font-bold">Sail Through Our Instagram</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>246
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>22
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span>
                                    <span class="text-foreground">Perfect day for a test drive on the water. Who wants to join? 🌊</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 16 comments</button>
                            </div>
                        </div>
                        
                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>221
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>30
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span> 
                                    <span class="text-foreground">Cruising into the weekend with our latest model! 🚤 #BoatLife</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 15 comments</button>
                            </div>
                        </div>

                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>196
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>28
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span>
                                    <span class="text-foreground">Just arrived: The all-new SpeedMaster 3000. Come see it in person! 😍</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 13 comments</button>
                            </div>
                        </div>

                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>196
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>28
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span>
                                    <span class="text-foreground">Just arrived: The all-new SpeedMaster 3000. Come see it in person! 😍</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 13 comments</button>
                            </div>
                        </div>

                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>242
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>14
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span>
                                    <span class="text-foreground">Sunset cruise on our luxury yacht. This could be you! 🌅 #YachtLife</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 8 comments</button>
                            </div>
                        </div>

                        <div class="rounded-xl bg-card text-card-foreground overflow-hidden border-2 hover:border-primary transition-colors duration-300 shadow-lg border-gray-200">
                            <div class="p-4 bg-muted">
                                <div class="flex items-center space-x-4">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">IM</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium">Impact Marine Group</p>
                                        <p class="text-xs text-foreground">@impactmarinegroup</p>
                                    </div>
                                </div>
                            </div>
                            <div class="relative group">
                                <img alt="Beautiful boat showcased by Impact Marine Group on Instagram" loading="lazy" width="400" height="400" decoding="async" data-nimg="1" class="transition-transform duration-300 group-hover:scale-105 object-cover" srcset="/_next/image?url=%2Fservice-department.webp&amp;w=640&amp;q=75 1x, /_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75 2x" src="/_next/image?url=%2Fservice-department.webp&amp;w=828&amp;q=75" style="color: transparent;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-4 w-4 mr-1 text-red-500"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>178
                                    </button>
                                    <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-secondary-foreground shadow-sm h-8 rounded-md px-3 text-xs bg-background/80 hover:bg-background">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle h-4 w-4 mr-1 text-primary"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path></svg>26
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm">
                                    <span class="font-medium">impactmarinegroup</span>
                                    <span class="text-foreground">New fishing boats in stock! Perfect for your next catch 🎣 #FishingLife</span>
                                </p>
                                <button class="inline-flex items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 text-primary underline-offset-4 hover:underline rounded-md text-xs mt-2 p-0 h-auto">View all 10 comments</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-12">
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-10 rounded-md px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram w-5 h-5 mr-2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
                            Follow Our Nautical Journey
                        </button>
                    </div>
                </div>
            </section>

        <?php elseif ($section_id === 'cta') : ?>
            <!-- CTA Section -->
            <section class="cta-section py-24 bg-blue-900 text-white">
                <div class="container mx-auto px-4">
                    <div class="cta-content max-w-4xl mx-auto text-center">
                        <?php if ($cta_title) : ?>
                            <h2 class="text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($cta_title); ?></h2>
                        <?php endif; ?>

                        <?php if ($cta_description) : ?>
                            <p class="text-xl text-blue-100 mb-8"><?php echo wp_kses_post($cta_description); ?></p>
                        <?php endif; ?>

                        <?php if ($cta_button_text && $cta_button_link) : ?>
                            <a href="<?php echo esc_url($cta_button_link); ?>" 
                               class="button inline-flex items-center bg-white hover:bg-blue-50 text-blue-900 px-8 py-4 rounded-lg font-medium transition-colors">
                                <?php echo esc_html($cta_button_text); ?>
                                <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</main>

<!-- Initialize Lucide icons -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php get_footer(); ?> 