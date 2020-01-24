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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension to improve array handling.
 *
 * @since  __DEPLOY_VERSION__
 */
final class JArray extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getFilters() : array
	{
		return [
			new TwigFilter('to_array', [$this, 'toArray']),
			new TwigFilter('array_values', 'array_values')
		];
	}

	/**
	 * @param $var
	 *
	 * @return array
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function toArray($var) : array
	{
		return (array) ($var);
	}

	/**
	 *
	 * @return string
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getName() : string
	{
		return 'jarray';
	}
}
