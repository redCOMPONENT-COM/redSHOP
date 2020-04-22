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

use Joomla\CMS\Factory;
use Redshop\Plugin\BaseTwigPlugin;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

/**
 * Plugin to activate twig cache.
 *
 * @since  1.0.0
 */
class PlgTwigCache extends BaseTwigPlugin
{
    /**
     * Cache status inherited.
     *
     * @const
     * @since  1.1.0
     */
    const STATUS_INHERITED = 0;

    /**
     * Cache status enabled.
     *
     * @const
     * @since  1.1.0
     */
    const STATUS_ENABLED = 1;

    /**
     * Cache status disabled.
     *
     * @const
     * @since  1.1.0
     */
    const STATUS_DISABLED = 2;

    /**
     * @param   Environment      $environment
     * @param   LoaderInterface  $loader
     * @param                    $options
     *
     * @throws Exception
     */
    public function onTwigBeforeLoad(Environment $environment, LoaderInterface $loader, &$options)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $cacheFolder = Factory::getConfig()->get('cache_path', JPATH_CACHE) . '/twig';

        if ($cacheFolder !== JPATH_CACHE) {
            $cacheFolder .= '/' . (Factory::getApplication()->isSite() ? 'site' : 'admin');
        }

        $options['cache'] = $cacheFolder;
    }

    /**
     * Check if the cache is enabled.
     *
     * @return  boolean
     *
     * @since   1.1.0
     */
    private function isEnabled()
    {
        $status = (int)$this->params->get('enabled', 0);

        if (self::STATUS_INHERITED === $status) {
            return (0 !== (int)Factory::getConfig()->get('caching'));
        }

        return $status === self::STATUS_ENABLED;
    }
}
