<?php
/**
 * View service provider.
 *
 * This is the service provider for the view system. The primary purpose of
 * this is to use the container as a factory for creating views. By adding this
 * to the container, it also allows the view implementation to be overwritten.
 * That way, any custom functions will utilize the new class.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy;

use Hybrid\Core\ServiceProvider;
use Hybrid\Theme\View\Legacy\Contracts\Engine as EngineContract;
use Hybrid\Theme\View\Legacy\Contracts\View as ViewContract;

/**
 * View provider class.
 */
class Provider extends ServiceProvider {

    /**
     * Binds the implementation of the view contract to the container.
     *
     * @return void
     */
    public function register() {

        // Bind the view contract.
        $this->app->bind( ViewContract::class, View::class );

        // Bind a single instance of the engine contract.
        $this->app->singleton( EngineContract::class, Engine::class );
    }

}
