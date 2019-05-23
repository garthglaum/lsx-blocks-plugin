<?php
namespace lsx_blocks_plugin\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-blocks-plugin
 */
class Frontend {

		/**
		 * Holds class instance
		 *
		 * @since 1.0.0
		 *
		 * @var      object \lsx_blocks_plugin\classes\Frontend()
		 */
		protected static $instance = null;

		/**
		 * Contructor
		 */
		public function __construct() {
			add_filter( 'the_content', array( $this, 'mobile_srcset_tag' ), 10, 1 );
			add_action( 'plugins_loaded', array( $this, 'blocks_loader' ) );
			add_action( 'init', array( $this, 'blocks_init' ) );
		}

		/**
		 * Return an instance of this class.
		 *
		 * @since 1.0.0
		 *
		 * @return    object \lsx_blocks_plugin\classes\Frontend()    A single instance of this class.
		 */
		public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
		self::$instance = new self;
		}
			return self::$instance;
		}

	/**
	 * Overwrites the mobile content and adds in the src tag
	 * @param $content
	 *
	 * @return mixed
	 */
	public function mobile_srcset_tag( $content ) {
		if ( function_exists( 'has_blocks' ) && has_blocks() ) {

			if ( has_block( 'lsx-blocks/lsx-testimonial' ) ) {

				$div_matches = array();
				preg_match_all('/<div class="lsx-block-post-grid-image">(.*?)<\/div>/s', $content, $div_matches);

				if ( ! empty( $div_matches ) && ! empty( $div_matches[1] ) ) {

					foreach ( $div_matches[1] as $image_match ) {
						if ( strpos( $image_match, 'srcset' ) === false ) {

							//Get the iamge URL
							$current_image_url = false;
							$image_urls = array();
							preg_match_all( '@src="([^"]+)"@' , $image_match, $image_urls );

							if ( ! empty( $image_urls ) && isset( $image_urls[1] ) && ! empty( $image_urls[1] ) && isset( $image_urls[1][0] ) ) {
								$current_image_url = $image_urls[1][0];
							}

							if ( false !== $current_image_url ) {

								//replace the extension with the mobile size.
								$mobile_image = $current_image_url;
								$mobile_image = str_replace( '-600x400.jpg', '-350x230.jpg', $mobile_image );
								$mobile_image = str_replace( '-600x400.png', '-350x230.png', $mobile_image );
								$mobile_image = str_replace( '-600x400.jpeg', '-350x230.jpeg', $mobile_image );

								$srcset_html = ' srcset="' . $mobile_image . ' 400w,' . $current_image_url . ' 1024w" sizes="(max-width: 400px) 50vw, 10vw" ';

								$new_image_html = str_replace( '<img', '<img ' . $srcset_html, $image_match );
								$content = str_replace( $image_match, $new_image_html, $content );
							}
						}
					}
				}
				//die();
			}
		}
		return $content;
	}

	/**
	 * Load the plugin textdomain
	 */
	public function blocks_init() {
		load_plugin_textdomain( 'lsx-blocks-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Loads the block code and the files needed for the block carousel.
	 */
	public function blocks_loader() {
		
		/**
		 * Load the blocks functionality
		 */
		require_once LSX_BLOCKS_PLUGIN_PATH . 'dist/init.php';
		
		/**
		 * Load Post Carousel PHP
		 */
		require_once LSX_BLOCKS_PLUGIN_PATH . 'src/block-lsx-testimonial/index.php';
	}
}
