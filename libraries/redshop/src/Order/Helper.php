<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Order;

defined('_JEXEC') or die;

/**
 * Order helper
 *
 * @since  2.0.7
 */
class Helper
{
	/**
	 * Method for generate accessory of order.
	 *
	 * @param   integer  $orderItemId  Order item ID.
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function generateAccessories($orderItemId = 0)
	{
		$accessoryHtml = '';
		$orderItemData = \RedshopHelperOrder::getOrderItemAccessoryDetail($orderItemId);

		if (count($orderItemData) > 0)
		{
			$accessoryHtml .= '<div class="checkout_accessory_static">' . \JText::_("COM_REDSHOP_ACCESSORY") . ':</div>';

			foreach ($orderItemData as $orderItemDatum)
			{
				$accessoryQuantity  = " [" . \JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " " . $orderItemDatum->product_quantity . "] ";
				$accessoryHtml      .= "<div class='checkout_accessory_title'>"
					. urldecode($orderItemDatum->order_acc_item_name)
					. " ("
					. \RedshopHelperProductPrice::formattedPrice($orderItemDatum->order_acc_price + $orderItemDatum->order_acc_vat)
					. ")" . $accessoryQuantity . "</div>";
				$makeAttributeOrder = \productHelper::getInstance()->makeAttributeOrder($orderItemId, 1, $orderItemDatum->product_id);
				$accessoryHtml      .= $makeAttributeOrder->product_attribute;
			}
		}
		else
		{
			$orderItemData = \RedshopHelperOrder::getOrderItemDetail(0, 0, $orderItemId);
			$orderItemData = !empty($orderItemData) ? reset($orderItemData) : array();
			$accessoryHtml = !empty($orderItemData) ? $orderItemData->product_accessory : '';
		}

		return $accessoryHtml;
	}

	/**
	 * Redesign product item
	 *
	 * @param   array  $post  Data
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function redesignProductItem($post = array())
	{
		if (empty($post))
		{
			return array();
		}

		$orderItem = array();
		$i = -1;

		foreach ($post as $key => $value)
		{
			if (!strcmp("product", substr($key, 0, 7)) && strlen($key) < 10)
			{
				$i++;

				if (!isset($orderItem[$i]))
				{
					$orderItem[$i] = new \stdClass;
				}

				$orderItem[$i]->product_id = $value;
			}

			if (!strcmp("attribute_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->attribute_data = $value;
			}

			if (!strcmp("property_dataproduct", substr($key, 0, 20)))
			{
				$orderItem[$i]->property_data = $value;
			}

			if (!strcmp("subproperty_dataproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->subproperty_data = $value;
			}

			if (!strcmp("accessory_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->accessory_data = $value;
			}

			if (!strcmp("acc_attribute_dataproduct", substr($key, 0, 25)))
			{
				$orderItem[$i]->acc_attribute_data = $value;
			}

			if (!strcmp("acc_property_dataproduct", substr($key, 0, 24)))
			{
				$orderItem[$i]->acc_property_data = $value;
			}

			if (!strcmp("acc_subproperty_dataproduct", substr($key, 0, 27)))
			{
				$orderItem[$i]->acc_subproperty_data = $value;
			}

			if (!strcmp("extrafieldId", substr($key, 0, 12)))
			{
				$orderItem[$i]->extrafieldId = $value;
			}

			if (!strcmp("extrafieldname", substr($key, 0, 14)))
			{
				$orderItem[$i]->extrafieldname = $value;
			}

			if (!strcmp("wrapper_dataproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->wrapper_data = $value;
			}

			if (!strcmp("quantityproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->quantity = $value;
			}

			if (!strcmp("prdexclpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->prdexclprice = $value;
			}

			if (!strcmp("taxpriceproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->taxprice = $value;
			}

			if (!strcmp("productpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->productprice = $value;
			}

			if (!strcmp("requiedAttributeproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->requiedAttributeproduct = $value;
			}
		}

		return $orderItem;
	}
}
