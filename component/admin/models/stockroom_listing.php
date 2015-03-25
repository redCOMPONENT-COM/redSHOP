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
		$db = JFactory::getDbo();

		// Initialize query
		$query = $db->getQuery(true)->select('p.*');

		$keyword = $this->getState('keyword');

		if (trim($keyword) != '')
		{
			$query->where($db->qn('p.' . $this->getState('search_field')) . ' LIKE ' . $db->q($keyword . '%'));
		}

		$categoryId = $this->getState('category_id');

		if ($categoryId > 0)
		{
			$query->where($db->qn('pcx.category_id') . ' = ' . (int) $categoryId);
		}

		$stockroomType = $this->getState('stockroom_type');

		if ($stockroomType == 'subproperty')
		{
			$query->select('asp.*, subattribute_color_id AS section_id')
				->from($db->qn('#__redshop_product_subattribute_color', 'asp'))
				->leftjoin(
					$db->qn('#__redshop_product_attribute_property', 'ap')
					. ' ON ' . $db->qn('asp.subattribute_id') . ' = ' . $db->qn('ap.property_id')
				)
				->leftjoin(
					$db->qn('#__redshop_product_attribute', 'a')
					. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
				)
				->leftjoin(
					$db->qn('#__redshop_product', 'p')
					. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id')
				)
				->group($db->qn('asp.subattribute_color_id'));
		}
		elseif ($stockroomType == 'property')
		{
			$query->select('ap.*, property_id AS section_id')
				->from($db->qn('#__redshop_product_attribute_property', 'ap'))
				->leftjoin(
					$db->qn('#__redshop_product_attribute', 'a')
					. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
				)
				->leftjoin(
					$db->qn('#__redshop_product', 'p')
					. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id')
				)
				->group($db->qn('ap.property_id'));
		}
		else
		{
			$query->from($db->qn('#__redshop_product', 'p'))
				->group($db->qn('p.product_id'));
		}

		$query->leftjoin(
				$db->qn('#__redshop_product_category_xref', 'pcx')
				. ' ON ' . $db->qn('pcx.product_id') . '=' . $db->qn('p.product_id')
			);

		// Build ordering query
		$filterOrder = $this->getState('list.ordering');

		if ($stockroomType == 'subproperty')
		{
			$filterOrder = 'p.product_id, a.attribute_id, ap.property_id, asp.ordering';
		}
		elseif ($stockroomType == 'property')
		{
			$filterOrder = 'p.product_id, a.attribute_id, ap.ordering';
		}

		$query->order($db->escape($filterOrder . ' ' . $this->getState('list.direction')));

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
