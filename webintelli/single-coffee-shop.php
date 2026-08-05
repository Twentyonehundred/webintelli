<?php
/**
 * Single coffee shop.
 *
 * @package WebIntelli
 */

get_header();

while ( have_posts() ) :
	the_post();

	$neighborhood = webintelli_field( 'neighborhood' );
	$address      = webintelli_field( 'address' );
	$hours        = webintelli_field( 'opening_hours' );
	?>

	<article <?php post_class(); ?>>
		<header class="entry-header wi-wrap">
			<?php webintelli_term_pills( 'coffee-region' ); ?>

			<h1><?php the_title(); ?></h1>

			<p class="entry-meta">
				<?php if ( $neighborhood ) : ?>
					<span><?php echo esc_html( $neighborhood ); ?></span>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<span><?php echo esc_html( $address ); ?></span>
				<?php endif; ?>
			</p>
		</header>

		<div class="wi-wrap">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-featured">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>

			<?php webintelli_answer_box( __( 'Known for', 'webintelli' ) ); ?>

			<?php
			webintelli_spec_list(
				array(
					__( 'Neighbourhood', 'webintelli' )  => $neighborhood,
					__( 'Price range', 'webintelli' )    => webintelli_field( 'price_range' ),
					__( 'Signature drink', 'webintelli' ) => webintelli_field( 'signature_drink' ),
					__( 'Wi-Fi', 'webintelli' )          => webintelli_field( 'has_wifi' )
						? __( 'Yes', 'webintelli' )
						: __( 'No', 'webintelli' ),
				)
			);
			?>
		</div>

		<div class="wi-wrap entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( $hours ) : ?>
			<div class="wi-wrap wi-wrap--text">
				<h2><?php esc_html_e( 'Opening hours', 'webintelli' ); ?></h2>
				<p><?php echo nl2br( esc_html( $hours ) ); ?></p>
			</div>
		<?php endif; ?>

		<div class="wi-wrap">
			<?php webintelli_faq_section(); ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
