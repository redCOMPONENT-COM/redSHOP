<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Extension
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Twig\Extension;

defined('_JEXEC') || die;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension to improve array handling.
 *
 * @since  1.0.0
 */
final class JArray extends AbstractExtension
{
	/**
	 * Inject our filter.
	 *
	 * @return  array
	 */
	public function getFilters() : array
	{
		return [
			new TwigFilter('to_array', [$this, 'toArray']),
			new TwigFilter('array_values', 'array_values')
		];
	}

	/**
	 * Cast variable to array.
	 *
	 * @param   mixed  $var  Var to cast
	 *
	 * @return  array
	 */
	public function toArray($var) : array
	{
		return (array) ($var);
	}

	/**
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName() : string
	{
		return 'jarray';
	}
}
