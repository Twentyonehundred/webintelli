<?php
/**
 * Presentational helpers shared across templates.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the AEO answer box for a post, if it has one.
 *
 * Brewing guides use `key_takeaway`; coffee shops use `key_facts_summary`.
 *
 * @param string $label Heading shown above the answer.
 */
function webintelli_answer_box( $label = '' ) {
	$answer = webintelli_field( 'key_takeaway' ) ?: webintelli_field( 'key_facts_summary' );

	if ( ! $answer ) {
		return;
	}

	$label = $label ?: __( 'Quick answer', 'webintelli' );
	?>
	<div class="answer-box">
		<span class="answer-box__label"><?php echo esc_html( $label ); ?></span>
		<p><?php echo esc_html( $answer ); ?></p>
	</div>
	<?php
}

/**
 * Output a definition list of label/value pairs, skipping empty values.
 *
 * @param array<string, mixed> $specs Label => value.
 */
function webintelli_spec_list( array $specs ) {
	$specs = array_filter(
		$specs,
		static function ( $value ) {
			return null !== $value && '' !== $value && array() !== $value;
		}
	);

	if ( ! $specs ) {
		return;
	}
	?>
	<dl class="spec-list">
		<?php foreach ( $specs as $label => $value ) : ?>
			<div>
				<dt><?php echo esc_html( $label ); ?></dt>
				<dd><?php echo esc_html( (string) $value ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
	<?php
}

/**
 * Output the FAQ accordion for the current post.
 */
function webintelli_faq_section() {
	$rows = webintelli_faq_rows();

	if ( ! $rows ) {
		return;
	}
	?>
	<section class="wi-section faq" aria-labelledby="faq-heading">
		<h2 id="faq-heading"><?php esc_html_e( 'Frequently asked questions', 'webintelli' ); ?></h2>

		<?php foreach ( $rows as $index => $row ) : ?>
			<details class="faq__item" <?php echo 0 === $index ? 'open' : ''; ?>>
				<summary><?php echo esc_html( $row['question'] ); ?></summary>
				<p class="faq__answer"><?php echo nl2br( esc_html( $row['answer'] ) ); ?></p>
			</details>
		<?php endforeach; ?>
	</section>
	<?php
}

/**
 * Output linked term pills for a taxonomy.
 *
 * @param string   $taxonomy Taxonomy key.
 * @param int|null $post_id  Optional post ID.
 */
function webintelli_term_pills( $taxonomy, $post_id = null ) {
	$terms = get_the_terms( $post_id ?: get_the_ID(), $taxonomy );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}
	?>
	<ul class="pill-row">
		<?php foreach ( $terms as $term ) : ?>
			<li>
				<a class="pill" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<?php echo esc_html( $term->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Render a card for a post, choosing the partial that matches its type.
 */
function webintelli_card() {
	$type = get_post_type();
	$slug = in_array( $type, array( 'coffee-shop', 'brewing-guide', 'coffee-glossary' ), true )
		? $type
		: 'post';

	get_template_part( 'template-parts/card', $slug );
}

/**
 * Output the card thumbnail, or the gradient placeholder when there is none.
 */
function webintelli_card_media() {
	if ( ! has_post_thumbnail() ) {
		echo '<div class="card__media" aria-hidden="true"></div>';
		return;
	}
	?>
	<div class="card__media">
		<?php the_post_thumbnail( 'webintelli-card', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
	</div>
	<?php
}

/**
 * Output numbered pagination for the current query.
 */
function webintelli_pagination() {
	the_posts_pagination(
		array(
			'class'              => 'pagination',
			'mid_size'           => 1,
			'prev_text'          => __( 'Previous', 'webintelli' ),
			'next_text'          => __( 'Next', 'webintelli' ),
			'screen_reader_text' => __( 'Page navigation', 'webintelli' ),
		)
	);
}

/**
 * Output a section listing the most recent posts of a given type.
 *
 * @param string $post_type Post type key.
 * @param string $heading   Section heading.
 * @param string $intro     Supporting sentence.
 * @param int    $count     Number of posts to show.
 */
function webintelli_post_type_section( $post_type, $heading, $intro, $count = 3 ) {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'posts_per_page'         => $count,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		)
	);

	if ( ! $query->have_posts() ) {
		return;
	}

	$archive = get_post_type_archive_link( $post_type );
	?>
	<section class="wi-section">
		<div class="wi-wrap">
			<div class="section-head">
				<div>
					<h2><?php echo esc_html( $heading ); ?></h2>
					<p><?php echo esc_html( $intro ); ?></p>
				</div>
				<?php if ( $archive ) : ?>
					<a class="link-more" href="<?php echo esc_url( $archive ); ?>">
						<?php esc_html_e( 'View all', 'webintelli' ); ?> &rarr;
					</a>
				<?php endif; ?>
			</div>

			<div class="card-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					webintelli_card();
				}
				?>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
