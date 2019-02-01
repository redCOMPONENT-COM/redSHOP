<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	/**
	 * Method to get mail section
	 *
	 * @param   integer  $templateId  Template id
	 * @param   string   $section     Template section
	 * @param   string   $extraCond   Extra condition for query
	 *
	 * @return  array
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Helper::getTemplate
	 */
	public static function getMailTemplate($templateId = 0, $section = '', $extraCond = '')
	{
		return Redshop\Mail\Helper::getTemplate($templateId, $section, $extraCond);
	}

	/**
	 * sendOrderMail function.
	 *
	 * @param   int     $orderId   Order ID.
	 * @param   boolean $onlyAdmin Send mail only to admin
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Mail\Order::sendMail
	 */
	public static function sendOrderMail($orderId, $onlyAdmin = false)
	{
		return Redshop\Mail\Order::sendMail($orderId, $onlyAdmin);
	}

	/**
	 * send Order Special Discount Mail function.
	 *
	 * @param   int $orderId Order ID.
	 *
	 * @return  boolean
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Order::sendOrderSpecialDiscountMail
	 */
	public static function sendOrderSpecialDiscountMail($orderId)
	{
		return Redshop\Mail\Order::sendSpecialDiscountMail($orderId);
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
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Invoice::replaceTemplate
	 */
	public static function replaceInvoiceMailTemplate($orderId, $html, $subject = null)
	{
		return Redshop\Mail\Invoice::replaceTemplate($orderId, $html, $subject);
	}

	/**
	 * Send Order Invoice Mail
	 * Email Body and Subject is from "Invoice Mail" template section.
	 * Contains PDF attachment. PDF html is from "Invoice Mail PDF" section.
	 *
	 * @param   int     $orderId  Order Information Id
	 * @param   string  $email    Email
	 *
	 * @return  boolean           True on sending email successfully.
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Invoice::sendMail
	 */
	public static function sendInvoiceMail($orderId, $email = null)
	{
		return Redshop\Mail\Invoice::sendMail($orderId, $email);
	}

	/**
	 * Send registration mail
	 *
	 * @param   array  $data  Registration data
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0
	 */
	public static function sendRegistrationMail(&$data)
	{
		return Redshop\Mail\User::sendRegistrationMail($data);
	}

	/**
	 * Send tax exempt mail
	 *
	 * @param   string $mailSection Mail section
	 * @param   array  $userInfo    User info data
	 * @param   string $email       User email
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 */
	public static function sendTaxExemptMail($mailSection, $userInfo = array(), $email = "")
	{
		return Redshop\Mail\User::sendTaxExempt($mailSection, $userInfo, $email);
	}

	/**
	 * Send subscriptions re-new mail
	 *
	 * @param   array $data Mail data
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 */
	public static function sendSubscriptionRenewalMail($data = array())
	{
		return Redshop\Mail\User::sendSubscriptionRenewal($data);
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string  $message  Text message
	 *
	 * @return  string
	 * @deprecated 2.1.0
	 * @see Redshop\Mail\Helper::imgInMail
	 */
	public static function imgInMail($message)
	{
		Redshop\Mail\Helper::imgInMail($message);

		return $message;
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   int $quotationId Quotation id
	 * @param   int $status      Status
	 *
	 * @return  boolean
	 * @throws  Exception
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Quotation::sendQuotationMail
	 */
	public static function sendQuotationMail($quotationId, $status = 0)
	{
		return Redshop\Mail\Quotation::sendMail($quotationId, $status);
	}

	/**
	 * Send newsletter confirmation mail
	 *
	 * @param   int $subscriptionId Subscription id
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 */
	public static function sendNewsletterConfirmationMail($subscriptionId)
	{
		return Redshop\Mail\Newsletter::sendConfirmationMail($subscriptionId);
	}

	/**
	 * Send newsletter cancellation mail
	 *
	 * @param   string  $email  Email
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 */
	public static function sendNewsletterCancellationMail($email = "")
	{
		return Redshop\Mail\Newsletter::sendCancellationMail($email);
	}

	/**
	 * Send ask question mail
	 *
	 * @param   int $answerId Answer id
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 */
	public static function sendAskQuestionMail($answerId)
	{
		return Redshop\Mail\AskQuestion::sendMail($answerId);
	}

	/**
	 * Send economic book invoice mail
	 *
	 * @param   int    $orderId        Order id
	 * @param   string $bookInvoicePdf Book invoice PDF
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 */
	public static function sendEconomicBookInvoiceMail($orderId = 0, $bookInvoicePdf = "")
	{
		return Redshop\Mail\Invoice::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf);
	}

	/**
	 * Send request tax exempt mail
	 *
	 * @param   object $data     Mail data
	 * @param   string $username Username
	 *
	 * @return  boolean
	 * @deprecated 2.1.0
	 */
	public static function sendRequestTaxExemptMail($data, $username = "")
	{
		return Redshop\Mail\User::sendRequestTaxExempt($data, $username);
	}

	/**
	 * Send catalog request
	 *
	 * @param   array  $catalog  Catalog data
	 *
	 * @return  boolean
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Mail\Catalog::sendRequest
	 */
	public static function sendCatalogRequest($catalog = array())
	{
		return Redshop\Mail\Catalog::sendRequest($catalog);
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
	 * @param   array   $argList     Function arguments
	 *
	 * @return  boolean          True on success. False otherwise.
	 * @deprecated  2.1.0
	 * @see Redshop\Mail\Helper::sendEmail
	 */
	public static function sendEmail($from, $fromName, $receiver, $subject, $body, $isHtml = true, $mailCC = null,
		$mailBCC = null, $attachment = null, $mailSection = '', $argList = array()
	)
	{
		return Redshop\Mail\Helper::sendEmail(
			$from, $fromName, $receiver, $subject, $body, $isHtml,
			$mailCC, $mailBCC, $attachment, $mailSection, $argList
		);
	}
}
