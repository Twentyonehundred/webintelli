<?php
/**
 * Comments.
 *
 * @package WebIntelli
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2>
			<?php
			printf(
				/* translators: %s: number of comments. */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'webintelli' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 44,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'class'     => 'pagination',
				'prev_text' => __( 'Previous', 'webintelli' ),
				'next_text' => __( 'Next', 'webintelli' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p><?php esc_html_e( 'Comments are closed.', 'webintelli' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit' => 'btn btn--primary',
			'title_reply'  => __( 'Leave a comment', 'webintelli' ),
		)
	);
	?>
</section>
