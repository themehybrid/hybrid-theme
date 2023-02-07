<?php

namespace Hybrid\Theme\View;

use Hybrid\View\Factory as ViewFactory;

use function Hybrid\Template\Hierarchy\hierarchy as templateHierarchy;
use function Hybrid\Theme\Post\hierarchy as postHierarchy;

class Factory extends ViewFactory {

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string                            $view
     * @param  \Hybrid\Contracts\Arrayable|array $data
     * @param  array                             $mergeData
     * @return \Hybrid\Contracts\View\View
     */
    public function make( $view, $data = [], $mergeData = [] ) {

        $slugs = (array) ( $data['slugs'] ?? [] );

        if ( ! $slugs ) {
            return parent::make( $view, $data, $mergeData );
        }

        unset( $data['slugs'] );

        $templates = [];

        // Uses the slugs to build a hierarchy.
        foreach ( $slugs as $slug ) {
            $templates[] = "{$view}/{$slug}";
        }

        // Add in a `default` template.
        if ( ! in_array( 'default', $slugs ) ) {
            $templates[] = "{$view}/default";
        }

        // Fallback to `{$name}` as a last resort.
        $templates[] = "{$view}";

        return parent::first( $templates, $data, $mergeData );
    }

    /**
     * Returns the array of slugs.
     *
     * @return array
     */
    public function slugs( $type = 'template' ) {
        return $type === 'template' ? templateHierarchy() : postHierarchy();
    }

}
