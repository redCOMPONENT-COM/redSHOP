<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Section;

defined('_JEXEC') or die;

/**
 * Section helper class
 *
 * @package     Redshop\Section
 *
 * @since       2.0.7
 */
class Helper
{
	/**
	 * Get section
	 *
	 * @param   string  $section  Section name
	 * @param   int     $id       Section id
	 *
	 * @return  mixed|null
	 */
	public static function getSection($section = '', $id = 0)
	{
		// To avoid killing queries do not allow queries that get all the items
		if ($id != 0 && !empty($section))
		{
			switch ($section)
			{
				case 'product':
					return \RedshopHelperProduct::getProductById($id);
					break;
				case 'category':
					return \RedshopEntityCategory::getInstance($id)->getItem();
					break;
				default:
					$db = \JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_' . $section))
						->where($db->qn($section . '_id') . ' = ' . (int) $id);

					return  $db->setQuery($query)->loadObject();
			}
		}

		return null;
	}
}
