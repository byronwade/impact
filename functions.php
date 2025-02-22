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
require get_template_directory() . '/inc/post-types.php';

/**
 * Custom Meta Boxes
 */
require get_template_directory() . '/inc/meta-boxes/about-meta.php';
require get_template_directory() . '/inc/meta-boxes/home-meta.php';
require get_template_directory() . '/inc/meta-boxes/shared-meta.php';
require get_template_directory() . '/inc/meta-boxes/boat-meta.php';
require get_template_directory() . '/inc/meta-boxes/services-meta.php';

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
        case 'templates/blog.php':
            require_once get_template_directory() . '/inc/meta-boxes/blog-meta.php';
            break;
    }
}
add_action('add_meta_boxes', 'wades_add_meta_boxes', 10, 2);

/**
 * Enqueue template-specific styles
 */
function wades_template_styles() {
    if (is_page_template('templates/home.php')) {
        wp_enqueue_style('wades-home-template', get_template_directory_uri() . '/assets/css/home-template.css', array(), _S_VERSION);
    }

    // Add blog styles for posts and blog index
    if (is_singular('post') || is_home() || is_archive()) {
        wp_enqueue_style('wades-blog', get_template_directory_uri() . '/assets/css/blog.css', array(), _S_VERSION);
        
        wp_add_inline_style('wades-blog', '
            /* Blog Grid Styles */
            .blog-grid {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                gap: 2rem;
                margin-bottom: 2rem;
            }
            
            @media (min-width: 768px) {
                .blog-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            
            @media (min-width: 1024px) {
                .blog-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            
            /* Blog Card Styles */
            .blog-card {
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                overflow: hidden;
                transition: transform 0.2s;
            }
            
            .blog-card:hover {
                transform: translateY(-4px);
            }
            
            .blog-card__image {
                aspect-ratio: 16/9;
                overflow: hidden;
            }
            
            .blog-card__image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }
            
            .blog-card:hover .blog-card__image img {
                transform: scale(1.05);
            }
            
            .blog-card__content {
                padding: 1.5rem;
            }
            
            .blog-card__meta {
                display: flex;
                align-items: center;
                gap: 1rem;
                font-size: 0.875rem;
                color: #6b7280;
                margin-bottom: 0.75rem;
            }
            
            .blog-card__title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #111827;
                margin-bottom: 0.75rem;
                line-height: 1.4;
            }
            
            .blog-card__excerpt {
                color: #4b5563;
                font-size: 0.875rem;
                line-height: 1.5;
                margin-bottom: 1rem;
            }
            
            .blog-card__link {
                color: var(--primary-color, #004AAD);
                font-size: 0.875rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: color 0.2s;
            }
            
            .blog-card__link:hover {
                color: var(--primary-color-dark, #003c8a);
            }
            
            /* Featured Post Styles */
            .featured-post {
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                margin-bottom: 3rem;
                overflow: hidden;
            }
            
            @media (min-width: 768px) {
                .featured-post {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            
            .featured-post__image {
                aspect-ratio: 16/9;
                position: relative;
            }
            
            @media (min-width: 768px) {
                .featured-post__image {
                    aspect-ratio: auto;
                    height: 100%;
                }
            }
            
            .featured-post__image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .featured-post__content {
                padding: 2rem;
            }
            
            .featured-post__badge {
                display: inline-block;
                background: var(--primary-color, #004AAD);
                color: white;
                font-size: 0.875rem;
                font-weight: 500;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                margin-bottom: 1rem;
            }
            
            .featured-post__meta {
                display: flex;
                align-items: center;
                gap: 1rem;
                font-size: 0.875rem;
                color: #6b7280;
                margin-bottom: 1rem;
            }
            
            .featured-post__title {
                font-size: 1.875rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 1rem;
                line-height: 1.3;
            }
            
            .featured-post__excerpt {
                color: #4b5563;
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }
            
            .featured-post__link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background: var(--primary-color, #004AAD);
                color: white;
                font-size: 0.875rem;
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                transition: background-color 0.2s;
            }
            
            .featured-post__link:hover {
                background: var(--primary-color-dark, #003c8a);
            }
            
            /* Blog Header Styles */
            .blog-header {
                text-align: center;
                margin-bottom: 3rem;
            }
            
            .blog-header__title {
                font-size: 2.25rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 1rem;
            }
            
            .blog-header__description {
                font-size: 1.125rem;
                color: #4b5563;
                max-width: 42rem;
                margin: 0 auto;
            }
            
            /* Single Post Styles */
            .single-post__header {
                margin-bottom: 2rem;
            }
            
            .single-post__meta {
                display: flex;
                align-items: center;
                gap: 1rem;
                font-size: 0.875rem;
                color: #6b7280;
                margin-bottom: 1rem;
            }
            
            .single-post__title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #111827;
                margin-bottom: 1rem;
                line-height: 1.2;
            }
            
            .single-post__excerpt {
                font-size: 1.25rem;
                color: #4b5563;
                line-height: 1.6;
            }
            
            .single-post__featured-image {
                margin-bottom: 2rem;
                border-radius: 0.75rem;
                overflow: hidden;
            }
            
            .single-post__featured-image img {
                width: 100%;
                height: auto;
            }
            
            .single-post__content {
                max-width: 65ch;
                margin: 0 auto;
            }
            
            /* Prose Styles */
            .prose {
                color: #374151;
                max-width: 65ch;
            }
            
            .prose p {
                margin-bottom: 1.5rem;
                line-height: 1.75;
            }
            
            .prose h2 {
                font-size: 1.875rem;
                font-weight: 700;
                color: #111827;
                margin: 2.5rem 0 1.5rem;
            }
            
            .prose h3 {
                font-size: 1.5rem;
                font-weight: 600;
                color: #111827;
                margin: 2rem 0 1.25rem;
            }
            
            .prose ul, .prose ol {
                margin: 1.5rem 0;
                padding-left: 1.5rem;
            }
            
            .prose li {
                margin: 0.5rem 0;
            }
            
            .prose a {
                color: var(--primary-color, #004AAD);
                text-decoration: none;
            }
            
            .prose a:hover {
                text-decoration: underline;
            }
            
            .prose blockquote {
                border-left: 4px solid var(--primary-color, #004AAD);
                margin: 1.5rem 0;
                padding: 1rem 0 1rem 1.5rem;
                font-style: italic;
                color: #4b5563;
            }
            
            .prose code {
                background: #f3f4f6;
                padding: 0.2rem 0.4rem;
                border-radius: 0.25rem;
                font-size: 0.875em;
            }
            
            .prose pre {
                background: #1f2937;
                color: #f3f4f6;
                padding: 1.25rem 1.5rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                margin: 1.5rem 0;
            }
            
            .prose img {
                border-radius: 0.75rem;
                margin: 1.5rem 0;
            }
            
            .prose figure {
                margin: 1.5rem 0;
            }
            
            .prose figcaption {
                font-size: 0.875rem;
                color: #6b7280;
                text-align: center;
                margin-top: 0.5rem;
            }
        ');
    }
}
add_action('wp_enqueue_scripts', 'wades_template_styles');

/**
 * Add featured post meta box
 */
function wades_add_featured_post_meta_box() {
    add_meta_box(
        'wades_featured_post',
        'Featured Post',
        'wades_featured_post_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_featured_post_meta_box');

/**
 * Featured post meta box callback
 */
function wades_featured_post_meta_box_callback($post) {
    wp_nonce_field('wades_featured_post', 'wades_featured_post_nonce');
    $is_featured = get_post_meta($post->ID, '_is_featured_post', true);
    ?>
    <label>
        <input type="checkbox" name="is_featured_post" value="1" <?php checked($is_featured, '1'); ?>>
        Mark as featured post
    </label>
    <?php
}

/**
 * Save featured post meta
 */
function wades_save_featured_post_meta($post_id) {
    if (!isset($_POST['wades_featured_post_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_featured_post_nonce'], 'wades_featured_post')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $is_featured = isset($_POST['is_featured_post']) ? '1' : '';
    update_post_meta($post_id, '_is_featured_post', $is_featured);
}
add_action('save_post', 'wades_save_featured_post_meta');

/**
 * Custom comment callback function
 */
function wades_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'bg-white rounded-xl shadow-md p-6' : 'bg-white rounded-xl shadow-md p-6 ml-12 mt-6', null, null, false); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta mb-4">
                <div class="flex items-start gap-4">
                    <?php
                    if (0 != $args['avatar_size']) {
                        echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full'));
                    }
                    ?>
                    <div>
                        <div class="comment-author vcard">
                            <?php printf('<b class="fn">%s</b>', get_comment_author_link()); ?>
                        </div>
                        <div class="comment-metadata">
                            <time datetime="<?php comment_time('c'); ?>" class="text-sm text-muted-foreground">
                                <?php
                                printf(
                                    '<a href="%s" class="hover:text-primary transition-colors">%s</a>',
                                    esc_url(get_comment_link($comment->comment_ID)),
                                    sprintf(
                                        /* translators: 1: comment date, 2: comment time */
                                        __('%1$s at %2$s', 'wades'),
                                        get_comment_date('', $comment),
                                        get_comment_time()
                                    )
                                );
                                ?>
                            </time>
                            <?php edit_comment_link(__('Edit', 'wades'), ' <span class="edit-link text-sm text-primary hover:text-primary/80 transition-colors">', '</span>'); ?>
                        </div>
                    </div>
                </div>

                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation text-sm text-yellow-600 mt-2"><?php _e('Your comment is awaiting moderation.', 'wades'); ?></p>
                <?php endif; ?>
            </footer>

            <div class="comment-content prose prose-sm max-w-none">
                <?php comment_text(); ?>
            </div>

            <?php
            comment_reply_link(array_merge($args, array(
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply mt-4">',
                'after'     => '</div>',
                'reply_text' => sprintf(
                    '<span class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors">%s <i data-lucide="reply" class="w-4 h-4 ml-1"></i></span>',
                    __('Reply', 'wades')
                ),
            )));
            ?>
        </article>
    </<?php echo $tag; ?>>
    <?php
}

/**
 * Create default pages and add them to menu
 */
function wades_create_default_pages() {
    // Create Services page if it doesn't exist
    $services_page = get_page_by_path('services');
    if (!$services_page) {
        // Create the page
        $services_page_id = wp_insert_post(array(
            'post_title'    => 'Services',
            'post_name'     => 'services',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_content'  => '<!-- wp:paragraph --><p>Our comprehensive marine services.</p><!-- /wp:paragraph -->',
            'menu_order'    => 20
        ));

        if (!is_wp_error($services_page_id)) {
            // Set the template
            update_post_meta($services_page_id, '_wp_page_template', 'templates/services.php');
            
            // Add to primary menu
            $menu_name = 'Primary';
            $menu_exists = wp_get_nav_menu_object($menu_name);
            
            if (!$menu_exists) {
                $menu_id = wp_create_nav_menu($menu_name);
            } else {
                $menu_id = $menu_exists->term_id;
            }

            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => 'Services',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $services_page_id,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
        }
    }
}
add_action('after_switch_theme', 'wades_create_default_pages');

// Run this once to ensure pages exist and are in menu
if (!get_option('wades_pages_created_v2')) {
    wades_create_default_pages();
    update_option('wades_pages_created_v2', true);
}

// Load template files and meta boxes on init to prevent header issues
function wades_load_template_files() {
    require_once get_template_directory() . '/inc/post-types.php';
    require_once get_template_directory() . '/inc/meta-boxes/about-meta.php';
    require_once get_template_directory() . '/inc/meta-boxes/home-meta.php';
    require_once get_template_directory() . '/inc/meta-boxes/shared-meta.php';
    require_once get_template_directory() . '/inc/meta-boxes/boat-meta.php';
    require_once get_template_directory() . '/inc/meta-boxes/blog-meta.php';
    require_once get_template_directory() . '/inc/block-patterns/templates.php';
    require_once get_template_directory() . '/inc/template-blocks.php';
}
add_action('init', 'wades_load_template_files', 5);

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

/**
 * Register and handle page templates
 */
function wades_add_page_templates($templates) {
    $custom_templates = array(
        'templates/about.php'     => 'About Template',
        'templates/boats.php'     => 'Boats Template',
        'templates/blog.php'      => 'Blog Template',
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

