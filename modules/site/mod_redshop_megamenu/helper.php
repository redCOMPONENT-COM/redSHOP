<?php
/**
 * @package     Redshop.Site
 * @subpackage  mod_redshop_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_menu
 *
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 * @since       1.6.21
 */
class ModRedshopMegaMenuHelper
{
	protected static $categories = array();

	/**
	 * Get a list of parents categories items.
	 *
	 * @param   object  $params  module params
	 *
	 * @return  array            Categories tree
	 */
	public static function getCategories($params)
	{
		$categoryId = $params->get('category', 0);

		if (isset(static::$categories[$categoryId]))
		{
			return static::$categories[$categoryId];
		}

		$menu = JFactory::getApplication()->getMenu();
		$joomlaMenu = array_unique($params->get('menu', array()));
		$items = array();
	
		foreach ($joomlaMenu as $key => $value)
		{
			$items[]   = $menu->getItems('id', $value, true);
		}

		$categories = RedshopHelperCategory::getCategoryListArray($categoryId);

		if (empty($categories))
		{
			static::$categories[$categoryId] = array();

			return static::$categories[$categoryId];
		}

		$subCategories = array();
		$ordering = array();

		// Get first sub-categories of parent category
		foreach ($categories as $category)
		{
			if ($category->category_parent_id != $categoryId)
			{
				continue;
			}

			$categoryMenuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category->category_id . '&manufacturer_id=0', true);

			$category->category_name = str_replace('- ', '', $category->category_name);
			$category->link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
				. $category->category_id . '&manufacturer_id=0&Itemid='
				. !empty($categoryMenuItem ? $categoryMenuItem->id : 0));

			$subCategories[] = $category;
			$ordering[] = $category->ordering;
		}

		// Get 1 more sub-level of sub-categories
		foreach ($subCategories as $subCategory)
		{
			$subCategory->sub_cat = array();

			foreach ($categories as $category)
			{
				if ($category->category_parent_id != $subCategory->category_id)
				{
					continue;
				}

				$categoryMenuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $subCategory->category_id . '&manufacturer_id=0', true);

				$category->category_name = str_replace('- ', '', $category->category_name);
				$category->link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
				. $subCategory->category_id . '&manufacturer_id=0&Itemid='
				. !empty($categoryMenuItem ? $categoryMenuItem->id : 0));
				$category->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
				$category->sub_cat = array();

				if (!empty($category->category_full_image)
					&& (strpos($category->category_full_image, '.jpg') == true
					|| strpos($category->category_full_image, '.png') == true
					|| strpos($category->category_full_image, '.jpeg') == true))
				{
					$category->image = $category->category_full_image;
				}

				$subCategory->sub_cat[] = $category;
			}

			foreach ($subCategory->sub_cat as $key => $subCat)
			{
				foreach ($categories as $category)
				{
					if ($category->category_parent_id != $subCat->category_id)
					{
						continue;
					}

					$categoryMenuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $subCategory->category_id . '&manufacturer_id=0', true);

					$category->category_name = str_replace('- ', '', $category->category_name);
					$category->link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
					. $subCat->category_id . '&manufacturer_id=0&Itemid='
					. !empty($categoryMenuItem ? $categoryMenuItem->id : 0));
					$category->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
					$category->sub_cat = array();

					if (!empty($category->category_full_image)
						&& (strpos($category->category_full_image, '.jpg') == true
						|| strpos($category->category_full_image, '.png') == true
						|| strpos($category->category_full_image, '.jpeg') == true))
					{
						$category->image = $category->category_full_image;
					}

					$subCategory->sub_cat[$key]->sub_cat[] = $category;
				}
			}
		}

		static::$categories[$categoryId] = $subCategories;

		$menuItem = array();
		$k = max($ordering);

		foreach ($items as $i => $item)
		{
			$menuItem[$i] = new StdClass;
			$menuItem[$i]->category_id = $item->id;
			$menuItem[$i]->category_name = $item->title;
			$menuItem[$i]->link = $item->link . '&Itemid=' . $item->id;
			$menuItem[$i]->ordering = $k++;
			$menuItem[$i]->published = 1;
		}

		return array_merge(static::$categories[$categoryId], $menuItem);
	}

	/**
	 * Method for sort categories
	 *
	 * @param   array   &$categories      Categories for sort.
	 * @param   string  $sortBy           Sort ordering.
	 * @param   string  $sortDestination  Sort destination.
	 *
	 * @return  void
	 */
	public static function sortCategories(&$categories = array(), $sortBy = 'name', $sortDestination = 'asc')
	{
		if (empty($categories))
		{
			return;
		}

		usort(
			$categories,
			function($a, $b) use (&$sortBy) {
				if ($sortBy == 'id')
				{
					return (int) $a->category_id > (int) $b->category_id;
				}
				elseif ($sortBy == 'ordering')
				{
					return (int) $a->ordering > (int) $b->ordering;
				}
				else
				{
					return strcmp($a->category_name, $b->category_name);
				}
			}
		);

		if ($sortDestination == 'desc')
		{
			array_reverse($categories);
		}
	}
}
