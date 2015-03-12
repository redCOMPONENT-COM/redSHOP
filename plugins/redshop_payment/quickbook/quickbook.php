<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_PaymentQuickbook extends JPlugin
{
	/**
	 * Connection ticket JSON file default path from  site root - JPATH_SITE
	 */
	const CONNECTION_TICKET_PATH = '/../connectionticket.json';

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Set connection ticket from Intuit Quickbook Subscription Url and write into JSON file
	 *
	 * @return  void
	 */
	public function setConnectionTicket()
	{
		jimport( 'joomla.filesystem.file' );

		$buffer = json_encode($_REQUEST);

		// Write connection ticket outside of site root
		JFile::write(JPATH_SITE . self::CONNECTION_TICKET_PATH, $buffer);

		JFactory::getApplication()->close();
	}

	/**
	 * Read the JSON file and get connection ticket.
	 *
	 * @return  void
	 */
	public function getConnectionTicket()
	{
		jimport( 'joomla.filesystem.file' );

		$data = JFile::read(JPATH_SITE . self::CONNECTION_TICKET_PATH);

		if ($data)
		{
			echo $data;
		}
		else
		{
			echo JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_GET_CONNECTION_TICKET_FAIL');
		}

		JFactory::getApplication()->close();
	}

	/**
	 * This method will be triggered on before placing order to authorize or charge credit card
	 *
	 * @param   string  $element  Name of the payment plugin
	 * @param   array   $data     Cart Information
	 *
	 * @return  object  Authorize or Charge success or failed message and transaction id
	 */
	public function onPrePayment_Quickbook($element, $data)
	{
		if ($element != 'quickbook')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$values->transaction_id = $transaction_id;
		$values->message = $message;

		return $values;
	}

	/**
	 * This method will be trigger to charge the credit card based on transaction id
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $request  Request data from payment
	 *
	 * @return  object  Success or failed message, transaction id and order id
	 */
	public function onNotifyPaymentQuickbook($element, $request)
	{
		if ($element != 'quickbook')
		{
			return false;
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	/**
	 * This method will be trigger on order status change to capture order ammount.
	 *
	 * @param   string  $element  Name of plugin
	 * @param   array   $data     Order Information array
	 *
	 * @return  object  Success or failed message
	 */
	public function onCapture_PaymentQuickbook($element, $data)
	{
		$values->message = $message;

		return $values;
	}
}
