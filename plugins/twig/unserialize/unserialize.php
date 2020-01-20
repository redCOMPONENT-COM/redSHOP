<?php
/**
 * @package     Redshop.Library
 * @subpackage  Plugin.Juser
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') || die;

JLoader::import('redshop.library');

use Redshop\Extension\Unserialize;
use Redshop\Plugin\BaseTwigPlugin;
use Twig\Environment;

/**
 * Plugin to allow to use unserialize in twig.
 *
 * @since  1.0.0
 */
class PlgTwigUnserialize extends BaseTwigPlugin
{
	/**
	 * Triggered after environment has been loaded.
	 *
	 * @param   Environment  $environment  Loaded environment
	 * @param   array        $params       Optional parameters
	 *
	 * @return  void
	 */
	public function onTwigAfterLoad(Environment $environment, $params)
	{
		$environment->addExtension(new Unserialize);
	}
}
