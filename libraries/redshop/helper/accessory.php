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
 * Class Redshop Helper for Product Accessory
 *
 * @since  1.5
 */
class RedshopHelperAccessory
{
	/**
	 * @var  array
	 */
	protected static $accessories = array();

	/**
	 * Prepare and collect accessory as a product information from cart.
	 *
	 * @param   array  $data  Information array from cart
	 *
	 * @return  object        Accessory as product information
	 */
	public static function getAccessoryAsProduct($data)
	{
		$collection = new stdClass;

		if (!Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
		{
			return $collection;
		}

		// Check the accessory as product ids are not null
		if ($data[0] != "" && $data[0] != 0)
		{
			$collection->accessory_data       = explode("@@", $data[0]);
			$collection->acc_quantity_data    = explode("@@", $data[1]);
			$collection->acc_attribute_data   = explode("@@", $data[2]);
			$collection->acc_property_data    = explode("@@", $data[3]);
			$collection->acc_subproperty_data = explode("@@", $data[4]);
			$productHelper = productHelper::getInstance();

			foreach ($collection->accessory_data as $key => $accessoryId)
			{
				$accessoryDetail       = $productHelper->getProductAccessory($accessoryId);
				$object                = $accessoryDetail[0];
				$object->quantity      = $collection->acc_quantity_data[$key];
				$object->attributes    = $collection->acc_attribute_data[$key];
				$object->properties    = $collection->acc_property_data[$key];
				$object->subproperties = $collection->acc_subproperty_data[$key];

				$info[$object->child_product_id] = $object;
			}

			$collection->accessory = $info;
		}

		return $collection;
	}

	/**
	 * Method for get Product Accessories.
	 *
	 * @param   string  $accessoryId     ID of accessory.
	 * @param   string  $productId       ID of product.
	 * @param   int     $childProductId  ID of child product.
	 * @param   int     $categoryId      ID of category.
	 *
	 * @return  array                 List of accessories.
	 *
	 * @since   2.0.3
	 */
	public static function getProductAccessories($accessoryId = '', $productId = '', $childProductId = 0, $categoryId = 0)
	{
		$key = $accessoryId . '_' . $productId . '_' . $childProductId . '_' . $categoryId;

		if (!array_key_exists($key, static::$accessories))
		{
			$db = JFactory::getDbo();

			$orderBy = " ORDER BY a.child_product_id ASC";

			if (Redshop::getConfig()->get('DEFAULT_ACCESSORY_ORDERING_METHOD'))
			{
				$orderBy = " ORDER BY " . Redshop::getConfig()->get('DEFAULT_ACCESSORY_ORDERING_METHOD');
			}

			$and     = "";
			$groupBy = "";

			if ($accessoryId != 0)
			{
				// Sanitize ids
				$accessoryId = explode(',', $accessoryId);
				$accessoryId = ArrayHelper::toInteger($accessoryId);

				$and .= " AND a.accessory_id IN (" . implode(',', $accessoryId) . ")";
			}

			if ($productId != 0)
			{
				// Sanitize ids
				$productId = explode(',', $productId);
				$productId = ArrayHelper::toInteger($productId);

				$and .= " AND a.product_id IN (" . implode(',', $productId) . ")";
			}

			if ($childProductId != 0)
			{
				$and .= " AND a.child_product_id = " . (int) $childProductId;
			}

			if ($categoryId != 0)
			{
				$and    .= " AND a.category_id = " . (int) $categoryId;
				$groupBy = " GROUP BY a.child_product_id";
			}

			$switchQuery = ", CASE a.oprand "
				. "WHEN '+' THEN IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) + accessory_price "
				. "WHEN '-' THEN IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) - accessory_price "
				. "WHEN '=' THEN accessory_price "
				. "END AS newaccessory_price ";

			$priceQuery = "IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) AS accessory_main_price ";

			$query = "SELECT a.*,p.product_number, p.product_name, " . $priceQuery
				. ", p.product_s_desc, p.product_full_image, p.product_on_sale "
				. $switchQuery
				. "FROM " . $db->qn('#__redshop_product_accessory') . " AS a "
				. "LEFT JOIN " . $db->qn('#__redshop_product') . " AS p ON p.product_id = a.child_product_id "
				. "WHERE p.published = 1 "
				. $and . $groupBy
				. $orderBy;

			static::$accessories[$key] = $db->setQuery($query)->loadObjectList();
		}

		return static::$accessories[$key];
	}

	/**
	 * Check if Accessory is existed
	 *
	 * @param   integer  $productId    Product ID
	 * @param   integer  $accessoryId  Accessory ID
	 *
	 * @return integer
	 *
	 * @since  2.0.0.3
	 */
	public static function checkAccessoryExists($productId, $accessoryId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn(array('pa.accessory_id', 'pa.product_id')))
			->from($db->qn('#__redshop_product_accessory', 'pa'))
			->where($db->qn('pa.product_id') . ' = ' . (int) $productId)
			->where($db->qn('pa.child_product_id') . ' = ' . (int) $accessoryId);

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
