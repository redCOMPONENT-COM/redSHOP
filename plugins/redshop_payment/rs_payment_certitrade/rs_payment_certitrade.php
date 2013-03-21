<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
/*$mainframe =& JFactory::getApplication();
$mainframe->registerEvent( 'onPrePayment', 'plgRedshoppayment_authorize' );*/
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
class plgRedshop_paymentrs_payment_certitrade extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_certitrade(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_certitrade');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_certitrade')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'extra_info.php';
		include($paymentpath);
	}

	function onNotifyPaymentrs_payment_certitrade($element, $request)
	{
		if ($element != 'rs_payment_certitrade')
		{
			return;
		}

		$db = jFactory::getDBO();
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

		// get value from xml file
		$string_to_hash = $secret; //$params->get("TWOCO_SECRETWORD");

		// this should be YOUR vendor number
		$string_to_hash .= $request['sid']; //$params->get("TWOCO_LOGIN");

		// append the order number, in this script it will always be 1
		if ($is_test)
		{
			$string_to_hash .= 1; //$_REQUEST['order_number'];
		}
		else
		{
			$string_to_hash .= $request['order_number'];
		}

		// append the sale total
		$string_to_hash .= $request["total"];

		// get a md5 hash of the string, uppercase it to match the returned key
		$hash_to_check = strtoupper(md5($string_to_hash));

		//
		// Now validat on the MD5 stamping. If the MD5 key is valid or if MD5 is disabled
		//
		if ($order_ekey === $hash_to_check)
		{
			//
			// Find the corresponding order in the database
			//

			$db = JFactory::getDBO();
			$qv = "SELECT order_id, order_number FROM #__redshop_orders WHERE order_id='" . $data['order_id'] . "'";
			$db->SetQuery($qv);
			$orders = $db->LoadObjectList();

			foreach ($orders as $order_detail)
			{
				$d['order_id'] = $order_detail->order_id;
			}
			//
			// Only update the order information once
			//
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

	function onCapture_Paymentrs_payment_certitrade($element, $data)
	{
		return;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM " . $this->_table_prefix . "order_payment WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

}