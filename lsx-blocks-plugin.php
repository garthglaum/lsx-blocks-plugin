<?php
/*
 * Plugin Name: LSX Blocks Plugin
 * Plugin URI:  https://github.com/lightspeeddevelopment/lsx-blocks-plugin
 * Description:	
 * Version:     1.0.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-blocks-plugin
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_BLOCKS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_BLOCKS_PLUGIN_CORE', __FILE__ );
define( 'LSX_BLOCKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_BLOCKS_PLUGIN_VER', '1.0.0' );


/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_BLOCKS_PLUGIN_PATH . 'includes/classes/class-core.php' );

function lsx_blocks_plugin() {
	return \lsx_blocks_plugin\classes\Core::get_instance();
}

lsx_blocks_plugin();

/**
 * Block Initializer.
 */


