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

use Twig\TwigFunction;
use Joomla\Registry\Registry;
use Twig\Extension\AbstractExtension;

/**
 * Registry integration for Twig.
 *
 * @since  __DEPLOY_VERSION__
 */
final class JRegistry extends AbstractExtension
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
			new TwigFunction('jregistry', [$this, 'getRegistry'])
		];
	}

	/**
	 * @param   null  $data
	 *
	 * @return Registry
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getRegistry($data = null)
	{
		return new Registry($data);
	}

	/**
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jregistry';
	}
}
