<?php
/**
 * wades Theme Customizer
 *
 * @package wades
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wades_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'wades_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'wades_customize_partial_blogdescription',
			)
		);
	}

    // Add Company Information Section
    $wp_customize->add_section('company_info', array(
        'title'    => __('Company Information', 'wades'),
        'priority' => 30,
    ));

    // Add Company Address Setting
    $wp_customize->add_setting('company_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('company_address', array(
        'label'       => __('Company Address', 'wades'),
        'description' => __('Enter the company address (separate parts with commas)', 'wades'),
        'section'     => 'company_info',
        'type'        => 'textarea',
    ));

    // Add Company Email Setting
    $wp_customize->add_setting('company_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('company_email', array(
        'label'       => __('Company Email', 'wades'),
        'description' => __('Enter the company email address', 'wades'),
        'section'     => 'company_info',
        'type'        => 'email',
    ));
}
add_action( 'customize_register', 'wades_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wades_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site description for the selective refresh partial.
 *
 * @return void
 */
function wades_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wades_customize_preview_js() {
	wp_enqueue_script( 'wades-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'wades_customize_preview_js' );
