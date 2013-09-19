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

class plgRedshop_paymentrs_payment_dotpay extends JPlugin
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
	public function plgRedshop_paymentrs_payment_dotpay(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_dotpay');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dotpay')
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

	public function onNotifyPaymentrs_payment_dotpay($element, $request)
	{
		if ($element != 'rs_payment_dotpay')
		{
			return;
		}

		$db = JFactory::getDbo();
		$request = JRequest::get('request');
		$id = mysql_real_escape_string($request["id"]);
		$order_id = $request['order_id'];
		$t_id = mysql_real_escape_string($request["t_id"]);
		$control = mysql_real_escape_string($request['control']);
		$amount = mysql_real_escape_string($request['amount']);
		$email = mysql_real_escape_string($request['email']);
		$t_status = mysql_real_escape_string($request['t_status']);
		$md5 = mysql_real_escape_string($request['md5']);

		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_dotpay');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$cancel_status = $paymentparams->get('cancel_status', '');
		$dotpay_key = $paymentparams->get('dotpay_pin', '');

		if (isset($request["service"]))
		{
			$service = mysql_real_escape_string($request["service"]);
		}
		else
		{
			$service = null;
		}

		if (isset($request["code"]))
		{
			$code = mysql_real_escape_string($request["code"]);
		}
		else
		{
			$code = null;
		}

		if (isset($request["username"]))
		{
			$username = mysql_real_escape_string($request["username"]);
		}
		else
		{
			$username = null;
		}

		if (isset($request["password"]))
		{
			$password = mysql_real_escape_string($request["password"]);
		}
		else
		{
			$password = null;
		}

		$obl_md5 = md5(
			"" . $dotpay_key . ":" . $id . ":" . $control . ":" . $t_id . ":" . $amount . ":" . $email
				. ":" . $service . ":" . $code . ":" . $username . ":" . $password . ":" . $t_status . ""
		);

		if ($md5 != $obl_md5)
		{
			$values->transaction_id = '';
			$values->order_id = $order_id;
			$values->order_status_code = $cancel_status;
			$values->order_payment_status_code = 'UNPAID';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}
		else
		{
			if ($t_status == 1)
			{
				$values->order_status_code = $invalid_status;
				$values->order_payment_status_code = 'UNPAID';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
			elseif ($t_status == 2)
			{
				$values->order_id = $order_id;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'PAID';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}

		$values->transaction_id = $t_id;
		$values->order_id = $order_id;

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDbo();
		$res = false;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '"
			. $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_dotpay($element, $data)
	{
		return;
	}
}
