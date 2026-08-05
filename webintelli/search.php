<?php
/**
 * Search results.
 *
 * @package WebIntelli
 */

get_header();
?>

<header class="entry-header wi-wrap">
	<span class="eyebrow"><?php esc_html_e( 'Search', 'webintelli' ); ?></span>

	<h1>
		<?php
		printf(
			/* translators: %s: search query. */
			esc_html__( 'Results for “%s”', 'webintelli' ),
			esc_html( get_search_query() )
		);
		?>
	</h1>

	<p class="lede">
		<?php
		global $wp_query;

		printf(
			/* translators: %s: number of results. */
			esc_html( _n( '%s result', '%s results', (int) $wp_query->found_posts, 'webintelli' ) ),
			esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
		);
		?>
	</p>

	<?php get_search_form(); ?>
</header>

<div class="wi-wrap">
	<?php if ( have_posts() ) : ?>
		<div class="card-grid">
			<?php
			while ( have_posts() ) {
				the_post();
				webintelli_card();
			}
			?>
		</div>

		<?php webintelli_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No matches. Try a broader term — a brew method, a neighbourhood, or a drink name.', 'webintelli' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
