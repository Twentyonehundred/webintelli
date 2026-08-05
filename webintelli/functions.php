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

/**
 * Register theme supports and navigation menus.
 */
function webintelli_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'webintelli' ),
		)
	);
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
}
add_action( 'wp_enqueue_scripts', 'webintelli_enqueue_assets' );
