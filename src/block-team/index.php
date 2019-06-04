<?php
/**
 * Server-side rendering for the team carousel block
 *
 * @since   1.0.0
 * @package LSX BLOCKS PLUGIN
 */

/**
 * Renders the team carousel block on server.
 */
function lsx_blocks_render_block_core_latest_team_members_carousel( $attributes ) {

	$categories = isset( $attributes['team_role'] ) ? $attributes['team_role'] : '';

	$custom_taxonomy = isset( $attributes['customTaxonomy'] ) ? $attributes['customTaxonomy'] : '';
	$custom_terms = isset( $attributes['customTermID'] ) ? $attributes['customTermID'] : '';

	$post_args = array(
		'posts_per_page' => $attributes['membersToShowCarousel'],
		'post_status' => 'publish',
		'order' => $attributes['orderCarousel'],
		'orderby' => $attributes['orderByCarousel'],
		'category' => $categories,
		'post_type' => 'team',
		'suppress_filters' => true,
	);
	if ( '' !== $custom_taxonomy && '' !== $custom_terms ) {
		unset( $post_args['category'] );
		$post_args[ trim( $custom_taxonomy ) ] = trim( $custom_terms );
	}
	$recent_posts = get_posts( $post_args );

	/*if (isset($_GET['debug'])) {
		print_r('<pre>');
		print_r($recent_posts);
		print_r('</pre>');
	}*/

	$list_items_markup = '';

	if ( ! empty( $recent_posts ) ) {
		foreach ( $recent_posts as $post ) {

			// Get the post ID.
			$post_id = $post->ID;

			// Get the post thumbnail.
			$post_thumb_id = get_post_thumbnail_id( $post_id );

			if ( $post_thumb_id && isset( $attributes['displayMemberImageCarousel'] ) && $attributes['displayMemberImageCarousel'] ) {
				$post_thumb_class = 'has-thumb';
			} else {
				$post_thumb_class = 'no-thumb';
			}

			// Start the markup for the post.
			$list_items_markup .= sprintf(
				'<div class="%1$s">',
				esc_attr( $post_thumb_class )
			);

			// Get the featured image.
			if ( isset( $attributes['displayMemberImageCarousel'] ) && $attributes['displayMemberImageCarousel'] && $post_thumb_id ) {
				if ( 'landscape' === $attributes['imageCrop'] ) {
					$post_thumb_size = 'lsx-block-post-grid-landscape';
				} else {
					$post_thumb_size = 'lsx-block-post-grid-square';
				}

				$list_items_markup .= sprintf(
					'<div class="lsx-block-post-grid-image lsx-block-team-grid-image"><a href="%1$s" rel="bookmark">%2$s</a></div>',
					esc_url( get_permalink( $post_id ) ),
					wp_get_attachment_image( $post_thumb_id, $post_thumb_size )
				);
			}

			// Wrap the text content.
			$list_items_markup .= sprintf(
				'<div class="lsx-block-post-grid-text lsx-block-team-grid-text">'
			);

			// Get the post title.
			$title = get_the_title( $post_id );

			if ( ! $title ) {
				$title = __( 'Untitled', 'lsx-blocks' );
			}

			$list_items_markup .= sprintf(
				'<h2 class="lsx-block-post-grid-title"><a href="%1$s" rel="bookmark">%2$s</a></h2>',
				esc_url( get_permalink( $post_id ) ),
				esc_html( $title )
			);

			// Wrap the excerpt content.
			$list_items_markup .= sprintf(
				'<div class="lsx-block-post-grid-excerpt">'
			);

			// Get the excerpt.
			$excerpt = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', $post_id, 'display' ) );

			if ( empty( $excerpt ) ) {
				$excerpt = apply_filters( 'the_excerpt', wp_trim_words( $post->post_content, 20 ) );
			}

			if ( ! $excerpt ) {
				$excerpt = null;
			}

			if ( isset( $attributes['displayMemberExcerptCarousel'] ) && $attributes['displayMemberExcerptCarousel'] ) {
				$list_items_markup .= wp_kses_post( $excerpt );
			}

			// Close the excerpt content.
			$list_items_markup .= sprintf(
				'</div>'
			);

			// Wrap the text content.
			$list_items_markup .= sprintf(
				'</div>'
			);

			// Close the markup for the post.
			$list_items_markup .= "</div>\n";
		}
	}

	// Build the classes.
	$class = "lsx-block-post-carousel lsx-block-team-carousel align{$attributes['alignCarousel']}";

	if ( isset( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}

	$grid_class = 'lsx-post-carousel-items lsx-team-carousel-items slick-slider';

	if ( isset( $attributes['columnsCarousel'] ) ) {
		$grid_class .= ' columns-' . $attributes['columnsCarousel'];
	}

	$slides_to_show   = $attributes['columnsCarousel'];
	$slides_to_scroll = $attributes['columnsCarousel'];
	$interval         = 'data-interval="false"';
	$slick_internal   = "data-slick='{ \"slidesToShow\": {$slides_to_show}, \"slidesToScroll\": {$slides_to_scroll} }'";

	// Output the post markup.
	$block_content = sprintf(
		'<div class="%1$s"><div class="%2$s"' . $slick_internal . $interval . '>%3$s</div></div>',
		esc_attr( $class ),
		esc_attr( $grid_class ),
		$list_items_markup
	);

	return $block_content;
}

/**
 * Registers the `core/latest-posts` block on server.
 */
function lsx_blocks_register_block_core_latest_team_members_carousel() {

	// Check if the register function exists.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type( 'lsx-blocks-plugin/block-team', array(
		'attributes'      => array(
			'categories'                 => array(
				'type' => 'string',
			),
			'className'                  => array(
				'type' => 'string',
			),
			'membersToShowCarousel'        => array(
				'type'    => 'number',
				'default' => 6,
			),
			'displayMemberExcerptCarousel' => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'displayMemberImageCarousel'   => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'columnsCarousel'              => array(
				'type'    => 'number',
				'default' => 3,
			),
			'alignCarousel'                => array(
				'type'    => 'string',
				'default' => 'center',
			),
			'width'                        => array(
				'type'    => 'string',
				'default' => 'wide',
			),
			'orderCarousel'                => array(
				'type'    => 'string',
				'default' => 'desc',
			),
			'orderByCarousel'              => array(
				'type'    => 'string',
				'default' => 'date',
			),
			'imageCrop'                    => array(
				'type'    => 'string',
				'default' => 'landscape',
			),
		),
		'render_callback' => 'lsx_blocks_render_block_core_latest_team_members_carousel',
	) );
}

