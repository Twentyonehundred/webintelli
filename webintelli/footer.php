<?php
/**
 * Theme footer.
 *
 * @package WebIntelli
 */

?>
</main>

<footer class="site-footer">
	<div class="wi-wrap">
		<div class="footer-grid">
			<div>
				<h2><?php esc_html_e( 'About', 'webintelli' ); ?></h2>
				<p>
					<?php
					echo esc_html(
						get_bloginfo( 'description' ) ?: __( 'Independent coffee guides, brewing methods and a plain-English glossary.', 'webintelli' )
					);
					?>
				</p>
			</div>

			<div>
				<h2><?php esc_html_e( 'Explore', 'webintelli' ); ?></h2>
				<ul>
					<?php foreach ( array( 'coffee-shop', 'brewing-guide', 'coffee-glossary' ) as $type ) : ?>
						<?php
						$archive = get_post_type_archive_link( $type );
						$object  = get_post_type_object( $type );

						if ( ! $archive || ! $object ) {
							continue;
						}
						?>
						<li>
							<a href="<?php echo esc_url( $archive ); ?>">
								<?php echo esc_html( $object->labels->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<h2><?php esc_html_e( 'Brew methods', 'webintelli' ); ?></h2>
				<?php
				wp_list_categories(
					array(
						'taxonomy'   => 'brew-method',
						'title_li'   => '',
						'number'     => 6,
						'hide_empty' => true,
					)
				);
				?>
			</div>

			<div>
				<h2><?php esc_html_e( 'Search', 'webintelli' ); ?></h2>
				<?php get_search_form(); ?>
			</div>
		</div>

		<div class="site-footer__base">
			<p>
				<?php
				printf(
					/* translators: 1: current year, 2: site name. */
					esc_html__( '%1$s %2$s', 'webintelli' ),
					'&copy; ' . esc_html( wp_date( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>

			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'nav-menu',
						'container'      => false,
						'depth'          => 1,
					)
				);
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
