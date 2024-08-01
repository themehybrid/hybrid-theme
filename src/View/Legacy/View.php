<?php
/**
 * View class.
 *
 * This file maintains the `View` class.  It's used for setting up and rendering
 * theme template files.  Views are a bit like a suped-up version of the core
 * WordPress `get_template_part()` function.  However, it allows you to build a
 * hierarchy of potential templates as well as pass in any arbitrary data to your
 * templates for use.
 *
 * Every effort has been made to make this compliant with WordPress.org theme
 * directory guidelines by providing compatible action hooks with WordPress core
 * `get_template_part()` and other `get_*()` functions for templates.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy;

use Hybrid\Theme\View\Legacy\Contracts\View as ViewContract;
use Hybrid\Tools\Collection;
use function Hybrid\Template\locate as locate_template;

/**
 * View class.
 */
class View implements ViewContract {

    /**
     * Name of the view. This is primarily used as the folder name. However,
     * it can also be the filename as the final fallback if no folder exists.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Array of slugs to look for. This creates the hierarchy based on the
     * `$name` property (e.g., `{$name}/{$slug}.php`). Slugs are used in
     * the order that they are set.
     *
     * @var string
     */
    protected $slugs = [];

    /**
     * An array of data that is passed into the view template.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The template filename.
     *
     * @var string
     */
    protected $template = null;

    /**
     * Sets up the view properties.
     *
     * @param string $name
     * @param array  $slugs
     * @param object $data
     * @return object
     */
    public function __construct( $name, $slugs = [], ?Collection $data = null ) {

        $this->name  = $name;
        $this->slugs = (array) $slugs;
        $this->data  = $data;

        // Apply filters after all the properties have been assigned.
        // This way, the full object is available to filters.
        $this->slugs = apply_filters( "hybrid/view/{$this->name}/slugs", $this->slugs, $this );
        $this->data  = apply_filters( "hybrid/view/{$this->name}/data", $this->data, $this );
    }

    /**
     * When attempting to use the object as a string, return the template output.
     *
     * @return string
     */
    public function __toString() {
        return $this->render();
    }

    /**
     * Returns the array of slugs.
     *
     * @return array
     */
    public function slugs() {
        return (array) $this->slugs;
    }

    /**
     * Uses the array of template slugs to build a hierarchy of potential
     * templates that can be used.
     *
     * @return array
     */
    protected function hierarchy() {

        // Uses the slugs to build a hierarchy.
        foreach ( $this->slugs as $slug ) {
            $templates[] = "{$this->name}/{$slug}.php";
        }

        // Add in a `default.php` template.
        if ( ! in_array( 'default', $this->slugs ) ) {
            $templates[] = "{$this->name}/default.php";
        }

        // Fallback to `{$name}.php` as a last resort.
        $templates[] = "{$this->name}.php";

        // Allow developers to overwrite the hierarchy.
        return apply_filters( "hybrid/view/{$this->name}/hierarchy", $templates, $this->slugs );
    }

    /**
     * Locates the template.
     *
     * @return string
     */
    protected function locate() {
        return locate_template( $this->hierarchy() );
    }

    /**
     * Returns the located template.
     *
     * @return string
     */
    public function template() {

        if ( is_null( $this->template ) ) {
            $this->template = $this->locate();
        }

        return $this->template;
    }

    /**
     * Sets up data to be passed to the template and renders it.
     *
     * @return void
     */
    public function display() {

        // Compatibility with core WP's template parts.
        $this->templatePartCompat();

        if ( $this->template() ) {

            // Maybe remove core WP's `prepend_attachment`.
            $this->maybeShiftAttachment();

            // Extract the data into individual variables. Each of
            // these variables will be available in the template.
            if ( $this->data instanceof Collection ) {
                extract( $this->data->all() );
            }

            // Make `$data` and `$view` variables available to templates.
            $data = $this->data;
            $view = $this;

            // Load the template.
            include $this->template();
        }
    }

    /**
     * Returns the template output as a string.
     *
     * @return string
     */
    public function render() {
        ob_start();
        $this->display();

        return ob_get_clean();
    }

    /**
     * Fires the core WP action hooks for template parts.
     *
     * Note that WP refers to `$name` and `$slug` differently than we do.
     * They're the opposite of what we use in our function.
     *
     * @return void
     */
    protected function templatePartCompat() {

        // The slug is a string in WP and we have an array. So, we're
        // just going to use the first item of the array in this case.
        $slug = $this->slugs ? reset( $this->slugs ) : null;

        // Compat with `get_header|footer|sidebar()`.
        if ( in_array( $this->name, [ 'header', 'footer', 'sidebar' ] ) ) {

            do_action( "get_{$this->name}", $slug );

            // Compat with `get_template_part()`.
        } else {
            do_action( "get_template_part_{$this->name}", $this->name, $slug );
        }
    }

    /**
     * Removes core WP's `prepend_attachment` filter whenever a theme is
     * building custom attachment templates. We'll assume that the theme
     * author will handle the appropriate output in the template itself.
     *
     * @return void
     */
    protected function maybeShiftAttachment() {

        if ( ! in_the_loop() || 'attachment' !== get_post_type() ) {
            return;
        }

        if ( in_array( $this->name, [ 'entry', 'post', 'entry/archive', 'entry/single' ] ) ) {

            remove_filter( 'the_content', 'prepend_attachment' );

        } elseif ( 'embed' === $this->name ) {

            remove_filter( 'the_content', 'prepend_attachment' );
            remove_filter( 'the_excerpt_embed', 'wp_embed_excerpt_attachment' );
        }
    }

}
