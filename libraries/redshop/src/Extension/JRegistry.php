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
 * @since  4.0.0
 */
final class JRegistry extends AbstractExtension
{
	/**
	 * Inject functions.
	 *
	 * @return  array
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('jregistry', [$this, 'getRegistry'])
		];
	}

	/**
	 * Get the registry instance.
	 *
	 * @param   mixed   $data  Data to initialise the registry
	 *
	 * @return  Registry
	 */
	public function getRegistry($data = null)
	{
		return new Registry($data);
	}

	/**
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName() : string
	{
		return 'jregistry';
	}
}
