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
class plgRedshop_paymentrs_payment_amazoncheckout extends JPlugin
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
	public function plgRedshop_paymentrs_payment_amazoncheckout(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_amazoncheckout');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe = JFactory::getApplication();
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $plugin . DS . $plugin . DS . 'extra_info.php';
		include $paymentpath;
	}

	public function onNotifyPaymentrs_payment_amazoncheckout($element, $request)
	{
		if ($element != 'rs_payment_amazoncheckout')
		{
			return;
		}

		$db = jFactory::getDBO();
		$request = JRequest::get('request');
		JPlugin::loadLanguage('com_redshop');
		$amazon_parameters = $this->getparameters('rs_payment_amazoncheckout');
		$paymentinfo = $amazon_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$auth_type = $paymentparams->get('auth_type', '');

		$order_id = $request['orderid'];
		$Itemid = $request['Itemid'];
		$status = $request['status'];

		$values = new stdClass;

		if ($request['status'] == 'PS' && $request['operation'] == 'pay')
		{
			$tid = $request['transactionId'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id = $tid;
				$values->order_status_code = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
			else
			{
				$transaction_id = $tid;
				$values->order_status_code = 'P';
				$values->order_payment_status_code = 'Unpaid';
				$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
				$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			}
		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id = $order_id;

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db = JFactory::getDBO();
		$res = true;
		$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->SetQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = false;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_amazoncheckout($element, $data)
	{
		return;
	}
}
