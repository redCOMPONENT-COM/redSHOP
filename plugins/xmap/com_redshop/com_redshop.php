<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Handles redSHOP product and categories links
 *
 * @package  Redshop
 * @since    2.5
 */
class Xmap_Com_Redshop
{
	/**
	 * This function is called before a menu item is printed. We use it to set the
	 * proper uniqueid for the items
	 *
	 * @param   object  &$node    Menu item to be "prepared"
	 * @param   array   &$params  The extension params
	 *
	 * @return  void
	 */
	public static function prepareMenuItem(&$node, &$params)
	{
		$link_query = parse_url($node->link);
		$app = JFactory::getApplication('site');

		parse_str(html_entity_decode($link_query['query']), $link_vars);

		$catid  = self::getParam($link_vars, 'cid', 0);
		$prodid = self::getParam($link_vars, 'pid', 0);

		$menu       = $app->getMenu();
		$menuparams = $menu->getParams($node->id);
		$manid      = $menuparams->get('manufacturerid');

		if ($prodid && $catid)
		{
			$node->uid        = 'com_redshopc' . $catid . 'p' . $prodid;
			$node->expandible = false;
		}
		elseif ($catid)
		{
			$node->uid        = 'com_redshopc' . $catid;
			$node->expandible = true;
		}
		elseif ($prodid && $manid)
		{
			$node->uid        = 'com_redshopm' . $manid . 'p' . $prodid;
			$node->expandible = false;
		}
		elseif ($manid)
		{
			$node->uid        = 'com_redshopm' . $manid;
			$node->expandible = true;
		}
	}

	/**
	 * Get the content tree for this kind of content
	 *
	 * @param   object  $xmap     Xmap Data Object
	 * @param   object  $parent   Parent data object array
	 * @param   array   &$params  Xmap Parameter Array
	 *
	 * @return  boolean
	 */
	public static function getTree($xmap, $parent, &$params)
	{
		$link_query = parse_url($parent->link);
		parse_str(html_entity_decode($link_query['query']), $link_vars);
		$app = JFactory::getApplication('site');

		$view = self::getParam($link_vars, 'view', '');

		$link_query = parse_url($parent->link);
		parse_str(html_entity_decode($link_query['query']), $link_vars);
		$catid = intval(self::getParam($link_vars, 'cid', 0));

		$menu                       = $app->getMenu();
		$menuparams                 = $menu->getParams($parent->id);
		$manid                      = $menuparams->get('manufacturerid');
		$params['Itemid']           = intval(self::getParam($link_vars, 'Itemid', $parent->id));
		$include_products           = self::getParam($params, 'include_products', 1);
		$include_products           = ($include_products == 1 || ($include_products == 2 && $xmap->view == 'xml') || ($include_products == 3 && $xmap->view == 'html'));
		$params['include_products'] = $include_products;
		$priority                   = self::getParam($params, 'cat_priority', $parent->priority);
		$changefreq                 = self::getParam($params, 'cat_changefreq', $parent->changefreq);
		$params['max_product']          = (int) self::getParam($params, 'max_product', 0);

		if ($priority == '-1')
		{
			$priority = $parent->priority;
		}

		if ($changefreq == '-1')
		{
			$changefreq = $parent->changefreq;
		}

		$params['cat_priority']   = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority   = self::getParam($params, 'prod_priority', $parent->priority);
		$changefreq = self::getParam($params, 'prod_changefreq', $parent->changefreq);

		if ($priority == '-1')
		{
			$priority = $parent->priority;
		}

		if ($changefreq == '-1')
		{
			$changefreq = $parent->changefreq;
		}

		$params['prod_priority']   = $priority;
		$params['prod_changefreq'] = $changefreq;

		switch ($view)
		{
			case 'category':
				self::getCategoryTree($xmap, $parent, $params, $catid);
				break;
			case 'manufacturers':
				self::getManufacturerTree($xmap, $parent, $params, $manid);
				break;
		}

		return true;
	}

