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

use Redshop\Order\Template;

/**
 * Mail Catalog helper
 *
 * @since  2.1.0
 */
class Invoice
{
	/**
	 * Send Order Invoice Mail
	 * Email Body and Subject is from "Invoice Mail" template section.
	 * Contains PDF attachment. PDF html is from "Invoice Mail PDF" section.
	 *
	 * @param   int     $orderId  Order Information Id
	 * @param   string  $email    Email
	 *
	 * @return  boolean           True on sending email successfully.
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendMail($orderId, $email = null)
	{
		$config = \JFactory::getConfig();

		if (!$config->get('mailonline'))
		{
			return false;
		}

		$mailSection = "invoice_mail";
		$mailBcc     = null;
		$mailInfo    = Helper::getTemplate(0, $mailSection);

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

		$mailTemplate    = self::replaceTemplate($orderId, $message, $subject);
		$mailBody        = $mailTemplate->body;
		$subject         = $mailTemplate->subject;
		$pdfTemplateFile = Helper::getTemplate(0, 'invoicefile_mail');

		// Init PDF template body
		$pdfTemplate = $mailBody;

		// Set actual PDF template if found
		if (count($pdfTemplateFile) > 0)
		{
			$pdfTemplate = self::replaceTemplate($orderId, $pdfTemplateFile[0]->mail_body)->body;
		}

		ob_clean();

		$invoiceAttachment = null;

		if (\RedshopHelperPdf::isAvailablePdfPlugins())
		{
			\JPluginHelper::importPlugin('redshop_pdf');
			$result = \RedshopHelperUtility::getDispatcher()->trigger('onRedshopOrderCreateInvoicePdf', array($orderId, $pdfTemplate, 'F', true));

			if (!in_array(false, $result, true))
			{
				$invoiceAttachment = JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $orderId . '/' . $result[0] . ".pdf";
			}
		}

		// Set the e-mail parameters
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		$billingAddresses = \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();

		if (empty($email))
		{
			$email = $billingAddresses->user_email;
		}

		Helper::imgInMail($mailBody);

		if ((\Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 2
			|| \Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& $email != ""
		)
		{
			if (!Helper::sendEmail(
				$from, $fromName, $email, $subject, $mailBody, true, null,
				$mailBcc, $invoiceAttachment, $mailSection, func_get_args()
			))
			{
				\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		if ((\Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 1 || \Redshop::getConfig()->get('INVOICE_MAIL_SEND_OPTION') == 3)
			&& \Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != ''
		)
		{
			$sendTo = explode(",", trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));

			if (!Helper::sendEmail(
				$from, $fromName, $sendTo, $subject, $mailBody, true, null,
				$mailBcc, $invoiceAttachment, $mailSection, func_get_args()
			))
			{
				\JError::raiseWarning(21, \JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		return true;
	}

	/**
	 * Replace invoice mail template tags and prepare mail body and pdf html
	 *
	 * @param   integer  $orderId  Order Information ID
	 * @param   string   $html     HTML template of mail body or pdf
	 * @param   string   $subject  Email Subject template, can be null for PDF
	 *
	 * @return  object             Object having mail body and subject. subject can be null for PDF type.
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function replaceTemplate($orderId, $html, $subject = null)
	{
		$row          = \RedshopEntityOrder::getInstance($orderId)->getItem();
		$discounts    = array_filter(explode('@', $row->discount_type));
		$discountType = '';

		foreach ($discounts as $discount)
		{
			$discountTypes = explode(':', $discount);

			if ($discountTypes[0] == 'c')
			{
				$discountType .= \JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}

			if ($discountTypes[0] == 'v')
			{
				$discountType .= \JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $discountTypes[1] . '<br>';
			}
		}

		if (empty($discountType))
		{
			$discountType = \JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		// Prepare subject replacement
		$searchSub[]  = "{order_id}";
		$replaceSub[] = $row->order_id;
		$searchSub[]  = "{order_number}";
		$replaceSub[] = $row->order_number;
		$searchSub[]  = "{invoice_number}";
		$replaceSub[] = $row->invoice_number;
		$searchSub[]  = "{shopname}";
		$replaceSub[] = \Redshop::getConfig()->get('SHOP_NAME');

		$billingAddresses = \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();
		$userFullName     = $billingAddresses->firstname . " " . $billingAddresses->lastname;
		$searchSub[]      = "{fullname}";
		$replaceSub[]     = $userFullName;
		$searchSub[]      = "{order_date}";
		$replaceSub[]     = \RedshopHelperDatetime::convertDateFormat($row->cdate);
		$subject          = str_replace($searchSub, $replaceSub, $subject);

		// Prepare mail body
		$search[]  = "{discount_type}";
		$replace[] = $discountType;
		$search[]  = "{invoice_number}";
		$replace[] = $row->invoice_number;

		$html = str_replace($search, $replace, $html);

		Helper::imgInMail($html);

		$html = Template::replaceTemplate($row, $html, true);
		$html = str_replace("{firstname}", $billingAddresses->firstname, $html);
		$html = str_replace("{lastname}", $billingAddresses->lastname, $html);
		$html = Template::replaceTemplate($row, $html, true);

		$object          = new \stdClass;
		$object->subject = $subject;
		$object->body    = $html;

		return $object;
	}

	/**
	 * Send economic book invoice mail
	 *
	 * @param   integer  $orderId        Order id
	 * @param   string   $bookInvoicePdf Book invoice PDF
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function sendEconomicBookInvoiceMail($orderId = 0, $bookInvoicePdf = "")
	{
		if ($orderId == 0)
		{
			return false;
		}

		$config      = \JFactory::getConfig();
		$from        = $config->get('mailfrom');
		$fromName    = $config->get('fromname');
		$mailSection = "economic_inoice";
		$mailInfo    = Helper::getTemplate(0, $mailSection);
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

		$orderDetail     = \RedshopEntityOrder::getInstance($orderId)->getItem();
		$userBillingInfo = \RedshopEntityOrder::getInstance($orderId)->getBilling()->getItem();

		$search  = array();
		$replace = array();

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
		$replace[] = \RedshopHelperDatetime::convertDateFormat($orderDetail->cdate);

		$dataAdd = str_replace($search, $replace, $dataAdd);

		Helper::imgInMail($dataAdd);

		$attachment[] = $bookInvoicePdf;

		if ($userBillingInfo->user_email != "")
		{
			Helper::sendEmail(
				$from, $fromName, $userBillingInfo->user_email, $subject, $dataAdd, 1,
				null, $mailBcc, $attachment, $mailSection, func_get_args()
			);
		}

		if (\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL') != '')
		{
			$sendTo = explode(",", trim(\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')));
			Helper::sendEmail($from, $fromName, $sendTo, $subject, $dataAdd, 1, null, $mailBcc, $attachment, $mailSection, func_get_args());
		}

		return true;
	}
}
