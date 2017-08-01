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
 * Helper for mod_redshop_megamenu
 *
 * @package     Redshopb.Site
 * @subpackage  mod_redshop_megamenu
 * @since       1.6.21
 */
class ModRedshopMegaMenuHelper
{
	protected static $categories = array();

	/**
	 * Get a list of parents categories items.
	 *
	 * @param   \Joomla\Registry\Registry  &$params  The module options.
	 *
	 * @return  array            Categories tree
	 */
	public static function getCategories($params)
	{
		$categoryId = $params->get('category', RedshopHelperCategory::getRootId());
		$end = $params->get('endLevel', 2);

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
				$firstItem[$i]->menu_anchor_css = $item->anchor_css;
				$firstItem[$i]->menu_anchor_title = $item->anchor_title;
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
			if ($category->parent_id != $categoryId)
			{
				continue;
			}

			$categoryMenuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category->id . '&manufacturer_id=0', true);

			$category->name = str_replace('- ', '', $category->name);
			$category->link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
				. $category->id . '&manufacturer_id=0&Itemid='
				. !empty($categoryMenuItem ? $categoryMenuItem->id : 0));
			$subCategories[] = $category;
		}

		foreach ($firstItem as $k => $item)
		{
			foreach ($subCategories as $i => $subCat)
			{
				if ($item->category_id == $subCat->id)
				{
					$firstItem[$k]->category_child_id = $subCat->id;
					$firstItem[$k]->level = 1;
				}
			}
		}

		foreach ($firstItem as $subCategory)
		{
			$subCategory->sub_cat = array();

			if ($subCategory->category_id != 0)
			{
				$subCategory->sub_cat = self::getListForRedshopMegamenu($categories, $subCategory->category_id, 1, $end);
			}
			else
			{
				$subMenu = $menu->getItems('parent_id', $subCategory->menu_parent_id);
				$subCategory->sub_cat = self::getListForJoomlaMegamenu($subMenu, $subCategory->menu_parent_id, 1, $end);
			}
		}

		static::$categories[$categoryId] = $firstItem;

		return static::$categories[$categoryId];
	}

	/**
	 * Get Joomla menu
	 *
	 * @param   object  $items     Menu list
	 * @param   string  $parentId  Product parent id
	 * @param   int     $level     Menu level
	 * @param   int     $end       Last menu level
	 *
	 * @return  array
	 */
	public static function getListForJoomlaMegamenu($items, $parentId, $level, $end)
	{
		if (empty($items))
		{
			return null;
		}

		$menu  = JFactory::getApplication()->getMenu();
		$subItem = array();
		$key = 0;
		$level++;

		foreach ($items as $item)
    	{
    		if ($item->parent_id == $parentId)
        	{
        		$subItem[$key] = new StdClass;

        		if ($end && $level > $end)
				{
					unset($subItem[$key]);
					continue;
				}

        		$subItem[$key]->category_id = $item->id;
				$subItem[$key]->category_name = $item->title;
				$subItem[$key]->link = JRoute::_($item->link . '&Itemid=' . $item->id);
				$subItem[$key]->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
				$subItem[$key]->menu_parent_id = $item->id;
				$subItem[$key]->level = $level;
				$childMenu = $menu->getItems('parent_id', $item->id);

        		$subItem[$key]->sub_cat = self::getListForJoomlaMegamenu($childMenu, $item->id, $level, $end);	
        		$key++;
        	}
    	}

    	return $subItem;
	}

	/**
	 * Get redSHOP Categories 
	 *
	 * @param   object  $items     Category list
	 * @param   string  $parentId  Product parent id
	 * @param   int     $level     Category level
	 * @param   int     $end       Last Category level
	 *
	 * @return  array
	 */
	public static function getListForRedshopMegamenu($items, $parentId, $level, $end)
	{
		if (empty($items))
		{
			return null;
		}

		$menu  = JFactory::getApplication()->getMenu();
		$subItem = array();
		$key = 0;
		$level++;

		foreach ($items as $item)
    	{
    		if ($item->parent_id == $parentId)
        	{
        		$subItem[$key] = new StdClass;

        		if ($end && $level > $end)
				{
					unset($subItem[$key]);
					continue;
				}

        		$categoryMenuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $item->id . '&manufacturer_id=0', true);

				$subItem[$key]->category_name = str_replace('- ', '', $item->name);
				$subItem[$key]->category_id = $item->id;
				$subItem[$key]->link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
				. $item->id . '&manufacturer_id=0&Itemid='
				. !empty($categoryMenuItem ? $categoryMenuItem->id : 0));
				$subItem[$key]->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
				$subItem[$key]->level = $level;
				$subItem[$key]->sub_cat = array();

				if (!empty($item->category_full_image)
					&& (strpos($item->category_full_image, '.jpg') == true
					|| strpos($item->category_full_image, '.png') == true
					|| strpos($item->category_full_image, '.jpeg') == true))
				{
					$subItem[$key]->image = $item->category_full_image;
				}

				$subItem[$key]->sub_cat = self::getListForRedshopMegamenu($items, $item->id, $level, $end);
				$key++;
        	}
        }

        return $subItem;
	}

	/**
	 * Display one redshop level
	 *
	 * @param   array   &$items      Redshop list items
	 * @param   object  $parentItem  Joomla parent item
	 * @param   int     $level       Current level display
	 *
	 * @return  int
	 */
	public static function displayLevel(&$items, $parentItem, $level = 1)
	{
		if ($level != 1)
		{
			echo '<ul class="unstyled">';
		}

		for ($i = 0, $ci = count($items); $i < $ci; $i++)
		{
			$parent = (!empty($items[$i]->sub_cat[0]->category_id)) ? ' parent ' : '';
			$subLevel = $level + 1;

			if ($level == 1)
			{
				echo '<div id="accordion' . $items[$i]->category_id . '-' . $parentItem->category_id . '" class="accordion span3">';
				echo '<div class="accordion-group item-' . $items[$i]->category_id . '-' . $parentItem->category_id . ' level-item-' . $subLevel . $parent . '">';
				echo '<div class="accordion-heading">';
				echo '<a class="categoryLink" href="' . $items[$i]->link . '">';
				echo '<div class="thumbnail">';
				echo '<div class="megaMenuEmptyBox">';

				if (!empty($items[$i]->image))
				{
					echo '<img src="' . JUri::root() . 'components/com_redshop/assets/images/category/' . $items[$i]->image . '" />';
				}

				echo '</div>';
				echo '</div>';
				echo '<span class="menuLinkTitle">' . $items[$i]->category_name . '</span>';

				if (!empty($items[$i]->sub_cat[0]->category_id))
				{
					$attr = array(
						'href' => '#collapseAnchor' . $items[$i]->category_id . '-' . $parentItem->category_id . '-' . $subLevel,
						'data-parent' => '#accordion' . $items[$i]->category_id . '-' . $parentItem->category_id,
						'data-toggle' => 'collapse',
						'class' => 'accordion-toggle collapsed'
					);
					echo '<a ' . self::getLinkAttributes($attr) . '>+</a>';
				}

				echo '</a>';
				echo '<div id="collapseAnchor' . $items[$i]->category_id . '-' . $parentItem->category_id . '-' . $subLevel . '" class="accordion-body collapse">';
				echo '<div class="accordion-inner">';

				if (!empty($items[$i]->sub_cat[0]->category_id))
				{
					echo self::displayLevel($items[$i]->sub_cat, $items[$i], $subLevel);
				}
			}
			else
			{
				echo '<li class="item-' . $items[$i]->category_id . '-' . $parentItem->category_id . ' level-item-' . $subLevel . '">';
				echo '<a class="categoryLink" href="' . $items[$i]->link . '">';
				echo $items[$i]->category_name;
				echo '</a>';

				if (!empty($items[$i]->sub_cat[0]->category_id))
				{
					echo self::displayLevel($items[$i]->sub_cat, $items[$i], $subLevel);
				}

				echo '</li>';	
			}

			if ($level == 1)
			{
				echo '</div></div></div></div></div>';
			}
		}

		if ($level != 1)
		{
			echo '</ul>';
		}
	}

	/**
	 * Build link attributes to string
	 *
	 * @param   array  $attr  Array link attributes
	 *
	 * @return  string
	 */
	public static function getLinkAttributes($attr)
	{
		return implode(' ',
			array_map(
				function ($v, $k)
				{
					return sprintf('%s="%s"', $k, $v);
				},
				$attr,
				array_keys($attr)
			)
		);
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
