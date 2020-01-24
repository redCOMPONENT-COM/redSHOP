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
 * Twig filter to unserialize data.
 *
 * @since  1.0.0
 */
final class Unserialize extends AbstractExtension
{
	/**
	 * Inject our filter.
	 *
	 * @return  array
	 */
	public function getFilters() : array
	{
		return [
			new TwigFilter('unserialize', 'unserialize')
		];
	}

	/**
	 * Get the name of this extension.
	 *
	 * @return  string
	 */
	public function getName() : string
	{
		return 'junserialize';
	}
}
