<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class product_category
{
	public $_cats = array();

	public $_table_prefix = null;

	public function __construct()
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
	}

	public function list_all($name, $category_id, $selected_categories = Array(), $size = 1, $toplevel = true, $multiple = false, $disabledFields = array(), $width = 250)
	{
		$db = JFactory::getDBO();
		$html = '';
		$q = "SELECT category_parent_id FROM " . $this->_table_prefix . "category_xref ";

		if ($category_id)
		{
			$q .= "WHERE category_child_id='$category_id'";
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
		$db = JFactory::getDBO();
		$level++;

		$q = "SELECT category_id, category_child_id,category_name "
			. "FROM " . $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx "
			. "WHERE cx.category_parent_id='$cid' "
			. "AND c.category_id=cx.category_child_id "
			. "AND c.category_id != '$category_id' "
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
					$html .= "<option $selected $disabled value=\"$child_id\">\n";

					for ($i = 0; $i < $level; $i++)
					{
						$html .= "&#151;";
					}

					$html .= "|$level|";
					$html .= "&nbsp;" . $cat->category_name . "</option>";
				}
			}

			$html .= $this->list_tree($category_id, $child_id, $level, $selected_categories, $disabledFields);
		}

		return $html;
	}

	public function getCategoryListArray($category_id = "", $cid = '0', $level = '0')
	{
		global $context;

		$app = JFactory::getApplication();

		$GLOBALS['catlist'] = array();
		$db = JFactory::getDBO();
		$level++;
		$view = JRequest::getVar('view');

		$category_main_filter = $app->getUserStateFromRequest($context . 'category_main_filter', 'category_main_filter', 0);

		$orderby = 'ORDER BY c.category_name';

		if ($level == 1 && $category_id)
		{
			$cid = $category_id;
		}

		if ($view == 'category')
		{
			$orderby = $this->_buildContentOrderBy();
		}

		if ($category_main_filter)
		{
			$and = " AND category_name like '%" . $category_main_filter . "%' ";
		}
		else
		{
			$and = " AND cx.category_parent_id='$cid' ";
		}

		$q = "SELECT c.category_id, cx.category_child_id, cx.category_parent_id "
			. ",c.category_name,c.category_description,c.published,ordering "
			. "FROM " . $this->_table_prefix . "category AS c "
			. " ," . $this->_table_prefix . "category_xref AS cx "
			. "WHERE c.category_id=cx.category_child_id "
			. $and
			. $orderby;
		$db->setQuery($q);
		$cats = $db->loadObjectList();

		if ($category_main_filter)
		{
			return $cats;
		}

		for ($x = 0; $x < count($cats); $x++)
		{
			$html = '';
			$cat = $cats[$x];
			$child_id = $cat->category_child_id;

			if ($child_id != $cid)
			{
				$catlist[] = $cat;

				for ($i = 0; $i < $level; $i++)
				{
					$html .= "&nbsp;&nbsp;";
				}

				$html .= "&nbsp;" . $cat->category_name;
			}

			$cat->category_name = $html;
			$this->_cats[] = $cat;

			$this->getCategoryListArray($category_id, $child_id, $level);
		}

		return $this->_cats;
	}

	public function getCategoryListReverceArray($cid = '0')
	{
		$db = JFactory::getDBO();
		$q = "SELECT c.category_id,c.category_name "
			. ",cx.category_child_id,cx.category_parent_id "
			. "FROM " . $this->_table_prefix . "category_xref as cx, " . $this->_table_prefix . "category as c "
			. "WHERE cx.category_child_id='" . $cid . "' "
			. "AND c.category_id = cx.category_parent_id";
		$db->setQuery($q);
		$cats = $db->loadObjectList();

		for ($x = 0; $x < count($cats); $x++)
		{
			$cat = $cats[$x];
			$parent_id = $cat->category_parent_id;
			$GLOBALS['catlist_reverse'][] = $cat;
			$this->getCategoryListReverceArray($parent_id);
		}

		return $GLOBALS['catlist_reverse'];
	}

	public function _buildContentOrderBy()
	{
		global $context;
		$app = JFactory::getApplication();
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function getParentCategories()
	{
		$db = JFactory::getDBO();
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
		$db = JFactory::getDBO();
		$q = "SELECT c.category_id,c.category_name "
			. ",cx.category_child_id,cx.category_parent_id "
			. "FROM " . $this->_table_prefix . "category_xref as cx, " . $this->_table_prefix . "category as c "
			. "WHERE cx.category_parent_id='" . $cid . "' "
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
		$db = JFactory::getDBO();
		$query = "SELECT p.product_id AS id "
			. "FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS x ON x.product_id = p.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON x.category_id = c.category_id "
			. "WHERE 1=1 AND c.category_id = '" . $cid . "' and p.published =1 ";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	public function CheckAccessoryExists($product_id, $accessory_id)
	{
		$db = JFactory::getDBO();

		$query = "SELECT accessory_id,product_id "
			. "FROM " . $this->_table_prefix . "product_accessory  "
			. "WHERE 1=1 AND product_id = '" . $product_id . "' and child_product_id ='" . $accessory_id . "'";

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
