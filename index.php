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
		<header class="text-center mb-12">
			<h1 class="text-4xl font-bold mb-4"><?php echo get_the_title(get_option('page_for_posts')); ?></h1>
			<p class="text-xl text-muted-foreground max-w-2xl mx-auto">Stay updated with our latest news, insights, and updates from Impact Marine Group.</p>
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
		<div class="mb-16">
			<article class="bg-white rounded-xl shadow-lg overflow-hidden">
				<div class="grid md:grid-cols-2 gap-8">
					<div class="relative aspect-w-16 aspect-h-9 md:aspect-none md:h-full">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('large', array(
								'class' => 'object-cover w-full h-full',
								'alt' => get_the_title()
							)); ?>
						<?php endif; ?>
						<div class="absolute top-4 left-4">
							<span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-primary text-white">
								Featured
							</span>
						</div>
					</div>
					<div class="p-8">
						<div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
							<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
							<span>•</span>
							<?php
							$categories = get_the_category();
							if ($categories) {
								echo '<span>' . esc_html($categories[0]->name) . '</span>';
							}
							?>
						</div>
						<h2 class="text-3xl font-bold mb-4">
							<a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
								<?php the_title(); ?>
							</a>
						</h2>
						<div class="prose prose-sm text-muted-foreground mb-6">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium bg-primary text-white hover:bg-primary/90 transition-colors">
							Read More
							<i data-lucide="chevron-right" class="w-4 h-4 ml-2"></i>
						</a>
					</div>
				</div>
			</article>
		</div>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>

		<!-- Posts Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			if (have_posts()) :
				while (have_posts()) : the_post();
					// Skip if this is the featured post
					if (get_post_meta(get_the_ID(), '_is_featured_post', true)) {
						continue;
					}
			?>
			<article class="bg-white rounded-xl shadow-md overflow-hidden group">
				<div class="relative aspect-w-16 aspect-h-9">
					<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('medium_large', array(
							'class' => 'object-cover w-full h-full transition-transform duration-300 group-hover:scale-105',
							'alt' => get_the_title()
						)); ?>
					<?php endif; ?>
				</div>
				<div class="p-6">
					<div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
						<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
						<span>•</span>
						<?php
						$categories = get_the_category();
						if ($categories) {
							echo '<span>' . esc_html($categories[0]->name) . '</span>';
						}
						?>
					</div>
					<h2 class="text-xl font-semibold mb-4 group-hover:text-primary transition-colors">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
					<div class="prose prose-sm text-muted-foreground mb-6">
						<?php the_excerpt(); ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors">
						Read More
						<i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
					</a>
				</div>
			</article>
			<?php
				endwhile;
			endif;
			?>
		</div>

		<!-- Pagination -->
		<?php if (get_the_posts_pagination()) : ?>
		<div class="mt-12 flex justify-center items-center gap-2">
			<?php
			echo paginate_links(array(
				'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i> Previous',
				'next_text' => 'Next <i data-lucide="chevron-right" class="w-4 h-4"></i>',
				'type' => 'list',
				'class' => 'flex items-center gap-2'
			));
			?>
		</div>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
