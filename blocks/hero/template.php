<?php
/**
 * Hero Block Template
 * 
 * @package wades
 */

$heading = $args['title'] ?? '';
$subheading = $args['description'] ?? '';
$primary_cta = $args['primaryCta'] ?? null;
$secondary_cta = $args['secondaryCta'] ?? null;
$background_image = isset($args['backgroundImage']['id']) ? wp_get_attachment_image_url($args['backgroundImage']['id'], 'full') : '';
?>

<section class="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white py-24">
    <?php if ($background_image) : ?>
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($background_image); ?>" alt="" class="w-full h-full object-cover opacity-20">
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-6"><?php echo esc_html($heading); ?></h1>
            <p class="text-xl mb-8"><?php echo wp_kses_post($subheading); ?></p>
            
            <?php if ($primary_cta || $secondary_cta) : ?>
                <div class="flex justify-center gap-4">
                    <?php if ($primary_cta) : ?>
                        <a href="<?php echo esc_url($primary_cta['link']); ?>" 
                           class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                            <?php echo esc_html($primary_cta['text']); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($secondary_cta) : ?>
                        <a href="<?php echo esc_url($secondary_cta['link']); ?>" 
                           class="border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors">
                            <?php echo esc_html($secondary_cta['text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section> 