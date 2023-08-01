<?php
/**
 * Sidebar functions.
 *
 * Helper functions and template tags related to sidebars.
 *
 * @package   HybridCore
 * @link      https://themehybrid.com/hybrid-core
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2008 - 2021, Justin Tadlock
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\Sidebar;

/**
 * Outputs a sidebar name.
 *
 * @since  1.0.0
 * @param  string $sidebar_id
 * @return void
 *
 * @access public
 */
function display_name( $sidebar_id ) {
    echo esc_html( render_name( $sidebar_id ) );
}

/**
 * Function for grabbing a dynamic sidebar name.
 *
 * @since  1.0.0
 * @global array   $wp_registered_sidebars
 * @param  string $sidebar_id
 * @return string
 *
 * @access public
 */
function render_name( $sidebar_id ) {
    global $wp_registered_sidebars;

    return isset( $wp_registered_sidebars[ $sidebar_id ] )
            ? $wp_registered_sidebars[ $sidebar_id ]['name']
            : '';
}
