<?php
/**
 * Customize service provider.
 *
 * This is the service provider for the theme features integration. It binds
 * an instance of the frameworks `Theme` class to the container.
 *
 * @package   HybridTheme
 * @link      https://github.com/themehybrid/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
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
