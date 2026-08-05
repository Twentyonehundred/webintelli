<?php
/**
 * Glossary archive, grouped A–Z.
 *
 * The main query is set to return every term (see webintelli_archive_query)
 * so the alphabet index is always complete.
 *
 * @package WebIntelli
 */

get_header();

$groups = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		$label  = webintelli_field( 'term' ) ?: get_the_title();
		$letter = strtoupper( mb_substr( remove_accents( $label ), 0, 1 ) );

		if ( ! preg_match( '/[A-Z]/', $letter ) ) {
			$letter = '#';
		}

		$groups[ $letter ][] = array(
			'label'      => $label,
			'permalink'  => get_permalink(),
			'definition' => webintelli_field( 'short_definition' ),
			'category'   => webintelli_field( 'category' ),
		);
	}

	ksort( $groups );
}
?>

<header class="entry-header wi-wrap">
	<span class="eyebrow"><?php esc_html_e( 'A–Z', 'webintelli' ); ?></span>
	<h1><?php esc_html_e( 'Coffee Glossary', 'webintelli' ); ?></h1>
	<p class="lede">
		<?php esc_html_e( 'Every term you are likely to meet on a menu or a bag of beans, in one sentence each.', 'webintelli' ); ?>
	</p>
</header>

<div class="wi-wrap">
	<?php if ( $groups ) : ?>
		<ul class="glossary-index">
			<?php foreach ( array_keys( $groups ) as $letter ) : ?>
				<li>
					<a class="pill" href="#letter-<?php echo esc_attr( sanitize_title( $letter ) ); ?>">
						<?php echo esc_html( $letter ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php foreach ( $groups as $letter => $entries ) : ?>
			<section class="glossary-group" id="letter-<?php echo esc_attr( sanitize_title( $letter ) ); ?>">
				<h2 class="glossary-group__letter"><?php echo esc_html( $letter ); ?></h2>

				<div class="card-grid">
					<?php foreach ( $entries as $entry ) : ?>
						<article class="card">
							<div class="card__body">
								<?php if ( $entry['category'] ) : ?>
									<span class="pill"><?php echo esc_html( $entry['category'] ); ?></span>
								<?php endif; ?>

								<h3 class="card__title">
									<a href="<?php echo esc_url( $entry['permalink'] ); ?>">
										<?php echo esc_html( $entry['label'] ); ?>
									</a>
								</h3>

								<?php if ( $entry['definition'] ) : ?>
									<p class="card__excerpt">
										<?php echo esc_html( wp_trim_words( $entry['definition'], 26 ) ); ?>
									</p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No glossary terms have been published yet.', 'webintelli' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
