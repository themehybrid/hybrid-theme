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
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy;

use Hybrid\App;
use Hybrid\Theme\View\Legacy\Contracts\View;
use Hybrid\Tools\Collection;

/**
 * Engine class.
 *
 * @since  5.1.0
 *
 * @access public
 */
class Engine {

    /**
     * Returns a View object.
     *
     * @since  5.1.0
     * @param  string                         $name
     * @param  array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return \Hybrid\Theme\View\Legacy\Contracts\View
     *
     * @access public
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
     * @since  5.1.0
     * @param  string                         $name
     * @param  array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return void
     *
     * @access public
     */
    public function display( $name, $slugs = [], $data = [] ) {
        $this->view( $name, $slugs, $data )->display();
    }

    /**
     * Returns a view template as a string.
     *
     * @since  5.1.0
     * @param  string                         $name
     * @param  array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return string
     *
     * @access public
     */
    public function render( $name, $slugs = [], $data = [] ) {
        return $this->view( $name, $slugs, $data )->render();
    }

}
