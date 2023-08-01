<?php
/**
 * Site functions.
 *
 * Helper functions and template tags related to the site.
 *
 * @package   HybridCore
 * @link      https://themehybrid.com/hybrid-core
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2008 - 2021, Justin Tadlock
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\Site;

/**
 * Outputs the site title HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_title( array $args = [] ) {
    echo render_title( $args );
}

/**
 * Returns the site title HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_title( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'class'      => 'app-header__title',
        'link_class' => 'app-header__title-link',
        'tag'        => is_front_page() ? 'h1' : 'div',
    ] );

    $html  = '';
    $title = get_bloginfo( 'name', 'display' );

    if ( $title ) {

        $link = render_home_link( [
            'class' => $args['link_class'],
            'text'  => $title,
        ] );

        $html = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            tag_escape( $args['tag'] ),
            esc_attr( $args['class'] ),
            $link
        );
    }

    return apply_filters( 'hybrid/theme/site/title', $html );
}

/**
 * Outputs the site description HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_description( array $args = [] ) {
    echo render_description( $args );
}

/**
 * Returns the site description HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_description( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'class' => 'app-header__description',
        'tag'   => 'div',
    ] );

    $html = '';
    $desc = get_bloginfo( 'description', 'display' );

    if ( $desc ) {

        $html = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            tag_escape( $args['tag'] ),
            esc_attr( $args['class'] ),
            $desc
        );
    }

    return apply_filters( 'hybrid/theme/site/description', $html );
}

/**
 * Outputs the site link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_home_link( array $args = [] ) {
    echo render_home_link( $args );
}

/**
 * Returns the site link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_home_link( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'home-link',
        'text'   => '%s',
    ] );

    $html = sprintf(
        '<a class="%s" href="%s" rel="home">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( home_url() ),
        sprintf( $args['text'], get_bloginfo( 'name', 'display' ) )
    );

    return apply_filters( 'hybrid/theme/site/home_link', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the WordPress.org link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_wp_link( array $args = [] ) {
    echo render_wp_link();
}

/**
 * Returns the WordPress.org link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_wp_link( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'wp-link',
        'text'   => '%s',
    ] );

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( __( 'https://wordpress.org', 'hybrid-core' ) ),
        sprintf( $args['text'], esc_html__( 'WordPress', 'hybrid-core' ) )
    );

    return apply_filters( 'hybrid/theme/site/wp_link', $args['before'] . $html . $args['after'] );
}
