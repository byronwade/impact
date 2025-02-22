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
?>

<main role="main" aria-label="Main content" class="flex-grow bg-gray-50">
	<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
		<!-- Blog Header -->
		<header class="blog-header">
			<h1 class="blog-header__title"><?php echo get_the_title(get_option('page_for_posts')); ?></h1>
			<p class="blog-header__description">Stay updated with our latest news, insights, and updates from Impact Marine Group.</p>
		</header>

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
		<article class="featured-post">
			<div class="featured-post__image">
				<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('large', array(
						'class' => 'object-cover w-full h-full',
						'alt' => get_the_title()
					)); ?>
				<?php endif; ?>
			</div>
			<div class="featured-post__content">
				<span class="featured-post__badge">Featured</span>
				<div class="featured-post__meta">
					<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
					<span>•</span>
					<?php
					$categories = get_the_category();
					if ($categories) {
						echo '<span>' . esc_html($categories[0]->name) . '</span>';
					}
					?>
				</div>
				<h2 class="featured-post__title">
					<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
						<?php the_title(); ?>
					</a>
				</h2>
				<div class="featured-post__excerpt">
					<?php the_excerpt(); ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="featured-post__link">
					Read More
					<i data-lucide="chevron-right" class="w-4 h-4"></i>
				</a>
			</div>
		</article>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>

		<!-- Posts Grid -->
		<div class="blog-grid">
			<?php
			if (have_posts()) :
				while (have_posts()) : the_post();
					// Skip if this is the featured post
					if (get_post_meta(get_the_ID(), '_is_featured_post', true)) {
						continue;
					}
			?>
			<article class="blog-card">
				<div class="blog-card__image">
					<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('medium_large', array(
							'class' => 'object-cover w-full h-full',
							'alt' => get_the_title()
						)); ?>
					<?php endif; ?>
				</div>
				<div class="blog-card__content">
					<div class="blog-card__meta">
						<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
						<span>•</span>
						<?php
						$categories = get_the_category();
						if ($categories) {
							echo '<span>' . esc_html($categories[0]->name) . '</span>';
						}
						?>
					</div>
					<h2 class="blog-card__title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
					<div class="blog-card__excerpt">
						<?php the_excerpt(); ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="blog-card__link">
						Read More
						<i data-lucide="chevron-right" class="w-4 h-4"></i>
					</a>
				</div>
			</article>
			<?php
				endwhile;

				// Pagination
				echo '<div class="mt-8 flex justify-center">';
				echo paginate_links(array(
					'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous',
					'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4"></i>',
					'class' => 'flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50'
				));
				echo '</div>';
			endif;
			?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
