<?php
/**
 * Single glossary term.
 *
 * @package WebIntelli
 */

get_header();

while ( have_posts() ) :
	the_post();

	$term       = webintelli_field( 'term' ) ?: get_the_title();
	$definition = webintelli_field( 'short_definition' );
	$example    = webintelli_field( 'example_usage' );
	$category   = webintelli_field( 'category' );
	$related    = webintelli_related_terms();
	?>

	<article <?php post_class(); ?>>
		<header class="entry-header wi-wrap wi-wrap--text">
			<?php if ( $category ) : ?>
				<span class="eyebrow"><?php echo esc_html( $category ); ?></span>
			<?php endif; ?>

			<h1><?php echo esc_html( $term ); ?></h1>
		</header>

		<div class="wi-wrap wi-wrap--text">
			<?php if ( $definition ) : ?>
				<div class="answer-box">
					<span class="answer-box__label"><?php esc_html_e( 'Definition', 'webintelli' ); ?></span>
					<p><?php echo esc_html( $definition ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $example ) : ?>
				<blockquote>
					<?php echo esc_html( $example ); ?>
				</blockquote>
			<?php endif; ?>
		</div>

		<div class="wi-wrap entry-content">
			<?php the_content(); ?>
		</div>

		<div class="wi-wrap wi-wrap--text">
			<?php webintelli_term_pills( 'brew-method' ); ?>

			<?php if ( $related ) : ?>
				<h2><?php esc_html_e( 'Related terms', 'webintelli' ); ?></h2>
				<ul class="pill-row">
					<?php
					foreach ( $related as $related_term ) :
						$match = get_page_by_path( sanitize_title( $related_term ), OBJECT, 'coffee-glossary' );
						?>
						<li>
							<?php if ( $match ) : ?>
								<a class="pill" href="<?php echo esc_url( get_permalink( $match ) ); ?>">
									<?php echo esc_html( $related_term ); ?>
								</a>
							<?php else : ?>
								<span class="pill"><?php echo esc_html( $related_term ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php
			$glossary_archive = get_post_type_archive_link( 'coffee-glossary' );
			if ( $glossary_archive ) :
				?>
				<p>
					<a class="link-more" href="<?php echo esc_url( $glossary_archive ); ?>">
						&larr; <?php esc_html_e( 'Back to the glossary', 'webintelli' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
