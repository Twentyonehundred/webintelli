<?php
/**
 * Homepage.
 *
 * @package WebIntelli
 */

get_header();

$shop_count    = wp_count_posts( 'coffee-shop' )->publish ?? 0;
$guide_count   = wp_count_posts( 'brewing-guide' )->publish ?? 0;
$term_count    = wp_count_posts( 'coffee-glossary' )->publish ?? 0;
$guide_archive = get_post_type_archive_link( 'brewing-guide' );
$shop_archive  = get_post_type_archive_link( 'coffee-shop' );
?>

<section class="hero">
	<div class="wi-wrap hero__inner">
		<span class="eyebrow"><?php esc_html_e( 'Coffee, explained properly', 'webintelli' ); ?></span>

		<h1><?php esc_html_e( 'Better coffee starts with better answers.', 'webintelli' ); ?></h1>

		<p class="hero__lede">
			<?php esc_html_e( 'Straightforward brewing guides, honest coffee shop write-ups, and a glossary that skips the jargon. No gatekeeping, no scoring out of a hundred.', 'webintelli' ); ?>
		</p>

		<div class="hero__actions">
			<?php if ( $guide_archive ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( $guide_archive ); ?>">
					<?php esc_html_e( 'Start brewing', 'webintelli' ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $shop_archive ) : ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( $shop_archive ); ?>">
					<?php esc_html_e( 'Find a coffee shop', 'webintelli' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="stat-row">
			<div class="stat">
				<span class="stat__value"><?php echo esc_html( number_format_i18n( $guide_count ) ); ?></span>
				<span class="stat__label"><?php esc_html_e( 'Brewing guides', 'webintelli' ); ?></span>
			</div>
			<div class="stat">
				<span class="stat__value"><?php echo esc_html( number_format_i18n( $shop_count ) ); ?></span>
				<span class="stat__label"><?php esc_html_e( 'Coffee shops', 'webintelli' ); ?></span>
			</div>
			<div class="stat">
				<span class="stat__value"><?php echo esc_html( number_format_i18n( $term_count ) ); ?></span>
				<span class="stat__label"><?php esc_html_e( 'Glossary terms', 'webintelli' ); ?></span>
			</div>
		</div>
	</div>
</section>

<?php
webintelli_post_type_section(
	'brewing-guide',
	__( 'Brewing guides', 'webintelli' ),
	__( 'Step-by-step methods with the kit list, timings and the one thing most people get wrong.', 'webintelli' )
);

webintelli_post_type_section(
	'coffee-shop',
	__( 'Coffee shops worth the walk', 'webintelli' ),
	__( 'Where to go, what to order, and whether you can actually get a seat with a laptop.', 'webintelli' )
);
?>

<section class="wi-section wi-section--tint">
	<div class="wi-wrap">
		<div class="section-head">
			<div>
				<h2><?php esc_html_e( 'Speak the language', 'webintelli' ); ?></h2>
				<p><?php esc_html_e( 'Every term you will hear at the counter, defined in a sentence.', 'webintelli' ); ?></p>
			</div>
			<?php
			$glossary_archive = get_post_type_archive_link( 'coffee-glossary' );
			if ( $glossary_archive ) :
				?>
				<a class="link-more" href="<?php echo esc_url( $glossary_archive ); ?>">
					<?php esc_html_e( 'Full glossary', 'webintelli' ); ?> &rarr;
				</a>
			<?php endif; ?>
		</div>

		<?php
		$glossary = new WP_Query(
			array(
				'post_type'           => 'coffee-glossary',
				'posts_per_page'      => 6,
				'orderby'             => 'title',
				'order'               => 'ASC',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( $glossary->have_posts() ) :
			?>
			<div class="card-grid">
				<?php
				while ( $glossary->have_posts() ) {
					$glossary->the_post();
					get_template_part( 'template-parts/card', 'coffee-glossary' );
				}
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Glossary terms will appear here once they are published.', 'webintelli' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
webintelli_post_type_section(
	'post',
	__( 'From the journal', 'webintelli' ),
	__( 'Longer reads on origins, roasting and the things worth arguing about.', 'webintelli' )
);

get_footer();
