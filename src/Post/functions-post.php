<?php
/**
 * Post functions.
 *
 * Helper functions and template tags related to posts.
 *
 * @package   HybridCore
 * @link      https://themehybrid.com/hybrid-core
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2008 - 2021, Justin Tadlock
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\Post;

/**
 * Creates a hierarchy based on the current post. Its primary purpose is for
 * use with post views/templates.
 *
 * @since  1.0.0
 * @return array
 *
 * @access public
 */
function hierarchy() {

    // Set up an empty array and get the post type.
    $hierarchy = [];
    $post_type = get_post_type();

    // If attachment, add attachment type templates.
    if ( 'attachment' === $post_type ) {

        extract( mime_types() );

        if ( $subtype ) {
            $hierarchy[] = "attachment-{$type}-{$subtype}";
            $hierarchy[] = "attachment-{$subtype}";
        }

        $hierarchy[] = "attachment-{$type}";
    }

    // If the post type supports 'post-formats', get the template based on the format.
    if ( post_type_supports( $post_type, 'post-formats' ) ) {

        // Get the post format.
        $post_format = get_post_format() ?: 'standard';

        // Template based off post type and post format.
        $hierarchy[] = "{$post_type}-{$post_format}";

        // Template based off the post format.
        $hierarchy[] = $post_format;
    }

    // Template based off the post type.
    $hierarchy[] = $post_type;

    return apply_filters( 'hybrid/theme/post/hierarchy', $hierarchy );
}

/**
 * Outputs the post title HTML.
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
 * Returns the post title HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_title( array $args = [] ) {

    $post_id   = get_the_ID();
    $is_single = is_single( $post_id ) || is_page( $post_id ) || is_attachment( $post_id );

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__title',
        'link'   => ! $is_single,
        'tag'    => $is_single ? 'h1' : 'h2',
        'text'   => '%s',
    ] );

    $text = sprintf( $args['text'], $is_single ? single_post_title( '', false ) : the_title( '', '', false ) );

    if ( $args['link'] ) {
        $text = render_permalink( [ 'text' => $text ] );
    }

    $html = sprintf(
        '<%1$s class="%2$s">%3$s</%1$s>',
        tag_escape( $args['tag'] ),
        esc_attr( $args['class'] ),
        $text
    );

    return apply_filters( 'hybrid/theme/post/title', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the post permalink HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_permalink( array $args = [] ) {
    echo render_permalink( $args );
}

/**
 * Returns the post permalink HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_permalink( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__permalink',
        'text'   => '%s',
    ] );

    $url = get_permalink();

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( $url ),
        sprintf( $args['text'], esc_url( $url ) )
    );

    return apply_filters( 'hybrid/theme/post/permalink', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the post author HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_author( array $args = [] ) {
    echo render_author( $args );
}

/**
 * Returns the post author HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_author( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__author',
        'link'   => true,
        'text'   => '%s',
    ] );

    $author = get_the_author();

    if ( $args['link'] ) {
        $url = get_author_posts_url( get_the_author_meta( 'ID' ) );

        $author = sprintf(
            '<a class="entry__author-link" href="%s">%s</a>',
            esc_url( $url ),
            $author
        );
    }

    $html = sprintf( '<span class="%s">%s</span>', esc_attr( $args['class'] ), $author );

    return apply_filters( 'hybrid/theme/post/author', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the post date HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_date( array $args = [] ) {
    echo render_date( $args );
}

/**
 * Returns the post date HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_date( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__published',
        'format' => '',
        'text'   => '%s',
    ] );

    $html = sprintf(
        '<time class="%s" datetime="%s">%s</time>',
        esc_attr( $args['class'] ),
        esc_attr( get_the_date( DATE_W3C ) ),
        sprintf( $args['text'], get_the_date( $args['format'] ) )
    );

    return apply_filters( 'hybrid/theme/post/date', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the post comments link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_comments_link( array $args = [] ) {
    echo render_comments_link( $args );
}

/**
 * Returns the post comments link HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_comments_link( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__comments',
        'more'   => false,
        'one'    => false,
        'zero'   => false,
    ] );

    $number = get_comments_number();

    if ( 0 === $number && ! comments_open() && ! pings_open() ) {
        return '';
    }

    $url  = get_comments_link();
    $text = get_comments_number_text( $args['zero'], $args['one'], $args['more'] );

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( $url ),
        $text
    );

    return apply_filters( 'hybrid/theme/post/comments', $args['before'] . $html . $args['after'] );
}

/**
 * Outputs the post terms HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_terms( array $args = [] ) {
    echo render_terms( $args );
}

/**
 * Returns the post terms HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_terms( array $args = [] ) {

    $html = '';

    $args = wp_parse_args( $args, [
        'after'    => '',
        'before'   => '',
        'class'    => '',
        // Translators: Separates tags, categories, etc. when displaying a post.
        'sep'      => _x( ', ', 'taxonomy terms separator', 'hybrid-core' ),
        'taxonomy' => 'category',
        'text'     => '%s',
    ] );

    // Append taxonomy to class name.
    if ( ! $args['class'] ) {
        $args['class'] = "entry__terms entry__terms--{$args['taxonomy']}";
    }

    $terms = get_the_term_list( get_the_ID(), $args['taxonomy'], '', $args['sep'], '' );

    if ( $terms ) {

        $html = sprintf(
            '<span class="%s">%s</span>',
            esc_attr( $args['class'] ),
            sprintf( $args['text'], $terms )
        );

        $html = $args['before'] . $html . $args['after'];
    }

    return apply_filters( 'hybrid/theme/post/terms', $html );
}

/**
 * Outputs the post format HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return void
 *
 * @access public
 */
