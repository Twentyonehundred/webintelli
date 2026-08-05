<?php
/**
 * ACF integration.
 *
 * The exported field groups bind "Brewing Guide Details" and "Glossary Term
 * Details" to the built-in `post` type rather than to `brewing-guide` and
 * `coffee-glossary`. That looks like an authoring slip in the export: the
 * groups are named for the custom post types and their fields only make sense
 * there. We repoint them at load time so the theme works with the export as it
 * stands, without anyone having to re-save the groups in wp-admin.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field group key => post type the group should actually be attached to.
 *
 * @return array<string, string>
 */
function webintelli_field_group_targets() {
	return array(
		'group_6a4fb33e1abfe' => 'brewing-guide',  // Brewing Guide Details.
		'group_6a4fb53562911' => 'coffee-glossary', // Glossary Term Details.
	);
}

/**
 * Repoint mislocated field groups at the correct post type.
 *
 * @param array $group The ACF field group settings.
 * @return array
 */
function webintelli_fix_field_group_location( $group ) {
	$targets = webintelli_field_group_targets();
	$key     = $group['key'] ?? '';

	if ( ! isset( $targets[ $key ] ) ) {
		return $group;
	}

	$group['location'] = array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $targets[ $key ],
			),
		),
	);

	return $group;
}
add_filter( 'acf/load_field_group', 'webintelli_fix_field_group_location' );

/**
 * Read a field with a graceful fallback when ACF is not active.
 *
 * @param string   $name    Field name.
 * @param int|null $post_id Optional post ID.
 * @return mixed
 */
function webintelli_field( $name, $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		return get_field( $name, $post_id );
	}

	$value = get_post_meta( $post_id, $name, true );

	return '' === $value ? null : $value;
}

/**
 * Read the FAQ repeater as a plain array of question/answer pairs.
 *
 * Falls back to raw meta so the template still renders if ACF is deactivated.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array{question: string, answer: string}>
 */
function webintelli_faq_rows( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$rows    = array();

	if ( function_exists( 'get_field' ) ) {
		$field = get_field( 'faq', $post_id );

		if ( is_array( $field ) ) {
			foreach ( $field as $row ) {
				$rows[] = array(
					'question' => (string) ( $row['question'] ?? '' ),
					'answer'   => (string) ( $row['answer'] ?? '' ),
				);
			}
		}

		return array_values( array_filter( $rows, 'webintelli_faq_row_is_complete' ) );
	}

	$count = (int) get_post_meta( $post_id, 'faq', true );

	for ( $i = 0; $i < $count; $i++ ) {
		$rows[] = array(
			'question' => (string) get_post_meta( $post_id, "faq_{$i}_question", true ),
			'answer'   => (string) get_post_meta( $post_id, "faq_{$i}_answer", true ),
		);
	}

	return array_values( array_filter( $rows, 'webintelli_faq_row_is_complete' ) );
}

/**
 * A FAQ row is only useful when both halves are filled in.
 *
 * @param array $row Question/answer pair.
 * @return bool
 */
function webintelli_faq_row_is_complete( $row ) {
	return '' !== trim( $row['question'] ) && '' !== trim( $row['answer'] );
}

/**
 * Split the comma-separated "Related Terms" field into a clean list.
 *
 * @param int|null $post_id Optional post ID.
 * @return string[]
 */
function webintelli_related_terms( $post_id = null ) {
	$raw = (string) webintelli_field( 'related_terms', $post_id );

	if ( '' === trim( $raw ) ) {
		return array();
	}

	$terms = array_map( 'trim', explode( ',', $raw ) );

	return array_values( array_filter( $terms, 'strlen' ) );
}
