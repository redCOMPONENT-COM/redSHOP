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

use Redshop\Economic\Economic;

/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCheckoutEconomic
{
	/**
	 * @param   Tableorder_detail  $order           Order detail table object
	 * @param   object             $paymentMethod   Payment method object
	 *
	 * @return  boolean
	 *
	 * @since   2.0.7
	 */
	public static function integrate($order, $paymentMethod)
	{
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') != 1 || Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 2)
		{
			return false;
		}

		// Economic Integration start for invoice generate and book current invoice
		$economicPaymentTermsId = $paymentMethod->params->get('economic_payment_terms_id');
		$economicDesignLayout    = $paymentMethod->params->get('economic_design_layout');
		$isCreditCard             = $paymentMethod->params->get('is_creditcard', '');
		$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($paymentMethod->element);

		$economicdata['economic_payment_terms_id'] = $economicPaymentTermsId;
		$economicdata['economic_design_layout']    = $economicDesignLayout;
		$economicdata['economic_is_creditcard']    = $isCreditCard;
		$paymentName                              = $paymentMethod->element;
		$paymentArr                                = explode("rs_payment_", $paymentMethod->element);

		if (count($paymentArr) > 0)
		{
			$paymentName = $paymentArr[1];
		}

		$economicdata['economic_payment_method'] = $paymentName;
		Economic::createInvoiceInEconomic($order->order_id, $economicdata);

		if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 0)
		{
			$checkOrderStatus = ($isBankTransferPaymentType) ? 0 : 1;

			$bookinvoicepdf = Economic::bookInvoiceInEconomic($order->order_id, $checkOrderStatus);

			if (JFile::exists($bookinvoicepdf))
			{
				RedshopHelperMail::sendEconomicBookInvoiceMail($order->order_id, $bookinvoicepdf);
			}
		}

		return true;
	}
}