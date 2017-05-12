<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * Class Redshop Helper Text
 *
 * @since  2.0.6
 */
class RedshopHelperTax
{
	/**
	 * Method for replace tags about VAT information
	 *
	 * @param   string  $templateData  Template data.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function replaceVatInformation($templateData)
	{
		if (strpos($templateData, "{vat_info}") !== false)
		{
			$isApplyVAT = productHelper::getInstance()->getApplyVatOrNot($templateData);

			if ($isApplyVAT)
			{
				$strVat = Redshop::getConfig()->get('WITH_VAT_TEXT_INFO');
			}
			else
			{
				$strVat = Redshop::getConfig()->get('WITHOUT_VAT_TEXT_INFO');
			}

			$templateData = str_replace("{vat_info}", $strVat, $templateData);
		}

		return $templateData;
	}

	/**
	 * Re-calculate the Voucher/Coupon value when the product is already discount
	 *
	 * @param   float  $value  Voucher/Coupon value
	 * @param   array  $cart   Cart array
	 *
	 * @return  float          Voucher/Coupon value
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculateAlreadyDiscount($value, $cart)
	{
		$idx = 0;

		if (isset($cart['idx']))
		{
			$idx = $cart['idx'];
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$product = productHelper::getInstance()->getProductNetPrice($cart[$i]['product_id']);

			// If the product is already discount
			if ($product['product_price_saving'] > 0)
			{
				$value = $value - ($product['product_price_saving'] * $cart[$i]['quantity']);
			}
		}

		return $value < 0 ? 0 : $value;
	}

	/**
	 * Calculate discount
	 *
	 * @param   string  $type   Type of discount ("voucher", "coupon")
	 * @param   array   $types  Data.
	 *
	 * @return  float           Voucher/Coupon discount value
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculateDiscount($type, $types)
	{
		if (empty($types))
		{
			return 0.0;
		}

		$value         = $type == 'voucher' ? 'voucher_value' : 'coupon_value';
		$discountValue = 0.0;

		foreach ($types as $type)
		{
			$discountValue += $type[$value];
		}

		return $discountValue;
	}
}
