<?php
/**
 * @package SD Plugins
 */
/*
Plugin Name: SD Tool
Plugin URI: https://sudipdebnathofficial.com/plugins/sd-tool
Description: An essential tool to manage/modify/update wordpress functionalities.
Version: 1.0
Author: Sudip Debnath (SD)
Author URI: https://sudipdebnathofficial.com/
License: GPLv3
Text Domain: sd
*/


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi buddy!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
define( 'SD_TOOL_VERSION', '1.0.1' );
define( 'SD_TOOL__MINIMUM_WP_VERSION', '4.0' );
define('SD_TOOL_DIR', plugin_dir_path(__FILE__));
define('SD_TOOL_URL', plugin_dir_url(__FILE__));

require_once( SD_TOOL_DIR . 'autoload.php' );
register_activation_hook( __FILE__, array( 'SD_TOOL', 'Activate' ) );
register_deactivation_hook( __FILE__, array( 'SD_TOOL', 'Deactivate' ) );
