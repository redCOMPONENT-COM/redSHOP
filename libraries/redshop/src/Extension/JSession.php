<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Extension;

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JSession integration for Twig.
 *
 * @since  __DEPLOY_VERSION__
 */
final class JSession extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jsession', [Factory::class, 'getSession'])
		];
	}

	/**
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jsession';
	}
}
