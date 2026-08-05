<?php
/**
 * Taxonomy archive for regions, brew methods and origins.
 *
 * @package WebIntelli
 */

get_header();

$term     = get_queried_object();
$taxonomy = $term instanceof WP_Term ? get_taxonomy( $term->taxonomy ) : null;
$children = $term instanceof WP_Term && is_taxonomy_hierarchical( $term->taxonomy )
	? get_terms(
		array(
			'taxonomy'   => $term->taxonomy,
			'parent'     => $term->term_id,
			'hide_empty' => true,
		)
	)
	: array();
?>

<header class="entry-header wi-wrap">
	<?php if ( $taxonomy ) : ?>
		<span class="eyebrow"><?php echo esc_html( $taxonomy->labels->singular_name ); ?></span>
	<?php endif; ?>

	<h1><?php echo esc_html( single_term_title( '', false ) ); ?></h1>

	<?php
	$description = term_description();
	if ( $description ) :
		?>
		<div class="lede"><?php echo wp_kses_post( $description ); ?></div>
	<?php endif; ?>

	<?php if ( $children && ! is_wp_error( $children ) ) : ?>
		<ul class="pill-row">
			<?php foreach ( $children as $child ) : ?>
				<li>
					<a class="pill" href="<?php echo esc_url( get_term_link( $child ) ); ?>">
						<?php echo esc_html( $child->name ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
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
		<p><?php esc_html_e( 'Nothing has been filed under this term yet.', 'webintelli' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
