<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminStockroom');
JLoader::load('RedshopHelperAdminShipping');
JLoader::load('RedshopHelperProduct');

class RedshopModelProduct extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_categorytreelist = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->_context = 'product_id';
		$this->_table_prefix = '#__redshop_';

		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$search_field = $app->getUserStateFromRequest($this->_context . 'search_field', 'search_field', '');
		$keyword = $app->getUserStateFromRequest($this->_context . 'keyword', 'keyword', '');
		$category_id = $app->getUserStateFromRequest($this->_context . 'category_id', 'category_id', 0);
		$product_sort = $app->getUserStateFromRequest($this->_context . 'product_sort', 'product_sort', 0);

		$this->setState('product_sort', $product_sort);
		$this->setState('search_field', $search_field);
		$this->setState('keyword', $keyword);
		$this->setState('category_id', $category_id);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

			// Product parent - child - format generation
			$products = $this->_data;

			if (!is_array($products))
			{
				$products = array();
			}

			// Establish the hierarchy of the menu
			$children = array();

			// First pass - collect children
			foreach ($products as $v)
			{
				$pt           = $v->parent;
				$v->parent_id = $v->parent;
				$list         = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}

			// Second pass - get an indent list of the items
			$this->_data = JHTML::_('menu.treerecurse', 0, '', array(), $children, max(0, 9));
			$this->_data = array_values($this->_data);
		}

		return $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		static $items;

		if (isset($items))
		{
			return $items;
		}

		$orderby = $this->_buildContentOrderBy();
		$limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');
		$search_field = $this->getState('search_field');
		$keyword = $this->getState('keyword');
		$category_id = $this->getState('category_id');
		$product_sort = $this->getState('product_sort');
		$keyword = addslashes($keyword);
		$arr_keyword = array();

		$where = '';
		$and = '';

		if (!empty($product_sort))
		{
			if ($product_sort == 'p.published')
			{
				$and = 'AND p.published=1 ';
			}
			elseif ($product_sort == 'p.unpublished')
			{
				$and = 'AND p.published=0 ';
			}
			elseif ($product_sort == 'p.product_on_sale')
			{
				$and = 'AND p.product_on_sale=1 ';
			}
			elseif ($product_sort == 'p.product_special')
			{
				$and = 'AND p.product_special=1 ';
			}
			elseif ($product_sort == 'p.expired')
			{
				$and = 'AND p.expired=1 ';
			}
			elseif ($product_sort == 'p.not_for_sale')
			{
				$and = 'AND p.not_for_sale=1 ';
			}
			elseif ($product_sort == 'p.product_not_on_sale')
			{
				$and = 'AND p.product_on_sale=0 ';
			}
			elseif ($product_sort == 'p.sold_out')
			{
				$query_prd = "SELECT DISTINCT(p.product_id),p.attribute_set_id FROM " . $this->_table_prefix . "product AS p ";
				$tot_products = $this->_getList($query_prd);
				$product_id_array = '';
				$producthelper = new producthelper;
				$products_stock = $producthelper->removeOutofstockProduct($tot_products);
				$final_product_stock = $this->getFinalProductStock($products_stock);

				if (count($final_product_stock) > 0)
				{
					$product_id_array = implode(',', $final_product_stock);
				}
				else
				{
					$product_id_array = "0";
				}

				$and = "AND p.product_id IN (" . $product_id_array . ")";
			}
		}

		if (trim($keyword) != '')
		{
			$arr_keyword = preg_split("/[\s-]+/", $keyword);
		}

		if ($search_field != 'pa.property_number')
		{
			for ($k = 0; $k < count($arr_keyword); $k++)
			{
				if ($k == 0)
				{
					$where .= " AND ( ";
				}

				if ($search_field == 'p.name_number')
				{
					$where .= " p.product_name LIKE '%$arr_keyword[$k]%' OR p.product_number LIKE '%$arr_keyword[$k]%' ";
				}
				else
				{
					$where .= $search_field . " LIKE '%$arr_keyword[$k]%'  ";
				}

				if ($k != count($arr_keyword) - 1)
				{
					if ($search_field == 'p.name_number')
					{
						$where .= ' OR ';
					}
					else
					{
						$where .= ' AND ';
					}
				}

				if ($k == count($arr_keyword) - 1)
				{
					$where .= " )  ";
				}
			}
		}

		if ($category_id)
		{
			$where .= " AND c.category_id = '" . $category_id . "'  ";
		}

		if ($where == '' && $search_field != 'pa.property_number')
		{

			$query = "SELECT p.product_id,p.product_id AS id,p.product_name,p.product_name AS treename,p.product_name
			AS title,p.product_price,p.product_parent_id,p.product_parent_id AS parent_id,p.product_parent_id AS parent  "
				. ",p.published,p.visited,p.manufacturer_id,p.product_number ,p.checked_out,p.checked_out_time,p.discount_price "
				. ",p.product_template "
				. " FROM " . $this->_table_prefix . "product AS p "
				. "WHERE 1=1 " . $and . $orderby;
		}
		else
		{
			$query = "SELECT p.product_id AS id,p.product_id,p.product_name,p.product_name AS treename,p.product_name AS
			name,p.product_name AS title,p.product_parent_id,p.product_parent_id AS parent,p.product_price " . ",
			p.published,p.visited,p.manufacturer_id,p.product_number,p.product_template,p.checked_out,p.checked_out_time,p.discount_price " . ",
			x.ordering , x.category_id "
			. " FROM " . $this->_table_prefix . "product AS p " . "LEFT JOIN " . $this->_table_prefix . "product_category_xref
			AS x ON x.product_id = p.product_id " . "LEFT JOIN " . $this->_table_prefix . "category AS c ON x.category_id = c.category_id ";

			if ($search_field == 'pa.property_number' && $keyword != '')
			{
				$query .= "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.product_id = p.product_id "
						. "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS pa ON pa.attribute_id = a.attribute_id "
						. "LEFT JOIN " . $this->_table_prefix . "product_subattribute_color AS ps ON ps.subattribute_id = pa.property_id ";
			}

			$query .= "WHERE 1=1 ";

			if ($search_field == 'pa.property_number' && $keyword != '')
			{
				$query .= "AND (pa.property_number LIKE '%$keyword%'  OR ps.subattribute_color_number LIKE '%$keyword%') ";
			}

			$query .= $where . $and . " GROUP BY p.product_id ";
			$query .= $orderby;
		}

		return $query;
	}

	public function getFinalProductStock($product_stock)
	{
		if (count($product_stock) > 0)
		{
			$product = array();

			for ($i = 0; $i < count($product_stock); $i++)
			{
				$product[] = $product_stock[$i]->product_id;
			}

			$product_id = implode(',', $product);
			$query_prd = "SELECT DISTINCT(p.product_id) FROM " . $this->_table_prefix . "product AS p WHERE p.product_id NOT IN(" . $product_id . ")";
			$this->_db->setQuery($query_prd);
			$final_products = $this->_db->loadColumn();

			return $final_products;
		}
	}

	public function _buildContentOrderBy()
	{
		$db  = JFactory::getDbo();
		$app = JFactory::getApplication();

		$category_id = $this->getState('category_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		if ($category_id)
		{
			$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'x.ordering');
		}
		else
		{
			$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'p.product_id');

			if ($filter_order == 'x.ordering')
			{
				$filter_order = 'p.product_id';
			}
		}

		$orderby = " ORDER BY " . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}

	public function MediaDetail($pid)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'media  WHERE section_id ="' . $pid . '" AND media_section = "product"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function listedincats($pid)
	{
		$query = 'SELECT c.category_name FROM ' . $this->_table_prefix . 'product_category_xref as ref, '
			. $this->_table_prefix . 'category as c WHERE product_id ="' . $pid
			. '" AND ref.category_id=c.category_id ORDER BY c.category_name';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function product_template($template_id, $product_id, $section)
	{
		$redTemplate = new Redtemplate;

		if ($section == 1 || $section == 12)
		{
			$template_desc = $redTemplate->getTemplate("product", $template_id);
		}
		else
		{
			$template_desc = $redTemplate->getTemplate("category", $template_id);
		}

		if (count($template_desc) == 0)
		{
			return;
		}

		$template = $template_desc[0]->template_desc;
		$str = array();
		$sec = explode(',', $section);

		for ($t = 0; $t < count($sec); $t++)
		{
			$inArr[] = "'" . $sec[$t] . "'";
		}

		$in = implode(',', $inArr);
		$q = "SELECT field_name,field_type,field_section from " . $this->_table_prefix . "fields where field_section in (" . $in . ") ";
		$this->_db->setQuery($q);
		$fields = $this->_db->loadObjectlist();

		for ($i = 0; $i < count($fields); $i++)
		{
			if (strstr($template, "{" . $fields[$i]->field_name . "}"))
			{
				if ($fields[$i]->field_section == 12)
				{
					if ($fields[$i]->field_type == 15)
					{
						$str[] = $fields[$i]->field_name;
					}
				}
				else
				{
					$str[] = $fields[$i]->field_name;
				}
			}
		}

		$list_field = array();

		if (count($str) > 0)
		{
			$dbname = "'" . implode("','", $str) . "'";
			$field = new extra_field;

			for ($t = 0; $t < count($sec); $t++)
			{
				$list_field[] = $field->list_all_field($sec[$t], $product_id, $dbname);
			}
		}

		if (count($list_field) > 0)
		{
			return $list_field;
		}

		else
		{
			return "";
		}
	}

	public function getmanufacturername($mid)
	{
		$query = 'SELECT manufacturer_name FROM ' . $this->_table_prefix . 'manufacturer  WHERE manufacturer_id="' . $mid . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function assignTemplate($data)
	{
		$cid = $data['cid'];

		$product_template = $data['product_template'];

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE ' . $this->_table_prefix . 'product' . ' SET `product_template` = "'
				. intval($product_template) . '" ' . ' WHERE product_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getCategoryList()
	{
		if ($this->_categorytreelist)
		{
			return $this->_categorytreelist;
		}

		$this->_categorytreelist = array();
		$q = "SELECT cx.category_child_id AS id, cx.category_parent_id AS parent_id, c.category_name AS title " . "FROM "
			. $this->_table_prefix . "category AS c, " . $this->_table_prefix . "category_xref AS cx "
			. "WHERE c.category_id=cx.category_child_id " . "ORDER BY ordering ";
		$this->_db->setQuery($q);
		$rows = $this->_db->loadObjectList();

		// Establish the hierarchy of the menu
		$children = array();

		// First pass - collect children
		foreach ($rows as $v)
		{
			$pt = $v->parent_id;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}

		// Second pass - get an indent list of the items
		$list = $this->treerecurse(0, '', array(), $children);

		if (count($list) > 0)
		{
			$this->_categorytreelist = $list;
		}

		return $this->_categorytreelist;
	}

	public function treerecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0)
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;
				$spacer = '  ';

				if ($v->parent_id == 0)
				{
					$txt = $v->title;
				}
				else
				{
					$txt = '- ' . $v->title;
				}

				$pt = $v->parent_id;
				$list[$id] = $v;
				$list[$id]->treename = $indent . $txt;
				$list[$id]->children = count(@$children[$id]);
				$list = $this->treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1);
			}
		}

		return $list;
	}

	/*
	 * save product ordering
	 * @params: $cid - array , $order-array
	 * $cid= product ids
	 * $order = product current ordring
	 * @return: boolean
	 */
	public function saveorder($cid = array(), $order = 0)
	{
		$app = JFactory::getApplication();

		$category_id_my = $app->getUserStateFromRequest('category_id', 'category_id', 0);

		$orderarray = array();

		for ($i = 0; $i < count($cid); $i++)
		{
			// Set product id as key AND order as value
			$orderarray[$cid[$i]] = $order[$i];
		}

		// Sorting array using value ( order )
		asort($orderarray);
		$i = 1;

		if (count($orderarray) > 0)
		{
			foreach ($orderarray as $productid => $order)
			{
				if ($order >= 0)
				{
					// Update ordering
					$query = 'UPDATE ' . $this->_table_prefix . 'product_category_xref' . ' SET ordering = ' . (int) $i
						. ' WHERE product_id=' . $productid . ' AND category_id = ' . $category_id_my;
					$this->_db->setQuery($query);
					$this->_db->execute();
				}

				$i++;
			}
		}

		return true;
	}
}
