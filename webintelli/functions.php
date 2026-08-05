<?php
/**
 * WebIntelli theme setup.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WEBINTELLI_VERSION', '1.0.0' );
define( 'WEBINTELLI_DIR', get_template_directory() );

require_once WEBINTELLI_DIR . '/inc/acf.php';
require_once WEBINTELLI_DIR . '/inc/post-types.php';
require_once WEBINTELLI_DIR . '/inc/taxonomies.php';
require_once WEBINTELLI_DIR . '/inc/template-tags.php';
require_once WEBINTELLI_DIR . '/inc/schema.php';

/**
 * Register theme supports and navigation menus.
 */
function webintelli_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'webintelli' ),
			'footer'  => __( 'Footer Menu', 'webintelli' ),
		)
	);

	add_image_size( 'webintelli-card', 720, 450, true );
}
add_action( 'after_setup_theme', 'webintelli_setup' );

/**
 * Enqueue front-end assets.
 */
function webintelli_enqueue_assets() {
	wp_enqueue_style(
		'webintelli-style',
		get_stylesheet_uri(),
		array(),
		WEBINTELLI_VERSION
	);

	wp_enqueue_script(
		'webintelli-nav',
		get_theme_file_uri( 'assets/js/navigation.js' ),
		array(),
		WEBINTELLI_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'webintelli_enqueue_assets' );

/**
 * Match the editor canvas to the front end.
 */
function webintelli_editor_assets() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'webintelli_editor_assets' );

/**
 * Show all glossary terms on one page so the A–Z index is complete.
 *
 * @param WP_Query $query The query being run.
 */
function webintelli_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'coffee-glossary' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}

	if ( $query->is_post_type_archive( array( 'coffee-shop', 'brewing-guide' ) ) ) {
		$query->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'webintelli_archive_query' );

/**
 * Include the custom post types in site search results.
 *
 * @param WP_Query $query The query being run.
 */
function webintelli_search_all_types( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$query->set( 'post_type', array( 'post', 'page', 'coffee-shop', 'brewing-guide', 'coffee-glossary' ) );
}
add_action( 'pre_get_posts', 'webintelli_search_all_types' );

/**
 * Drop the "Archives:" / "Category:" prefixes from archive headings.
 *
 * @param string $title Archive title.
 * @return string
 */
function webintelli_archive_title( $title ) {
	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}

	if ( is_tax() || is_category() || is_tag() ) {
		return single_term_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'webintelli_archive_title' );

/**
 * Provide a primary-menu fallback so navigation is never empty on a fresh install.
 */
function webintelli_nav_fallback() {
	$links = array( home_url( '/' ) => __( 'Home', 'webintelli' ) );

	foreach ( array( 'coffee-shop', 'brewing-guide', 'coffee-glossary' ) as $type ) {
		$archive = get_post_type_archive_link( $type );
		$object  = get_post_type_object( $type );

		if ( $archive && $object ) {
			$links[ $archive ] = $object->labels->name;
		}
	}

	echo '<ul class="nav-menu">';
	foreach ( $links as $url => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
