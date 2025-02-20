<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wades
 */

?>

<footer class="bg-background border-t">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4"><?php echo get_bloginfo('name'); ?></h3>
                <address class="not-italic">
                    <?php if ($address = get_theme_mod('company_address')) : ?>
                        <?php 
                        $address_parts = explode(',', $address);
                        foreach ($address_parts as $part) : ?>
                            <p><?php echo esc_html(trim($part)); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($email = get_theme_mod('company_email')) : ?>
                        <p class="mt-2">
                            <a class="hover:text-primary" href="mailto:<?php echo esc_attr($email); ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </address>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/about')); ?>">About Us</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
                </ul>
            </div>

            <!-- Boats -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Boats</h3>
                <ul class="space-y-2">
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/boats/new')); ?>">New Boats</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/boats/used')); ?>">Used Boats</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/financing')); ?>">Financing</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Services</h3>
                <ul class="space-y-2">
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/services/maintenance')); ?>">Maintenance</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/services/repairs')); ?>">Repairs</a></li>
                    <li><a class="hover:text-primary" href="<?php echo esc_url(home_url('/services/storage')); ?>">Storage</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t text-center text-sm text-muted-foreground">
            <p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
