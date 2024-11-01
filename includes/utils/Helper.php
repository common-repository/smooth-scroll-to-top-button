<?php

namespace HulkPlugins\SmoothScrollToTopButton\utils;

use HulkPlugins\SmoothScrollToTopButton\Filesystem;
use WP_Error;
use const HulkPlugins\SmoothScrollToTopButton\PLUGIN_DIR_PATH;
use const HulkPlugins\SmoothScrollToTopButton\SUPPORTED_SETTINGS;

final class Helper {

	/**
	 * Get pages
	 * @return array
	 */
	public static function get_pages(): array {
		$data  = [
			[
				'id'    => 'home',
				'title' => esc_attr__( 'Home', 'smooth-scroll-to-top-button' ),
			],
		];
		$pages = get_pages();
		if ( is_array( $pages ) ) {
			foreach ( $pages as $page ) {
				$data[] = [
					'id'    => absint( $page->ID ),
					'title' => esc_attr( $page->post_title ),
				];
			}
		}

		return apply_filters( 'hulk_ssttb_pages', $data );
	}

	/**
	 * Sanitize devices
	 *
	 * @param array $data
	 *
	 * @return array{
	 *     desktop: boolean,
	 *     tablet: boolean,
	 *     mobile: boolean,
	 * }
	 */
	public static function sanitize_devices( array $data ): array {
		$sanitized_data = [];
		foreach ( $data as $key => $value ) {
			$sanitized_data[ sanitize_text_field( $key ) ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		}

		return $sanitized_data;
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public static function sanitize_settings( array $data ): array {

		$sanitized_data = [];
		foreach ( $data as $key => $value ) {
			$key = sanitize_text_field( $key );
			switch ( $key ) {
				case 'text':
					$sanitized_data[ $key ] = wp_kses_post( $value );
					break;
				case 'distanceTop':
				case 'distanceLeft':
				case 'distanceRight':
				case 'distanceBottom':
				case 'borderWidth':
				case 'width':
				case 'height':
				case 'iconSize':
				case 'textSize':
				case 'gap':
				case 'scrollPosition':
				case 'radius':
					$sanitized_data[ $key ] = intval( $value );
					break;
				case 'scrollingSpeed':
					$sanitized_data[ $key ] = floatval( $value );
					break;
				case 'customCss':
					$sanitized_data[ $key ] = wp_unslash( sanitize_textarea_field( $value ) );
					break;
				case 'enableButtonBorder':
				case 'enable':
				case 'reverse':
					$sanitized_data[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
					break;
				case 'devices':
					$sanitized_data[ $key ] = self::sanitize_devices( $value );
					break;
				case 'excludedPages':
				case 'specificPages':
					$sanitized_data[ $key ] = array_map( 'sanitize_text_field', $value );
					break;
				default:
					$sanitized_data[ $key ] = sanitize_text_field( $value );
					break;
			}
		}

		return $sanitized_data;
	}

	/**
	 * Get registered settings
	 * @return array
	 */
	public static function get_settings(): array {

		$result = wp_cache_get( 'hulk_ssttb_settings', 'hulk_ssttb_group' );

		if ( $result === false ) {
			$settings = get_option( 'hulk_ssttb_settings' );
			$settings = is_array( $settings ) ? $settings : [];
			$settings = wp_parse_args( $settings, SUPPORTED_SETTINGS );
			$result   = self::sanitize_settings( $settings );
			wp_cache_set( 'hulk_ssttb_settings', $result, 'hulk_ssttb_group' );
		}

		return $result;
	}

	/**
	 * Save template and create a json file
	 *
	 * @param string $filename
	 * @param array $data
	 *
	 * @return true|WP_Error
	 */
	public static function save_template_to_assets( string $filename, array $data ) {

		// Define the path to the assets directory
		$assets_dir = PLUGIN_DIR_PATH . 'assets/templates';

		// Ensure the assets directory exists
		if ( ! file_exists( $assets_dir ) ) {
			wp_mkdir_p( $assets_dir );
		}

		// Define the file path
		$file_path = trailingslashit( $assets_dir ) . $filename . '.json';

		// Convert the data to JSON format
		$json_content = wp_json_encode( $data );

		$filesystem = Filesystem::get_instance();

		// Save the JSON file
		if ( ! $filesystem->put_contents( $file_path, $json_content, FS_CHMOD_FILE ) ) {
			return new WP_Error(
				'file_write_error',
				esc_attr__( 'Failed to write the JSON file', 'smooth-scroll-to-top-button' )
			);
		}

		return true;
	}

	/**
	 * Custom comparison function to extract numeric part and compare
	 *
	 * @param $key1
	 * @param $key2
	 *
	 * @return int
	 */
	public static function compare_keys( $key1, $key2 ): int {

		// Extract numeric part from the keys
		$num1 = intval( substr( $key1, strpos( $key1, '-' ) + 1 ) );
		$num2 = intval( substr( $key2, strpos( $key2, '-' ) + 1 ) );

		// Compare the numeric parts
		return $num1 - $num2;
	}

	/**
	 * Read template files
	 *
	 * @return array|WP_Error
	 */
	public static function read_templates() {

		// Define the path to the assets directory
		$assets_dir = PLUGIN_DIR_PATH . 'assets/templates';

		// Ensure the assets directory exists
		if ( ! file_exists( $assets_dir ) ) {
			return new WP_Error(
				'directory_not_found',
				esc_attr__( 'Assets directory does not exist', 'smooth-scroll-to-top-button' )
			);
		}

		// Get a list of all .json files in the assets directory
		$json_files = glob( trailingslashit( $assets_dir ) . '*.json' );

		if ( empty( $json_files ) ) {
			return new WP_Error(
				'no_files_found',
				esc_attr__( 'No JSON files found in the assets directory', 'smooth-scroll-to-top-button' )
			);
		}

		$json_data = [];

		$filesystem = Filesystem::get_instance();

		// Read the contents of each JSON file
		foreach ( $json_files as $file ) {
			$file_contents = $filesystem->get_contents( $file );
			if ( $file_contents === false ) {
				return new WP_Error(
					'file_read_error',
					esc_attr__( 'Failed to read the JSON file', 'smooth-scroll-to-top-button' )
				);
			}

			$file_name = basename( $file );
			$file_name = str_replace( '.json', '', $file_name );

			$json_data[ $file_name ] = json_decode( $file_contents, true );
		}

		uksort( $json_data, [ self::class, 'compare_keys' ] );

		return $json_data;
	}

	/**
	 * Read SVG icons
	 *
	 * @return array|WP_Error
	 */
	public static function read_svg_icons() {

		// Define the path to the assets directory
		$assets_dir = PLUGIN_DIR_PATH . 'assets/icons';

		// Ensure the assets directory exists
		if ( ! file_exists( $assets_dir ) ) {
			return new WP_Error(
				'directory_not_found',
				esc_attr__( 'Icons directory does not exist', 'smooth-scroll-to-top-button' )
			);
		}

		// Get a list of all .json files in the assets directory
		$json_files = glob( trailingslashit( $assets_dir ) . '*.svg' );

		if ( empty( $json_files ) ) {
			return new WP_Error(
				'no_files_found',
				esc_attr__( 'No SVG files found in the assets directory', 'smooth-scroll-to-top-button' )
			);
		}

		$data = [];

		$filesystem = Filesystem::get_instance();

		// Read the contents of each JSON file
		foreach ( $json_files as $file ) {
			$file_contents = $filesystem->get_contents( $file );
			if ( $file_contents === false ) {
				return new WP_Error(
					'file_read_error',
					esc_attr__( 'Failed to read the SVG file', 'smooth-scroll-to-top-button' )
				);
			}

			$file_name = basename( $file );

			$data[ $file_name ] = $file_contents;
		}

		return $data;
	}

	/**
	 * Read stylesheet content
	 *
	 * @param $handle
	 *
	 * @return false|string
	 */
	public static function read_enqueued_stylesheet_content( $handle ) {
		global $wp_styles;

		// Check if the stylesheet is registered
		if ( isset( $wp_styles->registered[ $handle ] ) ) {
			// Get the URL of the stylesheet
			$src = $wp_styles->registered[ $handle ]->src;

			// Convert URL to a file path
			$path = str_replace( site_url( '/' ), ABSPATH, $src );

			$filesystem = Filesystem::get_instance();

			// Use the filesystem object to read the file
			if ( $filesystem->exists( $path ) && $filesystem->is_readable( $path ) ) {
				return $filesystem->get_contents( $path );
			} else {
				return esc_attr__( 'File does not exist or is not readable.', 'smooth-scroll-to-top-button' );
			}
		} else {
			return esc_attr__( 'Stylesheet handle not found.', 'smooth-scroll-to-top-button' );
		}
	}

	/**
	 * Pages check
	 *
	 * @param array $pages
	 *
	 * @return bool
	 */
	public static function pages_check( array $pages ): bool {
		foreach ( $pages as $page ) {
			if ( is_numeric( $page ) ) {
				$query_id = get_queried_object_id();
				if ( $query_id === (int) $page ) {
					return true;
				}
			} elseif ( is_string( $page ) ) {
				if ( ( $page === 'home' ) && is_front_page() ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Arrow button visibility check
	 *
	 * @param array $settings
	 *
	 * @return bool
	 */
	public static function is_visible( array $settings ): bool {

		if ( $settings['enable'] !== true ) {
			return false;
		}

		if ( $settings['pages'] === 'excluded' ) {
			$excluded_pages = (array) $settings['excludedPages'];

			return ! self::pages_check( $excluded_pages );
		} elseif ( $settings['pages'] === 'specific' ) {
			$specific_pages = (array) $settings['specificPages'];

			return self::pages_check( $specific_pages );
		}

		return apply_filters( 'hulk_ssttb_is_visible', true );
	}
}
