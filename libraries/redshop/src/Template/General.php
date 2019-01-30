<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Template;

defined('_JEXEC') or die;

/**
 * @package     Redshop\Template
 *
 * @since       2.1.0
 */
class General
{

	/**
	 * @param   array  $tags     Array of tags
	 * @param   string $template Template
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function replaceBlank($tags, $template)
	{
		if (empty($tags))
		{
			return $template;
		}

		$replace = array_fill(0, count($tags) - 1, '');

		return str_replace($tags, $replace, $template);
	}
}
