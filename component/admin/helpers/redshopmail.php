<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Mail
 *
 * @since  2.0.0.3
 */
class redshopMail
{
	/**
	 * @deprecated  2.0.3
	 */
	public $_table_prefix = null;

	/**
	 * @deprecated  2.0.3
	 */
	public $db = null;

	/**
	 * @deprecated  2.0.3
	 */
	public $_carthelper = null;

	/**
	 * @deprecated  2.0.3
	 */
	public $_redhelper = null;

	/**
	 * @deprecated  2.0.3
	 */
	protected static $mailTemplates = array();

	/**
	 * @deprecated  2.0.3
	 */
	protected static $instance = null;

	/**
	 * Returns the redShopMail object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  redShopMail  The redShopMail object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since   1.6
	 *
	 * @deprecated  2.0.3
	 */
	public function __construct()
	{
		$this->_db              = JFactory::getDbo();
		$this->_table_prefix    = '#__redshop_';
		$this->_carthelper      = rsCarthelper::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_order_functions = order_functions::getInstance();
	}

	/**
	 * Method to get mail section
	 *
	 * @param   int     $tId        Template id
	 * @param   string  $section    Template section
	 * @param   string  $extraCond  Extra condition for query
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::getMailTemplate($tId, $section, $extraCond) instead
	 */
	public function getMailtemplate($tId = 0, $section = '', $extraCond = '')
	{
		return RedshopHelperMail::getMailTemplate($tId, $section, $extraCond);
	}

	/**
	 * sendOrderMail function.
	 *
	 * @param   int      $orderId    Order ID.
	 * @param   boolean  $onlyAdmin  send mail only to admin
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendOrderMail($orderId, $onlyAdmin) instead
	 */
	public function sendOrderMail($orderId, $onlyAdmin = false)
	{
		return RedshopHelperMail::sendOrderMail($orderId, $onlyAdmin);
	}

	/**
	 * send Order Special Discount Mail function.
	 *
	 * @param   int  $orderId  Order ID.
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendOrderSpecialDiscountMail($orderId) instead
	 */
	public function sendOrderSpecialDiscountMail($orderId)
	{
		return RedshopHelperMail::sendOrderSpecialDiscountMail($orderId);
	}

	/**
	 * Create multiple print invoice PDF
	 *
	 * @param   int  $oid  Order ID List.
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::createMultiprintInvoicePdf($oid) instead
	 */
	public function createMultiprintInvoicePdf($oid)
	{
		return RedshopHelperPdf::createMultiInvoice($oid);
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
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::replaceInvoiceMailTemplate($orderId, $html, $subject, $type) instead
	 */
	protected function replaceInvoiceMailTemplate($orderId, $html, $subject = null, $type = 'pdf')
	{
		return RedshopHelperMail::replaceInvoiceMailTemplate($orderId, $html, $subject, $type);
	}

	/**
	 * Send Order Invoice Mail
	 * Email Body and Subject is from "Invoice Mail" template section.
	 * Contains PDF attachement. PDF html is from "Invoice Mail PDF" section.
	 *
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  boolean  True on sending email successfully.
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendInvoiceMail($orderId) instead
	 */
	public function sendInvoiceMail($orderId)
	{
		return RedshopHelperMail::sendInvoiceMail($orderId);
	}

	/**
	 * Send registration mail
	 *
	 * @param   array  &$data  registration data
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendRegistrationMail(&$data) instead
	 */
	public function sendRegistrationMail(&$data)
	{
		return RedshopHelperMail::sendRegistrationMail($data);
	}

	/**
	 * Send tax exempt mail
	 *
	 * @param   string  $section   Mail section
	 * @param   array   $userInfo  User info data
	 * @param   string  $email     User email
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendTaxExemptMail($section, $userInfo, $email) instead
	 */
	public function sendTaxExemptMail($section, $userInfo = array(), $email = "")
	{
		return RedshopHelperMail::sendTaxExemptMail($section, $userInfo, $email);
	}

	/**
	 * Send subcription renewwal mail
	 *
	 * @param   array  $data  Mail data
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendSubscriptionRenewalMail($data) instead
	 */
	public function sendSubscriptionRenewalMail($data = array())
	{
		return RedshopHelperMail::sendSubscriptionRenewalMail($data);
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string  $message  Text message
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::imgInMail($message) instead
	 */
	public function imginmail($message)
	{
		return RedshopHelperMail::imgInMail($message);
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   int  $quotationId  Quotation id
	 * @param   int  $status       Status
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendQuotationMail($quotationId, $status) instead
	 */
	public function sendQuotationMail($quotationId, $status = 0)
	{
		return RedshopHelperMail::sendQuotationMail($quotationId, $status);
	}

	/**
	 * Send newsletter confirmation mail
	 *
	 * @param   int  $subscriptionId  Subscription id
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendNewsletterConfirmationMail($subscriptionId) instead
	 */
	public function sendNewsletterConfirmationMail($subscriptionId)
	{
		return RedshopHelperMail::sendNewsletterConfirmationMail($subscriptionId);
	}

	/**
	 * Send newsletter cancellation mail
	 *
	 * @param   string  $email  Email
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendNewsletterCancellationMail($email) instead
	 */
	public function sendNewsletterCancellationMail($email = "")
	{
		return RedshopHelperMail::sendNewsletterCancellationMail($email);
	}

	/**
	 * Send ask question mail
	 *
	 * @param   int  $ansid  Answer id
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendAskQuestionMail($ansid) instead
	 */
	public function sendAskQuestionMail($ansid)
	{
		return RedshopHelperMail::sendAskQuestionMail($ansid);
	}

	/**
	 * Send economic book invoice mail
	 *
	 * @param   int     $orderId         Order id
	 * @param   string  $bookInvoicePdf  Book invoice PDF
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf) instead
	 */
	public function sendEconomicBookInvoiceMail($orderId = 0, $bookInvoicePdf = "")
	{
		return RedshopHelperMail::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf);
	}

	/**
	 * Send request tax exempt mail
	 *
	 * @param   object  $data      Mail data
	 * @param   string  $username  Username
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendRequestTaxExemptMail($data, $username) instead
	 */
	public function sendRequestTaxExemptMail($data, $username = "")
	{
		return RedshopHelperMail::sendRequestTaxExemptMail($data, $username);
	}

	/**
	 * Send catalog request
	 *
	 * @param   array  $catalog  Catalog data
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperMail::sendCatalogRequest($catalog) instead
	 */
	public function sendCatalogRequest($catalog = array())
	{
		return RedshopHelperMail::sendCatalogRequest($catalog);
	}
}
