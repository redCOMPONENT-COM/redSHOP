<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Shipping
 *
 * @since  2.0.0.3
 */
class RedshopHelperShipping
{
	/**
	 * Get Shipping rate for cart
	 *
	 * @param   array  $data  Shipping data
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getDefaultShipping($data)
	{
		$productHelper = productHelper::getInstance();
		$userHelper    = rsUserHelper::getInstance();
		$session       = JFactory::getSession();
		$orderSubtotal = $data['order_subtotal'];
		$user          = JFactory::getUser();
		$userId        = $user->id;
		$db            = JFactory::getDbo();

		$totalDimention  = self::getCartItemDimention();
		$weightTotal     = $totalDimention['totalweight'];
		$volume          = $totalDimention['totalvolume'];

		$orderFunctions = order_functions::getInstance();
		$userInfo       = $orderFunctions->getBillingAddress($userId);
		$country        = '';
		$state          = '';
		$isCompany      = '';
		$newPwhere      = '';
		$newCwhere      = '';
		$whereState     = '';
		$whereShopper   = '';

		if ($userInfo)
		{
			$country   = $userInfo->country_code;
			$isCompany = $userInfo->is_company;
			$userId    = $userInfo->user_id;
			$state     = $userInfo->state_code;
		}

		$shopperGroup = $userHelper->getShoppergroupData($userId);

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;
			$whereShopper     = ' AND (FIND_IN_SET(' . $db->q((int) $shopperGroupId) . ', '
				. $db->qn('shipping_rate_on_shopper_group') . ' ) OR '
				. $db->qn('shipping_rate_on_shopper_group') . ' = "") ';
		}

		if ($country)
		{
			$whereCountry = '(FIND_IN_SET(' . $db->q($country) . ', ' . $db->qn('shipping_rate_country') . ') OR ' . $db->qn('shipping_rate_country') . ' = 0 OR ' . $db->qn('shipping_rate_country') . ' = "")';
		}
		else
		{
			$whereCountry = '(FIND_IN_SET(' . $db->q(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ', '
				. $db->qn('shipping_rate_country') . ') OR '
				. $db->qn('shipping_rate_country') . ' = 0 OR '
				. $db->qn('shipping_rate_country') . ' = "")';
		}

		if ($state)
		{
			$whereState = ' AND (FIND_IN_SET(' . $db->q($state) . ', '
				. $db->qn('shipping_rate_state') . ') OR '
				. $db->qn('shipping_rate_state') . ' = 0 OR '
				. $db->qn('shipping_rate_state') . ' = "")';
		}

		if (!$isCompany)
		{
			$isWhere = ' AND (' . $db->qn('company_only') . ' = 2 OR ' . $db->qn('company_only') . ' = 0) ';
		}
		else
		{
			$isWhere = ' AND (' . $db->qn('company_only') . ' = 1 OR ' . $db->qn('company_only') . ' = 0) ';
		}

		$shippingArr = self::getShopperGroupDefaultShipping();

		if (empty($shippingArr))
		{
			$cart        = $session->get('cart');
			$idx         = (int) ($cart ['idx']);
			$shippingRate = array();

			if ($idx)
			{
				$pWhere = 'AND ( ';

				for ($i = 0; $i < $idx; $i++)
				{
					$product_id = $cart[$i]['product_id'];
					$pWhere     .= 'FIND_IN_SET(' . $db->qn((int) $product_id) . ', ' . $db->qn('shipping_rate_on_product') . ')';

					if ($i != $idx - 1)
					{
						$pWhere .= " OR ";
					}
				}

				$pWhere    .= ")";
				$newPwhere = str_replace("AND (", "OR (", $pWhere);
				$sql       = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr "
							. "LEFT JOIN " . $db->qn('#__extensions') . " AS s ON " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
		 	     				 WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND " . $db->qn('s.enabled') . " = 1 AND " . $whereCountry . $isWhere . "
								 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND " . $db->qn('shipping_rate_volume_end') . " >= "
					. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
								 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND " . $db->qn('shipping_rate_ordertotal_end') . " >= "
					. $db->q($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
								 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND " . $db->qn('shipping_rate_weight_end') . " >= "
					. $db->q($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
					. $pWhere . $whereState . $whereShopper . "
								   ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

				$shippingRate = $db->setQuery($sql)->loadObject();
			}

			if (!$shippingRate)
			{
				for ($i = 0; $i < $idx; $i++)
				{
					$productId = $cart[$i]['product_id'];
					$sel = 'SELECT ' . $db->qn('category_id') . ' FROM ' . $db->qn('#__redshop_product_category_xref') . ' WHERE ' . $db->qn('product_id') . ' = ' . $db->q((int) $productId);
					$categoryData = $db->setQuery($sel)->loadObjectList();
					$where = ' ';

					if ($categoryData)
					{
						$where = 'AND ( ';

						for ($c = 0, $cn = count($categoryData); $c < $cn; $c++)
						{
							$where .= " FIND_IN_SET(" . $db->q((int) $categoryData[$c]->category_id) . ", " . $db->qn('shipping_rate_on_category') . ") ";

							if ($c != count($categoryData) - 1)
							{
								$where .= " OR ";
							}
						}

						$where .= ")";
						$newCwhere = str_replace("AND (", "OR (", $where);
						$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
									 LEFT JOIN " . $db->qn('#__extensions') . " AS s
									 ON
									 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
			 	     				 WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND " . $db->qn('s.enabled') . " = 1 AND" . $whereCountry . $whereShopper . $isWhere . "
									 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND " . $db->qn('shipping_rate_volume_end') . " >= "
							. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
									 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND " . $db->qn('shipping_rate_ordertotal_end') . " >= "
							. $db->q($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
									 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND " . $db->qn('shipping_rate_weight_end') . " >= "
							. $db->q($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
							. $where . $whereState . "
									ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

						$shippingRate = $db->setQuery($sql)->loadObject();
					}
				}
			}

			if (!$shippingRate)
			{
				$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
								 LEFT JOIN " . $db->qn('#__extensions') . " AS s
								 ON
								 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
		 	     		WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND " . $db->qn('s.enabled') . " = 1 AND " . $whereCountry . $whereShopper . $isWhere . $whereState . "
						AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND " . $db->qn('shipping_rate_volume_end') . " >= "
					. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
						AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND " . $db->qn('shipping_rate_ordertotal_end') . " >= "
					. $db->q($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
						AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND " . $db->qn('shipping_rate_weight_end') . " >= "
					. $db->q($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))
						AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $newPwhere . ") AND (" . $db->qn('shipping_rate_on_category') . " = '' " . $newCwhere . " )
						ORDER BY " . $db->qn('s.ordering') . ", " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

				$shippingrate = $db->setQuery($sql)->loadObject();
			}

			$total = 0;
			$shippingVat = 0;

			if ($shippingRate)
			{
				$total = $shippingRate->shipping_rate_value;

				if ($shippingRate->apply_vat == 1)
				{
					$result = self::getShippingVatRates($shippingRate->shipping_tax_group_id, $data);
					$addVat = $productHelper->taxexempt_addtocart($userId);

					if (!empty($result) && $addVat)
					{
						if ($result->tax_rate > 0)
						{
							$shippingVat = $total * $result->tax_rate;
							$total        = $shippingVat + $total;
						}
					}
				}
			}

			$shipArr['shipping_rate'] = $total;
			$shipArr['shipping_vat']  = $shippingVat;

			return $shipArr;
		}
		else
		{
			return $shippingArr;
		}
	}
}
