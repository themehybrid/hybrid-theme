<?php
/**
 * Sidebar functions.
 *
 * Helper functions and template tags related to sidebars.
 *
 * @package   HybridTheme
 * @link      https://github.com/themehybrid/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\Sidebar;

/**
 * Outputs a sidebar name.
 *
 * @param string $sidebar_id
 * @return void
 */
function display_name( $sidebar_id ) {
    echo esc_html( render_name( $sidebar_id ) );
}

/**
 * Function for grabbing a dynamic sidebar name.
 *
 * @global array   $wp_registered_sidebars
 * @param string $sidebar_id
 * @return string
 */
function render_name( $sidebar_id ) {
    global $wp_registered_sidebars;

    return isset( $wp_registered_sidebars[ $sidebar_id ] )
            ? $wp_registered_sidebars[ $sidebar_id ]['name']
            : '';
}
