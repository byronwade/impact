<?php
/**
 * Block Patterns for Page Templates
 */

function wades_register_template_patterns() {
    // Services Template Pattern
    register_block_pattern(
        'wades/services-template',
        array(
            'title'       => __('Services Template', 'wades'),
            'description' => __('Full template for services pages with hero, services grid, and CTA.', 'wades'),
            'categories'  => array('wades-templates'),
            'content'     => '<!-- wp:group {"className":"services-template"} -->
                <div class="wp-block-group services-template">
                    <!-- wp:group {"className":"hero-section"} -->
                    <div class="wp-block-group hero-section">
                        <!-- wp:heading {"level":1} -->
                        <h1>Services</h1>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph -->
                        <p>Our comprehensive marine services</p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"className":"services-grid"} -->
                    <div class="wp-block-group services-grid">
                        <!-- wp:columns -->
                        <div class="wp-block-columns">
                            <!-- wp:column -->
                            <div class="wp-block-column">
                                <!-- wp:heading {"level":3} -->
                                <h3>Service 1</h3>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph -->
                                <p>Service description here...</p>
                                <!-- /wp:paragraph -->
                            </div>
                            <!-- /wp:column -->
                            
                            <!-- Add more columns as needed -->
                        </div>
                        <!-- /wp:columns -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"className":"cta-section"} -->
                    <div class="wp-block-group cta-section">
                        <!-- wp:heading -->
                        <h2>Ready to get started?</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:buttons -->
                        <div class="wp-block-buttons">
                            <!-- wp:button -->
                            <div class="wp-block-button">
                                <a class="wp-block-button__link">Contact Us</a>
                            </div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->'
        )
    );

    // Add more patterns for other templates...
}
add_action('init', 'wades_register_template_patterns');

// Register template pattern category
function wades_register_template_pattern_category() {
    register_block_pattern_category(
        'wades-templates',
        array('label' => __('Wade\'s Templates', 'wades'))
    );
}
add_action('init', 'wades_register_template_pattern_category'); 