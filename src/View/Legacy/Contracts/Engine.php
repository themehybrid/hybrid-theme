<?php
/**
 * Engine contract.
 *
 * Engine classes are wrappers around the View system.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy\Contracts;

/**
 * View interface.
 *
 * @since  5.1.0
 */
interface Engine {

    /**
     * Returns a View object.
     *
     * @since  5.1.0
     * @param  string                                               $name
     * @param  array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return \Hybrid\Theme\View\Legacy\Contracts\View
     *
     * @access public
     */
    public function view( $name, $slugs = [], $data = [] );

    /**
     * Outputs a view template.
     *
     * @since  5.1.0
     * @param  string                                               $name
     * @param  array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return void
     *
     * @access public
     */
    public function display( $name, $slugs = [], $data = [] );

    /**
     * Returns a view template as a string.
     *
     * @since  5.1.0
     * @param  string                                               $name
     * @param  array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return string
     *
     * @access public
     */
    function render( $name, $slugs = [], $data = [] );

}
