<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper Stock Room
 *
 * @since  1.5
 */
class RedshopHelperStockroom
{
	/**
	 * Check already notified user
	 *
	 * @param   int  $userId         User id
	 * @param   int  $productId      Product id
	 * @param   int  $propertyId     Property id
	 * @param   int  $subPropertyId  Sub property id
	 *
	 * @return mixed
	 */
	public static function isAlreadyNotifiedUser($userId, $productId, $propertyId, $subPropertyId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id')
			->from($db->qn('#__redshop_notifystock_users'))
			->where('product_id = ' . (int) $productId)
			->where('property_id = ' . (int) $propertyId)
			->where('subproperty_id = ' . (int) $subPropertyId)
			->where('user_id = ' . (int) $userId)
			->where('notification_status = 0');

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Get stockroom
	 *
	 * @param   mixed    $stockroomId  stockroom id
	 * @param   int      $published    published/unpublished
	 * @param   boolean  $isChecked    checked use stockroom
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getStockroom($stockroomId = null, $published = null, $isChecked = false)
	{
		/**
		 * Check: If "Check stockroom config" is true, skip process if stockroom config is not use stockroom.
		 */
		if ($isChecked === true && Redshop::getConfig()->get('USE_STOCKROOM') != 1)
		{
			return array();
		}

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_stockroom'));

		// Convert stockroom ID to array
		if (!empty($stockroomId))
		{
			if (is_string($stockroomId))
			{
				$stockroomId = explode(',', $stockroomId);
			}
			elseif (is_int($stockroomId))
			{
				$stockroomId = array($stockroomId);
			}

			$query->where($db->qn('stockroom_id') . ' IN (' . implode(',', $stockroomId) . ')');
		}

		if (!empty($published))
		{
			$query->where($db->qn('published') . ' = ' . $db->quote((int) $published));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Check is stock exists
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function isStockExists($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$stock = self::getStockAmountwithReserve($sectionId, $section, $stockroomId);

			if ($stock > 0)
			{
				return true;
			}

			return false;
		}

		return true;
	}

	/**
	 * Check is attribute stock exists
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function isAttributeStockExists($productId)
	{
		$isStockExists = false;
		$productHelper = productHelper::getInstance();
		$property      = $productHelper->getAttibuteProperty(0, 0, $productId);

		for ($attJ = 0; $attJ < count($property); $attJ++)
		{
			$isSubpropertyStock = false;
			$subProperty        = $productHelper->getAttibuteSubProperty(0, $property[$attJ]->property_id);

			for ($subJ = 0; $subJ < count($subProperty); $subJ++)
			{
				$isSubpropertyStock = self::isStockExists($subProperty[$subJ]->subattribute_color_id, 'subproperty');

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
				$isPropertystock = self::isStockExists($property[$attJ]->property_id, "property");

				if ($isPropertystock)
				{
					$isStockExists = $isPropertystock;

					return $isStockExists;
				}
			}
		}

		return $isStockExists;
	}

	/**
	 * Check is pre-order stock exists
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function isPreorderStockExists($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$stock = self::getPreorderStockAmountwithReserve($sectionId, $section, $stockroomId);

			if ($stock > 0)
			{
				return true;
			}

			return false;
		}

		return true;
	}

	/**
	 * Check is attribute pre-order stock exists
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return  mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function isAttributePreorderStockExists($productId)
	{
		$productHelper         = productHelper::getInstance();
		$property              = $productHelper->getAttibuteProperty(0, 0, $productId);
		$isPreorderStockExists = false;

		for ($attJ = 0; $attJ < count($property); $attJ++)
		{
			$isSubpropertyStock = false;
			$subProperty        = $productHelper->getAttibuteSubProperty(0, $property[$attJ]->property_id);

			for ($subJ = 0; $subJ < count($subProperty); $subJ++)
			{
				$isSubpropertyStock = self::isPreorderStockExists($subProperty[$subJ]->subattribute_color_id, 'subproperty');

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
				$isPropertystock = self::isPreorderStockExists($property[$attJ]->property_id, "property");

				if ($isPropertystock)
				{
					$isPreorderStockExists = $isPropertystock;

					return $isPreorderStockExists;
				}
			}
		}

		return $isPreorderStockExists;
	}

	/**
	 * Get Stockroom Total amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getStockroomTotalAmount($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		$quantity = 1;

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$quantity        = self::getStockAmountwithReserve($sectionId, $section, $stockroomId);
			$reserveQuantity = self::getReservedStock($sectionId, $section);
			$quantity        = $quantity - $reserveQuantity;

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}

		return $quantity;
	}

	/**
	 * Get pre-order stockroom total amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getPreorderStockroomTotalAmount($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		$quantity = 1;

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$quantity = self::getPreorderStockAmountwithReserve($sectionId, $section, $stockroomId);

			$reserveQuantity = self::getReservedStock($sectionId, $section);
			$quantity        = $quantity - $reserveQuantity;

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}

		return $quantity;
	}

	/**
	 * Get Stock Amount with Reserve
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return int|mixed
	 */
	public static function getStockAmountWithReserve($sectionId = 0, $section = 'product', $stockroomId = 0)
	{
		$quantity = 1;

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			if ($section == 'product' && $stockroomId == 0 && $sectionId)
			{
				$sectionId = explode(',', $sectionId);
				$sectionId = ArrayHelper::toInteger($sectionId);
				$quantity  = 0;

				foreach ($sectionId as $item)
				{
					$productData = Redshop::product((int) $item);

					if (isset($productData->sum_quanity))
					{
						$quantity += $productData->sum_quanity;
					}
				}
			}
			else
			{
				$table = 'product';
				$db    = JFactory::getDbo();

				if ($section != 'product')
				{
					$table = 'product_attribute';
				}

				$query = $db->getQuery(true)
					->select('SUM(x.quantity)')
					->from($db->qn('#__redshop_' . $table . '_stockroom_xref', 'x'))
					->leftJoin($db->qn('#__redshop_stockroom', 's') . ' ON s.stockroom_id = x.stockroom_id')
					->where('x.quantity >= 0');

				if ($sectionId != 0)
				{
					$sectionId = explode(',', $sectionId);
					$sectionId = ArrayHelper::toInteger($sectionId);

					if ($section != 'product')
					{
						$query->where('x.section = ' . $db->quote($section))
							->where('x.section_id IN (' . implode(',', $sectionId) . ')');
					}
					else
					{
						$query->where('x.product_id IN (' . implode(',', $sectionId) . ')');
					}
				}

				if ($stockroomId != 0)
				{
					$query->where('x.stockroom_id = ' . (int) $stockroomId);
				}

				$db->setQuery($query);
				$quantity = $db->loadResult();
			}

			if ($quantity < 0)
			{
				$quantity = 0;
			}
		}

		if ($quantity == null)
		{
			$quantity = (Redshop::getConfig()->get('USE_BLANK_AS_INFINITE')) ? 1000000000 : 0;
		}

		return $quantity;
	}

	/**
	 * Get pre-order stockroom amount with reserve
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getPreorderStockAmountwithReserve($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		$quantity = 1;

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db    = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('SUM(x.preorder_stock) AS preorder_stock')
				->select('SUM(x.ordered_preorder) AS ordered_preorder')
				->from($db->qn($table, 'x'))
				->leftJoin(
					$db->qn('#__redshop_stockroom', 's') . ' ON '
					. $db->qn('x.stockroom_id') . ' = ' . $db->qn('s.stockroom_id')
				)
				->where($db->qn('x.quantity') . ' >= 0')
				->order($db->qn('s.min_del_time'));

			if ($sectionId != 0)
			{
				// Sanitize ids
				$sectionId = explode(',', $sectionId);
				$sectionId = ArrayHelper::toInteger($sectionId);

				if ($section != "product")
				{
					$query->where($db->qn('x.section') . ' = ' . $db->q($section))
						->where($db->qn('x.section_id') . ' IN (' . implode(',', $sectionId) . ')');
				}
				else
				{
					$query->where($db->qn('x.product_id') . ' IN (' . implode(',', $sectionId) . ')');
				}
			}

			if ($stockroomId != 0)
			{
				$query->where($db->qn('x.stockroom_id') . ' = ' . $db->q((int) $stockroomId));
			}

			$preOrderStock = $db->setQuery($query)->loadObjectList();

			if ($preOrderStock[0]->ordered_preorder == $preOrderStock[0]->preorder_stock
				|| $preOrderStock[0]->ordered_preorder > $preOrderStock[0]->preorder_stock)
			{
				$quantity = 0;
			}
			else
			{
				$quantity = $preOrderStock[0]->preorder_stock - $preOrderStock[0]->ordered_preorder;
			}
		}

		return $quantity;
	}

	/**
	 * Get stockroom amount detail list
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getStockroomAmountDetailList($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		$list = array();

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('DISTINCT(s.stockroom_id), s.*, x.*')
				->from($db->qn($table, 'x'))
				->leftJoin(
					$db->qn('#__redshop_stockroom', 's') . ' ON '
					. $db->qn('x.stockroom_id') . ' = ' . $db->qn('s.stockroom_id')
				)
				->order($db->qn('s.min_del_time'));

			if ($sectionId != 0)
			{
				if ($section != "product")
				{
					$query->where($db->qn('x.section') . ' = ' . $db->q($section))
						->where($db->qn('x.section_id') . ' = ' . $db->q((int) $sectionId));
				}
				else
				{
					$query->where($db->qn('x.product_id') . ' =' . $db->q((int) $sectionId));
				}
			}

			if ($stockroomId != 0)
			{
				$query->where($db->qn('x.stockroom_id') . ' = ' . $db->q((int) $stockroomId));
			}

			$list = $db->setQuery($query)->loadObjectList();
		}

		return $list;
	}

	/**
	 * Get pre-order stockroom amount detail list
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getPreorderStockroomAmountDetailList($sectionId = 0, $section = "product", $stockroomId = 0)
	{
		$list = array();

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('DISTINCT(s.stockroom_id), s.*, x.*')
				->from($db->qn($table, 'x'))
				->leftJoin(
					$db->qn('#__redshop_stockroom', 's') . ' ON '
					. $db->qn('x.stockroom_id') . ' = ' . $db->qn('s.stockroom_id')
				)
				->where($db->qn('x.preorder_stock') . ' >= ' . $db->qn('x.ordered_preorder'))
				->order($db->qn('s.min_del_time'));

			if ($sectionId != 0)
			{
				if ($section != "product")
				{
					$query->where($db->qn('x.section') . ' = ' . $db->q($section))
						->where($db->qn('x.section_id') . ' = ' . $db->q((int) $sectionId));
				}
				else
				{
					$query->where($db->qn('x.product_id') . ' =' . $db->q((int) $sectionId));
				}
			}

			if ($stockroomId != 0)
			{
				$query->where($db->qn('x.stockroom_id') . ' = ' . $db->q((int) $stockroomId));
			}

			$list = $db->setQuery($query)->loadObjectList();
		}

		return $list;
	}

	/**
	 * Update stockroom quantity
	 *
	 * @param   int     $sectionId  Section id
	 * @param   int     $quantity   Stockroom quantity
	 * @param   string  $section    Section
	 * @param   int     $productId  Product id
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function updateStockroomQuantity($sectionId = 0, $quantity = 0, $section = "product", $productId = 0)
	{
		$affectedRow       = array();
		$stockroomQuantity = array();

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$stockrooms = self::getStockroomAmountDetailList($sectionId, $section);

			foreach ($stockrooms as $stockroom)
			{
				if ($stockroom->quantity < $quantity)
				{
					$quantity          = $quantity - $stockroom->quantity;
					$remainingQuantity = $stockroom->quantity;
				}
				else
				{
					$remainingQuantity = $quantity;
					$quantity          -= $remainingQuantity;
				}

				if ($remainingQuantity > 0)
				{
					self::updateStockAmount($sectionId, $remainingQuantity, $stockroom->stockroom_id, $section);
					$affectedRow[]       = $stockroom->stockroom_id;
					$stockroomQuantity[] = $remainingQuantity;
				}

				$stockroomDetail = self::getStockroomAmountDetailList($sectionId, $section, $stockroom->stockroom_id);
				$remaining       = $stockroomDetail[0]->quantity - $quantity;

				if (Redshop::getConfig()->get('ENABLE_STOCKROOM_NOTIFICATION') == 1
					&& $remaining <= Redshop::getConfig()->get('DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER'))
				{
					$dispatcher = RedshopHelperUtility::getDispatcher();
					JPluginHelper::importPlugin('redshop_alert');
					$productId   = ($section == "product") ? $sectionId : $productId;
					$productData = Redshop::product((int) $productId);

					$message = JText::sprintf(
						'COM_REDSHOP_ALERT_STOCKROOM_BELOW_AMOUNT_NUMBER',
						$productData->product_id,
						$productData->product_name,
						$productData->product_number,
						$remaining,
						$stockroomDetail[0]->stockroom_name
					);

					$dispatcher->trigger('storeAlert', array($message));
					$dispatcher->trigger('sendEmail', array($message));
				}
			}

			// For preorder stock
			if ($quantity > 0)
			{
				$preorderList = self::getPreorderStockroomAmountDetailList($sectionId, $section);

				if ($section == "product")
				{
					$productData = Redshop::product((int) $sectionId);
				}
				else
				{
					$productData = Redshop::product((int) $productId);
				}

				if ($productData->preorder == "yes" || ($productData->preorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER'))
					|| ($productData->preorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
				{
					for ($i = 0, $in = count($preorderList); $i < $in; $i++)
					{
						if ($preorderList[$i]->preorder_stock < $quantity)
						{
							$quantity          = $quantity - $preorderList[$i]->preorder_stock;
							$remainingQuantity = $preorderList[$i]->preorder_stock;
						}
						else
						{
							$remainingQuantity = $quantity;
							$quantity          -= $remainingQuantity;
						}

						if ($remainingQuantity > 0)
						{
							$dispatcher = RedshopHelperUtility::getDispatcher();
							JPluginHelper::importPlugin('redshop_stockroom');
							$dispatcher->trigger('onUpdateStockroomQuantity', array($section, $product_data));
							self::updatePreorderStockAmount($sectionId, $remainingQuantity, $stockroom->stockroom_id, $section);
						}
					}
				}
			}
		}

		$list                                   = implode(",", $affectedRow);
		$stockroomQuantityList                  = implode(",", $stockroomQuantity);
		$resultArray                            = array();
		$resultArray['stockroom_list']          = $list;
		$resultArray['stockroom_quantity_list'] = $stockroomQuantityList;

		return $resultArray;
	}

	/**
	 * Update stockroom amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function updateStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		if (!Redshop::getConfig()->get('USE_STOCKROOM') || !$sectionId)
		{
			return true;
		}

		$table = "#__redshop_product_stockroom_xref";

		if ($section != "product")
		{
			$table = "#__redshop_product_attribute_stockroom_xref";
		}

		$db = JFactory::getDbo();

		$fields = array(
			$db->qn('quantity') . ' = ' . $db->qn('quantity') . ' - ' . (int) $quantity
		);

		$conditions = array(
			$db->qn('stockroom_id') . ' = ' . (int) $stockroomId,
			$db->qn('quantity') . ' > 0'
		);

		if ($section != "product")
		{
			$conditions[] = $db->qn('section') . ' = ' . $db->quote($section);
			$conditions[] = $db->qn('section_id') . ' = ' . (int) $sectionId;
		}
		else
		{
			$conditions[] = $db->qn('product_id') . ' = ' . (int) $sectionId;
		}

		$query = $db->getQuery(true)
			->update($db->qn($table))
			->set($fields)
			->where($conditions);

		return $db->setQuery($query)->execute();
	}

	/**
	 * Update pre-order stock amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function updatePreorderStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1 && $sectionId != 0 && trim($sectionId) != "")
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db = JFactory::getDbo();

			$fields = array(
				$db->qn('ordered_preorder') . ' = ' . $db->qn('ordered_preorder') . ' + ' . $db->q((int) $quantity)
			);

			$conditions = array(
				$db->qn('stockroom_id') . ' = ' . $db->q((int) $stockroomId)
			);

			if ($section != "product")
			{
				$conditions[] = $db->qn('section') . ' = ' . $db->q($section);
				$conditions[] = $db->qn('section_id') . ' = ' . $db->q((int) $sectionId);
			}
			else
			{
				$conditions[] = $db->qn('product_id') . ' = ' . $db->q((int) $sectionId);
			}

			$query = $db->getQuery(true)
				->update($db->qn($table))
				->set($fields)
				->where($conditions);

			$db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 * Manage stock amount
	 *
	 * @param   int     $sectionId    Section id
	 * @param   int     $quantity     Stockroom quantity
	 * @param   int     $stockroomId  Stockroom id
	 * @param   string  $section      Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function manageStockAmount($sectionId = 0, $quantity = 0, $stockroomId = 0, $section = "product")
	{
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db         = JFactory::getDbo();
			$conditions = array();

			if ($sectionId != 0 && trim($sectionId) != "")
			{
				if ($section != "product")
				{
					$conditions[] = $db->qn('section') . ' = ' . $db->q($section);
					$conditions[] = $db->qn('section_id') . ' = ' . $db->q((int) $sectionId);
				}
				else
				{
					$conditions[] = $db->qn('product_id') . ' = ' . $db->q((int) $sectionId);
				}
			}

			$stockId  = explode(",", $stockroomId);
			$stockQty = explode(",", $quantity);

			for ($i = 0, $in = count($stockId); $i < $in; $i++)
			{
				if ($stockId[$i] != "" && $sectionId != "" && $sectionId != 0)
				{
					$fields = array(
						$db->qn('quantity') . ' = ' . $db->qn('quantity') . ' + ' . $db->q((int) $stockQty[$i])
					);

					$conditions[] = $db->qn('stockroom_id') . ' = ' . $db->q((int) $stockId[$i]);

					$query = $db->getQuery(true)
						->update($db->qn($table))
						->set($fields)
						->where($conditions);

					$db->setQuery($query)->execute();

					$affectedRow = $db->getAffectedRows();

					if ($affectedRow > 0)
					{
						break;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Replace stockroom amount detail
	 *
	 * @param   string  $templateDesc  Template content
	 * @param   int     $sectionId     Section id
	 * @param   string  $section       Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function replaceStockroomAmountDetail($templateDesc = "", $sectionId = 0, $section = "product")
	{
		if (strpos($templateDesc, '{stockroom_detail}') !== false)
		{
			$productinstock = "";

			if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
			{
				$list = self::getStockroomAmountDetailList($sectionId, $section);

				$productinstock = RedshopLayoutHelper::render(
					'product.stockroom_detail',
					array(
						'stockroomDetails' => $list
					)
				);
			}

			$templateDesc = str_replace('{stockroom_detail}', $productinstock, $templateDesc);
		}

		return $templateDesc;
	}

	/**
	 * Get stock amount image
	 *
	 * @param   int     $sectionId    Section id
	 * @param   string  $section      Section
	 * @param   int     $stockAmount  Stockroom amount
	 *
	 * @return  array
	 *
	 * @since  2.0.0.3
	 */
	public static function getStockAmountImage($sectionId = 0, $section = "product", $stockAmount = 0)
	{
		$list = array();

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('DISTINCT(sm.stock_amount_id), sm.*')
				->from($db->qn('#__redshop_stockroom_amount_image', 'sm'))
				->leftJoin(
					$db->qn('#__redshop_product_stockroom_xref', 'sx') . ' ON '
					. $db->qn('sx.stockroom_id') . ' = ' . $db->qn('sm.stockroom_id')
				)
				->leftJoin(
					$db->qn('#__redshop_stockroom', 's') . ' ON '
					. $db->qn('sx.stockroom_id') . ' = ' . $db->qn('s.stockroom_id')
				)
				->where($db->qn('stock_option') . ' = 2')
				->where($db->qn('stock_quantity') . ' = ' . (int) $stockAmount);

			$list = $db->setQuery($query)->loadObjectList();

			if (count($list) <= 0)
			{
				$query->clear('where')
					->where($db->qn('stock_option') . ' = 1')
					->where($db->qn('stock_quantity') . ' < ' . $db->q((int) $stockAmount))
					->order($db->qn('stock_quantity') . ' ASC')
					->order($db->qn('s.max_del_time') . ' ASC');

				$list = $db->setQuery($query)->loadObjectList();

				if (count($list) <= 0)
				{
					$query->clear('where')
						->where($db->qn('stock_option') . ' = 3')
						->where($db->qn('stock_quantity') . ' > ' . $db->q((int) $stockAmount))
						->order($db->qn('stock_quantity') . ' ASC')
						->order($db->qn('s.max_del_time') . ' ASC');

					$list = $db->setQuery($query)->loadObjectList();
				}
			}
		}

		return $list;
	}

