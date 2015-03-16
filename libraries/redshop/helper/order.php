<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Order
 *
 * @since  1.5
 */
class RedshopHelperOrder
{
	/**
	 * Generate Invoice number in chronological order
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  object   Invoice number clean and formatted
	 */
	public static function generateInvoiceNumber($orderId)
	{
		$db    = JFactory::getDbo();

		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('invoice_number, order_status, order_payment_status')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);

		$db->setQuery($query);

		$orderInfo = $db->loadObject();
		$number    = $orderInfo->invoice_number;

		if ($number <= 0
			&& 'C' == $orderInfo->order_status
			&& 'Paid' == $orderInfo->order_payment_status)
		{

			$query = $db->getQuery(true)
					->select('MAX(invoice_number) as max_invoice_number')
					->from($db->qn('#__redshop_orders'));

			// Set the query and load the result.
			$db->setQuery($query);

			$maxInvoiceNo   = $db->loadResult();

			$firstInvoiceNo = (int) FIRST_INVOICE_NUMBER;

			$number = $maxInvoiceNo + $firstInvoiceNo + 1;

			self::updateInvoiceNumber($number, $orderId);
		}

		// Set invoice number negative for refunded / cancelled orders
		if (('R' == $orderInfo->order_status || 'X' == $orderInfo->order_status)
			&& $number > 0)
		{
			self::updateInvoiceNumber(($number * -1), $orderId);
		}

		$invoiceNo            = new stdClass;
		$invoiceNo->value     = $number;
		$invoiceNo->formatted = self::formatInvoiceNumber($number);

		return $invoiceNo;
	}

	/**
	 * Format the given invoice number
	 *
	 * @param   integer  $invoiceNo  Order Invoice Number
	 *
	 * @return  string   Formatted invoice number
	 */
	public static function formatInvoiceNumber($invoiceNo)
	{
		if ($invoiceNo == 0)
		{
			return '';
		}

		if (!INVOICE_NUMBER_TEMPLATE)
		{
			return $invoiceNo;
		}

		$isNegative = false;

		if ($invoiceNo < 0)
		{
			$isNegative = true;
			$invoiceNo *= -1;
		}

		$format = sprintf("%06d", $invoiceNo);
		$formattedInvoiceNo = str_replace("XXXXXX", $format, INVOICE_NUMBER_TEMPLATE);
		$formattedInvoiceNo = str_replace("xxxxxx", $format, INVOICE_NUMBER_TEMPLATE);
		$formattedInvoiceNo = str_replace("######", $format, INVOICE_NUMBER_TEMPLATE);

		$format = sprintf("%05d", $invoiceNo);
		$formattedInvoiceNo = str_replace("XXXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("#####", $format, $formattedInvoiceNo);

		$format = sprintf("%04d", $invoiceNo);
		$formattedInvoiceNo = str_replace("XXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("####", $format, $formattedInvoiceNo);

		$format = sprintf("%03d", $invoiceNo);
		$formattedInvoiceNo = str_replace("XXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("###", $format, $formattedInvoiceNo);

		$format = sprintf("%02d", $invoiceNo);
		$formattedInvoiceNo = str_replace("XX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("##", $format, $formattedInvoiceNo);

		if ($isNegative)
		{
			$formattedInvoiceNo = "-$formattedInvoiceNo";
		}

		return $formattedInvoiceNo;
	}

	/**
	 * Update invoice number in database
	 *
	 * @param   integer  $number   Order Invoice Number
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  void
	 */
	public static function updateInvoiceNumber($number, $orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->update($db->qn('#__redshop_orders'))
				->set($db->qn('invoice_number') . ' = ' . (int) $number)
				->where($db->qn('order_id') . ' = ' . (int) $orderId);

		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}
	}
}
