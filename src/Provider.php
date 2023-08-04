<?php
/**
 * Customize service provider.
 *
 * This is the service provider for the theme features integration. It binds
 * an instance of the frameworks `Theme` class to the container.
 *
 * @package   HybridTheme
 * @link      https://github.com/justintadlock/hybrid-theme
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2021, Justin Tadlock
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme;

use Hybrid\Core\ServiceProvider;

/**
 * Theme provider.
 *
 * @since  1.0.0
 *
 * @access public
 */
class Provider extends ServiceProvider {

    /**
     * Bootstrap action/filter hook calls.
     *
     * @since  1.0.0
     * @return void
     *
     * @access protected
     */
    public function boot() {
        require_once 'bootstrap-filters.php';
    }

}
