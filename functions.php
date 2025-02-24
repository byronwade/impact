<?php
/**
 * wades functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wades
 */

// Include helper functions
require get_template_directory() . '/inc/helpers.php';

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
 * Load Instagram feed functionality
 */
require get_template_directory() . '/inc/instagram-feed.php';

/**
 * Load custom navigation walkers
 */
require get_template_directory() . '/inc/class-wade-nav-walker.php';
require get_template_directory() . '/inc/class-wade-mobile-nav-walker.php';

/**
 * Load post types
 */
require_once get_template_directory() . '/inc/post-types/service.php';

// Include meta boxes
require_once get_template_directory() . '/inc/meta-boxes/about-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/home-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/shared-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/boat-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/boats-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/services-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/financing-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/contact-meta.php';
require_once get_template_directory() . '/inc/meta-boxes/blog-meta.php';

// Load meta boxes
require_once get_template_directory() . '/inc/meta-boxes.php';

// Remove old meta box loading code
remove_action('init', 'wades_load_template_files', 5);
remove_action('add_meta_boxes', 'wades_add_meta_boxes', 10);
remove_action('add_meta_boxes', 'wades_add_template_meta_boxes', 10, 2);

// Remove the old page header meta box registration
remove_action('add_meta_boxes', 'wades_add_page_header_meta_box', 10);

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

// Add this function to enqueue the block editor assets
function wades_enqueue_block_editor_assets() {
    // Enqueue the editor styles
    wp_enqueue_style(
        'wades-editor-styles',
        get_template_directory_uri() . '/assets/css/editor-styles.css',
        array(),
        _S_VERSION
    );
}

// Debug function for template registration
function wades_debug_log($message) {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        error_log('WADES TEMPLATE: ' . $message);
    }
}

// Register page templates
function wades_add_page_templates($templates) {
    wades_debug_log('Adding page templates');
    wades_debug_log('Current templates: ' . print_r($templates, true));
    
    $custom_templates = array(
        'templates/boats.php' => 'Boats Template',
        'templates/about.php' => 'About Template',
        'templates/blog.php' => 'Blog Template',
        'templates/contact.php' => 'Contact Template',
        'templates/financing.php' => 'Financing Template',
        'templates/home.php' => 'Home Template',
        'templates/services.php' => 'Services Template'
    );
    
    $merged_templates = array_merge($templates, $custom_templates);
    wades_debug_log('Final templates: ' . print_r($merged_templates, true));
    
    return $merged_templates;
}
add_filter('theme_page_templates', 'wades_add_page_templates', 20);

// Remove any conflicting template registrations
remove_all_filters('theme_page_templates', 10);

// Load page templates with debugging
function wades_load_page_templates($template) {
    global $post;
    
    if (!$post) {
        wades_debug_log('No post object found');
        return $template;
    }
    
    if (!is_page()) {
        wades_debug_log('Not a page');
        return $template;
    }
    
    $template_slug = get_page_template_slug($post->ID);
    wades_debug_log('Loading template for page ID: ' . $post->ID);
    wades_debug_log('Page title: ' . get_the_title($post->ID));
    wades_debug_log('Template slug: ' . $template_slug);
    wades_debug_log('Current template: ' . $template);
    
    if (empty($template_slug)) {
        wades_debug_log('No template slug found');
        return $template;
    }
    
    // Check if template exists in theme directory
    $template_path = get_template_directory() . '/' . $template_slug;
    wades_debug_log('Looking for template at: ' . $template_path);
    
    if (file_exists($template_path)) {
        wades_debug_log('Template found, loading: ' . $template_path);
        return $template_path;
    }
    
    // Additional check for templates in subdirectories
    $template_parts = explode('/', $template_slug);
    if (count($template_parts) > 1) {
        $alt_template_path = get_template_directory() . '/' . end($template_parts);
        wades_debug_log('Checking alternative template path: ' . $alt_template_path);
        
        if (file_exists($alt_template_path)) {
            wades_debug_log('Alternative template found, loading: ' . $alt_template_path);
            return $alt_template_path;
        }
    }
    
    wades_debug_log('Template not found, using default: ' . $template);
    return $template;
}
add_filter('template_include', 'wades_load_page_templates', 99);

