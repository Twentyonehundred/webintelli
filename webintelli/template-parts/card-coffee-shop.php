<?php
/**
 * Coffee shop card.
 *
 * @package WebIntelli
 */

$neighborhood = webintelli_field( 'neighborhood' );
$price        = webintelli_field( 'price_range' );
$signature    = webintelli_field( 'signature_drink' );
$summary      = webintelli_field( 'key_facts_summary' );
?>
<article <?php post_class( 'card' ); ?>>
	<?php webintelli_card_media(); ?>

	<div class="card__body">
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $summary ) : ?>
			<p class="card__excerpt"><?php echo esc_html( wp_trim_words( $summary, 22 ) ); ?></p>
		<?php elseif ( has_excerpt() ) : ?>
			<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php if ( $signature ) : ?>
			<p class="card__excerpt">
				<strong><?php esc_html_e( 'Order the', 'webintelli' ); ?></strong>
				<?php echo esc_html( $signature ); ?>
			</p>
		<?php endif; ?>

		<p class="card__meta">
			<?php if ( $neighborhood ) : ?>
				<span><?php echo esc_html( $neighborhood ); ?></span>
			<?php endif; ?>

			<?php if ( $price ) : ?>
				<span><?php echo esc_html( $price ); ?></span>
			<?php endif; ?>

			<?php if ( webintelli_field( 'has_wifi' ) ) : ?>
				<span><?php esc_html_e( 'Wi-Fi', 'webintelli' ); ?></span>
			<?php endif; ?>
		</p>
	</div>
</article>
