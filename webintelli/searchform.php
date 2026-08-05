<?php
/**
 * Search form.
 *
 * @package WebIntelli
 */

$webintelli_search_id = wp_unique_id( 'search-field-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $webintelli_search_id ); ?>">
		<?php esc_html_e( 'Search', 'webintelli' ); ?>
	</label>

	<input
		type="search"
		id="<?php echo esc_attr( $webintelli_search_id ); ?>"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search guides, shops, terms…', 'webintelli' ); ?>"
	>

	<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Search', 'webintelli' ); ?></button>
</form>
