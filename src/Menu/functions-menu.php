<?php
/**
 * Nav menu functions.
 *
 * Helper functions and template tags related to nav menus.
 *
 * @package   HybridCore
 * @link      https://themehybrid.com/hybrid-core
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2008 - 2021, Justin Tadlock
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\Menu;

/**
 * Outputs the nav menu name by theme location.
 *
 * @since  1.0.0
 * @param  string $location
 * @return void
 *
 * @access public
 */
function display_name( $location ) {
    echo esc_html( render_name( $location ) );
}

/**
 * Function for grabbing a WP nav menu name based on theme location.
 *
 * @since  1.0.0
 * @param  string $location
 * @return string
 *
 * @access public
 */
function render_name( $location ) {

    $locations = get_nav_menu_locations();

    $menu = isset( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : '';

    return $menu ? $menu->name : '';
}

/**
 * Outputs the nav menu theme location name.
 *
 * @since  1.0.0
 * @param  string $location
 * @return void
 *
 * @access public
 */
function display_location( $location ) {
    echo esc_html( render_location( $location ) );
}

/**
 * Function for grabbing a WP nav menu theme location name.
 *
 * @since  1.0.0
 * @param  string $location
 * @return string
 *
 * @access public
 */
function render_location( $location ) {

    $locations = get_registered_nav_menus();

    return $locations[ $location ] ?? '';
}
