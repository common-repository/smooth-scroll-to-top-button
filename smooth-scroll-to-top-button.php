<?php
/**
 * Plugin Name:       Smooth Scroll To Top Button
 * Plugin URI:        https://wordpress.org/plugins/smooth-scroll-to-top-button
 * Description:       Allow visitors to scroll to the top of the page with one click.
 * Author:            Hulk Plugins
 * Author URI:        https://hulkplugins.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       smooth-scroll-to-top-button
 * Domain Path:       /languages
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Requires at least: 6.2
 *
 * @package         HulkPlugins
 */

namespace HulkPlugins\SmoothScrollToTopButton;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Constants
require_once plugin_dir_path( __FILE__ ) . 'constants.php';

// Composer autoload
require_once PLUGIN_DIR_PATH . 'vendor/autoload.php';

// Init
Uninstall::get_instance()->init_hooks();
I18n::get_instance()->init_hooks();
Base::get_instance()->init_hooks();
Settings::get_instance()->init_hooks();
Frontend::get_instance()->init_hooks();
