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
use Redshop\Extension\JLanguage as TwigJLang;
use Redshop\Plugin\BaseTwigPlugin;
use Twig\Environment;

/**
 * Plugin to integrate jlang extension with twig.
 *
 * @since  1.0.0
 */
class PlgTwigJlang extends BaseTwigPlugin
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
		$environment->addExtension(new TwigJLang);
		$environment->addGlobal('jlang', Factory::getLanguage());
	}
}
