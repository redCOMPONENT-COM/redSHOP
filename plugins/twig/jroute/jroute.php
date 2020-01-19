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

use Redshop\Extension\JRoute;
use Redshop\Plugin\BaseTwigPlugin;
use Twig\Environment;

/**
 * Plugin to integrate jroute extension with twig.
 *
 * @since  1.0.0
 */
class PlgTwigJroute extends BaseTwigPlugin
{
	/**
	 * Triggered after environment has been loaded.
	 *
	 * @param   Environment  $environment  Loaded environment
	 * @param   array        $params       Optional parameters
	 *
	 * @return  void
	 */
	public function onTwigAfterLoad(Environment $environment, $params = [])
	{
		$environment->addExtension(new JRoute);
	}
}
