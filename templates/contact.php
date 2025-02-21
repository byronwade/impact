<?php
/**
 * Template Name: Contact Template
 * 
 * @package wades
 */

use function Wordpress\unstable_cache;

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

get_header(); ?>

<main class="flex-grow">
    <!-- Hero Section with Gradient -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="absolute inset-0 bg-grid-white/[0.2] bg-grid-16 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.5))]"></div>
        <div class="container relative px-4 py-20 mx-auto text-center text-white md:py-32">
            <h1 class="text-4xl font-bold tracking-tighter sm:text-6xl md:text-7xl">
                <?php echo esc_html(get_post_meta(get_the_ID(), '_contact_title', true) ?: 'Get in Touch'); ?>
            </h1>
            <p class="max-w-2xl mx-auto mt-6 text-lg text-blue-100 md:text-xl">
                <?php echo esc_html(get_post_meta(get_the_ID(), '_contact_subtitle', true) ?: 'We\'re here to help with all your boating needs'); ?>
            </p>
        </div>
    </section>

    <div class="container px-4 mx-auto">
        <!-- Contact Cards -->
        <div class="grid max-w-5xl gap-8 py-12 mx-auto -mt-16 lg:grid-cols-4">
            <?php
            $contact_cards = array(
                array(
                    'icon' => 'message-circle',
                    'title' => 'Chat to sales',
                    'description' => 'Speak to our friendly team.',
                    'contact' => 'sales@impactmarinegroup.com',
                    'type' => 'email'
                ),
                array(
                    'icon' => 'wrench',
                    'title' => 'Service support',
                    'description' => 'We\'re here to help.',
                    'contact' => 'service@impactmarinegroup.com',
                    'type' => 'email'
                ),
                array(
                    'icon' => 'map-pin',
                    'title' => 'Visit us',
                    'description' => '5185 Browns Bridge Rd',
                    'link_text' => 'View on Google Maps',
                    'href' => 'https://maps.google.com/?q=5185+Browns+Bridge+Rd'
                ),
                array(
                    'icon' => 'phone',
                    'title' => 'Call us',
                    'description' => 'Mon-Fri from 8am to 5pm.',
                    'contacts' => array(
                        array('label' => 'Sales', 'number' => '(770) 881-7808'),
                        array('label' => 'Service', 'number' => '(770) 881-7809')
                    ),
                    'type' => 'phone'
                )
            );

            foreach ($contact_cards as $card) : ?>
                <div class="relative p-6 bg-white rounded-2xl shadow-lg transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex flex-col items-center space-y-3">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i data-lucide="<?php echo esc_attr($card['icon']); ?>" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900"><?php echo esc_html($card['title']); ?></h3>
                        <p class="text-sm text-center text-gray-600"><?php echo esc_html($card['description']); ?></p>
                        
                        <?php if (isset($card['contact'])) : ?>
                            <a class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors" href="mailto:<?php echo esc_attr($card['contact']); ?>">
                                <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                                <?php echo esc_html($card['contact']); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (isset($card['contacts'])) : 
                            foreach ($card['contacts'] as $contact) : ?>
                                <a class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors" href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $contact['number'])); ?>">
                                    <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                                    <?php echo esc_html($contact['label']); ?>: <?php echo esc_html($contact['number']); ?>
                                </a>
                            <?php endforeach;
                        endif; ?>

                        <?php if (isset($card['link_text']) && isset($card['href'])) : ?>
                            <a class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors" href="<?php echo esc_url($card['href']); ?>" target="_blank" rel="noopener noreferrer">
                                <i data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                                <?php echo esc_html($card['link_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Contact Form Section -->
        <div class="max-w-3xl mx-auto mt-12 mb-24">
            <?php if (isset($message_status)) : ?>
                <div class="p-4 mb-6 rounded-lg animate-fade-in <?php echo $message_status === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
                    <div class="flex items-center">
                        <i data-lucide="<?php echo $message_status === 'success' ? 'check-circle' : 'alert-circle'; ?>" class="w-5 h-5 mr-2"></i>
                        <?php echo $message_status === 'success' ? 'Message sent successfully!' : 'Error sending message. Please try again.'; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-900" for="name">Name</label>
                        <input required type="text" id="name" name="name" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Your name">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-900" for="email">Email</label>
                        <input required type="email" id="email" name="email" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="your.email@example.com">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-900" for="subject">Subject</label>
                    <input required type="text" id="subject" name="subject" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                           placeholder="How can we help?">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-900" for="message">Message</label>
                    <textarea required id="message" name="message" rows="6" 
                              class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                              placeholder="Tell us about your needs..."></textarea>
                </div>
                <button type="submit" name="contact_submit" 
                        class="inline-flex items-center justify-center w-full px-6 py-4 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                    Send Message
                </button>
            </form>
        </div>

        <!-- FAQs Section -->
        <div class="max-w-3xl mx-auto mb-24">
            <h2 class="mb-12 text-3xl font-bold text-center text-gray-900">Frequently Asked Questions</h2>
            <div class="divide-y divide-gray-200">
                <?php
                $faqs = get_post_meta(get_the_ID(), '_faqs', true) ?: array(
                    array(
                        'icon' => 'ship',
                        'question' => 'What brands of boats do you offer?',
                        'answer' => 'We offer a wide range of boat brands, including Sea Fox, Bennington, and more. Visit our showroom or check our inventory online for the latest models.'
                    ),
                    array(
                        'icon' => 'clock',
                        'question' => 'What are your business hours?',
                        'answer' => 'We are open Monday through Friday from 8am to 5pm, and Saturday from 9am to 3pm. We are closed on Sundays.'
                    ),
                    array(
                        'icon' => 'wrench',
                        'question' => 'Do you offer boat maintenance services?',
                        'answer' => 'Yes, we provide comprehensive maintenance services including routine maintenance, repairs, and winterization.'
                    ),
                    array(
                        'icon' => 'credit-card',
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept all major credit cards, cash, and offer various financing options through our trusted partners.'
                    )
                );

                foreach ($faqs as $index => $faq) : ?>
                    <div class="py-6" x-data="{ open: false }">
                        <button class="flex items-center justify-between w-full text-left" 
                                @click="open = !open"
                                aria-expanded="false">
                            <div class="flex items-center">
                                <i data-lucide="<?php echo esc_attr($faq['icon']); ?>" class="w-5 h-5 mr-3 text-blue-600"></i>
                                <span class="text-lg font-medium text-gray-900"><?php echo esc_html($faq['question']); ?></span>
                            </div>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform" 
                               :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div class="mt-3 pl-8 pr-4 text-gray-600" 
                             x-show="open" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 -translate-y-1" 
                             x-transition:enter-end="opacity-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 translate-y-0" 
                             x-transition:leave-end="opacity-0 -translate-y-1" 
                             style="display: none;">
                            <?php echo esc_html($faq['answer']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
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