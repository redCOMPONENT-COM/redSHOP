<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopModelStockroom_listing
 *
 * @since  1.5
 */
class RedshopModelStockroom_Listing extends RedshopModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.5
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'p.product_number', 'product_number',
				'p.product_name', 'product_name',
				'stockroom_type', 'category_id',
				'search_field', 'keyword'
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
	 * Get the columns for the csv file.
	 *
	 * @return  array  An associative array of column names as key and the title as value.
	 */
	public function getCsvColumns()
	{
		return array(
			'stockroom_id' => JText::_('COM_REDSGOP_STOCKROOM_ID'),
			'stockroom_name' => JText::_('COM_REDSHOP_STOCKROOM_NAME'),
			'quantity' => JText::_('COM_REDSHOP_PRODUCT_QTY'),
			'preorder_stock' => JText::_('COM_REDSHOP_PREORDER_STOCKROOM_QTY'),
			'section_id' => JText::_('COM_REDSHOP_SECTION_ID'),
			'stockroom_type' => JText::_('COM_REDSHOP_SECTION_TYPE'),
			'product_id' => JText::_('COM_REDSHOP_PRODUCT_ID'),
			'product_number' => JText::_('COM_REDSHOP_PRODUCT_SKU'),
			'product_name' => JText::_('COM_REDSHOP_PRODUCT_NAME'),
		);
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

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();

		// Initialize query
		$query = $db->getQuery(true)
			->select('p.product_number, p.product_name, p.product_id, p.product_price');

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
				->innerJoin(
					$db->qn('#__redshop_product_attribute_property', 'ap')
					. ' ON ' . $db->qn('asp.subattribute_id') . ' = ' . $db->qn('ap.property_id')
				)
				->innerJoin(
					$db->qn('#__redshop_product_attribute', 'a')
					. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
				)
				->innerJoin(
					$db->qn('#__redshop_product', 'p')
					. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id')
				)
				->group($db->qn('asp.subattribute_color_id'));
		}
		elseif ($stockroomType == 'property')
		{
			$query->select('ap.*, property_id AS section_id')
				->from($db->qn('#__redshop_product_attribute_property', 'ap'))
				->innerJoin(
					$db->qn('#__redshop_product_attribute', 'a')
					. ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
				)
				->innerJoin(
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

	/**
	 * Get stockrooms
	 *
	 * @return mixed
	 */
	public function getStockroom()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__redshop_stockroom')
			->where('published = 1');

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 * Get quantity
	 *
	 * @param   string  $stockroom_type  Stockroom type
	 * @param   array   $pids            Sections ids
	 *
	 * @return mixed
	 */
	public function getQuantity($stockroom_type, $sid = '', $pids = array())
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*');

		if ($stockroom_type != 'product')
		{
			$query->select('CONCAT_WS(' . $db->q('.') . ', sx.section_id, sx.stockroom_id) AS concat_id')
				->from($db->qn('#__redshop_product_attribute_stockroom_xref', 'sx'))
				->where('sx.section = ' . $db->q($stockroom_type));

			if ($pids)
			{
				$query->where('sx.section_id IN (' . implode(',', (array) $pids) . ')');
			}
		}
		else
		{
			$query->select('CONCAT_WS(' . $db->q('.') . ', sx.product_id, sx.stockroom_id) AS concat_id')
				->from($db->qn('#__redshop_product_stockroom_xref', 'sx'));

			if ($pids)
			{
				$query->where('sx.product_id IN (' . implode(',', (array) $pids) . ')');
			}
		}

		$query->leftJoin($db->qn('#__redshop_stockroom', 's') . ' ON s.stockroom_id = sx.stockroom_id')
			->where('s.published = 1');

		if ($sid)
		{
			$query->where('s.stockroom_id = ' . $db->q($sid));
		}

		return $db->setQuery($query)->loadObjectlist('concat_id');
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
			if ($quantity == "" && Redshop::getConfig()->get('USE_BLANK_AS_INFINITE'))
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
					if ($quantity == "" && Redshop::getConfig()->get('USE_BLANK_AS_INFINITE'))
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
			$dispatcher = RedshopHelperUtility::getDispatcher();
			$dispatcher->trigger('onAfterUpdateStock', array($stockroom_data));
		}
	}

	public function ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid)
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
