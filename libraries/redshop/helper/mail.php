<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class Redshop Helper for Mail
 *
 * @since  2.0.0.3
 */
class RedshopHelperMail
{
	/**
	 * @var  array
	 */
	protected static $mailTemplates = array();

	/**
	 * Method to get mail section
	 *
	 * @param   int    $templateId Template id
	 * @param   string $section    Template section
	 * @param   string $extraCond  Extra condition for query
	 *
	 * @return  array
	 */
	public static function getMailTemplate($templateId = 0, $section = '', $extraCond = '')
	{
		JFactory::getLanguage()->load('com_redshop', JPATH_SITE);

		$key = $templateId . '_' . $section . '_' . serialize($extraCond);

		if (!array_key_exists($key, self::$mailTemplates))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_mail'))
				->where($db->qn('published') . ' = 1');

			if ($templateId)
			{
				$query->where($db->qn('mail_id') . ' = ' . (int) $templateId);
			}

			if ($section)
			{
				$query->where($db->qn('mail_section') . ' = ' . $db->quote($section));
			}

			if ($extraCond)
			{
				$query->where($extraCond);
			}

			self::$mailTemplates[$key] = $db->setQuery($query)->loadObjectList();
		}

		return self::$mailTemplates[$key];
	}

	/**
	 * sendOrderMail function.
	 *
	 * @param   int     $orderId   Order ID.
	 * @param   boolean $onlyAdmin send mail only to admin
	 *
	 * @return  boolean
	 */
	public static function sendOrderMail($orderId, $onlyAdmin = false)
	{
		$config = JFactory::getConfig();

		if (!$config->get('mailonline'))
		{
			return false;
		}

		$mailSection = "order";

		if (Redshop::getConfig()->get('USE_AS_CATALOG'))
		{
			$mailSection = "catalogue_order";
		}

		$mailInfo = self::getMailTemplate(0, $mailSection);

		if (empty($mailInfo))
		{
			return false;
		}

		$cartHelper = rsCarthelper::getInstance();

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		$row = RedshopHelperOrder::getOrderDetails($orderId);

		// It is necessory to take billing info from order user info table
		// Order mail output should reflect the checkout process"
		$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
		$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
		$message = $cartHelper->replaceOrderTemplate($row, $message, true);

		$discounts    = array_filter(explode('@', $row->discount_type));
		$discountType = '';

		if (!empty($discounts))
		{
			foreach ($discounts as $discount)
			{
				$tmpDiscountType = explode(':', $discount);

				if ($tmpDiscountType[0] == 'c')
				{
					$discountType .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $tmpDiscountType[1] . '<br>';
				}

				if ($tmpDiscountType[0] == 'v')
				{
					$discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $tmpDiscountType[1] . '<br>';
				}
			}
		}

		if (!$discountType)
		{
			$discountType = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		$search[]  = "{discount_type}";
		$replace[] = $discountType;

		$orderDetailUrl = JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $row->encr_key;
		$search[]       = "{order_detail_link}";
		$replace[]      = "<a href='" . $orderDetailUrl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$message          = str_replace($search, $replace, $message);
		$message          = self::imgInMail($message);
		$thirdPartyEmail  = $billingAddresses->thirdparty_email;
		$email            = $billingAddresses->user_email;
		$fullName         = $billingAddresses->firstname . ' ' . $billingAddresses->lastname;

		if ($billingAddresses->is_company == 1 && $billingAddresses->company_name != "")
		{
			$fullName = $billingAddresses->company_name;
		}

		$search[]     = "{order_id}";
		$replace[]    = $row->order_id;
		$search[]     = "{order_number}";
		$replace[]    = $row->order_number;
		$searchSub[]  = "{order_id}";
		$replaceSub[] = $row->order_id;
		$searchSub[]  = "{order_number}";
		$replaceSub[] = $row->order_number;
		$searchSub[]  = "{shopname}";
		$replaceSub[] = Redshop::getConfig()->get('SHOP_NAME');
		$searchSub[]  = "{order_date}";
		$replaceSub[] = Redconfiguration::getInstance()->convertDateFormat($row->cdate);
		$subject      = str_replace($searchSub, $replaceSub, $subject);

		$from     = JFactory::getConfig()->get('mailfrom');
		$fromName = JFactory::getConfig()->get('fromname');

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

			$bcc = (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '') ?
				explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'))) : array();
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
				&& !self::sendEmail($from, $fromName, $thirdPartyEmail, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}

			if (!self::sendEmail($from, $fromName, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		// As email only need to send admin no need to send email to others.
		if ($onlyAdmin)
		{
			return true;
		}

		// Send invoice mail if necessary.
		if (Redshop::getConfig()->get('INVOICE_MAIL_ENABLE') && $row->order_payment_status == 'Paid')
		{
			self::sendInvoiceMail($orderId);
		}

		$useManufacturerEmail = (boolean) Redshop::getConfig()->get('MANUFACTURER_MAIL_ENABLE');
		$useSupplierEmail     = (boolean) Redshop::getConfig()->get('SUPPLIER_MAIL_ENABLE');

		// If not enable manufacturer and supplier email. Skip that.
		if (!$useManufacturerEmail && !$useSupplierEmail)
		{
			return true;
		}

		$orderItems    = RedshopHelperOrder::getOrderItemDetail($orderId);
		$productHelper = productHelper::getInstance();

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

			$product = Redshop::product((int) $orderItem->product_id);

			if ($useManufacturerEmail)
			{
				$manufacturer = $productHelper->getSection("manufacturer", $product->manufacturer_id);

				if (!empty($manufacturer)
					&& !empty($manufacturer->manufacturer_email)
					&& !self::sendEmail(
						$from, $fromName, $manufacturer->manufacturer_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}

			if ($useSupplierEmail)
			{
				$supplier = $productHelper->getSection("supplier", $product->supplier_id);

				if (!empty($supplier)
					&& !empty($supplier->supplier_email)
					&& !self::sendEmail(
						$from, $fromName, $supplier->supplier_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	/**
	 * send Order Special Discount Mail function.
	 *
	 * @param   int $orderId Order ID.
	 *
	 * @return  boolean
	 */
	public static function sendOrderSpecialDiscountMail($orderId)
	{
		$mailSection = 'order_special_discount';

		$mailInfo = self::getMailTemplate(0, $mailSection);

		// Check if there are no template for Order Special Discount or this feature has been disable in config. Skip this.
		if (empty($mailInfo) || Redshop::getConfig()->get('SPECIAL_DISCOUNT_MAIL_SEND') != '1')
		{
			return false;
		}

		$cartHelper    = rsCarthelper::getInstance();
		$productHelper = productHelper::getInstance();
		$config        = JFactory::getConfig();
		$mailBcc       = array();

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		if (trim($mailInfo[0]->mail_bcc) != '')
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$order            = RedshopHelperOrder::getOrderDetails($orderId);
		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$orderPayment     = RedshopHelperOrder::getPaymentInfo($orderId);
		$paymentMethod    = RedshopHelperOrder::getPaymentMethodInfo($orderPayment->payment_method_class);
		$paymentMethod    = $paymentMethod[0];
		$message          = $cartHelper->replaceOrderTemplate($order, $message, true);

		// Set order paymethod name
		$search[]       = "{shopname}";
		$replace[]      = Redshop::getConfig()->get('SHOP_NAME');
		$search[]       = "{payment_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		$search[]       = "{payment_method}";
		$replace[]      = "";
		$search[]       = "{special_discount}";
		$replace[]      = $order->special_discount . '%';
		$search[]       = "{special_discount_amount}";
		$replace[]      = $productHelper->getProductFormattedPrice($order->special_discount_amount);
		$search[]       = "{special_discount_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');
		$orderDetailUrl = JUri::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $orderId . '&encr=' . $order->encr_key;
		$search[]       = "{order_detail_link}";
		$replace[]      = "<a href='" . $orderDetailUrl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		if (RedshopHelperPayment::isPaymentType($paymentMethod->element) === true)
		{
			$paymentParams = new Registry($paymentMethod->params);
			$txtExtraInfo  = $paymentParams->get('txtextra_info', '');
			$search[]      = "{payment_extrainfo}";
			$replace[]     = $txtExtraInfo;
		}

		$message  = str_replace($search, $replace, $message);
		$message  = self::imgInMail($message);
		$email    = $billingAddresses->user_email;
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');
		$body     = $message;
		$subject  = str_replace($search, $replace, $subject);

		if ($email != "")
		{
			$bcc = null;

			if (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);

			if (!self::sendEmail($from, $fromName, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
			}
		}

		if (Redshop::getConfig()->get('MANUFACTURER_MAIL_ENABLE'))
		{
			$orderItems    = RedshopHelperOrder::getOrderItemDetail($orderId);
			$productHelper = productHelper::getInstance();

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

				$product      = Redshop::product((int) $orderItem->product_id);
				$manufacturer = $productHelper->getSection("manufacturer", $product->manufacturer_id);

				if (!empty($manufacturer)
					&& !empty($manufacturer->manufacturer_email)
					&& !self::sendEmail(
						$from, $fromName, $manufacturer->manufacturer_email, $subject, $body, true, null, null, null, $mailSection, func_get_args()
					))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	/**
	 * Create multiple print invoice PDF
	 *
	 * @param   array $orderIds Order ID List.
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use
	 */
	public static function createMultiprintInvoicePdf($orderIds)
	{
		return RedshopHelperPdf::createMultiInvoice($orderIds);
	}

	/**
	 * Replace invoice mail template tags and prepare mail body and pdf html
	 *
	 * @param   integer $orderId Order Information ID
	 * @param   string  $html    HTML template of mail body or pdf
	 * @param   string  $subject Email Subject template, can be null for PDF
	 *
	 * @return  object  Object having mail body and subject. subject can be null for PDF type.
	 */
	public static function replaceInvoiceMailTemplate($orderId, $html, $subject = null)
	{
		$cartHelper   = rsCarthelper::getInstance();
		$redConfig    = Redconfiguration::getInstance();
		$row          = RedshopHelperOrder::getOrderDetails($orderId);
		$discounts    = array_filter(explode('@', $row->discount_type));
		$discountType = '';

		foreach ($discounts as $discount)
		{
			$discountTypes = explode(':', $discount);

			if ($discountTypes[0] == 'c')
			{
				$discountType .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}

			if ($discountTypes[0] == 'v')
			{
				$discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}
		}

		if (empty($discountType))
		{
			$discountType = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		// Prepare subject replacement
		$searchSub[]  = "{order_id}";
		$replaceSub[] = $row->order_id;
		$searchSub[]  = "{order_number}";
		$replaceSub[] = $row->order_number;
		$searchSub[]  = "{invoice_number}";
		$replaceSub[] = $row->invoice_number;
		$searchSub[]  = "{shopname}";
		$replaceSub[] = Redshop::getConfig()->get('SHOP_NAME');

		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$userFullName     = $billingAddresses->firstname . " " . $billingAddresses->lastname;
		$searchSub[]      = "{fullname}";
		$replaceSub[]     = $userFullName;
		$searchSub[]      = "{order_date}";
		$replaceSub[]     = $redConfig->convertDateFormat($row->cdate);
		$subject          = str_replace($searchSub, $replaceSub, $subject);

		// Prepare mail body
		$search[]  = "{discount_type}";
		$replace[] = $discountType;
		$search[]  = "{invoice_number}";
		$replace[] = $row->invoice_number;

		$html = str_replace($search, $replace, $html);
		$html = self::imgInMail($html);
		$html = $cartHelper->replaceOrderTemplate($row, $html, true);
		$html = str_replace("{firstname}", $billingAddresses->firstname, $html);
		$html = str_replace("{lastname}", $billingAddresses->lastname, $html);
		$html = $cartHelper->replaceOrderTemplate($row, $html, true);

		$object          = new stdClass;
		$object->subject = $subject;
		$object->body    = $html;

		return $object;
	}

	/**
	 * Send Order Invoice Mail
	 * Email Body and Subject is from "Invoice Mail" template section.
	 * Contains PDF attachement. PDF html is from "Invoice Mail PDF" section.
	 *
	 * @param   int     $orderId  Order Information Id
	 * @param   string  $email    Email
	 *
	 * @return  boolean  True on sending email successfully.
	 */
	public static function sendInvoiceMail($orderId, $email = null)
	{
		$config = JFactory::getConfig();

		if (!$config->get('mailonline'))
		{
			return false;
		}

		$mailSection = "invoice_mail";
		$mailBcc     = null;
		$mailInfo    = self::getMailTemplate(0, $mailSection);

		if (empty($mailInfo))
		{
			return false;
		}

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		if (trim($mailInfo[0]->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$mailTemplate    = self::replaceInvoiceMailTemplate($orderId, $message, $subject);
		$mailBody        = $mailTemplate->body;
		$subject         = $mailTemplate->subject;
		$pdfTemplateFile = self::getMailTemplate(0, 'invoicefile_mail');

		// Init PDF template body
		$pdfTemplate = $mailBody;

		// Set actual PDF template if found
		if (count($pdfTemplateFile) > 0)
		{
			$pdfTemplate = self::replaceInvoiceMailTemplate($orderId, $pdfTemplateFile[0]->mail_body)->body;
		}

		ob_clean();

		$invoiceAttachment = null;

		if (RedshopHelperPdf::isAvailablePdfPlugins())
		{
			JPluginHelper::importPlugin('redshop_pdf');
			$result = RedshopHelperUtility::getDispatcher()->trigger('onRedshopOrderCreateInvoicePdf', array($orderId, $pdfTemplate, 'F', true));

			if (!in_array(false, $result, true))
			{
				$invoiceAttachment = JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $orderId . '/' . $result[0] . ".pdf";
			}
		}

		// Set the e-mail parameters
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);

		if (empty($email))
		{
			$email = $billingAddresses->user_email;
		}

		$mailBody         = self::imgInMail($mailBody);

		if ((Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 2
				|| Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& $email != ""
		)
		{
			if (!self::sendEmail(
				$from, $fromName, $email, $subject, $mailBody, true, null, $mailBcc, $invoiceAttachment, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		if ((Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 1 || Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != ''
		)
		{
			$sendTo = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));

			if (!self::sendEmail(
				$from, $fromName, $sendTo, $subject, $mailBody, true, null, $mailBcc, $invoiceAttachment, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		return true;
	}

	/**
	 * Send registration mail
	 *
	 * @param   array &$data registration data
	 *
	 * @return  boolean
	 */
	public static function sendRegistrationMail(&$data)
	{
		$mailSection  = "register";
		$mailTemplate = self::getMailTemplate(0, $mailSection);

		if (empty($mailTemplate))
		{
			return false;
		}

		$app = JFactory::getApplication();

		$mailTemplate = $mailTemplate[0];
		$mainPassword = $app->input->post->getString('password1');
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');

		// Time for the email magic so get ready to sprinkle the magic dust...
		$mailBcc = array();

		$mailData    = $mailTemplate->mail_body;
		$mailSubject = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$search   = array();
		$replace  = array();
		$search[] = "{shopname}";
		$search[] = "{firstname}";
		$search[] = "{lastname}";
		$search[] = "{fullname}";
		$search[] = "{name}";
		$search[] = "{username}";
		$search[] = "{password}";
		$search[] = "{email}";
		$search[] = '{account_link}';

		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $data['firstname'];
		$replace[] = $data['lastname'];
		$replace[] = $data['firstname'] . " " . $data['lastname'];
		$replace[] = $data['name'];
		$replace[] = $data['username'];
		$replace[] = $mainPassword;
		$replace[] = $data['email'];
		$replace[] = '<a href="' . JUri::root() . 'index.php?option=com_redshop&view=account'
			. '" target="_blank">' . JText::_('COM_REDSHOP_ACCOUNT_LINK') . '</a>';

		$mailBody    = str_replace($search, $replace, $mailData);
		$mailBody    = self::imgInMail($mailBody);
		$mailSubject = str_replace($search, $replace, $mailSubject);

		$bcc = array();

		if ($mailBody && $data['email'] != "")
		{
			if (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);
			self::sendEmail($mailFrom, $fromName, $data['email'], $mailSubject, $mailBody, true, null, $bcc, null, $mailSection, func_get_args());
		}

		// Tax exempt waiting approval mail
		if (Redshop::getConfig()->get('USE_TAX_EXEMPT') && $data['tax_exempt'] == 1)
		{
			self::sendTaxExemptMail("tax_exempt_waiting_approval_mail", $data, $bcc);
		}

		return true;
	}

	/**
	 * Send tax exempt mail
	 *
	 * @param   string $mailSection Mail section
	 * @param   array  $userInfo    User info data
	 * @param   string $email       User email
	 *
	 * @return  boolean
	 */
	public static function sendTaxExemptMail($mailSection, $userInfo = array(), $email = "")
	{
		if ((boolean) Redshop::getConfig()->get('USE_TAX_EXEMPT') == false)
		{
			return false;
		}

		$app          = JFactory::getApplication();
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');
		$mailBcc      = null;
		$mailData     = $mailSection;
		$mailSubject  = $mailSection;
		$mailTemplate = self::getMailTemplate(0, $mailSection);

		if (count($mailTemplate) > 0)
		{
			$mailData    = html_entity_decode($mailTemplate[0]->mail_body, ENT_QUOTES);
			$mailSubject = html_entity_decode($mailTemplate[0]->mail_subject, ENT_QUOTES);

			if (trim($mailTemplate[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
			}
		}

		$search  = array();
		$replace = array();

		$search[]  = "{username}";
		$search[]  = "{shopname}";
		$search[]  = "{name}";
		$search[]  = "{company_name}";
		$search[]  = "{address}";
		$search[]  = "{city}";
		$search[]  = "{zipcode}";
		$search[]  = "{country}";
		$search[]  = "{phone}";
		$replace[] = $userInfo['username'];
		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $userInfo['firstname'] . ' ' . $userInfo['lastname'];

		if ($userInfo['is_company'] == 1)
		{
			$replace[] = $userInfo['company_name'];
		}
		else
		{
			$replace[] = "";
		}

		$replace[] = $userInfo['address'];
		$replace[] = $userInfo['city'];
		$replace[] = $userInfo['zipcode'];
		$replace[] = RedshopHelperOrder::getCountryName($userInfo['country_code']);
		$replace[] = $userInfo['phone'];

		$mailData = str_replace($search, $replace, $mailData);
		$mailData = self::imgInMail($mailData);

		if ($email != "")
		{
			self::sendEmail($mailFrom, $fromName, $email, $mailSubject, $mailData, true, null, $mailBcc, null, $mailSection, func_get_args());
		}

		return true;
	}

	/**
	 * Send subscriptions re-new mail
	 *
	 * @param   array $data Mail data
	 *
	 * @return  boolean
	 */
	public static function sendSubscriptionRenewalMail($data = array())
	{
		$mailSection  = "subscription_renewal_mail";
		$mailTemplate = self::getMailTemplate(0, $mailSection);

		if (empty($mailTemplate))
		{
			return false;
		}

		$app           = JFactory::getApplication();
		$productHelper = productHelper::getInstance();
		$redConfig     = Redconfiguration::getInstance();

		$mailTemplate = $mailTemplate[0];
		$data         = (object) $data;
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');
		$mailBcc      = null;

		$mailData    = $mailTemplate->mail_body;
		$mailSubject = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$userData = RedshopHelperOrder::getBillingAddress($data->user_id);

		if (!$userData)
		{
			return false;
		}

		$userEmail = $userData->user_email;
		$firstName = $userData->firstname;
		$lastName  = $userData->lastname;

		$product             = Redshop::product((int) $data->product_id);
		$productSubscription = $productHelper->getProductSubscriptionDetail($data->product_id, $data->subscription_id);

		$search   = array();
		$replace  = array();
		$search[] = "{shopname}";
		$search[] = "{firstname}";
		$search[] = "{lastname}";
		$search[] = "{product_name}";
		$search[] = "{subsciption_enddate}";
		$search[] = "{subscription_period}";
		$search[] = "{subscription_price}";
		$search[] = "{product_link}";

		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $firstName;
		$replace[] = $lastName;
		$replace[] = $product->product_name;
		$replace[] = $redConfig->convertDateFormat($data->end_date);
		$replace[] = $productSubscription->subscription_period . " " . $productSubscription->period_type;
		$replace[] = $productHelper->getProductFormattedPrice($productSubscription->subscription_price);

		$producturl = JUri::root() . 'index.php?option=com_redshop&view=product&pid=' . $data->product_id;

		$replace[] = "<a href='" . $producturl . "'>" . $product->product_name . "</a>";

		$mailData    = str_replace($search, $replace, $mailData);
		$mailData    = self::imgInMail($mailData);
		$mailSubject = str_replace($search, $replace, $mailSubject);

		return self::sendEmail($mailFrom, $fromName, $userEmail, $mailSubject, $mailData, true, null, $mailBcc, null, $mailSection, func_get_args());
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string $message Text message
	 *
	 * @return  string
	 */
	public static function imgInMail($message)
	{
		if (empty($message))
		{
			return '';
		}

		$url    = JUri::root();
		$images = array();

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $message, $matches);

		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);
			$image    = array_combine($m[1], $m[2]);
			$images[] = $image['src'];
		}

		$images = array_unique($images);

		if (empty($images))
		{
			return $message;
		}

		foreach ($images as $change)
		{
			if (strpos($change, 'http') === false)
			{
				$message = str_replace($change, $url . $change, $message);
			}
		}

		return $message;
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   int $quotationId Quotation id
	 * @param   int $status      Status
	 *
	 * @return  boolean
	 */
	public static function sendQuotationMail($quotationId, $status = 0)
	{
		$mailSection  = "quotation_mail";
		$mailTemplate = self::getMailTemplate(0, $mailSection);

		if (empty($mailTemplate) || !$quotationId)
		{
			return false;
		}

		// Call some helper.
		$cartHelper    = rsCarthelper::getInstance();
		$redConfig     = Redconfiguration::getInstance();
		$productHelper = productHelper::getInstance();
		$config        = JFactory::getConfig();
		$extraField    = extraField::getInstance();

		$mailTemplate = $mailTemplate[0];
		$mailBcc      = array();
		$message      = $mailTemplate->mail_body;
		$subject      = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$templateStart  = "";
		$templateEnd    = "";
		$templateMiddle = "";
		$cart           = "";
		$templateSdata  = explode('{product_loop_start}', $message);
		$fieldArray     = $extraField->getSectionFieldList(17, 0, 0);

		if (count($templateSdata) > 0)
		{
			$templateStart = $templateSdata[0];

			if (count($templateSdata) > 1)
			{
				$templateEdata = explode('{product_loop_end}', $templateSdata[1]);

				if (count($templateEdata) > 1)
				{
					$templateEnd = $templateEdata[1];
				}

				if (count($templateEdata) > 0)
				{
					$templateMiddle = $templateEdata[0];
				}
			}
		}

		$quotation = RedshopHelperQuotation::getQuotationDetail($quotationId);

		if (!$quotation)
		{
			return false;
		}

		$quotationProducts = RedshopHelperQuotation::getQuotationProduct($quotationId);

		foreach ($quotationProducts as $quotationProduct)
		{
			$productId                = $quotationProduct->product_id;
			$product                  = Redshop::product((int) $productId);
			$productName              = "<div class='product_name'>" . $quotationProduct->product_name . "</div>";
			$productTotalPrice        = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice(($quotationProduct->product_price * $quotationProduct->product_quantity)) . "</div>";
			$productPrice             = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice($quotationProduct->product_price) . "</div>";
			$productPriceExclVat      = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice($quotationProduct->product_excl_price) . "</div>";
			$productQuantity          = '<div class="update_cart">' . $quotationProduct->product_quantity . '</div>';
			$productTotalPriceExclVat = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice(($quotationProduct->product_excl_price * $quotationProduct->product_quantity)) . "</div>";

			$cartMdata   = $templateMiddle;
			$wrapperName = "";

			if ($quotationProduct->product_wrapperid)
			{
				$wrapper = $productHelper->getWrapper($productId, $quotationProduct->product_wrapperid);

				if (count($wrapper) > 0)
				{
					$wrapperName = $wrapper[0]->wrapper_name;
				}

				$wrapperName = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapperName;
			}

			$productImagePath = '';

			if ($product->product_full_image)
			{
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$productImagePath = $product->product_full_image;
				}
				else
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
					{
						$productImagePath = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
					}
				}
			}
			else
			{
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE')))
				{
					$productImagePath = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				}
			}

			if ($productImagePath)
			{
				$thumbUrl     = RedshopHelperMedia::getImagePath(
					$productImagePath,
					'',
					'thumb',
					'product',
					Redshop::getConfig()->get('CART_THUMB_WIDTH'),
					Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$productImage = "<div  class='product_image'><img src='" . $thumbUrl . "'></div>";
			}
			else
			{
				$productImage = "<div  class='product_image'></div>";
			}

			$cartMdata = str_replace("{product_name}", $productName, $cartMdata);
			$cartMdata = str_replace("{product_s_desc}", $product->product_s_desc, $cartMdata);
			$cartMdata = str_replace("{product_thumb_image}", $productImage, $cartMdata);

			$productNote       = "<div class='product_note'>" . $wrapperName . "</div>";
			$cartMdata         = str_replace("{product_wrapper}", $productNote, $cartMdata);
			$productUserFields = RedshopHelperQuotation::displayQuotationUserField($quotationProduct->quotation_item_id, 12);

			$cartMdata = str_replace("{product_userfields}", $productUserFields, $cartMdata);
			$cartMdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cartMdata);
			$cartMdata = str_replace("{product_number}", $product->product_number, $cartMdata);
			$cartMdata = str_replace(
				"{product_attribute}",
				$productHelper->makeAttributeQuotation(
					$quotationProduct->quotation_item_id,
					0,
					$quotationProduct->product_id,
					$quotation->quotation_status
				),
				$cartMdata
			);
			$cartMdata = str_replace(
				"{product_accessory}",
				$productHelper->makeAccessoryQuotation(
					$quotationProduct->quotation_item_id,
					$quotation->quotation_status
				),
				$cartMdata
			);

			// ProductFinderDatepicker Extra Field Start
			$cartMdata = $productHelper->getProductFinderDatepickerValue($cartMdata, $productId, $fieldArray);

			// ProductFinderDatepicker Extra Field End
			if ($quotation->quotation_status == 1 && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
			{
				$cartMdata = str_replace("{product_price_excl_vat}", "", $cartMdata);
				$cartMdata = str_replace("{product_price}", " ", $cartMdata);
				$cartMdata = str_replace("{product_total_price}", " ", $cartMdata);
				$cartMdata = str_replace("{product_subtotal_excl_vat}", " ", $cartMdata);
			}
			else
			{
				$cartMdata = str_replace("{product_price_excl_vat}", $productPriceExclVat, $cartMdata);
				$cartMdata = str_replace("{product_price}", $productPrice, $cartMdata);
				$cartMdata = str_replace("{product_total_price}", $productTotalPrice, $cartMdata);
				$cartMdata = str_replace("{product_subtotal_excl_vat}", $productTotalPriceExclVat, $cartMdata);
			}

			$cartMdata = str_replace("{product_quantity}", $productQuantity, $cartMdata);
			$cart      .= $cartMdata;
		}

		// End for

		$message = $templateStart . $cart . $templateEnd;

		$search[]  = "{quotation_note}";
		$replace[] = $quotation->quotation_note;
		$search[]  = "{shopname}";
		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$search[]  = "{quotation_id}";
		$replace[] = $quotation->quotation_id;
		$search[]  = "{quotation_number}";
		$replace[] = $quotation->quotation_number;
		$search[]  = "{quotation_date}";
		$replace[] = $redConfig->convertDateFormat($quotation->quotation_cdate);
		$search[]  = "{quotation_status}";
		$replace[] = RedshopHelperQuotation::getQuotationStatusName($quotation->quotation_status);

		$billAdd = '';

		if ($quotation->user_id != 0)
		{
			$message = RedshopHelperBillingTag::replaceBillingAddress($message, $quotation, true);
		}
		else
		{
			if ($quotation->quotation_email != "")
			{
				$billAdd .= JText::_("COM_REDSHOP_EMAIL") . ' : ' . $quotation->quotation_email . '<br />';
			}

			$message = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $message);

			if (strstr($message, "{quotation_custom_field_list}"))
			{
				$billAdd .= RedshopHelperExtrafields::listAllFieldDisplay(16, $quotation->user_info_id, 1, $quotation->quotation_email);
				$message = str_replace("{quotation_custom_field_list}", "", $message);
			}
			else
			{
				$message = RedshopHelperExtrafields::listAllFieldDisplay(16, $quotation->user_info_id, 1, $quotation->quotation_email, $message);
			}
		}

		$search[]    = "{billing_address}";
		$replace[]   = $billAdd;
		$totalLbl    = '';
		$subTotalLbl = '';
		$vatLbl      = '';

		if ($quotation->quotation_status != 1 || ($quotation->quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			$totalLbl    = JText::_('COM_REDSHOP_TOTAL_LBL');
			$subTotalLbl = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
			$vatLbl      = JText::_('COM_REDSHOP_QUOTATION_VAT');
		}

		$message = str_replace('{total_lbl}', $totalLbl, $message);
		$message = str_replace('{quotation_subtotal_lbl}', $subTotalLbl, $message);
		$message = str_replace('{quotation_vat_lbl}', $vatLbl, $message);
		$message = $cartHelper->replaceLabel($message);

		$search[]  = "{quotation_note}";
		$replace[] = $quotation->quotation_note;

		if ($quotation->quotation_status == 1 && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
		{
			$quotationSubtotal              = " ";
			$quotationTotal                 = " ";
			$quotationDiscount              = " ";
			$quotationVat                   = " ";
			$quotationSubtotalExclVat       = " ";
			$quotationSubtotalMinusDiscount = " ";
		}
		else
		{
			$tax = $quotation->quotation_tax;

			if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				$Discountvat                   = (
						(float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $quotation->quotation_discount) /
					(1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
					);
				$quotation->quotation_discount = $quotation->quotation_discount - $Discountvat;
				$tax                           = $tax - $Discountvat;
			}

			if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				$sp_discount                   = ($quotation->quotation_special_discount * ($quotation->quotation_subtotal + $quotation->quotation_tax)) / 100;
				$Discountspvat                 = (
						$sp_discount * (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')) /
					(1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
					);
				$DiscountspWithotVat           = $sp_discount - $Discountspvat;
				$quotation->quotation_discount = $quotation->quotation_discount + $DiscountspWithotVat;
				$tax                           = $tax - $Discountspvat;
			}

			$quotationSubtotalExclVat       = $productHelper->getProductFormattedPrice($quotation->quotation_subtotal - $quotation->quotation_tax);
			$quotationSubtotalMinusDiscount = $productHelper->getProductFormattedPrice($quotation->quotation_subtotal - $quotation->quotation_discount);
			$quotationSubtotal              = $productHelper->getProductFormattedPrice($quotation->quotation_subtotal);
			$quotationTotal                 = $productHelper->getProductFormattedPrice($quotation->quotation_total);
			$quotationDiscount              = $productHelper->getProductFormattedPrice($quotation->quotation_discount);
			$quotationVat                   = $productHelper->getProductFormattedPrice($quotation->quotation_tax);
		}

		$search[]  = "{quotation_subtotal}";
		$replace[] = $quotationSubtotal;
		$search[]  = "{quotation_total}";
		$replace[] = $quotationTotal;
		$search[]  = "{quotation_subtotal_minus_discount}";
		$replace[] = $quotationSubtotalMinusDiscount;
		$search[]  = "{quotation_subtotal_excl_vat}";
		$replace[] = $quotationSubtotalExclVat;
		$search[]  = "{quotation_discount}";
		$replace[] = $quotationDiscount;
		$search[]  = "{quotation_vat}";
		$replace[] = $quotationVat;

		$quotationDetailUrl = JUri::root() . 'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotationId . '&encr='
			. $quotation->quotation_encrkey;

		$search[]  = "{quotation_detail_link}";
		$replace[] = "<a href='" . $quotationDetailUrl . "'>" . JText::_("COM_REDSHOP_QUOTATION_DETAILS") . "</a>";

		$message = str_replace($search, $replace, $message);
		$message = self::imgInMail($message);
		$email   = $quotation->quotation_email;

		// Set the e-mail parameters
		$from     = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		$body     = $message;
		$subject  = str_replace($search, $replace, $subject);

		// Send the e-mail

		if ($email != "")
		{
			$bcc = array();

			if (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);

			if (!self::sendEmail($from, $fromname, $email, $subject, $body, true, null, $bcc, null, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('ERROR_SENDING_QUOTATION_MAIL'));
			}
		}

		if ($status != 0)
		{
			RedshopHelperQuotation::updateQuotationStatus($quotationId, $status);
		}

		return true;
	}

	/**
	 * Send newsletter confirmation mail
	 *
	 * @param   int $subscriptionId Subscription id
	 *
	 * @return  boolean
	 */
	public static function sendNewsletterConfirmationMail($subscriptionId)
	{
		if (!Redshop::getConfig()->get('NEWSLETTER_CONFIRMATION') || !$subscriptionId)
		{
			return false;
		}

		$config  = JFactory::getConfig();
		$url     = JUri::root();
		$db      = JFactory::getDbo();
		$mailBcc = null;

		$mailSection = "newsletter_confirmation";
		$mailInfo    = self::getMailTemplate(0, $mailSection);

		if (empty($mailInfo))
		{
			return false;
		}

		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		if (trim($mailInfo[0]->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_newsletter_subscription'))
			->where($db->qn('subscription_id') . ' = ' . $db->q((int) $subscriptionId));

		$list      = $db->setQuery($query)->loadObject();
		$link      = '<a href="' . $url . 'index.php?option=com_redshop&view=newsletter&sid=' . $subscriptionId . '">' .
			JText::_('COM_REDSHOP_CLICK_HERE') . '</a>';
		$search[]  = "{shopname}";
		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$search[]  = "{link}";
		$replace[] = $link;
		$search[]  = "{name}";
		$replace[] = $list->name;

		$email    = $list->email;
		$subject  = str_replace($search, $replace, $subject);
		$message  = str_replace($search, $replace, $message);
		$message  = self::imgInMail($message);
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		// Send the e-mail
		if ($email != "")
		{
			if (!self::sendEmail($from, $fromName, $email, $subject, $message, 1, null, $mailBcc, null, $mailSection, func_get_args()))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
			}
		}

		return true;
	}

	/**
	 * Send newsletter cancellation mail
	 *
	 * @param   string $email Email
	 *
	 * @return  boolean
	 */
	public static function sendNewsletterCancellationMail($email = "")
	{
		$mailSection = "newsletter_cancellation";
		$mailInfo    = self::getMailTemplate(0, $mailSection);

		if (empty($mailInfo))
		{
			return false;
		}

		$config  = JFactory::getConfig();
		$mailBcc = null;
		$message = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		if (trim($mailInfo[0]->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$search[]  = "{shopname}";
		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$subject   = str_replace($search, $replace, $subject);
		$message   = str_replace($search, $replace, $message);
		$message   = self::imgInMail($message);
		$from      = $config->get('mailfrom');
		$fromName  = $config->get('fromname');

		// Send the e-mail
		if ($email != "")
		{
			self::sendEmail($from, $fromName, $email, $subject, $message, 1, null, $mailBcc, null, $mailSection, func_get_args());
		}

		return true;
	}

	/**
	 * Send ask question mail
	 *
	 * @param   int $answerId Answer id
	 *
	 * @return  boolean
	 */
	public static function sendAskQuestionMail($answerId)
	{
		$mailSection = "ask_question_mail";
		$mailInfo    = self::getMailTemplate(0, $mailSection);

		if (empty($mailInfo) || !$answerId)
		{
			return false;
		}

		$productHelper = productHelper::getInstance();
		$uri           = JUri::getInstance();
		$url           = $uri->root();
		$mailBcc       = null;

		$dataAdd = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		// Only check if this field is not empty
		if (!empty($mailInfo[0]->mail_bcc))
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$answerData = $productHelper->getQuestionAnswer($answerId);

		if (empty($answerData))
		{
			return false;
		}

		$answerData = $answerData[0];
		$fromName   = $answerData->user_name;
		$from       = $answerData->user_email;
		$email      = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
		$question   = $answerData->question;
		$telephone  = "";
		$address    = "";
		$productId  = $answerData->product_id;
		$answer     = '';

		if ($answerData->parent_id)
		{
			$answer       = $answerData->question;
			$questionData = $productHelper->getQuestionAnswer($answerData->parent_id);

			if (count($questionData) > 0)
			{
				$config   = JFactory::getConfig();
				$from     = $config->get('mailfrom');
				$fromName = $config->get('fromname');

				$questionData = $questionData[0];
				$question     = $questionData->question;
				$email        = $questionData->user_email;
				$productId    = $questionData->product_id;
				$address      = $questionData->address;
				$telephone    = $questionData->telephone;
			}
		}

		$product    = Redshop::product((int) $productId);
		$link       = JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $productId);
		$dataAdd    = str_replace("{product_name}", $product->product_name, $dataAdd);
		$dataAdd    = str_replace("{product_desc}", $product->product_desc, $dataAdd);
		$productUrl = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$dataAdd    = str_replace("{product_link}", $productUrl, $dataAdd);
		$dataAdd    = str_replace("{user_question}", $question, $dataAdd);
		$dataAdd    = str_replace("{answer}", $answer, $dataAdd);
		$dataAdd    = str_replace("{user_address}", $address, $dataAdd);
		$dataAdd    = str_replace("{user_telephone}", $telephone, $dataAdd);
		$subject    = str_replace("{user_question}", $question, $subject);
		$subject    = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $subject);
		$subject    = str_replace("{product_name}", $product->product_name, $subject);
		$dataAdd    = self::imgInMail($dataAdd);

		if ($email && self::sendEmail($from, $fromName, $email, $subject, $dataAdd, 1, null, $mailBcc, null, $mailSection, func_get_args()))
		{
			return true;
		}

		return false;
	}

	/**
	 * Send economic book invoice mail
	 *
	 * @param   int    $orderId        Order id
	 * @param   string $bookInvoicePdf Book invoice PDF
	 *
	 * @return  boolean
	 */
	public static function sendEconomicBookInvoiceMail($orderId = 0, $bookInvoicePdf = "")
	{
		if ($orderId == 0)
		{
			return false;
		}

		$redConfig   = Redconfiguration::getInstance();
		$config      = JFactory::getConfig();
		$from        = $config->get('mailfrom');
		$fromName    = $config->get('fromname');
		$mailSection = "economic_inoice";
		$mailInfo    = self::getMailTemplate(0, $mailSection);
		$dataAdd     = "economic inoice";
		$subject     = "economic_inoice";
		$mailBcc     = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$orderDetail     = RedshopHelperOrder::getOrderDetails($orderId);
		$userBillingInfo = RedshopHelperOrder::getOrderBillingUserInfo($orderId);

		$search[] = "{name}";
		$search[] = "{order_number}";
		$search[] = "{order_comment}";
		$search[] = "{order_id}";
		$search[] = "{order_date}";

		if ($userBillingInfo->is_company == 1 && $userBillingInfo->company_name != '')
		{
			$replace[] = $userBillingInfo->company_name;
		}
		else
		{
			$replace[] = $userBillingInfo->firstname . " " . $userBillingInfo->lastname;
		}

		$replace[] = $orderDetail->order_number;
		$replace[] = $orderDetail->customer_note;
		$replace[] = $orderDetail->order_id;
		$replace[] = $redConfig->convertDateFormat($orderDetail->cdate);

		$dataAdd = str_replace($search, $replace, $dataAdd);
		$dataAdd = self::imgInMail($dataAdd);

		$attachment[] = $bookInvoicePdf;

		if ($userBillingInfo->user_email != "")
		{
			self::sendEmail($from, $fromName, $userBillingInfo->user_email, $subject, $dataAdd, 1, null, $mailBcc, $attachment, $mailSection, func_get_args());
		}

		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != '')
		{
			$sendTo = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			self::sendEmail($from, $fromName, $sendTo, $subject, $dataAdd, 1, null, $mailBcc, $attachment, $mailSection, func_get_args());
		}

		return true;
	}

	/**
	 * Send request tax exempt mail
	 *
	 * @param   object $data     Mail data
	 * @param   string $username Username
	 *
	 * @return  boolean
	 */
	public static function sendRequestTaxExemptMail($data, $username = "")
	{
		if (empty(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')))
		{
			return false;
		}

		$mailSection = "request_tax_exempt_mail";
		$mailInfo    = self::getMailTemplate(0, $mailSection);
		$dataAdd     = "";
		$subject     = "";
		$mailBcc     = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$config      = JFactory::getConfig();
		$from        = $config->get('mailfrom');
		$fromName    = $config->get('fromname');
		$stateName   = RedshopHelperOrder::getStateName($data->state_code);
		$countryName = RedshopHelperOrder::getCountryName($data->country_code);

		$dataAdd = str_replace("{vat_number}", $data->vat_number, $dataAdd);
		$dataAdd = str_replace("{username}", $username, $dataAdd);
		$dataAdd = str_replace("{company_name}", $data->company_name, $dataAdd);
		$dataAdd = str_replace("{country}", $countryName, $dataAdd);
		$dataAdd = str_replace("{state}", $stateName, $dataAdd);
		$dataAdd = str_replace("{phone}", $data->phone, $dataAdd);
		$dataAdd = str_replace("{zipcode}", $data->zipcode, $dataAdd);
		$dataAdd = str_replace("{address}", $data->address, $dataAdd);
		$dataAdd = str_replace("{city}", $data->city, $dataAdd);
		$dataAdd = self::imgInMail($dataAdd);
		$sendto  = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));

		return self::sendEmail($from, $fromName, $sendto, $subject, $dataAdd, 1, null, $mailBcc, null, $mailSection, func_get_args());
	}

	/**
	 * Send catalog request
	 *
	 * @param   array $catalog Catalog data
	 *
	 * @return  boolean
	 */
	public static function sendCatalogRequest($catalog = array())
	{
		$catalog     = (object) $catalog;
		$db          = JFactory::getDbo();
		$mailSection = "catalog";
		$mailInfo    = self::getMailTemplate(0, $mailSection);
		$dataAdd     = "";
		$subject     = "";
		$mailBcc     = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$config   = JFactory::getConfig();
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_media'))
			->where($db->qn('media_section') . ' = ' . $db->quote('catalog'))
			->where($db->qn('media_type') . ' = ' . $db->quote('document'))
			->where($db->qn('section_id') . ' = ' . (int) $catalog->catalog_id)
			->where($db->qn('published') . ' = 1');

		$catalogMedias = $db->setQuery($query)->loadObjectList();
		$attachment    = array();

		foreach ($catalogMedias as $catalogMedia)
		{
			$attachment[] = REDSHOP_FRONT_DOCUMENT_RELPATH . 'catalog/' . $catalogMedia->media_name;
		}

		$dataAdd = str_replace("{name}", $catalog->name, $dataAdd);
		$dataAdd = self::imgInMail($dataAdd);

		return self::sendEmail($from, $fromName, $catalog->email, $subject, $dataAdd, 1, null, $mailBcc, $attachment, $mailSection, func_get_args());
	}

	/**
	 * Send catalog request
	 *
	 * @param   string  $from        Sender email
	 * @param   string  $fromName    Sender name
	 * @param   mixed   $receiver    Receiver email
	 * @param   string  $subject     Mail subject
	 * @param   string  $body        Mail body
	 * @param   boolean $isHtml      True for use HTML for plain.
	 * @param   mixed   $mailCC      List of CC emails
	 * @param   mixed   $mailBCC     List of Bcc emails
	 * @param   mixed   $attachment  Attachment files.
	 * @param   string  $mailSection Mail Section
	 * @param   string  $argList     Function arguments
	 *
	 * @return  boolean          True on success. False otherwise.
	 */
	public static function sendEmail($from, $fromName, $receiver, $subject, $body, $isHtml = true, $mailCC = null, $mailBCC = null, $attachment = null, $mailSection = '', $argList = null)
	{
		if (empty($receiver) || empty($subject) || empty($body))
		{
			return false;
		}

		if (empty($from) || empty($fromName))
		{
			$config   = JFactory::getConfig();
			$from     = $config->get('mailfrom', '');
			$fromName = $config->get('fromname', '');
		}

		if (empty($from) || empty($fromName))
		{
			return false;
		}

		$mail = JFactory::getMailer();
		$mail->setSender(array($from, $fromName));
		$mail->setSubject($subject);
		$mail->setBody($body);
		$mail->addRecipient($receiver);

		if (!empty($mailCC))
		{
			$mail->addCc($mailCC);
		}

		if (!empty($mailBCC))
		{
			$mail->addBcc($mailBCC);
		}

		$mail->isHtml((boolean) $isHtml);

		if (!empty($attachment))
		{
			$mail->addAttachment($attachment);
		}

		JPluginHelper::importPlugin('redshop_mail');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		// Process the product plugin before send mail
		$dispatcher->trigger('beforeRedshopSendMail', array(&$mail, $mailSection, $argList));

		$isSend = $mail->Send();

		// Process the product plugin after send mail
		$dispatcher->trigger('afterRedshopSendMail', array(&$mail, $mailSection, $argList, $isSend));

		return $isSend;
	}
}
