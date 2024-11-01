<?php

namespace HulkPlugins\SmoothScrollToTopButton;

use WP_Filesystem_Direct;

/**
 * Filesystem Class for direct PHP file and folder manipulation.
 */
class Filesystem {

	private static WP_Filesystem_Direct $instance;
	private WP_Filesystem_Direct $filesystem;

	/**
	 * Private constructor to prevent multiple instances
	 */
	private function __construct() {

		// Initialize the WP Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		global /** @var WP_Filesystem_Direct $wp_filesystem */
		$wp_filesystem;

		$this->filesystem = $wp_filesystem;
	}

	/**
	 * Get the instance
	 * @return WP_Filesystem_Direct
	 */
	public static function get_instance(): WP_Filesystem_Direct {
		if ( ! isset( self::$instance ) ) {
			self::$instance = ( new self() )->filesystem;
		}

		return self::$instance;
	}

	/**
	 * Prevent cloning
	 * @return void
	 */
	private function __clone() {
	}
}
