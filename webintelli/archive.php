<?php
/**
 * Generic archive.
 *
 * Also serves the coffee shop and brewing guide post type archives, which need
 * nothing beyond the standard heading, card grid and pagination.
 *
 * @package WebIntelli
 */

get_header();
?>

<header class="entry-header wi-wrap">
	<h1><?php the_archive_title(); ?></h1>
	<?php
	$description = get_the_archive_description();
	if ( $description ) :
		?>
		<div class="lede"><?php echo wp_kses_post( $description ); ?></div>
	<?php endif; ?>
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
		<p><?php esc_html_e( 'Nothing has been published here yet.', 'webintelli' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
