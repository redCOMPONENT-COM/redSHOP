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

class plgRedshop_paymentrs_payment_giropay extends JPlugin
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
	public function plgRedshop_paymentrs_payment_giropay(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_giropay');
		$this->_params = new JRegistry($this->_plugin->params);

	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_giropay')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$mainframe =& JFactory::getApplication();
		$class_path = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'gsGiropay.php';
		include $class_path;

		$gsGiropay = new gsGiropay;
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $plugin . DS . 'extra_info.php';
		include $paymentpath;
	}

	/*
	 *  Plugin onNotifyPayment method with the same name as the event will be called automatically.
	 */
	public function onNotifyPaymentrs_payment_giropay($element, $request)
	{
		if ($element != 'rs_payment_giropay')
		{
			return false;
		}

		$db = jFactory::getDBO();
		$request = JRequest::get('request');
		$transactionId = $request['order_id'];
		$gpCode = $request['gpCode'];
		$gpHash = $request['gpHash'];

		JPlugin::loadLanguage('com_redshop');

		$merchantId = $this->_params->get('merchant_id', '');
		$projectId = $this->_params->get('project_id', '');

		$verify_status = $this->_params->get('verify_status', '');
		$invalid_status = $this->_params->get('invalid_status', '');
		$auth_type = $this->_params->get('auth_type', '');
		$secret_password = $this->_params->get("secret_password");
		$debug_mode = $this->_params->get("debug_mode");
		$values = new stdClass;

		$class_path = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . $element . DS . $element . DS . 'gsGiropay.php';
		include $class_path;

		$gsGiropay = new gsGiropay;
		$hash = $gsGiropay->generateHash($merchantId . $projectId . $transactionId . $gpCode, $secret_password);

		$message = $gsGiropay->getCodeDescription($gpCode);

		if ($gpHash != $hash)
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;
		}

		// Neuen Bestellstatus ermitteln
		if ($gsGiropay->codeIsOK($gpCode))
		{
			$values->order_status_code = $verify_status;
			$values->order_payment_status_code = 'Paid';
			$values->log = $message;
			$values->msg = $message;

		}
		else
		{
			$values->order_status_code = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log = $message;
			$values->msg = $message;

		}

		$values->transaction_id = $transactionId;
		$values->order_id = $request['order_id'];

		return $values;
	}
}
