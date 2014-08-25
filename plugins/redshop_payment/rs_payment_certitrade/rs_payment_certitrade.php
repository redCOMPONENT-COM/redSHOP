<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminOrder');

class plgRedshop_paymentrs_payment_certitrade extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_certitrade(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_certitrade');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_certitrade')
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

	public function onNotifyPaymentrs_payment_certitrade($element, $request)
	{
		if ($element != 'rs_payment_certitrade')
		{
			return;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');
		$order_id = $request['merchant_order_id'];
		$Itemid = $request['Itemid'];
		$vendor_id = $this->_params->get("vendor_id");
		$verify_status = $this->_params->get("verify_status");
		$invalid_status = $this->_params->get("invalid_status");
		$secret_words = $this->_params->get("secret_words");
		$order_amount = $request["total"];
		$order_ekey = $request["key"];
		$accept = $_REQUEST["sid"];
		JPlugin::loadLanguage('com_redshop');

		// Get value from xml file
		$string_to_hash = $secret;

		// This should be YOUR vendor number
		$string_to_hash .= $request['sid'];

		// Append the order number, in this script it will always be 1
		if ($is_test)
		{
			$string_to_hash .= 1; //$_REQUEST['order_number'];
		}
		else
		{
			$string_to_hash .= $request['order_number'];
		}

		// Append the sale total
		$string_to_hash .= $request["total"];

		// Get a md5 hash of the string, uppercase it to match the returned key
		$hash_to_check = strtoupper(md5($string_to_hash));

		// Now validate on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		if ($order_ekey === $hash_to_check)
		{
			// Find the corresponding order in the database

			$db = JFactory::getDbo();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $data['order_id'] . "'";
			$db->setQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}

			// Only update the order information once
			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				// UPDATE THE ORDER STATUS to 'VALID'
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id = $transaction_id;
				$values->order_id = $order_id;
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$msg = JText::_('COM_REDSHOP_PHPSHOP_PAYMENT_ERROR');
		}

		return $values;
	}

	public function onCapture_Paymentrs_payment_certitrade($element, $data)
	{
		return;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}
}
