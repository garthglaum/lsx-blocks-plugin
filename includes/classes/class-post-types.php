<?php
namespace lsx_blocks_plugin\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-blocks-plugin
 */
class Post_Types {

		/**
		 * Holds class instance
		 *
		 * @since 1.0.0
		 *
		 * @var      object \lsx_blocks_plugin\classes\Core()
		 */
		protected static $instance = null;

		/**
		 * Contructor
		 */
		public function __construct() {
		add_action( 'init', array( $this, 'post_type_setup' ) );
		}

		/**
		 * Return an instance of this class.
		 *
		 * @since 1.0.0
		 *
		 * @return    object \lsx_blocks_plugin\classes\Core()    A single instance of this class.
		 */
		public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
		self::$instance = new self;
		}
			return self::$instance;
		}

		public function post_type_setup() {
		$labels = array(
		'name'               => esc_html_x( 'Testimonials', 'post type general name', 'lsx-testimonials' ),
		'singular_name'      => esc_html_x( 'Testimonial', 'post type singular name', 'lsx-testimonials' ),
		'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-testimonials' ),
		'add_new_item'       => esc_html__( 'Add New Testimonial', 'lsx-testimonials' ),
		'edit_item'          => esc_html__( 'Edit Testimonial', 'lsx-testimonials' ),
		'new_item'           => esc_html__( 'New Testimonial', 'lsx-testimonials' ),
		'all_items'          => esc_html__( 'All Testimonials', 'lsx-testimonials' ),
		'view_item'          => esc_html__( 'View Testimonial', 'lsx-testimonials' ),
		'search_items'       => esc_html__( 'Search Testimonials', 'lsx-testimonials' ),
		'not_found'          => esc_html__( 'No testimonials found', 'lsx-testimonials' ),
		'not_found_in_trash' => esc_html__( 'No testimonials found in Trash', 'lsx-testimonials' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html_x( 'Testimonials', 'admin menu', 'lsx-testimonials' ),
		);

		$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-editor-quote',
		'query_var'          => true,
		'rewrite'            => array(
			'slug' => 'testimonials',
		),
		'capability_type'    => 'post',
		'has_archive'        => 'testimonials',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			),
		);
		register_post_type( 'testimonial', $args );
	}
}
