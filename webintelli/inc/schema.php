<?php
/**
 * JSON-LD structured data.
 *
 * The ACF fields are explicitly AEO-oriented (the field instructions mention
 * answer-engine optimisation), so the values are also emitted as machine
 * readable schema.org markup: CafeOrCoffeeShop for shops, HowTo for brewing
 * guides, DefinedTerm for glossary entries, and FAQPage wherever the FAQ
 * repeater has rows.
 *
 * @package WebIntelli
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print the JSON-LD graph for the current singular view.
 */
function webintelli_output_schema() {
	if ( ! is_singular( array( 'coffee-shop', 'brewing-guide', 'coffee-glossary' ) ) ) {
		return;
	}

	$nodes = array_filter(
		array(
			webintelli_schema_for_post(),
			webintelli_schema_faq(),
		)
	);

	if ( ! $nodes ) {
		return;
	}

	$graph = 1 === count( $nodes )
		? array_merge( array( '@context' => 'https://schema.org' ), reset( $nodes ) )
		: array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $nodes ),
		);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'webintelli_output_schema', 20 );

/**
 * Build the primary schema node for the current post.
 *
 * @return array<string, mixed>|null
 */
function webintelli_schema_for_post() {
	switch ( get_post_type() ) {
		case 'coffee-shop':
			return webintelli_schema_coffee_shop();
		case 'brewing-guide':
			return webintelli_schema_brewing_guide();
		case 'coffee-glossary':
			return webintelli_schema_glossary_term();
	}

	return null;
}

/**
 * CafeOrCoffeeShop node.
 *
 * @return array<string, mixed>
 */
function webintelli_schema_coffee_shop() {
	$node = array(
		'@type' => 'CafeOrCoffeeShop',
		'name'  => get_the_title(),
		'url'   => get_permalink(),
	);

	$summary = webintelli_field( 'key_facts_summary' );
	if ( $summary ) {
		$node['description'] = $summary;
	}

	$address = webintelli_field( 'address' );
	if ( $address ) {
		$node['address'] = array(
			'@type'         => 'PostalAddress',
			'streetAddress' => $address,
		);
	}

	$neighborhood = webintelli_field( 'neighborhood' );
	if ( $neighborhood ) {
		$node['areaServed'] = $neighborhood;
	}

	$price = webintelli_field( 'price_range' );
	if ( $price ) {
		$node['priceRange'] = $price;
	}

	$hours = webintelli_field( 'opening_hours' );
	if ( $hours ) {
		$node['openingHours'] = array_values(
			array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $hours ) ), 'strlen' )
		);
	}

	if ( webintelli_field( 'has_wifi' ) ) {
		$node['amenityFeature'] = array(
			'@type' => 'LocationFeatureSpecification',
			'name'  => 'Wi-Fi',
			'value' => true,
		);
	}

	if ( has_post_thumbnail() ) {
		$node['image'] = get_the_post_thumbnail_url( null, 'large' );
	}

	return $node;
}

/**
 * HowTo node for a brewing guide.
 *
 * @return array<string, mixed>
 */
function webintelli_schema_brewing_guide() {
	$node = array(
		'@type' => 'HowTo',
		'name'  => get_the_title(),
		'url'   => get_permalink(),
	);

	$takeaway = webintelli_field( 'key_takeaway' );
	if ( $takeaway ) {
		$node['description'] = $takeaway;
	}

	$yield = webintelli_field( 'servings' );
	if ( $yield ) {
		$node['yield'] = (string) $yield;
	}

	$equipment = webintelli_field( 'equipment_needed' );
	if ( $equipment ) {
		$items = array_values(
			array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $equipment ) ), 'strlen' )
		);

		$node['tool'] = array_map(
			static function ( $item ) {
				return array(
					'@type' => 'HowToTool',
					'name'  => $item,
				);
			},
			$items
		);
	}

	if ( has_post_thumbnail() ) {
		$node['image'] = get_the_post_thumbnail_url( null, 'large' );
	}

	return $node;
}

/**
 * DefinedTerm node for a glossary entry.
 *
 * @return array<string, mixed>
 */
function webintelli_schema_glossary_term() {
	$node = array(
		'@type' => 'DefinedTerm',
		'name'  => webintelli_field( 'term' ) ?: get_the_title(),
		'url'   => get_permalink(),
	);

	$definition = webintelli_field( 'short_definition' );
	if ( $definition ) {
		$node['description'] = $definition;
	}

	$archive = get_post_type_archive_link( 'coffee-glossary' );
	if ( $archive ) {
		$node['inDefinedTermSet'] = array(
			'@type' => 'DefinedTermSet',
			'name'  => __( 'Coffee Glossary', 'webintelli' ),
			'url'   => $archive,
		);
	}

	$category = webintelli_field( 'category' );
	if ( $category ) {
		$node['termCode'] = $category;
	}

	return $node;
}

/**
 * FAQPage node built from the FAQ repeater.
 *
 * @return array<string, mixed>|null
 */
function webintelli_schema_faq() {
	$rows = webintelli_faq_rows();

	if ( ! $rows ) {
		return null;
	}

	return array(
		'@type'      => 'FAQPage',
		'mainEntity' => array_map(
			static function ( $row ) {
				return array(
					'@type'          => 'Question',
					'name'           => $row['question'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $row['answer'],
					),
				);
			},
			$rows
		),
	);
}
