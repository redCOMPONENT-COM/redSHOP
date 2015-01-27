<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelStockroom_listing extends RedshopModel
{
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('stockroom_type');
		$id .= ':' . $this->getState('search_field');
		$id .= ':' . $this->getState('keyword');
		$id .= ':' . $this->getState('category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'p.product_id', $direction = '')
	{
		$stockroom_type = $this->getUserStateFromRequest($this->context . '.stockroom_type', 'stockroom_type', 'product');
		$search_field = $this->getUserStateFromRequest($this->context . '.search_field', 'search_field', 'product_name');
		$keyword = $this->getUserStateFromRequest($this->context . '.keyword', 'keyword', '');
		$category_id = $this->getUserStateFromRequest($this->context . '.category_id', 'category_id', 0);

		$this->setState('stockroom_type', $stockroom_type);
		$this->setState('search_field', $search_field);
		$this->setState('keyword', $keyword);
		$this->setState('category_id', $category_id);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$field = "";
		$and = "";
		$leftjoin = " ";

		$db = JFactory::getDbo();
		$filter_order_Dir = $this->getState('list.direction');
		$filter_order = $this->getState('list.ordering');
		$stockroom_type = $this->getState('stockroom_type');

		if ($stockroom_type == 'subproperty')
		{
			$filter_order = 'p.product_id, a.attribute_id, ap.property_id, asp.ordering';
		}

		elseif ($stockroom_type == 'property')
		{
			$filter_order = 'p.product_id, a.attribute_id, ap.ordering';
		}

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		$search_field = $this->getState('search_field');
		$keyword = $this->getState('keyword');
		$category_id = $this->getState('category_id');

		if (trim($keyword) != '')
		{
			$and .= " AND p." . $search_field . " LIKE '" . $keyword . "%' ";
		}

		if ($category_id > 0)
		{
			$and .= " AND pcx.category_id='" . $category_id . "' ";
		}

		if ($stockroom_type == 'subproperty')
		{
			$field = ", asp.*, subattribute_color_id AS section_id ";
			$table = "product_subattribute_color AS asp ";
			$leftjoin = "LEFT JOIN #__redshop_product_attribute_property AS ap ON asp.subattribute_id = ap.property_id "
				. "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id = ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id = a.product_id ";
		}
		elseif ($stockroom_type == 'property')
		{
			$field = ", ap.*, property_id AS section_id ";
			$table = "product_attribute_property AS ap ";
			$leftjoin = "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id = ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id = a.product_id ";
		}
		else
		{
			$table = "product AS p ";
		}

		$query = "SELECT p.* " . $field
			. "FROM #__redshop_" . $table
			. $leftjoin
			. "LEFT JOIN #__redshop_product_category_xref AS pcx ON pcx.product_id=p.product_id "
			. "WHERE 1 = 1 "
			. $and
			. ' GROUP BY p.product_id '
			. $orderby;

		return $query;
	}

	public function getStockroom()
	{
		$query = 'SELECT * FROM #__redshop_stockroom WHERE published=1';
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
		$query = "SELECT * FROM #__redshop_" . $table . "_stockroom_xref "
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
				$query = "DELETE FROM #__redshop_" . $table . "_stockroom_xref "
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
					$query = "UPDATE #__redshop_" . $table . "_stockroom_xref "
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
							$query = "INSERT INTO #__redshop_" . $table . "_stockroom_xref "
								. "(section_id, stockroom_id, quantity, section , preorder_stock, ordered_preorder) "
								. "VALUES ('" . $pid . "', '" . $sid . "', '" . $quantity . "', '" . $stockroom_type . "', '"
								. $preorder_stock . "','0') ";
						}
						else
						{
							$query = "INSERT INTO #__redshop_" . $table . "_stockroom_xref "
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
			$this->_db->execute();

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
		$query = "SELECT product_id FROM #__redshop_product_category_xref "
			. "WHERE category_id= " . $cid;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadColumn();

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

		$query = "UPDATE #__redshop_" . $table . "_stockroom_xref "
			. "SET preorder_stock='0' , ordered_preorder= '0' "
			. "WHERE stockroom_id='" . $sid . "'"
			. $product . $section;

		if ($query != "")
		{
			$this->_db->setQuery($query);
			$this->_db->execute();
		}
	}
}
