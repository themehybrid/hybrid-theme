<?php
/**
 * View service provider.
 *
 * This is the service provider for the view system. The primary purpose of
 * this is to use the container as a factory for creating views. By adding this
 * to the container, it also allows the view implementation to be overwritten.
 * That way, any custom functions will utilize the new class.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View;

use Hybrid\Core\ServiceProvider;
use Hybrid\Theme\Facades\View;
use function Hybrid\Template\path;
use function Hybrid\Tools\collect;

/**
 * View provider class.
 */
class Provider extends ServiceProvider {

    /**
     * Binds the implementation of the view contract to the container.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton( 'theme.view', function ( $app ) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $factory = $this->createFactory( $resolver, $finder, $app['events'] );

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $factory->setContainer( $app );

            $factory->share( 'app', $app );

            return $factory;
        } );
    }

    /**
     * Create a new Factory Instance.
     *
     * @param \Hybrid\View\Engines\EngineResolver $resolver
     * @param \Hybrid\View\ViewFinderInterface    $finder
     * @param \Hybrid\Contracts\Events\Dispatcher $events
     * @return \Hybrid\Theme\View\Factory
     */
    protected function createFactory( $resolver, $finder, $events ) {
        return new Factory( $resolver, $finder, $events );
    }

    /**
     * Boot.
     */
    public function boot() {
        // Relative path to the templates directory.
        $templates_path = path();

        // Add view paths.
        View::addLocation( get_stylesheet_directory() . '/' . $templates_path );
        View::addLocation( get_template_directory() . '/' . $templates_path );

        View::composer( '*', function ( $view ) {
            $this->maybeShiftAttachment( $view );
        } );
    }

    /**
     * Removes core WP's `prepend_attachment` filter whenever a theme is
     * building custom attachment templates. We'll assume that the theme
     * author will handle the appropriate output in the template itself.
     *
     * @param \Hybrid\View\View $view
     * @return void
     */
    protected function maybeShiftAttachment( $view ) {

        if ( ! in_the_loop() || 'attachment' !== get_post_type() ) {
            return;
        }

        $viewName = $view->getName();

        $filtered_entry_templates = collect( [ 'entry', 'post', 'entry.archive', 'entry.single' ] )->filter( static fn( $value ) => str_contains( $viewName, $value ) );

        $filtered_embed_template = collect( [ 'embed' ] )->filter( static fn( $value ) => str_contains( $viewName, $value ) );

        if ( $filtered_entry_templates->isNotEmpty() ) {
            remove_filter( 'the_content', 'prepend_attachment' );
        } elseif ( $filtered_embed_template->isNotEmpty() ) {
            remove_filter( 'the_content', 'prepend_attachment' );
            remove_filter( 'the_excerpt_embed', 'wp_embed_excerpt_attachment' );
        }
    }

}