	/**
	 * Get all redSHOP category tree
	 *
	 * @param   object   $xmap     Xmap Data Object
	 * @param   object   $parent   Parent data object array
	 * @param   array    &$params  Xmap Parameter Array
	 * @param   integer  $catid    redSHOP Category Id
	 *
	 * @return  void
	 */
	static protected function getCategoryTree($xmap, $parent, &$params, $catid = 0)
	{
		$db      = JFactory::getDbo();
		$objhelper     = redhelper::getInstance();
		$producthelper = productHelper::getInstance();

		$query = $db->getQuery(true)
			->select('a.id, a.name, a.created_date')
			->from($db->qn('#__redshop_category', 'a'))
			->where('a.published = 1')
			->where('b.parent_id = ' . (int) $catid)
			->order('a.ordering ASC, a.name ASC');

		if ($rows = $db->setQuery($query)->loadObjectList())
		{
			$xmap->changeLevel(1);

			foreach ($rows as $row)
			{
				// Get Category Menu Itemid
				$cItemid = RedshopHelperUtility::getCategoryItemid($row->id);

				if ($cItemid != "")
				{
					$params['Itemid'] = $cItemid;
				}

				$node = new stdclass;
				$node->id = $parent->id;
				$node->uid = $parent->uid . 'c' . $row->id;
				$node->browserNav = $parent->browserNav;
				$node->name = stripslashes($row->name);
				$node->modified = strtotime($row->created_date);
				$node->priority = $params['cat_priority'];
				$node->changefreq = $params['cat_changefreq'];
				$node->expandible = false;
				$node->link = "index.php?option=com_redshop&view=category&cid=$row->id&layout=detail&Itemid=" . $params['Itemid'];

				if ($xmap->printNode($node) !== false)
				{
					self::getCategoryTree($xmap, $parent, $params, $row->id);
				}
			}

			$xmap->changeLevel(-1);
		}

		if ($params['include_products'])
		{
			$query->clear()
				->select('a.product_id, a.update_date, a.product_name, a.publish_date, a.product_thumb_image, a.product_full_image, b.category_id, d.created_date')
				->from($db->qn('#__redshop_product', 'a'))
				->leftJoin($db->qn('#__redshop_product_category_xref', 'b') . ' ON a.product_id = b.product_id')
				->leftJoin($db->qn('#__redshop_category', 'd') . ' ON b.category_id = d.id')
				->where('a.published = 1')
				->where('b.category_id = ' . (int) $catid)
				->where('a.product_parent_id = 0')
				->order('a.product_name');

			if ($params['max_product'])
			{
				$limit = (int) $params['max_product'];
			}
			else
			{
				$limit = 0;
			}

			if ($rows = $db->setQuery($query, 0, $limit)->loadObjectList())
			{
				$xmap->changeLevel(1);

				foreach ($rows as $row)
				{
					// Get Product Menu Itemid
					$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

					if (count($ItemData) > 0)
					{
						$params['Itemid'] = $ItemData->id;
					}
					else
					{
						$params['Itemid'] = RedshopHelperUtility::getItemId($row->product_id, $row->category_id);
					}

					$node = new stdclass;
					$node->id = $parent->id;
					$node->uid = $parent->uid . 'c' . $row->category_id . 'p' . $row->product_id;
					$node->browserNav = $parent->browserNav;
					$node->priority = $params['prod_priority'];
					$node->changefreq = $params['prod_changefreq'];
					$node->name = $row->product_name;
					$node->modified = strtotime($row->update_date);
					$node->expandible = false;
					$node->link = "index.php?option=com_redshop&view=product&pid=$row->product_id&cid=$row->category_id&Itemid=" . $params['Itemid'];

					if ($xmap->printNode($node) !== false)
					{
						self::getProductTree($xmap, $parent, $params, $row->product_id, $row->category_id);
					}
				}

				$xmap->changeLevel(-1);
			}
		}
	}

