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

    // Add Header Settings Section
    $wp_customize->add_section('header_settings', array(
        'title' => __('Header Settings', 'wades'),
        'priority' => 30,
    ));

    // Default Hero Title
    $wp_customize->add_setting('default_hero_title', array(
        'default' => 'Exceptional Boats. Unparalleled Service.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control('default_hero_title', array(
        'label' => __('Default Hero Title', 'wades'),
        'section' => 'header_settings',
        'type' => 'text',
    ));

    // Default Hero Description
    $wp_customize->add_setting('default_hero_description', array(
        'default' => 'Your premier destination for luxury watercraft',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control('default_hero_description', array(
        'label' => __('Default Hero Description', 'wades'),
        'section' => 'header_settings',
        'type' => 'textarea',
    ));

    // Default Background Image
    $wp_customize->add_setting('default_hero_background', array(
        'default' => '',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'default_hero_background', array(
        'label' => __('Default Hero Background Image', 'wades'),
        'section' => 'header_settings',
        'mime_type' => 'image',
    )));

    // Header Height
    $wp_customize->add_setting('hero_height', array(
        'default' => '70',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control('hero_height', array(
        'label' => __('Header Height (vh)', 'wades'),
        'section' => 'header_settings',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 30,
            'max' => 100,
            'step' => 5,
        ),
    ));

    // Overlay Opacity
    $wp_customize->add_setting('hero_overlay_opacity', array(
        'default' => '40',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control('hero_overlay_opacity', array(
        'label' => __('Overlay Opacity (%)', 'wades'),
        'section' => 'header_settings',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 5,
        ),
    ));

    // Brand Logos Section
    $wp_customize->add_section('brand_logos', array(
        'title' => __('Brand Logos', 'wades'),
        'priority' => 35,
        'description' => __('Manage brand logos that appear in the page headers and footers.', 'wades'),
    ));

    // MB Sports Logo
    $wp_customize->add_setting('mb_sports_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mb_sports_logo', array(
        'label' => __('MB Sports Logo', 'wades'),
        'section' => 'brand_logos',
        'mime_type' => 'image',
        'description' => __('Upload the MB Sports logo (recommended size: 240x80px)', 'wades'),
    )));

    // Viaggio Logo
    $wp_customize->add_setting('viaggio_logo', array(
        'default' => '',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'viaggio_logo', array(
        'label' => __('Viaggio Logo', 'wades'),
        'section' => 'brand_logos',
        'mime_type' => 'image',
        'description' => __('Upload the Viaggio logo (recommended size: 240x80px)', 'wades'),
    )));

    // Show Brand Logos Setting
    $wp_customize->add_setting('show_brand_logos', array(
        'default' => true,
        'sanitize_callback' => 'wades_sanitize_checkbox',
    ));

    $wp_customize->add_control('show_brand_logos', array(
        'label' => __('Show Brand Logos', 'wades'),
        'section' => 'brand_logos',
        'type' => 'checkbox',
        'description' => __('Enable/disable brand logos in headers', 'wades'),
    ));

    // Brand Logos Background Setting
    $wp_customize->add_setting('brand_logos_bg', array(
        'default' => 'white',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('brand_logos_bg', array(
        'label' => __('Logo Background Style', 'wades'),
        'section' => 'brand_logos',
        'type' => 'select',
        'choices' => array(
            'white' => __('White Background', 'wades'),
            'transparent' => __('Transparent', 'wades'),
            'dark' => __('Dark Background', 'wades'),
        ),
    ));

    if (!function_exists('wades_sanitize_checkbox')) {
        function wades_sanitize_checkbox($checked) {
            return ((isset($checked) && true == $checked) ? true : false);
        }
    }
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
