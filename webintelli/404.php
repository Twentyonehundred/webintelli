<?php
/**
 * 404.
 *
 * @package WebIntelli
 */

get_header();
?>

<section class="wi-section wi-wrap wi-wrap--text">
	<span class="eyebrow"><?php esc_html_e( 'Error 404', 'webintelli' ); ?></span>

	<h1><?php esc_html_e( 'That page has gone cold.', 'webintelli' ); ?></h1>

	<p class="lede">
		<?php esc_html_e( 'The page you were after does not exist. Try a search, or pick up one of the guides below.', 'webintelli' ); ?>
	</p>

	<?php get_search_form(); ?>

	<ul class="pill-row" style="margin-top:2rem">
		<?php foreach ( array( 'brewing-guide', 'coffee-shop', 'coffee-glossary' ) as $webintelli_type ) : ?>
			<?php
			$webintelli_archive = get_post_type_archive_link( $webintelli_type );
			$webintelli_object  = get_post_type_object( $webintelli_type );

			if ( ! $webintelli_archive || ! $webintelli_object ) {
				continue;
			}
			?>
			<li>
				<a class="pill" href="<?php echo esc_url( $webintelli_archive ); ?>">
					<?php echo esc_html( $webintelli_object->labels->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php
get_footer();
