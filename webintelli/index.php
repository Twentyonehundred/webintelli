<?php
/**
 * Main template file.
 *
 * @package WebIntelli
 */

get_header();
?>

<main class="site-main">
	<?php if ( have_posts() ) : ?>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h2>

				<?php if ( 'post' === get_post_type() ) : ?>
					<p class="entry-meta">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</p>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_excerpt(); ?>
				</div>
			</article>
			<?php
		endwhile;

		the_posts_pagination();
		?>

	<?php else : ?>

		<p><?php esc_html_e( 'Nothing has been published yet.', 'webintelli' ); ?></p>

	<?php endif; ?>
</main>

<?php
get_footer();
