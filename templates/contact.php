<?php
/**
 * Template Name: Contact Template
 * 
 * @package wades
 */

// Get spacing and layout settings with defaults
$spacing_top = get_post_meta(get_the_ID(), '_content_spacing_top', true) ?: '96';
$spacing_bottom = get_post_meta(get_the_ID(), '_content_spacing_bottom', true) ?: '96';
$content_width = get_post_meta(get_the_ID(), '_content_max_width', true) ?: '7xl';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $nonce = $_POST['contact_nonce'];
    if (wp_verify_nonce($nonce, 'contact_form')) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $to = get_option('admin_email');
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email
        );
        
        $email_content = "Name: " . $name . "<br>";
        $email_content .= "Email: " . $email . "<br>";
        $email_content .= "Subject: " . $subject . "<br><br>";
        $email_content .= "Message:<br>" . nl2br($message);
        
        $sent = wp_mail($to, 'Contact Form: ' . $subject, $email_content, $headers);
        
        if ($sent) {
            $message_status = 'success';
        } else {
            $message_status = 'error';
        }
    }
}

get_header();

// Get the header template part
get_template_part('template-parts/template-header');
?>

<main class="flex-grow">
    <!-- Main Content -->
    <div class="relative z-10 px-4 sm:px-6 lg:px-8" style="padding-top: <?php echo esc_attr($spacing_top); ?>px; padding-bottom: <?php echo esc_attr($spacing_bottom); ?>px;">
        <div class="mx-auto max-w-<?php echo esc_attr($content_width); ?>">
            <!-- Contact Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
                <!-- Sales Card -->
                <div class="relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500">
                        <i data-lucide="shopping-bag" class="h-6 w-6 text-white"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold">Sales Inquiries</h3>
                    <p class="mt-2 text-gray-600">Interested in our boats? Our sales team is here to help.</p>
                    <dl class="mt-4 space-y-2">
                        <div class="flex items-start">
                            <dt class="sr-only">Phone</dt>
                            <i data-lucide="phone" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd><a href="tel:(770) 881-7808" class="text-blue-600 hover:text-blue-800">(770) 881-7808</a></dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="sr-only">Email</dt>
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd><a href="mailto:sales@impactmarinegroup.com" class="text-blue-600 hover:text-blue-800">sales@impactmarinegroup.com</a></dd>
                        </div>
                    </dl>
                </div>

                <!-- Service Card -->
                <div class="relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500">
                        <i data-lucide="wrench" class="h-6 w-6 text-white"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold">Service Department</h3>
                    <p class="mt-2 text-gray-600">Need maintenance or repairs? Contact our service team.</p>
                    <dl class="mt-4 space-y-2">
                        <div class="flex items-start">
                            <dt class="sr-only">Phone</dt>
                            <i data-lucide="phone" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd><a href="tel:(770) 881-7809" class="text-blue-600 hover:text-blue-800">(770) 881-7809</a></dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="sr-only">Email</dt>
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd><a href="mailto:service@impactmarinegroup.com" class="text-blue-600 hover:text-blue-800">service@impactmarinegroup.com</a></dd>
                        </div>
                    </dl>
                </div>

                <!-- Location Card -->
                <div class="relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500">
                        <i data-lucide="map-pin" class="h-6 w-6 text-white"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold">Visit Our Dealership</h3>
                    <p class="mt-2 text-gray-600">Come see us in person at our showroom.</p>
                    <dl class="mt-4 space-y-2">
                        <div class="flex items-start">
                            <dt class="sr-only">Address</dt>
                            <i data-lucide="map" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd class="text-gray-700">5185 Browns Bridge Rd<br>Cumming, GA</dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="sr-only">Hours</dt>
                            <i data-lucide="clock" class="h-5 w-5 text-gray-400 mr-2"></i>
                            <dd class="text-gray-700">Mon-Fri: 8am - 5pm</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="https://maps.google.com/?q=5185+Browns+Bridge+Rd" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center text-purple-600 hover:text-purple-800">
                            Get Directions
                            <i data-lucide="arrow-right" class="ml-1 h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form Section -->
            <div class="mx-auto w-full">
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <!-- Form -->
                        <div class="p-8 lg:p-12">
                            <h2 class="text-2xl font-bold text-gray-900">Send us a message</h2>
                            <p class="mt-2 text-gray-600">Fill out the form below and we'll get back to you shortly.</p>

                            <?php if (isset($message_status)) : ?>
                                <div class="mt-4 p-4 rounded-lg <?php echo $message_status === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'; ?>">
                                    <div class="flex items-center">
                                        <i data-lucide="<?php echo $message_status === 'success' ? 'check-circle' : 'alert-circle'; ?>" class="h-5 w-5 mr-2"></i>
                                        <p><?php echo $message_status === 'success' ? 'Message sent successfully!' : 'Error sending message. Please try again.'; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-8 space-y-6">
                                <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="name" name="name" required
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" id="email" name="email" required
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                        <input type="text" id="subject" name="subject" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                        <textarea id="message" name="message" rows="6" required
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                </div>
                                <button type="submit" name="contact_submit"
                                        class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Send Message
                                    <i data-lucide="send" class="ml-2 h-5 w-5"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Map -->
                        <div class="relative lg:h-full min-h-[400px]">
                            <div class="absolute inset-0">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.7333463722747!2d-84.2012492!3d34.2191283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f59fca4d40129d%3A0x3d4a4748f08786a4!2s5185%20Browns%20Bridge%20Rd%2C%20Cumming%2C%20GA%2030041!5e0!3m2!1sen!2sus!4v1629308000000!5m2!1sen!2sus"
                                    class="w-full h-full"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mx-auto w-full mt-24">
                <div class="text-center">
                    <h2 class="text-3xl font-bold">Frequently Asked Questions</h2>
                    <p class="mt-4 text-lg text-gray-600">Can't find the answer you're looking for? Reach out to our team.</p>
                </div>

                <div class="mt-12 space-y-8">
                    <?php
                    $faqs = array(
                        array(
                            'question' => 'What are your business hours?',
                            'answer' => 'We are open Monday through Friday from 8am to 5pm. We are closed on weekends and major holidays.'
                        ),
                        array(
                            'question' => 'Do you offer mobile service?',
                            'answer' => 'Yes, we offer mobile service for many repairs and maintenance tasks. Contact our service department for details.'
                        ),
                        array(
                            'question' => 'What forms of payment do you accept?',
                            'answer' => 'We accept all major credit cards, cash, and offer various financing options through our trusted partners.'
                        ),
                        array(
                            'question' => 'Do you offer boat storage?',
                            'answer' => 'Yes, we offer both indoor and outdoor storage options. Contact us for availability and rates.'
                        )
                    );

                    foreach ($faqs as $faq) : ?>
                        <div class="rounded-lg bg-white p-6 shadow" x-data="{ open: false }">
                            <button class="flex w-full items-center justify-between text-left" @click="open = !open">
                                <span class="text-lg font-medium"><?php echo esc_html($faq['question']); ?></span>
                                <i data-lucide="chevron-down" class="h-5 w-5 transform transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div class="mt-4" x-show="open" x-collapse>
                                <p class="text-gray-600"><?php echo esc_html($faq['answer']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-24"></div>
</main>

<!-- AlpineJS for FAQ interactions -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Initialize Lucide icons -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php get_footer(); ?> 