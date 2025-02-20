<?php
/**
 * Template Name: Default Page Template
 * 
 * @package wades
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('prose max-w-none'); ?>>
                    <header class="entry-header mb-8">
                        <?php the_title('<h1 class="entry-title text-4xl font-bold">', '</h1>'); ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'wades'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                </article>
                <?php
            endwhile;
        endif;
        ?>
    </div>
</main>

<?php
get_footer(); 