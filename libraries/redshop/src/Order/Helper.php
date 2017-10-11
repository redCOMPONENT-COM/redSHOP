<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Order;

defined('_JEXEC') or die;

/**
 * Utility helper
 *
 * @since  __DEPLOY_VERSION__
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
	 * @since   __DEPLOY_VERSION__
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
				$accessoryQuantity  = " [" . JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " " . $orderItemDatum->product_quantity . "] ";
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
}
