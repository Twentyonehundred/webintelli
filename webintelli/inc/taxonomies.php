<?php
/**
 * Taxonomies.
 *
 * The ACF export contains no taxonomies — every post type has an empty
 * `taxonomies` value — so these are defined here to give the site the
 * taxonomy archive pages the design calls for.
 *
 * - Region      : where a coffee shop is, hierarchical (City > Neighbourhood).
 * - Brew Method : shared by brewing guides and glossary terms, so a method
 *                 archive collects the how-to and the definitions together.
 * - Origin      : bean-growing origin, used on guides and blog posts.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme's taxonomies.
 *
 * Priority 9 so the terms exist before ACF registers its post types at the
 * default priority and picks them up via `register_post_type_args`.
 */
function webintelli_register_taxonomies() {
	register_taxonomy(
		'coffee-region',
		array( 'coffee-shop' ),
		array(
			'labels'            => webintelli_taxonomy_labels(
				__( 'Regions', 'webintelli' ),
				__( 'Region', 'webintelli' )
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'region' ),
		)
	);

	register_taxonomy(
		'brew-method',
		array( 'brewing-guide', 'coffee-glossary' ),
		array(
			'labels'            => webintelli_taxonomy_labels(
				__( 'Brew Methods', 'webintelli' ),
				__( 'Brew Method', 'webintelli' )
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'method' ),
		)
	);

	register_taxonomy(
		'coffee-origin',
		array( 'brewing-guide', 'post' ),
		array(
			'labels'            => webintelli_taxonomy_labels(
				__( 'Origins', 'webintelli' ),
				__( 'Origin', 'webintelli' )
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'origin' ),
		)
	);
}
add_action( 'init', 'webintelli_register_taxonomies', 9 );

/**
 * Build a standard label set for a taxonomy.
 *
 * @param string $plural   Plural label.
 * @param string $singular Singular label.
 * @return array<string, string>
 */
function webintelli_taxonomy_labels( $plural, $singular ) {
	return array(
		'name'              => $plural,
		'singular_name'     => $singular,
		'menu_name'         => $plural,
		'all_items'         => sprintf(
			/* translators: %s: taxonomy plural name. */
			__( 'All %s', 'webintelli' ),
			$plural
		),
		'edit_item'         => sprintf(
			/* translators: %s: taxonomy singular name. */
			__( 'Edit %s', 'webintelli' ),
			$singular
		),
		'add_new_item'      => sprintf(
			/* translators: %s: taxonomy singular name. */
			__( 'Add New %s', 'webintelli' ),
			$singular
		),
		'search_items'      => sprintf(
			/* translators: %s: taxonomy plural name. */
			__( 'Search %s', 'webintelli' ),
			$plural
		),
		'not_found'         => sprintf(
			/* translators: %s: taxonomy plural name, lowercase. */
			__( 'No %s found', 'webintelli' ),
			strtolower( $plural )
		),
		'parent_item'       => sprintf(
			/* translators: %s: taxonomy singular name. */
			__( 'Parent %s', 'webintelli' ),
			$singular
		),
		'parent_item_colon' => sprintf(
			/* translators: %s: taxonomy singular name. */
			__( 'Parent %s:', 'webintelli' ),
			$singular
		),
	);
}
