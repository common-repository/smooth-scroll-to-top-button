<?php

namespace HulkPlugins\SmoothScrollToTopButton;

class Base extends Singleton {

	public function init_hooks(): void {
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	/**
	 * Add plugin meta
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array|mixed
	 */
	public function plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'smooth-scroll-to-top-button.php' ) !== false ) {

			$row_meta['docs'] = sprintf(
			/** @lang text */                '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>',
				esc_url( DOCUMENTATION ),
				esc_html__( 'Docs', 'smooth-scroll-to-top-button' )
			);

			$row_meta['support'] = sprintf(
			/** @lang text */                '<a target="_blank" href="%1$s">%2$s</a>',
				esc_url( SUPPORT ),
				esc_html__( 'Help &amp; Support', 'smooth-scroll-to-top-button' )
			);

			$links = array_merge( $links, $row_meta );
		}

		return $links;
	}

	/**
	 * Admin enqueue scripts
	 * @return void
	 */
	public function admin_scripts() {

		// Compatible with WordPress 6.5 earlier versions
		if ( ! wp_script_is( 'react-jsx-runtime', 'registered' ) ) {
			wp_register_script(
				'react-jsx-runtime',
				PLUGIN_DIR_URL . 'assets/js/react-jsx-runtime.js',
				[ 'react' ],
				'18.3.1',
				true
			);
		}

		$admin_settings_assets = require_once PLUGIN_DIR_PATH . 'build/admin/settings/index.asset.php';

		wp_register_style(
			'hulk-ssttb-settings',
			PLUGIN_DIR_URL . 'build/admin/settings/index.css',
			[ 'wp-components' ],
			$admin_settings_assets['version']
		);

		wp_register_script(
			'hulk-ssttb-settings',
			PLUGIN_DIR_URL . 'build/admin/settings/index.js',
			$admin_settings_assets['dependencies'],
			$admin_settings_assets['version'],
			true
		);

		// Load translation files for javascript
		wp_set_script_translations(
			'hulk-ssttb-settings',
			'smooth-scroll-to-top-button',
			PLUGIN_DIR_PATH . 'languages'
		);
	}
}