// Ensure template loading filter is registered early
function wades_ensure_template_loading() {
    remove_all_filters('template_include', 98);
    if (!has_filter('template_include', 'wades_load_page_templates')) {
        add_filter('template_include', 'wades_load_page_templates', 99);
        wades_debug_log('Template loading filter registered');
    }
}
add_action('init', 'wades_ensure_template_loading', 5);

function wades_admin_scripts() {
    if (is_admin()) {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        
        // Enqueue meta box styles
        wp_enqueue_style(
            'wades-meta-box-styles',
            get_template_directory_uri() . '/inc/meta-boxes/meta-box-styles.css',
            array(),
            _S_VERSION
        );
    }
}
add_action('admin_enqueue_scripts', 'wades_admin_scripts');

/**
 * Create default services when theme is activated
 */
function wades_create_default_services() {
    $default_services = array(
        array(
            'title' => 'Winterization',
            'content' => 'Comprehensive winterization services to protect your boat during the off-season.',
            'icon' => 'snowflake',
            'features' => array(
                'Engine winterization',
                'Antifreeze protection',
                'Battery maintenance',
                'Shrink wrap protection',
                'Climate-controlled storage'
            ),
            'location' => 'shop',
            'show_on_home' => true,
            'home_order' => 1
        ),
        array(
            'title' => 'Trailer Repair',
            'content' => 'Professional trailer repair services to keep your boat transport safe and reliable.',
            'icon' => 'truck',
            'features' => array('Brake service', 'Bearing replacement', 'Frame repair', 'Wiring repairs'),
            'location' => 'both'
        ),
        array(
            'title' => 'Speed Control Install',
            'content' => 'Expert installation of precision speed control systems for optimal performance.',
            'icon' => 'gauge',
            'features' => array('Zero-Off installation', 'Perfect Pass systems', 'Custom calibration', 'Performance testing'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Engine Repair',
            'content' => 'Comprehensive engine repair and maintenance services for all marine engines.',
            'icon' => 'engine',
            'features' => array('Diagnostic testing', 'Engine rebuilds', 'Performance tuning', 'Preventive maintenance'),
            'location' => 'both'
        ),
        array(
            'title' => 'Marine Audio Install',
            'content' => 'Custom marine audio installations for the ultimate on-water entertainment.',
            'icon' => 'music',
            'features' => array('Waterproof systems', 'Custom speaker installation', 'Amplifier setup', 'Bluetooth integration'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Complete Boat Detail',
            'content' => 'Thorough boat detailing services to keep your vessel looking its best.',
            'icon' => 'sparkles',
            'features' => array('Hull cleaning', 'Interior detailing', 'Waxing & polishing', 'Carpet cleaning'),
            'location' => 'both'
        ),
        array(
            'title' => 'Fiberglass Repair',
            'content' => 'Expert fiberglass repair and restoration services.',
            'icon' => 'hammer',
            'features' => array('Structural repairs', 'Gelcoat matching', 'Surface restoration', 'Custom fabrication'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Ceramic Coating',
            'content' => 'Professional ceramic coating application for superior protection.',
            'icon' => 'shield',
            'features' => array('UV protection', 'Long-lasting finish', 'Easy maintenance', 'Scratch resistance'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Heater Install',
            'content' => 'Custom heater installations for comfortable boating in any weather.',
            'icon' => 'thermometer',
            'features' => array('Custom fitting', 'Multiple zone control', 'Efficient systems', 'Professional installation'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Tower Install',
            'content' => 'Professional tower installation and customization services.',
            'icon' => 'tower',
            'features' => array('Custom fitting', 'Speaker integration', 'Light installation', 'Accessory mounting'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Swim Deck Reteak',
            'content' => 'Expert swim deck restoration and teak replacement services.',
            'icon' => 'ruler',
            'features' => array('Custom fitting', 'Weather resistance', 'Non-slip surface', 'Professional finish'),
            'location' => 'shop'
        ),
        array(
            'title' => 'L.E.D. Lighting Install',
            'content' => 'Custom LED lighting solutions for your boat.',
            'icon' => 'lightbulb',
            'features' => array('Underwater lights', 'Interior lighting', 'Navigation lights', 'Custom effects'),
            'location' => 'shop'
        ),
        array(
            'title' => 'Dockside Service',
            'content' => 'Convenient dockside maintenance and repair services.',
            'icon' => 'anchor',
            'features' => array('Basic maintenance', 'System checks', 'Emergency repairs', 'Seasonal service'),
            'location' => 'mobile'
        )
    );

    foreach ($default_services as $service) {
        // Check if service already exists
        $existing_service = get_page_by_title($service['title'], OBJECT, 'service');
        
        if (!$existing_service) {
            // Create the service
            $service_data = array(
                'post_title'    => $service['title'],
                'post_content'  => $service['content'],
                'post_status'   => 'publish',
                'post_type'     => 'service'
            );
            
            $service_id = wp_insert_post($service_data);
            
            if (!is_wp_error($service_id)) {
                // Add service meta
                update_post_meta($service_id, '_service_icon', $service['icon']);
                update_post_meta($service_id, '_service_features', $service['features']);
                update_post_meta($service_id, '_service_location', $service['location']);
                
                // Set default price range
                update_post_meta($service_id, '_service_price', 'Contact for Quote');
                
                // Set to show on homepage by default if specified
                if (isset($service['show_on_home']) && $service['show_on_home']) {
                    update_post_meta($service_id, '_show_on_home', '1');
                    update_post_meta($service_id, '_home_order', $service['home_order']);
                } else {
                    update_post_meta($service_id, '_show_on_home', '0');
                    update_post_meta($service_id, '_home_order', '10');
                }
            }
        }
    }
}

// Run this when theme is activated
add_action('after_switch_theme', 'wades_create_default_services');

// Run this once to ensure services exist
if (!get_option('wades_services_created')) {
    wades_create_default_services();
    update_option('wades_services_created', true);
}

/**
 * Disable Gutenberg for template pages
 */
function wades_disable_gutenberg($can_edit, $post_type) {
    if ($post_type !== 'page') {
        return $can_edit;
    }

    $template = get_page_template_slug(get_the_ID());
    $template_pages = array(
        'templates/boats.php',
        'templates/about.php',
        'templates/blog.php',
        'templates/contact.php',
        'templates/financing.php',
        'templates/home.php',
        'templates/services.php'
    );

    if (in_array($template, $template_pages)) {
        return false;
    }

    return $can_edit;
}
add_filter('use_block_editor_for_post_type', 'wades_disable_gutenberg', 10, 2);

/**
 * Remove the content editor from template pages
 */
function wades_remove_content_editor() {
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else if (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    } else {
        return;
    }

    if (!isset($post_id)) {
        return;
    }

    $template = get_page_template_slug($post_id);
    $template_pages = array(
        'templates/boats.php',
        'templates/about.php',
        'templates/blog.php',
        'templates/contact.php',
        'templates/financing.php',
        'templates/home.php',
        'templates/services.php'
    );

    if (in_array($template, $template_pages)) {
        remove_post_type_support('page', 'editor');
    }
}
add_action('admin_init', 'wades_remove_content_editor');

/**
 * Add notice to template pages about using meta boxes
 */
function wades_template_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id !== 'page') {
        return;
    }

    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else if (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    } else {
        return;
    }

    $template = get_page_template_slug($post_id);
    $template_pages = array(
        'templates/boats.php',
        'templates/about.php',
        'templates/blog.php',
        'templates/contact.php',
        'templates/financing.php',
        'templates/home.php',
        'templates/services.php'
    );

    if (in_array($template, $template_pages)) {
        $template_name = ucfirst(str_replace(array('templates/', '.php'), '', $template));
        echo '<div class="notice notice-info">
            <p><strong>' . $template_name . ' Template:</strong> This page uses a custom template. Please use the settings below to customize the content.</p>
        </div>';
    }
}
add_action('admin_notices', 'wades_template_admin_notice');

// Check and fix template assignments
function wades_check_template_assignments() {
    $services_page = get_page_by_path('services');
    if ($services_page) {
        $template = get_post_meta($services_page->ID, '_wp_page_template', true);
        wades_debug_log('Services page template: ' . $template);
        
        // If template is not set or incorrect, set it
        if (empty($template) || $template !== 'templates/services.php') {
            update_post_meta($services_page->ID, '_wp_page_template', 'templates/services.php');
            wades_debug_log('Updated services page template');
        }
    } else {
        wades_debug_log('Services page not found');
    }
}
add_action('init', 'wades_check_template_assignments', 1);

// Prevent template conflicts
function wades_prevent_template_conflicts($template) {
    global $post;
    
    if (!$post) {
        return $template;
    }
    
    // Check if we're on the services page
    if ($post->post_name === 'services') {
        $services_template = get_template_directory() . '/templates/services.php';
        if (file_exists($services_template)) {
            wades_debug_log('Forcing services template for services page');
            return $services_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'wades_prevent_template_conflicts', 100);

// Remove all previous template handling code
remove_all_filters('template_include');
remove_all_filters('page_template');
remove_all_filters('single_template');
remove_all_filters('archive_template');
remove_all_filters('category_template');
remove_all_filters('home_template');
remove_all_filters('frontpage_template');
remove_all_filters('index_template');

// Handle services page directly
add_action('parse_request', function($wp) {
    // Check if this is the services page
    if (isset($wp->request) && $wp->request === 'services') {
        // Force this to be treated as a page
        $wp->query_vars['post_type'] = 'page';
        $wp->query_vars['pagename'] = 'services';
        
        // Remove any blog-related query vars
        unset($wp->query_vars['post_type']);
        unset($wp->query_vars['name']);
        unset($wp->query_vars['category_name']);
        unset($wp->query_vars['feed']);
        unset($wp->query_vars['author']);
        unset($wp->query_vars['withcomments']);
        unset($wp->query_vars['withoutcomments']);
        unset($wp->query_vars['year']);
        unset($wp->query_vars['monthnum']);
        unset($wp->query_vars['day']);
        unset($wp->query_vars['w']);
        unset($wp->query_vars['tag']);
        unset($wp->query_vars['cat']);
    }
}, 1);

// Force services template
add_action('template_redirect', function() {
    global $wp_query;
    
    // Check if this is the services page
    if (isset($wp_query->query['pagename']) && $wp_query->query['pagename'] === 'services') {
        // Force this to be a page
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        $wp_query->is_category = false;
        $wp_query->is_tag = false;
        $wp_query->is_tax = false;
        $wp_query->is_page = true;
        $wp_query->is_single = false;
        
        // Load the services template
        $template = get_template_directory() . '/templates/services.php';
        if (file_exists($template)) {
            include($template);
            exit;
        }
    }
}, 0);

// Prevent any redirects
add_filter('redirect_canonical', function($redirect_url, $requested_url) {
    if (strpos($requested_url, '/services') !== false) {
        return false;
    }
    return $redirect_url;
}, 10, 2);

// Register services template
add_filter('theme_page_templates', function($templates) {
    $templates['templates/services.php'] = 'Services Template';
    return $templates;
}, 20);

/**
 * Custom comment callback
 */
function wades_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                    if ($args['avatar_size'] != 0) {
                        echo get_avatar($comment, $args['avatar_size']);
                    }
                    printf('<b class="fn">%s</b>', get_comment_author_link());
                    ?>
                </div>

                <div class="comment-metadata">
                    <time datetime="<?php comment_time('c'); ?>">
                        <?php
                        printf(
                            _x('%1$s at %2$s', '1: date, 2: time'),
                            get_comment_date(),
                            get_comment_time()
                        );
                        ?>
                    </time>
                    <?php edit_comment_link(__('Edit'), ' <span class="edit-link">', '</span>'); ?>
                </div>

                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em>
                <?php endif; ?>
            </footer>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="reply">
                <?php
                comment_reply_link(array_merge($args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth']
                )));
                ?>
            </div>
        </article>
    <?php
}

// Add comment styles
function wades_comment_styles() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_style('wades-comments', get_template_directory_uri() . '/assets/css/comments.css', array(), _S_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'wades_comment_styles');

/**
 * AJAX handler for loading more posts
 */
function wades_load_more_posts() {
    check_ajax_referer('load_more_posts', 'nonce');

    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    $posts_per_page = get_option('posts_per_page');

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'post');
        }
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_load_more_posts', 'wades_load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'wades_load_more_posts');

