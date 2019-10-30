<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Cart module helper
 *
 * @since  2.1.0
 */
class Module
{
	/**
	 * @var  Registry
	 */
	public static $params;

	/**
	 * Get cart module calculate
	 *
	 * @param   array $cart Cart data
	 *
	 * @return  float
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function calculate($cart = array())
	{
		$cart = empty($cart) ? \RedshopHelperCartSession::getCart() : $cart;

		$cartParams       = self::getParams();
		$showWithShipping = (boolean) $cartParams->get('show_with_shipping', true);
		$showWithDiscount = (boolean) $cartParams->get('show_with_discount', true);
		$showWithVat      = (boolean) $cartParams->get('show_with_vat', true);
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
	 * @return  Registry
	 *
	 * @since   2.1.0
	 */
	public static function getParams()
	{
		if (null === self::$params)
		{
			$db = \JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn('params'))
				->from($db->qn('#__modules'))
				->where($db->qn('module') . ' = ' . $db->quote('mod_redshop_cart'))
				->where($db->qn('published') . ' = 1');

			$params = $db->setQuery($query)->loadResult();

			self::$params = new Registry($params);
		}

		return self::$params;
	}
}
