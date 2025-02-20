<?php
/**
 * Custom Meta Boxes for About Page Template
 */

use WP_Block_Editor_Context;

function wades_about_meta_boxes($post_type, $post) {
    // Only add meta boxes for pages
    if ($post_type !== 'page') {
        return;
    }

    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = '';
    if (isset($_GET['post'])) {
        $template = get_page_template_slug($_GET['post']);
    } elseif (isset($_POST['post_ID'])) {
        $template = get_page_template_slug($_POST['post_ID']);
    }

    // Add meta boxes based on template
    switch ($template) {
        case 'templates/about.php':
            add_meta_box(
                'wades_about_section',
                'About Section',
                'wades_about_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_brands_section',
                'Brands Section',
                'wades_brands_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_services_section',
                'Services Section',
                'wades_services_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_testimonials_section',
                'Testimonials Section',
                'wades_testimonials_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_contact_section',
                'Contact Section',
                'wades_contact_section_callback',
                'page',
                'normal',
                'high'
            );
            break;

        case 'templates/boats.php':
            add_meta_box(
                'wades_hero_section',
                'Hero Section',
                'wades_hero_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_boats_section',
                'Boats Section',
                'wades_boats_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_cta_section',
                'CTA Section',
                'wades_cta_section_callback',
                'page',
                'normal',
                'high'
            );
            break;

        case 'templates/services.php':
            add_meta_box(
                'wades_hero_section',
                'Hero Section',
                'wades_hero_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_services_section',
                'Services Section',
                'wades_services_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_cta_section',
                'CTA Section',
                'wades_cta_section_callback',
                'page',
                'normal',
                'high'
            );
            break;

        case 'templates/financing.php':
            add_meta_box(
                'wades_hero_section',
                'Hero Section',
                'wades_hero_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_financing_section',
                'Financing Section',
                'wades_financing_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_cta_section',
                'CTA Section',
                'wades_cta_section_callback',
                'page',
                'normal',
                'high'
            );
            break;

        case 'templates/contact.php':
            add_meta_box(
                'wades_hero_section',
                'Hero Section',
                'wades_hero_section_callback',
                'page',
                'normal',
                'high'
            );
            add_meta_box(
                'wades_contact_info_section',
                'Contact Information',
                'wades_contact_info_section_callback',
                'page',
                'normal',
                'high'
            );
            break;

        // Add other template cases as needed
    }
}
add_action('add_meta_boxes', 'wades_about_meta_boxes', 10, 2);

// About Section Callback
function wades_about_section_callback($post) {
    wp_nonce_field('wades_about_meta_box', 'wades_about_meta_box_nonce');

    // Get meta values with defaults
    $about_title = get_post_meta($post->ID, '_about_title', true) ?: 'About Impact Marine Group';
    $about_paragraphs = get_post_meta($post->ID, '_about_paragraphs', true);
    $about_image = get_post_meta($post->ID, '_about_image', true);
    $about_features = get_post_meta($post->ID, '_about_features', true);

    // Set default paragraphs if empty
    if (!is_array($about_paragraphs) || empty($about_paragraphs)) {
        $about_paragraphs = array(
            'Impact Marine Group is more than just a boat dealership - we\'re your gateway to unforgettable aquatic adventures. Founded by passionate boating enthusiasts, our mission is to provide unparalleled service and top-quality marine products to both seasoned sailors and newcomers to the boating world.',
            'Located at 5185 Browns Bridge Rd, our state-of-the-art facility is a testament to our commitment to excellence. We\'ve created a space where customers can explore, learn, and find the perfect vessel for their needs. Our showroom features a wide array of boats, from sleek speedboats to comfortable family cruisers, all hand-picked for their quality and performance.',
            'What sets Impact Marine Group apart is our team. Each member brings years of experience and a genuine love for boating to the table. We don\'t just sell boats - we use them, we live and breathe the boating lifestyle. This firsthand experience allows us to provide expert advice and insights that go beyond what you\'ll find in any product brochure.',
            'We believe that boating is more than a hobby - it\'s a way of life. That\'s why we\'re committed to fostering a community of boating enthusiasts. Through our events, workshops, and customer appreciation days, we bring together like-minded individuals who share our passion for the water.'
        );
    }

    // Set default features if empty
    if (!is_array($about_features) || empty($about_features)) {
        $about_features = array(
            array(
                'icon' => 'users',
                'title' => 'Expert Team',
                'description' => 'Knowledgeable staff with years of boating experience'
            ),
            array(
                'icon' => 'zap',
                'title' => 'Top Brands',
                'description' => 'Curated selection of premium marine products'
            )
        );
    }
    ?>
    <p>
        <label for="about_title">Section Title:</label><br>
        <input type="text" id="about_title" name="about_title" value="<?php echo esc_attr($about_title); ?>" class="widefat">
    </p>

    <div class="about-paragraphs">
        <h4>About Paragraphs</h4>
        <?php foreach ($about_paragraphs as $index => $paragraph) : ?>
            <p>
                <textarea name="about_paragraphs[]" rows="3" class="widefat"><?php echo esc_textarea($paragraph); ?></textarea>
                <button type="button" class="button remove-paragraph">Remove Paragraph</button>
            </p>
        <?php endforeach; ?>
        <button type="button" class="button add-paragraph">Add Paragraph</button>
    </div>

    <p>
        <label for="about_image">About Image:</label><br>
        <input type="hidden" id="about_image" name="about_image" value="<?php echo esc_attr($about_image); ?>">
        <button type="button" class="button upload-image">Upload Image</button>
        <div class="image-preview">
            <?php if ($about_image) : ?>
                <?php echo wp_get_attachment_image($about_image, 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>

    <div class="about-features">
        <h4>Features</h4>
        <?php foreach ($about_features as $index => $feature) : ?>
            <div class="feature-item">
                <p>
                    <label>Icon (Lucide icon name):</label><br>
                    <input type="text" name="about_features[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($feature['icon']); ?>" class="widefat">
                </p>
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="about_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="about_features[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($feature['description']); ?></textarea>
                </p>
                <button type="button" class="button remove-feature">Remove Feature</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-feature">Add Feature</button>
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Image Upload
            $('.upload-image').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var customUploader = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use this image' },
                    multiple: false
                }).on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    button.siblings('input[type="hidden"]').val(attachment.id);
                    button.siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
                }).open();
            });

            // Add Paragraph
            $('.add-paragraph').click(function() {
                var newParagraph = '<p><textarea name="about_paragraphs[]" rows="3" class="widefat"></textarea>' +
                                 '<button type="button" class="button remove-paragraph">Remove Paragraph</button></p>';
                $(this).before(newParagraph);
            });

            // Remove Paragraph
            $(document).on('click', '.remove-paragraph', function() {
                $(this).parent('p').remove();
            });

            // Add Feature
            $('.add-feature').click(function() {
                var index = $('.feature-item').length;
                var newFeature = '<div class="feature-item">' +
                    '<p><label>Icon (Lucide icon name):</label><br>' +
                    '<input type="text" name="about_features[' + index + '][icon]" class="widefat"></p>' +
                    '<p><label>Title:</label><br>' +
                    '<input type="text" name="about_features[' + index + '][title]" class="widefat"></p>' +
                    '<p><label>Description:</label><br>' +
                    '<textarea name="about_features[' + index + '][description]" rows="2" class="widefat"></textarea></p>' +
                    '<button type="button" class="button remove-feature">Remove Feature</button>' +
                    '</div>';
                $(this).before(newFeature);
            });

            // Remove Feature
            $(document).on('click', '.remove-feature', function() {
                $(this).parent('.feature-item').remove();
            });
        });
    </script>
    <?php
}

