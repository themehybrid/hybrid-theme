<?php
/**
 * Engine class.
 *
 * A wrapper around the `View` class with methods for quickly working with views
 * without having to manually instantiate a view object.  It's also useful
 * because it passes an `$engine` variable to all views.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy;

use Hybrid\App;
use Hybrid\Theme\View\Legacy\Contracts\View;
use Hybrid\Tools\Collection;

/**
 * Engine class.
 */
class Engine {

    /**
     * Returns a View object.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return \Hybrid\Theme\View\Legacy\Contracts\View
     */
    public function view( $name, $slugs = [], $data = [] ) {

        if ( ! $data instanceof Collection ) {
            $data = new Collection( (array) $data );
        }

        // Pass the engine itself along so that it can be used directly
        // in views.
        $data->put( 'engine', $this );

        return App::resolve( View::class, compact( 'name', 'slugs', 'data' ) );
    }

    /**
     * Outputs a view template.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return void
     */
    public function display( $name, $slugs = [], $data = [] ) {
        $this->view( $name, $slugs, $data )->display();
    }

    /**
     * Returns a view template as a string.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return string
     */
    public function render( $name, $slugs = [], $data = [] ) {
        return $this->view( $name, $slugs, $data )->render();
    }

}
