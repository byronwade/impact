<?php
/**
 * Template Name: Financing Template
 * 
 * @package wades
 */

get_header();
get_template_part('template-parts/template-header');
?>

<main role="main" aria-label="Main content" class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
        <!-- Financing Options Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
            <?php
            $financing_options = get_post_meta(get_the_ID(), '_financing_options', true) ?: array(
                array(
                    'icon' => 'anchor',
                    'title' => 'Boat Financing',
                    'description' => 'Find the perfect loan for your new or used boat',
                    'features' => array(
                        'Competitive interest rates',
                        'Flexible terms up to 20 years',
                        'Financing available for boats up to $5 million',
                        'Quick and easy application process'
                    )
                ),
                array(
                    'icon' => 'wrench',
                    'title' => 'Service Financing',
                    'description' => 'Affordable options for repairs and maintenance',
                    'features' => array(
                        '0% interest for 12 months on services over $2,000',
                        'Low monthly payments',
                        'Cover unexpected repairs or planned upgrades',
                        'Quick approval process'
                    )
                )
            );

            foreach ($financing_options as $option) : ?>
                <div class="rounded-xl border bg-card text-card-foreground shadow-sm hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <i data-lucide="<?php echo esc_attr($option['icon']); ?>" class="h-5 w-5 text-primary"></i>
                            <h3 class="text-xl font-semibold"><?php echo esc_html($option['title']); ?></h3>
                        </div>
                        <p class="text-muted-foreground mb-4"><?php echo esc_html($option['description']); ?></p>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <?php foreach ($option['features'] as $feature) : ?>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="h-4 w-4 text-primary"></i>
                                    <?php echo esc_html($feature); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Financing Partners -->
        <?php
        $financing_partners = get_post_meta(get_the_ID(), '_financing_partners', true);
        if (!empty($financing_partners)) : ?>
            <div class="mb-12">
                <h2 class="text-2xl font-semibold mb-6">Our Financing Partners</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <?php foreach ($financing_partners as $partner) : ?>
                        <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6 flex flex-col items-center text-center">
                            <?php if (!empty($partner['logo'])) : ?>
                                <div class="mb-4 h-20 flex items-center justify-center">
                                    <?php 
                                    $logo_url = wp_get_attachment_image_url($partner['logo'], 'medium');
                                    if ($logo_url) :
                                        if (!empty($partner['link'])) : ?>
                                            <a href="<?php echo esc_url($partner['link']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php endif; ?>
                                        
                                        <?php echo wades_get_image_html($partner['logo'], 'medium', array(
                                            'class' => 'h-full w-auto object-contain',
                                            'alt' => esc_attr($partner['name']) . ' logo'
                                        )); ?>
                                             
                                        <?php if (!empty($partner['link'])) : ?>
                                            </a>
                                        <?php endif;
                                    else :
                                        echo wades_get_image_html(0, 'medium', array(
                                            'class' => 'h-full w-auto object-contain',
                                            'alt' => 'Partner logo placeholder'
                                        ));
                                    endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="text-lg font-semibold mb-2"><?php echo esc_html($partner['name']); ?></h3>
                            <?php if (!empty($partner['description'])) : ?>
                                <p class="text-muted-foreground text-sm mb-4"><?php echo esc_html($partner['description']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($partner['link'])) : ?>
                                <a href="<?php echo esc_url($partner['link']); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center text-sm text-primary hover:text-primary/80">
                                    Visit Website
                                    <i data-lucide="external-link" class="ml-1 h-4 w-4"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Loan Calculator -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Loan Calculator</h2>
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium" for="loanAmount">Loan Amount ($)</label>
                                <input type="number" id="loanAmount" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" value="50000">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" for="interestRate">Interest Rate (%)</label>
                                <input type="number" id="interestRate" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" value="5">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium" for="loanTerm">Loan Term (months)</label>
                                <input type="number" id="loanTerm" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" value="60">
                            </div>
                        </div>
                        <div class="flex flex-col justify-between">
                            <div class="bg-muted rounded-lg p-6 text-center">
                                <p class="text-lg mb-2">Estimated Monthly Payment</p>
                                <p class="text-4xl font-bold text-primary" id="monthlyPayment">$943.56</p>
                            </div>
                            <a href="tel:+17708817808" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                <i data-lucide="phone-call" class="mr-2 h-4 w-4"></i>
                                Discuss Your Options: (770) 881-7808
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQs -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Frequently Asked Questions</h2>
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm divide-y">
                <?php
                $faqs = get_post_meta(get_the_ID(), '_faqs', true) ?: array(
                    array(
                        'question' => 'What credit score do I need to qualify?',
                        'answer' => 'While we consider various factors, a credit score of 640 or higher typically results in the best rates and terms. However, we offer options for a wide range of credit profiles.'
                    ),
                    array(
                        'question' => 'How long does the approval process take?',
                        'answer' => 'Our streamlined process often provides a decision within 24-48 hours of receiving a completed application.'
                    ),
                    array(
                        'question' => 'Can I finance both new and used boats?',
                        'answer' => 'Yes, we offer financing options for both new and used boats, as well as refinancing for your current boat.'
                    ),
                    array(
                        'question' => 'Is there a minimum or maximum loan amount?',
                        'answer' => 'We offer financing from $5,000 up to $5 million, accommodating a wide range of boats and budgets.'
                    )
                );
                foreach ($faqs as $index => $faq) : ?>
                    <div class="faq-item">
                        <button type="button" class="w-full flex items-center justify-between p-4 text-left text-sm font-medium hover:bg-muted/50 transition-colors" onclick="toggleFAQ(<?php echo $index; ?>)">
                            <?php echo esc_html($faq['question']); ?>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground transition-transform"></i>
                        </button>
                        <div id="faq-<?php echo $index; ?>" class="hidden p-4 text-sm text-muted-foreground bg-muted/30">
                            <?php echo esc_html($faq['answer']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-muted rounded-xl p-8 text-center">
            <h2 class="text-2xl font-semibold mb-4">Ready to Get Started?</h2>
            <p class="text-muted-foreground mb-6 max-w-2xl mx-auto">Our financing experts are here to help you navigate your options and find the best solution for your needs.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="tel:+17708817808" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-8">
                    <i data-lucide="phone-call" class="mr-2 h-5 w-5"></i>
                    Call Us: (770) 881-7808
                </a>
                <a href="/contact" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-8">
                    Contact Form
                </a>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateMonthlyPayment() {
        const loanAmount = parseFloat(document.getElementById('loanAmount').value) || 0;
        const interestRate = (parseFloat(document.getElementById('interestRate').value) || 0) / 100 / 12;
        const loanTerm = parseInt(document.getElementById('loanTerm').value) || 0;
        
        let monthlyPayment = 0;
        if (loanAmount > 0 && interestRate > 0 && loanTerm > 0) {
            monthlyPayment = (loanAmount * interestRate * Math.pow(1 + interestRate, loanTerm)) / (Math.pow(1 + interestRate, loanTerm) - 1);
        }
        
        document.getElementById('monthlyPayment').textContent = '$' + monthlyPayment.toFixed(2);
    }

    function toggleFAQ(index) {
        const button = document.querySelector(`button[onclick="toggleFAQ(${index})"]`);
        const icon = button.querySelector('[data-lucide="chevron-down"]');
        const answer = document.getElementById(`faq-${index}`);
        const allAnswers = document.querySelectorAll('[id^="faq-"]');
        const allIcons = document.querySelectorAll('[data-lucide="chevron-down"]');
        
        allAnswers.forEach(el => {
            if (el.id !== `faq-${index}`) {
                el.classList.add('hidden');
            }
        });
        
        allIcons.forEach(icon => {
            icon.style.transform = 'rotate(0deg)';
        });

        answer.classList.toggle('hidden');
        if (!answer.classList.contains('hidden')) {
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // Add event listeners for calculator inputs
    const loanAmount = document.getElementById('loanAmount');
    const interestRate = document.getElementById('interestRate');
    const loanTerm = document.getElementById('loanTerm');

    if (loanAmount && interestRate && loanTerm) {
        loanAmount.addEventListener('input', calculateMonthlyPayment);
        interestRate.addEventListener('input', calculateMonthlyPayment);
        loanTerm.addEventListener('input', calculateMonthlyPayment);

        // Initialize calculator with default values
        calculateMonthlyPayment();
    }

    // Make toggleFAQ available globally
    window.toggleFAQ = toggleFAQ;
});
</script>

<?php get_footer(); ?> 