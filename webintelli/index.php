<?php
/**
 * Main template file and blog index.
 *
 * @package WebIntelli
 */

get_header();
?>

<header class="entry-header wi-wrap">
	<h1>
		<?php
		if ( is_home() && get_option( 'page_for_posts' ) ) {
			echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
		} else {
			esc_html_e( 'Journal', 'webintelli' );
		}
		?>
	</h1>
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
		<p><?php esc_html_e( 'Nothing has been published yet.', 'webintelli' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
