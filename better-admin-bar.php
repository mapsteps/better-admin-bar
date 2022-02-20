<?php
/**
 * Plugin Name: Better Admin Bar
 * Plugin URI: https://betteradminbar.com/
 * Description: A better WordPress admin bar. Quickly access all important areas of your website.
 * Version: 3.6.0
 * Author: David Vongries
 * Author URI: https://github.com/MapSteps/
 * Text Domain: better-admin-bar
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Helper constants.
define( 'BETTER_ADMIN_BAR_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'BETTER_ADMIN_BAR_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'BETTER_ADMIN_BAR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'BETTER_ADMIN_BAR_PLUGIN_VERSION', '3.6.0' );

require __DIR__ . '/autoload.php';
