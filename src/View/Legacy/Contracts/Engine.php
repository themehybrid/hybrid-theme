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
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy\Contracts;

/**
 * View interface.
 */
interface Engine {

    /**
     * Returns a View object.
     *
     * @param string                                               $name
     * @param array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return \Hybrid\Theme\View\Legacy\Contracts\View
     */
    public function view( $name, $slugs = [], $data = [] );

    /**
     * Outputs a view template.
     *
     * @param string                                               $name
     * @param array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return void
     */
    public function display( $name, $slugs = [], $data = [] );

    /**
     * Returns a view template as a string.
     *
     * @param string                                               $name
     * @param array|string                                         $slugs
     * @param array|\Hybrid\Theme\View\Legacy\Contracts\Collection $data
     * @return string
     */
    public function render( $name, $slugs = [], $data = [] );

}
