<?php
/**
 * View contract.
 *
 * View classes represent a template partial, generally speaking. Their purpose
 * should be to find a template file and render or display the output.
 *
 * @package   HybridTheme
 * @link      https://themehybrid.com/hybrid-theme
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2024, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Theme\View\Legacy\Contracts;

use Hybrid\Contracts\Displayable;
use Hybrid\Contracts\Renderable;

/**
 * View interface.
 */
interface View extends Displayable, Renderable {

    /**
     * Returns the array of slugs.
     *
     * @return array
     */
    public function slugs();

    /**
     * Returns the absolute path to the template file.
     *
     * @return string
     */
    public function template();

}
