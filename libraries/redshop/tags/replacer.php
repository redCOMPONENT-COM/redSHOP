<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Main tags replacer class
 *
 * @since  2.1
 */
class RedshopTagsReplacer
{
	/**
	 * Execute replace
	 *
	 * @param   string  $key       Key
	 * @param   string  $template  Input template
	 * @param   array   $data      Extra data
	 *
	 * @return  string
	 *
	 * @since   2.1
	 */
	public static function _($key, $template, $data = array())
	{
		if (strpos($key, '.') !== false)
		{
			$execute = explode('.', $key);
		}
		else
		{
			$execute[0] = $key;

			// By default we call replace for general replace
			$execute[1] = 'replace';
		}

		$className = 'RedshopTagsSections' . ucfirst($execute[0]);

		// Make sure we have this sub class before use it
		if (class_exists($className))
		{
			$class = new $className($template, $data);

			return call_user_func(array($class, $execute[1]));
		}

		return $template;
	}
}
