<?php
/**
 * Default card for posts and pages.
 *
 * @package WebIntelli
 */

?>
<article <?php post_class( 'card' ); ?>>
	<?php webintelli_card_media(); ?>

	<div class="card__body">
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>

		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="card__meta">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
			</p>
		<?php endif; ?>
	</div>
</article>
