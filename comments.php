<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wades
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="text-2xl font-semibold mb-8">
			<?php
			$comment_count = get_comments_number();
			if ( $comment_count === '1' ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'wades' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'wades' ) ),
					number_format_i18n( $comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list space-y-8 mb-12">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'callback'   => 'wades_comment_callback',
					'avatar_size'=> 60
				)
			);
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comment-navigation flex justify-between items-center mb-8">
				<div class="nav-previous">
					<?php previous_comments_link( sprintf(
						'<span class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors"><i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i> %s</span>',
						__('Older Comments', 'wades')
					) ); ?>
				</div>
				<div class="nav-next">
					<?php next_comments_link( sprintf(
						'<span class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors">%s <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i></span>',
						__('Newer Comments', 'wades')
					) ); ?>
				</div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments text-muted-foreground text-center py-4"><?php esc_html_e( 'Comments are closed.', 'wades' ); ?></p>
		<?php endif; ?>

		<?php
	endif; // Check for have_comments().

	$commenter = wp_get_current_commenter();
	$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	
	comment_form(
		array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title text-2xl font-semibold mb-6">',
			'title_reply'        => __('Leave a Comment', 'wades'),
			'title_reply_after'  => '</h2>',
			'class_form'         => 'comment-form space-y-6',
			'class_submit'       => 'submit inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-medium bg-primary text-white hover:bg-primary/90 transition-colors',
			'comment_field'      => sprintf(
				'<p class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium mb-2">%s</label><textarea id="comment" name="comment" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" rows="8" maxlength="65525" required="required"></textarea></p>',
				_x('Comment', 'noun', 'wades')
			),
			'fields'            => array(
				'author' => sprintf(
					'<p class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium mb-2">%s%s</label><input id="author" name="author" type="text" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" value="%s" size="30" maxlength="245"%s /></p>',
					__('Name', 'wades'),
					($req ? ' <span class="required text-red-500">*</span>' : ''),
					esc_attr($commenter['comment_author']),
					($req ? ' required="required"' : '')
				),
				'email' => sprintf(
					'<p class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium mb-2">%s%s</label><input id="email" name="email" type="email" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s /></p>',
					__('Email', 'wades'),
					($req ? ' <span class="required text-red-500">*</span>' : ''),
					esc_attr($commenter['comment_author_email']),
					($req ? ' required="required"' : '')
				),
				'url' => sprintf(
					'<p class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium mb-2">%s</label><input id="url" name="url" type="url" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" value="%s" size="30" maxlength="200" /></p>',
					__('Website', 'wades'),
					esc_attr($commenter['comment_author_url'])
				),
				'cookies' => sprintf(
					'<p class="comment-form-cookies-consent mb-4"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 mr-2"%s /><label for="wp-comment-cookies-consent" class="text-sm text-muted-foreground">%s</label></p>',
					$consent,
					__('Save my name, email, and website in this browser for the next time I comment.', 'wades')
				),
			),
			'comment_notes_before' => sprintf(
				'<p class="comment-notes mb-4"><span id="email-notes" class="text-sm text-muted-foreground">%s</span>%s</p>',
				__('Your email address will not be published.', 'wades'),
				($req ? sprintf(' <span class="required-field-message text-sm text-muted-foreground">%s</span>', __('Required fields are marked *', 'wades')) : '')
			),
		)
	);
	?>

</div><!-- #comments -->
