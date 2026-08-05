<?php
/**
 * Post type adjustments.
 *
 * The three post types are registered by ACF. The export sets
 * `has_archive: false` on all of them, which leaves the theme with no listing
 * pages to hang navigation off. Rather than re-registering (which would
 * conflict with ACF), we filter the arguments on the way through and attach
 * the theme's taxonomies at the same time.
 *
 * If ACF is not active the post types would not exist at all, so a guarded
 * fallback registers them late — enough for the theme to stand up on its own.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Archive slug and taxonomy wiring for each post type.
 *
 * @return array<string, array{archive: string, taxonomies: string[], labels: array}>
 */
function webintelli_post_type_config() {
	return array(
		'coffee-shop'     => array(
			'archive'    => 'coffee-shops',
			'taxonomies' => array( 'coffee-region' ),
			'labels'     => array(
				'name'          => __( 'Coffee Shops', 'webintelli' ),
				'singular_name' => __( 'Coffee Shop', 'webintelli' ),
			),
			'menu_icon'  => 'dashicons-coffee',
		),
		'brewing-guide'   => array(
			'archive'    => 'brewing-guides',
			'taxonomies' => array( 'brew-method' ),
			'labels'     => array(
				'name'          => __( 'Brewing Guides', 'webintelli' ),
				'singular_name' => __( 'Brewing Guide', 'webintelli' ),
			),
			'menu_icon'  => 'dashicons-list-view',
		),
		'coffee-glossary' => array(
			'archive'    => 'coffee-glossary',
			'taxonomies' => array( 'brew-method' ),
			'labels'     => array(
				'name'          => __( 'Coffee Glossary', 'webintelli' ),
				'singular_name' => __( 'Glossary Term', 'webintelli' ),
			),
			'menu_icon'  => 'dashicons-book-alt',
		),
	);
}

/**
 * Enable archives and attach taxonomies to the ACF-registered post types.
 *
 * @param array  $args      Post type arguments.
 * @param string $post_type Post type key.
 * @return array
 */
function webintelli_filter_post_type_args( $args, $post_type ) {
	$config = webintelli_post_type_config();

	if ( ! isset( $config[ $post_type ] ) ) {
		return $args;
	}

	$args['has_archive'] = $config[ $post_type ]['archive'];
	$args['taxonomies']  = array_values(
		array_unique(
			array_merge(
				(array) ( $args['taxonomies'] ?? array() ),
				$config[ $post_type ]['taxonomies']
			)
		)
	);

	return $args;
}
add_filter( 'register_post_type_args', 'webintelli_filter_post_type_args', 10, 2 );

/**
 * Register the post types only if ACF has not already done so.
 *
 * Runs late (priority 20) so ACF's own registration at the default priority
 * always wins. This exists so the theme is previewable without the plugin.
 */
function webintelli_register_fallback_post_types() {
	foreach ( webintelli_post_type_config() as $post_type => $config ) {
		if ( post_type_exists( $post_type ) ) {
			continue;
		}

		register_post_type(
			$post_type,
			array(
				'labels'       => array(
					'name'          => $config['labels']['name'],
					'singular_name' => $config['labels']['singular_name'],
					'menu_name'     => $config['labels']['name'],
					'add_new_item'  => sprintf(
						/* translators: %s: post type singular name. */
						__( 'Add New %s', 'webintelli' ),
						$config['labels']['singular_name']
					),
					'edit_item'     => sprintf(
						/* translators: %s: post type singular name. */
						__( 'Edit %s', 'webintelli' ),
						$config['labels']['singular_name']
					),
					'search_items'  => sprintf(
						/* translators: %s: post type plural name. */
						__( 'Search %s', 'webintelli' ),
						$config['labels']['name']
					),
				),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => $config['menu_icon'],
				'has_archive'  => $config['archive'],
				'rewrite'      => array( 'slug' => $post_type ),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),
				'taxonomies'   => $config['taxonomies'],
			)
		);
	}
}
add_action( 'init', 'webintelli_register_fallback_post_types', 20 );

/**
 * Flush rewrite rules once when the theme is activated so archives resolve.
 */
function webintelli_flush_rewrites() {
	webintelli_register_taxonomies();
	webintelli_register_fallback_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'webintelli_flush_rewrites' );
