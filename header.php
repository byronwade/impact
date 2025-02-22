<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wades
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wades' ); ?></a>

	<header class="sticky top-0 z-50 flex-shrink-0 border-b bg-background">
		<div class="container px-4 mx-auto">
			<div class="flex items-center justify-between h-16">
				<!-- Logo -->
				<a class="flex items-center space-x-2" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
					<?php
					if (has_custom_logo()) {
						$custom_logo_id = get_theme_mod('custom_logo');
						$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
						?>
						<img src="<?php echo esc_url($logo[0]); ?>"
							 alt="<?php bloginfo('name'); ?>"
							 class="w-auto h-10 max-w-[200px] object-contain"
						>
						<?php
					} else {
						?>
						<span class="text-xl font-bold"><?php bloginfo('name'); ?></span>
						<?php
					}
					?>
				</a>

				<!-- Desktop Navigation -->
				<nav class="hidden space-x-6 md:flex">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'menu-1',
						'container' => false,
						'items_wrap' => '%3$s',
						'walker' => new Wade_Nav_Walker(),
					));
					?>
				</nav>

				<!-- Phone Button -->
				<?php if ($phone = wades_get_setting('company_phone')) : ?>
					<a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>" 
					   class="items-center justify-center whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-xs hidden md:flex flex items-center">
						<i data-lucide="phone-call" class="w-3 h-3 mr-2"></i>
						<span><?php echo esc_html($phone); ?></span>
					</a>
				<?php endif; ?>

				<!-- Mobile Menu Button -->
				<button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 w-9 md:hidden"
						type="button"
						aria-label="Open menu"
						aria-expanded="false"
						data-toggle="mobile-menu">
					<i data-lucide="menu" class="w-5 h-5"></i>
					<span class="sr-only">Open menu</span>
				</button>
			</div>

			<!-- Mobile Menu -->
			<div id="mobile-menu" class="hidden md:hidden">
				<?php
				wp_nav_menu(array(
					'theme_location' => 'menu-1',
					'container' => 'nav',
					'container_class' => 'py-4',
					'menu_class' => 'space-y-2',
					'walker' => new Wade_Mobile_Nav_Walker(),
				));
				?>
				<?php if ($phone = wades_get_setting('company_phone')) : ?>
					<div class="py-4 border-t border-border">
						<a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>" 
						   class="flex items-center space-x-2 text-sm font-medium text-primary hover:text-primary/90">
							<i data-lucide="phone-call" class="w-4 h-4"></i>
							<span><?php echo esc_html($phone); ?></span>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</header>
