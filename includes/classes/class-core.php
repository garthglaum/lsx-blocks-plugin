<?php
namespace lsx_blocks_plugin\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-blocks-plugin
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_blocks_plugin\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx_blocks_plugin\classes\Post_types();
	 */
	public $post_types;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_classes();
		$this->load_includes();
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

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {
		require_once( LSX_BLOCKS_PLUGIN_PATH . 'includes/classes/class-post-types.php' );
		$this->post_types = Post_Types::get_instance();
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_includes() {
		require_once LSX_BLOCKS_PLUGIN_PATH . 'src/init.php';
	}
}
