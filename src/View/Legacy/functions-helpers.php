<?php
/**
 * View template tags.
 *
 * Template functions related to views.
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
use Hybrid\Theme\View\Legacy\Contracts\Engine;

if ( ! function_exists( __NAMESPACE__ . '\\view' ) ) {
    /**
     * Returns a view object.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return \Hybrid\Theme\View\Legacy\View
     */
    function view( $name, $slugs = [], $data = [] ) {
        return App::resolve( Engine::class )->view( $name, $slugs, $data );
    }
}

if ( ! function_exists( __NAMESPACE__ . '\\display' ) ) {
    /**
     * Outputs a view template.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return void
     */
    function display( $name, $slugs = [], $data = [] ) {
        view( $name, $slugs, $data )->display();
    }
}

if ( ! function_exists( __NAMESPACE__ . '\\render' ) ) {
    /**
     * Returns a view template as a string.
     *
     * @param string                         $name
     * @param array|string                   $slugs
     * @param array|\Hybrid\Tools\Collection $data
     * @return string
     */
    function render( $name, $slugs = [], $data = [] ) {
        return view( $name, $slugs, $data )->render();
    }
}
