<?php
/**
 * Single brewing guide.
 *
 * @package WebIntelli
 */

get_header();

while ( have_posts() ) :
	the_post();

	$equipment = webintelli_field( 'equipment_needed' );
	$servings  = webintelli_field( 'servings' );
	?>

	<article <?php post_class(); ?>>
		<header class="entry-header wi-wrap">
			<?php webintelli_term_pills( 'brew-method' ); ?>

			<h1><?php the_title(); ?></h1>

			<p class="entry-meta">
				<?php if ( webintelli_field( 'difficulty' ) ) : ?>
					<span><?php echo esc_html( webintelli_field( 'difficulty' ) ); ?></span>
				<?php endif; ?>
				<?php if ( webintelli_field( 'prep_time' ) ) : ?>
					<span><?php echo esc_html( webintelli_field( 'prep_time' ) ); ?></span>
				<?php endif; ?>
			</p>
		</header>

		<div class="wi-wrap">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-featured">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>

			<?php webintelli_answer_box( __( 'The short version', 'webintelli' ) ); ?>

			<?php
			webintelli_spec_list(
				array(
					__( 'Difficulty', 'webintelli' ) => webintelli_field( 'difficulty' ),
					__( 'Prep time', 'webintelli' )  => webintelli_field( 'prep_time' ),
					__( 'Servings', 'webintelli' )   => $servings ? number_format_i18n( (int) $servings ) : null,
				)
			);
			?>
		</div>

		<?php if ( $equipment ) : ?>
			<div class="wi-wrap wi-wrap--text">
				<h2><?php esc_html_e( 'What you need', 'webintelli' ); ?></h2>
				<ul>
					<?php
					$items = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $equipment ) ), 'strlen' );

					foreach ( $items as $item ) {
						printf( '<li>%s</li>', esc_html( $item ) );
					}
					?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="wi-wrap entry-content">
			<?php the_content(); ?>
		</div>

		<div class="wi-wrap">
			<?php webintelli_faq_section(); ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
