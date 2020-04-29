<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Twig\Traits;

defined('_JEXEC') || die;

use Redshop\Traits;

/**
 * For classes having a linked app.
 *
 * @since  1.1.0
 */
trait HasTwigRenderer
{
    /**
     * Render a layout of this module.
     *
     * @param   string  $layout  Layout to render.
     * @param   array   $data    Optional data for the layout.
     *
     * @return  string
     */
    public function render($layout, array $data = [])
    {
        return $this->getRenderer()->render($layout, array_merge($this->getLayoutData(), $data));
    }

    /**
     * Get the module renderer.
     *
     * @return  Twig
     */
    public function getRenderer(): Twig
    {
        return Twig::instance();
    }

    /**
     * Get the data that will be sent to renderer.
     *
     * @return  array
     */
    abstract protected function getLayoutData();
}
