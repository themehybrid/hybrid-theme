<?php
/**
 * Customize service provider.
 *
 * This is the service provider for the theme features integration. It binds
 * an instance of the frameworks `Theme` class to the container.
 *
 * @package   HybridTheme
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2021, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-theme
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme;

use Hybrid\Core\ServiceProvider;

/**
 * Theme provider.
 *
 * @since  1.0.0
 * @access public
 */
class Provider extends ServiceProvider {

	/**
	 * Registration callback that adds a single instance of the theme
	 * object to the container.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register() {

		$this->registerDefaultProviders();
		$this->bootstrapFilters();
	}

	/**
	 * Adds the default service providers for the framework.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function registerDefaultProviders() {

		array_map( function( $provider ) {
			$this->provider( $provider );
		}, [] );
	}

	/**
	 * Bootstrap action/filter hook calls.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function bootstrapFilters() {

		require_once( 'bootstrap-filters.php' );
	}
}
