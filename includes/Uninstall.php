<?php

namespace HulkPlugins\SmoothScrollToTopButton;

class Uninstall extends Singleton {

	public function init_hooks(): void {
		$freemius = FreemiusSDK::get_instance();
		$freemius->add_action( 'after_uninstall', [ $this, 'cleanup' ] );
	}

	public function cleanup() {

		// Delete settings
		delete_option( 'hulk_ssttb_settings' );
	}
}
