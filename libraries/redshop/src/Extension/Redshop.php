<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


namespace Redshop\Extension;

defined('_JEXEC') || die;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig filter to unserialize data.
 *
 * @since  2.1.5
 */
final class Redshop extends AbstractExtension
{
    /**
     *
     * @return array
     *
     * @since 2.1.5
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('redconfig', [$this, 'getConfig'])
        ];
    }

    /**
     * @param $config
     *
     * @return string
     *
     * @since version
     */
    public function getConfig($config)
    {
        return \Redshop::getConfig()->get($config);
    }

    /**
     *
     * @return array
     *
     * @since 2.1.5
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('redSHOPCall', [$this, 'redshopFunction']),
        ];
    }

    public function redshopFunction($className, $method, $args)
    {
        return call_user_func_array("$className::$method", $args);
    }

    /**
     *
     * @return string
     *
     * @since 2.1.5
     */
    public function getName(): string
    {
        return 'redSHOPCall';
    }
}