	/**
	 * Get reserved Stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 *
	 * @return  int
	 *
	 * @since  2.0.0.3
	 */
	public static function getReservedStock($sectionId, $section = "product")
	{
		if (!Redshop::getConfig()->get('IS_PRODUCT_RESERVE') || !Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			return 0;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('SUM(qty)')
			->from($db->qn('#__redshop_cart'))
			->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId))
			->where($db->qn('section') . ' = ' . $db->q($section));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Get current User reserved stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getCurrentUserReservedStock($sectionId, $section = "product")
	{
		if (Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			$db        = JFactory::getDbo();
			$sessionId = session_id();

			$query = $db->getQuery(true)
				->select('SUM(qty)')
				->from($db->qn('#__redshop_cart'))
				->where($db->qn('product_id') . ' = ' . (int) $sectionId)
				->where($db->qn('session_id') . ' = ' . $db->q($sessionId))
				->where($db->qn('section') . ' = ' . $db->q($section));

			return (int) $db->setQuery($query)->loadResult();
		}

		return 0;
	}

	/**
	 * Delete expired cart product
	 *
	 * @return  boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function deleteExpiredCartProduct()
	{
		if (!Redshop::getConfig()->get('IS_PRODUCT_RESERVE') || !Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			return false;
		}

		$db   = JFactory::getDBO();
		$time = time() - (Redshop::getConfig()->get('CART_TIMEOUT') * 60);

		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_cart'))
			->where($db->qn('time') . ' < ' . $db->quote($time));

		return (boolean) $db->setQuery($query)->execute();
	}

	/**
	 * Delete cart after empty
	 *
	 * @param   int     $sectionId  Section id
	 * @param   string  $section    Section
	 * @param   int     $quantity   Stockroom quantity
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function deleteCartAfterEmpty($sectionId = 0, $section = "product", $quantity = 0)
	{
		if (Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->where($db->qn('session_id') . ' = ' . $db->q(session_id()));

			if ($sectionId != 0)
			{
				$query->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId))
					->where($db->qn('section') . ' = ' . $db->q($section));
			}

			if ($quantity)
			{
				$query->select($db->qn('qty'))
					->from($db->qn('#__redshop_cart'));

				$qty = (int) $db->setQuery($query)->loadResult();
				$query->clear('select')
					->clear('from');

				if ($qty - (int) $quantity > 0)
				{
					$query->update($db->qn('#__redshop_cart'))
						->set($db->qn('qty') . ' = ' . $db->q(($qty - (int) $quantity)));
				}
				else
				{
					$query->delete($db->qn('#__redshop_cart'));
				}
			}
			else
			{
				$query->delete($db->qn('#__redshop_cart'));
			}

			$db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 * Add reserved stock
	 *
	 * @param   int     $sectionId  Section id
	 * @param   int     $quantity   Stockroom quantity
	 * @param   string  $section    Section
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function addReservedStock($sectionId, $quantity = 0, $section = "product")
	{
		if (Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			$db        = JFactory::getDBO();
			$sessionId = session_id();
			$time      = time();

			$query = $db->getQuery(true)
				->select($db->qn('qty'))
				->from($db->qn('#__redshop_cart'))
				->where($db->qn('session_id') . ' = ' . $db->q($sessionId))
				->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId))
				->where($db->qn('section') . ' = ' . $db->q($section));

			$qty = $db->setQuery($query)->loadResult();

			if ($qty !== null)
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_cart'))
					->set($db->qn('qty') . ' = ' . $db->q((int) $quantity))
					->set($db->qn('time') . ' = ' . $db->q($time))
					->where($db->qn('session_id') . ' = ' . $db->q($sessionId))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId))
					->where($db->qn('section') . ' = ' . $db->q($section));
			}
			else
			{
				$query = $db->getQuery(true)
					->insert($db->qn('#__redshop_cart'))
					->columns(array($db->qn('session_id'), $db->qn('product_id'), $db->qn('qty'), $db->qn('time'), $db->qn('section')))
					->values($db->q($sessionId) . ',' . $db->q((int) $sectionId) . ',' . $db->q((int) $quantity) . ',' . $db->q($time) . ',' . $db->q($section));
			}

			$db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 * Get min delivery time
	 *
	 * @param   mixed  $stockroomId  Stockroom id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0.3
	 */
	public static function getStockroomMaxDelivery($stockroomId)
	{
		// Sanitize ids
		$stockroomId = explode(',', $stockroomId);
		$stockroomId = ArrayHelper::toInteger($stockroomId);

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('max_del_time'))
			->select($db->qn('delivery_time'))
			->from($db->qn('#__redshop_stockroom'))
			->where($db->qn('stockroom_id') . ' IN (' . implode(',', $stockroomId) . ')')
			->where($db->qn('published') . ' = 1')
			->order($db->qn('max_del_time') . ' DESC');

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 * Get date diff
	 *
	 * @param   int  $endDate    End date
	 * @param   int  $beginDate  Begin date
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getDateDiff($endDate, $beginDate)
	{
		$epoch_1  = mktime(0, 0, 0, date("m", $endDate), date("d", $endDate), date("Y", $endDate));
		$epoch_2  = mktime(0, 0, 0, date("m", $beginDate), date("d", $beginDate), date("Y", $beginDate));
		$dateDiff = $epoch_1 - $epoch_2;
		$fullDays = floor($dateDiff / (60 * 60 * 24));

		return $fullDays;
	}

