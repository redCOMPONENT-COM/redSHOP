<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Shipping.Rate
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer
 *
 * @since  2.0
 */
class RedshopTagsReplacer
{
	
	public static function _()
	{
		$args = func_get_args();
		$content = array_shift($args);
		$key = array_shift($args);
		
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
		
		if (class_exists($className))
		{
			$r = new ReflectionClass($className);
			
			$class = $r->newInstanceArgs($args);
			
			return $class->$execute[1]($content);
		}
		
		return $content;
	}
}