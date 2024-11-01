<?php

namespace HulkPlugins\SmoothScrollToTopButton;

use HulkPlugins\SmoothScrollToTopButton\utils\Helper;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class Settings extends Singleton {

	public string $hook_suffix = 'toplevel_page_smooth-scroll-to-top-button';

	public function init_hooks(): void {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( "admin_print_styles-$this->hook_suffix", [ $this, 'admin_styles' ] );
		add_action( "admin_print_scripts-$this->hook_suffix", [ $this, 'admin_scripts' ] );
		add_action( 'rest_api_init', [ $this, 'api_init' ] );
	}

	/**
	 * Add setting menu page
	 * @return void
	 */
	public function admin_menu() {
		add_menu_page(
			esc_attr__( 'Scroll To Top', 'smooth-scroll-to-top-button' ),
			esc_attr__( 'Scroll To Top', 'smooth-scroll-to-top-button' ),
			'manage_options',
			'smooth-scroll-to-top-button',
			[ $this, 'settings_page' ],
			'dashicons-arrow-up-alt'
		);
	}

	/**
	 * Settings page
	 * @return void
	 */
	public function settings_page() {
		echo /** @lang text */ '<div class="hulk-ssttb-settings" id="hulk-ssttb-settings"></div>';
	}

	/**
	 * Admin enqueue styles
	 * @return void
	 */
	public function admin_styles() {
		wp_enqueue_style( 'hulk-ssttb-settings' );
	}

	/**
	 * Admin enqueue scripts
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'hulk-ssttb-settings' );
		wp_localize_script(
			'hulk-ssttb-settings',
			'hulk_ssttb_settings',
			[
				'rest_url'       => rest_url(),
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'PLUGIN_DIR_URL' => esc_url( PLUGIN_DIR_URL ),
				'VERSION'        => esc_attr( VERSION ),
				'SUPPORT'        => esc_url( SUPPORT ),
				'DOCUMENTATION'  => esc_url( DOCUMENTATION ),
				'pages'          => Helper::get_pages(),
				'settings'       => Helper::get_settings(),
				'templates'      => Helper::read_templates(),
				'svg_icons'      => Helper::read_svg_icons(),
				'style'          => Helper::read_enqueued_stylesheet_content( 'hulk-ssttb-settings' ),
			]
		);
	}

	/**
	 * REST API
	 *
	 * @return void
	 */
	public function api_init() {

		// Register settings save route
		register_rest_route(
			'hulk-ssttb/v1',
			'/save',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'save_settings' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);

		// Register template save route
		register_rest_route(
			'hulk-ssttb/v1',
			'/save-template',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'save_template' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	/**
	 * Save settings
	 */
	public function save_settings( WP_REST_Request $request ) {

		$data = $request->get_json_params();

		if ( empty( $data ) ) {
			return new WP_Error(
				'no_data',
				esc_attr__( 'No data received', 'smooth-scroll-to-top-button' ),
				[ 'status' => 400 ]
			);
		}

		update_option( 'hulk_ssttb_settings', Helper::sanitize_settings( $data ) );

		$response = [
			'success' => true,
			'message' => esc_attr__( 'Settings saved.', 'smooth-scroll-to-top-button' ),
		];

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Save template
	 */
	public function save_template( WP_REST_Request $request ) {

		$data = $request->get_json_params();

		if ( empty( $data ) ) {
			return new WP_Error(
				'no_data',
				esc_attr__( 'No data received', 'smooth-scroll-to-top-button' ),
				[ 'status' => 400 ]
			);
		}

		if ( empty( $data['name'] ) ) {
			return new WP_Error(
				'no_data',
				esc_attr__( 'Template name is required', 'smooth-scroll-to-top-button' ),
				[ 'status' => 400 ]
			);
		}

		if ( empty( $data['settings'] ) ) {
			return new WP_Error(
				'no_data',
				esc_attr__( 'Settings is required', 'smooth-scroll-to-top-button' ),
				[ 'status' => 400 ]
			);
		}

		Helper::save_template_to_assets(
			sanitize_title( $data['name'] ),
			Helper::sanitize_settings( $data['settings'] )
		);

		$response = [
			'success' => true,
			'message' => esc_attr__( 'Template saved.', 'smooth-scroll-to-top-button' ),
		];

		return new WP_REST_Response( $response, 200 );
	}
}
