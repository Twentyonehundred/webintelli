<?php
/**
 * Brewing guide card.
 *
 * @package WebIntelli
 */

$difficulty = webintelli_field( 'difficulty' );
$prep_time  = webintelli_field( 'prep_time' );
$servings   = webintelli_field( 'servings' );
$takeaway   = webintelli_field( 'key_takeaway' );
?>
<article <?php post_class( 'card' ); ?>>
	<?php webintelli_card_media(); ?>

	<div class="card__body">
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $takeaway ) : ?>
			<p class="card__excerpt"><?php echo esc_html( wp_trim_words( $takeaway, 22 ) ); ?></p>
		<?php elseif ( has_excerpt() ) : ?>
			<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<p class="card__meta">
			<?php if ( $difficulty ) : ?>
				<span><?php echo esc_html( $difficulty ); ?></span>
			<?php endif; ?>

			<?php if ( $prep_time ) : ?>
				<span><?php echo esc_html( $prep_time ); ?></span>
			<?php endif; ?>

			<?php if ( $servings ) : ?>
				<span>
					<?php
					printf(
						/* translators: %s: number of servings. */
						esc_html( _n( '%s serving', '%s servings', (int) $servings, 'webintelli' ) ),
						esc_html( number_format_i18n( (int) $servings ) )
					);
					?>
				</span>
			<?php endif; ?>
		</p>
	</div>
</article>
