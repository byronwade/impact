<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wades
 */

?>

<footer class="bg-gray-900 text-gray-300">
    <!-- Main Footer -->
    <div class="container mx-auto max-w-7xl px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Company Info -->
            <div class="space-y-6">
                <?php if (has_custom_logo()) : ?>
                    <div class="mb-6">
                        <?php
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        ?>
                        <img src="<?php echo esc_url($logo[0]); ?>"
                             alt="<?php bloginfo('name'); ?>"
                             class="w-auto h-16 max-w-[240px] object-contain bg-white p-3 rounded-lg"
                        >
                    </div>
                <?php else : ?>
                    <h3 class="text-2xl font-bold text-white mb-6"><?php bloginfo('name'); ?></h3>
                <?php endif; ?>
                
                <p class="text-gray-400">Your trusted partner in marine excellence, providing exceptional boat sales and services since 2005.</p>
                
                <!-- Social Links -->
                <div class="flex space-x-4">
                    <?php
                    $social_links = array(
                        'facebook' => get_option('wades_social_facebook'),
                        'instagram' => get_option('wades_social_instagram'),
                        'youtube' => get_option('wades_social_youtube')
                    );

                    foreach ($social_links as $platform => $url) :
                        if ($url) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white p-2 rounded-full transition-colors">
                            <span class="sr-only"><?php echo ucfirst($platform); ?></span>
                            <i data-lucide="<?php echo $platform; ?>" class="w-5 h-5"></i>
                        </a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="/boats" class="hover:text-white transition-colors flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i>
                            Our Inventory
                        </a>
                    </li>
                    <li>
                        <a href="/services" class="hover:text-white transition-colors flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i>
                            Marine Services
                        </a>
                    </li>
                    <li>
                        <a href="/financing" class="hover:text-white transition-colors flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i>
                            Financing
                        </a>
                    </li>
                    <li>
                        <a href="/about" class="hover:text-white transition-colors flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i>
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="hover:text-white transition-colors flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mr-2"></i>
                            Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Contact Info</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <i data-lucide="map-pin" class="w-5 h-5 mr-3 mt-1 text-blue-500"></i>
                        <span>5185 Browns Bridge Rd<br>Cumming, GA</span>
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="phone" class="w-5 h-5 mr-3 text-blue-500"></i>
                        <a href="tel:770-881-7808" class="hover:text-white transition-colors">(770) 881-7808</a>
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="mail" class="w-5 h-5 mr-3 text-blue-500"></i>
                        <a href="mailto:info@impactmarinegroup.com" class="hover:text-white transition-colors">info@impactmarinegroup.com</a>
                    </li>
                    <li class="flex items-center">
                        <i data-lucide="clock" class="w-5 h-5 mr-3 text-blue-500"></i>
                        <span>Mon-Fri: 8am - 5pm</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-6">Newsletter</h3>
                <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest updates, special offers, and boating tips.</p>
                <form action="#" method="POST" class="space-y-3">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter your email">
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                        Subscribe
                        <i data-lucide="send" class="w-4 h-4 ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="container mx-auto max-w-7xl px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Copyright -->
                <div class="text-sm text-gray-400">
                    © <?php echo date('Y'); ?> Impact Marine Group. All rights reserved.
                </div>

                <!-- Additional Links -->
                <div class="flex space-x-6 text-sm">
                    <a href="/privacy-policy" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="/terms-of-service" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                    <a href="/sitemap" class="text-gray-400 hover:text-white transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Attribution -->
    <div class="bg-gray-950">
        <div class="container mx-auto max-w-7xl px-4 py-3">
            <div class="flex justify-center items-center text-sm text-gray-500">
                <span>Designed & Developed by</span>
                <a href="https://byronwade.com" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="inline-flex items-center ml-2 text-blue-400 hover:text-blue-300 transition-colors">
                    Byron Wade
                    <i data-lucide="external-link" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scroll-to-top" 
            class="fixed bottom-8 right-8 bg-blue-600 text-white p-2 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <span class="sr-only">Scroll to top</span>
        <i data-lucide="chevron-up" class="w-6 h-6"></i>
    </button>
</footer>

<script>
// Scroll to Top functionality
document.addEventListener('DOMContentLoaded', function() {
    const scrollButton = document.getElementById('scroll-to-top');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollButton.classList.remove('opacity-0', 'invisible');
            scrollButton.classList.add('opacity-100', 'visible');
        } else {
            scrollButton.classList.add('opacity-0', 'invisible');
            scrollButton.classList.remove('opacity-100', 'visible');
        }
    });

    scrollButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
