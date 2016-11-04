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
 * Class Redshop Helper for Mail
 *
 * @since  2.0.0.3
 */
class RedshopHelperMail
{
	protected static $mailTemplates = array();

	/**
	 * Method to get mail section
	 *
	 * @param   int     $tId        Template id
	 * @param   string  $section    Template section
	 * @param   string  $extraCond  Extra condition for query
	 *
	 * @return  array
	 */
	public static function getMailTemplate($tId = 0, $section = '', $extraCond = '')
	{
		$key = $tId . '_' . $section . '_' . serialize($extraCond);

		if (!array_key_exists($key, self::$mailTemplates))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_mail'))
				->where($db->qn('published') . ' = 1');

			if ($tId)
			{
				$query->where($db->qn('mail_id') . ' = ' . (int) $tId);
			}

			if ($section)
			{
				$query->where($db->qn('mail_section') . ' = ' . $db->q($section));
			}

			if ($extraCond)
			{
				$query->where($extraCond);
			}

			self::$mailTemplates[$key] = $db->setQuery($query)->loadObjectlist();
		}

		return self::$mailTemplates[$key];
	}

	/**
	 * sendOrderMail function.
	 *
	 * @param   int      $orderId    Order ID.
	 * @param   boolean  $onlyAdmin  send mail only to admin
	 *
	 * @return  boolean
	 */
	public static function sendOrderMail($orderId, $onlyAdmin = false)
	{
		$cartHelper     = rsCarthelper::getInstance();
		$orderFunctions = order_functions::getInstance();
		$redConfig      = Redconfiguration::getInstance();
		$productHelper  = productHelper::getInstance();
		$config         = JFactory::getConfig();

		// Set the e-mail parameters
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		if (Redshop::getConfig()->get('USE_AS_CATALOG'))
		{
			$mailInfo = self::getMailTemplate(0, "catalogue_order");
		}
		else
		{
			$mailInfo = self::getMailTemplate(0, "order");
		}

		if (count($mailInfo) > 0)
		{
			$message = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;
		}
		else
		{
			return false;
		}

		$row           = $orderFunctions->getOrderDetails($orderId);
		$orderPayment  = $orderFunctions->getOrderPaymentDetail($orderId);

		// It is necessory to take billing info from order user info table
		// Order mail output should reflect the checkout process"

		$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
		$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
		$message = $cartHelper->replaceOrderTemplate($row, $message, true);

		$rowItem = $orderFunctions->getOrderItemDetail($orderId);

		$manufacturerEmail = array();
		$supplierEmail = array();

		for ($i = 0, $in = count($rowItem); $i < $in; $i++)
		{
			if ($rowItem[$i]->is_giftcard == '1')
			{
				$product          = $productHelper->getGiftcardData((int) $rowItem[$i]->product_id);
				$manufacturerData = array();
				$supplierData     = array();
			}
			else
			{
				$product          = Redshop::product((int) $rowItem[$i]->product_id);
				$manufacturerData = $productHelper->getSection("manufacturer", $product->manufacturer_id);
				$supplierData     = $productHelper->getSection("supplier", $product->supplier_id);
			}

			if (count($manufacturerData) > 0)
			{
				if ($manufacturerData->manufacturer_email != '')
				{
					$manufacturerEmail[$i] = $manufacturerData->manufacturer_email;
				}
			}

			if (count($supplierData) > 0)
			{
				if ($supplierData->supplier_email != '')
				{
					$supplierEmail[$i] = $supplierData->supplier_email;
				}
			}
		}

		$arrDiscount = explode('@', $row->discount_type);
		$discountType = '';

		for ($d = 0, $dn = count($arrDiscount); $d < $dn; $d++)
		{
			if ($arrDiscount[$d])
			{
				$arrDiscountType = explode(':', $arrDiscount[$d]);

				if ($arrDiscountType[0] == 'c')
				{
					$discountType .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arrDiscountType[1] . '<br>';
				}

				if ($arrDiscountType[0] == 'v')
				{
					$discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arrDiscountType[1] . '<br>';
				}
			}
		}

		if (!$discountType)
		{
			$discountType = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		$search[]         = "{discount_type}";
		$replace[]        = $discountType;

		$orderDetailUrl   = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid='
			. $orderId . '&encr=' . $row->encr_key;
		$search[]         = "{order_detail_link}";
		$replace[]        = "<a href='" . $orderDetailUrl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$message          = str_replace($search, $replace, $message);
		$message          = self::imgInMail($message);
		$thirdPartyEmail  = $billingAddresses->thirdparty_email;
		$email            = $billingAddresses->user_email;

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
		$replaceSub[] = $redConfig->convertDateFormat($row->cdate);
		$subject      = str_replace($searchSub, $replaceSub, $subject);

		// Send the e-mail
		if ($email != "")
		{
			$mailBcc = array();

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}

			$bcc      = (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '') ?
				explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'))) : array();
			$bcc      = array_merge($bcc, $mailBcc);
			$fullName = $billingAddresses->firstname . " " . $billingAddresses->lastname;

			if ($billingAddresses->is_company == 1 && $billingAddresses->company_name != "")
			{
				$fullName = $billingAddresses->company_name;
			}

			$subject = str_replace("{fullname}", $fullName, $subject);
			$subject = str_replace("{firstname}", $billingAddresses->firstname, $subject);
			$subject = str_replace("{lastname}", $billingAddresses->lastname, $subject);
			$message = str_replace("{fullname}", $fullName, $message);
			$message = str_replace("{firstname}", $billingAddresses->firstname, $message);
			$message = str_replace("{lastname}", $billingAddresses->lastname, $message);
			$body    = $message;

			// As only need to send email to administrator,
			// Here variables are changed to use bcc email - from redSHOP configuration - Administrator Email
			if ($onlyAdmin)
			{
				$email           = $bcc;
				$thirdPartyEmail = '';
				$bcc             = null;
			}

			if ($thirdPartyEmail != '')
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $thirdPartyEmail, $subject, $body, 1, null, $bcc))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}

			if (!JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $body, 1, null, $bcc))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
			}
		}

		// As email only need to send admin no need to send email to others.
		if ($onlyAdmin)
		{
			return true;
		}

		if (Redshop::getConfig()->get('MANUFACTURER_MAIL_ENABLE'))
		{
			sort($manufacturerEmail);

			for ($man = 0; $man < count($manufacturerEmail); $man++)
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $manufacturerEmail[$man], $subject, $body, 1))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		if (Redshop::getConfig()->get('SUPPLIER_MAIL_ENABLE'))
		{
			sort($supplierEmail);

			for ($sup = 0; $sup < count($supplierEmail); $sup++)
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $supplierEmail[$sup], $subject, $body, 1))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		// Invoice mail send
		if (Redshop::getConfig()->get('INVOICE_MAIL_ENABLE') && $row->order_payment_status == "Paid")
		{
			self::sendInvoiceMail($orderId);
		}

		return true;
	}

	/**
	 * send Order Special Discount Mail function.
	 *
	 * @param   int  $orderId  Order ID.
	 *
	 * @return  boolean
	 */
	public static function sendOrderSpecialDiscountMail($orderId)
	{
		$cartHelper     = rsCarthelper::getInstance();
		$orderFunctions = order_functions::getInstance();
		$productHelper  = productHelper::getInstance();
		$config         = JFactory::getConfig();
		$mailBcc        = array();
		$mailInfo       = self::getMailTemplate(0, "order_special_discount");

		if (count($mailInfo) > 0)
		{
			$message = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$manufacturerEmail = array();
		$row               = $orderFunctions->getOrderDetails($order_id);
		$billingAddresses  = RedshopHelperOrder::getOrderBillingUserInfo($order_id);
		$orderPayment      = $orderFunctions->getOrderPaymentDetail($order_id);
		$paymentMethod     = $orderFunctions->getPaymentMethodInfo($orderPayment[0]->payment_method_class);
		$paymentMethod     = $paymentMethod[0];
		$message           = $cartHelper->replaceOrderTemplate($row, $message, true);

		// Set order paymethod name
		$search[]       = "{shopname}";
		$replace[]      = Redshop::getConfig()->get('SHOP_NAME');
		$search[]       = "{payment_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		$search[]       = "{payment_method}";
		$replace[]      = "";
		$search[]       = "{special_discount}";
		$replace[]      = $row->special_discount . '%';
		$search[]       = "{special_discount_amount}";
		$replace[]      = $productHelper->getProductFormattedPrice($row->special_discount_amount);
		$search[]       = "{special_discount_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');
		$orderDetailUrl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid='
			. $order_id . '&encr=' . $row->encr_key;
		$search[]       = "{order_detail_link}";
		$replace[]      = "<a href='" . $orderDetailUrl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
		$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentMethod->element);

		if ($isBankTransferPaymentType)
		{
			$paymentParams = new JRegistry($paymentMethod->params);
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

			if (Redshop::getConfig()->get('SPECIAL_DISCOUNT_MAIL_SEND') == '1')
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $body, 1, null, $bcc))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		if (Redshop::getConfig()->get('MANUFACTURER_MAIL_ENABLE'))
		{
			sort($manufacturerEmail);

			for ($man = 0; $man < count($manufacturerEmail); $man++)
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $manufacturerEmail[$man], $subject, $body, 1))
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
	 * @param   array  $oid  Order ID List.
	 *
	 * @return  boolean
	 */
	public static function createMultiprintInvoicePdf($oid)
	{
		$cartHelper     = rsCarthelper::getInstance();
		$orderFunctions = order_functions::getInstance();
		$redTemplate    = Redtemplate::getInstance();
		$message        = "";
		$pdfObj         = RedshopHelperPdf::getInstance();
		$pdfObj->SetTitle('Shipped');

		// Changed font to support Unicode Characters - Specially Polish Characters
		$font = 'times';
		$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdfObj->setHeaderFont(array($font, '', 8));

		// Set font
		$pdfObj->SetFont($font, "", 6);

		for ($o = 0, $on = count($oid); $o < $on; $o++)
		{
			$orderId            = $oid[$o];
			$ordersDetail       = $orderFunctions->getOrderDetails($orderId);
			$orderPrintTemplate = $redTemplate->getTemplate("order_print");

			if (count($orderPrintTemplate) > 0 && $orderPrintTemplate[0]->template_desc != "")
			{
				$message = $orderPrintTemplate[0]->template_desc;
			}
			else
			{
				$message = '<table style="width: 100%;" border="0" cellpadding="5" cellspacing="0">
				<tbody><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody>
				<tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}{print}</th></tr><tr></tr
				><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr>
				<td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr><tr>
				<td>{shipping_method_lbl} : {shipping_method} : {shipping_rate_name}</td></tr><tr><td>{payment_lbl} : {payment_method}</td>
				</tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th>
				</tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan="2">
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;">
				<th align="left">{shipping_address_info_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody>
				</table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr><tr></tr><tr><td>
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody><tr><td>{product_name_lbl}</td><td>{note_lbl}</td>
				<td>{price_lbl}</td><td>{quantity_lbl}</td><td align="right">Total Price</td></tr>{product_loop_start}<tr>
				<td><p>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}</p></td>
				<td>{product_wrapper}{product_thumb_image}</td><td>{product_price}</td><td>{product_quantity}</td>
				<td align="right">{product_total_price}</td></tr>{product_loop_end}</tbody></table></td></tr><tr>
				<td></td></tr><tr><td><table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
				<tr align="left"><td align="left"><strong>{order_subtotal_lbl} : </strong></td><td align="right">{order_subtotal}</td>
				</tr>{if vat}<tr align="left"><td align="left"><strong>{vat_lbl} : </strong></td><td align="right">{order_tax}</td>
				</tr>{vat end if}{if discount}<tr align="left"><td align="left"><strong>{discount_lbl} : </strong></td>
				<td align="right">{order_discount}</td></tr>{discount end if}<tr align="left"><td align="left">
				<strong>{shipping_lbl} : </strong></td><td align="right">{order_shipping}</td></tr><tr align="left">
				<td colspan="2" align="left"><hr /></td></tr><tr align="left"><td align="left"><strong>{total_lbl} :</strong>
				</td><td align="right">{order_total}</td></tr><tr align="left"><td colspan="2" align="left"><hr /><br />
				 <hr /></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';
			}

			$printTag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
				. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
				. JText::_('COM_REDSHOP_PRINT') . "' /></a>";

			$message = str_replace("{print}", $printTag, $message);
			$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
			$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
			$message = $cartHelper->replaceOrderTemplate($ordersDetail, $message, true);
			$pdfObj->AddPage();
			$pdfObj->WriteHTML($message, true, false, true, false, '');
		}

		$invoicePdfName = "multiprintorder" . round(microtime(true) * 1000);
		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", "F");
		$storeFiles = array('index.html', '' . $invoicePdfName . '.pdf');

		foreach (glob(JPATH_SITE . "/components/com_redshop/assets/document/invoice/*") as $file)
		{
			if (!in_array(basename($file), $storeFiles))
			{
				unlink($file);
			}
		}

		return $invoicePdfName;
	}

	/**
	 * Replace invoice mail template tags and prepare mail body and pdf html
	 *
	 * @param   integer  $orderId  Order Information ID
	 * @param   string   $html     HTML template of mail body or pdf
	 * @param   string   $subject  Email Subject template, can be null for PDF
	 * @param   string   $type     Either 'html' or 'pdf'
	 *
	 * @return  object  Object having mail body and subject. subject can be null for PDF type.
	 */
	public static function replaceInvoiceMailTemplate($orderId, $html, $subject = null, $type = 'pdf')
	{
		$cartHelper      = rsCarthelper::getInstance();
		$orderFunctions  = order_functions::getInstance();
		$redConfig       = Redconfiguration::getInstance();
		$arrDiscountType = array();
		$row             = $orderFunctions->getOrderDetails($orderId);
		$arrDiscount     = explode('@', $row->discount_type);
		$discountType    = '';

		for ($d = 0, $dn = count($arrDiscount); $d < $dn; $d++)
		{
			if ($arrDiscount[$d])
			{
				$arrDiscountType = explode(':', $arrDiscount[$d]);

				if ($arrDiscountType[0] == 'c')
				{
					$discountType .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arrDiscountType[1] . '<br>';
				}

				if ($arrDiscountType[0] == 'v')
				{
					$discountType .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arrDiscountType[1] . '<br>';
				}
			}
		}

		if (!$discountType)
		{
			$discountType = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		// Prepare subject replacement
		$searchSub[]     = "{order_id}";
		$replaceSub[]    = $row->order_id;
		$searchSub[]     = "{order_number}";
		$replaceSub[]    = $row->order_number;
		$searchSub[]     = "{invoice_number}";
		$replaceSub[]    = $row->invoice_number;
		$searchSub[]     = "{shopname}";
		$replaceSub[]    = Redshop::getConfig()->get('SHOP_NAME');

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
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  boolean  True on sending email successfully.
	 */
	public static function sendInvoiceMail($orderId)
	{
		$config   = JFactory::getConfig();
		$mailBcc  = null;
		$mailInfo = self::getMailTemplate(0, "invoice_mail");

		if (count($mailInfo) > 0)
		{
			$message = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$mailTemplate    = self::replaceInvoiceMailTemplate($orderId, $message, $subject, 'html');
		$mailBody        = $mailTemplate->body;
		$subject         = $mailTemplate->subject;
		$pdfTemplateFile = self::getMailTemplate(0, "invoicefile_mail");

		// Init PDF template body
		$pdfTemplate = $mailBody;

		// Set actual PDF template if found
		if (count($pdfTemplateFile) > 0)
		{
			$pdfTemplate = self::replaceInvoiceMailTemplate($orderId, $pdfTemplateFile[0]->mail_body)->body;
		}

		ob_clean();

		$options = array(
			'format' => 'A4'
		);
		$pdfObj = RedshopHelperPdf::getInstance('tcpdf', $options);
		$pdfObj->SetTitle(JText::_('COM_REDSHOP_INVOICE') . $orderId);
		$pdfObj->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
		$pdfObj->setHeaderFont(array('times', '', 10));
		$pdfObj->AddPage();
		$pdfObj->WriteHTML($pdfTemplate, true, false, true, false, '');

		$invoicePdfName = $orderId;

		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", "F");
		$invoiceAttachment = JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf";

		// Set the e-mail parameters
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$email            = $billingAddresses->user_email;
		$mailBody         = self::imgInMail($mailBody);

		if ((Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 2
			|| Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& $email != "")
		{
			if (!JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $mailBody, 1, null, $mailBcc, $invoiceAttachment))
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		if ((Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 1
			|| Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != '')
		{
			$sendTo = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));

			if (!JFactory::getMailer()->sendMail($from, $fromName, $sendTo, $subject, $mailBody, 1, null, $mailBcc, $invoiceAttachment))
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
	 * @param   array  &$data  registration data
	 *
	 * @return  boolean
	 */
	public static function sendRegistrationMail(&$data)
	{
		$app          = JFactory::getApplication();
		$me           = JFactory::getUser();
		$mainPassword = $app->input->post->getString('password1');
		$mailFrom     = $app->getCfg('mailfrom');
		$fromName     = $app->getCfg('fromname');

		// Time for the email magic so get ready to sprinkle the magic dust...
		$adminEmail   = $me->get('email');
		$adminName    = $me->get('name');
		$mailData     = "";
		$mailSubject  = "";
		$mailBcc      = array();
		$mailTemplate = self::getMailTemplate(0, "register");

		if (count($mailTemplate) > 0)
		{
			$mailData    = $mailTemplate[0]->mail_body;
			$mailsubject = $mailTemplate[0]->mail_subject;

			if (trim($mailTemplate[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$search    = array();
		$replace   = array();
		$search[]  = "{shopname}";
		$search[]  = "{firstname}";
		$search[]  = "{lastname}";
		$search[]  = "{fullname}";
		$search[]  = "{name}";
		$search[]  = "{username}";
		$search[]  = "{password}";
		$search[]  = "{email}";
		$search[]  = '{account_link}';

		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $data['firstname'];
		$replace[] = $data['lastname'];
		$replace[] = $data['firstname'] . " " . $data['lastname'];
		$replace[] = $data['name'];
		$replace[] = $data['username'];
		$replace[] = $mainPassword;
		$replace[] = $data['email'];
		$replace[] = '<a href="' . JURI::root() . 'index.php?option=com_redshop&view=account'
			. '" target="_blank">' . JText::_('COM_REDSHOP_ACCOUNT_LINK') . '</a>';

		$mailBody    = str_replace($search, $replace, $mailData);
		$mailBody    = self::imgInMail($mailBody);
		$mailSubject = str_replace($search, $replace, $mailSubject);

		if ($mailFrom != '' && $fromName != '')
		{
			$adminName  = $fromName;
			$adminEmail = $mailFrom;
		}

		$bcc = array();

		if ($mailBody && $data['email'] != "")
		{
			if (trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);
			JFactory::getMailer()->sendMail($mailFrom, $fromName, $data['email'], $mailSubject, $mailBody, 1, null, $bcc);
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
	 * @param   string  $section   Mail section
	 * @param   array   $userInfo  User info data
	 * @param   string  $email     User email
	 *
	 * @return  boolean
	 */
	public static function sendTaxExemptMail($section, $userInfo = array(), $email = "")
	{
		if (Redshop::getConfig()->get('USE_TAX_EXEMPT'))
		{
			$orderFunctions = order_functions::getInstance();
			$app            = JFactory::getApplication();
			$mailFrom       = $app->getCfg('mailfrom');
			$fromName       = $app->getCfg('fromname');
			$mailBcc        = null;
			$mailData       = $section;
			$mailSubject    = $section;
			$mailTemplate   = self::getMailTemplate(0, $section);

			if (count($mailTemplate) > 0)
			{
				$mailData    = html_entity_decode($mailTemplate[0]->mail_body, ENT_QUOTES);
				$mailSubject = html_entity_decode($mailTemplate[0]->mail_subject, ENT_QUOTES);

				if (trim($mailTemplate[0]->mail_bcc) != "")
				{
					$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
				}
			}

			$search    = array();
			$replace   = array();

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
			$replace[] = $orderFunctions->getCountryName($userInfo['country_code']);
			$replace[] = $userInfo['phone'];

			$mailData = str_replace($search, $replace, $mailData);
			$mailData = self::imgInMail($mailData);

			if ($email != "")
			{
				JFactory::getMailer()->sendMail($mailFrom, $fromName, $email, $mailSubject, $mailData, 1, null, $mailBcc);
			}
		}

		return true;
	}

	/**
	 * Send subcription renewwal mail
	 *
	 * @param   array  $data  Mail data
	 *
	 * @return  boolean
	 */
	public static function sendSubscriptionRenewalMail($data = array())
	{
		$data           = (object) $data;
		$orderFunctions = order_functions::getInstance();
		$app            = JFactory::getApplication();
		$productHelper  = productHelper::getInstance();
		$redConfig      = Redconfiguration::getInstance();
		$mailFrom       = $app->getCfg('mailfrom');
		$fromName       = $app->getCfg('fromname');
		$userEmail      = "";
		$firstName      = "";
		$lastName       = "";
		$mailData       = "";
		$mailSubject    = "";
		$mailBcc        = null;
		$mailTemplate   = self::getMailTemplate(0, "subscription_renewal_mail");

		if (count($mailTemplate) > 0)
		{
			$mailTemplate = $mailTemplate[0];
			$mailData     = $mailTemplate->mail_body;
			$mailSubject  = $mailTemplate->mail_subject;

			if (trim($mailTemplate->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$userData = $orderFunctions->getBillingAddress($data->user_id);

		if (count($userData) > 0)
		{
			$userEmail = $userData->user_email;
			$firstName = $userData->firstname;
			$lastName  = $userData->lastname;
		}

		$product             = Redshop::product((int) $data->product_id);
		$productSubscription = $productHelper->getProductSubscriptionDetail($data->product_id, $data->subscription_id);

		$search    = array();
		$replace   = array();
		$search[]  = "{shopname}";
		$search[]  = "{firstname}";
		$search[]  = "{lastname}";
		$search[]  = "{product_name}";
		$search[]  = "{subsciption_enddate}";
		$search[]  = "{subscription_period}";
		$search[]  = "{subscription_price}";
		$search[]  = "{product_link}";

		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $firstName;
		$replace[] = $lastName;
		$replace[] = $product->product_name;
		$replace[] = $redConfig->convertDateFormat($data->end_date);
		$replace[] = $productSubscription->subscription_period . " " . $productSubscription->period_type;
		$replace[] = $productHelper->getProductFormattedPrice($productSubscription->subscription_price);

		$producturl  = JURI::root() . 'index.php?option=com_redshop&view=product&pid=' . $data->product_id;

		$replace[]   = "<a href='" . $producturl . "'>" . $product->product_name . "</a>";

		$mailData    = str_replace($search, $replace, $mailData);
		$mailData = self::imgInMail($mailData);

		$mailSubject = str_replace($search, $replace, $mailSubject);

		if ($userEmail != "")
		{
			JFactory::getMailer()->sendMail($mailFrom, $fromName, $userEmail, $mailSubject, $mailData, 1, null, $mailBcc);
		}

		return true;
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string  $message  Text message
	 *
	 * @return  string
	 */
	public static function imgInMail($message)
	{
		$url            = JFactory::getURI()->root();
		$imagesCurArray = array();

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $message, $matches);

		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);
			$imagesCur        = array_combine($m[1], $m[2]);
			$imagesCurArray[] = $imagesCur['src'];
		}

		$imagesCurArray = array_unique($imagesCurArray);

		if (count($imagesCurArray))
		{
			foreach ($imagesCurArray as $change)
			{
				if (strpos($change, 'http') === false)
				{
					$message = str_replace($change, $url . $change, $message);
				}
			}
		}

		return $message;
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   int  $quotationId  Quotation id
	 * @param   int  $status       Status
	 *
	 * @return  boolean
	 */
	public static function sendQuotationMail($quotationId, $status = 0)
	{
		$cartHelper      = rsCarthelper::getInstance();
		$redConfig       = Redconfiguration::getInstance();
		$productHelper   = productHelper::getInstance();
		$extraAdminField = extra_field::getInstance();
		$quotationHelper = quotationHelper::getInstance();
		$config          = JFactory::getConfig();
		$mailInfo        = self::getMailTemplate(0, "quotation_mail");
		$mailBcc         = array();
		$extraField      = extraField::getInstance();

		if (count($mailInfo) > 0)
		{
			$message = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		else
		{
			return false;
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

		$row = $quotationHelper->getQuotationDetail($quotationId);

		if (!$row)
		{
			return false;
		}

		$rowItem = $quotationHelper->getQuotationProduct($quotationId);

		for ($i = 0, $in = count($rowItem); $i < $in; $i++)
		{
			$productId                   = $rowItem[$i]->product_id;
			$product                      = Redshop::product((int) $productId);
			$productName                 = "<div class='product_name'>" .
				$rowItem[$i]->product_name . "</div>";
			$productTotalPrice          = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice(($rowItem[$i]->product_price * $rowItem[$i]->product_quantity)) . "</div>";
			$productPrice                = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice($rowItem[$i]->product_price) . "</div>";
			$productPriceExclVat       = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice($rowItem[$i]->product_excl_price) . "</div>";
			$productQuantity             = '<div class="update_cart">' .
				$rowItem[$i]->product_quantity . '</div>';
			$productTotalPriceExclVat = "<div class='product_price'>" .
				$productHelper->getProductFormattedPrice(($rowItem[$i]->product_excl_price * $rowItem[$i]->product_quantity)) . "</div>";

			$cartMdata   = $templateMiddle;
			$wrapperName = "";

			if ($rowItem[$i]->product_wrapperid)
			{
				$wrapper = $productHelper->getWrapper($productId, $rowItem[$i]->product_wrapperid);

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
				$thumbUrl = RedShopHelperImages::getImagePath(
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
			$productUserFields = $quotationHelper->displayQuotationUserfield($rowItem[$i]->quotation_item_id, 12);

			$cartMdata = str_replace("{product_userfields}", $productUserFields, $cartMdata);
			$cartMdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cartMdata);
			$cartMdata = str_replace("{product_number}", $product->product_number, $cartMdata);
			$cartMdata = str_replace(
				"{product_attribute}",
				$productHelper->makeAttributeQuotation(
					$rowItem[$i]->quotation_item_id,
					0,
					$rowItem[$i]->product_id,
					$row->quotation_status
				),
				$cartMdata
			);
			$cartMdata = str_replace(
				"{product_accessory}",
				$productHelper->makeAccessoryQuotation(
					$rowItem[$i]->quotation_item_id,
					$row->quotation_status
				),
				$cartMdata
			);

			// ProductFinderDatepicker Extra Field Start
			$cartMdata = $productHelper->getProductFinderDatepickerValue($cartMdata, $productId, $fieldArray);

			// ProductFinderDatepicker Extra Field End
			if ($row->quotation_status == 1 && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
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
			$cart .= $cartMdata;
		}

		// End for

		$message = $templateStart . $cart . $templateEnd;

		$search[]  = "{quotation_note}";
		$replace[] = $row->quotation_note;
		$search[]  = "{shopname}";
		$replace[] = Redshop::getConfig()->get('SHOP_NAME');
		$search[]  = "{quotation_id}";
		$replace[] = $row->quotation_id;
		$search[]  = "{quotation_number}";
		$replace[] = $row->quotation_number;
		$search[]  = "{quotation_date}";
		$replace[] = $redConfig->convertDateFormat($row->quotation_cdate);
		$search[]  = "{quotation_status}";
		$replace[] = $quotationHelper->getQuotationStatusName($row->quotation_status);

		$billAdd = '';

		if ($row->user_id != 0)
		{
			$message = $cartHelper->replaceBillingAddress($message, $row, true);
		}
		else
		{
			if ($row->quotation_email != "")
			{
				$billAdd .= JText::_("COM_REDSHOP_EMAIL") . ' : ' . $row->quotation_email . '<br />';
			}

			$message = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $message);

			if (strstr($message, "{quotation_custom_field_list}"))
			{
				$billAdd .= $extraAdminField->list_all_field_display(16, $row->user_info_id, 1, $row->quotation_email);
				$message = str_replace("{quotation_custom_field_list}", "", $message);
			}
			else
			{
				$message = $extraAdminField->list_all_field_display(16, $row->user_info_id, 1, $row->quotation_email, $message);
			}
		}

		$search[]     = "{billing_address}";
		$replace[]    = $billAdd;
		$totalLbl    = '';
		$subTotalLbl = '';
		$vatLbl      = '';

		if ($row->quotation_status != 1 || ($row->quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			$totalLbl    = JText::_('COM_REDSHOP_TOTAL_LBL');
			$subTotalLbl = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
			$vatLbl      = JText::_('COM_REDSHOP_QUOTATION_VAT');
		}

		$message = str_replace('{total_lbl}', $totalLbl, $message);
		$message = str_replace('{quotation_subtotal_lbl}', $subTotalLbl, $message);
		$message = str_replace('{quotation_vat_lbl}', $vatLbl, $message);
		$message = $cartHelper->replaceLabel($message);

		$search[] = "{quotation_note}";
		$replace[] = $row->quotation_note;

		if ($row->quotation_status == 1 && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))
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
			$tax = $row->quotation_tax;

			if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				$Discountvat             = (
					(float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') * $row->quotation_discount) /
					(1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
				);
				$row->quotation_discount = $row->quotation_discount - $Discountvat;
				$tax                     = $tax - $Discountvat;
			}

			if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'))
			{
				$sp_discount             = ($row->quotation_special_discount * ($row->quotation_subtotal + $row->quotation_tax)) / 100;
				$Discountspvat           = (
					$sp_discount * (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')) /
					(1 + (float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT')
				);
				$DiscountspWithotVat     = $sp_discount - $Discountspvat;
				$row->quotation_discount = $row->quotation_discount + $DiscountspWithotVat;
				$tax                     = $tax - $Discountspvat;
			}

			$quotationSubtotalExclVat       = $productHelper->getProductFormattedPrice($row->quotation_subtotal - $row->quotation_tax);
			$quotationSubtotalMinusDiscount = $productHelper->getProductFormattedPrice($row->quotation_subtotal - $row->quotation_discount);
			$quotationSubtotal              = $productHelper->getProductFormattedPrice($row->quotation_subtotal);
			$quotationTotal                 = $productHelper->getProductFormattedPrice($row->quotation_total);
			$quotationDiscount              = $productHelper->getProductFormattedPrice($row->quotation_discount);
			$quotationVat                   = $productHelper->getProductFormattedPrice($row->quotation_tax);
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

		$quotationDetailUrl = JURI::root() . 'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotationId . '&encr='
			. $row->quotation_encrkey;

		$search[]  = "{quotation_detail_link}";
		$replace[] = "<a href='" . $quotationDetailUrl . "'>" . JText::_("COM_REDSHOP_QUOTATION_DETAILS") . "</a>";

		$message   = str_replace($search, $replace, $message);
		$message   = self::imgInMail($message);
		$email     = $row->quotation_email;

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

			if (!JFactory::getMailer()->sendMail($from, $fromname, $email, $subject, $body, 1, null, $bcc))
			{
				JError::raiseWarning(21, JText::_('ERROR_SENDING_QUOTATION_MAIL'));
			}
		}

		if ($status != 0)
		{
			$quotationHelper->updateQuotationStatus($quotationId, $status);
		}

		return true;
	}

	/**
	 * Send newsletter confirmation mail
	 *
	 * @param   int  $subscriptionId  Subscription id
	 *
	 * @return  boolean
	 */
	public static function sendNewsletterConfirmationMail($subscriptionId)
	{
		if (Redshop::getConfig()->get('NEWSLETTER_CONFIRMATION'))
		{
			$config   = JFactory::getConfig();
			$url      = JURI::root();
			$db       = JFactory::getDBO();
			$mailBcc  = null;
			$mailInfo = self::getMailTemplate(0, "newsletter_confirmation");

			if (count($mailInfo) > 0)
			{
				$message = $mailInfo[0]->mail_body;
				$subject = $mailInfo[0]->mail_subject;

				if (trim($mailInfo[0]->mail_bcc) != "")
				{
					$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
				}
			}

			else
			{
				return false;
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

			$email     = $list->email;
			$subject   = str_replace($search, $replace, $subject);
			$message   = str_replace($search, $replace, $message);
			$message   = self::imgInMail($message);
			$from      = $config->get('mailfrom');
			$fromName  = $config->get('fromname');

			// Send the e-mail
			if ($email != "")
			{
				if (!JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $message, 1, null, $mailBcc))
				{
					JError::raiseWarning(21, JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	/**
	 * Send newsletter cancellation mail
	 *
	 * @param   string  $email  Email
	 *
	 * @return  boolean
	 */
	public static function sendNewsletterCancellationMail($email = "")
	{
		$config   = JFactory::getConfig();
		$mailInfo = self::getMailTemplate(0, "newsletter_cancellation");
		$mailBcc  = null;

		if (count($mailInfo) > 0)
		{
			$message = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
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
			JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $message, 1, null, $mailBcc);
		}

		return true;
	}

	/**
	 * Send ask question mail
	 *
	 * @param   int  $ansid  Answer id
	 *
	 * @return  boolean
	 */
	public static function sendAskQuestionMail($ansid)
	{
		$productHelper = productHelper::getInstance();
		$uri           = JURI::getInstance();
		$url           = $uri->root();
		$subject       = "";
		$dataAdd       = "";
		$mailBcc       = null;

		$mailInfo = self::getMailTemplate(0, "ask_question_mail");
		$ans      = $productHelper->getQuestionAnswer($ansid);

		if (count($mailInfo) > 0)
		{
			$data_add = $mailInfo[0]->mail_body;
			$subject  = $mailInfo[0]->mail_subject;

			// Only check if this field is not empty
			if (!empty($mailInfo[0]->mail_bcc))
			{
				$mailBcc  = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		if (count($ans) > 0)
		{
			$ans       = $ans[0];
			$fromName  = $ans->user_name;
			$from      = $ans->user_email;
			$email     = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			$question  = $ans->question;
			$answer    = "";
			$telephone = "";
			$address   = "";
			$productId = $ans->product_id;

			if ($ans->parent_id)
			{
				$answer  = $ans->question;
				$qDetail = $productHelper->getQuestionAnswer($ans->parent_id);

				if (count($qDetail) > 0)
				{
					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');

					$qDetail   = $qDetail[0];
					$question  = $qDetail->question;
					$email     = $qDetail->user_email;
					$productId = $qDetail->product_id;
					$address   = $qDetail->address;
					$telephone = $qDetail->telephone;
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

			if ($email && JFactory::getMailer()->sendMail($from, $fromName, $email, $subject, $dataAdd, 1, null, $mailBcc))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Send economic book invoice mail
	 *
	 * @param   int     $orderId         Order id
	 * @param   string  $bookInvoicePdf  Book invoice PDF
	 *
	 * @return  boolean
	 */
	public static function sendEconomicBookInvoiceMail($orderId = 0, $bookInvoicePdf = "")
	{
		if ($orderId == 0)
		{
			return false;
		}

		$redConfig      = Redconfiguration::getInstance();
		$orderFunctions = order_functions::getInstance();
		$config         = JFactory::getConfig();
		$from           = $config->get('mailfrom');
		$fromName       = $config->get('fromname');
		$mailInfo       = self::getMailTemplate(0, "economic_inoice");
		$dataAdd        = "economic inoice";
		$subject        = "economic_inoice";
		$mailBcc        = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$orderDetail = $orderFunctions->getOrderDetails($orderId);
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
			JFactory::getMailer()->sendMail($from, $fromName, $userBillingInfo->user_email, $subject, $dataAdd, 1, null, $mailBcc, $attachment);
		}

		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != '')
		{
			$sendto = explode(",", trim(Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			JFactory::getMailer()->sendMail($from, $fromName, $sendto, $subject, $dataAdd, 1, null, $mailBcc, $attachment);
		}

		return true;
	}

	/**
	 * Send request tax exempt mail
	 *
	 * @param   object  $data      Mail data
	 * @param   string  $username  Username
	 *
	 * @return  boolean
	 */
	public static function sendRequestTaxExemptMail($data, $username = "")
	{
		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != '')
		{
			$orderFunctions = order_functions::getInstance();
			$mailInfo       = self::getMailTemplate(0, "request_tax_exempt_mail");
			$dataAdd        = "";
			$subject        = "";
			$mailBcc        = null;

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
			$stateName   = $orderFunctions->getStateName($data->state_code);
			$countryName = $orderFunctions->getCountryName($data->country_code);

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

			return JFactory::getMailer()->sendMail($from, $fromName, $sendto, $subject, $dataAdd, 1, null, $mailBcc);
		}
	}

	/**
	 * Send catalog request
	 *
	 * @param   array  $catalog  Catalog data
	 *
	 * @return  boolean
	 */
	public static function sendCatalogRequest($catalog = array())
	{
		$data     = (object) $catalog;
		$db       = JFactory::getDBO();
		$mailInfo = self::getMailTemplate(0, "catalog");
		$dataAdd  = "";
		$subject  = "";
		$mailBcc  = null;

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
			->where($db->qn('media_section') . ' = ' . $db->q('catalog'))
			->where($db->qn('media_type') . ' = ' . $db->q('document'))
			->where($db->qn('section_id') . ' = ' . $db->q((int) $catalog->catalog_id))
			->where($db->qn('published') . ' = 1');

		$catalogData = $db->setQuery($query)->loadObjectlist();
		$attachment = array();

		for ($p = 0, $pn = count($catalogData); $p < $pn; $p++)
		{
			$attachment[] = REDSHOP_FRONT_DOCUMENT_RELPATH . 'catalog/' . $catalogData[$p]->media_name;
		}

		$dataAdd = str_replace("{name}", $catalog->name, $dataAdd);
		$dataAdd = self::imgInMail($dataAdd);

		if (!JFactory::getMailer()->sendMail($from, $fromName, $catalog->email, $subject, $dataAdd, 1, null, $mailBcc, $attachment))
		{
			return false;
		}

		return true;
	}
}
