<?php

namespace HulkPlugins\SmoothScrollToTopButton;

use HulkPlugins\SmoothScrollToTopButton\utils\Helper;

class Frontend extends Singleton {

	private bool $is_visible = true;

	public function init_hooks(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'wp_print_styles', [ $this, 'print_styles' ] );
		add_action( 'wp_footer', [ $this, 'front_arrow_view' ] );
	}

	/**
	 * Enqueue scripts
	 * @return void
	 */
	public function scripts() {

		$settings         = Helper::get_settings();
		$this->is_visible = Helper::is_visible( $settings );

		if ( ! $this->is_visible ) {
			return;
		}

		$assets = require_once PLUGIN_DIR_PATH . 'build/frontend/index.asset.php';

		wp_register_style(
			'hulk-ssttb-arrow-button',
			PLUGIN_DIR_URL . 'build/frontend/index.css',
			[],
			$assets['version']
		);

		wp_register_script(
			'hulk-ssttb-arrow-button',
			PLUGIN_DIR_URL . 'build/frontend/index.js',
			$assets['dependencies'],
			$assets['version'],
			true
		);

		$button_color       = esc_attr( $settings['buttonColor'] );
		$button_color_hover = esc_attr( $settings['buttonHoverColor'] );
		if ( empty( $button_color_hover ) ) {
			$button_color_hover = $button_color;
		}

		$icon_color       = esc_attr( $settings['iconColor'] );
		$icon_color_hover = esc_attr( $settings['iconHoverColor'] );
		if ( empty( $icon_color_hover ) ) {
			$icon_color_hover = $icon_color;
		}

		$border_color       = esc_attr( $settings['borderColor'] );
		$border_color_hover = esc_attr( $settings['borderHoverColor'] );
		if ( empty( $border_color_hover ) ) {
			$border_color_hover = $border_color;
		}

		$inline_data = '
			.hulk-ssttb-arrow-button {
				--hulk-ssttb-background: ' . esc_attr( $button_color ) . ';
				--hulk-ssttb-background-hover: ' . esc_attr( $button_color_hover ) . ';
				--hulk-ssttb-color: ' . esc_attr( $icon_color ) . ';
				--hulk-ssttb-color-hover: ' . esc_attr( $icon_color_hover ) . ';
				--hulk-ssttb-text-font-weight: ' . esc_attr( $settings['textFontWeight'] ) . ';
				--hulk-ssttb-distance-top: ' . esc_attr( intval( $settings['distanceTop'] ) ) . 'px;
				--hulk-ssttb-distance-left: ' . esc_attr( intval( $settings['distanceLeft'] ) ) . 'px;
				--hulk-ssttb-distance-right: ' . esc_attr( intval( $settings['distanceRight'] ) ) . 'px;
				--hulk-ssttb-distance-bottom: ' . esc_attr( intval( $settings['distanceBottom'] ) ) . 'px;
				--hulk-ssttb-radius: ' . esc_attr( intval( $settings['radius'] ) ) . 'px;
				--hulk-ssttb-border-color: ' . esc_attr( $border_color ) . ';
				--hulk-ssttb-border-color-hover: ' . esc_attr( $border_color_hover ) . ';
				--hulk-ssttb-border-width: ' . esc_attr( intval( $settings['borderWidth'] ) ) . 'px;
				--hulk-ssttb-width: ' . esc_attr( intval( $settings['width'] ) ) . 'px;
				--hulk-ssttb-height: ' . esc_attr( intval( $settings['height'] ) ) . 'px;
				--hulk-ssttb-icon-size: ' . esc_attr( intval( $settings['iconSize'] ) ) . 'px;
				--hulk-ssttb-direction: ' . esc_attr( $settings['direction'] ) . ';
				--hulk-ssttb-border-style: ' . esc_attr( $settings['borderStyle'] ) . ';
				--hulk-ssttb-text-size: ' . esc_attr( intval( $settings['textSize'] ) ) . 'px;
				--hulk-ssttb-gap: ' . esc_attr( $settings['gap'] ) . 'px;
			}' . esc_attr( $settings['customCss'] ) . ';';

		wp_add_inline_style(
			'hulk-ssttb-arrow-button',
			trim( wp_filter_nohtml_kses( $inline_data ) )
		);

		wp_localize_script(
			'hulk-ssttb-arrow-button',
			'hulk_ssttb_settings',
			[
				'PLUGIN_DIR_URL' => esc_url( PLUGIN_DIR_URL ),
				'pages'          => Helper::get_pages(),
				'settings'       => Helper::sanitize_settings( $settings ),
			]
		);
	}

	/**
	 * Fires before styles in the $handles queue are printed.
	 * @return void
	 */
	public function print_styles() {
		if ( $this->is_visible ) {
			wp_enqueue_style( 'hulk-ssttb-arrow-button' );
		}
	}

	/**
	 * Frontend arrow button view
	 * @return void
	 */
	public function front_arrow_view() {
		if ( $this->is_visible ) {
			wp_enqueue_script( 'hulk-ssttb-arrow-button' );
			require_once PLUGIN_DIR_PATH . 'templates/frontend/main-view.php';
		}
	}
}
