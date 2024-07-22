<?php
/**
 * Theme functions.
 *
 * Helper functions and template tags related to the theme itself.
 *
 * @package   HybridTheme
 * @link      https://github.com/themehybrid/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme;

/**
 * This is a wrapper function for core WP's `get_theme_mod()` function.  Core
 * doesn't provide a filter hook for the default value (useful for child themes).
 * The purpose of this function is to provide that additional filter hook.  To
 * filter the final theme mod, use the core `theme_mod_{$name}` filter hook.
 *
 * @param string $name
 * @param mixed  $default
 * @return mixed
 */
function mod( $name, $default = false ) {
    return get_theme_mod(
        $name,
        apply_filters( "hybrid/theme/mod/{$name}/default", $default )
    );
}

/**
 * Outputs the [parent] theme link HTML.
 *
 * @param array $args
 * @return void
 */
function display_link( array $args = [] ) {
    echo render_link( $args );
}

/**
 * Returns the [parent] theme link HTML.
 *
 * @param array $args
 * @return string
 */
function render_link( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'theme-link',
    ] );

    $theme = wp_get_theme( get_template() );

    $allowed = [
        'abbr'    => [ 'title' => true ],
        'acronym' => [ 'title' => true ],
        'code'    => true,
        'em'      => true,
        'strong'  => true,
    ];

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( $theme->display( 'ThemeURI' ) ),
        wp_kses( $theme->display( 'Name' ), $allowed )
    );

    return apply_filters( 'hybrid/theme/link/parent', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the child theme link HTML.
 *
 * @param array $args
 * @return void
 */
function display_child_link( array $args = [] ) {
    echo render_child_link( $args );
}

/**
 * Returns the child theme link HTML.
 *
 * @param array $args
 * @return string
 */
function render_child_link( array $args = [] ) {

    if ( ! is_child_theme() ) {
        return '';
    }

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'child-link',
    ] );

    $theme = wp_get_theme();

    $allowed = [
        'abbr'    => [ 'title' => true ],
        'acronym' => [ 'title' => true ],
        'code'    => true,
        'em'      => true,
        'strong'  => true,
    ];

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( $theme->display( 'ThemeURI' ) ),
        wp_kses( $theme->display( 'Name' ), $allowed )
    );

    return apply_filters( 'hybrid/theme/link/child', $args['before'] . $html . $args['after'] );
}
