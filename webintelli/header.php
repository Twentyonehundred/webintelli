<?php
/**
 * Theme header.
 *
 * @package WebIntelli
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'webintelli' ); ?></a>

<header class="site-header">
	<div class="wi-wrap site-header__inner">
		<p class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</p>

		<button
			class="nav-toggle"
			aria-expanded="false"
			aria-controls="site-nav"
		><?php esc_html_e( 'Menu', 'webintelli' ); ?></button>

		<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'webintelli' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'nav-menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => 'webintelli_nav_fallback',
				)
			);
			?>
		</nav>
	</div>
</header>

<main id="main">