	/**
	 * Get all redSHOP Products
	 *
	 * @param   object   $xmap      Xmap Data Object
	 * @param   object   $parent    Parent data object array
	 * @param   array    &$params   Xmap Parameter Array
	 * @param   integer  $prod      redSHOP Product Id
	 * @param   integer  $category  redSHOP Category Id
	 * @param   integer  $manid     redSHOP Manufacture Id
	 *
	 * @return  void
	 */
	static protected function getProductTree($xmap, $parent, &$params, $prod = 0, $category = 0, $manid = 0)
	{
		if (!$params['include_products'])
		{
			return;
		}

		$db = JFactory::getDbo();
		$objhelper     = redhelper::getInstance();
		$producthelper = productHelper::getInstance();

		$query = $db->getQuery(true)
			->select('prod.*, cpx.category_id')
			->from($db->qn('#__redshop_product', 'prod'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'cpx') . ' ON cpx.product_id = prod.product_id')
			->where('prod.published = 1');

		if ($manid > 0)
		{
			$query->where('prod.manufacturer_id = ' . (int) $manid);
		}
		else
		{
			$query->where('prod.product_parent_id = ' . (int) $prod);
		}

		if ($category)
		{
			$query->where('cpx.category_id = ' . (int) $category);
		}

		if ($params['max_product'])
		{
			$limit = (int) $params['max_product'];
		}
		else
		{
			$limit = 0;
		}

		if ($childproducts = $db->setQuery($query, 0, $limit)->loadObjectList())
		{
			$xmap->changeLevel(1);

			foreach ($childproducts as $row)
			{
				// Get Product Menu Itemid
				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

				if (count($ItemData) > 0)
				{
					$params['Itemid'] = $ItemData->id;
				}
				else
				{
					$params['Itemid'] = RedshopHelperUtility::getItemId($row->product_id, $row->category_id);
				}

				$node = new stdclass;
				$node->id = $parent->id;
				$node->uid = ($manid > 0) ? $parent->uid . 'm' . $manid . 'p' . $row->product_id : $parent->uid . 'c' . $row->category_id . 'p' . $row->product_id;
				$node->browserNav = $parent->browserNav;
				$node->priority = $params['prod_priority'];
				$node->changefreq = $params['prod_changefreq'];
				$node->name = $row->product_name;
				$node->modified = strtotime($row->update_date);
				$node->expandible = false;
				$node->link = "index.php?option=com_redshop&view=product&pid=$row->product_id&cid=$row->category_id&Itemid=" . $params['Itemid'];

				if ($xmap->printNode($node) !== false)
				{
					self::getProductTree($xmap, $parent, $params, $row->product_id, $category);
				}
			}

			$xmap->changeLevel(-1);
		}
	}

	/**
	 * Get All Manufacturers
	 *
	 * @param   object   $xmap    Xmap Data Object
	 * @param   object   $parent  Parent data object array
	 * @param   array    $params  Xmap Parameter Array
	 * @param   integer  $manid   redSHOP Manufacture Id
	 *
	 * @return  void
	 */
	static protected function getManufacturerTree($xmap, $parent, $params, $manid = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('manufacturer_id, manufacturer_name')
			->from($db->qn('#__redshop_manufacturer'))
			->where('published = 1');

		if ($manid)
		{
			$query->where('manufacturer_id = ' . (int) $manid);
		}

		if ($manufacturers = $db->setQuery($query)->loadObjectList())
		{
			$xmap->changeLevel(1);

			foreach ($manufacturers as $manufacturer)
			{
				$manid   = $manufacturer->manufacturer_id;
				$manName = $manufacturer->manufacturer_name;

				$node             = new stdclass;
				$node->id         = $manid;
				$node->uid        = $parent->uid . 'm' . $manid;
				$node->browserNav = $parent->browserNav;
				$node->name       = stripslashes($manName);
				$node->modified   = intval(time());
				$node->priority   = $params['cat_priority'];
				$node->changefreq = $params['cat_changefreq'];
				$node->expandible = true;
				$node->link       = "index.php?option=com_redshop&view=manufacturers&layout=products&mid=$manid&Itemid=" . $params['Itemid'];

				if ($xmap->printNode($node) !== false)
				{
					self::getProductTree($xmap, $parent, $params, 0, 0, $manid);
				}
			}

			$xmap->changeLevel(-1);
		}
	}

	static protected function getParam($arr, $name, $def)
	{
		return JArrayHelper::getValue($arr, $name, $def, '');
	}
}
