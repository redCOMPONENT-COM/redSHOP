<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class stockroom_listingModelstockroom_listing extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context2 = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$this->_context2 = 'p.product_id';

		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context2 . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context2 . 'limitstart', 'limitstart', 0);
		$stockroom_type = $app->getUserStateFromRequest($this->_context2 . 'stockroom_type', 'stockroom_type', '');
		$search_field = $app->getUserStateFromRequest($this->_context2 . 'search_field', 'search_field', '');
		$keyword = $app->getUserStateFromRequest($this->_context2 . 'keyword', 'keyword', '');
		$category_id = $app->getUserStateFromRequest($this->_context2 . 'category_id', 'category_id', '');

		$this->setState('stockroom_type', $stockroom_type);
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
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
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
		$app = JFactory::getApplication();

		$where = "";
		$field = "";
		$and = "";
		$leftjoin = " ";

		$orderby = $this->_buildContentOrderBy();

		$stockroom_type = $this->getState('stockroom_type');
		$search_field = $this->getState('search_field');
		$keyword = $this->getState('keyword');
		$category_id = $this->getState('category_id');

		$container_id = JRequest::getVar('container_list', '0');

		if (trim($keyword) != '')
		{
			$and .= " AND p." . $search_field . " LIKE '" . $keyword . "%' ";
		}
		if ($container_id != 0)
		{
			$where = " AND c.container_id=" . $container_id;
		}
		if ($category_id > 0)
		{
			$and .= " AND pcx.category_id='" . $category_id . "' ";
		}

		if ($stockroom_type == 'subproperty')
		{
			$field = ", asp.*, subattribute_color_id AS section_id ";
			$table = "product_subattribute_color AS asp ";
			$leftjoin = "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS ap ON asp.subattribute_id = ap.property_id "
				. "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.attribute_id = ap.attribute_id "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = a.product_id ";
		}
		elseif ($stockroom_type == 'property')
		{
			$field = ", ap.*, property_id AS section_id ";
			$table = "product_attribute_property AS ap ";
			$leftjoin = "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.attribute_id = ap.attribute_id "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = a.product_id ";
		}
		else
		{
			$table = "product AS p ";
		}
		$query = "SELECT distinct p.product_id, p . * " . $field
			. "FROM " . $this->_table_prefix . $table
			. $leftjoin
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pcx ON pcx.product_id=p.product_id "
			. "WHERE p.product_id is not NULL "
			. $and
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();
		$stockroom_type = $this->getState('stockroom_type');
		$filter_order = $app->getUserStateFromRequest($this->_context2 . 'filter_order', 'filter_order', 'p.product_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context2 . 'filter_order_Dir', 'filter_order_Dir', '');

		if ($stockroom_type == 'subproperty')
		{
			$filter_order = 'p.product_id, a.attribute_id, ap.property_id, asp.ordering';
		}

		elseif ($stockroom_type == 'property')
		{
			$filter_order = 'p.product_id, a.attribute_id, ap.ordering';
		}

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function getStockroom()
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getQuantity($stockroom_type, $sid, $pid)
	{
		$product = " AND product_id='" . $pid . "' ";
		$section = "";
		$stock = "";
		$table = "product";

		if ($stockroom_type != 'product')
		{
			$product = " AND section_id='" . $pid . "' ";
			$section = " AND section = '" . $stockroom_type . "' ";
			$table = "product_attribute";
		}
		if ($sid != 0)
		{
			$stock = "AND stockroom_id='" . $sid . "' ";
		}
		$query = "SELECT * FROM " . $this->_table_prefix . $table . "_stockroom_xref "
			. "WHERE 1=1 "
			. $stock
			. $product . $section;

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function storeStockroomQuantity($stockroom_type, $sid, $pid, $quantity = "", $preorder_stock = 0, $ordered_preorder = 0)
	{
		$product = " AND product_id='" . $pid . "' ";
		$section = "";
		$table = "product";

		if ($stockroom_type != 'product')
		{
			$product = " AND section_id='" . $pid . "' ";
			$section = " AND section = '" . $stockroom_type . "' ";
			$table = "product_attribute";
		}
		$list = $this->getQuantity($stockroom_type, $sid, $pid);
		$query = "";

		if (count($list) > 0)
		{
			if ($quantity == "" && USE_BLANK_AS_INFINITE)
			{
				$query = "DELETE FROM " . $this->_table_prefix . $table . "_stockroom_xref "
					. " WHERE stockroom_id='" . $sid . "' " . $product . $section;
			}
			else
			{
				if (($preorder_stock < $ordered_preorder) && $preorder_stock != "" && $ordered_preorder != "")
				{
					$msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED');
					JError::raiseWarning('', $msg);

					return false;
				}
				else
				{
					$query = "UPDATE " . $this->_table_prefix . $table . "_stockroom_xref "
						. "SET quantity='" . $quantity . "' , preorder_stock= '" . $preorder_stock . "'"
						. " WHERE stockroom_id='" . $sid . "'"
						. $product . $section;
				}
			}
		}
		else
		{
			if ($preorder_stock < $ordered_preorder && $preorder_stock != "" && $ordered_preorder != "")
			{
				$msg = JText::_('COM_REDSHOP_PREORDER_STOCK_NOT_ALLOWED') . "for Stockroom ";
				JError::raiseWarning('', $msg);

				return false;
			}
			else
			{
				if ($preorder_stock != "" || $quantity != "")
				{
					if ($quantity == "" && USE_BLANK_AS_INFINITE)
					{
						$query = "";
					}
					else
					{
						if ($quantity == "")
						{
							$quantity = 0;
						}
						if ($stockroom_type != 'product')
						{
							$query = "INSERT INTO " . $this->_table_prefix . $table . "_stockroom_xref "
								. "(section_id, stockroom_id, quantity, section , preorder_stock, ordered_preorder) "
								. "VALUES ('" . $pid . "', '" . $sid . "', '" . $quantity . "', '" . $stockroom_type . "', '"
								. $preorder_stock . "','0') ";
						}
						else
						{
							$query = "INSERT INTO " . $this->_table_prefix . $table . "_stockroom_xref "
								. "(product_id, stockroom_id, quantity, preorder_stock, ordered_preorder ) "
								. "VALUES ('" . $pid . "', '" . $sid . "', '" . $quantity . "', '" . $preorder_stock . "','0' ) ";
						}
					}
				}
			}
		}
		if ($query != "")
		{
			$this->_db->setQuery($query);
			$this->_db->Query();

			// For stockroom Notify Email

			$stockroom_data = array();
			$stockroom_data['section'] = $stockroom_type;
			$stockroom_data['section_id'] = $pid;
			$stockroom_data['regular_stock'] = $quantity;
			$stockroom_data['preorder_stock'] = $preorder_stock;
			JPluginHelper::importPlugin('redshop_product');
			$dispatcher = JDispatcher::getInstance();
			$data = $dispatcher->trigger('afterUpdateStock', array($stockroom_data));
		}
	}

	public function getProductIdsfromCategoryid($cid)
	{
		$query = "SELECT product_id FROM " . $this->_table_prefix . "product_category_xref "
			. "WHERE category_id= " . $cid;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadResultArray();

		return $this->_data;
	}

	public function getcontainerproducts($product_ids = 0)
	{
		$and = "";

		if ($product_ids != 0)
		{
			$and = " and ps.product_id in (" . $product_ids . ")";
		}
		if (USE_CONTAINER)
		{
			$query = "SELECT DISTINCT p.product_id, p.product_name, p.product_number,p.product_volume,cp.quantity, c . * , sc . *
				FROM " . $this->_table_prefix . "container AS c
				LEFT JOIN " . $this->_table_prefix . "container_product_xref AS cp ON cp.container_id = c.container_id
				LEFT JOIN " . $this->_table_prefix . "product AS p ON cp.product_id = p.product_id
				LEFT JOIN " . $this->_table_prefix . "stockroom_container_xref AS sc ON sc.container_id = c.container_id where 1=1 ";
		}
		else
		{
			$query = "SELECT * FROM  " . $this->_table_prefix . "stockroom as s , "
				. $this->_table_prefix . "product_stockroom_xref AS ps "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON ps.product_id = p.product_id "
				. "WHERE ps.stockroom_id = s.stockroom_id " . $and;
		}
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObjectlist();

		return $this->_data;
	}

	public function ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid)
	{
		$query = "";
		$product = " AND product_id='" . $pid . "' ";
		$section = "";
		$table = "product";

		if ($stockroom_type != 'product')
		{
			$product = " AND section_id='" . $pid . "' ";
			$section = " AND section = '" . $stockroom_type . "' ";
			$table = "product_attribute";
		}

		$query = "UPDATE " . $this->_table_prefix . $table . "_stockroom_xref "
			. "SET preorder_stock='0' , ordered_preorder= '0' "
			. "WHERE stockroom_id='" . $sid . "'"
			. $product . $section;

		if ($query != "")
		{
			$this->_db->setQuery($query);
			$this->_db->Query();
		}
	}
}
