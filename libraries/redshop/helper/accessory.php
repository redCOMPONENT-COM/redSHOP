<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Product Accessory
 *
 * @since  1.5
 */
class RedshopHelperAccessory
{
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

		if (!ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
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
			$productHelper = RedshopSiteProduct::getInstance();

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
}
