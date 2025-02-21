<?php
/**
 * Server-side rendering of the `wades/hero` block.
 *
 * @package wades
 */

/**
 * Renders the `wades/hero` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the hero section.
 */
function render_block_wades_hero($attributes, $content, $block) {
    $heading = $attributes['heading'] ?? '';
    $subheading = $attributes['subheading'] ?? '';
    $primary_cta_text = $attributes['primaryCtaText'] ?? '';
    $primary_cta_link = $attributes['primaryCtaLink'] ?? '';
    $secondary_cta_text = $attributes['secondaryCtaText'] ?? '';
    $secondary_cta_link = $attributes['secondaryCtaLink'] ?? '';

    $wrapper_attributes = get_block_wrapper_attributes();

    ob_start();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="container">
            <div class="content">
                <?php if ($heading) : ?>
                    <h1><?php echo esc_html($heading); ?></h1>
                <?php endif; ?>

                <?php if ($subheading) : ?>
                    <p><?php echo esc_html($subheading); ?></p>
                <?php endif; ?>

                <?php if ($primary_cta_text || $secondary_cta_text) : ?>
                    <div class="cta-buttons">
                        <?php if ($primary_cta_text && $primary_cta_link) : ?>
                            <a href="<?php echo esc_url($primary_cta_link); ?>" class="primary-cta">
                                <?php echo esc_html($primary_cta_text); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($secondary_cta_text && $secondary_cta_link) : ?>
                            <a href="<?php echo esc_url($secondary_cta_link); ?>" class="secondary-cta">
                                <?php echo esc_html($secondary_cta_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Registers the `wades/hero` block on the server.
 */
function register_block_wades_hero() {
    register_block_type(__DIR__, array(
        'render_callback' => 'render_block_wades_hero',
    ));
}
add_action('init', 'register_block_wades_hero');
