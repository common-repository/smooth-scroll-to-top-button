<?php

namespace HulkPlugins\SmoothScrollToTopButton;

use Freemius;
use Freemius_Exception;

final class FreemiusSDK {

	private static ?FreemiusSDK $instance = null;
	private Freemius $fs;

	/**
	 * Create a new class instance.
	 */
	private function __construct() {
		$this->init_freemius();
	}

	/**
	 * Get the instance
	 * @return Freemius|null
	 */
	public static function get_instance(): ?Freemius {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance->get_freemius();
	}

	/**
	 * Set the freemius configuration
	 * @return void
	 */
	private function init_freemius(): void {
		if ( ! isset( $this->fs ) ) {
			require_once PLUGIN_DIR_PATH . 'vendor/freemius/wordpress-sdk/start.php';

			try {
				$this->fs = fs_dynamic_init(
					[
						'id'             => '15841',
						'slug'           => 'smooth-scroll-to-top-button',
						'type'           => 'plugin',
						'public_key'     => 'pk_21d2abb232c23895b7c3e2d4d1e0b',
						'is_premium'     => false,
						'has_addons'     => false,
						'has_paid_plans' => false,
						'menu'           => [
							'slug'       => 'smooth-scroll-to-top-button',
							'first-path' => 'admin.php?page=smooth-scroll-to-top-button',
							'account'    => false,
							'support'    => false,
						],
					]
				);
			} catch ( Freemius_Exception $e ) {
				wp_die( esc_attr( $e->getMessage() ) );
			}
		}
	}

	/**
	 * Get the freemius
	 * @return Freemius
	 */
	private function get_freemius(): Freemius {
		return $this->fs;
	}
}
