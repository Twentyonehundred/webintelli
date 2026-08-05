<?php
/**
 * Glossary term card.
 *
 * @package WebIntelli
 */

$definition = webintelli_field( 'short_definition' );
$category   = webintelli_field( 'category' );
?>
<article <?php post_class( 'card' ); ?>>
	<div class="card__body">
		<?php if ( $category ) : ?>
			<span class="pill"><?php echo esc_html( $category ); ?></span>
		<?php endif; ?>

		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>">
				<?php echo esc_html( webintelli_field( 'term' ) ?: get_the_title() ); ?>
			</a>
		</h3>

		<?php if ( $definition ) : ?>
			<p class="card__excerpt"><?php echo esc_html( wp_trim_words( $definition, 26 ) ); ?></p>
		<?php endif; ?>
	</div>
</article>
