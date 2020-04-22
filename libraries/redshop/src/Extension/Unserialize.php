<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


namespace Redshop\Extension;

defined('_JEXEC') || die;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig filter to unserialize data.
 *
 * @since  2.1.5
 */
final class Unserialize extends AbstractExtension
{
	/**
	 *
	 * @return array
	 *
	 * @since 2.1.5
	 */
	public function getFilters() : array
	{
		return [
			new TwigFilter('unserialize', 'unserialize')
		];
	}

	/**
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function getName() : string
	{
		return 'junserialize';
	}
}
