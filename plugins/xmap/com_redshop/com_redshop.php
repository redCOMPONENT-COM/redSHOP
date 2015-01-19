<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperProduct');

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
	static function prepareMenuItem(&$node, &$params)
	{
		$link_query = parse_url($node->link);

		parse_str(html_entity_decode($link_query['query']), $link_vars);

		$catid  = self::getParam($link_vars, 'cid', 0);
		$prodid = self::getParam($link_vars, 'pid', 0);

		$menu       = JSite::getMenu();
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
	static function getTree($xmap, $parent, &$params)
	{
		$link_query = parse_url($parent->link);
		parse_str(html_entity_decode($link_query['query']), $link_vars);

		$view = self::getParam($link_vars, 'view', '');

		$link_query = parse_url($parent->link);
		parse_str(html_entity_decode($link_query['query']), $link_vars);
		$catid = intval(self::getParam($link_vars, 'cid', 0));

		$menu                       = JSite::getMenu();
		$menuparams                 = $menu->getParams($parent->id);
		$manid                      = $menuparams->get('manufacturerid');
		$params['Itemid']           = intval(self::getParam($link_vars, 'Itemid', $parent->id));
		$include_products           = self::getParam($params, 'include_products', 1);
		$include_products           = ($include_products == 1 || ($include_products == 2 && $xmap->view == 'xml') || ($include_products == 3 && $xmap->view == 'html'));
		$params['include_products'] = $include_products;
		$priority                   = self::getParam($params, 'cat_priority', $parent->priority);
		$changefreq                 = self::getParam($params, 'cat_changefreq', $parent->changefreq);

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
		$database      = JFactory::getDbo();
		$objhelper     = new redhelper;
		$producthelper = new producthelper;

		static $urlBase;

		if (!isset($urlBase))
		{
			$urlBase = JURI::base();
		}

		$query = "SELECT a.category_id, a.category_name, a.category_pdate "
			. "\n FROM #__redshop_category AS a, #__redshop_category_xref AS b "
			. "\n WHERE a.published ='1' "
			. "\n AND b.category_parent_id = $catid "
			. "\n AND a.category_id=b.category_child_id "
			. "\n ORDER BY a.ordering ASC, a.category_name ASC";

		$database->setQuery($query);
		$rows = $database->loadObjectList();
		$xmap->changeLevel(1);

		foreach ($rows as $row)
		{
			// Get Category Menu Itemid
			$cItemid = $objhelper->getCategoryItemid($row->category_id);

			if ($cItemid != "")
			{
				$params['Itemid'] = $cItemid;
			}

			$node             = new stdclass;
			$node->id         = $parent->id;
			$node->uid        = $parent->uid . 'c' . $row->category_id;
			$node->browserNav = $parent->browserNav;
			$node->name       = stripslashes($row->category_name);
			$node->modified   = strtotime($row->category_pdate);
			$node->priority   = $params['cat_priority'];
			$node->changefreq = $params['cat_changefreq'];
			$node->expandible = false;
			$node->link       = "index.php?option=com_redshop&view=category&cid=$row->category_id&layout=detail&Itemid=" . $params['Itemid'];

			if ($xmap->printNode($node) !== false)
			{
				self::getCategoryTree($xmap, $parent, $params, $row->category_id);
			}
		}

		$xmap->changeLevel(-1);

		if ($params['include_products'])
		{
			$query = "SELECT a.product_id,a.update_date, a.product_name,a.publish_date, a.product_thumb_image, a.product_full_image, b.category_id, d.category_pdate "
				. "\n FROM #__redshop_product AS a, #__redshop_product_category_xref AS b, #__redshop_category AS  d"
				. "\n WHERE a.published='1'"
				. "\n AND b.category_id=$catid "
				. "\n AND a.product_parent_id=0 "
				. "\n AND a.product_id=b.product_id "
				. "\n AND b.category_id=d.category_id "
				. "\n ORDER BY a.product_name";

			$database->setQuery($query);
			$rows = $database->loadObjectList();
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
					$params['Itemid'] = $objhelper->getItemid($row->product_id, $row->category_id);
				}

				$node             = new stdclass;
				$node->id         = $parent->id;
				$node->uid        = $parent->uid . 'c' . $row->category_id . 'p' . $row->product_id;
				$node->browserNav = $parent->browserNav;
				$node->priority   = $params['prod_priority'];
				$node->changefreq = $params['prod_changefreq'];
				$node->name       = $row->product_name;
				$node->modified   = strtotime($row->update_date);
				$node->expandible = false;
				$node->link       = "index.php?option=com_redshop&view=product&pid=$row->product_id&cid=$row->category_id&Itemid=" . $params['Itemid'];

				if ($xmap->printNode($node) !== false)
				{
					self::getProductTree($xmap, $parent, $params, $row->product_id, $row->category_id);
				}
			}

			$xmap->changeLevel(-1);
		}
	}

	/**
	 * Get all redSHOP Products
	 *
	 * @param   object   $xmap       Xmap Data Object
	 * @param   object   $parent     Parent data object array
	 * @param   array    &$params    Xmap Parameter Array
	 * @param   integer  $prod       redSHOP Product Id
	 * @param   integer  $category   redSHOP Category Id
	 * @param   integer  $manid      redSHOP Manufacture Id
	 *
	 * @return  void
	 */
	static protected function getProductTree($xmap, $parent, &$params, $prod = 0, $category = 0, $manid = 0)
	{
		$database      = JFactory::getDbo();
		$objhelper     = new redhelper;
		$producthelper = new producthelper;

		if ($manid > 0)
		{
			$sql = "SELECT prod.* FROM #__redshop_product AS prod WHERE manufacturer_id = '" . $manid . "' AND published = 1";
		}
		else
		{
			$sql = "SELECT prod.*, cat.category_pdate, cat.category_name  FROM #__redshop_product AS prod, #__redshop_category AS cat WHERE prod.product_parent_id='" . $prod . "'";
		}

		$database->setQuery($sql);
		$childproducts = $database->loadObjectList();

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
				$params['Itemid'] = $objhelper->getItemid($row->product_id, $row->category_id);
			}

			$node             = new stdclass;
			$node->id         = $parent->id;
			$node->uid        = ($manid > 0) ? $parent->uid . 'm' . $manid . 'p' . $row->product_id : $parent->uid . 'c' . $category . 'p' . $row->product_id;
			$node->browserNav = $parent->browserNav;
			$node->priority   = $params['prod_priority'];
			$node->changefreq = $params['prod_changefreq'];
			$node->name       = $row->product_name;
			$node->modified   = strtotime($row->update_date);
			$node->expandible = false;
			$node->link       = "index.php?option=com_redshop&view=product&pid=$row->product_id&cid=$category&Itemid=" . $params['Itemid'];

			if ($xmap->printNode($node) !== false)
			{
				self::getProductTree($xmap, $parent, $params, $row->product_id, $category);
			}
		}

		$xmap->changeLevel(-1);
	}

	/**
	 * Get All Manufacturers
	 *
	 * @param   object   $xmap      Xmap Data Object
	 * @param   object   $parent    Parent data object array
	 * @param   array    $params    Xmap Parameter Array
	 * @param   integer  $manid     redSHOP Manufacture Id
	 *
	 * @return  void
	 */
	static protected function getManufacturerTree($xmap, $parent, $params, $manid = 0)
	{
		$db = JFactory::getDbo();

		$whereBy = ($manid > 0) ? " AND manufacturer_id = " . $manid : "";

		$query = "SELECT manufacturer_id, manufacturer_name FROM #__redshop_manufacturer "
			. " WHERE `published` = 1 "
			. $whereBy
			. " ORDER BY `ordering`";
		$db->setQuery($query);
		$manufacturers = $db->loadObjectList();

		if (count($manufacturers) > 0)
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
