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
		add_action( 'init', array( $this, 'post_type_setup_testimonial' ) );
		add_action( 'init', array( $this, 'post_type_setup_team' ) );
		add_action( 'init', array( $this, 'taxonomy_setup_team' ) );
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

	public function post_type_setup_testimonial() {
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

	/**
	 * Set the Team Custom Post Type
	 *
	 * @return void
	 */
	public function post_type_setup_team() {
		$labels = array(
			'name'               => esc_html_x( 'Team Members', 'post type general name', 'lsx-team' ),
			'singular_name'      => esc_html_x( 'Team Member', 'post type singular name', 'lsx-team' ),
			'add_new'            => esc_html_x( 'Add New', 'post type general name', 'lsx-team' ),
			'add_new_item'       => esc_html__( 'Add New Team Member', 'lsx-team' ),
			'edit_item'          => esc_html__( 'Edit Team Member', 'lsx-team' ),
			'new_item'           => esc_html__( 'New Team Member', 'lsx-team' ),
			'all_items'          => esc_html__( 'All Team Members', 'lsx-team' ),
			'view_item'          => esc_html__( 'View Team Member', 'lsx-team' ),
			'search_items'       => esc_html__( 'Search Team Members', 'lsx-team' ),
			'not_found'          => esc_html__( 'No team members found', 'lsx-team' ),
			'not_found_in_trash' => esc_html__( 'No team members found in Trash', 'lsx-team' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html_x( 'Team Members', 'admin menu', 'lsx-team' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-groups',
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'team',
			),
			'capability_type'    => 'post',
			'has_archive'        => 'team',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
			),
		);
		register_post_type( 'team', $args );
	}

	/**
	 * Roles Taxonomy for Team Post Type
	 *
	 * @return void
	 */
	public function taxonomy_setup_team() {
		$labels = array(
			'name'              => esc_html_x( 'Roles', 'taxonomy general name', 'lsx-team' ),
			'singular_name'     => esc_html_x( 'Role', 'taxonomy singular name', 'lsx-team' ),
			'search_items'      => esc_html__( 'Search Roles', 'lsx-team' ),
			'all_items'         => esc_html__( 'All Roles', 'lsx-team' ),
			'parent_item'       => esc_html__( 'Parent Role', 'lsx-team' ),
			'parent_item_colon' => esc_html__( 'Parent Role:', 'lsx-team' ),
			'edit_item'         => esc_html__( 'Edit Role', 'lsx-team' ),
			'update_item'       => esc_html__( 'Update Role', 'lsx-team' ),
			'add_new_item'      => esc_html__( 'Add New Role', 'lsx-team' ),
			'new_item_name'     => esc_html__( 'New Role Name', 'lsx-team' ),
			'menu_name'         => esc_html__( 'Roles', 'lsx-team' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug' => 'team-role',
			),
		);
		register_taxonomy( 'team_role', array( 'team' ), $args );
	}
}
