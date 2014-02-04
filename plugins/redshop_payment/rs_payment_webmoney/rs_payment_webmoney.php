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
//$app = JFactory::getApplication();
//$app->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
class plgRedshop_paymentrs_payment_webmoney extends JPlugin
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
	public function plgRedshop_paymentrs_payment_webmoney(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_webmoney');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_webmoney')
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

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_webmoney($element, $request)
	{
		if ($element != 'rs_payment_webmoney')
		{
			break;
		}

		$verify_status = $this->_params->get("verify_status");
		$invalid_status = $this->_params->get("invalid_status");

		$db = JFactory::getDbo();
		$request = JRequest::get('request');

		$post_msg = "";
		$pure_feedback = array();

		foreach ($_POST as $ipnkey => $ipnval)
		{
			$post_msg .= "$ipnkey=$ipnval&amp;";
		}

		$post_msg = "";

		foreach ($_POST as $ipnkey => $ipnval)
		{
			// Fix issue with magic quotes
			if (get_magic_quotes_gpc())
			{
				$ipnkey = stripslashes($ipnkey);
				$ipnval = stripslashes($ipnval);
			}
			// ^ Antidote to potential variable injection and poisoning
			if (!preg_match("/^[_0-9a-z-]{1,30}$/i", $ipnkey))
			{
				unset ($ipnkey);
				unset ($ipnval);
			}

			$pure_feedback[$ipnkey] = $ipnval;
		}

		// prerequest

		$wm_post_0 = trim($request['LMI_PREREQUEST']);
		$wm_post_4 = trim($request['LMI_MODE']);
		$wm_post_5 = trim($request['LMI_PAYER_PURSE']);
		$wm_post_6 = trim($request['LMI_PAYER_WM']);
		//transaction
		$wm_post_7 = trim($request['LMI_SYS_INVS_NO']);
		$wm_post_8 = trim($request['LMI_SYS_TRANS_NO']);
		$wm_post_9 = trim($request['LMI_SYS_TRANS_DATE']);
		$wm_post_10 = trim($request['LMI_HASH']);

		foreach ($pure_feedback as $wm_name => $wm_value)
		{
			if ($wm_name == LMI_PAYMENT_NO)
			{
				$invoice = $wm_value;
			}

			if ($wm_name == LMI_PAYMENT_AMOUNT)
			{
				$final_cost = $wm_value;
			}

			if ($wm_name == LMI_PREREQUEST)
			{
				$prerequest_mode = $wm_value;
			}
		}

		$account_number_stored = LMI_PAYEE_PURSE;
		$secret_key = WEBMONEY_SECRET_CODE;

		if ($prerequest_mode == "1")
		{
			$account_number_stored = LMI_PAYEE_PURSE;
			$account = trim($_POST['LMI_PAYEE_PURSE']);

			if ($account != $account_number_stored)
			{
				$err = 1;
				echo "ERR: WRONGFUL purse RECIPIENT " . $_POST['LMI_PAYEE_PURSE'];
				exit;
			}

			//retrieving invoice parameters from DB

			$sql = "SELECT *
		                FROM " . $this->_table_prefix . "orders
		                WHERE `order_id`='" . $invoice . "'";

			$query = $db->setQuery($sql);
			$order = $db->loadObject();

			$order_total_cost = $order->order_total;
			$shop_user = $order->user_id;
			$user_ip = $order->ip_address;
			$order_id = $order->order_id;

			if (!$order_id or $order_id == "")
			{
				$err = 1;
				echo "ERR: No such product";
				exit;
			}

			if ($order->order_total)
			{
				if (!$order_total_cost or $order_total_cost == "")
				{
					$err = 1;
					echo "ERR: NO SUCH PRICES";
					exit;
				}

				if ($order_total_cost != $final_cost)
				{
					$err = 1;
					$shop_user = $db_wm->f('user_id');
					$user_ip = $db_wm->f('ip_address');
					echo "ERR: Invalid amount " . $final_cost;
					exit;
				}
			}

			if (!$err)
			{
				echo "YES";
			}
		}
		else
		{
			$sql = "SELECT *
							FROM " . $this->_table_prefix . "orders
							WHERE `order_id`='" . $invoice . "'";

			$query = $db->setQuery($sql);
			$order = $db->loadObject();

			$order_total_cost = $order->order_total;
			$shop_user = $order->user_id;
			$user_ip = $order->ip_address;
			$order_id = $order->order_id;

			$common_string = $request['LMI_PAYEE_PURSE'] . $request['LMI_PAYMENT_AMOUNT'] . $request['LMI_PAYMENT_NO'] . $request['LMI_MODE'] . $request['LMI_SYS_INVS_NO'] . $request['LMI_SYS_TRANS_NO'] . $request['LMI_SYS_TRANS_DATE'] . $secret_key . $request['LMI_PAYER_PURSE'] . $request['LMI_PAYER_WM'];

			$hash = strtoupper(md5($common_string));

			if ($hash != $wm_post_10)
			{
				// FAILED: UPDATE THE ORDER STATUS to 'PENDING'
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->order_id = $request['orderid'];
			}
			else
			{
				// SUCCESS: UPDATE THE ORDER STATUS to 'CONFIRMED'
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->transaction_id = $wm_post_8;
				$values->order_id = $request['orderid'];
			}
		}

		return $values;
	}

	function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
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

	function onCapture_Paymentrs_payment_webmoney($element, $data)
	{
		return;
	}

}