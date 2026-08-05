<?php
/**
 * Single blog post.
 *
 * @package WebIntelli
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article <?php post_class(); ?>>
		<header class="entry-header wi-wrap wi-wrap--text">
			<?php webintelli_term_pills( 'coffee-origin' ); ?>

			<h1><?php the_title(); ?></h1>

			<p class="entry-meta">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
				<span><?php echo esc_html( get_the_author() ); ?></span>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="wi-wrap">
				<div class="entry-featured">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="wi-wrap entry-content">
			<?php the_content(); ?>
		</div>

		<div class="wi-wrap wi-wrap--text">
			<?php the_tags( '<ul class="pill-row"><li class="pill">', '</li><li class="pill">', '</li></ul>' ); ?>
		</div>
	</article>

	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="wi-wrap wi-wrap--text">';
		comments_template();
		echo '</div>';
	}

endwhile;

get_footer();
