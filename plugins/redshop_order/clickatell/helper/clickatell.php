<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop helper for ClickATell
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.6
 */
class RedshopHelperClickatell
{
	/**
	 * Method for run process on order ID
	 *
	 * @param   integer $orderId ID of order
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function clickatellSMS($orderId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_order_users_info', 'oui'))
			->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('oui.order_id'))
			->where($db->qn('oui.order_id') . ' = ' . (int) $orderId)
			->where($db->qn('oui.address_type') . ' = ' . $db->quote('ST'));

		$orderData = $db->setQuery($query)->loadObject();

		$query->clear()
			->select($db->qn('p.payment_method_name'))
			->select($db->qn('op.payment_method_id'))
			->from($db->qn('#__redshop_order_payment', 'op'))
			->leftJoin($db->qn('#__redshop_orders', 'o') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('op.order_id'))
			->leftJoin($db->qn('#__redshop_payment_method', 'p') . ' ON ' . $db->qn('p.payment_method_id') . ' = ' . $db->qn('op.payment_method_id'))
			->where($db->qn('op.order_id') . ' = ' . (int) $orderId);

		$paymentData = $db->setQuery($query)->loadObject();

		$paymentName     = $paymentData->payment_method_name;
		$paymentMethodId = $paymentData->payment_method_id;
		$to              = $orderData->phone;
		$templateDetail  = RedshopHelperTemplate::getTemplate("clicktell_sms_message");

		$orderShippingClass = 0;
		$orderShipping      = RedshopShippingRate::decrypt($orderData->ship_method_id);

		if (isset($orderShipping[0]))
		{
			$orderShippingClass = $orderShipping[0];
		}

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_template', 't'))
			->where($db->qn('t.template_section') . ' = ' . $db->quote('clicktell_sms_message'))
			->where('FIND_IN_SET(' . $db->quote($orderData->order_status) . ', order_status)')
			->where('FIND_IN_SET(' . $db->quote($paymentMethodId) . ', payment_methods)')
			->order($db->qn('template_id') . ' DESC');

		$paymentMethod = $db->setQuery($query, 0, 1)->loadObject();

		$message = self::replaceMessage($paymentMethod->template_desc, $orderData, $paymentName);

		if ($message)
		{
			self::sendMessage(urlencode($message), $to);
		}

		$query->clear()
			->select('*')
			->from($db->qn('#__redshop_template', 't'))
			->where($db->qn('t.template_section') . ' = ' . $db->quote('clicktell_sms_message'))
			->where('FIND_IN_SET(' . $db->quote($orderData->order_status) . ', order_status)')
			->where('FIND_IN_SET(' . $db->quote($orderShippingClass) . ', shipping_methods)')
			->order($db->qn('template_id') . ' DESC');

		$shippingMethod = $db->setQuery($query)->loadObject();

		$message = self::replaceMessage($shippingMethod->template_desc, $orderData, $paymentName);

		if ($message)
		{
			self::sendMessage(urlencode($message), $to);
		}

		if (Redshop::getConfig()->get('CLICKATELL_ORDER_STATUS') == $orderData->order_status)
		{
			$message = self::replaceMessage($templateDetail[0]->template_desc, $orderData, $paymentName);

			if ($message)
			{
				self::sendMessage(urlencode($message), $to);
			}
		}
	}

	/**
	 * Method for replace message
	 *
	 * @param   string $message     Message text
	 * @param   object $orderData   Object data
	 * @param   string $paymentName Name of payment
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function replaceMessage($message, $orderData, $paymentName)
	{
		$shippingMethod = '';
		$details        = RedshopShippingRate::decrypt($orderData->ship_method_id);

		if (count($details) > 1)
		{
			$text = "";

			if (array_key_exists(2, $details))
			{
				$text = " (" . $details[2] . ")";
			}

			$shippingMethod = $details[1] . $text;
		}

		$userData = RedshopHelperUser::getUserInformation($orderData->user_id);

		$message = str_replace('{order_id}', $orderData->order_id, $message);
		$message = str_replace('{order_status}', $orderData->order_status, $message);
		$message = str_replace('{customer_name}', $userData->firstname, $message);
		$message = str_replace('{payment_status}', $orderData->order_payment_status, $message);
		$message = str_replace('{order_comment}', $orderData->customer_note, $message);
		$message = str_replace('{shipping_method}', $shippingMethod, $message);
		$message = str_replace('{payment_method}', $paymentName, $message);

		return $message;
	}

	/**
	 * Method for send message
	 *
	 * @param   string $text Message text
	 * @param   string $to   Phone number for send
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendMessage($text, $to)
	{
		// ClickATell username
		$user = Redshop::getConfig()->get('CLICKATELL_USERNAME');

		// ClickATell password
		$password = Redshop::getConfig()->get('CLICKATELL_PASSWORD');

		// Clickatell_api_id
		$clickATellAPI = Redshop::getConfig()->get('CLICKATELL_API_ID');
		$baseUrl       = "http://api.clickatell.com";

		// Auth call
		$url = $baseUrl . '/http/auth?user=' . $user . '&password=' . $password . '&api_id=' . $clickATellAPI;

		// Do auth call
		$result = file($url);

		// Split our response. return string is on first line of the data returned
		$session = explode(":", $result[0]);

		if ($session[0] == "OK")
		{
			// Remove any whitespace
			$sessionId = trim($session[1]);
			$url       = $baseUrl . '/http/sendmsg?session_id=' . $sessionId . '&to=' . $to . '&text=' . $text;

			// Do send sms call
			$result = file($url);
			$send   = explode(":", $result[0]);

			if ($send[0] == "ID")
			{
				echo "success message ID: " . $send[1];
			}
			else
			{
				JError::raiseWarning(21, "send message failed: ");
			}
		}
		else
		{
			JError::raiseWarning(21, "Authentication failure: " . $result[0]);
		}
	}
}