add_action( 'init', 'lsx_blocks_register_block_core_latest_team_members_carousel' );


/**
 * Create API fields for additional info
 */
function lsx_blocks_register_rest_fields_team_carousel() {
	// Add landscape featured image source.
	register_rest_field(
		'team',
		'featured_image_src',
		array(
			'get_callback'    => 'lsx_blocks_get_image_src_landscape_team_carousel',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	// Add square featured image source.
	register_rest_field(
		'team',
		'featured_image_src_square',
		array(
			'get_callback'    => 'lsx_blocks_get_image_src_square_team_carousel',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'lsx_blocks_register_rest_fields_team_carousel' );


/**
 * Get landscape featured image source for the rest field
 */
function lsx_blocks_get_image_src_landscape_team_carousel( $object, $field_name, $request ) {
	$feat_img_array = wp_get_attachment_image_src(
		$object['featured_media'],
		'lsx-block-post-grid-landscape',
		false
	);
	return $feat_img_array[0];
}

/**
 * Get square featured image source for the rest field
 */
function lsx_blocks_get_image_src_square_team_carousel( $object, $field_name, $request ) {
	$feat_img_array = wp_get_attachment_image_src(
		$object['featured_media'],
		'lsx-block-post-grid-square',
		false
	);
	return $feat_img_array[0];
}
