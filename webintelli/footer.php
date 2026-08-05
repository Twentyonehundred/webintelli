<?php
/**
 * Theme footer.
 *
 * @package WebIntelli
 */

?>
<footer class="site-footer">
	<p>
		<?php
		printf(
			/* translators: %s: site name. */
			esc_html__( '%s — powered by WordPress on WP Engine.', 'webintelli' ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
	</p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
