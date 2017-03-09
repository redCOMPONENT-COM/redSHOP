<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Tags
 *
 * @since  2.0.3
 */
class RedshopHelperTags
{
	/**
	 * [getManufacturer description]
	 *
	 * @param   [int]  $id  Id of manufacturer
	 * 
	 * @return [type] [description]
	 */
	public static function getTag($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$db->qn(
				['tags_name']
			)
		)
		->from($db->qn('#__redshop_product_tags'))
		->where($db->qn('tags_id') . ' = ' . (int) $id);

		$db->setQuery($query);

		return $db->loadObject();
	}
}
