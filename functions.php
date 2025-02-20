<?php
/**
 * wades functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wades
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.1' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wades_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on wades, use a find and replace
		* to change 'wades' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'wades', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'wades' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'wades_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Add block support
	add_theme_support('block-templates');
	add_theme_support('block-template-parts');

	// Add theme support for page templates
	add_theme_support('page-templates');
}
add_action( 'after_setup_theme', 'wades_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wades_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wades_content_width', 640 );
}
add_action( 'after_setup_theme', 'wades_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wades_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wades' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wades' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wades_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wades_scripts() {
	wp_enqueue_style( 'wades-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wades-style', 'rtl', 'replace' );

	wp_enqueue_script( 'wades-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	
	// Enqueue Lucide UMD version from CDN
	wp_enqueue_script( 'lucide', 'https://unpkg.com/lucide@latest/dist/umd/lucide.js', array(), null, true );
	wp_add_inline_script( 'lucide', 'window.addEventListener("DOMContentLoaded", function() { lucide.createIcons(); });', 'after' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wades_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Register block patterns directory
function wades_register_block_patterns() {
	register_block_pattern_category(
		'wades',
		array('label' => __('Wade\'s Patterns', 'wades'))
	);
}
add_action('init', 'wades_register_block_patterns');

// Register block styles
function wades_register_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'content-wrapper',
			'label' => __('Content Wrapper', 'wades'),
		)
	);
}
add_action('init', 'wades_register_block_styles');

/**
 * Load theme settings
 */
require get_template_directory() . '/inc/theme-settings.php';

/**
 * Load custom navigation walkers
 */
require get_template_directory() . '/inc/class-wade-nav-walker.php';
require get_template_directory() . '/inc/class-wade-mobile-nav-walker.php';

/**
 * Load default content creation
 */
require get_template_directory() . '/inc/default-content.php';

/**
 * Custom Post Types and Taxonomies
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Custom Meta Boxes
 */
require get_template_directory() . '/inc/meta-boxes/about-meta.php';
require get_template_directory() . '/inc/meta-boxes/home-meta.php';
require get_template_directory() . '/inc/meta-boxes/shared-meta.php';
require get_template_directory() . '/inc/meta-boxes/boat-meta.php';

/**
 * Helper function to get meta values
 */
function wades_get_meta($key, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_' . $key, true);
}

function wades_block_editor_styles() {
    wp_enqueue_style(
        'wades-editor-styles',
        get_template_directory_uri() . '/assets/css/editor-styles.css',
        array(),
        _S_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'wades_block_editor_styles');

// Add theme support for editor styles
add_theme_support('editor-styles');

// Add these near your other requires at the top
require get_template_directory() . '/inc/block-patterns/templates.php';
require get_template_directory() . '/inc/template-blocks.php';

// Add this function to enqueue the block editor assets
function wades_enqueue_block_editor_assets() {
    // Enqueue the editor styles
    wp_enqueue_style(
        'wades-editor-styles',
        get_template_directory_uri() . '/assets/css/editor-styles.css',
        array(),
        _S_VERSION
    );

    // Enqueue the template sidebar script
    wp_enqueue_script(
        'wades-template-sidebar',
        get_template_directory_uri() . '/assets/js/template-sidebar.js',
        array(
            'wp-plugins',
            'wp-edit-post',
            'wp-element',
            'wp-components',
            'wp-data',
            'wp-i18n'
        ),
        _S_VERSION,
        true
    );
}
add_action('enqueue_block_editor_assets', 'wades_enqueue_block_editor_assets');

// Add this to enable block editor features
function wades_setup_block_editor() {
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for wide alignments
    add_theme_support('align-wide');
    
    // Add support for custom colors
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', 'wades'),
            'slug'  => 'primary',
            'color' => '#004AAD',
        ),
        array(
            'name'  => __('Light', 'wades'),
            'slug'  => 'light',
            'color' => '#F5F5F5',
        ),
        // Add more colors as needed
    ));
}
add_action('after_setup_theme', 'wades_setup_block_editor');

// Add this to register template post meta
function wades_register_meta() {
    register_post_meta('page', '_template_settings', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'object',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));

    // Register individual meta fields
    $meta_fields = array(
        '_hero_title',
        '_hero_description',
        '_services_title',
        '_services_subtitle',
        '_cta_text',
        '_cta_link',
        // Add other meta fields here
    );

    foreach ($meta_fields as $field) {
        register_post_meta('page', $field, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }
}
add_action('init', 'wades_register_meta');

// Add this to register block patterns
function wades_register_patterns() {
    // Register pattern categories
    register_block_pattern_category(
        'wades-templates',
        array('label' => __('Wade\'s Templates', 'wades'))
    );

    // Register patterns
    register_block_pattern(
        'wades/services-template',
        array(
            'title' => __('Services Template', 'wades'),
            'content' => file_get_contents(get_template_directory() . '/inc/block-patterns/services-pattern.html'),
            'categories' => array('wades-templates'),
        )
    );
    // Register other patterns...
}
add_action('init', 'wades_register_patterns');

/**
 * Register and handle page templates
 */
function wades_add_page_templates($templates) {
    $custom_templates = array(
        'templates/about.php'     => 'About Template',
        'templates/boats.php'     => 'Boats Template',
        'templates/contact.php'   => 'Contact Template',
        'templates/financing.php' => 'Financing Template',
        'templates/home.php'      => 'Home Template',
        'templates/services.php'  => 'Services Template'
    );
    
    return array_merge($templates, $custom_templates);
}
add_filter('theme_page_templates', 'wades_add_page_templates');

/**
 * Load custom page templates
 */
function wades_load_page_templates($template) {
    global $post;

    if (!is_page() || !$post) {
        return $template;
    }

    // Get the template slug
    $template_slug = get_page_template_slug($post->ID);
    
    if (empty($template_slug)) {
        return $template;
    }

    // Check if template file exists in theme directory
    $template_path = get_template_directory() . '/' . $template_slug;
    
    if (file_exists($template_path)) {
        return $template_path;
    }

    return $template;
}
add_filter('template_include', 'wades_load_page_templates', 99);

// Remove conflicting filters
remove_all_filters('template_include', 98);

function wades_admin_scripts() {
    if (is_admin()) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'wades_admin_scripts');

/**
 * Add meta boxes only when editing pages
 */
function wades_add_meta_boxes($post_type, $post) {
    if ($post_type !== 'page') {
        return;
    }

    $template = get_page_template_slug($post->ID);

    // Add meta boxes based on template
    switch ($template) {
        case 'templates/home.php':
            require_once get_template_directory() . '/inc/meta-boxes/home-meta.php';
            break;
        case 'templates/services.php':
            require_once get_template_directory() . '/inc/meta-boxes/services-meta.php';
            break;
        case 'templates/boats.php':
            require_once get_template_directory() . '/inc/meta-boxes/boats-meta.php';
            break;
    }
}
add_action('add_meta_boxes', 'wades_add_meta_boxes', 10, 2);

