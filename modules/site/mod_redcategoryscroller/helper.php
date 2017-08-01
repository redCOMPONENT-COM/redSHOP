<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redcategoryscroller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

JLoader::import('redshop.library');

/**
 * Helper for category scroll module
 *
 * @since  2.0.0
 */
class ModRedCategoryScrollerHelper
{
	/**
	 * Method for get necessary data
	 *
	 * @param   Registry  $params  Module params
	 *
	 * @return  array
	 *
	 * @since   2.0.0
	 */
	public static function getList(&$params)
	{
		$limit      = (int) $params->get('NumberOfCategory', 5);
		$sortMethod = $params->get('ScrollSortMethod', 'random');
		$isFeatured = (boolean) $params->get('featuredCategory', false);
		$categoryId = JFactory::getApplication()->input->getInt('cid', 0);
		$hierarchyTree = RedshopHelperCategory::getCategoryListArray($categoryId, $categoryId);

		$cid = array();

		for ($i = 0, $in = count($hierarchyTree); $i < $in; $i++)
		{
			$cid[] = $hierarchyTree[$i]->id;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1');

		switch ($sortMethod)
		{
			case 'random':
				$query->order('RAND()');
				break;
			case 'oldest':
				$query->order($db->qn('category_pdate') . ' ASC');
				break;
			default:
				$query->order($db->qn('category_pdate') . ' DESC');
				break;
		}


		if ($limit)
		{
			$db->setQuery($query, 0, $limit);
		}
		else
		{
			$db->setQuery($query);
		}

		return $db->loadObjectList();
	}
}
