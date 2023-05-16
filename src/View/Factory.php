<?php

namespace Hybrid\Theme\View;

use Hybrid\Tools\Arr;
use Hybrid\View\Factory as ViewFactory;
use Hybrid\View\MockView;

use function Hybrid\Template\Hierarchy\hierarchy as templateHierarchy;
use function Hybrid\Theme\Post\hierarchy as postHierarchy;
use function Hybrid\Tools\str;

class Factory extends ViewFactory {

    protected array $viewParams = [];

    protected $hierarchy = [];
    protected $slugs     = [];

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string                            $view
     * @param \Hybrid\Contracts\Arrayable|array $data
     * @param array                             $mergeData
     * @return \Hybrid\Contracts\View\View
     */
    public function make( $view, $data = [], $mergeData = [] ) {
        try {

            $this->viewParams = compact( 'view', 'data', 'mergeData' );

            $this->prepareParams();

            return self::firstView( $this->prepareViews(), $data, $mergeData );
        } catch ( \InvalidArgumentException $e ) {

            if ( str( $e->getMessage() )->contains( [ 'None of the views in the given array exist.' ] ) ) {
                error_log( sprintf( 'Hybrid Theme View `%s`, not found.', $view ) );

                return new MockView( '', '', [] );
            }

            throw $e;
        }
    }

    /**
     * Prepare the fallback templates.
     *
     * @param string                            $view
     * @param \Hybrid\Contracts\Arrayable|array $slugs
     * @return array
     */
    public function prepareFallbackTemplates( $view, $slugs ) {
        $templates = [];

        // Uses the slugs to build a hierarchy.
        foreach ( $slugs as $slug ) {
            $templates[] = "{$view}/{$slug}";
        }

        // Add in a `default` template.
        if ( ! in_array( 'default', $slugs ) ) {
            $templates[] = "{$view}/default";
        }

        return apply_filters( 'hybrid/theme/view/template/fallback', $templates, $view, $slugs );
    }

    /**
     * Returns the array of slugs.
     *
     * @return array
     */
    public function slugs( $type = 'template' ) {
        return $type === 'template' ? templateHierarchy() : postHierarchy();
    }

    /**
     * Get the first view that actually exists from the given list.
     *
     * @param array                             $views
     * @param \Hybrid\Contracts\Arrayable|array $data
     * @param array                             $mergeData
     * @return \Hybrid\Contracts\View\View
     * @throws \InvalidArgumentException
     */
    public function firstView( array $views, $data = [], $mergeData = [] ) {
        $view = Arr::first( $views, fn( $view) => $this->exists( $view ) );

        if ( ! $view ) {
            throw new \InvalidArgumentException( 'None of the views in the given array exist.' );
        }

        return parent::make( $view, $data, $mergeData );
    }

    /**
     * Perform necessary tweaks to view params,
     * so it accepts either inline slugs,
     * or via filter.
     *
     * @return void
     */
    public function prepareParams() {
        $this->prepareTemplateSlugs();
        $this->prepareTemplateHierarchy();
    }

    public function prepareTemplateHierarchy() {
        static $appliedHierarchy = false;

        if ( $appliedHierarchy ) {
            return;
        }

        $this->hierarchy = apply_filters( 'hybrid/theme/view/template/hierarchy', $this->hierarchy );

        $appliedHierarchy = true;
    }

    public function prepareTemplateSlugs() {
        static $appliedSlugs = false;

        if ( $appliedSlugs ) {
            return;
        }

        $this->slugs = apply_filters( 'hybrid/theme/view/template/slugs', $this->slugs );

        $appliedSlugs = true;
    }

    public function prepareViews() {
        $name = $this->viewParams['view'];

        // If an inline hierarchy is set, we will make use of it.
        $hierarchy = (array) ( $this->viewParams['data']['hierarchy'] ?? [] );

        if ( count( $hierarchy ) > 0 ) {
            return $hierarchy;
        }

        // If the view hierarchy is set using a filter, we utilize it.
        $hierarchy = (array) ( $this->hierarchy[ $name ] ?? [] );

        if ( count( $hierarchy ) > 0 ) {
            return $hierarchy;
        }

        // If inline slugs are set, we use them.
        $slugs = (array) ( $this->viewParams['data']['slugs'] ?? [] );

        if ( count( $slugs ) > 0 ) {
            return $this->prepareFallbackTemplates( $name, $slugs );
        }

        $prepared_name = str_replace( '.', '/', $name );

        // Directory name.
        $name = pathinfo( $prepared_name, PATHINFO_DIRNAME );

        // If slugs are set using a filter, we use them.
        $slugs = (array) ( $this->slugs[ $name ] ?? [] );

        // If none of the slug types are set, we will define our own using the view file name as the main slug.
        if ( 0 === count( $slugs ) ) {
            $slugs = [ pathinfo( $prepared_name, PATHINFO_FILENAME ) ];
        }

        // If none of the slugs or hierarchy is set, we will fallback to using default templates.
        return $this->prepareFallbackTemplates( $name, $slugs );
    }

}
