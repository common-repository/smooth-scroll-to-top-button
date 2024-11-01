<?php

namespace HulkPlugins\SmoothScrollToTopButton;

class I18n extends Singleton {

	public function init_hooks(): void {
		add_action( 'plugins_loaded', [ $this, 'load_text_domain' ] );
	}

	/**
	 * Load translation files
	 * @return void
	 */
	public function load_text_domain() {
		load_plugin_textdomain(
			'smooth-scroll-to-top-button',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);
	}
}
