<?php
/**
 * Single page.
 *
 * @package WebIntelli
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article <?php post_class(); ?>>
		<header class="entry-header wi-wrap wi-wrap--text">
			<h1><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="wi-wrap">
				<div class="entry-featured">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="wi-wrap entry-content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
