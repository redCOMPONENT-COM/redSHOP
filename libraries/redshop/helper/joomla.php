<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Joomla! helper
 *
 * @since  2.0.0.6
 */
class RedshopHelperJoomla
{

	/**
	 * Get redSHOP manifest value
	 *
	 * @param   string  $name     Name param
	 * @param   mixed   $default  Default return value if value is not exists
	 *
	 * @return  mixed
	 */
	public static function getManifestValue($name, $default = null)
	{
		static $oldManifest;

		if (!isset($oldManifest))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('manifest_cache')
				->from($db->qn('#__extensions'))
				->where('type = ' . $db->q('component'))
				->where('element = ' . $db->q('com_redshop'));
			$oldManifest = json_decode($db->setQuery($query)->loadResult(), true);
		}

		if (isset($oldManifest[$name]))
		{
			return $oldManifest[$name];
		}
		else
		{
			return $default;
		}
	}
}