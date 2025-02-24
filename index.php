<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wades
 */

get_header();

// Get blog settings
$blog_settings = wades_get_blog_settings();

// Extract settings
$hero_title = $blog_settings['hero_title'];
$hero_description = $blog_settings['hero_description'];
$hero_background_image = $blog_settings['hero_image'];
$hero_overlay_opacity = $blog_settings['hero_opacity'];
$show_featured = $blog_settings['show_featured'];
$show_categories = $blog_settings['show_categories'];
$posts_per_page = $blog_settings['posts_per_page'];
$show_sidebar = $blog_settings['show_sidebar'];
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
	<!-- Hero Section -->
	<section class="relative py-24 overflow-hidden">
		<!-- Background Image with Overlay -->
		<div class="absolute inset-0">
			<?php if ($hero_background_image && wp_get_attachment_image_url($hero_background_image, 'full')) : ?>
				<?php echo wp_get_attachment_image($hero_background_image, 'full', false, array(
					'class' => 'absolute inset-0 w-full h-full object-cover',
					'alt' => esc_attr($hero_title)
				)); ?>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-r from-primary to-primary-dark"></div>
			<?php endif; ?>
			<div class="absolute inset-0 bg-gradient-to-r from-black/<?php echo esc_attr($hero_overlay_opacity); ?> to-black/25"></div>
		</div>

		<!-- Content -->
		<div class="container relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			<div class="max-w-3xl">
				<h1 class="text-4xl sm:text-5xl font-bold text-white mb-6"><?php echo esc_html($hero_title); ?></h1>
				<div class="text-xl text-white/90"><?php echo wp_kses_post($hero_description); ?></div>
			</div>
		</div>
	</section>

	<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
		<?php if ($show_categories) : ?>
			<!-- Categories Filter -->
			<div class="flex flex-wrap justify-center gap-2 mb-12">
				<?php
				$categories = get_categories();
				foreach ($categories as $category) :
					$active_class = (is_category($category->term_id)) ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
				?>
					<a href="<?php echo get_category_link($category->term_id); ?>" 
					   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo $active_class; ?>">
						<?php echo esc_html($category->name); ?>
						<span class="ml-1 text-xs">(<?php echo $category->count; ?>)</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ($show_featured && !is_paged()) : ?>
			<!-- Featured Post -->
			<?php
			$featured_args = array(
				'posts_per_page' => 1,
				'meta_key' => '_is_featured_post',
				'meta_value' => '1'
			);
			$featured_query = new WP_Query($featured_args);

			if ($featured_query->have_posts()) :
				while ($featured_query->have_posts()) : $featured_query->the_post();
			?>
			<article class="featured-post bg-white rounded-xl shadow-lg overflow-hidden mb-12">
				<div class="grid md:grid-cols-2 gap-8">
					<div class="relative aspect-w-16 aspect-h-9">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('large', array(
								'class' => 'object-cover w-full h-full',
								'alt' => get_the_title()
							)); ?>
						<?php else : ?>
							<?php echo wades_get_image_html(0, 'large', array(
								'class' => 'object-cover w-full h-full',
								'alt' => get_the_title()
							)); ?>
						<?php endif; ?>
					</div>
					<div class="p-8 flex flex-col justify-center">
						<div class="inline-flex items-center rounded-full bg-primary/10 text-primary px-3 py-1 text-sm font-medium mb-4">
							Featured Post
						</div>
						<div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
							<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
							<?php
							$categories = get_the_category();
							if ($categories) {
								echo '<span>•</span><span>' . esc_html($categories[0]->name) . '</span>';
							}
							?>
						</div>
						<h2 class="text-2xl font-bold mb-4">
							<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
								<?php the_title(); ?>
							</a>
						</h2>
						<div class="text-muted-foreground mb-6">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" 
						   class="inline-flex items-center text-primary hover:text-primary/80 font-medium">
							Read More
							<i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
						</a>
					</div>
				</div>
			</article>
			<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		<?php endif; ?>

		<!-- Main Content -->
		<div class="grid grid-cols-1 <?php echo $show_sidebar ? 'lg:grid-cols-3 gap-12' : 'md:grid-cols-2 lg:grid-cols-3 gap-8'; ?>">
			<!-- Posts Grid -->
			<div class="<?php echo $show_sidebar ? 'lg:col-span-2' : 'col-span-full'; ?>">
				<div class="grid grid-cols-1 <?php echo $show_sidebar ? 'md:grid-cols-2' : 'md:grid-cols-2 lg:grid-cols-3'; ?> gap-8">
					<?php
					if (have_posts()) :
						while (have_posts()) : the_post();
							// Skip featured post on first page
							if ($show_featured && !is_paged() && get_post_meta(get_the_ID(), '_is_featured_post', true)) {
								continue;
							}
					?>
					<article <?php post_class('bg-white rounded-xl overflow-hidden shadow-lg transition-transform hover:translate-y-[-4px]'); ?>>
						<?php if (has_post_thumbnail()) : ?>
							<div class="aspect-video overflow-hidden">
								<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
							</div>
						<?php else : ?>
							<?php echo wades_get_image_html(0, 'medium_large', array(
								'class' => 'w-full h-full object-cover',
								'alt' => get_the_title()
							)); ?>
						<?php endif; ?>
						<div class="p-6">
							<div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
								<i data-lucide="calendar" class="w-4 h-4"></i>
								<?php echo get_the_date(); ?>
							</div>
							<h2 class="text-xl font-semibold mb-2">
								<a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
									<?php the_title(); ?>
								</a>
							</h2>
							<p class="text-gray-600 mb-4"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
							<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700">
								Read More
								<i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
							</a>
						</div>
					</article>
					<?php
						endwhile;
					else :
					?>
						<div class="col-span-full text-center py-12">
							<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
								<i data-lucide="file-text" class="w-8 h-8 text-blue-600"></i>
							</div>
							<h2 class="text-2xl font-semibold mb-2">No Posts Found</h2>
							<p class="text-gray-600">It seems we can't find what you're looking for.</p>
						</div>
					<?php endif; ?>
				</div>

				<!-- Pagination -->
				<?php if ($wp_query->max_num_pages > 1) : ?>
					<div class="mt-12 flex justify-center">
						<?php
						echo paginate_links(array(
							'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous',
							'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4"></i>',
							'type' => 'list',
							'class' => 'pagination'
						));
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($show_sidebar) : ?>
				<!-- Sidebar -->
				<aside class="lg:col-span-1">
					<?php get_sidebar(); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Initialize Lucide icons
	lucide.createIcons();
});
</script>

<style>
/* Card Styles */
.blog-card {
	transition: all 0.2s ease-in-out;
}

/* Pagination Styles */
.page-numbers {
	display: inline-flex;
	align-items: center;
	gap: 0.5rem;
	list-style: none;
	padding: 0;
	margin: 0;
}

.page-numbers li {
	margin: 0;
}

.page-numbers a,
.page-numbers span {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 2.5rem;
	height: 2.5rem;
	padding: 0 0.75rem;
	border-radius: 0.375rem;
	font-size: 0.875rem;
	font-weight: 500;
	transition: all 0.2s;
}

.page-numbers a {
	background-color: white;
	border: 1px solid #e5e7eb;
	color: #374151;
}

.page-numbers a:hover {
	background-color: #f3f4f6;
}

.page-numbers span.current {
	background-color: #2563eb;
	color: white;
}

.page-numbers .dots {
	color: #6b7280;
}
</style>

<?php get_footer(); ?>
