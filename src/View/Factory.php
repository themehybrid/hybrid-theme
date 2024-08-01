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

    protected $slugs = [];

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

        // Use the slugs to build the template hierarchy.
        foreach ( $slugs as $slug ) {
            $templates[] = "{$view}/{$slug}";
        }

        // Add a 'default' template if it is not already in the slugs.
        if ( ! in_array( 'default', $slugs ) ) {
            $templates[] = "{$view}/default";
        }

        // Include the original view in the templates if it represents a directory path
        // and is not already present in the list of templates.
        if ( strpos( $view, '/' ) !== false && ! in_array( $view, $templates ) ) {
            $templates[] = $view;
        }

        // If the view path does not exist (e.g., 'menu/primary'), add a final fallback 'default' template
        // (e.g., 'menu/default') to ensure there is a default fallback option.
        if ( strpos( $view, '/' ) !== false ) {
            $templates[] = pathinfo( $view, PATHINFO_DIRNAME ) . '/default';
        }

        return apply_filters( 'hybrid/theme/view/template/fallback', $templates, $view, $slugs );
    }

    /**
     * Retrieves an array of slugs based on the specified type.
     *
     * @param string $type The type of slugs to retrieve ('template' or 'post').
     * @return array The array of slugs.
     */
    public function slugs( $type = 'template' ) {
        return 'template' === $type ? templateHierarchy() : postHierarchy();
    }

    /**
     * Get the first view that actually exists from the given list.
     *
     * @param \Hybrid\Contracts\Arrayable|array $data
     * @param array                             $mergeData
     * @return \Hybrid\Contracts\View\View
     * @throws \InvalidArgumentException
     */
    public function firstView( array $views, $data = [], $mergeData = [] ) {
        $view = Arr::first( $views, fn( $view ) => $this->exists( $view ) );

        if ( ! $view ) {
            throw new \InvalidArgumentException( 'None of the views in the given array exist.' );
        }

        return parent::make( $view, $data, $mergeData );
    }

    /**
     * Adjusts view parameters to accept slugs inline or via filter.
     *
     * @return void
     */
    public function prepareParams() {
        $this->prepareTemplateSlugs();
        $this->prepareTemplateHierarchy();
    }

    /**
     * Applies the template hierarchy filter if not already applied.
     *
     * @return void
     */
    public function prepareTemplateHierarchy() {
        static $appliedHierarchy = false;

        if ( $appliedHierarchy ) {
            return;
        }

        $this->hierarchy = apply_filters( 'hybrid/theme/view/template/hierarchy', $this->hierarchy );

        $appliedHierarchy = true;
    }

    /**
     * Applies the template slugs filter if not already applied.
     *
     * @return void
     */
    public function prepareTemplateSlugs() {
        static $appliedSlugs = false;

        if ( $appliedSlugs ) {
            return;
        }

        $this->slugs = apply_filters( 'hybrid/theme/view/template/slugs', $this->slugs );

        $appliedSlugs = true;
    }

    /**
     * Prepares the views by determining the appropriate template hierarchy or slugs.
     *
     * @return array The array of templates to be used.
     */
    public function prepareViews() {
        $name = $this->viewParams['view'];

        // Use inline hierarchy if set.
        $hierarchy = (array) ( $this->viewParams['data']['hierarchy'] ?? [] );

        if ( count( $hierarchy ) > 0 ) {
            return $hierarchy;
        }

        // Use view hierarchy set via filter if available.
        $hierarchy = (array) ( $this->hierarchy[ $name ] ?? [] );

        if ( count( $hierarchy ) > 0 ) {
            return $hierarchy;
        }

        // Use inline slugs if set.
        $slugs = (array) ( $this->viewParams['data']['slugs'] ?? [] );

        if ( count( $slugs ) > 0 ) {
            return $this->prepareFallbackTemplates( $name, $slugs );
        }

        $prepared_name = str_replace( '.', '/', $name );

        // Directory name.
        $name = pathinfo( $prepared_name, PATHINFO_DIRNAME );

        // Use slugs set via filter if available.
        $slugs = (array) ( $this->slugs[ $name ] ?? [] );

        // If no slugs are set and the view is a directory path, use the file name as the main slug.
        if ( 0 === count( $slugs ) && strpos( $prepared_name, '/' ) !== false ) {
            $slugs = [ pathinfo( $prepared_name, PATHINFO_FILENAME ) ];
        } else {
            // If no subdirectories are found in the view name, reset to the original view.
            $name = $prepared_name;
        }

        // Fallback to default templates if no slugs or hierarchy are set.
        return $this->prepareFallbackTemplates( $name, $slugs );
    }

}
