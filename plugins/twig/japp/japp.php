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

/**
 * Plugin to integrate japp extension with twig.
 *
 * @since  1.0.0
 */
class PlgTwigJapp extends BaseTwigPlugin
{
	/**
	 * @param   Environment  $environment
	 * @param   array        $params
	 *
	 *
	 * @throws Exception
	 * @since  1.0.0
	 */
	public function onTwigAfterLoad(Environment $environment, $params = [])
	{
		$environment->addGlobal('japp', Factory::getApplication());
	}
}
