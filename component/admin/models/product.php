<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;


class RedshopModelProduct extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_categorytreelist = null;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 */
	public function __construct($config = array())
	{
		// Different context depending on the view
		if (empty($this->context))
		{
			$input         = JFactory::getApplication()->input;
			$view          = $input->getString('view', '');
			$layout        = $input->getString('layout', 'none');
			$this->context = strtolower('com_redshop.' . $view . '.' . $this->getName() . '.' . $layout);
		}

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'product_number'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('product_sort');
		$id .= ':' . $this->getState('search_field');
		$id .= ':' . $this->getState('keyword');
		$id .= ':' . $this->getState('category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'p.product_id', $direction = 'desc')
	{
		$search_field = $this->getUserStateFromRequest($this->context . '.search_field', 'search_field', 'p.product_name');
		$this->setState('search_field', $search_field);

		$keyword = $this->getUserStateFromRequest($this->context . '.keyword', 'keyword', '');
		$this->setState('keyword', $keyword);

		$category_id = $this->getUserStateFromRequest($this->context . '.category_id', 'category_id', 0);
		$this->setState('category_id', $category_id);

		$product_sort = $this->getUserStateFromRequest($this->context . '.product_sort', 'product_sort', 0);
		$this->setState('product_sort', $product_sort);

		parent::populateState($ordering, $direction);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$this->_data = parent::getData();

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

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function _buildQuery()
	{
		static $items;

		if (isset($items))
		{
			return $items;
		}

		$db = JFactory::getDbo();

		$orderby      = $this->_buildContentOrderBy();
		$search_field = $this->getState('search_field');
		$keyword      = $this->getState('keyword');
		$category_id  = $this->getState('category_id');
		$product_sort = $this->getState('product_sort');
		$keyword      = addslashes($keyword);
		$arr_keyword  = array();

		$where = '';
		$and   = '';

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
				$and = 'AND p.not_for_sale > 0 ';
			}
			elseif ($product_sort == 'p.product_not_on_sale')
			{
				$and = 'AND p.product_on_sale=0 ';
			}
			elseif ($product_sort == 'p.sold_out')
			{
				$query_prd           = "SELECT DISTINCT(p.product_id),p.attribute_set_id FROM #__redshop_product AS p ";
				$tot_products        = $this->_getList($query_prd);
				$product_id_array    = '';
				$producthelper       = productHelper::getInstance();
				$products_stock      = $producthelper->removeOutofstockProduct($tot_products);
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
			for ($k = 0, $kn = count($arr_keyword); $k < $kn; $k++)
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

		if ($this->getState('filter.product_number'))
		{
			$where .= " AND p.product_number = '" . $db->escape($this->getState('filter.product_number')) . "'";
		}

		if ($category_id)
		{
			$where .= " AND c.id = '" . $category_id . "'  ";
		}

		if ($where == '' && $search_field != 'pa.property_number')
		{

			$query = "SELECT p.product_id,p.product_id AS id,p.product_name,p.product_name AS treename,p.product_name
			AS title,p.product_price,p.product_parent_id,p.product_parent_id AS parent_id,p.product_parent_id AS parent  "
				. ",p.published,p.visited,p.manufacturer_id,p.product_number ,p.checked_out,p.checked_out_time,p.discount_price "
				. ",p.product_template "
				. " FROM #__redshop_product AS p "
				. "WHERE 1=1 " . $and . $orderby;
		}
		else
		{
			$query = "SELECT p.product_id AS id,p.product_id,p.product_name,p.product_name AS treename,p.product_name AS
			name,p.product_name AS title,p.product_parent_id,p.product_parent_id AS parent,p.product_price " . ",
			p.published,p.visited,p.manufacturer_id,p.product_number,p.product_template,p.checked_out,p.checked_out_time,p.discount_price " . ",
			x.ordering , x.category_id "
				. " FROM #__redshop_product AS p " . "LEFT JOIN #__redshop_product_category_xref
			AS x ON x.product_id = p.product_id " . "LEFT JOIN #__redshop_category AS c ON x.category_id = c.id ";

			if ($search_field == 'pa.property_number' && $keyword != '')
			{
				$query .= "LEFT JOIN #__redshop_product_attribute AS a ON a.product_id = p.product_id "
					. "LEFT JOIN #__redshop_product_attribute_property AS pa ON pa.attribute_id = a.attribute_id "
					. "LEFT JOIN #__redshop_product_subattribute_color AS ps ON ps.subattribute_id = pa.property_id ";
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

			for ($i = 0, $in = count($product_stock); $i < $in; $i++)
			{
				$product[] = $product_stock[$i]->product_id;
			}

			$product_id = implode(',', $product);
			$query_prd  = "SELECT DISTINCT(p.product_id) FROM #__redshop_product AS p WHERE p.product_id NOT IN(" . $product_id . ")";
			$this->_db->setQuery($query_prd);
			$final_products = $this->_db->loadColumn();

			return $final_products;
		}
	}

	public function _buildContentOrderBy()
	{
		$db = JFactory::getDbo();

		$category_id      = $this->getState('category_id');
		$filter_order_Dir = $this->getState('list.direction');

		if ($category_id)
		{
			$filter_order = $this->getState('list.ordering', 'x.ordering');
		}
		else
		{
			$filter_order = $this->getState('list.ordering', 'p.product_id');

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
		$query = 'SELECT * FROM #__redshop_media  WHERE section_id ="' . $pid . '" AND media_section = "product"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function listedincats($pid)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->order($db->qn('c.name'));

		return $db->setQuery($query)->loadObjectlist();
	}

	public function product_template($template_id, $product_id, $section)
	{
		$redTemplate = Redtemplate::getInstance();

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
		$str      = array();
		$sec      = explode(',', $section);

		for ($t = 0, $tn = count($sec); $t < $tn; $t++)
		{
			$inArr[] = "'" . $sec[$t] . "'";
		}

		$in = implode(',', $inArr);
		$q  = "SELECT field_name,field_type,field_section from #__redshop_fields where field_section in (" . $in . ") ";
		$this->_db->setQuery($q);
		$fields = $this->_db->loadObjectlist();

		for ($i = 0, $in = count($fields); $i < $in; $i++)
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
			$dbname = implode(",", $str);
			$field  = extra_field::getInstance();

			for ($t = 0, $tn = count($sec); $t < $tn; $t++)
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

	public function assignTemplate($data)
	{
		$cid = $data['cid'];

		$product_template = $data['product_template'];

		if (count($cid))
		{
			$cids  = implode(',', $cid);
			$query = 'UPDATE #__redshop_product' . ' SET `product_template` = "'
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

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'parent_id', 'level')))
			->select($db->qn('name', 'title'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1')
			->where($db->qn('level') . ' > 0')
			->order($db->qn('lft'));

		$rows = $db->setQuery($query)->loadObjectList();

		// Establish the hierarchy of the menu
		$children = array();

		// First pass - collect children
		foreach ($rows as $v)
		{
			$pt   = $v->parent_id;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}

		// Get first key to generate tree recursive
		$firstKey = current(array_keys($children));

		// Second pass - get an indent list of the items
		$list = $this->treerecurse($firstKey, '- ', array(), $children);

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

				if ($v->parent_id == 0)
				{
					$txt = $v->title;
				}
				else
				{
					$txt = str_repeat($indent, $v->level) . $v->title;
				}

				$list[$id]           = $v;
				$list[$id]->treename = $txt;
				$list[$id]->children = count(@$children[$id]);
				$list                = $this->treerecurse($id, $indent, $list, $children, $maxlevel, $level + 1);
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
		$category_id_my = $this->getState('category_id');

		$orderarray = array();

		for ($i = 0, $in = count($cid); $i < $in; $i++)
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
					$query = 'UPDATE #__redshop_product_category_xref' . ' SET ordering = ' . (int) $i
						. ' WHERE product_id=' . $productid . ' AND category_id = ' . $category_id_my;
					$this->_db->setQuery($query);
					$this->_db->execute();
				}

				$i++;
			}
		}

		return true;
	}

	/**
	 * Method for save discount for list of product Ids
	 *
	 * @param   array $productIds     Product Id
	 * @param   array $discountPrices List of discount price.
	 *
	 * @return  bool
	 *
	 * @since   2.0.4
	 */
	public function saveDiscountPrices($productIds = array(), $discountPrices = array())
	{
		if (empty($productIds))
		{
			return false;
		}

		$productIds = ArrayHelper::toInteger($productIds);
		$case       = array();
		$db         = $this->_db;

		foreach ($productIds as $index => $productId)
		{
			// Skip if discount price doesn't populate
			if (!isset($discountPrices[$index]))
			{
				continue;
			}

			$price = (float) $discountPrices[$index];

			$case[] = 'WHEN ' . $db->qn('product_id') . ' = ' . $productId . ' AND ' . $db->qn('product_price') . ' >= ' . $price
				. ' THEN ' . $db->quote($price);
		}

		if (empty($case))
		{
			return false;
		}

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('discount_price') . ' = CASE ' . implode(' ', $case) . ' ELSE NULL END');

		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for save discount for list of product Ids
	 *
	 * @param   array $productIds Product Id
	 * @param   array $prices     List of discount price.
	 *
	 * @return  bool
	 *
	 * @since   2.0.4
	 */
	public function savePrices($productIds = array(), $prices = array())
	{
		if (empty($productIds))
		{
			return false;
		}

		$productIds = ArrayHelper::toInteger($productIds);
		$case       = array();
		$db         = $this->_db;

		foreach ($productIds as $index => $productId)
		{
			// Skip if discount price doesn't populate
			if (!isset($prices[$index]))
			{
				continue;
			}

			$price = (float) $prices[$index];

			$case[] = 'WHEN ' . $db->qn('product_id') . ' = ' . $productId . ' THEN ' . $db->quote($price);
		}

		if (empty($case))
		{
			return false;
		}

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('product_price') . ' = CASE ' . implode(' ', $case) . ' ELSE ' . $db->qn('product_price') . ' END');

		return $db->setQuery($query)->execute();
	}
}
