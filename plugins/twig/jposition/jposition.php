<?php
/**
 * @package     Redshopb.Plugin
 * @subpackage  redshop_pdf
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') || die;

JLoader::import('redshop.library');

use Redshop\Extension\JPosition as TwigJPos;
use Redshop\Plugin\BaseTwigPlugin;
use Twig\Environment;

/**
 * Plugin to load module positions in twig.
 *
 * @since  1.0.0
 */
class PlgTwigJposition extends BaseTwigPlugin
{
    /**
     * @param   Environment  $environment
     * @param   array        $params
     *
     *
     * @since 1.0.0
     */
    public function onTwigAfterLoad(Environment $environment, $params = [])
    {
        $environment->addExtension(new TwigJPos);
    }
}
