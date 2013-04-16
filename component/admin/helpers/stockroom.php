<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';

class rsstockroomhelper
{
	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
	}

	public function getStockroomDetail($stockroom_id = 0)
	{
		$list = array();

		if (USE_STOCKROOM == 1)
		{
			$db = JFactory::getDBO();
			$and = "";

			if ($stockroom_id != 0)
			{
				$and = "AND stockroom_id='" . $stockroom_id . "' ";
			}

			$query = "SELECT * FROM " . $this->_table_prefix . "stockroom "
				. "WHERE 1=1 "
				. $and;
			$db->setQuery($query);
			$list = $db->loadObjectList();
		}

		return $list;
	}

	public function isStockExists($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		if (USE_STOCKROOM == 1)
		{
			$stock = $this->getStockAmountwithReserve($section_id, $section, $stockroom_id);

			if ($stock > 0)
			{
				return true;
			}

			return false;
		}

		return true;
	}

	public function isAttributeStockExists($product_id)
	{
		$isStockExists = false;
		$producthelper = new producthelper;
		$property = $producthelper->getAttibuteProperty(0, 0, $product_id);

		for ($att_j = 0; $att_j < count($property); $att_j++)
		{
			$isSubpropertyStock = false;
			$sub_property = $producthelper->getAttibuteSubProperty(0, $property[$att_j]->property_id);

			for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
			{
				$isSubpropertyStock = $this->isStockExists($sub_property[$sub_j]->subattribute_color_id, 'subproperty');

				if ($isSubpropertyStock)
				{
					$isStockExists = $isSubpropertyStock;

					return $isStockExists;
				}
			}

			if ($isSubpropertyStock)
			{
				return $isStockExists;
			}
			else
			{
				$isPropertystock = $this->isStockExists($property[$att_j]->property_id, "property");

				if ($isPropertystock)
				{
					$isStockExists = $isPropertystock;

					return $isStockExists;
				}
			}
		}

		return $isStockExists;
	}

	public function isPreorderStockExists($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		if (USE_STOCKROOM == 1)
		{
			$stock = $this->getPreorderStockAmountwithReserve($section_id, $section, $stockroom_id);

			if ($stock > 0)
			{
				return true;
			}

			return false;
		}

		return true;
	}

	public function isAttributePreorderStockExists($product_id)
	{
		$producthelper = new producthelper;
		$property = $producthelper->getAttibuteProperty(0, 0, $product_id);

		for ($att_j = 0; $att_j < count($property); $att_j++)
		{
			$isSubpropertyStock = false;
			$sub_property = $producthelper->getAttibuteSubProperty(0, $property[$att_j]->property_id);

			for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
			{
				$isSubpropertyStock = $this->isPreorderStockExists($sub_property[$sub_j]->subattribute_color_id, 'subproperty');

				if ($isSubpropertyStock)
				{
					$isPreorderStockExists = $isSubpropertyStock;

					return $isPreorderStockExists;
				}
			}

			if ($isSubpropertyStock)
			{
				return $isPreorderStockExists;
			}
			else
			{
				$isPropertystock = $this->isPreorderStockExists($property[$att_j]->property_id, "property");

				if ($isPropertystock)
				{
					$isPreorderStockExists = $isPropertystock;

					return $isPreorderStockExists;
				}
			}
		}

		return $isPreorderStockExists;
	}

	public function getStockroomTotalAmount($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$quantity = 1;

		if (USE_STOCKROOM == 1)
		{
			$quantity = $this->getStockAmountwithReserve($section_id, $section, $stockroom_id);

			$reserve_quantity = $this->getReservedStock($section_id, $section);
			$quantity = $quantity - $reserve_quantity;

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}

		return $quantity;
	}

	public function getPreorderStockroomTotalAmount($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$quantity = 1;

		if (USE_STOCKROOM == 1)
		{
			$quantity = $this->getPreorderStockAmountwithReserve($section_id, $section, $stockroom_id);

			$reserve_quantity = $this->getReservedStock($section_id, $section);
			$quantity = $quantity - $reserve_quantity;

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}

		return $quantity;
	}

	public function getStockAmountwithReserve($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$quantity = 1;

		if (USE_STOCKROOM == 1)
		{
			$and = "";
			$table = "product";
			$db = JFactory::getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0)
			{
				if ($section != "product")
				{
					$and = "AND x.section='" . $section . "' AND x.section_id IN (" . $section_id . ") ";
				}
				else
				{
					$and = "AND x.product_id IN (" . $section_id . ") ";
				}
			}

			if ($stockroom_id != 0)
			{
				$and .= "AND x.stockroom_id='" . $stockroom_id . "' ";
			}

			$query = "SELECT SUM(x.quantity)  FROM " . $this->_table_prefix . $table . "_stockroom_xref AS x "
				. ", " . $this->_table_prefix . "stockroom AS s "
				. "WHERE s.stockroom_id=x.stockroom_id "
				. "AND x.quantity>=0 "
				. $and
				. "ORDER BY s.min_del_time ";

			$db->setQuery($query);
			$quantity = $db->loadResult();

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}
		else
		{
			$helper = new redhelper;

			if ($helper->isredCRM())
			{
				if (ENABLE_ITEM_TRACKING_SYSTEM && !ENABLE_ONE_STOCKROOM_MANAGEMENT)
				{
					// Include redSHOP product helper
					$producthelper = new producthelper;

					// Supplier order helper object
					$crmSupplierOrderHelper = new crmSupplierOrderHelper;

					$getstockdata = new stdClass;

					$getstockdata->property_id = 0;
					$getstockdata->subproperty_id = 0;

					if ($section == "product")
					{
						$getstockdata->product_id = $section_id;
					}
					elseif ($section == "property")
					{
						$property = $producthelper->getAttibuteProperty($section_id);
						$attribute_id = $property[0]->attribute_id;
						$attribute = $producthelper->getProductAttribute(0, 0, $attribute_id);
						$product_id = $attribute[0]->product_id;

						$getstockdata->product_id = $product_id;
						$getstockdata->property_id = $section_id;
					}
					elseif ($section == "subproperty")
					{
						$subproperty = $producthelper->getAttibuteSubProperty($section_id);
						$property_id = $subproperty[0]->subattribute_id;
						$property = $producthelper->getAttibuteProperty($property_id);
						$attribute_id = $property[0]->attribute_id;
						$attribute = $producthelper->getProductAttribute(0, 0, $attribute_id);
						$product_id = $attribute[0]->product_id;

						$getstockdata->product_id = $product_id;
						$getstockdata->property_id = $property_id;
						$getstockdata->subproperty_id = $section_id;
					}

					$quantity = $crmSupplierOrderHelper->getSupplierStock($getstockdata);
				}
			}
		}

		if ($quantity == null)
		{
			$quantity = (USE_BLANK_AS_INFINITE) ? 1000000000 : 0;
		}

		return $quantity;
	}

	function getPreorderStockAmountwithReserve($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$quantity = 1;

		if (USE_STOCKROOM == 1)
		{
			$and = "";
			$table = "product";
			$db = JFactory::getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0)
			{
				if ($section != "product")
				{
					$and = "AND x.section='" . $section . "' AND x.section_id IN (" . $section_id . ") ";
				}
				else
				{
					$and = "AND x.product_id IN (" . $section_id . ") ";
				}
			}

			if ($stockroom_id != 0)
			{
				$and .= "AND x.stockroom_id='" . $stockroom_id . "' ";
			}
			$query = "SELECT SUM(x.preorder_stock) as preorder_stock, SUM(x.ordered_preorder) as ordered_preorder FROM "
				. $this->_table_prefix . $table . "_stockroom_xref AS x "
				. ", " . $this->_table_prefix . "stockroom AS s "
				. "WHERE s.stockroom_id=x.stockroom_id "
				. "AND x.quantity>=0 "
				. $and
				. "ORDER BY s.min_del_time ";

			$db->setQuery($query);
			$pre_order_stock = $db->loadObjectList();


			if ($pre_order_stock[0]->ordered_preorder == $pre_order_stock[0]->preorder_stock
				|| $pre_order_stock[0]->ordered_preorder > $pre_order_stock[0]->preorder_stock)
			{
				$quantity = 0;
			}
			else
			{
				$quantity = $pre_order_stock[0]->preorder_stock - $pre_order_stock[0]->ordered_preorder;
			}
		}
		else
		{
			$helper = new redhelper;

			if ($helper->isredCRM())
			{
				if (ENABLE_ITEM_TRACKING_SYSTEM && !ENABLE_ONE_STOCKROOM_MANAGEMENT)
				{
					// Include redSHOP product helper
					$producthelper = new producthelper;

					// Supplier order helper object
					$crmSupplierOrderHelper = new crmSupplierOrderHelper;

					$getstockdata = new stdClass;

					$getstockdata->property_id = 0;
					$getstockdata->subproperty_id = 0;

					if ($section == "product")
					{
						$getstockdata->product_id = $section_id;
					}
					elseif ($section == "property")
					{
						$property = $producthelper->getAttibuteProperty($section_id);
						$attribute_id = $property[0]->attribute_id;
						$attribute = $producthelper->getProductAttribute(0, 0, $attribute_id);
						$product_id = $attribute[0]->product_id;

						$getstockdata->product_id = $product_id;
						$getstockdata->property_id = $section_id;
					}
					elseif ($section == "subproperty")
					{
						$subproperty = $producthelper->getAttibuteSubProperty($section_id);
						$property_id = $subproperty[0]->subattribute_id;
						$property = $producthelper->getAttibuteProperty($property_id);
						$attribute_id = $property[0]->attribute_id;
						$attribute = $producthelper->getProductAttribute(0, 0, $attribute_id);
						$product_id = $attribute[0]->product_id;

						$getstockdata->product_id = $product_id;
						$getstockdata->property_id = $property_id;
						$getstockdata->subproperty_id = $section_id;
					}

					$quantity = $crmSupplierOrderHelper->getSupplierStock($getstockdata);
				}
			}
		}

		return $quantity;
	}

	public function getStockroomAmountDetailList($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$list = array();

		if (USE_STOCKROOM == 1)
		{
			$and = "";
			$table = "product";
			$db = JFactory::getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0)
			{
				if ($section != "product")
				{
					$and = "AND x.section='" . $section . "' AND x.section_id='" . $section_id . "' ";
				}
				else
				{
					$and = "AND x.product_id='" . $section_id . "' ";
				}
			}

			if ($stockroom_id != 0)
			{
				$and .= "AND x.stockroom_id='" . $stockroom_id . "' ";
			}

			$query = "SELECT * FROM " . $this->_table_prefix . $table . "_stockroom_xref AS x "
				. "LEFT JOIN " . $this->_table_prefix . "stockroom AS s ON s.stockroom_id=x.stockroom_id "
				. "WHERE 1=1 "
				. "AND x.quantity>0 "
				. $and
				. "ORDER BY s.min_del_time ";
			$db->setQuery($query);
			$list = $db->loadObjectList();
		}

		return $list;
	}

	public function getPreorderStockroomAmountDetailList($section_id = 0, $section = "product", $stockroom_id = 0)
	{
		$list = array();

		if (USE_STOCKROOM == 1)
		{
			$and = "";
			$table = "product";
			$db = JFactory::getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0)
			{
				if ($section != "product")
				{
					$and = "AND x.section='" . $section . "' AND x.section_id='" . $section_id . "' ";
				}
				else
				{
					$and = "AND x.product_id='" . $section_id . "' ";
				}
			}

			if ($stockroom_id != 0)
			{
				$and .= "AND x.stockroom_id='" . $stockroom_id . "' ";
			}

			$query = "SELECT * FROM " . $this->_table_prefix . $table . "_stockroom_xref AS x "
				. "LEFT JOIN " . $this->_table_prefix . "stockroom AS s ON s.stockroom_id=x.stockroom_id "
				. "WHERE 1=1 "
				. "AND x.preorder_stock>= x.ordered_preorder "
				. $and
				. "ORDER BY s.min_del_time ";
			$db->setQuery($query);
			$list = $db->loadObjectList();
		}

		return $list;
	}

	public function updateStockroomQuantity($section_id = 0, $quantity = 0, $section = "product", $product_id = 0)
	{
		$affected_row = array();
		$stockroom_quantity = array();

		if (USE_STOCKROOM == 1)
		{
			$list = $this->getStockroomAmountDetailList($section_id, $section);

			for ($i = 0; $i < count($list); $i++)
			{
				if ($list[$i]->quantity < $quantity)
				{
					$quantity = $quantity - $list[$i]->quantity;
					$remaining_quantity = $list[$i]->quantity;
				}
				else
				{
					$remaining_quantity = $quantity;
					$quantity -= $remaining_quantity;
				}

				if ($remaining_quantity > 0)
				{
					$this->updateStockAmount($section_id, $remaining_quantity, $list[$i]->stockroom_id, $section);
					$affected_row[] = $list[$i]->stockroom_id;
					$stockroom_quantity[] = $remaining_quantity;
				}
			}

			// For preorder stock
			if ($quantity > 0)
			{
				$preorder_list = $this->getPreorderStockroomAmountDetailList($section_id, $section);
				$producthelper = new producthelper();

				if ($section == "product")
				{
					$product_data = $producthelper->getProductById($section_id);
				}
				else
				{
					$product_data = $producthelper->getProductById($product_id);
				}

				if ($product_data->preorder == "yes" || ($product_data->preorder == "global" && ALLOW_PRE_ORDER)
					|| ($product_data->preorder == "" && ALLOW_PRE_ORDER))
				{
					for ($i = 0; $i < count($preorder_list); $i++)
					{
						if ($preorder_list[$i]->preorder_stock < $quantity)
						{
							$quantity = $quantity - $preorder_list[$i]->preorder_stock;
							$remaining_quantity = $preorder_list[$i]->preorder_stock;
						}
						else
						{
							$remaining_quantity = $quantity;
							$quantity -= $remaining_quantity;
						}

						if ($remaining_quantity > 0)
						{
							$this->updatePreorderStockAmount($section_id, $remaining_quantity, $preorder_list[$i]->stockroom_id, $section);
						}
					}
				}
			}
		}

		$list = implode(",", $affected_row);
		$stockroom_quantity_list = implode(",", $stockroom_quantity);
		$result_array = array();
		$result_array['stockroom_list'] = $list;
		$result_array['stockroom_quantity_list'] = $stockroom_quantity_list;

		return $result_array;
	}

	public function updateStockAmount($section_id = 0, $quantity = 0, $stockroom_id = 0, $section = "product")
	{
		$and = "";
		$table = "product";

		if (USE_STOCKROOM == 1)
		{
			$db = JFactory::getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0)
			{
				if ($section != "product")
				{
					$and = "AND section='" . $section . "' AND section_id='" . $section_id . "' ";
				}
				else
				{
					$and = "AND product_id='" . $section_id . "' ";
				}

				$query = 'UPDATE ' . $this->_table_prefix . $table . '_stockroom_xref '
					. 'SET quantity=quantity - ' . $quantity . ' '
					. 'WHERE stockroom_id="' . $stockroom_id . '" '
					. 'AND quantity > 0 '
					. $and;
				$db->setQuery($query);
				$db->query();
			}
		}
		return true;
	}

	public function updatePreorderStockAmount($section_id = 0, $quantity = 0, $stockroom_id = 0, $section = "product")
	{
		$and = "";
		$table = "product";

		if (USE_STOCKROOM == 1)
		{
			$db = JFactory :: getDBO();

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0 && trim($section_id) != "")
			{
				if ($section != "product")
				{
					$and = "AND section='" . $section . "' AND section_id='" . $section_id . "' ";
				}
				else
				{
					$and = "AND product_id='" . $section_id . "' ";
				}

				$query = 'UPDATE ' . $this->_table_prefix . $table . '_stockroom_xref '
					. 'SET ordered_preorder=ordered_preorder + ' . $quantity . ' '
					. 'WHERE stockroom_id="' . $stockroom_id . '" '
					. $and;
				$db->setQuery($query);
				$db->query();
			}
		}

		return true;
	}

	public function manageStockAmount($section_id = 0, $quantity = 0, $stockroom_id = 0, $section = "product")
	{
		if (USE_STOCKROOM == 1)
		{
			$db = JFactory::getDBO();
			$and = "";
			$table = "product";

			if ($section != "product")
			{
				$table = "product_attribute";
			}

			if ($section_id != 0 && trim($section_id) != "")
			{
				if ($section != "product")
				{
					$and = "AND section='" . $section . "' AND section_id='" . $section_id . "' ";
				}
				else
				{
					$and = "AND product_id='" . $section_id . "' ";
				}
			}

			$stockId = explode(",", $stockroom_id);
			$stock_Qty = explode(",", $quantity);

			for ($i = 0; $i < count($stockId); $i++)
			{
				if ($stockId[$i] != "" && $section_id != "" && $section_id != 0)
				{
					$query = 'UPDATE ' . $this->_table_prefix . $table . '_stockroom_xref '
						. 'SET quantity=quantity + ' . $stock_Qty[$i] . ' '
						. 'WHERE stockroom_id = ' . $stockId[$i] . ' '
						. $and;
					$db->setQuery($query);
					$db->query();
					$affected_row = $db->getAffectedRows();

					if ($affected_row > 0)
					{
						break;
					}
				}
			}
		}

		return true;
	}

	public function replaceStockroomAmountDetail($template_desc = "", $section_id = 0, $section = "product")
	{
		$productinstock = "";

		if (USE_STOCKROOM == 1)
		{
			$list = $this->getStockroomAmountDetailList($section_id, $section);

			for ($i = 0; $i < count($list); $i++)
			{
				$productinstock .= "<div><span>" . $list[$i]->stockroom_name . "</span>:<span>" . $list[$i]->quantity . "</span></div>";
			}
		}

		$template_desc = str_replace('{stockroom_detail}', $productinstock, $template_desc);

		return $template_desc;
	}

	public function getStockAmountImage($section_id = 0, $section = "product", $stock_amount = 0)
	{
		$list = array();

		if (USE_STOCKROOM == 1)
		{
			$db = JFactory::getDBO();

			if ($stock_amount == 0)
			{
				$stock_amount = $this->getStockAmountwithReserve($section_id, $section);
			}

			$query = "SELECT * FROM " . $this->_table_prefix . "stockroom_amount_image as sm LEFT JOIN "
				. $this->_table_prefix . "product_stockroom_xref AS sx ON sx.stockroom_id=sm.stockroom_id LEFT JOIN "
				. $this->_table_prefix . "stockroom AS s ON sx.stockroom_id=s.stockroom_id where  sx.quantity > 0 and sx.product_id= "
				. $section_id;

			$query1 = $query . " AND stock_option=2 AND stock_quantity='" . $stock_amount . "'";
			$db->setQuery($query1);
			$list = $db->loadObjectList();

			if (count($list) <= 0)
			{
				$query1 = $query . " AND stock_option=1 AND stock_quantity < '" . $stock_amount . "' ORDER BY stock_quantity DESC, s.max_del_time asc ";
				$db->setQuery($query1);
				$list = $db->loadObjectList();

				if (count($list) <= 0)
				{
					$query1 = $query . " AND stock_option=3 AND stock_quantity > '" . $stock_amount . "' ORDER BY stock_quantity ASC , s.max_del_time asc ";
					$db->setQuery($query1);
					$list = $db->loadObjectList();
				}
			}
		}

		return $list;
	}

	public function getReservedStock($section_id, $section = "product")
	{
		if (IS_PRODUCT_RESERVE && USE_STOCKROOM)
		{
			$db = JFactory::getDBO();
			$session_id = session_id();

			$query = "SELECT SUM(qty) FROM " . $this->_table_prefix . "cart "
				. "WHERE product_id='" . $section_id . "' "
				. "AND section='" . $section . "' ";
			$db->setQuery($query);
			$count = intval($db->loadResult());

			return $count;
		}

		return 0;
	}

	public function getCurrentUserReservedStock($section_id, $section = "product")
	{
		if (IS_PRODUCT_RESERVE && USE_STOCKROOM)
		{
			$db = JFactory::getDBO();
			$session_id = session_id();

			$query = "SELECT SUM(qty) FROM " . $this->_table_prefix . "cart "
				. "WHERE product_id='" . $section_id . "' "
				. "AND session_id='" . $session_id . "' "
				. "AND section='" . $section . "' ";
			$db->setQuery($query);
			$count = intval($db->loadResult());

			return $count;
		}

		return 0;
	}

	public function deleteExpiredCartProduct()
	{
		if (IS_PRODUCT_RESERVE)
		{
			$db = JFactory::getDBO();
			$time = time() - (CART_TIMEOUT * 60);

			$query = "DELETE FROM " . $this->_table_prefix . "cart "
				. "WHERE time < $time ";
			$db->setQuery($query);
			$db->query();
		}

		return true;
	}

	public function deleteCartAfterEmpty($section_id = 0, $section = "product")
	{
		if (IS_PRODUCT_RESERVE)
		{
			$db = JFactory::getDBO();
			$session_id = session_id();
			$and = "";

			if ($section_id != 0)
			{
				$and .= "AND product_id='" . $section_id . "' AND section='" . $section . "' ";
			}

			$query = "DELETE FROM " . $this->_table_prefix . "cart "
				. "WHERE session_id='" . $session_id . "' "
				. $and;
			$db->setQuery($query);
			$db->query();
		}

		return true;
	}

	public function addReservedStock($section_id, $quantity = 0, $section = "product")
	{
		if (IS_PRODUCT_RESERVE)
		{
			$db = JFactory::getDBO();
			$session_id = session_id();

			$time = time();
			$and = "AND session_id='" . $session_id . "' "
				. "AND product_id='" . $section_id . "' "
				. "AND section='" . $section . "' ";

			$sql = "SELECT COUNT(*) FROM " . $this->_table_prefix . "cart "
				. "WHERE 1=1 "
				. $and;
			$db->setQuery($sql);
			$count = intval($db->loadResult());

			if ($count)
			{
				$query = "UPDATE " . $this->_table_prefix . "cart "
					. "SET qty='" . $quantity . "', time='" . $time . "' "
					. "WHERE 1=1 "
					. $and;
			}
			else
			{
				$query = "INSERT INTO " . $this->_table_prefix . "cart "
					. "(session_id, product_id, qty, time, section) "
					. "VALUES ('" . $session_id . "', '" . $section_id . "', '" . $quantity . "', '" . $time . "', '" . $section . "')";
			}

			$db->setQuery($query);
			$db->query();
		}

		return true;
	}

	public function getStockroom($stockroom_id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id in  (' . $stockroom_id . ') and published=1';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Function to get min delivery time
	 */
	public function getStockroom_maxdelivery($stockroom_id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT max_del_time,delivery_time  FROM ' . $this->_table_prefix . 'stockroom WHERE stockroom_id in  ('
			. $stockroom_id . ') and published=1 order by max_del_time desc';

		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getdatediff($endDate, $beginDate)
	{
		$epoch_1 = mktime(0, 0, 0, date("m", $endDate), date("d", $endDate), date("Y", $endDate));
		$epoch_2 = mktime(0, 0, 0, date("m", $beginDate), date("d", $beginDate), date("Y", $beginDate));
		$dateDiff = $epoch_1 - $epoch_2;
		$fullDays = floor($dateDiff / (60 * 60 * 24));

		return $fullDays;
	}

	public function getFinalStockofProduct($product_id, $totalatt)
	{
		$producthelper = new producthelper;

		$isStockExists = $this->isStockExists($product_id);
		$isPreorderStockExists = '';

		if ($totalatt > 0 && !$isStockExists)
		{
			$property = $producthelper->getAttibuteProperty(0, 0, $product_id);

			for ($att_j = 0; $att_j < count($property); $att_j++)
			{
				$isSubpropertyStock = false;
				$sub_property = $producthelper->getAttibuteSubProperty(0, $property[$att_j]->property_id);

				for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
				{
					$isSubpropertyStock = $this->isStockExists($sub_property[$sub_j]->subattribute_color_id, 'subproperty');

					if ($isSubpropertyStock)
					{
						$isStockExists = $isSubpropertyStock;
						break;
					}
				}

				if ($isSubpropertyStock)
				{
					break;
				}
				else
				{
					$isPropertystock = $this->isStockExists($property[$att_j]->property_id, "property");

					if ($isPropertystock)
					{
						$isStockExists = $isPropertystock;
						break;
					}
				}
			}
		}

		return $isStockExists;
	}

	public function getFinalPreorderStockofProduct($product_id, $totalatt)
	{
		$producthelper = new producthelper;

		$isStockExists = $this->isPreorderStockExists($product_id);
		$isPreorderStockExists = '';

		if ($totalatt > 0 && !$isStockExists)
		{
			$property = $producthelper->getAttibuteProperty(0, 0, $product_id);

			for ($att_j = 0; $att_j < count($property); $att_j++)
			{
				$isSubpropertyStock = false;
				$sub_property = $producthelper->getAttibuteSubProperty(0, $property[$att_j]->property_id);

				for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
				{
					$isSubpropertyStock = $this->isPreorderStockExists($sub_property[$sub_j]->subattribute_color_id, 'subproperty');

					if ($isSubpropertyStock)
					{
						$isStockExists = $isSubpropertyStock;
						break;
					}
				}

				if ($isSubpropertyStock)
				{
					break;
				}
				else
				{
					$isPropertystock = $this->isPreorderStockExists($property[$att_j]->property_id, "property");

					if ($isPropertystock)
					{
						$isStockExists = $isPropertystock;
						break;
					}
				}
			}
		}

		return $isStockExists;
	}
}
