<?php
/**
 * Template Name: Contact Template
 * 
 * @package wades
 */

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
    <div class="flex min-h-screen flex-col">
        <main class="flex-1">
            <section class="w-full py-12 md:py-24 lg:py-32">
                <div class="container px-4 md:px-6">
                    <!-- Header -->
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold tracking-tighter sm:text-5xl text-blue-900">
                                <?php echo esc_html(get_post_meta(get_the_ID(), '_contact_title', true) ?: 'Contact Impact Marine Group'); ?>
                            </h1>
                            <p class="text-gray-500 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                                <?php echo esc_html(get_post_meta(get_the_ID(), '_contact_subtitle', true) ?: 'Let us know how we can help you with your boating needs.'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Contact Cards -->
                    <div class="mx-auto grid max-w-5xl gap-6 py-12 lg:grid-cols-4">
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
                            <div class="rounded-xl border bg-card text-card-foreground shadow">
                                <div class="flex flex-col items-center space-y-2 p-6">
                                    <i data-lucide="<?php echo esc_attr($card['icon']); ?>" class="h-6 w-6 text-blue-600"></i>
                                    <h3 class="text-xl font-bold"><?php echo esc_html($card['title']); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo esc_html($card['description']); ?></p>
                                    
                                    <?php if (isset($card['contact'])) : ?>
                                        <a class="text-sm underline text-blue-600" href="mailto:<?php echo esc_attr($card['contact']); ?>">
                                            <?php echo esc_html($card['contact']); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (isset($card['contacts'])) : 
                                        foreach ($card['contacts'] as $contact) : ?>
                                            <a class="text-sm underline text-blue-600" href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $contact['number'])); ?>">
                                                <?php echo esc_html($contact['label']); ?>: <?php echo esc_html($contact['number']); ?>
                                            </a>
                                        <?php endforeach;
                                    endif; ?>

                                    <?php if (isset($card['link_text']) && isset($card['href'])) : ?>
                                        <a class="text-sm underline text-blue-600" href="<?php echo esc_url($card['href']); ?>">
                                            <?php echo esc_html($card['link_text']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Contact Form -->
                    <div class="mx-auto max-w-3xl space-y-8 mt-12">
                        <?php if (isset($message_status)) : ?>
                            <div class="p-4 mb-4 rounded-lg <?php echo $message_status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                <?php echo $message_status === 'success' ? 'Message sent successfully!' : 'Error sending message. Please try again.'; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" class="space-y-4">
                            <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium" for="name">Name</label>
                                    <input required type="text" id="name" name="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium" for="email">Email</label>
                                    <input required type="email" id="email" name="email" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" for="subject">Subject</label>
                                <input required type="text" id="subject" name="subject" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" for="message">Message</label>
                                <textarea required id="message" name="message" class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2"></textarea>
                            </div>
                            <button type="submit" name="contact_submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Send Message
                            </button>
                        </form>
                    </div>

                    <!-- FAQs -->
                    <div class="mx-auto max-w-3xl space-y-8 mt-16">
                        <h2 class="text-3xl font-bold tracking-tighter text-center text-blue-900">Frequently asked questions</h2>
                        <div class="divide-y">
                            <?php
                            $faqs = get_post_meta(get_the_ID(), '_faqs', true) ?: array(
                                array(
                                    'icon' => 'ship',
                                    'question' => 'What brands of boats do you offer?',
                                    'answer' => 'We offer a wide range of boat brands, including Sea Fox, Bennington, and more. Visit our showroom or check our inventory online for the latest models.'
                                ),
                                // ... add more default FAQs
                            );

                            foreach ($faqs as $index => $faq) : ?>
                                <div class="py-4">
                                    <button class="flex w-full items-center justify-between text-left" onclick="toggleFAQ(<?php echo $index; ?>)">
                                        <div class="flex items-center">
                                            <i data-lucide="<?php echo esc_attr($faq['icon']); ?>" class="mr-2 h-5 w-5 text-blue-600"></i>
                                            <span class="font-medium"><?php echo esc_html($faq['question']); ?></span>
                                        </div>
                                        <i data-lucide="chevron-down" class="h-4 w-4 transition-transform"></i>
                                    </button>
                                    <div id="faq-<?php echo $index; ?>" class="hidden mt-2 pl-7 text-gray-600">
                                        <?php echo esc_html($faq['answer']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</main>

<script>
function toggleFAQ(index) {
    const answer = document.getElementById(`faq-${index}`);
    const button = answer.previousElementSibling;
    const icon = button.querySelector('[data-lucide="chevron-down"]');
    
    answer.classList.toggle('hidden');
    if (!answer.classList.contains('hidden')) {
        icon.style.transform = 'rotate(180deg)';
    } else {
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

<?php get_footer(); ?> 