// Brands Section Callback
function wades_brands_section_callback($post) {
    $brands_title = get_post_meta($post->ID, '_brands_title', true) ?: 'Our Premium Brands';
    $brands_subtitle = get_post_meta($post->ID, '_brands_subtitle', true) ?: 'Discover the finest names in the marine industry';
    $featured_brands = get_post_meta($post->ID, '_featured_brands', true);

    if (!is_array($featured_brands) || empty($featured_brands)) {
        $featured_brands = array(
            array(
                'name' => 'Sea Fox Boat Company',
                'description' => 'Sea Fox Boat Company stands at the forefront of marine innovation, crafting vessels that seamlessly blend luxury, performance, and durability. With a clear mission to provide quality, hand-crafted saltwater boats, Sea Fox offers a range of models that cater to diverse boating needs and preferences.',
                'features' => array(
                    'Premium-grade materials for lasting durability',
                    'State-of-the-art navigation and fish-finding technology',
                    'Ergonomic designs for maximum comfort and functionality',
                    'Powered by reliable Yamaha Outboards for optimal performance',
                    'Industry-leading warranty for peace of mind'
                ),
                'image' => '',
                'models' => array(
                    'Sea Fox 288 Commander',
                    'Sea Fox 249 Avenger',
                    'Sea Fox 226 Traveler',
                    'Sea Fox 328 Commander'
                )
            )
        );
    }
    ?>
    <p>
        <label for="brands_title">Section Title:</label><br>
        <input type="text" id="brands_title" name="brands_title" value="<?php echo esc_attr($brands_title); ?>" class="widefat">
    </p>
    <p>
        <label for="brands_subtitle">Section Subtitle:</label><br>
        <input type="text" id="brands_subtitle" name="brands_subtitle" value="<?php echo esc_attr($brands_subtitle); ?>" class="widefat">
    </p>

    <div class="featured-brands">
        <h4>Featured Brands</h4>
        <?php foreach ($featured_brands as $index => $brand) : ?>
            <div class="brand-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Brand Name:</label><br>
                    <input type="text" name="featured_brands[<?php echo $index; ?>][name]" value="<?php echo esc_attr($brand['name']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="featured_brands[<?php echo $index; ?>][description]" rows="3" class="widefat"><?php echo esc_textarea($brand['description']); ?></textarea>
                </p>
                <div class="brand-features">
                    <label>Features:</label><br>
                    <?php foreach ($brand['features'] as $feature_index => $feature) : ?>
                        <p>
                            <input type="text" name="featured_brands[<?php echo $index; ?>][features][]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                        </p>
                    <?php endforeach; ?>
                </div>
                <p>
                    <label>Brand Image:</label><br>
                    <input type="hidden" name="featured_brands[<?php echo $index; ?>][image]" value="<?php echo esc_attr($brand['image']); ?>" class="brand-image-input">
                    <button type="button" class="button upload-brand-image">Upload Image</button>
                    <div class="image-preview">
                        <?php if ($brand['image']) : ?>
                            <?php echo wp_get_attachment_image($brand['image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
                <div class="brand-models">
                    <label>Popular Models:</label><br>
                    <?php foreach ($brand['models'] as $model_index => $model) : ?>
                        <p>
                            <input type="text" name="featured_brands[<?php echo $index; ?>][models][]" value="<?php echo esc_attr($model); ?>" class="widefat">
                        </p>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button remove-brand">Remove Brand</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-brand">Add Brand</button>
    </div>
    <?php
}

// Services Section Callback
function wades_services_section_callback($post) {
    $services_title = get_post_meta($post->ID, '_services_title', true) ?: 'Comprehensive Marine Services';
    $services_subtitle = get_post_meta($post->ID, '_services_subtitle', true) ?: 'Expert care for your vessel, from bow to stern';
    $services_description = get_post_meta($post->ID, '_services_description', true) ?: 'At Impact Marine Group, we pride ourselves on offering a full spectrum of marine services. Our state-of-the-art facility and team of certified technicians ensure that your boat receives the best care possible, keeping it in prime condition for all your aquatic adventures.';
    $service_cards = get_post_meta($post->ID, '_service_cards', true);

    $cta_text = get_post_meta($post->ID, '_cta_text', true);
    $cta_link = get_post_meta($post->ID, '_cta_link', true);

    if (!is_array($service_cards) || empty($service_cards)) {
        $service_cards = array(
            array(
                'icon' => 'ship',
                'title' => 'Boat Repair',
                'description' => 'From minor fixes to major overhauls, our expert technicians handle all aspects of boat repair with precision and care. We specialize in both structural and mechanical repairs, ensuring your vessel is restored to peak condition.'
            ),
            array(
                'icon' => 'wrench',
                'title' => 'Routine Maintenance',
                'description' => 'Regular maintenance is key to extending the life of your boat. Our comprehensive maintenance programs cover everything from engine tune-ups to hull cleaning, keeping your boat running smoothly and looking great.'
            ),
            array(
                'icon' => 'compass',
                'title' => 'Winterization',
                'description' => 'Protect your investment during the off-season with our thorough winterization services. We safeguard your boat\'s engine, plumbing, and other critical systems against cold weather damage, ensuring it\'s ready to launch when spring arrives.'
            ),
            array(
                'icon' => 'life-buoy',
                'title' => 'Safety Inspections',
                'description' => 'Safety is paramount on the water. Our certified inspectors conduct thorough safety checks, ensuring your boat meets all current regulations and is equipped with proper safety gear. We provide detailed reports and recommendations for any necessary upgrades.'
            ),
            array(
                'icon' => 'dollar-sign',
                'title' => 'Financing Options',
                'description' => 'Make your boating dreams a reality with our flexible financing solutions. We work with top lenders to offer competitive rates and terms tailored to your budget, making boat ownership accessible and affordable.'
            ),
            array(
                'icon' => 'users',
                'title' => 'Boating Education',
                'description' => 'Expand your boating knowledge with our comprehensive educational programs. From beginner courses to advanced seamanship, our classes cover navigation, safety, maintenance, and more, helping you become a more confident and capable boater.'
            ),
            array(
                'icon' => 'zap',
                'title' => 'Electronics Installation',
                'description' => 'Upgrade your boat with the latest marine electronics. Our technicians are skilled in installing and configuring a wide range of devices, from GPS and fish finders to complete entertainment systems, enhancing your boating experience.'
            ),
            array(
                'icon' => 'shield',
                'title' => 'Extended Warranty Plans',
                'description' => 'Enjoy peace of mind with our extended warranty options. We offer comprehensive coverage plans that go beyond standard warranties, protecting your investment and ensuring worry-free boating for years to come.'
            ),
            array(
                'icon' => 'award',
                'title' => 'Customization Services',
                'description' => 'Make your boat truly yours with our customization services. From custom upholstery and paint jobs to adding specialized equipment, we can help you create a boat that perfectly fits your style and needs.'
            )
        );
    }

    // Add this temporarily
    echo '<div style="background: #eee; padding: 10px; margin: 10px;">';
    echo '<h3>Current Meta Data:</h3>';
    echo '<pre>';
    print_r(get_post_meta($post->ID));
    echo '</pre>';
    echo '</div>';
    ?>
    <p>
        <label for="services_title">Section Title:</label><br>
        <input type="text" id="services_title" name="services_title" value="<?php echo esc_attr($services_title); ?>" class="widefat">
    </p>
    <p>
        <label for="services_subtitle">Section Subtitle:</label><br>
        <input type="text" id="services_subtitle" name="services_subtitle" value="<?php echo esc_attr($services_subtitle); ?>" class="widefat">
    </p>
    <p>
        <label for="services_description">Section Description:</label><br>
        <textarea id="services_description" name="services_description" rows="3" class="widefat"><?php echo esc_textarea($services_description); ?></textarea>
    </p>

    <div class="service-cards">
        <h4>Service Cards</h4>
        <?php foreach ($service_cards as $index => $card) : ?>
            <div class="service-card-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Icon (Lucide icon name):</label><br>
                    <input type="text" name="service_cards[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($card['icon']); ?>" class="widefat">
                </p>
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="service_cards[<?php echo $index; ?>][title]" value="<?php echo esc_attr($card['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="service_cards[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($card['description']); ?></textarea>
                </p>
                <button type="button" class="button remove-service-card">Remove Service</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-service-card">Add Service</button>
    </div>

    <p>
        <label for="cta_text">CTA Button Text:</label><br>
        <input type="text" id="cta_text" name="cta_text" value="<?php echo esc_attr($cta_text); ?>" class="widefat">
    </p>
    
    <p>
        <label for="cta_link">CTA Button Link:</label><br>
        <input type="text" id="cta_link" name="cta_link" value="<?php echo esc_attr($cta_link); ?>" class="widefat">
    </p>
    <?php
}

// Testimonials Section Callback
function wades_testimonials_section_callback($post) {
    $testimonials_title = get_post_meta($post->ID, '_testimonials_title', true) ?: 'Customer Testimonials';
    $testimonials_subtitle = get_post_meta($post->ID, '_testimonials_subtitle', true) ?: 'Hear from our satisfied boating enthusiasts';
    $testimonials = get_post_meta($post->ID, '_testimonials', true);

    if (!is_array($testimonials) || empty($testimonials)) {
        $testimonials = array(
            array(
                'content' => 'Impact Marine Group provided exceptional service when I purchased my Sea Fox. Their knowledge and attention to detail made the process smooth and enjoyable. The team went above and beyond to ensure I found the perfect boat for my family\'s needs. Even after the sale, their support has been outstanding.',
                'author' => 'John D.'
            ),
            array(
                'content' => 'The maintenance team at Impact Marine is top-notch. They keep my boat running perfectly, and their winterization service gives me peace of mind during the off-season. I\'ve been a customer for years, and the consistent quality of their work is why I keep coming back. They treat my boat as if it were their own.',
                'author' => 'Sarah M.'
            ),
            array(
                'content' => 'I took a boating class with Impact Marine, and it was incredibly informative. The instructors were knowledgeable and patient, perfect for a newcomer like me. They covered everything from basic navigation to advanced safety techniques. I feel much more confident on the water now, thanks to their excellent training program.',
                'author' => 'Mike R.'
            ),
            array(
                'content' => 'The financing options offered by Impact Marine helped me get the boat of my dreams. Their team worked hard to find a plan that fit my budget. They explained every detail of the process, making it stress-free. I appreciate their transparency and commitment to customer satisfaction. I wouldn\'t hesitate to recommend them to anyone looking to finance a boat.',
                'author' => 'Lisa K.'
            ),
            array(
                'content' => 'The customization services at Impact Marine are second to none. They helped me upgrade my boat with a new sound system and fishing equipment. The attention to detail in the installation was impressive, and the result exceeded my expectations. It\'s like having a brand new boat tailored exactly to my preferences.',
                'author' => 'David W.'
            ),
            array(
                'content' => 'As a first-time boat owner, I was nervous about maintenance, but Impact Marine\'s service team has been incredible. They\'re always willing to explain procedures and offer advice on keeping my boat in top shape. Their preventative maintenance program has saved me from potential issues and given me confidence in my boat\'s reliability.',
                'author' => 'Emily T.'
            )
        );
    }
    ?>
    <p>
        <label for="testimonials_title">Section Title:</label><br>
        <input type="text" id="testimonials_title" name="testimonials_title" value="<?php echo esc_attr($testimonials_title); ?>" class="widefat">
    </p>
    <p>
        <label for="testimonials_subtitle">Section Subtitle:</label><br>
        <input type="text" id="testimonials_subtitle" name="testimonials_subtitle" value="<?php echo esc_attr($testimonials_subtitle); ?>" class="widefat">
    </p>

    <div class="testimonials">
        <h4>Testimonials</h4>
        <?php foreach ($testimonials as $index => $testimonial) : ?>
            <div class="testimonial-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Content:</label><br>
                    <textarea name="testimonials[<?php echo $index; ?>][content]" rows="3" class="widefat"><?php echo esc_textarea($testimonial['content']); ?></textarea>
                </p>
                <p>
                    <label>Author:</label><br>
                    <input type="text" name="testimonials[<?php echo $index; ?>][author]" value="<?php echo esc_attr($testimonial['author']); ?>" class="widefat">
                </p>
                <button type="button" class="button remove-testimonial">Remove Testimonial</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-testimonial">Add Testimonial</button>
    </div>
    <?php
}

// Contact Section Callback
function wades_contact_section_callback($post) {
    $contact_title = get_post_meta($post->ID, '_contact_title', true) ?: 'Contact Us';
    $contact_subtitle = get_post_meta($post->ID, '_contact_subtitle', true) ?: 'We\'re here to assist you with all your boating needs';
    $address = get_post_meta($post->ID, '_address', true) ?: '5185 Browns Bridge Rd, Cumming, GA 30041';
    $business_hours = get_post_meta($post->ID, '_business_hours', true);
    $phone_numbers = get_post_meta($post->ID, '_phone_numbers', true);
    $email_addresses = get_post_meta($post->ID, '_email_addresses', true);
    $service_areas = get_post_meta($post->ID, '_service_areas', true);
    $map_image = get_post_meta($post->ID, '_map_image', true);

    if (!is_array($business_hours) || empty($business_hours)) {
        $business_hours = array(
            'Monday - Friday: 9AM-6PM',
            'Saturday: 10AM-4PM',
            'Sunday: Closed'
        );
    }

    if (!is_array($phone_numbers) || empty($phone_numbers)) {
        $phone_numbers = array(
            'Sales: (770) 881-7808',
            'Service: (770) 881-7809'
        );
    }

    if (!is_array($email_addresses) || empty($email_addresses)) {
        $email_addresses = array(
            'sales@impactmarinegroup.com',
            'service@impactmarinegroup.com'
        );
    }

    if (!is_array($service_areas) || empty($service_areas)) {
        $service_areas = array(
            'Lake Lanier',
            'Lake Allatoona',
            'Lake Burton',
            'Lake Sinclair',
            'Lake Hartwell',
            'All Georgia Lakes'
        );
    }
    ?>
    <p>
        <label for="contact_title">Section Title:</label><br>
        <input type="text" id="contact_title" name="contact_title" value="<?php echo esc_attr($contact_title); ?>" class="widefat">
    </p>
    <p>
        <label for="contact_subtitle">Section Subtitle:</label><br>
        <input type="text" id="contact_subtitle" name="contact_subtitle" value="<?php echo esc_attr($contact_subtitle); ?>" class="widefat">
    </p>
    <p>
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value="<?php echo esc_attr($address); ?>" class="widefat">
    </p>

    <div class="business-hours">
        <h4>Business Hours</h4>
        <?php foreach ($business_hours as $index => $hours) : ?>
            <p>
                <input type="text" name="business_hours[]" value="<?php echo esc_attr($hours); ?>" class="widefat">
                <button type="button" class="button remove-hours">Remove</button>
            </p>
        <?php endforeach; ?>
        <button type="button" class="button add-hours">Add Hours</button>
    </div>

    <div class="phone-numbers">
        <h4>Phone Numbers</h4>
        <?php foreach ($phone_numbers as $index => $phone) : ?>
            <p>
                <input type="text" name="phone_numbers[]" value="<?php echo esc_attr($phone); ?>" class="widefat">
                <button type="button" class="button remove-phone">Remove</button>
            </p>
        <?php endforeach; ?>
        <button type="button" class="button add-phone">Add Phone</button>
    </div>

    <div class="email-addresses">
        <h4>Email Addresses</h4>
        <?php foreach ($email_addresses as $index => $email) : ?>
            <p>
                <input type="email" name="email_addresses[]" value="<?php echo esc_attr($email); ?>" class="widefat">
                <button type="button" class="button remove-email">Remove</button>
            </p>
        <?php endforeach; ?>
        <button type="button" class="button add-email">Add Email</button>
    </div>

    <div class="service-areas">
        <h4>Service Areas</h4>
        <?php foreach ($service_areas as $index => $area) : ?>
            <p>
                <input type="text" name="service_areas[]" value="<?php echo esc_attr($area); ?>" class="widefat">
                <button type="button" class="button remove-area">Remove</button>
            </p>
        <?php endforeach; ?>
        <button type="button" class="button add-area">Add Area</button>
    </div>

    <p>
        <label for="map_image">Map Image:</label><br>
        <input type="hidden" id="map_image" name="map_image" value="<?php echo esc_attr($map_image); ?>">
        <button type="button" class="button upload-image">Upload Image</button>
        <div class="image-preview">
            <?php if ($map_image) : ?>
                <?php echo wp_get_attachment_image($map_image, 'thumbnail'); ?>
            <?php endif; ?>
        </div>
    </p>
    <?php
}

// Boats Section Callback
function wades_boats_section_callback($post) {
    wp_nonce_field('wades_boats_meta_box', 'wades_boats_meta_box_nonce');

    $featured_boats = get_post_meta($post->ID, '_featured_boats', true);
    
    if (!is_array($featured_boats) || empty($featured_boats)) {
        $featured_boats = array(
            array(
                'image' => '',
                'title' => 'Sample Boat',
                'description' => 'This is a sample boat description.',
                'price' => '$99,999',
                'link' => '#'
            )
        );
    }
    ?>
    <div class="featured-boats">
        <h4>Featured Boats</h4>
        <?php foreach ($featured_boats as $index => $boat) : ?>
            <div class="boat-item" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Boat Image:</label><br>
                    <input type="hidden" name="featured_boats[<?php echo $index; ?>][image]" value="<?php echo esc_attr($boat['image']); ?>" class="boat-image-input">
                    <button type="button" class="button upload-boat-image">Upload Image</button>
                    <div class="image-preview">
                        <?php if ($boat['image']) : ?>
                            <?php echo wp_get_attachment_image($boat['image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="featured_boats[<?php echo $index; ?>][title]" value="<?php echo esc_attr($boat['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="featured_boats[<?php echo $index; ?>][description]" rows="3" class="widefat"><?php echo esc_textarea($boat['description']); ?></textarea>
                </p>
                <p>
                    <label>Price:</label><br>
                    <input type="text" name="featured_boats[<?php echo $index; ?>][price]" value="<?php echo esc_attr($boat['price']); ?>" class="widefat">
                </p>
                <p>
                    <label>Link:</label><br>
                    <input type="text" name="featured_boats[<?php echo $index; ?>][link]" value="<?php echo esc_url($boat['link']); ?>" class="widefat">
                </p>
                <button type="button" class="button remove-boat">Remove Boat</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-boat">Add Boat</button>
    </div>
    <?php
}

// Financing Section Callback
function wades_financing_section_callback($post) {
    wp_nonce_field('wades_financing_meta_box', 'wades_financing_meta_box_nonce');

    $financing_title = get_post_meta($post->ID, '_financing_title', true) ?: 'Financing Options';
    $financing_description = get_post_meta($post->ID, '_financing_description', true);
    $financing_options = get_post_meta($post->ID, '_financing_options', true);

    if (!is_array($financing_options) || empty($financing_options)) {
        $financing_options = array(
            array(
                'title' => 'Traditional Boat Loans',
                'description' => 'Fixed-rate financing with competitive terms',
                'features' => array(
                    'Competitive interest rates',
                    'Flexible terms up to 20 years',
                    'Fixed monthly payments',
                    'Quick approval process'
                )
            ),
            array(
                'title' => 'Home Equity Options',
                'description' => 'Use your home equity for boat financing',
                'features' => array(
                    'Lower interest rates',
                    'Potential tax benefits',
                    'Flexible payment options',
                    'Higher approval amounts'
                )
            )
        );
    }
    ?>
    <p>
        <label for="financing_title">Section Title:</label><br>
        <input type="text" id="financing_title" name="financing_title" value="<?php echo esc_attr($financing_title); ?>" class="widefat">
    </p>
    <p>
        <label for="financing_description">Section Description:</label><br>
        <textarea id="financing_description" name="financing_description" rows="3" class="widefat"><?php echo esc_textarea($financing_description); ?></textarea>
    </p>

    <div class="financing-options">
        <h4>Financing Options</h4>
        <?php foreach ($financing_options as $index => $option) : ?>
            <div class="financing-option" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
                <p>
                    <label>Title:</label><br>
                    <input type="text" name="financing_options[<?php echo $index; ?>][title]" value="<?php echo esc_attr($option['title']); ?>" class="widefat">
                </p>
                <p>
                    <label>Description:</label><br>
                    <textarea name="financing_options[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($option['description']); ?></textarea>
                </p>
                <div class="features">
                    <label>Features:</label><br>
                    <?php foreach ($option['features'] as $feature_index => $feature) : ?>
                        <p>
                            <input type="text" name="financing_options[<?php echo $index; ?>][features][]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                            <button type="button" class="button remove-feature">Remove Feature</button>
                        </p>
                    <?php endforeach; ?>
                    <button type="button" class="button add-feature">Add Feature</button>
                </div>
                <button type="button" class="button remove-option">Remove Option</button>
            </div>
        <?php endforeach; ?>
        <button type="button" class="button add-option">Add Option</button>
    </div>
    <?php
}

// Contact Info Section Callback
function wades_contact_info_section_callback($post) {
    wp_nonce_field('wades_contact_info_meta_box', 'wades_contact_info_meta_box_nonce');

    $contact_info = array(
        'address' => get_post_meta($post->ID, '_contact_address', true),
        'phone' => get_post_meta($post->ID, '_contact_phone', true),
        'email' => get_post_meta($post->ID, '_contact_email', true),
        'hours' => get_post_meta($post->ID, '_contact_hours', true),
        'map_embed' => get_post_meta($post->ID, '_contact_map_embed', true),
        'form_shortcode' => get_post_meta($post->ID, '_contact_form_shortcode', true)
    );
    ?>
    <p>
        <label for="contact_address">Address:</label><br>
        <input type="text" id="contact_address" name="contact_address" value="<?php echo esc_attr($contact_info['address']); ?>" class="widefat">
    </p>
    <p>
        <label for="contact_phone">Phone:</label><br>
        <input type="text" id="contact_phone" name="contact_phone" value="<?php echo esc_attr($contact_info['phone']); ?>" class="widefat">
    </p>
    <p>
        <label for="contact_email">Email:</label><br>
        <input type="email" id="contact_email" name="contact_email" value="<?php echo esc_attr($contact_info['email']); ?>" class="widefat">
    </p>
    <p>
        <label for="contact_hours">Business Hours:</label><br>
        <textarea id="contact_hours" name="contact_hours" rows="4" class="widefat"><?php echo esc_textarea($contact_info['hours']); ?></textarea>
    </p>
    <p>
        <label for="contact_map_embed">Google Maps Embed Code:</label><br>
        <textarea id="contact_map_embed" name="contact_map_embed" rows="4" class="widefat"><?php echo esc_textarea($contact_info['map_embed']); ?></textarea>
    </p>
    <p>
        <label for="contact_form_shortcode">Contact Form Shortcode:</label><br>
        <input type="text" id="contact_form_shortcode" name="contact_form_shortcode" value="<?php echo esc_attr($contact_info['form_shortcode']); ?>" class="widefat">
    </p>
    <?php
}

// Save meta box data
function wades_save_about_meta($post_id) {
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if our nonce is set and verify it.
    if (!isset($_POST['wades_about_meta_box_nonce']) || !wp_verify_nonce($_POST['wades_about_meta_box_nonce'], 'wades_about_meta_box')) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Make sure we're working with a page
    if (get_post_type($post_id) !== 'page') {
        return;
    }

    // Check if this is the correct template
    $template = get_page_template_slug($post_id);
    if ($template && $template !== 'templates/about.php') {
        return;
    }

    // Call shared meta save function
    wades_save_shared_meta($post_id);

    // Now it's safe to save the data
    if (isset($_POST['about_title'])) {
        update_post_meta($post_id, '_about_title', sanitize_text_field($_POST['about_title']));
    }

    if (isset($_POST['about_paragraphs'])) {
        $paragraphs = array_map('wp_kses_post', $_POST['about_paragraphs']);
        update_post_meta($post_id, '_about_paragraphs', $paragraphs);
    }

    if (isset($_POST['about_image'])) {
        update_post_meta($post_id, '_about_image', absint($_POST['about_image']));
    }

    if (isset($_POST['about_features'])) {
        $features = array();
        foreach ($_POST['about_features'] as $feature) {
            $features[] = array(
                'icon' => sanitize_text_field($feature['icon']),
                'title' => sanitize_text_field($feature['title']),
                'description' => wp_kses_post($feature['description'])
            );
        }
        update_post_meta($post_id, '_about_features', $features);
    }

    // Save Brands Section
    if (isset($_POST['brands_title'])) {
        update_post_meta($post_id, '_brands_title', sanitize_text_field($_POST['brands_title']));
    }
    if (isset($_POST['brands_subtitle'])) {
        update_post_meta($post_id, '_brands_subtitle', sanitize_text_field($_POST['brands_subtitle']));
    }
    if (isset($_POST['featured_brands'])) {
        $brands = array();
        foreach ($_POST['featured_brands'] as $brand) {
            $brands[] = array(
                'name' => sanitize_text_field($brand['name']),
                'description' => wp_kses_post($brand['description']),
                'features' => array_map('sanitize_text_field', $brand['features']),
                'image' => absint($brand['image']),
                'models' => array_map('sanitize_text_field', $brand['models'])
            );
        }
        update_post_meta($post_id, '_featured_brands', $brands);
    }

    // Save Services Section
    if (isset($_POST['services_title'])) {
        update_post_meta($post_id, '_services_title', sanitize_text_field($_POST['services_title']));
    }
    if (isset($_POST['services_subtitle'])) {
        update_post_meta($post_id, '_services_subtitle', sanitize_text_field($_POST['services_subtitle']));
    }
    if (isset($_POST['services_description'])) {
        update_post_meta($post_id, '_services_description', wp_kses_post($_POST['services_description']));
    }
    if (isset($_POST['service_cards'])) {
        $cards = array();
        foreach ($_POST['service_cards'] as $card) {
            $cards[] = array(
                'icon' => sanitize_text_field($card['icon']),
                'title' => sanitize_text_field($card['title']),
                'description' => wp_kses_post($card['description'])
            );
        }
        update_post_meta($post_id, '_service_cards', $cards);
    }

    // Save Testimonials Section
    if (isset($_POST['testimonials_title'])) {
        update_post_meta($post_id, '_testimonials_title', sanitize_text_field($_POST['testimonials_title']));
    }
    if (isset($_POST['testimonials_subtitle'])) {
        update_post_meta($post_id, '_testimonials_subtitle', sanitize_text_field($_POST['testimonials_subtitle']));
    }
    if (isset($_POST['testimonials'])) {
        $testimonials = array();
        foreach ($_POST['testimonials'] as $testimonial) {
            $testimonials[] = array(
                'content' => wp_kses_post($testimonial['content']),
                'author' => sanitize_text_field($testimonial['author'])
            );
        }
        update_post_meta($post_id, '_testimonials', $testimonials);
    }

    // Save Contact Section
    if (isset($_POST['contact_title'])) {
        update_post_meta($post_id, '_contact_title', sanitize_text_field($_POST['contact_title']));
    }
    if (isset($_POST['contact_subtitle'])) {
        update_post_meta($post_id, '_contact_subtitle', sanitize_text_field($_POST['contact_subtitle']));
    }
    if (isset($_POST['address'])) {
        update_post_meta($post_id, '_address', sanitize_text_field($_POST['address']));
    }
    if (isset($_POST['business_hours'])) {
        update_post_meta($post_id, '_business_hours', array_map('sanitize_text_field', $_POST['business_hours']));
    }
    if (isset($_POST['phone_numbers'])) {
        update_post_meta($post_id, '_phone_numbers', array_map('sanitize_text_field', $_POST['phone_numbers']));
    }
    if (isset($_POST['email_addresses'])) {
        update_post_meta($post_id, '_email_addresses', array_map('sanitize_email', $_POST['email_addresses']));
    }
    if (isset($_POST['service_areas'])) {
        update_post_meta($post_id, '_service_areas', array_map('sanitize_text_field', $_POST['service_areas']));
    }
    if (isset($_POST['map_image'])) {
        update_post_meta($post_id, '_map_image', absint($_POST['map_image']));
    }

    // Add to save_post function
    if (isset($_POST['featured_boats'])) {
        $boats = array();
        foreach ($_POST['featured_boats'] as $boat) {
            $boats[] = array(
                'image' => absint($boat['image']),
                'title' => sanitize_text_field($boat['title']),
                'description' => wp_kses_post($boat['description']),
                'price' => sanitize_text_field($boat['price']),
                'link' => esc_url_raw($boat['link'])
            );
        }
        update_post_meta($post_id, '_featured_boats', $boats);
    }

    // Save Financing Section
    if (isset($_POST['financing_title'])) {
        update_post_meta($post_id, '_financing_title', sanitize_text_field($_POST['financing_title']));
    }
    if (isset($_POST['financing_description'])) {
        update_post_meta($post_id, '_financing_description', wp_kses_post($_POST['financing_description']));
    }
    if (isset($_POST['financing_options'])) {
        $options = array();
        foreach ($_POST['financing_options'] as $option) {
            $options[] = array(
                'title' => sanitize_text_field($option['title']),
                'description' => wp_kses_post($option['description']),
                'features' => array_map('sanitize_text_field', $option['features'])
            );
        }
        update_post_meta($post_id, '_financing_options', $options);
    }

    // Save Contact Info Section
    if (isset($_POST['contact_address'])) {
        update_post_meta($post_id, '_contact_address', sanitize_text_field($_POST['contact_address']));
    }
    if (isset($_POST['contact_phone'])) {
        update_post_meta($post_id, '_contact_phone', sanitize_text_field($_POST['contact_phone']));
    }
    if (isset($_POST['contact_email'])) {
        update_post_meta($post_id, '_contact_email', sanitize_email($_POST['contact_email']));
    }
    if (isset($_POST['contact_hours'])) {
        update_post_meta($post_id, '_contact_hours', wp_kses_post($_POST['contact_hours']));
    }
    if (isset($_POST['contact_map_embed'])) {
        update_post_meta($post_id, '_contact_map_embed', wp_kses_post($_POST['contact_map_embed']));
    }
    if (isset($_POST['contact_form_shortcode'])) {
        update_post_meta($post_id, '_contact_form_shortcode', sanitize_text_field($_POST['contact_form_shortcode']));
    }
}
add_action('save_post', 'wades_save_about_meta');

// Add JavaScript for dynamic fields
add_action('admin_footer', function() {
    global $post;
    if (!$post || get_page_template_slug($post->ID) !== 'templates/about.php') return;
    ?>
    <script>
        jQuery(document).ready(function($) {
            // ... existing image upload code ...

            // Add dynamic field handlers for new sections
            function addDynamicFieldHandler(addButtonClass, removeButtonClass, template) {
                $(document).on('click', addButtonClass, function() {
                    $(this).before(template);
                });

                $(document).on('click', removeButtonClass, function() {
                    $(this).parent('p, div').remove();
                });
            }

            // Brand image upload
            $(document).on('click', '.upload-brand-image', function(e) {
                e.preventDefault();
                var button = $(this);
                var customUploader = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use this image' },
                    multiple: false
                }).on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    button.siblings('.brand-image-input').val(attachment.id);
                    button.siblings('.image-preview').html('<img src="' + attachment.url + '" style="max-width:150px;">');
                }).open();
            });

            // Add handlers for all dynamic fields
            addDynamicFieldHandler('.add-hours', '.remove-hours', 
                '<p><input type="text" name="business_hours[]" class="widefat"><button type="button" class="button remove-hours">Remove</button></p>');
            
            addDynamicFieldHandler('.add-phone', '.remove-phone',
                '<p><input type="text" name="phone_numbers[]" class="widefat"><button type="button" class="button remove-phone">Remove</button></p>');
            
            addDynamicFieldHandler('.add-email', '.remove-email',
                '<p><input type="email" name="email_addresses[]" class="widefat"><button type="button" class="button remove-email">Remove</button></p>');
            
            addDynamicFieldHandler('.add-area', '.remove-area',
                '<p><input type="text" name="service_areas[]" class="widefat"><button type="button" class="button remove-area">Remove</button></p>');
        });
    </script>
    <?php
});

// Update the meta box registration
function wades_register_template_meta_boxes() {
    register_meta('post', '_hero_title', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
    
    register_meta('post', '_hero_description', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ));
    
    // Register other meta fields...
}
add_action('init', 'wades_register_template_meta_boxes'); 