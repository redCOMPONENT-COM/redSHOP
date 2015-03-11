<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class product_category
{
	public $_cats = array();

	public $_table_prefix = null;

	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
	}

	public function list_all($name, $category_id, $selected_categories = Array(), $size = 1, $toplevel = false, $multiple = false, $disabledFields = array(), $width = 250)
	{
		$db = JFactory::getDbo();
		$html = '';
		$q = "SELECT category_parent_id FROM " . $this->_table_prefix . "category_xref ";

		if ($category_id)
		{
			$q .= "WHERE category_child_id = " . (int) $category_id;
		}

		$db->setQuery($q);
		$cats = $db->loadObjectList();

		if ($cats)
		{
			$selected_categories[] = $cats[0]->category_parent_id;
		}

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id = str_replace('[]', '', $name);
		$html .= "<select class=\"inputbox\" style=\"width: " . $width . "px;\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";

		if ($toplevel)
		{
			$html .= "<option value=\"0\"> -Top- </option>\n";
		}

		$html .= $this->list_tree($category_id, '0', '0', $selected_categories, $disabledFields);
		$html .= "</select>\n";

		return $html;
	}

	public function list_tree($category_id = "", $cid = '0', $level = '0', $selected_categories = Array(), $disabledFields = Array(), $html = '')
	{
		$db = JFactory::getDbo();
		$level++;

		$q = "SELECT category_id, category_child_id,category_name "
			. "FROM " . $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx "
			. "WHERE cx.category_parent_id='$cid' "
			. "AND c.category_id=cx.category_child_id "
			. "AND c.category_id != " . (int) $category_id . " "
			. "ORDER BY c.category_name ASC";
		$db->setQuery($q);
		$cats = $db->loadObjectList();

		for ($x = 0; $x < count($cats); $x++)
		{
			$cat = $cats[$x];
			$child_id = $cat->category_child_id;

			if ($child_id != $cid)
			{
				$selected = ($child_id == $category_id) ? "selected=\"selected\"" : "";

				if ($selected == "" && @$selected_categories[$child_id] == "1")
				{
					$selected = "selected=\"selected\"";
				}

				if (is_array($selected_categories))
				{
					if (in_array($child_id, $selected_categories))
					{
						$selected = "selected=\"selected\"";
					}
				}

				$disabled = '';

				if (in_array($child_id, $disabledFields))
				{
					$disabled = 'disabled="disabled"';
				}

				if ($disabled != '' && stristr($_SERVER['HTTP_USER_AGENT'], 'msie'))
				{
					// IE7 suffers from a bug, which makes disabled option fields selectable
				}
				else
				{
					$html .= "<option $selected $disabled value=\"$child_id\">" . str_repeat('- ', $level) . $cat->category_name . "</option>";
				}
			}

			$html .= $this->list_tree($category_id, $child_id, $level, $selected_categories, $disabledFields);
		}

		return $html;
	}

	/**
	 * Get Category List Array
	 *
	 * @param   int  $category_id  First category level in filter
	 * @param   int  $cid          Current category id
	 *
	 * @return array|mixed
	 *
	 * @deprecated  1.5 Use RedshopHelperCategory::getCategoryListArray instead
	 */
	public function getCategoryListArray($category_id = 0, $cid = 0)
	{
		return RedshopHelperCategory::getCategoryListArray($category_id, $cid);
	}

	/**
	 * Get Category List Reverse Array
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return array
	 *
	 * @deprecated  1.5  Use RedshopHelperCategory::getCategoryListReverseArray instead
	 */
	public function getCategoryListReverceArray($cid = '0')
	{
		return RedshopHelperCategory::getCategoryListReverseArray($cid);
	}

	public function _buildContentOrderBy()
	{
		$db = JFactory::getDbo();
		global $context;
		$app = JFactory::getApplication();

		$filter_order = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering'));
		$filter_order_Dir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}

	public function getParentCategories()
	{
		$db = JFactory::getDbo();
		$query = 'SELECT DISTINCT c.category_name, c.category_id'
			. ' FROM ' . $this->_table_prefix . 'category c '
			. ' LEFT JOIN ' . $this->_table_prefix . 'category_xref AS x ON c.category_id = x.category_child_id '
			. 'WHERE x.category_parent_id=0 ';
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get category tree
	 *
	 * @param :$cid - categoryid
	 *
	 * @return: array - categoryid
	 *
	 */

	public function getCategoryTree($cid = '0')
	{
		if (!isset($GLOBALS['catlist']))
		{
			$GLOBALS['catlist'] = array();
		}

		$db = JFactory::getDbo();
		$q = "SELECT c.category_id,c.category_name "
			. ",cx.category_child_id,cx.category_parent_id "
			. "FROM " . $this->_table_prefix . "category_xref as cx, " . $this->_table_prefix . "category as c "
			. "WHERE cx.category_parent_id = " . (int) $cid . " "
			. "AND c.category_id = cx.category_child_id";

		$db->setQuery($q);

		$cats = $db->loadObjectList();

		for ($x = 0; $x < count($cats); $x++)
		{
			$cat = $cats[$x];
			$parent_id = $cat->category_child_id;
			$GLOBALS['catlist'][] = $cat;
			$this->getCategoryTree($parent_id);
		}

		return $GLOBALS['catlist'];
	}

	public function getCategoryProductList($cid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT p.product_id AS id "
			. "FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS x ON x.product_id = p.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON x.category_id = c.category_id "
			. "WHERE c.category_id = " . (int) $cid . " and p.published =1 ";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	public function CheckAccessoryExists($product_id, $accessory_id)
	{
		$db = JFactory::getDbo();

		$query = "SELECT accessory_id,product_id "
			. "FROM " . $this->_table_prefix . "product_accessory  "
			. "WHERE product_id = " . (int) $product_id . " and child_product_id = " . (int) $accessory_id;

		$db->setQuery($query);
		$result = $db->loadObjectList();

		if (count($result) > 0)
		{
			$return = $result[0]->accessory_id;
		}
		else
		{
			$return = 0;
		}

		return $return;
	}
}
