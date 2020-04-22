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

use Twig\Environment;
use Redshop\Plugin\BaseTwigPlugin;
use Redshop\Extension\JRegistry as TwigJRegistry;

/**
 * Plugin to use Joomla Registry class in twig.
 *
 * @since  1.0.0
 */
class PlgTwigJregistry extends BaseTwigPlugin
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
		$environment->addExtension(new TwigJRegistry);
	}
}
