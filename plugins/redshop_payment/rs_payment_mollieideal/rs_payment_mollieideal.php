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
require_once JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';

class plgRedshop_paymentrs_payment_mollieideal extends JPlugin
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
	public function plgRedshop_paymentrs_payment_mollieideal(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_mollieideal');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_mollieideal')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		echo $this->show_mollie_ideal($data['order_id']);
		/* 		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.$plugin.DS.'extra_info.php';
				include $paymentpath;
		*/
	}

	public function onNotifyPaymentrs_payment_mollieideal($element, $request)
	{
		if ($element != 'rs_payment_mollieideal')
		{
			return;
		}

		global $mainframe;
		$objOrder = new order_functions;
		$uri =& JURI::getInstance();
		$request = JRequest::get('request');

		$url = $uri->root();
		$Itemid = JRequest::getInt('Itemid');
		$msg = JText::_('IDEAL_PAYMENT_SUCCESSFUL');
		$tid = $request['transaction_id'];

		$quickpay_parameters = $this->getparameters('rs_payment_mollieideal');
		$paymentinfo = $quickpay_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		$verify_status = $paymentparams->get('verify_status', '');
		$invalid_status = $paymentparams->get('invalid_status', '');
		$cancel_status = $paymentparams->get('cancel_status', '');

		// Check Payment True/False
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_mollieideal' . DS
			. 'rs_payment_mollieideal' . DS . 'class.mollie.ideal.php';
		include $paymentpath;

		$mideal = new ideal;
		$response = $mideal->checkPayment($paymentparams->get("mollieideal_partner_id"), $tid, $paymentparams->get("mollieideal_is_test"));

		$user = JFactory::getUser();
		$order_id = $request['orderid'];

		$uri =& JURI::getInstance();
		$url = JURI::base();
		$uid = $user->id;
		$db = JFactory::getDBO();

		if ($response->payed == "false")
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}
		else
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = JText::_('COM_REDSHOP_ORDER_PLACED');
			$values->msg = JText::_('COM_REDSHOP_ORDER_PLACED');
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

	public function show_mollie_ideal($order_id)
	{
		// Valid order_id?
		$request = JRequest::get('request');

		if (!is_numeric($order_id))
		{
			return $this->_show_error('"' . JText::_('COM_REDSHOP_MOLLIEIDEAL_ORDER_ERROR') . '"');
		}

		require_once JPATH_BASE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_mollieideal' . DS
			. 'rs_payment_mollieideal' . DS . 'class.mollie.ideal.php';

		$db = jFactory::getDBO();

		// Paid already?
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'orders WHERE order_id = "' . (int) $order_id . '" AND order_payment_status = "Paid" LIMIT 1; ';

		$db->setQuery($query);
		$podata = $db->loadObject();

		if (isset($request['mideal']) and $request['mideal'] != 3)
		{
			if (count($podata) > 0)
				return $this->_show_div('<b>' . JText::_('COM_REDSHOP_MOLLIEIDEAL_PAID_ERROR') . ' <img src="http://www.mollie.nl/images/icons/ideal-25x22.gif" alt="" />.</b>');
		}

		// Does order?
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'orders WHERE order_id = "' . (int) $order_id . '"';
		$db->setQuery($query);
		$odata = $db->loadObject();

		if (count($odata) <= 0)
		{
			return $this->_show_error('"' . JText::_('COM_REDSHOP_MOLLIEIDEAL_ORDER_ERROR') . '"');
		}

		// Choose the right step to give back:
		if (!isset($request['stap']))
		{
			$request['stap'] = 1;
		}

		if (isset($request['mideal']) and $request['mideal'] == 3)
		{
			$request['stap'] = 3;
		}

		switch ($request['stap'])
		{
			case 1:
			default:
				return $this->show_bank_form($order_id);
				break;
			case 2:
				return $this->go_to_bank($order_id);
				break;
			case 3:
				return $this->validate_payment($order_id);
				break;
		}
	}

	public function _show_div($content)
	{
		return '<div style="width:300px;text-align:center;border:1px solid #000;background:#f9f9f9 url(http://www.mollie.nl/partners/images/betaalscherm-bg-1.jpg) bottom right;padding:30px 30px 60px 30px">' . $content . '</div><br />';
	}

	public function _show_error($content)
	{
		return $this->_show_div('<b style="color:red">' . $content . '</b>');
	}

	public function show_bank_form($order_id)
	{
		// Question bank list:
		$mideal = new ideal;
		$mideal->setPartnerID($this->_params->get("mollieideal_partner_id"));
		$mideal->setTestMode($this->_params->get("mollieideal_is_test"));
		$mbanks = $mideal->fetchBanks();
		$Itemid = JRequest::getInt('Itemid');

		// Valid bank list?
		if (!$mbanks)
		{
			return $this->_show_error('"' . JText::_('COM_REDSHOP_BANKLIST_ERROR') . '"');
		}

		// Genereer formulier om bank te selecteren:
		$form = '<b>Step 1 - ' . JText::_('COM_REDSHOP_MOLLIEIDEAL_STEP_HEADER') . ' <img src="http://www.mollie.nl/images/icons/ideal-25x22.gif" alt="" /></b><br /><br />' .
			'<form method="post" action="' . JURI::root() . 'index.php?option=com_redshop&view=checkout&format=final&stap=2&oid=' . (int) $order_id . '&Itemid=' . $Itemid . '"><input type="hidden" name="stap" value="2" />' .
			'<label>' . JText::_('COM_REDSHOP_MOLLIEIDEAL_SELECT_BANK') . '</label> <select name="bankid">';

		foreach ($mbanks as $mbankid => $mbankname)
		{
			$form .= '<option value="' . $mbankid . '">' . $mbankname . '</option>';
		}

		$form .= '</select><br /><br /><input type="hidden" name="payment_method_id" value="' . JRequest::getVar('payment_method_id') . '" />
			<input type="submit" value="' . JText::_('COM_REDSHOP_MOLLIEIDEAL_NEXTBUTTON') . '" /></form>';

		return $this->_show_div($form);
	}

	public function go_to_bank($order_id)
	{
		$request = JRequest::get('request');

		// Valid bankid?
		if (!isset($request['bankid']) or !is_numeric($request['bankid']))
		{
			return $this->_show_error('"' . JText::_('COM_REDSHOP_SELECTBANK_ERROR') . '"');
		}

		$db = jFactory::getDBO();

		$query = 'SELECT order_total FROM #__redshop_orders WHERE order_id = "' . (int) $order_id . '"';
		$db->setQuery($query);
		$odata = $db->loadObject();

		// Ask transaction:
		$mideal = new ideal;
		$mideal->setPartnerID($this->_params->get("mollieideal_partner_id"));
		$mideal->setBankID($request['bankid']);
		$mideal->setAmount($odata->order_total);
		$mideal->setDescription('Order ' . sprintf('%08d', $order_id) . ' - ' . substr($this->_params->get("mollieideal_company_name"), 0, 12));
		$mideal->setReturnUrl(JURI::base() . "index.php?option=com_redshop&view=order_detail&Itemid=$Itemid&oid=" . $order_id);
		$mideal->setReportUrl(JURI::base() . "index2.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_mollieideal&orderid=" . $order_id);

		$mideal->setTestMode($this->_params->get("mollieideal_is_test"));
		$created = $mideal->createPayment();

		// Transaction request?
		if (!$created
			|| !$mideal->transaction_id
			|| !$mideal->bankurl)
		{
			return $this->_show_error('"' . JText::_('COM_REDSHOP_MOLLIEIDEAL_TRANSACTION_ERROR') . '"');
		}

		// Show button to view the bank to go:
		if ($mideal->bankurl)
		{
			$query = "UPDATE " . $this->_table_prefix . "order_payment SET order_payment_number = '" . $order_id . "', order_payment_trans_id = '" . $mideal->transaction_id . "', order_payment_code = 0 where order_id = '" . $order_id . "'";
			$db->SetQuery($query);
			$db->Query();
			$form = '<b>Step 2 - ' . JText::_('COM_REDSHOP_MOLLIEIDEAL_STEP_HEADER') . ' <img src="http://www.mollie.nl/images/icons/ideal-25x22.gif" alt="" /></b><br /><br />' . JText::_('COM_REDSHOP_MOLLIEIDEAL_STEP2_DESCRIPTION') . '<br /><br />' .
				'<button onclick="window.location = \'' . $mideal->bankurl . '\'; return false">' . JText::_('COM_REDSHOP_MOLLIEIDEAL_CONTINUEBUTTON') . '</button>';
		}

		return $this->_show_div($form);
	}

	public function onCapture_Paymentrs_payment_mollieideal($element, $data)
	{
		return;
	}
}