	/**
	 * Get final stock of product
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $totalAtt   Total attribute
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getFinalStockofProduct($productId, $totalAtt)
	{
		$productHelper = productHelper::getInstance();

		$isStockExists = self::isStockExists($productId);

		if ($totalAtt > 0 && !$isStockExists)
		{
			$property = $productHelper->getAttibuteProperty(0, 0, $productId);

			for ($attJ = 0; $attJ < count($property); $attJ++)
			{
				$isSubpropertyStock = false;
				$subProperty        = $productHelper->getAttibuteSubProperty(0, $property[$attJ]->property_id);

				for ($subJ = 0; $subJ < count($subProperty); $subJ++)
				{
					$isSubpropertyStock = self::isStockExists($subProperty[$subJ]->subattribute_color_id, 'subproperty');

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
					$isPropertystock = self::isStockExists($property[$attJ]->property_id, "property");

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

	/**
	 * Get final pre-order stock of product
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $totalAtt   Total attribute
	 *
	 * @return mixed
	 *
	 * @since  2.0.0.3
	 */
	public static function getFinalPreorderStockofProduct($productId, $totalAtt)
	{
		$isStockExists = self::isPreorderStockExists($productId);

		if ($totalAtt > 0 && !$isStockExists)
		{
			$property = RedshopHelperProduct_Attribute::getAttributeProperties(0, 0, $productId);

			for ($attJ = 0; $attJ < count($property); $attJ++)
			{
				$isSubpropertyStock = false;
				$subProperty        = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property[$attJ]->property_id);

				for ($subJ = 0; $subJ < count($subProperty); $subJ++)
				{
					$isSubpropertyStock = self::isPreorderStockExists($subProperty[$subJ]->subattribute_color_id, 'subproperty');

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
					$isPropertystock = self::isPreorderStockExists($property[$attJ]->property_id, "property");

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

	/**
	 * Get Stock Amount with Reserve
	 *
	 * @param   array   $sectionIds   Array of section id.
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	public static function getMultiSectionsStock($sectionIds = array(), $section = 'product', $stockroomId = 0)
	{
		if (empty($sectionIds))
		{
			return array();
		}

		$quantities = array();
		$sectionIds = ArrayHelper::toInteger($sectionIds);

		if ($section == 'product' && $stockroomId == 0 && $sectionIds)
		{
			foreach ($sectionIds as $item)
			{
				$productData       = Redshop::product((int) $item);
				$quantities[$item] = !empty($productData->sum_quanity) ? (int) $productData->sum_quanity : 0;
			}
		}
		else
		{
			if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
			{
				$db         = JFactory::getDbo();
				$isInfinite = (boolean) Redshop::getConfig()->get('USE_BLANK_AS_INFINITE', 0);
				$query      = $db->getQuery(true);

				$column     = 'p.product_id';
				$table      = '#__redshop_product';
				$stockTable = '#__redshop_product_stockroom_xref';
				$stockColumn = 'x.product_id';

				if ($section == 'property')
				{
					$column = 'p.property_id';
					$table  = '#__redshop_product_attribute_property';
					$stockTable = '#__redshop_product_attribute_stockroom_xref';
					$stockColumn = 'x.section_id';
				}
				elseif ($section == 'subproperty')
				{
					$column = 'p.subattribute_color_id';
					$table  = '#__redshop_product_subattribute_color';
					$stockTable = '#__redshop_product_attribute_stockroom_xref';
					$stockColumn = 'x.section_id';
				}

				$query->select($db->qn($column, 'id'))
					->from($db->qn($table, 'p'))
					->group($db->qn('id'));

				if ($section != 'product')
				{
					$query->leftJoin(
						$db->qn($stockTable, 'x') . ' ON ' . $db->qn($stockColumn) . ' = ' . $db->qn($column)
						. ' AND ' . $db->qn('x.section') . ' = ' . $db->quote($section)
					);
				}
				else
				{
					$query->leftJoin($db->qn($stockTable, 'x') . ' ON ' . $db->qn($stockColumn) . ' = ' . $db->qn($column));
				}

				$query->leftJoin($db->qn('#__redshop_stockroom', 's') . ' ON ' . $db->qn('s.stockroom_id') . ' = ' . $db->qn('x.stockroom_id'));

				if ($sectionIds)
				{
					$query->where($db->qn($column) . ' IN (' . implode(',', $sectionIds) . ')');
				}

				if (!$isInfinite)
				{
					$query->select($db->qn('x.quantity'))
						->where($db->qn('x.quantity') . ' >= 0');
				}
				else
				{
					$query->select(
						'IF(SUM(' . $db->qn('x.quantity') . ') IS NULL, 1000000000, SUM(' . $db->qn('x.quantity') . ')) AS ' . $db->qn('quantity')
					);
				}

				if ($stockroomId != 0)
				{
					$query->where($db->qn('x.stockroom_id') . ' = ' . (int) $stockroomId);
				}

				$db->setQuery($query);
				$results = $db->loadObjectList();

				if (!empty($results))
				{
					foreach ($results as $result)
					{
						$quantities[$result->id] = $result->quantity;
					}
				}
			}
			else
			{
				foreach ($sectionIds as $item)
				{
					$quantities[$item] = 1;
				}
			}
		}

		return $quantities;
	}

	/**
	 * Get pre-order stockroom amount with reserve
	 *
	 * @param   array   $sectionIds   Section id
	 * @param   string  $section      Section
	 * @param   int     $stockroomId  Stockroom id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.4
	 */
	public static function getMultiSectionsPreOrderStock($sectionIds = array(), $section = 'product', $stockroomId = 0)
	{
		if (empty($sectionIds))
		{
			return array();
		}

		$quantities = array();
		$sectionIds = ArrayHelper::toInteger($sectionIds);

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$table = "#__redshop_product_stockroom_xref";

			if ($section != "product")
			{
				$table = "#__redshop_product_attribute_stockroom_xref";
			}

			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('SUM(x.preorder_stock) AS preorder_stock')
				->select('SUM(x.ordered_preorder) AS ordered_preorder')
				->select($db->qn('x.section_id'))
				->from($db->qn($table, 'x'))
				->leftJoin(
					$db->qn('#__redshop_stockroom', 's') . ' ON '
					. $db->qn('x.stockroom_id') . ' = ' . $db->qn('s.stockroom_id')
				)
				->where($db->qn('x.quantity') . ' >= 0')
				->order($db->qn('s.min_del_time'));

			if (!empty($sectionIds))
			{
				if ($section != "product")
				{
					$query->where($db->qn('x.section') . ' = ' . $db->q($section))
						->where($db->qn('x.section_id') . ' IN (' . implode(',', $sectionIds) . ')');
				}
				else
				{
					$query->where($db->qn('x.product_id') . ' IN (' . implode(',', $sectionIds) . ')');
				}
			}

			if ($stockroomId != 0)
			{
				$query->where($db->qn('x.stockroom_id') . ' = ' . $db->q((int) $stockroomId));
			}

			$query->group($db->qn('x.section_id'));

			$db->setQuery($query);
			$results = $db->loadObjectList('section_id');

			if (!empty($results))
			{
				foreach ($results as $result)
				{
					if ($result->ordered_preorder == $result->preorder_stock || $result->ordered_preorder > $result->preorder_stock)
					{
						$quantity = 0;
					}
					else
					{
						$quantity = $result->preorder_stock - $results->ordered_preorder;
					}

					$quantities[$result->section_id] = $quantity;
				}
			}
		}

		return $quantities;
	}
}
