<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgRedshop_paymentrs_payment_payson extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_payson')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
		include $paymentpath;
	}

	function onNotifyPaymentrs_payment_payson($element, $request)
	{
		if ($element != 'rs_payment_payson')
		{
			return;
		}

		$request           = JRequest::get('request');
		$Itemid            = $request["Itemid"];
		$order_id          = $request["RefNr"];
		$okurl             = JURI::base() . "index.php?option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_payson&orderid=" . $order_id;
		$paysonref         = $request["Paysonref"];

		$fee               = $request["Fee"];
		$md5key            = $this->params->get('pays_md5', '');
		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$cancel_status     = $this->params->get('cancel_status', '');

		// Validate md5
		$strTestMD5String       = htmlspecialchars($okurl . $paysonref) . $md5key;
		$strMD5Hash             = md5($strTestMD5String);

		$compare_hash1          = $strMD5Hash;
		$compare_hash2          = $request['MD5'];
		$values->transaction_id = $paysonref;
		$values->order_id       = $order_id;

		if ($compare_hash1 != $compare_hash2)
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_PAYSON_CHECKOUT_FAILURE.');
			$values->msg = JText::_('COM_REDSHOP_PAYSON_CHECKOUT_FAILURE');

			return $values;
		}
		else
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
		}

		return $values;
	}
}
