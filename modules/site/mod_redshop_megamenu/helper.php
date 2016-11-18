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

		$menu  = JFactory::getApplication()->getMenu();
		$items = $menu->getItems('menutype', $params->get('menutype'));
		$firstItem = array();

		foreach ($items as $i => $item)
		{
			if ($item->level == 1)
			{
				$firstItem[$i] = new StdClass;
				$firstItem[$i]->category_id = !empty($item->query['cid']) ? $item->query['cid'] : 0;
				$firstItem[$i]->category_name = $item->title;
				$firstItem[$i]->link = JRoute::_($item->link . '&Itemid=' . $item->id);
				$firstItem[$i]->published = 1;
				$firstItem[$i]->menu_parent_id = $item->id;
			}
		}

		$firstItem = array_merge(array(), $firstItem);
	
		$categories = RedshopHelperCategory::getCategoryListArray($categoryId);

		if (empty($categories))
		{
			static::$categories[$categoryId] = array();

			return static::$categories[$categoryId];
		}

		$subCategories = array();

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
		}

		foreach ($firstItem as $k => $item)
		{
			foreach ($subCategories as $i => $subCat)
			{
				if ($item->category_id == $subCat->category_id)
				{
					$firstItem[$k]->category_child_id = $subCat->category_child_id;
				}
			}
		}

		// Get 1 more sub-level of sub-categories
		foreach ($firstItem as $subCategory)
		{
			$subCategory->sub_cat = array();

			if ($subCategory->category_id != 0)
			{
				foreach ($categories as $category)
				{
					if ($category->category_parent_id == $subCategory->category_id)
					{

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
				}

				foreach ($subCategory->sub_cat as $key => $subCat)
				{
					foreach ($categories as $category)
					{
						if ($category->category_parent_id == $subCat->category_id)
						{
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
			}
			else
			{
				$subMenu = $menu->getItems('parent_id', $subCategory->menu_parent_id);

				if (!empty($subMenu))
				{
					foreach ($subMenu as $menuItem)
					{
						$subMenuItem = new StdClass;
						$subMenuItem->category_id = $menuItem->id;
						$subMenuItem->category_name = $menuItem->title;
						$subMenuItem->link = JRoute::_($menuItem->link . '&Itemid=' . $menuItem->id);
						$subMenuItem->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
						$subMenuItem->menu_parent_id = $menuItem->id;
						$childMenu = $menu->getItems('parent_id', $menuItem->id);

						if (!empty($childMenu))
						{
							foreach ($childMenu as $childItem)
							{
								$childMenuItem = new StdClass;
								$childMenuItem->category_id = $childItem->id;
								$childMenuItem->category_name = $childItem->title;
								$childMenuItem->link = JRoute::_($childItem->link . '&Itemid=' . $childItem->id);
								$childMenuItem->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
								$subMenuItem->sub_cat[] = $childMenuItem;
							}
						}

						$subCategory->sub_cat[] = $subMenuItem;
					}
				}
			}
		}

		static::$categories[$categoryId] = $firstItem;


		return static::$categories[$categoryId];
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
				elseif ($sortBy == 'name')
				{
					return strcmp($a->category_name, $b->category_name);
				}
			}
		);

		if ($sortDestination == 'desc')
		{
			array_reverse($categories);
		}

		foreach ($categories as $category)
		{
			if (!empty($category->sub_cat))
			{
				self::sortCategories($category->sub_cat, $sortBy, $sortDestination);
			}
		}
	}
}
