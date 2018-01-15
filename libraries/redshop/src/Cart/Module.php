<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

defined('_JEXEC') or die;

/**
 * Cart module helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Module
{
	/**
	 * Get cart module calculate
	 *
	 * @param   array $cart Cart data
	 *
	 * @return  float
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calculate($cart = array())
	{
		$cart = empty($cart) ? \RedshopHelperCartSession::getCart() : $cart;

		$cartParams       = self::getParams();
		$showWithShipping = array_key_exists('show_with_shipping', $cartParams) ? (boolean) $cartParams['show_with_shipping'] : true;
		$showWithDiscount = array_key_exists('show_with_discount', $cartParams) ? (boolean) $cartParams['show_with_discount'] : true;
		$showWithVat      = array_key_exists('show_with_vat', $cartParams) ? (boolean) $cartParams['show_with_vat'] : true;
		$total            = !$showWithVat ? $cart['product_subtotal_excl_vat'] : $cart['product_subtotal'];
		$shipping         = $cart['shipping'];
		$discountTotal    = $cart['coupon_discount'] + $cart['voucher_discount'] + $cart['cart_discount'];
		$modCartTotal     = $total;

		if ($showWithShipping && $showWithDiscount)
		{
			$modCartTotal = $total + $shipping - $discountTotal;
		}
		elseif (!$showWithShipping && $showWithDiscount)
		{
			$modCartTotal = $total - $discountTotal;
		}
		elseif ($showWithShipping && !$showWithDiscount)
		{
			$modCartTotal = $total + $shipping;
		}

		$layout = \JFactory::getApplication()->input->getCmd('layout');
		$view   = \JFactory::getApplication()->input->getCmd('view');

		if (!array_key_exists('payment_amount', $cart) || $view != 'checkout' || $layout == 'default')
		{
			return $modCartTotal;
		}

		$modCartTotal = $cart['payment_oprand'] == '+' ? $modCartTotal + $cart['payment_amount'] : $modCartTotal - $cart['payment_amount'];

		return $modCartTotal;
	}

	/**
	 * Method for get parameters of module cart
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getParams()
	{
		$db = \JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('params'))
			->from($db->qn('#__modules'))
			->where($db->qn('module') . ' = ' . $db->quote('mod_redshop_cart'))
			->where($db->qn('published') . ' = 1');

		$params = $db->setQuery($query)->loadResult();

		if (empty($params))
		{
			return array();
		}

		$params = substr($params, 1);
		$params = substr_replace($params, " ", -1);
		$params = str_replace('"', ' ', $params);
		$params = explode(",", $params);

		$cartParams = array();

		foreach ($params as $param)
		{
			$param = explode(':', $param);

			if (empty($param))
			{
				continue;
			}

			if (strpos($param[0], 'cart_output') !== false
				|| strpos($param[0], 'show_with_shipping') !== false
				|| strpos($param[0], 'show_with_discount') !== false
				|| strpos($param[0], 'show_with_vat') !== false
				|| strpos($param[0], 'show_shipping_line') !== false)
			{
				$cartParams[trim($param[0])] = trim($param[1]);
			}
		}

		return $cartParams;
	}
}
