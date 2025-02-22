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
	<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
		<!-- Categories Filter -->
		<div class="flex flex-wrap justify-center gap-2 mb-8">
			<?php
			$categories = get_categories();
			foreach ($categories as $category) :
				$active_class = (get_query_var('cat') == $category->term_id) ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
			?>
				<a href="<?php echo get_category_link($category->term_id); ?>" 
				   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo $active_class; ?>">
					<?php echo esc_html($category->name); ?>
					<span class="ml-1 text-xs">(<?php echo $category->count; ?>)</span>
				</a>
			<?php endforeach; ?>
		</div>

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
			<article class="blog-card bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
				<a href="<?php the_permalink(); ?>" class="block">
					<div class="relative aspect-w-16 aspect-h-9">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('medium_large', array(
								'class' => 'transition-transform duration-300 group-hover:scale-105 object-cover w-full h-full',
								'alt' => get_the_title()
							)); ?>
						<?php endif; ?>
					</div>
				</a>
				<div class="p-6">
					<div class="flex items-center gap-2 text-sm text-muted-foreground mb-4">
						<time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
						<?php
						$categories = get_the_category();
						if ($categories) {
							echo '<span>•</span><span>' . esc_html($categories[0]->name) . '</span>';
						}
						?>
					</div>
					<h2 class="text-xl font-semibold mb-4">
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
			</article>
			<?php
				endwhile;
			endif;
			?>
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
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Initialize Lucide icons
	lucide.createIcons();
});
</script>

<?php get_footer(); ?>