function display_format( array $args = [] ) {
    echo render_format( $args );
}

/**
 * Returns the post format HTML.
 *
 * @since  1.0.0
 * @param  array $args
 * @return string
 *
 * @access public
 */
function render_format( array $args = [] ) {

    $args = wp_parse_args( $args, [
        'after'  => '',
        'before' => '',
        'class'  => 'entry__format',
        'text'   => '%s',
    ] );

    $format = get_post_format();
    $url    = $format ? get_post_format_link( $format ) : get_permalink();
    $string = get_post_format_string( $format );

    $html = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr( $args['class'] ),
        esc_url( $url ),
        sprintf( $args['text'], $string )
    );

    return apply_filters( 'hybrid/theme/post/format', $args['before'] . $html . $args['after'] );
}

/**
 * Splits the post mime type into two distinct parts: type / subtype
 * (e.g., image / png). Returns an array of the parts.
 *
 * @since  1.0.0
 * @param  \WP_Post|int $post  A post object or ID.
 * @return array
 *
 * @access public
 */
function mime_types( $post = null ) {

    $type    = get_post_mime_type( $post );
    $subtype = '';

    if ( false !== strpos( $type, '/' ) ) {
        [ $type, $subtype ] = explode( '/', $type );
    }

    return [
        'subtype' => $subtype,
        'type'    => $type,
    ];
}

/**
 * Checks if a post has any content. Useful if you need to check if the user has
 * written any content before performing any actions.
 *
 * @since  1.0.0
 * @param  \WP_Post|int $post  A post object or post ID.
 * @return bool
 *
 * @access public
 */
function has_content( $post = null ) {
    $post = get_post( $post );

    return ! empty( $post->post_content );
}

/**
 * Returns the number of items in all the galleries for the post.
 *
 * @since  1.0.0
 * @param  \WP_Post|int $post  A post object or ID.
 * @return int
 *
 * @access public
 */
function gallery_count( $post = null ) {

    $post   = get_post( $post );
    $images = [];

    // `get_post_galleries_images()` passes an array of arrays, so we need
    // to merge them all together.
    foreach ( get_post_galleries_images( $post ) as $gallery_images ) {
        $images = array_merge( $images, $gallery_images );
    }

    // If there are no images in the array, just grab the attached images.
    if ( ! $images ) {

        $images = get_posts( [
            'fields'         => 'ids',
            'numberposts'    => -1,
            'post_mime_type' => 'image',
            'post_parent'    => $post->ID,
            'post_type'      => 'attachment',
        ] );
    }

    // Return the count of the images.
    return count( $images );
}
