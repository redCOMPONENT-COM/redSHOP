<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Redshop\Order\Template;

/**
 * Mail Order helper
 *
 * @since  2.1.0
 */
class Order
{
	/**
	 * Send order email.
	 *
	 * @param   integer  $orderId    Order ID.
	 * @param   boolean  $onlyAdmin  Send mail only to admin
	 *
	 * @return  boolean
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	public static function sendMail($orderId, $onlyAdmin = false)
	{
		$config = \JFactory::getConfig();

		if (!$config->get('mailonline') || !$orderId)
		{
			return false;
		}

		$mailSection = \Redshop::getConfig()->get('USE_AS_CATALOG') ? 'catalogue_order' : 'order';
		$mailInfo    = Helper::getTemplate(0, $mailSection);

		if (empty($mailInfo))
		{
			return false;
		}

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		$row = \RedshopEntityOrder::getInstance($orderId)->getItem();

		// It is necessary to take billing info from order user info table
		// Order mail output should reflect the checkout process"
		$message = str_replace("{order_mail_intro_text_title}", \JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
		$message = str_replace("{order_mail_intro_text}", \JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
		$message = Template::replaceTemplate($row, $message, true);

		$discounts    = array_filter(explode('@', $row->discount_type));
		$discountType = '';

		if (!empty($discounts))
		{
			foreach ($discounts as $discount)
			{
				$tmpDiscountType = explode(':', $discount);

				if ($tmpDiscountType[0] == 'c')
				{
					$discountType .= \JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $tmpDiscountType[1] . '<br>';
				}

				if ($tmpDiscountType[0] == 'v')
				{
					$discountType .= \JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $tmpDiscountType[1] . '<br>';
				}
			}
		}

		$discountType   = !$discountType ? \JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE') : $discountType;
		$orderDetailUrl = \JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $row->encr_key;

		$search  = array('{discount_type}', '{order_detail_link}');
		$replace = array($discountType, "<a href='" . $orderDetailUrl . "'>" . \JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>");

		$message = str_replace($search, $replace, $message);

		Helper::imgInMail($message);

		$billingAddresses = \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();
		$thirdPartyEmail  = $billingAddresses->thirdparty_email;
		$email            = $billingAddresses->user_email;
		$fullName         = $billingAddresses->firstname . ' ' . $billingAddresses->lastname;

		if ($billingAddresses->is_company == 1 && $billingAddresses->company_name != "")
		{
			$fullName = $billingAddresses->company_name;
		}

		$search[]  = "{order_id}";
		$replace[] = $row->order_id;
		$search[]  = "{order_number}";
		$replace[] = $row->order_number;

		$searchSub  = array("{order_id}", '{order_number}', '{shopname}', '{order_date}');
		$replaceSub = array(
			$row->order_id,
			$row->order_number,
			\Redshop::getConfig()->get('SHOP_NAME'),
			\RedshopHelperDatetime::convertDateFormat($row->cdate)
		);

		$subject = str_replace($searchSub, $replaceSub, $subject);

		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$subject = str_replace("{fullname}", $fullName, $subject);
		$subject = str_replace("{firstname}", $billingAddresses->firstname, $subject);
		$subject = str_replace("{lastname}", $billingAddresses->lastname, $subject);

		$message = str_replace("{fullname}", $fullName, $message);
		$message = str_replace("{firstname}", $billingAddresses->firstname, $message);
		$message = str_replace("{lastname}", $billingAddresses->lastname, $message);
		$body    = $message;

		// Send the e-mail
		if (!empty($email))
		{
			$mailBcc = array();

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}

			$bcc = (trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '') ?
				explode(",", trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'))) : array();
			$bcc = array_merge($bcc, $mailBcc);

			// As only need to send email to administrator,
			// Here variables are changed to use bcc email - from redSHOP configuration - Administrator Email
			if ($onlyAdmin)
			{
				$email           = $bcc;
				$thirdPartyEmail = '';
				$bcc             = null;
			}

			if (!empty($thirdPartyEmail)
				&& !Helper::sendEmail($from, $fromName, $thirdPartyEmail, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}

			if (!Helper::sendEmail($from, $fromName, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		// As email only need to send admin no need to send email to others.
		if ($onlyAdmin)
		{
			return true;
		}

		// Send invoice mail if necessary.
		if (\Redshop::getConfig()->get('INVOICE_MAIL_ENABLE') && $row->order_payment_status == 'Paid')
		{
			Invoice::sendMail($orderId);
		}

		$useManufacturerEmail = \Redshop::getConfig()->getBool('MANUFACTURER_MAIL_ENABLE');
		$useSupplierEmail     = \Redshop::getConfig()->getBool('SUPPLIER_MAIL_ENABLE');

		// If not enable manufacturer and supplier email. Skip that.
		if (!$useManufacturerEmail && !$useSupplierEmail)
		{
			return true;
		}

		$orderItems = \RedshopHelperOrder::getOrderItemDetail($orderId);

		if (empty($orderItems))
		{
			return true;
		}

		foreach ($orderItems as $orderItem)
		{
			// Skip send email if this item is giftcard.
			if ($orderItem->is_giftcard == '1')
			{
				continue;
			}

			$product = \Redshop::product((int) $orderItem->product_id);

			if ($useManufacturerEmail)
			{
				$manufacturer = \RedshopEntityManufacturer::getInstance($product->manufacturer_id)->getItem();

				if (!empty($manufacturer)
					&& !empty($manufacturer->manufacturer_email)
					&& !Helper::sendEmail(
						$from, $fromName, $manufacturer->manufacturer_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}

			if ($useSupplierEmail)
			{
				$supplier = \RedshopEntitySupplier::getInstance($product->supplier_id)->getItem();

				if (!empty($supplier)
					&& !empty($supplier->supplier_email)
					&& !Helper::sendEmail(
						$from, $fromName, $supplier->supplier_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	/**
	 * send Order Special Discount Mail function.
	 *
	 * @param   integer  $orderId  Order ID.
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendSpecialDiscountMail($orderId)
	{
		$mailSection = 'order_special_discount';

		$mailInfo = Helper::getTemplate(0, $mailSection);

		// Check if there are no template for Order Special Discount or this feature has been disable in config. Skip this.
		if (empty($mailInfo) || \Redshop::getConfig()->get('SPECIAL_DISCOUNT_MAIL_SEND') != '1')
		{
			return false;
		}

		$config        = \JFactory::getConfig();
		$mailBcc       = array();

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		if (trim($mailInfo[0]->mail_bcc) != '')
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$order            = \RedshopEntityOrder::getInstance($orderId);
		$billingAddresses = $order->getBilling()->getItem();
		$orderPayment     = $order->getPayment()->getItem();
		$order            = $order->getItem();
		$paymentMethod    = \RedshopHelperOrder::getPaymentMethodInfo($orderPayment->payment_method_class);
		$paymentMethod    = $paymentMethod[0];
		$message          = Template::replaceTemplate($order, $message, true);

		// Set order payment method name
		$search = array('{shopname}', '{payment_lbl}', '{payment_method}', '{special_discount}', '{special_discount_amount}',
			'{special_discount_lbl}', '{order_detail_link}'
		);

		$orderDetailUrl = \JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $order->encr_key;

		$replace = array(
			\Redshop::getConfig()->get('SHOP_NAME'),
			\JText::_('COM_REDSHOP_PAYMENT_METHOD'),
			'',
			$order->special_discount . '%',
			\RedshopHelperProductPrice::formattedPrice($order->special_discount_amount),
			\JText::_('COM_REDSHOP_SPECIAL_DISCOUNT'),
			"<a href='" . $orderDetailUrl . "'>" . \JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>"
		);

		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		if (\RedshopHelperPayment::isPaymentType($paymentMethod->element) === true)
		{
			$paymentParams = new Registry($paymentMethod->params);
			$txtExtraInfo  = $paymentParams->get('txtextra_info', '');
			$search[]      = "{payment_extrainfo}";
			$replace[]     = $txtExtraInfo;
		}

		$message = str_replace($search, $replace, $message);

		Helper::imgInMail($message);

		$email    = $billingAddresses->user_email;
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');
		$body     = $message;
		$subject  = str_replace($search, $replace, $subject);

		if ($email != "")
		{
			$bcc = null;

			if (trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);

			if (!Helper::sendEmail($from, $fromName, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
			}
		}

		if (\Redshop::getConfig()->get('MANUFACTURER_MAIL_ENABLE'))
		{
			$orderItems = \RedshopHelperOrder::getOrderItemDetail($orderId);

			if (empty($orderItems))
			{
				return true;
			}

			foreach ($orderItems as $orderItem)
			{
				// Skip send email if this item is giftcard.
				if ($orderItem->is_giftcard == '1')
				{
					continue;
				}

				$product      = \Redshop::product((int) $orderItem->product_id);
				$manufacturer = \RedshopEntityManufacturer::getInstance($product->manufacturer_id)->getItem();

				if (!empty($manufacturer)
					&& !empty($manufacturer->manufacturer_email)
					&& !Helper::sendEmail(
						$from, $fromName, $manufacturer->manufacturer_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}
}
