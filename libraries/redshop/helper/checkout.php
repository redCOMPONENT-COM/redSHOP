<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.7
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCheckout
{
	/**
	 * @param   array  $post  Post
	 *
	 *
	 * @since   2.0.7
	 */
	public static function newsLetter($post)
	{
		// If user subscribe for the newsletter
		if (isset($post['newsletter_signup']) && $post['newsletter_signup'] == 1)
		{
			RedshopHelperNewsletter::subscribe();
		}

		// If user unsubscribe for the newsletter

		if (isset($post['newsletter_signoff']) && $post['newsletter_signoff'] == 1)
		{
			RedshopHelperNewsletter::removeSubscribe();
		}
	}
	/**
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public static function getCcData()
	{
		$ccData = JFactory::getSession()->get('ccdata');

		if (!isset($ccData['creditcard_code']))
		{
			$ccData['creditcard_code'] = 0;
		}

		if (!isset($ccData['order_payment_number']))
		{
			$ccData['order_payment_number'] = 0;
		}

		if (!isset($ccData['order_payment_expire_month']))
		{
			$ccData['order_payment_expire_month'] = 0;
		}

		if (!isset($ccData['order_payment_expire_year']))
		{
			$ccData['order_payment_expire_year'] = 0;
		}

		return $ccData;
	}
	/**
	 * @param   Tableorder_detail  $order  Order detail JTable object
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function sendOrderMail($order)
	{
		// Send the Order mail before payment
		if (!Redshop::getConfig()->get('ORDER_MAIL_AFTER') || (Redshop::getConfig()->get('ORDER_MAIL_AFTER') && $order->order_payment_status == "Paid"))
		{
			return RedshopHelperMail::sendOrderMail($order->order_id);
		}

		if (Redshop::getConfig()->get('ORDER_MAIL_AFTER') == 1)
		{
			// If Order mail set to send after payment then send mail to administrator only.
			return RedshopHelperMail::sendOrderMail($order->order_id, true);
		}

		return false;
	}

	/**
	 * @param   Tableorder_detail  $order  Order detail JTable object
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function sendDownload($order)
	{
		if ($order->order_status != "C" || $order->order_payment_status != "Paid")
		{
			return false;
		}

		return RedshopHelperOrder::sendDownload($order->order_id);
	}
}
