<?php

namespace HulkPlugins\SmoothScrollToTopButton;

use Exception;

abstract class Singleton {

	/**
	 * The instance of the subclass
	 * @var array
	 */
	private static array $instances = [];

	/**
	 * Protected constructor to prevent creating a new instance via the 'new' operator
	 */
	protected function __construct() {
	}

	/**
	 * Prevent instance from being cloned
	 * @return void
	 */
	protected function __clone() {
	}

	/**
	 * @throws Exception
	 */
	public function __wakeup() {
		throw new Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * The method to get the instance of the subclass
	 * @return mixed|static
	 */
	public static function get_instance() {
		$class = static::class; // Late static binding
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}

		return self::$instances[ $class ];
	}

	/**
	 * WP Hooks init
	 * @return void
	 */
	public function init_hooks(): void {
	}
}
