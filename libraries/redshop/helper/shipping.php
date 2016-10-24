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
					$query = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

					$categoryData = $db->setQuery($query)->loadObjectList();
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

	/**
	 * Get Shipping rate for xmlexport
	 *
	 * @param   array  $data  Shipping data
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function getDefaultShippingXmlExport($data)
	{
		$productHelper = productHelper::getInstance();
		$userHelper    = rsUserHelper::getInstance();
		$session       = JFactory::getSession();
		$orderSubtotal = $data['order_subtotal'];
		$user          = JFactory::getUser();
		$userId        = $user->id;
		$db            = JFactory::getDbo();

		$data         = Redshop::product((int) $data['product_id']);
		$weightTotal  = $data->weight;
		$volume       = $data->product_volume;
		$userInfo     = self::getShippingAddress($data['users_info_id']);
		$productId    = $data['product_id'];
		$country      = '';
		$state        = '';
		$isCompany    = '';
		$newPwhere    = '';
		$newCwhere    = '';
		$whereState   = '';
		$whereShopper = '';

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
			$whereShopper = ' AND (FIND_IN_SET(' . $db->q((int) $shopperGroupId) . ', '
				. $db->qn('shipping_rate_on_shopper_group') . ') OR '
				. $db->qn('shipping_rate_on_shopper_group') . ' = "") ';
		}

		if ($country)
		{
			$whereCountry = '(FIND_IN_SET(' . $db->q($country) . ', '
				. $db->qn('shipping_rate_country') . ') OR '
				. $db->qn('shipping_rate_country') . ' = 0 OR '
				. $db->qn('shipping_rate_country') . ' = "")';
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
			$isWhere = " AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$isWhere = " AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}

		$shippingArr = self::getShopperGroupDefaultShipping();

		if (empty($shippingArr))
		{
			$cart         = $session->get('cart');
			$shippingRate = array();

			$pWhere    = 'AND ( FIND_IN_SET(' . $db->q((int) $productId) . ', ' . $db->qn('shipping_rate_on_product') . ') )';
			$newPwhere = str_replace("AND (", "OR (", $pWhere);

			$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr "
				. "LEFT JOIN " . $db->qn('#__extensions') . " AS s ON " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . " WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND "
				. $whereCountry . $isWhere . "
						 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND "
					. $db->qn('shipping_rate_volume_end') . " >= "
				. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
						 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND "
					. $db->qn('shipping_rate_ordertotal_end') . " >= "
				. $db->q($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
						 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND "
					. $db->qn('shipping_rate_weight_end') . " >= "
				. $db->q($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
				. $pWhere . $whereState . $whereShopper . "
						   ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

			$shippingRate = $db->setQuery($sql)->loadObject();

			if (!$shippingRate)
			{
				$query = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

				$categoryData = $db->setQuery($query)->loadObjectList();
				$where = ' ';

				if ($categoryData)
				{
					$where = 'AND ( ';

					for ($c = 0, $cn = count($categoryData); $c < $cn; $c++)
					{
						$where .= " FIND_IN_SET(" . $db->q((int) $categoryData [$c]->category_id) . ", " . $db->qn('shipping_rate_on_category') . ") ";

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
			 	     				 WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND "
						. $whereCountry . $whereShopper . $isWhere . "
									 AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND "
							. $db->qn('shipping_rate_volume_end') . " >= "
						. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
									 AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND "
							. $db->qn('shipping_rate_ordertotal_end') . " >= "
						. $db->q($orderSubtotal) . ") OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
									 AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND "
							. $db->qn('shipping_rate_weight_end') . " >= "
						. $db->q($weightTotal) . "  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))"
						. $where . $whereState . "
									ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

					$shippingRate = $db->setQuery($sql)->loadObject();
				}
			}

			if (!$shippingRate)
			{
				$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " AS sr
								 LEFT JOIN " . $db->qn('#__extensions') . " AS s
								 ON
								 " . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element') . "
		 	     		WHERE " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping') . " AND "
					. $whereCountry . $whereShopper . $isWhere . $whereState . "
						AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND "
						. $db->qn('shipping_rate_volume_end') . " >= "
					. $db->q($volume) . ") OR ("
						. $db->qn('shipping_rate_volume_end') . " = 0) )
						AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderSubtotal) . " AND "
						. $db->qn('shipping_rate_ordertotal_end') . " >= "
					. $db->q($orderSubtotal) . ")  OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
						AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND "
						. $db->qn('shipping_rate_weight_end') . " >= "
					. $db->q($weightTotal) . ")  OR (" . $db->qn('shipping_rate_weight_end') . " = 0))
						AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $newPwhere . ") AND ("
						. $db->qn('shipping_rate_on_category') . " = '' " . $newCwhere . ")
						ORDER BY " . $db->qn('sr.shipping_rate_priority') . " LIMIT 0,1";

				$shippingRate = $db->setQuery($sql)->loadObject();
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

	/**
	 * Return only one shipping rate on cart page...
	 * this function is called by ajax
	 *
	 * @return  string
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingRateCalc()
	{
		$input         = JFactory::getApplication()->input;
		$session       = JFactory::getSession();
		$db            = JFactory::getDbo();
		$productHelper = productHelper::getInstance();
		$country       = $input->getString('country_code');
		$state         = $input->getString('state_code');
		$zip           = $input->getString('zip_code');
		$cart          = $session->get('cart');
		$idx           = (int) ($cart['idx']);
		$orderTotal    = 0;
		$rate          = 0;
		$pWhere        = "";
		$cWhere        = "";

		for ($i = 0; $i < $idx; $i++)
		{
			$orderTotal += ($cart[$i]['product_price'] * $cart[$i]['quantity']);

			$productId = $cart[$i]['product_id'];
			$pWhere     .= 'FIND_IN_SET(' . $db->q((int) $productId) . ', ' . $db->qn('shipping_rate_on_product') . ')';

			if ($i != $idx - 1)
			{
				$pWhere .= " OR ";
			}

			$query = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

			$categoryData = $db->setQuery($query)->loadObjectList();

			if ($categoryData)
			{
				$cWhere = ' ( ';

				for ($c = 0, $cn = count($categoryData); $c < $cn; $c++)
				{
					$cWhere .= " FIND_IN_SET(" . $db->q((int) $categoryData [$c]->category_id) . ", " . $db->qn('shipping_rate_on_category') . ") ";

					if ($c != count($categoryData) - 1)
					{
						$cWhere .= " OR ";
					}
				}

				$cWhere .= ")";
			}
		}

		if ($pWhere != "")
		{
			$pWhere = " OR (" . $pWhere . ")";
		}

		if ($cWhere != "")
		{
			$cWhere = " OR (" . $cWhere . ")";
		}

		$totalDimention = self::getCartItemDimention();
		$weightTotal    = $totalDimention['totalweight'];
		$volume         = $totalDimention['totalvolume'];

		// Product volume based shipping
		$volumeShipping = self::getProductVolumeShipping();

		$whereShippingVolume = "";

		for ($g = 0, $gn = count($volumeShipping); $g < $gn; $g++)
		{
			$length = $volumeShipping[$g]['length'];
			$width  = $volumeShipping[$g]['width'];

			if ($g == 0)
			{
				$whereShippingVolume .= "AND (";
			}

			$whereShippingVolume .= "((" . $db->qn('shipping_rate_length_start') . " <= " . $db->q($length) . " AND "
				. $db->qn('shipping_rate_length_end') . " >= "
				. $db->q($length) . " AND (" . $db->qn('shipping_rate_width_start') . " <= " . $db->q($width) . " AND "
				. $db->qn('shipping_rate_width_end') . " >= "
				. $db->q($width) . ") AND (" . $db->qn('shipping_rate_height_start') . " <= " . $db->q($length) . " AND "
				. $db->qn('shipping_rate_height_end') . " >= "
				. $db->q($length) . ")) ";

			if ($g != count($volumeShipping) - 1)
			{
				$whereShippingVolume .= " OR ";
			}

			if ($g == count($volumeShipping) - 1)
			{
				$whereShippingVolume .= ")";
			}
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");

		$zipCond = "";
		$zip = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = ' AND ( ( ' . $db->qn('shipping_rate_zip_start') . ' <= ' . $db->q($zip) . ' AND '
				. $db->qn('shipping_rate_zip_end') . ' >= ' . $db->q($zip) . ' )
			OR (' . $db->qn('shipping_rate_zip_start') . ' = 0 AND ' . $db->qn('shipping_rate_zip_end') . ' = 0)
			OR (' . $db->qn('shipping_rate_zip_start') . ' = "" AND ' . $db->qn('shipping_rate_zip_end') . ' = "") ) ';
		}

		$whereCountry = "";
		$whereState = '';

		if ($country)
		{
			$whereCountry = ' AND (FIND_IN_SET(' . $db->q($country) . ', '
				. $db->qn('shipping_rate_country') . ') OR ('
				. $db->qn('shipping_rate_country') . ' = 0 OR '
				. $db->qn('shipping_rate_country') . ' = "") )';
		}

		if ($state)
		{
			$whereState = ' AND (FIND_IN_SET(' . $db->q($state) . ', '
				. $db->qn('shipping_rate_state') . ') OR '
				. $db->qn('shipping_rate_state') . ' = 0 OR '
				. $db->qn('shipping_rate_state') . ' = "")';
		}

		$sql = "SELECT "
			. $db->qn('shipping_rate_value') . ", "
			. $db->qn('shipping_rate_zip_start') . ", "
			. $db->qn('shipping_rate_zip_end')
			. " FROM "
			. $db->qn('#__redshop_shipping_rate') . " AS sr
				LEFT JOIN " . $db->qn('#__extensions') . " AS s
				ON
				" . $db->qn('sr.shipping_class') . " = " . $db->qn('s.element')
			. " WHERE 1=1 AND " . $db->qn('s.folder') . " = " . $db->qn('redshop_shipping')
			. " AND " . $whereCountry . $whereState . $zipCond . "
				AND ((" . $db->qn('shipping_rate_volume_start') . " <= " . $db->q($volume) . " AND "
				. $db->qn('shipping_rate_volume_end') . " >= "
			. $db->q($volume) . ") OR (" . $db->qn('shipping_rate_volume_end') . " = 0) )
				AND ((" . $db->qn('shipping_rate_ordertotal_start') . " <= " . $db->q($orderTotal) . " AND "
				. $db->qn('shipping_rate_ordertotal_end') . " >= "
			. $db->q($orderTotal) . " OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
				AND ((" . $db->qn('shipping_rate_weight_start') . " <= " . $db->q($weightTotal) . " AND "
				. $db->qn('shipping_rate_weight_end') . " >= "
			. $db->q($weightTotal) . ") OR (" . $db->qn('shipping_rate_weight_end') . " = 0))" . $whereShippingVolume . "
				AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $pWhere . ") AND ("
				. $db->qn('shipping_rate_on_category') . " = '' " . $cWhere . " )
				ORDER BY " . $db->qn('shipping_rate_priority') . ", " . $db->qn('shipping_rate_value') . ", " . $db->qn('sr.shipping_rate_id');

		$shippingRate = $db->setQuery($sql)->loadObjectlist();

		/**
		 * rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k = 0;

			$userZipLen = (self::strposa($zip, $numbers) !== false) ? ($self::strposa($zip, $numbers)) : strlen($zip);

			for ($i = 0, $in = count($shippingRate); $i < $in; $i++)
			{
				$flag             = false;
				$tmpShippingRate = $shippingRate[$i];
				$start            = $tmpShippingRate->shipping_rate_zip_start;
				$end              = $tmpShippingRate->shipping_rate_zip_end;

				$startZipLen = (self::strposa($start, $numbers) !== false) ? (self::strposa($start, $numbers)) : strlen($start);
				$endZipLen = (self::strposa($end, $numbers) !== false) ? (self::strposa($end, $numbers)) : strlen($end);

				if ($startZipLen != $endZipLen || $userZipLen != $endZipLen)
				{
					continue;
				}

				$len = $userZipLen;

				for ($j = 0; $j < $len; $j++)
				{
					if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j])) && ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j])))
					{
						$flag = true;
					}

					else
					{
						$flag = false;
						break;
					}
				}

				if ($flag)
				{
					$shipping[$k++] = $tmpShippingRate;
				}
			}

			if (count($shipping) > 0)
			{
				$rate = $shipping[0]->shipping_rate_value;
			}

			else
			{
				if (count($shippingRate) > 0)
				{
					$rate = $shippingRate[0]->shipping_rate_value;
				}
			}
		}
		else
		{
			if (count($shippingRate) > 0)
			{
				$rate = $shippingRate[0]->shipping_rate_value;
			}
		}

		$total = $cart['total'] - $cart['shipping'] + $rate;
		$rate  = $productHelper->getProductFormattedPrice($rate, true);
		$total = $productHelper->getProductFormattedPrice($total, true);

		return $rate . "`" . $total;
	}

	/**
	 * Encrypt Shipping
	 *
	 * @param   string  $strMessage  String to encrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::encrypt(array);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @since   2.0.0.3
	 */
	public static function encryptShipping($strMessage)
	{
		$lenStrMessage       = strlen($strMessage);
		$strEncryptedMessage = "";

		for ($position = 0; $position < $lenStrMessage; $position++)
		{
			$keyToUse              = (($lenStrMessage + $position) + 1);
			$keyToUse              = (255 + $keyToUse) % 255;
			$byteToBeEncrypted     = substr($strMessage, $position, 1);
			$asciiNumByteToEncrypt = ord($byteToBeEncrypted);
			$xoredByte             = $asciiNumByteToEncrypt ^ $keyToUse;
			$encryptedByte         = chr($xoredByte);
			$strEncryptedMessage   .= $encryptedByte;
		}

		$result = base64_encode($strEncryptedMessage);
		$result = str_replace("+", " ", $result);

		return $result;
	}

	/**
	 * Decrypt Shipping
	 *
	 * @param   string  $strMessage  String to decrypt
	 *
	 * @deprecated 1.6  Use RedshopShippingRate::decrypt(string);
	 *
	 * @return  string  Encrypt shipping rate
	 *
	 * @since   2.0.0.3
	 */
	public static function decryptShipping($strMessage)
	{
		$strMessage          = base64_decode($strMessage);
		$lenStrMessage       = strlen($strMessage);
		$strEncryptedMessage = "";

		for ($position = 0; $position < $lenStrMessage; $position++)
		{
			$keyToUse              = (($lenStrMessage + $position) + 1);
			$keyToUse              = (255 + $keyToUse) % 255;
			$byteToBeEncrypted     = substr($strMessage, $position, 1);
			$asciiNumByteToEncrypt = ord($byteToBeEncrypted);

			// Xor operation
			$xoredByte             = $asciiNumByteToEncrypt ^ $keyToUse;
			$encryptedByte         = chr($xoredByte);
			$strEncryptedMessage   .= $encryptedByte;
		}

		return $strEncryptedMessage;
	}

	/**
	 * Get shipping address
	 *
	 * @param   int  $userInfoId  User info id
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingAddress($userInfoId)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_users_info'))
			->where($db->qn('users_info_id') . ' = ' . $db->q((int) $userInfoId));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shipping method class
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingMethodByClass($shippingClass = '')
	{
		$folder = strtolower('redshop_shipping');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__extensions'))
			->where('LOWER(' . $db->qn('folder') . ')' . ' = ' . $db->q($folder))
			->where($db->qn('element') . ' = ' . $db->q($shippingClass));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shipping method by id
	 *
	 * @param   int  $id  Shipping id
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingMethodById($id = 0)
	{
		$folder = strtolower('redshop_shipping');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->select($db->qn('extension_id'))
			->from($db->qn('#__extensions'))
			->where('LOWER(' . $db->qn('folder') . ')' . ' = ' . $db->q($folder))
			->where($db->qn('extension_id') . ' = ' . $db->q((int) $id));

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shipping rates
	 *
	 * @param   string  $shippingClass  Shipping class
	 *
	 * @return  object
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingRates($shippingClass)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_shipping_rate'))
			->where($db->qn('shipping_class') . ' = ' . $db->q($shippingClass));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Apply VAT on shipping rate
	 *
	 * @param   object  $shippingRate  Shipping Rate information
	 * @param   array   $data          Shipping Rate user information from cart or checkout selection.
	 *
	 * @return  object  Shipping Rate
	 *
	 * @since   2.0.0.3
	 */
	public static function applyVatOnShippingRate($shippingRate, $data)
	{
		if (!is_array($data))
		{
			throw new InvalidArgumentException(
				__FUNCTION__ . ' function only accepts array as 2nd argument. Input was: ' . getType($data)
			);
		}

		$productHelper   = productHelper::getInstance();
		$shippingRateVat = $shippingRate->shipping_rate_value;

		if ($shippingRate->apply_vat == 1)
		{
			$result = self::getShippingVatRates($shippingRate->shipping_tax_group_id, $data);
			$addVat = $productHelper->taxexempt_addtocart($data['user_id']);

			if (!empty($result) && $addVat)
			{
				if ($result->tax_rate > 0)
				{
					$shippingRateVat = ($shippingRateVat * $result->tax_rate) + $shippingRateVat;
				}
			}
		}

		return $shippingRateVat;
	}

	/**
	 * List shipping rates
	 *
	 * @param   object  $shippingClass  Shipping class
	 * @param   array   $usersInfoId    User info id
	 * @param   array   &$data          Shipping data
	 *
	 * @return  object  Shipping Rate
	 *
	 * @since   2.0.0.3
	 */
	public static function listShippingRates($shippingClass, $usersInfoId, &$data)
	{
		$app            = JFactory::getApplication();
		$isAdmin        = $app->isAdmin();
		$userHelper     = rsUserHelper::getInstance();
		$orderSubtotal  = $data['order_subtotal'];
		$totalDimention = self::getCartItemDimention();
		$weightTotal    = $totalDimention['totalweight'];
		$volume         = $totalDimention['totalvolume'];
		$session        = JFactory::getSession();
		$db             = JFactory::getDbo();
		$cart           = $session->get('cart');
		$idx            = (int) ($cart ['idx']);

		// Product volume based shipping
		$volumeShipping      = self::getProductVolumeShipping();
		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0, $gn = count($volumeShipping); $g < $gn; $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= "(
						((" . $db->q($length) . " BETWEEN " . $db->qn('shipping_rate_length_start')
						. " AND " . $db->qn('shipping_rate_length_end') . ")
							OR (" . $db->qn('shipping_rate_length_start') . " = 0 AND "
							. $db->qn('shipping_rate_length_end') . " = 0))
						AND ((" . $db->q($width) . " BETWEEN " . $db->qn('shipping_rate_width_start')
						. " AND " . $db->qn('shipping_rate_width_end') . ")
							OR (" . $db->qn('shipping_rate_width_start') . " = 0 AND "
							. $db->qn('shipping_rate_width_end') . " = 0))
						AND ((" . $db->q($height) . " BETWEEN " . $db->qn('shipping_rate_height_start')
						. " AND " . $db->qn('shipping_rate_height_end') . ")
							OR (" . $db->qn('shipping_rate_height_start') . " = 0 AND "
							. $db->qn('shipping_rate_height_end') . "= 0))
						) ";
			}

			$whereShippingVolume .= " ) ";
		}

		$userInfo     = self::getShippingAddress($usersInfoId);
		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$zip          = $userInfo->zipcode;
		$isCompany    = $userInfo->is_company;
		$where        = '';
		$whereState   = '';
		$whereShopper = '';

		if (!$isCompany)
		{
			$where = " AND ( " . $db->qn('company_only') . " = 2 OR " . $db->qn('company_only') . " = 0) ";
		}
		else
		{
			$where = " AND ( " . $db->qn('company_only') . " = 1 OR " . $db->qn('company_only') . " = 0) ";
		}

		$shopperGroup = $userHelper->getShoppergroupData($userInfo->user_id);

		if (count($shopperGroup) > 0)
		{
			$shopperGroupId = $shopperGroup->shopper_group_id;
			$whereShopper     = " AND (FIND_IN_SET(" . (int) $shopperGroupId . ", " . $db->qn('shipping_rate_on_shopper_group') . ")
				OR " . $db->qn('shipping_rate_on_shopper_group') . "= '') ";
		}

		$shippingRate = array();

		if ($country)
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->q($country) . ", " . $db->qn('shipping_rate_country') . ") OR "
				. $db->qn('shipping_rate_country') . " = 0 OR " . $db->qn('shipping_rate_country') . " = '' )";
		}
		else
		{
			$whereCountry = "AND (FIND_IN_SET(" . $db->q(Redshop::getConfig()->get('DEFAULT_SHIPPING_COUNTRY')) . ", "
				. $db->qn('shipping_rate_country') . ") )";
		}

		if ($state)
		{
			$whereState = " AND (FIND_IN_SET(" . $db->q($state) . ", " . $db->qn('shipping_rate_state') . ") OR "
				. $db->qn('shipping_rate_state') . " = 0 OR " . $db->qn('shipping_rate_state') . " = '')";
		}

		$pWhere = "";
		$cWhere = "";

		if ($idx)
		{
			$pWhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$productId = $cart[$i]['product_id'];
				$pWhere .= "FIND_IN_SET(" . $db->q((int) $productId) . ", " . $db->qn('shipping_rate_on_product') . ")";

				if ($i != $idx - 1)
				{
					$pWhere .= " OR ";
				}
			}

			$pWhere .= ")";
		}

		if (!$shippingRate)
		{
			for ($i = 0; $i < $idx; $i++)
			{
				$productId = $cart[$i]['product_id'];
				$query = $db->getQuery(true)
					->select($db->qn('category_id'))
					->from($db->qn('#__redshop_product_category_xref'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

				$categoryData = $db->setQuery($query)->loadObjectList();

				if ($categoryData)
				{
					for ($c = 0, $cn = count($categoryData); $c < $cn; $c++)
					{
						$acWhere[] = " FIND_IN_SET(" . $db->q((int) $categoryData[$c]->category_id) . ", " . $db->qn('shipping_rate_on_category') . ") ";
					}
				}
			}

			if (isset($acWhere) && count($acWhere) > 0)
			{
				$acWhere = implode(' OR ', $acWhere);
				$cWhere  = ' OR (' . $acWhere . ')';
			}
		}

		if (!$shippingRate)
		{
			$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z","A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", " ");

			$zipCond = "";
			$zip     = trim($zip);

			if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
			{
				$zipCond = " AND ( ( " . $db->qn('shipping_rate_zip_start') . " <= " . $db->q($zip) . " AND "
					. $db->qn('shipping_rate_zip_end') . " >= " . $db->q($zip) . " )
				OR (" . $db->qn('shipping_rate_zip_start') . " = 0 AND " . $db->qn('shipping_rate_zip_end') . " = 0)
				OR (" . $db->qn('shipping_rate_zip_start') . " = '' AND " . $db->qn('shipping_rate_zip_end') . " = '') ) ";
			}

			$sql = "SELECT * FROM " . $db->qn('#__redshop_shipping_rate') . " WHERE " . $db->qn('shipping_class') . " = " . $db->q($shippingClass) . $whereCountry . $whereState . $whereShopper . $zipCond . "
				AND (( " . $db->qn('$volume') . " BETWEEN " . $db->qn('shipping_rate_volume_start')
					. " AND " . $db->qn('shipping_rate_volume_end') . ") OR ( . " . $db->qn('shipping_rate_volume_end') . " = 0) )
				AND (( " . $db->qn('$orderSubtotal') . " BETWEEN " . $db->qn('shipping_rate_ordertotal_start')
					. " AND " . $db->qn('shipping_rate_ordertotal_end') . ") OR (" . $db->qn('shipping_rate_ordertotal_end') . " = 0))
				AND (( " . $db->qn('$weightTotal') . " BETWEEN " . $db->qn('shipping_rate_weight_start')
					. " AND " . $db->qn('shipping_rate_weight_end') . ") OR ("
						. $db->qn('shipping_rate_weight_end') . " = 0)) " . $whereShippingVolume . "
				AND (" . $db->qn('shipping_rate_on_product') . " = '' " . $pWhere . ") AND ("
					. $db->qn('shipping_rate_on_category') . " = '' " . $cWhere . ")" . $where . "
				ORDER BY " . $db->qn('shipping_rate_priority');

			$shippingRate = $db->setQuery($sql)->loadObjectList();
		}

		/*
		 * rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k = 0;
			$userZipLen = (self::strposa($zip, $numbers) !== false) ? (self::strposa($zip, $numbers)) : strlen($zip);

			for ($i = 0, $countShippingRate = count($shippingRate); $i < $countShippingRate; $i++)
			{
				$flag            = false;
				$tmpShippingRate = $shippingRate[$i];
				$start           = $tmpShippingRate->shipping_rate_zip_start;
				$end             = $tmpShippingRate->shipping_rate_zip_end;

				if (trim($start) == "" && trim($end) == "")
				{
					$shipping[$k++] = $tmpShippingRate;
				}

				else
				{
					$startZipLen = (self::strposa($start, $numbers) !== false) ? (self::strposa($start, $numbers)) : strlen($start);
					$endZipLen   = (self::strposa($end, $numbers) !== false) ? (self::strposa($end, $numbers)) : strlen($end);

					if ($startZipLen != $endZipLen || $userZipLen != $endZipLen)
					{
						continue;
					}

					$len = $userZipLen;

					for ($j = 0; $j < $len; $j++)
					{
						if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j]))
							&& ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j])))
						{
							$flag = true;
						}
						else
						{
							$flag = false;
							break;
						}
					}

					if ($flag)
					{
						$shipping[$k++] = $tmpShippingRate;
					}
				}
			}

			if ($isAdmin == false)
			{
				$shipping = self::filterRatesByPriority($shipping);
			}

			return $shipping;
		}
		else
		{
			if ($isAdmin == false)
			{
				$shippingRate = self::filterRatesByPriority($shippingRate);
			}

			return $shippingRate;
		}
	}

	/**
	 * Get shipping vat rates based on either billing or shipping user
	 *
	 * @param   int    $shippingTaxGroupId  Shipping Default Tax Gorup ID
	 * @param   array  $data                Shipping User Information array
	 *
	 * @return  object Shipping VAT rates
	 *
	 * @since   2.0.0.3
	 */
	public static function getShippingVatRates($shippingTaxGroupId, $data = array())
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		if (!empty($data) && ($data['user_id'] > 0 || $data['users_info_id'] > 0))
		{
			if ('BT' == Redshop::getConfig()->get('CALCULATE_VAT_ON'))
			{
				$userData = RedshopHelperUser::getUserInformation($data['user_id'], 'BT', 0, true, true);
			}
			else
			{
				$userData = RedshopHelperUser::getUserInformation(0, '', $data['users_info_id'], false);
			}

			if (count($userData) > 0)
			{
				if (!$userData->country_code)
				{
					$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
				}

				if (!$userData->state_code)
				{
					$userdata->state_code = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}

				/*
				 *  VAT_BASED_ON = 0 // webshop mode
				 *  VAT_BASED_ON = 1 // customer mode
				 *  VAT_BASED_ON = 2 // EU mode
				 */
				if (0 == Redshop::getConfig()->get('VAT_BASED_ON'))
				{
					$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
					$userData->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');
				}
			}

			if (Redshop::getConfig()->get('VAT_BASED_ON') == 2)
			{
				$query->where($db->qn('tr.is_eu_country') . " = 1");
			}
		}
		else
		{
			$session                = JFactory::getSession();
			$auth                   = $session->get('auth');
			$usersInfoId            = $auth['users_info_id'];
			$userData               = new stdClass;
			$userData->country_code = Redshop::getConfig()->get('DEFAULT_VAT_COUNTRY');
			$userData->state_code   = Redshop::getConfig()->get('DEFAULT_VAT_STATE');

			if ($usersInfoId && (Redshop::getConfig()->get('REGISTER_METHOD') == 1 || Redshop::getConfig()->get('REGISTER_METHOD') == 2) && (Redshop::getConfig()->get('VAT_BASED_ON') == 2 || Redshop::getConfig()->get('VAT_BASED_ON') == 1))
			{
				$query = $db->getQuery(true)
					->select($db->qn('country_code'))
					->select($db->qn('state_code'))
					->from($db->qn('#__redshop_users_info', 'u'))
					->leftJoin(
						$db->qn('#__redshop_shopper_group', 'sh')
						. ' ON ' . $db->qn('sh.shopper_group_id') . ' = ' . $db->qn('u.shopper_group_id')
					)
					->where($db->qn('u.users_info_id') . ' = ' . $db->q((int) $usersInfoId))
					->order($db->qn('u.users_info_id') . 'ASC')
					->setLimit(1);
				$userData = $db->setQuery($query)->loadObject();
			}
		}

		if ($shippingTaxGroupId == 0)
		{
			$query->where($db->qn('tr.tax_group_id') . ' = ' . $db->q((int) Redshop::getConfig()->get('DEFAULT_VAT_GROUP')));
		}
		elseif ($shipping_tax_group_id > 0)
		{
			$query->leftJoin(
				$db->qn('#__redshop_shipping_rate', 's')
					. ' ON ' . $db->qn('tr.tax_group_id') . ' = ' . $db->qn('s.shipping_tax_group_id')
				)
				->where($db->qn('s.shipping_tax_group_id') . ' = ' . $db->q((int) $shippingTaxGroupId));
		}
		else
		{
			$query->where($db->qn('tr.tax_group_id') . ' = ' . $db->q((int) Redshop::getConfig()->get('DEFAULT_VAT_GROUP')));
		}

		$query->select('tr.*')
			->from($db->qn('#__redshop_tax_rate', 'tr'))
			->where($db->qn('tr.tax_country') . ' = ' . $db->q($userData->country_code) . ' OR' . $db->qn('tr.tax_country') . ' = ""')
			->where($db->qn('tr.tax_rate') . ' = ' . $db->q($userData->state_code) . ' OR ' . $db->qn('tr.tax_rate') . ' = ""')
			->order($db->qn('tr.tax_rate') . 'DESC');

		return $db->setQuery($query)->loadObject();
	}

	/**
	 * Get shopper group default shipping
	 *
	 * @param   int  $userId  User id
	 *
	 * @return  arrays
	 *
	 * @since   2.0.0.3
	 */
	public static function getShopperGroupDefaultShipping($userId = 0)
	{
		$productHelper = productHelper::getInstance();
		$shippingArr   = array();
		$user          = JFactory::getUser();

		// FOR OFFLINE ORDER
		if ($userId == 0)
		{
			$userId = $user->id;
		}

		if ($userId)
		{
			$result = $productHelper->getUserInformation($userId);

			if (count($result) > 0 && $result->default_shipping == 1)
			{
				$shippingArr['shipping_rate'] = 0;
				$shippingArr['shipping_vat']  = 0;

				$row = self::getShippingVatRates(0);

				if (!empty($row))
				{
					$total       = 0;
					$shippingVat = 0;

					if ($row->tax_rate > 0)
					{
						$shippingVat = ($result->default_shipping_rate * $row->tax_rate) . "<br>";
						$total        += $shippingVat + $result->default_shipping_rate;
					}

					$shippingArr['shipping_vat']  = $shippingVat;
					$shippingArr['shipping_rate'] = $total;

					return $shippingArr;
				}

				$shippingArr['shipping_rate'] = $result->default_shipping_rate;

				return $shippingArr;
			}
		}

		return $shippingArr;
	}

	/**
	 * Find first number position
	 *
	 * @param   string  $haystack  string to find
	 * @param   array   $needles   array to find
	 * @param   int     $offset    position
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 */
	public static function strposa($haystack, $needles = array(), $offset = 0)
	{
		$chr = array();

		foreach ($needles as $needle)
		{
			if (strpos($haystack, $needle, $offset) !== false)
			{
				$chr[] = strpos($haystack, $needle, $offset);
			}
		}

		if (empty($chr))
		{
			return false;
		}
		else
		{
			return min($chr);
		}
	}

	/**
	 * Filter Shipping rates based on their priority
	 * Only show Higher priority rates (In [1,2,3,4] take 1 as a high priority)
	 * Rates with same priority will shown as radio button list in checkout
	 *
	 * @param   array  $shippingRates  Array shipping rates
	 *
	 * @return array
	 */
	public static function filterRatesByPriority($shippingRates)
	{
		$filteredRates = array();

		for ($i = 0, $j = 0, $ni = count($shippingRates); $i < $ni; $i++)
		{
			if ($shippingRates[0]->shipping_rate_priority == $shippingRates[$i]->shipping_rate_priority)
			{
				$filteredRates[$j] = $shippingRates[$i];
				$j++;
			}
		}

		return $filteredRates;
	}

	/**
	 * Function to get product volume shipping
	 *
	 * @return array $cases , 3cases of shipping
	 */
	public static function getProductVolumeShipping()
	{
		$productHelper = productHelper::getInstance();
		$session       = JFactory::getSession();
		$cart          = $session->get('cart');
		$idx           = (int) ($cart['idx']);

		$length  = array();
		$width   = array();
		$height  = array();
		$lengthQ = array();
		$widthQ  = array();
		$heightQ = array();
		$lMax    = 0;
		$lTotal  = 0;
		$wMax    = 0;
		$wTotal  = 0;
		$hMax    = 0;
		$hTotal  = 0;

		// Cart loop
		for ($i = 0; $i < $idx; $i++)
		{
			if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
			{
				continue;
			}

			$data       = Redshop::product((int) $cart[$i]['product_id']);
			$length[$i] = $data->product_length;
			$width[$i]  = $data->product_width;
			$height[$i] = $data->product_height;
			$tmpArr     = array($length[$i], $width[$i], $height[$i]);
			$switch     = array_search(min($tmpArr), $tmpArr);

			switch ($switch)
			{
				case 0:
					$lengthQ[$i] = $data->product_length * $cart[$i]['quantity'];
					$widthQ[$i]  = $data->product_width;
					$heightQ[$i] = $data->product_height;
				break;
				case 1:
					$lengthQ[$i] = $data->product_length;
					$widthQ[$i]  = $data->product_width * $cart[$i]['quantity'];
					$heightQ[$i] = $data->product_height;
				break;
				case 2:
					$lengthQ[$i] = $data->product_length;
					$widthQ[$i]  = $data->product_width;
					$heightQ[$i] = $data->product_height * $cart[$i]['quantity'];
				break;
			}
		}

		// Get maximum length
		if (count($length) > 0)
		{
			$lMax = max($length);
		}

		// Get total length
		if (count($lengthQ) > 0)
		{
			$lTotal = array_sum($lengthQ);
		}

		// Get maximum width
		if (count($width) > 0)
		{
			$wMax = max($width);
		}

		// Get total width
		if (count($widthQ) > 0)
		{
			$wTotal = array_sum($widthQ);
		}

		// Get maximum height
		if (count($height) > 0)
		{
			$hMax = max($height);
		}

		// Get total height
		if (count($heightQ) > 0)
		{
			$hTotal = array_sum($heightQ);
		}

		// 3 cases are available for shipping boxes
		$cases              = array();
		$cases[0]['length'] = $lMax;
		$cases[0]['width']  = $wMax;
		$cases[0]['height'] = $hTotal;

		$cases[1]['length'] = $lMax;
		$cases[1]['width']  = $wTotal;
		$cases[1]['height'] = $hMax;

		$cases[2]['length'] = $lTotal;
		$cases[2]['width']  = $wMax;
		$cases[2]['height'] = $hMax;

		return $cases;
	}
}
